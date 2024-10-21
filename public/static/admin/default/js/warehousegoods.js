$(function()
{
    // popup 容器   
    var $popup = $('#warehouse-goods-popup');

    // 分页
    $('.goods-page-container').html(PageLibrary());

    // 添加商品窗口
    $(document).on('click', '.add-goods-submit', function()
    {
        $popup.modal('open').on('closed.modal.amui', function()
        {
            // 关闭刷新页面
            window.location.reload();
        });
        $popup.find('.search-submit').trigger('click');
    });

    // 搜索商品
    $(document).on('click', '.forth-selection-container .search-submit, .pagelibrary li a', function()
    {
        // 分页处理
        var is_active = $(this).data('is-active') || 0;
        if(is_active == 1)
        {
            return false;
        }
        var page = $(this).data('page') || 1;

        // 请求参数
        var url = $('.forth-selection-container').data('search-url');
        var warehouse_id = $('.forth-selection-form-warehouse').val();
        var category_id = $('.forth-selection-form-category').val();
        var keywords = $('.forth-selection-form-keywords').val();
        if(warehouse_id <= 0)
        {
            Prompt(window['lang_warehouse_choice_tips'] || '请选择仓库');
            return false;
        }

        var $btn = $('.forth-selection-container .search-submit');
        $btn.button('loading');
        $.AMUI.progress.start();
        $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> '+($('.goods-list-container').data('loading-msg'))+'</div>');
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'post',
            data: {page: page, warehouse_id: warehouse_id, category_id: category_id, keywords: keywords},
            dataType: 'json',
            success:function(res)
            {
                $.AMUI.progress.done();
                $btn.button('reset');
                if(res.code == 0)
                {
                    $('.goods-list-container').attr('data-is-init', 0);
                    $('.goods-list-container ul.am-gallery').html(res.data.data);
                    $('.goods-page-container').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 4));
                } else {
                    Prompt(res.msg);
                    $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> '+res.msg+'</div>');
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                $btn.button('reset');
                var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
                Prompt(msg, null, 30);
                $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> '+msg+'</div>');
            }
        });
    });

    // 商品添加/删除
    $(document).on('click', '.goods-list-container .goods-add-submit, .goods-list-container .goods-del-submit', function()
    {
        var $this = $(this);
        var type = $this.data('type');
        var url = $('.goods-list-container').data(type+'-url');
        var icon_html = $this.parents('li').data((type == 'add' ? 'del' : 'add')+'-html');
        var warehouse_id = parseInt($('.forth-selection-form-warehouse').val()) || 0;
        var goods_id = $this.parents('li').data('gid');
        if(warehouse_id <= 0)
        {
            Prompt(window['lang_warehouse_choice_tips'] || '请选择仓库');
            return false;
        }

        $.AMUI.progress.start();
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'post',
            data: {"warehouse_id":warehouse_id, "goods_id":goods_id},
            dataType: 'json',
            success: function(res)
            {
                $.AMUI.progress.done();
                if(res.code == 0)
                {
                    $this.parent().html(icon_html);
                    Prompt(res.msg, 'success');
                } else {
                    Prompt(res.msg);
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });
});