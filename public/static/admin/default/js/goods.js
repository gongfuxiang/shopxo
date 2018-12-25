$(function()
{
    // 商品导航
    $('.goods-nav li a').on('click', function()
    {
        $('.goods-nav li a').removeClass('goods-nav-active');
        $(this).addClass('goods-nav-active');
    })

    // 规格列添加
    $('.specifications-nav-title-add').on('click', function()
    {
        if($('.specifications-table th.table-title').length >= 5)
        {
            Prompt('最多添加5列规格');
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

        // 规格图片
        SpecImagesHandle();
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

        // 规格图片
        SpecImagesHandle();
    });

    // 添加一行规格值
    $('.specifications-line-add').on('click', function()
    {
        if($('.specifications-table th.table-title').length <= 0)
        {
            Prompt('请先添加规格');
            return false;
        }

        var html = $('.specifications-table').find('tbody tr:last').prop('outerHTML');
        if(html.indexOf('<!--operation-->') >= 0)
        {
            html = html.replace(/<!--operation-->/ig, '<span class="fs-12 cr-blue c-p m-r-5 line-copy">复制</span> <span class="fs-12 cr-red c-p line-remove">移除</span>');
        }
        $('.specifications-table').append(html);
        $('.specifications-table').find('tbody tr:last').addClass('line-not-first');

        // 值赋空
        $('.specifications-table').find('tbody tr:last').find('input').each(function(k, v)
        {
            $(this).attr('value', '');
        });

        // 规格图片
        SpecImagesHandle();
    });

    // 规格行复制
    $(document).on('click', '.specifications-table .line-copy', function()
    {
        var $parent = $(this).parents('tr');
        $parent.find('input').each(function(k, v)
        {
            $(this).attr('value', $(this).val());
        });
        $parent.after($parent.prop('outerHTML'));

        // 规格图片
        SpecImagesHandle();
    });

    // 规格行移除
    $(document).on('click', '.specifications-table .line-remove', function()
    {
        $(this).parents('tr').remove();

        if($('.specifications-table tbody tr').length <= 1)
        {
            $('.specifications-table th.table-title').remove();
            $('.specifications-table td.table-value').remove();
        }

        // 规格图片
        SpecImagesHandle();
    });

    // 规格首列事件
    $('.spec-images-tips').on('change', 'input', function()
    {
        // 规格图片
        SpecImagesHandle();
    });

    // 规格第一列输入事件
    $(document).on('change', '.specifications-table tbody tr td.table-value input', function()
    {
        // 规格图片
        SpecImagesHandle();
    });

    // 规格图片
    function SpecImagesHandle()
    {
        if($('.spec-images-tips input').prop('checked'))
        {
            // 清空规格
            $('ul.spec-images-list').html('');

            // 获取第一列数据值
            var temp_spec_all = {}, spec_all = [];
            $('.specifications-table tbody tr').each(function(k, v)
            {
                var value = $(this).find('td.table-value:eq(0) input').val();
                if((value || null) != null)
                {
                    temp_spec_all[value] = value;
                }
            });
            spec_all = Object.keys(temp_spec_all);

            // 添加规格图片
            if(spec_all.length > 0)
            {
                $('.spec-images-list').show();
                for(var i in spec_all)
                {
                    var temp_class = 'spec-images-items-'+spec_all[i];
                    if($('ul.spec-images-list').find('.'+temp_class).length <= 0)
                    {
                        var html = '<li class="'+temp_class+'">';
                            html += '<input type="text" value="'+spec_all[i]+'" class="am-radius t-c" disabled="disabled" />'
                            html += '<ul class="plug-file-upload-view spec-images-view-'+spec_all[i]+'" data-form-name="spec_images['+spec_all[i]+']" data-max-number="1" data-delete="0" data-dialog-type="images">';
                            html += '<li>';
                            html += '<input type="text" name="spec_images['+spec_all[i]+']" value="" data-validation-message="请上传规格图片" required />';
                            html += '<img src="'+__image_host__+'/static/admin/default/images/default-images.png" />';
                            html += '</li>';
                            html += '</ul>';
                            html += '<div class="plug-file-upload-submit" data-view-tag="ul.spec-images-view-'+spec_all[i]+'">+上传图片</div>';
                            html += '</li>';
                        $('ul.spec-images-list').append(html);
                    }
                }
            } else {
                $('.spec-images-list').hide();
            }
        } else {
            $('ul.spec-images-list').html('');
            $('.spec-images-list').hide();
        }
    }

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
    $('ul.goods-attribute-items').dragsort({ dragSelector: 'i.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});

});