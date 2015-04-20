/**
 * @ignore
 * @file index.js
 * @author fanyy 
 * @time 15-4-6
 */

define(function(require) {

    var $ = require('jquery');
    var header = require('common/header');

    function init() {
        header.init();
        bindEvents();
    }

    function bindEvents() {
        //点击问题滑出帮助内容  
        $(".operate-content .list-title").click(function() {
            var current = $(this).next();
            var all = $(".operate-content .list-text");
            current.slideToggle();
            current.hasClass("current") ? current.removeClass("current") : (all.removeClass("current"), current.addClass("current"));

            var others = $(".operate-content .list-text").not(".current");
            others.slideUp();
        });
        //锚点事件
        $(".menu-list a").click(
            function() {
                var id = $(this).attr("target-id");
                if (id) {
                    $('html, body').animate({
                        scrollTop: $("#" + id).offset().top
                    }, 1000, "linear");
                }
            }
        );
    }
    return {
        init: init
    };
});
