/**
 * 多语言数据组件（后台）
 * @use    表单标记
 *         <form data-i18n-table="goods">                                          表单对应的业务表名
 *         <form data-i18n-table="plugins_config" data-i18n-plugins="shop">         插件表单需带插件标识（按需读/写）
 *         <input data-i18n="title">                                               可翻译的普通字段
 *         <input data-i18n="1" data-i18n-default="默认文案">                      输入为空时弹窗默认值用该属性
 *         <input data-i18n="1" data-i18n-label="字段标题">                         弹窗字段标题（优先于表单label）
 *         <textarea data-i18n="content_web" data-i18n-type="editor">              可翻译的富文本字段
 *         <ul data-i18n-dynamic="content_app_text">                               动态列表容器（可增删排序）
 *         容器内条目字段 <textarea data-i18n="content_app_text">                    按条目顺序以 字段名_索引 存储
 * @desc   弹窗外壳在 footer 公共模板（#common-i18n-modal），本文件只负责打开/关闭与字段区拼接
 *         插件可通过钩子 plugins_view_admin_i18n_popup_top/bottom（后台）
 *         或 plugins_view_index_i18n_popup_top/bottom（用户端）插入按钮/内容，再用 window.$I18nPopup 填值
 *         每个可翻译字段后跟随常驻语言按钮（不悬浮、手机可直接点按）
 *         弹窗普通字段所有语言直接铺开成行（左语言名、右输入框），编辑器字段顶部语言切换
 * @author  Devil
 * @blog    http://gong.gg/
 */
