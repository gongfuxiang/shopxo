$(function()
{
    // 筛选操作
    $(document).on('click', '.select-list dl dd', function()
    {
        $(this).addClass("selected").siblings().removeClass("selected");
        var selected_tag_name = $(this).parent('.dd-conent').attr('data-selected-tag');
        console.log(selected_tag_name)
        if ($(this).hasClass("select-all")) {
            $('#'+selected_tag_name).remove();
        } else {
            if ($('#'+selected_tag_name).length > 0) {
                $('#'+selected_tag_name).find("a").html($(this).text());
            } else {
                var copy_html = $(this).clone();
                $(".select-result dl").append(copy_html.attr("id", selected_tag_name));
            }
        }
    });

    $(document).on('click', '.select-result dl dd', function() {
        $(this).remove();
        $('#'+$(this).attr('id')+'-dl').find('.select-all').addClass('selected').siblings().removeClass('selected');
    });

    $(document).on('click', 'ul.select dd', function() {
        if ($('.select-result dd').length > 1) {
            $('.select-no').hide();
            $('.screening-remove-submit').show();
            $('.select-result').show();
        } else {
            $('.select-no').show();
            $('.select-result').hide();
        }
    });

    $(".screening-remove-submit").on("click", function() {
        $('.select-result dd.selected').remove();
        $('.select-list .select-all').addClass('selected').siblings().removeClass('selected');
        $(this).hide();
        $('.select-result .select-no').show();
        $('.select-result').hide();

    });


    var hh = document.documentElement.clientHeight;
    var ls = document.documentElement.clientWidth;
    if (ls < 640) {



        $(".select dt").on('click', function() {
            if ($(this).next("div").css("display") == 'none') {
                $(".theme-popover-mask").height(hh);
                $(".theme-popover").css({"position":"fixed", "top":0, "padding-top":"46px"});
                $(this).next("div").slideToggle(300);
                $(".select div").not($(this).next()).hide();
            } else {
                $(this).next("div").slideUp(300);
                $(".theme-popover-mask").height(0);
                $(".theme-popover").css({"position":"static", "top":0, "padding-top":"0"});
           }
        })


        $(document).on("click", ".screening-remove-submit", function() {
            $(".dd-conent").slideUp(300);
        })

        $(document).on("click", ".select dd", function() {
            $(".dd-conent").slideUp(300);
            $(".theme-popover-mask").height(0);
            $(".theme-popover").css({"position":"static", "top":0, "padding-top":"0"});
        });
        $(document).on("click", ".theme-popover-mask", function() {
            $(".dd-conent").slideUp(300);
            $(".theme-popover-mask").height(0);
            $(".theme-popover").css({"position":"static", "top":0, "padding-top":"0"});
        });

    }

    // 导航显示/隐藏处理
    function search_nav()
    {
        // 滚动处理导航
        $(window).scroll(function()
        {
            if($(window).width() <= 625)
            {
                var scroll = $(document).scrollTop();

                if($('.nav-search').length > 0)
                {
                    if(scroll > 30)
                    {
                        $('.nav-search').css('display','none');
                    } else {
                        $('.nav-search').css('display','block');
                    }
                }
                
            }
        });
    }

    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        search_nav();
    });
    search_nav();

});