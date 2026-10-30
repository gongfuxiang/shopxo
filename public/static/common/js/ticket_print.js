/**
 * 小票可视化设计器（块级，非 hiprint）
 */
(function (window, $) {
    'use strict';

    function uid() {
        return 'e' + Date.now().toString(36) + Math.random().toString(36).slice(2, 7);
    }

    function clone(obj) {
        return JSON.parse(JSON.stringify(obj || {}));
    }

    function TicketPrintDesigner(root) {
        this.$root = $(root);
        this.$form = this.$root.closest('form');
        this.formName = this.$root.data('form-name') || 'ticket_print_template';
        this.draft = parseInt(this.$root.data('draft'), 10) === 1;
        this.lang = this.$root.data('lang') || {};
        this.typeData = this.$root.data('type-data') || {};
        this.sampleData = this.$root.data('sample-data') || {};
        this.templateKeys = this.$root.data('template-keys') || [];
        this.defaultTemplates = this.$root.data('default-templates') || {};
        if (this.draft) {
            this.$input = this.$root.find('textarea.ticket-print-draft-input');
            if (!this.$input.length) {
                this.$input = $('<textarea class="am-hide ticket-print-draft-input"></textarea>');
                this.$root.prepend(this.$input);
            }
        } else {
            this.$input = this.$form.find('[name="' + this.formName + '"]');
            if (!this.$input.length) {
                this.$input = $('<textarea class="am-hide" name="' + this.formName + '"></textarea>');
                this.$form.append(this.$input);
            }
        }
        this.currentKey = this.templateKeys.length ? String(this.templateKeys[0].value) : 'default';
        this.selected = -1;
        this.dragFrom = '';
        this.dragIndex = -1;
        this.dragPayload = null;
        this.dragHeight = 32;
        this.lastHintAt = -1;
        this.didDrag = false;
        this.templates = this.parseSaved();
        this.bind();
        this.render();
        this.sync();
    }

    TicketPrintDesigner.prototype.parseSaved = function () {
        var raw = $.trim(this.$input.val() || '');
        var data = {};
        if (raw) {
            try {
                data = JSON.parse(raw);
            } catch (e) {
                data = {};
            }
        }
        var result = {};
        if (this.templateKeys.length) {
            var self = this;
            $.each(this.templateKeys, function (_, item) {
                var key = String(item.value);
                var tpl = data[key] || data[item.value] || self.defaultTemplates[key] || self.defaultTemplates[item.value] || { width: 58, elements: [] };
                if (!tpl.elements) {
                    tpl.elements = [];
                }
                result[key] = tpl;
            });
            return result;
        }
        if (data.elements) {
            return { default: data };
        }
        if (data.default && data.default.elements) {
            return { default: data.default };
        }
        var list = this.getDefaultTemplateList();
        if (list.length > 0 && list[0].tpl) {
            return { default: list[0].tpl };
        }
        return { default: { width: 58, elements: [] } };
    };

    TicketPrintDesigner.prototype.tpl = function () {
        if (!this.templates[this.currentKey]) {
            this.templates[this.currentKey] = { width: 58, elements: [] };
        }
        return this.templates[this.currentKey];
    };

    TicketPrintDesigner.prototype.fieldMeta = function (field, type) {
        var groups = ['common', 'business', 'assist'];
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

    TicketPrintDesigner.prototype.elementTitle = function (el) {
        if (el && el.title) {
            return el.title;
        }
        var meta = this.fieldMeta(el ? el.field : '', el ? el.type : '');
        if (meta && meta.title) {
            return meta.title;
        }
        var type = el ? el.type : '';
        if (type === 'line') {
            return this.lang.assist_line || type;
        }
        if (type === 'blank') {
            return this.lang.assist_blank || type;
        }
        if (type === 'qr') {
            return this.lang.assist_qr || type;
        }
        if (type === 'barcode') {
            return this.lang.assist_barcode || type;
        }
        if (type === 'cut') {
            return this.lang.assist_cut || type;
        }
        if (type === 'items') {
            return this.lang.items_sample || type;
        }
        return type || '';
    };

    TicketPrintDesigner.prototype.previewText = function (el) {
        if (el.type === 'line') {
            return '';
        }
        if (el.type === 'blank') {
            return '';
        }
        if (el.type === 'cut') {
            return this.lang.assist_cut_feed || this.lang.assist_cut || 'CUT';
        }
        if (el.type === 'qr') {
            return 'QR ' + (el.custom_text || el.field || '');
        }
        if (el.type === 'barcode') {
            return 'BAR ' + (el.custom_text || el.field || '');
        }
        if (el.type === 'items') {
            var field = el.field || 'items';
            var items = this.sampleData[field];
            if (!items || !items.length) {
                var itemsMeta = this.fieldMeta(field, 'items');
                if (itemsMeta && itemsMeta.sample && itemsMeta.sample.length) {
                    items = itemsMeta.sample;
                } else if (field !== 'items' && this.sampleData.items && this.sampleData.items.length) {
                    // 非商品字段没有样例时不回退到商品，避免地址/快递显示成商品信息
                    items = [];
                } else {
                    items = this.sampleData.items || [];
                }
            }
            if (!items || !items.length) {
                var emptyMeta = this.fieldMeta(field, 'items');
                return (emptyMeta && emptyMeta.title) || this.lang.items_sample || '商品列表';
            }
            var lines = [];
            var self = this;
            if (el.mode !== 'simple') {
                lines.push(this.resolveItemsHeader(el, field));
            }
            $.each(items, function (_, item) {
                lines.push(self.composeItemsPreviewLine(item, field, el.mode || 'table'));
            });
            return lines.filter(Boolean).join('\n');
        }
        var label = el.label || '';
        var value = el.custom_text;
        if (!value && el.field && this.sampleData[el.field] != null && this.sampleData[el.field] !== '') {
            value = this.sampleData[el.field];
        }
        if (!value) {
            var meta = this.fieldMeta(el.field, el.type);
            // 有前缀时不要用标题当样例，避免「标题：标题」叠在一起
            if (meta && meta.sample !== undefined && meta.sample !== null && String(meta.sample) !== '') {
                value = meta.sample;
            } else if (!label) {
                value = meta ? (meta.title || '') : (el.field || '');
            } else {
                value = this.lang.field_sample || '';
            }
        }
        return label + value;
    };

    TicketPrintDesigner.prototype.bind = function () {
        var self = this;
        // input / change / search：兼容浏览器搜索框叉号清空（部分环境只触发 change/search）
        this.$root.on('input change search', '.ticket-print-palette-search-input', function () {
            self.filterPalette($.trim($(this).val() || ''));
        });
        this.$root.on('keydown', '.ticket-print-palette-search-input', function (e) {
            if (e.keyCode === 27) {
                $(this).val('');
                self.filterPalette('');
            }
        });
        this.$root.on('click', '.ticket-print-palette .item', function () {
            if (self.didDrag) {
                self.didDrag = false;
                return;
            }
            if ($(this).hasClass('is-disabled')) {
                if (($(this).data('type') || '') === 'qr') {
                    Prompt(self.lang.qr_only_one || '');
                }
                return;
            }
            var $item = $(this);
            self.addElement({
                type: $item.data('type') || 'text',
                field: $item.data('field') || '',
                title: $item.data('title') || '',
                label: $item.data('label') || '',
                mode: $item.data('mode') || '',
                size: $item.data('size') || '',
                header: $item.data('header') != null ? String($item.data('header')) : '',
                custom: parseInt($item.data('custom'), 10) === 1
            });
        });
        this.$root.on('click', '.ticket-print-ticket .el', function (e) {
            e.stopPropagation();
            self.selected = self.elIndex($(this));
            self.renderCanvas();
            self.renderSetting();
            self.locatePaletteItem(self.tpl().elements[self.selected], true);
        });
        this.$root.on('click', '.ticket-print-ticket .el-remove', function (e) {
            e.stopPropagation();
            self.removeElement(self.elIndex($(this).closest('.el')));
        });
        this.$root.on('click', '.ticket-print-delete', function (e) {
            e.preventDefault();
            self.removeElement(self.selected);
        });
        this.$root.on('click', '.ticket-print-width-btn', function () {
            self.tpl().width = parseInt($(this).data('width'), 10) || 58;
            self.renderToolbar();
            self.renderCanvas();
            self.sync();
        });
        this.$root.on('click', '.ticket-print-key-btn', function () {
            self.currentKey = String($(this).data('key'));
            self.selected = -1;
            self.render();
        });
        this.$root.on('click', '.ticket-print-clear-btn', function () {
            self.tpl().elements = [];
            self.selected = -1;
            self.clearPaletteLocate();
            self.renderCanvas();
            self.renderSetting();
            self.sync();
        });
        this.$root.on('click', '.ticket-print-reset-btn', function () {
            // 仅一套默认时直接恢复
            var list = self.getDefaultTemplateList();
            if (list.length === 1) {
                self.applyDefaultTemplate(list[0].key);
            } else if (list.length > 1) {
                self.applyDefaultTemplate(self.resolveDefaultTemplateKey());
            }
        });
        this.$root.on('click', '.ticket-print-reset-item', function (e) {
            e.preventDefault();
            self.applyDefaultTemplate(String($(this).data('key')));
            var $dd = $(this).closest('.am-dropdown');
            if ($dd.length && $dd.data('am.dropdown')) {
                $dd.dropdown('close');
            }
        });
        this.$root.on('click', '.ticket-print-format-tabs .item', function () {
            if (self.selected < 0 || $(this).hasClass('am-active')) {
                return;
            }
            self.changeElementType(String($(this).data('type') || 'text'));
        });
        this.$root.on('change input', '.ticket-print-setting [data-prop]', function () {
            if (self.selected < 0) {
                return;
            }
            var $el = $(this);
            var prop = $el.data('prop');
            var val = $el.attr('type') === 'checkbox' ? ($el.prop('checked') ? 1 : 0) : $el.val();
            var target = self.tpl().elements[self.selected];
            if (prop === 'size_amplify') {
                target.size = val ? 'B' : 'normal';
            } else {
                target[prop] = val;
            }
            // 切换列表样式时按字段套用对应默认表头（地址/快递/服务与商品不同）
            if (prop === 'mode' && target.type === 'items') {
                target.header = self.defaultItemsHeader(target.field || '', val);
                self.renderSetting();
            }
            self.renderCanvas();
            self.sync();
        });
        this.$form.on('submit', function () {
            self.sync();
        });
        this.bindDrag();
    };

    TicketPrintDesigner.prototype.elIndex = function ($el) {
        return this.$root.find('.ticket-print-ticket').children('.el').index($el);
    };

    TicketPrintDesigner.prototype.visibleEls = function () {
        return this.$root.find('.ticket-print-ticket').children('.el').not('.is-dragging');
    };

    TicketPrintDesigner.prototype.dropIndexFromPoint = function (clientY) {
        var $els = this.visibleEls();
        var insertAt = $els.length;
        $els.each(function (i) {
            var rect = this.getBoundingClientRect();
            if (clientY < rect.top + rect.height / 2) {
                insertAt = i;
                return false;
            }
        });
        return insertAt;
    };

    TicketPrintDesigner.prototype.palettePreviewHeight = function (payload, sourceHeight) {
        var type = payload && payload.type ? payload.type : 'text';
        if (type === 'qr') {
            return 92;
        }
        if (type === 'barcode') {
            return 64;
        }
        if (type === 'line') {
            return 24;
        }
        if (type === 'blank') {
            return 24;
        }
        if (type === 'items') {
            return 72;
        }
        return Math.max(sourceHeight || 0, 28);
    };

    TicketPrintDesigner.prototype.showDropHint = function (insertAt) {
        if (this.lastHintAt === insertAt && this.$root.find('.ticket-print-drop-hint').length) {
            return;
        }
        this.lastHintAt = insertAt;
        var $ticket = this.$root.find('.ticket-print-ticket');
        var $hint = $ticket.children('.ticket-print-drop-hint');
        if (!$hint.length) {
            $hint = $('<div class="ticket-print-drop-hint"></div>');
        }
        $hint.css('height', ((this.dragHeight || 32) / 10) + 'rem');
        var $els = this.visibleEls();
        if (!$els.length || insertAt >= $els.length) {
            $ticket.append($hint);
            return;
        }
        $els.eq(insertAt).before($hint);
    };

    TicketPrintDesigner.prototype.clearDropHint = function () {
        this.$root.find('.ticket-print-drop-hint').remove();
        this.$root.find('.ticket-print-ticket .el.is-dragging').removeClass('is-dragging');
        this.lastHintAt = -1;
    };

    TicketPrintDesigner.prototype.resetDrag = function () {
        this.clearDropHint();
        this.dragFrom = '';
        this.dragIndex = -1;
        this.dragPayload = null;
        this.dragHeight = 32;
    };

    TicketPrintDesigner.prototype.bindDrag = function () {
        var self = this;
        this.$root.on('dragstart', '.ticket-print-palette .item', function (e) {
            if ($(this).hasClass('is-disabled')) {
                e.preventDefault();
                return;
            }
            var $item = $(this);
            self.didDrag = true;
            self.dragFrom = 'palette';
            self.dragIndex = -1;
            self.dragPayload = {
                type: $item.data('type') || 'text',
                field: $item.data('field') || '',
                title: $item.data('title') || '',
                label: $item.data('label') || '',
                mode: $item.data('mode') || '',
                size: $item.data('size') || '',
                header: $item.data('header') != null ? String($item.data('header')) : '',
                custom: parseInt($item.data('custom'), 10) === 1
            };
            self.dragHeight = self.palettePreviewHeight(self.dragPayload, $item.outerHeight());
            self.lastHintAt = -1;
            e.originalEvent.dataTransfer.effectAllowed = 'copy';
            e.originalEvent.dataTransfer.setData('text/plain', 'palette');
        });
        this.$root.on('dragstart', '.ticket-print-ticket .el', function (e) {
            if ($(e.target).closest('.el-remove').length) {
                e.preventDefault();
                return;
            }
            var $el = $(this);
            var evt = e.originalEvent;
            self.didDrag = true;
            self.dragFrom = 'sort';
            self.dragIndex = self.elIndex($el);
            self.dragPayload = null;
            self.dragHeight = Math.max($el.outerHeight(), 24);
            self.lastHintAt = -1;
            evt.dataTransfer.effectAllowed = 'move';
            evt.dataTransfer.setData('text/plain', 'sort');
            try {
                evt.dataTransfer.setDragImage($el[0], 16, 12);
            } catch (err) {}
            setTimeout(function () {
                if (self.dragFrom !== 'sort') {
                    return;
                }
                $el.addClass('is-dragging');
                self.showDropHint(self.dragIndex);
            }, 0);
        });
        this.$root.on('dragover', '.ticket-print-ticket', function (e) {
            if (!self.dragFrom) {
                return;
            }
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = self.dragFrom === 'palette' ? 'copy' : 'move';
            self.showDropHint(self.dropIndexFromPoint(e.originalEvent.clientY));
        });
        this.$root.on('drop', '.ticket-print-ticket', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var insertAt = self.dropIndexFromPoint(e.originalEvent.clientY);
            if (self.dragFrom === 'palette' && self.dragPayload) {
                self.addElement(self.dragPayload, insertAt);
            } else if (self.dragFrom === 'sort' && self.dragIndex >= 0) {
                var list = self.tpl().elements;
                var item = list.splice(self.dragIndex, 1)[0];
                list.splice(insertAt, 0, item);
                self.selected = insertAt;
                self.renderCanvas();
                self.renderSetting();
                self.sync();
            }
            self.resetDrag();
        });
        this.$root.on('dragend', '.ticket-print-palette .item, .ticket-print-ticket .el', function () {
            setTimeout(function () {
                self.resetDrag();
                self.didDrag = false;
            }, 50);
        });
    };

    TicketPrintDesigner.prototype.hasType = function (type, excludeIndex) {
        var found = false;
        $.each(this.tpl().elements || [], function (index, el) {
            if (excludeIndex !== undefined && index === excludeIndex) {
                return;
            }
            if (el && el.type === type) {
                found = true;
            }
        });
        return found;
    };

    TicketPrintDesigner.prototype.isFormatType = function (type) {
        return type === 'text' || type === 'barcode' || type === 'qr';
    };

    TicketPrintDesigner.prototype.changeElementType = function (type) {
        if (this.selected < 0 || !this.isFormatType(type)) {
            return;
        }
        var el = this.tpl().elements[this.selected];
        if (!el || !this.isFormatType(el.type)) {
            this.renderSetting();
            return;
        }
        if (type === 'qr' && el.type !== 'qr' && this.hasType('qr', this.selected)) {
            Prompt(this.lang.qr_only_one || '');
            this.renderSetting();
            return;
        }
        el.type = type;
        if (type !== 'text') {
            el.label = '';
            el.align = 'left';
            el.size = 'normal';
            el.bold = 0;
            el.tall = 0;
            el.wide = 0;
        }
        if (!el.title) {
            el.title = this.elementTitle(el);
        }
        this.renderCanvas();
        this.renderSetting();
        this.sync();
    };

    TicketPrintDesigner.prototype.defaultItemsHeader = function (field, mode) {
        mode = mode || 'table';
        if (mode === 'simple') {
            return '';
        }
        var meta = this.fieldMeta(field || '', 'items');
        if (meta && meta.header) {
            return meta.header;
        }
        if (field === 'address_data') {
            return this.lang.address_header || '';
        }
        if (field === 'express_data') {
            return this.lang.express_header || '';
        }
        if (field === 'service_data') {
            return this.lang.service_header || '';
        }
        return this.lang.items_header || '';
    };

    // 内置默认表头随当前语言显示；自定义表头保持原样
    TicketPrintDesigner.prototype.resolveItemsHeader = function (el, field) {
        field = field || (el && el.field) || 'items';
        var current = this.defaultItemsHeader(field, (el && el.mode) || 'table');
        var header = el && el.header != null ? String(el.header) : '';
        if (!header) {
            return current;
        }
        var builtins = [
            '名称           单价  数量 金额',
            '名稱           單價  數量 金額',
            'Name           Price Qty Amount',
            'Nombre          Precio Cant Importe',
            '收货人         电话        地址',
            '收貨人         電話        地址',
            'Contact        Phone       Address',
            'Contacto        Teléfono    Dirección',
            '快递公司                    单号',
            '快遞公司                    單號',
            'Courier                      Tracking No.',
            'Mensajería                   Nº seguimiento',
            '服务人员       电话        备注',
            '服務人員       電話        備註',
            'Staff          Phone       Note',
            'Personal        Teléfono    Nota'
        ];
        if (builtins.indexOf(header) >= 0) {
            return current;
        }
        return header;
    };

    // 简洁预览行：地址/快递/服务拼全部列；商品仅标题+数量
    TicketPrintDesigner.prototype.composeItemsPreviewLine = function (item, field, mode) {
        item = item || {};
        var name = item.name || item.title || '';
        var price = item.price || '';
        var num = item.num || '';
        var total = item.total || '';
        if (mode === 'simple') {
            if (['address_data', 'express_data', 'service_data'].indexOf(field) !== -1) {
                return [name, price, num, total].filter(function (v) { return v !== ''; }).join(' ');
            }
            return name + (num ? '（' + num + '）' : '');
        }
        return [name, price, num, total].filter(function (v) { return v !== ''; }).join('  ');
    };

    TicketPrintDesigner.prototype.addElement = function (payload, insertAt) {
        if (payload.type === 'qr' && this.hasType('qr')) {
            Prompt(this.lang.qr_only_one || '');
            return;
        }
        var mode = payload.mode || (payload.type === 'items' ? 'table' : '');
        var header = '';
        if (payload.type === 'items') {
            header = (payload.header != null && payload.header !== '')
                ? payload.header
                : this.defaultItemsHeader(payload.field || '', mode);
        }
        var el = {
            id: uid(),
            type: payload.type || 'text',
            field: payload.field || '',
            title: payload.title || '',
            label: payload.label || '',
            custom_text: payload.custom ? '' : '',
            align: payload.align || 'left',
            size: payload.size || (payload.type === 'items' && mode === 'simple' ? 'B' : 'normal'),
            bold: payload.bold || 0,
            hide_empty: 1,
            tall: 0,
            wide: 0,
            mode: mode,
            header: header
        };
        var list = this.tpl().elements;
        if (typeof insertAt === 'number' && insertAt >= 0 && insertAt < list.length) {
            list.splice(insertAt, 0, el);
            this.selected = insertAt;
        } else {
            list.push(el);
            this.selected = list.length - 1;
        }
        this.renderCanvas();
        this.renderSetting();
        this.sync();
    };

    TicketPrintDesigner.prototype.render = function () {
        this.renderToolbar();
        this.renderCanvas();
        this.renderSetting();
    };

    TicketPrintDesigner.prototype.renderToolbar = function () {
        var self = this;
        var html = '';
        if (this.templateKeys.length) {
            html += '<div class="am-btn-group am-btn-group-xs">';
            $.each(this.templateKeys, function (_, item) {
                var key = String(item.value);
                html += '<button type="button" class="am-btn am-radius ticket-print-key-btn ' + (self.currentKey === key ? 'am-btn-primary' : 'am-btn-default-plain') + '" data-key="' + key + '">' + (item.name || key) + '</button>';
            });
            html += '</div>';
        }
        var width = this.tpl().width || 58;
        html += '<div class="am-btn-group am-btn-group-xs ticket-print-width-group">';
        html += '<button type="button" class="am-btn am-radius am-btn-xs ticket-print-width-btn ' + (parseInt(width, 10) === 58 ? 'am-btn-primary' : 'am-btn-default') + '" data-width="58">58mm</button>';
        html += '<button type="button" class="am-btn am-radius am-btn-xs ticket-print-width-btn ' + (parseInt(width, 10) === 80 ? 'am-btn-primary' : 'am-btn-default') + '" data-width="80">80mm</button>';
        html += '</div>';
        html += '<button type="button" class="am-btn am-btn-danger-plain am-radius am-btn-xs ticket-print-clear-btn">' + (this.lang.form_panel_clear || '') + '</button>';
        // 默认模板：1 套直接按钮；多套下拉选择
        var defaults = this.getDefaultTemplateList();
        if (defaults.length === 1) {
            html += '<button type="button" class="am-btn am-btn-default-plain am-radius am-btn-xs ticket-print-reset-btn">' + (this.lang.reset_default || '') + '</button>';
        } else if (defaults.length > 1) {
            html += '<div class="am-dropdown ticket-print-reset-dropdown" data-am-dropdown>';
            html += '<button type="button" class="am-btn am-btn-default-plain am-radius am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>';
            html += (this.lang.reset_default || '') + ' <span class="am-icon-caret-down"></span>';
            html += '</button>';
            html += '<ul class="am-dropdown-content am-radius">';
            $.each(defaults, function (_, item) {
                html += '<li><a href="javascript:;" class="ticket-print-reset-item" data-key="' + self.esc(item.key) + '">' + self.esc(item.name) + '</a></li>';
            });
            html += '</ul></div>';
        }
        this.$root.find('.ticket-print-toolbar').html(html);
        if (defaults.length > 1 && $.fn.dropdown) {
            this.$root.find('.ticket-print-reset-dropdown').dropdown();
        }
    };

    /**
     * 规范化默认模板列表
     * 支持：
     * 1) 数组 [{key,name,width,elements}, ...]
     * 2) 对象 {key: {width,elements}, ...}（兼容旧写法，按指纹去重）
     */
    TicketPrintDesigner.prototype.getDefaultTemplateList = function () {
        var d = this.defaultTemplates || {};
        var list = [];
        var seen = {};
        var self = this;
        var pushItem = function (key, name, tpl) {
            if (!tpl || !$.isArray(tpl.elements)) {
                return;
            }
            var fingerprint = JSON.stringify(tpl.elements) + '|' + String(tpl.width || 58);
            if (seen[fingerprint]) {
                return;
            }
            seen[fingerprint] = 1;
            list.push({
                key: String(key),
                name: name || String(key),
                tpl: { width: tpl.width || 58, elements: clone(tpl.elements) }
            });
        };
        if ($.isArray(d)) {
            $.each(d, function (i, item) {
                if (!item || !item.elements) {
                    return;
                }
                var key = item.key != null ? item.key : (item.id != null ? item.id : i);
                var name = item.name || item.title || self.lang.reset_default || String(key);
                pushItem(key, name, { width: item.width, elements: item.elements });
            });
            return list;
        }
        // 对象：优先常用键顺序，再补其余键（同内容去重）
        var preferred = ['default', '0', '1', 'standard', 'simple'];
        var nameMap = {
            default: self.lang.reset_default || 'default',
            '0': self.lang.start_standard || '0',
            '1': self.lang.start_simple || '1',
            standard: self.lang.start_standard || 'standard',
            simple: self.lang.start_simple || 'simple'
        };
        $.each(preferred, function (_, key) {
            if (d[key] && d[key].elements) {
                pushItem(key, nameMap[key] || key, d[key]);
            }
        });
        $.each(d, function (key, tpl) {
            if (preferred.indexOf(String(key)) !== -1) {
                return;
            }
            if (tpl && tpl.elements) {
                pushItem(key, nameMap[key] || key, tpl);
            }
        });
        return list;
    };

    TicketPrintDesigner.prototype.hasDefaultTemplates = function () {
        return this.getDefaultTemplateList().length > 0;
    };

    TicketPrintDesigner.prototype.resolveDefaultTemplateKey = function () {
        var list = this.getDefaultTemplateList();
        if (!list.length) {
            return '';
        }
        var editId = this.listEditId != null ? String(this.listEditId) : '';
        var i;
        if (editId !== '') {
            for (i = 0; i < list.length; i++) {
                if (String(list[i].key) === editId) {
                    return list[i].key;
                }
            }
        }
        if (this.currentKey) {
            for (i = 0; i < list.length; i++) {
                if (String(list[i].key) === String(this.currentKey)) {
                    return list[i].key;
                }
            }
        }
        return list[0].key;
    };

    TicketPrintDesigner.prototype.resolveDefaultTemplate = function () {
        var key = this.resolveDefaultTemplateKey();
        var list = this.getDefaultTemplateList();
        var i;
        for (i = 0; i < list.length; i++) {
            if (String(list[i].key) === String(key)) {
                return list[i].tpl;
            }
        }
        return { width: 58, elements: [] };
    };

    TicketPrintDesigner.prototype.applyDefaultTemplate = function (key) {
        var list = this.getDefaultTemplateList();
        var tpl = { width: 58, elements: [] };
        var name = '';
        var i;
        for (i = 0; i < list.length; i++) {
            if (String(list[i].key) === String(key)) {
                tpl = list[i].tpl;
                name = list[i].name || '';
                break;
            }
        }
        this.templates[this.currentKey] = clone(tpl);
        this.selected = -1;
        this.render();
        this.sync();
        Prompt((this.lang.reset_default_success || '') + (name ? '（' + name + '）' : ''), 'success');
    };

    TicketPrintDesigner.prototype.renderCanvas = function () {
        var self = this;
        var tpl = this.tpl();
        var $ticket = this.$root.find('.ticket-print-ticket');
        $ticket.toggleClass('is-80', parseInt(tpl.width, 10) === 80);
        var html = '';
        $.each(tpl.elements || [], function (index, el) {
            var cls = 'el';
            if (index === self.selected) {
                cls += ' is-active';
            }
            if (el.type === 'line') {
                cls += ' el-line';
            } else if (el.type === 'blank') {
                cls += ' el-blank';
            } else if (el.type === 'qr') {
                cls += ' el-qr';
            } else if (el.type === 'barcode') {
                cls += ' el-barcode';
            } else if (el.type === 'cut') {
                cls += ' el-cut';
            }
            if (el.align === 'center') {
                cls += ' is-center';
            }
            if (el.align === 'right') {
                cls += ' is-right';
            }
            self.normalizeElementStyle(el);
            if (el.size === 'B') {
                cls += ' is-b';
            }
            if (parseInt(el.bold, 10) === 1) {
                cls += ' is-bold';
            }
            if (parseInt(el.tall, 10) === 1) {
                cls += ' is-tall';
            }
            if (parseInt(el.wide, 10) === 1) {
                cls += ' is-wide';
            }
            html += '<div class="' + cls + '" draggable="true">';
            html += '<span class="el-remove">&times;</span>';
            if (el.type === 'qr') {
                html += '<span class="ticket-print-qr-box"><i></i><i></i><i></i></span>';
            } else if (el.type === 'barcode') {
                html += '<span class="ticket-print-barcode-box"></span>';
                html += '<div class="ticket-print-barcode-text">' + $('<div/>').text(self.previewText(el).replace(/^BAR\s*/, '') || 'BARCODE').html() + '</div>';
            } else if (el.type === 'line') {
                html += '<span class="ticket-print-line"></span>';
            } else {
                html += $('<div/>').text(self.previewText(el)).html();
            }
            html += '</div>';
        });
        if (!html) {
            html = '<div class="am-text-center am-color-grey am-padding-lg">' + (this.lang.empty_canvas || '') + '</div>';
        }
        $ticket.html(html);
        this.updatePaletteState();
        if (this.selected >= 0 && this.tpl().elements[this.selected]) {
            this.locatePaletteItem(this.tpl().elements[this.selected], false);
        } else {
            this.clearPaletteLocate();
        }
    };

    TicketPrintDesigner.prototype.clearPaletteLocate = function () {
        this.$root.find('.ticket-print-palette .item').removeClass('is-shake');
    };

    /**
     * 选中画布元素后，左侧面板滚动到对应字段并抖动提示
     * @param {object} el
     * @param {boolean} shake 是否抖动提示
     */
    TicketPrintDesigner.prototype.locatePaletteItem = function (el, shake) {
        var $palette = this.$root.find('.ticket-print-palette');
        if (!$palette.length || !el) {
            return;
        }
        $palette.find('.item').removeClass('is-shake');
        var type = el.type || 'text';
        var field = String(el.field || '');
        var $items = $palette.find('.item').filter(function () {
            var $it = $(this);
            return String($it.attr('data-type') || 'text') === type
                && String($it.attr('data-field') || '') === field;
        });
        // 无绑定字段的自定义文本，优先匹配左侧「自定义文本」
        if ($items.length > 1 && type === 'text' && field === '') {
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
        // 若当前被搜索隐藏，清空搜索后再定位
        if ($target.hasClass('is-search-hidden')) {
            var $search = $palette.find('.ticket-print-palette-search-input');
            if ($search.length) {
                $search.val('');
                this.filterPalette('');
            }
        }
        var $scroll = $palette.find('.ticket-print-palette-list');
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

    // 左侧面板平滑滚动（结束后回调，便于再抖动）
    TicketPrintDesigner.prototype.animatePaletteScroll = function (el, toTop, done) {
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

    TicketPrintDesigner.prototype.filterPalette = function (keyword) {
        var kw = String(keyword || '').toLowerCase();
        var $list = this.$root.find('.ticket-print-palette-list');
        if (!$list.length) {
            $list = this.$root.find('.ticket-print-palette');
        }
        $list.find('.item').each(function () {
            var $item = $(this);
            var title = String($item.attr('data-title') || $item.text() || '');
            var field = String($item.attr('data-field') || '');
            var label = String($item.attr('data-label') || '');
            var hit = !kw
                || title.toLowerCase().indexOf(kw) >= 0
                || field.toLowerCase().indexOf(kw) >= 0
                || label.toLowerCase().indexOf(kw) >= 0;
            $item.toggleClass('is-search-hidden', !hit);
        });
        $list.find('.group-title').each(function () {
            var $title = $(this);
            var visible = $title.nextUntil('.group-title, .ticket-print-palette-search-empty').filter('.item:not(.is-search-hidden)').length > 0;
            $title.toggleClass('is-search-hidden', !visible);
        });
        var any = $list.find('.item:not(.is-search-hidden)').length > 0;
        $list.find('.ticket-print-palette-search-empty').toggleClass('am-hide', any || !kw);
    };

    TicketPrintDesigner.prototype.updatePaletteState = function () {
        var counts = {};
        $.each(this.tpl().elements || [], function (_, el) {
            var key = (el.type || 'text') + '\n' + String(el.field || '');
            counts[key] = (counts[key] || 0) + 1;
        });
        var hasQr = !!counts['qr\n'] || this.hasType('qr');
        this.$root.find('.ticket-print-palette .item').each(function () {
            var $item = $(this);
            var type = $item.attr('data-type') || 'text';
            var field = $item.attr('data-field') || '';
            if (type === 'qr') {
                $item.toggleClass('is-disabled', hasQr);
            }
            var n = counts[type + '\n' + field] || 0;
            var $badge = $item.children('.ticket-print-use-count');
            if (!$badge.length) {
                $badge = $('<span class="ticket-print-use-count"></span>');
                $item.append($badge);
            }
            if (n > 0) {
                $badge.text(n).show();
            } else {
                $badge.hide();
            }
        });
    };

    TicketPrintDesigner.prototype.removeElement = function (index) {
        if (index < 0 || !this.tpl().elements[index]) {
            return;
        }
        this.tpl().elements.splice(index, 1);
        this.selected = -1;
        this.clearPaletteLocate();
        this.renderCanvas();
        this.renderSetting();
        this.sync();
    };

    TicketPrintDesigner.prototype.renderSetting = function () {
        var $box = this.$root.find('.ticket-print-setting');
        if (this.selected < 0 || !this.tpl().elements[this.selected]) {
            $box.html('<div class="empty-tips">' + (this.lang.form_panel_setting_tips || '') + '</div>');
            return;
        }
        var el = this.tpl().elements[this.selected];
        if (!el.title) {
            el.title = this.elementTitle(el);
        }
        var html = '';
        html += '<div class="am-form-group"><label>' + (this.lang.prop_title || '') + '</label><div>' + this.esc(this.elementTitle(el)) + '</div></div>';
        if (this.isFormatType(el.type)) {
            html += this.formatTabsHtml(el.type || 'text');
        }
        if (el.field) {
            html += '<div class="am-form-group"><label>' + (this.lang.prop_bind_field || '') + '</label><div>' + this.esc(el.field) + '</div></div>';
        }
        if ((el.type === 'text' || el.type === 'barcode' || el.type === 'qr') && !el.field) {
            html += '<div class="am-form-group"><label>' + (this.lang.prop_custom_text || '') + '</label><input type="text" class="am-form-field am-radius am-input-sm" data-prop="custom_text" placeholder="' + this.esc(this.lang.prop_custom_text) + '" value="' + this.esc(el.custom_text) + '" /></div>';
        } else if (el.type === 'text') {
            html += '<div class="am-form-group"><label>' + (this.lang.prop_label || '') + '</label><input type="text" class="am-form-field am-radius am-input-sm" data-prop="label" placeholder="' + this.esc(this.lang.prop_label) + '" value="' + this.esc(el.label) + '" /></div>';
        }
        if (el.type === 'text') {
            this.normalizeElementStyle(el);
            html += this.radioHtml('align', el.align || 'left', [
                { value: 'left', name: this.lang.align_left },
                { value: 'center', name: this.lang.align_center },
                { value: 'right', name: this.lang.align_right }
            ], this.lang.prop_align);
            html += this.checkListHtml(this.lang.prop_size, [
                { prop: 'size_amplify', checked: el.size === 'B', name: this.lang.size_b },
                { prop: 'bold', checked: el.bold == 1, name: this.lang.size_bold },
                { prop: 'tall', checked: el.tall == 1, name: this.lang.prop_tall },
                { prop: 'wide', checked: el.wide == 1, name: this.lang.prop_wide }
            ]);
            html += '<div class="am-form-group"><label class="am-checkbox"><input type="checkbox" data-prop="hide_empty" ' + (el.hide_empty == 1 ? 'checked' : '') + ' data-am-ucheck /> ' + (this.lang.prop_hide_empty || '') + '</label></div>';
        }
        if (el.type === 'barcode' || el.type === 'qr') {
            html += '<div class="am-form-group"><label class="am-checkbox"><input type="checkbox" data-prop="hide_empty" ' + (el.hide_empty == 1 ? 'checked' : '') + ' data-am-ucheck /> ' + (this.lang.prop_hide_empty || '') + '</label></div>';
        }
        if (el.type === 'items') {
            this.normalizeElementStyle(el);
            html += this.radioHtml('mode', el.mode || 'table', [
                { value: 'table', name: this.lang.items_mode_table },
                { value: 'simple', name: this.lang.items_mode_simple }
            ], this.lang.prop_items_mode);
            html += '<div class="am-form-group"><label>' + (this.lang.prop_items_header || '') + '</label><input type="text" class="am-form-field am-radius am-input-sm" data-prop="header" placeholder="' + this.esc(this.lang.prop_items_header) + '" value="' + this.esc(el.header) + '" /></div>';
            html += this.checkListHtml(this.lang.prop_size, [
                { prop: 'size_amplify', checked: el.size === 'B', name: this.lang.size_b },
                { prop: 'bold', checked: el.bold == 1, name: this.lang.size_bold }
            ]);
        }
        html += '<button type="button" class="am-btn am-btn-danger am-btn-xs am-radius ticket-print-delete">' + this.esc(this.lang.remove || '删除组件') + '</button>';
        $box.html(html);
        if ($.fn.uCheck) {
            $box.find('[data-am-ucheck]').uCheck();
        }
    };

    TicketPrintDesigner.prototype.formatTabsHtml = function (value) {
        var options = [
            { value: 'text', name: this.lang.format_text },
            { value: 'barcode', name: this.lang.format_barcode },
            { value: 'qr', name: this.lang.format_qr }
        ];
        var html = '<div class="ticket-print-format-box">';
        html += '<div class="ticket-print-format-title">' + this.esc(this.lang.prop_format || '') + '</div>';
        html += '<ul class="ticket-print-format-tabs">';
        $.each(options, function (_, item) {
            html += '<li class="item' + (String(value) === String(item.value) ? ' am-active' : '') + '" data-type="' + item.value + '">' + (item.name || item.value) + '</li>';
        });
        html += '</ul></div>';
        return html;
    };

    TicketPrintDesigner.prototype.normalizeElementStyle = function (el) {
        if (!el) {
            return el;
        }
        var size = el.size || 'normal';
        if (size === 'CB') {
            el.size = 'B';
            if (el.align !== 'right') {
                el.align = 'center';
            }
        } else if (size === 'BB') {
            el.size = 'B';
            el.bold = 1;
        } else if (size === 'BOLD') {
            el.size = 'normal';
            el.bold = 1;
        } else if (size !== 'B') {
            el.size = 'normal';
        }
        el.bold = parseInt(el.bold, 10) === 1 ? 1 : 0;
        el.tall = parseInt(el.tall, 10) === 1 ? 1 : 0;
        el.wide = parseInt(el.wide, 10) === 1 ? 1 : 0;
        return el;
    };

    TicketPrintDesigner.prototype.checkListHtml = function (label, options) {
        var html = '<div class="am-form-group ticket-print-check-group"><label>' + (label || '') + '</label><div class="ticket-print-check-list">';
        $.each(options || [], function (_, item) {
            html += '<label class="am-checkbox-inline"><input type="checkbox" data-prop="' + item.prop + '" ' + (item.checked ? 'checked' : '') + ' data-am-ucheck /> ' + (item.name || '') + '</label>';
        });
        html += '</div></div>';
        return html;
    };

    TicketPrintDesigner.prototype.radioHtml = function (prop, value, options, label, extras) {
        var name = 'feie-' + prop + '-' + (this.$root.data('feie-radio') || '');
        if (!this.$root.data('feie-radio')) {
            this.$root.data('feie-radio', uid());
            name = 'feie-' + prop + '-' + this.$root.data('feie-radio');
        }
        var html = '<div class="am-form-group ticket-print-radio-group"><label>' + (label || '') + '</label><div class="ticket-print-radio-list">';
        $.each(options, function (_, item) {
            html += '<label class="am-radio-inline"><input type="radio" name="' + name + '" data-prop="' + prop + '" value="' + item.value + '" ' + (String(value) === String(item.value) ? 'checked' : '') + ' data-am-ucheck /> ' + (item.name || item.value) + '</label>';
        });
        html += '</div>';
        if (extras && extras.length) {
            html += '<div class="ticket-print-check-list">';
            $.each(extras, function (_, item) {
                html += '<label class="am-checkbox-inline"><input type="checkbox" data-prop="' + item.prop + '" ' + (item.checked ? 'checked' : '') + ' data-am-ucheck /> ' + (item.name || '') + '</label>';
            });
            html += '</div>';
        }
        html += '</div>';
        return html;
    };

    TicketPrintDesigner.prototype.selectHtml = function (prop, value, options, label) {
        var html = '<div class="am-form-group"><label>' + (label || '') + '</label><select class="am-form-field am-radius am-input-sm" data-prop="' + prop + '">';
        $.each(options, function (_, item) {
            html += '<option value="' + item.value + '" ' + (String(value) === String(item.value) ? 'selected' : '') + '>' + (item.name || item.value) + '</option>';
        });
        html += '</select></div>';
        return html;
    };

    TicketPrintDesigner.prototype.esc = function (str) {
        return $('<div/>').text(str == null ? '' : String(str)).html();
    };

    TicketPrintDesigner.prototype.loadJson = function (tpl) {
        this.templates = { default: clone(tpl || { width: 58, elements: [] }) };
        if (!this.templates.default.elements) {
            this.templates.default.elements = [];
        }
        this.currentKey = 'default';
        this.selected = -1;
        this.render();
        this.sync();
    };

    TicketPrintDesigner.prototype.getJson = function () {
        var self = this;
        var tpl = clone(this.tpl());
        $.each(tpl.elements || [], function (_, el) {
            self.normalizeElementStyle(el);
        });
        return tpl;
    };

    TicketPrintDesigner.prototype.sync = function () {
        var self = this;
        $.each(this.templates || {}, function (_, tpl) {
            if (tpl && tpl.elements) {
                $.each(tpl.elements, function (__, el) {
                    self.normalizeElementStyle(el);
                });
            }
        });
        var payload;
        if (this.templateKeys.length) {
            payload = this.templates;
        } else {
            payload = this.templates.default || this.tpl();
        }
        this.$input.val(JSON.stringify(payload));
    };

    function initAll() {
        $('.ticket-print-designer').each(function () {
            if (!this._ticketPrintDesigner) {
                this._ticketPrintDesigner = new TicketPrintDesigner(this);
            }
        });
        $('.ticket-print-list-box').each(function () {
            if (!this._ticketPrintTemplateList) {
                this._ticketPrintTemplateList = new TicketPrintTemplateList(this);
            }
        });
    }

    function TicketPrintTemplateList(root) {
        this.$root = $(root);
        this.$input = this.$root.find('.ticket-print-list-input');
        this.$list = this.$root.find('.ticket-print-template-list');
        this.$popup = $('#ticket-print-template-popup');
        this.editId = '';
        var designerEl = this.$popup.find('.ticket-print-designer')[0];
        this.designer = designerEl ? designerEl._ticketPrintDesigner : null;
        this.lang = this.designer ? this.designer.lang : {};
        this.defaults = this.designer ? this.designer.defaultTemplates : {};
        this.list = this.parse();
        this.bind();
        this.render();
        this.sync();
        this.refreshPrinterSelects();
    }

    TicketPrintTemplateList.prototype.parse = function () {
        var raw = $.trim(this.$input.val() || '');
        var data = [];
        if (raw) {
            try {
                data = JSON.parse(raw);
            } catch (e) {
                data = [];
            }
        }
        if (!$.isArray(data)) {
            var list = [];
            $.each(data, function (id, item) {
                if (!item || !item.elements) {
                    return;
                }
                list.push({
                    id: String(item.id != null ? item.id : id),
                    name: item.name || String(id),
                    width: item.width || 58,
                    elements: item.elements || []
                });
            });
            data = list;
        }
        return data;
    };

    TicketPrintTemplateList.prototype.bind = function () {
        var self = this;
        this.$root.on('click', '.ticket-print-template-add', function () {
            self.list.push({
                id: 'tpl' + Date.now().toString(36) + Math.random().toString(36).slice(2, 6),
                name: '',
                width: 58,
                elements: []
            });
            self.render();
            self.sync();
            self.refreshPrinterSelects();
        });
        this.$root.on('click', '.ticket-print-template-edit', function () {
            self.openPopup(String($(this).data('id')));
        });
        this.$root.on('click', '.ticket-print-template-del', function () {
            var id = String($(this).data('id'));
            self.list = $.grep(self.list, function (item) {
                return String(item.id) !== id;
            });
            self.render();
            self.sync();
            self.refreshPrinterSelects();
        });
        this.$root.on('input', '.ticket-print-template-name-input', function () {
            var id = String($(this).data('id'));
            var name = $(this).val();
            $.each(self.list, function (i, item) {
                if (String(item.id) === id) {
                    self.list[i].name = name;
                }
            });
            self.sync();
            self.refreshPrinterSelects();
        });
        this.$popup.on('click', '.ticket-print-template-confirm', function () {
            self.confirmPopup();
        });
    };

    TicketPrintTemplateList.prototype.openPopup = function (id) {
        this.editId = id || '';
        var tpl = { width: 58, elements: [] };
        if (this.editId) {
            $.each(this.list, function (_, item) {
                if (String(item.id) === String(id)) {
                    tpl = item;
                }
            });
        }
        if (this.designer) {
            // 供「恢复默认」按模板 id（0 标准 / 1 简洁）取对应默认配置
            this.designer.listEditId = this.editId;
            this.designer.loadJson(tpl);
        }
        this.$popup.modal({ closeViaDimmer: false });
    };

    TicketPrintTemplateList.prototype.confirmPopup = function () {
        if (!this.editId) {
            this.$popup.modal('close');
            return;
        }
        var json = this.designer ? this.designer.getJson() : { width: 58, elements: [] };
        var self = this;
        $.each(this.list, function (i, item) {
            if (String(item.id) === String(self.editId)) {
                json.id = item.id;
                json.name = item.name || '';
                self.list[i] = json;
            }
        });
        this.sync();
        this.$popup.modal('close');
    };

    TicketPrintTemplateList.prototype.render = function () {
        var self = this;
        var html = '';
        if (!this.list.length) {
            html = '<li class="ticket-print-list-empty am-padding-sm">' + (this.lang.list_empty || '') + '</li>';
        } else {
            $.each(this.list, function (_, item) {
                html += '<li class="am-padding-xs am-flex am-flex-justify-between am-flex-items-center am-gap-1">';
                html += '<input type="text" class="am-form-field am-radius am-input-sm ticket-print-template-name-input" maxlength="30" data-id="' + item.id + '" value="' + String(item.name || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;') + '" placeholder="' + (self.lang.prop_name || '') + '" />';
                html += '<span class="am-flex-shrink-0">';
                html += '<button type="button" class="am-btn am-btn-default-plain am-btn-xs am-radius ticket-print-template-edit" data-id="' + item.id + '">' + (self.lang.edit_title || '') + '</button> ';
                html += '<button type="button" class="am-btn am-btn-danger-plain am-btn-xs am-radius ticket-print-template-del" data-id="' + item.id + '">' + (self.lang.remove_title || '') + '</button>';
                html += '</span></li>';
            });
        }
        this.$list.html(html);
    };

    TicketPrintTemplateList.prototype.sync = function () {
        this.$input.val(JSON.stringify(this.list));
    };

    TicketPrintTemplateList.prototype.refreshPrinterSelects = function () {
        var options = this.list;
        $('select.printer-template-select').each(function () {
            var $select = $(this);
            var current = String($select.val() || '');
            var html = '';
            $.each(options, function (_, item) {
                html += '<option value="' + item.id + '">' + $('<div/>').text(item.name || item.id).html() + '</option>';
            });
            $select.html(html);
            if (current && $select.find('option[value="' + current + '"]').length) {
                $select.val(current);
            }
            $select.trigger('chosen:updated');
        });
    };

    window.TicketPrintDesignerInit = initAll;
})(window, window.jQuery);
