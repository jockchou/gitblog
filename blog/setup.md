<!--
author: Peikon
head: http://pingodata.qiniudn.com/jockchou-avatar.jpg
date: 
title: Ubuntu + Apache2.4.7环境下搭建使用Gitblog
tags: GitBlog Apache
images: http://pingodata.qiniudn.com/cube2.jpg
category: GitBlog
status: publish
summary: Ubuntu+Apache2.4.7环境下搭建使用Gitblog
-->

## 一. 环境准备 ##
- Linux主机
- PHP 5.5.9 + mbstring扩展库
- Apache 2.4.7

## 二. 安装步骤 ##
1. 到[这里][1]下载最新的GitBlog源码包
2. 解压复制所有文件到你的PHP网站根目录
3. 配置你的Apache
4. 打开浏览器，访问网站首页

## 三. 配置Apache ##
GitBlog在Aapche上运行需要开启`Aapche Rewrite`模块用以支持GitBlog的伪静态URL,并且要配置`.htaccess`文件可用。

在/etc/apache2/mods-enabled/路径下新建一个文件"rewrite.load",文件内容为
```
LoadModule rewrite_module /usr/lib/apache2/modules/mod_rewrite.so
```

修改/etc/apache/sites-available/xxx-default.conf文件,在`<VirtualHost *:80>`节点内添加以下内容
```bash
<Directory /var/www/cavy/www>   //你的Gitblog根目录
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    allow from all
</Directory>
```
并修改`DocumentRoot`为你的Gitblog根目录

你的Gitblog根目录下应该有.htaccess文件存在。如果不存在,则创建并添加以下内容
```
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteCond $1 !^(index\.php|img|robots\.txt)
RewriteRule ^(.*)$ /index.php/$1 [L]
```

到此时为止你的Gitblog应该就可以使用了。Have Fun!

[1]: https://github.com/jockchou/gitblog/releases


