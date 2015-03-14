define([
    'require',
    'zrender/tool/math'
], function (require) {
    var zrMath = require('zrender/tool/math');
    function polar2cartesian(r, theta) {
        return [
            r * zrMath.sin(theta),
            r * zrMath.cos(theta)
        ];
    }
    function cartesian2polar(x, y) {
        return [
            Math.sqrt(x * x + y * y),
            Math.atan(y / x)
        ];
    }
    return {
        polar2cartesian: polar2cartesian,
        cartesian2polar: cartesian2polar
    };
});