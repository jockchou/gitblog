<!--
author: jockchou
date: 2015-07-23
title: Mosquitto服务器日志分析总结
tags: Mosquitto, 服务器
category: Linux服务器
status: publish
summary: Mosquitto是一款实现了消息推送协议MQTT v3.1的开源消息代理软件，提供轻量级的，支持可发布/可订阅的的消息推送模式，使设备对设备之间的短消息通信变得简单，比如现在应用广泛的低功耗传感器，手机、嵌入式计算机、微型控制器等移动设备。
-->

Mosquitto是一款实现了消息推送协议MQTT v3.1的开源消息代理软件，提供轻量级的，支持可发布/可订阅的的消息推送模式，使设备对设备之间的短消息通信变得简单，比如现在应用广泛的低功耗传感器，手机、嵌入式计算机、微型控制器等移动设备。

Mosquitto官网：[http://mosquitto.org/](http://mosquitto.org/ "mosquitto")  
MQTT协议：[http://mqtt.org/](http://mqtt.org/ "mqtt")

----------

## 客户端连接日志 ##

```
1403334375: New connection from 121.201.8.163 on port 1883.

1403334375: New client connected from 121.201.8.163 as zhoujing (c0, k60, ujoyo).

1403334375: Sending CONNACK to zhoujing (0)
```

以上是一个客户端正常连接的日志。

- 第一行：服务器收到一个新的连接请求

> 1403334375: 表示连接的时间点  
> 121.201.8.163: 客户端的IP地址  
> 1883: 连接的服务器端口号  


- 第二行：服务器为客户端建立连接

> zhoujing (c0, k60, ujoyo)： 客户端连接指定的ClientID为'zhoujing'   
> c0: 表示cleanSession=false，不清除session  
> k60: 表示keep-alive=60s  
> ujoyo: 表示使用的服务器账号为joyo  

- 第三行：发送连接ACK包给客户端


## 客户端订阅过程日志 ##

```
1403334375: Received SUBSCRIBE from zhoujing
1403334375: jiji/chat/9 (QoS 2)
1403334375: zhoujing 2 jiji/chat/9
1403334375: Sending SUBACK to zhoujing
```

- 第一行：服务器收到一个来自ClientID为zhoujing的订阅请求
- 第二行：服务器识别zhoujing订阅的主题为 jiji/chat/9，指定的QOS=2（有且只发送一次）
- 第三行：给ClientID=zhoujing客户端发送订阅ACK回包

## 服务器发送内容给客户端日志 ##

```
1403334378: Sending PUBLISH to zhoujing (d0, q2, r0, m1, 'jiji/chat/9', ... (396 bytes))
1403334378: Received PUBREC from zhoujing (Mid: 1)
1403334378: Sending PUBREL to zhoujing (Mid: 1)
1403334378: Received PUBCOMP from zhoujing (Mid: 1)
```

- 第一行：服务器正在发送一个消息给ClientID=zhoujing的客户端，消息体大小为396 bytes，消息主题为‘jiji/chat/9’ 

> (d0, q2, r0, m1)的解释, 以下参数具体含义，参考MQTT协议  
> d: 表示mqtt报头的DUP字段  
> q: 表示的QOS字段  
> r: 表示的是RETAIN  
> m: 表示的是消息ID,即mid   

- 第二行：发布收稿阶段，有保证的交付第一部分
- 第三行：出版发行阶段，有保证的交付第二部分
- 第四行：发布完成，有保证的交付第三部分，至此一个内容发部过程完成



## 客户端主动断开连接的日志 ##

客户端主动断开连接的只有一行日志，很简单。如下所示：

```
1403334380: Received DISCONNECT from JY_API_PUSH_CLIENT
```

## 接收客户端发布内容的过程日志 ##

```
1403334389: Received PUBLISH from JY_API_PUSH_CLIENT (d0, q2, r0, m1, 'jiji/chat/9', ... (396 bytes))
1403334389: Sending PUBREC to JY_API_PUSH_CLIENT (Mid: 1)
1403334389: Received PUBREL from JY_API_PUSH_CLIENT (Mid: 1)
1403334389: Sending PUBCOMP to JY_API_PUSH_CLIENT (Mid: 1)
```

- 第一行：接收来自ClientID=JY_API_PUSH_CLIENT的发布请求，发布的消息主题为'jiji/chat/9'，消息大小为396 bytes
- 第二行：服务器发送PUBREC给客户端，此过程与发送消息给客户端正好对称，只不过是Received和Sending的对象反过来而已
- 第三行：服务器接收到客户端已经释放的命令，内容交付第二步完成
- 第四行：通知客户端，接收完成，至此一个消息发布完成，内容交付第三步完成

## 同一个ClientID重复连接时 ##

```
1403334510: New connection from 121.201.7.150 on port 1883.
1403334510: Client JY_API_PUSH_CLIENT already connected, closing old connection.
```

## 接收客户端心跳包 ##

```
1403336716: Received PINGREQ from 36383A64663A6464003V0
1403336716: Sending PINGRESP to 36383A64663A6464003V0
```

## 客户端连接超时，服务器主动清除连接信息 ##

```
1403336671: Client 33303A33393A3236003V0 has exceeded timeout, disconnecting.
```

## 客户端socket异常时的日志 ##

```
1403337602: Socket error on client 351BBJKFX62C1, disconnecting.
1403337602: Socket error on client 351BBJKFX62C0, disconnecting.
```




		


		
