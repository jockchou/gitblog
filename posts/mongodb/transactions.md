# MongoDB原子性和事务 #

在MongoDB中，写操作的原子性是在`document`级别上的，即使修改的是文档中的内嵌部分，写锁的级别也是`document`上。

当一个写操作要修改多个文档，每个文档的修改是原子性的。整个的写操作并不是原子性的，它可能和其他写操作产生交织。然而你可以使用`$isolated`隔离操作符来限制写操作，让它不与其他写操作交织。 不隔离性能更高，但是会产生数据的不确定性，隔离写操作，事务性更好。MongoDB把这个级别完全由用户控制。

# 隔离写操作 #
MongoDB使用`$isolated`操作符来隔离写操作。如果一个写操作要更新多个文档，它能防止其他进程与本次写操作交错。直到这个写操作完成，其他进程才能写。

但是，`$isolated`算不上一个事务，如果在写的过程中发生错误，MongoDB并不会回滚已经写的数据。`$isolated`也不能在分片集群上工作。

特性：

- 隔离不支持分片集群
- 不支持“all-or-nothing”特性
- MongoDB2.2版本后`$isolated`被替换成`$atomic`

# 类事务语法 #

MongoDB并不支持关系型数据库中的那种事务特性，为了性能着想，它把这个特性交给程序员去实现。这就是MongoDB官方所讲的[Two Phase Commits](http://docs.mongodb.org/manual/tutorial/perform-two-phase-commits)两阶段提交。这个技术虽然在一定程度上能保证数据最终的一致性，但是应用程序还是可能会读到提交或者回滚过程中的中间数据。对于这个技术如果有兴趣可以读一读原文。

#并发控制#

并发控制允许多个应用层程序同时访问数据库，而不引起数据不一致或冲突。

MongoDB中提到两种技术来解决这个问题。第一种是唯一索引，第二种是叫`Update if Current`。

用唯一索引来防止多个进程重复插入或者更新导致的重复的值。
`Update if Current`意思是说在更新数据的时候，在更新条件里给定一个期望的值（这个值是先查询出来的），用来防止在更新之前其他进程已经将此值更新。看一个例子:
```
var myDocument = db.products.findOne( { sku: "abc123" } );

if ( myDocument ) {
   var oldQuantity = myDocument.quantity;
   var oldReordered = myDocument.reordered;

   var results = db.products.update(
      {
        _id: myDocument._id,
        quantity: oldQuantity,
        reordered: oldReordered
      },
      {
        $inc: { quantity: 50 },
        $set: { reordered: true }
      }
   )

   if ( results.hasWriteError() ) {
      print( "unexpected error updating document: " + tojson(results) );
   }
   else if ( results.nMatched === 0 ) {
      print( "No matching document for " +
             "{ _id: "+ myDocument._id.toString() +
             ", quantity: " + oldQuantity +
             ", reordered: " + oldReordered
             + " } "
      );
   }
}
```

同样的，在findAndModify()函数中:
```
db.people.findAndModify({
    query: { name: "Andy" },
    sort: { rating: 1 },
    update: { $inc: { score: 1 } },
    upsert: true
})
```

如果有多个进程同时调用此函数，这些进程都完成了查询阶段，如果`name`字段上没有唯一索引，upsert阶段的操作，多个进程可能都会执行。导致写入重复的文档。