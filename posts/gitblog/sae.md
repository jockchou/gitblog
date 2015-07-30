<!--
author: jockchou
date: 2015-07-24
title: 在新浪SAE上运行GitBlog
tags: GitBlog
category: GitBlog
status: publish
summary: Gitblog支持在新浪SAE云平台上运行。SAE是Sina App Engine的简称，是新浪研发中心推出的国内首个公有云计算平台，支持PHP，MySQL，Memcached，Mail，TaskQueue，RDC（关系型数据库集群）等服务。SAE通过实名认证及开发者认证，每个月送大量云豆，对于一般的博客站点云豆完全够用，也就是说用SAE搭建博客完全免费，不需要支付费用。
-->

Gitblog支持在新浪[SAE](http://sae.sina.com.cn)云平台上运行。SAE是Sina App Engine的简称，是新浪研发中心推出的国内首个公有云计算平台，支持PHP，MySQL，Memcached，Mail，TaskQueue，RDC（关系型数据库集群）等服务。SAE通过实名认证及开发者认证，每个月送大量云豆，对于一般的博客站点云豆完全够用，也就是说用SAE搭建博客完全免费，不需要支付费用。

## 布署Gitblog项目到SAE ##

首先要申请SAE账号，在SAE管理后台创建一个PHP(5.3版本以上)应用，创建应用完成后，参照代码管理说明书档，通过SVN提交Gitblog源码到应用在SAE的SVN仓库地址即可。例如：

```
https://svn.sinaapp.com/gitblogdoc/
```

## 关于SAE的特别说明 ##
由于SAE禁止PHP访问本地IO，所以Gitblog的缓存机制在SAE上是不支持的，不过没关系。没有缓存Gitblog照样能运行良好，只是博客数量太多了页面会稍微慢一点。后面的版本会考虑使用的SAE的Storage来支持缓存。
