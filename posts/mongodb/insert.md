# 使用mongo命令行插入数据 #


## 概述 ##

在MongoDB中，你可以使用`insert()`方法插入一个文档到MongoDB集合中，如果此集合不存在，MongoDB会自动为你创建。

## 插入文档 ##

先用mongo命令行连接到一个MongoDB实例，转到test数据库。
```
use test
```

插入一个文档到restaurants集中，如果restaurants集合不存在，这个操作会先创建一个restaurants集合。

```
db.restaurants.insert(
   {
      "address" : {
         "street" : "2 Avenue",
         "zipcode" : "10075",
         "building" : "1480",
         "coord" : [ -73.9557413, 40.7720266 ],
      },
      "borough" : "Manhattan",
      "cuisine" : "Italian",
      "grades" : [
         {
            "date" : ISODate("2014-10-01T00:00:00Z"),
            "grade" : "A",
            "score" : 11
         },
         {
            "date" : ISODate("2014-01-16T00:00:00Z"),
            "grade" : "B",
            "score" : 17
         }
      ],
      "name" : "Vella",
      "restaurant_id" : "41704620"
   }
)
```

可以看到，命令行的执行，其实就是javascript函数的调用。函数调用后返回一个 `WriteResult`对象，它包含操作的返回状态信息。

如果插入的文档不包含`_id`字段，mongo命令行会自动加上这个字段到文档中，并且这个字段的值是根据[ObjectId](http://docs.mongodb.org/manual/reference/object-id/)生成。


