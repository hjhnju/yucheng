/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/util/PathProxy', function (require) {
    var vector = require('../../tool/vector');
    var PathSegment = function (command, points) {
        this.command = command;
        this.points = points || null;
    };
    var PathProxy = function () {
        this.pathCommands = [];
        this._ctx = null;
        this._min = [];
        this._max = [];
    };
    PathProxy.prototype.fastBoundingRect = function () {
        var min = this._min;
        var max = this._max;
        min[0] = min[1] = Infinity;
        max[0] = max[1] = -Infinity;
        for (var i = 0; i < this.pathCommands.length; i++) {
            var seg = this.pathCommands[i];
            var p = seg.points;
            switch (seg.command) {
            case 'M':
                vector.min(min, min, p);
                vector.max(max, max, p);
                break;
            case 'L':
                vector.min(min, min, p);
                vector.max(max, max, p);
                break;
            case 'C':
                for (var j = 0; j < 6; j += 2) {
                    min[0] = Math.min(min[0], min[0], p[j]);
                    min[1] = Math.min(min[1], min[1], p[j + 1]);
                    max[0] = Math.max(max[0], max[0], p[j]);
                    max[1] = Math.max(max[1], max[1], p[j + 1]);
                }
                break;
            case 'Q':
                for (var j = 0; j < 4; j += 2) {
                    min[0] = Math.min(min[0], min[0], p[j]);
                    min[1] = Math.min(min[1], min[1], p[j + 1]);
                    max[0] = Math.max(max[0], max[0], p[j]);
                    max[1] = Math.max(max[1], max[1], p[j + 1]);
                }
                break;
            case 'A':
                var cx = p[0];
                var cy = p[1];
                var rx = p[2];
                var ry = p[3];
                min[0] = Math.min(min[0], min[0], cx - rx);
                min[1] = Math.min(min[1], min[1], cy - ry);
                max[0] = Math.max(max[0], max[0], cx + rx);
                max[1] = Math.max(max[1], max[1], cy + ry);
                break;
            }
        }
        return {
            x: min[0],
            y: min[1],
            width: max[0] - min[0],
            height: max[1] - min[1]
        };
    };
    PathProxy.prototype.begin = function (ctx) {
        this._ctx = ctx || null;
        this.pathCommands.length = 0;
        return this;
    };
    PathProxy.prototype.moveTo = function (x, y) {
        this.pathCommands.push(new PathSegment('M', [
            x,
            y
        ]));
        if (this._ctx) {
            this._ctx.moveTo(x, y);
        }
        return this;
    };
    PathProxy.prototype.lineTo = function (x, y) {
        this.pathCommands.push(new PathSegment('L', [
            x,
            y
        ]));
        if (this._ctx) {
            this._ctx.lineTo(x, y);
        }
        return this;
    };
    PathProxy.prototype.bezierCurveTo = function (x1, y1, x2, y2, x3, y3) {
        this.pathCommands.push(new PathSegment('C', [
            x1,
            y1,
            x2,
            y2,
            x3,
            y3
        ]));
        if (this._ctx) {
            this._ctx.bezierCurveTo(x1, y1, x2, y2, x3, y3);
        }
        return this;
    };
    PathProxy.prototype.quadraticCurveTo = function (x1, y1, x2, y2) {
        this.pathCommands.push(new PathSegment('Q', [
            x1,
            y1,
            x2,
            y2
        ]));
        if (this._ctx) {
            this._ctx.quadraticCurveTo(x1, y1, x2, y2);
        }
        return this;
    };
    PathProxy.prototype.arc = function (cx, cy, r, startAngle, endAngle, anticlockwise) {
        this.pathCommands.push(new PathSegment('A', [
            cx,
            cy,
            r,
            r,
            startAngle,
            endAngle - startAngle,
            0,
            anticlockwise ? 0 : 1
        ]));
        if (this._ctx) {
            this._ctx.arc(cx, cy, r, startAngle, endAngle, anticlockwise);
        }
        return this;
    };
    PathProxy.prototype.arcTo = function (x1, y1, x2, y2, radius) {
        if (this._ctx) {
            this._ctx.arcTo(x1, y1, x2, y2, radius);
        }
        return this;
    };
    PathProxy.prototype.rect = function (x, y, w, h) {
        if (this._ctx) {
            this._ctx.rect(x, y, w, h);
        }
        return this;
    };
    PathProxy.prototype.closePath = function () {
        this.pathCommands.push(new PathSegment('z'));
        if (this._ctx) {
            this._ctx.closePath();
        }
        return this;
    };
    PathProxy.prototype.isEmpty = function () {
        return this.pathCommands.length === 0;
    };
    PathProxy.PathSegment = PathSegment;
    return PathProxy;
});