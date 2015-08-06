# JavaScript类型检测最佳实践 #

----------
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
