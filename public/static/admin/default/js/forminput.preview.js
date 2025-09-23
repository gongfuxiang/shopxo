$(function()
{
    // 预览终端切换
    $(document).on('click', '.preview-nav-switch-content .item', function()
    {
        var value = $(this).attr('data-value') || 'web';
        if(value == 'mobile')
        {
            $('.web-iframe').addClass('am-hide');
            $('.mobile-iframe-content').removeClass('am-hide');
        } else {
            $('.web-iframe').removeClass('am-hide');
            $('.mobile-iframe-content').addClass('am-hide');
        }
        $('.preview-nav-switch-content .item').removeClass('am-active');
        $(this).addClass('am-active');
    });
});