/**
 * 标签可视化设计器（坐标排版，与小票设计器无关）
 */
(function (window, $) {
    'use strict';

    function uid() {
        return 'e' + Date.now().toString(36) + Math.random().toString(36).slice(2, 7);
    }

    function clone(obj) {
        return JSON.parse(JSON.stringify(obj || {}));
    }

    function LabelPrintDesigner(root) {
        this.$root = $(root);
        this.$box = this.$root.closest('.label-print-designer-box');
        this.$input = this.$box.find('textarea').first();
        this.lang = this.$root.data('lang') || {};
        this.typeData = this.$root.data('type-data') || {};
        this.sampleData = this.$root.data('sample-data') || {};
        this.paperUrl = this.$root.data('paper-url') || '';
        this.showRepeat = parseInt(this.$root.data('show-repeat'), 10) === 1;
        this.showRepeatItem = String(this.$root.attr('data-show-repeat-item')) !== '0';
        this.showRepeatQty = String(this.$root.attr('data-show-repeat-qty')) === '1';
        // 商品模板可覆盖为「整商品一张 / 按规格行」
        var repeatOnceText = this.$root.attr('data-repeat-once-text') || this.$root.data('repeat-once-text') || '';
        var repeatItemText = this.$root.attr('data-repeat-item-text') || this.$root.data('repeat-item-text') || '';
        this.repeatQtyText = this.$root.attr('data-repeat-qty-text') || this.$root.data('repeat-qty-text') || '';
        if (repeatOnceText) {
            this.lang.repeat_once = repeatOnceText;
        }
        if (repeatItemText) {
            this.lang.repeat_item = repeatItemText;
        }
        if (!this.repeatQtyText && this.showRepeatQty) {
            this.repeatQtyText = this.lang.repeat_qty || '按商品数量';
        }
        this.template = this.parseSaved();
        this.selected = -1;
        this.drag = null;
        this.didDrag = false;
        this.dotsPerMm = 8;
        this.viewPxPerMm = 6;
        this.viewScale = this.viewPxPerMm / this.dotsPerMm;
        this.ns = '.labelprint' + uid();
        this.bind();
        this.render();
        this.sync();
    }

    LabelPrintDesigner.prototype.parseSaved = function () {
        var raw = $.trim(this.$input.val() || '');
        var data = {};
        if (raw) {
            try {
                data = JSON.parse(raw);
            } catch (e) {
                data = {};
            }
        }
        if (!data || typeof data !== 'object') {
            data = {};
        }
        if (!data.elements) {
            data.elements = [];
        }
        data.width = parseInt(data.width, 10) || 60;
        data.height = parseInt(data.height, 10) || 40;
        data.direction = parseInt(data.direction, 10) === 0 ? 0 : 1;
        data.repeat = (data.repeat === 'item' || data.repeat === 'qty') ? data.repeat : 'once';
        return data;
    };

    LabelPrintDesigner.prototype.fieldMeta = function (field, type) {
        var groups = ['business', 'assist'];
        var i, list, j, v;
        field = field || '';
        type = type || 'text';
        for (i = 0; i < groups.length; i++) {
            list = this.typeData[groups[i]] || [];
            for (j = 0; j < list.length; j++) {
                v = list[j];
                if ((v.type || 'text') !== type) {
                    continue;
                }
                if (String(v.field || '') === String(field)) {
                    return v;
                }
            }
        }
        return null;
    };

    LabelPrintDesigner.prototype.elementTitle = function (el) {
        if (el && el.title) {
            return el.title;
        }
        var meta = this.fieldMeta(el ? el.field : '', el ? el.type : '');
        if (meta && meta.title) {
            return meta.title;
        }
        if (el && el.type === 'qr') {
            return this.lang.assist_qr || 'QR';
        }
        if (el && el.type === 'barcode') {
            return this.lang.assist_barcode || 'Barcode';
        }
        return this.lang.assist_custom_text || 'Text';
    };

    LabelPrintDesigner.prototype.mediaText = function (el) {
        var field = el.field || '';
        var sample = '';
        if (field && this.sampleData && this.sampleData[field] !== undefined && this.sampleData[field] !== null && this.sampleData[field] !== '') {
            sample = String(this.sampleData[field]);
        }
        if (!sample && el.value) {
            sample = String(el.value);
        }
        if (!sample) {
            var meta = this.fieldMeta(field, el.type);
            if (meta && meta.sample) {
                sample = String(meta.sample);
            }
        }
        if (el.type === 'barcode') {
            return sample || '12345678';
        }
        return sample || 'qr';
    };

    LabelPrintDesigner.prototype.qrModules = function (text, level) {
        var len = String(text || '').length || 1;
        var ratio = { L: 1, M: 0.8, Q: 0.62, H: 0.45 };
        var factor = ratio[String(level || 'L').toUpperCase()] || 1;
        var caps = [17, 32, 53, 78, 106, 134, 154, 192, 230, 271, 321, 367, 425, 458, 520];
        var version = caps.length;
        var i;
        for (i = 0; i < caps.length; i++) {
            if (len <= Math.floor(caps[i] * factor)) {
                version = i + 1;
                break;
            }
        }
        return 21 + (version - 1) * 4;
    };

    LabelPrintDesigner.prototype.fontMetric = function (font) {
        var map = {
            1: [8, 12],
            2: [12, 20],
            3: [16, 24],
            4: [24, 32],
            5: [32, 48],
            12: [24, 24]
        };
        return map[parseInt(font, 10)] || map[12];
    };

    LabelPrintDesigner.prototype.textBox = function (el) {
        if (!el || (el.type && el.type !== 'text')) {
            return null;
        }
        var metric = this.fontMetric(el.font || 12);
        var sw = this.clamp(parseInt(el.w, 10) || 1, 1, 10);
        var sh = this.clamp(parseInt(el.h, 10) || 1, 1, 10);
        var full = Math.max(8, metric[0] * sw);
        var half = Math.max(4, Math.floor(full / 2));
        var text = String(this.previewText(el) || '');
        var width = 0;
        var i;
        var code;
        for (i = 0; i < text.length; i++) {
            code = text.charCodeAt(i);
            width += (code <= 0x7f) ? half : full;
        }
        if (width < full) {
            width = full;
        }
        var height = Math.max(12, metric[1] * sh);
        return {
            width: width,
            height: height,
            fontSize: height,
            scaleX: (metric[0] * sw) / height
        };
    };

    /**
     * 画布占位与打印点一致：二维码边长 = 模块数 × 宽度等级（不含四周空白）；条码高度用设置值，宽度随内容和窄条/宽条变化。
     */
    LabelPrintDesigner.prototype.mediaBox = function (el) {
        if (!el || (el.type !== 'qr' && el.type !== 'barcode')) {
            return null;
        }
        if (el.type === 'qr') {
            var cell = this.clamp(el.w || 5, 1, 10);
            var side = this.qrModules(this.mediaText(el), el.e) * cell;
            return { width: side, height: side, text: 0 };
        }
        var narrow = this.clamp(el.n || 1, 1, 10);
        var wide = this.clamp(el.w || 1, 1, 10);
        var barH = this.clamp(el.h || 64, 1, 255);
        var chars = Math.max(this.mediaText(el).length, 1);
        var code = String(el.code || 'BC128').toUpperCase();
        var width;
        if (code === 'BC39') {
            width = (chars + 2) * (3 * wide + 7 * narrow);
        } else {
            width = Math.round((11 * chars + 35) * narrow * (0.65 + 0.35 * (wide / narrow)));
        }
        if (width < 8) {
            width = 8;
        }
        var textH = parseInt(el.s, 10) === 0 ? 0 : 24;
        return { width: width, height: barH + textH, text: textH };
    };

    LabelPrintDesigner.prototype.rotation = function (el) {
        var r = parseInt(el && el.r, 10) || 0;
        if (r !== 90 && r !== 180 && r !== 270) {
            return 0;
        }
        return r;
    };

    LabelPrintDesigner.prototype.footprint = function (el) {
        var box = this.mediaBox(el);
        var text = box ? null : this.textBox(el);
        var width = box ? box.width : (text ? text.width : 36);
        var height = box ? box.height : (text ? text.height : 24);
        var rot = this.rotation(el);
        if (rot === 90 || rot === 270) {
            return { width: height, height: width };
        }
        return { width: width, height: height };
    };

    LabelPrintDesigner.prototype.previewText = function (el) {
        var field = el.field || '';
        var sample = '';
        if (field && this.sampleData && this.sampleData[field] !== undefined && this.sampleData[field] !== null && this.sampleData[field] !== '') {
            sample = this.sampleData[field];
        } else {
            var meta = this.fieldMeta(field, el.type);
            if (meta && meta.sample) {
                sample = meta.sample;
            }
        }
        if ((sample === '' || sample === null) && el.value) {
            sample = el.value;
        }
        if (el.type === 'qr') {
            return this.lang.assist_qr || 'QR';
        }
        if (el.type === 'barcode') {
            return sample || '12345678';
        }
        var label = el.label || '';
        var text = label + (sample || this.elementTitle(el));
        return text;
    };

    LabelPrintDesigner.prototype.bind = function () {
        var self = this;
        this.$root.on('input', '.label-print-palette-search-input', function () {
            self.filterPalette($.trim($(this).val()));
        });
        this.$root.on('click', '.label-print-palette .item', function (e) {
            e.preventDefault();
            if (self.didDrag) {
                self.didDrag = false;
                return;
            }
            var payload = self.payloadFromItem($(this));
            var step = (self.template.elements.length % 6) * 24;
            self.addElement(payload, 16 + step, 16 + step);
        });
        this.$root.on('dragstart', '.label-print-palette .item', function (e) {
            var $item = $(this);
            self.didDrag = true;
            var payload = self.payloadFromItem($item);
            e.originalEvent.dataTransfer.setData('text/plain', JSON.stringify(payload));
            e.originalEvent.dataTransfer.effectAllowed = 'copy';
        });
        this.$root.on('dragend', '.label-print-palette .item', function () {
            setTimeout(function () {
                self.didDrag = false;
            }, 50);
        });
        this.$root.on('dragover', '.label-print-canvas', function (e) {
            e.preventDefault();
            $(this).addClass('is-over');
        });
        this.$root.on('dragleave', '.label-print-canvas', function () {
            $(this).removeClass('is-over');
        });
        this.$root.on('drop', '.label-print-canvas', function (e) {
            e.preventDefault();
            $(this).removeClass('is-over');
            var raw = e.originalEvent.dataTransfer.getData('text/plain');
            if (!raw) {
                return;
            }
            var payload;
            try {
                payload = JSON.parse(raw);
            } catch (err) {
                return;
            }
            var pos = self.pointFromEvent(e.originalEvent);
            self.addElement(payload, pos.x, pos.y);
        });
        this.$root.on('mousedown', '.label-print-el', function (e) {
            if (e.button !== 0) {
                return;
            }
            e.preventDefault();
            var index = parseInt($(this).attr('data-index'), 10);
            self.selected = index;
            var el = self.template.elements[index];
            self.drag = {
                index: index,
                sx: e.clientX,
                sy: e.clientY,
                ox: parseInt(el.x, 10) || 0,
                oy: parseInt(el.y, 10) || 0
            };
            self.renderCanvas();
            self.renderSetting();
            self.locatePaletteItem(el, true);
        });
        $(document).on('mousemove' + this.ns, function (e) {
            if (!self.drag) {
                return;
            }
            var el = self.template.elements[self.drag.index];
            if (!el) {
                return;
            }
            var nx = self.drag.ox + (e.clientX - self.drag.sx) / self.viewScale;
            var ny = self.drag.oy + (e.clientY - self.drag.sy) / self.viewScale;
            var snapped = self.snapDrag(el, self.drag.index, nx, ny);
            nx = snapped.x;
            ny = snapped.y;
            if (self.isAligned(el)) {
                nx = el.x;
            }
            self.placeElement(el, nx, ny, self.drag.index);
            if (el.align === 'left' || el.align === 'right') {
                el.x = 0;
            } else if (self.isAligned(el)) {
                var keepY = el.y;
                self.applyAlign(el);
                el.y = keepY;
            }
            self.renderCanvas();
            self.drawGuides(el, self.drag.index);
        });
        $(document).on('mouseup' + this.ns, function () {
            if (!self.drag) {
                return;
            }
            self.drag = null;
            self.$root.find('.label-print-guide').remove();
            self.sync();
            self.renderSetting();
        });
        this.$root.on('change input', '.label-print-paper-bar [data-key]', function (e) {
            var key = $(this).data('key');
            var val = $(this).val();
            if (key === 'width' || key === 'height' || key === 'direction') {
                var n = parseInt(val, 10);
                var negative = String(val).charAt(0) === '-' || n < 0;
                if (key === 'width') {
                    self.template.width = self.clamp(isNaN(n) ? 60 : n, 20, 150);
                    if (e.type === 'change' || negative || n > 150) {
                        $(this).val(self.template.width);
                    }
                } else if (key === 'height') {
                    self.template.height = self.clamp(isNaN(n) ? 40 : n, 10, 200);
                    if (e.type === 'change' || negative || n > 200) {
                        $(this).val(self.template.height);
                    }
                } else if (key === 'direction') {
                    self.template.direction = parseInt(val, 10) === 0 ? 0 : 1;
                }
            } else if (key === 'repeat') {
                self.template.repeat = (val === 'item' || val === 'qty') ? val : 'once';
            }
            self.renderCanvas();
            if (self.selected >= 0) {
                self.renderSetting();
            }
            self.sync();
        });
        this.$root.on('click', '.label-print-paper-submit', function () {
            self.sendPaper();
        });
        this.$root.on('click', '.label-print-clear-btn', function () {
            self.template.elements = [];
            self.selected = -1;
            self.$root.find('.label-print-palette .item').removeClass('is-shake');
            self.renderCanvas();
            self.renderSetting();
            self.sync();
        });
        this.$root.on('click', '.label-print-delete', function () {
            self.removeSelected();
        });
        this.$root.on('click', '.label-print-format-tabs .item', function () {
            if (self.selected < 0 || $(this).hasClass('am-active')) {
                return;
            }
            self.changeElementType(String($(this).data('type') || 'text'));
        });
        this.$root.on('change input', '.label-print-setting [data-prop]', function () {
            self.updateProp($(this));
        });
    };

    LabelPrintDesigner.prototype.filterPalette = function (keyword) {
        var $items = this.$root.find('.label-print-palette .item');
        var hit = 0;
        keyword = (keyword || '').toLowerCase();
        $items.each(function () {
            var $item = $(this);
            var text = (String($item.attr('data-title') || '') + ' ' + String($item.attr('data-field') || '') + ' ' + String($item.attr('data-label') || '')).toLowerCase();
            var show = !keyword || text.indexOf(keyword) !== -1;
            $item.toggle(show);
            if (show) {
                hit++;
            }
        });
        this.$root.find('.label-print-palette-search-empty').toggleClass('am-hide', hit > 0 || !keyword);
    };

    LabelPrintDesigner.prototype.locatePaletteItem = function (el, shake) {
        var $palette = this.$root.find('.label-print-palette');
        if (!$palette.length || !el) {
            return;
        }
        $palette.find('.item').removeClass('is-shake');
        var type = el.type || 'text';
        var field = String(el.field || '');
        var $items = $palette.find('.item').filter(function () {
            var $it = $(this);
            if (field) {
                return String($it.attr('data-field') || '') === field;
            }
            return String($it.attr('data-field') || '') === '' && String($it.attr('data-type') || 'text') === type;
        });
        if ($items.length > 1 && !field && type === 'text') {
            var $custom = $items.filter(function () {
                return parseInt($(this).attr('data-custom'), 10) === 1;
            });
            if ($custom.length) {
                $items = $custom;
            }
        }
        var $target = $items.first();
        if (!$target.length) {
            return;
        }
        if (!$target.is(':visible')) {
            var $search = $palette.find('.label-print-palette-search-input');
            if ($search.length) {
                $search.val('');
                this.filterPalette('');
            }
        }
        var $scroll = $palette.find('.label-print-palette-list');
        var paletteEl = ($scroll.length ? $scroll[0] : $palette[0]);
        var afterScroll = function () {
            if (!shake || !$target.length) {
                return;
            }
            $target.removeClass('is-shake');
            $target[0].offsetWidth;
            $target.addClass('is-shake');
            clearTimeout(this._paletteShakeTimer);
            this._paletteShakeTimer = setTimeout(function () {
                $target.removeClass('is-shake');
            }, 700);
        }.bind(this);
        if (paletteEl) {
            var itemTop = $target.offset().top - $(paletteEl).offset().top + (paletteEl.scrollTop || 0);
            var itemH = $target.outerHeight() || 0;
            var viewH = paletteEl.clientHeight || 0;
            var nextTop = Math.max(0, itemTop - (viewH / 2) + (itemH / 2));
            this.animatePaletteScroll(paletteEl, nextTop, afterScroll);
        } else {
            afterScroll();
        }
    };

    LabelPrintDesigner.prototype.animatePaletteScroll = function (el, toTop, done) {
        if (!el) {
            if (typeof done === 'function') {
                done();
            }
            return;
        }
        var from = el.scrollTop || 0;
        var to = Math.max(0, toTop);
        var finish = function () {
            el.scrollTop = to;
            if (typeof done === 'function') {
                done();
            }
        };
        if (Math.abs(to - from) < 2) {
            finish();
            return;
        }
        clearInterval(this._paletteScrollTimer);
        var start = Date.now();
        var duration = 180;
        var self = this;
        this._paletteScrollTimer = setInterval(function () {
            var p = Math.min(1, (Date.now() - start) / duration);
            var ease = 1 - Math.pow(1 - p, 3);
            el.scrollTop = from + (to - from) * ease;
            if (p >= 1) {
                clearInterval(self._paletteScrollTimer);
                finish();
            }
        }, 16);
    };

    LabelPrintDesigner.prototype.updatePaletteState = function () {
        var byField = {};
        var byTypeEmpty = {};
        $.each(this.template.elements || [], function (_, el) {
            var field = String(el.field || '');
            var type = el.type || 'text';
            if (field) {
                byField[field] = (byField[field] || 0) + 1;
            } else {
                byTypeEmpty[type] = (byTypeEmpty[type] || 0) + 1;
            }
        });
        this.$root.find('.label-print-palette .item').each(function () {
            var $item = $(this);
            var type = $item.attr('data-type') || 'text';
            var field = String($item.attr('data-field') || '');
            var n = field ? (byField[field] || 0) : (byTypeEmpty[type] || 0);
            var $badge = $item.children('.label-print-use-count');
            if (!$badge.length) {
                $badge = $('<span class="label-print-use-count"></span>');
                $item.append($badge);
            }
            if (n > 0) {
                $badge.text(n).show();
            } else {
                $badge.hide();
            }
        });
    };

    LabelPrintDesigner.prototype.payloadFromItem = function ($item) {
        return {
            type: $item.data('type') || 'text',
            field: $item.data('field') || '',
            title: $item.data('title') || '',
            label: $item.attr('data-label') || '',
            custom: parseInt($item.data('custom'), 10) === 1 ? 1 : 0
        };
    };

    LabelPrintDesigner.prototype.paperDots = function () {
        return {
            width: this.template.width * this.dotsPerMm,
            height: this.template.height * this.dotsPerMm
        };
    };

    LabelPrintDesigner.prototype.toPx = function (dots) {
        return Math.round(dots * this.viewScale * 100) / 100;
    };

    LabelPrintDesigner.prototype.pointFromEvent = function (event) {
        var rect = this.$root.find('.label-print-canvas')[0].getBoundingClientRect();
        var paper = this.paperDots();
        return {
            x: this.clamp(Math.round((event.clientX - rect.left) / this.viewScale), 0, paper.width),
            y: this.clamp(Math.round((event.clientY - rect.top) / this.viewScale), 0, paper.height)
        };
    };

    LabelPrintDesigner.prototype.clamp = function (n, min, max) {
        n = parseInt(n, 10) || 0;
        if (n < min) {
            return min;
        }
        if (n > max) {
            return max;
        }
        return n;
    };

    LabelPrintDesigner.prototype.elementSize = function (el, index) {
        var foot = this.footprint(el);
        if (foot) {
            return { width: foot.width, height: foot.height };
        }
        var node = this.$root.find('.label-print-el[data-index="' + index + '"]')[0];
        if (node) {
            return { width: node.offsetWidth / this.viewScale, height: node.offsetHeight / this.viewScale };
        }
        return { width: 36, height: 24 };
    };

    LabelPrintDesigner.prototype.elementBox = function (el, index, x, y) {
        var size = this.elementSize(el, index);
        var left = (x === undefined || x === null) ? (this.isAligned(el) ? this.alignDrawX(el) : (parseInt(el.x, 10) || 0)) : x;
        var top = (y === undefined || y === null) ? (parseInt(el.y, 10) || 0) : y;
        return {
            left: left,
            top: top,
            right: left + size.width,
            bottom: top + size.height,
            cx: left + size.width / 2,
            cy: top + size.height / 2
        };
    };

    LabelPrintDesigner.prototype.nearestSnap = function (edges, targets, threshold) {
        var best = null;
        var i;
        var j;
        var delta;
        for (i = 0; i < edges.length; i++) {
            for (j = 0; j < targets.length; j++) {
                delta = targets[j] - edges[i];
                if (Math.abs(delta) <= threshold && (!best || Math.abs(delta) < Math.abs(best.delta))) {
                    best = { delta: delta };
                }
            }
        }
        return best;
    };

    LabelPrintDesigner.prototype.snapDrag = function (el, index, x, y) {
        var paper = this.paperDots();
        var threshold = 8;
        var xTargets = [0, paper.width / 2, paper.width];
        var yTargets = [0, paper.height / 2, paper.height];
        var i;
        for (i = 0; i < this.template.elements.length; i++) {
            if (i === index) {
                continue;
            }
            var box = this.elementBox(this.template.elements[i], i);
            xTargets.push(box.left, box.cx, box.right);
            yTargets.push(box.top, box.cy, box.bottom);
        }
        var size = this.elementSize(el, index);
        var nx = x;
        var ny = y;
        if (!this.isAligned(el)) {
            var hitX = this.nearestSnap([x, x + size.width / 2, x + size.width], xTargets, threshold);
            if (hitX) {
                nx = x + hitX.delta;
            }
        }
        var hitY = this.nearestSnap([y, y + size.height / 2, y + size.height], yTargets, threshold);
        if (hitY) {
            ny = y + hitY.delta;
        }
        return { x: nx, y: ny };
    };

    LabelPrintDesigner.prototype.collectGuide = function (list, edges, targets) {
        var i;
        var j;
        for (i = 0; i < edges.length; i++) {
            for (j = 0; j < targets.length; j++) {
                if (Math.abs(edges[i] - targets[j]) <= 1.5 && list.indexOf(targets[j]) < 0) {
                    list.push(targets[j]);
                }
            }
        }
    };

    LabelPrintDesigner.prototype.drawGuides = function (el, index) {
        var $canvas = this.$root.find('.label-print-canvas');
        $canvas.children('.label-print-guide').remove();
        if (!el) {
            return;
        }
        var paper = this.paperDots();
        var box = this.elementBox(el, index);
        var xTargets = [0, paper.width / 2, paper.width];
        var yTargets = [0, paper.height / 2, paper.height];
        var i;
        for (i = 0; i < this.template.elements.length; i++) {
            if (i === index) {
                continue;
            }
            var other = this.elementBox(this.template.elements[i], i);
            xTargets.push(other.left, other.cx, other.right);
            yTargets.push(other.top, other.cy, other.bottom);
        }
        var vertical = [];
        var horizontal = [];
        this.collectGuide(vertical, [box.left, box.cx, box.right], xTargets);
        this.collectGuide(horizontal, [box.top, box.cy, box.bottom], yTargets);
        var self = this;
        $.each(vertical, function (_, pos) {
            $canvas.append('<div class="label-print-guide is-v" style="left:' + self.toPx(pos) + 'px"></div>');
        });
        $.each(horizontal, function (_, pos) {
            $canvas.append('<div class="label-print-guide is-h" style="top:' + self.toPx(pos) + 'px"></div>');
        });
    };

    LabelPrintDesigner.prototype.placeElement = function (el, x, y, index) {
        var paper = this.paperDots();
        var size = this.elementSize(el, index);
        var maxX = Math.max(0, paper.width - size.width);
        var maxY = Math.max(0, paper.height - size.height);
        el.x = this.clamp(Math.round(x), 0, maxX);
        el.y = this.clamp(Math.round(y), 0, maxY);
        this.fitElementInside(el, index);
    };

    LabelPrintDesigner.prototype.fitElementInside = function (el, index) {
        var canvas = this.$root.find('.label-print-canvas')[0];
        if (!canvas) {
            return;
        }
        var node = this.$root.find('.label-print-el[data-index="' + index + '"]')[0];
        var box = this.mediaBox(el) || this.textBox(el);
        var widthPx = node ? node.offsetWidth : this.toPx(box ? box.width : 36);
        var heightPx = node ? node.offsetHeight : this.toPx(box ? box.height : 24);
        var maxLeft = Math.max(0, canvas.clientWidth - widthPx);
        var maxTop = Math.max(0, canvas.clientHeight - heightPx);
        var guard = 0;
        while (el.x > 0 && this.toPx(el.x) > maxLeft && guard < 80) {
            el.x -= 1;
            guard += 1;
        }
        guard = 0;
        while (el.y > 0 && this.toPx(el.y) > maxTop && guard < 80) {
            el.y -= 1;
            guard += 1;
        }
    };

    LabelPrintDesigner.prototype.addElement = function (payload, x, y) {
        var el = {
            id: uid(),
            type: payload.type || 'text',
            field: payload.field || '',
            title: payload.title || '',
            label: payload.label || '',
            value: '',
            x: x,
            y: y,
            hide_empty: 1
        };
        if (el.type === 'text') {
            el.font = 12;
            el.w = 1;
            el.h = 1;
            el.r = 0;
        } else if (el.type === 'qr') {
            el.e = 'L';
            el.w = 5;
        } else if (el.type === 'barcode') {
            el.code = 'BC128';
            el.h = 64;
            el.s = 1;
            el.r = 0;
            el.n = 1;
            el.w = 1;
        }
        this.template.elements.push(el);
        this.selected = this.template.elements.length - 1;
        this.renderCanvas();
        this.placeElement(el, el.x, el.y, this.selected);
        this.renderCanvas();
        this.renderSetting();
        this.sync();
    };

    LabelPrintDesigner.prototype.removeSelected = function () {
        if (this.selected < 0 || !this.template.elements[this.selected]) {
            return;
        }
        this.template.elements.splice(this.selected, 1);
        this.selected = -1;
        this.renderCanvas();
        this.renderSetting();
        this.sync();
    };

    LabelPrintDesigner.prototype.updateProp = function ($input) {
        var el = this.template.elements[this.selected];
        if (!el) {
            return;
        }
        var prop = $input.data('prop');
        var val = $input.val();
        if (prop === 'x' && this.isAligned(el)) {
            return;
        }
        var prevAlign = el.align || '';
        if ($input.attr('type') === 'checkbox') {
            el[prop] = $input.prop('checked') ? 1 : 0;
        } else if (['x', 'y', 'font', 'w', 'h', 'r', 's', 'n', 'gap'].indexOf(prop) !== -1) {
            el[prop] = parseInt(val, 10);
            if (isNaN(el[prop])) {
                el[prop] = 0;
            }
        } else {
            el[prop] = val;
        }
        if (prop === 'align') {
            if (val === 'left' || val === 'right') {
                el.x = 0;
            } else if (prevAlign === 'left' || prevAlign === 'right') {
                el.align = prevAlign;
                el.x = this.alignDrawX(el);
                el.align = val;
            }
        }
        var range = this.metricRange(el, prop);
        if (range) {
            el[prop] = this.clamp(el[prop], range.min, range.max);
            if (String($input.val()) !== String(el[prop])) {
                $input.val(el[prop]);
            }
        }
        if (prop === 'x' || prop === 'y') {
            this.placeElement(el, el.x, el.y, this.selected);
            $input.val(el[prop]);
        }
        this.renderCanvas();
        var placedX = el.x;
        var placedY = el.y;
        this.placeElement(el, el.x, el.y, this.selected);
        if (el.align === 'left' || el.align === 'right') {
            el.x = 0;
        }
        if (el.x !== placedX || el.y !== placedY) {
            this.renderCanvas();
        }
        if (prop === 'align') {
            this.renderSetting();
        } else {
            var showX = (el.align === 'left' || el.align === 'right') ? 0 : el.x;
            this.$root.find('.label-print-setting [data-prop="x"]').val(showX);
            this.$root.find('.label-print-setting [data-prop="y"]').val(el.y);
        }
        this.sync();
    };

    LabelPrintDesigner.prototype.render = function () {
        this.renderPaperBar();
        this.renderCanvas();
        this.renderSetting();
    };

    LabelPrintDesigner.prototype.renderPaperBar = function () {
        var lang = this.lang;
        var t = this.template;
        var html = '';
        var widthTitle = lang.paper_width || '宽mm';
        var heightTitle = lang.paper_height || '高mm';
        html += '<label>' + widthTitle + '</label><input type="number" data-key="width" min="20" max="150" value="' + t.width + '" placeholder="' + this.escape(widthTitle) + '" />';
        html += '<label>' + heightTitle + '</label><input type="number" data-key="height" min="10" max="200" value="' + t.height + '" placeholder="' + this.escape(heightTitle) + '" />';
        html += '<label>' + (lang.paper_direction || '方向') + '</label><select class="am-radius chosen-select" data-key="direction">';
        html += '<option value="1"' + (t.direction === 1 ? ' selected' : '') + '>' + (lang.direction_forward || '正向') + '</option>';
        html += '<option value="0"' + (t.direction === 0 ? ' selected' : '') + '>' + (lang.direction_backward || '反向') + '</option>';
        html += '</select>';
        if (this.showRepeat) {
            html += '<label>' + (lang.repeat || '打印方式') + '</label><select class="am-radius chosen-select" data-key="repeat">';
            html += '<option value="once"' + (t.repeat === 'once' ? ' selected' : '') + '>' + (lang.repeat_once || '整单一张') + '</option>';
            if (this.showRepeatItem) {
                html += '<option value="item"' + (t.repeat === 'item' ? ' selected' : '') + '>' + (lang.repeat_item || '按商品行') + '</option>';
            }
            if (this.showRepeatQty) {
                html += '<option value="qty"' + (t.repeat === 'qty' ? ' selected' : '') + '>' + this.escape(this.repeatQtyText || lang.repeat_qty || '按商品数量') + '</option>';
            }
            html += '</select>';
        }
        if (this.paperUrl) {
            html += '<button type="button" class="am-btn am-btn-default am-btn-xs am-radius label-print-paper-submit">' + (lang.paper_send || '下发纸张') + '</button>';
        }
        html += '<button type="button" class="am-btn am-btn-danger-plain am-radius am-btn-xs label-print-clear-btn">' + (lang.form_panel_clear || '清空') + '</button>';
        html += '<span class="am-text-grey am-text-xs">' + (lang.paper_unit || '1mm = 8点') + '</span>';
        this.$root.find('.label-print-paper-bar').html(html);
        if (typeof SelectChosenInit === 'function') {
            SelectChosenInit();
        }
    };

    LabelPrintDesigner.prototype.isAligned = function (el) {
        return !!(el && (el.align === 'left' || el.align === 'center' || el.align === 'right'));
    };

    LabelPrintDesigner.prototype.alignDrawX = function (el) {
        if (!el || !this.isAligned(el)) {
            return parseInt(el && el.x, 10) || 0;
        }
        var paperW = this.paperDots().width;
        var width = this.elementSize(el, -1).width;
        var gap = 0;
        if (el.align === 'left' || el.align === 'right') {
            gap = parseInt(el.gap, 10) || 0;
            if (gap < 0) {
                gap = 0;
            }
        }
        var x = gap;
        if (el.align === 'center') {
            x = Math.round((paperW - width) / 2);
        } else if (el.align === 'right') {
            x = paperW - width - gap;
        }
        if (x < 0) {
            x = 0;
        }
        if (x > paperW) {
            x = paperW;
        }
        return x;
    };

    LabelPrintDesigner.prototype.applyAlign = function (el) {
        if (!this.isAligned(el) || el.align === 'left' || el.align === 'right') {
            return;
        }
        el.x = this.alignDrawX(el);
    };

    LabelPrintDesigner.prototype.applyAlignAll = function () {
        var i;
        for (i = 0; i < (this.template.elements || []).length; i++) {
            this.applyAlign(this.template.elements[i]);
        }
    };

    LabelPrintDesigner.prototype.renderCanvas = function () {
        this.applyAlignAll();
        var $canvas = this.$root.find('.label-print-canvas');
        var paper = this.paperDots();
        var w = this.toPx(paper.width);
        var h = this.toPx(paper.height);
        $canvas.css({ width: w + 'px', height: h + 'px' });
        var html = '';
        var i, el;
        if (!this.template.elements.length) {
            html = '<div class="label-print-empty">' + (this.lang.empty_canvas || '') + '</div>';
        }
        for (i = 0; i < this.template.elements.length; i++) {
            el = this.template.elements[i];
            var cls = 'label-print-el' + (i === this.selected ? ' is-selected' : '') + (this.isAligned(el) ? ' is-aligned' : '');
            var x = this.alignDrawX(el);
            var y = parseInt(el.y, 10) || 0;
            var box = this.mediaBox(el);
            var textBox = box ? null : this.textBox(el);
            var contentW = box ? box.width : (textBox ? textBox.width : 36);
            var contentH = box ? box.height : (textBox ? textBox.height : 24);
            var foot = this.footprint(el);
            var rot = this.rotation(el);
            var style = 'left:' + this.toPx(x) + 'px;top:' + this.toPx(y) + 'px;';
            style += 'width:' + this.toPx(foot.width) + 'px;height:' + this.toPx(foot.height) + 'px;';
            if (box) {
                cls += el.type === 'qr' ? ' is-qr' : ' is-barcode';
            } else if (textBox) {
                cls += ' is-text';
            }
            var rotStyle = 'width:' + this.toPx(contentW) + 'px;height:' + this.toPx(contentH) + 'px;transform:translate(-50%,-50%) rotate(' + rot + 'deg);';
            html += '<div class="' + cls + '" data-index="' + i + '" style="' + style + '">';
            html += '<div class="label-print-rot" style="' + rotStyle + '">';
            if (el.type === 'qr') {
                html += '<span class="media-face"><i></i><i></i><i></i></span>';
            } else if (el.type === 'barcode') {
                html += '<span class="media-face"></span>';
                if (box.text) {
                    html += '<span class="media-text" style="height:' + this.toPx(box.text) + 'px;line-height:' + this.toPx(box.text) + 'px;font-size:' + this.toPx(12) + 'px;">' + this.escape(this.mediaText(el)) + '</span>';
                }
            } else if (textBox) {
                html += '<span class="text-face" style="font-size:' + this.toPx(textBox.fontSize) + 'px;line-height:' + this.toPx(textBox.fontSize) + 'px;transform:scaleX(' + textBox.scaleX + ');">' + this.escape(this.previewText(el)) + '</span>';
            } else {
                html += this.escape(this.previewText(el));
            }
            html += '</div></div>';
        }
        $canvas.html(html);
        this.updatePaletteState();
    };

    LabelPrintDesigner.prototype.renderSetting = function () {
        var $box = this.$root.find('.label-print-setting-body');
        var el = this.selected >= 0 ? this.template.elements[this.selected] : null;
        if (!el) {
            $box.html('<div class="empty-tips">' + (this.lang.setting_tips || '') + '</div>');
            return;
        }
        var lang = this.lang;
        var html = '<div class="am-form-group"><label>' + this.escape(lang.prop_title || '组件') + '</label><div>' + this.escape(this.elementTitle(el)) + '</div></div>';
        if (el.type === 'text' || el.type === 'qr' || el.type === 'barcode') {
            html += this.formatTabsHtml(el.type);
        }
        html += this.selectProp('align', lang.prop_align || '对齐', el.align || '', [
            ['', lang.align_free || '自由'],
            ['left', lang.align_left || '居左'],
            ['center', lang.align_center || '居中'],
            ['right', lang.align_right || '居右']
        ]);
        if (el.align === 'left' || el.align === 'right') {
            html += this.numProp('gap', lang.prop_gap || '间距', el.gap || 0, 0);
        }
        var shownX = (el.align === 'left' || el.align === 'right') ? 0 : el.x;
        html += this.numProp('x', lang.prop_x || 'X', shownX, 0, null, this.isAligned(el));
        html += this.numProp('y', lang.prop_y || 'Y', el.y, 0);
        if (el.type === 'text') {
            html += this.textProp('label', lang.prop_label || '前缀', el.label || '');
            if (!el.field) {
                html += this.textProp('value', lang.prop_custom_text || '内容', el.value || '');
            }
            html += this.selectProp('font', lang.prop_font || '字体', el.font || 12, [
                [12, lang.font_12 || '24×24'],
                [1, '8×12'],
                [2, '12×20'],
                [3, '16×24'],
                [4, '24×32'],
                [5, '32×48']
            ]);
            html += this.numProp('w', lang.prop_w || '宽倍率', el.w || 1, 1, 10);
            html += this.numProp('h', lang.prop_h || '高倍率', el.h || 1, 1, 10);
            html += this.selectProp('r', lang.prop_r || '旋转角度', el.r || 0, [[0, '0°'], [90, '90°'], [180, '180°'], [270, '270°']]);
        } else if (el.type === 'qr') {
            if (!el.field) {
                html += this.textProp('value', lang.prop_custom_text || '内容', el.value || '');
            }
            html += this.selectProp('e', lang.prop_qr_level || '纠错', el.e || 'L', [['L', 'L'], ['M', 'M'], ['Q', 'Q'], ['H', 'H']]);
            html += this.numProp('w', lang.prop_qr_size || '宽度', el.w || 5, 1, 10);
        } else if (el.type === 'barcode') {
            if (!el.field) {
                html += this.textProp('value', lang.prop_custom_text || '内容', el.value || '');
            }
            html += this.selectProp('code', lang.prop_code || '类型', el.code || 'BC128', [['BC128', 'Code128'], ['BC39', 'Code39']]);
            html += this.numProp('h', lang.prop_bar_h || '高度', el.h || 64, 1, 255);
            html += this.selectProp('s', lang.prop_bar_text || '文字', el.s === 0 ? 0 : 1, [[1, lang.yes || '显示'], [0, lang.no || '不显示']]);
            html += this.numProp('n', lang.prop_bar_n || '窄条', el.n || 1, 1, 10);
            html += this.numProp('w', lang.prop_bar_w || '宽条', el.w || 1, 1, 10);
        }
        html += '<div class="am-form-group"><label class="am-checkbox"><input type="checkbox" data-prop="hide_empty" data-am-ucheck ' + (el.hide_empty === 0 ? '' : 'checked') + ' /> ' + (lang.prop_hide_empty || '空值不打印') + '</label></div>';
        html += '<button type="button" class="am-btn am-btn-danger am-btn-xs am-radius label-print-delete">' + (lang.remove || '删除') + '</button>';
        $box.html(html);
        var $ucheck = $box.find('input[data-am-ucheck]');
        if ($ucheck.length && $.fn.uCheck) {
            $ucheck.uCheck();
        }
    };

    LabelPrintDesigner.prototype.formatTabsHtml = function (value) {
        var lang = this.lang;
        var options = [
            ['text', lang.format_text || '文本'],
            ['barcode', lang.format_barcode || '条码'],
            ['qr', lang.format_qr || '二维码']
        ];
        var html = '<div class="label-print-format-box">';
        html += '<div class="label-print-format-title">' + this.escape(lang.prop_format || '格式') + '</div>';
        html += '<ul class="label-print-format-tabs">';
        var i;
        for (i = 0; i < options.length; i++) {
            html += '<li class="item' + (String(value) === options[i][0] ? ' am-active' : '') + '" data-type="' + options[i][0] + '">' + this.escape(options[i][1]) + '</li>';
        }
        html += '</ul></div>';
        return html;
    };

    LabelPrintDesigner.prototype.changeElementType = function (type) {
        var el = this.template.elements[this.selected];
        if (!el || ['text', 'qr', 'barcode'].indexOf(type) < 0) {
            return;
        }
        el.type = type;
        if (type === 'text') {
            el.font = 12;
            el.w = 1;
            el.h = 1;
            el.r = 0;
        } else if (type === 'qr') {
            el.e = 'L';
            el.w = 5;
        } else if (type === 'barcode') {
            el.code = 'BC128';
            el.h = 64;
            el.s = 1;
            el.n = 1;
            el.w = 1;
            el.r = 0;
        }
        this.renderCanvas();
        this.placeElement(el, el.x, el.y, this.selected);
        this.renderCanvas();
        this.renderSetting();
        this.sync();
    };

    LabelPrintDesigner.prototype.metricRange = function (el, prop) {
        if (!el) {
            return null;
        }
        if (prop === 'x' || prop === 'y' || prop === 'gap') {
            return { min: 0, max: 9999 };
        }
        if (el.type === 'text' && (prop === 'w' || prop === 'h')) {
            return { min: 1, max: 10 };
        }
        if (el.type === 'qr' && prop === 'w') {
            return { min: 1, max: 10 };
        }
        if (el.type === 'barcode' && prop === 'h') {
            return { min: 1, max: 255 };
        }
        if (el.type === 'barcode' && (prop === 'n' || prop === 'w')) {
            return { min: 1, max: 10 };
        }
        return null;
    };

    LabelPrintDesigner.prototype.numProp = function (prop, title, value, min, max, disabled) {
        var extra = '';
        var shown = parseInt(value, 10);
        if (isNaN(shown)) {
            shown = 0;
        }
        if (disabled) {
            extra += ' disabled';
        }
        if (min != null) {
            extra += ' min="' + min + '"';
            if (shown < min) {
                shown = min;
            }
        }
        if (max != null) {
            extra += ' max="' + max + '"';
            if (shown > max) {
                shown = max;
            }
        }
        return '<div class="am-form-group"><label>' + this.escape(title) + '</label><input type="number" class="am-form-field am-radius am-input-sm" data-prop="' + prop + '" value="' + shown + '" placeholder="' + this.escape(title) + '"' + extra + ' /></div>';
    };

    LabelPrintDesigner.prototype.textProp = function (prop, title, value) {
        return '<div class="am-form-group"><label>' + this.escape(title) + '</label><input type="text" class="am-form-field am-radius am-input-sm" data-prop="' + prop + '" value="' + this.escape(value || '') + '" placeholder="' + this.escape(title) + '" /></div>';
    };

    LabelPrintDesigner.prototype.selectProp = function (prop, title, value, options) {
        var name = this.ns.replace(/\W/g, '') + '-' + prop;
        var html = '<div class="am-form-group"><label>' + this.escape(title) + '</label><div class="label-print-radio-list">';
        var i;
        for (i = 0; i < options.length; i++) {
            var checked = String(options[i][0]) === String(value) ? ' checked' : '';
            html += '<label class="am-radio-inline">';
            html += '<input type="radio" name="' + name + '" data-prop="' + prop + '" value="' + this.escape(options[i][0]) + '"' + checked + ' data-am-ucheck /> ' + this.escape(options[i][1]);
            html += '</label>';
        }
        html += '</div></div>';
        return html;
    };

    LabelPrintDesigner.prototype.escape = function (text) {
        return String(text || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    };

    LabelPrintDesigner.prototype.sync = function () {
        this.$input.val(JSON.stringify(this.template));
    };

    LabelPrintDesigner.prototype.loadJson = function (raw) {
        this.$input.val(raw == null ? '' : String(raw));
        this.template = this.parseSaved();
        this.selected = -1;
        this.render();
        this.sync();
    };

    LabelPrintDesigner.prototype.sendPaper = function () {
        var self = this;
        if (!this.paperUrl) {
            return;
        }
        $.AMUI.progress.start();
        $.ajax({
            url: RequestUrlHandle(this.paperUrl),
            type: 'POST',
            dataType: 'json',
            data: {
                width: this.template.width,
                height: this.template.height,
                direction: this.template.direction
            },
            success: function (res) {
                $.AMUI.progress.done();
                if (res.code == 0) {
                    Prompt(res.msg, 'success');
                } else {
                    Prompt(res.msg);
                }
            },
            error: function (xhr) {
                $.AMUI.progress.done();
                Prompt(HtmlToString(xhr.responseText) || 'error');
            }
        });
    };

    function bindLabelPrintPopup() {
        if (window._labelPrintPopupBound) {
            return;
        }
        window._labelPrintPopupBound = true;
        $(document).on('click', '.label-print-open', function () {
            var $entry = $(this).closest('.label-print-popup-entry');
            var $popup = $('#' + $(this).data('popup-id'));
            var designerEl = $popup.find('.label-print-designer')[0];
            var designer = designerEl ? designerEl._labelPrintDesigner : null;
            if (!$popup.length || !designer) {
                return;
            }
            designer.loadJson($entry.find('.label-print-popup-input').val());
            $popup.data('label-print-entry', $entry);
            $popup.modal({ closeViaDimmer: false });
        });
        $(document).on('click', '.label-print-popup-confirm', function () {
            var $popup = $(this).closest('.am-popup');
            var designerEl = $popup.find('.label-print-designer')[0];
            var designer = designerEl ? designerEl._labelPrintDesigner : null;
            var popupId = $popup.attr('id');
            var $entry = $popup.data('label-print-entry');
            if ((!$entry || !$entry.length) && popupId) {
                $entry = $('.label-print-open[data-popup-id="' + popupId + '"]').closest('.label-print-popup-entry');
            }
            if (designer && $entry && $entry.length) {
                designer.sync();
                var raw = designer.$input.val();
                $entry.find('.label-print-popup-input').val(raw);
                var designed = false;
                try {
                    var saved = JSON.parse(raw);
                    designed = !!(saved && saved.elements && saved.elements.length);
                } catch (e) {
                    designed = false;
                }
                $entry.find('.label-print-designed').toggleClass('am-hide', !designed);
            }
            $popup.modal('close');
        });
    }

    window.LabelPrintDesignerInit = function () {
        $('.label-print-designer').each(function () {
            if ($(this).data('label-print-ready')) {
                return;
            }
            $(this).data('label-print-ready', 1);
            this._labelPrintDesigner = new LabelPrintDesigner(this);
        });
        bindLabelPrintPopup();
    };
})(window, jQuery);
