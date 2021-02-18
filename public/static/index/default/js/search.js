/**
 * 获取商品列表
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-01-08
 * @desc    description
 * @param   {[int]}        page [分页值]
 */
function GetGoodsList(page)
{
    // 清除条件显隐
    if($('.map-item>li>.map-right>ul>li.active').length > 0)
    {
        $('.map-remove-submit').removeClass('am-hide');
    } else {
        $('.map-remove-submit').addClass('am-hide');
    }

    // 请求参数处理
    var data = {
        "wd": $('#search-input').val() || '',
        "category_id": $('.search-container').data('category-id') || 0,
        "brand_id": $('.search-container').data('brand-id') || 0,
        "page": page || parseInt($('.search-pages-submit').attr('data-page')) || 1,
        "order_by_field": $('.sort-nav li.active').attr('data-field') || 'default',
        "order_by_type": $('.sort-nav li.active').attr('data-type') == 'asc' ? 'desc' : 'asc',
    };
    var index = 0;
    $('.map-item>li>.map-right>ul>li.active').each(function(k, v)
    {
        var field = $(this).parents('.map-item>li').data('field') || null;
        var value = $(this).data('value') || null;
        if(field != null && value != null)
        {
            if((data[field] || null) == null)
            {
                data[field] = {};
                index = 0;
            }
            data[field][index] = value;
            index++;
        }
    });

    // 清空数据
    if(data.page == 1)
    {
        $('.search-list').html('');
    }

    // 页面提示处理
    $('.loding-view').show();
    $('.search-pages-submit').hide();
    $('.table-no').hide();

    // 请求数据
    $.ajax({
        url: $('.search-pages-submit').data('url'),
        type: 'POST',
        dataType: 'json',
        timeout: 30000,
        data: data,
        success:function(result)
        {
            $('.loding-view').hide();
            if(result.code == 0)
            {
                $('.search-list').append(result.data.data);
                $('.search-pages-submit').attr('data-page', data.page+1);
                $('.search-pages-submit').attr('disabled', (result.data.page_total <= 1));
                $('.search-pages-submit').show();
                $('.table-no').hide();
            } else if(result.code == -100) {
                if($('.search-list li').length == 0)
                {
                    $('.table-no').show();
                    $('.search-pages-submit').hide();
                } else {
                    $('.table-no').hide();
                    $('.search-pages-submit').show();
                    $('.search-pages-submit').attr('disabled', true);
                }
                Prompt(result.msg);
            } else {
                Prompt(result.msg);
            }

            // 总数处理
            var total = result.data.total || 0;
            if(total > 0 || $('.search-list li').length == 0)
            {
                $('.map-result-count').text(total);
            }
        },
        error:function(xhr, type)
        {
            $('.loding-view').hide();
            $('.search-pages-submit').hide();
            $('.table-no').show();
            Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
        }
    });
}

/**
 * 更多条件展开处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-01-10
 * @desc    description
 */
function MapMoreSubmitHandle()
{
    $('.map-images-text-items,.map-text-items').each(function(k, v)
    {
        var height = $(this).find('>ul').innerHeight();
        var max_height = $(this).hasClass('map-images-text-items') ? 66 : 56;
        if(height > max_height)
        {
            $(this).find('.map-more-submit').removeClass('am-hide');
        }
    });
}

$(function()
{
    // 更多条件展开处理
    MapMoreSubmitHandle();

    // 默认加载数据
    GetGoodsList(1);

    // 手机模式下更多条件开启
    $(document).on('click', '.map-offcanvas-submit', function()
    {
        MapMoreSubmitHandle();
    });

    // 筛选操作
    $(document).on('click', '.map-item>li>.map-right>ul>li', function()
    {
        if(!$(this).hasClass('disabled'))
        {
            if($(this).hasClass('active'))
            {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }
            GetGoodsList(1);
        }
    });

    // 清除条件
    $(document).on('click', '.map-remove-submit', function()
    {
        $('.map-item>li>.map-right>ul>li').removeClass('active');
        GetGoodsList(1);
    });

    // 条件展开/隐藏
    $(document).on('click', '.map-item .map-more-submit', function()
    {
        var $parents = $(this).parents('.map-right');
        var height = $parents.hasClass('map-images-text-items') ? '66px' : '56px';
        if($parents.css('height') == height)
        {
            $parents.css('height', 'auto');
        } else {
            $parents.css('height', height);
        }
    });

    // 排序
    $(document).on('click', '.sort-nav li', function()
    {
        var type = $(this).attr('data-type');
        $('.sort-nav li').attr('data-type', 'desc');
        $('.sort-nav li a i').removeClass('am-icon-long-arrow-up').addClass('am-icon-long-arrow-down');

        if($(this).hasClass('active'))
        {
            if(type == 'asc')
            {
                $(this).attr('data-type', 'desc');
                $(this).find('a i').removeClass('am-icon-long-arrow-down').addClass('am-icon-long-arrow-up');
            } else {
                $(this).attr('data-type', 'asc');
                $(this).find('a i').removeClass('am-icon-long-arrow-up').addClass('am-icon-long-arrow-down');
            }
        } else {
            $('.sort-nav li').removeClass('active');
            $(this).addClass('active');
            $(this).attr('data-type', 'asc');
        }
        GetGoodsList(1);
    });

    // 加载更多数据
    $(document).on('click', '.search-pages-submit', function()
    {
        GetGoodsList();
    });
});