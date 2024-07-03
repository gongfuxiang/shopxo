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
        var goods_ids = [];
        var ids = [];
        $('.am-table input[type="checkbox"]').each(function(k, v)
        {
            if($(this).prop('checked'))
            {
                var $stock = $(this).parents('tr').find('.stock-tag');
                var stock = parseInt($stock.find('input').val());
                var price = parseFloat($stock.attr('data-price'));
                total_stock += stock;
                total_price += stock*price;
                ids.push($(this).val());
                goods_ids.push($(this).parents('tr').attr('data-goods-id'))
            }
        });
        ids = ids.toString() || 0;
        goods_ids = goods_ids.toString() || 0;
        $('.cart-nav .selected-tips strong').text(total_stock);
        $('.cart-nav .nav-total-price').text(__currency_symbol__+FomatFloat(total_price));
        $('.cart-nav input[name="ids"]').val(ids.toString() || 0);
        $('.cart-nav .nav-delete-submit').attr('data-id', ids);
        $('.cart-nav .nav-collect-submit').attr('data-id', goods_ids);
    }

    /**
     * 购物车数量更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-11
     * @desc    description
     * @param   {[object]}        self  [操作按钮对象]
     * @param   {[int]}           stock [数量]
     */
    function CartNumberUpdate(self, stock)
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
            url: RequestUrlHandle($('.cart-content').data('ajax-url')),
            type: 'post',
            dataType: "json",
            timeout: 10000,
            data: {"id": id, "goods_id": goods_id, "stock": stock},
            success: function(res)
            {
                $.AMUI.progress.done();
                if(res.code == 0)
                {
                    var $stock = self.parents('.stock-tag');
                    $stock.attr('data-price', res.data.price);
                    $stock.find('input').val(res.data.stock);
                    self.parents('tr').find('.line-price').text(res.data.show_price_symbol+res.data.price+res.data.show_price_unit);
                    self.parents('tr').find('.total-price-content').text(__currency_symbol__+res.data.total_price);

                    Prompt(res.msg, 'success');

                    // 计算选择的商品总数和总价
                    CartBaseTotal();
                } else {
                    Prompt(res.msg);
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    }

    // 购物车数量操作
    $(document).on('click', '.stock-tag .stock-submit', function()
    {
        // 数量参数
        var $parent = $(this).parents('.stock-tag');
        var $input = $parent.find('input[type="number"]');
        var min = parseInt($input.data('min-limit') || 1);
        var max = parseInt($input.data('max-limit'));
        var unit = $input.data('unit') || '';
        var stock = parseInt($input.val());
        var type = $(this).data('type');
        if(type == 'add')
        {
            var temp_stock = stock+1;
            if(max > 0 && temp_stock > max)
            {
                $input.val(max);
                Prompt((window['lang_goods_stock_max_tips'] || '最大限购数量')+max+unit);
                return false;
            }
        } else {
            var temp_stock = stock-1;
            if(temp_stock < min)
            {
                $input.val(min);
                Prompt((window['lang_goods_stock_min_tips'] || '最低起购数量')+min+unit);
                return false;
            }
        }

        CartNumberUpdate($(this), temp_stock);
    });

    // 输入事件
    $(document).on('blur', '.stock-tag input[type="number"]', function()
    {
        // 数量参数
        var $input = $(this);
        var min = parseInt($input.data('min-limit') || 1);
        var max = parseInt($input.data('max-limit'));
        var unit = $input.data('unit') || '';
        var stock = parseInt($input.val());
        if(isNaN(stock))
        {
            stock = min;
        }
        if(max > 0 && stock > max)
        {
            stock = max;
        }
        if(stock < min)
        {
            stock = min;
        }
        $(this).val(stock);
        CartNumberUpdate($(this), stock);
    });

    // 全选/反选
    $(document).on('click', '.select-all-event', function()
    {
        if($(this).find('input').is(':checked'))
        {
            $(this).find('span.el-text').text(window['lang_select_reverse_name'] || '反选');
            $('.am-table').find('input[type="checkbox"]').not(':disabled').uCheck('check');
        } else {
            $(this).find('span.el-text').text(window['lang_select_all_name'] || '全选');
            $('.am-table').find('input[type="checkbox"]').not(':disabled').uCheck('uncheck');
        }

        // 计算选择的商品总数和总价
        CartBaseTotal();
    });

    // 选择
    $(document).on('click', '.am-table input[type="checkbox"]', function()
    {
        // 计算选择的商品总数和总价
        CartBaseTotal();
    });

    // 导航固定
    var $nav = $('.cart-nav');
    var nav_top = $nav.length > 0 ? $nav.offset().top : 0;
    function CartNavPop()
    {
        var scroll = $(document).scrollTop();
        var height = $nav.innerHeight();
        var location = scroll+$(window).height()-height;
        var bottom = ($(window).width() < 641) ? height+'px' : '0';
        if($(window).width() < 641)
        {
            location -= $('.mobile-navigation').innerHeight();
            var bottom = (height-14)+'px';
        } else {
            var bottom = 0;
        }
        if(location < nav_top)
        {
            $nav.css({"position":"fixed", "bottom":bottom, "width":$('.cart-content').width()+"px", "z-index":1000}).addClass('am-box-shadow-top am-background-white');
            $nav.find('.am-container').addClass('am-radius-bottom-right-0 am-radius-bottom-left-0 am-radius-bottom-left-0');
            $('body').css({"padding-bottom":height+"px"});
        } else {
            $nav.css({"position":"relative", "bottom":0, "z-index":0, "width":"100%"}).removeClass('am-box-shadow-top am-background-white');
            $nav.find('.am-container').removeClass('am-radius-bottom-right-0 am-radius-bottom-left-0');
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
        CartNavPop();
    });

    // 结算事件
    $(document).on('click', '.separate-submit', function()
    {
        // 计算选择的商品总数和总价
        CartBaseTotal();

        // 获取购物车id
        var ids = $(this).parents('form').find('input[name="ids"]').val() || 0;
        if(ids == 0)
        {
            Prompt(window['lang_goods_no_choice_tips'] || '请选择商品');
            return false;
        }
    });
});