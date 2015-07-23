<!--
author: jockchou
date: 2015-07-23
title: InnoDB脏页刷新机制
tags: InnoDB,MySQL,脏页
category: MySQL数据库
status: publish
summary: 我们知道InnoDB采用Write Ahead Log策略来防止宕机数据丢失，即事务提交时，先写重做日志，再修改内存数据页，这样就产生了脏页。既然有重做日志保证数据持久性，查询时也可以直接从缓冲池页中取数据，那为什么还要刷新脏页到磁盘呢？如果重做日志可以无限增大，同时缓冲池足够大，能够缓存所有数据，那么是不需要将缓冲池中的脏页刷新到磁盘。
-->

我们知道InnoDB采用Write Ahead Log策略来防止宕机数据丢失，即事务提交时，先写重做日志，再修改内存数据页，这样就产生了脏页。既然有重做日志保证数据持久性，查询时也可以直接从缓冲池页中取数据，那为什么还要刷新脏页到磁盘呢？如果重做日志可以无限增大，同时缓冲池足够大，能够缓存所有数据，那么是不需要将缓冲池中的脏页刷新到磁盘。但是，通常会有以下几个问题：

- 服务器内存有限，缓冲池不够用，无法缓存全部数据
- 重做日志无限增大成本要求太高
- 宕机时如果重做全部日志恢复时间过长

事实上，当数据库宕机时，数据库不需要重做所有的日志，只需要执行上次刷入点之后的日志。这个点就叫做Checkpoint，它解决了以上的问题：

- 缩短数据库恢复时间
- 缓冲池不够用时，将脏页刷新到磁盘
- 重做日志不可用时，刷新脏页

重做日志被设计成可循环使用，当日志文件写满时，重做日志中对应数据已经被刷新到磁盘的那部分不再需要的日志可以被覆盖重用。

InnoDB引擎通过LSN(Log Sequence Number)来标记版本，LSN是日志空间中每条日志的结束点，用字节偏移量来表示。每个page有LSN，redo log也有LSN，Checkpoint也有LSN。可以通过命令`show engine innodb status`来观察：

```
---
LOG
---
Log sequence number 11102619599
Log flushed up to   11102618636
Last checkpoint at  11102606319
0 pending log writes, 0 pending chkp writes
15416290 log i/o's done, 12.32 log i/o's/second
```

Checkpoint机制每次刷新多少页，从哪里取脏页，什么时间触发刷新？这些都是很复杂的。有两种Checkpoint，分别为：

- Sharp Checkpoint  
- Fuzzy Checkpoint  

Sharp Checkpoint发生在关闭数据库时，将所有脏页刷回磁盘。在运行时使用Fuzzy Checkpoint进行部分脏页的刷新。部分脏页刷新有以下几种：

- Master Thread Checkpoint
- FLUSH_LRU_LIST Checkpoint
- Async/Sync Flush Checkpoint
- Dirty Page too much Checkpoint


## Master Thread Checkpoint ##

Master Thread以每秒或每十秒的速度从缓冲池的脏页列表中刷新一定比例的页回磁盘。这个过程是异步的，不会阻塞查询线程。

## FLUSH_LRU_LIST Checkpoint ##

InnoDB要保证LRU列表中有100左右空闲页可使用。在InnoDB1.1.X版本前，要检查LRU中是否有足够的页用于用户查询操作线程，如果没有，会将LRU列表尾端的页淘汰，如果被淘汰的页中有脏页，会强制执行Checkpoint刷回脏页数据到磁盘，显然这会阻塞用户查询线程。从InnoDB1.2.X版本开始，这个检查放到单独的Page Cleaner Thread中进行，并且用户可以通过`innodb_lru_scan_depth`控制LRU列表中可用页的数量，默认值为1024。

## Async/Sync Flush Checkpoint ##

是指重做日志文件不可用时，需要强制将脏页列表中的一些页刷新回磁盘。这可以保证重做日志文件可循环使用。在InnoDB1.2.X版本之前，Async Flush Checkpoint会阻塞发现问题的用户查询线程，Sync Flush Checkpoint会阻塞所有查询线程。InnoDB1.2.X之后放到单独的Page Cleaner Thread。

## Dirty Page too much Checkpoint ##

脏页数量太多时，InnoDB引擎会强制进行Checkpoint。目的还是为了保证缓冲池中有足够可用的空闲页。其可以通过参数`innodb_max_dirty_pages_pct`来设置：

```
mysql> show variables like 'innodb_max_dirty_pages_pct';
+----------------------------+-------+
| Variable_name              | Value |
+----------------------------+-------+
| innodb_max_dirty_pages_pct | 90    |
+----------------------------+-------+
```
