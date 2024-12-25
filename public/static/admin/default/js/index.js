/**
 * 独立弹窗层级处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-02-15
 * @desc    description
 * @param   {[string]}        key [窗口可以]
 */
function LayerPagesLevelHandle (key) {
    var index = 0;
    var $content = $('#ifcontent');
    $content.find('.window-layer-alone-layer').each(function () {
        var temp_index = parseInt($(this).css('z-index') || 0);
        if (temp_index > index) {
            index = temp_index
        }
    });
    $content.find('.window-layer .window-layer-seat').show();
    var $layer = $content.find('.window-layer.iframe-item-key-' + key);
    $layer.css({ 'z-index': index + 1, 'position': 'fixed' });
    $layer.find('.window-layer-seat').hide();
}

/**
 * 展开菜单
 * @author  kevin
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-12-06
 * @desc    description
 */
function OpenMenu () {
    $('.menu-scaling-submit').addClass('open-menu');
    $('.menu-scaling-submit').animate({ left: '135px' }, 300);
    $('.menu-scaling-submit').removeClass('icon-stretch').addClass('icon-shrink');
    $('#admin-offcanvas').addClass('open-child-menu');
    $('#admin-offcanvas').animate({ width: '203px' }, 300);
    $('#ifcontent').animate({ paddingLeft: '203px' }, 300);
    $('header.admin-header').animate({ left: '203px' }, 300);
}

/**
 * 收起菜单
 * @author  kevin
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-12-06
 * @desc    description
 */
function CloseMenu () {
    $('.menu-scaling-submit').removeClass('open-menu');
    $('.menu-scaling-submit').animate({ left: '30px' }, 300);
    $('.menu-scaling-submit').removeClass('icon-shrink').addClass('icon-stretch');
    $('#admin-offcanvas').removeClass('open-child-menu');
    $('#admin-offcanvas').animate({ width: '80px' }, 300);
    $('#ifcontent').animate({ paddingLeft: '80px' }, 300);
    $('header.admin-header').animate({ left: '80px' }, 300);
}

/**
 * 关闭tabs工具
 * @author  kevin
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-12-06
 * @desc    description
 */
function IframeCloseNavTabsRightTools () {
    $('.tabs-list .tabs-tool').dropdown('close');
}

