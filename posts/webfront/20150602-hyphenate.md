# MooTools源码里的两个实用函数camelize，hyphenate #

----------
这两个javascript函数非常实用，用来转换驼峰和中划线命名，源码如下：

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