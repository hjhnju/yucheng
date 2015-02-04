define('zrender/config', [], function () {
    var config = {
            EVENT: {
                RESIZE: 'resize',
                CLICK: 'click',
                DBLCLICK: 'dblclick',
                MOUSEWHEEL: 'mousewheel',
                MOUSEMOVE: 'mousemove',
                MOUSEOVER: 'mouseover',
                MOUSEOUT: 'mouseout',
                MOUSEDOWN: 'mousedown',
                MOUSEUP: 'mouseup',
                GLOBALOUT: 'globalout',
                DRAGSTART: 'dragstart',
                DRAGEND: 'dragend',
                DRAGENTER: 'dragenter',
                DRAGOVER: 'dragover',
                DRAGLEAVE: 'dragleave',
                DROP: 'drop',
                touchClickDelay: 300
            },
            catchBrushException: false,
            debugMode: 0
        };
    return config;
});