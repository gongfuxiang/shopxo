$(function()
{
    // 分类显/隐
    $('.category-list ul.category-nav li').on('mouseover', function(){
        var index = $(this).index();
        $('.category-list ul.category-nav li').removeClass('active');
        $(this).addClass('active');
        $('.category-content').addClass('none');
        $('.category-content-'+index).removeClass('none');
    });
});