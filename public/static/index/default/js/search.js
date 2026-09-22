/**
 * 搜索页 - 商品列表 AJAX（筛选不改 URL）
 */
var search_goods_loading = false;
// 当前筛选条件（内存态，不写地址栏）
var search_goods_filter = {};

/**
 * 从地址栏解析初始参数（兼容 pathinfo：/cid/5 与 ?cid=5）
 */
function SearchGoodsQueryParamsFromLocation()
{
    var params = {};
    var pathinfo = '';

    var search = window.location.search || '';
    if(search.charAt(0) === '?')
    {
        search = search.substring(1);
    }
    if(search !== '')
    {
        search.split('&').forEach(function(pair)
        {
            if(!pair)
            {
                return;
            }
            var parts = pair.split('=');
            var key = decodeURIComponent(parts[0] || '');
            if(!key)
            {
                return;
            }
            var val = decodeURIComponent((parts[1] || '').replace(/\+/g, ' '));
            if(key === 's')
            {
                pathinfo = val;
                return;
            }
            params[key] = val;
        });
    }

    if(!pathinfo)
    {
        pathinfo = window.location.pathname || '';
    }
    pathinfo = String(pathinfo).replace(/^\/+/, '');
    pathinfo = pathinfo.replace(/\.(html|htm|shtml)$/i, '');
    pathinfo = pathinfo.replace(/^index\.php\/?/i, '');

    var segs = pathinfo.split('/').filter(function(p)
    {
        return p !== '';
    });
    if(segs.length && segs[0] === 'search')
    {
        segs.shift();
        if(segs.length && (segs[0] === 'index' || segs[0] === 'datalist' || segs[0] === 'goodslist'))
        {
            segs.shift();
        }
    }
    for(var i = 0; i + 1 < segs.length; i += 2)
    {
        try {
            params[decodeURIComponent(segs[i])] = decodeURIComponent(segs[i + 1]);
        } catch(e) {
            params[segs[i]] = segs[i + 1];
        }
    }
    return params;
}

/**
 * 当前用于请求的筛选参数
 */
function SearchGoodsQueryParams()
{
    return $.extend({}, search_goods_filter);
}

/**
 * 写入筛选字段（空则删除）
 * @param {string} field
 * @param {string|number|null} value
 */
function SearchGoodsFilterSet(field, value)
{
    if(!field)
    {
        return;
    }
    if(value === null || value === undefined || value === '')
    {
        delete search_goods_filter[field];
    } else {
        search_goods_filter[field] = String(value);
    }
}

/**
 * 关闭移动端筛选侧栏
 */
function SearchMapOffcanvasClose()
{
    var $off = $('#search-map');
    if($off.length && $off.hasClass('am-active') && typeof $off.offCanvas === 'function')
    {
        $off.offCanvas('close');
    }
}

/**
 * 是否存在已选筛选条件（用于显示「清除」）
 */
function SearchMapHasActiveFilter()
{
    var fields = ['cid', 'bid', 'peid', 'poid', 'price', 'brand', 'psid', 'scid', 'goods_params_values', 'goods_spec_values'];
    for(var i = 0; i < fields.length; i++)
    {
        var v = search_goods_filter[fields[i]];
        if(v !== undefined && v !== null && String(v).trim() !== '')
        {
            return true;
        }
    }
    return false;
}

/**
 * 同步「清除」按钮显隐
 */
function SearchMapClearButtonSync()
{
    var $btn = $('.search-container .map-remove-submit');
    if(!$btn.length)
    {
        return;
    }
    if(SearchMapHasActiveFilter())
    {
        $btn.removeClass('am-hide');
    } else {
        $btn.addClass('am-hide');
    }
}

/**
 * 清除全部筛选条件（保留关键词 / 入口分类 / 布局）
 */
function SearchMapClearAll()
{
    $('.search-container .map-item li.active').removeClass('active');
    $('.search-container select.search-map-select').each(function()
    {
        var $el = $(this);
        $el.find('option').prop('selected', false);
        $el.find('option[value=""]').prop('selected', true);
        $el.val('');
    });
    if(typeof SearchMapSelectedInit === 'function')
    {
        SearchMapSelectedInit();
    }
    $('.sort-nav-map-price-input-min,.sort-nav-map-price-input-max').val('');

    var keep = {};
    if(search_goods_filter.layout !== undefined)
    {
        keep.layout = search_goods_filter.layout;
    }
    if(search_goods_filter.wd)
    {
        keep.wd = search_goods_filter.wd;
    }
    // 保留入口分类，便于参数/规格继续展示
    if(search_goods_filter.category_id)
    {
        keep.category_id = search_goods_filter.category_id;
    }
    search_goods_filter = keep;
    SearchMapClearButtonSync();
    if(typeof SearchMapParamsSpecRefresh === 'function')
    {
        SearchMapParamsSpecRefresh();
    }
    SearchGoodsNavigate();
}

