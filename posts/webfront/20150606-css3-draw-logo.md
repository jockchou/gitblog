# 用CSS3画公司logo #

----------
本练习主要用到CSS3的元素形变，圆角的一些属性。

公司的Logo如下图
![pingo-logo](../../img/pingo.png)

首先构造三个圆圈的HTML结构:
```
<div id="wrap">
	<div id="left-circle">
		<div id="left-inner">
			<div id="small-circle"></div>
		</div>
	</div>
	<div id="mask"></div>
	<div id="mask-circle"></div>
	<div id="right-circle">
		<div id="right-inner"></div>
	</div>
</div>

```

定义CSS：
```
#wrap {
	width: 400px;
	height: 400px;
	background: #f86768;
}

#left-circle {
	width: 200px;
	height: 200px;
	border-radius: 100px;
	background: #fff;
	position: relative;
	top: 100px;
	left: 50px;
	float: left;
}

#left-inner {
	width: 140px;
	height: 140px;
	border-radius: 70px;
	background: #f86768;
	position: relative;
	top: 30px;
	left: 30px;
}

#right-circle {
	width: 130px;
	height: 130px;
	border-radius: 65px;
	background: #fff;
	position: relative;
	top: 164px;
	left: 16px;
	float: left;
}

#right-inner {
	width: 70px;
	height: 70px;
	border-radius: 35px;
	background: #f86768;
	position: relative;
	top: 30px;
	left: 30px;
}

#small-circle {
	width: 24px;
	height: 24px;
	border-radius: 12px;
	background: #fff;
	position: relative;
	top: 80px;
	left: 100px;
}

#mask {
	width: 70px;
	height: 70px;
	background: #f86768;
	position: absolute;
	left: 222px;
	top: 160px;
	transform: rotate(-36deg);
}

#mask-circle {
	width: 30px;
	height: 30px;
	border-radius: 15px;
	background: #fff;
	position: absolute;
	top: 150px;
	left: 216px;
}

```