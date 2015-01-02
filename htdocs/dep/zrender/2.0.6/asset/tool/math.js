/*! 2015 Baidu Inc. All Rights Reserved */
define('zrender/tool/math', function () {
    var _radians = Math.PI / 180;
    function sin(angle, isDegrees) {
        return Math.sin(isDegrees ? angle * _radians : angle);
    }
    function cos(angle, isDegrees) {
        return Math.cos(isDegrees ? angle * _radians : angle);
    }
    function degreeToRadian(angle) {
        return angle * _radians;
    }
    function radianToDegree(angle) {
        return angle / _radians;
    }
    return {
        sin: sin,
        cos: cos,
        degreeToRadian: degreeToRadian,
        radianToDegree: radianToDegree
    };
});