(function ()
{
    // 配置（由 footer.html 注入）
    window.$I18nVersion = 'v3-popup-footer';
    var config = window.$I18nConfig || null;
    if(!config || !config.language_list || config.language_list.length <= 0)
    {
        return;
    }

    // 弹窗对象
    var $modal = null;

    // 当前打开的字段信息
    var current = {
        form: null,        // 表单jquery对象
        field: '',         // 存储字段名（动态列表为 字段名_运行时标识）
        label: '',         // 字段标题
        type: 'text',      // 字段类型（text、editor）
        tag: 'input',      // 主表单控件类型（input单行、textarea多行）
        rows: 4,           // 主表单textarea行数
        is_dynamic: false, // 是否动态列表字段
        base_value: ''     // 主表单当前值
    };


    /**
     * md5（UTF-8、与PHP md5结果一致）
     * 动态字段以内容md5为存储键、条目增删排序不串数据、内容修改则翻译自然失效回退原值
     */

function Md5(str)
{
    function add32(a, b) { return (a + b) & 0xFFFFFFFF; }
    function cmn(q, a, b, x, s, t) { return add32(bitRol(add32(add32(a, q), add32(x, t)), s), b); }
    function ff(a, b, c, d, x, s, t) { return cmn((b & c) | (~b & d), a, b, x, s, t); }
    function gg(a, b, c, d, x, s, t) { return cmn((d & b) | (~d & c), a, b, x, s, t); }
    function hh(a, b, c, d, x, s, t) { return cmn(b ^ c ^ d, a, b, x, s, t); }
    function ii(a, b, c, d, x, s, t) { return cmn(c ^ (b | ~d), a, b, x, s, t); }
    function md51(s)
    {
        var n = s.length, state = [1732584193, -271733879, -1732584194, 271733878], i;
        for(i = 64; i <= s.length; i += 64) { md5cycle(state, md5blk(s.substring(i - 64, i))); }
        s = s.substring(i - 64);
        var tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for(i = 0; i < s.length; i++) { tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3); }
        tail[i >> 2] |= 0x80 << ((i % 4) << 3);
        if(i > 55) { md5cycle(state, tail); for(i = 0; i < 16; i++) { tail[i] = 0; } }
        tail[14] = n * 8;
        md5cycle(state, tail);
        return state;
    }
    function md5blk(s)
    {
        var md5blks = [], i;
        for(i = 0; i < 64; i += 4) { md5blks[i >> 2] = s.charCodeAt(i) + (s.charCodeAt(i + 1) << 8) + (s.charCodeAt(i + 2) << 16) + (s.charCodeAt(i + 3) << 24); }
        return md5blks;
    }
    function md5cycle(x, k)
    {
        var a = x[0], b = x[1], c = x[2], d = x[3];
        a = ff(a, b, c, d, k[0], 7, -680876936); d = ff(d, a, b, c, k[1], 12, -389564586); c = ff(c, d, a, b, k[2], 17, 606105819); b = ff(b, c, d, a, k[3], 22, -1044525330);
        a = ff(a, b, c, d, k[4], 7, -176418897); d = ff(d, a, b, c, k[5], 12, 1200080426); c = ff(c, d, a, b, k[6], 17, -1473231341); b = ff(b, c, d, a, k[7], 22, -45705983);
        a = ff(a, b, c, d, k[8], 7, 1770035416); d = ff(d, a, b, c, k[9], 12, -1958414417); c = ff(c, d, a, b, k[10], 17, -42063); b = ff(b, c, d, a, k[11], 22, -1990404162);
        a = ff(a, b, c, d, k[12], 7, 1804603682); d = ff(d, a, b, c, k[13], 12, -40341101); c = ff(c, d, a, b, k[14], 17, -1502002290); b = ff(b, c, d, a, k[15], 22, 1236535329);
        a = gg(a, b, c, d, k[1], 5, -165796510); d = gg(d, a, b, c, k[6], 9, -1069501632); c = gg(c, d, a, b, k[11], 14, 643717713); b = gg(b, c, d, a, k[0], 20, -373897302);
        a = gg(a, b, c, d, k[5], 5, -701558691); d = gg(d, a, b, c, k[10], 9, 38016083); c = gg(c, d, a, b, k[15], 14, -660478335); b = gg(b, c, d, a, k[4], 20, -405537848);
        a = gg(a, b, c, d, k[9], 5, 568446438); d = gg(d, a, b, c, k[14], 9, -1019803690); c = gg(c, d, a, b, k[3], 14, -187363961); b = gg(b, c, d, a, k[8], 20, 1163531501);
        a = gg(a, b, c, d, k[13], 5, -1444681467); d = gg(d, a, b, c, k[2], 9, -51403784); c = gg(c, d, a, b, k[7], 14, 1735328473); b = gg(b, c, d, a, k[12], 20, -1926607734);
        a = hh(a, b, c, d, k[5], 4, -378558); d = hh(d, a, b, c, k[8], 11, -2022574463); c = hh(c, d, a, b, k[11], 16, 1839030562); b = hh(b, c, d, a, k[14], 23, -35309556);
        a = hh(a, b, c, d, k[1], 4, -1530992060); d = hh(d, a, b, c, k[4], 11, 1272893353); c = hh(c, d, a, b, k[7], 16, -155497632); b = hh(b, c, d, a, k[10], 23, -1094730640);
        a = hh(a, b, c, d, k[13], 4, 681279174); d = hh(d, a, b, c, k[0], 11, -358537222); c = hh(c, d, a, b, k[3], 16, -722521979); b = hh(b, c, d, a, k[6], 23, 76029189);
        a = hh(a, b, c, d, k[9], 4, -640364487); d = hh(d, a, b, c, k[12], 11, -421815835); c = hh(c, d, a, b, k[15], 16, 530742520); b = hh(b, c, d, a, k[2], 23, -995338651);
        a = ii(a, b, c, d, k[0], 6, -198630844); d = ii(d, a, b, c, k[7], 10, 1126891415); c = ii(c, d, a, b, k[14], 15, -1416354905); b = ii(b, c, d, a, k[5], 21, -57434055);
        a = ii(a, b, c, d, k[12], 6, 1700485571); d = ii(d, a, b, c, k[3], 10, -1894986606); c = ii(c, d, a, b, k[10], 15, -1051523); b = ii(b, c, d, a, k[1], 21, -2054922799);
        a = ii(a, b, c, d, k[8], 6, 1873313359); d = ii(d, a, b, c, k[15], 10, -30611744); c = ii(c, d, a, b, k[6], 15, -1560198380); b = ii(b, c, d, a, k[13], 21, 1309151649);
        a = ii(a, b, c, d, k[4], 6, -145523070); d = ii(d, a, b, c, k[11], 10, -1120210379); c = ii(c, d, a, b, k[2], 15, 718787259); b = ii(b, c, d, a, k[9], 21, -343485551);
        x[0] = add32(a, x[0]); x[1] = add32(b, x[1]); x[2] = add32(c, x[2]); x[3] = add32(d, x[3]);
    }
    function bitRol(num, cnt) { return (num << cnt) | (num >>> (32 - cnt)); }
    function rhex(n)
    {
        var s = '', j;
        for(j = 0; j < 4; j++) { s += ((n >> (j * 8 + 4)) & 0x0F).toString(16) + ((n >> (j * 8)) & 0x0F).toString(16); }
        return s;
    }
    // UTF-8编码后计算（与PHP md5中文结果一致）
    var utf8 = unescape(encodeURIComponent('' + str));
    var arr = md51(utf8);
    return rhex(arr[0]) + rhex(arr[1]) + rhex(arr[2]) + rhex(arr[3]);
}

    /**
     * tagsinput标签输入位置合并：按原文顺序逐值取翻译拼逗号串（未翻译项跳过、消费的逐值键删除）
     */
    function TagsinputPositionalMerge(data, base, main_value)
    {
        var merged = {};
        var items = String(main_value || '').split(',');
        for(var i in items)
        {
            var item = items[i].trim();
            if(item === '')
            {
                continue;
            }
            var item_key = base + '_' + Md5Content(item);
            if(data[item_key] !== undefined)
            {
                for(var lang in data[item_key])
                {
                    if((data[item_key][lang] || '') !== '')
                    {
                        merged[lang] = (merged[lang] ? merged[lang] + ',' : '') + data[item_key][lang];
                    }
                }
                delete data[item_key];
            }
        }
        return merged;
    }

    /**
     * 表单状态（挂在表单对象上）
     * data      已录入的多语言数据 field => lang => value（动态字段为 字段名_运行时标识）
     * loaded_id 已加载数据的业务id（新增编辑弹窗复用表单时用于重置）
     * dirty     是否确认过弹窗（未确认过则不覆盖服务端数据）
     */
    function State($form)
    {
        var state = $form.data('i18n-state');
        if(!state)
        {
            state = {
                data: {},
                loaded_id: null,
                loaded_ids: {},
                dirty: false
            };
            $form.data('i18n-state', state);
        }
        return state;
    }

    /**
     * 动态条目运行时唯一标识（条目增删排序后标识保持、提交时再按最新顺序转成索引）
     */
    function RowUid($row)
    {
        var uid = $row.data('i18n-uid');
        if(!uid)
        {
            window.$I18nUidIndex = (window.$I18nUidIndex || 0) + 1;
            uid = 'r' + window.$I18nUidIndex;
            $row.data('i18n-uid', uid);
        }
        return uid;
    }

    /**
     * 业务id
     */
    function BusinessId($form)
    {
        var id = $form.attr('data-i18n-id');
        if(id === undefined || id === '')
        {
            var $id_input = $form.find('input[name="id"]');
            id = $id_input.length > 0 ? $id_input.val() : 0;
        }
        return parseInt(id || 0);
    }

    /**
     * 插件标识（仅插件表单 data-i18n-plugins）
     */
    function FormPlugins($form)
    {
        return (($form.attr('data-i18n-plugins') || '')+'').trim();
    }

    /**
     * 同步插件标识隐藏域（随表单提交写入 i18n_value.plugins）
     */
    function SyncPluginsHidden($form)
    {
        var plugins = FormPlugins($form);
        var $input = $form.find('input.i18n-plugins-input');
        if(plugins === '')
        {
            $input.remove();
            return;
        }
        if($input.length <= 0)
        {
            $input = $('<input type="hidden" name="i18n_plugins" class="i18n-plugins-input" />').appendTo($form);
        }
        $input.val(plugins);
    }

    /**
     * 字段级业务id（行表用行id；config/plugins_config 等内容池才用 0）
     */
    function FieldBusinessId($field, $form)
    {
        var fid = $field.attr('data-i18n-business-id');
        if(fid !== undefined && fid !== '')
        {
            return parseInt(fid);
        }
        return BusinessId($form);
    }

    /**
     * 字段存储名
     */
    // 动态字段基础名：name带索引括号取括号前（parameters_name[0]）、下划线拼接id的取容器声明（content_app_text_xxx）
    function DynamicFieldBase($field)
    {
        var name = $field.attr('name') || '';
        var bracket = name.indexOf('[');
        if(bracket > 0)
        {
            return name.substring(0, bracket);
        }
        var declare = $field.closest('[data-i18n-dynamic]').attr('data-i18n-dynamic');
        if(declare && declare != '1' && name.indexOf(declare + '_') === 0)
        {
            return declare;
        }
        return name;
    }

    // 动态行内按基础名找字段元素（同名双输入取可见的、如参数值单行/多行）
    function DynamicRowField($row, base)
    {
        var $f = $row.find('[data-i18n][name^="' + base + '["]');
        if($f.length <= 0)
        {
            $f = $row.find('[data-i18n][name^="' + base + '_"]');
        }
        var $visible = $f.filter(':visible');
        return ($visible.length > 0 ? $visible : $f).first();
    }

    // 内容规范化md5（换行统一\n并去首尾空白、与服务端ContentKey一致、textarea的\r\n不影响）
    function Md5Content(val)
    {
        return Md5(String(val === null || val === undefined ? '' : val).replace(/\r\n/g, '\n').replace(/\r/g, '\n').trim());
    }

    // 动态字段内容键：基础名_内容md5（顺序无关）
    function DynamicContentKey($row, base)
    {
        // 内容寻址的显式字段（元素自身即字段、如主题数据文本/自定义数据）：空内容不生成键（避免空值md5把全部空字段串起来）
        if($row.attr('data-i18n-content') !== undefined && $row.attr('data-i18n-field') == base)
        {
            var content_val = String($row.val() || '');
            if(content_val.trim() === '')
            {
                return null;
            }
            return base + '_' + Md5Content(content_val);
        }
        // 行内内容字段（元素自身即行）
        if($row.attr('data-i18n-name') == base)
        {
            return base + '_' + Md5Content($row.val());
        }
        var $f = DynamicRowField($row, base);
        return base + '_' + Md5Content($f.length > 0 ? $f.val() : '');
    }

    function FieldKey($field)
    {
        // 显式指定的字段存储键（如楼层关键字按分类指定：基础名_分类id）
        var explicit_field = $field.attr('data-i18n-field');
        if(explicit_field && explicit_field.trim() !== '')
        {
            // 内容寻址字段（如主题数据文本/自定义数据、同基础名多实例）：会话内按字段实例区分、保存时转内容键，避免多实例共用一个存储键互相串数据
            if($field.attr('data-i18n-content') !== undefined)
            {
                return {
                    key: explicit_field + '_' + RowUid($field),
                    field: explicit_field,
                    is_dynamic: true
                };
            }
            // 固定显式键（插件配置/系统配置键）：按存储键读写，不是内容寻址动态字段
            return {
                key: explicit_field,
                field: explicit_field,
                is_dynamic: false
            };
        }

        // 行内内容字段（如商品规格的规格名/规格值、以自身内容md5为存储键）
        var inline_base = $field.attr('data-i18n-name');
        if(inline_base)
        {
            return {
                key: inline_base + '_' + RowUid($field),
                field: inline_base,
                is_dynamic: true
            };
        }

        // 动态列表字段（行li或表格tr、支持一行多个字段、会话内以行标识为键）
        var $dynamic = $field.closest('[data-i18n-dynamic]');
        if($dynamic.length > 0)
        {
            var $row = $field.closest('li, tr');
            if($row.length > 0)
            {
                var base = DynamicFieldBase($field);
                return {
                    key: base + '_' + RowUid($row),
                    field: base,
                    is_dynamic: true
                };
            }
        }
        // 普通字段：字段名读name（data-i18n仅作标记）
        var field = $field.attr('name') || $field.attr('data-i18n');
        return {
            key: field,
            field: field,
            is_dynamic: false
        };
    }

    /**
     * 序列化多语言数据到表单隐藏域
     * 动态列表字段按条目最新顺序转成 字段名_索引（和服务端保存顺序一致）
     */
    function Serialize($form)
    {
        var state = State($form);
        if(!state.dirty)
        {
            return;
        }

        // 表单业务id已变化（如分类弹窗切换了编辑对象）、丢弃旧数据避免错存
        // 仅行id(>0)之间变化才重置、内容寻址键池(id=0)与行id混用的表单不受影响
        var now_id = BusinessId($form);
        if(now_id > 0 && state.loaded_id > 0 && state.loaded_id !== now_id)
        {
            ResetState($form);
            return;
        }

        // 动态行 运行时标识 => 行元素（内容键按行内字段当前值计算）
        var dynamic_rows = {};
        $form.find('[data-i18n-dynamic]').each(function ()
        {
            $(this).children('li, tr').each(function ()
            {
                dynamic_rows[RowUid($(this))] = $(this);
            });
        });

        // 行内内容字段（元素自身即行）
        $form.find('[data-i18n-name]').each(function ()
        {
            dynamic_rows[RowUid($(this))] = $(this);
        });

        // 内容寻址的显式字段（元素自身即字段、如主题数据文本/自定义数据）
        $form.find('[data-i18n-content]').each(function ()
        {
            dynamic_rows[RowUid($(this))] = $(this);
        });

        // 组装数据（确认过的字段始终保留key、值可全空、动态key由 基础名_r标识 转成 基础名_内容md5）
        // 空对象{}仅在从未确认过任何字段时出现、服务端跳过、避免误清空
        var data = {};
        for(var k in state.data)
        {
            var key = k;
            var uid_match = k.match(/^(.+)_r(\d+)$/);
            if(uid_match && dynamic_rows['r' + uid_match[2]] !== undefined)
            {
                key = DynamicContentKey(dynamic_rows['r' + uid_match[2]], uid_match[1]);
                if(key === null)
                {
                    // 空内容字段（如刚新增未填的行）、不生成内容键
                    continue;
                }
            }
            data[key] = state.data[k];
        }

        // plugins_config / config 等同 id=0 池：只提交当前表单字段，避免把其它页面/插件的翻译一并写入
        var form_bases = [];
        $form.find('[data-i18n]').each(function ()
        {
            var info = FieldKey($(this));
            if(info && info.field)
            {
                form_bases.push(info.field);
            }
        });
        if(form_bases.length > 0)
        {
            var scoped = {};
            for(var sk in data)
            {
                for(var bi = 0; bi < form_bases.length; bi++)
                {
                    if(sk === form_bases[bi] || sk.indexOf(form_bases[bi] + '_') === 0)
                    {
                        scoped[sk] = data[sk];
                        break;
                    }
                }
            }
            data = scoped;
        }

        // 隐藏域（未改动过的表单不生成、服务端不处理；确认过弹窗则始终生成、空对象表示清空）
        var $input = $form.find('input.i18n-data-input');
        if($input.length <= 0)
        {
            $input = $('<input type="hidden" name="i18n_data" class="i18n-data-input" />').appendTo($form);
        }
        $input.val(JSON.stringify(data));
        SyncPluginsHidden($form);

        // 弹窗子表单（如备案信息/自提地址行编辑、sync提交不走主表单）：数据合并进页面主表单、随主表单提交携带
        MergeToMainForm($form, data);
    }

    /**
     * 弹窗子表单数据合并进页面主表单（同业务表、非自身的第一个表单）
     * sync提交的子表单原生submit事件不触发、ConfirmPopup确认时与Serialize时双路调用保障
     */
    function MergeToMainForm($form, data)
    {
        if(!data || $.isEmptyObject(data))
        {
            return;
        }

        // 排除内容寻址/动态行的会话基础键（data-i18n-field 为基础名，会话键为 基础名_rN）
        // 固定显式键（如订单快递备注 note_{快递id}_{单号md5}）字段键即存储键，必须合并进主表单
        var session_keys = {};
        $('[data-i18n-field]').each(function()
        {
            var $el = $(this);
            if($el.attr('data-i18n-content') !== undefined || $el.attr('data-i18n-name') !== undefined || $el.closest('[data-i18n-dynamic]').length > 0)
            {
                session_keys[$el.attr('data-i18n-field')] = true;
            }
        });
        var clean = {};
        for(var mk in data)
        {
            if(session_keys[mk] || /_r\d+$/.test(mk))
            {
                continue;
            }
            clean[mk] = data[mk];
        }
        if($.isEmptyObject(clean))
        {
            return;
        }

        var $main_form = $('form[data-i18n-table="' + $form.attr('data-i18n-table') + '"]').not($form).first();
        if($main_form.length > 0)
        {
            var main_state = State($main_form);
            for(var ck in clean)
            {
                main_state.data[ck] = clean[ck];
            }
            main_state.dirty = true;

            // 业务id对齐（避免后续Serialize的id变化检查触发ResetState误清合并数据）
            main_state.loaded_id = BusinessId($main_form);

            // 主表单隐藏域同步写入（按钮click提交走GetFormVal读DOM、原生submit提交走state序列化、双路保障）
            var $hidden = $main_form.find('input.i18n-data-input');
            if($hidden.length <= 0)
            {
                $hidden = $('<input type="hidden" name="i18n_data" class="i18n-data-input" />').appendTo($main_form);
            }
            var cur = {};
            try
            {
                cur = JSON.parse($hidden.val() || '{}') || {};
            } catch(e) {}
            for(var hk in clean)
            {
                cur[hk] = clean[hk];
            }
            $hidden.val(JSON.stringify(cur));
            SyncPluginsHidden($main_form);
        }
    }

    /**
     * 重置表单状态（分类等弹窗复用同一个表单时）
     */
    function ResetState($form)
    {
        var state = State($form);
        state.data = {};
        state.loaded_id = null;
        state.loaded_ids = {};
        state.loaded_data_ids = {};
        state.dirty = false;
        $form.find('input.i18n-data-input').remove();
        $form.find('input.i18n-plugins-input').remove();
    }

    /**
     * 服务端返回的动态字段内容key（基础名_md5）转行运行时标识key（基础名_r1）
     * 按行内字段当前内容md5匹配、增删排序后仍能正确回显；未匹配的（内容已改或行已删）丢弃
     */
    function DynamicKeyToUid($form, data)
    {
        // 行内内容字段（元素自身即行）
        $form.find('[data-i18n-name]').each(function ()
        {
            var uid = RowUid($(this));
            var base = $(this).attr('data-i18n-name');
            if($(this).attr('data-am-tagsinput') !== undefined)
            {
                // tagsinput标签输入（逗号分割多值）：按原文顺序拼逗号串
                data[base + '_' + uid] = TagsinputPositionalMerge(data, base, $(this).val());
            } else {
                var content_key = base + '_' + Md5Content($(this).val());
                if(data[content_key] !== undefined)
                {
                    data[base + '_' + uid] = data[content_key];
                    delete data[content_key];
                }
            }
        });

        // 内容寻址的显式字段（如主题数据文本/自定义数据）：内容键转字段实例键
        // 共享基础键（不带md5）为历史版本遗留、任何实例弹窗都不该读到（多实例互相串数据）、直接丢弃
        $form.find('[data-i18n-content]').each(function ()
        {
            var base = $(this).attr('data-i18n-field');
            delete data[base];
            var val = String($(this).val() || '');
            if(val.trim() === '')
            {
                return;
            }
            var content_key = base + '_' + Md5Content(val);
            if(data[content_key] !== undefined)
            {
                data[base + '_' + RowUid($(this))] = data[content_key];
                delete data[content_key];
            }
        });

        $form.find('[data-i18n-dynamic]').each(function ()
        {
            $(this).children('li, tr').each(function ()
            {
                var uid = RowUid($(this));
                var $row = $(this);

                // 行内全部已标记动态字段（按基础名去重）
                var base_list = [];
                $row.find('[data-i18n]').each(function ()
                {
                    var base = DynamicFieldBase($(this));
                    if(base_list.indexOf(base) === -1)
                    {
                        base_list.push(base);
                    }
                });

                // 内容md5匹配服务端key
                for(var i in base_list)
                {
                    var content_key = DynamicContentKey($row, base_list[i]);
                    if(data[content_key] !== undefined)
                    {
                        data[base_list[i] + '_' + uid] = data[content_key];
                        delete data[content_key];
                    }
                }
            });
        });

        // 显式字段键的标签输入（如楼层关键字按分类）：逐值内容键按位置合并、转成该字段的会话键
        $form.find('[data-i18n-field]').each(function ()
        {
            var base = $(this).attr('data-i18n-field');
            if($(this).attr('data-am-tagsinput') !== undefined)
            {
                data[base] = TagsinputPositionalMerge(data, base, $(this).val());
            }
        });

        // 注意：未匹配实例的内容键不能在这里删除
        // 0池（business_id=0）为同表全局共享；服务端按本次提交的精确字段覆盖，加载到的键随序列化带回可同步同内容翻译
    }

    /**
     * 字段是否已有语言按钮（克隆节点会带上按钮但丢失 jquery data）
     */
    function ExistingI18nButton($field)
    {
        if($field.attr('data-i18n-type') == 'editor')
        {
            return $field.closest('.am-form-group').find('.i18n-field-btn').first();
        }
        var $wrap = $field.closest('.i18n-field-wrap');
        if($wrap.length > 0)
        {
            return $wrap.find('.i18n-field-btn').first();
        }
        return $field.next('.i18n-field-btn');
    }

    /**
     * 字段添加常驻语言按钮
     * 普通字段：按钮跟在输入框右侧（组合输入框追加为组合项、独立输入框包裹flex容器）
     * 编辑器字段：按钮放所属表单组右上角（原textarea会被编辑器隐藏）
     */
    function InitField($field)
    {
        $field.data('i18n-init', 1);

        // 禁止修改的字段不渲染按钮（如商品参数/规格锁定readonly、disabled）
        if($field.prop('readonly') || $field.prop('disabled'))
        {
            return;
        }

        // 日期选择字段不渲染按钮（值为日期非文案）
        if($field.hasClass('Wdate'))
        {
            return;
        }

        // 同行同名字段去重（仅参数值的单行/多行双输入这类异标签同逻辑字段、同标签多值输入如规格值每个都要按钮）
        var fname = $field.attr('name');
        var ftag = ($field.prop('tagName') || '').toLowerCase();
        var $same_row = $field.closest('li, tr').find('[data-i18n][name="' + fname + '"]').first();
        if(fname && $same_row.length > 0 && $same_row.get(0) !== $field.get(0) && ($same_row.prop('tagName') || '').toLowerCase() !== ftag)
        {
            return;
        }
        var $btn = $('<a href="javascript:;" class="i18n-field-btn" title="' + config.lang.popup_title + '"><i class="am-icon-language"></i></a>').data('field', $field);
        if($field.attr('data-i18n-type') == 'editor')
        {
            var $group = $field.closest('.am-form-group');
            if($group.length > 0 && $group.find('.i18n-field-btn').length <= 0)
            {
                $group.addClass('i18n-editor-group');
                $btn.appendTo($group);
            }
        } else {
            var $input_group = $field.closest('.am-input-group');
            if($input_group.length > 0)
            {
                // 组合输入框整体包裹、按钮放组合外部右侧（与独立输入框的按钮右边缘对齐）
                if($input_group.parent('.i18n-field-wrap').length > 0)
                {
                    $input_group.parent('.i18n-field-wrap').append($btn);
                } else {
                    $input_group.wrap('<div class="i18n-field-wrap am-flex am-flex-items-center am-gap-1-half"></div>').after($btn);
                }
            } else if($field.parent('.i18n-field-wrap').length > 0)
            {
                $field.parent('.i18n-field-wrap').append($btn);
            } else {
                var is_textarea = ($field.prop('tagName') == 'TEXTAREA');
                $field.wrap('<div class="i18n-field-wrap am-flex am-flex-items-flex-start am-gap-1-half"></div>');

                // tagsinput组件会把原输入框隐藏并在其后插入标签容器、把容器纳入同行再放语言按钮
                var $tagsinput = $field.parent('.i18n-field-wrap').next('.am-tagsinput');
                if($tagsinput.length > 0)
                {
                    $field.parent('.i18n-field-wrap').append($tagsinput);
                    $btn.insertAfter($tagsinput);
                } else {
                    $btn.insertAfter($field);
                }
            }
        }
    }

    /**
     * 扫描初始化（页面加载、动态新增字段自动处理）
     */
    function InitFields()
    {
        $('form[data-i18n-table]').find('[data-i18n]').each(function ()
        {
            var $field = $(this);
            if($field.closest('script').length > 0)
            {
                return;
            }
            if($field.data('i18n-init'))
            {
                return;
            }
            var $btn = ExistingI18nButton($field);
            if($btn.length > 0)
            {
                $field.data('i18n-init', 1);
                $btn.data('field', $field);
                return;
            }
            InitField($field);
        });
    }

    /**
     * 字段标题
     */
    function FieldLabel($field)
    {
        // 显式指定的标题优先（如协议页以顶部tab名称为标题）
        var explicit = $field.attr('data-i18n-label');
        if(explicit && explicit.trim() !== '')
        {
            return explicit.trim();
        }

        var $label = $field.closest('.am-form-group').find('label').first();
        var text = '';
        if($label.length > 0)
        {
            // 仅取label直接文本节点（右侧长描述、必填星号等子元素不参与）
            $label[0].childNodes.forEach(function(node)
            {
                if(node.nodeType === 3)
                {
                    text += node.nodeValue;
                }
            });
        }
        return text.replace(/\*+/g, '').trim() || $field.attr('name') || current.field;
    }

    /**
     * 打开弹窗
     * 先弹出再异步拉数据，避免首次打开卡在接口等待上
     */
    function OpenPopup($field)
    {
        if(!BindModal())
        {
            return;
        }
        var $form = $field.closest('form[data-i18n-table]');
        if($form.length <= 0)
        {
            return;
        }

        // 字段信息（控件类型与主表单保持一致）
        var info = FieldKey($field);
        current = {
            form: $form,
            field: info.field,
            key: info.key,
            field_el: $field,
            label: FieldLabel($field),
            type: $field.attr('data-i18n-type') || 'text',
            tag: (($field.prop('tagName') || 'INPUT') + '').toLowerCase(),
            rows: parseInt($field.attr('rows') || 0) > 0 ? parseInt($field.attr('rows')) : 4,
            is_dynamic: info.is_dynamic,
            is_tagsinput: $field.attr('data-am-tagsinput') !== undefined,
            base_value: GetMainValue($field),
            loading: false,
            request_seq: (current && current.request_seq ? current.request_seq : 0) + 1
        };
        var request_seq = current.request_seq;

        // 加载数据（内容寻址键字段可能指定business_id、与行字段分池存储）
        var state = State($form);
        var business_id = FieldBusinessId(current.field_el, $form);
        var show_popup = function (is_loading)
        {
            // 请求已过期（用户关闭后点了别的字段）则不再渲染
            if(!current || current.request_seq !== request_seq)
            {
                return;
            }
            current.loading = !!is_loading;

            // tagsinput字段：确认后current.key为逐值键形式、重开时按位置重建逗号串回显
            if(!is_loading && current.is_tagsinput && state.data[current.key] === undefined)
            {
                state.data[current.key] = TagsinputPositionalMerge(state.data, current.field, current.base_value);
            }

            // 动态/行内字段：无会话数据（如生成规格产生的新元素）时按内容键回显（同内容的已设数据自动带出）
            if(!is_loading && !current.is_tagsinput && current.is_dynamic && state.data[current.key] === undefined)
            {
                var fallback_key = current.field + '_' + Md5Content(current.base_value);
                var content_cache = current.form.data('i18n-content-cache') || {};
                if(content_cache[fallback_key] !== undefined)
                {
                    state.data[current.key] = $.extend(true, {}, content_cache[fallback_key]);
                } else if(state.data[fallback_key] !== undefined) {
                    state.data[current.key] = state.data[fallback_key];
                }
            }
            RenderPopup();
            if(!$modal.hasClass('am-modal-active'))
            {
                $modal.modal('open');
            }

            // 禁止点击背景关闭
            // 框架将关闭事件绑定在遮罩层元素上、且每次open都会重新绑定、故每次打开后解绑
            var modal_inst = $modal.data('amui.modal');
            if(modal_inst && modal_inst.dimmer && modal_inst.dimmer.$element)
            {
                modal_inst.dimmer.$element.off('click.dimmer.modal.amui');
            }

            // 加载中禁用确认，避免空数据误存
            $modal.find('.i18n-confirm').prop('disabled', !!is_loading);
        };
        // 行id(>0)之间变化才重置（分类等弹窗切换编辑对象）、内容寻址键池(id=0)与行id混用不受影响
        if(business_id > 0 && state.loaded_id > 0 && state.loaded_id !== business_id)
        {
            ResetState($form);
        }
        state.loaded_id = business_id;
        state.loaded_ids[business_id] = true;

        // 该业务id池首次打开则请求服务端（混合池表单会先后请求行id池和0池、已请求过的不重复）
        if(!state.loaded_data_ids || !state.loaded_data_ids[business_id])
        {
            // 商品表单：新增（id=0）也请求，回显严格按 table+business_id（不合并规格模板）
            // config/plugins_config：键值型无行id（id=0）
            // 行表（theme_data / plugins_vip_level 等）：即使有 data-i18n-content，也按行 id 存取，禁止仅因 content 标记去拉 0 池
            var table_name = $form.attr('data-i18n-table');
            if(business_id > 0 || table_name == 'goods' || table_name == 'plugins_supplier_goods' || table_name == 'plugins_realstore_system_goods' || table_name == 'config' || table_name == 'plugins_config')
            {
                state.loaded_data_ids = state.loaded_data_ids || {};
                state.loaded_data_ids[business_id] = true;
                // 先打开弹窗，接口返回后再填值（消除“点了半天才弹”的卡顿感）
                show_popup(true);
                var req_data = {table: table_name, id: business_id};
                var form_plugins = FormPlugins($form);
                if(form_plugins !== '')
                {
                    req_data.plugins = form_plugins;
                }
                $.ajax({
                    url: RequestUrlHandle(config.url),
                    type: 'POST',
                    dataType: 'json',
                    data: req_data,
                    success: function (res)
                    {
                        if(!current || current.request_seq !== request_seq)
                        {
                            return;
                        }
                        if(res.code == 0)
                        {
                            // 合并进会话数据（多池共存、不覆盖已确认的会话数据）
                            for(var lk in (res.data || {}))
                            {
                                if(state.data[lk] === undefined)
                                {
                                    state.data[lk] = res.data[lk];
                                }
                            }

                            // 内容键缓存（服务端数据原样留存、生成规格等新元素按内容回显用）
                            var $cache = $form.data('i18n-content-cache') || {};
                            for(var ck2 in (res.data || {}))
                            {
                                $cache[ck2] = res.data[ck2];
                            }
                            $form.data('i18n-content-cache', $cache);

                            // 动态字段序号key转行标识key（弹窗回显用）
                            // 传会话数据本体：内容键转实例键、共享基础键及未匹配的孤儿键在会话内同步清理（否则随序列化重复保存）
                            DynamicKeyToUid($form, state.data);
                        } else {
                            Prompt(res.msg || config.lang.load_fail_tips);
                            // 加载失败允许重试：清标记，下次点击重新请求
                            if(state.loaded_data_ids)
                            {
                                delete state.loaded_data_ids[business_id];
                            }
                        }
                        show_popup(false);
                    },
                    error: function ()
                    {
                        if(!current || current.request_seq !== request_seq)
                        {
                            return;
                        }
                        Prompt(config.lang.load_fail_tips);
                        if(state.loaded_data_ids)
                        {
                            delete state.loaded_data_ids[business_id];
                        }
                        show_popup(false);
                    }
                });
                return;
            }
        }
        show_popup(false);
    }

    /**
     * 主表单字段当前值（输入为空时可用 data-i18n-default 作为弹窗默认展示）
     */
    function GetMainValue($field)
    {
        var tag = $field.prop('tagName');
        if(tag == 'TEXTAREA' || tag == 'INPUT')
        {
            var val = ($field.val() || '').toString();
            if(val !== '')
            {
                return val;
            }
            // 输入为空：显式默认值（如左侧标题文案）
            var def = ($field.attr('data-i18n-default') || '').toString().trim();
            if(def !== '')
            {
                return def;
            }
            return '';
        }
        return '';
    }

    /**
     * 弹窗绑定（壳在 footer #common-i18n-modal，仅绑定一次事件）
     */
    function BindModal()
    {
        if($modal !== null)
        {
            return $modal.length > 0;
        }
        $modal = $('#common-i18n-modal');
        if($modal.length <= 0)
        {
            $modal = null;
            return false;
        }

        // 关闭（不保存）
        $modal.find('.i18n-close, .i18n-cancel').on('click', function ()
        {
            ClosePopup();
        });

        // 语言切换（仅编辑器字段的顶部tab）
        $modal.find('.i18n-body').on('click', '.i18n-lang-tabs a', function ()
        {
            SwitchLang($(this).data('lang'));
        });

        // 确认（暂存、随表单提交保存）
        $modal.find('.i18n-confirm').on('click', function ()
        {
            ConfirmPopup();
        });
        return true;
    }

    /**
     * 当前弹窗上下文（供插件 JS 翻译填入）
     */
    function PopupContext()
    {
        if(!current || !current.form)
        {
            return null;
        }
        return {
            modal: $modal,
            form: current.form,
            field: current.field,
            key: current.key,
            label: current.label,
            type: current.type,
            base_value: current.base_value,
            loading: !!current.loading,
            table: current.form.attr('data-i18n-table') || '',
            plugins: FormPlugins(current.form),
            business_id: FieldBusinessId(current.field_el, current.form),
            language_list: config.language_list || []
        };
    }

    /**
     * 读取弹窗某语言当前输入值
     */
    function GetLangValue(lang)
    {
        if(!$modal || !lang)
        {
            return '';
        }
        var $item = $modal.find('.i18n-lang-item[data-lang="' + lang + '"]').first();
        if($item.length <= 0)
        {
            return '';
        }
        if($item.data('type') == 'editor')
        {
            var $container = $item.find('.i18n-editor-container');
            if($container.length > 0 && typeof UE !== 'undefined')
            {
                try
                {
                    var editor = UE.getEditor($container.attr('id'));
                    if(editor)
                    {
                        return editor.getContent() || '';
                    }
                } catch(e) {}
            }
            var $ta = $item.find('textarea').first();
            return $ta.length > 0 ? ($ta.val() || '') : '';
        }
        var $input = $item.find('input, textarea').filter(':visible').first();
        if($input.length <= 0)
        {
            $input = $item.find('input, textarea').first();
        }
        return $input.length > 0 ? ($input.val() || '') : '';
    }

    /**
     * 写入弹窗某语言输入值（插件自动翻译填入）
     */
    function SetLangValue(lang, value)
    {
        if(!$modal || !lang)
        {
            return false;
        }
        value = (value === null || value === undefined) ? '' : String(value);
        var $item = $modal.find('.i18n-lang-item[data-lang="' + lang + '"]').first();
        if($item.length <= 0)
        {
            return false;
        }
        if($item.data('type') == 'editor')
        {
            var $container = $item.find('.i18n-editor-container');
            if($container.length > 0)
            {
                // 同步 data-value，避免 editor.ready 晚于翻译回填时又用旧值覆盖
                $container.attr('data-value', value);
            }
            if($container.length > 0 && typeof UE !== 'undefined')
            {
                try
                {
                    var editor = UE.getEditor($container.attr('id'));
                    if(editor)
                    {
                        if(editor.isReady)
                        {
                            editor.setContent(value);
                        } else {
                            editor.ready(function ()
                            {
                                editor.setContent($container.attr('data-value') || '');
                            });
                        }
                        return true;
                    }
                } catch(e) {}
            }
            var $ta = $item.find('textarea').first();
            if($ta.length > 0)
            {
                $ta.val(value);
                return true;
            }
            return false;
        }
        var $tags = $item.find('input[data-am-tagsinput]');
        if($tags.length > 0 && typeof $tags.tagsinput === 'function')
        {
            try
            {
                $tags.tagsinput('removeAll');
                if(value !== '')
                {
                    var parts = value.split(',');
                    for(var i = 0; i < parts.length; i++)
                    {
                        var part = $.trim(parts[i]);
                        if(part !== '')
                        {
                            $tags.tagsinput('add', part);
                        }
                    }
                }
                return true;
            } catch(e) {}
        }
        var $input = $item.find('input, textarea').filter(':visible').first();
        if($input.length <= 0)
        {
            $input = $item.find('input, textarea').first();
        }
        if($input.length <= 0)
        {
            return false;
        }
        $input.val(value);
        return true;
    }

    /**
     * 批量写入（translations: {lang: value}）
     */
    function SetLangValues(translations, only_empty)
    {
        if(!translations || typeof translations !== 'object')
        {
            return 0;
        }
        var count = 0;
        for(var lang in translations)
        {
            if(!Object.prototype.hasOwnProperty.call(translations, lang))
            {
                continue;
            }
            if(only_empty && GetLangValue(lang) !== '')
            {
                continue;
            }
            if(SetLangValue(lang, translations[lang]))
            {
                count++;
            }
        }
        return count;
    }

    /**
     * 切换语言（仅编辑器字段）
     */
    function SwitchLang(lang)
    {
        $modal.find('.i18n-lang-tabs li').removeClass('am-active');
        $modal.find('.i18n-lang-tabs a[data-lang="' + lang + '"]').parent().addClass('am-active');
        $modal.find('.i18n-lang-item').addClass('am-hide');
        var $item = $modal.find('.i18n-lang-item-' + lang);
        $item.removeClass('am-hide');
        InitEditor($item);
    }

    /**
     * 弹窗渲染
     * 普通字段：所有语言直接铺开成行（左语言名、右输入框、无需切换）
     * 编辑器字段：顶部语言切换 + 全宽编辑器（多实例太重）
     */
    function RenderPopup()
    {
        if(!BindModal())
        {
            return;
        }

        // 字段信息（标题、默认语言当前值）
        $modal.find('.i18n-field-label').text('[' + current.label + '] ');
        // 富文本内容过长不放弹窗、以提示语代替；普通字段去标签后展示
        var base_preview = (current.type == 'editor') ? config.lang.editor_tips : current.base_value.replace(/<[^>]+>/g, '');
        $modal.find('.i18n-field-value').text(config.default_tips + '：' + (base_preview || '-'));

        // 首次拉取多语言数据中：显示加载态，避免空表单闪一下
        if(current.loading)
        {
            DestroyEditor();
            $modal.removeClass('i18n-modal-lg');
            $modal.find('.i18n-body').html('<div class="i18n-loading am-text-center am-padding-xl am-text-grey"><span class="am-icon-spinner am-icon-spin am-vertical-align-middle"></span><span class="am-margin-left-xs am-vertical-align-middle">' + (config.lang.loading_tips || '...') + '</span></div>');
            $(document).trigger('i18n.popup.loading', [PopupContext()]);
            return;
        }

        // 语言数据
        var state = State(current.form);
        var field_data = state.data[current.key] || {};
        var is_editor = (current.type == 'editor');

        var tabs = [];
        var items = [];
        for(var i in config.language_list)
        {
            var lang = config.language_list[i];
            var value = field_data[lang.code] || '';

            if(is_editor)
            {
                // 编辑器字段：顶部语言tab
                tabs.push('<li class="' + (i == 0 ? 'am-active' : '') + '"><a href="javascript: void(0)" data-lang="' + lang.code + '">' + LangLabelHtml(lang) + '</a></li>');
                items.push('<div class="i18n-lang-item i18n-lang-item-' + lang.code + (i == 0 ? '' : ' am-hide') + '" data-lang="' + lang.code + '" data-type="editor">' +
                        '<div class="i18n-editor-container" id="i18n-editor-' + current.field + '-' + lang.code + '" data-value=\'' + String(value).replace(/'/g, "&#39;") + '\'></div>' +
                    '</div>');
            } else {
                // 普通字段：每行一个语言（左语言名、右输入框、直接输入、提示文字和标题一致）
                var control = '';
                var placeholder = ' placeholder="' + htmlspecialchars(current.label) + '（' + lang.name + '）"';
                if(current.is_tagsinput)
                {
                    // 主表单为标签输入、弹窗同样使用（值逗号分隔与主表单存储一致）
                    control = '<input type="text" class="am-radius"' + placeholder + ' value="' + htmlspecialchars(value) + '" data-am-tagsinput />';
                } else if(current.tag == 'textarea') {
                    control = '<textarea rows="' + Math.min(current.rows, 6) + '" class="am-radius"' + placeholder + '>' + htmlspecialchars(value) + '</textarea>';
                } else {
                    control = '<input type="text" class="am-form-field am-radius"' + placeholder + ' value="' + htmlspecialchars(value) + '" />';
                }
                items.push('<div class="i18n-lang-item i18n-lang-row" data-lang="' + lang.code + '" data-type="text">' +
                        '<div class="i18n-lang-name">' + LangLabelHtml(lang) + '</div>' +
                        '<div class="i18n-lang-input">' + control + '</div>' +
                    '</div>');
            }
        }

        // 布局（弹窗固定宽度、编辑器弹窗加大）
        if(is_editor)
        {
            $modal.addClass('i18n-modal-lg');
            $modal.find('.i18n-body').html('<div class="i18n-editor-layout">' +
                    '<ul class="am-nav am-nav-tabs i18n-lang-tabs">' + tabs.join('') + '</ul>' +
                    '<div class="i18n-lang-content i18n-editor-content">' + items.join('') + '</div>' +
                '</div>');
            $modal.find('.i18n-lang-item').each(function ()
            {
                InitEditor($(this));
            });
        } else {
            $modal.removeClass('i18n-modal-lg');
            $modal.find('.i18n-body').html('<div class="i18n-rows-layout">' + items.join('') + '</div>');

            // 弹窗内标签输入初始化
            $modal.find('.i18n-body input[data-am-tagsinput]').each(function ()
            {
                $(this).tagsinput();
            });
        }

        // 通知插件：字段区已渲染完成，可操作钩子按钮做翻译填入
        $(document).trigger('i18n.popup.rendered', [PopupContext()]);
    }

    /**
     * html转义
     */
    function htmlspecialchars(str)
    {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /**
     * 语言标签（有配置图标才显示，图标在右侧便于对齐）
     */
    function LangLabelHtml(lang)
    {
        var name = htmlspecialchars(lang.name || lang.code || '');
        var html = '<span class="am-vertical-align-middle">' + name + '</span>';
        if(lang.icon)
        {
            html += '<img src="' + htmlspecialchars(lang.icon) + '" alt="' + name + '" class="i18n-lang-icon am-radius am-vertical-align-middle" width="14" height="14" />';
        }
        return html;
    }

    /**
     * 编辑器初始化（富文本字段、且页面已加载ueditor）
     */
    function InitEditor($item)
    {
        if($item.length <= 0 || $item.data('type') != 'editor')
        {
            return;
        }
        var $container = $item.find('.i18n-editor-container');
        if($container.length <= 0 || $container.data('init'))
        {
            return;
        }

        // 编辑器不可用时使用源码textarea
        if(typeof UE === 'undefined')
        {
            $container.replaceWith('<textarea rows="8" class="am-radius">' + htmlspecialchars($container.attr('data-value') || '') + '</textarea>');
            $item.data('type', 'text');
            return;
        }
        $container.data('init', 1);
        var editor = UE.getEditor($container.attr('id'), {
            initialFrameWidth: '100%',
            initialFrameHeight: 260,
            autoHeightEnabled: false,
            wordCount: false,
            elementPathEnabled: false
        });
        editor.ready(function ()
        {
            editor.setContent($container.attr('data-value') || '');
        });
    }

    /**
     * 销毁弹窗内编辑器
     */
    function DestroyEditor()
    {
        $modal && $modal.find('.i18n-editor-container').each(function ()
        {
            try
            {
                // delEditor 销毁实例并清理注册表（仅destroy会残留记录、再次打开同id实例报错）
                UE.delEditor($(this).attr('id'));
            } catch(e) {}
        });
    }

    /**
     * 关闭弹窗（× 或取消、不暂存数据）
     */
    function ClosePopup()
    {
        // 作废进行中的异步回填，避免关闭后接口返回又把弹窗打开/重绘
        if(current)
        {
            current.loading = false;
            current.request_seq = (current.request_seq || 0) + 1;
        }
        DestroyEditor();
        if($modal)
        {
            $modal.find('.i18n-confirm').prop('disabled', false);
            $modal.modal('close');
        }
    }

    /**
     * 确认弹窗（暂存数据并写入表单隐藏域、随表单保存提交）
     */
    function ConfirmPopup()
    {
        if(current && current.loading)
        {
            return;
        }
        var state = State(current.form);
        if(state.data[current.key] === undefined)
        {
            state.data[current.key] = {};
        }
        $modal.find('.i18n-lang-item').each(function ()
        {
            var lang = $(this).data('lang');
            var value = '';
            if($(this).data('type') == 'editor')
            {
                var $container = $(this).find('.i18n-editor-container');
                try
                {
                    var editor = UE.getEditor($container.attr('id'));
                    value = editor.getContent();
                } catch(e) {
                    value = $container.attr('data-value') || '';
                }
            } else {
                value = $(this).find('textarea, input').val();
            }
            state.data[current.key][lang] = value || '';
        });

        // tagsinput标签输入：值按逗号拆分与原文按位置对应、展开为逐值键（无_r后缀键serialize原样提交）
        if(current.is_tagsinput)
        {
            var main_items = String(current.base_value || '').split(',');
            delete state.data[current.key];
            $modal.find('.i18n-lang-item').each(function ()
            {
                var lang = $(this).data('lang');
                var trans_items = String($(this).find('input').first().val() || '').split(',');
                for(var i in main_items)
                {
                    var item = main_items[i].trim();
                    if(item === '' || (trans_items[i] || '').trim() === '')
                    {
                        continue;
                    }
                    var item_key = current.field + '_' + Md5Content(item);
                    if(state.data[item_key] === undefined)
                    {
                        state.data[item_key] = {};
                    }
                    state.data[item_key][lang] = trans_items[i].trim();
                }
            });
        }
        state.dirty = true;

        // 内容键副本：以确认时主表单字段当前内容为键（仅内容寻址字段、内容修改后翻译跟随新内容、新增行打开为空此时已有输入）
        // 固定显式键（config配置键/插件配置键/楼层关键字按分类）不产生副本（副本是冗余数据）
        var $cf = (current.field_el && current.field_el.length > 0) ? current.field_el : null;
        var is_content_addressed = $cf && ($cf.attr('data-i18n-content') !== undefined || $cf.attr('data-i18n-name') !== undefined || $cf.closest('[data-i18n-dynamic]').length > 0);
        if(is_content_addressed && current.is_dynamic && !current.is_tagsinput && state.data[current.key] !== undefined)
        {
            var content_value = (current.field_el && current.field_el.length > 0) ? GetMainValue(current.field_el) : current.base_value;
            if(String(content_value).trim() !== '')
            {
                state.data[current.field + '_' + Md5Content(content_value)] = state.data[current.key];
                var $cache = current.form.data('i18n-content-cache') || {};
                $cache[current.field + '_' + Md5Content(content_value)] = $.extend(true, {}, state.data[current.key]);
                current.form.data('i18n-content-cache', $cache);

                // sync提交的弹窗子表单（如备案信息/自提地址行编辑、原生submit事件不会触发）：确认时直接把内容键合并进页面主表单
                var merge_data = {};
                merge_data[current.field + '_' + Md5Content(content_value)] = state.data[current.key];
                MergeToMainForm(current.form, merge_data);
            }
        }
        DestroyEditor();
        $modal.modal('close');
        Serialize(current.form);
    }

    /**
     * 事件绑定
     */
    $(function ()
    {
        // 初始化字段按钮、绑定 footer 公共弹窗
        InitFields();
        BindModal();

        // 插件 API：读上下文 / 填各语言值
        window.$I18nPopup = {
            getModal: function ()
            {
                return $modal;
            },
            getContext: function ()
            {
                return PopupContext();
            },
            getBaseValue: function ()
            {
                return current ? (current.base_value || '') : '';
            },
            getLangValue: GetLangValue,
            setLangValue: SetLangValue,
            setLangValues: SetLangValues
        };

        // 语言按钮点击（委托、动态新增字段自动生效）
        $(document).on('click', '.i18n-field-btn', function (e)
        {
            e.preventDefault();
            var $field = $(this).data('field');
            if($field && $field.length > 0)
            {
                OpenPopup($field);
            }
        });

        // 动态新增字段自动初始化按钮（如商品手机详情添加条目）
        var init_timer = null;
        if(window.MutationObserver)
        {
            new MutationObserver(function ()
            {
                clearTimeout(init_timer);
                init_timer = setTimeout(InitFields, 100);
            }).observe(document.body, {childList: true, subtree: true});
        }

        // 动态添加表单字段后主动重扫（页面JS拼接行时调用、避免observer竞态漏扫）
        window.$I18nRescan = function ()
        {
            InitFields();
        };

        // 表单提交前序列化（捕获阶段、先于公共表单处理获取数据）
        document.addEventListener('submit', function (e)
        {
            var $form = $(e.target);
            if($form.is('form[data-i18n-table]'))
            {
                Serialize($form);
            }
        }, true);
    });
})();
