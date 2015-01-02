/*! 2015 Baidu Inc. All Rights Reserved */
define('echarts/util/projection/albers', function () {
    function _albers() {
        var radians = Math.PI / 180;
        var origin = [
                0,
                0
            ];
        var parallels = [
                29.5,
                45.5
            ];
        var scale = 1000;
        var translate = [
                0,
                0
            ];
        var lng0;
        var n;
        var C;
        var p0;
        function albers(coordinates) {
            var t = n * (radians * coordinates[0] - lng0);
            var p = Math.sqrt(C - 2 * n * Math.sin(radians * coordinates[1])) / n;
            return [
                scale * p * Math.sin(t) + translate[0],
                scale * (p * Math.cos(t) - p0) + translate[1]
            ];
        }
        albers.invert = function (coordinates) {
            var x = (coordinates[0] - translate[0]) / scale;
            var y = (coordinates[1] - translate[1]) / scale;
            var p0y = p0 + y;
            var t = Math.atan2(x, p0y);
            var p = Math.sqrt(x * x + p0y * p0y);
            return [
                (lng0 + t / n) / radians,
                Math.asin((C - p * p * n * n) / (2 * n)) / radians
            ];
        };
        function reload() {
            var phi1 = radians * parallels[0];
            var phi2 = radians * parallels[1];
            var lat0 = radians * origin[1];
            var s = Math.sin(phi1);
            var c = Math.cos(phi1);
            lng0 = radians * origin[0];
            n = 0.5 * (s + Math.sin(phi2));
            C = c * c + 2 * n * s;
            p0 = Math.sqrt(C - 2 * n * Math.sin(lat0)) / n;
            return albers;
        }
        albers.origin = function (x) {
            if (!arguments.length) {
                return origin;
            }
            origin = [
                +x[0],
                +x[1]
            ];
            return reload();
        };
        albers.parallels = function (x) {
            if (!arguments.length) {
                return parallels;
            }
            parallels = [
                +x[0],
                +x[1]
            ];
            return reload();
        };
        albers.scale = function (x) {
            if (!arguments.length) {
                return scale;
            }
            scale = +x;
            return albers;
        };
        albers.translate = function (x) {
            if (!arguments.length) {
                return translate;
            }
            translate = [
                +x[0],
                +x[1]
            ];
            return albers;
        };
        return reload();
    }
    return _albers;
});