# 使用mongo命令行进行数据聚合 #

## 概述 ##

MongoDB可以执行数据聚合，比如按指定Key分组，计算总数，求不同分组的值。

使用`aggregate()`方法执行一个基于步骤的聚合操作（类似于Linux管道）。`aggregate()`接收一个步骤数组成为它的参数，每个步骤描述对数据处理的操作。

```
db.collection.aggregate( [ <stage1>, <stage2>, ... ] )
```

## 按字段分组并计算总数 ##

使用$group管理操作符进行分组操作。在$group操作符中，使用`_id`来说明分组的key。$gropu管理操作使用$+字段名的方式来访问分组Key的。可以在每个分组管理操作中进行分组计算。下面的例子把restaurants集合按borough字段分组，并使用$sum操作符计算每个分组的文档数。

```
db.restaurants.aggregate(
   [
     { $group: { "_id": "$borough", "count": { $sum: 1 } } }
   ]
);
```

结果集包含以下文档：
```
{ "_id" : "Staten Island", "count" : 969 }
{ "_id" : "Brooklyn", "count" : 6086 }
{ "_id" : "Manhattan", "count" : 10259 }
{ "_id" : "Queens", "count" : 5656 }
{ "_id" : "Bronx", "count" : 2338 }
{ "_id" : "Missing", "count" : 51 }
```

`_id`字段包含了不同的borough值，它也是分组参照的Key值。

## 过滤并分组文档 ##

使用`$match`管道操作符过滤文档。`$match`使用的是MongoDB查询语法。下面的管道使用`$macth`查询borough字段值为"Queens"并且cuisine字段值为"Brazilian"的所有文档。然后`$group`分组管理操作符把匹配的所有文档按address.zipcode字段每组，并且使用`$sum`计算器计算总数。

```
db.restaurants.aggregate(
   [
     { $match: { "borough": "Queens", "cuisine": "Brazilian" } },
     { $group: { "_id": "$address.zipcode" , "count": { $sum: 1 } } }
   ]
);
```

结果集包含的文档如下：

```
{ "_id" : "11368", "count" : 1 }
{ "_id" : "11106", "count" : 3 }
{ "_id" : "11377", "count" : 1 }
{ "_id" : "11103", "count" : 1 }
{ "_id" : "11101", "count" : 2 }
```

`_id`字段包含不同的zipcode的值。它是分组的Key。