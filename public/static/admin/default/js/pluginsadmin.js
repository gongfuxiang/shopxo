/**
 * 插件搜索
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-07-09
 * @desc    description
 */
function PluginsSearch()
{
    var keywords = $('.plugins-search input').val().trim() || null;
    if(keywords != null)
    {
        var count = 0;
        $('.plugins-data-list ul li').each(function(k, v)
        {
            var name = $(this).find('.base .name').text();
            var desc = $(this).find('.desc').text();
            if(name.indexOf(keywords) != -1 || desc.indexOf(keywords) != -1)
            {
                $(this).show();
                count++;
            } else {
                $(this).hide();
            }
        });
        if(count == 0)
        {
            $('.not-data-tips').removeClass('none');
        } else {
            $('.not-data-tips').addClass('none');
        }
    } else {
        $('.plugins-data-list ul li').show();
        $('.not-data-tips').addClass('none');
    }
}

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
    $('.plugins-data-list ul').dragsort({ dragSelector: 'button.submit-move', placeHolderTemplate: '<li><div class="item drag-sort-dotted"></div></li>'});

    // 排序开启/取消/保存
    $('.submit-move-sort-open').on('click', function()
    {
        $('.submit-move-sort-open').addClass('am-hide');
        $('.submit-move-sort-save').removeClass('am-hide');
        $('.submit-move-sort-cancel').removeClass('am-hide');
        $('.plugins-data-list ul li .submit-move').removeClass('am-hide');
    });
    $('.submit-move-sort-cancel').on('click', function()
    {
        $('.submit-move-sort-open').removeClass('am-hide');
        $('.submit-move-sort-save').addClass('am-hide');
        $('.submit-move-sort-cancel').addClass('am-hide');
        $('.plugins-data-list ul li .submit-move').addClass('am-hide');
    });
    $('.submit-move-sort-save').on('click', function()
    {
        var json = {};
        $('.plugins-data-list ul li').each(function(k, v)
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
            url: RequestUrlHandle($('.plugins-data-list ul').data('sort-save-url')),
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
                    $('.plugins-data-list ul li .submit-move').addClass('am-hide');
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

    // 搜索
    $('.plugins-search button').on('click', function()
    {
        PluginsSearch();
    });
    // 输入回车搜索
    $('.plugins-search input').on('keydown', function(e)
    {
        if(e.keyCode == 13)
        {
            PluginsSearch();
            e.preventDefault();
        }
    });
});