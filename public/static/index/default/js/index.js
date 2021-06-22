// 楼层聚合数据高度处理
function FloorResizeHandle()
{
    $('.floor').each(function(k, v)
    {
        var height = $(this).find('.goods-list').height();
        $(this).find('.aggregation').css('height', ((window.innerWidth || $(window).width()) <= 640) ? 'auto' : height+'px');
    });
}

$(window).load(function()
{
    FloorResizeHandle();
});

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

    // 布局保存
    $('.layout-operate-container button').on('click', function()
    {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: $('.layout-operate-container').data('save-url'),
            type: 'post',
            data: {"config": JSON.stringify(LayoutViewConfig())},
            dataType: 'json',
            success:function(res)
            {
                if(res['code'] == 0)
                {
                    Prompt(res.msg, 'success');
                    setTimeout(function()
                    {
                        window.location.href = __my_url__;
                    }, 1500);
                } else {
                    $this.button('reset');
                    Prompt(res.msg);
                }                
            },
            error:function(res)
            {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || '异常错误';
                Prompt(msg, null, 30);
            }
        });
    });

});