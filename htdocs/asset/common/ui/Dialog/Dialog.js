define('common/ui/Dialog/Dialog', function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var POPUPTMPL = require('./dialog.tpl');
    function is(type, obj) {
        var cls = Object.prototype.toString.call(obj).slice(8, -1);
        return obj !== undefined && obj !== null && cls === type;
    }
    function isFunction(obj) {
        return is('Function', obj);
    }
    var defaultOpts = {
            mask: true,
            width: 200,
            defaultTitle: true,
            title: '',
            content: ''
        };
    var view = {
            options: {},
            popupMask: null,
            popup: null
        };
    function init() {
        etpl.compile(POPUPTMPL);
    }
    function showPopup(conf, cb) {
        var options = $.extend({}, defaultOpts, conf);
        options.width = (options.width + '').replace(/px$/, '');
        view.options = options;
        $('body').append(etpl.render('dialogWarp', options));
        fixPosition();
        bindEvents();
        cb && cb();
    }
    function fixPosition() {
        view.popupMask = $('#mk-dialog-mask');
        view.popup = $('#mk-dialog');
        if (view.options.mask) {
            view.popupMask.addClass('mask');
            view.popup.addClass('mask');
        }
        view.popupMask.css({
            width: $(document).width(),
            height: $(document).height()
        });
        var popupWidth = view.popup.width();
        var popupHeight = view.popup.height();
        var popupTop = $(window).scrollTop() + Math.round(($(window).height() - popupHeight) / 2);
        var popupLeft = $(window).scrollLeft() + Math.round(($(window).width() - popupWidth) / 2);
        view.popup.css({
            top: popupTop + 'px',
            left: popupLeft + 'px'
        });
    }
    function closePopup(cb) {
        view.popup && view.popup.remove();
        view.popupMask && view.popupMask.remove();
        cb && isFunction(cb) && cb.call(null);
    }
    function bindEvents() {
        $(window).on('resize', function () {
            fixPosition();
        });
        $('#popup-close').on('click', closePopup);
    }
    return {
        init: init,
        show: showPopup,
        closePopup: closePopup
    };
});