$(function()
{
  // 在线客服
  $('.commononlineservice .btn-open').click(function()
    {
        $('.commononlineservice .content').animate({'margin-right':'0px'}, 300);
        $('.commononlineservice .btn-open').css('display', 'none');
        $('.commononlineservice .btn-ctn').css('display', 'block');        
    });

    $('.commononlineservice .btn-ctn').click(function()
    {
        $('.commononlineservice .content').animate({'margin-right':'-150px'}, 300);
        $('.commononlineservice .btn-open').css('display', 'block');
        $('.commononlineservice .btn-ctn').css('display', 'none');  
    });
});