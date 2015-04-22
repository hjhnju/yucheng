! function(e, t) {
    function n(e) {
        var t = e.length,
            n = lt.type(e);
        if (lt.isWindow(e)) return !1;
        if (1 === e.nodeType && t) return !0;
        else return "array" === n || "function" !== n && (0 === t || "number" == typeof t && t > 0 && t - 1 in e)
    }

    function i(e) {
        var t = Et[e] = {};
        return lt.each(e.match(ft) || [], function(e, n) {
            t[n] = !0
        }), t
    }

    function r(e, n, i, r) {
        if (lt.acceptData(e)) {
            var o, s, a = lt.expando,
                l = "string" == typeof n,
                u = e.nodeType,
                f = u ? lt.cache : e,
                c = u ? e[a] : e[a] && a;
            if (c && f[c] && (r || f[c].data) || !l || i !== t) {
                if (!c)
                    if (u) e[a] = c = Z.pop() || lt.guid++;
                    else c = a;
                if (!f[c])
                    if (f[c] = {}, !u) f[c].toJSON = lt.noop;
                if ("object" == typeof n || "function" == typeof n)
                    if (r) f[c] = lt.extend(f[c], n);
                    else f[c].data = lt.extend(f[c].data, n);
                if (o = f[c], !r) {
                    if (!o.data) o.data = {};
                    o = o.data
                }
                if (i !== t) o[lt.camelCase(n)] = i;
                if (l) {
                    if (s = o[n], null == s) s = o[lt.camelCase(n)]
                } else s = o;
                return s
            }
        }
    }

    function o(e, t, n) {
        if (lt.acceptData(e)) {
            var i, r, o, s = e.nodeType,
                l = s ? lt.cache : e,
                u = s ? e[lt.expando] : lt.expando;
            if (l[u]) {
                if (t)
                    if (o = n ? l[u] : l[u].data) {
                        if (!lt.isArray(t))
                            if (t in o) t = [t];
                            else if (t = lt.camelCase(t), t in o) t = [t];
                        else t = t.split(" ");
                        else t = t.concat(lt.map(t, lt.camelCase));
                        for (i = 0, r = t.length; r > i; i++) delete o[t[i]];
                        if (!(n ? a : lt.isEmptyObject)(o)) return
                    }
                if (!n)
                    if (delete l[u].data, !a(l[u])) return;
                if (s) lt.cleanData([e], !0);
                else if (lt.support.deleteExpando || l != l.window) delete l[u];
                else l[u] = null
            }
        }
    }

    function s(e, n, i) {
        if (i === t && 1 === e.nodeType) {
            var r = "data-" + n.replace(Nt, "-$1").toLowerCase();
            if (i = e.getAttribute(r), "string" == typeof i) {
                try {
                    i = "true" === i ? !0 : "false" === i ? !1 : "null" === i ? null : +i + "" === i ? +i : Ct.test(i) ? lt.parseJSON(i) : i
                } catch (o) {}
                lt.data(e, n, i)
            } else i = t
        }
        return i
    }

    function a(e) {
        var t;
        for (t in e)
            if ("data" !== t || !lt.isEmptyObject(e[t])) {
                if ("toJSON" !== t) return !1
            } else;
        return !0
    }

    function l() {
        return !0
    }

    function u() {
        return !1
    }

    function f(e, t) {
        do e = e[t]; while (e && 1 !== e.nodeType);
        return e
    }

    function c(e, t, n) {
        if (t = t || 0, lt.isFunction(t)) return lt.grep(e, function(e, i) {
            var r = !!t.call(e, i, e);
            return r === n
        });
        else if (t.nodeType) return lt.grep(e, function(e) {
            return e === t === n
        });
        else if ("string" == typeof t) {
            var i = lt.grep(e, function(e) {
                return 1 === e.nodeType
            });
            if (Wt.test(t)) return lt.filter(t, i, !n);
            else t = lt.filter(t, i)
        }
        return lt.grep(e, function(e) {
            return lt.inArray(e, t) >= 0 === n
        })
    }

    function p(e) {
        var t = Ut.split("|"),
            n = e.createDocumentFragment();
        if (n.createElement)
            for (; t.length;) n.createElement(t.pop());
        return n
    }

    function d(e, t) {
        return e.getElementsByTagName(t)[0] || e.appendChild(e.ownerDocument.createElement(t))
    }

    function h(e) {
        var t = e.getAttributeNode("type");
        return e.type = (t && t.specified) + "/" + e.type, e
    }

    function m(e) {
        var t = on.exec(e.type);
        if (t) e.type = t[1];
        else e.removeAttribute("type");
        return e
    }

    function g(e, t) {
        for (var n, i = 0; null != (n = e[i]); i++) lt._data(n, "globalEval", !t || lt._data(t[i], "globalEval"))
    }

    function y(e, t) {
        if (1 === t.nodeType && lt.hasData(e)) {
            var n, i, r, o = lt._data(e),
                s = lt._data(t, o),
                a = o.events;
            if (a) {
                delete s.handle, s.events = {};
                for (n in a)
                    for (i = 0, r = a[n].length; r > i; i++) lt.event.add(t, n, a[n][i])
            }
            if (s.data) s.data = lt.extend({}, s.data)
        }
    }

    function v(e, t) {
        var n, i, r;
        if (1 === t.nodeType) {
            if (n = t.nodeName.toLowerCase(), !lt.support.noCloneEvent && t[lt.expando]) {
                r = lt._data(t);
                for (i in r.events) lt.removeEvent(t, i, r.handle);
                t.removeAttribute(lt.expando)
            }
            if ("script" === n && t.text !== e.text) h(t).text = e.text, m(t);
            else if ("object" === n) {
                if (t.parentNode) t.outerHTML = e.outerHTML;
                if (lt.support.html5Clone && e.innerHTML && !lt.trim(t.innerHTML)) t.innerHTML = e.innerHTML
            } else if ("input" === n && tn.test(e.type)) {
                if (t.defaultChecked = t.checked = e.checked, t.value !== e.value) t.value = e.value
            } else if ("option" === n) t.defaultSelected = t.selected = e.defaultSelected;
            else if ("input" === n || "textarea" === n) t.defaultValue = e.defaultValue
        }
    }

    function b(e, n) {
        var i, r, o = 0,
            s = typeof e.getElementsByTagName !== G ? e.getElementsByTagName(n || "*") : typeof e.querySelectorAll !== G ? e.querySelectorAll(n || "*") : t;
        if (!s)
            for (s = [], i = e.childNodes || e; null != (r = i[o]); o++)
                if (!n || lt.nodeName(r, n)) s.push(r);
                else lt.merge(s, b(r, n));
        return n === t || n && lt.nodeName(e, n) ? lt.merge([e], s) : s
    }

    function x(e) {
        if (tn.test(e.type)) e.defaultChecked = e.checked
    }

    function w(e, t) {
        if (t in e) return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), i = t, r = Nn.length; r--;)
            if (t = Nn[r] + n, t in e) return t;
        return i
    }

    function T(e, t) {
        return e = t || e, "none" === lt.css(e, "display") || !lt.contains(e.ownerDocument, e)
    }

    function E(e, t) {
        for (var n, i, r, o = [], s = 0, a = e.length; a > s; s++)
            if (i = e[s], i.style) {
                if (o[s] = lt._data(i, "olddisplay"), n = i.style.display, t) {
                    if (!o[s] && "none" === n) i.style.display = "";
                    if ("" === i.style.display && T(i)) o[s] = lt._data(i, "olddisplay", k(i.nodeName))
                } else if (!o[s])
                    if (r = T(i), n && "none" !== n || !r) lt._data(i, "olddisplay", r ? n : lt.css(i, "display"))
            } else;
        for (s = 0; a > s; s++)
            if (i = e[s], i.style) {
                if (!t || "none" === i.style.display || "" === i.style.display) i.style.display = t ? o[s] || "" : "none"
            } else;
        return e
    }

    function C(e, t, n) {
        var i = vn.exec(t);
        return i ? Math.max(0, i[1] - (n || 0)) + (i[2] || "px") : t
    }

    function N(e, t, n, i, r) {
        for (var o = n === (i ? "border" : "content") ? 4 : "width" === t ? 1 : 0, s = 0; 4 > o; o += 2) {
            if ("margin" === n) s += lt.css(e, n + Cn[o], !0, r);
            if (i) {
                if ("content" === n) s -= lt.css(e, "padding" + Cn[o], !0, r);
                if ("margin" !== n) s -= lt.css(e, "border" + Cn[o] + "Width", !0, r)
            } else if (s += lt.css(e, "padding" + Cn[o], !0, r), "padding" !== n) s += lt.css(e, "border" + Cn[o] + "Width", !0, r)
        }
        return s
    }

    function _(e, t, n) {
        var i = !0,
            r = "width" === t ? e.offsetWidth : e.offsetHeight,
            o = cn(e),
            s = lt.support.boxSizing && "border-box" === lt.css(e, "boxSizing", !1, o);
        if (0 >= r || null == r) {
            if (r = pn(e, t, o), 0 > r || null == r) r = e.style[t];
            if (bn.test(r)) return r;
            i = s && (lt.support.boxSizingReliable || r === e.style[t]), r = parseFloat(r) || 0
        }
        return r + N(e, t, n || (s ? "border" : "content"), i, o) + "px"
    }

    function k(e) {
        var t = X,
            n = wn[e];
        if (!n) {
            if (n = S(e, t), "none" === n || !n) fn = (fn || lt("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(t.documentElement), t = (fn[0].contentWindow || fn[0].contentDocument).document, t.write("<!doctype html><html><body>"), t.close(), n = S(e, t), fn.detach();
            wn[e] = n
        }
        return n
    }

    function S(e, t) {
        var n = lt(t.createElement(e)).appendTo(t.body),
            i = lt.css(n[0], "display");
        return n.remove(), i
    }

    function D(e, t, n, i) {
        var r;
        if (lt.isArray(t)) lt.each(t, function(t, r) {
            if (n || kn.test(e)) i(e, r);
            else D(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
        });
        else if (!n && "object" === lt.type(t))
            for (r in t) D(e + "[" + r + "]", t[r], n, i);
        else i(e, t)
    }

    function A(e) {
        return function(t, n) {
            if ("string" != typeof t) n = t, t = "*";
            var i, r = 0,
                o = t.toLowerCase().match(ft) || [];
            if (lt.isFunction(n))
                for (; i = o[r++];)
                    if ("+" === i[0]) i = i.slice(1) || "*", (e[i] = e[i] || []).unshift(n);
                    else(e[i] = e[i] || []).push(n)
        }
    }

    function j(e, t, n, i) {
        function r(a) {
            var l;
            return o[a] = !0, lt.each(e[a] || [], function(e, a) {
                var u = a(t, n, i);
                if ("string" == typeof u && !s && !o[u]) return t.dataTypes.unshift(u), r(u), !1;
                else if (s) return !(l = u)
            }), l
        }
        var o = {},
            s = e === zn;
        return r(t.dataTypes[0]) || !o["*"] && r("*")
    }

    function L(e, n) {
        var i, r, o = lt.ajaxSettings.flatOptions || {};
        for (r in n)
            if (n[r] !== t)(o[r] ? e : i || (i = {}))[r] = n[r];
        if (i) lt.extend(!0, e, i);
        return e
    }

    function M(e, n, i) {
        var r, o, s, a, l = e.contents,
            u = e.dataTypes,
            f = e.responseFields;
        for (a in f)
            if (a in i) n[f[a]] = i[a];
        for (;
            "*" === u[0];)
            if (u.shift(), o === t) o = e.mimeType || n.getResponseHeader("Content-Type");
        if (o)
            for (a in l)
                if (l[a] && l[a].test(o)) {
                    u.unshift(a);
                    break
                }
        if (u[0] in i) s = u[0];
        else {
            for (a in i) {
                if (!u[0] || e.converters[a + " " + u[0]]) {
                    s = a;
                    break
                }
                if (!r) r = a
            }
            s = s || r
        }
        if (s) {
            if (s !== u[0]) u.unshift(s);
            return i[s]
        }
    }

    function R(e, t) {
        var n, i, r, o, s = {},
            a = 0,
            l = e.dataTypes.slice(),
            u = l[0];
        if (e.dataFilter) t = e.dataFilter(t, e.dataType);
        if (l[1])
            for (r in e.converters) s[r.toLowerCase()] = e.converters[r];
        for (; i = l[++a];)
            if ("*" !== i) {
                if ("*" !== u && u !== i) {
                    if (r = s[u + " " + i] || s["* " + i], !r)
                        for (n in s)
                            if (o = n.split(" "), o[1] === i)
                                if (r = s[u + " " + o[0]] || s["* " + o[0]]) {
                                    if (r === !0) r = s[n];
                                    else if (s[n] !== !0) i = o[0], l.splice(a--, 0, i);
                                    break
                                }
                    if (r !== !0)
                        if (r && e["throws"]) t = r(t);
                        else try {
                            t = r(t)
                        } catch (f) {
                            return {
                                state: "parsererror",
                                error: r ? f : "No conversion from " + u + " to " + i
                            }
                        }
                }
                u = i
            }
        return {
            state: "success",
            data: t
        }
    }

    function I() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {}
    }

    function O() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {}
    }

    function H() {
        return setTimeout(function() {
            Zn = t
        }), Zn = lt.now()
    }

    function q(e, t) {
        lt.each(t, function(t, n) {
            for (var i = (oi[t] || []).concat(oi["*"]), r = 0, o = i.length; o > r; r++)
                if (i[r].call(e, t, n)) return
        })
    }

    function P(e, t, n) {
        var i, r, o = 0,
            s = ri.length,
            a = lt.Deferred().always(function() {
                delete l.elem
            }),
            l = function() {
                if (r) return !1;
                for (var t = Zn || H(), n = Math.max(0, u.startTime + u.duration - t), i = n / u.duration || 0, o = 1 - i, s = 0, l = u.tweens.length; l > s; s++) u.tweens[s].run(o);
                if (a.notifyWith(e, [u, o, n]), 1 > o && l) return n;
                else return a.resolveWith(e, [u]), !1
            },
            u = a.promise({
                elem: e,
                props: lt.extend({}, t),
                opts: lt.extend(!0, {
                    specialEasing: {}
                }, n),
                originalProperties: t,
                originalOptions: n,
                startTime: Zn || H(),
                duration: n.duration,
                tweens: [],
                createTween: function(t, n) {
                    var i = lt.Tween(e, u.opts, t, n, u.opts.specialEasing[t] || u.opts.easing);
                    return u.tweens.push(i), i
                },
                stop: function(t) {
                    var n = 0,
                        i = t ? u.tweens.length : 0;
                    if (r) return this;
                    for (r = !0; i > n; n++) u.tweens[n].run(1);
                    if (t) a.resolveWith(e, [u, t]);
                    else a.rejectWith(e, [u, t]);
                    return this
                }
            }),
            f = u.props;
        for (F(f, u.opts.specialEasing); s > o; o++)
            if (i = ri[o].call(u, e, f, u.opts)) return i;
        if (q(u, f), lt.isFunction(u.opts.start)) u.opts.start.call(e, u);
        return lt.fx.timer(lt.extend(l, {
            elem: e,
            anim: u,
            queue: u.opts.queue
        })), u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always)
    }

    function F(e, t) {
        var n, i, r, o, s;
        for (r in e) {
            if (i = lt.camelCase(r), o = t[i], n = e[r], lt.isArray(n)) o = n[1], n = e[r] = n[0];
            if (r !== i) e[i] = n, delete e[r];
            if (s = lt.cssHooks[i], s && "expand" in s) {
                n = s.expand(n), delete e[i];
                for (r in n)
                    if (!(r in e)) e[r] = n[r], t[r] = o
            } else t[i] = o
        }
    }

    function $(e, t, n) {
        var i, r, o, s, a, l, u, f, c, p = this,
            d = e.style,
            h = {},
            m = [],
            g = e.nodeType && T(e);
        if (!n.queue) {
            if (f = lt._queueHooks(e, "fx"), null == f.unqueued) f.unqueued = 0, c = f.empty.fire, f.empty.fire = function() {
                if (!f.unqueued) c()
            };
            f.unqueued++, p.always(function() {
                p.always(function() {
                    if (f.unqueued--, !lt.queue(e, "fx").length) f.empty.fire()
                })
            })
        }
        if (1 === e.nodeType && ("height" in t || "width" in t))
            if (n.overflow = [d.overflow, d.overflowX, d.overflowY], "inline" === lt.css(e, "display") && "none" === lt.css(e, "float"))
                if (!lt.support.inlineBlockNeedsLayout || "inline" === k(e.nodeName)) d.display = "inline-block";
                else d.zoom = 1;
        if (n.overflow)
            if (d.overflow = "hidden", !lt.support.shrinkWrapBlocks) p.always(function() {
                d.overflow = n.overflow[0], d.overflowX = n.overflow[1], d.overflowY = n.overflow[2]
            });
        for (r in t)
            if (s = t[r], ti.exec(s)) {
                if (delete t[r], l = l || "toggle" === s, s === (g ? "hide" : "show")) continue;
                m.push(r)
            }
        if (o = m.length) {
            if (a = lt._data(e, "fxshow") || lt._data(e, "fxshow", {}), "hidden" in a) g = a.hidden;
            if (l) a.hidden = !g;
            if (g) lt(e).show();
            else p.done(function() {
                lt(e).hide()
            });
            for (p.done(function() {
                    var t;
                    lt._removeData(e, "fxshow");
                    for (t in h) lt.style(e, t, h[t])
                }), r = 0; o > r; r++)
                if (i = m[r], u = p.createTween(i, g ? a[i] : 0), h[i] = a[i] || lt.style(e, i), !(i in a))
                    if (a[i] = u.start, g) u.end = u.start, u.start = "width" === i || "height" === i ? 1 : 0
        }
    }

    function B(e, t, n, i, r) {
        return new B.prototype.init(e, t, n, i, r)
    }

    function W(e, t) {
        var n, i = {
                height: e
            },
            r = 0;
        for (t = t ? 1 : 0; 4 > r; r += 2 - t) n = Cn[r], i["margin" + n] = i["padding" + n] = e;
        if (t) i.opacity = i.width = e;
        return i
    }

    function z(e) {
        return lt.isWindow(e) ? e : 9 === e.nodeType ? e.defaultView || e.parentWindow : !1
    }
    var Y, U, G = typeof t,
        X = e.document,
        V = e.location,
        K = e.jQuery,
        J = e.$,
        Q = {},
        Z = [],
        et = "1.9.1",
        tt = Z.concat,
        nt = Z.push,
        it = Z.slice,
        rt = Z.indexOf,
        ot = Q.toString,
        st = Q.hasOwnProperty,
        at = et.trim,
        lt = function(e, t) {
            return new lt.fn.init(e, t, U)
        },
        ut = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        ft = /\S+/g,
        ct = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
        pt = /^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/,
        dt = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        ht = /^[\],:{}\s]*$/,
        mt = /(?:^|:|,)(?:\s*\[)+/g,
        gt = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,
        yt = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,
        vt = /^-ms-/,
        bt = /-([\da-z])/gi,
        xt = function(e, t) {
            return t.toUpperCase()
        },
        wt = function(e) {
            if (X.addEventListener || "load" === e.type || "complete" === X.readyState) Tt(), lt.ready()
        },
        Tt = function() {
            if (X.addEventListener) X.removeEventListener("DOMContentLoaded", wt, !1), e.removeEventListener("load", wt, !1);
            else X.detachEvent("onreadystatechange", wt), e.detachEvent("onload", wt)
        };
    lt.fn = lt.prototype = {
        jquery: et,
        constructor: lt,
        init: function(e, n, i) {
            var r, o;
            if (!e) return this;
            if ("string" == typeof e) {
                if ("<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3) r = [null, e, null];
                else r = pt.exec(e);
                if (r && (r[1] || !n))
                    if (r[1]) {
                        if (n = n instanceof lt ? n[0] : n, lt.merge(this, lt.parseHTML(r[1], n && n.nodeType ? n.ownerDocument || n : X, !0)), dt.test(r[1]) && lt.isPlainObject(n))
                            for (r in n)
                                if (lt.isFunction(this[r])) this[r](n[r]);
                                else this.attr(r, n[r]);
                        return this
                    } else {
                        if (o = X.getElementById(r[2]), o && o.parentNode) {
                            if (o.id !== r[2]) return i.find(e);
                            this.length = 1, this[0] = o
                        }
                        return this.context = X, this.selector = e, this
                    } else if (!n || n.jquery) return (n || i).find(e);
                else return this.constructor(n).find(e)
            } else if (e.nodeType) return this.context = this[0] = e, this.length = 1, this;
            else if (lt.isFunction(e)) return i.ready(e);
            if (e.selector !== t) this.selector = e.selector, this.context = e.context;
            return lt.makeArray(e, this)
        },
        selector: "",
        length: 0,
        size: function() {
            return this.length
        },
        toArray: function() {
            return it.call(this)
        },
        get: function(e) {
            return null == e ? this.toArray() : 0 > e ? this[this.length + e] : this[e]
        },
        pushStack: function(e) {
            var t = lt.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        },
        each: function(e, t) {
            return lt.each(this, e, t)
        },
        ready: function(e) {
            return lt.ready.promise().done(e), this
        },
        slice: function() {
            return this.pushStack(it.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        eq: function(e) {
            var t = this.length,
                n = +e + (0 > e ? t : 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        },
        map: function(e) {
            return this.pushStack(lt.map(this, function(t, n) {
                return e.call(t, n, t)
            }))
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: nt,
        sort: [].sort,
        splice: [].splice
    }, lt.fn.init.prototype = lt.fn, lt.extend = lt.fn.extend = function() {
        var e, n, i, r, o, s, a = arguments[0] || {},
            l = 1,
            u = arguments.length,
            f = !1;
        if ("boolean" == typeof a) f = a, a = arguments[1] || {}, l = 2;
        if ("object" != typeof a && !lt.isFunction(a)) a = {};
        if (u === l) a = this, --l;
        for (; u > l; l++)
            if (null != (o = arguments[l]))
                for (r in o)
                    if (e = a[r], i = o[r], a !== i) {
                        if (f && i && (lt.isPlainObject(i) || (n = lt.isArray(i)))) {
                            if (n) n = !1, s = e && lt.isArray(e) ? e : [];
                            else s = e && lt.isPlainObject(e) ? e : {};
                            a[r] = lt.extend(f, s, i)
                        } else if (i !== t) a[r] = i
                    } else;
        return a
    }, lt.extend({
        noConflict: function(t) {
            if (e.$ === lt) e.$ = J;
            if (t && e.jQuery === lt) e.jQuery = K;
            return lt
        },
        isReady: !1,
        readyWait: 1,
        holdReady: function(e) {
            if (e) lt.readyWait++;
            else lt.ready(!0)
        },
        ready: function(e) {
            if (e === !0 ? !--lt.readyWait : !lt.isReady) {
                if (!X.body) return setTimeout(lt.ready);
                if (lt.isReady = !0, !(e !== !0 && --lt.readyWait > 0))
                    if (Y.resolveWith(X, [lt]), lt.fn.trigger) lt(X).trigger("ready").off("ready")
            }
        },
        isFunction: function(e) {
            return "function" === lt.type(e)
        },
        isArray: Array.isArray || function(e) {
            return "array" === lt.type(e)
        },
        isWindow: function(e) {
            return null != e && e == e.window
        },
        isNumeric: function(e) {
            return !isNaN(parseFloat(e)) && isFinite(e)
        },
        type: function(e) {
            if (null == e) return String(e);
            else return "object" == typeof e || "function" == typeof e ? Q[ot.call(e)] || "object" : typeof e
        },
        isPlainObject: function(e) {
            if (!e || "object" !== lt.type(e) || e.nodeType || lt.isWindow(e)) return !1;
            try {
                if (e.constructor && !st.call(e, "constructor") && !st.call(e.constructor.prototype, "isPrototypeOf")) return !1
            } catch (n) {
                return !1
            }
            var i;
            for (i in e);
            return i === t || st.call(e, i)
        },
        isEmptyObject: function(e) {
            var t;
            for (t in e) return !1;
            return !0
        },
        error: function(e) {
            throw new Error(e)
        },
        parseHTML: function(e, t, n) {
            if (!e || "string" != typeof e) return null;
            if ("boolean" == typeof t) n = t, t = !1;
            t = t || X;
            var i = dt.exec(e),
                r = !n && [];
            if (i) return [t.createElement(i[1])];
            if (i = lt.buildFragment([e], t, r), r) lt(r).remove();
            return lt.merge([], i.childNodes)
        },
        parseJSON: function(t) {
            if (e.JSON && e.JSON.parse) return e.JSON.parse(t);
            if (null === t) return t;
            if ("string" == typeof t)
                if (t = lt.trim(t))
                    if (ht.test(t.replace(gt, "@").replace(yt, "]").replace(mt, ""))) return new Function("return " + t)();
            lt.error("Invalid JSON: " + t)
        },
        parseXML: function(n) {
            var i, r;
            if (!n || "string" != typeof n) return null;
            try {
                if (e.DOMParser) r = new DOMParser, i = r.parseFromString(n, "text/xml");
                else i = new ActiveXObject("Microsoft.XMLDOM"), i.async = "false", i.loadXML(n)
            } catch (o) {
                i = t
            }
            if (!i || !i.documentElement || i.getElementsByTagName("parsererror").length) lt.error("Invalid XML: " + n);
            return i
        },
        noop: function() {},
        globalEval: function(t) {
            if (t && lt.trim(t))(e.execScript || function(t) {
                e.eval.call(e, t)
            })(t)
        },
        camelCase: function(e) {
            return e.replace(vt, "ms-").replace(bt, xt)
        },
        nodeName: function(e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        },
        each: function(e, t, i) {
            var r, o = 0,
                s = e.length,
                a = n(e);
            if (i) {
                if (a)
                    for (; s > o && (r = t.apply(e[o], i), r !== !1); o++);
                else
                    for (o in e)
                        if (r = t.apply(e[o], i), r === !1) break
            } else if (a)
                for (; s > o && (r = t.call(e[o], o, e[o]), r !== !1); o++);
            else
                for (o in e)
                    if (r = t.call(e[o], o, e[o]), r === !1) break; return e
        },
        trim: at && !at.call("﻿ ") ? function(e) {
            return null == e ? "" : at.call(e)
        } : function(e) {
            return null == e ? "" : (e + "").replace(ct, "")
        },
        makeArray: function(e, t) {
            var i = t || [];
            if (null != e)
                if (n(Object(e))) lt.merge(i, "string" == typeof e ? [e] : e);
                else nt.call(i, e);
            return i
        },
        inArray: function(e, t, n) {
            var i;
            if (t) {
                if (rt) return rt.call(t, e, n);
                for (i = t.length, n = n ? 0 > n ? Math.max(0, i + n) : n : 0; i > n; n++)
                    if (n in t && t[n] === e) return n
            }
            return -1
        },
        merge: function(e, n) {
            var i = n.length,
                r = e.length,
                o = 0;
            if ("number" == typeof i)
                for (; i > o; o++) e[r++] = n[o];
            else
                for (; n[o] !== t;) e[r++] = n[o++];
            return e.length = r, e
        },
        grep: function(e, t, n) {
            var i, r = [],
                o = 0,
                s = e.length;
            for (n = !!n; s > o; o++)
                if (i = !!t(e[o], o), n !== i) r.push(e[o]);
            return r
        },
        map: function(e, t, i) {
            var r, o = 0,
                s = e.length,
                a = n(e),
                l = [];
            if (a) {
                for (; s > o; o++)
                    if (r = t(e[o], o, i), null != r) l[l.length] = r
            } else
                for (o in e)
                    if (r = t(e[o], o, i), null != r) l[l.length] = r; return tt.apply([], l)
        },
        guid: 1,
        proxy: function(e, n) {
            var i, r, o;
            if ("string" == typeof n) o = e[n], n = e, e = o;
            if (!lt.isFunction(e)) return t;
            else return i = it.call(arguments, 2), r = function() {
                return e.apply(n || this, i.concat(it.call(arguments)))
            }, r.guid = e.guid = e.guid || lt.guid++, r
        },
        access: function(e, n, i, r, o, s, a) {
            var l = 0,
                u = e.length,
                f = null == i;
            if ("object" === lt.type(i)) {
                o = !0;
                for (l in i) lt.access(e, n, l, i[l], !0, s, a)
            } else if (r !== t) {
                if (o = !0, !lt.isFunction(r)) a = !0;
                if (f)
                    if (a) n.call(e, r), n = null;
                    else f = n, n = function(e, t, n) {
                        return f.call(lt(e), n)
                    };
                if (n)
                    for (; u > l; l++) n(e[l], i, a ? r : r.call(e[l], l, n(e[l], i)))
            }
            return o ? e : f ? n.call(e) : u ? n(e[0], i) : s
        },
        now: function() {
            return (new Date).getTime()
        }
    }), lt.ready.promise = function(t) {
        if (!Y)
            if (Y = lt.Deferred(), "complete" === X.readyState) setTimeout(lt.ready);
            else if (X.addEventListener) X.addEventListener("DOMContentLoaded", wt, !1), e.addEventListener("load", wt, !1);
        else {
            X.attachEvent("onreadystatechange", wt), e.attachEvent("onload", wt);
            var n = !1;
            try {
                n = null == e.frameElement && X.documentElement
            } catch (i) {}
            if (n && n.doScroll) ! function r() {
                if (!lt.isReady) {
                    try {
                        n.doScroll("left")
                    } catch (e) {
                        return setTimeout(r, 50)
                    }
                    Tt(), lt.ready()
                }
            }()
        }
        return Y.promise(t)
    }, lt.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(e, t) {
        Q["[object " + t + "]"] = t.toLowerCase()
    }), U = lt(X);
    var Et = {};
    lt.Callbacks = function(e) {
        e = "string" == typeof e ? Et[e] || i(e) : lt.extend({}, e);
        var n, r, o, s, a, l, u = [],
            f = !e.once && [],
            c = function(t) {
                for (r = e.memory && t, o = !0, a = l || 0, l = 0, s = u.length, n = !0; u && s > a; a++)
                    if (u[a].apply(t[0], t[1]) === !1 && e.stopOnFalse) {
                        r = !1;
                        break
                    }
                if (n = !1, u)
                    if (f) {
                        if (f.length) c(f.shift())
                    } else if (r) u = [];
                else p.disable()
            },
            p = {
                add: function() {
                    if (u) {
                        var t = u.length;
                        if (function i(t) {
                                lt.each(t, function(t, n) {
                                    var r = lt.type(n);
                                    if ("function" === r) {
                                        if (!e.unique || !p.has(n)) u.push(n)
                                    } else if (n && n.length && "string" !== r) i(n)
                                })
                            }(arguments), n) s = u.length;
                        else if (r) l = t, c(r)
                    }
                    return this
                },
                remove: function() {
                    if (u) lt.each(arguments, function(e, t) {
                        for (var i;
                            (i = lt.inArray(t, u, i)) > -1;)
                            if (u.splice(i, 1), n) {
                                if (s >= i) s--;
                                if (a >= i) a--
                            }
                    });
                    return this
                },
                has: function(e) {
                    return e ? lt.inArray(e, u) > -1 : !(!u || !u.length)
                },
                empty: function() {
                    return u = [], this
                },
                disable: function() {
                    return u = f = r = t, this
                },
                disabled: function() {
                    return !u
                },
                lock: function() {
                    if (f = t, !r) p.disable();
                    return this
                },
                locked: function() {
                    return !f
                },
                fireWith: function(e, t) {
                    if (t = t || [], t = [e, t.slice ? t.slice() : t], u && (!o || f))
                        if (n) f.push(t);
                        else c(t);
                    return this
                },
                fire: function() {
                    return p.fireWith(this, arguments), this
                },
                fired: function() {
                    return !!o
                }
            };
        return p
    }, lt.extend({
        Deferred: function(e) {
            var t = [
                    ["resolve", "done", lt.Callbacks("once memory"), "resolved"],
                    ["reject", "fail", lt.Callbacks("once memory"), "rejected"],
                    ["notify", "progress", lt.Callbacks("memory")]
                ],
                n = "pending",
                i = {
                    state: function() {
                        return n
                    },
                    always: function() {
                        return r.done(arguments).fail(arguments), this
                    },
                    then: function() {
                        var e = arguments;
                        return lt.Deferred(function(n) {
                            lt.each(t, function(t, o) {
                                var s = o[0],
                                    a = lt.isFunction(e[t]) && e[t];
                                r[o[1]](function() {
                                    var e = a && a.apply(this, arguments);
                                    if (e && lt.isFunction(e.promise)) e.promise().done(n.resolve).fail(n.reject).progress(n.notify);
                                    else n[s + "With"](this === i ? n.promise() : this, a ? [e] : arguments)
                                })
                            }), e = null
                        }).promise()
                    },
                    promise: function(e) {
                        return null != e ? lt.extend(e, i) : i
                    }
                },
                r = {};
            if (i.pipe = i.then, lt.each(t, function(e, o) {
                    var s = o[2],
                        a = o[3];
                    if (i[o[1]] = s.add, a) s.add(function() {
                        n = a
                    }, t[1 ^ e][2].disable, t[2][2].lock);
                    r[o[0]] = function() {
                        return r[o[0] + "With"](this === r ? i : this, arguments), this
                    }, r[o[0] + "With"] = s.fireWith
                }), i.promise(r), e) e.call(r, r);
            return r
        },
        when: function(e) {
            var t, n, i, r = 0,
                o = it.call(arguments),
                s = o.length,
                a = 1 !== s || e && lt.isFunction(e.promise) ? s : 0,
                l = 1 === a ? e : lt.Deferred(),
                u = function(e, n, i) {
                    return function(r) {
                        if (n[e] = this, i[e] = arguments.length > 1 ? it.call(arguments) : r, i === t) l.notifyWith(n, i);
                        else if (!--a) l.resolveWith(n, i)
                    }
                };
            if (s > 1)
                for (t = new Array(s), n = new Array(s), i = new Array(s); s > r; r++)
                    if (o[r] && lt.isFunction(o[r].promise)) o[r].promise().done(u(r, i, o)).fail(l.reject).progress(u(r, n, t));
                    else --a;
            if (!a) l.resolveWith(i, o);
            return l.promise()
        }
    }), lt.support = function() {
        var t, n, i, r, o, s, a, l, u, f, c = X.createElement("div");
        if (c.setAttribute("className", "t"), c.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", n = c.getElementsByTagName("*"), i = c.getElementsByTagName("a")[0], !n || !i || !n.length) return {};
        o = X.createElement("select"), a = o.appendChild(X.createElement("option")), r = c.getElementsByTagName("input")[0], i.style.cssText = "top:1px;float:left;opacity:.5", t = {
            getSetAttribute: "t" !== c.className,
            leadingWhitespace: 3 === c.firstChild.nodeType,
            tbody: !c.getElementsByTagName("tbody").length,
            htmlSerialize: !!c.getElementsByTagName("link").length,
            style: /top/.test(i.getAttribute("style")),
            hrefNormalized: "/a" === i.getAttribute("href"),
            opacity: /^0.5/.test(i.style.opacity),
            cssFloat: !!i.style.cssFloat,
            checkOn: !!r.value,
            optSelected: a.selected,
            enctype: !!X.createElement("form").enctype,
            html5Clone: "<:nav></:nav>" !== X.createElement("nav").cloneNode(!0).outerHTML,
            boxModel: "CSS1Compat" === X.compatMode,
            deleteExpando: !0,
            noCloneEvent: !0,
            inlineBlockNeedsLayout: !1,
            shrinkWrapBlocks: !1,
            reliableMarginRight: !0,
            boxSizingReliable: !0,
            pixelPosition: !1
        }, r.checked = !0, t.noCloneChecked = r.cloneNode(!0).checked, o.disabled = !0, t.optDisabled = !a.disabled;
        try {
            delete c.test
        } catch (p) {
            t.deleteExpando = !1
        }
        if (r = X.createElement("input"), r.setAttribute("value", ""), t.input = "" === r.getAttribute("value"), r.value = "t", r.setAttribute("type", "radio"), t.radioValue = "t" === r.value, r.setAttribute("checked", "t"), r.setAttribute("name", "t"), s = X.createDocumentFragment(), s.appendChild(r), t.appendChecked = r.checked, t.checkClone = s.cloneNode(!0).cloneNode(!0).lastChild.checked, c.attachEvent) c.attachEvent("onclick", function() {
            t.noCloneEvent = !1
        }), c.cloneNode(!0).click();
        for (f in {
                submit: !0,
                change: !0,
                focusin: !0
            }) c.setAttribute(l = "on" + f, "t"), t[f + "Bubbles"] = l in e || c.attributes[l].expando === !1;
        return c.style.backgroundClip = "content-box", c.cloneNode(!0).style.backgroundClip = "", t.clearCloneStyle = "content-box" === c.style.backgroundClip, lt(function() {
            var n, i, r, o = "padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",
                s = X.getElementsByTagName("body")[0];
            if (s) {
                if (n = X.createElement("div"), n.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", s.appendChild(n).appendChild(c), c.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", r = c.getElementsByTagName("td"), r[0].style.cssText = "padding:0;margin:0;border:0;display:none", u = 0 === r[0].offsetHeight, r[0].style.display = "", r[1].style.display = "none", t.reliableHiddenOffsets = u && 0 === r[0].offsetHeight, c.innerHTML = "", c.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;", t.boxSizing = 4 === c.offsetWidth, t.doesNotIncludeMarginInBodyOffset = 1 !== s.offsetTop, e.getComputedStyle) t.pixelPosition = "1%" !== (e.getComputedStyle(c, null) || {}).top, t.boxSizingReliable = "4px" === (e.getComputedStyle(c, null) || {
                    width: "4px"
                }).width, i = c.appendChild(X.createElement("div")), i.style.cssText = c.style.cssText = o, i.style.marginRight = i.style.width = "0", c.style.width = "1px", t.reliableMarginRight = !parseFloat((e.getComputedStyle(i, null) || {}).marginRight);
                if (typeof c.style.zoom !== G)
                    if (c.innerHTML = "", c.style.cssText = o + "width:1px;padding:1px;display:inline;zoom:1", t.inlineBlockNeedsLayout = 3 === c.offsetWidth, c.style.display = "block", c.innerHTML = "<div></div>", c.firstChild.style.width = "5px", t.shrinkWrapBlocks = 3 !== c.offsetWidth, t.inlineBlockNeedsLayout) s.style.zoom = 1;
                s.removeChild(n), n = c = r = i = null
            }
        }), n = o = s = a = i = r = null, t
    }();
    var Ct = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/,
        Nt = /([A-Z])/g;
    lt.extend({
        cache: {},
        expando: "jQuery" + (et + Math.random()).replace(/\D/g, ""),
        noData: {
            embed: !0,
            object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
            applet: !0
        },
        hasData: function(e) {
            return e = e.nodeType ? lt.cache[e[lt.expando]] : e[lt.expando], !!e && !a(e)
        },
        data: function(e, t, n) {
            return r(e, t, n)
        },
        removeData: function(e, t) {
            return o(e, t)
        },
        _data: function(e, t, n) {
            return r(e, t, n, !0)
        },
        _removeData: function(e, t) {
            return o(e, t, !0)
        },
        acceptData: function(e) {
            if (e.nodeType && 1 !== e.nodeType && 9 !== e.nodeType) return !1;
            var t = e.nodeName && lt.noData[e.nodeName.toLowerCase()];
            return !t || t !== !0 && e.getAttribute("classid") === t
        }
    }), lt.fn.extend({
        data: function(e, n) {
            var i, r, o = this[0],
                a = 0,
                l = null;
            if (e === t) {
                if (this.length)
                    if (l = lt.data(o), 1 === o.nodeType && !lt._data(o, "parsedAttrs")) {
                        for (i = o.attributes; a < i.length; a++)
                            if (r = i[a].name, !r.indexOf("data-")) r = lt.camelCase(r.slice(5)), s(o, r, l[r]);
                        lt._data(o, "parsedAttrs", !0)
                    }
                return l
            }
            if ("object" == typeof e) return this.each(function() {
                lt.data(this, e)
            });
            else return lt.access(this, function(n) {
                if (n === t) return o ? s(o, e, lt.data(o, e)) : null;
                else return void this.each(function() {
                    lt.data(this, e, n)
                })
            }, null, n, arguments.length > 1, null, !0)
        },
        removeData: function(e) {
            return this.each(function() {
                lt.removeData(this, e)
            })
        }
    }), lt.extend({
        queue: function(e, t, n) {
            var i;
            if (e) {
                if (t = (t || "fx") + "queue", i = lt._data(e, t), n)
                    if (!i || lt.isArray(n)) i = lt._data(e, t, lt.makeArray(n));
                    else i.push(n);
                return i || []
            }
        },
        dequeue: function(e, t) {
            t = t || "fx";
            var n = lt.queue(e, t),
                i = n.length,
                r = n.shift(),
                o = lt._queueHooks(e, t),
                s = function() {
                    lt.dequeue(e, t)
                };
            if ("inprogress" === r) r = n.shift(), i--;
            if (o.cur = r, r) {
                if ("fx" === t) n.unshift("inprogress");
                delete o.stop, r.call(e, s, o)
            }
            if (!i && o) o.empty.fire()
        },
        _queueHooks: function(e, t) {
            var n = t + "queueHooks";
            return lt._data(e, n) || lt._data(e, n, {
                empty: lt.Callbacks("once memory").add(function() {
                    lt._removeData(e, t + "queue"), lt._removeData(e, n)
                })
            })
        }
    }), lt.fn.extend({
        queue: function(e, n) {
            var i = 2;
            if ("string" != typeof e) n = e, e = "fx", i--;
            if (arguments.length < i) return lt.queue(this[0], e);
            else return n === t ? this : this.each(function() {
                var t = lt.queue(this, e, n);
                if (lt._queueHooks(this, e), "fx" === e && "inprogress" !== t[0]) lt.dequeue(this, e)
            })
        },
        dequeue: function(e) {
            return this.each(function() {
                lt.dequeue(this, e)
            })
        },
        delay: function(e, t) {
            return e = lt.fx ? lt.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function(t, n) {
                var i = setTimeout(t, e);
                n.stop = function() {
                    clearTimeout(i)
                }
            })
        },
        clearQueue: function(e) {
            return this.queue(e || "fx", [])
        },
        promise: function(e, n) {
            var i, r = 1,
                o = lt.Deferred(),
                s = this,
                a = this.length,
                l = function() {
                    if (!--r) o.resolveWith(s, [s])
                };
            if ("string" != typeof e) n = e, e = t;
            for (e = e || "fx"; a--;)
                if (i = lt._data(s[a], e + "queueHooks"), i && i.empty) r++, i.empty.add(l);
            return l(), o.promise(n)
        }
    });
    var _t, kt, St = /[\t\r\n]/g,
        Dt = /\r/g,
        At = /^(?:input|select|textarea|button|object)$/i,
        jt = /^(?:a|area)$/i,
        Lt = /^(?:checked|selected|autofocus|autoplay|async|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped)$/i,
        Mt = /^(?:checked|selected)$/i,
        Rt = lt.support.getSetAttribute,
        It = lt.support.input;
    if (lt.fn.extend({
            attr: function(e, t) {
                return lt.access(this, lt.attr, e, t, arguments.length > 1)
            },
            removeAttr: function(e) {
                return this.each(function() {
                    lt.removeAttr(this, e)
                })
            },
            prop: function(e, t) {
                return lt.access(this, lt.prop, e, t, arguments.length > 1)
            },
            removeProp: function(e) {
                return e = lt.propFix[e] || e, this.each(function() {
                    try {
                        this[e] = t, delete this[e]
                    } catch (n) {}
                })
            },
            addClass: function(e) {
                var t, n, i, r, o, s = 0,
                    a = this.length,
                    l = "string" == typeof e && e;
                if (lt.isFunction(e)) return this.each(function(t) {
                    lt(this).addClass(e.call(this, t, this.className))
                });
                if (l)
                    for (t = (e || "").match(ft) || []; a > s; s++)
                        if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(St, " ") : " ")) {
                            for (o = 0; r = t[o++];)
                                if (i.indexOf(" " + r + " ") < 0) i += r + " ";
                            n.className = lt.trim(i)
                        }
                return this
            },
            removeClass: function(e) {
                var t, n, i, r, o, s = 0,
                    a = this.length,
                    l = 0 === arguments.length || "string" == typeof e && e;
                if (lt.isFunction(e)) return this.each(function(t) {
                    lt(this).removeClass(e.call(this, t, this.className))
                });
                if (l)
                    for (t = (e || "").match(ft) || []; a > s; s++)
                        if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(St, " ") : "")) {
                            for (o = 0; r = t[o++];)
                                for (; i.indexOf(" " + r + " ") >= 0;) i = i.replace(" " + r + " ", " ");
                            n.className = e ? lt.trim(i) : ""
                        }
                return this
            },
            toggleClass: function(e, t) {
                var n = typeof e,
                    i = "boolean" == typeof t;
                if (lt.isFunction(e)) return this.each(function(n) {
                    lt(this).toggleClass(e.call(this, n, this.className, t), t)
                });
                else return this.each(function() {
                    if ("string" === n)
                        for (var r, o = 0, s = lt(this), a = t, l = e.match(ft) || []; r = l[o++];) a = i ? a : !s.hasClass(r), s[a ? "addClass" : "removeClass"](r);
                    else if (n === G || "boolean" === n) {
                        if (this.className) lt._data(this, "__className__", this.className);
                        this.className = this.className || e === !1 ? "" : lt._data(this, "__className__") || ""
                    }
                })
            },
            hasClass: function(e) {
                for (var t = " " + e + " ", n = 0, i = this.length; i > n; n++)
                    if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(St, " ").indexOf(t) >= 0) return !0;
                return !1
            },
            val: function(e) {
                var n, i, r, o = this[0];
                if (arguments.length) return r = lt.isFunction(e), this.each(function(n) {
                    var o, s = lt(this);
                    if (1 === this.nodeType) {
                        if (r) o = e.call(this, n, s.val());
                        else o = e;
                        if (null == o) o = "";
                        else if ("number" == typeof o) o += "";
                        else if (lt.isArray(o)) o = lt.map(o, function(e) {
                            return null == e ? "" : e + ""
                        });
                        if (i = lt.valHooks[this.type] || lt.valHooks[this.nodeName.toLowerCase()], !(i && "set" in i && i.set(this, o, "value") !== t)) this.value = o
                    }
                });
                else if (o)
                    if (i = lt.valHooks[o.type] || lt.valHooks[o.nodeName.toLowerCase()], i && "get" in i && (n = i.get(o, "value")) !== t) return n;
                    else return n = o.value, "string" == typeof n ? n.replace(Dt, "") : null == n ? "" : n
            }
        }), lt.extend({
            valHooks: {
                option: {
                    get: function(e) {
                        var t = e.attributes.value;
                        return !t || t.specified ? e.value : e.text
                    }
                },
                select: {
                    get: function(e) {
                        for (var t, n, i = e.options, r = e.selectedIndex, o = "select-one" === e.type || 0 > r, s = o ? null : [], a = o ? r + 1 : i.length, l = 0 > r ? a : o ? r : 0; a > l; l++)
                            if (n = i[l], !(!n.selected && l !== r || (lt.support.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && lt.nodeName(n.parentNode, "optgroup"))) {
                                if (t = lt(n).val(), o) return t;
                                s.push(t)
                            }
                        return s
                    },
                    set: function(e, t) {
                        var n = lt.makeArray(t);
                        if (lt(e).find("option").each(function() {
                                this.selected = lt.inArray(lt(this).val(), n) >= 0
                            }), !n.length) e.selectedIndex = -1;
                        return n
                    }
                }
            },
            attr: function(e, n, i) {
                var r, o, s, a = e.nodeType;
                if (e && 3 !== a && 8 !== a && 2 !== a) {
                    if (typeof e.getAttribute === G) return lt.prop(e, n, i);
                    if (o = 1 !== a || !lt.isXMLDoc(e)) n = n.toLowerCase(), r = lt.attrHooks[n] || (Lt.test(n) ? kt : _t);
                    if (i !== t)
                        if (null === i) lt.removeAttr(e, n);
                        else if (r && o && "set" in r && (s = r.set(e, i, n)) !== t) return s;
                    else return e.setAttribute(n, i + ""), i;
                    else if (r && o && "get" in r && null !== (s = r.get(e, n))) return s;
                    else {
                        if (typeof e.getAttribute !== G) s = e.getAttribute(n);
                        return null == s ? t : s
                    }
                }
            },
            removeAttr: function(e, t) {
                var n, i, r = 0,
                    o = t && t.match(ft);
                if (o && 1 === e.nodeType)
                    for (; n = o[r++];) {
                        if (i = lt.propFix[n] || n, Lt.test(n))
                            if (!Rt && Mt.test(n)) e[lt.camelCase("default-" + n)] = e[i] = !1;
                            else e[i] = !1;
                        else lt.attr(e, n, "");
                        e.removeAttribute(Rt ? n : i)
                    }
            },
            attrHooks: {
                type: {
                    set: function(e, t) {
                        if (!lt.support.radioValue && "radio" === t && lt.nodeName(e, "input")) {
                            var n = e.value;
                            if (e.setAttribute("type", t), n) e.value = n;
                            return t
                        }
                    }
                }
            },
            propFix: {
                tabindex: "tabIndex",
                readonly: "readOnly",
                "for": "htmlFor",
                "class": "className",
                maxlength: "maxLength",
                cellspacing: "cellSpacing",
                cellpadding: "cellPadding",
                rowspan: "rowSpan",
                colspan: "colSpan",
                usemap: "useMap",
                frameborder: "frameBorder",
                contenteditable: "contentEditable"
            },
            prop: function(e, n, i) {
                var r, o, s, a = e.nodeType;
                if (e && 3 !== a && 8 !== a && 2 !== a) {
                    if (s = 1 !== a || !lt.isXMLDoc(e)) n = lt.propFix[n] || n, o = lt.propHooks[n];
                    if (i !== t)
                        if (o && "set" in o && (r = o.set(e, i, n)) !== t) return r;
                        else return e[n] = i;
                    else if (o && "get" in o && null !== (r = o.get(e, n))) return r;
                    else return e[n]
                }
            },
            propHooks: {
                tabIndex: {
                    get: function(e) {
                        var n = e.getAttributeNode("tabindex");
                        return n && n.specified ? parseInt(n.value, 10) : At.test(e.nodeName) || jt.test(e.nodeName) && e.href ? 0 : t
                    }
                }
            }
        }), kt = {
            get: function(e, n) {
                var i = lt.prop(e, n),
                    r = "boolean" == typeof i && e.getAttribute(n),
                    o = "boolean" == typeof i ? It && Rt ? null != r : Mt.test(n) ? e[lt.camelCase("default-" + n)] : !!r : e.getAttributeNode(n);
                return o && o.value !== !1 ? n.toLowerCase() : t
            },
            set: function(e, t, n) {
                if (t === !1) lt.removeAttr(e, n);
                else if (It && Rt || !Mt.test(n)) e.setAttribute(!Rt && lt.propFix[n] || n, n);
                else e[lt.camelCase("default-" + n)] = e[n] = !0;
                return n
            }
        }, !It || !Rt) lt.attrHooks.value = {
        get: function(e, n) {
            var i = e.getAttributeNode(n);
            return lt.nodeName(e, "input") ? e.defaultValue : i && i.specified ? i.value : t
        },
        set: function(e, t, n) {
            if (lt.nodeName(e, "input")) e.defaultValue = t;
            else return _t && _t.set(e, t, n)
        }
    };
    if (!Rt) _t = lt.valHooks.button = {
        get: function(e, n) {
            var i = e.getAttributeNode(n);
            return i && ("id" === n || "name" === n || "coords" === n ? "" !== i.value : i.specified) ? i.value : t
        },
        set: function(e, n, i) {
            var r = e.getAttributeNode(i);
            if (!r) e.setAttributeNode(r = e.ownerDocument.createAttribute(i));
            return r.value = n += "", "value" === i || n === e.getAttribute(i) ? n : t
        }
    }, lt.attrHooks.contenteditable = {
        get: _t.get,
        set: function(e, t, n) {
            _t.set(e, "" === t ? !1 : t, n)
        }
    }, lt.each(["width", "height"], function(e, t) {
        lt.attrHooks[t] = lt.extend(lt.attrHooks[t], {
            set: function(e, n) {
                if ("" === n) return e.setAttribute(t, "auto"), n;
                else return void 0
            }
        })
    });
    if (!lt.support.hrefNormalized) lt.each(["href", "src", "width", "height"], function(e, n) {
        lt.attrHooks[n] = lt.extend(lt.attrHooks[n], {
            get: function(e) {
                var i = e.getAttribute(n, 2);
                return null == i ? t : i
            }
        })
    }), lt.each(["href", "src"], function(e, t) {
        lt.propHooks[t] = {
            get: function(e) {
                return e.getAttribute(t, 4)
            }
        }
    });
    if (!lt.support.style) lt.attrHooks.style = {
        get: function(e) {
            return e.style.cssText || t
        },
        set: function(e, t) {
            return e.style.cssText = t + ""
        }
    };
    if (!lt.support.optSelected) lt.propHooks.selected = lt.extend(lt.propHooks.selected, {
        get: function(e) {
            var t = e.parentNode;
            if (t)
                if (t.selectedIndex, t.parentNode) t.parentNode.selectedIndex;
            return null
        }
    });
    if (!lt.support.enctype) lt.propFix.enctype = "encoding";
    if (!lt.support.checkOn) lt.each(["radio", "checkbox"], function() {
        lt.valHooks[this] = {
            get: function(e) {
                return null === e.getAttribute("value") ? "on" : e.value
            }
        }
    });
    lt.each(["radio", "checkbox"], function() {
        lt.valHooks[this] = lt.extend(lt.valHooks[this], {
            set: function(e, t) {
                if (lt.isArray(t)) return e.checked = lt.inArray(lt(e).val(), t) >= 0;
                else return void 0
            }
        })
    });
    var Ot = /^(?:input|select|textarea)$/i,
        Ht = /^key/,
        qt = /^(?:mouse|contextmenu)|click/,
        Pt = /^(?:focusinfocus|focusoutblur)$/,
        Ft = /^([^.]*)(?:\.(.+)|)$/;
    if (lt.event = {
            global: {},
            add: function(e, n, i, r, o) {
                var s, a, l, u, f, c, p, d, h, m, g, y = lt._data(e);
                if (y) {
                    if (i.handler) u = i, i = u.handler, o = u.selector;
                    if (!i.guid) i.guid = lt.guid++;
                    if (!(a = y.events)) a = y.events = {};
                    if (!(c = y.handle)) c = y.handle = function(e) {
                        return typeof lt !== G && (!e || lt.event.triggered !== e.type) ? lt.event.dispatch.apply(c.elem, arguments) : t
                    }, c.elem = e;
                    for (n = (n || "").match(ft) || [""], l = n.length; l--;) {
                        if (s = Ft.exec(n[l]) || [], h = g = s[1], m = (s[2] || "").split(".").sort(), f = lt.event.special[h] || {}, h = (o ? f.delegateType : f.bindType) || h, f = lt.event.special[h] || {}, p = lt.extend({
                                type: h,
                                origType: g,
                                data: r,
                                handler: i,
                                guid: i.guid,
                                selector: o,
                                needsContext: o && lt.expr.match.needsContext.test(o),
                                namespace: m.join(".")
                            }, u), !(d = a[h]))
                            if (d = a[h] = [], d.delegateCount = 0, !f.setup || f.setup.call(e, r, m, c) === !1)
                                if (e.addEventListener) e.addEventListener(h, c, !1);
                                else if (e.attachEvent) e.attachEvent("on" + h, c);
                        if (f.add)
                            if (f.add.call(e, p), !p.handler.guid) p.handler.guid = i.guid;
                        if (o) d.splice(d.delegateCount++, 0, p);
                        else d.push(p);
                        lt.event.global[h] = !0
                    }
                    e = null
                }
            },
            remove: function(e, t, n, i, r) {
                var o, s, a, l, u, f, c, p, d, h, m, g = lt.hasData(e) && lt._data(e);
                if (g && (f = g.events)) {
                    for (t = (t || "").match(ft) || [""], u = t.length; u--;)
                        if (a = Ft.exec(t[u]) || [], d = m = a[1], h = (a[2] || "").split(".").sort(), d) {
                            for (c = lt.event.special[d] || {}, d = (i ? c.delegateType : c.bindType) || d, p = f[d] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = o = p.length; o--;)
                                if (s = p[o], !(!r && m !== s.origType || n && n.guid !== s.guid || a && !a.test(s.namespace) || i && i !== s.selector && ("**" !== i || !s.selector))) {
                                    if (p.splice(o, 1), s.selector) p.delegateCount--;
                                    if (c.remove) c.remove.call(e, s)
                                }
                            if (l && !p.length) {
                                if (!c.teardown || c.teardown.call(e, h, g.handle) === !1) lt.removeEvent(e, d, g.handle);
                                delete f[d]
                            }
                        } else
                            for (d in f) lt.event.remove(e, d + t[u], n, i, !0);
                    if (lt.isEmptyObject(f)) delete g.handle, lt._removeData(e, "events")
                }
            },
            trigger: function(n, i, r, o) {
                var s, a, l, u, f, c, p, d = [r || X],
                    h = st.call(n, "type") ? n.type : n,
                    m = st.call(n, "namespace") ? n.namespace.split(".") : [];
                if (l = c = r = r || X, 3 !== r.nodeType && 8 !== r.nodeType)
                    if (!Pt.test(h + lt.event.triggered)) {
                        if (h.indexOf(".") >= 0) m = h.split("."), h = m.shift(), m.sort();
                        if (a = h.indexOf(":") < 0 && "on" + h, n = n[lt.expando] ? n : new lt.Event(h, "object" == typeof n && n), n.isTrigger = !0, n.namespace = m.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + m.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, n.result = t, !n.target) n.target = r;
                        if (i = null == i ? [n] : lt.makeArray(i, [n]), f = lt.event.special[h] || {}, o || !f.trigger || f.trigger.apply(r, i) !== !1) {
                            if (!o && !f.noBubble && !lt.isWindow(r)) {
                                if (u = f.delegateType || h, !Pt.test(u + h)) l = l.parentNode;
                                for (; l; l = l.parentNode) d.push(l), c = l;
                                if (c === (r.ownerDocument || X)) d.push(c.defaultView || c.parentWindow || e)
                            }
                            for (p = 0;
                                (l = d[p++]) && !n.isPropagationStopped();) {
                                if (n.type = p > 1 ? u : f.bindType || h, s = (lt._data(l, "events") || {})[n.type] && lt._data(l, "handle")) s.apply(l, i);
                                if (s = a && l[a], s && lt.acceptData(l) && s.apply && s.apply(l, i) === !1) n.preventDefault()
                            }
                            if (n.type = h, !o && !n.isDefaultPrevented())
                                if (!(f._default && f._default.apply(r.ownerDocument, i) !== !1 || "click" === h && lt.nodeName(r, "a") || !lt.acceptData(r)))
                                    if (a && r[h] && !lt.isWindow(r)) {
                                        if (c = r[a]) r[a] = null;
                                        lt.event.triggered = h;
                                        try {
                                            r[h]()
                                        } catch (g) {}
                                        if (lt.event.triggered = t, c) r[a] = c
                                    }
                            return n.result
                        }
                    }
            },
            dispatch: function(e) {
                e = lt.event.fix(e);
                var n, i, r, o, s, a = [],
                    l = it.call(arguments),
                    u = (lt._data(this, "events") || {})[e.type] || [],
                    f = lt.event.special[e.type] || {};
                if (l[0] = e, e.delegateTarget = this, !f.preDispatch || f.preDispatch.call(this, e) !== !1) {
                    for (a = lt.event.handlers.call(this, e, u), n = 0;
                        (o = a[n++]) && !e.isPropagationStopped();)
                        for (e.currentTarget = o.elem, s = 0;
                            (r = o.handlers[s++]) && !e.isImmediatePropagationStopped();)
                            if (!e.namespace_re || e.namespace_re.test(r.namespace))
                                if (e.handleObj = r, e.data = r.data, i = ((lt.event.special[r.origType] || {}).handle || r.handler).apply(o.elem, l), i !== t)
                                    if ((e.result = i) === !1) e.preventDefault(), e.stopPropagation();
                    if (f.postDispatch) f.postDispatch.call(this, e);
                    return e.result
                }
            },
            handlers: function(e, n) {
                var i, r, o, s, a = [],
                    l = n.delegateCount,
                    u = e.target;
                if (l && u.nodeType && (!e.button || "click" !== e.type))
                    for (; u != this; u = u.parentNode || this)
                        if (1 === u.nodeType && (u.disabled !== !0 || "click" !== e.type)) {
                            for (o = [], s = 0; l > s; s++) {
                                if (r = n[s], i = r.selector + " ", o[i] === t) o[i] = r.needsContext ? lt(i, this).index(u) >= 0 : lt.find(i, this, null, [u]).length;
                                if (o[i]) o.push(r)
                            }
                            if (o.length) a.push({
                                elem: u,
                                handlers: o
                            })
                        }
                if (l < n.length) a.push({
                    elem: this,
                    handlers: n.slice(l)
                });
                return a
            },
            fix: function(e) {
                if (e[lt.expando]) return e;
                var t, n, i, r = e.type,
                    o = e,
                    s = this.fixHooks[r];
                if (!s) this.fixHooks[r] = s = qt.test(r) ? this.mouseHooks : Ht.test(r) ? this.keyHooks : {};
                for (i = s.props ? this.props.concat(s.props) : this.props, e = new lt.Event(o), t = i.length; t--;) n = i[t], e[n] = o[n];
                if (!e.target) e.target = o.srcElement || X;
                if (3 === e.target.nodeType) e.target = e.target.parentNode;
                return e.metaKey = !!e.metaKey, s.filter ? s.filter(e, o) : e
            },
            props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
            fixHooks: {},
            keyHooks: {
                props: "char charCode key keyCode".split(" "),
                filter: function(e, t) {
                    if (null == e.which) e.which = null != t.charCode ? t.charCode : t.keyCode;
                    return e
                }
            },
            mouseHooks: {
                props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
                filter: function(e, n) {
                    var i, r, o, s = n.button,
                        a = n.fromElement;
                    if (null == e.pageX && null != n.clientX) r = e.target.ownerDocument || X, o = r.documentElement, i = r.body, e.pageX = n.clientX + (o && o.scrollLeft || i && i.scrollLeft || 0) - (o && o.clientLeft || i && i.clientLeft || 0), e.pageY = n.clientY + (o && o.scrollTop || i && i.scrollTop || 0) - (o && o.clientTop || i && i.clientTop || 0);
                    if (!e.relatedTarget && a) e.relatedTarget = a === e.target ? n.toElement : a;
                    if (!e.which && s !== t) e.which = 1 & s ? 1 : 2 & s ? 3 : 4 & s ? 2 : 0;
                    return e
                }
            },
            special: {
                load: {
                    noBubble: !0
                },
                click: {
                    trigger: function() {
                        if (lt.nodeName(this, "input") && "checkbox" === this.type && this.click) return this.click(), !1;
                        else return void 0
                    }
                },
                focus: {
                    trigger: function() {
                        if (this !== X.activeElement && this.focus) try {
                            return this.focus(), !1
                        } catch (e) {}
                    },
                    delegateType: "focusin"
                },
                blur: {
                    trigger: function() {
                        if (this === X.activeElement && this.blur) return this.blur(), !1;
                        else return void 0
                    },
                    delegateType: "focusout"
                },
                beforeunload: {
                    postDispatch: function(e) {
                        if (e.result !== t) e.originalEvent.returnValue = e.result
                    }
                }
            },
            simulate: function(e, t, n, i) {
                var r = lt.extend(new lt.Event, n, {
                    type: e,
                    isSimulated: !0,
                    originalEvent: {}
                });
                if (i) lt.event.trigger(r, null, t);
                else lt.event.dispatch.call(t, r);
                if (r.isDefaultPrevented()) n.preventDefault()
            }
        }, lt.removeEvent = X.removeEventListener ? function(e, t, n) {
            if (e.removeEventListener) e.removeEventListener(t, n, !1)
        } : function(e, t, n) {
            var i = "on" + t;
            if (e.detachEvent) {
                if (typeof e[i] === G) e[i] = null;
                e.detachEvent(i, n)
            }
        }, lt.Event = function(e, t) {
            if (!(this instanceof lt.Event)) return new lt.Event(e, t);
            if (e && e.type) this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || e.returnValue === !1 || e.getPreventDefault && e.getPreventDefault() ? l : u;
            else this.type = e;
            if (t) lt.extend(this, t);
            this.timeStamp = e && e.timeStamp || lt.now(), this[lt.expando] = !0
        }, lt.Event.prototype = {
            isDefaultPrevented: u,
            isPropagationStopped: u,
            isImmediatePropagationStopped: u,
            preventDefault: function() {
                var e = this.originalEvent;
                if (this.isDefaultPrevented = l, e)
                    if (e.preventDefault) e.preventDefault();
                    else e.returnValue = !1
            },
            stopPropagation: function() {
                var e = this.originalEvent;
                if (this.isPropagationStopped = l, e) {
                    if (e.stopPropagation) e.stopPropagation();
                    e.cancelBubble = !0
                }
            },
            stopImmediatePropagation: function() {
                this.isImmediatePropagationStopped = l, this.stopPropagation()
            }
        }, lt.each({
            mouseenter: "mouseover",
            mouseleave: "mouseout"
        }, function(e, t) {
            lt.event.special[e] = {
                delegateType: t,
                bindType: t,
                handle: function(e) {
                    var n, i = this,
                        r = e.relatedTarget,
                        o = e.handleObj;
                    if (!r || r !== i && !lt.contains(i, r)) e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t;
                    return n
                }
            }
        }), !lt.support.submitBubbles) lt.event.special.submit = {
        setup: function() {
            if (lt.nodeName(this, "form")) return !1;
            else return void lt.event.add(this, "click._submit keypress._submit", function(e) {
                var n = e.target,
                    i = lt.nodeName(n, "input") || lt.nodeName(n, "button") ? n.form : t;
                if (i && !lt._data(i, "submitBubbles")) lt.event.add(i, "submit._submit", function(e) {
                    e._submit_bubble = !0
                }), lt._data(i, "submitBubbles", !0)
            })
        },
        postDispatch: function(e) {
            if (e._submit_bubble)
                if (delete e._submit_bubble, this.parentNode && !e.isTrigger) lt.event.simulate("submit", this.parentNode, e, !0)
        },
        teardown: function() {
            if (lt.nodeName(this, "form")) return !1;
            else return void lt.event.remove(this, "._submit")
        }
    };
    if (!lt.support.changeBubbles) lt.event.special.change = {
        setup: function() {
            if (Ot.test(this.nodeName)) {
                if ("checkbox" === this.type || "radio" === this.type) lt.event.add(this, "propertychange._change", function(e) {
                    if ("checked" === e.originalEvent.propertyName) this._just_changed = !0
                }), lt.event.add(this, "click._change", function(e) {
                    if (this._just_changed && !e.isTrigger) this._just_changed = !1;
                    lt.event.simulate("change", this, e, !0)
                });
                return !1
            }
            lt.event.add(this, "beforeactivate._change", function(e) {
                var t = e.target;
                if (Ot.test(t.nodeName) && !lt._data(t, "changeBubbles")) lt.event.add(t, "change._change", function(e) {
                    if (this.parentNode && !e.isSimulated && !e.isTrigger) lt.event.simulate("change", this.parentNode, e, !0)
                }), lt._data(t, "changeBubbles", !0)
            })
        },
        handle: function(e) {
            var t = e.target;
            if (this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type) return e.handleObj.handler.apply(this, arguments);
            else return void 0
        },
        teardown: function() {
            return lt.event.remove(this, "._change"), !Ot.test(this.nodeName)
        }
    };
    if (!lt.support.focusinBubbles) lt.each({
        focus: "focusin",
        blur: "focusout"
    }, function(e, t) {
        var n = 0,
            i = function(e) {
                lt.event.simulate(t, e.target, lt.event.fix(e), !0)
            };
        lt.event.special[t] = {
            setup: function() {
                if (0 === n++) X.addEventListener(e, i, !0)
            },
            teardown: function() {
                if (0 === --n) X.removeEventListener(e, i, !0)
            }
        }
    });
    lt.fn.extend({
            on: function(e, n, i, r, o) {
                var s, a;
                if ("object" == typeof e) {
                    if ("string" != typeof n) i = i || n, n = t;
                    for (s in e) this.on(s, n, i, e[s], o);
                    return this
                }
                if (null == i && null == r) r = n, i = n = t;
                else if (null == r)
                    if ("string" == typeof n) r = i, i = t;
                    else r = i, i = n, n = t;
                if (r === !1) r = u;
                else if (!r) return this;
                if (1 === o) a = r, r = function(e) {
                    return lt().off(e), a.apply(this, arguments)
                }, r.guid = a.guid || (a.guid = lt.guid++);
                return this.each(function() {
                    lt.event.add(this, e, r, i, n)
                })
            },
            one: function(e, t, n, i) {
                return this.on(e, t, n, i, 1)
            },
            off: function(e, n, i) {
                var r, o;
                if (e && e.preventDefault && e.handleObj) return r = e.handleObj, lt(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this;
                if ("object" == typeof e) {
                    for (o in e) this.off(o, n, e[o]);
                    return this
                }
                if (n === !1 || "function" == typeof n) i = n, n = t;
                if (i === !1) i = u;
                return this.each(function() {
                    lt.event.remove(this, e, i, n)
                })
            },
            bind: function(e, t, n) {
                return this.on(e, null, t, n)
            },
            unbind: function(e, t) {
                return this.off(e, null, t)
            },
            delegate: function(e, t, n, i) {
                return this.on(t, e, n, i)
            },
            undelegate: function(e, t, n) {
                return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
            },
            trigger: function(e, t) {
                return this.each(function() {
                    lt.event.trigger(e, t, this)
                })
            },
            triggerHandler: function(e, t) {
                var n = this[0];
                if (n) return lt.event.trigger(e, t, n, !0);
                else return void 0
            }
        }),
        function(e, t) {
            function n(e) {
                return ht.test(e + "")
            }

            function i() {
                var e, t = [];
                return e = function(n, i) {
                    if (t.push(n += " ") > C.cacheLength) delete e[t.shift()];
                    return e[n] = i
                }
            }

            function r(e) {
                return e[P] = !0, e
            }

            function o(e) {
                var t = j.createElement("div");
                try {
                    return e(t)
                } catch (n) {
                    return !1
                } finally {
                    t = null
                }
            }

            function s(e, t, n, i) {
                var r, o, s, a, l, u, f, d, h, m;
                if ((t ? t.ownerDocument || t : F) !== j) A(t);
                if (t = t || j, n = n || [], !e || "string" != typeof e) return n;
                if (1 !== (a = t.nodeType) && 9 !== a) return [];
                if (!M && !i) {
                    if (r = mt.exec(e))
                        if (s = r[1]) {
                            if (9 === a)
                                if (o = t.getElementById(s), o && o.parentNode) {
                                    if (o.id === s) return n.push(o), n
                                } else return n;
                            else if (t.ownerDocument && (o = t.ownerDocument.getElementById(s)) && H(t, o) && o.id === s) return n.push(o), n
                        } else if (r[2]) return J.apply(n, Q.call(t.getElementsByTagName(e), 0)), n;
                    else if ((s = r[3]) && $.getByClassName && t.getElementsByClassName) return J.apply(n, Q.call(t.getElementsByClassName(s), 0)), n;
                    if ($.qsa && !R.test(e)) {
                        if (f = !0, d = P, h = t, m = 9 === a && e, 1 === a && "object" !== t.nodeName.toLowerCase()) {
                            if (u = c(e), f = t.getAttribute("id")) d = f.replace(vt, "\\$&");
                            else t.setAttribute("id", d);
                            for (d = "[id='" + d + "'] ", l = u.length; l--;) u[l] = d + p(u[l]);
                            h = dt.test(e) && t.parentNode || t, m = u.join(",")
                        }
                        if (m) try {
                            return J.apply(n, Q.call(h.querySelectorAll(m), 0)), n
                        } catch (g) {} finally {
                            if (!f) t.removeAttribute("id")
                        }
                    }
                }
                return x(e.replace(st, "$1"), t, n, i)
            }

            function a(e, t) {
                var n = t && e,
                    i = n && (~t.sourceIndex || X) - (~e.sourceIndex || X);
                if (i) return i;
                if (n)
                    for (; n = n.nextSibling;)
                        if (n === t) return -1;
                return e ? 1 : -1
            }

            function l(e) {
                return function(t) {
                    var n = t.nodeName.toLowerCase();
                    return "input" === n && t.type === e
                }
            }

            function u(e) {
                return function(t) {
                    var n = t.nodeName.toLowerCase();
                    return ("input" === n || "button" === n) && t.type === e
                }
            }

            function f(e) {
                return r(function(t) {
                    return t = +t, r(function(n, i) {
                        for (var r, o = e([], n.length, t), s = o.length; s--;)
                            if (n[r = o[s]]) n[r] = !(i[r] = n[r])
                    })
                })
            }

            function c(e, t) {
                var n, i, r, o, a, l, u, f = Y[e + " "];
                if (f) return t ? 0 : f.slice(0);
                for (a = e, l = [], u = C.preFilter; a;) {
                    if (!n || (i = at.exec(a))) {
                        if (i) a = a.slice(i[0].length) || a;
                        l.push(r = [])
                    }
                    if (n = !1, i = ut.exec(a)) n = i.shift(), r.push({
                        value: n,
                        type: i[0].replace(st, " ")
                    }), a = a.slice(n.length);
                    for (o in C.filter)
                        if ((i = pt[o].exec(a)) && (!u[o] || (i = u[o](i)))) n = i.shift(), r.push({
                            value: n,
                            type: o,
                            matches: i
                        }), a = a.slice(n.length);
                    if (!n) break
                }
                return t ? a.length : a ? s.error(e) : Y(e, l).slice(0)
            }

            function p(e) {
                for (var t = 0, n = e.length, i = ""; n > t; t++) i += e[t].value;
                return i
            }

            function d(e, t, n) {
                var i = t.dir,
                    r = n && "parentNode" === i,
                    o = W++;
                return t.first ? function(t, n, o) {
                    for (; t = t[i];)
                        if (1 === t.nodeType || r) return e(t, n, o)
                } : function(t, n, s) {
                    var a, l, u, f = B + " " + o;
                    if (s) {
                        for (; t = t[i];)
                            if (1 === t.nodeType || r)
                                if (e(t, n, s)) return !0
                    } else
                        for (; t = t[i];)
                            if (1 === t.nodeType || r)
                                if (u = t[P] || (t[P] = {}), (l = u[i]) && l[0] === f) {
                                    if ((a = l[1]) === !0 || a === E) return a === !0
                                } else if (l = u[i] = [f], l[1] = e(t, n, s) || E, l[1] === !0) return !0
                }
            }

            function h(e) {
                return e.length > 1 ? function(t, n, i) {
                    for (var r = e.length; r--;)
                        if (!e[r](t, n, i)) return !1;
                    return !0
                } : e[0]
            }

            function m(e, t, n, i, r) {
                for (var o, s = [], a = 0, l = e.length, u = null != t; l > a; a++)
                    if (o = e[a])
                        if (!n || n(o, i, r))
                            if (s.push(o), u) t.push(a);
                return s
            }

            function g(e, t, n, i, o, s) {
                if (i && !i[P]) i = g(i);
                if (o && !o[P]) o = g(o, s);
                return r(function(r, s, a, l) {
                    var u, f, c, p = [],
                        d = [],
                        h = s.length,
                        g = r || b(t || "*", a.nodeType ? [a] : a, []),
                        y = e && (r || !t) ? m(g, p, e, a, l) : g,
                        v = n ? o || (r ? e : h || i) ? [] : s : y;
                    if (n) n(y, v, a, l);
                    if (i)
                        for (u = m(v, d), i(u, [], a, l), f = u.length; f--;)
                            if (c = u[f]) v[d[f]] = !(y[d[f]] = c);
                    if (r) {
                        if (o || e) {
                            if (o) {
                                for (u = [], f = v.length; f--;)
                                    if (c = v[f]) u.push(y[f] = c);
                                o(null, v = [], u, l)
                            }
                            for (f = v.length; f--;)
                                if ((c = v[f]) && (u = o ? Z.call(r, c) : p[f]) > -1) r[u] = !(s[u] = c)
                        }
                    } else if (v = m(v === s ? v.splice(h, v.length) : v), o) o(null, s, v, l);
                    else J.apply(s, v)
                })
            }

            function y(e) {
                for (var t, n, i, r = e.length, o = C.relative[e[0].type], s = o || C.relative[" "], a = o ? 1 : 0, l = d(function(e) {
                        return e === t
                    }, s, !0), u = d(function(e) {
                        return Z.call(t, e) > -1
                    }, s, !0), f = [function(e, n, i) {
                        return !o && (i || n !== D) || ((t = n).nodeType ? l(e, n, i) : u(e, n, i))
                    }]; r > a; a++)
                    if (n = C.relative[e[a].type]) f = [d(h(f), n)];
                    else {
                        if (n = C.filter[e[a].type].apply(null, e[a].matches), n[P]) {
                            for (i = ++a; r > i && !C.relative[e[i].type]; i++);
                            return g(a > 1 && h(f), a > 1 && p(e.slice(0, a - 1)).replace(st, "$1"), n, i > a && y(e.slice(a, i)), r > i && y(e = e.slice(i)), r > i && p(e))
                        }
                        f.push(n)
                    }
                return h(f)
            }

            function v(e, t) {
                var n = 0,
                    i = t.length > 0,
                    o = e.length > 0,
                    a = function(r, a, l, u, f) {
                        var c, p, d, h = [],
                            g = 0,
                            y = "0",
                            v = r && [],
                            b = null != f,
                            x = D,
                            w = r || o && C.find.TAG("*", f && a.parentNode || a),
                            T = B += null == x ? 1 : Math.random() || .1;
                        if (b) D = a !== j && a, E = n;
                        for (; null != (c = w[y]); y++) {
                            if (o && c) {
                                for (p = 0; d = e[p++];)
                                    if (d(c, a, l)) {
                                        u.push(c);
                                        break
                                    }
                                if (b) B = T, E = ++n
                            }
                            if (i) {
                                if (c = !d && c) g--;
                                if (r) v.push(c)
                            }
                        }
                        if (g += y, i && y !== g) {
                            for (p = 0; d = t[p++];) d(v, h, a, l);
                            if (r) {
                                if (g > 0)
                                    for (; y--;)
                                        if (!v[y] && !h[y]) h[y] = K.call(u);
                                h = m(h)
                            }
                            if (J.apply(u, h), b && !r && h.length > 0 && g + t.length > 1) s.uniqueSort(u)
                        }
                        if (b) B = T, D = x;
                        return v
                    };
                return i ? r(a) : a
            }

            function b(e, t, n) {
                for (var i = 0, r = t.length; r > i; i++) s(e, t[i], n);
                return n
            }

            function x(e, t, n, i) {
                var r, o, s, a, l, u = c(e);
                if (!i)
                    if (1 === u.length) {
                        if (o = u[0] = u[0].slice(0), o.length > 2 && "ID" === (s = o[0]).type && 9 === t.nodeType && !M && C.relative[o[1].type]) {
                            if (t = C.find.ID(s.matches[0].replace(xt, wt), t)[0], !t) return n;
                            e = e.slice(o.shift().value.length)
                        }
                        for (r = pt.needsContext.test(e) ? 0 : o.length; r-- && (s = o[r], !C.relative[a = s.type]);)
                            if (l = C.find[a])
                                if (i = l(s.matches[0].replace(xt, wt), dt.test(o[0].type) && t.parentNode || t)) {
                                    if (o.splice(r, 1), e = i.length && p(o), !e) return J.apply(n, Q.call(i, 0)), n;
                                    break
                                }
                    }
                return k(e, u)(i, t, M, n, dt.test(e)), n
            }

            function w() {}
            var T, E, C, N, _, k, S, D, A, j, L, M, R, I, O, H, q, P = "sizzle" + -new Date,
                F = e.document,
                $ = {},
                B = 0,
                W = 0,
                z = i(),
                Y = i(),
                U = i(),
                G = typeof t,
                X = 1 << 31,
                V = [],
                K = V.pop,
                J = V.push,
                Q = V.slice,
                Z = V.indexOf || function(e) {
                    for (var t = 0, n = this.length; n > t; t++)
                        if (this[t] === e) return t;
                    return -1
                },
                et = "[\\x20\\t\\r\\n\\f]",
                tt = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
                nt = tt.replace("w", "w#"),
                it = "([*^$|!~]?=)",
                rt = "\\[" + et + "*(" + tt + ")" + et + "*(?:" + it + et + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + nt + ")|)|)" + et + "*\\]",
                ot = ":(" + tt + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + rt.replace(3, 8) + ")*)|.*)\\)|)",
                st = new RegExp("^" + et + "+|((?:^|[^\\\\])(?:\\\\.)*)" + et + "+$", "g"),
                at = new RegExp("^" + et + "*," + et + "*"),
                ut = new RegExp("^" + et + "*([\\x20\\t\\r\\n\\f>+~])" + et + "*"),
                ft = new RegExp(ot),
                ct = new RegExp("^" + nt + "$"),
                pt = {
                    ID: new RegExp("^#(" + tt + ")"),
                    CLASS: new RegExp("^\\.(" + tt + ")"),
                    NAME: new RegExp("^\\[name=['\"]?(" + tt + ")['\"]?\\]"),
                    TAG: new RegExp("^(" + tt.replace("w", "w*") + ")"),
                    ATTR: new RegExp("^" + rt),
                    PSEUDO: new RegExp("^" + ot),
                    CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + et + "*(even|odd|(([+-]|)(\\d*)n|)" + et + "*(?:([+-]|)" + et + "*(\\d+)|))" + et + "*\\)|)", "i"),
                    needsContext: new RegExp("^" + et + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + et + "*((?:-\\d)?\\d*)" + et + "*\\)|)(?=[^-]|$)", "i")
                },
                dt = /[\x20\t\r\n\f]*[+~]/,
                ht = /^[^{]+\{\s*\[native code/,
                mt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                gt = /^(?:input|select|textarea|button)$/i,
                yt = /^h\d$/i,
                vt = /'|\\/g,
                bt = /\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,
                xt = /\\([\da-fA-F]{1,6}[\x20\t\r\n\f]?|.)/g,
                wt = function(e, t) {
                    var n = "0x" + t - 65536;
                    return n !== n ? t : 0 > n ? String.fromCharCode(n + 65536) : String.fromCharCode(n >> 10 | 55296, 1023 & n | 56320)
                };
            try {
                Q.call(F.documentElement.childNodes, 0)[0].nodeType
            } catch (Tt) {
                Q = function(e) {
                    for (var t, n = []; t = this[e++];) n.push(t);
                    return n
                }
            }
            _ = s.isXML = function(e) {
                var t = e && (e.ownerDocument || e).documentElement;
                return t ? "HTML" !== t.nodeName : !1
            }, A = s.setDocument = function(e) {
                var i = e ? e.ownerDocument || e : F;
                if (i === j || 9 !== i.nodeType || !i.documentElement) return j;
                if (j = i, L = i.documentElement, M = _(i), $.tagNameNoComments = o(function(e) {
                        return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
                    }), $.attributes = o(function(e) {
                        e.innerHTML = "<select></select>";
                        var t = typeof e.lastChild.getAttribute("multiple");
                        return "boolean" !== t && "string" !== t
                    }), $.getByClassName = o(function(e) {
                        if (e.innerHTML = "<div class='hidden e'></div><div class='hidden'></div>", !e.getElementsByClassName || !e.getElementsByClassName("e").length) return !1;
                        else return e.lastChild.className = "e", 2 === e.getElementsByClassName("e").length
                    }), $.getByName = o(function(e) {
                        e.id = P + 0, e.innerHTML = "<a name='" + P + "'></a><div name='" + P + "'></div>", L.insertBefore(e, L.firstChild);
                        var t = i.getElementsByName && i.getElementsByName(P).length === 2 + i.getElementsByName(P + 0).length;
                        return $.getIdNotName = !i.getElementById(P), L.removeChild(e), t
                    }), C.attrHandle = o(function(e) {
                        return e.innerHTML = "<a href='#'></a>", e.firstChild && typeof e.firstChild.getAttribute !== G && "#" === e.firstChild.getAttribute("href")
                    }) ? {} : {
                        href: function(e) {
                            return e.getAttribute("href", 2)
                        },
                        type: function(e) {
                            return e.getAttribute("type")
                        }
                    }, $.getIdNotName) C.find.ID = function(e, t) {
                    if (typeof t.getElementById !== G && !M) {
                        var n = t.getElementById(e);
                        return n && n.parentNode ? [n] : []
                    }
                }, C.filter.ID = function(e) {
                    var t = e.replace(xt, wt);
                    return function(e) {
                        return e.getAttribute("id") === t
                    }
                };
                else C.find.ID = function(e, n) {
                    if (typeof n.getElementById !== G && !M) {
                        var i = n.getElementById(e);
                        return i ? i.id === e || typeof i.getAttributeNode !== G && i.getAttributeNode("id").value === e ? [i] : t : []
                    }
                }, C.filter.ID = function(e) {
                    var t = e.replace(xt, wt);
                    return function(e) {
                        var n = typeof e.getAttributeNode !== G && e.getAttributeNode("id");
                        return n && n.value === t
                    }
                };
                if (C.find.TAG = $.tagNameNoComments ? function(e, t) {
                        if (typeof t.getElementsByTagName !== G) return t.getElementsByTagName(e);
                        else return void 0
                    } : function(e, t) {
                        var n, i = [],
                            r = 0,
                            o = t.getElementsByTagName(e);
                        if ("*" === e) {
                            for (; n = o[r++];)
                                if (1 === n.nodeType) i.push(n);
                            return i
                        }
                        return o
                    }, C.find.NAME = $.getByName && function(e, t) {
                        if (typeof t.getElementsByName !== G) return t.getElementsByName(name);
                        else return void 0
                    }, C.find.CLASS = $.getByClassName && function(e, t) {
                        if (typeof t.getElementsByClassName !== G && !M) return t.getElementsByClassName(e);
                        else return void 0
                    }, I = [], R = [":focus"], $.qsa = n(i.querySelectorAll)) o(function(e) {
                    if (e.innerHTML = "<select><option selected=''></option></select>", !e.querySelectorAll("[selected]").length) R.push("\\[" + et + "*(?:checked|disabled|ismap|multiple|readonly|selected|value)");
                    if (!e.querySelectorAll(":checked").length) R.push(":checked")
                }), o(function(e) {
                    if (e.innerHTML = "<input type='hidden' i=''/>", e.querySelectorAll("[i^='']").length) R.push("[*^$]=" + et + "*(?:\"\"|'')");
                    if (!e.querySelectorAll(":enabled").length) R.push(":enabled", ":disabled");
                    e.querySelectorAll("*,:x"), R.push(",.*:")
                });
                if ($.matchesSelector = n(O = L.matchesSelector || L.mozMatchesSelector || L.webkitMatchesSelector || L.oMatchesSelector || L.msMatchesSelector)) o(function(e) {
                    $.disconnectedMatch = O.call(e, "div"), O.call(e, "[s!='']:x"), I.push("!=", ot)
                });
                return R = new RegExp(R.join("|")), I = new RegExp(I.join("|")), H = n(L.contains) || L.compareDocumentPosition ? function(e, t) {
                    var n = 9 === e.nodeType ? e.documentElement : e,
                        i = t && t.parentNode;
                    return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
                } : function(e, t) {
                    if (t)
                        for (; t = t.parentNode;)
                            if (t === e) return !0;
                    return !1
                }, q = L.compareDocumentPosition ? function(e, t) {
                    var n;
                    if (e === t) return S = !0, 0;
                    if (n = t.compareDocumentPosition && e.compareDocumentPosition && e.compareDocumentPosition(t)) {
                        if (1 & n || e.parentNode && 11 === e.parentNode.nodeType) {
                            if (e === i || H(F, e)) return -1;
                            if (t === i || H(F, t)) return 1;
                            else return 0
                        }
                        return 4 & n ? -1 : 1
                    }
                    return e.compareDocumentPosition ? -1 : 1
                } : function(e, t) {
                    var n, r = 0,
                        o = e.parentNode,
                        s = t.parentNode,
                        l = [e],
                        u = [t];
                    if (e === t) return S = !0, 0;
                    else if (!o || !s) return e === i ? -1 : t === i ? 1 : o ? -1 : s ? 1 : 0;
                    else if (o === s) return a(e, t);
                    for (n = e; n = n.parentNode;) l.unshift(n);
                    for (n = t; n = n.parentNode;) u.unshift(n);
                    for (; l[r] === u[r];) r++;
                    return r ? a(l[r], u[r]) : l[r] === F ? -1 : u[r] === F ? 1 : 0
                }, S = !1, [0, 0].sort(q), $.detectDuplicates = S, j
            }, s.matches = function(e, t) {
                return s(e, null, null, t)
            }, s.matchesSelector = function(e, t) {
                if ((e.ownerDocument || e) !== j) A(e);
                if (t = t.replace(bt, "='$1']"), !(!$.matchesSelector || M || I && I.test(t) || R.test(t))) try {
                    var n = O.call(e, t);
                    if (n || $.disconnectedMatch || e.document && 11 !== e.document.nodeType) return n
                } catch (i) {}
                return s(t, j, null, [e]).length > 0
            }, s.contains = function(e, t) {
                if ((e.ownerDocument || e) !== j) A(e);
                return H(e, t)
            }, s.attr = function(e, t) {
                var n;
                if ((e.ownerDocument || e) !== j) A(e);
                if (!M) t = t.toLowerCase();
                if (n = C.attrHandle[t]) return n(e);
                if (M || $.attributes) return e.getAttribute(t);
                else return ((n = e.getAttributeNode(t)) || e.getAttribute(t)) && e[t] === !0 ? t : n && n.specified ? n.value : null
            }, s.error = function(e) {
                throw new Error("Syntax error, unrecognized expression: " + e)
            }, s.uniqueSort = function(e) {
                var t, n = [],
                    i = 1,
                    r = 0;
                if (S = !$.detectDuplicates, e.sort(q), S) {
                    for (; t = e[i]; i++)
                        if (t === e[i - 1]) r = n.push(i);
                    for (; r--;) e.splice(n[r], 1)
                }
                return e
            }, N = s.getText = function(e) {
                var t, n = "",
                    i = 0,
                    r = e.nodeType;
                if (!r)
                    for (; t = e[i]; i++) n += N(t);
                else if (1 === r || 9 === r || 11 === r)
                    if ("string" == typeof e.textContent) return e.textContent;
                    else
                        for (e = e.firstChild; e; e = e.nextSibling) n += N(e);
                else if (3 === r || 4 === r) return e.nodeValue;
                return n
            }, C = s.selectors = {
                cacheLength: 50,
                createPseudo: r,
                match: pt,
                find: {},
                relative: {
                    ">": {
                        dir: "parentNode",
                        first: !0
                    },
                    " ": {
                        dir: "parentNode"
                    },
                    "+": {
                        dir: "previousSibling",
                        first: !0
                    },
                    "~": {
                        dir: "previousSibling"
                    }
                },
                preFilter: {
                    ATTR: function(e) {
                        if (e[1] = e[1].replace(xt, wt), e[3] = (e[4] || e[5] || "").replace(xt, wt), "~=" === e[2]) e[3] = " " + e[3] + " ";
                        return e.slice(0, 4)
                    },
                    CHILD: function(e) {
                        if (e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3)) {
                            if (!e[3]) s.error(e[0]);
                            e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])
                        } else if (e[3]) s.error(e[0]);
                        return e
                    },
                    PSEUDO: function(e) {
                        var t, n = !e[5] && e[2];
                        if (pt.CHILD.test(e[0])) return null;
                        if (e[4]) e[2] = e[4];
                        else if (n && ft.test(n) && (t = c(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length)) e[0] = e[0].slice(0, t), e[2] = n.slice(0, t);
                        return e.slice(0, 3)
                    }
                },
                filter: {
                    TAG: function(e) {
                        if ("*" === e) return function() {
                            return !0
                        };
                        else return e = e.replace(xt, wt).toLowerCase(),
                            function(t) {
                                return t.nodeName && t.nodeName.toLowerCase() === e
                            }
                    },
                    CLASS: function(e) {
                        var t = z[e + " "];
                        return t || (t = new RegExp("(^|" + et + ")" + e + "(" + et + "|$)")) && z(e, function(e) {
                            return t.test(e.className || typeof e.getAttribute !== G && e.getAttribute("class") || "")
                        })
                    },
                    ATTR: function(e, t, n) {
                        return function(i) {
                            var r = s.attr(i, e);
                            if (null == r) return "!=" === t;
                            if (!t) return !0;
                            else return r += "", "=" === t ? r === n : "!=" === t ? r !== n : "^=" === t ? n && 0 === r.indexOf(n) : "*=" === t ? n && r.indexOf(n) > -1 : "$=" === t ? n && r.slice(-n.length) === n : "~=" === t ? (" " + r + " ").indexOf(n) > -1 : "|=" === t ? r === n || r.slice(0, n.length + 1) === n + "-" : !1
                        }
                    },
                    CHILD: function(e, t, n, i, r) {
                        var o = "nth" !== e.slice(0, 3),
                            s = "last" !== e.slice(-4),
                            a = "of-type" === t;
                        return 1 === i && 0 === r ? function(e) {
                            return !!e.parentNode
                        } : function(t, n, l) {
                            var u, f, c, p, d, h, m = o !== s ? "nextSibling" : "previousSibling",
                                g = t.parentNode,
                                y = a && t.nodeName.toLowerCase(),
                                v = !l && !a;
                            if (g) {
                                if (o) {
                                    for (; m;) {
                                        for (c = t; c = c[m];)
                                            if (a ? c.nodeName.toLowerCase() === y : 1 === c.nodeType) return !1;
                                        h = m = "only" === e && !h && "nextSibling"
                                    }
                                    return !0
                                }
                                if (h = [s ? g.firstChild : g.lastChild], s && v) {
                                    for (f = g[P] || (g[P] = {}), u = f[e] || [], d = u[0] === B && u[1], p = u[0] === B && u[2], c = d && g.childNodes[d]; c = ++d && c && c[m] || (p = d = 0) || h.pop();)
                                        if (1 === c.nodeType && ++p && c === t) {
                                            f[e] = [B, d, p];
                                            break
                                        }
                                } else if (v && (u = (t[P] || (t[P] = {}))[e]) && u[0] === B) p = u[1];
                                else
                                    for (; c = ++d && c && c[m] || (p = d = 0) || h.pop();)
                                        if ((a ? c.nodeName.toLowerCase() === y : 1 === c.nodeType) && ++p) {
                                            if (v)(c[P] || (c[P] = {}))[e] = [B, p];
                                            if (c === t) break
                                        } return p -= r, p === i || p % i === 0 && p / i >= 0
                            }
                        }
                    },
                    PSEUDO: function(e, t) {
                        var n, i = C.pseudos[e] || C.setFilters[e.toLowerCase()] || s.error("unsupported pseudo: " + e);
                        if (i[P]) return i(t);
                        if (i.length > 1) return n = [e, e, "", t], C.setFilters.hasOwnProperty(e.toLowerCase()) ? r(function(e, n) {
                            for (var r, o = i(e, t), s = o.length; s--;) r = Z.call(e, o[s]), e[r] = !(n[r] = o[s])
                        }) : function(e) {
                            return i(e, 0, n)
                        };
                        else return i
                    }
                },
                pseudos: {
                    not: r(function(e) {
                        var t = [],
                            n = [],
                            i = k(e.replace(st, "$1"));
                        return i[P] ? r(function(e, t, n, r) {
                            for (var o, s = i(e, null, r, []), a = e.length; a--;)
                                if (o = s[a]) e[a] = !(t[a] = o)
                        }) : function(e, r, o) {
                            return t[0] = e, i(t, null, o, n), !n.pop()
                        }
                    }),
                    has: r(function(e) {
                        return function(t) {
                            return s(e, t).length > 0
                        }
                    }),
                    contains: r(function(e) {
                        return function(t) {
                            return (t.textContent || t.innerText || N(t)).indexOf(e) > -1
                        }
                    }),
                    lang: r(function(e) {
                        if (!ct.test(e || "")) s.error("unsupported lang: " + e);
                        return e = e.replace(xt, wt).toLowerCase(),
                            function(t) {
                                var n;
                                do
                                    if (n = M ? t.getAttribute("xml:lang") || t.getAttribute("lang") : t.lang) return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-");
                                while ((t = t.parentNode) && 1 === t.nodeType);
                                return !1
                            }
                    }),
                    target: function(t) {
                        var n = e.location && e.location.hash;
                        return n && n.slice(1) === t.id
                    },
                    root: function(e) {
                        return e === L
                    },
                    focus: function(e) {
                        return e === j.activeElement && (!j.hasFocus || j.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                    },
                    enabled: function(e) {
                        return e.disabled === !1
                    },
                    disabled: function(e) {
                        return e.disabled === !0
                    },
                    checked: function(e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && !!e.checked || "option" === t && !!e.selected
                    },
                    selected: function(e) {
                        if (e.parentNode) e.parentNode.selectedIndex;
                        return e.selected === !0
                    },
                    empty: function(e) {
                        for (e = e.firstChild; e; e = e.nextSibling)
                            if (e.nodeName > "@" || 3 === e.nodeType || 4 === e.nodeType) return !1;
                        return !0
                    },
                    parent: function(e) {
                        return !C.pseudos.empty(e)
                    },
                    header: function(e) {
                        return yt.test(e.nodeName)
                    },
                    input: function(e) {
                        return gt.test(e.nodeName)
                    },
                    button: function(e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && "button" === e.type || "button" === t
                    },
                    text: function(e) {
                        var t;
                        return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || t.toLowerCase() === e.type)
                    },
                    first: f(function() {
                        return [0]
                    }),
                    last: f(function(e, t) {
                        return [t - 1]
                    }),
                    eq: f(function(e, t, n) {
                        return [0 > n ? n + t : n]
                    }),
                    even: f(function(e, t) {
                        for (var n = 0; t > n; n += 2) e.push(n);
                        return e
                    }),
                    odd: f(function(e, t) {
                        for (var n = 1; t > n; n += 2) e.push(n);
                        return e
                    }),
                    lt: f(function(e, t, n) {
                        for (var i = 0 > n ? n + t : n; --i >= 0;) e.push(i);
                        return e
                    }),
                    gt: f(function(e, t, n) {
                        for (var i = 0 > n ? n + t : n; ++i < t;) e.push(i);
                        return e
                    })
                }
            };
            for (T in {
                    radio: !0,
                    checkbox: !0,
                    file: !0,
                    password: !0,
                    image: !0
                }) C.pseudos[T] = l(T);
            for (T in {
                    submit: !0,
                    reset: !0
                }) C.pseudos[T] = u(T);
            k = s.compile = function(e, t) {
                var n, i = [],
                    r = [],
                    o = U[e + " "];
                if (!o) {
                    if (!t) t = c(e);
                    for (n = t.length; n--;)
                        if (o = y(t[n]), o[P]) i.push(o);
                        else r.push(o);
                    o = U(e, v(r, i))
                }
                return o
            }, C.pseudos.nth = C.pseudos.eq, C.filters = w.prototype = C.pseudos, C.setFilters = new w, A(), s.attr = lt.attr, lt.find = s, lt.expr = s.selectors, lt.expr[":"] = lt.expr.pseudos, lt.unique = s.uniqueSort, lt.text = s.getText, lt.isXMLDoc = s.isXML, lt.contains = s.contains
        }(e);
    var $t = /Until$/,
        Bt = /^(?:parents|prev(?:Until|All))/,
        Wt = /^.[^:#\[\.,]*$/,
        zt = lt.expr.match.needsContext,
        Yt = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };
    lt.fn.extend({
        find: function(e) {
            var t, n, i, r = this.length;
            if ("string" != typeof e) return i = this, this.pushStack(lt(e).filter(function() {
                for (t = 0; r > t; t++)
                    if (lt.contains(i[t], this)) return !0
            }));
            for (n = [], t = 0; r > t; t++) lt.find(e, this[t], n);
            return n = this.pushStack(r > 1 ? lt.unique(n) : n), n.selector = (this.selector ? this.selector + " " : "") + e, n
        },
        has: function(e) {
            var t, n = lt(e, this),
                i = n.length;
            return this.filter(function() {
                for (t = 0; i > t; t++)
                    if (lt.contains(this, n[t])) return !0
            })
        },
        not: function(e) {
            return this.pushStack(c(this, e, !1))
        },
        filter: function(e) {
            return this.pushStack(c(this, e, !0))
        },
        is: function(e) {
            return !!e && ("string" == typeof e ? zt.test(e) ? lt(e, this.context).index(this[0]) >= 0 : lt.filter(e, this).length > 0 : this.filter(e).length > 0)
        },
        closest: function(e, t) {
            for (var n, i = 0, r = this.length, o = [], s = zt.test(e) || "string" != typeof e ? lt(e, t || this.context) : 0; r > i; i++)
                for (n = this[i]; n && n.ownerDocument && n !== t && 11 !== n.nodeType;) {
                    if (s ? s.index(n) > -1 : lt.find.matchesSelector(n, e)) {
                        o.push(n);
                        break
                    }
                    n = n.parentNode
                }
            return this.pushStack(o.length > 1 ? lt.unique(o) : o)
        },
        index: function(e) {
            if (!e) return this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
            if ("string" == typeof e) return lt.inArray(this[0], lt(e));
            else return lt.inArray(e.jquery ? e[0] : e, this)
        },
        add: function(e, t) {
            var n = "string" == typeof e ? lt(e, t) : lt.makeArray(e && e.nodeType ? [e] : e),
                i = lt.merge(this.get(), n);
            return this.pushStack(lt.unique(i))
        },
        addBack: function(e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), lt.fn.andSelf = lt.fn.addBack, lt.each({
        parent: function(e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        },
        parents: function(e) {
            return lt.dir(e, "parentNode")
        },
        parentsUntil: function(e, t, n) {
            return lt.dir(e, "parentNode", n)
        },
        next: function(e) {
            return f(e, "nextSibling")
        },
        prev: function(e) {
            return f(e, "previousSibling")
        },
        nextAll: function(e) {
            return lt.dir(e, "nextSibling")
        },
        prevAll: function(e) {
            return lt.dir(e, "previousSibling")
        },
        nextUntil: function(e, t, n) {
            return lt.dir(e, "nextSibling", n)
        },
        prevUntil: function(e, t, n) {
            return lt.dir(e, "previousSibling", n)
        },
        siblings: function(e) {
            return lt.sibling((e.parentNode || {}).firstChild, e)
        },
        children: function(e) {
            return lt.sibling(e.firstChild)
        },
        contents: function(e) {
            return lt.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : lt.merge([], e.childNodes)
        }
    }, function(e, t) {
        lt.fn[e] = function(n, i) {
            var r = lt.map(this, t, n);
            if (!$t.test(e)) i = n;
            if (i && "string" == typeof i) r = lt.filter(i, r);
            if (r = this.length > 1 && !Yt[e] ? lt.unique(r) : r, this.length > 1 && Bt.test(e)) r = r.reverse();
            return this.pushStack(r)
        }
    }), lt.extend({
        filter: function(e, t, n) {
            if (n) e = ":not(" + e + ")";
            return 1 === t.length ? lt.find.matchesSelector(t[0], e) ? [t[0]] : [] : lt.find.matches(e, t)
        },
        dir: function(e, n, i) {
            for (var r = [], o = e[n]; o && 9 !== o.nodeType && (i === t || 1 !== o.nodeType || !lt(o).is(i));) {
                if (1 === o.nodeType) r.push(o);
                o = o[n]
            }
            return r
        },
        sibling: function(e, t) {
            for (var n = []; e; e = e.nextSibling)
                if (1 === e.nodeType && e !== t) n.push(e);
            return n
        }
    });
    var Ut = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        Gt = / jQuery\d+="(?:null|\d+)"/g,
        Xt = new RegExp("<(?:" + Ut + ")[\\s/>]", "i"),
        Vt = /^\s+/,
        Kt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        Jt = /<([\w:]+)/,
        Qt = /<tbody/i,
        Zt = /<|&#?\w+;/,
        en = /<(?:script|style|link)/i,
        tn = /^(?:checkbox|radio)$/i,
        nn = /checked\s*(?:[^=]|=\s*.checked.)/i,
        rn = /^$|\/(?:java|ecma)script/i,
        on = /^true\/(.*)/,
        sn = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
        an = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            area: [1, "<map>", "</map>"],
            param: [1, "<object>", "</object>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: lt.support.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
        },
        ln = p(X),
        un = ln.appendChild(X.createElement("div"));
    an.optgroup = an.option, an.tbody = an.tfoot = an.colgroup = an.caption = an.thead, an.th = an.td, lt.fn.extend({
        text: function(e) {
            return lt.access(this, function(e) {
                return e === t ? lt.text(this) : this.empty().append((this[0] && this[0].ownerDocument || X).createTextNode(e))
            }, null, e, arguments.length)
        },
        wrapAll: function(e) {
            if (lt.isFunction(e)) return this.each(function(t) {
                lt(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = lt(e, this[0].ownerDocument).eq(0).clone(!0);
                if (this[0].parentNode) t.insertBefore(this[0]);
                t.map(function() {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;) e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        },
        wrapInner: function(e) {
            if (lt.isFunction(e)) return this.each(function(t) {
                lt(this).wrapInner(e.call(this, t))
            });
            else return this.each(function() {
                var t = lt(this),
                    n = t.contents();
                if (n.length) n.wrapAll(e);
                else t.append(e)
            })
        },
        wrap: function(e) {
            var t = lt.isFunction(e);
            return this.each(function(n) {
                lt(this).wrapAll(t ? e.call(this, n) : e)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                if (!lt.nodeName(this, "body")) lt(this).replaceWith(this.childNodes)
            }).end()
        },
        append: function() {
            return this.domManip(arguments, !0, function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) this.appendChild(e)
            })
        },
        prepend: function() {
            return this.domManip(arguments, !0, function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) this.insertBefore(e, this.firstChild)
            })
        },
        before: function() {
            return this.domManip(arguments, !1, function(e) {
                if (this.parentNode) this.parentNode.insertBefore(e, this)
            })
        },
        after: function() {
            return this.domManip(arguments, !1, function(e) {
                if (this.parentNode) this.parentNode.insertBefore(e, this.nextSibling)
            })
        },
        remove: function(e, t) {
            for (var n, i = 0; null != (n = this[i]); i++)
                if (!e || lt.filter(e, [n]).length > 0) {
                    if (!t && 1 === n.nodeType) lt.cleanData(b(n));
                    if (n.parentNode) {
                        if (t && lt.contains(n.ownerDocument, n)) g(b(n, "script"));
                        n.parentNode.removeChild(n)
                    }
                }
            return this
        },
        empty: function() {
            for (var e, t = 0; null != (e = this[t]); t++) {
                if (1 === e.nodeType) lt.cleanData(b(e, !1));
                for (; e.firstChild;) e.removeChild(e.firstChild);
                if (e.options && lt.nodeName(e, "select")) e.options.length = 0
            }
            return this
        },
        clone: function(e, t) {
            return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function() {
                return lt.clone(this, e, t)
            })
        },
        html: function(e) {
            return lt.access(this, function(e) {
                var n = this[0] || {},
                    i = 0,
                    r = this.length;
                if (e === t) return 1 === n.nodeType ? n.innerHTML.replace(Gt, "") : t;
                if (!("string" != typeof e || en.test(e) || !lt.support.htmlSerialize && Xt.test(e) || !lt.support.leadingWhitespace && Vt.test(e) || an[(Jt.exec(e) || ["", ""])[1].toLowerCase()])) {
                    e = e.replace(Kt, "<$1></$2>");
                    try {
                        for (; r > i; i++)
                            if (n = this[i] || {}, 1 === n.nodeType) lt.cleanData(b(n, !1)), n.innerHTML = e;
                        n = 0
                    } catch (o) {}
                }
                if (n) this.empty().append(e)
            }, null, e, arguments.length)
        },
        replaceWith: function(e) {
            var t = lt.isFunction(e);
            if (!t && "string" != typeof e) e = lt(e).not(this).detach();
            return this.domManip([e], !0, function(e) {
                var t = this.nextSibling,
                    n = this.parentNode;
                if (n) lt(this).remove(), n.insertBefore(e, t)
            })
        },
        detach: function(e) {
            return this.remove(e, !0)
        },
        domManip: function(e, n, i) {
            e = tt.apply([], e);
            var r, o, s, a, l, u, f = 0,
                c = this.length,
                p = this,
                g = c - 1,
                y = e[0],
                v = lt.isFunction(y);
            if (v || !(1 >= c || "string" != typeof y || lt.support.checkClone) && nn.test(y)) return this.each(function(r) {
                var o = p.eq(r);
                if (v) e[0] = y.call(this, r, n ? o.html() : t);
                o.domManip(e, n, i)
            });
            if (c) {
                if (u = lt.buildFragment(e, this[0].ownerDocument, !1, this), r = u.firstChild, 1 === u.childNodes.length) u = r;
                if (r) {
                    for (n = n && lt.nodeName(r, "tr"), a = lt.map(b(u, "script"), h), s = a.length; c > f; f++) {
                        if (o = u, f !== g)
                            if (o = lt.clone(o, !0, !0), s) lt.merge(a, b(o, "script"));
                        i.call(n && lt.nodeName(this[f], "table") ? d(this[f], "tbody") : this[f], o, f)
                    }
                    if (s)
                        for (l = a[a.length - 1].ownerDocument, lt.map(a, m), f = 0; s > f; f++)
                            if (o = a[f], rn.test(o.type || "") && !lt._data(o, "globalEval") && lt.contains(l, o))
                                if (o.src) lt.ajax({
                                    url: o.src,
                                    type: "GET",
                                    dataType: "script",
                                    async: !1,
                                    global: !1,
                                    "throws": !0
                                });
                                else lt.globalEval((o.text || o.textContent || o.innerHTML || "").replace(sn, ""));
                    u = r = null
                }
            }
            return this
        }
    }), lt.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(e, t) {
        lt.fn[e] = function(e) {
            for (var n, i = 0, r = [], o = lt(e), s = o.length - 1; s >= i; i++) n = i === s ? this : this.clone(!0), lt(o[i])[t](n), nt.apply(r, n.get());
            return this.pushStack(r)
        }
    }), lt.extend({
        clone: function(e, t, n) {
            var i, r, o, s, a, l = lt.contains(e.ownerDocument, e);
            if (lt.support.html5Clone || lt.isXMLDoc(e) || !Xt.test("<" + e.nodeName + ">")) o = e.cloneNode(!0);
            else un.innerHTML = e.outerHTML, un.removeChild(o = un.firstChild);
            if (!(lt.support.noCloneEvent && lt.support.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || lt.isXMLDoc(e)))
                for (i = b(o), a = b(e), s = 0; null != (r = a[s]); ++s)
                    if (i[s]) v(r, i[s]);
            if (t)
                if (n)
                    for (a = a || b(e), i = i || b(o), s = 0; null != (r = a[s]); s++) y(r, i[s]);
                else y(e, o);
            if (i = b(o, "script"), i.length > 0) g(i, !l && b(e, "script"));
            return i = a = r = null, o
        },
        buildFragment: function(e, t, n, i) {
            for (var r, o, s, a, l, u, f, c = e.length, d = p(t), h = [], m = 0; c > m; m++)
                if (o = e[m], o || 0 === o)
                    if ("object" === lt.type(o)) lt.merge(h, o.nodeType ? [o] : o);
                    else if (!Zt.test(o)) h.push(t.createTextNode(o));
            else {
                for (a = a || d.appendChild(t.createElement("div")), l = (Jt.exec(o) || ["", ""])[1].toLowerCase(), f = an[l] || an._default, a.innerHTML = f[1] + o.replace(Kt, "<$1></$2>") + f[2], r = f[0]; r--;) a = a.lastChild;
                if (!lt.support.leadingWhitespace && Vt.test(o)) h.push(t.createTextNode(Vt.exec(o)[0]));
                if (!lt.support.tbody)
                    for (o = "table" === l && !Qt.test(o) ? a.firstChild : "<table>" === f[1] && !Qt.test(o) ? a : 0, r = o && o.childNodes.length; r--;)
                        if (lt.nodeName(u = o.childNodes[r], "tbody") && !u.childNodes.length) o.removeChild(u);
                for (lt.merge(h, a.childNodes), a.textContent = ""; a.firstChild;) a.removeChild(a.firstChild);
                a = d.lastChild
            }
            if (a) d.removeChild(a);
            if (!lt.support.appendChecked) lt.grep(b(h, "input"), x);
            for (m = 0; o = h[m++];)
                if (!i || -1 === lt.inArray(o, i)) {
                    if (s = lt.contains(o.ownerDocument, o), a = b(d.appendChild(o), "script"), s) g(a);
                    if (n)
                        for (r = 0; o = a[r++];)
                            if (rn.test(o.type || "")) n.push(o)
                } else;
            return a = null, d
        },
        cleanData: function(e, t) {
            for (var n, i, r, o, s = 0, a = lt.expando, l = lt.cache, u = lt.support.deleteExpando, f = lt.event.special; null != (n = e[s]); s++)
                if (t || lt.acceptData(n))
                    if (r = n[a], o = r && l[r]) {
                        if (o.events)
                            for (i in o.events)
                                if (f[i]) lt.event.remove(n, i);
                                else lt.removeEvent(n, i, o.handle);
                        if (l[r]) {
                            if (delete l[r], u) delete n[a];
                            else if (typeof n.removeAttribute !== G) n.removeAttribute(a);
                            else n[a] = null;
                            Z.push(r)
                        }
                    }
        }
    });
    var fn, cn, pn, dn = /alpha\([^)]*\)/i,
        hn = /opacity\s*=\s*([^)]*)/,
        mn = /^(top|right|bottom|left)$/,
        gn = /^(none|table(?!-c[ea]).+)/,
        yn = /^margin/,
        vn = new RegExp("^(" + ut + ")(.*)$", "i"),
        bn = new RegExp("^(" + ut + ")(?!px)[a-z%]+$", "i"),
        xn = new RegExp("^([+-])=(" + ut + ")", "i"),
        wn = {
            BODY: "block"
        },
        Tn = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        En = {
            letterSpacing: 0,
            fontWeight: 400
        },
        Cn = ["Top", "Right", "Bottom", "Left"],
        Nn = ["Webkit", "O", "Moz", "ms"];
    if (lt.fn.extend({
            css: function(e, n) {
                return lt.access(this, function(e, n, i) {
                    var r, o, s = {},
                        a = 0;
                    if (lt.isArray(n)) {
                        for (o = cn(e), r = n.length; r > a; a++) s[n[a]] = lt.css(e, n[a], !1, o);
                        return s
                    }
                    return i !== t ? lt.style(e, n, i) : lt.css(e, n)
                }, e, n, arguments.length > 1)
            },
            show: function() {
                return E(this, !0)
            },
            hide: function() {
                return E(this)
            },
            toggle: function(e) {
                var t = "boolean" == typeof e;
                return this.each(function() {
                    if (t ? e : T(this)) lt(this).show();
                    else lt(this).hide()
                })
            }
        }), lt.extend({
            cssHooks: {
                opacity: {
                    get: function(e, t) {
                        if (t) {
                            var n = pn(e, "opacity");
                            return "" === n ? "1" : n
                        }
                    }
                }
            },
            cssNumber: {
                columnCount: !0,
                fillOpacity: !0,
                fontWeight: !0,
                lineHeight: !0,
                opacity: !0,
                orphans: !0,
                widows: !0,
                zIndex: !0,
                zoom: !0
            },
            cssProps: {
                "float": lt.support.cssFloat ? "cssFloat" : "styleFloat"
            },
            style: function(e, n, i, r) {
                if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                    var o, s, a, l = lt.camelCase(n),
                        u = e.style;
                    if (n = lt.cssProps[l] || (lt.cssProps[l] = w(u, l)), a = lt.cssHooks[n] || lt.cssHooks[l], i !== t) {
                        if (s = typeof i, "string" === s && (o = xn.exec(i))) i = (o[1] + 1) * o[2] + parseFloat(lt.css(e, n)), s = "number";
                        if (null == i || "number" === s && isNaN(i)) return;
                        if ("number" === s && !lt.cssNumber[l]) i += "px";
                        if (!lt.support.clearCloneStyle && "" === i && 0 === n.indexOf("background")) u[n] = "inherit";
                        if (!(a && "set" in a && (i = a.set(e, i, r)) === t)) try {
                            u[n] = i
                        } catch (f) {}
                    } else if (a && "get" in a && (o = a.get(e, !1, r)) !== t) return o;
                    else return u[n]
                }
            },
            css: function(e, n, i, r) {
                var o, s, a, l = lt.camelCase(n);
                if (n = lt.cssProps[l] || (lt.cssProps[l] = w(e.style, l)), a = lt.cssHooks[n] || lt.cssHooks[l], a && "get" in a) s = a.get(e, !0, i);
                if (s === t) s = pn(e, n, r);
                if ("normal" === s && n in En) s = En[n];
                if ("" === i || i) return o = parseFloat(s), i === !0 || lt.isNumeric(o) ? o || 0 : s;
                else return s
            },
            swap: function(e, t, n, i) {
                var r, o, s = {};
                for (o in t) s[o] = e.style[o], e.style[o] = t[o];
                r = n.apply(e, i || []);
                for (o in t) e.style[o] = s[o];
                return r
            }
        }), e.getComputedStyle) cn = function(t) {
        return e.getComputedStyle(t, null)
    }, pn = function(e, n, i) {
        var r, o, s, a = i || cn(e),
            l = a ? a.getPropertyValue(n) || a[n] : t,
            u = e.style;
        if (a) {
            if ("" === l && !lt.contains(e.ownerDocument, e)) l = lt.style(e, n);
            if (bn.test(l) && yn.test(n)) r = u.width, o = u.minWidth, s = u.maxWidth, u.minWidth = u.maxWidth = u.width = l, l = a.width, u.width = r, u.minWidth = o, u.maxWidth = s
        }
        return l
    };
    else if (X.documentElement.currentStyle) cn = function(e) {
        return e.currentStyle
    }, pn = function(e, n, i) {
        var r, o, s, a = i || cn(e),
            l = a ? a[n] : t,
            u = e.style;
        if (null == l && u && u[n]) l = u[n];
        if (bn.test(l) && !mn.test(n)) {
            if (r = u.left, o = e.runtimeStyle, s = o && o.left) o.left = e.currentStyle.left;
            if (u.left = "fontSize" === n ? "1em" : l, l = u.pixelLeft + "px", u.left = r, s) o.left = s
        }
        return "" === l ? "auto" : l
    };
    if (lt.each(["height", "width"], function(e, t) {
            lt.cssHooks[t] = {
                get: function(e, n, i) {
                    if (n) return 0 === e.offsetWidth && gn.test(lt.css(e, "display")) ? lt.swap(e, Tn, function() {
                        return _(e, t, i)
                    }) : _(e, t, i);
                    else return void 0
                },
                set: function(e, n, i) {
                    var r = i && cn(e);
                    return C(e, n, i ? N(e, t, i, lt.support.boxSizing && "border-box" === lt.css(e, "boxSizing", !1, r), r) : 0)
                }
            }
        }), !lt.support.opacity) lt.cssHooks.opacity = {
        get: function(e, t) {
            return hn.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        },
        set: function(e, t) {
            var n = e.style,
                i = e.currentStyle,
                r = lt.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "",
                o = i && i.filter || n.filter || "";
            if (n.zoom = 1, (t >= 1 || "" === t) && "" === lt.trim(o.replace(dn, "")) && n.removeAttribute)
                if (n.removeAttribute("filter"), "" === t || i && !i.filter) return;
            n.filter = dn.test(o) ? o.replace(dn, r) : o + " " + r
        }
    };
    if (lt(function() {
            if (!lt.support.reliableMarginRight) lt.cssHooks.marginRight = {
                get: function(e, t) {
                    if (t) return lt.swap(e, {
                        display: "inline-block"
                    }, pn, [e, "marginRight"]);
                    else return void 0
                }
            };
            if (!lt.support.pixelPosition && lt.fn.position) lt.each(["top", "left"], function(e, t) {
                lt.cssHooks[t] = {
                    get: function(e, n) {
                        if (n) return n = pn(e, t), bn.test(n) ? lt(e).position()[t] + "px" : n;
                        else return void 0
                    }
                }
            })
        }), lt.expr && lt.expr.filters) lt.expr.filters.hidden = function(e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !lt.support.reliableHiddenOffsets && "none" === (e.style && e.style.display || lt.css(e, "display"))
    }, lt.expr.filters.visible = function(e) {
        return !lt.expr.filters.hidden(e)
    };
    lt.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function(e, t) {
        if (lt.cssHooks[e + t] = {
                expand: function(n) {
                    for (var i = 0, r = {}, o = "string" == typeof n ? n.split(" ") : [n]; 4 > i; i++) r[e + Cn[i] + t] = o[i] || o[i - 2] || o[0];
                    return r
                }
            }, !yn.test(e)) lt.cssHooks[e + t].set = C
    });
    var _n = /%20/g,
        kn = /\[\]$/,
        Sn = /\r?\n/g,
        Dn = /^(?:submit|button|image|reset|file)$/i,
        An = /^(?:input|select|textarea|keygen)/i;
    lt.fn.extend({
        serialize: function() {
            return lt.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var e = lt.prop(this, "elements");
                return e ? lt.makeArray(e) : this
            }).filter(function() {
                var e = this.type;
                return this.name && !lt(this).is(":disabled") && An.test(this.nodeName) && !Dn.test(e) && (this.checked || !tn.test(e))
            }).map(function(e, t) {
                var n = lt(this).val();
                return null == n ? null : lt.isArray(n) ? lt.map(n, function(e) {
                    return {
                        name: t.name,
                        value: e.replace(Sn, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Sn, "\r\n")
                }
            }).get()
        }
    }), lt.param = function(e, n) {
        var i, r = [],
            o = function(e, t) {
                t = lt.isFunction(t) ? t() : null == t ? "" : t, r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
            };
        if (n === t) n = lt.ajaxSettings && lt.ajaxSettings.traditional;
        if (lt.isArray(e) || e.jquery && !lt.isPlainObject(e)) lt.each(e, function() {
            o(this.name, this.value)
        });
        else
            for (i in e) D(i, e[i], n, o);
        return r.join("&").replace(_n, "+")
    }, lt.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(e, t) {
        lt.fn[t] = function(e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), lt.fn.hover = function(e, t) {
        return this.mouseenter(e).mouseleave(t || e)
    };
    var jn, Ln, Mn = lt.now(),
        Rn = /\?/,
        In = /#.*$/,
        On = /([?&])_=[^&]*/,
        Hn = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
        qn = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
        Pn = /^(?:GET|HEAD)$/,
        Fn = /^\/\//,
        $n = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,
        Bn = lt.fn.load,
        Wn = {},
        zn = {},
        Yn = "*/".concat("*");
    try {
        Ln = V.href
    } catch (Un) {
        Ln = X.createElement("a"), Ln.href = "", Ln = Ln.href
    }
    jn = $n.exec(Ln.toLowerCase()) || [], lt.fn.load = function(e, n, i) {
        if ("string" != typeof e && Bn) return Bn.apply(this, arguments);
        var r, o, s, a = this,
            l = e.indexOf(" ");
        if (l >= 0) r = e.slice(l, e.length), e = e.slice(0, l);
        if (lt.isFunction(n)) i = n, n = t;
        else if (n && "object" == typeof n) s = "POST";
        if (a.length > 0) lt.ajax({
            url: e,
            type: s,
            dataType: "html",
            data: n
        }).done(function(e) {
            o = arguments, a.html(r ? lt("<div>").append(lt.parseHTML(e)).find(r) : e)
        }).complete(i && function(e, t) {
            a.each(i, o || [e.responseText, t, e])
        });
        return this
    }, lt.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(e, t) {
        lt.fn[t] = function(e) {
            return this.on(t, e)
        }
    }), lt.each(["get", "post"], function(e, n) {
        lt[n] = function(e, i, r, o) {
            if (lt.isFunction(i)) o = o || r, r = i, i = t;
            return lt.ajax({
                url: e,
                type: n,
                dataType: o,
                data: i,
                success: r
            })
        }
    }), lt.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Ln,
            type: "GET",
            isLocal: qn.test(jn[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Yn,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText"
            },
            converters: {
                "* text": e.String,
                "text html": !0,
                "text json": lt.parseJSON,
                "text xml": lt.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(e, t) {
            return t ? L(L(e, lt.ajaxSettings), t) : L(lt.ajaxSettings, e)
        },
        ajaxPrefilter: A(Wn),
        ajaxTransport: A(zn),
        ajax: function(e, n) {
            function i(e, n, i, r) {
                var o, c, v, b, w, E = n;
                if (2 !== x) {
                    if (x = 2, l) clearTimeout(l);
                    if (f = t, a = r || "", T.readyState = e > 0 ? 4 : 0, i) b = M(p, T, i);
                    if (e >= 200 && 300 > e || 304 === e) {
                        if (p.ifModified) {
                            if (w = T.getResponseHeader("Last-Modified")) lt.lastModified[s] = w;
                            if (w = T.getResponseHeader("etag")) lt.etag[s] = w
                        }
                        if (204 === e) o = !0, E = "nocontent";
                        else if (304 === e) o = !0, E = "notmodified";
                        else o = R(p, b), E = o.state, c = o.data, v = o.error, o = !v
                    } else if (v = E, e || !E)
                        if (E = "error", 0 > e) e = 0;
                    if (T.status = e, T.statusText = (n || E) + "", o) m.resolveWith(d, [c, E, T]);
                    else m.rejectWith(d, [T, E, v]);
                    if (T.statusCode(y), y = t, u) h.trigger(o ? "ajaxSuccess" : "ajaxError", [T, p, o ? c : v]);
                    if (g.fireWith(d, [T, E]), u)
                        if (h.trigger("ajaxComplete", [T, p]), !--lt.active) lt.event.trigger("ajaxStop")
                }
            }
            if ("object" == typeof e) n = e, e = t;
            n = n || {};
            var r, o, s, a, l, u, f, c, p = lt.ajaxSetup({}, n),
                d = p.context || p,
                h = p.context && (d.nodeType || d.jquery) ? lt(d) : lt.event,
                m = lt.Deferred(),
                g = lt.Callbacks("once memory"),
                y = p.statusCode || {},
                v = {},
                b = {},
                x = 0,
                w = "canceled",
                T = {
                    readyState: 0,
                    getResponseHeader: function(e) {
                        var t;
                        if (2 === x) {
                            if (!c)
                                for (c = {}; t = Hn.exec(a);) c[t[1].toLowerCase()] = t[2];
                            t = c[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    },
                    getAllResponseHeaders: function() {
                        return 2 === x ? a : null
                    },
                    setRequestHeader: function(e, t) {
                        var n = e.toLowerCase();
                        if (!x) e = b[n] = b[n] || e, v[e] = t;
                        return this
                    },
                    overrideMimeType: function(e) {
                        if (!x) p.mimeType = e;
                        return this
                    },
                    statusCode: function(e) {
                        var t;
                        if (e)
                            if (2 > x)
                                for (t in e) y[t] = [y[t], e[t]];
                            else T.always(e[T.status]);
                        return this
                    },
                    abort: function(e) {
                        var t = e || w;
                        if (f) f.abort(t);
                        return i(0, t), this
                    }
                };
            if (m.promise(T).complete = g.add, T.success = T.done, T.error = T.fail, p.url = ((e || p.url || Ln) + "").replace(In, "").replace(Fn, jn[1] + "//"), p.type = n.method || n.type || p.method || p.type, p.dataTypes = lt.trim(p.dataType || "*").toLowerCase().match(ft) || [""], null == p.crossDomain) r = $n.exec(p.url.toLowerCase()), p.crossDomain = !(!r || r[1] === jn[1] && r[2] === jn[2] && (r[3] || ("http:" === r[1] ? 80 : 443)) == (jn[3] || ("http:" === jn[1] ? 80 : 443)));
            if (p.data && p.processData && "string" != typeof p.data) p.data = lt.param(p.data, p.traditional);
            if (j(Wn, p, n, T), 2 === x) return T;
            if (u = p.global, u && 0 === lt.active++) lt.event.trigger("ajaxStart");
            if (p.type = p.type.toUpperCase(), p.hasContent = !Pn.test(p.type), s = p.url, !p.hasContent) {
                if (p.data) s = p.url += (Rn.test(s) ? "&" : "?") + p.data, delete p.data;
                if (p.cache === !1) p.url = On.test(s) ? s.replace(On, "$1_=" + Mn++) : s + (Rn.test(s) ? "&" : "?") + "_=" + Mn++
            }
            if (p.ifModified) {
                if (lt.lastModified[s]) T.setRequestHeader("If-Modified-Since", lt.lastModified[s]);
                if (lt.etag[s]) T.setRequestHeader("If-None-Match", lt.etag[s])
            }
            if (p.data && p.hasContent && p.contentType !== !1 || n.contentType) T.setRequestHeader("Content-Type", p.contentType);
            T.setRequestHeader("Accept", p.dataTypes[0] && p.accepts[p.dataTypes[0]] ? p.accepts[p.dataTypes[0]] + ("*" !== p.dataTypes[0] ? ", " + Yn + "; q=0.01" : "") : p.accepts["*"]);
            for (o in p.headers) T.setRequestHeader(o, p.headers[o]);
            if (p.beforeSend && (p.beforeSend.call(d, T, p) === !1 || 2 === x)) return T.abort();
            w = "abort";
            for (o in {
                    success: 1,
                    error: 1,
                    complete: 1
                }) T[o](p[o]);
            if (f = j(zn, p, n, T), !f) i(-1, "No Transport");
            else {
                if (T.readyState = 1, u) h.trigger("ajaxSend", [T, p]);
                if (p.async && p.timeout > 0) l = setTimeout(function() {
                    T.abort("timeout")
                }, p.timeout);
                try {
                    x = 1, f.send(v, i)
                } catch (E) {
                    if (2 > x) i(-1, E);
                    else throw E
                }
            }
            return T
        },
        getScript: function(e, n) {
            return lt.get(e, t, n, "script")
        },
        getJSON: function(e, t, n) {
            return lt.get(e, t, n, "json")
        }
    }), lt.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(e) {
                return lt.globalEval(e), e
            }
        }
    }), lt.ajaxPrefilter("script", function(e) {
        if (e.cache === t) e.cache = !1;
        if (e.crossDomain) e.type = "GET", e.global = !1
    }), lt.ajaxTransport("script", function(e) {
        if (e.crossDomain) {
            var n, i = X.head || lt("head")[0] || X.documentElement;
            return {
                send: function(t, r) {
                    if (n = X.createElement("script"), n.async = !0, e.scriptCharset) n.charset = e.scriptCharset;
                    n.src = e.url, n.onload = n.onreadystatechange = function(e, t) {
                        if (t || !n.readyState || /loaded|complete/.test(n.readyState)) {
                            if (n.onload = n.onreadystatechange = null, n.parentNode) n.parentNode.removeChild(n);
                            if (n = null, !t) r(200, "success")
                        }
                    }, i.insertBefore(n, i.firstChild)
                },
                abort: function() {
                    if (n) n.onload(t, !0)
                }
            }
        }
    });
    var Gn = [],
        Xn = /(=)\?(?=&|$)|\?\?/;
    lt.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var e = Gn.pop() || lt.expando + "_" + Mn++;
            return this[e] = !0, e
        }
    }), lt.ajaxPrefilter("json jsonp", function(n, i, r) {
        var o, s, a, l = n.jsonp !== !1 && (Xn.test(n.url) ? "url" : "string" == typeof n.data && !(n.contentType || "").indexOf("application/x-www-form-urlencoded") && Xn.test(n.data) && "data");
        if (l || "jsonp" === n.dataTypes[0]) {
            if (o = n.jsonpCallback = lt.isFunction(n.jsonpCallback) ? n.jsonpCallback() : n.jsonpCallback, l) n[l] = n[l].replace(Xn, "$1" + o);
            else if (n.jsonp !== !1) n.url += (Rn.test(n.url) ? "&" : "?") + n.jsonp + "=" + o;
            return n.converters["script json"] = function() {
                if (!a) lt.error(o + " was not called");
                return a[0]
            }, n.dataTypes[0] = "json", s = e[o], e[o] = function() {
                a = arguments
            }, r.always(function() {
                if (e[o] = s, n[o]) n.jsonpCallback = i.jsonpCallback, Gn.push(o);
                if (a && lt.isFunction(s)) s(a[0]);
                a = s = t
            }), "script"
        }
    });
    var Vn, Kn, Jn = 0,
        Qn = e.ActiveXObject && function() {
            var e;
            for (e in Vn) Vn[e](t, !0)
        };
    if (lt.ajaxSettings.xhr = e.ActiveXObject ? function() {
            return !this.isLocal && I() || O()
        } : I, Kn = lt.ajaxSettings.xhr(), lt.support.cors = !!Kn && "withCredentials" in Kn, Kn = lt.support.ajax = !!Kn) lt.ajaxTransport(function(n) {
        if (!n.crossDomain || lt.support.cors) {
            var i;
            return {
                send: function(r, o) {
                    var s, a, l = n.xhr();
                    if (n.username) l.open(n.type, n.url, n.async, n.username, n.password);
                    else l.open(n.type, n.url, n.async);
                    if (n.xhrFields)
                        for (a in n.xhrFields) l[a] = n.xhrFields[a];
                    if (n.mimeType && l.overrideMimeType) l.overrideMimeType(n.mimeType);
                    if (!n.crossDomain && !r["X-Requested-With"]) r["X-Requested-With"] = "XMLHttpRequest";
                    try {
                        for (a in r) l.setRequestHeader(a, r[a])
                    } catch (u) {}
                    if (l.send(n.hasContent && n.data || null), i = function(e, r) {
                            var a, u, f, c;
                            try {
                                if (i && (r || 4 === l.readyState)) {
                                    if (i = t, s)
                                        if (l.onreadystatechange = lt.noop, Qn) delete Vn[s];
                                    if (r) {
                                        if (4 !== l.readyState) l.abort()
                                    } else {
                                        if (c = {}, a = l.status, u = l.getAllResponseHeaders(), "string" == typeof l.responseText) c.text = l.responseText;
                                        try {
                                            f = l.statusText
                                        } catch (p) {
                                            f = ""
                                        }
                                        if (!a && n.isLocal && !n.crossDomain) a = c.text ? 200 : 404;
                                        else if (1223 === a) a = 204
                                    }
                                }
                            } catch (d) {
                                if (!r) o(-1, d)
                            }
                            if (c) o(a, f, c, u)
                        }, !n.async) i();
                    else if (4 === l.readyState) setTimeout(i);
                    else {
                        if (s = ++Jn, Qn) {
                            if (!Vn) Vn = {}, lt(e).unload(Qn);
                            Vn[s] = i
                        }
                        l.onreadystatechange = i
                    }
                },
                abort: function() {
                    if (i) i(t, !0)
                }
            }
        }
    });
    var Zn, ei, ti = /^(?:toggle|show|hide)$/,
        ni = new RegExp("^(?:([+-])=|)(" + ut + ")([a-z%]*)$", "i"),
        ii = /queueHooks$/,
        ri = [$],
        oi = {
            "*": [function(e, t) {
                var n, i, r = this.createTween(e, t),
                    o = ni.exec(t),
                    s = r.cur(),
                    a = +s || 0,
                    l = 1,
                    u = 20;
                if (o) {
                    if (n = +o[2], i = o[3] || (lt.cssNumber[e] ? "" : "px"), "px" !== i && a) {
                        a = lt.css(r.elem, e, !0) || n || 1;
                        do l = l || ".5", a /= l, lt.style(r.elem, e, a + i); while (l !== (l = r.cur() / s) && 1 !== l && --u)
                    }
                    r.unit = i, r.start = a, r.end = o[1] ? a + (o[1] + 1) * n : n
                }
                return r
            }]
        };
    if (lt.Animation = lt.extend(P, {
            tweener: function(e, t) {
                if (lt.isFunction(e)) t = e, e = ["*"];
                else e = e.split(" ");
                for (var n, i = 0, r = e.length; r > i; i++) n = e[i], oi[n] = oi[n] || [], oi[n].unshift(t)
            },
            prefilter: function(e, t) {
                if (t) ri.unshift(e);
                else ri.push(e)
            }
        }), lt.Tween = B, B.prototype = {
            constructor: B,
            init: function(e, t, n, i, r, o) {
                this.elem = e, this.prop = n, this.easing = r || "swing", this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = o || (lt.cssNumber[n] ? "" : "px")
            },
            cur: function() {
                var e = B.propHooks[this.prop];
                return e && e.get ? e.get(this) : B.propHooks._default.get(this)
            },
            run: function(e) {
                var t, n = B.propHooks[this.prop];
                if (this.options.duration) this.pos = t = lt.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration);
                else this.pos = t = e;
                if (this.now = (this.end - this.start) * t + this.start, this.options.step) this.options.step.call(this.elem, this.now, this);
                if (n && n.set) n.set(this);
                else B.propHooks._default.set(this);
                return this
            }
        }, B.prototype.init.prototype = B.prototype, B.propHooks = {
            _default: {
                get: function(e) {
                    var t;
                    if (null != e.elem[e.prop] && (!e.elem.style || null == e.elem.style[e.prop])) return e.elem[e.prop];
                    else return t = lt.css(e.elem, e.prop, ""), !t || "auto" === t ? 0 : t
                },
                set: function(e) {
                    if (lt.fx.step[e.prop]) lt.fx.step[e.prop](e);
                    else if (e.elem.style && (null != e.elem.style[lt.cssProps[e.prop]] || lt.cssHooks[e.prop])) lt.style(e.elem, e.prop, e.now + e.unit);
                    else e.elem[e.prop] = e.now
                }
            }
        }, B.propHooks.scrollTop = B.propHooks.scrollLeft = {
            set: function(e) {
                if (e.elem.nodeType && e.elem.parentNode) e.elem[e.prop] = e.now
            }
        }, lt.each(["toggle", "show", "hide"], function(e, t) {
            var n = lt.fn[t];
            lt.fn[t] = function(e, i, r) {
                return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(W(t, !0), e, i, r)
            }
        }), lt.fn.extend({
            fadeTo: function(e, t, n, i) {
                return this.filter(T).css("opacity", 0).show().end().animate({
                    opacity: t
                }, e, n, i)
            },
            animate: function(e, t, n, i) {
                var r = lt.isEmptyObject(e),
                    o = lt.speed(t, n, i),
                    s = function() {
                        var t = P(this, lt.extend({}, e), o);
                        if (s.finish = function() {
                                t.stop(!0)
                            }, r || lt._data(this, "finish")) t.stop(!0)
                    };
                return s.finish = s, r || o.queue === !1 ? this.each(s) : this.queue(o.queue, s)
            },
            stop: function(e, n, i) {
                var r = function(e) {
                    var t = e.stop;
                    delete e.stop, t(i)
                };
                if ("string" != typeof e) i = n, n = e, e = t;
                if (n && e !== !1) this.queue(e || "fx", []);
                return this.each(function() {
                    var t = !0,
                        n = null != e && e + "queueHooks",
                        o = lt.timers,
                        s = lt._data(this);
                    if (n) {
                        if (s[n] && s[n].stop) r(s[n])
                    } else
                        for (n in s)
                            if (s[n] && s[n].stop && ii.test(n)) r(s[n]); for (n = o.length; n--;)
                        if (o[n].elem === this && (null == e || o[n].queue === e)) o[n].anim.stop(i), t = !1, o.splice(n, 1);
                    if (t || !i) lt.dequeue(this, e)
                })
            },
            finish: function(e) {
                if (e !== !1) e = e || "fx";
                return this.each(function() {
                    var t, n = lt._data(this),
                        i = n[e + "queue"],
                        r = n[e + "queueHooks"],
                        o = lt.timers,
                        s = i ? i.length : 0;
                    if (n.finish = !0, lt.queue(this, e, []), r && r.cur && r.cur.finish) r.cur.finish.call(this);
                    for (t = o.length; t--;)
                        if (o[t].elem === this && o[t].queue === e) o[t].anim.stop(!0), o.splice(t, 1);
                    for (t = 0; s > t; t++)
                        if (i[t] && i[t].finish) i[t].finish.call(this);
                    delete n.finish
                })
            }
        }), lt.each({
            slideDown: W("show"),
            slideUp: W("hide"),
            slideToggle: W("toggle"),
            fadeIn: {
                opacity: "show"
            },
            fadeOut: {
                opacity: "hide"
            },
            fadeToggle: {
                opacity: "toggle"
            }
        }, function(e, t) {
            lt.fn[e] = function(e, n, i) {
                return this.animate(t, e, n, i)
            }
        }), lt.speed = function(e, t, n) {
            var i = e && "object" == typeof e ? lt.extend({}, e) : {
                complete: n || !n && t || lt.isFunction(e) && e,
                duration: e,
                easing: n && t || t && !lt.isFunction(t) && t
            };
            if (i.duration = lt.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in lt.fx.speeds ? lt.fx.speeds[i.duration] : lt.fx.speeds._default, null == i.queue || i.queue === !0) i.queue = "fx";
            return i.old = i.complete, i.complete = function() {
                if (lt.isFunction(i.old)) i.old.call(this);
                if (i.queue) lt.dequeue(this, i.queue)
            }, i
        }, lt.easing = {
            linear: function(e) {
                return e
            },
            swing: function(e) {
                return .5 - Math.cos(e * Math.PI) / 2
            }
        }, lt.timers = [], lt.fx = B.prototype.init, lt.fx.tick = function() {
            var e, n = lt.timers,
                i = 0;
            for (Zn = lt.now(); i < n.length; i++)
                if (e = n[i], !e() && n[i] === e) n.splice(i--, 1);
            if (!n.length) lt.fx.stop();
            Zn = t
        }, lt.fx.timer = function(e) {
            if (e() && lt.timers.push(e)) lt.fx.start()
        }, lt.fx.interval = 13, lt.fx.start = function() {
            if (!ei) ei = setInterval(lt.fx.tick, lt.fx.interval)
        }, lt.fx.stop = function() {
            clearInterval(ei), ei = null
        }, lt.fx.speeds = {
            slow: 600,
            fast: 200,
            _default: 400
        }, lt.fx.step = {}, lt.expr && lt.expr.filters) lt.expr.filters.animated = function(e) {
        return lt.grep(lt.timers, function(t) {
            return e === t.elem
        }).length
    };
    if (lt.fn.offset = function(e) {
            if (arguments.length) return e === t ? this : this.each(function(t) {
                lt.offset.setOffset(this, e, t)
            });
            var n, i, r = {
                    top: 0,
                    left: 0
                },
                o = this[0],
                s = o && o.ownerDocument;
            if (s) {
                if (n = s.documentElement, !lt.contains(n, o)) return r;
                if (typeof o.getBoundingClientRect !== G) r = o.getBoundingClientRect();
                return i = z(s), {
                    top: r.top + (i.pageYOffset || n.scrollTop) - (n.clientTop || 0),
                    left: r.left + (i.pageXOffset || n.scrollLeft) - (n.clientLeft || 0)
                }
            }
        }, lt.offset = {
            setOffset: function(e, t, n) {
                var i = lt.css(e, "position");
                if ("static" === i) e.style.position = "relative";
                var r, o, s = lt(e),
                    a = s.offset(),
                    l = lt.css(e, "top"),
                    u = lt.css(e, "left"),
                    f = ("absolute" === i || "fixed" === i) && lt.inArray("auto", [l, u]) > -1,
                    c = {},
                    p = {};
                if (f) p = s.position(), r = p.top, o = p.left;
                else r = parseFloat(l) || 0, o = parseFloat(u) || 0;
                if (lt.isFunction(t)) t = t.call(e, n, a);
                if (null != t.top) c.top = t.top - a.top + r;
                if (null != t.left) c.left = t.left - a.left + o;
                if ("using" in t) t.using.call(e, c);
                else s.css(c)
            }
        }, lt.fn.extend({
            position: function() {
                if (this[0]) {
                    var e, t, n = {
                            top: 0,
                            left: 0
                        },
                        i = this[0];
                    if ("fixed" === lt.css(i, "position")) t = i.getBoundingClientRect();
                    else {
                        if (e = this.offsetParent(), t = this.offset(), !lt.nodeName(e[0], "html")) n = e.offset();
                        n.top += lt.css(e[0], "borderTopWidth", !0), n.left += lt.css(e[0], "borderLeftWidth", !0)
                    }
                    return {
                        top: t.top - n.top - lt.css(i, "marginTop", !0),
                        left: t.left - n.left - lt.css(i, "marginLeft", !0)
                    }
                }
            },
            offsetParent: function() {
                return this.map(function() {
                    for (var e = this.offsetParent || X.documentElement; e && !lt.nodeName(e, "html") && "static" === lt.css(e, "position");) e = e.offsetParent;
                    return e || X.documentElement
                })
            }
        }), lt.each({
            scrollLeft: "pageXOffset",
            scrollTop: "pageYOffset"
        }, function(e, n) {
            var i = /Y/.test(n);
            lt.fn[e] = function(r) {
                return lt.access(this, function(e, r, o) {
                    var s = z(e);
                    if (o === t) return s ? n in s ? s[n] : s.document.documentElement[r] : e[r];
                    if (s) s.scrollTo(!i ? o : lt(s).scrollLeft(), i ? o : lt(s).scrollTop());
                    else e[r] = o
                }, e, r, arguments.length, null)
            }
        }), lt.each({
            Height: "height",
            Width: "width"
        }, function(e, n) {
            lt.each({
                padding: "inner" + e,
                content: n,
                "": "outer" + e
            }, function(i, r) {
                lt.fn[r] = function(r, o) {
                    var s = arguments.length && (i || "boolean" != typeof r),
                        a = i || (r === !0 || o === !0 ? "margin" : "border");
                    return lt.access(this, function(n, i, r) {
                        var o;
                        if (lt.isWindow(n)) return n.document.documentElement["client" + e];
                        if (9 === n.nodeType) return o = n.documentElement, Math.max(n.body["scroll" + e], o["scroll" + e], n.body["offset" + e], o["offset" + e], o["client" + e]);
                        else return r === t ? lt.css(n, i, a) : lt.style(n, i, r, a)
                    }, n, s ? r : t, s, null)
                }
            })
        }), "function" == typeof define && define.amd) define("jquery/jquery.min", [], function() {
        return lt
    })
}(window), define("jquery", ["jquery/jquery.min"], function(e) {
        return e
    }),
    function(e) {
        function t(e, t) {
            for (var n in t)
                if (t.hasOwnProperty(n)) e[n] = t[n];
            return e
        }

        function n() {
            this.raw = [], this.length = 0
        }

        function i() {
            return "___" + D++
        }

        function r(e, t) {
            var n = new Function;
            n.prototype = t.prototype, e.prototype = new n, e.prototype.constructor = e
        }

        function o(e) {
            return A[e]
        }

        function s(e) {
            return '"' + e.replace(/\x5C/g, "\\\\").replace(/"/g, '\\"').replace(/\x0A/g, "\\n").replace(/\x09/g, "\\t").replace(/\x0D/g, "\\r") + '"'
        }

        function a(e) {
            return e.replace(/[\^\[\]\$\(\)\{\}\?\*\.\+]/g, function(e) {
                return "\\" + e
            })
        }

        function l(e) {
            var t = arguments;
            return e.replace(/\{([0-9]+)\}/g, function(e, n) {
                return t[n - 0 + 1]
            })
        }

        function u(e) {
            return e = e.replace(/^\s*\*/, ""), l('gv({0},["{1}"])', s(e), e.replace(/\[['"]?([^'"]+)['"]?\]/g, function(e, t) {
                return "." + t
            }).split(".").join('","'))
        }

        function f(e, t, n, i, r, o) {
            for (var s = n.length, a = e.split(t), l = 0, u = [], f = 0, c = a.length; c > f; f++) {
                var p = a[f];
                if (f) {
                    var d = 1;
                    for (l++;;) {
                        var h = p.indexOf(n);
                        if (0 > h) {
                            u.push(l > 1 && d ? t : "", p);
                            break
                        }
                        if (l = i ? l - 1 : 0, u.push(l > 0 && d ? t : "", p.slice(0, h), l > 0 ? n : ""), p = p.slice(h + s), d = 0, 0 === l) break
                    }
                    if (0 === l) r(u.join("")), o(p), u = []
                } else p && o(p)
            }
            if (l > 0 && u.length > 0) o(t), o(u.join(""))
        }

        function c(e, t, n) {
            var i, r = [],
                o = t.options,
                a = "",
                l = "",
                p = "",
                d = "";
            if (n) a = "ts(", l = ")", p = M, d = R, i = o.defaultFilter;
            return f(e, o.variableOpen, o.variableClose, 1, function(e) {
                if (n && e.indexOf("|") < 0 && i) e += "|" + i;
                var o = e.indexOf("|"),
                    s = (o > 0 ? e.slice(0, o) : e).replace(/^\s+/, "").replace(/\s+$/, ""),
                    f = o > 0 ? e.slice(o + 1) : "",
                    h = 0 === s.indexOf("*"),
                    m = [h ? "" : a, u(s), h ? "" : l];
                if (f) {
                    f = c(f, t);
                    for (var g = f.split("|"), y = 0, v = g.length; v > y; y++) {
                        var b = g[y];
                        if (/^\s*([a-z0-9_-]+)(\((.*)\))?\s*$/i.test(b)) {
                            if (m.unshift('fs["' + RegExp.$1 + '"]('), RegExp.$3) m.push(",", RegExp.$3);
                            m.push(")")
                        }
                    }
                }
                r.push(p, m.join(""), d)
            }, function(e) {
                r.push(p, n ? s(e) : e, d)
            }), r.join("")
        }

        function p(e, t) {
            this.value = e, this.engine = t
        }

        function d(e, t) {
            this.value = e, this.engine = t, this.children = [], this.cloneProps = []
        }

        function h(e, t) {
            var n = e.stack,
                i = t ? n.find(function(e) {
                    return e instanceof t
                }) : n.bottom();
            if (i) {
                for (var r;
                    (r = n.top()) !== i;) {
                    if (!r.autoClose) throw new Error(r.type + " must be closed manually: " + r.value);
                    r.autoClose(e)
                }
                i.close(e)
            }
            return i
        }

        function m(e, t) {
            if (!/^\s*([a-z0-9\/_-]+)\s*(\(\s*master\s*=\s*([a-z0-9\/_-]+)\s*\))?\s*/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.master = RegExp.$3, this.name = RegExp.$1, d.call(this, e, t), this.blocks = {}
        }

        function g(e, t) {
            if (!/^\s*([a-z0-9\/_-]+)\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.name = RegExp.$1, d.call(this, e, t), this.cloneProps = ["name"]
        }

        function y(e, t) {
            if (!/^\s*([a-z0-9\/_-]+)\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.name = RegExp.$1, d.call(this, e, t), this.cloneProps = ["name", "state", "blocks"], this.blocks = {}
        }

        function v(e, t) {
            if (!/^\s*([a-z0-9_]+)\s*=([\s\S]*)$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.name = RegExp.$1, this.expr = RegExp.$2, d.call(this, e, t), this.cloneProps = ["name", "expr"]
        }

        function b(e, t) {
            if (!/^\s*([a-z0-9_-]+)\s*(\(([\s\S]*)\))?\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.name = RegExp.$1, this.args = RegExp.$3, d.call(this, e, t), this.cloneProps = ["name", "args"]
        }

        function x(e, t) {
            if (!/^\s*([a-z0-9\/_-]+)\s*(\(([\s\S]*)\))?\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.name = RegExp.$1, this.args = RegExp.$3, d.call(this, e, t), this.cloneProps = ["name", "args"]
        }

        function w(e, t) {
            var n = new RegExp(l("^\\s*({0}[\\s\\S]+{1})\\s+as\\s+{0}([0-9a-z_]+){1}\\s*(,\\s*{0}([0-9a-z_]+){1})?\\s*$", a(t.options.variableOpen), a(t.options.variableClose)), "i");
            if (!n.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.list = RegExp.$1, this.item = RegExp.$2, this.index = RegExp.$4, d.call(this, e, t), this.cloneProps = ["list", "item", "index"]
        }

        function T(e, t) {
            d.call(this, e, t)
        }

        function E(e, t) {
            T.call(this, e, t)
        }

        function C(e, t) {
            d.call(this, e, t)
        }

        function N(e, t) {
            t.target = e;
            var n = t.engine,
                i = e.name;
            if (n.targets[i]) switch (n.options.namingConflict) {
                case "override":
                    n.targets[i] = e, t.targets.push(i);
                case "ignore":
                    break;
                default:
                    throw new Error("Target exists: " + i)
            } else n.targets[i] = e, t.targets.push(i)
        }

        function _(e, t) {
            q[e] = t, t.prototype.type = e
        }

        function k(e) {
            this.options = {
                commandOpen: "<!--",
                commandClose: "-->",
                commandSyntax: /^\s*(\/)?([a-z]+)\s*(?::([\s\S]*))?$/,
                variableOpen: "${",
                variableClose: "}",
                defaultFilter: "html"
            }, this.config(e), this.targets = {}, this.filters = t({}, j)
        }

        function S(e, t) {
            function i() {
                var e;
                if (c.length > 0 && (e = c.join(""))) {
                    var n = new p(e, t);
                    if (n.beforeAdd(u), l.top().addChild(n), c = [], t.options.strip && u.current instanceof d) n.value = e.replace(/^[\x20\t\r]*\n/, "");
                    u.current = n
                }
            }
            var r, o = t.options.commandOpen,
                s = t.options.commandClose,
                a = t.options.commandSyntax,
                l = new n,
                u = {
                    engine: t,
                    targets: [],
                    stack: l,
                    target: null
                },
                c = [];
            return f(e, o, s, 0, function(e) {
                var n = a.exec(e);
                if (n && (r = q[n[2].toLowerCase()]) && "function" == typeof r) {
                    i();
                    var l = u.current;
                    if (t.options.strip && l instanceof p) l.value = l.value.replace(/\r?\n[\x20\t]*$/, "\n");
                    if (n[1]) l = h(u, r);
                    else {
                        if (l = new r(n[3], t), "function" == typeof l.beforeOpen) l.beforeOpen(u);
                        l.open(u)
                    }
                    u.current = l
                } else if (!/^\s*\/\//.test(e)) c.push(o, e, s);
                r = null
            }, function(e) {
                c.push(e)
            }), i(), h(u), u.targets
        }
        n.prototype = {
            push: function(e) {
                this.raw[this.length++] = e
            },
            pop: function() {
                if (this.length > 0) {
                    var e = this.raw[--this.length];
                    return this.raw.length = this.length, e
                }
            },
            top: function() {
                return this.raw[this.length - 1]
            },
            bottom: function() {
                return this.raw[0]
            },
            find: function(e) {
                for (var t = this.length; t--;) {
                    var n = this.raw[t];
                    if (e(n)) return n
                }
            }
        };
        var D = 178245,
            A = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;"
            },
            j = {
                html: function(e) {
                    return e.replace(/[&<>"']/g, o)
                },
                url: encodeURIComponent,
                raw: function(e) {
                    return e
                }
            },
            L = 'var r="";',
            M = "r+=",
            R = ";",
            I = "return r;";
        if ("undefined" != typeof navigator && /msie\s*([0-9]+)/i.test(navigator.userAgent) && RegExp.$1 - 0 < 8) L = "var r=[],ri=0;", M = "r[ri++]=", I = 'return r.join("");';
        p.prototype = {
            getRendererBody: function() {
                var e = this.value,
                    t = this.engine.options;
                if (!e || t.strip && /^\s*$/.test(e)) return "";
                else return c(e, this.engine, 1)
            },
            clone: function() {
                return this
            }
        }, d.prototype = {
            addChild: function(e) {
                this.children.push(e)
            },
            open: function(e) {
                var t = e.stack.top();
                t && t.addChild(this), e.stack.push(this)
            },
            close: function(e) {
                if (e.stack.top() === this) e.stack.pop()
            },
            getRendererBody: function() {
                for (var e = [], t = this.children, n = 0; n < t.length; n++) e.push(t[n].getRendererBody());
                return e.join("")
            },
            clone: function() {
                for (var e = new this.constructor(this.value, this.engine), t = 0, n = this.children.length; n > t; t++) e.addChild(this.children[t].clone());
                for (var t = 0, n = this.cloneProps.length; n > t; t++) {
                    var i = this.cloneProps[t];
                    e[i] = this[i]
                }
                return e
            }
        };
        var O = 'data=data||{};var v={},fs=engine.filters,hg=typeof data.get=="function",gv=function(n,ps){var p=ps[0],d=v[p];if(d==null){if(hg){return data.get(n);}d=data[p];}for(var i=1,l=ps.length;i<l;i++)if(d!=null)d = d[ps[i]];return d;},ts=function(s){if(typeof s==="string"){return s;}if(s==null){s="";}return ""+s;};';
        r(m, d), r(g, d), r(y, d), r(v, d), r(b, d), r(x, d), r(w, d), r(T, d), r(E, T), r(C, T);
        var H = {
            READING: 1,
            READED: 2,
            APPLIED: 3,
            READY: 4
        };
        y.prototype.applyMaster = m.prototype.applyMaster = function(e) {
            function t(e) {
                var i = e.children;
                if (i instanceof Array)
                    for (var r = 0, o = i.length; o > r; r++) {
                        var s = i[r];
                        if (s instanceof g && n[s.name]) s = i[r] = n[s.name];
                        t(s)
                    }
            }
            if (this.state >= H.APPLIED) return 1;
            var n = this.blocks,
                i = this.engine.targets[e];
            if (i && i.applyMaster(i.master)) return this.children = i.clone().children, t(this), this.state = H.APPLIED, 1;
            else return void 0
        }, m.prototype.isReady = function() {
            function e(i) {
                for (var r = 0, o = i.children.length; o > r; r++) {
                    var s = i.children[r];
                    if (s instanceof y) {
                        var a = t.targets[s.name];
                        n = n && a && a.isReady(t)
                    } else if (s instanceof d) e(s)
                }
            }
            if (this.state >= H.READY) return 1;
            var t = this.engine,
                n = 1;
            if (this.applyMaster(this.master)) return e(this), n && (this.state = H.READY), n;
            else return void 0
        }, m.prototype.getRenderer = function() {
            if (this.renderer) return this.renderer;
            if (this.isReady()) {
                var e = new Function("data", "engine", [O, L, this.getRendererBody(), I].join("\n")),
                    t = this.engine;
                return this.renderer = function(n) {
                    return e(n, t)
                }, this.renderer
            }
            return null
        }, m.prototype.open = function(e) {
            h(e), d.prototype.open.call(this, e), this.state = H.READING, N(this, e)
        }, v.prototype.open = x.prototype.open = function(e) {
            e.stack.top().addChild(this)
        }, g.prototype.open = function(e) {
            d.prototype.open.call(this, e), (e.imp || e.target).blocks[this.name] = this
        }, E.prototype.open = function(e) {
            var t = new C;
            t.open(e);
            var n = h(e, T);
            n.addChild(this), e.stack.push(this)
        }, C.prototype.open = function(e) {
            var t = h(e, T);
            t.addChild(this), e.stack.push(this)
        }, y.prototype.open = function(e) {
            this.parent = e.stack.top(), this.target = e.target, d.prototype.open.call(this, e), this.state = H.READING, e.imp = this
        }, x.prototype.close = v.prototype.close = function() {}, y.prototype.close = function(e) {
            d.prototype.close.call(this, e), this.state = H.READED, e.imp = null
        }, m.prototype.close = function(e) {
            d.prototype.close.call(this, e), this.state = this.master ? H.READED : H.APPLIED, e.target = null
        }, y.prototype.autoClose = function(e) {
            var t = this.parent.children;
            t.push.apply(t, this.children), this.children.length = 0;
            for (var n in this.blocks) this.target.blocks[n] = this.blocks[n];
            this.blocks = {}, this.close(e)
        }, x.prototype.beforeOpen = y.prototype.beforeOpen = v.prototype.beforeOpen = w.prototype.beforeOpen = b.prototype.beforeOpen = g.prototype.beforeOpen = T.prototype.beforeOpen = p.prototype.beforeAdd = function(e) {
            if (!e.stack.bottom()) {
                var t = new m(i(), e.engine);
                t.open(e)
            }
        }, y.prototype.getRendererBody = function() {
            return this.applyMaster(this.name), d.prototype.getRendererBody.call(this)
        }, x.prototype.getRendererBody = function() {
            return l("{0}engine.render({2},{{3}}){1}", M, R, s(this.name), c(this.args, this.engine).replace(/(^|,)\s*([a-z0-9_]+)\s*=/gi, function(e, t, n) {
                return (t || "") + s(n) + ":"
            }))
        }, v.prototype.getRendererBody = function() {
            if (this.expr) return l("v[{0}]={1};", s(this.name), c(this.expr, this.engine));
            else return ""
        }, T.prototype.getRendererBody = function() {
            return l("if({0}){{1}}", c(this.value, this.engine), d.prototype.getRendererBody.call(this))
        }, C.prototype.getRendererBody = function() {
            return l("}else{{0}", d.prototype.getRendererBody.call(this))
        }, w.prototype.getRendererBody = function() {
            return l('var {0}={1};if({0} instanceof Array)for (var {4}=0,{5}={0}.length;{4}<{5};{4}++){v[{2}]={4};v[{3}]={0}[{4}];{6}}else if(typeof {0}==="object")for(var {4} in {0}){v[{2}]={4};v[{3}]={0}[{4}];{6}}', i(), c(this.list, this.engine), s(this.index || i()), s(this.item), i(), i(), d.prototype.getRendererBody.call(this))
        }, b.prototype.getRendererBody = function() {
            var e = this.args;
            return l("{2}fs[{5}]((function(){{0}{4}{1}})(){6}){3}", L, I, M, R, d.prototype.getRendererBody.call(this), s(this.name), e ? "," + c(e, this.engine) : "")
        };
        var q = {};
        _("target", m), _("block", g), _("import", y), _("use", x), _("var", v), _("for", w), _("if", T), _("elif", E), _("else", C), _("filter", b), k.prototype.config = function(e) {
            t(this.options, e)
        }, k.prototype.compile = k.prototype.parse = function(e) {
            if (e) {
                var t = S(e, this);
                if (t.length) return this.targets[t[0]].getRenderer()
            }
            return new Function('return ""')
        }, k.prototype.getRenderer = function(e) {
            var t = this.targets[e];
            if (t) return t.getRenderer();
            else return void 0
        }, k.prototype.render = function(e, t) {
            var n = this.getRenderer(e);
            if (n) return n(t);
            else return ""
        }, k.prototype.addFilter = function(e, t) {
            if ("function" == typeof t) this.filters[e] = t
        };
        var P = new k;
        if (P.Engine = k, "object" == typeof exports && "object" == typeof module) exports = module.exports = P;
        else if ("function" == typeof define && define.amd) define("etpl/main", [], P);
        else e.etpl = P
    }(this), define("etpl", ["etpl/main"], function(e) {
        return e
    }), define("common/common.tpl", [], function() {
        return '<!--* @ignore* @file error.tpl* @author mySunShinning(441984145@qq.com)*         yangbinYB(1033371745@qq.com)* @time 14-12-7--><!-- target: Loading --><div class="xjd-loading">&nbsp;</div><!-- /target --><!-- target: Error --><div class="xjd-error"><div class="xjd-error-msg">${msg}</div></div><!-- /target -->'
    }),
    function(e) {
        "use strict";
        if ("function" == typeof define && define.amd) define("common/extra/jquery.scrollUp", ["jquery"], e);
        else window.scrollUp = window.scrollUp || {}, window.scrollUp.scrollUp = e(window.jQuery)
    }(function(e) {
        "use strict";

        function t(n) {
            if (!e.data(document.body, "scrollUp")) e.data(document.body, "scrollUp", !0), t.prototype.init(n)
        }
        return e.extend(t.prototype, {
            defaults: {
                scrollName: "scrollUp",
                scrollDistance: 300,
                scrollFrom: "top",
                scrollSpeed: 300,
                easingType: "linear",
                animation: "fade",
                animationSpeed: 200,
                scrollTrigger: !1,
                scrollTarget: !1,
                scrollText: "返回顶部",
                scrollTitle: !0,
                scrollImg: !0,
                activeOverlay: !1,
                zIndex: 2147483647
            },
            init: function(t) {
                var n, i, r, o, s, a, l, u = this.settings = e.extend({}, this.defaults, t),
                    f = !1;
                if (u.scrollTrigger) l = e(u.scrollTrigger);
                else l = e("<a/>", {
                    id: u.scrollName,
                    href: "#top"
                });
                if (u.scrollTitle) l.attr("title", u.scrollText);
                if (l.appendTo("body"), !u.scrollImg && !u.scrollTrigger) l.html(u.scrollText);
                if (l.css({
                        display: "none",
                        position: "fixed",
                        zIndex: u.zIndex
                    }), u.activeOverlay) e("<div/>", {
                    id: u.scrollName + "-active"
                }).css({
                    position: "absolute",
                    top: u.scrollDistance + "px",
                    width: "100%",
                    borderTop: "1px dotted" + u.activeOverlay,
                    zIndex: u.zIndex
                }).appendTo("body");
                switch (u.animation) {
                    case "fade":
                        n = "fadeIn", i = "fadeOut", r = u.animationSpeed;
                        break;
                    case "slide":
                        n = "slideDown", i = "slideUp", r = u.animationSpeed;
                        break;
                    default:
                        n = "show", i = "hide", r = 0
                }
                if ("top" === u.scrollFrom) o = u.scrollDistance;
                else o = e(document).height() - e(window).height() - u.scrollDistance;
                if (s = e(window).scroll(function() {
                        if (e(window).scrollTop() > o) {
                            if (!f) l[n](r), f = !0
                        } else if (f) l[i](r), f = !1
                    }), u.scrollTarget) {
                    if ("number" == typeof u.scrollTarget) a = u.scrollTarget;
                    else if ("string" == typeof u.scrollTarget) a = Math.floor(e(u.scrollTarget).offset().top)
                } else a = 0;
                l.click(function(t) {
                    t.preventDefault(), e("html, body").animate({
                        scrollTop: a
                    }, u.scrollSpeed, u.easingType)
                })
            },
            destroy: function(t) {
                if (e.removeData(document.body, "scrollUp"), e("#" + e.fn.scrollUp.settings.scrollName).remove(), e("#" + e.fn.scrollUp.settings.scrollName + "-active").remove(), e.fn.jquery.split(".")[1] >= 7) e(window).off("scroll", t);
                else e(window).unbind("scroll", t)
            }
        }), t
    }), define("common/header", ["require", "jquery", "etpl", "./common.tpl", "common/extra/jquery.scrollUp"], function(require) {
        function e() {
            s.compile(a), n(), t(), i(), r()
        }

        function t() {
            var e, t;
            o(".attention-me").mouseenter(function() {
                if (t && t !== o(this)) o(".xinlang-erweima").removeClass("current");
                clearTimeout(e), t = o(this), o(this).children(".xinlang-erweima").addClass("current")
            }).mouseleave(function() {
                e = setTimeout(function() {
                    o(".attention-me").children(".xinlang-erweima").removeClass("current")
                }, 0)
            })
        }

        function n() {
            var e, t;
            o(".footer-site-me-xinlang").mouseenter(function() {
                if (t && t !== o(this)) o(".xinlang-erweima").removeClass("current");
                clearTimeout(e), t = o(this), o(this).children(".xinlang-erweima").addClass("current")
            }).mouseleave(function() {
                e = setTimeout(function() {
                    o(".footer-site-me-xinlang").children(".xinlang-erweima").removeClass("current")
                }, 0)
            })
        }

        function i() {
            var e = o(".login-register");
            e.on("mouseenter", "a", function() {
                o(this).hasClass("login") ? e.removeClass("register-hover").addClass("login-hover") : e.removeClass("login-hover").addClass("register-hover")
            }).on("mouseleave", "a", function() {
                e.hasClass("register-hover") || e.hasClass("login-hover") || e.addClass("login-hover")
            })
        }

        function r() {
            var e = require("common/extra/jquery.scrollUp");
            e()
        }
        var o = require("jquery"),
            s = require("etpl"),
            a = require("./common.tpl");
        return {
            init: e,
            loginRegister: i
        }
    }), define("moment/moment", ["require", "exports", "module"], function(require, exports, module) {
        (function(e) {
            function t(e, t, n) {
                switch (arguments.length) {
                    case 2:
                        return null != e ? e : t;
                    case 3:
                        return null != e ? e : null != t ? t : n;
                    default:
                        throw new Error("Implement me")
                }
            }

            function n() {
                return {
                    empty: !1,
                    unusedTokens: [],
                    unusedInput: [],
                    overflow: -2,
                    charsLeftOver: 0,
                    nullInput: !1,
                    invalidMonth: null,
                    invalidFormat: !1,
                    userInvalidated: !1,
                    iso: !1
                }
            }

            function i(e, t) {
                function n() {
                    if (ct.suppressDeprecationWarnings === !1 && "undefined" != typeof console && console.warn) console.warn("Deprecation warning: " + e)
                }
                var i = !0;
                return u(function() {
                    if (i) n(), i = !1;
                    return t.apply(this, arguments)
                }, t)
            }

            function r(e, t) {
                return function(n) {
                    return p(e.call(this, n), t)
                }
            }

            function o(e, t) {
                return function(n) {
                    return this.lang().ordinal(e.call(this, n), t)
                }
            }

            function s() {}

            function a(e) {
                N(e), u(this, e)
            }

            function l(e) {
                var t = v(e),
                    n = t.year || 0,
                    i = t.quarter || 0,
                    r = t.month || 0,
                    o = t.week || 0,
                    s = t.day || 0,
                    a = t.hour || 0,
                    l = t.minute || 0,
                    u = t.second || 0,
                    f = t.millisecond || 0;
                this._milliseconds = +f + 1e3 * u + 6e4 * l + 36e5 * a, this._days = +s + 7 * o, this._months = +r + 3 * i + 12 * n, this._data = {}, this._bubble()
            }

            function u(e, t) {
                for (var n in t)
                    if (t.hasOwnProperty(n)) e[n] = t[n];
                if (t.hasOwnProperty("toString")) e.toString = t.toString;
                if (t.hasOwnProperty("valueOf")) e.valueOf = t.valueOf;
                return e
            }

            function f(e) {
                var t, n = {};
                for (t in e)
                    if (e.hasOwnProperty(t) && Et.hasOwnProperty(t)) n[t] = e[t];
                return n
            }

            function c(e) {
                if (0 > e) return Math.ceil(e);
                else return Math.floor(e)
            }

            function p(e, t, n) {
                for (var i = "" + Math.abs(e), r = e >= 0; i.length < t;) i = "0" + i;
                return (r ? n ? "+" : "" : "-") + i
            }

            function d(e, t, n, i) {
                var r = t._milliseconds,
                    o = t._days,
                    s = t._months;
                if (i = null == i ? !0 : i, r) e._d.setTime(+e._d + r * n);
                if (o) at(e, "Date", st(e, "Date") + o * n);
                if (s) ot(e, st(e, "Month") + s * n);
                if (i) ct.updateOffset(e, o || s)
            }

            function h(e) {
                return "[object Array]" === Object.prototype.toString.call(e)
            }

            function m(e) {
                return "[object Date]" === Object.prototype.toString.call(e) || e instanceof Date
            }

            function g(e, t, n) {
                var i, r = Math.min(e.length, t.length),
                    o = Math.abs(e.length - t.length),
                    s = 0;
                for (i = 0; r > i; i++)
                    if (n && e[i] !== t[i] || !n && x(e[i]) !== x(t[i])) s++;
                return s + o
            }

            function y(e) {
                if (e) {
                    var t = e.toLowerCase().replace(/(.)s$/, "$1");
                    e = Qt[e] || Zt[t] || t
                }
                return e
            }

            function v(e) {
                var t, n, i = {};
                for (n in e)
                    if (e.hasOwnProperty(n))
                        if (t = y(n)) i[t] = e[n];
                return i
            }

            function b(t) {
                var n, i;
                if (0 === t.indexOf("week")) n = 7, i = "day";
                else if (0 === t.indexOf("month")) n = 12, i = "month";
                else return;
                ct[t] = function(r, o) {
                    var s, a, l = ct.fn._lang[t],
                        u = [];
                    if ("number" == typeof r) o = r, r = e;
                    if (a = function(e) {
                            var t = ct().utc().set(i, e);
                            return l.call(ct.fn._lang, t, r || "")
                        }, null != o) return a(o);
                    else {
                        for (s = 0; n > s; s++) u.push(a(s));
                        return u
                    }
                }
            }

            function x(e) {
                var t = +e,
                    n = 0;
                if (0 !== t && isFinite(t))
                    if (t >= 0) n = Math.floor(t);
                    else n = Math.ceil(t);
                return n
            }

            function w(e, t) {
                return new Date(Date.UTC(e, t + 1, 0)).getUTCDate()
            }

            function T(e, t, n) {
                return tt(ct([e, 11, 31 + t - n]), t, n).week
            }

            function E(e) {
                return C(e) ? 366 : 365
            }

            function C(e) {
                return e % 4 === 0 && e % 100 !== 0 || e % 400 === 0
            }

            function N(e) {
                var t;
                if (e._a && -2 === e._pf.overflow) {
                    if (t = e._a[gt] < 0 || e._a[gt] > 11 ? gt : e._a[yt] < 1 || e._a[yt] > w(e._a[mt], e._a[gt]) ? yt : e._a[vt] < 0 || e._a[vt] > 23 ? vt : e._a[bt] < 0 || e._a[bt] > 59 ? bt : e._a[xt] < 0 || e._a[xt] > 59 ? xt : e._a[wt] < 0 || e._a[wt] > 999 ? wt : -1, e._pf._overflowDayOfYear && (mt > t || t > yt)) t = yt;
                    e._pf.overflow = t
                }
            }

            function _(e) {
                if (null == e._isValid)
                    if (e._isValid = !isNaN(e._d.getTime()) && e._pf.overflow < 0 && !e._pf.empty && !e._pf.invalidMonth && !e._pf.nullInput && !e._pf.invalidFormat && !e._pf.userInvalidated, e._strict) e._isValid = e._isValid && 0 === e._pf.charsLeftOver && 0 === e._pf.unusedTokens.length;
                return e._isValid
            }

            function k(e) {
                return e ? e.toLowerCase().replace("_", "-") : e
            }

            function S(e, t) {
                return t._isUTC ? ct(e).zone(t._offset || 0) : ct(e).local()
            }

            function D(e, t) {
                if (t.abbr = e, !Tt[e]) Tt[e] = new s;
                return Tt[e].set(t), Tt[e]
            }

            function A(e) {
                delete Tt[e]
            }

            function j(e) {
                var t, n, i, r, o = 0,
                    s = function(e) {
                        if (!Tt[e] && Ct) try {
                            require("./lang/" + e)
                        } catch (t) {}
                        return Tt[e]
                    };
                if (!e) return ct.fn._lang;
                if (!h(e)) {
                    if (n = s(e)) return n;
                    e = [e]
                }
                for (; o < e.length;) {
                    for (r = k(e[o]).split("-"), t = r.length, i = k(e[o + 1]), i = i ? i.split("-") : null; t > 0;) {
                        if (n = s(r.slice(0, t).join("-"))) return n;
                        if (i && i.length >= t && g(r, i, !0) >= t - 1) break;
                        t--
                    }
                    o++
                }
                return ct.fn._lang
            }

            function L(e) {
                if (e.match(/\[[\s\S]/)) return e.replace(/^\[|\]$/g, "");
                else return e.replace(/\\/g, "")
            }

            function M(e) {
                var t, n, i = e.match(St);
                for (t = 0, n = i.length; n > t; t++)
                    if (on[i[t]]) i[t] = on[i[t]];
                    else i[t] = L(i[t]);
                return function(r) {
                    var o = "";
                    for (t = 0; n > t; t++) o += i[t] instanceof Function ? i[t].call(r, e) : i[t];
                    return o
                }
            }

            function R(e, t) {
                if (!e.isValid()) return e.lang().invalidDate();
                if (t = I(t, e.lang()), !en[t]) en[t] = M(t);
                return en[t](e)
            }

            function I(e, t) {
                function n(e) {
                    return t.longDateFormat(e) || e
                }
                var i = 5;
                for (Dt.lastIndex = 0; i >= 0 && Dt.test(e);) e = e.replace(Dt, n), Dt.lastIndex = 0, i -= 1;
                return e
            }

            function O(e, t) {
                var n, i = t._strict;
                switch (e) {
                    case "Q":
                        return Ft;
                    case "DDDD":
                        return Bt;
                    case "YYYY":
                    case "GGGG":
                    case "gggg":
                        return i ? Wt : Lt;
                    case "Y":
                    case "G":
                    case "g":
                        return Yt;
                    case "YYYYYY":
                    case "YYYYY":
                    case "GGGGG":
                    case "ggggg":
                        return i ? zt : Mt;
                    case "S":
                        if (i) return Ft;
                    case "SS":
                        if (i) return $t;
                    case "SSS":
                        if (i) return Bt;
                    case "DDD":
                        return jt;
                    case "MMM":
                    case "MMMM":
                    case "dd":
                    case "ddd":
                    case "dddd":
                        return It;
                    case "a":
                    case "A":
                        return j(t._l)._meridiemParse;
                    case "X":
                        return qt;
                    case "Z":
                    case "ZZ":
                        return Ot;
                    case "T":
                        return Ht;
                    case "SSSS":
                        return Rt;
                    case "MM":
                    case "DD":
                    case "YY":
                    case "GG":
                    case "gg":
                    case "HH":
                    case "hh":
                    case "mm":
                    case "ss":
                    case "ww":
                    case "WW":
                        return i ? $t : At;
                    case "M":
                    case "D":
                    case "d":
                    case "H":
                    case "h":
                    case "m":
                    case "s":
                    case "w":
                    case "W":
                    case "e":
                    case "E":
                        return At;
                    case "Do":
                        return Pt;
                    default:
                        return n = new RegExp(Y(z(e.replace("\\", "")), "i"))
                }
            }

            function H(e) {
                e = e || "";
                var t = e.match(Ot) || [],
                    n = t[t.length - 1] || [],
                    i = (n + "").match(Kt) || ["-", 0, 0],
                    r = +(60 * i[1]) + x(i[2]);
                return "+" === i[0] ? -r : r
            }

            function q(e, t, n) {
                var i, r = n._a;
                switch (e) {
                    case "Q":
                        if (null != t) r[gt] = 3 * (x(t) - 1);
                        break;
                    case "M":
                    case "MM":
                        if (null != t) r[gt] = x(t) - 1;
                        break;
                    case "MMM":
                    case "MMMM":
                        if (i = j(n._l).monthsParse(t), null != i) r[gt] = i;
                        else n._pf.invalidMonth = t;
                        break;
                    case "D":
                    case "DD":
                        if (null != t) r[yt] = x(t);
                        break;
                    case "Do":
                        if (null != t) r[yt] = x(parseInt(t, 10));
                        break;
                    case "DDD":
                    case "DDDD":
                        if (null != t) n._dayOfYear = x(t);
                        break;
                    case "YY":
                        r[mt] = ct.parseTwoDigitYear(t);
                        break;
                    case "YYYY":
                    case "YYYYY":
                    case "YYYYYY":
                        r[mt] = x(t);
                        break;
                    case "a":
                    case "A":
                        n._isPm = j(n._l).isPM(t);
                        break;
                    case "H":
                    case "HH":
                    case "h":
                    case "hh":
                        r[vt] = x(t);
                        break;
                    case "m":
                    case "mm":
                        r[bt] = x(t);
                        break;
                    case "s":
                    case "ss":
                        r[xt] = x(t);
                        break;
                    case "S":
                    case "SS":
                    case "SSS":
                    case "SSSS":
                        r[wt] = x(1e3 * ("0." + t));
                        break;
                    case "X":
                        n._d = new Date(1e3 * parseFloat(t));
                        break;
                    case "Z":
                    case "ZZ":
                        n._useUTC = !0, n._tzm = H(t);
                        break;
                    case "dd":
                    case "ddd":
                    case "dddd":
                        if (i = j(n._l).weekdaysParse(t), null != i) n._w = n._w || {}, n._w.d = i;
                        else n._pf.invalidWeekday = t;
                        break;
                    case "w":
                    case "ww":
                    case "W":
                    case "WW":
                    case "d":
                    case "e":
                    case "E":
                        e = e.substr(0, 1);
                    case "gggg":
                    case "GGGG":
                    case "GGGGG":
                        if (e = e.substr(0, 2), t) n._w = n._w || {}, n._w[e] = x(t);
                        break;
                    case "gg":
                    case "GG":
                        n._w = n._w || {}, n._w[e] = ct.parseTwoDigitYear(t)
                }
            }

            function P(e) {
                var n, i, r, o, s, a, l, u;
                if (n = e._w, null != n.GG || null != n.W || null != n.E) s = 1, a = 4, i = t(n.GG, e._a[mt], tt(ct(), 1, 4).year), r = t(n.W, 1), o = t(n.E, 1);
                else if (u = j(e._l), s = u._week.dow, a = u._week.doy, i = t(n.gg, e._a[mt], tt(ct(), s, a).year), r = t(n.w, 1), null != n.d) {
                    if (o = n.d, s > o) ++r
                } else if (null != n.e) o = n.e + s;
                else o = s;
                l = nt(i, r, o, a, s), e._a[mt] = l.year, e._dayOfYear = l.dayOfYear
            }

            function F(e) {
                var n, i, r, o, s = [];
                if (!e._d) {
                    if (r = B(e), e._w && null == e._a[yt] && null == e._a[gt]) P(e);
                    if (e._dayOfYear) {
                        if (o = t(e._a[mt], r[mt]), e._dayOfYear > E(o)) e._pf._overflowDayOfYear = !0;
                        i = J(o, 0, e._dayOfYear), e._a[gt] = i.getUTCMonth(), e._a[yt] = i.getUTCDate()
                    }
                    for (n = 0; 3 > n && null == e._a[n]; ++n) e._a[n] = s[n] = r[n];
                    for (; 7 > n; n++) e._a[n] = s[n] = null == e._a[n] ? 2 === n ? 1 : 0 : e._a[n];
                    if (e._d = (e._useUTC ? J : K).apply(null, s), null != e._tzm) e._d.setUTCMinutes(e._d.getUTCMinutes() + e._tzm)
                }
            }

            function $(e) {
                var t;
                if (!e._d) t = v(e._i), e._a = [t.year, t.month, t.day, t.hour, t.minute, t.second, t.millisecond], F(e)
            }

            function B(e) {
                var t = new Date;
                if (e._useUTC) return [t.getUTCFullYear(), t.getUTCMonth(), t.getUTCDate()];
                else return [t.getFullYear(), t.getMonth(), t.getDate()]
            }

            function W(e) {
                if (e._f === ct.ISO_8601) return void G(e);
                e._a = [], e._pf.empty = !0;
                var t, n, i, r, o, s = j(e._l),
                    a = "" + e._i,
                    l = a.length,
                    u = 0;
                for (i = I(e._f, s).match(St) || [], t = 0; t < i.length; t++) {
                    if (r = i[t], n = (a.match(O(r, e)) || [])[0]) {
                        if (o = a.substr(0, a.indexOf(n)), o.length > 0) e._pf.unusedInput.push(o);
                        a = a.slice(a.indexOf(n) + n.length), u += n.length
                    }
                    if (on[r]) {
                        if (n) e._pf.empty = !1;
                        else e._pf.unusedTokens.push(r);
                        q(r, n, e)
                    } else if (e._strict && !n) e._pf.unusedTokens.push(r)
                }
                if (e._pf.charsLeftOver = l - u, a.length > 0) e._pf.unusedInput.push(a);
                if (e._isPm && e._a[vt] < 12) e._a[vt] += 12;
                if (e._isPm === !1 && 12 === e._a[vt]) e._a[vt] = 0;
                F(e), N(e)
            }

            function z(e) {
                return e.replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function(e, t, n, i, r) {
                    return t || n || i || r
                })
            }

            function Y(e) {
                return e.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
            }

            function U(e) {
                var t, i, r, o, s;
                if (0 === e._f.length) return e._pf.invalidFormat = !0, void(e._d = new Date(0 / 0));
                for (o = 0; o < e._f.length; o++)
                    if (s = 0, t = u({}, e), t._pf = n(), t._f = e._f[o], W(t), _(t)) {
                        if (s += t._pf.charsLeftOver, s += 10 * t._pf.unusedTokens.length, t._pf.score = s, null == r || r > s) r = s, i = t
                    } else;
                u(e, i || t)
            }

            function G(e) {
                var t, n, i = e._i,
                    r = Ut.exec(i);
                if (r) {
                    for (e._pf.iso = !0, t = 0, n = Xt.length; n > t; t++)
                        if (Xt[t][1].exec(i)) {
                            e._f = Xt[t][0] + (r[6] || " ");
                            break
                        }
                    for (t = 0, n = Vt.length; n > t; t++)
                        if (Vt[t][1].exec(i)) {
                            e._f += Vt[t][0];
                            break
                        }
                    if (i.match(Ot)) e._f += "Z";
                    W(e)
                } else e._isValid = !1
            }

            function X(e) {
                if (G(e), e._isValid === !1) delete e._isValid, ct.createFromInputFallback(e)
            }

            function V(t) {
                var n = t._i,
                    i = Nt.exec(n);
                if (n === e) t._d = new Date;
                else if (i) t._d = new Date(+i[1]);
                else if ("string" == typeof n) X(t);
                else if (h(n)) t._a = n.slice(0), F(t);
                else if (m(n)) t._d = new Date(+n);
                else if ("object" == typeof n) $(t);
                else if ("number" == typeof n) t._d = new Date(n);
                else ct.createFromInputFallback(t)
            }

            function K(e, t, n, i, r, o, s) {
                var a = new Date(e, t, n, i, r, o, s);
                if (1970 > e) a.setFullYear(e);
                return a
            }

            function J(e) {
                var t = new Date(Date.UTC.apply(null, arguments));
                if (1970 > e) t.setUTCFullYear(e);
                return t
            }

            function Q(e, t) {
                if ("string" == typeof e)
                    if (!isNaN(e)) e = parseInt(e, 10);
                    else if (e = t.weekdaysParse(e), "number" != typeof e) return null;
                return e
            }

            function Z(e, t, n, i, r) {
                return r.relativeTime(t || 1, !!n, e, i)
            }

            function et(e, t, n) {
                var i = ht(Math.abs(e) / 1e3),
                    r = ht(i / 60),
                    o = ht(r / 60),
                    s = ht(o / 24),
                    a = ht(s / 365),
                    l = i < tn.s && ["s", i] || 1 === r && ["m"] || r < tn.m && ["mm", r] || 1 === o && ["h"] || o < tn.h && ["hh", o] || 1 === s && ["d"] || s <= tn.dd && ["dd", s] || s <= tn.dm && ["M"] || s < tn.dy && ["MM", ht(s / 30)] || 1 === a && ["y"] || ["yy", a];
                return l[2] = t, l[3] = e > 0, l[4] = n, Z.apply({}, l)
            }

            function tt(e, t, n) {
                var i, r = n - t,
                    o = n - e.day();
                if (o > r) o -= 7;
                if (r - 7 > o) o += 7;
                return i = ct(e).add("d", o), {
                    week: Math.ceil(i.dayOfYear() / 7),
                    year: i.year()
                }
            }

            function nt(e, t, n, i, r) {
                var o, s, a = J(e, 0, 1).getUTCDay();
                return a = 0 === a ? 7 : a, n = null != n ? n : r, o = r - a + (a > i ? 7 : 0) - (r > a ? 7 : 0), s = 7 * (t - 1) + (n - r) + o + 1, {
                    year: s > 0 ? e : e - 1,
                    dayOfYear: s > 0 ? s : E(e - 1) + s
                }
            }

            function it(t) {
                var n = t._i,
                    i = t._f;
                if (null === n || i === e && "" === n) return ct.invalid({
                    nullInput: !0
                });
                if ("string" == typeof n) t._i = n = j().preparse(n);
                if (ct.isMoment(n)) t = f(n), t._d = new Date(+n._d);
                else if (i)
                    if (h(i)) U(t);
                    else W(t);
                else V(t);
                return new a(t)
            }

            function rt(e, t) {
                var n, i;
                if (1 === t.length && h(t[0])) t = t[0];
                if (!t.length) return ct();
                for (n = t[0], i = 1; i < t.length; ++i)
                    if (t[i][e](n)) n = t[i];
                return n
            }

            function ot(e, t) {
                var n;
                if ("string" == typeof t)
                    if (t = e.lang().monthsParse(t), "number" != typeof t) return e;
                return n = Math.min(e.date(), w(e.year(), t)), e._d["set" + (e._isUTC ? "UTC" : "") + "Month"](t, n), e
            }

            function st(e, t) {
                return e._d["get" + (e._isUTC ? "UTC" : "") + t]()
            }

            function at(e, t, n) {
                if ("Month" === t) return ot(e, n);
                else return e._d["set" + (e._isUTC ? "UTC" : "") + t](n)
            }

            function lt(e, t) {
                return function(n) {
                    if (null != n) return at(this, e, n), ct.updateOffset(this, t), this;
                    else return st(this, e)
                }
            }

            function ut(e) {
                ct.duration.fn[e] = function() {
                    return this._data[e]
                }
            }

            function ft(e, t) {
                ct.duration.fn["as" + e] = function() {
                    return +this / t
                }
            }
            for (var ct, pt, dt = "2.7.0", ht = ("undefined" != typeof global ? global : this, Math.round), mt = 0, gt = 1, yt = 2, vt = 3, bt = 4, xt = 5, wt = 6, Tt = {}, Et = {
                    _isAMomentObject: null,
                    _i: null,
                    _f: null,
                    _l: null,
                    _strict: null,
                    _tzm: null,
                    _isUTC: null,
                    _offset: null,
                    _pf: null,
                    _lang: null
                }, Ct = "undefined" != typeof module && module.exports, Nt = /^\/?Date\((\-?\d+)/i, _t = /(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/, kt = /^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/, St = /(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,4}|X|zz?|ZZ?|.)/g, Dt = /(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g, At = /\d\d?/, jt = /\d{1,3}/, Lt = /\d{1,4}/, Mt = /[+\-]?\d{1,6}/, Rt = /\d+/, It = /[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i, Ot = /Z|[\+\-]\d\d:?\d\d/gi, Ht = /T/i, qt = /[\+\-]?\d+(\.\d{1,3})?/, Pt = /\d{1,2}/, Ft = /\d/, $t = /\d\d/, Bt = /\d{3}/, Wt = /\d{4}/, zt = /[+-]?\d{6}/, Yt = /[+-]?\d+/, Ut = /^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/, Gt = "YYYY-MM-DDTHH:mm:ssZ", Xt = [
                    ["YYYYYY-MM-DD", /[+-]\d{6}-\d{2}-\d{2}/],
                    ["YYYY-MM-DD", /\d{4}-\d{2}-\d{2}/],
                    ["GGGG-[W]WW-E", /\d{4}-W\d{2}-\d/],
                    ["GGGG-[W]WW", /\d{4}-W\d{2}/],
                    ["YYYY-DDD", /\d{4}-\d{3}/]
                ], Vt = [
                    ["HH:mm:ss.SSSS", /(T| )\d\d:\d\d:\d\d\.\d+/],
                    ["HH:mm:ss", /(T| )\d\d:\d\d:\d\d/],
                    ["HH:mm", /(T| )\d\d:\d\d/],
                    ["HH", /(T| )\d\d/]
                ], Kt = /([\+\-]|\d\d)/gi, Jt = ("Date|Hours|Minutes|Seconds|Milliseconds".split("|"), {
                    Milliseconds: 1,
                    Seconds: 1e3,
                    Minutes: 6e4,
                    Hours: 36e5,
                    Days: 864e5,
                    Months: 2592e6,
                    Years: 31536e6
                }), Qt = {
                    ms: "millisecond",
                    s: "second",
                    m: "minute",
                    h: "hour",
                    d: "day",
                    D: "date",
                    w: "week",
                    W: "isoWeek",
                    M: "month",
                    Q: "quarter",
                    y: "year",
                    DDD: "dayOfYear",
                    e: "weekday",
                    E: "isoWeekday",
                    gg: "weekYear",
                    GG: "isoWeekYear"
                }, Zt = {
                    dayofyear: "dayOfYear",
                    isoweekday: "isoWeekday",
                    isoweek: "isoWeek",
                    weekyear: "weekYear",
                    isoweekyear: "isoWeekYear"
                }, en = {}, tn = {
                    s: 45,
                    m: 45,
                    h: 22,
                    dd: 25,
                    dm: 45,
                    dy: 345
                }, nn = "DDD w W M D d".split(" "), rn = "M D H h m s w W".split(" "), on = {
                    M: function() {
                        return this.month() + 1
                    },
                    MMM: function(e) {
                        return this.lang().monthsShort(this, e)
                    },
                    MMMM: function(e) {
                        return this.lang().months(this, e)
                    },
                    D: function() {
                        return this.date()
                    },
                    DDD: function() {
                        return this.dayOfYear()
                    },
                    d: function() {
                        return this.day()
                    },
                    dd: function(e) {
                        return this.lang().weekdaysMin(this, e)
                    },
                    ddd: function(e) {
                        return this.lang().weekdaysShort(this, e)
                    },
                    dddd: function(e) {
                        return this.lang().weekdays(this, e)
                    },
                    w: function() {
                        return this.week()
                    },
                    W: function() {
                        return this.isoWeek()
                    },
                    YY: function() {
                        return p(this.year() % 100, 2)
                    },
                    YYYY: function() {
                        return p(this.year(), 4)
                    },
                    YYYYY: function() {
                        return p(this.year(), 5)
                    },
                    YYYYYY: function() {
                        var e = this.year(),
                            t = e >= 0 ? "+" : "-";
                        return t + p(Math.abs(e), 6)
                    },
                    gg: function() {
                        return p(this.weekYear() % 100, 2)
                    },
                    gggg: function() {
                        return p(this.weekYear(), 4)
                    },
                    ggggg: function() {
                        return p(this.weekYear(), 5)
                    },
                    GG: function() {
                        return p(this.isoWeekYear() % 100, 2)
                    },
                    GGGG: function() {
                        return p(this.isoWeekYear(), 4)
                    },
                    GGGGG: function() {
                        return p(this.isoWeekYear(), 5)
                    },
                    e: function() {
                        return this.weekday()
                    },
                    E: function() {
                        return this.isoWeekday()
                    },
                    a: function() {
                        return this.lang().meridiem(this.hours(), this.minutes(), !0)
                    },
                    A: function() {
                        return this.lang().meridiem(this.hours(), this.minutes(), !1)
                    },
                    H: function() {
                        return this.hours()
                    },
                    h: function() {
                        return this.hours() % 12 || 12
                    },
                    m: function() {
                        return this.minutes()
                    },
                    s: function() {
                        return this.seconds()
                    },
                    S: function() {
                        return x(this.milliseconds() / 100)
                    },
                    SS: function() {
                        return p(x(this.milliseconds() / 10), 2)
                    },
                    SSS: function() {
                        return p(this.milliseconds(), 3)
                    },
                    SSSS: function() {
                        return p(this.milliseconds(), 3)
                    },
                    Z: function() {
                        var e = -this.zone(),
                            t = "+";
                        if (0 > e) e = -e, t = "-";
                        return t + p(x(e / 60), 2) + ":" + p(x(e) % 60, 2)
                    },
                    ZZ: function() {
                        var e = -this.zone(),
                            t = "+";
                        if (0 > e) e = -e, t = "-";
                        return t + p(x(e / 60), 2) + p(x(e) % 60, 2)
                    },
                    z: function() {
                        return this.zoneAbbr()
                    },
                    zz: function() {
                        return this.zoneName()
                    },
                    X: function() {
                        return this.unix()
                    },
                    Q: function() {
                        return this.quarter()
                    }
                }, sn = ["months", "monthsShort", "weekdays", "weekdaysShort", "weekdaysMin"]; nn.length;) pt = nn.pop(), on[pt + "o"] = o(on[pt], pt);
            for (; rn.length;) pt = rn.pop(), on[pt + pt] = r(on[pt], 2);
            for (on.DDDD = r(on.DDD, 3), u(s.prototype, {
                    set: function(e) {
                        var t, n;
                        for (n in e)
                            if (t = e[n], "function" == typeof t) this[n] = t;
                            else this["_" + n] = t
                    },
                    _months: "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
                    months: function(e) {
                        return this._months[e.month()]
                    },
                    _monthsShort: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
                    monthsShort: function(e) {
                        return this._monthsShort[e.month()]
                    },
                    monthsParse: function(e) {
                        var t, n, i;
                        if (!this._monthsParse) this._monthsParse = [];
                        for (t = 0; 12 > t; t++) {
                            if (!this._monthsParse[t]) n = ct.utc([2e3, t]), i = "^" + this.months(n, "") + "|^" + this.monthsShort(n, ""), this._monthsParse[t] = new RegExp(i.replace(".", ""), "i");
                            if (this._monthsParse[t].test(e)) return t
                        }
                    },
                    _weekdays: "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
                    weekdays: function(e) {
                        return this._weekdays[e.day()]
                    },
                    _weekdaysShort: "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
                    weekdaysShort: function(e) {
                        return this._weekdaysShort[e.day()]
                    },
                    _weekdaysMin: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
                    weekdaysMin: function(e) {
                        return this._weekdaysMin[e.day()]
                    },
                    weekdaysParse: function(e) {
                        var t, n, i;
                        if (!this._weekdaysParse) this._weekdaysParse = [];
                        for (t = 0; 7 > t; t++) {
                            if (!this._weekdaysParse[t]) n = ct([2e3, 1]).day(t), i = "^" + this.weekdays(n, "") + "|^" + this.weekdaysShort(n, "") + "|^" + this.weekdaysMin(n, ""), this._weekdaysParse[t] = new RegExp(i.replace(".", ""), "i");
                            if (this._weekdaysParse[t].test(e)) return t
                        }
                    },
                    _longDateFormat: {
                        LT: "h:mm A",
                        L: "MM/DD/YYYY",
                        LL: "MMMM D YYYY",
                        LLL: "MMMM D YYYY LT",
                        LLLL: "dddd, MMMM D YYYY LT"
                    },
                    longDateFormat: function(e) {
                        var t = this._longDateFormat[e];
                        if (!t && this._longDateFormat[e.toUpperCase()]) t = this._longDateFormat[e.toUpperCase()].replace(/MMMM|MM|DD|dddd/g, function(e) {
                            return e.slice(1)
                        }), this._longDateFormat[e] = t;
                        return t
                    },
                    isPM: function(e) {
                        return "p" === (e + "").toLowerCase().charAt(0)
                    },
                    _meridiemParse: /[ap]\.?m?\.?/i,
                    meridiem: function(e, t, n) {
                        if (e > 11) return n ? "pm" : "PM";
                        else return n ? "am" : "AM"
                    },
                    _calendar: {
                        sameDay: "[Today at] LT",
                        nextDay: "[Tomorrow at] LT",
                        nextWeek: "dddd [at] LT",
                        lastDay: "[Yesterday at] LT",
                        lastWeek: "[Last] dddd [at] LT",
                        sameElse: "L"
                    },
                    calendar: function(e, t) {
                        var n = this._calendar[e];
                        return "function" == typeof n ? n.apply(t) : n
                    },
                    _relativeTime: {
                        future: "in %s",
                        past: "%s ago",
                        s: "a few seconds",
                        m: "a minute",
                        mm: "%d minutes",
                        h: "an hour",
                        hh: "%d hours",
                        d: "a day",
                        dd: "%d days",
                        M: "a month",
                        MM: "%d months",
                        y: "a year",
                        yy: "%d years"
                    },
                    relativeTime: function(e, t, n, i) {
                        var r = this._relativeTime[n];
                        return "function" == typeof r ? r(e, t, n, i) : r.replace(/%d/i, e)
                    },
                    pastFuture: function(e, t) {
                        var n = this._relativeTime[e > 0 ? "future" : "past"];
                        return "function" == typeof n ? n(t) : n.replace(/%s/i, t)
                    },
                    ordinal: function(e) {
                        return this._ordinal.replace("%d", e)
                    },
                    _ordinal: "%d",
                    preparse: function(e) {
                        return e
                    },
                    postformat: function(e) {
                        return e
                    },
                    week: function(e) {
                        return tt(e, this._week.dow, this._week.doy).week
                    },
                    _week: {
                        dow: 0,
                        doy: 6
                    },
                    _invalidDate: "Invalid date",
                    invalidDate: function() {
                        return this._invalidDate
                    }
                }), ct = function(t, i, r, o) {
                    var s;
                    if ("boolean" == typeof r) o = r, r = e;
                    return s = {}, s._isAMomentObject = !0, s._i = t, s._f = i, s._l = r, s._strict = o, s._isUTC = !1, s._pf = n(), it(s)
                }, ct.suppressDeprecationWarnings = !1, ct.createFromInputFallback = i("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.", function(e) {
                    e._d = new Date(e._i)
                }), ct.min = function() {
                    var e = [].slice.call(arguments, 0);
                    return rt("isBefore", e)
                }, ct.max = function() {
                    var e = [].slice.call(arguments, 0);
                    return rt("isAfter", e)
                }, ct.utc = function(t, i, r, o) {
                    var s;
                    if ("boolean" == typeof r) o = r, r = e;
                    return s = {}, s._isAMomentObject = !0, s._useUTC = !0, s._isUTC = !0, s._l = r, s._i = t, s._f = i, s._strict = o, s._pf = n(), it(s).utc()
                }, ct.unix = function(e) {
                    return ct(1e3 * e)
                }, ct.duration = function(e, t) {
                    var n, i, r, o = e,
                        s = null;
                    if (ct.isDuration(e)) o = {
                        ms: e._milliseconds,
                        d: e._days,
                        M: e._months
                    };
                    else if ("number" == typeof e)
                        if (o = {}, t) o[t] = e;
                        else o.milliseconds = e;
                    else if (s = _t.exec(e)) n = "-" === s[1] ? -1 : 1, o = {
                        y: 0,
                        d: x(s[yt]) * n,
                        h: x(s[vt]) * n,
                        m: x(s[bt]) * n,
                        s: x(s[xt]) * n,
                        ms: x(s[wt]) * n
                    };
                    else if (s = kt.exec(e)) n = "-" === s[1] ? -1 : 1, r = function(e) {
                        var t = e && parseFloat(e.replace(",", "."));
                        return (isNaN(t) ? 0 : t) * n
                    }, o = {
                        y: r(s[2]),
                        M: r(s[3]),
                        d: r(s[4]),
                        h: r(s[5]),
                        m: r(s[6]),
                        s: r(s[7]),
                        w: r(s[8])
                    };
                    if (i = new l(o), ct.isDuration(e) && e.hasOwnProperty("_lang")) i._lang = e._lang;
                    return i
                }, ct.version = dt, ct.defaultFormat = Gt, ct.ISO_8601 = function() {}, ct.momentProperties = Et, ct.updateOffset = function() {}, ct.relativeTimeThreshold = function(t, n) {
                    if (tn[t] === e) return !1;
                    else return tn[t] = n, !0
                }, ct.lang = function(e, t) {
                    var n;
                    if (!e) return ct.fn._lang._abbr;
                    if (t) D(k(e), t);
                    else if (null === t) A(e), e = "en";
                    else if (!Tt[e]) j(e);
                    return n = ct.duration.fn._lang = ct.fn._lang = j(e), n._abbr
                }, ct.langData = function(e) {
                    if (e && e._lang && e._lang._abbr) e = e._lang._abbr;
                    return j(e)
                }, ct.isMoment = function(e) {
                    return e instanceof a || null != e && e.hasOwnProperty("_isAMomentObject")
                }, ct.isDuration = function(e) {
                    return e instanceof l
                }, pt = sn.length - 1; pt >= 0; --pt) b(sn[pt]);
            ct.normalizeUnits = function(e) {
                return y(e)
            }, ct.invalid = function(e) {
                var t = ct.utc(0 / 0);
                if (null != e) u(t._pf, e);
                else t._pf.userInvalidated = !0;
                return t
            }, ct.parseZone = function() {
                return ct.apply(null, arguments).parseZone()
            }, ct.parseTwoDigitYear = function(e) {
                return x(e) + (x(e) > 68 ? 1900 : 2e3)
            }, u(ct.fn = a.prototype, {
                clone: function() {
                    return ct(this)
                },
                valueOf: function() {
                    return +this._d + 6e4 * (this._offset || 0)
                },
                unix: function() {
                    return Math.floor(+this / 1e3)
                },
                toString: function() {
                    return this.clone().lang("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
                },
                toDate: function() {
                    return this._offset ? new Date(+this) : this._d
                },
                toISOString: function() {
                    var e = ct(this).utc();
                    if (0 < e.year() && e.year() <= 9999) return R(e, "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]");
                    else return R(e, "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")
                },
                toArray: function() {
                    var e = this;
                    return [e.year(), e.month(), e.date(), e.hours(), e.minutes(), e.seconds(), e.milliseconds()]
                },
                isValid: function() {
                    return _(this)
                },
                isDSTShifted: function() {
                    if (this._a) return this.isValid() && g(this._a, (this._isUTC ? ct.utc(this._a) : ct(this._a)).toArray()) > 0;
                    else return !1
                },
                parsingFlags: function() {
                    return u({}, this._pf)
                },
                invalidAt: function() {
                    return this._pf.overflow
                },
                utc: function() {
                    return this.zone(0)
                },
                local: function() {
                    return this.zone(0), this._isUTC = !1, this
                },
                format: function(e) {
                    var t = R(this, e || ct.defaultFormat);
                    return this.lang().postformat(t)
                },
                add: function(e, t) {
                    var n;
                    if ("string" == typeof e && "string" == typeof t) n = ct.duration(isNaN(+t) ? +e : +t, isNaN(+t) ? t : e);
                    else if ("string" == typeof e) n = ct.duration(+t, e);
                    else n = ct.duration(e, t);
                    return d(this, n, 1), this
                },
                subtract: function(e, t) {
                    var n;
                    if ("string" == typeof e && "string" == typeof t) n = ct.duration(isNaN(+t) ? +e : +t, isNaN(+t) ? t : e);
                    else if ("string" == typeof e) n = ct.duration(+t, e);
                    else n = ct.duration(e, t);
                    return d(this, n, -1), this
                },
                diff: function(e, t, n) {
                    var i, r, o = S(e, this),
                        s = 6e4 * (this.zone() - o.zone());
                    if (t = y(t), "year" === t || "month" === t) {
                        if (i = 432e5 * (this.daysInMonth() + o.daysInMonth()), r = 12 * (this.year() - o.year()) + (this.month() - o.month()), r += (this - ct(this).startOf("month") - (o - ct(o).startOf("month"))) / i, r -= 6e4 * (this.zone() - ct(this).startOf("month").zone() - (o.zone() - ct(o).startOf("month").zone())) / i, "year" === t) r /= 12
                    } else i = this - o, r = "second" === t ? i / 1e3 : "minute" === t ? i / 6e4 : "hour" === t ? i / 36e5 : "day" === t ? (i - s) / 864e5 : "week" === t ? (i - s) / 6048e5 : i;
                    return n ? r : c(r)
                },
                from: function(e, t) {
                    return ct.duration(this.diff(e)).lang(this.lang()._abbr).humanize(!t)
                },
                fromNow: function(e) {
                    return this.from(ct(), e)
                },
                calendar: function(e) {
                    var t = e || ct(),
                        n = S(t, this).startOf("day"),
                        i = this.diff(n, "days", !0),
                        r = -6 > i ? "sameElse" : -1 > i ? "lastWeek" : 0 > i ? "lastDay" : 1 > i ? "sameDay" : 2 > i ? "nextDay" : 7 > i ? "nextWeek" : "sameElse";
                    return this.format(this.lang().calendar(r, this))
                },
                isLeapYear: function() {
                    return C(this.year())
                },
                isDST: function() {
                    return this.zone() < this.clone().month(0).zone() || this.zone() < this.clone().month(5).zone()
                },
                day: function(e) {
                    var t = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
                    if (null != e) return e = Q(e, this.lang()), this.add({
                        d: e - t
                    });
                    else return t
                },
                month: lt("Month", !0),
                startOf: function(e) {
                    switch (e = y(e)) {
                        case "year":
                            this.month(0);
                        case "quarter":
                        case "month":
                            this.date(1);
                        case "week":
                        case "isoWeek":
                        case "day":
                            this.hours(0);
                        case "hour":
                            this.minutes(0);
                        case "minute":
                            this.seconds(0);
                        case "second":
                            this.milliseconds(0)
                    }
                    if ("week" === e) this.weekday(0);
                    else if ("isoWeek" === e) this.isoWeekday(1);
                    if ("quarter" === e) this.month(3 * Math.floor(this.month() / 3));
                    return this
                },
                endOf: function(e) {
                    return e = y(e), this.startOf(e).add("isoWeek" === e ? "week" : e, 1).subtract("ms", 1)
                },
                isAfter: function(e, t) {
                    return t = "undefined" != typeof t ? t : "millisecond", +this.clone().startOf(t) > +ct(e).startOf(t)
                },
                isBefore: function(e, t) {
                    return t = "undefined" != typeof t ? t : "millisecond", +this.clone().startOf(t) < +ct(e).startOf(t)
                },
                isSame: function(e, t) {
                    return t = t || "ms", +this.clone().startOf(t) === +S(e, this).startOf(t)
                },
                min: i("moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548", function(e) {
                    return e = ct.apply(null, arguments), this > e ? this : e
                }),
                max: i("moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548", function(e) {
                    return e = ct.apply(null, arguments), e > this ? this : e
                }),
                zone: function(e, t) {
                    var n = this._offset || 0;
                    if (null != e) {
                        if ("string" == typeof e) e = H(e);
                        if (Math.abs(e) < 16) e = 60 * e;
                        if (this._offset = e, this._isUTC = !0, n !== e)
                            if (!t || this._changeInProgress) d(this, ct.duration(n - e, "m"), 1, !1);
                            else if (!this._changeInProgress) this._changeInProgress = !0, ct.updateOffset(this, !0), this._changeInProgress = null
                    } else return this._isUTC ? n : this._d.getTimezoneOffset();
                    return this
                },
                zoneAbbr: function() {
                    return this._isUTC ? "UTC" : ""
                },
                zoneName: function() {
                    return this._isUTC ? "Coordinated Universal Time" : ""
                },
                parseZone: function() {
                    if (this._tzm) this.zone(this._tzm);
                    else if ("string" == typeof this._i) this.zone(this._i);
                    return this
                },
                hasAlignedHourOffset: function(e) {
                    if (!e) e = 0;
                    else e = ct(e).zone();
                    return (this.zone() - e) % 60 === 0
                },
                daysInMonth: function() {
                    return w(this.year(), this.month())
                },
                dayOfYear: function(e) {
                    var t = ht((ct(this).startOf("day") - ct(this).startOf("year")) / 864e5) + 1;
                    return null == e ? t : this.add("d", e - t)
                },
                quarter: function(e) {
                    return null == e ? Math.ceil((this.month() + 1) / 3) : this.month(3 * (e - 1) + this.month() % 3)
                },
                weekYear: function(e) {
                    var t = tt(this, this.lang()._week.dow, this.lang()._week.doy).year;
                    return null == e ? t : this.add("y", e - t)
                },
                isoWeekYear: function(e) {
                    var t = tt(this, 1, 4).year;
                    return null == e ? t : this.add("y", e - t)
                },
                week: function(e) {
                    var t = this.lang().week(this);
                    return null == e ? t : this.add("d", 7 * (e - t))
                },
                isoWeek: function(e) {
                    var t = tt(this, 1, 4).week;
                    return null == e ? t : this.add("d", 7 * (e - t))
                },
                weekday: function(e) {
                    var t = (this.day() + 7 - this.lang()._week.dow) % 7;
                    return null == e ? t : this.add("d", e - t)
                },
                isoWeekday: function(e) {
                    return null == e ? this.day() || 7 : this.day(this.day() % 7 ? e : e - 7)
                },
                isoWeeksInYear: function() {
                    return T(this.year(), 1, 4)
                },
                weeksInYear: function() {
                    var e = this._lang._week;
                    return T(this.year(), e.dow, e.doy)
                },
                get: function(e) {
                    return e = y(e), this[e]()
                },
                set: function(e, t) {
                    if (e = y(e), "function" == typeof this[e]) this[e](t);
                    return this
                },
                lang: function(t) {
                    if (t === e) return this._lang;
                    else return this._lang = j(t), this
                }
            }), ct.fn.millisecond = ct.fn.milliseconds = lt("Milliseconds", !1), ct.fn.second = ct.fn.seconds = lt("Seconds", !1), ct.fn.minute = ct.fn.minutes = lt("Minutes", !1), ct.fn.hour = ct.fn.hours = lt("Hours", !0), ct.fn.date = lt("Date", !0), ct.fn.dates = i("dates accessor is deprecated. Use date instead.", lt("Date", !0)), ct.fn.year = lt("FullYear", !0), ct.fn.years = i("years accessor is deprecated. Use year instead.", lt("FullYear", !0)), ct.fn.days = ct.fn.day, ct.fn.months = ct.fn.month, ct.fn.weeks = ct.fn.week, ct.fn.isoWeeks = ct.fn.isoWeek, ct.fn.quarters = ct.fn.quarter, ct.fn.toJSON = ct.fn.toISOString, u(ct.duration.fn = l.prototype, {
                _bubble: function() {
                    var e, t, n, i, r = this._milliseconds,
                        o = this._days,
                        s = this._months,
                        a = this._data;
                    a.milliseconds = r % 1e3, e = c(r / 1e3), a.seconds = e % 60, t = c(e / 60), a.minutes = t % 60, n = c(t / 60), a.hours = n % 24, o += c(n / 24), a.days = o % 30, s += c(o / 30), a.months = s % 12, i = c(s / 12), a.years = i
                },
                weeks: function() {
                    return c(this.days() / 7)
                },
                valueOf: function() {
                    return this._milliseconds + 864e5 * this._days + this._months % 12 * 2592e6 + 31536e6 * x(this._months / 12)
                },
                humanize: function(e) {
                    var t = +this,
                        n = et(t, !e, this.lang());
                    if (e) n = this.lang().pastFuture(t, n);
                    return this.lang().postformat(n)
                },
                add: function(e, t) {
                    var n = ct.duration(e, t);
                    return this._milliseconds += n._milliseconds, this._days += n._days, this._months += n._months, this._bubble(), this
                },
                subtract: function(e, t) {
                    var n = ct.duration(e, t);
                    return this._milliseconds -= n._milliseconds, this._days -= n._days, this._months -= n._months, this._bubble(), this
                },
                get: function(e) {
                    return e = y(e), this[e.toLowerCase() + "s"]()
                },
                as: function(e) {
                    return e = y(e), this["as" + e.charAt(0).toUpperCase() + e.slice(1) + "s"]()
                },
                lang: ct.fn.lang,
                toIsoString: function() {
                    var e = Math.abs(this.years()),
                        t = Math.abs(this.months()),
                        n = Math.abs(this.days()),
                        i = Math.abs(this.hours()),
                        r = Math.abs(this.minutes()),
                        o = Math.abs(this.seconds() + this.milliseconds() / 1e3);
                    if (!this.asSeconds()) return "P0D";
                    else return (this.asSeconds() < 0 ? "-" : "") + "P" + (e ? e + "Y" : "") + (t ? t + "M" : "") + (n ? n + "D" : "") + (i || r || o ? "T" : "") + (i ? i + "H" : "") + (r ? r + "M" : "") + (o ? o + "S" : "")
                }
            });
            for (pt in Jt)
                if (Jt.hasOwnProperty(pt)) ft(pt, Jt[pt]), ut(pt.toLowerCase());
            if (ft("Weeks", 6048e5), ct.duration.fn.asMonths = function() {
                    return (+this - 31536e6 * this.years()) / 2592e6 + 12 * this.years()
                }, ct.lang("en", {
                    ordinal: function(e) {
                        var t = e % 10,
                            n = 1 === x(e % 100 / 10) ? "th" : 1 === t ? "st" : 2 === t ? "nd" : 3 === t ? "rd" : "th";
                        return e + n
                    }
                }), Ct) module.exports = ct
        }).call(this)
    }), define("moment", ["moment/moment"], function(e) {
        return e
    }), define("common/data", [], function() {
        return {
            pagerOpt: {
                startPage: 1,
                prevText: "上一页",
                nextText: "下一页"
            }
        }
    }), define("saber-emitter/emitter", [], function() {
        function e() {}
        var t = e.prototype;
        return t._getEvents = function() {
            if (!this._events) this._events = {};
            return this._events
        }, t._getMaxListeners = function() {
            if (isNaN(this.maxListeners)) this.maxListeners = 10;
            return this.maxListeners
        }, t.on = function(e, t) {
            var n = this._getEvents(),
                i = this._getMaxListeners();
            n[e] = n[e] || [];
            var r = n[e].length;
            if (r >= i && 0 !== i) throw new RangeError("Warning: possible Emitter memory leak detected. " + r + " listeners added.");
            return n[e].push(t), this
        }, t.once = function(e, t) {
            function n() {
                i.off(e, n), t.apply(this, arguments)
            }
            var i = this;
            return n.listener = t, this.on(e, n), this
        }, t.off = function(e, t) {
            var n = this._getEvents();
            if (0 === arguments.length) return this._events = {}, this;
            var i = n[e];
            if (!i) return this;
            if (1 === arguments.length) return delete n[e], this;
            for (var r, o = 0; o < i.length; o++)
                if (r = i[o], r === t || r.listener === t) {
                    i.splice(o, 1);
                    break
                }
            return this
        }, t.emit = function(e) {
            var t = this._getEvents(),
                n = t[e],
                i = Array.prototype.slice.call(arguments, 1);
            if (n) {
                n = n.slice(0);
                for (var r = 0, o = n.length; o > r; r++) n[r].apply(this, i)
            }
            return this
        }, t.listeners = function(e) {
            var t = this._getEvents();
            return t[e] || []
        }, t.setMaxListeners = function(e) {
            return this.maxListeners = e, this
        }, e.mixin = function(t) {
            for (var n in e.prototype) t[n] = e.prototype[n];
            return t
        }, e
    }), define("saber-emitter", ["saber-emitter/emitter"], function(e) {
        return e
    }), define("common/XEmitter", ["require", "saber-emitter", "jquery"], function(require) {
        function e() {}
        var t = require("saber-emitter"),
            n = require("jquery");
        return t.mixin = function(e) {
            e = "function" === n.type(e) && e.prototype;
            var i = t.prototype;
            for (var r in i)
                if (i.hasOwnProperty(r)) e[r] = i[r]
        }, e.prototype = {
            constructor: e
        }, t.mixin(e), e.mixin = t.mixin, e
    }), define("common/ui/Pager/pager.tpl", [], function() {
        return '<!-- target: ui-pager --><table class="ui-pager" border="0" cellpadding="0" cellspacing="0"><tr><td><div class="ui-pager-box"><!-- if: ${data.hasprev} --><a href="###" class="ui-pager-item ui-pager-left" data-value="${data.prev}">${data.prevText}</a><!-- /if --><!-- for: ${data.pages} as ${item}, ${idx} --><!-- if: ${idx} == ${data.currentPage} --><a href="###" class="ui-pager-item ui-pager-select current" data-value="${item.index}">${item.value}</a><!-- else --><a href="###" class="ui-pager-item ui-pager-select" data-value="${item.index}">${item.value}</a><!-- /if --><!-- /for --><!-- if: ${data.hasnext} --><a href="###" class="ui-pager-item ui-pager-right" data-value="${data.next}">${data.nextText}</a><!-- /if --></div></td></tr></table>'
    }), define("common/ui/Pager/Pager", ["require", "common/XEmitter", "jquery", "etpl", "./pager.tpl"], function(require) {
        function e(t) {
            this.opt = {}, n.extend(this.opt, e.defaultOpt, t || {}), this.init()
        }
        var t = require("common/XEmitter"),
            n = require("jquery"),
            i = require("etpl"),
            r = require("./pager.tpl");
        return i.compile(r), e.prototype = {
            constructor: e,
            init: function() {
                var e = this;
                this.opt.main = n(this.opt.main), this.render(0), this.opt.main.delegate(".ui-pager-item", "click", function(t) {
                    t.preventDefault();
                    var i = +n.trim(n(this).attr("data-value"));
                    e.emit("change", {
                        e: t,
                        value: i,
                        target: n(this)[0]
                    })
                })
            },
            render: function(e) {
                if (1 === this.opt.total) return void this.opt.main.html("");
                var t = this.calculateItem(e - this.opt.startPage + 1);
                this.opt.main.html(i.render("ui-pager", {
                    data: n.extend({}, t, {
                        prevText: this.opt.prevText,
                        nextText: this.opt.nextText
                    })
                }))
            },
            calculateItem: function(e) {
                e = e || 1;
                var t, n, i = {
                        hasprev: 1,
                        hasnext: 1,
                        prev: this.opt.startPage ? e - 1 : e - 2,
                        next: this.opt.startPage ? e + 1 : e,
                        pages: [],
                        currentPage: -1,
                        page: e
                    },
                    r = this.opt.front,
                    o = this.opt.total - this.opt.end,
                    s = this.opt.front + this.opt.end + 1;
                if (1 === +this.opt.total || e === +this.opt.total) i.hasnext = 0;
                if (1 === e) i.hasprev = 0;
                if (e > r && o > e) t = e - this.opt.front, n = e + this.opt.end;
                else if (this.opt.total < s) t = 1, n = this.opt.total;
                else if (r >= e) t = 1, n = s;
                else if (e >= o) t = this.opt.total - s + 1, n = this.opt.total;
                for (var a = 0, l = t; n >= l; l++) {
                    if (i.pages.push({
                            value: l,
                            index: this.opt.startPage ? l : l - 1
                        }), l === e) i.currentPage = a;
                    a++
                }
                return i
            },
            setOpt: function(e, t) {
                if (this.opt.hasOwnProperty(e)) this.opt[e] = t
            }
        }, e.defaultOpt = {
            startPage: 0,
            page: 0,
            total: 10,
            main: n("body"),
            prevText: "上一页",
            nextText: "下一页",
            front: 4,
            end: 5
        }, t.mixin(e), e
    }), define("common/config", [], function() {
        var e = "" + window.location.protocol + "//" + window.location.host,
            t = {
                ROOT: e,
                IMG_GET: e + "/user/imagecode/getimage?type=",
                REGIST_CHECKNAME_CHECK: e + "/user/registapi/checkname",
                REGIST_CHECKPHONE_CHECK: e + "/user/registapi/checkphone",
                REGIST_SENDSMSCODE_CHECK: e + "/user/registapi/sendsmscode",
                REGIST_CHECKSMSCODE_CHECK: e + "/user/registapi/checksmscode",
                REGIST_INDEX_CHECK: e + "/user/registapi/index",
                REGIST_CHECKINVITER_CHECK: e + "/user/registapi/checkinviter",
                LOGIN_INDEX_CHECK: e + "/user/loginapi/index",
                LOGIN_IMGCODE_ADD: e + "/user/loginapi/getauthimageurl",
                LOGIN_IMGCODE_CHECK: e + "/user/loginapi/checkauthimage",
                EDIT_GETSMSCODE_CHECK: e + "/account/edit/getsmscode",
                EDIT_PHONE_SUBMITE: e + "/account/editapi/checkphone",
                EDIT_PHONE_SUBMITE2ND: e + "/account/editapi/bindnewphone",
                EDIT_EMAILCONFIRM: e + "/account/editapi/newemail",
                EDIT_CHPWD_SUBMITE: e + "/account/editapi/modifypwd",
                ACCOUNT_AWARD_RECEIVEAWARDS: e + "/account/award/receiveawards",
                ACCOUNT_CASH_LIST: e + "/account/cashapi/list",
                LINE_GET: e + "/account/overview/profitCurve",
                SECURE_DEGREE: e + "/account/secure/securedegree",
                SECURE_UNBIND_THIRD: e + "/account/editapi/unbindthird",
                ACCOUNT_CASH_RECHARGE_ADD: e + "/account/cashapi/recharge",
                ACCOUNT_CASH_WITHDRAW_ADD: e + "/account/cashapi/withdraw",
                INVEST_LIST: e + "/invest/api",
                INVEST_DETAIL_START: e + "/invest/list",
                INVEST_DETAIL_CONFIRM_ADD: e + "/invest/tender",
                MY_INVEST_GET: e + "/account/invest/backing",
                MY_INVEST_DETAIL: e + "/account/invest/repayplan",
                MY_INVEST_ENDED: e + "/account/invest/ended",
                MY_INVEST_TENDERING: e + "/account/invest/tendering",
                MY_INVEST_TENDERFAIL: e + "/account/invest/tenderfail",
                MY_MSG_LIST: e + "/msg/list",
                MY_MSG_SETREAD_ADD: e + "/msg/read",
                MY_MSG_DEL: e + "/msg/del",
                MY_MSG_DELALL: e + "/msg/delall",
                MY_MSG_SETREADALL: e + "/msg/readall",
                COMPANY_INFOS_LIST: e + "/infos/infoapi",
                USER_REGISTAPI_MODIFYPWD: e + "/user/registapi/modifypwd",
                LOAN_REQUEST: e + "/loan/request",
                ACCOUNT_AWARDAPI_TICKETS: e + "/account/awardapi/tickets",
                ACCOUNT_AWARDAPI_EXCHANGE: e + "/account/awardapi/exchange",
                ACCOUNT_INVITEAPI_LIST: e + "/account/Inviteapi/list"
            };
        return {
            URL: t
        }
    }), define("common/global", ["jquery"], function() {
        function e() {
            t(document).ready(function() {});
            var e = window.GLOBAL || {};
            for (var n in e) i.set(n, e[n]);
            return i
        }
        var t = require("jquery"),
            n = {},
            i = {
                set: function(e, t) {
                    return n[e] = t, this
                },
                get: function(e) {
                    return n[e]
                },
                clear: function() {
                    return n = {}, this
                },
                remove: function(e) {
                    if (e) delete n[e];
                    return this
                }
            };
        return e()
    }), define("common/Remoter", ["require", "./config", "jquery", "common/global", "./XEmitter"], function(require) {
        function e(e) {
            if (this.opt = {}, this.opt.url = e, !t.URL[e]) throw ["[err]", "url:", e, "undefined"].join(" ")
        }
        var t = require("./config"),
            n = require("jquery"),
            i = require("common/global"),
            r = require("./XEmitter");
        return e.hooks = {
            token: i.get("token")
        }, e.prototype = {
            constructor: e,
            remote: function(i, r) {
                var o = this;
                if ("string" != typeof i) r = i, i = "post";
                return r = n.extend({}, e.hooks, r), n.ajax({
                    url: t.URL[o.opt.url],
                    type: i || "post",
                    async: !0,
                    data: "get" === i ? n.param(r) : r,
                    dataType: "json",
                    success: function(e) {
                        var t = +e.status;
                        if (1025 > t)
                            if (0 === t) o.emit("success", e.data);
                            else if (302 === t) window.location.href = e.data.url;
                        else if (101 === t) o.emit("success", {
                            imgCode: !0,
                            status: e.status,
                            data: e.data
                        });
                        else o.emit("fail", e.statusInfo);
                        else if (t > 1024 && 99999 > t) o.emit("success", {
                            status: t,
                            statusInfo: e.statusInfo,
                            bizError: !0
                        })
                    },
                    error: function() {
                        o.emit("error")
                    }
                })
            },
            jsonp: function(e) {
                var i = this;
                return n.jsonp({
                    url: t.URL[i.opt.url],
                    callback: "callback",
                    data: e,
                    success: function(e) {
                        i.emit("success", e)
                    },
                    error: function() {
                        i.emit("error")
                    }
                })
            }
        }, r.mixin(e), e
    }), define("my/message/list.tpl", [], function() {
        return '<!--* @ignore* @file list* @author mySunShinning(441984145@qq.com)*         yangbinYB(1033371745@qq.com)* @time 14-12-27--><!-- target: msgList --><ul><li class="my-invest-header"><span class="my-invest-title msg-type">消息类型</span><span class="my-invest-title msg-content">内容</span><span class="my-invest-title msg-time">发送时间</span></li><!-- for: ${list} as ${item} --><!-- if: ${item.status} == 1 --><li class="my-invest-item"><!-- else --><li class="my-invest-item unread"><!-- /if --><div class="my-invest-content"><span class="my-invest-project msg-type">${item.type}</span><span class="my-invest-project msg-content"><span class="msg-content-text" data-id="${item.mid}">${item.content}</span></span><span class="my-invest-project msg-time">${item.timeInfo} <span class="del-msg">×</span></span></div><div class="my-invest-detail my-msg-detail"><span class="close-detail">收起</span><p>${item.content}</p><div>查看 <a href="${item.link}">${item.linkname}</a></div></div></li><!-- /for --></ul><!-- /target -->'
    }), define("my/message/index", ["require", "jquery", "etpl", "common/header", "moment", "common/data", "common/ui/Pager/Pager", "common/Remoter", "./list.tpl", 'common/ui/Dialog/Dialog'], function(require) {
        function e() {
            i.init();
            var e, a = t("#my-msg-list");
            n.compile(f), l.remote({
                    status: c,
                    page: 1
                }), l.on("success", function(i) {
                    if (i.bizError) a.html(n.render("Error", i.statusInfo));
                    else {
                        if (!i.list.length) return a.html(n.render("Error", {
                            msg: "您当前还没有消息哟"
                        })), void t("#my-msg-pager").html("");
                        if (!e) e = new s(t.extend({}, o.pagerOpt, {
                            main: t("#my-msg-pager"),
                            total: +i.pageall
                        })), e.on("change", function(e) {
                            l.remote({
                                status: c,
                                page: +e.value
                            })
                        });
                        e.setOpt("total", +i.pageall), e.render(+i.page);
                        for (var u = 0, f = i.list.length; f > u; u++) {
                            var p = i.list[u];
                            p.timeInfo = r.unix(+p.time).format("YYYY-MM-DD HH:mm")
                        }
                        a.html(n.render("msgList", {
                            list: i.list
                        }))
                    }
                }), a.delegate(".msg-content-text", "click", function() {
                    var e = t(this).attr("data-id"),
                        n = t(this).closest(".my-invest-item");
                    var detail = n.find(".my-msg-detail");

                    if (n.removeClass("unread"), n.hasClass("current")) {
                        n.removeClass("current");
                        detail.slideUp();
                    } else{ n.addClass("current"), detail.slideDown(), u.remote({
                        mid: e
                    })
                }
                }), a.delegate(".close-detail", "click", function() {
                    t(this).closest(".my-invest-item").removeClass("current"), t(this).closest(".my-msg-detail").slideUp();
                }), t(".my-invest-tab-item").click(function() {
                    c = +t(this).attr("data-value"), t(".my-invest-tab-item").removeClass("current"), t(this).addClass("current"), l.remote({
                        status: c,
                        page: 1
                    })
                }),
                //删除消息事件
                a.delegate('.del-msg', 'click', function() {
                 /*   dialog.show({
                        width: 300,
                        defaultTitle: true,
                        title: "温馨提示",
                        content: '您确定要删除此条消息么？',
                        confirmBack: function() {
                            var id = t(this).parent().prev().find('.msg-content-text').attr('data-id');
                            delMsg.remote({
                                mid: id
                            });
                        }
                    });*/
                      if (confirm("您确定要删除此条消息么？")) {
                          var id = t(this).parent().prev().find('.msg-content-text').attr('data-id'); 
                          delMsg.remote({
                              mid: id
                          });
                      }
                }),
                delMsg.on('success', function(data) {
                    if (data.bizError) {
                        alert(data.statusInfo);
                    } else {
                        l.remote({
                            status: c,
                            page: 1
                        });
                        if(data.unreadMsg>0){
                            t('.mynews-count').html('('+data.unreadMsg+')');
                            t('.default-fastlogin .unreadmsg').html('('+data.unreadMsg+'条未读)');
                        }
                        else{
                            t('.mynews-count').hide();
                            t('.default-fastlogin .unreadmsg').hide();
                        }
                        
                    }
                }),
                //全部标记为已读 
                t('.set-readAll-btn').click(function() {
               /*     dialog.show({
                        width: 300,
                        defaultTitle: true,
                        title: "温馨提示",
                        content: '您确定要把所有消息设置为已读么？',
                        confirmBack: function() {
                            var userid = t(this).attr('data-userid');
                            setReadAll.remote({
                                uid: userid
                            });
                        }
                    });*/
                    if (confirm("您确定要把所有消息设置为已读么？")) {
                     var userid=t(this).attr('data-userid'); 
                    setReadAll.remote({
                          uid : userid
                    });

                }

                }),
                //全部标记为已读
                setReadAll.on('success', function(data) {
                    if (data.bizError) {
                        alert(data.statusInfo);
                    } else {
                        l.remote({
                            status: status,
                            page: 1
                        });
                        if(data.unreadMsg>0){
                        t('.mynews-count').html('('+data.unreadMsg+')');
                        t('.default-fastlogin .unreadmsg').html('('+data.unreadMsg+'条未读)');
                        } else{
                            t('.mynews-count').hide();
                            t('.default-fastlogin .unreadmsg').hide();
                        }
                    }

                }),
                 //已读
                u.on('success', function(data) {
                    if (data.bizError) {
                        alert(data.statusInfo);
                    } else { 
                        if (data.unreadMsg > 0) {
                            t('.mynews-count').html('(' + data.unreadMsg + ')');
                            t('.default-fastlogin .unreadmsg').html('(' + data.unreadMsg + '条未读)');
                        } else {
                            t('.mynews-count').hide();
                            t('.default-fastlogin .unreadmsg').hide();
                        }
                    }

                })
        }
        var t = require("jquery"),
            n = require("etpl"),
            i = require("common/header"),
            r = require("moment"),
            o = require("common/data"),
            s = require("common/ui/Pager/Pager"),
            a = require("common/Remoter"),
            l = new a("MY_MSG_LIST"),
            u = new a("MY_MSG_SETREAD_ADD"),
            f = require("./list.tpl"),
            c = 0;
        var delMsg = new a('MY_MSG_DEL');
        var delAllMsg = new a('MY_MSG_DELALL');
        var setReadAll = new a('MY_MSG_SETREADALL');
        var dialog = require('common/ui/Dialog/Dialog');
        return {
            init: e
        }
    });
