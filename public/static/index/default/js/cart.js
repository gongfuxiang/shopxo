$(function()
{
    /**
     * 计算选择的商品总数和总价
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-03-21
     * @desc    description
     */
    function CartBaseTotal()
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
        ids = ids.toString() || 0;
        $('.cart-nav .selected-tips strong').text(total_stock);
        $('.cart-nav .nav-total-price').text(__price_symbol__+FomatFloat(total_price));
        $('.cart-nav input[name="ids"]').val(ids.toString() || 0);
        $('.cart-nav .nav-delete-submit').attr('data-id', ids);
    }

    /**
     * 购物车数量更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-11
     * @desc    description
     * @param   {[object]}        self  [氮气罐对象]
     * @param   {[int]}           stock [数量]
     */
    function CardNumberUpdate(self, stock)
    {
        var id = parseInt(self.parents('tr').data('id'));
        var goods_id = parseInt(self.parents('tr').data('goods-id'));
        var inventory = parseInt(self.parents('.stock-tag').data('inventory'));
        var price = parseFloat(self.parents('.stock-tag').data('price'));
        var type = self.data('type');

        if(stock > inventory)
        {
            stock = inventory;
        }
        if(stock <= 0)
        {
            stock = 1;
        }

        // 开启进度条
        $.AMUI.progress.start();

        // ajax请求
        $.ajax({
            url: self.parents('.stock-tag').data('ajax-url'),
            type: 'post',
            dataType: "json",
            timeout: 10000,
            data: {"id": id, "goods_id": goods_id, "stock": stock},
            success: function(result)
            {
                $.AMUI.progress.done();
                if(result.code == 0)
                {
                    self.parents('.stock-tag').find('input').val(stock);
                    self.parents('tr').find('.total-price-content').text(__price_symbol__+FomatFloat(stock*price, 2));
                    
                    PromptCenter(result.msg, 'success');

                    // 数量更新
                    self.parents('tr').find('.wap-number').text('x'+stock);

                    // 计算选择的商品总数和总价
                    CartBaseTotal();
                } else {
                    PromptCenter(result.msg);
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                PromptCenter('服务器错误');
            }
        });
    }

    // 购物车数量操作
    $('.stock-tag .stock-submit').on('click', function()
    {
        var stock = parseInt($(this).parents('.stock-tag').find('input').val());
        var type = $(this).data('type');
        var temp_stock = (type == 'add') ? stock+1 : stock-1;
        CardNumberUpdate($(this), temp_stock);
    });

    // 输入事件
    $('.stock-tag input[type="number"]').on('blur', function()
    {
        var stock = $(this).val() || null;
        if(stock == null)
        {
            stock = 1;
        }
        $(this).val(stock);
        CardNumberUpdate($(this), stock);
    });

    // 全选/反选
    $('.select-all-event').on('click', function()
    {
        if($(this).find('input').is(':checked'))
        {
            $(this).find('span.el-text').text('反选');
            $('.am-table').find('input[type="checkbox"]').not(':disabled').uCheck('check');
        } else {
            $(this).find('span.el-text').text('全选');
            $('.am-table').find('input[type="checkbox"]').not(':disabled').uCheck('uncheck');
        }

        // 计算选择的商品总数和总价
        CartBaseTotal();
    });

    // 选择
    $('.am-table input[type="checkbox"]').on('click', function()
    {
        // 计算选择的商品总数和总价
        CartBaseTotal();
    });

    // 导航固定
    var nav_top = $('.cart-nav').length > 0 ? $('.cart-nav').offset().top : 0;
    function CartNavPop()
    {
        var scroll = $(document).scrollTop();
        var location = scroll+$(window).height()-100;
        var bottom = ($(window).width() < 640) ? '49px' : '0';
        if(location < nav_top)
        {
            $('.cart-nav').css({"position":"fixed", "bottom":bottom, "width":$('.cart-content').width()+"px", "z-index":1000});
            $('body').css({"padding-bottom":"50px"});
        } else {
            $('.cart-nav').css({"position":"relative", "bottom":0, "z-index":0, "width":"100%"});
            $('body').css({"padding-bottom":"0"});
        }
    }
    CartNavPop();
    $(window).scroll(function()
    {
        CartNavPop();
    });

    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        // 导航固定初始化
        CartNavPop();
    });

    // 结算事件
    $('.separate-submit').on('click', function()
    {
        // 计算选择的商品总数和总价
        CartBaseTotal();

        // 获取购物车id
        var ids = $(this).parents('form').find('input[name="ids"]').val() || 0;
        if(ids == 0)
        {
            PromptCenter('请选择商品');
            return false;
        }
    });

});