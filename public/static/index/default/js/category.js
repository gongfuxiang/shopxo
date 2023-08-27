$(function () {
    // 一级分类显/隐操作
    $('.category-list-container ul.category-nav-hover li').on('mouseover', function () {
        var index = $(this).index();
        $('.category-list-container ul.category-nav-hover li').removeClass('active');
        $(this).addClass('active');
        $('.category-list-container .category-content').addClass('none');
        $('.category-list-container .category-content-' + index).removeClass('none');
    });

    // 一级分类双击进入商品搜索页
    $('.category-list-container ul.category-nav-hover li').on('dblclick', function () {
        var url = $(this).data('url') || null;
        if (url != null) {
            window.location.href = url;
        }
    });
    CategoryGoodsSearchAjax($('.zero-title ul li.active'), 1);
    // 一级菜单点击
    $(document).on('click', '.zero-title ul li', function () {
        var data = $(this).data('json');
        $('.zero-right-title a').remove();
        $('.zero-right-title').hide();
        $(this).addClass('active').siblings().removeClass('active')
        if (data) {
            var json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8));
            console.log(json)
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
        CategoryGoodsSearchAjax($(this), 1, 1);
        $('.zero-left ul li').eq(0).addClass('active').siblings().removeClass('active');
    })
    // 一级菜单鼠标移入移出效果
    $('.category-list-container .model-one li a').hover(function () {
        $(this).find('.category-img').addClass('am-hide');
        $(this).find('.category-img-active').removeClass('am-hide');
    }, function() {
        $(this).find('.category-img').removeClass('am-hide');
        $(this).find('.category-img-active').addClass('am-hide');
    });
    // 二级菜单点击
    $(document).on('click', '.zero-left ul li', function () {
        var data = $(this).data('json');
        $('.zero-right-title a').remove();
        $('.zero-right-title').hide();
        if (data) {
            var json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8));
            console.log(json)
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
        var obj;
        if ($(this).data('id')) {
            obj = $(this);
        } else {
            obj = $('.zero-title ul li.active');
        }
        CategoryGoodsSearchAjax(obj, 2, 1);
        $(this).addClass('active').siblings().removeClass('active');
    })
    // 三级菜单点击
    $(document).on('click', '.zero-right-title a', function () {
        $(this).addClass('active').siblings().removeClass('active');
        var obj;
        if ($(this).data('id')) {
            obj = $(this);
        } else {
            if ($('.zero-left ul li.active').data('id')) {
                obj = $('.zero-left ul li.active');
            } else {
                obj = $('.zero-right-title a.active');
            }
        }
        CategoryGoodsSearchAjax(obj, 3, 1);
    })
    var dom = $('.zero-title ul li.active')
    var domlevel = 1
    // 分页
    $(document).on('click', '.zero-right-page .pagelibrary li a', function () {
        CategoryGoodsSearchAjax(dom, domlevel, $(this).data('page'));
    })
});

// 商品搜索
function CategoryGoodsSearchAjax(obj, level, page) {
    var category_id = '';
    if (level === 3) {
        if (obj.data('id')) {
            category_id = obj.data('id');
        } else {
            if ($('.zero-title ul li.active').data('id')) {
                category_id = $('.zero-title ul li.active').data('id');
            } else {
                category_id = $('.zero-left ul li.active').data('id');
            }
        }
    } else if (level === 2) {
        if (obj.data('id')) {
            category_id = obj.data('id');
        } else {
            category_id = $('.zero-left ul li.active').data('id');
        }
    } else {
        category_id = $(obj).data('id');
    }
    dom = category_id;
    domlevel = level
    // ajax
    $.ajax({
        url: RequestUrlHandle($('.zero-search').data('url')),
        type: 'POST',
        data: {
            "category_id": category_id,
            "page": page || 1
        },
        dataType: 'json',
        success: function (res) {
            if (res.code == 0) {
                $('.zero-right-item').html(res.data.data);
                if (res.data.total > 0) {
                    $('.zero-right-page').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 2));
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