/**
 * 同步排序导航选中态与箭头（AJAX 后 href 不会刷新，需内存切换）
 * @param {string|null} ov  如 price-asc；空为默认
 */
function SearchSortNavSync(ov)
{
    var field = 'default';
    var dir = '';
    if(ov)
    {
        var parts = String(ov).split('-');
        field = parts[0] || 'default';
        dir = parts[1] || 'desc';
    }
    $('.search-container .sort-nav > li[data-type]').each(function()
    {
        var $li = $(this);
        var type = String($li.data('type') || '');
        var $icon = $li.find('a > i');
        if(type === field)
        {
            $li.addClass('active');
            if($icon.length)
            {
                // 与服务端模板一致：当前 desc → 箭头 down；当前 asc → 箭头 up
                $icon.removeClass('am-icon-long-arrow-up am-icon-long-arrow-down')
                    .addClass(dir === 'asc' ? 'am-icon-long-arrow-up' : 'am-icon-long-arrow-down');
            }
        } else {
            $li.removeClass('active');
            if($icon.length)
            {
                $icon.removeClass('am-icon-long-arrow-up am-icon-long-arrow-down')
                    .addClass('am-icon-long-arrow-down');
            }
        }
    });
}

/**
 * 点击排序项：同字段来回切升/降序，默认排序清空 ov
 * @param {jQuery} $a
 */
function SearchSortNavClick($a)
{
    var type = String($a.data('type') || $a.closest('li').data('type') || '');
    if(!type || type === 'default')
    {
        SearchGoodsFilterSet('ov', null);
        SearchSortNavSync(null);
        SearchGoodsNavigate();
        return;
    }

    var cur = String(search_goods_filter.ov || '');
    var cur_field = '';
    var cur_dir = '';
    if(cur)
    {
        var parts = cur.split('-');
        cur_field = parts[0] || '';
        cur_dir = parts[1] || '';
    }

    // 首次选中该字段默认降序；同字段再次点击则升/降切换
    var next_dir = 'desc';
    if(cur_field === type)
    {
        next_dir = (cur_dir === 'desc') ? 'asc' : 'desc';
    }
    var ov = type + '-' + next_dir;
    SearchGoodsFilterSet('ov', ov);
    SearchSortNavSync(ov);
    SearchGoodsNavigate();
}

/**
 * 显示/隐藏列表加载遮罩
 * @param {boolean} show
 */
function SearchGoodsLoadingMask(show)
{
    var $mask = $('#search-goods-panel .search-goods-loading-mask');
    if($mask.length === 0)
    {
        return;
    }
    if(show)
    {
        $mask.removeClass('am-hide');
    } else {
        $mask.addClass('am-hide');
    }
}

/**
 * 拉取商品列表
 * @param {number} page
 * @param {number|null} page_size
 */
