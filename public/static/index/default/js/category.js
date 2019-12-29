$(function()
{
    // 一级分类显/隐操作
    $('.category-list-container ul.category-nav li').on('mouseover', function()
    {
        var index = $(this).index();
        $('.category-list-container ul.category-nav li').removeClass('active');
        $(this).addClass('active');
        $('.category-list-container .category-content').addClass('none');
        $('.category-list-container .category-content-'+index).removeClass('none');
    });

    // 一级分类双击进入商品搜索页
    $('.category-list-container ul.category-nav li').on('dblclick', function()
    {
        var url = $(this).data('url') || null;
        if(url != null)
        {
            window.location.href = url;
        }
    });
});