/**
 * 手机端配置 / 页面DIY
 */
$(function()
{
    // 商品分类层级切换预览
    $(document).on('click', '.goods-category-level-box .level-type input', function () {
        var key = $(this).val();
        var $box = $(this).closest('.goods-category-level-box');
        $box.find(".level-type-content .item[data-key='" + key + "']").addClass('am-active').siblings('.item').removeClass('am-active');
        var $style = $box.find('.goods-category-style-box');
        if($style.length > 0)
        {
            if(parseInt(key) === 0)
            {
                $style.removeClass('am-hide');
                var style_val = $style.find('.goods-category-style-type input:checked').val() || 1;
                GoodsCategoryStylePreviewSync($box, style_val);
            } else {
                $style.addClass('am-hide');
            }
        }
    });
    // 分类+商品风格预览联动
    $(document).on('click', '.goods-category-level-box .goods-category-style-type input', function () {
        GoodsCategoryStylePreviewSync($(this).closest('.goods-category-level-box'), $(this).val());
    });

    // 品类 DIY：点新增打开时清空编辑态
    $(document).on('click', '.goods-detail-category-diy-open', function()
    {
        GoodsDetailCategoryDiyResetModal();
    });

    // 品类 DIY：删除行
    $(document).on('click', '.goods-detail-category-diy-remove', function()
    {
        var $box = $('#goods-detail-category-diy-box');
        if($box.length == 0)
        {
            return;
        }
        $(this).closest('tr').remove();
        GoodsDetailCategoryDiySyncEmpty($box);
    });

    // 品类 DIY：编辑行
    $(document).on('click', '.goods-detail-category-diy-edit', function()
    {
        var $box = $('#goods-detail-category-diy-box');
        var $popup = $('#goods-detail-category-diy-popup');
        var $tr = $(this).closest('tr');
        if($box.length == 0 || $popup.length == 0 || $tr.length == 0)
        {
            return;
        }
        GoodsDetailCategoryDiyResetModal();
        var category_id = String($tr.data('category-id') || '');
        var $cat = $popup.find('#goods-detail-category-diy-category');
        $cat.val(category_id).trigger('chosen:updated');
        $popup.find('.goods-detail-category-diy-client').each(function()
        {
            var $sel = $(this);
            var client = String($sel.data('client') || '');
            var diy_id = $tr.find('.goods-detail-category-diy-fields input[data-client="'+client+'"][data-field="diy_id"]').val() || '';
            var time_start = $tr.find('.goods-detail-category-diy-fields input[data-client="'+client+'"][data-field="time_start"]').val() || '';
            var time_end = $tr.find('.goods-detail-category-diy-fields input[data-client="'+client+'"][data-field="time_end"]').val() || '';
            $sel.val(diy_id).trigger('chosen:updated');
            var $row = $sel.closest('.am-flex');
            $row.find('.goods-detail-category-diy-time-start').val(time_start);
            $row.find('.goods-detail-category-diy-time-end').val(time_end);
        });
        $popup.attr('data-edit-category-id', category_id);
        $popup.modal();
    });

    // 品类 DIY：弹窗关闭后重置
    $(document).on('closed.modal.amui', '#goods-detail-category-diy-popup', function()
    {
        GoodsDetailCategoryDiyResetModal();
    });

    // 品类 DIY：确认写入列表（不提交外层保存表单）
    $(document).on('click', '.goods-detail-category-diy-confirm', function()
    {
        var $box = $('#goods-detail-category-diy-box');
        var $popup = $('#goods-detail-category-diy-popup');
        if($box.length == 0 || $popup.length == 0)
        {
            return;
        }
        var $list = $box.find('.goods-detail-category-diy-list');
        var $cat = $popup.find('#goods-detail-category-diy-category');
        var category_id = parseInt($cat.val() || 0);
        if(category_id <= 0)
        {
            Prompt($box.data('choice-tips') || '请选择商品分类');
            return;
        }
        var category_name = $cat.find('option:selected').attr('data-name') || $cat.find('option:selected').text();
        var client_text = [];
        var fields_html = '';
        var has_diy = false;
        $popup.find('.goods-detail-category-diy-client').each(function()
        {
            var $sel = $(this);
            var client = $sel.data('client');
            var client_name = $sel.data('client-name');
            var diy_id = $sel.val() || '';
            var diy_name = $sel.find('option:selected').text();
            var $row = $sel.closest('.am-flex');
            var time_start = $row.find('.goods-detail-category-diy-time-start').val() || '';
            var time_end = $row.find('.goods-detail-category-diy-time-end').val() || '';
            if(diy_id != '')
            {
                has_diy = true;
                client_text.push(client_name + '：' + diy_name);
                fields_html += '<input type="hidden" name="common_app_goods_detail_category_diy_mode['+category_id+']['+client+'][diy_id]" value="'+diy_id+'" data-client="'+client+'" data-field="diy_id" />';
                fields_html += '<input type="hidden" name="common_app_goods_detail_category_diy_mode['+category_id+']['+client+'][time_start]" value="'+time_start+'" data-client="'+client+'" data-field="time_start" />';
                fields_html += '<input type="hidden" name="common_app_goods_detail_category_diy_mode['+category_id+']['+client+'][time_end]" value="'+time_end+'" data-client="'+client+'" data-field="time_end" />';
            }
        });
        if(!has_diy)
        {
            Prompt($box.data('diy-tips') || '请至少选择一个终端的DIY模板');
            return;
        }
        var edit_category_id = String($popup.attr('data-edit-category-id') || '');
        var $exist = $list.find('tr[data-category-id="'+category_id+'"]');
        // 编辑同一品类直接覆盖；换成其他已存在品类则提示
        if($exist.length > 0 && edit_category_id !== String(category_id))
        {
            if(!window.confirm($box.data('exist-tips') || '该品类已配置，确认将覆盖原设置'))
            {
                return;
            }
        }
        if($exist.length > 0)
        {
            $exist.remove();
        }
        // 编辑时若改了品类，删掉原行
        if(edit_category_id !== '' && edit_category_id !== String(category_id))
        {
            $list.find('tr[data-category-id="'+edit_category_id+'"]').remove();
        }
        $list.find('.goods-detail-category-diy-empty').remove();
        var delete_title = $box.data('delete-title') || '删除';
        var edit_title = $box.data('edit-title') || '编辑';
        var tr = '<tr data-category-id="'+category_id+'">';
        tr += '<td class="category-name">'+category_name+'</td>';
        tr += '<td class="client-text">'+client_text.join('；')+'</td>';
        tr += '<td class="am-text-center">';
        tr += '<a href="javascript:;" class="am-color-blue goods-detail-category-diy-edit">'+edit_title+'</a>';
        tr += '<a href="javascript:;" class="am-color-red am-margin-left-sm goods-detail-category-diy-remove">'+delete_title+'</a>';
        tr += '<div class="goods-detail-category-diy-fields">'+fields_html+'</div>';
        tr += '</td></tr>';
        $list.append(tr);
        $popup.modal('close');
    });
});

