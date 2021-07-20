// 公共列表 form 搜索条件
FromInit('form.form-validation-layout-config');
FromInit('form.form-validation-module-offcanvas-images');
FromInit('form.form-validation-module-offcanvas-many-images');
FromInit('form.form-validation-module-offcanvas-video');
FromInit('form.form-validation-module-offcanvas-goods');
FromInit('form.form-validation-module-offcanvas-title');
FromInit('form.form-validation-module-offcanvas-border');
FromInit('form.form-validation-module-offcanvas-height');

FromInit('form.form-validation-module-modal-title-keywords');
FromInit('form.form-validation-module-modal-rolling-config');
FromInit('form.form-validation-module-modal-list-config');

FromInit('form.form-validation-module-popup-goods-search');

// 弹窗容器
var $layout_content_obj = null;
var $page_parent_obj = null;
var $base_show_style_value_obj = null;
var $base_title_keywords_obj = null;
var $offcanvas_layout_config = $('#offcanvas-layout-config');
var $offcanvas_config_images = $('#offcanvas-module-config-images');
var $offcanvas_config_many_images = $('#offcanvas-module-config-many-images');
var $offcanvas_config_video = $('#offcanvas-module-config-video');
var $offcanvas_config_goods = $('#offcanvas-module-config-goods');
var $offcanvas_config_title = $('#offcanvas-module-config-title');
var $offcanvas_config_border = $('#offcanvas-module-config-border');
var $offcanvas_config_height = $('#offcanvas-module-config-height');

var $modal_pages_select = $('#modal-module-pages-select');
var $modal_rolling_config = $('#modal-module-rolling-config');
var $modal_list_config = $('#modal-module-list-config');
var $modal_title_keywords = $('#modal-module-title-keywords');

var $popup_goods_select = $('#popup-module-goods-select');
var $popup_goods_search = $('#popup-module-goods-search');
var $popup_goods_category = $('#popup-module-goods-category');

// 布局模块类型信息
var layout_module_type_arr = {
    "images": "单图",
    "many-images": "多图",
    "video": "视频",
    "goods": "商品",
    "title": "标题",
    "border": "辅助线",
    "height": "辅助空白"
}

/**
 * 模块拖拽初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-13
 * @desc    description
 * @param   {[object]}        event [初始化容器]
 */
function ModuleDragSortInit(event)
{
    // 是否指定初始化容器
    if((event || null) == null)
    {
        event = $('.layout-content');
    }

    // 模块拖拽
    event.dragsort({
        dragSelector: '.module-view-submit-drag',
        placeHolderTemplate: '<div class="drag-sort-dotted"></div>'
    });
}

/**
 * 布局生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-10
 * @desc    description
 * @param   {[string]}        value [布局比例值]
 */
function StructureDragHtmlCreate(value)
{
    // 基础
    var html = '<div class="layout-view" data-value="'+value+'">';
        html += '<i class="layout-view-dragenter-icon am-icon-sort-asc am-icon-lg am-hide"></i>';
        html += '<div class="layout-content-submit drag-submit">';
        html += '<input type="checkbox" class="switch-checkbox" checked="true" data-size="xs" data-on-color="success" data-off-color="warning" data-off-text="关闭" data-on-text="开启" />';
        html += ' <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-square-o layout-submit layout-submit-set"> 布局设置</button>';
        html += ' <button type="button" class="am-btn am-btn-danger am-radius am-btn-xs am-icon-trash-o layout-submit layout-submit-del"> 布局移除</button>';
        html += '</div>';

    // 容器设置
    var content_submit = '<div class="layout-content-submit-container">';
        content_submit += '<button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-gear layout-submit layout-content-submit-set"></button>';
        content_submit += '</div>';

    // 默认内容提示信息
    var content_tips = '<div class="layout-content-tips">模块内容区域</div>';

    // 根据布局类型处理
    var arr = value.toString().split(':');
    var length = arr.length;
    if(length <= 1)
    {
        // 100%
        html += '<div class="layout-content-children">';
        html += '<div class="layout-content-container">';
        html += content_submit;
        html += '<div class="layout-content">';
        html += content_tips;
        html += '</div>';
        html += '</div>';
        html += '</div>';
    } else {
        // 多个格子
        html += '<div class="layout-content-children">';
        for( var i in arr)
        {
            html += '<div class="am-u-md-'+arr[i]+'">';
            html += '<div class="layout-content-container">';
            html += content_submit;
            html += '<div class="layout-content">';
            html += content_tips;
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }
        html += '</div>';
    }
    html += '</div>';
    return html;
}

/**
 * 模块生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-13
 * @desc    description
 * @param   {[string]}        value [模块类型]
 */
function RenovationModuleDragHtmlCreate(value)
{
    // 根据模块类型处理
    if((layout_module_type_arr[value] || null) == null)
    {
        Prompt('模块未定义['+value+']');
        return false;
    }

    // 基础
    var index = parseInt(Math.random()*1000001);
    var doc = 'module-content-index-'+value+'-'+index;
    var html = '<div class="module-view">';
        html += '<div class="module-view-submit-container" data-value="'+value+'" data-index="'+index+'" data-doc=".'+doc+'">';
        html += '<button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-reorder layout-submit module-view-submit-drag"></button>';
        html += ' <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-folder-open-o layout-submit module-view-submit-set"></button>';
        html += ' <button type="button" class="am-btn am-btn-danger am-radius am-btn-xs am-icon-trash-o layout-submit module-view-submit-del"></button>';
        html += '</div>';
        html += '<div class="module-content module-content-type-'+value+' '+doc+'">';
        html += '<div class="am-text-center am-padding-vertical-sm am-text-primary">请配置'+layout_module_type_arr[value]+'</div>';
        html += '</div>';
        html += '</div>';
    return html;
}

/**
 * 模块-链接提示信息
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[string]}        to_name [链接地址名称]
 */
function ModuleToPrompt(to_name)
{
    Prompt(to_name || '未设置链接地址', 'warning');
}

/**
 * 基础样式处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-09
 * @desc    description
 * @param   {[object]}        data          [数据]
 * @param   {[string]}        key           [key]
 * @param   {[array]}         replace_rules [替换规则]
 */
function StyleBaseHandle(data, key, replace_rules)
{
    // 样式容器
    var style = '';

    // 上下左右处理
    // 边线类型、边线、外边距、内边距
    var arr = [
        {
            "type": "border_style",
            "css": "border-style",
            "unit": "",
            "value": ""
        },
        {
            "type": "border_width",
            "css": "border-width",
            "unit": "px",
            "value": 0
        },
        {
            "type": "margin",
            "css": "margin",
            "unit": "px",
            "value": 0
        },
        {
            "type": "padding",
            "css": "padding",
            "unit": "px",
            "value": 0
        }
    ];
    for(var i in arr)
    {
        var type = arr[i]['type'];
        var value = arr[i]['value'];
        var unit = arr[i]['unit'];
        var t = data[key+type+'_top'] || value;
        var r = data[key+type+'_right'] || value;
        var b = data[key+type+'_bottom'] || value;
        var l = data[key+type+'_left'] || value;
        if((t != 0 || r != 0 || b != 0 || l != 0) || (t != '' || r != '' || b != '' || l != ''))
        {
            style += arr[i]['css']+':'+ t+unit+' '+r+unit+' '+b+unit+' '+l+unit+';';
        }
    }

    // 单个处理
    var arr2 = [
        {
            "type": "border_style",
            "css": "border-style",
            "unit": ""
        },
        {
            "type": "border_width",
            "css": "border-width",
            "unit": "px"
        },
        {
            "type": "border_color",
            "css": "border-color",
            "unit": ""
        },
        {
            "type": "border_radius",
            "css": "border-radius",
            "unit": "px"
        },
        {
            "type": "background_color",
            "css": "background-color",
            "unit": ""
        },
        {
            "type": "color",
            "css": "color",
            "unit": ""
        },
        {
            "type": "margin",
            "css": "margin",
            "unit": "px"
        },
        {
            "type": "padding",
            "css": "padding",
            "unit": "px"
        },
        {
            "type": "height",
            "css": "height",
            "unit": "px"
        },
        {
            "type": "width",
            "css": "width",
            "unit": "px"
        }
    ];
    for(var i in arr2)
    {
        if((data[key+arr2[i]['type']] || null) != null)
        {
            // 样式值
            var v = data[key+arr2[i]['type']]+arr2[i]['unit'];

            // 替换规则
            // rules {"field":{"value":"hello","var":"var"}}
            if((replace_rules || null) != null && (replace_rules[arr2[i]['type']] || null) != null)
            {
                var rules = replace_rules[arr2[i]['type']];
                var reg = new RegExp(rules['var'], 'g');
                v = rules['value'].replace(reg, data[key+arr2[i]['type']]);
            }

            style += arr2[i]['css']+':'+v+';';
        }
    }

    return style;
}

/**
 * 元素固定处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-09
 * @desc    description
 * @param   {[object]}        data  [数据]
 */
function MediaFixedHandle(data)
{
    // 文件容器
    var media_container_ent = '';
    var media_container_style = StyleBaseHandle(data, 'style_media_fixed_');
    if((media_container_style || null) != null)
    {
        media_container_ent += 'module-fixed-doc ';
    }

    // 文件
    var media_ent = '';
    var arr = ['width', 'height', 'auto', 'cover'];
    for(var i in arr)
    {
        var key = 'style_media_fixed_is_'+arr[i];
        if((data[key] || 0) == 1)
        {
            media_ent += 'module-fixed-doc-ent-'+arr[i]+' ';
        }
    }

    return {
        "media_container_ent": media_container_ent,
        "media_container_style": media_container_style,
        "media_ent": media_ent
    }
}

