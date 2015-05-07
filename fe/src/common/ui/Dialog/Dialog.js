/**
 * @ignore
 * @file Dialog.js
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 15-1-2
 */

define(function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');

    /**
     * 弹出浮层内容
     *
     * @const
     * @type {string}
     */
    var POPUPTMPL = require('./dialog.tpl');


    /**
     * 判断类型
     *
     * @inner
     * @param {string} type 类型
     * @param {Object} obj 需要被判断的类型
     * @return {boolean}
     */
    function is(type, obj) {
        var cls = Object.prototype.toString.call(obj).slice(8, -1);
        return obj !== undefined && obj !== null && cls === type;
    }

    /**
     * 判断function类型
     *
     * @inner
     * @param {Object} obj 需要被判断的类型
     * @return {boolean}
     */
    function isFunction(obj) {
        return is('Function', obj);
    }

    /**
     * 浮层默认配置
     *
     * @const
     * @type {Object}
     */
    var defaultOpts = {
        mask: true,             //是否有遮罩层
        width: 200,             //浮层的宽度 
        defaultTitle: true,     //是否有title
        title: '',              //title内容
        bgClass:'',             //bg 的样式
        content: '',            //浮层内容
        contentClass:'',        //内容样式
        data:{},                //传递的数据
        confirmBack:function(){}//点击确定的回掉函数

    };

    var view = {
        options: {},
        popupMask: null,
        popup: null
    };

    function init() {
        etpl.compile(POPUPTMPL);
    }

    /**
     * 显示浮层
     *
     * @param {Object} conf 自定义配置
     * @param {Function=} 回调
     */
    function showPopup(conf, cb) {
        var options = $.extend({}, defaultOpts, conf);
        options.width = (options.width + '').replace(/px$/, '');
        view.options = options;

        $('body').append(etpl.render('dialogWarp', options));

        fixPosition();
        bindEvents();

        cb && cb();
    }

    /**
     * 设置浮层位置
     *
     * @inner
     */
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
        var popupTop =
            $(window).scrollTop()
            + Math.round(($(window).height() - popupHeight) / 2);
        var popupLeft =
            $(window).scrollLeft()
            + Math.round(($(window).width() - popupWidth) / 2);
        view.popup.css({
            top: popupTop + 'px',
            left: popupLeft + 'px'
        });
    }

    /**
     * 关闭浮层
     *
     * @param {Function} cb 回调方法
     */
    function closePopup(cb) {
        view.popup && view.popup.remove();
        view.popupMask && view.popupMask.remove();
        cb && isFunction(cb) && cb.call(null);
    }

    /**
     * 初始化浮层事件
     *
     * @inner
     */
    function bindEvents() {
        $(window).on('resize', function () {
            fixPosition();
        });
        $('#popup-close,.popup-close').on('click', closePopup);


    }

   /**
    * 显示 (确定-取消)选择 浮层
    *
    * @inner
    */
    function confirmPopup(conf, cb) {
        var options = $.extend({}, defaultOpts, conf);
        options.width = (options.width + '').replace(/px$/, '');
        view.options = options;

        $('body').append(etpl.render('dialogConfirm', options));

        fixPosition();
        bindEvents();

        cb && cb();

        //确定回调函数
        $('.popup-confirm').click(function(){
            options.confirmBack(options.data);
        });
    }

    return {
        init: init,
        show: showPopup,
        closePopup: closePopup,
        confirm: confirmPopup
    };
});