function SearchGoodsListAjax(page, page_size)
{
    var $box = $('.search-container');
    if($box.length === 0)
    {
        return;
    }
    var ajax_url = $box.data('ajax-url') || '';
    if(!ajax_url)
    {
        return;
    }
    if(search_goods_loading)
    {
        return;
    }
    search_goods_loading = true;

    if(page)
    {
        SearchGoodsFilterSet('page', page);
    }
    if(page_size)
    {
        SearchGoodsFilterSet('page_size', page_size);
    }

    var data = SearchGoodsQueryParams();
    data.page = parseInt(data.page || 1, 10) || 1;
    if(!data.page_size && $('#search-goods-page input[name="page_size"]').length)
    {
        var size_val = parseInt($('#search-goods-page input[name="page_size"]').val() || 0, 10);
        if(size_val > 0)
        {
            data.page_size = size_val;
            SearchGoodsFilterSet('page_size', size_val);
        }
    }
    if($box.data('layout') !== undefined && data.layout === undefined)
    {
        data.layout = $box.data('layout');
        SearchGoodsFilterSet('layout', data.layout);
    }

    // 保留当前列表，盖加载遮罩
    SearchGoodsLoadingMask(true);

    $.ajax({
        url: typeof RequestUrlHandle === 'function' ? RequestUrlHandle(ajax_url) : ajax_url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(res)
        {
            search_goods_loading = false;
            SearchGoodsLoadingMask(false);
            if(!res || (typeof res.code !== 'undefined' && parseInt(res.code, 10) !== 0 && parseInt(res.code, 10) !== -1))
            {
                Prompt((res && res.msg) || (window['lang_error_text'] || '异常错误'));
                return;
            }

            var payload = (res && res.data) ? res.data : {};
            var total = parseInt(payload.total || 0, 10) || 0;
            var page_size_val = parseInt(payload.page_size || data.page_size || 20, 10) || 20;
            var page_val = parseInt(payload.page || data.page || 1, 10) || 1;

            $('.map-result-count').text(total);
            if(typeof payload.data === 'undefined' || payload.data === null)
            {
                $('#search-goods-list').html('<div class="table-no">' + ((res && res.msg) || (window['lang_no_data'] || '没有相关数据')) + '</div>');
                $('#search-goods-page').empty();
            } else {
                $('#search-goods-list').html(payload.data || '');
            }

            if(total > 0)
            {
                $('#search-goods-page').html(PageLibrary(total, page_size_val, page_val, 2, true));
            } else {
                $('#search-goods-page').empty();
            }
            SearchMapOffcanvasClose();

            if(typeof ThemeDataEditEventInit === 'function')
            {
                ThemeDataEditEventInit();
            }
        },
        error: function(xhr)
        {
            search_goods_loading = false;
            SearchGoodsLoadingMask(false);
            Prompt((typeof HtmlToString === 'function' ? HtmlToString(xhr.responseText) : '') || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

/**
 * 筛选项容器对应的参数名
 * @param {jQuery} $el
 */
function SearchMapFieldName($el)
{
    var $box = $el.closest('li[data-field], .map-brand-container, .map-category-container, .screening-price-container, .goods-place-origin-container');
    if($box.hasClass('map-brand-container'))
    {
        return 'bid';
    }
    if($box.hasClass('map-category-container'))
    {
        return 'cid';
    }
    if($box.hasClass('screening-price-container'))
    {
        return 'peid';
    }
    if($box.hasClass('goods-place-origin-container'))
    {
        return 'poid';
    }
    return '';
}

/**
 * 多选 toggle 字段值
 * @param {string} field
 * @param {string|number} id
 */
function SearchMapToggleField(field, id)
{
    id = String(id || '');
    if(!field || !id)
    {
        return;
    }
    var list = String(search_goods_filter[field] || '').split(',').map(function(item)
    {
        return String(item || '').trim();
    }).filter(function(item)
    {
        return item !== '';
    });
    var idx = list.indexOf(id);
    if(idx >= 0)
    {
        list.splice(idx, 1);
    } else {
        list.push(id);
    }
    SearchGoodsFilterSet(field, list.length ? list.join(',') : null);
}

/**
 * 从链接 href 合并排序等参数到内存态（不改地址栏）
 * @param {string} href
 * @param {array} keep_fields 仅处理这些字段；空则解析全部
 */
function SearchGoodsFilterApplyHref(href, keep_fields)
{
    if(!href)
    {
        return;
    }
    try {
        var a = document.createElement('a');
        a.href = href;
        var q = {};
        var search = a.search || '';
        if(search.charAt(0) === '?')
        {
            search = search.substring(1);
        }
        if(search)
        {
            search.split('&').forEach(function(pair)
            {
                if(!pair)
                {
                    return;
                }
                var parts = pair.split('=');
                var key = decodeURIComponent(parts[0] || '');
                if(!key || key === 's')
                {
                    return;
                }
                q[key] = decodeURIComponent((parts[1] || '').replace(/\+/g, ' '));
            });
        }
        // pathinfo
        var path = (a.pathname || '').replace(/^\/+/, '').replace(/\.(html|htm|shtml)$/i, '');
        var segs = path.split('/').filter(Boolean);
        if(segs[0] === 'search')
        {
            segs.shift();
            if(segs[0] === 'index' || segs[0] === 'datalist' || segs[0] === 'goodslist')
            {
                segs.shift();
            }
        }
        for(var i = 0; i + 1 < segs.length; i += 2)
        {
            q[decodeURIComponent(segs[i])] = decodeURIComponent(segs[i + 1]);
        }

        var fields = keep_fields && keep_fields.length ? keep_fields : Object.keys(q);
        fields.forEach(function(field)
        {
            if(Object.prototype.hasOwnProperty.call(q, field))
            {
                SearchGoodsFilterSet(field, q[field]);
            } else if(keep_fields && keep_fields.length)
            {
                // 排序切换到 default 时可能去掉 ov
                SearchGoodsFilterSet(field, null);
            }
        });
    } catch(e) {}
}

/**
 * 汇总参数/规格下拉已选值到筛选内存（传 ascii id，避免选项文本含逗号时拆错）
 * @param {string} filter_field goods_params_values | goods_spec_values
 */
function SearchMapSelectSync(filter_field)
{
    if(!filter_field)
    {
        return;
    }
    var ids = [];
    $('.search-container .search-map-select[data-filter="'+filter_field+'"]').each(function()
    {
        var $opt = $(this).find('option:selected');
        var id = String($opt.data('id') || $(this).val() || '').trim();
        if(id !== '')
        {
            ids.push(id);
        }
    });
    if(filter_field === 'goods_params_values')
    {
        SearchGoodsFilterSet('goods_params_values', null);
        SearchGoodsFilterSet('psid', ids.length ? ids.join(',') : null);
    } else if(filter_field === 'goods_spec_values')
    {
        SearchGoodsFilterSet('goods_spec_values', null);
        SearchGoodsFilterSet('scid', ids.length ? ids.join(',') : null);
    }
}

/**
 * Selected 会跳过 value="" 的 option，需把「标题=未选择」补进列表，并固定 placeholder 为标题
 * @param {jQuery} $el
 */
function SearchMapSelectedEnsureTitleItem($el)
{
    var $wrap = $el.next('.am-selected');
    if(!$wrap.length)
    {
        return;
    }
    var name = String($el.data('name') || $el.attr('placeholder') || $el.attr('data-placeholder') || '');
    $el.attr('placeholder', name).attr('data-placeholder', name);
    $wrap.attr('data-placeholder', name);
    var inst = $el.data('amui.selected');
    if(inst && inst.options)
    {
        inst.options.placeholder = name;
    }
    var $list = $wrap.find('.am-selected-list');
    $list.find('> li.search-map-none-item').remove();
    var is_none = !String($el.val() || '').trim();
    $list.prepend(
        '<li class="search-map-none-item'+(is_none ? ' am-checked' : '')+'" data-index="0" data-group="0" data-value="">' +
        '<span class="am-selected-text">'+ $('<div/>').text(name).html() +'</span>' +
        '<i class="am-icon-check"></i></li>'
    );
    if(inst)
    {
        inst.$shadowOptions = $list.find('> li');
        inst.$list = $list;
    }
    if(is_none)
    {
        $wrap.find('.am-selected-status').text(name);
    }
}

/**
 * 已选中时高亮按钮（与上方筛选项 active 风格一致）
 * @param {jQuery} $el
 */
function SearchMapSelectedSyncActive($el)
{
    var $wrap = $el.next('.am-selected');
    if(!$wrap.length)
    {
        return;
    }
    $wrap.toggleClass('is-selected', !!String($el.val() || '').trim());
}

/**
 * 初始化参数/规格 Selected（AJAX 插入后需重绑）
 * @param {jQuery} [$scope]
 */
function SearchMapSelectedInit($scope)
{
    var $list = ($scope && $scope.length) ? $scope.find('select.search-map-select[data-am-selected]') : $('.search-container select.search-map-select[data-am-selected]');
    $list.each(function()
    {
        var $el = $(this);
        var name = String($el.data('name') || $el.attr('placeholder') || $el.attr('data-placeholder') || '');
        if(!String($el.val() || '').trim())
        {
            $el.val('');
        }
        $el.attr('placeholder', name);
        if(typeof $el.selected === 'function')
        {
            if($el.data('amui.selected'))
            {
                try {
                    $el.selected('destroy');
                } catch(e) {}
            }
            $el.selected({placeholder: name});
        }
        SearchMapSelectedEnsureTitleItem($el);
        SearchMapSelectedSyncActive($el);
        $el.off('changed.selected.amui.searchmap').on('changed.selected.amui.searchmap', function()
        {
            SearchMapSelectedEnsureTitleItem($el);
            SearchMapSelectedSyncActive($el);
        });
    });
}

/**
 * 分类变化后刷新参数/规格下拉
 */
function SearchMapParamsSpecRefresh()
{
    // 分类变了先清掉旧参数/规格，避免列表请求仍带旧条件
    SearchGoodsFilterSet('goods_params_values', null);
    SearchGoodsFilterSet('goods_spec_values', null);
    SearchGoodsFilterSet('psid', null);
    SearchGoodsFilterSet('scid', null);
    SearchMapClearButtonSync();

    var $box = $('.search-container');
    var ajax_url = $box.data('map-filter-url') || '';
    var $list = $('#search-map .map-item');
    if(!ajax_url || !$list.length)
    {
        return;
    }
    var data = {
        category_id: search_goods_filter.category_id || $box.data('category-id') || '',
        cid: search_goods_filter.cid || ''
    };
    $.ajax({
        url: typeof RequestUrlHandle === 'function' ? RequestUrlHandle(ajax_url) : ajax_url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(res)
        {
            if(!res || parseInt(res.code, 10) !== 0)
            {
                return;
            }
            var html = (res.data && res.data.html) ? res.data.html : '';
            $list.find('.map-select-item').remove();
            if(html)
            {
                var $hooks = $list.find('.plugins-tag').filter(function()
                {
                    return $(this).text().indexOf('plugins_view_search_map_inside_end') >= 0;
                }).first();
                if($hooks.length)
                {
                    $hooks.before(html);
                } else {
                    $list.append(html);
                }
                SearchMapSelectedInit($list);
            }
        }
    });
}

/**
 * 刷新列表（不改地址栏）
 */
function SearchGoodsNavigate()
{
    SearchGoodsFilterSet('page', 1);
    SearchMapClearButtonSync();
    SearchGoodsListAjax(1);
}

$(function()
{
    // 初始条件：读入一次 URL，之后只改内存
    search_goods_filter = SearchGoodsQueryParamsFromLocation();
    // 布局不跟 URL/历史走，刷新后用页面上的后台默认值
    delete search_goods_filter.layout;
    var $search_box = $('.search-container');
    if($search_box.length && $search_box.data('layout') !== undefined)
    {
        SearchGoodsFilterSet('layout', $search_box.data('layout'));
    }
    // 参数/规格 Selected：收窄宽度，默认标题未选状态
    SearchMapSelectedInit();
    // 按当前筛选同步「清除」按钮
    SearchMapClearButtonSync();

    // 条件展开关闭处理（「更多」与标题同级，挂在 li 下）
    $('.map-images-text-items,.map-text-items').each(function(k, v)
    {
        var $right = $(this);
        var height = $right.find('ul').innerHeight();
        var max_height = $right.hasClass('map-images-text-items') ? 55 : 45;
        if(height > max_height || $(window).width() < 641)
        {
            $right.closest('li').children('.map-more-submit').removeClass('am-hide');
        }
    });

    // 条件展开/隐藏
    $(document).on('click', '.map-item .map-more-submit', function()
    {
        var $parents = $(this).closest('li').children('.map-right');
        var height = $parents.hasClass('map-images-text-items') ? '55px' : '45px';
        if($parents.css('height') == height)
        {
            $parents.css('height', 'auto');
        } else {
            $parents.css('height', height);
        }
    });

    // 列表布局选择
    $(document).on('click', '.layout-styles', function()
    {
        var value = ($(this).data('value') || 0) == 1 ? 0 : 1;
        $('.search-container').data('layout', value);
        $(this).data('value', value);
        $(this).find('i').attr('class', 'iconfont ' + (value == 1 ? 'icon-table-list' : 'icon-table-grid'));
        SearchGoodsFilterSet('layout', value);
        SearchGoodsNavigate();
    });

    // 价格滑条初始化
    var $range_input = $('.sort-nav-map-price-range-slider-input input');
    if($range_input.length && typeof $range_input.jRange === 'function')
    {
        $range_input.jRange({
            from: 0,
            to: $range_input.attr('data-to'),
            step: 1,
            showScale: false,
            format: '%s',
            width: 178,
            showLabels: true,
            isRange : false,
            theme: 'theme-main',
            onstatechange: function(res) {
                var arr = res.split(',');
                $('.sort-nav-map-price-input-min').val(arr[0]);
                $('.sort-nav-map-price-input-max').val(arr[1]);
            }
        });
    }
    // 价格滑条清空
    $(document).on('click', '.sort-nav-map-price-clear', function()
    {
        $('.sort-nav-map-price-input-min,.sort-nav-map-price-input-max').val('');
        SearchGoodsFilterSet('price', null);
        SearchGoodsNavigate();
    });
    // 价格滑条确认
    $(document).on('click', '.sort-nav-map-price-submit', function()
    {
        var min = FomatFloat($('.sort-nav-map-price-input-min').val() || 0);
        var max = FomatFloat($('.sort-nav-map-price-input-max').val() || 0);
        if(min <= 0 && max <= 0)
        {
            SearchGoodsFilterSet('price', null);
        } else {
            SearchGoodsFilterSet('price', min+'-'+max);
        }
        SearchGoodsNavigate();
    });

    // 参数 / 规格 Selected
    $(document).on('change', '.search-container select.search-map-select', function()
    {
        var $el = $(this);
        var filter_field = $el.data('filter') || '';
        SearchMapSelectedEnsureTitleItem($el);
        SearchMapSelectedSyncActive($el);
        SearchMapSelectSync(filter_field);
        SearchGoodsNavigate();
    });

    // 点击标题项（空 value）：取消该筛选
    $(document).on('click', '.search-container .search-map-none-item', function(e)
    {
        e.preventDefault();
        e.stopPropagation();
        var $item = $(this);
        var $wrap = $item.closest('.am-selected');
        var $el = $wrap.prev('select.search-map-select');
        if(!$el.length)
        {
            return;
        }
        var name = String($el.data('name') || $el.attr('data-placeholder') || '');
        $el.find('option').prop('selected', false);
        $el.find('option[value=""]').prop('selected', true);
        $el.val('');
        $wrap.find('.am-selected-list > li').removeClass('am-checked');
        $item.addClass('am-checked');
        $wrap.find('.am-selected-status').text(name);
        if(typeof $wrap.dropdown === 'function')
        {
            try { $wrap.dropdown('close'); } catch(err) {}
        }
        SearchMapSelectedSyncActive($el);
        SearchMapSelectSync($el.data('filter') || '');
        SearchGoodsNavigate();
    });

    // 筛选条件 / 排序：AJAX，不改 URL
    $(document).on('click', '.search-container .map-item a[href], .search-container .sort-nav a[href]', function(e)
    {
        // Selected 组件内部链接不走筛选跳转
        if($(this).closest('.am-selected').length)
        {
            return;
        }
        // 清除按钮单独处理
        if($(this).hasClass('map-remove-submit'))
        {
            return;
        }
        var href = $(this).attr('href') || '';
        if(!href || href.indexOf('javascript:') === 0 || $(this).attr('target') === '_blank')
        {
            return;
        }

        e.preventDefault();

        // 排序：按内存态来回切换升/降序（不依赖页内静态 href）
        if($(this).closest('.sort-nav').length)
        {
            SearchSortNavClick($(this));
            return;
        }

        // 分类 / 品牌 / 价格 / 产地：多选 toggle
        var field = SearchMapFieldName($(this));
        var id = $(this).data('id') || $(this).closest('li').data('id') || '';
        $(this).closest('li').toggleClass('active');
        if(field && id)
        {
            SearchMapToggleField(field, id);
            if(field === 'cid')
            {
                SearchMapParamsSpecRefresh();
            }
            SearchGoodsNavigate();
        }
    });

    // 清除全部筛选
    $(document).on('click', '.search-container .map-remove-submit', function(e)
    {
        e.preventDefault();
        SearchMapClearAll();
    });

    // 分页（不改 URL）
    $(document).on('click', '#search-goods-page .pagelibrary li a', function()
    {
        if($(this).data('is-active') == 1)
        {
            return false;
        }
        var page = $(this).data('page') || 1;
        SearchGoodsListAjax(page);
    });

    // 分页输入
    $(document).on('change', '#search-goods-page .am-pagination-container input', function()
    {
        var type = $(this).data('type');
        var value = parseInt($(this).val() || $(this).data('default-value') || 0, 10);
        if(isNaN(value))
        {
            value = 1;
        }
        if(type == 'page')
        {
            var value_max = parseInt($(this).data('value-max'), 10);
            if(value > value_max)
            {
                value = value_max;
            }
            SearchGoodsListAjax(value);
        } else {
            SearchGoodsListAjax(1, value);
        }
    });

    // 初始加载
    if($('.search-container').data('ajax-url'))
    {
        var init_page = parseInt((search_goods_filter.page || 1), 10) || 1;
        SearchGoodsListAjax(init_page);
    }
});
