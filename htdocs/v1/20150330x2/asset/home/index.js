! function(e, t) {
	function n(e) {
		var t = e.length,
			n = ut.type(e);
		if (ut.isWindow(e)) return !1;
		if (1 === e.nodeType && t) return !0;
		else return "array" === n || "function" !== n && (0 === t || "number" == typeof t && t > 0 && t - 1 in e)
	}

	function i(e) {
		var t = Et[e] = {};
		return ut.each(e.match(ft) || [], function(e, n) {
			t[n] = !0
		}), t
	}

	function r(e, n, i, r) {
		if (ut.acceptData(e)) {
			var o, s, a = ut.expando,
				u = "string" == typeof n,
				l = e.nodeType,
				f = l ? ut.cache : e,
				c = l ? e[a] : e[a] && a;
			if (c && f[c] && (r || f[c].data) || !u || i !== t) {
				if (!c)
					if (l) e[a] = c = Z.pop() || ut.guid++;
					else c = a;
				if (!f[c])
					if (f[c] = {}, !l) f[c].toJSON = ut.noop;
				if ("object" == typeof n || "function" == typeof n)
					if (r) f[c] = ut.extend(f[c], n);
					else f[c].data = ut.extend(f[c].data, n);
				if (o = f[c], !r) {
					if (!o.data) o.data = {};
					o = o.data
				}
				if (i !== t) o[ut.camelCase(n)] = i;
				if (u) {
					if (s = o[n], null == s) s = o[ut.camelCase(n)]
				} else s = o;
				return s
			}
		}
	}

	function o(e, t, n) {
		if (ut.acceptData(e)) {
			var i, r, o, s = e.nodeType,
				u = s ? ut.cache : e,
				l = s ? e[ut.expando] : ut.expando;
			if (u[l]) {
				if (t)
					if (o = n ? u[l] : u[l].data) {
						if (!ut.isArray(t))
							if (t in o) t = [t];
							else if (t = ut.camelCase(t), t in o) t = [t];
						else t = t.split(" ");
						else t = t.concat(ut.map(t, ut.camelCase));
						for (i = 0, r = t.length; r > i; i++) delete o[t[i]];
						if (!(n ? a : ut.isEmptyObject)(o)) return
					}
				if (!n)
					if (delete u[l].data, !a(u[l])) return;
				if (s) ut.cleanData([e], !0);
				else if (ut.support.deleteExpando || u != u.window) delete u[l];
				else u[l] = null
			}
		}
	}

	function s(e, n, i) {
		if (i === t && 1 === e.nodeType) {
			var r = "data-" + n.replace(Nt, "-$1").toLowerCase();
			if (i = e.getAttribute(r), "string" == typeof i) {
				try {
					i = "true" === i ? !0 : "false" === i ? !1 : "null" === i ? null : +i + "" === i ? +i : Ct.test(i) ? ut.parseJSON(i) : i
				} catch (o) {}
				ut.data(e, n, i)
			} else i = t
		}
		return i
	}

	function a(e) {
		var t;
		for (t in e)
			if ("data" !== t || !ut.isEmptyObject(e[t])) {
				if ("toJSON" !== t) return !1
			} else;
		return !0
	}

	function u() {
		return !0
	}

	function l() {
		return !1
	}

	function f(e, t) {
		do e = e[t]; while (e && 1 !== e.nodeType);
		return e
	}

	function c(e, t, n) {
		if (t = t || 0, ut.isFunction(t)) return ut.grep(e, function(e, i) {
			var r = !!t.call(e, i, e);
			return r === n
		});
		else if (t.nodeType) return ut.grep(e, function(e) {
			return e === t === n
		});
		else if ("string" == typeof t) {
			var i = ut.grep(e, function(e) {
				return 1 === e.nodeType
			});
			if (Wt.test(t)) return ut.filter(t, i, !n);
			else t = ut.filter(t, i)
		}
		return ut.grep(e, function(e) {
			return ut.inArray(e, t) >= 0 === n
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
		for (var n, i = 0; null != (n = e[i]); i++) ut._data(n, "globalEval", !t || ut._data(t[i], "globalEval"))
	}

	function y(e, t) {
		if (1 === t.nodeType && ut.hasData(e)) {
			var n, i, r, o = ut._data(e),
				s = ut._data(t, o),
				a = o.events;
			if (a) {
				delete s.handle, s.events = {};
				for (n in a)
					for (i = 0, r = a[n].length; r > i; i++) ut.event.add(t, n, a[n][i])
			}
			if (s.data) s.data = ut.extend({}, s.data)
		}
	}

	function v(e, t) {
		var n, i, r;
		if (1 === t.nodeType) {
			if (n = t.nodeName.toLowerCase(), !ut.support.noCloneEvent && t[ut.expando]) {
				r = ut._data(t);
				for (i in r.events) ut.removeEvent(t, i, r.handle);
				t.removeAttribute(ut.expando)
			}
			if ("script" === n && t.text !== e.text) h(t).text = e.text, g(t);
			else if ("object" === n) {
				if (t.parentNode) t.outerHTML = e.outerHTML;
				if (ut.support.html5Clone && e.innerHTML && !ut.trim(t.innerHTML)) t.innerHTML = e.innerHTML
			} else if ("input" === n && tn.test(e.type)) {
				if (t.defaultChecked = t.checked = e.checked, t.value !== e.value) t.value = e.value
			} else if ("option" === n) t.defaultSelected = t.selected = e.defaultSelected;
			else if ("input" === n || "textarea" === n) t.defaultValue = e.defaultValue
		}
	}

	function b(e, n) {
		var i, r, o = 0,
			s = typeof e.getElementsByTagName !== X ? e.getElementsByTagName(n || "*") : typeof e.querySelectorAll !== X ? e.querySelectorAll(n || "*") : t;
		if (!s)
			for (s = [], i = e.childNodes || e; null != (r = i[o]); o++)
				if (!n || ut.nodeName(r, n)) s.push(r);
				else ut.merge(s, b(r, n));
		return n === t || n && ut.nodeName(e, n) ? ut.merge([e], s) : s
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
		return e = t || e, "none" === ut.css(e, "display") || !ut.contains(e.ownerDocument, e)
	}

	function E(e, t) {
		for (var n, i, r, o = [], s = 0, a = e.length; a > s; s++)
			if (i = e[s], i.style) {
				if (o[s] = ut._data(i, "olddisplay"), n = i.style.display, t) {
					if (!o[s] && "none" === n) i.style.display = "";
					if ("" === i.style.display && T(i)) o[s] = ut._data(i, "olddisplay", _(i.nodeName))
				} else if (!o[s])
					if (r = T(i), n && "none" !== n || !r) ut._data(i, "olddisplay", r ? n : ut.css(i, "display"))
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
			if ("margin" === n) s += ut.css(e, n + Cn[o], !0, r);
			if (i) {
				if ("content" === n) s -= ut.css(e, "padding" + Cn[o], !0, r);
				if ("margin" !== n) s -= ut.css(e, "border" + Cn[o] + "Width", !0, r)
			} else if (s += ut.css(e, "padding" + Cn[o], !0, r), "padding" !== n) s += ut.css(e, "border" + Cn[o] + "Width", !0, r)
		}
		return s
	}

	function k(e, t, n) {
		var i = !0,
			r = "width" === t ? e.offsetWidth : e.offsetHeight,
			o = cn(e),
			s = ut.support.boxSizing && "border-box" === ut.css(e, "boxSizing", !1, o);
		if (0 >= r || null == r) {
			if (r = pn(e, t, o), 0 > r || null == r) r = e.style[t];
			if (bn.test(r)) return r;
			i = s && (ut.support.boxSizingReliable || r === e.style[t]), r = parseFloat(r) || 0
		}
		return r + N(e, t, n || (s ? "border" : "content"), i, o) + "px"
	}

	function _(e) {
		var t = U,
			n = wn[e];
		if (!n) {
			if (n = S(e, t), "none" === n || !n) fn = (fn || ut("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(t.documentElement), t = (fn[0].contentWindow || fn[0].contentDocument).document, t.write("<!doctype html><html><body>"), t.close(), n = S(e, t), fn.detach();
			wn[e] = n
		}
		return n
	}

	function S(e, t) {
		var n = ut(t.createElement(e)).appendTo(t.body),
			i = ut.css(n[0], "display");
		return n.remove(), i
	}

	function D(e, t, n, i) {
		var r;
		if (ut.isArray(t)) ut.each(t, function(t, r) {
			if (n || _n.test(e)) i(e, r);
			else D(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
		});
		else if (!n && "object" === ut.type(t))
			for (r in t) D(e + "[" + r + "]", t[r], n, i);
		else i(e, t)
	}

	function A(e) {
		return function(t, n) {
			if ("string" != typeof t) n = t, t = "*";
			var i, r = 0,
				o = t.toLowerCase().match(ft) || [];
			if (ut.isFunction(n))
				for (; i = o[r++];)
					if ("+" === i[0]) i = i.slice(1) || "*", (e[i] = e[i] || []).unshift(n);
					else(e[i] = e[i] || []).push(n)
		}
	}

	function j(e, t, n, i) {
		function r(a) {
			var u;
			return o[a] = !0, ut.each(e[a] || [], function(e, a) {
				var l = a(t, n, i);
				if ("string" == typeof l && !s && !o[l]) return t.dataTypes.unshift(l), r(l), !1;
				else if (s) return !(u = l)
			}), u
		}
		var o = {},
			s = e === zn;
		return r(t.dataTypes[0]) || !o["*"] && r("*")
	}

	function L(e, n) {
		var i, r, o = ut.ajaxSettings.flatOptions || {};
		for (r in n)
			if (n[r] !== t)(o[r] ? e : i || (i = {}))[r] = n[r];
		if (i) ut.extend(!0, e, i);
		return e
	}

	function M(e, n, i) {
		var r, o, s, a, u = e.contents,
			l = e.dataTypes,
			f = e.responseFields;
		for (a in f)
			if (a in i) n[f[a]] = i[a];
		for (;
			"*" === l[0];)
			if (l.shift(), o === t) o = e.mimeType || n.getResponseHeader("Content-Type");
		if (o)
			for (a in u)
				if (u[a] && u[a].test(o)) {
					l.unshift(a);
					break
				}
		if (l[0] in i) s = l[0];
		else {
			for (a in i) {
				if (!l[0] || e.converters[a + " " + l[0]]) {
					s = a;
					break
				}
				if (!r) r = a
			}
			s = s || r
		}
		if (s) {
			if (s !== l[0]) l.unshift(s);
			return i[s]
		}
	}

	function R(e, t) {
		var n, i, r, o, s = {},
			a = 0,
			u = e.dataTypes.slice(),
			l = u[0];
		if (e.dataFilter) t = e.dataFilter(t, e.dataType);
		if (u[1])
			for (r in e.converters) s[r.toLowerCase()] = e.converters[r];
		for (; i = u[++a];)
			if ("*" !== i) {
				if ("*" !== l && l !== i) {
					if (r = s[l + " " + i] || s["* " + i], !r)
						for (n in s)
							if (o = n.split(" "), o[1] === i)
								if (r = s[l + " " + o[0]] || s["* " + o[0]]) {
									if (r === !0) r = s[n];
									else if (s[n] !== !0) i = o[0], u.splice(a--, 0, i);
									break
								}
					if (r !== !0)
						if (r && e["throws"]) t = r(t);
						else try {
							t = r(t)
						} catch (f) {
							return {
								state: "parsererror",
								error: r ? f : "No conversion from " + l + " to " + i
							}
						}
				}
				l = i
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

	function I() {
		return setTimeout(function() {
			Zn = t
		}), Zn = ut.now()
	}

	function q(e, t) {
		ut.each(t, function(t, n) {
			for (var i = (oi[t] || []).concat(oi["*"]), r = 0, o = i.length; o > r; r++)
				if (i[r].call(e, t, n)) return
		})
	}

	function P(e, t, n) {
		var i, r, o = 0,
			s = ri.length,
			a = ut.Deferred().always(function() {
				delete u.elem
			}),
			u = function() {
				if (r) return !1;
				for (var t = Zn || I(), n = Math.max(0, l.startTime + l.duration - t), i = n / l.duration || 0, o = 1 - i, s = 0, u = l.tweens.length; u > s; s++) l.tweens[s].run(o);
				if (a.notifyWith(e, [l, o, n]), 1 > o && u) return n;
				else return a.resolveWith(e, [l]), !1
			},
			l = a.promise({
				elem: e,
				props: ut.extend({}, t),
				opts: ut.extend(!0, {
					specialEasing: {}
				}, n),
				originalProperties: t,
				originalOptions: n,
				startTime: Zn || I(),
				duration: n.duration,
				tweens: [],
				createTween: function(t, n) {
					var i = ut.Tween(e, l.opts, t, n, l.opts.specialEasing[t] || l.opts.easing);
					return l.tweens.push(i), i
				},
				stop: function(t) {
					var n = 0,
						i = t ? l.tweens.length : 0;
					if (r) return this;
					for (r = !0; i > n; n++) l.tweens[n].run(1);
					if (t) a.resolveWith(e, [l, t]);
					else a.rejectWith(e, [l, t]);
					return this
				}
			}),
			f = l.props;
		for (F(f, l.opts.specialEasing); s > o; o++)
			if (i = ri[o].call(l, e, f, l.opts)) return i;
		if (q(l, f), ut.isFunction(l.opts.start)) l.opts.start.call(e, l);
		return ut.fx.timer(ut.extend(u, {
			elem: e,
			anim: l,
			queue: l.opts.queue
		})), l.progress(l.opts.progress).done(l.opts.done, l.opts.complete).fail(l.opts.fail).always(l.opts.always)
	}

	function F(e, t) {
		var n, i, r, o, s;
		for (r in e) {
			if (i = ut.camelCase(r), o = t[i], n = e[r], ut.isArray(n)) o = n[1], n = e[r] = n[0];
			if (r !== i) e[i] = n, delete e[r];
			if (s = ut.cssHooks[i], s && "expand" in s) {
				n = s.expand(n), delete e[i];
				for (r in n)
					if (!(r in e)) e[r] = n[r], t[r] = o
			} else t[i] = o
		}
	}

	function $(e, t, n) {
		var i, r, o, s, a, u, l, f, c, p = this,
			d = e.style,
			h = {},
			g = [],
			m = e.nodeType && T(e);
		if (!n.queue) {
			if (f = ut._queueHooks(e, "fx"), null == f.unqueued) f.unqueued = 0, c = f.empty.fire, f.empty.fire = function() {
				if (!f.unqueued) c()
			};
			f.unqueued++, p.always(function() {
				p.always(function() {
					if (f.unqueued--, !ut.queue(e, "fx").length) f.empty.fire()
				})
			})
		}
		if (1 === e.nodeType && ("height" in t || "width" in t))
			if (n.overflow = [d.overflow, d.overflowX, d.overflowY], "inline" === ut.css(e, "display") && "none" === ut.css(e, "float"))
				if (!ut.support.inlineBlockNeedsLayout || "inline" === _(e.nodeName)) d.display = "inline-block";
				else d.zoom = 1;
		if (n.overflow)
			if (d.overflow = "hidden", !ut.support.shrinkWrapBlocks) p.always(function() {
				d.overflow = n.overflow[0], d.overflowX = n.overflow[1], d.overflowY = n.overflow[2]
			});
		for (r in t)
			if (s = t[r], ti.exec(s)) {
				if (delete t[r], u = u || "toggle" === s, s === (m ? "hide" : "show")) continue;
				g.push(r)
			}
		if (o = g.length) {
			if (a = ut._data(e, "fxshow") || ut._data(e, "fxshow", {}), "hidden" in a) m = a.hidden;
			if (u) a.hidden = !m;
			if (m) ut(e).show();
			else p.done(function() {
				ut(e).hide()
			});
			for (p.done(function() {
					var t;
					ut._removeData(e, "fxshow");
					for (t in h) ut.style(e, t, h[t])
				}), r = 0; o > r; r++)
				if (i = g[r], l = p.createTween(i, m ? a[i] : 0), h[i] = a[i] || ut.style(e, i), !(i in a))
					if (a[i] = l.start, m) l.end = l.start, l.start = "width" === i || "height" === i ? 1 : 0
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
		return ut.isWindow(e) ? e : 9 === e.nodeType ? e.defaultView || e.parentWindow : !1
	}
	var Y, G, X = typeof t,
		U = e.document,
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
		ut = function(e, t) {
			return new ut.fn.init(e, t, G)
		},
		lt = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
		ft = /\S+/g,
		ct = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
		pt = /^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/,
		dt = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
		ht = /^[\],:{}\s]*$/,
		gt = /(?:^|:|,)(?:\s*\[)+/g,
		mt = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,
		yt = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,
		vt = /^-ms-/,
		bt = /-([\da-z])/gi,
		xt = function(e, t) {
			return t.toUpperCase()
		},
		wt = function(e) {
			if (U.addEventListener || "load" === e.type || "complete" === U.readyState) Tt(), ut.ready()
		},
		Tt = function() {
			if (U.addEventListener) U.removeEventListener("DOMContentLoaded", wt, !1), e.removeEventListener("load", wt, !1);
			else U.detachEvent("onreadystatechange", wt), e.detachEvent("onload", wt)
		};
	ut.fn = ut.prototype = {
		jquery: et,
		constructor: ut,
		init: function(e, n, i) {
			var r, o;
			if (!e) return this;
			if ("string" == typeof e) {
				if ("<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3) r = [null, e, null];
				else r = pt.exec(e);
				if (r && (r[1] || !n))
					if (r[1]) {
						if (n = n instanceof ut ? n[0] : n, ut.merge(this, ut.parseHTML(r[1], n && n.nodeType ? n.ownerDocument || n : U, !0)), dt.test(r[1]) && ut.isPlainObject(n))
							for (r in n)
								if (ut.isFunction(this[r])) this[r](n[r]);
								else this.attr(r, n[r]);
						return this
					} else {
						if (o = U.getElementById(r[2]), o && o.parentNode) {
							if (o.id !== r[2]) return i.find(e);
							this.length = 1, this[0] = o
						}
						return this.context = U, this.selector = e, this
					} else if (!n || n.jquery) return (n || i).find(e);
				else return this.constructor(n).find(e)
			} else if (e.nodeType) return this.context = this[0] = e, this.length = 1, this;
			else if (ut.isFunction(e)) return i.ready(e);
			if (e.selector !== t) this.selector = e.selector, this.context = e.context;
			return ut.makeArray(e, this)
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
			var t = ut.merge(this.constructor(), e);
			return t.prevObject = this, t.context = this.context, t
		},
		each: function(e, t) {
			return ut.each(this, e, t)
		},
		ready: function(e) {
			return ut.ready.promise().done(e), this
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
			return this.pushStack(ut.map(this, function(t, n) {
				return e.call(t, n, t)
			}))
		},
		end: function() {
			return this.prevObject || this.constructor(null)
		},
		push: nt,
		sort: [].sort,
		splice: [].splice
	}, ut.fn.init.prototype = ut.fn, ut.extend = ut.fn.extend = function() {
		var e, n, i, r, o, s, a = arguments[0] || {},
			u = 1,
			l = arguments.length,
			f = !1;
		if ("boolean" == typeof a) f = a, a = arguments[1] || {}, u = 2;
		if ("object" != typeof a && !ut.isFunction(a)) a = {};
		if (l === u) a = this, --u;
		for (; l > u; u++)
			if (null != (o = arguments[u]))
				for (r in o)
					if (e = a[r], i = o[r], a !== i) {
						if (f && i && (ut.isPlainObject(i) || (n = ut.isArray(i)))) {
							if (n) n = !1, s = e && ut.isArray(e) ? e : [];
							else s = e && ut.isPlainObject(e) ? e : {};
							a[r] = ut.extend(f, s, i)
						} else if (i !== t) a[r] = i
					} else;
		return a
	}, ut.extend({
		noConflict: function(t) {
			if (e.$ === ut) e.$ = J;
			if (t && e.jQuery === ut) e.jQuery = K;
			return ut
		},
		isReady: !1,
		readyWait: 1,
		holdReady: function(e) {
			if (e) ut.readyWait++;
			else ut.ready(!0)
		},
		ready: function(e) {
			if (e === !0 ? !--ut.readyWait : !ut.isReady) {
				if (!U.body) return setTimeout(ut.ready);
				if (ut.isReady = !0, !(e !== !0 && --ut.readyWait > 0))
					if (Y.resolveWith(U, [ut]), ut.fn.trigger) ut(U).trigger("ready").off("ready")
			}
		},
		isFunction: function(e) {
			return "function" === ut.type(e)
		},
		isArray: Array.isArray || function(e) {
			return "array" === ut.type(e)
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
			if (!e || "object" !== ut.type(e) || e.nodeType || ut.isWindow(e)) return !1;
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
			t = t || U;
			var i = dt.exec(e),
				r = !n && [];
			if (i) return [t.createElement(i[1])];
			if (i = ut.buildFragment([e], t, r), r) ut(r).remove();
			return ut.merge([], i.childNodes)
		},
		parseJSON: function(t) {
			if (e.JSON && e.JSON.parse) return e.JSON.parse(t);
			if (null === t) return t;
			if ("string" == typeof t)
				if (t = ut.trim(t))
					if (ht.test(t.replace(mt, "@").replace(yt, "]").replace(gt, ""))) return new Function("return " + t)();
			ut.error("Invalid JSON: " + t)
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
			if (!i || !i.documentElement || i.getElementsByTagName("parsererror").length) ut.error("Invalid XML: " + n);
			return i
		},
		noop: function() {},
		globalEval: function(t) {
			if (t && ut.trim(t))(e.execScript || function(t) {
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
					if (r = t.call(e[o], o, e[o]), r === !1) break;
			return e
		},
		trim: at && !at.call("﻿ ") ? function(e) {
			return null == e ? "" : at.call(e)
		} : function(e) {
			return null == e ? "" : (e + "").replace(ct, "")
		},
		makeArray: function(e, t) {
			var i = t || [];
			if (null != e)
				if (n(Object(e))) ut.merge(i, "string" == typeof e ? [e] : e);
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
				u = [];
			if (a) {
				for (; s > o; o++)
					if (r = t(e[o], o, i), null != r) u[u.length] = r
			} else
				for (o in e)
					if (r = t(e[o], o, i), null != r) u[u.length] = r;
			return tt.apply([], u)
		},
		guid: 1,
		proxy: function(e, n) {
			var i, r, o;
			if ("string" == typeof n) o = e[n], n = e, e = o;
			if (!ut.isFunction(e)) return t;
			else return i = it.call(arguments, 2), r = function() {
				return e.apply(n || this, i.concat(it.call(arguments)))
			}, r.guid = e.guid = e.guid || ut.guid++, r
		},
		access: function(e, n, i, r, o, s, a) {
			var u = 0,
				l = e.length,
				f = null == i;
			if ("object" === ut.type(i)) {
				o = !0;
				for (u in i) ut.access(e, n, u, i[u], !0, s, a)
			} else if (r !== t) {
				if (o = !0, !ut.isFunction(r)) a = !0;
				if (f)
					if (a) n.call(e, r), n = null;
					else f = n, n = function(e, t, n) {
						return f.call(ut(e), n)
					};
				if (n)
					for (; l > u; u++) n(e[u], i, a ? r : r.call(e[u], u, n(e[u], i)))
			}
			return o ? e : f ? n.call(e) : l ? n(e[0], i) : s
		},
		now: function() {
			return (new Date).getTime()
		}
	}), ut.ready.promise = function(t) {
		if (!Y)
			if (Y = ut.Deferred(), "complete" === U.readyState) setTimeout(ut.ready);
			else if (U.addEventListener) U.addEventListener("DOMContentLoaded", wt, !1), e.addEventListener("load", wt, !1);
		else {
			U.attachEvent("onreadystatechange", wt), e.attachEvent("onload", wt);
			var n = !1;
			try {
				n = null == e.frameElement && U.documentElement
			} catch (i) {}
			if (n && n.doScroll) ! function r() {
				if (!ut.isReady) {
					try {
						n.doScroll("left")
					} catch (e) {
						return setTimeout(r, 50)
					}
					Tt(), ut.ready()
				}
			}()
		}
		return Y.promise(t)
	}, ut.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(e, t) {
		Q["[object " + t + "]"] = t.toLowerCase()
	}), G = ut(U);
	var Et = {};
	ut.Callbacks = function(e) {
		e = "string" == typeof e ? Et[e] || i(e) : ut.extend({}, e);
		var n, r, o, s, a, u, l = [],
			f = !e.once && [],
			c = function(t) {
				for (r = e.memory && t, o = !0, a = u || 0, u = 0, s = l.length, n = !0; l && s > a; a++)
					if (l[a].apply(t[0], t[1]) === !1 && e.stopOnFalse) {
						r = !1;
						break
					}
				if (n = !1, l)
					if (f) {
						if (f.length) c(f.shift())
					} else if (r) l = [];
				else p.disable()
			},
			p = {
				add: function() {
					if (l) {
						var t = l.length;
						if (function i(t) {
								ut.each(t, function(t, n) {
									var r = ut.type(n);
									if ("function" === r) {
										if (!e.unique || !p.has(n)) l.push(n)
									} else if (n && n.length && "string" !== r) i(n)
								})
							}(arguments), n) s = l.length;
						else if (r) u = t, c(r)
					}
					return this
				},
				remove: function() {
					if (l) ut.each(arguments, function(e, t) {
						for (var i;
							(i = ut.inArray(t, l, i)) > -1;)
							if (l.splice(i, 1), n) {
								if (s >= i) s--;
								if (a >= i) a--
							}
					});
					return this
				},
				has: function(e) {
					return e ? ut.inArray(e, l) > -1 : !(!l || !l.length)
				},
				empty: function() {
					return l = [], this
				},
				disable: function() {
					return l = f = r = t, this
				},
				disabled: function() {
					return !l
				},
				lock: function() {
					if (f = t, !r) p.disable();
					return this
				},
				locked: function() {
					return !f
				},
				fireWith: function(e, t) {
					if (t = t || [], t = [e, t.slice ? t.slice() : t], l && (!o || f))
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
	}, ut.extend({
		Deferred: function(e) {
			var t = [
					["resolve", "done", ut.Callbacks("once memory"), "resolved"],
					["reject", "fail", ut.Callbacks("once memory"), "rejected"],
					["notify", "progress", ut.Callbacks("memory")]
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
						return ut.Deferred(function(n) {
							ut.each(t, function(t, o) {
								var s = o[0],
									a = ut.isFunction(e[t]) && e[t];
								r[o[1]](function() {
									var e = a && a.apply(this, arguments);
									if (e && ut.isFunction(e.promise)) e.promise().done(n.resolve).fail(n.reject).progress(n.notify);
									else n[s + "With"](this === i ? n.promise() : this, a ? [e] : arguments)
								})
							}), e = null
						}).promise()
					},
					promise: function(e) {
						return null != e ? ut.extend(e, i) : i
					}
				},
				r = {};
			if (i.pipe = i.then, ut.each(t, function(e, o) {
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
				a = 1 !== s || e && ut.isFunction(e.promise) ? s : 0,
				u = 1 === a ? e : ut.Deferred(),
				l = function(e, n, i) {
					return function(r) {
						if (n[e] = this, i[e] = arguments.length > 1 ? it.call(arguments) : r, i === t) u.notifyWith(n, i);
						else if (!--a) u.resolveWith(n, i)
					}
				};
			if (s > 1)
				for (t = new Array(s), n = new Array(s), i = new Array(s); s > r; r++)
					if (o[r] && ut.isFunction(o[r].promise)) o[r].promise().done(l(r, i, o)).fail(u.reject).progress(l(r, n, t));
					else --a;
			if (!a) u.resolveWith(i, o);
			return u.promise()
		}
	}), ut.support = function() {
		var t, n, i, r, o, s, a, u, l, f, c = U.createElement("div");
		if (c.setAttribute("className", "t"), c.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", n = c.getElementsByTagName("*"), i = c.getElementsByTagName("a")[0], !n || !i || !n.length) return {};
		o = U.createElement("select"), a = o.appendChild(U.createElement("option")), r = c.getElementsByTagName("input")[0], i.style.cssText = "top:1px;float:left;opacity:.5", t = {
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
			enctype: !!U.createElement("form").enctype,
			html5Clone: "<:nav></:nav>" !== U.createElement("nav").cloneNode(!0).outerHTML,
			boxModel: "CSS1Compat" === U.compatMode,
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
		if (r = U.createElement("input"), r.setAttribute("value", ""), t.input = "" === r.getAttribute("value"), r.value = "t", r.setAttribute("type", "radio"), t.radioValue = "t" === r.value, r.setAttribute("checked", "t"), r.setAttribute("name", "t"), s = U.createDocumentFragment(), s.appendChild(r), t.appendChecked = r.checked, t.checkClone = s.cloneNode(!0).cloneNode(!0).lastChild.checked, c.attachEvent) c.attachEvent("onclick", function() {
			t.noCloneEvent = !1
		}), c.cloneNode(!0).click();
		for (f in {
				submit: !0,
				change: !0,
				focusin: !0
			}) c.setAttribute(u = "on" + f, "t"), t[f + "Bubbles"] = u in e || c.attributes[u].expando === !1;
		return c.style.backgroundClip = "content-box", c.cloneNode(!0).style.backgroundClip = "", t.clearCloneStyle = "content-box" === c.style.backgroundClip, ut(function() {
			var n, i, r, o = "padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",
				s = U.getElementsByTagName("body")[0];
			if (s) {
				if (n = U.createElement("div"), n.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", s.appendChild(n).appendChild(c), c.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", r = c.getElementsByTagName("td"), r[0].style.cssText = "padding:0;margin:0;border:0;display:none", l = 0 === r[0].offsetHeight, r[0].style.display = "", r[1].style.display = "none", t.reliableHiddenOffsets = l && 0 === r[0].offsetHeight, c.innerHTML = "", c.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;", t.boxSizing = 4 === c.offsetWidth, t.doesNotIncludeMarginInBodyOffset = 1 !== s.offsetTop, e.getComputedStyle) t.pixelPosition = "1%" !== (e.getComputedStyle(c, null) || {}).top, t.boxSizingReliable = "4px" === (e.getComputedStyle(c, null) || {
					width: "4px"
				}).width, i = c.appendChild(U.createElement("div")), i.style.cssText = c.style.cssText = o, i.style.marginRight = i.style.width = "0", c.style.width = "1px", t.reliableMarginRight = !parseFloat((e.getComputedStyle(i, null) || {}).marginRight);
				if (typeof c.style.zoom !== X)
					if (c.innerHTML = "", c.style.cssText = o + "width:1px;padding:1px;display:inline;zoom:1", t.inlineBlockNeedsLayout = 3 === c.offsetWidth, c.style.display = "block", c.innerHTML = "<div></div>", c.firstChild.style.width = "5px", t.shrinkWrapBlocks = 3 !== c.offsetWidth, t.inlineBlockNeedsLayout) s.style.zoom = 1;
				s.removeChild(n), n = c = r = i = null
			}
		}), n = o = s = a = i = r = null, t
	}();
	var Ct = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/,
		Nt = /([A-Z])/g;
	ut.extend({
		cache: {},
		expando: "jQuery" + (et + Math.random()).replace(/\D/g, ""),
		noData: {
			embed: !0,
			object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
			applet: !0
		},
		hasData: function(e) {
			return e = e.nodeType ? ut.cache[e[ut.expando]] : e[ut.expando], !!e && !a(e)
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
			var t = e.nodeName && ut.noData[e.nodeName.toLowerCase()];
			return !t || t !== !0 && e.getAttribute("classid") === t
		}
	}), ut.fn.extend({
		data: function(e, n) {
			var i, r, o = this[0],
				a = 0,
				u = null;
			if (e === t) {
				if (this.length)
					if (u = ut.data(o), 1 === o.nodeType && !ut._data(o, "parsedAttrs")) {
						for (i = o.attributes; a < i.length; a++)
							if (r = i[a].name, !r.indexOf("data-")) r = ut.camelCase(r.slice(5)), s(o, r, u[r]);
						ut._data(o, "parsedAttrs", !0)
					}
				return u
			}
			if ("object" == typeof e) return this.each(function() {
				ut.data(this, e)
			});
			else return ut.access(this, function(n) {
				if (n === t) return o ? s(o, e, ut.data(o, e)) : null;
				else return void this.each(function() {
					ut.data(this, e, n)
				})
			}, null, n, arguments.length > 1, null, !0)
		},
		removeData: function(e) {
			return this.each(function() {
				ut.removeData(this, e)
			})
		}
	}), ut.extend({
		queue: function(e, t, n) {
			var i;
			if (e) {
				if (t = (t || "fx") + "queue", i = ut._data(e, t), n)
					if (!i || ut.isArray(n)) i = ut._data(e, t, ut.makeArray(n));
					else i.push(n);
				return i || []
			}
		},
		dequeue: function(e, t) {
			t = t || "fx";
			var n = ut.queue(e, t),
				i = n.length,
				r = n.shift(),
				o = ut._queueHooks(e, t),
				s = function() {
					ut.dequeue(e, t)
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
			return ut._data(e, n) || ut._data(e, n, {
				empty: ut.Callbacks("once memory").add(function() {
					ut._removeData(e, t + "queue"), ut._removeData(e, n)
				})
			})
		}
	}), ut.fn.extend({
		queue: function(e, n) {
			var i = 2;
			if ("string" != typeof e) n = e, e = "fx", i--;
			if (arguments.length < i) return ut.queue(this[0], e);
			else return n === t ? this : this.each(function() {
				var t = ut.queue(this, e, n);
				if (ut._queueHooks(this, e), "fx" === e && "inprogress" !== t[0]) ut.dequeue(this, e)
			})
		},
		dequeue: function(e) {
			return this.each(function() {
				ut.dequeue(this, e)
			})
		},
		delay: function(e, t) {
			return e = ut.fx ? ut.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function(t, n) {
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
				o = ut.Deferred(),
				s = this,
				a = this.length,
				u = function() {
					if (!--r) o.resolveWith(s, [s])
				};
			if ("string" != typeof e) n = e, e = t;
			for (e = e || "fx"; a--;)
				if (i = ut._data(s[a], e + "queueHooks"), i && i.empty) r++, i.empty.add(u);
			return u(), o.promise(n)
		}
	});
	var kt, _t, St = /[\t\r\n]/g,
		Dt = /\r/g,
		At = /^(?:input|select|textarea|button|object)$/i,
		jt = /^(?:a|area)$/i,
		Lt = /^(?:checked|selected|autofocus|autoplay|async|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped)$/i,
		Mt = /^(?:checked|selected)$/i,
		Rt = ut.support.getSetAttribute,
		Ht = ut.support.input;
	if (ut.fn.extend({
			attr: function(e, t) {
				return ut.access(this, ut.attr, e, t, arguments.length > 1)
			},
			removeAttr: function(e) {
				return this.each(function() {
					ut.removeAttr(this, e)
				})
			},
			prop: function(e, t) {
				return ut.access(this, ut.prop, e, t, arguments.length > 1)
			},
			removeProp: function(e) {
				return e = ut.propFix[e] || e, this.each(function() {
					try {
						this[e] = t, delete this[e]
					} catch (n) {}
				})
			},
			addClass: function(e) {
				var t, n, i, r, o, s = 0,
					a = this.length,
					u = "string" == typeof e && e;
				if (ut.isFunction(e)) return this.each(function(t) {
					ut(this).addClass(e.call(this, t, this.className))
				});
				if (u)
					for (t = (e || "").match(ft) || []; a > s; s++)
						if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(St, " ") : " ")) {
							for (o = 0; r = t[o++];)
								if (i.indexOf(" " + r + " ") < 0) i += r + " ";
							n.className = ut.trim(i)
						}
				return this
			},
			removeClass: function(e) {
				var t, n, i, r, o, s = 0,
					a = this.length,
					u = 0 === arguments.length || "string" == typeof e && e;
				if (ut.isFunction(e)) return this.each(function(t) {
					ut(this).removeClass(e.call(this, t, this.className))
				});
				if (u)
					for (t = (e || "").match(ft) || []; a > s; s++)
						if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(St, " ") : "")) {
							for (o = 0; r = t[o++];)
								for (; i.indexOf(" " + r + " ") >= 0;) i = i.replace(" " + r + " ", " ");
							n.className = e ? ut.trim(i) : ""
						}
				return this
			},
			toggleClass: function(e, t) {
				var n = typeof e,
					i = "boolean" == typeof t;
				if (ut.isFunction(e)) return this.each(function(n) {
					ut(this).toggleClass(e.call(this, n, this.className, t), t)
				});
				else return this.each(function() {
					if ("string" === n)
						for (var r, o = 0, s = ut(this), a = t, u = e.match(ft) || []; r = u[o++];) a = i ? a : !s.hasClass(r), s[a ? "addClass" : "removeClass"](r);
					else if (n === X || "boolean" === n) {
						if (this.className) ut._data(this, "__className__", this.className);
						this.className = this.className || e === !1 ? "" : ut._data(this, "__className__") || ""
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
				if (arguments.length) return r = ut.isFunction(e), this.each(function(n) {
					var o, s = ut(this);
					if (1 === this.nodeType) {
						if (r) o = e.call(this, n, s.val());
						else o = e;
						if (null == o) o = "";
						else if ("number" == typeof o) o += "";
						else if (ut.isArray(o)) o = ut.map(o, function(e) {
							return null == e ? "" : e + ""
						});
						if (i = ut.valHooks[this.type] || ut.valHooks[this.nodeName.toLowerCase()], !(i && "set" in i && i.set(this, o, "value") !== t)) this.value = o
					}
				});
				else if (o)
					if (i = ut.valHooks[o.type] || ut.valHooks[o.nodeName.toLowerCase()], i && "get" in i && (n = i.get(o, "value")) !== t) return n;
					else return n = o.value, "string" == typeof n ? n.replace(Dt, "") : null == n ? "" : n
			}
		}), ut.extend({
			valHooks: {
				option: {
					get: function(e) {
						var t = e.attributes.value;
						return !t || t.specified ? e.value : e.text
					}
				},
				select: {
					get: function(e) {
						for (var t, n, i = e.options, r = e.selectedIndex, o = "select-one" === e.type || 0 > r, s = o ? null : [], a = o ? r + 1 : i.length, u = 0 > r ? a : o ? r : 0; a > u; u++)
							if (n = i[u], !(!n.selected && u !== r || (ut.support.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && ut.nodeName(n.parentNode, "optgroup"))) {
								if (t = ut(n).val(), o) return t;
								s.push(t)
							}
						return s
					},
					set: function(e, t) {
						var n = ut.makeArray(t);
						if (ut(e).find("option").each(function() {
								this.selected = ut.inArray(ut(this).val(), n) >= 0
							}), !n.length) e.selectedIndex = -1;
						return n
					}
				}
			},
			attr: function(e, n, i) {
				var r, o, s, a = e.nodeType;
				if (e && 3 !== a && 8 !== a && 2 !== a) {
					if (typeof e.getAttribute === X) return ut.prop(e, n, i);
					if (o = 1 !== a || !ut.isXMLDoc(e)) n = n.toLowerCase(), r = ut.attrHooks[n] || (Lt.test(n) ? _t : kt);
					if (i !== t)
						if (null === i) ut.removeAttr(e, n);
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
						if (i = ut.propFix[n] || n, Lt.test(n))
							if (!Rt && Mt.test(n)) e[ut.camelCase("default-" + n)] = e[i] = !1;
							else e[i] = !1;
						else ut.attr(e, n, "");
						e.removeAttribute(Rt ? n : i)
					}
			},
			attrHooks: {
				type: {
					set: function(e, t) {
						if (!ut.support.radioValue && "radio" === t && ut.nodeName(e, "input")) {
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
					if (s = 1 !== a || !ut.isXMLDoc(e)) n = ut.propFix[n] || n, o = ut.propHooks[n];
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
		}), _t = {
			get: function(e, n) {
				var i = ut.prop(e, n),
					r = "boolean" == typeof i && e.getAttribute(n),
					o = "boolean" == typeof i ? Ht && Rt ? null != r : Mt.test(n) ? e[ut.camelCase("default-" + n)] : !!r : e.getAttributeNode(n);
				return o && o.value !== !1 ? n.toLowerCase() : t
			},
			set: function(e, t, n) {
				if (t === !1) ut.removeAttr(e, n);
				else if (Ht && Rt || !Mt.test(n)) e.setAttribute(!Rt && ut.propFix[n] || n, n);
				else e[ut.camelCase("default-" + n)] = e[n] = !0;
				return n
			}
		}, !Ht || !Rt) ut.attrHooks.value = {
		get: function(e, n) {
			var i = e.getAttributeNode(n);
			return ut.nodeName(e, "input") ? e.defaultValue : i && i.specified ? i.value : t
		},
		set: function(e, t, n) {
			if (ut.nodeName(e, "input")) e.defaultValue = t;
			else return kt && kt.set(e, t, n)
		}
	};
	if (!Rt) kt = ut.valHooks.button = {
		get: function(e, n) {
			var i = e.getAttributeNode(n);
			return i && ("id" === n || "name" === n || "coords" === n ? "" !== i.value : i.specified) ? i.value : t
		},
		set: function(e, n, i) {
			var r = e.getAttributeNode(i);
			if (!r) e.setAttributeNode(r = e.ownerDocument.createAttribute(i));
			return r.value = n += "", "value" === i || n === e.getAttribute(i) ? n : t
		}
	}, ut.attrHooks.contenteditable = {
		get: kt.get,
		set: function(e, t, n) {
			kt.set(e, "" === t ? !1 : t, n)
		}
	}, ut.each(["width", "height"], function(e, t) {
		ut.attrHooks[t] = ut.extend(ut.attrHooks[t], {
			set: function(e, n) {
				if ("" === n) return e.setAttribute(t, "auto"), n;
				else return void 0
			}
		})
	});
	if (!ut.support.hrefNormalized) ut.each(["href", "src", "width", "height"], function(e, n) {
		ut.attrHooks[n] = ut.extend(ut.attrHooks[n], {
			get: function(e) {
				var i = e.getAttribute(n, 2);
				return null == i ? t : i
			}
		})
	}), ut.each(["href", "src"], function(e, t) {
		ut.propHooks[t] = {
			get: function(e) {
				return e.getAttribute(t, 4)
			}
		}
	});
	if (!ut.support.style) ut.attrHooks.style = {
		get: function(e) {
			return e.style.cssText || t
		},
		set: function(e, t) {
			return e.style.cssText = t + ""
		}
	};
	if (!ut.support.optSelected) ut.propHooks.selected = ut.extend(ut.propHooks.selected, {
		get: function(e) {
			var t = e.parentNode;
			if (t)
				if (t.selectedIndex, t.parentNode) t.parentNode.selectedIndex;
			return null
		}
	});
	if (!ut.support.enctype) ut.propFix.enctype = "encoding";
	if (!ut.support.checkOn) ut.each(["radio", "checkbox"], function() {
		ut.valHooks[this] = {
			get: function(e) {
				return null === e.getAttribute("value") ? "on" : e.value
			}
		}
	});
	ut.each(["radio", "checkbox"], function() {
		ut.valHooks[this] = ut.extend(ut.valHooks[this], {
			set: function(e, t) {
				if (ut.isArray(t)) return e.checked = ut.inArray(ut(e).val(), t) >= 0;
				else return void 0
			}
		})
	});
	var Ot = /^(?:input|select|textarea)$/i,
		It = /^key/,
		qt = /^(?:mouse|contextmenu)|click/,
		Pt = /^(?:focusinfocus|focusoutblur)$/,
		Ft = /^([^.]*)(?:\.(.+)|)$/;
	if (ut.event = {
			global: {},
			add: function(e, n, i, r, o) {
				var s, a, u, l, f, c, p, d, h, g, m, y = ut._data(e);
				if (y) {
					if (i.handler) l = i, i = l.handler, o = l.selector;
					if (!i.guid) i.guid = ut.guid++;
					if (!(a = y.events)) a = y.events = {};
					if (!(c = y.handle)) c = y.handle = function(e) {
						return typeof ut !== X && (!e || ut.event.triggered !== e.type) ? ut.event.dispatch.apply(c.elem, arguments) : t
					}, c.elem = e;
					for (n = (n || "").match(ft) || [""], u = n.length; u--;) {
						if (s = Ft.exec(n[u]) || [], h = m = s[1], g = (s[2] || "").split(".").sort(), f = ut.event.special[h] || {}, h = (o ? f.delegateType : f.bindType) || h, f = ut.event.special[h] || {}, p = ut.extend({
								type: h,
								origType: m,
								data: r,
								handler: i,
								guid: i.guid,
								selector: o,
								needsContext: o && ut.expr.match.needsContext.test(o),
								namespace: g.join(".")
							}, l), !(d = a[h]))
							if (d = a[h] = [], d.delegateCount = 0, !f.setup || f.setup.call(e, r, g, c) === !1)
								if (e.addEventListener) e.addEventListener(h, c, !1);
								else if (e.attachEvent) e.attachEvent("on" + h, c);
						if (f.add)
							if (f.add.call(e, p), !p.handler.guid) p.handler.guid = i.guid;
						if (o) d.splice(d.delegateCount++, 0, p);
						else d.push(p);
						ut.event.global[h] = !0
					}
					e = null
				}
			},
			remove: function(e, t, n, i, r) {
				var o, s, a, u, l, f, c, p, d, h, g, m = ut.hasData(e) && ut._data(e);
				if (m && (f = m.events)) {
					for (t = (t || "").match(ft) || [""], l = t.length; l--;)
						if (a = Ft.exec(t[l]) || [], d = g = a[1], h = (a[2] || "").split(".").sort(), d) {
							for (c = ut.event.special[d] || {}, d = (i ? c.delegateType : c.bindType) || d, p = f[d] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), u = o = p.length; o--;)
								if (s = p[o], !(!r && g !== s.origType || n && n.guid !== s.guid || a && !a.test(s.namespace) || i && i !== s.selector && ("**" !== i || !s.selector))) {
									if (p.splice(o, 1), s.selector) p.delegateCount--;
									if (c.remove) c.remove.call(e, s)
								}
							if (u && !p.length) {
								if (!c.teardown || c.teardown.call(e, h, m.handle) === !1) ut.removeEvent(e, d, m.handle);
								delete f[d]
							}
						} else
							for (d in f) ut.event.remove(e, d + t[l], n, i, !0);
					if (ut.isEmptyObject(f)) delete m.handle, ut._removeData(e, "events")
				}
			},
			trigger: function(n, i, r, o) {
				var s, a, u, l, f, c, p, d = [r || U],
					h = st.call(n, "type") ? n.type : n,
					g = st.call(n, "namespace") ? n.namespace.split(".") : [];
				if (u = c = r = r || U, 3 !== r.nodeType && 8 !== r.nodeType)
					if (!Pt.test(h + ut.event.triggered)) {
						if (h.indexOf(".") >= 0) g = h.split("."), h = g.shift(), g.sort();
						if (a = h.indexOf(":") < 0 && "on" + h, n = n[ut.expando] ? n : new ut.Event(h, "object" == typeof n && n), n.isTrigger = !0, n.namespace = g.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, n.result = t, !n.target) n.target = r;
						if (i = null == i ? [n] : ut.makeArray(i, [n]), f = ut.event.special[h] || {}, o || !f.trigger || f.trigger.apply(r, i) !== !1) {
							if (!o && !f.noBubble && !ut.isWindow(r)) {
								if (l = f.delegateType || h, !Pt.test(l + h)) u = u.parentNode;
								for (; u; u = u.parentNode) d.push(u), c = u;
								if (c === (r.ownerDocument || U)) d.push(c.defaultView || c.parentWindow || e)
							}
							for (p = 0;
								(u = d[p++]) && !n.isPropagationStopped();) {
								if (n.type = p > 1 ? l : f.bindType || h, s = (ut._data(u, "events") || {})[n.type] && ut._data(u, "handle")) s.apply(u, i);
								if (s = a && u[a], s && ut.acceptData(u) && s.apply && s.apply(u, i) === !1) n.preventDefault()
							}
							if (n.type = h, !o && !n.isDefaultPrevented())
								if (!(f._default && f._default.apply(r.ownerDocument, i) !== !1 || "click" === h && ut.nodeName(r, "a") || !ut.acceptData(r)))
									if (a && r[h] && !ut.isWindow(r)) {
										if (c = r[a]) r[a] = null;
										ut.event.triggered = h;
										try {
											r[h]()
										} catch (m) {}
										if (ut.event.triggered = t, c) r[a] = c
									}
							return n.result
						}
					}
			},
			dispatch: function(e) {
				e = ut.event.fix(e);
				var n, i, r, o, s, a = [],
					u = it.call(arguments),
					l = (ut._data(this, "events") || {})[e.type] || [],
					f = ut.event.special[e.type] || {};
				if (u[0] = e, e.delegateTarget = this, !f.preDispatch || f.preDispatch.call(this, e) !== !1) {
					for (a = ut.event.handlers.call(this, e, l), n = 0;
						(o = a[n++]) && !e.isPropagationStopped();)
						for (e.currentTarget = o.elem, s = 0;
							(r = o.handlers[s++]) && !e.isImmediatePropagationStopped();)
							if (!e.namespace_re || e.namespace_re.test(r.namespace))
								if (e.handleObj = r, e.data = r.data, i = ((ut.event.special[r.origType] || {}).handle || r.handler).apply(o.elem, u), i !== t)
									if ((e.result = i) === !1) e.preventDefault(), e.stopPropagation();
					if (f.postDispatch) f.postDispatch.call(this, e);
					return e.result
				}
			},
			handlers: function(e, n) {
				var i, r, o, s, a = [],
					u = n.delegateCount,
					l = e.target;
				if (u && l.nodeType && (!e.button || "click" !== e.type))
					for (; l != this; l = l.parentNode || this)
						if (1 === l.nodeType && (l.disabled !== !0 || "click" !== e.type)) {
							for (o = [], s = 0; u > s; s++) {
								if (r = n[s], i = r.selector + " ", o[i] === t) o[i] = r.needsContext ? ut(i, this).index(l) >= 0 : ut.find(i, this, null, [l]).length;
								if (o[i]) o.push(r)
							}
							if (o.length) a.push({
								elem: l,
								handlers: o
							})
						}
				if (u < n.length) a.push({
					elem: this,
					handlers: n.slice(u)
				});
				return a
			},
			fix: function(e) {
				if (e[ut.expando]) return e;
				var t, n, i, r = e.type,
					o = e,
					s = this.fixHooks[r];
				if (!s) this.fixHooks[r] = s = qt.test(r) ? this.mouseHooks : It.test(r) ? this.keyHooks : {};
				for (i = s.props ? this.props.concat(s.props) : this.props, e = new ut.Event(o), t = i.length; t--;) n = i[t], e[n] = o[n];
				if (!e.target) e.target = o.srcElement || U;
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
					if (null == e.pageX && null != n.clientX) r = e.target.ownerDocument || U, o = r.documentElement, i = r.body, e.pageX = n.clientX + (o && o.scrollLeft || i && i.scrollLeft || 0) - (o && o.clientLeft || i && i.clientLeft || 0), e.pageY = n.clientY + (o && o.scrollTop || i && i.scrollTop || 0) - (o && o.clientTop || i && i.clientTop || 0);
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
						if (ut.nodeName(this, "input") && "checkbox" === this.type && this.click) return this.click(), !1;
						else return void 0
					}
				},
				focus: {
					trigger: function() {
						if (this !== U.activeElement && this.focus) try {
							return this.focus(), !1
						} catch (e) {}
					},
					delegateType: "focusin"
				},
				blur: {
					trigger: function() {
						if (this === U.activeElement && this.blur) return this.blur(), !1;
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
				var r = ut.extend(new ut.Event, n, {
					type: e,
					isSimulated: !0,
					originalEvent: {}
				});
				if (i) ut.event.trigger(r, null, t);
				else ut.event.dispatch.call(t, r);
				if (r.isDefaultPrevented()) n.preventDefault()
			}
		}, ut.removeEvent = U.removeEventListener ? function(e, t, n) {
			if (e.removeEventListener) e.removeEventListener(t, n, !1)
		} : function(e, t, n) {
			var i = "on" + t;
			if (e.detachEvent) {
				if (typeof e[i] === X) e[i] = null;
				e.detachEvent(i, n)
			}
		}, ut.Event = function(e, t) {
			if (!(this instanceof ut.Event)) return new ut.Event(e, t);
			if (e && e.type) this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || e.returnValue === !1 || e.getPreventDefault && e.getPreventDefault() ? u : l;
			else this.type = e;
			if (t) ut.extend(this, t);
			this.timeStamp = e && e.timeStamp || ut.now(), this[ut.expando] = !0
		}, ut.Event.prototype = {
			isDefaultPrevented: l,
			isPropagationStopped: l,
			isImmediatePropagationStopped: l,
			preventDefault: function() {
				var e = this.originalEvent;
				if (this.isDefaultPrevented = u, e)
					if (e.preventDefault) e.preventDefault();
					else e.returnValue = !1
			},
			stopPropagation: function() {
				var e = this.originalEvent;
				if (this.isPropagationStopped = u, e) {
					if (e.stopPropagation) e.stopPropagation();
					e.cancelBubble = !0
				}
			},
			stopImmediatePropagation: function() {
				this.isImmediatePropagationStopped = u, this.stopPropagation()
			}
		}, ut.each({
			mouseenter: "mouseover",
			mouseleave: "mouseout"
		}, function(e, t) {
			ut.event.special[e] = {
				delegateType: t,
				bindType: t,
				handle: function(e) {
					var n, i = this,
						r = e.relatedTarget,
						o = e.handleObj;
					if (!r || r !== i && !ut.contains(i, r)) e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t;
					return n
				}
			}
		}), !ut.support.submitBubbles) ut.event.special.submit = {
		setup: function() {
			if (ut.nodeName(this, "form")) return !1;
			else return void ut.event.add(this, "click._submit keypress._submit", function(e) {
				var n = e.target,
					i = ut.nodeName(n, "input") || ut.nodeName(n, "button") ? n.form : t;
				if (i && !ut._data(i, "submitBubbles")) ut.event.add(i, "submit._submit", function(e) {
					e._submit_bubble = !0
				}), ut._data(i, "submitBubbles", !0)
			})
		},
		postDispatch: function(e) {
			if (e._submit_bubble)
				if (delete e._submit_bubble, this.parentNode && !e.isTrigger) ut.event.simulate("submit", this.parentNode, e, !0)
		},
		teardown: function() {
			if (ut.nodeName(this, "form")) return !1;
			else return void ut.event.remove(this, "._submit")
		}
	};
	if (!ut.support.changeBubbles) ut.event.special.change = {
		setup: function() {
			if (Ot.test(this.nodeName)) {
				if ("checkbox" === this.type || "radio" === this.type) ut.event.add(this, "propertychange._change", function(e) {
					if ("checked" === e.originalEvent.propertyName) this._just_changed = !0
				}), ut.event.add(this, "click._change", function(e) {
					if (this._just_changed && !e.isTrigger) this._just_changed = !1;
					ut.event.simulate("change", this, e, !0)
				});
				return !1
			}
			ut.event.add(this, "beforeactivate._change", function(e) {
				var t = e.target;
				if (Ot.test(t.nodeName) && !ut._data(t, "changeBubbles")) ut.event.add(t, "change._change", function(e) {
					if (this.parentNode && !e.isSimulated && !e.isTrigger) ut.event.simulate("change", this.parentNode, e, !0)
				}), ut._data(t, "changeBubbles", !0)
			})
		},
		handle: function(e) {
			var t = e.target;
			if (this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type) return e.handleObj.handler.apply(this, arguments);
			else return void 0
		},
		teardown: function() {
			return ut.event.remove(this, "._change"), !Ot.test(this.nodeName)
		}
	};
	if (!ut.support.focusinBubbles) ut.each({
		focus: "focusin",
		blur: "focusout"
	}, function(e, t) {
		var n = 0,
			i = function(e) {
				ut.event.simulate(t, e.target, ut.event.fix(e), !0)
			};
		ut.event.special[t] = {
			setup: function() {
				if (0 === n++) U.addEventListener(e, i, !0)
			},
			teardown: function() {
				if (0 === --n) U.removeEventListener(e, i, !0)
			}
		}
	});
	ut.fn.extend({
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
				if (r === !1) r = l;
				else if (!r) return this;
				if (1 === o) a = r, r = function(e) {
					return ut().off(e), a.apply(this, arguments)
				}, r.guid = a.guid || (a.guid = ut.guid++);
				return this.each(function() {
					ut.event.add(this, e, r, i, n)
				})
			},
			one: function(e, t, n, i) {
				return this.on(e, t, n, i, 1)
			},
			off: function(e, n, i) {
				var r, o;
				if (e && e.preventDefault && e.handleObj) return r = e.handleObj, ut(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this;
				if ("object" == typeof e) {
					for (o in e) this.off(o, n, e[o]);
					return this
				}
				if (n === !1 || "function" == typeof n) i = n, n = t;
				if (i === !1) i = l;
				return this.each(function() {
					ut.event.remove(this, e, i, n)
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
					ut.event.trigger(e, t, this)
				})
			},
			triggerHandler: function(e, t) {
				var n = this[0];
				if (n) return ut.event.trigger(e, t, n, !0);
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
				var r, o, s, a, u, l, f, d, h, g;
				if ((t ? t.ownerDocument || t : F) !== j) A(t);
				if (t = t || j, n = n || [], !e || "string" != typeof e) return n;
				if (1 !== (a = t.nodeType) && 9 !== a) return [];
				if (!M && !i) {
					if (r = gt.exec(e))
						if (s = r[1]) {
							if (9 === a)
								if (o = t.getElementById(s), o && o.parentNode) {
									if (o.id === s) return n.push(o), n
								} else return n;
							else if (t.ownerDocument && (o = t.ownerDocument.getElementById(s)) && I(t, o) && o.id === s) return n.push(o), n
						} else if (r[2]) return J.apply(n, Q.call(t.getElementsByTagName(e), 0)), n;
					else if ((s = r[3]) && $.getByClassName && t.getElementsByClassName) return J.apply(n, Q.call(t.getElementsByClassName(s), 0)), n;
					if ($.qsa && !R.test(e)) {
						if (f = !0, d = P, h = t, g = 9 === a && e, 1 === a && "object" !== t.nodeName.toLowerCase()) {
							if (l = c(e), f = t.getAttribute("id")) d = f.replace(vt, "\\$&");
							else t.setAttribute("id", d);
							for (d = "[id='" + d + "'] ", u = l.length; u--;) l[u] = d + p(l[u]);
							h = dt.test(e) && t.parentNode || t, g = l.join(",")
						}
						if (g) try {
							return J.apply(n, Q.call(h.querySelectorAll(g), 0)), n
						} catch (m) {} finally {
							if (!f) t.removeAttribute("id")
						}
					}
				}
				return x(e.replace(st, "$1"), t, n, i)
			}

			function a(e, t) {
				var n = t && e,
					i = n && (~t.sourceIndex || U) - (~e.sourceIndex || U);
				if (i) return i;
				if (n)
					for (; n = n.nextSibling;)
						if (n === t) return -1;
				return e ? 1 : -1
			}

			function u(e) {
				return function(t) {
					var n = t.nodeName.toLowerCase();
					return "input" === n && t.type === e
				}
			}

			function l(e) {
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
				var n, i, r, o, a, u, l, f = Y[e + " "];
				if (f) return t ? 0 : f.slice(0);
				for (a = e, u = [], l = C.preFilter; a;) {
					if (!n || (i = at.exec(a))) {
						if (i) a = a.slice(i[0].length) || a;
						u.push(r = [])
					}
					if (n = !1, i = lt.exec(a)) n = i.shift(), r.push({
						value: n,
						type: i[0].replace(st, " ")
					}), a = a.slice(n.length);
					for (o in C.filter)
						if ((i = pt[o].exec(a)) && (!l[o] || (i = l[o](i)))) n = i.shift(), r.push({
							value: n,
							type: o,
							matches: i
						}), a = a.slice(n.length);
					if (!n) break
				}
				return t ? a.length : a ? s.error(e) : Y(e, u).slice(0)
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
					var a, u, l, f = B + " " + o;
					if (s) {
						for (; t = t[i];)
							if (1 === t.nodeType || r)
								if (e(t, n, s)) return !0
					} else
						for (; t = t[i];)
							if (1 === t.nodeType || r)
								if (l = t[P] || (t[P] = {}), (u = l[i]) && u[0] === f) {
									if ((a = u[1]) === !0 || a === E) return a === !0
								} else if (u = l[i] = [f], u[1] = e(t, n, s) || E, u[1] === !0) return !0
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
				for (var o, s = [], a = 0, u = e.length, l = null != t; u > a; a++)
					if (o = e[a])
						if (!n || n(o, i, r))
							if (s.push(o), l) t.push(a);
				return s
			}

			function m(e, t, n, i, o, s) {
				if (i && !i[P]) i = m(i);
				if (o && !o[P]) o = m(o, s);
				return r(function(r, s, a, u) {
					var l, f, c, p = [],
						d = [],
						h = s.length,
						m = r || b(t || "*", a.nodeType ? [a] : a, []),
						y = e && (r || !t) ? g(m, p, e, a, u) : m,
						v = n ? o || (r ? e : h || i) ? [] : s : y;
					if (n) n(y, v, a, u);
					if (i)
						for (l = g(v, d), i(l, [], a, u), f = l.length; f--;)
							if (c = l[f]) v[d[f]] = !(y[d[f]] = c);
					if (r) {
						if (o || e) {
							if (o) {
								for (l = [], f = v.length; f--;)
									if (c = v[f]) l.push(y[f] = c);
								o(null, v = [], l, u)
							}
							for (f = v.length; f--;)
								if ((c = v[f]) && (l = o ? Z.call(r, c) : p[f]) > -1) r[l] = !(s[l] = c)
						}
					} else if (v = g(v === s ? v.splice(h, v.length) : v), o) o(null, s, v, u);
					else J.apply(s, v)
				})
			}

			function y(e) {
				for (var t, n, i, r = e.length, o = C.relative[e[0].type], s = o || C.relative[" "], a = o ? 1 : 0, u = d(function(e) {
						return e === t
					}, s, !0), l = d(function(e) {
						return Z.call(t, e) > -1
					}, s, !0), f = [function(e, n, i) {
						return !o && (i || n !== D) || ((t = n).nodeType ? u(e, n, i) : l(e, n, i))
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
					a = function(r, a, u, l, f) {
						var c, p, d, h = [],
							m = 0,
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
									if (d(c, a, u)) {
										l.push(c);
										break
									}
								if (b) B = T, E = ++n
							}
							if (i) {
								if (c = !d && c) m--;
								if (r) v.push(c)
							}
						}
						if (m += y, i && y !== m) {
							for (p = 0; d = t[p++];) d(v, h, a, u);
							if (r) {
								if (m > 0)
									for (; y--;)
										if (!v[y] && !h[y]) h[y] = K.call(l);
								h = g(h)
							}
							if (J.apply(l, h), b && !r && h.length > 0 && m + t.length > 1) s.uniqueSort(l)
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
				var r, o, s, a, u, l = c(e);
				if (!i)
					if (1 === l.length) {
						if (o = l[0] = l[0].slice(0), o.length > 2 && "ID" === (s = o[0]).type && 9 === t.nodeType && !M && C.relative[o[1].type]) {
							if (t = C.find.ID(s.matches[0].replace(xt, wt), t)[0], !t) return n;
							e = e.slice(o.shift().value.length)
						}
						for (r = pt.needsContext.test(e) ? 0 : o.length; r-- && (s = o[r], !C.relative[a = s.type]);)
							if (u = C.find[a])
								if (i = u(s.matches[0].replace(xt, wt), dt.test(o[0].type) && t.parentNode || t)) {
									if (o.splice(r, 1), e = i.length && p(o), !e) return J.apply(n, Q.call(i, 0)), n;
									break
								}
					}
				return _(e, l)(i, t, M, n, dt.test(e)), n
			}

			function w() {}
			var T, E, C, N, k, _, S, D, A, j, L, M, R, H, O, I, q, P = "sizzle" + -new Date,
				F = e.document,
				$ = {},
				B = 0,
				W = 0,
				z = i(),
				Y = i(),
				G = i(),
				X = typeof t,
				U = 1 << 31,
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
				lt = new RegExp("^" + et + "*([\\x20\\t\\r\\n\\f>+~])" + et + "*"),
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
			k = s.isXML = function(e) {
				var t = e && (e.ownerDocument || e).documentElement;
				return t ? "HTML" !== t.nodeName : !1
			}, A = s.setDocument = function(e) {
				var i = e ? e.ownerDocument || e : F;
				if (i === j || 9 !== i.nodeType || !i.documentElement) return j;
				if (j = i, L = i.documentElement, M = k(i), $.tagNameNoComments = o(function(e) {
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
						return e.innerHTML = "<a href='#'></a>", e.firstChild && typeof e.firstChild.getAttribute !== X && "#" === e.firstChild.getAttribute("href")
					}) ? {} : {
						href: function(e) {
							return e.getAttribute("href", 2)
						},
						type: function(e) {
							return e.getAttribute("type")
						}
					}, $.getIdNotName) C.find.ID = function(e, t) {
					if (typeof t.getElementById !== X && !M) {
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
					if (typeof n.getElementById !== X && !M) {
						var i = n.getElementById(e);
						return i ? i.id === e || typeof i.getAttributeNode !== X && i.getAttributeNode("id").value === e ? [i] : t : []
					}
				}, C.filter.ID = function(e) {
					var t = e.replace(xt, wt);
					return function(e) {
						var n = typeof e.getAttributeNode !== X && e.getAttributeNode("id");
						return n && n.value === t
					}
				};
				if (C.find.TAG = $.tagNameNoComments ? function(e, t) {
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
					}, C.find.NAME = $.getByName && function(e, t) {
						if (typeof t.getElementsByName !== X) return t.getElementsByName(name);
						else return void 0
					}, C.find.CLASS = $.getByClassName && function(e, t) {
						if (typeof t.getElementsByClassName !== X && !M) return t.getElementsByClassName(e);
						else return void 0
					}, H = [], R = [":focus"], $.qsa = n(i.querySelectorAll)) o(function(e) {
					if (e.innerHTML = "<select><option selected=''></option></select>", !e.querySelectorAll("[selected]").length) R.push("\\[" + et + "*(?:checked|disabled|ismap|multiple|readonly|selected|value)");
					if (!e.querySelectorAll(":checked").length) R.push(":checked")
				}), o(function(e) {
					if (e.innerHTML = "<input type='hidden' i=''/>", e.querySelectorAll("[i^='']").length) R.push("[*^$]=" + et + "*(?:\"\"|'')");
					if (!e.querySelectorAll(":enabled").length) R.push(":enabled", ":disabled");
					e.querySelectorAll("*,:x"), R.push(",.*:")
				});
				if ($.matchesSelector = n(O = L.matchesSelector || L.mozMatchesSelector || L.webkitMatchesSelector || L.oMatchesSelector || L.msMatchesSelector)) o(function(e) {
					$.disconnectedMatch = O.call(e, "div"), O.call(e, "[s!='']:x"), H.push("!=", ot)
				});
				return R = new RegExp(R.join("|")), H = new RegExp(H.join("|")), I = n(L.contains) || L.compareDocumentPosition ? function(e, t) {
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
							if (e === i || I(F, e)) return -1;
							if (t === i || I(F, t)) return 1;
							else return 0
						}
						return 4 & n ? -1 : 1
					}
					return e.compareDocumentPosition ? -1 : 1
				} : function(e, t) {
					var n, r = 0,
						o = e.parentNode,
						s = t.parentNode,
						u = [e],
						l = [t];
					if (e === t) return S = !0, 0;
					else if (!o || !s) return e === i ? -1 : t === i ? 1 : o ? -1 : s ? 1 : 0;
					else if (o === s) return a(e, t);
					for (n = e; n = n.parentNode;) u.unshift(n);
					for (n = t; n = n.parentNode;) l.unshift(n);
					for (; u[r] === l[r];) r++;
					return r ? a(u[r], l[r]) : u[r] === F ? -1 : l[r] === F ? 1 : 0
				}, S = !1, [0, 0].sort(q), $.detectDuplicates = S, j
			}, s.matches = function(e, t) {
				return s(e, null, null, t)
			}, s.matchesSelector = function(e, t) {
				if ((e.ownerDocument || e) !== j) A(e);
				if (t = t.replace(bt, "='$1']"), !(!$.matchesSelector || M || H && H.test(t) || R.test(t))) try {
					var n = O.call(e, t);
					if (n || $.disconnectedMatch || e.document && 11 !== e.document.nodeType) return n
				} catch (i) {}
				return s(t, j, null, [e]).length > 0
			}, s.contains = function(e, t) {
				if ((e.ownerDocument || e) !== j) A(e);
				return I(e, t)
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
						} : function(t, n, u) {
							var l, f, c, p, d, h, g = o !== s ? "nextSibling" : "previousSibling",
								m = t.parentNode,
								y = a && t.nodeName.toLowerCase(),
								v = !u && !a;
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
									for (f = m[P] || (m[P] = {}), l = f[e] || [], d = l[0] === B && l[1], p = l[0] === B && l[2], c = d && m.childNodes[d]; c = ++d && c && c[g] || (p = d = 0) || h.pop();)
										if (1 === c.nodeType && ++p && c === t) {
											f[e] = [B, d, p];
											break
										}
								} else if (v && (l = (t[P] || (t[P] = {}))[e]) && l[0] === B) p = l[1];
								else
									for (; c = ++d && c && c[g] || (p = d = 0) || h.pop();)
										if ((a ? c.nodeName.toLowerCase() === y : 1 === c.nodeType) && ++p) {
											if (v)(c[P] || (c[P] = {}))[e] = [B, p];
											if (c === t) break
										}
								return p -= r, p === i || p % i === 0 && p / i >= 0
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
							i = _(e.replace(st, "$1"));
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
				}) C.pseudos[T] = u(T);
			for (T in {
					submit: !0,
					reset: !0
				}) C.pseudos[T] = l(T);
			_ = s.compile = function(e, t) {
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
			}, C.pseudos.nth = C.pseudos.eq, C.filters = w.prototype = C.pseudos, C.setFilters = new w, A(), s.attr = ut.attr, ut.find = s, ut.expr = s.selectors, ut.expr[":"] = ut.expr.pseudos, ut.unique = s.uniqueSort, ut.text = s.getText, ut.isXMLDoc = s.isXML, ut.contains = s.contains
		}(e);
	var $t = /Until$/,
		Bt = /^(?:parents|prev(?:Until|All))/,
		Wt = /^.[^:#\[\.,]*$/,
		zt = ut.expr.match.needsContext,
		Yt = {
			children: !0,
			contents: !0,
			next: !0,
			prev: !0
		};
	ut.fn.extend({
		find: function(e) {
			var t, n, i, r = this.length;
			if ("string" != typeof e) return i = this, this.pushStack(ut(e).filter(function() {
				for (t = 0; r > t; t++)
					if (ut.contains(i[t], this)) return !0
			}));
			for (n = [], t = 0; r > t; t++) ut.find(e, this[t], n);
			return n = this.pushStack(r > 1 ? ut.unique(n) : n), n.selector = (this.selector ? this.selector + " " : "") + e, n
		},
		has: function(e) {
			var t, n = ut(e, this),
				i = n.length;
			return this.filter(function() {
				for (t = 0; i > t; t++)
					if (ut.contains(this, n[t])) return !0
			})
		},
		not: function(e) {
			return this.pushStack(c(this, e, !1))
		},
		filter: function(e) {
			return this.pushStack(c(this, e, !0))
		},
		is: function(e) {
			return !!e && ("string" == typeof e ? zt.test(e) ? ut(e, this.context).index(this[0]) >= 0 : ut.filter(e, this).length > 0 : this.filter(e).length > 0)
		},
		closest: function(e, t) {
			for (var n, i = 0, r = this.length, o = [], s = zt.test(e) || "string" != typeof e ? ut(e, t || this.context) : 0; r > i; i++)
				for (n = this[i]; n && n.ownerDocument && n !== t && 11 !== n.nodeType;) {
					if (s ? s.index(n) > -1 : ut.find.matchesSelector(n, e)) {
						o.push(n);
						break
					}
					n = n.parentNode
				}
			return this.pushStack(o.length > 1 ? ut.unique(o) : o)
		},
		index: function(e) {
			if (!e) return this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
			if ("string" == typeof e) return ut.inArray(this[0], ut(e));
			else return ut.inArray(e.jquery ? e[0] : e, this)
		},
		add: function(e, t) {
			var n = "string" == typeof e ? ut(e, t) : ut.makeArray(e && e.nodeType ? [e] : e),
				i = ut.merge(this.get(), n);
			return this.pushStack(ut.unique(i))
		},
		addBack: function(e) {
			return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
		}
	}), ut.fn.andSelf = ut.fn.addBack, ut.each({
		parent: function(e) {
			var t = e.parentNode;
			return t && 11 !== t.nodeType ? t : null
		},
		parents: function(e) {
			return ut.dir(e, "parentNode")
		},
		parentsUntil: function(e, t, n) {
			return ut.dir(e, "parentNode", n)
		},
		next: function(e) {
			return f(e, "nextSibling")
		},
		prev: function(e) {
			return f(e, "previousSibling")
		},
		nextAll: function(e) {
			return ut.dir(e, "nextSibling")
		},
		prevAll: function(e) {
			return ut.dir(e, "previousSibling")
		},
		nextUntil: function(e, t, n) {
			return ut.dir(e, "nextSibling", n)
		},
		prevUntil: function(e, t, n) {
			return ut.dir(e, "previousSibling", n)
		},
		siblings: function(e) {
			return ut.sibling((e.parentNode || {}).firstChild, e)
		},
		children: function(e) {
			return ut.sibling(e.firstChild)
		},
		contents: function(e) {
			return ut.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : ut.merge([], e.childNodes)
		}
	}, function(e, t) {
		ut.fn[e] = function(n, i) {
			var r = ut.map(this, t, n);
			if (!$t.test(e)) i = n;
			if (i && "string" == typeof i) r = ut.filter(i, r);
			if (r = this.length > 1 && !Yt[e] ? ut.unique(r) : r, this.length > 1 && Bt.test(e)) r = r.reverse();
			return this.pushStack(r)
		}
	}), ut.extend({
		filter: function(e, t, n) {
			if (n) e = ":not(" + e + ")";
			return 1 === t.length ? ut.find.matchesSelector(t[0], e) ? [t[0]] : [] : ut.find.matches(e, t)
		},
		dir: function(e, n, i) {
			for (var r = [], o = e[n]; o && 9 !== o.nodeType && (i === t || 1 !== o.nodeType || !ut(o).is(i));) {
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
		Ut = new RegExp("<(?:" + Gt + ")[\\s/>]", "i"),
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
			_default: ut.support.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
		},
		un = p(U),
		ln = un.appendChild(U.createElement("div"));
	an.optgroup = an.option, an.tbody = an.tfoot = an.colgroup = an.caption = an.thead, an.th = an.td, ut.fn.extend({
		text: function(e) {
			return ut.access(this, function(e) {
				return e === t ? ut.text(this) : this.empty().append((this[0] && this[0].ownerDocument || U).createTextNode(e))
			}, null, e, arguments.length)
		},
		wrapAll: function(e) {
			if (ut.isFunction(e)) return this.each(function(t) {
				ut(this).wrapAll(e.call(this, t))
			});
			if (this[0]) {
				var t = ut(e, this[0].ownerDocument).eq(0).clone(!0);
				if (this[0].parentNode) t.insertBefore(this[0]);
				t.map(function() {
					for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;) e = e.firstChild;
					return e
				}).append(this)
			}
			return this
		},
		wrapInner: function(e) {
			if (ut.isFunction(e)) return this.each(function(t) {
				ut(this).wrapInner(e.call(this, t))
			});
			else return this.each(function() {
				var t = ut(this),
					n = t.contents();
				if (n.length) n.wrapAll(e);
				else t.append(e)
			})
		},
		wrap: function(e) {
			var t = ut.isFunction(e);
			return this.each(function(n) {
				ut(this).wrapAll(t ? e.call(this, n) : e)
			})
		},
		unwrap: function() {
			return this.parent().each(function() {
				if (!ut.nodeName(this, "body")) ut(this).replaceWith(this.childNodes)
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
				if (!e || ut.filter(e, [n]).length > 0) {
					if (!t && 1 === n.nodeType) ut.cleanData(b(n));
					if (n.parentNode) {
						if (t && ut.contains(n.ownerDocument, n)) m(b(n, "script"));
						n.parentNode.removeChild(n)
					}
				}
			return this
		},
		empty: function() {
			for (var e, t = 0; null != (e = this[t]); t++) {
				if (1 === e.nodeType) ut.cleanData(b(e, !1));
				for (; e.firstChild;) e.removeChild(e.firstChild);
				if (e.options && ut.nodeName(e, "select")) e.options.length = 0
			}
			return this
		},
		clone: function(e, t) {
			return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function() {
				return ut.clone(this, e, t)
			})
		},
		html: function(e) {
			return ut.access(this, function(e) {
				var n = this[0] || {},
					i = 0,
					r = this.length;
				if (e === t) return 1 === n.nodeType ? n.innerHTML.replace(Xt, "") : t;
				if (!("string" != typeof e || en.test(e) || !ut.support.htmlSerialize && Ut.test(e) || !ut.support.leadingWhitespace && Vt.test(e) || an[(Jt.exec(e) || ["", ""])[1].toLowerCase()])) {
					e = e.replace(Kt, "<$1></$2>");
					try {
						for (; r > i; i++)
							if (n = this[i] || {}, 1 === n.nodeType) ut.cleanData(b(n, !1)), n.innerHTML = e;
						n = 0
					} catch (o) {}
				}
				if (n) this.empty().append(e)
			}, null, e, arguments.length)
		},
		replaceWith: function(e) {
			var t = ut.isFunction(e);
			if (!t && "string" != typeof e) e = ut(e).not(this).detach();
			return this.domManip([e], !0, function(e) {
				var t = this.nextSibling,
					n = this.parentNode;
				if (n) ut(this).remove(), n.insertBefore(e, t)
			})
		},
		detach: function(e) {
			return this.remove(e, !0)
		},
		domManip: function(e, n, i) {
			e = tt.apply([], e);
			var r, o, s, a, u, l, f = 0,
				c = this.length,
				p = this,
				m = c - 1,
				y = e[0],
				v = ut.isFunction(y);
			if (v || !(1 >= c || "string" != typeof y || ut.support.checkClone) && nn.test(y)) return this.each(function(r) {
				var o = p.eq(r);
				if (v) e[0] = y.call(this, r, n ? o.html() : t);
				o.domManip(e, n, i)
			});
			if (c) {
				if (l = ut.buildFragment(e, this[0].ownerDocument, !1, this), r = l.firstChild, 1 === l.childNodes.length) l = r;
				if (r) {
					for (n = n && ut.nodeName(r, "tr"), a = ut.map(b(l, "script"), h), s = a.length; c > f; f++) {
						if (o = l, f !== m)
							if (o = ut.clone(o, !0, !0), s) ut.merge(a, b(o, "script"));
						i.call(n && ut.nodeName(this[f], "table") ? d(this[f], "tbody") : this[f], o, f)
					}
					if (s)
						for (u = a[a.length - 1].ownerDocument, ut.map(a, g), f = 0; s > f; f++)
							if (o = a[f], rn.test(o.type || "") && !ut._data(o, "globalEval") && ut.contains(u, o))
								if (o.src) ut.ajax({
									url: o.src,
									type: "GET",
									dataType: "script",
									async: !1,
									global: !1,
									"throws": !0
								});
								else ut.globalEval((o.text || o.textContent || o.innerHTML || "").replace(sn, ""));
					l = r = null
				}
			}
			return this
		}
	}), ut.each({
		appendTo: "append",
		prependTo: "prepend",
		insertBefore: "before",
		insertAfter: "after",
		replaceAll: "replaceWith"
	}, function(e, t) {
		ut.fn[e] = function(e) {
			for (var n, i = 0, r = [], o = ut(e), s = o.length - 1; s >= i; i++) n = i === s ? this : this.clone(!0), ut(o[i])[t](n), nt.apply(r, n.get());
			return this.pushStack(r)
		}
	}), ut.extend({
		clone: function(e, t, n) {
			var i, r, o, s, a, u = ut.contains(e.ownerDocument, e);
			if (ut.support.html5Clone || ut.isXMLDoc(e) || !Ut.test("<" + e.nodeName + ">")) o = e.cloneNode(!0);
			else ln.innerHTML = e.outerHTML, ln.removeChild(o = ln.firstChild);
			if (!(ut.support.noCloneEvent && ut.support.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || ut.isXMLDoc(e)))
				for (i = b(o), a = b(e), s = 0; null != (r = a[s]); ++s)
					if (i[s]) v(r, i[s]);
			if (t)
				if (n)
					for (a = a || b(e), i = i || b(o), s = 0; null != (r = a[s]); s++) y(r, i[s]);
				else y(e, o);
			if (i = b(o, "script"), i.length > 0) m(i, !u && b(e, "script"));
			return i = a = r = null, o
		},
		buildFragment: function(e, t, n, i) {
			for (var r, o, s, a, u, l, f, c = e.length, d = p(t), h = [], g = 0; c > g; g++)
				if (o = e[g], o || 0 === o)
					if ("object" === ut.type(o)) ut.merge(h, o.nodeType ? [o] : o);
					else if (!Zt.test(o)) h.push(t.createTextNode(o));
			else {
				for (a = a || d.appendChild(t.createElement("div")), u = (Jt.exec(o) || ["", ""])[1].toLowerCase(), f = an[u] || an._default, a.innerHTML = f[1] + o.replace(Kt, "<$1></$2>") + f[2], r = f[0]; r--;) a = a.lastChild;
				if (!ut.support.leadingWhitespace && Vt.test(o)) h.push(t.createTextNode(Vt.exec(o)[0]));
				if (!ut.support.tbody)
					for (o = "table" === u && !Qt.test(o) ? a.firstChild : "<table>" === f[1] && !Qt.test(o) ? a : 0, r = o && o.childNodes.length; r--;)
						if (ut.nodeName(l = o.childNodes[r], "tbody") && !l.childNodes.length) o.removeChild(l);
				for (ut.merge(h, a.childNodes), a.textContent = ""; a.firstChild;) a.removeChild(a.firstChild);
				a = d.lastChild
			}
			if (a) d.removeChild(a);
			if (!ut.support.appendChecked) ut.grep(b(h, "input"), x);
			for (g = 0; o = h[g++];)
				if (!i || -1 === ut.inArray(o, i)) {
					if (s = ut.contains(o.ownerDocument, o), a = b(d.appendChild(o), "script"), s) m(a);
					if (n)
						for (r = 0; o = a[r++];)
							if (rn.test(o.type || "")) n.push(o)
				} else;
			return a = null, d
		},
		cleanData: function(e, t) {
			for (var n, i, r, o, s = 0, a = ut.expando, u = ut.cache, l = ut.support.deleteExpando, f = ut.event.special; null != (n = e[s]); s++)
				if (t || ut.acceptData(n))
					if (r = n[a], o = r && u[r]) {
						if (o.events)
							for (i in o.events)
								if (f[i]) ut.event.remove(n, i);
								else ut.removeEvent(n, i, o.handle);
						if (u[r]) {
							if (delete u[r], l) delete n[a];
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
		vn = new RegExp("^(" + lt + ")(.*)$", "i"),
		bn = new RegExp("^(" + lt + ")(?!px)[a-z%]+$", "i"),
		xn = new RegExp("^([+-])=(" + lt + ")", "i"),
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
	if (ut.fn.extend({
			css: function(e, n) {
				return ut.access(this, function(e, n, i) {
					var r, o, s = {},
						a = 0;
					if (ut.isArray(n)) {
						for (o = cn(e), r = n.length; r > a; a++) s[n[a]] = ut.css(e, n[a], !1, o);
						return s
					}
					return i !== t ? ut.style(e, n, i) : ut.css(e, n)
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
					if (t ? e : T(this)) ut(this).show();
					else ut(this).hide()
				})
			}
		}), ut.extend({
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
				"float": ut.support.cssFloat ? "cssFloat" : "styleFloat"
			},
			style: function(e, n, i, r) {
				if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
					var o, s, a, u = ut.camelCase(n),
						l = e.style;
					if (n = ut.cssProps[u] || (ut.cssProps[u] = w(l, u)), a = ut.cssHooks[n] || ut.cssHooks[u], i !== t) {
						if (s = typeof i, "string" === s && (o = xn.exec(i))) i = (o[1] + 1) * o[2] + parseFloat(ut.css(e, n)), s = "number";
						if (null == i || "number" === s && isNaN(i)) return;
						if ("number" === s && !ut.cssNumber[u]) i += "px";
						if (!ut.support.clearCloneStyle && "" === i && 0 === n.indexOf("background")) l[n] = "inherit";
						if (!(a && "set" in a && (i = a.set(e, i, r)) === t)) try {
							l[n] = i
						} catch (f) {}
					} else if (a && "get" in a && (o = a.get(e, !1, r)) !== t) return o;
					else return l[n]
				}
			},
			css: function(e, n, i, r) {
				var o, s, a, u = ut.camelCase(n);
				if (n = ut.cssProps[u] || (ut.cssProps[u] = w(e.style, u)), a = ut.cssHooks[n] || ut.cssHooks[u], a && "get" in a) s = a.get(e, !0, i);
				if (s === t) s = pn(e, n, r);
				if ("normal" === s && n in En) s = En[n];
				if ("" === i || i) return o = parseFloat(s), i === !0 || ut.isNumeric(o) ? o || 0 : s;
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
			u = a ? a.getPropertyValue(n) || a[n] : t,
			l = e.style;
		if (a) {
			if ("" === u && !ut.contains(e.ownerDocument, e)) u = ut.style(e, n);
			if (bn.test(u) && yn.test(n)) r = l.width, o = l.minWidth, s = l.maxWidth, l.minWidth = l.maxWidth = l.width = u, u = a.width, l.width = r, l.minWidth = o, l.maxWidth = s
		}
		return u
	};
	else if (U.documentElement.currentStyle) cn = function(e) {
		return e.currentStyle
	}, pn = function(e, n, i) {
		var r, o, s, a = i || cn(e),
			u = a ? a[n] : t,
			l = e.style;
		if (null == u && l && l[n]) u = l[n];
		if (bn.test(u) && !gn.test(n)) {
			if (r = l.left, o = e.runtimeStyle, s = o && o.left) o.left = e.currentStyle.left;
			if (l.left = "fontSize" === n ? "1em" : u, u = l.pixelLeft + "px", l.left = r, s) o.left = s
		}
		return "" === u ? "auto" : u
	};
	if (ut.each(["height", "width"], function(e, t) {
			ut.cssHooks[t] = {
				get: function(e, n, i) {
					if (n) return 0 === e.offsetWidth && mn.test(ut.css(e, "display")) ? ut.swap(e, Tn, function() {
						return k(e, t, i)
					}) : k(e, t, i);
					else return void 0
				},
				set: function(e, n, i) {
					var r = i && cn(e);
					return C(e, n, i ? N(e, t, i, ut.support.boxSizing && "border-box" === ut.css(e, "boxSizing", !1, r), r) : 0)
				}
			}
		}), !ut.support.opacity) ut.cssHooks.opacity = {
		get: function(e, t) {
			return hn.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
		},
		set: function(e, t) {
			var n = e.style,
				i = e.currentStyle,
				r = ut.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "",
				o = i && i.filter || n.filter || "";
			if (n.zoom = 1, (t >= 1 || "" === t) && "" === ut.trim(o.replace(dn, "")) && n.removeAttribute)
				if (n.removeAttribute("filter"), "" === t || i && !i.filter) return;
			n.filter = dn.test(o) ? o.replace(dn, r) : o + " " + r
		}
	};
	if (ut(function() {
			if (!ut.support.reliableMarginRight) ut.cssHooks.marginRight = {
				get: function(e, t) {
					if (t) return ut.swap(e, {
						display: "inline-block"
					}, pn, [e, "marginRight"]);
					else return void 0
				}
			};
			if (!ut.support.pixelPosition && ut.fn.position) ut.each(["top", "left"], function(e, t) {
				ut.cssHooks[t] = {
					get: function(e, n) {
						if (n) return n = pn(e, t), bn.test(n) ? ut(e).position()[t] + "px" : n;
						else return void 0
					}
				}
			})
		}), ut.expr && ut.expr.filters) ut.expr.filters.hidden = function(e) {
		return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !ut.support.reliableHiddenOffsets && "none" === (e.style && e.style.display || ut.css(e, "display"))
	}, ut.expr.filters.visible = function(e) {
		return !ut.expr.filters.hidden(e)
	};
	ut.each({
		margin: "",
		padding: "",
		border: "Width"
	}, function(e, t) {
		if (ut.cssHooks[e + t] = {
				expand: function(n) {
					for (var i = 0, r = {}, o = "string" == typeof n ? n.split(" ") : [n]; 4 > i; i++) r[e + Cn[i] + t] = o[i] || o[i - 2] || o[0];
					return r
				}
			}, !yn.test(e)) ut.cssHooks[e + t].set = C
	});
	var kn = /%20/g,
		_n = /\[\]$/,
		Sn = /\r?\n/g,
		Dn = /^(?:submit|button|image|reset|file)$/i,
		An = /^(?:input|select|textarea|keygen)/i;
	ut.fn.extend({
		serialize: function() {
			return ut.param(this.serializeArray())
		},
		serializeArray: function() {
			return this.map(function() {
				var e = ut.prop(this, "elements");
				return e ? ut.makeArray(e) : this
			}).filter(function() {
				var e = this.type;
				return this.name && !ut(this).is(":disabled") && An.test(this.nodeName) && !Dn.test(e) && (this.checked || !tn.test(e))
			}).map(function(e, t) {
				var n = ut(this).val();
				return null == n ? null : ut.isArray(n) ? ut.map(n, function(e) {
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
	}), ut.param = function(e, n) {
		var i, r = [],
			o = function(e, t) {
				t = ut.isFunction(t) ? t() : null == t ? "" : t, r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
			};
		if (n === t) n = ut.ajaxSettings && ut.ajaxSettings.traditional;
		if (ut.isArray(e) || e.jquery && !ut.isPlainObject(e)) ut.each(e, function() {
			o(this.name, this.value)
		});
		else
			for (i in e) D(i, e[i], n, o);
		return r.join("&").replace(kn, "+")
	}, ut.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(e, t) {
		ut.fn[t] = function(e, n) {
			return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
		}
	}), ut.fn.hover = function(e, t) {
		return this.mouseenter(e).mouseleave(t || e)
	};
	var jn, Ln, Mn = ut.now(),
		Rn = /\?/,
		Hn = /#.*$/,
		On = /([?&])_=[^&]*/,
		In = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
		qn = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
		Pn = /^(?:GET|HEAD)$/,
		Fn = /^\/\//,
		$n = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,
		Bn = ut.fn.load,
		Wn = {},
		zn = {},
		Yn = "*/".concat("*");
	try {
		Ln = V.href
	} catch (Gn) {
		Ln = U.createElement("a"), Ln.href = "", Ln = Ln.href
	}
	jn = $n.exec(Ln.toLowerCase()) || [], ut.fn.load = function(e, n, i) {
		if ("string" != typeof e && Bn) return Bn.apply(this, arguments);
		var r, o, s, a = this,
			u = e.indexOf(" ");
		if (u >= 0) r = e.slice(u, e.length), e = e.slice(0, u);
		if (ut.isFunction(n)) i = n, n = t;
		else if (n && "object" == typeof n) s = "POST";
		if (a.length > 0) ut.ajax({
			url: e,
			type: s,
			dataType: "html",
			data: n
		}).done(function(e) {
			o = arguments, a.html(r ? ut("<div>").append(ut.parseHTML(e)).find(r) : e)
		}).complete(i && function(e, t) {
			a.each(i, o || [e.responseText, t, e])
		});
		return this
	}, ut.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(e, t) {
		ut.fn[t] = function(e) {
			return this.on(t, e)
		}
	}), ut.each(["get", "post"], function(e, n) {
		ut[n] = function(e, i, r, o) {
			if (ut.isFunction(i)) o = o || r, r = i, i = t;
			return ut.ajax({
				url: e,
				type: n,
				dataType: o,
				data: i,
				success: r
			})
		}
	}), ut.extend({
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
				"text json": ut.parseJSON,
				"text xml": ut.parseXML
			},
			flatOptions: {
				url: !0,
				context: !0
			}
		},
		ajaxSetup: function(e, t) {
			return t ? L(L(e, ut.ajaxSettings), t) : L(ut.ajaxSettings, e)
		},
		ajaxPrefilter: A(Wn),
		ajaxTransport: A(zn),
		ajax: function(e, n) {
			function i(e, n, i, r) {
				var o, c, v, b, w, E = n;
				if (2 !== x) {
					if (x = 2, u) clearTimeout(u);
					if (f = t, a = r || "", T.readyState = e > 0 ? 4 : 0, i) b = M(p, T, i);
					if (e >= 200 && 300 > e || 304 === e) {
						if (p.ifModified) {
							if (w = T.getResponseHeader("Last-Modified")) ut.lastModified[s] = w;
							if (w = T.getResponseHeader("etag")) ut.etag[s] = w
						}
						if (204 === e) o = !0, E = "nocontent";
						else if (304 === e) o = !0, E = "notmodified";
						else o = R(p, b), E = o.state, c = o.data, v = o.error, o = !v
					} else if (v = E, e || !E)
						if (E = "error", 0 > e) e = 0;
					if (T.status = e, T.statusText = (n || E) + "", o) g.resolveWith(d, [c, E, T]);
					else g.rejectWith(d, [T, E, v]);
					if (T.statusCode(y), y = t, l) h.trigger(o ? "ajaxSuccess" : "ajaxError", [T, p, o ? c : v]);
					if (m.fireWith(d, [T, E]), l)
						if (h.trigger("ajaxComplete", [T, p]), !--ut.active) ut.event.trigger("ajaxStop")
				}
			}
			if ("object" == typeof e) n = e, e = t;
			n = n || {};
			var r, o, s, a, u, l, f, c, p = ut.ajaxSetup({}, n),
				d = p.context || p,
				h = p.context && (d.nodeType || d.jquery) ? ut(d) : ut.event,
				g = ut.Deferred(),
				m = ut.Callbacks("once memory"),
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
								for (c = {}; t = In.exec(a);) c[t[1].toLowerCase()] = t[2];
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
			if (g.promise(T).complete = m.add, T.success = T.done, T.error = T.fail, p.url = ((e || p.url || Ln) + "").replace(Hn, "").replace(Fn, jn[1] + "//"), p.type = n.method || n.type || p.method || p.type, p.dataTypes = ut.trim(p.dataType || "*").toLowerCase().match(ft) || [""], null == p.crossDomain) r = $n.exec(p.url.toLowerCase()), p.crossDomain = !(!r || r[1] === jn[1] && r[2] === jn[2] && (r[3] || ("http:" === r[1] ? 80 : 443)) == (jn[3] || ("http:" === jn[1] ? 80 : 443)));
			if (p.data && p.processData && "string" != typeof p.data) p.data = ut.param(p.data, p.traditional);
			if (j(Wn, p, n, T), 2 === x) return T;
			if (l = p.global, l && 0 === ut.active++) ut.event.trigger("ajaxStart");
			if (p.type = p.type.toUpperCase(), p.hasContent = !Pn.test(p.type), s = p.url, !p.hasContent) {
				if (p.data) s = p.url += (Rn.test(s) ? "&" : "?") + p.data, delete p.data;
				if (p.cache === !1) p.url = On.test(s) ? s.replace(On, "$1_=" + Mn++) : s + (Rn.test(s) ? "&" : "?") + "_=" + Mn++
			}
			if (p.ifModified) {
				if (ut.lastModified[s]) T.setRequestHeader("If-Modified-Since", ut.lastModified[s]);
				if (ut.etag[s]) T.setRequestHeader("If-None-Match", ut.etag[s])
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
				if (T.readyState = 1, l) h.trigger("ajaxSend", [T, p]);
				if (p.async && p.timeout > 0) u = setTimeout(function() {
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
			return ut.get(e, t, n, "script")
		},
		getJSON: function(e, t, n) {
			return ut.get(e, t, n, "json")
		}
	}), ut.ajaxSetup({
		accepts: {
			script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
		},
		contents: {
			script: /(?:java|ecma)script/
		},
		converters: {
			"text script": function(e) {
				return ut.globalEval(e), e
			}
		}
	}), ut.ajaxPrefilter("script", function(e) {
		if (e.cache === t) e.cache = !1;
		if (e.crossDomain) e.type = "GET", e.global = !1
	}), ut.ajaxTransport("script", function(e) {
		if (e.crossDomain) {
			var n, i = U.head || ut("head")[0] || U.documentElement;
			return {
				send: function(t, r) {
					if (n = U.createElement("script"), n.async = !0, e.scriptCharset) n.charset = e.scriptCharset;
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
		Un = /(=)\?(?=&|$)|\?\?/;
	ut.ajaxSetup({
		jsonp: "callback",
		jsonpCallback: function() {
			var e = Xn.pop() || ut.expando + "_" + Mn++;
			return this[e] = !0, e
		}
	}), ut.ajaxPrefilter("json jsonp", function(n, i, r) {
		var o, s, a, u = n.jsonp !== !1 && (Un.test(n.url) ? "url" : "string" == typeof n.data && !(n.contentType || "").indexOf("application/x-www-form-urlencoded") && Un.test(n.data) && "data");
		if (u || "jsonp" === n.dataTypes[0]) {
			if (o = n.jsonpCallback = ut.isFunction(n.jsonpCallback) ? n.jsonpCallback() : n.jsonpCallback, u) n[u] = n[u].replace(Un, "$1" + o);
			else if (n.jsonp !== !1) n.url += (Rn.test(n.url) ? "&" : "?") + n.jsonp + "=" + o;
			return n.converters["script json"] = function() {
				if (!a) ut.error(o + " was not called");
				return a[0]
			}, n.dataTypes[0] = "json", s = e[o], e[o] = function() {
				a = arguments
			}, r.always(function() {
				if (e[o] = s, n[o]) n.jsonpCallback = i.jsonpCallback, Xn.push(o);
				if (a && ut.isFunction(s)) s(a[0]);
				a = s = t
			}), "script"
		}
	});
	var Vn, Kn, Jn = 0,
		Qn = e.ActiveXObject && function() {
			var e;
			for (e in Vn) Vn[e](t, !0)
		};
	if (ut.ajaxSettings.xhr = e.ActiveXObject ? function() {
			return !this.isLocal && H() || O()
		} : H, Kn = ut.ajaxSettings.xhr(), ut.support.cors = !!Kn && "withCredentials" in Kn, Kn = ut.support.ajax = !!Kn) ut.ajaxTransport(function(n) {
		if (!n.crossDomain || ut.support.cors) {
			var i;
			return {
				send: function(r, o) {
					var s, a, u = n.xhr();
					if (n.username) u.open(n.type, n.url, n.async, n.username, n.password);
					else u.open(n.type, n.url, n.async);
					if (n.xhrFields)
						for (a in n.xhrFields) u[a] = n.xhrFields[a];
					if (n.mimeType && u.overrideMimeType) u.overrideMimeType(n.mimeType);
					if (!n.crossDomain && !r["X-Requested-With"]) r["X-Requested-With"] = "XMLHttpRequest";
					try {
						for (a in r) u.setRequestHeader(a, r[a])
					} catch (l) {}
					if (u.send(n.hasContent && n.data || null), i = function(e, r) {
							var a, l, f, c;
							try {
								if (i && (r || 4 === u.readyState)) {
									if (i = t, s)
										if (u.onreadystatechange = ut.noop, Qn) delete Vn[s];
									if (r) {
										if (4 !== u.readyState) u.abort()
									} else {
										if (c = {}, a = u.status, l = u.getAllResponseHeaders(), "string" == typeof u.responseText) c.text = u.responseText;
										try {
											f = u.statusText
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
							if (c) o(a, f, c, l)
						}, !n.async) i();
					else if (4 === u.readyState) setTimeout(i);
					else {
						if (s = ++Jn, Qn) {
							if (!Vn) Vn = {}, ut(e).unload(Qn);
							Vn[s] = i
						}
						u.onreadystatechange = i
					}
				},
				abort: function() {
					if (i) i(t, !0)
				}
			}
		}
	});
	var Zn, ei, ti = /^(?:toggle|show|hide)$/,
		ni = new RegExp("^(?:([+-])=|)(" + lt + ")([a-z%]*)$", "i"),
		ii = /queueHooks$/,
		ri = [$],
		oi = {
			"*": [function(e, t) {
				var n, i, r = this.createTween(e, t),
					o = ni.exec(t),
					s = r.cur(),
					a = +s || 0,
					u = 1,
					l = 20;
				if (o) {
					if (n = +o[2], i = o[3] || (ut.cssNumber[e] ? "" : "px"), "px" !== i && a) {
						a = ut.css(r.elem, e, !0) || n || 1;
						do u = u || ".5", a /= u, ut.style(r.elem, e, a + i); while (u !== (u = r.cur() / s) && 1 !== u && --l)
					}
					r.unit = i, r.start = a, r.end = o[1] ? a + (o[1] + 1) * n : n
				}
				return r
			}]
		};
	if (ut.Animation = ut.extend(P, {
			tweener: function(e, t) {
				if (ut.isFunction(e)) t = e, e = ["*"];
				else e = e.split(" ");
				for (var n, i = 0, r = e.length; r > i; i++) n = e[i], oi[n] = oi[n] || [], oi[n].unshift(t)
			},
			prefilter: function(e, t) {
				if (t) ri.unshift(e);
				else ri.push(e)
			}
		}), ut.Tween = B, B.prototype = {
			constructor: B,
			init: function(e, t, n, i, r, o) {
				this.elem = e, this.prop = n, this.easing = r || "swing", this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = o || (ut.cssNumber[n] ? "" : "px")
			},
			cur: function() {
				var e = B.propHooks[this.prop];
				return e && e.get ? e.get(this) : B.propHooks._default.get(this)
			},
			run: function(e) {
				var t, n = B.propHooks[this.prop];
				if (this.options.duration) this.pos = t = ut.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration);
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
					else return t = ut.css(e.elem, e.prop, ""), !t || "auto" === t ? 0 : t
				},
				set: function(e) {
					if (ut.fx.step[e.prop]) ut.fx.step[e.prop](e);
					else if (e.elem.style && (null != e.elem.style[ut.cssProps[e.prop]] || ut.cssHooks[e.prop])) ut.style(e.elem, e.prop, e.now + e.unit);
					else e.elem[e.prop] = e.now
				}
			}
		}, B.propHooks.scrollTop = B.propHooks.scrollLeft = {
			set: function(e) {
				if (e.elem.nodeType && e.elem.parentNode) e.elem[e.prop] = e.now
			}
		}, ut.each(["toggle", "show", "hide"], function(e, t) {
			var n = ut.fn[t];
			ut.fn[t] = function(e, i, r) {
				return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(W(t, !0), e, i, r)
			}
		}), ut.fn.extend({
			fadeTo: function(e, t, n, i) {
				return this.filter(T).css("opacity", 0).show().end().animate({
					opacity: t
				}, e, n, i)
			},
			animate: function(e, t, n, i) {
				var r = ut.isEmptyObject(e),
					o = ut.speed(t, n, i),
					s = function() {
						var t = P(this, ut.extend({}, e), o);
						if (s.finish = function() {
								t.stop(!0)
							}, r || ut._data(this, "finish")) t.stop(!0)
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
						o = ut.timers,
						s = ut._data(this);
					if (n) {
						if (s[n] && s[n].stop) r(s[n])
					} else
						for (n in s)
							if (s[n] && s[n].stop && ii.test(n)) r(s[n]);
					for (n = o.length; n--;)
						if (o[n].elem === this && (null == e || o[n].queue === e)) o[n].anim.stop(i), t = !1, o.splice(n, 1);
					if (t || !i) ut.dequeue(this, e)
				})
			},
			finish: function(e) {
				if (e !== !1) e = e || "fx";
				return this.each(function() {
					var t, n = ut._data(this),
						i = n[e + "queue"],
						r = n[e + "queueHooks"],
						o = ut.timers,
						s = i ? i.length : 0;
					if (n.finish = !0, ut.queue(this, e, []), r && r.cur && r.cur.finish) r.cur.finish.call(this);
					for (t = o.length; t--;)
						if (o[t].elem === this && o[t].queue === e) o[t].anim.stop(!0), o.splice(t, 1);
					for (t = 0; s > t; t++)
						if (i[t] && i[t].finish) i[t].finish.call(this);
					delete n.finish
				})
			}
		}), ut.each({
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
			ut.fn[e] = function(e, n, i) {
				return this.animate(t, e, n, i)
			}
		}), ut.speed = function(e, t, n) {
			var i = e && "object" == typeof e ? ut.extend({}, e) : {
				complete: n || !n && t || ut.isFunction(e) && e,
				duration: e,
				easing: n && t || t && !ut.isFunction(t) && t
			};
			if (i.duration = ut.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in ut.fx.speeds ? ut.fx.speeds[i.duration] : ut.fx.speeds._default, null == i.queue || i.queue === !0) i.queue = "fx";
			return i.old = i.complete, i.complete = function() {
				if (ut.isFunction(i.old)) i.old.call(this);
				if (i.queue) ut.dequeue(this, i.queue)
			}, i
		}, ut.easing = {
			linear: function(e) {
				return e
			},
			swing: function(e) {
				return .5 - Math.cos(e * Math.PI) / 2
			}
		}, ut.timers = [], ut.fx = B.prototype.init, ut.fx.tick = function() {
			var e, n = ut.timers,
				i = 0;
			for (Zn = ut.now(); i < n.length; i++)
				if (e = n[i], !e() && n[i] === e) n.splice(i--, 1);
			if (!n.length) ut.fx.stop();
			Zn = t
		}, ut.fx.timer = function(e) {
			if (e() && ut.timers.push(e)) ut.fx.start()
		}, ut.fx.interval = 13, ut.fx.start = function() {
			if (!ei) ei = setInterval(ut.fx.tick, ut.fx.interval)
		}, ut.fx.stop = function() {
			clearInterval(ei), ei = null
		}, ut.fx.speeds = {
			slow: 600,
			fast: 200,
			_default: 400
		}, ut.fx.step = {}, ut.expr && ut.expr.filters) ut.expr.filters.animated = function(e) {
		return ut.grep(ut.timers, function(t) {
			return e === t.elem
		}).length
	};
	if (ut.fn.offset = function(e) {
			if (arguments.length) return e === t ? this : this.each(function(t) {
				ut.offset.setOffset(this, e, t)
			});
			var n, i, r = {
					top: 0,
					left: 0
				},
				o = this[0],
				s = o && o.ownerDocument;
			if (s) {
				if (n = s.documentElement, !ut.contains(n, o)) return r;
				if (typeof o.getBoundingClientRect !== X) r = o.getBoundingClientRect();
				return i = z(s), {
					top: r.top + (i.pageYOffset || n.scrollTop) - (n.clientTop || 0),
					left: r.left + (i.pageXOffset || n.scrollLeft) - (n.clientLeft || 0)
				}
			}
		}, ut.offset = {
			setOffset: function(e, t, n) {
				var i = ut.css(e, "position");
				if ("static" === i) e.style.position = "relative";
				var r, o, s = ut(e),
					a = s.offset(),
					u = ut.css(e, "top"),
					l = ut.css(e, "left"),
					f = ("absolute" === i || "fixed" === i) && ut.inArray("auto", [u, l]) > -1,
					c = {},
					p = {};
				if (f) p = s.position(), r = p.top, o = p.left;
				else r = parseFloat(u) || 0, o = parseFloat(l) || 0;
				if (ut.isFunction(t)) t = t.call(e, n, a);
				if (null != t.top) c.top = t.top - a.top + r;
				if (null != t.left) c.left = t.left - a.left + o;
				if ("using" in t) t.using.call(e, c);
				else s.css(c)
			}
		}, ut.fn.extend({
			position: function() {
				if (this[0]) {
					var e, t, n = {
							top: 0,
							left: 0
						},
						i = this[0];
					if ("fixed" === ut.css(i, "position")) t = i.getBoundingClientRect();
					else {
						if (e = this.offsetParent(), t = this.offset(), !ut.nodeName(e[0], "html")) n = e.offset();
						n.top += ut.css(e[0], "borderTopWidth", !0), n.left += ut.css(e[0], "borderLeftWidth", !0)
					}
					return {
						top: t.top - n.top - ut.css(i, "marginTop", !0),
						left: t.left - n.left - ut.css(i, "marginLeft", !0)
					}
				}
			},
			offsetParent: function() {
				return this.map(function() {
					for (var e = this.offsetParent || U.documentElement; e && !ut.nodeName(e, "html") && "static" === ut.css(e, "position");) e = e.offsetParent;
					return e || U.documentElement
				})
			}
		}), ut.each({
			scrollLeft: "pageXOffset",
			scrollTop: "pageYOffset"
		}, function(e, n) {
			var i = /Y/.test(n);
			ut.fn[e] = function(r) {
				return ut.access(this, function(e, r, o) {
					var s = z(e);
					if (o === t) return s ? n in s ? s[n] : s.document.documentElement[r] : e[r];
					if (s) s.scrollTo(!i ? o : ut(s).scrollLeft(), i ? o : ut(s).scrollTop());
					else e[r] = o
				}, e, r, arguments.length, null)
			}
		}), ut.each({
			Height: "height",
			Width: "width"
		}, function(e, n) {
			ut.each({
				padding: "inner" + e,
				content: n,
				"": "outer" + e
			}, function(i, r) {
				ut.fn[r] = function(r, o) {
					var s = arguments.length && (i || "boolean" != typeof r),
						a = i || (r === !0 || o === !0 ? "margin" : "border");
					return ut.access(this, function(n, i, r) {
						var o;
						if (ut.isWindow(n)) return n.document.documentElement["client" + e];
						if (9 === n.nodeType) return o = n.documentElement, Math.max(n.body["scroll" + e], o["scroll" + e], n.body["offset" + e], o["offset" + e], o["client" + e]);
						else return r === t ? ut.css(n, i, a) : ut.style(n, i, r, a)
					}, n, s ? r : t, s, null)
				}
			})
		}), "function" == typeof define && define.amd) define("jquery/jquery.min", [], function() {
		return ut
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

		function u(e) {
			var t = arguments;
			return e.replace(/\{([0-9]+)\}/g, function(e, n) {
				return t[n - 0 + 1]
			})
		}

		function l(e) {
			return e = e.replace(/^\s*\*/, ""), u('gv({0},["{1}"])', s(e), e.replace(/\[['"]?([^'"]+)['"]?\]/g, function(e, t) {
				return "." + t
			}).split(".").join('","'))
		}

		function f(e, t, n, i, r, o) {
			for (var s = n.length, a = e.split(t), u = 0, l = [], f = 0, c = a.length; c > f; f++) {
				var p = a[f];
				if (f) {
					var d = 1;
					for (u++;;) {
						var h = p.indexOf(n);
						if (0 > h) {
							l.push(u > 1 && d ? t : "", p);
							break
						}
						if (u = i ? u - 1 : 0, l.push(u > 0 && d ? t : "", p.slice(0, h), u > 0 ? n : ""), p = p.slice(h + s), d = 0, 0 === u) break
					}
					if (0 === u) r(l.join("")), o(p), l = []
				} else p && o(p)
			}
			if (u > 0 && l.length > 0) o(t), o(l.join(""))
		}

		function c(e, t, n) {
			var i, r = [],
				o = t.options,
				a = "",
				u = "",
				p = "",
				d = "";
			if (n) a = "ts(", u = ")", p = M, d = R, i = o.defaultFilter;
			return f(e, o.variableOpen, o.variableClose, 1, function(e) {
				if (n && e.indexOf("|") < 0 && i) e += "|" + i;
				var o = e.indexOf("|"),
					s = (o > 0 ? e.slice(0, o) : e).replace(/^\s+/, "").replace(/\s+$/, ""),
					f = o > 0 ? e.slice(o + 1) : "",
					h = 0 === s.indexOf("*"),
					g = [h ? "" : a, l(s), h ? "" : u];
				if (f) {
					f = c(f, t);
					for (var m = f.split("|"), y = 0, v = m.length; v > y; y++) {
						var b = m[y];
						if (/^\s*([a-z0-9_-]+)(\((.*)\))?\s*$/i.test(b)) {
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

		function b(e, t) {
			if (!/^\s*([a-z0-9_-]+)\s*(\(([\s\S]*)\))?\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
			this.name = RegExp.$1, this.args = RegExp.$3, d.call(this, e, t), this.cloneProps = ["name", "args"]
		}

		function x(e, t) {
			if (!/^\s*([a-z0-9\/_-]+)\s*(\(([\s\S]*)\))?\s*$/i.test(e)) throw new Error("Invalid " + this.type + " syntax: " + e);
			this.name = RegExp.$1, this.args = RegExp.$3, d.call(this, e, t), this.cloneProps = ["name", "args"]
		}

		function w(e, t) {
			var n = new RegExp(u("^\\s*({0}[\\s\\S]+{1})\\s+as\\s+{0}([0-9a-z_]+){1}\\s*(,\\s*{0}([0-9a-z_]+){1})?\\s*$", a(t.options.variableOpen), a(t.options.variableClose)), "i");
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
			q[e] = t, t.prototype.type = e
		}

		function _(e) {
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
					if (n.beforeAdd(l), u.top().addChild(n), c = [], t.options.strip && l.current instanceof d) n.value = e.replace(/^[\x20\t\r]*\n/, "");
					l.current = n
				}
			}
			var r, o = t.options.commandOpen,
				s = t.options.commandClose,
				a = t.options.commandSyntax,
				u = new n,
				l = {
					engine: t,
					targets: [],
					stack: u,
					target: null
				},
				c = [];
			return f(e, o, s, 0, function(e) {
				var n = a.exec(e);
				if (n && (r = q[n[2].toLowerCase()]) && "function" == typeof r) {
					i();
					var u = l.current;
					if (t.options.strip && u instanceof p) u.value = u.value.replace(/\r?\n[\x20\t]*$/, "\n");
					if (n[1]) u = h(l, r);
					else {
						if (u = new r(n[3], t), "function" == typeof u.beforeOpen) u.beforeOpen(l);
						u.open(l)
					}
					l.current = u
				} else if (!/^\s*\/\//.test(e)) c.push(o, e, s);
				r = null
			}, function(e) {
				c.push(e)
			}), i(), h(l), l.targets
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
			H = "return r;";
		if ("undefined" != typeof navigator && /msie\s*([0-9]+)/i.test(navigator.userAgent) && RegExp.$1 - 0 < 8) L = "var r=[],ri=0;", M = "r[ri++]=", H = 'return r.join("");';
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
		r(g, d), r(m, d), r(y, d), r(v, d), r(b, d), r(x, d), r(w, d), r(T, d), r(E, T), r(C, T);
		var I = {
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
			if (this.state >= I.APPLIED) return 1;
			var n = this.blocks,
				i = this.engine.targets[e];
			if (i && i.applyMaster(i.master)) return this.children = i.clone().children, t(this), this.state = I.APPLIED, 1;
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
			if (this.state >= I.READY) return 1;
			var t = this.engine,
				n = 1;
			if (this.applyMaster(this.master)) return e(this), n && (this.state = I.READY), n;
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
			h(e), d.prototype.open.call(this, e), this.state = I.READING, N(this, e)
		}, v.prototype.open = x.prototype.open = function(e) {
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
			this.parent = e.stack.top(), this.target = e.target, d.prototype.open.call(this, e), this.state = I.READING, e.imp = this
		}, x.prototype.close = v.prototype.close = function() {}, y.prototype.close = function(e) {
			d.prototype.close.call(this, e), this.state = I.READED, e.imp = null
		}, g.prototype.close = function(e) {
			d.prototype.close.call(this, e), this.state = this.master ? I.READED : I.APPLIED, e.target = null
		}, y.prototype.autoClose = function(e) {
			var t = this.parent.children;
			t.push.apply(t, this.children), this.children.length = 0;
			for (var n in this.blocks) this.target.blocks[n] = this.blocks[n];
			this.blocks = {}, this.close(e)
		}, x.prototype.beforeOpen = y.prototype.beforeOpen = v.prototype.beforeOpen = w.prototype.beforeOpen = b.prototype.beforeOpen = m.prototype.beforeOpen = T.prototype.beforeOpen = p.prototype.beforeAdd = function(e) {
			if (!e.stack.bottom()) {
				var t = new g(i(), e.engine);
				t.open(e)
			}
		}, y.prototype.getRendererBody = function() {
			return this.applyMaster(this.name), d.prototype.getRendererBody.call(this)
		}, x.prototype.getRendererBody = function() {
			return u("{0}engine.render({2},{{3}}){1}", M, R, s(this.name), c(this.args, this.engine).replace(/(^|,)\s*([a-z0-9_]+)\s*=/gi, function(e, t, n) {
				return (t || "") + s(n) + ":"
			}))
		}, v.prototype.getRendererBody = function() {
			if (this.expr) return u("v[{0}]={1};", s(this.name), c(this.expr, this.engine));
			else return ""
		}, T.prototype.getRendererBody = function() {
			return u("if({0}){{1}}", c(this.value, this.engine), d.prototype.getRendererBody.call(this))
		}, C.prototype.getRendererBody = function() {
			return u("}else{{0}", d.prototype.getRendererBody.call(this))
		}, w.prototype.getRendererBody = function() {
			return u('var {0}={1};if({0} instanceof Array)for (var {4}=0,{5}={0}.length;{4}<{5};{4}++){v[{2}]={4};v[{3}]={0}[{4}];{6}}else if(typeof {0}==="object")for(var {4} in {0}){v[{2}]={4};v[{3}]={0}[{4}];{6}}', i(), c(this.list, this.engine), s(this.index || i()), s(this.item), i(), i(), d.prototype.getRendererBody.call(this))
		}, b.prototype.getRendererBody = function() {
			var e = this.args;
			return u("{2}fs[{5}]((function(){{0}{4}{1}})(){6}){3}", L, H, M, R, d.prototype.getRendererBody.call(this), s(this.name), e ? "," + c(e, this.engine) : "")
		};
		var q = {};
		k("target", g), k("block", m), k("import", y), k("use", x), k("var", v), k("for", w), k("if", T), k("elif", E), k("else", C), k("filter", b), _.prototype.config = function(e) {
			t(this.options, e)
		}, _.prototype.compile = _.prototype.parse = function(e) {
			if (e) {
				var t = S(e, this);
				if (t.length) return this.targets[t[0]].getRenderer()
			}
			return new Function('return ""')
		}, _.prototype.getRenderer = function(e) {
			var t = this.targets[e];
			if (t) return t.getRenderer();
			else return void 0
		}, _.prototype.render = function(e, t) {
			var n = this.getRenderer(e);
			if (n) return n(t);
			else return ""
		}, _.prototype.addFilter = function(e, t) {
			if ("function" == typeof t) this.filters[e] = t
		};
		var P = new _;
		if (P.Engine = _, "object" == typeof exports && "object" == typeof module) exports = module.exports = P;
		else if ("function" == typeof define && define.amd) define("etpl/main", [], P);
		else e.etpl = P
	}(this), define("etpl", ["etpl/main"], function(e) {
		return e
	}), define("common/common.tpl", [], function() {
		return '<!--* @ignore* @file error.tpl* @author mySunShinning(441984145@qq.com)*         yangbinYB(1033371745@qq.com)* @time 14-12-7--><!-- target: Loading --><div class="xjd-loading">&nbsp;</div><!-- /target --><!-- target: Error --><div class="xjd-error"><div class="xjd-error-msg">${msg}</div></div><!-- /target -->'
	}), define("common/header", ["require", "jquery", "etpl", "./common.tpl","common/extra/jquery.scrollUp"], function(require) {
		function e() {
			o.compile(s), n(), t(), i() ,scrollUp()
		}

		function t() {
			var e, t;
			r(".attention-me").mouseenter(function() {
				if (t && t !== r(this)) r(".xinlang-erweima").removeClass("current");
				clearTimeout(e), t = r(this), r(this).children(".xinlang-erweima").addClass("current")
			}).mouseleave(function() {
				e = setTimeout(function() {
					r(".attention-me").children(".xinlang-erweima").removeClass("current")
				}, 0)
			})
		}

		function n() {
			var e, t;
			r(".footer-site-me-xinlang").mouseenter(function() {
				if (t && t !== r(this)) r(".xinlang-erweima").removeClass("current");
				clearTimeout(e), t = r(this), r(this).children(".xinlang-erweima").addClass("current")
			}).mouseleave(function() {
				e = setTimeout(function() {
					r(".footer-site-me-xinlang").children(".xinlang-erweima").removeClass("current")
				}, 0)
			})
		}

		function i() {
			var e = r(".login-register");
			e.on("mouseenter", "a", function() {
				r(this).hasClass("login") ? e.removeClass("register-hover").addClass("login-hover") : e.removeClass("login-hover").addClass("register-hover")
			}).on("mouseleave", "a", function() {
				e.hasClass("register-hover") || e.hasClass("login-hover") || e.addClass("login-hover")
			})
		}
        function scrollUp () {
          var scrollUp = require('common/extra/jquery.scrollUp');
              scrollUp();
        }

		var r = require("jquery"),
			o = require("etpl"),
			s = require("./common.tpl");
		return {
			init: e
		}
	}), define("home/index", ["require", "jquery", "common/header"], function(require) {
		function e() {
			var e = require("common/header");
			e.init(), t(r(".banner-floation"), r(".banner-item"), r(".banner-item-list"), r(".banner-select-link")), n(), i()
		}

		function t(e, t, n, i) {
			function o(e) {
				u = e, c.removeClass("current"), c.eq(u).addClass("current"), a.stop(!0).animate({
					left: -u * f + "px"
				}, 300, function() {
					s = setTimeout(function() {
						u = ++u % p, o(u)
					}, 5e3)
				})
			}
			var s, a = t,
				u = 0,
				l = n,
				f = l.eq(0).width(),
				c = i,
				p = l.length,
				d = r(".jiantou-left"),
				h = r(".jiantou-right");
			d.click(function() {
				s && clearTimeout(s), u = (u - 1 + p) % p, console.log(u), o(u)
			}), h.click(function() {
				s && clearTimeout(s), u = (u + 1) % p, console.log(u), o(u)
			}), c.mouseenter(function() {
				s && clearTimeout(s);
				var e = r(this).index();
				o(e)
			}), s = setTimeout(function() {
				o(0)
			}, 5e3)
		}

		function n() {
			function e() {
				a.on("mouseenter", function() {
					n()
				}).on("mouseleave", function() {
					t()
				})
			}

			function t() {
				n(), o = setTimeout(function() {
					c = (c + 1) % l, a.animate({
						top: 0 == c ? -l * f : -c * f
					}, 800, function() {
						0 == c && a.css("top", 0), t()
					})
				}, 4e3)
			}

			function n() {
				clearTimeout(o)
			}

			function i() {
				var e = u.eq(0).clone();
				e.attr("data-order", l), a.append(e)
			}
			var o, s = r("#notice"),
				a = s.find(".item-wrap"),
				u = a.find(".item"),
				l = u.length,
				f = 64,
				c = 0;
			l > 1 && (i(), t(), e())
		}

		function i() {}
		var r = require("jquery");
		return {
			init: e
		}
	});