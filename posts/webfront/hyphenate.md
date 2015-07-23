<!--
author: jockchou
date: 2015-07-23
title: MooTools源码里的两个实用函数 
tags: web前端, javascript
category: 前端开发
status: publish
summary: 在前端开发中，有时候你可能需要转换驼峰和中划线命名的一些字符串，现提供MooTools源码里的两个实用的转换函数。 
-->

在前端开发中，有时候你可能需要转换驼峰和中划线命名的一些字符串，现提供MooTools源码里的两个实用的转换函数，用来转换驼峰和中划线命名，源码如下：

```
function camelize(str) {
    return (str + "").replace(/-\D/g,
    function(match) {
        return match.charAt(1).toUpperCase();
    });
}
camelize("border-bottom-color"); // "borderBottomColor"

function hyphenate(str) {
    return (str + "").replace(/[A-Z]/g,
    function(match) {
        return "-" + match.toLowerCase();
    });
}
hyphenate("borderBottomColor"); // "border-bottom-color"
```