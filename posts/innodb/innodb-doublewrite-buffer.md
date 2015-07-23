<!--
author: jockchou
date: 2015-07-23
title: InnoDB双写缓冲技术
tags: InnoDB,MySQL,双写缓冲
category: MySQL数据库
status: publish
summary: 先简单解释下什么是双写缓冲。InnoDB使用了一种叫做doublewrite的特殊文件flush技术，在把pages写到date files之前，InnoDB先把它们写到一个叫doublewrite buffer的连续区域内，在写doublewrite buffer完成后，InnoDB才会把pages写到data file的适当的位置。如果在写page的过程中发生意外崩溃，InnoDB在稍后的恢复过程中在doublewrite buffer中找到完好的page副本用于恢复。
-->

先简单解释下什么是双写缓冲。InnoDB使用了一种叫做doublewrite的特殊文件flush技术，在把pages写到date files之前，InnoDB先把它们写到一个叫doublewrite buffer的连续区域内，在写doublewrite buffer完成后，InnoDB才会把pages写到data file的适当的位置。如果在写page的过程中发生意外崩溃，InnoDB在稍后的恢复过程中在doublewrite buffer中找到完好的page副本用于恢复。



## 了解partial page write问题 ##

InnoDB的page size一般是16KB，其数据校验也是针对这16KB来计算的，将数据写入到磁盘是以page为单位进行操作的。操作系统写文件是以4KB作为单位的，那么每写一个InnoDB的page到磁盘上，操作系统需要写4个块。而计算机硬件和操作系统，在极端情况下（比如断电）往往并不能保证这一操作的原子性，16K的数据，写入4K时，发生了系统断电或系统崩溃，只有一部分写是成功的，这种情况下就是partial page write（部分页写入）问题。这时page数据出现不一样的情形，从而形成一个"断裂"的page，使数据产生混乱。这个时候InnoDB对这种块错误是无
能为力的.

有人会认为系统恢复后，MySQL可以根据redo log进行恢复，而MySQL在恢复的过程中是检查page的checksum，checksum就是pgae的最后事务号，发生partial page write问题时，page已经损坏，找不到该page中的事务号，就无法恢复。



## doublewrite buffer是什么？ ##

doublewrite buffer是InnoDB在tablespace上的128个页（2个区）大小是2MB。为了解决 partial page write问题，当MySQL将脏数据flush到data file的时候, 先使用memcopy将脏数据复制到内存中的doublewrite buffer，之后通过doublewrite buffer再分2次，每次写入1MB到共享表空间，然后马上调用fsync函数，同步到磁盘上，避免缓冲带来的问题，在这个过程中，doublewrite是顺序写，开销并不大，在完成doublewrite写入后，再将double write buffer写入各表空间文件，这时是离散写入。

所以在正常的情况下, MySQL写数据page时，会写两遍到磁盘上，第一遍是写到doublewrite buffer，第二遍是从doublewrite buffer写到真正的数据文件中。如果发生了极端情况（断电），InnoDB再次启动后，发现了一个page数据已经损坏，那么此时就可以从doublewrite buffer中进行数据恢复了。


## doublewrite的缺点是什么？ ##

位于共享表空间上的doublewrite buffer实际上也是一个文件，写共享表空间会导致系统有更多的fsync操作, 而硬盘的fsync性能因素会降低MySQL的整体性能，但是并不会降低到原来的50%。这主要是因为：

1. doublewrite是在一个连续的存储空间, 所以硬盘在写数据的时候是顺序写，而不是随机写，这样性能更高。
2. 将数据从doublewrite buffer写到真正的segment中的时候，系统会自动合并连接空间刷新的方式，每次可以刷新多个pages。



## 是否一定需要doublewrite ##

在一些情况下可以关闭doublewrite以获取更高的性能。比如在slave上可以关闭，因为即使出现了partial page write问题，数据还是可以从中继日志中恢复。设置`InnoDB_doublewrite=0`即可关闭doublewrite buffer。

