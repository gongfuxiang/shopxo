$(function()
{
    // 支付窗口
    var $pay_popup = $('#order-pay-popup');

    // 支付窗口参数初始化
    function PayPopupParamsInit(ids, payment_id)
    {
        // 数组则转成字符串
        if(IsArray(ids))
        {
            ids = ids.join(',');
        }
        $('form.pay-form input[name=ids]').val(ids);
        $('form.pay-form input[name=payment_id]').val(0);
        $('form.pay-form ul.payment-list li').each(function(k, v)
        {
            var temp = parseInt($(this).data('value') || 0);
            if(temp == payment_id)
            {
                $(this).addClass('selected');
                $('form.pay-form input[name=payment_id]').val(payment_id);
            } else {
                $(this).removeClass('selected');
            }
        });
    }
    // 支付操作
    $(document).on('click', '.submit-pay', function()
    {
        PayPopupParamsInit($(this).data('id'), $(this).data('payment-id'));
        $pay_popup.modal();
    });

    // 支付表单
    $(document).on('click', 'form.pay-form button[type=submit]', function()
    {
        var ids = $('form.pay-form input[name=ids]').val() || null;
        if(ids == null)
        {
            Prompt(lang_order_id_empty || '订单id有误');
            return false;
        }
        var payment_id = $('form.pay-form input[name=payment_id]').val() || 0;
        if(payment_id == 0)
        {
            Prompt(lang_payment_choice_tips || '请选择支付方式');
            return false;
        }
    });

    /**
     * 评价打分
     */
    $(document).on('click', 'ul.rating li', function()
    {
        $(this).parent().find('li i').removeClass('am-icon-star').addClass('am-icon-star-o');
        var index = $(this).index();
        var rating_arr = (lang_rating_string || '非常差,差,一般,好,非常好').split(',');
        for(var i=0; i<=index; i++)
        {
            $(this).parent().find('li').eq(i).find('i').removeClass('am-icon-star-o').addClass('am-icon-star');
        }
        $(this).parent().find('li.tips-text').text(rating_arr[index]);
        $(this).parents('td').find('input.input-rating').val(index+1).trigger('blur');
        $(this).parent().removeClass('not-selected');
    });

    // 自动支付处理
    if($pay_popup.length > 0)
    {
        // 是否自动打开支付窗口
        if($pay_popup.data('is-auto') == 1)
        {
            setTimeout(function()
            {
                $('.submit-pay').trigger('click');
            }, 100);
        }

        // 是否自动提交支付表单
        if($pay_popup.data('is-pay') == 1)
        {
            $pay_popup.find('button[type="submit"]').trigger('click');
        }
    }

    // 批量支付
    $(document).on('click', '.batch-pay-submit', function()
    {
        // 是否有选择的数据
        var values = FromTableCheckedValues('order_form_checkbox_value', '.am-table-scrollable-horizontal');
        if(values.length <= 0)
        {
            Prompt(lang_not_choice_data_tips || '请先选中数据');
            return false;
        }

        // 支付url支付地址
        var url = $(this).data('url') || null;
        if(url == null)
        {
            Prompt(lang_pay_url_empty_tips || '支付url地址有误');
            return false;
        }

        // 获取第一个订单支付方式
        var payment_id = $('#data-list-'+values[0]).find('.submit-pay').data('payment-id') || null;

        // 支付弹窗
        PayPopupParamsInit(values, payment_id);
        $pay_popup.modal();
    });
});