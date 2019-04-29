$(function()
{
    // 表单初始化
    FromInit('form.form-validation-plugins-recharge-modal');
    FromInit('form.form-validation-plugins-recharge-popup');

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
        $('form.form-validation-plugins-recharge-popup input[name='+$(this).parent().data('type')+'_id]').val(value).blur();
    });

    // 充值列表支付发起事件
    $('.recharge-submit').on('click', function()
    {
        var recharge_id = $(this).data('value') || null;
        var $popup = $('#plugins-recharge-pay-popup');
        if(recharge_id != null)
        {
            $popup.find('input[name="recharge_id"]').val(recharge_id);
            $popup.modal('open');
        } else {
            Prompt('充值id有误');
        }
    });
});