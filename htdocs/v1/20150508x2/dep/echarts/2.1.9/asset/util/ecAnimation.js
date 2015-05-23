define('echarts/util/ecAnimation', [
    'require',
    'zrender/tool/util',
    'zrender/shape/Polygon'
], function (require) {
    var zrUtil = require('zrender/tool/util');
    function pointList(zr, oldShape, newShape, duration, easing) {
        var newPointList = newShape.style.pointList;
        var newPointListLen = newPointList.length;
        var oldPointList;
        if (!oldShape) {
            oldPointList = [];
            if (newShape._orient != 'vertical') {
                var y = newPointList[0][1];
                for (var i = 0; i < newPointListLen; i++) {
                    oldPointList[i] = [
                        newPointList[i][0],
                        y
                    ];
                }
            } else {
                var x = newPointList[0][0];
                for (var i = 0; i < newPointListLen; i++) {
                    oldPointList[i] = [
                        x,
                        newPointList[i][1]
                    ];
                }
            }
            if (newShape.type == 'half-smooth-polygon') {
                oldPointList[newPointListLen - 1] = zrUtil.clone(newPointList[newPointListLen - 1]);
                oldPointList[newPointListLen - 2] = zrUtil.clone(newPointList[newPointListLen - 2]);
            }
            oldShape = { style: { pointList: oldPointList } };
        }
        oldPointList = oldShape.style.pointList;
        var oldPointListLen = oldPointList.length;
        if (oldPointListLen == newPointListLen) {
            newShape.style.pointList = oldPointList;
        } else if (oldPointListLen < newPointListLen) {
            newShape.style.pointList = oldPointList.concat(newPointList.slice(oldPointListLen));
        } else {
            newShape.style.pointList = oldPointList.slice(0, newPointListLen);
        }
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, { pointList: newPointList }).start(easing);
    }
    function cloneStyle(target, source) {
        var len = arguments.length;
        for (var i = 2; i < len; i++) {
            var prop = arguments[i];
            target.style[prop] = source.style[prop];
        }
    }
    function rectangle(zr, oldShape, newShape, duration, easing) {
        var newShapeStyle = newShape.style;
        if (!oldShape) {
            oldShape = {
                position: newShape.position,
                style: {
                    x: newShapeStyle.x,
                    y: newShape._orient == 'vertical' ? newShapeStyle.y + newShapeStyle.height : newShapeStyle.y,
                    width: newShape._orient == 'vertical' ? newShapeStyle.width : 0,
                    height: newShape._orient != 'vertical' ? newShapeStyle.height : 0
                }
            };
        }
        var newX = newShapeStyle.x;
        var newY = newShapeStyle.y;
        var newWidth = newShapeStyle.width;
        var newHeight = newShapeStyle.height;
        var newPosition = [
                newShape.position[0],
                newShape.position[1]
            ];
        cloneStyle(newShape, oldShape, 'x', 'y', 'width', 'height');
        newShape.position = oldShape.position;
        zr.addShape(newShape);
        if (newPosition[0] != oldShape.position[0] || newPosition[1] != oldShape.position[1]) {
            zr.animate(newShape.id, '').when(duration, { position: newPosition }).start(easing);
        }
        zr.animate(newShape.id, 'style').when(duration, {
            x: newX,
            y: newY,
            width: newWidth,
            height: newHeight
        }).start(easing);
    }
    function candle(zr, oldShape, newShape, duration, easing) {
        if (!oldShape) {
            var y = newShape.style.y;
            oldShape = {
                style: {
                    y: [
                        y[0],
                        y[0],
                        y[0],
                        y[0]
                    ]
                }
            };
        }
        var newY = newShape.style.y;
        newShape.style.y = oldShape.style.y;
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, { y: newY }).start(easing);
    }
    function ring(zr, oldShape, newShape, duration, easing) {
        var x = newShape.style.x;
        var y = newShape.style.y;
        var r0 = newShape.style.r0;
        var r = newShape.style.r;
        if (newShape._animationAdd != 'r') {
            newShape.style.r0 = 0;
            newShape.style.r = 0;
            newShape.rotation = [
                Math.PI * 2,
                x,
                y
            ];
            zr.addShape(newShape);
            zr.animate(newShape.id, 'style').when(duration, {
                r0: r0,
                r: r
            }).start(easing);
            zr.animate(newShape.id, '').when(Math.round(duration / 3 * 2), {
                rotation: [
                    0,
                    x,
                    y
                ]
            }).start(easing);
        } else {
            newShape.style.r0 = newShape.style.r;
            zr.addShape(newShape);
            zr.animate(newShape.id, 'style').when(duration, { r0: r0 }).start(easing);
        }
    }
    function sector(zr, oldShape, newShape, duration, easing) {
        if (!oldShape) {
            if (newShape._animationAdd != 'r') {
                oldShape = {
                    style: {
                        startAngle: newShape.style.startAngle,
                        endAngle: newShape.style.startAngle
                    }
                };
            } else {
                oldShape = { style: { r0: newShape.style.r } };
            }
        }
        var startAngle = newShape.style.startAngle;
        var endAngle = newShape.style.endAngle;
        cloneStyle(newShape, oldShape, 'startAngle', 'endAngle');
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, {
            startAngle: startAngle,
            endAngle: endAngle
        }).start(easing);
    }
    function text(zr, oldShape, newShape, duration, easing) {
        if (!oldShape) {
            oldShape = {
                style: {
                    x: newShape.style.textAlign == 'left' ? newShape.style.x + 100 : newShape.style.x - 100,
                    y: newShape.style.y
                }
            };
        }
        var x = newShape.style.x;
        var y = newShape.style.y;
        cloneStyle(newShape, oldShape, 'x', 'y');
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, {
            x: x,
            y: y
        }).start(easing);
    }
    function polygon(zr, oldShape, newShape, duration, easing) {
        var rect = require('zrender/shape/Polygon').prototype.getRect(newShape.style);
        var x = rect.x + rect.width / 2;
        var y = rect.y + rect.height / 2;
        newShape.scale = [
            0.1,
            0.1,
            x,
            y
        ];
        zr.addShape(newShape);
        zr.animate(newShape.id, '').when(duration, {
            scale: [
                1,
                1,
                x,
                y
            ]
        }).start(easing);
    }
    function ribbon(zr, oldShape, newShape, duration, easing) {
        if (!oldShape) {
            oldShape = {
                style: {
                    source0: 0,
                    source1: newShape.style.source1 > 0 ? 360 : -360,
                    target0: 0,
                    target1: newShape.style.target1 > 0 ? 360 : -360
                }
            };
        }
        var source0 = newShape.style.source0;
        var source1 = newShape.style.source1;
        var target0 = newShape.style.target0;
        var target1 = newShape.style.target1;
        if (oldShape.style) {
            cloneStyle(newShape, oldShape, 'source0', 'source1', 'target0', 'target1');
        }
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, {
            source0: source0,
            source1: source1,
            target0: target0,
            target1: target1
        }).start(easing);
    }
    function gaugePointer(zr, oldShape, newShape, duration, easing) {
        if (!oldShape) {
            oldShape = { style: { angle: newShape.style.startAngle } };
        }
        var angle = newShape.style.angle;
        newShape.style.angle = oldShape.style.angle;
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, { angle: angle }).start(easing);
    }
    function icon(zr, oldShape, newShape, duration, easing, delay) {
        newShape.style._x = newShape.style.x;
        newShape.style._y = newShape.style.y;
        newShape.style._width = newShape.style.width;
        newShape.style._height = newShape.style.height;
        if (!oldShape) {
            var x = newShape._x || 0;
            var y = newShape._y || 0;
            newShape.scale = [
                0.01,
                0.01,
                x,
                y
            ];
            zr.addShape(newShape);
            zr.animate(newShape.id, '').delay(delay).when(duration, {
                scale: [
                    1,
                    1,
                    x,
                    y
                ]
            }).start(easing || 'QuinticOut');
        } else {
            rectangle(zr, oldShape, newShape, duration, easing);
        }
    }
    function line(zr, oldShape, newShape, duration, easing) {
        if (!oldShape) {
            oldShape = {
                style: {
                    xStart: newShape.style.xStart,
                    yStart: newShape.style.yStart,
                    xEnd: newShape.style.xStart,
                    yEnd: newShape.style.yStart
                }
            };
        }
        var xStart = newShape.style.xStart;
        var xEnd = newShape.style.xEnd;
        var yStart = newShape.style.yStart;
        var yEnd = newShape.style.yEnd;
        cloneStyle(newShape, oldShape, 'xStart', 'xEnd', 'yStart', 'yEnd');
        zr.addShape(newShape);
        zr.animate(newShape.id, 'style').when(duration, {
            xStart: xStart,
            xEnd: xEnd,
            yStart: yStart,
            yEnd: yEnd
        }).start(easing);
    }
    function markline(zr, oldShape, newShape, duration, easing) {
        if (!newShape.style.smooth) {
            newShape.style.pointList = !oldShape ? [
                [
                    newShape.style.xStart,
                    newShape.style.yStart
                ],
                [
                    newShape.style.xStart,
                    newShape.style.yStart
                ]
            ] : oldShape.style.pointList;
            zr.addShape(newShape);
            zr.animate(newShape.id, 'style').when(duration, {
                pointList: [
                    [
                        newShape.style.xStart,
                        newShape.style.yStart
                    ],
                    [
                        newShape._x || 0,
                        newShape._y || 0
                    ]
                ]
            }).start(easing || 'QuinticOut');
        } else {
            if (!oldShape) {
                newShape.style.pointListLength = 1;
                zr.addShape(newShape);
                newShape.style.pointList = newShape.style.pointList || newShape.getPointList(newShape.style);
                zr.animate(newShape.id, 'style').when(duration, { pointListLength: newShape.style.pointList.length }).start(easing || 'QuinticOut');
            } else {
                zr.addShape(newShape);
            }
        }
    }
    return {
        pointList: pointList,
        rectangle: rectangle,
        candle: candle,
        ring: ring,
        sector: sector,
        text: text,
        polygon: polygon,
        ribbon: ribbon,
        gaugePointer: gaugePointer,
        icon: icon,
        line: line,
        markline: markline
    };
});