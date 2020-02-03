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

    // 手机模式下关闭地址选中
    $('.mobile-address-close-submit').on('click', function()
    {
        $('.address').removeClass('mobile-address');
        $(document.body).css({"overflow": "auto", "position":"unset"});
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
        // 0销售型, 2自提点 校验地址
        var site_type = $('.nav-buy').data('site-type') || 0;
        if(site_type == 0 || site_type == 2)
        {
            var address_id = parseInt($('form.nav-buy input[name="address_id"]').val());
            if(address_id == -1)
            {
                Prompt('请选择地址');
                return false;
            }
        }

        // 非预约模式校验支付方式
        var is_booking = $('.nav-buy').data('is-booking') || 0;
        if(is_booking != 1)
        {
            var payment_id = parseInt($('form.nav-buy input[name="payment_id"]').val()) || 0;
            if(payment_id === 0)
            {
                Prompt('请选择支付');
                return false;
            }
        }

        // 备注
        $('form.nav-buy input[name=user_note]').val($('.order-user-info input.memo-input').val());
    });

    // 自提点地址
    $extraction_popup = $('#extraction-address-popup');
    $extraction_popup.find('.extraction-address-item button').on('click', function()
    {
        window.location.href = UrlFieldReplace('address_id', $(this).data('value'));
    });
    $('.extraction-default .extraction-address-item').on('click', function(e)
    {
        if($(window).width() < 640)
        {
            $extraction_popup.modal();
        }
    });

    // 自提点地址 - 查看地图/关闭地图
    var $extraction_map_container = $('.extraction-address-map-container');
    $extraction_popup.find('.extraction-address-map-submit').on('click', function()
    {
        var lng = $(this).data('lng') || null;
        var lat = $(this).data('lat') || null;
        if(lng == null || lat == null)
        {
            Prompt('坐标有误');
            return false;
        }

        $extraction_map_container.show();
        MapInit(lng, lat, null, null, false);
    });
    $extraction_map_container.find('.map-inner .am-close').on('click', function()
    {
        $extraction_map_container.hide();
    });

    // 销售+自提 切换
    $('.buy-header-nav li a').on('click', function()
    {
        var value = $(this).data('value') || 0;
        var url = UrlFieldReplace('address_id', null);
        window.location.href = UrlFieldReplace('site_model', value, url);
    });
}); 