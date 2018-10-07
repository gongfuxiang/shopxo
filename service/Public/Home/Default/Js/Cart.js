$(function()
{
    // 计算选择的商品总数和总价
    function cart_base_total()
    {
        var total_stock = 0;
        var total_price = 0.00;
        var ids = [];
        $('.am-table input[type="checkbox"]').each(function(k, v)
        {
            if($(this).prop('checked'))
            {
                var stock = parseInt($(this).parents('tr').find('.stock-tag input').val());
                var price = parseFloat($(this).parents('tr').find('.stock-tag').data('price'));
                total_stock += stock;
                total_price += stock*price;
                ids.push($(this).val());
            }
        });
        $('.cart-nav .selected-tips strong').text(total_stock);
        $('.cart-nav .nav-total-price').text('￥'+FomatFloat(total_price));
        $('.cart-nav input[name="ids"]').val(ids.toString() || 0);
    }

    // 购物车数量操作
    $('.stock-tag .stock-submit').on('click', function()
    {
        var id = parseInt($(this).parents('tr').data('id'));
        var goods_id = parseInt($(this).parents('tr').data('goods-id'));
        var inventory = parseInt($(this).parent().data('inventory'));
        var price = parseFloat($(this).parent().data('price'));
        var stock = parseInt($(this).parent().find('input').val());
        var type = $(this).data('type');

        var temp_stock = (type == 'add') ? stock+1 : stock-1;
        if(temp_stock > inventory)
        {
            temp_stock = inventory;
        }
        if(temp_stock <= 0)
        {
            temp_stock = 1;
        }
        $(this).parent().find('input').val(temp_stock);

        var temp_price = FomatFloat(temp_stock*price, 2);
        $(this).parents('tr').find('.total-price-content').text('￥'+temp_price);

        // 数量不一样则更新
        if(stock != temp_stock)
        {
            // 开启进度条
            $.AMUI.progress.start();

            // ajax请求
            $.ajax({
                url: $(this).parent().data('ajax-url'),
                type: 'post',
                dataType: "json",
                timeout: 10000,
                data: {"id": id, "goods_id": goods_id, "stock": temp_stock},
                success: function(result)
                {
                    $.AMUI.progress.done();
                    if(result.code == 0)
                    {
                        PromptCenter(result.msg, 'success');

                        // 计算选择的商品总数和总价
                        cart_base_total();
                    } else {
                        PromptCenter(result.msg);
                    }
                },
                error: function(xhr, type)
                {
                    $.AMUI.progress.done();
                    PromptCenter('网络异常错误');
                }
            });
        }
    });

    // 全选/反选
    $('.select-all-event').on('click', function()
    {
        if($(this).prop('checked'))
        {
            $(this).next().text('取消');
            $('.am-table input[type="checkbox"]').each(function(k, v)
            {
                if(!$(this).prop('disabled'))
                {
                    this.checked = true;
                }
            });
        } else {
            $(this).next().text('全选');
            $('.am-table input[type="checkbox"]').each(function(k, v)
            {
                if(!$(this).prop('disabled'))
                {
                    this.checked = false;
                }
            });
        }

        // 计算选择的商品总数和总价
        cart_base_total();
    });

    // 选择
    $('.am-table input[type="checkbox"]').on('click', function()
    {
        // 计算选择的商品总数和总价
        cart_base_total();
    });

    // 导航固定
    var nav_top = $('.cart-nav').length > 0 ? $('.cart-nav').offset().top : 0;
    function cart_nav_pop()
    {
        var scroll = $(document).scrollTop();
        var location = scroll+$(window).height();
        if(location < nav_top)
        {
            $('.cart-nav').css({"position":"fixed", "bottom":0, "width":$('.cart-content').width()+"px", "z-index":1000});
        } else {
            $('.cart-nav').css({"position":"relative", "bottom":0, "z-index":0, "width":"100%"});
        }
    }
    cart_nav_pop();
    $(window).scroll(function()
    {
        cart_nav_pop();
    });

    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        // 导航固定初始化
        cart_nav_pop();
    });

    // 结算事件
    $('.separate-submit').on('click', function()
    {
        // 计算选择的商品总数和总价
        cart_base_total();

        // 获取购物车id
        var ids = $(this).parents('form').find('input[name="ids"]').val() || 0;
        if(ids == 0)
        {
            PromptCenter('请选择商品');
            return false;
        }
    });

});