$(function()
{
    // 回顶部监测
    $(window).scroll(function()
    {
      if($(window).scrollTop() > 100)
      {
        $("#plugins-commonrightnavigation").fadeIn(1000);
      } else {
        $("#plugins-commonrightnavigation").fadeOut(1000);
      }
    });
});