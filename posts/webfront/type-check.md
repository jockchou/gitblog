<!--
author: jockchou
date: 2015-07-23
title: JavaScript类型检测最佳实践 
tags: web前端, javascript
category: 前端开发
status: publish
summary: 前端开发中，javascript对数据类型进行判断是非常常见的代码，现总结了JavaScript类型检测最佳实践代码，仅供参考。类型检测的方法很多，本文的方法方便统一编程风格。
-->

前端开发中，javascript对数据类型进行判断是非常常见的代码，现总结了JavaScript类型检测最佳实践代码，仅供参考。类型检测的方法很多，本文的方法方便统一编程风格。

```
- String: typeof object === "string"
- Number: typeof object === "number"
- Boolean: typeof object === "boolean"
- Object: typeof object === "object"
- Plain Object: jQuery.isPlainObject( object )
- Function: jQuery.isFunction( object )
- Array: jQuery.isArray( object )
- Element: object.nodeType
- null: object === null
- null or undefined: object == null
- undefined:
- Global Variables: typeof variable === "undefined"
- Local Variables: variable === undefined
- Properties: object.prop === undefined
```
