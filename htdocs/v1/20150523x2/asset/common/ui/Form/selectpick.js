define('common/ui/Form/selectpick', [
    'require',
    'jquery'
], function (require) {
    var $ = require('jquery');
    $.fn.selectpick = function (options) {
        var selectpick_config = {
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
        var settings = $.extend({}, selectpick_config, options);
        return this.each(function (elem_id) {
            var obj = this;
            var _offset = $(this).offset();
            var top = _offset.top + $(document).scrollTop();
            var elem_width = $(obj).width();
            var left = _offset.left + $(document).scrollLeft();
            var elem_id = $(obj).attr('id');
            var _selectBody = '<div onselectstart=\'return false;\' class=' + settings.className + '><div class=\'selectpick_div selectpick_div_' + elem_id + '\'  id=\'selectpick_' + elem_id + '\'><span style=\'float:left;\' id=\'selectpick_span_' + elem_id + '\'></span><span class=\'selectpick_icon\' id=\'selectpick_icon_' + elem_id + '\'></span></div><div class=\'selectpick_options selectpick_options_' + elem_id + '\'></div></div>';
            $('#' + elem_id).after(_selectBody);
            $(obj).addClass('select_hide');
            if (settings.selectText != '' && settings.selectText != undefined) {
                $('.selectpick_div_' + elem_id + ' span').first().text(settings.selectText);
            } else {
                $('.selectpick_div_' + elem_id + ' span').first().text($(obj).children('option').first().text());
            }
            if (settings.disabled) {
                $('.selectpick_div_' + elem_id).addClass('selectpick_no_select');
                $('#selectpick_icon_' + elem_id).css({ 'cursor': 'default' });
                return;
            }
            $('.selectpick_div_' + elem_id + ',#selectpick_span_' + elem_id + ',#selectpick_options_' + elem_id + '').bind('click', function (event) {
                var selected_text = $('.selectpick_div_' + elem_id + ' span').first().text();
                event.stopPropagation();
                if ($('.selectpick_ul_' + elem_id + ' li').length > 0) {
                    $('.selectpick_options_' + elem_id).empty().css({ 'border-top': 'none' });
                    return;
                } else {
                    $('.selectpick_options_' + elem_id).css({ 'border-top': 'solid 1px #CFCFCF' });
                    $('.selectpick_options ul li').remove();
                    var ul = '<ul class=\'selectpick_ul_' + elem_id + '\'>';
                    $(obj).children('option').each(function () {
                        if ($(this).text() == selected_text) {
                            ul += '<li class=\'selectpick_options_selected\' style=\'font-size:13px;background-color:' + settings.selectedColor + ';color:#fff;height:' + (settings.height - 3) + 'px; line-height:' + (settings.height - 3) + 'px;font-size:13px;\'><label style=\'display:none;\'>' + $(this).val() + '</label><label>' + $(this).text() + '</label></li>';
                        } else {
                            ul += '<li style=\'font-size:13px;height:' + (settings.height - 3) + 'px; line-height:' + (settings.height - 3) + 'px;\'><label style=\'display:none;\'>' + $(this).val() + '</label><label>' + $(this).text() + '</label></li>';
                        }
                    });
                    ul += '</ul>';
                    $('.selectpick_options_' + elem_id).append(ul).show();
                    $('.selectpick_options_' + elem_id + ' ul li').hover(function () {
                        $(this).css({
                            'background-color': settings.optionColor,
                            'color': '#fff'
                        });
                    }, function () {
                        if ($(this).hasClass('selectpick_options_selected')) {
                            $(this).css({
                                'background-color': settings.optionColor,
                                'color': '#fff'
                            });
                        } else {
                            $(this).css({
                                'background-color': '',
                                'color': '#000'
                            });
                        }
                    });
                    $('.selectpick_ul_' + elem_id + ' li').bind('click', function () {
                        var value = $(this).children('label').first().text();
                        var text = $(this).children('label').first().next().text();
                        $(obj).val(value);
                        $('.selectpick_div_' + elem_id + ' span').first().text(text);
                        $('.selectpick_options_' + elem_id).empty().hide();
                        if (settings.onSelect != undefined && settings.onSelect != '' && typeof settings.onSelect == 'function') {
                            settings.onSelect($(obj).parent(), value, text);
                        }
                    });
                }
            });
            $(document).bind('click', function (event) {
                var e = event || window.event;
                var elem = e.srcElement || e.target;
                if (elem.id == 'selectpick_' + elem_id || elem.id == 'selectpick_icon_' + elem_id || elem.id == 'selectpick_span_' + elem_id) {
                    return;
                } else {
                    $('.selectpick_options_' + elem_id).empty().hide();
                    if (settings.onClose != undefined && settings.onClose != '' && typeof settings.onClose == 'function') {
                        settings.onClose($(obj).parent());
                    }
                }
            });
        });
    };
});
;