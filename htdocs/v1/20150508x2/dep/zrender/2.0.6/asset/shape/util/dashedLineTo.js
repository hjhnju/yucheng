define([], function () {
    var dashPattern = [
            5,
            5
        ];
    return function (ctx, x1, y1, x2, y2, dashLength) {
        if (ctx.setLineDash) {
            dashPattern[0] = dashPattern[1] = dashLength;
            ctx.setLineDash(dashPattern);
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            return;
        }
        dashLength = typeof dashLength != 'number' ? 5 : dashLength;
        var dx = x2 - x1;
        var dy = y2 - y1;
        var numDashes = Math.floor(Math.sqrt(dx * dx + dy * dy) / dashLength);
        dx = dx / numDashes;
        dy = dy / numDashes;
        var flag = true;
        for (var i = 0; i < numDashes; ++i) {
            if (flag) {
                ctx.moveTo(x1, y1);
            } else {
                ctx.lineTo(x1, y1);
            }
            flag = !flag;
            x1 += dx;
            y1 += dy;
        }
        ctx.lineTo(x2, y2);
    };
});