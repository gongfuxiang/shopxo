// 楼层聚合数据高度处理
function FloorResizeHandle()
{
    $('.floor').each(function(k, v)
    {
        var height = $(this).find('.goods-list').height();
        $(this).find('.aggregation').css('height', ((window.innerWidth || $(window).width()) <= 640) ? 'auto' : height+'px');
    });
}

$(function()
{
    // 新闻轮播
    if((window.innerWidth || $(window).width()) <= 640)
    {
        function AutoScroll()
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
            AutoScroll();
        }, 3000);
    }

    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        FloorResizeHandle();
    });

});

$(window).load(function()
{
    FloorResizeHandle();
});