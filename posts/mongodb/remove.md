# 使用mongo命令行删除数据 #

## 概述 ##

使用`remove()`方法从集合中删除文档。这个方法需要一个条件文档用来决定哪些文档将被删除。


## 删除匹配的所有文档 ##

下面的操作将删除指定条件匹配的所有文件:

```
db.restaurants.remove( { "borough": "Manhattan" } )
```

删除操作返回一个`WriteResult`对象，它包含了操作的状态信息。`nRemoved`字段值表示被删除的文档数量。


## 使用justOne可选参数 ##

默认地，`remove()`方法将删除匹配指定条件的所有文档。使用justOne可选参数可以限制删除操作只删除一条。

```
db.restaurants.remove( { "borough": "Queens" }, { justOne: true } )

```
操作成功将返回如下的`WriteResult`对象。

```
WriteResult({ "nRemoved" : 1 })
```

`nRemoved`字段值表示删除的文档数量。


## 删除所有文档 ##

删除一个集合中的所有文档，传递一个空的条件文档即可。

```
db.restaurants.remove( { } )
```

## 删除一个集合 ##

删除所有的操作仅仅是删除集合中的全部文档。集合本身和集合的索引并不会被删除。直接删除集合包括索引，也许比删除一个集合中的所有文档更高效。需要的时候重新创建集合并构建索引。使用`drop()`方法删除一个集合，包括所有索引。

```
db.restaurants.drop()
```

删除集合如果成功，此操作将返回true。如果被删除的集合不存在，将返回false。



在MongoDB中，"写"操作是文档级别的原子操作。如果一个删除操作要删除集合中的多个文档，这个操作会和其他写操作交错。具体请参考MongoDB手册中[Atomicity](http://docs.mongodb.org/manual/core/write-operations-atomicity/)。




