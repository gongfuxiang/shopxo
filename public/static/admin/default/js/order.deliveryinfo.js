// 表单初始化
FromInit('form.form-validation-express');

/**
 * 快递备注多语言字段键（与 PHP I18nService::OrderExpressNoteField 一致）
 */
function OrderExpressNoteI18nField(express_id, express_number)
{
    var eid = parseInt(express_id || 0) || 0;
    var num = String(express_number === null || express_number === undefined ? '' : express_number).replace(/\r\n/g, '\n').replace(/\r/g, '\n').trim();
    if(eid <= 0 || num === '')
    {
        return '';
    }
    var hash = (typeof hex_md5 === 'function') ? hex_md5(num) : num;
    return 'note_' + eid + '_' + hash;
}

/**
 * 同步备注 input 的 data-i18n-field（按当前快递公司+单号隔离，避免弹窗复用串数据）
 */
function OrderExpressNoteI18nSync($form)
{
    $form = ($form && $form.length) ? $form : $('form.form-validation-express');
    var $note = $form.find('input[name="note"][data-i18n]');
    if($note.length <= 0)
    {
        return '';
    }
    var field = OrderExpressNoteI18nField($form.find('[name="express_id"]').val(), $form.find('[name="express_number"]').val());
    $note.attr('data-i18n-field', field || 'note');
    return field;
}

/**
 * 快递弹窗确认回调（表单 request-value）
 */
function ViewExpressModalBack(data)
{
    ExpressModalHandle(data);
}

/**
 * 快递返回处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 * @param   {[object]}        data [快递信息]
 */
function ExpressModalHandle(data)
{
    $(function()
    {
        // 参数处理
        var express_id = data.express_id || null;
        var express_number = data.express_number || null;
        if(express_id == null || express_number == null)
        {
            Prompt(window['lang_not_fill_in_error'] || '数据填写有误');
            return false;
        }

        // 快递名称
        data['express_name'] = $('select[name="express_id"]').find('option:selected').text();

        // 数据拼接
        var html = '<li>';
            html += '<span>'+data['express_name']+' / '+data['express_number']+'</span>';
            html += '<a href="javascript:;" class="am-text-xs am-text-blue edit-submit"> '+(window['lang_operate_edit_name'] || '编辑')+'</a>';
            html += '<a href="javascript:;" class="am-text-xs am-text-blue delete-submit"> '+(window['lang_operate_remove_name'] || '移除')+'</a>';
            html += '</li>';

        // 数据处理
        var value = ExpressValue();
        
        // 弹层
        var $popup_express = $('#popup-express-win');

        // 操作类型（add, edit）
        var form_type = $popup_express.attr('data-type') || 'add';
        if(form_type == 'add')
        {
            $('.express-list ul').append(html);
            value.push(data);
        } else {
            var form_index = $popup_express.attr('data-index') || 0;
            value.splice(form_index, 1, data);
            $('.express-list ul').find('li').eq(form_index).replaceWith(html);
        }
        $popup_express.modal('close');
        $('input[name="express_data"]').val(encodeURIComponent(value.length == 0 ? '' : JSON.stringify(value)));
    });
}

/**
 * 获取快递信息
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 */
function ExpressValue()
{
    var value = $('input[name="express_data"]').val() || null;
    return (value == null) ? [] : JSON.parse(decodeURIComponent(value));
}

$(function()
{
    // 弹层
    var $popup_express = $('#popup-express-win');
    var $express_form = $('form.form-validation-express');

    // 快递公司/单号变化时同步备注多语言字段键
    $express_form.on('change keyup', '[name="express_id"], [name="express_number"]', function()
    {
        OrderExpressNoteI18nSync($express_form);
    });

    // 打开备注多语言前校验并同步字段键（捕获阶段，先于 i18n 弹窗）
    document.addEventListener('click', function(e)
    {
        var btn = e.target.closest ? e.target.closest('form.form-validation-express .i18n-field-btn') : null;
        if(!btn)
        {
            return;
        }
        var $form = $(btn).closest('form');
        var field = OrderExpressNoteI18nSync($form);
        if(!field)
        {
            e.preventDefault();
            e.stopPropagation();
            Prompt(window['lang_not_fill_in_error'] || '请先选择快递并填写单号');
        }
    }, true);

    // 快递添加开启
    $('.express-submit-add').on('click', function()
    {
        $popup_express.modal({width: 360, closeViaDimmer: false});
        $popup_express.attr('data-type', 'add');

        // 清空数据
        FormDataFill({"express_id":0, "express_number":"", "note":""}, 'form.form-validation-express');
        OrderExpressNoteI18nSync($express_form);
    });

    // 快递移除
    $(document).on('click', '.express-list .delete-submit', function()
    {
        var index = $(this).parents('li').index();
        var value = ExpressValue();
        if(value.length > 0)
        {
            AMUI.dialog.confirm({
                title: window['lang_reminder_title'] || '温馨提示',
                content: window['lang_operate_remove_confirm_tips'] || '移除后保存生效、确认继续吗？',
                onConfirm: function(options)
                {
                    value.splice(index, 1);
                    $('input[name="express_data"]').val(encodeURIComponent(value.length == 0 ? '' : JSON.stringify(value)));
                    $('.express-list ul').find('li').eq(index).remove();
                },
                onCancel: function(){}
            });
        } else {
            $('.express-list ul').find('li').eq(index).remove();
        }
    });

    // 快递编辑
    $(document).on('click', '.express-list .edit-submit', function()
    {
        // 数据验证
        var index = $(this).parents('li').index();
        var value = ExpressValue();
        if(value.length <= 0 || (value[index] || null) == null)
        {
            Prompt(window['lang_data_error'] || '数据有误');
            return false;
        }

        // 数据填充
        FormDataFill(value[index], 'form.form-validation-express');
        OrderExpressNoteI18nSync($express_form);

        // 基础数据
        $popup_express.modal({width: 360, closeViaDimmer: false});
        $popup_express.attr('data-type', 'edit');
        $popup_express.attr('data-index', index);
    });
});