$(function () {
    // 左侧菜单箭头方向回调处理
    $('#admin-offcanvas li.admin-parent').on('open.collapse.amui', function () {
        $(this).find('a i').toggleClass('top-menu-more-icon-rotate');
    }).on('close.collapse.amui', function () {
        $(this).find('a i').toggleClass('top-menu-more-icon-rotate');
    });

    // url加载
    $(document).on('click', '.common-left-menu li a, .admin-header-right-list li a.new-window', function () {
        var url = $(this).data('url') || null;
        var type = $(this).data('type');
        var key = $(this).data('key');
        var name = $(this).data('node-name') || $(this).find('.nav-name').text();
        AdminTopNavIframeAddHandle(url, name, key, type);
    });
    // 顶级左侧菜单选择
    $(document).on('click', '.must-menu-list .common-left-menu li a', function (event) {
        $('.common-left-menu li a').removeClass('common-left-menu-active');
        $(this).addClass('common-left-menu-active');
        if (window.innerWidth > 641) {
            if ($(this).parent().find('ul').length > 0) {
                OpenMenu();
                $('.child-menu-list').find('ul.second').addClass('am-hide');
                $('.child-menu-list').find('#power-menu-' + $(this).data('id')).removeClass('am-hide');
                $('.child-menu-list .menu-name').text($(this).text().trim());
                $('.menu-scaling-submit').removeClass('am-hide');
                // 获取下级菜单第一个并打开
                $('.child-menu-list').find('#power-menu-' + $(this).data('id') + '>li>a').addClass('am-collapsed');
                $('.child-menu-list').find('#power-menu-' + $(this).data('id') + '>li>a i').removeClass('left-menu-more-icon-rotate');
                $('.child-menu-list').find('#power-menu-' + $(this).data('id') + '>li>ul').removeClass('am-in');
                $('.child-menu-list').find('#power-menu-' + $(this).data('id') + ' li:eq(0)>a').trigger('click');


                if (!$('.child-menu-list').find('#power-menu-' + $(this).data('id') + ' li:eq(0)>a').hasClass('am-collapsed')) {
                    $('.child-menu-list').find('#power-menu-' + $(this).data('id') + ' li:eq(0)>ul li:eq(0) a').trigger('click');
                } else {
                    if ($(this).parent().find('ul').length <= 0) {
                        $('.child-menu-list').find('#power-menu-' + $(this).data('id') + ' li:eq(0)>ul li:eq(0) a').trigger('click');
                    }
                }
            } else {
                CloseMenu();
                $('.menu-scaling-submit').addClass('am-hide');
            }
        }
    });
    // 二级右侧菜单选择（全部包含下级菜单）
    $(document).on('click', '.child-menu-list .common-left-menu li a', function () {
        $(this).parent().parent().find('a').removeClass('common-left-menu-active');
        $(this).addClass('common-left-menu-active');
    });

    // 二级菜单开关
    $(document).on('click', '.menu-scaling-submit', function () {
        $('.must-menu-list .common-left-menu>li').each((k, v) => {
            if ($(v).find('a').hasClass('common-left-menu-active') && $(v).find('ul.am-list').length > 0) {
                if ($('#admin-offcanvas').hasClass('open-child-menu')) {
                    CloseMenu();
                } else {
                    OpenMenu();
                }
            }
        })
    });

    // 浏览器窗口实时事件
    $(window).resize(function () {
        var data_status = $('.menu-scaling-submit').hasClass('open-menu');
        // 小屏幕关闭左侧导航
        if ($(document).width() <= 640) {
            $('#admin-offcanvas').css({ 'width': 'inherit' });
            $('#ifcontent').css({ 'padding-left': 0 });
            $('header.admin-header').css({ 'left': 0 });
        } else {
            if (data_status) {
                $('#admin-offcanvas').css({ 'width': '203px' });
                $('#ifcontent').css({ 'padding-left': '203px' });
                $('header.admin-header').css({ 'left': '203px' });
            } else {
                $('#admin-offcanvas').css({ 'width': '80px' });
                $('#ifcontent').css({ 'padding-left': '80px' });
                $('header.admin-header').css({ 'left': '80px' });
            }
        }
    });

    // 页面切换
    var window_layer_alone_layer_warning_timer = null;
    // 是否第一次加载菜单
    var is_reload_menu_status = 0;
    $(document).on('click', '.header-menu-open-pages-list ul li', function (event) {
        // 选中当前页面
        $('.header-menu-open-pages-list ul li').removeClass('am-active');
        $(this).addClass('am-active');
        var key = $(this).data('key');
        var $content = $('#ifcontent');
        // 显示当前页面
        $content.find('.window-layer').not('.window-layer-alone-layer').hide();
        if ($('#ifcontent .iframe-item-key-' + key).length > 0) {
            var $current_iframe = $('#ifcontent .iframe-item-key-' + key);
            $current_iframe.show();

            // 窗口存在独立
            if ($current_iframe.hasClass('window-layer-alone-layer')) {
                // 窗口存在独立则警告窗口提示
                var count = 0;
                clearInterval(window_layer_alone_layer_warning_timer);
                window_layer_alone_layer_warning_timer = setInterval(function () {
                    if (count > 10) {
                        clearInterval(window_layer_alone_layer_warning_timer);
                    } else {
                        $current_iframe.css('box-shadow', 'rgb(0 0 0 / 30%) 1px 1px ' + (count % 2 == 0 ? '12px' : '24px'));
                        count++;
                    }
                }, 50);
                // 设置层级
                LayerPagesLevelHandle(key);
            } else {
                // 非独立窗口则隐藏所有页面占位
                $content.find('.window-layer .window-layer-seat').hide();
            }
        } else {
            var url = $(this).data('url');
            var type = $(this).data('type');
            var key = $(this).data('key');
            var name = $(this).data('name');
            AdminTopNavIframeAddHandle(url, name, key, type);
        }
        if (window.innerWidth > 641) {
            // 菜单跟随切换
            $('.menu-list').find('a').removeClass('common-left-menu-active');
            $('.menu-list').find("[data-key='" + key + "']").each((k, v) => {
                var menu_id_level_list = '';
                if ($(v).data('parent')) {
                    menu_id_level_list = $(v).data('parent').toString().split('|');
                    $('.menu-list').find('.menu-parent-items-' + menu_id_level_list[0]).addClass('common-left-menu-active');
                    // 菜单打开
                    OpenMenu();
                    $('.menu-scaling-submit').removeClass('am-hide');
                    $('.child-menu-list').find('ul.second').addClass('am-hide');
                    $('.child-menu-list').find('#power-menu-' + menu_id_level_list[0]).removeClass('am-hide');
                    $('.child-menu-list .menu-name').text($('.menu-list').find('.menu-parent-items-' + menu_id_level_list[0]).text().trim());

                    // 打开菜单并展开折叠
                    if (menu_id_level_list.length > 1) {
                        $('#power-menu-' + menu_id_level_list[1]).collapse('open');
                    }
                } else {
                    // 菜单关闭
                    CloseMenu();
                    $('.menu-scaling-submit').addClass('am-hide');
                }
                $(v).addClass('common-left-menu-active');
            })
            var offset_scroll_top = $('.menu-list').find('.must-menu-scroll').scrollTop();
            var $menu_active = $('.menu-list').find('a.common-left-menu-active');
            if ($menu_active.length > 0) {
                $('.must-menu-list-am-active').css({ 'display': 'block' });
                var offset_top = $('.menu-list a.common-left-menu-active').parent().offset().top - ($('.must-menu-list-am-active').hasClass('is_logo') ? $('.menu-logo').height() : 0);
                if (is_reload_menu_status == 0 || (event.originalEvent && event.originalEvent.isTrusted)) {
                    $('.must-menu-scroll').animate({
                        scrollTop: offset_top + offset_scroll_top,
                    }, 300);
                    is_reload_menu_status = 1;
                }

                $('.must-menu-list-am-active').animate({
                    'top': offset_top + offset_scroll_top
                }, 300);
            } else {
                $('.must-menu-list-am-active').css({ 'top': 0, 'display': 'none' });
            }
        }
        // 存储tabs标签数据
        AdminMenuNavTabsMemoryHandle();
    });

    // 页面移除
    $(document).on('click', '.header-menu-open-pages-list ul li a', function () {
        // 移除当前页面
        var $parent = $(this).parent();
        var key = $parent.data('key');
        $parent.remove();
        $('#ifcontent .iframe-item-key-' + key).remove();
        // 当tabs没有了时，工具箱隐藏
        if ($('.header-menu-open-pages-list ul li').length == 0) {
            $('.tabs-tool').addClass('am-hide')
        }
        // 当前没有选中的导航则模拟点击最后一个选中
        if ($('.header-menu-open-pages-list ul li.am-active').length == 0) {
            $('.header-menu-open-pages-list ul li:last').trigger('click');
        }
        // 无页面则添加默认初始化页面
        if ($('.header-menu-open-pages-list ul li').length == 0) {
            $('#ifcontent .window-layer').show();
            $('.menu-list').find('a').removeClass('common-left-menu-active');
            $('.menu-home').addClass('common-left-menu-active');
            CloseMenu();
        }
        return false;
    });
    // tabs页面操作工具
    $(document).on('click', '.tabs-list .tabs-tool ul li a', function () {
        // 移除当前页面
        var $nav_list = $(this).parents('.tabs-list').find('.header-menu-open-pages-list ul li');
        // 获取当前点击的key
        var key = $(this).data('key');
        if (key == 'nav-refresh') {
            // 刷新
            var nav_key = null;
            $nav_list.each((k, v) => {
                if ($(v).hasClass('am-active')) {
                    nav_key = $(v).data('key');
                }
            });
            if (nav_key) {
                $('#ifcontent .window-layer').each((k, v) => {
                    if ($(v).data('key') == nav_key) {
                        if ($(v).find('iframe').attr('src', $(v).find('iframe').attr('src')));
                    }
                });
            }
            $(this).parents('.tabs-tool').dropdown('close');
        } else if (key == 'nav-close') {
            // 关闭
            $nav_list.each((k, v) => {
                if ($(v).hasClass('am-active')) {
                    $(v).find('a').click();
                }
            });
            if ($('.header-menu-open-pages-list ul li').length == 0) {
                $('.menu-list').find('a').removeClass('common-left-menu-active');
                $('.menu-home').addClass('common-left-menu-active');
                CloseMenu();
            }
        } else if (key == 'nav-close-other') {
            // 关闭其他
            $nav_list.each((k, v) => {
                if (!$(v).hasClass('am-active')) {
                    $(v).find('a').click();
                }
            });
            $(this).parents('.tabs-tool').dropdown('close');
        } else if (key == 'nav-close-all') {
            // 关闭所有
            $nav_list.each((k, v) => {
                $(v).find('a').click();
            });
            $('.menu-list').find('a').removeClass('common-left-menu-active');
            $('.menu-home').addClass('common-left-menu-active');
            CloseMenu();
        }
        // 存储tabs标签数据
        AdminMenuNavTabsMemoryHandle();
    });
    $('.tabs-tool').on('open.dropdown.amui', function (e) {
        var self = $(this);
        var tabs_lsit = $('.tabs-list .header-menu-open-pages-list ul li');
        tabs_lsit.each((i, item) => {
            if ($(item).hasClass('am-active') && $(item).data('key') == '-') {
                self.find('ul li').each((c, child) => {
                    if ($(child).find('a').data('key') == 'nav-close' || $(child).find('a').data('key') == 'nav-close-all') {
                        $(child).addClass('am-hide');
                    } else {
                        $(child).removeClass('am-hide');
                    }
                })
                return false;
            } else {
                self.find('ul li').removeClass('am-hide');
            }
        })
    });

    // 双击为独立窗口
    $(document).on('dblclick', '.header-menu-open-pages-list ul li', function () {
        var key = $(this).data('key') || null;
        // 判断是否为首页
        if (key != '-') {
            // 增加独立窗口类
            $('.iframe-item-key-' + key).addClass('window-layer-alone-layer').show();
            // 设置层级
            LayerPagesLevelHandle(key);
        }
    });

    // 窗口切换
    $(document).on('click', '#ifcontent .window-layer', function () {
        if ($(this).hasClass('window-layer-alone-layer')) {
            LayerPagesLevelHandle($(this).data('key'));
        } else {
            // 非独立窗口则隐藏所有页面占位
            $('#ifcontent .window-layer .window-layer-seat').hide();
        }
    });

    // 独立窗口刷新
    $(document).on('click', '.window-layer-alone-layer .window-layer-tab-bar .refresh', function () {
        var $parent = $(this).parents('.window-layer');
        if ($parent.find('iframe').attr('src', $parent.find('iframe').attr('src')));
    });

    // 收回独立窗口
    $(document).on('click', '.window-layer-alone-layer .window-layer-tab-bar .recovery', function () {
        // 移除class和样式
        $(this).parents('.window-layer').removeClass('window-layer-alone-layer').css({ 'position': '', 'left': '', 'top': '', 'box-shadow': '', 'width': '', 'height': '', 'z-index': '' });
        // 显示已选中页面
        var key = $('.header-menu-open-pages-list ul li.am-active').data('key') || null;
        if (key != null) {
            $('#ifcontent .window-layer').not('.window-layer-alone-layer').hide();
            $('#ifcontent .iframe-item-key-' + key).show();
        }
        // 阻止事件
        return false;
    });

    // 移除独立窗口
    $(document).on('click', '.window-layer-alone-layer .window-layer-tab-bar .close', function () {
        // 移除当前页面
        var $parent = $(this).parents('.window-layer');
        var key = $parent.data('key');
        $parent.remove();
        $('.header-menu-open-pages-list ul li.nav-item-key-' + key).remove();
        // 当前没有选中的导航则模拟点击最后一个选中
        if ($('.header-menu-open-pages-list ul li.am-active').length == 0) {
            $('.header-menu-open-pages-list ul li:last').trigger('click');
        }
        // 无页面则添加默认初始化页面
        if ($('.header-menu-open-pages-list ul li').length == 0) {
            $('#ifcontent .window-layer').show();
        }
        return false;
    });

    // 弹窗拖拽
    $(document).on('mousedown', '.window-layer-alone-layer .window-layer-tab-bar', function (pe) {
        var is_move = true;
        var $content = $('#ifcontent');
        var $layer = $(this).parents('.window-layer');
        var $layer_seat = $layer.find('.window-layer-seat');
        var header_height = $('header.admin-header').height();
        var menu_width = parseInt($content.css('padding-left') || 0);
        var width = $layer.outerWidth();
        var height = $layer.outerHeight();
        var win_width = $content.width() + menu_width;
        var win_height = $content.height() + header_height;
        var abs_x = pe.pageX - $layer.offset().left;
        var abs_y = pe.pageY - $layer.offset().top;
        // 设置层级
        LayerPagesLevelHandle($layer.data('key'));
        $layer_seat.show();
        $(document).mousemove(function (event) {
            if (is_move) {
                // 左
                var left = event.pageX - abs_x
                if (left < menu_width) {
                    left = menu_width
                } else if (left > win_width - width) {
                    left = win_width - width;
                }

                // 上
                var top = event.pageY - abs_y;
                if (top < header_height) {
                    top = header_height;
                }
                if (top > win_height - height) {
                    top = win_height - height
                }

                // 设置层级
                var index = 0;
                $layer.parent().find('.window-layer-alone-layer').each(function () {
                    var temp_index = parseInt($(this).css('z-index') || 0);
                    if (temp_index > index) {
                        index = temp_index
                    }
                });
                $layer.css({ 'left': left, 'top': top, 'margin': 0, 'position': 'fixed', 'z-index': index + 1 });
            };
        }).mouseup(function () {
            if (is_move) {
                $layer_seat.hide();
            }
            is_move = false;
        }).mouseleave(function () {
            if (is_move) {
                $layer_seat.hide();
            }
            is_move = false;
        });
    });

    // 独立窗口拉动大小
    $(document).on('mousedown', '.window-layer-alone-layer .window-layer-resize-bar div[class^="window-layer-resize-item-"]', function (pe) {
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
        var win_width = $content.width() + menu_width;
        var win_height = $content.height();
        var limit_min_width = 500;
        var limit_min_height = 300;
        // 设置层级
        LayerPagesLevelHandle($layer.data('key'));
        $layer_seat.show();
        $(document).mousemove(function (event) {
            if (is_move) {
                var hh = parseInt(event.pageY) - parseInt(py);
                var ww = parseInt(event.pageX) - parseInt(px);
                var temp_y = hh + parseInt(p_init_y);
                var temp_x = ww + parseInt(p_init_x);
                if (temp_y < header_height) {
                    temp_y = header_height;
                }

                // 高度
                var height = 0;
                if (['left-top', 'top', 'right-top'].indexOf(resize_bar_type) != -1) {
                    height = parseInt(p_init_height) - hh;
                }
                if (['left-bottom', 'bottom', 'right-bottom'].indexOf(resize_bar_type) != -1) {
                    height = parseInt(p_init_height) + hh;
                }
                if (height > win_height) {
                    height = win_height;
                }
                if (height < limit_min_height) {
                    height = limit_min_height;
                }

                // 宽度
                var width = 0;
                if (['left-top', 'left', 'left-bottom'].indexOf(resize_bar_type) != -1) {
                    width = parseInt(p_init_width) - ww;
                }
                if (['right-top', 'right', 'right-bottom'].indexOf(resize_bar_type) != -1) {
                    width = parseInt(p_init_width) + ww;
                }
                if (width > win_width) {
                    width = win_width;
                }
                if (width < limit_min_width) {
                    width = limit_min_width;
                }

                // 不允许超出外边距范围
                if (event.pageY - header_height <= 0 || event.pageY >= win_height + header_height) {
                    return false;
                }
                if (event.pageX >= win_width) {
                    return false;
                }
                if (event.pageX <= menu_width) {
                    return false;
                }

                // 根据类型设置样式
                switch (resize_bar_type) {
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
        }).mouseup(function () {
            if (is_move) {
                $layer_seat.hide();
            }
            is_move = false;
        }).mouseleave(function () {
            if (is_move) {
                $layer_seat.hide();
            }
            is_move = false;
        });
    });

    // 监听顶部tabs记忆展示
    AdminMenuNavTabsMemoryView();

    // 清除选项卡的缓存
    $(document).on('click', '.clear-cache-html', function () {
        var key = AdminMenuNavTabsMemoryKey();
        var key_data = localStorage.getItem(key);
        if (key_data.length <= 1) {
            localStorage.removeItem(key);
        }
    });
});