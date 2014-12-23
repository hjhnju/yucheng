/**
 * @ignore
 * @file util
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function () {
    /**
     * 每三位默认加,格式化
     * @param {number} x 数字
     * @return {string}
     */
    function addCommas(x) {
        if (isNaN(x)) {
            return '-';
        }
        x = (x + '').split('.');
        return x[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g,'$1,')
            + (x.length > 1 ? ('.' + x[1]) : '');
    }
    return {
        addCommas: addCommas
    };
});
