
/**
 * 公共提示
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:32:39+0800
 * @param {[string]}	msg  [提示信息]
 * @param {[string]} 	type [类型（失败:error, 警告:warning, 成功:success）]
 * @param {[int]} 		time [自动关闭时间（秒）, 默认3秒]
 */
function Prompt (msg, type, time) {
    if (msg != undefined && msg != '') {
        // 存在的提示信息则不继续
        var status = true;
        $('.common-prompt').each(function (k, v) {
            if (status && $(this).find('.prompt-msg').text() == msg) {
                status = false;
            }
        });
        if (status) {
            // 是否已存在提示条
            var height = 55;
            var length = $('.common-prompt').length;

            // 提示信息添加
            if ((type || null) == null) {
                type = 'danger';
            }

            // icon图标, 默认错误
            var icon = 'am-icon-times-circle';
            switch (type) {
                // 成功
                case 'success':
                    icon = 'am-icon-check-circle';
                    break;

                // 警告
                case 'warning':
                    icon = 'am-icon-exclamation-circle';
                    break;
            }
            var index = parseInt(Math.random() * 1000001);
            var html = '<div class="common-prompt common-prompt-' + index + ' am-alert am-alert-' + type + ' am-animation-slide-top" data-index="' + index + '" style="top:' + ((height * length) + 20) + 'px;" data-am-alert><button type="button" class="am-close">&times;</button><div class="prompt-content"><i class="' + icon + ' am-icon-sm am-margin-right-sm"></i><p class="prompt-msg">' + msg + '</p></div></div>';
            $('body').append(html);

            // 自动关闭提示
            setTimeout(function () {
                $('.common-prompt-' + index).slideToggle(300, function () {
                    $('.common-prompt-' + index).remove();
                    $('.common-prompt').each(function (k, v) {
                        $(this).animate({ 'top': (k * height + 20) + 'px' });
                    });
                });
            }, (time || 3) * 1000);
            return true;
        }
    }
    return false;
}

/**
 * js数组转json
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:32:04+0800
 * @param  {[array]} 	all    	[需要被转的数组]
 * @param  {[object]} 	object 	[需要压进去的json对象]
 * @return {[object]} 			[josn对象]
 */
function ArrayTurnJson (all, object) {
    for (var name in all) {
        object.append(name, all[name]);
    }
    return object;
}

/**
 * 获取form表单的数据
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:31:19+0800
 * @param    {[string]}     element [元素的class或id]
 * @param    {[boolean]}    is_json [是否返回json对象（默认否）]
 * @return   {[object]}        		[josn对象]
 */
function GetFormVal (element, is_json) {
    var $form = (typeof (element) == 'object') ? $(element) : $(element);
    var object = new FormData();

    // input 常用类型
    $form.find('input[type="hidden"], input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="date"], input[type="url"], input[type="radio"]:checked, textarea, input[type="file"]').each(function (key, tmp) {
        if (tmp.type == 'file') {
            object.append(tmp.name, ($(this).get(0).files[0] == undefined) ? '' : $(this).get(0).files[0]);
        } else {
            object.append(tmp.name, tmp.value.replace(/^\s+|\s+$/g, ""));
        }
    });

    // select 单选择和多选择
    var tmp_all = [];
    var i = 0;
    $form.find('select').find('option').each(function (key, tmp) {
        var name = $(this).parents('select').attr('name');
        if (name != undefined && name != '') {
            if ($(this).is(':selected')) {
                var value = (tmp.value == undefined) ? '' : tmp.value;
                if ($(this).parents('select').attr('multiple') != undefined) {
                    // 多选择
                    if (tmp_all[name] == undefined) {
                        tmp_all[name] = [];
                        i = 0;
                    }
                    tmp_all[name][i] = value;
                    i++;
                } else {
                    // 单选择
                    if (object[name] == undefined) {
                        object.append(name, value);
                    }
                }
            }
        }
    });
    object = ArrayTurnJson(tmp_all, object);

    // input 复选框checkbox
    tmp_all = [];
    i = 0;
    $form.find('input[type="checkbox"]').each(function (key, tmp) {
        if (tmp.name != undefined && tmp.name != '') {
            if ($(this).is(':checked')) {
                if (tmp_all[tmp.name] == undefined) {
                    tmp_all[tmp.name] = [];
                    i = 0;
                }
                tmp_all[tmp.name][i] = tmp.value;
                i++;
            } else {
                // 滑动开关、未选中则0
                if (typeof ($(this).attr('data-am-switch')) != 'undefined') {
                    tmp_all[tmp.name] = 0;
                }
            }
        }
    });
    object = ArrayTurnJson(tmp_all, object);

    // 是否需要返回json对象
    if (is_json === true) {
        var json = {};
        object.forEach(function (value, key) {
            if ((key || null) != null) {
                json[key] = value
            }
        });
        return json;
    }
    return object;
}

/**
 * 方法是否已定义
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:30:37+0800
 * @param    {[string]}    fun_name [方法名]
 * @return 	 {[boolean]}        	[已定义true, 则false]
 */
function IsExitsFunction (fun_name) {
    try {
        if (typeof (eval(fun_name)) == "function") return true;
    } catch (e) { }
    return false;
}

/**
 * 根据tag对象获取值
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-10-07T20:53:40+0800
 * @param    {[object]}         tag_obj [tag对象]
 */
function GetTagValue (tag_obj) {
    // 默认值
    var v = null;

    // 标签名称
    var tag_name = tag_obj.prop("tagName");

    // input
    var type = tag_obj.attr('type');
    switch (type) {
        // 单选框
        case 'checkbox':
            v = tag_obj.is(':checked') ? tag_obj.val() : null;
            break;
        // 单选框
        case 'radio':
            v = tag_obj.is(':checked') ? tag_obj.val() : null;
            break;

        // 其它选择
        default:
            v = tag_obj.val() || null;
    }
    return v;
}

/**
 * 公共表单校验, 添加class form-validation 类的表单自动校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:22:39+0800
 * @param    {[string] [form_name] 		[标题class或id]}
 * @param    {[string] [action] 		[请求地址]}
 * @param    {[string] [method] 		[请求类型 POST, GET]}
 * @param    {[string] [request-type] 	[回调类型 ajax-url, ajax-fun, ajax-reload]}
 * @param    {[string] [request-value] 	[回调值 ajax-url地址 或 ajax-fun方法]}
 */

function FromInit (form_name) {
    if (form_name == undefined) {
        form_name = 'form.form-validation';
    }
    var editor_tag_name = 'editor-tag';
    var $form = $(form_name);
    if ($form.length <= 0) {
        return false;
    }
    var $editor_tag = $form.find('[id=' + editor_tag_name + ']');
    var editor_count = $editor_tag.length;
    if (editor_count > 0) {
        // 编辑器初始化
        var editor = UE.getEditor(editor_tag_name);

        // 编辑器内容变化时同步到 textarea
        editor.addListener('contentChange', function () {
            editor.sync();

            // 触发验证
            $editor_tag.trigger('change');
        });
    }
    $form.validator(
        {
            // 自定义校验规则
            validate: function (validity) {
                // 二选一校验
                if ($(validity.field).is('.js-choice-one')) {
                    var tag = $(validity.field).attr('data-choice-one-to');
                    if (typeof ($(validity.field).attr('required')) == 'undefined' && typeof ($(tag).attr('required')) == 'undefined') {
                        validity.valid = true;
                    } else {
                        var v1 = GetTagValue($(validity.field));
                        var v2 = GetTagValue($(tag));
                        validity.valid = (v1 == null && v2 == null) ? false : true;
                    }
                }
            },

            // 错误
            onInValid: function (validity) {
                var $this = this;
                var $field = $(validity.field);
                var tag_name = $field.prop('tagName');
                if (tag_name == 'SELECT') {
                    setTimeout(function () {
                        // 错误信息
                        var $field = $(validity.field);
                        var value = $field.val();
                        var msg = $field.data('validationMessage') || $this.getValidationMessage(validity);
                        if ((value == '' || value == undefined) && $field.hasClass('am-field-error')) {
                            Prompt(msg);
                        }
                    }, 100);
                } else {
                    var msg = $field.data('validationMessage') || $this.getValidationMessage(validity);
                    Prompt(msg);
                }
            },

            // 提交
            submit: function (e) {
                if (editor_count > 0) {
                    // 同步编辑器数据
                    editor.sync();

                    // 表单验证未成功，而且未成功的第一个元素为 UEEditor 时，focus 编辑器
                    if (!this.isFormValid() && $form.find('.' + this.options.inValidClass).eq(0).is($editor_tag)) {
                        // 编辑器获取焦点
                        editor.focus();

                        // 错误信息
                        var msg = $editor_tag.data('validationMessage') || $editor_tag.getValidationMessage(validity);
                        Prompt(msg);
                    }
                }

                // 通过验证
                if (this.isFormValid()) {
                    // 多选插件校验
                    if ($form.find('select.chosen-select')) {
                        var is_success = true;
                        $form.find('select.chosen-select').each(function (k, v) {
                            var required = $(this).attr('required');
                            if (($(this).attr('required') || null) == 'required') {
                                var multiple = $(this).attr('multiple') || null;
                                var minchecked = parseInt($(this).attr('minchecked')) || 0;
                                var maxchecked = parseInt($(this).attr('maxchecked')) || 0;
                                var msg = $(this).attr('data-validation-message');
                                var value = $(this).val();
                                if ((value || null) == null && value != '0') {
                                    is_success = false;
                                    Prompt(msg || window['lang_select_not_chosen_tips'] || '请选择项');
                                    $(this).trigger('blur');
                                    return false;
                                } else {
                                    if (multiple == 'multiple') {
                                        var count = value.length;
                                        if (minchecked > 0 && count < minchecked) {
                                            is_success = false;
                                            if ((msg || null) == null) {
                                                var temp_msg = window['lang_select_chosen_min_tips'] || '至少选择{value}项';
                                                msg = temp_msg.replace('{value}', minchecked);
                                            }
                                        }
                                        if (maxchecked > 0 && count > maxchecked) {
                                            is_success = false;
                                            if ((msg || null) == null) {
                                                var temp_msg = window['lang_select_chosen_max_tips'] || '最多选择{value}项';
                                                msg = temp_msg.replace('{value}', maxchecked);
                                            }
                                        }
                                        if (is_success === false) {
                                            Prompt(msg);
                                            $(this).trigger('blur');
                                            $(this).parents('.am-form-group').removeClass('am-form-success').addClass('am-form-error');
                                            return false;
                                        }
                                    }
                                }
                            }
                        });
                        if (is_success === false) {
                            return false;
                        }
                    }

                    // button加载
                    var $button = $form.find('button[type="submit"]');
                    $button.button('loading');

                    // 获取表单数据
                    var action = $form.attr('action') || null;
                    var method = $form.attr('method') || null;
                    var request_type = $form.attr('request-type') || null;
                    var request_value = $form.attr('request-value') || null;
                    var is_progress = ($form.attr('data-is-progress') == undefined || parseInt($form.attr('data-is-progress') || 0) == 1) ? 1 : 0;
                    var is_loading = parseInt($form.attr('data-is-loading') || 0);
                    var loading_msg = $form.attr('data-loading-msg') || window['lang_request_handle_loading_tips'] || '正在处理中、请稍候...';
                    // 以 ajax 开头的都会先请求再处理
                    // ajax-reload  请求完成后刷新页面
                    // ajax-close   请求完成后关闭弹窗
                    // ajax-url 	请求完成后调整到指定的请求值
                    // ajax-fun 	请求完成后调用指定方法
                    // ajax-view 	请求完成后仅提示文本信息
                    // sync 		不发起请求、直接同步调用指定的方法
                    // jump 		不发起请求、拼接数据参数跳转到指定 url 地址
                    var request_handle = ['ajax-reload', 'ajax-close', 'ajax-url', 'ajax-fun', 'ajax-view', 'sync', 'jump', 'form'];

                    // 参数校验
                    if (request_handle.indexOf(request_type) == -1) {
                        $button.button('reset');
                        Prompt(window['lang_form_config_type_params_tips'] || '表单[类型]参数配置有误');
                        return false;
                    }

                    // 类型值必须配置校验
                    var request_type_value = ['ajax-url', 'ajax-fun', 'sync', 'jump']
                    if (request_type_value.indexOf(request_type) != -1 && request_value == null) {
                        $button.button('reset');
                        Prompt(window['lang_form_config_value_params_tips'] || '表单[类型值]参数配置有误');
                        return false;
                    }

                    // 请求类型
                    switch (request_type) {
                        // 是form表单直接通过
                        case 'form':
                            return true;
                            break;

                        // 同步调用方法
                        case 'sync':
                            $button.button('reset');
                            if (IsExitsFunction(request_value)) {
                                window[request_value](GetFormVal(form_name, true));
                            } else {
                                Prompt((window['lang_form_call_fun_not_exist_tips'] || '表单配置的方法未定义') + '[' + request_value + ']');
                            }
                            return false;
                            break;

                        // 拼接参数跳转
                        case 'jump':
                            var params = GetFormVal(form_name, true);
                            var pv = '';
                            for (var i in params) {
                                if (params[i] != undefined && params[i] != '') {
                                    pv += i + '=' + encodeURIComponent(params[i]) + '&';
                                }
                            }
                            if (pv != '') {
                                var join = (request_value.indexOf('?') >= 0) ? '&' : '?';
                                request_value += join + pv.substr(0, pv.length - 1);
                            }

                            window.location.href = request_value;
                            return false;
                            break;

                        // 调用自定义回调方法
                        case 'ajax-fun':
                            if (!IsExitsFunction(request_value)) {
                                $button.button('reset');
                                Prompt((window['lang_form_call_fun_not_exist_tips'] || '表单配置的方法未定义') + '[' + request_value + ']');
                                return false;
                            }
                            break;
                    }

                    // 请求 url http类型
                    if (action == null || method == null) {
                        $button.button('reset');
                        Prompt(window['lang_form_config_main_tips'] || '表单[action或method]参数配置有误');
                        return false;
                    }

                    // 请求参数
                    var form_data_count = 0;
                    var form_data = GetFormVal(form_name);
                    var temp_form_data = form_data.appendData || form_data;
                    for (var i in temp_form_data) {
                        form_data_count += 1;
                    }

                    // 请求参数是否超过系统环境参数
                    if (typeof (__env_max_input_vars_count__) != 'undefined') {
                        var env_vars_count = parseInt(__env_max_input_vars_count__);
                        if (env_vars_count > 0 && form_data_count > env_vars_count) {
                            $button.button('reset');
                            Prompt((window['lang_max_input_vars_tips'] || '请求参数数量已超出php.ini限制') + '[max_input_vars](' + form_data_count + '>' + env_vars_count + ')');
                            return false;
                        }
                    }

                    // 弹层加载
                    if (is_loading == 1) {
                        AMUI.dialog.loading({ title: loading_msg });
                    }

                    // ajax请求
                    if(is_progress == 1) {
                        $.AMUI.progress.start();
                    }

                    $.ajax({
                        url: RequestUrlHandle(action),
                        type: method,
                        dataType: "json",
                        timeout: $form.attr('timeout') || 60000,
                        data: form_data,
                        processData: false,
                        contentType: false,
                        success: function (result) {
                            if (is_loading == 1) {
                                AMUI.dialog.loading('close');
                            }
                            if(is_progress == 1) {
                                $.AMUI.progress.done();
                            }
                            // 调用自定义回调方法
                            if (request_type == 'ajax-fun') {
                                if (IsExitsFunction(request_value)) {
                                    window[request_value](result);
                                } else {
                                    $button.button('reset');
                                    Prompt((window['lang_form_call_fun_not_exist_tips'] || '表单配置的方法未定义') + '[' + request_value + ']');
                                }
                            } else {
                                // 统一处理
                                if (result.code == 0) {
                                    switch (request_type) {
                                        // url跳转
                                        case 'ajax-url':
                                            Prompt(result.msg, 'success');
                                            setTimeout(function () {
                                                window.location.href = request_value;
                                            }, 1000);
                                            break;

                                        // 页面刷新
                                        case 'ajax-reload':
                                            Prompt(result.msg, 'success');
                                            setTimeout(function () {
                                                // 等于parent则刷新父窗口
                                                if (request_value == 'parent') {
                                                    parent.location.reload();
                                                } else {
                                                    window.location.reload();
                                                }
                                            }, 1000);
                                            break;

                                        // 页面关闭
                                        case 'ajax-close':
                                            Prompt(result.msg, 'success');
                                            setTimeout(function () {
                                                // 1. 已指定 data-am-modal-close 弹窗关闭属性
                                                // 2. 为父级iframe载入的弹窗（则调用父级定义的关闭方法、当前窗口则不用）
                                                if (request_value == 'parent' || ($form.find('button').is('[data-am-modal-close]') && $form.find('button').parents('.am-popup').length == 0 && $form.find('button').parents('.am-modal').length == 0)) {
                                                    parent.CommonPopupClose();
                                                }
                                            }, 1000);
                                            break;

                                        // 默认仅提示
                                        default:
                                            Prompt(result.msg, 'success');
                                            // 等于parent则关闭父窗口
                                            if (request_value == 'parent') {
                                                setTimeout(function () {
                                                    $button.button('reset');
                                                    parent.CommonPopupClose();
                                                }, 1000);
                                            } else {
                                                $button.button('reset');
                                            }
                                    }
                                } else {
                                    Prompt(result.msg);
                                    $button.button('reset');
                                }
                            }
                        },
                        error: function (xhr, type) {
                            if (is_loading == 1) {
                                AMUI.dialog.loading('close');
                            }
                            if(is_progress == 1) {
                                $.AMUI.progress.done();
                            }
                            $button.button('reset');
                            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                        }
                    });
                }
                return false;
            }
        });
}

/**
 * 表单数据填充
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-14T14:46:47+0800
 * @param    {[json]}    json [json数据对象]
 * @param    {[string]}  tag  [tag标签]
 */
function FormDataFill (json, tag = null) {
    if ((json || null) != null) {
        if ((tag || null) == null) {
            tag = 'form.form-validation';
        }
        $form = (typeof (tag) == 'object') ? tag : $(tag);
        for (var i in json) {
            $form.find('input[type="hidden"][name="' + i + '"], input[type="text"][name="' + i + '"], input[type="password"][name="' + i + '"], input[type="email"][name="' + i + '"], input[type="number"][name="' + i + '"], input[type="date"][name="' + i + '"], textarea[name="' + i + '"], select[name="' + i + '"], input[type="url"][name="' + i + '"]').val(json[i]);

            // input radio
            $form.find('input[type="radio"][name="' + i + '"]').each(function (k, v) {
                this.checked = json[i] == $(this).val();
            });
            // input checkbox
            $form.find('input[type="checkbox"][name="' + i + '"]').each(function (k, v) {
                var temp_value = (typeof (json[i]) != 'object') ? json[i].toString().split(',') : json[i];
                this.checked = temp_value.indexOf($(this).val()) != -1;
            });
        }

        // 是否存在pid和当前id相同
        if ($form.find('select[name="pid"]').length > 0) {
            $form.find('select[name="pid"]').find('option').removeAttr('disabled');
            if ((json['id'] || null) != null) {
                $form.find('select[name="pid"]').find('option[value="' + json['id'] + '"]').attr('disabled', true);
            }
        }

        // 状态切换开关
        SwitchInit();
        // 多选插件事件更新
        SelectChosenInit();
    }
}

/**
 * 树方法头配置数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-01-07
 * @desc    description
 */
function TreeHeadConfig () {
    var head_col = $('#tree').data('head') || null;
    // 如果未定义头信息则默认id和name
    if (head_col == null || head_col.length == 0) {
        head_col = [{ "name": "ID", "field": "id" }, { "name": (window['lang_title_name'] || '名称'), "field": "name", "is_arrow": "1", "value_style": { "style": "color:#999", "value_style_key": "is_enable", "value_style_key_status": "0" } }, { "name": (window['lang_operate_name'] || '操作'), "type": "operate" }];
    }
    return head_col;
}

/**
 * 树方法
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-13T10:30:23+0800
 * @param    {[int]}    	id    			[节点id]
 * @param    {[string]}   	url   			[请求url地址]
 * @param    {[int]}      	level 			[层级]
 * @param    {[int]}      	is_delete_all	[是否所有开启删除按钮]
 */
function Tree (id, url, level = 0, is_delete_all = 0) {
    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: 60000,
        data: { "id": id },
        success: function (result) {
            if (result.code == 0 && result.data.length > 0) {
                html = '';
                for (var i in result.data) {
                    html += TreeItemHtmlHandle(result.data[i], id, level, is_delete_all)
                }

                // 是否首次
                if (id == 0) {
                    $('#tree').attr('data-is-delete-all', is_delete_all);
                    var table_html = '<table class="am-table am-table-striped am-table-hover">';

                    // 头信息配置
                    var head_col = TreeHeadConfig();

                    // 拼接头信息
                    table_html += '<thead>';
                    for (var i = 0; i < head_col.length; i++) {
                        // 类型class
                        var ent = (head_col[i]['type'] || null) == 'operate' ? 'type-operate ' : '';
                        // 字段class
                        ent += (head_col[i]['field'] || null) != null ? 'field-' + head_col[i].field + ' ' : '';
                        table_html += '<th class="' + ent + '">' + head_col[i].name + '</th>';
                    }
                    table_html += '</thead>';
                    table_html += html;
                    table_html += '</table>';
                    $('#tree').html(table_html);
                } else {
                    $('.tree-pid-' + id).remove();
                    $('#data-list-' + id).after(html);
                    $('#data-list-' + id).find('.tree-submit').removeClass('icon-arrow-right');
                    $('#data-list-' + id).find('.tree-submit').addClass('icon-arrow-down');
                }

                // 图片预览初始化
                $.AMUI.figure.init();
                // 图片画廊初始化
                $.AMUI.gallery.init();
                // 折叠组件
                $.AMUI.accordion.init();
                // 切换开关初始化
                SwitchInit();
            } else {
                $('#tree').find('p').text(result.msg);
                $('#tree').find('img').attr('src', __attachment_host__ + '/static/common/images/no-data.png');
            }
        },
        error: function (xhr, type) {
            $('#tree').find('p').text(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
            $('#tree').find('img').attr('src', __attachment_host__ + '/static/common/images/no-data.png');
        }
    });
}

/**
 * tree列表数据处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-19
 * @desc    description
 * @param    {[boject]}     item            [数据]
 * @param    {[int]}    	pid    			[节点pid]
 * @param    {[int]}      	level 			[层级]
 * @param    {[int]}      	is_delete_all	[是否所有开启删除按钮]
 */
