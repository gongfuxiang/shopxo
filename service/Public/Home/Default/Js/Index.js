$(function()
{
    // 新闻轮播
    if ($(window).width() < 640) {
        function autoScroll(obj) {
            $(obj).find("ul").animate({
                marginTop: "-39px"
            }, 500, function() {
                $(this).css({
                    marginTop: "0px"
                }).find("li:first").appendTo(this);
            })
        }
        setInterval(function()
        {
            autoScroll(".banner-news");
        }, 3000);
    }

});