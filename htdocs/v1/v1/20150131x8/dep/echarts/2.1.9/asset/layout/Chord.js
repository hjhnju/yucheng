define('echarts/layout/Chord', ['require'], function (require) {
    var ChordLayout = function (opts) {
        opts = opts || {};
        this.sort = opts.sort || null;
        this.sortSub = opts.sortSub || null;
        this.padding = 0.05;
        this.startAngle = opts.startAngle || 0;
        this.clockWise = opts.clockWise == null ? false : opts.clockWise;
        this.center = opts.center || [
            0,
            0
        ];
        this.directed = true;
    };
    ChordLayout.prototype.run = function (graphs) {
        if (!(graphs instanceof Array)) {
            graphs = [graphs];
        }
        var gl = graphs.length;
        if (!gl) {
            return;
        }
        var graph0 = graphs[0];
        var nl = graph0.nodes.length;
        var groups = [];
        var sumSize = 0;
        for (var i = 0; i < nl; i++) {
            var g0node = graph0.nodes[i];
            var group = {
                    size: 0,
                    subGroups: [],
                    node: g0node
                };
            groups.push(group);
            var sumWeight = 0;
            for (var k = 0; k < graphs.length; k++) {
                var graph = graphs[k];
                var node = graph.getNodeById(g0node.id);
                if (!node) {
                    continue;
                }
                group.size += node.layout.size;
                var edges = this.directed ? node.outEdges : node.edges;
                for (var j = 0; j < edges.length; j++) {
                    var e = edges[j];
                    var w = e.layout.weight;
                    group.subGroups.push({
                        weight: w,
                        edge: e,
                        graph: graph
                    });
                    sumWeight += w;
                }
            }
            sumSize += group.size;
            var multiplier = group.size / sumWeight;
            for (var j = 0; j < group.subGroups.length; j++) {
                group.subGroups[j].weight *= multiplier;
            }
            if (this.sortSub === 'ascending') {
                group.subGroups.sort(compareSubGroups);
            } else if (this.sort === 'descending') {
                group.subGroups.sort(compareSubGroups);
                group.subGroups.reverse();
            }
        }
        if (this.sort === 'ascending') {
            groups.sort(compareGroups);
        } else if (this.sort === 'descending') {
            groups.sort(compareGroups);
            groups.reverse();
        }
        var multiplier = (Math.PI * 2 - this.padding * nl) / sumSize;
        var angle = this.startAngle;
        var sign = this.clockWise ? 1 : -1;
        for (var i = 0; i < nl; i++) {
            var group = groups[i];
            group.node.layout.startAngle = angle;
            group.node.layout.endAngle = angle + sign * group.size * multiplier;
            group.node.layout.subGroups = [];
            for (var j = 0; j < group.subGroups.length; j++) {
                var subGroup = group.subGroups[j];
                subGroup.edge.layout.startAngle = angle;
                angle += sign * subGroup.weight * multiplier;
                subGroup.edge.layout.endAngle = angle;
            }
            angle = group.node.layout.endAngle + sign * this.padding;
        }
    };
    var compareSubGroups = function (a, b) {
        return a.weight - b.weight;
    };
    var compareGroups = function (a, b) {
        return a.size - b.size;
    };
    return ChordLayout;
});