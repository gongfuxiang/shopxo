$(function()
{
    var $popup = $('#aftersale-popup');

    // 弹窗事件
    $('.user-content-body table.am-table .operations button').on('click', function()
    {
        var order_id = $(this).data('order-id') || 0;
        var goods_id = $(this).data('goods-id') || 0;
        var number = $(this).data('number') || 0;
        var price = $(this).data('price') || 0;
        if(order_id == 0 || goods_id == 0 || number == 0)
        {
            Prompt('参数配置有误');
            return false;
        }
        $popup.find('.am-form-group').addClass('none');
        $popup.find('input[name="order_id"]').val(order_id);
        $popup.find('input[name="goods_id"]').val(goods_id);
        $popup.find('input[name="number"]').val(number);
        $popup.find('input[name="number"]').attr('max', number);
        $popup.find('input[name="price"]').val(price);
        $('.aftersale-type .am-vertical-align').removeClass('selected');
        $popup.modal('open');
    });

    // 类型切换
    $('.aftersale-type .am-vertical-align').on('click', function()
    {
        $('.aftersale-type .am-vertical-align').removeClass('selected');
        $(this).addClass('selected');

        // 表单处理
        var type = $(this).data('type');
        if(type != undefined)
        {
            $popup.find('.am-form-group').removeClass('none');
            $popup.find('input[name="type"]').val(type);
        }
        switch(type)
        {
            // 仅退款
            case 0 :
                $popup.find('.form-only-money').removeClass('none');
                $popup.find('.form-money-goods').addClass('none');
                break;

            // 退款退货
            case 1 :
                $popup.find('.form-only-money').addClass('none');
                $popup.find('.form-money-goods').removeClass('none');
                break;
        }
    });
});