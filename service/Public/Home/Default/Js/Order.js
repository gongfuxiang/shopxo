$(function()
{
    /* 搜索切换 */
    var $so_list = $('.so-list');
    $thin_sub = $('.thin_sub');
    $thin_sub.find('input[name="so_type"]').change(function()
    {
        if($thin_sub.find('i').hasClass('am-icon-angle-down'))
        {
            $thin_sub.find('i').removeClass('am-icon-angle-down');
            $thin_sub.find('i').addClass('am-icon-angle-up');
        } else {
            $thin_sub.find('i').addClass('am-icon-angle-down');
            $thin_sub.find('i').removeClass('am-icon-angle-up');
        }
    
        if($thin_sub.find('input[name="so_type"]:checked').val() == undefined)
        {
            $so_list.addClass('none');
        } else {
            $so_list.removeClass('none');
        }
    });

});