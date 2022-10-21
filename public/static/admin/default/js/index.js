/**
 * 顶部导航页面打开容器处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-21
 * @desc    description
 */
function HeaderMenuPagesListHandle()
{
    if($('.header-menu-open-pages-list').length > 0)
    {
        var width = $('.admin-header .am-topbar-brand').width()+$('.admin-header .am-topbar-right').width()+215;
        $('.header-menu-open-pages-list').css('width', $(window).width()-width);
    }
}

$(function()
{
    // 处理顶部导航页面列表宽度
    HeaderMenuPagesListHandle();

    // 左侧菜单箭头方向回调处理
    $('#admin-offcanvas li.admin-parent').on('open.collapse.amui', function()
    {
        $(this).find('a i').toggleClass('left-menu-more-icon-rotate');
    }).on('close.collapse.amui', function()
    {
        $(this).find('a i').toggleClass('left-menu-more-icon-rotate');
    });

    // url加载
    $(document).on('click', '.common-left-menu li a, .common-nav-top li a, .menu-mini-container-popup ul li a', function()
    {
        var url = $(this).data('url') || null;
        var type = $(this).data('type');
        var key = $(this).data('key');
        if(url != null)
        {
            // 先隐藏所有的iframe
            // 页面未打开则添加iframe并打开
            if($('#ifcontent iframe.iframe-item-key-'+key).length == 0)
            {
                $('#ifcontent').append('<iframe src="'+url+'" width="100%" height="100%" class="iframe-item-key-'+key+'"></iframe>');
            }

            // 添加快捷导航
            if($('.header-menu-open-pages-list ul li.nav-item-key-'+key).length == 0)
            {
                var html = '<li data-url="'+url+'" data-key="'+key+'" class="nav-item-key-'+key+'">';
                    html += '<span>'+$(this).find('.nav-name').text()+'</span>';
                    html += '<a href="javascript:;" class="am-icon-close"></a>';
                    html += '</li>';
                $('.header-menu-open-pages-list ul').append(html);
            }
            // 模拟点击当前元素
            $('.header-menu-open-pages-list ul li.nav-item-key-'+key).trigger('click');

            // 顶部菜单事件，关闭弹层
            if(type == 'nav')
            {
                if($(document).width() < 641)
                {
                    $('.header-nav-submit').trigger('click');
                } else {
                    $(this).parents('.common-nav-top').trigger('click');
                }
            }

            // 关闭左侧弹层
            if(type == 'menu')
            {
                $('#admin-offcanvas').offCanvas('close');
            }
        }
    });

    // 菜单选择
    $('.common-left-menu li a').on('click', function()
    {
        $('.common-left-menu a').removeClass('common-left-menu-active');
        $(this).addClass('common-left-menu-active');
    });

    // mini伸缩开关
    $('.menu-scaling-submit').on('click', function()
    {
        var status = $(this).attr('data-status') || 0;
        $('#admin-offcanvas ul').css('opacity', 0.1);
        $('.menu-mini-container-popup').hide();
        $('.menu-mini-container-tips').hide();
        if(status == 0)
        {
            $(this).animate({left: '18.5px'}, 300);
            $(this).removeClass('am-icon-dedent').addClass('am-icon-indent');
            $('#admin-offcanvas').addClass('menu-mini').addClass('menu-mini-event');
            $('#admin-offcanvas').animate({width: '55px'}, 300);
            $('#ifcontent').animate({paddingLeft: '55px'}, 300);
            $('header.admin-header').animate({left: '55px'}, 300);
        } else {
            $(this).animate({left: '123.5px'}, 300);
            $(this).removeClass('am-icon-indent').addClass('am-icon-dedent');
            $('#admin-offcanvas').animate({width: '160px'}, 300);
            $('#ifcontent').animate({paddingLeft: '160px'}, 300);
            $('header.admin-header').animate({left: '160px'}, 300);
            $('#admin-offcanvas').removeClass('menu-mini-event');
            setTimeout(function() {
                $('#admin-offcanvas').removeClass('menu-mini');
            }, 300);
        }
        $('#admin-offcanvas ul').animate({opacity: 1}, 300);
        $(this).attr('data-status', status == 0 ? 1 : 0);
    });

    // mini菜单操作
    var timer_menu = null;
    $(document).on('mouseenter', '.menu-mini-event li', function()
    {
        clearTimeout(timer_menu);
        var html = $(this).find('ul.admin-sidebar-sub').html() || null;
        var top = $(this).offset().top;
        var win_height = $(window).height();
        var $popup = $('.menu-mini-container-popup');
        var $tips = $('.menu-mini-container-tips');
        if(html == null)
        {
            $popup.hide();
            $tips.show();
            $tips.text($(this).find('.nav-name').text());
            $tips.css('top', top);
        } else {
            $popup.find('ul').html(html);
            $tips.hide();
            $popup.show();
            
            // 容器是否超出底部
            var h = $popup.height();
            if(h+top > win_height)
            {
                var t = win_height-h;
                $popup.css('top', t);
                $popup.find('.mui-mbar-tab-tip').css('top', top-t+12);
            } else {
                $popup.css('top', top);
                $popup.find('.mui-mbar-tab-tip').css('top', '10px');
            }
        }
    });
    $(document).on('mouseleave', '.menu-mini-event li', function()
    {
        $('.menu-mini-container-tips').hide();
        clearTimeout(timer_menu);
        timer_menu = setTimeout(function()
        {
            $('.menu-mini-container-tips').hide();
            if(($('.menu-mini-container-popup').attr('data-is-leave') || 0) == 0)
            {
                $('.menu-mini-container-popup').hide();
            }
        }, 3000);
    });
    $(document).on('mouseenter', '.menu-mini-container-popup', function()
    {
        $(this).attr('data-is-leave', 1);
    });
    $(document).on('mouseleave', '.menu-mini-container-popup', function()
    {
        $(this).attr('data-is-leave', 0);
        $(this).hide();
    });

    // mini菜单选择
    $(document).on('click', '.menu-mini-container-popup ul li a', function()
    {
        $('.admin-sidebar-list li a').removeClass('common-left-menu-active');
        $('.menu-mini-container-popup ul li a').removeClass('common-left-menu-active');

        var parent_id = $(this).data('parent-id') || 0;
        if(parent_id != 0)
        {
            $('.menu-parent-items-'+parent_id).addClass('common-left-menu-active');
        }
        $(this).addClass('common-left-menu-active');
    });

    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        // 小屏幕关闭mini导航
        if($(document).width() <= 640)
        {
            $('.menu-scaling-submit').attr('data-status', 0);
            $('.menu-scaling-submit').removeClass('am-icon-indent').addClass('am-icon-dedent');
            $('#admin-offcanvas').css({'width': 'inherit'});
            $('#admin-offcanvas').removeClass('menu-mini').removeClass('menu-mini-event');
            $('#ifcontent').css({'padding-left':0});
            $('header.admin-header').css({'left': 0});
        } else {
            if(($('.menu-scaling-submit').attr('data-status') || 0) == 0)
            {
                $('#admin-offcanvas').css({'width': '160px'});
                $('#ifcontent').css({'padding-left':'160px'});
                $('header.admin-header').css({'left': '160px'});
            }
        }

        // 处理顶部导航页面列表宽度
        HeaderMenuPagesListHandle();
    });

    // 页面切换
    $(document).on('click', '.header-menu-open-pages-list ul li', function()
    {
        // 选中当前页面
        $('.header-menu-open-pages-list ul li').removeClass('am-active');
        $(this).addClass('am-active');
        var key = $(this).data('key');
        // 显示当前页面
        $('#ifcontent iframe').hide();
        $('#ifcontent iframe.iframe-item-key-'+key).show();
    });
    // 页面移除
    $(document).on('click', '.header-menu-open-pages-list ul li a', function()
    {
        // 移除当前页面
        var $parent = $(this).parent();
        var key = $parent.data('key');
        $parent.remove();
        $('#ifcontent iframe.iframe-item-key-'+key).remove();
        // 当前没有选中的导航则模拟点击最后一个选中
        if($('.header-menu-open-pages-list ul li.am-active').length == 0)
        {
            $('.header-menu-open-pages-list ul li:last').trigger('click');
        }
        // 无页面则添加默认初始化页面
        if($('.header-menu-open-pages-list ul li').length == 0)
        {
            $('#ifcontent iframe').show();
        }
        return false;
    });
});