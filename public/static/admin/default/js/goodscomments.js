$(function()
{
    // 表单初始化
    FromInit('form.form-validation-reply');

    // 处理
    $(document).on('click', '.submit-reply', function()
    {
        var json = $(this).data('json') || {};
        var user = json.user || {};
        var $popup = $('#my-popup-reply');
        var $user_base = $popup.find('.user-info .user-base');
        $popup.find('input[name="id"]').val(json.id);
        
        // 用户信息
        $popup.find('.user-info img').attr('src', user.avatar || $popup.find('.user-info img').attr('src'));
        $user_base.find('.username span').html(user.username || '');
        $user_base.find('.nickname span').html(user.nickname || '');
        $user_base.find('.mobile span').html(user.mobile || '');
        $user_base.find('.email span').html(user.email || '');
        $popup.find('.content').html(json.content || '');
        $popup.find('textarea[name="reply"]').val(json.reply || '');

        // 商品
        var $goods_info = $popup.find('.goods-info');
        var goods = json.goods || null;
        if(goods == null)
        {
            $goods_info.html('<span class="am-color-grey">'+(lang_not_data_error || '无信息')+'</span>');
            
        } else {
            $goods_info.removeClass('am-hide');
            $goods_info.html(`<div class="base am-nbfc">
                    <a href="`+goods.goods_url+`" target="_blank">
                        <img src="`+goods.images+`" class="am-img-thumbnail am-radius am-align-left am-margin-right-xs am-fl" width="60" height="60" />
                    </a>
                    <a class="am-text-top am-nowrap-initial title" href="`+goods.goods_url+`" target="_blank">`+goods.title+`</a>
                </div>
                <p class="price">`+goods.show_price_symbol+goods.price+`</p>`);
        }
    });
});