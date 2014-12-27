/*! 2014 Baidu Inc. All Rights Reserved */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/sl', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    function translate(number, withoutSuffix, key) {
        var result = number + ' ';
        switch (key) {
        case 'm':
            return withoutSuffix ? 'ena minuta' : 'eno minuto';
        case 'mm':
            if (number === 1) {
                result += 'minuta';
            } else if (number === 2) {
                result += 'minuti';
            } else if (number === 3 || number === 4) {
                result += 'minute';
            } else {
                result += 'minut';
            }
            return result;
        case 'h':
            return withoutSuffix ? 'ena ura' : 'eno uro';
        case 'hh':
            if (number === 1) {
                result += 'ura';
            } else if (number === 2) {
                result += 'uri';
            } else if (number === 3 || number === 4) {
                result += 'ure';
            } else {
                result += 'ur';
            }
            return result;
        case 'dd':
            if (number === 1) {
                result += 'dan';
            } else {
                result += 'dni';
            }
            return result;
        case 'MM':
            if (number === 1) {
                result += 'mesec';
            } else if (number === 2) {
                result += 'meseca';
            } else if (number === 3 || number === 4) {
                result += 'mesece';
            } else {
                result += 'mesecev';
            }
            return result;
        case 'yy':
            if (number === 1) {
                result += 'leto';
            } else if (number === 2) {
                result += 'leti';
            } else if (number === 3 || number === 4) {
                result += 'leta';
            } else {
                result += 'let';
            }
            return result;
        }
    }
    return moment.lang('sl', {
        months: 'januar_februar_marec_april_maj_junij_julij_avgust_september_oktober_november_december'.split('_'),
        monthsShort: 'jan._feb._mar._apr._maj._jun._jul._avg._sep._okt._nov._dec.'.split('_'),
        weekdays: 'nedelja_ponedeljek_torek_sreda_\u010Detrtek_petek_sobota'.split('_'),
        weekdaysShort: 'ned._pon._tor._sre._\u010Det._pet._sob.'.split('_'),
        weekdaysMin: 'ne_po_to_sr_\u010De_pe_so'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            L: 'DD. MM. YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY LT',
            LLLL: 'dddd, D. MMMM YYYY LT'
        },
        calendar: {
            sameDay: '[danes ob] LT',
            nextDay: '[jutri ob] LT',
            nextWeek: function () {
                switch (this.day()) {
                case 0:
                    return '[v] [nedeljo] [ob] LT';
                case 3:
                    return '[v] [sredo] [ob] LT';
                case 6:
                    return '[v] [soboto] [ob] LT';
                case 1:
                case 2:
                case 4:
                case 5:
                    return '[v] dddd [ob] LT';
                }
            },
            lastDay: '[v\u010Deraj ob] LT',
            lastWeek: function () {
                switch (this.day()) {
                case 0:
                case 3:
                case 6:
                    return '[prej\u0161nja] dddd [ob] LT';
                case 1:
                case 2:
                case 4:
                case 5:
                    return '[prej\u0161nji] dddd [ob] LT';
                }
            },
            sameElse: 'L'
        },
        relativeTime: {
            future: '\u010Dez %s',
            past: '%s nazaj',
            s: 'nekaj sekund',
            m: translate,
            mm: translate,
            h: translate,
            hh: translate,
            d: 'en dan',
            dd: translate,
            M: 'en mesec',
            MM: translate,
            y: 'eno leto',
            yy: translate
        },
        ordinal: '%d.',
        week: {
            dow: 1,
            doy: 7
        }
    });
}));