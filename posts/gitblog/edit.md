<!--
author: jockchou
date: 2015-07-27
title: 编写GitBlog博客
images: /theme/cube/image/cube4.jpg
tags: GitBlog
category: GitBlog
status: publish
summary: 一篇GitBlog就是一个markdown文件，GitBlog使用解析markdown文件为html展示在网页上。所有的博客markdown文件必须放在posts文件夹里。后缀名只可以是xx.md或者xx.markdown。
-->

一篇GitBlog就是一个markdown文件，GitBlog使用解析markdown文件为html展示在网页上。所有的博客markdown文件必须放在`posts`文件夹里。后缀名只可以是`xx.md`或者`xx.markdown`。

## 发表一篇新博客 ##

在`posts`里新建文件`helloworld.md`，输入以下内容：
```
<!--
author: jockchou
date: 2015-07-30
title: Hello World!
tags: GitBlog
category: GitBlog
status: publish
summary: 你好！GitBlog
-->

你好！GitBlog

GitBlog是一个简单易用的Markdown博客系统，它不需要数据库，没有管理后台功能，更新博客只需要添加你写好的Markdown文件即可。

```
文件里头部的注释用来定义博客的属性，这是一个规定的写法，必须放在文件的头部，每个属性独占一行。再次访问首页，就能看到这篇新发的博客了。如果没显示，请清除`app/cache`文件夹下的所有缓存文件试试看。

```
<!--
这里定义博客属性
-->
```

*注意：markdown文件须采用utf8编码*，如果出现乱码，请修改编码为utf8。


## 博客属性定义 ##

GitBlog目前针对博客定义了以下属性：

- author: 博客作者名称  
- date: 博客时间，用于页面显示  
- title: 博客标题  
- tags: 博客里的标签，多个用逗号或空格分隔  
- category: 博客分类，多个用逗号或空格分隔  
- status: 博客状态，`draft`表示草稿，GitBlog解析时会忽略草稿；`publish`表示发表状态，默认为publish  
- summary: 博客摘要信息  

以上所有信息都是独占一行，暂不支持写在多行。


## markdown路径与URL对应关系 ##

GitBlog中`posts`中的markdown文件可以放在子文件夹中。举个例子，假如你的`helloword.md`文件目录如下：

```
posts/hello/helloword.md
```
你在浏览器访问的对应地址应该是这样的：

```
http://jockchou.gitblog.cn/blog/hello/helloworld.html
```

GitBlog对`posts`中子文件夹的层级没有限制，但请尽量不要太深，一般2，3层就够了。如果你在本地编写博客，使用FTP工具上传markdown文件到`posts`目录。