<!--
author: jockchou
date: 2015-07-23
title: 前端开发实用函数getPageSize
tags: web前端,前端开发,javascript
category: 前端开发
status: publish
summary: 在web前端开发中，经常需要计算浏览器宽高，网页宽高等属性。现提供两个非常实现的javascript函数用来计算浏览器页面大小，窗口大小，以及滚动高度。
-->

在web前端开发中，经常需要计算浏览器宽高，网页宽高等属性。现提供两个非常实现的javascript函数用来计算浏览器页面大小，窗口大小，以及滚动高度，源代码如下：

```
function getPageSize() {
    var xScroll, yScroll;
    if (window.innerHeight && window.scrollMaxY) {
        xScroll = window.innerWidth + window.scrollMaxX;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }
    var windowWidth, windowHeight;
    if (self.innerHeight) { // all except Explorer
        if (document.documentElement.clientWidth) {
            windowWidth = document.documentElement.clientWidth;
        } else {
            windowWidth = self.innerWidth;
        }
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }

    var pageHeight, pageWidth;
    // for small pages with total height less then height of the viewport
    pageHeight = yScroll < windowHeight ? windowHeight: yScroll;
    // for small pages with total width less then width of the viewport
    pageWidth = xScroll < windowWidth ? windowWidth: xScroll;
    return {
        pageWidth: pageWidth,
        pageHeight: pageHeight,
        windowWidth: windowWidth,
        windowHeight: windowHeight
    };
}
function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
        yScroll = self.pageYOffset;
        xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) { // Explorer 6 Strict
        yScroll = document.documentElement.scrollTop;
        xScroll = document.documentElement.scrollLeft;
    } else if (document.body) { // all other Explorers
        yScroll = document.body.scrollTop;
        xScroll = document.body.scrollLeft;
    }
    return {
        xScroll: xScroll,
        yScroll: yScroll
    };
}
```