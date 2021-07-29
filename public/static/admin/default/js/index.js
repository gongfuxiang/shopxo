/**
 * 系统更新异步请求步骤
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-22
 * @desc    description
 * @param   {[string]}        url   [url地址]
 * @param   {[string]}        opt   [操作类型（url 获取下载地址， download_system 下载系统包， download_upgrade 下载升级包， upgrade 更新操作）]
 * @param   {[string]}        msg   [提示信息]
 */
function SystemUpgradeRequestHandle(params)
{
    // 参数处理
    if((params || null) == null)
    {
        Prompt('操作参数有误');
        return false;
    }
    var url = params.url || null;
    var opt = params.opt || 'url';
    var msg = params.msg || '正在获取中...';

    // 加载提示
    AMUI.dialog.loading({title: msg});

    // ajax
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        timeout: 305000,
        data: {"opt":opt},
        success: function(result)
        {
            if((result || null) != null && result.code == 0)
            {
                switch(opt)
                {
                    // 获取下载地址
                    case 'url' :
                        params['opt'] = 'download_system';
                        params['msg'] = '系统包正在下载中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 下载系统包
                    case 'download_system' :
                        params['opt'] = 'download_upgrade';
                        params['msg'] = '升级包正在下载中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 下载升级包
                    case 'download_upgrade' :
                        params['opt'] = 'upgrade';
                        params['msg'] = '正在更新中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 更新完成
                    case 'upgrade' :
                        Prompt(result.msg, 'success');
                        setTimeout(function()
                        {
                            window.location.reload();
                        }, 1500);
                        break;
                }
            } else {
                AMUI.dialog.loading('close');
                Prompt(((result || null) == null) ? '返回数据格式错误' : (result.msg || '异常错误'));
            }
        },
        error: function(xhr, type)
        {
            AMUI.dialog.loading('close');
            Prompt(HtmlToString(xhr.responseText) || '异常错误');
        }
    });
}