function TreeItemHtmlHandle (item, pid, level, is_delete_all) {
    // 基础参数处理
    is_delete_all = is_delete_all || 0;
    var rank = parseInt($('#tree').attr('data-rank')) || 0;
    var delete_url = $('#tree').attr('data-del-url');
    var class_name = $('#data-list-' + pid).attr('class') || '';
    class_name = class_name.replace('am-active', '');
    var popup_tag = $('#tree').attr('data-popup-tag') || '' + popup_tag + '';
    // 是否有自定义配置
    var modal_config = JsonStringToJsonObject($('#tree').attr('data-modal-config') || {});
    modal_config['target'] = popup_tag;
    modal_config = JSON.stringify(modal_config);

    // 数据 start
    var is_active = (item.is_enable !== undefined) ? ((item.is_enable == 0) ? 'am-active' : '') : ((item.is_show !== undefined) ? ((item.is_show == 0) ? 'am-active' : '') : '');

    // 头信息配置
    var head_col = TreeHeadConfig();
    html = '<tr id="data-list-' + item.id + '" data-value="' + item.id + '" data-level="' + level + '" class="' + class_name + ' tree-pid-' + pid + ' ' + is_active + '">';
    for (var i = 0; i < head_col.length; i++) {
        // 类型class
        var ent = (head_col[i]['type'] || null) == 'operate' ? 'type-operate ' : '';
        // 字段class
        ent += (head_col[i]['field'] || null) != null ? 'field-' + head_col[i].field + ' ' : '';
        html += '<td data-key="' + (head_col[i].field || '') + '" class="' + ent + '">';
        var style = '';
        if (head_col[i].value_style) {
            if (head_col[i].value_style.value_style_key && head_col[i].value_style.value_style_key_status) {
                if (item[head_col[i].value_style.value_style_key] == head_col[i].value_style.value_style_key_status) {
                    style = head_col[i].value_style.style;
                }
            } else {
                style = head_col[i].value_style.style;
            }
        }
        switch (head_col[i].type) {
            // 图标
            case 'img':
                if ((item[head_col[i].field] || null) != null) {
                    html += '<a href="javascript:;" class="am-figure am-margin-right-xs am-inline-block three-item-icon" data-am-widget="figure"  data-am-figure="{pureview: \'true\'}"><img src="' + item[head_col[i].field] + '" width="30" height="30" class="am-vertical-align-middle am-radius" /></a>';
                }
                break;
            // icon
            case 'icon':
                if ((item[head_col[i].field] || null) != null) {
                    html += '<i class="am-text-sm iconfont ' + item[head_col[i].field] + '" style="' + style + '"></i>';
                }
                break;
            // 状态
            case 'status':
                html += '<input data-am-switch name="is_enable_' + item.id + '" value="' + item[head_col[i].field] + '" data-loading type="checkbox" ';
                if (item[head_col[i].field] == '1') {
                    html += ' checked />';
                } else {
                    html += ' />';
                }
                break;

            // 操作
            case 'operate':
                // 操作项 start
                html += '<div class="submit am-flex am-flex-items-center am-gap-12">';
                // 新增
                if (level < rank - 1) {
                    html += '<a href="javascript:;" class="tree-submit-add-node" data-am-modal=\'' + modal_config + '\' data-id="' + item.id + '"> ' + (window['lang_operate_add_name'] || '新增') + '</a>';
                }
                // 编辑
                html += '<a href="javascript:;" class="submit-edit" data-am-modal=\'' + modal_config + '\' data-json="' + encodeURIComponent(item.json) + '" data-is-exist-son="' + (item.is_son || 'no') + '"> ' + (window['lang_operate_edit_name'] || '编辑') + '</a>';
                if (item.is_son != 'ok' || is_delete_all == 1) {
                    // 是否需要删除子数据
                    var pid_class = is_delete_all == 1 ? '.tree-pid-' + item.id : '';
                    // 删除
                    html += '<a href="javascript:;" class="submit-delete" data-id="' + item.id + '" data-url="' + delete_url + '" data-ext-delete-tag="' + pid_class + '"> ' + (window['lang_operate_delete_name'] || '删除') + '</a>';
                }
                html += '</div>';
                // 操作项 end
                break;
            default:
                if (head_col[i].is_arrow == '1') {
                    var left = (pid != 0) ? parseInt(level) * 20 : parseInt(level);
                    html += '<span class="name" style="padding-left:' + left + 'px;">';
                    if (item.is_son == 'ok') {
                        html += '<a href="javascript:;" class="iconfont icon-arrow-right ';
                        html += 'tree-submit" data-id="' + item.id + '" data-is-delete-all="' + is_delete_all + '" style="margin-right:8px;width:12px;"></a>';
                    }
                    html += '<span style="' + style + '">' + item[head_col[i].field] + '</span>';
                    html += '</span>';
                } else {
                    html += '<span><span style="' + style + '">' + item[head_col[i].field] + '</span></span>';
                }
        }
        html += '</td>';
    }
    html += '</tr>';
    return html;
}

/**
 * tree数据保存回调处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-19
 * @desc    description
 * @param   {[boject]}        e [当前回调数据]
 */
function TreeFormSaveBackHandle (e) {
    $.AMUI.progress.done();
    var $popup = $($('#tree').data('popup-tag'));
    var $button = $popup.find('button[type="submit"]');
    if (e.code == 0) {
        Prompt(e.msg, 'success');
        // 数据处理
        if ((e.data || null) != null) {
            if (typeof (e.data) == 'object') {
                var json = e.data;
                var string = JSON.stringify(e.data);
            } else {
                var json = JSON.parse(decodeURIComponent(e.data));
                var string = e.data;
            }
            if ((json.id || null) != null) {
                // 存在数据编辑、则添加
                var $obj = $('#data-list-' + json.id);
                if ($obj.length > 0) {
                    // 原始json数据
                    var json_old = JSON.parse(decodeURIComponent($obj.find('.submit-edit').attr('data-json')));

                    // 头信息配置
                    var head_col = TreeHeadConfig();
                    for (var i = 0; i < head_col.length; i++) {
                        var style = '';
                        if (head_col[i].value_style) {
                            if (head_col[i].value_style.value_style_key && head_col[i].value_style.value_style_key_status) {
                                if (json[head_col[i].value_style.value_style_key] == head_col[i].value_style.value_style_key_status) {
                                    style = head_col[i].value_style.style;
                                }
                            } else {
                                style = head_col[i].value_style.style;
                            }
                        }
                        switch (head_col[i].type) {
                            // 图标
                            case 'img':
                                if ((json[head_col[i].field] || null) != null) {
                                    if ($obj.find('.three-item-icon').length == 0) {
                                        $obj.find('td[data-key="' + head_col[i].field + '"]').prepend('<a href="javascript:;" class="am-figure am-margin-right-xs am-inline-block three-item-icon" data-am-widget="figure" data-am-figure="{pureview: \'true\'}"><img src="' + json[head_col[i].field] + '" width="30" height="30" class="am-vertical-align-middle am-radius" /></a>');
                                    } else {
                                        $obj.find('td[data-key="' + head_col[i].field + '"] .three-item-icon img').attr('src', json[head_col[i].field]);
                                    }
                                } else {
                                    $obj.find('td[data-key="' + head_col[i].field + '"] .three-item-icon').remove();
                                }
                                break;
                            case 'icon':
                                // icon
                                if ((json[head_col[i].field] || null) != null) {
                                    $obj.find('td[data-key="' + head_col[i].field + '"]').empty();
                                    $obj.find('td[data-key="' + head_col[i].field + '"]').append('<i class="am-text-sm iconfont ' + json[head_col[i].field] + '" style="' + style + '"></i>');
                                } else {
                                    $obj.find('td[data-key="' + head_col[i].field + '"]').empty();
                                }
                                break;
                            // 状态
                            case 'status':
                                if ((json[head_col[i].field] || null) != null) {
                                    $obj.find('td[data-key="' + head_col[i].field + '"] input').prop('checked', json[head_col[i].field] == 1 ? true : false);
                                } else {
                                    $obj.find('td[data-key="' + head_col[i].field + '"] input').prop('checked', false);
                                }
                                break;
                            default:
                                $obj.find('td[data-key="' + head_col[i].field + '"] span>span').remove();
                                $obj.find('td[data-key="' + head_col[i].field + '"] span').append('<span style="' + style + '">' + json[head_col[i].field] + '</span>');

                        }
                    }

                    // 状态处理
                    if ((json.is_enable !== undefined && json.is_enable != json_old.is_enable) || (json.is_show !== undefined && json.is_show != json_old.is_show)) {
                        if ($obj.hasClass('am-active')) {
                            $obj.removeClass('am-active');
                        } else {
                            $obj.addClass('am-active');
                        }
                    }

                    // 属性json数据更新
                    $obj.find('.submit-edit').attr('data-json', encodeURIComponent(string));
                } else {
                    // 存在pid直接拉取下级数据,则追加新数据
                    var is_delete_all = parseInt($('#tree').attr('data-is-delete-all') || 0);
                    if (json.pid > 0) {
                        // 没有展开图标则增加
                        if ($('#data-list-' + json.pid + ' .tree-submit').length == 0) {
                            $('#data-list-' + json.pid + ' td span.name').prepend('<a href="javascript:;" class="iconfont icon-arrow-down tree-submit" data-id="' + json.pid + '" data-is_delete_all="' + is_delete_all + '" style="margin-right:8px;width:12px;"></a>');
                        }

                        // 数据子级读取
                        var level = $('#data-list-' + json.pid).length > 0 ? parseInt($('#data-list-' + json.pid).attr('data-level') || 0) + 1 : 0;
                        Tree(json.pid, $('#tree').data('node-url'), level, is_delete_all);
                    } else {
                        json['json'] = string;

                        // 拼接html数据
                        var html = TreeItemHtmlHandle(json, 0, 0, is_delete_all);

                        // 首次则增加table标签容器
                        if ($('#tree table tbody').length > 0) {
                            $('#tree table tbody').append(html);
                        } else {
                            var thead_html = '';
                            // 头信息配置
                            var head_col = TreeHeadConfig();
                            thead_html = '<thead>';
                            for (var i = 0; i < head_col.length; i++) {
                                thead_html += '<th>' + head_col[i].name + '</th>';
                            }
                            thead_html += '</thead>';
                            $('#tree').html(html);
                            $('#tree').html('<table class="am-table am-table-striped am-table-hover">' + thead_html + '<tbody>' + html + '</tbody></table>');
                        }
                    }
                }

                // 图片预览初始化
                $.AMUI.figure.init();
                // 图片画廊初始化
                $.AMUI.gallery.init();
                // 折叠组件
                $.AMUI.accordion.init();
                // 切换开关初始化
                SwitchInit();
            }
        }
        setTimeout(function () {
            $button.button('reset');
            $popup.modal('close');
        }, 1000);
    } else {
        $button.button('reset');
        Prompt(e.msg);
    }
}

/**
 * 图片上传预览
 * @param  {[string]} class_name 		[class名称]
 * @param  {[string]} show_img   		[预览图片id或class]
 * @param  {[string]} default_images    [默认图片]
 */
function ImageFileUploadShow (class_name, show_img, default_images) {
    $(document).on("change", class_name, function (imgFile) {
        show_img = $(this).attr('data-image-tag') || null;
        var status = false;
        if ((imgFile.target.value || null) != null) {
            var filextension = imgFile.target.value.substring(imgFile.target.value.lastIndexOf("."), imgFile.target.value.length);
            filextension = filextension.toLowerCase();
            if ((filextension != '.jpg') && (filextension != '.gif') && (filextension != '.jpeg') && (filextension != '.png') && (filextension != '.bmp')) {
                Prompt(window['lang_upload_images_format_tips'] || '图片格式错误，请重新上传');
            } else {
                if (document.all) {
                    Prompt(window['lang_ie_browser_tips'] || 'ie浏览器不可用');
                    /*imgFile.select();
                    path = document.selection.createRange().text;
                    $(this).parent().parent().find('img').attr('src', '');
                    $(this).parent().parent().find('img').attr('src', path);  //使用滤镜效果  */
                } else {
                    var url = window.URL.createObjectURL(imgFile.target.files[0]);// FF 7.0以上
                    $(show_img).attr('src', url);
                    status = true;
                }
            }
        }
        var default_img = $(show_img).attr('data-default') || null;
        if (status == false && ((default_images || null) != null || default_img != null)) {
            $(show_img).attr('src', default_images || default_img);
        }
    });
}

/**
 * 视频上传预览
 * @param  {[string]} class_name 		[class名称]
 * @param  {[string]} show_video   		[预览视频id或class]
 * @param  {[string]} default_video     [默认视频]
 */
function VideoFileUploadShow (class_name, show_video, default_video) {
    $(document).on("change", class_name, function (imgFile) {
        show_video = $(this).attr('data-video-tag') || null;
        var status = false;
        if ((imgFile.target.value || null) != null) {
            var filextension = imgFile.target.value.substring(imgFile.target.value.lastIndexOf("."), imgFile.target.value.length);
            filextension = filextension.toLowerCase();
            if (filextension != '.mp4') {
                Prompt(window['lang_upload_video_format_tips'] || '视频格式错误，请重新上传');
            } else {
                if (document.all) {
                    Prompt(window['lang_ie_browser_tips'] || 'ie浏览器不可用');
                    /*imgFile.select();
                    path = document.selection.createRange().text;
                    $(this).parent().parent().find('img').attr('src', '');
                    $(this).parent().parent().find('img').attr('src', path);  //使用滤镜效果  */
                } else {
                    var url = window.URL.createObjectURL(imgFile.target.files[0]);// FF 7.0以上
                    $(show_video).attr('src', url);
                    status = true;
                }
            }
        }
        var default_video = $(show_video).attr('data-default') || null;
        if (status == false && ((default_video || null) != null || default_video != null)) {
            $(show_video).attr('src', default_video || default_video);
        }
    });
}

/**
 * 弹窗加载
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-13
 * @desc    description
 * @param   {[string]}  url   		  [加载url]
 * @param   {[string]}  title 		  [标题]
 * @param   {[string]}  class_tag 	  [指定class]
 * @param   {[int]}     full 		  [是否满屏（0否, 1是）]
 * @param   {[int]}     full_max	  [满屏最大限制（max-width:1400px）（0否, 1是）]
 * @param   {[string]}  full_max_size [满屏最大限制指定（默认空 最大1200、lg 1000, 有效值 md 800, sm 500, xs 400）]
 * @param   {[string]}  on_open	      [开启监听方法]
 * @param   {[string]}  on_close	  [关闭监听方法]
 * @param   {[string]}  offcanvas     [侧边栏弹窗 top/bottom/left/right]
 */
function ModalLoad (url, title, class_tag, full = 0, full_max = 0, full_max_size = '', on_open = '', on_close = '', offcanvas = '') {
    // class 定义
    var ent = 'popup-iframe';

    // 自定义 class
    if ((class_tag || null) != null) {
        ent += ' ' + class_tag;
    }

    // 是否满屏
    if ((full || 0) == 1) {
        ent += ' am-popup-full';
    }

    // 是否开启侧边栏
    if ((offcanvas || '') !== '') {
        ent += ' am-popup-offcanvas-' + offcanvas;
    }

    // 满屏最大限制
    if ((full_max || 0) == 1) {
        ent += ' am-popup-full-max';
        // 满屏最大限制指定大小类型
        if ((full_max_size || null) != null) {
            ent += '-' + full_max_size;
        }
    }

    // 调用弹窗组件
    var iframe_id = 'common-popup-iframe-' + (Math.random() * 9999999999).toString().replace('.', '');
    AMUI.dialog.popup({
        title: title || '',
        content: '<iframe src="' + RequestUrlHandle(url) + '" width="100%" height="100%" class="am-block" id="' + iframe_id + '"></iframe>',
        class: ent,
        onOpen: function () {
            // 子页面样式处理
            IframeSubpageStyleHandle(iframe_id, 'iframe-popup-page');

            // 回调打开方法
            if ((on_open || null) != null) {
                if(typeof on_open == 'function') {
                    on_open();
                } else if(IsExitsFunction(on_open))
                {
                    window[on_open]();
                }
            }
        },
        onClose: function () {
            // 回调关闭方法
            if ((on_close || null) != null) {
                if(typeof on_close == 'function') {
                    on_close();
                } else if(IsExitsFunction(on_close))
                {
                    window[on_close]();
                }
            }
        }
    });
}

/**
 * 提示弹窗加载
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2025-01-23
 * @desc    description
 * @param   {Object}        params   [参数]
 * @param   {Object}        config   [配置]
 * @param   {String}        on_open  [监听打开]
 * @param   {String}        on_close [监听关闭]
 */
function AlertLoad(params, config = {}, on_open = '', on_close = '')
{
    if((params || null) == null) {
        params = {};
    }

    // 调用类库方法
    var iframe_id = '';

    // url地址
    var iframe = '';
    if ((params.url || null) != null) {
        iframe_id = 'common-alert-iframe-' + (Math.random() * 9999999999).toString().replace('.', '');
        iframe = '<iframe src="' + RequestUrlHandle(params.url) + '" class="am-block" id="' + iframe_id + '" style="width:100%;height:calc(100% - 4.5rem);"></iframe>';
    }

    // 打开弹窗
    AMUI.dialog.alert({
        title: params.title || '',
        class: params.class || '',
        style: params.style || '',
        content_style: params.content_style || '',
        isClose: true,
        config: config || {},
        iframe: iframe,
        content: params.content || '',
        onOpen: function () {
            // 子页面样式处理
            if(iframe_id !== '' && iframe !== '') {
                IframeSubpageStyleHandle(iframe_id, 'iframe-alert-page');
            }

            // 回调打开方法
            if ((on_open || null) != null) {
                if(typeof on_open == 'function') {
                    on_open();
                } else if(IsExitsFunction(on_open))
                {
                    window[on_open]();
                }
            }
        },
        onClose: function () {
            // 回调关闭方法
            if ((on_close || null) != null) {
                if(typeof on_close == 'function') {
                    on_close();
                } else if(IsExitsFunction(on_close))
                {
                    window[on_close]();
                }
            }
        }
    });
}

/**
 * iframe子页面样式处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-01-11
 * @desc    description
 * @param   {[string]}        iframe_id   [iframeID]
 * @param   {[string]}        style_class [样式class]
 */
function IframeSubpageStyleHandle (iframe_id, style_class) {
    // iframe对象
    var iframe = document.getElementById(iframe_id);

    // 初始先执行
    var iframe_doc = iframe.contentDocument || iframe.contentWindow.document || null;
    if (iframe_doc != null && iframe_doc.body != null) {
        iframe_doc.body.classList.add(style_class);
    }

    // 加载完成后执行
    iframe.onload = function () {
        var iframe_doc = iframe.contentDocument || iframe.contentWindow.document || null;
        if (iframe_doc != null) {
            iframe_doc.body.classList.add(style_class);
        }
    }
}

/**
 * 价格四舍五入，并且指定保留小数位数
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-14
 * @desc    description
 * @param   {[float]}      value [金额]
 * @param   {[int]}        pos   [位数 默认2]
 */
function FomatFloat (value, pos = 2) {
    var f_x = Math.round(value * Math.pow(10, pos)) / Math.pow(10, pos);
    var s_x = f_x.toString();
    var pos_decimal = s_x.indexOf('.');
    if (pos_decimal < 0) {
        pos_decimal = s_x.length;
        s_x += '.';
    }
    while (s_x.length <= pos_decimal + 2) {
        s_x += '0';
    }
    return s_x;
}

/**
 * 数据删除
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-24T08:24:58+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function DataDelete (e) {
    // 参数获取
    var id = e.attr('data-id');
    var key = e.attr('data-key') || 'id';
    var url = e.attr('data-url');
    var value = e.attr('data-value') || null;
    var view = e.attr('data-view') || 'delete';
    var view_value = e.attr('data-view-value') || '';
    var ext_delete_tag = e.attr('data-ext-delete-tag') || null;
    var is_progress = (e.attr('data-is-progress') == undefined || parseInt(e.attr('data-is-progress') || 0) == 1) ? 1 : 0;
    var is_loading = parseInt(e.attr('data-is-loading') || 0);
    var loading_msg = e.attr('data-loading-msg') || window['lang_request_handle_loading_tips'] || '正在处理中、请稍候...';

    // 参数校验
    if ((id || null) == null || (url || null) == null) {
        Prompt(window['lang_params_error_tips'] || '参数配置有误');
        return false;
    }

    // 弹层加载
    if (is_loading == 1) {
        AMUI.dialog.loading({ title: loading_msg });
    }

    // 请求删除数据
    if(is_progress == 1) {
        $.AMUI.progress.start();
    }

    // 请求数据
    var data = {};
    data[key] = id;

    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: e.attr('data-timeout') || 60000,
        data: data,
        success: function (result) {
            if(is_progress == 1) {
                $.AMUI.progress.done();
            }
            if (result.code == 0) {
                Prompt(result.msg, 'success');

                switch (view) {
                    // 成功则删除数据列表
                    case 'delete':
                        Prompt(result.msg, 'success');
                        $('#data-list-' + id).remove();
                        if (ext_delete_tag != null) {
                            $(ext_delete_tag).remove();
                        }
                        break;

                    // 刷新
                    case 'reload':
                        Prompt(result.msg, 'success');
                        setTimeout(function () {
                            if (is_loading == 1) {
                                AMUI.dialog.loading('close');
                            }
                            // 等于parent则刷新父窗口
                            if (view_value == 'parent') {
                                parent.location.reload();
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                        break;

                    // 回调函数
                    case 'fun':
                        if (IsExitsFunction(value)) {
                            result['data_id'] = id;
                            window[value](result, e);
                        } else {
                            Prompt((window['lang_config_fun_not_exist_tips'] || '配置方法未定义') + '[' + value + ']');
                        }
                        break;

                    // 跳转
                    case 'jump':
                        Prompt(result.msg, 'success');
                        if (value != null) {
                            setTimeout(function () {
                                window.location.href = value;
                            }, 1000);
                        }
                        break;

                    // 默认提示成功
                    default:
                        Prompt(result.msg, 'success');
                }
                // 成功则删除数据列表
                $('#data-list-' + id).remove();

                // 非刷新和跳转操作
                if (view != 'reload' && view != 'jump') {
                    if (is_loading == 1) {
                        AMUI.dialog.loading('close');
                    }
                }
            } else {
                if (is_loading == 1) {
                    AMUI.dialog.loading('close');
                }
                Prompt(result.msg);
            }
        },
        error: function (xhr, type) {
            if (is_loading == 1) {
                AMUI.dialog.loading('close');
            }
            if(is_progress == 1) {
                $.AMUI.progress.done();
            }
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

/**
 * 数据删除
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-24T08:24:58+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function ConfirmDataDelete (e) {
    var title = e.attr('data-title') || window['lang_reminder_title'] || '温馨提示';
    var msg = e.attr('data-msg') || window['lang_delete_confirm_tips'] || '删除后不可恢复、确认操作吗？';
    var is_confirm = (e.attr('data-is-confirm') == undefined || e.attr('data-is-confirm') == 1) ? 1 : 0;

    if (is_confirm == 1) {
        AMUI.dialog.confirm({
            title: title,
            content: msg,
            onConfirm: function (options) {
                DataDelete(e);
            },
            onCancel: function () { }
        });
    } else {
        DataDelete(e);
    }
}

/**
 * ajax网络请求
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-04-30T00:25:21+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function AjaxRequest (e) {
    // 参数
    var id = e.attr('data-id');
    var key = e.attr('data-key') || 'id';
    var field = e.attr('data-field') || '';
    var value = e.attr('data-value') || '';
    var url = e.attr('data-url');
    var view = e.attr('data-view') || '';
    var view_value = e.attr('data-view-value') || '';
    var is_error_view = parseInt(e.attr('data-is-error-view') || 0) == 1;
    var is_example = e.hasClass('btn-loading-example');
    var is_reset = e.attr('data-is-reset');
    var is_progress = (e.attr('data-is-progress') == undefined || parseInt(e.attr('data-is-progress') || 0) == 1) ? 1 : 0;
    var is_loading = parseInt(e.attr('data-is-loading') || 0);
    var loading_msg = e.attr('data-loading-msg') || window['lang_request_handle_loading_tips'] || '正在处理中、请稍候...';

    // 请求数据
    var data = { "value": value, "field": field };
    data[key] = id;

    // 弹层加载
    if (is_loading == 1) {
        AMUI.dialog.loading({ title: loading_msg });
    }

    // 按钮加载
    if (is_example) {
        e.button('loading');
    }

    // ajax
    if(is_progress == 1) {
        $.AMUI.progress.start();
    }
    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: e.attr('data-timeout') || 60000,
        data: data,
        success: function (result) {
            if (is_example) {
                // 是否指定需要释放按钮禁用状态
                if (is_reset == 1) {
                    e.button('reset');
                } else {
                    // 未指定则刷新和跳转不释放按钮禁用状态
                    if (is_reset == undefined) {
                        var not_reset = ['reload', 'jump'];
                        if (not_reset.indexOf(view) == -1) {
                            e.button('reset');
                        }
                    }
                }
            }

            // 关闭进度条
            if(is_progress == 1) {
                $.AMUI.progress.done();
            }

            // 根据类型处理回调
            if (result.code == 0) {
                switch (view) {
                    // 成功则删除数据列表
                    case 'delete':
                        Prompt(result.msg, 'success');
                        $('#data-list-' + id).remove();
                        break;

                    // 刷新
                    case 'reload':
                        Prompt(result.msg, 'success');
                        setTimeout(function () {
                            if (is_loading == 1) {
                                AMUI.dialog.loading('close');
                            }
                            // 等于parent则刷新父窗口
                            if (view_value == 'parent') {
                                parent.location.reload();
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                        break;

                    // 回调函数
                    case 'fun':
                        if (IsExitsFunction(value)) {
                            window[value](result, e);
                        } else {
                            Prompt((window['lang_config_fun_not_exist_tips'] || '配置方法未定义') + '[' + value + ']');
                        }
                        break;

                    // 跳转
                    case 'jump':
                        Prompt(result.msg, 'success');
                        if (value != null) {
                            setTimeout(function () {
                                window.location.href = value;
                            }, 1000);
                        }
                        break;

                    // 默认提示成功
                    default:
                        Prompt(result.msg, 'success');
                }

                // 非刷新和跳转操作
                if (view != 'reload' && view != 'jump') {
                    if (is_loading == 1) {
                        AMUI.dialog.loading('close');
                    }
                }
            } else {
                if (is_loading == 1) {
                    AMUI.dialog.loading('close');
                }
                if (is_example) {
                    e.button('reset');
                }
                if(is_error_view) {
                    switch (view) {
                        // 刷新
                        case 'reload':
                            Prompt(result.msg);
                            setTimeout(function () {
                                if (is_loading == 1) {
                                    AMUI.dialog.loading('close');
                                }
                                // 等于parent则刷新父窗口
                                if (view_value == 'parent') {
                                    parent.location.reload();
                                } else {
                                    window.location.reload();
                                }
                            }, 1000);
                            break;

                        // 回调函数
                        case 'fun':
                            if (IsExitsFunction(value)) {
                                window[value](result, e);
                            } else {
                                Prompt((window['lang_config_fun_not_exist_tips'] || '配置方法未定义') + '[' + value + ']');
                            }
                            break;

                        // 跳转
                        case 'jump':
                            Prompt(result.msg);
                            if (value != null) {
                                setTimeout(function () {
                                    window.location.href = value;
                                }, 1000);
                            }
                            break;

                        // 默认提示失败
                        default:
                            Prompt(result.msg);
                    }
                } else {
                    Prompt(result.msg);
                }
            }
        },
        error: function (xhr, type) {
            if (is_loading == 1) {
                AMUI.dialog.loading('close');
            }
            if (is_example) {
                e.button('reset');
            }
            if(is_progress == 1) {
                $.AMUI.progress.done();
            }
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

/**
 * 确认网络请求
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-24T08:24:58+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function ConfirmNetworkAjax (e) {
    var title = e.attr('data-title') || window['lang_reminder_title'] || '温馨提示';
    var msg = e.attr('data-msg') || window['lang_operate_confirm_tips'] || '操作后不可恢复、确认继续吗？';
    AMUI.dialog.confirm({
        title: title,
        content: msg,
        onConfirm: function (result) {
            AjaxRequest(e);
        },
        onCancel: function () { }
    });
}

/**
 * 开启全屏
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-01
 * @desc    description
 */
