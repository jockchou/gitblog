<!--
author: jockchou
date: 2015-07-30
title: GitBlog配置
tags: GitBlog
category: GitBlog
status: publish
summary: 这是Giblog的一个简单安装教程，如果你熟悉PHP或Web开发，这对你来说一定非常简单。本教程只针对Linux+Nginx环境，对于使用Apache的用户配置参考网上其他资料。
-->

Gitblog无需任何配置即可运行，但是为了突显你的博客特征。只需要对配置文件进行简单修改即可。Gitblog采用[yaml](http://www.yaml.org/ "yaml")格式的配置文件。

## 配置文件conf.yaml ##

```
#GitBlog配置文件，使用4个空格代替Tab
---
url             : "http://jockchou.gitblog.cn" #网站首页url
title           : jockchou的博客               #博客标题
subtitle        : 自豪地采用GitBlog            #博客副标题
theme           : default                      #主题名称
highlight       : on                           #是否开启代码高亮支持
mathjax         : on                           #是否开启数学公式支持
duoshuo         : jockchou                     #多说评论框ID
baiduAnalytics  : 732acc76ff6bd41343951a67cbfafe34  #百度统计ID
keywords        : jockchou,markdown,blog,php,github #网站关键字
description     : GitBlog是一个简单易用的Markdown博客系统,这是我的第一个GitBlog博客. #网站描述
version         : 1.0                          #系统版本号

blog:
    recentSize      : 5                        #最近博客显示条数
    pageSize        : 10                       #每页显示博客条数
    pageBarSize     : 5                        #翻页Bar的长度
    allBlogsForPage : false                    #页面需要所有博客数据
    
author:
    name    : jockchou                         #你的名称     
    email   : 164068300@qq.com                 #你的邮箱
    github  : jockchou                         #你的Github名称
    weibo   : 2558456121                       #你的微博ID

text:
    title: 简介                                #任一文本标题
    intro: >                                   #任一文本内容,这里可以多行  
        GitBlog是一个简单易用的Markdown博客系统，
        这是我的第一个GitBlog博客
```

你可能需要修改的配置参数：
url: 修改成你的域名
title： 修改成你的博客标题
subtitle： 修改成你的副标题
duoshuo： Gitblog采用多[说评](http://duoshuo.com/)论框，你需要申请多说账号，并在这里填写你的多说ID
baiduAnalytics： Gitblog采用[百度统计](http://tongji.baidu.com/)，你需要申请百度统计账号，在这里填写你的统计Key
author：修改为你个人的信息即可

如果你不需要评论和统计功能，删除`duoshuo`和`baiduAnalytics`这两荐即可。其他信息，可根据浏览博客页面的效果进行修改调整。

## 主题配置 ##
主题配置参数`theme`，可选值即为app/theme目录下主题文件夹的名称，如`simple`和`quest`，可根据自己喜好选择配置。
