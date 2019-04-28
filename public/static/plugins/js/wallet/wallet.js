$(function()
{
    // 支付操作表单
    FromInit('form.form-validation-plugins-recharge-pay');

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        if($(this).hasClass('selected'))
        {
            $('form.form-validation-plugins-recharge-pay input[name='+$(this).parent().data('type')+'_id]').val(0);
            $(this).removeClass('selected');
        } else {
            $('form.form-validation-plugins-recharge-pay input[name='+$(this).parent().data('type')+'_id]').val($(this).data('value'));
            $(this).addClass('selected').siblings('li').removeClass('selected');
        }
    });
});