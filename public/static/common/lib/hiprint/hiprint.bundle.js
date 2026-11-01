/**
 * jQuery Hiprint 2.5.4
 * 
 * Copyright (c) 2016-2021 www.hinnn.com. All rights reserved.
 *
 * Licensed under the LGPL or commercial licenses
 * To use it on other terms please contact us: hinnn.com@gmail.com
 *
 */

 

"use strict";

function _instanceof(left, right) { if (right != null && typeof Symbol !== "undefined" && right[Symbol.hasInstance]) { return !!right[Symbol.hasInstance](left); } else { return left instanceof right; } }

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

var hiprint = function (t) {
    var e = {};

    function n(i) {
        if (e[i]) return e[i].exports;
        var o = e[i] = {
            i: i,
            l: !1,
            exports: {}
        };
        return t[i].call(o.exports, o, o.exports, n), o.l = !0, o.exports;
    }

    return n.m = t, n.c = e, n.d = function (t, e, i) {
        n.o(t, e) || Object.defineProperty(t, e, {
            enumerable: !0,
            get: i
        });
    }, n.r = function (t) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(t, "__esModule", {
            value: !0
        });
    }, n.t = function (t, e) {
        if (1 & e && (t = n(t)), 8 & e) return t;
        if (4 & e && "object" == _typeof(t) && t && t.__esModule) return t;
        var i = Object.create(null);
        if (n.r(i), Object.defineProperty(i, "default", {
            enumerable: !0,
            value: t
        }), 2 & e && "string" != typeof t) for (var o in t) {
            n.d(i, o, function (e) {
                return t[e];
            }.bind(null, o));
        }
        return i;
    }, n.n = function (t) {
        var e = t && t.__esModule ? function () {
            return t.default;
        } : function () {
            return t;
        };
        return n.d(e, "a", e), e;
    }, n.o = function (t, e) {
        return Object.prototype.hasOwnProperty.call(t, e);
    }, n.p = "/", n(n.s = 21);
}([function (t, e, n) {
    "use strict";

    var i;
    n.d(e, "a", function () {
        return hinnn;
    }), window.hinnn = {}, hinnn.event = (i = {}, {
        on: function on(t, e) {
            i[t] || (i[t] = []), i[t].push(e);
        },
        id: 0,
        off: function off(t, e) {
            var n = i[t];

            if (n) {
                for (var o = -1, r = 0; r < n.length; r++) {
                    if (n[r] === e) {
                        o = r;
                        break;
                    }
                }

                o < 0 || i[t].splice(o, 1);
            }
        },
        trigger: function trigger(t) {
            var e = i[t];
            if (e && e.length) for (var n = Array.prototype.slice.call(arguments, 1), o = 0; o < e.length; o++) {
                e[o].apply(this, n);
            }
        },
        clear: function clear(t) {
            i[t] = [];
        },
        getId: function getId() {
            return this.id += 1, this.id;
        },
        getNameWithId: function getNameWithId(t) {
            return t + "-" + this.getId();
        }
    }), hinnn.form = {
        serialize: function serialize(t) {
            var e = $(t).serializeArray(),
                n = {};
            return $.each(e, function () {
                n[this.name] ? "[object Array]" == Object.prototype.toString.call(n[this.name]) ? n[this.name].push(this.value) : n[this.name] = [n[this.name], this.value] : n[this.name] = this.value;
            }), n;
        }
    }, hinnn.pt = {
        toPx: function toPx(t) {
            return t * (this.getDpi() / 72);
        },
        dpi: 0,
        getDpi: function getDpi() {
            if (!this.dpi) {
                var _t2 = document.createElement("DIV");

                _t2.style.cssText = "width:1in;height:1in;position:absolute;left:0px;top:0px;z-index:99;visibility:hidden", document.body.appendChild(_t2), this.dpi = _t2.offsetHeight;
            }

            return this.dpi;
        }
    }, hinnn.px = {
        toPt: function toPt(t) {
            return t * (72 / this.getDpi());
        },
        dpi: 0,
        getDpi: function getDpi() {
            if (!this.dpi) {
                var _t3 = document.createElement("DIV");

                _t3.style.cssText = "width:1in;height:1in;position:absolute;left:0px;top:0px;z-index:99;visibility:hidden", document.body.appendChild(_t3), this.dpi = _t3.offsetHeight;
            }

            return this.dpi;
        }
    }, hinnn.mm = {
        toPt: function toPt(t) {
            return 72 / 25.4 * t;
        },
        toPx: function toPx(t) {
            return hinnn.pt.toPx(hinnn.mm.toPt(t));
        }
    }, hinnn.throttle = function (t, e, n) {
        var i,
            o,
            r,
            a = null,
            p = 0;
        n || (n = {});

        var s = function s() {
            p = !1 === n.leading ? 0 : _.now(), a = null, r = t.apply(i, o), a || (i = o = null);
        };

        return function () {
            var l = _.now();

            p || !1 !== n.leading || (p = l);
            var u = e - (l - p);
            return i = this, o = arguments, u <= 0 || u > e ? (a && (clearTimeout(a), a = null), p = l, r = t.apply(i, o), a || (i = o = null)) : a || !1 === n.trailing || (a = setTimeout(s, u)), r;
        };
    }, hinnn.debounce = function (t, e, n) {
        var i,
            o,
            r,
            a,
            p,
            s = function s() {
                var l = _.now() - a;
                l < e && l >= 0 ? i = setTimeout(s, e - l) : (i = null, n || (p = t.apply(r, o), i || (r = o = null)));
            };

        return function () {
            r = this, o = arguments, a = _.now();
            var l = n && !i;
            return i || (i = setTimeout(s, e)), l && (p = t.apply(r, o), r = o = null), p;
        };
    }, hinnn.toUtf8 = function (t) {
        var e, n, i, o;

        for (e = "", i = t.length, n = 0; n < i; n++) {
            (o = t.charCodeAt(n)) >= 1 && o <= 127 ? e += t.charAt(n) : o > 2047 ? (e += String.fromCharCode(224 | o >> 12 & 15), e += String.fromCharCode(128 | o >> 6 & 63), e += String.fromCharCode(128 | o >> 0 & 63)) : (e += String.fromCharCode(192 | o >> 6 & 31), e += String.fromCharCode(128 | o >> 0 & 63));
        }

        return e;
    }, hinnn.groupBy = function (t, e, n) {
        var i = {};
        return t.forEach(function (t) {
            var o = JSON.stringify(n(t));
            i[o] || (i[o] = {
                rows: []
            }, e.forEach(function (e) {
                i[o][e] = t[e];
            })), i[o].rows.push(t);
        }), Object.keys(i).map(function (t) {
            return i[t];
        });
    }, hinnn.orderBy = function (t, e) {
        if (t.length <= 1) return t;
        var n = Math.floor(t.length / 2),
            i = t.splice(n, 1)[0],
            o = [],
            r = [];
        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
            for (var _iterator = t[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                var _n = _step.value;
                e(_n) < e(i) ? o.push(_n) : r.push(_n);
            }
        } catch (err) {
            _didIteratorError = true;
            _iteratorError = err;
        } finally {
            try {
                if (!_iteratorNormalCompletion && _iterator.return != null) {
                    _iterator.return();
                }
            } finally {
                if (_didIteratorError) {
                    throw _iteratorError;
                }
            }
        }

        return this.orderBy(o, e).concat([i], this.orderBy(r, e));
    }, hinnn.dateFormat = function (t, e) {
        if (t) try {
            var o = "string" == typeof t ? new Date(t) : t;
            var n = {
                "M+": o.getMonth() + 1,
                "d+": o.getDate(),
                "H+": o.getHours(),
                "m+": o.getMinutes(),
                "s+": o.getSeconds(),
                "q+": Math.floor((o.getMonth() + 3) / 3),
                S: o.getMilliseconds()
            };

            for (var i in /(y+)/.test(e) && (e = e.replace(RegExp.$1, (o.getFullYear() + "").substr(4 - RegExp.$1.length))), n) {
                new RegExp("(" + i + ")").test(e) && (e = e.replace(RegExp.$1, 1 == RegExp.$1.length ? n[i] : ("00" + n[i]).substr(("" + n[i]).length)));
            }

            return e;
        } catch (t) {
            return console.log(t), "";
        }
        return "";
    };
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return o;
    });

    var i = n(9),
        o = function () {
            function t() {
                this.providers = [], this.movingDistance = 1.5, this.paperHeightTrim = 1, this.text = {
                    supportOptions: [{
                        name: "title",
                        hidden: !1,
                        title: ""
                    }, {
                        name: "field",
                        hidden: !1
                    }, {
                        name: "testData",
                        hidden: !1
                    }, {
                        name: "dataType",
                        hidden: !1
                    }, {
                        name: "fontFamily",
                        hidden: !1
                    }, {
                        name: "fontSize",
                        hidden: !1
                    }, {
                        name: "fontWeight",
                        hidden: !1
                    }, {
                        name: "letterSpacing",
                        hidden: !1
                    }, {
                        name: "color",
                        hidden: !1
                    }, {
                        name: "textDecoration",
                        hidden: !1
                    }, {
                        name: "textAlign",
                        hidden: !1
                    }, {
                        name: "textContentVerticalAlign",
                        hidden: !1
                    }, {
                        name: "lineHeight",
                        hidden: !1
                    }, {
                        name: "textType",
                        hidden: !1
                    }, {
                        name: "barcodeMode",
                        hidden: !1
                    }, {
                        name: "hideTitle",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "unShowInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "optionsGroup",
                        hidden: !1
                    }, {
                        name: "borderLeft",
                        hidden: !1
                    }, {
                        name: "borderTop",
                        hidden: !1
                    }, {
                        name: "borderRight",
                        hidden: !1
                    }, {
                        name: "borderBottom",
                        hidden: !1
                    }, {
                        name: "borderWidth",
                        hidden: !1
                    }, {
                        name: "borderColor",
                        hidden: !1
                    }, {
                        name: "contentPaddingLeft",
                        hidden: !1
                    }, {
                        name: "contentPaddingTop",
                        hidden: !1
                    }, {
                        name: "contentPaddingRight",
                        hidden: !1
                    }, {
                        name: "contentPaddingBottom",
                        hidden: !1
                    }, {
                        name: "backgroundColor",
                        hidden: !1
                    }, {
                        name: "formatter",
                        hidden: !1
                    }, {
                        name: "styler",
                        hidden: !1
                    }],
                    default: {
                        fontFamily: void 0,
                        fontSize: void 0,
                        fontWeight: "",
                        letterSpacing: void 0,
                        textAlign: void 0,
                        textType: "text",
                        hideTitle: !1,
                        height: 9.75,
                        lineHeight: void 0,
                        width: 120
                    }
                }, this.image = {
                    supportOptions: [{
                        name: "field",
                        hidden: !1
                    }, {
                        name: "src",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "formatter",
                        hidden: !1
                    }, {
                        name: "styler",
                        hidden: !1
                    }],
                    default: {}
                }, this.longText = {
                    supportOptions: [{
                        name: "title",
                        hidden: !1
                    }, {
                        name: "field",
                        hidden: !1
                    }, {
                        name: "testData",
                        hidden: !1
                    }, {
                        name: "fontFamily",
                        hidden: !1
                    }, {
                        name: "fontSize",
                        hidden: !1
                    }, {
                        name: "fontWeight",
                        hidden: !1
                    }, {
                        name: "letterSpacing",
                        hidden: !1
                    }, {
                        name: "textAlign",
                        hidden: !1
                    }, {
                        name: "lineHeight",
                        hidden: !1
                    }, {
                        name: "color",
                        hidden: !1
                    }, {
                        name: "hideTitle",
                        hidden: !1
                    }, {
                        name: "longTextIndent",
                        hidden: !1
                    }, {
                        name: "leftSpaceRemoved",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "unShowInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "lHeight",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "optionsGroup",
                        hidden: !1
                    }, {
                        name: "borderLeft",
                        hidden: !1
                    }, {
                        name: "borderTop",
                        hidden: !1
                    }, {
                        name: "borderRight",
                        hidden: !1
                    }, {
                        name: "borderBottom",
                        hidden: !1
                    }, {
                        name: "borderWidth",
                        hidden: !1
                    }, {
                        name: "borderColor",
                        hidden: !1
                    }, {
                        name: "contentPaddingLeft",
                        hidden: !1
                    }, {
                        name: "contentPaddingTop",
                        hidden: !1
                    }, {
                        name: "contentPaddingRight",
                        hidden: !1
                    }, {
                        name: "contentPaddingBottom",
                        hidden: !1
                    }, {
                        name: "backgroundColor",
                        hidden: !1
                    }, {
                        name: "formatter",
                        hidden: !1
                    }, {
                        name: "styler",
                        hidden: !1
                    }],
                    default: {
                        fontFamily: void 0,
                        fontSize: void 0,
                        fontWeight: "",
                        letterSpacing: void 0,
                        textAlign: void 0,
                        hideTitle: !1,
                        height: 42,
                        lineHeight: void 0,
                        width: 550
                    }
                }, this.table = {
                    supportOptions: [{
                        name: "field",
                        hidden: !1
                    }, {
                        name: "fontFamily",
                        hidden: !1
                    }, {
                        name: "fontSize",
                        hidden: !1
                    }, {
                        name: "lineHeight",
                        hidden: !1
                    }, {
                        name: "textAlign",
                        hidden: !1
                    }, {
                        name: "gridColumns",
                        hidden: !1
                    }, {
                        name: "gridColumnsGutter",
                        hidden: !1
                    }, {
                        name: "tableBorder",
                        hidden: !1
                    }, {
                        name: "tableHeaderBorder",
                        hidden: !1
                    }, {
                        name: "tableHeaderCellBorder",
                        hidden: !1
                    }, {
                        name: "tableHeaderRowHeight",
                        hidden: !1
                    }, {
                        name: "tableHeaderBackground",
                        hidden: !1
                    }, {
                        name: "tableHeaderFontSize",
                        hidden: !1
                    }, {
                        name: "tableHeaderFontWeight",
                        hidden: !1
                    }, {
                        name: "tableBodyRowHeight",
                        hidden: !1
                    }, {
                        name: "tableBodyRowBorder",
                        hidden: !1
                    }, {
                        name: "tableBodyCellBorder",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "lHeight",
                        hidden: !1
                    }, {
                        name: "autoCompletion",
                        hidden: !1
                    }, {
                        name: "columns",
                        hidden: !1
                    }, {
                        name: "styler",
                        hidden: !1
                    }, {
                        name: "rowStyler",
                        hidden: !1
                    }, {
                        name: "tableFooterRepeat",
                        hidden: !1
                    }, {
                        name: "footerFormatter",
                        hidden: !1
                    }, {
                        name: "gridColumnsFooterFormatter",
                        hidden: !1
                    }],
                    default: {
                        fontFamily: void 0,
                        fontSize: void 0,
                        fontWeight: "",
                        textAlign: void 0,
                        tableBorder: void 0,
                        tableHeaderBorder: void 0,
                        tableHeaderCellBorder: void 0,
                        tableHeaderBackground: void 0,
                        tableHeaderRowHeight: void 0,
                        tableHeaderFontWeight: void 0,
                        tableBodyCellBorder: void 0,
                        tableBodyRowHeight: void 0,
                        letterSpacing: "",
                        lineHeight: void 0,
                        width: 550
                    }
                }, this.tableCustom = {
                    supportOptions: [{
                        name: "field",
                        hidden: !1
                    }, {
                        name: "fontFamily",
                        hidden: !1
                    }, {
                        name: "fontSize",
                        hidden: !1
                    }, {
                        name: "textAlign",
                        hidden: !1
                    }, {
                        name: "tableBorder",
                        hidden: !1
                    }, {
                        name: "tableHeaderBorder",
                        hidden: !1
                    }, {
                        name: "tableHeaderCellBorder",
                        hidden: !1
                    }, {
                        name: "tableHeaderRowHeight",
                        hidden: !1
                    }, {
                        name: "tableHeaderFontSize",
                        hidden: !1
                    }, {
                        name: "tableHeaderFontWeight",
                        hidden: !1
                    }, {
                        name: "tableHeaderBackground",
                        hidden: !1
                    }, {
                        name: "tableBodyRowHeight",
                        hidden: !1
                    }, {
                        name: "tableBodyRowBorder",
                        hidden: !1
                    }, {
                        name: "tableBodyCellBorder",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "lHeight",
                        hidden: !1
                    }, {
                        name: "autoCompletion",
                        hidden: !1
                    }, {
                        name: "tableFooterRepeat",
                        hidden: !1
                    }],
                    default: {
                        fontFamily: void 0,
                        fontSize: void 0,
                        fontWeight: "",
                        textAlign: void 0,
                        tableBorder: void 0,
                        tableHeaderBorder: void 0,
                        tableHeaderCellBorder: void 0,
                        tableHeaderBackground: void 0,
                        tableHeaderRowHeight: void 0,
                        tableHeaderFontWeight: void 0,
                        tableBodyCellBorder: void 0,
                        tableBodyRowHeight: void 0,
                        letterSpacing: "",
                        lineHeight: void 0,
                        width: 550
                    }
                }, this.hline = {
                    supportOptions: [{
                        name: "borderColor",
                        hidden: !1
                    }, {
                        name: "borderWidth",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "borderStyle",
                        hidden: !1
                    }],
                    default: {
                        borderWidth: .75,
                        height: 9,
                        width: 90
                    }
                }, this.vline = {
                    supportOptions: [{
                        name: "borderColor",
                        hidden: !1
                    }, {
                        name: "borderWidth",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "borderStyle",
                        hidden: !1
                    }],
                    default: {
                        borderWidth: void 0,
                        height: 90,
                        width: 9
                    }
                }, this.rect = {
                    supportOptions: [{
                        name: "borderColor",
                        hidden: !1
                    }, {
                        name: "borderWidth",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "borderStyle",
                        hidden: !1
                    }],
                    default: {
                        borderWidth: void 0,
                        height: 90,
                        width: 90
                    }
                }, this.oval = {
                    supportOptions: [{
                        name: "borderColor",
                        hidden: !1
                    }, {
                        name: "borderWidth",
                        hidden: !1
                    }, {
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "transform",
                        hidden: !1
                    }, {
                        name: "borderStyle",
                        hidden: !1
                    }],
                    default: {
                        borderWidth: void 0,
                        height: 90,
                        width: 90
                    }
                }, this.html = {
                    supportOptions: [{
                        name: "showInPage",
                        hidden: !1
                    }, {
                        name: "unShowInPage",
                        hidden: !1
                    }, {
                        name: "fixed",
                        hidden: !1
                    }, {
                        name: "axis",
                        hidden: !1
                    }, {
                        name: "formatter",
                        hidden: !1
                    }],
                    default: {
                        height: 90,
                        width: 90
                    }
                }, this.tableColumn = {
                    supportOptions: [{
                        name: "title",
                        hidden: !1
                    }, {
                        name: "align",
                        hidden: !1
                    }, {
                        name: "halign",
                        hidden: !1
                    }, {
                        name: "vAlign",
                        hidden: !1
                    }, {
                        name: "paddingLeft",
                        hidden: !1
                    }, {
                        name: "paddingRight",
                        hidden: !1
                    }, {
                        name: "formatter2",
                        hidden: !1
                    }, {
                        name: "styler2",
                        hidden: !1
                    }],
                    default: {
                        height: 90,
                        width: 90
                    }
                };
            }

            return t.prototype.init = function (t) {
                t && $.extend(this, t);
            }, Object.defineProperty(t, "instance", {
                get: function get() {
                    return t._instance || (t._instance = new t(), window.HIPRINT_CONFIG && $.extend(t._instance, HIPRINT_CONFIG), t._instance.optionItems && t._instance.optionItems.forEach(function (t) {
                        i.a.registerItem(t);
                    })), t._instance;
                },
                enumerable: !0,
                configurable: !0
            }), t;
        }();
}, function (t, e, n) {
    "use strict";

    var i = function () {
        function t(t) {
            this.printElement = t;
        }

        return t.prototype.updatePosition = function (t, e) {
            this.left = t, this.top = e;
        }, t;
    }();

    n.d(e, "a", function () {
        return o;
    });

    var o = function () {
        function t() {
            this.printTemplateContainer = {}, this.A1 = {
                width: 841,
                height: 594
            }, this.A2 = {
                width: 420,
                height: 594
            }, this.A3 = {
                width: 420,
                height: 297
            }, this.A4 = {
                width: 210,
                height: 297
            }, this.A5 = {
                width: 210,
                height: 148
            }, this.A6 = {
                width: 105,
                height: 148
            }, this.A7 = {
                width: 105,
                height: 74
            }, this.A8 = {
                width: 52,
                height: 74
            }, this.B1 = {
                width: 1e3,
                height: 707
            }, this.B2 = {
                width: 500,
                height: 707
            }, this.B3 = {
                width: 500,
                height: 353
            }, this.B4 = {
                width: 250,
                height: 353
            }, this.B5 = {
                width: 250,
                height: 176
            }, this.B6 = {
                width: 125,
                height: 176
            }, this.B7 = {
                width: 125,
                height: 88
            }, this.B8 = {
                width: 62,
                height: 88
            }, this.dragLengthCNum = function (t, e) {
                var n = .75 * t;
                return e && (e = e), Math.round(n / e) * e;
            };
        }

        return Object.defineProperty(t, "instance", {
            get: function get() {
                return this._instance || (this._instance = new t()), this._instance;
            },
            enumerable: !0,
            configurable: !0
        }), t.prototype.getDragingPrintElement = function () {
            return t.instance.dragingPrintElement;
        }, t.prototype.setDragingPrintElement = function (e) {
            t.instance.dragingPrintElement = new i(e);
        }, t.prototype.guid = function () {
            return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (t) {
                var e = 16 * Math.random() | 0;
                return ("x" == t ? e : 3 & e | 8).toString(16);
            });
        }, t.prototype.imageToBase64 = function (t) {
            if (-1 == $(t).attr("src").indexOf("base64")) try {
                var e = document.createElement("canvas"),
                    n = new Image();
                n.src = t.attr("src"), e.width = n.width, e.height = n.height, e.getContext("2d").drawImage(n, 0, 0), t.attr("src", e.toDataURL("image/png"));
            } catch (e) {
                try {
                    this.xhrLoadImage(t);
                } catch (t) {
                    console.log(t);
                }
            }
        }, t.prototype.xhrLoadImage = function (t) { }, t.prototype.transformImg = function (t) {
            var e = this;
            t.map(function (t, n) {
                e.imageToBase64($(n));
            });
        }, t.prototype.getPrintTemplateById = function (e) {
            return t.instance.printTemplateContainer[e];
        }, t.prototype.setPrintTemplateById = function (e, n) {
            return t.instance.printTemplateContainer[e] = n;
        }, t;
    }();
}, function (t, e, n) {
    "use strict";

    var i = function () {
        return function () { };
    }();

    n.d(e, "a", function () {
        return o;
    });

    var o = function () {
        function t(t) {
            t = t || {}, this.left = t.left, this.top = t.top, this.topInDesign = this.top, this.height = t.height, this.width = t.width, this.init(t);
        }

        return t.prototype.setDefault = function (t) {
            this.defaultOptions = t, this.initSize();
        }, t.prototype.initSize = function () {
            this.width || this.setWidth(this.defaultOptions.width), this.height || this.setHeight(this.defaultOptions.height);
        }, t.prototype.initSizeByHtml = function (t, e) {
            this.width || this.setWidth(t), this.height || this.setHeight(e);
        }, t.prototype.getLeft = function () {
            return this.left;
        }, t.prototype.displayLeft = function () {
            return this.left + "pt";
        }, t.prototype.setLeft = function (t) {
            null != t && (this.left = t);
        }, t.prototype.getTop = function () {
            return this.top;
        }, t.prototype.getTopInDesign = function () {
            return this.topInDesign;
        }, t.prototype.displayTop = function () {
            return this.top + "pt";
        }, t.prototype.setTop = function (t) {
            null != t && (this.top = t);
        }, t.prototype.copyDesignTopFromTop = function () {
            this.topInDesign = this.top;
        }, t.prototype.getHeight = function () {
            return this.height;
        }, t.prototype.displayHeight = function () {
            return this.height + "pt";
        }, t.prototype.setHeight = function (t) {
            null != t && (this.height = t);
        }, t.prototype.getWidth = function () {
            return this.width;
        }, t.prototype.displayWidth = function () {
            return this.width + "pt";
        }, t.prototype.setWidth = function (t) {
            null != t && (this.width = t);
        }, t.prototype.getValueFromOptionsOrDefault = function (t) {
            return null == this[t] ? this.defaultOptions[t] : this[t];
        }, t.prototype.getPrintElementOptionEntity = function () {
            var t = new i(),
                e = this;
            return Object.keys(this).filter(function (t) {
                return "topInDesign" != t;
            }).forEach(function (n) {
                if ("number" != typeof e[n] && "string" != typeof e[n] && _typeof(e[n]) != _typeof(!0) || (t[n] = e[n]), "style" == n) {
                    t.style = {};
                    var i = e[n];
                    if (i) Object.keys(i).forEach(function (e) {
                        "number" != typeof i[e] && "string" != typeof i[e] || (t.style[e] = i[e]);
                    });
                }
            }), t;
        }, t.prototype.init = function (t) {
            var e = this;
            t && Object.keys(t).forEach(function (n) {
                e[n] = t[n];
            });
        }, t;
    }();
}, function (module, __webpack_exports__, __webpack_require__) {
    "use strict";

    __webpack_require__.d(__webpack_exports__, "a", function () {
        return BasePrintElement;
    });

    var _entity_PrintElementEntity__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(17),
        _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(1),
        _print_element_option_PrintElementOptionItemManager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(9),
        _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(6),
        _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(0),
        _PrintReferenceElement__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(8),
        _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(2),
        BasePrintElement = function () {
            function BasePrintElement(t) {
                this.printElementType = t, this.id = _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__.a.instance.guid();
            }

            return BasePrintElement.prototype.getConfigOptionsByName = function (t) {
                return _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance[t];
            }, BasePrintElement.prototype.getProxyTarget = function (t) {
                t && this.SetProxyTargetOption(t);
                var e = this.getData(),
                    n = this.createTarget(this.getTitle(), e);
                return this.updateTargetSize(n), this.css(n, e), n;
            }, BasePrintElement.prototype.SetProxyTargetOption = function (t) {
                this.options.getPrintElementOptionEntity();
                $.extend(this.options, t);
            }, BasePrintElement.prototype.showInPage = function (t, e) {
                var n = this.options.showInPage,
                    i = this.options.unShowInPage;

                if (n) {
                    if ("first" == n) return 0 == t;
                    if (t == e - 1 && "last" == i) return !1;
                    if ("odd" == n) return (0 != t || "first" != i) && t % 2 == 0;
                    if ("even" == n) return t % 2 == 1;
                    if ("last" == n) return t == e - 1;
                }

                return (0 != t || "first" != i) && (t != e - 1 || "last" != i);
            }, BasePrintElement.prototype.setTemplateId = function (t) {
                this.templateId = t;
            }, BasePrintElement.prototype.setPanel = function (t) {
                this.panel = t;
            }, BasePrintElement.prototype.getField = function () {
                return this.options.field || this.printElementType.field;
            }, BasePrintElement.prototype.getTitle = function () {
                return this.printElementType.title;
            }, BasePrintElement.prototype.updateSizeAndPositionOptions = function (t, e, n, i) {
                this.options.setLeft(t), this.options.setTop(e), this.options.copyDesignTopFromTop(), this.options.setWidth(n), this.options.setHeight(i), _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_4__.a.event.trigger("hiprintTemplateDataChanged_" + this.templateId);
            }, BasePrintElement.prototype.initSizeByHtml = function (t) {
                if (t && t.length) {
                    this.createTempContainer();
                    var e = t.clone();
                    this.getTempContainer().append(e), this.options.initSizeByHtml(parseInt(_assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_4__.a.px.toPt(e.width()).toString()), parseInt(_assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_4__.a.px.toPt(e.height()).toString())), this.removeTempContainer();
                }
            }, BasePrintElement.prototype.updateTargetSize = function (t) {
                t.css("width", this.options.displayWidth()), t.css("height", this.options.displayHeight());
            }, BasePrintElement.prototype.updateTargetWidth = function (t) {
                t.css("width", this.options.displayWidth());
            }, BasePrintElement.prototype.getDesignTarget = function (t) {
                var e = this;
                return this.designTarget = this.getHtml(t)[0].target, this.designPaper = t, this.designTarget.click(function () {
                    _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_4__.a.event.trigger(e.getPrintElementSelectEventKey(), {
                        printElement: e
                    });
                }), this.designTarget;
            }, BasePrintElement.prototype.getPrintElementSelectEventKey = function () {
                return "PrintElementSelectEventKey_" + this.templateId;
            }, BasePrintElement.prototype.design = function (t, e) {
                var n = this;
                this.designTarget.hidraggable({
                    axis: n.options.axis && t && t.axisEnabled ? n.options.axis : void 0,
                    onDrag: function onDrag(t, i, o) {
                        n.updateSizeAndPositionOptions(i, o), n.createLineOfPosition(e);
                    },
                    moveUnit: "pt",
                    minMove: _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.movingDistance,
                    onBeforeDrag: function onBeforeDrag(t) {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__.a.instance.draging = !0, n.designTarget.focus(), n.createLineOfPosition(e);
                    },
                    onStopDrag: function onStopDrag(t) {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__.a.instance.draging = !1, n.removeLineOfPosition();
                    }
                }), this.designTarget.hireizeable({
                    showPoints: n.getReizeableShowPoints(),
                    onBeforeResize: function onBeforeResize() {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__.a.instance.draging = !0;
                    },
                    onResize: function onResize(t, i, o, r, a) {
                        n.onResize(t, i, o, r, a), n.createLineOfPosition(e);
                    },
                    onStopResize: function onStopResize() {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__.a.instance.draging = !1, n.removeLineOfPosition();
                    }
                }), this.bingCopyEvent(this.designTarget), this.bingKeyboardMoveEvent(this.designTarget, e);
            }, BasePrintElement.prototype.getPrintElementEntity = function (t) {
                return t ? new _entity_PrintElementEntity__WEBPACK_IMPORTED_MODULE_0__.a(void 0, this.options.getPrintElementOptionEntity(), this.printElementType.getPrintElementTypeEntity()) : new _entity_PrintElementEntity__WEBPACK_IMPORTED_MODULE_0__.a(this.printElementType.tid, this.options.getPrintElementOptionEntity());
            }, BasePrintElement.prototype.submitOption = function () {
                var t = this;
                this.getPrintElementOptionItems().forEach(function (e) {
                    var n = e.getValue();
                    n && "object" == _typeof(n) ? Object.keys(n).forEach(function (e) {
                        t.options[e] = n[e];
                    }) : t.options[e.name] = n;
                }), this.updateDesignViewFromOptions(), _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_4__.a.event.trigger("hiprintTemplateDataChanged_" + this.templateId);
            }, BasePrintElement.prototype.getReizeableShowPoints = function () {
                return ["s", "e"];
            }, BasePrintElement.prototype.onResize = function (t, e, n, i, o) {
                this.updateSizeAndPositionOptions(o, i, n, e);
            }, BasePrintElement.prototype.getOrderIndex = function () {
                return this.options.getTop();
            }, BasePrintElement.prototype.getHtml = function (t, e, n) {
                var i = 0;
                this.setCurrenttemplateData(e);
                var o = [],
                    r = this.getBeginPrintTopInPaperByReferenceElement(t),
                    a = t.getPaperFooter(i);
                this.isHeaderOrFooter() || this.isFixed() || r > a && (o.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_3__.a({
                    target: void 0,
                    printLine: void 0
                })), r = r - a + t.paperHeader, i++ , a = t.getPaperFooter(i));
                var p = this.getData(e),
                    s = this.createTarget(this.getTitle(), p, n);
                return this.updateTargetSize(s), this.css(s, p), s.css("position", "absolute"), s.css("left", this.options.displayLeft()), s.css("top", r + "pt"), o.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_3__.a({
                    target: s,
                    printLine: r + this.options.getHeight()
                })), o;
            }, BasePrintElement.prototype.getHtml2 = function (t, e, n) {
                var i = 0;
                this.setCurrenttemplateData(e);
                var o = [],
                    r = this.getBeginPrintTopInPaperByReferenceElement(t),
                    a = t.getPaperFooter(i);
                this.isHeaderOrFooter() || this.isFixed() || (r > a && (o.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_3__.a({
                    target: void 0,
                    printLine: void 0
                })), r = r - a + t.paperHeader, i++ , a = t.getPaperFooter(i)), r <= a && r + this.options.getHeight() > a && (o.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_3__.a({
                    target: void 0,
                    printLine: void 0
                })), r = t.paperHeader, i++ , a = t.getPaperFooter(i)));
                var p = this.getData(e),
                    s = this.createTarget(this.getTitle(), p);
                return this.updateTargetSize(s), this.css(s, p), s.css("position", "absolute"), s.css("left", this.options.displayLeft()), s.css("top", r + "pt"), o.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_3__.a({
                    target: s,
                    printLine: r + this.options.getHeight(),
                    referenceElement: new _PrintReferenceElement__WEBPACK_IMPORTED_MODULE_5__.a({
                        top: this.options.getTop(),
                        left: this.options.getLeft(),
                        height: this.options.getHeight(),
                        width: this.options.getWidth(),
                        beginPrintPaperIndex: t.index,
                        bottomInLastPaper: r + this.options.getHeight(),
                        printTopInPaper: r
                    })
                })), o;
            }, BasePrintElement.prototype.getBeginPrintTopInPaperByReferenceElement = function (t) {
                var e = this.options.getTop();
                return this.isHeaderOrFooter() || this.isFixed() ? e : t.referenceElement.isPositionLeftOrRight(e) ? t.referenceElement.printTopInPaper + (e - t.referenceElement.top) : t.referenceElement.bottomInLastPaper + (e - (t.referenceElement.top + t.referenceElement.height));
            }, BasePrintElement.prototype.css = function (t, e) {
                var n = this,
                    i = [],
                    o = this.getConfigOptions();

                if (o) {
                    var r = o.supportOptions;
                    r && r.forEach(function (e) {
                        var o = _print_element_option_PrintElementOptionItemManager__WEBPACK_IMPORTED_MODULE_2__.a.getItem(e.name);

                        if (o && o.css) {
                            var r = o.css(t, n.options.getValueFromOptionsOrDefault(e.name));
                            r && i.push(r);
                        }
                    });
                }

                this.stylerCss(t, e);
            }, BasePrintElement.prototype.stylerCss = function (t, e) {
                var n = this.getStyler();

                if (n) {
                    var i = n(e, this.options, t, this._currenttemplateData);
                    if (i) Object.keys(i).forEach(function (e) {
                        t.css(e, i[e]);
                    });
                }
            }, BasePrintElement.prototype.getData = function (t) {
                return t ? t[this.getField()] || "" : this.printElementType.getData();
            }, BasePrintElement.prototype.getPrintElementOptionItems = function () {
                if (this._printElementOptionItems) return this._printElementOptionItems;
                var t = [],
                    e = this.getConfigOptions();

                if (e) {
                    var n = e.supportOptions;
                    n && n.filter(function (t) {
                        return !t.hidden;
                    }).forEach(function (e) {
                        var n = _print_element_option_PrintElementOptionItemManager__WEBPACK_IMPORTED_MODULE_2__.a.getItem(e.name);

                        t.push(n);
                    });
                }

                return this._printElementOptionItems = this.filterOptionItems(t.concat()), this._printElementOptionItems;
            }, BasePrintElement.prototype.getPrintElementOptionItemsByName = function (t) {
                var e = [],
                    n = this.getConfigOptionsByName(t);

                if (n) {
                    var i = n.supportOptions;
                    i && i.filter(function (t) {
                        return !t.hidden;
                    }).forEach(function (t) {
                        var n = _print_element_option_PrintElementOptionItemManager__WEBPACK_IMPORTED_MODULE_2__.a.getItem(t.name);

                        e.push(n);
                    });
                }

                return e.concat();
            }, BasePrintElement.prototype.filterOptionItems = function (t) {
                return this.printElementType.field ? t.filter(function (t) {
                    return "field" != t.name;
                }) : t;
            }, BasePrintElement.prototype.createTempContainer = function () {
                this.removeTempContainer(), $("body").append($('<div class="hiprint_temp_Container hiprint-printPaper" style="overflow:hidden;height: 0px;box-sizing: border-box;"></div>'));
            }, BasePrintElement.prototype.removeTempContainer = function () {
                $(".hiprint_temp_Container").remove();
            }, BasePrintElement.prototype.getTempContainer = function () {
                return $(".hiprint_temp_Container");
            }, BasePrintElement.prototype.isHeaderOrFooter = function () {
                return this.options.getTopInDesign() < this.panel.paperHeader || this.options.getTopInDesign() >= this.panel.paperFooter;
            }, BasePrintElement.prototype.delete = function () {
                this.designTarget && this.designTarget.remove();
            }, BasePrintElement.prototype.setCurrenttemplateData = function (t) {
                this._currenttemplateData = t;
            }, BasePrintElement.prototype.isFixed = function () {
                return this.options.fixed;
            }, BasePrintElement.prototype.onRendered = function (t, e) {
                this.printElementType && this.printElementType.onRendered && this.printElementType.onRendered(e, this.options, t.getTarget());
            }, BasePrintElement.prototype.createLineOfPosition = function (t) {
                var e = $(".toplineOfPosition" + this.id),
                    n = $(".leftlineOfPosition" + this.id),
                    i = $(".rightlineOfPosition" + this.id),
                    o = $(".bottomlineOfPosition" + this.id);
                if (e.length ? e.css("top", this.options.displayTop()) : ((e = $('<div class="toplineOfPosition' + this.id + '" style="border:0;border-top:1px dashed  rgb(169, 169, 169);position: absolute; width: 100%;"></div>')).css("top", this.options.displayTop()), e.css("width", t.displayWidth()), this.designTarget.parents(".hiprint-printPaper-content").append(e)), n.length) n.css("left", this.options.displayLeft()); else {
                    var r = $('<div class="leftlineOfPosition' + this.id + '" style="border:0;border-left:1px dashed  rgb(169, 169, 169);position: absolute;height: 100%;"></div>');
                    r.css("left", this.options.displayLeft()), r.css("height", t.displayHeight()), this.designTarget.parents(".hiprint-printPaper-content").append(r);
                }
                if (i.length) i.css("left", this.options.getLeft() + this.options.getWidth() + "pt"); else {
                    var a = $('<div class="rightlineOfPosition' + this.id + '" style="border:0;border-left:1px dashed  rgb(169, 169, 169);position: absolute;height: 100%;"></div>');
                    a.css("left", this.options.getLeft() + this.options.getWidth() + "pt"), a.css("height", t.displayHeight()), this.designTarget.parents(".hiprint-printPaper-content").append(a);
                }
                if (o.length) o.css("top", this.options.getTop() + this.options.getHeight() + "pt"); else {
                    var p = $('<div class="bottomlineOfPosition' + this.id + '" style="border:0;border-top:1px dashed  rgb(169, 169, 169);position: absolute;width: 100%;"></div>');
                    p.css("top", this.options.getTop() + this.options.getHeight() + "pt"), p.css("width", t.displayWidth()), this.designTarget.parents(".hiprint-printPaper-content").append(p);
                }
            }, BasePrintElement.prototype.removeLineOfPosition = function () {
                $(".toplineOfPosition" + this.id).remove(), $(".leftlineOfPosition" + this.id).remove(), $(".rightlineOfPosition" + this.id).remove(), $(".bottomlineOfPosition" + this.id).remove();
            }, BasePrintElement.prototype.getFields = function () {
                var t = this.printElementType.getFields();
                return t || (t = _HiPrintlib__WEBPACK_IMPORTED_MODULE_6__.a.instance.getPrintTemplateById(this.templateId).getFields());
            }, BasePrintElement.prototype.bingCopyEvent = function (t) { }, BasePrintElement.prototype.getFormatter = function () {
                var formatter = void 0;
                if (this.printElementType.formatter && (formatter = this.printElementType.formatter), this.options.formatter) try {
                    var s = "formatter=" + this.options.formatter;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return formatter;
            }, BasePrintElement.prototype.getStyler = function () {
                var fnstyler = void 0;
                if (this.printElementType.styler && (fnstyler = this.printElementType.styler), this.options.styler) try {
                    var s = "fnstyler=" + this.options.styler;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return fnstyler;
            }, BasePrintElement.prototype.bingKeyboardMoveEvent = function (t, e) {
                var n = this,
                    i = void 0,
                    o = void 0;
                t.attr("tabindex", "1"), t.keydown(function (r) {
                    switch (r.keyCode) {
                        case 37:
                            i = n.options.getLeft(), n.updateSizeAndPositionOptions(i - _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.movingDistance), t.css("left", n.options.displayLeft()), n.createLineOfPosition(e), r.preventDefault();
                            break;

                        case 38:
                            o = n.options.getTop(), n.updateSizeAndPositionOptions(void 0, o - _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.movingDistance), t.css("top", n.options.displayTop()), n.createLineOfPosition(e), r.preventDefault();
                            break;

                        case 39:
                            i = n.options.getLeft(), n.updateSizeAndPositionOptions(i + _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.movingDistance), t.css("left", n.options.displayLeft()), n.createLineOfPosition(e), r.preventDefault();
                            break;

                        case 40:
                            o = n.options.getTop(), n.updateSizeAndPositionOptions(void 0, o + _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.movingDistance), t.css("top", n.options.displayTop()), n.createLineOfPosition(e), r.preventDefault();
                    }
                });
            }, BasePrintElement.prototype.inRect = function (t) {
                var e = this.designTarget.offset().left,
                    n = this.designTarget.offset().top;
                return t.minX < e && t.minY < n && t.maxX > e && t.maxY > n;
            }, BasePrintElement.prototype.multipleSelect = function (t) {
                t ? this.designTarget.addClass("multipleSelect") : this.designTarget.removeClass("multipleSelect");
            }, BasePrintElement.prototype.updatePositionByMultipleSelect = function (t, e) {
                this.updateSizeAndPositionOptions(t + this.options.getLeft(), e + this.options.getTop()), this.designTarget.css("left", this.options.displayLeft()), this.designTarget.css("top", this.options.displayTop());
            }, BasePrintElement;
        }();
}, function (t, e, n) {
    "use strict";

    var i = function () {
        function t() { }

        return t.prototype.init = function (t) {
            this.target = $('<input type="text" class="hitable-editor-text" value="" />'), t.getTarget().append(this.target), this.target.focus();
        }, t.prototype.getValue = function () {
            return this.target.val();
        }, t.prototype.setValue = function (t) {
            this.target.val(t);
        }, t.prototype.destroy = function () {
            this.target.remove();
        }, t;
    }(),
        o = function () {
            function t() {
                this.text = new i();
            }

            return Object.defineProperty(t, "Instance", {
                get: function get() {
                    return t._instance || (t._instance = new t()), t._instance;
                },
                enumerable: !0,
                configurable: !0
            }), t;
        }(),
        r = function () {
            function t() { }

            return Object.defineProperty(t, "Instance", {
                get: function get() {
                    return o._instance || (t._instance = new t()), t._instance;
                },
                enumerable: !0,
                configurable: !0
            }), t.prototype.createEditor = function (t) {
                return $.extend({}, o.Instance[t]);
            }, t;
        }(),
        a = n(10),
        p = n(14),
        s = n(11),
        l = function () {
            function t() { }

            return t.prototype.init = function (t, e) {
                var n = this;
                this.tableOptions = e, this.title = t.title, this.field = t.field, t.getTarget().unbind("dblclick.hitable").bind("dblclick.hitable", function () {
                    t.isEditing = !0, n.beginEdit(t);
                });
            }, t.prototype.getDisplayHtml = function () {
                return this.title;
            }, t.prototype.beginEdit = function (t) {
                var e = this;
                this.editor = r.Instance.createEditor("text"), t.getTarget().html(""), this.editor.init(t), (this.title || this.field) && (this.tableOptions.options.isEnableEditField ? this.editor.setValue((this.title || "") + "#" + (this.field || "")) : this.editor.setValue(this.title || "")), $(this.editor.target).keydown(function (n) {
                    13 == n.keyCode && e.endEdit(t);
                }), $(this.editor.target).blur(function (n) {
                    e.endEdit(t);
                }), this.tableOptions.editingCell && this.tableOptions.editingCell.id != t.id && this.tableOptions.editingCell.innerElement.endEdit(this.tableOptions.editingCell), this.tableOptions.editingCell = t;
            }, t.prototype.endEdit = function (t) {
                var e = this.editor.getValue();
                if (e) {
                    if (this.tableOptions.options.isEnableEditField) {
                        var n = e.split("#");
                        t.title = this.title = n[0], n.length > 0 && (t.field = this.field = n[1]);
                    } else t.title = this.title = e;
                } else this.tableOptions.options.isEnableEditField ? (t.title = this.title = "", t.field = this.field = "") : t.title = this.title = "";
                this.editor.destroy(), t.getTarget().html(this.title);
            }, t;
        }(),
        u = function () {
            return function (t) {
                this.title = t.title, this.field = t.field, this.width = t.width, this.align = t.align, this.halign = t.halign, this.vAlign = t.vAlign, this.colspan = t.colspan, this.rowspan = t.rowspan, this.checked = t.checked, this.columnId = t.columnId, this.formatter2 = t.formatter2, this.styler2 = t.styler2;
            };
        }(),
        d = function () {
            function t() {
                this.id = s.a.createId();
            }

            return t.prototype.init = function (t, e, n, i) {
                this.isHead = i, this.rowId = n, this.isEditing = !1;
                var o = /^[0-9]*$/;
                this.target = t, this.tableOptions = e;
                var r = this.target.attr("colspan");
                this.colspan = o.test(r) ? parseInt(r) : 1;
                var a = this.target.attr("rowspan");
                this.rowspan = o.test(a) ? parseInt(a) : 1, this.initEvent(), this.isHead && this.initInnerEelement();
            }, t.prototype.beginEdit = function () {
                if (!this.isEditing && this.tableOptions.isEnableEdit && this.tableOptions.onBeforEdit(this)) {
                    var t = this.getValue();
                    this.editor = r.Instance.createEditor("text"), this.isEditing = !0, this.tableOptions.editingCell = this, this.target.html(""), this.editor.init(this), this.editor.setValue(t);
                }
            }, t.prototype.endEdit = function () {
                this.isEditing = !1;
                var t = this.editor.getValue();
                this.editor.destroy(), this.target.html(t);
            }, t.prototype.getTarget = function () {
                return this.target;
            }, t.prototype.getValue = function () {
                return this.target.html();
            }, t.prototype.setValue = function (t) { }, t.prototype.initInnerEelement = function () {
                this.innerElement = new l(), this.innerElement.init(this, this.tableOptions);
            }, t.prototype.initEvent = function () { }, t.prototype.isXYinCell = function (t, e) {
                var n = new a.b({
                    x: t,
                    y: e,
                    height: 0,
                    width: 0
                });
                return this.isOverlap(n);
            }, t.prototype.getTableRect = function () {
                return new a.b({
                    x: this.target.offset().left,
                    y: this.target.offset().top,
                    height: this.target[0].offsetHeight,
                    width: this.target[0].offsetWidth
                });
            }, t.prototype.isOverlap = function (t) {
                var e = this.getTableRect();
                return t.x + t.width > e.x && e.x + e.width > t.x && t.y + t.height > e.y && e.y + e.height > t.y;
            }, t.prototype.isInRect = function (t) {
                var e = t.rect,
                    n = this.getTableRect();

                if (e.x + e.width > n.x && n.x + n.width > e.x && e.y + e.height > n.y && n.y + n.height > e.y) {
                    var i = p.a.mergeRect(e, n);
                    return JSON.stringify(e) == JSON.stringify(i) || (t.changed = !0, t.rect = i, !0);
                }

                return !1;
            }, t.prototype.isSelected = function () {
                return this.target.hasClass("selected");
            }, t.prototype.select = function () {
                this.target.addClass("selected");
            }, t.prototype.isHeader = function () {
                return !1;
            }, t.prototype.setAlign = function (t) {
                this.align = t, t ? this.target.css("text-align", t) : this.target[0].style.textAlign = "";
            }, t.prototype.setVAlign = function (t) {
                this.vAlign = t, t ? this.target.css("vertical-align", t) : this.target[0].style.verticalAlign = "";
            }, t.prototype.getEntity = function () {
                return new u(this);
            }, t;
        }();

    n.d(e, "a", function () {
        return f;
    });

    var _c,
        h = (_c = function c(t, e) {
            return (_c = Object.setPrototypeOf || _instanceof({
                __proto__: []
            }, Array) && function (t, e) {
                t.__proto__ = e;
            } || function (t, e) {
                for (var n in e) {
                    e.hasOwnProperty(n) && (t[n] = e[n]);
                }
            })(t, e);
        }, function (t, e) {
            function n() {
                this.constructor = t;
            }

            _c(t, e), t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
        }),
        f = function (t) {
            function e(e) {
                var n = this;
                return e = e || {}, (n = t.call(this) || this).width = e.width ? parseFloat(e.width.toString()) : 100, n.title = e.title, n.descTitle = e.descTitle, n.field = e.field, n.fixed = e.fixed, n.rowspan = e.rowspan ? parseInt(e.rowspan) : 1, n.colspan = e.colspan ? parseInt(e.colspan) : 1, n.align = e.align, n.halign = e.halign, n.vAlign = e.vAlign, n.formatter = e.formatter, n.styler = e.styler, n.formatter2 = e.formatter2, n.styler2 = e.styler2, n.checkbox = e.checkbox, n.checked = 0 != e.checked, n.columnId = e.columnId || e.field, n;
            }

            return h(e, t), e.prototype.css = function (t) { }, e;
        }(d);
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return i;
    });

    var i = function () {
        return function (t) {
            this.printLine = t.printLine, this.target = t.target, this.referenceElement = t.referenceElement;
        };
    }();
}, function (module, __webpack_exports__, __webpack_require__) {
    "use strict";

    __webpack_require__.d(__webpack_exports__, "a", function () {
        return TableExcelHelper;
    });

    var _ReconsitutionTableColumns__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(19),
        _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(0),
        TableExcelHelper = function () {
            function TableExcelHelper() { }

            return TableExcelHelper.createTableHead = function (t, e) {
                for (var n = TableExcelHelper.reconsitutionTableColumnTree(t), i = $("<thead></thead>"), o = TableExcelHelper.getColumnsWidth(n, e), r = function r(t) {
                    var e = $("<tr></tr>");
                    n[t].forEach(function (t) {
                        var n = $("<td></td>");
                        t.id && n.attr("id", t.id), t.columnId && n.attr("column-id", t.columnId), (t.align || t.halign) && n.css("text-align", t.halign || t.align), t.vAlign && n.css("vertical-align", t.vAlign), t.colspan > 1 && n.attr("colspan", t.colspan), t.rowspan > 1 && n.attr("rowspan", t.rowspan), n.html(t.title), o[t.id] ? (t.hasWidth = !0, t.targetWidth = o[t.id], n.attr("haswidth", "haswidth"), n.css("width", o[t.id] + "pt")) : t.hasWidth = !1, e.append(n);
                    }), i.append(e);
                }, a = 0; a < n.totalLayer; a++) {
                    r(a);
                }

                return TableExcelHelper.syncTargetWidthToOption(t), i;
            }, TableExcelHelper.createTableFooter = function (t, e, n, i, o, r) {
                var a = $("<tfoot></tfoot>"),
                    p = this.getFooterFormatter(n, i);
                return p && a.append(p(n, e, o, r)), a;
            }, TableExcelHelper.createTableRow = function (t, e, n, i) {
                if(Object.prototype.toString.call(e) != '[object Array]') {
                    e = [e];
                }
                var o = TableExcelHelper.reconsitutionTableColumnTree(t),
                    r = $("<tbody></tbody>");
                (e || (e = []), i.groupFields.length) ? _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_1__.a.groupBy(e, i.groupFields, function (t) {
                    var e = {};
                    return i.groupFields.forEach(function (n) {
                        return e[n] = t[n];
                    }), e;
                }).forEach(function (t) {
                    if (i.groupFormatter) {
                        var e = $("<tr><td colspan=" + o.colspan + "></td></tr>");
                        e.find("td").append(i.groupFormatter(t, n)), r.append(e);
                    }

                    if (t.rows.forEach(function (t) {
                        var e = TableExcelHelper.createRowTarget(o, t, n, i);
                        r.append(e);
                    }), i.groupFooterFormatter) {
                        var a = $("<tr><td colspan=" + o.colspan + "></td></tr>");
                        a.find("td").append(i.groupFooterFormatter(t, n)), r.append(a);
                    }
                }) : e.forEach(function (t) {
                    var e = TableExcelHelper.createRowTarget(o, t, n, i);
                    r.append(e);
                });
                return r;
            }, TableExcelHelper.createRowTarget = function (t, e, n, i) {
                var o = $("<tr></tr>");
                o.data("rowData", e), t.rowColumns.forEach(function (t, i) {
                    var r = $("<td></td>");
                    t.field && r.attr("field", t.field), t.align && r.css("text-align", t.align), t.vAlign && r.css("vertical-align", t.vAlign);
                    var a = TableExcelHelper.getColumnFormatter(t),
                        p = a ? a(e[t.field], e, i, n) : e[t.field];
                    r.html(p);
                    var s = TableExcelHelper.getColumnStyler(t);

                    if (s) {
                        var l = s(e[t.field], e, i, n);
                        if (l) Object.keys(l).forEach(function (t) {
                            r.css(t, l[t]);
                        });
                    }

                    o.append(r);
                });
                var r = TableExcelHelper.getRowStyler(n, i);

                if (r) {
                    var a = r(e, n);
                    if (a) Object.keys(a).forEach(function (t) {
                        o.css(t, a[t]);
                    });
                }

                return o;
            }, TableExcelHelper.createEmptyRowTarget = function (t) {
                var e = TableExcelHelper.reconsitutionTableColumnTree(t),
                    n = $("<tr></tr>");
                return e.rowColumns.forEach(function (t, e) {
                    var i = $("<td></td>");
                    t.field && i.attr("field", t.field), t.align && i.css("text-align", t.align), t.vAlign && i.css("vertical-align", t.vAlign), n.append(i);
                }), n;
            }, TableExcelHelper.getColumnsWidth = function (t, e) {
                var n = {},
                    i = TableExcelHelper.allAutoWidth(t),
                    o = TableExcelHelper.allFixedWidth(t);
                return t.rowColumns.forEach(function (t) {
                    if (t.fixed) n[t.id] = t.width; else {
                        var r = e - o,
                            a = t.width / i * (r > 0 ? r : 0);
                        n[t.id] = a;
                    }
                }), n;
            }, TableExcelHelper.resizeTableCellWidth = function (t, e, n) {
                var i = TableExcelHelper.reconsitutionTableColumnTree(e),
                    o = TableExcelHelper.getColumnsWidth(i, n);
                t.find("thead tr td[haswidth]").map(function (t, e) {
                    var n = $(e).attr("id"),
                        i = o[n];
                    $(e).css("width", i + "pt");
                });
            }, TableExcelHelper.allAutoWidth = function (t) {
                var e = 0;
                return t.rowColumns.forEach(function (t) {
                    e += t.fixed ? 0 : t.width;
                }), e;
            }, TableExcelHelper.allFixedWidth = function (t) {
                var e = 0;
                return t.rowColumns.forEach(function (t) {
                    e += t.fixed ? t.width : 0;
                }), e;
            }, TableExcelHelper.reconsitutionTableColumnTree = function (t, e, n) {
                var i = e || new _ReconsitutionTableColumns__WEBPACK_IMPORTED_MODULE_0__.a();
                i.colspan = 0;

                for (var o = function o(e) {
                    i.totalLayer = e + 1, i[e] = t[e].columns, 0 == e && t[e].columns.forEach(function (t) {
                        0 == e && (i.colspan += t.colspan);
                    });
                }, r = 0; r < t.length; r++) {
                    o(r);
                }

                return i.rowColumns = TableExcelHelper.getOrderdColumns(i), i;
            }, TableExcelHelper.syncTargetWidthToOption = function (t) {
                t.forEach(function (t) {
                    t.columns.forEach(function (t) {
                        t.hasWidth && (t.width = t.targetWidth);
                    });
                });
            }, TableExcelHelper.getFooterFormatter = function (options, tablePrintElementType) {
                var footerFormatter = void 0;
                if (tablePrintElementType.footerFormatter && (footerFormatter = tablePrintElementType.footerFormatter), options.footerFormatter) try {
                    var s = "footerFormatter=" + options.footerFormatter;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return footerFormatter;
            }, TableExcelHelper.getRowStyler = function (options, tablePrintElementType) {
                var rowStyler = void 0;
                if (tablePrintElementType.rowStyler && (rowStyler = tablePrintElementType.rowStyler), options.rowStyler) try {
                    var s = "rowStyler=" + options.rowStyler;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return rowStyler;
            }, TableExcelHelper.getColumnStyler = function (column) {
                var styler = void 0;
                if (column.styler && (styler = column.styler), column.styler2) try {
                    var s = "styler=" + column.styler2;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return styler;
            }, TableExcelHelper.getColumnFormatter = function (column) {
                var formatter = void 0;
                if (column.formatter && (formatter = column.formatter), column.formatter2) try {
                    var s = "formatter=" + column.formatter2;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return formatter;
            }, TableExcelHelper.getOrderdColumns = function (t) {
                for (var e = {}, n = function n(_n2) {
                    t[_n2].forEach(function (t) {
                        for (var i = 0; i < t.rowspan; i++) {
                            e[_n2 + i] = e[_n2 + i] ? e[_n2 + i] : [], e[_n2 + i].push(t);
                        }
                    });
                }, i = 0; i < t.totalLayer; i++) {
                    n(i);
                }

                return e[t.totalLayer - 1];
            }, TableExcelHelper;
        }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return i;
    });

    var i = function () {
        function t(t) {
            this.top = t.top, this.left = t.left, this.height = t.height, this.width = t.width, this.bottomInLastPaper = t.bottomInLastPaper, this.beginPrintPaperIndex = t.beginPrintPaperIndex, this.printTopInPaper = t.printTopInPaper, this.endPrintPaperIndex = t.endPrintPaperIndex;
        }

        return t.prototype.isPositionLeftOrRight = function (t) {
            return this.top <= t && this.top + this.height > t;
        }, t;
    }();
}, function (t, e, n) {
    "use strict";

    var i = function () {
        function t() {
            this.name = "lineHeight";
        }

        return t.prototype.css = function (t, e) {
            if (t && t.length) {
                if (e) return t.css("line-height", e + "pt"), "line-height:" + e + "pt";
                t[0].style.lineHeight = "";
            }

            return null;
        }, t.prototype.createTarget = function () {
            return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        字体行高\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        <option value="22.5" >22.5pt</option>\n        <option value="23.25" >23.25pt</option>\n        <option value="24" >24pt</option>\n        <option value="24.75" >24.75pt</option>\n        <option value="25.5" >25.5pt</option>\n        <option value="26.25" >26.25pt</option>\n        <option value="27" >27pt</option>\n        <option value="27.75" >27.75pt</option>\n        <option value="28.5" >28.5pt</option>\n        <option value="29.25" >29.25pt</option>\n        <option value="30" >30pt</option>\n        <option value="30.75" >30.75pt</option>\n        <option value="31.5" >31.5pt</option>\n        <option value="32.25" >32.25pt</option>\n        <option value="33" >33pt</option>\n        <option value="33.75" >33.75pt</option>\n        <option value="34.5" >34.5pt</option>\n        <option value="35.25" >35.25pt</option>\n        <option value="36" >36pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
        }, t.prototype.getValue = function () {
            var t = this.target.find("select").val();
            if (t) return parseFloat(t.toString());
        }, t.prototype.setValue = function (t) {
            t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
            this.target.find("select").val(t);
        }, t.prototype.destroy = function () {
            this.target.remove();
        }, t;
    }(),
        o = function () {
            function t() {
                this.name = "fontFamily";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        字体\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="SimSun" >宋体</option>\n            <option value="Microsoft YaHei" >微软雅黑</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("font-family", e), "font-family:" + e;
                    t[0].style.fontFamily = "";
                }

                return null;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        r = function () {
            function t() {
                this.name = "fontSize";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("font-size", e + "pt"), "font-size:" + e + "pt";
                    t[0].style.fontSize = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        字体大小\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        a = function () {
            function t() {
                this.name = "fontWeight";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("font-weight", e), "font-weight:" + e;
                    t[0].style.fontWeight = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        字体粗细\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="lighter" >更细</option>\n        <option value="bold" >粗体</option>\n        <option value="bolder" >粗体+</option>\n            <option value="100" >100</option>\n            <option value="200" >200</option>\n            <option value="300" >300</option>\n            <option value="400" >400</option>\n            <option value="500" >500</option>\n            <option value="600" >600</option>\n            <option value="700" >700</option>\n            <option value="800" >800</option>\n            <option value="900" >900</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        p = function () {
            function t() {
                this.name = "letterSpacing";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("letter-spacing", e + "pt"), "letter-spacing:" + e + "pt";
                    t[0].style.letterSpacing = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        字间距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        s = function () {
            function t() {
                this.name = "textAlign";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("text-align", e), "justify" == e ? (t.css("text-align-last", "justify"), t.css("text-justify", "distribute-all-lines")) : (t[0].style.textAlignLast = "", t[0].style.textJustify = ""), "text-align:" + e;
                    t[0].style.textAlign = "", t[0].style.textAlignLast = "", t[0].style.textJustify = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        左右对齐\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="" >居左</option>\n        <option value="center" >居中</option>\n        <option value="right" >居右</option>\n        <option value="justify" >两端对齐</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        l = function () {
            function t() {
                this.name = "hideTitle";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        标题显示隐藏\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="false" >显示</option>\n            <option value="true" >隐藏</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                if ("true" == this.target.find("select").val()) return !0;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val((null == t ? "" : t).toString());
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        u = function () {
            function t() {
                this.name = "tableBorder";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("table").length) {
                    if ("border" == e) return t.find("table").css("border", "1px solid"), "border:1px solid";
                    "noBorder" == e ? t.find("table").css("border", "0px solid") : t.find("table")[0].style.border = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表格边框\n        </div>\n        <div class="hiprint-option-item-field">\n            <select class="auto-submit">\n            <option value="" >默认</option>\n            <option value="border" >有边框</option>\n            <option value="noBorder" >无边框</option>\n            </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        d = function () {
            function t() {
                this.name = "tableHeaderBorder";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("thead tr").length) {
                    if ("border" == e) return t.find("thead tr").css("border", "1px solid"), "border:1pt solid";
                    "noBorder" == e ? t.find("thead tr").css("border", "0px solid") : "topBorder" == e ? (t.find("thead tr").css("border", "0px solid"), t.find("thead tr").css("border-top", "1px solid")) : "bottomBorder" == e ? (t.find("thead tr").css("border", "0px solid"), t.find("thead tr").css("border-bottom", "1px solid")) : "topBottomBorder" == e ? (t.find("thead tr").css("border", "0px solid"), t.find("thead tr").css("border-top", "1px solid"), t.find("thead tr").css("border-bottom", "1px solid")) : t.find("thead tr").map(function (t, e) {
                        e.style.border = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表头边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>    \n        <option value="border" >有边框</option>\n        <option value="noBorder" >无边框</option>\n        <option value="topBorder" >上边框</option>\n        <option value="bottomBorder" >下边框</option>\n        <option value="topBottomBorder" >上下边框</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        c = function () {
            function t() {
                this.name = "tableHeaderCellBorder";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("thead tr td").length) {
                    if ("border" == e) return t.find("thead tr td").css("border", "1px solid"), "border:1px solid";
                    "noBorder" == e ? t.find("thead tr td").css("border", "0px solid") : t.find("thead tr td").map(function (t, e) {
                        e.style.border = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表头单元格边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>    \n        <option value="border" >有边框</option>\n        <option value="noBorder" >无边框</option>\n      \n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        h = function () {
            function t() {
                this.name = "tableHeaderRowHeight";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("thead tr td").length) {
                    if (e) return t.find("thead tr td:not([rowspan])").css("height", e + "pt"), "height:" + e + "pt";
                    t.find("thead tr td").map(function (t, e) {
                        e.style.height = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表头行高\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n       \n        <option value="" >默认</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        <option value="22.5" >22.5pt</option>\n        <option value="23.25" >23.25pt</option>\n        <option value="24" >24pt</option>\n        <option value="24.75" >24.75pt</option>\n        <option value="25.5" >25.5pt</option>\n        <option value="26.25" >26.25pt</option>\n        <option value="27" >27pt</option>\n        <option value="27.75" >27.75pt</option>\n        <option value="28.5" >28.5pt</option>\n        <option value="29.25" >29.25pt</option>\n        <option value="30" >30pt</option>\n        <option value="30.75" >30.75pt</option>\n        <option value="31.5" >31.5pt</option>\n        <option value="32.25" >32.25pt</option>\n        <option value="33" >33pt</option>\n        <option value="33.75" >33.75pt</option>\n        <option value="34.5" >34.5pt</option>\n        <option value="35.25" >35.25pt</option>\n        <option value="36" >36pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        f = function () {
            function t() {
                this.name = "tableHeaderFontSize";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("thead").length) {
                    if (e) return t.find("thead").css("font-size", e + "pt"), "font-size:" + e + "pt";
                    t.find("thead").map(function (t, e) {
                        e.style.fontSize = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表头字体大小\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        g = function () {
            function t() {
                this.name = "tableHeaderFontWeight";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("thead").length) {
                    if (e) return t.find("thead tr td").css("font-weight", e), "font-weight:" + e;
                    t.find("thead tr td").map(function (t, e) {
                        e.style.fontWeight = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表头字体粗细\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit"> \n        <option value="" >默认</option>\n        <option value="lighter" >更细</option>\n        <option value="bold" >粗体</option>\n        <option value="bolder" >粗体+</option>\n        <option value="100" >100</option>\n        <option value="200" >200</option>\n        <option value="300" >300</option>\n        <option value="400" >400</option>\n        <option value="500" >500</option>\n        <option value="600" >600</option>\n        <option value="700" >700</option>\n        <option value="800" >800</option>\n        <option value="900" >900</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        m = function () {
            function t() {
                this.name = "tableBodyCellBorder";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("tbody tr td").length) {
                    if ("border" == e) return t.find("tbody tr td").css("border", "1px solid"), "border:1px solid";
                    "noBorder" == e ? t.find("tbody tr td").css("border", "0px solid") : t.find("tbody tr td").map(function (t, e) {
                        e.style.border = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n            表体单元格\n        </div>\n        <div class="hiprint-option-item-field">\n            <select class="auto-submit">\n            <option value="" >默认</option>\n            <option value="border" >有边框</option>\n            <option value="noBorder" >无边框</option>\n            </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        v = function () {
            function t() {
                this.name = "tableBodyRowHeight";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("tbody tr td").length) {
                    if (e) return t.find("tbody tr td:not([rowspan])").css("height", e + "pt"), "height:" + e + "pt";
                    t.find("tbody tr td").map(function (t, e) {
                        e.style.height = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n            表体行高\n        </div>\n        <div class="hiprint-option-item-field">\n            <select class="auto-submit">\n            <option value="" >默认</option>\n            <option value="6" >6pt</option>\n            <option value="6.75" >6.75pt</option>\n            <option value="7.5" >7.5pt</option>\n            <option value="8.25" >8.25pt</option>\n            <option value="9" >9pt</option>\n            <option value="9.75" >9.75pt</option>\n            <option value="10.5" >10.5pt</option>\n            <option value="11.25" >11.25pt</option>\n            <option value="12" >12pt</option>\n            <option value="12.75" >12.75pt</option>\n            <option value="13.5" >13.5pt</option>\n            <option value="14.25" >14.25pt</option>\n            <option value="15" >15pt</option>\n            <option value="15.75" >15.75pt</option>\n            <option value="16.5" >16.5pt</option>\n            <option value="17.25" >17.25pt</option>\n            <option value="18" >18pt</option>\n            <option value="18.75" >18.75pt</option>\n            <option value="19.5" >19.5pt</option>\n            <option value="20.25" >20.25pt</option>\n            <option value="21" >21pt</option>\n            <option value="21.75" >21.75pt</option>\n            <option value="22.5" >22.5pt</option>\n            <option value="23.25" >23.25pt</option>\n            <option value="24" >24pt</option>\n            <option value="24.75" >24.75pt</option>\n            <option value="25.5" >25.5pt</option>\n            <option value="26.25" >26.25pt</option>\n            <option value="27" >27pt</option>\n            <option value="27.75" >27.75pt</option>\n            <option value="28.5" >28.5pt</option>\n            <option value="29.25" >29.25pt</option>\n            <option value="30" >30pt</option>\n            <option value="30.75" >30.75pt</option>\n            <option value="31.5" >31.5pt</option>\n            <option value="32.25" >32.25pt</option>\n            <option value="33" >33pt</option>\n            <option value="33.75" >33.75pt</option>\n            <option value="34.5" >34.5pt</option>\n            <option value="35.25" >35.25pt</option>\n            <option value="36" >36pt</option>\n            </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        y = function () {
            function t() {
                this.name = "tableHeaderBackground";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("thead").length) {
                    if (e) return t.find("thead").css("background", e), "background:" + e;
                    t.find("thead").map(function (t, e) {
                        e.style.background = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表头背景\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" class="auto-submit" />\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").minicolors({
                    defaultValue: t || "",
                    theme: "bootstrap"
                }), this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        b = function () {
            function t() {
                this.name = "borderWidth";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        边框大小\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-width", e + "pt"), "border-width:" + e + "pt";
                    t[0].style.borderWidth = "";
                }

                return null;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        E = function () {
            function t() {
                this.name = "barcodeMode";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        条形码格式\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="CODE128A" >CODE128A</option>\n        <option value="CODE128B" >CODE128B</option>\n        <option value="CODE128C" >CODE128C</option>\n        <option value="CODE39" >CODE39</option>\n        <option value="EAN-13" >EAN-13</option>\n        <option value="EAN-8" >EAN-8</option>\n        <option value="EAN-5" >EAN-5</option>\n        <option value="EAN-2" >EAN-2</option>\n        <option value="UPC（A）" >UPC（A）</option>\n        <option value="ITF" >ITF</option>\n        <option value="ITF-14" >ITF-14</option>\n        <option value="MSI" >MSI</option>\n            <option value="MSI10" >MSI10</option>\n            <option value="MSI11" >MSI11</option>\n            <option value="MSI1010" >MSI1010</option>\n            <option value="MSI1110" >MSI1110</option>\n            <option value="Pharmacode" >Pharmacode</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                return t || void 0;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        T = function () {
            function t() {
                this.name = "color";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("color", e), "color:" + e;
                    t[0].style.color = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        字体颜色\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" class="auto-submit"/>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").minicolors({
                    defaultValue: t || "",
                    theme: "bootstrap"
                }), this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        P = function () {
            function t() {
                this.name = "textDecoration";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        文本修饰\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="underline" >下划线。</option>\n            <option value="overline" >上划线</option>\n            <option value="line-through" >穿梭线</option>\n           \n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("text-decoration", e), "text-decoration:" + e;
                    t[0].style.textDecoration = "";
                }

                return null;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        _ = function () {
            function t() {
                this.name = "field";
            }

            return t.prototype.createTarget = function (t) {
                var e = void 0;

                if (t && (e = t.getFields()), e) {
                    this.isSelect = !0;
                    var n = ' <div class="hiprint-option-item hiprint-option-item-row">\n            <div class="hiprint-option-item-label">\n            字段名\n            </div>\n            <div class="hiprint-option-item-field">\n            <select class="auto-submit">\n                <option value="" >请选择字段</option>';
                    e.forEach(function (t, e) {
                        n += ' <option value="' + (t.field || "") + '" >' + (t.text || "") + "</option>";
                    }), n += " </select>\n            </div>\n        </div>", this.target = $(n);
                } else {
                    this.isSelect = !1;
                    this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n            <div class="hiprint-option-item-label">\n            字段名\n            </div>\n            <div class="hiprint-option-item-field">\n            <input type="text" placeholder="请输入字段名" class="auto-submit">\n            </div>\n        </div>');
                }

                return this.target;
            }, t.prototype.getValue = function () {
                return (this.isSelect ? this.target.find("select").val() : this.target.find("input").val()) || void 0;
            }, t.prototype.setValue = function (t) {
                this.isSelect ? t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"), this.target.find("select").val(t)) : this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        w = function () {
            function t() {
                this.name = "title";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        标题\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:50px;" placeholder="请输入标题" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        x = function () {
            function t() {
                this.name = "testData";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        测试数据\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="仅字段名称存在时有效" class="auto-submit" >\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        C = function () {
            function t() {
                this.name = "src";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        图片地址\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="请输入图片地址" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        O = function () {
            function t() {
                this.name = "borderColor";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-color", e), "border-color:" + e;
                    t[0].style.borderColor = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        边框颜色\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" class="auto-submit" />\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").minicolors({
                    defaultValue: t || "",
                    theme: "bootstrap"
                }), this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        H = function () {
            function t() {
                this.name = "paperNumberFormat";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        页码格式\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="paperNo-paperCount" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        D = function () {
            function t() {
                this.name = "paperNumberDisabled";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        启用/禁用\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="false" >启用</option>\n        <option value="true" >禁用</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                if ("true" == this.target.find("select").val()) return !0;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        I = function () {
            function t() {
                this.name = "longTextIndent";
            }

            return t.prototype.css = function (t, e) {
                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        每行缩进\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        <option value="22.5" >22.5pt</option>\n        <option value="23.25" >23.25pt</option>\n        <option value="24" >24pt</option>\n        <option value="24.75" >24.75pt</option>\n        <option value="25.5" >25.5pt</option>\n        <option value="26.25" >26.25pt</option>\n        <option value="27" >27pt</option>\n        <option value="27.75" >27.75pt</option>\n        <option value="28.5" >28.5pt</option>\n        <option value="29.25" >29.25pt</option>\n        <option value="30" >30pt</option>\n        <option value="30.75" >30.75pt</option>\n        <option value="31.5" >31.5pt</option>\n        <option value="32.25" >32.25pt</option>\n        <option value="33" >33pt</option>\n        <option value="33.75" >33.75pt</option>\n        <option value="34.5" >34.5pt</option>\n        <option value="35.25" >35.25pt</option>\n        <option value="36" >36pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        R = function () {
            function t() {
                this.name = "showInPage";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        显示规则\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="first" >首页</option>\n            <option value="odd" >奇数页</option>\n            <option value="even" >偶数页</option>\n            <option value="last" >尾页</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        M = function () {
            function t() {
                this.name = "panelPaperRule";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        打印规则\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="odd" >保持奇数</option>\n            <option value="even" >保持偶数</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        S = function () {
            function t() {
                this.name = "leftSpaceRemoved";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        移除段落左侧空白\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="true" >移除</option>\n            <option value="false" >不移除</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                if ("false" == this.target.find("select").val()) return !1;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val((null == t ? "" : t).toString());
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        B = function () {
            function t() {
                this.name = "firstPaperFooter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        首页页尾\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="首页页尾" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        F = function () {
            function t() {
                this.name = "lastPaperFooter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        尾页页尾\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="尾页页尾" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        L = function () {
            function t() {
                this.name = "evenPaperFooter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        偶数页页尾\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="偶数页页尾" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        A = function () {
            function t() {
                this.name = "oddPaperFooter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        奇数页页尾\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="奇数页页尾" class="auto-submit" >\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        z = function () {
            function t() {
                this.name = "fixed";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        位置固定\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="false" >否</option>\n            <option value="true" >是</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                if ("true" == this.target.find("select").val()) return !0;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val((null == t ? "" : t).toString());
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        k = function () {
            function t() {
                this.name = "axis";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        拖动方向\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="v" >横向</option>\n        <option value="h" >竖向</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                return t || void 0;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        N = function () {
            function t() {
                this.name = "leftOffset";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        左偏移\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="偏移量pt" class="auto-submit" >\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        V = function () {
            function t() {
                this.name = "lHeight";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        最低高度\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="文本过短或为空时的高度" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        W = function () {
            function t() {
                this.name = "unShowInPage";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        隐藏规则\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n            <option value="first" >首页</option>\n            <option value="last" >尾页</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        j = function () {
            function t() {
                this.name = "tableBodyRowBorder";
            }

            return t.prototype.css = function (t, e) {
                if (t.find("tbody tr").length) {
                    if ("border" == e) return t.find("tbody tr").css("border", "1px solid"), "border:1pt solid";
                    "noBorder" == e ? t.find("tbody tr").css("border", "0px solid") : "topBorder" == e ? (t.find("tbody tr").css("border", "0px solid"), t.find("tbody tr").css("border-top", "1px solid")) : "bottomBorder" == e ? (t.find("tbody tr").css("border", "0px solid"), t.find("tbody tr").css("border-bottom", "1px solid")) : "topBottomBorder" == e ? (t.find("tbody tr").css("border", "0px solid"), t.find("tbody tr").css("border-top", "1px solid"), t.find("tbody tr").css("border-bottom", "1px solid")) : t.find("tbody tr").map(function (t, e) {
                        e.style.border = "";
                    });
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表体行边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>    \n        <option value="border" >有边框</option>\n        <option value="noBorder" >无边框</option>\n        <option value="topBorder" >上边框</option>\n        <option value="bottomBorder" >下边框</option>\n        <option value="topBottomBorder" >上下边框</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        U = function () {
            function t() {
                this.name = "transform";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    var n = t.find(".hiprint-printElement-content");
                    if (e) return n.css("transform", "rotate(" + e + "deg)"), n.css("-ms-transform", "rotate(" + e + "deg)"), n.css("-moz-transform", "rotate(" + e + "deg)"), n.css("-webkit-transform", "rotate(" + e + "deg)"), n.css("-o-transform", "rotate(" + e + "deg)"), "transform:rotate(" + e + "deg)";
                    n.length && (n[0].style.transform = "");
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $('<div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        旋转角度\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" class="auto-submit"/>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        K = function () {
            function t() {
                this.name = "optionsGroup";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        边框设置\n        </div>\n       \n    </div>'), this.target;
            }, t.prototype.getValue = function () { }, t.prototype.setValue = function (t) { }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        G = function () {
            function t() {
                this.name = "borderTop";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-top-style", e), "border-top:1px";
                    t[0].style.borderTopStyle = "", t[0].style.borderTopWidth = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        上边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n            <option value="" >否</option>\n            <option value="solid" >实线</option>\n            <option value="dotted" >虚线</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        q = function () {
            function t() {
                this.name = "borderLeft";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-left-style", e), "border-left:1px";
                    t[0].style.borderLeftStyle = "", t[0].style.borderLeftWidth = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        左边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >否</option>\n        <option value="solid" >实线</option>\n        <option value="dotted" >虚线</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        X = function () {
            function t() {
                this.name = "borderRight";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-right-style", e), "border-right:1px";
                    t[0].style.borderRightStyle = "", t[0].style.borderRightWidth = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        右边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >否</option>\n        <option value="solid" >实线</option>\n        <option value="dotted" >虚线</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        Y = function () {
            function t() {
                this.name = "borderBottom";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-bottom-style", e), "border-bottom-style:1px solid";
                    t[0].style.borderBottomStyle = "", t[0].style.borderBottomWidth = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        下边框\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >否</option>\n        <option value="solid" >实线</option>\n        <option value="dotted" >虚线</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        J = function () {
            function t() {
                this.name = "contentPaddingLeft";
            }

            return t.prototype.css = function (t, e) {
                var n = t.find(".hiprint-printElement-content");

                if (n && n.length) {
                    if (e) return n.css("padding-left", e + "pt"), "padding-left";
                    n[0].style.paddingLeft = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        左内边距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        Q = function () {
            function t() {
                this.name = "contentPaddingTop";
            }

            return t.prototype.css = function (t, e) {
                var n = t.find(".hiprint-printElement-content");

                if (n && n.length) {
                    if (e) return n.css("padding-top", e + "pt"), "padding-top";
                    n[0].style.paddingTop = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        上内边距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        Z = function () {
            function t() {
                this.name = "contentPaddingRight";
            }

            return t.prototype.css = function (t, e) {
                var n = t.find(".hiprint-printElement-content");

                if (n && n.length) {
                    if (e) return n.css("padding-right", e + "pt"), "padding-right";
                    n[0].style.paddingRight = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        右内边距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        tt = function () {
            function t() {
                this.name = "contentPaddingBottom";
            }

            return t.prototype.css = function (t, e) {
                var n = t.find(".hiprint-printElement-content");

                if (n && n.length) {
                    if (e) return n.css("padding-bottom", e + "pt"), "padding-bottom";
                    n[0].style.paddingBottom = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        下内边距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        et = function () {
            function t() {
                this.name = "borderStyle";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("border-style", e), "border-style:1px";
                    t[0].style.borderStyle = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n       边框样式\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n            <option value="" >默认</option>\n            <option value="solid" >实线</option>\n            <option value="dotted" >虚线</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        nt = function () {
            function t() {
                this.name = "backgroundColor";
            }

            return t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.css("background-color", e), "background-color:" + e;
                    t[0].style.backgroundColor = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        背景颜色\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" class="auto-submit"/>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("input").minicolors({
                    defaultValue: t || "",
                    theme: "bootstrap"
                }), this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        it = function () {
            function t() {
                this.name = "orient";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        纸张方向(仅自定义纸质有效)\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="1" >纵向</option>\n        <option value="2" >横向</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        ot = function () {
            function t() {
                this.name = "textContentVerticalAlign";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        上下对齐\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="middle" >垂直居中</option>\n        <option value="bottom" >底部</option>\n       \n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return "middle" === e && t.addClass("hiprint-text-content-middle"), "bottom" === e && t.addClass("hiprint-text-content-bottom"), "";
                    t.removeClass("hiprint-text-content-middle"), t.removeClass("hiprint-text-content-bottom");
                }

                return null;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        rt = n(5),
        at = function () {
            function t() {
                this.name = "columns";
            }

            return t.prototype.createTarget = function () {
                $('<div class="indicator"></div>').appendTo("body");
                return " </ul>\n       </div>\n    </div>", this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n       <div>\n            <ul class="hiprint-option-table-selected-columns"> </ul>\n       </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                return this.buildData();
            }, t.prototype.setValue = function (t, e, n) {
                var i = this,
                    o = this;
                this.value = t, this.options = e, this.printElementType = n;
                var r = n.columns[0].filter(function (e) {
                    return 0 == t[0].columns.filter(function (t) {
                        return e.columnId == t.columnId;
                    }).length;
                }).map(function (t) {
                    var e = new rt.a(t);
                    return e.checked = !1, e;
                });
                this.allColumns = t[0].columns.concat(r), t && 1 == t.length && (this.target.find("ul").html(this.allColumns.map(function (t, e) {
                    return '<li  class="hiprint-option-table-selected-item"> <div class="hi-pretty p-default">\n                ' + (t.checked ? '<input type="checkbox"   checked column-id="' + (t.columnId || "") + '" />' : '<input type="checkbox"  column-id="' + (t.columnId || "") + '" />') + '\n                <div class="state">\n                    <label></label>\n                </div>\n            </div><span class="column-title">' + (t.title || t.descTitle || "") + "</span></li>";
                }).join("")), this.target.find("input").change(function () {
                    i.submit();
                }), this.printElementType.columnDisplayIndexEditable && this.target.find("li").hidraggable({
                    revert: !0,
                    handle: ".column-title",
                    moveUnit: "pt",
                    deltaX: 0,
                    deltaY: 0
                }).hidroppable({
                    onDragOver: function onDragOver(t, e) {
                        $(this).css("border-bottom-color", "red");
                    },
                    onDragLeave: function onDragLeave(t, e) {
                        $(this).css("border-bottom-color", "");
                    },
                    onDrop: function onDrop(t, e) {
                        $(e).insertAfter(this), $(this).css("border-bottom-color", ""), o.submit();
                    }
                }));
            }, t.prototype.buildData = function () {
                var t = this,
                    e = [];
                return this.allColumns.filter(function (t) {
                    t.checked = !1;
                }), (this.printElementType.columnDisplayEditable ? this.target.find("input:checked") : this.target.find("input")).map(function (n, i) {
                    var o = $(i).attr("column-id"),
                        r = t.options.makeColumnObj();
                    if (r[o]) r[o].checked = !0, e.push(r[o]); else {
                        var a = t.printElementType.getColumnByColumnId(o);

                        if (a) {
                            var p = new rt.a(a);
                            p.checked = !0, e.push(p);
                        }
                    }
                }), this.value[0].columns = e, this.value;
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        pt = function () {
            function t() {
                this.name = "textType";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        打印类型\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="" >文本</option>\n        <option value="barcode" >条形码</option>\n        <option value="qrcode" >二维码</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        st = function () {
            function t() {
                this.name = "topOffset";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        顶部偏移\n        </div>\n        <div class="hiprint-option-item-field">\n        <input type="text" placeholder="偏移量pt" class="auto-submit">\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("input").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                this.target.find("input").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        lt = function () {
            function t() {
                this.name = "gridColumns";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        一行多组\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="2" >一行二列</option>\n        <option value="3" >一行三列</option>\n        <option value="4" >一行四列</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        ut = function () {
            function t() {
                this.name = "gridColumnsGutter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        一行多组间隔\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.25" >7.25pt</option>\n        <option value="8.5" >8.5pt</option>\n        <option value="9" >9pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.css = function (t, e) {
                if (t && t.length) {
                    if (e) return t.find(".table-grid-row").css("margin-left", "-" + e + "pt").css("margin-right", "-" + e + "pt"), t.find(".tableGridColumnsGutterRow").css("padding-left", e + "pt").css("padding-right", e + "pt"), null;
                    t.find(".table-grid-row").map(function (t, e) {
                        e.style.marginLeft = "", e.style.marginRight = "";
                    }), t.find(".tableGridColumnsGutterRow").map(function (t, e) {
                        e.style.paddingLeft = "", e.style.paddingRight = "";
                    });
                }

                return null;
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        dt = function () {
            function t() {
                this.name = "paddingLeft";
            }

            return t.prototype.css = function (t, e) {
                var n = t;

                if (n && n.length) {
                    if (e) return n.css("padding-left", e + "pt"), "padding-left";
                    n[0].style.paddingLeft = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        左内边距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        ct = function () {
            function t() {
                this.name = "paddingRight";
            }

            return t.prototype.css = function (t, e) {
                var n = t;

                if (n && n.length) {
                    if (e) return n.css("padding-right", e + "pt"), "padding-right";
                    n[0].style.paddingRight = "";
                }

                return null;
            }, t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        右内边距\n        </div>\n        <div class="hiprint-option-item-field">\n        <select>\n        <option value="" >默认</option>\n        <option value="0.75" >0.75pt</option>\n        <option value="1.5" >1.5pt</option>\n        <option value="2.25" >2.25pt</option>\n        <option value="3" >3pt</option>\n        <option value="3.75" >3.75pt</option>\n        <option value="4.5" >4.5pt</option>\n        <option value="5.25" >5.25pt</option>\n        <option value="6" >6pt</option>\n        <option value="6.75" >6.75pt</option>\n        <option value="7.5" >7.5pt</option>\n        <option value="8.25" >8.25pt</option>\n        <option value="9" >9pt</option>\n        <option value="9.75" >9.75pt</option>\n        <option value="10.5" >10.5pt</option>\n        <option value="11.25" >11.25pt</option>\n        <option value="12" >12pt</option>\n        <option value="12.75" >12.75pt</option>\n        <option value="13.5" >13.5pt</option>\n        <option value="14.25" >14.25pt</option>\n        <option value="15" >15pt</option>\n        <option value="15.75" >15.75pt</option>\n        <option value="16.5" >16.5pt</option>\n        <option value="17.25" >17.25pt</option>\n        <option value="18" >18pt</option>\n        <option value="18.75" >18.75pt</option>\n        <option value="19.5" >19.5pt</option>\n        <option value="20.25" >20.25pt</option>\n        <option value="21" >21pt</option>\n        <option value="21.75" >21.75pt</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return parseFloat(t.toString());
            }, t.prototype.setValue = function (t) {
                t && (this.target.find('option[value="' + t + '"]').length || this.target.find("select").prepend('<option value="' + t + '" >' + t + "</option>"));
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        ht = function () {
            function t() {
                this.name = "dataType";
            }

            return t.prototype.createTarget = function () {
                var t = this;
                return this.target = $('\n        <div class="hiprint-option-item-row">\n        <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        数据类型\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="hiprint-option-item-datatype">\n        <option value="" >默认</option>\n        <option value="datetime" >日期时间</option>\n        <option value="boolean" >布尔</option>\n        </select>\n        </div>\n    </div>\n    <div class="hiprint-option-item ">\n        <div class="hiprint-option-item-label ">\n        格式\n        </div>\n        <div class="hiprint-option-item-field">\n        <select  class="auto-submit hiprint-option-item-datatype-select-format">\n        <option value="" >默认</option>\n        \n        </select>\n        <input class="auto-submit  hiprint-option-item-datatype-input-format" type="text" data-type="boolean" placeholder="true:false">\n        </div>\n    </div>\n        </div>\n        '), $(this.target.find(".hiprint-option-item-datatype")).change(function () {
                    var e = $(t.target.find(".hiprint-option-item-datatype")).val();
                    t.loadFormatSelectByDataType(e), t.submit(t.getValue());
                }), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find(".hiprint-option-item-datatype").val();

                if (t) {
                    var e = this.target.find(".hiprint-option-item-datatype-format").val();
                    return {
                        dataType: t,
                        format: e || void 0
                    };
                }

                return {
                    dataType: void 0,
                    format: void 0
                };
            }, t.prototype.setValue = function (t, e) {
                this.target.find(".hiprint-option-item-datatype").val(e.dataType || ""), this.loadFormatSelectByDataType(e.dataType), this.target.find(".hiprint-option-item-datatype-format").val(e.format || "");
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t.prototype.loadFormatSelectByDataType = function (t) {
                "boolean" === t ? (this.target.find(".hiprint-option-item-datatype-select-format").removeClass("hiprint-option-item-datatype-format").hide().val(""), this.target.find(".hiprint-option-item-datatype-input-format").addClass("hiprint-option-item-datatype-format").show()) : "datetime" === t ? (this.target.find(".hiprint-option-item-datatype-select-format").addClass("hiprint-option-item-datatype-format").show(), this.target.find(".hiprint-option-item-datatype-input-format").removeClass("hiprint-option-item-datatype-format").hide().val(""), this.target.find(".hiprint-option-item-datatype-select-format").html('\n            <option value="" >默认</option>\n            <option value="M/d" >M/d</option>\n            <option value="MM/dd" >MM/dd</option>\n            <option value="yy/M/d" >yy/M/d</option>\n            <option value="yy/MM/dd" >yy/MM/dd</option>\n            <option value="yyyy/M/d" >yyyy/M/d</option>\n            <option value="yyyy/MM/dd" >yyyy/MM/dd</option>\n            <option value="yy/M/d H:m" >yy/M/d H:m</option>\n            <option value="yy/M/d H:m:s" >yy/M/d H:m:s</option>\n            <option value="yy/M/d HH:mm" >yy/M/d HH:mm</option>\n            <option value="yy/M/d HH:mm:ss" >yy/M/d HH:mm:ss</option>\n            <option value="yy/MM/dd H:m" >yy/MM/dd H:m</option>\n            <option value="yy/MM/dd H:m:s" >yy/MM/dd H:m:s</option>\n            <option value="yy/MM/dd HH:mm" >yy/MM/dd HH:mm</option>\n            <option value="yy/MM/dd HH:mm:ss" >yy/MM/dd HH:mm:ss</option>\n            <option value="yyyy/M/d H:m" >yyyy/M/dd H:m</option>\n            <option value="yyyy/M/d H:m:s" >yyyy/M/d H:m:s</option>\n            <option value="yyyy/M/d HH:mm" >yyyy/M/d HH:mm</option>\n            <option value="yyyy/M/d HH:mm:ss" >yyyy/M/d HH:mm:ss</option>\n            <option value="yyyy/MM/dd H:m" >yyyy/MM/dd H:m</option>\n            <option value="yyyy/MM/dd H:m:s" >yyyy/MM/dd H:m:s</option>\n            <option value="yyyy/MM/dd HH:mm" >yyyy/MM/dd HH:mm</option>\n            <option value="yyyy/MM/dd HH:mm:ss" >yyyy/MM/dd HH:mm:ss</option>\n\n            <option value="M-d" >M-d</option>\n            <option value="MM-dd" >MM-dd</option>\n            <option value="yy-M-d" >yy-M-d</option>\n            <option value="yy-MM-dd" >yy-MM-dd</option>\n            <option value="yyyy-M-d" >yyyy-M-d</option>\n            <option value="yyyy-MM-dd" >yyyy-MM-dd</option>\n            <option value="yy-M-d H:m" >yy-M-d H:m</option>\n            <option value="yy-M-d H:m:s" >yy-M-d H:m:s</option>\n            <option value="yy-M-d HH:mm" >yy-M-d HH:mm</option>\n            <option value="yy-M-d HH:mm:ss" >yy-M-d HH:mm:ss</option>\n            <option value="yy-MM-dd H:m" >yy-MM-dd H:m</option>\n            <option value="yy-MM-dd H:m:s" >yy-MM-dd H:m:s</option>\n            <option value="yy-MM-dd HH:mm" >yy-MM-dd HH:mm</option>\n            <option value="yy-MM-dd HH:mm:ss" >yy-MM-dd HH:mm:ss</option>\n            <option value="yyyy-M-d H:m" >yyyy-M-d H:m</option>\n            <option value="yyyy-M-d H:m:s" >yyyy-M-d H:m:s</option>\n            <option value="yyyy-M-d HH:mm" >yyyy-M-d HH:mm</option>\n            <option value="yyyy-M-d HH:mm:ss" >yyyy-M-d HH:mm:ss</option>\n            <option value="yyyy-MM-dd H:m" >yyyy-MM-dd H:m</option>\n            <option value="yyyy-MM-dd H:m:s" >yyyy-MM-dd H:m:s</option>\n            <option value="yyyy-MM-dd HH:mm" >yyyy-MM-dd HH:mm</option>\n            <option value="yyyy-MM-dd HH:mm:ss" >yyyy-MM-dd HH:mm:ss</option>\n        ')) : (this.target.find(".hiprint-option-item-datatype-select-format").show(), this.target.find(".hiprint-option-item-datatype-input-format").hide().val(""), this.target.find(".hiprint-option-item-datatype-format").html('\n            <option value="" >默认</option>\n        '));
            }, t;
        }(),
        ft = function () {
            function t() {
                this.name = "formatter";
            }

            return t.prototype.createTarget = function () {
                var t = ' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        格式化函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="' + (this.placeholder || "") + '" class="auto-submit"></textarea>\n        </div>\n    </div>';
                return this.target = $(t), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        gt = function () {
            function t() {
                this.name = "styler";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        样式函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="function(value, options, target,templateData){}" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        mt = function () {
            function t() {
                this.name = "footerFormatter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        表格脚函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="function(options,rows,data){ return \'<tr></tr>\' }; }" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        vt = function () {
            function t() {
                this.name = "gridColumnsFooterFormatter";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        多组表格脚函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="function(options,rows,data){ return \'\' }; }" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        yt = function () {
            function t() {
                this.name = "rowStyler";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        行样式函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="请输入标题" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        bt = function () {
            function t() {
                this.name = "align";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        单元格左右对齐\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="left" >居左</option>\n        <option value="center" >居中</option>\n        <option value="right" >居右</option>\n        <option value="justify" >两端对齐</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        Et = function () {
            function t() {
                this.name = "vAlign";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        单元格上下对齐\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="top" >上</option>\n        <option value="middle" >中</option>\n        <option value="bottom" >居右</option>\n        \n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        Tt = function () {
            function t() {
                this.name = "halign";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表格头单元格左右对齐\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="left" >居左</option>\n        <option value="center" >居中</option>\n        <option value="right" >居右</option>\n        <option value="justify" >两端对齐</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        Pt = function () {
            function t() {
                this.name = "styler2";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        单元格样式函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="function(value,row,index,options){ return {color:\'red\' }; }" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        _t = function () {
            function t() {
                this.name = "formatter2";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item hiprint-option-item-row">\n        <div class="hiprint-option-item-label">\n        单元格格式化函数\n        </div>\n        <div class="hiprint-option-item-field">\n        <textarea style="height:80px;" placeholder="function(value,row,index,options){ return \'\'; }" class="auto-submit"></textarea>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("textarea").val();
                if (t) return t;
            }, t.prototype.setValue = function (t) {
                this.target.find("textarea").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        wt = function () {
            function t() {
                this.name = "autoCompletion";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        自动补全\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="true" >是</option>\n        <option value="false" >否</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                if ("true" == this.target.find("select").val()) return !0;
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val((null == t ? "" : t).toString());
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }(),
        xt = function () {
            function t() {
                this.name = "tableFooterRepeat";
            }

            return t.prototype.createTarget = function () {
                return this.target = $(' <div class="hiprint-option-item">\n        <div class="hiprint-option-item-label">\n        表格脚显示\n        </div>\n        <div class="hiprint-option-item-field">\n        <select class="auto-submit">\n        <option value="" >默认</option>\n        <option value="no" >不显示</option>\n        <option value="page" >每页显示</option>\n        <option value="last" >最后显示</option>\n        </select>\n        </div>\n    </div>'), this.target;
            }, t.prototype.getValue = function () {
                var t = this.target.find("select").val();
                if (t) return t.toString();
            }, t.prototype.setValue = function (t) {
                this.target.find("select").val(t);
            }, t.prototype.destroy = function () {
                this.target.remove();
            }, t;
        }();

    n.d(e, "a", function () {
        return Ct;
    });

    var Ct = function () {
        function t() { }

        return t.init = function () {
            t.printElementOptionItems || (t.printElementOptionItems = {}, t._printElementOptionItems.forEach(function (e) {
                t.printElementOptionItems[e.name] = e;
            }));
        }, t.registerItem = function (e) {
            if (!e.name) throw new Error("styleItem must have name");
            t.init(), t.printElementOptionItems[e.name] = e;
        }, t.getItem = function (e) {
            return t.init(), t.printElementOptionItems[e];
        }, t._printElementOptionItems = [new o(), new r(), new a(), new p(), new i(), new s(), new l(), new pt(), new u(), new d(), new c(), new h(), new f(), new g(), new m(), new v(), new y(), new b(), new E(), new T(), new P(), new _(), new w(), new x(), new C(), new O(), new H(), new D(), new I(), new R(), new M(), new S(), new B(), new F(), new L(), new A(), new z(), new k(), new st(), new N(), new V(), new W(), new j(), new U(), new K(), new G(), new q(), new X(), new Y(), new Q(), new J(), new Z(), new tt(), new et(), new nt(), new it(), new ot(), new at(), new lt(), new ut(), new dt(), new ct(), new ht(), new ft(), new gt(), new mt(), new vt(), new yt(), new bt(), new Tt(), new Et(), new Pt(), new _t(), new wt(), new xt()], t;
    }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return o;
    }), n.d(e, "b", function () {
        return r;
    });

    var i = n(14),
        o = function () {
            function t(t, e) {
                this.selectedCells = [], this.rows = t, this.tableTatget = e;
            }

            return t.prototype.clear = function () {
                this.tableTatget.find("td").removeClass("selected");
            }, t.prototype.setSingleSelect = function (t) {
                this.startCell = t, this.selectedCells = [];
            }, t.prototype.getSingleSelect = function () {
                if (this.selectedCells.length) {
                    if (1 == this.selectedCells.length) return 1 == this.selectedCells[0].length ? this.selectedCells[0][0] : void 0;
                    if (this.selectedCells.length > 1) return;
                }

                return this.startCell;
            }, t.prototype.singleSelectByXY = function (t, e) {
                var n = this.getCellByXY(t, e);
                n && (this.clear(), n && (n.cell.select(), this.startCell = n, this.selectedCells = []));
            }, t.prototype.multipleSelectByXY = function (t, e) {
                this.clear();
                var n = [];

                if (this.startCell) {
                    var o = this.getCellByXY(t, e);

                    if (o) {
                        var r = i.a.mergeRect(this.startCell.cell.getTableRect(), o.cell.getTableRect());
                        this.selectByRect(new a(r), n);
                    }
                }

                this.selectedCells = n;
            }, t.prototype.selectByRect = function (t, e) {
                this.rows.forEach(function (n, i) {
                    var o = [];
                    n.columns.forEach(function (e) {
                        e.isInRect(t) && (o.push(new p(i, e)), e.select());
                    }), o.length && e.push(o);
                }), t.changed && (t.changed = !1, e.splice(0, e.length), this.selectByRect(t, e));
            }, t.prototype.getSelectedCells = function () {
                return this.selectedCells;
            }, t.prototype.getCellByXY = function (t, e) {
                var n;
                return this.rows.forEach(function (i, o) {
                    var r = i.columns.filter(function (n) {
                        return n.isXYinCell(t, e);
                    });
                    r.length && (n = new p(o, r[0]));
                }), n;
            }, t;
        }(),
        r = function () {
            return function (t) {
                this.x = t.x, this.y = t.y, this.height = t.height, this.width = t.width;
            };
        }(),
        a = function () {
            return function (t) {
                this.rect = t;
            };
        }(),
        p = function () {
            return function (t, e) {
                this.rowIndex = t, this.cell = e;
            };
        }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return i;
    });

    var i = function () {
        function t() { }

        return t.createId = function () {
            return this.id += 1, this.id;
        }, t.id = 1, t;
    }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return p;
    });

    var _i,
        o = n(5),
        r = n(13),
        a = (_i = function i(t, e) {
            return (_i = Object.setPrototypeOf || _instanceof({
                __proto__: []
            }, Array) && function (t, e) {
                t.__proto__ = e;
            } || function (t, e) {
                for (var n in e) {
                    e.hasOwnProperty(n) && (t[n] = e[n]);
                }
            })(t, e);
        }, function (t, e) {
            function n() {
                this.constructor = t;
            }

            _i(t, e), t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
        }),
        p = function (t) {
            function e(e) {
                var n = t.call(this) || this;
                (n.columns = [], e && e.constructor === Array) ? (e || []).forEach(function (t) {
                    n.columns.push(new o.a(t));
                }) : e.columns && (e.columns || []).forEach(function (t) {
                    n.columns.push(new o.a(t));
                });
                return n;
            }

            return a(e, t), e.prototype.getPrintElementOptionEntity = function () {
                var t = [];
                return this.columns.forEach(function (e) {
                    t.push(e.getEntity());
                }), t;
            }, e;
        }(r.a);
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return r;
    });

    var i = n(11),
        o = n(5),
        r = function () {
            function t() {
                this.id = i.a.createId();
            }

            return t.prototype.init = function (t, e, n) {
                this.isHead = n, this.target = e || $("<tr></tr>"), this.tableOptions = t, this.initCells(this.columns);
            }, t.prototype.getTarget = function () {
                return this.target;
            }, t.prototype.initCells = function (t) {
                var e = this;
                t ? t.forEach(function (t, n) {
                    t.init(e.target.find("td:eq(" + n + ")"), e.tableOptions, e.id, e.isHead);
                }) : (this.columns = [], this.target.find("td").map(function (t, n) {
                    var i = new o.a();
                    i.init($(n), e.tableOptions, e.id, e.isHead), e.columns.push(i);
                }));
            }, t.prototype.removeCell = function (t) {
                var e = this.columns.indexOf(t);
                this.columns[e].getTarget().remove(), this.columns.splice(e, 1);
            }, t.prototype.createTableCell = function (t, e) {
                var n = new o.a();
                return n.init($("<td></td>"), this.tableOptions, this.id, this.isHead), t > 1 && (n.getTarget().attr("rowspan", t), n.rowspan = t), e > 1 && (n.getTarget().attr("colspan", e), n.colspan = e), n;
            }, t.prototype.insertToTargetCellLeft = function (t, e) {
                var n = this.columns.indexOf(t);
                t.getTarget().before(e.getTarget()), this.columns.splice(n, 0, e);
            }, t.prototype.insertToTargetCellRight = function (t, e) {
                var n = this.columns.indexOf(t);
                this.columns[n].getTarget().after(e.getTarget()), this.columns.splice(n + 1, 0, e);
            }, t.prototype.insertCellToFirst = function (t) {
                this.target.prepend(t.getTarget()), this.columns.splice(0, 0, t);
            }, t.prototype.insertCellToLast = function (t) {
                this.columns.push(t), this.target.append(t.getTarget());
            }, t.prototype.getPrintElementOptionEntity = function () {
                var t = [];
                return this.columns.forEach(function (e) {
                    t.push(e.getEntity());
                }), t;
            }, t;
        }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return o;
    });

    var i = n(10),
        o = function () {
            function t() { }

            return t.mergeRect = function (t, e) {
                var n = Math.min(t.x, e.x),
                    o = Math.min(t.y, e.y);
                return new i.b({
                    x: n,
                    y: o,
                    height: Math.max(t.y + t.height, e.y + e.height) - o,
                    width: Math.max(t.x + t.width, e.x + e.width) - n
                });
            }, t.Rect = function (t, e, n, i) {
                return {
                    minX: t < n ? t : n,
                    minY: e < i ? e : i,
                    maxX: t < n ? n : t,
                    maxY: e < i ? i : e
                };
            }, t;
        }();
}, function (module, __webpack_exports__, __webpack_require__) {
    "use strict";

    __webpack_require__.d(__webpack_exports__, "a", function () {
        return TablePrintElement;
    });

    var _BasePrintElement__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(4),
        _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(1),
        _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(6),
        _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(0),
        _PrintReferenceElement__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(8),
        _option_TablePrintElementOption__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(18),
        _table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(7),
        _hitable_HiTale__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(16),
        _table_GridColumnsStructure__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(20),
        _HiPrintlib__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(2),
        __extends = (_extendStatics = function extendStatics(t, e) {
            return (_extendStatics = Object.setPrototypeOf || _instanceof({
                __proto__: []
            }, Array) && function (t, e) {
                t.__proto__ = e;
            } || function (t, e) {
                for (var n in e) {
                    e.hasOwnProperty(n) && (t[n] = e[n]);
                }
            })(t, e);
        }, function (t, e) {
            function n() {
                this.constructor = t;
            }

            _extendStatics(t, e), t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
        }),
        _extendStatics,
        TablePrintElement = function (_super) {
            function TablePrintElement(t, e) {
                var n = _super.call(this, t) || this;
                return n.gridColumnsFooterCss = "hiprint-gridColumnsFooter", n.tableGridRowCss = "table-grid-row", n.options = new _option_TablePrintElementOption__WEBPACK_IMPORTED_MODULE_5__.a(e, n.printElementType), n.options.setDefault(new _option_TablePrintElementOption__WEBPACK_IMPORTED_MODULE_5__.a(_HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.table.default).getPrintElementOptionEntity()), n;
            }

            return __extends(TablePrintElement, _super), TablePrintElement.prototype.getColumns = function () {
                return this.options.columns;
            }, TablePrintElement.prototype.getColumnByColumnId = function (t) {
                return this.options.getColumnByColumnId(t);
            }, TablePrintElement.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    this.css(this.designTarget, this.getData());
                    var t = this.designTarget.find(".hiprint-printElement-table-content"),
                        e = this.getHtml(this.designPaper);
                    t.html(""), t.append(e[0].target.find(".table-grid-row")), this.printElementType.editable && this.setHitable(), this.setColumnsOptions();
                }
            }, TablePrintElement.prototype.css = function (t, e) {
                if ((this.getField() || !this.options.content) && !this.printElementType.formatter) return _super.prototype.css.call(this, t, e);
            }, TablePrintElement.prototype.getDesignTarget = function (t) {
                return this.designTarget = this.getHtml(t)[0].target, this.designPaper = t, this.designTarget.find("td").hidroppable({
                    accept: ".rn-draggable-item",
                    onDrop: function onDrop(t, e) { },
                    onDragEnter: function onDragEnter(t, e) {
                        $(e).removeClass("rn-draggable-item");
                    },
                    onDragLeave: function onDragLeave(t, e) {
                        $(e).addClass("rn-draggable-item");
                    }
                }), this.designTarget;
            }, TablePrintElement.prototype.getConfigOptions = function () {
                return _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.table;
            }, TablePrintElement.prototype.createTarget = function (t, e, n) {
                for (var i = $('<div class="hiprint-printElement hiprint-printElement-table" style="position: absolute;"><div class="hiprint-printElement-table-handle"></div><div class="hiprint-printElement-table-content" style="height:100%;width:100%"></span></div>'), o = this.createGridColumnsStructure(n), r = 0; r < o.gridColumns; r++) {
                    o.getByIndex(r).append(this.getTableHtml(e, n));
                }

                return i.find(".hiprint-printElement-table-content").append(o.target), i;
            }, TablePrintElement.prototype.createGridColumnsStructure = function (t) {
                for (var e = $('<div class="hi-grid-row table-grid-row"></div>'), n = 0; n < this.options.getGridColumns(); n++) {
                    var i = $('<div class="tableGridColumnsGutterRow hi-grid-col" style="width:' + 100 / this.options.getGridColumns() + '%;"></div>');
                    e.append(i);
                }

                var o = this.getGridColumnsFooterFormatter();

                if (o) {
                    var r = $('<div class="hiprint-gridColumnsFooter"></div>');
                    r.append(o(this.options, this.getData(t), t, [])), e.append(r);
                }

                return new _table_GridColumnsStructure__WEBPACK_IMPORTED_MODULE_8__.a(this.options.getGridColumns(), e);
            }, TablePrintElement.prototype.createtempEmptyRowsTargetStructure = function (t) {
                if (this.getField()) return this.createTarget(this.printElementType.title, []);
                var e = this.createTarget(this.printElementType.title, []).clone();
                return e.find(".hiprint-printElement-tableTarget tbody tr").remove(), e;
            }, TablePrintElement.prototype.getTableHtml = function (t, e) {
                var n, i;
                if (!this.getField() && this.options.content) return (n = $("<div></div>")).append(this.options.content), (i = n.find("table")).addClass("hiprint-printElement-tableTarget"), i;
                if (this.printElementType.formatter) return (n = $("<div></div>")).append(this.printElementType.formatter(t)), (i = n.find("table")).addClass("hiprint-printElement-tableTarget"), i;
                var o = $('<table class="hiprint-printElement-tableTarget" style="border-collapse: collapse;"></table>');
                return o.append(_table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.createTableHead(this.getColumns(), this.options.getWidth() / this.options.getGridColumns())), o.append(_table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.createTableRow(this.getColumns(), t, this.options, this.printElementType)), this.getFooterFormatter() && ("no" == this.options.tableFooterRepeat || ("last" == this.options.tableFooterRepeat ? o.find("tbody").append(_table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.createTableFooter(this.printElementType.columns, t, this.options, this.printElementType, e, t).html()) : o.append(_table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.createTableFooter(this.printElementType.columns, t, this.options, this.printElementType, e, [])))), o;
            }, TablePrintElement.prototype.getEmptyRowTarget = function () {
                return _table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.createEmptyRowTarget(this.getColumns());
            }, TablePrintElement.prototype.getHtml = function (t, e) {
                this.createTempContainer();
                var n = this.getPaperHtmlResult(t, e);
                return this.removeTempContainer(), n;
            }, TablePrintElement.prototype.getPaperHtmlResult = function (t, e) {
                var n = [],
                    i = this.getData(e),
                    o = this.getTableHtml(i, e),
                    r = this.createtempEmptyRowsTargetStructure(e);
                e ? this.updateTargetWidth(r) : this.updateTargetSize(r), this.css(r, i), this.css(o, i), this.getTempContainer().html(""), this.getTempContainer().append(r);

                for (var a, p = this.getBeginPrintTopInPaperByReferenceElement(t), s = 0, l = !1; !l;) {
                    var u = 0,
                        d = t.getPaperFooter(s);
                    0 == s && p > d && (p = p - d + t.paperHeader, n.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_2__.a({
                        target: void 0,
                        printLine: void 0
                    })), u = t.getContentHeight(s) - (p - t.paperHeader), s++ , d = t.getPaperFooter(s));
                    var c = n.length > 0 ? n[n.length - 1].target : void 0,
                        h = this.getRowsInSpecificHeight(e, u > 0 ? u : 0 == s ? d - p : t.getContentHeight(s), r, o, s, c);
                    l = h.isEnd;
                    var f = void 0;
                    h.target && (h.target.css("left", this.options.displayLeft()), h.target[0].height = ""), 0 == s || u > 0 ? (h.target && (a = p, h.target.css("top", p + "pt")), f = l && null != this.options.lHeight ? p + (h.height > this.options.lHeight ? h.height : this.options.lHeight) : p + h.height) : (h.target && (a = t.paperHeader, h.target.css("top", t.paperHeader + "pt")), f = t.paperHeader + h.height), n.push(new _dto_PaperHtmlResult__WEBPACK_IMPORTED_MODULE_2__.a({
                        target: h.target,
                        printLine: f,
                        referenceElement: new _PrintReferenceElement__WEBPACK_IMPORTED_MODULE_4__.a({
                            top: this.options.getTop(),
                            left: this.options.getLeft(),
                            height: this.options.getHeight(),
                            width: this.options.getWidth(),
                            beginPrintPaperIndex: t.index,
                            bottomInLastPaper: f,
                            printTopInPaper: a
                        })
                    })), s++;
                }

                return n;
            }, TablePrintElement.prototype.getRowsInSpecificHeight = function (t, e, n, i, o, r) {
                var a = i.find("tbody"),
                    p = _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.pt.toPx(e);

                n.find(".hiprint-printElement-tableTarget tbody").html("");
                var s = n.outerHeight();
                if (s > p) return {
                    target: void 0,
                    length: 0,
                    height: 0,
                    isEnd: !1
                };

                for (var l = [], u = 0; u < this.options.getGridColumns(); u++) {
                    for (var d = n.find(".hiprint-printElement-tableTarget:eq(" + u + ")"), c = void 0, h = []; ;) {
                        if (s <= p) if (0 == a.find("tr").length) c = {
                            height: _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.px.toPt(s),
                            isEnd: !0
                        }, t && this.options.autoCompletion && (this.autoCompletion(p, d), s = n.outerHeight()); else {
                            var f = a.find("tr:lt(1)");
                            d.find("tbody").append(f);
                            var g = f.data("rowData");
                            l.push(g), h.push(g), (s = n.outerHeight()) > p && (a.prepend(f), l.pop(), h.pop(), s = n.outerHeight(), c = {
                                height: _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.px.toPt(s),
                                isEnd: !1
                            });
                        }

                        if (c) {
                            if (this.getFooterFormatter()) d.find("tfoot").length && d.find("tfoot").html(_table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.createTableFooter(this.printElementType.columns, this.getData(t), this.options, this.printElementType, t, h).html());
                            break;
                        }
                    }
                }

                var m = n.find(".hiprint-printElement-tableTarget tbody tr").length,
                    v = this.getGridColumnsFooterFormatter();
                return v && n.find(this.gridColumnsFooterCss).html(v(this.options, this.getData(t), t, l)), 0 == a.find("tr").length ? 0 == m && r ? {
                    target: void 0,
                    length: 0,
                    height: 0,
                    isEnd: !0
                } : {
                        target: n.clone(),
                        length: m,
                        height: _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.px.toPt(s),
                        isEnd: !0
                    } : {
                        target: n.clone(),
                        length: m,
                        height: _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.px.toPt(s),
                        isEnd: !1
                    };
            }, TablePrintElement.prototype.autoCompletion = function (t, e) {
                for (var n, i = this.getEmptyRowTarget(), o = e.outerHeight(); t > o;) {
                    n = i.clone(), e.find("tbody").append(n), o = e.outerHeight();
                }

                n && n.remove();
            }, TablePrintElement.prototype.getData = function (t) {
                if (!t) return [{}];
                var e = t[this.getField()];
                return e ? JSON.parse(JSON.stringify(e)) : [];
            }, TablePrintElement.prototype.onResize = function (t, e, n, i, o) {
                _super.prototype.updateSizeAndPositionOptions.call(this, o, i, n, e), _table_TableExcelHelper__WEBPACK_IMPORTED_MODULE_6__.a.resizeTableCellWidth(this.designTarget, this.getColumns(), this.options.getWidth());
            }, TablePrintElement.prototype.getReizeableShowPoints = function () {
                return ["s", "e"];
            }, TablePrintElement.prototype.design = function (t, e) {
                var n = this;
                this.designTarget.hidraggable({
                    handle: this.designTarget.find(".hiprint-printElement-table-handle"),
                    axis: n.options.axis && t && t.axisEnabled ? n.options.axis : void 0,
                    onDrag: function onDrag(t, i, o) {
                        n.updateSizeAndPositionOptions(i, o), n.createLineOfPosition(e);
                    },
                    moveUnit: "pt",
                    minMove: _HiPrintConfig__WEBPACK_IMPORTED_MODULE_1__.a.instance.movingDistance,
                    onBeforeDrag: function onBeforeDrag(t) {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_9__.a.instance.draging = !0, n.createLineOfPosition(e);
                    },
                    onStopDrag: function onStopDrag(t) {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_9__.a.instance.draging = !1, n.removeLineOfPosition();
                    }
                }), this.printElementType.editable && this.setHitable(), this.setColumnsOptions(), this.designTarget.hireizeable({
                    showPoints: n.getReizeableShowPoints(),
                    noContainer: !0,
                    onBeforeResize: function onBeforeResize() {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_9__.a.instance.draging = !0;
                    },
                    onResize: function onResize(t, i, o, r, a) {
                        n.onResize(t, i, o, r, a), n.hitable && n.hitable.updateColumnGrips(), n.createLineOfPosition(e);
                    },
                    onStopResize: function onStopResize() {
                        _HiPrintlib__WEBPACK_IMPORTED_MODULE_9__.a.instance.draging = !1, n.removeLineOfPosition();
                    }
                }), this.bingKeyboardMoveEvent(this.designTarget, e);
            }, TablePrintElement.prototype.setHitable = function () {
                var t = this;
                this.hitable = new _hitable_HiTale__WEBPACK_IMPORTED_MODULE_7__.a({
                    table: this.designTarget.find(".hiprint-printElement-tableTarget:eq(0)"),
                    rows: this.getColumns(),
                    resizeRow: !1,
                    resizeColumn: !0,
                    trs: this.designTarget.find(".hiprint-printElement-tableTarget:eq(0)").find("tbody tr"),
                    handle: this.designTarget.find(".hiprint-printElement-tableTarget:eq(0)").find("thead"),
                    isEnableEdit: this.printElementType.editable,
                    columnDisplayEditable: this.printElementType.columnDisplayEditable,
                    columnDisplayIndexEditable: this.printElementType.columnDisplayIndexEditable,
                    columnResizable: this.printElementType.columnResizable,
                    columnAlignEditable: this.printElementType.columnAlignEditable,
                    isEnableEditText: this.printElementType.columnTitleEditable,
                    isEnableEditField: !1,
                    isEnableContextMenu: !0,
                    isEnableInsertRow: !1,
                    isEnableDeleteRow: !1,
                    isEnableInsertColumn: !1,
                    isEnableDeleteColumn: !1,
                    isEnableMergeCell: !1
                }), _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.event.on("updateTable" + this.hitable.id, function () {
                    t.updateDesignViewFromOptions();
                });
            }, TablePrintElement.prototype.setColumnsOptions = function () {
                var t = this;
                this.designTarget.find(".hiprint-printElement-tableTarget:eq(0)").find("thead td").bind("click.hiprint", function (e) {
                    var n = $(e.target).attr("column-id"),
                        i = t.getColumnByColumnId(n);

                    if (i) {
                        var o = t.getPrintElementOptionItemsByName("tableColumn");

                        _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.event.trigger(t.getPrintElementSelectEventKey(), {
                            printElement: t,
                            customOptionsInput: [{
                                title: i.title + "-列属性",
                                optionItems: o,
                                options: i,
                                callback: function callback(t) {
                                    o.forEach(function (t) {
                                        var e = t.getValue();
                                        i[t.name] = e;
                                    });
                                }
                            }]
                        });
                    } else _assets_plugins_hinnn__WEBPACK_IMPORTED_MODULE_3__.a.event.trigger(t.getPrintElementSelectEventKey(), {
                        printElement: t
                    });
                });
            }, TablePrintElement.prototype.filterOptionItems = function (t) {
                var e = _super.prototype.filterOptionItems.call(this, t);

                return this.printElementType.editable && 1 == this.options.columns.length ? e : t.filter(function (t) {
                    return "columns" != t.name;
                });
            }, TablePrintElement.prototype.getFooterFormatter = function () {
                var footerFormatter = void 0;
                if (this.printElementType.footerFormatter && (footerFormatter = this.printElementType.footerFormatter), this.options.footerFormatter) try {
                    var s = "footerFormatter=" + this.options.footerFormatter;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return footerFormatter;
            }, TablePrintElement.prototype.getGridColumnsFooterFormatter = function () {
                var gridColumnsFooterFormatter = void 0;
                if (this.printElementType.gridColumnsFooterFormatter && (gridColumnsFooterFormatter = this.printElementType.gridColumnsFooterFormatter), this.options.gridColumnsFooterFormatter) try {
                    var s = "gridColumnsFooterFormatter=" + this.options.gridColumnsFooterFormatter;
                    eval(s);
                } catch (t) {
                    console.log(t);
                }
                return gridColumnsFooterFormatter;
            }, TablePrintElement;
        }(_BasePrintElement__WEBPACK_IMPORTED_MODULE_0__.a);
}, function (t, e, n) {
    "use strict";

    var i = function () {
        return function (t) {
            this.table = t.table, this.isEnableEdit = t.isEnableEdit, this.trs = t.trs, this.resizeRow = t.resizeRow, this.resizeColumn = t.resizeColumn, this.isEnableEditField = t.isEnableEditField, this.isEnableContextMenu = t.isEnableContextMenu, this.isEnableEditField = t.isEnableEditField, this.isEnableInsertRow = t.isEnableInsertRow, this.isEnableDeleteRow = t.isEnableDeleteRow, this.isEnableInsertColumn = t.isEnableInsertColumn, this.isEnableDeleteColumn = t.isEnableDeleteColumn, this.isEnableMergeCell = t.isEnableMergeCell, this.columnResizable = t.columnResizable, this.columnAlignEditable = t.columnAlignEditable;
        };
    }(),
        o = function () {
            function t(t) {
                this.options = new i(t);
            }

            return t.prototype.enableEidt = function () {
                this.options.isEnableEdit;
            }, t.prototype.disableEdit = function () {
                this.options.isEnableEdit;
            }, t.prototype.isEnableEdit = function () {
                return this.options.isEnableEdit;
            }, t;
        }(),
        r = n(0),
        a = function () {
            return function (t) {
                this.cell = t.cell, this.link = t.link, this.linkType = t.linkType, this.bottom = t.bottom, this.rightMost = t.rightMost, this.rowLevel = t.rowLevel, this.columnLevel = t.columnLevel, this.indexInTableGridRow = t.indexInTableGridRow, this.indexInTableGridColumn = t.indexInTableGridColumn;
            };
        }(),
        p = n(10),
        s = function () {
            function t() { }

            return t.getLeftTableCell = function (t, e) {
                var n;
                return t.forEach(function (t, i) {
                    t.cell && i < e && (n = t.cell);
                }), n;
            }, t.getIndex = function (t, e) {
                var n;
                return t.forEach(function (t, i) {
                    t.cell && t.cell.id == e && (n = i);
                }), n;
            }, t;
        }(),
        l = n(13),
        u = n(11),
        d = function () {
            return function (t, e) {
                this.target = t, this.grips = e;
            };
        }(),
        c = function () {
            return function (t) {
                this.target = t;
            };
        }(),
        h = function () {
            return function () {
                this.rowColumns = [];
            };
        }(),
        f = function () {
            function t() { }

            return t.getColumnsWidth = function (e, n) {
                var i = {},
                    o = t.allAutoWidth(e);
                return e.rowColumns.forEach(function (t) {
                    var e = n - 0,
                        r = t.width / o * (e > 0 ? e : 0);
                    i[t.id] = r;
                }), i;
            }, t.resizeTableCellWeight = function (t) {
                t.forEach(function (t) {
                    t.columns.forEach(function (t) {
                        t.hasWidth && $(t.getTarget()).css("width", t.width + "pt");
                    });
                });
            }, t.allAutoWidth = function (t) {
                var e = 0;
                return t.rowColumns.forEach(function (t) {
                    e += t.width;
                }), e;
            }, t.reconsitutionTableColumnTree = function (t, e, n) {
                for (var i = e || new h(), o = function o(e) {
                    i.totalLayer = e + 1, i[e] = t[e].columns, i.rowColumns = i.rowColumns.concat(i[e].filter(function (n) {
                        return n.rowspan == t.length - e;
                    }));
                }, r = 0; r < t.length; r++) {
                    o(r);
                }

                return i;
            }, t;
        }(),
        g = n(2),
        m = function () {
            function t(t) {
                this.signature = "HiTresizer", this.hitable = t, this.rows = t.rows, this.target = t.target;
            }

            return t.prototype.init = function () {
                this.addResizeRowAndColumn(), this.hitable.optionsCoat.options.resizeColumn && this.createColumnGrips(), this.hitable.optionsCoat.options.resizeRow && this.createRowGrips();
            }, t.prototype.resizeTableCellWidth = function () {
                f.resizeTableCellWeight(this.rows);
            }, t.prototype.addResizeRowAndColumn = function () { }, t.prototype.createColumnGrips = function () {
                var t = this,
                    e = this,
                    n = [],
                    i = $('<div class="columngrips"/>');
                i.width(this.target.width()), this.rows.forEach(function (o) {
                    o.columns.forEach(function (o, a) {
                        if (o.getTarget().attr("haswidth")) {
                            var p = $('<div class="columngrip"><div class="gripResizer"></div></div>');
                            i.append(p);
                            var s = new c(p);
                            n.length > 0 && (n[n.length - 1].nextGrip = s), n.push(s), t.syncGrips(o, s), $(p).hidraggable({
                                axis: "h",
                                onDrag: function onDrag(t, e, n) { },
                                moveUnit: "pt",
                                minMove: 1,
                                onBeforeDrag: function onBeforeDrag(t) {
                                    if (g.a.instance.draging = !0, !s.nextGrip) return !1;
                                    e.dragingGrip = s, e.dragingGrip.left = parseFloat(e.dragingGrip.target.css("left").replace("px", "")), s.target.addClass("columngripDraging");
                                },
                                onStopDrag: function onStopDrag(n) {
                                    g.a.instance.draging = !1;
                                    var i = parseFloat(e.dragingGrip.target.css("left").replace("px", "")),
                                        o = r.a.px.toPt(i - e.dragingGrip.left);
                                    s.cell.width = s.cell.width + o, s.nextGrip.cell.width = s.nextGrip.cell.width - o, t.resizeTableCellWidth(), s.target.removeClass("columngripDraging"), e.updateColumnGrips();
                                }
                            });
                        }
                    });
                }), this.target.before(i), this.cgripContariner = new d(i, n);
            }, t.prototype.updateColumnGrips = function () {
                this.cgripContariner && (this.cgripContariner.target.remove(), this.createColumnGrips());
            }, t.prototype.updateRowGrips = function () {
                this.rgripContariner && (this.rgripContariner.target.remove(), this.createRowGrips());
            }, t.prototype.createRowGrips = function () {
                var t = this,
                    e = this,
                    n = [],
                    i = $('<div class="rowgrips"/>');
                this.rows.forEach(function (o, a) {
                    var p = $('<div class="rowgrip"><div class="gripResizer"></div></div>');
                    i.append(p);
                    var s = new c(p);
                    n.push(s), a > 0 && a < t.rows.length && $(p).hidraggable({
                        axis: "v",
                        onDrag: function onDrag(t, e, n) { },
                        moveUnit: "pt",
                        minMove: 1,
                        onBeforeDrag: function onBeforeDrag(t) {
                            e.dragingGrip = s, e.dragingGrip.top = parseFloat(e.dragingGrip.target.css("top").replace("px", "")), s.target.addClass("rowgripDraging");
                        },
                        onStopDrag: function onStopDrag(t) {
                            var n = parseFloat(e.dragingGrip.target.css("top").replace("px", "")),
                                i = r.a.px.toPt(n - e.dragingGrip.top + e.rows[a].columns[0].getTarget().height());
                            e.rows[a].columns[0].getTarget().css("height", i + "pt"), e.syncRowGrips(), s.target.removeClass("rowgripDraging");
                        }
                    });
                }), this.target.before(i), this.rgripContariner = new d(i, n), this.syncRowGrips();
            }, t.prototype.syncGrips = function (t, e) {
                var n = t.getTarget();
                e.cell = t, e.target.css({
                    left: n.offset().left - this.target.offset().left + n.outerWidth(!1),
                    height: 30
                });
            }, t.prototype.syncRowGrips = function () {
                var t = this;
                this.rgripContariner.target.height(this.target.height()), this.rows.forEach(function (e, n) {
                    var i = e.columns[0].getTarget();
                    t.rgripContariner.grips[n].target.css({
                        top: i.offset().top - t.target.offset().top + i.outerHeight(!1),
                        width: 30
                    });
                });
            }, t.prototype.addResizerHeadRow = function () {
                this.target.find("thead").prepend();
            }, t;
        }(),
        v = function () {
            function t() { }

            return t.prototype.init = function () { }, t.prototype.updateRowGrips = function () { }, t.prototype.updateColumnGrips = function () { }, t;
        }();

    n.d(e, "a", function () {
        return y;
    });

    var y = function () {
        function t(t) {
            this.id = u.a.createId(), this.optionsCoat = new o(t), this.handle = t.handle, this.target = t.table, this.initRows(t.rows), this.init(t), this.tableCellSelector = new p.a(this.rows, this.target), this.resizer = this.optionsCoat.options.columnResizable ? new m(this) : new v(), this.resizer.init();
        }

        return t.prototype.insertRow = function (t, e, n) {
            var i = e || this.tableCellSelector.getSingleSelect(),
                o = i.cell,
                a = this.rows[i.rowIndex],
                p = i.rowIndex,
                s = this.getCellGrid(),
                u = new l.a();
            if (u.init(this.optionsCoat, void 0, a.isHead), n && u.getTarget().addClass(n), "above" == t) s[p].forEach(function (t) {
                var e = t.link ? t.link : t.cell,
                    n = e.width / e.colspan;

                if (0 == t.columnLevel) {
                    var i = u.createTableCell();
                    i.width = n, u.insertCellToLast(i);
                } else {
                    if ("column" == t.linkType) {
                        var o = t.link.getTarget();
                        t.link.rowspan += 1, o.attr("rowspan", t.link.rowspan);
                    }

                    t.linkType;
                }
            }), this.rows.splice(p, 0, u), a.getTarget().before(u.getTarget()), r.a.event.trigger("newRow" + this.id, u); else {
                var d = p + o.rowspan - 1;
                s[d].forEach(function (t) {
                    var e = t.link ? t.link : t.cell,
                        n = e.width / e.colspan;

                    if (t.bottom) {
                        var i = u.createTableCell();
                        i.width = n, u.insertCellToLast(i);
                    } else {
                        if (t.cell) {
                            var o = t.cell.getTarget();
                            t.cell.rowspan += 1, o.attr("rowspan", t.cell.rowspan);
                        }

                        if ("column" == t.linkType) {
                            o = t.link.getTarget();
                            t.link.rowspan += 1, o.attr("rowspan", t.link.rowspan);
                        }
                    }
                }), this.rows.splice(d + 1, 0, u), this.rows[d].getTarget().after(u.getTarget()), r.a.event.trigger("newRow" + this.id, u);
            }
        }, t.prototype.insertColumn = function (t, e, n, i) {
            var o = this,
                a = this.rows.concat(this.trRows),
                p = e || this.tableCellSelector.getSingleSelect(),
                s = p.cell,
                l = p.rowIndex,
                u = this.getCellGrid(a),
                d = u[l].filter(function (t) {
                    return t.cell && t.cell.id == s.id || t.link && t.link.id == s.id;
                });

            if ("left" == t) {
                var c = d[0].indexInTableGridRow;
                u.forEach(function (t, e) {
                    var p = t[c],
                        s = t.filter(function (t, e) {
                            return e >= c && t.cell;
                        });

                    if (0 == p.rowLevel) {
                        var l = a[e],
                            u = a[e].createTableCell();
                        n && u.getTarget().addClass(n), null != i && (u.width = i), s.length ? l.insertToTargetCellLeft(s[0].cell, u) : l.insertCellToLast(u), r.a.event.trigger("newCell" + o.id, u);
                    } else if ("row" == p.linkType) {
                        var d = p.link.getTarget();
                        p.link.colspan += 1, d.attr("colspan", p.link.colspan);
                    }
                });
            } else {
                var h = d[d.length - 1].indexInTableGridRow;
                u.forEach(function (t, e) {
                    var p = t[h],
                        s = t.filter(function (t, e) {
                            return e <= h && t.cell;
                        });

                    if (p.rightMost) {
                        var l = a[e],
                            u = l.createTableCell();
                        n && u.getTarget().addClass(n), null != i && (u.width = i), s.length ? l.insertToTargetCellRight(s[s.length - 1].cell, u) : l.insertCellToFirst(u), r.a.event.trigger("newCell" + o.id, u);
                    } else {
                        var d = p.link || p.cell;

                        if ("row" == p.linkType) {
                            var c = d.getTarget();
                            d.colspan += 1, c.attr("colspan", d.colspan);
                        }

                        if (p.cell) {
                            c = d.getTarget();
                            d.colspan += 1, c.attr("colspan", d.colspan);
                        }
                    }
                });
            }
        }, t.prototype.deleteRow = function () {
            var t = this,
                e = this.tableCellSelector.getSingleSelect(),
                n = (e.cell, this.rows[e.rowIndex], e.rowIndex),
                i = this.getCellGrid(),
                o = this.rows[n];
            i[n].forEach(function (e, r) {
                if (e.cell) {
                    if (1 == e.cell.rowspan) o.removeCell(e.cell); else {
                        o.removeCell(e.cell);
                        var a = i[n + 1].filter(function (t, e) {
                            return t.cell && e > r;
                        }),
                            p = t.rows[n + 1],
                            s = p.createTableCell(e.cell.rowspan - 1, e.cell.colspan);
                        a.length ? p.insertToTargetCellLeft(a[0].cell, s) : p.insertCellToLast(s);
                    }
                } else if ("column" == e.linkType) {
                    var l = e.link;
                    l.rowspan -= 1, l.getTarget().attr("rowspan", l.rowspan);
                }
            }), o.getTarget().remove(), this.rows.splice(n, 1);
        }, t.prototype.deleteColums = function () {
            var t = this.rows.concat(this.trRows),
                e = this.tableCellSelector.getSingleSelect(),
                n = e.cell,
                i = e.rowIndex,
                o = this.getCellGrid(t),
                r = o[i].filter(function (t) {
                    return t.cell && t.cell.id == n.id || t.link && t.link.id == n.id;
                })[0].indexInTableGridRow;
            o.forEach(function (e, n) {
                var i = e[r];
                i.cell ? 1 == i.cell.colspan ? t[n].removeCell(i.cell) : (i.cell.colspan -= 1, i.cell.getTarget().attr("colspan", i.cell.colspan)) : "row" == i.linkType && (i.link.colspan -= 1, i.link.getTarget().attr("colspan", i.link.colspan));
            });
        }, t.prototype.mergeCell = function () {
            var t = this,
                e = this.tableCellSelector.getSelectedCells();

            if (0 != e.length) {
                var n = e[0][0].cell;
                e.forEach(function (i, o) {
                    i.forEach(function (i, r) {
                        0 == o ? 0 != r && (n.colspan += i.cell.colspan, t.rows[i.rowIndex].removeCell(i.cell)) : t.rows[i.rowIndex].removeCell(i.cell), 0 == r && e[0][0].rowIndex + n.rowspan - 1 < i.rowIndex && (n.rowspan += i.cell.rowspan);
                    });
                }), n.getTarget().attr("colspan", n.colspan), n.getTarget().attr("rowspan", n.rowspan), this.tableCellSelector.setSingleSelect(e[0][0]);
            }
        }, t.prototype.splitCell = function () {
            var t = this.tableCellSelector.getSingleSelect(),
                e = this.getCellGrid(),
                n = s.getIndex(e[t.rowIndex], t.cell.id);

            if (t) {
                for (var i = t.rowIndex; i < t.rowIndex + t.cell.rowspan; i++) {
                    for (var o = this.rows[i], r = i == t.rowIndex ? t.cell : s.getLeftTableCell(e[i], n), a = 0; a < t.cell.colspan; a++) {
                        i == t.rowIndex && 0 == a || (r ? o.insertToTargetCellRight(r, o.createTableCell()) : o.insertCellToFirst(o.createTableCell()));
                    }
                }

                t.cell.rowspan = 1, t.cell.colspan = 1, t.cell.getTarget().attr("colspan", t.cell.colspan), t.cell.getTarget().attr("rowspan", t.cell.rowspan);
            }
        }, t.prototype.init = function (t) {
            var e = this;
            $(this.target).addClass("hitable"), this.optionsCoat.onBeforEdit = function (n) {
                if (e.optionsCoat.options.onBeforEdit && !1 === t.onBeforEdit(n)) return !1;
                return e.optionsCoat.editingCell && e.optionsCoat.editingCell.endEdit(), !0;
            }, $(this.target).mousedown(function (t) {
                e.optionsCoat.isLeftMouseButtonDown = !0;
            }), $(this.target).mouseup(function (t) {
                e.optionsCoat.isLeftMouseButtonDown = !1;
            }), this.initContext(), this.target.on("mousemove", function (t) {
                1 === t.buttons && e.tableCellSelector.multipleSelectByXY(t.pageX, t.pageY);
            }).on("mousedown", function (t) {
                1 === t.buttons && e.tableCellSelector.singleSelectByXY(t.pageX, t.pageY);
            });
        }, t.prototype.initRows = function (t) {
            var e = this;

            if (this.trRows = [], t) {
                this.rows = t, t.forEach(function (t, n) {
                    t.init(e.optionsCoat, e.target.find("tr:eq(" + n + ")"), !0);
                });
                var n = this.optionsCoat.options.trs;
                n && this.initRowsByTrs(n).forEach(function (t) {
                    e.trRows.push(t);
                });
            } else this.rows = this.initRowsByTrs(this.target.find("tr"));
        }, t.prototype.initRowsByTrs = function (t) {
            var e = this;
            return t.map(function (t, n) {
                var i = new l.a();
                return i.init(e.optionsCoat, $(n)), i;
            }).get();
        }, t.prototype.enableEidt = function () {
            this.optionsCoat.enableEidt();
        }, t.prototype.disableEdit = function () {
            this.optionsCoat.disableEdit();
        }, t.prototype.getCellGrid = function (t) {
            var e = t || this.rows,
                n = this.getColumnStep(),
                i = new Array();
            return e.forEach(function (t, e) {
                t.columns.forEach(function (t, o) {
                    for (var r = 0; r < t.colspan; r++) {
                        for (var p = 0, s = !1; p < n && !s;) {
                            if (i[e] = i[e] || [], i[e][p]); else {
                                i[e][p] = new a({
                                    cell: 0 == r ? t : void 0,
                                    link: 0 != r ? t : void 0,
                                    linkType: r > 0 ? "row" : void 0,
                                    rightMost: r == t.colspan - 1 || void 0,
                                    bottom: 0 == t.rowspan - 1,
                                    rowLevel: r,
                                    columnLevel: 0,
                                    indexInTableGridRow: p,
                                    indexInTableGridColumn: e
                                });

                                for (var l = e + 1, u = 1; u < t.rowspan; u++) {
                                    i[l] = i[l] || [], i[l][p] = new a({
                                        cell: void 0,
                                        link: t,
                                        linkType: r > 0 ? "rowColumn" : "column",
                                        rightMost: r == t.colspan - 1 || void 0,
                                        bottom: u == t.rowspan - 1,
                                        rowLevel: r,
                                        columnLevel: u,
                                        indexInTableGridRow: p,
                                        indexInTableGridColumn: l
                                    }), l += 1;
                                }

                                s = !0;
                            }
                            p++;
                        }
                    }
                });
            }), i;
        }, t.prototype.setAlign = function (t) {
            var e = this.tableCellSelector.getSingleSelect();
            e && e.cell.setAlign(t);
        }, t.prototype.setVAlign = function (t) {
            var e = this.tableCellSelector.getSingleSelect();
            e && e.cell.setVAlign(t);
        }, t.prototype.getColumnStep = function (t) {
            var e = 0;
            return this.rows.length && this.rows[t || 0].columns.forEach(function (t) {
                e += t.colspan;
            }), e;
        }, t.prototype.initContext = function () {
            var t = this;
            if (!this.optionsCoat.options.isEnableContextMenu) return !1;
            $(this.handle).hicontextMenu({
                menus: [{
                    text: "在上方插入行",
                    enabled: this.optionsCoat.options.isEnableInsertRow,
                    disable: function disable() {
                        return !t.tableCellSelector.getSingleSelect();
                    },
                    callback: function callback() {
                        t.insertRow("above"), t.resizer.updateRowGrips(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "在下方插入行",
                    borderBottom: !0,
                    enabled: this.optionsCoat.options.isEnableInsertRow,
                    disable: function disable() {
                        return !t.tableCellSelector.getSingleSelect();
                    },
                    callback: function callback() {
                        t.insertRow("below"), t.resizer.updateRowGrips(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "向左方插入列",
                    enabled: this.optionsCoat.options.isEnableInsertColumn,
                    disable: function disable() {
                        return !t.tableCellSelector.getSingleSelect();
                    },
                    callback: function callback() {
                        t.insertColumn("left"), t.resizer.updateColumnGrips(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "向右方插入列",
                    enabled: this.optionsCoat.options.isEnableInsertColumn,
                    disable: function disable() {
                        return !t.tableCellSelector.getSingleSelect();
                    },
                    borderBottom: !0,
                    callback: function callback() {
                        t.insertColumn("right"), t.resizer.updateColumnGrips(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "删除行",
                    enabled: this.optionsCoat.options.isEnableDeleteRow,
                    disable: function disable() {
                        return !t.tableCellSelector.getSingleSelect();
                    },
                    callback: function callback() {
                        t.deleteRow(), t.resizer.updateRowGrips(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "删除列",
                    borderBottom: !0,
                    enabled: this.optionsCoat.options.isEnableDeleteColumn,
                    disable: function disable() {
                        return !t.tableCellSelector.getSingleSelect();
                    },
                    callback: function callback() {
                        t.deleteColums(), t.resizer.updateColumnGrips(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "对齐",
                    borderBottom: !0,
                    enabled: this.optionsCoat.options.columnAlignEditable,
                    menus: [{
                        text: "左",
                        callback: function callback() {
                            t.setAlign("left");
                        }
                    }, {
                        text: "左右居中",
                        callback: function callback() {
                            t.setAlign("center");
                        }
                    }, {
                        text: "右",
                        callback: function callback() {
                            t.setAlign("right");
                        }
                    }, {
                        text: "默认",
                        borderBottom: !0,
                        callback: function callback() {
                            t.setAlign("");
                        }
                    }, {
                        text: "上",
                        callback: function callback() {
                            t.setVAlign("top");
                        }
                    }, {
                        text: "垂直居中",
                        callback: function callback() {
                            t.setVAlign("middle");
                        }
                    }, {
                        text: "下",
                        callback: function callback() {
                            t.setVAlign("bottom");
                        }
                    }, {
                        text: "默认",
                        callback: function callback() {
                            t.setVAlign("");
                        }
                    }]
                }, {
                    text: "合并单元格",
                    enabled: this.optionsCoat.options.isEnableMergeCell,
                    disable: function disable() {
                        return t.tableCellSelector.getSingleSelect();
                    },
                    callback: function callback() {
                        t.mergeCell(), r.a.event.trigger("updateTable" + t.id);
                    }
                }, {
                    text: "解开单元格",
                    enabled: this.optionsCoat.options.isEnableMergeCell,
                    disable: function disable() {
                        var e = t.tableCellSelector.getSingleSelect();
                        return !e || 1 == e.cell.rowspan && 1 == e.cell.colspan;
                    },
                    callback: function callback() {
                        t.splitCell(), r.a.event.trigger("updateTable" + t.id);
                    }
                }].filter(function (t) {
                    return t.enabled;
                })
            });
        }, t.prototype.getTableWidth = function () {
            return r.a.px.toPt(this.target.outerWidth(!1));
        }, t.prototype.updateColumnGrips = function () {
            this.resizer.updateColumnGrips();
        }, t.prototype.updateRowGrips = function () {
            this.resizer.updateRowGrips();
        }, t;
    }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return i;
    });

    var i = function () {
        return function (t, e, n) {
            this.tid = t, this.options = e, this.printElementType = n;
        };
    }();
}, function (t, e, n) {
    "use strict";

    var i = n(3),
        o = n(12),
        r = (function () { }(), function () {
            return function (t) {
                this.width = t.width, this.title = t.title, this.columnId = t.columnId, this.fixed = !1, this.rowspan = t.rowspan || 1, this.colspan = t.colspan || 1, this.align = t.align, this.halign = t.halign, this.vAlign = t.vAlign, this.formatter2 = t.formatter2, this.styler2 = t.styler2, this.field=t.field;
            };
        }()),
        a = n(5);
    n.d(e, "a", function () {
        return l;
    });

    var _p,
        s = (_p = function p(t, e) {
            return (_p = Object.setPrototypeOf || _instanceof({
                __proto__: []
            }, Array) && function (t, e) {
                t.__proto__ = e;
            } || function (t, e) {
                for (var n in e) {
                    e.hasOwnProperty(n) && (t[n] = e[n]);
                }
            })(t, e);
        }, function (t, e) {
            function n() {
                this.constructor = t;
            }

            _p(t, e), t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
        }),
        l = function (t) {
            function e(e, n) {
                var i = this;
                (e = e || {}, (i = t.call(this, e) || this).lHeight = e.lHeight, i.autoCompletion = e.autoCompletion, i.tableFooterRepeat = e.tableFooterRepeat, n) && (i.columns = [], n.editable && e.columns && e.columns.length ? e.columns.forEach(function (t) {
                    var e = [];
                    t.forEach(function (t) {
                        var i = new r(t),
                            o = n.getColumnByColumnId(i.columnId),
                            p = o ? $.extend(o, i) : new a.a(i);
                        p.checked = !0, e.push(p);
                    }), i.columns.push(new o.a(e));
                }) : n.columns.forEach(function (t) {
                    i.columns.push(new o.a(t.filter(function (t) {
                        return t.checked;
                    })));
                }));
                return i;
            }

            return s(e, t), e.prototype.getColumnByColumnId = function (t) {
                return this.makeColumnObj()[t];
            }, e.prototype.makeColumnObj = function () {
                var t = {};
                return this.columns && this.columns.forEach(function (e) {
                    e.columns.forEach(function (e) {
                        e.columnId && (t[e.columnId] = e);
                    });
                }), t;
            }, e.prototype.getGridColumns = function () {
                return this.gridColumns || 1;
            }, e.prototype.getPrintElementOptionEntity = function () {
                var e = t.prototype.getPrintElementOptionEntity.call(this);
                return this.columns && (e.columns = [], this.columns.forEach(function (t) {
                    var n = t.getPrintElementOptionEntity().filter(function (t) {
                        return t.checked;
                    }).map(function (t) {
                        return new r(t);
                    });
                    e.columns.push(n);
                })), e;
            }, e;
        }(i.a);
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return i;
    });

    var i = function () {
        return function () {
            this.rowColumns = [];
        };
    }();
}, function (t, e, n) {
    "use strict";

    n.d(e, "a", function () {
        return i;
    });

    var i = function () {
        function t(t, e) {
            this.gridColumns = t, this.target = e;
        }

        return t.prototype.getByIndex = function (t) {
            return this.target.find(".hi-grid-col:eq(" + t + ")");
        }, t;
    }();
}, function (t, e, n) {
    t.exports = n(33);
}, function (t, e) {
    !function (t) {
        function e(e) {
            var n = t.data(e.data.target, "hidraggable"),
                i = n.options,
                o = n.proxy,
                r = e.data,
                a = r.startLeft + e.pageX - r.startX,
                p = r.startTop + e.pageY - r.startY;
            o && (o.parent()[0] == document.body ? (a = null != i.deltaX && null != i.deltaX ? e.pageX + i.deltaX : e.pageX - e.data.offsetWidth, p = null != i.deltaY && null != i.deltaY ? e.pageY + i.deltaY : e.pageY - e.data.offsetHeight) : (null != i.deltaX && null != i.deltaX && (a += e.data.offsetWidth + i.deltaX), null != i.deltaY && null != i.deltaY && (p += e.data.offsetHeight + i.deltaY))), e.data.parent != document.body && (a += t(e.data.parent).scrollLeft(), p += t(e.data.parent).scrollTop()), "h" == i.axis ? r.left = a : "v" == i.axis ? r.top = p : (r.left = a, r.top = p);
        }

        function n(e) {
            var n = t.data(e.data.target, "hidraggable"),
                i = n.options,
                o = n.proxy;
            o || (o = t(e.data.target)), o.css({
                left: t.fn.dragLengthC(e.data.left, i),
                top: t.fn.dragLengthC(e.data.top, i)
            }), t("body").css("cursor", i.cursor);
        }

        function i(i) {
            t.fn.hidraggable.isDragging = !0;
            var o = t.data(i.data.target, "hidraggable"),
                r = o.options,
                a = t(".hidroppable").filter(function () {
                    return i.data.target != this;
                }).filter(function () {
                    var e = t.data(this, "hidroppable").options.accept;
                    return !e || t(e).filter(function () {
                        return this == i.data.target;
                    }).length > 0;
                });
            o.hidroppables = a;
            var p = o.proxy;
            return p || (r.proxy ? (p = "clone" == r.proxy ? t(i.data.target).clone().insertAfter(i.data.target) : r.proxy.call(i.data.target, i.data.target), o.proxy = p) : p = t(i.data.target)), p.css("position", "absolute"), e(i), n(i), r.onStartDrag.call(i.data.target, i), !1;
        }

        function o(i) {
            var o = t.data(i.data.target, "hidraggable");
            e(i), 0 != o.options.onDrag.call(i.data.target, i, t.fn.dragLengthCNum(i.data.left, o.options), t.fn.dragLengthCNum(i.data.top, o.options)) && n(i);
            var r = i.data.target;
            return o.hidroppables.each(function () {
                var e = t(this);

                if (!e.hidroppable("options").disabled) {
                    var n = e.offset();
                    i.pageX > n.left && i.pageX < n.left + e.outerWidth() && i.pageY > n.top && i.pageY < n.top + e.outerHeight() ? (this.entered || (t(this).trigger("_dragenter", [r]), this.entered = !0), t(this).trigger("_dragover", [r])) : this.entered && (t(this).trigger("_dragleave", [r]), this.entered = !1);
                }
            }), !1;
        }

        function r(e) {
            t.fn.hidraggable.isDragging = !1, o(e);
            var n,
                i,
                r = t.data(e.data.target, "hidraggable"),
                a = r.proxy,
                p = r.options;
            p.revert ? 1 == l() ? t(e.data.target).css({
                position: e.data.startPosition,
                left: e.data.startLeft,
                top: e.data.startTop
            }) : a ? (a.parent()[0] == document.body ? (n = e.data.startX - e.data.offsetWidth, i = e.data.startY - e.data.offsetHeight) : (n = e.data.startLeft, i = e.data.startTop), a.animate({
                left: n,
                top: i
            }, function () {
                s();
            })) : t(e.data.target).animate({
                left: e.data.startLeft,
                top: e.data.startTop
            }, function () {
                t(e.data.target).css("position", e.data.startPosition);
            }) : (t(e.data.target).css({
                position: "absolute",
                left: t.fn.dragLengthC(e.data.left, p),
                top: t.fn.dragLengthC(e.data.top, p)
            }), l());

            function s() {
                a && a.remove(), r.proxy = null;
            }

            function l() {
                var n = !1;
                return r.hidroppables.each(function () {
                    var i = t(this);

                    if (!i.hidroppable("options").disabled) {
                        var o = i.offset();
                        return e.pageX > o.left && e.pageX < o.left + i.outerWidth() && e.pageY > o.top && e.pageY < o.top + i.outerHeight() ? (p.revert && t(e.data.target).css({
                            position: e.data.startPosition,
                            left: e.data.startLeft,
                            top: e.data.startTop
                        }), t(this).trigger("_drop", [e.data.target]), s(), n = !0, this.entered = !1, !1) : void 0;
                    }
                }), n || p.revert || s(), n;
            }

            return p.onStopDrag.call(e.data.target, e), t(document).unbind(".hidraggable"), setTimeout(function () {
                t("body").css("cursor", "");
            }, 100), !1;
        }

        t.fn.hidraggable = function (e, n) {
            return "string" == typeof e ? t.fn.hidraggable.methods[e](this, n) : this.each(function () {
                var n,
                    a = t.data(this, "hidraggable");
                a ? (a.handle.unbind(".hidraggable"), n = t.extend(a.options, e)) : n = t.extend({}, t.fn.hidraggable.defaults, t.fn.hidraggable.parseOptions(this), e || {});
                var p = n.handle ? "string" == typeof n.handle ? t(n.handle, this) : n.handle : t(this);

                function s(e) {
                    var n = t.data(e.data.target, "hidraggable"),
                        i = n.handle,
                        o = t(i).offset(),
                        r = t(i).outerWidth(),
                        a = t(i).outerHeight(),
                        p = e.pageY - o.top,
                        s = o.left + r - e.pageX,
                        l = o.top + a - e.pageY,
                        u = e.pageX - o.left;
                    return Math.min(p, s, l, u) > n.options.edge;
                }

                t.data(this, "hidraggable", {
                    options: n,
                    handle: p
                }), n.disabled ? t(this).css("cursor", "") : p.unbind(".hidraggable").bind("mousemove.hidraggable", {
                    target: this
                }, function (e) {
                    if (!t.fn.hidraggable.isDragging) {
                        var n = t.data(e.data.target, "hidraggable").options;
                        s(e) ? t(this).css("cursor", n.cursor) : t(this).css("cursor", "");
                    }
                }).bind("mouseleave.hidraggable", {
                    target: this
                }, function (e) {
                    t(this).css("cursor", "");
                }).bind("mousedown.hidraggable", {
                    target: this
                }, function (e) {
                    if (0 != s(e)) {
                        t(this).css("cursor", "");
                        var n = t(e.data.target).position(),
                            a = t(e.data.target).offset(),
                            p = {
                                startPosition: t(e.data.target).css("position"),
                                startLeft: n.left,
                                startTop: n.top,
                                left: n.left,
                                top: n.top,
                                startX: e.pageX,
                                startY: e.pageY,
                                offsetWidth: e.pageX - a.left,
                                offsetHeight: e.pageY - a.top,
                                target: e.data.target,
                                parent: t(e.data.target).parent()[0]
                            };
                        t.extend(e.data, p), 0 != t.data(e.data.target, "hidraggable").options.onBeforeDrag.call(e.data.target, e) && (t(document).bind("mousedown.hidraggable", e.data, i), t(document).bind("mousemove.hidraggable", e.data, o), t(document).bind("mouseup.hidraggable", e.data, r));
                    }
                });
            });
        }, t.fn.hidraggable.methods = {
            options: function options(e) {
                return t.data(e[0], "hidraggable").options;
            },
            proxy: function proxy(e) {
                return t.data(e[0], "hidraggable").proxy;
            },
            enable: function enable(e) {
                return e.each(function () {
                    t(this).hidraggable({
                        disabled: !1
                    });
                });
            },
            disable: function disable(e) {
                return e.each(function () {
                    t(this).hidraggable({
                        disabled: !0
                    });
                });
            }
        }, t.fn.hidraggable.parseOptions = function (e) {
            var n = t(e);
            return t.extend({}, t.hiprintparser.parseOptions(e, ["cursor", "handle", "axis", {
                revert: "boolean",
                deltaX: "number",
                deltaY: "number",
                edge: "number"
            }]), {
                    disabled: !!n.attr("disabled") || void 0
                });
        }, t.fn.hidraggable.defaults = {
            proxy: null,
            revert: !1,
            cursor: "move",
            deltaX: null,
            deltaY: null,
            handle: null,
            disabled: !1,
            edge: 0,
            axis: null,
            onBeforeDrag: function onBeforeDrag(t) { },
            onStartDrag: function onStartDrag(t) { },
            onDrag: function onDrag(t) { },
            onStopDrag: function onStopDrag(t) { }
        }, t.fn.hidraggable.isDragging = !1;
    }(jQuery);
}, function (t, e) {
    !function (t) {
        t.fn.hidroppable = function (e, n) {
            return "string" == typeof e ? t.fn.hidroppable.methods[e](this, n) : (e = e || {}, this.each(function () {
                var n,
                    i = t.data(this, "hidroppable");
                i ? t.extend(i.options, e) : (t(n = this).addClass("hidroppable"), t(n).bind("_dragenter", function (e, i) {
                    t.data(n, "hidroppable").options.onDragEnter.apply(n, [e, i]);
                }), t(n).bind("_dragleave", function (e, i) {
                    t.data(n, "hidroppable").options.onDragLeave.apply(n, [e, i]);
                }), t(n).bind("_dragover", function (e, i) {
                    t.data(n, "hidroppable").options.onDragOver.apply(n, [e, i]);
                }), t(n).bind("_drop", function (e, i) {
                    t.data(n, "hidroppable").options.onDrop.apply(n, [e, i]);
                }), t.data(this, "hidroppable", {
                    options: t.extend({}, t.fn.hidroppable.defaults, t.fn.hidroppable.parseOptions(this), e)
                }));
            }));
        }, t.fn.hidroppable.methods = {
            options: function options(e) {
                return t.data(e[0], "hidroppable").options;
            },
            enable: function enable(e) {
                return e.each(function () {
                    t(this).hidroppable({
                        disabled: !1
                    });
                });
            },
            disable: function disable(e) {
                return e.each(function () {
                    t(this).hidroppable({
                        disabled: !0
                    });
                });
            }
        }, t.fn.hidroppable.parseOptions = function (e) {
            var n = t(e);
            return t.extend({}, t.hiprintparser.parseOptions(e, ["accept"]), {
                disabled: !!n.attr("disabled") || void 0
            });
        }, t.fn.hidroppable.defaults = {
            accept: null,
            disabled: !1,
            onDragEnter: function onDragEnter(t, e) { },
            onDragOver: function onDragOver(t, e) { },
            onDragLeave: function onDragLeave(t, e) { },
            onDrop: function onDrop(t, e) { }
        };
    }(jQuery);
}, function (t, e) {
    var n;
    (n = jQuery).hiprintparser = {
        parseOptions: function parseOptions(t, e) {
            var i = n(t),
                o = {},
                r = n.trim(i.attr("data-options"));

            if (r && ("{" != r.substring(0, 1) && (r = "{" + r + "}"), o = new Function("return " + r)()), e) {
                for (var a = {}, p = 0; p < e.length; p++) {
                    var s = e[p];
                    if ("string" == typeof s) a[s] = "width" == s || "height" == s || "left" == s || "top" == s ? parseInt(t.style[s]) || void 0 : i.attr(s); else for (var l in s) {
                        var u = s[l];
                        "boolean" == u ? a[l] = i.attr(l) ? "true" == i.attr(l) : void 0 : "number" == u && (a[l] = "0" == i.attr(l) ? 0 : parseFloat(i.attr(l)) || void 0);
                    }
                }

                n.extend(o, a);
            }

            return o;
        }
    }, n.fn.dragLengthC = function (t, e) {
        return "pt" == e.moveUnit ? n.fn.dragLengthCNum(t, e) + "pt" : n.fn.dragLengthCNum(t, e);
    }, n.fn.dragLengthCNum = function (t, e) {
        var n = 3;

        if ("pt" == e.moveUnit) {
            var i = .75 * t;
            return e.minMove && (n = e.minMove), Math.round(i / n) * n;
        }

        return Math.round(i / n) * n;
    };
}, function (t, e) {
    var n, i, o;
    n = jQuery, i = {
        maxPanelIndex: 0
    }, (o = function o(t) {
        this.options = n.data(t.target, "hireizeable").options, this.init(t.target);
    }).prototype = {
            numHandlerText: function numHandlerText(t) {
                return this.numHandler(t) + "pt";
            },
            numHandler: function numHandler(t) {
                var e = 1.5,
                    n = .75 * t;
                return this.options.minResize && (e = this.options.minResize), Math.round(n / e) * e;
            },
            init: function init(t) {
                this.initResizeBox(t);
            },
            initResizeBox: function initResizeBox(t) {
                var e = this;
                n(t).each(function () {
                    var o;
                    i.maxPanelIndex += 1, e.options.noContainer ? o = n(t) : (o = n("<div panelIndex=" + i.maxPanelIndex + ' class="resize-panel"></div>')).css({
                        width: "100%",
                        height: "100%",
                        top: 0,
                        left: 0,
                        position: "absolute",
                        "background-color": "rgba(0,0,0,0.5)",
                        cursor: "move",
                        display: "none"
                    }), e.appendHandler(o, n(this));

                    var r = {
                        name: "n",
                        target: n('<div class="n resizebtn" style="cursor: n-resize;top: -12px;margin-left: -4px;left: 50%;"></div>')
                    },
                        a = {
                            name: "s",
                            target: n('<div class="s resizebtn" style="cursor: s-resize;bottom: -12px;margin-left: -4px;left: 50%;"></div>')
                        },
                        p = {
                            name: "w",
                            target: n('<div class="w resizebtn" style="cursor: w-resize;left: -12px;margin-top: -4px;top: 50%;"></div>')
                        },
                        s = {
                            name: "e",
                            target: n('<div class="e resizebtn" style="cursor: e-resize; top: 50%; margin-top:-4px;right: -12px;"></div>')
                        },
                        l = {
                            name: "ne",
                            target: n('<div class="ne resizebtn" style="cursor: ne-resize;top: -12px;right: -12px;"></div>')
                        },
                        u = {
                            name: "nw",
                            target: n('<div class="nw resizebtn" style=" cursor: nw-resize;top: -12px;left:-12px;"></div>')
                        },
                        d = {
                            name: "se",
                            target: n('<div class="se resizebtn" style="cursor: se-resize;bottom:-12px;right: -12px;"></div>')
                        },
                        c = {
                            name: "sw",
                            target: n('<div class="sw resizebtn" style="cursor: sw-resize;bottom: -12px;left: -12px;"></div>')
                        },
                        h = function h() {
                            var t = [],
                                i = e.options.showPoints;
                            return n.each([r, a, p, s, l, u, d, c], function (e, o) {
                                n.inArray(o.name, i) > -1 && t.push(o.target);
                            }), t;
                        };

                    e.addHandlerCss(h()), e.appendHandler(h(), o), e.bindResizeEvent(o, n(this));
                    var f = n(this);
                    n(o).on("mousedown", ".resizebtn", function () {
                        f.addClass("resizeing");
                    }), n(".easyui-droppable").on("mouseup", function () {
                        f.removeClass("resizeing");
                    }), e.bindTrigger(n(this));
                }), e.bindHidePanel();
            },
            addHandlerCss: function addHandlerCss(t) {
                for (var e = 0; e < t.length; e++) {
                    t[e].css({
                        position: "absolute",
                        width: "8px",
                        height: "8px",
                        background: "#ff6600",
                        "border-radius": "50%"
                    });
                }
            },
            appendHandler: function appendHandler(t, e) {
                for (var n = 0; n < t.length; n++) {
                    e.append(t[n]);
                }
            },
            triggerResize: function triggerResize(t) {
                t.siblings().children("div[panelindex]").css({
                    display: "none"
                }), t.children("div[panelindex]").css({
                    display: "block"
                });
            },
            bindResizeEvent: function bindResizeEvent(t, e) {
                var i = this,
                    o = 0,
                    r = 0,
                    a = t.width(),
                    p = t.height(),
                    s = t.offset().left,
                    l = t.offset().top,
                    u = i.options.noContainer ? n(e) : t.parent(),
                    d = !1;
                t.on("mousedown", ".e", function (e) {
                    o = e.pageX, a = t.width(), d = !0;
                });
                var c = !1;
                t.on("mousedown", ".s", function (e) {
                    r = e.pageY, p = t.height(), c = !0;
                });
                var h = !1;
                t.on("mousedown", ".w", function (e) {
                    o = e.pageX, a = t.width(), h = !0, s = u.offset().left;
                });
                var f = !1;
                t.on("mousedown", ".n", function (e) {
                    r = e.pageY, p = t.height(), f = !0, l = u.offset().top;
                });
                var g = !1;
                t.on("mousedown", ".ne", function (e) {
                    o = e.pageX, r = e.pageY, a = t.width(), p = t.height(), g = !0, l = u.offset().top;
                });
                var m = !1;
                t.on("mousedown", ".nw", function (e) {
                    o = e.pageX, r = e.pageY, a = t.width(), p = t.height(), l = u.offset().top, s = u.offset().left, m = !0;
                });
                var v = !1;
                t.on("mousedown", ".se", function (e) {
                    o = e.pageX, r = e.pageY, a = t.width(), p = t.height(), v = !0;
                });
                var y = !1;
                t.on("mousedown", ".sw", function (e) {
                    o = e.pageX, r = e.pageY, a = t.width(), p = t.height(), y = !0, s = u.offset().left;
                });
                var b = !1;
                t.on("mousedown", function (t) {
                    i.options.onBeforeResize(), o = t.pageX, r = t.pageY, l = u.offset().top, s = u.offset().left, b = !1;
                }), n(i.options.stage).on("mousemove", function (e) {
                    if (d) {
                        var n = e.pageX - o;
                        t.css({
                            width: "100%"
                        }), u.css({
                            width: i.numHandlerText(a + n)
                        }), i.options.onResize(e, void 0, i.numHandler(a + n), void 0, void 0);
                    } else if (c) {
                        var E = e.pageY - r;
                        t.css({
                            height: "100%"
                        }), u.css({
                            height: i.numHandlerText(p + E)
                        }), i.options.onResize(e, i.numHandler(p + E), void 0, void 0, void 0);
                    } else h ? (n = e.pageX - o, t.css({
                        width: "100%"
                    }), u.css({
                        width: i.numHandlerText(a - n),
                        left: i.numHandlerText(i.options.noDrag ? void 0 : i.numHandler(s + n))
                    }), i.options.onResize(e, void 0, i.numHandler(a - n), void 0, i.options.noDrag ? void 0 : i.numHandler(s + n))) : f ? (E = e.pageY - r, t.css({
                        height: "100%"
                    }), u.css({
                        height: i.numHandlerText(p - E),
                        top: i.numHandlerText(i.options.noDrag ? void 0 : l + E)
                    }), i.options.onResize(e, i.numHandler(p - E), void 0, i.options.noDrag ? void 0 : i.numHandler(l + E), void 0)) : g ? (n = e.pageX - o, E = e.pageY - r, t.css({
                        height: "100%",
                        width: "100%"
                    }), u.css({
                        height: i.numHandlerText(p - E),
                        top: i.numHandlerText(i.options.noDrag ? void 0 : l + E),
                        width: i.numHandlerText(a + n)
                    }), i.options.onResize(e, i.numHandler(p - E), i.numHandler(a + n), i.options.noDrag ? void 0 : i.numHandler(l + E), void 0)) : m ? (n = e.pageX - o, E = e.pageY - r, t.css({
                        height: "100%",
                        width: "100%"
                    }), u.css({
                        height: i.numHandlerText(p - E),
                        top: i.numHandlerText(i.options.noDrag ? void 0 : l + E),
                        width: i.numHandlerText(a - n),
                        left: i.numHandlerText(i.options.noDrag ? void 0 : s + n)
                    }), i.options.onResize(e, i.numHandler(p - E), i.numHandler(a - n), i.options.noDrag ? void 0 : i.numHandler(l + E), i.options.noDrag ? void 0 : i.numHandler(s + n))) : v ? (n = e.pageX - o, E = e.pageY - r, t.css({
                        width: "100%",
                        height: "100%"
                    }), u.css({
                        width: i.numHandlerText(a + n),
                        height: i.numHandlerText(p + E)
                    }), i.options.onResize(e, i.numHandler(p + E), i.numHandler(a + n), void 0, void 0)) : y ? (n = e.pageX - o, E = e.pageY - r, t.css({
                        width: "100%",
                        height: "100%"
                    }), u.css({
                        width: i.numHandlerText(a - n),
                        left: i.numHandlerText(i.options.noDrag ? void 0 : s + n),
                        height: i.numHandlerText(p + E)
                    }), i.options.onResize(e, i.numHandler(p + E), i.numHandler(a - n), i.numHandler(otundefinedop), i.options.noDrag ? void 0 : i.numHandler(s + n))) : b && (n = e.pageX - o, E = e.pageY - r, u.css({
                        left: i.numHandlerText(i.options.noDrag ? void 0 : s + n),
                        top: i.numHandlerText(i.options.noDrag ? void 0 : l + E)
                    }), i.options.onResize(e, void 0, void 0, i.options.noDrag ? void 0 : i.numHandler(l + E), i.options.noDrag ? void 0 : i.numHandler(s + n)));
                }).on("mouseup", function (t) {
                    d = !1, c = !1, h = !1, f = !1, g = !1, m = !1, y = !1, v = !1, b = !1, i.options.onStopResize();
                });
            },
            bindTrigger: function bindTrigger(t) {
                var e = this;
                t.on("click", function (n) {
                    n.stopPropagation(), e.triggerResize(t);
                });
            },
            bindHidePanel: function bindHidePanel(t) {
                if (i.maxPanelIndex < 2) {
                    var e = this.options.stage;
                    n(e).bind("click", function (t) {
                        t.stopPropagation(), n("div[panelindex]").css({
                            display: "none"
                        });
                    });
                }
            }
        }, n.fn.extend({
            hireizeable: function hireizeable(t) {
                return this.each(function () {
                    var e,
                        i = n.data(this, "hireizeable");
                    e = i ? n.extend(i.options, _1f) : n.extend({}, n.fn.hireizeable.defaults, t || {}), n.data(this, "hireizeable", {
                        options: e
                    }), new o({
                        target: this,
                        onResize: function onResize(t, e, n, i, o) { },
                        onStopResize: function onStopResize(t, e, n, i, o) { }
                    });
                });
            }
        }), n.fn.hireizeable.defaults = {
            stage: document,
            reizeUnit: "pt",
            minResize: 1.5,
            showPoints: ["s", "e"],
            noContainer: !1,
            onBeforeResize: function onBeforeResize(t, e, n, i, o) { },
            onResize: function onResize(t, e, n, i, o) { },
            onStopResize: function onStopResize(t, e, n, i, o) { },
            noDrag: !1
        };
}, function (t, e) {
    var n, i;
    jQuery, n = "connected", i = "reconnecting", window.hiwebSocket = {
        opened: !1,
        name: "webSockets",
        reconnectTimeout: 6e4,
        reconnectWindowSetTimeout: null,
        reconnectDelay: 2e3,
        supportsKeepAlive: function supportsKeepAlive() {
            return !0;
        },
        hasIo: function hasIo(t) {
            return window.io;
        },
        send: function send(t) {
            try {
                this.socket.emit("news", t);
            } catch (e) {
                console.log("send data error:" + (t || "") + JSON.stringify(e));
            }
        },
        getPrinterList: function getPrinterList() {
            return this.printerList;
        },
        start: function start() {
            var _this = this;

            var t = this;
            window.WebSocket ? this.socket || (this.socket = io("http://localhost:17521", {
                reconnectionAttempts: 5
            }), this.socket.on("connect", function (e) {
                t.opened = !0, console.log("Websocket opened."), _this.socket.on("successs", function (t) {
                    hinnn.event.trigger("printSuccess_" + t.templateId, t);
                }), _this.socket.on("error", function (t) {
                    hinnn.event.trigger("printError_" + t.templateId, t);
                }), _this.socket.on("printerList", function (e) {
                    t.printerList = e;
                }), t.state = n;
            }), this.socket.on("disconnect", function () {
                t.opened = !1;
            })) : console.log("WebSocket start fail");
        },
        reconnect: function reconnect() {
            this.state !== n && this.state !== i || (this.stop(), this.ensureReconnectingState() && (console.log("Websocket reconnecting."), this.start()));
        },
        stop: function stop() {
            this.socket && (console.log("Closing the Websocket."), this.socket.close(), this.socket = null);
        },
        ensureReconnectingState: function ensureReconnectingState() {
            return this.state = i, this.state === i;
        }
    };
}, function (t, e, n) {
    var i = n(28);
    "string" == typeof i && (i = [[t.i, i, ""]]);
    var o = {
        hmr: !0,
        transform: void 0,
        insertInto: void 0
    };
    n(30)(i, o);
    i.locals && (t.exports = i.locals);
}, function (t, e, n) {
    (t.exports = n(29)(!1)).push([t.i, ".hicontextmenu {\r\n\tposition: absolute;\r\n\tdisplay: inline-block;\r\n\twidth: 215px;\r\n\tpadding: 0 0;\r\n\tmargin: 0;\r\n\tfont-family: inherit;\r\n\tfont-size: inherit;\r\n\tlist-style-type: none;\r\n\tlist-style: none;\r\n\tbackground: #fff;\r\n\tborder: 1px solid #bebebe;\r\n\tborder-radius: 2px;\r\n\tfont-size: 13px;\r\n}\r\n\r\n.hicontextmenuroot .hicontextmenuitem {\r\n\tposition: relative;\r\n\t-webkit-box-sizing: content-box;\r\n\t-moz-box-sizing: content-box;\r\n\tbox-sizing: content-box;\r\n\tpadding: .2em 12px;\r\n\tcolor: #2f2f2f;\r\n\t-webkit-user-select: none;\r\n\t-moz-user-select: none;\r\n\t-ms-user-select: none;\r\n\ttext-decoration: none;\r\n\r\n\tuser-select: none;\r\n\tbackground-color: #fff;\r\n\r\n}\r\n\r\n.hicontextmenuroot>.hicontextmenuitem:hover,\r\n.hicontextmenuroot .hicontextmenuitem > a:hover {\r\n\tbackground-color: #f3f3f3;\r\n}\r\n\r\n.hicontextmenuroot .hicontextmenuitem>a {\r\n\ttext-decoration: none;\r\n\tcolor: #363636;\r\n\tline-height: 22px;\r\n\r\n}\r\n\r\n.hicontextmenuroot .hicontextsubmenu>ul {\r\n\tdisplay: none;\r\n\tposition: absolute;\r\n\r\n}\r\n\r\n.hicontextmenuroot .hicontextsubmenu:hover>ul {\r\n\tdisplay: block;\r\n\tleft: 100%;\r\n\ttop: -1px;\r\n\tmargin-left: 0px;\r\n}\r\n\r\n.hicontextmenuroot .borderBottom {\r\n\tborder-bottom: 1px solid #efe6e6;\r\n}\r\n\r\n.hicontextmenuroot .disable> a {\r\n  \r\n    color: #ccc;\r\n   \r\n}\r\n.hicontextmenuroot>.disable:hover,\r\n.hicontextmenuroot .disable> a:hover {\r\n\tbackground-color:#fff;\r\n}", ""]);
}, function (t, e, n) {
    "use strict";

    t.exports = function (t) {
        var e = [];
        return e.toString = function () {
            return this.map(function (e) {
                var n = function (t, e) {
                    var n = t[1] || "",
                        i = t[3];
                    if (!i) return n;

                    if (e && "function" == typeof btoa) {
                        var o = (a = i, "/*# sourceMappingURL=data:application/json;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(a)))) + " */"),
                            r = i.sources.map(function (t) {
                                return "/*# sourceURL=" + i.sourceRoot + t + " */";
                            });
                        return [n].concat(r).concat([o]).join("\n");
                    }

                    var a;
                    return [n].join("\n");
                }(e, t);

                return e[2] ? "@media " + e[2] + "{" + n + "}" : n;
            }).join("");
        }, e.i = function (t, n) {
            "string" == typeof t && (t = [[null, t, ""]]);

            for (var i = {}, o = 0; o < this.length; o++) {
                var r = this[o][0];
                null != r && (i[r] = !0);
            }

            for (o = 0; o < t.length; o++) {
                var a = t[o];
                null != a[0] && i[a[0]] || (n && !a[2] ? a[2] = n : n && (a[2] = "(" + a[2] + ") and (" + n + ")"), e.push(a));
            }
        }, e;
    };
}, function (t, e, n) {
    var i,
        o,
        r = {},
        a = (i = function i() {
            return window && document && document.all && !window.atob;
        }, function () {
            return void 0 === o && (o = i.apply(this, arguments)), o;
        }),
        p = function (t) {
            var e = {};
            return function (t, n) {
                if ("function" == typeof t) return t();

                if (void 0 === e[t]) {
                    var i = function (t, e) {
                        return e ? e.querySelector(t) : document.querySelector(t);
                    }.call(this, t, n);

                    if (window.HTMLIFrameElement && _instanceof(i, window.HTMLIFrameElement)) try {
                        i = i.contentDocument.head;
                    } catch (t) {
                        i = null;
                    }
                    e[t] = i;
                }

                return e[t];
            };
        }(),
        s = null,
        l = 0,
        u = [],
        d = n(31);

    function c(t, e) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n],
                o = r[i.id];

            if (o) {
                o.refs++;

                for (var a = 0; a < o.parts.length; a++) {
                    o.parts[a](i.parts[a]);
                }

                for (; a < i.parts.length; a++) {
                    o.parts.push(y(i.parts[a], e));
                }
            } else {
                var p = [];

                for (a = 0; a < i.parts.length; a++) {
                    p.push(y(i.parts[a], e));
                }

                r[i.id] = {
                    id: i.id,
                    refs: 1,
                    parts: p
                };
            }
        }
    }

    function h(t, e) {
        for (var n = [], i = {}, o = 0; o < t.length; o++) {
            var r = t[o],
                a = e.base ? r[0] + e.base : r[0],
                p = {
                    css: r[1],
                    media: r[2],
                    sourceMap: r[3]
                };
            i[a] ? i[a].parts.push(p) : n.push(i[a] = {
                id: a,
                parts: [p]
            });
        }

        return n;
    }

    function f(t, e) {
        var n = p(t.insertInto);
        if (!n) throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
        var i = u[u.length - 1];
        if ("top" === t.insertAt) i ? i.nextSibling ? n.insertBefore(e, i.nextSibling) : n.appendChild(e) : n.insertBefore(e, n.firstChild), u.push(e); else if ("bottom" === t.insertAt) n.appendChild(e); else {
            if ("object" != _typeof(t.insertAt) || !t.insertAt.before) throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
            var o = p(t.insertAt.before, n);
            n.insertBefore(e, o);
        }
    }

    function g(t) {
        if (null === t.parentNode) return !1;
        t.parentNode.removeChild(t);
        var e = u.indexOf(t);
        e >= 0 && u.splice(e, 1);
    }

    function m(t) {
        var e = document.createElement("style");

        if (void 0 === t.attrs.type && (t.attrs.type = "text/css"), void 0 === t.attrs.nonce) {
            var i = function () {
                0;
                return n.nc;
            }();

            i && (t.attrs.nonce = i);
        }

        return v(e, t.attrs), f(t, e), e;
    }

    function v(t, e) {
        Object.keys(e).forEach(function (n) {
            t.setAttribute(n, e[n]);
        });
    }

    function y(t, e) {
        var n, i, o, r;

        if (e.transform && t.css) {
            if (!(r = "function" == typeof e.transform ? e.transform(t.css) : e.transform.default(t.css))) return function () { };
            t.css = r;
        }

        if (e.singleton) {
            var a = l++;
            n = s || (s = m(e)), i = T.bind(null, n, a, !1), o = T.bind(null, n, a, !0);
        } else t.sourceMap && "function" == typeof URL && "function" == typeof URL.createObjectURL && "function" == typeof URL.revokeObjectURL && "function" == typeof Blob && "function" == typeof btoa ? (n = function (t) {
            var e = document.createElement("link");
            return void 0 === t.attrs.type && (t.attrs.type = "text/css"), t.attrs.rel = "stylesheet", v(e, t.attrs), f(t, e), e;
        }(e), i = function (t, e, n) {
            var i = n.css,
                o = n.sourceMap,
                r = void 0 === e.convertToAbsoluteUrls && o;
            (e.convertToAbsoluteUrls || r) && (i = d(i));
            o && (i += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(o)))) + " */");
            var a = new Blob([i], {
                type: "text/css"
            }),
                p = t.href;
            t.href = URL.createObjectURL(a), p && URL.revokeObjectURL(p);
        }.bind(null, n, e), o = function o() {
            g(n), n.href && URL.revokeObjectURL(n.href);
        }) : (n = m(e), i = function (t, e) {
            var n = e.css,
                i = e.media;
            i && t.setAttribute("media", i);
            if (t.styleSheet) t.styleSheet.cssText = n; else {
                for (; t.firstChild;) {
                    t.removeChild(t.firstChild);
                }

                t.appendChild(document.createTextNode(n));
            }
        }.bind(null, n), o = function o() {
            g(n);
        });

        return i(t), function (e) {
            if (e) {
                if (e.css === t.css && e.media === t.media && e.sourceMap === t.sourceMap) return;
                i(t = e);
            } else o();
        };
    }

    t.exports = function (t, e) {
        if ("undefined" != typeof DEBUG && DEBUG && "object" != (typeof document === "undefined" ? "undefined" : _typeof(document))) throw new Error("The style-loader cannot be used in a non-browser environment");
        (e = e || {}).attrs = "object" == _typeof(e.attrs) ? e.attrs : {}, e.singleton || "boolean" == typeof e.singleton || (e.singleton = a()), e.insertInto || (e.insertInto = "head"), e.insertAt || (e.insertAt = "bottom");
        var n = h(t, e);
        return c(n, e), function (t) {
            for (var i = [], o = 0; o < n.length; o++) {
                var a = n[o];
                (p = r[a.id]).refs-- , i.push(p);
            }

            t && c(h(t, e), e);

            for (o = 0; o < i.length; o++) {
                var p;

                if (0 === (p = i[o]).refs) {
                    for (var s = 0; s < p.parts.length; s++) {
                        p.parts[s]();
                    }

                    delete r[p.id];
                }
            }
        };
    };

    var b,
        E = (b = [], function (t, e) {
            return b[t] = e, b.filter(Boolean).join("\n");
        });

    function T(t, e, n, i) {
        var o = n ? "" : i.css;
        if (t.styleSheet) t.styleSheet.cssText = E(e, o); else {
            var r = document.createTextNode(o),
                a = t.childNodes;
            a[e] && t.removeChild(a[e]), a.length ? t.insertBefore(r, a[e]) : t.appendChild(r);
        }
    }
}, function (t, e) {
    t.exports = function (t) {
        var e = "undefined" != typeof window && window.location;
        if (!e) throw new Error("fixUrls requires window.location");
        if (!t || "string" != typeof t) return t;
        var n = e.protocol + "//" + e.host,
            i = n + e.pathname.replace(/\/[^\/]*$/, "/");
        return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function (t, e) {
            var o,
                r = e.trim().replace(/^"(.*)"$/, function (t, e) {
                    return e;
                }).replace(/^'(.*)'$/, function (t, e) {
                    return e;
                });
            return /^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(r) ? t : (o = 0 === r.indexOf("//") ? r : 0 === r.indexOf("/") ? n + r : i + r.replace(/^\.\//, ""), "url(" + JSON.stringify(o) + ")");
        });
    };
}, function (t, e) {
    var n, i;
    window, document, n = jQuery, (i = function i(t, e) {
        this.init(t, e);
    }).prototype = {
            init: function init(t, e) {
                this.ele = t, this.defaults = {
                    menu: [{
                        text: "text",
                        menus: [{}, {}],
                        callback: function callback() { }
                    }],
                    target: function target(t) { },
                    width: 100,
                    itemHeight: 28,
                    bgColor: "#fff",
                    color: "#333",
                    fontSize: 14,
                    hoverBgColor: "#f5f5f5"
                }, this.opts = n.extend(!0, {}, this.defaults, e), this.random = new Date().getTime() + parseInt(1e3 * Math.random()), this.eventBind();
            },
            renderMenu: function renderMenu(t, e) {
                var n = this,
                    i = e;

                if (t && t.length) {
                    var o = $('<ul class="hicontextmenu" ></ul>');
                    i || (i = o).addClass("hicontextmenuroot"), $.each(t, function (t, e) {
                        var i = !!e.disable && e.disable(),
                            r = $('<li class="hicontextmenuitem"><a href="javascript:void(0);"><span>' + (e.text || "") + "</span></a></li>");
                        i && r.addClass("disable"), e.borderBottom && r.addClass("borderBottom"), e.menus && (r.addClass("hicontextsubmenu"), n.renderMenu(e.menus, r)), e.callback && r.click(function (t) {
                            $(this).hasClass("disable") ? t.stopPropagation() : ($(".hicontextmenuroot").remove(), e.callback(), t.stopPropagation());
                        }), o.append(r);
                    }), e && e.append(o);
                }

                e || $("body").append(i).find(".hicontextmenuroot").hide();
            },
            setPosition: function setPosition(t) {
                $(".hicontextmenuroot").css({
                    left: t.pageX + 2,
                    top: t.pageY + 2
                }).show();
            },
            eventBind: function eventBind() {
                var t = this;
                this.ele.on("contextmenu", function (e) {
                    $(".hicontextmenuroot").remove(), e.preventDefault(), t.renderMenu(t.opts.menus), t.setPosition(e), t.opts.target && "function" == typeof t.opts.target && t.opts.target(n(this));
                }), n("body").on("click", function () {
                    n(".hicontextmenuroot").remove();
                });
            }
        }, n.fn.hicontextMenu = function (t) {
            return new i(this, t), this;
        };
}, function (t, e, n) {
    "use strict";

    n.r(e);
    n(22), n(23), n(24), n(25);
    var i,
        o = n(0);
    n(26);
    window.hiLocalStorage = (i = window.localStorage || null, {
        saveLocalData: function saveLocalData(t, e) {
            return !(!i || !e || (i.setItem(t, e), 0));
        },
        getLocalData: function getLocalData(t) {
            return i ? i.getItem(t) : null;
        },
        removeItem: function removeItem(t) {
            i && i.removeItem(t);
        }
    });
    n(27), n(32);

    var _r,
        a = function () {
            function t() {
                this.allElementTypes = [];
            }

            return Object.defineProperty(t, "instance", {
                get: function get() {
                    return t._instance || (t._instance = new t()), t._instance;
                },
                enumerable: !0,
                configurable: !0
            }), t.prototype.addPrintElementTypes = function (t, e) {
                var n = this;
                this[t] ? this[t] = this[t].concat(e) : this[t] = e, e.forEach(function (t) {
                    n.allElementTypes = n.allElementTypes.concat(t.printElementTypes);
                });
            }, t.prototype.getElementTypeGroups = function (t) {
                return this[this.formatterModule(t)] || [];
            }, t.prototype.getElementType = function (t) {
                var e = this.allElementTypes.filter(function (e) {
                    return e.tid == t;
                });
                if (e.length > 0) return e[0];
            }, t.prototype.formatterModule = function (t) {
                return t || "_default";
            }, t;
        }(),
        p = n(1),
        s = n(2),
        l = function () {
            function t() { }

            return t.prototype.createPrintElementTypeHtml = function (t, e) {
                var n = $('<ul class="hiprint-printElement-type"></ul>');
                return e.forEach(function (t) {
                    var e = $("<li></li>");
                    e.append('<span class="title">' + t.name + "</span>");
                    var i = $("<ul></ul>");
                    e.append(i), t.printElementTypes.forEach(function (t) {
                        i.append('<li><a class="ep-draggable-item" tid="' + t.tid + '">  ' + t.getText() + " </a></li>");
                    }), n.append(e);
                }), $(t).append(n), n.find(".ep-draggable-item");
            }, t;
        }(),
        u = n(5),
        d = n(15),
        c = function () {
            return function (t) {
                this.field = t.field, this.title = t.title, this.type = t.type, this.columns = t.columns;
            };
        }(),
        h = function () {
            function t(t) {
                var e = this;
                this.text = t.text, this.field = t.field, this.fields = t.fields, this.title = t.title, this.tid = t.tid, this.data = t.data, this.styler = t.styler, this.formatter = t.formatter, this.type = t.type, this.options = t.options, this.editable = t.editable, this.columnDisplayEditable = t.columnDisplayEditable, this.columnDisplayIndexEditable = t.columnDisplayIndexEditable, this.columnTitleEditable = t.columnTitleEditable, this.columnResizable = t.columnResizable, this.columnAlignEditable = t.columnAlignEditable, this.columns = [], (t.columns || []).forEach(function (t, n) {
                    e.columns.push(e.createTableColumnArray(t));
                }), this.rowStyler = t.rowStyler, this.striped = t.striped, this.groupFields = t.groupFields || [], this.groupFormatter = t.groupFormatter, this.groupFooterFormatter = t.groupFooterFormatter, this.footerFormatter = t.footerFormatter, this.gridColumnsFooterFormatter = t.gridColumnsFooterFormatter, this.columnObj = this.makeColumnObj();
            }

            return t.prototype.getText = function () {
                return this.text || this.title || "";
            }, t.prototype.createPrintElement = function (t) {
                var e = this;
                return this.columns && 0 == this.columns.length && (t.columns || []).forEach(function (t, n) {
                    e.columns.push(e.createTableColumnArray(t));
                }), new d.a(this, t);
            }, t.prototype.getData = function () {
                return [{}];
            }, t.prototype.createTableColumnArray = function (t) {
                var e = [];
                return t.forEach(function (t, n) {
                    e.push(new u.a(t));
                }), e;
            }, t.prototype.getPrintElementTypeEntity = function () {
                return new c({
                    title: this.title,
                    type: this.type
                });
            }, t.prototype.getFields = function () {
                return this.fields;
            }, t.prototype.getOptions = function () {
                return this.options || {};
            }, t.prototype.getColumnByColumnId = function (t) {
                return this.columnObj[t];
            }, t.prototype.makeColumnObj = function () {
                var t = {};
                return this.columns && this.columns.forEach(function (e) {
                    e.forEach(function (e) {
                        e.columnId && (t[e.columnId] = e);
                    });
                }), t;
            }, t;
        }(),
        f = n(4),
        g = n(3),
        m = (_r = function r(t, e) {
            return (_r = Object.setPrototypeOf || _instanceof({
                __proto__: []
            }, Array) && function (t, e) {
                t.__proto__ = e;
            } || function (t, e) {
                for (var n in e) {
                    e.hasOwnProperty(n) && (t[n] = e[n]);
                }
            })(t, e);
        }, function (t, e) {
            function n() {
                this.constructor = t;
            }

            _r(t, e), t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
        }),
        v = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new g.a(n), i.options.setDefault(new g.a(p.a.instance.image.default).getPrintElementOptionEntity()), i;
            }

            return m(e, t), e.prototype.getReizeableShowPoints = function () {
                return ["se"];
            }, e.prototype.getData = function (t) {
                var e = "";
                t ? e = this.getField() ? t[this.getField()] || "" : this.options.src || this.printElementType.getData() : e = this.options.src || this.printElementType.getData();
                var n = this.getFormatter();
                return n && (e = n(e, this.options, this._currenttemplateData)), e || "";
            }, e.prototype.createTarget = function (t, e) {
                var n = $('<div  class="hiprint-printElement hiprint-printElement-image" style="position: absolute;"><div class="hiprint-printElement-image-content" style="height:100%;width:100%"></div></div>');
                return this.updateTargetImage(n, t, e), n;
            }, e.prototype.initSizeByHtml = function (e) {
                t.prototype.initSizeByHtml.call(this, e), this.css(e, this.getData());
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.image;
            }, e.prototype.updateDesignViewFromOptions = function () {
                this.designTarget && (this.css(this.designTarget, this.getData()), this.updateTargetImage(this.designTarget, this.getTitle(), this.getData()));
            }, e.prototype.updateTargetImage = function (t, e, n) {
                var i = t.find(".hiprint-printElement-image-content");
                i.find("img").length ? i.find("img").attr("src", n) : i.html('<img style="width:100%;height:100%;" src="' + n + '">');
            }, e.prototype.getHtml = function (t, e, n) {
                return this.getHtml2(t, e, n);
            }, e;
        }(f.a),
        y = function () {
            var _t4 = function t(e, n) {
                return (_t4 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t4(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        b = function (t) {
            function e(e) {
                var n = this;
                return e = e || {}, (n = t.call(this, e) || this).leftSpaceRemoved = e.leftSpaceRemoved, n;
            }

            return y(e, t), e.prototype.getHideTitle = function () {
                return null == this.hideTitle ? this.defaultOptions.hideTitle : this.hideTitle;
            }, e;
        }(g.a),
        E = n(8),
        T = function () {
            function t(t, e, n, i, r, a, p, s, l, u, d) {
                this.defaultPaperNumberFormat = "paperNo-paperCount", this.printLine = 0, this.templateId = t, this.width = o.a.mm.toPt(e), this.height = o.a.mm.toPt(n), this.mmwidth = e, this.mmheight = n, this.paperHeader = i, this.paperFooter = r, this.contentHeight = r - i, this.createTarget(), this.index = u, this.paperNumberLeft = a || parseInt((this.width - 30).toString()), this.paperNumberTop = p || parseInt((this.height - 22).toString()), this.paperNumberDisabled = s, this.paperNumberFormat = l, this.referenceElement = d ? $.extend({}, d) : new E.a({
                    top: 0,
                    left: 0,
                    height: 0,
                    width: 0,
                    bottomInLastPaper: 0,
                    beginPrintPaperIndex: 0,
                    printTopInPaper: 0,
                    endPrintPaperIndex: 0
                });
            }

            return t.prototype.subscribePaperBaseInfoChanged = function (t) {
                this.onPaperBaseInfoChanged = t;
            }, t.prototype.triggerOnPaperBaseInfoChanged = function () {
                this.onPaperBaseInfoChanged && this.onPaperBaseInfoChanged({
                    paperHeader: this.paperHeader,
                    paperFooter: this.paperFooter,
                    paperNumberLeft: this.paperNumberLeft,
                    paperNumberTop: this.paperNumberTop,
                    paperNumberDisabled: this.paperNumberDisabled,
                    paperNumberFormat: this.paperNumberFormat
                });
            }, t.prototype.setFooter = function (t, e, n, i) {
                this.firstPaperFooter = t, this.evenPaperFooter = e, this.oddPaperFooter = n, this.lastPaperFooter = i;
            }, t.prototype.setOffset = function (t, e) {
                this.setLeftOffset(t), this.setTopOffset(e);
            }, t.prototype.setLeftOffset = function (t) {
                t ? this.paperContentTarget.css("left", t + "pt") : this.paperContentTarget[0].style.left = "";
            }, t.prototype.setTopOffset = function (t) {
                t ? this.paperContentTarget.css("top", t + "pt") : this.paperContentTarget[0].style.top = "";
            }, t.prototype.createTarget = function () {
                this.target = $('<div class="hiprint-printPaper"><div class="hiprint-printPaper-content"></div></div>'), this.paperContentTarget = this.target.find(".hiprint-printPaper-content"), this.target.css("width", this.mmwidth + "mm"), this.target.css("height", this.mmheight - p.a.instance.paperHeightTrim + "mm"), this.target.attr("original-height", this.mmheight);
            }, t.prototype.createHeaderLine = function () {
                var t = this;
                this.headerLinetarget = $('<div class="hiprint-headerLine"  style="position: absolute;width: 100%;border-top: 1px dashed rgba(0,0,0,.1);height: 7pt;"></div>'), this.headerLinetarget.css("top", (this.paperHeader || -1) + "pt"), 0 == this.paperHeader && this.headerLinetarget.addClass("hideheaderLinetarget"), this.paperContentTarget.append(this.headerLinetarget), this.dragHeadLineOrFootLine(this.headerLinetarget, function (e, n) {
                    t.paperHeader = n, t.triggerOnPaperBaseInfoChanged();
                });
            }, t.prototype.createFooterLine = function () {
                var t = this;
                this.footerLinetarget = $('<div class="hiprint-footerLine"  style="position: absolute;width: 100%;border-top: 1px dashed rgba(0,0,0,.1);height: 7pt;"></div>'), this.footerLinetarget.css("top", parseInt(this.paperFooter.toString()) + "pt"), this.paperFooter == this.height && (this.footerLinetarget.css("top", this.mmheight - p.a.instance.paperHeightTrim + "mm"), this.footerLinetarget.addClass("hidefooterLinetarget")), this.paperContentTarget.append(this.footerLinetarget), this.dragHeadLineOrFootLine(this.footerLinetarget, function (e, n) {
                    t.paperFooter = n, t.triggerOnPaperBaseInfoChanged();
                });
            }, t.prototype.createPaperNumber = function (t) {
                var e = this,
                    n = this.target.find(".hiprint-paperNumber");
                if (n.length) return n.html(t), n;
                var i = $('<span class="hiprint-paperNumber"  style="position: absolute">' + t + "</span>");
                return i.css("top", this.paperNumberTop + "pt"), i.css("left", this.paperNumberLeft + "pt"), this.paperContentTarget.append(i), this.dragHeadLineOrFootLine(i, function (t, n) {
                    e.paperNumberTop = n, e.paperNumberLeft = t, e.triggerOnPaperBaseInfoChanged();
                }, !0), i;
            }, t.prototype.getTarget = function () {
                return this.target;
            }, t.prototype.append = function (t) {
                this.paperContentTarget.append(t);
            }, t.prototype.updateReferenceElement = function (t) {
                t && (this.referenceElement = t);
            }, t.prototype.updatePrintLine = function (t) {
                t >= this.printLine && (this.printLine = t);
            }, t.prototype.design = function (t) {
                var e = this;
                this.createHeaderLine(), this.createFooterLine(), this.target.addClass("design"), this.paperNumberTarget = this.createPaperNumber(this.formatPaperNumber(1, 1)), this.createRuler(), this.resetPaperNumber(this.paperNumberTarget), $(this.paperNumberTarget).bind("dblclick.hiprint", function () {
                    null == e.paperNumberDisabled && (e.paperNumberDisabled = !1), e.paperNumberDisabled = !e.paperNumberDisabled, e.resetPaperNumber(e.paperNumberTarget), e.triggerOnPaperBaseInfoChanged();
                }), $(this.paperNumberTarget).bind("click.hiprint", function () {
                    o.a.event.trigger("BuildCustomOptionSettingEventKey_" + e.templateId, {
                        options: {
                            paperNumberFormat: e.paperNumberFormat,
                            paperNumberDisabled: e.paperNumberDisabled
                        },
                        callback: function callback(t) {
                            e.paperNumberDisabled = !!t.paperNumberDisabled || void 0, e.paperNumberFormat = t.paperNumberFormat ? t.paperNumberFormat : void 0, e.createPaperNumber(e.formatPaperNumber(1, 1)), e.resetPaperNumber(e.paperNumberTarget), e.triggerOnPaperBaseInfoChanged();
                        }
                    });
                });
            }, t.prototype.resetPaperNumber = function (t) {
                this.paperNumberDisabled ? t.addClass("hiprint-paperNumber-disabled") : t.removeClass("hiprint-paperNumber-disabled");
            }, t.prototype.updatePaperNumber = function (t, e, n) {
                var i = this.createPaperNumber(this.formatPaperNumber(t, e));
                this.paperNumberDisabled ? i.hide() : n && this.index % 2 == 1 && (i[0].style.left = "", i.css("right", this.paperNumberLeft + "pt"));
            }, t.prototype.formatPaperNumber = function (t, e) {
                return (this.paperNumberFormat ? this.paperNumberFormat : this.defaultPaperNumberFormat).replace("paperNo", t.toString()).replace("paperCount", e.toString());
            }, t.prototype.dragHeadLineOrFootLine = function (t, e, n) {
                var i = this;
                t.hidraggable({
                    axis: n ? void 0 : "v",
                    onDrag: function onDrag(t, n, i) {
                        e(n, i);
                    },
                    moveUnit: "pt",
                    minMove: p.a.instance.movingDistance,
                    onBeforeDrag: function onBeforeDrag(t) {
                        s.a.instance.draging = !0;
                    },
                    onStopDrag: function onStopDrag(t) {
                        s.a.instance.draging = !1, i.footerLinetarget.removeClass("hidefooterLinetarget"), i.headerLinetarget.removeClass("hideheaderLinetarget");
                    }
                });
            }, t.prototype.resize = function (t, e) {
                this.width = o.a.mm.toPt(t), this.height = o.a.mm.toPt(e), this.mmwidth = t, this.mmheight = e, this.target.css("width", t + "mm"), this.target.css("height", e - p.a.instance.paperHeightTrim + "mm"), this.target.attr("original-height", this.mmheight), this.paperFooter = this.height, this.footerLinetarget.css("top", this.height + "pt"), this.contentHeight = this.paperFooter - this.paperHeader, this.paperNumberLeft = parseInt((this.width - 30).toString()), this.paperNumberTop = parseInt((this.height - 22).toString()), this.paperNumberTarget.css("top", this.paperNumberTop + "pt"), this.paperNumberTarget.css("left", this.paperNumberLeft + "pt"), this.triggerOnPaperBaseInfoChanged();
            }, t.prototype.getPaperFooter = function (t) {
                var e = this.index + t;
                return 0 == e ? this.firstPaperFooter ? this.firstPaperFooter : this.oddPaperFooter ? this.oddPaperFooter : this.paperFooter : e % 2 == 0 ? this.oddPaperFooter ? this.oddPaperFooter : this.paperFooter : e % 2 == 1 ? this.evenPaperFooter ? this.evenPaperFooter : this.paperFooter : void 0;
            }, t.prototype.getContentHeight = function (t) {
                return this.getPaperFooter(t) - this.paperHeader;
            }, t.prototype.createRuler = function () {
                this.target.append('<div class="hiprint_rul_wrapper">\n                     <img class="h_img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAB9AAAAAPCAYAAAC891QNAAAKxklEQVR4Xu1dPezlQxQ92yE6opGIaOg2QeWjUVjRSCg24qMgQtBItHazq5XoJBtBgYiCROGz0CBRiGRVdKISoRNKcmIudyfze+/tvL27v/Oc1+yX3/ife2buOXPv/OYdAXASwCnof4xjXRyaD/NREQHPq4qozo9pPuZjV/Gk+aiI6vyY5mM+dhVPmo+KqM6PaT7mY1fxpPmoiOr8mOZjPnYVT5qPiqjOj2k+5mNX8aT5qIjq/JjmYz52FU+aj4qozo9pPuZjV/Gk+aiI6vyY5mM+dhVPmo+KqE6OeQTAXwD4q/rHONbFoPkwHxUR8LyqiOr8mOZjPnYVT5qPiqjOj2k+5mNX8aT5qIjq/JjmYz52FU+aj4qozo9pPuZjV/Gk+aiI6vyY5mM+dhVPmo+KqM6PaT7mY1fxpPmoiOr8mOZjPnYVT5qPiqjOj2k+5mNX8aT5qIjq5JhuoE8GrvAxL5DC4E4MbT4mglb4iPkoDO7E0OZjImiFj5iPwuBODG0+JoJW+Ij5KAzuxNDmYyJohY+Yj8LgTgxtPiaCVviI+SgM7sTQ5mMiaIWPmI/C4E4MbT4mglb4iPkoDO7E0OZjImiFj5iPwuBODG0+JoJW+Ij5KAzu+Q6dG+gPAXgLwBkAzwH483wHu8T/fZ5YtwO4HsDbAK5qvx4DcAeAry7xz7ntfx84go9PAfD3/BCPEo4rALwM4Mk0r/h3ajjihgbOpacBvARAFUfMK84nrofvRfkIHGcBHAfwqyCOmwC8C+BoW98PA/hEEAfXxwsATgNQzlfE0eug6jrnlNqmg2vW/CU9Jy7+3D82Lb+xrSH+PfPAD9sE9iL/+y6+hOuemqjIB+fYly2m4a8UccS0yHNLEUf2u+Hl71+xt99lfZwA8KLo+ghd5PwKbbxHkI/Is/QqyvlqtB9UWOe77AcVcfwG4HIAzwN4BQD/rIgj78+V1kc/r7gnUdTzHgfnUe8V1eeVkp5vyldKet7jYP2H+1w1Pe9xXJ1qD8r5alQXXfs637UuqoiDfQM1Pd/Gh8r6GOG4WVDPRziiH6W0P982r1T0fBsOFT0f4eC+Q03PRziuE9TzbfNq9fXEaKDTTEVjkMW2KE5f5FrzXv+7KMRFwSqLHvGwURgY13w4gDjIB3l4NTVBGBw1HPmrAe5rHNwqjIPifWc7YBLrRGlekY8nALzfClW5wKCEI+crYmAiZlFaeX0EL4o4Mh807PzwAJMaHz0OYlDNu9t08HUAj7XDQGvU/JGeM2/FgawwVzzs91Hj6d7273sZiQv88JIvCT38qTUP3gHwoBgfbPrHh40pziNytGYvucQHcURzjc3arIcq64N8sNHJJsgHjZh86E8JRy6UEMNlgvkqr4/ghc0pxfURfHwL4BEAH4rykXGweKKQd3fZDyro+QjHN63w83M6KL729THC8XvTDCU9H+GInKWk50s41PR8aX2o6fmmfMUXWFT0fNP6UNLzTXwo6fk2HCp6vktdVEHPRzi47eBLIUp6PsLBnMs9oJKej3DECwVKer6EQ03Pl9aHmp5vyldKer5pfSjp+SY+lPR8G47V63k00Lmgo/jcF+AucD25bLilN1miwE4h6ZuHZT/MHgP3VzTEz09+2ChQxMFmzrWt8fyUKA6ui1sAXAngTQCKOPKJn3gb6lFBPpivuC54s4EyjhCQnHNV81W8ofZee1tQEUfWwXh7+xrB9RHzKr+B3vPxHYDbWsN5jZq/yxvoNIvx5tofrbHD3LymA3KbcNAmMPah7Wv2YJtwxNp/pt0EooqD/vCXhoE3mijiyDcC8BApG1SKOJiveJjs7vbGsyqO2ArE3oNFYEU+iIM+/oF20wf/rIgjdDAKo58J6SBjvrQfVNLzjIP72fzGmtL66HGo6nmPQ1XPexyqep5xcD3EjQBqet7nK1U9H61zRT3vcajq+UgHFfV8U11USc8zjv4NdCU973Go6nmPQ1XPexyqep5x5BsB1PS8z1eqej5a54p63uNQ1fORDkrouRvoe3S6ix6NQjWLCnzb7ot2vatiQyqfMFFvSDHBftyKhqoN9EPh49Aanflgj+I6H725rdp4jje3WVjnlaI3uIFepHSbh/0/NNCpiZxvfCtSqdCQD2YEi9EEUWpIZRxxiISNc35UG+iHwodyo7NfH/mAUhyYobdXOrjUv7mtvM75hhQ/XwN4Q6SBvm0/qFJw73GQB8UG+giHop6PcGROVNZ5j0NVzw+VD1U9H/GhqOc9DlU9H61zRT0Pn75UF1XR8x4HbzBR1PMRDkU9H+FQ1PMeBw9Qs+6ruD8nln5PqFgvyTiivivR6ATQv5g6qv3w1j6l/Xnmg7/nja/xUpGKbx+tcxk9P8Qr3PtkFUZR6Ypqvil8CsBr6TtdFXEcypXIxBHf2875xZNjkbCU5tWh8DFqoCvywfwbVyvx6xr4UVzn5ONxACcB8ISl8tXnIejBg+JV9KM30Pt5pXRFXL/5yNc7q1zh3vsSYrqr3dbAf1O5anvJX4VxV7gSeWl9vNUWP281eVbkSv1D5aMvuKvOK/ITX9fAt21V1zkP+nyeNujKfOR8q6CDu+wHVXH0DXT+ee1XuI/4UNTzTfNKSc+XcKjp+SHzoajnIz4U9XyEQ1HPl/hQ0/Nd6nAKej7C0TfQFfR8hIPNKLX9+aZ5paTnSzjU9PyQ+egb6Ar7waV8pbY/H+Hgi15q+/MlPmT0PBro/JVFaSaoM+1q5Py2KgH1JzjW/HdRcCee3Pzk96byOqy1Y2NDiqcwjkY3RxRHnlcsSh8TxhGFHn7/I9eI4rw6JD4iX6nPq3yanTnW8+rS5ef8FQehg6p85Mbakg7yIFBoPgtcvAZ9jdqY9TxyGDe4/P4lrpmzTSePtwNna/UqGccJAKdTw5baqMhHXh/x8yviyE110kKfqIjjUPg4FBycV/mGGeYmz6uLqzM578YekGs8fr92PnbdDyri4PrIb6zxDSNFHIp6vm1eqej5CIeinh8yH4p6vjSv1PT8kOeVop7vWodbuw6OcCjq+QiHop5vm1cqer40r6KmpbQ/31SnVuZDUc+X5pWanh/yOpfS89xAT71aqWZ5/NxrLZr75/tvZpkjrYMonrueu9aFHAGv3zUfnHO+cr5yvnK+WuMhIOcm5ybnJucm56aLe3jGedd513nXedd513nX9WfXr1y/+kcLvBYcA8+DPdeCG+h7BtCJyMm47U0sSBYkC5LzqQt2Lti5YOeCnQt2LtjZE9oT2hPaE9oT2hPaE9oT2hPaE9oT2hPaE9oT2hPaE9oTintCN9CdyJ3IncidyMUTuQ/y+CCPD/L8u4hdpHCRwkUKe1t7W3tbe1t7Wzeu3LiyJ7QntCe0J7QntCe0J7QntCe0J7QntCfcyxO6gW5DaUNpQ2lDaUNpQ2lDaUNpQ7mXofRBHh/k8UEeH+RJdsqaYk2xprjO4DqD6wyuM7jO4DqD6wz2hPaE9oT2hPaE9oTSntANdCcxJzEnMekk5qaNmzZu2rhp46bNOdU5FylcpHCRwv7e/t7+3v7ejSs3rty4sie0J7QntCe0J7QntCe0J7QntCe0J9zDE7qBbjNlM2UzZTNlM2UzZTNlM7WHmfJBHh/k8UEeH+TxQR4f5DnXTsK6al1148q1FtdaXGtxrcW1FtdaXGuxJ7QntCe0J7QnFPaEfwNdvyoPYn5mCwAAAABJRU5ErkJggg==" />\n                     <img class="v_img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAB9AAAAAPCAYAAAC891QNAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAiHSURBVHhe7Z29q21HGYevplLTKSL4UQQkwUYQG1EUtBGba5qkyEeX+NGI9irof2ClvTaCtoqgoEkISAIpTZMiJBCCQsCvSq7vc88emExm7XO2++Tc+S2fB17WmnftzZ3ffWf2b601a+9za8Kdw7bHnDkwZw7MmQNz5sCcOTBnDsyZA3PmwJw5MGcOzJkDc+bAnDkwZw7MmYPlc+897IiIiIiIiIiIiIiIiIiIiPxf4wK6iIiIiIiIiIiIiIiIiIhI4QK6iIiIiIiIiIiIiIiIiIhI4QK6iIiIiIiIiIiIiIiIiIhI4QK6iIiIiIiIiIiIiIiIiIhIkbCA/vmKOxW/rfggieKxCvJJzHTQJl6qeJBEADMd7JOjLinMdDxd8b6L3Rj2XI+96IDbFSlzHPZcj59WkGO74nynT62P7f96poNj5FeGMY+/EQ363I+h1XXM6tHmAvF9EkWiDrZp42prfox9th43w551AP1/6mL3LtbjZtia57SJ1vdkHX09mq5VrwdnOug7/sexRqIO6tD63fw8UQdb2unjivkxzulEHXupx150AP3v/TxRx17q0XyQaPM9WUdfD/b716zGTAd9H/08UUfrM9H8PFEHW9rp42rm59bjZtizDqD/vZ9bj5tha57TJtp8T9bR1+Md64OzBfT3HLY99yr3/oovV3yo4scVX62YvW7GdfflnNyWDuKhit9VvFox45x/97pzMx2PH/Y59kBFG2wjN9G/q+a2dPys4l8V/aQZuYn+XTW393qwz+s/XLF1IXXdfTknN9PRXoeGv17sTrnuvpyTu2xcfboitR5fqHimgtd/s+LfFTOuuy+n5D5TQR/pP3P4YxVb4+oyzu3LyKm5r1U8eojvkRiYvXfGdfSl55TcWA/q0Grwg4o/HPavwin/7si5uVHHJw9b2knjaqaDmyTPVrST4Nl7Z5zbl5FTcnuuR9PxnYonK2bvnXFuX0ZOyc3mObB982J3+t4Z5/Zl5JTcZePq2xWz9844ty8jp+RGHb0Pcvy5w/YqnPLvjpybO6ajn+fEyteDW/Ojh9cRaTpmfk4k6djyDyJNx8zPCevxdt6N3DEdvZ8TafMc2PZ+TiSPq+bnRJKOLT8nUnW0eZ5wH25rfvSk6hj9PFHHzD9SdYx+bj1uLndMR/Pz1HkObJuf72Fc4eeJOmZ+nq6Dvrd5/o71wdW/gY6gNyr+VsHiE4tQiWzpwEi+W/HDiq2FnJU4Vg/6z2Dj2OrMdPy64hsVH6/4VUWqjkZ6PRLZ0tFMI6EWcFk9/l5BfnVmOjBFThg5kW9PJq8GfXyhgjn8jwoMPXF+8LTePyu4CUK/7z/k0hjrQZs5zYkUF+ecKCYw6uhPZtHwi4vd5Rl13FeB1/FwzNcrOJbAXuvR60hi1EEbuJn7l4vdCC6rxyuH7eqMOvDBj1b8vmLVb6fNmOmY+fnq14Nb82MkUcfMz9N0bPlHmo4tP7ceN8MxHT2J8xxGP0+vR/PzNB1bfp6oY+v6nNeseh9ua37MSNMx83NI0rHlH5Ck49j1ufV49zmmYyRJB22YXZ8n16O/Pk/Scez6PE3H6OfT9UH/Bvq9g8FFYY59CzIBBtYTFasuRp3KFyv+dLEbCSeN1IIFQn6WInHBCnodKTdKt+DEMXlMASeLPJX1fMUvK1Y0wqvAIu6PKnjyDLNMH1tyb+AhDJ7e7S/O03irghsMzOk9zANOhDmnevluKw/qwcUTT7nie+NP3qXQ6/gJiWC4uE0eU0A98GzmOR7e3/hJ4/WKz1Xwiyb8usle2Mv1YKqO0c8Tdcz8PHlc9X6eWo/Rz9N1ND9PHVejn6fWY/Tz1HqMfp6qY2Qv90VTdYx+nqhj5ufJ46r389R6jH6erqP5eeq4Gv08tR6jn6fWY/TzVB1bvG19cPUFdCb4RyqYJHz4tp9pAH4OJKUoMx2frfhzBfD3Gzi2OjMdn6j4TQVPl6SwNa74KQcW1lJuls50sLDJT07QfrEi4SJkqx4PV/AtYj6UeRJodWY62O8NPoGZDk4WOZnHHB+p4NjqbI0r4MESFtNXHFf0CX9ofXytYkvHyj7IZ88HKvAI+s0DC+3z6OcV/QM+K+sY6/Gfw5Zv1nNhe7uikaSD9qcqvlIxfj6l6YBvVXDC3pOmg4snzkF4Ur/pgiQdzHMegEMHDyulfF7N6rH18FtaPb5UgXfj4XzDq5Gko39oL8kHRx0zP+f46teDs/kBnBu2m7yJOmZ+nlqP0c9TdUDv56k6Rj9P1DHz89R6jH6eWo/RzxN1zPw8UcfMzxPui87mB/R+nqhj5uep9Rj9PHlc9X6eqmP080QdMz9Prcfo56n1GP08UcfMzxN1bN1vT1sfvAtmztM+TPh2MsUAI9/g+OqMOgj2ybUbWok6GHTtD/C3BZFUHdyAa2MMEnW0NpE8rjjx5WdA0nXQd9pE+1mTRB0E++TajepEHf3n1arjatbHUQdwjHxjNR3Q5nH/kz70uemC1XXM6sE2rR7HxhXR+p6qo+030nT07fRxhUek66Df9J820XwvsR5safefw4k66C/tvXzuNh39WGuvSdBBv5kXHINUHWxpp9ejjSuC/WQdbR8SdfTt9HE1+nmijr7PBJpS68GWdvPzVB178cFRR/8atrQTdND33s9TdbClnV6P0c+TdbR9SNTRt9PH1ejniTrod/MKon1upekAtrSbn6fq2IsPznS088XGajr+Z/YiRB1roY61UMdaqGMt1LEW6lgLdayFOtZCHWuhjrVQx1qoYy3UsRbqWAt1rIU61kIda6GOtVDHWtzxb6CLiIiIiIiIiIiIiIiIiIgU9x22e+CPh2066lgLdayFOtZCHWuhjrVQx1qoYy3UsRbqWAt1rIU61kIda6GOtVDHWqhjLdSxFupYC3WshTqW4dat/wKB2hwSL8nDjQAAAABJRU5ErkJggg==" />\n                    </div>');
            }, t.prototype.displayHeight = function () {
                return this.mmheight - p.a.instance.paperHeightTrim + "mm";
            }, t.prototype.displayWidth = function () {
                return this.mmwidth + "mm";
            }, t.prototype.getPanelTarget = function () {
                return this.target.parent(".hiprint-printPanel ");
            }, t;
        }(),
        P = n(6),
        _ = function () {
            var _t5 = function t(e, n) {
                return (_t5 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t5(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        w = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new b(n), i.options.setDefault(new b(p.a.instance.longText.default).getPrintElementOptionEntity()), i;
            }

            return _(e, t), e.prototype.getDesignTarget = function (e) {
                var n = t.prototype.getDesignTarget.call(this, e);
                return n.find(".hiprint-printElement-longText-content").css("border", "1px dashed #cebcbc"), n;
            }, e.prototype.getProxyTarget = function (t) {
                t && this.SetProxyTargetOption(t);
                var e = this.getData(),
                    n = this.createTarget(this.printElementType.getText(!0), e);
                return this.updateTargetSize(n), this.css(n, e), n;
            }, e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData(),
                        e = this.getHtml(this.designPaper)[0].target;
                    this.designTarget.find(".hiprint-printElement-longText-content").html(e.find(".hiprint-printElement-longText-content").html()), this.css(this.designTarget, t);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.longText;
            }, e.prototype.getTitle = function () {
                return this.options.title || this.printElementType.title;
            }, e.prototype.getData = function (t) {
                return t ? t[this.getField()] || "" : this.options.testData || this.printElementType.getData() || "";
            }, e.prototype.updateTargetText = function (t, e, n) {
                var i = t.find(".hiprint-printElement-longText-content"),
                    o = this.getText(e, n);
                i.html(o);
            }, e.prototype.createTarget = function (t, e) {
                var n = $('<div  class="hiprint-printElement hiprint-printElement-longText" style="position: absolute;"><div class="hiprint-printElement-longText-content hiprint-printElement-content" style="height:100%;width:100%"></div></div>');
                return this.updateTargetText(n, t, e), n;
            }, e.prototype.getText = function (t, e) {
                var n = this.getFormatter();
                e && (e = 0 != this.options.leftSpaceRemoved ? e.toString().replace(/^\s*/, "") : e);
                return (this.getField() ? (this.options.getHideTitle() ? "" : t ? t + "：" : "") + (n ? n(t, e, this.options, this._currenttemplateData) : e) : n ? n(t, t, this.options, this._currenttemplateData) : t || "") || "";
            }, e.prototype.getHtml = function (t, e) {
                this.setCurrenttemplateData(e), this.createTempContainer();
                var n = this.getPaperHtmlResult(t, e);
                return this.removeTempContainer(), n;
            }, e.prototype.getHeightByData = function (t) {
                this.createTempContainer();
                var e = this.getPaperHtmlResult(new T("", 1e3, 1e3, 0, 25e3, 0, 0, !0, void 0, 0, void 0), {}, t);
                return this.removeTempContainer(), e[0].referenceElement.bottomInLastPaper - e[0].referenceElement.printTopInPaper;
            }, e.prototype.getLongTextIndent = function () {
                return this.options.longTextIndent ? '<span class="long-text-indent" style="margin-left:' + this.options.longTextIndent + 'pt"></span>' : '<span class="long-text-indent"></span>';
            }, e.prototype.getPaperHtmlResult = function (t, e, n) {
                var i = this,
                    o = [],
                    r = 0,
                    a = n || this.getData(e),
                    p = this.getText(this.getTitle(), a),
                    s = this.createTarget(this.getTitle(), this.options.testData || "");
                this.css(s, a), e ? this.updateTargetWidth(s) : this.updateTargetSize(s), this.getTempContainer().html(""), this.getTempContainer().append(s);
                var l = [this.getLongTextIndent()],
                    u = p.split(new RegExp("\r|\n", "g"));
                if (u.forEach(function (t, e) {
                    var n = 0 != i.options.leftSpaceRemoved ? (t || "").toString().replace(/^\s*/, "") : t;
                    l = l.concat(n.split("")), e < u.length - 1 && l.push("<br/>" + i.getLongTextIndent());
                }), 0 == l.length && (l = [""]), this.isHeaderOrFooter() || this.isFixed() || !e) return (f = this.getStringBySpecificHeight(l, 25e3, s)).target.css("left", this.options.displayLeft()), f.target.css("top", this.options.displayTop()), f.target[0].height = "", o.push(new P.a({
                    target: f.target,
                    printLine: this.options.displayTop() + f.height,
                    referenceElement: new E.a({
                        top: this.options.getTop(),
                        left: this.options.getLeft(),
                        height: this.options.getHeight(),
                        width: this.options.getWidth(),
                        beginPrintPaperIndex: t.index,
                        bottomInLastPaper: this.options.getTop() + f.height,
                        printTopInPaper: this.options.getTop()
                    })
                })), o;

                for (var d = this.getBeginPrintTopInPaperByReferenceElement(t); l.length > 0;) {
                    var c = 0,
                        h = t.getPaperFooter(r);
                    0 == r && d > h && (d = d - h + t.paperHeader, o.push(new P.a({
                        target: void 0,
                        printLine: void 0
                    })), r++ , c = t.getContentHeight(r) - (d - t.paperHeader), h = t.getPaperFooter(r));
                    var f = this.getStringBySpecificHeight(l, c > 0 ? c : 0 == r ? h - d : t.getContentHeight(r), s);
                    l.splice(0, f.length);
                    var g = void 0,
                        m = void 0;
                    f.target.css("left", this.options.displayLeft()), f.target[0].height = "", 0 == r || c > 0 ? (m = d, f.target.css("top", m + "pt"), g = l.length > 0 ? d + f.height : null != this.options.lHeight ? d + (f.height > this.options.lHeight ? f.height : this.options.lHeight) : d + f.height) : (m = t.paperHeader, f.target.css("top", m + "pt"), g = m + f.height), o.push(new P.a({
                        target: f.target,
                        printLine: g,
                        referenceElement: new E.a({
                            top: this.options.getTop(),
                            left: this.options.getLeft(),
                            height: this.options.getHeight(),
                            width: this.options.getWidth(),
                            beginPrintPaperIndex: t.index,
                            bottomInLastPaper: g,
                            printTopInPaper: m
                        })
                    })), r++;
                }

                return o;
            }, e.prototype.getStringBySpecificHeight = function (t, e, n) {
                var i = o.a.pt.toPx(e),
                    r = this.IsPaginationIndex(t, t.length - 1, i, n);
                return r.IsPagination ? r : this.BinarySearch(t, 0, t.length - 1, i, n);
            }, e.prototype.BinarySearch = function (t, e, n, i, o) {
                var r = Math.floor((e + n) / 2);
                if (e > n) return o.find(".hiprint-printElement-longText-content").html(""), {
                    IsPagination: !0,
                    height: 0,
                    length: 0,
                    target: o.clone()
                };
                var a = this.IsPaginationIndex(t, r, i, o);
                return a.IsPagination ? a : "l" == a.move ? this.BinarySearch(t, e, r - 1, i, o) : this.BinarySearch(t, r + 1, n, i, o);
            }, e.prototype.IsPaginationIndex = function (t, e, n, i) {
                i.find(".hiprint-printElement-longText-content").html(t.slice(0, e + 2).join(""));
                var r = i.height();
                i.find(".hiprint-printElement-longText-content").html(t.slice(0, e + 1).join(""));
                var a = i.height();
                return e >= t.length - 1 && a < n ? {
                    IsPagination: !0,
                    height: o.a.px.toPt(a),
                    length: t.length,
                    target: i.clone()
                } : a <= n && r >= n ? {
                    IsPagination: !0,
                    height: a,
                    length: e + 1,
                    target: i.clone()
                } : a >= n ? {
                    IsPagination: !1,
                    move: "l"
                } : r <= n ? {
                    IsPagination: !1,
                    move: "r"
                } : {
                                    IsPagination: !0,
                                    result: 1
                                };
            }, e;
        }(f.a),
        x = function () {
            function t() { }

            return t.replaceEnterAndNewline = function (t, e) {
                return t.replace(new RegExp("\r|\n|/g", "g"), e);
            }, t.replaceTab = function (t, e) {
                return t.replace(new RegExp("\t/g", "g"), e);
            }, t.replaceEnterAndNewlineAndTab = function (t, e) {
                return t.replace(new RegExp("\r|\n|\t|/g", "g"), e);
            }, t;
        }(),
        C = function () {
            var _t6 = function t(e, n) {
                return (_t6 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t6(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        O = function (t) {
            function e(e) {
                var n = t.call(this, e) || this;
                return n.title && (n.title = x.replaceEnterAndNewlineAndTab(n.title, "")), n;
            }

            return C(e, t), e.prototype.getHideTitle = function () {
                return null == this.hideTitle ? this.defaultOptions.hideTitle : this.hideTitle;
            }, e.prototype.getTextType = function () {
                return (null == this.textType ? this.defaultOptions.textType : this.textType) || "text";
            }, e.prototype.getFontSize = function () {
                return (null == this.fontSize ? this.defaultOptions.fontSize : this.fontSize) || 9;
            }, e.prototype.getbarcodeMode = function () {
                return (null == this.barcodeMode ? this.defaultOptions.barcodeMode : this.barcodeMode) || "CODE128";
            }, e;
        }(g.a),
        H = function () {
            var _t7 = function t(e, n) {
                return (_t7 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t7(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        D = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new O(n), i.options.setDefault(new O(p.a.instance.text.default).getPrintElementOptionEntity()), i;
            }

            return H(e, t), e.prototype.getDesignTarget = function (e) {
                return t.prototype.getDesignTarget.call(this, e);
            }, e.prototype.getProxyTarget = function (t) {
                t && this.SetProxyTargetOption(t);
                var e = this.getData(),
                    n = this.createTarget(this.printElementType.getText(!0), e);
                return this.updateTargetSize(n), this.css(n, e), n;
            }, e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData();
                    this.css(this.designTarget, t), this.updateTargetText(this.designTarget, this.getTitle(), t);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.text;
            }, e.prototype.getTitle = function () {
                var t = this.options.title || this.printElementType.title || "";
                return t && (t = x.replaceEnterAndNewlineAndTab(t, "")), t;
            }, e.prototype.getData = function (t) {
                var e = void 0;

                if (e = t ? t[this.getField()] || "" : this.options.testData || this.printElementType.getData() || "", this.options.format) {
                    if ("datetime" == this.options.dataType) return o.a.dateFormat(e, this.options.format);

                    if ("boolen" == this.options.dataType) {
                        var n = this.options.format.split(":");
                        if (n.length > 0) return !0 === e || "true" === e ? n[0] : n[1];
                    }
                }

                return e;
            }, e.prototype.updateTargetText = function (t, e, n, i) {
                var r = this.getFormatter(),
                    a = t.find(".hiprint-printElement-text-content"),
                    p = "";
                p = this.getField() ? (this.options.getHideTitle() ? "" : e ? e + "：" : "") + (r ? r(e, n, this.options, this._currenttemplateData, t) : n) : n = r ? r(e, e, this.options, this._currenttemplateData, t) : e;
                var s = this.options.getTextType();
                if ("text" == s) a.html(p); else {
                    if ("barcode" == s) {
                        a.html('<svg width="100%" display="block" height="100%" class="hibarcode_imgcode" preserveAspectRatio="none slice"></svg ><div class="hibarcode_displayValue"></div>');

                        try {
                            n ? (JsBarcode(a.find(".hibarcode_imgcode")[0], n, {
                                format: this.options.getbarcodeMode(),
                                width: 1,
                                textMargin: -1,
                                lineColor: this.options.color || "#000000",
                                margin: 0,
                                height: parseInt(o.a.pt.toPx(this.options.getHeight() || 10).toString()),
                                displayValue: !1
                            }), a.find(".hibarcode_imgcode").attr("height", "100%"), a.find(".hibarcode_imgcode").attr("width", "100%"), this.options.hideTitle || a.find(".hibarcode_displayValue").html(n)) : a.html("");
                        } catch (t) {
                            console.log(t), a.html("此格式不支持该文本");
                        }
                    }

                    if ("qrcode" == s) {
                        a.html("");

                        try {
                            if (n) {
                                var l = parseInt(o.a.pt.toPx(this.options.getWidth() || 20)),
                                    u = parseInt(o.a.pt.toPx(this.options.getHeight() || 20));
                                new QRCode(a[0], {
                                    width: l,
                                    height: u,
                                    colorDark: this.options.color || "#000000",
                                    useSVG: !0
                                }).makeCode(n);
                            }
                        } catch (t) {
                            console.log(t), a.html("二维码生成失败");
                        }
                    }
                }
            }, e.prototype.onResize = function (e, n, i, o, r) {
                t.prototype.onResize.call(this, e, n, i, o, r);
                "barcode" != this.options.getTextType() && "qrcode" != this.options.getTextType() || this.updateTargetText(this.designTarget, this.getTitle(), this.getData());
            }, e.prototype.createTarget = function (t, e, n) {
                var i = $('<div tabindex="1" class="hiprint-printElement hiprint-printElement-text" style="position: absolute;"><div class="hiprint-printElement-text-content hiprint-printElement-content" style="height:100%;width:100%"></div></div>');
                return this.updateTargetText(i, t, e, n), i;
            }, e.prototype.getHtml = function (t, e, n) {
                return this.getHtml2(t, e, n);
            }, e;
        }(f.a),
        I = function () {
            var _t8 = function t(e, n) {
                return (_t8 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t8(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        R = function (t) {
            function e(e) {
                return t.call(this, e) || this;
            }

            return I(e, t), e;
        }(g.a),
        M = function () {
            var _t9 = function t(e, n) {
                return (_t9 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t9(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        S = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new R(n), i.options.setDefault(new R(p.a.instance.html.default).getPrintElementOptionEntity()), i;
            }

            return M(e, t), e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData();
                    this.css(this.designTarget, t), this.updateTargetHtml();
                }
            }, e.prototype.updateTargetHtml = function () {
                var t = this.getFormatter();

                if (t) {
                    var e = t(this.getData(), this.options, this._currenttemplateData);
                    this.designTarget.find(".hiprint-printElement-html-content").html(e);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.html;
            }, e.prototype.createTarget = function (t, e) {
                var n = $('<div  class="hiprint-printElement hiprint-printElement-html" style="position: absolute;"><div class="hiprint-printElement-html-content" style="height:100%;width:100%"></div></div>'),
                    i = this.getFormatter();

                if (i) {
                    var o = i(this.getData(), this.options, this._currenttemplateData);
                    n.find(".hiprint-printElement-html-content").append(o);
                } else this.options.content && n.find(".hiprint-printElement-html-content").append(this.options.content);

                return n;
            }, e.prototype.getHtml = function (t, e, n) {
                return this.getHtml2(t, e, n);
            }, e;
        }(f.a),
        B = function () {
            var _t10 = function t(e, n) {
                return (_t10 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t10(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        F = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new g.a(n), i.options.setDefault(new g.a(p.a.instance.vline.default).getPrintElementOptionEntity()), i;
            }

            return B(e, t), e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData();
                    this.css(this.designTarget, t);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.hline;
            }, e.prototype.createTarget = function (t, e) {
                return $('<div class="hiprint-printElement hiprint-printElement-vline" style="border-left:1px solid;position: absolute;"></div>');
            }, e.prototype.getReizeableShowPoints = function () {
                return ["s"];
            }, e.prototype.getHtml = function (t, e, n) {
                return this.getHtml2(t, e, n);
            }, e;
        }(f.a),
        L = function () {
            var _t11 = function t(e, n) {
                return (_t11 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t11(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        A = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new g.a(n), i.options.setDefault(new g.a(p.a.instance.hline.default).getPrintElementOptionEntity()), i;
            }

            return L(e, t), e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData();
                    this.css(this.designTarget, t);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.hline;
            }, e.prototype.createTarget = function (t, e) {
                return $('<div class="hiprint-printElement hiprint-printElement-hline" style="border-top:1px solid;position: absolute;"></div>');
            }, e.prototype.getReizeableShowPoints = function () {
                return ["e"];
            }, e;
        }(f.a),
        z = function () {
            var _t12 = function t(e, n) {
                return (_t12 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t12(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        k = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new g.a(n), i.options.setDefault(new g.a(p.a.instance.rect.default).getPrintElementOptionEntity()), i;
            }

            return z(e, t), e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData();
                    this.css(this.designTarget, t);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.hline;
            }, e.prototype.createTarget = function (t, e) {
                return $('<div class="hiprint-printElement hiprint-printElement-rect" style="border:1px solid;position: absolute;"></div>');
            }, e.prototype.getHtml = function (t, e, n) {
                return this.getHtml2(t, e, n);
            }, e;
        }(f.a),
        N = function () {
            var _t13 = function t(e, n) {
                return (_t13 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t13(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        V = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new g.a(n), i.options.setDefault(new g.a(p.a.instance.oval.default).getPrintElementOptionEntity()), i;
            }

            return N(e, t), e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    var t = this.getData();
                    this.css(this.designTarget, t);
                }
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.hline;
            }, e.prototype.createTarget = function (t, e) {
                return $('<div class="hiprint-printElement hiprint-printElement-oval" style="border:1px solid;position: absolute;border-radius: 50%;"></div>');
            }, e.prototype.getHtml = function (t, e, n) {
                return this.getHtml2(t, e, n);
            }, e;
        }(f.a),
        W = function () {
            function t() { }

            return t.createPrintElement = function (t, e) {
                return "text" == t.type ? new D(t, e) : "image" == t.type ? new v(t, e) : "longText" == t.type ? new w(t, e) : "table" == t.type ? new d.a(t, e) : "html" == t.type ? new S(t, e) : "vline" == t.type ? new F(t, e) : "hline" == t.type ? new A(t, e) : "rect" == t.type ? new k(t, e) : "oval" == t.type ? new V(t, e) : void 0;
            }, t;
        }(),
        j = function () {
            function t(t) {
                this.field = t.field, this.fields = t.fields, this.title = t.title, this.text = t.text, this.tid = t.tid, this.data = t.data, this.styler = t.styler, this.formatter = t.formatter, this.type = t.type, this.onRendered = t.onRendered, this.options = t.options;
            }

            return t.prototype.getText = function (t) {
                return t ? this.title || this.text || "" : this.text || this.title || "";
            }, t.prototype.getData = function () {
                return this.data;
            }, t.prototype.createPrintElement = function (t) {
                var e = {};
                return $.extend(e, t || {}), W.createPrintElement(this, e);
            }, t.prototype.getPrintElementTypeEntity = function () {
                return new c({
                    title: this.title,
                    type: this.type
                });
            }, t.prototype.getFields = function () {
                return this.fields;
            }, t.prototype.getOptions = function () {
                return this.options || {};
            }, t;
        }(),
        U = n(16),
        K = n(12),
        G = function () {
            var _t14 = function t(e, n) {
                return (_t14 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t14(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        q = function (t) {
            function e(e) {
                var n = t.call(this, e) || this;
                (e = e || {}).columns ? (n.columns = [], e.columns.forEach(function (t) {
                    n.columns.push(new K.a(t));
                })) : n.columns = [new K.a({
                    columns: [new u.a({
                        width: 100
                    }), new u.a({
                        width: 100
                    })]
                })];
                return n.lHeight = e.lHeight, n.autoCompletion = e.autoCompletion, n.tableFooterRepeat = e.tableFooterRepeat, n;
            }

            return G(e, t), e.prototype.getPrintElementOptionEntity = function () {
                var e = t.prototype.getPrintElementOptionEntity.call(this);
                return e.columns = [], this.columns.forEach(function (t) {
                    e.columns.push(t.getPrintElementOptionEntity());
                }), e;
            }, e;
        }(g.a),
        X = n(7),
        Y = function () {
            var _t15 = function t(e, n) {
                return (_t15 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t15(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        J = function (t) {
            function e(e, n) {
                var i = t.call(this, e) || this;
                return i.options = new q(n), i.options.setDefault(new q(p.a.instance.tableCustom.default).getPrintElementOptionEntity()), i.columns = i.options.columns, i;
            }

            return Y(e, t), e.prototype.updateDesignViewFromOptions = function () {
                if (this.designTarget) {
                    this.css(this.designTarget, this.getData());
                    var t = this.designTarget.find(".hiprint-printElement-table-content"),
                        e = this.getHtml(this.designPaper);
                    t.html(""), t.append(e[0].target.find(".hiprint-printElement-tableTarget")), this.setHiReizeable();
                }
            }, e.prototype.getDesignTarget = function (t) {
                var e = this;
                return this.designTarget = this.getHtml(t)[0].target, this.designPaper = t, this.designTarget.click(function () {
                    o.a.event.trigger(e.getPrintElementSelectEventKey(), {
                        printElement: e
                    });
                }), this.designTarget.find("td").hidroppable({
                    accept: ".rn-draggable-item",
                    onDrop: function onDrop(t, e) { },
                    onDragEnter: function onDragEnter(t, e) {
                        $(e).removeClass("rn-draggable-item");
                    },
                    onDragLeave: function onDragLeave(t, e) {
                        $(e).addClass("rn-draggable-item");
                    }
                }), this.designTarget;
            }, e.prototype.getConfigOptions = function () {
                return p.a.instance.tableCustom;
            }, e.prototype.createTarget = function (t, e, n) {
                var i = $('<div class="hiprint-printElement hiprint-printElement-table" style="position: absolute;"><div class="hiprint-printElement-table-handle"></div><div class="hiprint-printElement-table-content" style="height:100%;width:100%"></span></div>');
                return i.find(".hiprint-printElement-table-content").append(this.getTableHtml(e, n)), i;
            }, e.prototype.getTableHtml = function (t, e) {
                var n = $('<table class="hiprint-printElement-tableTarget" style="border-collapse: collapse;width:100%;"></table>');
                return n.append(X.a.createTableHead(this.columns, this.options.getWidth())), n.append(X.a.createTableRow(this.columns, t, this.options, this.printElementType)), this.printElementType.footerFormatter && ("no" == this.options.tableFooterRepeat || ("last" == this.options.tableFooterRepeat ? n.find("tbody").append(X.a.createTableFooter(this.printElementType.columns, t, this.options, this.printElementType, e, t).html()) : n.append(X.a.createTableFooter(this.printElementType.columns, t, this.options, this.printElementType, e, [])))), n;
            }, e.prototype.getHtml = function (t, e) {
                this.setCurrenttemplateData(e), this.createTempContainer();
                var n = this.getPaperHtmlResult(t, e);
                return this.removeTempContainer(), n;
            }, e.prototype.getPaperHtmlResult = function (t, e) {
                var n = [],
                    i = this.getData(e),
                    o = this.getTableHtml(i, e),
                    r = this.createTarget(this.printElementType.title, [], e);
                e ? this.updateTargetWidth(r) : this.updateTargetSize(r), this.css(r, i), this.css(o, i), this.getTempContainer().html(""), this.getTempContainer().append(r);

                for (var a, p = this.getBeginPrintTopInPaperByReferenceElement(t), s = 0, l = !1; !l;) {
                    var u = 0,
                        d = t.getPaperFooter(s);
                    0 == s && p > d && (p = p - d + t.paperHeader, n.push(new P.a({
                        target: void 0,
                        printLine: void 0
                    })), s++ , u = t.getContentHeight(s) - (p - t.paperHeader), d = t.getPaperFooter(s));
                    var c = n.length > 0 ? n[n.length - 1].target : void 0,
                        h = this.getRowsInSpecificHeight(u > 0 ? u : 0 == s ? d - p : t.getContentHeight(s), r, o, s, c, e);
                    l = h.isEnd;
                    var f = void 0;
                    h.target && (h.target.css("left", this.options.displayLeft()), h.target[0].height = ""), 0 == s || u > 0 ? (h.target && (a = p, h.target.css("top", p + "pt")), f = l && null != this.options.lHeight ? p + (h.height > this.options.lHeight ? h.height : this.options.lHeight) : p + h.height) : (h.target && (a = t.paperHeader, h.target.css("top", t.paperHeader + "pt")), f = t.paperHeader + h.height), n.push(new P.a({
                        target: h.target,
                        printLine: f,
                        referenceElement: new E.a({
                            top: this.options.getTop(),
                            left: this.options.getLeft(),
                            height: this.options.getHeight(),
                            width: this.options.getWidth(),
                            beginPrintPaperIndex: t.index,
                            bottomInLastPaper: f,
                            printTopInPaper: a
                        })
                    })), s++;
                }

                return n;
            }, e.prototype.getRowsInSpecificHeight = function (t, e, n, i, r, a) {
                var p = void 0,
                    s = n.find("tbody"),
                    l = o.a.pt.toPx(t);
                e.find("tbody").html("");

                for (var u = e.outerHeight(), d = []; ;) {
                    if (u <= l) {
                        if (0 == s.find("tr").length) {
                            a && this.options.autoCompletion && (this.autoCompletion(l, e), u = e.outerHeight()), p = {
                                target: e.clone(),
                                length: e.find("tbody tr").length,
                                height: o.a.px.toPt(u),
                                isEnd: !0
                            }, 0 == e.find("tbody tr").length && r && (p = {
                                target: void 0,
                                length: 0,
                                height: 0,
                                isEnd: !0
                            });
                        } else {
                            var c = s.find("tr:lt(1)");
                            e.find("tbody").append(c), u = e.outerHeight();
                            var h = c.data("rowData");
                            d.push(h), u > l && (s.prepend(c), d.pop(), u = e.outerHeight(), p = {
                                target: e.clone(),
                                length: e.find("tbody tr").length,
                                height: o.a.px.toPt(u),
                                isEnd: !1
                            });
                        }
                    } else p = {
                        target: void 0,
                        length: 0,
                        height: 0,
                        isEnd: !1
                    };

                    if (p) {
                        this.printElementType.footerFormatter && e.find("tfoot") && e.find("tfoot").html(X.a.createTableFooter(this.printElementType.columns, this.getData(a), this.options, this.printElementType, a, d).html());
                        break;
                    }
                }

                return p;
            }, e.prototype.getData = function (t) {
                if (!t) return [{}];
                var e = t[this.getField()];
                return e ? JSON.parse(JSON.stringify(e)) : [];
            }, e.prototype.autoCompletion = function (t, e) {
                for (var n, i = this.getEmptyRowTarget(), o = e.outerHeight(); t > o;) {
                    n = i.clone(), e.find("tbody").append(n), o = e.outerHeight();
                }

                n && n.remove();
            }, e.prototype.getEmptyRowTarget = function () {
                return X.a.createEmptyRowTarget(this.columns);
            }, e.prototype.onResize = function (e, n, i, o, r) {
                t.prototype.updateSizeAndPositionOptions.call(this, r, o, i, n), X.a.resizeTableCellWidth(this.designTarget, this.columns, this.options.getWidth());
            }, e.prototype.getReizeableShowPoints = function () {
                return ["s", "e"];
            }, e.prototype.design = function (t, e) {
                var n = this;
                this.designTarget.hidraggable({
                    handle: this.designTarget.find(".hiprint-printElement-table-handle"),
                    axis: n.options.axis && t && t.axisEnabled ? n.options.axis : void 0,
                    onDrag: function onDrag(t, i, o) {
                        n.updateSizeAndPositionOptions(i, o), n.createLineOfPosition(e);
                    },
                    moveUnit: "pt",
                    minMove: p.a.instance.movingDistance,
                    onBeforeDrag: function onBeforeDrag(t) {
                        s.a.instance.draging = !0, n.createLineOfPosition(e);
                    },
                    onStopDrag: function onStopDrag(t) {
                        s.a.instance.draging = !1, n.removeLineOfPosition();
                    }
                }), this.setHiReizeable(), this.designTarget.hireizeable({
                    showPoints: n.getReizeableShowPoints(),
                    noContainer: !0,
                    onBeforeResize: function onBeforeResize() {
                        s.a.instance.draging = !0;
                    },
                    onResize: function onResize(t, i, o, r, a) {
                        n.onResize(t, i, o, r, a), n.hitable.updateColumnGrips(), n.createLineOfPosition(e);
                    },
                    onStopResize: function onStopResize() {
                        s.a.instance.draging = !1, n.removeLineOfPosition();
                    }
                }), this.bingKeyboardMoveEvent(this.designTarget, e);
            }, e.prototype.setHiReizeable = function () {
                var t = this;
                this.hitable = new U.a({
                    table: this.designTarget.find("table"),
                    rows: this.columns,
                    resizeRow: !1,
                    resizeColumn: !0,
                    trs: $(this.designTarget).find("tbody tr"),
                    handle: this.designTarget.find("table thead"),
                    columnDisplayEditable: !0,
                    columnDisplayIndexEditable: !0,
                    columnResizable: !0,
                    columnAlignEditable: !0,
                    isEnableEdit: !0,
                    isEnableEditText: !0,
                    isEnableEditField: !0,
                    isEnableContextMenu: !0,
                    isEnableInsertRow: !0,
                    isEnableDeleteRow: !0,
                    isEnableInsertColumn: !0,
                    isEnableDeleteColumn: !0,
                    isEnableMergeCell: !0
                }), o.a.event.on("updateTable" + this.hitable.id, function () {
                    t.updateDesignViewFromOptions();
                });
            }, e;
        }(f.a),
        Q = function () {
            var _t16 = function t(e, n) {
                return (_t16 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t16(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        Z = function (t) {
            function e(e) {
                return t.call(this, e) || this;
            }

            return Q(e, t), e.prototype.createPrintElement = function (t) {
                return new J(this, t);
            }, e;
        }(h),
        tt = function () {
            var _t17 = function t(e, n) {
                return (_t17 = Object.setPrototypeOf || _instanceof({
                    __proto__: []
                }, Array) && function (t, e) {
                    t.__proto__ = e;
                } || function (t, e) {
                    for (var n in e) {
                        e.hasOwnProperty(n) && (t[n] = e[n]);
                    }
                })(e, n);
            };

            return function (e, n) {
                function i() {
                    this.constructor = e;
                }

                _t17(e, n), e.prototype = null === n ? Object.create(n) : (i.prototype = n.prototype, new i());
            };
        }(),
        et = function (t) {
            function e(e) {
                return t.call(this, e) || this;
            }

            return tt(e, t), e.prototype.createPrintElement = function (t) {
                var e = {};
                return $.extend(e, t || {}), W.createPrintElement(this, e);
            }, e.prototype.getPrintElementTypeEntity = function () {
                return new c({
                    title: this.title,
                    type: this.type
                });
            }, e;
        }(j),
        nt = function () {
            function t() { }

            return t.createPrintElementType = function (t) {
                return t.type = t.type || "text", "text" == t.type ? new et(t) : "table" == t.type ? new h(t) : "tableCustom" == t.type ? new Z(t) : new j(t);
            }, t;
        }(),
        it = function () {
            function t() { }

            return t.getElementTypeGroups = function (e) {
                var n = t.formatterModule(e);
                return a.instance[n] || [];
            }, t.getElementType = function (t, e) {
                if (t) return a.instance.getElementType(t);
                nt.createPrintElementType({
                    type: e
                });
            }, t.build = function (e, n) {
                var i = t.formatterModule(n),
                    o = new l().createPrintElementTypeHtml(e, this.getElementTypeGroups(i));
                this.enableDrag(o);
            }, t.buildByHtml = function (t) {
                this.enableDrag(t);
            }, t.enableDrag = function (e) {
                e.hidraggable({
                    revert: !0,
                    proxy: function proxy(t) {
                        var e = s.a.instance.getDragingPrintElement(),
                            n = e.printElement.getProxyTarget(e.printElement.printElementType.getOptions());
                        return n.appendTo("body"), n.css("z-index", "9999"), n;
                    },
                    moveUnit: "pt",
                    minMove: 4,
                    onBeforeDrag: function onBeforeDrag(e) {
                        s.a.instance.draging = !0;
                        var n = t.getElementType($(e.data.target).attr("tid"), $(e.data.target).attr("ptype"));
                        return s.a.instance.setDragingPrintElement(n.createPrintElement()), !0;
                    },
                    onDrag: function onDrag(t, e, n) {
                        s.a.instance.getDragingPrintElement().updatePosition(e, n);
                    },
                    onStopDrag: function onStopDrag(t) {
                        s.a.instance.draging = !1;
                    }
                });
            }, t.formatterModule = function (t) {
                return t || "_default";
            }, t;
        }(),
        ot = function () {
            return function (t, e) {
                var n = this;
                this.name = t, this.printElementTypes = [], e.forEach(function (t) {
                    n.printElementTypes.push(nt.createPrintElementType(t));
                });
            };
        }(),
        rt = function () {
            return function (t) {
                if (this.index = t.index, this.paperType = t.paperType, this.paperType) {
                    var e = s.a.instance[this.paperType];
                    t.height ? (this.height = t.height, this.width = t.width) : (this.height = e.height, this.width = e.width);
                } else this.height = t.height, this.width = t.width;

                this.paperHeader = t.paperHeader || 0, this.paperFooter = t.paperFooter || o.a.mm.toPt(this.height), this.printElements = t.printElements || [], this.paperNumberLeft = t.paperNumberLeft, this.paperNumberTop = t.paperNumberTop, this.paperNumberDisabled = t.paperNumberDisabled, this.paperNumberFormat = t.paperNumberFormat, this.panelPaperRule = t.panelPaperRule, this.rotate = t.rotate || void 0, this.firstPaperFooter = t.firstPaperFooter, this.evenPaperFooter = t.evenPaperFooter, this.oddPaperFooter = t.oddPaperFooter, this.lastPaperFooter = t.lastPaperFooter, this.topOffset = t.topOffset, this.fontFamily = t.fontFamily, this.leftOffset = t.leftOffset, this.orient = t.orient;
            };
        }(),
        at = function () {
            function t(t, e, n, i) {
                this.startX = this.minX = t, this.startY = this.minY = e, this.maxX = t, this.maxY = e, this.lastLeft = n, this.lastTop = i;
            }

            return t.prototype.updateRect = function (t, e) {
                this.minX = this.startX < t ? this.startX : t, this.minY = this.startY < e ? this.startY : e, this.maxX = this.startX < t ? t : this.startX, this.maxY = this.startY < e ? e : this.startY;
            }, t.prototype.updatePositionByMultipleSelect = function (t, e) {
                null != t && (this.lastLeft = this.lastLeft + t), null != e && (this.lastTop = this.lastTop + e), this.target.css({
                    left: this.lastLeft + "pt",
                    top: this.lastTop + "pt"
                });
            }, t;
        }(),
        pt = function () {
            function t(t, e) {
                this.templateId = e, this.index = t.index, this.width = t.width, this.height = t.height, this.paperType = t.paperType, this.paperHeader = t.paperHeader, this.paperFooter = t.paperFooter, this.initPrintElements(t.printElements), this.paperNumberLeft = t.paperNumberLeft, this.paperNumberTop = t.paperNumberTop, this.paperNumberDisabled = t.paperNumberDisabled, this.paperNumberFormat = t.paperNumberFormat, this.panelPaperRule = t.panelPaperRule, this.firstPaperFooter = t.firstPaperFooter, this.evenPaperFooter = t.evenPaperFooter, this.oddPaperFooter = t.oddPaperFooter, this.lastPaperFooter = t.lastPaperFooter, this.topOffset = t.topOffset, this.leftOffset = t.leftOffset, this.fontFamily = t.fontFamily, this.orient = t.orient, this.target = this.createTarget(), this.rotate = t.rotate;
            }

            return t.prototype.design = function (t) {
                var e = this;
                this.orderPrintElements(), this.designPaper = this.createNewPage(0), this.target.html(""), this.target.append(this.designPaper.getTarget()), this.droppablePaper(this.designPaper), this.designPaper.design(t), this.designPaper.subscribePaperBaseInfoChanged(function (t) {
                    e.paperHeader = t.paperHeader, e.paperFooter = t.paperFooter, e.paperNumberLeft = t.paperNumberLeft, e.paperNumberTop = t.paperNumberTop, e.paperNumberDisabled = t.paperNumberDisabled, e.paperNumberFormat = t.paperNumberFormat;
                }), this.printElements.forEach(function (n) {
                    e.appendDesignPrintElement(e.designPaper, n), n.design(t, e.designPaper);
                }), this.target.bind("click.hiprint", function (t) {
                    o.a.event.trigger("BuildCustomOptionSettingEventKey_" + e.templateId, {
                        options: {
                            panelPaperRule: e.panelPaperRule,
                            firstPaperFooter: e.firstPaperFooter,
                            evenPaperFooter: e.evenPaperFooter,
                            oddPaperFooter: e.oddPaperFooter,
                            lastPaperFooter: e.lastPaperFooter,
                            leftOffset: e.leftOffset,
                            topOffset: e.topOffset,
                            fontFamily: e.fontFamily,
                            orient: e.orient,
                            paperNumberFormat: e.paperNumberFormat
                        },
                        callback: function callback(t) {
                            e.panelPaperRule = t.panelPaperRule, e.firstPaperFooter = t.firstPaperFooter, e.evenPaperFooter = t.evenPaperFooter, e.oddPaperFooter = t.oddPaperFooter, e.lastPaperFooter = t.lastPaperFooter, e.leftOffset = t.leftOffset, e.topOffset = t.topOffset, e.fontFamily = t.fontFamily, e.orient = t.orient, e.paperNumberFormat = t.paperNumberFormat, e.designPaper.setOffset(e.leftOffset, e.topOffset), e.css(e.target);
                        }
                    });
                }), this.bindBatchMoveElement();
            }, t.prototype.css = function (t) {
                this.fontFamily && t.css("fontFamily", this.fontFamily);
            }, t.prototype.getHtml = function (t, e, n, i, o) {
                var r = this;
                this.orderPrintElements();
                var a,
                    p = n || [],
                    s = i || this,
                    l = void 0;

                if (i ? (l = p[p.length - 1], a = l.getPanelTarget(), l.updateReferenceElement(new E.a({
                    top: this.paperHeader,
                    left: 0,
                    height: 0,
                    width: 0,
                    bottomInLastPaper: l.referenceElement.bottomInLastPaper,
                    beginPrintPaperIndex: p.length - 1,
                    printTopInPaper: l.referenceElement.bottomInLastPaper,
                    endPrintPaperIndex: p.length - 1
                }))) : (a = s.createTarget(), l = s.createNewPage(p.length), p.push(l), a.append(l.getTarget())), this.printElements.filter(function (t) {
                    return !t.isFixed() && !t.isHeaderOrFooter();
                }).forEach(function (e) {
                    var n = [],
                        i = p[p.length - 1];
                    i.referenceElement.isPositionLeftOrRight(e.options.getTop()) ? (l = p[i.referenceElement.beginPrintPaperIndex], n = e.getHtml(l, t)) : (l = p[i.referenceElement.endPrintPaperIndex], n = e.getHtml(l, t)), n.forEach(function (t, i) {
                        t.referenceElement && (t.referenceElement.endPrintPaperIndex = t.referenceElement.beginPrintPaperIndex + n.length - 1), i > 0 && (l.index < p.length - 1 ? l = p[l.index + 1] : (l = s.createNewPage(p.length, l.referenceElement), p.push(l)), a.append(l.getTarget())), t.target && (l.append(t.target), l.updatePrintLine(t.printLine), e.onRendered(l, t.target)), i == n.length - 1 && t.referenceElement && l.updateReferenceElement(t.referenceElement);
                    });
                }), o && o.templates.forEach(function (t, e) {
                    var i = t.data || {},
                        o = t.options || {};
                    t.template.printPanels.forEach(function (t) {
                        t.getHtml(i, o, n, r);
                    });
                }), !i) {
                    if (this.lastPaperFooter) p[p.length - 1].printLine > this.lastPaperFooter && (l = s.createNewPage(p.length, l.referenceElement), p.push(l), a.append(l.getTarget()));
                    this.panelPaperRule && ("odd" == this.panelPaperRule && p.length % 2 == 0 && (l = s.createNewPage(p.length, l.referenceElement), p.push(l), a.append(l.getTarget())), "even" == this.panelPaperRule && p.length % 2 == 1 && (l = s.createNewPage(p.length, l.referenceElement), p.push(l), a.append(l.getTarget()))), p.forEach(function (n) {
                        n.updatePaperNumber(n.index + 1, p.length, e.paperNumberToggleInEven), r.fillPaperHeaderAndFooter(n, t, p.length), e && (null != e.leftOffset && n.setLeftOffset(e.leftOffset), null != e.topOffset && n.setTopOffset(e.topOffset));
                    }), a.prepend(this.getPrintStyle());
                }

                return a;
            }, t.prototype.resize = function (t, e, n, i) {
                this.width = e, this.height = n, this.paperType = t, this.rotate = i, this.designPaper.resize(e, n);
            }, t.prototype.rotatePaper = function () {
                null == this.rotate && (this.rotate = !1), this.rotate = !this.rotate, this.resize(this.paperType, this.height, this.width, this.rotate);
            }, t.prototype.getTarget = function () {
                return this.target;
            }, t.prototype.enable = function () {
                this.target.removeClass("hipanel-disable");
            }, t.prototype.disable = function () {
                this.target.addClass("hipanel-disable");
            }, t.prototype.getPanelEntity = function (t) {
                var e = [];
                return this.printElements.forEach(function (n) {
                    e.push(n.getPrintElementEntity(t));
                }), new rt({
                    index: this.index,
                    width: this.width,
                    height: this.height,
                    paperType: this.paperType,
                    paperHeader: this.paperHeader,
                    paperFooter: this.paperFooter,
                    paperNumberDisabled: !!this.paperNumberDisabled || void 0,
                    paperNumberFormat: this.paperNumberFormat ? this.paperNumberFormat : void 0,
                    panelPaperRule: this.panelPaperRule ? this.panelPaperRule : void 0,
                    paperNumberLeft: this.paperNumberLeft,
                    paperNumberTop: this.paperNumberTop,
                    printElements: e,
                    rotate: this.rotate,
                    firstPaperFooter: this.firstPaperFooter,
                    evenPaperFooter: this.evenPaperFooter,
                    oddPaperFooter: this.oddPaperFooter,
                    lastPaperFooter: this.lastPaperFooter,
                    topOffset: this.topOffset,
                    fontFamily: this.fontFamily,
                    orient: this.orient,
                    leftOffset: this.leftOffset
                });
            }, t.prototype.createTarget = function () {
                var t = $('<div class="hiprint-printPanel panel-index-' + this.index + '"></div>');
                return this.css(t), t;
            }, t.prototype.droppablePaper = function (t) {
                var e = this;
                t.getTarget().hidroppable({
                    accept: ".ep-draggable-item",
                    onDrop: function onDrop(n, i) {
                        var r = s.a.instance.getDragingPrintElement(),
                            a = r.printElement;
                        a.updateSizeAndPositionOptions(e.mathroundToporleft(r.left - o.a.px.toPt(e.target.offset().left)), e.mathroundToporleft(r.top - o.a.px.toPt(e.target.offset().top))), a.setTemplateId(e.templateId), a.setPanel(e), e.appendDesignPrintElement(e.designPaper, a, !0), e.printElements.push(a), a.design(void 0, t);
                    }
                });
            }, t.prototype.initPrintElements = function (t) {
                var e = this;
                this.printElements = [], t && t.forEach(function (n) {
                    var i;

                    if (i = n.printElementType ? nt.createPrintElementType(n.printElementType) : a.instance.getElementType(n.tid)) {
                        var o = i.createPrintElement(n.options);
                        o.setTemplateId(e.templateId), o.setPanel(e), e.printElements.push(o);
                    } else console.log("miss " + JSON.stringify(t));
                });
            }, t.prototype.mathroundToporleft = function (t) {
                var e = p.a.instance.movingDistance;
                return Math.round(t / e) * e;
            }, t.prototype.appendDesignPrintElement = function (t, e, n) {
                e.setCurrenttemplateData(void 0);
                var i = e.getDesignTarget(t);
                i.addClass("design"), n && e.initSizeByHtml(i), t.append(i);
            }, t.prototype.createNewPage = function (t, e) {
                var n = new T(this.templateId, this.width, this.height, this.paperHeader, this.paperFooter, this.paperNumberLeft, this.paperNumberTop, this.paperNumberDisabled, this.paperNumberFormat, t, e);
                return n.setFooter(this.firstPaperFooter, this.evenPaperFooter, this.oddPaperFooter, this.lastPaperFooter), n.setOffset(this.leftOffset, this.topOffset), n;
            }, t.prototype.orderPrintElements = function () {
                this.printElements = o.a.orderBy(this.printElements, function (t) {
                    return t.options.getLeft();
                }), this.printElements = o.a.orderBy(this.printElements, function (t) {
                    return t.options.getTop();
                });
            }, t.prototype.fillPaperHeaderAndFooter = function (t, e, n) {
                this.printElements.filter(function (t) {
                    return t.isFixed() || t.isHeaderOrFooter();
                }).forEach(function (i) {
                    if (i.isFixed(), i.showInPage(t.index, n)) {
                        var o = i.getHtml(t, e);
                        o.length && t.append(o[0].target);
                    }
                });
            }, t.prototype.clear = function () {
                this.printElements.forEach(function (t) {
                    t.designTarget && t.designTarget.length && t.designTarget.remove();
                }), this.printElements = [];
            }, t.prototype.insertPrintElementToPanel = function (t) {
                var e = this.getPrintElementTypeByEntity(t);

                if (e) {
                    var n = e.createPrintElement(t.options);
                    n.setTemplateId(this.templateId), n.setPanel(this), this.printElements.push(n);
                }
            }, t.prototype.addPrintText = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "text", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintHtml = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "html", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintTable = function (t) {
                if (t.printElementType = t.printElementType || {}, t.printElementType.type = "table", t.options && t.options.columns) {
                    var e = $.extend({}, t.options.columns);
                    t.printElementType.columns = e.columns, e.columns = void 0;
                }

                this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintImage = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "image", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintLongText = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "longText", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintVline = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "vline", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintHline = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "hline", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintRect = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "rect", this.insertPrintElementToPanel(t);
            }, t.prototype.addPrintOval = function (t) {
                t.printElementType = t.printElementType || {}, t.printElementType.type = "oval", this.insertPrintElementToPanel(t);
            }, t.prototype.getPrintElementTypeByEntity = function (t) {
                var e;
                return (e = t.tid ? a.instance.getElementType(t.tid) : nt.createPrintElementType(t.printElementType)) || console.log("miss " + JSON.stringify(t)), e;
            }, t.prototype.getPrintStyle = function () {
                return " <style printStyle>\n        @page\n        {\n             border:0;\n             padding:0cm;\n             margin:0cm;\n             " + this.getPrintSizeStyle() + "\n        }\n        </style>\n        ";
            }, t.prototype.getPrintSizeStyle = function () {
                return this.paperType ? "size:" + this.paperType + " " + (this.height > this.width ? "portrait" : "landscape") + ";" : "size:" + this.width + "mm " + this.height + "mm " + (this.orient ? 1 == this.orient ? "portrait" : "landscape" : "") + ";";
            }, t.prototype.deletePrintElement = function (t) {
                var e = this;
                this.printElements.filter(function (n, i) {
                    n.id == t.id && (t.delete(), e.printElements.splice(i, 1));
                });
            }, t.prototype.getElementByTid = function (t) {
                return this.printElements.filter(function (e) {
                    return e.printElementType.tid === t;
                }).map(function (t, e) {
                    return t;
                });
            }, t.prototype.getElementByName = function (t) {
                return this.printElements.filter(function (e) {
                    return e.options.name === t;
                }).map(function (t, e) {
                    return t;
                });
            }, t.prototype.getFieldsInPanel = function () {
                var t = [];
                return this.printElements.forEach(function (e) {
                    e.options && e.options.field ? t.push(e.options.field) : e.printElementType.field && t.push(e.printElementType.field);
                }), t;
            }, t.prototype.bindBatchMoveElement = function () {
                var t = this;
                this.designPaper.getTarget().on("mousemove", function (e) {
                    s.a.instance.draging || 1 === e.buttons && (t.mouseRect.updateRect(e.pageX, e.pageY), t.updateRectPanel(t.mouseRect));
                }).on("mousedown", function (e) {
                    s.a.instance.draging || (t.mouseRect && t.mouseRect.target && t.mouseRect.target.remove(), 1 === e.buttons && (t.mouseRect = new at(e.pageX, e.pageY, s.a.instance.dragLengthCNum(e.pageX - t.designPaper.getTarget().offset().left, p.a.instance.movingDistance), s.a.instance.dragLengthCNum(e.pageY - t.designPaper.getTarget().offset().top, p.a.instance.movingDistance))));
                });
            }, t.prototype.getElementInRect = function (t) {
                var e = [];
                return this.printElements.forEach(function (n) {
                    n.inRect(t) && e.push(n);
                }), e;
            }, t.prototype.updateRectPanel = function (t) {
                var e = this,
                    n = this.designPaper.getTarget();
                this.mouseRect.target || (this.mouseRect.target = $('<div tabindex="1" style="z-index:2;position: absolute;opacity:0.2;border: 1px dashed #000;background-color:#31676f;"><span></span></div>'), n.find(".hiprint-printPaper-content").append(this.mouseRect.target), this.mouseRect.target.focus(), this.bingKeyboardMoveEvent(this.mouseRect.target), this.mouseRect.target.hidraggable({
                    onDrag: function onDrag(t, n, i) {
                        e.mouseRect.lastLeft = e.mouseRect.lastLeft ? e.mouseRect.lastLeft : n, e.mouseRect.lastTop = e.mouseRect.lastTop ? e.mouseRect.lastTop : i, (e.mouseRect.mouseRectSelectedElement || []).forEach(function (t) {
                            t.updatePositionByMultipleSelect(n - e.mouseRect.lastLeft, i - e.mouseRect.lastTop);
                        }), e.mouseRect.lastLeft = n, e.mouseRect.lastTop = i;
                    },
                    moveUnit: "pt",
                    minMove: p.a.instance.movingDistance,
                    onBeforeDrag: function onBeforeDrag(t) {
                        e.mouseRect.target.focus(), s.a.instance.draging = !0, e.mouseRect.mouseRectSelectedElement || (e.mouseRect.mouseRectSelectedElement = e.getElementInRect(e.mouseRect));
                    },
                    onStopDrag: function onStopDrag(t) {
                        s.a.instance.draging = !1;
                    }
                })), this.mouseRect.target.css({
                    height: t.maxY - t.minY + "px",
                    width: t.maxX - t.minX + "px",
                    left: t.lastLeft + "pt",
                    top: t.lastTop + "pt"
                });
            }, t.prototype.bingKeyboardMoveEvent = function (t) {
                var e = this;
                t.attr("tabindex", "1"), t.keydown(function (t) {
                    e.mouseRect.mouseRectSelectedElement || (e.mouseRect.mouseRectSelectedElement = e.getElementInRect(e.mouseRect));
                    var n = e.mouseRect.mouseRectSelectedElement || [];

                    switch (t.keyCode) {
                        case 37:
                            e.mouseRect.updatePositionByMultipleSelect(0 - p.a.instance.movingDistance, 0), n.forEach(function (t) {
                                t.updatePositionByMultipleSelect(0 - p.a.instance.movingDistance, 0);
                            }), t.preventDefault();
                            break;

                        case 38:
                            e.mouseRect.updatePositionByMultipleSelect(0, 0 - p.a.instance.movingDistance), n.forEach(function (t) {
                                t.updatePositionByMultipleSelect(0, 0 - p.a.instance.movingDistance);
                            }), t.preventDefault();
                            break;

                        case 39:
                            e.mouseRect.updatePositionByMultipleSelect(p.a.instance.movingDistance, 0), n.forEach(function (t) {
                                t.updatePositionByMultipleSelect(p.a.instance.movingDistance, 0);
                            }), t.preventDefault();
                            break;

                        case 40:
                            e.mouseRect.updatePositionByMultipleSelect(0, p.a.instance.movingDistance), n.forEach(function (t) {
                                t.updatePositionByMultipleSelect(0, p.a.instance.movingDistance);
                            }), t.preventDefault();
                    }
                });
            }, t;
        }(),
        st = function () {
            return function (t) {
                if (t) if (t.panels) {
                    this.panels = [];

                    for (var e = 0; e < t.panels.length; e++) {
                        this.panels.push(new rt(t.panels[e]));
                    }
                } else this.panels = [];
            };
        }(),
        lt = n(9),
        ut = function () {
            function t(t, e) {
                var n = this;
                this.printElementOptionSettingPanel = {}, this.printTemplate = t, this.settingContainer = $(e), o.a.event.on(t.getPrintElementSelectEventKey(), function (t) {
                    n.buildSetting(t);
                }), o.a.event.on(t.getBuildCustomOptionSettingEventKey(), function (t) {
                    n.buildSettingByCustomOptions(t);
                });
            }

            return t.prototype.init = function () { }, t.prototype.buildSetting = function (t) {
                var e = this,
                    n = this,
                    i = t.printElement,
                    o = t.customOptionsInput;
                this.lastPrintElement && this.lastPrintElement.getPrintElementOptionItems().forEach(function (t) {
                    t.destroy();
                });
                this.lastPrintElement = void 0, this.settingContainer.html("");
                var r = $('<div class="hiprint-option-items"></div>');
                i.getPrintElementOptionItems().forEach(function (t) {
                    t.submit = function (t) {
                        i.submitOption();
                    };

                    var n = t.createTarget(i, i.options, i.printElementType);
                    e.printElementOptionSettingPanel[t.name] = n, r.append(n), t.setValue(i.options[t.name], i.options, i.printElementType);
                });
                var a = $('<button class="hiprint-option-item-settingBtn hiprint-option-item-submitBtn"\n        type="button">确定</button>'),
                    p = $('<button  class="hiprint-option-item-settingBtn hiprint-option-item-deleteBtn"\n        type="button">删除</button>');
                r.append(a), r.append(p), a.bind("click.submitOption", function () {
                    i.submitOption();
                }), p.bind("click.deleteBtn", function () {
                    n.printTemplate.deletePrintElement(i);
                }), r.find(".auto-submit").change(function (t) {
                    i.submitOption();
                }), r.find(".auto-submit:input").bind("keydown.submitOption", function (t) {
                    13 == t.keyCode && i.submitOption();
                }), this.settingContainer.append(r), o && o.forEach(function (t) {
                    var n = t.callback;
                    t.callback = function (t) {
                        n && (n(t), i.submitOption());
                    }, e.buildSettingByCustomOptions(t, e.settingContainer);
                }), this.lastPrintElement = i;
            }, t.prototype.buildSettingByCustomOptions = function (t, e) {
                var n = this;
                this.lastPrintElement && this.lastPrintElement.getPrintElementOptionItems().forEach(function (t) {
                    t.destroy();
                });
                this.lastPrintElement = void 0;
                var i = e || this.settingContainer;
                e || this.settingContainer.html("");
                var o = [];
                t.optionItems ? o = t.optionItems : Object.keys(t.options).forEach(function (t) {
                    var e = lt.a.getItem(t);
                    e && o.push(e);
                });
                var r = $('<div class="hiprint-option-items"></div>');
                t.title && r.append('<div class="hiprint-option-item hiprint-option-item-row">\n            <div class="hiprint-option-item-label hiprint-option-title">\n              ' + t.title + "\n            </div>\n        </div>"), o.forEach(function (e) {
                    e.submit = function (e) {
                        t.callback(n.getValueByOptionItems(o));
                    }, r.append(e.createTarget(void 0, t.options, void 0)), e.setValue(t.options[e.name], t.options, void 0);
                });
                var a = $('<button class="hiprint-option-item-settingBtn hiprint-option-item-submitBtn"\n        type="button">确定</button>');
                r.append(a), a.bind("click.submitOption", function () {
                    t.callback(n.getValueByOptionItems(o));
                }), r.find(".auto-submit").change(function (e) {
                    t.callback(n.getValueByOptionItems(o));
                }), r.find(".auto-submit:input").bind("keydown.submitOption", function (e) {
                    13 == e.keyCode && t.callback(n.getValueByOptionItems(o));
                }), i.append(r);
            }, t.prototype.getValueByOptionItems = function (t) {
                var e = {};
                return t.forEach(function (t) {
                    e[t.name] = t.getValue();
                }), e;
            }, t;
        }(),
        dt = function () {
            function t(t, e) {
                this.paginationContainer = t, this.jqPaginationContainer = $(this.paginationContainer), this.template = e;
            }

            return t.prototype.buildPagination = function (t) {
                var e = this.template.getPaneltotal(),
                    n = this;
                this.jqPaginationContainer.html("");

                for (var i = $('<ul class="hiprint-pagination"></ul>'), o = function o() {
                    var t = r,
                        e = $("<li><span>" + (t + 1) + '</span><a href="javascript:void(0);">x</a></li>');
                    e.find("span").click(function () {
                        n.template.selectPanel(t), e.removeClass("selected"), $(this).parent("li").addClass("selected");
                    }), e.find("a").click(function () {
                        n.template.deletePanel(t), n.buildPagination();
                    }), i.append(e);
                }, r = 0; r < e; r++) {
                    o();
                }

                var a = $("<li><span>+</span></li>");
                i.append(a), this.jqPaginationContainer.append(i), a.click(function () {
                    n.template.addPrintPanel(void 0, !0), n.buildPagination();
                });
            }, t;
        }(),
        ct = function () {
            function t(t) {
                var e = this;
                this.tempimageBase64 = {}, this.id = s.a.instance.guid(), s.a.instance.setPrintTemplateById(this.id, this);
                var n = t || {};
                this.printPanels = [];
                var i = new st(n.template || []);
                n.template && i.panels.forEach(function (t) {
                    e.printPanels.push(new pt(t, e.id));
                }), n.fields && (this.fields = n.fields), n.settingContainer && new ut(this, n.settingContainer), n.paginationContainer && (this.printPaginationCreator = new dt(n.paginationContainer, this), this.printPaginationCreator.buildPagination()), this.initAutoSave();
            }

            return t.prototype.design = function (t, e) {
                var n = this;

                if (e || (e = {}), 0 == this.printPanels.length) {
                    var i = this.createDefaultPanel();
                    this.printPanels.push(i);
                }

                if (!t) throw new Error("options.container can not be empty");
                this.createContainer(t), this.printPanels.forEach(function (t, i) {
                    n.container.append(t.getTarget()), i > 0 && t.disable(), t.design(e);
                }), this.selectPanel(0);
            }, t.prototype.getSimpleHtml = function (t, e) {
                var n = this;
                e || (e = {});
                var i = $('<div class="hiprint-printTemplate"></div>');
                t && t.constructor === Array ? t.forEach(function (t) {
                    t && n.printPanels.forEach(function (n, o) {
                        i.append(n.getHtml(t, e));
                    });
                }) : this.printPanels.forEach(function (n, o) {
                    i.append(n.getHtml(t, e));
                });
                return e && e.imgToBase64 && this.transformImg(i.find("img")), i;
            }, t.prototype.getHtml = function (t, e) {
                return t || (t = {}), this.getSimpleHtml(t, e);
            }, t.prototype.getJointHtml = function (t, e, n) {
                var i = $('<div class="hiprint-printTemplate"></div>'),
                    o = [];
                return this.printPanels.forEach(function (r, a) {
                    i.append(r.getHtml(t, e, o, void 0, n));
                }), i;
            }, t.prototype.setPaper = function (t, e) {
                if (/^(([1-9][0-9]*)|(([0]\.\d{1,2}|[1-9][0-9]*\.\d{1,2})))$/.test(t)) this.editingPanel.resize(void 0, parseFloat(t), parseFloat(e), !1); else {
                    var n = s.a.instance[t];
                    if (!n) throw new Error("not found pagetype:" + (t || ""));
                    this.editingPanel.resize(t, n.width, n.height, !1);
                }
            }, t.prototype.rotatePaper = function () {
                this.editingPanel.rotatePaper();
            }, t.prototype.addPrintPanel = function (t, e) {
                var n = t ? new pt(new rt(t), this.id) : this.createDefaultPanel();
                return t && (t.index = this.printPanels.length), e && (this.container.append(n.getTarget()), n.design()), this.printPanels.push(n), e && this.selectPanel(n.index), n;
            }, t.prototype.selectPanel = function (t) {
                var e = this;
                this.printPanels.forEach(function (n, i) {
                    t == i ? (n.enable(), e.editingPanel = n) : n.disable();
                });
            }, t.prototype.deletePanel = function (t) {
                this.printPanels[t].clear(), this.printPanels[t].getTarget().remove(), this.printPanels.splice(t, 1);
            }, t.prototype.getPaneltotal = function () {
                return this.printPanels.length;
            }, t.prototype.createDefaultPanel = function () {
                return new pt(new rt({
                    index: this.printPanels.length,
                    paperType: "A4"
                }), this.id);
            }, t.prototype.createContainer = function (t) {
                t ? (this.container = $(t), this.container.addClass("hiprint-printTemplate")) : this.container = $('<div class="hiprint-printTemplate"></div>');
            }, t.prototype.getJsonTid = function () {
                var t = [];
                return this.printPanels.forEach(function (e) {
                    e.getPanelEntity().printElements.length && t.push(e.getPanelEntity());
                }), new st({
                    panels: t
                });
            }, t.prototype.getJson = function () {
                var t = [];
                return this.printPanels.forEach(function (e) {
                    t.push(e.getPanelEntity(!0));
                }), new st({
                    panels: t
                });
            }, t.prototype.getPrintElementSelectEventKey = function () {
                return "PrintElementSelectEventKey_" + this.id;
            }, t.prototype.getBuildCustomOptionSettingEventKey = function () {
                return "BuildCustomOptionSettingEventKey_" + this.id;
            }, t.prototype.clear = function () {
                this.printPanels.forEach(function (t) {
                    if (t.clear(), t.index > 0) {
                        var e = t.getTarget();
                        e && e.length && e.remove();
                    }
                }), this.printPanels = [this.printPanels[0]], this.printPaginationCreator && this.printPaginationCreator.buildPagination();
            }, t.prototype.getPaperType = function (t) {
                return null == t && (t = 0), this.printPanels[0].paperType;
            }, t.prototype.getOrient = function (t) {
                return null == t && (t = 0), this.printPanels[t].height > this.printPanels[t].width ? 1 : 2;
            }, t.prototype.getPrintStyle = function (t) {
                return this.printPanels[t].getPrintStyle();
            }, t.prototype.print = function (t, e) {
                t || (t = {}), this.getHtml(t, e).hiwprint();
            }, t.prototype.print2 = function (t, e) {
                if (t || (t = {}), e || (e = {}), this.clientIsOpened()) {
                    var n = this,
                        i = 0,
                        o = {},
                        r = $("link[media=print]").length > 0 ? $("link[media=print]") : $("link");
                    r.each(function (a, p) {
                        var s = new XMLHttpRequest();
                        s.open("GET", $(p).attr("href")), s.onreadystatechange = function () {
                            if (4 === s.readyState && 200 === s.status && (o[a + ""] = '<style rel="stylesheet" type="text/css">' + s.responseText + "</style>", ++i == r.length)) {
                                for (var p = "", l = 0; l < r.length; l++) {
                                    p += o[l + ""];
                                }

                                n.sentToClient(p, t, e);
                            }
                        }, s.send();
                    });
                } else alert("连接客户端失败");
            }, t.prototype.imageToBase64 = function (t) {
                var e = $(t).attr("src");
                if (-1 == e.indexOf("base64")) try {
                    if (!this.tempimageBase64[e]) {
                        var n = document.createElement("canvas"),
                            i = new Image();
                        i.src = t.attr("src"), n.width = i.width, n.height = i.height, n.getContext("2d").drawImage(i, 0, 0), e && (this.tempimageBase64[e] = n.toDataURL("image/png"));
                    }

                    t.attr("src", this.tempimageBase64[e]);
                } catch (e) {
                    try {
                        this.xhrLoadImage(t);
                    } catch (t) {
                        console.log(t);
                    }
                }
            }, t.prototype.xhrLoadImage = function (t) { }, t.prototype.sentToClient = function (t, e, n) {
                e || (e = {});
                var i = $.extend({}, n || {});
                i.imgToBase64 = !0;
                var o = t + this.getHtml(e, i)[0].outerHTML;
                i.id = s.a.instance.guid(), i.html = o, i.templateId = this.id, hiwebSocket.send(i);
            }, t.prototype.printByHtml = function (t) {
                $(t).hiwprint();
            }, t.prototype.printByHtml2 = function (t, e) {
                if (e || (e = {}), this.clientIsOpened()) {
                    var n = this,
                        i = 0,
                        o = {},
                        r = $("link[media=print]").length > 0 ? $("link[media=print]") : $("link");
                    r.each(function (a, p) {
                        var l = new XMLHttpRequest();
                        l.open("GET", $(p).attr("href")), l.onreadystatechange = function () {
                            if (4 === l.readyState && 200 === l.status && (o[a + ""] = '<style rel="stylesheet" type="text/css">' + l.responseText + "</style>", ++i == r.length)) {
                                for (var p = "", u = 0; u < r.length; u++) {
                                    p += o[u + ""];
                                }

                                var d = p + $(t)[0].outerHTML,
                                    c = $.extend({}, e || {});
                                c.id = s.a.instance.guid(), c.html = d, c.templateId = n.id, hiwebSocket.send(c);
                            }
                        }, l.send();
                    });
                } else alert("连接客户端失败");
            }, t.prototype.deletePrintElement = function (t) {
                this.printPanels.forEach(function (e) {
                    e.deletePrintElement(t);
                });
            }, t.prototype.transformImg = function (t) {
                var e = this;
                t.map(function (t, n) {
                    e.imageToBase64($(n));
                });
            }, t.prototype.toPdf = function (t, e, n) {
                var i = this;

                if (this.printPanels.length) {
                    var r = o.a.mm.toPt(this.printPanels[0].width),
                        a = o.a.mm.toPt(this.printPanels[0].height),
                        p = $.extend({
                            scale: 2,
                            width: o.a.pt.toPx(r),
                            x: 0,
                            y: 0,
                            useCORS: !0
                        }, n || {}),
                        s = new jsPDF({
                            orientation: 1 == this.getOrient(0) ? "portrait" : "landscape",
                            unit: "pt",
                            format: this.printPanels[0].paperType ? this.printPanels[0].paperType.toLocaleLowerCase() : [r, a]
                        }),
                        l = this.getHtml(t, n);
                    this.createTempContainer();
                    var u = this.getTempContainer();
                    this.svg2canvas(l), u.html(l[0]);
                    var d = u.find(".hiprint-printPanel .hiprint-printPaper").length;
                    $(l).css("position:fixed"), html2canvas(l[0], p).then(function (t) {
                        var n = t.getContext("2d");
                        n.mozImageSmoothingEnabled = !1, n.webkitImageSmoothingEnabled = !1, n.msImageSmoothingEnabled = !1, n.imageSmoothingEnabled = !1;

                        for (var o = t.toDataURL("image/jpeg"), p = 0; p < d; p++) {
                            s.addImage(o, "JPEG", 0, 0 - p * a, r, d * a), p < d - 1 && s.addPage();
                        }

                        i.removeTempContainer(), e.indexOf(".pdf") > -1 ? s.save(e) : s.save(e + ".pdf");
                    });
                }
            }, t.prototype.createTempContainer = function () {
                this.removeTempContainer(), $("body").prepend($('<div class="hiprint_temp_Container" style="overflow:hidden;height: 0px;box-sizing: border-box;"></div>'));
            }, t.prototype.removeTempContainer = function () {
                $(".hiprint_temp_Container").remove();
            }, t.prototype.getTempContainer = function () {
                return $(".hiprint_temp_Container");
            }, t.prototype.svg2canvas = function (t) {
                t.find("svg").each(function (t, e) {
                    var n = e.parentNode,
                        i = document.createElement("canvas"),
                        o = new XMLSerializer().serializeToString(e);
                    canvg(i, o), $(e).before(i), n.removeChild(e), $(i).css("width", "100%"), $(i).css("height", "100%");
                });
            }, t.prototype.on = function (t, e) {
                o.a.event.on(t + "_" + this.id, e);
            }, t.prototype.clientIsOpened = function () {
                return hiwebSocket.opened;
            }, t.prototype.getPrinterList = function () {
                var t = hiwebSocket.getPrinterList();
                return t || [];
            }, t.prototype.getElementByTid = function (t, e) {
                return null == e && (e = 0), this.printPanels[e].getElementByTid(t);
            }, t.prototype.getElementByName = function (t, e) {
                return null == e && (e = 0), this.printPanels[e].getElementByName(t);
            }, t.prototype.getPanel = function (t) {
                return null == t && (t = 0), this.printPanels[t];
            }, t.prototype.loadAllImages = function (t, e, n) {
                var i = this;
                null == n && (n = 0);

                for (var o = t[0].getElementsByTagName("img"), r = !0, a = 0; a < o.length; a++) {
                    var p = o[a];
                    p.src && p.src !== window.location.href && -1 == p.src.indexOf("base64") && (p && void 0 !== p.naturalWidth && 0 !== p.naturalWidth && p.complete || (r = !1));
                }

                n++ , !r && n < 10 ? setTimeout(function () {
                    i.loadAllImages(t, e, n);
                }, 500) : e();
            }, t.prototype.setFields = function (t) {
                this.fields = t;
            }, t.prototype.getFields = function () {
                return this.fields;
            }, t.prototype.getFieldsInPanel = function () {
                var t = [];
                return this.printPanels.forEach(function (e) {
                    t = t.concat(e.getFieldsInPanel());
                }), t;
            }, t.prototype.initAutoSave = function () {
                var t = this;
                this.autoSave && o.a.event.on("hiprintTemplateDataChanged_" + this.id, function () {
                    hiLocalStorage.saveLocalData(t.autoSaveKey || "hiprintAutoSave", JSON.stringify(1 == t.autoSaveMode ? t.getJson() : t.getJsonTid()));
                });
            }, t;
        }();

    function ht(t) {
        this.getHtml(t).hiwprint();
    }

    function ft(t, e, n) {
        $.extend({}, t || {}).imgToBase64 = !0;
        var i = new ct({});
        i.on("printSuccess", e), i.on("printError", n), i.printByHtml2(this.getHtml(t), t.options);
    }

    function gt(t) {
        var e = void 0;
        return t && t.templates.forEach(function (n, i) {
            var o = $.extend({}, n.options || {});
            t.imgToBase64 && (o.imgToBase64 = !0), e ? e.append(n.template.getHtml(n.data, o).html()) : e = n.template.getHtml(n.data, o);
        }), e;
    }

    function mt(t) {
        p.a.instance.init(t), p.a.instance.providers.forEach(function (t) {
            t.addElementTypes(a.instance);
        });
    }

    n.d(e, "init", function () {
        return mt;
    }), n.d(e, "PrintElementTypeManager", function () {
        return it;
    }), n.d(e, "PrintElementTypeGroup", function () {
        return ot;
    }), n.d(e, "PrintTemplate", function () {
        return ct;
    }), n.d(e, "print", function () {
        return ht;
    }), n.d(e, "print2", function () {
        return ft;
    }), n.d(e, "getHtml", function () {
        return gt;
    }), $(document).ready(function () {
        hiwebSocket.hasIo() && hiwebSocket.start();
    });
}]);