<!--
author: jockchou
date: 2015-07-23
title: MySQL数据库和ACID模型
tags: ACID，MySQL，数据库
category: MySQL数据库
status: publish
summary: ACID模型是一组强调高可靠性的数据库系统设计原则。InnoDB存储引擎坚持ACID原则，确保即使在软件崩溃甚至是硬件故障的情况下，数据也不会损坏。当你需要依赖兼容ACID原则的业务时，你不必重复造轮子去实现一致性检查和崩溃恢复机制。在一些情况下，如果你有额外的安全保证机制，可靠的硬件条件，或者应用能够容忍少量的数据丢失和不一致，你可以调整MYSQL设置，牺牲掉ACID的一些可靠性换取更高的性能和数据吞吐量。
-->

ACID模型是一组强调高可靠性的数据库系统设计原则。InnoDB存储引擎坚持ACID原则，确保即使在软件崩溃甚至是硬件故障的情况下，数据也不会损坏。当你需要依赖兼容ACID原则的业务时，你不必重复造轮子去实现一致性检查和崩溃恢复机制。在一些情况下，如果你有额外的安全保证机制，可靠的硬件条件，或者应用能够容忍少量的数据丢失和不一致，你可以调整MYSQL设置，牺牲掉ACID的一些可靠性换取更高的性能和数据吞吐量。

## ACID原则 ##

- A: atomicity      (原子性)   
- C: consistency	(一致性)    
- I: isolation	   （隔离性）  
- D: durability	   （持久性） 


## Atomicity(原子性） ##
原子性主要涉及到InnoDB事务。相关的MYSQL特征包括：

- Autocommit  
- COMMIT语句  
- ROLLBACK语句  

## Consistency(一致性) ##
一致性主要涉及到InnoDB内部软件崩溃时的数据保护恢复机器。相关的MYSQL特征包括：

- InnoDB双写缓冲  
- InnoDB崩溃恢复  

## Isolation(隔离性) ##
隔离性主要涉及到InnoDB具体事务的隔离级别。相关的MYSQL特征包括：

- Autocommit  
- SET ISOLATION LEVEL语句  
- InnoDB锁的低层细节。在性能调优时，你可以通过INFORMATION_SCHEMA表看到这些细节   

## Durability(持久性) ##

持久性主要涉及MySQL软件特征与你实际硬件配置的相互作用。这个特性更多的取决于你的CPU，网络，和存储设备的能力。相关的MYSQL特征包括：

- innodb_doublewrite  
- innodb_flush_log_at_trx_commit  
- sync_binlog  
- innodb_file_per_table  
- 磁盘驱动  
- 操作系统是否支持fsync()系统调用  
- 备份策略
- 分布式  
