define('echarts/util/projection/mercator', function () {
    function _mercator() {
        var radians = Math.PI / 180;
        var scale = 500;
        var translate = [
                480,
                250
            ];
        function mercator(coordinates) {
            var x = coordinates[0] / 360;
            var y = -(Math.log(Math.tan(Math.PI / 4 + coordinates[1] * radians / 2)) / radians) / 360;
            return [
                scale * x + translate[0],
                scale * Math.max(-0.5, Math.min(0.5, y)) + translate[1]
            ];
        }
        mercator.invert = function (coordinates) {
            var x = (coordinates[0] - translate[0]) / scale;
            var y = (coordinates[1] - translate[1]) / scale;
            return [
                360 * x,
                2 * Math.atan(Math.exp(-360 * y * radians)) / radians - 90
            ];
        };
        mercator.scale = function (x) {
            if (!arguments.length) {
                return scale;
            }
            scale = +x;
            return mercator;
        };
        mercator.translate = function (x) {
            if (!arguments.length) {
                return translate;
            }
            translate = [
                +x[0],
                +x[1]
            ];
            return mercator;
        };
        return mercator;
    }
    return _mercator;
});