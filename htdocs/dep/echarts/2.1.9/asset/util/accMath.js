/*! 2015 Baidu Inc. All Rights Reserved */
define('echarts/util/accMath', function () {
    function accDiv(arg1, arg2) {
        var s1 = arg1.toString();
        var s2 = arg2.toString();
        var m = 0;
        try {
            m = s2.split('.')[1].length;
        } catch (e) {
        }
        try {
            m -= s1.split('.')[1].length;
        } catch (e) {
        }
        return (s1.replace('.', '') - 0) / (s2.replace('.', '') - 0) * Math.pow(10, m);
    }
    function accMul(arg1, arg2) {
        var s1 = arg1.toString();
        var s2 = arg2.toString();
        var m = 0;
        try {
            m += s1.split('.')[1].length;
        } catch (e) {
        }
        try {
            m += s2.split('.')[1].length;
        } catch (e) {
        }
        return (s1.replace('.', '') - 0) * (s2.replace('.', '') - 0) / Math.pow(10, m);
    }
    function accAdd(arg1, arg2) {
        var r1 = 0;
        var r2 = 0;
        try {
            r1 = arg1.toString().split('.')[1].length;
        } catch (e) {
        }
        try {
            r2 = arg2.toString().split('.')[1].length;
        } catch (e) {
        }
        var m = Math.pow(10, Math.max(r1, r2));
        return (Math.round(arg1 * m) + Math.round(arg2 * m)) / m;
    }
    function accSub(arg1, arg2) {
        return accAdd(arg1, -arg2);
    }
    return {
        accDiv: accDiv,
        accMul: accMul,
        accAdd: accAdd,
        accSub: accSub
    };
});