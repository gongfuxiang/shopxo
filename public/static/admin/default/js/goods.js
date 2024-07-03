// 规格容器
var $spec_table = $('table.specifications-table');

/**
 * 笛卡尔积生成规格
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-09-22T00:33:48+0800
 * @desc     description
 * @param    {[array]}                 arr1 [要进行笛卡尔积的二维数组]
 * @param    {[array]}                 arr2 [最终实现的笛卡尔积组合,可不写]
 */
function SpecCartesian (arr1, arr2) {
    // 去除第一个元素
    var result = [];
    var temp_arr = arr1;
    var first = temp_arr.splice(0, 1);

    if ((arr2 || null) == null) {
        arr2 = [];
    }

    // 判断是否是第一次进行拼接
    if (arr2.length > 0) {
        for (var i in arr2) {
            for (var k in first[0].value) {
                result.push(arr2[i] + ',' + first[0].value[k]);
            }
        }
    } else {
        for (var i in first[0].value) {
            result.push(first[0].value[i]);
        }
    }

    // 递归进行拼接
    if (arr1.length > 0) {
        result = SpecCartesian(arr1, result);
    }

    // 返回最终笛卡尔积
    return result;
}

$(function () {
    // 表单初始化
    FromInit('form.form-validation-specifications-extends');

    // 商品导航
    $(document).on('click', '.goods-nav li a', function () {
        // 样式
        $('.goods-nav li a').removeClass('goods-nav-active');
        $(this).addClass('goods-nav-active');

        // 滚动
        $(window).smoothScroll({ position: $($(this).data('value')).offset().top });
    });

    // 商品导航收缩
    $(document).on('click', '.goods-nav li.nav-shrink-submit', function () {
        if ($(this).find('i').hasClass('am-icon-angle-double-right')) {
            $(this).find('i').removeClass('am-icon-angle-double-right');
            $(this).find('i').addClass('am-icon-angle-double-left');
            $(this).parents('.goods-nav').addClass('goods-nav-retract');
            $('.goods-nav-retract').animate({ right: '-110px' }, 500, function () {
                $('.goods-nav-retract li.nav-shrink-submit').animate({ width: '50px', left: '-80px' });
            });

        } else {
            $(this).find('i').removeClass('am-icon-angle-double-left');
            $(this).find('i').addClass('am-icon-angle-double-right');
            $(this).parents('.goods-nav').removeClass('goods-nav-retract');
            $('.goods-nav').animate({ right: '-0px' });
            $('.goods-nav li.nav-shrink-submit').animate({ width: '100%', left: '0px' });
        }
    });

    // 规格列添加
    $(document).on('click', '.specifications-nav-title-add', function () {
        var spec_max = $spec_table.data('spec-add-max-number') || 3;
        if ($('.specifications-table th.table-title').length >= spec_max) {
            Prompt($spec_table.data('spec-max-error'));
            return false;
        }

        // title
        var index = parseInt(Math.random() * 1000001);
        html = '<th class="table-title table-title-' + index + '">';
        html += '<div class="am-flex am-am-flex-items-center am-gap-1">';
        html += '<input type="text" name="specifications_name_' + index + '" placeholder="' + $spec_table.data('spec-type-name') + '" class="am-radius" data-validation-message="' + $spec_table.data('spec-type-message') + '" data-is-clearout="0" required />';
        html += '<i class="am-close title-nav-remove iconfont icon-delete am-text-red" data-index="' + index + '"></i>';
        html += '</div>';
        html += '</th>';
        $('.title-start').before(html);

        // value
        html = '<td class="table-value table-value-' + index + '">';
        html += '<input type="text" name="specifications_value_' + index + '[]" placeholder="' + $spec_table.data('spec-value-name') + '" class="am-radius" data-validation-message="' + $spec_table.data('spec-value-message') + '" required />';
        html += '</td>';
        $('.value-start').before(html);
    });

    // 规格列移除
    $(document).on('click', '.specifications-table .title-nav-remove', function () {
        var index = $(this).data('index');
        $('.table-title-' + index).remove();
        $('.table-value-' + index).remove();

        if ($('.specifications-table th.table-title').length <= 0) {
            // 防止用户操作删除了第一条数据、首行移除指定class
            ($('.specifications-table tr.line-not-first').length >= $('.specifications-table tr').length)
            {
                $spec_table.find('tbody tr:first').removeClass('line-not-first');
            }

            // 移除多余的规格行
            $('.specifications-table tr.line-not-first').remove();
        }
    });

    // 添加一行规格值
    $(document).on('click', '.specifications-line-add', function () {
        if ($('.specifications-table th.table-title').length <= 0) {
            Prompt($spec_table.data('spec-empty-data-tips') || '请先添加规格');
            return false;
        }

        var index = parseInt(Math.random() * 1000001);
        var html = $spec_table.find('tbody tr:last').prop('outerHTML');
        $spec_table.append(html);
        $spec_table.find('tbody tr:last').attr('class', 'line-' + index + ' line-not-first');
        $spec_table.find('tbody tr:last').attr('data-line-tag', '.line-' + index);

        // 值赋空
        $spec_table.find('tbody tr:last').find('input').each(function (k, v) {
            $(this).attr('value', '');
        });
    });

    // 规格行复制
    $(document).on('click', '.specifications-table .line-copy', function () {
        // 是否存在规格名称
        if ($('.specifications-table th.table-title').length <= 0) {
            Prompt($spec_table.data('spec-empty-data-tips') || '请先添加规格');
            return false;
        }

        // 开始复制
        var index = parseInt(Math.random() * 1000001);
        var $parent = $(this).parents('tr');
        $parent.find('input').each(function (k, v) {
            $(this).attr('value', $(this).val());
        });
        $parent.after($parent.prop('outerHTML'));
        $parent.next().attr('class', 'line-' + index + ' line-not-first');
        $parent.next().attr('data-line-tag', '.line-' + index);
    });

    // 规格行移除
    $(document).on('click', '.specifications-table .line-remove', function () {
        // 不能全部移除，至少需要保留一行
        if ($('.specifications-table tbody tr').length <= 1) {
            Prompt($spec_table.data('spec-min-tips-message') || '至少需要保留一行规格值');
            return false;
        }

        // 移除操作
        $(this).parents('tr').remove();
    });

    // 添加规格图片
    $(document).on('click', '.specifications-line-images-add', function () {
        // 是否存在规格
        if ($('.specifications-table th.table-title').length <= 0) {
            Prompt($spec_table.data('spec-empty-data-tips' || '请先添加规格'));
            return false;
        }

        // 开始添加
        var index = parseInt(Math.random() * 1000001);
        var temp_class = 'spec-images-items-' + index;
        var html = '<li class="spec-images-items ' + temp_class + '">';
        html += '<input type="text" name="spec_images_name[' + index + ']" placeholder="' + $spec_table.data('spec-type-name') + '" class="am-radius am-text-center" data-validation-message="' + $spec_table.data('spec-type-message') + '" data-is-clearout="0" required />'
        html += '<ul class="plug-file-upload-view spec-images-view-' + index + '" data-form-name="spec_images[' + index + ']" data-max-number="1" data-dialog-type="images">';
        html += '<li>';
        html += '<input type="text" name="spec_images[' + index + ']" data-validation-message="' + $spec_table.data('spec-images-message') + '" required />';
        html += '<img src="' + __attachment_host__ + '/static/common/images/default-images.jpg" />';
        html += '<i class="iconfont icon-close"></i>';
        html += '</li>';
        html += '</ul>';
        html += '<div class="plug-file-upload-submit" data-view-tag="ul.spec-images-view-' + index + '">+ ' + $spec_table.data('spec-images-name') + '</div>';
        html += '</li>';
        $('.spec-images-list ul.spec-images-content').append(html);
    });

    // 规格图片删除
    $(document).on('click', '.spec-images-list ul.spec-images-content ul.plug-file-upload-view li i', function () {
        $(this).parents('li.spec-images-items').remove();
    });

    // 规格图片自动添加
    $(document).on('click', '.specifications-line-images-auto-add', function () {
        // 是否存在规格
        var spec_count = $('.specifications-table th.table-title').length || 0;
        if (spec_count <= 0) {
            Prompt($spec_table.data('spec-empty-data-tips') || '请先添加规格');
            return false;
        }

        // 获取第一列规格名称
        var data = [];
        var index = parseInt($(this).find('input').val()) || 1;
        if (index <= 0) {
            index = 1;
        }
        if (index > spec_count) {
            index = spec_count;
        }
        index -= 1;
        $('.specifications-table tbody tr').each(function (k, v) {
            var value = $(this).find('td').eq(index).find('input').val() || null;
            if (value != null && data.indexOf(value) == -1) {
                data.push(value);
            }
        });
        if (data.length <= 0) {
            Prompt($spec_table.data('spec-empty-fill-tips') || '请先填写规格');
            return false;
        }

        // 获取已存在规格图片
        var data_old = [];
        $('.spec-images-list ul.spec-images-content li.spec-images-items').each(function (k, v) {
            var value = $(this).find('input').val() || null;
            if (value == null) {
                $(this).remove();
            } else if (data_old.indexOf(value) == -1) {
                data_old.push(value);
            }
        });

        // 循环添加
        for (var i in data) {
            // 开始添加，不存在则不添加
            if (data_old.indexOf(data[i]) == -1) {
                var index = parseInt(Math.random() * 1000001);
                var temp_class = 'spec-images-items-' + index;
                var html = '<li class="spec-images-items ' + temp_class + '">';
                html += '<input type="text" name="spec_images_name[' + index + ']" value="' + data[i] + '" placeholder="' + $spec_table.data('spec-type-name') + '" class="am-radius am-text-center" data-validation-message="' + $spec_table.data('spec-type-message') + '" data-is-clearout="0" required />'
                html += '<ul class="plug-file-upload-view spec-images-view-' + index + '" data-form-name="spec_images[' + index + ']" data-max-number="1" data-dialog-type="images">';
                html += '<li>';
                html += '<input type="text" name="spec_images[' + index + ']" data-validation-message="' + $spec_table.data('spec-images-message') + '" required />';
                html += '<img src="' + __attachment_host__ + '/static/common/images/default-images.jpg" />';
                html += '<i class="iconfont icon-close"></i>';
                html += '</li>';
                html += '</ul>';
                html += '<div class="plug-file-upload-submit" data-view-tag="ul.spec-images-view-' + index + '">+ ' + $spec_table.data('spec-images-name') + '</div>';
                html += '</li>';
                $('.spec-images-list ul.spec-images-content').append(html);
            }
        }

        // 原始图片规格不存在指定规格列中则移除
        for (var i in data_old) {
            if (data.indexOf(data_old[i]) == -1) {
                $('.spec-images-list ul.spec-images-content li.spec-images-items').each(function (k, v) {
                    var value = $(this).find('input').val() || null;
                    if (value == data_old[i]) {
                        $(this).remove();
                    }
                });
            }
        }
    });

    // 自动添加图片规格input输入值处理
    $(document).on('blur', '.specifications-line-images-auto-add input', function () {
        var value = parseInt($(this).val()) || 1;
        if (value <= 0) {
            value = 1;
        }
        var spec_count = $('.specifications-table th.table-title').length || 1;
        if (value > spec_count) {
            value = spec_count;
        }
        $(this).val(value);
    });

    // 自动添加图片规格input禁止冒泡
    $(document).on('click', '.specifications-line-images-auto-add input', function () {
        return false;
    });

    // 规格批量操作-开启
    var $spec_modal = $('#spec-modal-all-operation');
    $(document).on('click', '.specifications-table thead th i.icon-edit', function () {
        $spec_modal.modal({
            width: 200,
            height: 120,
            closeViaDimmer: false
        });
        $spec_modal.attr('data-index', $(this).parent().index());
        $spec_modal.find('.am-input-group input').val('');
    });

    // 规格批量操作-确认
    $spec_modal.find('.am-input-group button').on('click', function () {
        var index = $spec_modal.attr('data-index') || 0;
        var value = $spec_modal.find('.am-input-group input').val() || '';
        $('.specifications-table tbody tr').each(function (k, v) {
            $(this).find('td').eq(index).find('input').val(value);
        });
        $spec_modal.modal('close');
    });

    // 规格高级批量操作-弹层
    var $spec_popup_all_operation = $('#spec-popup-all-operation');
    $(document).on('click', '.specifications-nav-set-all', function () {
        // 获取规格标题
        var title = [];
        $('.specifications-table th.table-title').each(function (k, v) {
            var value = $(this).find('input').val() || null;
            if (value != null && title.indexOf(value) == -1) {
                title.push(value);
            }
        });
        if (title.length < $('.specifications-table th.table-title').length) {
            Prompt($spec_table.data('spec-type-message') || '请填写规格名称');
            return false;
        }

        // 获取规格值
        var data = [];
        for (var i in title) {
            data[i] = [];
            $('.specifications-table tbody tr').each(function (k, v) {
                var value = $(this).find('td').eq(i).find('input').val() || null;
                if (value != null && data[i].indexOf(value) == -1) {
                    data[i].push(value);
                }
            });
        }

        // 拼接html
        var html = '';
        for (var i in data) {
            html += '<div class="am-form-group">';
            html += '<label class="block">' + title[i] + '</label>';
            html += '<select class="chosen-select am-radius" data-placeholder="' + $spec_table.data('spec-all-name') + '">';
            html += '<option value="">' + $spec_table.data('spec-all-name') + '</option>';
            for (var k in data[i]) {
                html += '<option value="' + data[i][k] + '">' + data[i][k] + '</option>';
            }
            html += '</select>';
            html += '</div>';
        }
        var $spec_container = $spec_popup_all_operation.find('.am-popup-bd .spec-title-container');
        $spec_container.html(html);
        if (data.length > 0) {
            $spec_container.show();
        } else {
            $spec_container.hide();
        }

        // select组件初始化
        $spec_popup_all_operation.find('.chosen-select').chosen({
            inherit_select_classes: true,
            enable_split_word_search: true,
            search_contains: true,
            no_results_text: lang_chosen_select_no_results_text
        });

        // 所有input赋空
        $spec_popup_all_operation.find('input').val('');
    });

    // 规格高级批量操作-赋值
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
        html += '<i class="iconfont icon-upload-add"></i>';
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


    // 规格扩展数据编辑
    var $extends_popup = $('#specifications-extends-popup');
    $(document).on('click', '.specifications-table .line-extend-btn', function () {
        $extends_popup.attr('data-line-extend', $(this).parents('tr').attr('data-line-tag'));
        $extends_popup.find('input,select,textarea').val('');
        var json = $(this).prev().val() || null;
        if (json != null) {
            FormDataFill(JSON.parse(json), '#specifications-extends-popup');
        }
        $extends_popup.modal();
    });



    // 规格快捷操作 - 规格列添加
    $(document).on('click', '.quick-spec-title-add', function () {
        var spec_max = $spec_table.data('spec-add-max-number') || 3;
        if ($('.spec-quick table tbody tr').length >= spec_max) {
            Prompt($spec_table.data('spec-max-error'));
            return false;
        }

        var index = parseInt(Math.random() * 1000001);
        var html = '<tr>';
        html += '<td class="am-text-middle">';
        html += '<div class="am-flex am-flex-items-center am-gap-1">';
        html += '<input type="text" name="spec_base_title_' + index + '" placeholder="' + $spec_table.data('spec-type-name') + '" class="am-radius" />';
        html += '<i class="am-close quick-title-remove iconfont icon-delete am-text-red"></i>';
        html += '</div>';
        html += '</td>';
        html += '<td class="spec-quick-td-value am-cf">';
        html += '<div class="am-flex am-flex-warp am-gap-1">'
        html += '<div class="am-fl value-item am-text-left">';
        html += '<span class="business-operations-submit quick-spec-value-add" data-index="' + index + '"><i class="iconfont icon-add"></i> ' + $spec_table.data('spec-add-value-message') + '</span>';
        html += '</div>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        $('.spec-quick table tbody').append(html);
        $('.spec-quick .goods-specifications').show();
    });

    // 添加规格值
    $(document).on('click', '.spec-quick table .quick-spec-value-add', function () {
        var index = $(this).data('index');
        var html = '<div class="am-fl value-item">';
        html += '<div class="am-flex am-flex-items-center am-gap-1">'
        html += '<input type="text" class="am-fl am-radius" name="spec_base_value_' + index + '[]" placeholder="' + $spec_table.data('spec-value-name') + '" />';
        html += '<i class="am-close quick-value-remove iconfont icon-delete am-text-red"></i>';
        html += '</div>';
        html += '</div>';
        $(this).parent().before(html);
    });

    // 规格快捷操作 - 规格名称移除
    $(document).on('click', '.spec-quick table .quick-title-remove', function () {
        $(this).parents('tr').remove();
        if ($('.spec-quick table tbody tr').length <= 0) {
            $('.spec-quick .goods-specifications').hide();
        }
    });

    // 规格快捷操作 - 规格值移除
    $(document).on('click', '.spec-quick table .value-item .quick-value-remove', function () {
        $(this).parents('.value-item').remove();
    });

    // 规格快捷操作 - 生成规格
    $(document).on('click', '.quick-spec-created', function () {
        var spec = [];
        $('.spec-quick table tbody tr').each(function (k, v) {
            var title = $(this).find('td.am-text-middle input').val() || null;
            if (title != null) {
                var temp_data = [];
                $(this).find('td.spec-quick-td-value .value-item').each(function (ks, vs) {
                    var value = $(this).find('input').val() || null;
                    if (value != null) {
                        temp_data.push(value);
                    }
                });
                if (temp_data.length > 0) {
                    spec.push({
                        "title": title,
                        "value": temp_data
                    });
                }
            }
        });

        // 是否存在规格
        if (spec.length <= 0) {
            Prompt($spec_table.data('spec-quick-error') || '快捷操作规格为空');
            return false;
        }

        // 操作确认
        AMUI.dialog.confirm({
            title: $spec_table.data('spec-quick-tips-title'),
            content: $spec_table.data('spec-quick-tips-msg'),
            onConfirm: function (options) {
                // 移除所有规格列
                $('.specifications-table .title-nav-remove').trigger('click');

                // 添加规格列
                for (var i in spec) {
                    var index = parseInt(Math.random() * 1000001);
                    // title
                    html = '<th class="table-title table-title-' + index + '">';
                    html += '<div class="am-flex am-flex-items-center am-gap-1">';
                    html += '<input type="text" name="specifications_name_' + index + '" value="' + spec[i]['title'] + '" placeholder="' + $spec_table.data('spec-type-name') + '" class="am-radius" data-validation-message="' + $spec_table.data('spec-type-message') + '" required />';
                    html += '<i class="am-close title-nav-remove iconfont icon-delete am-text-red" data-index="' + index + '"></i>';
                    html += '</div>';
                    html += '</th>';
                    $('.title-start').before(html);

                    // value
                    html = '<td class="table-value table-value-' + index + '">';
                    html += '<input type="text" name="specifications_value_' + index + '[]" value="' + (spec[i]['value'][0] || "") + '" placeholder="' + $spec_table.data('spec-value-name') + '" class="am-radius" data-validation-message="' + $spec_table.data('spec-value-name') + '" required />';
                    html += '</td>';
                    $('.value-start').before(html);
                }

                // 自动生成规格
                var data = SpecCartesian(spec);
                for (var i = 1; i < data.length; i++) {
                    // 添加规格值
                    var index = parseInt(Math.random() * 1000001);
                    var html = $spec_table.find('tbody tr:last').prop('outerHTML');
                    $spec_table.append(html);
                    $spec_table.find('tbody tr:last').attr('class', 'line-' + index + ' line-not-first');
                    $spec_table.find('tbody tr:last').attr('data-line-tag', '.line-' + index);

                    // 规格值
                    var temp_spec = data[i].split(',');
                    for (var k in temp_spec) {
                        // 规格值赋值
                        $spec_table.find('tbody tr:last').find('td:eq(' + k + ') input').val(temp_spec[k]);
                    }
                }

                // 清空扩展数据
                $('.specifications-table .line-extend-input').val('');

                Prompt($spec_table.data('spec-quick-success') || '生成成功', 'success');
            },
            onCancel: function () { }
        });
    });

    // 虚拟商品编辑器初始化
    if ($('#goods-fictitious-container').length > 0) {
        UE.getEditor('goods-fictitious-container', {
            toolbars: [['source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', '|', 'forecolor', 'backcolor', 'link', 'fontsize', 'insertorderedlist', 'insertunorderedlist', '|', 'simpleupload', 'insertimage', 'insertvideo', 'attachment']],
            initialFrameHeight: 200
        });
    }

    // 规格选中状态
    $(document).on('click', '.specifications-table tbody > tr', function () {
        $('.specifications-table tr').removeClass('am-primary');
        $(this).addClass('am-primary');
    });

    // 商品参数模板选择
    $(document).on('change', '#parameters-quick-container select', function () {
        var value = $(this).val() || null;
        if (value != null) {
            value = decodeURIComponent(value);
            if (typeof (value) == 'object') {
                value = JSON.stringify(value);
            }
        }
        $('#parameters-quick-container textarea').val(value || '');
    });

    // 商品规格模板和参数模板数据获取、选择商品分类后异步读取
    var $spec_quick = $('#specifications-quick-container');
    var $params_quick = $('#parameters-quick-container');
    $(document).on('change', 'select[name="category_id"]', function () {
        var value = $(this).val() || '';
        $.ajax({
            url: RequestUrlHandle($(this).data('base-template-url')),
            type: 'POST',
            dataType: 'json',
            timeout: 305000,
            data: { "category_ids": value },
            success: function (result) {
                // 移除现有模板
                $spec_quick.find('select option').each(function (k, v) {
                    if (k > 0) {
                        $(this).remove();
                    }
                });
                $params_quick.find('select option').each(function (k, v) {
                    if (k > 0) {
                        $(this).remove();
                    }
                });
                // 循环处理得到的最新模板
                if ((result.data || null) != null) {
                    // 规格模板
                    if ((result.data.spec || null) != null && result.data.spec.length > 0) {
                        var html = '';
                        for (var i in result.data.spec) {
                            html += '<option value="' + result.data.spec[i]['content'] + '" data-origin-name="' + result.data.spec[i]['name'] + '">' + result.data.spec[i]['name'] + '</option>';
                        }
                        $spec_quick.find('select').append(html);
                    }

                    // 参数模板
                    if ((result.data.params || null) != null && result.data.params.length > 0) {
                        var html = '';
                        for (var i in result.data.params) {
                            html += '<option value="' + encodeURIComponent(JSON.stringify(result.data.params[i]['config_data'])) + '" data-origin-name="' + result.data.params[i]['name'] + '">' + result.data.params[i]['name'] + '</option>';
                        }
                        $params_quick.find('select').append(html);
                    }
                }
                // 更新select组件
                $spec_quick.find('select').trigger('chosen:updated');
                $params_quick.find('select').trigger('chosen:updated');
            },
            error: function (xhr, type) {
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
            }
        });
    });
    // 规格模板选择
    $(document).on('click', '#specifications-quick-container select option, #specifications-quick-container .chosen-container .chosen-results li.active-result', function () {
        if ($(this).index() > 0) {
            var value = $spec_quick.find('select').val() || null;
            if (value == null) {
                Prompt($spec_quick.data('spec-template-tips') || '规格模板数据有误');
                return false;
            }
            value = value.split(',');
            var name = $spec_quick.find('select').find('option:selected').data('origin-name');

            // 名称是否已存在
            var status = true;
            $('.spec-quick .goods-specifications table tbody tr').each(function () {
                var temp_name = $(this).find('td:first').find('input').val();
                if (temp_name == name) {
                    status = false;
                }
            });
            if (!status) {
                Prompt(($spec_quick.data('spec-template-name-tips') || '相同规格名称已经存在') + '(' + name + ')');
                return false;
            }

            // 模拟点击添加一个规格类型
            $('.quick-spec-title-add').trigger('click');
            // 填入规格名称
            $('.spec-quick .goods-specifications table tbody tr:last td:first input').val(name);
            // 加入规格值
            for (var i in value) {
                $('.spec-quick .goods-specifications table tbody tr:last td:last .quick-spec-value-add').trigger('click');
                $('.spec-quick .goods-specifications table tbody tr:last td:last .value-item:eq(-2) input').val(value[i]);
            }
        }
    });
});