(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/lt', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    var units = {
            'm': 'minut\u0117_minut\u0117s_minut\u0119',
            'mm': 'minut\u0117s_minu\u010Di\u0173_minutes',
            'h': 'valanda_valandos_valand\u0105',
            'hh': 'valandos_valand\u0173_valandas',
            'd': 'diena_dienos_dien\u0105',
            'dd': 'dienos_dien\u0173_dienas',
            'M': 'm\u0117nuo_m\u0117nesio_m\u0117nes\u012F',
            'MM': 'm\u0117nesiai_m\u0117nesi\u0173_m\u0117nesius',
            'y': 'metai_met\u0173_metus',
            'yy': 'metai_met\u0173_metus'
        }, weekDays = 'sekmadienis_pirmadienis_antradienis_tre\u010Diadienis_ketvirtadienis_penktadienis_\u0161e\u0161tadienis'.split('_');
    function translateSeconds(number, withoutSuffix, key, isFuture) {
        if (withoutSuffix) {
            return 'kelios sekund\u0117s';
        } else {
            return isFuture ? 'keli\u0173 sekund\u017Ei\u0173' : 'kelias sekundes';
        }
    }
    function translateSingular(number, withoutSuffix, key, isFuture) {
        return withoutSuffix ? forms(key)[0] : isFuture ? forms(key)[1] : forms(key)[2];
    }
    function special(number) {
        return number % 10 === 0 || number > 10 && number < 20;
    }
    function forms(key) {
        return units[key].split('_');
    }
    function translate(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        if (number === 1) {
            return result + translateSingular(number, withoutSuffix, key[0], isFuture);
        } else if (withoutSuffix) {
            return result + (special(number) ? forms(key)[1] : forms(key)[0]);
        } else {
            if (isFuture) {
                return result + forms(key)[1];
            } else {
                return result + (special(number) ? forms(key)[1] : forms(key)[2]);
            }
        }
    }
    function relativeWeekDay(moment, format) {
        var nominative = format.indexOf('dddd HH:mm') === -1, weekDay = weekDays[moment.day()];
        return nominative ? weekDay : weekDay.substring(0, weekDay.length - 2) + '\u012F';
    }
    return moment.lang('lt', {
        months: 'sausio_vasario_kovo_baland\u017Eio_gegu\u017E\u0117s_bir\u017E\u0117lio_liepos_rugpj\u016B\u010Dio_rugs\u0117jo_spalio_lapkri\u010Dio_gruod\u017Eio'.split('_'),
        monthsShort: 'sau_vas_kov_bal_geg_bir_lie_rgp_rgs_spa_lap_grd'.split('_'),
        weekdays: relativeWeekDay,
        weekdaysShort: 'Sek_Pir_Ant_Tre_Ket_Pen_\u0160e\u0161'.split('_'),
        weekdaysMin: 'S_P_A_T_K_Pn_\u0160'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            L: 'YYYY-MM-DD',
            LL: 'YYYY [m.] MMMM D [d.]',
            LLL: 'YYYY [m.] MMMM D [d.], LT [val.]',
            LLLL: 'YYYY [m.] MMMM D [d.], dddd, LT [val.]',
            l: 'YYYY-MM-DD',
            ll: 'YYYY [m.] MMMM D [d.]',
            lll: 'YYYY [m.] MMMM D [d.], LT [val.]',
            llll: 'YYYY [m.] MMMM D [d.], ddd, LT [val.]'
        },
        calendar: {
            sameDay: '[\u0160iandien] LT',
            nextDay: '[Rytoj] LT',
            nextWeek: 'dddd LT',
            lastDay: '[Vakar] LT',
            lastWeek: '[Pra\u0117jus\u012F] dddd LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: 'po %s',
            past: 'prie\u0161 %s',
            s: translateSeconds,
            m: translateSingular,
            mm: translate,
            h: translateSingular,
            hh: translate,
            d: translateSingular,
            dd: translate,
            M: translateSingular,
            MM: translate,
            y: translateSingular,
            yy: translate
        },
        ordinal: function (number) {
            return number + '-oji';
        },
        week: {
            dow: 1,
            doy: 4
        }
    });
}));