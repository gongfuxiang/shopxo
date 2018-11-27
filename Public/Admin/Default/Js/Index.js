/**
 * 全屏操作
 */
var $fullscreen = $.AMUI.fullscreen;
$('#admin-fullscreen').on('click', function()
{
	$fullscreen.toggle();
});
if($fullscreen.enabled)
{
    $(document).on($fullscreen.raw.fullscreenchange, function()
    {
    	$tag = $('.admin-fulltext');
    	$tag.text($fullscreen.isFullscreen ? $tag.attr('fulltext-exit') : $tag.attr('fulltext-open'));
    });
}

/**
 * url加载
 */
$('.common-left-menu, .common-nav-top').find('li a').on('click', function()
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