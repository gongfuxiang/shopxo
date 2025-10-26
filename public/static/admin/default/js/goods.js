$(function () {
    // 表单初始化
    FromInit('form.form-validation-specifications-extends');

    // 规格高级批量操作-赋值
    var $spec_popup_all_operation = $('#spec-popup-all-operation');
    $spec_popup_all_operation.find('button.spec-all-submit').on('click', function () {
        // 获取规格值条件
        var data = [];
        $spec_popup_all_operation.find('.am-popup-bd .spec-title-container select.chosen-select').each(function (k, v) {
            data.push($(this).val() || null);
        });

        // 获取基础值
        var price = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_price').val() || '';
        var original_price = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_original_price').val() || '';
        var buy_min_number = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_buy_min_number').val() || '';
        var buy_max_number = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_buy_max_number').val() || '';
        var weight = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_weight').val() || '';
        var volume = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_volume').val() || '';
        var coding = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_coding').val() || '';
        var barcode = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_barcode').val() || '';
        var inventory_unit = $spec_popup_all_operation.find('.am-popup-bd input.popup_all_inventory_unit').val() || '';

        // 批量设置
        var data_length = data.length;
        $('.specifications-table tbody tr').each(function (k, v) {
            var count = 0;
            for (var i in data) {
                if (data[i] == null || data[i] == ($(this).find('td').eq(i).find('input').val() || null)) {
                    count++;
                }
            }
            var index = $(this).find('.value-start').index();
            if (count >= data_length) {
                $(this).find('td').eq(index).find('input').val(price);
                $(this).find('td').eq(index + 1).find('input').val(original_price);
                $(this).find('td').eq(index + 2).find('input').val(buy_min_number);
                $(this).find('td').eq(index + 3).find('input').val(buy_max_number);
                $(this).find('td').eq(index + 4).find('input').val(weight);
                $(this).find('td').eq(index + 5).find('input').val(volume);
                $(this).find('td').eq(index + 6).find('input').val(coding);
                $(this).find('td').eq(index + 7).find('input').val(barcode);
                $(this).find('td').eq(index + 8).find('input').val(inventory_unit);
            }
        });
        $spec_popup_all_operation.modal('close');
    });


    // 手机详情添加
    $(document).on('click', '.content-app-items-add-sub', function () {
        var $app_content = $('#goods-nav-app .content-app-items');
        var i = (($(this).attr('index') || null) == null) ? parseInt($app_content.find('li').length) : parseInt($(this).attr('index'));
        var index = parseInt(Math.random() * 1000001) + i;
        var images_name = $app_content.data('images-name');
        var content_name = $app_content.data('content-name');
        // html拼接
        var html = '<li><div class="am-flex am-flex-warp am-gap-32">';
        // 左侧
        html += '<div class="am-form-group am-form-file content-app-left am-padding-bottom-0">';
        html += '<label class="block">' + $app_content.data('images-title') + '</label>';
        html += '<div class="am-form-file-upload-container">';
        html += '<ul class="plug-file-upload-view goods-content-app-images-view-' + index + '" data-form-name="' + images_name + '_' + index + '" data-max-number="1" data-dialog-type="images" data-is-eye="1">';
        html += '<li class="plug-file-upload-submit" data-view-tag="ul.goods-content-app-images-view-' + index + '">';
        html += '<i class="iconfont icon-add"></i>';
        html += '</li>';
        html += '</ul>';
        html += '</div>';
        html += '</div>';

        // 右侧
        html += '<div class="am-form-group content-app-right am-padding-bottom-0">';
        html += '<label>' + $app_content.data('content-title') + '</label>';
        html += '<textarea rows="3" name="' + content_name + '_' + index + '" class="am-radius" placeholder="' + $app_content.data('content-title') + '"></textarea>';
        html += '</div>';
        html += '</div>';

        // 操作按钮
        html += '<div class="am-flex am-flex-items-center am-gap-32 am-margin-top-sm">';
        html += '<a href="javascript:;" class="am-text-xs am-text-danger content-app-items-rem-sub am-flex am-flex-items-center"><i class="iconfont icon-delete"></i> ' + $app_content.data('delete-title') + '</a>';
        html += ' <a href="javascript:;" class="am-text-xs drag-sort-submit am-flex am-flex-items-center"><i class="iconfont icon-sort"></i> ' + $app_content.data('drag-title') + '</a>';
        html += '</div>';
        html += '</li>';
        $app_content.append(html);
        $app_content.attr('index', index);
        $(this).attr('index', i + 1);
    });

    // 手机详情删除
    $(document).on('click', '.content-app-items-rem-sub', function () {
        $(this).parent().parent().remove();
    });

    // 拖拽
    $('ul.goods-photo-view').dragsort({ dragSelector: 'li', placeHolderTemplate: '<li class="drag-sort-dotted"></li>' });
    $('ul.content-app-items').dragsort({ dragSelector: 'a.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>' });

    // 虚拟商品编辑器初始化
    if ($('#goods-fictitious-container').length > 0) {
        UE.getEditor('goods-fictitious-container', {
            toolbars: [['source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', '|', 'forecolor', 'backcolor', 'link', 'fontsize', 'insertorderedlist', 'insertunorderedlist', '|', 'simpleupload', 'insertimage', 'insertvideo', 'attachment']],
            initialFrameHeight: 200
        });
    }


    // 确认下一步
    $(document).on('click', '.confirm-next-submit', function()
    {
        // 参数数据
        var json = $('.goods-category-choice .already-select-tips').attr('data-value') || null;
        if(json != null)
        {
            json = JSON.parse(decodeURIComponent(json)) || null;
        }
        if(json == null)
        {
            Prompt($('.system-goods-category-choice').data('please-choice-goods-category-tips') || '请先选择商品分类');
            return false;
        }

        // 是否完全选择
        var len = json.length;
        if(len < 3)
        {
            if($('.goods-category-select-'+(len+1)+' li').length > 0)
            {
                Prompt($('.system-goods-category-choice').data('please-choice-complete-goods-category-level-tips') || '请选择完整商品分类层级');
                return false;
            }
        }

        // 数据赋值
        var text = '';
        var value = [];
        for(var i in json)
        {
            if(i > 0)
            {
                text += ' > ';
            }
            text += json[i]['name'];
            value.push(json[i]['id']);
        };
        value = value.join(',');

        var category_id = json[len-1]['id'];
        $('.goods-category-form-again-choice').attr('data-value', value);
        $('.goods-category-form-content .text-tips').text(text);
        $('.goods-category-form-content input[name="category_id"]').val(category_id);
        $('.goods-category-choice').addClass('am-hide');
        $('form.form-validation').removeClass('am-hide');

        // 规格基础模板生成
        GoodsSpecBaseTemplateCreated(category_id, parseInt($('.content-right').attr('data-is-goods-single-category-mode') || 0));
    });

    // 商品分类重新选择
    $(document).on('click', '.goods-category-form-again-choice', function()
    {
        // 选中数据
        var value = $(this).attr('data-value') || null;
        if(value != null)
        {
            value = value.split(',');
            for(var i in value)
            {
                var $gcs = $('.goods-category-select-'+(parseInt(i)+1));
                if($gcs.length > 0 && $gcs.find('li').length > 0)
                {
                    $gcs.find('li').each(function(k, v)
                    {
                        if($(this).find('a').data('value') == value[i])
                        {
                            $(this).find('a').trigger('click');
                        }
                    });
                }
            }
        }

        // 容器显隐
        $('.goods-category-choice').removeClass('am-hide');
        $('form.form-validation').addClass('am-hide');
    });
});