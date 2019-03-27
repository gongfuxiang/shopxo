if(store.enabled)
{
    // 选择缓存key
    var store_use_new_address_status_key = 'store-buy-use-new-address-status-count';
}

$(function()
{
    // 选中处理
    if(store.enabled)
    {
        // 是否使用新地址
        var store_address_use_status = store.get(store_use_new_address_status_key);
        if(store_address_use_status !== undefined)
        {
            // 如果新的地址大于使用新地址标记数量则使用第一个地址
            if(store_address_use_status < $('ul.address-list li').length)
            {
                store.set(store_use_new_address_status_key, undefined);
                window.location.href = UrlFieldReplace('address_id', $('ul.address-list li:first').data('value'));
            }
        }
    }

    // 地址选择
    $('ul.address-list li').on('click', function(e)
    {
        if($(window).width() < 640)
        {
            if(!$('.address').hasClass('mobile-address'))
            {
                $('.address').addClass('mobile-address');
                $(document.body).css({"overflow": "hidden", "position":"fixed"});
                e.stopPropagation();
            }
        } else {
            // 底部地址同步
            window.location.href = UrlFieldReplace('address_id', $(this).data('value'));
        }
    });

    // 手机模式下选择地址
    $('.address').on('click', 'ul.address-list li', function()
    {
        window.location.href = UrlFieldReplace('address_id', $(this).data('value'));
    });

    // 设为默认地址
    $('.address-default-submit').on('click', function(e)
    {
        ConfirmNetworkAjax($(this));
        e.stopPropagation();
    });

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        var field = $(this).parents('.business-item').data('field') || null;
        var value = $(this).data('value') || null;
        if(field != null && value != null)
        {
            window.location.href = UrlFieldReplace(field, value);
        }
    });

    // 弹出地址选择
    $('.address-submit-save').on('click', function(e)
    {
        ModalLoad($(this).data('url'), $(this).data('popup-title'), 'popup-modal-address', 'common-address-modal');

        // 阻止事件冒泡
        e.stopPropagation();

        // 使用新地址标记
        store.set(store_use_new_address_status_key, $('ul.address-list li').length);
    });

    // 阻止事件冒泡
    $('.address-submit-delete').on('click', function(e)
    {
        ConfirmDataDelete($(this));
        e.stopPropagation();
    });

    // 提交订单
    $('.nav-buy .btn-go').on('click', function()
    {
        var msg = '';
        var status = true;
        var address_id = $('ul.address-list li.address-default').data('value') || null;
        if(address_id === null)
        {
            status = false;
            msg = '请选择地址';
        }

        if(status === true)
        {
            var payment_id = $('ul.payment-list li.selected').data('value') || null;
            if(payment_id === null)
            {
                status = false;
                msg = '请选择支付';
            }
        }

        if(status === false)
        {
            if($(window).width() < 640)
            {
                PromptBottom(msg, null, null, 50);
            } else {
                PromptCenter(msg);
            }
            return false;
        }
        
        $('form.nav-buy input[name=address_id]').val(address_id);
        $('form.nav-buy input[name=payment_id]').val(payment_id);
        $('form.nav-buy input[name=user_note]').val($('.order-user-info input.memo-input').val());
    });
    
}); 