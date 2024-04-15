FromInit('form.form-validation-pay');
FromInit('form.form-validation-take');
$(function()
{
    // 支付操作
    var $form_pay = $('form.form-validation-pay');
    $(document).on('click', '.submit-pay', function()
    {
        var payment_id = parseInt($(this).data('payment-id') || 0);
        $form_pay.find('input[name=id]').val($(this).data('id'));
        $form_pay.find('input[name=payment_id]').val(0);
        $form_pay.find('ul.payment-list li').each(function(k, v)
        {
            var temp = parseInt($(this).data('value') || 0);
            if(temp == payment_id)
            {
                $(this).addClass('selected');
                $form_pay.find('input[name=payment_id]').val(payment_id);
            } else {
                $(this).removeClass('selected');
            }
        });
    });
    // 支付操作表单
    $form_pay.find('button[type=submit]').on('click', function()
    {
        var id = parseInt($form_pay.find('input[name=id]').val() || 0);
        if(id == 0)
        {
            Prompt(window['lang_order_id_empty'] || '订单id有误');
            return false;
        }
        var payment_id = parseInt($form_pay.find('input[name=payment_id]').val() || 0);
        if(payment_id == 0)
        {
            Prompt(window['lang_payment_choice_tips'] || '请选择支付方式');
            return false;
        }
    });

    // 取货操作
    var $form_take = $('form.form-validation-take');
    $(document).on('click', '.submit-take', function()
    {
        $form_take.find('input[name=id]').val($(this).data('id') || 0);
        $form_take.find('input[name=user_id]').val($(this).data('user-id') || 0);
    });
    // 取货操作表单
    $form_take.find('button[type=submit]').on('click', function()
    {
        if(parseInt($form_take.find('input[name=id]').val() || 0) == 0)
        {
            Prompt(window['lang_order_id_empty'] || '订单id有误');
            return false;
        }
    });

});