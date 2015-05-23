define('common/util', ['jquery'], function () {
    var $ = require('jquery');
    function addCommas(x) {
        if ($.isArray(x))
            return $.map(x, function (x) {
                return addCommas(x);
            });
        if (isNaN(x)) {
            return '0.00';
        }
        x = (x + '').split('.');
        return x[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,') + (x.length > 1 ? '.' + x[1] : '');
    }
    function removeCommas(e, t) {
        if ($.isArray(e))
            return $.map(e, function (e) {
                return removeCommas(e, t);
            });
        if (e = e || 0, 'number' == typeof e)
            return e;
        t = t || '.';
        var n = new RegExp('[^0-9-' + t + ']', ['g']), r = parseFloat(('' + e).replace(/\((.*)\)/, '-1').replace(n, '').replace(t, '.'));
        return isNaN(r) ? 0 : r;
    }
    function toPercent(n) {
        var num = Math.round(parseFloat(n) * 10000);
        num /= 100;
        return num.toString() + '%';
    }
    function copyToClipBoard(s) {
        if (window.clipboardData) {
            window.clipboardData.setData('Text', s);
            alert('\u5DF2\u7ECF\u590D\u5236\u5230\u526A\u5207\u677F\uFF01' + '\n' + s);
        } else if (navigator.userAgent.indexOf('Opera') != -1) {
            window.location = s;
        } else if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
            } catch (e) {
                alert('\u88AB\u6D4F\u89C8\u5668\u62D2\u7EDD\uFF01\n\u8BF7\u5728\u6D4F\u89C8\u5668\u5730\u5740\u680F\u8F93\u5165\'about:config\'\u5E76\u56DE\u8F66\n\u7136\u540E\u5C06\'signed.applets.codebase_principal_support\'\u8BBE\u7F6E\u4E3A\'true\'');
            }
            var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
            if (!clip)
                return;
            var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
            if (!trans)
                return;
            trans.addDataFlavor('text/unicode');
            var str = Components.classes['@mozilla.org/supports-string;1'].createInstance(Components.interfaces.nsISupportsString);
            var copytext = s;
            str.data = copytext;
            trans.setTransferData('text/unicode', str, copytext.length * 2);
            var clipid = Components.interfaces.nsIClipboard;
            if (!clip)
                return false;
            clip.setData(trans, null, clipid.kGlobalClipboard);
            alert('\u5DF2\u7ECF\u590D\u5236\u5230\u526A\u5207\u677F\uFF01' + '\n' + s);
        }
    }
    function debounce(fn, wait) {
        var timer = null;
        return function () {
            if (timer) {
                return;
            }
            timer = setTimeout(function () {
                timer = null;
            }, wait);
            return fn.apply(this, arguments);
        };
    }
    var username = document.cookie.split(';')[0].split('=')[1];
    function setCookie(name, value, hours, path) {
        var exp = new Date();
        exp.setTime(exp.getTime() + hours * 60 * 60 * 1000);
        document.cookie = name + '=' + escape(value) + ';expires=' + exp.toGMTString() + ';path=' + path;
    }
    function getCookie(name) {
        var arr, reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
        if (arr = document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    }
    function delCookie(name) {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = getCookie(name);
        if (cval != null)
            document.cookie = name + '=' + cval + ';expires=' + exp.toGMTString();
    }
    function getUrlParam(name) {
        var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)');
        var r = window.location.search.substr(1).match(reg);
        if (r != null)
            return unescape(r[2]);
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
        getUrlParam: getUrlParam
    };
});