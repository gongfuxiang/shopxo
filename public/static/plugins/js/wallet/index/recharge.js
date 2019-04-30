$(function()
{
    // 充值列表支付发起事件
    $('.recharge-submit').on('click', function()
    {
        var recharge_id = $(this).data('value') || null;
        var recharge_no = $(this).data('recharge-no') || null;
        var money = $(this).data('money') || null;
        var $popup = $('#plugins-recharge-pay-popup');
        if(recharge_id != null && recharge_no != null && money != null)
        {
            $popup.find('.business-item ul li').removeClass('selected');
            $popup.find('input[name="payment_id"]').val('');
            $popup.find('input[name="recharge_id"]').val(recharge_id);
            $popup.find('.base .recharge-no').text(recharge_no);
            $popup.find('.base .price strong').text('￥'+money);
            $popup.modal('open');
        } else {
            Prompt('充值参数有误');
        }
    });
});