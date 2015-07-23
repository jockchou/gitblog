# 使用mongo命令行更新数据 #

## 概述 ##

使用`update()`方法更新文档。这个方法接收以下参数：

- 一个方档匹配的过滤器，用于过滤要更新的文档
- 一个用来执行修改操作的更新文档
- 一个可选的参数

指定过滤器和指定查询的时候是一样的。`update()`方法默认只更新单个文档，使用`multi`可选参数指定更新所有匹配的文档。

不能更新文档的`_id`字段。

## 更新指定字段 ##

要改变某个字段的值，MongoDB提供了更新操作，比如`$set`用来修改值。如果字段不存在，`$set`会创建这个字段。


## 更新顶级字段 ##

下面的操作更新`name`字段值为"Juni"的第一个文档，使用`$set`操作更新cuisine字段，使用`$currentDate`操作更新lastModified字段。

```
db.restaurants.update(
    { "name" : "Juni" },
    {
      $set: { "cuisine": "American (New)" },
      $currentDate: { "lastModified": true }
    }
)
```

更新操作会返回一个`WriteResult`对象，它包含更新操作返回的一些状态信息。


## 更新内嵌文档字段 ##

更新内嵌文档的字段，需要使用“.”号。如下所示：

```
db.restaurants.update(
  { "restaurant_id" : "41156888" },
  { $set: { "address.street": "East 31st Street" } }
)
```

## 更新多个文档 ##

默认地，`update()`方法只更新一个文档。如果要更新多个文档，需要指定`multi`可选参数。

```
db.restaurants.update(
  { "address.zipcode": "10016", cuisine: "Other" },
  {
    $set: { cuisine: "Category To Be Determined" },
    $currentDate: { "lastModified": true }
  },
  { multi: true}
)
```

## 替换文档 ##

要替换一个文档，只需要把一个新的文档传递给`update()`的第二个参数，并且不需要包含`_id`字段。如果包含`_id`字段，只保证跟原文档是同一个值。用于替换的文档可以跟原文档具有完全不同的字段。

```
db.restaurants.update(
   { "restaurant_id" : "41704620" },
   {
     "name" : "Vella 2",
     "address" : {
              "coord" : [ -73.9557413, 40.7720266 ],
              "building" : "1480",
              "street" : "2 Avenue",
              "zipcode" : "10075"
     }
   }
)
```
