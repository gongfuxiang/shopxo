if(store.enabled)
{
    // 选择缓存key
    var store_address_key = 'store-buy-address-selected-index';
    var store_use_new_address_status_key = 'store-buy-use-new-address-status-count';
    var store_payment_key = 'store-buy-payment-selected-index';
}

$(function()
{
    // 底部地址同步显示
    function address_sync_view()
    {
        var $address = $('ul.address-list li.address-default');
        if($address.length > 0)
        {
            var user = $address.find('.user-base span.user').text();
            var phone = $address.find('.user-base span.phone').text();
            var province = $address.find('.region .province').text();
            var city = $address.find('.region .city').text();
            var dist = $address.find('.region .dist').text();
            var street = $address.find('.region .street').text();

            $('.buy-address-detail').text(province+' '+city+' '+dist+' '+street);
            $('.buy-footer-address .buy-user').text(user);
            $('.buy-footer-address .buy-phone').text(phone);
        }
    }

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
                store.set(store_address_key, 0);
                store.set(store_use_new_address_status_key, undefined);
            }
        }

        // 地址
        var store_address_value = store.get(store_address_key);
        if(store_address_value !== undefined)
        {
            $('ul.address-list li').eq(store_address_value).addClass('address-default').siblings().removeClass('address-default');

            // 底部地址同步
            address_sync_view();
        }
        // 支付方式
        var store_payment_value = store.get(store_payment_key);
        if(store_payment_value !== undefined)
        {
            $('ul.payment-list li').eq(store_payment_value).addClass('selected');
        }
    }

    // 地址不为空，并且未设置默认，并且没有选择 默认选中第一个
    if($('ul.address-list li').length > 0 && $('ul.address-list li.address-default').length == 0)
    {
        $('ul.address-list li').eq(0).addClass('address-default');

        // 底部地址同步
        address_sync_view();
    }

    // 地址选择
    $('ul.address-list li').on('click', function()
    {
        $(this).addClass('address-default').siblings().removeClass('address-default');
        store.set(store_address_key, $(this).index());

        // 底部地址同步
        address_sync_view();
    });

    // 混合列表选择
    $('.business-item ul li').on('click', function()
    {
        var type = $(this).parents('.business-item').data('type') || null;
        var temp_store_key = null;
        switch(type)
        {
            case 'payment' :
                temp_store_key = store_payment_key;
                break;
        }
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            if(temp_store_key != null)
            {
                store.remove(temp_store_key);
            }
        } else {
            $(this).addClass('selected').siblings('li').removeClass('selected');
            if(temp_store_key != null)
            {
                store.set(temp_store_key, $(this).index());
            }
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

    
    // 手机模式下选择地址
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
        }
    });
    $('.address').on('click', 'ul.address-list li', function()
    {
        $('.address').removeClass('mobile-address');
        $(document.body).css({"overflow": "auto", "position":"unset"});
        $('body').scrollTop(0);
    });

    // 设为默认地址
    $('.address-default-submit').on('click', function(e)
    {
        ConfirmNetworkAjax($(this));
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