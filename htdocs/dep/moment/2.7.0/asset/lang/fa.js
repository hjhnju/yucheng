(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/fa', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    var symbolMap = {
            '1': '\u06F1',
            '2': '\u06F2',
            '3': '\u06F3',
            '4': '\u06F4',
            '5': '\u06F5',
            '6': '\u06F6',
            '7': '\u06F7',
            '8': '\u06F8',
            '9': '\u06F9',
            '0': '\u06F0'
        }, numberMap = {
            '\u06F1': '1',
            '\u06F2': '2',
            '\u06F3': '3',
            '\u06F4': '4',
            '\u06F5': '5',
            '\u06F6': '6',
            '\u06F7': '7',
            '\u06F8': '8',
            '\u06F9': '9',
            '\u06F0': '0'
        };
    return moment.lang('fa', {
        months: '\u0698\u0627\u0646\u0648\u06CC\u0647_\u0641\u0648\u0631\u06CC\u0647_\u0645\u0627\u0631\u0633_\u0622\u0648\u0631\u06CC\u0644_\u0645\u0647_\u0698\u0648\u0626\u0646_\u0698\u0648\u0626\u06CC\u0647_\u0627\u0648\u062A_\u0633\u067E\u062A\u0627\u0645\u0628\u0631_\u0627\u06A9\u062A\u0628\u0631_\u0646\u0648\u0627\u0645\u0628\u0631_\u062F\u0633\u0627\u0645\u0628\u0631'.split('_'),
        monthsShort: '\u0698\u0627\u0646\u0648\u06CC\u0647_\u0641\u0648\u0631\u06CC\u0647_\u0645\u0627\u0631\u0633_\u0622\u0648\u0631\u06CC\u0644_\u0645\u0647_\u0698\u0648\u0626\u0646_\u0698\u0648\u0626\u06CC\u0647_\u0627\u0648\u062A_\u0633\u067E\u062A\u0627\u0645\u0628\u0631_\u0627\u06A9\u062A\u0628\u0631_\u0646\u0648\u0627\u0645\u0628\u0631_\u062F\u0633\u0627\u0645\u0628\u0631'.split('_'),
        weekdays: '\u06CC\u06A9\u200C\u0634\u0646\u0628\u0647_\u062F\u0648\u0634\u0646\u0628\u0647_\u0633\u0647\u200C\u0634\u0646\u0628\u0647_\u0686\u0647\u0627\u0631\u0634\u0646\u0628\u0647_\u067E\u0646\u062C\u200C\u0634\u0646\u0628\u0647_\u062C\u0645\u0639\u0647_\u0634\u0646\u0628\u0647'.split('_'),
        weekdaysShort: '\u06CC\u06A9\u200C\u0634\u0646\u0628\u0647_\u062F\u0648\u0634\u0646\u0628\u0647_\u0633\u0647\u200C\u0634\u0646\u0628\u0647_\u0686\u0647\u0627\u0631\u0634\u0646\u0628\u0647_\u067E\u0646\u062C\u200C\u0634\u0646\u0628\u0647_\u062C\u0645\u0639\u0647_\u0634\u0646\u0628\u0647'.split('_'),
        weekdaysMin: '\u06CC_\u062F_\u0633_\u0686_\u067E_\u062C_\u0634'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY LT',
            LLLL: 'dddd, D MMMM YYYY LT'
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return '\u0642\u0628\u0644 \u0627\u0632 \u0638\u0647\u0631';
            } else {
                return '\u0628\u0639\u062F \u0627\u0632 \u0638\u0647\u0631';
            }
        },
        calendar: {
            sameDay: '[\u0627\u0645\u0631\u0648\u0632 \u0633\u0627\u0639\u062A] LT',
            nextDay: '[\u0641\u0631\u062F\u0627 \u0633\u0627\u0639\u062A] LT',
            nextWeek: 'dddd [\u0633\u0627\u0639\u062A] LT',
            lastDay: '[\u062F\u06CC\u0631\u0648\u0632 \u0633\u0627\u0639\u062A] LT',
            lastWeek: 'dddd [\u067E\u06CC\u0634] [\u0633\u0627\u0639\u062A] LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: '\u062F\u0631 %s',
            past: '%s \u067E\u06CC\u0634',
            s: '\u0686\u0646\u062F\u06CC\u0646 \u062B\u0627\u0646\u06CC\u0647',
            m: '\u06CC\u06A9 \u062F\u0642\u06CC\u0642\u0647',
            mm: '%d \u062F\u0642\u06CC\u0642\u0647',
            h: '\u06CC\u06A9 \u0633\u0627\u0639\u062A',
            hh: '%d \u0633\u0627\u0639\u062A',
            d: '\u06CC\u06A9 \u0631\u0648\u0632',
            dd: '%d \u0631\u0648\u0632',
            M: '\u06CC\u06A9 \u0645\u0627\u0647',
            MM: '%d \u0645\u0627\u0647',
            y: '\u06CC\u06A9 \u0633\u0627\u0644',
            yy: '%d \u0633\u0627\u0644'
        },
        preparse: function (string) {
            return string.replace(/[۰-۹]/g, function (match) {
                return numberMap[match];
            }).replace(/،/g, ',');
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap[match];
            }).replace(/,/g, '\u060C');
        },
        ordinal: '%d\u0645',
        week: {
            dow: 6,
            doy: 12
        }
    });
}));