(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/uz', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    return moment.lang('uz', {
        months: '\u044F\u043D\u0432\u0430\u0440\u044C_\u0444\u0435\u0432\u0440\u0430\u043B\u044C_\u043C\u0430\u0440\u0442_\u0430\u043F\u0440\u0435\u043B\u044C_\u043C\u0430\u0439_\u0438\u044E\u043D\u044C_\u0438\u044E\u043B\u044C_\u0430\u0432\u0433\u0443\u0441\u0442_\u0441\u0435\u043D\u0442\u044F\u0431\u0440\u044C_\u043E\u043A\u0442\u044F\u0431\u0440\u044C_\u043D\u043E\u044F\u0431\u0440\u044C_\u0434\u0435\u043A\u0430\u0431\u0440\u044C'.split('_'),
        monthsShort: '\u044F\u043D\u0432_\u0444\u0435\u0432_\u043C\u0430\u0440_\u0430\u043F\u0440_\u043C\u0430\u0439_\u0438\u044E\u043D_\u0438\u044E\u043B_\u0430\u0432\u0433_\u0441\u0435\u043D_\u043E\u043A\u0442_\u043D\u043E\u044F_\u0434\u0435\u043A'.split('_'),
        weekdays: '\u042F\u043A\u0448\u0430\u043D\u0431\u0430_\u0414\u0443\u0448\u0430\u043D\u0431\u0430_\u0421\u0435\u0448\u0430\u043D\u0431\u0430_\u0427\u043E\u0440\u0448\u0430\u043D\u0431\u0430_\u041F\u0430\u0439\u0448\u0430\u043D\u0431\u0430_\u0416\u0443\u043C\u0430_\u0428\u0430\u043D\u0431\u0430'.split('_'),
        weekdaysShort: '\u042F\u043A\u0448_\u0414\u0443\u0448_\u0421\u0435\u0448_\u0427\u043E\u0440_\u041F\u0430\u0439_\u0416\u0443\u043C_\u0428\u0430\u043D'.split('_'),
        weekdaysMin: '\u042F\u043A_\u0414\u0443_\u0421\u0435_\u0427\u043E_\u041F\u0430_\u0416\u0443_\u0428\u0430'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY LT',
            LLLL: 'D MMMM YYYY, dddd LT'
        },
        calendar: {
            sameDay: '[\u0411\u0443\u0433\u0443\u043D \u0441\u043E\u0430\u0442] LT [\u0434\u0430]',
            nextDay: '[\u042D\u0440\u0442\u0430\u0433\u0430] LT [\u0434\u0430]',
            nextWeek: 'dddd [\u043A\u0443\u043D\u0438 \u0441\u043E\u0430\u0442] LT [\u0434\u0430]',
            lastDay: '[\u041A\u0435\u0447\u0430 \u0441\u043E\u0430\u0442] LT [\u0434\u0430]',
            lastWeek: '[\u0423\u0442\u0433\u0430\u043D] dddd [\u043A\u0443\u043D\u0438 \u0441\u043E\u0430\u0442] LT [\u0434\u0430]',
            sameElse: 'L'
        },
        relativeTime: {
            future: '\u042F\u043A\u0438\u043D %s \u0438\u0447\u0438\u0434\u0430',
            past: '\u0411\u0438\u0440 \u043D\u0435\u0447\u0430 %s \u043E\u043B\u0434\u0438\u043D',
            s: '\u0444\u0443\u0440\u0441\u0430\u0442',
            m: '\u0431\u0438\u0440 \u0434\u0430\u043A\u0438\u043A\u0430',
            mm: '%d \u0434\u0430\u043A\u0438\u043A\u0430',
            h: '\u0431\u0438\u0440 \u0441\u043E\u0430\u0442',
            hh: '%d \u0441\u043E\u0430\u0442',
            d: '\u0431\u0438\u0440 \u043A\u0443\u043D',
            dd: '%d \u043A\u0443\u043D',
            M: '\u0431\u0438\u0440 \u043E\u0439',
            MM: '%d \u043E\u0439',
            y: '\u0431\u0438\u0440 \u0439\u0438\u043B',
            yy: '%d \u0439\u0438\u043B'
        },
        week: {
            dow: 1,
            doy: 7
        }
    });
}));