/**
 * 模块-容器设置处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackLayoutConfigHandle(data)
{
    // 基础信息
    if($layout_content_obj == null)
    {
        Prompt('操作标记有误');
        return false;
    }

    // 标签类定义
    var ent = '';

    // 边线大小、外边距、内边距
    var size_arr = ['sm', 'md', 'lg'];
    var angle_arr = ['top', 'right', 'bottom', 'left'];
    var type_arr = {
        "style_{var}_border_width": "layout-{var}-border",
        "style_{var}_margin": "layout-{var}-margin",
        "style_{var}_padding": "layout-{var}-padding",
    };
    for(var a in size_arr)
    {
        for(var b in type_arr)
        {
            for(var c in angle_arr)
            {
                var key = b.replace('{var}', size_arr[a])+'_'+angle_arr[c];
                if((data[key] || 0) > 0)
                {
                    ent += type_arr[b].replace('{var}', size_arr[a])+'-'+angle_arr[c]+'-'+data[key]+' ';
                }
            }
        }
    }

    // 边线类型
    for(var a in size_arr)
    {
        for(var b in angle_arr)
        {
            var key = 'style_'+size_arr[a]+'_border_style_'+angle_arr[b];
            if((data[key] || null) != null)
            {
                ent += 'layout-'+size_arr[a]+'-border-'+angle_arr[b]+'-'+data[key]+' ';
            }
        }
    }

    // 圆角
    for(var i in size_arr)
    {
        var key = 'style_'+size_arr[i]+'_border_radius';
        if((data[key] || 0) > 0)
        {
            ent += 'layout-'+size_arr[i]+'-border-radius-'+data[key]+' ';
        }
    }

    // 样式处理
    var style = '';

    // 背景色
    if((data['style_background_color'] || null) != null)
    {
        style += 'background-color:'+data['style_background_color']+';';
    }
    // 边线颜色
    if((data['style_border_color'] || null) != null)
    {
        style += 'border-color:'+data['style_border_color']+';';
    }

    // 类和样式处理
    $layout_content_obj.attr('class', $offcanvas_layout_config.attr('data-ent')+' '+ent);
    $layout_content_obj.attr('style', style);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style,
        "ent": ent
    }
    $layout_content_obj.attr('data-json', encodeURIComponent(JSON.stringify(data)));
    $offcanvas_layout_config.offCanvas('close');
}

/**
 * 模块-图片处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigImagesHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_images.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 图片必须
    if((data.content_images || null) == null)
    {
        Prompt('请上传图片');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 图片固定
    var media_fixed = MediaFixedHandle(data);

    // html拼接
    var html = '<div class="module-images-container" style="'+style+'">';
        html += '<a href="javascript:ModuleToPrompt(\''+(data.content_to_name || '')+'\');" class="'+media_fixed.media_container_ent+'" style="'+media_fixed.media_container_style+'">';
        html += '<img src="'+data['content_images']+'" class="'+media_fixed.media_ent+'" />';
        html += '</a>';
        html += '</div>';
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style,
        "media_fixed": media_fixed
    }
    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));
    $offcanvas_config_images.offCanvas('close');
}

/**
 * 模块-多图处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigManyImagesHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_many_images.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 多图图片
    var fields = {
        "content_images_": "images",
        "content_to_name_": "name",
        "content_to_type_": "type",
        "content_to_value_": "value"
    };
    var key_temp = [];
    var data_list = [];
    for(var i in data)
    {
        for(var f in fields)
        {
            if(i.substr(0, f.length) == f)
            {
                var key = i.replace(f, '') || null;
                if(key != null)
                {
                    // 临时索引记录
                    var index = key_temp.indexOf(key);
                    if(index == -1)
                    {
                        key_temp.push(key);
                        index = key_temp.length-1;
                    }

                    // 数据组合
                    if(data_list[index] == undefined)
                    {
                        data_list[index] = {};
                    }
                    data_list[index][fields[f]] = (fields[f] != 'value' || (data[i] || null) == null) ? data[i] : (JSON.parse(decodeURIComponent(data[i])) || '');
                }
                delete data[i];
            }
        }
    }
    if(data_list.length <= 0)
    {
        Prompt('请先添加图片并配置');
        return false;
    }
    for(var i in data_list)
    {
        if((data_list[i]['images'] || null) == null)
        {
            Prompt('请上传图片');
            return false;
        }
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 展示模式
    var show_style = data.view_list_show_style || 'routine';

    // 图片固定
    var media_fixed = MediaFixedHandle(data);

    // 数据项html
    var item_html = '';
    if(show_style != 'list')
    {
        for(var i in data_list)
        {
            item_html += '<li>';
            item_html += '<a href="javascript:ModuleToPrompt(\''+(data_list[i]['name'] || '')+'\');" class="'+media_fixed.media_container_ent+'" style="'+media_fixed.media_container_style+'">'
            item_html += '<img src="'+data_list[i]['images']+'" class="'+media_fixed.media_ent+'" />';
            item_html += '</a>';
            item_html += '</li>';
        }
    }

    // html拼接
    var html = '<div class="module-slider-container" style="'+style+'">';

    // 初始化参数
    var option = {};

    // 展示模式处理
    var nav_dot_ent = '';
    var list_ent = '';
    switch(show_style)
    {
        // 滚动
        case 'rolling' :
            // 参数处理
            var show_style_value = ViewRollingShowStyleValueHandle(data.view_list_show_style_value);

            // 是否展示导航点
            nav_dot_ent = show_style_value.is_nav_dot ? '' : 'slides-rolling-not-dot';

            // html拼接
            html += '<div class="am-slider am-slider-default am-slider-carousel '+nav_dot_ent+'">';
            html += '<ul class="am-slides">';
            html += item_html;
            html += '</ul>';
            html += '</div>';

            // 组件参数
            option = {
                itemWidth: show_style_value.item_width,
                itemMargin: show_style_value.item_margin,
                slideshow: show_style_value.is_auto_play,
                controlNav: show_style_value.is_nav_dot
            };
            break;

        // 列表
        case 'list' :
            // 参数处理
            var show_style_value = ViewListShowStyleValueHandle(data.view_list_show_style_value);

            // 列表展示数量
            var sm = show_style_value.view_list_number_sm || 2;
            var md = show_style_value.view_list_number_md || 5;
            var lg = show_style_value.view_list_number_lg || 5;

            // 外边距
            var margin = show_style_value.style_margin || 0;

            // 数据项样式处理
            var item_style = (margin > 0) ? 'margin:'+margin+'px 0 0 '+margin+'px;' : '';

            // 设置了外边距，则计算平均移动值
            var avg = (margin > 0) ? 'module-list-content-avg-'+margin : '';

            // 列表class
            list_ent = avg+' module-list-sm-'+sm+' module-list-md-'+md+' module-list-lg-'+md+' ';

            html += '<ul class="module-list-content '+list_ent+'">';
            for(var i in data_list)
            {
                html += '<li>';
                html += '<div class="module-item" style="'+item_style+'">';
                html += '<a href="javascript:ModuleToPrompt(\''+(data_list[i]['name'] || '')+'\');" class="'+media_fixed.media_container_ent+'" style="'+media_fixed.media_container_style+'">'
                html += '<img src="'+data_list[i]['images']+'" class="'+media_fixed.media_ent+'" />';
                html += '</a>';
                html += '</div>';
                html += '</li>';
            }
            html += '</ul>';
            break;

        // 常规、默认
        default :
            // html拼接
            html += '<div data-am-widget="slider" class="am-slider am-slider-a1" data-am-slider=\'{"directionNav":false}\'>';
            html += '<ul class="am-slides">';
            html += item_html;
            html += '</ul>';
            html += '</div>';
            break;
    }
    html += '</div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style,
        "item_style": item_style,
        "nav_dot_ent": nav_dot_ent,
        "list_ent": list_ent,
        "media_fixed": media_fixed
    }
    data['data_list'] = data_list;
    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));

    // 滚动初始化
    if(show_style != 'list')
    {
        $doc.find('.am-slider').flexslider(option);
    }
    $offcanvas_config_many_images.offCanvas('close');
}

/**
 * 模块-视频处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigVideoHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_video.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 视频
    if((data.content_video || null) == null)
    {
        Prompt('请上传视频');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 视频固定
    var media_fixed = MediaFixedHandle(data);

    // html拼接
    var html = '<div class="module-video-container" style="'+style+'">';
        html += '<div class="module-video-content '+media_fixed.media_container_ent+'" style="'+media_fixed.media_container_style+'">';
        html += '<video src="'+data.content_video+'" poster="'+(data.content_images || '')+'" controls class="'+media_fixed.media_ent+'">your browser does not support the video tag</video>';
        html += '</div>';
        html += '</div>';
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style,
        "media_fixed": media_fixed
    }
    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));
    $offcanvas_config_video.offCanvas('close');
}

/**
 * 模块-商品处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigGoodsHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_goods.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 数据类型
    var goods_ids = '';
    var category_id = 0;
    switch(data.goods_data_type)
    {
        // 商品
        case 'goods' :
            if((data.goods_ids || null) == null)
            {
                Prompt('请选择商品');
                return false;
            }
            goods_ids = data.goods_ids;
            break;

        // 商品分类
        case 'category' :
            if((data.goods_category_value || null) == null)
            {
                Prompt('请选择商品分类');
                return false;
            }
            var category = JSON.parse(decodeURIComponent(data.goods_category_value)) || null;
            category_id = category[category.length-1]['id'];
            break;

        default :
            Prompt('数据类型有误['+data.goods_data_type+']');
            return false;
    }

    // 获取商品
    var $this = $offcanvas_config_goods.find('button[type="submit"]');
    var url = $offcanvas_config_goods.data('data-url');
    $this.button('loading');
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "data_type": data.goods_data_type,
            "goods_ids": goods_ids,
            "category_id": category_id,
            "order_by_type": data.goods_order_by_type || 0,
            "order_by_rule": data.goods_order_by_rule || 0,
            "order_limit_number": data.goods_order_limit_number || 10
        },
        dataType: 'json',
        success:function(res)
        {
            $this.button('reset');
            if(res['code'] == 0)
            {
                if((res.data || null) != null && res.data.length > 0)
                {
                    // 列表数据
                    var list = res.data;

                    // 列表展示数量
                    var sm = data.view_list_number_sm || 2;
                    var md = data.view_list_number_md || 5;
                    var lg = data.view_list_number_lg || 5;

                    // 外边距
                    var margin = data.style_margin || 0;

                    // 内容样式处理
                    var style = StyleBaseHandle(data, 'style_module_');

                    // 商品样式处理
                    var rules = {
                        "margin": {
                            "value": "{var}px 0 0 {var}px",
                            "var": "{var}"
                        }
                    };
                    var item_style = StyleBaseHandle(data, 'style_', rules);

                    // 图片固定
                    var media_fixed = MediaFixedHandle(data);

                    // 模块容器设置
                    var $doc = $(doc);

                    // 数据项html
                    var item_html = '';
                    for(var i in list)
                    {
                        item_html += '<li>';
                        item_html += '<div class="module-item" style="'+item_style+'">';
                        item_html += '<a href="'+list[i]['goods_url']+'" target="_blank" class="'+media_fixed.media_container_ent+'" style="'+media_fixed.media_container_style+'">';
                        item_html += '<img src="'+list[i]['images']+'" alt="'+list[i]['title']+'" class="'+media_fixed.media_ent+'" />';
                        item_html += '</a>';
                        item_html += '<div class="item-bottom">';
                        item_html += '<div class="module-title">';
                        item_html += '<a href="'+list[i]['goods_url']+'" target="_blank">'+list[i]['title']+'</a>';
                        item_html += '</div>';
                        item_html += '<p class="module-price">'+__currency_symbol__+list[i]['price']+'</p>';
                        item_html += '</div>';
                        item_html += '</div>';
                        item_html += '</li>';
                    }

                    // 商品容器
                    var html = '<div class="module-goods-container" style="'+style+'">';

                    // 初始化参数
                    var option = {};

                    // 展示模式
                    var nav_dot_ent = '';
                    var list_ent = '';
                    var show_style = data.view_list_show_style || 'routine';
                    switch(show_style)
                    {
                        // 滚动
                        case 'rolling' :
                            // 参数处理
                            var show_style_value = ViewRollingShowStyleValueHandle(data.view_list_show_style_value);

                            // 是否展示导航点
                            nav_dot_ent = show_style_value.is_nav_dot ? '' : 'slides-rolling-not-dot';

                            // html拼接
                            html += '<div class="am-slider am-slider-default am-slider-carousel module-goods-content '+nav_dot_ent+'">';
                            html += '<ul class="am-slides module-list-content">';
                            html += item_html;
                            html += '</ul>';
                            html += '</div>';

                            // 配置参数
                            option = {
                                itemWidth: show_style_value.item_width,
                                itemMargin: show_style_value.item_margin,
                                slideshow: show_style_value.is_auto_play,
                                controlNav: show_style_value.is_nav_dot
                            }
                            break;

                        // 常规、默认
                        default :
                            // 设置了外边距，则计算平均移动值
                            var avg = (margin > 0) ? 'module-list-content-avg-'+margin : '';

                            // 列表class
                            list_ent = avg+' module-list-sm-'+sm+' module-list-md-'+md+' module-list-lg-'+md+' ';

                            html += '<ul class="module-goods-content module-list-content '+list_ent+'">';
                            html += item_html;
                            html += '</ul>';
                            break;
                    }
                    html += '</div>';

                    // 固定商品则加入数据中，方便使用
                    if(data.goods_data_type == 'goods')
                    {
                        data['data_list'] = list;
                    }

                    // 模块容器设置
                    $doc.html(html);

                    // 数据加入配置
                    data['frontend_config'] = {
                        "style": style,
                        "item_style": item_style,
                        "nav_dot_ent": nav_dot_ent,
                        "list_ent": list_ent,
                        "media_fixed": media_fixed
                    }
                    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));

                    // 组件参数
                    if(JSON.stringify(option) !== '{}')
                    {
                        $doc.find('.am-slider').flexslider(option);
                    }

                    // 关闭商品窗口
                    $offcanvas_config_goods.offCanvas('close');
                } else {
                    Prompt('商品信息为空');
                }
            } else {
                Prompt(res.msg);
            }                
        },
        error:function(res)
        {
            $this.button('reset');
            var msg = HtmlToString(xhr.responseText) || '异常错误';
            Prompt(msg, null, 30);
        }
    });
}

/**
 * 模块-标题处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigTitleHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_title.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 视频
    if((data.content_title || null) == null)
    {
        Prompt('请填写主标题');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 主标题样式
    var style_title_main = StyleBaseHandle(data, 'style_title_');

    // 副标题样式
    var style_title_vice = StyleBaseHandle(data, 'style_title_vice_');

    // 右侧按钮样式
    var style_title_more = StyleBaseHandle(data, 'style_title_more_');

    // html拼接
    var html = '<div class="module-title-container" style="'+style+'">';
        html += '<div class="module-title-content">';
        html += '<span class="title-main" style="'+style_title_main+'">'+data.content_title+'</span>';

    // 副标题
    if((data.content_title_vice || null) != null)
    {
        html += '<span class="title-vice" style="'+style_title_vice+'">'+data.content_title_vice+'</span>';
    }
        
    // 关键字
    var field_first = 'content_item_keywords_';
    var field_first_length = field_first.length;
    var key_temp = [];
    var keywords_list = [];
    for(var i in data)
    {
        if(i.substr(0, field_first_length) == field_first)
        {
            keywords_list.push(JSON.parse(decodeURIComponent(data[i])));
            delete data[i];
        }
    }
    if(keywords_list.length > 0)
    {
        html += '<div class="keywords-content">';
        for(var i in keywords_list)
        {
            var kt_item_style = ((keywords_list[i]['style_keywords_color'] || null) == null) ? '' : 'color:'+keywords_list[i]['style_keywords_color']+';';
            html += '<a href="javascript:ModuleToPrompt(\''+keywords_list[i]['content_to_name']+'\');" style="'+kt_item_style+'">'+keywords_list[i]['content_keywords']+'</a>';
        }
        html += '</div>';   
    }
    data['keywords_list'] = keywords_list;


    // 右侧按钮
    if((data.content_title_more || null) != null)
    {
        html += '<div class="more-content">';
        html += '<a href="javascript:ModuleToPrompt(\''+(data.content_to_name || '')+'\');" style="'+style_title_more+'">';
        html += '<span>'+data.content_title_more+'</span> ';
        html += '<i class="am-icon-angle-right"></i>';
        html += '</a>';
        html += '</div>';
    }
    html += '</div>';
    html += '</div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style,
        "style_title_main": style_title_main,
        "style_title_vice": style_title_vice,
        "style_title_more": style_title_more
    }
    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));
    $offcanvas_config_title.offCanvas('close');
}

/**
 * 模块-辅助线处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigBorderHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_border.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 边线类型、和大小
    if((data.style_border_style || null) == null)
    {
        Prompt('请选择边线类型');
        return false;
    }
    if((data.style_border_width || null) == null)
    {
        Prompt('请输入边线、最大10的数字');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // html拼接
    var html = '<div class="module-border-container" style="'+style+'"></div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style
    }
    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));
    $offcanvas_config_border.offCanvas('close');
}

/**
 * 模块-辅助空白处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigHeightHandle(data)
{
    // 基础信息
    var doc = $offcanvas_config_height.attr('data-doc') || null;
    if(doc == null)
    {
        Prompt('模块标记有误');
        return false;
    }

    // 高度
    if((data.style_height || null) == null)
    {
        Prompt('请输入高度、最大100的数字');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // html拼接
    var html = '<div class="module-height-container" style="'+style+'"></div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        "style": style
    }
    $doc.attr('data-json', encodeURIComponent(JSON.stringify(data)));
    $offcanvas_config_height.offCanvas('close');
}

/**
 * 模块-滚动配置处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleModalRollingConfigHandle(data)
{
    $base_show_style_value_obj.val(encodeURIComponent(JSON.stringify(data)));
    $modal_rolling_config.modal('close');
}

/**
 * 模块-列表配置处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleModalListConfigHandle(data)
{
    $base_show_style_value_obj.val(encodeURIComponent(JSON.stringify(data)));
    $modal_list_config.modal('close');
}

/**
 * 模块-标题-关键字处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleModalTitleKeywordsHandle(data)
{
    // 操作类型
    var type = parseInt($modal_title_keywords.attr('data-opt-type') || 0);

    // 获取设置的数据、关键字名称必填
    if((data.content_keywords || null) == null)
    {
        $modal_title_keywords.find('input[name="content_keywords"]').focus();
        Prompt('请填写关键字');
        return false;
    }

    // 生成数据项
    var params = {
        "content_keywords": data.content_keywords,
        "style_keywords_color": data.style_keywords_color || '',
        "content_to_type": data.content_to_type || '',
        "content_to_name": data.content_to_name || '',
        "content_to_value": data.content_to_value || ''
    };
    var html = ModuleConfigTitleKeywordsContentHtml(params);
    // 0添加、1编辑
    if(type == 0)
    {
        $offcanvas_config_title.find('.config-title-container').append(html);
    } else {
        $base_title_keywords_obj.prop('outerHTML', html);
    }
    $modal_title_keywords.modal('close');
}

/**
 * 模块-弹窗搜索选择回调处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModulePopupGoodsSearchHandle(data)
{
    // 参数配置
    var type = $popup_goods_search.find('.am-tabs-nav li.am-active a').data('value');
    var $vb = $popup_goods_search.find('.form-container-'+type);
    var params = {
        "type": type,
        "value": ""
    };
    switch(type)
    {
        // 商品分类
        case 'category' :
            var json = $vb.find('.already-select-tips').attr('data-value') || null;
            if(json != null)
            {
                json = JSON.parse(decodeURIComponent(json)) || null;
            }
            if(json == null)
            {
                Prompt('请先选择商品分类');
                return false;
            }
            params['value'] = json;
            break;

        // 品牌
        case 'brand' :
            var $vbs = $vb.find('ul li.active a');
            if($vbs.length <= 0)
            {
                Prompt('请先选择品牌');
                return false;
            }
            params['value'] = {
                "id": $vbs.data('value'),
                "name": $vbs.find('span').text()
            };
            break;

        // 关键字
        case 'keywords' :
            var value = $vb.find('input').val() || '';
            if(value == '')
            {
                $vb.find('input').focus();
                Prompt('请先输入关键字1~30个字符');
                return false;
            }
            // 输入关键字去除引号
            params['value'] = value.replace(new RegExp('"', 'g'), '').replace(new RegExp("'", 'g'), '');
            break;
    }

    // 数据赋值并关闭弹窗
    var show_name = ModuleConfigGoodsSearchPageShowName(params);
    var $page = $modal_pages_select.find('.am-tabs-bd ul li.page-goods_search a');
        $page.find('span').text($page.data('name')+show_name);
        $page.attr('data-json', encodeURIComponent(JSON.stringify(params)));
        $popup_goods_search.modal('close');
}

/**
 * 模块-图片链接地址生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[string]}        name [显示名称]
 */
