define('common/util', [], function () {
    function addCommas(x) {
        if (isNaN(x)) {
            return '0.00';
        }
        x = (x + '').split('.');
        return x[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,') + (x.length > 1 ? '.' + x[1] : '');
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
            return fn.apply(null, arguments);
        };
    }
    return {
        addCommas: addCommas,
        copyToClipBoard: copyToClipBoard,
        debounce: debounce
    };
});