# 安装MongoDB #

MongoDB能够运行在多种平台，并支持32位和64的构架。

由于本教程是在Windows上开展，所以只讲Windows上的安装。其他平台参考MongoDB官方手册。

## 在Windows上安装MongoDB ##

MongoDB2.2版本之前不支持Windows XP，本教程使用的版本是最新的3.0的版本。为了方便操作和理解，所以选择在Windows讲解，生产环境请使用Linux版本。

MongoDB支持32位和64的CPU构架。32位版本只是为了适应老的操作系统，用于开发学习，它有很多局限性，仅支持数据库少于2G的系统。64位版本还有一个Legacy版本，它不包括最近的性能优化，不建议使用。

在这里我们直接下载这个64位版本（[MongoDB for Windows 64-bit](https://fastdl.mongodb.org/win32/mongodb-win32-x86_64-2008plus-ssl-3.0.3-signed.msi)）。安装过程非常简单，跟安装其他软件一样，一直下一步就行了。比如我的机器上安装到了`C:\mongodb`，在安装目录下面有一个`bin`目录。这个目录包含了MongoDB所有的命令和工具集合，把它配置到环境变量PATH中。如果你选择其他目录安装，请确保路径上没有空格，不然到时候会有很多坑。


## 设置MongoDB环境 ##

MongoDB需要一个目录来保存数据，默认的数据目录是`\data\db`。在我的机器上使用下面的目录作为数据目录。
```
C:\data\mongo
```

你可以在启动MongoDB的时候为它指定一个数据目录。例如我们用如下命令启动MongoDB:
```
C:\mongodb\bin\mongod.exe --dbpath C:\data\mongo
```

数据目录不应该包含空格，否则要用 mongod.exe --dbpath "C:\data\mongo"。这些启动参数都是可以放到配置文件当中的，启动的时候指定配置文件。由于配置文件的参数比较多，我们这里暂时不需要理解那么多，先不使用。
```
mongod.exe --config /etc/mongod.conf
```

## 运行MongoDB ##

启动MongoDB，使用`mongod.exe`命令，例如：

```
C:\mongodb\bin\mongod.exe --dbpath C:\data\mongo
```

以上命令用来启动MongoDB服务主进程，并指定数据目录。执行完此命令后，在控制台会打印一系列的启动信息，包括MongoDB的版本，是否根据journal日志执行recovery，进程的信号，操作系统的信息等乖。最后一行会提示你启动成功，监听了27017端口，等待连接消息。

## 连接MongoDB ##

使用`mongo.exe`命令连接。打开另一个命令行窗口，输入如下命令：

```
C:\mongodb\bin\mongo.exe
```
执行些命令后，就能连接上MongoDB服务。由于没有配置任何其他端口，也没有配置权限认证，所以一切都是默认的本地连接，相当简单。连接成功后，执行help命令，看看有什么内容吧。