function ModuleConfigImagesToContentHtml(name)
{
    // 无数据
    if((name || null) == null)
    {
        return '<a href="javascript:;" class="form-view-choice-container-submit">请选择跳转链接</a>';
    }

    // 有数据
    var html = '<span class="form-view-choice-container-submit form-view-choice-container-active am-radius">';
        html += '<span class="am-text-truncate">'+name+'</span>';
        html += '<i class="am-icon-close am-margin-left-xs"></i>';
        html += '</span>';
        html += '<a href="javascript:;" class="form-view-choice-container-submit am-margin-left-sm">修改</a>';
    return html;
}

/**
 * 模块-商品分类地址生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[string]}        name [显示名称]
 */
function ModuleConfigGoodsCategoryContentHtml(name)
{
    // 无数据
    if((name || null) == null)
    {
        return '<a href="javascript:;" class="form-view-choice-container-submit">请选择商品分类</a>';
    }

    // 设置数据
    var html = '<span class="form-view-choice-container-submit form-view-choice-container-active am-radius">';
        html += '<span class="am-text-truncate">'+name+'</span>';
        html += '<i class="am-icon-close am-margin-left-xs"></i>';
        html += '</span>';
        html += '<a href="javascript:;" class="form-view-choice-container-submit am-margin-left-sm">修改</a>';
    return html;
}

