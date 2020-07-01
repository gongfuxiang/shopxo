$(function()
{
    // 登录页面背景切换
    var count = $('.bg-slides-item').length;
    if(count > 1)
    {
        var temp_old = 0;
        var temp_new = 1;
        var fade_time = 1000;
        var interval_time = 6000;
        setInterval(function()
        {
            $('.bg-slides-item').eq(temp_old).fadeOut(fade_time);
            $('.bg-slides-item').eq(temp_new).fadeIn(fade_time);
            temp_old = temp_new;
            temp_new++;
            if(temp_new > count-1)
            {
                temp_new = 0;
            }
        }, interval_time);
    } else if(count == 1)
    {
        // 只有一张图片则直接显示
        $('.bg-slides-item').show();
    }
});