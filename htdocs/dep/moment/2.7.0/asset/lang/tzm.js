(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/tzm', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    return moment.lang('tzm', {
        months: '\u2D49\u2D4F\u2D4F\u2D30\u2D62\u2D54_\u2D31\u2D55\u2D30\u2D62\u2D55_\u2D4E\u2D30\u2D55\u2D5A_\u2D49\u2D31\u2D54\u2D49\u2D54_\u2D4E\u2D30\u2D62\u2D62\u2D53_\u2D62\u2D53\u2D4F\u2D62\u2D53_\u2D62\u2D53\u2D4D\u2D62\u2D53\u2D63_\u2D56\u2D53\u2D5B\u2D5C_\u2D5B\u2D53\u2D5C\u2D30\u2D4F\u2D31\u2D49\u2D54_\u2D3D\u2D5F\u2D53\u2D31\u2D55_\u2D4F\u2D53\u2D61\u2D30\u2D4F\u2D31\u2D49\u2D54_\u2D37\u2D53\u2D4A\u2D4F\u2D31\u2D49\u2D54'.split('_'),
        monthsShort: '\u2D49\u2D4F\u2D4F\u2D30\u2D62\u2D54_\u2D31\u2D55\u2D30\u2D62\u2D55_\u2D4E\u2D30\u2D55\u2D5A_\u2D49\u2D31\u2D54\u2D49\u2D54_\u2D4E\u2D30\u2D62\u2D62\u2D53_\u2D62\u2D53\u2D4F\u2D62\u2D53_\u2D62\u2D53\u2D4D\u2D62\u2D53\u2D63_\u2D56\u2D53\u2D5B\u2D5C_\u2D5B\u2D53\u2D5C\u2D30\u2D4F\u2D31\u2D49\u2D54_\u2D3D\u2D5F\u2D53\u2D31\u2D55_\u2D4F\u2D53\u2D61\u2D30\u2D4F\u2D31\u2D49\u2D54_\u2D37\u2D53\u2D4A\u2D4F\u2D31\u2D49\u2D54'.split('_'),
        weekdays: '\u2D30\u2D59\u2D30\u2D4E\u2D30\u2D59_\u2D30\u2D62\u2D4F\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D4F\u2D30\u2D59_\u2D30\u2D3D\u2D54\u2D30\u2D59_\u2D30\u2D3D\u2D61\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D4E\u2D61\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D39\u2D62\u2D30\u2D59'.split('_'),
        weekdaysShort: '\u2D30\u2D59\u2D30\u2D4E\u2D30\u2D59_\u2D30\u2D62\u2D4F\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D4F\u2D30\u2D59_\u2D30\u2D3D\u2D54\u2D30\u2D59_\u2D30\u2D3D\u2D61\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D4E\u2D61\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D39\u2D62\u2D30\u2D59'.split('_'),
        weekdaysMin: '\u2D30\u2D59\u2D30\u2D4E\u2D30\u2D59_\u2D30\u2D62\u2D4F\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D4F\u2D30\u2D59_\u2D30\u2D3D\u2D54\u2D30\u2D59_\u2D30\u2D3D\u2D61\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D4E\u2D61\u2D30\u2D59_\u2D30\u2D59\u2D49\u2D39\u2D62\u2D30\u2D59'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY LT',
            LLLL: 'dddd D MMMM YYYY LT'
        },
        calendar: {
            sameDay: '[\u2D30\u2D59\u2D37\u2D45 \u2D34] LT',
            nextDay: '[\u2D30\u2D59\u2D3D\u2D30 \u2D34] LT',
            nextWeek: 'dddd [\u2D34] LT',
            lastDay: '[\u2D30\u2D5A\u2D30\u2D4F\u2D5C \u2D34] LT',
            lastWeek: 'dddd [\u2D34] LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: '\u2D37\u2D30\u2D37\u2D45 \u2D59 \u2D62\u2D30\u2D4F %s',
            past: '\u2D62\u2D30\u2D4F %s',
            s: '\u2D49\u2D4E\u2D49\u2D3D',
            m: '\u2D4E\u2D49\u2D4F\u2D53\u2D3A',
            mm: '%d \u2D4E\u2D49\u2D4F\u2D53\u2D3A',
            h: '\u2D59\u2D30\u2D44\u2D30',
            hh: '%d \u2D5C\u2D30\u2D59\u2D59\u2D30\u2D44\u2D49\u2D4F',
            d: '\u2D30\u2D59\u2D59',
            dd: '%d o\u2D59\u2D59\u2D30\u2D4F',
            M: '\u2D30\u2D62o\u2D53\u2D54',
            MM: '%d \u2D49\u2D62\u2D62\u2D49\u2D54\u2D4F',
            y: '\u2D30\u2D59\u2D33\u2D30\u2D59',
            yy: '%d \u2D49\u2D59\u2D33\u2D30\u2D59\u2D4F'
        },
        week: {
            dow: 6,
            doy: 12
        }
    });
}));