/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/loadingEffect/Base', [
    'require',
    '../tool/util',
    '../shape/Text',
    '../shape/Rectangle'
], function (require) {
    var util = require('../tool/util');
    var TextShape = require('../shape/Text');
    var RectangleShape = require('../shape/Rectangle');
    var DEFAULT_TEXT = 'Loading...';
    var DEFAULT_TEXT_FONT = 'normal 16px Arial';
    function Base(options) {
        this.setOptions(options);
    }
    Base.prototype.createTextShape = function (textStyle) {
        return new TextShape({
            highlightStyle: util.merge({
                x: this.canvasWidth / 2,
                y: this.canvasHeight / 2,
                text: DEFAULT_TEXT,
                textAlign: 'center',
                textBaseline: 'middle',
                textFont: DEFAULT_TEXT_FONT,
                color: '#333',
                brushType: 'fill'
            }, textStyle, true)
        });
    };
    Base.prototype.createBackgroundShape = function (color) {
        return new RectangleShape({
            highlightStyle: {
                x: 0,
                y: 0,
                width: this.canvasWidth,
                height: this.canvasHeight,
                brushType: 'fill',
                color: color
            }
        });
    };
    Base.prototype.start = function (painter) {
        this.canvasWidth = painter._width;
        this.canvasHeight = painter._height;
        function addShapeHandle(param) {
            painter.storage.addHover(param);
        }
        function refreshHandle() {
            painter.refreshHover();
        }
        this.loadingTimer = this._start(addShapeHandle, refreshHandle);
    };
    Base.prototype._start = function () {
        return setInterval(function () {
        }, 10000);
    };
    Base.prototype.stop = function () {
        clearInterval(this.loadingTimer);
    };
    Base.prototype.setOptions = function (options) {
        this.options = options || {};
    };
    Base.prototype.adjust = function (value, region) {
        if (value <= region[0]) {
            value = region[0];
        } else if (value >= region[1]) {
            value = region[1];
        }
        return value;
    };
    Base.prototype.getLocation = function (loc, totalWidth, totalHeight) {
        var x = loc.x != null ? loc.x : 'center';
        switch (x) {
        case 'center':
            x = Math.floor((this.canvasWidth - totalWidth) / 2);
            break;
        case 'left':
            x = 0;
            break;
        case 'right':
            x = this.canvasWidth - totalWidth;
            break;
        }
        var y = loc.y != null ? loc.y : 'center';
        switch (y) {
        case 'center':
            y = Math.floor((this.canvasHeight - totalHeight) / 2);
            break;
        case 'top':
            y = 0;
            break;
        case 'bottom':
            y = this.canvasHeight - totalHeight;
            break;
        }
        return {
            x: x,
            y: y,
            width: totalWidth,
            height: totalHeight
        };
    };
    return Base;
});