/**
 * 模块-商品信息生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [商品列表]
 */
function ModuleConfigGoodsItemContentHtml(data)
{
    var html = '';
    if((data || null) != null && data.length > 0)
    {
        for(var i in data)
        {
            html += '<li data-gid="'+data[i]['id']+'">';
            html += '<a href="javascript:;" class="am-close am-close-alt am-icon-times"></a>';
            html += '<a href="'+data[i]['goods_url']+'" title="'+data[i]['title']+'" target="_blank" class="am-block am-padding-xs">';
            html += '<img src="'+data[i]['images']+'" alt="'+data[i]['title']+'" />';
            html += '</a>';
            html += '</li>';
        }
    }
    return html;
}

/**
 * 模块-多图信息生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[string]}        images    [图片地址]
 * @param   {[string]}        type      [类型]
 * @param   {[string]}        name      [名称]
 * @param   {[string]}        value     [数据值]
 */
function ModuleConfigManyImagesItemContentHtml(images, type, name, value)
{
    var index = parseInt(Math.random()*1000001);
    var html = '<div class="am-panel am-panel-default am-padding-sm">';
        html += '<a href="javascript:;" class="am-close am-close-alt am-icon-times"></a>';
        html += '<div class="am-form-group am-form-file am-form-group-refreshing">';
        html += '<ul class="plug-file-upload-view module-slider-type-images-view module-slider-type-images-view-'+index+'" data-form-name="content_images_'+index+'" data-max-number="1" data-delete="0" data-dialog-type="images">';
        html += '<li>';
        html += '<input type="text" name="content_images_'+index+'" data-validation-message="请上传图片" value="'+(images || '')+'" required />';
        html += '<img src="'+(images || $offcanvas_config_many_images.data('default-images'))+'" />';
        html += '</li>';
        html += '</ul>';
        html += '<div class="plug-file-upload-submit" data-view-tag="ul.module-slider-type-images-view-'+index+'">+上传图片</div>';
        html += '</div>';
        html += '<div class="am-form-group am-form-group-refreshing">';
        html += '<div class="form-view-choice-container am-margin-top-xs" data-key="'+index+'">';
        html += '<input type="hidden" name="content_to_type_'+index+'" value="'+(type || '')+'" />';
        html += '<input type="hidden" name="content_to_name_'+index+'" value="'+(name || '')+'" />';
        html += '<input type="hidden" name="content_to_value_'+index+'" value="'+((value || null) == null ? '' : encodeURIComponent(JSON.stringify(value)))+'" />';
        html += '<div class="form-view-choice-container-content">';
        html += ModuleConfigImagesToContentHtml(name);
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
    return html;
}

/**
 * 模块-标题关键字
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-12
 * @desc    description
 * @param   {[object]}        data [关键字数据项配置]
 */
function ModuleConfigTitleKeywordsContentHtml(data)
{
    var index = parseInt(Math.random()*1000001);
    var html = '<li>';
        html += '<input type="hidden" name="content_item_keywords_'+index+'" value="'+(((data || null) == null) ? '' : encodeURIComponent(JSON.stringify(data)))+'" />';
        html += '<span class="am-text-truncate am-block">'+(data.content_keywords || '')+'</span>';
        html += '<a href="javascript:;" class="am-icon-edit"></a>';
        html += '<a href="javascript:;" class="am-icon-remove"></a>';
        html += '</li>';
    return html;
}

/**
 * 模块-标题关键字打开弹窗
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-12
 * @desc    description
 * @param   {[int]}        type [操作类型（0添加、 1编辑）]
 * @param   {[object]}     data [关键字数据项配置]
 */
function ModuleConfigTitleKeywordsOpen(type, data)
{
    // 数据处理
    if((data || null) != null)
    {
        data = JSON.parse(decodeURIComponent(data)) || null;
    }
    if((data || null) == null)
    {
        data = {
            "content_keywords" : "",
            "style_keywords_color" : "",
            "content_to_name" : "",
            "content_to_type" : "",
            "content_to_value" : ""
        };
    }

    // 表单数据赋值
    FormDataFill(data, '#modal-module-title-keywords');

    // 链接地址
    $modal_title_keywords.find('.form-view-choice-container-content').html(ModuleConfigImagesToContentHtml(data.content_to_name));

    // 关键字颜色
    var color = data.style_keywords_color || '';
    $modal_title_keywords.find('.module-style-color_keywords').css({"background-color":color, "border-color":color, "color":color});

    // 开启标题关键字配置弹窗
    $modal_title_keywords.modal({
        width: 260,
        height: 210,
        closeViaDimmer: false
    });
    $modal_title_keywords.attr('data-opt-type', type || 0);
}

/**
 * 模块商品搜索-页面展示名称
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-20
 * @desc    description
 * @param   {[string]}          data -> type  [类型]
 * @param   {[string|object]}   data -> value [选择的数据值]
 */
function ModuleConfigGoodsSearchPageShowName(data)
{
    var name = '';
    if((data || null) != null && (data.type || null) != null && (data.value || null) != null)
    {
        var value = data['value'];
        switch(data.type)
        {
            // 商品分类
            case 'category' :
                name = '商品分类-'+value[value.length-1]['name'];
                break;

            // 品牌
            case 'brand' :
                name = '品牌-'+value['name'];
                break;

            // 关键字
            case 'keywords' :
                name = '关键字-'+value;
                break;
        }
    }
    return (name || null) == null ? '' : '（'+name+'）';
}

/**
 * 左侧商品配置商品id
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-06
 * @desc    description
 */
function OffcanvasModuleConfigGoodsIds()
{
    return $('#offcanvas-module-config-goods input[name="goods_ids"]').val() || '';
}

/**
 * 滚动数据参数处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-12
 * @desc    description
 * @param   {[object]}        data [配置数据]
 */
function ViewRollingShowStyleValueHandle(data)
{
    // 数据处理
    var value = ((data || null) == null) ? {} : (JSON.parse(decodeURIComponent(data)) || {});

    // 宽度
    value['item_width'] = parseInt(value['item_width'] || 200);

    // 外边距
    value['item_margin'] = parseInt(value['item_margin'] || 0);

    // 是否自动播放
    value['is_auto_play'] = ((value.is_auto_play || 0) == 0) ? false : true;

    // 是否展示导航点
    value['is_nav_dot'] = ((value.is_nav_dot || 0) == 0) ? false : true;

    return value;
}

/**
 * 列表数据参数处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-12
 * @desc    description
 * @param   {[object]}        data [配置数据]
 */
function ViewListShowStyleValueHandle(data)
{
    // 数据处理
    var value = ((data || null) == null) ? {} : (JSON.parse(decodeURIComponent(data)) || {});

    // 小屏
    value['view_list_number_sm'] = parseInt(value['view_list_number_sm'] || 2);

    // 中屏
    value['view_list_number_md'] = parseInt(value['view_list_number_md'] || 5);

    // 大屏
    value['view_list_number_lg'] = parseInt(value['view_list_number_lg'] || 5);

    // 外边距
    value['style_margin'] = parseInt(value['style_margin'] || 0);

    return value;
}

/**
 * 左侧配置 - 页面选择
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-10
 * @desc    description
 * @param   {[object]}        obj   [当前对象]
 * @param   {[object]}        event [事件]
 */
function OffcanvasConfigPagesChoice(obj, event)
{
    // 当前选择页面的链接位置对象
    $page_parent_obj = obj.parent();

    // 获取已选择的数据
    var $parent = $page_parent_obj.parents('.form-view-choice-container');
    var key = $parent.data('key') || null;
    var index = (key == null) ? '' : '_'+key;
    var to_type = $parent.find('input[name="content_to_type'+index+'"]').val() || null;
    var to_name = $parent.find('input[name="content_to_name'+index+'"]').val() || null;
    var to_value = $parent.find('input[name="content_to_value'+index+'"]').val() || null;

    // 所有处理
    $modal_pages_select.find('.am-tabs-bd ul li').each(function(k, v)
    {
        $(this).removeClass('active');
        $(this).find('a span').text($(this).find('a').data('name'));   
    });
    $modal_pages_select.find('.am-tabs-bd ul li').removeClass('active');
    $modal_pages_select.find('.am-tabs-bd ul li a').attr('data-json', '');

    // 自定义链接地址
    if(to_type == 'pages-custom-url')
    {
        var form_doc = '.pages-custom-url-container';
        if((to_value || null) == null)
        {
            to_value = GetFormVal(form_doc, true);
        } else {
            to_value = JSON.parse(decodeURIComponent(to_value));
        }
        FormDataFill(to_value, form_doc);
        var index = 2;

    // 常规页面选择
    } else {
        // 当前选中的数据
        var $active_obj = $modal_pages_select.find('.am-tabs-bd ul li.page-'+to_type);
        $active_obj.addClass('active');
        $active_obj.find('a span').text(to_name);
        $active_obj.find('a').attr('data-json', to_value);

        // 当前选中的索引值
        var index = $active_obj.parents('.am-tab-panel').index();
        if(index == -1)
        {
            index = 0;
        }
    }

    // tab切换
    $modal_pages_select.find('.am-tabs-nav li').removeClass('am-active');
    $modal_pages_select.find('.am-tabs-nav li').eq(index).addClass('am-active');
    $modal_pages_select.find('.am-tabs-bd .am-tab-panel').removeClass('am-active');
    $modal_pages_select.find('.am-tabs-bd .am-tab-panel').eq(index).addClass('am-active');

    // 开启页面选择弹窗
    $modal_pages_select.modal({
        width: 300,
        height: 332,
        closeViaDimmer: false
    });
}

