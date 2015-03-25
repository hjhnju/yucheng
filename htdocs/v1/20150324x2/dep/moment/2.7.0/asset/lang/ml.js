(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/ml', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    return moment.lang('ml', {
        months: '\u0D1C\u0D28\u0D41\u0D35\u0D30\u0D3F_\u0D2B\u0D46\u0D2C\u0D4D\u0D30\u0D41\u0D35\u0D30\u0D3F_\u0D2E\u0D3E\u0D7C\u0D1A\u0D4D\u0D1A\u0D4D_\u0D0F\u0D2A\u0D4D\u0D30\u0D3F\u0D7D_\u0D2E\u0D47\u0D2F\u0D4D_\u0D1C\u0D42\u0D7A_\u0D1C\u0D42\u0D32\u0D48_\u0D13\u0D17\u0D38\u0D4D\u0D31\u0D4D\u0D31\u0D4D_\u0D38\u0D46\u0D2A\u0D4D\u0D31\u0D4D\u0D31\u0D02\u0D2C\u0D7C_\u0D12\u0D15\u0D4D\u0D1F\u0D4B\u0D2C\u0D7C_\u0D28\u0D35\u0D02\u0D2C\u0D7C_\u0D21\u0D3F\u0D38\u0D02\u0D2C\u0D7C'.split('_'),
        monthsShort: '\u0D1C\u0D28\u0D41._\u0D2B\u0D46\u0D2C\u0D4D\u0D30\u0D41._\u0D2E\u0D3E\u0D7C._\u0D0F\u0D2A\u0D4D\u0D30\u0D3F._\u0D2E\u0D47\u0D2F\u0D4D_\u0D1C\u0D42\u0D7A_\u0D1C\u0D42\u0D32\u0D48._\u0D13\u0D17._\u0D38\u0D46\u0D2A\u0D4D\u0D31\u0D4D\u0D31._\u0D12\u0D15\u0D4D\u0D1F\u0D4B._\u0D28\u0D35\u0D02._\u0D21\u0D3F\u0D38\u0D02.'.split('_'),
        weekdays: '\u0D1E\u0D3E\u0D2F\u0D31\u0D3E\u0D34\u0D4D\u0D1A_\u0D24\u0D3F\u0D19\u0D4D\u0D15\u0D33\u0D3E\u0D34\u0D4D\u0D1A_\u0D1A\u0D4A\u0D35\u0D4D\u0D35\u0D3E\u0D34\u0D4D\u0D1A_\u0D2C\u0D41\u0D27\u0D28\u0D3E\u0D34\u0D4D\u0D1A_\u0D35\u0D4D\u0D2F\u0D3E\u0D34\u0D3E\u0D34\u0D4D\u0D1A_\u0D35\u0D46\u0D33\u0D4D\u0D33\u0D3F\u0D2F\u0D3E\u0D34\u0D4D\u0D1A_\u0D36\u0D28\u0D3F\u0D2F\u0D3E\u0D34\u0D4D\u0D1A'.split('_'),
        weekdaysShort: '\u0D1E\u0D3E\u0D2F\u0D7C_\u0D24\u0D3F\u0D19\u0D4D\u0D15\u0D7E_\u0D1A\u0D4A\u0D35\u0D4D\u0D35_\u0D2C\u0D41\u0D27\u0D7B_\u0D35\u0D4D\u0D2F\u0D3E\u0D34\u0D02_\u0D35\u0D46\u0D33\u0D4D\u0D33\u0D3F_\u0D36\u0D28\u0D3F'.split('_'),
        weekdaysMin: '\u0D1E\u0D3E_\u0D24\u0D3F_\u0D1A\u0D4A_\u0D2C\u0D41_\u0D35\u0D4D\u0D2F\u0D3E_\u0D35\u0D46_\u0D36'.split('_'),
        longDateFormat: {
            LT: 'A h:mm -\u0D28\u0D41',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, LT',
            LLLL: 'dddd, D MMMM YYYY, LT'
        },
        calendar: {
            sameDay: '[\u0D07\u0D28\u0D4D\u0D28\u0D4D] LT',
            nextDay: '[\u0D28\u0D3E\u0D33\u0D46] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[\u0D07\u0D28\u0D4D\u0D28\u0D32\u0D46] LT',
            lastWeek: '[\u0D15\u0D34\u0D3F\u0D1E\u0D4D\u0D1E] dddd, LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: '%s \u0D15\u0D34\u0D3F\u0D1E\u0D4D\u0D1E\u0D4D',
            past: '%s \u0D2E\u0D41\u0D7B\u0D2A\u0D4D',
            s: '\u0D05\u0D7D\u0D2A \u0D28\u0D3F\u0D2E\u0D3F\u0D37\u0D19\u0D4D\u0D19\u0D7E',
            m: '\u0D12\u0D30\u0D41 \u0D2E\u0D3F\u0D28\u0D3F\u0D31\u0D4D\u0D31\u0D4D',
            mm: '%d \u0D2E\u0D3F\u0D28\u0D3F\u0D31\u0D4D\u0D31\u0D4D',
            h: '\u0D12\u0D30\u0D41 \u0D2E\u0D23\u0D3F\u0D15\u0D4D\u0D15\u0D42\u0D7C',
            hh: '%d \u0D2E\u0D23\u0D3F\u0D15\u0D4D\u0D15\u0D42\u0D7C',
            d: '\u0D12\u0D30\u0D41 \u0D26\u0D3F\u0D35\u0D38\u0D02',
            dd: '%d \u0D26\u0D3F\u0D35\u0D38\u0D02',
            M: '\u0D12\u0D30\u0D41 \u0D2E\u0D3E\u0D38\u0D02',
            MM: '%d \u0D2E\u0D3E\u0D38\u0D02',
            y: '\u0D12\u0D30\u0D41 \u0D35\u0D7C\u0D37\u0D02',
            yy: '%d \u0D35\u0D7C\u0D37\u0D02'
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return '\u0D30\u0D3E\u0D24\u0D4D\u0D30\u0D3F';
            } else if (hour < 12) {
                return '\u0D30\u0D3E\u0D35\u0D3F\u0D32\u0D46';
            } else if (hour < 17) {
                return '\u0D09\u0D1A\u0D4D\u0D1A \u0D15\u0D34\u0D3F\u0D1E\u0D4D\u0D1E\u0D4D';
            } else if (hour < 20) {
                return '\u0D35\u0D48\u0D15\u0D41\u0D28\u0D4D\u0D28\u0D47\u0D30\u0D02';
            } else {
                return '\u0D30\u0D3E\u0D24\u0D4D\u0D30\u0D3F';
            }
        }
    });
}));