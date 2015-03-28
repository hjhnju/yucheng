/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-11
 */

define(function (require) {

    var $ = require('jquery');

    function init() {

        var header = require('common/header');
        header.init();
        lunbox($('.banner-floation'), $('.banner-item'), $('.banner-item-list'), $('.banner-select-link'));
        scroll();
        slider();
    }


    function lunbox(overbox, item, list, btn) {

        var oUl = item;
        var timer;
        var now = 0;
        var aLi = list;
        var aLiWidth = aLi.eq(0).width();
        var small = btn;
        var length = aLi.length;
        var left = $('.jiantou-left');
        var right = $('.jiantou-right');

        left.click(function () {

            timer && clearTimeout(timer);
            now = (now - 1 + length)%length;
            console.log(now);

            lunbo(now);

        });

        right.click(function () {

            timer && clearTimeout(timer);
            now = (now+1) % length;
            console.log(now);
            lunbo(now);

        });

        function lunbo(index) {
            now = index;
            small.removeClass('current');
            small.eq(now).addClass('current');
            oUl.stop(true).animate({
                'left': -now * aLiWidth + 'px'
            }, 300, function () {
                timer = setTimeout(function () {
                    now = ++now % length;
                    lunbo(now);
                }, 5000);
            });
        }

        small.mouseenter(function () {
            timer && clearTimeout(timer);
            var index = $(this).index();
            lunbo(index);
        });


        timer = setTimeout(function(){
            lunbo(0);
        }, 5000);

    }
    
    /*
     * 滚动公告模块  
     * 范莹莹
     * */ 
    function scroll () {
    	 var noticeObj1, noticeObj2 = $("#notice");
    	 var items = noticeObj2.find(".item-wrap");
    	 var item = items.find(".item");
    	 var length = item.length;
    	 var r = 64;
    	 var m = 0;  
    	 function t() {
 	        items.on("mouseenter", function () {
 	            i();
 	        }).on("mouseleave", function () {
 	            e();
 	        });
 	    }
 	    function e() {
 	        i(), noticeObj1 = setTimeout(function () {
 	            m = (m + 1) % length, items.animate({ top: 0 == m ? -length * r : -m * r }, 800, function () {
 	                0 == m && items.css("top", 0), e();
 	            });
 	        }, 4e3);
 	    }
 	    function i() {
 	        clearTimeout(noticeObj1);
 	    }
 	    function o() {
 	        var n = item.eq(0).clone();
 	        n.attr("data-order", length), items.append(n);
 	    } 
    	 
    	 length > 1 && (o(), e(), t()); 
    }
    
    /*
     * banner 效果
     * */
    function slider() { 
    	/*var  slider = require('common/extra/jquery.flexslider'); 
    	 $('.banner-floation').slider({
    	        directionNav: true,
    	        pauseOnAction: false
    	 });*/
    	 
    }
    return {
        init: init
    };
});