/**
 * 左侧配置 - 页面移除
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-10
 * @desc    description
 * @param   {[object]}        obj   [当前对象]
 * @param   {[object]}        event [事件]
 */
function OffcanvasConfigPagesRemove(obj, event)
{
    var $parent = obj.parents('.form-view-choice-container');
    var $content = $parent.find('.form-view-choice-container-content');
    var key = $parent.data('key') || null;
    var index = (key == null) ? '' : '_'+key;
    $content.html(ModuleConfigImagesToContentHtml());
    $parent.find('input[name="content_to_type'+index+'"]').val('');
    $parent.find('input[name="content_to_name'+index+'"]').val('');
    $parent.find('input[name="content_to_value'+index+'"]').val('');
    event.stopPropagation();
}

/**
 * 模块背景色组件处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-09
 * @desc    description
 * @param   {[object]}        e  [模块对象]
 */
function ModuleColorpickerHandle(e)
{
    e.find('.colorpicker-submit').each(function(k ,v)
    {
        var color = $(this).prev().val() || '';
        var style = $(this).data('color-style') || null;
        if(style != null)
        {
            var arr = style.split('|');
            for(var i in arr)
            {
                $(this).css(arr[i], color);
            }
        }
    });
}

/**
 * 布局页面配置获取
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-16
 * @desc    description
 */
function LayoutViewConfig()
{
    // 循环读取布局
    var data = [];
    $('.layout-container .layout-view').each(function(k, v)
    {
        // 布局数据
        var json = $(this).find('.layout-content-children').attr('data-json') || null;
        var layout_temp = {
            "value": $(this).data('value').toString(),
            "status": ($(this).find('.layout-content-submit input[type="checkbox"]:checked').val() == 'on') ? 1 : 0,
            "config": (json == null) ? {} : JSON.parse(decodeURIComponent(json)),
            "children": []
        };

        // 模块容器
        $(this).find('.layout-content-container').each(function(ks, vs)
        {
            // 容器数据
            var json = $(this).attr('data-json') || null;
            var content_temp = {
                "config": (json == null) ? {} : JSON.parse(decodeURIComponent(json)),
                "children": []
            };

            // 模块
            $(this).find('.layout-content .module-view').each(function(kss, vss)
            {
                // 模块数据
                var json = $(this).find('.module-content').attr('data-json') || null;
                var value = $(this).find('.module-view-submit-container').data('value');
                var module_config = {
                    "value": value,
                    "name": layout_module_type_arr[value],
                    "config": (json == null) ? {} : JSON.parse(decodeURIComponent(json))
                };
                // 商品模块移除商品列表
                if(value == 'goods')
                {
                    delete module_config.config.data_list;
                }
                content_temp.children.push(module_config);
            });

            // 容器加入布局
            layout_temp.children.push(content_temp);
        });

        // 加入数据列表
        data.push(layout_temp);
    });
    return data;
}

/**
 * 状态切换组件初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-18
 * @desc    description
 */
function LayoutSwitchInit()
{
    $('.layout-view .switch-checkbox').bootstrapSwitch({
        onSwitchChange: function(event, state)
        {
            if(state === true)
            {
                $(this).parents('.layout-view').removeClass('layout-view-hidden');
            } else {
                $(this).parents('.layout-view').addClass('layout-view-hidden');
            }
        }
    });
}


