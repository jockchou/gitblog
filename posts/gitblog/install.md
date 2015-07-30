<!--
author: jockchou
date: 2015-07-30
title: GitBlog安装
tags: GitBlog
category: GitBlog
status: publish
summary: 这是Giblog的一个简单安装教程，如果你熟悉PHP或Web开发，这对你来说一定非常简单。本教程只针对Linux+Nginx环境，对于使用Apache的用户配置参考网上其他资料。
-->

这是Giblog的一个简单安装教程，如果你熟悉PHP或Web开发，这对你来说一定非常简单。本教程只针对Linux+Nginx环境，对于使用Apache的用户配置参考网上其他资料。

## 环境准备: ##

- 域名
- Linux主机
- php + php-fpm
- nginx

假设我的域名为：
```
jockchou.gitblog.cn
```

## 配置nginx虚拟主机 ##

假设我的nginx配置的网站根目录为：

```
/data/vhosts/jockchou.gitblog.cn
```

Gitblog采用[CodeIgniter](http://codeigniter.org.cn/)开发，nginx可参考如下配置：

```
server {
        listen       80;
        server_name  jockchou.gitblog.cn;
        root         /data/vhosts/jockchou.gitblog.cn;
        index        index.html index.htm index.php;

        location ~ \.(jpg|png|gif|js|css|swf|flv|ico)$ {
                 expires 12h;
        }

        location / {
                if (!-e $request_filename) {
					rewrite ^(.*)$ /index.php?$1 last ;
					break;
                }
        }

        location ~* ^/(doc|logs|app|sys)/ {
                return 403;
        }
    
        location ~ .*\.(php|php5)?$
        {
                fastcgi_connect_timeout 300;
                fastcgi_send_timeout 300;
                fastcgi_read_timeout 300;
                fastcgi_pass   127.0.0.1:9000;
                fastcgi_index  index.php;
                fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include        fastcgi_params;
        }
}
```
在根目录下写一个`index.php`文件

```
<?php phpinfo();?>
```
启动nginx和php-fpm，在浏览器中访问域名`http://jockchou.gitblog.cn`正常显示phpinfo的内容表示安装环境成功了。

## 下载GitBlog源码包 ##

到[这里](https://github.com/jockchou/gitblog/releases)下载最新的Gitblog源码包，下传到你的服务器，解压复制包中的所有文件到网站根目录:
```
/data/vhosts/jockchou.gitblog.cn
```
再访问域名，就能看到Gitblog的默认页面了。

## 权限问题 ##

确保`posts`拥有读权限
确保`app/cache`和`app/logs`目录的写权限

假如运行php-fpm的用户名为apache：

```
chown -R apache:apache ./app/cache
chown -R apache:apache ./app/logs
```





