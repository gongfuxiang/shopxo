// 公共列表 form 搜索条件
FromInit('form.form-validation-layout-config');
FromInit('form.form-validation-module-offcanvas-images');
FromInit('form.form-validation-module-offcanvas-many-images');
FromInit('form.form-validation-module-offcanvas-images-text');
FromInit('form.form-validation-module-offcanvas-images-magic-cube');
FromInit('form.form-validation-module-offcanvas-video');
FromInit('form.form-validation-module-offcanvas-goods');
FromInit('form.form-validation-module-offcanvas-title');
FromInit('form.form-validation-module-offcanvas-custom');
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
var $layout = $('.layout-container');
var $offcanvas_layout_config = $('#offcanvas-layout-config');
var $offcanvas_config_images = $('#offcanvas-module-config-images');
var $offcanvas_config_many_images = $('#offcanvas-module-config-many-images');
var $offcanvas_config_images_text = $('#offcanvas-module-config-images-text');
var $offcanvas_config_images_magic_cube = $('#offcanvas-module-config-images-magic-cube');
var $offcanvas_config_video = $('#offcanvas-module-config-video');
var $offcanvas_config_goods = $('#offcanvas-module-config-goods');
var $offcanvas_config_title = $('#offcanvas-module-config-title');
var $offcanvas_config_custom = $('#offcanvas-module-config-custom');
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
var layout_module_type_arr = {};
$('#renovation-tabs-module button').each(function (k, v) {
    layout_module_type_arr[$(this).data('value')] = $(this).text();
});

/**
 * 模块拖拽初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-13
 * @desc    description
 * @param   {[object]}        event [初始化容器]
 */
