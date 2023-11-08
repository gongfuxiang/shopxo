$(function()
{
    // 表单初始化
    FromInit('form.form-validation-reply');

    // 处理
    $(document).on('click', '.submit-reply', function()
    {
        var json = $(this).data('json') || {};
        var user = json.user || {};
        var goods = json.goods || {};
        var $popup = $('#my-popup-reply');
        var $user_base = $popup.find('.user-info .user-base');
        var $goods_info = $popup.find('.goods-info');
        var $goods_base = $goods_info.find('.base');
        $popup.find('input[name="id"]').val(json.id);
        $popup.find('.user-info img').attr('src', user.avatar || $popup.find('.user-info img').attr('src'));
        $user_base.find('.username span').html(user.username || '');
        $user_base.find('.nickname span').html(user.nickname || '');
        $user_base.find('.mobile span').html(user.mobile || '');
        $user_base.find('.email span').html(user.email || '');
        $goods_base.find('a').attr('href', goods.goods_url || 'javascript:;');
        $goods_base.find('img').attr('src', goods.images || $goods_base.find('img').attr('src'));
        $goods_info.find('.title').html(goods.title);
        $goods_info.find('.price').html(__currency_symbol__+goods.price);
        $popup.find('.content').html(json.content || '');
        $popup.find('textarea[name="reply"]').val(json.reply || '');
    });
});