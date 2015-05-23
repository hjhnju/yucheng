/**
 * @插件名 selectBox
 * @作用 用Jquery开发的一款美化下拉框插件 
 * @ fanyy 
 * @日期 2015-5-18 
 */

define(function(require) {
    var $ = require('jquery');
    $.fn.selectBox = function(options) {
        // selectBox的配置
        var selectBox_config = {
            className: "",
            height: 30,
            width: 150,
            optionColor: "#3BAFDA",
            selectedColor: "#3BAFDA",
            disabled: false, // 是否禁用,默认false
            selectText: "", // 设置哪个文本被选中
            onSelect: "", // 点击后选中事件
            onClose: "" //关闭后事件
        }

        var settings = $.extend({}, selectBox_config, options);
        // 每个下拉框组件的操作
        return this.each(function(elem_id) {
            var obj = this;
            var _offset = $(this).offset();
            var top = _offset.top + $(document).scrollTop();
            var elem_width = $(obj).width();
            var left = _offset.left + $(document).scrollLeft();
            var elem_id = $(obj).attr("id"); // 元素的ID
            // 生成的div的样式
            var _selectBody = "<div onselectstart='return false;' class=" + settings.className + "><div class='selectBox_div selectBox_div_" + elem_id + "'  id='selectBox_" + elem_id + "'><span style='float:left;' id='selectBox_span_" + elem_id + "'></span><span class='selectBox_icon' id='selectBox_icon_" + elem_id + "'></span></div><div class='selectBox_options selectBox_options_" + elem_id + "'></div></div>";
            $("#" + elem_id).after(_selectBody);
            $(obj).addClass("select_hide");

            // 设置selectBox显示的位置
            /*    $(".selectBox_div_" + elem_id).css({
                    "height": settings.height,
                    "width": settings.width
                     "left": left,
                    "top": top 
                });*/

            // 设置默认显示在div上的值
            if (settings.selectText != "" && settings.selectText != undefined) {
                $(".selectBox_div_" + elem_id + " span").first().text(settings.selectText);
            } else {
                var selectText = $(obj).children("option:selected").text();
                //$(obj).children("option ").first().text()
                $(".selectBox_div_" + elem_id + " span").first().text(selectText);
            }

            // 是否禁用下拉框
            if (settings.disabled) {
                $(".selectBox_div_" + elem_id).addClass("selectBox_no_select");
                $("#selectBox_icon_" + elem_id).css({
                    "cursor": "default"
                });
                return;
            }
            // 点击div显示列表
            $(".selectBox_div_" + elem_id + ",#selectBox_span_" + elem_id + ",#selectBox_options_" + elem_id + "").bind("click", function(event) {
                // 当前div中的值
                var selected_text = $(".selectBox_div_" + elem_id + " span").first().text();

                event.stopPropagation(); //  阻止事件冒泡

                if ($(".selectBox_ul_" + elem_id + " li").length > 0) {
                    // 隐藏和显示div
                    $(".selectBox_options_" + elem_id).empty().css({
                        "border-top": "none"
                    });
                    return;
                } else {
                    $(".selectBox_options_" + elem_id).css({
                        "border-top": "solid 1px #CFCFCF"
                    });
                    $(".selectBox_options ul li").remove();
                    // 添加列表项
                    var ul = "<ul class='selectBox_ul_" + elem_id + "'>";
                    $(obj).children("option").each(function() {
                        if ($(this).text() == selected_text) {
                            ul += "<li class='selectBox_options_selected' style='font-size:13px;background-color:" + settings.selectedColor + ";color:#fff;height:" + (settings.height - 3) + "px; line-height:" + (settings.height - 3) + "px;font-size:13px;'><label style='display:none;'>" + $(this).val() + "</label><label>" + $(this).text() + "</label></li>";
                        } else {
                            if (!$(this).attr('disabled')) {
                                ul += "<li style='font-size:13px;height:" + (settings.height - 3) + "px; line-height:" + (settings.height - 3) + "px;'><label style='display:none;'>" + $(this).val() + "</label><label>" + $(this).text() + "</label></li>";
                            };
                        }
                    });
                    ul += "</ul>";
                    /*    $(".selectBox_options_" + elem_id).css({
                            "width": settings.width + 5,
                            "left": left,
                            "top": top + settings.height
                        }).append(ul).show();*/

                    $(".selectBox_options_" + elem_id).append(ul).show();
                    // li鼠标滑过事件
                    $(".selectBox_options_" + elem_id + " ul li").hover(function() {
                        $(this).css({
                            "background-color": settings.optionColor,
                            "color": "#fff"
                        });
                    }, function() {
                        if ($(this).hasClass("selectBox_options_selected")) {
                            $(this).css({
                                "background-color": settings.optionColor,
                                "color": "#fff"
                            });
                        } else {
                            $(this).css({
                                "background-color": "",
                                "color": "#666"
                            });
                        }

                    });

                    // 每个li点击事件
                    $(".selectBox_ul_" + elem_id + " li").bind("click", function() {
                        var value = $(this).children("label").first().text();
                        var text = $(this).children("label").first().next().text();
                        //设置选中的值
                        $(obj).val(value);
                        $(".selectBox_div_" + elem_id + " span").first().text(text);
                        $(".selectBox_options_" + elem_id).empty().hide();
                        // 回调函数
                        if (settings.onSelect != undefined && settings.onSelect != "" && typeof settings.onSelect == "function") {
                            settings.onSelect($(obj).parent(), value, text);
                        }
                    });
                }

            });
            // 点击div外面关闭列表
            $(document).bind("click", function(event) {
                var e = event || window.event;
                var elem = e.srcElement || e.target;
                if (elem.id == "selectBox_" + elem_id || elem.id == "selectBox_icon_" + elem_id || elem.id == "selectBox_span_" + elem_id) {
                    return;
                } else {
                    $(".selectBox_options_" + elem_id).empty().hide();

                    //回调函数 
                    if (settings.onClose != undefined && settings.onClose != "" && typeof settings.onClose == "function") {
                        settings.onClose($(obj).parent());
                    }
                }
            });

        });
    }
});;
