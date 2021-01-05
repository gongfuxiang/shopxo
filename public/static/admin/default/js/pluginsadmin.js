$(function()
{
    // 删除提示
    $('.submit-delete-modal').on('click', function()
    {
        var $modal = $('#plugins-delete-modal');
        $modal.find('button.submit-ajax').attr('data-id', $(this).data('id'));
        $modal.modal('open');
    });

    // 插件设置事件
    $('.plugins-set-event').on('click', function()
    {
        if($(this).parents('.am-gallery-item').hasClass('am-active'))
        {
            Prompt('请先点击勾勾启用');
        } else {
            window.location.href = $(this).data('set-url');
        }
    });

    // 拖拽
    $('.content ul.am-gallery-bordered').dragsort({ dragSelector: 'button.submit-move', placeHolderTemplate: '<li><div class="am-gallery-item drag-sort-dotted"></div></li>'});

    // 排序开启/取消/保存
    $('.submit-move-sort-open').on('click', function()
    {
        $('.submit-move-sort-open').addClass('am-hide');
        $('.submit-move-sort-save').removeClass('am-hide');
        $('.submit-move-sort-cancel').removeClass('am-hide');
        $('.content ul.am-gallery-bordered li .submit-move').removeClass('am-hide');
    });
    $('.submit-move-sort-cancel').on('click', function()
    {
        $('.submit-move-sort-open').removeClass('am-hide');
        $('.submit-move-sort-save').addClass('am-hide');
        $('.submit-move-sort-cancel').addClass('am-hide');
        $('.content ul.am-gallery-bordered li .submit-move').addClass('am-hide');
    });
    $('.submit-move-sort-save').on('click', function()
    {
        var json = {};
        $('.content ul.am-gallery-bordered li').each(function(k, v)
        {
            var id = parseInt($(this).data('id')) || 0;
            if(id > 0)
            {
                json[k] = id;
            }
        });
        var len = 0;
        for(var i in json)
        {
            len++;
        }
        if(len <= 0)
        {
            Prompt('没有可保存的插件数据');
            return false;
        }

        // ajax请求
        $.AMUI.progress.start();
        $.ajax({
            url: $('.content ul.am-gallery-bordered').data('sort-save-url'),
            type: 'POST',
            dataType: 'json',
            timeout: 10000,
            data: {"data": json},
            success: function(result)
            {
                $.AMUI.progress.done();
                if(result.code == 0)
                {
                    $('.submit-move-sort-open').removeClass('am-hide');
                    $('.submit-move-sort-save').addClass('am-hide');
                    $('.submit-move-sort-cancel').addClass('am-hide');
                    $('.content ul.am-gallery-bordered li .submit-move').addClass('am-hide');
                    Prompt(result.msg, 'success');
                } else {
                    Prompt(result.msg);
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
            }
        });
    });
});