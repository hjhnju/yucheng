(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define('common/extra/jquery.scrollUp', ['jquery'], factory);
    } else {
        window.scrollUp = window.scrollUp || {};
        window.scrollUp.scrollUp = factory(window.jQuery);
    }
}(function ($) {
    'use strict';
    function scrollUp(options) {
        if (!$.data(document.body, 'scrollUp')) {
            $.data(document.body, 'scrollUp', true);
            scrollUp.prototype.init(options);
        }
    }
    $.extend(scrollUp.prototype, {
        defaults: {
            scrollName: 'scrollUp',
            scrollDistance: 300,
            scrollFrom: 'top',
            scrollSpeed: 300,
            easingType: 'linear',
            animation: 'fade',
            animationSpeed: 200,
            scrollTrigger: false,
            scrollTarget: false,
            scrollText: '\u8FD4\u56DE\u9876\u90E8',
            scrollTitle: true,
            scrollImg: true,
            activeOverlay: false,
            zIndex: 2147483647
        },
        init: function (options) {
            var o = this.settings = $.extend({}, this.defaults, options), triggerVisible = false, animIn, animOut, animSpeed, scrollDis, scrollEvent, scrollTarget, $self;
            if (o.scrollTrigger) {
                $self = $(o.scrollTrigger);
            } else {
                $self = $('<a/>', {
                    id: o.scrollName,
                    href: '#top'
                });
            }
            if (o.scrollTitle) {
                $self.attr('title', o.scrollText);
            }
            $self.appendTo('body');
            if (!(o.scrollImg || o.scrollTrigger)) {
                $self.html(o.scrollText);
            }
            $self.css({
                display: 'none',
                position: 'fixed',
                zIndex: o.zIndex
            });
            if (o.activeOverlay) {
                $('<div/>', { id: o.scrollName + '-active' }).css({
                    position: 'absolute',
                    'top': o.scrollDistance + 'px',
                    width: '100%',
                    borderTop: '1px dotted' + o.activeOverlay,
                    zIndex: o.zIndex
                }).appendTo('body');
            }
            switch (o.animation) {
            case 'fade':
                animIn = 'fadeIn';
                animOut = 'fadeOut';
                animSpeed = o.animationSpeed;
                break;
            case 'slide':
                animIn = 'slideDown';
                animOut = 'slideUp';
                animSpeed = o.animationSpeed;
                break;
            default:
                animIn = 'show';
                animOut = 'hide';
                animSpeed = 0;
            }
            if (o.scrollFrom === 'top') {
                scrollDis = o.scrollDistance;
            } else {
                scrollDis = $(document).height() - $(window).height() - o.scrollDistance;
            }
            scrollEvent = $(window).scroll(function () {
                if ($(window).scrollTop() > scrollDis) {
                    if (!triggerVisible) {
                        $self[animIn](animSpeed);
                        triggerVisible = true;
                    }
                } else {
                    if (triggerVisible) {
                        $self[animOut](animSpeed);
                        triggerVisible = false;
                    }
                }
            });
            if (o.scrollTarget) {
                if (typeof o.scrollTarget === 'number') {
                    scrollTarget = o.scrollTarget;
                } else if (typeof o.scrollTarget === 'string') {
                    scrollTarget = Math.floor($(o.scrollTarget).offset().top);
                }
            } else {
                scrollTarget = 0;
            }
            $self.click(function (e) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: scrollTarget }, o.scrollSpeed, o.easingType);
            });
        },
        destroy: function (scrollEvent) {
            $.removeData(document.body, 'scrollUp');
            $('#' + $.fn.scrollUp.settings.scrollName).remove();
            $('#' + $.fn.scrollUp.settings.scrollName + '-active').remove();
            if ($.fn.jquery.split('.')[1] >= 7) {
                $(window).off('scroll', scrollEvent);
            } else {
                $(window).unbind('scroll', scrollEvent);
            }
        }
    });
    return scrollUp;
}));