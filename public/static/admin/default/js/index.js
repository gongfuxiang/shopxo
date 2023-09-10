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

/**
 * 独立弹窗层级处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-02-15
 * @desc    description
 * @param   {[string]}        key [窗口可以]
 */
function LayerPagesLevelHandle(key)
{
    var index = 0;
    var $content = $('#ifcontent');
    $content.find('.window-layer-alone-layer').each(function()
    {
        var temp_index = parseInt($(this).css('z-index') || 0);
        if(temp_index > index)
        {
            index = temp_index
        }
    });
    $content.find('.window-layer .window-layer-seat').show();
    var $layer = $content.find('.window-layer.iframe-item-key-'+key);
    $layer.css({'z-index': index+1, 'position': 'fixed'});
    $layer.find('.window-layer-seat').hide();
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
    $(document).on('click', '.common-left-menu li a, .admin-header-list li a.new-window, .menu-mini-container-popup ul li a', function()
    {
        var url = $(this).data('url') || null;
        var type = $(this).data('type');
        var key = $(this).data('key');
        var name = $(this).data('node-name') || $(this).find('.nav-name').text();
        AdminTopNavIframeAddHandle(url, name, key, type);
    });

    // 菜单选择
    $(document).on('click', '.common-left-menu li a', function()
    {
        $('.common-left-menu a').removeClass('common-left-menu-active');
        $(this).addClass('common-left-menu-active');
    });

    // mini伸缩开关
    $(document).on('click', '.menu-scaling-submit', function()
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
    var window_layer_alone_layer_warning_timer = null;
    $(document).on('click', '.header-menu-open-pages-list ul li', function()
    {
        // 选中当前页面
        $('.header-menu-open-pages-list ul li').removeClass('am-active');
        $(this).addClass('am-active');
        var key = $(this).data('key');
        var $content = $('#ifcontent');
        // 显示当前页面
        $content.find('.window-layer').not('.window-layer-alone-layer').hide();
        var $current_iframe = $('#ifcontent .iframe-item-key-'+key);
        $current_iframe.show();
        // 窗口存在独立
        if($current_iframe.hasClass('window-layer-alone-layer'))
        {
            // 窗口存在独立则警告窗口提示
            var count = 0;
            clearInterval(window_layer_alone_layer_warning_timer);
            window_layer_alone_layer_warning_timer = setInterval(function()
            {
                if(count > 10)
                {
                    clearInterval(window_layer_alone_layer_warning_timer);
                } else {
                    $current_iframe.css('box-shadow', 'rgb(0 0 0 / 30%) 1px 1px '+(count%2 == 0 ? '12px' : '24px'));
                    count++;
                }
            }, 50);
            // 设置层级
            LayerPagesLevelHandle(key);
        } else {
            // 非独立窗口则隐藏所有页面占位
            $content.find('.window-layer .window-layer-seat').hide();
        }
    });

    // 页面移除
    $(document).on('click', '.header-menu-open-pages-list ul li a', function()
    {
        // 移除当前页面
        var $parent = $(this).parent();
        var key = $parent.data('key');
        $parent.remove();
        $('#ifcontent .iframe-item-key-'+key).remove();
        // 当前没有选中的导航则模拟点击最后一个选中
        if($('.header-menu-open-pages-list ul li.am-active').length == 0)
        {
            $('.header-menu-open-pages-list ul li:last').trigger('click');
        }
        // 无页面则添加默认初始化页面
        if($('.header-menu-open-pages-list ul li').length == 0)
        {
            $('#ifcontent .window-layer').show();
        }
        return false;
    });

    // 拖动为独立窗口
    $(document).on('mousedown', '.header-menu-open-pages-list ul li', function(event)
    {
        var is_move = true;
        var key = $(this).data('key') || null;
        var $iframe = $('.iframe-item-key-'+key);
        if(key != null && $iframe.length > 0)
        {
            var top = $(this).innerHeight()+$(this).offset().top;
            $(document).mousemove(function(event)
            {
                if(is_move && event.pageY > top)
                {
                    // 增加独立窗口类
                    $iframe.addClass('window-layer-alone-layer').show();
                    // 设置层级
                    LayerPagesLevelHandle(key);
                }
            }).mouseup(function(event)
            {
                is_move = false;
            });
        }
    });
    // 双击为独立窗口
    $(document).on('dblclick', '.header-menu-open-pages-list ul li', function()
    {
        var key = $(this).data('key') || null;
        // 增加独立窗口类
        $('.iframe-item-key-'+key).addClass('window-layer-alone-layer').show();
        // 设置层级
        LayerPagesLevelHandle(key);
    });

    // 窗口切换
    $(document).on('click', '#ifcontent .window-layer', function()
    {
        if($(this).hasClass('window-layer-alone-layer'))
        {
            LayerPagesLevelHandle($(this).data('key'));
        } else {
            // 非独立窗口则隐藏所有页面占位
            $('#ifcontent .window-layer .window-layer-seat').hide();
        }
    });

    // 独立窗口刷新
    $(document).on('click', '.window-layer-alone-layer .window-layer-tab-bar .refresh', function()
    {
        var $parent = $(this).parents('.window-layer');
        if($parent.find('iframe').attr('src', $parent.find('iframe').attr('src')));
    });

    // 收回独立窗口
    $(document).on('click', '.window-layer-alone-layer .window-layer-tab-bar .recovery', function()
    {
        // 移除class和样式
        $(this).parents('.window-layer').removeClass('window-layer-alone-layer').css({'position':'', 'left':'', 'top':'', 'box-shadow':'', 'width':'', 'height':'', 'z-index':''});
        // 显示已选中页面
        var key = $('.header-menu-open-pages-list ul li.am-active').data('key') || null;
        if(key != null)
        {
            $('#ifcontent .window-layer').not('.window-layer-alone-layer').hide();
            $('#ifcontent .iframe-item-key-'+key).show();
        }
        // 阻止事件
        return false;
    });

    // 移除独立窗口
    $(document).on('click', '.window-layer-alone-layer .window-layer-tab-bar .close', function()
    {
        // 移除当前页面
        var $parent = $(this).parents('.window-layer');
        var key = $parent.data('key');
        $parent.remove();
        $('.header-menu-open-pages-list ul li.nav-item-key-'+key).remove();
        // 当前没有选中的导航则模拟点击最后一个选中
        if($('.header-menu-open-pages-list ul li.am-active').length == 0)
        {
            $('.header-menu-open-pages-list ul li:last').trigger('click');
        }
        // 无页面则添加默认初始化页面
        if($('.header-menu-open-pages-list ul li').length == 0)
        {
            $('#ifcontent .window-layer').show();
        }
        return false;
    });

    // 弹窗拖拽
    $(document).on('mousedown', '.window-layer-alone-layer .window-layer-tab-bar', function(pe)
    {
        var is_move = true;
        var $content = $('#ifcontent');
        var $layer = $(this).parents('.window-layer');
        var $layer_seat = $layer.find('.window-layer-seat');
        var header_height = $('header.admin-header').height();
        var menu_width = parseInt($content.css('padding-left') || 0);
        var width = $layer.outerWidth();
        var height = $layer.outerHeight();
        var win_width = $content.width()+menu_width;
        var win_height = $content.height()+header_height;
        var abs_x = pe.pageX - $layer.offset().left;
        var abs_y = pe.pageY - $layer.offset().top;
        // 设置层级
        LayerPagesLevelHandle($layer.data('key'));
        $layer_seat.show();
        $(document).mousemove(function(event)
        {
            if(is_move)
            {
                // 左
                var left = event.pageX - abs_x
                if(left < menu_width)
                {
                    left = menu_width
                } else if (left > win_width - width)
                {
                    left = win_width - width;
                }

                // 上
                var top = event.pageY - abs_y;
                if(top < header_height)
                {
                    top = header_height;
                }
                if (top > win_height - height)
                {
                    top = win_height - height
                }

                // 设置层级
                var index = 0;
                $layer.parent().find('.window-layer-alone-layer').each(function()
                {
                    var temp_index = parseInt($(this).css('z-index') || 0);
                    if(temp_index > index)
                    {
                        index = temp_index
                    }
                });
                $layer.css({'left':left, 'top':top, 'margin': 0, 'position': 'fixed', 'z-index': index+1});
            };
        }).mouseup(function()
        {
            if(is_move)
            {
                $layer_seat.hide();
            }
            is_move = false;
        }).mouseleave(function()
        {
            if(is_move)
            {
                $layer_seat.hide();
            }
            is_move = false;
        });
    });

    // 独立窗口拉动大小
    $(document).on('mousedown', '.window-layer-alone-layer .window-layer-resize-bar div[class^="window-layer-resize-item-"]', function(pe)
    {
        var is_move = true;
        var $content = $('#ifcontent');
        var $layer = $(this).parents('.window-layer');
        var $layer_seat = $layer.find('.window-layer-seat');
        var py = pe.pageY;
        var px = pe.pageX;
        var resize_bar_type = $(this).data('type');
        var p_init_y = $layer.css('top').replace('px', '');
        var p_init_x = $layer.css('left').replace('px', '');
        var p_init_height = $layer.height();
        var p_init_width = $layer.width();
        var header_height = $('header.admin-header').height();
        var menu_width = parseInt($content.css('padding-left') || 0);
        var win_width = $content.width()+menu_width;
        var win_height = $content.height();
        var limit_min_width = 500;
        var limit_min_height = 300;
        // 设置层级
        LayerPagesLevelHandle($layer.data('key'));
        $layer_seat.show();
        $(document).mousemove(function(event)
        {
            if(is_move)
            {
                var hh = parseInt(event.pageY) - parseInt(py);
                var ww = parseInt(event.pageX) - parseInt(px);
                var temp_y = hh + parseInt(p_init_y);
                var temp_x = ww + parseInt(p_init_x);
                if(temp_y < header_height)
                {
                    temp_y = header_height;
                }

                // 高度
                var height = 0;
                if(['left-top', 'top', 'right-top'].indexOf(resize_bar_type) != -1)
                {
                    height = parseInt(p_init_height) - hh;
                }
                if(['left-bottom', 'bottom', 'right-bottom'].indexOf(resize_bar_type) != -1)
                {
                    height = parseInt(p_init_height) + hh;
                }
                if(height > win_height)
                {
                    height = win_height;
                }
                if(height < limit_min_height)
                {
                    height = limit_min_height;
                }

                // 宽度
                var width = 0;
                if(['left-top', 'left', 'left-bottom'].indexOf(resize_bar_type) != -1)
                {
                    width = parseInt(p_init_width) - ww;
                }
                if(['right-top', 'right', 'right-bottom'].indexOf(resize_bar_type) != -1)
                {
                    width = parseInt(p_init_width) + ww;
                }
                if(width > win_width)
                {
                    width = win_width;
                }
                if(width < limit_min_width)
                {
                    width = limit_min_width;
                }

                // 不允许超出外边距范围
                if(event.pageY-header_height <= 0 || event.pageY >= win_height+header_height)
                {
                    return false;
                }
                if(event.pageX >= win_width)
                {
                    return false;
                }
                if(event.pageX <= menu_width)
                {
                    return false;
                }

                // 根据类型设置样式
                switch(resize_bar_type)
                {
                    case 'left-top':
                        $layer.css({
                            top: temp_y + 'px',
                            height: height + 'px',
                            left: temp_x + 'px',
                            width: width + 'px'
                        });
                    break;
                    case 'top':
                        $layer.css({
                            top: temp_y + 'px',
                            height: height + 'px'
                        });
                    break;
                    case 'right-top':
                        $layer.css({
                            top: temp_y + 'px',
                            height: height + 'px',
                            width: width + 'px'
                        });
                    break;
                    case 'left':
                        $layer.css({
                        left: temp_x + 'px',
                            width: width + 'px'
                        });
                    break;
                    case 'right':
                        $layer.css({
                            width: width + 'px'
                        });
                    break;
                    case 'left-bottom':
                        $layer.css({
                            height: height + 'px',
                            left: temp_x + 'px',
                            width: width + 'px'
                        });
                    break;
                    case 'bottom':
                        $layer.css({
                            height: height + 'px'
                        });
                    break;
                    case 'right-bottom':
                        $layer.css({
                            height: height + 'px',
                            width: width + 'px'
                        });
                    break;
                }
            }
        }).mouseup(function()
        {
            if(is_move)
            {
                $layer_seat.hide();
            }
            is_move = false;
        }).mouseleave(function()
        {
            if(is_move)
            {
                $layer_seat.hide();
            }
            is_move = false;
        });
    });
});