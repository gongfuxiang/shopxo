$(function()
{
    // 支付操作
    $('.submit-delivery').on('click', function()
    {
        $('form.delivery-form input[name=id]').val($(this).data('id'));
        $('form.delivery-form input[name=express_number]').val('');
        var express_id = $(this).data('express-id') || 0;
        var user_id = $(this).data('user-id') || 0;
        $('form.delivery-form input[name=express_id]').val(express_id);
        $('form.delivery-form input[name=user_id]').val(user_id);
        $('ul.express-list li.selected').removeClass('selected');
        if(express_id != 0) {
            $('.express-items-'+express_id).addClass('selected').siblings('li').removeClass('selected');
        }
    });

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        if($(this).hasClass('selected'))
        {
            $('form input[name='+$(this).parent().data('type')+'_id]').val(0);
            $(this).removeClass('selected');
        } else {
            $('form input[name='+$(this).parent().data('type')+'_id]').val($(this).data('value'));
            $(this).addClass('selected').siblings('li').removeClass('selected');
        }
    });

    // 发货操作表单
    FromInit('form.form-validation-delivery');
    $('form.delivery-form button[type=submit]').on('click', function()
    {
        var id = $('form.delivery-form input[name=id]').val() || 0;
        if(id == 0)
        {
            Prompt('订单id有误');
            return false;
        }
        var express_id = $('form.delivery-form input[name=express_id]').val() || 0;
        if(express_id == 0)
        {
            Prompt('请选择快递方式');
            return false;
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

    // 支付操作表单
    FromInit('form.form-validation-pay');
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

    // 取货操作
    $('.submit-take').on('click', function()
    {
        $('form.take-form input[name=id]').val($(this).data('id') || 0);
        $('form.take-form input[name=user_id]').val($(this).data('user-id') || 0);
    });

    // 取货操作表单
    FromInit('form.form-validation-take');
    $('form.take-form button[type=submit]').on('click', function()
    {
        if(($('form.take-form input[name=id]').val() || 0) == 0)
        {
            Prompt('订单id有误');
            return false;
        }
    });

});