$(function()
{
    // 筛选操作
    $(document).on('click', '.select-list dl dd', function()
    {
        $(this).addClass("selected").siblings().removeClass("selected");
        var selected_tag_name = $(this).parent('.dd-conent').attr('data-selected-tag');
        if ($(this).hasClass("select-all")) {
            $('#'+selected_tag_name).remove();
        } else {
            if ($('#'+selected_tag_name).length > 0) {
                $('#'+selected_tag_name).find('a').html($(this).find('a').text());
                $('#'+selected_tag_name).find('a').attr('data-value', $(this).find('a').attr('data-value'));
            } else {
                var copy_html = $(this).clone();
                $(".select-result dl").append(copy_html.attr("id", selected_tag_name));
            }
        }
        get_goods_list(1);
    });

    $(document).on('click', '.select-result dl dd', function() {
        $(this).remove();
        $('#'+$(this).attr('id')+'-dl').find('.select-all').addClass('selected').siblings().removeClass('selected');
        get_goods_list(1);
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
        get_goods_list(1);
    });

    // 排序导航
    $('.sort-nav li').on('click', function()
    {
        var type = $(this).attr('data-type');
        $('.sort-nav li').attr('data-type', 'desc');
        $('.sort-nav li a i').removeClass('am-icon-long-arrow-up').addClass('am-icon-long-arrow-down');

        if($(this).hasClass('active'))
        {
            if(type == 'asc')
            {
                $(this).attr('data-type', 'desc');
                $(this).find('a i').removeClass('am-icon-long-arrow-down').addClass('am-icon-long-arrow-up');
            } else {
                $(this).attr('data-type', 'asc');
                $(this).find('a i').removeClass('am-icon-long-arrow-up').addClass('am-icon-long-arrow-down');
            }
        } else {
            $('.sort-nav li').removeClass('active');
            $(this).addClass('active');
            $(this).attr('data-type', 'asc');
        }
        get_goods_list(1);
    });

    // 条件分类组筛选
    $(".select dt").on('click', function()
    {
        if($(window).width() < 640)
        {
            $('body,html').scrollTop(0);
            $(this).next('div.dd-conent').css('top', ($('.theme-popover').height())+'px');
            if ($(this).next('div.dd-conent').css("display") == 'none') {
                $(".theme-popover-mask").show();
                $(".theme-popover").css({"position":"fixed", "top":0});
                $(this).next('div.dd-conent').slideToggle(300);
                $('.select div.dd-conent').not($(this).next()).hide();
            } else {
                $(this).next('div.dd-conent').slideUp(300);
                $(".theme-popover-mask").hide();
                $(".theme-popover").css({"position":"static", "top":0});
           }
        }
    });

    // 取消条件/移除条件
    $(document).on("click", ".select dd, .screening-remove-submit", function()
    {
        if($(document).width() < 640)
        {
            $(".dd-conent").slideUp(300);
            $(".theme-popover-mask").hide();
            $(".theme-popover").css({"position":"static", "top":0});
        }
    });
    $(document).on("click", ".theme-popover-mask", function()
    {
        $(".dd-conent").slideUp(300);
        $(".theme-popover-mask").hide();
        $(".theme-popover").css({"position":"static", "top":0});
    });
    

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
        // 导航
        search_nav();

        // 条件筛选
        if($(document).width() >= 640)
        {
            $('.dd-conent').show();
        }
    });
    search_nav();

    // 获取商品列表
    function get_goods_list(page)
    {
        // 请求参数处理
        var data = {
            category_id: $('.search-content').data('category-id') || 0,
            keywords: $('#search-input').val() || '',
            page: page || parseInt($('.search-pages-submit').attr('data-page')) || 1,
            order_by_field: $('.sort-nav li.active').attr('data-field') || 'default',
            order_by_type: $('.sort-nav li.active').attr('data-type') == 'asc' ? 'desc' : 'asc',
        }
        $('.select-result .selected a').each(function(k, v)
        {
            data[$(this).attr('data-field')] = $(this).attr('data-value');
        });

        // 清空数据
        if(data.page == 1)
        {
            $('.data-list').html('');
        }

        // 页面提示处理
        $('.loding-view').show();
        $('.search-pages-submit').hide();
        $('.table-no').hide();

        // 请求数据
        $.ajax({
            url:$('.search-pages-submit').data('url'),
            type:'POST',
            dataType:"json",
            timeout:10000,
            data:data,
            success:function(result)
            {
                $('.loding-view').hide();
                if(result.code == 0)
                {
                    for(var i in result.data.data)
                    {
                        var html = '<li class="am-animation-scale-up"><div class="i-pic limit">';
                            html += '<a href="'+result.data.data[i]['goods_url']+'" target="_blank">';
                            html += '<img src="'+result.data.data[i]['images']+'" />';
                            html += '</a>';
                            html += '<a href="'+result.data.data[i]['goods_url']+'" target="_blank">';
                            html += '<p class="title fl">'+result.data.data[i]['title']+'</p>';
                            html += '</a>';
                            html += '<p class="price fl"><b>¥</b><strong>'+result.data.data[i]['min_price']+'</strong></p>';
                            html += '<p class="number fl">销量<span>'+result.data.data[i]['sales_count']+'</span></p>';
                            html += '</div></li>';

                        $('.data-list').append(html);
                    }
                    $('.search-pages-submit').attr('data-page', data.page+1);
                    $('.search-pages-submit').attr('disabled', (result.data.page_total <= 1));
                    $('.search-pages-submit').show();
                    $('.table-no').hide();
                } else if(result.code == -100) {
                    if($('.data-list li').length == 0)
                    {
                        $('.table-no').show();
                        $('.search-pages-submit').hide();
                    } else {
                        $('.table-no').hide();
                        $('.search-pages-submit').show();
                        $('.search-pages-submit').attr('disabled', true);
                    }
                    Prompt(result.msg);
                } else {
                    Prompt(result.msg);
                }
                $('.select .title-tips strong').text(result.data.total);
            },
            error:function(xhr, type)
            {
                $('.loding-view').hide();
                $('.search-pages-submit').hide();
                $('.table-no').show();
                Prompt('网络异常出错');
            }
        });
    }
    get_goods_list(1);

    // 加载更多数据
    $('.search-pages-submit').on('click', function()
    {
        get_goods_list();
    });

});