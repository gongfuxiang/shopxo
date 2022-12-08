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
    var stock = $cart_info_goods_spec.find('.number-operate input[type="number"]').val() || 1;

    // 开启进度条
    $.AMUI.progress.start();

    // ajax请求
    $.ajax({
        url: $cart_info_goods_spec.data('specdetail-url'),
        type: 'post',
        dataType: "json",
        timeout: 10000,
        data: {"id": $('.goods-spec-content').data('id'), "spec": spec, "stock": stock},
        success: function(result)
        {
            $.AMUI.progress.done();
            if(result.code == 0)
            {
                // 售价
                $cart_info_goods_spec.find('.price').text(__currency_symbol__+result.data.spec_base.price);
                // 限购数量是否已大于库存
                var max = parseInt(result.data.spec_base.inventory);
                var $stock = $cart_info_goods_spec.find('.stock-tips .stock');
                var limit_max = parseInt($stock.attr('data-max-limit') || 0);
                if(limit_max > 0)
                {
                    max = (max < limit_max) ? max : limit_max;
                    $stock.attr('data-max-limit', max);
                }
                $cart_info_goods_spec.find('.number-operate input[type="number"]').attr('max', max);

                // 库存
                $stock.text(result.data.spec_base.inventory);
                if(result.data.spec_base.original_price > 0)
                {
                    $cart_info_goods_spec.find('.original-price').attr('data-price', result.data.spec_base.original_price);
                    $cart_info_goods_spec.find('.original-price').text(__currency_symbol__+result.data.spec_base.original_price);
                    $cart_info_goods_spec.find('.original-price').show();
                } else {
                    $('.original-price').hide();
                }
            } else {
                Prompt(result.msg);
            }
        },
        error: function(xhr, type)
        {
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
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
        url: $cart_info_goods_spec.data('spectype-url'),
        type: 'post',
        dataType: "json",
        timeout: 10000,
        data: {"id": $('.goods-spec-content').data('id'), "spec": spec},
        success: function(result)
        {
            $.AMUI.progress.done();
            if(result.code == 0)
            {
                var spec_count = spec.length;
                var index = (spec_count > 0) ? spec_count : 0;
                if(index < sku_count)
                {
                    $('.goods-spec-content .sku-items').eq(index).find('li').each(function(k, v)
                    {
                        $(this).removeClass('sku-dont-choose');
                        var value = $(this).data('value').toString();
                        if(result.data.spec_type.indexOf(value) == -1)
                        {
                            $(this).addClass('sku-items-disabled');
                        } else {
                            $(this).removeClass('sku-items-disabled');
                        }
                    });
                }
            } else {
                Prompt(result.msg);
            }
        },
        error: function(xhr, type)
        {
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
        }
    });
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
    $cart_info_goods_spec.find('.price').text(__currency_symbol__+$cart_info_goods_spec.find('.price').data('default-price'));
    $cart_info_goods_spec.find('.number-operate input[type="number"]').attr('max', $cart_info_goods_spec.find('.number-operate input[type="number"]').data('original-max'));
    $cart_info_goods_spec.find('.stock-tips .stock').text($cart_info_goods_spec.find('.stock-tips .stock').data('original-stock'));
    if($cart_info_goods_spec.find('.original-price').length > 0)
    {
        $cart_info_goods_spec.find('.original-price').text(__currency_symbol__+$cart_info_goods_spec.find('.original-price').data('default-price'));
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
    var min = $stock.data('min-limit') || 1;
    var max = $stock.data('max-limit') || 0;
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
        // 切换规格购买数量清空
        $cart_info_goods_spec.find('.number-operate input').val($cart_info_goods_spec.find('.stock-tips .stock').data('min-limit') || 1);
        
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
        if(temp_stock < min)
        {
            temp_stock = min;
            Prompt('不能小于最低限购('+min+')');
        }
        if(temp_stock > max)
        {
            temp_stock = max;
            Prompt('超过最大限购('+max+')');
        }
        $input.val(temp_stock).blur();
    });
    $(document).on('blur', '.goods-spec-content .number-operate input', function()
    {
        if(parseInt($(this).val() || 0) <= 0)
        {
            $(this).val($(this).attr('min'));
        }
    });
    $(document).on('focus', '.goods-spec-content .number-operate input', function()
    {
        $(this).select();
    });

    // 加入购物车
    $('form.form-validation button[type="submit"]').on('click', function()
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