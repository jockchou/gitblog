# C语言真假判断的理解 #

----------
我们知道，C语言不像其他高级语言，它本身并没有定义boolean类型。它认为非0的值都为真，而只有0是假。也就是说C语言是通过判断整数值是否为0来进来逻辑控制的。

C99定义了_Bool类型来表示布尔。我们先来看一个关于_Bool类型的例子：
```
#include<stdio.h>

int main(void) {
	
	_Bool a = -8;
	_Bool b = 10L;
	_Bool c = 0;
	_Bool d = 0.0;
	_Bool e = 121.45;
	_Bool f = 'c';
	_Bool g = "world";
	_Bool h = '\0';
	_Bool i = NULL;
	
	printf("sizeof(_Bool) = %d\n", sizeof(_Bool));
	
	printf("a->%d\n", a);
	printf("b->%d\n", b);
	printf("c->%d\n", c);
	printf("d->%d\n", d);
	printf("e->%d\n", e);
	printf("f->%d\n", f);
	printf("g->%d\n", g);
	printf("h->%d\n", h);
	printf("i->%d\n", i);
	
	return 0;
}
```

输出结果：
```
sizeof(_Bool) = 1
a->1
b->1
c->0
d->0
e->1
f->1
g->1
h->0
i->0
```
从输出结果来看，gcc实现中，_Bool类型占一个字节大小，可以使用标量类型对_Bool变量赋值，但结果都会转化成0或者1，非0都转化成1。理所当然，空字符和空指针都转化成0。这种设计能够很好兼容以前的C版本。再来看一个例子：
```
#include<stdio.h>

int main(void) {
	
	int true_val, false_val;
	
	true_val = (10 > 2);   //一个真值表达式的值
	false_val = (10 == 2); //一个假值表达式的值
	
	printf("true = %d\nfalse = %d\n", true_val, false_val);
	
	return 0;
}
```
输出结果：

```
true = 1
false = 0
```
正如你所料，在C语言中，条件表达式的值只有0和1两个。实际上，只要是非0的表达式在C中都认为是真，只有值为0的表达式才为假。我们知道，在C语言中有各种表达式，这些表达式通常具有一个值。在进行真假判断的时候，系统首先将表达式的值转化为整型，再确定是否值为0。这个过程类似于_Bool类型变量赋值的过程。当然，只有标量类型可以进行此操作。什么是标量类型呢？根据C语言规范定义，只有整型，浮点数以及指针类型才是标量类型。

从这上面的分析可以理解到，if,while中的条件表达式的值必须是标量类型的。结构和数组这种复合类型是不允许出现在这里的。如果是函数调用，其返回值也必须是标量类型的。

