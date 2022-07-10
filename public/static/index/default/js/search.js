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
    $('.map-item .map-more-submit').on('click', function()
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
});