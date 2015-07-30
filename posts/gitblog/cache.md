<!--
author: jockchou
date: 2015-07-25
title: GitBlog的缓存机制
tags: GitBlog
category: GitBlog
status: publish
summary: 由于Gitblog没有数据库，是依靠解析posts文件夹中的markdown文件来展示表客数据的。通常我们写好一篇博客以后，对其进行修改的频率并不高。Gitblog没必要每次访问页面时，都去解析markdown文件。基于这个理由，Gitblog对数据进行了缓存，Gitblog的缓存有三个层面的实现。
-->

由于Gitblog没有数据库，是依靠解析`posts`文件夹中的markdown文件来展示表客数据的。通常我们写好一篇博客以后，对其进行修改的频率并不高。Gitblog没必要每次访问页面时，都去解析markdown文件。基于这个理由，Gitblog对数据进行了缓存，Gitblog的缓存有三个层面的实现。

## 三层缓存机制 ##

三层缓存机制由上至下依次是：

- HTML页面缓存
- Twig模板缓存
- PHP数据缓存

所有的缓存数据都放在`app/cache`目录中，所以要保证程序有写这个目录的权限，缓存才能生效。下面我们由下至上依次来说明这三层缓存。

## PHP数据缓存 ##

第一次访问博客时，PHP程序会做解析`posts`下的markdown博客文件。解析完后全部缓存起来。缓存的文件有：

```
all_blog.gb		->所有的博客数据
all_tag.gb		->所有的博客标签
all_category.gb	->所有的分类信息
all_archive.gb	->所有的归档月份
```

些外，按分类，标签，归档访问博客列表时也会对应生成相应的缓存，并且按ID访问分类，标签，博客时也会生成对应的缓存。 这是最底层的缓存，缓存的文件名后缀为`.gb`。


## Twig模板缓存 ##

Gitblog采用[Twig](http://twig.sensiolabs.org/)模板引擎，Twig是一个轻量，高效，安全的PHP模板引擎。Twig会将html模板块转换成PHP类文件缓存起来。这些模板层级的缓存，主要是针对重复载入拼接模板的优化。

## HTML页面缓存 ##

这是最上层的缓存机制，Gitblog会将访问过的每一个页面都缓存为一个html文件。下次访问时，直接读取这个html文件。

当你对Gitblog作了改动后，发现没有生效，可以先尝试清除`app/cache`下的所有缓存文件。比如你上传了新的markdown，希望马上能访问看到，可以这样做。










