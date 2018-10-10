$(function()
{
    /* 搜索切换 */
    var $so_list = $('.so-list');
    $thin_sub = $('.thin_sub');
    $thin_sub.find('input[name="is_more"]').change(function()
    {
        if($thin_sub.find('i').hasClass('am-icon-angle-down'))
        {
            $thin_sub.find('i').removeClass('am-icon-angle-down');
            $thin_sub.find('i').addClass('am-icon-angle-up');
        } else {
            $thin_sub.find('i').addClass('am-icon-angle-down');
            $thin_sub.find('i').removeClass('am-icon-angle-up');
        }
    
        if($thin_sub.find('input[name="is_more"]:checked').val() == undefined)
        {
            $so_list.addClass('none');
        } else {
            $so_list.removeClass('none');
        }
    });

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
        $(this).parents('td').find('input.input-rating').val(index+1);
    });

});