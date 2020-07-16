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
            Prompt('请选择仓库');
            return false;
        }

        var $this = $(this);
        $this.button('loading');
        $('.goods-list-container').html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> '+($('.goods-list-container').data('loading-msg'))+'</div>');
        $.ajax({
            url: url,
            type: 'post',
            data: {"page":page, "warehouse_id":warehouse_id, "category_id":category_id, "keywords":keywords},
            dataType: 'json',
            success:function(res)
            {
                $this.button('reset');
                if(res.code == 0)
                {
                    $('.goods-list-container').attr('data-is-init', 0);
                    $('.goods-list-container').html(res.data.data);
                    $('.goods-page-container').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 4));
                } else {
                    Prompt(res.msg);
                    $('.goods-list-container').html('<div class="table-no"><i class="am-icon-warning"></i> '+res.msg+'</div>');
                }
            },
            error:function(res)
            {
                $this.button('reset');
                Prompt('请求失败');
                $('.goods-list-container').html('<div class="table-no"><i class="am-icon-warning"></i> 请求失败</div>');
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
            Prompt('请选择仓库');
            return false;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: {"warehouse_id":warehouse_id, "goods_id":goods_id},
            dataType: 'json',
            success:function(res)
            {
                if(res.code == 0)
                {
                    $this.parent().html(icon_html);
                    Prompt(res.msg, 'success');
                } else {
                    Prompt(res.msg);
                }
            },
            error:function(res)
            {
                Prompt('请求失败');
            }
        });
    });


});