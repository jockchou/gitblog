<!--
author: jockchou
date: 2015-07-20
title: 使用GitBlog和Github Pages搭建博客
images: http://pingodata.qiniudn.com/cube6.jpg
tags: GitBlog
category: GitBlog
status: publish
summary: 如果你没有主机，也不想使用SAE，只有Github账号，你想用GitBlog搭建自己的博客系统也是可以简单地做到的，[Github pages](https://pages.github.com/)允许你基于Github分库建立一个站点。
-->


## 创建Github Pages仓库 ##

你首先要创建一个新的Github仓库，这个仓库的名字必须为`username.github.io`，username为你的github账户名，必须一致。具体的步骤请参照这里[https://pages.github.com/](https://pages.github.com/)。

## 导出GitBlog静态网站 ##
Github pages不支持PHP程序运行，所以需要导出静态页面，将导出的`_site`目录下的所有文件复制到上一步创建的本地仓库，把仓库同步到Github上面。这样就好了，你可以通过域名username.github.io来访问刚才同步的Github pages了。

## 绑定域名到Github pages ##

你也可以通过绑定自己的域名到Github pages，使用自己的域名来访问Github pages。在你的仓库根目录下创建一个`CNAME`文件，文件中输出你的域名，例如：

```
jockchou.com
```

同步这个CNAME文件到Github仓库。然后就是要把你的域名解析到Github pages了。如果使用的是顶级域名解析A纪录，如果是子域名解析CNAME纪录，解析的纪录值是:

```
103.245.222.133
```

这个IP可能Github官方会变，请注意修改。详细的配置方法参考[这里](https://help.github.com/articles/setting-up-a-custom-domain-with-github-pages/)。


以上配置完成后，等域名解析生效后，就可以使用自己的域名访问了。

