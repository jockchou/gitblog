<!--
author: jockchou
date: 2015-07-23
title: 用redis实现计数器
tags: redis, 计数器
category: NoSQL开发
status: publish
summary:社交产品业务里有很多计数的地方，比如点赞数，评论数等。点赞操作MySQL执行+1这种写操作,写入非常频繁,对数据库写的压力很大,现整理一个内存存储优化方案供大家一起研究讨论。
-->

社交产品业务里有很多计数的地方，比如点赞数，评论数等。点赞操作MySQL执行+1这种写操作,写入非常频繁,对数据库写的压力很大,现整理一个内存存储优化方案供大家一起研究讨论。

社交产品业务里有很多统计计数的功能，比如：

- 用户： 总点赞数，关注数，粉丝数
- 帖子： 点赞数，评论数，热度
- 消息： 已读，未读，红点消息数
- 话题： 阅读数，帖子数，收藏数



## 统计计数的特点 ##

- 实时性要求高  
- 写的频率很高  
- 写的性能对MySQL是一个挑战  


可以采用redis来优化高频率写入的性能要求。

## redis优化方案一 ##

对于每一个实体的计数，设计一个hash结构的counter：

```
//用户
counter:user:{userID}
						->	praiseCnt: 100		//点赞数
						->	hostCnt: 200		//热度
						->	followCnt: 332		//关注数
						->	fansCnt: 123		//粉丝数


//帖子
counter:topic:{topicID}
						-> praiseCnt: 100		//点赞数
						-> commentCnt: 322		//评论数


//话题
counter:subject:{subjectID}
							-> favoCnt: 312		//收藏数
							-> viewCnt: 321		//阅读数
							-> searchCnt: 212	//搜索进入次数
							-> topicCnt: 312	//话题中帖子数 


```
类似这种计数器，随着产品功能的增加，也会越来越多，比如回复数，踩数，转发数什么的。

## redis相关的命令 ##

```
//获取指定userID的所有计数器
HGETALL counter:user:{userID}   

//获取指定userID的指定计数器
HMGET counter:user:{userID}  praiseCnt hostCnt 

//指定userID点赞数+1
HINCRBY counter:user:{userID}   praiseCnt 
```

缺点：
> 这样设计，如果要批量查询多个用户的数据，就比较麻烦，例如一次要查指定20个userID的计数器？只能循环执行 HGETALL counter:user:{userID}。

优点：
> 以实体聚合数据，方便数据管理


## redis优化方案二 ##

方案二是用来解决方案一的缺点的，依然是采用hash，结构设计是这样的：

```
counter:user:praiseCnt
						->  userID_1001: 100
						->  userID_1002: 200
						->  userID_1003: 332
						->  userID_1004: 123
								.......
						->  userID_9999: 213



counter:user:hostCnt
						->  userID_1001: 10
						->  userID_1002: 290
						->  userID_1003: 322
						->  userID_1004: 143
								.......
						->  userID_9999: 213


counter:user:followCnt
						->  userID_1001: 21
						->  userID_1002: 10
						->  userID_1003: 32
						->  userID_1004: 203
								.......
						->  userID_9999: 130

```


获取多个指定userID的点赞数的命令变成这样了：

```
HMGET counter:user:praiseCnt userID_1001 userID_1002
```

上面命令可以批量获取多个用户的点赞数，时间复杂度为O(n)，n为指定userID的数量。

优点：

> 解决了批量操作的问题

缺点：

> 当要获取多个计数器，比如同时需要praiseCnt，hostCnt时，要读多次，不过要比第一种方案读的次数要少。
> 一个hash里的字段将会非常宠大，HMGET也许会有性能瓶颈。


## 用redis管道（Pipelining)来优化方案一 ##

对于第一种方案的缺点，可以通过redis管道来优化，一次性发送多个命令给redis执行：

```
$userIDArray = array(1001, 1002, 1003, 1009);

$pipe = $redis->multi(Redis::PIPELINE);
foreach ($userIDArray as $userID) {
	$pipe->hGetAll('counter:user:' . $userID);
}

$replies = $pipe->exec();
print_r($replies);  
```

还有一种方式是在redis上执行lua脚本，前提是你必须要学会写lua。