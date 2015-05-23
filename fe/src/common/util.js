/**
 * @ignore
 * @file util
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function() {
    var $ = require('jquery');
    /**
     * 每三位默认加,格式化
     * @param {number} x 数字  增加对数组的判断
     * @return {string}
     */
    function addCommas(x) {
        if ($.isArray(x))
            return $.map(x, function(x) {
                return addCommas(x);
            });
        if (isNaN(x)) {
            return '0.00';
        }
        x = (x + '').split('.');
        return x[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,') + (x.length > 1 ? ('.' + x[1]) : '');
    }

    /**
     * 清除数字格式化
     * @param {string}  e 数字字符串  增加对数组的判断
     * @return {number}
     */
    function removeCommas(e, t) {
        if ($.isArray(e))
            return $.map(e, function(e) {
                return removeCommas(e, t);
            });
        if (e = e || 0, "number" == typeof e)
            return e;
        t = t || ".";
        var n = new RegExp("[^0-9-" + t + "]", ["g"]),
            r = parseFloat(("" + e).replace(/\((.*)\)/, "-1").replace(n, "").replace(t, "."));

        return isNaN(r) ? 0 : r;
    }

    /**
     * 小数转换为百分比
     * @param  {[number]} n [小数]
     * @return {[type]}   [百分数]
     */
    function toPercent(n) {
        var num = Math.round(parseFloat(n) * 10000);
        num /= 100.00;
        return num.toString() + "%";
    }

    /**
     * 复制到剪切板js代码
     * @param {string} s 复制内容
     */
    function copyToClipBoard(s) {
        //alert(s);
        if (window.clipboardData) {
            window.clipboardData.setData("Text", s);
            alert("已经复制到剪切板！" + "\n" + s);
        } else if (navigator.userAgent.indexOf("Opera") != -1) {
            window.location = s;
        } else if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
            }
            var clip = Components.classes['@mozilla.org/widget/clipboard;1']
                .createInstance(Components.interfaces.nsIClipboard);
            if (!clip)
                return;
            var trans = Components.classes['@mozilla.org/widget/transferable;1']
                .createInstance(Components.interfaces.nsITransferable);
            if (!trans)
                return;
            trans.addDataFlavor('text/unicode');

            // var str = new Object();
            // var len = new Object();

            var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
            var copytext = s;
            str.data = copytext;
            trans.setTransferData("text/unicode", str, copytext.length * 2);
            var clipid = Components.interfaces.nsIClipboard;
            if (!clip)
                return false;
            clip.setData(trans, null, clipid.kGlobalClipboard);
            alert("已经复制到剪切板！" + "\n" + s);
        }
    }

    /**
     * 对指定的函数进行包装, 返回一个在指定的时间内一次的函数
     * @param  {Function} fn   待包装函数
     * @param  {number}   wait 时间范围
     * @return {Function}      包装后的函数
     */
    function debounce(fn, wait) {
        var timer = null;
        return function() {

            if (timer) {
                return;
            }

            timer = setTimeout(function() {
                timer = null;
            }, wait);

            return fn.apply(this, arguments);
        };
    }

    /*
        JS读取cookie: 
        假设cookie中存储的内容为：name=jack;password=123 
        则在B页面中获取变量username的值的JS代码如下：*/

    var username = document.cookie.split(";")[0].split("=")[1];

    //JS操作cookies方法!

    //写cookies

    function setCookie(name, value, hours, path) {
        var exp = new Date();
        exp.setTime(exp.getTime() + hours * 60 * 60 * 1000);
        document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=" + path;
    }

    //读取cookies
    function getCookie(name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

        if (arr = document.cookie.match(reg))

            return unescape(arr[2]);
        else
            return null;
    }

    //删除cookies
    function delCookie(name) {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = getCookie(name);
        if (cval != null)
            document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
    }

    /**
     * 获取url参数
     * @param  {[string]} name [参数名字]
     * @return {[type]}      [description]
     */
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

    return {
        addCommas: addCommas,
        copyToClipBoard: copyToClipBoard,
        debounce: debounce,
        removeCommas: removeCommas,
        toPercent: toPercent,
        setCookie: setCookie,
        getCookie: getCookie,
        delCookie: delCookie,
        getUrlParam:getUrlParam
    };
});
