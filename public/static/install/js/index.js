$(function()
{
    // 环境检查
    $('.check-submit').on('click', function()
    {
        var count = $('.check table tr').length-3;
        if($('.check .yes').length < count)
        {
            Prompt('您的配置或权限不符合要求');
        } else {
            location.href = $(this).data('url');
        }
    });

});