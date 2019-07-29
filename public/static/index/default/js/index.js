$(function()
{
    // 新闻轮播
    if((window.innerWidth || $(window).width()) <= 640)
    {
        function auto_scroll()
        {
            $('.banner-news').find("ul").animate({
                marginTop: "-39px"
            }, 500, function() {
                $(this).css({
                    marginTop: "0px"
                }).find("li:first").appendTo(this);
            });
        }
        setInterval(function()
        {
            auto_scroll();
        }, 3000);
    }

    // 楼层聚合数据高度处理
    function floor_resize_handle()
    {
        $('.floor').each(function(k, v)
        {
            var height = $(this).find('.goods-list').height();
            $(this).find('.aggregation').css('height', ((window.innerWidth || $(window).width()) <= 640) ? 'auto' : height+'px');
        });
    }
    floor_resize_handle();

    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        floor_resize_handle();
    });

});