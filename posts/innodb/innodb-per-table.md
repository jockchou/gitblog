<!--
author: jockchou
date: 2015-07-23
title: InnoDB独立表空间
tags: InnoDB,MySQL,数据库,表空间
category: MySQL数据库
status: publish
summary: 使用过MySQL的同学，刚开始接触最多的莫过于MyISAM表引擎了，这种引擎的数据库会分别创建三个文件：数据文件(.MYD)，索引文件(.MYI)和表结构文件(.frm)。我们可以将某个数据库目录直接迁移到其他数据库也可以正常工作。然而，当你使用InnoDB的时候，一切都变了。
-->

使用过MySQL的同学，刚开始接触最多的莫过于MyISAM表引擎了，这种引擎的数据库会分别创建三个文件：数据文件(.MYD)，索引文件(.MYI)和表结构文件(.frm)。我们可以将某个数据库目录直接迁移到其他数据库也可以正常工作。然而，当你使用InnoDB的时候，一切都变了。

InnoDB默认会将所有的数据库的数据存储在一个共享表空间：ibdata1文件中，这样就感觉不爽，增删数据库的时候，ibdata1文件不会自动收缩，单个数据库的备份也将成为问题。通常只能将数据使用mysqldump导出，然后再导入解决这个问题。

在MySQL的配置文件[mysqld]部分，增加innodb_file_per_table参数。可以修改InnoDB为独立表空间模式，每个数据库的每个表都会生成一个数据空间。

## 独立表空间优点： ##

- 每个表都有自已独立的表空间  
- 每个表的数据和索引都会存在自已的表空间中  
- 可以实现单表在不同的数据库中移动  
- drop table操作会自动回收表空间  


使用独立表空间的表，不管怎么删除，表空间的碎片不会太严重的影响数据库整体性能，而且还有机会处理。

## 独立表空间缺点： ##

- 单表增加过大，如超过100个G
- 表空间文件不能分多文件存放在不同磁盘中

# 结论 #

共享表空间insert操作上略有优势，其它都没独立表空间表现好。当启用独立表空间时，请合理调整一下`innodb_open_files`参数。

独立表空间开启方法，在my.cnf中[mysqld]下设置：
```
innodb_file_per_table=1
```

查看是否开启：
```
mysql> show variables like ‘%per_table%’;
```

关闭独享表空间：

```
innodb_file_per_table=0
```

通常这三个参数放在一起设置：

```
innodb_file_per_table=1
innodb_file_format=barracuda
innodb_strict_mode=1
```