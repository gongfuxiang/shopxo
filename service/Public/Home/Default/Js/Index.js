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

    // 分类事件显示/隐藏
    $(".category-content li").hover(function() {
        $(".category-content .category-list li.first .menu-in").css("display", "none");
        $(".category-content .category-list li.first").removeClass("hover");
        $(this).addClass("hover");
        $(this).children("div.menu-in").css("display", "block")
    }, function() {
        $(this).removeClass("hover")
        $(this).children("div.menu-in").css("display", "none")
    });

});