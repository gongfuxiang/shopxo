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
    $('.address-submit').on('click', function()
    {
        ModalLoad($(this).data('url'), '地址管理', 'popup-modal-address', 'common-address-modal');
    });


    
}); 