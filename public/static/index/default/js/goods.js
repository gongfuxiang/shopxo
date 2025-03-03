// 规格弹窗PC显示
function GoodsBuyPoptitPcShow()
{
    $(document.body).css('position', 'static');
    $('.theme-signin-left').scrollTop(0);
    $('.theme-popover-mask').hide();
    $('.theme-popover').slideDown(0);
}
// 规格弹窗关闭
function GoodsBuyPoptitClose()
{
    if($(window).width() < 1025)
    {
        $(document.body).css('position', 'static');
        $('.theme-signin-left').scrollTop(0);
        $('.theme-popover-mask').hide();
        $('.theme-popover').slideUp(100);
    }
}

/**
 * 评论记录ajax请求
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-05-13T21:39:55+0800
 * @param    {[int]}                 page [分页值]
 */
function GoodsCommentsHtml(page)
{
    if($('.goods-comment').length > 0)
    {
        if($('.goods-comment-content article').length <= 0)
        {
            $('.goods-page-no-data').removeClass('none');
            $('.evaluate').removeClass('evaluate-no-data');
            $('.score-container').removeClass('evaluate-no-data');
            $('.goods-page-no-data p').text(window['lang_loading_tips'] || '加载中...');
        } else {
            $('.goods-page-no-data').addClass('none');
            $('.evaluate').addClass('evaluate-no-data');
            $('.score-container').addClass('evaluate-no-data');
        }

        $.ajax({
            url: RequestUrlHandle($('.goods-comment').data('url')),
            type: 'POST',
            data: {"goods_id": $('.goods-comment').data('goods-id'), "page": page || 1},
            dataType: 'json',
            success: function(res)
            {
                $('.goods-page-no-data').addClass('none');
                $('.evaluate').addClass('evaluate-no-data');
                $('.score-container').addClass('evaluate-no-data');
                if(res.code == 0)
                {
                    $('.goods-comment-content').html(res.data.data);
                    $('.goods-page-container').html(PageLibrary(res.data.total, res.data.number, page, 2));
                    
    				// 图片预览初始化
    				$.AMUI.figure.init();
                    // 图片画廊初始化
                    $.AMUI.gallery.init();
                }

                // 没有数据
                if($('.goods-comment-content article').length <= 0)
                {
                    $('.goods-page-no-data').removeClass('none');
                    $('.evaluate').removeClass('evaluate-no-data');
                    $('.score-container').removeClass('evaluate-no-data');
                    $('.goods-page-no-data p').text(window['lang_comment_no_data_tips'] || '此商品暂时还没有评价哦~');
                }
            },
            error: function(xhr, type)
            {
                $('.goods-page-no-data').removeClass('none');
                $('.evaluate').removeClass('evaluate-no-data');
                $('.score-container').removeClass('evaluate-no-data');
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    }
}

/**
 * 已选规格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-10-05
 * @desc    description
 */
function GoodsSelectedSpec()
{
    // 规格
    var spec = [];
    var sku_count = $('.sku-items').length;
    if(sku_count > 0)
    {
        var spec_count = $('.sku-line.selected').length;
        if(spec_count >= sku_count)
        {
            $('.theme-signin-left .sku-items li.selected').each(function(k, v)
            {
                spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')});
            });
        }
    }
    return spec;
}

/**
 * 购买/加入购物车校验
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-13
 * @desc    description
 * @param   {[object]}        e [当前标签对象]
 */
function BuyCartCheck(e)
{
    // 参数
    var $number_tag = $('.operate-number-container');
    var stock = parseInt($('.operate-number-input').val() || 1);
    var inventory = parseInt($('.stock-tips .stock').text());
    var min = parseInt($number_tag.attr('data-min-limit') || 1);
    var max = parseInt($number_tag.attr('data-max-limit') || 0);
    var unit = $number_tag.data('unit') || '';
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
    var sku_count = $('.sku-items').length;
    if(sku_count > 0)
    {
        var spec_count = $('.sku-line.selected').length;
        if(spec_count < sku_count)
        {
            $('.sku-items').each(function(k, v)
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
        spec = GoodsSelectedSpec();
    }
    var type =( typeof(e) == 'object') ? e.attr('data-type') : null;
    return {
        "id": $('.system-goods-detail').data('id'),
        "stock": stock,
        "spec": spec,
        "type": type
    };
}

/**
 * 购买/加入购物车处理
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-13
 * @desc    description
 * @param   {[object]}        e [当前标签对象]
 */
function BuyCartHandle(e)
{
    // 参数
    var params = BuyCartCheck(e);
    if(params === false)
    {
        return false;
    }

    // 操作类型
    var goods_data = encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify([{
            goods_id: params.id,
            stock: params.stock,
            spec: params.spec
        }]))));
    switch(params.type)
    {
        // 立即购买
        case 'buy' :
            var $form = $('form.buy-form');
            $form.find('input[name="goods_data"]').val(goods_data);
            $form.find('button[type="submit"]').trigger('click');
            break;

        // 加入购物车
        case 'cart' :
            var $form = $('form.cart-form');
            $form.find('input[name="goods_data"]').val(goods_data);
            $form.find('button[type="submit"]').trigger('click');
            GoodsBuyPoptitClose();
            break;

        // 默认
        default :
            console.warn(window['lang_operate_params_error'] || '操作参数配置有误');
    }
    return true;
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
function GoodsSpecDetailBackHandle(data)
{
    // 售价
    $('.text-info .price-now b').text(data.spec_base.price);
    $('.goods-sale-price-value').text(data.spec_base.price);
    // 数量处理
    var inventory = parseInt(data.spec_base.inventory);
    var $number_tag = $('.operate-number-container');
    var $input = $('.operate-number-input');
    var $stock = $('.stock-tips .stock');
    var $origina_price_value = $('.goods-original-price-value b');

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
        $origina_price_value.text(data.spec_base.original_price);
        $origina_price_value.parents('.items').show();
    } else {
        $origina_price_value.parents('.items').hide();
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

    // 扩展数据处理
    var extends_element = data.extends_element || [];
    if(extends_element.length > 0)
    {
        for(var i in extends_element)
        {
            if((extends_element[i]['element'] || null) != null && extends_element[i]['content'] !== null)
            {
                $(extends_element[i]['element']).prop('outerHTML', extends_element[i]['content']);
            }
        }
    }

    // 起购/限购
    if(min > 0)
    {
        $number_tag.attr('data-min-limit', min);
    }
    if(max > 0)
    {
        $number_tag.attr('data-max-limit', max);
    }
}

/**
 * 获取规格详情
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-12-14
 * @desc    description
 */
function GoodsSpecDetail()
{
    // 是否全部选中
    var sku_count = $('.theme-signin-left .sku-items').length;
    var active_count = $('.theme-signin-left .sku-items li.selected').length;
    if(active_count < sku_count)
    {
        return false;
    }

    // 获取规格值
    var spec = GoodsSelectedSpec();

    // 已填写数量
    var stock = parseInt($('.operate-number-input').val()) || 1;

    // 开启进度条
    $.AMUI.progress.start();

    // ajax请求
    $.ajax({
        url: RequestUrlHandle($('.system-goods-detail').data('spec-detail-ajax-url')),
        type: 'post',
        dataType: "json",
        timeout: 10000,
        data: {
            "id": $('.system-goods-detail').data('id'),
            "stock": stock,
            "spec": spec
        },
        success: function(res)
        {
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                GoodsSpecDetailBackHandle(res.data);
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
function GoodsSpecType()
{
    // 是否全部选中
    var sku_count = $('.theme-signin-left .sku-items').length;
    var active_count = $('.theme-signin-left .sku-items li.selected').length;
    if(active_count <= 0 || active_count >= sku_count)
    {
        return false;
    }

    // 获取规格值
    var spec = [];
    $('.theme-signin-left .sku-items li.selected').each(function(k, v)
    {
        spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')})
    });

    // 开启进度条
    $.AMUI.progress.start();

    // ajax请求
    $.ajax({
        url: RequestUrlHandle($('.system-goods-detail').data('spec-type-ajax-url')),
        type: 'post',
        dataType: "json",
        timeout: 10000,
        data: {"id": $('.system-goods-detail').data('id'), "spec": spec},
        success: function(res)
        {
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                var spec_count = spec.length;
                var index = (spec_count > 0) ? spec_count : 0;
                if(index < sku_count)
                {
                    $('.theme-signin-left .sku-items').eq(index).find('li').each(function(k, v)
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

                // 扩展数据处理
                var extends_element = res.data.extends_element || [];
                if(extends_element.length > 0)
                {
                    for(var i in extends_element)
                    {
                        if((extends_element[i]['element'] || null) != null && extends_element[i]['content'] !== null)
                        {
                            $(extends_element[i]['element']).prop('outerHTML', extends_element[i]['content']);
                        }
                    }
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
 * 商品规格选择改变浏览器url地址
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-04-10
 * @desc    description
 */
function GoodsBrowserHistoryUrlChange()
{
    // 仅参数存在spec则改变url地址
    if(GetQueryValue('spec') != false)
    {
        history.pushState({}, '', UrlFieldReplace('spec', null));
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
function GoodsBaseRestore()
{
    var $number_tag = $('.operate-number-container');
    var $input = $('.operate-number-input');
    var $stock = $('.stock-tips .stock');
    var $price = $('.goods-sale-price-value');
    var $price_now = $('.text-info .price-now');
    var $original_price_value = $('.tb-detail-panel-base .goods-original-price-value');
    $input.attr('min', $input.data('original-buy-min-number'));
    $input.attr('max', $number_tag.data('original-max'));
    $stock.text($number_tag.data('original-inventory'));
    $number_tag.attr('data-min-limit', $input.attr('data-original-buy-min-number'));
    $number_tag.attr('data-max-limit', $input.attr('data-original-buy-max-number'));

    // 价格处理
    $price_now.find('b').text($price_now.data('original-price'));
    $price.text($price.data('original-price'));
    if($original_price_value.length > 0)
    {
        $original_price_value.each(function(k, v)
        {
            var price = $(this).data('original-price');
            if(price !== undefined)
            {
                $(this).find('b').text(price);
            }
        });
    }
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
    var stock = parseInt($('.operate-number-input').val()) || 1;
    var spec = [];
    var sku_count = $('.sku-items').length;
    if(sku_count > 0)
    {
        // 未完全选择规格则返回
        var spec_count = $('.sku-line.selected').length;
        if(spec_count < sku_count)
        {
            return false;
        }

        // 已选规格
        spec = GoodsSelectedSpec();
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
            "id": $('.system-goods-detail').data('id'),
            "stock": stock,
            "spec": spec
        },
        success: function(res)
        {
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                GoodsSpecDetailBackHandle(res.data);
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
 * 规格选择显示
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-16
 * @desc    description
 */
function SpecPopupShow(e)
{
    $(document.body).css({"position":"fixed", "width":"100%"});
    $('.theme-popover-mask').show();
    $('.theme-popover').slideDown(200);
    $('.theme-popover .confirm').attr('data-type', e.data('type') || 'buy');    
}

$(function() {
    // 购物车表单初始化
    FromInit('form.cart-form');

    // 指定规格初始化选中
    var spec = decodeURIComponent(GetQueryValue('spec') || '');
    if((spec || null) != null)
    {
        spec = spec.split('|');
        if($('.sku-container').length > 0 && $('.sku-container .sku-items').length > 0 && $('.sku-container .sku-items').length == spec.length && $('.buy-submit-container').length > 0 && ($('.buy-submit-container button.buy-submit').length > 0 || $('.buy-submit-container button.cart-submit').length > 0) && $('.tb-detail-panel-top-price-content .goods-sale-price').length > 0)
        {
            var $price = $('.tb-detail-panel-base .tb-detail-panel-top-price-content .items.price dd b, .tb-detail-panel-base .tb-detail-panel-top-price-content .goods-sale-price .goods-sale-price-value');
            // 先清除价格展示信息
            $price.text('...');
            var num = 0;
            var timer = setInterval(function()
            {
                $('.sku-container .sku-items').each(function(k, v)
                {
                    // 清除价格展示信息、避免获取价格类型赋值
                    $price.text('...');
                    // 必须不存在已选择项
                    if($(this).find('ul li.selected').length <= 0)
                    {
                        var temp_spec = spec[k];
                        var status = false;
                        $(this).find('ul li').each(function(ks, vs)
                        {
                            // 必须是可选和未选
                            if(!status && !$(this).hasClass('sku-items-disabled') && !$(this).hasClass('sku-dont-choose') && temp_spec == $(this).attr('data-value'))
                            {
                                $(this).trigger('click');
                                status = true;
                                num++;
                            }
                        });
                    }
                });
                if(num >= $('.sku-container .sku-items').length)
                {
                    clearInterval(timer);
                }
            }, 100);
            setTimeout(function()
            {
                clearInterval(timer);
            }, 20000);
        }
    }

    // 商品规格选择
    $('.theme-options').each(function()
    {
        $(this).find('ul>li').on('click', function()
        {
            // 规格处理
            var length = $('.theme-signin-left .sku-container .sku-items').length;
            var index = $(this).parents('.sku-items').index();
            if($(this).hasClass('selected'))
            {
                $(this).removeClass('selected');

                // 去掉元素之后的禁止
                $('.theme-signin-left .sku-container .sku-items').each(function(k, v)
                {
                    if(k > index)
                    {
                        $(this).find('li').removeClass('sku-items-disabled').removeClass('selected').addClass('sku-dont-choose');
                    }
                });

                // 数据还原
                GoodsBaseRestore();

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
                    $('.theme-signin-left .sku-container .sku-items').each(function(k, v)
                    {
                        if(k > index)
                        {
                            $(this).find('li').removeClass('sku-items-disabled').removeClass('selected').addClass('sku-dont-choose');
                        }
                    });
                }

                // 是否存在规格图片
                var spec_images = $(this).data('type-images') || null;
                if(spec_images != null)
                {
                    $('.jqzoom').attr('src', spec_images);
                    $('.jqzoom').attr('rel', spec_images);
                    $('.img-info img').attr('src', spec_images);
                }

                // 后面是否还有未选择的规格
                if(index < length-1)
                {
                    // 数据还原
                    GoodsBaseRestore();
                }

                // 获取下一个规格类型
                GoodsSpecType();

                // 获取规格详情
                GoodsSpecDetail();
            }

            // 浏览器地址改变
            GoodsBrowserHistoryUrlChange();
        });
    });

    // 放大镜初始化
    $('.jqzoom').imagezoom({
        yzoom: 398
    });
    $(document).on('mouseover', '#thumblist li img', function() {
        $(this).parents('li').addClass('tb-selected').siblings().removeClass('tb-selected');
        var img = $(this).attr('mid');
        $('.jqzoom').attr('src', img);
        $('.jqzoom').attr('rel', img);
    });

    // 规格选择显示事件
    $(document).on('click', '.mini-spec-event', function()
    {
        SpecPopupShow($(this));
    });

    //弹出规格选择
    $(document).on('click', '.buy-event', function() {
        if($(window).width() < 1025) {
            // 是否登录
            if(__user_id__ != 0)
            {
                SpecPopupShow($(this));
            }
        } else {
            GoodsBuyPoptitPcShow();
        }
    });
    $(document).on('click', '.theme-poptit .close, .btn-op .close', function() {
        GoodsBuyPoptitClose();
    });

    // 购买
    $(document).on('click', '.buy-submit, .cart-submit', function()
    {
        // 是否登录
        if(__user_id__ != 0)
        {
            if($(window).width() >= 1025)
            {
                BuyCartHandle($(this));
            }
        }
    });
    // 确认
    $(document).on('click', '.theme-popover .confirm', function()
    {
        // 是否登录
        if(__user_id__ != 0)
        {
            if($(window).width() < 1025)
            {
                BuyCartHandle($(this));
            }
        }
    });

    // 视频
    var player = null;
    $(document).on('click', '.goods-video-submit-start', function()
    {
        // 当前
        var is_wap = $(window).width() < 1025;
        var cu_ent = '.goods-video-'+(is_wap ? 'wap' : 'pc')+'-container';
        $(cu_ent).removeClass('none');
        $('.goods-video-submit-close').removeClass('none');
        $('.goods-video-submit-start').addClass('none');

        // 非当前
        $('.goods-video-'+(is_wap ? 'pc' : 'wap')+'-container').html('');

        // 调用播放器
        player = new ckplayer({
            container: cu_ent,
            video: $(cu_ent).data('url'),
            autoplay: true,
            menu: null
        });
    });
    $(document).on('click', '.goods-video-submit-close', function()
    {
        $('.goods-video-container').addClass('none');
        $('.goods-video-submit-close').addClass('none');
        $('.goods-video-submit-start').removeClass('none');
        player.pause();
    });

    //获得文本框对象
    var $number_tag = $('.operate-number-container');
    var $input = $('.operate-number-input');
    var unit = $number_tag.data('unit') || '';

    // 手动输入
    $input.on('blur', function()
    {
        var min = parseInt($number_tag.attr('data-min-limit') || 1);
        var max = parseInt($number_tag.attr('data-max-limit') || 0);
        var stock = parseInt($(this).val());
        var inventory = parseInt($number_tag.attr('data-original-max') || 0);
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
        $input.val(stock);

        // 数量更新事件
        GoodsNumberChange();
    });

    //数量增加操作
    $(document).on('click', '.operate-number-inc', function()
    {
        var max = parseInt($number_tag.attr('data-max-limit') || 0);
        var inventory = parseInt($number_tag.attr('data-original-max') || 0);
        var stock = parseInt($input.val())+1;
        if(max > 0 && stock > max)
        {
            $input.val(max);
            Prompt((window['lang_goods_stock_max_tips'] || '最大限购数量')+max+unit);
            return false;
        }
        if(stock > inventory)
        {
            $input.val(inventory);
            Prompt((window['lang_goods_inventory_number_tips'] || '库存数量')+inventory+unit);
            return false;
        }
        $input.val(stock);

        // 数量更新事件
        GoodsNumberChange();
    });
    //数量减少操作
    $(document).on('click', '.operate-number-dec', function()
    {
        var min = parseInt($number_tag.attr('data-min-limit') || 1);
        var value = parseInt($input.val())-1;
        if(value < min)
        {
            $input.val(min);
            Prompt((window['lang_goods_stock_min_tips'] || '最低起购数量')+min+unit);
            return false;
        }
        $input.val(value);

        // 数量更新事件
        GoodsNumberChange();
    });

    // 评论
    GoodsCommentsHtml(1);
    $(document).on('click', '.goods-page-container .pagelibrary a', function()
    {
        var page = $(this).data('page') || null;
        if(page != null)
        {
            // 获取数据
            GoodsCommentsHtml(page);

            // 回到顶部位置
            $(window).smoothScroll({position: $('.introduce-main').offset().top});
        }
    });

    // tab事件
    $(document).on('click', '.introduce-main .am-tabs li', function()
    {
        $(window).smoothScroll({position: $('.introduce-main').offset().top});
    });
});

// 浏览器窗口实时事件
$(window).resize(function()
{
    // 规格显示/隐藏处理
    if($(window).width() < 1025)
    {
        if(parseInt(__is_mobile__ || 0) != 1)
        {
            GoodsBuyPoptitClose();
        }
    } else {
        GoodsBuyPoptitPcShow();
    }
});