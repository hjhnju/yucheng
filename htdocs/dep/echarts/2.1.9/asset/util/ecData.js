/*! 2015 Baidu Inc. All Rights Reserved */
define('echarts/util/ecData', function () {
    function pack(shape, series, seriesIndex, data, dataIndex, name, special, special2) {
        var value;
        if (typeof data != 'undefined') {
            value = data.value == null ? data : data.value;
        }
        shape._echartsData = {
            '_series': series,
            '_seriesIndex': seriesIndex,
            '_data': data,
            '_dataIndex': dataIndex,
            '_name': name,
            '_value': value,
            '_special': special,
            '_special2': special2
        };
        return shape._echartsData;
    }
    function get(shape, key) {
        var data = shape._echartsData;
        if (!key) {
            return data;
        }
        switch (key) {
        case 'series':
        case 'seriesIndex':
        case 'data':
        case 'dataIndex':
        case 'name':
        case 'value':
        case 'special':
        case 'special2':
            return data && data['_' + key];
        }
        return null;
    }
    function set(shape, key, value) {
        shape._echartsData = shape._echartsData || {};
        switch (key) {
        case 'series':
        case 'seriesIndex':
        case 'data':
        case 'dataIndex':
        case 'name':
        case 'value':
        case 'special':
        case 'special2':
            shape._echartsData['_' + key] = value;
            break;
        }
    }
    function clone(source, target) {
        target._echartsData = {
            '_series': source._echartsData._series,
            '_seriesIndex': source._echartsData._seriesIndex,
            '_data': source._echartsData._data,
            '_dataIndex': source._echartsData._dataIndex,
            '_name': source._echartsData._name,
            '_value': source._echartsData._value,
            '_special': source._echartsData._special,
            '_special2': source._echartsData._special2
        };
    }
    return {
        pack: pack,
        set: set,
        get: get,
        clone: clone
    };
});