/**
 * 列表空状态
 */
function GoodsDetailCategoryDiySyncEmpty($box)
{
    var $list = $box.find('.goods-detail-category-diy-list');
    var has_row = $list.find('tr[data-category-id]').length > 0;
    $list.find('.goods-detail-category-diy-empty').remove();
    if(!has_row)
    {
        $list.append('<tr class="goods-detail-category-diy-empty"><td colspan="3" class="am-text-center am-color-grey">'+$box.data('empty-tips')+'</td></tr>');
    }
}

/**
 * 分类+商品风格预览同步
 */
function GoodsCategoryStylePreviewSync($box, style_val)
{
    if($box.length == 0)
    {
        return;
    }
    var $list = $box.find('.level-type-content .item[data-key="0"] .goods-category-style-preview-list');
    if($list.length == 0)
    {
        return;
    }
    $list.find('.goods-category-style-preview').addClass('am-hide');
    $list.find('.goods-category-style-preview[data-style="' + style_val + '"]').removeClass('am-hide');
}

/**
 * 重置品类设置弹窗
 */
function GoodsDetailCategoryDiyResetModal()
{
    var $popup = $('#goods-detail-category-diy-popup');
    if($popup.length == 0)
    {
        return;
    }
    $popup.removeAttr('data-edit-category-id');
    var $cat = $popup.find('#goods-detail-category-diy-category');
    $cat.val('').trigger('chosen:updated');
    $popup.find('.goods-detail-category-diy-client').each(function()
    {
        $(this).val('').trigger('chosen:updated');
    });
    $popup.find('.goods-detail-category-diy-time-start, .goods-detail-category-diy-time-end').val('');
}
