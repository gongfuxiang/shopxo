//商品规格选择
$(function() {
    $(".theme-options").each(function() {
        var i = $(this);
        var p = i.find("ul>li");
        p.click(function() {
            if (!!$(this).hasClass("selected")) {
                $(this).removeClass("selected");

            } else {
                $(this).addClass("selected").siblings("li").removeClass("selected");

            }

        })
    });

    // 放大镜初始化
    $(".jqzoom").imagezoom();
    $("#thumblist li a").click(function() {
        $(this).parents("li").addClass("tb-selected").siblings().removeClass("tb-selected");
        $(".jqzoom").attr('src', $(this).find("img").attr("mid"));
        $(".jqzoom").attr('rel', $(this).find("img").attr("big"));
    });

    //弹出规格选择
    var $ww = $(window).width();
    if ($ww <1025) {
        $('.theme-login').click(function() {
            $(document.body).css("position", "fixed");
            $('.theme-popover-mask').show();
            $('.theme-popover').slideDown(200);
        })

        $('.theme-poptit .close,.btn-op .close').click(function() {
            $(document.body).css("position", "static");
            // 滚动条复位
            $('.theme-signin-left').scrollTop(0);

            $('.theme-popover-mask').hide();
            $('.theme-popover').slideUp(200);
        })
    }
});

// 购买导航动画显示/隐藏
var temp_scroll = 0;
var scroll_type = -1;
var location_scroll = 0;
var nav_status = 1;
var $buy_nav= $("div.buy-nav");
$(window).scroll(function()
{
    if($(window).width() <= 625)
    {
        var scroll = $(document).scrollTop();
        if(scroll != temp_scroll)
        {
            var temp_scroll_type = (scroll > temp_scroll) ? 1 : 0;
            if(temp_scroll_type != scroll_type)
            {
                scroll_type = temp_scroll_type;
                location_scroll = scroll;
            }

            if(scroll_type == 1)
            {
                if(nav_status == 1 && scroll > location_scroll+200)
                {
                    nav_status = 0;
                    if(!$buy_nav.is(":animated"))
                    {
                        $buy_nav.slideUp(500);
                    }
                }
            } else {
                if(nav_status == 0 && scroll < location_scroll-50)
                {
                    nav_status = 1;
                    if(!$buy_nav.is(":animated"))
                    {
                        $buy_nav.slideDown(500);
                    }
                }
            }
            temp_scroll = scroll;
        }
    }
});



$(document).ready(function() {
    //获得文本框对象
    var t = $("#text_box");
    //初始化数量为1,并失效减
    $('#min').attr('disabled', true);
    //数量增加操作
    $("#add").click(function() {
            t.val(parseInt(t.val()) + 1)
            if (parseInt(t.val()) != 1) {
                $('#min').attr('disabled', false);
            }

        });
    //数量减少操作
    $("#min").click(function() {
        t.val(parseInt(t.val()) - 1);
        if (parseInt(t.val()) == 1) {
            $('#min').attr('disabled', true);
        }

    })

});