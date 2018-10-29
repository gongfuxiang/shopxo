$(function()
{
    // 添加元素到右侧
    function RightElementAdd(value, name)
    {
        if($('ul.ul-right').find('.items-li-'+value).length == 0)
        {
            var html = '<li class="items-li-'+value+'"><span class="name" data-value="'+value+'">'+name+'</span><i class="am-icon-trash-o am-fr"></i></li>';
            $('ul.ul-right').append(html);
        }
    }
    // 左侧点击到右侧
    $('ul.ul-left i.am-icon-angle-right').on('click', function()
    {
        var value = $(this).prev().data('value');
        var name = $(this).prev().text();
        RightElementAdd(value, name);
        $(this).parent().remove();
        return false;
    });

    // 左侧全部移动到右侧
    $('.selected-all').on('click', function()
    {
        $('ul.ul-left li').each(function(k, v)
        {
            var value = $(this).find('span.name').data('value');
            var name = $(this).find('span.name').text();
            RightElementAdd(value, name);
            $(this).remove();
        });
    });

    // 右侧删除
    $('ul.ul-right i.am-icon-trash-o').on('click', function()
    {
        $(this).parent().remove();
        return false;
    });

});