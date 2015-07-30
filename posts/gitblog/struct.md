<!--
author: jockchou
date: 2015-07-30
title: GitBlog目录结构
tags: GitBlog
category: GitBlog
status: publish
summary: Gitblog采用流行的PHP框架CodeIgniter开发，只是我对一些目录进行了重命名。如果你熟悉CodeIgniter框架，那你一定不会陌生。
-->
Gitblog采用流行的PHP框架`CodeIgniter`开发，只是我对一些目录进行了重命名。如果你熟悉CodeIgniter框架，那你一定不会陌生。

## 目录结构如下 ##

Gitblog的目录结构如下所示：


```
.
├── app
│   ├── cache
│   └── logs
├── theme
│   ├── default
│   ├── quest
│   └── simple
├── sys
├── img
├── posts
├── conf.yaml
├── favicon.ico
├── index.php
├── LICENSE
└── robots.txt
```

## 目录说明 ##

- app: CodeIgniter主程序目录，cache和logs分别是缓存和日志目录，请确保写的权限    
- sys： CodeIgniter系统源码目录，一般不需要改这里面的任何东西  
- theme： Gitblog主题目录，所有主题模板都放在这里    
- posts: Gitblog存放markdown博客文件的目录，你写的博客都放这里  
- img： 图片目录，你的markdown中引用的图片都放到这里，使用相对路径引用  
- conf.yaml: Gitblog配置文件  
- index.php: 入口php文件  

 
