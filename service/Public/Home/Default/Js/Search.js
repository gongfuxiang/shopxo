$(function()
{
    $("#select1 dd").click(function() {
        $(this).addClass("selected").siblings().removeClass("selected");
        if ($(this).hasClass("select-all")) {
            $("#selectA").remove();
        } else {
            var copyThisA = $(this).clone();
            if ($("#selectA").length > 0) {
                $("#selectA a").html($(this).text());
            } else {
                $(".select-result dl").append(copyThisA.attr("id", "selectA"));

            }
        }
    });

    $("#select2 dd").click(function() {
        $(this).addClass("selected").siblings().removeClass("selected");
        if ($(this).hasClass("select-all")) {
            $("#selectB").remove();
        } else {
            var copyThisB = $(this).clone();
            if ($("#selectB").length > 0) {
                $("#selectB a").html($(this).text());
            } else {
                $(".select-result dl").append(copyThisB.attr("id", "selectB"));
            }
        }
    });

    $("#select3 dd").click(function() {
        $(this).addClass("selected").siblings().removeClass("selected");
        if ($(this).hasClass("select-all")) {
            $("#selectC").remove();
        } else {
            var copyThisC = $(this).clone();
            if ($("#selectC").length > 0) {
                $("#selectC a").html($(this).text());
            } else {
                $(".select-result dl").append(copyThisC.attr("id", "selectC"));
            }
        }
    });

    $(document).on("click", "#selectA", function() {
        $(this).remove();
        $("#select1 .select-all").addClass("selected").siblings().removeClass("selected");
    });

    $(document).on("click", "#selectB", function() {
        $(this).remove();
        $("#select2 .select-all").addClass("selected").siblings().removeClass("selected");
    });

    $(document).on("click", "#selectC", function() {
        $(this).remove();
        $("#select3 .select-all").addClass("selected").siblings().removeClass("selected");
    });

    $(document).on("click", ".select dd", function() {
        if ($(".select-result dd").length > 1) {
            $(".select-no").hide();
            $(".eliminateCriteria").show();
            $(".select-result").show();
        } else {
            $(".select-no").show();
            $(".select-result").hide();

        }
    });

    $(".eliminateCriteria").on("click", function() {
        $("#selectA").remove();
        $("#selectB").remove();
        $("#selectC").remove();
        $(".select-all").addClass("selected").siblings().removeClass("selected");
        $(".eliminateCriteria").hide();
        $(".select-no").show();
        $(".select-result").hide();

    });


    var hh = document.documentElement.clientHeight;
    var ls = document.documentElement.clientWidth;
    if (ls < 640) {



        $(".select dt").on('click', function() {
            if ($(this).next("div").css("display") == 'none') {
                $(".theme-popover-mask").height(hh);
                $(".theme-popover").css("position", "fixed");
                $(this).next("div").slideToggle("slow");
                $(".select div").not($(this).next()).hide();
            }
          else{
            $(".theme-popover-mask").height(0);
            $(".theme-popover").css("position", "static");
            $(this).next("div").slideUp("slow");;
          }

        })


        $(document).on("click", ".eliminateCriteria", function() {
            $(".dd-conent").hide();
        })

        $(document).on("click", ".select dd", function() {
            $(".theme-popover-mask").height(0);
            $(".theme-popover").css("position", "static");
            $(".dd-conent").hide();
        });
        $(document).on("click", ".theme-popover-mask", function() {
            $(".theme-popover-mask").height(0);
            $(".theme-popover").css("position", "static");
            $(".dd-conent").hide();
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