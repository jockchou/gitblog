# 命令行 #

Mongo命令行是一个跟MongodDB服务交互的JavaScript接口工具，它是MongoDB封装的一个组件。你可以使用这个命令行工具查询，更新数据，执行一些管理操作。

## 运行命令行 ##

安装并启动MongoDB后，就可以连接`mongo`命令行到MongoDB实例了。先确认MongoDB实例已经运行，然后才可以启动`mongo`命令行连接。

打开一个命令行窗口，执行如下命令即可：
```
mongo
```

请确认你已经配置了环境变量，在Windows上你也可以加上后缀.exe:

```
mongo.exe
```

如果没有配置环境变量，你要指定命令的全路径。

```
C:\mongodb\bin\mongo.exe
```

当运行`mongo`命令，不指定任何参数的时候，它默认是连接到本机localhost的27017端口。详细的参数参考[MongoDB Shell](http://docs.mongodb.org/manual/reference/program/mongo/)


## Mongo命令行Help命令 ##

在mongo命令行中输出`help`将会列出所有可用的命令以及描述。

```
> help
```

mongo命令行还提供了跟Linux一样的<tab>自动完成和提示功能。并且可以使用上下箭头翻动历史命令。