# Journaling日志机制 #

----------

运行MongoDB如果开启了journaling日志功能，MongoDB先在内存保存写操作，并记录journaling日志到磁盘，然后才会把数据改变刷入到磁盘上的数据文件。为了保证journal日志文件的一致性，写日志是一个原子操作。本文将讨论MongoDB中journaling日志的实现机制。

# Journal日志文件 #

如果开启了journal日志功能，MongoDB会在数据目录下创建一个`journal`文件夹，用来存放预写重放日志。同时这个目录也会有一个`last-sequence-number`文件。如果MongoDB安全关闭的话，会自动删除此目录下的所有文件，如果是崩溃导致的关闭，不会删除日志文件。在MongoDB进程重启的过程中，journal日志文件用于自动修复数据到一个一致性的状态。

journal日志文件是一种往文件尾不停追加内容的文件，它命名以`j._`开头，后面接一个数字（从0开始）作为序列号。如果文件超过1G大小，MongoDB会新建一个journal文件`j._1`。只要MongoDB把特定日志中的所有写操作刷入到磁盘数据文件，将会删除此日志文件。因为数据已经持久化，不再需要用它来重放恢复数据了。journal日志文件一般情况下只会生成两三个，除非你每秒有大量的写操作发生。

如果你需要的话，你可以使用`storage.smallFiles`参数来配置journal日志文件的大小。比如配置为`128M`。

# Journaling机制的存储视图 #

Journaling功能用到了MongoDB存储层数据集内部的两个视图。

`shared`视图保存数据修改操作，用于刷入到磁盘数据文件。`shared`视图是MongoDB中唯一访问磁盘数据文件的视图。`mongod`进程请求操作系统把磁盘数据文件映射到虚拟内存的`shared`视图。操作系统只是映射数据与内存关系，并不马上加载数据到内存。当查询需要的时候，才会加载数据到内存，即按需加载。

`private`视图存储用于查询操作的数据。同时`private`视图也是MongoDB执行写操作的第一个地方。一旦journal日志提交完成，MongoDB会复制`private`视图中的改变到`shared`视图，再通过`shared`视图将数据刷入到磁盘数据文件。

`journal`视图是一个用来保证新的写操作的磁盘视图。当MongoDB在`private`视图执行完写操作后，在数据刷入磁盘之前，会先记录`journal`日志。`journal`日志保证了持久性。如果`mongod`实例在数据刷入磁盘之前崩溃，重启过程中`journal`日志会重放并写入`shared`视图，最终刷入磁盘持久化。

# Journaling如何纪录写操作 #

MongoDB采用`group commits`方式将写操作批量复制到`journal`日志文件中。`group commits`提交方式能够最小化journal日志机制对性能的影响。因此`group commits`方式在提交过程中必须阻塞所有写入。`commitIntervalMs`参数可以用于配置日志提交的频率，默认是100ms。

Journaling存储以下原始操作：

- 文档插入或更新  
- 索引修改  
- 命名空间文件元数据的修改  
- 创建和者删除数据库或关联的数据文件  

当发生写操作，MongoDB首先写入数据到内存中的`private`视图，然后批量复制写操作到`journal`日志文件。写个`journal`日志实体来用描述写操作改变数据文件的哪些字节。

MongoDB接下来执行`journal`的写操作到`shared`视图。此时，`shared`视图与磁盘数据文件不一样。

默认每60s钟，MongoDB请求操作系统将`shared`视图刷入到磁盘。使数据文件更新到最新的写入状态。如果系统内存资源不足的时候，操作系统会选择以更高的频率刷入`shared`视图到磁盘。

MongoDB刷入数据文件完成后，会通知`journal`日志已经刷入。一旦`journal`日志文件只包含全部刷入的写操作，不再用于恢复，MongoDB会将它删除或者作为一个新的日志文件再次使用。

作为journaling机制的一部分，MongoDB会例行性请求操作系统重新将`shared`视图映射到`private`视图，为了节省物理内存。一旦发生重映射，操作系统能够识别到可以在`private`视图和`shared`视图共享的内存页映射。

# 小结 #

Journaling是MongoDB中非常重要的一项功能，类似于关系数据库中的事务日志。Journaling能够使MongoDB数据库由于意外故障后快速恢复。MongoDB2.0版本后默认开启了Journaling日志功能，`mongod`实例每次启动时都会检查`journal`日志文件看是否需要恢复。由于提交`journal`日志会产生写入阻塞，所以它对写入的操作有性能影响，但对于读没有影响。在生产环境中开启Journaling是很有必要的。