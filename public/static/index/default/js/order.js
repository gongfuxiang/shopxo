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
        if((payment_id || null) != null && $('.payment-items-'+payment_id).length > 0)
        {
            $('form.pay-form input[name=payment_id]').val(payment_id);
            $('.payment-items-'+payment_id).addClass('selected').siblings('li').removeClass('selected');
        } else {
            $('form.pay-form input[name=payment_id]').val(0);
            $('ul.payment-list li.selected').removeClass('selected');
        }
    }
    // 支付操作
    $('.submit-pay').on('click', function()
    {
        PayPopupParamsInit($(this).data('id'), $(this).data('payment-id'));
        $pay_popup.modal();
    });

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        if($(this).hasClass('selected'))
        {
            $('form.pay-form input[name='+$(this).parent().data('type')+'_id]').val(0);
            $(this).removeClass('selected');
        } else {
            $('form.pay-form input[name='+$(this).parent().data('type')+'_id]').val($(this).data('value'));
            $(this).addClass('selected').siblings('li').removeClass('selected');
        }
    });

    // 支付表单
    $('form.pay-form button[type=submit]').on('click', function()
    {
        var ids = $('form.pay-form input[name=ids]').val() || null;
        if(ids == null)
        {
            Prompt('订单id有误');
            return false;
        }
        var payment_id = $('form.pay-form input[name=payment_id]').val() || 0;
        if(payment_id == 0)
        {
            Prompt('请选择支付方式');
            return false;
        }
    });

    /**
     * 评价打分
     */
    $('ul.rating li').on('click', function()
    {
        $(this).parent().find('li i').removeClass('am-icon-star').addClass('am-icon-star-o');
        var index = $(this).index();
        var rating_msg = ['非常差', '差', '一般', '好', '非常好'];
        for(var i=0; i<=index; i++)
        {
            $(this).parent().find('li').eq(i).find('i').removeClass('am-icon-star-o').addClass('am-icon-star');
        }
        $(this).parent().find('li.tips-text').text(rating_msg[index]);
        $(this).parents('td').find('input.input-rating').val(index+1).trigger('blur');
        $(this).parent().removeClass('not-selected');
    });

    // 自动支付处理
    if($pay_popup.length > 0)
    {
        // 是否自动打开支付窗口
        if($pay_popup.data('is-auto') == 1)
        {
            $pay_popup.modal();
        }

        // 是否自动提交支付表单
        if($pay_popup.data('is-pay') == 1)
        {
            $pay_popup.find('button[type="submit"]').trigger('click');
        }
    }

    // 批量支付
    $('.batch-pay-submit').on('click', function()
    {
        // 是否有选择的数据
        var values = FromTableCheckedValues('order_form_checkbox_value', '.am-table-scrollable-horizontal');
        if(values.length <= 0)
        {
            Prompt('请先选中数据');
            return false;
        }

        // 支付url支付地址
        var url = $(this).data('url') || null;
        if(url == null)
        {
            Prompt('支付url地址有误');
            return false;
        }

        // 获取第一个订单支付方式
        var payment_id = $('#data-list-'+values[0]).find('.submit-pay').data('payment-id') || null;

        // 支付弹窗
        PayPopupParamsInit(values, payment_id);
        $pay_popup.modal();
    });
});