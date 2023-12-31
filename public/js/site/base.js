function _get() {
    return (
        (_get =
            "undefined" != typeof Reflect && Reflect.get
                ? Reflect.get.bind()
                : function (e, t, n) {
                      var r = _superPropBase(e, t);
                      if (r) {
                          var i = Object.getOwnPropertyDescriptor(r, t);
                          return i.get ? i.get.call(arguments.length < 3 ? e : n) : i.value;
                      }
                  }),
        _get.apply(this, arguments)
    );
}
function _superPropBase(e, t) {
    for (; !Object.prototype.hasOwnProperty.call(e, t) && null !== (e = _getPrototypeOf(e)); );
    return e;
}
function _inherits(e, t) {
    if ("function" != typeof t && null !== t) throw new TypeError("Super expression must either be null or a function");
    (e.prototype = Object.create(t && t.prototype, { constructor: { value: e, writable: !0, configurable: !0 } })), Object.defineProperty(e, "prototype", { writable: !1 }), t && _setPrototypeOf(e, t);
}
function _setPrototypeOf(e, t) {
    return (
        (_setPrototypeOf = Object.setPrototypeOf
            ? Object.setPrototypeOf.bind()
            : function (e, t) {
                  return (e.__proto__ = t), e;
              }),
        _setPrototypeOf(e, t)
    );
}
function _createSuper(e) {
    var t = _isNativeReflectConstruct();
    return function () {
        var n,
            r = _getPrototypeOf(e);
        if (t) {
            var i = _getPrototypeOf(this).constructor;
            n = Reflect.construct(r, arguments, i);
        } else n = r.apply(this, arguments);
        return _possibleConstructorReturn(this, n);
    };
}
function _possibleConstructorReturn(e, t) {
    if (t && ("object" === _typeof(t) || "function" == typeof t)) return t;
    if (void 0 !== t) throw new TypeError("Derived constructors may only return object or undefined");
    return _assertThisInitialized(e);
}
function _assertThisInitialized(e) {
    if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    return e;
}
function _isNativeReflectConstruct() {
    if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
    if (Reflect.construct.sham) return !1;
    if ("function" == typeof Proxy) return !0;
    try {
        return Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})), !0;
    } catch (e) {
        return !1;
    }
}
function _getPrototypeOf(e) {
    return (
        (_getPrototypeOf = Object.setPrototypeOf
            ? Object.getPrototypeOf.bind()
            : function (e) {
                  return e.__proto__ || Object.getPrototypeOf(e);
              }),
        _getPrototypeOf(e)
    );
}
function ownKeys(e, t) {
    var n = Object.keys(e);
    if (Object.getOwnPropertySymbols) {
        var r = Object.getOwnPropertySymbols(e);
        t &&
            (r = r.filter(function (t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable;
            })),
            n.push.apply(n, r);
    }
    return n;
}
function _objectSpread(e) {
    for (var t = 1; t < arguments.length; t++) {
        var n = null != arguments[t] ? arguments[t] : {};
        t % 2
            ? ownKeys(Object(n), !0).forEach(function (t) {
                  _defineProperty(e, t, n[t]);
              })
            : Object.getOwnPropertyDescriptors
            ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n))
            : ownKeys(Object(n)).forEach(function (t) {
                  Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t));
              });
    }
    return e;
}
function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
}
function _defineProperties(e, t) {
    for (var n = 0; n < t.length; n++) {
        var r = t[n];
        (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
    }
}
function _createClass(e, t, n) {
    return t && _defineProperties(e.prototype, t), n && _defineProperties(e, n), Object.defineProperty(e, "prototype", { writable: !1 }), e;
}
function _slicedToArray(e, t) {
    return _arrayWithHoles(e) || _iterableToArrayLimit(e, t) || _unsupportedIterableToArray(e, t) || _nonIterableRest();
}
function _nonIterableRest() {
    throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
function _iterableToArrayLimit(e, t) {
    var n = null == e ? null : ("undefined" != typeof Symbol && e[Symbol.iterator]) || e["@@iterator"];
    if (null != n) {
        var r,
            i,
            o = [],
            a = !0,
            s = !1;
        try {
            for (n = n.call(e); !(a = (r = n.next()).done) && (o.push(r.value), !t || o.length !== t); a = !0);
        } catch (e) {
            (s = !0), (i = e);
        } finally {
            try {
                a || null == n.return || n.return();
            } finally {
                if (s) throw i;
            }
        }
        return o;
    }
}
function _arrayWithHoles(e) {
    if (Array.isArray(e)) return e;
}
function _toConsumableArray(e) {
    return _arrayWithoutHoles(e) || _iterableToArray(e) || _unsupportedIterableToArray(e) || _nonIterableSpread();
}
function _nonIterableSpread() {
    throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
function _iterableToArray(e) {
    if (("undefined" != typeof Symbol && null != e[Symbol.iterator]) || null != e["@@iterator"]) return Array.from(e);
}
function _arrayWithoutHoles(e) {
    if (Array.isArray(e)) return _arrayLikeToArray(e);
}
function _createForOfIteratorHelper(e, t) {
    var n = ("undefined" != typeof Symbol && e[Symbol.iterator]) || e["@@iterator"];
    if (!n) {
        if (Array.isArray(e) || (n = _unsupportedIterableToArray(e)) || (t && e && "number" == typeof e.length)) {
            n && (e = n);
            var r = 0,
                i = function () {};
            return {
                s: i,
                n: function () {
                    return r >= e.length ? { done: !0 } : { done: !1, value: e[r++] };
                },
                e: function (e) {
                    throw e;
                },
                f: i,
            };
        }
        throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }
    var o,
        a = !0,
        s = !1;
    return {
        s: function () {
            n = n.call(e);
        },
        n: function () {
            var e = n.next();
            return (a = e.done), e;
        },
        e: function (e) {
            (s = !0), (o = e);
        },
        f: function () {
            try {
                a || null == n.return || n.return();
            } finally {
                if (s) throw o;
            }
        },
    };
}
function _unsupportedIterableToArray(e, t) {
    if (e) {
        if ("string" == typeof e) return _arrayLikeToArray(e, t);
        var n = Object.prototype.toString.call(e).slice(8, -1);
        return "Object" === n && e.constructor && (n = e.constructor.name), "Map" === n || "Set" === n ? Array.from(e) : "Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n) ? _arrayLikeToArray(e, t) : void 0;
    }
}
function _arrayLikeToArray(e, t) {
    (null == t || t > e.length) && (t = e.length);
    for (var n = 0, r = new Array(t); n < t; n++) r[n] = e[n];
    return r;
}
function _defineProperty(e, t, n) {
    return t in e ? Object.defineProperty(e, t, { value: n, enumerable: !0, configurable: !0, writable: !0 }) : (e[t] = n), e;
}
function _typeof(e) {
    return (
        (_typeof =
            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                ? function (e) {
                      return typeof e;
                  }
                : function (e) {
                      return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                  }),
        _typeof(e)
    );
}
!(function (e, t) {
    "use strict";
    "object" == ("undefined" == typeof module ? "undefined" : _typeof(module)) && "object" == _typeof(module.exports)
        ? (module.exports = e.document
              ? t(e, !0)
              : function (e) {
                    if (!e.document) throw new Error("jQuery requires a window with a document");
                    return t(e);
                })
        : t(e);
})("undefined" != typeof window ? window : this, function (e, t) {
    "use strict";
    var n = [],
        r = Object.getPrototypeOf,
        i = n.slice,
        o = n.flat
            ? function (e) {
                  return n.flat.call(e);
              }
            : function (e) {
                  return n.concat.apply([], e);
              },
        a = n.push,
        s = n.indexOf,
        l = {},
        u = l.toString,
        c = l.hasOwnProperty,
        f = c.toString,
        d = f.call(Object),
        h = {},
        p = function (e) {
            return "function" == typeof e && "number" != typeof e.nodeType && "function" != typeof e.item;
        },
        g = function (e) {
            return null != e && e === e.window;
        },
        v = e.document,
        m = { type: !0, src: !0, nonce: !0, noModule: !0 };
    function y(e, t, n) {
        var r,
            i,
            o = (n = n || v).createElement("script");
        if (((o.text = e), t)) for (r in m) (i = t[r] || (t.getAttribute && t.getAttribute(r))) && o.setAttribute(r, i);
        n.head.appendChild(o).parentNode.removeChild(o);
    }
    function _(e) {
        return null == e ? e + "" : "object" == _typeof(e) || "function" == typeof e ? l[u.call(e)] || "object" : _typeof(e);
    }
    var b = "3.6.4",
        w = function e(t, n) {
            return new e.fn.init(t, n);
        };
    function k(e) {
        var t = !!e && "length" in e && e.length,
            n = _(e);
        return !p(e) && !g(e) && ("array" === n || 0 === t || ("number" == typeof t && 0 < t && t - 1 in e));
    }
    (w.fn = w.prototype = {
        jquery: b,
        constructor: w,
        length: 0,
        toArray: function () {
            return i.call(this);
        },
        get: function (e) {
            return null == e ? i.call(this) : e < 0 ? this[e + this.length] : this[e];
        },
        pushStack: function (e) {
            var t = w.merge(this.constructor(), e);
            return (t.prevObject = this), t;
        },
        each: function (e) {
            return w.each(this, e);
        },
        map: function (e) {
            return this.pushStack(
                w.map(this, function (t, n) {
                    return e.call(t, n, t);
                })
            );
        },
        slice: function () {
            return this.pushStack(i.apply(this, arguments));
        },
        first: function () {
            return this.eq(0);
        },
        last: function () {
            return this.eq(-1);
        },
        even: function () {
            return this.pushStack(
                w.grep(this, function (e, t) {
                    return (t + 1) % 2;
                })
            );
        },
        odd: function () {
            return this.pushStack(
                w.grep(this, function (e, t) {
                    return t % 2;
                })
            );
        },
        eq: function (e) {
            var t = this.length,
                n = +e + (e < 0 ? t : 0);
            return this.pushStack(0 <= n && n < t ? [this[n]] : []);
        },
        end: function () {
            return this.prevObject || this.constructor();
        },
        push: a,
        sort: n.sort,
        splice: n.splice,
    }),
        (w.extend = w.fn.extend = function () {
            var e,
                t,
                n,
                r,
                i,
                o,
                a = arguments[0] || {},
                s = 1,
                l = arguments.length,
                u = !1;
            for ("boolean" == typeof a && ((u = a), (a = arguments[s] || {}), s++), "object" == _typeof(a) || p(a) || (a = {}), s === l && ((a = this), s--); s < l; s++)
                if (null != (e = arguments[s]))
                    for (t in e)
                        (r = e[t]),
                            "__proto__" !== t &&
                                a !== r &&
                                (u && r && (w.isPlainObject(r) || (i = Array.isArray(r)))
                                    ? ((n = a[t]), (o = i && !Array.isArray(n) ? [] : i || w.isPlainObject(n) ? n : {}), (i = !1), (a[t] = w.extend(u, o, r)))
                                    : void 0 !== r && (a[t] = r));
            return a;
        }),
        w.extend({
            expando: "jQuery" + (b + Math.random()).replace(/\D/g, ""),
            isReady: !0,
            error: function (e) {
                throw new Error(e);
            },
            noop: function () {},
            isPlainObject: function (e) {
                var t, n;
                return !(!e || "[object Object]" !== u.call(e) || ((t = r(e)) && ("function" != typeof (n = c.call(t, "constructor") && t.constructor) || f.call(n) !== d)));
            },
            isEmptyObject: function (e) {
                var t;
                for (t in e) return !1;
                return !0;
            },
            globalEval: function (e, t, n) {
                y(e, { nonce: t && t.nonce }, n);
            },
            each: function (e, t) {
                var n,
                    r = 0;
                if (k(e)) for (n = e.length; r < n && !1 !== t.call(e[r], r, e[r]); r++);
                else for (r in e) if (!1 === t.call(e[r], r, e[r])) break;
                return e;
            },
            makeArray: function (e, t) {
                var n = t || [];
                return null != e && (k(Object(e)) ? w.merge(n, "string" == typeof e ? [e] : e) : a.call(n, e)), n;
            },
            inArray: function (e, t, n) {
                return null == t ? -1 : s.call(t, e, n);
            },
            merge: function (e, t) {
                for (var n = +t.length, r = 0, i = e.length; r < n; r++) e[i++] = t[r];
                return (e.length = i), e;
            },
            grep: function (e, t, n) {
                for (var r = [], i = 0, o = e.length, a = !n; i < o; i++) !t(e[i], i) !== a && r.push(e[i]);
                return r;
            },
            map: function (e, t, n) {
                var r,
                    i,
                    a = 0,
                    s = [];
                if (k(e)) for (r = e.length; a < r; a++) null != (i = t(e[a], a, n)) && s.push(i);
                else for (a in e) null != (i = t(e[a], a, n)) && s.push(i);
                return o(s);
            },
            guid: 1,
            support: h,
        }),
        "function" == typeof Symbol && (w.fn[Symbol.iterator] = n[Symbol.iterator]),
        w.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (e, t) {
            l["[object " + t + "]"] = t.toLowerCase();
        });
    var x = (function (e) {
        var t,
            n,
            r,
            i,
            o,
            a,
            s,
            l,
            u,
            c,
            f,
            d,
            h,
            p,
            g,
            v,
            m,
            y,
            _,
            b = "sizzle" + 1 * new Date(),
            w = e.document,
            k = 0,
            x = 0,
            C = le(),
            T = le(),
            A = le(),
            E = le(),
            S = function (e, t) {
                return e === t && (f = !0), 0;
            },
            O = {}.hasOwnProperty,
            j = [],
            N = j.pop,
            D = j.push,
            L = j.push,
            I = j.slice,
            P = function (e, t) {
                for (var n = 0, r = e.length; n < r; n++) if (e[n] === t) return n;
                return -1;
            },
            H = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            M = "[\\x20\\t\\r\\n\\f]",
            q = "(?:\\\\[\\da-fA-F]{1,6}" + M + "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",
            F = "\\[" + M + "*(" + q + ")(?:" + M + "*([*^$|!~]?=)" + M + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + q + "))|)" + M + "*\\]",
            R = ":(" + q + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + F + ")*)|.*)\\)|)",
            W = new RegExp(M + "+", "g"),
            B = new RegExp("^" + M + "+|((?:^|[^\\\\])(?:\\\\.)*)" + M + "+$", "g"),
            z = new RegExp("^" + M + "*," + M + "*"),
            $ = new RegExp("^" + M + "*([>+~]|" + M + ")" + M + "*"),
            X = new RegExp(M + "|>"),
            V = new RegExp(R),
            U = new RegExp("^" + q + "$"),
            K = {
                ID: new RegExp("^#(" + q + ")"),
                CLASS: new RegExp("^\\.(" + q + ")"),
                TAG: new RegExp("^(" + q + "|[*])"),
                ATTR: new RegExp("^" + F),
                PSEUDO: new RegExp("^" + R),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + M + "*(even|odd|(([+-]|)(\\d*)n|)" + M + "*(?:([+-]|)" + M + "*(\\d+)|))" + M + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + H + ")$", "i"),
                needsContext: new RegExp("^" + M + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + M + "*((?:-\\d)?\\d*)" + M + "*\\)|)(?=[^-]|$)", "i"),
            },
            Q = /HTML$/i,
            Y = /^(?:input|select|textarea|button)$/i,
            G = /^h\d$/i,
            J = /^[^{]+\{\s*\[native \w/,
            Z = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
            ee = /[+~]/,
            te = new RegExp("\\\\[\\da-fA-F]{1,6}" + M + "?|\\\\([^\\r\\n\\f])", "g"),
            ne = function (e, t) {
                var n = "0x" + e.slice(1) - 65536;
                return t || (n < 0 ? String.fromCharCode(n + 65536) : String.fromCharCode((n >> 10) | 55296, (1023 & n) | 56320));
            },
            re = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
            ie = function (e, t) {
                return t ? ("\0" === e ? "�" : e.slice(0, -1) + "\\" + e.charCodeAt(e.length - 1).toString(16) + " ") : "\\" + e;
            },
            oe = function () {
                d();
            },
            ae = be(
                function (e) {
                    return !0 === e.disabled && "fieldset" === e.nodeName.toLowerCase();
                },
                { dir: "parentNode", next: "legend" }
            );
        try {
            L.apply((j = I.call(w.childNodes)), w.childNodes), j[w.childNodes.length].nodeType;
        } catch (t) {
            L = {
                apply: j.length
                    ? function (e, t) {
                          D.apply(e, I.call(t));
                      }
                    : function (e, t) {
                          for (var n = e.length, r = 0; (e[n++] = t[r++]); );
                          e.length = n - 1;
                      },
            };
        }
        function se(e, t, r, i) {
            var o,
                s,
                u,
                c,
                f,
                p,
                m,
                y = t && t.ownerDocument,
                w = t ? t.nodeType : 9;
            if (((r = r || []), "string" != typeof e || !e || (1 !== w && 9 !== w && 11 !== w))) return r;
            if (!i && (d(t), (t = t || h), g)) {
                if (11 !== w && (f = Z.exec(e)))
                    if ((o = f[1])) {
                        if (9 === w) {
                            if (!(u = t.getElementById(o))) return r;
                            if (u.id === o) return r.push(u), r;
                        } else if (y && (u = y.getElementById(o)) && _(t, u) && u.id === o) return r.push(u), r;
                    } else {
                        if (f[2]) return L.apply(r, t.getElementsByTagName(e)), r;
                        if ((o = f[3]) && n.getElementsByClassName && t.getElementsByClassName) return L.apply(r, t.getElementsByClassName(o)), r;
                    }
                if (n.qsa && !E[e + " "] && (!v || !v.test(e)) && (1 !== w || "object" !== t.nodeName.toLowerCase())) {
                    if (((m = e), (y = t), 1 === w && (X.test(e) || $.test(e)))) {
                        for (((y = (ee.test(e) && me(t.parentNode)) || t) === t && n.scope) || ((c = t.getAttribute("id")) ? (c = c.replace(re, ie)) : t.setAttribute("id", (c = b))), s = (p = a(e)).length; s--; )
                            p[s] = (c ? "#" + c : ":scope") + " " + _e(p[s]);
                        m = p.join(",");
                    }
                    try {
                        return L.apply(r, y.querySelectorAll(m)), r;
                    } catch (t) {
                        E(e, !0);
                    } finally {
                        c === b && t.removeAttribute("id");
                    }
                }
            }
            return l(e.replace(B, "$1"), t, r, i);
        }
        function le() {
            var e = [];
            return function t(n, i) {
                return e.push(n + " ") > r.cacheLength && delete t[e.shift()], (t[n + " "] = i);
            };
        }
        function ue(e) {
            return (e[b] = !0), e;
        }
        function ce(e) {
            var t = h.createElement("fieldset");
            try {
                return !!e(t);
            } catch (e) {
                return !1;
            } finally {
                t.parentNode && t.parentNode.removeChild(t), (t = null);
            }
        }
        function fe(e, t) {
            for (var n = e.split("|"), i = n.length; i--; ) r.attrHandle[n[i]] = t;
        }
        function de(e, t) {
            var n = t && e,
                r = n && 1 === e.nodeType && 1 === t.nodeType && e.sourceIndex - t.sourceIndex;
            if (r) return r;
            if (n) for (; (n = n.nextSibling); ) if (n === t) return -1;
            return e ? 1 : -1;
        }
        function he(e) {
            return function (t) {
                return "input" === t.nodeName.toLowerCase() && t.type === e;
            };
        }
        function pe(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e;
            };
        }
        function ge(e) {
            return function (t) {
                return "form" in t
                    ? t.parentNode && !1 === t.disabled
                        ? "label" in t
                            ? "label" in t.parentNode
                                ? t.parentNode.disabled === e
                                : t.disabled === e
                            : t.isDisabled === e || (t.isDisabled !== !e && ae(t) === e)
                        : t.disabled === e
                    : "label" in t && t.disabled === e;
            };
        }
        function ve(e) {
            return ue(function (t) {
                return (
                    (t = +t),
                    ue(function (n, r) {
                        for (var i, o = e([], n.length, t), a = o.length; a--; ) n[(i = o[a])] && (n[i] = !(r[i] = n[i]));
                    })
                );
            });
        }
        function me(e) {
            return e && void 0 !== e.getElementsByTagName && e;
        }
        for (t in ((n = se.support = {}),
        (o = se.isXML = function (e) {
            var t = e && e.namespaceURI,
                n = e && (e.ownerDocument || e).documentElement;
            return !Q.test(t || (n && n.nodeName) || "HTML");
        }),
        (d = se.setDocument = function (e) {
            var t,
                i,
                a = e ? e.ownerDocument || e : w;
            return (
                a != h &&
                    9 === a.nodeType &&
                    a.documentElement &&
                    ((p = (h = a).documentElement),
                    (g = !o(h)),
                    w != h && (i = h.defaultView) && i.top !== i && (i.addEventListener ? i.addEventListener("unload", oe, !1) : i.attachEvent && i.attachEvent("onunload", oe)),
                    (n.scope = ce(function (e) {
                        return p.appendChild(e).appendChild(h.createElement("div")), void 0 !== e.querySelectorAll && !e.querySelectorAll(":scope fieldset div").length;
                    })),
                    (n.cssHas = ce(function () {
                        try {
                            return h.querySelector(":has(*,:jqfake)"), !1;
                        } catch (e) {
                            return !0;
                        }
                    })),
                    (n.attributes = ce(function (e) {
                        return (e.className = "i"), !e.getAttribute("className");
                    })),
                    (n.getElementsByTagName = ce(function (e) {
                        return e.appendChild(h.createComment("")), !e.getElementsByTagName("*").length;
                    })),
                    (n.getElementsByClassName = J.test(h.getElementsByClassName)),
                    (n.getById = ce(function (e) {
                        return (p.appendChild(e).id = b), !h.getElementsByName || !h.getElementsByName(b).length;
                    })),
                    n.getById
                        ? ((r.filter.ID = function (e) {
                              var t = e.replace(te, ne);
                              return function (e) {
                                  return e.getAttribute("id") === t;
                              };
                          }),
                          (r.find.ID = function (e, t) {
                              if (void 0 !== t.getElementById && g) {
                                  var n = t.getElementById(e);
                                  return n ? [n] : [];
                              }
                          }))
                        : ((r.filter.ID = function (e) {
                              var t = e.replace(te, ne);
                              return function (e) {
                                  var n = void 0 !== e.getAttributeNode && e.getAttributeNode("id");
                                  return n && n.value === t;
                              };
                          }),
                          (r.find.ID = function (e, t) {
                              if (void 0 !== t.getElementById && g) {
                                  var n,
                                      r,
                                      i,
                                      o = t.getElementById(e);
                                  if (o) {
                                      if ((n = o.getAttributeNode("id")) && n.value === e) return [o];
                                      for (i = t.getElementsByName(e), r = 0; (o = i[r++]); ) if ((n = o.getAttributeNode("id")) && n.value === e) return [o];
                                  }
                                  return [];
                              }
                          })),
                    (r.find.TAG = n.getElementsByTagName
                        ? function (e, t) {
                              return void 0 !== t.getElementsByTagName ? t.getElementsByTagName(e) : n.qsa ? t.querySelectorAll(e) : void 0;
                          }
                        : function (e, t) {
                              var n,
                                  r = [],
                                  i = 0,
                                  o = t.getElementsByTagName(e);
                              if ("*" === e) {
                                  for (; (n = o[i++]); ) 1 === n.nodeType && r.push(n);
                                  return r;
                              }
                              return o;
                          }),
                    (r.find.CLASS =
                        n.getElementsByClassName &&
                        function (e, t) {
                            if (void 0 !== t.getElementsByClassName && g) return t.getElementsByClassName(e);
                        }),
                    (m = []),
                    (v = []),
                    (n.qsa = J.test(h.querySelectorAll)) &&
                        (ce(function (e) {
                            var t;
                            (p.appendChild(e).innerHTML = "<a id='" + b + "'></a><select id='" + b + "-\r\\' msallowcapture=''><option selected=''></option></select>"),
                                e.querySelectorAll("[msallowcapture^='']").length && v.push("[*^$]=" + M + "*(?:''|\"\")"),
                                e.querySelectorAll("[selected]").length || v.push("\\[" + M + "*(?:value|" + H + ")"),
                                e.querySelectorAll("[id~=" + b + "-]").length || v.push("~="),
                                (t = h.createElement("input")).setAttribute("name", ""),
                                e.appendChild(t),
                                e.querySelectorAll("[name='']").length || v.push("\\[" + M + "*name" + M + "*=" + M + "*(?:''|\"\")"),
                                e.querySelectorAll(":checked").length || v.push(":checked"),
                                e.querySelectorAll("a#" + b + "+*").length || v.push(".#.+[+~]"),
                                e.querySelectorAll("\\\f"),
                                v.push("[\\r\\n\\f]");
                        }),
                        ce(function (e) {
                            e.innerHTML = "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";
                            var t = h.createElement("input");
                            t.setAttribute("type", "hidden"),
                                e.appendChild(t).setAttribute("name", "D"),
                                e.querySelectorAll("[name=d]").length && v.push("name" + M + "*[*^$|!~]?="),
                                2 !== e.querySelectorAll(":enabled").length && v.push(":enabled", ":disabled"),
                                (p.appendChild(e).disabled = !0),
                                2 !== e.querySelectorAll(":disabled").length && v.push(":enabled", ":disabled"),
                                e.querySelectorAll("*,:x"),
                                v.push(",.*:");
                        })),
                    (n.matchesSelector = J.test((y = p.matches || p.webkitMatchesSelector || p.mozMatchesSelector || p.oMatchesSelector || p.msMatchesSelector))) &&
                        ce(function (e) {
                            (n.disconnectedMatch = y.call(e, "*")), y.call(e, "[s!='']:x"), m.push("!=", R);
                        }),
                    n.cssHas || v.push(":has"),
                    (v = v.length && new RegExp(v.join("|"))),
                    (m = m.length && new RegExp(m.join("|"))),
                    (t = J.test(p.compareDocumentPosition)),
                    (_ =
                        t || J.test(p.contains)
                            ? function (e, t) {
                                  var n = (9 === e.nodeType && e.documentElement) || e,
                                      r = t && t.parentNode;
                                  return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)));
                              }
                            : function (e, t) {
                                  if (t) for (; (t = t.parentNode); ) if (t === e) return !0;
                                  return !1;
                              }),
                    (S = t
                        ? function (e, t) {
                              if (e === t) return (f = !0), 0;
                              var r = !e.compareDocumentPosition - !t.compareDocumentPosition;
                              return (
                                  r ||
                                  (1 & (r = (e.ownerDocument || e) == (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1) || (!n.sortDetached && t.compareDocumentPosition(e) === r)
                                      ? e == h || (e.ownerDocument == w && _(w, e))
                                          ? -1
                                          : t == h || (t.ownerDocument == w && _(w, t))
                                          ? 1
                                          : c
                                          ? P(c, e) - P(c, t)
                                          : 0
                                      : 4 & r
                                      ? -1
                                      : 1)
                              );
                          }
                        : function (e, t) {
                              if (e === t) return (f = !0), 0;
                              var n,
                                  r = 0,
                                  i = e.parentNode,
                                  o = t.parentNode,
                                  a = [e],
                                  s = [t];
                              if (!i || !o) return e == h ? -1 : t == h ? 1 : i ? -1 : o ? 1 : c ? P(c, e) - P(c, t) : 0;
                              if (i === o) return de(e, t);
                              for (n = e; (n = n.parentNode); ) a.unshift(n);
                              for (n = t; (n = n.parentNode); ) s.unshift(n);
                              for (; a[r] === s[r]; ) r++;
                              return r ? de(a[r], s[r]) : a[r] == w ? -1 : s[r] == w ? 1 : 0;
                          })),
                h
            );
        }),
        (se.matches = function (e, t) {
            return se(e, null, null, t);
        }),
        (se.matchesSelector = function (e, t) {
            if ((d(e), n.matchesSelector && g && !E[t + " "] && (!m || !m.test(t)) && (!v || !v.test(t))))
                try {
                    var r = y.call(e, t);
                    if (r || n.disconnectedMatch || (e.document && 11 !== e.document.nodeType)) return r;
                } catch (e) {
                    E(t, !0);
                }
            return 0 < se(t, h, null, [e]).length;
        }),
        (se.contains = function (e, t) {
            return (e.ownerDocument || e) != h && d(e), _(e, t);
        }),
        (se.attr = function (e, t) {
            (e.ownerDocument || e) != h && d(e);
            var i = r.attrHandle[t.toLowerCase()],
                o = i && O.call(r.attrHandle, t.toLowerCase()) ? i(e, t, !g) : void 0;
            return void 0 !== o ? o : n.attributes || !g ? e.getAttribute(t) : (o = e.getAttributeNode(t)) && o.specified ? o.value : null;
        }),
        (se.escape = function (e) {
            return (e + "").replace(re, ie);
        }),
        (se.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e);
        }),
        (se.uniqueSort = function (e) {
            var t,
                r = [],
                i = 0,
                o = 0;
            if (((f = !n.detectDuplicates), (c = !n.sortStable && e.slice(0)), e.sort(S), f)) {
                for (; (t = e[o++]); ) t === e[o] && (i = r.push(o));
                for (; i--; ) e.splice(r[i], 1);
            }
            return (c = null), e;
        }),
        (i = se.getText = function (e) {
            var t,
                n = "",
                r = 0,
                o = e.nodeType;
            if (o) {
                if (1 === o || 9 === o || 11 === o) {
                    if ("string" == typeof e.textContent) return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling) n += i(e);
                } else if (3 === o || 4 === o) return e.nodeValue;
            } else for (; (t = e[r++]); ) n += i(t);
            return n;
        }),
        ((r = se.selectors = {
            cacheLength: 50,
            createPseudo: ue,
            match: K,
            attrHandle: {},
            find: {},
            relative: { ">": { dir: "parentNode", first: !0 }, " ": { dir: "parentNode" }, "+": { dir: "previousSibling", first: !0 }, "~": { dir: "previousSibling" } },
            preFilter: {
                ATTR: function (e) {
                    return (e[1] = e[1].replace(te, ne)), (e[3] = (e[3] || e[4] || e[5] || "").replace(te, ne)), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4);
                },
                CHILD: function (e) {
                    return (
                        (e[1] = e[1].toLowerCase()),
                        "nth" === e[1].slice(0, 3) ? (e[3] || se.error(e[0]), (e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3]))), (e[5] = +(e[7] + e[8] || "odd" === e[3]))) : e[3] && se.error(e[0]),
                        e
                    );
                },
                PSEUDO: function (e) {
                    var t,
                        n = !e[6] && e[2];
                    return K.CHILD.test(e[0])
                        ? null
                        : (e[3] ? (e[2] = e[4] || e[5] || "") : n && V.test(n) && (t = a(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && ((e[0] = e[0].slice(0, t)), (e[2] = n.slice(0, t))), e.slice(0, 3));
                },
            },
            filter: {
                TAG: function (e) {
                    var t = e.replace(te, ne).toLowerCase();
                    return "*" === e
                        ? function () {
                              return !0;
                          }
                        : function (e) {
                              return e.nodeName && e.nodeName.toLowerCase() === t;
                          };
                },
                CLASS: function (e) {
                    var t = C[e + " "];
                    return (
                        t ||
                        ((t = new RegExp("(^|" + M + ")" + e + "(" + M + "|$)")) &&
                            C(e, function (e) {
                                return t.test(("string" == typeof e.className && e.className) || (void 0 !== e.getAttribute && e.getAttribute("class")) || "");
                            }))
                    );
                },
                ATTR: function (e, t, n) {
                    return function (r) {
                        var i = se.attr(r, e);
                        return null == i
                            ? "!=" === t
                            : !t ||
                                  ((i += ""),
                                  "=" === t
                                      ? i === n
                                      : "!=" === t
                                      ? i !== n
                                      : "^=" === t
                                      ? n && 0 === i.indexOf(n)
                                      : "*=" === t
                                      ? n && -1 < i.indexOf(n)
                                      : "$=" === t
                                      ? n && i.slice(-n.length) === n
                                      : "~=" === t
                                      ? -1 < (" " + i.replace(W, " ") + " ").indexOf(n)
                                      : "|=" === t && (i === n || i.slice(0, n.length + 1) === n + "-"));
                    };
                },
                CHILD: function (e, t, n, r, i) {
                    var o = "nth" !== e.slice(0, 3),
                        a = "last" !== e.slice(-4),
                        s = "of-type" === t;
                    return 1 === r && 0 === i
                        ? function (e) {
                              return !!e.parentNode;
                          }
                        : function (t, n, l) {
                              var u,
                                  c,
                                  f,
                                  d,
                                  h,
                                  p,
                                  g = o !== a ? "nextSibling" : "previousSibling",
                                  v = t.parentNode,
                                  m = s && t.nodeName.toLowerCase(),
                                  y = !l && !s,
                                  _ = !1;
                              if (v) {
                                  if (o) {
                                      for (; g; ) {
                                          for (d = t; (d = d[g]); ) if (s ? d.nodeName.toLowerCase() === m : 1 === d.nodeType) return !1;
                                          p = g = "only" === e && !p && "nextSibling";
                                      }
                                      return !0;
                                  }
                                  if (((p = [a ? v.firstChild : v.lastChild]), a && y)) {
                                      for (
                                          _ = (h = (u = (c = (f = (d = v)[b] || (d[b] = {}))[d.uniqueID] || (f[d.uniqueID] = {}))[e] || [])[0] === k && u[1]) && u[2], d = h && v.childNodes[h];
                                          (d = (++h && d && d[g]) || (_ = h = 0) || p.pop());

                                      )
                                          if (1 === d.nodeType && ++_ && d === t) {
                                              c[e] = [k, h, _];
                                              break;
                                          }
                                  } else if ((y && (_ = h = (u = (c = (f = (d = t)[b] || (d[b] = {}))[d.uniqueID] || (f[d.uniqueID] = {}))[e] || [])[0] === k && u[1]), !1 === _))
                                      for (
                                          ;
                                          (d = (++h && d && d[g]) || (_ = h = 0) || p.pop()) &&
                                          ((s ? d.nodeName.toLowerCase() !== m : 1 !== d.nodeType) || !++_ || (y && ((c = (f = d[b] || (d[b] = {}))[d.uniqueID] || (f[d.uniqueID] = {}))[e] = [k, _]), d !== t));

                                      );
                                  return (_ -= i) === r || (_ % r == 0 && 0 <= _ / r);
                              }
                          };
                },
                PSEUDO: function (e, t) {
                    var n,
                        i = r.pseudos[e] || r.setFilters[e.toLowerCase()] || se.error("unsupported pseudo: " + e);
                    return i[b]
                        ? i(t)
                        : 1 < i.length
                        ? ((n = [e, e, "", t]),
                          r.setFilters.hasOwnProperty(e.toLowerCase())
                              ? ue(function (e, n) {
                                    for (var r, o = i(e, t), a = o.length; a--; ) e[(r = P(e, o[a]))] = !(n[r] = o[a]);
                                })
                              : function (e) {
                                    return i(e, 0, n);
                                })
                        : i;
                },
            },
            pseudos: {
                not: ue(function (e) {
                    var t = [],
                        n = [],
                        r = s(e.replace(B, "$1"));
                    return r[b]
                        ? ue(function (e, t, n, i) {
                              for (var o, a = r(e, null, i, []), s = e.length; s--; ) (o = a[s]) && (e[s] = !(t[s] = o));
                          })
                        : function (e, i, o) {
                              return (t[0] = e), r(t, null, o, n), (t[0] = null), !n.pop();
                          };
                }),
                has: ue(function (e) {
                    return function (t) {
                        return 0 < se(e, t).length;
                    };
                }),
                contains: ue(function (e) {
                    return (
                        (e = e.replace(te, ne)),
                        function (t) {
                            return -1 < (t.textContent || i(t)).indexOf(e);
                        }
                    );
                }),
                lang: ue(function (e) {
                    return (
                        U.test(e || "") || se.error("unsupported lang: " + e),
                        (e = e.replace(te, ne).toLowerCase()),
                        function (t) {
                            var n;
                            do {
                                if ((n = g ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))) return (n = n.toLowerCase()) === e || 0 === n.indexOf(e + "-");
                            } while ((t = t.parentNode) && 1 === t.nodeType);
                            return !1;
                        }
                    );
                }),
                target: function (t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id;
                },
                root: function (e) {
                    return e === p;
                },
                focus: function (e) {
                    return e === h.activeElement && (!h.hasFocus || h.hasFocus()) && !!(e.type || e.href || ~e.tabIndex);
                },
                enabled: ge(!1),
                disabled: ge(!0),
                checked: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return ("input" === t && !!e.checked) || ("option" === t && !!e.selected);
                },
                selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, !0 === e.selected;
                },
                empty: function (e) {
                    for (e = e.firstChild; e; e = e.nextSibling) if (e.nodeType < 6) return !1;
                    return !0;
                },
                parent: function (e) {
                    return !r.pseudos.empty(e);
                },
                header: function (e) {
                    return G.test(e.nodeName);
                },
                input: function (e) {
                    return Y.test(e.nodeName);
                },
                button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return ("input" === t && "button" === e.type) || "button" === t;
                },
                text: function (e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase());
                },
                first: ve(function () {
                    return [0];
                }),
                last: ve(function (e, t) {
                    return [t - 1];
                }),
                eq: ve(function (e, t, n) {
                    return [n < 0 ? n + t : n];
                }),
                even: ve(function (e, t) {
                    for (var n = 0; n < t; n += 2) e.push(n);
                    return e;
                }),
                odd: ve(function (e, t) {
                    for (var n = 1; n < t; n += 2) e.push(n);
                    return e;
                }),
                lt: ve(function (e, t, n) {
                    for (var r = n < 0 ? n + t : t < n ? t : n; 0 <= --r; ) e.push(r);
                    return e;
                }),
                gt: ve(function (e, t, n) {
                    for (var r = n < 0 ? n + t : n; ++r < t; ) e.push(r);
                    return e;
                }),
            },
        }).pseudos.nth = r.pseudos.eq),
        { radio: !0, checkbox: !0, file: !0, password: !0, image: !0 }))
            r.pseudos[t] = he(t);
        for (t in { submit: !0, reset: !0 }) r.pseudos[t] = pe(t);
        function ye() {}
        function _e(e) {
            for (var t = 0, n = e.length, r = ""; t < n; t++) r += e[t].value;
            return r;
        }
        function be(e, t, n) {
            var r = t.dir,
                i = t.next,
                o = i || r,
                a = n && "parentNode" === o,
                s = x++;
            return t.first
                ? function (t, n, i) {
                      for (; (t = t[r]); ) if (1 === t.nodeType || a) return e(t, n, i);
                      return !1;
                  }
                : function (t, n, l) {
                      var u,
                          c,
                          f,
                          d = [k, s];
                      if (l) {
                          for (; (t = t[r]); ) if ((1 === t.nodeType || a) && e(t, n, l)) return !0;
                      } else
                          for (; (t = t[r]); )
                              if (1 === t.nodeType || a)
                                  if (((c = (f = t[b] || (t[b] = {}))[t.uniqueID] || (f[t.uniqueID] = {})), i && i === t.nodeName.toLowerCase())) t = t[r] || t;
                                  else {
                                      if ((u = c[o]) && u[0] === k && u[1] === s) return (d[2] = u[2]);
                                      if (((c[o] = d)[2] = e(t, n, l))) return !0;
                                  }
                      return !1;
                  };
        }
        function we(e) {
            return 1 < e.length
                ? function (t, n, r) {
                      for (var i = e.length; i--; ) if (!e[i](t, n, r)) return !1;
                      return !0;
                  }
                : e[0];
        }
        function ke(e, t, n, r, i) {
            for (var o, a = [], s = 0, l = e.length, u = null != t; s < l; s++) (o = e[s]) && ((n && !n(o, r, i)) || (a.push(o), u && t.push(s)));
            return a;
        }
        function xe(e, t, n, r, i, o) {
            return (
                r && !r[b] && (r = xe(r)),
                i && !i[b] && (i = xe(i, o)),
                ue(function (o, a, s, l) {
                    var u,
                        c,
                        f,
                        d = [],
                        h = [],
                        p = a.length,
                        g =
                            o ||
                            (function (e, t, n) {
                                for (var r = 0, i = t.length; r < i; r++) se(e, t[r], n);
                                return n;
                            })(t || "*", s.nodeType ? [s] : s, []),
                        v = !e || (!o && t) ? g : ke(g, d, e, s, l),
                        m = n ? (i || (o ? e : p || r) ? [] : a) : v;
                    if ((n && n(v, m, s, l), r)) for (u = ke(m, h), r(u, [], s, l), c = u.length; c--; ) (f = u[c]) && (m[h[c]] = !(v[h[c]] = f));
                    if (o) {
                        if (i || e) {
                            if (i) {
                                for (u = [], c = m.length; c--; ) (f = m[c]) && u.push((v[c] = f));
                                i(null, (m = []), u, l);
                            }
                            for (c = m.length; c--; ) (f = m[c]) && -1 < (u = i ? P(o, f) : d[c]) && (o[u] = !(a[u] = f));
                        }
                    } else (m = ke(m === a ? m.splice(p, m.length) : m)), i ? i(null, a, m, l) : L.apply(a, m);
                })
            );
        }
        function Ce(e) {
            for (
                var t,
                    n,
                    i,
                    o = e.length,
                    a = r.relative[e[0].type],
                    s = a || r.relative[" "],
                    l = a ? 1 : 0,
                    c = be(
                        function (e) {
                            return e === t;
                        },
                        s,
                        !0
                    ),
                    f = be(
                        function (e) {
                            return -1 < P(t, e);
                        },
                        s,
                        !0
                    ),
                    d = [
                        function (e, n, r) {
                            var i = (!a && (r || n !== u)) || ((t = n).nodeType ? c(e, n, r) : f(e, n, r));
                            return (t = null), i;
                        },
                    ];
                l < o;
                l++
            )
                if ((n = r.relative[e[l].type])) d = [be(we(d), n)];
                else {
                    if ((n = r.filter[e[l].type].apply(null, e[l].matches))[b]) {
                        for (i = ++l; i < o && !r.relative[e[i].type]; i++);
                        return xe(1 < l && we(d), 1 < l && _e(e.slice(0, l - 1).concat({ value: " " === e[l - 2].type ? "*" : "" })).replace(B, "$1"), n, l < i && Ce(e.slice(l, i)), i < o && Ce((e = e.slice(i))), i < o && _e(e));
                    }
                    d.push(n);
                }
            return we(d);
        }
        return (
            (ye.prototype = r.filters = r.pseudos),
            (r.setFilters = new ye()),
            (a = se.tokenize = function (e, t) {
                var n,
                    i,
                    o,
                    a,
                    s,
                    l,
                    u,
                    c = T[e + " "];
                if (c) return t ? 0 : c.slice(0);
                for (s = e, l = [], u = r.preFilter; s; ) {
                    for (a in ((n && !(i = z.exec(s))) || (i && (s = s.slice(i[0].length) || s), l.push((o = []))),
                    (n = !1),
                    (i = $.exec(s)) && ((n = i.shift()), o.push({ value: n, type: i[0].replace(B, " ") }), (s = s.slice(n.length))),
                    r.filter))
                        !(i = K[a].exec(s)) || (u[a] && !(i = u[a](i))) || ((n = i.shift()), o.push({ value: n, type: a, matches: i }), (s = s.slice(n.length)));
                    if (!n) break;
                }
                return t ? s.length : s ? se.error(e) : T(e, l).slice(0);
            }),
            (s = se.compile = function (e, t) {
                var n,
                    i,
                    o,
                    s,
                    l,
                    c,
                    f = [],
                    p = [],
                    v = A[e + " "];
                if (!v) {
                    for (t || (t = a(e)), n = t.length; n--; ) (v = Ce(t[n]))[b] ? f.push(v) : p.push(v);
                    (v = A(
                        e,
                        ((i = p),
                        (s = 0 < (o = f).length),
                        (l = 0 < i.length),
                        (c = function (e, t, n, a, c) {
                            var f,
                                p,
                                v,
                                m = 0,
                                y = "0",
                                _ = e && [],
                                b = [],
                                w = u,
                                x = e || (l && r.find.TAG("*", c)),
                                C = (k += null == w ? 1 : Math.random() || 0.1),
                                T = x.length;
                            for (c && (u = t == h || t || c); y !== T && null != (f = x[y]); y++) {
                                if (l && f) {
                                    for (p = 0, t || f.ownerDocument == h || (d(f), (n = !g)); (v = i[p++]); )
                                        if (v(f, t || h, n)) {
                                            a.push(f);
                                            break;
                                        }
                                    c && (k = C);
                                }
                                s && ((f = !v && f) && m--, e && _.push(f));
                            }
                            if (((m += y), s && y !== m)) {
                                for (p = 0; (v = o[p++]); ) v(_, b, t, n);
                                if (e) {
                                    if (0 < m) for (; y--; ) _[y] || b[y] || (b[y] = N.call(a));
                                    b = ke(b);
                                }
                                L.apply(a, b), c && !e && 0 < b.length && 1 < m + o.length && se.uniqueSort(a);
                            }
                            return c && ((k = C), (u = w)), _;
                        }),
                        s ? ue(c) : c)
                    )).selector = e;
                }
                return v;
            }),
            (l = se.select = function (e, t, n, i) {
                var o,
                    l,
                    u,
                    c,
                    f,
                    d = "function" == typeof e && e,
                    h = !i && a((e = d.selector || e));
                if (((n = n || []), 1 === h.length)) {
                    if (2 < (l = h[0] = h[0].slice(0)).length && "ID" === (u = l[0]).type && 9 === t.nodeType && g && r.relative[l[1].type]) {
                        if (!(t = (r.find.ID(u.matches[0].replace(te, ne), t) || [])[0])) return n;
                        d && (t = t.parentNode), (e = e.slice(l.shift().value.length));
                    }
                    for (o = K.needsContext.test(e) ? 0 : l.length; o-- && ((u = l[o]), !r.relative[(c = u.type)]); )
                        if ((f = r.find[c]) && (i = f(u.matches[0].replace(te, ne), (ee.test(l[0].type) && me(t.parentNode)) || t))) {
                            if ((l.splice(o, 1), !(e = i.length && _e(l)))) return L.apply(n, i), n;
                            break;
                        }
                }
                return (d || s(e, h))(i, t, !g, n, !t || (ee.test(e) && me(t.parentNode)) || t), n;
            }),
            (n.sortStable = b.split("").sort(S).join("") === b),
            (n.detectDuplicates = !!f),
            d(),
            (n.sortDetached = ce(function (e) {
                return 1 & e.compareDocumentPosition(h.createElement("fieldset"));
            })),
            ce(function (e) {
                return (e.innerHTML = "<a href='#'></a>"), "#" === e.firstChild.getAttribute("href");
            }) ||
                fe("type|href|height|width", function (e, t, n) {
                    if (!n) return e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2);
                }),
            (n.attributes &&
                ce(function (e) {
                    return (e.innerHTML = "<input/>"), e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value");
                })) ||
                fe("value", function (e, t, n) {
                    if (!n && "input" === e.nodeName.toLowerCase()) return e.defaultValue;
                }),
            ce(function (e) {
                return null == e.getAttribute("disabled");
            }) ||
                fe(H, function (e, t, n) {
                    var r;
                    if (!n) return !0 === e[t] ? t.toLowerCase() : (r = e.getAttributeNode(t)) && r.specified ? r.value : null;
                }),
            se
        );
    })(e);
    (w.find = x), (w.expr = x.selectors), (w.expr[":"] = w.expr.pseudos), (w.uniqueSort = w.unique = x.uniqueSort), (w.text = x.getText), (w.isXMLDoc = x.isXML), (w.contains = x.contains), (w.escapeSelector = x.escape);
    var C = function (e, t, n) {
            for (var r = [], i = void 0 !== n; (e = e[t]) && 9 !== e.nodeType; )
                if (1 === e.nodeType) {
                    if (i && w(e).is(n)) break;
                    r.push(e);
                }
            return r;
        },
        T = function (e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n;
        },
        A = w.expr.match.needsContext;
    function E(e, t) {
        return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase();
    }
    var S = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;
    function O(e, t, n) {
        return p(t)
            ? w.grep(e, function (e, r) {
                  return !!t.call(e, r, e) !== n;
              })
            : t.nodeType
            ? w.grep(e, function (e) {
                  return (e === t) !== n;
              })
            : "string" != typeof t
            ? w.grep(e, function (e) {
                  return -1 < s.call(t, e) !== n;
              })
            : w.filter(t, e, n);
    }
    (w.filter = function (e, t, n) {
        var r = t[0];
        return (
            n && (e = ":not(" + e + ")"),
            1 === t.length && 1 === r.nodeType
                ? w.find.matchesSelector(r, e)
                    ? [r]
                    : []
                : w.find.matches(
                      e,
                      w.grep(t, function (e) {
                          return 1 === e.nodeType;
                      })
                  )
        );
    }),
        w.fn.extend({
            find: function (e) {
                var t,
                    n,
                    r = this.length,
                    i = this;
                if ("string" != typeof e)
                    return this.pushStack(
                        w(e).filter(function () {
                            for (t = 0; t < r; t++) if (w.contains(i[t], this)) return !0;
                        })
                    );
                for (n = this.pushStack([]), t = 0; t < r; t++) w.find(e, i[t], n);
                return 1 < r ? w.uniqueSort(n) : n;
            },
            filter: function (e) {
                return this.pushStack(O(this, e || [], !1));
            },
            not: function (e) {
                return this.pushStack(O(this, e || [], !0));
            },
            is: function (e) {
                return !!O(this, "string" == typeof e && A.test(e) ? w(e) : e || [], !1).length;
            },
        });
    var j,
        N = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
    ((w.fn.init = function (e, t, n) {
        var r, i;
        if (!e) return this;
        if (((n = n || j), "string" == typeof e)) {
            if (!(r = "<" === e[0] && ">" === e[e.length - 1] && 3 <= e.length ? [null, e, null] : N.exec(e)) || (!r[1] && t)) return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
            if (r[1]) {
                if (((t = t instanceof w ? t[0] : t), w.merge(this, w.parseHTML(r[1], t && t.nodeType ? t.ownerDocument || t : v, !0)), S.test(r[1]) && w.isPlainObject(t))) for (r in t) p(this[r]) ? this[r](t[r]) : this.attr(r, t[r]);
                return this;
            }
            return (i = v.getElementById(r[2])) && ((this[0] = i), (this.length = 1)), this;
        }
        return e.nodeType ? ((this[0] = e), (this.length = 1), this) : p(e) ? (void 0 !== n.ready ? n.ready(e) : e(w)) : w.makeArray(e, this);
    }).prototype = w.fn),
        (j = w(v));
    var D = /^(?:parents|prev(?:Until|All))/,
        L = { children: !0, contents: !0, next: !0, prev: !0 };
    function I(e, t) {
        for (; (e = e[t]) && 1 !== e.nodeType; );
        return e;
    }
    w.fn.extend({
        has: function (e) {
            var t = w(e, this),
                n = t.length;
            return this.filter(function () {
                for (var e = 0; e < n; e++) if (w.contains(this, t[e])) return !0;
            });
        },
        closest: function (e, t) {
            var n,
                r = 0,
                i = this.length,
                o = [],
                a = "string" != typeof e && w(e);
            if (!A.test(e))
                for (; r < i; r++)
                    for (n = this[r]; n && n !== t; n = n.parentNode)
                        if (n.nodeType < 11 && (a ? -1 < a.index(n) : 1 === n.nodeType && w.find.matchesSelector(n, e))) {
                            o.push(n);
                            break;
                        }
            return this.pushStack(1 < o.length ? w.uniqueSort(o) : o);
        },
        index: function (e) {
            return e ? ("string" == typeof e ? s.call(w(e), this[0]) : s.call(this, e.jquery ? e[0] : e)) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
        },
        add: function (e, t) {
            return this.pushStack(w.uniqueSort(w.merge(this.get(), w(e, t))));
        },
        addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e));
        },
    }),
        w.each(
            {
                parent: function (e) {
                    var t = e.parentNode;
                    return t && 11 !== t.nodeType ? t : null;
                },
                parents: function (e) {
                    return C(e, "parentNode");
                },
                parentsUntil: function (e, t, n) {
                    return C(e, "parentNode", n);
                },
                next: function (e) {
                    return I(e, "nextSibling");
                },
                prev: function (e) {
                    return I(e, "previousSibling");
                },
                nextAll: function (e) {
                    return C(e, "nextSibling");
                },
                prevAll: function (e) {
                    return C(e, "previousSibling");
                },
                nextUntil: function (e, t, n) {
                    return C(e, "nextSibling", n);
                },
                prevUntil: function (e, t, n) {
                    return C(e, "previousSibling", n);
                },
                siblings: function (e) {
                    return T((e.parentNode || {}).firstChild, e);
                },
                children: function (e) {
                    return T(e.firstChild);
                },
                contents: function (e) {
                    return null != e.contentDocument && r(e.contentDocument) ? e.contentDocument : (E(e, "template") && (e = e.content || e), w.merge([], e.childNodes));
                },
            },
            function (e, t) {
                w.fn[e] = function (n, r) {
                    var i = w.map(this, t, n);
                    return "Until" !== e.slice(-5) && (r = n), r && "string" == typeof r && (i = w.filter(r, i)), 1 < this.length && (L[e] || w.uniqueSort(i), D.test(e) && i.reverse()), this.pushStack(i);
                };
            }
        );
    var P = /[^\x20\t\r\n\f]+/g;
    function H(e) {
        return e;
    }
    function M(e) {
        throw e;
    }
    function q(e, t, n, r) {
        var i;
        try {
            e && p((i = e.promise)) ? i.call(e).done(t).fail(n) : e && p((i = e.then)) ? i.call(e, t, n) : t.apply(void 0, [e].slice(r));
        } catch (e) {
            n.apply(void 0, [e]);
        }
    }
    (w.Callbacks = function (e) {
        var t, n;
        e =
            "string" == typeof e
                ? ((t = e),
                  (n = {}),
                  w.each(t.match(P) || [], function (e, t) {
                      n[t] = !0;
                  }),
                  n)
                : w.extend({}, e);
        var r,
            i,
            o,
            a,
            s = [],
            l = [],
            u = -1,
            c = function () {
                for (a = a || e.once, o = r = !0; l.length; u = -1) for (i = l.shift(); ++u < s.length; ) !1 === s[u].apply(i[0], i[1]) && e.stopOnFalse && ((u = s.length), (i = !1));
                e.memory || (i = !1), (r = !1), a && (s = i ? [] : "");
            },
            f = {
                add: function () {
                    return (
                        s &&
                            (i && !r && ((u = s.length - 1), l.push(i)),
                            (function t(n) {
                                w.each(n, function (n, r) {
                                    p(r) ? (e.unique && f.has(r)) || s.push(r) : r && r.length && "string" !== _(r) && t(r);
                                });
                            })(arguments),
                            i && !r && c()),
                        this
                    );
                },
                remove: function () {
                    return (
                        w.each(arguments, function (e, t) {
                            for (var n; -1 < (n = w.inArray(t, s, n)); ) s.splice(n, 1), n <= u && u--;
                        }),
                        this
                    );
                },
                has: function (e) {
                    return e ? -1 < w.inArray(e, s) : 0 < s.length;
                },
                empty: function () {
                    return s && (s = []), this;
                },
                disable: function () {
                    return (a = l = []), (s = i = ""), this;
                },
                disabled: function () {
                    return !s;
                },
                lock: function () {
                    return (a = l = []), i || r || (s = i = ""), this;
                },
                locked: function () {
                    return !!a;
                },
                fireWith: function (e, t) {
                    return a || ((t = [e, (t = t || []).slice ? t.slice() : t]), l.push(t), r || c()), this;
                },
                fire: function () {
                    return f.fireWith(this, arguments), this;
                },
                fired: function () {
                    return !!o;
                },
            };
        return f;
    }),
        w.extend({
            Deferred: function (t) {
                var n = [
                        ["notify", "progress", w.Callbacks("memory"), w.Callbacks("memory"), 2],
                        ["resolve", "done", w.Callbacks("once memory"), w.Callbacks("once memory"), 0, "resolved"],
                        ["reject", "fail", w.Callbacks("once memory"), w.Callbacks("once memory"), 1, "rejected"],
                    ],
                    r = "pending",
                    i = {
                        state: function () {
                            return r;
                        },
                        always: function () {
                            return o.done(arguments).fail(arguments), this;
                        },
                        catch: function (e) {
                            return i.then(null, e);
                        },
                        pipe: function () {
                            var e = arguments;
                            return w
                                .Deferred(function (t) {
                                    w.each(n, function (n, r) {
                                        var i = p(e[r[4]]) && e[r[4]];
                                        o[r[1]](function () {
                                            var e = i && i.apply(this, arguments);
                                            e && p(e.promise) ? e.promise().progress(t.notify).done(t.resolve).fail(t.reject) : t[r[0] + "With"](this, i ? [e] : arguments);
                                        });
                                    }),
                                        (e = null);
                                })
                                .promise();
                        },
                        then: function (t, r, i) {
                            var o = 0;
                            function a(t, n, r, i) {
                                return function () {
                                    var s = this,
                                        l = arguments,
                                        u = function () {
                                            var e, u;
                                            if (!(t < o)) {
                                                if ((e = r.apply(s, l)) === n.promise()) throw new TypeError("Thenable self-resolution");
                                                (u = e && ("object" == _typeof(e) || "function" == typeof e) && e.then),
                                                    p(u)
                                                        ? i
                                                            ? u.call(e, a(o, n, H, i), a(o, n, M, i))
                                                            : (o++, u.call(e, a(o, n, H, i), a(o, n, M, i), a(o, n, H, n.notifyWith)))
                                                        : (r !== H && ((s = void 0), (l = [e])), (i || n.resolveWith)(s, l));
                                            }
                                        },
                                        c = i
                                            ? u
                                            : function () {
                                                  try {
                                                      u();
                                                  } catch (e) {
                                                      w.Deferred.exceptionHook && w.Deferred.exceptionHook(e, c.stackTrace), o <= t + 1 && (r !== M && ((s = void 0), (l = [e])), n.rejectWith(s, l));
                                                  }
                                              };
                                    t ? c() : (w.Deferred.getStackHook && (c.stackTrace = w.Deferred.getStackHook()), e.setTimeout(c));
                                };
                            }
                            return w
                                .Deferred(function (e) {
                                    n[0][3].add(a(0, e, p(i) ? i : H, e.notifyWith)), n[1][3].add(a(0, e, p(t) ? t : H)), n[2][3].add(a(0, e, p(r) ? r : M));
                                })
                                .promise();
                        },
                        promise: function (e) {
                            return null != e ? w.extend(e, i) : i;
                        },
                    },
                    o = {};
                return (
                    w.each(n, function (e, t) {
                        var a = t[2],
                            s = t[5];
                        (i[t[1]] = a.add),
                            s &&
                                a.add(
                                    function () {
                                        r = s;
                                    },
                                    n[3 - e][2].disable,
                                    n[3 - e][3].disable,
                                    n[0][2].lock,
                                    n[0][3].lock
                                ),
                            a.add(t[3].fire),
                            (o[t[0]] = function () {
                                return o[t[0] + "With"](this === o ? void 0 : this, arguments), this;
                            }),
                            (o[t[0] + "With"] = a.fireWith);
                    }),
                    i.promise(o),
                    t && t.call(o, o),
                    o
                );
            },
            when: function (e) {
                var t = arguments.length,
                    n = t,
                    r = Array(n),
                    o = i.call(arguments),
                    a = w.Deferred(),
                    s = function (e) {
                        return function (n) {
                            (r[e] = this), (o[e] = 1 < arguments.length ? i.call(arguments) : n), --t || a.resolveWith(r, o);
                        };
                    };
                if (t <= 1 && (q(e, a.done(s(n)).resolve, a.reject, !t), "pending" === a.state() || p(o[n] && o[n].then))) return a.then();
                for (; n--; ) q(o[n], s(n), a.reject);
                return a.promise();
            },
        });
    var F = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
    (w.Deferred.exceptionHook = function (t, n) {
        e.console && e.console.warn && t && F.test(t.name) && e.console.warn("jQuery.Deferred exception: " + t.message, t.stack, n);
    }),
        (w.readyException = function (t) {
            e.setTimeout(function () {
                throw t;
            });
        });
    var R = w.Deferred();
    function W() {
        v.removeEventListener("DOMContentLoaded", W), e.removeEventListener("load", W), w.ready();
    }
    (w.fn.ready = function (e) {
        return (
            R.then(e).catch(function (e) {
                w.readyException(e);
            }),
            this
        );
    }),
        w.extend({
            isReady: !1,
            readyWait: 1,
            ready: function (e) {
                (!0 === e ? --w.readyWait : w.isReady) || ((w.isReady = !0) !== e && 0 < --w.readyWait) || R.resolveWith(v, [w]);
            },
        }),
        (w.ready.then = R.then),
        "complete" === v.readyState || ("loading" !== v.readyState && !v.documentElement.doScroll) ? e.setTimeout(w.ready) : (v.addEventListener("DOMContentLoaded", W), e.addEventListener("load", W));
    var B = function e(t, n, r, i, o, a, s) {
            var l = 0,
                u = t.length,
                c = null == r;
            if ("object" === _(r)) for (l in ((o = !0), r)) e(t, n, l, r[l], !0, a, s);
            else if (
                void 0 !== i &&
                ((o = !0),
                p(i) || (s = !0),
                c &&
                    (s
                        ? (n.call(t, i), (n = null))
                        : ((c = n),
                          (n = function (e, t, n) {
                              return c.call(w(e), n);
                          }))),
                n)
            )
                for (; l < u; l++) n(t[l], r, s ? i : i.call(t[l], l, n(t[l], r)));
            return o ? t : c ? n.call(t) : u ? n(t[0], r) : a;
        },
        z = /^-ms-/,
        $ = /-([a-z])/g;
    function X(e, t) {
        return t.toUpperCase();
    }
    function V(e) {
        return e.replace(z, "ms-").replace($, X);
    }
    var U = function (e) {
        return 1 === e.nodeType || 9 === e.nodeType || !+e.nodeType;
    };
    function K() {
        this.expando = w.expando + K.uid++;
    }
    (K.uid = 1),
        (K.prototype = {
            cache: function (e) {
                var t = e[this.expando];
                return t || ((t = {}), U(e) && (e.nodeType ? (e[this.expando] = t) : Object.defineProperty(e, this.expando, { value: t, configurable: !0 }))), t;
            },
            set: function (e, t, n) {
                var r,
                    i = this.cache(e);
                if ("string" == typeof t) i[V(t)] = n;
                else for (r in t) i[V(r)] = t[r];
                return i;
            },
            get: function (e, t) {
                return void 0 === t ? this.cache(e) : e[this.expando] && e[this.expando][V(t)];
            },
            access: function (e, t, n) {
                return void 0 === t || (t && "string" == typeof t && void 0 === n) ? this.get(e, t) : (this.set(e, t, n), void 0 !== n ? n : t);
            },
            remove: function (e, t) {
                var n,
                    r = e[this.expando];
                if (void 0 !== r) {
                    if (void 0 !== t) {
                        n = (t = Array.isArray(t) ? t.map(V) : (t = V(t)) in r ? [t] : t.match(P) || []).length;
                        for (; n--; ) delete r[t[n]];
                    }
                    (void 0 === t || w.isEmptyObject(r)) && (e.nodeType ? (e[this.expando] = void 0) : delete e[this.expando]);
                }
            },
            hasData: function (e) {
                var t = e[this.expando];
                return void 0 !== t && !w.isEmptyObject(t);
            },
        });
    var Q = new K(),
        Y = new K(),
        G = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
        J = /[A-Z]/g;
    function Z(e, t, n) {
        var r, i;
        if (void 0 === n && 1 === e.nodeType)
            if (((r = "data-" + t.replace(J, "-$&").toLowerCase()), "string" == typeof (n = e.getAttribute(r)))) {
                try {
                    n = "true" === (i = n) || ("false" !== i && ("null" === i ? null : i === +i + "" ? +i : G.test(i) ? JSON.parse(i) : i));
                } catch (e) {}
                Y.set(e, t, n);
            } else n = void 0;
        return n;
    }
    w.extend({
        hasData: function (e) {
            return Y.hasData(e) || Q.hasData(e);
        },
        data: function (e, t, n) {
            return Y.access(e, t, n);
        },
        removeData: function (e, t) {
            Y.remove(e, t);
        },
        _data: function (e, t, n) {
            return Q.access(e, t, n);
        },
        _removeData: function (e, t) {
            Q.remove(e, t);
        },
    }),
        w.fn.extend({
            data: function (e, t) {
                var n,
                    r,
                    i,
                    o = this[0],
                    a = o && o.attributes;
                if (void 0 === e) {
                    if (this.length && ((i = Y.get(o)), 1 === o.nodeType && !Q.get(o, "hasDataAttrs"))) {
                        for (n = a.length; n--; ) a[n] && 0 === (r = a[n].name).indexOf("data-") && ((r = V(r.slice(5))), Z(o, r, i[r]));
                        Q.set(o, "hasDataAttrs", !0);
                    }
                    return i;
                }
                return "object" == _typeof(e)
                    ? this.each(function () {
                          Y.set(this, e);
                      })
                    : B(
                          this,
                          function (t) {
                              var n;
                              if (o && void 0 === t) return void 0 !== (n = Y.get(o, e)) || void 0 !== (n = Z(o, e)) ? n : void 0;
                              this.each(function () {
                                  Y.set(this, e, t);
                              });
                          },
                          null,
                          t,
                          1 < arguments.length,
                          null,
                          !0
                      );
            },
            removeData: function (e) {
                return this.each(function () {
                    Y.remove(this, e);
                });
            },
        }),
        w.extend({
            queue: function (e, t, n) {
                var r;
                if (e) return (t = (t || "fx") + "queue"), (r = Q.get(e, t)), n && (!r || Array.isArray(n) ? (r = Q.access(e, t, w.makeArray(n))) : r.push(n)), r || [];
            },
            dequeue: function (e, t) {
                t = t || "fx";
                var n = w.queue(e, t),
                    r = n.length,
                    i = n.shift(),
                    o = w._queueHooks(e, t);
                "inprogress" === i && ((i = n.shift()), r--),
                    i &&
                        ("fx" === t && n.unshift("inprogress"),
                        delete o.stop,
                        i.call(
                            e,
                            function () {
                                w.dequeue(e, t);
                            },
                            o
                        )),
                    !r && o && o.empty.fire();
            },
            _queueHooks: function (e, t) {
                var n = t + "queueHooks";
                return (
                    Q.get(e, n) ||
                    Q.access(e, n, {
                        empty: w.Callbacks("once memory").add(function () {
                            Q.remove(e, [t + "queue", n]);
                        }),
                    })
                );
            },
        }),
        w.fn.extend({
            queue: function (e, t) {
                var n = 2;
                return (
                    "string" != typeof e && ((t = e), (e = "fx"), n--),
                    arguments.length < n
                        ? w.queue(this[0], e)
                        : void 0 === t
                        ? this
                        : this.each(function () {
                              var n = w.queue(this, e, t);
                              w._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && w.dequeue(this, e);
                          })
                );
            },
            dequeue: function (e) {
                return this.each(function () {
                    w.dequeue(this, e);
                });
            },
            clearQueue: function (e) {
                return this.queue(e || "fx", []);
            },
            promise: function (e, t) {
                var n,
                    r = 1,
                    i = w.Deferred(),
                    o = this,
                    a = this.length,
                    s = function () {
                        --r || i.resolveWith(o, [o]);
                    };
                for ("string" != typeof e && ((t = e), (e = void 0)), e = e || "fx"; a--; ) (n = Q.get(o[a], e + "queueHooks")) && n.empty && (r++, n.empty.add(s));
                return s(), i.promise(t);
            },
        });
    var ee = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        te = new RegExp("^(?:([+-])=|)(" + ee + ")([a-z%]*)$", "i"),
        ne = ["Top", "Right", "Bottom", "Left"],
        re = v.documentElement,
        ie = function (e) {
            return w.contains(e.ownerDocument, e);
        },
        oe = { composed: !0 };
    re.getRootNode &&
        (ie = function (e) {
            return w.contains(e.ownerDocument, e) || e.getRootNode(oe) === e.ownerDocument;
        });
    var ae = function (e, t) {
        return "none" === (e = t || e).style.display || ("" === e.style.display && ie(e) && "none" === w.css(e, "display"));
    };
    function se(e, t, n, r) {
        var i,
            o,
            a = 20,
            s = r
                ? function () {
                      return r.cur();
                  }
                : function () {
                      return w.css(e, t, "");
                  },
            l = s(),
            u = (n && n[3]) || (w.cssNumber[t] ? "" : "px"),
            c = e.nodeType && (w.cssNumber[t] || ("px" !== u && +l)) && te.exec(w.css(e, t));
        if (c && c[3] !== u) {
            for (l /= 2, u = u || c[3], c = +l || 1; a--; ) w.style(e, t, c + u), (1 - o) * (1 - (o = s() / l || 0.5)) <= 0 && (a = 0), (c /= o);
            (c *= 2), w.style(e, t, c + u), (n = n || []);
        }
        return n && ((c = +c || +l || 0), (i = n[1] ? c + (n[1] + 1) * n[2] : +n[2]), r && ((r.unit = u), (r.start = c), (r.end = i))), i;
    }
    var le = {};
    function ue(e, t) {
        for (var n, r, i, o, a, s, l, u = [], c = 0, f = e.length; c < f; c++)
            (r = e[c]).style &&
                ((n = r.style.display),
                t
                    ? ("none" === n && ((u[c] = Q.get(r, "display") || null), u[c] || (r.style.display = "")),
                      "" === r.style.display &&
                          ae(r) &&
                          (u[c] =
                              ((l = a = o = void 0),
                              (a = (i = r).ownerDocument),
                              (s = i.nodeName),
                              (l = le[s]) || ((o = a.body.appendChild(a.createElement(s))), (l = w.css(o, "display")), o.parentNode.removeChild(o), "none" === l && (l = "block"), (le[s] = l)))))
                    : "none" !== n && ((u[c] = "none"), Q.set(r, "display", n)));
        for (c = 0; c < f; c++) null != u[c] && (e[c].style.display = u[c]);
        return e;
    }
    w.fn.extend({
        show: function () {
            return ue(this, !0);
        },
        hide: function () {
            return ue(this);
        },
        toggle: function (e) {
            return "boolean" == typeof e
                ? e
                    ? this.show()
                    : this.hide()
                : this.each(function () {
                      ae(this) ? w(this).show() : w(this).hide();
                  });
        },
    });
    var ce,
        fe,
        de = /^(?:checkbox|radio)$/i,
        he = /<([a-z][^\/\0>\x20\t\r\n\f]*)/i,
        pe = /^$|^module$|\/(?:java|ecma)script/i;
    (ce = v.createDocumentFragment().appendChild(v.createElement("div"))),
        (fe = v.createElement("input")).setAttribute("type", "radio"),
        fe.setAttribute("checked", "checked"),
        fe.setAttribute("name", "t"),
        ce.appendChild(fe),
        (h.checkClone = ce.cloneNode(!0).cloneNode(!0).lastChild.checked),
        (ce.innerHTML = "<textarea>x</textarea>"),
        (h.noCloneChecked = !!ce.cloneNode(!0).lastChild.defaultValue),
        (ce.innerHTML = "<option></option>"),
        (h.option = !!ce.lastChild);
    var ge = { thead: [1, "<table>", "</table>"], col: [2, "<table><colgroup>", "</colgroup></table>"], tr: [2, "<table><tbody>", "</tbody></table>"], td: [3, "<table><tbody><tr>", "</tr></tbody></table>"], _default: [0, "", ""] };
    function ve(e, t) {
        var n;
        return (n = void 0 !== e.getElementsByTagName ? e.getElementsByTagName(t || "*") : void 0 !== e.querySelectorAll ? e.querySelectorAll(t || "*") : []), void 0 === t || (t && E(e, t)) ? w.merge([e], n) : n;
    }
    function me(e, t) {
        for (var n = 0, r = e.length; n < r; n++) Q.set(e[n], "globalEval", !t || Q.get(t[n], "globalEval"));
    }
    (ge.tbody = ge.tfoot = ge.colgroup = ge.caption = ge.thead), (ge.th = ge.td), h.option || (ge.optgroup = ge.option = [1, "<select multiple='multiple'>", "</select>"]);
    var ye = /<|&#?\w+;/;
    function _e(e, t, n, r, i) {
        for (var o, a, s, l, u, c, f = t.createDocumentFragment(), d = [], h = 0, p = e.length; h < p; h++)
            if ((o = e[h]) || 0 === o)
                if ("object" === _(o)) w.merge(d, o.nodeType ? [o] : o);
                else if (ye.test(o)) {
                    for (a = a || f.appendChild(t.createElement("div")), s = (he.exec(o) || ["", ""])[1].toLowerCase(), l = ge[s] || ge._default, a.innerHTML = l[1] + w.htmlPrefilter(o) + l[2], c = l[0]; c--; ) a = a.lastChild;
                    w.merge(d, a.childNodes), ((a = f.firstChild).textContent = "");
                } else d.push(t.createTextNode(o));
        for (f.textContent = "", h = 0; (o = d[h++]); )
            if (r && -1 < w.inArray(o, r)) i && i.push(o);
            else if (((u = ie(o)), (a = ve(f.appendChild(o), "script")), u && me(a), n)) for (c = 0; (o = a[c++]); ) pe.test(o.type || "") && n.push(o);
        return f;
    }
    var be = /^([^.]*)(?:\.(.+)|)/;
    function we() {
        return !0;
    }
    function ke() {
        return !1;
    }
    function xe(e, t) {
        return (
            (e ===
                (function () {
                    try {
                        return v.activeElement;
                    } catch (e) {}
                })()) ==
            ("focus" === t)
        );
    }
    function Ce(e, t, n, r, i, o) {
        var a, s;
        if ("object" == _typeof(t)) {
            for (s in ("string" != typeof n && ((r = r || n), (n = void 0)), t)) Ce(e, s, n, r, t[s], o);
            return e;
        }
        if ((null == r && null == i ? ((i = n), (r = n = void 0)) : null == i && ("string" == typeof n ? ((i = r), (r = void 0)) : ((i = r), (r = n), (n = void 0))), !1 === i)) i = ke;
        else if (!i) return e;
        return (
            1 === o &&
                ((a = i),
                ((i = function (e) {
                    return w().off(e), a.apply(this, arguments);
                }).guid = a.guid || (a.guid = w.guid++))),
            e.each(function () {
                w.event.add(this, t, i, r, n);
            })
        );
    }
    function Te(e, t, n) {
        n
            ? (Q.set(e, t, !1),
              w.event.add(e, t, {
                  namespace: !1,
                  handler: function (e) {
                      var r,
                          o,
                          a = Q.get(this, t);
                      if (1 & e.isTrigger && this[t]) {
                          if (a.length) (w.event.special[t] || {}).delegateType && e.stopPropagation();
                          else if (((a = i.call(arguments)), Q.set(this, t, a), (r = n(this, t)), this[t](), a !== (o = Q.get(this, t)) || r ? Q.set(this, t, !1) : (o = {}), a !== o))
                              return e.stopImmediatePropagation(), e.preventDefault(), o && o.value;
                      } else a.length && (Q.set(this, t, { value: w.event.trigger(w.extend(a[0], w.Event.prototype), a.slice(1), this) }), e.stopImmediatePropagation());
                  },
              }))
            : void 0 === Q.get(e, t) && w.event.add(e, t, we);
    }
    (w.event = {
        global: {},
        add: function (e, t, n, r, i) {
            var o,
                a,
                s,
                l,
                u,
                c,
                f,
                d,
                h,
                p,
                g,
                v = Q.get(e);
            if (U(e))
                for (
                    n.handler && ((n = (o = n).handler), (i = o.selector)),
                        i && w.find.matchesSelector(re, i),
                        n.guid || (n.guid = w.guid++),
                        (l = v.events) || (l = v.events = Object.create(null)),
                        (a = v.handle) ||
                            (a = v.handle = function (t) {
                                return void 0 !== w && w.event.triggered !== t.type ? w.event.dispatch.apply(e, arguments) : void 0;
                            }),
                        u = (t = (t || "").match(P) || [""]).length;
                    u--;

                )
                    (h = g = (s = be.exec(t[u]) || [])[1]),
                        (p = (s[2] || "").split(".").sort()),
                        h &&
                            ((f = w.event.special[h] || {}),
                            (h = (i ? f.delegateType : f.bindType) || h),
                            (f = w.event.special[h] || {}),
                            (c = w.extend({ type: h, origType: g, data: r, handler: n, guid: n.guid, selector: i, needsContext: i && w.expr.match.needsContext.test(i), namespace: p.join(".") }, o)),
                            (d = l[h]) || (((d = l[h] = []).delegateCount = 0), (f.setup && !1 !== f.setup.call(e, r, p, a)) || (e.addEventListener && e.addEventListener(h, a))),
                            f.add && (f.add.call(e, c), c.handler.guid || (c.handler.guid = n.guid)),
                            i ? d.splice(d.delegateCount++, 0, c) : d.push(c),
                            (w.event.global[h] = !0));
        },
        remove: function (e, t, n, r, i) {
            var o,
                a,
                s,
                l,
                u,
                c,
                f,
                d,
                h,
                p,
                g,
                v = Q.hasData(e) && Q.get(e);
            if (v && (l = v.events)) {
                for (u = (t = (t || "").match(P) || [""]).length; u--; )
                    if (((h = g = (s = be.exec(t[u]) || [])[1]), (p = (s[2] || "").split(".").sort()), h)) {
                        for (f = w.event.special[h] || {}, d = l[(h = (r ? f.delegateType : f.bindType) || h)] || [], s = s[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), a = o = d.length; o--; )
                            (c = d[o]),
                                (!i && g !== c.origType) ||
                                    (n && n.guid !== c.guid) ||
                                    (s && !s.test(c.namespace)) ||
                                    (r && r !== c.selector && ("**" !== r || !c.selector)) ||
                                    (d.splice(o, 1), c.selector && d.delegateCount--, f.remove && f.remove.call(e, c));
                        a && !d.length && ((f.teardown && !1 !== f.teardown.call(e, p, v.handle)) || w.removeEvent(e, h, v.handle), delete l[h]);
                    } else for (h in l) w.event.remove(e, h + t[u], n, r, !0);
                w.isEmptyObject(l) && Q.remove(e, "handle events");
            }
        },
        dispatch: function (e) {
            var t,
                n,
                r,
                i,
                o,
                a,
                s = new Array(arguments.length),
                l = w.event.fix(e),
                u = (Q.get(this, "events") || Object.create(null))[l.type] || [],
                c = w.event.special[l.type] || {};
            for (s[0] = l, t = 1; t < arguments.length; t++) s[t] = arguments[t];
            if (((l.delegateTarget = this), !c.preDispatch || !1 !== c.preDispatch.call(this, l))) {
                for (a = w.event.handlers.call(this, l, u), t = 0; (i = a[t++]) && !l.isPropagationStopped(); )
                    for (l.currentTarget = i.elem, n = 0; (o = i.handlers[n++]) && !l.isImmediatePropagationStopped(); )
                        (l.rnamespace && !1 !== o.namespace && !l.rnamespace.test(o.namespace)) ||
                            ((l.handleObj = o), (l.data = o.data), void 0 !== (r = ((w.event.special[o.origType] || {}).handle || o.handler).apply(i.elem, s)) && !1 === (l.result = r) && (l.preventDefault(), l.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, l), l.result;
            }
        },
        handlers: function (e, t) {
            var n,
                r,
                i,
                o,
                a,
                s = [],
                l = t.delegateCount,
                u = e.target;
            if (l && u.nodeType && !("click" === e.type && 1 <= e.button))
                for (; u !== this; u = u.parentNode || this)
                    if (1 === u.nodeType && ("click" !== e.type || !0 !== u.disabled)) {
                        for (o = [], a = {}, n = 0; n < l; n++) void 0 === a[(i = (r = t[n]).selector + " ")] && (a[i] = r.needsContext ? -1 < w(i, this).index(u) : w.find(i, this, null, [u]).length), a[i] && o.push(r);
                        o.length && s.push({ elem: u, handlers: o });
                    }
            return (u = this), l < t.length && s.push({ elem: u, handlers: t.slice(l) }), s;
        },
        addProp: function (e, t) {
            Object.defineProperty(w.Event.prototype, e, {
                enumerable: !0,
                configurable: !0,
                get: p(t)
                    ? function () {
                          if (this.originalEvent) return t(this.originalEvent);
                      }
                    : function () {
                          if (this.originalEvent) return this.originalEvent[e];
                      },
                set: function (t) {
                    Object.defineProperty(this, e, { enumerable: !0, configurable: !0, writable: !0, value: t });
                },
            });
        },
        fix: function (e) {
            return e[w.expando] ? e : new w.Event(e);
        },
        special: {
            load: { noBubble: !0 },
            click: {
                setup: function (e) {
                    var t = this || e;
                    return de.test(t.type) && t.click && E(t, "input") && Te(t, "click", we), !1;
                },
                trigger: function (e) {
                    var t = this || e;
                    return de.test(t.type) && t.click && E(t, "input") && Te(t, "click"), !0;
                },
                _default: function (e) {
                    var t = e.target;
                    return (de.test(t.type) && t.click && E(t, "input") && Q.get(t, "click")) || E(t, "a");
                },
            },
            beforeunload: {
                postDispatch: function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result);
                },
            },
        },
    }),
        (w.removeEvent = function (e, t, n) {
            e.removeEventListener && e.removeEventListener(t, n);
        }),
        (w.Event = function (e, t) {
            if (!(this instanceof w.Event)) return new w.Event(e, t);
            e && e.type
                ? ((this.originalEvent = e),
                  (this.type = e.type),
                  (this.isDefaultPrevented = e.defaultPrevented || (void 0 === e.defaultPrevented && !1 === e.returnValue) ? we : ke),
                  (this.target = e.target && 3 === e.target.nodeType ? e.target.parentNode : e.target),
                  (this.currentTarget = e.currentTarget),
                  (this.relatedTarget = e.relatedTarget))
                : (this.type = e),
                t && w.extend(this, t),
                (this.timeStamp = (e && e.timeStamp) || Date.now()),
                (this[w.expando] = !0);
        }),
        (w.Event.prototype = {
            constructor: w.Event,
            isDefaultPrevented: ke,
            isPropagationStopped: ke,
            isImmediatePropagationStopped: ke,
            isSimulated: !1,
            preventDefault: function () {
                var e = this.originalEvent;
                (this.isDefaultPrevented = we), e && !this.isSimulated && e.preventDefault();
            },
            stopPropagation: function () {
                var e = this.originalEvent;
                (this.isPropagationStopped = we), e && !this.isSimulated && e.stopPropagation();
            },
            stopImmediatePropagation: function () {
                var e = this.originalEvent;
                (this.isImmediatePropagationStopped = we), e && !this.isSimulated && e.stopImmediatePropagation(), this.stopPropagation();
            },
        }),
        w.each(
            {
                altKey: !0,
                bubbles: !0,
                cancelable: !0,
                changedTouches: !0,
                ctrlKey: !0,
                detail: !0,
                eventPhase: !0,
                metaKey: !0,
                pageX: !0,
                pageY: !0,
                shiftKey: !0,
                view: !0,
                char: !0,
                code: !0,
                charCode: !0,
                key: !0,
                keyCode: !0,
                button: !0,
                buttons: !0,
                clientX: !0,
                clientY: !0,
                offsetX: !0,
                offsetY: !0,
                pointerId: !0,
                pointerType: !0,
                screenX: !0,
                screenY: !0,
                targetTouches: !0,
                toElement: !0,
                touches: !0,
                which: !0,
            },
            w.event.addProp
        ),
        w.each({ focus: "focusin", blur: "focusout" }, function (e, t) {
            w.event.special[e] = {
                setup: function () {
                    return Te(this, e, xe), !1;
                },
                trigger: function () {
                    return Te(this, e), !0;
                },
                _default: function (t) {
                    return Q.get(t.target, e);
                },
                delegateType: t,
            };
        }),
        w.each({ mouseenter: "mouseover", mouseleave: "mouseout", pointerenter: "pointerover", pointerleave: "pointerout" }, function (e, t) {
            w.event.special[e] = {
                delegateType: t,
                bindType: t,
                handle: function (e) {
                    var n,
                        r = e.relatedTarget,
                        i = e.handleObj;
                    return (r && (r === this || w.contains(this, r))) || ((e.type = i.origType), (n = i.handler.apply(this, arguments)), (e.type = t)), n;
                },
            };
        }),
        w.fn.extend({
            on: function (e, t, n, r) {
                return Ce(this, e, t, n, r);
            },
            one: function (e, t, n, r) {
                return Ce(this, e, t, n, r, 1);
            },
            off: function (e, t, n) {
                var r, i;
                if (e && e.preventDefault && e.handleObj) return (r = e.handleObj), w(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this;
                if ("object" == _typeof(e)) {
                    for (i in e) this.off(i, t, e[i]);
                    return this;
                }
                return (
                    (!1 !== t && "function" != typeof t) || ((n = t), (t = void 0)),
                    !1 === n && (n = ke),
                    this.each(function () {
                        w.event.remove(this, e, n, t);
                    })
                );
            },
        });
    var Ae = /<script|<style|<link/i,
        Ee = /checked\s*(?:[^=]|=\s*.checked.)/i,
        Se = /^\s*<!\[CDATA\[|\]\]>\s*$/g;
    function Oe(e, t) {
        return (E(e, "table") && E(11 !== t.nodeType ? t : t.firstChild, "tr") && w(e).children("tbody")[0]) || e;
    }
    function je(e) {
        return (e.type = (null !== e.getAttribute("type")) + "/" + e.type), e;
    }
    function Ne(e) {
        return "true/" === (e.type || "").slice(0, 5) ? (e.type = e.type.slice(5)) : e.removeAttribute("type"), e;
    }
    function De(e, t) {
        var n, r, i, o, a, s;
        if (1 === t.nodeType) {
            if (Q.hasData(e) && (s = Q.get(e).events)) for (i in (Q.remove(t, "handle events"), s)) for (n = 0, r = s[i].length; n < r; n++) w.event.add(t, i, s[i][n]);
            Y.hasData(e) && ((o = Y.access(e)), (a = w.extend({}, o)), Y.set(t, a));
        }
    }
    function Le(e, t, n, r) {
        t = o(t);
        var i,
            a,
            s,
            l,
            u,
            c,
            f = 0,
            d = e.length,
            g = d - 1,
            v = t[0],
            m = p(v);
        if (m || (1 < d && "string" == typeof v && !h.checkClone && Ee.test(v)))
            return e.each(function (i) {
                var o = e.eq(i);
                m && (t[0] = v.call(this, i, o.html())), Le(o, t, n, r);
            });
        if (d && ((a = (i = _e(t, e[0].ownerDocument, !1, e, r)).firstChild), 1 === i.childNodes.length && (i = a), a || r)) {
            for (l = (s = w.map(ve(i, "script"), je)).length; f < d; f++) (u = i), f !== g && ((u = w.clone(u, !0, !0)), l && w.merge(s, ve(u, "script"))), n.call(e[f], u, f);
            if (l)
                for (c = s[s.length - 1].ownerDocument, w.map(s, Ne), f = 0; f < l; f++)
                    (u = s[f]),
                        pe.test(u.type || "") &&
                            !Q.access(u, "globalEval") &&
                            w.contains(c, u) &&
                            (u.src && "module" !== (u.type || "").toLowerCase() ? w._evalUrl && !u.noModule && w._evalUrl(u.src, { nonce: u.nonce || u.getAttribute("nonce") }, c) : y(u.textContent.replace(Se, ""), u, c));
        }
        return e;
    }
    function Ie(e, t, n) {
        for (var r, i = t ? w.filter(t, e) : e, o = 0; null != (r = i[o]); o++) n || 1 !== r.nodeType || w.cleanData(ve(r)), r.parentNode && (n && ie(r) && me(ve(r, "script")), r.parentNode.removeChild(r));
        return e;
    }
    w.extend({
        htmlPrefilter: function (e) {
            return e;
        },
        clone: function (e, t, n) {
            var r,
                i,
                o,
                a,
                s,
                l,
                u,
                c = e.cloneNode(!0),
                f = ie(e);
            if (!(h.noCloneChecked || (1 !== e.nodeType && 11 !== e.nodeType) || w.isXMLDoc(e)))
                for (a = ve(c), r = 0, i = (o = ve(e)).length; r < i; r++)
                    (s = o[r]), "input" === (u = (l = a[r]).nodeName.toLowerCase()) && de.test(s.type) ? (l.checked = s.checked) : ("input" !== u && "textarea" !== u) || (l.defaultValue = s.defaultValue);
            if (t)
                if (n) for (o = o || ve(e), a = a || ve(c), r = 0, i = o.length; r < i; r++) De(o[r], a[r]);
                else De(e, c);
            return 0 < (a = ve(c, "script")).length && me(a, !f && ve(e, "script")), c;
        },
        cleanData: function (e) {
            for (var t, n, r, i = w.event.special, o = 0; void 0 !== (n = e[o]); o++)
                if (U(n)) {
                    if ((t = n[Q.expando])) {
                        if (t.events) for (r in t.events) i[r] ? w.event.remove(n, r) : w.removeEvent(n, r, t.handle);
                        n[Q.expando] = void 0;
                    }
                    n[Y.expando] && (n[Y.expando] = void 0);
                }
        },
    }),
        w.fn.extend({
            detach: function (e) {
                return Ie(this, e, !0);
            },
            remove: function (e) {
                return Ie(this, e);
            },
            text: function (e) {
                return B(
                    this,
                    function (e) {
                        return void 0 === e
                            ? w.text(this)
                            : this.empty().each(function () {
                                  (1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType) || (this.textContent = e);
                              });
                    },
                    null,
                    e,
                    arguments.length
                );
            },
            append: function () {
                return Le(this, arguments, function (e) {
                    (1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType) || Oe(this, e).appendChild(e);
                });
            },
            prepend: function () {
                return Le(this, arguments, function (e) {
                    if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                        var t = Oe(this, e);
                        t.insertBefore(e, t.firstChild);
                    }
                });
            },
            before: function () {
                return Le(this, arguments, function (e) {
                    this.parentNode && this.parentNode.insertBefore(e, this);
                });
            },
            after: function () {
                return Le(this, arguments, function (e) {
                    this.parentNode && this.parentNode.insertBefore(e, this.nextSibling);
                });
            },
            empty: function () {
                for (var e, t = 0; null != (e = this[t]); t++) 1 === e.nodeType && (w.cleanData(ve(e, !1)), (e.textContent = ""));
                return this;
            },
            clone: function (e, t) {
                return (
                    (e = null != e && e),
                    (t = null == t ? e : t),
                    this.map(function () {
                        return w.clone(this, e, t);
                    })
                );
            },
            html: function (e) {
                return B(
                    this,
                    function (e) {
                        var t = this[0] || {},
                            n = 0,
                            r = this.length;
                        if (void 0 === e && 1 === t.nodeType) return t.innerHTML;
                        if ("string" == typeof e && !Ae.test(e) && !ge[(he.exec(e) || ["", ""])[1].toLowerCase()]) {
                            e = w.htmlPrefilter(e);
                            try {
                                for (; n < r; n++) 1 === (t = this[n] || {}).nodeType && (w.cleanData(ve(t, !1)), (t.innerHTML = e));
                                t = 0;
                            } catch (e) {}
                        }
                        t && this.empty().append(e);
                    },
                    null,
                    e,
                    arguments.length
                );
            },
            replaceWith: function () {
                var e = [];
                return Le(
                    this,
                    arguments,
                    function (t) {
                        var n = this.parentNode;
                        w.inArray(this, e) < 0 && (w.cleanData(ve(this)), n && n.replaceChild(t, this));
                    },
                    e
                );
            },
        }),
        w.each({ appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith" }, function (e, t) {
            w.fn[e] = function (e) {
                for (var n, r = [], i = w(e), o = i.length - 1, s = 0; s <= o; s++) (n = s === o ? this : this.clone(!0)), w(i[s])[t](n), a.apply(r, n.get());
                return this.pushStack(r);
            };
        });
    var Pe = new RegExp("^(" + ee + ")(?!px)[a-z%]+$", "i"),
        He = /^--/,
        Me = function (t) {
            var n = t.ownerDocument.defaultView;
            return (n && n.opener) || (n = e), n.getComputedStyle(t);
        },
        qe = function (e, t, n) {
            var r,
                i,
                o = {};
            for (i in t) (o[i] = e.style[i]), (e.style[i] = t[i]);
            for (i in ((r = n.call(e)), t)) e.style[i] = o[i];
            return r;
        },
        Fe = new RegExp(ne.join("|"), "i"),
        Re = "[\\x20\\t\\r\\n\\f]",
        We = new RegExp("^" + Re + "+|((?:^|[^\\\\])(?:\\\\.)*)" + Re + "+$", "g");
    function Be(e, t, n) {
        var r,
            i,
            o,
            a,
            s = He.test(t),
            l = e.style;
        return (
            (n = n || Me(e)) &&
                ((a = n.getPropertyValue(t) || n[t]),
                s && a && (a = a.replace(We, "$1") || void 0),
                "" !== a || ie(e) || (a = w.style(e, t)),
                !h.pixelBoxStyles() && Pe.test(a) && Fe.test(t) && ((r = l.width), (i = l.minWidth), (o = l.maxWidth), (l.minWidth = l.maxWidth = l.width = a), (a = n.width), (l.width = r), (l.minWidth = i), (l.maxWidth = o))),
            void 0 !== a ? a + "" : a
        );
    }
    function ze(e, t) {
        return {
            get: function () {
                if (!e()) return (this.get = t).apply(this, arguments);
                delete this.get;
            },
        };
    }
    !(function () {
        function t() {
            if (c) {
                (u.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0"),
                    (c.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%"),
                    re.appendChild(u).appendChild(c);
                var t = e.getComputedStyle(c);
                (r = "1%" !== t.top),
                    (l = 12 === n(t.marginLeft)),
                    (c.style.right = "60%"),
                    (a = 36 === n(t.right)),
                    (i = 36 === n(t.width)),
                    (c.style.position = "absolute"),
                    (o = 12 === n(c.offsetWidth / 3)),
                    re.removeChild(u),
                    (c = null);
            }
        }
        function n(e) {
            return Math.round(parseFloat(e));
        }
        var r,
            i,
            o,
            a,
            s,
            l,
            u = v.createElement("div"),
            c = v.createElement("div");
        c.style &&
            ((c.style.backgroundClip = "content-box"),
            (c.cloneNode(!0).style.backgroundClip = ""),
            (h.clearCloneStyle = "content-box" === c.style.backgroundClip),
            w.extend(h, {
                boxSizingReliable: function () {
                    return t(), i;
                },
                pixelBoxStyles: function () {
                    return t(), a;
                },
                pixelPosition: function () {
                    return t(), r;
                },
                reliableMarginLeft: function () {
                    return t(), l;
                },
                scrollboxSize: function () {
                    return t(), o;
                },
                reliableTrDimensions: function () {
                    var t, n, r, i;
                    return (
                        null == s &&
                            ((t = v.createElement("table")),
                            (n = v.createElement("tr")),
                            (r = v.createElement("div")),
                            (t.style.cssText = "position:absolute;left:-11111px;border-collapse:separate"),
                            (n.style.cssText = "border:1px solid"),
                            (n.style.height = "1px"),
                            (r.style.height = "9px"),
                            (r.style.display = "block"),
                            re.appendChild(t).appendChild(n).appendChild(r),
                            (i = e.getComputedStyle(n)),
                            (s = parseInt(i.height, 10) + parseInt(i.borderTopWidth, 10) + parseInt(i.borderBottomWidth, 10) === n.offsetHeight),
                            re.removeChild(t)),
                        s
                    );
                },
            }));
    })();
    var $e = ["Webkit", "Moz", "ms"],
        Xe = v.createElement("div").style,
        Ve = {};
    function Ue(e) {
        return (
            w.cssProps[e] ||
            Ve[e] ||
            (e in Xe
                ? e
                : (Ve[e] =
                      (function (e) {
                          for (var t = e[0].toUpperCase() + e.slice(1), n = $e.length; n--; ) if ((e = $e[n] + t) in Xe) return e;
                      })(e) || e))
        );
    }
    var Ke = /^(none|table(?!-c[ea]).+)/,
        Qe = { position: "absolute", visibility: "hidden", display: "block" },
        Ye = { letterSpacing: "0", fontWeight: "400" };
    function Ge(e, t, n) {
        var r = te.exec(t);
        return r ? Math.max(0, r[2] - (n || 0)) + (r[3] || "px") : t;
    }
    function Je(e, t, n, r, i, o) {
        var a = "width" === t ? 1 : 0,
            s = 0,
            l = 0;
        if (n === (r ? "border" : "content")) return 0;
        for (; a < 4; a += 2)
            "margin" === n && (l += w.css(e, n + ne[a], !0, i)),
                r
                    ? ("content" === n && (l -= w.css(e, "padding" + ne[a], !0, i)), "margin" !== n && (l -= w.css(e, "border" + ne[a] + "Width", !0, i)))
                    : ((l += w.css(e, "padding" + ne[a], !0, i)), "padding" !== n ? (l += w.css(e, "border" + ne[a] + "Width", !0, i)) : (s += w.css(e, "border" + ne[a] + "Width", !0, i)));
        return !r && 0 <= o && (l += Math.max(0, Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - o - l - s - 0.5)) || 0), l;
    }
    function Ze(e, t, n) {
        var r = Me(e),
            i = (!h.boxSizingReliable() || n) && "border-box" === w.css(e, "boxSizing", !1, r),
            o = i,
            a = Be(e, t, r),
            s = "offset" + t[0].toUpperCase() + t.slice(1);
        if (Pe.test(a)) {
            if (!n) return a;
            a = "auto";
        }
        return (
            ((!h.boxSizingReliable() && i) || (!h.reliableTrDimensions() && E(e, "tr")) || "auto" === a || (!parseFloat(a) && "inline" === w.css(e, "display", !1, r))) &&
                e.getClientRects().length &&
                ((i = "border-box" === w.css(e, "boxSizing", !1, r)), (o = s in e) && (a = e[s])),
            (a = parseFloat(a) || 0) + Je(e, t, n || (i ? "border" : "content"), o, r, a) + "px"
        );
    }
    function et(e, t, n, r, i) {
        return new et.prototype.init(e, t, n, r, i);
    }
    w.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = Be(e, "opacity");
                        return "" === n ? "1" : n;
                    }
                },
            },
        },
        cssNumber: {
            animationIterationCount: !0,
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            gridArea: !0,
            gridColumn: !0,
            gridColumnEnd: !0,
            gridColumnStart: !0,
            gridRow: !0,
            gridRowEnd: !0,
            gridRowStart: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0,
        },
        cssProps: {},
        style: function (e, t, n, r) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var i,
                    o,
                    a,
                    s = V(t),
                    l = He.test(t),
                    u = e.style;
                if ((l || (t = Ue(s)), (a = w.cssHooks[t] || w.cssHooks[s]), void 0 === n)) return a && "get" in a && void 0 !== (i = a.get(e, !1, r)) ? i : u[t];
                "string" === (o = _typeof(n)) && (i = te.exec(n)) && i[1] && ((n = se(e, t, i)), (o = "number")),
                    null != n &&
                        n == n &&
                        ("number" !== o || l || (n += (i && i[3]) || (w.cssNumber[s] ? "" : "px")),
                        h.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (u[t] = "inherit"),
                        (a && "set" in a && void 0 === (n = a.set(e, n, r))) || (l ? u.setProperty(t, n) : (u[t] = n)));
            }
        },
        css: function (e, t, n, r) {
            var i,
                o,
                a,
                s = V(t);
            return (
                He.test(t) || (t = Ue(s)),
                (a = w.cssHooks[t] || w.cssHooks[s]) && "get" in a && (i = a.get(e, !0, n)),
                void 0 === i && (i = Be(e, t, r)),
                "normal" === i && t in Ye && (i = Ye[t]),
                "" === n || n ? ((o = parseFloat(i)), !0 === n || isFinite(o) ? o || 0 : i) : i
            );
        },
    }),
        w.each(["height", "width"], function (e, t) {
            w.cssHooks[t] = {
                get: function (e, n, r) {
                    if (n)
                        return !Ke.test(w.css(e, "display")) || (e.getClientRects().length && e.getBoundingClientRect().width)
                            ? Ze(e, t, r)
                            : qe(e, Qe, function () {
                                  return Ze(e, t, r);
                              });
                },
                set: function (e, n, r) {
                    var i,
                        o = Me(e),
                        a = !h.scrollboxSize() && "absolute" === o.position,
                        s = (a || r) && "border-box" === w.css(e, "boxSizing", !1, o),
                        l = r ? Je(e, t, r, s, o) : 0;
                    return (
                        s && a && (l -= Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - parseFloat(o[t]) - Je(e, t, "border", !1, o) - 0.5)),
                        l && (i = te.exec(n)) && "px" !== (i[3] || "px") && ((e.style[t] = n), (n = w.css(e, t))),
                        Ge(0, n, l)
                    );
                },
            };
        }),
        (w.cssHooks.marginLeft = ze(h.reliableMarginLeft, function (e, t) {
            if (t)
                return (
                    (parseFloat(Be(e, "marginLeft")) ||
                        e.getBoundingClientRect().left -
                            qe(e, { marginLeft: 0 }, function () {
                                return e.getBoundingClientRect().left;
                            })) + "px"
                );
        })),
        w.each({ margin: "", padding: "", border: "Width" }, function (e, t) {
            (w.cssHooks[e + t] = {
                expand: function (n) {
                    for (var r = 0, i = {}, o = "string" == typeof n ? n.split(" ") : [n]; r < 4; r++) i[e + ne[r] + t] = o[r] || o[r - 2] || o[0];
                    return i;
                },
            }),
                "margin" !== e && (w.cssHooks[e + t].set = Ge);
        }),
        w.fn.extend({
            css: function (e, t) {
                return B(
                    this,
                    function (e, t, n) {
                        var r,
                            i,
                            o = {},
                            a = 0;
                        if (Array.isArray(t)) {
                            for (r = Me(e), i = t.length; a < i; a++) o[t[a]] = w.css(e, t[a], !1, r);
                            return o;
                        }
                        return void 0 !== n ? w.style(e, t, n) : w.css(e, t);
                    },
                    e,
                    t,
                    1 < arguments.length
                );
            },
        }),
        (((w.Tween = et).prototype = {
            constructor: et,
            init: function (e, t, n, r, i, o) {
                (this.elem = e), (this.prop = n), (this.easing = i || w.easing._default), (this.options = t), (this.start = this.now = this.cur()), (this.end = r), (this.unit = o || (w.cssNumber[n] ? "" : "px"));
            },
            cur: function () {
                var e = et.propHooks[this.prop];
                return e && e.get ? e.get(this) : et.propHooks._default.get(this);
            },
            run: function (e) {
                var t,
                    n = et.propHooks[this.prop];
                return (
                    this.options.duration ? (this.pos = t = w.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration)) : (this.pos = t = e),
                    (this.now = (this.end - this.start) * t + this.start),
                    this.options.step && this.options.step.call(this.elem, this.now, this),
                    n && n.set ? n.set(this) : et.propHooks._default.set(this),
                    this
                );
            },
        }).init.prototype = et.prototype),
        ((et.propHooks = {
            _default: {
                get: function (e) {
                    var t;
                    return 1 !== e.elem.nodeType || (null != e.elem[e.prop] && null == e.elem.style[e.prop]) ? e.elem[e.prop] : (t = w.css(e.elem, e.prop, "")) && "auto" !== t ? t : 0;
                },
                set: function (e) {
                    w.fx.step[e.prop] ? w.fx.step[e.prop](e) : 1 !== e.elem.nodeType || (!w.cssHooks[e.prop] && null == e.elem.style[Ue(e.prop)]) ? (e.elem[e.prop] = e.now) : w.style(e.elem, e.prop, e.now + e.unit);
                },
            },
        }).scrollTop = et.propHooks.scrollLeft = {
            set: function (e) {
                e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now);
            },
        }),
        (w.easing = {
            linear: function (e) {
                return e;
            },
            swing: function (e) {
                return 0.5 - Math.cos(e * Math.PI) / 2;
            },
            _default: "swing",
        }),
        (w.fx = et.prototype.init),
        (w.fx.step = {});
    var tt,
        nt,
        rt,
        it,
        ot = /^(?:toggle|show|hide)$/,
        at = /queueHooks$/;
    function st() {
        nt && (!1 === v.hidden && e.requestAnimationFrame ? e.requestAnimationFrame(st) : e.setTimeout(st, w.fx.interval), w.fx.tick());
    }
    function lt() {
        return (
            e.setTimeout(function () {
                tt = void 0;
            }),
            (tt = Date.now())
        );
    }
    function ut(e, t) {
        var n,
            r = 0,
            i = { height: e };
        for (t = t ? 1 : 0; r < 4; r += 2 - t) i["margin" + (n = ne[r])] = i["padding" + n] = e;
        return t && (i.opacity = i.width = e), i;
    }
    function ct(e, t, n) {
        for (var r, i = (ft.tweeners[t] || []).concat(ft.tweeners["*"]), o = 0, a = i.length; o < a; o++) if ((r = i[o].call(n, t, e))) return r;
    }
    function ft(e, t, n) {
        var r,
            i,
            o = 0,
            a = ft.prefilters.length,
            s = w.Deferred().always(function () {
                delete l.elem;
            }),
            l = function () {
                if (i) return !1;
                for (var t = tt || lt(), n = Math.max(0, u.startTime + u.duration - t), r = 1 - (n / u.duration || 0), o = 0, a = u.tweens.length; o < a; o++) u.tweens[o].run(r);
                return s.notifyWith(e, [u, r, n]), r < 1 && a ? n : (a || s.notifyWith(e, [u, 1, 0]), s.resolveWith(e, [u]), !1);
            },
            u = s.promise({
                elem: e,
                props: w.extend({}, t),
                opts: w.extend(!0, { specialEasing: {}, easing: w.easing._default }, n),
                originalProperties: t,
                originalOptions: n,
                startTime: tt || lt(),
                duration: n.duration,
                tweens: [],
                createTween: function (t, n) {
                    var r = w.Tween(e, u.opts, t, n, u.opts.specialEasing[t] || u.opts.easing);
                    return u.tweens.push(r), r;
                },
                stop: function (t) {
                    var n = 0,
                        r = t ? u.tweens.length : 0;
                    if (i) return this;
                    for (i = !0; n < r; n++) u.tweens[n].run(1);
                    return t ? (s.notifyWith(e, [u, 1, 0]), s.resolveWith(e, [u, t])) : s.rejectWith(e, [u, t]), this;
                },
            }),
            c = u.props;
        for (
            (function (e, t) {
                var n, r, i, o, a;
                for (n in e)
                    if (((i = t[(r = V(n))]), (o = e[n]), Array.isArray(o) && ((i = o[1]), (o = e[n] = o[0])), n !== r && ((e[r] = o), delete e[n]), (a = w.cssHooks[r]) && ("expand" in a)))
                        for (n in ((o = a.expand(o)), delete e[r], o)) (n in e) || ((e[n] = o[n]), (t[n] = i));
                    else t[r] = i;
            })(c, u.opts.specialEasing);
            o < a;
            o++
        )
            if ((r = ft.prefilters[o].call(u, e, c, u.opts))) return p(r.stop) && (w._queueHooks(u.elem, u.opts.queue).stop = r.stop.bind(r)), r;
        return (
            w.map(c, ct, u),
            p(u.opts.start) && u.opts.start.call(e, u),
            u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always),
            w.fx.timer(w.extend(l, { elem: e, anim: u, queue: u.opts.queue })),
            u
        );
    }
    (w.Animation = w.extend(ft, {
        tweeners: {
            "*": [
                function (e, t) {
                    var n = this.createTween(e, t);
                    return se(n.elem, e, te.exec(t), n), n;
                },
            ],
        },
        tweener: function (e, t) {
            p(e) ? ((t = e), (e = ["*"])) : (e = e.match(P));
            for (var n, r = 0, i = e.length; r < i; r++) (n = e[r]), (ft.tweeners[n] = ft.tweeners[n] || []), ft.tweeners[n].unshift(t);
        },
        prefilters: [
            function (e, t, n) {
                var r,
                    i,
                    o,
                    a,
                    s,
                    l,
                    u,
                    c,
                    f = "width" in t || "height" in t,
                    d = this,
                    h = {},
                    p = e.style,
                    g = e.nodeType && ae(e),
                    v = Q.get(e, "fxshow");
                for (r in (n.queue ||
                    (null == (a = w._queueHooks(e, "fx")).unqueued &&
                        ((a.unqueued = 0),
                        (s = a.empty.fire),
                        (a.empty.fire = function () {
                            a.unqueued || s();
                        })),
                    a.unqueued++,
                    d.always(function () {
                        d.always(function () {
                            a.unqueued--, w.queue(e, "fx").length || a.empty.fire();
                        });
                    })),
                t))
                    if (((i = t[r]), ot.test(i))) {
                        if ((delete t[r], (o = o || "toggle" === i), i === (g ? "hide" : "show"))) {
                            if ("show" !== i || !v || void 0 === v[r]) continue;
                            g = !0;
                        }
                        h[r] = (v && v[r]) || w.style(e, r);
                    }
                if ((l = !w.isEmptyObject(t)) || !w.isEmptyObject(h))
                    for (r in (f &&
                        1 === e.nodeType &&
                        ((n.overflow = [p.overflow, p.overflowX, p.overflowY]),
                        null == (u = v && v.display) && (u = Q.get(e, "display")),
                        "none" === (c = w.css(e, "display")) && (u ? (c = u) : (ue([e], !0), (u = e.style.display || u), (c = w.css(e, "display")), ue([e]))),
                        ("inline" === c || ("inline-block" === c && null != u)) &&
                            "none" === w.css(e, "float") &&
                            (l ||
                                (d.done(function () {
                                    p.display = u;
                                }),
                                null == u && ((c = p.display), (u = "none" === c ? "" : c))),
                            (p.display = "inline-block"))),
                    n.overflow &&
                        ((p.overflow = "hidden"),
                        d.always(function () {
                            (p.overflow = n.overflow[0]), (p.overflowX = n.overflow[1]), (p.overflowY = n.overflow[2]);
                        })),
                    (l = !1),
                    h))
                        l ||
                            (v ? "hidden" in v && (g = v.hidden) : (v = Q.access(e, "fxshow", { display: u })),
                            o && (v.hidden = !g),
                            g && ue([e], !0),
                            d.done(function () {
                                for (r in (g || ue([e]), Q.remove(e, "fxshow"), h)) w.style(e, r, h[r]);
                            })),
                            (l = ct(g ? v[r] : 0, r, d)),
                            r in v || ((v[r] = l.start), g && ((l.end = l.start), (l.start = 0)));
            },
        ],
        prefilter: function (e, t) {
            t ? ft.prefilters.unshift(e) : ft.prefilters.push(e);
        },
    })),
        (w.speed = function (e, t, n) {
            var r = e && "object" == _typeof(e) ? w.extend({}, e) : { complete: n || (!n && t) || (p(e) && e), duration: e, easing: (n && t) || (t && !p(t) && t) };
            return (
                w.fx.off ? (r.duration = 0) : "number" != typeof r.duration && (r.duration in w.fx.speeds ? (r.duration = w.fx.speeds[r.duration]) : (r.duration = w.fx.speeds._default)),
                (null != r.queue && !0 !== r.queue) || (r.queue = "fx"),
                (r.old = r.complete),
                (r.complete = function () {
                    p(r.old) && r.old.call(this), r.queue && w.dequeue(this, r.queue);
                }),
                r
            );
        }),
        w.fn.extend({
            fadeTo: function (e, t, n, r) {
                return this.filter(ae).css("opacity", 0).show().end().animate({ opacity: t }, e, n, r);
            },
            animate: function (e, t, n, r) {
                var i = w.isEmptyObject(e),
                    o = w.speed(t, n, r),
                    a = function () {
                        var t = ft(this, w.extend({}, e), o);
                        (i || Q.get(this, "finish")) && t.stop(!0);
                    };
                return (a.finish = a), i || !1 === o.queue ? this.each(a) : this.queue(o.queue, a);
            },
            stop: function (e, t, n) {
                var r = function (e) {
                    var t = e.stop;
                    delete e.stop, t(n);
                };
                return (
                    "string" != typeof e && ((n = t), (t = e), (e = void 0)),
                    t && this.queue(e || "fx", []),
                    this.each(function () {
                        var t = !0,
                            i = null != e && e + "queueHooks",
                            o = w.timers,
                            a = Q.get(this);
                        if (i) a[i] && a[i].stop && r(a[i]);
                        else for (i in a) a[i] && a[i].stop && at.test(i) && r(a[i]);
                        for (i = o.length; i--; ) o[i].elem !== this || (null != e && o[i].queue !== e) || (o[i].anim.stop(n), (t = !1), o.splice(i, 1));
                        (!t && n) || w.dequeue(this, e);
                    })
                );
            },
            finish: function (e) {
                return (
                    !1 !== e && (e = e || "fx"),
                    this.each(function () {
                        var t,
                            n = Q.get(this),
                            r = n[e + "queue"],
                            i = n[e + "queueHooks"],
                            o = w.timers,
                            a = r ? r.length : 0;
                        for (n.finish = !0, w.queue(this, e, []), i && i.stop && i.stop.call(this, !0), t = o.length; t--; ) o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                        for (t = 0; t < a; t++) r[t] && r[t].finish && r[t].finish.call(this);
                        delete n.finish;
                    })
                );
            },
        }),
        w.each(["toggle", "show", "hide"], function (e, t) {
            var n = w.fn[t];
            w.fn[t] = function (e, r, i) {
                return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(ut(t, !0), e, r, i);
            };
        }),
        w.each({ slideDown: ut("show"), slideUp: ut("hide"), slideToggle: ut("toggle"), fadeIn: { opacity: "show" }, fadeOut: { opacity: "hide" }, fadeToggle: { opacity: "toggle" } }, function (e, t) {
            w.fn[e] = function (e, n, r) {
                return this.animate(t, e, n, r);
            };
        }),
        (w.timers = []),
        (w.fx.tick = function () {
            var e,
                t = 0,
                n = w.timers;
            for (tt = Date.now(); t < n.length; t++) (e = n[t])() || n[t] !== e || n.splice(t--, 1);
            n.length || w.fx.stop(), (tt = void 0);
        }),
        (w.fx.timer = function (e) {
            w.timers.push(e), w.fx.start();
        }),
        (w.fx.interval = 13),
        (w.fx.start = function () {
            nt || ((nt = !0), st());
        }),
        (w.fx.stop = function () {
            nt = null;
        }),
        (w.fx.speeds = { slow: 600, fast: 200, _default: 400 }),
        (w.fn.delay = function (t, n) {
            return (
                (t = (w.fx && w.fx.speeds[t]) || t),
                (n = n || "fx"),
                this.queue(n, function (n, r) {
                    var i = e.setTimeout(n, t);
                    r.stop = function () {
                        e.clearTimeout(i);
                    };
                })
            );
        }),
        (rt = v.createElement("input")),
        (it = v.createElement("select").appendChild(v.createElement("option"))),
        (rt.type = "checkbox"),
        (h.checkOn = "" !== rt.value),
        (h.optSelected = it.selected),
        ((rt = v.createElement("input")).value = "t"),
        (rt.type = "radio"),
        (h.radioValue = "t" === rt.value);
    var dt,
        ht = w.expr.attrHandle;
    w.fn.extend({
        attr: function (e, t) {
            return B(this, w.attr, e, t, 1 < arguments.length);
        },
        removeAttr: function (e) {
            return this.each(function () {
                w.removeAttr(this, e);
            });
        },
    }),
        w.extend({
            attr: function (e, t, n) {
                var r,
                    i,
                    o = e.nodeType;
                if (3 !== o && 8 !== o && 2 !== o)
                    return void 0 === e.getAttribute
                        ? w.prop(e, t, n)
                        : ((1 === o && w.isXMLDoc(e)) || (i = w.attrHooks[t.toLowerCase()] || (w.expr.match.bool.test(t) ? dt : void 0)),
                          void 0 !== n
                              ? null === n
                                  ? void w.removeAttr(e, t)
                                  : i && "set" in i && void 0 !== (r = i.set(e, n, t))
                                  ? r
                                  : (e.setAttribute(t, n + ""), n)
                              : i && "get" in i && null !== (r = i.get(e, t))
                              ? r
                              : null == (r = w.find.attr(e, t))
                              ? void 0
                              : r);
            },
            attrHooks: {
                type: {
                    set: function (e, t) {
                        if (!h.radioValue && "radio" === t && E(e, "input")) {
                            var n = e.value;
                            return e.setAttribute("type", t), n && (e.value = n), t;
                        }
                    },
                },
            },
            removeAttr: function (e, t) {
                var n,
                    r = 0,
                    i = t && t.match(P);
                if (i && 1 === e.nodeType) for (; (n = i[r++]); ) e.removeAttribute(n);
            },
        }),
        (dt = {
            set: function (e, t, n) {
                return !1 === t ? w.removeAttr(e, n) : e.setAttribute(n, n), n;
            },
        }),
        w.each(w.expr.match.bool.source.match(/\w+/g), function (e, t) {
            var n = ht[t] || w.find.attr;
            ht[t] = function (e, t, r) {
                var i,
                    o,
                    a = t.toLowerCase();
                return r || ((o = ht[a]), (ht[a] = i), (i = null != n(e, t, r) ? a : null), (ht[a] = o)), i;
            };
        });
    var pt = /^(?:input|select|textarea|button)$/i,
        gt = /^(?:a|area)$/i;
    function vt(e) {
        return (e.match(P) || []).join(" ");
    }
    function mt(e) {
        return (e.getAttribute && e.getAttribute("class")) || "";
    }
    function yt(e) {
        return Array.isArray(e) ? e : ("string" == typeof e && e.match(P)) || [];
    }
    w.fn.extend({
        prop: function (e, t) {
            return B(this, w.prop, e, t, 1 < arguments.length);
        },
        removeProp: function (e) {
            return this.each(function () {
                delete this[w.propFix[e] || e];
            });
        },
    }),
        w.extend({
            prop: function (e, t, n) {
                var r,
                    i,
                    o = e.nodeType;
                if (3 !== o && 8 !== o && 2 !== o)
                    return (
                        (1 === o && w.isXMLDoc(e)) || ((t = w.propFix[t] || t), (i = w.propHooks[t])),
                        void 0 !== n ? (i && "set" in i && void 0 !== (r = i.set(e, n, t)) ? r : (e[t] = n)) : i && "get" in i && null !== (r = i.get(e, t)) ? r : e[t]
                    );
            },
            propHooks: {
                tabIndex: {
                    get: function (e) {
                        var t = w.find.attr(e, "tabindex");
                        return t ? parseInt(t, 10) : pt.test(e.nodeName) || (gt.test(e.nodeName) && e.href) ? 0 : -1;
                    },
                },
            },
            propFix: { for: "htmlFor", class: "className" },
        }),
        h.optSelected ||
            (w.propHooks.selected = {
                get: function (e) {
                    var t = e.parentNode;
                    return t && t.parentNode && t.parentNode.selectedIndex, null;
                },
                set: function (e) {
                    var t = e.parentNode;
                    t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex);
                },
            }),
        w.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
            w.propFix[this.toLowerCase()] = this;
        }),
        w.fn.extend({
            addClass: function (e) {
                var t, n, r, i, o, a;
                return p(e)
                    ? this.each(function (t) {
                          w(this).addClass(e.call(this, t, mt(this)));
                      })
                    : (t = yt(e)).length
                    ? this.each(function () {
                          if (((r = mt(this)), (n = 1 === this.nodeType && " " + vt(r) + " "))) {
                              for (o = 0; o < t.length; o++) (i = t[o]), n.indexOf(" " + i + " ") < 0 && (n += i + " ");
                              (a = vt(n)), r !== a && this.setAttribute("class", a);
                          }
                      })
                    : this;
            },
            removeClass: function (e) {
                var t, n, r, i, o, a;
                return p(e)
                    ? this.each(function (t) {
                          w(this).removeClass(e.call(this, t, mt(this)));
                      })
                    : arguments.length
                    ? (t = yt(e)).length
                        ? this.each(function () {
                              if (((r = mt(this)), (n = 1 === this.nodeType && " " + vt(r) + " "))) {
                                  for (o = 0; o < t.length; o++) for (i = t[o]; -1 < n.indexOf(" " + i + " "); ) n = n.replace(" " + i + " ", " ");
                                  (a = vt(n)), r !== a && this.setAttribute("class", a);
                              }
                          })
                        : this
                    : this.attr("class", "");
            },
            toggleClass: function (e, t) {
                var n,
                    r,
                    i,
                    o,
                    a = _typeof(e),
                    s = "string" === a || Array.isArray(e);
                return p(e)
                    ? this.each(function (n) {
                          w(this).toggleClass(e.call(this, n, mt(this), t), t);
                      })
                    : "boolean" == typeof t && s
                    ? t
                        ? this.addClass(e)
                        : this.removeClass(e)
                    : ((n = yt(e)),
                      this.each(function () {
                          if (s) for (o = w(this), i = 0; i < n.length; i++) (r = n[i]), o.hasClass(r) ? o.removeClass(r) : o.addClass(r);
                          else (void 0 !== e && "boolean" !== a) || ((r = mt(this)) && Q.set(this, "__className__", r), this.setAttribute && this.setAttribute("class", r || !1 === e ? "" : Q.get(this, "__className__") || ""));
                      }));
            },
            hasClass: function (e) {
                var t,
                    n,
                    r = 0;
                for (t = " " + e + " "; (n = this[r++]); ) if (1 === n.nodeType && -1 < (" " + vt(mt(n)) + " ").indexOf(t)) return !0;
                return !1;
            },
        });
    var _t = /\r/g;
    w.fn.extend({
        val: function (e) {
            var t,
                n,
                r,
                i = this[0];
            return arguments.length
                ? ((r = p(e)),
                  this.each(function (n) {
                      var i;
                      1 === this.nodeType &&
                          (null == (i = r ? e.call(this, n, w(this).val()) : e)
                              ? (i = "")
                              : "number" == typeof i
                              ? (i += "")
                              : Array.isArray(i) &&
                                (i = w.map(i, function (e) {
                                    return null == e ? "" : e + "";
                                })),
                          ((t = w.valHooks[this.type] || w.valHooks[this.nodeName.toLowerCase()]) && "set" in t && void 0 !== t.set(this, i, "value")) || (this.value = i));
                  }))
                : i
                ? (t = w.valHooks[i.type] || w.valHooks[i.nodeName.toLowerCase()]) && "get" in t && void 0 !== (n = t.get(i, "value"))
                    ? n
                    : "string" == typeof (n = i.value)
                    ? n.replace(_t, "")
                    : null == n
                    ? ""
                    : n
                : void 0;
        },
    }),
        w.extend({
            valHooks: {
                option: {
                    get: function (e) {
                        var t = w.find.attr(e, "value");
                        return null != t ? t : vt(w.text(e));
                    },
                },
                select: {
                    get: function (e) {
                        var t,
                            n,
                            r,
                            i = e.options,
                            o = e.selectedIndex,
                            a = "select-one" === e.type,
                            s = a ? null : [],
                            l = a ? o + 1 : i.length;
                        for (r = o < 0 ? l : a ? o : 0; r < l; r++)
                            if (((n = i[r]).selected || r === o) && !n.disabled && (!n.parentNode.disabled || !E(n.parentNode, "optgroup"))) {
                                if (((t = w(n).val()), a)) return t;
                                s.push(t);
                            }
                        return s;
                    },
                    set: function (e, t) {
                        for (var n, r, i = e.options, o = w.makeArray(t), a = i.length; a--; ) ((r = i[a]).selected = -1 < w.inArray(w.valHooks.option.get(r), o)) && (n = !0);
                        return n || (e.selectedIndex = -1), o;
                    },
                },
            },
        }),
        w.each(["radio", "checkbox"], function () {
            (w.valHooks[this] = {
                set: function (e, t) {
                    if (Array.isArray(t)) return (e.checked = -1 < w.inArray(w(e).val(), t));
                },
            }),
                h.checkOn ||
                    (w.valHooks[this].get = function (e) {
                        return null === e.getAttribute("value") ? "on" : e.value;
                    });
        }),
        (h.focusin = "onfocusin" in e);
    var bt = /^(?:focusinfocus|focusoutblur)$/,
        wt = function (e) {
            e.stopPropagation();
        };
    w.extend(w.event, {
        trigger: function (t, n, r, i) {
            var o,
                a,
                s,
                l,
                u,
                f,
                d,
                h,
                m = [r || v],
                y = c.call(t, "type") ? t.type : t,
                _ = c.call(t, "namespace") ? t.namespace.split(".") : [];
            if (
                ((a = h = s = r = r || v),
                3 !== r.nodeType &&
                    8 !== r.nodeType &&
                    !bt.test(y + w.event.triggered) &&
                    (-1 < y.indexOf(".") && ((y = (_ = y.split(".")).shift()), _.sort()),
                    (u = y.indexOf(":") < 0 && "on" + y),
                    ((t = t[w.expando] ? t : new w.Event(y, "object" == _typeof(t) && t)).isTrigger = i ? 2 : 3),
                    (t.namespace = _.join(".")),
                    (t.rnamespace = t.namespace ? new RegExp("(^|\\.)" + _.join("\\.(?:.*\\.|)") + "(\\.|$)") : null),
                    (t.result = void 0),
                    t.target || (t.target = r),
                    (n = null == n ? [t] : w.makeArray(n, [t])),
                    (d = w.event.special[y] || {}),
                    i || !d.trigger || !1 !== d.trigger.apply(r, n)))
            ) {
                if (!i && !d.noBubble && !g(r)) {
                    for (l = d.delegateType || y, bt.test(l + y) || (a = a.parentNode); a; a = a.parentNode) m.push(a), (s = a);
                    s === (r.ownerDocument || v) && m.push(s.defaultView || s.parentWindow || e);
                }
                for (o = 0; (a = m[o++]) && !t.isPropagationStopped(); )
                    (h = a),
                        (t.type = 1 < o ? l : d.bindType || y),
                        (f = (Q.get(a, "events") || Object.create(null))[t.type] && Q.get(a, "handle")) && f.apply(a, n),
                        (f = u && a[u]) && f.apply && U(a) && ((t.result = f.apply(a, n)), !1 === t.result && t.preventDefault());
                return (
                    (t.type = y),
                    i ||
                        t.isDefaultPrevented() ||
                        (d._default && !1 !== d._default.apply(m.pop(), n)) ||
                        !U(r) ||
                        (u &&
                            p(r[y]) &&
                            !g(r) &&
                            ((s = r[u]) && (r[u] = null),
                            (w.event.triggered = y),
                            t.isPropagationStopped() && h.addEventListener(y, wt),
                            r[y](),
                            t.isPropagationStopped() && h.removeEventListener(y, wt),
                            (w.event.triggered = void 0),
                            s && (r[u] = s))),
                    t.result
                );
            }
        },
        simulate: function (e, t, n) {
            var r = w.extend(new w.Event(), n, { type: e, isSimulated: !0 });
            w.event.trigger(r, null, t);
        },
    }),
        w.fn.extend({
            trigger: function (e, t) {
                return this.each(function () {
                    w.event.trigger(e, t, this);
                });
            },
            triggerHandler: function (e, t) {
                var n = this[0];
                if (n) return w.event.trigger(e, t, n, !0);
            },
        }),
        h.focusin ||
            w.each({ focus: "focusin", blur: "focusout" }, function (e, t) {
                var n = function (e) {
                    w.event.simulate(t, e.target, w.event.fix(e));
                };
                w.event.special[t] = {
                    setup: function () {
                        var r = this.ownerDocument || this.document || this,
                            i = Q.access(r, t);
                        i || r.addEventListener(e, n, !0), Q.access(r, t, (i || 0) + 1);
                    },
                    teardown: function () {
                        var r = this.ownerDocument || this.document || this,
                            i = Q.access(r, t) - 1;
                        i ? Q.access(r, t, i) : (r.removeEventListener(e, n, !0), Q.remove(r, t));
                    },
                };
            });
    var kt = e.location,
        xt = { guid: Date.now() },
        Ct = /\?/;
    w.parseXML = function (t) {
        var n, r;
        if (!t || "string" != typeof t) return null;
        try {
            n = new e.DOMParser().parseFromString(t, "text/xml");
        } catch (t) {}
        return (
            (r = n && n.getElementsByTagName("parsererror")[0]),
            (n && !r) ||
                w.error(
                    "Invalid XML: " +
                        (r
                            ? w
                                  .map(r.childNodes, function (e) {
                                      return e.textContent;
                                  })
                                  .join("\n")
                            : t)
                ),
            n
        );
    };
    var Tt = /\[\]$/,
        At = /\r?\n/g,
        Et = /^(?:submit|button|image|reset|file)$/i,
        St = /^(?:input|select|textarea|keygen)/i;
    function Ot(e, t, n, r) {
        var i;
        if (Array.isArray(t))
            w.each(t, function (t, i) {
                n || Tt.test(e) ? r(e, i) : Ot(e + "[" + ("object" == _typeof(i) && null != i ? t : "") + "]", i, n, r);
            });
        else if (n || "object" !== _(t)) r(e, t);
        else for (i in t) Ot(e + "[" + i + "]", t[i], n, r);
    }
    (w.param = function (e, t) {
        var n,
            r = [],
            i = function (e, t) {
                var n = p(t) ? t() : t;
                r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(null == n ? "" : n);
            };
        if (null == e) return "";
        if (Array.isArray(e) || (e.jquery && !w.isPlainObject(e)))
            w.each(e, function () {
                i(this.name, this.value);
            });
        else for (n in e) Ot(n, e[n], t, i);
        return r.join("&");
    }),
        w.fn.extend({
            serialize: function () {
                return w.param(this.serializeArray());
            },
            serializeArray: function () {
                return this.map(function () {
                    var e = w.prop(this, "elements");
                    return e ? w.makeArray(e) : this;
                })
                    .filter(function () {
                        var e = this.type;
                        return this.name && !w(this).is(":disabled") && St.test(this.nodeName) && !Et.test(e) && (this.checked || !de.test(e));
                    })
                    .map(function (e, t) {
                        var n = w(this).val();
                        return null == n
                            ? null
                            : Array.isArray(n)
                            ? w.map(n, function (e) {
                                  return { name: t.name, value: e.replace(At, "\r\n") };
                              })
                            : { name: t.name, value: n.replace(At, "\r\n") };
                    })
                    .get();
            },
        });
    var jt = /%20/g,
        Nt = /#.*$/,
        Dt = /([?&])_=[^&]*/,
        Lt = /^(.*?):[ \t]*([^\r\n]*)$/gm,
        It = /^(?:GET|HEAD)$/,
        Pt = /^\/\//,
        Ht = {},
        Mt = {},
        qt = "*/".concat("*"),
        Ft = v.createElement("a");
    function Rt(e) {
        return function (t, n) {
            "string" != typeof t && ((n = t), (t = "*"));
            var r,
                i = 0,
                o = t.toLowerCase().match(P) || [];
            if (p(n)) for (; (r = o[i++]); ) "+" === r[0] ? ((r = r.slice(1) || "*"), (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n);
        };
    }
    function Wt(e, t, n, r) {
        var i = {},
            o = e === Mt;
        function a(s) {
            var l;
            return (
                (i[s] = !0),
                w.each(e[s] || [], function (e, s) {
                    var u = s(t, n, r);
                    return "string" != typeof u || o || i[u] ? (o ? !(l = u) : void 0) : (t.dataTypes.unshift(u), a(u), !1);
                }),
                l
            );
        }
        return a(t.dataTypes[0]) || (!i["*"] && a("*"));
    }
    function Bt(e, t) {
        var n,
            r,
            i = w.ajaxSettings.flatOptions || {};
        for (n in t) void 0 !== t[n] && ((i[n] ? e : r || (r = {}))[n] = t[n]);
        return r && w.extend(!0, e, r), e;
    }
    (Ft.href = kt.href),
        w.extend({
            active: 0,
            lastModified: {},
            etag: {},
            ajaxSettings: {
                url: kt.href,
                type: "GET",
                isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(kt.protocol),
                global: !0,
                processData: !0,
                async: !0,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                accepts: { "*": qt, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript" },
                contents: { xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/ },
                responseFields: { xml: "responseXML", text: "responseText", json: "responseJSON" },
                converters: { "* text": String, "text html": !0, "text json": JSON.parse, "text xml": w.parseXML },
                flatOptions: { url: !0, context: !0 },
            },
            ajaxSetup: function (e, t) {
                return t ? Bt(Bt(e, w.ajaxSettings), t) : Bt(w.ajaxSettings, e);
            },
            ajaxPrefilter: Rt(Ht),
            ajaxTransport: Rt(Mt),
            ajax: function (t, n) {
                "object" == _typeof(t) && ((n = t), (t = void 0)), (n = n || {});
                var r,
                    i,
                    o,
                    a,
                    s,
                    l,
                    u,
                    c,
                    f,
                    d,
                    h = w.ajaxSetup({}, n),
                    p = h.context || h,
                    g = h.context && (p.nodeType || p.jquery) ? w(p) : w.event,
                    m = w.Deferred(),
                    y = w.Callbacks("once memory"),
                    _ = h.statusCode || {},
                    b = {},
                    k = {},
                    x = "canceled",
                    C = {
                        readyState: 0,
                        getResponseHeader: function (e) {
                            var t;
                            if (u) {
                                if (!a) for (a = {}; (t = Lt.exec(o)); ) a[t[1].toLowerCase() + " "] = (a[t[1].toLowerCase() + " "] || []).concat(t[2]);
                                t = a[e.toLowerCase() + " "];
                            }
                            return null == t ? null : t.join(", ");
                        },
                        getAllResponseHeaders: function () {
                            return u ? o : null;
                        },
                        setRequestHeader: function (e, t) {
                            return null == u && ((e = k[e.toLowerCase()] = k[e.toLowerCase()] || e), (b[e] = t)), this;
                        },
                        overrideMimeType: function (e) {
                            return null == u && (h.mimeType = e), this;
                        },
                        statusCode: function (e) {
                            var t;
                            if (e)
                                if (u) C.always(e[C.status]);
                                else for (t in e) _[t] = [_[t], e[t]];
                            return this;
                        },
                        abort: function (e) {
                            var t = e || x;
                            return r && r.abort(t), T(0, t), this;
                        },
                    };
                if (
                    (m.promise(C),
                    (h.url = ((t || h.url || kt.href) + "").replace(Pt, kt.protocol + "//")),
                    (h.type = n.method || n.type || h.method || h.type),
                    (h.dataTypes = (h.dataType || "*").toLowerCase().match(P) || [""]),
                    null == h.crossDomain)
                ) {
                    l = v.createElement("a");
                    try {
                        (l.href = h.url), (l.href = l.href), (h.crossDomain = Ft.protocol + "//" + Ft.host != l.protocol + "//" + l.host);
                    } catch (t) {
                        h.crossDomain = !0;
                    }
                }
                if ((h.data && h.processData && "string" != typeof h.data && (h.data = w.param(h.data, h.traditional)), Wt(Ht, h, n, C), u)) return C;
                for (f in ((c = w.event && h.global) && 0 == w.active++ && w.event.trigger("ajaxStart"),
                (h.type = h.type.toUpperCase()),
                (h.hasContent = !It.test(h.type)),
                (i = h.url.replace(Nt, "")),
                h.hasContent
                    ? h.data && h.processData && 0 === (h.contentType || "").indexOf("application/x-www-form-urlencoded") && (h.data = h.data.replace(jt, "+"))
                    : ((d = h.url.slice(i.length)),
                      h.data && (h.processData || "string" == typeof h.data) && ((i += (Ct.test(i) ? "&" : "?") + h.data), delete h.data),
                      !1 === h.cache && ((i = i.replace(Dt, "$1")), (d = (Ct.test(i) ? "&" : "?") + "_=" + xt.guid++ + d)),
                      (h.url = i + d)),
                h.ifModified && (w.lastModified[i] && C.setRequestHeader("If-Modified-Since", w.lastModified[i]), w.etag[i] && C.setRequestHeader("If-None-Match", w.etag[i])),
                ((h.data && h.hasContent && !1 !== h.contentType) || n.contentType) && C.setRequestHeader("Content-Type", h.contentType),
                C.setRequestHeader("Accept", h.dataTypes[0] && h.accepts[h.dataTypes[0]] ? h.accepts[h.dataTypes[0]] + ("*" !== h.dataTypes[0] ? ", " + qt + "; q=0.01" : "") : h.accepts["*"]),
                h.headers))
                    C.setRequestHeader(f, h.headers[f]);
                if (h.beforeSend && (!1 === h.beforeSend.call(p, C, h) || u)) return C.abort();
                if (((x = "abort"), y.add(h.complete), C.done(h.success), C.fail(h.error), (r = Wt(Mt, h, n, C)))) {
                    if (((C.readyState = 1), c && g.trigger("ajaxSend", [C, h]), u)) return C;
                    h.async &&
                        0 < h.timeout &&
                        (s = e.setTimeout(function () {
                            C.abort("timeout");
                        }, h.timeout));
                    try {
                        (u = !1), r.send(b, T);
                    } catch (t) {
                        if (u) throw t;
                        T(-1, t);
                    }
                } else T(-1, "No Transport");
                function T(t, n, a, l) {
                    var f,
                        d,
                        v,
                        b,
                        k,
                        x = n;
                    u ||
                        ((u = !0),
                        s && e.clearTimeout(s),
                        (r = void 0),
                        (o = l || ""),
                        (C.readyState = 0 < t ? 4 : 0),
                        (f = (200 <= t && t < 300) || 304 === t),
                        a &&
                            (b = (function (e, t, n) {
                                for (var r, i, o, a, s = e.contents, l = e.dataTypes; "*" === l[0]; ) l.shift(), void 0 === r && (r = e.mimeType || t.getResponseHeader("Content-Type"));
                                if (r)
                                    for (i in s)
                                        if (s[i] && s[i].test(r)) {
                                            l.unshift(i);
                                            break;
                                        }
                                if (l[0] in n) o = l[0];
                                else {
                                    for (i in n) {
                                        if (!l[0] || e.converters[i + " " + l[0]]) {
                                            o = i;
                                            break;
                                        }
                                        a || (a = i);
                                    }
                                    o = o || a;
                                }
                                if (o) return o !== l[0] && l.unshift(o), n[o];
                            })(h, C, a)),
                        !f && -1 < w.inArray("script", h.dataTypes) && w.inArray("json", h.dataTypes) < 0 && (h.converters["text script"] = function () {}),
                        (b = (function (e, t, n, r) {
                            var i,
                                o,
                                a,
                                s,
                                l,
                                u = {},
                                c = e.dataTypes.slice();
                            if (c[1]) for (a in e.converters) u[a.toLowerCase()] = e.converters[a];
                            for (o = c.shift(); o; )
                                if ((e.responseFields[o] && (n[e.responseFields[o]] = t), !l && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), (l = o), (o = c.shift())))
                                    if ("*" === o) o = l;
                                    else if ("*" !== l && l !== o) {
                                        if (!(a = u[l + " " + o] || u["* " + o]))
                                            for (i in u)
                                                if ((s = i.split(" "))[1] === o && (a = u[l + " " + s[0]] || u["* " + s[0]])) {
                                                    !0 === a ? (a = u[i]) : !0 !== u[i] && ((o = s[0]), c.unshift(s[1]));
                                                    break;
                                                }
                                        if (!0 !== a)
                                            if (a && e.throws) t = a(t);
                                            else
                                                try {
                                                    t = a(t);
                                                } catch (e) {
                                                    return { state: "parsererror", error: a ? e : "No conversion from " + l + " to " + o };
                                                }
                                    }
                            return { state: "success", data: t };
                        })(h, b, C, f)),
                        f
                            ? (h.ifModified && ((k = C.getResponseHeader("Last-Modified")) && (w.lastModified[i] = k), (k = C.getResponseHeader("etag")) && (w.etag[i] = k)),
                              204 === t || "HEAD" === h.type ? (x = "nocontent") : 304 === t ? (x = "notmodified") : ((x = b.state), (d = b.data), (f = !(v = b.error))))
                            : ((v = x), (!t && x) || ((x = "error"), t < 0 && (t = 0))),
                        (C.status = t),
                        (C.statusText = (n || x) + ""),
                        f ? m.resolveWith(p, [d, x, C]) : m.rejectWith(p, [C, x, v]),
                        C.statusCode(_),
                        (_ = void 0),
                        c && g.trigger(f ? "ajaxSuccess" : "ajaxError", [C, h, f ? d : v]),
                        y.fireWith(p, [C, x]),
                        c && (g.trigger("ajaxComplete", [C, h]), --w.active || w.event.trigger("ajaxStop")));
                }
                return C;
            },
            getJSON: function (e, t, n) {
                return w.get(e, t, n, "json");
            },
            getScript: function (e, t) {
                return w.get(e, void 0, t, "script");
            },
        }),
        w.each(["get", "post"], function (e, t) {
            w[t] = function (e, n, r, i) {
                return p(n) && ((i = i || r), (r = n), (n = void 0)), w.ajax(w.extend({ url: e, type: t, dataType: i, data: n, success: r }, w.isPlainObject(e) && e));
            };
        }),
        w.ajaxPrefilter(function (e) {
            var t;
            for (t in e.headers) "content-type" === t.toLowerCase() && (e.contentType = e.headers[t] || "");
        }),
        (w._evalUrl = function (e, t, n) {
            return w.ajax({
                url: e,
                type: "GET",
                dataType: "script",
                cache: !0,
                async: !1,
                global: !1,
                converters: { "text script": function () {} },
                dataFilter: function (e) {
                    w.globalEval(e, t, n);
                },
            });
        }),
        w.fn.extend({
            wrapAll: function (e) {
                var t;
                return (
                    this[0] &&
                        (p(e) && (e = e.call(this[0])),
                        (t = w(e, this[0].ownerDocument).eq(0).clone(!0)),
                        this[0].parentNode && t.insertBefore(this[0]),
                        t
                            .map(function () {
                                for (var e = this; e.firstElementChild; ) e = e.firstElementChild;
                                return e;
                            })
                            .append(this)),
                    this
                );
            },
            wrapInner: function (e) {
                return p(e)
                    ? this.each(function (t) {
                          w(this).wrapInner(e.call(this, t));
                      })
                    : this.each(function () {
                          var t = w(this),
                              n = t.contents();
                          n.length ? n.wrapAll(e) : t.append(e);
                      });
            },
            wrap: function (e) {
                var t = p(e);
                return this.each(function (n) {
                    w(this).wrapAll(t ? e.call(this, n) : e);
                });
            },
            unwrap: function (e) {
                return (
                    this.parent(e)
                        .not("body")
                        .each(function () {
                            w(this).replaceWith(this.childNodes);
                        }),
                    this
                );
            },
        }),
        (w.expr.pseudos.hidden = function (e) {
            return !w.expr.pseudos.visible(e);
        }),
        (w.expr.pseudos.visible = function (e) {
            return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length);
        }),
        (w.ajaxSettings.xhr = function () {
            try {
                return new e.XMLHttpRequest();
            } catch (e) {}
        });
    var zt = { 0: 200, 1223: 204 },
        $t = w.ajaxSettings.xhr();
    (h.cors = !!$t && "withCredentials" in $t),
        (h.ajax = $t = !!$t),
        w.ajaxTransport(function (t) {
            var n, r;
            if (h.cors || ($t && !t.crossDomain))
                return {
                    send: function (i, o) {
                        var a,
                            s = t.xhr();
                        if ((s.open(t.type, t.url, t.async, t.username, t.password), t.xhrFields)) for (a in t.xhrFields) s[a] = t.xhrFields[a];
                        for (a in (t.mimeType && s.overrideMimeType && s.overrideMimeType(t.mimeType), t.crossDomain || i["X-Requested-With"] || (i["X-Requested-With"] = "XMLHttpRequest"), i)) s.setRequestHeader(a, i[a]);
                        (n = function (e) {
                            return function () {
                                n &&
                                    ((n = r = s.onload = s.onerror = s.onabort = s.ontimeout = s.onreadystatechange = null),
                                    "abort" === e
                                        ? s.abort()
                                        : "error" === e
                                        ? "number" != typeof s.status
                                            ? o(0, "error")
                                            : o(s.status, s.statusText)
                                        : o(zt[s.status] || s.status, s.statusText, "text" !== (s.responseType || "text") || "string" != typeof s.responseText ? { binary: s.response } : { text: s.responseText }, s.getAllResponseHeaders()));
                            };
                        }),
                            (s.onload = n()),
                            (r = s.onerror = s.ontimeout = n("error")),
                            void 0 !== s.onabort
                                ? (s.onabort = r)
                                : (s.onreadystatechange = function () {
                                      4 === s.readyState &&
                                          e.setTimeout(function () {
                                              n && r();
                                          });
                                  }),
                            (n = n("abort"));
                        try {
                            s.send((t.hasContent && t.data) || null);
                        } catch (i) {
                            if (n) throw i;
                        }
                    },
                    abort: function () {
                        n && n();
                    },
                };
        }),
        w.ajaxPrefilter(function (e) {
            e.crossDomain && (e.contents.script = !1);
        }),
        w.ajaxSetup({
            accepts: { script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript" },
            contents: { script: /\b(?:java|ecma)script\b/ },
            converters: {
                "text script": function (e) {
                    return w.globalEval(e), e;
                },
            },
        }),
        w.ajaxPrefilter("script", function (e) {
            void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET");
        }),
        w.ajaxTransport("script", function (e) {
            var t, n;
            if (e.crossDomain || e.scriptAttrs)
                return {
                    send: function (r, i) {
                        (t = w("<script>")
                            .attr(e.scriptAttrs || {})
                            .prop({ charset: e.scriptCharset, src: e.url })
                            .on(
                                "load error",
                                (n = function (e) {
                                    t.remove(), (n = null), e && i("error" === e.type ? 404 : 200, e.type);
                                })
                            )),
                            v.head.appendChild(t[0]);
                    },
                    abort: function () {
                        n && n();
                    },
                };
        });
    var Xt,
        Vt = [],
        Ut = /(=)\?(?=&|$)|\?\?/;
    w.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function () {
            var e = Vt.pop() || w.expando + "_" + xt.guid++;
            return (this[e] = !0), e;
        },
    }),
        w.ajaxPrefilter("json jsonp", function (t, n, r) {
            var i,
                o,
                a,
                s = !1 !== t.jsonp && (Ut.test(t.url) ? "url" : "string" == typeof t.data && 0 === (t.contentType || "").indexOf("application/x-www-form-urlencoded") && Ut.test(t.data) && "data");
            if (s || "jsonp" === t.dataTypes[0])
                return (
                    (i = t.jsonpCallback = p(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback),
                    s ? (t[s] = t[s].replace(Ut, "$1" + i)) : !1 !== t.jsonp && (t.url += (Ct.test(t.url) ? "&" : "?") + t.jsonp + "=" + i),
                    (t.converters["script json"] = function () {
                        return a || w.error(i + " was not called"), a[0];
                    }),
                    (t.dataTypes[0] = "json"),
                    (o = e[i]),
                    (e[i] = function () {
                        a = arguments;
                    }),
                    r.always(function () {
                        void 0 === o ? w(e).removeProp(i) : (e[i] = o), t[i] && ((t.jsonpCallback = n.jsonpCallback), Vt.push(i)), a && p(o) && o(a[0]), (a = o = void 0);
                    }),
                    "script"
                );
        }),
        (h.createHTMLDocument = (((Xt = v.implementation.createHTMLDocument("").body).innerHTML = "<form></form><form></form>"), 2 === Xt.childNodes.length)),
        (w.parseHTML = function (e, t, n) {
            return "string" != typeof e
                ? []
                : ("boolean" == typeof t && ((n = t), (t = !1)),
                  t || (h.createHTMLDocument ? (((r = (t = v.implementation.createHTMLDocument("")).createElement("base")).href = v.location.href), t.head.appendChild(r)) : (t = v)),
                  (o = !n && []),
                  (i = S.exec(e)) ? [t.createElement(i[1])] : ((i = _e([e], t, o)), o && o.length && w(o).remove(), w.merge([], i.childNodes)));
            var r, i, o;
        }),
        (w.fn.load = function (e, t, n) {
            var r,
                i,
                o,
                a = this,
                s = e.indexOf(" ");
            return (
                -1 < s && ((r = vt(e.slice(s))), (e = e.slice(0, s))),
                p(t) ? ((n = t), (t = void 0)) : t && "object" == _typeof(t) && (i = "POST"),
                0 < a.length &&
                    w
                        .ajax({ url: e, type: i || "GET", dataType: "html", data: t })
                        .done(function (e) {
                            (o = arguments), a.html(r ? w("<div>").append(w.parseHTML(e)).find(r) : e);
                        })
                        .always(
                            n &&
                                function (e, t) {
                                    a.each(function () {
                                        n.apply(this, o || [e.responseText, t, e]);
                                    });
                                }
                        ),
                this
            );
        }),
        (w.expr.pseudos.animated = function (e) {
            return w.grep(w.timers, function (t) {
                return e === t.elem;
            }).length;
        }),
        (w.offset = {
            setOffset: function (e, t, n) {
                var r,
                    i,
                    o,
                    a,
                    s,
                    l,
                    u = w.css(e, "position"),
                    c = w(e),
                    f = {};
                "static" === u && (e.style.position = "relative"),
                    (s = c.offset()),
                    (o = w.css(e, "top")),
                    (l = w.css(e, "left")),
                    ("absolute" === u || "fixed" === u) && -1 < (o + l).indexOf("auto") ? ((a = (r = c.position()).top), (i = r.left)) : ((a = parseFloat(o) || 0), (i = parseFloat(l) || 0)),
                    p(t) && (t = t.call(e, n, w.extend({}, s))),
                    null != t.top && (f.top = t.top - s.top + a),
                    null != t.left && (f.left = t.left - s.left + i),
                    "using" in t ? t.using.call(e, f) : c.css(f);
            },
        }),
        w.fn.extend({
            offset: function (e) {
                if (arguments.length)
                    return void 0 === e
                        ? this
                        : this.each(function (t) {
                              w.offset.setOffset(this, e, t);
                          });
                var t,
                    n,
                    r = this[0];
                return r ? (r.getClientRects().length ? ((t = r.getBoundingClientRect()), (n = r.ownerDocument.defaultView), { top: t.top + n.pageYOffset, left: t.left + n.pageXOffset }) : { top: 0, left: 0 }) : void 0;
            },
            position: function () {
                if (this[0]) {
                    var e,
                        t,
                        n,
                        r = this[0],
                        i = { top: 0, left: 0 };
                    if ("fixed" === w.css(r, "position")) t = r.getBoundingClientRect();
                    else {
                        for (t = this.offset(), n = r.ownerDocument, e = r.offsetParent || n.documentElement; e && (e === n.body || e === n.documentElement) && "static" === w.css(e, "position"); ) e = e.parentNode;
                        e && e !== r && 1 === e.nodeType && (((i = w(e).offset()).top += w.css(e, "borderTopWidth", !0)), (i.left += w.css(e, "borderLeftWidth", !0)));
                    }
                    return { top: t.top - i.top - w.css(r, "marginTop", !0), left: t.left - i.left - w.css(r, "marginLeft", !0) };
                }
            },
            offsetParent: function () {
                return this.map(function () {
                    for (var e = this.offsetParent; e && "static" === w.css(e, "position"); ) e = e.offsetParent;
                    return e || re;
                });
            },
        }),
        w.each({ scrollLeft: "pageXOffset", scrollTop: "pageYOffset" }, function (e, t) {
            var n = "pageYOffset" === t;
            w.fn[e] = function (r) {
                return B(
                    this,
                    function (e, r, i) {
                        var o;
                        if ((g(e) ? (o = e) : 9 === e.nodeType && (o = e.defaultView), void 0 === i)) return o ? o[t] : e[r];
                        o ? o.scrollTo(n ? o.pageXOffset : i, n ? i : o.pageYOffset) : (e[r] = i);
                    },
                    e,
                    r,
                    arguments.length
                );
            };
        }),
        w.each(["top", "left"], function (e, t) {
            w.cssHooks[t] = ze(h.pixelPosition, function (e, n) {
                if (n) return (n = Be(e, t)), Pe.test(n) ? w(e).position()[t] + "px" : n;
            });
        }),
        w.each({ Height: "height", Width: "width" }, function (e, t) {
            w.each({ padding: "inner" + e, content: t, "": "outer" + e }, function (n, r) {
                w.fn[r] = function (i, o) {
                    var a = arguments.length && (n || "boolean" != typeof i),
                        s = n || (!0 === i || !0 === o ? "margin" : "border");
                    return B(
                        this,
                        function (t, n, i) {
                            var o;
                            return g(t)
                                ? 0 === r.indexOf("outer")
                                    ? t["inner" + e]
                                    : t.document.documentElement["client" + e]
                                : 9 === t.nodeType
                                ? ((o = t.documentElement), Math.max(t.body["scroll" + e], o["scroll" + e], t.body["offset" + e], o["offset" + e], o["client" + e]))
                                : void 0 === i
                                ? w.css(t, n, s)
                                : w.style(t, n, i, s);
                        },
                        t,
                        a ? i : void 0,
                        a
                    );
                };
            });
        }),
        w.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
            w.fn[t] = function (e) {
                return this.on(t, e);
            };
        }),
        w.fn.extend({
            bind: function (e, t, n) {
                return this.on(e, null, t, n);
            },
            unbind: function (e, t) {
                return this.off(e, null, t);
            },
            delegate: function (e, t, n, r) {
                return this.on(t, e, n, r);
            },
            undelegate: function (e, t, n) {
                return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n);
            },
            hover: function (e, t) {
                return this.mouseenter(e).mouseleave(t || e);
            },
        }),
        w.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (e, t) {
            w.fn[t] = function (e, n) {
                return 0 < arguments.length ? this.on(t, null, e, n) : this.trigger(t);
            };
        });
    var Kt = /^[\s\uFEFF\xA0]+|([^\s\uFEFF\xA0])[\s\uFEFF\xA0]+$/g;
    (w.proxy = function (e, t) {
        var n, r, o;
        if (("string" == typeof t && ((n = e[t]), (t = e), (e = n)), p(e)))
            return (
                (r = i.call(arguments, 2)),
                ((o = function () {
                    return e.apply(t || this, r.concat(i.call(arguments)));
                }).guid = e.guid = e.guid || w.guid++),
                o
            );
    }),
        (w.holdReady = function (e) {
            e ? w.readyWait++ : w.ready(!0);
        }),
        (w.isArray = Array.isArray),
        (w.parseJSON = JSON.parse),
        (w.nodeName = E),
        (w.isFunction = p),
        (w.isWindow = g),
        (w.camelCase = V),
        (w.type = _),
        (w.now = Date.now),
        (w.isNumeric = function (e) {
            var t = w.type(e);
            return ("number" === t || "string" === t) && !isNaN(e - parseFloat(e));
        }),
        (w.trim = function (e) {
            return null == e ? "" : (e + "").replace(Kt, "$1");
        }),
        "function" == typeof define &&
            define.amd &&
            define("jquery", [], function () {
                return w;
            });
    var Qt = e.jQuery,
        Yt = e.$;
    return (
        (w.noConflict = function (t) {
            return e.$ === w && (e.$ = Yt), t && e.jQuery === w && (e.jQuery = Qt), w;
        }),
        void 0 === t && (e.jQuery = e.$ = w),
        w
    );
}),
    (function (e, t) {
        "object" == ("undefined" == typeof exports ? "undefined" : _typeof(exports)) && "undefined" != typeof module
            ? (module.exports = t(require("@popperjs/core")))
            : "function" == typeof define && define.amd
            ? define(["@popperjs/core"], t)
            : ((e = "undefined" != typeof globalThis ? globalThis : e || self).bootstrap = t(e.Popper));
    })(this, function (e) {
        "use strict";
        var t = (function (e) {
                var t = Object.create(null, _defineProperty({}, Symbol.toStringTag, { value: "Module" }));
                if (e) {
                    var n = function (n) {
                        if ("default" !== n) {
                            var r = Object.getOwnPropertyDescriptor(e, n);
                            Object.defineProperty(
                                t,
                                n,
                                r.get
                                    ? r
                                    : {
                                          enumerable: !0,
                                          get: function () {
                                              return e[n];
                                          },
                                      }
                            );
                        }
                    };
                    for (var r in e) n(r);
                }
                return (t.default = e), Object.freeze(t);
            })(e),
            n = "transitionend",
            r = function (e) {
                return (
                    e &&
                        window.CSS &&
                        window.CSS.escape &&
                        (e = e.replace(/#([^\s"#']+)/g, function (e, t) {
                            return "#".concat(CSS.escape(t));
                        })),
                    e
                );
            },
            i = function (e) {
                e.dispatchEvent(new Event(n));
            },
            o = function (e) {
                return !(!e || "object" != _typeof(e)) && (void 0 !== e.jquery && (e = e[0]), void 0 !== e.nodeType);
            },
            a = function (e) {
                return o(e) ? (e.jquery ? e[0] : e) : "string" == typeof e && e.length > 0 ? document.querySelector(r(e)) : null;
            },
            s = function (e) {
                if (!o(e) || 0 === e.getClientRects().length) return !1;
                var t = "visible" === getComputedStyle(e).getPropertyValue("visibility"),
                    n = e.closest("details:not([open])");
                if (!n) return t;
                if (n !== e) {
                    var r = e.closest("summary");
                    if (r && r.parentNode !== n) return !1;
                    if (null === r) return !1;
                }
                return t;
            },
            l = function (e) {
                return !e || e.nodeType !== Node.ELEMENT_NODE || !!e.classList.contains("disabled") || (void 0 !== e.disabled ? e.disabled : e.hasAttribute("disabled") && "false" !== e.getAttribute("disabled"));
            },
            u = function e(t) {
                if (!document.documentElement.attachShadow) return null;
                if ("function" == typeof t.getRootNode) {
                    var n = t.getRootNode();
                    return n instanceof ShadowRoot ? n : null;
                }
                return t instanceof ShadowRoot ? t : t.parentNode ? e(t.parentNode) : null;
            },
            c = function () {},
            f = function (e) {
                e.offsetHeight;
            },
            d = function () {
                return window.jQuery && !document.body.hasAttribute("data-bs-no-jquery") ? window.jQuery : null;
            },
            h = [],
            p = function () {
                return "rtl" === document.documentElement.dir;
            },
            g = function (e) {
                var t;
                (t = function () {
                    var t = d();
                    if (t) {
                        var n = e.NAME,
                            r = t.fn[n];
                        (t.fn[n] = e.jQueryInterface),
                            (t.fn[n].Constructor = e),
                            (t.fn[n].noConflict = function () {
                                return (t.fn[n] = r), e.jQueryInterface;
                            });
                    }
                }),
                    "loading" === document.readyState
                        ? (h.length ||
                              document.addEventListener("DOMContentLoaded", function () {
                                  var e,
                                      t = _createForOfIteratorHelper(h);
                                  try {
                                      for (t.s(); !(e = t.n()).done; ) {
                                          (0, e.value)();
                                      }
                                  } catch (e) {
                                      t.e(e);
                                  } finally {
                                      t.f();
                                  }
                              }),
                          h.push(t))
                        : t();
            },
            v = function (e) {
                var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : [],
                    n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e;
                return "function" == typeof e ? e.apply(void 0, _toConsumableArray(t)) : n;
            },
            m = function (e, t) {
                if (!(arguments.length > 2 && void 0 !== arguments[2]) || arguments[2]) {
                    var r =
                            (function (e) {
                                if (!e) return 0;
                                var t = window.getComputedStyle(e),
                                    n = t.transitionDuration,
                                    r = t.transitionDelay,
                                    i = Number.parseFloat(n),
                                    o = Number.parseFloat(r);
                                return i || o ? ((n = n.split(",")[0]), (r = r.split(",")[0]), 1e3 * (Number.parseFloat(n) + Number.parseFloat(r))) : 0;
                            })(t) + 5,
                        o = !1;
                    t.addEventListener(n, function r(i) {
                        i.target === t && ((o = !0), t.removeEventListener(n, r), v(e));
                    }),
                        setTimeout(function () {
                            o || i(t);
                        }, r);
                } else v(e);
            },
            y = function (e, t, n, r) {
                var i = e.length,
                    o = e.indexOf(t);
                return -1 === o ? (!n && r ? e[i - 1] : e[0]) : ((o += n ? 1 : -1), r && (o = (o + i) % i), e[Math.max(0, Math.min(o, i - 1))]);
            },
            _ = /[^.]*(?=\..*)\.|.*/,
            b = /\..*/,
            w = /::\d+$/,
            k = {},
            x = 1,
            C = { mouseenter: "mouseover", mouseleave: "mouseout" },
            T = new Set([
                "click",
                "dblclick",
                "mouseup",
                "mousedown",
                "contextmenu",
                "mousewheel",
                "DOMMouseScroll",
                "mouseover",
                "mouseout",
                "mousemove",
                "selectstart",
                "selectend",
                "keydown",
                "keypress",
                "keyup",
                "orientationchange",
                "touchstart",
                "touchmove",
                "touchend",
                "touchcancel",
                "pointerdown",
                "pointermove",
                "pointerup",
                "pointerleave",
                "pointercancel",
                "gesturestart",
                "gesturechange",
                "gestureend",
                "focus",
                "blur",
                "change",
                "reset",
                "select",
                "submit",
                "focusin",
                "focusout",
                "load",
                "unload",
                "beforeunload",
                "resize",
                "move",
                "DOMContentLoaded",
                "readystatechange",
                "error",
                "abort",
                "scroll",
            ]);
        function A(e, t) {
            return (t && "".concat(t, "::").concat(x++)) || e.uidEvent || x++;
        }
        function E(e) {
            var t = A(e);
            return (e.uidEvent = t), (k[t] = k[t] || {}), k[t];
        }
        function S(e, t) {
            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : null;
            return Object.values(e).find(function (e) {
                return e.callable === t && e.delegationSelector === n;
            });
        }
        function O(e, t, n) {
            var r = "string" == typeof t,
                i = r ? n : t || n,
                o = L(e);
            return T.has(o) || (o = e), [r, i, o];
        }
        function j(e, t, n, r, i) {
            if ("string" == typeof t && e) {
                var o = _slicedToArray(O(t, n, r), 3),
                    a = o[0],
                    s = o[1],
                    l = o[2];
                if (t in C) {
                    s = (function (e) {
                        return function (t) {
                            if (!t.relatedTarget || (t.relatedTarget !== t.delegateTarget && !t.delegateTarget.contains(t.relatedTarget))) return e.call(this, t);
                        };
                    })(s);
                }
                var u = E(e),
                    c = u[l] || (u[l] = {}),
                    f = S(c, s, a ? n : null);
                if (f) f.oneOff = f.oneOff && i;
                else {
                    var d = A(s, t.replace(_, "")),
                        h = a
                            ? (function (e, t, n) {
                                  return function r(i) {
                                      for (var o = e.querySelectorAll(t), a = i.target; a && a !== this; a = a.parentNode) {
                                          var s,
                                              l = _createForOfIteratorHelper(o);
                                          try {
                                              for (l.s(); !(s = l.n()).done; ) {
                                                  if (s.value === a) return P(i, { delegateTarget: a }), r.oneOff && I.off(e, i.type, t, n), n.apply(a, [i]);
                                              }
                                          } catch (e) {
                                              l.e(e);
                                          } finally {
                                              l.f();
                                          }
                                      }
                                  };
                              })(e, n, s)
                            : (function (e, t) {
                                  return function n(r) {
                                      return P(r, { delegateTarget: e }), n.oneOff && I.off(e, r.type, t), t.apply(e, [r]);
                                  };
                              })(e, s);
                    (h.delegationSelector = a ? n : null), (h.callable = s), (h.oneOff = i), (h.uidEvent = d), (c[d] = h), e.addEventListener(l, h, a);
                }
            }
        }
        function N(e, t, n, r, i) {
            var o = S(t[n], r, i);
            o && (e.removeEventListener(n, o, Boolean(i)), delete t[n][o.uidEvent]);
        }
        function D(e, t, n, r) {
            for (var i = t[n] || {}, o = 0, a = Object.entries(i); o < a.length; o++) {
                var s = _slicedToArray(a[o], 2),
                    l = s[0],
                    u = s[1];
                l.includes(r) && N(e, t, n, u.callable, u.delegationSelector);
            }
        }
        function L(e) {
            return (e = e.replace(b, "")), C[e] || e;
        }
        var I = {
            on: function (e, t, n, r) {
                j(e, t, n, r, !1);
            },
            one: function (e, t, n, r) {
                j(e, t, n, r, !0);
            },
            off: function (e, t, n, r) {
                if ("string" == typeof t && e) {
                    var i = _slicedToArray(O(t, n, r), 3),
                        o = i[0],
                        a = i[1],
                        s = i[2],
                        l = s !== t,
                        u = E(e),
                        c = u[s] || {},
                        f = t.startsWith(".");
                    if (void 0 === a) {
                        if (f)
                            for (var d = 0, h = Object.keys(u); d < h.length; d++) {
                                D(e, u, h[d], t.slice(1));
                            }
                        for (var p = 0, g = Object.entries(c); p < g.length; p++) {
                            var v = _slicedToArray(g[p], 2),
                                m = v[0],
                                y = v[1],
                                _ = m.replace(w, "");
                            (l && !t.includes(_)) || N(e, u, s, y.callable, y.delegationSelector);
                        }
                    } else {
                        if (!Object.keys(c).length) return;
                        N(e, u, s, a, o ? n : null);
                    }
                }
            },
            trigger: function (e, t, n) {
                if ("string" != typeof t || !e) return null;
                var r = d(),
                    i = null,
                    o = !0,
                    a = !0,
                    s = !1;
                t !== L(t) && r && ((i = r.Event(t, n)), r(e).trigger(i), (o = !i.isPropagationStopped()), (a = !i.isImmediatePropagationStopped()), (s = i.isDefaultPrevented()));
                var l = new Event(t, { bubbles: o, cancelable: !0 });
                return (l = P(l, n)), s && l.preventDefault(), a && e.dispatchEvent(l), l.defaultPrevented && i && i.preventDefault(), l;
            },
        };
        function P(e) {
            for (
                var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                    n = function () {
                        var t = _slicedToArray(i[r], 2),
                            n = t[0],
                            o = t[1];
                        try {
                            e[n] = o;
                        } catch (t) {
                            Object.defineProperty(e, n, {
                                configurable: !0,
                                get: function () {
                                    return o;
                                },
                            });
                        }
                    },
                    r = 0,
                    i = Object.entries(t);
                r < i.length;
                r++
            )
                n();
            return e;
        }
        var H = new Map(),
            M = function (e, t, n) {
                H.has(e) || H.set(e, new Map());
                var r = H.get(e);
                r.has(t) || 0 === r.size ? r.set(t, n) : console.error("Bootstrap doesn't allow more than one instance per element. Bound instance: ".concat(Array.from(r.keys())[0], "."));
            },
            q = function (e, t) {
                return (H.has(e) && H.get(e).get(t)) || null;
            },
            F = function (e, t) {
                if (H.has(e)) {
                    var n = H.get(e);
                    n.delete(t), 0 === n.size && H.delete(e);
                }
            };
        function R(e) {
            if ("true" === e) return !0;
            if ("false" === e) return !1;
            if (e === Number(e).toString()) return Number(e);
            if ("" === e || "null" === e) return null;
            if ("string" != typeof e) return e;
            try {
                return JSON.parse(decodeURIComponent(e));
            } catch (t) {
                return e;
            }
        }
        function W(e) {
            return e.replace(/[A-Z]/g, function (e) {
                return "-".concat(e.toLowerCase());
            });
        }
        var B = function (e, t, n) {
                e.setAttribute("data-bs-".concat(W(t)), n);
            },
            z = function (e, t) {
                e.removeAttribute("data-bs-".concat(W(t)));
            },
            $ = function (e) {
                if (!e) return {};
                var t,
                    n = {},
                    r = Object.keys(e.dataset).filter(function (e) {
                        return e.startsWith("bs") && !e.startsWith("bsConfig");
                    }),
                    i = _createForOfIteratorHelper(r);
                try {
                    for (i.s(); !(t = i.n()).done; ) {
                        var o = t.value,
                            a = o.replace(/^bs/, "");
                        n[(a = a.charAt(0).toLowerCase() + a.slice(1, a.length))] = R(e.dataset[o]);
                    }
                } catch (e) {
                    i.e(e);
                } finally {
                    i.f();
                }
                return n;
            },
            X = function (e, t) {
                return R(e.getAttribute("data-bs-".concat(W(t))));
            },
            V = (function () {
                function e() {
                    _classCallCheck(this, e);
                }
                return (
                    _createClass(
                        e,
                        [
                            {
                                key: "_getConfig",
                                value: function (e) {
                                    return (e = this._mergeConfigObj(e)), (e = this._configAfterMerge(e)), this._typeCheckConfig(e), e;
                                },
                            },
                            {
                                key: "_configAfterMerge",
                                value: function (e) {
                                    return e;
                                },
                            },
                            {
                                key: "_mergeConfigObj",
                                value: function (e, t) {
                                    var n = o(t) ? X(t, "config") : {};
                                    return _objectSpread(_objectSpread(_objectSpread(_objectSpread({}, this.constructor.Default), "object" == _typeof(n) ? n : {}), o(t) ? $(t) : {}), "object" == _typeof(e) ? e : {});
                                },
                            },
                            {
                                key: "_typeCheckConfig",
                                value: function (e) {
                                    for (var t, n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : this.constructor.DefaultType, r = 0, i = Object.entries(n); r < i.length; r++) {
                                        var a = _slicedToArray(i[r], 2),
                                            s = a[0],
                                            l = a[1],
                                            u = e[s],
                                            c = o(u)
                                                ? "element"
                                                : null == (t = u)
                                                ? "".concat(t)
                                                : Object.prototype.toString
                                                      .call(t)
                                                      .match(/\s([a-z]+)/i)[1]
                                                      .toLowerCase();
                                        if (!new RegExp(l).test(c)) throw new TypeError("".concat(this.constructor.NAME.toUpperCase(), ': Option "').concat(s, '" provided type "').concat(c, '" but expected type "').concat(l, '".'));
                                    }
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return {};
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return {};
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    throw new Error('You have to implement the static method "NAME", for each component!');
                                },
                            },
                        ]
                    ),
                    e
                );
            })(),
            U = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return _classCallCheck(this, n), (i = t.call(this)), (e = a(e)) && ((i._element = e), (i._config = i._getConfig(r)), M(i._element, i.constructor.DATA_KEY, _assertThisInitialized(i))), i;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "dispose",
                                value: function () {
                                    F(this._element, this.constructor.DATA_KEY), I.off(this._element, this.constructor.EVENT_KEY);
                                    var e,
                                        t = _createForOfIteratorHelper(Object.getOwnPropertyNames(this));
                                    try {
                                        for (t.s(); !(e = t.n()).done; ) {
                                            this[e.value] = null;
                                        }
                                    } catch (e) {
                                        t.e(e);
                                    } finally {
                                        t.f();
                                    }
                                },
                            },
                            {
                                key: "_queueCallback",
                                value: function (e, t) {
                                    m(e, t, !(arguments.length > 2 && void 0 !== arguments[2]) || arguments[2]);
                                },
                            },
                            {
                                key: "_getConfig",
                                value: function (e) {
                                    return (e = this._mergeConfigObj(e, this._element)), (e = this._configAfterMerge(e)), this._typeCheckConfig(e), e;
                                },
                            },
                        ],
                        [
                            {
                                key: "getInstance",
                                value: function (e) {
                                    return q(a(e), this.DATA_KEY);
                                },
                            },
                            {
                                key: "getOrCreateInstance",
                                value: function (e) {
                                    var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                                    return this.getInstance(e) || new this(e, "object" == _typeof(t) ? t : null);
                                },
                            },
                            {
                                key: "VERSION",
                                get: function () {
                                    return "5.3.0-alpha1";
                                },
                            },
                            {
                                key: "DATA_KEY",
                                get: function () {
                                    return "bs.".concat(this.NAME);
                                },
                            },
                            {
                                key: "EVENT_KEY",
                                get: function () {
                                    return ".".concat(this.DATA_KEY);
                                },
                            },
                            {
                                key: "eventName",
                                value: function (e) {
                                    return "".concat(e).concat(this.EVENT_KEY);
                                },
                            },
                        ]
                    ),
                    n
                );
            })(V),
            K = function (e) {
                var t = e.getAttribute("data-bs-target");
                if (!t || "#" === t) {
                    var n = e.getAttribute("href");
                    if (!n || (!n.includes("#") && !n.startsWith("."))) return null;
                    n.includes("#") && !n.startsWith("#") && (n = "#".concat(n.split("#")[1])), (t = n && "#" !== n ? n.trim() : null);
                }
                return r(t);
            },
            Q = {
                find: function (e) {
                    var t,
                        n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : document.documentElement;
                    return (t = []).concat.apply(t, _toConsumableArray(Element.prototype.querySelectorAll.call(n, e)));
                },
                findOne: function (e) {
                    var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : document.documentElement;
                    return Element.prototype.querySelector.call(t, e);
                },
                children: function (e, t) {
                    var n;
                    return (n = []).concat.apply(n, _toConsumableArray(e.children)).filter(function (e) {
                        return e.matches(t);
                    });
                },
                parents: function (e, t) {
                    for (var n = [], r = e.parentNode.closest(t); r; ) n.push(r), (r = r.parentNode.closest(t));
                    return n;
                },
                prev: function (e, t) {
                    for (var n = e.previousElementSibling; n; ) {
                        if (n.matches(t)) return [n];
                        n = n.previousElementSibling;
                    }
                    return [];
                },
                next: function (e, t) {
                    for (var n = e.nextElementSibling; n; ) {
                        if (n.matches(t)) return [n];
                        n = n.nextElementSibling;
                    }
                    return [];
                },
                focusableChildren: function (e) {
                    var t = ["a", "button", "input", "textarea", "select", "details", "[tabindex]", '[contenteditable="true"]']
                        .map(function (e) {
                            return "".concat(e, ':not([tabindex^="-"])');
                        })
                        .join(",");
                    return this.find(t, e).filter(function (e) {
                        return !l(e) && s(e);
                    });
                },
                getSelectorFromElement: function (e) {
                    var t = K(e);
                    return t && Q.findOne(t) ? t : null;
                },
                getElementFromSelector: function (e) {
                    var t = K(e);
                    return t ? Q.findOne(t) : null;
                },
                getMultipleElementsFromSelector: function (e) {
                    var t = K(e);
                    return t ? Q.find(t) : [];
                },
            },
            Y = function (e) {
                var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "hide",
                    n = "click.dismiss".concat(e.EVENT_KEY),
                    r = e.NAME;
                I.on(document, n, '[data-bs-dismiss="'.concat(r, '"]'), function (n) {
                    if ((["A", "AREA"].includes(this.tagName) && n.preventDefault(), !l(this))) {
                        var i = Q.getElementFromSelector(this) || this.closest(".".concat(r));
                        e.getOrCreateInstance(i)[t]();
                    }
                });
            },
            G = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n() {
                    return _classCallCheck(this, n), t.apply(this, arguments);
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "close",
                                value: function () {
                                    var e = this;
                                    if (!I.trigger(this._element, "close.bs.alert").defaultPrevented) {
                                        this._element.classList.remove("show");
                                        var t = this._element.classList.contains("fade");
                                        this._queueCallback(
                                            function () {
                                                return e._destroyElement();
                                            },
                                            this._element,
                                            t
                                        );
                                    }
                                },
                            },
                            {
                                key: "_destroyElement",
                                value: function () {
                                    this._element.remove(), I.trigger(this._element, "closed.bs.alert"), this.dispose();
                                },
                            },
                        ],
                        [
                            {
                                key: "NAME",
                                get: function () {
                                    return "alert";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e] || e.startsWith("_") || "constructor" === e) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e](this);
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        Y(G, "close"), g(G);
        var J = '[data-bs-toggle="button"]',
            Z = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n() {
                    return _classCallCheck(this, n), t.apply(this, arguments);
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "toggle",
                                value: function () {
                                    this._element.setAttribute("aria-pressed", this._element.classList.toggle("active"));
                                },
                            },
                        ],
                        [
                            {
                                key: "NAME",
                                get: function () {
                                    return "button";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this);
                                        "toggle" === e && t[e]();
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(document, "click.bs.button.data-api", J, function (e) {
            e.preventDefault();
            var t = e.target.closest(J);
            Z.getOrCreateInstance(t).toggle();
        }),
            g(Z);
        var ee = { endCallback: null, leftCallback: null, rightCallback: null },
            te = { endCallback: "(function|null)", leftCallback: "(function|null)", rightCallback: "(function|null)" },
            ne = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return _classCallCheck(this, n), ((i = t.call(this))._element = e), e && n.isSupported() && ((i._config = i._getConfig(r)), (i._deltaX = 0), (i._supportPointerEvents = Boolean(window.PointerEvent)), i._initEvents()), i;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "dispose",
                                value: function () {
                                    I.off(this._element, ".bs.swipe");
                                },
                            },
                            {
                                key: "_start",
                                value: function (e) {
                                    this._supportPointerEvents ? this._eventIsPointerPenTouch(e) && (this._deltaX = e.clientX) : (this._deltaX = e.touches[0].clientX);
                                },
                            },
                            {
                                key: "_end",
                                value: function (e) {
                                    this._eventIsPointerPenTouch(e) && (this._deltaX = e.clientX - this._deltaX), this._handleSwipe(), v(this._config.endCallback);
                                },
                            },
                            {
                                key: "_move",
                                value: function (e) {
                                    this._deltaX = e.touches && e.touches.length > 1 ? 0 : e.touches[0].clientX - this._deltaX;
                                },
                            },
                            {
                                key: "_handleSwipe",
                                value: function () {
                                    var e = Math.abs(this._deltaX);
                                    if (!(e <= 40)) {
                                        var t = e / this._deltaX;
                                        (this._deltaX = 0), t && v(t > 0 ? this._config.rightCallback : this._config.leftCallback);
                                    }
                                },
                            },
                            {
                                key: "_initEvents",
                                value: function () {
                                    var e = this;
                                    this._supportPointerEvents
                                        ? (I.on(this._element, "pointerdown.bs.swipe", function (t) {
                                              return e._start(t);
                                          }),
                                          I.on(this._element, "pointerup.bs.swipe", function (t) {
                                              return e._end(t);
                                          }),
                                          this._element.classList.add("pointer-event"))
                                        : (I.on(this._element, "touchstart.bs.swipe", function (t) {
                                              return e._start(t);
                                          }),
                                          I.on(this._element, "touchmove.bs.swipe", function (t) {
                                              return e._move(t);
                                          }),
                                          I.on(this._element, "touchend.bs.swipe", function (t) {
                                              return e._end(t);
                                          }));
                                },
                            },
                            {
                                key: "_eventIsPointerPenTouch",
                                value: function (e) {
                                    return this._supportPointerEvents && ("pen" === e.pointerType || "touch" === e.pointerType);
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return ee;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return te;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "swipe";
                                },
                            },
                            {
                                key: "isSupported",
                                value: function () {
                                    return "ontouchstart" in document.documentElement || navigator.maxTouchPoints > 0;
                                },
                            },
                        ]
                    ),
                    n
                );
            })(V),
            re = "next",
            ie = "prev",
            oe = "left",
            ae = "right",
            se = "slid.bs.carousel",
            le = "carousel",
            ue = "active",
            ce = { ArrowLeft: ae, ArrowRight: oe },
            fe = { interval: 5e3, keyboard: !0, pause: "hover", ride: !1, touch: !0, wrap: !0 },
            de = { interval: "(number|boolean)", keyboard: "boolean", pause: "(string|boolean)", ride: "(boolean|string)", touch: "boolean", wrap: "boolean" },
            he = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return (
                        _classCallCheck(this, n),
                        ((i = t.call(this, e, r))._interval = null),
                        (i._activeElement = null),
                        (i._isSliding = !1),
                        (i.touchTimeout = null),
                        (i._swipeHelper = null),
                        (i._indicatorsElement = Q.findOne(".carousel-indicators", i._element)),
                        i._addEventListeners(),
                        i._config.ride === le && i.cycle(),
                        i
                    );
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "next",
                                value: function () {
                                    this._slide(re);
                                },
                            },
                            {
                                key: "nextWhenVisible",
                                value: function () {
                                    !document.hidden && s(this._element) && this.next();
                                },
                            },
                            {
                                key: "prev",
                                value: function () {
                                    this._slide(ie);
                                },
                            },
                            {
                                key: "pause",
                                value: function () {
                                    this._isSliding && i(this._element), this._clearInterval();
                                },
                            },
                            {
                                key: "cycle",
                                value: function () {
                                    var e = this;
                                    this._clearInterval(),
                                        this._updateInterval(),
                                        (this._interval = setInterval(function () {
                                            return e.nextWhenVisible();
                                        }, this._config.interval));
                                },
                            },
                            {
                                key: "_maybeEnableCycle",
                                value: function () {
                                    var e = this;
                                    this._config.ride &&
                                        (this._isSliding
                                            ? I.one(this._element, se, function () {
                                                  return e.cycle();
                                              })
                                            : this.cycle());
                                },
                            },
                            {
                                key: "to",
                                value: function (e) {
                                    var t = this,
                                        n = this._getItems();
                                    if (!(e > n.length - 1 || e < 0))
                                        if (this._isSliding)
                                            I.one(this._element, se, function () {
                                                return t.to(e);
                                            });
                                        else {
                                            var r = this._getItemIndex(this._getActive());
                                            if (r !== e) {
                                                var i = e > r ? re : ie;
                                                this._slide(i, n[e]);
                                            }
                                        }
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    this._swipeHelper && this._swipeHelper.dispose(), _get(_getPrototypeOf(n.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "_configAfterMerge",
                                value: function (e) {
                                    return (e.defaultInterval = e.interval), e;
                                },
                            },
                            {
                                key: "_addEventListeners",
                                value: function () {
                                    var e = this;
                                    this._config.keyboard &&
                                        I.on(this._element, "keydown.bs.carousel", function (t) {
                                            return e._keydown(t);
                                        }),
                                        "hover" === this._config.pause &&
                                            (I.on(this._element, "mouseenter.bs.carousel", function () {
                                                return e.pause();
                                            }),
                                            I.on(this._element, "mouseleave.bs.carousel", function () {
                                                return e._maybeEnableCycle();
                                            })),
                                        this._config.touch && ne.isSupported() && this._addTouchEventListeners();
                                },
                            },
                            {
                                key: "_addTouchEventListeners",
                                value: function () {
                                    var e,
                                        t = this,
                                        n = _createForOfIteratorHelper(Q.find(".carousel-item img", this._element));
                                    try {
                                        for (n.s(); !(e = n.n()).done; ) {
                                            var r = e.value;
                                            I.on(r, "dragstart.bs.carousel", function (e) {
                                                return e.preventDefault();
                                            });
                                        }
                                    } catch (e) {
                                        n.e(e);
                                    } finally {
                                        n.f();
                                    }
                                    var i = {
                                        leftCallback: function () {
                                            return t._slide(t._directionToOrder(oe));
                                        },
                                        rightCallback: function () {
                                            return t._slide(t._directionToOrder(ae));
                                        },
                                        endCallback: function () {
                                            "hover" === t._config.pause &&
                                                (t.pause(),
                                                t.touchTimeout && clearTimeout(t.touchTimeout),
                                                (t.touchTimeout = setTimeout(function () {
                                                    return t._maybeEnableCycle();
                                                }, 500 + t._config.interval)));
                                        },
                                    };
                                    this._swipeHelper = new ne(this._element, i);
                                },
                            },
                            {
                                key: "_keydown",
                                value: function (e) {
                                    if (!/input|textarea/i.test(e.target.tagName)) {
                                        var t = ce[e.key];
                                        t && (e.preventDefault(), this._slide(this._directionToOrder(t)));
                                    }
                                },
                            },
                            {
                                key: "_getItemIndex",
                                value: function (e) {
                                    return this._getItems().indexOf(e);
                                },
                            },
                            {
                                key: "_setActiveIndicatorElement",
                                value: function (e) {
                                    if (this._indicatorsElement) {
                                        var t = Q.findOne(".active", this._indicatorsElement);
                                        t.classList.remove(ue), t.removeAttribute("aria-current");
                                        var n = Q.findOne('[data-bs-slide-to="'.concat(e, '"]'), this._indicatorsElement);
                                        n && (n.classList.add(ue), n.setAttribute("aria-current", "true"));
                                    }
                                },
                            },
                            {
                                key: "_updateInterval",
                                value: function () {
                                    var e = this._activeElement || this._getActive();
                                    if (e) {
                                        var t = Number.parseInt(e.getAttribute("data-bs-interval"), 10);
                                        this._config.interval = t || this._config.defaultInterval;
                                    }
                                },
                            },
                            {
                                key: "_slide",
                                value: function (e) {
                                    var t = this,
                                        n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : null;
                                    if (!this._isSliding) {
                                        var r = this._getActive(),
                                            i = e === re,
                                            o = n || y(this._getItems(), r, i, this._config.wrap);
                                        if (o !== r) {
                                            var a = this._getItemIndex(o),
                                                s = function (n) {
                                                    return I.trigger(t._element, n, { relatedTarget: o, direction: t._orderToDirection(e), from: t._getItemIndex(r), to: a });
                                                };
                                            if (!s("slide.bs.carousel").defaultPrevented && r && o) {
                                                var l = Boolean(this._interval);
                                                this.pause(), (this._isSliding = !0), this._setActiveIndicatorElement(a), (this._activeElement = o);
                                                var u = i ? "carousel-item-start" : "carousel-item-end",
                                                    c = i ? "carousel-item-next" : "carousel-item-prev";
                                                o.classList.add(c),
                                                    f(o),
                                                    r.classList.add(u),
                                                    o.classList.add(u),
                                                    this._queueCallback(
                                                        function () {
                                                            o.classList.remove(u, c), o.classList.add(ue), r.classList.remove(ue, c, u), (t._isSliding = !1), s(se);
                                                        },
                                                        r,
                                                        this._isAnimated()
                                                    ),
                                                    l && this.cycle();
                                            }
                                        }
                                    }
                                },
                            },
                            {
                                key: "_isAnimated",
                                value: function () {
                                    return this._element.classList.contains("slide");
                                },
                            },
                            {
                                key: "_getActive",
                                value: function () {
                                    return Q.findOne(".active.carousel-item", this._element);
                                },
                            },
                            {
                                key: "_getItems",
                                value: function () {
                                    return Q.find(".carousel-item", this._element);
                                },
                            },
                            {
                                key: "_clearInterval",
                                value: function () {
                                    this._interval && (clearInterval(this._interval), (this._interval = null));
                                },
                            },
                            {
                                key: "_directionToOrder",
                                value: function (e) {
                                    return p() ? (e === oe ? ie : re) : e === oe ? re : ie;
                                },
                            },
                            {
                                key: "_orderToDirection",
                                value: function (e) {
                                    return p() ? (e === ie ? oe : ae) : e === ie ? ae : oe;
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return fe;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return de;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "carousel";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this, e);
                                        if ("number" != typeof e) {
                                            if ("string" == typeof e) {
                                                if (void 0 === t[e] || e.startsWith("_") || "constructor" === e) throw new TypeError('No method named "'.concat(e, '"'));
                                                t[e]();
                                            }
                                        } else t.to(e);
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(document, "click.bs.carousel.data-api", "[data-bs-slide], [data-bs-slide-to]", function (e) {
            var t = Q.getElementFromSelector(this);
            if (t && t.classList.contains(le)) {
                e.preventDefault();
                var n = he.getOrCreateInstance(t),
                    r = this.getAttribute("data-bs-slide-to");
                return r ? (n.to(r), void n._maybeEnableCycle()) : "next" === X(this, "slide") ? (n.next(), void n._maybeEnableCycle()) : (n.prev(), void n._maybeEnableCycle());
            }
        }),
            I.on(window, "load.bs.carousel.data-api", function () {
                var e,
                    t = _createForOfIteratorHelper(Q.find('[data-bs-ride="carousel"]'));
                try {
                    for (t.s(); !(e = t.n()).done; ) {
                        var n = e.value;
                        he.getOrCreateInstance(n);
                    }
                } catch (e) {
                    t.e(e);
                } finally {
                    t.f();
                }
            }),
            g(he);
        var pe = "show",
            ge = "collapse",
            ve = "collapsing",
            me = '[data-bs-toggle="collapse"]',
            ye = { parent: null, toggle: !0 },
            _e = { parent: "(null|element)", toggle: "boolean" },
            be = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    _classCallCheck(this, n), ((i = t.call(this, e, r))._isTransitioning = !1), (i._triggerArray = []);
                    var o,
                        a = _createForOfIteratorHelper(Q.find(me));
                    try {
                        for (a.s(); !(o = a.n()).done; ) {
                            var s = o.value,
                                l = Q.getSelectorFromElement(s),
                                u = Q.find(l).filter(function (e) {
                                    return e === i._element;
                                });
                            null !== l && u.length && i._triggerArray.push(s);
                        }
                    } catch (e) {
                        a.e(e);
                    } finally {
                        a.f();
                    }
                    return i._initializeChildren(), i._config.parent || i._addAriaAndCollapsedClass(i._triggerArray, i._isShown()), i._config.toggle && i.toggle(), i;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "toggle",
                                value: function () {
                                    this._isShown() ? this.hide() : this.show();
                                },
                            },
                            {
                                key: "show",
                                value: function () {
                                    var e = this;
                                    if (!this._isTransitioning && !this._isShown()) {
                                        var t = [];
                                        if (
                                            !(this._config.parent &&
                                                (t = this._getFirstLevelChildren(".collapse.show, .collapse.collapsing")
                                                    .filter(function (t) {
                                                        return t !== e._element;
                                                    })
                                                    .map(function (e) {
                                                        return n.getOrCreateInstance(e, { toggle: !1 });
                                                    })),
                                            (t.length && t[0]._isTransitioning) || I.trigger(this._element, "show.bs.collapse").defaultPrevented)
                                        ) {
                                            var r,
                                                i = _createForOfIteratorHelper(t);
                                            try {
                                                for (i.s(); !(r = i.n()).done; ) {
                                                    r.value.hide();
                                                }
                                            } catch (e) {
                                                i.e(e);
                                            } finally {
                                                i.f();
                                            }
                                            var o = this._getDimension();
                                            this._element.classList.remove(ge), this._element.classList.add(ve), (this._element.style[o] = 0), this._addAriaAndCollapsedClass(this._triggerArray, !0), (this._isTransitioning = !0);
                                            var a = "scroll".concat(o[0].toUpperCase() + o.slice(1));
                                            this._queueCallback(
                                                function () {
                                                    (e._isTransitioning = !1), e._element.classList.remove(ve), e._element.classList.add(ge, pe), (e._element.style[o] = ""), I.trigger(e._element, "shown.bs.collapse");
                                                },
                                                this._element,
                                                !0
                                            ),
                                                (this._element.style[o] = "".concat(this._element[a], "px"));
                                        }
                                    }
                                },
                            },
                            {
                                key: "hide",
                                value: function () {
                                    var e = this;
                                    if (!this._isTransitioning && this._isShown() && !I.trigger(this._element, "hide.bs.collapse").defaultPrevented) {
                                        var t = this._getDimension();
                                        (this._element.style[t] = "".concat(this._element.getBoundingClientRect()[t], "px")), f(this._element), this._element.classList.add(ve), this._element.classList.remove(ge, pe);
                                        var n,
                                            r = _createForOfIteratorHelper(this._triggerArray);
                                        try {
                                            for (r.s(); !(n = r.n()).done; ) {
                                                var i = n.value,
                                                    o = Q.getElementFromSelector(i);
                                                o && !this._isShown(o) && this._addAriaAndCollapsedClass([i], !1);
                                            }
                                        } catch (e) {
                                            r.e(e);
                                        } finally {
                                            r.f();
                                        }
                                        (this._isTransitioning = !0),
                                            (this._element.style[t] = ""),
                                            this._queueCallback(
                                                function () {
                                                    (e._isTransitioning = !1), e._element.classList.remove(ve), e._element.classList.add(ge), I.trigger(e._element, "hidden.bs.collapse");
                                                },
                                                this._element,
                                                !0
                                            );
                                    }
                                },
                            },
                            {
                                key: "_isShown",
                                value: function () {
                                    return (arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : this._element).classList.contains(pe);
                                },
                            },
                            {
                                key: "_configAfterMerge",
                                value: function (e) {
                                    return (e.toggle = Boolean(e.toggle)), (e.parent = a(e.parent)), e;
                                },
                            },
                            {
                                key: "_getDimension",
                                value: function () {
                                    return this._element.classList.contains("collapse-horizontal") ? "width" : "height";
                                },
                            },
                            {
                                key: "_initializeChildren",
                                value: function () {
                                    if (this._config.parent) {
                                        var e,
                                            t = _createForOfIteratorHelper(this._getFirstLevelChildren(me));
                                        try {
                                            for (t.s(); !(e = t.n()).done; ) {
                                                var n = e.value,
                                                    r = Q.getElementFromSelector(n);
                                                r && this._addAriaAndCollapsedClass([n], this._isShown(r));
                                            }
                                        } catch (e) {
                                            t.e(e);
                                        } finally {
                                            t.f();
                                        }
                                    }
                                },
                            },
                            {
                                key: "_getFirstLevelChildren",
                                value: function (e) {
                                    var t = Q.find(":scope .collapse .collapse", this._config.parent);
                                    return Q.find(e, this._config.parent).filter(function (e) {
                                        return !t.includes(e);
                                    });
                                },
                            },
                            {
                                key: "_addAriaAndCollapsedClass",
                                value: function (e, t) {
                                    if (e.length) {
                                        var n,
                                            r = _createForOfIteratorHelper(e);
                                        try {
                                            for (r.s(); !(n = r.n()).done; ) {
                                                var i = n.value;
                                                i.classList.toggle("collapsed", !t), i.setAttribute("aria-expanded", t);
                                            }
                                        } catch (e) {
                                            r.e(e);
                                        } finally {
                                            r.f();
                                        }
                                    }
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return ye;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return _e;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "collapse";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    var t = {};
                                    return (
                                        "string" == typeof e && /show|hide/.test(e) && (t.toggle = !1),
                                        this.each(function () {
                                            var r = n.getOrCreateInstance(this, t);
                                            if ("string" == typeof e) {
                                                if (void 0 === r[e]) throw new TypeError('No method named "'.concat(e, '"'));
                                                r[e]();
                                            }
                                        })
                                    );
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(document, "click.bs.collapse.data-api", me, function (e) {
            ("A" === e.target.tagName || (e.delegateTarget && "A" === e.delegateTarget.tagName)) && e.preventDefault();
            var t,
                n = _createForOfIteratorHelper(Q.getMultipleElementsFromSelector(this));
            try {
                for (n.s(); !(t = n.n()).done; ) {
                    var r = t.value;
                    be.getOrCreateInstance(r, { toggle: !1 }).toggle();
                }
            } catch (e) {
                n.e(e);
            } finally {
                n.f();
            }
        }),
            g(be);
        var we = "dropdown",
            ke = "ArrowUp",
            xe = "ArrowDown",
            Ce = "click.bs.dropdown.data-api",
            Te = "keydown.bs.dropdown.data-api",
            Ae = "show",
            Ee = '[data-bs-toggle="dropdown"]:not(.disabled):not(:disabled)',
            Se = "".concat(Ee, ".show"),
            Oe = ".dropdown-menu",
            je = p() ? "top-end" : "top-start",
            Ne = p() ? "top-start" : "top-end",
            De = p() ? "bottom-end" : "bottom-start",
            Le = p() ? "bottom-start" : "bottom-end",
            Ie = p() ? "left-start" : "right-start",
            Pe = p() ? "right-start" : "left-start",
            He = { autoClose: !0, boundary: "clippingParents", display: "dynamic", offset: [0, 2], popperConfig: null, reference: "toggle" },
            Me = { autoClose: "(boolean|string)", boundary: "(string|element)", display: "string", offset: "(array|string|function)", popperConfig: "(null|object|function)", reference: "(string|element|object)" },
            qe = (function (e) {
                _inherits(r, e);
                var n = _createSuper(r);
                function r(e, t) {
                    var i;
                    return (
                        _classCallCheck(this, r),
                        ((i = n.call(this, e, t))._popper = null),
                        (i._parent = i._element.parentNode),
                        (i._menu = Q.next(i._element, Oe)[0] || Q.prev(i._element, Oe)[0] || Q.findOne(Oe, i._parent)),
                        (i._inNavbar = i._detectNavbar()),
                        i
                    );
                }
                return (
                    _createClass(
                        r,
                        [
                            {
                                key: "toggle",
                                value: function () {
                                    return this._isShown() ? this.hide() : this.show();
                                },
                            },
                            {
                                key: "show",
                                value: function () {
                                    if (!l(this._element) && !this._isShown()) {
                                        var e = { relatedTarget: this._element };
                                        if (!I.trigger(this._element, "show.bs.dropdown", e).defaultPrevented) {
                                            if ((this._createPopper(), "ontouchstart" in document.documentElement && !this._parent.closest(".navbar-nav"))) {
                                                var t,
                                                    n,
                                                    r = _createForOfIteratorHelper((t = []).concat.apply(t, _toConsumableArray(document.body.children)));
                                                try {
                                                    for (r.s(); !(n = r.n()).done; ) {
                                                        var i = n.value;
                                                        I.on(i, "mouseover", c);
                                                    }
                                                } catch (e) {
                                                    r.e(e);
                                                } finally {
                                                    r.f();
                                                }
                                            }
                                            this._element.focus(), this._element.setAttribute("aria-expanded", !0), this._menu.classList.add(Ae), this._element.classList.add(Ae), I.trigger(this._element, "shown.bs.dropdown", e);
                                        }
                                    }
                                },
                            },
                            {
                                key: "hide",
                                value: function () {
                                    if (!l(this._element) && this._isShown()) {
                                        var e = { relatedTarget: this._element };
                                        this._completeHide(e);
                                    }
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    this._popper && this._popper.destroy(), _get(_getPrototypeOf(r.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "update",
                                value: function () {
                                    (this._inNavbar = this._detectNavbar()), this._popper && this._popper.update();
                                },
                            },
                            {
                                key: "_completeHide",
                                value: function (e) {
                                    if (!I.trigger(this._element, "hide.bs.dropdown", e).defaultPrevented) {
                                        if ("ontouchstart" in document.documentElement) {
                                            var t,
                                                n,
                                                r = _createForOfIteratorHelper((t = []).concat.apply(t, _toConsumableArray(document.body.children)));
                                            try {
                                                for (r.s(); !(n = r.n()).done; ) {
                                                    var i = n.value;
                                                    I.off(i, "mouseover", c);
                                                }
                                            } catch (e) {
                                                r.e(e);
                                            } finally {
                                                r.f();
                                            }
                                        }
                                        this._popper && this._popper.destroy(),
                                            this._menu.classList.remove(Ae),
                                            this._element.classList.remove(Ae),
                                            this._element.setAttribute("aria-expanded", "false"),
                                            z(this._menu, "popper"),
                                            I.trigger(this._element, "hidden.bs.dropdown", e);
                                    }
                                },
                            },
                            {
                                key: "_getConfig",
                                value: function (e) {
                                    if ("object" == _typeof((e = _get(_getPrototypeOf(r.prototype), "_getConfig", this).call(this, e)).reference) && !o(e.reference) && "function" != typeof e.reference.getBoundingClientRect)
                                        throw new TypeError("".concat(we.toUpperCase(), ': Option "reference" provided type "object" without a required "getBoundingClientRect" method.'));
                                    return e;
                                },
                            },
                            {
                                key: "_createPopper",
                                value: function () {
                                    if (void 0 === t) throw new TypeError("Bootstrap's dropdowns require Popper (https://popper.js.org)");
                                    var e = this._element;
                                    "parent" === this._config.reference ? (e = this._parent) : o(this._config.reference) ? (e = a(this._config.reference)) : "object" == _typeof(this._config.reference) && (e = this._config.reference);
                                    var n = this._getPopperConfig();
                                    this._popper = t.createPopper(e, this._menu, n);
                                },
                            },
                            {
                                key: "_isShown",
                                value: function () {
                                    return this._menu.classList.contains(Ae);
                                },
                            },
                            {
                                key: "_getPlacement",
                                value: function () {
                                    var e = this._parent;
                                    if (e.classList.contains("dropend")) return Ie;
                                    if (e.classList.contains("dropstart")) return Pe;
                                    if (e.classList.contains("dropup-center")) return "top";
                                    if (e.classList.contains("dropdown-center")) return "bottom";
                                    var t = "end" === getComputedStyle(this._menu).getPropertyValue("--bs-position").trim();
                                    return e.classList.contains("dropup") ? (t ? Ne : je) : t ? Le : De;
                                },
                            },
                            {
                                key: "_detectNavbar",
                                value: function () {
                                    return null !== this._element.closest(".navbar");
                                },
                            },
                            {
                                key: "_getOffset",
                                value: function () {
                                    var e = this,
                                        t = this._config.offset;
                                    return "string" == typeof t
                                        ? t.split(",").map(function (e) {
                                              return Number.parseInt(e, 10);
                                          })
                                        : "function" == typeof t
                                        ? function (n) {
                                              return t(n, e._element);
                                          }
                                        : t;
                                },
                            },
                            {
                                key: "_getPopperConfig",
                                value: function () {
                                    var e = {
                                        placement: this._getPlacement(),
                                        modifiers: [
                                            { name: "preventOverflow", options: { boundary: this._config.boundary } },
                                            { name: "offset", options: { offset: this._getOffset() } },
                                        ],
                                    };
                                    return (
                                        (this._inNavbar || "static" === this._config.display) && (B(this._menu, "popper", "static"), (e.modifiers = [{ name: "applyStyles", enabled: !1 }])),
                                        _objectSpread(_objectSpread({}, e), v(this._config.popperConfig, [e]))
                                    );
                                },
                            },
                            {
                                key: "_selectMenuItem",
                                value: function (e) {
                                    var t = e.key,
                                        n = e.target,
                                        r = Q.find(".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)", this._menu).filter(function (e) {
                                            return s(e);
                                        });
                                    r.length && y(r, n, t === xe, !r.includes(n)).focus();
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return He;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return Me;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return we;
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = r.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e]) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e]();
                                        }
                                    });
                                },
                            },
                            {
                                key: "clearMenus",
                                value: function (e) {
                                    if (2 !== e.button && ("keyup" !== e.type || "Tab" === e.key)) {
                                        var t,
                                            n = _createForOfIteratorHelper(Q.find(Se));
                                        try {
                                            for (n.s(); !(t = n.n()).done; ) {
                                                var i = t.value,
                                                    o = r.getInstance(i);
                                                if (o && !1 !== o._config.autoClose) {
                                                    var a = e.composedPath(),
                                                        s = a.includes(o._menu);
                                                    if (
                                                        !(
                                                            a.includes(o._element) ||
                                                            ("inside" === o._config.autoClose && !s) ||
                                                            ("outside" === o._config.autoClose && s) ||
                                                            (o._menu.contains(e.target) && (("keyup" === e.type && "Tab" === e.key) || /input|select|option|textarea|form/i.test(e.target.tagName)))
                                                        )
                                                    ) {
                                                        var l = { relatedTarget: o._element };
                                                        "click" === e.type && (l.clickEvent = e), o._completeHide(l);
                                                    }
                                                }
                                            }
                                        } catch (e) {
                                            n.e(e);
                                        } finally {
                                            n.f();
                                        }
                                    }
                                },
                            },
                            {
                                key: "dataApiKeydownHandler",
                                value: function (e) {
                                    var t = /input|textarea/i.test(e.target.tagName),
                                        n = "Escape" === e.key,
                                        i = [ke, xe].includes(e.key);
                                    if ((i || n) && (!t || n)) {
                                        e.preventDefault();
                                        var o = this.matches(Ee) ? this : Q.prev(this, Ee)[0] || Q.next(this, Ee)[0] || Q.findOne(Ee, e.delegateTarget.parentNode),
                                            a = r.getOrCreateInstance(o);
                                        if (i) return e.stopPropagation(), a.show(), void a._selectMenuItem(e);
                                        a._isShown() && (e.stopPropagation(), a.hide(), o.focus());
                                    }
                                },
                            },
                        ]
                    ),
                    r
                );
            })(U);
        I.on(document, Te, Ee, qe.dataApiKeydownHandler),
            I.on(document, Te, Oe, qe.dataApiKeydownHandler),
            I.on(document, Ce, qe.clearMenus),
            I.on(document, "keyup.bs.dropdown.data-api", qe.clearMenus),
            I.on(document, Ce, Ee, function (e) {
                e.preventDefault(), qe.getOrCreateInstance(this).toggle();
            }),
            g(qe);
        var Fe = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",
            Re = ".sticky-top",
            We = "padding-right",
            Be = "margin-right",
            ze = (function () {
                function e() {
                    _classCallCheck(this, e), (this._element = document.body);
                }
                return (
                    _createClass(e, [
                        {
                            key: "getWidth",
                            value: function () {
                                var e = document.documentElement.clientWidth;
                                return Math.abs(window.innerWidth - e);
                            },
                        },
                        {
                            key: "hide",
                            value: function () {
                                var e = this.getWidth();
                                this._disableOverFlow(),
                                    this._setElementAttributes(this._element, We, function (t) {
                                        return t + e;
                                    }),
                                    this._setElementAttributes(Fe, We, function (t) {
                                        return t + e;
                                    }),
                                    this._setElementAttributes(Re, Be, function (t) {
                                        return t - e;
                                    });
                            },
                        },
                        {
                            key: "reset",
                            value: function () {
                                this._resetElementAttributes(this._element, "overflow"), this._resetElementAttributes(this._element, We), this._resetElementAttributes(Fe, We), this._resetElementAttributes(Re, Be);
                            },
                        },
                        {
                            key: "isOverflowing",
                            value: function () {
                                return this.getWidth() > 0;
                            },
                        },
                        {
                            key: "_disableOverFlow",
                            value: function () {
                                this._saveInitialAttribute(this._element, "overflow"), (this._element.style.overflow = "hidden");
                            },
                        },
                        {
                            key: "_setElementAttributes",
                            value: function (e, t, n) {
                                var r = this,
                                    i = this.getWidth();
                                this._applyManipulationCallback(e, function (e) {
                                    if (!(e !== r._element && window.innerWidth > e.clientWidth + i)) {
                                        r._saveInitialAttribute(e, t);
                                        var o = window.getComputedStyle(e).getPropertyValue(t);
                                        e.style.setProperty(t, "".concat(n(Number.parseFloat(o)), "px"));
                                    }
                                });
                            },
                        },
                        {
                            key: "_saveInitialAttribute",
                            value: function (e, t) {
                                var n = e.style.getPropertyValue(t);
                                n && B(e, t, n);
                            },
                        },
                        {
                            key: "_resetElementAttributes",
                            value: function (e, t) {
                                this._applyManipulationCallback(e, function (e) {
                                    var n = X(e, t);
                                    null !== n ? (z(e, t), e.style.setProperty(t, n)) : e.style.removeProperty(t);
                                });
                            },
                        },
                        {
                            key: "_applyManipulationCallback",
                            value: function (e, t) {
                                if (o(e)) t(e);
                                else {
                                    var n,
                                        r = _createForOfIteratorHelper(Q.find(e, this._element));
                                    try {
                                        for (r.s(); !(n = r.n()).done; ) {
                                            t(n.value);
                                        }
                                    } catch (e) {
                                        r.e(e);
                                    } finally {
                                        r.f();
                                    }
                                }
                            },
                        },
                    ]),
                    e
                );
            })(),
            $e = "show",
            Xe = "mousedown.bs.backdrop",
            Ve = { className: "modal-backdrop", clickCallback: null, isAnimated: !1, isVisible: !0, rootElement: "body" },
            Ue = { className: "string", clickCallback: "(function|null)", isAnimated: "boolean", isVisible: "boolean", rootElement: "(element|string)" },
            Ke = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e) {
                    var r;
                    return _classCallCheck(this, n), ((r = t.call(this))._config = r._getConfig(e)), (r._isAppended = !1), (r._element = null), r;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "show",
                                value: function (e) {
                                    if (this._config.isVisible) {
                                        this._append();
                                        var t = this._getElement();
                                        this._config.isAnimated && f(t),
                                            t.classList.add($e),
                                            this._emulateAnimation(function () {
                                                v(e);
                                            });
                                    } else v(e);
                                },
                            },
                            {
                                key: "hide",
                                value: function (e) {
                                    var t = this;
                                    this._config.isVisible
                                        ? (this._getElement().classList.remove($e),
                                          this._emulateAnimation(function () {
                                              t.dispose(), v(e);
                                          }))
                                        : v(e);
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    this._isAppended && (I.off(this._element, Xe), this._element.remove(), (this._isAppended = !1));
                                },
                            },
                            {
                                key: "_getElement",
                                value: function () {
                                    if (!this._element) {
                                        var e = document.createElement("div");
                                        (e.className = this._config.className), this._config.isAnimated && e.classList.add("fade"), (this._element = e);
                                    }
                                    return this._element;
                                },
                            },
                            {
                                key: "_configAfterMerge",
                                value: function (e) {
                                    return (e.rootElement = a(e.rootElement)), e;
                                },
                            },
                            {
                                key: "_append",
                                value: function () {
                                    var e = this;
                                    if (!this._isAppended) {
                                        var t = this._getElement();
                                        this._config.rootElement.append(t),
                                            I.on(t, Xe, function () {
                                                v(e._config.clickCallback);
                                            }),
                                            (this._isAppended = !0);
                                    }
                                },
                            },
                            {
                                key: "_emulateAnimation",
                                value: function (e) {
                                    m(e, this._getElement(), this._config.isAnimated);
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return Ve;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return Ue;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "backdrop";
                                },
                            },
                        ]
                    ),
                    n
                );
            })(V),
            Qe = ".bs.focustrap",
            Ye = "backward",
            Ge = { autofocus: !0, trapElement: null },
            Je = { autofocus: "boolean", trapElement: "element" },
            Ze = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e) {
                    var r;
                    return _classCallCheck(this, n), ((r = t.call(this))._config = r._getConfig(e)), (r._isActive = !1), (r._lastTabNavDirection = null), r;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "activate",
                                value: function () {
                                    var e = this;
                                    this._isActive ||
                                        (this._config.autofocus && this._config.trapElement.focus(),
                                        I.off(document, Qe),
                                        I.on(document, "focusin.bs.focustrap", function (t) {
                                            return e._handleFocusin(t);
                                        }),
                                        I.on(document, "keydown.tab.bs.focustrap", function (t) {
                                            return e._handleKeydown(t);
                                        }),
                                        (this._isActive = !0));
                                },
                            },
                            {
                                key: "deactivate",
                                value: function () {
                                    this._isActive && ((this._isActive = !1), I.off(document, Qe));
                                },
                            },
                            {
                                key: "_handleFocusin",
                                value: function (e) {
                                    var t = this._config.trapElement;
                                    if (e.target !== document && e.target !== t && !t.contains(e.target)) {
                                        var n = Q.focusableChildren(t);
                                        0 === n.length ? t.focus() : this._lastTabNavDirection === Ye ? n[n.length - 1].focus() : n[0].focus();
                                    }
                                },
                            },
                            {
                                key: "_handleKeydown",
                                value: function (e) {
                                    "Tab" === e.key && (this._lastTabNavDirection = e.shiftKey ? Ye : "forward");
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return Ge;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return Je;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "focustrap";
                                },
                            },
                        ]
                    ),
                    n
                );
            })(V),
            et = "hidden.bs.modal",
            tt = "show.bs.modal",
            nt = "modal-open",
            rt = "show",
            it = "modal-static",
            ot = { backdrop: !0, focus: !0, keyboard: !0 },
            at = { backdrop: "(boolean|string)", focus: "boolean", keyboard: "boolean" },
            st = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return (
                        _classCallCheck(this, n),
                        ((i = t.call(this, e, r))._dialog = Q.findOne(".modal-dialog", i._element)),
                        (i._backdrop = i._initializeBackDrop()),
                        (i._focustrap = i._initializeFocusTrap()),
                        (i._isShown = !1),
                        (i._isTransitioning = !1),
                        (i._scrollBar = new ze()),
                        i._addEventListeners(),
                        i
                    );
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "toggle",
                                value: function (e) {
                                    return this._isShown ? this.hide() : this.show(e);
                                },
                            },
                            {
                                key: "show",
                                value: function (e) {
                                    var t = this;
                                    this._isShown ||
                                        this._isTransitioning ||
                                        I.trigger(this._element, tt, { relatedTarget: e }).defaultPrevented ||
                                        ((this._isShown = !0),
                                        (this._isTransitioning = !0),
                                        this._scrollBar.hide(),
                                        document.body.classList.add(nt),
                                        this._adjustDialog(),
                                        this._backdrop.show(function () {
                                            return t._showElement(e);
                                        }));
                                },
                            },
                            {
                                key: "hide",
                                value: function () {
                                    var e = this;
                                    this._isShown &&
                                        !this._isTransitioning &&
                                        (I.trigger(this._element, "hide.bs.modal").defaultPrevented ||
                                            ((this._isShown = !1),
                                            (this._isTransitioning = !0),
                                            this._focustrap.deactivate(),
                                            this._element.classList.remove(rt),
                                            this._queueCallback(
                                                function () {
                                                    return e._hideModal();
                                                },
                                                this._element,
                                                this._isAnimated()
                                            )));
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    for (var e = 0, t = [window, this._dialog]; e < t.length; e++) {
                                        var r = t[e];
                                        I.off(r, ".bs.modal");
                                    }
                                    this._backdrop.dispose(), this._focustrap.deactivate(), _get(_getPrototypeOf(n.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "handleUpdate",
                                value: function () {
                                    this._adjustDialog();
                                },
                            },
                            {
                                key: "_initializeBackDrop",
                                value: function () {
                                    return new Ke({ isVisible: Boolean(this._config.backdrop), isAnimated: this._isAnimated() });
                                },
                            },
                            {
                                key: "_initializeFocusTrap",
                                value: function () {
                                    return new Ze({ trapElement: this._element });
                                },
                            },
                            {
                                key: "_showElement",
                                value: function (e) {
                                    var t = this;
                                    document.body.contains(this._element) || document.body.append(this._element),
                                        (this._element.style.display = "block"),
                                        this._element.removeAttribute("aria-hidden"),
                                        this._element.setAttribute("aria-modal", !0),
                                        this._element.setAttribute("role", "dialog"),
                                        (this._element.scrollTop = 0);
                                    var n = Q.findOne(".modal-body", this._dialog);
                                    n && (n.scrollTop = 0),
                                        f(this._element),
                                        this._element.classList.add(rt),
                                        this._queueCallback(
                                            function () {
                                                t._config.focus && t._focustrap.activate(), (t._isTransitioning = !1), I.trigger(t._element, "shown.bs.modal", { relatedTarget: e });
                                            },
                                            this._dialog,
                                            this._isAnimated()
                                        );
                                },
                            },
                            {
                                key: "_addEventListeners",
                                value: function () {
                                    var e = this;
                                    I.on(this._element, "keydown.dismiss.bs.modal", function (t) {
                                        if ("Escape" === t.key) return e._config.keyboard ? (t.preventDefault(), void e.hide()) : void e._triggerBackdropTransition();
                                    }),
                                        I.on(window, "resize.bs.modal", function () {
                                            e._isShown && !e._isTransitioning && e._adjustDialog();
                                        }),
                                        I.on(this._element, "mousedown.dismiss.bs.modal", function (t) {
                                            I.one(e._element, "click.dismiss.bs.modal", function (n) {
                                                e._element === t.target && e._element === n.target && ("static" !== e._config.backdrop ? e._config.backdrop && e.hide() : e._triggerBackdropTransition());
                                            });
                                        });
                                },
                            },
                            {
                                key: "_hideModal",
                                value: function () {
                                    var e = this;
                                    (this._element.style.display = "none"),
                                        this._element.setAttribute("aria-hidden", !0),
                                        this._element.removeAttribute("aria-modal"),
                                        this._element.removeAttribute("role"),
                                        (this._isTransitioning = !1),
                                        this._backdrop.hide(function () {
                                            document.body.classList.remove(nt), e._resetAdjustments(), e._scrollBar.reset(), I.trigger(e._element, et);
                                        });
                                },
                            },
                            {
                                key: "_isAnimated",
                                value: function () {
                                    return this._element.classList.contains("fade");
                                },
                            },
                            {
                                key: "_triggerBackdropTransition",
                                value: function () {
                                    var e = this;
                                    if (!I.trigger(this._element, "hidePrevented.bs.modal").defaultPrevented) {
                                        var t = this._element.scrollHeight > document.documentElement.clientHeight,
                                            n = this._element.style.overflowY;
                                        "hidden" === n ||
                                            this._element.classList.contains(it) ||
                                            (t || (this._element.style.overflowY = "hidden"),
                                            this._element.classList.add(it),
                                            this._queueCallback(function () {
                                                e._element.classList.remove(it),
                                                    e._queueCallback(function () {
                                                        e._element.style.overflowY = n;
                                                    }, e._dialog);
                                            }, this._dialog),
                                            this._element.focus());
                                    }
                                },
                            },
                            {
                                key: "_adjustDialog",
                                value: function () {
                                    var e = this._element.scrollHeight > document.documentElement.clientHeight,
                                        t = this._scrollBar.getWidth(),
                                        n = t > 0;
                                    if (n && !e) {
                                        var r = p() ? "paddingLeft" : "paddingRight";
                                        this._element.style[r] = "".concat(t, "px");
                                    }
                                    if (!n && e) {
                                        var i = p() ? "paddingRight" : "paddingLeft";
                                        this._element.style[i] = "".concat(t, "px");
                                    }
                                },
                            },
                            {
                                key: "_resetAdjustments",
                                value: function () {
                                    (this._element.style.paddingLeft = ""), (this._element.style.paddingRight = "");
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return ot;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return at;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "modal";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e, t) {
                                    return this.each(function () {
                                        var r = n.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === r[e]) throw new TypeError('No method named "'.concat(e, '"'));
                                            r[e](t);
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(document, "click.bs.modal.data-api", '[data-bs-toggle="modal"]', function (e) {
            var t = this,
                n = Q.getElementFromSelector(this);
            ["A", "AREA"].includes(this.tagName) && e.preventDefault(),
                I.one(n, tt, function (e) {
                    e.defaultPrevented ||
                        I.one(n, et, function () {
                            s(t) && t.focus();
                        });
                });
            var r = Q.findOne(".modal.show");
            r && st.getInstance(r).hide(), st.getOrCreateInstance(n).toggle(this);
        }),
            Y(st),
            g(st);
        var lt = "show",
            ut = "showing",
            ct = "hiding",
            ft = ".offcanvas.show",
            dt = "hidePrevented.bs.offcanvas",
            ht = "hidden.bs.offcanvas",
            pt = { backdrop: !0, keyboard: !0, scroll: !1 },
            gt = { backdrop: "(boolean|string)", keyboard: "boolean", scroll: "boolean" },
            vt = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return _classCallCheck(this, n), ((i = t.call(this, e, r))._isShown = !1), (i._backdrop = i._initializeBackDrop()), (i._focustrap = i._initializeFocusTrap()), i._addEventListeners(), i;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "toggle",
                                value: function (e) {
                                    return this._isShown ? this.hide() : this.show(e);
                                },
                            },
                            {
                                key: "show",
                                value: function (e) {
                                    var t = this;
                                    this._isShown ||
                                        I.trigger(this._element, "show.bs.offcanvas", { relatedTarget: e }).defaultPrevented ||
                                        ((this._isShown = !0),
                                        this._backdrop.show(),
                                        this._config.scroll || new ze().hide(),
                                        this._element.setAttribute("aria-modal", !0),
                                        this._element.setAttribute("role", "dialog"),
                                        this._element.classList.add(ut),
                                        this._queueCallback(
                                            function () {
                                                (t._config.scroll && !t._config.backdrop) || t._focustrap.activate(),
                                                    t._element.classList.add(lt),
                                                    t._element.classList.remove(ut),
                                                    I.trigger(t._element, "shown.bs.offcanvas", { relatedTarget: e });
                                            },
                                            this._element,
                                            !0
                                        ));
                                },
                            },
                            {
                                key: "hide",
                                value: function () {
                                    var e = this;
                                    this._isShown &&
                                        (I.trigger(this._element, "hide.bs.offcanvas").defaultPrevented ||
                                            (this._focustrap.deactivate(),
                                            this._element.blur(),
                                            (this._isShown = !1),
                                            this._element.classList.add(ct),
                                            this._backdrop.hide(),
                                            this._queueCallback(
                                                function () {
                                                    e._element.classList.remove(lt, ct), e._element.removeAttribute("aria-modal"), e._element.removeAttribute("role"), e._config.scroll || new ze().reset(), I.trigger(e._element, ht);
                                                },
                                                this._element,
                                                !0
                                            )));
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    this._backdrop.dispose(), this._focustrap.deactivate(), _get(_getPrototypeOf(n.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "_initializeBackDrop",
                                value: function () {
                                    var e = this,
                                        t = Boolean(this._config.backdrop);
                                    return new Ke({
                                        className: "offcanvas-backdrop",
                                        isVisible: t,
                                        isAnimated: !0,
                                        rootElement: this._element.parentNode,
                                        clickCallback: t
                                            ? function () {
                                                  "static" !== e._config.backdrop ? e.hide() : I.trigger(e._element, dt);
                                              }
                                            : null,
                                    });
                                },
                            },
                            {
                                key: "_initializeFocusTrap",
                                value: function () {
                                    return new Ze({ trapElement: this._element });
                                },
                            },
                            {
                                key: "_addEventListeners",
                                value: function () {
                                    var e = this;
                                    I.on(this._element, "keydown.dismiss.bs.offcanvas", function (t) {
                                        "Escape" === t.key && (e._config.keyboard ? e.hide() : I.trigger(e._element, dt));
                                    });
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return pt;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return gt;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "offcanvas";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e] || e.startsWith("_") || "constructor" === e) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e](this);
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(document, "click.bs.offcanvas.data-api", '[data-bs-toggle="offcanvas"]', function (e) {
            var t = this,
                n = Q.getElementFromSelector(this);
            if ((["A", "AREA"].includes(this.tagName) && e.preventDefault(), !l(this))) {
                I.one(n, ht, function () {
                    s(t) && t.focus();
                });
                var r = Q.findOne(ft);
                r && r !== n && vt.getInstance(r).hide(), vt.getOrCreateInstance(n).toggle(this);
            }
        }),
            I.on(window, "load.bs.offcanvas.data-api", function () {
                var e,
                    t = _createForOfIteratorHelper(Q.find(ft));
                try {
                    for (t.s(); !(e = t.n()).done; ) {
                        var n = e.value;
                        vt.getOrCreateInstance(n).show();
                    }
                } catch (e) {
                    t.e(e);
                } finally {
                    t.f();
                }
            }),
            I.on(window, "resize.bs.offcanvas", function () {
                var e,
                    t = _createForOfIteratorHelper(Q.find("[aria-modal][class*=show][class*=offcanvas-]"));
                try {
                    for (t.s(); !(e = t.n()).done; ) {
                        var n = e.value;
                        "fixed" !== getComputedStyle(n).position && vt.getOrCreateInstance(n).hide();
                    }
                } catch (e) {
                    t.e(e);
                } finally {
                    t.f();
                }
            }),
            Y(vt),
            g(vt);
        var mt = new Set(["background", "cite", "href", "itemtype", "longdesc", "poster", "src", "xlink:href"]),
            yt = /^(?:(?:https?|mailto|ftp|tel|file|sms):|[^#&/:?]*(?:[#/?]|$))/i,
            _t = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[\d+/a-z]+=*$/i,
            bt = function (e, t) {
                var n = e.nodeName.toLowerCase();
                return t.includes(n)
                    ? !mt.has(n) || Boolean(yt.test(e.nodeValue) || _t.test(e.nodeValue))
                    : t
                          .filter(function (e) {
                              return e instanceof RegExp;
                          })
                          .some(function (e) {
                              return e.test(n);
                          });
            },
            wt = {
                "*": ["class", "dir", "id", "lang", "role", /^aria-[\w-]*$/i],
                a: ["target", "href", "title", "rel"],
                area: [],
                b: [],
                br: [],
                col: [],
                code: [],
                div: [],
                em: [],
                hr: [],
                h1: [],
                h2: [],
                h3: [],
                h4: [],
                h5: [],
                h6: [],
                i: [],
                img: ["src", "srcset", "alt", "title", "width", "height"],
                li: [],
                ol: [],
                p: [],
                pre: [],
                s: [],
                small: [],
                span: [],
                sub: [],
                sup: [],
                strong: [],
                u: [],
                ul: [],
            },
            kt = { allowList: wt, content: {}, extraClass: "", html: !1, sanitize: !0, sanitizeFn: null, template: "<div></div>" },
            xt = { allowList: "object", content: "object", extraClass: "(string|function)", html: "boolean", sanitize: "boolean", sanitizeFn: "(null|function)", template: "string" },
            Ct = { entry: "(string|element|function|null)", selector: "(string|element)" },
            Tt = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e) {
                    var r;
                    return _classCallCheck(this, n), ((r = t.call(this))._config = r._getConfig(e)), r;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "getContent",
                                value: function () {
                                    var e = this;
                                    return Object.values(this._config.content)
                                        .map(function (t) {
                                            return e._resolvePossibleFunction(t);
                                        })
                                        .filter(Boolean);
                                },
                            },
                            {
                                key: "hasContent",
                                value: function () {
                                    return this.getContent().length > 0;
                                },
                            },
                            {
                                key: "changeContent",
                                value: function (e) {
                                    return this._checkContent(e), (this._config.content = _objectSpread(_objectSpread({}, this._config.content), e)), this;
                                },
                            },
                            {
                                key: "toHtml",
                                value: function () {
                                    var e,
                                        t = document.createElement("div");
                                    t.innerHTML = this._maybeSanitize(this._config.template);
                                    for (var n = 0, r = Object.entries(this._config.content); n < r.length; n++) {
                                        var i = _slicedToArray(r[n], 2),
                                            o = i[0],
                                            a = i[1];
                                        this._setContent(t, a, o);
                                    }
                                    var s = t.children[0],
                                        l = this._resolvePossibleFunction(this._config.extraClass);
                                    return l && (e = s.classList).add.apply(e, _toConsumableArray(l.split(" "))), s;
                                },
                            },
                            {
                                key: "_typeCheckConfig",
                                value: function (e) {
                                    _get(_getPrototypeOf(n.prototype), "_typeCheckConfig", this).call(this, e), this._checkContent(e.content);
                                },
                            },
                            {
                                key: "_checkContent",
                                value: function (e) {
                                    for (var t = 0, r = Object.entries(e); t < r.length; t++) {
                                        var i = _slicedToArray(r[t], 2),
                                            o = i[0],
                                            a = i[1];
                                        _get(_getPrototypeOf(n.prototype), "_typeCheckConfig", this).call(this, { selector: o, entry: a }, Ct);
                                    }
                                },
                            },
                            {
                                key: "_setContent",
                                value: function (e, t, n) {
                                    var r = Q.findOne(n, e);
                                    r && ((t = this._resolvePossibleFunction(t)) ? (o(t) ? this._putElementInTemplate(a(t), r) : this._config.html ? (r.innerHTML = this._maybeSanitize(t)) : (r.textContent = t)) : r.remove());
                                },
                            },
                            {
                                key: "_maybeSanitize",
                                value: function (e) {
                                    return this._config.sanitize
                                        ? (function (e, t, n) {
                                              var r;
                                              if (!e.length) return e;
                                              if (n && "function" == typeof n) return n(e);
                                              var i,
                                                  o = new window.DOMParser().parseFromString(e, "text/html"),
                                                  a = _createForOfIteratorHelper((r = []).concat.apply(r, _toConsumableArray(o.body.querySelectorAll("*"))));
                                              try {
                                                  for (a.s(); !(i = a.n()).done; ) {
                                                      var s,
                                                          l = i.value,
                                                          u = l.nodeName.toLowerCase();
                                                      if (Object.keys(t).includes(u)) {
                                                          var c,
                                                              f = (s = []).concat.apply(s, _toConsumableArray(l.attributes)),
                                                              d = [].concat(t["*"] || [], t[u] || []),
                                                              h = _createForOfIteratorHelper(f);
                                                          try {
                                                              for (h.s(); !(c = h.n()).done; ) {
                                                                  var p = c.value;
                                                                  bt(p, d) || l.removeAttribute(p.nodeName);
                                                              }
                                                          } catch (e) {
                                                              h.e(e);
                                                          } finally {
                                                              h.f();
                                                          }
                                                      } else l.remove();
                                                  }
                                              } catch (e) {
                                                  a.e(e);
                                              } finally {
                                                  a.f();
                                              }
                                              return o.body.innerHTML;
                                          })(e, this._config.allowList, this._config.sanitizeFn)
                                        : e;
                                },
                            },
                            {
                                key: "_resolvePossibleFunction",
                                value: function (e) {
                                    return v(e, [this]);
                                },
                            },
                            {
                                key: "_putElementInTemplate",
                                value: function (e, t) {
                                    if (this._config.html) return (t.innerHTML = ""), void t.append(e);
                                    t.textContent = e.textContent;
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return kt;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return xt;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "TemplateFactory";
                                },
                            },
                        ]
                    ),
                    n
                );
            })(V),
            At = new Set(["sanitize", "allowList", "sanitizeFn"]),
            Et = "fade",
            St = "show",
            Ot = ".modal",
            jt = "hide.bs.modal",
            Nt = "hover",
            Dt = "focus",
            Lt = { AUTO: "auto", TOP: "top", RIGHT: p() ? "left" : "right", BOTTOM: "bottom", LEFT: p() ? "right" : "left" },
            It = {
                allowList: wt,
                animation: !0,
                boundary: "clippingParents",
                container: !1,
                customClass: "",
                delay: 0,
                fallbackPlacements: ["top", "right", "bottom", "left"],
                html: !1,
                offset: [0, 0],
                placement: "top",
                popperConfig: null,
                sanitize: !0,
                sanitizeFn: null,
                selector: !1,
                template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                title: "",
                trigger: "hover focus",
            },
            Pt = {
                allowList: "object",
                animation: "boolean",
                boundary: "(string|element)",
                container: "(string|element|boolean)",
                customClass: "(string|function)",
                delay: "(number|object)",
                fallbackPlacements: "array",
                html: "boolean",
                offset: "(array|string|function)",
                placement: "(string|function)",
                popperConfig: "(null|object|function)",
                sanitize: "boolean",
                sanitizeFn: "(null|function)",
                selector: "(string|boolean)",
                template: "string",
                title: "(string|element|function)",
                trigger: "string",
            },
            Ht = (function (e) {
                _inherits(r, e);
                var n = _createSuper(r);
                function r(e, i) {
                    var o;
                    if ((_classCallCheck(this, r), void 0 === t)) throw new TypeError("Bootstrap's tooltips require Popper (https://popper.js.org)");
                    return (
                        ((o = n.call(this, e, i))._isEnabled = !0),
                        (o._timeout = 0),
                        (o._isHovered = null),
                        (o._activeTrigger = {}),
                        (o._popper = null),
                        (o._templateFactory = null),
                        (o._newContent = null),
                        (o.tip = null),
                        o._setListeners(),
                        o._config.selector || o._fixTitle(),
                        o
                    );
                }
                return (
                    _createClass(
                        r,
                        [
                            {
                                key: "enable",
                                value: function () {
                                    this._isEnabled = !0;
                                },
                            },
                            {
                                key: "disable",
                                value: function () {
                                    this._isEnabled = !1;
                                },
                            },
                            {
                                key: "toggleEnabled",
                                value: function () {
                                    this._isEnabled = !this._isEnabled;
                                },
                            },
                            {
                                key: "toggle",
                                value: function () {
                                    this._isEnabled && ((this._activeTrigger.click = !this._activeTrigger.click), this._isShown() ? this._leave() : this._enter());
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    clearTimeout(this._timeout),
                                        I.off(this._element.closest(Ot), jt, this._hideModalHandler),
                                        this._element.getAttribute("data-bs-original-title") && this._element.setAttribute("title", this._element.getAttribute("data-bs-original-title")),
                                        this._disposePopper(),
                                        _get(_getPrototypeOf(r.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "show",
                                value: function () {
                                    var e = this;
                                    if ("none" === this._element.style.display) throw new Error("Please use show on visible elements");
                                    if (this._isWithContent() && this._isEnabled) {
                                        var t = I.trigger(this._element, this.constructor.eventName("show")),
                                            n = (u(this._element) || this._element.ownerDocument.documentElement).contains(this._element);
                                        if (!t.defaultPrevented && n) {
                                            this._disposePopper();
                                            var r = this._getTipElement();
                                            this._element.setAttribute("aria-describedby", r.getAttribute("id"));
                                            var i = this._config.container;
                                            if (
                                                (this._element.ownerDocument.documentElement.contains(this.tip) || (i.append(r), I.trigger(this._element, this.constructor.eventName("inserted"))),
                                                (this._popper = this._createPopper(r)),
                                                r.classList.add(St),
                                                "ontouchstart" in document.documentElement)
                                            ) {
                                                var o,
                                                    a,
                                                    s = _createForOfIteratorHelper((o = []).concat.apply(o, _toConsumableArray(document.body.children)));
                                                try {
                                                    for (s.s(); !(a = s.n()).done; ) {
                                                        var l = a.value;
                                                        I.on(l, "mouseover", c);
                                                    }
                                                } catch (e) {
                                                    s.e(e);
                                                } finally {
                                                    s.f();
                                                }
                                            }
                                            this._queueCallback(
                                                function () {
                                                    I.trigger(e._element, e.constructor.eventName("shown")), !1 === e._isHovered && e._leave(), (e._isHovered = !1);
                                                },
                                                this.tip,
                                                this._isAnimated()
                                            );
                                        }
                                    }
                                },
                            },
                            {
                                key: "hide",
                                value: function () {
                                    var e = this;
                                    if (this._isShown() && !I.trigger(this._element, this.constructor.eventName("hide")).defaultPrevented) {
                                        if ((this._getTipElement().classList.remove(St), "ontouchstart" in document.documentElement)) {
                                            var t,
                                                n,
                                                r = _createForOfIteratorHelper((t = []).concat.apply(t, _toConsumableArray(document.body.children)));
                                            try {
                                                for (r.s(); !(n = r.n()).done; ) {
                                                    var i = n.value;
                                                    I.off(i, "mouseover", c);
                                                }
                                            } catch (e) {
                                                r.e(e);
                                            } finally {
                                                r.f();
                                            }
                                        }
                                        (this._activeTrigger.click = !1),
                                            (this._activeTrigger.focus = !1),
                                            (this._activeTrigger.hover = !1),
                                            (this._isHovered = null),
                                            this._queueCallback(
                                                function () {
                                                    e._isWithActiveTrigger() || (e._isHovered || e._disposePopper(), e._element.removeAttribute("aria-describedby"), I.trigger(e._element, e.constructor.eventName("hidden")));
                                                },
                                                this.tip,
                                                this._isAnimated()
                                            );
                                    }
                                },
                            },
                            {
                                key: "update",
                                value: function () {
                                    this._popper && this._popper.update();
                                },
                            },
                            {
                                key: "_isWithContent",
                                value: function () {
                                    return Boolean(this._getTitle());
                                },
                            },
                            {
                                key: "_getTipElement",
                                value: function () {
                                    return this.tip || (this.tip = this._createTipElement(this._newContent || this._getContentForTemplate())), this.tip;
                                },
                            },
                            {
                                key: "_createTipElement",
                                value: function (e) {
                                    var t = this._getTemplateFactory(e).toHtml();
                                    if (!t) return null;
                                    t.classList.remove(Et, St), t.classList.add("bs-".concat(this.constructor.NAME, "-auto"));
                                    var n = (function (e) {
                                        do {
                                            e += Math.floor(1e6 * Math.random());
                                        } while (document.getElementById(e));
                                        return e;
                                    })(this.constructor.NAME).toString();
                                    return t.setAttribute("id", n), this._isAnimated() && t.classList.add(Et), t;
                                },
                            },
                            {
                                key: "setContent",
                                value: function (e) {
                                    (this._newContent = e), this._isShown() && (this._disposePopper(), this.show());
                                },
                            },
                            {
                                key: "_getTemplateFactory",
                                value: function (e) {
                                    return (
                                        this._templateFactory
                                            ? this._templateFactory.changeContent(e)
                                            : (this._templateFactory = new Tt(_objectSpread(_objectSpread({}, this._config), {}, { content: e, extraClass: this._resolvePossibleFunction(this._config.customClass) }))),
                                        this._templateFactory
                                    );
                                },
                            },
                            {
                                key: "_getContentForTemplate",
                                value: function () {
                                    return { ".tooltip-inner": this._getTitle() };
                                },
                            },
                            {
                                key: "_getTitle",
                                value: function () {
                                    return this._resolvePossibleFunction(this._config.title) || this._element.getAttribute("data-bs-original-title");
                                },
                            },
                            {
                                key: "_initializeOnDelegatedTarget",
                                value: function (e) {
                                    return this.constructor.getOrCreateInstance(e.delegateTarget, this._getDelegateConfig());
                                },
                            },
                            {
                                key: "_isAnimated",
                                value: function () {
                                    return this._config.animation || (this.tip && this.tip.classList.contains(Et));
                                },
                            },
                            {
                                key: "_isShown",
                                value: function () {
                                    return this.tip && this.tip.classList.contains(St);
                                },
                            },
                            {
                                key: "_createPopper",
                                value: function (e) {
                                    var n = v(this._config.placement, [this, e, this._element]),
                                        r = Lt[n.toUpperCase()];
                                    return t.createPopper(this._element, e, this._getPopperConfig(r));
                                },
                            },
                            {
                                key: "_getOffset",
                                value: function () {
                                    var e = this,
                                        t = this._config.offset;
                                    return "string" == typeof t
                                        ? t.split(",").map(function (e) {
                                              return Number.parseInt(e, 10);
                                          })
                                        : "function" == typeof t
                                        ? function (n) {
                                              return t(n, e._element);
                                          }
                                        : t;
                                },
                            },
                            {
                                key: "_resolvePossibleFunction",
                                value: function (e) {
                                    return v(e, [this._element]);
                                },
                            },
                            {
                                key: "_getPopperConfig",
                                value: function (e) {
                                    var t = this,
                                        n = {
                                            placement: e,
                                            modifiers: [
                                                { name: "flip", options: { fallbackPlacements: this._config.fallbackPlacements } },
                                                { name: "offset", options: { offset: this._getOffset() } },
                                                { name: "preventOverflow", options: { boundary: this._config.boundary } },
                                                { name: "arrow", options: { element: ".".concat(this.constructor.NAME, "-arrow") } },
                                                {
                                                    name: "preSetPlacement",
                                                    enabled: !0,
                                                    phase: "beforeMain",
                                                    fn: function (e) {
                                                        t._getTipElement().setAttribute("data-popper-placement", e.state.placement);
                                                    },
                                                },
                                            ],
                                        };
                                    return _objectSpread(_objectSpread({}, n), v(this._config.popperConfig, [n]));
                                },
                            },
                            {
                                key: "_setListeners",
                                value: function () {
                                    var e,
                                        t = this,
                                        n = _createForOfIteratorHelper(this._config.trigger.split(" "));
                                    try {
                                        for (n.s(); !(e = n.n()).done; ) {
                                            var r = e.value;
                                            if ("click" === r)
                                                I.on(this._element, this.constructor.eventName("click"), this._config.selector, function (e) {
                                                    t._initializeOnDelegatedTarget(e).toggle();
                                                });
                                            else if ("manual" !== r) {
                                                var i = r === Nt ? this.constructor.eventName("mouseenter") : this.constructor.eventName("focusin"),
                                                    o = r === Nt ? this.constructor.eventName("mouseleave") : this.constructor.eventName("focusout");
                                                I.on(this._element, i, this._config.selector, function (e) {
                                                    var n = t._initializeOnDelegatedTarget(e);
                                                    (n._activeTrigger["focusin" === e.type ? Dt : Nt] = !0), n._enter();
                                                }),
                                                    I.on(this._element, o, this._config.selector, function (e) {
                                                        var n = t._initializeOnDelegatedTarget(e);
                                                        (n._activeTrigger["focusout" === e.type ? Dt : Nt] = n._element.contains(e.relatedTarget)), n._leave();
                                                    });
                                            }
                                        }
                                    } catch (e) {
                                        n.e(e);
                                    } finally {
                                        n.f();
                                    }
                                    (this._hideModalHandler = function () {
                                        t._element && t.hide();
                                    }),
                                        I.on(this._element.closest(Ot), jt, this._hideModalHandler);
                                },
                            },
                            {
                                key: "_fixTitle",
                                value: function () {
                                    var e = this._element.getAttribute("title");
                                    e &&
                                        (this._element.getAttribute("aria-label") || this._element.textContent.trim() || this._element.setAttribute("aria-label", e),
                                        this._element.setAttribute("data-bs-original-title", e),
                                        this._element.removeAttribute("title"));
                                },
                            },
                            {
                                key: "_enter",
                                value: function () {
                                    var e = this;
                                    this._isShown() || this._isHovered
                                        ? (this._isHovered = !0)
                                        : ((this._isHovered = !0),
                                          this._setTimeout(function () {
                                              e._isHovered && e.show();
                                          }, this._config.delay.show));
                                },
                            },
                            {
                                key: "_leave",
                                value: function () {
                                    var e = this;
                                    this._isWithActiveTrigger() ||
                                        ((this._isHovered = !1),
                                        this._setTimeout(function () {
                                            e._isHovered || e.hide();
                                        }, this._config.delay.hide));
                                },
                            },
                            {
                                key: "_setTimeout",
                                value: function (e, t) {
                                    clearTimeout(this._timeout), (this._timeout = setTimeout(e, t));
                                },
                            },
                            {
                                key: "_isWithActiveTrigger",
                                value: function () {
                                    return Object.values(this._activeTrigger).includes(!0);
                                },
                            },
                            {
                                key: "_getConfig",
                                value: function (e) {
                                    for (var t = $(this._element), n = 0, r = Object.keys(t); n < r.length; n++) {
                                        var i = r[n];
                                        At.has(i) && delete t[i];
                                    }
                                    return (e = _objectSpread(_objectSpread({}, t), "object" == _typeof(e) && e ? e : {})), (e = this._mergeConfigObj(e)), (e = this._configAfterMerge(e)), this._typeCheckConfig(e), e;
                                },
                            },
                            {
                                key: "_configAfterMerge",
                                value: function (e) {
                                    return (
                                        (e.container = !1 === e.container ? document.body : a(e.container)),
                                        "number" == typeof e.delay && (e.delay = { show: e.delay, hide: e.delay }),
                                        "number" == typeof e.title && (e.title = e.title.toString()),
                                        "number" == typeof e.content && (e.content = e.content.toString()),
                                        e
                                    );
                                },
                            },
                            {
                                key: "_getDelegateConfig",
                                value: function () {
                                    for (var e = {}, t = 0, n = Object.entries(this._config); t < n.length; t++) {
                                        var r = _slicedToArray(n[t], 2),
                                            i = r[0],
                                            o = r[1];
                                        this.constructor.Default[i] !== o && (e[i] = o);
                                    }
                                    return (e.selector = !1), (e.trigger = "manual"), e;
                                },
                            },
                            {
                                key: "_disposePopper",
                                value: function () {
                                    this._popper && (this._popper.destroy(), (this._popper = null)), this.tip && (this.tip.remove(), (this.tip = null));
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return It;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return Pt;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "tooltip";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = r.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e]) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e]();
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    r
                );
            })(U);
        g(Ht);
        var Mt = _objectSpread(
                _objectSpread({}, Ht.Default),
                {},
                { content: "", offset: [0, 8], placement: "right", template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>', trigger: "click" }
            ),
            qt = _objectSpread(_objectSpread({}, Ht.DefaultType), {}, { content: "(null|string|element|function)" }),
            Ft = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n() {
                    return _classCallCheck(this, n), t.apply(this, arguments);
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "_isWithContent",
                                value: function () {
                                    return this._getTitle() || this._getContent();
                                },
                            },
                            {
                                key: "_getContentForTemplate",
                                value: function () {
                                    return { ".popover-header": this._getTitle(), ".popover-body": this._getContent() };
                                },
                            },
                            {
                                key: "_getContent",
                                value: function () {
                                    return this._resolvePossibleFunction(this._config.content);
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return Mt;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return qt;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "popover";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e]) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e]();
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(Ht);
        g(Ft);
        var Rt = "click.bs.scrollspy",
            Wt = "active",
            Bt = "[href]",
            zt = { offset: null, rootMargin: "0px 0px -25%", smoothScroll: !1, target: null, threshold: [0.1, 0.5, 1] },
            $t = { offset: "(number|null)", rootMargin: "string", smoothScroll: "boolean", target: "element", threshold: "array" },
            Xt = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return (
                        _classCallCheck(this, n),
                        ((i = t.call(this, e, r))._targetLinks = new Map()),
                        (i._observableSections = new Map()),
                        (i._rootElement = "visible" === getComputedStyle(i._element).overflowY ? null : i._element),
                        (i._activeTarget = null),
                        (i._observer = null),
                        (i._previousScrollData = { visibleEntryTop: 0, parentScrollTop: 0 }),
                        i.refresh(),
                        i
                    );
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "refresh",
                                value: function () {
                                    this._initializeTargetsAndObservables(), this._maybeEnableSmoothScroll(), this._observer ? this._observer.disconnect() : (this._observer = this._getNewObserver());
                                    var e,
                                        t = _createForOfIteratorHelper(this._observableSections.values());
                                    try {
                                        for (t.s(); !(e = t.n()).done; ) {
                                            var n = e.value;
                                            this._observer.observe(n);
                                        }
                                    } catch (e) {
                                        t.e(e);
                                    } finally {
                                        t.f();
                                    }
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    this._observer.disconnect(), _get(_getPrototypeOf(n.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "_configAfterMerge",
                                value: function (e) {
                                    return (
                                        (e.target = a(e.target) || document.body),
                                        (e.rootMargin = e.offset ? "".concat(e.offset, "px 0px -30%") : e.rootMargin),
                                        "string" == typeof e.threshold &&
                                            (e.threshold = e.threshold.split(",").map(function (e) {
                                                return Number.parseFloat(e);
                                            })),
                                        e
                                    );
                                },
                            },
                            {
                                key: "_maybeEnableSmoothScroll",
                                value: function () {
                                    var e = this;
                                    this._config.smoothScroll &&
                                        (I.off(this._config.target, Rt),
                                        I.on(this._config.target, Rt, Bt, function (t) {
                                            var n = e._observableSections.get(t.target.hash);
                                            if (n) {
                                                t.preventDefault();
                                                var r = e._rootElement || window,
                                                    i = n.offsetTop - e._element.offsetTop;
                                                if (r.scrollTo) return void r.scrollTo({ top: i, behavior: "smooth" });
                                                r.scrollTop = i;
                                            }
                                        }));
                                },
                            },
                            {
                                key: "_getNewObserver",
                                value: function () {
                                    var e = this,
                                        t = { root: this._rootElement, threshold: this._config.threshold, rootMargin: this._config.rootMargin };
                                    return new IntersectionObserver(function (t) {
                                        return e._observerCallback(t);
                                    }, t);
                                },
                            },
                            {
                                key: "_observerCallback",
                                value: function (e) {
                                    var t = this,
                                        n = function (e) {
                                            return t._targetLinks.get("#".concat(e.target.id));
                                        },
                                        r = function (e) {
                                            (t._previousScrollData.visibleEntryTop = e.target.offsetTop), t._process(n(e));
                                        },
                                        i = (this._rootElement || document.documentElement).scrollTop,
                                        o = i >= this._previousScrollData.parentScrollTop;
                                    this._previousScrollData.parentScrollTop = i;
                                    var a,
                                        s = _createForOfIteratorHelper(e);
                                    try {
                                        for (s.s(); !(a = s.n()).done; ) {
                                            var l = a.value;
                                            if (l.isIntersecting) {
                                                var u = l.target.offsetTop >= this._previousScrollData.visibleEntryTop;
                                                if (o && u) {
                                                    if ((r(l), !i)) return;
                                                } else o || u || r(l);
                                            } else (this._activeTarget = null), this._clearActiveClass(n(l));
                                        }
                                    } catch (e) {
                                        s.e(e);
                                    } finally {
                                        s.f();
                                    }
                                },
                            },
                            {
                                key: "_initializeTargetsAndObservables",
                                value: function () {
                                    (this._targetLinks = new Map()), (this._observableSections = new Map());
                                    var e,
                                        t = _createForOfIteratorHelper(Q.find(Bt, this._config.target));
                                    try {
                                        for (t.s(); !(e = t.n()).done; ) {
                                            var n = e.value;
                                            if (n.hash && !l(n)) {
                                                var r = Q.findOne(n.hash, this._element);
                                                s(r) && (this._targetLinks.set(n.hash, n), this._observableSections.set(n.hash, r));
                                            }
                                        }
                                    } catch (e) {
                                        t.e(e);
                                    } finally {
                                        t.f();
                                    }
                                },
                            },
                            {
                                key: "_process",
                                value: function (e) {
                                    this._activeTarget !== e &&
                                        (this._clearActiveClass(this._config.target), (this._activeTarget = e), e.classList.add(Wt), this._activateParents(e), I.trigger(this._element, "activate.bs.scrollspy", { relatedTarget: e }));
                                },
                            },
                            {
                                key: "_activateParents",
                                value: function (e) {
                                    if (e.classList.contains("dropdown-item")) Q.findOne(".dropdown-toggle", e.closest(".dropdown")).classList.add(Wt);
                                    else {
                                        var t,
                                            n = _createForOfIteratorHelper(Q.parents(e, ".nav, .list-group"));
                                        try {
                                            for (n.s(); !(t = n.n()).done; ) {
                                                var r,
                                                    i = t.value,
                                                    o = _createForOfIteratorHelper(Q.prev(i, ".nav-link, .nav-item > .nav-link, .list-group-item"));
                                                try {
                                                    for (o.s(); !(r = o.n()).done; ) {
                                                        r.value.classList.add(Wt);
                                                    }
                                                } catch (e) {
                                                    o.e(e);
                                                } finally {
                                                    o.f();
                                                }
                                            }
                                        } catch (e) {
                                            n.e(e);
                                        } finally {
                                            n.f();
                                        }
                                    }
                                },
                            },
                            {
                                key: "_clearActiveClass",
                                value: function (e) {
                                    e.classList.remove(Wt);
                                    var t,
                                        n = _createForOfIteratorHelper(Q.find("[href].active", e));
                                    try {
                                        for (n.s(); !(t = n.n()).done; ) {
                                            t.value.classList.remove(Wt);
                                        }
                                    } catch (e) {
                                        n.e(e);
                                    } finally {
                                        n.f();
                                    }
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return zt;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return $t;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "scrollspy";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e] || e.startsWith("_") || "constructor" === e) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e]();
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(window, "load.bs.scrollspy.data-api", function () {
            var e,
                t = _createForOfIteratorHelper(Q.find('[data-bs-spy="scroll"]'));
            try {
                for (t.s(); !(e = t.n()).done; ) {
                    var n = e.value;
                    Xt.getOrCreateInstance(n);
                }
            } catch (e) {
                t.e(e);
            } finally {
                t.f();
            }
        }),
            g(Xt);
        var Vt = "ArrowLeft",
            Ut = "ArrowRight",
            Kt = "ArrowUp",
            Qt = "ArrowDown",
            Yt = "active",
            Gt = "fade",
            Jt = "show",
            Zt = '[data-bs-toggle="tab"], [data-bs-toggle="pill"], [data-bs-toggle="list"]',
            en = '.nav-link:not(.dropdown-toggle), .list-group-item:not(.dropdown-toggle), [role="tab"]:not(.dropdown-toggle), '.concat(Zt),
            tn = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e) {
                    var r;
                    return (
                        _classCallCheck(this, n),
                        ((r = t.call(this, e))._parent = r._element.closest('.list-group, .nav, [role="tablist"]')),
                        r._parent &&
                            (r._setInitialAttributes(r._parent, r._getChildren()),
                            I.on(r._element, "keydown.bs.tab", function (e) {
                                return r._keydown(e);
                            })),
                        r
                    );
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "show",
                                value: function () {
                                    var e = this._element;
                                    if (!this._elemIsActive(e)) {
                                        var t = this._getActiveElem(),
                                            n = t ? I.trigger(t, "hide.bs.tab", { relatedTarget: e }) : null;
                                        I.trigger(e, "show.bs.tab", { relatedTarget: t }).defaultPrevented || (n && n.defaultPrevented) || (this._deactivate(t, e), this._activate(e, t));
                                    }
                                },
                            },
                            {
                                key: "_activate",
                                value: function (e, t) {
                                    var n = this;
                                    e &&
                                        (e.classList.add(Yt),
                                        this._activate(Q.getElementFromSelector(e)),
                                        this._queueCallback(
                                            function () {
                                                "tab" === e.getAttribute("role")
                                                    ? (e.removeAttribute("tabindex"), e.setAttribute("aria-selected", !0), n._toggleDropDown(e, !0), I.trigger(e, "shown.bs.tab", { relatedTarget: t }))
                                                    : e.classList.add(Jt);
                                            },
                                            e,
                                            e.classList.contains(Gt)
                                        ));
                                },
                            },
                            {
                                key: "_deactivate",
                                value: function (e, t) {
                                    var n = this;
                                    e &&
                                        (e.classList.remove(Yt),
                                        e.blur(),
                                        this._deactivate(Q.getElementFromSelector(e)),
                                        this._queueCallback(
                                            function () {
                                                "tab" === e.getAttribute("role")
                                                    ? (e.setAttribute("aria-selected", !1), e.setAttribute("tabindex", "-1"), n._toggleDropDown(e, !1), I.trigger(e, "hidden.bs.tab", { relatedTarget: t }))
                                                    : e.classList.remove(Jt);
                                            },
                                            e,
                                            e.classList.contains(Gt)
                                        ));
                                },
                            },
                            {
                                key: "_keydown",
                                value: function (e) {
                                    if ([Vt, Ut, Kt, Qt].includes(e.key)) {
                                        e.stopPropagation(), e.preventDefault();
                                        var t = [Ut, Qt].includes(e.key),
                                            r = y(
                                                this._getChildren().filter(function (e) {
                                                    return !l(e);
                                                }),
                                                e.target,
                                                t,
                                                !0
                                            );
                                        r && (r.focus({ preventScroll: !0 }), n.getOrCreateInstance(r).show());
                                    }
                                },
                            },
                            {
                                key: "_getChildren",
                                value: function () {
                                    return Q.find(en, this._parent);
                                },
                            },
                            {
                                key: "_getActiveElem",
                                value: function () {
                                    var e = this;
                                    return (
                                        this._getChildren().find(function (t) {
                                            return e._elemIsActive(t);
                                        }) || null
                                    );
                                },
                            },
                            {
                                key: "_setInitialAttributes",
                                value: function (e, t) {
                                    this._setAttributeIfNotExists(e, "role", "tablist");
                                    var n,
                                        r = _createForOfIteratorHelper(t);
                                    try {
                                        for (r.s(); !(n = r.n()).done; ) {
                                            var i = n.value;
                                            this._setInitialAttributesOnChild(i);
                                        }
                                    } catch (e) {
                                        r.e(e);
                                    } finally {
                                        r.f();
                                    }
                                },
                            },
                            {
                                key: "_setInitialAttributesOnChild",
                                value: function (e) {
                                    e = this._getInnerElement(e);
                                    var t = this._elemIsActive(e),
                                        n = this._getOuterElement(e);
                                    e.setAttribute("aria-selected", t),
                                        n !== e && this._setAttributeIfNotExists(n, "role", "presentation"),
                                        t || e.setAttribute("tabindex", "-1"),
                                        this._setAttributeIfNotExists(e, "role", "tab"),
                                        this._setInitialAttributesOnTargetPanel(e);
                                },
                            },
                            {
                                key: "_setInitialAttributesOnTargetPanel",
                                value: function (e) {
                                    var t = Q.getElementFromSelector(e);
                                    t && (this._setAttributeIfNotExists(t, "role", "tabpanel"), e.id && this._setAttributeIfNotExists(t, "aria-labelledby", "#".concat(e.id)));
                                },
                            },
                            {
                                key: "_toggleDropDown",
                                value: function (e, t) {
                                    var n = this._getOuterElement(e);
                                    if (n.classList.contains("dropdown")) {
                                        var r = function (e, r) {
                                            var i = Q.findOne(e, n);
                                            i && i.classList.toggle(r, t);
                                        };
                                        r(".dropdown-toggle", Yt), r(".dropdown-menu", Jt), n.setAttribute("aria-expanded", t);
                                    }
                                },
                            },
                            {
                                key: "_setAttributeIfNotExists",
                                value: function (e, t, n) {
                                    e.hasAttribute(t) || e.setAttribute(t, n);
                                },
                            },
                            {
                                key: "_elemIsActive",
                                value: function (e) {
                                    return e.classList.contains(Yt);
                                },
                            },
                            {
                                key: "_getInnerElement",
                                value: function (e) {
                                    return e.matches(en) ? e : Q.findOne(en, e);
                                },
                            },
                            {
                                key: "_getOuterElement",
                                value: function (e) {
                                    return e.closest(".nav-item, .list-group-item") || e;
                                },
                            },
                        ],
                        [
                            {
                                key: "NAME",
                                get: function () {
                                    return "tab";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e] || e.startsWith("_") || "constructor" === e) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e]();
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        I.on(document, "click.bs.tab", Zt, function (e) {
            ["A", "AREA"].includes(this.tagName) && e.preventDefault(), l(this) || tn.getOrCreateInstance(this).show();
        }),
            I.on(window, "load.bs.tab", function () {
                var e,
                    t = _createForOfIteratorHelper(Q.find('.active[data-bs-toggle="tab"], .active[data-bs-toggle="pill"], .active[data-bs-toggle="list"]'));
                try {
                    for (t.s(); !(e = t.n()).done; ) {
                        var n = e.value;
                        tn.getOrCreateInstance(n);
                    }
                } catch (e) {
                    t.e(e);
                } finally {
                    t.f();
                }
            }),
            g(tn);
        var nn = "hide",
            rn = "show",
            on = "showing",
            an = { animation: "boolean", autohide: "boolean", delay: "number" },
            sn = { animation: !0, autohide: !0, delay: 5e3 },
            ln = (function (e) {
                _inherits(n, e);
                var t = _createSuper(n);
                function n(e, r) {
                    var i;
                    return _classCallCheck(this, n), ((i = t.call(this, e, r))._timeout = null), (i._hasMouseInteraction = !1), (i._hasKeyboardInteraction = !1), i._setListeners(), i;
                }
                return (
                    _createClass(
                        n,
                        [
                            {
                                key: "show",
                                value: function () {
                                    var e = this;
                                    I.trigger(this._element, "show.bs.toast").defaultPrevented ||
                                        (this._clearTimeout(),
                                        this._config.animation && this._element.classList.add("fade"),
                                        this._element.classList.remove(nn),
                                        f(this._element),
                                        this._element.classList.add(rn, on),
                                        this._queueCallback(
                                            function () {
                                                e._element.classList.remove(on), I.trigger(e._element, "shown.bs.toast"), e._maybeScheduleHide();
                                            },
                                            this._element,
                                            this._config.animation
                                        ));
                                },
                            },
                            {
                                key: "hide",
                                value: function () {
                                    var e = this;
                                    this.isShown() &&
                                        (I.trigger(this._element, "hide.bs.toast").defaultPrevented ||
                                            (this._element.classList.add(on),
                                            this._queueCallback(
                                                function () {
                                                    e._element.classList.add(nn), e._element.classList.remove(on, rn), I.trigger(e._element, "hidden.bs.toast");
                                                },
                                                this._element,
                                                this._config.animation
                                            )));
                                },
                            },
                            {
                                key: "dispose",
                                value: function () {
                                    this._clearTimeout(), this.isShown() && this._element.classList.remove(rn), _get(_getPrototypeOf(n.prototype), "dispose", this).call(this);
                                },
                            },
                            {
                                key: "isShown",
                                value: function () {
                                    return this._element.classList.contains(rn);
                                },
                            },
                            {
                                key: "_maybeScheduleHide",
                                value: function () {
                                    var e = this;
                                    this._config.autohide &&
                                        (this._hasMouseInteraction ||
                                            this._hasKeyboardInteraction ||
                                            (this._timeout = setTimeout(function () {
                                                e.hide();
                                            }, this._config.delay)));
                                },
                            },
                            {
                                key: "_onInteraction",
                                value: function (e, t) {
                                    switch (e.type) {
                                        case "mouseover":
                                        case "mouseout":
                                            this._hasMouseInteraction = t;
                                            break;
                                        case "focusin":
                                        case "focusout":
                                            this._hasKeyboardInteraction = t;
                                    }
                                    if (t) this._clearTimeout();
                                    else {
                                        var n = e.relatedTarget;
                                        this._element === n || this._element.contains(n) || this._maybeScheduleHide();
                                    }
                                },
                            },
                            {
                                key: "_setListeners",
                                value: function () {
                                    var e = this;
                                    I.on(this._element, "mouseover.bs.toast", function (t) {
                                        return e._onInteraction(t, !0);
                                    }),
                                        I.on(this._element, "mouseout.bs.toast", function (t) {
                                            return e._onInteraction(t, !1);
                                        }),
                                        I.on(this._element, "focusin.bs.toast", function (t) {
                                            return e._onInteraction(t, !0);
                                        }),
                                        I.on(this._element, "focusout.bs.toast", function (t) {
                                            return e._onInteraction(t, !1);
                                        });
                                },
                            },
                            {
                                key: "_clearTimeout",
                                value: function () {
                                    clearTimeout(this._timeout), (this._timeout = null);
                                },
                            },
                        ],
                        [
                            {
                                key: "Default",
                                get: function () {
                                    return sn;
                                },
                            },
                            {
                                key: "DefaultType",
                                get: function () {
                                    return an;
                                },
                            },
                            {
                                key: "NAME",
                                get: function () {
                                    return "toast";
                                },
                            },
                            {
                                key: "jQueryInterface",
                                value: function (e) {
                                    return this.each(function () {
                                        var t = n.getOrCreateInstance(this, e);
                                        if ("string" == typeof e) {
                                            if (void 0 === t[e]) throw new TypeError('No method named "'.concat(e, '"'));
                                            t[e](this);
                                        }
                                    });
                                },
                            },
                        ]
                    ),
                    n
                );
            })(U);
        return Y(ln), g(ln), { Alert: G, Button: Z, Carousel: he, Collapse: be, Dropdown: qe, Modal: st, Offcanvas: vt, Popover: Ft, ScrollSpy: Xt, Tab: tn, Toast: ln, Tooltip: Ht };
    });