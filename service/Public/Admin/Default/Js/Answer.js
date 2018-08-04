$(function()
{
    // 处理
    $('.submit-reply').on('click', function()
    {
        var json = $(this).data('json');
        $('#my-popup-reply input[name="id"]').val(json.id);
        $('.reply-name').html(json.name || '<span class="cr-ddd">未填写</span>');
        $('.reply-tel').html(json.tel || '<span class="cr-ddd">未填写</span>');
    });
    $('#my-popup-reply button[type="submit"]').on('click', function()
    {
        $('#my-popup-reply textarea[name="reply"]').attr('required', true);
        $('#my-popup-reply textarea[name="reply"]').blur();
        $('#my-popup-reply input[name="status"]').val(status);
    });
});