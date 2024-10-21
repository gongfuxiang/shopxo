var $cart_info_goods_spec = $('.goods-spec-container');
/**
 * 获取规格详情
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-12-14
 * @desc    description
 */
function GoodsCartInfoSpecDetail()
{
    // 是否全部选中
    var sku_count = $('.goods-spec-content .sku-items').length;
    var active_count = $('.goods-spec-content .sku-items li.selected').length;
    if(active_count < sku_count)
    {
        return false;
    }

    // 获取规格值
    var spec = [];
    $('.goods-spec-content .sku-items li.selected').each(function(k, v)
    {
        spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')})
    });
    // 数量
    var stock = $cart_info_goods_spec.find('.number-operate input[type="number"]').val() || 1;

    // 开启进度条
    $.AMUI.progress.start();

    // ajax请求
    $.ajax({
        url: RequestUrlHandle(__goods_spec_detail_url__),
        type: 'post',
        dataType: 'json',
        timeout: 10000,
        data: {"id": $('.goods-spec-content').data('id'), "spec": spec, "stock": stock},
        success: function(res)
        {
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                GoodsCartInfoSpecDetailBackHandle(res.data);
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

/**
 * 获取规格类型
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-12-14
 * @desc    description
 */
function GoodsCartInfoSpecType()
{
    // 是否全部选中
    var sku_count = $('.goods-spec-content .sku-items').length;
    var active_count = $('.goods-spec-content .sku-items li.selected').length;
    if(active_count <= 0 || active_count >= sku_count)
    {
        return false;
    }

    // 获取规格值
    var spec = [];
    $('.goods-spec-content .sku-items li.selected').each(function(k, v)
    {
        spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')})
    });

    // 开启进度条
    $.AMUI.progress.start();

    // ajax请求
    $.ajax({
        url: RequestUrlHandle(__goods_spec_type_url__),
        type: 'post',
        dataType: 'json',
        timeout: 10000,
        data: {"id": $('.goods-spec-content').data('id'), "spec": spec},
        success: function(res)
        {
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                var spec_count = spec.length;
                var index = (spec_count > 0) ? spec_count : 0;
                if(index < sku_count)
                {
                    $('.goods-spec-content .sku-items').eq(index).find('li').each(function(k, v)
                    {
                        $(this).removeClass('sku-dont-choose');
                        var value = $(this).data('value').toString();
                        if(res.data.spec_type.indexOf(value) == -1)
                        {
                            $(this).addClass('sku-items-disabled');
                        } else {
                            $(this).removeClass('sku-items-disabled');
                        }
                    });
                }
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

/**
 * 数量值改变
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-10-05
 * @desc    description
 */
function GoodsNumberChange()
{
    var stock = $cart_info_goods_spec.find('.number-operate input[type="number"]').val() || 1;
    var spec = [];
    var sku_count = $('.goods-spec-content .sku-items').length;
    if(sku_count > 0)
    {
        // 未完全选择规格则返回
        var spec_count = $('.sku-line.selected').length;
        if(spec_count < sku_count)
        {
            return false;
        }

        // 已选规格
        $('.goods-spec-content .sku-items li.selected').each(function(k, v)
        {
            spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')})
        });
    }

    // 开启进度条
    $.AMUI.progress.start();

    // ajax请求
    $.ajax({
        url: RequestUrlHandle(__goods_stock_url__),
        type: 'post',
        dataType: "json",
        timeout: 10000,
        data: {
            "id": $('.goods-spec-content').data('id'),
            "stock": stock,
            "spec": spec
        },
        success: function(res)
        {
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                GoodsCartInfoSpecDetailBackHandle(res.data);
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

/**
 * 商品规格详情返回数据处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-10-05
 * @desc    description
 * @param   {[object]}        data [后端返回数据]
 */
function GoodsCartInfoSpecDetailBackHandle(data)
{
    // 售价
    $cart_info_goods_spec.find('.price').html(__currency_symbol__+data.spec_base.price);
    // 数量处理
    var inventory = parseInt(data.spec_base.inventory);
    var $input = $cart_info_goods_spec.find('.number-operate input[type="number"]');
    var $stock = $cart_info_goods_spec.find('.stock-tips .stock');
    var $origina_price = $cart_info_goods_spec.find('.original-price');

    // 起购数
    var min = parseInt($input.data('original-buy-min-number'));
    var buy_min_number = parseInt(data.spec_base.buy_min_number);
    if(buy_min_number > 0)
    {
        min = buy_min_number;
    }
    $input.attr('min', min);

    // 限购数
    var max = inventory;
    var buy_max_number = parseInt(data.spec_base.buy_max_number);
    if(buy_max_number > 0 && buy_max_number < max)
    {
        max = buy_max_number;
    }
    $input.attr('max', max);
    $stock.text(inventory);

    // 原价
    if(data.spec_base.original_price > 0)
    {
        $origina_price.html(__currency_symbol__+data.spec_base.original_price);
        $origina_price.parents('.items').show();
    } else {
        $origina_price.parents('.items').hide();
    }

    // 已选数量校验、超出规格数量则以规格数量为准
    var stock = parseInt($input.val());
    if(min > 0 && stock < min)
    {
        stock = min;
    }
    if(max > 0 && stock > max)
    {
        stock = max;
    }
    if(stock > inventory)
    {
        stock = inventory;
    }
    $input.val(stock);

    // 起购/限购
    if(min > 0)
    {
        $stock.attr('data-min-limit', min);
    }
    if(max > 0)
    {
        $stock.attr('data-max-limit', max);
    }
}

/**
 * 商品基础数据恢复
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-12-25
 * @desc    description
 */
function GoodsCartInfoBaseRestore()
{
    var $input = $cart_info_goods_spec.find('.number-operate input[type="number"]');
    var $stock = $cart_info_goods_spec.find('.stock-tips .stock');
    var $price = $cart_info_goods_spec.find('.price');
    var $original_price = $cart_info_goods_spec.find('.original-price');
    $input.attr('min', $input.data('original-buy-min-number'));
    $input.attr('max', $stock.data('original-max'));
    $stock.text($stock.data('original-inventory'));
    $stock.attr('data-min-limit', $input.attr('data-original-buy-min-number'));
    $stock.attr('data-max-limit', $input.attr('data-original-buy-max-number'));
    $price.html(__currency_symbol__+$price.data('default-price'));
    if($original_price.length > 0)
    {
        $original_price.html(__currency_symbol__+$original_price.data('default-price'));
    }
}

/**
 * 加入购物车校验
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-13
 * @desc    description
 */
function GoodsCartInfoBuyCartCheck()
{
    // 参数
    var $stock = $cart_info_goods_spec.find('.stock-tips .stock');
    var stock = parseInt($cart_info_goods_spec.find('.number-operate input').val() || 1);
    var inventory = parseInt($stock.text());
    var min = $stock.attr('data-min-limit') || 1;
    var max = $stock.attr('data-max-limit') || 0;
    var unit = $stock.data('unit') || '';
    if(stock < min)
    {
        Prompt((window['lang_goods_stock_min_tips'] || '最低起购数量')+min+unit);
        return false;
    }
    if(max > 0 && stock > max)
    {
        Prompt((window['lang_goods_stock_max_tips'] || '最大限购数量')+max+unit);
        return false;
    }
    if(stock > inventory)
    {
        Prompt((window['lang_goods_inventory_number_tips'] || '库存数量')+inventory+unit);
        return false;
    }

    // 规格
    var spec = [];
    var sku_count = $('.goods-spec-content .sku-items').length;
    if(sku_count > 0)
    {
        var spec_count = $('.sku-line.selected').length;
        if(spec_count < sku_count)
        {
            $('.goods-spec-content .sku-items').each(function(k, v)
            {
                if($(this).find('.sku-line.selected').length == 0)
                {
                    $(this).addClass('sku-not-active');
                }
            });
            Prompt(window['lang_goods_no_choice_spec_tips'] || '请选择规格');
            return false;
        }

        // 已选规格
        spec = GoodsCartInfoSelectedSpec();
    }
    return {
        stock: stock,
        spec: spec,
    };
}

/**
 * 已选规格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-10-05
 * @desc    description
 */
function GoodsCartInfoSelectedSpec()
{
    // 规格
    var spec = [];
    var sku_count = $('.sku-items').length;
    if(sku_count > 0)
    {
        var spec_count = $('.sku-line.selected').length;
        if(spec_count >= sku_count)
        {
            $('.sku-items li.selected').each(function(k, v)
            {
                spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')});
            });
        }
    }
    return spec;
}

$(function()
{
    // 商品规格选择
    $(document).on('click', '.spec-options ul>li', function()
    {        
        // 规格处理
        var length = $('.goods-spec-content .sku-items').length;
        var index = $(this).parents('.sku-items').index();

        if($(this).hasClass('selected'))
        {
            $(this).removeClass('selected');

            // 去掉元素之后的禁止
            $('.goods-spec-content .sku-items').each(function(k, v)
            {
                if(k > index)
                {
                    $(this).find('li').removeClass('sku-items-disabled').removeClass('selected').addClass('sku-dont-choose');
                }
            });

            // 数据还原
            GoodsCartInfoBaseRestore();

        } else {
            if($(this).hasClass('sku-items-disabled') || $(this).hasClass('sku-dont-choose'))
            {
                return false;
            }
            $(this).addClass('selected').siblings('li').removeClass('selected');
            $(this).parents('.sku-items').removeClass('sku-not-active');

            // 去掉元素之后的禁止
            if(index < length)
            {
                $('.goods-spec-content .sku-items').each(function(k, v)
                {
                    if(k > index)
                    {
                        $(this).find('li').removeClass('sku-items-disabled').removeClass('selected').addClass('sku-dont-choose');
                    }
                });
            }

            // 后面是否还有未选择的规格
            if(index < length-1)
            {
                // 数据还原
                GoodsCartInfoBaseRestore();
            }

            // 获取下一个规格类型
            GoodsCartInfoSpecType();

            // 获取规格详情
            GoodsCartInfoSpecDetail();
        }
    });

    // 数量操作
    $(document).on('click', '.goods-spec-content .number-operate span', function()
    {
        var $input = $(this).parents('.number-operate').find('input');
        var min = parseInt($input.attr('min') || 0);
        var max = parseInt($input.attr('max') || 0);
        var stock = parseInt($input.val());
        var type = $(this).data('type');
        var temp_stock = (type == 'add') ? stock+1 : stock-1;
        var unit = $cart_info_goods_spec.find('.stock-tips .stock').data('unit') || '';
        if(temp_stock < min)
        {
            $input.val(min);
            Prompt((window['lang_goods_stock_min_tips'] || '最低起购数量')+min+unit);
            return false;
        }
        if(temp_stock > max)
        {
            $input.val(max);
            Prompt((window['lang_goods_stock_max_tips'] || '最大限购数量')+max+unit);
            return false;
        }
        $input.val(temp_stock);

        // 数量更新事件
        GoodsNumberChange();
    });
    // 手动输入、失去焦点
    $(document).on('blur', '.goods-spec-content .number-operate input', function()
    {
        var $stock_tips = $cart_info_goods_spec.find('.stock-tips .stock');
        var min = parseInt($stock_tips.attr('data-min-limit')) || 1;
        var max = parseInt($stock_tips.attr('data-max-limit')) || 0;
        var stock = parseInt($(this).val());
        var inventory = parseInt($stock_tips.text());
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
        if(stock > inventory)
        {
            stock = inventory;
        }
        $(this).val(stock);

        // 数量更新事件
        GoodsNumberChange();
    });
    $(document).on('focus', '.goods-spec-content .number-operate input', function()
    {
        $(this).select();
    });

    // 加入购物车
    $(document).on('click', 'form.form-validation button[type="submit"]', function()
    {
        // 参数
        var params = GoodsCartInfoBuyCartCheck();
        if(params === false)
        {
            return false;
        }
        $('form.form-validation input[name="spec"]').val(JSON.stringify(params.spec));
        return true;
    });
});