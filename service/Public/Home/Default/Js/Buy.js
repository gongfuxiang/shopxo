$(function() {
    // 地址选择
    $('ul.address-list li').click(function() {
        $(this).addClass("address-default").siblings().removeClass("address-default");
    });

    // 混合列表选择
    $('.business-item ul li').on('click', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            $(this).addClass('selected').siblings("li").removeClass('selected');
        }
    });

 
 
// 弹出地址选择


    var $ww = $(window).width();

    $('.address-submit').click(function() {
//                  禁止遮罩层下面的内容滚动
        $(document.body).css("overflow","hidden");
    
        $(this).addClass("selected");
        $(this).parent().addClass("selected");

                        
        $('.theme-popover-mask').show();
        $('.theme-popover-mask').height($(window).height());
        $('.theme-popover').slideDown(200);                                                                     
        
    })
    
    $('.theme-poptit .close,.btn-op .close').click(function() {

        $(document.body).css("overflow","visible");
        $('.address-submit').removeClass("selected");
        $('.item-props-can').removeClass("selected");                   
        $('.theme-popover-mask').hide();
        $('.theme-popover').slideUp(200);
    });

    
}); 