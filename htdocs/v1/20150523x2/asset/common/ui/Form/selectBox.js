define('common/ui/Form/selectBox', [
    'require',
    'jquery'
], function (require) {
    var $ = require('jquery');
    $.fn.selectBox = function (options) {
        var selectBox_config = {
                className: '',
                height: 30,
                width: 150,
                optionColor: '#3BAFDA',
                selectedColor: '#3BAFDA',
                disabled: false,
                selectText: '',
                onSelect: '',
                onClose: ''
            };
        var settings = $.extend({}, selectBox_config, options);
        return this.each(function (elem_id) {
            var obj = this;
            var _offset = $(this).offset();
            var top = _offset.top + $(document).scrollTop();
            var elem_width = $(obj).width();
            var left = _offset.left + $(document).scrollLeft();
            var elem_id = $(obj).attr('id');
            var _selectBody = '<div onselectstart=\'return false;\' class=' + settings.className + '><div class=\'selectBox_div selectBox_div_' + elem_id + '\'  id=\'selectBox_' + elem_id + '\'><span style=\'float:left;\' id=\'selectBox_span_' + elem_id + '\'></span><span class=\'selectBox_icon\' id=\'selectBox_icon_' + elem_id + '\'></span></div><div class=\'selectBox_options selectBox_options_' + elem_id + '\'></div></div>';
            $('#' + elem_id).after(_selectBody);
            $(obj).addClass('select_hide');
            if (settings.selectText != '' && settings.selectText != undefined) {
                $('.selectBox_div_' + elem_id + ' span').first().text(settings.selectText);
            } else {
                var selectText = $(obj).children('option:selected').text();
                $('.selectBox_div_' + elem_id + ' span').first().text(selectText);
            }
            if (settings.disabled) {
                $('.selectBox_div_' + elem_id).addClass('selectBox_no_select');
                $('#selectBox_icon_' + elem_id).css({ 'cursor': 'default' });
                return;
            }
            $('.selectBox_div_' + elem_id + ',#selectBox_span_' + elem_id + ',#selectBox_options_' + elem_id + '').bind('click', function (event) {
                var selected_text = $('.selectBox_div_' + elem_id + ' span').first().text();
                event.stopPropagation();
                if ($('.selectBox_ul_' + elem_id + ' li').length > 0) {
                    $('.selectBox_options_' + elem_id).empty().css({ 'border-top': 'none' });
                    return;
                } else {
                    $('.selectBox_options_' + elem_id).css({ 'border-top': 'solid 1px #CFCFCF' });
                    $('.selectBox_options ul li').remove();
                    var ul = '<ul class=\'selectBox_ul_' + elem_id + '\'>';
                    $(obj).children('option').each(function () {
                        if ($(this).text() == selected_text) {
                            ul += '<li class=\'selectBox_options_selected\' style=\'font-size:13px;background-color:' + settings.selectedColor + ';color:#fff;height:' + (settings.height - 3) + 'px; line-height:' + (settings.height - 3) + 'px;font-size:13px;\'><label style=\'display:none;\'>' + $(this).val() + '</label><label>' + $(this).text() + '</label></li>';
                        } else {
                            if (!$(this).attr('disabled')) {
                                ul += '<li style=\'font-size:13px;height:' + (settings.height - 3) + 'px; line-height:' + (settings.height - 3) + 'px;\'><label style=\'display:none;\'>' + $(this).val() + '</label><label>' + $(this).text() + '</label></li>';
                            }
                            ;
                        }
                    });
                    ul += '</ul>';
                    $('.selectBox_options_' + elem_id).append(ul).show();
                    $('.selectBox_options_' + elem_id + ' ul li').hover(function () {
                        $(this).css({
                            'background-color': settings.optionColor,
                            'color': '#fff'
                        });
                    }, function () {
                        if ($(this).hasClass('selectBox_options_selected')) {
                            $(this).css({
                                'background-color': settings.optionColor,
                                'color': '#fff'
                            });
                        } else {
                            $(this).css({
                                'background-color': '',
                                'color': '#666'
                            });
                        }
                    });
                    $('.selectBox_ul_' + elem_id + ' li').bind('click', function () {
                        var value = $(this).children('label').first().text();
                        var text = $(this).children('label').first().next().text();
                        $(obj).val(value);
                        $('.selectBox_div_' + elem_id + ' span').first().text(text);
                        $('.selectBox_options_' + elem_id).empty().hide();
                        if (settings.onSelect != undefined && settings.onSelect != '' && typeof settings.onSelect == 'function') {
                            settings.onSelect($(obj).parent(), value, text);
                        }
                    });
                }
            });
            $(document).bind('click', function (event) {
                var e = event || window.event;
                var elem = e.srcElement || e.target;
                if (elem.id == 'selectBox_' + elem_id || elem.id == 'selectBox_icon_' + elem_id || elem.id == 'selectBox_span_' + elem_id) {
                    return;
                } else {
                    $('.selectBox_options_' + elem_id).empty().hide();
                    if (settings.onClose != undefined && settings.onClose != '' && typeof settings.onClose == 'function') {
                        settings.onClose($(obj).parent());
                    }
                }
            });
        });
    };
});
;