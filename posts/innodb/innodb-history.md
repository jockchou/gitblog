<!--
author: jockchou
date: 2015-07-23
title: InnoDB历史
tags: InnoDB,MySQL,数据库
category: MySQL数据库
status: publish
summary: InnoDB是MySQL的数据库引擎之一，为MySQL AB发布binary的标准之一。InnoDB由Innobase Oy公司所开发，2006年五月时由甲骨文公司并购。与传统的ISAM与MyISAM相比，InnoDB的最大特色就是支持了ACID兼容的事务（Transaction）功能，类似于PostgreSQL。
-->

InnoDB是MySQL的数据库引擎之一，为MySQL AB发布binary的标准之一。InnoDB由Innobase Oy公司所开发，2006年五月时由甲骨文公司并购。与传统的ISAM与MyISAM相比，InnoDB的最大特色就是支持了ACID兼容的事务（Transaction）功能，类似于PostgreSQL。

InnoDB和MySQL的一些曲折历史：

- InnoDB引擎是由InnobaseOy公司开发。  
- 2001年，Innobase公司开始与MySQL AB公司进行合作并开源InnoDB存储引擎的代码。  
- Oracle公司在2005年以迅雷不及掩耳之势收购了Innobase公司。  
- 2008年Sun公司收购MySQL AB公司。  
- 2009年Oracle收购Sun公司，而MySQL数据库最终到了Oracle的手中，InnoDB存储引擎和MySQL终于又在一起了。  

2008年发布InnoDB Plugin，适用于MySQL 5.1版本，这是Oracle创建的下一代InnoDB引擎，其拥有者是InnoDB而不是MySQL。

Google，Percona，Sun Microsystems等公司为InnoDB Plugin提供了patch补丁包，使其性能发挥至极致。

MySQL 5.1.38前的版本中，当你需要安装InnoDB Plugin时，必须下载Plugin的文件，解压后再进行一系列的安装。

从MySQL 5.1.38开始，MySQL包含了2个不同版本的InnoDB存储引擎—一个是旧版本的引擎，称之为build-in innodb；另一个是1.0.4版本的InnoDB存储引擎。如果你想使用新的InnDB Plugin引擎，只需在配置文件做如下设置：

```
[mysqld]  
ignore-builtin-innodb  
plugin-load=innodb=ha_innodb_plugin.so;  
innodb_trx=ha_innodb_plugin.so; 
innodb_locks=ha_innodb_plugin.so;  
innodb_cmp=ha_innodb_plugin.so;  
innodb_cmp_reset=ha_innodb_plugin.so;   
innodb_cmpmem=ha_innodb_plugin.so;  
innodb_cmpmem_reset=ha_innodb_plugin.so;  
```

MySQL5.5使用InnoDB作为默认的引擎，你不再需要进行任何安装。这个引擎就是InnoDB Plugin，已经成为默认引擎随MySQL一起分布。为了更好的利用引擎的特性，推荐如下配置：
```
innodb_file_per_table=1
innodb_file_format=barracuda
innodb_strict_mode=1
```


innodb_strict_mode=1用来开启innodb严格模式，在严格模式下innodb在一些条件下会直接报错，而不是发出警告或者保守处理某些语法。
innodb_file_format指定文件格式，InnoDB当前支持两种文件各式，Antelope和Barracuda。Antelope就老的文件格式，它是MySQL5.5默认的格式，以最大限度兼容之前的版本，支持COMPACT和REDUNDANT行格式。Barracuda是新的文件格式，额外支持DYNAMIC和COMPRESSED行格式，同时TRUNCATE TABLE的速度更快。
innodb_file_per_table=1表示每个表使用独立的表空间，这样做有很多好处，以后会讲到。