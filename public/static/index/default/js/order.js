$(function()
{
    // 支付操作
    $('.submit-pay').on('click', function()
    {
        $('form.pay-form input[name=id]').val($(this).data('id'));
        var payment_id = $(this).data('payment-id') || 0;
        if($('.payment-items-'+payment_id).length > 0)
        {
            $('form.pay-form input[name=payment_id]').val(payment_id);
            $('.payment-items-'+payment_id).addClass('selected').siblings('li').removeClass('selected');
        } else {
            $('form.pay-form input[name=payment_id]').val(0);
            $('ul.payment-list li.selected').removeClass('selected');
        }
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
        var id = $('form.pay-form input[name=id]').val() || 0;
        if(id == 0)
        {
            PromptCenter('订单id有误');
            return false;
        }
        var payment_id = $('form.pay-form input[name=payment_id]').val() || 0;
        if(payment_id == 0)
        {
            PromptCenter('请选择支付方式');
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
        for(var i=0; i<=index; i++)
        {
            $(this).parent().find('li').eq(i).find('i').removeClass('am-icon-star-o').addClass('am-icon-star');
        }
        $(this).parents('td').find('input.input-rating').val(index+1).trigger('blur');
    });

    // 是否自动开启支付窗口
    if($('.submit-pay').length > 0 && $('.submit-pay').data('is-auto') == 1)
    {
        $('.submit-pay').trigger('click');
    }

});