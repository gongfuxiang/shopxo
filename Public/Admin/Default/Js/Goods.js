$(function()
{
    // 商品导航
    $('.goods-nav li a').on('click', function()
    {
        $('.goods-nav li a').removeClass('goods-nav-active');
        $(this).addClass('goods-nav-active');
    })

    // 添加属性类型
    $attribute_tag = $('.goods-attribute-items');
    $('.attribute-type-add-sub').on('click', function()
    {
        var index = (($(this).attr('index') || null) == null) ? parseInt($attribute_tag.find('.goods-attribute').length) : parseInt($(this).attr('index'));
        var parent_name = $attribute_tag.data('name') || 'attribute';

        var attribute_type_title = $attribute_tag.data('attribute-type-title');
        var attribute_type_name = $attribute_tag.data('attribute-type-name');
        var attribute_type_format = $attribute_tag.data('attribute-type-format');
        var attribute_type_placeholder = $attribute_tag.data('attribute-type-placeholder');

        var attribute_type_type_name = $attribute_tag.data('attribute-type-type-name');
        var attribute_type_type_format = $attribute_tag.data('attribute-type-type-format');

        var attribute_type_type_show = $attribute_tag.data('attribute-type-type-show');
        var attribute_type_type_choose = $attribute_tag.data('attribute-type-type-choose');
        var attribute_add_sub_text = $attribute_tag.data('attribute-add-sub-text');
        var drag_sort_text = $attribute_tag.data('drag-sort-text');

        var html = '<li class="goods-attribute goods-attribute-'+index+'">';
            html += '<div class="attribute-type am-radius">';
            html += '<i class="am-icon-times-circle am-icon-sm c-p attribute-type-rem-sub"></i>';
            html += '<p class="am-form-group">';
            html += '&nbsp;<span>'+attribute_type_name+'&nbsp;&nbsp;</span>'
            html += '<input type="text" name="'+parent_name+'_'+index+'_data_name" class="am-radius" placeholder="'+attribute_type_placeholder+'" minlength="1" maxlength="10" data-validation-message="'+attribute_type_format+'" required />';
            html += '</p>';

            html += '<p class="am-form-group">';
            html += '&nbsp;<span>'+attribute_type_type_name+'&nbsp;&nbsp;</span>';
            html += '<span class="am-btn-group attribute-type-se" data-am-button>';
            html += '<label class="am-btn am-btn-default am-radius am-btn-sm">';
            html += '<input type="radio" name="'+parent_name+'_'+index+'_data_type" value="show" data-validation-message="'+attribute_type_type_format+'" required />'+attribute_type_type_show+'</label>';

            html += '<label class="am-btn am-btn-default am-radius am-btn-sm">';
            html += '<input type="radio" name="'+parent_name+'_'+index+'_data_type" value="choose" data-validation-message="'+attribute_type_type_format+'" required />'+attribute_type_type_choose+'</label>';

            html += '</span>';
            html += '</p>';
            html += '</div>';
            html += '<ul class="attribute-items-ul-'+index+'"></ul>';
            html += '<i class="am-icon-plus-square-o am-icon-sm attribute-add-sub c-p" name="'+parent_name+'_'+index+'_find" data-tag=".attribute-items-ul-'+index+'"> '+attribute_add_sub_text+'</i>';
            html += ' <i class="am-icon-list-ul am-icon-sm c-m drag-sort-submit"> '+drag_sort_text+'</i>';
            html += '</li>';

        $attribute_tag.append(html);
        $(this).attr('index', index+1);
        $('ul.attribute-items-ul-'+index).dragsort({ dragSelector: 'i.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});
    });

    // 添加属性
    $(document).on("click", ".attribute-add-sub", function()
    {
        var name = $(this).attr('name');
        var index = ($(this).attr('index') == undefined) ? parseInt($(this).parent().find('table').length) : parseInt($(this).attr('index'));

        var drag_sort_text = $attribute_tag.data('drag-sort-text');
        var attribute_name = $attribute_tag.data('attribute-name');
        var attribute_placeholder = $attribute_tag.data('attribute-placeholder');
        var attribute_format = $attribute_tag.data('attribute-format');

        var html = '<li class="attribute">';
            html += '<i class="am-icon-times-circle-o am-icon-sm c-p attribute-rem-sub"></i>&nbsp;';
            html += '<input type="text" name="'+name+'_'+index+'_name" class="am-radius" placeholder="'+attribute_placeholder+'" minlength="1" maxlength="10" data-validation-message="'+attribute_format+'" required />';
            html += '&nbsp;<i class="am-icon-list-ul am-icon-sm c-m drag-sort-submit"> '+drag_sort_text+'</i>';
            html += '</li>';
        $($(this).data('tag')).append(html);
        $(this).attr('index', index+1);
    });

    // 属性类型删除
    $(document).on("click", ".attribute-type-rem-sub", function()
    {
        $(this).parent().parent().remove();
    });

    // 具体属性删除
    $(document).on("click", ".attribute-rem-sub", function()
    {
        $(this).parent().remove();
    });

    // 类型选择
    $(document).on("change", '.attribute-type-se label input[type="radio"]', function()
    {
        var $par_obj = $(this).parent().parent().find('label');
        $par_obj.removeClass('br-sed');
        $(this).parent().addClass('br-sed');
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
        var images_default = $content_tag.data('images-default');
        var delete_text = $content_tag.data('delete-text');
        var drag_sort_text = $content_tag.data('drag-sort-text');
        var select_images_text = $content_tag.data('select-images-text');
        var select_images_format = $content_tag.data('select-images-format');

        var html = '<li><div>';
            html += '<div class="am-form-group am-form-file">';
            html += '<label class="block">'+images_text+'</label>';
            html += '<button type="button" class="am-btn am-btn-default am-btn-sm am-radius">';
            html += '<i class="am-icon-cloud-upload"></i> '+select_images_text+'</button>';
            html += '<input type="text" name="'+images_name+'_'+index+'" class="am-radius js-choice-one original-images-url original-images-url-tag-'+index+'" data-choice-one-to=".images-file-tag-'+index+'" data-validation-message="'+select_images_format+'" readonly="readonly" />';
            html += '<input type="file" name="'+images_name+'_file_'+index+'" data-validation-message="'+select_images_format+'" accept="image/gif,image/jpeg,image/jpg,image/png" class="js-choice-one images-file-tag-'+index+'" data-choice-one-to=".original-images-url-tag-'+index+'" data-tips-tag="#form-images_url-tips-'+index+'" data-image-tag="#form-img-images_url-'+index+'" />';
            html += '<div id="form-images_url-tips-'+index+'" class="m-t-5"></div>';
            html += '<img src="'+images_default+'" id="form-img-images_url-'+index+'" class="block m-t-5 am-img-thumbnail am-radius" height="150" data-default="'+images_default+'" />';
            html += '</div>';
            html += '<div class="am-form-group fr">';
            html += '<label>'+content_text+'</label>';
            html += '<textarea rows="6" name="'+content_name+'_'+index+'" class="am-radius" placeholder="'+content_text+'" data-validation-message=""></textarea>';
            html += '</div>';
            html += '</div>';
            html += '<i class="am-icon-times-circle am-icon-sm c-p content-app-items-rem-sub"> '+delete_text+'</i>';
            html += '<i class="am-icon-list-ul am-icon-sm c-m drag-sort-submit"> '+drag_sort_text+'</i>';
            html += '</li>';
        $content_tag.append(html);
        $content_tag.attr('index', index);
        $(this).attr('index', i+1);
        ImageFileUploadShow('.images-file-tag-'+index);
    });

    // 手机详情删除
    $(document).on('click', '.content-app-items-rem-sub', function()
    {
        $(this).parent().remove();
    });

    // 拖拽
    $('ul.plug-images-list').dragsort({ dragSelector: 'img', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});
    $('ul.content-app-items').dragsort({ dragSelector: 'i.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});
    $('ul.goods-attribute-items').dragsort({ dragSelector: 'i.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});
});