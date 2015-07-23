# 使用mongo命令行查询数据 #

## 概述 ##
使用`find()`方法在MongoDB集合中查询数据。MongoDB所有的查询范围都是单个集合的。也就是说MongoDB不能跨集合查询数据。

查询可以返回集合中的所有文档，或者仅仅返回指定过滤条件的文档。你可以指定一个过滤条件或才一个判断条件作为参数传递给`find()`方法。

`find()`方法在一个游标中返回所有的结果集，通过游标的迭代可以输出所有文档。

## 查询集合中的所有文档 ##

 查询集合中的所有文档，直接调用集合的`find()`方法，不需要指定条件。如下命令查询`restaurants`中的所有文档。

```
db.restaurants.find()
```

返回的结果集包含`restaurants`所有的文档。

## 指定"等于"条件 ##

查询条件如果是某个字段上的“等于”匹配的话，具有如下格式：

```
{ <field1>: <value1>, <field2>: <value2>, ... }
```

如果<field>是文档中的顶级字段，并不是内嵌的，也不是数组的话，你可以使用引号括住字段名，或者不使用引号。

如果<field>就文内嵌字段，或者是数组，使用“.”号访问字段。而且必要使用相号括住整个字段名。

## 根据顶级字段查询 ##

下面的命令查询所有`borough`字段值为"Manhattan"的文档。

```
db.restaurants.find( { "borough": "Manhattan" } )
```
查询的结果集中仅包含匹配的文档。


## 根据数组中的字段查询 ##

在restaurants集合中，grades数组包含了内嵌文档作为它的元素。使用“.”号可以在内嵌文档中的某个字段上指定一个条件。同样，需要用引号括住有点号的引用。如下命令查询grades包括一个内嵌文档，它的grade字段的值为'B'的所有文档。

```
db.restaurants.find({"grades.grade": "B"})
```

## 指定操作条件查询 ##

MongoDB提供了一些操作用来指定查询条件，比如比较操作。一些操作是除此之外的，比如`$or`和`$and`条件操作。使用操作的查询条件的格式如下：

```
{ <field1>: { <operator1>: <value1> } }
```

## 大于操作($gt) ##

查询所有grades数组的内嵌文档中score字段的值大于30的文档。

```
db.restaurants.find( { "grades.score": { $gt: 30 } } )
```

## 小于操作($lt) ##

```
db.restaurants.find( { "grades.score": { $lt: 10 } } )

```

## 逻辑AND ##

你可以使用逻辑AND用于查询条件之间，使用逗号隔开。

```
db.restaurants.find( { "cuisine": "Italian", "address.zipcode": "10075" } )
```

## 逻辑OR ##
你可以为多个查询条件中使用逻辑OR，使用$or查询操作。
```
db.restaurants.find(
   { $or: [ { "cuisine": "Italian" }, { "address.zipcode": "10075" } ] }
)
```

当然，$and也可以使用上面的语法。

## 排序查询结果 ##

指定查询结果排序方式的就是在查询后追加一个`sort()`方法调用。传递给此方法一个文档，包含指定排序字段和排序类型。1表示长充，-1表示降序。

```
db.restaurants.find().sort( { "borough": 1, "address.zipcode": 1 } )
```
如上命令，先按borough字段升序排列，再按address.zipcode升序排。