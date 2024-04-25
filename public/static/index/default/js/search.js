$(function()
{
    // 条件展开关闭处理
    $('.map-images-text-items,.map-text-items').each(function(k, v)
    {
        var height = $(this).find('ul').innerHeight();
        var max_height = $(this).hasClass('map-images-text-items') ? 55 : 45;
        if(height > max_height || $(window).width() < 641)
        {
            $(this).find('.map-more-submit').removeClass('am-hide');
        }
    });

    // 条件展开/隐藏
    $(document).on('click', '.map-item .map-more-submit', function()
    {
        var $parents = $(this).parents('.map-right');
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
        window.location.href = UrlFieldReplace('layout', value);
    });

    // 价格滑条初始化
    var $range_input = $('.sort-nav-map-price-range-slider-input input');
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
    // 价格滑条清空
    $(document).on('click', '.sort-nav-map-price-clear', function()
    {
        $('.sort-nav-map-price-input-min,.sort-nav-map-price-input-max').val('');
        window.location.href = UrlFieldReplace('price', null);
    });
    // 价格滑条确认
    $(document).on('click', '.sort-nav-map-price-submit', function()
    {
        var min = FomatFloat($('.sort-nav-map-price-input-min').val() || 0);
        var max = FomatFloat($('.sort-nav-map-price-input-max').val() || 0);
        if(min <= 0 && max <= 0)
        {
            var url = UrlFieldReplace('price', null);
        } else {
            var url = UrlFieldReplace('price', min+'-'+max);
        }
        window.location.href = url;
    });
});