# 导入数据 #

本教程使用test数据库和restaurants集合为例进行讲解。下面是restaurants的一个文档结构示例：

```
{
  "address": {
     "building": "1007",
     "coord": [ -73.856077, 40.848447 ],
     "street": "Morris Park Ave",
     "zipcode": "10462"
  },
  "borough": "Bronx",
  "cuisine": "Bakery",
  "grades": [
     { "date": { "$date": 1393804800000 }, "grade": "A", "score": 2 },
     { "date": { "$date": 1378857600000 }, "grade": "A", "score": 6 },
     { "date": { "$date": 1358985600000 }, "grade": "A", "score": 10 },
     { "date": { "$date": 1322006400000 }, "grade": "A", "score": 9 },
     { "date": { "$date": 1299715200000 }, "grade": "B", "score": 14 }
  ],
  "name": "Morris Park Bake Shop",
  "restaurant_id": "30075445"
}
```

## 导入例子数据 ##

在进行操作之前，我们需要例子数据，在这里下载数据文件[dataset.json](../../download/dataset.json)。

## 导入数据到集合 ##

在命令行中执行`mongoimport`命令将上面下载的数据文件中的数据导入到`test`数据库的`restaurants`集合中。如果此集合已经存在，下面的操作会先删除。

```
mongoimport --db test --collection restaurants --drop --file C:\data\dataset.json
```

`mongoimport`命令连接到本机运行的`mongod`实例，如果要把数据导到不同主机，不同端口的实例，可以指定主机和端口，使用参数 `--host`和`--port`。

数据导入后，你可以用`mongo`命令连接到实例，使用`show dbs`，`use test`，`show collections`和`db.restaurants.find()`命令查看导入的数据。