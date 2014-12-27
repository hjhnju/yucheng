/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/util/smartSteps', function () {
    var mySteps = [
            10,
            25,
            50
        ];
    var mySections = [
            4,
            5,
            6
        ];
    var custOpts;
    var custSteps;
    var custSecs;
    var minLocked;
    var maxLocked;
    var MT = Math;
    var MATH_ROUND = MT.round;
    var MATH_FLOOR = MT.floor;
    var MATH_CEIL = MT.ceil;
    var MATH_ABS = MT.abs;
    function MATH_LOG(n) {
        return MT.log(MATH_ABS(n)) / MT.LN10;
    }
    function MATH_POW(n) {
        return MT.pow(10, n);
    }
    function MATH_ISINT(n) {
        return n === MATH_FLOOR(n);
    }
    function smartSteps(min, max, section, opts) {
        custOpts = opts || {};
        custSteps = custOpts.steps || mySteps;
        custSecs = custOpts.secs || mySections;
        section = MATH_ROUND(+section || 0) % 99;
        min = +min || 0;
        max = +max || 0;
        minLocked = maxLocked = 0;
        if ('min' in custOpts) {
            min = +custOpts.min || 0;
            minLocked = 1;
        }
        if ('max' in custOpts) {
            max = +custOpts.max || 0;
            maxLocked = 1;
        }
        if (min > max) {
            max = [
                min,
                min = max
            ][0];
        }
        var span = max - min;
        if (minLocked && maxLocked) {
            return bothLocked(min, max, section);
        }
        if (span < (section || 5)) {
            if (MATH_ISINT(min) && MATH_ISINT(max)) {
                return forInteger(min, max, section);
            } else if (span === 0) {
                return forSpan0(min, max, section);
            }
        }
        return coreCalc(min, max, section);
    }
    function makeResult(newMin, newMax, section, expon) {
        expon = expon || 0;
        var expStep = expNum((newMax - newMin) / section, -1);
        var expMin = expNum(newMin, -1, 1);
        var expMax = expNum(newMax, -1);
        var minExp = MT.min(expStep.e, expMin.e, expMax.e);
        expFixTo(expStep, {
            c: 0,
            e: minExp
        });
        expFixTo(expMin, expStep, 1);
        expFixTo(expMax, expStep);
        expon += minExp;
        newMin = expMin.c;
        newMax = expMax.c;
        var step = (newMax - newMin) / section;
        var zoom = MATH_POW(expon);
        var fixTo = 0;
        var points = [];
        for (var i = section + 1; i--;) {
            points[i] = (newMin + step * i) * zoom;
        }
        if (expon < 0) {
            fixTo = decimals(zoom);
            step = +(step * zoom).toFixed(fixTo);
            newMin = +(newMin * zoom).toFixed(fixTo);
            newMax = +(newMax * zoom).toFixed(fixTo);
            for (var i = points.length; i--;) {
                points[i] = points[i].toFixed(fixTo);
                +points[i] === 0 && (points[i] = '0');
            }
        } else {
            newMin *= zoom;
            newMax *= zoom;
            step *= zoom;
        }
        custSecs = 0;
        custSteps = 0;
        custOpts = 0;
        return {
            min: newMin,
            max: newMax,
            secs: section,
            step: step,
            fix: fixTo,
            exp: expon,
            pnts: points
        };
    }
    function expNum(num, digit, byFloor) {
        digit = MATH_ROUND(digit % 10) || 2;
        if (digit < 0) {
            if (MATH_ISINT(num)) {
                digit = ('' + MATH_ABS(num)).replace(/0+$/, '').length || 1;
            } else {
                num = num.toFixed(15).replace(/0+$/, '');
                digit = num.replace('.', '').replace(/^[-0]+/, '').length;
                num = +num;
            }
        }
        var expon = MATH_FLOOR(MATH_LOG(num)) - digit + 1;
        var cNum = +(num * MATH_POW(-expon)).toFixed(15) || 0;
        cNum = byFloor ? MATH_FLOOR(cNum) : MATH_CEIL(cNum);
        !cNum && (expon = 0);
        if (('' + MATH_ABS(cNum)).length > digit) {
            expon += 1;
            cNum /= 10;
        }
        return {
            c: cNum,
            e: expon
        };
    }
    function expFixTo(expnum1, expnum2, byFloor) {
        var deltaExp = expnum2.e - expnum1.e;
        if (deltaExp) {
            expnum1.e += deltaExp;
            expnum1.c *= MATH_POW(-deltaExp);
            expnum1.c = byFloor ? MATH_FLOOR(expnum1.c) : MATH_CEIL(expnum1.c);
        }
    }
    function expFixMin(expnum1, expnum2, byFloor) {
        if (expnum1.e < expnum2.e) {
            expFixTo(expnum2, expnum1, byFloor);
        } else {
            expFixTo(expnum1, expnum2, byFloor);
        }
    }
    function getCeil(num, rounds) {
        rounds = rounds || mySteps;
        num = expNum(num);
        var cNum = num.c;
        var i = 0;
        while (cNum > rounds[i]) {
            i++;
        }
        if (!rounds[i]) {
            cNum /= 10;
            num.e += 1;
            i = 0;
            while (cNum > rounds[i]) {
                i++;
            }
        }
        num.c = rounds[i];
        return num;
    }
    function coreCalc(min, max, section) {
        var step;
        var secs = section || +custSecs.slice(-1);
        var expStep = getCeil((max - min) / secs, custSteps);
        var expSpan = expNum(max - min);
        var expMin = expNum(min, -1, 1);
        var expMax = expNum(max, -1);
        expFixTo(expSpan, expStep);
        expFixTo(expMin, expStep, 1);
        expFixTo(expMax, expStep);
        if (!section) {
            secs = look4sections(expMin, expMax);
        } else {
            step = look4step(expMin, expMax, secs);
        }
        if (MATH_ISINT(min) && MATH_ISINT(max) && min * max >= 0) {
            if (max - min < secs) {
                return forInteger(min, max, secs);
            }
            secs = tryForInt(min, max, section, expMin, expMax, secs);
        }
        var arrMM = cross0(min, max, expMin.c, expMax.c);
        expMin.c = arrMM[0];
        expMax.c = arrMM[1];
        if (minLocked || maxLocked) {
            singleLocked(min, max, expMin, expMax);
        }
        return makeResult(expMin.c, expMax.c, secs, expMax.e);
    }
    function look4sections(expMin, expMax) {
        var section;
        var tmpStep, tmpMin, tmpMax;
        var reference = [];
        for (var i = custSecs.length; i--;) {
            section = custSecs[i];
            tmpStep = getCeil((expMax.c - expMin.c) / section, custSteps);
            tmpStep = tmpStep.c * MATH_POW(tmpStep.e);
            tmpMin = MATH_FLOOR(expMin.c / tmpStep) * tmpStep;
            tmpMax = MATH_CEIL(expMax.c / tmpStep) * tmpStep;
            reference[i] = {
                min: tmpMin,
                max: tmpMax,
                step: tmpStep,
                span: tmpMax - tmpMin
            };
        }
        reference.sort(function (a, b) {
            return a.span - b.span;
        });
        reference = reference[0];
        section = reference.span / reference.step;
        expMin.c = reference.min;
        expMax.c = reference.max;
        return section < 3 ? section * 2 : section;
    }
    function look4step(expMin, expMax, secs) {
        var span;
        var tmpMax;
        var tmpMin = expMax.c;
        var tmpStep = (expMax.c - expMin.c) / secs - 1;
        while (tmpMin > expMin.c) {
            tmpStep = getCeil(tmpStep + 1, custSteps);
            tmpStep = tmpStep.c * MATH_POW(tmpStep.e);
            span = tmpStep * secs;
            tmpMax = MATH_CEIL(expMax.c / tmpStep) * tmpStep;
            tmpMin = tmpMax - span;
        }
        var deltaMin = expMin.c - tmpMin;
        var deltaMax = tmpMax - expMax.c;
        var deltaDelta = deltaMin - deltaMax;
        if (deltaDelta >= tmpStep * 2) {
            deltaDelta = MATH_FLOOR(deltaDelta / tmpStep) * tmpStep;
            tmpMin += deltaDelta;
            tmpMax += deltaDelta;
        }
        expMin.c = tmpMin;
        expMax.c = tmpMax;
        return tmpStep;
    }
    function tryForInt(min, max, section, expMin, expMax, secs) {
        var span = expMax.c - expMin.c;
        var step = span / secs * MATH_POW(expMax.e);
        if (!MATH_ISINT(step)) {
            step = MATH_FLOOR(step);
            span = step * secs;
            if (span < max - min) {
                step += 1;
                span = step * secs;
                if (!section && step * (secs - 1) >= max - min) {
                    secs -= 1;
                    span = step * secs;
                }
            }
            if (span >= max - min) {
                var delta = span - (max - min);
                expMin.c = MATH_ROUND(min - delta / 2);
                expMax.c = MATH_ROUND(max + delta / 2);
                expMin.e = 0;
                expMax.e = 0;
            }
        }
        return secs;
    }
    function forInteger(min, max, section) {
        section = section || 5;
        if (minLocked) {
            max = min + section;
        } else if (maxLocked) {
            min = max - section;
        } else {
            var delta = section - (max - min);
            var newMin = MATH_ROUND(min - delta / 2);
            var newMax = MATH_ROUND(max + delta / 2);
            var arrMM = cross0(min, max, newMin, newMax);
            min = arrMM[0];
            max = arrMM[1];
        }
        return makeResult(min, max, section);
    }
    function forSpan0(min, max, section) {
        section = section || 5;
        var delta = MT.min(MATH_ABS(max / section), section) / 2.1;
        if (minLocked) {
            max = min + delta;
        } else if (maxLocked) {
            min = max - delta;
        } else {
            min = min - delta;
            max = max + delta;
        }
        return coreCalc(min, max, section);
    }
    function cross0(min, max, newMin, newMax) {
        if (min >= 0 && newMin < 0) {
            newMax -= newMin;
            newMin = 0;
        } else if (max <= 0 && newMax > 0) {
            newMin -= newMax;
            newMax = 0;
        }
        return [
            newMin,
            newMax
        ];
    }
    function decimals(num) {
        num = (+num).toFixed(15).split('.');
        return num.pop().replace(/0+$/, '').length;
    }
    function singleLocked(min, max, emin, emax) {
        if (minLocked) {
            var expMin = expNum(min, 4, 1);
            if (emin.e - expMin.e > 6) {
                expMin = {
                    c: 0,
                    e: emin.e
                };
            }
            expFixMin(emin, expMin);
            expFixMin(emax, expMin);
            emax.c += expMin.c - emin.c;
            emin.c = expMin.c;
        } else if (maxLocked) {
            var expMax = expNum(max, 4);
            if (emax.e - expMax.e > 6) {
                expMax = {
                    c: 0,
                    e: emax.e
                };
            }
            expFixMin(emin, expMax);
            expFixMin(emax, expMax);
            emin.c += expMax.c - emax.c;
            emax.c = expMax.c;
        }
    }
    function bothLocked(min, max, section) {
        var trySecs = section ? [section] : custSecs;
        var span = max - min;
        if (span === 0) {
            max = expNum(max, 3);
            section = trySecs[0];
            max.c = MATH_ROUND(max.c + section / 2);
            return makeResult(max.c - section, max.c, section, max.e);
        }
        if (MATH_ABS(max / span) < 0.000001) {
            max = 0;
        }
        if (MATH_ABS(min / span) < 0.000001) {
            min = 0;
        }
        var step, deltaSpan, score;
        var scoreS = [
                [
                    5,
                    10
                ],
                [
                    10,
                    2
                ],
                [
                    50,
                    10
                ],
                [
                    100,
                    2
                ]
            ];
        var reference = [];
        var debugLog = [];
        var expSpan = expNum(max - min, 3);
        var expMin = expNum(min, -1, 1);
        var expMax = expNum(max, -1);
        expFixTo(expMin, expSpan, 1);
        expFixTo(expMax, expSpan);
        span = expMax.c - expMin.c;
        expSpan.c = span;
        for (var i = trySecs.length; i--;) {
            section = trySecs[i];
            step = MATH_CEIL(span / section);
            deltaSpan = step * section - span;
            score = (deltaSpan + 3) * 3;
            score += (section - trySecs[0] + 2) * 2;
            if (section % 5 === 0) {
                score -= 10;
            }
            for (var j = scoreS.length; j--;) {
                if (step % scoreS[j][0] === 0) {
                    score /= scoreS[j][1];
                }
            }
            debugLog[i] = [
                section,
                step,
                deltaSpan,
                score
            ].join();
            reference[i] = {
                secs: section,
                step: step,
                delta: deltaSpan,
                score: score
            };
        }
        reference.sort(function (a, b) {
            return a.score - b.score;
        });
        reference = reference[0];
        expMin.c = MATH_ROUND(expMin.c - reference.delta / 2);
        expMax.c = MATH_ROUND(expMax.c + reference.delta / 2);
        return makeResult(expMin.c, expMax.c, reference.secs, expSpan.e);
    }
    return smartSteps;
});