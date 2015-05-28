/**
 * @ignore
 * @file tabBox.js
 * @description tab美化
 * @author fanyy
 * @time 15-5-25
 */

define(function(require) {
    var $ = require('jquery');
    //tab 标签切换
    function tabBox(option) {
        return new _tabBox(option);
    }

    function _tabBox(e) {
        this.ctrlSelector = e.ctrlSelector || e.control,
            this.contentSelector = e.contentSelector || e.content,
            this.eventName = e.eventName || e.event_name,
            this.current = e.current || e.currentClass,
            this.defaultSelected = e.defaultSelected || e.default_selected,
            this.offset = e.offset,
            this.stopPropagation = e.stopPropagation || e.stop_propagation,
            this.preventDefault = e.preventDefault || e.prevent_default,
            this.prevCallback = e.prevCallback || e.prev_callback,
            this.callback = e.callback,
            this._init();
    }
    $.extend(_tabBox.prototype, {
        constructor: tabBox,
        _init: function() {
            if (this.ctrlSelector === "" || typeof this.ctrlSelector != "string")
             return;
            typeof this.contentSelector != "string" && (this.contentSelector = "");
            if (typeof this.eventName != "string" || this.eventName === "")
                this.eventName = "click";
            typeof this.current == "undefined" && (this.current = ""), typeof this.defaultSelected != "number" && (this.defaultSelected = parseInt("0" + this.defaultSelected, 10)), this.offset = typeof this.offset != "number" ? parseInt("0" + this.offset, 10) : this.offset < 0 ? 0 : this.offset, this.stopPropagation = typeof this.stopPropagation == "undefined" ? !1 : !!this.stopPropagation, this.preventDefault = typeof this.preventDefault == "undefined" ? !0 : !!this.preventDefault, this._create()
        },
        _current: function(e) {
            return $.isArray(this.current) ? this.current[e] : this.current
        },
        _create: function() {
            var e = this;
            $(this.ctrlSelector).each(function(n, r) {
                var i;
                i = n - e.offet < 0 ? !1 : !0, e.defaultSelected == n ? ($(this).addClass(e._current(n)), e.contentSelector !== "" && i && $(e.contentSelector).eq(n - e.offset).show()) : ($(this).removeClass(e._current(n)), e.contentSelector !== "" && i && $(e.contentSelector).eq(n - e.offset).hide()), $(this).bind(e.eventName, function(i) {
                    var u = !0;
                    typeof e.prevCallback == "function" && e.prevCallback.call(r, n, r) === !1 && (u = !1), u && e.defaultSelected != n && ($(this).addClass(e._current(n)), $(e.ctrlSelector).eq(e.defaultSelected).removeClass(e._current(e.defaultSelected)), e.contentSelector !== "" && e.defaultSelected - e.offset >= 0 && $(e.contentSelector).eq(e.defaultSelected - e.offset).hide(), e.defaultSelected = n, e.contentSelector !== "" && e.defaultSelected - e.offset >= 0 && $(e.contentSelector).eq(e.defaultSelected - e.offset).show(), typeof e.callback == "function" && e.callback.call(r, n, r)), e.preventDefault || (e.returnValue = !1), e.stopPropagation || (e.cancelBubble = !0)
                })
            })
        }
    });

    return tabBox; 
});
