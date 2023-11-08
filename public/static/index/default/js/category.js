// 商品搜索
function CategoryGoodsSearchAjax(page = 1, page_size = null) {
    // 选中的分类id（三级没有则选择二级，二级没有则选择一级）
    var category_id = parseInt($('.zero-search-right .zero-right-title a.active').data('id') || 0);
    if(category_id == 0)
    {
        category_id = parseInt($('.zero-search-left .zero-left ul li.active').data('id') || 0);
        if(category_id == 0)
        {
            category_id = parseInt($('.zero-search-top ul li.active').data('id') || 0);
        }
    }

    // ajax
    $.ajax({
        url: RequestUrlHandle($('.zero-search').data('url')),
        type: 'POST',
        data: {
            category_id: category_id,
            page: page || 1,
            page_size:  page_size || parseInt($('.am-pagination-container input[name="page_size"]').val() || 20)
        },
        dataType: 'json',
        success: function (res) {
            if (res.code == 0) {
                $('.zero-right-item').html(res.data.data);
                if (res.data.total > 0) {
                    $('.zero-right-page').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 2, true));
                } else {
                    $('.zero-right-page').empty();
                }
            }
        },
        error: function (xhr, type) {
            $('.goods-page-no-data').removeClass('none');
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}
$(function () {
    // 商品初始化
    if($('.zero-search').length > 0)
    {
        CategoryGoodsSearchAjax();
    }

    // 一级分类显/隐操作
    $(document).on('mouseover', '.category-list-container ul.category-nav-hover li', function () {
        var index = $(this).index();
        $('.category-list-container ul.category-nav-hover li').removeClass('active');
        $(this).addClass('active');
        $('.category-list-container .category-content').addClass('none');
        $('.category-list-container .category-content-' + index).removeClass('none');
    });

    // 一级分类双击进入商品搜索页
    $(document).on('dblclick', '.category-list-container ul.category-nav-hover li', function () {
        var url = $(this).data('url') || null;
        if (url != null) {
            window.location.href = url;
        }
    });

    // 一级菜单点击
    $(document).on('click', '.zero-title ul li', function () {
        var data = $(this).data('json');
        $('.zero-right-title a').remove();
        $('.zero-right-title').hide();
        $(this).addClass('active').siblings().removeClass('active')
        if (data) {
            var json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8));
            if (json.length > 0) {
                var menuHtml = '';
                json.forEach(item => {
                    var childJson = encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(item.items))));
                    menuHtml += '<li class="am-text-break" data-json="' + childJson + '" data-id="' + item.id + '">' + item.name + '</li>';
                });
                $('.zero-left ul li').eq(0).siblings().remove();
                $('.zero-left ul li').eq(0).after(menuHtml);
            }
        }
        CategoryGoodsSearchAjax();
        $('.zero-left ul li').eq(0).addClass('active').siblings().removeClass('active');
    })
    // 一级菜单鼠标移入移出效果
    $('.category-list-container .model-one li a').hover(function () {
        if($(this).find('.category-img-active').length > 0)
        {
            $(this).find('.category-img').addClass('am-hide');
            $(this).find('.category-img-active').removeClass('am-hide');
        }
    }, function() {
        if($(this).find('.category-img-active').length > 0)
        {
            $(this).find('.category-img').removeClass('am-hide');
            $(this).find('.category-img-active').addClass('am-hide');
        }
    });
    // 二级菜单点击
    $(document).on('click', '.zero-left ul li', function () {
        var data = $(this).data('json') || null;
        $('.zero-right-title a').remove();
        $('.zero-right-title').hide();
        if (data != null) {
            var json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8));
            if (json.length > 0) {
                var menuHtml = '';
                menuHtml += '<a class="am-radius active" data-id="">' + ($('.zero-search').data('all-name') || '全部') + '</a>'
                json.forEach(item => {
                    var childJson = encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(item.items))));
                    menuHtml += '<a class="am-radius" data-id="' + item.id + '">' + item.name + '</a>';
                });
                $('.zero-right-title .am-text-nowrap').empty().append(menuHtml);
                $('.zero-right-title').show();
            }
        }

        $(this).addClass('active').siblings().removeClass('active');
        CategoryGoodsSearchAjax();
    });
    // 三级菜单点击
    $(document).on('click', '.zero-right-title a', function () {
        $(this).addClass('active').siblings().removeClass('active');
        CategoryGoodsSearchAjax();
    });

    // 分页
    $(document).on('click', '.zero-right-page .pagelibrary li a', function () {
        // 分页处理
        var is_active = $(this).data('is-active') || 0;
        if(is_active == 1)
        {
            return false;
        }

        // 搜索订单
        var page = $(this).data('page') || 1;
        CategoryGoodsSearchAjax(page);
    });

    // 分页输入事件
    $(document).on('change', '.am-pagination-container input', function () {
        // 基础数据
        var type = $(this).data('type');
        var value = parseInt($(this).val() || $(this).data('default-value') || 0);
        if (isNaN(value)) {
            value = 1;
        }
        // 分页则处理最大分页数
        if (type == 'page') {
            var value_max = parseInt($(this).data('value-max'));
            if (value > value_max) {
                value = value_max;
            }
            CategoryGoodsSearchAjax(value);
        } else {
            CategoryGoodsSearchAjax(1, value);
        }
    });
});