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
function SpecCartesian(arr1, arr2)
{
    // 去除第一个元素
    var result = [];
    var temp_arr = arr1;
    var first = temp_arr.splice(0, 1);

    if((arr2 || null) == null)
    {
        arr2 = [];
    }

    // 判断是否是第一次进行拼接
    if(arr2.length > 0)
    {
        for(var i in arr2)
        {
            for(var k in first[0].value)
            {
                result.push(arr2[i]+','+first[0].value[k]);
            }
        }
    } else {
        for(var i in first[0].value)
        {
            result.push(first[0].value[i]);
        }
    }

    // 递归进行拼接
    if(arr1.length > 0)
    {
        result = SpecCartesian(arr1, result);
    }

    // 返回最终笛卡尔积
    return result;
}
        
$(function()
{
    // 表单初始化
    FromInit('form.form-validation-specifications-extends');

    // 商品导航
    $('.goods-nav li a').on('click', function()
    {
        $('.goods-nav li a').removeClass('goods-nav-active');
        $(this).addClass('goods-nav-active');
    });

    // 商品导航收缩
    $('.goods-nav li.nav-shrink-submit').on('click', function()
    {
        if($(this).find('i').hasClass('am-icon-angle-double-right'))
        {
            $(this).find('i').removeClass('am-icon-angle-double-right');
            $(this).find('i').addClass('am-icon-angle-double-left');
            $(this).parents('.goods-nav').addClass('goods-nav-retract');
            $('.goods-nav-retract').animate({right:'-110px'}, 500, function()
            {
                $('.goods-nav-retract li.nav-shrink-submit').animate({width: '50px', left:'-51px'});
            });
            
        } else {
            $(this).find('i').removeClass('am-icon-angle-double-left');
            $(this).find('i').addClass('am-icon-angle-double-right');
            $(this).parents('.goods-nav').removeClass('goods-nav-retract');
            $('.goods-nav').animate({right:'-0px'});
            $('.goods-nav li.nav-shrink-submit').animate({width: '100%', left:'0px'});
        }
    });

    // 规格列添加
    $('.specifications-nav-title-add').on('click', function()
    {
        var spec_max = $('#goods-nav-operations').data('spec-add-max-number') || 3;
        if($('.specifications-table th.table-title').length >= spec_max)
        {
            Prompt('最多添加'+spec_max+'列规格');
            return false;
        }

        // title
        var index = parseInt(Math.random()*1000001);
        html = '<th class="table-title table-title-'+index+'">';
        html += '<i class="am-close am-close-spin title-nav-remove" data-index="'+index+'">&times;</i>';
        html += '<input type="text" name="specifications_name_'+index+'" placeholder="规格名" class="am-radius" data-validation-message="请填写规格名" required />';
        html += '</th>';
        $('.title-start').before(html);

        // value
        html = '<td class="table-value table-value-'+index+'">';
        html += '<input type="text" name="specifications_value_'+index+'[]" placeholder="规格值" class="am-radius" data-validation-message="请填写规格值" required />';
        html += '</td>';
        $('.value-start').before(html);
    });

    // 规格列移除
    $(document).on('click', '.specifications-table .title-nav-remove', function()
    {
        var index = $(this).data('index');
        $('.table-title-'+index).remove();
        $('.table-value-'+index).remove();

        if($('.specifications-table th.table-title').length <= 0)
        {
            $('.specifications-table tr.line-not-first').remove();
        }
    });

    // 添加一行规格值
    $('.specifications-line-add').on('click', function()
    {
        if($('.specifications-table th.table-title').length <= 0)
        {
            Prompt('请先添加规格');
            return false;
        }

        var index = parseInt(Math.random()*1000001);
        var html = $('.specifications-table').find('tbody tr:last').prop('outerHTML');
        if(html.indexOf('<!--operation-->') >= 0)
        {
            html = html.replace(/<!--operation-->/ig, '<span class="fs-12 cr-blue c-p m-r-5 line-copy">复制</span> <span class="fs-12 cr-red c-p line-remove">移除</span>');
        }
        $('.specifications-table').append(html);
        $('.specifications-table').find('tbody tr:last').attr('class', 'line-'+index+' line-not-first');
        $('.specifications-table').find('tbody tr:last').attr('data-line-tag', '.line-'+index);

        // 值赋空
        $('.specifications-table').find('tbody tr:last').find('input').each(function(k, v)
        {
            $(this).attr('value', '');
        });
    });

    // 规格行复制
    $(document).on('click', '.specifications-table .line-copy', function()
    {
        var index = parseInt(Math.random()*1000001);
        var $parent = $(this).parents('tr');
        $parent.find('input').each(function(k, v)
        {
            $(this).attr('value', $(this).val());
        });
        $parent.after($parent.prop('outerHTML'));
        $('.specifications-table').find('tbody tr:last').attr('class', 'line-'+index+' line-not-first');
        $('.specifications-table').find('tbody tr:last').attr('data-line-tag', '.line-'+index);
    });

    // 规格行移除
    $(document).on('click', '.specifications-table .line-remove', function()
    {
        $(this).parents('tr').remove();

        if($('.specifications-table tbody tr').length <= 1)
        {
            $('.specifications-table th.table-title').remove();
            $('.specifications-table td.table-value').remove();
            $('ul.spec-images-list').html('');
        }
    });

    // 添加规格图片
    $('.specifications-line-images-add').on('click', function()
    {
        if($('.specifications-table th.table-title').length <= 0)
        {
            Prompt('请先添加规格');
            return false;
        }

        // 开始添加
        var index = parseInt(Math.random()*1000001);
        var temp_class = 'spec-images-items-'+index;
        var html = '<li class="spec-images-items '+temp_class+'">';
            html += '<input type="text" name="spec_images_name['+index+']" placeholder="规格名称" class="am-radius t-c" data-validation-message="请填写规格名称" required />'
            html += '<ul class="plug-file-upload-view spec-images-view-'+index+'" data-form-name="spec_images['+index+']" data-max-number="1" data-dialog-type="images">';
            html += '<li>';
            html += '<input type="text" name="spec_images['+index+']" data-validation-message="请上传规格图片" required />';
            html += '<img src="'+__attachment_host__+'/static/admin/default/images/default-images.jpg" />';
            html += '<i>×</i>';
            html += '</li>';
            html += '</ul>';
            html += '<div class="plug-file-upload-submit" data-view-tag="ul.spec-images-view-'+index+'">+上传图片</div>';
            html += '</li>';
        $('ul.spec-images-list').append(html);
    });

    // 规格图片删除
    $('ul.spec-images-list').on('click', 'ul.plug-file-upload-view li i', function()
    {
        $(this).parents('li.spec-images-items').remove();
    });

    // 手机详情添加
    $(document).on('click', '.content-app-items-add-sub', function()
    {
        var $content_tag = $('.content-app-items');

        var i = (($(this).attr('index') || null) == null) ? parseInt($content_tag.find('li').length) : parseInt($(this).attr('index'));
        var index = parseInt(Math.random()*1000001)+i;

        var images_name = $content_tag.data('images-name');
        var content_name = $content_tag.data('content-name');
        var images_text = $content_tag.data('images-text');
        var content_text = $content_tag.data('content-text');
        var delete_text = $content_tag.data('delete-text');
        var drag_sort_text = $content_tag.data('drag-sort-text');

        var html = '<li><div>';
            // 左侧
            html += '<div class="content-app-left">';
            html += '<label class="block">图片</label>';
            html += '<ul class="plug-file-upload-view goods-content-app-images-view-'+index+'" data-form-name="'+images_name+'_'+index+'" data-max-number="1" data-dialog-type="images">';
            html += '</ul>';
            html += '<div class="plug-file-upload-submit" data-view-tag="ul.goods-content-app-images-view-'+index+'">+上传图片</div>';
            html += '</div>';

            // 右侧
            html += '<div class="am-form-group content-app-right fr">';
            html += '<label>文本内容</label>';
            html += '<textarea rows="3" name="'+content_name+'_'+index+'" class="am-radius" placeholder="'+content_text+'" data-validation-message=""></textarea>';
            html += '</div>';
            html += '</div>';

            // 操作按钮
            html += '<i class="c-p fs-12 cr-red content-app-items-rem-sub">删除</i>';
            html += ' <i class="c-m fs-12 drag-sort-submit">拖拽排序</i>';
            html += '</li>';
        $content_tag.append(html);
        $content_tag.attr('index', index);
        $(this).attr('index', i+1);
    });

    // 手机详情删除
    $(document).on('click', '.content-app-items-rem-sub', function()
    {
        $(this).parent().remove();
    });

    // 拖拽
    $('ul.goods-photo-view').dragsort({ dragSelector: 'img', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});
    $('ul.content-app-items').dragsort({ dragSelector: 'i.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});


    // 规格扩展数据编辑
    var $extends_popup = $('#specifications-extends-popup');
    $(document).on('click', '.specifications-table .line-extend-btn', function()
    {
        $extends_popup.attr('data-line-extend', $(this).parents('tr').attr('data-line-tag'));
        $extends_popup.find('input,select,textarea').val('');
        var json = $(this).prev().val() || null;
        if(json != null)
        {
            FormDataFill(JSON.parse(json), '#specifications-extends-popup');
        }
        $extends_popup.modal();
    });



    // 快捷操作
    // 规格列添加
    $('.quick-spec-title-add').on('click', function()
    {
        var spec_max = $('#goods-nav-operations').data('spec-add-max-number') || 3;
        if($('.spec-quick table tbody tr').length >= spec_max)
        {
            Prompt('最多添加'+spec_max+'列规格');
            return false;
        }

        var html = '<tr>';
            html += '<td class="am-text-middle">';
            html += '<i class="am-close am-close-spin quick-title-remove" data-index="168">×</i>';
            html += '<input type="text" name="spec_quick_title_0" placeholder="规格名" />';
            html += '</td>';
            html += '<td class="spec-quick-td-value am-cf">';
            html += '<div class="am-fl am-margin-xs value-item am-text-left">';
            html += '<span class="business-operations-submit quick-spec-value-add">+添加规格值</span>';
            html += '</div>';
            html += '</td>';
            html += '</tr>';
        $('.spec-quick table tbody').append(html);
        $('.spec-quick .goods-specifications').show();
    });

    // 添加规格值
    $(document).on('click', '.spec-quick table .quick-spec-value-add', function()
    {
        var html = '<div class="am-fl am-margin-xs value-item">';
            html += '<input type="text" class="am-fl" name="spec_quick_value_0" placeholder="规格值" />';
            html += '<i class="am-close am-close-spin quick-value-remove" data-index="168">×</i>';
            html += '</div>';
        $(this).parent().before(html);
    });

    // 规格名称移除
    $(document).on('click', '.spec-quick table .quick-title-remove', function()
    {
        $(this).parents('tr').remove();
        if($('.spec-quick table tbody tr').length <= 0)
        {
            $('.spec-quick .goods-specifications').hide();
        }
    });

    // 规格值移除
    $(document).on('click', '.spec-quick table .value-item .quick-value-remove', function()
    {
        $(this).parent().remove();
    });

    // 生成规格
    $('.quick-spec-created').on('click', function()
    {
        var spec = [];
        $('.spec-quick table tbody tr').each(function(k, v)
        {
            spec[k] = {
                "title": $(this).find('td.am-text-middle input').val(),
                "value": []
            }
            $(this).find('td.spec-quick-td-value .value-item').each(function(ks,vs)
            {
                var value = $(this).find('input').val() || null;
                if(value != null)
                {
                    spec[k]['value'][ks] = $(this).find('input').val();
                }
            });
        });

        // 是否存在规格
        if(spec.length <= 0)
        {
            Prompt('快捷操作规格为空');
            return false;
        }

        // 移除规格列
        $('.specifications-table .title-nav-remove').trigger('click');
        
        // 添加规格列
        for(var i in spec)
        {
            var index = parseInt(Math.random()*1000001);
            // title
            html = '<th class="table-title table-title-'+index+'">';
            html += '<i class="am-close am-close-spin title-nav-remove" data-index="'+index+'">&times;</i>';
            html += '<input type="text" name="specifications_name_'+index+'" value="'+spec[i]['title']+'" placeholder="规格名" class="am-radius" data-validation-message="请填写规格名" required />';
            html += '</th>';
            $('.title-start').before(html);

            // value
            html = '<td class="table-value table-value-'+index+'">';
            html += '<input type="text" name="specifications_value_'+index+'[]" value="'+(spec[i]['value'][0] || "")+'" placeholder="规格值" class="am-radius" data-validation-message="请填写规格值" required />';
            html += '</td>';
            $('.value-start').before(html);
        }

        // 自动生成规格
        var data = SpecCartesian(spec);
        for(var i=1; i<data.length; i++)
        {
            // 添加规格值
            var html = $('.specifications-table').find('tbody tr:last').prop('outerHTML');
            if(html.indexOf('<!--operation-->') >= 0)
            {
                html = html.replace(/<!--operation-->/ig, '<span class="fs-12 cr-blue c-p m-r-5 line-copy">复制</span> <span class="fs-12 cr-red c-p line-remove">移除</span>');
            }
            $('.specifications-table').append(html);
            $('.specifications-table').find('tbody tr:last').attr('class', 'line-'+index+' line-not-first');
            $('.specifications-table').find('tbody tr:last').attr('data-line-tag', '.line-'+index);

            // 规格值
            var temp_spec = data[i].split(',');
            for(var k in temp_spec)
            {
                // 规格值赋值
                $('.specifications-table').find('tbody tr:last').find('td:eq('+k+') input').val(temp_spec[k]);
            }
        }
    });
});