$(function()
{
    // 表单初始化
    if($('form.form-validation-plugins-recharge-modal').length > 0)
    {
        FromInit('form.form-validation-plugins-recharge-modal');
    }
    if($('form.form-validation-plugins-recharge-popup').length > 0)
    {
        FromInit('form.form-validation-plugins-recharge-popup');
    }

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
});