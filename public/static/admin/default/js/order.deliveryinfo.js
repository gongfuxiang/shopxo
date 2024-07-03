// 表单初始化
FromInit('form.form-validation-express');

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
            html += '<span class="address-content">'+data['express_name']+' / '+data['express_number']+'</span>';
            html += '</span>';
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

    // 快递添加开启
    $('.express-submit-add').on('click', function()
    {
        $popup_express.modal({width: 360});
        $popup_express.attr('data-type', 'add');

        // 清空数据
        FormDataFill({"express_id":0, "express_number":"", "note":""}, 'form.form-validation-express');
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

        // 基础数据
        $popup_express.modal({width: 360});
        $popup_express.attr('data-type', 'edit');
        $popup_express.attr('data-index', index);
    });
});