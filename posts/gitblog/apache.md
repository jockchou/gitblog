<!--
author: jockchou
date: 2015-07-22
title: 在Apache上运行GitBlog
tags: GitBlog
category: GitBlog
status: publish
summary: GitBlog在Aapche上运行需要开启Aapche Rewrite模块以支持GitBlog的伪静态URL。并且要配置.htaccess文件可用，然后在.htaccess文件中配置Rewriter规则。
-->

GitBlog在Aapche上运行需要开启Aapche Rewrite模板用以支持GitBlog的伪静态URL。并且要配置`.htaccess`文件可用，然后在`.htaccess`文件中配置Rewriter规则。


## 打开Aapache Rewrite模块##


打开`httpd.conf`文件，解除`rewrite_module`模板前的的注释`#`：

```
LoadModule rewrite_module modules/mod_rewrite.so
```

启用`.htaccess`，在虚拟机配置项中：

```
AllowOverride None #修改为： AllowOverride All
```

## 配置Rewrite规则 ##

在GitBlog根目录下创建`.htaccess`文件，输出以下内容：

```
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteCond $1 !^(index\.php|img|robots\.txt)
RewriteRule ^(.*)$ /index.php/$1 [L]
```

## 权限配置 ##

由于GitBlog的缓存机制需要写`app/cache`目录，必要时请查看并修改这个目录的权限，以确保你的PHP拥有写这个目录的权限。最粗鲁的方式就是把整个GitBlog目录的权限都修改成你的apache运行账户的权限。

```
chown -R apache:apache ./gitblog
```