function ModuleDragSortInit (event) {
    // 是否指定初始化容器
    if ((event || null) == null) {
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
function StructureDragHtmlCreate (value) {
    // 基础
    var switch_on = $layout.data('switch-on-text') || '开启';
    var switch_off = $layout.data('switch-off-text') || '关闭';
    var set_title = $layout.data('layout-set-title') || '布局设置';
    var del_title = $layout.data('layout-del-title') || '布局移除';
    var content_tips = $layout.data('layout-content-tips') || '模块内容区域';
    var html = '<div class="layout-view" data-value="' + value + '">';
    html += '<i class="layout-view-dragenter-icon am-icon-sort-asc am-icon-lg am-hide"></i>';
    html += '<div class="layout-content-submit drag-submit">';
    html += '<input type="checkbox" data-am-switch class="switch-checkbox am-switch-mini" checked="true" data-size="xs" data-on-color="success" data-off-color="warning" data-off-text="' + switch_off + '" data-on-text="' + switch_on + '" />';
    html += ' <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-square-o layout-submit layout-submit-set"> ' + set_title + '</button>';
    html += ' <button type="button" class="am-btn am-btn-danger am-radius am-btn-xs am-icon-trash-o layout-submit layout-submit-del"> ' + del_title + '</button>';
    html += '</div>';

    // 容器设置
    var content_submit = '<div class="layout-content-submit-container">';
    content_submit += '<button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-gear layout-submit layout-content-submit-set"></button>';
    content_submit += '</div>';

    // 默认内容提示信息
    var content_tips = '<div class="layout-content-tips">' + content_tips + '</div>';

    // 根据布局类型处理
    var arr = value.toString().split(':');
    var length = arr.length;
    if (length <= 1) {
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
        for (var i in arr) {
            html += '<div class="am-u-md-' + arr[i] + '">';
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
function RenovationModuleDragHtmlCreate (value) {
    // 根据模块类型处理
    if ((layout_module_type_arr[value] || null) == null) {
        Prompt(($layout.data('module-not-exist-tips') || '模块未定义') + '[' + value + ']');
        return false;
    }

    // 基础
    var config_first_tips = $layout.data('config-first-tips') || '请配置';
    var index = parseInt(Math.random() * 1000001);
    var doc = 'module-content-index-' + value + '-' + index;
    var html = '<div class="module-view">';
    html += '<div class="module-view-submit-container" data-value="' + value + '" data-index="' + index + '" data-doc=".' + doc + '">';
    html += '<button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-reorder layout-submit module-view-submit-drag"></button>';
    html += ' <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-folder-open-o layout-submit module-view-submit-set"></button>';
    html += ' <button type="button" class="am-btn am-btn-danger am-radius am-btn-xs am-icon-trash-o layout-submit module-view-submit-del"></button>';
    html += '</div>';
    html += '<div class="module-content module-content-type-' + value + ' ' + doc + '">';
    html += '<div class="am-text-center am-padding-vertical-sm am-text-primary">' + config_first_tips + layout_module_type_arr[value] + '</div>';
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
function ModuleToPrompt (to_name) {
    Prompt(to_name || ($layout.data('url-not-set-tips') || '未设置链接地址'), 'warning');
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
 * @param   {[array]}         exclude       [排除css]
 */
function StyleBaseHandle (data, key, replace_rules, exclude) {
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
    for (var i in arr) {
        var type = arr[i]['type'];
        var value = arr[i]['value'];
        var unit = arr[i]['unit'];
        var t = data[key + type + '_top'] || value;
        var r = data[key + type + '_right'] || value;
        var b = data[key + type + '_bottom'] || value;
        var l = data[key + type + '_left'] || value;
        if ((t != 0 || r != 0 || b != 0 || l != 0) || (t != '' || r != '' || b != '' || l != '')) {
            if ((exclude || null) != null) {
                if (exclude.indexOf(arr[i][t]) != -1) {
                    t = 0;
                }
                if (exclude.indexOf(arr[i][r]) != -1) {
                    r = 0;
                }
                if (exclude.indexOf(arr[i][b]) != -1) {
                    b = 0;
                }
                if (exclude.indexOf(arr[i][l]) != -1) {
                    l = 0;
                }
            }
            style += arr[i]['css'] + ':' + t + unit + ' ' + r + unit + ' ' + b + unit + ' ' + l + unit + ';';
        }
    }

    // 单个处理
    var arr2 = [
        { type: "border_style", css: "border-style", unit: "" },
        { type: "border_width", css: "border-width", unit: "px" },
        { type: "border_color", css: "border-color", unit: "" },
        { type: "border_radius", css: "border-radius", unit: "px" },
        { type: "background_color", css: "background-color", unit: "" },
        { type: "color", css: "color", unit: "" },
        { type: "align", css: "text-align", unit: "" },
        { type: "font_size", css: "font-size", unit: "px" },
        { type: "margin", css: "margin", unit: "px" },
        { type: "padding", css: "padding", unit: "px" },
        { type: "height", css: "height", unit: "px" },
        { type: "width", css: "width", unit: "px" },
        { type: "max_height", css: "max-height", unit: "px" },
        { type: "max_width", css: "max-width", unit: "px" }
    ];
    for (var i in arr2) {
        if ((data[key + arr2[i]['type']] || null) != null) {
            if ((exclude || null) == null || exclude.indexOf(arr2[i]['css']) == -1) {
                // 样式值
                var v = data[key + arr2[i]['type']] + arr2[i]['unit'];

                // 替换规则
                // rules {"field":{"value":"hello","var":"var"}}
                if ((replace_rules || null) != null && (replace_rules[arr2[i]['type']] || null) != null) {
                    var rules = replace_rules[arr2[i]['type']];
                    var reg = new RegExp(rules['var'], 'g');
                    v = rules['value'].replace(reg, data[key + arr2[i]['type']]);
                }
                style += arr2[i]['css'] + ':' + v + ';';
            }
        }
    }

    return style;
}

/**
 * 表单回调数据列表处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-11-25
 * @desc    description
 * @param   {[object]}        data   [表单数据]
 * @param   {[object]}        fields [处理字段]
 */
function FormBackDataListHandle (data, fields) {
    var key_temp = [];
    var data_list = [];
    for (var i in data) {
        var loc = i.lastIndexOf('_');
        if (loc != -1) {
            var key = i.substr(0, loc + 1);
            var last = i.substr(loc + 1);
            for (var f in fields) {
                if (key == f) {
                    // 临时索引记录
                    var index = key_temp.indexOf(last);
                    if (index == -1) {
                        key_temp.push(last);
                        index = key_temp.length - 1;
                    }

                    // 数据组合
                    if (data_list[index] == undefined) {
                        data_list[index] = {};
                    }
                    data_list[index][fields[f]] = (fields[f] != 'value' || (data[i] || null) == null) ? data[i] : (JSON.parse(decodeURIComponent(data[i])) || '');
                    delete data[i];
                }
            }
        }
    }
    return {
        data: data,
        data_list: data_list
    }
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
function MediaFixedHandle (data) {
    // 文件容器
    var media_container_ent = '';
    // 文件容器样式
    var media_container_style = StyleBaseHandle(data, 'style_media_fixed_');
    if ((media_container_style || null) != null) {
        media_container_ent += 'module-fixed-doc ';
    }
    // 文件容器加上居中、避免容器没居中导致内容居中无效
    if ((data['style_media_fixed_is_auto'] || 0) == 1) {
        media_container_ent += 'module-fixed-doc-ent-auto ';
    }

    // 文件
    var media_ent = '';
    var arr = ['width', 'height', 'auto', 'cover'];
    for (var i in arr) {
        var key = 'style_media_fixed_is_' + arr[i];
        if ((data[key] || 0) == 1) {
            media_ent += 'module-fixed-doc-ent-' + arr[i] + ' ';
        }
    }

    // 鼠标悬停在图片上方放大
    if (parseInt(data.style_mouse_hover_images_amplify_value || 0) == 1) {
        media_ent += 'module-mouse-hover-images-amplify ';
    }

    return {
        "media_container_ent": media_container_ent,
        "media_container_style": media_container_style,
        "media_ent": media_ent
    }
}

/**
 * 布局-容器设置处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackLayoutConfigHandle (data) {
    // 基础信息
    if ($layout_content_obj == null) {
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
    for (var a in size_arr) {
        for (var b in type_arr) {
            for (var c in angle_arr) {
                var key = b.replace('{var}', size_arr[a]) + '_' + angle_arr[c];
                if ((data[key] || 0) > 0) {
                    ent += type_arr[b].replace('{var}', size_arr[a]) + '-' + angle_arr[c] + '-' + data[key] + ' ';
                }
            }
        }
    }

    // 边线类型
    for (var a in size_arr) {
        for (var b in angle_arr) {
            var key = 'style_' + size_arr[a] + '_border_style_' + angle_arr[b];
            if ((data[key] || null) != null) {
                ent += 'layout-' + size_arr[a] + '-border-' + angle_arr[b] + '-' + data[key] + ' ';
            }
        }
    }
    // 圆角
    for (var i in size_arr) {
        var key = 'style_' + size_arr[i] + '_border_radius';
        if ((data[key] || 0) > 0) {
            ent += 'layout-' + size_arr[i] + '-border-radius-' + data[key] + ' ';
        }
    }
    // 系统标准限宽、兼容老版本的参数
    if ((data['style_width_max_limit_value'] || null) != null || (data['width_max_limit_value'] || null) != null) {
        ent += 'am-container ';
    }

    // 样式处理
    var style = '';

    // 背景色
    if ((data['style_background_color'] || null) != null) {
        style += 'background-color:' + data['style_background_color'] + ';';
    }
    // 边线颜色
    if ((data['style_border_color'] || null) != null) {
        style += 'border-color:' + data['style_border_color'] + ';';
    }

    // 背景样式
    // 是否不允许重复
    if (parseInt(data.style_background_images_no_repeat || 0) == 1) {
        style += 'background-repeat:no-repeat;';
    }
    // 是否铺满
    if (parseInt(data.style_background_images_size_cover || 0) == 1) {
        style += 'background-size:cover;';
    }
    // 是否居中
    if (parseInt(data.style_background_images_position_center || 0) == 1) {
        style += 'background-position:center;';
    }
    // 背景图片
    var temp_style = style;
    if ((data.style_background_images || null) != null) {
        temp_style += 'background-image:url(' + data.style_background_images + ');';
    }

    // 类和样式处理
    $layout_content_obj.attr('class', $offcanvas_layout_config.attr('data-ent') + ' ' + ent);
    $layout_content_obj.attr('style', temp_style);

    // 数据加入配置
    data['frontend_config'] = {
        style: style,
        ent: ent
    }
    $layout_content_obj.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
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
function FormBackModuleConfigImagesHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_images.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 图片必须
    if ((data.content_images || null) == null) {
        Prompt($layout.data('upload-images-tips') || '请上传图片');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 图片样式
    var media_fixed = MediaFixedHandle(data);

    // html拼接
    var html = '<div class="module-images-container" style="' + style + '">';
    html += '<a href="javascript:ModuleToPrompt(\'' + (data.content_to_name || '') + '\');" class="' + media_fixed.media_container_ent + '" style="' + media_fixed.media_container_style + '">';
    html += '<img src="' + data['content_images'] + '" class="' + media_fixed.media_ent + '" style="' + media_fixed.media_container_style + '" />';
    html += '</a>';
    html += '</div>';
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        style: style,
        media_fixed: media_fixed
    }
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
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
function FormBackModuleConfigManyImagesHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_many_images.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 数据字段
    var fields = {
        content_images_: "images",
        content_to_name_: "name",
        content_to_type_: "type",
        content_to_value_: "value"
    };
    var res = FormBackDataListHandle(data, fields);
    data = res.data;
    var data_list = res.data_list;
    if (data_list.length <= 0) {
        Prompt($layout.data('config-images-tips') || '请先添加图片并配置');
        return false;
    }
    for (var i in data_list) {
        if ((data_list[i]['images'] || null) == null) {
            Prompt($layout.data('upload-images-tips') || '请上传图片');
            return false;
        }
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 展示模式
    var show_style = data.view_list_show_style || 'routine';

    // 图片样式
    var media_fixed = MediaFixedHandle(data);

    // 数据项html
    var item_html = '';
    if (show_style != 'list') {
        for (var i in data_list) {
            item_html += '<li>';
            item_html += '<a href="javascript:ModuleToPrompt(\'' + (data_list[i]['name'] || '') + '\');" class="' + media_fixed.media_container_ent + '" style="' + media_fixed.media_container_style + '">'
            item_html += '<img src="' + data_list[i]['images'] + '" class="' + media_fixed.media_ent + '" style="' + media_fixed.media_container_style + '" />';
            item_html += '</a>';
            item_html += '</li>';
        }
    }

    // html拼接
    var html = '<div class="module-slider-container" style="' + style + '">';

    // 初始化参数
    var option = {};

    // 展示模式处理
    var nav_dot_ent = '';
    var list_ent = '';
    switch (show_style) {
        // 滚动
        case 'rolling':
            // 参数处理
            var show_style_value = ViewRollingShowStyleValueHandle(data.view_list_show_style_value);

            // 是否展示导航点
            nav_dot_ent = show_style_value.is_nav_dot ? '' : 'slides-rolling-not-dot';

            // html拼接
            html += '<div class="am-slider am-slider-default am-slider-carousel ' + nav_dot_ent + '">';
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
        case 'list':
            // 参数处理
            var show_style_value = ViewListShowStyleValueHandle(data.view_list_show_style_value);

            // 列表展示数量
            var sm = show_style_value.view_list_number_sm || 2;
            var md = show_style_value.view_list_number_md || 5;
            var lg = show_style_value.view_list_number_lg || 5;

            // 外边距
            var item_margin = parseInt(show_style_value.style_margin || 0);

            // 数据项样式处理
            var item_style = (item_margin > 0) ? 'margin:' + item_margin + 'px 0 0 ' + item_margin + 'px;' : '';

            // 设置了外边距，则计算平均移动值
            var avg = (item_margin > 0) ? 'module-list-content-avg-' + item_margin : '';

            // 列表class
            list_ent = avg + ' module-list-sm-' + sm + ' module-list-md-' + md + ' module-list-lg-' + md + ' ';

            html += '<ul class="module-list-content ' + list_ent + '">';
            for (var i in data_list) {
                html += '<li>';
                html += '<div class="module-item" style="' + item_style + '">';
                html += '<a href="javascript:ModuleToPrompt(\'' + (data_list[i]['name'] || '') + '\');" class="' + media_fixed.media_container_ent + '" style="' + media_fixed.media_container_style + '">'
                html += '<img src="' + data_list[i]['images'] + '" class="' + media_fixed.media_ent + '" style="' + media_fixed.media_container_style + '" />';
                html += '</a>';
                html += '</div>';
                html += '</li>';
            }
            html += '</ul>';
            break;

        // 常规、默认
        default:
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
        style: style,
        item_style: item_style,
        nav_dot_ent: nav_dot_ent,
        list_ent: list_ent,
        media_fixed: media_fixed
    }
    data['data_list'] = data_list;
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));

    // 滚动初始化
    if (show_style != 'list') {
        $doc.find('.am-slider').flexslider(option);
    }
    $offcanvas_config_many_images.offCanvas('close');
}

/**
 * 模块-图文处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigImagesTextHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_images_text.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 数据字段
    var fields = {
        content_images_: "images",
        content_title_: "title",
        content_title_style_color_: "title_style_color",
        content_title_style_font_size_: "title_style_font_size",
        content_title_style_align_: "title_style_align",
        content_title_style_margin_top_: "title_style_margin_top",
        content_title_style_margin_right_: "title_style_margin_right",
        content_title_style_margin_bottom_: "title_style_margin_bottom",
        content_title_style_margin_left_: "title_style_margin_left",
        content_desc_: "desc",
        content_desc_style_color_: "desc_style_color",
        content_desc_style_font_size_: "desc_style_font_size",
        content_desc_style_align_: "desc_style_align",
        content_desc_style_margin_top_: "desc_style_margin_top",
        content_desc_style_margin_right_: "desc_style_margin_right",
        content_desc_style_margin_bottom_: "desc_style_margin_bottom",
        content_desc_style_margin_left_: "desc_style_margin_left",
        content_to_name_: "name",
        content_to_type_: "type",
        content_to_value_: "value"
    };
    var res = FormBackDataListHandle(data, fields);
    data = res.data;
    var data_list = res.data_list;
    if (data_list.length <= 0) {
        Prompt($layout.data('config-images-text-tips') || '请先添加图文并配置');
        return false;
    }
    for (var i in data_list) {
        if ((data_list[i]['images'] || null) == null && (data_list[i]['title'] || null) == null) {
            Prompt($layout.data('upload-images-or-title-tips') || '图片和标题必填一项');
            return false;
        }
    }
    // 展示模式
    var show_style = data.view_list_show_style || null;
    if (show_style == null) {
        Prompt($layout.data('data-show-modal-tips') || '请选择数据展示模式');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 图片样式
    var media_fixed = MediaFixedHandle(data);

    // html拼接
    var html = '<div class="module-slider-container" style="' + style + '">';
    // 初始化参数
    var option = {};

    // 参数处理
    var show_style_value = ViewListShowStyleValueHandle(data.view_list_show_style_value);

    // 外边距
    var item_margin = parseInt(show_style_value.style_margin || 0);

    // 数据项样式处理
    var item_style = (item_margin > 0 && show_style != 'rolling') ? 'margin:' + item_margin + 'px 0 0 ' + item_margin + 'px;' : '';

    // 内容处理
    var item_html = '';
    var item_right_style = '';
    var item_field_style = [];
    for (var i in data_list) {
        // 字段样式
        if (item_field_style[i] == undefined) {
            item_field_style[i] = {};
        }
        item_field_style[i]['title'] = StyleBaseHandle(data_list[i], 'title_style_');
        item_field_style[i]['desc'] = StyleBaseHandle(data_list[i], 'desc_style_');

        // 拼接html
        item_html += '<li>';
        item_html += '<div class="module-item" style="' + item_style + '">';
        switch (show_style) {
            // 左右
            case 'leftright':
                var style_media_fixed_width = parseInt(data.style_media_fixed_width || 0);
                item_right_style = 'margin-left:10px;' + ((style_media_fixed_width > 0) ? 'width: calc(100% - ' + (style_media_fixed_width + 10) + 'px);' : '');
                if ((data_list[i]['images'] || null) != null) {
                    item_html += '<a href="javascript:ModuleToPrompt(\'' + (data_list[i]['name'] || '') + '\');" class="am-fl ' + media_fixed.media_container_ent + '" style="' + media_fixed.media_container_style + '">';
                    item_html += '<img src="' + data_list[i]['images'] + '" class="' + media_fixed.media_ent + '" style="' + media_fixed.media_container_style + '" />';
                    item_html += '</a>';
                }
                item_html += '<div class="am-fl" style="' + item_right_style + '">';
                if ((data_list[i]['title'] || null) != null) {
                    item_html += '<a href="javascript:ModuleToPrompt(\'' + (data_list[i]['name'] || '') + '\');">';
                    item_html += '<p style="' + item_field_style[i]['title'] + '">' + data_list[i]['title'] + '</p>';
                    item_html += '</a>';
                }
                if ((data_list[i]['desc'] || null) != null) {
                    item_html += '<p style="' + item_field_style[i]['desc'] + '">' + data_list[i]['desc'] + '</p>';
                }
                item_html += '</div>';
                break;

            // 默认 上下、滚动
            case 'updown':
            default:
                if ((data_list[i]['images'] || null) != null) {
                    item_html += '<a href="javascript:ModuleToPrompt(\'' + (data_list[i]['name'] || '') + '\');" class="' + media_fixed.media_container_ent + '" style="' + media_fixed.media_container_style + '">'
                    item_html += '<img src="' + data_list[i]['images'] + '" class="' + media_fixed.media_ent + '" style="' + media_fixed.media_container_style + '" />';
                    item_html += '</a>';
                }
                if ((data_list[i]['title'] || null) != null) {
                    item_html += '<a href="javascript:ModuleToPrompt(\'' + (data_list[i]['name'] || '') + '\');">';
                    item_html += '<p style="' + item_field_style[i]['title'] + '">' + data_list[i]['title'] + '</p>';
                    item_html += '</a>';
                }
                if ((data_list[i]['desc'] || null) != null) {
                    item_html += '<p style="' + item_field_style[i]['desc'] + '">' + data_list[i]['desc'] + '</p>';
                }
                break;
        }
        item_html += '</div>';
        item_html += '</li>';
    }

    // 滚动
    var nav_dot_ent = '';
    var list_ent = '';
    if (show_style == 'rolling') {
        // 参数处理
        var show_style_value = ViewRollingShowStyleValueHandle(data.view_list_show_style_value);

        // 是否展示导航点
        nav_dot_ent = show_style_value.is_nav_dot ? '' : 'slides-rolling-not-dot';

        // html拼接
        html += '<div class="am-slider am-slider-default am-slider-carousel ' + nav_dot_ent + '">';
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
    } else {
        // 列表展示数量
        var sm = show_style_value.view_list_number_sm || 2;
        var md = show_style_value.view_list_number_md || 5;
        var lg = show_style_value.view_list_number_lg || 5;
        // 设置了外边距，则计算平均移动值
        var avg = (item_margin > 0) ? 'module-list-content-avg-' + item_margin : '';

        // 列表class
        list_ent = avg + ' module-list-sm-' + sm + ' module-list-md-' + md + ' module-list-lg-' + md + ' ';
        html += '<ul class="module-list-content ' + list_ent + '">';
        html += item_html;
        html += '</ul>';
    }
    html += '</div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        style: style,
        item_style: item_style,
        item_right_style: item_right_style,
        item_field_style: item_field_style,
        nav_dot_ent: nav_dot_ent,
        list_ent: list_ent,
        media_fixed: media_fixed
    }
    data['data_list'] = data_list;
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));

    // 滚动初始化
    if (show_style == 'rolling') {
        $doc.find('.am-slider').flexslider(option);
    }
    $offcanvas_config_images_text.offCanvas('close');
}

/**
 * 模块-图片魔方处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigImagesMagicCubeHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_images_magic_cube.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 数据字段
    var fields = {
        content_images_: "images",
        content_to_name_: "name",
        content_to_type_: "type",
        content_to_value_: "value"
    };
    var res = FormBackDataListHandle(data, fields);
    data = res.data;
    var data_list = res.data_list;
    for (var i in data_list) {
        if ((data_list[i]['images'] || null) == null) {
            Prompt($layout.data('upload-images-tips') || '请上传图片');
            return false;
        }
    }
    // 展示模式
    var show_style = data.view_list_show_style || null;
    if (show_style == null) {
        Prompt($layout.data('data-show-modal-tips') || '请选择数据展示模式');
        return false;
    }

    // 外边距
    var margin = parseInt(data.style_margin || 0);
    data['style_item_margin_top'] = margin;
    data['style_item_margin_left'] = margin;

    // 样式处理
    var images_style = StyleBaseHandle(data, 'style_item_');

    // 设置了外边距，则计算平均移动值
    var list_ent = (margin > 0) ? 'module-list-content-avg-' + margin : '';

    // 图片样式
    var media_fixed = MediaFixedHandle(data);

    // 数据处理
    var html = '';
    var item_style = [];
    switch (show_style) {
        // 1图
        case 'g1':
            item_style[0] = 'width:100%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 2竖图
        case 'v2':
            item_style[0] = 'width:50%;';
            item_style[1] = 'width:50%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 3竖图
        case 'v3':
            item_style[0] = 'width:33.33%;';
            item_style[1] = 'width:33.33%;';
            item_style[2] = 'width:33.33%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 4竖图
        case 'v4':
            item_style[0] = 'width:25%;';
            item_style[1] = 'width:25%;';
            item_style[2] = 'width:25%;';
            item_style[3] = 'width:25%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[3] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 2横图
        case 'h2':
            item_style[0] = 'width:100%;';
            item_style[1] = 'width:100%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 3横图
        case 'h3':
            item_style[0] = 'width:100%;';
            item_style[1] = 'width:100%;';
            item_style[2] = 'width:100%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 4横图
        case 'h4':
            item_style[0] = 'width:100%;';
            item_style[1] = 'width:100%;';
            item_style[2] = 'width:100%;';
            item_style[3] = 'width:100%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[3] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 1左右2
        case 'lr12':
            if (margin > 0) {
                item_style[0] = 'width:calc(50% + ' + (margin / 2) + 'px);';
                item_style[1] = 'width:calc(50% - ' + (margin / 2) + 'px);';
            } else {
                item_style[0] = 'width:50%;';
                item_style[1] = 'width:50%;';
            }
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 1左右3
        case 'lr13':
            if (margin > 0) {
                item_style[0] = 'width:calc(50% + ' + margin + 'px);';
                item_style[1] = 'width:calc(50% - ' + margin + 'px);';
            } else {
                item_style[0] = 'width:50%;';
                item_style[1] = 'width:50%;';
            }
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 2左右1
        case 'lr21':
            if (margin > 0) {
                item_style[0] = 'width:calc(50% - ' + (margin / 2) + 'px);';
                item_style[1] = 'width:calc(50% + ' + (margin / 2) + 'px);';
            } else {
                item_style[0] = 'width:50%;';
                item_style[1] = 'width:50%;';
            }
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 3左右1
        case 'lr31':
            if (margin > 0) {
                item_style[0] = 'width:calc(50% - ' + margin + 'px);';
                item_style[1] = 'width:calc(50% + ' + margin + 'px);';
            } else {
                item_style[0] = 'width:50%;';
                item_style[1] = 'width:50%;';
            }
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 1上下2
        case 'tb12':
            item_style[0] = 'width:100%;';
            item_style[1] = 'width:50%;';
            item_style[2] = 'width:50%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-nbfc">
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fr item-module-magic-cube" style="`+ item_style[2] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                        </div>
                    </div>`;
            break;

        // 1上下3
        case 'tb13':
            item_style[0] = 'width:100%;';
            item_style[1] = 'width:33.33%;';
            item_style[2] = 'width:33.33%;';
            item_style[3] = 'width:33.33%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-nbfc">
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[2] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fr item-module-magic-cube" style="`+ item_style[3] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                        </div>
                    </div>`;
            break;

        // 2上下1
        case 'tb21':
            item_style[0] = 'width:50%;';
            item_style[1] = 'width:50%;';
            item_style[2] = 'width:100%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-nbfc">
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 3上下1
        case 'tb31':
            item_style[0] = 'width:33.33%;';
            item_style[1] = 'width:33.33%;';
            item_style[2] = 'width:33.33%;';
            item_style[3] = 'width:100%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-nbfc">
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fr item-module-magic-cube" style="`+ item_style[2] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                        </div>
                        <div class="item-module-magic-cube" style="`+ item_style[3] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 2竖左右横2
        case 'lrv2h2':
            if (margin > 0) {
                item_style[0] = 'width:calc(25% + ' + (margin / 2) + 'px);';
                item_style[1] = 'width:calc(25% + ' + (margin / 2) + 'px);';
                item_style[2] = 'width:calc(50% - ' + margin + 'px);';
            } else {
                item_style[0] = 'width:25%;';
                item_style[1] = 'width:25%;';
                item_style[2] = 'width:50%;';
            }
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 2横左右竖2
        case 'lrh2v2':
            if (margin > 0) {
                item_style[0] = 'width:calc(50% - ' + margin + 'px);';
                item_style[1] = 'width:calc(25% + ' + (margin / 2) + 'px);';
                item_style[2] = 'width:calc(25% + ' + (margin / 2) + 'px);';
            } else {
                item_style[0] = 'width:50%;';
                item_style[1] = 'width:25%;';
                item_style[2] = 'width:25%;';
            }
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                            <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fl item-module-magic-cube" style="`+ item_style[1] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                        <div class="am-fr item-module-magic-cube" style="`+ item_style[2] + `">
                            <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                            </a>
                        </div>
                    </div>`;
            break;

        // 4图
        case 'g4':
            item_style[0] = 'width:50%;';
            item_style[1] = 'width:50%;';
            item_style[2] = 'width:50%;';
            item_style[3] = 'width:50%;';
            html += `<div class="am-nbfc layout-module-images-magic-cube layout-module-images-magic-cube-` + show_style + ` ` + list_ent + `">
                        <div class="am-nbfc">
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[0] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[0]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[0]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fr item-module-magic-cube" style="`+ item_style[1] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[1]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[1]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                        </div>
                        <div class="am-nbfc">
                            <div class="am-fl item-module-magic-cube" style="`+ item_style[2] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[2]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[2]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                            <div class="am-fr item-module-magic-cube" style="`+ item_style[3] + `">
                                <a href="javascript:ModuleToPrompt('`+ (data_list[3]['name'] || '') + `');" class="am-block am-nbfc" style="` + images_style + `">
                                    <img src="`+ data_list[3]['images'] + `" class="am-block ` + media_fixed.media_ent + `" />
                                </a>
                            </div>
                        </div>
                    </div>`;
            break;
    }

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        list_ent: list_ent,
        item_style: item_style,
        images_style: images_style,
        media_fixed: media_fixed
    }
    data['data_list'] = data_list;
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
    $offcanvas_config_images_magic_cube.offCanvas('close');
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
function FormBackModuleConfigVideoHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_video.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 视频
    if ((data.content_video || null) == null) {
        Prompt($layout.data('upload-video-tips') || '请上传视频');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // 视频固定
    var media_fixed = MediaFixedHandle(data);

    // html拼接
    var html = '<div class="module-video-container" style="' + style + '">';
    html += '<div class="module-video-content ' + media_fixed.media_container_ent + '" style="' + media_fixed.media_container_style + '">';
    html += '<video src="' + data.content_video + '" poster="' + (data.content_images || '') + '" controls class="' + media_fixed.media_ent + '" style="' + media_fixed.media_container_style + '">your browser does not support the video tag</video>';
    html += '</div>';
    html += '</div>';
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        style: style,
        media_fixed: media_fixed
    }
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
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
function FormBackModuleConfigGoodsHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_goods.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 数据类型
    var goods_ids = '';
    var category_id = 0;
    switch (data.goods_data_type) {
        // 商品
        case 'goods':
            if ((data.goods_ids || null) == null) {
                Prompt($layout.data('choice-goods-tips') || '请选择商品');
                return false;
            }
            goods_ids = data.goods_ids;
            break;

        // 商品分类
        case 'category':
            if ((data.goods_category_value || null) == null) {
                Prompt($layout.data('choice-goods-category-tips') || '请选择商品分类');
                return false;
            }
            var category = JSON.parse(decodeURIComponent(data.goods_category_value)) || null;
            category_id = category[category.length - 1]['id'];
            break;

        default:
            Prompt(($layout.data('data-type-tips') || '数据类型有误') + '[' + data.goods_data_type + ']');
            return false;
    }

    // 获取商品
    var $this = $offcanvas_config_goods.find('button[type="submit"]');
    var url = $offcanvas_config_goods.data('data-url');
    $this.button('loading');
    $.ajax({
        url: RequestUrlHandle(url),
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
        success: function (res) {
            $this.button('reset');
            if (res['code'] == 0) {
                if ((res.data || null) != null && res.data.length > 0) {
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
                        margin: {
                            value: "{var}px 0 0 {var}px",
                            var: "{var}"
                        }
                    };
                    var item_style = StyleBaseHandle(data, 'style_', rules);

                    // 图片样式
                    var media_fixed = MediaFixedHandle(data);

                    // 模块容器设置
                    var $doc = $(doc);

                    // 数据项html
                    var item_html = '';
                    for (var i in list) {
                        item_html += `<li>
                                <div class="module-item" style="`+ item_style + `">
                                    <a href="`+ list[i]['goods_url'] + `" target="_blank" class="` + media_fixed.media_container_ent + `" style="` + media_fixed.media_container_style + `">
                                        <img src="`+ list[i]['images'] + `" alt="` + list[i]['title'] + `" class="` + media_fixed.media_ent + `" style="` + media_fixed.media_container_style + `" />
                                    </a>
                                    <div class="item-bottom">
                                        <div class="module-title">
                                            <a href="`+ list[i]['goods_url'] + `" target="_blank">` + list[i]['title'] + `</a>
                                        </div>
                                        <p class="module-price">`+ __currency_symbol__ + list[i]['price'] + `</p>
                                    </div>
                                </div>
                            </li>`;
                    }

                    // 商品容器
                    var html = '<div class="module-goods-container" style="' + style + '">';

                    // 初始化参数
                    var option = {};

                    // 展示模式
                    var nav_dot_ent = '';
                    var list_ent = '';
                    var item_right_style = '';
                    var show_style = data.view_list_show_style || 'routine';
                    switch (show_style) {
                        // 滚动
                        case 'rolling':
                            // 参数处理
                            var show_style_value = ViewRollingShowStyleValueHandle(data.view_list_show_style_value);

                            // 是否展示导航点
                            nav_dot_ent = show_style_value.is_nav_dot ? '' : 'slides-rolling-not-dot';

                            // html拼接
                            html += '<div class="am-slider am-slider-default am-slider-carousel module-goods-content ' + nav_dot_ent + '">';
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

                        // 左图右文
                        case 'leftright':
                            var style_media_fixed_width = parseInt(data.style_media_fixed_width || 0);
                            item_right_style = 'margin-left:10px;' + ((style_media_fixed_width > 0) ? 'width: calc(100% - ' + (style_media_fixed_width + 10) + 'px);' : '');
                            // 设置了外边距，则计算平均移动值
                            var avg = (margin > 0) ? 'module-list-content-avg-' + margin : '';
                            // 列表class
                            list_ent = avg + ' module-list-sm-' + sm + ' module-list-md-' + md + ' module-list-lg-' + md + ' ';
                            html += '<ul class="module-goods-content module-list-content ' + list_ent + '">';
                            for (var i in list) {
                                html += `<li>
                                        <div class="module-item" style="`+ item_style + `">
                                            <a href="`+ list[i]['goods_url'] + `" target="_blank" class="am-fl ` + media_fixed.media_container_ent + `" style="` + media_fixed.media_container_style + `">
                                                <img src="`+ list[i]['images'] + `" class="` + media_fixed.media_ent + `" style="` + media_fixed.media_container_style + `" />
                                            </a>
                                            <div class="am-fl" style="`+ item_right_style + `">
                                                <div class="module-title">
                                                    <a href="`+ list[i]['goods_url'] + `" target="_blank">` + list[i]['title'] + `</a>
                                                </div>
                                                <p class="module-price">`+ __currency_symbol__ + list[i]['price'] + `</p>
                                            </div>
                                        </div>
                                    </li>`;
                            }
                            html += '</ul>';
                            break;

                        // 常规、默认
                        default:
                            // 设置了外边距，则计算平均移动值
                            var avg = (margin > 0) ? 'module-list-content-avg-' + margin : '';

                            // 列表class
                            list_ent = avg + ' module-list-sm-' + sm + ' module-list-md-' + md + ' module-list-lg-' + md + ' ';

                            html += '<ul class="module-goods-content module-list-content ' + list_ent + '">';
                            html += item_html;
                            html += '</ul>';
                            break;
                    }
                    html += '</div>';

                    // 固定商品则加入数据中，方便使用
                    if (data.goods_data_type == 'goods') {
                        data['data_list'] = list;
                    }

                    // 模块容器设置
                    $doc.html(html);

                    // 数据加入配置
                    data['frontend_config'] = {
                        style: style,
                        item_style: item_style,
                        item_right_style: item_right_style,
                        nav_dot_ent: nav_dot_ent,
                        list_ent: list_ent,
                        media_fixed: media_fixed
                    }
                    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));

                    // 组件参数
                    if (JSON.stringify(option) !== '{}') {
                        $doc.find('.am-slider').flexslider(option);
                    }

                    // 关闭商品窗口
                    $offcanvas_config_goods.offCanvas('close');
                } else {
                    Prompt($layout.data('goods-data-empty-tips') || '商品信息为空');
                }
            } else {
                Prompt(res.msg);
            }
        },
        error: function (res) {
            $this.button('reset');
            var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
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
function FormBackModuleConfigTitleHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_title.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 视频
    if ((data.content_title || null) == null) {
        Prompt($layout.data('main-title-tips') || '请填写主标题');
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
    var html = '<div class="module-title-container" style="' + style + '">';
    html += '<div class="module-title-content">';
    html += '<span class="title-main" style="' + style_title_main + '">' + data.content_title + '</span>';

    // 副标题
    if ((data.content_title_vice || null) != null) {
        html += '<span class="title-vice" style="' + style_title_vice + '">' + data.content_title_vice + '</span>';
    }

    // 关键字
    var field_first = 'content_item_keywords_';
    var field_first_length = field_first.length;
    var key_temp = [];
    var keywords_list = [];
    for (var i in data) {
        if (i.substr(0, field_first_length) == field_first) {
            keywords_list.push(JSON.parse(decodeURIComponent(data[i])));
            delete data[i];
        }
    }
    if (keywords_list.length > 0) {
        html += '<div class="keywords-content">';
        for (var i in keywords_list) {
            var kt_item_style = ((keywords_list[i]['style_keywords_color'] || null) == null) ? '' : 'color:' + keywords_list[i]['style_keywords_color'] + ';';
            html += '<a href="javascript:ModuleToPrompt(\'' + keywords_list[i]['content_to_name'] + '\');" style="' + kt_item_style + '">' + keywords_list[i]['content_keywords'] + '</a>';
        }
        html += '</div>';
    }
    data['keywords_list'] = keywords_list;


    // 右侧按钮
    if ((data.content_title_more || null) != null) {
        html += '<div class="more-content">';
        html += '<a href="javascript:ModuleToPrompt(\'' + (data.content_to_name || '') + '\');" style="' + style_title_more + '">';
        html += '<span>' + data.content_title_more + '</span> ';
        html += '<i class="iconfont icon-angle-right"></i>';
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
        style: style,
        style_title_main: style_title_main,
        style_title_vice: style_title_vice,
        style_title_more: style_title_more
    }
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
    $offcanvas_config_title.offCanvas('close');
}

/**
 * 模块-自定义html处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-04
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function FormBackModuleConfigCustomHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_custom.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(data.custom || '');

    // 数据加入配置
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
    $offcanvas_config_custom.offCanvas('close');
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
function FormBackModuleConfigBorderHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_border.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 边线类型、和大小
    if ((data.style_border_style || null) == null) {
        Prompt($layout.data('border-style-tips') || '请选择边线类型');
        return false;
    }
    if ((data.style_border_width || null) == null) {
        Prompt($layout.data('border-style-max-tips') || '请输入边线、最大10的数字');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // html拼接
    var html = '<div class="module-border-container" style="' + style + '"></div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        style: style
    }
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
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
function FormBackModuleConfigHeightHandle (data) {
    // 基础信息
    var doc = $offcanvas_config_height.attr('data-doc') || null;
    if (doc == null) {
        Prompt($layout.data('module-tab-tips') || '模块标记有误');
        return false;
    }

    // 高度
    if ((data.style_height || null) == null) {
        Prompt($layout.data('height-max-tips') || '请输入高度、最大100的数字');
        return false;
    }

    // 样式处理
    var style = StyleBaseHandle(data, 'style_');

    // html拼接
    var html = '<div class="module-height-container" style="' + style + '"></div>';

    // 模块容器设置
    var $doc = $(doc);
    $doc.html(html);

    // 数据加入配置
    data['frontend_config'] = {
        style: style
    }
    $doc.attr('data-json', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));
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
function FormBackModuleModalRollingConfigHandle (data) {
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
function FormBackModuleModalListConfigHandle (data) {
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
function FormBackModuleModalTitleKeywordsHandle (data) {
    // 操作类型
    var type = parseInt($modal_title_keywords.attr('data-opt-type') || 0);

    // 获取设置的数据、关键字名称必填
    if ((data.content_keywords || null) == null) {
        $modal_title_keywords.find('input[name="content_keywords"]').focus();
        Prompt($layout.data('keywords-tips') || '请填写关键字');
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
    if (type == 0) {
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
function FormBackModulePopupGoodsSearchHandle (data) {
    // 参数配置
    var type = $popup_goods_search.find('.am-tabs-nav li.am-active a').data('value');
    var $vb = $popup_goods_search.find('.form-container-' + type);
    var params = {
        "type": type,
        "value": ""
    };
    switch (type) {
        // 商品分类
        case 'category':
            var json = $vb.find('.already-select-tips').attr('data-value') || null;
            if (json != null) {
                json = JSON.parse(decodeURIComponent(json)) || null;
            }
            if (json == null) {
                Prompt($layout.data('before-choice-goods-category-tips') || '请先选择商品分类');
                return false;
            }
            params['value'] = json;
            break;

        // 品牌
        case 'brand':
            var $vbs = $vb.find('ul li.active a');
            if ($vbs.length <= 0) {
                Prompt($layout.data('before-choice-brand-tips') || '请先选择品牌');
                return false;
            }
            params['value'] = {
                "id": $vbs.data('value'),
                "name": $vbs.find('span').text()
            };
            break;

        // 关键字
        case 'keywords':
            var value = $vb.find('input').val() || '';
            if (value == '') {
                $vb.find('input').focus();
                Prompt($layout.data('before-input-keywords-tips') || '请先输入关键字1~30个字符');
                return false;
            }
            // 输入关键字去除引号
            params['value'] = value.replace(new RegExp('"', 'g'), '').replace(new RegExp("'", 'g'), '');
            break;
    }

    // 数据赋值并关闭弹窗
    var show_name = ModuleConfigGoodsSearchPageShowName(params);
    var $page = $modal_pages_select.find('.am-tabs-bd ul li.page-goods_search a');
    $page.find('span').text($page.data('name') + show_name);
    $page.attr('data-json', encodeURIComponent(JSON.stringify(params)));
    $popup_goods_search.modal('close');
}

/**
 * 模块-基础布局处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data [表单数据]
 */
function ModuleConfigBaseContentHandle (data) {
    // 默认值处理
    data.style_width_max_limit_value = data.style_width_max_limit_value || '';
    data.style_background_images_no_repeat = data.style_background_images_no_repeat || '';
    data.style_background_images_size_cover = data.style_background_images_size_cover || '';
    data.style_background_images_position_center = data.style_background_images_position_center || '';

    // 图片处理
    var $ul = $offcanvas_layout_config.find('ul.layout-style-background-images-view');
    if ((data.style_background_images || null) == null) {
        $ul.find('li').remove();
    } else {
        var html = `<li>
                <input type="text" name="style_background_images" value="`+ data.style_background_images + `">
                <img src="`+ data.style_background_images + `" />
                <i class="iconfont icon-close"></i>
            </li>`;
        $ul.html(html);
    }

    return data;
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
function ModuleConfigImagesToContentHtml (name) {
    // 无数据
    if ((name || null) == null) {
        return '<a href="javascript:;" class="form-view-choice-container-submit">' + ($layout.data('choice-url-tips') || '请选择跳转链接') + '</a>';
    }

    // 有数据
    var html = '<span class="form-view-choice-container-submit form-view-choice-container-active am-radius">';
    html += '<span class="am-text-truncate">' + name + '</span>';
    html += '<i class="am-icon-close am-margin-left-xs"></i>';
    html += '</span>';
    html += '<a href="javascript:;" class="form-view-choice-container-submit am-margin-left-sm">' + ($layout.data('edit-name') || '修改') + '</a>';
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
function ModuleConfigGoodsCategoryContentHtml (name) {
    // 无数据
    if ((name || null) == null) {
        return '<a href="javascript:;" class="form-view-choice-container-submit">' + ($layout.data('choice-goods-category-tips') || '请选择商品分类') + '</a>';
    }

    // 设置数据
    var html = '<span class="form-view-choice-container-submit form-view-choice-container-active am-radius">';
    html += '<span class="am-text-truncate">' + name + '</span>';
    html += '<i class="am-icon-close am-margin-left-xs"></i>';
    html += '</span>';
    html += '<a href="javascript:;" class="form-view-choice-container-submit am-margin-left-sm">' + ($layout.data('edit-name') || '修改') + '</a>';
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
function ModuleConfigGoodsItemContentHtml (data) {
    var html = '';
    if ((data || null) != null && data.length > 0) {
        for (var i in data) {
            html += '<li data-gid="' + data[i]['id'] + '">';
            html += '<a href="javascript:;" class="am-close am-close-alt am-icon-times"></a>';
            html += '<a href="' + data[i]['goods_url'] + '" title="' + data[i]['title'] + '" target="_blank" class="am-block am-padding-xs">';
            html += '<img src="' + data[i]['images'] + '" alt="' + data[i]['title'] + '" />';
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
function ModuleConfigManyImagesItemContentHtml (images, type, name, value) {
    var index = parseInt(Math.random() * 1000001);
    var html = `<div class="am-panel am-panel-default am-padding-sm">
            <a href="javascript:;" class="am-close am-close-alt am-icon-times"></a>
            <div class="am-form-group am-form-file am-form-group-refreshing">
                <ul class="plug-file-upload-view am-cf module-slider-type-images-view module-slider-type-images-view-`+ index + `" data-form-name="content_images_` + index + `" data-max-number="1" data-delete="0" data-dialog-type="images">
                    <li>
                        <input type="text" name="content_images_`+ index + `" data-validation-message="` + ($layout.data('upload-images-tips') || '请上传图片') + `" value="` + (images || '') + `" required />
                        <img src="`+ (images || $offcanvas_config_many_images.data('default-images')) + `" />
                    </li>
                </ul>
                <div class="plug-file-upload-submit" data-view-tag="ul.module-slider-type-images-view-`+ index + `">+ ` + ($layout.data('upload-images-name') || '上传图片') + `</div>
            </div>
            <div class="am-form-group am-form-group-refreshing">
                <div class="form-view-choice-container am-margin-top-xs" data-key="`+ index + `">
                    <input type="hidden" name="content_to_type_`+ index + `" value="` + (type || '') + `" />
                    <input type="hidden" name="content_to_name_`+ index + `" value="` + (name || '') + `" />
                    <input type="hidden" name="content_to_value_`+ index + `" value="` + ((value || null) == null ? '' : encodeURIComponent(JSON.stringify(value))) + `" />
                    <div class="form-view-choice-container-content">
                        `+ ModuleConfigImagesToContentHtml(name) + `
                    </div>
                </div>
            </div>
        </div>`;
    return html;
}

/**
 * 模块-图文信息生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data        [配置数据]
 */
function ModuleConfigImagesTextItemContentHtml (data) {
    if ((data || null) == null) {
        data = {};
    }
    if ((data.title_style_align || null) == null) {
        data.title_style_align = 'left';
    }
    if ((data.desc_style_align || null) == null) {
        data.desc_style_align = 'left';
    }
    var index = parseInt(Math.random() * 1000001);
    var font_size = $layout.data('images-text-font-size') || '大小';
    var title_message = $layout.data('images-text-margin-title-message') || '标题外边距最大1000';
    var desc_message = $layout.data('images-text-margin-desc-message') || '描述外边距最大1000';
    var margin_top = $layout.data('images-text-margin-top') || '外上';
    var margin_right = $layout.data('images-text-margin-right') || '外右';
    var margin_bottom = $layout.data('images-text-margin-bottom') || '外下';
    var margin_left = $layout.data('images-text-margin-left') || '外左';
    var position_left = $layout.data('images-text-position-left') || '外左';
    var position_center = $layout.data('images-text-position-center') || '外中';
    var position_right = $layout.data('images-text-position-right') || '外右';
    var html = `<div class="am-panel am-panel-default am-padding-sm">
                    <a href="javascript:;" class="am-close am-close-alt am-icon-times"></a>
                    <div class="am-form-group am-form-file am-form-group-refreshing">
                        <ul class="plug-file-upload-view am-cf module-slider-type-images-view module-slider-type-images-view-`+ index + `" data-form-name="content_images_` + index + `" data-max-number="1" data-delete="0" data-dialog-type="images">
                            <li>
                                <input type="text" name="content_images_`+ index + `" value="` + (data.images || '') + `" />
                                <img src="`+ (data.images || $offcanvas_config_many_images.data('default-images')) + `" />
                            </li>
                        </ul>
                    <div class="plug-file-upload-submit" data-view-tag="ul.module-slider-type-images-view-`+ index + `">+ ` + ($layout.data('upload-images-name') || '上传图片') + `</div>
                    </div>
                    <div class="am-form-group am-form-group-refreshing">
                    <div class="form-view-choice-container am-margin-top-xs" data-key="`+ index + `">
                        <input type="hidden" name="content_to_type_`+ index + `" value="` + (data.type || '') + `" />
                        <input type="hidden" name="content_to_name_`+ index + `" value="` + (data.name || '') + `" />
                        <input type="hidden" name="content_to_value_`+ index + `" value="` + ((data.value || null) == null ? '' : encodeURIComponent(JSON.stringify(data.value))) + `" />
                        <div class="form-view-choice-container-content">
                            `+ ModuleConfigImagesToContentHtml(data.name) + `
                        </div>
                    </div>
                </div>`;
    // 标题
    var title_style_color = (data.title_style_color || null) == null ? '' : 'background-color:' + data.title_style_color + ';border-color:' + data.title_style_color + ';';
    var title = $layout.data('images-text-title') || '标题';
    html += `<div class="am-form-group am-form-group-refreshing">
                    <label>`+ title + `</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" placeholder="`+ title + `" name="content_title_` + index + `" value="` + (data.title || '') + `" class="am-form-field" />
                        <input type="hidden" name="content_title_style_color_`+ index + `" value="` + (data.title_style_color || '') + `" />
                        <a href="javascript:;" class="am-input-group-label am-padding-0 am-border-0">
                            <div class="colorpicker-container colorpicker-simple">
                                <div class="colorpicker-submit module-style-color-images-text-content-title-style-color-`+ index + `" data-position="fixed" data-input-tag=".module-style-color-images-text-content-title-style-color-` + index + `" data-color-tag="input[name='content_title_style_color_` + index + `']" data-color-style="background-color|border-color" style="` + title_style_color + `" data-color="` + (data.title_style_color || '') + `">
                                </div>
                                <img class="imitate-colorpicker-submit" src="`+ __attachment_host__ + `/static/common/images/colorpicker.png" />
                            </div>
                        </a>
                    </div>`;

    // 外边距
    html += `<div class="am-input-group am-input-group-sm group-border-width am-margin-top-xs">
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_top + `" name="content_title_style_margin_top_` + index + `" min="0" max="1000" data-validation-message="` + title_message + `" value="` + (data.title_style_margin_top || '') + `" class="am-form-field" />
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_right + `" name="content_title_style_margin_right_` + index + `" min="0" max="1000" data-validation-message="` + title_message + `" value="` + (data.title_style_margin_right || '') + `" class="am-form-field" />
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_bottom + `" name="content_title_style_margin_bottom_` + index + `" min="0" max="1000" data-validation-message="` + title_message + `" value="` + (data.title_style_margin_bottom || '') + `" class="am-form-field" />
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_left + `" name="content_title_style_margin_left_` + index + `" min="0" max="1000" data-validation-message="` + title_message + `" value="` + (data.title_style_margin_left || '') + `" class="am-form-field" />
                    <span class="am-input-group-label">px</span>
                </div>`;

    // 字体大小和左右居中
    html += `<div class="am-margin-top-xs am-nbfc">
                    <div class="am-input-group am-input-group-sm am-fl group-input-font-size">
                        <input type="number" data-is-clearout="0" placeholder="`+ font_size + `" name="content_title_style_font_size_` + index + `" min="0" max="1000" data-validation-message="` + ($layout.data('images-text-font-size-title-message') || '标题字体最大1000') + `" value="` + (data.title_style_font_size || '') + `" class="am-form-field" />
                        <span class="am-input-group-label">px</span>
                    </div>
                    <div class="am-fr group-text-align-style"><label class="am-checkbox-inline"><input type="radio" name="content_title_style_align_`+ index + `" value="left" data-am-ucheck ` + (data.title_style_align == 'left' ? 'checked' : '') + ` /> ` + position_left + `</label><label class="am-checkbox-inline"><input type="radio" name="content_title_style_align_` + index + `" value="center" data-am-ucheck ` + (data.title_style_align == 'center' ? 'checked' : '') + ` /> ` + position_center + `</label><label class="am-checkbox-inline"><input type="radio" name="content_title_style_align_` + index + `" value="right" data-am-ucheck ` + (data.title_style_align == 'right' ? 'checked' : '') + ` /> ` + position_right + `</label></div>
                    </div>`;
    html += `</div>`;

    // 描述
    var desc = $layout.data('images-text-desc') || '描述';
    var desc_style_color = (data.desc_style_color || null) == null ? '' : 'background-color:' + data.desc_style_color + ';border-color:' + data.desc_style_color + ';';
    html += `<div class="am-form-group am-form-group-refreshing">
                    <label>`+ desc + `</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" placeholder="`+ desc + `" name="content_desc_` + index + `" value="` + (data.desc || '') + `" class="am-form-field" />
                        <input type="hidden" name="content_desc_style_color_`+ index + `" value="` + (data.desc_style_color || '') + `" />
                        <a href="javascript:;" class="am-input-group-label am-padding-0 am-border-0">
                            <div class="colorpicker-container colorpicker-simple">
                                <div class="colorpicker-submit module-style-color-images-text-content-desc-style-color-`+ index + `" data-position="fixed" data-input-tag=".module-style-color-images-text-content-desc-style-color-` + index + `" data-color-tag="input[name='content_desc_style_color_` + index + `']" data-color-style="background-color|border-color" style="` + desc_style_color + `" data-color="` + (data.desc_style_color || '') + `">
                                </div>
                                <img class="imitate-colorpicker-submit" src="`+ __attachment_host__ + `/static/common/images/colorpicker.png" />
                            </div>
                        </a>
                    </div>`;

    // 外边距
    html += `<div class="am-input-group am-input-group-sm group-border-width am-margin-top-xs">
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_top + `" name="content_desc_style_margin_top_` + index + `" min="0" max="1000" data-validation-message="` + desc_message + `" value="` + (data.desc_style_margin_top || '') + `" class="am-form-field" />
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_right + `" name="content_desc_style_margin_right_` + index + `" min="0" max="1000" data-validation-message="` + desc_message + `" value="` + (data.desc_style_margin_right || '') + `" class="am-form-field" />
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_bottom + `" name="content_desc_style_margin_bottom_` + index + `" min="0" max="1000" data-validation-message="` + desc_message + `" value="` + (data.desc_style_margin_bottom || '') + `" class="am-form-field" />
                    <input type="number" data-is-clearout="0" placeholder="`+ margin_left + `" name="content_desc_style_margin_left_` + index + `" min="0" max="1000" data-validation-message="` + desc_message + `" value="` + (data.desc_style_margin_left || '') + `" class="am-form-field" />
                    <span class="am-input-group-label">px</span>
                </div>`;

    // 字体大小和左右居中
    html += `<div class="am-margin-top-xs am-nbfc">
                    <div class="am-input-group am-input-group-sm am-fl group-input-font-size">
                        <input type="number" data-is-clearout="0" placeholder="`+ font_size + `" name="content_desc_style_font_size_` + index + `" min="0" max="1000" data-validation-message="` + ($layout.data('images-text-font-size-desc-message') || '描述字体最大1000') + `" value="` + (data.desc_style_font_size || '') + `" class="am-form-field" />
                        <span class="am-input-group-label">px</span>
                    </div>
                    <div class="am-fr group-text-align-style"><label class="am-checkbox-inline"><input type="radio" name="content_desc_style_align_`+ index + `" value="left" data-am-ucheck ` + (data.desc_style_align == 'left' ? 'checked' : '') + ` /> ` + position_left + `</label><label class="am-checkbox-inline"><input type="radio" name="content_desc_style_align_` + index + `" value="center" data-am-ucheck ` + (data.desc_style_align == 'center' ? 'checked' : '') + ` /> ` + position_center + `</label><label class="am-checkbox-inline"><input type="radio" name="content_desc_style_align_` + index + `" value="right" data-am-ucheck ` + (data.desc_style_align == 'right' ? 'checked' : '') + ` /> ` + position_right + `</label></div>
                    </div>
                </div>`;
    html += `</div>`;
    return html;
}

/**
 * 模块-图片魔方信息生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-05-18
 * @desc    description
 * @param   {[object]}        data        [配置数据]
 */
function ModuleConfigImagesMagicCubeItemContentHtml (data) {
    // 基础处理
    if ((data || null) == null) {
        data = {};
    }

    // 展示模式
    var value = (((data.view_list_show_style || null) == null) ? $offcanvas_config_images_magic_cube.find('input[name="view_list_show_style"]:checked').val() : data.view_list_show_style) || null;
    if (value == null) {
        Prompt($layout.data('data-show-modal-tips') || '请选择数据展示模式');
        return false;
    }
    var modal_arr = { g1: 1, v2: 2, v3: 3, v4: 4, h2: 2, h3: 3, h4: 4, lr12: 3, lr13: 4, lr21: 3, lr31: 4, tb12: 3, tb13: 4, tb21: 3, tb31: 4, lrv2h2: 4, lrh2v2: 4, g4: 4 };
    if (modal_arr[value] == undefined) {
        Prompt(($layout.data('data-show-modal-error-tips') || '展示模式有误') + '(' + value + ')');
        return false;
    }

    // 编辑则重新添加
    var count = 0;
    if (JSON.stringify(data) == '{}') {
        // 已有数据条数、满足数量则不增加，多余则移除
        count = $offcanvas_config_images_magic_cube.find('.config-images-magic-cube-container > .am-panel').length;
        var max = modal_arr[value] - count;
        if (max < 0) {
            for (var i = count; i >= modal_arr[value]; i--) {
                $offcanvas_config_images_magic_cube.find('.config-images-magic-cube-container > .am-panel:eq(' + i + ')').remove();
            }
        }
        if (max <= 0) {
            return false;
        }
    }

    // 生成数据
    var html = '';
    var data_list = data.data_list || [];
    for (var i = count; i < modal_arr[value]; i++) {
        var item = data_list[i] || {};
        html += `<div class="am-panel am-panel-default am-padding-sm">
                <div class="am-form-group am-form-file am-form-group-refreshing">
                    <ul class="plug-file-upload-view am-cf module-slider-type-images-view module-slider-type-images-view-`+ i + `" data-form-name="content_images_` + i + `" data-max-number="1" data-delete="0" data-dialog-type="images">
                        <li>
                            <input type="text" name="content_images_`+ i + `" data-validation-message="` + ($layout.data('upload-images-tips') || '请上传图片') + `" value="` + (item.images || '') + `" required />
                            <img src="`+ (item.images || $offcanvas_config_many_images.data('default-images')) + `" />
                        </li>
                    </ul>
                    <div class="plug-file-upload-submit" data-view-tag="ul.module-slider-type-images-view-`+ i + `">+ ` + ($layout.data('upload-images-name') || '上传图片') + `</div>
                </div>
                <div class="am-form-group am-form-group-refreshing">
                    <div class="form-view-choice-container am-margin-top-xs" data-key="`+ i + `">
                        <input type="hidden" name="content_to_type_`+ i + `" value="` + (item.type || '') + `" />
                        <input type="hidden" name="content_to_name_`+ i + `" value="` + (item.name || '') + `" />
                        <input type="hidden" name="content_to_value_`+ i + `" value="` + ((item.value || null) == null ? '' : encodeURIComponent(JSON.stringify(item.value))) + `" />
                        <div class="form-view-choice-container-content">
                            `+ ModuleConfigImagesToContentHtml(item.name) + `
                        </div>
                    </div>
                </div>
            </div>`;
    }
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
function ModuleConfigTitleKeywordsContentHtml (data) {
    var index = parseInt(Math.random() * 1000001);
    var html = '<li>';
    html += '<input type="hidden" name="content_item_keywords_' + index + '" value="' + (((data || null) == null) ? '' : encodeURIComponent(JSON.stringify(data))) + '" />';
    html += '<span class="am-text-truncate am-block">' + (data.content_keywords || '') + '</span>';
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
function ModuleConfigTitleKeywordsOpen (type, data) {
    // 数据处理
    if ((data || null) != null) {
        data = JSON.parse(decodeURIComponent(data)) || null;
    }
    if ((data || null) == null) {
        data = {
            "content_keywords": "",
            "style_keywords_color": "",
            "content_to_name": "",
            "content_to_type": "",
            "content_to_value": ""
        };
    }

    // 表单数据赋值
    FormDataFill(data, '#modal-module-title-keywords');

    // 链接地址
    $modal_title_keywords.find('.form-view-choice-container-content').html(ModuleConfigImagesToContentHtml(data.content_to_name));

    // 关键字颜色
    var color = data.style_keywords_color || '';
    $modal_title_keywords.find('.module-style-color_keywords').css({ "background-color": color, "border-color": color, "color": color });

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
function ModuleConfigGoodsSearchPageShowName (data) {
    var name = '';
    if ((data || null) != null && (data.type || null) != null && (data.value || null) != null) {
        var value = data['value'];
        switch (data.type) {
            // 商品分类
            case 'category':
                name = ($layout.data('goods-category-name') || '商品分类') + '-' + value[value.length - 1]['name'];
                break;

            // 品牌
            case 'brand':
                name = ($layout.data('brand-name') || '品牌') + '-' + value['name'];
                break;

            // 关键字
            case 'keywords':
                name = ($layout.data('keywords-name') || '关键字') + '-' + value;
                break;
        }
    }
    return (name || null) == null ? '' : '（' + name + '）';
}

/**
 * 左侧商品配置商品id
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-06
 * @desc    description
 */
function OffcanvasModuleConfigGoodsIds () {
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
function ViewRollingShowStyleValueHandle (data) {
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
function ViewListShowStyleValueHandle (data) {
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
function OffcanvasConfigPagesChoice (obj, event) {
    // 当前选择页面的链接位置对象
    $page_parent_obj = obj.parent();

    // 获取已选择的数据
    var $parent = $page_parent_obj.parents('.form-view-choice-container');
    var key = $parent.data('key');
    var index = (key == undefined) ? '' : '_' + key;
    var to_type = $parent.find('input[name="content_to_type' + index + '"]').val() || null;
    var to_name = $parent.find('input[name="content_to_name' + index + '"]').val() || null;
    var to_value = $parent.find('input[name="content_to_value' + index + '"]').val() || null;

    // 所有处理
    $modal_pages_select.find('.am-tabs-bd ul li').each(function (k, v) {
        $(this).removeClass('active');
        $(this).find('a span').text($(this).find('a').data('name'));
    });
    $modal_pages_select.find('.am-tabs-bd ul li').removeClass('active');
    $modal_pages_select.find('.am-tabs-bd ul li a').attr('data-json', '');

    // 自定义链接地址
    if (to_type == 'pages-custom-url') {
        var form_doc = '.pages-custom-url-container';
        if ((to_value || null) == null) {
            to_value = GetFormVal(form_doc, true);
        } else {
            to_value = JSON.parse(decodeURIComponent(to_value));
        }
        FormDataFill(to_value, form_doc);
        var index = 2;

        // 常规页面选择
    } else {
        // 当前选中的数据
        var $active_obj = $modal_pages_select.find('.am-tabs-bd ul li.page-' + to_type);
        $active_obj.addClass('active');
        $active_obj.find('a span').text(to_name);
        $active_obj.find('a').attr('data-json', to_value);

        // 当前选中的索引值
        var index = $active_obj.parents('.am-tab-panel').index();
        if (index == -1) {
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
        width: 380,
        height: 475,
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
function OffcanvasConfigPagesRemove (obj, event) {
    var $parent = obj.parents('.form-view-choice-container');
    var $content = $parent.find('.form-view-choice-container-content');
    var key = $parent.data('key');
    var index = (key == undefined) ? '' : '_' + key;
    $content.html(ModuleConfigImagesToContentHtml());
    $parent.find('input[name="content_to_type' + index + '"]').val('');
    $parent.find('input[name="content_to_name' + index + '"]').val('');
    $parent.find('input[name="content_to_value' + index + '"]').val('');
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
function ModuleColorpickerHandle (e) {
    e.find('.colorpicker-submit').each(function (k, v) {
        var color = $(this).prev().val() || '';
        var style = $(this).data('color-style') || null;
        if (style != null) {
            var arr = style.split('|');
            for (var i in arr) {
                $(this).css(arr[i], color);
                $(this).find('.fcolorpicker-curbox').css(arr[i], color);
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
function LayoutViewConfig () {
    // 循环读取布局
    var data = [];
    $('.layout-container .layout-view').each(function (k, v) {
        // 布局数据
        var json = $(this).find('.layout-content-children').attr('data-json') || null;
        var layout_temp = {
            value: $(this).data('value').toString(),
            status: ($(this).find('.layout-content-submit input[type="checkbox"]:checked').val() == 'on') ? 1 : 0,
            config: (json == null) ? {} : JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)),
            children: []
        };

        // 模块容器
        $(this).find('.layout-content-container').each(function (ks, vs) {
            // 容器数据
            var json = $(this).attr('data-json') || null;
            var content_temp = {
                config: (json == null) ? {} : JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)),
                children: []
            };

            // 模块
            $(this).find('.layout-content .module-view').each(function (kss, vss) {
                // 模块数据
                var json = $(this).find('.module-content').attr('data-json') || null;
                var value = $(this).find('.module-view-submit-container').data('value');
                var module_config = {
                    value: value,
                    name: layout_module_type_arr[value],
                    config: (json == null) ? {} : JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8))
                };
                // 商品模块移除商品列表
                if (value == 'goods') {
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
function LayoutSwitchInit () {
    SwitchInit ();
}


$(function () {
    // 开关变化
    $(document).on('click', '.layout-view .switch-checkbox', function () {
        var state = $(this).find('input').is(':checked') ? true : false;
        if (state === true) {
            $(this).parents('.layout-view').removeClass('layout-view-hidden');
        } else {
            $(this).parents('.layout-view').addClass('layout-view-hidden');
        }
    })
    // 布局拖拽
    $layout.dragsort({
        dragSelector: '.drag-submit',
        placeHolderTemplate: '<div class="drag-sort-dotted"></div>'
    });

    // 模块拖拽
    ModuleDragSortInit();

    // 开关初始化
    LayoutSwitchInit();

    // 布局/模块切换事件
    $('.renovation-tabs .am-tabs-nav a').on('click', function () {
        $(this).parent('li').addClass('am-active').siblings('li').removeClass('am-active');
        $('.renovation-tabs .am-tabs-bd .renovation-panel').addClass('am-hide');
        $($(this).attr('href')).removeClass('am-hide');
    });

    // 布局拖放
    $(document).on('dragstart', '.structure-drag button', function (e) {
        // 布局配置
        var drag_value = $(this).data('value');

        // 拖拽过程中经过的元素
        $(document).on('dragenter', '.layout-view, .layout-container-tips', function (e) {
            // 当前是否在布局模式下操作
            if ($('.renovation-tabs .am-tabs-nav li.am-active').data('value') == 'structure') {
                $('.layout-view, .layout-container-tips').removeClass('layout-view-dragenter');
                $(this).addClass('layout-view-dragenter');
            }
        });
        // 拖拽离开元素移除过程中添加的样式
        $(document).on('dragleave', '.structure-drag button', function (e) {
            $('.layout-view,.layout-container-tips').removeClass('layout-view-dragenter');
        });
        // 拖拽结束
        $(document).on('dragend', '.structure-drag button', function (e) {
            // 移除过程中添加的样式
            $('.layout-view,.layout-container-tips').removeClass('layout-view-dragenter');

            // 关闭事件
            $('.layout-view, .layout-container-tips').off('dragover');
            $('.layout-view, .layout-container-tips').off('dragenter');
            $('.layout-view, .layout-container-tips').off('drop');
        });
        // 拖拽过程中一直在元素内、并阻止默认事件
        $(document).on('dragover', '.layout-container', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // 拖放接收事件
        $('.layout-view, .layout-container-tips').on('drop', function (e) {
            // 添加布局
            $(this).before(StructureDragHtmlCreate(drag_value));

            // 容器设置弹出框提示初始化
            $('.layout-content-submit-set').popover({
                content: $layout.data('layout-content-set-tips') || '容器设置',
                trigger: 'hover focus',
                theme: 'sm'
            });

            // 新的元素新增样式
            var $new = $(this).prev();
            $new.addClass('layout-view-new');
            setTimeout(function () {
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
    $(document).on('click', '.layout-submit-set', function () {
        // 布局对象
        $layout_content_obj = $(this).parents('.layout-content-submit').next();

        // 配置数据
        var config_doc = '#offcanvas-layout-config';
        var json = $layout_content_obj.attr('data-json') || null;
        if (json != null) {
            // 数据解析
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)) || null;
        }
        // 获取表单字段并赋空值
        if ((json || null) == null) {
            json = GetFormVal(config_doc, true);
            for (var i in json) {
                json[i] = '';
            }
        }
        // 节点数据处理
        json = ModuleConfigBaseContentHandle(json);

        // 表单赋值
        FormDataFill(json, config_doc);

        // 单选框初始化
        $offcanvas_layout_config.find('input[type="checkbox"], input[type="radio"]').uCheck();

        // 背景色组件处理
        ModuleColorpickerHandle($offcanvas_layout_config);

        // 指定操作类型
        $offcanvas_layout_config.attr('data-ent', 'layout-content-children');

        // 打开配置
        $offcanvas_layout_config.offCanvas('open');
    });

    // 布局移除
    $(document).on('click', '.layout-submit-del', function () {
        var $this = $(this);
        AMUI.dialog.confirm({
            title: $layout.data('layout-reminder-title') || '温馨提示',
            content: $layout.data('layout-reminder-msg') || '移除后不可恢复、确定继续吗？',
            onConfirm: function (e) {
                // 移除布局
                $this.parents('.layout-view').remove();

                // 无布局则添加提示信息
                if ($('.layout-container .layout-view').length <= 0) {
                    $layout.html('<div class="layout-container-tips">' + ($layout.data('layout-container-tips') || '布局拖放到该区域松开鼠标即可') + '</div>');
                }
            }
        });
    });

    // 布局容器设置
    $(document).on('click', '.layout-content-submit-set', function () {
        // 容器内容对象
        $layout_content_obj = $(this).parents('.layout-content-container');

        // 配置数据
        var config_doc = '#offcanvas-layout-config';
        var json = $layout_content_obj.attr('data-json') || null;
        if (json != null) {
            // 数据解析
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)) || null;
        }
        // 获取表单字段并赋空值
        if ((json || null) == null) {
            json = GetFormVal(config_doc, true);
            for (var i in json) {
                json[i] = '';
            }
        }
        // 节点数据处理
        json = ModuleConfigBaseContentHandle(json);

        // 表单赋值
        FormDataFill(json, config_doc);
        // 单选框初始化
        $offcanvas_layout_config.find('input[type="checkbox"], input[type="radio"]').uCheck();

        // 背景色组件处理
        ModuleColorpickerHandle($offcanvas_layout_config);

        // 指定操作类型
        $offcanvas_layout_config.attr('data-ent', 'layout-content-container');

        // 打开配置
        $offcanvas_layout_config.offCanvas('open');
    });

    // 模块拖放
    $(document).on('dragstart', '.renovation-drag button', function (e) {
        // 布局配置
        var drag_value = $(this).data('value');

        // 拖拽过程中经过的元素
        $(document).on('dragenter', '.layout-content', function (e) {
            // 当前是否在布局模式下操作
            if ($('.renovation-tabs .am-tabs-nav li.am-active').data('value') == 'module') {
                $('.layout-content').removeClass('layout-content-dragenter');
                if (!$(this).hasClass('.layout-content-tips')) {
                    $(this).addClass('layout-content-dragenter');
                }
            }
        });
        // 拖拽离开元素移除过程中添加的样式
        $(document).on('dragleave', '.renovation-drag button', function (e) {
            $('.layout-content').removeClass('layout-content-dragenter');
        });
        // 拖拽结束
        $(document).on('dragend', '.renovation-drag button', function (e) {
            // 移除过程中添加的样式
            $('.layout-content').removeClass('layout-content-dragenter');

            // 关闭事件
            $('.layout-content').off('dragover');
            $('.layout-content').off('dragenter');
            $('.layout-content').off('drop');
        });

        // 拖拽过程中一直在元素内、并阻止默认事件
        $(document).on('dragover', '.layout-content', function (event) {
            event.preventDefault();
            event.stopPropagation();
        });

        // 拖放接收事件
        $('.layout-content').on('drop', function (e) {
            // 生成模块html
            var html = RenovationModuleDragHtmlCreate(drag_value);
            if (html != false) {
                // 添加模块
                $(this).find('.layout-content-tips').remove();
                $(this).append(html);

                // 模块弹出框提示初始化
                $('.module-view-submit-drag').popover({
                    content: $layout.data('module-drag-title') || '拖拽排序',
                    trigger: 'hover focus',
                    theme: 'sm'
                });
                $('.module-view-submit-set').popover({
                    content: $layout.data('module-set-title') || '模块设置',
                    trigger: 'hover focus',
                    theme: 'sm'
                });
                $('.module-view-submit-del').popover({
                    content: $layout.data('module-del-title') || '模块移除',
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
    $(document).on('click', '.module-view-submit-del', function () {
        var $this = $(this);
        AMUI.dialog.confirm({
            title: $layout.data('layout-reminder-title') || '温馨提示',
            content: $layout.data('layout-reminder-msg') || '移除后不可恢复、确定继续吗？',
            onConfirm: function (e) {
                // 模块容器
                var $module_view = $this.parents('.layout-content');

                // 移除模块
                $this.parents('.module-view').remove();

                // 无模块则添加提示信息
                if ($module_view.find('.module-view').length <= 0) {
                    $module_view.html('<div class="layout-content-tips">' + ($layout.data('layout-content-tips') || '模块内容区域') + '</div>');
                }
            }
        });
    });

    // 模块设置
    $(document).on('click', '.module-view-submit-set', function () {
        // 基础
        var $parent = $(this).parents('.module-view-submit-container');
        var value = $parent.data('value') || null;
        var index = $parent.data('index') || null;
        var doc = $parent.data('doc') || null;
        if (value == null || index == null || doc == null) {
            Prompt($layout.data('module-attr-tips') || '模块属性有误');
            return false;
        }

        // 公共数据
        var json = $(doc).attr('data-json') || null;
        if (json != null) {
            // 数据解析
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)) || null;
        }

        // 配置模块
        var config_doc = '#offcanvas-module-config-' + value;
        var $config = $(config_doc);
        $config.attr('data-value', value);
        $config.attr('data-index', index);
        $config.attr('data-doc', doc);

        // 获取表单字段并赋空值
        if ((json || null) == null) {
            var fields_dv = {
                goods_order_by_type: 0,
                goods_order_by_rule: 0,
                view_list_show_style: 'routine',
                view_list_number_sm: 2,
                view_list_number_md: 5,
                view_list_number_lg: 5,
                goods_data_type: "goods"
            };
            json = GetFormVal(config_doc, true);
            for (var i in json) {
                json[i] = (fields_dv[i] == undefined) ? '' : fields_dv[i];
            }
        }

        // 展示模式默认值处理、默认常规模式
        if ((json.view_list_show_style || null) == null) {
            json['view_list_show_style'] = 'routine';
        }

        // 根据模块类型处理
        switch (value) {
            // 图片模块
            case 'images':
                var default_images = $config.data('default-images');
                var html = '<li>';
                html += '<input type="text" name="content_images" value="' + (json.content_images || '') + '" data-validation-message="' + ($layout.data('upload-images-tips') || '请上传图片') + '" value="" required />';
                html += '<img src="' + (json.content_images || default_images) + '" />';
                html += '</li>';
                $config.find('ul.module-images-type-images-view').html(html);

                // 链接地址处理
                $config.find('.form-view-choice-container-content').html(ModuleConfigImagesToContentHtml(json.content_to_name || ''));
                break;

            // 多图
            case 'many-images':
                var html = '';
                if ((json.data_list || null) != null && json.data_list.length > 0) {
                    for (var i in json.data_list) {
                        html += ModuleConfigManyImagesItemContentHtml(json.data_list[i]['images'], json.data_list[i]['type'], json.data_list[i]['name'], json.data_list[i]['value']);
                    }
                }
                $config.find('.config-many-images-container').html(html);
                break;

            // 图文
            case 'images-text':
                var html = '';
                if ((json.data_list || null) != null && json.data_list.length > 0) {
                    for (var i in json.data_list) {
                        html += ModuleConfigImagesTextItemContentHtml(json.data_list[i]);
                    }
                }
                $config.find('.config-images-text-container').html(html);
                // 展示模式默认空，必须要选择
                if ((json.view_list_show_style || null) == null) {
                    json['view_list_show_style'] = '';
                }
                break;

            // 图片魔方
            case 'images-magic-cube':
                var html = '';
                if ((json.data_list || null) != null && json.data_list.length > 0) {
                    html += ModuleConfigImagesMagicCubeItemContentHtml(json);
                }
                $config.find('.config-images-magic-cube-container').html(html);
                var $tips_msg = $offcanvas_config_images_magic_cube.find('.tips-msg');
                if ((html || null) == null) {
                    $tips_msg.removeClass('am-hide');
                } else {
                    $tips_msg.addClass('am-hide');
                }
                // 展示模式默认空，必须要选择
                if ((json.view_list_show_style || null) == null) {
                    json['view_list_show_style'] = '';
                }
                break;

            // 视频
            case 'video':
                // 视频地址
                $config.find('.module-video-type-view-video video').attr('src', json.content_video || '');

                // 封面图片
                var html = '';
                if ((json.content_images || null) != null) {
                    html += '<li>';
                    html += '<input type="text" name="content_images" value="' + json.content_images + '" /><img src="' + json.content_images + '" />';
                    html += '<i class="iconfont icon-close"></i>';
                    html += '</li>';
                }
                $config.find('.module-video-type-view-images').html(html);
                break;

            // 商品模块
            case 'goods':
                var goods_data_type = json.goods_data_type || 'goods';
                switch (goods_data_type) {
                    // 商品
                    case 'goods':
                        $config.find('.config-goods-list').html(ModuleConfigGoodsItemContentHtml(json.data_list));

                        // 清空分类选择
                        $config.find('.offcanvas-config-goods-category-container .form-view-choice-container-content').html(ModuleConfigGoodsCategoryContentHtml());
                        $config.find('input[name="goods_category_value"]').val('');
                        break;

                    // 商品分类
                    case 'category':
                        var category = ((json.goods_category_value || null) == null) ? null : JSON.parse(decodeURIComponent(json.goods_category_value)) || null;
                        var name = (category == null || category.length <= 0) ? '' : category[category.length - 1]['name'];
                        var html = ModuleConfigGoodsCategoryContentHtml(name);
                        $config.find('.offcanvas-config-goods-category-container .form-view-choice-container-content').html(html);

                        // 清空商品列表
                        $config.find('.config-goods-list').html('');
                        $config.find('input[name="goods_ids"]').val('');
                        break;

                    // 未定义
                    default:
                        console.info(($layout.data('module-assembly-not-exist-tips') || '模块组件未定义') + '[' + goods_data_type + ']')
                }

                // tab处理
                var index = 0;
                $config.find('.am-tabs-nav li').each(function (k, v) {
                    if ($(this).find('a').data('value') == goods_data_type) {
                        index = k;
                    }
                });
                $config.find('.am-tabs-nav li').removeClass('am-active');
                $config.find('.am-tabs-nav li').eq(index).addClass('am-active');
                $config.find('.am-tabs-bd .am-tab-panel').removeClass('am-active');
                $config.find('.am-tabs-bd .am-tab-panel').eq(index).addClass('am-active');
                break;

            // 标题
            case 'title':
                // 关键字
                var html = '';
                if ((json.keywords_list || null) != null && json.keywords_list.length > 0) {
                    for (var i in json.keywords_list) {
                        html += ModuleConfigTitleKeywordsContentHtml(json.keywords_list[i]);
                    }
                }
                $config.find('.config-title-container').html(html);

                // 链接地址处理
                $config.find('.form-view-choice-container-content').html(ModuleConfigImagesToContentHtml(json.content_to_name || ''));
                break;

            default:
                if ($config.length == 0) {
                    Prompt(($layout.data('base-module-not-config-tips') || '模块未配置') + '(' + value + ')');
                    return false;
                }
        }

        // 表单数据赋值
        FormDataFill(json, config_doc);

        // 背景色组件处理
        ModuleColorpickerHandle($config);

        // 颜色选择器初始化
        ColorPickerInit();

        // 单选框初始化
        $config.find('input[type="checkbox"], input[type="radio"]').uCheck();

        // 更新选择组件
        $config.find('.chosen-select').trigger('chosen:updated');

        // 打开弹窗
        $config.offCanvas('open');
    });

    // 配置图片 - 选择页面事件
    $offcanvas_config_images.on('click', '.form-view-choice-container-submit', function (e) {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 配置图片 - 选择页面 - 移除
    $offcanvas_config_images.on('click', '.form-view-choice-container-active i.am-icon-close', function (e) {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 页面选择 tab 切换事件
    $modal_pages_select.on('click', '.am-tabs-nav a', function () {
        // 所有处理
        $modal_pages_select.find('.am-tabs-bd ul li').removeClass('active');
        $modal_pages_select.find('.am-tabs-bd ul li a').attr('data-json', '');

        // 名称还原
        $modal_pages_select.find('.am-tabs-bd ul li').each(function (k, v) {
            var $o = $(this).find('a');
            $o.attr('data-json', '');
            $o.find('span').text($o.data('name'));
        });

        // 自定义链接清空
        $('.pages-custom-url-container input').val('');
    });

    // 页面选择切换
    $modal_pages_select.on('click', '.am-tabs-bd ul li a', function () {
        // 选中状态
        var $parent = $(this).parents('.am-tab-panel');
        $parent.find('li').removeClass('active');
        $(this).parent().addClass('active');

        // 参数值
        var value = $(this).data('value') || null;
        var name = $(this).data('name') || null;
        var json = $(this).attr('data-json') || null;
        if (value == null || name == null) {
            Prompt($layout.data('params-tips') || '参数值有误');
            return false;
        }
        if (json != null) {
            json = JSON.parse(decodeURIComponent(json)) || null;
        }

        // 根据类型处理
        switch (value) {
            // 单一商品
            case 'goods':
                // 弹窗数据设置并打开
                var goods_ids = (json == null || (json.id || null) == null) ? '' : json.id;
                $popup_goods_select.attr('data-goods-ids', goods_ids);
                $popup_goods_select.attr('data-type', 'single-goods');
                $popup_goods_select.attr('data-is-single-choice', 1);
                $popup_goods_select.modal({ closeViaDimmer: false });
                // 弹窗没有商品内容则触发搜索
                if($popup_goods_select.find('.goods-list-container ul li').length == 0)
                {
                    $popup_goods_select.find('.forth-selection-container .search-submit').trigger('click');
                }
                break;

            // 搜索页面
            case 'goods_search':
                var index = 0;
                var type = 'category';
                var value = null;
                if (json != null && (json.type || null) != null) {
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
                if (value != null) {
                    switch (type) {
                        // 商品分类
                        case 'category':
                            for (var i in value) {
                                var $gcs = $popup_goods_search.find('.form-container-category .goods-category-select-' + (parseInt(i) + 1));
                                if ($gcs.length > 0 && $gcs.find('li').length > 0) {
                                    $gcs.find('li').each(function (k, v) {
                                        if ($(this).find('a').data('value') == value[i]['id']) {
                                            $(this).find('a').trigger('click');
                                        }
                                    });
                                }
                            }
                            break;

                        // 品牌
                        case 'brand':
                            $popup_goods_search.find('.form-container-brand ul li').each(function (k, v) {
                                if ($(this).find('a').data('value') == value.id) {
                                    $(this).addClass('active');
                                }
                            });
                            break;

                        // 关键字
                        case 'keywords':
                            $popup_goods_search.find('.form-container-keywords input').val(value);
                            break;
                    }
                }

                $popup_goods_search.modal({ closeViaDimmer: false });
                break;
        }
    });

    // 页面选择确认事件
    $modal_pages_select.on('click', '.pages-confirm-submit', function () {
        // 选中tab
        var index = $modal_pages_select.find('.am-tabs-nav li.am-active').index();

        // 参数值、自定义链接、常规页面选择
        if (index == 2) {
            var to_type = 'pages-custom-url';
            var to_name = $layout.data('custom-url-name') || '自定义链接';
            var to_value = GetFormVal('.pages-custom-url-container', true);
            var count = 0;
            for (var i in to_value) {
                if ((to_value[i] || null) == null) {
                    count++;
                }
            }
            if (count >= Object.keys(to_value).length) {
                Prompt($layout.data('custom-url-tips') || '请至少填写一个地址');
                return false;
            }
            to_value = encodeURIComponent(JSON.stringify(to_value));
        } else {
            var $obj = $modal_pages_select.find('.am-tab-panel.am-active ul li.active a');
            var to_type = $obj.data('value') || '';
            var to_name = $obj.data('name') || '';
            var to_value = $obj.attr('data-json') || '';
            var json = null;
            if (to_value != '') {
                json = JSON.parse(decodeURIComponent(to_value)) || null;
            }
            if (to_type == '' || to_name == '') {
                Prompt($layout.data('before-choice-page-tips') || '请先选择页面');
                return false;
            }

            // 根据类型处理
            switch (to_type) {
                // 单一商品
                case 'goods':
                    if (json == null) {
                        Prompt($layout.data('choice-goods-tips') || '请选择商品');
                        return false;
                    }

                    // 选择位置是否存在
                    if ($page_parent_obj == null) {
                        Prompt($layout.data('before-choice-url-position-tips') || '请先选择链接位置');
                        return false;
                    }

                    // 显示名称
                    to_name += '（' + json['title'] + '）';
                    break;

                // 搜索页面
                case 'goods_search':
                    if (json == null) {
                        Prompt($layout.data('before-config-goods-search-tips') || '请先配置商品搜索');
                        return false;
                    }

                    // 显示名称
                    to_name += ModuleConfigGoodsSearchPageShowName(json);
                    break;
            }
        }

        // 设置数据
        var $parent = $page_parent_obj.parents('.form-view-choice-container');
        var key = $parent.data('key');
        var index = (key == undefined) ? '' : '_' + key;
        $parent.find('input[name="content_to_type' + index + '"]').val(to_type);
        $parent.find('input[name="content_to_name' + index + '"]').val(to_name);
        $parent.find('input[name="content_to_value' + index + '"]').val(to_value);
        $page_parent_obj.html(ModuleConfigImagesToContentHtml(to_name));
        $modal_pages_select.modal('close');
    });

    // 商品配置-选择商品和分类
    $offcanvas_config_goods.on('click', '.form-view-choice-container-submit', function () {
        var value = $(this).parents('.form-view-choice-container').data('value');
        switch (value) {
            // 商品
            case 'goods':
                // 弹窗数据设置并打开
                var goods_ids = OffcanvasModuleConfigGoodsIds();
                $popup_goods_select.attr('data-goods-ids', goods_ids);
                $popup_goods_select.attr('data-type', 'list-goods');
                $popup_goods_select.attr('data-is-single-choice', 0);
                $popup_goods_select.modal({ closeViaDimmer: false });
                // 弹窗没有商品内容则触发搜索
                if($popup_goods_select.find('.goods-list-container ul li').length == 0)
                {
                    $popup_goods_select.find('.forth-selection-container .search-submit').trigger('click');
                }
                break;

            // 商品分类
            case 'category':
                // 分类数据
                var json = $offcanvas_config_goods.find('.offcanvas-config-goods-category-container input[name="goods_category_value"]').val() || null;
                if (json != null) {
                    json = JSON.parse(decodeURIComponent(json)) || null;
                }
                if (json == null) {
                    // 数据清空
                    $popup_goods_category.find('.form-container-category').find('.goods-category-select-2, .goods-category-select-3').html('').addClass('am-hide');
                    $popup_goods_category.find('.form-container-category .already-select-tips').attr('data-value', '').addClass('am-hide').find('strong').text('');
                    $popup_goods_category.find('.goods-category-choice-content ul li.active').removeClass('active');
                } else {
                    // 生成选择的数据
                    for (var i in json) {
                        var $gcs = $popup_goods_category.find('.form-container-category .goods-category-select-' + (parseInt(i) + 1));
                        if ($gcs.length > 0 && $gcs.find('li').length > 0) {
                            $gcs.find('li').each(function (k, v) {
                                if ($(this).find('a').data('value') == json[i]['id']) {
                                    $(this).find('a').trigger('click');
                                }
                            });
                        }
                    }
                }

                $popup_goods_category.modal({ closeViaDimmer: false });
                break;

            default:
                Prompt(($layout.data('type-event-not-exist-tips') || '类型事件未定义') + '[' + value + ']')
        }
    });

    // 商品配置-商品选择-移除
    $(document).on('click', '#offcanvas-module-config-goods .config-goods-list li a.am-close', function () {
        // 商品id处理
        var value = $(this).parent().data('gid');
        var goods_ids = $offcanvas_config_goods.find('input[name="goods_ids"]').val() || null;
        if (goods_ids != null) {
            goods_ids = goods_ids.split(',');
            for (var i in goods_ids) {
                if (goods_ids[i] == value) {
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
    $popup_goods_category.on('click', '.confirm-submit', function () {
        // 分类数据
        var json = $popup_goods_category.find('.already-select-tips').attr('data-value') || null;
        if (json != null) {
            json = JSON.parse(decodeURIComponent(json)) || null;
        }
        if (json == null) {
            Prompt($layout.data('before-choice-goods-category-tips') || '请先选择商品分类');
            return false;
        }

        // 当前选择的节点
        var data = json[json.length - 1];
        var html = ModuleConfigGoodsCategoryContentHtml(data.name);
        var $offcanvas_category = $offcanvas_config_goods.find('.offcanvas-config-goods-category-container');
        $offcanvas_category.find('.form-view-choice-container-content').html(html);
        $offcanvas_category.find('input[name="goods_category_value"]').val(encodeURIComponent(JSON.stringify(json)));
        $popup_goods_category.modal('close');
    });

    // 商品配置-分类选择确认-移除
    $offcanvas_config_goods.on('click', '.offcanvas-config-goods-category-container .form-view-choice-container-active i.am-icon-close', function () {
        var $parent = $(this).parents('.form-view-choice-container-content');
        $parent.html('<a href="javascript:;" class="form-view-choice-container-submit">' + ($layout.data('choice-goods-category-tips') || '请选择商品分类') + '</a>');
        $parent.parent().find('input[name="goods_category_value"]').val('');
        return false;
    });

    // 商品配置-tab切换
    $offcanvas_config_goods.on('click', '.am-tabs-nav a', function () {
        $offcanvas_config_goods.find('input[name="goods_data_type"]').val($(this).data('value') || 'goods');
    });


    // 商品搜索选择
    // 分页
    $('.goods-page-container').html(PageLibrary());

    // 搜索商品
    $(document).on('click', '.forth-selection-container .search-submit, .pagelibrary li a', function () {
        // 分页处理
        var is_active = $(this).data('is-active') || 0;
        if (is_active == 1) {
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
        if ($this.hasClass('search-submit')) {
            $this.button('loading');
        }
        $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> ' + ($('.goods-list-container').data('loading-msg')) + '</div>');
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'post',
            data: { "page": page, "category_field": category_field, "category_id": category_id, "keywords": keywords, "goods_ids": goods_ids },
            dataType: 'json',
            success: function (res) {
                $this.button('reset');
                if (res.code == 0) {
                    $('.goods-list-container').attr('data-is-init', 0);
                    $('.goods-list-container ul.am-gallery').html(res.data.data);
                    $('.goods-page-container').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 4));
                } else {
                    Prompt(res.msg);
                    $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> ' + res.msg + '</div>');
                }
            },
            error: function (res) {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
                Prompt(msg, null, 30);
                $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> ' + msg + '</div>');
            }
        });
    });

    // 商品添加/删除
    $(document).on('click', '.goods-list-container .goods-add-submit, .goods-list-container .goods-del-submit', function () {
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
        if (is_single_choice == 1) {
            // 还原选项
            $popup_goods_select.find('.goods-list-container li').each(function (k, v) {
                $(this).find('.icon-submit-container').html($(this).data('add-html'));
            });

            if (type == 'add') {
                goods_ids = [goods_id];
                $parent.find('.icon-submit-container').html($parent.data('del-html'));
            } else {
                goods_ids = [];
            }
        } else {
            // 商品是否已经添加
            var index = goods_ids.indexOf(goods_id.toString());
            if (index == -1) {
                goods_ids.push(goods_id);
            } else {
                goods_ids.splice(index, 1);
            }
            var icon_html = $parent.data((type == 'add' ? 'del' : 'add') + '-html');
            $this.parent().html(icon_html);
        }

        // 选择的商品id赋值
        $popup_goods_select.attr('data-goods-ids', goods_ids.join(','));
    });

    // 商品选择确认事件
    $popup_goods_select.on('click', '.confirm-submit', function () {
        // 已选商品
        var goods_ids = $popup_goods_select.attr('data-goods-ids') || null;
        if (goods_ids == null) {
            Prompt($layout.data('before-choice-goods-tips') || '请先选择商品');
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
            url: RequestUrlHandle(url),
            type: 'post',
            data: { "goods_ids": goods_ids },
            dataType: 'json',
            success: function (res) {
                $this.button('reset');
                if (res['code'] == 0) {
                    if ((res.data || null) != null && res.data.length > 0) {
                        // 根据类型处理不同业务逻辑
                        var data = res.data;
                        switch (type) {
                            // 单一商品选择
                            case 'single-goods':
                                var $single_goods = $modal_pages_select.find('.am-tabs-bd ul li.page-goods a');
                                $single_goods.find('span').text($single_goods.data('name') + '（' + data[0]['title'] + '）');
                                $single_goods.attr('data-json', encodeURIComponent(JSON.stringify(data[0])));
                                break;

                            // 列表商品
                            case 'list-goods':
                                // 商品id赋值
                                var goods_ids = data.map(function (e) { return e.id; }).join(',');
                                $offcanvas_config_goods.find('input[name="goods_ids"]').val(goods_ids);

                                // html 拼接
                                $offcanvas_config_goods.find('.config-goods-list').html(ModuleConfigGoodsItemContentHtml(data));
                                break;
                        }

                        // 关闭商品窗口
                        $popup_goods_select.modal('close');

                        // 清空选择的商品id
                        $popup_goods_select.attr('data-goods-ids', '');
                    } else {
                        Prompt($layout.data('goods-data-empty-tips') || '商品信息为空');
                    }
                } else {
                    Prompt(res.msg);
                }
            },
            error: function (res) {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
                Prompt(msg, null, 30);
            }
        });
    });

    // 商品搜索tab类型切换事件
    $popup_goods_search.on('click', '.am-tabs-nav a', function () {
        $popup_goods_search.find('.form-container-category').find('.goods-category-select-2, .goods-category-select-3').html('').addClass('am-hide');
        $popup_goods_search.find('.form-container-category .already-select-tips').attr('data-value', '').addClass('am-hide').find('strong').text('');
        $popup_goods_search.find('.goods-category-choice-content ul li.active').removeClass('active');
        $popup_goods_search.find('.form-container-keywords input').val('');
    });

    // 分类选择切换
    $(document).on('click', '.layout-category-choice .goods-category-choice-content ul li a', function () {
        // 父级
        var $parents = $(this).parents('.goods-category-choice');

        // 选中
        $(this).parent().addClass('active').siblings().removeClass('active');

        // 分类数据
        var data = $(this).find('span.data-json').text() || null;
        if (data != null) {
            data = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8))
        }

        // 参数
        var level = $(this).parents('ul').data('level') || 1;
        var level_next = level + 1;

        // 拼接html
        if (data != null && level < 3) {
            var html = '';
            for (var i in data) {
                var json = ((data[i]['items'] || null) == null || data[i]['items'].length == 0) ? '' : encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data[i]['items']))));
                html += '<li><a href="javascript:;" data-value="' + data[i]['id'] + '">';
                html += '<span class="data-name">' + data[i]['name'] + '</span>';
                html += '<span class="data-json am-hide">' + json + '</span>';
                if ((data[i]['items'] || null) != null && data[i]['items'].length > 0) {
                    html += '<i class="iconfont icon-angle-right am-fr"></i>';
                }
                html += '</a></li>';
            }
            $parents.find('.goods-category-select-' + level_next).html(html).removeClass('am-hide');
        }

        // 级别数据处理
        if (data == null) {
            $parents.find('.goods-category-select-' + level_next).addClass('am-hide').html('');
        } else {
            $parents.find('.goods-category-select-' + level_next).removeClass('am-hide');
        }

        // 选择第一级的时候隐藏第三级
        if (level == 1) {
            $parents.find('.goods-category-select-3').addClass('am-hide').html('');
        }

        // 提示信息展示
        var text = '';
        var value = [];
        $parents.find('ul li.active').each(function (k, v) {
            if (k > 0) {
                text += ' > ';
            }
            var name = $(this).find('a span.data-name').text();
            value.push({ "id": $(this).find('a').data('value'), "name": name });
            text += name;
        });
        var $tips = $parents.find('.already-select-tips');
        $tips.removeClass('am-hide');
        $tips.find('strong').text(text);

        // 选择数据
        $tips.attr('data-value', encodeURIComponent(JSON.stringify(value)));
    });

    // 左侧配置 - 多图- 多图添加图片
    $offcanvas_config_many_images.on('click', '.config-many-images-item-add', function () {
        $offcanvas_config_many_images.find('.config-many-images-container').append(ModuleConfigManyImagesItemContentHtml());
    });

    // 配置多图 - 选择页面事件
    $offcanvas_config_many_images.on('click', '.form-view-choice-container-submit', function (e) {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 配置多图 - 链接地址 - 移除
    $offcanvas_config_many_images.on('click', '.form-view-choice-container-active i.am-icon-close', function (e) {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 左侧配置 - 配置多图 - 移除
    $(document).on('click', '#offcanvas-module-config-many-images .config-many-images-container .am-panel a.am-close', function () {
        $(this).parent().remove();
    });

    // 配置多图 - 展示模式切换
    $(document).on('click', '#offcanvas-module-config-many-images input[name="view_list_show_style"]', function () {
        $base_show_style_value_obj = $(this).parents('.config-view-show-style').find('input[name="view_list_show_style_value"]');
        switch ($(this).val()) {
            // 滚动
            case 'rolling':
                // 数据填充
                var json = ViewRollingShowStyleValueHandle($base_show_style_value_obj.val());
                if (json['item_margin'] <= 0) {
                    json['item_margin'] = '';
                }
                FormDataFill(json, '#modal-module-rolling-config');

                // 开关状态
                $modal_rolling_config.find('input[name="is_auto_play"]').switch().toggleSwitch(json.is_auto_play);
                $modal_rolling_config.find('input[name="is_nav_dot"]').switch().toggleSwitch(json.is_nav_dot);

                // 开启弹窗
                $modal_rolling_config.modal({
                    width: 260,
                    height: 370,
                    closeViaDimmer: false
                });
                break;

            // 列表
            case 'list':
                // 数据填充
                var json = ViewListShowStyleValueHandle($base_show_style_value_obj.val());
                if (json['style_margin'] <= 0) {
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

            default:
                $base_show_style_value_obj.val('');
        }
    });


    // 左侧配置 - 图文- 图文添加
    $offcanvas_config_images_text.on('click', '.config-images-text-item-add', function () {
        $offcanvas_config_images_text.find('.config-images-text-container').append(ModuleConfigImagesTextItemContentHtml());
        // 单选框初始化
        $offcanvas_config_images_text.find('input[type="checkbox"], input[type="radio"]').uCheck();
        // 颜色选择器初始化
        ColorPickerInit();
    });

    // 配置图文 - 选择页面事件
    $offcanvas_config_images_text.on('click', '.form-view-choice-container-submit', function (e) {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 配置图文 - 链接地址 - 移除
    $offcanvas_config_images_text.on('click', '.form-view-choice-container-active i.am-icon-close', function (e) {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 左侧配置 - 配置图文 - 移除
    $(document).on('click', '#offcanvas-module-config-images-text .config-images-text-container .am-panel a.am-close', function () {
        $(this).parent().remove();
    });

    // 配置图文 - 展示模式切换
    $(document).on('click', '#offcanvas-module-config-images-text input[name="view_list_show_style"]', function () {
        $base_show_style_value_obj = $(this).parents('.config-view-show-style').find('input[name="view_list_show_style_value"]');
        switch ($(this).val()) {
            // 滚动
            case 'rolling':
                // 数据填充
                var json = ViewRollingShowStyleValueHandle($base_show_style_value_obj.val());
                if (json['item_margin'] <= 0) {
                    json['item_margin'] = '';
                }
                FormDataFill(json, '#modal-module-rolling-config');

                // 开关状态
                $modal_rolling_config.find('input[name="is_auto_play"]').switch().toggleSwitch(json.is_auto_play);
                $modal_rolling_config.find('input[name="is_nav_dot"]').switch().toggleSwitch(json.is_nav_dot);

                // 开启弹窗
                $modal_rolling_config.modal({
                    width: 260,
                    height: 370,
                    closeViaDimmer: false
                });
                break;

            // 默认
            default:
                // 数据填充
                var json = ViewListShowStyleValueHandle($base_show_style_value_obj.val());
                if (json['style_margin'] <= 0) {
                    json['style_margin'] = '';
                }
                FormDataFill(json, '#modal-module-list-config');

                // 开启弹窗
                $modal_list_config.modal({
                    width: 260,
                    height: 225,
                    closeViaDimmer: false
                });
        }
    });


    // 配置图片魔方 - 选择页面事件
    $offcanvas_config_images_magic_cube.on('click', '.form-view-choice-container-submit', function (e) {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 配置图片魔方 - 链接地址 - 移除
    $offcanvas_config_images_magic_cube.on('click', '.form-view-choice-container-active i.am-icon-close', function (e) {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 配置图片魔方 - 展示模式切换
    $(document).on('click', '#offcanvas-module-config-images-magic-cube input[name="view_list_show_style"]', function () {
        var html = ModuleConfigImagesMagicCubeItemContentHtml();
        if (html !== false) {
            $offcanvas_config_images_magic_cube.find('.config-images-magic-cube-container').append(html);
        }
    });

    // 配置商品 - 展示模式切换
    $(document).on('click', '#offcanvas-module-config-goods input[name="view_list_show_style"]', function () {
        $base_show_style_value_obj = $(this).parents('.config-view-show-style').find('input[name="view_list_show_style_value"]');
        switch ($(this).val()) {
            // 滚动
            case 'rolling':
                // 数据填充
                var json = ViewRollingShowStyleValueHandle($base_show_style_value_obj.val());
                if (json['item_margin'] <= 0) {
                    json['item_margin'] = '';
                }
                FormDataFill(json, '#modal-module-rolling-config');

                // 开关状态
                $modal_rolling_config.find('input[name="is_auto_play"]').switch().toggleSwitch(json.is_auto_play);
                $modal_rolling_config.find('input[name="is_nav_dot"]').switch().toggleSwitch(json.is_nav_dot);

                // 开启弹窗
                $modal_rolling_config.modal({
                    width: 260,
                    height: 370,
                    closeViaDimmer: false
                });
                break;
        }
    });


    // 左侧配置 - 标题 - 右侧按钮 - 选择页面事件
    $offcanvas_config_title.on('click', '.form-view-choice-container-submit', function (e) {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 标题 - 右侧按钮 - 移除
    $offcanvas_config_title.on('click', '.form-view-choice-container-active i.am-icon-close', function (e) {
        OffcanvasConfigPagesRemove($(this), e);
    });

    // 左侧配置 - 标题 - 添加关键字
    $offcanvas_config_title.on('click', '.config-title-item-add', function () {
        ModuleConfigTitleKeywordsOpen();
    });

    // 左侧配置 - 标题 - 关键字 - 编辑
    $(document).on('click', '#offcanvas-module-config-title .config-title-container li a.am-icon-edit', function () {
        var $parent = $(this).parent();
        $base_title_keywords_obj = $parent;
        ModuleConfigTitleKeywordsOpen(1, $parent.find('input').val() || null);
    });

    // 左侧配置 - 标题 - 关键字 - 移除
    $(document).on('click', '#offcanvas-module-config-title .config-title-container li a.am-icon-remove', function () {
        $(this).parents('li').remove();
    });

    // 左侧配置 - 标题 - 关键字 - 选择页面事件
    $modal_title_keywords.on('click', '.form-view-choice-container-submit', function (e) {
        OffcanvasConfigPagesChoice($(this), e);
    });

    // 左侧配置 - 标题 - 关键字 - 移除
    $modal_title_keywords.on('click', '.form-view-choice-container-active i.am-icon-close', function (e) {
        OffcanvasConfigPagesRemove($(this), e);
    });


    // 页面布局数据保存
    var $layout_operate_container = $('.layout-operate-container');
    $layout_operate_container.on('click', 'button', function () {
        // 基础配置
        var data = $layout_operate_container.attr('data-json') || null;
        if (data != null) {
            data = JSON.parse(decodeURIComponent(data)) || null;
        }
        if (data == null) {
            data = {};
        }

        // 设计配置
        data['config'] = JSON.stringify(LayoutViewConfig());

        // 保存数据
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: RequestUrlHandle($layout_operate_container.data('save-url')),
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res['code'] == 0) {
                    Prompt(res.msg, 'success');
                    setTimeout(function () {
                        var url = $layout_operate_container.find('a').attr('href') || null;
                        if (url == null || url == 'javascript:;') {
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
            error: function (xhr, type) {
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
                Prompt(msg, null, 30);
            }
        });
    });
});