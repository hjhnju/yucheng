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
        var t = Gt.split("|"),
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

    function g(e) {
        var t = on.exec(e.type);
        if (t) e.type = t[1];
        else e.removeAttribute("type");
        return e
    }

    function m(e, t) {
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
            if ("script" === n && t.text !== e.text) h(t).text = e.text, g(t);
            else if ("object" === n) {
                if (t.parentNode) t.outerHTML = e.outerHTML;
                if (lt.support.html5Clone && e.innerHTML && !lt.trim(t.innerHTML)) t.innerHTML = e.innerHTML
            } else if ("input" === n && tn.test(e.type)) {
                if (t.defaultChecked = t.checked = e.checked, t.value !== e.value) t.value = e.value
            } else if ("option" === n) t.defaultSelected = t.selected = e.defaultSelected;
            else if ("input" === n || "textarea" === n) t.defaultValue = e.defaultValue
        }
    }

    function x(e, n) {
        var i, r, o = 0,
            s = typeof e.getElementsByTagName !== X ? e.getElementsByTagName(n || "*") : typeof e.querySelectorAll !== X ? e.querySelectorAll(n || "*") : t;
        if (!s)
            for (s = [], i = e.childNodes || e; null != (r = i[o]); o++)
                if (!n || lt.nodeName(r, n)) s.push(r);
                else lt.merge(s, x(r, n));
        return n === t || n && lt.nodeName(e, n) ? lt.merge([e], s) : s
    }

    function b(e) {
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
                    if ("" === i.style.display && T(i)) o[s] = lt._data(i, "olddisplay", S(i.nodeName))
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

    function k(e, t, n) {
        var i = !0,
            r = "width" === t ? e.offsetWidth : e.offsetHeight,
            o = cn(e),
            s = lt.support.boxSizing && "border-box" === lt.css(e, "boxSizing", !1, o);
        if (0 >= r || null == r) {
            if (r = pn(e, t, o), 0 > r || null == r) r = e.style[t];
            if (xn.test(r)) return r;
            i = s && (lt.support.boxSizingReliable || r === e.style[t]), r = parseFloat(r) || 0
        }
        return r + N(e, t, n || (s ? "border" : "content"), i, o) + "px"
    }

    function S(e) {
        var t = K,
            n = wn[e];
        if (!n) {
            if (n = D(e, t), "none" === n || !n) fn = (fn || lt("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(t.documentElement), t = (fn[0].contentWindow || fn[0].contentDocument).document, t.write("<!doctype html><html><body>"), t.close(), n = D(e, t), fn.detach();
            wn[e] = n
        }
        return n
    }

    function D(e, t) {
        var n = lt(t.createElement(e)).appendTo(t.body),
            i = lt.css(n[0], "display");
        return n.remove(), i
    }

    function A(e, t, n, i) {
        var r;
        if (lt.isArray(t)) lt.each(t, function(t, r) {
            if (n || Sn.test(e)) i(e, r);
            else A(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
        });
        else if (!n && "object" === lt.type(t))
            for (r in t) A(e + "[" + r + "]", t[r], n, i);
        else i(e, t)
    }

    function _(e) {
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

    function R(e, n, i) {
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

    function I(e, t) {
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

    function H() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {}
    }

    function O() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {}
    }

    function q() {
        return setTimeout(function() {
            Zn = t
        }), Zn = lt.now()
    }

    function M(e, t) {
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
                for (var t = Zn || q(), n = Math.max(0, u.startTime + u.duration - t), i = n / u.duration || 0, o = 1 - i, s = 0, l = u.tweens.length; l > s; s++) u.tweens[s].run(o);
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
                startTime: Zn || q(),
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
        for (B(f, u.opts.specialEasing); s > o; o++)
            if (i = ri[o].call(u, e, f, u.opts)) return i;
        if (M(u, f), lt.isFunction(u.opts.start)) u.opts.start.call(e, u);
        return lt.fx.timer(lt.extend(l, {
            elem: e,
            anim: u,
            queue: u.opts.queue
        })), u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always)
    }

    function B(e, t) {
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

    function F(e, t, n) {
        var i, r, o, s, a, l, u, f, c, p = this,
            d = e.style,
            h = {},
            g = [],
            m = e.nodeType && T(e);
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
                if (!lt.support.inlineBlockNeedsLayout || "inline" === S(e.nodeName)) d.display = "inline-block";
                else d.zoom = 1;
        if (n.overflow)
            if (d.overflow = "hidden", !lt.support.shrinkWrapBlocks) p.always(function() {
                d.overflow = n.overflow[0], d.overflowX = n.overflow[1], d.overflowY = n.overflow[2]
            });
        for (r in t)
            if (s = t[r], ti.exec(s)) {
                if (delete t[r], l = l || "toggle" === s, s === (m ? "hide" : "show")) continue;
                g.push(r)
            }
        if (o = g.length) {
            if (a = lt._data(e, "fxshow") || lt._data(e, "fxshow", {}), "hidden" in a) m = a.hidden;
            if (l) a.hidden = !m;
            if (m) lt(e).show();
            else p.done(function() {
                lt(e).hide()
            });
            for (p.done(function() {
                    var t;
                    lt._removeData(e, "fxshow");
                    for (t in h) lt.style(e, t, h[t])
                }), r = 0; o > r; r++)
                if (i = g[r], u = p.createTween(i, m ? a[i] : 0), h[i] = a[i] || lt.style(e, i), !(i in a))
                    if (a[i] = u.start, m) u.end = u.start, u.start = "width" === i || "height" === i ? 1 : 0
        }
    }

    function $(e, t, n, i, r) {
        return new $.prototype.init(e, t, n, i, r)
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
    var U, G, X = typeof t,
        K = e.document,
        Y = e.location,
        V = e.jQuery,
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
            return new lt.fn.init(e, t, G)
        },
        ut = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        ft = /\S+/g,
        ct = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
        pt = /^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/,
        dt = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        ht = /^[\],:{}\s]*$/,
        gt = /(?:^|:|,)(?:\s*\[)+/g,
        mt = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,
        yt = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,
        vt = /^-ms-/,
        xt = /-([\da-z])/gi,
        bt = function(e, t) {
            return t.toUpperCase()
        },
        wt = function(e) {
            if (K.addEventListener || "load" === e.type || "complete" === K.readyState) Tt(), lt.ready()
        },
        Tt = function() {
            if (K.addEventListener) K.removeEventListener("DOMContentLoaded", wt, !1), e.removeEventListener("load", wt, !1);
            else K.detachEvent("onreadystatechange", wt), e.detachEvent("onload", wt)
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
                        if (n = n instanceof lt ? n[0] : n, lt.merge(this, lt.parseHTML(r[1], n && n.nodeType ? n.ownerDocument || n : K, !0)), dt.test(r[1]) && lt.isPlainObject(n))
                            for (r in n)
                                if (lt.isFunction(this[r])) this[r](n[r]);
                                else this.attr(r, n[r]);
                        return this
                    } else {
                        if (o = K.getElementById(r[2]), o && o.parentNode) {
                            if (o.id !== r[2]) return i.find(e);
                            this.length = 1, this[0] = o
                        }
                        return this.context = K, this.selector = e, this
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
            if (t && e.jQuery === lt) e.jQuery = V;
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
                if (!K.body) return setTimeout(lt.ready);
                if (lt.isReady = !0, !(e !== !0 && --lt.readyWait > 0))
                    if (U.resolveWith(K, [lt]), lt.fn.trigger) lt(K).trigger("ready").off("ready")
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
            t = t || K;
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
                    if (ht.test(t.replace(mt, "@").replace(yt, "]").replace(gt, ""))) return new Function("return " + t)();
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
            return e.replace(vt, "ms-").replace(xt, bt)
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
        if (!U)
            if (U = lt.Deferred(), "complete" === K.readyState) setTimeout(lt.ready);
            else if (K.addEventListener) K.addEventListener("DOMContentLoaded", wt, !1), e.addEventListener("load", wt, !1);
        else {
            K.attachEvent("onreadystatechange", wt), e.attachEvent("onload", wt);
            var n = !1;
            try {
                n = null == e.frameElement && K.documentElement
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
        return U.promise(t)
    }, lt.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(e, t) {
        Q["[object " + t + "]"] = t.toLowerCase()
    }), G = lt(K);
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
        var t, n, i, r, o, s, a, l, u, f, c = K.createElement("div");
        if (c.setAttribute("className", "t"), c.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", n = c.getElementsByTagName("*"), i = c.getElementsByTagName("a")[0], !n || !i || !n.length) return {};
        o = K.createElement("select"), a = o.appendChild(K.createElement("option")), r = c.getElementsByTagName("input")[0], i.style.cssText = "top:1px;float:left;opacity:.5", t = {
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
            enctype: !!K.createElement("form").enctype,
            html5Clone: "<:nav></:nav>" !== K.createElement("nav").cloneNode(!0).outerHTML,
            boxModel: "CSS1Compat" === K.compatMode,
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
        if (r = K.createElement("input"), r.setAttribute("value", ""), t.input = "" === r.getAttribute("value"), r.value = "t", r.setAttribute("type", "radio"), t.radioValue = "t" === r.value, r.setAttribute("checked", "t"), r.setAttribute("name", "t"), s = K.createDocumentFragment(), s.appendChild(r), t.appendChecked = r.checked, t.checkClone = s.cloneNode(!0).cloneNode(!0).lastChild.checked, c.attachEvent) c.attachEvent("onclick", function() {
            t.noCloneEvent = !1
        }), c.cloneNode(!0).click();
        for (f in {
                submit: !0,
                change: !0,
                focusin: !0
            }) c.setAttribute(l = "on" + f, "t"), t[f + "Bubbles"] = l in e || c.attributes[l].expando === !1;
        return c.style.backgroundClip = "content-box", c.cloneNode(!0).style.backgroundClip = "", t.clearCloneStyle = "content-box" === c.style.backgroundClip, lt(function() {
            var n, i, r, o = "padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",
                s = K.getElementsByTagName("body")[0];
            if (s) {
                if (n = K.createElement("div"), n.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", s.appendChild(n).appendChild(c), c.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", r = c.getElementsByTagName("td"), r[0].style.cssText = "padding:0;margin:0;border:0;display:none", u = 0 === r[0].offsetHeight, r[0].style.display = "", r[1].style.display = "none", t.reliableHiddenOffsets = u && 0 === r[0].offsetHeight, c.innerHTML = "", c.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;", t.boxSizing = 4 === c.offsetWidth, t.doesNotIncludeMarginInBodyOffset = 1 !== s.offsetTop, e.getComputedStyle) t.pixelPosition = "1%" !== (e.getComputedStyle(c, null) || {}).top, t.boxSizingReliable = "4px" === (e.getComputedStyle(c, null) || {
                    width: "4px"
                }).width, i = c.appendChild(K.createElement("div")), i.style.cssText = c.style.cssText = o, i.style.marginRight = i.style.width = "0", c.style.width = "1px", t.reliableMarginRight = !parseFloat((e.getComputedStyle(i, null) || {}).marginRight);
                if (typeof c.style.zoom !== X)
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
    var kt, St, Dt = /[\t\r\n]/g,
        At = /\r/g,
        _t = /^(?:input|select|textarea|button|object)$/i,
        jt = /^(?:a|area)$/i,
        Lt = /^(?:checked|selected|autofocus|autoplay|async|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped)$/i,
        Rt = /^(?:checked|selected)$/i,
        It = lt.support.getSetAttribute,
        Ht = lt.support.input;
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
                        if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(Dt, " ") : " ")) {
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
                        if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(Dt, " ") : "")) {
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
                    else if (n === X || "boolean" === n) {
                        if (this.className) lt._data(this, "__className__", this.className);
                        this.className = this.className || e === !1 ? "" : lt._data(this, "__className__") || ""
                    }
                })
            },
            hasClass: function(e) {
                for (var t = " " + e + " ", n = 0, i = this.length; i > n; n++)
                    if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(Dt, " ").indexOf(t) >= 0) return !0;
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
                    else return n = o.value, "string" == typeof n ? n.replace(At, "") : null == n ? "" : n
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
                    if (typeof e.getAttribute === X) return lt.prop(e, n, i);
                    if (o = 1 !== a || !lt.isXMLDoc(e)) n = n.toLowerCase(), r = lt.attrHooks[n] || (Lt.test(n) ? St : kt);
                    if (i !== t)
                        if (null === i) lt.removeAttr(e, n);
                        else if (r && o && "set" in r && (s = r.set(e, i, n)) !== t) return s;
                    else return e.setAttribute(n, i + ""), i;
                    else if (r && o && "get" in r && null !== (s = r.get(e, n))) return s;
                    else {
                        if (typeof e.getAttribute !== X) s = e.getAttribute(n);
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
                            if (!It && Rt.test(n)) e[lt.camelCase("default-" + n)] = e[i] = !1;
                            else e[i] = !1;
                        else lt.attr(e, n, "");
                        e.removeAttribute(It ? n : i)
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
                        return n && n.specified ? parseInt(n.value, 10) : _t.test(e.nodeName) || jt.test(e.nodeName) && e.href ? 0 : t
                    }
                }
            }
        }), St = {
            get: function(e, n) {
                var i = lt.prop(e, n),
                    r = "boolean" == typeof i && e.getAttribute(n),
                    o = "boolean" == typeof i ? Ht && It ? null != r : Rt.test(n) ? e[lt.camelCase("default-" + n)] : !!r : e.getAttributeNode(n);
                return o && o.value !== !1 ? n.toLowerCase() : t
            },
            set: function(e, t, n) {
                if (t === !1) lt.removeAttr(e, n);
                else if (Ht && It || !Rt.test(n)) e.setAttribute(!It && lt.propFix[n] || n, n);
                else e[lt.camelCase("default-" + n)] = e[n] = !0;
                return n
            }
        }, !Ht || !It) lt.attrHooks.value = {
        get: function(e, n) {
            var i = e.getAttributeNode(n);
            return lt.nodeName(e, "input") ? e.defaultValue : i && i.specified ? i.value : t
        },
        set: function(e, t, n) {
            if (lt.nodeName(e, "input")) e.defaultValue = t;
            else return kt && kt.set(e, t, n)
        }
    };
    if (!It) kt = lt.valHooks.button = {
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
        get: kt.get,
        set: function(e, t, n) {
            kt.set(e, "" === t ? !1 : t, n)
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
        qt = /^key/,
        Mt = /^(?:mouse|contextmenu)|click/,
        Pt = /^(?:focusinfocus|focusoutblur)$/,
        Bt = /^([^.]*)(?:\.(.+)|)$/;
    if (lt.event = {
            global: {},
            add: function(e, n, i, r, o) {
                var s, a, l, u, f, c, p, d, h, g, m, y = lt._data(e);
                if (y) {
                    if (i.handler) u = i, i = u.handler, o = u.selector;
                    if (!i.guid) i.guid = lt.guid++;
                    if (!(a = y.events)) a = y.events = {};
                    if (!(c = y.handle)) c = y.handle = function(e) {
                        return typeof lt !== X && (!e || lt.event.triggered !== e.type) ? lt.event.dispatch.apply(c.elem, arguments) : t
                    }, c.elem = e;
                    for (n = (n || "").match(ft) || [""], l = n.length; l--;) {
                        if (s = Bt.exec(n[l]) || [], h = m = s[1], g = (s[2] || "").split(".").sort(), f = lt.event.special[h] || {}, h = (o ? f.delegateType : f.bindType) || h, f = lt.event.special[h] || {}, p = lt.extend({
                                type: h,
                                origType: m,
                                data: r,
                                handler: i,
                                guid: i.guid,
                                selector: o,
                                needsContext: o && lt.expr.match.needsContext.test(o),
                                namespace: g.join(".")
                            }, u), !(d = a[h]))
                            if (d = a[h] = [], d.delegateCount = 0, !f.setup || f.setup.call(e, r, g, c) === !1)
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
                var o, s, a, l, u, f, c, p, d, h, g, m = lt.hasData(e) && lt._data(e);
                if (m && (f = m.events)) {
                    for (t = (t || "").match(ft) || [""], u = t.length; u--;)
                        if (a = Bt.exec(t[u]) || [], d = g = a[1], h = (a[2] || "").split(".").sort(), d) {
                            for (c = lt.event.special[d] || {}, d = (i ? c.delegateType : c.bindType) || d, p = f[d] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = o = p.length; o--;)
                                if (s = p[o], !(!r && g !== s.origType || n && n.guid !== s.guid || a && !a.test(s.namespace) || i && i !== s.selector && ("**" !== i || !s.selector))) {
                                    if (p.splice(o, 1), s.selector) p.delegateCount--;
                                    if (c.remove) c.remove.call(e, s)
                                }
                            if (l && !p.length) {
                                if (!c.teardown || c.teardown.call(e, h, m.handle) === !1) lt.removeEvent(e, d, m.handle);
                                delete f[d]
                            }
                        } else
                            for (d in f) lt.event.remove(e, d + t[u], n, i, !0);
                    if (lt.isEmptyObject(f)) delete m.handle, lt._removeData(e, "events")
                }
            },
            trigger: function(n, i, r, o) {
                var s, a, l, u, f, c, p, d = [r || K],
                    h = st.call(n, "type") ? n.type : n,
                    g = st.call(n, "namespace") ? n.namespace.split(".") : [];
                if (l = c = r = r || K, 3 !== r.nodeType && 8 !== r.nodeType)
                    if (!Pt.test(h + lt.event.triggered)) {
                        if (h.indexOf(".") >= 0) g = h.split("."), h = g.shift(), g.sort();
                        if (a = h.indexOf(":") < 0 && "on" + h, n = n[lt.expando] ? n : new lt.Event(h, "object" == typeof n && n), n.isTrigger = !0, n.namespace = g.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, n.result = t, !n.target) n.target = r;
                        if (i = null == i ? [n] : lt.makeArray(i, [n]), f = lt.event.special[h] || {}, o || !f.trigger || f.trigger.apply(r, i) !== !1) {
                            if (!o && !f.noBubble && !lt.isWindow(r)) {
                                if (u = f.delegateType || h, !Pt.test(u + h)) l = l.parentNode;
                                for (; l; l = l.parentNode) d.push(l), c = l;
                                if (c === (r.ownerDocument || K)) d.push(c.defaultView || c.parentWindow || e)
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
                                        } catch (m) {}
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
                if (!s) this.fixHooks[r] = s = Mt.test(r) ? this.mouseHooks : qt.test(r) ? this.keyHooks : {};
                for (i = s.props ? this.props.concat(s.props) : this.props, e = new lt.Event(o), t = i.length; t--;) n = i[t], e[n] = o[n];
                if (!e.target) e.target = o.srcElement || K;
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
                    if (null == e.pageX && null != n.clientX) r = e.target.ownerDocument || K, o = r.documentElement, i = r.body, e.pageX = n.clientX + (o && o.scrollLeft || i && i.scrollLeft || 0) - (o && o.clientLeft || i && i.clientLeft || 0), e.pageY = n.clientY + (o && o.scrollTop || i && i.scrollTop || 0) - (o && o.clientTop || i && i.clientTop || 0);
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
                        if (this !== K.activeElement && this.focus) try {
                            return this.focus(), !1
                        } catch (e) {}
                    },
                    delegateType: "focusin"
                },
                blur: {
                    trigger: function() {
                        if (this === K.activeElement && this.blur) return this.blur(), !1;
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
        }, lt.removeEvent = K.removeEventListener ? function(e, t, n) {
            if (e.removeEventListener) e.removeEventListener(t, n, !1)
        } : function(e, t, n) {
            var i = "on" + t;
            if (e.detachEvent) {
                if (typeof e[i] === X) e[i] = null;
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
                if (0 === n++) K.addEventListener(e, i, !0)
            },
            teardown: function() {
                if (0 === --n) K.removeEventListener(e, i, !0)
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
                var r, o, s, a, l, u, f, d, h, g;
                if ((t ? t.ownerDocument || t : B) !== j) _(t);
                if (t = t || j, n = n || [], !e || "string" != typeof e) return n;
                if (1 !== (a = t.nodeType) && 9 !== a) return [];
                if (!R && !i) {
                    if (r = gt.exec(e))
                        if (s = r[1]) {
                            if (9 === a)
                                if (o = t.getElementById(s), o && o.parentNode) {
                                    if (o.id === s) return n.push(o), n
                                } else return n;
                            else if (t.ownerDocument && (o = t.ownerDocument.getElementById(s)) && q(t, o) && o.id === s) return n.push(o), n
                        } else if (r[2]) return J.apply(n, Q.call(t.getElementsByTagName(e), 0)), n;
                    else if ((s = r[3]) && F.getByClassName && t.getElementsByClassName) return J.apply(n, Q.call(t.getElementsByClassName(s), 0)), n;
                    if (F.qsa && !I.test(e)) {
                        if (f = !0, d = P, h = t, g = 9 === a && e, 1 === a && "object" !== t.nodeName.toLowerCase()) {
                            if (u = c(e), f = t.getAttribute("id")) d = f.replace(vt, "\\$&");
                            else t.setAttribute("id", d);
                            for (d = "[id='" + d + "'] ", l = u.length; l--;) u[l] = d + p(u[l]);
                            h = dt.test(e) && t.parentNode || t, g = u.join(",")
                        }
                        if (g) try {
                            return J.apply(n, Q.call(h.querySelectorAll(g), 0)), n
                        } catch (m) {} finally {
                            if (!f) t.removeAttribute("id")
                        }
                    }
                }
                return b(e.replace(st, "$1"), t, n, i)
            }

            function a(e, t) {
                var n = t && e,
                    i = n && (~t.sourceIndex || K) - (~e.sourceIndex || K);
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
                var n, i, r, o, a, l, u, f = U[e + " "];
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
                return t ? a.length : a ? s.error(e) : U(e, l).slice(0)
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
                    var a, l, u, f = $ + " " + o;
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

            function g(e, t, n, i, r) {
                for (var o, s = [], a = 0, l = e.length, u = null != t; l > a; a++)
                    if (o = e[a])
                        if (!n || n(o, i, r))
                            if (s.push(o), u) t.push(a);
                return s
            }

            function m(e, t, n, i, o, s) {
                if (i && !i[P]) i = m(i);
                if (o && !o[P]) o = m(o, s);
                return r(function(r, s, a, l) {
                    var u, f, c, p = [],
                        d = [],
                        h = s.length,
                        m = r || x(t || "*", a.nodeType ? [a] : a, []),
                        y = e && (r || !t) ? g(m, p, e, a, l) : m,
                        v = n ? o || (r ? e : h || i) ? [] : s : y;
                    if (n) n(y, v, a, l);
                    if (i)
                        for (u = g(v, d), i(u, [], a, l), f = u.length; f--;)
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
                    } else if (v = g(v === s ? v.splice(h, v.length) : v), o) o(null, s, v, l);
                    else J.apply(s, v)
                })
            }

            function y(e) {
                for (var t, n, i, r = e.length, o = C.relative[e[0].type], s = o || C.relative[" "], a = o ? 1 : 0, l = d(function(e) {
                        return e === t
                    }, s, !0), u = d(function(e) {
                        return Z.call(t, e) > -1
                    }, s, !0), f = [function(e, n, i) {
                        return !o && (i || n !== A) || ((t = n).nodeType ? l(e, n, i) : u(e, n, i))
                    }]; r > a; a++)
                    if (n = C.relative[e[a].type]) f = [d(h(f), n)];
                    else {
                        if (n = C.filter[e[a].type].apply(null, e[a].matches), n[P]) {
                            for (i = ++a; r > i && !C.relative[e[i].type]; i++);
                            return m(a > 1 && h(f), a > 1 && p(e.slice(0, a - 1)).replace(st, "$1"), n, i > a && y(e.slice(a, i)), r > i && y(e = e.slice(i)), r > i && p(e))
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
                            m = 0,
                            y = "0",
                            v = r && [],
                            x = null != f,
                            b = A,
                            w = r || o && C.find.TAG("*", f && a.parentNode || a),
                            T = $ += null == b ? 1 : Math.random() || .1;
                        if (x) A = a !== j && a, E = n;
                        for (; null != (c = w[y]); y++) {
                            if (o && c) {
                                for (p = 0; d = e[p++];)
                                    if (d(c, a, l)) {
                                        u.push(c);
                                        break
                                    }
                                if (x) $ = T, E = ++n
                            }
                            if (i) {
                                if (c = !d && c) m--;
                                if (r) v.push(c)
                            }
                        }
                        if (m += y, i && y !== m) {
                            for (p = 0; d = t[p++];) d(v, h, a, l);
                            if (r) {
                                if (m > 0)
                                    for (; y--;)
                                        if (!v[y] && !h[y]) h[y] = V.call(u);
                                h = g(h)
                            }
                            if (J.apply(u, h), x && !r && h.length > 0 && m + t.length > 1) s.uniqueSort(u)
                        }
                        if (x) $ = T, A = b;
                        return v
                    };
                return i ? r(a) : a
            }

            function x(e, t, n) {
                for (var i = 0, r = t.length; r > i; i++) s(e, t[i], n);
                return n
            }

            function b(e, t, n, i) {
                var r, o, s, a, l, u = c(e);
                if (!i)
                    if (1 === u.length) {
                        if (o = u[0] = u[0].slice(0), o.length > 2 && "ID" === (s = o[0]).type && 9 === t.nodeType && !R && C.relative[o[1].type]) {
                            if (t = C.find.ID(s.matches[0].replace(bt, wt), t)[0], !t) return n;
                            e = e.slice(o.shift().value.length)
                        }
                        for (r = pt.needsContext.test(e) ? 0 : o.length; r-- && (s = o[r], !C.relative[a = s.type]);)
                            if (l = C.find[a])
                                if (i = l(s.matches[0].replace(bt, wt), dt.test(o[0].type) && t.parentNode || t)) {
                                    if (o.splice(r, 1), e = i.length && p(o), !e) return J.apply(n, Q.call(i, 0)), n;
                                    break
                                }
                    }
                return S(e, u)(i, t, R, n, dt.test(e)), n
            }

            function w() {}
            var T, E, C, N, k, S, D, A, _, j, L, R, I, H, O, q, M, P = "sizzle" + -new Date,
                B = e.document,
                F = {},
                $ = 0,
                W = 0,
                z = i(),
                U = i(),
                G = i(),
                X = typeof t,
                K = 1 << 31,
                Y = [],
                V = Y.pop,
                J = Y.push,
                Q = Y.slice,
                Z = Y.indexOf || function(e) {
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
                gt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                mt = /^(?:input|select|textarea|button)$/i,
                yt = /^h\d$/i,
                vt = /'|\\/g,
                xt = /\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,
                bt = /\\([\da-fA-F]{1,6}[\x20\t\r\n\f]?|.)/g,
                wt = function(e, t) {
                    var n = "0x" + t - 65536;
                    return n !== n ? t : 0 > n ? String.fromCharCode(n + 65536) : String.fromCharCode(n >> 10 | 55296, 1023 & n | 56320)
                };
            try {
                Q.call(B.documentElement.childNodes, 0)[0].nodeType
            } catch (Tt) {
                Q = function(e) {
                    for (var t, n = []; t = this[e++];) n.push(t);
                    return n
                }
            }
            k = s.isXML = function(e) {
                var t = e && (e.ownerDocument || e).documentElement;
                return t ? "HTML" !== t.nodeName : !1
            }, _ = s.setDocument = function(e) {
                var i = e ? e.ownerDocument || e : B;
                if (i === j || 9 !== i.nodeType || !i.documentElement) return j;
                if (j = i, L = i.documentElement, R = k(i), F.tagNameNoComments = o(function(e) {
                        return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
                    }), F.attributes = o(function(e) {
                        e.innerHTML = "<select></select>";
                        var t = typeof e.lastChild.getAttribute("multiple");
                        return "boolean" !== t && "string" !== t
                    }), F.getByClassName = o(function(e) {
                        if (e.innerHTML = "<div class='hidden e'></div><div class='hidden'></div>", !e.getElementsByClassName || !e.getElementsByClassName("e").length) return !1;
                        else return e.lastChild.className = "e", 2 === e.getElementsByClassName("e").length
                    }), F.getByName = o(function(e) {
                        e.id = P + 0, e.innerHTML = "<a name='" + P + "'></a><div name='" + P + "'></div>", L.insertBefore(e, L.firstChild);
                        var t = i.getElementsByName && i.getElementsByName(P).length === 2 + i.getElementsByName(P + 0).length;
                        return F.getIdNotName = !i.getElementById(P), L.removeChild(e), t
                    }), C.attrHandle = o(function(e) {
                        return e.innerHTML = "<a href='#'></a>", e.firstChild && typeof e.firstChild.getAttribute !== X && "#" === e.firstChild.getAttribute("href")
                    }) ? {} : {
                        href: function(e) {
                            return e.getAttribute("href", 2)
                        },
                        type: function(e) {
                            return e.getAttribute("type")
                        }
                    }, F.getIdNotName) C.find.ID = function(e, t) {
                    if (typeof t.getElementById !== X && !R) {
                        var n = t.getElementById(e);
                        return n && n.parentNode ? [n] : []
                    }
                }, C.filter.ID = function(e) {
                    var t = e.replace(bt, wt);
                    return function(e) {
                        return e.getAttribute("id") === t
                    }
                };
                else C.find.ID = function(e, n) {
                    if (typeof n.getElementById !== X && !R) {
                        var i = n.getElementById(e);
                        return i ? i.id === e || typeof i.getAttributeNode !== X && i.getAttributeNode("id").value === e ? [i] : t : []
                    }
                }, C.filter.ID = function(e) {
                    var t = e.replace(bt, wt);
                    return function(e) {
                        var n = typeof e.getAttributeNode !== X && e.getAttributeNode("id");
                        return n && n.value === t
                    }
                };
                if (C.find.TAG = F.tagNameNoComments ? function(e, t) {
                        if (typeof t.getElementsByTagName !== X) return t.getElementsByTagName(e);
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
                    }, C.find.NAME = F.getByName && function(e, t) {
                        if (typeof t.getElementsByName !== X) return t.getElementsByName(name);
                        else return void 0
                    }, C.find.CLASS = F.getByClassName && function(e, t) {
                        if (typeof t.getElementsByClassName !== X && !R) return t.getElementsByClassName(e);
                        else return void 0
                    }, H = [], I = [":focus"], F.qsa = n(i.querySelectorAll)) o(function(e) {
                    if (e.innerHTML = "<select><option selected=''></option></select>", !e.querySelectorAll("[selected]").length) I.push("\\[" + et + "*(?:checked|disabled|ismap|multiple|readonly|selected|value)");
                    if (!e.querySelectorAll(":checked").length) I.push(":checked")
                }), o(function(e) {
                    if (e.innerHTML = "<input type='hidden' i=''/>", e.querySelectorAll("[i^='']").length) I.push("[*^$]=" + et + "*(?:\"\"|'')");
                    if (!e.querySelectorAll(":enabled").length) I.push(":enabled", ":disabled");
                    e.querySelectorAll("*,:x"), I.push(",.*:")
                });
                if (F.matchesSelector = n(O = L.matchesSelector || L.mozMatchesSelector || L.webkitMatchesSelector || L.oMatchesSelector || L.msMatchesSelector)) o(function(e) {
                    F.disconnectedMatch = O.call(e, "div"), O.call(e, "[s!='']:x"), H.push("!=", ot)
                });
                return I = new RegExp(I.join("|")), H = new RegExp(H.join("|")), q = n(L.contains) || L.compareDocumentPosition ? function(e, t) {
                    var n = 9 === e.nodeType ? e.documentElement : e,
                        i = t && t.parentNode;
                    return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
                } : function(e, t) {
                    if (t)
                        for (; t = t.parentNode;)
                            if (t === e) return !0;
                    return !1
                }, M = L.compareDocumentPosition ? function(e, t) {
                    var n;
                    if (e === t) return D = !0, 0;
                    if (n = t.compareDocumentPosition && e.compareDocumentPosition && e.compareDocumentPosition(t)) {
                        if (1 & n || e.parentNode && 11 === e.parentNode.nodeType) {
                            if (e === i || q(B, e)) return -1;
                            if (t === i || q(B, t)) return 1;
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
                    if (e === t) return D = !0, 0;
                    else if (!o || !s) return e === i ? -1 : t === i ? 1 : o ? -1 : s ? 1 : 0;
                    else if (o === s) return a(e, t);
                    for (n = e; n = n.parentNode;) l.unshift(n);
                    for (n = t; n = n.parentNode;) u.unshift(n);
                    for (; l[r] === u[r];) r++;
                    return r ? a(l[r], u[r]) : l[r] === B ? -1 : u[r] === B ? 1 : 0
                }, D = !1, [0, 0].sort(M), F.detectDuplicates = D, j
            }, s.matches = function(e, t) {
                return s(e, null, null, t)
            }, s.matchesSelector = function(e, t) {
                if ((e.ownerDocument || e) !== j) _(e);
                if (t = t.replace(xt, "='$1']"), !(!F.matchesSelector || R || H && H.test(t) || I.test(t))) try {
                    var n = O.call(e, t);
                    if (n || F.disconnectedMatch || e.document && 11 !== e.document.nodeType) return n
                } catch (i) {}
                return s(t, j, null, [e]).length > 0
            }, s.contains = function(e, t) {
                if ((e.ownerDocument || e) !== j) _(e);
                return q(e, t)
            }, s.attr = function(e, t) {
                var n;
                if ((e.ownerDocument || e) !== j) _(e);
                if (!R) t = t.toLowerCase();
                if (n = C.attrHandle[t]) return n(e);
                if (R || F.attributes) return e.getAttribute(t);
                else return ((n = e.getAttributeNode(t)) || e.getAttribute(t)) && e[t] === !0 ? t : n && n.specified ? n.value : null
            }, s.error = function(e) {
                throw new Error("Syntax error, unrecognized expression: " + e)
            }, s.uniqueSort = function(e) {
                var t, n = [],
                    i = 1,
                    r = 0;
                if (D = !F.detectDuplicates, e.sort(M), D) {
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
                        if (e[1] = e[1].replace(bt, wt), e[3] = (e[4] || e[5] || "").replace(bt, wt), "~=" === e[2]) e[3] = " " + e[3] + " ";
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
                        else return e = e.replace(bt, wt).toLowerCase(),
                            function(t) {
                                return t.nodeName && t.nodeName.toLowerCase() === e
                            }
                    },
                    CLASS: function(e) {
                        var t = z[e + " "];
                        return t || (t = new RegExp("(^|" + et + ")" + e + "(" + et + "|$)")) && z(e, function(e) {
                            return t.test(e.className || typeof e.getAttribute !== X && e.getAttribute("class") || "")
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
                            var u, f, c, p, d, h, g = o !== s ? "nextSibling" : "previousSibling",
                                m = t.parentNode,
                                y = a && t.nodeName.toLowerCase(),
                                v = !l && !a;
                            if (m) {
                                if (o) {
                                    for (; g;) {
                                        for (c = t; c = c[g];)
                                            if (a ? c.nodeName.toLowerCase() === y : 1 === c.nodeType) return !1;
                                        h = g = "only" === e && !h && "nextSibling"
                                    }
                                    return !0
                                }
                                if (h = [s ? m.firstChild : m.lastChild], s && v) {
                                    for (f = m[P] || (m[P] = {}), u = f[e] || [], d = u[0] === $ && u[1], p = u[0] === $ && u[2], c = d && m.childNodes[d]; c = ++d && c && c[g] || (p = d = 0) || h.pop();)
                                        if (1 === c.nodeType && ++p && c === t) {
                                            f[e] = [$, d, p];
                                            break
                                        }
                                } else if (v && (u = (t[P] || (t[P] = {}))[e]) && u[0] === $) p = u[1];
                                else
                                    for (; c = ++d && c && c[g] || (p = d = 0) || h.pop();)
                                        if ((a ? c.nodeName.toLowerCase() === y : 1 === c.nodeType) && ++p) {
                                            if (v)(c[P] || (c[P] = {}))[e] = [$, p];
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
                            i = S(e.replace(st, "$1"));
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
                        return e = e.replace(bt, wt).toLowerCase(),
                            function(t) {
                                var n;
                                do
                                    if (n = R ? t.getAttribute("xml:lang") || t.getAttribute("lang") : t.lang) return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-");
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
                        return mt.test(e.nodeName)
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
            S = s.compile = function(e, t) {
                var n, i = [],
                    r = [],
                    o = G[e + " "];
                if (!o) {
                    if (!t) t = c(e);
                    for (n = t.length; n--;)
                        if (o = y(t[n]), o[P]) i.push(o);
                        else r.push(o);
                    o = G(e, v(r, i))
                }
                return o
            }, C.pseudos.nth = C.pseudos.eq, C.filters = w.prototype = C.pseudos, C.setFilters = new w, _(), s.attr = lt.attr, lt.find = s, lt.expr = s.selectors, lt.expr[":"] = lt.expr.pseudos, lt.unique = s.uniqueSort, lt.text = s.getText, lt.isXMLDoc = s.isXML, lt.contains = s.contains
        }(e);
    var Ft = /Until$/,
        $t = /^(?:parents|prev(?:Until|All))/,
        Wt = /^.[^:#\[\.,]*$/,
        zt = lt.expr.match.needsContext,
        Ut = {
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
            if (!Ft.test(e)) i = n;
            if (i && "string" == typeof i) r = lt.filter(i, r);
            if (r = this.length > 1 && !Ut[e] ? lt.unique(r) : r, this.length > 1 && $t.test(e)) r = r.reverse();
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
    var Gt = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        Xt = / jQuery\d+="(?:null|\d+)"/g,
        Kt = new RegExp("<(?:" + Gt + ")[\\s/>]", "i"),
        Yt = /^\s+/,
        Vt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
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
        ln = p(K),
        un = ln.appendChild(K.createElement("div"));
    an.optgroup = an.option, an.tbody = an.tfoot = an.colgroup = an.caption = an.thead, an.th = an.td, lt.fn.extend({
        text: function(e) {
            return lt.access(this, function(e) {
                return e === t ? lt.text(this) : this.empty().append((this[0] && this[0].ownerDocument || K).createTextNode(e))
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
                    if (!t && 1 === n.nodeType) lt.cleanData(x(n));
                    if (n.parentNode) {
                        if (t && lt.contains(n.ownerDocument, n)) m(x(n, "script"));
                        n.parentNode.removeChild(n)
                    }
                }
            return this
        },
        empty: function() {
            for (var e, t = 0; null != (e = this[t]); t++) {
                if (1 === e.nodeType) lt.cleanData(x(e, !1));
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
                if (e === t) return 1 === n.nodeType ? n.innerHTML.replace(Xt, "") : t;
                if (!("string" != typeof e || en.test(e) || !lt.support.htmlSerialize && Kt.test(e) || !lt.support.leadingWhitespace && Yt.test(e) || an[(Jt.exec(e) || ["", ""])[1].toLowerCase()])) {
                    e = e.replace(Vt, "<$1></$2>");
                    try {
                        for (; r > i; i++)
                            if (n = this[i] || {}, 1 === n.nodeType) lt.cleanData(x(n, !1)), n.innerHTML = e;
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
                m = c - 1,
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
                    for (n = n && lt.nodeName(r, "tr"), a = lt.map(x(u, "script"), h), s = a.length; c > f; f++) {
                        if (o = u, f !== m)
                            if (o = lt.clone(o, !0, !0), s) lt.merge(a, x(o, "script"));
                        i.call(n && lt.nodeName(this[f], "table") ? d(this[f], "tbody") : this[f], o, f)
                    }
                    if (s)
                        for (l = a[a.length - 1].ownerDocument, lt.map(a, g), f = 0; s > f; f++)
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
            if (lt.support.html5Clone || lt.isXMLDoc(e) || !Kt.test("<" + e.nodeName + ">")) o = e.cloneNode(!0);
            else un.innerHTML = e.outerHTML, un.removeChild(o = un.firstChild);
            if (!(lt.support.noCloneEvent && lt.support.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || lt.isXMLDoc(e)))
                for (i = x(o), a = x(e), s = 0; null != (r = a[s]); ++s)
                    if (i[s]) v(r, i[s]);
            if (t)
                if (n)
                    for (a = a || x(e), i = i || x(o), s = 0; null != (r = a[s]); s++) y(r, i[s]);
                else y(e, o);
            if (i = x(o, "script"), i.length > 0) m(i, !l && x(e, "script"));
            return i = a = r = null, o
        },
        buildFragment: function(e, t, n, i) {
            for (var r, o, s, a, l, u, f, c = e.length, d = p(t), h = [], g = 0; c > g; g++)
                if (o = e[g], o || 0 === o)
                    if ("object" === lt.type(o)) lt.merge(h, o.nodeType ? [o] : o);
                    else if (!Zt.test(o)) h.push(t.createTextNode(o));
            else {
                for (a = a || d.appendChild(t.createElement("div")), l = (Jt.exec(o) || ["", ""])[1].toLowerCase(), f = an[l] || an._default, a.innerHTML = f[1] + o.replace(Vt, "<$1></$2>") + f[2], r = f[0]; r--;) a = a.lastChild;
                if (!lt.support.leadingWhitespace && Yt.test(o)) h.push(t.createTextNode(Yt.exec(o)[0]));
                if (!lt.support.tbody)
                    for (o = "table" === l && !Qt.test(o) ? a.firstChild : "<table>" === f[1] && !Qt.test(o) ? a : 0, r = o && o.childNodes.length; r--;)
                        if (lt.nodeName(u = o.childNodes[r], "tbody") && !u.childNodes.length) o.removeChild(u);
                for (lt.merge(h, a.childNodes), a.textContent = ""; a.firstChild;) a.removeChild(a.firstChild);
                a = d.lastChild
            }
            if (a) d.removeChild(a);
            if (!lt.support.appendChecked) lt.grep(x(h, "input"), b);
            for (g = 0; o = h[g++];)
                if (!i || -1 === lt.inArray(o, i)) {
                    if (s = lt.contains(o.ownerDocument, o), a = x(d.appendChild(o), "script"), s) m(a);
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
                            else if (typeof n.removeAttribute !== X) n.removeAttribute(a);
                            else n[a] = null;
                            Z.push(r)
                        }
                    }
        }
    });
    var fn, cn, pn, dn = /alpha\([^)]*\)/i,
        hn = /opacity\s*=\s*([^)]*)/,
        gn = /^(top|right|bottom|left)$/,
        mn = /^(none|table(?!-c[ea]).+)/,
        yn = /^margin/,
        vn = new RegExp("^(" + ut + ")(.*)$", "i"),
        xn = new RegExp("^(" + ut + ")(?!px)[a-z%]+$", "i"),
        bn = new RegExp("^([+-])=(" + ut + ")", "i"),
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
                        if (s = typeof i, "string" === s && (o = bn.exec(i))) i = (o[1] + 1) * o[2] + parseFloat(lt.css(e, n)), s = "number";
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
            if (xn.test(l) && yn.test(n)) r = u.width, o = u.minWidth, s = u.maxWidth, u.minWidth = u.maxWidth = u.width = l, l = a.width, u.width = r, u.minWidth = o, u.maxWidth = s
        }
        return l
    };
    else if (K.documentElement.currentStyle) cn = function(e) {
        return e.currentStyle
    }, pn = function(e, n, i) {
        var r, o, s, a = i || cn(e),
            l = a ? a[n] : t,
            u = e.style;
        if (null == l && u && u[n]) l = u[n];
        if (xn.test(l) && !gn.test(n)) {
            if (r = u.left, o = e.runtimeStyle, s = o && o.left) o.left = e.currentStyle.left;
            if (u.left = "fontSize" === n ? "1em" : l, l = u.pixelLeft + "px", u.left = r, s) o.left = s
        }
        return "" === l ? "auto" : l
    };
    if (lt.each(["height", "width"], function(e, t) {
            lt.cssHooks[t] = {
                get: function(e, n, i) {
                    if (n) return 0 === e.offsetWidth && mn.test(lt.css(e, "display")) ? lt.swap(e, Tn, function() {
                        return k(e, t, i)
                    }) : k(e, t, i);
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
                        if (n) return n = pn(e, t), xn.test(n) ? lt(e).position()[t] + "px" : n;
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
    var kn = /%20/g,
        Sn = /\[\]$/,
        Dn = /\r?\n/g,
        An = /^(?:submit|button|image|reset|file)$/i,
        _n = /^(?:input|select|textarea|keygen)/i;
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
                return this.name && !lt(this).is(":disabled") && _n.test(this.nodeName) && !An.test(e) && (this.checked || !tn.test(e))
            }).map(function(e, t) {
                var n = lt(this).val();
                return null == n ? null : lt.isArray(n) ? lt.map(n, function(e) {
                    return {
                        name: t.name,
                        value: e.replace(Dn, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Dn, "\r\n")
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
            for (i in e) A(i, e[i], n, o);
        return r.join("&").replace(kn, "+")
    }, lt.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(e, t) {
        lt.fn[t] = function(e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), lt.fn.hover = function(e, t) {
        return this.mouseenter(e).mouseleave(t || e)
    };
    var jn, Ln, Rn = lt.now(),
        In = /\?/,
        Hn = /#.*$/,
        On = /([?&])_=[^&]*/,
        qn = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
        Mn = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
        Pn = /^(?:GET|HEAD)$/,
        Bn = /^\/\//,
        Fn = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,
        $n = lt.fn.load,
        Wn = {},
        zn = {},
        Un = "*/".concat("*");
    try {
        Ln = Y.href
    } catch (Gn) {
        Ln = K.createElement("a"), Ln.href = "", Ln = Ln.href
    }
    jn = Fn.exec(Ln.toLowerCase()) || [], lt.fn.load = function(e, n, i) {
        if ("string" != typeof e && $n) return $n.apply(this, arguments);
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
            isLocal: Mn.test(jn[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Un,
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
        ajaxPrefilter: _(Wn),
        ajaxTransport: _(zn),
        ajax: function(e, n) {
            function i(e, n, i, r) {
                var o, c, v, x, w, E = n;
                if (2 !== b) {
                    if (b = 2, l) clearTimeout(l);
                    if (f = t, a = r || "", T.readyState = e > 0 ? 4 : 0, i) x = R(p, T, i);
                    if (e >= 200 && 300 > e || 304 === e) {
                        if (p.ifModified) {
                            if (w = T.getResponseHeader("Last-Modified")) lt.lastModified[s] = w;
                            if (w = T.getResponseHeader("etag")) lt.etag[s] = w
                        }
                        if (204 === e) o = !0, E = "nocontent";
                        else if (304 === e) o = !0, E = "notmodified";
                        else o = I(p, x), E = o.state, c = o.data, v = o.error, o = !v
                    } else if (v = E, e || !E)
                        if (E = "error", 0 > e) e = 0;
                    if (T.status = e, T.statusText = (n || E) + "", o) g.resolveWith(d, [c, E, T]);
                    else g.rejectWith(d, [T, E, v]);
                    if (T.statusCode(y), y = t, u) h.trigger(o ? "ajaxSuccess" : "ajaxError", [T, p, o ? c : v]);
                    if (m.fireWith(d, [T, E]), u)
                        if (h.trigger("ajaxComplete", [T, p]), !--lt.active) lt.event.trigger("ajaxStop")
                }
            }
            if ("object" == typeof e) n = e, e = t;
            n = n || {};
            var r, o, s, a, l, u, f, c, p = lt.ajaxSetup({}, n),
                d = p.context || p,
                h = p.context && (d.nodeType || d.jquery) ? lt(d) : lt.event,
                g = lt.Deferred(),
                m = lt.Callbacks("once memory"),
                y = p.statusCode || {},
                v = {},
                x = {},
                b = 0,
                w = "canceled",
                T = {
                    readyState: 0,
                    getResponseHeader: function(e) {
                        var t;
                        if (2 === b) {
                            if (!c)
                                for (c = {}; t = qn.exec(a);) c[t[1].toLowerCase()] = t[2];
                            t = c[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    },
                    getAllResponseHeaders: function() {
                        return 2 === b ? a : null
                    },
                    setRequestHeader: function(e, t) {
                        var n = e.toLowerCase();
                        if (!b) e = x[n] = x[n] || e, v[e] = t;
                        return this
                    },
                    overrideMimeType: function(e) {
                        if (!b) p.mimeType = e;
                        return this
                    },
                    statusCode: function(e) {
                        var t;
                        if (e)
                            if (2 > b)
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
            if (g.promise(T).complete = m.add, T.success = T.done, T.error = T.fail, p.url = ((e || p.url || Ln) + "").replace(Hn, "").replace(Bn, jn[1] + "//"), p.type = n.method || n.type || p.method || p.type, p.dataTypes = lt.trim(p.dataType || "*").toLowerCase().match(ft) || [""], null == p.crossDomain) r = Fn.exec(p.url.toLowerCase()), p.crossDomain = !(!r || r[1] === jn[1] && r[2] === jn[2] && (r[3] || ("http:" === r[1] ? 80 : 443)) == (jn[3] || ("http:" === jn[1] ? 80 : 443)));
            if (p.data && p.processData && "string" != typeof p.data) p.data = lt.param(p.data, p.traditional);
            if (j(Wn, p, n, T), 2 === b) return T;
            if (u = p.global, u && 0 === lt.active++) lt.event.trigger("ajaxStart");
            if (p.type = p.type.toUpperCase(), p.hasContent = !Pn.test(p.type), s = p.url, !p.hasContent) {
                if (p.data) s = p.url += (In.test(s) ? "&" : "?") + p.data, delete p.data;
                if (p.cache === !1) p.url = On.test(s) ? s.replace(On, "$1_=" + Rn++) : s + (In.test(s) ? "&" : "?") + "_=" + Rn++
            }
            if (p.ifModified) {
                if (lt.lastModified[s]) T.setRequestHeader("If-Modified-Since", lt.lastModified[s]);
                if (lt.etag[s]) T.setRequestHeader("If-None-Match", lt.etag[s])
            }
            if (p.data && p.hasContent && p.contentType !== !1 || n.contentType) T.setRequestHeader("Content-Type", p.contentType);
            T.setRequestHeader("Accept", p.dataTypes[0] && p.accepts[p.dataTypes[0]] ? p.accepts[p.dataTypes[0]] + ("*" !== p.dataTypes[0] ? ", " + Un + "; q=0.01" : "") : p.accepts["*"]);
            for (o in p.headers) T.setRequestHeader(o, p.headers[o]);
            if (p.beforeSend && (p.beforeSend.call(d, T, p) === !1 || 2 === b)) return T.abort();
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
                    b = 1, f.send(v, i)
                } catch (E) {
                    if (2 > b) i(-1, E);
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
            var n, i = K.head || lt("head")[0] || K.documentElement;
            return {
                send: function(t, r) {
                    if (n = K.createElement("script"), n.async = !0, e.scriptCharset) n.charset = e.scriptCharset;
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
    var Xn = [],
        Kn = /(=)\?(?=&|$)|\?\?/;
    lt.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var e = Xn.pop() || lt.expando + "_" + Rn++;
            return this[e] = !0, e
        }
    }), lt.ajaxPrefilter("json jsonp", function(n, i, r) {
        var o, s, a, l = n.jsonp !== !1 && (Kn.test(n.url) ? "url" : "string" == typeof n.data && !(n.contentType || "").indexOf("application/x-www-form-urlencoded") && Kn.test(n.data) && "data");
        if (l || "jsonp" === n.dataTypes[0]) {
            if (o = n.jsonpCallback = lt.isFunction(n.jsonpCallback) ? n.jsonpCallback() : n.jsonpCallback, l) n[l] = n[l].replace(Kn, "$1" + o);
            else if (n.jsonp !== !1) n.url += (In.test(n.url) ? "&" : "?") + n.jsonp + "=" + o;
            return n.converters["script json"] = function() {
                if (!a) lt.error(o + " was not called");
                return a[0]
            }, n.dataTypes[0] = "json", s = e[o], e[o] = function() {
                a = arguments
            }, r.always(function() {
                if (e[o] = s, n[o]) n.jsonpCallback = i.jsonpCallback, Xn.push(o);
                if (a && lt.isFunction(s)) s(a[0]);
                a = s = t
            }), "script"
        }
    });
    var Yn, Vn, Jn = 0,
        Qn = e.ActiveXObject && function() {
            var e;
            for (e in Yn) Yn[e](t, !0)
        };
    if (lt.ajaxSettings.xhr = e.ActiveXObject ? function() {
            return !this.isLocal && H() || O()
        } : H, Vn = lt.ajaxSettings.xhr(), lt.support.cors = !!Vn && "withCredentials" in Vn, Vn = lt.support.ajax = !!Vn) lt.ajaxTransport(function(n) {
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
                                        if (l.onreadystatechange = lt.noop, Qn) delete Yn[s];
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
                            if (!Yn) Yn = {}, lt(e).unload(Qn);
                            Yn[s] = i
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
        ri = [F],
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
        }), lt.Tween = $, $.prototype = {
            constructor: $,
            init: function(e, t, n, i, r, o) {
                this.elem = e, this.prop = n, this.easing = r || "swing", this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = o || (lt.cssNumber[n] ? "" : "px")
            },
            cur: function() {
                var e = $.propHooks[this.prop];
                return e && e.get ? e.get(this) : $.propHooks._default.get(this)
            },
            run: function(e) {
                var t, n = $.propHooks[this.prop];
                if (this.options.duration) this.pos = t = lt.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration);
                else this.pos = t = e;
                if (this.now = (this.end - this.start) * t + this.start, this.options.step) this.options.step.call(this.elem, this.now, this);
                if (n && n.set) n.set(this);
                else $.propHooks._default.set(this);
                return this
            }
        }, $.prototype.init.prototype = $.prototype, $.propHooks = {
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
        }, $.propHooks.scrollTop = $.propHooks.scrollLeft = {
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
        }, lt.timers = [], lt.fx = $.prototype.init, lt.fx.tick = function() {
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
                if (typeof o.getBoundingClientRect !== X) r = o.getBoundingClientRect();
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
                    for (var e = this.offsetParent || K.documentElement; e && !lt.nodeName(e, "html") && "static" === lt.css(e, "position");) e = e.offsetParent;
                    return e || K.documentElement
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
    }), define("setting/common/picScroll", ["require", "jquery"], function(require) {
        function e() {
            t()
        }

        function t() {
            var e = n(".login-item"),
                t = n(".login-item-list"),
                i = t.eq(0).width(),
                r = n(".login-right-linkleft.left"),
                o = n(".login-right-linkleft.right"),
                s = t.eq(0).width() * t.size(),
                a = 0,
                l = 3;
            e.css("width", s + "px"), r.click(function() {
                a = (a - 1 + l) % l, e.stop(!0).animate({
                    left: -i * a + "px"
                }, 500)
            }), o.click(function() {
                a = (a + 1) % l, e.stop(!0).animate({
                    left: -i * a + "px"
                }, 500)
            })
        }
        var n = require("jquery");
        return {
            init: e
        }
    }), define("common/util", [], function() {
        function e(e) {
            if (isNaN(e)) return "0.00";
            else return e = (e + "").split("."), e[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, "$1,") + (e.length > 1 ? "." + e[1] : "")
        }

        function t(e) {
            if (window.clipboardData) window.clipboardData.setData("Text", e), alert("已经复制到剪切板！\n" + e);
            else if (-1 != navigator.userAgent.indexOf("Opera")) window.location = e;
            else if (window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
                } catch (t) {
                    alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'")
                }
                var n = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
                if (!n) return;
                var i = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
                if (!i) return;
                i.addDataFlavor("text/unicode");
                var r = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString),
                    o = e;
                r.data = o, i.setTransferData("text/unicode", r, 2 * o.length);
                var s = Components.interfaces.nsIClipboard;
                if (!n) return !1;
                n.setData(i, null, s.kGlobalClipboard), alert("已经复制到剪切板！\n" + e)
            }
        }

        function n(e, t) {
            var n = null;
            return function() {
                if (!n) return n = setTimeout(function() {
                    n = null
                }, t), e.apply(this, arguments);
                else return void 0
            }
        }
        return {
            addCommas: e,
            copyToClipBoard: t,
            debounce: n
        }
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
            return "___" + A++
        }

        function r(e, t) {
            var n = new Function;
            n.prototype = t.prototype, e.prototype = new n, e.prototype.constructor = e
        }

        function o(e) {
            return _[e]
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
            if (n) a = "ts(", l = ")", p = R, d = I, i = o.defaultFilter;
            return f(e, o.variableOpen, o.variableClose, 1, function(e) {
                if (n && e.indexOf("|") < 0 && i) e += "|" + i;
                var o = e.indexOf("|"),
                    s = (o > 0 ? e.slice(0, o) : e).replace(/^\s+/, "").replace(/\s+$/, ""),
                    f = o > 0 ? e.slice(o + 1) : "",
                    h = 0 === s.indexOf("*"),
                    g = [h ? "" : a, u(s), h ? "" : l];
                if (f) {
                    f = c(f, t);
                    for (var m = f.split("|"), y = 0, v = m.length; v > y; y++) {
                        var x = m[y];
                        if (/^\s*([a-z0-9_-]+)(\((.*)\))?\s*$/i.test(x)) {
                            if (g.unshift('fs["' + RegExp.$1 + '"]('), RegExp.$3) g.push(",", RegExp.$3);
                            g.push(")")
                        }
                    }
                }
                r.push(p, g.join(""), d)
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

        function g(e, t) {
            if (!/^\s*([a-z0-9\/_-]+)\s*(\(\s*master\s*=\s*([a-z0-9\/_-]+)\s*\))?\s*/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.master = RegExp.$3, this.name = RegExp.$1, d.call(this, e, t), this.blocks = {}
        }

        function m(e, t) {
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

        function x(e, t) {
            if (!/^\s*([a-z0-9_-]+)\s*(\(([\s\S]*)\))?\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
            this.name = RegExp.$1, this.args = RegExp.$3, d.call(this, e, t), this.cloneProps = ["name", "args"]
        }

        function b(e, t) {
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

        function k(e, t) {
            M[e] = t, t.prototype.type = e
        }

        function S(e) {
            this.options = {
                commandOpen: "<!--",
                commandClose: "-->",
                commandSyntax: /^\s*(\/)?([a-z]+)\s*(?::([\s\S]*))?$/,
                variableOpen: "${",
                variableClose: "}",
                defaultFilter: "html"
            }, this.config(e), this.targets = {}, this.filters = t({}, j)
        }

        function D(e, t) {
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
                if (n && (r = M[n[2].toLowerCase()]) && "function" == typeof r) {
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
        var A = 178245,
            _ = {
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
            R = "r+=",
            I = ";",
            H = "return r;";
        if ("undefined" != typeof navigator && /msie\s*([0-9]+)/i.test(navigator.userAgent) && RegExp.$1 - 0 < 8) L = "var r=[],ri=0;", R = "r[ri++]=", H = 'return r.join("");';
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
        r(g, d), r(m, d), r(y, d), r(v, d), r(x, d), r(b, d), r(w, d), r(T, d), r(E, T), r(C, T);
        var q = {
            READING: 1,
            READED: 2,
            APPLIED: 3,
            READY: 4
        };
        y.prototype.applyMaster = g.prototype.applyMaster = function(e) {
            function t(e) {
                var i = e.children;
                if (i instanceof Array)
                    for (var r = 0, o = i.length; o > r; r++) {
                        var s = i[r];
                        if (s instanceof m && n[s.name]) s = i[r] = n[s.name];
                        t(s)
                    }
            }
            if (this.state >= q.APPLIED) return 1;
            var n = this.blocks,
                i = this.engine.targets[e];
            if (i && i.applyMaster(i.master)) return this.children = i.clone().children, t(this), this.state = q.APPLIED, 1;
            else return void 0
        }, g.prototype.isReady = function() {
            function e(i) {
                for (var r = 0, o = i.children.length; o > r; r++) {
                    var s = i.children[r];
                    if (s instanceof y) {
                        var a = t.targets[s.name];
                        n = n && a && a.isReady(t)
                    } else if (s instanceof d) e(s)
                }
            }
            if (this.state >= q.READY) return 1;
            var t = this.engine,
                n = 1;
            if (this.applyMaster(this.master)) return e(this), n && (this.state = q.READY), n;
            else return void 0
        }, g.prototype.getRenderer = function() {
            if (this.renderer) return this.renderer;
            if (this.isReady()) {
                var e = new Function("data", "engine", [O, L, this.getRendererBody(), H].join("\n")),
                    t = this.engine;
                return this.renderer = function(n) {
                    return e(n, t)
                }, this.renderer
            }
            return null
        }, g.prototype.open = function(e) {
            h(e), d.prototype.open.call(this, e), this.state = q.READING, N(this, e)
        }, v.prototype.open = b.prototype.open = function(e) {
            e.stack.top().addChild(this)
        }, m.prototype.open = function(e) {
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
            this.parent = e.stack.top(), this.target = e.target, d.prototype.open.call(this, e), this.state = q.READING, e.imp = this
        }, b.prototype.close = v.prototype.close = function() {}, y.prototype.close = function(e) {
            d.prototype.close.call(this, e), this.state = q.READED, e.imp = null
        }, g.prototype.close = function(e) {
            d.prototype.close.call(this, e), this.state = this.master ? q.READED : q.APPLIED, e.target = null
        }, y.prototype.autoClose = function(e) {
            var t = this.parent.children;
            t.push.apply(t, this.children), this.children.length = 0;
            for (var n in this.blocks) this.target.blocks[n] = this.blocks[n];
            this.blocks = {}, this.close(e)
        }, b.prototype.beforeOpen = y.prototype.beforeOpen = v.prototype.beforeOpen = w.prototype.beforeOpen = x.prototype.beforeOpen = m.prototype.beforeOpen = T.prototype.beforeOpen = p.prototype.beforeAdd = function(e) {
            if (!e.stack.bottom()) {
                var t = new g(i(), e.engine);
                t.open(e)
            }
        }, y.prototype.getRendererBody = function() {
            return this.applyMaster(this.name), d.prototype.getRendererBody.call(this)
        }, b.prototype.getRendererBody = function() {
            return l("{0}engine.render({2},{{3}}){1}", R, I, s(this.name), c(this.args, this.engine).replace(/(^|,)\s*([a-z0-9_]+)\s*=/gi, function(e, t, n) {
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
        }, x.prototype.getRendererBody = function() {
            var e = this.args;
            return l("{2}fs[{5}]((function(){{0}{4}{1}})(){6}){3}", L, H, R, I, d.prototype.getRendererBody.call(this), s(this.name), e ? "," + c(e, this.engine) : "")
        };
        var M = {};
        k("target", g), k("block", m), k("import", y), k("use", b), k("var", v), k("for", w), k("if", T), k("elif", E), k("else", C), k("filter", x), S.prototype.config = function(e) {
            t(this.options, e)
        }, S.prototype.compile = S.prototype.parse = function(e) {
            if (e) {
                var t = D(e, this);
                if (t.length) return this.targets[t[0]].getRenderer()
            }
            return new Function('return ""')
        }, S.prototype.getRenderer = function(e) {
            var t = this.targets[e];
            if (t) return t.getRenderer();
            else return void 0
        }, S.prototype.render = function(e, t) {
            var n = this.getRenderer(e);
            if (n) return n(t);
            else return ""
        }, S.prototype.addFilter = function(e, t) {
            if ("function" == typeof t) this.filters[e] = t
        };
        var P = new S;
        if (P.Engine = S, "object" == typeof exports && "object" == typeof module) exports = module.exports = P;
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
            loginRegister:i
        }
    }), define("common/ui/Dialog/dialog.tpl", [], function() {
        return '<!--* @ignore* @file dialog* @author mySunShinning(441984145@qq.com)*         yangbinYB(1033371745@qq.com)* @time 15-1-2--><!-- target: dialogWarp --><div id="mk-dialog"><div class="bg" style="width:${width}px;"><!-- if: ${defaultTitle}--><div class="title"><span>${title}</span></div><!-- /if --><a id="popup-close" class="close" title="关闭">╳</a>${content|raw}</div></div><!-- if: ${mask} --><div id="mk-dialog-mask" class="mask"></div><!-- else --><div id="mk-dialog-mask"></div><!-- /if -->'
    }), define("common/ui/Dialog/Dialog", ["require", "jquery", "etpl", "./dialog.tpl"], function(require) {
        function e(e, t) {
            var n = Object.prototype.toString.call(t).slice(8, -1);
            return void 0 !== t && null !== t && n === e
        }

        function t(t) {
            return e("Function", t)
        }

        function n() {
            l.compile(u)
        }

        function i(e, t) {
            var n = a.extend({}, f, e);
            n.width = (n.width + "").replace(/px$/, ""), c.options = n, a("body").append(l.render("dialogWarp", n)), r(), s(), t && t()
        }

        function r() {
            if (c.popupMask = a("#mk-dialog-mask"), c.popup = a("#mk-dialog"), c.options.mask) c.popupMask.addClass("mask"), c.popup.addClass("mask");
            c.popupMask.css({
                width: a(document).width(),
                height: a(document).height()
            });
            var e = c.popup.width(),
                t = c.popup.height(),
                n = a(window).scrollTop() + Math.round((a(window).height() - t) / 2),
                i = a(window).scrollLeft() + Math.round((a(window).width() - e) / 2);
            c.popup.css({
                top: n + "px",
                left: i + "px"
            })
        }

        function o(e) {
            c.popup && c.popup.remove(), c.popupMask && c.popupMask.remove(), e && t(e) && e.call(null)
        }

        function s() {
            a(window).on("resize", function() {
                r()
            }), a("#popup-close").on("click", o)
        }
        var a = require("jquery"),
            l = require("etpl"),
            u = require("./dialog.tpl"),
            f = {
                mask: !0,
                width: 200,
                defaultTitle: !0,
                title: "",
                content: ""
            },
            c = {
                options: {},
                popupMask: null,
                popup: null
            };
        return {
            init: n,
            show: i,
            closePopup: o
        }
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
                COMPANY_INFOS_LIST: e + "/infos/infoapi",
                USER_REGISTAPI_MODIFYPWD: e + "/user/registapi/modifypwd",
                LOAN_REQUEST: e + "/loan/request"
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
    }), define("setting/login/index", ["require", "jquery", "../common/picScroll", "common/header", "common/Remoter", "common/config"], function(require) {
        function e(e) {
            i = e || "login",
            // s.init(),
              o.init(), 
              t(), 
              n()
        }

        function t() {
            r(".login-username .login-input").on({
                focus: function() {
                    var e = r(this).parent(),
                        t = e.children(".username-error");
                    e.removeClass("current"), t.html(""), r(this).next().addClass("hidden")
                },
                blur: function() {
                    var e = r.trim(r(this).val());
                    !e && r(this).next().removeClass("hidden")
                }
            }), r("#login-testing").focus(function() {
                p.html("")
            }), d.click(function(e) {
                e.preventDefault(), r(this).attr("src", c + i)
            }), r(".login .login-fastlogin").click(function(e) {
                e.preventDefault();
                var t = r.trim(r("#login-user").val()),
                    n = r.trim(r("#login-pwd").val()),
                    o = r.trim(r("#login-testing").val());
                if (!t || !n) return void p.html("用户名或密码不能为空");
                if (!r(".login-username").hasClass("login-display-none")) {
                    if (!o) return void p.html("验证码不能为空");
                    u.remote({
                        name: t,
                        passwd: n,
                        imagecode: o,
                        type: i,
                        isthird: "login" === i ? 0 : 1
                    })
                } else u.remote({
                    name: t,
                    passwd: n,
                    type: i,
                    isthird: "login" === i ? 0 : 1
                })
            }), r("#login-pwd").keyup(function(e) {
                if (13 === e.keyCode) r(".login-fastlogin").trigger("click"), r(this).trigger("blur")
            }), r("#login-testing").keyup(function(e) {
                if (13 === e.keyCode) r(".login-fastlogin").trigger("click"), r(this).trigger("blur")
            })
        }

        function n() {
            u.on("success", function(e) {
                if (e.imgCode) {
                    var t = r(".login-display-none:eq(0)");
                    t.removeClass("login-display-none"), d.attr("src", e.data.url)
                } else if (e.bizError) r("#login-error").html(e.statusInfo), d.trigger("click");
                else window.location.href = "/account/overview/index"
            }), f.on("success", function(e) {
                if (e.bizError) alert(e.statusInfo);
                else r("#login-testing-error").html('<span class="username-error-span"></span>')
            })
        }
        var i, r = require("jquery"),
            o = require("../common/picScroll"),
            s = require("common/header"),
            a = require("common/Remoter"),
            l = require("common/config"),
            u = new a("LOGIN_INDEX_CHECK"),
            f = new a("LOGIN_IMGCODE_CHECK"),
            c = l.URL.IMG_GET,
            p = r("#login-error"),
            d = r("#login-img-url");
        return {
            init: e
        }
    }), define("setting/regist/regist.tpl", [], function() {
        return '<!-- target: fixBox --><div class="login-left-box login"><div class="login-left-title">账户绑定</div><div id="login-error" class="login-error"></div><div class="login-username"><input autocomplete="off" class="login-input" id="login-user" type="text" /><label class="user-lable" for="login-user">用户名</label></div><div class="login-username"><input autocomplete="off" class="login-input" id="login-pwd" type="password" /><label class="user-lable" for="login-pwd">密码</label></div><div class="login-username login-display-none"><input autocomplete="off" class="login-input testing" id="login-testing" type="text" /><label class="user-lable" for="login-testing">验证码</label><a href="###" class="login-username-testing"><img id="login-img-url" src="#" width="162" height="35" /></a><div class="username-error yanzma" id="login-testing-error"></div></div><a class="login-fastlogin">确定</a></div>'
    }), define("setting/regist/index", ["require", "jquery", "../common/picScroll", "common/util", "common/header", "common/ui/Dialog/Dialog", "setting/login/index", "etpl", "./regist.tpl", "common/Remoter"], function(require) {
        function e(e) {
            r = e ? 1 : 0, f.compile(c), s.init(), l.init(), t(), n(), i()
        }

        function t() {
            o(".regist .login-input").on({
                focus: function() {
                    var e = o(this).parent(),
                        t = e.children(".username-error"),
                        n = o(this).attr("id"),
                        i = e.find("#" + n + "-point"),
                        r = e.find(".username-point");
                    e.removeClass("current"), t.html(""), i.html(""), r.show(), o(this).next().addClass("hidden")
                },
                blur: function() {
                    var e = o(this).parent(),
                        t = o.trim(o(this).val()),
                        n = o(this).attr("data-text"),
                        i = o(this).attr("id");
                    if (!t) {
                        if (!o(this).hasClass("login-tuijian")) e.addClass("current"), o("#" + i + "-error").html(n + "不能为空");
                        o(this).next().removeClass("hidden")
                    }
                }
            }), w.loginUser.blur(function() {
                var e = o.trim(o(this).val());
                if (e) d.remote({
                    name: e
                });
                else C.user = 0
            }), w.loginPwd.blur(function() {
                var e = o.trim(o(this).val());
                if (!e) return void(C.pwd = 0);
                if (!x.test(e)) return C.pwd = 0, o(this).parent().addClass("current"), void T.pwd2Error.html("密码只能为 6 - 32 位数字，字母及常用符号组成");
                else return C.pwd = 1, E.pwdPointTip.hide(), void E.pwdPointIcon.html(b)
            }), w.loginPwd2.blur(function() {
                var e = o.trim(o(w.loginPwd).val()),
                    t = o.trim(o(this).val());
                if (!t) return void(C.pwd = 0);
                if (t != e) return C.pwd = 0, o(this).parent().addClass("current"), void T.pwd2Error.html("两次输入的密码不一致 ");
                else return C.pwd = 1, E.pwd2PointTip.hide(), void E.pwd2PointIcon.html(b)
            }), w.loginPhone.blur(function() {
                var e = o.trim(o(this).val());
                if (e) h.remote({
                    phone: e
                });
                else C.phone = 0, o(".login-username-testing").addClass("disabled")
            }), w.loginTest.blur(function() {
                var e = o.trim(o(this).val()),
                    t = o.trim(w.loginPhone.val());
                if (!t) return w.loginPhone.trigger("blur"), void(C.vericode = 0);
                if (e) y.remote({
                    vericode: e,
                    phone: t,
                    type: "regist"
                });
                else C.vericode = 0
            }), o(".login-username-testing").click(a.debounce(function(e) {
                e.preventDefault();
                var t = o.trim(w.loginPhone.val());
                if (!o(this).hasClass("disabled") && t) g.remote({
                    phone: t,
                    type: "regist"
                })
            }, 1e3)), w.loginTuiJian.blur(function() {
                var e = o.trim(o(this).val());
                if (e) m.remote({
                    inviter: e
                })
            }), o(".regist .login-fastlogin").click(a.debounce(function(e) {
                e.preventDefault();
                var t = 1;
                if (!o("#tiaoyue-itp")[0].checked) return void alert("请同意用户条约");
                for (var n in C)
                    if (C.hasOwnProperty(n))
                        if (!C[n]) w[N[n]].trigger("blur"), t = 0;
                t && v.remote({
                    name: w.loginUser.val(),
                    passwd: w.loginPwd.val(),
                    phone: w.loginPhone.val(),
                    inviter: w.loginTuiJian.val(),
                    vericode: w.loginTest.val(),
                    isthird: r
                })
            }, 1e3))
        }

        function n() {
            var e;
            d.on("success", function(e) {
                if (e && e.bizError) w.loginUser.parent().addClass("current"), T.userError.html(e.statusInfo), C.user = 0;
                else E.userPointTip.hide(), E.userPointIcon.html(b), C.user = 1
            }), h.on("success", function(e) {
                if (e && e.bizError) w.loginPhone.parent().addClass("current"), T.phoneError.html(e.statusInfo), C.phone = 0, o(".login-username-testing").addClass("disabled");
                else E.phonePointTip.hide(), E.phonePointIcon.html(b), C.phone = 1, o(".login-username-testing").removeClass("disabled")
            }), m.on("success", function(e) {
                if (e && e.bizError) w.loginTuiJian.parent().addClass("current"), T.tuiJianError.html(e.statusInfo), C.tui = 0;
                else E.tuiJianPointTip.hide(), E.tuiJianPoint.html(b), C.tui = 1
            }), g.on("success", function(t) {
                var n = 60;
                if (t && t.bizError) alert(t.statusInfo);
                else {
                    var i = o("#testing-wait"),
                        r = o(".login-username-testing"),
                        s = o(".testTip");
                    i.text("60秒后重新获取验证码"), i.addClass("show"), r.addClass("login-username-testing-wait"), r.addClass("disabled"), s.hide(), e = setInterval(function() {
                        if (i.text(--n + "秒后重新获取验证码"), 0 > n) clearInterval(e), i.removeClass("show"), r.removeClass("disabled"), r.removeClass("login-username-testing-wait")
                    }, 1e3)
                }
            }), y.on("success", function(e) {
                if (e && e.bizError) w.loginTest.parent().addClass("current"), T.testError.html(e.statusInfo), C.vericode = 0;
                else E.testPointTip.hide(), E.testPointIcon.html(b), C.vericode = 1
            }), v.on("success", function(e) {
                if (e && e.bizError) alert(e.statusInfo);
                else window.location.href = "/user/open/index"
            }), o(".fix-box-register").click(function() {
                l.show({
                    width: 500,
                    defaultTitle: !1,
                    content: f.render("fixBox")
                }), u.init("registBind")
            })
        }

        function i() {
            var e = o(".login-username-testing").not("disabled");
            e.on("mouseenter", function() {
                if (!e.hasClass("disabled")) o(".testTip").show(200)
            }).on("mouseleave", function() {
                if (!e.hasClass("disabled")) o(".testTip").hide(200)
            })
        }
        var r, o = require("jquery"),
            s = require("../common/picScroll"),
            a = require("common/util"),
            l = (require("common/header"), require("common/ui/Dialog/Dialog")),
            u = require("setting/login/index"),
            f = require("etpl"),
            c = require("./regist.tpl"),
            p = require("common/Remoter"),
            d = new p("REGIST_CHECKNAME_CHECK"),
            h = new p("REGIST_CHECKPHONE_CHECK"),
            g = new p("REGIST_SENDSMSCODE_CHECK"),
            m = new p("REGIST_CHECKINVITER_CHECK"),
            y = new p("REGIST_CHECKSMSCODE_CHECK"),
            v = new p("REGIST_INDEX_CHECK"),
            x = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/,
            b = '<span class="username-error-span"></span>',
            w = {
                loginUser: o("#regist-user"),
                loginPwd: o("#regist-pwd"),
                loginPwd2: o("#regist-pwd2"),
                loginPhone: o("#regist-phone"),
                loginTest: o("#regist-testing"),
                loginTuiJian: o("#regist-tuijian")
            },
            T = {
                userError: o("#regist-user-error"),
                phoneError: o("#regist-phone-error"),
                pwd2Error: o("#regist-pwd2-error"),
                pwdError: o("#regist-pwd-error"),
                testError: o("#regist-testing-error"),
                tuiJianError: o("#regist-tuijian-error")
            },
            E = {
                userPointIcon: o("#regist-user-point"),
                phonePointIcon: o("#regist-phone-point"),
                pwdPointIcon: o("#regist-pwd-point"),
                pwd2PointIcon: o("#regist-pwd2-point"),
                testPointIcon: o("#regist-testing-point"),
                tuiJianPointIcon: o("#regist-tuijian-point"),
                userPointTip: o("#regist-user-point").next(),
                phonePointTip: o("#regist-phone-point").next(),
                pwdPointTip: o("#regist-pwd-point").next(),
                pwd2PointTip: o("#regist-pwd2-point").next(),
                testPointTip: o("#regist-testing-point").next(),
                tuiJianPointTip: o("#regist-tuijian-point").next()
            },
            C = {
                user: 0,
                phone: 0,
                pwd: 0,
                vericode: 0,
                tui: 1
            },
            N = {
                user: "loginUser",
                phone: "loginPhone",
                pwd: "loginPwd",
                vericode: "loginTest",
                tui: "loginTuiJian"
            };
        return {
            init: e
        }
    });
