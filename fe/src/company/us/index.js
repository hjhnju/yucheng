/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 15-1-6
 */

define(function (require) {

    var $ = require('jquery');

    function init() {
         
    }

    /*
     * 幻灯片效果
     * */
    function slide() { 
    	var  blueimp = require('common/extra/Gallery/js/blueimp-gallery');
    	 $("#links").click(function (event) {
    		event = event || window.event;
    		 var target = event.target || event.srcElement;
 	        var link = target.src ? target.parentNode : target;
 	        var options = {index: link, event: event};
 	        var links = this.getElementsByTagName('a'); 
 	        blueimp(links, options);
    	});
    	 
    }


    return {
        init: init,
        slide:slide
        
    };
});
