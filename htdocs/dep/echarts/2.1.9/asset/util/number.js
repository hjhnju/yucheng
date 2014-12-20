/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/util/number', function () {
    function _trim(str) {
        return str.replace(/^\s+/, '').replace(/\s+$/, '');
    }
    function parsePercent(value, maxValue) {
        if (typeof value === 'string') {
            if (_trim(value).match(/%$/)) {
                return parseFloat(value) / 100 * maxValue;
            }
            return parseFloat(value);
        }
        return value;
    }
    function parseCenter(zr, center) {
        return [
            parsePercent(center[0], zr.getWidth()),
            parsePercent(center[1], zr.getHeight())
        ];
    }
    function parseRadius(zr, radius) {
        if (!(radius instanceof Array)) {
            radius = [
                0,
                radius
            ];
        }
        var zrSize = Math.min(zr.getWidth(), zr.getHeight()) / 2;
        return [
            parsePercent(radius[0], zrSize),
            parsePercent(radius[1], zrSize)
        ];
    }
    function addCommas(x) {
        if (isNaN(x)) {
            return '-';
        }
        x = (x + '').split('.');
        return x[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,') + (x.length > 1 ? '.' + x[1] : '');
    }
    return {
        parsePercent: parsePercent,
        parseCenter: parseCenter,
        parseRadius: parseRadius,
        addCommas: addCommas
    };
});