function FullscreenOpen () {
    var elem = document.documentElement;
    if (elem.webkitRequestFullScreen) {
        elem.webkitRequestFullScreen();
    } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
    } else if (elem.requestFullScreen) {
        elem.requestFullScreen();
    } else {
        Prompt(window['lang_browser_api_error_tips'] || '浏览器不支持全屏API或已被禁用');
        return false;
    }
    return true;
}

/**
 * 关闭全屏
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-01
 * @desc    description
 */
function FullscreenExit () {
    var elem = document;
    if (elem.webkitCancelFullScreen) {
        elem.webkitCancelFullScreen();
    } else if (elem.mozCancelFullScreen) {
        elem.mozCancelFullScreen();
    } else if (elem.cancelFullScreen) {
        elem.cancelFullScreen();
    } else if (elem.exitFullscreen) {
        elem.exitFullscreen();
    } else {
        Prompt(window['lang_browser_api_error_tips'] || '浏览器不支持全屏API或已被禁用');
        return false;
    }
    return true;
}

/**
 * 全屏ESC监听
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-01
 * @desc    description
 */
var fullscreen_counter = 0;
function FullscreenEscEvent () {
    fullscreen_counter++;
    if (fullscreen_counter % 2 == 0) {
        var $fullscreen = $('.fullscreen-event');
        if (($fullscreen.attr('data-status') || 0) == 1) {
            $fullscreen.find('.fullscreen-text').text($fullscreen.attr('data-fulltext-open') || window['lang_fullscreen_open_name'] || '开启全屏');
            $fullscreen.attr('data-status', 0);
        }
    }
}

/**
 * url参数替换，参数不存在则添加
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-20
 * @desc    description
 * @param   {[string]}        field [字段名称]
 * @param   {[string]}        value [字段值, null 则去除字段]
 * @param   {[string]}        url   [自定义url]
 * @param   {[string]}        anchor[锚点、传入空字符串则表示去除已存在的锚点]
 */
function UrlFieldReplace (field, value, url = null, anchor = null) {
    // 当前页面url地址
    url = url || window.location.href;

    // 锚点
    if (url.indexOf('#') != -1) {
        var temp_url = url.split('#');
        url = temp_url[0];
        // 未指定锚点则使用url自带的
        if (temp_url.length > 1 && anchor === null) {
            anchor = temp_url[1];
        }
    }
    // 存在锚点则增加#号、则赋空字符
    if ((anchor || null) != null && anchor.indexOf('#') == -1) {
        anchor = '#' + anchor;
    } else {
        anchor = '';
    }

    // 是否存在问号参数
    if (url.indexOf('?') >= 0) {
        var str = url.substr(0, url.lastIndexOf('.' + __seo_url_suffix__)) || url;
        var loc = url.lastIndexOf('.' + __seo_url_suffix__);
        var ext = (loc == -1) ? '' : url.substr(loc);
        if (str.indexOf(field + '/') >= 0) {
            var first = str.substr(0, str.lastIndexOf(field));
            var last = str.substr(str.lastIndexOf(field));
            last = last.replace(new RegExp(field + '/', 'g'), '');
            last = (last.indexOf('/') >= 0) ? last.substr(last.indexOf('/')) : '';
            if (value === null) {
                if (first.substr(-1) == '/') {
                    first = first.substr(0, first.length - 1);
                }
                url = first + last + ext;
            } else {
                url = first + field + '/' + value + last + ext;
            }
        } else {
            if (ext.indexOf('?') >= 0) {
                var p = '';
                exts = ext.substr(ext.indexOf('?') + 1);
                if (ext.indexOf(field) >= 0) {
                    var params_all = exts.split('&');
                    for (var i in params_all) {
                        var temp = params_all[i].split('=');
                        if (temp.length >= 2) {
                            if (temp[0] == field) {
                                if (value !== null) {
                                    p += '&' + field + '=' + value;
                                }
                            } else {
                                p += '&' + params_all[i];
                            }
                        }
                    }
                } else {
                    if (value === null) {
                        p = exts;
                    } else {
                        p = exts + '&' + field + '=' + value;
                    }
                }
                url = str + (ext.substr(0, ext.indexOf('?')))
                if ((p || null) != null) {
                    if (p.substr(0, 1) == '&') {
                        p = p.substr(1);
                    }
                    url += '?' + p;
                }
            } else {
                if (str.indexOf('?') >= 0) {
                    var p = '';
                    exts = str.substr(str.indexOf('?') + 1);
                    if (str.indexOf(field) >= 0) {
                        var params_all = exts.split('&');
                        for (var i in params_all) {
                            var temp = params_all[i].split('=');
                            if (temp.length >= 2) {
                                if (temp[0] == field) {
                                    if (value !== null) {
                                        p += '&' + field + '=' + value;
                                    }
                                } else {
                                    p += '&' + params_all[i];
                                }
                            }
                        }
                    } else {
                        if (value === null) {
                            p = exts;
                        } else {
                            p = exts + '&' + field + '=' + value;
                        }
                    }
                    url = (str.substr(0, str.indexOf('?')))
                    if ((p || null) != null) {
                        if (p.substr(0, 1) == '&') {
                            p = p.substr(1);
                        }
                        url += '?' + p;
                    }
                } else {
                    if (value === null) {
                        url = str + ext;
                    } else {
                        url = str + '/' + field + '/' + value + ext;
                    }
                }
            }
        }
    } else {
        if (url.indexOf(field + '/') != -1) {
            var str = url.substr(0, url.lastIndexOf('.' + __seo_url_suffix__)) || url;
            var loc = url.lastIndexOf('.' + __seo_url_suffix__);
            var ext = (loc == -1) ? '' : url.substr(loc);
            var first = str.substr(0, str.lastIndexOf(field));
            var last = str.substr(str.lastIndexOf(field));
            last = last.replace(new RegExp(field + '/', 'g'), '');
            last = (last.indexOf('/') >= 0) ? last.substr(last.indexOf('/')) : '';
            if (value === null) {
                if (first.substr(-1) == '/') {
                    first = first.substr(0, first.length - 1);
                }
                url = first + last + ext;
            } else {
                url = first + field + '/' + value + last + ext;
            }
        } else {
            if (value !== null) {
                url += '?' + field + '=' + value;
            }
        }
    }

    return url + anchor;
}

/**
 * 当前手机浏览器环境
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-04-20T19:48:59+0800
 * @return   {string} [weixin,weibo,qq]
 */
function MobileBrowserEnvironment () {
    // 浏览器标识
    var ua = navigator.userAgent.toLowerCase();

    // 微信
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return 'weixin';
    }

    // 支付宝
    if (ua.match(/AlipayClient/i) == 'alipayclient') {
        return 'alipay';
    }

    // 百度
    if (ua.match(/swan-baiduboxapp/i) == 'swan-baiduboxapp') {
        return 'baidu';
    }

    // 头条
    if (ua.match(/ToutiaoMicroApp/i) == 'toutiaomicroapp') {
        return 'toutiao';
    }

    // 快手
    if (ua.match(/AllowKsCallApp/i) == 'allowkscallapp') {
        return 'kuaishou';
    }

    // 新浪微博
    if (ua.match(/WeiBo/i) == 'weibo') {
        return 'weibo';
    }

    // QQ空间
    if (ua.match(/qzone/i) == 'qzone') {
        return 'qzone';
    }

    // QQ
    if (ua.match(/QQ/i) == 'qq') {
        return 'qq';
    }
    return null;
}

/**
 * 分页按钮获取
 * @param  {[int]}      total       [数据总条数]
 * @param  {[int]}      number      [页面数据显示条数]
 * @param  {[int]}      page        [当前页码数]
 * @param  {[int]}      sub_number  [按钮生成个数]
 * @param  {[boolean]}  is_extend   [是否展示扩展信息]
 * @return {[string]}               [html代码]
 */
function PageLibrary (total, number, page, sub_number, is_extend = false) {
    if ((page || null) == null) page = 1;
    if ((number || null) == null) number = 15;
    if ((sub_number || null) == null) sub_number = 2;

    var page_total = Math.ceil((total || 0) / number);
    if (page > page_total) page = page_total;
    page = (page <= 0) ? 1 : parseInt(page);

    var html = '<ul class="am-pagination-container am-pagination am-pagination-right pagelibrary"><li ';
    html += (page > 1) ? '' : 'class="am-disabled"';
    page_x = page - 1;
    html += '><a href="javascript:;" data-page="' + page_x + '" class="am-radius">&laquo;</a></li>';

    var html_before = '';
    var html_after = '';
    var html_page = '<li class="am-active"><a href="javascript:;" data-is-active="1" class="am-radius">' + page + '</a></li>';
    if (sub_number > 0) {
        // 前按钮
        if (page > 1) {
            var temp = (page - sub_number < 1) ? 1 : page - sub_number;
            for (var i = page - 1; i >= temp; i--) {
                html_before = '<li><a href="javascript:;" data-page="' + i + '" class="am-radius">' + i + '</a></li>' + html_before;
            }
        }

        // 后按钮
        if (page_total > page) {
            var temp = (page + sub_number > page_total) ? page_total : page + sub_number;
            for (var i = page + 1; i <= temp; i++) {
                html_after += '<li><a href="javascript:;" data-page="' + i + '" class="am-radius">' + i + '</a></li>';
            }
        }
    }

    html += html_before + html_page + html_after;
    html += '<li';
    html += (page > 0 && page < page_total) ? '' : ' class="am-disabled"';
    page_y = page + 1;
    html += '><a href="javascript:;" data-page="' + page_y + '" class="am-radius">&raquo;</a></li>';

    // 页码信息
    if (is_extend) {
        html += `<span class="current-page-input">
                    <span class="am-margin-left-sm">`+ (window['lang_page_each_page_name'] || '每页') + `</span>
                    <input type="text" name="page_size" min="1" data-is-clearout="0" class="am-form-field am-inline-block am-text-center am-margin-horizontal-xs am-radius pagination-input" value="`+ number + `" data-type="size" data-default-value="30" onclick="this.select()">
                    <span>`+ (window['lang_page_page_strip'] || '条') + `</span>
                </span>
                <span class="to-page-input">
                    <span class="am-margin-left-sm">`+ (window['lang_page_jump_to_text'] || '跳转到') + `</span>
                    <input type="text" name="page" min="1" data-is-clearout="0" class="am-form-field am-inline-block am-text-center am-margin-horizontal-xs am-radius pagination-input" value="`+ page + `" data-type="page" data-default-value="1" data-value-max="` + page_total + `" onclick="this.select()">
                    <span>`+ (window['lang_page_page_unit'] || '页') + `</span>
                </span>`;
    }

    // 分页结束
    html += '</ul>';

    // 统计信息、换行展示
    if (is_extend) {
        html += `<div class="am-text-right am-text-grey">
                    <span>`+ (window['lang_page_data_total'] || '共 {:total} 条数据').replace('{:total}', total) + `</span>
                    <span class="am-margin-left-sm">`+ (window['lang_page_page_total'] || '共 {:total} 页').replace('{:total}', page_total) + `</span>
                </div>`;
    }
    return html;
}

/**
 * 地区联动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-23T22:00:30+0800
 * @param    {[int]}          pid     	[pid数据值]
 * @param    {[string]}       name      [当前节点name名称]
 * @param    {[string]}       next_name [下一个节点名称（数据渲染节点）]
 * @param    {[int]}          value 	[需要选中的值]
 * @param    {[int]}          ansyc 	[接口同步异步]
 */
function RegionNodeData (pid, name, next_name, value, ansyc = true) {
    if (pid !== null) {
        /* 没有指定选中值则从元素属性读取 */
        value = value || $('.region-linkage input[name=' + next_name + ']').val() || null;

        // 是否指定地区数据
        var data = $('.region-linkage').attr('data-original-value') || null;
            data = (data == null) ? null : (JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8)) || null);
        if(data != null) {
            var node = [];
            if(next_name == 'province') {
                node = data;
            } else if(next_name == 'city')
            {
                data.forEach((item) => {
                    if((item.items || null) != null && item.items.length > 0) {
                        if(item.id == pid) {
                            node = item.items;
                        }
                    }
                });
            } else if(next_name == 'county') {
                data.forEach((item) => {
                    if((item.items || null) != null && item.items.length > 0) {
                        item.items.forEach((items) => {
                            if(items.id == pid) {
                                node = items.items;
                            }
                        });
                    }
                });
            }
            if(node.length > 0) {
                /* html拼接 */
                var html = '';
                node.forEach((item) => {
                    html += '<li data-value="' + item['id'] + '" data-name="' + item['name'] + '" class="am-cascader-node'
                    if (value != null && value == item['id']) {
                        html += ' am-active ';
                    }
                    html += '"><span class="am-cascader-node-label">' + item['name'] + '</span>';
                    // 当是区的时候不显示箭头
                    if((item.items || null) != null && item.items.length > 0) {
                        html += '<i class="iconfont icon-arrow-right am-cascader-node-postfix"></i>';
                    }
                    html += '</li>';
                });

                /* 下一级数据添加 */
                $('.region-linkage .' + next_name).find('ul').html(html);
            }
        } else {
            $.ajax({
                url: RequestUrlHandle($('.region-linkage').attr('data-url')),
                type: 'POST',
                async: ansyc,
                data: { "pid": pid },
                dataType: 'json',
                success: function (result) {
                    if (result.code == 0) {
                        /* html拼接 */
                        var html = '';
                        for (var i in result.data) {
                            html += '<li data-value="' + result.data[i]['id'] + '" data-name="' + result.data[i]['name'] + '" class="am-cascader-node'
                            if (value != null && value == result.data[i]['id']) {
                                html += ' am-active ';
                            }
                            html += '"><span class="am-cascader-node-label">' + result.data[i]['name'] + '</span>';
                            // 当是区的时候不显示箭头
                            if (result.data[i].is_son === 'ok') {
                                html += '<i class="iconfont icon-arrow-right am-cascader-node-postfix"></i>';
                            }
                            html += '</li>';
                        }
                        /* 下一级数据添加 */
                        $('.region-linkage .' + next_name).find('ul').html(html);
                    } else {
                        Prompt(result.msg);
                    }
                }
            });

            /* 子级元素数据清空 */
            var child = null;
            switch (name) {
                case 'province':
                    child = ['city', 'county'];
                    break;

                case 'city':
                    child = ['county'];
                    break;
            }
            if (child != null) {
                for (var i in child) {
                    var $temp_obj = $('.region-linkage .' + child[i]);
                    $temp_obj.find('ul').empty();
                }
            }
        }
    }
}

/**
 * 编辑窗口额为参数处理
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-08-07
 * @desc    description
 * @param   {[object]}        data [数据]
 * @param   {[string]}        type [edit, add]
 * @param   {[string]}        tag  [tag标签]
 * @return  {[object]}             [处理后的数据]
 */