$(function()
{
    // 布局拖拽
    $('.layout-container').dragsort({
        dragSelector: '.drag-submit',
        placeHolderTemplate: '<div class="drag-sort-dotted"></div>'
    });

    // 模块拖拽
    ModuleDragSortInit();

    // 开关初始化
    LayoutSwitchInit();

    // 布局/模块切换事件
    $('.renovation-tabs .am-tabs-nav a').on('click', function()
    {
        $(this).parent('li').addClass('am-active').siblings('li').removeClass('am-active');
        $('.renovation-tabs .am-tabs-bd .renovation-panel').addClass('am-hide');
        $($(this).attr('href')).removeClass('am-hide');
    });

    // 布局拖放
    $(document).on('dragstart', '.structure-drag button', function(e)
    {
        // 布局配置
        var drag_value = $(this).data('value');

        // 拖拽过程中经过的元素
        $(document).on('dragenter', '.layout-view, .layout-container-tips', function(e)
        {
            // 当前是否在布局模式下操作
            if($('.renovation-tabs .am-tabs-nav li.am-active').data('value') == 'structure')
            {
                $('.layout-view, .layout-container-tips').removeClass('layout-view-dragenter');
                $(this).addClass('layout-view-dragenter');
            }
        });
        // 拖拽离开元素移除过程中添加的样式
        $(document).on('dragleave', '.structure-drag button', function(e)
        {
            $('.layout-view,.layout-container-tips').removeClass('layout-view-dragenter');
        });
        // 拖拽结束
        $(document).on('dragend', '.structure-drag button', function(e)
        {
            // 移除过程中添加的样式
            $('.layout-view,.layout-container-tips').removeClass('layout-view-dragenter');

            // 关闭事件
            $('.layout-view, .layout-container-tips').off('dragover');
            $('.layout-view, .layout-container-tips').off('dragenter');
            $('.layout-view, .layout-container-tips').off('drop');
        });
        // 拖拽过程中一直在元素内、并阻止默认事件
        $(document).on('dragover', '.layout-container', function(e)
        {
            e.preventDefault();
            e.stopPropagation();
        });

        // 拖放接收事件
        $('.layout-view, .layout-container-tips').on('drop', function(e)
        {
            // 添加布局
            $(this).before(StructureDragHtmlCreate(drag_value));

            // 容器设置弹出框提示初始化
            $('.layout-content-submit-set').popover({
                content: '容器设置',
                trigger: 'hover focus',
                theme: 'sm'
            });

            // 新的元素新增样式
            var $new = $(this).prev();
            $new.addClass('layout-view-new');
            setTimeout(function() {
                $new.removeClass('layout-view-new');
            }, 1500);

            // 模块拖拽
            ModuleDragSortInit($new.find('.layout-content'));

            // 关闭事件
            $('.layout-view, .layout-container-tips').off('dragover');
            $('.layout-view, .layout-container-tips').off('dragenter');
            $('.layout-view, .layout-container-tips').off('drop');

            // 开关初始化
            LayoutSwitchInit();

            // 移除布局提示
            $('.layout-container-tips').remove();
        });
    });

    // 布局设置
    $(document).on('click', '.layout-submit-set', function()
    {
        // 布局对象
        $layout_content_obj = $(this).parents('.layout-content-submit').next();

        // 配置数据
        var config_doc = '#offcanvas-layout-config';
        var json = $layout_content_obj.attr('data-json') || null;
        if(json != null)
        {
            // 数据解析
            json = JSON.parse(decodeURIComponent(json)) || null;
        }
        // 获取表单字段并赋空值
        if((json || null) == null)
        {
            json = GetFormVal(config_doc, true);
            for(var i in json)
            {
                json[i] = '';
            }
        }
        FormDataFill(json, config_doc);

        // 背景色组件处理
        ModuleColorpickerHandle($offcanvas_layout_config);

        // 指定操作类型
        $offcanvas_layout_config.attr('data-ent', 'layout-content-children');

        // 打开配置
        $offcanvas_layout_config.offCanvas('open');
    });

    // 布局移除
    $(document).on('click', '.layout-submit-del', function()
    {
        var $this = $(this);
        AMUI.dialog.confirm({
            title: '温馨提示',
            content: '移除后不可恢复、确定继续吗？',
            onConfirm: function(e)
            {
                // 移除布局
                $this.parents('.layout-view').remove();

                // 无布局则添加提示信息
                if($('.layout-container .layout-view').length <= 0)
                {
                    $('.layout-container').html('<div class="layout-container-tips">布局拖放到该区域松开鼠标即可</div>');
                }
            }
        });
    });

    // 容器设置
    $(document).on('click', '.layout-content-submit-set', function()
    {
        // 容器内容对象
        $layout_content_obj = $(this).parents('.layout-content-container');

        // 配置数据
        var config_doc = '#offcanvas-layout-config';
        var json = $layout_content_obj.attr('data-json') || null;
        if(json != null)
        {
            // 数据解析
            json = JSON.parse(decodeURIComponent(json)) || null;
        }
        // 获取表单字段并赋空值
        if((json || null) == null)
        {
            json = GetFormVal(config_doc, true);
            for(var i in json)
            {
                json[i] = '';
            }
        }
        FormDataFill(json, config_doc);

        // 背景色组件处理
        ModuleColorpickerHandle($offcanvas_layout_config);

        // 指定操作类型
        $offcanvas_layout_config.attr('data-ent', 'layout-content-container');

        // 打开配置
        $offcanvas_layout_config.offCanvas('open');
    });

    // 模块拖放
    $(document).on('dragstart', '.renovation-drag button', function(e)
    {
        // 布局配置
        var drag_value = $(this).data('value');

        // 拖拽过程中经过的元素
        $(document).on('dragenter', '.layout-content', function(e)
        {
            // 当前是否在布局模式下操作
            if($('.renovation-tabs .am-tabs-nav li.am-active').data('value') == 'module')
            {
                $('.layout-content').removeClass('layout-content-dragenter');
                if(!$(this).hasClass('.layout-content-tips'))
                {
                    $(this).addClass('layout-content-dragenter');
                }
            }
        });
        // 拖拽离开元素移除过程中添加的样式
        $(document).on('dragleave', '.renovation-drag button', function(e)
        {
            $('.layout-content').removeClass('layout-content-dragenter');
        });
        // 拖拽结束
        $(document).on('dragend', '.renovation-drag button', function(e)
        {
            // 移除过程中添加的样式
            $('.layout-content').removeClass('layout-content-dragenter');

            // 关闭事件
            $('.layout-content').off('dragover');
            $('.layout-content').off('dragenter');
            $('.layout-content').off('drop');
        });

        // 拖拽过程中一直在元素内、并阻止默认事件
        $(document).on('dragover', '.layout-content', function(event)
        {
            event.preventDefault(); 
            event.stopPropagation(); 
        });

        // 拖放接收事件
        $('.layout-content').on('drop', function(e)
        {
            // 生成模块html
            var html = RenovationModuleDragHtmlCreate(drag_value);
            if(html != false)
            {
                // 添加模块
                $(this).find('.layout-content-tips').remove();
                $(this).append(html);

                // 模块弹出框提示初始化
                $('.module-view-submit-drag').popover({
                    content: '拖拽排序',
                    trigger: 'hover focus',
                    theme: 'sm'
                });
                $('.module-view-submit-set').popover({
                    content: '模块设置',
                    trigger: 'hover focus',
                    theme: 'sm'
                });
                $('.module-view-submit-del').popover({
                    content: '模块移除',
                    trigger: 'hover focus',
                    theme: 'sm'
                });
            }

            // 关闭事件
            $('.layout-content').off('dragover');
            $('.layout-content').off('dragenter');
            $('.layout-content').off('drop');
        });
    });

    // 模块移除
    $(document).on('click', '.module-view-submit-del', function()
    {
        var $this = $(this);
        AMUI.dialog.confirm({
            title: '温馨提示',
            content: '移除后不可恢复、确定继续吗？',
            onConfirm: function(e)
            {
                // 模块容器
                var $module_view = $this.parents('.layout-content');

                // 移除模块
                $this.parents('.module-view').remove();

                // 无模块则添加提示信息
                if($module_view.find('.module-view').length <= 0)
                {
                    $module_view.html('<div class="layout-content-tips">模块内容区域</div>');
                }
            }
        });
    });

    // 模块设置
    $(document).on('click', '.module-view-submit-set', function()
    {
        // 基础
        var $parent = $(this).parents('.module-view-submit-container');
        var value = $parent.data('value') || null;
        var index = $parent.data('index') || null;
        var doc = $parent.data('doc') || null;
        if(value == null || index == null || doc == null)
        {
            Prompt('模块属性有误');
            return false;
        }

        // 公共数据
        var json = $(doc).attr('data-json') || null;
        if(json != null)
        {
            // 数据解析
            json = JSON.parse(decodeURIComponent(json)) || null;
        }

        // 配置模块
        var config_doc = '#offcanvas-module-config-'+value;
        var $config = $(config_doc);
        $config.attr('data-value', value);
        $config.attr('data-index', index);
        $config.attr('data-doc', doc);

        // 获取表单字段并赋空值
        if((json || null) == null)
        {
            var fields_dv = {
                "goods_order_by_type": 0,
                "goods_order_by_rule": 0,
                "view_list_show_style": 'routine',
                "view_list_number_sm": 2,
                "view_list_number_md": 5,
                "view_list_number_lg": 5,
                "goods_data_type": "goods"
            };
            json = GetFormVal(config_doc, true);
            for(var i in json)
            {
                json[i] = (fields_dv[i] == undefined) ? '' : fields_dv[i];
            }
        }

        // 展示模式默认值处理、默认常规模式
        if((json.view_list_show_style || null) == null)
        {
            json['view_list_show_style'] = 'routine';
        }

        // 根据模块类型处理
        switch(value)
        {
            // 图片模块
            case 'images' :
                // 图片处理
                var default_images = $config.data('default-images');
                var html = '<li>';
                    html += '<input type="text" name="content_images" value="'+(json.content_images || '')+'" data-validation-message="请上传图片" value="" required />';
                    html += '<img src="'+(json.content_images || default_images)+'" />';
                    html += '</li>';
                $config.find('ul.module-images-type-images-view').html(html);

                // 链接地址处理
                $config.find('.form-view-choice-container-content').html(ModuleConfigImagesToContentHtml(json.content_to_name || ''));
                break;

            // 多图
            case 'many-images' :
                // 多图图片
                var html = '';
                if((json.data_list || null) != null && json.data_list.length > 0)
                {
                    for(var i in json.data_list)
                    {
                        html += ModuleConfigManyImagesItemContentHtml(json.data_list[i]['images'], json.data_list[i]['type'], json.data_list[i]['name'], json.data_list[i]['value']);
                    }
                }
                $config.find('.config-many-images-container').html(html);
                break;

            // 视频
            case 'video' :
                // 视频地址
                $config.find('.module-video-type-view-video video').attr('src', json.content_video || '');

                // 封面图片
                var html = '';
                if((json.content_images || null) != null)
                {
                    html += '<li>';
                    html += '<input type="text" name="content_images" value="'+json.content_images+'" /><img src="'+json.content_images+'" />';
                    html += '<i>×</i>';
                    html += '</li>';
                }
                $config.find('.module-video-type-view-images').html(html);
                break;

            // 商品模块
            case 'goods' :
                var goods_data_type = json.goods_data_type || 'goods';
                switch(goods_data_type)
                {
                    // 商品
                    case 'goods' :
                        $config.find('.config-goods-list').html(ModuleConfigGoodsItemContentHtml(json.data_list));

                        // 清空分类选择
                        $config.find('.offcanvas-config-goods-category-container .form-view-choice-container-content').html(ModuleConfigGoodsCategoryContentHtml());
                        $config.find('input[name="goods_category_value"]').val('');
                        break;

                    // 商品分类
                    case 'category' :
                        var category = ((json.goods_category_value || null) == null) ? null : JSON.parse(decodeURIComponent(json.goods_category_value)) || null;
                        var name = (category == null || category.length <= 0) ? '' : category[category.length-1]['name'];
                        var html = ModuleConfigGoodsCategoryContentHtml(name);
                        $config.find('.offcanvas-config-goods-category-container .form-view-choice-container-content').html(html);

                        // 清空商品列表
                        $config.find('.config-goods-list').html('');
                        $config.find('input[name="goods_ids"]').val('');
                        break;

                    // 未定义
                    default :
                        console.info('模块组件未定义['+goods_data_type+']')
                }
                
                // tab处理
                var index = 0;
                $config.find('.am-tabs-nav li').each(function(k, v)
                {
                    if($(this).find('a').data('value') == goods_data_type)
                    {
                        index = k;
                    }
                });
                $config.find('.am-tabs-nav li').removeClass('am-active');
                $config.find('.am-tabs-nav li').eq(index).addClass('am-active');
                $config.find('.am-tabs-bd .am-tab-panel').removeClass('am-active');
                $config.find('.am-tabs-bd .am-tab-panel').eq(index).addClass('am-active');
                break;

            // 标题
            case 'title' :
                // 关键字
                var html = '';
                if((json.keywords_list || null) != null && json.keywords_list.length > 0)
                {
                    for(var i in json.keywords_list)
                    {
                        html += ModuleConfigTitleKeywordsContentHtml(json.keywords_list[i]);
                    }
                }
                $config.find('.config-title-container').html(html);

                // 链接地址处理
                $config.find('.form-view-choice-container-content').html(ModuleConfigImagesToContentHtml(json.content_to_name || ''));
                break;
        }
 
        // 表单数据赋值
        FormDataFill(json, config_doc);

        // 背景色组件处理
        ModuleColorpickerHandle($config);

        // 更新选择组件
        $config.find('.chosen-select').trigger('chosen:updated');

        // 打开弹窗
        $config.offCanvas('open');
    });

    // 配置图片 - 选择页面事件
    $offcanvas_config_images.on('click', '.form-view-choice-container-submit', function(e)
    {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 配置图片 - 选择页面 - 移除
    $offcanvas_config_images.on('click', '.form-view-choice-container-active i.am-icon-close', function(e)
    {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 页面选择 tab 切换事件
    $modal_pages_select.on('click', '.am-tabs-nav a', function()
    {
        // 所有处理
        $modal_pages_select.find('.am-tabs-bd ul li').removeClass('active');
        $modal_pages_select.find('.am-tabs-bd ul li a').attr('data-json', '');

        // 名称还原
        $modal_pages_select.find('.am-tabs-bd ul li').each(function(k, v)
        {
            var $o = $(this).find('a');
            $o.attr('data-json', '');
            $o.find('span').text($o.data('name'));
        });

        // 自定义链接清空
        $('.pages-custom-url-container input').val('');
    });

    // 页面选择切换
    $modal_pages_select.on('click',  '.am-tabs-bd ul li a', function()
    {
        // 选中状态
        var $parent = $(this).parents('.am-tab-panel');
        $parent.find('li').removeClass('active');
        $(this).parent().addClass('active');

        // 参数值
        var value = $(this).data('value') || null;
        var name = $(this).data('name') || null;
        var json = $(this).attr('data-json') || null;
        if(value == null || name == null)
        {
            Prompt('参数值有误');
            return false;
        }
        if(json != null)
        {
            json = JSON.parse(decodeURIComponent(json)) || null;
        }

        // 根据类型处理
        switch(value)
        {
            // 单一商品
            case 'goods' :
                // 初始化搜索数据
                $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> 请搜索商品</div>');
                $('.goods-page-container').html(PageLibrary());

                // 弹窗数据设置并打开
                var goods_ids = (json == null || (json.id || null) == null) ? '' : json.id;
                $popup_goods_select.attr('data-goods-ids', goods_ids);
                $popup_goods_select.attr('data-type', 'single-goods');
                $popup_goods_select.attr('data-is-single-choice', 1);
                $popup_goods_select.modal({closeViaDimmer: false});
                break;

            // 搜索页面
            case 'goods_search' :
                var index = 0;
                var type = 'category';
                var value = null;
                if(json != null && (json.type || null) != null)
                {
                    // 基础参数
                    var arr = ['category', 'brand', 'keywords', 'other'];
                    index = arr.indexOf(json.type);
                    type = json.type;
                    value = json.value || null;
                }

                // tab导航和容器
                $popup_goods_search.find('.am-tabs-nav li').removeClass('am-active');
                $popup_goods_search.find('.am-tabs-nav li').eq(index).addClass('am-active');
                $popup_goods_search.find('.am-tabs-bd .am-tab-panel').removeClass('am-active');
                $popup_goods_search.find('.am-tabs-bd .am-tab-panel').eq(index).addClass('am-active');

                // 数据处理
                $popup_goods_search.find('.form-container-category').find('.goods-category-select-2, .goods-category-select-3').html('').addClass('am-hide');
                $popup_goods_search.find('.form-container-category .already-select-tips').attr('data-value', '').addClass('am-hide').find('strong').text('');
                $popup_goods_search.find('.goods-category-choice-content ul li.active').removeClass('active');
                $popup_goods_search.find('.form-container-keywords input').val('');

                // 数据选中
                if(value != null)
                {
                    switch(type)
                    {
                        // 商品分类
                        case 'category' :
                            for(var i in value)
                            {
                                var $gcs = $popup_goods_search.find('.form-container-category .goods-category-select-'+(parseInt(i)+1));
                                if($gcs.length > 0 && $gcs.find('li').length > 0)
                                {
                                    $gcs.find('li').each(function(k, v)
                                    {
                                        if($(this).find('a').data('value') == value[i]['id'])
                                        {
                                            $(this).find('a').trigger('click');
                                        }
                                    });
                                }
                            }
                            break;

                        // 品牌
                        case 'brand' :
                            $popup_goods_search.find('.form-container-brand ul li').each(function(k, v)
                            {
                                if($(this).find('a').data('value') == value.id)
                                {
                                    $(this).addClass('active');
                                }
                            });
                            break;

                        // 关键字
                        case 'keywords' :
                            $popup_goods_search.find('.form-container-keywords input').val(value);
                            break;
                    }
                }

                $popup_goods_search.modal({closeViaDimmer: false});
                break;
        }
    });

    // 页面选择确认事件
    $modal_pages_select.on('click', '.pages-confirm-submit', function()
    {
        // 选中tab
        var index = $modal_pages_select.find('.am-tabs-nav li.am-active').index();

        // 参数值、自定义链接、常规页面选择
        if(index == 2)
        {
            var to_type = 'pages-custom-url';
            var to_name = '自定义链接';
            var to_value = GetFormVal('.pages-custom-url-container', true);
            var count = 0;
            for(var i in to_value)
            {
                if((to_value[i] || null) == null)
                {
                    count++;
                }
            }
            if(count >= Object.keys(to_value).length)
            {
                Prompt('请至少填写一个地址');
                return false;
            }
            to_value = encodeURIComponent(JSON.stringify(to_value));
        } else {
            var $obj = $modal_pages_select.find('.am-tab-panel.am-active ul li.active a');
            var to_type = $obj.data('value') || '';
            var to_name = $obj.data('name') || '';
            var to_value = $obj.attr('data-json') || '';
            var json = null;
            if(to_value != '')
            {
                json = JSON.parse(decodeURIComponent(to_value)) || null;
            }
            if(to_type == '' || to_name == '')
            {
                Prompt('请先选择页面');
                return false;
            }

            // 根据类型处理
            switch(to_type)
            {
                // 单一商品
                case 'goods' :
                    if(json == null)
                    {
                        Prompt('请选择商品');
                        return false;
                    }

                    // 选择位置是否存在
                    if($page_parent_obj == null)
                    {
                        Prompt('请先选择链接位置');
                        return false;
                    }

                    // 显示名称
                    to_name += '（'+json['title']+'）';
                    break;

                // 搜索页面
                case 'goods_search' :
                    if(json == null)
                    {
                        Prompt('请先配置商品搜索');
                        return false;
                    }

                    // 显示名称
                    to_name += ModuleConfigGoodsSearchPageShowName(json);
                    break;
            }
        }

        // 设置数据
        var $parent = $page_parent_obj.parents('.form-view-choice-container');
        var key = $parent.data('key') || null;
        var index = (key == null) ? '' : '_'+key;
        $parent.find('input[name="content_to_type'+index+'"]').val(to_type);
        $parent.find('input[name="content_to_name'+index+'"]').val(to_name);
        $parent.find('input[name="content_to_value'+index+'"]').val(to_value);
        $page_parent_obj.html(ModuleConfigImagesToContentHtml(to_name));
        $modal_pages_select.modal('close');
    });

    // 商品配置-选择商品和分类
    $offcanvas_config_goods.on('click', '.form-view-choice-container-submit', function()
    {
        var value = $(this).parents('.form-view-choice-container').data('value');
        switch(value)
        {
            // 商品
            case 'goods' :
                // 初始化搜索数据
                $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> 请搜索商品</div>');
                $('.goods-page-container').html(PageLibrary());

                // 弹窗数据设置并打开
                var goods_ids = OffcanvasModuleConfigGoodsIds();
                $popup_goods_select.attr('data-goods-ids', goods_ids);
                $popup_goods_select.attr('data-type', 'list-goods');
                $popup_goods_select.attr('data-is-single-choice', 0);
                $popup_goods_select.modal({closeViaDimmer: false});
                break;

            // 商品分类
            case 'category' :
                // 分类数据
                var json = $offcanvas_config_goods.find('.offcanvas-config-goods-category-container input[name="goods_category_value"]').val() || null;
                if(json != null)
                {
                    json = JSON.parse(decodeURIComponent(json)) || null;
                }
                if(json == null)
                {
                    // 数据清空
                    $popup_goods_category.find('.form-container-category').find('.goods-category-select-2, .goods-category-select-3').html('').addClass('am-hide');
                    $popup_goods_category.find('.form-container-category .already-select-tips').attr('data-value', '').addClass('am-hide').find('strong').text('');
                    $popup_goods_category.find('.goods-category-choice-content ul li.active').removeClass('active');
                } else {
                    // 生成选择的数据
                    for(var i in json)
                    {
                        var $gcs = $popup_goods_category.find('.form-container-category .goods-category-select-'+(parseInt(i)+1));
                        if($gcs.length > 0 && $gcs.find('li').length > 0)
                        {
                            $gcs.find('li').each(function(k, v)
                            {
                                if($(this).find('a').data('value') == json[i]['id'])
                                {
                                    $(this).find('a').trigger('click');
                                }
                            });
                        }
                    }
                }

                $popup_goods_category.modal({closeViaDimmer: false});
                break;

            default :
                Prompt('类型事件未定义['+value+']')
        }
    });

    // 商品配置-商品选择-移除
    $(document).on('click', '#offcanvas-module-config-goods .config-goods-list li a.am-close', function()
    {
        // 商品id处理
        var value = $(this).parent().data('gid');
        var goods_ids = $offcanvas_config_goods.find('input[name="goods_ids"]').val() || null;
        if(goods_ids != null)
        {
            goods_ids = goods_ids.split(',');
            for(var i in goods_ids)
            {
                if(goods_ids[i] == value)
                {
                    goods_ids.splice(i, 1);
                }
            }
            goods_ids = (goods_ids.length > 0) ? goods_ids.join(',') : '';
        }
        $offcanvas_config_goods.find('input[name="goods_ids"]').val(goods_ids || '');

        // 移除元素
        $(this).parent().remove();
    });

    // 商品配置-分类选择确认
    $popup_goods_category.on('click', '.confirm-submit', function()
    {
        // 分类数据
        var json = $popup_goods_category.find('.already-select-tips').attr('data-value') || null;
        if(json != null)
        {
            json = JSON.parse(decodeURIComponent(json)) || null;
        }
        if(json == null)
        {
            Prompt('请先选择商品分类');
            return false;
        }

        // 当前选择的节点
        var data = json[json.length-1];
        var html = ModuleConfigGoodsCategoryContentHtml(data.name);
        var $offcanvas_category = $offcanvas_config_goods.find('.offcanvas-config-goods-category-container');
        $offcanvas_category.find('.form-view-choice-container-content').html(html);
        $offcanvas_category.find('input[name="goods_category_value"]').val(encodeURIComponent(JSON.stringify(json)));
        $popup_goods_category.modal('close');
    });

    // 商品配置-分类选择确认-移除
    $offcanvas_config_goods.on('click', '.offcanvas-config-goods-category-container .form-view-choice-container-active i.am-icon-close', function()
    {
        var $parent = $(this).parents('.form-view-choice-container-content');
        $parent.html('<a href="javascript:;" class="form-view-choice-container-submit">请选择商品分类</a>');
        $parent.parent().find('input[name="goods_category_value"]').val('');
        return false;
    });

    // 商品配置-tab切换
    $offcanvas_config_goods.on('click', '.am-tabs-nav a', function()
    {
        $offcanvas_config_goods.find('input[name="goods_data_type"]').val($(this).data('value') || 'goods');
    });


    // 商品搜索选择
    // 分页
    $('.goods-page-container').html(PageLibrary());

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
        var category_field = $('.forth-selection-container').find('input[name="category_field"]').val() || '';
        var category_id = $('.forth-selection-form-category').val();
        var keywords = $('.forth-selection-form-keywords').val();
        var goods_ids = $popup_goods_select.attr('data-goods-ids') || '';

        var $this = $(this);
        $this.button('loading');
        $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> '+($('.goods-list-container').data('loading-msg'))+'</div>');
        $.ajax({
            url: url,
            type: 'post',
            data: {"page":page, "category_field":category_field, "category_id":category_id, "keywords":keywords, "goods_ids":goods_ids},
            dataType: 'json',
            success:function(res)
            {
                $this.button('reset');
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
            error:function(res)
            {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || '异常错误';
                Prompt(msg, null, 30);
                $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> '+msg+'</div>');
            }
        });
    });

    // 商品添加/删除
    $(document).on('click', '.goods-list-container .goods-add-submit, .goods-list-container .goods-del-submit', function()
    {
        // 基础参数
        var is_single_choice = parseInt($popup_goods_select.attr('data-is-single-choice')) || 0;
        var goods_ids = $popup_goods_select.attr('data-goods-ids') || null;
            goods_ids = (goods_ids == null) ? [] : goods_ids.split(',');

        // 商品信息
        var $this = $(this);
        var type = $this.data('type');
        var $parent = $this.parents('li');
        var goods_id = $parent.data('gid');
        var goods_title = $parent.data('title');
        var goods_url = $parent.data('url');
        var goods_img = $parent.data('img');

        // 是否单选
        if(is_single_choice == 1)
        {
            // 还原选项
            $popup_goods_select.find('.goods-list-container li').each(function(k, v)
            {
                $(this).find('.icon-submit-container').html($(this).data('add-html'));
            });

            if(type == 'add')
            {
                goods_ids = [goods_id];
                $parent.find('.icon-submit-container').html($parent.data('del-html'));
            } else {
                goods_ids = [];
            }
        } else {
            // 商品是否已经添加
            var index = goods_ids.indexOf(goods_id.toString());
            if(index == -1)
            {
                goods_ids.push(goods_id);
            } else {
                goods_ids.splice(index, 1); 
            }
            var icon_html = $parent.data((type == 'add' ? 'del' : 'add')+'-html');
            $this.parent().html(icon_html);
        }

        // 选择的商品id赋值
        $popup_goods_select.attr('data-goods-ids', goods_ids.join(','));
    });

    // 商品选择确认事件
    $popup_goods_select.on('click', '.confirm-submit', function()
    {
        // 已选商品
        var goods_ids = $popup_goods_select.attr('data-goods-ids') || null;
        if(goods_ids == null)
        {
            Prompt('请先选择商品');
            return false;
        }

        // 基础参数
        var $this = $(this);
        var url = $('.forth-selection-container').data('data-url');
        var type = $popup_goods_select.attr('data-type') || null;
        var is_single_choice = parseInt($popup_goods_select.attr('data-is-single-choice')) || 0;

        // 获取商品
        $this.button('loading');
        $.ajax({
            url: url,
            type: 'post',
            data: {"goods_ids":goods_ids},
            dataType: 'json',
            success:function(res)
            {
                $this.button('reset');
                if(res['code'] == 0)
                {
                    if((res.data || null) != null && res.data.length > 0)
                    {
                        // 根据类型处理不同业务逻辑
                        var data = res.data;
                        switch(type)
                        {
                            // 单一商品选择
                            case 'single-goods' :
                                var $single_goods = $modal_pages_select.find('.am-tabs-bd ul li.page-goods a');
                                $single_goods.find('span').text($single_goods.data('name')+'（'+data[0]['title']+'）');
                                $single_goods.attr('data-json', encodeURIComponent(JSON.stringify(data[0])));
                                break;

                            // 列表商品
                            case 'list-goods' :
                                // 商品id赋值
                                var goods_ids = data.map(function(e){return e.id;}).join(',');
                                $offcanvas_config_goods.find('input[name="goods_ids"]').val(goods_ids);
                                
                                // html 接
                                $offcanvas_config_goods.find('.config-goods-list').html(ModuleConfigGoodsItemContentHtml(data));
                                break;
                        }

                        // 关闭商品窗口
                        $popup_goods_select.modal('close');

                        // 清空选择的商品id
                        $popup_goods_select.attr('data-goods-ids', '');
                    } else {
                        Prompt('商品信息为空');
                    }
                } else {
                    Prompt(res.msg);
                }                
            },
            error:function(res)
            {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || '异常错误';
                Prompt(msg, null, 30);
            }
        });
    });

    // 商品搜索tab类型切换事件
    $popup_goods_search.on('click', '.am-tabs-nav a', function()
    {
        $popup_goods_search.find('.form-container-category').find('.goods-category-select-2, .goods-category-select-3').html('').addClass('am-hide');
        $popup_goods_search.find('.form-container-category .already-select-tips').attr('data-value', '').addClass('am-hide').find('strong').text('');
        $popup_goods_search.find('.goods-category-choice-content ul li.active').removeClass('active');
        $popup_goods_search.find('.form-container-keywords input').val('');
    });

    // 分类选择切换
    $(document).on('click',  '.layout-category-choice .goods-category-choice-content ul li a', function()
    {
        // 父级
        var $parents = $(this).parents('.goods-category-choice');

        // 选中
        $(this).parent().addClass('active').siblings().removeClass('active');

        // 分类数据
        var data = $(this).data('json') || null;
        if(data != null)
        {
            data = JSON.parse(decodeURIComponent(data)) || null;
        }
        
        // 参数
        var level = $(this).parents('ul').data('level') || 1;
        var level_next = level+1;

        // 拼接html
        if(data != null && level < 3)
        {
            var html = '';
            for(var i in data)
            {
                var json = (data[i]['items'] || null) == null ? '' : encodeURIComponent(JSON.stringify(data[i]['items']));
                html += '<li><a href="javascript:;" data-json="'+json+'" data-value="'+data[i]['id']+'"><span>'+data[i]['name']+'</span>';
                if((data[i]['items'] || null) != null)
                {
                    html += '<i class="am-icon-angle-double-right am-fr"></i>';
                }
                html += '</a></li>';
            }
            $parents.find('.goods-category-select-'+level_next).html(html).removeClass('am-hide');
        }

        // 级别数据处理
        if(data == null)
        {
            $parents.find('.goods-category-select-'+level_next).addClass('am-hide').html('');
        } else {
            $parents.find('.goods-category-select-'+level_next).removeClass('am-hide');
        }

        // 选择第一级的时候隐藏第三级
        if(level == 1)
        {
            $parents.find('.goods-category-select-3').addClass('am-hide').html('');
        }

        // 提示信息展示
        var text = '';
        var value = [];
        $parents.find('ul li.active').each(function(k, v)
        {
            if(k > 0)
            {
                text += ' > ';
            }
            var name = $(this).find('a span').text();
            value.push({"id":$(this).find('a').data('value'), "name":name});
            text += name;
        });
        var $tips = $parents.find('.already-select-tips');
        $tips.removeClass('am-hide');
        $tips.find('strong').text(text);

        // 选择数据
        $tips.attr('data-value', encodeURIComponent(JSON.stringify(value)));
    });

    // 左侧配置 - 多图- 多图添加图片
    $offcanvas_config_many_images.on('click', '.config-many-images-item-add', function()
    {
        $offcanvas_config_many_images.find('.config-many-images-container').append(ModuleConfigManyImagesItemContentHtml());
    });

    // 配置多图 - 选择页面事件
    $offcanvas_config_many_images.on('click', '.form-view-choice-container-submit', function(e)
    {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 配置多图 - 链接地址 - 移除
    $offcanvas_config_many_images.on('click', '.form-view-choice-container-active i.am-icon-close', function(e)
    {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 左侧配置 - 配置多图 - 移除
    $(document).on('click', '#offcanvas-module-config-many-images .config-many-images-container .am-panel a.am-close', function()
    {
        $(this).parent().remove();
    });

    // 展示模式切换
    $(document).on('click', 'input[name="view_list_show_style"]', function()
    {
        $base_show_style_value_obj = $(this).parents('.config-view-show-style').find('input[name="view_list_show_style_value"]');
        switch($(this).val())
        {
            // 滚动
            case 'rolling' :
                // 数据填充
                var json = ViewRollingShowStyleValueHandle($base_show_style_value_obj.val());
                if(json['item_margin'] <= 0)
                {
                    json['item_margin'] = '';
                }
                FormDataFill(json, '#modal-module-rolling-config');

                // 开关状态
                $modal_rolling_config.find('input[name="is_auto_play"]').bootstrapSwitch('state', json.is_auto_play);
                $modal_rolling_config.find('input[name="is_nav_dot"]').bootstrapSwitch('state', json.is_nav_dot);

                // 开启弹窗
                $modal_rolling_config.modal({
                    width: 260,
                    height: 370,
                    closeViaDimmer: false
                });
                break;

            // 列表
            case 'list' :
                // 数据填充
                var json = ViewListShowStyleValueHandle($base_show_style_value_obj.val());
                if(json['style_margin'] <= 0)
                {
                    json['style_margin'] = '';
                }
                FormDataFill(json, '#modal-module-list-config');

                // 开启弹窗
                $modal_list_config.modal({
                    width: 260,
                    height: 225,
                    closeViaDimmer: false
                });
                break;

            default :
                $base_show_style_value_obj.val('');
        }
    });

    // 左侧配置 - 标题 - 右侧按钮 - 选择页面事件
    $offcanvas_config_title.on('click', '.form-view-choice-container-submit', function(e)
    {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 标题 - 右侧按钮 - 移除
    $offcanvas_config_title.on('click', '.form-view-choice-container-active i.am-icon-close', function(e)
    {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 左侧配置 - 标题 - 添加关键字
    $offcanvas_config_title.on('click', '.config-title-item-add', function()
    {
        ModuleConfigTitleKeywordsOpen();
    });

    // 左侧配置 - 标题 - 关键字 - 编辑
    $(document).on('click', '#offcanvas-module-config-title .config-title-container li a.am-icon-edit', function()
    {
        var $parent = $(this).parent();
        $base_title_keywords_obj = $parent;
        ModuleConfigTitleKeywordsOpen(1, $parent.find('input').val() || null);
    });

    // 左侧配置 - 标题 - 关键字 - 移除
    $(document).on('click', '#offcanvas-module-config-title .config-title-container li a.am-icon-remove', function()
    {
        $(this).parents('li').remove();
    });

    // 左侧配置 - 标题 - 关键字 - 选择页面事件
    $modal_title_keywords.on('click', '.form-view-choice-container-submit', function(e)
    {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 标题 - 关键字 - 移除
    $modal_title_keywords.on('click', '.form-view-choice-container-active i.am-icon-close', function(e)
    {
        OffcanvasConfigPagesRemove($(this), e);
    });


    // 页面布局数据保存
    var $layout_operate_container = $('.layout-operate-container');
    $layout_operate_container.on('click', 'button', function()
    {
        // 基础配置
        var data = $layout_operate_container.attr('data-json') || null;
        if(data != null)
        {
            data = JSON.parse(decodeURIComponent(data)) || null;
        }
        if(data == null)
        {
            data = {};
        }

        // 设计配置
        data['config'] = JSON.stringify(LayoutViewConfig());

        // 保存数据
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: $layout_operate_container.data('save-url'),
            type: 'post',
            data: data,
            dataType: 'json',
            success:function(res)
            {
                if(res['code'] == 0)
                {
                    Prompt(res.msg, 'success');
                    setTimeout(function()
                    {
                        var url = $layout_operate_container.find('a').attr('href') || null;
                        if(url == null || url == 'javascript:;')
                        {
                            window.location.reload();
                        } else {
                            window.location.href = url;
                        }
                    }, 1500);
                } else {
                    $this.button('reset');
                    Prompt(res.msg);
                }                
            },
            error:function(xhr, type)
            {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || '异常错误';
                Prompt(msg, null, 30);
            }
        });
    });
});