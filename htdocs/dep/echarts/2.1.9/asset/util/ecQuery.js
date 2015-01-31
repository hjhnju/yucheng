define('echarts/util/ecQuery', function (require) {
    var zrUtil = require('zrender/tool/util');
    function query(optionTarget, optionLocation) {
        if (typeof optionTarget == 'undefined') {
            return;
        }
        if (!optionLocation) {
            return optionTarget;
        }
        optionLocation = optionLocation.split('.');
        var length = optionLocation.length;
        var curIdx = 0;
        while (curIdx < length) {
            optionTarget = optionTarget[optionLocation[curIdx]];
            if (typeof optionTarget == 'undefined') {
                return;
            }
            curIdx++;
        }
        return optionTarget;
    }
    function deepQuery(ctrList, optionLocation) {
        var finalOption;
        for (var i = 0, l = ctrList.length; i < l; i++) {
            finalOption = query(ctrList[i], optionLocation);
            if (typeof finalOption != 'undefined') {
                return finalOption;
            }
        }
    }
    function deepMerge(ctrList, optionLocation) {
        var finalOption;
        var len = ctrList.length;
        while (len--) {
            var tempOption = query(ctrList[len], optionLocation);
            if (typeof tempOption != 'undefined') {
                if (typeof finalOption == 'undefined') {
                    finalOption = zrUtil.clone(tempOption);
                } else {
                    zrUtil.merge(finalOption, tempOption, true);
                }
            }
        }
        return finalOption;
    }
    return {
        query: query,
        deepQuery: deepQuery,
        deepMerge: deepMerge
    };
});