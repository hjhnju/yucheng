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
        slide();
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
                    }, 400, "linear");
                }
            }
        );

        //操作演示点击问题滑出帮助内容  
        $(".operate-demo-content .content-title").click(function() {
            var current = $(this).nextAll();
            var all = $(".operate-demo-content .content-list");
            current.slideToggle(1000);
            current.hasClass("current") ? current.removeClass("current") : (all.removeClass("current"), current.addClass("current"));

            var others = $(".operate-demo-content .content-text").not(".current");
            others.slideUp(1000);
        });

        //操作演示锚点事件
        $(".menu-list a").click(
            function() {
                var id = $(this).attr("target-id");
                if (id) {
                    var current = $("#" + id + " .content-list");
                    var others = $(".operate-demo-content .content-list").not("#" + id + " .content-list");
                    others.hide();
                    current.show(400, function() {
                        $('html, body').animate({
                            scrollTop: $("#" + id).offset().top
                        }, 400, "linear");
                    });
                }
            });
        //新手指引按钮光泽效果 

        $('.guide-btn').mouseenter(function() {
            var shine = $(".guide-btn-shine");
            shine.stop();
            shine.css("background-position", "-99px -20px");

            shine.animate({
                backgroundPositionX: "132px",
                backgroundPositionY: "-20px"
            }, 400);
        }).mouseleave(function() {

        });
        setInterval(function() {
            var shine = $(".guide-btn-shine");
            shine.stop();
            shine.css("background-position", "-99px -20px");

            shine.animate({
                backgroundPositionX: "132px",
                backgroundPositionY: "-20px"
            }, 400);

        }, 3000);
    }

    /*
     * 幻灯片效果
     * */
    function slide() {
        var blueimp = require('common/extra/Gallery/js/blueimp-gallery');
        event = event || window.event;
        var target = event.target || event.srcElement;
        var link = target.src ? target.parentNode : target;
        var options = {
            index: link,
            event: event,
            onclosed: function() {
                $("#scrollUp").show();
            }
        };
        var links = this.getElementsByTagName('a');
        blueimp(links, options);
        //隐藏返回顶部按钮
        $("#scrollUp").hide();
    }

    return {
        init: init
    };
});
