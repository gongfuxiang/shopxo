$(function()
{
    // 添加元素到右侧
    function RightElementAdd(value, name)
    {
        if($('.forth-selection-container ul.ul-right').find('.items-li-'+value).length == 0)
        {
            var html = '<li class="am-animation-slide-bottom items-li-'+value+'"><span class="name" data-value="'+value+'">'+name+'</span><i class="am-icon-trash-o am-fr"></i></li>';
            $('.forth-selection-container ul.ul-right').append(html);
        }

        // 右侧数据同步
        RightElementGoods();

        // 左侧是否还有内容
        if($('.forth-selection-container ul.ul-left li').length == 0)
        {
            $('.forth-selection-container ul.ul-left .table-no').removeClass('none');
        } else {
            $('.forth-selection-container ul.ul-left .table-no').addClass('none');
        }
    }

    // 批量-商品id同步
    function RightElementGoods()
    {
        var value_all = [];
        $('.forth-selection-container ul.ul-right li').each(function(k, v)
        {
            value_all[k] = $(this).find('span.name').data('value');
        });
        $('.forth-selection-container input[name="category_ids"]').val(value_all.join(',')).blur();

        // 右侧是否还有数据
        if($('.forth-selection-container ul.ul-right li').length == 0)
        {
            $('.forth-selection-container ul.ul-right .table-no').removeClass('none');
        } else {
            $('.forth-selection-container ul.ul-right .table-no').addClass('none');
        }
    }
    // 左侧点击到右侧
    $('.forth-selection-container ul.ul-left').on('click', 'i.am-icon-angle-right', function()
    {
        var value = $(this).prev().data('value');
        var name = $(this).prev().text();
        $(this).parent().remove();
        RightElementAdd(value, name);
    });

    // 左侧全部移动到右侧
    $('.forth-selection-container .selected-all').on('click', function()
    {
        $('.forth-selection-container ul.ul-left li').each(function(k, v)
        {
            var value = $(this).find('span.name').data('value');
            var name = $(this).find('span.name').text();
            $(this).remove();
            RightElementAdd(value, name);
        });
    });

    // 右侧删除
    $('.forth-selection-container ul.ul-right').on('click', 'i.am-icon-trash-o', function()
    {
        $(this).parent().remove();
        RightElementGoods();
    });

    // 商品搜索
    $('.forth-selection-form .search-submit').on('click', function()
    {
        var category_id = $('.forth-selection-form .forth-selection-form-category').val();
        var keywords = $('.forth-selection-form .forth-selection-form-keywords').val();
        console.log(category_id, keywords)

        // ajax请求
        $.ajax({
            url:$('.forth-selection-form').data('search-url'),
            type:'POST',
            dataType:"json",
            timeout:10000,
            data:{"category_id": category_id, "keywords": keywords},
            success:function(result)
            {
                if(result.code == 0)
                {
                    var html = '';
                    for(var i in result.data)
                    {
                        html += '<li class="am-animation-slide-bottom"><span class="name" data-value="'+result['data'][i]['id']+'">'+result['data'][i]['title']+'</span><i class="am-icon-angle-right am-fr"></i></li>';
                    }
                    $('ul.ul-left .table-no').addClass('none');
                    $('ul.ul-left li').remove();
                    $('ul.ul-left').append(html);
                } else {
                    Prompt(result.msg);
                }
            },
            error:function()
            {
                Prompt('网络异常错误');
            }
        });
    });

});