function FunSaveWinAdditional (data, type, tag = null) {
    // 额外处理数据
    if ($('#tree').length > 0 || $('#card').length > 0) {
        var additional = $('#tree, #card').data('additional') || null;
        if (additional != null) {
            for (var i in additional) {
                var value = (type == 'add') ? (additional[i]['value'] || '') : (data[additional[i]['field']] || additional[i]['value'] || '');
                switch (additional[i]['type']) {
                    // 表单
                    case 'input':
                    case 'select':
                    case 'textarea':
                        data[additional[i]['field']] = value;
                        break;

                    // 样式处理
                    case 'css':
                        // 设置样式
                        $(additional[i]['tag']).css(additional[i]['style'], value);
                        // 颜色选择器初始化
                        ColorPickerInit();
                        break;

                    // 文件
                    case 'file':
                        var $file_tag = $(additional[i]['tag']);
                        if ($file_tag.val().length > 0) {
                            $file_tag.after($file_tag.clone().val(''));
                            $file_tag.val('');
                        }
                        break;

                    // 附件
                    case 'annex':
                        if ((additional[i]['tag'] || null) != null) {
                            var $tag = $(additional[i]['tag']);
                            if ($tag.length > 0) {
                                var form_name = $tag.attr('data-form-name') || null;
                                var form_type = $tag.attr('data-dialog-type') || null;
                                var is_delete = ($tag.attr('data-delete') == undefined) ? 1 : parseInt($tag.attr('data-delete'));
                                var is_compose = $tag.find('li.plug-file-upload-submit').length > 0;
                                var is_eye = parseInt($tag.attr('data-is-eye') || 0);
                                var is_download = parseInt($tag.attr('data-is-download') || 0);
                                if (form_name != null && form_type != null) {
                                    // 是否json对象
                                    if ((value || null) != null && typeof value != 'object') {
                                        // 是否对象字符串
                                        if (value.substr(0, 1) == '{') {
                                            value = JSON.parse(value);
                                        } else {
                                            value = value.split(',');
                                        }
                                    }
                                    // 是否有数据
                                    if ((value || null) != null) {
                                        // 先清空数据、组合上传和显示则不清除
                                        if (!is_compose) {
                                            $tag.empty();
                                        }
                                        // 循环追加
                                        for (var fi in value) {
                                            if ((value[fi] || null) != null) {
                                                // 根据类型处理数据
                                                var input = null;
                                                var element = null;
                                                switch (form_type) {
                                                    // 图片
                                                    case 'images':
                                                        input = `<input type="hidden" name="` + form_name + `" value="` + value[fi] + `" />`;
                                                        element = `<img src="` + value[fi] + `" />`;
                                                        break;
                                                    // 视频
                                                    case 'video':
                                                        input = `<input type="hidden" name="` + form_name + `" value="` + value[fi] + `" />`;
                                                        element = `<video src="` + value[fi] + `" controls="">your browser does not support the video tag</video>`;
                                                        break;
                                                    // 文件
                                                    case 'file':
                                                        if ((value[fi]['title'] || null) != null && (value[fi]['url']) != null) {
                                                            input = `<input type="text" name="` + form_name + `[` + fi + `][title]" value="` + value[fi]['title'] + `" /><input type="text" name="` + form_name + `[` + fi + `][url]" value="` + value[fi]['title'] + `" />`;
                                                            element = `<a href="` + value[fi]['title'] + `" target="_blank">` + value[fi]['title'] + `</a>`;
                                                        }
                                                        break;
                                                }
                                                if (input != null && element != null) {
                                                    var html = input + element;
                                                    if (is_delete == 1) {
                                                        html += '<i class="iconfont icon-close"></i>';
                                                    }
                                                    if (is_eye == 1) {
                                                        html += '<i class="iconfont icon-eye" data-is-download="' + is_download + '"></i>';
                                                    }
                                                    if (is_compose) {
                                                        $tag.find('li.plug-file-upload-submit').html(html);
                                                    } else {
                                                        $tag.append(`<li>` + html + `</li>`);
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        if (is_compose) {
                                            $tag.find('li.plug-file-upload-submit').html('<i class="iconfont icon-upload-add"></i>');
                                        } else {
                                            // 没数据则判断是否可以删除、可以就清空则值赋为空
                                            if (is_delete == 1) {
                                                $tag.empty();
                                            } else {
                                                $tag.find('li input').val('');
                                                $tag.find('li img').attr('src', '');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    // 属性
                    case 'attr':
                        $(additional[i]['tag']).attr(additional[i]['style'], value);
                        break;

                    // 内容替换
                    case 'html':
                        $(additional[i]['tag']).html(value);
                        break;
                }
            }
        }
    }

    // 处理后初始数据
    FormDataFill(data, tag);
}

/**
 * 添加窗口初始化
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-08-06
 * @desc    description
 */
function TreeFormInit () {
    // popup窗口
    var $popup = $($('#tree').data('popup-tag') || '' + popup_tag + '');

    // 更改窗口名称
    var $title = $popup.find('.am-popup-title');
    if ($title.length == 0) {
        $title = $popup.find('.am-modal-hd > span');
    }
    $title.text($title.attr('data-add-title'));
    // 填充数据
    var data = { id: '', pid: 0, name: '', vice_name: '', describe: '', letters: '', lng: '', lat: '', code: '', sort: 0, is_enable: 1, icon: '', realistic_images: '', big_images: '', seo_title: '', seo_keywords: '', seo_desc: '', control: '', action: '', url: '' };
    // 指定字段
    var fields = $popup.data('fields') || null;
    if (fields != null) {
        var arr = fields.split(',');
        for (var i in arr) {
            data[arr[i]] = '';
        }
    }
    // 先初始数据
    FormDataFill(data);

    // 额外处理数据
    FunSaveWinAdditional(data, 'init');

    // 移除菜单禁止状态
    $popup.find('form select[name="pid"]').removeAttr('disabled');

    // 校验成功状态增加失去焦点
    $popup.find('form').find('.am-field-valid').each(function () {
        // 暂时去掉验证，避免打开弹窗就提示错误
        // $(this).blur();
    });

    // 多选插件事件更新
    SelectChosenInit();
}

/**
 * 卡片组件添加窗口初始化
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-08-06
 * @desc    description
 */
function CardFormInit () {
    // popup窗口
    var $popup = $($('#card').data('popup-tag') || '' + popup_tag + '');

    // 更改窗口名称
    var $title = $popup.find('.am-popup-title');
    if ($title.length == 0) {
        $title = $popup.find('.am-modal-hd > span');
    }
    $title.text($title.attr('data-add-title'));

    // 填充数据
    var data = { id: '', is_son: '', max_price: '', min_price: '', name: '', sort: 0, is_enable: 1, json: '', website_url: '', icon: '', };
    // 指定字段
    var fields = $popup.data('fields') || null;
    if (fields != null) {
        var arr = fields.split(',');
        for (var i in arr) {
            data[arr[i]] = '';
        }
    }

    // 先初始数据
    FormDataFill(data);

    // 额外处理数据
    FunSaveWinAdditional(data, 'init');

    // 移除菜单禁止状态
    $popup.find('form select[name="pid"]').removeAttr('disabled');

    // 校验成功状态增加失去焦点
    $popup.find('form').find('.am-field-valid').each(function () {
        // $(this).blur();
    });

    // 多选插件事件更新
    SelectChosenInit();
}

/**
 * 地图初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 * @param   {[float]}        	lng   		[经度]
 * @param   {[float]}        	lat   		[维度]
 * @param   {[int]}        		level 		[层级]
 * @param   {[boolean]}        	is_dragend 	[标注是否可拖拽]
 * @param   {[string]}        	mapid 	    [地图id（默认 map）]
 */
function MapInit (lng, lat, level, is_dragend, mapid) {
    // 地图容器
    if ((mapid || null) == null) {
        mapid = 'map';
    }
    $('#' + mapid).html('');

    // 默认16级
    level = level || $('#' + mapid).data('level') || 16;

    // 标点是否可以拖动
    if (is_dragend == undefined || is_dragend == true) {
        is_dragend = true;
    }

    // 经纬度
    lng = lng || 116.400244;
    lat = lat || 39.92556

    // 地图类型
    switch (__load_map_type__) {
        // 百度
        case 'baidu':
            var map = new BMap.Map(mapid, { enableMapClick: false });
            var point = new BMap.Point(lng, lat);
            map.centerAndZoom(point, level);

            // 添加控件
            var navigationControl = new BMap.NavigationControl({
                // 靠左上角位置
                anchor: BMAP_ANCHOR_TOP_LEFT,
                // LARGE类型
                type: BMAP_NAVIGATION_CONTROL_LARGE,
            });
            map.addControl(navigationControl);

            // 创建标注
            // 将标注添加到地图中
            var marker = new BMap.Marker(point);
            map.addOverlay(marker);

            // 标注是否可拖拽
            if (is_dragend) {
                marker.enableDragging();
                marker.addEventListener('dragend', function (e) {
                    map.panTo(e.point);
                    if ($('#form-lng').length > 0 && $('#form-lat').length > 0) {
                        $('#form-lng').val(e.point.lng);
                        $('#form-lat').val(e.point.lat);
                    }
                });

                // 设置标注提示信息
                var cr = new BMap.CopyrightControl({ anchor: BMAP_ANCHOR_BOTTOM_RIGHT });
                map.addControl(cr); //添加版权控件
                var bs = map.getBounds();   //返回地图可视区域
                cr.addCopyright({ id: 1, content: '<div class="map-dragging-tips"><span>' + (window['lang_map_dragging_icon_tips'] || '拖动红色图标直接定位') + '</span></div>', bounds: bs });
            }
            break;

        // 高德地图
        case 'amap':
            var map = new AMap.Map(mapid, { zoomEnable: true, resizeEnable: false, scrollWheel: false, zoom: level, center: [lng, lat] });
            // 插件控件
            AMap.plugin([
                'AMap.ToolBar',
            ], function () {
                // 在图面添加工具条控件, 工具条控件只有缩放功能
                map.addControl(new AMap.ToolBar());
            });

            // 创建标注
            var marker_config = {
                position: map.getCenter(),
                offset: new AMap.Pixel(-13, -30)
            };
            // 标注是否可拖拽
            if (is_dragend) {
                marker_config['draggable'] = true;
            }
            var marker = new AMap.Marker(marker_config);
            marker.setMap(map);

            // 标注可拖拽回调
            if (is_dragend) {
                marker.on('dragend', function (e) {
                    map.panTo(e.lnglat);
                    if ($('#form-lng').length > 0 && $('#form-lat').length > 0) {
                        $('#form-lng').val(e.lnglat.lng);
                        $('#form-lat').val(e.lnglat.lat);
                    }
                });
            }
            break;

        // 腾讯地图
        case 'tencent':
            // v2版本
            var point = new qq.maps.LatLng(lat, lng);
            var map = new qq.maps.Map(mapid, {
                center: point,
                zoom: level
            });
            var marker = new qq.maps.Marker({
                position: point,
                draggable: is_dragend,
                map: map
            });
            qq.maps.event.addListener(marker, 'dragend', function (e) {
                map.panTo(e.latLng);
                if ($('#form-lng').length > 0 && $('#form-lat').length > 0) {
                    $('#form-lng').val(e.latLng.lng);
                    $('#form-lat').val(e.latLng.lat);
                }
            });


            // // GL v1版本
            // var point = new TMap.LatLng(lat, lng);
            // //初始化地图
            // var map = new TMap.Map(mapid, {
            // 	zoom: level,//设置地图缩放级别
            // 	center: point//设置地图中心点坐标
            // });
            // var marker = new TMap.MultiMarker({
            // 	map: map,
            // 	geometries: [
            // 		{
            // 			position: point,
            // 			id: 'marker',
            // 		}
            // 	],
            // });
            // //监听marker点击事件
            // marker.on('click', function(e)
            // {
            // 	console.log(e);
            // });
            break;

        // 天地图
        case 'tianditu':
            // 初始化地图对象
            var map = new T.Map(mapid);
            // 设置显示地图的中心点和级别
            var point = new T.LngLat(lng, lat);
            map.centerAndZoom(point, level);
            // 禁止鼠标滚动缩小放大
            map.disableScrollWheelZoom();

            // 添加控件
            //创建缩放平移控件对象
            var control = new T.Control.Zoom();
            control.setPosition(T_ANCHOR_TOP_RIGHT);
            //添加缩放平移控件
            map.addControl(control);

            // 创建标注对象
            // 向地图上添加标注
            var marker = new T.Marker(point);
            map.addOverLay(marker);

            // 标注是否可拖拽
            if (is_dragend) {
                marker.enableDragging();
                marker.addEventListener('dragend', function (e) {
                    map.panTo(new T.LngLat(e.lnglat.lng, e.lnglat.lat));
                    if ($('#form-lng').length > 0 && $('#form-lat').length > 0) {
                        $('#form-lng').val(e.lnglat.lng);
                        $('#form-lat').val(e.lnglat.lat);
                    }
                });
            }
            break;

        // 默认
        default:
            Prompt((window['lang_map_type_not_exist_tips'] || '该地图功能未定义') + '(' + __load_map_type__ + ')');
    }

    //获取地址坐标
    if ($('#form-lng').length > 0 && $('#form-lat').length > 0 && lng != 0 && lat != 0) {
        $('#form-lng').val(lng);
        $('#form-lat').val(lat);
    }
}

/**
 * 表格容器处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-02-29
 * @desc    description
 */
function FormTableContainerInit () {
    if ($('.am-table-scrollable-horizontal').length > 0) {
        // 表格容器
        var $obj = $('.am-table-scrollable-horizontal');

        // 左固定
        $obj.find('> table > thead > tr, > table > tbody > tr').each(function (k, v) {
            var left = 0;
            $(this).find('.am-grid-fixed-left').each(function (ks, vs) {
                var width = parseInt($(this).attr('data-width') || $(this).innerWidth());
                $(this).css({ width: width + 'px', left: left + 'px' });
                left += width;
            });
        });

        // 右固定
        $obj.find('> table > thead > tr, > table > tbody > tr').each(function (k, v) {
            var fixed_right_arr = [];
            $(this).find('.am-grid-fixed-right').each(function (ks, vs) {
                fixed_right_arr.push($(this));
            });
            if (fixed_right_arr.length > 0) {
                var right = 0;
                fixed_right_arr.reverse().forEach((item) => {
                    var width = parseInt(item.attr('data-width') || item.innerWidth());
                    item.css({ width: width + 'px', right: right + 'px' });
                    right += width;
                });
            }
        });

        // 左边最后一列、右边第一列设置阴影样式
        $obj.find('> table tr').each(function (k, v) {
            $(this).find('.am-grid-fixed-left').last().addClass('am-grid-fixed-left-shadow');
            $(this).find('.am-grid-fixed-right').first().addClass('am-grid-fixed-right-shadow');
        });

        // 右侧操作栏更多按钮显示容器宽度处理点击事件、鼠标进入和移除事件
        $obj.find('.am-table tr .am-operate-grid-more-list button.am-dropdown-toggle').each(function (k, v) {
            FormTableContainerOperateGridMoreListInit($(this), false);
        });
        $obj.find('.am-table tr .am-operate-grid-more-list button.am-dropdown-toggle').on('click', function () {
            FormTableContainerOperateGridMoreListInit($(this));
        });
        $obj.find('.am-table tr .am-operate-grid-more-list button.am-dropdown-toggle').on('mouseenter', function () {
            FormTableContainerOperateGridMoreListInit($(this));
        });
        $obj.find('.am-table tr .am-operate-grid-more-list button.am-dropdown-toggle').on('mouseleave', function () {
            FormTableContainerOperateGridMoreListInit($(this));
        });
    }
}

/**
 * 表格容器列表更多操作处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-09-27
 * @desc    description
 * @param   {[object]}        e [当前对象]
 */
function FormTableContainerOperateGridMoreListInit (e, is_prompt = true) {
    var $parent = e.parent();
    var length = $parent.find('.am-dropdown-content .am-badge').length;
    if (length == 0) {
        $parent.removeClass('am-active');
        $parent.find('.am-dropdown-content').remove();
        e.find('>span').addClass('am-text-grey');
        if (is_prompt) {
            Prompt(window['lang_not_operate_error'] || '没有相关操作', 'warning');
        }
    } else {
        if (length > 0) {
            // 隐藏的元素无法获取宽度，使用一个浮动元素临时存放按钮获取宽度
            var key = 'temp-operate-more-item-width-container';
            $('body').append('<div class="' + key + '" style="position:fixed;left:-9999999999px;bottom:-9999999999px;"></div>');
            // 初始宽度设置误差6
            var width = 6;
            $parent.find('.am-dropdown-content .am-badge').each(function (k, v) {
                width += $('.' + key).html($(this).prop('outerHTML')).outerWidth(true);
            });
            $('.' + key).remove();
            $parent.find('.am-dropdown-content').css('width', width + 'px');
        }
        $parent.addClass('am-dropdown-flip');
    }
}

/**
 * 动态表格选中的值
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-26
 * @desc    description
 * @param   {[string]}        form 	[表单名称]
 * @param   {[string]}        tag 	[表单父级标签class或id]
 */
function FromTableCheckedValues (form, tag) {
    // 获取复选框选中的值
    var values = [];
    $(tag).find('input[name="' + form + '"]').each(function (key, tmp) {
        if ($(this).is(':checked')) {
            values.push(tmp.value);
        }
    });

    // 获取单选选中的值
    if (values.length <= 0) {
        var val = $(tag).find('input[name="' + form + '"]:checked').val();
        if (val != undefined) {
            values.push(val);
        }
    }
    return values;
}

/**
 * 判断变量是否为数组
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-28
 * @desc    description
 * @param   {[mixed]}        value [变量值]
 */
function IsArray (value) {
    return Object.prototype.toString.call(value) == '[object Array]';
}

/**
 * html转字符串
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-12-30
 * @desc    description
 * @param   {[string]}        html_str [html代码]
 */
function HtmlToString (html_str) {
    if ((html_str || null) != null) {
        function ToTxt (str) {
            var rex = /\<|\>|\"|\'|\&|　| /g;
            str = str.replace(rex, function (match_str) {
                switch (match_str) {
                    case '<':
                        return '&lt;';
                        break;
                    case '>':
                        return '&gt;';
                        break;
                    case '"':
                        return '&quot;';
                        break;
                    case '\'':
                        return '&#39;';
                        break;
                    case '&':
                        return '&amp;';
                        break;
                    case ' ':
                        return '&ensp;';
                        break;
                    case '　':
                        return '&emsp;';
                        break;
                }
            });
            return str;
        }
        return ToTxt(html_str).replace(/\&lt\;br[\&ensp\;|\&emsp\;]*[\/]?\&gt\;|\r\n|\n/g, '<br/>');
    }
}

/**
 * 获取浏览器参数
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-22
 * @desc    description
 * @param   {[string]}        field [参数字段名称、null则返回全部参数]
 */
function GetQueryValue (field = null) {
    // 路径参数值
    var path = window.location.pathname || null;
    var query = (path == null || path == '/') ? '' : path

    // 问号后面的参数值
    var search = window.location.search || null;
    if (search != null) {
        if (query != '') {
            query += '&';
        }
        query += search.substring(1);
    }

    // 首两个是否为s=字符，存在则去除
    if (query.substr(0, 2) == 's=') {
        query = query.substr(2);
    }
    // 第一个字符为斜杠，存在则去除
    if (query.substr(0, 1) == '/') {
        query = query.substr(1);
    }

    // 是否存在参数
    var vars = [];
    if (query != null) {
        var temp = query.split('&');
        if (temp.length > 0) {
            for (var i in temp) {
                // 参数是否为斜杠参数、仅首条记录处理
                if (i == 0 && temp[i].indexOf('/') != -1) {
                    var temp_field = null;
                    var temp_ds = temp[i].split('/');
                    var temp_count = temp_ds.length;
                    for (var x in temp_ds) {
                        // 奇数则忽略第一个参数（为系统路由名称）
                        if (temp_count % 2 != 0 && x == 0) {
                            continue;
                        }

                        // 参数组合
                        if (temp_field == null) {
                            temp_field = temp_ds[x];
                        } else {
                            vars[temp_field] = temp_ds[x].replace('.' + __seo_url_suffix__, '');
                            temp_field = null;
                        }
                    }
                } else {
                    var pair = temp[i].split('=');
                    if (pair.length == 2) {
                        vars[pair[0]] = pair[1];
                    }
                }
            }
        }
    }

    // 是否指定字段
    if (field === null) {
        return vars;
    } else {
        // 是否存在该字段数据
        for (var i in vars) {
            if (i == field) {
                return vars[i];
            }
        }
    }
    return false;
}

/**
 * uuid生成
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-03-13
 * @desc    description
 */
function UUId () {
    var d = new Date().getTime();
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : r & 0x3 | 0x8).toString(16);
    });
}

/**
 * 打开新窗口
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-03-21
 * @desc    description
 * @param   {String}        url    [url地址]
 * @param   {String}        name   [网页名称]
 * @param   {Number}        width  [宽度]
 * @param   {Number}        height [高度]
 */
function OpenWindow (url, name = '', width = 850, height = 600) {
    // window.screen.height获得屏幕的高
    // window.screen.width获得屏幕的宽
    // 获得窗口的垂直位置;
    var top = (window.screen.height - 30 - height) / 2;
    // 获得窗口的水平位置;
    var left = (window.screen.width - 10 - width) / 2;
    window.open(url, name, 'height=' + height + ',innerHeight=' + height + ',width=' + width + ',innerWidth=' + width + ',top=' + top + ',left=' + left + ',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
}

/**
 * 地址联动初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-04-11
 * @desc    description
 */
function RegionLinkageInit () {
    if ($('.region-linkage .am-cascader-menu').length > 0) {
        // 省初始化
        RegionNodeData(0, 'province', 'province');

        // 市初始化
        var value = $('.region-linkage input[name=province]').val() || 0;
        if (value != 0) {
            RegionNodeData(value, 'city', 'city');
        }

        // 区/县初始化
        var value = $('.region-linkage input[name=city]').val() || 0;
        if (value != 0) {
            RegionNodeData(value, 'county', 'county');
        }
    }
}

/**
 * 请求url地址处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-05-14
 * @desc    description
 * @param   {string}        url [请求url地址]
 */
function RequestUrlHandle (url) {
    // 增加系统参数
    return UrlFieldReplace('system_type', __system_type__, url);
}

/**
 * url使用当前host地址
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-05-16
 * @desc    description
 * @param   {string}        url [url地址]
 */
function UrlUseCurrentHostHandle (url) {
    var location = url.replace('://', '').indexOf('/');
    if (location != -1) {
        var first = url.substr(0, location + 4);
        if (__my_url__ != first) {
            url = __my_url__ + url.substr(location + 4);
        }
    }
    return url;
}

/**
 * 下拉选择组件初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-02
 * @desc    description
 */
function SelectChosenInit () {
    if ($('select.chosen-select').length > 0) {
        // 已初始化的则更新
        $('select.chosen-select.chosen-init-success').trigger('chosen:updated');

        // 初始化
        $('select.chosen-select:not(.chosen-init-success)').chosen({
            inherit_select_classes: true,
            enable_split_word_search: true,
            search_contains: true,
            no_results_text: window['lang_chosen_select_no_results_text'],
            disable_search_threshold: 10
        });
    }
}

/**
 * 状态切换组件初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-07-23
 * @desc    description
 */
function SwitchInit () {
    $('input[data-am-switch]').each(function () {
        if (!$(this).hasClass('am-switch-already')) {
            $(this).switch();
        }
        var val = $(this).is(':checked') ? true : false;
        $(this).switch().toggleSwitch(val);
    });
}

/**
 * 获取鼠标光标位置
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-07-04
 * @desc    description
 * @param   {[object]}        e [元素对象]
 */
function CursorPos (e) {
    var pos = 0;
    if (typeof (e) == 'object') {
        var el = e.get(0);
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var sel = document.selection.createRange();
            var sel_len = document.selection.createRange().text.length;
            sel.moveStart('character', -el.value.length);
            pos = sel.text.length - sel_len;
        }
    }
    return pos;
}

/**
 * json字符串转json对象
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-09-19
 * @desc    description
 * @param   {[string]}        value [json字符串]
 */
function JsonStringToJsonObject (value) {
    if ((value || null) != null && typeof (value) == 'string') {
        value = eval('(' + value + ')');
    }
    return value;
}

/**
 * json对象转json字符串
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-09-19
 * @desc    description
 * @param   {[object]}        value [json对象]
 */
function JsonObjectToJsonString (value) {
    if ((value || null) != null && typeof (value) == 'object') {
        value = JSON.stringify(value);
    }
    return value;
}

/**
 * 弹出内容处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-09-26
 * @desc    description
 * @param   [string]          content [展示的内容]
 */
function PopoverContentHandle (content) {
    return content.replace(new RegExp("\n", 'g'), '<br />').replace(new RegExp("\r", 'g'), '').replace(new RegExp("'", 'g'), '').replace(new RegExp('"', 'g'), '');
}

/**
 * 数据打印处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-09
 * @desc    description
 * @param   {int}        is_pdf [是否导出PDF（0否、1是）]
 */
function DataPrintHandle (is_pdf = 0) {
    // 打印模板数据
    var print_template = window['print_template'] || null;
    if (print_template == null) {
        Prompt(window['lang_operate_params_error'] || '操作参数有误');
        return false;
    }

    // 需要打印的数据
    var print_data = [];

    // 是否列表选择多选
    var print_is_list_choice = parseInt(window['print_is_list_choice'] || 0);
    if (print_is_list_choice == 1) {
        // 获取数据id
        var values = FromTableCheckedValues('form_checkbox_value', '.am-table-scrollable-horizontal');
        if (values.length <= 0) {
            Prompt(window['lang_before_choice_data_tips'] || '请先选择数据');
            return false;
        }

        // 获取打印数据
        for(var i in values) {
            var json = $('.am-table-scrollable-horizontal .form-table-data-list #data-list-'+values[i]).attr('data-original-form-table-item-data') || null;
            if(json != null) {
                json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)) || null;
                if(json != null) {
                    print_data.push(json);
                }
            }
        }
    } else {
        var json = $('.form-table-detail-nav-operate').attr('data-original-form-table-detail-data') || null;
        if(json != null) {
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8)) || null;
            if(json != null) {
                print_data.push(json);
            }
        }
    }

    // 获取需要打印的数据
    if (print_data.length == 0) {
        Prompt(window['lang_not_operate_error'] || '没有相关数据');
        return false;
    }

    // 是否已引入hiprint库
    if ((window['hiprint'] || null) == null) {
        Prompt(window['lang_not_load_lib_hiprint_error'] || '请先引入hiprint组件库');
        return false;
    }

    // 初始化模板
    var ht = new hiprint.PrintTemplate({ template: JsonStringToJsonObject(print_template) });

    // 是否导出pdf
    if (is_pdf == 1) {
        // 导出pdf
        var filename = $(this).data('file-name') || 'file-' + (new Date().getTime());
        ht.toPdf(print_data, filename);
    } else {
        // 调用打印组件
        ht.print(print_data, {});
    }
}

/**
 * 输入框清除按钮处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-30
 * @desc    description
 * @param   {[object]}        e [当前元素对象]
 */
function InputClearOutHandle (e) {
    var value = '';
    var $obj = e;
    // input/textarea、排除非下拉搜索的input
    if ((e.is('input') || e.is('textarea')) && !e.parent().hasClass('chosen-search') && !e.parent().hasClass('search-field') && !e.parent().hasClass('am-selected-search')) {
        var status = e.attr('data-is-clearout');
        if (status == undefined || parseInt(status) == 1) {
            value = e.val();
        }
    }
    // 插件下拉选择组件
    if (e.parents('.chosen-container').length > 0 && !e.is('input')) {
        $obj = e.parents('.chosen-container').prev();
        var status = e.parents('.chosen-container').prev().attr('data-is-clearout');
        if (status == undefined || parseInt(status) == 1) {
            value = e.parents('.chosen-container').prev().val();
        }
    }
    // 框架下拉选择组件
    if (e.parents('.am-selected').length > 0 && !e.is('input')) {
        $obj = e.parents('.am-selected').prev();
        var status = e.parents('.am-selected').prev().attr('data-is-clearout');
        if (status == undefined || parseInt(status) == 1) {
            value = e.parents('.am-selected').prev().val();
        }
    }
    // 值不为空或undefined
    if (value !== '' && value !== undefined && value !== null) {
        if (
            ((e.is('input') || e.is('textarea')) && e.attr('disabled') != 'disabled' && e.attr('readonly') != 'readonly') ||
            (e.parents('.chosen-container').length > 0 && !e.is('input')) ||
            (e.parents('.am-selected').length > 0 && !e.is('input'))
        ) {
            if ($obj.attr('disabled') === undefined && $obj.attr('readonly') === undefined) {
                // 添加清除按钮
                if (!e.next().is('a.input-clearout-submit')) {
                    e.after('<a href="javascript:;" class="input-clearout-submit"><i>&times;</i></a>');
                }
                // 清除按钮位置处理
                var scroll_top = $(document).scrollTop();
                var top = e.offset().top - scroll_top - 3;  // 解决清除按钮位置偏下的问题
                var left = e.offset().left;
                var width = e.innerWidth();
                var height = e.innerHeight();

                // 存在popup弹窗则减去弹窗的外边距
                if (e.parents('.am-popup').length > 0) {
                    var offset = e.parents('.am-popup').offset();
                    top -= offset.top;
                    left -= offset.left;
                }
                // 存在modal弹窗则减去弹窗的外边距
                if (e.parents('.am-modal').length > 0) {
                    var offset = e.parents('.am-modal .am-modal-dialog').offset();
                    top -= offset.top;
                    left -= offset.left;
                }
                // 存在tabs
                if (e.parents('.am-tab-panel').length > 0) {
                    var offset = e.parents('.am-tab-panel').offset();
                    left -= offset.left;
                    top = (scroll_top > 0) ? (scroll_top + top) - offset.top : top - offset.top;
                }

                // 设置位置
                e.next().css({ 'left': (left + width - 23) + 'px', 'top': (top + 4) + 'px', 'padding': (((height - 14) / 2) - 0.5) + 'px 4px' });
                e.addClass('input-clearout-element');

                return false;
            }
        }
    } else {
        // 无数据、存在清除按钮则移除
        if (e.next().is('a.input-clearout-submit')) {
            e.next().remove();
            e.removeClass('input-clearout-element');
        }
    }
}

/**
 * 颜色选择器初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-11-23
 * @desc    description
 */
function ColorPickerInit () {
    // 颜色选择器
    if ($('.colorpicker-container .colorpicker-submit').length > 0) {
        // 初始化
        $('.colorpicker-container .colorpicker-submit').each(function (k, v) {
            // 初始化颜色
            var init_color = $(this).attr('data-color') || $(this).parent().find('input').val() || '';
            // css样式
            var color_style = $(this).attr('data-color-style') || 'color';
            var style_arr = color_style.split('|');
            var style_value = {};
            for (var i in style_arr) {
                style_value[style_arr[i]] = init_color;
            }
            // input 赋值
            $($(this).data('color-tag')).val(init_color);
            // 其他dom赋值
            $($(this).data('input-tag')).css(style_value);
            // 当前元素初始背景
            $(this).css('background', init_color);
            // 当前元素已初始的组件背景
            $(this).find('.fcolorpicker-curbox').css('background', init_color);
        });

        // 调用颜色组件
        $(document).on('click', '.colorpicker-container', function () {
            var $self = $(this).find('.colorpicker-submit');
            if ($self.find('.fcolorpicker-curbox').length == 0) {
                // 颜色格式
                var color_format = $self.attr('data-color-format') || 'hex';
                // 初始化颜色
                var init_color = $self.attr('data-color') || '';
                // css样式
                var color_style = $self.attr('data-color-style') || 'color';
                var color_style_arr = color_style.split('|');
                // 语言
                var lang = (window['lang_multilingual_default_code'] || 'zh-cn') == 'en' ? 'en' : 'cn';
                new XNColorPicker({
                    color: init_color,
                    selector: $self,
                    showprecolor: true,//显示预制颜色
                    prevcolors: null,//预制颜色，不设置则默认
                    showhistorycolor: true,//显示历史
                    historycolornum: 16,//历史条数
                    format: color_format,//rgba hex hsla,初始颜色类型
                    showPalette: true,//显示色盘
                    show: true, //初始化显示
                    lang: lang,// cn 、en
                    colorTypeOption: 'single,linear-gradient,radial-gradient', //颜色选择器可选类型，纯色，线性渐变，径向渐变
                    canMove: true, //选择器位置是否可以拖拽
                    alwaysShow: false,
                    autoConfirm: true, //改变颜色时自动确认
                    onError: function (e) { },
                    onCancel: function (color) {
                        var style_value = {};
                        if (color.colorType === 'linear-gradient' || color.colorType === 'radial-gradient') {
                            // 只适用于背景色
                            for (var i in color_style_arr) {
                                style_value[color_style_arr[i]] = '';
                            }
                        } else {
                            for (var i in color_style_arr) {
                                style_value[color_style_arr[i]] = '';
                            }
                        }
                        // input 赋值
                        $($self.data('color-tag')).val('');
                        // 其他dom赋值
                        $($self.data('input-tag')).css(style_value);
                        $($self.data('color-tag')).trigger('change');
                        // 清除当前自身按钮背景色
                        $self.css('background', '');
                        $self.find('.fcolorpicker-curbox').css('background', '');
                    },
                    onChange: function (color) {
                        var style_value = {};
                        if (color.colorType === 'linear-gradient' || color.colorType === 'radial-gradient') {
                            // 只适用于背景色
                            for (var i in color_style_arr) {
                                style_value[color_style_arr[i]] = color.color.str;
                            }
                            // input 赋值
                            $($self.data('color-tag')).val(color.color.str);
                            // 清除当前自身按钮背景色
                            $self.css('background', color.color.str);
                        } else {
                            for (var i in color_style_arr) {
                                style_value[color_style_arr[i]] = color.color[color_format];
                            }
                            // input 赋值
                            $($self.data('color-tag')).val(color.color[color_format]);
                            // 清除当前自身按钮背景色
                            $self.css('background', color.color[color_format]);
                        }
                        // 其他dom赋值
                        $($self.data('input-tag')).css(style_value);
                        $($self.data('color-tag')).trigger('change');
                    },
                    onConfirm: function (color) {
                        var style_value = {};
                        if (color.colorType === 'linear-gradient' || color.colorType === 'radial-gradient') {
                            // 只适用于背景色
                            for (var i in color_style_arr) {
                                style_value[color_style_arr[i]] = color.color.str;
                            }
                            // input 赋值
                            $($self.data('color-tag')).val(color.color.str);
                            // 清除当前自身按钮背景色
                            $self.css('background', color.color.str);
                        } else {
                            for (var i in color_style_arr) {
                                style_value[color_style_arr[i]] = color.color[color_format];
                            }
                            // input 赋值
                            $($self.data('color-tag')).val(color.color[color_format]);
                            // 清除当前自身按钮背景色
                            $self.css('background', color.color[color_format]);
                        }
                        // 其他dom赋值
                        $($self.data('input-tag')).css(style_value);
                        $($self.data('color-tag')).trigger('change');
                    }
                });
            }
        });
    }
}

/**
 * 获取规格详情
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-12-14
 * @desc    description
 */
function CommonGoodsChoiceSpecDetail () {
    // 是否全部选中
    var $spec = $('.common-goods-spec-choice-content');
    var sku_count = $spec.find('.sku-items').length;
    var active_count = $spec.find('.sku-items li.selected').length;
    if (active_count < sku_count) {
        return false;
    }

    // 获取规格值
    var spec = [];
    $spec.find('.sku-items li.selected').each(function (k, v) {
        spec.push({ "type": $(this).data('type-value'), "value": $(this).data('value') })
    });

    // ajax请求
    $.AMUI.progress.start();
    $.ajax({
        url: RequestUrlHandle(__goods_spec_detail_url__),
        type: 'post',
        dataType: 'json',
        timeout: 10000,
        data: { "id": $spec.data('id'), "spec": spec },
        success: function (result) {
            $.AMUI.progress.done();
            if (result.code != 0) {
                Prompt(result.msg);
            }
        },
        error: function (xhr, type) {
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
        }
    });
}

/**
 * 获取规格类型
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-12-14
 * @desc    description
 */
function CommonGoodsChoiceSpecType () {
    // 是否全部选中
    var $spec = $('.common-goods-spec-choice-content');
    var sku_count = $spec.find('.sku-items').length;
    var active_count = $spec.find('.sku-items li.selected').length;
    if (active_count <= 0 || active_count >= sku_count) {
        return false;
    }

    // 获取规格值
    var spec = [];
    $spec.find('.sku-items li.selected').each(function (k, v) {
        spec.push({ "type": $(this).data('type-value'), "value": $(this).data('value') })
    });

    // ajax请求
    $.AMUI.progress.start();
    $.ajax({
        url: RequestUrlHandle(__goods_spec_type_url__),
        type: 'post',
        dataType: 'json',
        timeout: 10000,
        data: { "id": $spec.data('id'), "spec": spec },
        success: function (result) {
            $.AMUI.progress.done();
            if (result.code == 0) {
                var spec_count = spec.length;
                var index = (spec_count > 0) ? spec_count : 0;
                if (index < sku_count) {
                    $spec.find('.sku-items').eq(index).find('li').each(function (k, v) {
                        $(this).removeClass('sku-dont-choose');
                        var value = $(this).data('value').toString();
                        if (result.data.spec_type.indexOf(value) == -1) {
                            $(this).addClass('sku-items-disabled');
                        } else {
                            $(this).removeClass('sku-items-disabled');
                        }
                    });
                }
            } else {
                Prompt(result.msg);
            }
        },
        error: function (xhr, type) {
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
        }
    });
}

/**
 * 二维码初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-01-09
 * @desc    description
 */
function ViewQrCodeInit () {
    $('.view-qrcode-init').each(function () {
        var value = $(this).attr('data-value') || $(this).text();
        if (value !== '') {
            $(this).empty().qrcode({
                text: value,
                width: parseInt($(this).data('width') || 100),
                height: parseInt($(this).data('height') || 100)
            });
        }
    });
}

/**
 * 标签title属性初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-07-22
 * @desc    description
 */
function ViewDocumentTitleInit () {
    $('*').each(function (k, v) {
        if ($(this).prop('tagName') != 'IMG') {
            if ($(this).attr('data-am-popover') === undefined) {
                var title = $(this).attr('title') || null;
                if (title !== null) {
                    $(this).popover({
                        content: title,
                        trigger: 'hover focus',
                        theme: 'sm'
                    });
                    $(this).removeAttr('title');
                }
            } else {
                $(this).popover();
            }
        }
    });
}

/**
 * 自定义url打开处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-03-17
 * @desc    description
 * @param   {[string]}        value [自定义url信息]
 */
function CustomUrlOpenHandle (value) {
    if ((value || null) != null) {
        // 地图协议
        if (value.substr(0, 6) == 'map://') {
            var values = value.substr(6).split('|');
            if (values.length == 4) {
                // 拼接地图地址、并调用弹窗方法
                ModalLoad(UrlFieldReplace('lat', values[3], UrlFieldReplace('lng', values[2], __map_view_url__)));
            }

            // 电话协议
        } else if (value.substr(0, 6) == 'tel://') {
            window.location.href = value;

            // 默认新标签跳转页面
        } else {
            window.open(value, '_blank');
        }
    }
}

/**
 * 附件字节大小转换为单位
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-21
 * @desc    description
 * @param   {int}        size [文件大小]
 */
function AnnexSizeToUnit (size = 0) {
    var unit = 'KB';
    size = parseInt(size || 0);
    var kb = size / 1024;
    if (kb < 1024) {
        unit = 'KB';
        size = Math.round(kb * 100) / 100;
    } else if (kb < 1024 * 1024) {
        unit = 'MB';
        size = Math.round((size / (1024 * 1024)) * 100) / 100;
    } else if (kb < 1024 * 1024 * 1024) {
        unit = 'GB';
        size = Math.round((size / (1024 * 1024 * 1024)) * 100) / 100;
    }
    return size + unit
}

/**
 * 文本复制初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2025-02-16
 * @desc    description
 */
function TextCopyinit() {
    // 文本信息复制
    if ($('.text-copy-submit').length > 0) {
        // 阻止a标签跳转
        $(document).on('click', 'a.text-copy-submit', function (e) {
            e.preventDefault();
        });
        // 调用复制文本组件
        var text_copy_clipboard = new ClipboardJS('.text-copy-submit',
            {
                text: function (e) {
                    return $(e).attr('data-value') || $(e).attr('href') || $(e).attr('src') || $(e).text().trim();
                }
            });
        text_copy_clipboard.on('success', function (e) {
            Prompt(window['lang_copy_success'] || '复制成功', 'success');
        });
        text_copy_clipboard.on('error', function (e) {
            Prompt(window['lang_copy_fail'] || '复制失败');
        });
    }
}

/**
 * 表格数据模块初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-07-04
 * @desc    description
 */
function FormTableContentModuleInit () {
    // 列表数据容器
    var $obj = $('.am-table-scrollable-horizontal');

    // 动态列表数据初始化
    FormTableContainerInit();

    // 二维码初始化
    ViewQrCodeInit();

    // 文本复制初始化
    TextCopyinit();

    // 标签title属性初始化
    ViewDocumentTitleInit();

    // 折叠组件初始化
    $.AMUI.accordion.init();
    // 图片预览初始化
    $.AMUI.figure.init();
    // 图片画廊初始化
    $.AMUI.gallery.init();

    // 其他模块
    $obj.find('*').each(function (k, v) {
        // 单选、多选组建初始化
        if ($(this).attr('data-am-ucheck') !== undefined) {
            $(this).uCheck();
        }

        // 弹窗提示框
        if ($(this).attr('data-am-popover') !== undefined) {
            $(this).popover();
        }

        // 下拉组建
        if ($(this).attr('data-am-dropdown') !== undefined) {
            $(this).dropdown();
        }
    });
}

/**
 * 初始化生成滚动箭头
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-10-21
 * @desc    只可以放在循环的子元素的上两级，或ul的上一级 例如<div class="am-slider-horizontal"><ul><li></li></ul></div>
 */
function InitScroll () {
    if ($('body').find('.am-slider-horizontal').length > 0) {
        $('.am-slider-horizontal').each(function () {
            // 判断长度 =》 是否生成滚动箭头
            var parent_width = $(this).width();
            var children_width_li = 0;
            $(this).find('li').each(function (index, item) {
                children_width_li += $(item).width();
            })
            // 判断是否需要生成右侧按钮
            if (parent_width >= children_width_li) {
                // 判断是否已生成过右侧箭头按钮
                if ($(this).find('.tabs-right').length > 0) {
                    $(this).find('.tabs-right').remove();
                }
            } else {
                // 判断是否已生成过右侧箭头按钮
                if ($(this).find('.tabs-right').length < 1) {
                    var slider_html = '<div class="tabs-right" data-num="1" onclick="TabsEvent(this,' + parent_width + ',' + children_width_li + ')">' +
                        '<i class="am-icon-chevron-right"></i>' +
                        '</div>';
                    $(this).append(slider_html);
                }
            }
        })
    }
}

/**
 * tabs导航事件
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-10-21
 * @desc    description
 * @param   {[string]}     obj        [tab元素]
 * @param   {[int]}        parent_w   [父级宽度]
 * @param   {[int]}        children_w [子级宽度]
 */
function TabsEvent (obj, parent_w, children_w) {
    var pratnt_obj = $(obj).parent();
    var num = $(obj).attr('data-num');
    if (num * parent_w >= children_w) {
        pratnt_obj.animate({ scrollLeft: 0 }, 500);
        $(obj).attr('data-num', 1)
    } else {
        pratnt_obj.animate({ scrollLeft: parent_w * num }, 500);
        $(obj).attr('data-num', Number($(obj).attr('data-num')) + 1);
    }
}

/**
 * 公共单个文件上传表单回调处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-10-21
 * @desc    description
 */
function CommonFormUploadEditorSingleBackHandle (params) {
    var $form = $('form.form-validation-common-upload-editor-single');
    $form.find('button[type="reset"]').trigger('click');
    $form.find('button[type="submit"]').button('reset');
    if (params.code == 0) {
        CommonFormUploadEditorDataViewHandle([params.data], $($('body').attr('view-tag')).attr('data-dialog-type') || 'images');
    } else {
        Prompt(params.msg);
    }
}

/**
 * 公共表单文件上传数据展示处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-10-21
 * @desc    description
 * @param   {[array]}        data [数据列表]
 * @param   {[string]}       type [类型（images 图片、video 视频、file 文件）]
 */
function CommonFormUploadEditorDataViewHandle (data, type = 'images') {
    if ((data || null) != null && data.length > 0) {
        var $tag = $($('body').attr('view-tag'));
        var max_number = $tag.attr('data-max-number') || 0;
        var is_delete = ($tag.attr('data-delete') == undefined) ? 1 : parseInt($tag.attr('data-delete'));
        var form_name = $tag.attr('data-form-name') || '';
        var is_attr = $tag.attr('data-is-attr') || null;
        var is_compose = $tag.find('li.plug-file-upload-submit').length > 0;
        var is_eye = parseInt($tag.attr('data-is-eye') || 0);
        var is_download = parseInt($tag.attr('data-is-download') || 0);

        // 只限制一条、排除上传和显示组合的方式
        if (max_number <= 1 && !is_compose) {
            $tag.find('li').remove();
        }

        // 根据类型处理
        switch (type) {
            // 图片
            case 'images':
                for (var i in data) {
                    var src = data[i]['src'] || data[i]['url'];
                    // 是否直接赋值属性
                    if (i == 0 && is_attr != null) {
                        $('form [name="' + form_name + '"]').val(src);
                        $tag.attr(is_attr, src);
                        break;
                    }

                    // 是否上传和显示组合模式
                    if (is_compose) {
                        var html = '<input type="text" name="' + form_name + '" value="' + src + '" />';
                        html += '<img src="' + src + '" />';
                        if (is_delete == 1) {
                            html += '<i class="iconfont icon-close"></i>';
                        }
                        if (is_eye == 1) {
                            html += '<i class="iconfont icon-eye" data-is-download="' + is_download + '"></i>';
                        }
                        $tag.find('li.plug-file-upload-submit').html(html);
                    } else {
                        // 是否限制数量
                        if (max_number > 0 && $tag.find('li').length >= max_number) {
                            var temp_msg = window['lang_upload_images_max_tips'] || '最多上传{value}张图片';
                            Prompt(temp_msg.replace('{value}', max_number));
                            break;
                        }

                        // 拼接html追加
                        var html = '<li>';
                        html += '<input type="text" name="' + form_name + '" value="' + src + '" />';
                        html += '<img src="' + src + '" />';
                        if (is_delete == 1) {
                            html += '<i class="iconfont icon-close"></i>';
                        }
                        if (is_eye == 1) {
                            html += '<i class="iconfont icon-eye" data-is-download="' + is_download + '"></i>';
                        }
                        html += '</li>';
                        $tag.append(html);
                    }
                }
                break;

            // 视频
            case 'video':
                for (var i in data) {
                    var src = data[i]['src'] || data[i]['url'];
                    // 是否直接赋值属性
                    if (i == 0 && is_attr != null) {
                        $('form [name="' + form_name + '"]').val(src);
                        $tag.attr(is_attr, src);
                        break;
                    }

                    // 是否上传和显示组合模式
                    if (is_compose) {
                        var html = '<input type="text" name="' + form_name + '" value="' + src + '" />';
                        html += '<video src="' + src + '" controls>your browser does not support the video tag</video>';
                        if (is_delete == 1) {
                            html += '<i class="iconfont icon-close"></i>';
                        }
                        if (is_eye == 1) {
                            html += '<i class="iconfont icon-eye" data-is-download="' + is_download + '"></i>';
                        }
                        $tag.find('li.plug-file-upload-submit').html(html);
                    } else {
                        // 是否限制数量
                        if (max_number > 0 && $tag.find('li').length >= max_number) {
                            var temp_msg = window['lang_upload_video_max_tips'] || '最多上传{value}个视频';
                            Prompt(temp_msg.replace('{value}', max_number));
                            break;
                        }

                        var html = '<li>';
                        html += '<input type="text" name="' + form_name + '" value="' + src + '" />';
                        html += '<video src="' + src + '" controls>your browser does not support the video tag</video>';
                        if (is_delete == 1) {
                            html += '<i class="iconfont icon-close"></i>';
                        }
                        if (is_eye == 1) {
                            html += '<i class="iconfont icon-eye" data-is-download="' + is_download + '"></i>';
                        }
                        html += '</li>';
                        $tag.append(html);
                    }
                }
                break;

            // 文件
            case 'file':
                for (var i in data) {
                    var src = data[i]['src'] || data[i]['url'];
                    // 是否直接赋值属性
                    if (i == 0 && is_attr != null) {
                        $('form [name="' + form_name + '"]').val(src);
                        $tag.attr(is_attr, src);
                        break;
                    }

                    // 是否上传和显示组合模式
                    if (is_compose) {
                        var html = '<input type="text" name="' + form_name + '[' + index + '][title]" value="' + data[i].title + '" />';
                        html += '<input type="text" name="' + form_name + '[' + index + '][url]" value="' + src + '" />';
                        html += '<a href="' + src + '" target="_blank">' + data[i].title + '</a>';
                        if (is_delete == 1) {
                            html += '<i class="iconfont icon-close"></i>';
                        }
                        $tag.find('li.plug-file-upload-submit').html(html);
                    } else {
                        // 是否限制数量
                        if (max_number > 0 && $tag.find('li').length >= max_number) {
                            var temp_msg = window['lang_upload_annex_max_tips'] || '最多上传{value}个附件';
                            Prompt(temp_msg.replace('{value}', max_number));
                            break;
                        }

                        var index = parseInt($tag.find('li').length) || 0;
                        var html = '<li>';
                        html += '<input type="text" name="' + form_name + '[' + index + '][title]" value="' + data[i].title + '" />';
                        html += '<input type="text" name="' + form_name + '[' + index + '][url]" value="' + src + '" />';
                        html += '<a href="' + src + '" target="_blank">' + data[i].title + '</a>';
                        if (is_delete == 1) {
                            html += '<i class="iconfont icon-close"></i>';
                        }
                        html += '</li>';
                        $tag.append(html);
                    }
                }
                break;
        }
    }
}


/**
 * 卡片组件
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-10-21
 * @desc    description
 * @param    {[int]}    	id    			[节点id]
 * @param    {[string]}   	url   			[请求url地址]
 * @param    {[int]}      	is_delete_all	[是否所有开启删除按钮]
 */
function Card (id, url, is_delete_all = 0) {
    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: 60000,
        data: { "id": id },
        success: function (result) {
            $('#card').attr('data-is-delete-all', is_delete_all);
            // html
            var list_html = '<ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-5 am-thumbnails">';
            // 是否有自定义配置
            var popup_tag = $('#card').attr('data-popup-tag');
            var modal_config = JsonStringToJsonObject($('#card').attr('data-modal-config') || {});
            modal_config['target'] = popup_tag;
            modal_config = JSON.stringify(modal_config);
            if (result.code == 0) {
                var html = '';
                if (result.data.length > 0) {
                    for (var i in result.data) {
                        html += CardItemHtmlHandle(result.data[i], id, is_delete_all)
                    }
                }
                list_html += html;
            }
            list_html += `<li class="card-submit-add" data-am-modal=\'` + modal_config + `\'><div class="item-last"><i class="iconfont icon-btn-add"></i></div></li>`;
            list_html += '</ul>';
            $('#card').html(list_html);
        },
        error: function (xhr, type) {
            $('#card').find('p').text(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
            $('#card').find('img').attr('src', __attachment_host__ + '/static/common/images/no-data.png');
        }
    });
}

/**
 * card卡片组件数据保存回调处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-19
 * @desc    description
 * @param   {[boject]}        e [当前回调数据]
 */
function CardFormSaveBackHandle (e) {
    $.AMUI.progress.done();
    var popup_tag = $('#card').attr('data-popup-tag');
    var $popup = $(popup_tag);
    var $button = $popup.find('button[type="submit"]');
    if (e.code == 0) {
        Prompt(e.msg, 'success');
        // 是否有自定义配置
        var modal_config = JsonStringToJsonObject($('#card').attr('data-modal-config') || {});
        modal_config['target'] = popup_tag;
        modal_config = JSON.stringify(modal_config);

        // 数据处理
        if ((e.data || null) != null) {
            if (typeof (e.data) == 'object') {
                var json = e.data;
                var string = JSON.stringify(e.data);
            } else {
                var json = JSON.parse(decodeURIComponent(e.data));
                var string = e.data;
            }
            if ((json.id || null) != null) {
                // 存在数据编辑、则添加
                var $obj = $('#data-list-' + json.id);
                if ($obj.length > 0) {
                    // 原始json数据
                    var json_old = JSON.parse(decodeURIComponent($obj.find('.submit-edit').attr('data-json')));

                    // 名称更新
                    $obj.find('.item .name').text(json.name);

                    // 状态处理
                    if (json.is_enable != json_old.is_enable) {
                        if ($obj.hasClass('am-active')) {
                            $obj.removeClass('am-active');
                        } else {
                            $obj.addClass('am-active');
                        }
                    }

                    // 属性json数据更新
                    $obj.find('.submit-edit').attr('data-json', encodeURIComponent(string));
                } else {
                    // 存在pid直接拉取下级数据,则追加新数据
                    var is_delete_all = parseInt($('#card').attr('data-is-delete-all') || 0);
                    if (json.pid > 0) {

                        // 数据子级读取
                        var level = $('#data-list-' + json.pid).length > 0 ? parseInt($('#data-list-' + json.pid).attr('data-level') || 0) + 1 : 0;
                        Card(json.pid, $('#card').data('node-url'), level, is_delete_all);
                    } else {
                        json['json'] = string;

                        // 拼接html数据
                        var html = CardItemHtmlHandle(json, 0, 0, is_delete_all);

                        // 首次则增加table标签容器
                        if ($('#card ul li').length > 1) {
                            var last_item = $('#card ul li:last');
                            last_item.prev().after(html);
                        } else {
                            $('#card').html(html);
                            $('#card').html('<ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-5 am-thumbnails">' + html + `<li class="card-submit-add" data-am-modal=\'` + modal_config + `\'><div class="item-last"><i class="iconfont icon-btn-add"></i></div></li>` + '</ul>');
                        }
                    }
                }
            }
        }
        setTimeout(function () {
            $button.button('reset');
            $popup.modal('close');
        }, 1000);
    } else {
        $button.button('reset');
        Prompt(e.msg);
    }
}
/**
 * Card卡片组建列表数据处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-19
 * @desc    description
 * @param    {[boject]}     item            [数据]
 * @param    {[int]}    	pid    			[节点pid]
 * @param    {[int]}      	is_delete_all	[是否所有开启删除按钮]
 */
function CardItemHtmlHandle (item, pid, is_delete_all) {
    // 基础参数处理
    is_delete_all = is_delete_all || 0;
    var rank = parseInt($('#card').attr('data-rank')) || 0;
    var delete_url = $('#card').data('del-url');
    var class_name = $('#data-list-' + pid).attr('class') || '';
    class_name = class_name.replace('am-active', '');
    var popup_tag = $('#card').data('popup-tag') || '' + popup_tag + '';
    // 是否有自定义配置
    var modal_config = JsonStringToJsonObject($('#card').attr('data-modal-config') || {});
    modal_config['target'] = popup_tag;
    modal_config = JSON.stringify(modal_config);

    // 数据 start
    var is_active = (item.is_enable == 0) ? 'am-active' : '';
    html = '<li id="data-list-' + item.id + '" data-value="' + item.id + '" class="' + class_name + ' cart-pid-' + pid + ' ' + is_active + '">';
    html += '<div class="item">';
    html += '<div class="am-text-center price-range">';
    var data_prefix = $('#card').data('prefix');
    if (data_prefix) {
        switch (data_prefix.type) {
            // icon
            case 'icon':
                html += '<i class="iconfont ' + item[data_prefix.type] + '"></i>';
                break;

            // 图片
            case 'img':
                html += '<img src="' + item[data_prefix.field] + '" height="30" class="am-vertical-align-middle am-radius am-margin-right-sm"></img>';
                break;
            // 默认
            default:
                html += data_prefix.name ? '<span>' + data_prefix.name + '</span>' : '';
        }
    }
    html += '<span class="name">' + item.name + '</span>';
    var data_suffix = $('#card').data('suffix');
    if (data_suffix) {
        switch (data_suffix.type) {
            // icon
            case 'icon':
                html += '<i class="iconfont ' + item[data_suffix.type] + '"></i>';
                break;

            // 图片
            case 'img':
                html += '<img src="' + item[data_suffix.field] + '" height="30" class="am-vertical-align-middle am-radius am-margin-left-sm"></img>';
                break;
            // 默认
            default:
                html += '<span>' + data_suffix.name + '</span>';
        }
    }
    html += '</div>';
    // 数据 end
    // 操作项 start
    html += '<div class="submit am-flex am-flex-justify-right am-flex-items-center am-gap-12">';

    // 编辑
    html += '<a href="javascript:;" class="submit-edit" data-am-modal=\'' + modal_config + '\' data-json="' + encodeURIComponent(item.json) + '"> ' + (window['lang_operate_edit_name'] || '编辑') + '</a>';
    if (item.is_son != 'ok' || is_delete_all == 1) {
        // 是否需要删除子数据
        var pid_class = is_delete_all == 1 ? '.cart-pid-' + item.id : '';
        // 删除
        html += '<a href="javascript:;" class="submit-delete cr-red am-divider-l-d am-padding-left" data-id="' + item.id + '" data-url="' + delete_url + '" data-ext-delete-tag="' + pid_class + '"> ' + (window['lang_operate_delete_name'] || '删除') + '</a>';
    }
    html += '</div>';
    // 操作项 end
    html += '</div>';
    html += '</li>';
    return html;
}

/**
 * 语音提醒
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-03-06
 * @desc    description
 * @param   {[string]}        $mp3 [mp3地址]
 */
function VoiceNotice (mp3) {
    // 存在则先移除
    if ($('.common-voice-container').length > 0) {
        $('.common-voice-container').remove();
    }
    // 添加提示
    $('body').append('<audio class="common-voice-container" src="' + mp3 + '" controls autoplay></audio>');
}

/**
 * 附件预览
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-04-19
 * @desc    description
 * @param   {[string]}        type  [文件类型（video 视频， images 默认图片）]
 * @param   {[string]}        value [附件地址]
 * @param   {[object]}        obj   [元素对象]
 */
function AnnexView (type, value, obj) {
    if ((value || null) != null) {
        var title = (typeof (obj) == 'object') ? (obj.attr('data-title') || null) : null;
        var html = null;
        var style = 'max-width:100%; max-height:calc(90vh - 4.5rem); margin: 0 auto;';
        switch (type) {
            case 'video':
                title = title || window['lang_video_preview_title'] || '视频预览';
                html = '<video src="' + value + '" style="' + style + '" controls autoplay>your browser does not support the video tag</video>';
                break;
            default:
                title = title || window['lang_images_preview_title'] || '图片预览';
                html = '<img src="' + value + '" class="am-block" style="' + style + '" />';
        }
        // 是否存在元素对象
        if (typeof (obj) == 'object') {
            // 是否开启下载
            if (parseInt(obj.attr('data-is-download') || 0) == 1) {
                html += '<button type="button" class="am-btn am-btn-primary am-btn-xs am-radius am-animation-slide-bottom btn-loading-example common-annex-download-event" data-value="' + value + '" data-name="' + (obj.attr('data-download-name') || '') + '" data-am-loading="{spinner: \'circle-o-notch\', loadingText:\'\'}" style="position: absolute;left: calc(50% - 2rem);bottom: 1rem;height: 2.2rem;line-height: 2.2rem;"><i class="iconfont icon-download-btn"></i></button>';
            }
        }
        if (html != null) {
            AMUI.dialog.alert({
                title: title,
                isClose: true,
                config: {},
                style: 'max-width: 80%; max-height: 90%; left: auto; min-width: 12rem;',
                content_style: 'padding: 0; border-bottom: 0;',
                content: '<div class="'+((typeof (obj) == 'object') ? (obj.attr('data-class') || '') : '')+'">'+html+'</div>',
            });
        }
    }
}

/**
 * highlight代码高亮初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-26
 * @desc    description
 */
function HighlightInit () {
    if ($('.richtext pre').length > 0) {
        $('.richtext pre').each(function () {
            hljs.highlightBlock(this);
        });
    }
}

/**
 * Dropdown下拉组件初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-26
 * @desc    description
 */
function DropdownInit () {
    if ($('.am-dropdown').length > 0) {
        $('.am-dropdown').dropdown();
    }
}





// 公共数据操作
$(function () {
    // 默认初始化一次,默认标签[form.form-validation]
    FromInit('form.form-validation');
    // 公共列表 form 搜索条件
    FromInit('form.form-validation-search');
    // 公共单个文件上传表单初始化
    FromInit('form.form-validation-common-upload-editor-single');

    // 表格初始化
    FormTableContainerInit();

    // 颜色选择器初始化
    ColorPickerInit();

    // 二维码初始化
    ViewQrCodeInit();

    // 文本复制初始化
    TextCopyinit();

    // 标签title属性初始化
    ViewDocumentTitleInit();

    // highlight代码高亮初始化
    HighlightInit();

    // 隐藏播放器下载按钮和右击事件
    $('video').attr('controlslist', 'nodownload');
    $('video').bind('contextmenu', function () { return false; });

    // 表格字段数据排序
    $('.form-sort-container .sort-icon').on('click', function () {
        var key = $(this).data('key') || null;
        var val = $(this).data('val') || null;
        if (key == null || val == null) {
            Prompt(window['lang_operate_params_error'] || '排序数据值有误');
            return false;
        }

        // 选中
        $(this).addClass('sort-active').siblings('a').removeClass('sort-active');

        // 赋值并搜索
        var $parent = $(this).parents('form.form-validation-search');
        $parent.find('input[name="fp_order_by_key"]').val(key);
        $parent.find('input[name="fp_order_by_val"]').val(val);
        $parent.find('button[type="submit"]').trigger('click');
    });

    // 表格字显示段拖拽排序
    if ($('ul.form-table-fields-content-container').length > 0) {
        $('ul.form-table-fields-content-container').dragsort({ dragSelector: 'li', placeHolderTemplate: '<li class="drag-sort-dotted am-padding-vertical-sm am-width"></li>' });
    }

    // 表格字段选择确认
    $('.form-table-field-confirm-submit').on('click', function () {
        // 获取复选框选中的值
        var fields = [];
        $('.form-table-fields-list-container').find('input[name="form_field_checkbox_value"]').each(function (key, tmp) {
            fields.push({
                label: $(this).data('original-name'),
                key: $(this).val(),
                checked: $(this).is(':checked') ? 1 : 0
            });
        });

        // 是否有选择的数据
        if (fields.length <= 0) {
            Prompt(window['lang_before_choice_data_tips'] || '请先选择数据');
            return false;
        }

        // 表单唯一md5key
        var md5_key = $('.am-table-scrollable-horizontal').data('md5-key') || '';

        // ajax请求操作
        var $button = $(this);
        $button.button('loading');
        $.AMUI.progress.start();
        $.ajax({
            url: RequestUrlHandle($button.data('url')),
            type: 'POST',
            dataType: 'json',
            data: { "fields": fields, "md5_key": md5_key },
            success: function (result) {
                $.AMUI.progress.done();
                if (result.code == 0) {
                    Prompt(result.msg, 'success');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);

                } else {
                    $button.button('reset');
                    Prompt(result.msg);
                }
            },
            error: function (xhr, type) {
                $.AMUI.progress.done();
                $button.button('reset');
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });

    // 表格字段复选框操作 全选/反选
    $('.form-table-field-checkbox-submit').on('click', function () {
        var value = parseInt($(this).attr('data-value')) || 0;
        if (value == 1) {
            var not_checked_text = $(this).data('not-checked-text') || window['lang_select_all_name'] || '全选';
            $(this).parent().find('.checkbox-name').text(not_checked_text);
            $('.form-table-fields-list-container ul li').find('input[type="checkbox"]').uCheck('uncheck');
        } else {
            var checked_text = $(this).data('checked-text') || window['lang_select_reverse_name'] || '反选';
            $(this).parent().find('.checkbox-name').text(checked_text);
            $('.form-table-fields-list-container ul li').find('input[type="checkbox"]').uCheck('check');
        }
        $(this).attr('data-value', value == 1 ? 0 : 1);
    });

    // 表格复选框操作 全选/反选
    $('.form-table-operate-checkbox-submit').on('click', function () {
        var value = parseInt($(this).attr('data-value')) || 0;
        if (value == 1) {
            var not_checked_text = $(this).data('not-checked-text') || window['lang_select_all_name'] || '全选';
            $(this).text(not_checked_text);
            $('.form-table-operate-checkbox').find('input[type="checkbox"]').uCheck('uncheck');
        } else {
            var checked_text = $(this).data('checked-text') || window['lang_select_reverse_name'] || '反选';
            $(this).text(checked_text);
            $('.form-table-operate-checkbox').find('input[type="checkbox"]').uCheck('check');
        }
        $(this).attr('data-value', value == 1 ? 0 : 1);
    });

    // 表格公共搜索操作
    $(document).on('click', '.form-table-operate-top-search-submit', function () {
        // 表单数据
        var element = 'form.form-validation-search';
        var $form = $(element);
        var action = $form.attr('action');
        var data = GetFormVal(element, true);

        // 改变浏览器url地址
        var browser_url = action;
        var pv = '';
        for (var i in data) {
            if (data[i] != undefined && data[i] != '') {
                pv += i + '=' + encodeURIComponent(data[i]) + '&';
            }
        }
        if (pv != '') {
            var join = (browser_url.indexOf('?') >= 0) ? '&' : '?';
            browser_url += join + pv.substr(0, pv.length - 1);
        }
        history.pushState({}, '', browser_url);

        // 是否同步搜索、则直接刷新页面
        if (parseInt($(this).attr('data-is-sync-search')) == 1) {
            window.location.reload();
        } else {
            // ajax请求操作
            $.AMUI.progress.start();
            $.ajax({
                url: RequestUrlHandle(action),
                type: 'POST',
                dataType: "json",
                timeout: $form.data('timeout') || 60000,
                data: data,
                success: function (result) {
                    $.AMUI.progress.done();
                    if (typeof (result) == 'object' && result.code != 0) {
                        Prompt(result.msg);
                    } else {
                        // 截取数据并渲染
                        var arr = [
                            {
                                start: '<!-- form_table_data_content_start -->',
                                end: '<!-- form_table_data_content_end -->',
                                element: '.form-table-content .am-table-scrollable-horizontal > .am-table > tbody'
                            },
                            {
                                start: '<!-- form_table_no_data_start -->',
                                end: '<!-- form_table_no_data_end -->',
                                element: '.form-table-content .am-table-scrollable-horizontal > .form-table-no-data'
                            },
                            {
                                start: '<!-- form_table_data_page_start -->',
                                end: '<!-- form_table_data_page_end -->',
                                element: '.form-table-content > .form-table-operate-page'
                            }
                        ];
                        arr.forEach((v) => {
                            var start_number = result.indexOf(v.start);
                            var end_number = result.indexOf(v.end);
                            $(v.element).html(result.substring(start_number, end_number + v.end.length));
                        });

                        // 表格数据模块初始化
                        FormTableContentModuleInit();

                        // 回调方法
                        var back_function = 'FormTableDataListPageChangeBackEvent';
                        if (IsExitsFunction(back_function)) {
                            window[back_function](result);
                        }
                    }
                },
                error: function (xhr, type) {
                    $.AMUI.progress.done();
                    Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                }
            });
        }
        return false;
    });

    // 表格公共删除操作
    $(document).on('click', '.form-table-operate-top-delete-submit', function () {
        // 请求 url
        var url = $(this).data('url') || null;
        if (url == null) {
            Prompt(window['lang_operate_params_error'] || 'url参数有误');
            return false;
        }

        // form name 名称
        var form = $(this).data('form') || null;
        if (form == null) {
            Prompt(window['lang_operate_params_error'] || 'form参数有误');
            return false;
        }

        // 是否有选择的数据
        var values = FromTableCheckedValues(form, '.am-table-scrollable-horizontal');
        if (values.length <= 0) {
            Prompt(window['lang_before_choice_data_tips'] || '请先选中数据');
            return false;
        }

        // 提交字段名称|超时时间|标题|描述
        var key = $(this).data('key') || form;
        var timeout = $(this).data('timeout') || 60000;
        var title = $(this).data('confirm-title') || window['lang_reminder_title'] || '温馨提示';
        var msg = $(this).data('confirm-msg') || window['lang_delete_confirm_tips'] || '删除后不可恢复、确认操作吗？';

        // 再次确认
        AMUI.dialog.confirm({
            title: title,
            content: msg,
            onConfirm: function (result) {
                // 数组转对象
                var data = {};
                data[key] = {};
                for (var i in values) {
                    data[key][i] = values[i];
                }

                // ajax请求操作
                $.AMUI.progress.start();
                $.ajax({
                    url: RequestUrlHandle(url),
                    type: 'POST',
                    dataType: "json",
                    timeout: timeout,
                    data: data,
                    success: function (result) {
                        $.AMUI.progress.done();
                        if (result.code == 0) {
                            // 成功则删除数据列表
                            Prompt(result.msg, 'success');
                            for (var i in values) {
                                $('#data-list-' + values[i]).remove();
                            }
                        } else {
                            Prompt(result.msg);
                        }
                    },
                    error: function (xhr, type) {
                        $.AMUI.progress.done();
                        Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                    }
                });
            },
            onCancel: function () { }
        });
    });

    // 表格公共excel导出操作
    $(document).on('click', '.form-table-operate-top-export-excel-submit', function () {
        // 表单基础
        var form_name = 'form.form-validation-search';
        var $form = $(form_name);
        var request_value = $form.attr('request-value') || window.location.href;
        request_value = UrlFieldReplace('form_table_is_export_excel', null, request_value);

        // 拼接条件
        var pv = 'form_table_is_export_excel=1&';

        // 是否存在数据选择（复选框列+id数据列）
        var id_form_name = null;
        if ($form.find('.form-table-search-item-head-value-form_checkbox_value').length > 0 && $form.find('.form-table-search-item-head-value-id').length > 0) {
            // 是否有选择的数据
            var values = FromTableCheckedValues('form_checkbox_value', '.am-table-scrollable-horizontal');
            if (values.length > 0) {
                id_form_name = $form.find('.form-table-search-item-head-value-id input').attr('name') || null;
                if (id_form_name != null) {
                    pv += id_form_name + '=' + encodeURIComponent(values.join(',')) + '&';
                }
            }
        }

        // 拼接参数
        var params = GetFormVal(form_name, true);
        for (var i in params) {
            if (params[i] != undefined && params[i] != '' && i != id_form_name) {
                pv += i + '=' + encodeURIComponent(params[i]) + '&';
            }
        }
        var join = (request_value.indexOf('?') >= 0) ? '&' : '?';
        request_value += join + pv.substr(0, pv.length - 1);

        window.open(request_value);
    });

    // 表格公共pdf导出和打印操作
    $(document).on('click', '.form-table-operate-top-data-print-submit,.common-print-submit', function () {
        DataPrintHandle($(this).data('is-pdf'));
    });

    // 表格内部表格伸缩事件
    $(document).on('click', '.form-inside-stretch-submit > a', function () {
        var $container = $(this).parents('.form-inside-table-container');
        var $layer = $container.find('.form-inside-table-layer');
        var open_text = $(this).data('open-text') || null;
        var close_text = $(this).data('close-text') || null;
        if ($layer.hasClass('form-inside-table-layer-auto')) {
            $layer.removeClass('form-inside-table-layer-auto');
            $(this).removeClass('am-icon-angle-double-up').addClass('am-icon-angle-double-down');
            if (open_text != null) {
                $(this).text(' ' + open_text);
            }
        } else {
            $layer.addClass('form-inside-table-layer-auto');
            $(this).addClass('am-icon-angle-double-up').removeClass('am-icon-angle-double-down');
            if (close_text != null) {
                $(this).text(' ' + close_text);
            }
        }
    });

    // 页面加载loading
    if ($('.am-page-loading').length > 0) {
        $('.am-page-loading').fadeOut(400);
    }

    // 全屏操作
    $(document).on('click', '.fullscreen-event', function () {
        var status = $(this).attr('data-status') || 0;
        if (status == 0) {
            if (FullscreenOpen()) {
                $(this).find('.fullscreen-text').text($(this).attr('data-fulltext-exit') || window['lang_fullscreen_exit_name'] || '退出全屏');
            }
        } else {
            if (FullscreenExit()) {
                $(this).find('.fullscreen-text').text($(this).attr('data-fulltext-open') || window['lang_fullscreen_open_name'] || '开启全屏');
            }
        }
        $(this).attr('data-status', status == 0 ? 1 : 0);
        $(this).attr('data-status-y', status);
    });

    // esc退出全屏事件
    document.addEventListener("fullscreenchange", function (e) {
        FullscreenEscEvent();
    });
    document.addEventListener("mozfullscreenchange", function (e) {
        FullscreenEscEvent();
    });
    document.addEventListener("webkitfullscreenchange", function (e) {
        FullscreenEscEvent();
    });
    document.addEventListener("msfullscreenchange", function (e) {
        FullscreenEscEvent();
    });


    // 下拉选择组件初始化
    SelectChosenInit();

    // 多选插件 空内容失去焦点验证bug兼容处理
    $(document).on('blur', 'ul.chosen-choices .search-field, div.chosen-select .chosen-search', function () {
        if ($(this).parent().find('li').length <= 1 || $(this).parent().parent().find('.chosen-default').length >= 1) {
            $(this).parent().parent().prev().trigger('blur');
        }
    });
    // 多选插件分组支持组单选
    $(document).on('click', '.chosen-container-multi .chosen-results li', function () {
        var $chosen = $(this).parents('.chosen-container');
        var is_group_single = parseInt($chosen.prev().data('group-single') || 0);
        if (is_group_single == 1 && !$(this).hasClass('group-result')) {
            var index = $(this).index();
            var $parent = $(this).parent();
            var count = $parent.find('li').length;
            var arr = [];
            // 获取前面选中的数据
            var temp_index = index - 1;
            while (temp_index != 0) {
                var $li = $parent.find('li').eq(temp_index);
                if ($li.hasClass('group-result')) {
                    break;
                }
                if ($li.hasClass('result-selected')) {
                    var value = $li.attr('data-option-array-index') || null;
                    if (value != null) {
                        arr.push(value);
                    }
                }
                temp_index--;
            }
            // 获取后面选中的数据
            temp_index = index + 1;
            while (temp_index < count) {
                var $li = $parent.find('li').eq(temp_index);
                if ($li.hasClass('group-result')) {
                    break;
                }
                if ($li.hasClass('result-selected')) {
                    var value = $li.attr('data-option-array-index') || null;
                    if (value != null) {
                        arr.push(value);
                    }
                }
                temp_index++;
            }
            if (arr.length > 0) {
                $chosen.find('.chosen-choices li').each(function (k, v) {
                    var $a = $(this).find('a.search-choice-close');
                    var value = $a.attr('data-option-array-index');
                    if (arr.indexOf(value) != -1) {
                        $a.trigger('click');
                    }
                });
            }
        }
    });

    /**
     * 删除数据列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T14:22:39+0800
     * @param    {[int] 	[data-id] 	[数据id]}
     * @param    {[string] 	[data-url] 	[请求地址]}
     */
    $(document).on('click', '.submit-delete', function () {
        ConfirmDataDelete($(this));
    });

    /**
     * 公共数据状态操作
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T14:22:39+0800
     * @param    {[int] 	[data-id] 	[数据id]}
     * @param    {[int] 	[data-state][状态值]}
     * @param    {[string] 	[data-url] 	[请求地址]}
     */
    $(document).on('click', '.submit-state', function () {
        if ($(this).attr('data-animate') !== '0') {
            $(this).find('i').animate({ "font-size": "28px" }, 200);
            setTimeout(() => {
                $(this).find('i').animate({ "font-size": "24px" }, 200);
            }, 200);
        }
        // 获取参数
        var $this = $(this);
        var id = $this.attr('data-id');
        var state = ($this.attr('data-state') == 1) ? 0 : 1;
        var url = $this.attr('data-url');
        var field = $this.attr('data-field') || '';
        var is_update_status = $this.attr('data-is-update-status') || 0;
        var is_progress = ($this.attr('data-is-progress') == undefined || parseInt($this.attr('data-is-progress') || 0) == 1) ? 1 : 0;
        var is_loading = parseInt($this.attr('data-is-loading') || 0);
        var loading_msg = $this.attr('data-loading-msg') || window['lang_request_handle_loading_tips'] || '正在处理中、请稍候...';
        if (id == undefined || url == undefined) {
            Prompt(window['lang_params_error_tips'] || '参数配置有误');
            return false;
        }

        // 弹层加载
        if (is_loading == 1) {
            AMUI.dialog.loading({ title: loading_msg });
        }

        // 请求更新数据
        if (is_progress == 1) {
            $.AMUI.progress.start();
        }
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'POST',
            dataType: 'json',
            timeout: $this.attr('data-timeout') || 60000,
            data: { "id": id, "state": state, "field": field },
            success: function (result) {
                if (is_loading == 1) {
                    AMUI.dialog.loading('close');
                }
                if (is_progress == 1) {
                    $.AMUI.progress.done();
                }
                if (result.code == 0) {
                    Prompt(result.msg, 'success');

                    // 成功则更新数据样式
                    if ($this.hasClass('am-success')) {
                        $this.removeClass('am-success');
                        $this.addClass('am-default');
                        if (is_update_status == 1) {
                            if ($('#data-list-' + id).length > 0) {
                                $('#data-list-' + id).addClass('am-active');
                            }
                        }
                    } else {
                        $this.removeClass('am-default');
                        $this.addClass('am-success');
                        if (is_update_status == 1) {
                            if ($('#data-list-' + id).length > 0) {
                                $('#data-list-' + id).removeClass('am-active');
                            }
                        }
                    }
                    $this.attr('data-state', state);
                } else {
                    Prompt(result.msg);
                }
            },
            error: function (xhr, type) {
                if (is_loading == 1) {
                    AMUI.dialog.loading('close');
                }
                if (is_progress == 1) {
                    $.AMUI.progress.done();
                }
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });

    /**
     * 公共编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T13:53:13+0800
     */
    $(document).on('click', '.submit-edit', function () {
        // 窗口标签
        var tag = $(this).attr('data-tag') || null;
        if (tag == null) {
            var modal = $(this).attr('data-am-modal') || null;
            if (modal != null) {
                modal = JsonStringToJsonObject(modal);
                if ((modal.target || null) != null) {
                    tag = modal.target;
                }
            }
        }
        if (tag == null) {
            tag = '#data-save-win';
        } else {
            if (tag.indexOf('#') == -1) {
                tag = '#' + tag;
            }
        }

        // 更改窗口名称
        if ($(tag).length > 0) {
            // 是否modal窗口
            var $title = $(tag).find('>.am-modal-dialog>.am-modal-hd>span');
            if ($title.length == 0) {
                $title = $(tag).find('.am-popup-title');
            }
            $title.text($title.attr('data-edit-title'));
        }

        // 数据
        var data = JSON.parse(decodeURIComponent($(this).attr('data-json')));

        // 初始数据
        FormDataFill(data, tag);

        // 额外数据处理
        FunSaveWinAdditional(data, 'edit', tag);
    });

    /**
     * 公共无限节点 - 双击编辑
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-10-16
     * @desc    description
     */
    $(document).on('dblclick', '#tree table.am-table tr', function () {
        if ($(this).find('td.type-operate .submit-edit').length > 0) {
            $(this).find('td.type-operate .submit-edit').trigger('click');
        }
    });

    /**
     * 公共无限节点 - 新子节点
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:12:10+0800
     */
    $(document).on('click', '#tree .tree-submit-add-node', function () {
        // 清空表单数据
        TreeFormInit();

        // 父节点赋值
        var id = parseInt($(this).attr('data-id')) || 0;
        $($(this).parents('#tree').data('popup-tag') || '' + popup_tag + '').find('input[name="pid"], select[name="pid"]').val(id);

        // 多选插件事件更新
        if ($('select.chosen-select').length > 0) {
            SelectChosenInit();
        }
    });

    /**
     * 公共无限节点
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:12:10+0800
     */
    $(document).on('click', '#tree .tree-submit', function () {
        var id = parseInt($(this).attr('data-id')) || 0;
        // 状态
        if ($('.tree-pid-' + id).length > 0) {
            if ($(this).hasClass('icon-arrow-right')) {
                $(this).removeClass('icon-arrow-right');
                $(this).addClass('icon-arrow-down');
                $('.tree-pid-' + id).removeClass('am-hide');
                $('.tree-pid-' + id).each(function (k, v) {
                    if ($('.tree-pid-' + $(this).attr('data-value')).length > 0) {
                        $(this).find('td .tree-submit').removeClass('icon-arrow-right');
                        $(this).find('td .tree-submit').addClass('icon-arrow-down');
                    }
                });
            } else {
                $(this).removeClass('icon-arrow-down');
                $(this).addClass('icon-arrow-right');
                $('.tree-pid-' + id).addClass('am-hide');
                $('.tree-pid-' + id).each(function (k, v) {
                    if ($('.tree-pid-' + $(this).attr('data-value')).length > 0) {
                        $(this).find('td .tree-submit').removeClass('icon-arrow-down');
                        $(this).find('td .tree-submit').addClass('icon-arrow-right');
                    }
                });
            }
        } else {
            var url = $(this).parents('#tree').data('node-url') || '';
            var level = parseInt($(this).parents('tr').attr('data-level') || 0) + 1;
            var is_delete_all = parseInt($(this).attr('data-is-delete-all')) || 0;
            if (id > 0 && url != '') {
                Tree(id, url, level, is_delete_all);
            } else {
                Prompt(window['lang_operate_params_error'] || '参数有误');
            }
        }
    });

    /**
     * 公共无限节点新增按钮处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:11:34+0800
     */
    $('.tree-submit-add').on('click', function () {
        TreeFormInit();
    });

    /**
     * 公共无限节点开关状态
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:11:34+0800
     */
    $(document).on('click', '#tree table tr td .am-switch', function () {
        var $this = $(this);
        var $tr = $(this).parents('tr');
        var $td = $(this).parent('td');
        // 原始json数据
        var json_old = JSON.parse(decodeURIComponent($tr.find('.submit-edit').attr('data-json')));
        var head_col = TreeHeadConfig();
        var url = $('#tree').attr('data-status-url');
        var is_loading = parseInt($('#tree').attr('data-status-is-loading') || 0);
        var loading_msg = $('#tree').attr('data-status-loading-msg') || window['lang_request_handle_loading_tips'] || '正在处理中、请稍候...';;

        // 弹层加载
        if (is_loading == 1) {
            AMUI.dialog.loading({ title: loading_msg });
        }
        // 请求数据
        var status_field = $td.data('key');
        var status_value = json_old[status_field] == 1 ? 0 : 1;
        var data = {
            id: $tr.data('value'),
            field: status_field,
            state: status_value,
        };
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (result) {
                setTimeout(() => {
                    $this.find('input').switch().toggleSwitchOpen();
                }, 1000);
                if (is_loading == 1) {
                    setTimeout(function () {
                        AMUI.dialog.loading('close');
                    }, 1000);
                }
                $.AMUI.progress.done();
                if (result.code == 0) {
                    Prompt(result.msg, 'success');
                    // 当前表单是否成功后刷新页面
                    if ($('form.form-validation').attr('request-type') == 'ajax-reload') {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        if (head_col.length > 0) {
                            for (var i = 0; i < head_col.length; i++) {
                                if (head_col[i].value_style && head_col[i].value_style.value_style_key && head_col[i].value_style.value_style_key == status_field && head_col[i].value_style.value_style_key_status) {
                                    var style = '';
                                    json_old[head_col[i].value_style.value_style_key] = json_old[head_col[i].value_style.value_style_key] == '1' ? '0' : '1';
                                    if (json_old[head_col[i].value_style.value_style_key] == head_col[i].value_style.value_style_key_status) {
                                        style = head_col[i].value_style.style;
                                    }
                                    switch (head_col[i].type) {
                                        // 图标
                                        case 'img':
                                            break;
                                        // icon
                                        case 'icon':
                                            if ((json_old[head_col[i].field] || null) != null) {
                                                $tr.find('td[data-key="' + head_col[i].field + '"]').empty();
                                                $tr.find('td[data-key="' + head_col[i].field + '"]').append('<i class="iconfont ' + json_old[head_col[i].field] + '" style="' + style + '"></i>');
                                            }
                                            break;
                                        // 状态
                                        case 'status':
                                            break;
                                        default:
                                            $tr.find('td[data-key="' + head_col[i].field + '"] span>span').remove();
                                            $tr.find('td[data-key="' + head_col[i].field + '"] span').append('<span style="' + style + '">' + json_old[head_col[i].field] + '</span>');
                                    }
                                }
                            }
                        }

                        // tr状态class
                        var status_field_arr = ['is_enable', 'is_show'];
                        if (status_field_arr.indexOf(status_field) != -1) {
                            if (status_value == 1) {
                                $tr.removeClass('am-active');
                            } else {
                                $tr.addClass('am-active');
                            }
                        }

                        // 属性json数据更新
                        $tr.find('.submit-edit').attr('data-json', encodeURIComponent(JSON.stringify(json_old)));
                    }
                } else {
                    if (head_col.length > 0) {
                        for (var i = 0; i < head_col.length; i++) {
                            var style = '';
                            switch (head_col[i].type) {
                                // 状态
                                case 'status':
                                    $this.find('input').switch().toggleSwitch(json_old[head_col[i].field] == 1 ? true : false);
                                    break;
                            }
                        }
                    }
                    Prompt(result.msg);
                }
            },
            error: function (xhr, type) {
                if (is_loading == 1) {
                    setTimeout(function () {
                        AMUI.dialog.loading('close');
                    }, 1000);
                }
                $.AMUI.progress.done();
                setTimeout(() => {
                    $this.find('input').switch().toggleSwitchOpen();
                }, 1000);
                if (head_col.length > 0) {
                    for (var i = 0; i < head_col.length; i++) {
                        var style = '';
                        switch (head_col[i].type) {
                            // 状态
                            case 'status':
                                $this.find('input').switch().toggleSwitch(json_old[head_col[i].field] == 1 ? true : false);
                                break;
                        }
                    }
                }
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });

    /**
     * 卡片组件公共无限节点新增按钮处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:11:34+0800
     */
    $(document).on('click', '.card-submit-add', function () {
        CardFormInit();
    });

    /**
     * 公共数据ajax操作
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T14:22:39+0800
     * @param    {[int] 	[data-id] 	[数据id]}
     * @param    {[int] 	[data-view] [完成操作（delete删除数据, reload刷新页面, fun方法回调(data-value)]}
     * @param    {[string] 	[data-url] 	[请求地址]}
     */
    $(document).on('click', '.submit-ajax', function () {
        var is_confirm = $(this).attr('data-is-confirm');
        if (is_confirm == undefined || is_confirm == 1) {
            ConfirmNetworkAjax($(this));
        } else {
            AjaxRequest($(this));
        }
    });

    /**
     * url事件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-10-09
     * @desc    description
     * @param    {[string]    [data-url]  [请求地址]}
     * @param    {[string]    [data-title][提示标题]}
     * @param    {[string]    [data-msg]  [提示信息，不配置则不提示]}
     */
    $(document).on('click', '.submit-url', function () {
        var url = $(this).attr('data-url') || $(this).attr('href') || $(this).attr('src') || null;
        if (url != null) {
            var msg = $(this).attr('data-msg') || null;
            if (msg == null) {
                window.location.href = url;
            } else {
                AMUI.dialog.confirm({
                    title: $(this).attr('data-title') || window['lang_reminder_title'] || '温馨提示',
                    content: msg,
                    onConfirm: function (result) {
                        window.location.href = url;
                    }
                });
            }
        }
    });

    // 地址初始化
    RegionLinkageInit();
    // 地址编号搜索
    $(document).on('click', '.region-linkage-code button', function () {
        var code = $(this).parents('.region-linkage-code').find('input').val() || null;
        if (code == null) {
            Prompt(window['lang_input_empty_tips'] || '请输入数据');
            return false;
        }
        var $this = $(this);
        var $parent = $this.parents('.region-linkage');
        $this.attr('disabled', true);
        $.ajax({
            url: RequestUrlHandle($parent.data('code-url')),
            type: 'POST',
            dataType: 'json',
            timeout: 30000,
            data: { code: code },
            success: function (result) {
                $this.attr('disabled', false);
                if (result.code == 0) {
                    // 更新选中值
                    $parent.find('input[name="province"]').val(result.data.province.id);
                    $parent.find('input[name="city"]').val(result.data.city.id);
                    $parent.find('input[name="county"]').val(result.data.county.id);
                    var address = result.data.province.name + ((result.data.city.name || null) == null ? '' : '-' + result.data.city.name) + ((result.data.county.name || null) == null ? '' : '-' + result.data.county.name);
                    $parent.find('input[name="province_city_county"]').val(address);
                    // 地址初始化
                    RegionLinkageInit();
                    $parent.find('.am-cascader-dropdown').removeClass('am-active');
                    $parent.find('.am-cascader .am-input-suffix i').removeClass('is-reverse');
                    if (result.data.city.name.length > 0) {
                        $parent.find('.am-cascader-menu:eq(1)').addClass('am-active');
                    } else {
                        $parent.find('.am-cascader-menu:eq(1)').removeClass('am-active');
                    }
                    if (result.data.county.name.length > 0) {
                        $parent.find('.am-cascader-menu:eq(2)').addClass('am-active');
                    } else {
                        $parent.find('.am-cascader-menu:eq(2)').removeClass('am-active');
                    }
                    Prompt(result.msg, 'success');
                } else {
                    Prompt(result.msg);
                }
            },
            error: function (xhr, type) {
                $this.attr('disabled', false);
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
            }
        });
    });
    $(document).on('keydown', '.region-linkage-code input', function () {
        if (event.keyCode == 13) {
            $(this).parents('.region-linkage-code').find('button').trigger('click');
            return false;
        }
    });

    // 根据字符串地址获取坐标位置
    $(document).on('click', '#map-location-submit', function () {
        // 地址信息
        var region = $('.region-linkage input[name="province_city_county"]').val();
        var detail = $('#form-address').val();
        if (region.length <= 0 || detail.length <= 0) {
            Prompt(window['lang_address_data_empty_tips'] || '地址为空');
            return false;
        }
        var arr = region.split('-');
        var province = arr[0];
        var address = arr.join('') + detail;

        // 地图类型
        switch (__load_map_type__) {
            // 百度地图
            case 'baidu':
                // 创建地址解析器实例
                var geo = new BMap.Geocoder();
                // 将地址解析结果显示在地图上,并调整地图视野
                geo.getPoint(address, function (point) {
                    if (point) {
                        MapInit(point.lng, point.lat);
                    } else {
                        Prompt(window['lang_map_address_analysis_tips'] || '您选择地址没有解析到结果！');
                    }
                }, province);
                break;

            // 高德地图
            case 'amap':
                AMap.plugin('AMap.Geocoder', function () {
                    var geo = new AMap.Geocoder()
                    geo.getLocation(address, function (status, result) {
                        if (status === 'complete' && result.geocodes.length) {
                            var lnglat = result.geocodes[0].location;
                            MapInit(lnglat.lng, lnglat.lat);
                        } else {
                            Prompt(window['lang_map_address_analysis_tips'] || '您选择地址没有解析到结果！');
                        }
                    });
                });
                break;

            // 腾讯地图
            case 'tencent':
                var geo = new TMap.service.Geocoder();
                geo.getLocation({ address: address }).then((result) => {
                    var lnglat = result.result.location;
                    MapInit(lnglat.lng, lnglat.lat);
                });
                break;

            // 天地图
            case 'tianditu':
                var geo = new T.Geocoder();
                geo.getPoint(address, function (result) {
                    if (result.getStatus() == 0) {
                        var point = result.getLocationPoint();
                        MapInit(point.lng, point.lat);
                    } else {
                        Prompt(point.getMsg());
                    }
                });
                break;

            // 默认
            default:
                Prompt((window['lang_map_type_not_exist_tips'] || '该地图功能未定义') + '(' + __load_map_type__ + ')');
        }
    });

    // 图片上传
    $(document).on('change', '.images-file-event, .file-event', function () {
        // 显示选择的图片名称
        var fileNames = '';
        $.each(this.files, function () {
            fileNames += '<span class="am-font-weight">' + this.name + '</span> <span class="am-color-999">(' + AnnexSizeToUnit(this.size) + ')</span>';
        });
        $($(this).attr('data-tips-tag')).html(fileNames);

        // 触发配合显示input地址事件
        var input_tag = $(this).attr('data-choice-one-to') || null;
        if (input_tag != null) {
            $(input_tag).trigger('blur');
        }
    });

    // 图片预览
    if ($('.images-file-event').length > 0) {
        ImageFileUploadShow('.images-file-event');
    }

    // 视频上传
    $(document).on('change', '.video-file-event', function () {
        // 显示选择的图片名称
        var fileNames = '';
        $.each(this.files, function () {
            fileNames += '<span class="am-badge">' + this.name + '</span> ';
        });
        $($(this).attr('data-tips-tag')).html(fileNames);

        // 触发配合显示input地址事件
        var input_tag = $(this).attr('data-choice-one-to') || null;
        if (input_tag != null) {
            $(input_tag).trigger('blur');
        }
    });

    // 视频预览
    if ($('.video-file-event').length > 0) {
        VideoFileUploadShow('.video-file-event');
    }

    // 监听多图上传和上传附件组件的插入动作
    if (typeof (upload_editor) == 'object') {
        upload_editor.ready(function () {
            // 图片上传动作
            upload_editor.addListener("beforeInsertImage", function (t, result) {
                CommonFormUploadEditorDataViewHandle(result, 'images');
            });

            // 视频上传
            upload_editor.addListener("beforeInsertVideo", function (t, result) {
                CommonFormUploadEditorDataViewHandle(result, 'video');
            });

            // 文件上传
            upload_editor.addListener("beforeInsertFile", function (t, result) {
                CommonFormUploadEditorDataViewHandle(result, 'file');
            });
        });
    }

    // 打开编辑器插件
    $(document).on('click', '.plug-file-upload-submit, .plug-file-upload-pure-submit', function () {
        // 是否查看文件操作、则阻止往下执行
        if ($(this).find('>i.icon-eye').length > 0) {
            return false;
        }

        // 组件是否初始化
        if (typeof (upload_editor) != 'object') {
            Prompt(window['lang_assembly_not_init_tips'] || '组件未初始化');
            return false;
        }

        // 容器配置
        var $view_tag = (($(this).attr('data-view-tag') || null) == null) ? null : $($(this).attr('data-view-tag'));
        if($view_tag != null) {
            // 是否限制数量
            var max_number = $view_tag.attr('data-max-number') || 0;
            if (max_number > 0 && $view_tag.find('li').length > 0) {
                var count = 0;
                var remove_default_images = $view_tag.data('remove-default-images') || null;
                $view_tag.find('li').each(function (k, v) {
                    // 默认图片项不参与数量计算、li是按钮则不参与计算
                    if ($(this).find('img').attr('src') != remove_default_images && !$(this).hasClass('plug-file-upload-submit')) {
                        count++;
                    }
                });
                if (count > max_number) {
                    var temp_msg = window['lang_upload_annex_max_tips'] || '最多上传{value}个附件';
                    Prompt(temp_msg.replace('{value}', max_number));
                    return false;
                }
            }

            // 是否指定form名称
            if (($view_tag.attr('data-form-name') || null) == null) {
                Prompt(window['lang_not_specified_form_name_tips'] || '未指定表单name名称');
                return false;
            }

            // 加载类型
            var dialog_type = $view_tag.attr('data-dialog-type');
        } else {
            var dialog_type = $(this).attr('data-dialog-type');
        }

        // 加载组件类型
        var form_action = 'uploadimage';
        switch (dialog_type) {
            // 视频
            case 'video':
                dialog_type = 'insertvideo';
                form_action = 'uploadvideo';
                break;

            // 图片
            case 'images':
                dialog_type = 'insertimage';
                form_action = 'uploadimage';
                break;

            // 文件
            case 'file':
                dialog_type = 'attachment';
                form_action = 'uploadfile';
                break;

            default :
                Prompt(window['lang_not_specified_assembly_tips'] || '未指定加载组件');
                return false;
        }

        // 容易配置判断
        if($view_tag != null) {
            // 是否单个上传
            if (parseInt($view_tag.data('is-single') || 0) == 1) {
                var $form = $('form.form-validation-common-upload-editor-single');
                $form.find('input[name="action"]').val(form_action);
                $form.find('input[name="upfile"]').attr('accept', form_action == 'uploadimage' ? 'image/*' : '').trigger('click');
                return false;
            }

            // 赋值参数
            $('body').attr('view-tag', $(this).attr('data-view-tag'));
        }

        // 打开组件
        var dialog = upload_editor.getDialog(dialog_type);
        dialog.render();
        dialog.open();
    });

    // 公共单个文件上传表单
    $('form.form-validation-common-upload-editor-single input[name="upfile"]').on('change', function () {
        $(this).parents('form').find('button[type="submit"]').trigger('click');
    });

    // 删除容器中的内容（排除上传和查看icon）
    $(document).on('click', '.plug-file-upload-view li i:not(.icon-upload-add, .icon-eye)', function () {
        // 容器
        var $tag = $(this).parents('ul.plug-file-upload-view');
        // 是否上传和显示组合
        var $compose = $tag.find('li.plug-file-upload-submit');
        if ($compose.length > 0) {
            $compose.html('<i class="iconfont icon-upload-add"></i>');
        } else {
            var $parent = $(this).parent();
            // 删除默认图片
            var remove_default_images = $tag.attr('data-remove-default-images');
            if (remove_default_images === undefined) {
                // 删除数据、延迟10毫秒删除、避免业务层页面有其他操作
                setTimeout(function () {
                    $parent.remove();
                }, 10);

                // 数据处理
                var max_number = parseInt($tag.attr('data-max-number') || 0);
                if (max_number > 0) {
                    if ($tag.find('li').length < max_number) {
                        $('.plug-file-upload-submit').show();
                    }
                }
            } else {
                $parent.find('input').val('');
                $parent.find('img').attr('src', remove_default_images || '');
                $parent.find('i').remove();
            }
        }
        return false;
    });

    // 上传文件组件预览
    $(document).on('click', '.plug-file-upload-view li i.icon-eye', function () {
        var $parent = $(this).parent();
        var $tag = $(this).parents('ul.plug-file-upload-view');
        var type = $tag.attr('data-dialog-type') || null;
        var value = $parent.find('>img').attr('src') || null;
        if (value != null) {
            type = 'images';
        } else {
            value = $parent.find('>video').attr('src') || null;
            if (value != null) {
                type = 'video';
            }
        }
        AnnexView(type, value, $(this));
    });

    // 公共文件预览
    $(document).on('click', '.common-annex-view-event', function () {
        var type = $(this).attr('data-type') || 'images';
        var value = $(this).attr('data-value') || $(this).attr('src') || null;
        AnnexView(type, value, $(this));
    });

    // 富文本图片预览
    $(document).on('click', '.richtext img', function () {
        // 存在跳转则不执行预览
        if ($(this).parent().prop('tagName') != 'A') {
            AnnexView('images', $(this).attr('src'), $(this));
        }
    });

    // 文件下载
    $(document).on('click', '.common-annex-download-event', function () {
        // 文件信息
        var value = $(this).attr('data-value') || $(this).attr('src') || null;
        if (value == null) {
            Prompt(window['lang_data_error'] || '数据有误');
            return false;
        }
        // 名称及后缀、没有指定则获取最后一次出现的斜杠后面的的内容作为文件名称
        var name = $(this).attr('data-name') || null;
        if (name == null) {
            var last = value.lastIndexOf('/');
            name = (last == -1) ? value : value.substr(last + 1);
        }

        var $this = $(this);
        $this.button('loading');

        // 异步
        var xhr = new XMLHttpRequest();
        xhr.open('GET', value, true);
        // blob 类型
        xhr.responseType = 'blob';
        xhr.onload = function () {
            if (xhr.status != 200) {
                Prompt(window['lang_operate_download_fail_tips'] || '下载失败！');
                $this.button('reset');
                return false;
            }

            if (window.navigator.msSaveOrOpenBlob) {
                // IE
                navigator.msSaveBlob(xhr.response, filename);
            } else {
                var url = window.URL.createObjectURL(xhr.response);
                var a = document.createElement('a');
                a.setAttribute('href', url);
                a.setAttribute('target', '_blank');
                a.setAttribute('download', name);
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
            $this.button('reset');
        };
        xhr.send();
    });

    /* 搜索切换 */
    var $more_where = $('.more-where');
    $more_submit = $('.more-submit');
    $more_submit.find('input[name="is_more"]').change(function () {
        if ($more_submit.find('i').hasClass('am-icon-angle-down')) {
            $more_submit.find('i').removeClass('am-icon-angle-down');
            $more_submit.find('i').addClass('am-icon-angle-up');
        } else {
            $more_submit.find('i').addClass('am-icon-angle-down');
            $more_submit.find('i').removeClass('am-icon-angle-up');
        }

        if ($more_submit.find('input[name="is_more"]:checked').val() == undefined) {
            $more_where.addClass('none');
        } else {
            $more_where.removeClass('none');
        }
    });

    // 加载 loading popup 弹层
    $(document).on('click', '.submit-popup', function () {
        var url = $(this).attr('data-url') || null;
        if (url == null) {
            Prompt(window['lang_operate_params_error'] || 'url未配置');
            return false;
        }

        // 基础参数
        var title = $(this).attr('data-title') || '';
        var full = parseInt($(this).attr('data-full')) || 0;
        var full_max = parseInt($(this).attr('data-full-max')) || 0;
        var full_max_size = $(this).attr('data-full-max-size') || '';
        var class_tag = $(this).attr('data-class') || '';
        var on_open = $(this).attr('data-on-open') || '';
        var on_close = $(this).attr('data-on-close') || '';
        var offcanvas = $(this).attr('data-offcanvas') || '';

        // 调用弹窗方法
        ModalLoad(url, title, class_tag, full, full_max, full_max_size, on_open, on_close, offcanvas);
    });

    // 加载 loading modal 弹层
    $(document).on('click', '.submit-modal', function () {
        var url = $(this).data('url') || null;
        if (url == null) {
            Prompt(window['lang_operate_params_error'] || 'url未配置');
            return false;
        }
        var title = $(this).attr('data-title') || '';

        // 宽高
        var config = {};
        var width = parseInt($(this).attr('data-width') || 0);
        if (width > 0) {
            config['width'] = width;
        }
        var height = parseInt($(this).attr('data-height') || 0);
        if (height > 0) {
            config['height'] = height;
        }
        // 是否点击遮罩层时关闭
        var is_close_via_dimmer = $(this).attr('data-close-via-dimmer');
        config['closeViaDimmer'] = (is_close_via_dimmer == undefined || is_close_via_dimmer == 1);

        // 监听方法
        var on_open = $(this).attr('data-on-open') || '';
        var on_close = $(this).attr('data-on-close') || '';

        // 调用弹窗
        AlertLoad({url: url, title: title}, config, on_open, on_close);
    });

    // 地图弹窗
    $(document).on('click', '.submit-map-popup', function () {
        // 参数
        var lng = $(this).data('lng') || null;
        var lat = $(this).data('lat') || null;
        if (lng == null || lat == null) {
            Prompt(window['lang_map_coordinate_tips'] || '坐标有误');
            return false;
        }

        // 基础参数
        var title = $(this).data('title') || '';
        var class_tag = $(this).data('class') || '';
        var full = parseInt($(this).data('full')) || 0;
        var full_max = parseInt($(this).data('full-max')) || 0;
        var full_max_size = $(this).data('full-max-size') || '';

        // 调用弹窗方法
        var url = UrlFieldReplace('lat', lat, UrlFieldReplace('lng', lng, __map_view_url__));
        ModalLoad(url, title, class_tag, full, full_max, full_max_size);
    });

    // 弹窗拖拽
    $(document).on('mousedown', '.am-popup .am-popup-hd, .am-modal-dialog .am-modal-hd', function (event) {
        var is_move = true;
        var $popup = $(this).parents('.am-popup, .am-modal-dialog');
        if (!$popup.hasClass('am-popup-offcanvas-right') && !$popup.hasClass('am-popup-offcanvas-left')) {
            var width = $popup.width();
            var win_width = $(window).width();
            var win_height = $(window).height();
            var abs_x = event.pageX - $popup.offset().left;
            var abs_y = event.pageY - $popup.offset().top;
            $(document).mousemove(function (event) {
                if (is_move) {
                    var scroll_top = $(document).scrollTop();
                    var left = event.pageX - abs_x;
                    var left_max = (0 - width);
                    if (left < left_max) {
                        left = left_max + 20;
                    }
                    if (left > win_width) {
                        left = win_width - 20;
                    }
                    var top = event.pageY - abs_y;
                    if (top > win_height + scroll_top) {
                        top = (win_height + scroll_top) - 20;
                    }
                    top -= scroll_top;
                    if (top < 0) {
                        top = 0;
                    }
                    $popup.css({ 'position': 'fixed', 'left': left, 'top': top, 'margin': 0 });
                };
            }).mouseup(function (event) {
                is_move = false;
            }).mouseleave(function () {
                is_move = false;
            });
        }
    });

    // 关闭窗口
    $(document).on('click', '.window-close-event', function () {
        // 根据环境判断处理
        var env = MobileBrowserEnvironment();
        // 是否微信环境存在微信sdk
        if (env == 'weixin' && typeof (wx) == 'object') {
            wx.closeWindow();
            // 是否支付宝环境存在支付宝sdk
        } else if (env == 'alipay' && typeof (AlipayJSBridge) == 'object') {
            AlipayJSBridge.call('exitApp');
        } else {
            // web端
            if (confirm($(this).data('msg') || window['lang_window_close_confirm_tips'] || '您确定要关闭本页吗？')) {
                var user_agent = navigator.userAgent;
                if (user_agent.indexOf('Firefox') != -1 || user_agent.indexOf('Chrome') != -1) {
                    location.href = 'about:blank';
                } else {
                    window.opener = null;
                    window.open('', '_self');
                }
                window.close();
            }
        }
    });

    // dropdown组件hover显示
    var dropdown_timer = null;
    $(document).on('mouseenter', '.am-dropdown .am-dropdown-toggle', function () {
        clearTimeout(dropdown_timer);
        if ($(window).width() >= 641) {
            $('body .am-dropdown').each(function (k, v) {
                var config = JsonStringToJsonObject($(this).attr('data-am-dropdown')) || null;
                if (config != null && (config.trigger || null) == 'hover') {
                    $(this).dropdown('close');
                }
            });
            var $parent = $(this).parent();
            var config = JsonStringToJsonObject($parent.attr('data-am-dropdown')) || null;
            if (config != null && (config.trigger || null) == 'hover') {
                if ($parent.find('.am-dropdown-content').css('display') != 'block') {
                    $parent.find('.am-dropdown-content').attr('data-is-stay', 1);
                    $parent.dropdown('open');
                }
            }
        }
    });
    $(document).on('mousemove', '.am-dropdown .am-dropdown-toggle', function () {
        clearTimeout(dropdown_timer);
        if ($(window).width() >= 641) {
            var $parent = $(this).parent();
            var config = JsonStringToJsonObject($parent.attr('data-am-dropdown')) || null;
            if (config != null && (config.trigger || null) == 'hover') {
                if ($parent.find('.am-dropdown-content').css('display') != 'block') {
                    $parent.find('.am-dropdown-content').attr('data-is-stay', 1);
                    $parent.dropdown('open');
                }
            }
        }
    });
    $(document).on('click', '.am-dropdown .am-dropdown-toggle', function () {
        clearTimeout(dropdown_timer);
        if ($(window).width() >= 641) {
            var $parent = $(this).parent();
            var config = JsonStringToJsonObject($parent.attr('data-am-dropdown')) || null;
            if (config != null && (config.trigger || null) == 'hover') {
                if ($parent.find('.am-dropdown-content').css('display') != 'block') {
                    $parent.find('.am-dropdown-content').attr('data-is-stay', 1);
                    $parent.dropdown('open');
                }
            }
        }
    });
    $(document).on('mouseleave', '.am-dropdown .am-dropdown-toggle', function () {
        clearTimeout(dropdown_timer);
        if ($(window).width() >= 641) {
            var $parent = $(this).parent();
            var config = JsonStringToJsonObject($parent.attr('data-am-dropdown')) || null;
            if (config != null && (config.trigger || null) == 'hover') {
                $parent.find('.am-dropdown-content').attr('data-is-stay', 0);
                dropdown_timer = setTimeout(function () {
                    if ((parseInt($parent.find('.am-dropdown-content').attr('data-is-stay') || 0)) == 0) {
                        $parent.dropdown('close');
                    }
                }, 500);
            }
        }
    });
    $(document).on('mouseenter', '.am-dropdown .am-dropdown-content', function () {
        clearTimeout(dropdown_timer);
        if ($(window).width() >= 641) {
            var $parent = $(this).parent();
            var config = JsonStringToJsonObject($parent.attr('data-am-dropdown')) || null;
            if (config != null && (config.trigger || null) == 'hover') {
                $(this).attr('data-is-stay', 1);
            }
        }
    });
    $(document).on('mouseleave', '.am-dropdown .am-dropdown-content', function () {
        clearTimeout(dropdown_timer);
        if ($(window).width() >= 641) {
            var $parent = $(this).parent();
            var config = JsonStringToJsonObject($parent.attr('data-am-dropdown')) || null;
            if (config != null && (config.trigger || null) == 'hover') {
                $(this).attr('data-is-stay', 0);
                $parent.dropdown('close');
            }
        }
    });

    // 返回上一页、默认-1
    $(document).on('click', '.back-submit-event', function () {
        var number = $(this).data('number') || '-1';
        window.history.go(number);
    });

    // 页面切换
    var temp_scroll_original_index = 0;
    $(document).on('click', '.tabs-switch-horizontal-container ul li', function () {
        var $scroll_obj = $(this).parents('.tabs-switch-horizontal-container');
        var parent_width = $scroll_obj.width();
        var current_width = $(this).outerWidth(true);
        var current_index = $(this).index();
        if (current_index != temp_scroll_original_index) {
            // 计算当前元素前面的元素宽度（含padding、margin、border）
            var scroll = 0;
            for (var i = 0; i <= current_index; i++) {
                scroll += $scroll_obj.find('ul li:eq(' + i + ')').outerWidth(true);
            }
            // 减去父元素宽度一半
            scroll -= parent_width / 2;
            // 减去当前元素宽度一半
            scroll -= current_width / 2;
            $scroll_obj.animate({ 'scrollLeft': scroll });
        }
        temp_scroll_original_index = current_index;
    });

    // 混合业务列表选择
    $(document).on('click', '.business-list ul li', function () {
        var $business = ($(this).parents('form').length == 1) ? $(this).parents('form') : $(this).parents('.business-list');
        var $parent = $(this).parent();
        var type = $parent.data('type');
        var $input = $business.find('input[name=' + type + ']').length == 0 ? $business.find('input[name=' + type + '_id]') : $business.find('input[name=' + type + ']');
        var value = $input.val();
        if ($(this).hasClass('selected')) {
            if (parseInt($parent.data('is-required') || 0) == 0) {
                $input.val('');
                if (value != '') {
                    $input.trigger('change');
                }
                $(this).removeClass('selected');
            }
        } else {
            $input.val($(this).data('value'));
            if (value != $(this).data('value')) {
                $input.trigger('change');
            }
            $(this).addClass('selected').siblings('li').removeClass('selected');
        }
    });

    // 输入数据框添加清除按钮 - 鼠标进入事件
    $(document).on('mouseenter', 'select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"]', function () {
        InputClearOutHandle($(this));
    });
    // 输入数据框添加清除按钮 - 输入事件
    $(document).on('keyup', 'select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"]', function () {
        InputClearOutHandle($(this));
    });
    // 输入数据框添加清除按钮 - 获取焦点事件
    $(document).on('focus', 'select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"]', function () {
        InputClearOutHandle($(this));
    });
    // 下拉选择组件
    $(document).on('mouseenter', '.chosen-container .chosen-single, .am-selected .am-selected-btn', function () {
        InputClearOutHandle($(this));
    });
    $(document).on('mouseleave', '.chosen-container .chosen-single, .am-selected .am-selected-btn', function () {
        $(this).removeClass('input-clearout-element');
    });
    // 鼠标进入清除按钮 - 增加元素class
    $(document).on('mouseenter', 'a.input-clearout-submit', function () {
        $(this).prev().addClass('input-clearout-element');
    });
    // 鼠标移开清除按钮 - 移除元素class
    $(document).on('mouseleave', 'a.input-clearout-submit', function () {
        $(this).prev().removeClass('input-clearout-element');
    });
    // 输入数据框清除
    $(document).on('click', 'a.input-clearout-submit', function () {
        // 文本框则清空数据
        if ($(this).prev().is('input') || $(this).prev().is('textarea')) {
            $(this).prev().val('').trigger('change');
        }
        // 插件下拉选择组件
        if ($(this).parents('.chosen-container').length > 0) {
            var $select = $(this).parents('.chosen-container').prev();
            $select.val('');
            $select.trigger('chosen:updated');
            $select.trigger('change');
        }
        // 框架下拉选择组件
        if ($(this).parents('.am-selected').length > 0) {
            var $select = $(this).parents('.am-selected');
            $select.prev().val('');
            $select.find('.am-selected-btn .am-selected-status').text($select.prev().attr('placeholder') || $select.data('placeholder'));
            $select.find('.am-selected-list li.am-checked').removeClass('am-checked');
        }
        // 移除class
        $(this).prev().removeClass('input-clearout-element');
        // 删除清除按钮
        $(this).remove();
    });
    // 页面滚动则移除input删除按钮
    $(window).on('scroll', function () {
        $('*').removeClass('input-clearout-element');
        $('.input-clearout-submit').remove();
    });
    $('*').on('scroll', function () {
        $('*').removeClass('input-clearout-element');
        $('.input-clearout-submit').remove();
    });

    // 下拉组件输入框快捷添加则不触发失去焦点事件
    $(document).on('blur', '.chosen-container > .chosen-drop > .chosen-search > input', function () {
        if (parseInt($(this).parents('.chosen-container').prev().data('no-results-operate-button') || 0) == 1) {
            return false;
        }
    });
    // 下拉组件快捷添加
    $(document).on('click', '.chosen-container > .chosen-drop > .chosen-results > .no-results > .chosen-select-quick-add-submit', function () {
        var $this = $(this);
        var $parent = $this.parents('.chosen-container');
        var $select = $parent.prev();
        var value = $parent.find('.chosen-search input').val();
        var is_customer = $select.data('is-customer') || 0;
        var is_supplier = $select.data('is-supplier') || 0;
        var json = JsonStringToJsonObject($select.data('no-results-operate-button-params')) || {};
        var data_field = $select.data('no-results-operate-button-data-field');
        json[data_field] = value;
        $this.button('loading');
        $.ajax({
            url: RequestUrlHandle($select.data('quick-add-url')),
            type: 'POST',
            dataType: 'json',
            timeout: 30000,
            data: json,
            success: function (result) {
                $this.button('reset');
                if (result.code == 0) {
                    // 添加数据到列表
                    $select.append('<option value="' + result.data + '">' + value + '</option>');
                    // 选中并更新列表数据
                    $select.val(result.data);
                    $select.trigger('chosen:updated').trigger('change');
                    // 关闭下拉选择
                    $parent.removeClass('chosen-with-drop').removeClass('chosen-container-active');
                } else {
                    Prompt(result.msg);
                }
            },
            error: function (xhr, type) {
                $this.button('reset');
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
            }
        });
    });

    // 调起视频扫码、持续扫码
    var $video_scan_popup = $('#common-video-scan-popup');
    var $continue_submit = $video_scan_popup.find('.video-scan-continue-submit');
    var $switch_submit = $video_scan_popup.find('.video-scan-switch-camera-submit');
    var $video_scan = $video_scan_popup.find('.scanner video');
    var video_scan_code_reader = null;
    var video_scan_selected_deviceid = null;
    var video_scan_source_select = [];
    var video_scan_back_function = null;
    var video_scan_is_close_popup = 1;
    $(document).on('click', '.common-scan-submit,.video-scan-continue-submit', function () {
        // 关闭摄像头
        if (video_scan_code_reader != null) {
            video_scan_code_reader.reset();
        }

        // 关闭继续按钮
        $continue_submit.addClass('am-hide');

        // 主开启事件配置信息
        if (!$(this).hasClass('video-scan-continue-submit')) {
            // 回调方法
            video_scan_back_function = $(this).data('back-fun');
            // 是否关闭弹窗
            video_scan_is_close_popup = ($(this).data('auto-close-popup') == undefined) ? 1 : parseInt($(this).data('auto-close-popup') || 0);
        }

        // 开启弹窗
        $video_scan_popup.modal('open');

        // 初始化组件
        video_scan_code_reader = new ZXing.BrowserMultiFormatReader()

        // 摄像头列表
        video_scan_code_reader.listVideoInputDevices().then((videoInputDevices) => {
            // 初始最后一个摄像头
            if (video_scan_selected_deviceid == null) {
                // 默认取最后一个摄像头
                video_scan_selected_deviceid = videoInputDevices[videoInputDevices.length - 1].deviceId;
                // PC模式下默认画像翻转
                if (parseInt(__is_mobile__) != 1) {
                    $video_scan.addClass('picture-reverse');
                }
            }
            // 大于一个则增加列表切换
            if (videoInputDevices.length > 1) {
                // 摄像头加到容器
                videoInputDevices.forEach((element) => {
                    if (video_scan_source_select.indexOf(element.deviceId) == -1) {
                        video_scan_source_select.push(element.deviceId);
                    }
                });
                // 展示切换摄像头按钮
                $switch_submit.removeClass('am-hide');
            } else {
                $switch_submit.addClass('am-hide');
            }

            // 调起摄像头
            video_scan_code_reader.decodeFromVideoDevice(video_scan_selected_deviceid, 'video', (res, err) => {
                // 识别成功
                if (res) {
                    // 语音提示
                    $('.video-scan-audio-container').html('<audio src="' + __my_public_url__ + '/static/common/media/scan-success.mp3" controls autoplay style="height:0;"></audio>');
                    // 调用回调方法
                    if (IsExitsFunction(video_scan_back_function)) {
                        window[video_scan_back_function](res.text);
                    } else {
                        Prompt((window['lang_config_fun_not_exist_tips'] || '配置方法未定义') + '[' + video_scan_back_function + ']');
                    }
                    // 关闭摄像头
                    video_scan_code_reader.reset();
                    // 打开继续按钮
                    $continue_submit.removeClass('am-hide');
                    // 是否需要关闭弹窗
                    if (video_scan_is_close_popup == 1) {
                        $video_scan_popup.modal('close');
                    }
                }
                // 调起失败
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    Prompt(err);
                    // 打开继续按钮
                    $continue_submit.removeClass('am-hide');
                }
            });
        }).catch((err) => {
            Prompt(err);
        });
    });
    // 切换摄像头
    $switch_submit.on('click', function () {
        var index = $(this).attr('data-index');
        if (index == undefined) {
            video_scan_source_select.length - 1;
        }
        index = parseInt(index) + 1;
        if (video_scan_source_select[index] == undefined) {
            index = 0;
        }
        video_scan_selected_deviceid = video_scan_source_select[index];
        $switch_submit.attr('data-index', index);
        $continue_submit.trigger('click');
        // 画像翻转
        if (index == 0 || video_scan_source_select.length == 1) {
            $video_scan.addClass('picture-reverse');
        } else {
            $video_scan.removeClass('picture-reverse');
        }
    });
    // 弹窗关闭则关闭摄像头
    $video_scan_popup.on('close.modal.amui', function () {
        // 关闭摄像头组件
        video_scan_code_reader.reset();
        // 打开继续按钮
        $continue_submit.removeClass('am-hide');
    });
    // 地址下拉框关闭按钮
    var $dropdown = $('#doc-dropdown-js'), data = $dropdown.data('amui.dropdown');
    $('#doc-dropdown-close').on('click', function (e) {
        data.active ? $dropdown.dropdown('close') : alert('没有开哪有关，没有失哪有得！');
        return false;
    });
    $dropdown.on('open.dropdown.amui', function (e) {
        console.log('open event triggered');
    });

    // 分页点击事件
    $(document).on('click', '.am-pagination-container a', function () {
        var url = $(this).data('url') || null;
        if (url != null) {
            // 是否在动态数据列表表单中
            var $form_table = $(this).parents('.form-table-content');
            if ($form_table.length > 0) {
                $form_table.find('input[name="page"]').val($(this).data('value'));
                $form_table.find('.form-table-operate-top-search-submit').trigger('click');
            } else {
                window.location.href = url;
            }
        }
    });
    // 分页输入事件
    $(document).on('change', '.am-pagination-container input', function () {
        // 基础数据
        var type = $(this).data('type');
        var value = parseInt($(this).val() || $(this).data('default-value') || 0);
        if (isNaN(value)) {
            value = 1;
        }
        // 分页则处理最大分页数
        if (type == 'page') {
            var value_max = parseInt($(this).data('value-max'));
            if (value > value_max) {
                value = value_max;
            }
        }

        // 是否在动态数据列表表单中
        var $form_table = $(this).parents('.form-table-content');
        if ($form_table.length > 0) {
            $form_table.find('input[name="page' + ((type == 'size') ? '_size' : '') + '"]').val(value);
            $form_table.find('.form-table-operate-top-search-submit').trigger('click');
        } else {
            var url = $(this).data('url') || null;
            if (url != null) {
                window.location.href = $(this).data('url') + value;
            }
        }
    });

    // 表单底部浮动导航取消按钮事件
    $(document).on('click', '.am-form-popup-submit button', function () {
        // 1. 已指定 data-am-modal-close 弹窗关闭属性
        // 2. 为父级iframe载入的弹窗（则调用父级定义的关闭方法、当前窗口则不用）
        if ($(this).is('[data-am-modal-close]') && $(this).parents('.am-popup').length == 0 && $(this).parents('.am-modal').length == 0) {
            parent.CommonPopupClose();
        }
    });
    // 评论舰艇
    // 监听textarea是否有值-----改变btn评论按钮的颜色状态
    $(document).bind('input', '.add-event-listener-textarea', function (obj) {
        if ($(obj.target).val().length > 0) {
            $(obj.target).parent().find('.textarea-btn').addClass('am-background-main')
        } else {
            $(obj.target).parent().find('.textarea-btn').removeClass('am-background-main')
        }
    })

    // 颜色选择器模拟点击
    $(document).on('click', '.imitate-colorpicker-submit', function () {
        $(this).parent().find('.colorpicker-submit').children().trigger('click');
    })

    $(window).resize(function () {
        // 动态监听 初始化生成滚动箭头
        InitScroll();
    });
    // 生成滚动箭头
    InitScroll();

    // 级联选择器
    $(document).on('click', '.am-cascader .am-cascader-suffix', function () {
        if ($(this).parent().find('.am-input-suffix i').hasClass('is-reverse')) {
            $(this).parent().find('.am-input-suffix i').removeClass('is-reverse');
            $(this).parent().find('.am-cascader-dropdown').removeClass('am-active');
        } else {
            $(this).parent().find('i').addClass('is-reverse');
            $(this).parent().find('.am-cascader-dropdown').addClass('am-active');
            $('.region-linkage .am-cascader-menu ul').each(function () {
                if ($(this).find('li').length < 1) {
                    $(this).parents('.am-cascader-menu').removeClass('am-active')
                } else {
                    var self_top = $(this).find('li:eq(0)').offset().top;
                    var parent_top = $(this).offset().top;
                    $(this).parent().scrollTop(self_top - parent_top);
                }

            })
            $('.region-linkage .am-cascader-menu ul li.am-active').each(function () {
                $(this).parents('.am-cascader-menu').addClass('am-active');
                var self_top = $(this).offset().top;
                var parent_top = $(this).parent().offset().top;
                $(this).parent().parent().scrollTop(self_top - parent_top);
            })
        }
    })
    $(document).on('click', '.am-cascader .am-cascader-dropdown .am-cascader-node', function () {
        var level_1 = '';
        var level_2 = '';
        var level_3 = '';
        var level_1_name = '';
        var level_2_name = '';
        var level_3_name = '';
        var parents_menu = $(this).parents('.am-cascader-menu');
        $(this).addClass('am-active').siblings().removeClass('am-active');
        if (parents_menu.data('key') == 'province') {
            level_1 = $(this).data('value');
            level_1_name = $(this).data('name');
            parents_menu.parent().find('.am-cascader-menu:last-of-type').removeClass('am-active');
            if ($(this).find('i').length > 0) {
                parents_menu.next().addClass('am-active');
                RegionNodeData($(this).data('value'), 'province', 'city');
            } else {
                parents_menu.next().removeClass('am-active');
                parents_menu.parents('.am-cascader').find('input[name="province_city_county"]').val(level_1_name);
                $('input[name="province"]').val(level_1);
                $('input[name="city"]').val('');
                $('input[name="county"]').val('');
                parents_menu.parents('.am-cascader-dropdown').removeClass('am-active');
                parents_menu.parents('.am-cascader').find('.am-input-suffix i').removeClass('is-reverse');
            }
        } else if (parents_menu.data('key') == 'city') {
            level_1 = $(this).parents('.am-cascader-panel').find('.province li.am-active').data('value');
            level_1_name = $(this).parents('.am-cascader-panel').find('.province li.am-active').data('name');
            level_2 = $(this).data('value');
            level_2_name = $(this).data('name');
            if ($(this).find('i').length > 0) {
                parents_menu.next().addClass('am-active');
                RegionNodeData($(this).data('value'), 'city', 'county');
            } else {
                parents_menu.next().removeClass('am-active');
                parents_menu.parents('.am-cascader').find('input[name="province_city_county"]').val(level_1_name + '-' + level_2_name);
                $('input[name="province"]').val(level_1);
                $('input[name="city"]').val(level_2);
                $('input[name="county"]').val('');
                parents_menu.parents('.am-cascader-dropdown').removeClass('am-active');
                parents_menu.parents('.am-cascader').find('.am-input-suffix i').removeClass('is-reverse');
            }
        } else {
            level_1 = $(this).parents('.am-cascader-panel').find('.province li.am-active').data('value');
            level_2 = $(this).parents('.am-cascader-panel').find('.city li.am-active').data('value');
            level_3 = $(this).data('value');
            level_1_name = $(this).parents('.am-cascader-panel').find('.province li.am-active').data('name');
            level_2_name = $(this).parents('.am-cascader-panel').find('.city li.am-active').data('name');
            level_3_name = $(this).data('name');
            parents_menu.parents('.am-cascader').find('input[name="province_city_county"]').val(level_1_name + '-' + level_2_name + '-' + level_3_name);
            $('input[name="province"]').val(level_1);
            $('input[name="city"]').val(level_2);
            $('input[name="county"]').val(level_3);
            parents_menu.parents('.am-cascader-dropdown').removeClass('am-active');
            parents_menu.parents('.am-cascader').find('.am-input-suffix i').removeClass('is-reverse');
        }
    })
    // 监听div外部的点击事件，但不包括其子元素
    $(document).click(function (event) {
        var target = $(event.target);
        if (!target.parents(".am-cascader").length && !target.is(".am-cascader")) {
            // 关闭div
            $('.am-cascader .am-cascader-dropdown').removeClass('am-active');
            $('.am-cascader .am-input-suffix i').removeClass('is-reverse');
        }
    });
    // 卡片点击效果
    $(document).on('click', '#card li', function () {
        $(this).addClass('am-active').siblings().removeClass('am-active');
    })

    // 下拉框展开折叠
    $(document).on('click', '.user-info-dropdown-data-container > a', function () {
        if ($(this).hasClass('am-active')) {
            $(this).siblings().animate({
                opacity: 0,
            }, 100); // 500毫秒内将元素透明度设置为1，并显示高度，实现显示效果
            $(this).siblings().animate({
                height: 'hide'
            }, 300); // 500毫秒内将元素透明度设置为1，并显示高度，实现显示效果
            $(this).removeClass('am-active').siblings().removeClass('am-active');
        } else {
            $(this).siblings().animate({
                opacity: 1,
                height: 'show'
            }, 100); // 500毫秒内将元素透明度设置为1，并显示高度，实现显示效果
            $(this).addClass('am-active').siblings().addClass('am-active');
        }
    })
    // 当点击user-info-dropdown-data-container以外的地方下拉框隐藏
    $(document).click(function (event) {
        var dropdown = $('.user-info-dropdown-data-container');
        if (!dropdown.is(event.target) && dropdown.has(event.target).length === 0) {
            dropdown.find('.user-info-content').animate({
                opacity: 0,
            }, 100); // 500毫秒内将元素透明度设置为1，并显示高度，实现显示效果
            dropdown.find('.user-info-content').animate({
                height: 'hide'
            }, 300); // 500毫秒内将元素透明度设置为1，并显示高度，实现显示效果
            dropdown.find('.user-info-content').removeClass('am-active').siblings().removeClass('am-active');
        }
    });

    // 详情导航tabs切换事件
    $(document).on('click', '.detail-content-nav-switch-tabs .item', function () {
        $(this).addClass('am-active').siblings().removeClass('am-active');
        var key = $(this).data('key');
        $("[data-key='" + key + "']").addClass('am-active').siblings('.item').removeClass('am-active');
    });

    // 顶级提示信息
    $(document).on('click', '.am-operate-stretch-tips .title', function () {
        var $parent = $(this).parent();
        if ($parent.hasClass('am-close-tips')) {
            $parent.removeClass('am-close-tips');
            const tips = $(this).parent()[0];
            tips.style.height = 'auto';
            tips.style.width = '100%';
            const { height } = tips.getBoundingClientRect();
            tips.style.height = '35px';
            tips.style.width = '90px';
            $parent.animate({ height: height + 'px', width: '100%' }, 300);
        } else {
            $parent.addClass('am-close-tips');
            $parent.animate({ height: '35px', width: '90px' }, 300);
        }
    });

    // 后台页面二级导航
    $(document).on('click', '.nav-switch-btn .item', function () {
        $(this).addClass('am-active').siblings().removeClass('am-active');
        var key = $(this).data('key');
        $("[data-key='" + key + "']").addClass('am-active').siblings('.item').removeClass('am-active');
    });

    // 表单标题向上推显示效果
    $(document).on('blur', '.am-form-input-material input', function () {
        var value = $(this).val();
        if (value === '') {
            $(this).addClass('am-value-valid-false');
            $(this).removeClass('am-value-valid-true');
        } else {
            $(this).removeClass('am-value-valid-false');
            $(this).addClass('am-value-valid-true');
        }
    });
    $(document).on('focus', '.am-form-input-material input', function () {
        $(this).removeClass('am-value-valid-false');
        $(this).removeClass('am-value-valid-true');
    });
});