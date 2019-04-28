$(function()
{
    // 支付操作表单
    FromInit('form.form-validation-plugins-recharge-pay');

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        var value = '';
        if($(this).hasClass('selected'))
        {
            $(this).removeClass('selected');
        } else {
            value = $(this).data('value');
            $(this).addClass('selected').siblings('li').removeClass('selected');
        }
        $('form.form-validation-plugins-recharge-pay input[name='+$(this).parent().data('type')+'_id]').val(value).blur();
    });
});