$(function()
{
    // 支付操作
    $('.submit-delivery').on('click', function()
    {
        $('form.delivery-form input[name=id]').val($(this).data('id'));
        $('form.delivery-form input[name=express_number]').val('');
        var express_id = $(this).data('express-id') || 0;
        var user_id = $(this).data('user-id') || 0;
        if($('.express-items-'+express_id).length > 0)
        {
            $('form.delivery-form input[name=express_id]').val(express_id);
            $('form.delivery-form input[name=user_id]').val(user_id);
            $('.express-items-'+express_id).addClass('selected').siblings('li').removeClass('selected');
        } else {
            $('form.delivery-form input[name=express_id]').val(0);
            $('form.delivery-form input[name=user_id]').val(0);
            $('ul.express-list li.selected').removeClass('selected');
        }
    });

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        if($(this).hasClass('selected'))
        {
            $('form.delivery-form input[name='+$(this).parent().data('type')+'_id]').val(0);
            $(this).removeClass('selected');
        } else {
            $('form.delivery-form input[name='+$(this).parent().data('type')+'_id]').val($(this).data('value'));
            $(this).addClass('selected').siblings('li').removeClass('selected');
        }
    });

    // 发货表单
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
});