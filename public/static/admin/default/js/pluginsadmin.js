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
    // 分类筛选
    var $list = $('.plugins-data-list ul.already-install');
    var cid = parseInt($('.plugins-category-nav button.am-btn-secondary').data('value') || 0);
    if(cid == 0)
    {
        $list.find('>li').removeClass('am-hide');
    } else {
        $list.find('>li').addClass('am-hide');
        $list.find('>li.plugins-category-'+cid).removeClass('am-hide');
    }

    // 关键字筛选
    var keywords = $('.plugins-search input').val().trim() || null;
    if(keywords != null)
    {
        $list.find('>li').each(function(k, v)
        {
            if(!$(this).hasClass('am-hide'))
            {
                var name = $(this).find('.base .name').text();
                var desc = $(this).find('.desc').text();
                if(name.indexOf(keywords) != -1 || desc.indexOf(keywords) != -1)
                {
                    $(this).removeClass('am-hide');
                } else {
                    $(this).addClass('am-hide');
                }
            }
        });
    }

    // 空则显示提示
    if($list.find('>li:not(.am-hide)').length > 0)
    {
        $('.not-data-tips').addClass('am-hide');
    } else {
        $('.not-data-tips').removeClass('am-hide');
    }
}

$(function()
{
    // 删除提示
    $(document).on('click', '.submit-delete-modal', function()
    {
        var $modal = $('#plugins-delete-modal');
        $modal.find('button.submit-ajax').attr('data-id', $(this).data('id'));
        $modal.modal('open');
    });

    // 插件设置事件
    $(document).on('click', '.plugins-set-event', function()
    {
        if($(this).parents('.item').hasClass('am-active'))
        {
            Prompt(window['lang_not_enable_tips'] || '请先点击勾勾启用');
        } else {
            window.parent.AdminTopNavIframeAddHandle($(this).data('set-url'), $(this).data('name'), $(this).data('key'), 'nnav', true);
        }
    });

    // 拖拽
    $('.plugins-data-list ul').dragsort({ dragSelector: 'button.submit-move', placeHolderTemplate: '<li><div class="item drag-sort-dotted"></div></li>'});

    // 排序开启/取消/保存
    $(document).on('click', '.submit-move-setup-open', function()
    {
        $('.submit-move-setup-open').addClass('am-hide');
        $('.submit-move-setup-save').removeClass('am-hide');
        $('.submit-move-setup-cancel').removeClass('am-hide');
        $('.plugins-data-list > ul > li .submit-move').removeClass('am-hide');
        $('.plugins-data-list > ul > li .plugins-right-top-container').removeClass('am-hide');
    });
    $(document).on('click', '.submit-move-setup-cancel', function()
    {
        $('.submit-move-setup-open').removeClass('am-hide');
        $('.submit-move-setup-save').addClass('am-hide');
        $('.submit-move-setup-cancel').addClass('am-hide');
        $('.plugins-data-list > ul > li .submit-move').addClass('am-hide');
        $('.plugins-data-list > ul > li .plugins-right-top-container').addClass('am-hide');
    });
    $(document).on('click', '.submit-move-setup-save', function()
    {
        var json = {};
        $('.plugins-data-list > ul > li').each(function(k, v)
        {
            var id = parseInt($(this).data('id')) || 0;
            if(id > 0)
            {
                var $right = $(this).find('.plugins-right-top-container');
                json[k] = {
                    sort: k,
                    id: id,
                    category_id: $right.find('select[name="plugins_category_id"]').val() || 0,
                    menu_control: $right.find('select[name="plugins_menu_control"]').val() || '',
                };
            }
        });
        var len = 0;
        for(var i in json)
        {
            len++;
        }
        if(len <= 0)
        {
            Prompt(window['lang_save_no_data_tips'] || '没有可保存的插件数据');
            return false;
        }

        // ajax请求
        $.AMUI.progress.start();
        $.ajax({
            url: RequestUrlHandle($('.plugins-data-list ul').data('setup-save-url')),
            type: 'POST',
            dataType: 'json',
            timeout: 10000,
            data: {"data": json},
            success: function(result)
            {
                $.AMUI.progress.done();
                if(result.code == 0)
                {
                    $('.submit-move-setup-open').removeClass('am-hide');
                    $('.submit-move-setup-save').addClass('am-hide');
                    $('.submit-move-setup-cancel').addClass('am-hide');
                    $('.plugins-data-list > ul > li .submit-move').addClass('am-hide');
                    $('.plugins-data-list > ul > li .plugins-right-top-container').addClass('am-hide');
                    Prompt(result.msg, 'success');
                    setTimeout(function()
                    {
                        window.location.reload();
                    }, 1500);
                } else {
                    Prompt(result.msg);
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });

    // 搜索
    $(document).on('click', '.plugins-search button', function()
    {
        PluginsSearch();
    });
    // 输入回车搜索
    $(document).on('keydown', '.plugins-search input', function(e)
    {
        if(e.keyCode == 13)
        {
            PluginsSearch();
            e.preventDefault();
        }
    });

    // 分类筛选
    $(document).on('click', '.plugins-category-nav button', function()
    {
        // 分类样式
        $('.plugins-category-nav button').removeClass('am-btn-secondary').addClass('am-btn-default');
        $(this).addClass('am-btn-secondary');

        // 搜索
        PluginsSearch();
    });
});