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
            return '0.00';
        }
        x = (x + '').split('.');
        return x[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,')
            + (x.length > 1 ? ('.' + x[1]) : '');
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
        }
        else if (navigator.userAgent.indexOf("Opera") != -1) {
            window.location = s;
        }
        else if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }
            catch (e) {
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
        return function () {

            if (timer) {
                return;
            }

            timer = setTimeout(function () {
                timer = null;
            }, wait);

            return fn.apply(null, arguments);
        };
    }

    return {
        addCommas: addCommas,
        copyToClipBoard: copyToClipBoard,
        debounce: debounce
    };
});