$(function()
{
    /**
     * url加载
     */
    $(document).on('click', '.common-left-menu li a, .common-nav-top li a, .menu-mini-container-popup ul li a', function()
    {
        var link = $(this).data('url');
        var type = $(this).data('type');
        if(link != undefined)
        {
        	// 打开url地址
            $('#ifcontent').attr('src', link);

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
        } else {
        	// 左侧菜单剪头方向处理
        	if(type == 'menu')
        	{
    	    	if($(this).find('i').length > 0)
    	    	{
    	    		$(this).find('i').toggleClass('left-menu-more-ico-rotate');
    	    	}
        	}
        }
    });

    /**
     * 菜单选择
     */
    $('.common-left-menu li a').on('click', function()
    {
        $('.common-left-menu a').removeClass('common-left-menu-active');
        $(this).addClass('common-left-menu-active');
    });

    /**
     * mini伸缩开关
     */
    $('.menu-scaling-submit').on('click', function()
    {
        var status = $(this).attr('data-status') || 0;
        $('#admin-offcanvas ul').css('opacity', 0.1);
        $('.menu-mini-container-popup').hide();
        $('.menu-mini-container-tips').hide();
        if(status == 0)
        {
            $(this).animate({left: "59px"}, 300);
            $(this).removeClass('am-icon-angle-double-left').addClass('am-icon-angle-double-right');
            $('#admin-offcanvas').addClass('menu-mini').addClass('menu-mini-event');
            $('#admin-offcanvas').animate({width: "55px"}, 300);
            $('#ifcontent').animate({paddingLeft: "55px"}, 300);
            $('header.admin-header').animate({left: "54px"}, 300);
        } else {
            $(this).animate({left: "134px"}, 300);
            $(this).removeClass('am-icon-angle-double-right').addClass('am-icon-angle-double-left');
            $('#admin-offcanvas').animate({width: "130px"}, 300);
            $('#ifcontent').animate({paddingLeft: "130px"}, 300);
            $('header.admin-header').animate({left: "129px"}, 300);
            $('#admin-offcanvas').removeClass('menu-mini-event');
            setTimeout(function() {
                $('#admin-offcanvas').removeClass('menu-mini');
            }, 300);
        }
        $('#admin-offcanvas ul').animate({opacity: 1}, 300);
        $(this).attr('data-status', status == 0 ? 1 : 0);
    });

    /**
     * mini菜单操作
     */
    var timer_menu = null;
    $(document).on('mouseenter', '.menu-mini-event li', function()
    {
        clearTimeout(timer_menu);
        var html = $(this).find('ul.admin-sidebar-sub').html() || null;
        var top = $(this).offset().top;
        var win_height = $(window).height();
        if(html == null)
        {
            $('.menu-mini-container-popup').hide();
            $('.menu-mini-container-tips').show();
            $('.menu-mini-container-tips span').text($(this).find('span.nav-name').text());
            $('.menu-mini-container-tips').css('top', top);
        } else {
            $('.menu-mini-container-popup ul').html(html);
            $('.menu-mini-container-tips').hide();
            $('.menu-mini-container-popup').show();
            
            // 容器是否超出底部
            var h = $('.menu-mini-container-popup').height();
            if(h+top > win_height)
            {
                var t = win_height-h;
                $('.menu-mini-container-popup').css('top', t);
                $('.menu-mini-container-popup .mui-mbar-tab-tip').css('top', top-t+12);
            } else {
                $('.menu-mini-container-popup').css('top', top);
                $('.menu-mini-container-popup .mui-mbar-tab-tip').css('top', '10px');
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

    /**
     * mini菜单选择
     */
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
            $('.menu-scaling-submit').css({"left": "134px"});
            $('.menu-scaling-submit').removeClass('am-icon-angle-double-right').addClass('am-icon-angle-double-left');
            $('#admin-offcanvas').css({"width": "inherit"});
            $('#admin-offcanvas').removeClass('menu-mini').removeClass('menu-mini-event');
            $('#ifcontent').css({"padding-left":0});
            $('header.admin-header').css({"left": 0});
        } else {
            if(($('.menu-scaling-submit').attr('data-status') || 0) == 0)
            {
                $('#admin-offcanvas').css({"width": "130px"});
                $('#ifcontent').css({"padding-left":"130px"});
                $('header.admin-header').css({"left": "129px"});
            }
        }
    });

    // 检查更新
    var $inspect_upgrade_popup = $('#inspect-upgrade-popup');
    $('.inspect-upgrade-submit').on('click', function()
    {
        // 基础信息
        AMUI.dialog.loading({title: '正在获取最新内容、请稍候...'});

        // ajax请求
        $.ajax({
            url: $(this).data('url'),
            type: 'POST',
            dataType: 'json',
            timeout: 30000,
            data: {},
            success: function(result)
            {
                AMUI.dialog.loading('close');
                if(result.code == 0)
                {
                    // html内容处理
                    // 基础信息
                    // 是否存在数据、网络不通将返回空数据
                    if((result.data || null) != null)
                    {
                        var html = '<p class="upgrade-title">';
                            html += '<i class="am-icon-info-circle am-icon-md am-text-warning"></i>';
                            html += '<span class="am-margin-left-xs">'+result.data.title+'</span>';
                            html += '</p>';
                            html += '<div class="am-alert upgrade-base">';
                            html += '<span class="upgrade-ver">更新版本：'+result.data.version_new+'</span>';
                            html += '<span class="upgrade-date am-margin-left-sm">更新日期：'+result.data.add_time+'</span>';
                            // 是否带指定链接和链接名称
                            if((result.data.go_title || null) != null && (result.data.go_url || null) != null)
                            {
                                html += '<a href="'+result.data.go_url+'" class="upgrade-go-detail am-margin-left-lg" target="_blank">'+result.data.go_title+'</a>';
                            }
                            html += '</div>';

                            // 提示信息
                            if((result.data.tips || null) != null)
                            {
                                html += '<div class="am-alert am-alert-danger">';
                                html += '<p class="am-text-danger">'+result.data.tips+'</p>';
                                html += '</div>';
                            }

                            // 更新内容介绍
                            if((result.data.content || null) != null && result.data.content.length > 0)
                            {
                                html += '<div class="am-alert am-alert-secondary upgrade-content-item">';
                                html += '<ul>';
                                for(var i in result.data.content)
                                {
                                    html += '<li>'+result.data.content[i]+'</li>';
                                }
                                html += '</ul>';
                                html += '</div>';
                            }
                    } else {
                        var html = '<p class="upgrade-title am-text-center am-margin-top-xl am-padding-top-xl">';
                            html += '<i class="am-icon-info-circle am-icon-md am-text-warning"></i>';
                            html += '<span class="am-margin-left-xs">'+result.msg+'</span>';
                            html += '</p>';
                    }
                    $inspect_upgrade_popup.find('.upgrade-content').html(html);

                    // 是否支持在线自动更新
                    if((result.data.is_auto || 0) == 1)
                    {
                        $inspect_upgrade_popup.find('.inspect-upgrade-confirm').removeClass('am-hide');
                    } else {
                        $inspect_upgrade_popup.find('.inspect-upgrade-confirm').addClass('am-hide');
                    }

                    // 打开弹窗
                    $inspect_upgrade_popup.modal('open');
                } else {
                    Prompt(result.msg);
                }
            },
            error: function(xhr, type)
            {
                AMUI.dialog.loading('close');
                Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
            }
        });
    });

    // 系统更新确认
    $('.inspect-upgrade-confirm').on('click', function()
    {
        $inspect_upgrade_popup.modal('close');
        SystemUpgradeRequestHandle({"url": $(this).data('url')});
    });
});