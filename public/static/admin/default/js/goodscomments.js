$(function()
{
    // 表单初始化
    FromInit('form.form-validation-reply');

    // 处理
    $('.submit-reply').on('click', function()
    {
        var json = $(this).data('json');
        var $popup = $('#my-popup-reply');
        $popup.find('input[name="id"]').val(json.id);
        $popup.find('.user-info img').attr('src', json.user.avatar || $popup.find('.user-info img').attr('src'));
        $popup.find('.user-info .user-base .username span').html(json.user.username || '<span class="cr-ddd">未填写</span>');
        $popup.find('.user-info .user-base .nickname span').html(json.user.nickname || '<span class="cr-ddd">未填写</span>');
        $popup.find('.user-info .user-base .mobile span').html(json.user.mobile || '<span class="cr-ddd">未填写</span>');
        $popup.find('.user-info .user-base .email span').html(json.user.email || '<span class="cr-ddd">未填写</span>');

        $popup.find('.goods-info .base a').attr('href', json.goods.goods_url || 'javascript:;');
        $popup.find('.goods-info .base img').attr('src', json.goods.images || $popup.find('.goods-info .base img').attr('src'));
        $popup.find('.goods-info .title').html(json.goods.title);
        $popup.find('.goods-info .price').html(__price_symbol__+json.goods.price);

        $popup.find('.content').html(json.content || '<span class="cr-ddd">没有评论内容</span>');
    });
});