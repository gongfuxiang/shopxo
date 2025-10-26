// 表单初始化
FromInit('form.form-validation-service');

/**
 * 服务返回处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 * @param   {[object]}        data [服务信息]
 */
function ServiceModalHandle(data)
{
    $(function()
    {
        // 参数处理
        var service_name = data.service_name || null;
        var service_mobile = data.service_mobile || null;
        if(service_name == null || service_mobile == null)
        {
            Prompt(window['lang_not_fill_in_error'] || '数据填写有误');
            return false;
        }

        // 数据拼接
        var html = '<li>';
            html += '<p>'+data['service_name']+' / '+data['service_mobile']+'</p>';
            if((data.service_start_time || null) != null || (data.service_end_time || null) != null)
            {
                html += '<p>';
                if((data.service_start_time || null) != null)
                {
                    html += '<span>'+data.service_start_time+'</span>';
                }
                if((data.service_start_time || null) != null && (data.service_end_time || null) != null)
                {
                    html += ' <span class="am-color-grey">~</span> ';
                }
                if((data.service_end_time || null) != null)
                {
                    html += '<span>'+data.service_end_time+'</span>';
                }
                html += '</p>';
            }
            if((data.note || null) != null)
            {
                html += '<p>'+data.note+'</p>';
            }
            html += '<a href="javascript:;" class="am-text-xs am-text-blue edit-submit"> '+(window['lang_operate_edit_name'] || '编辑')+'</a>';
            html += '<a href="javascript:;" class="am-text-xs am-text-blue delete-submit"> '+(window['lang_operate_remove_name'] || '移除')+'</a>';
            html += '</li>';

        // 数据处理
        var value = ServiceValue();
        
        // 弹层
        var $popup_service = $('#popup-service-win');

        // 操作类型（add, edit）
        var form_type = $popup_service.attr('data-type') || 'add';
        if(form_type == 'add')
        {
            $('.service-list ul').append(html);
            value.push(data);
        } else {
            var form_index = $popup_service.attr('data-index') || 0;
            value.splice(form_index, 1, data);
            $('.service-list ul').find('li').eq(form_index).replaceWith(html);
        }
        $popup_service.modal('close');
        $('input[name="service_data"]').val(encodeURIComponent(value.length == 0 ? '' : JSON.stringify(value)));
    });
}

/**
 * 获取服务信息
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 */
function ServiceValue()
{
    var value = $('input[name="service_data"]').val() || null;
    return (value == null) ? [] : JSON.parse(decodeURIComponent(value));
}

$(function()
{
    // 弹层
    var $popup_service = $('#popup-service-win');

    // 服务添加开启
    $('.service-submit-add').on('click', function()
    {
        $popup_service.modal({width: 360});
        $popup_service.attr('data-type', 'add');

        // 清空数据
        FormDataFill({service_name: '', service_mobile:'', service_start_time:'', service_end_time:'', note:''}, 'form.form-validation-service');
    });

    // 服务移除
    $(document).on('click', '.service-list .delete-submit', function()
    {
        var index = $(this).parents('li').index();
        var value = ServiceValue();
        if(value.length > 0)
        {
            AMUI.dialog.confirm({
                title: window['lang_reminder_title'] || '温馨提示',
                content: window['lang_operate_remove_confirm_tips'] || '移除后保存生效、确认继续吗？',
                onConfirm: function(options)
                {
                    value.splice(index, 1);
                    $('input[name="service_data"]').val(encodeURIComponent(value.length == 0 ? '' : JSON.stringify(value)));
                    $('.service-list ul').find('li').eq(index).remove();
                },
                onCancel: function(){}
            });
        } else {
            $('.service-list ul').find('li').eq(index).remove();
        }
    });

    // 服务编辑
    $(document).on('click', '.service-list .edit-submit', function()
    {
        // 数据验证
        var index = $(this).parents('li').index();
        var value = ServiceValue();
        if(value.length <= 0 || (value[index] || null) == null)
        {
            Prompt(window['lang_data_error'] || '数据有误');
            return false;
        }

        // 数据填充
        value[index]['service_start_time'] = value[index]['service_start_time'].replace('+', ' ');
        value[index]['service_end_time'] = value[index]['service_end_time'].replace('+', ' ');
        FormDataFill(value[index], 'form.form-validation-service');

        // 基础数据
        $popup_service.modal({width: 360});
        $popup_service.attr('data-type', 'edit');
        $popup_service.attr('data-index', index);
    });
});