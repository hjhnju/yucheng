(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('moment/lang/is', ['moment'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment'));
    } else {
        factory(window.moment);
    }
}(function (moment) {
    function plural(n) {
        if (n % 100 === 11) {
            return true;
        } else if (n % 10 === 1) {
            return false;
        }
        return true;
    }
    function translate(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        switch (key) {
        case 's':
            return withoutSuffix || isFuture ? 'nokkrar sek\xFAndur' : 'nokkrum sek\xFAndum';
        case 'm':
            return withoutSuffix ? 'm\xEDn\xFAta' : 'm\xEDn\xFAtu';
        case 'mm':
            if (plural(number)) {
                return result + (withoutSuffix || isFuture ? 'm\xEDn\xFAtur' : 'm\xEDn\xFAtum');
            } else if (withoutSuffix) {
                return result + 'm\xEDn\xFAta';
            }
            return result + 'm\xEDn\xFAtu';
        case 'hh':
            if (plural(number)) {
                return result + (withoutSuffix || isFuture ? 'klukkustundir' : 'klukkustundum');
            }
            return result + 'klukkustund';
        case 'd':
            if (withoutSuffix) {
                return 'dagur';
            }
            return isFuture ? 'dag' : 'degi';
        case 'dd':
            if (plural(number)) {
                if (withoutSuffix) {
                    return result + 'dagar';
                }
                return result + (isFuture ? 'daga' : 'd\xF6gum');
            } else if (withoutSuffix) {
                return result + 'dagur';
            }
            return result + (isFuture ? 'dag' : 'degi');
        case 'M':
            if (withoutSuffix) {
                return 'm\xE1nu\xF0ur';
            }
            return isFuture ? 'm\xE1nu\xF0' : 'm\xE1nu\xF0i';
        case 'MM':
            if (plural(number)) {
                if (withoutSuffix) {
                    return result + 'm\xE1nu\xF0ir';
                }
                return result + (isFuture ? 'm\xE1nu\xF0i' : 'm\xE1nu\xF0um');
            } else if (withoutSuffix) {
                return result + 'm\xE1nu\xF0ur';
            }
            return result + (isFuture ? 'm\xE1nu\xF0' : 'm\xE1nu\xF0i');
        case 'y':
            return withoutSuffix || isFuture ? '\xE1r' : '\xE1ri';
        case 'yy':
            if (plural(number)) {
                return result + (withoutSuffix || isFuture ? '\xE1r' : '\xE1rum');
            }
            return result + (withoutSuffix || isFuture ? '\xE1r' : '\xE1ri');
        }
    }
    return moment.lang('is', {
        months: 'jan\xFAar_febr\xFAar_mars_apr\xEDl_ma\xED_j\xFAn\xED_j\xFAl\xED_\xE1g\xFAst_september_okt\xF3ber_n\xF3vember_desember'.split('_'),
        monthsShort: 'jan_feb_mar_apr_ma\xED_j\xFAn_j\xFAl_\xE1g\xFA_sep_okt_n\xF3v_des'.split('_'),
        weekdays: 'sunnudagur_m\xE1nudagur_\xFEri\xF0judagur_mi\xF0vikudagur_fimmtudagur_f\xF6studagur_laugardagur'.split('_'),
        weekdaysShort: 'sun_m\xE1n_\xFEri_mi\xF0_fim_f\xF6s_lau'.split('_'),
        weekdaysMin: 'Su_M\xE1_\xDEr_Mi_Fi_F\xF6_La'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            L: 'DD/MM/YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY [kl.] LT',
            LLLL: 'dddd, D. MMMM YYYY [kl.] LT'
        },
        calendar: {
            sameDay: '[\xED dag kl.] LT',
            nextDay: '[\xE1 morgun kl.] LT',
            nextWeek: 'dddd [kl.] LT',
            lastDay: '[\xED g\xE6r kl.] LT',
            lastWeek: '[s\xED\xF0asta] dddd [kl.] LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: 'eftir %s',
            past: 'fyrir %s s\xED\xF0an',
            s: translate,
            m: translate,
            mm: translate,
            h: 'klukkustund',
            hh: translate,
            d: translate,
            dd: translate,
            M: translate,
            MM: translate,
            y: translate,
            yy: translate
        },
        ordinal: '%d.',
        week: {
            dow: 1,
            doy: 4
        }
    });
}));