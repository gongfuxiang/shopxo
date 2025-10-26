var $forminput_data_list = [];
var $forminput_data_config = {};
var $forminput_id = '';
var $forminput_phone_id = '';
// 手机验证码需要的参数
var $forminput_user_verify_win = $('#forminput-user-verify-win');
var $forminput_verify_img = $forminput_user_verify_win.find('#user-verify-win-img');
var $forminput_verify = $forminput_user_verify_win.find('#user-verify-win-img-value');
var $forminput_verify_win_submit = $forminput_user_verify_win.find('.user-verify-win-submit');
// var $forminput_phone_url = '';
var $forminput_phone_data = {};
var $forminput_data_address_url = '';
var $forminput_data_address_code_url = '';
var $forminput_data_item_event = '';
var $forminput_data_data_event = '';
$(function () { 
    // 拿到单个操作的方法名称
    $forminput_data_item_event = $('.forminput-data-content').attr('data-item-event') || null;
    // 拿到返回所有数据的方法名称
    $forminput_data_data_event = $('.forminput-data-content').attr('data-data-event') || null;
    // 拿到所有的列表数据信息
    const data = $('.forminput-data-content').attr('data-list') || null;
    const data_list = (data == null) ? null : (JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(data)).toString(CryptoJS.enc.Utf8)) || null);
    // 规整初始数据
    $forminput_data_list = ForminputDataHandle(data_list);
    // 拿到所有的配置信息
    const config = $('.forminput-data-content').attr('data-config') || null;
    $forminput_data_config = (config == null) ? null : (JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(config)).toString(CryptoJS.enc.Utf8)) || null);
    // 获取表单id
    $forminput_id = $('.forminput-data-content').attr('data-id') || null;
    if($forminput_data_config === null) {
        return false;
    }
    // 地址组件数据
    $forminput_data_address_url = $('.forminput-data-content').attr('data-address-url') || '';
    $forminput_data_address_code_url = $('.forminput-data-content').attr('data-address-code-url') || '';
    if ($forminput_data_config.type_value == 'free') {
        $('.forminput .forminput-data').attr('style', 'width: '+ $forminput_data_config.custom_width +'px;');
        $('.forminput .forminput-popup-content').attr('style', 'width: '+ $forminput_data_config.custom_width +'px;' + 'height: '+ $forminput_data_config.custom_height +'px;' + 'padding:0;position: relative;');
    }
    const filed_title_size_type = $forminput_data_config.style_settings.computer.filed_title_size_type;
    // 默认显隐规则逻辑触发
    ForminputShowHiddenChange();
    // 输入框获取焦点时触发
    $(document).on('focus', '.forminput-input', function(e) {
        ForminputInputChange($(this), 'focus');
    })
    // 输入框离开焦点时触发
    $(document).on('blur', '.forminput-input', function(e) {
        ForminputInputChange($(this), 'blur');
    })
    // 输入框输入时的变化
    $(document).on('input', '.forminput-input', function(e) {
        // 获取当前输入框宽度的变化
        let val = $(this).val();
        // 触发数据变化
        ForminputDataChange($(this));
        // 获取当前数据框的父级数据
        let parentNode = $(this).parent();
        // 获取父节点下所有具有特定类的子元素
        let childrenWithClass = parentNode.find('.limit-num');
        // 如果没有这个元素的时候不能报错
        if (childrenWithClass) {
            // 修改父节点下所有具有特定类的子元素的内容
            childrenWithClass.html(val.length);
        }
    })
    // 密码选中时的变化
    $(document).on('click', '.forminput-password', function(e) {
        const this_data = $(this);
        // 获取当前数据框的父级数据
        let parentNode = this_data.parent();
        // 如果没有这个元素的时候不能报错
        if (parentNode.length === 0) {
            return;
        }
        let inputElement = parentNode.children('input');
        if (inputElement.length === 0) {
            return;
        }
        // 判断当前输入框是否为密码框
        if (this_data.hasClass('icon-eye')) {
            inputElement.prop('type', 'text');
            this_data.removeClass('icon-eye').addClass('icon-eye-slash');
        } else {
            inputElement.prop('type', 'password');
            this_data.addClass('icon-eye').removeClass('icon-eye-slash');
        }
    })
    var $forminput_add_option_popup = $('#forminput-add-option-popup');
    var $forminput_add_option_id = '';
    var $forminput_subform_add_option_index = '';
    var $forminput_subform_add_option_id = '';
    // 添加新选项时的变化
    $(document).on('click', '.forminput-data-content .add-option', function(e) {
        // 获取当前添加的id
        const parent = $(this).parent().parent();
        const parent_parent = parent.parent();
        $forminput_add_option_id = parent.attr('id');
        // 避免下拉复选框拿不到对应的id处理
        if (IsEmpty($forminput_add_option_id)) {
            $forminput_add_option_id = parent.parent().attr('id');
        }
        if ($forminput_add_option_id.indexOf('-') > -1) {
            $forminput_add_option_id = parent_parent.data('subform-id');
            $forminput_subform_add_option_index = parent_parent.data('index');
            $forminput_subform_add_option_id = parent_parent.data('id');
        }
        // 清空弹出框里的内容
        const data = $forminput_add_option_popup.find('input');
        data.val('');
        data.removeClass('am-field-error');
        // 打开弹出框
        $forminput_add_option_popup.modal('open');
    })
    // 新选项添加确认
    $(document).on('click', '#forminput-add-option-popup .add-option-confirm', function () {
        // 获取数据
        const data = $forminput_add_option_popup.find('input');
        // 如果输入框有数据，就往下执行，否则的话，提示用户输入数据
        if (!IsEmpty(data.val())) {
            // 获取数据列表，并添加到指定的全局数据中
            $forminput_data_list.forEach(item => {
                if (item.id == $forminput_add_option_id) {
                    const value = 'option' + ForminputGetMath();
                    const val_name = data.val();
                    let is_subform = false;
                    //  js添加选项的参数处理
                    let $element = $('#' + $forminput_add_option_id);
                    // 获取数据列表的长度
                    let list_length = 0;
                    // 获取数据详细信息
                    let add_option_data = item.com_data;
                    if (item.key == 'subform') {
                        is_subform = true;
                        $element = $('#' + $forminput_subform_add_option_id + '-' + $forminput_subform_add_option_index);
                        add_option_data = item.com_data.data_list[$forminput_subform_add_option_index].find(item => item.id == $forminput_subform_add_option_id).com_data || {};
                        list_length = add_option_data.option_list.concat(item.com_data.custom_option_list).length;
                    } else {
                        list_length = item.com_data.option_list.concat(item.com_data.custom_option_list).length;
                    }
                    // 新添加数据的内容
                    const params = {
                        type: 'add',
                        name: val_name,
                        value: value,
                        color: ForminputColorChange(list_length),
                    };
                    const checkbox_height_class = filed_title_size_type == 'big' ? 'radio_or_checkbox_big_height' : (filed_title_size_type == 'middle' ? 'radio_or_checkbox_height' : '');
                    const multicolour_class = filed_title_size_type == 'big' ? 'item-multicolour-big' : (filed_title_size_type == 'middle' ? 'item-multicolour' : 'item-multicolour-small');
                    if (add_option_data.type == 'checkbox') {
                        // 添加选项的html数据拼接
                        let new_option_html = '<label class="'+ (add_option_data.arrangement == 'horizontal' ? 'am-checkbox-inline ' : 'am-checkbox ') + (add_option_data.is_multicolour == '1' ? checkbox_height_class : '')+'">' +
                                '<input type="checkbox" name="' +  item.form_name +'" value="' + value +'" data-am-ucheck="" '+ (list_length == 0 && add_option_data.is_required == '1' ? 'required=""' : '') +
                                + (list_length == 0 && add_option_data.is_limit_num == '1' && !IsEmpty(add_option_data.min_num) ? ('minchecked="'+ add_option_data.min_num + '"') : '') +
                                + (list_length == 0 && add_option_data.is_limit_num == '1' && !IsEmpty(add_option_data.max_num) ? ('maxchecked="'+ add_option_data.max_num + '"') : '') +
                                ' class="am-ucheck-checkbox"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>' +
                                '<div class="'+ multicolour_class +' am-flex-row align-items-center forminput-gap-5" style="'+ (add_option_data.is_multicolour == '1' ? ('background:' + params.color + ';color:#fff;border-radius:4px;') : 'padding: 0;') +'">' + val_name + '<i data-value="'+ value +'" data-id="'+ $forminput_add_option_id +'" class="add-option-icon iconfont icon-close '+ (add_option_data.is_multicolour == '1' ? '' : 'forminput-cr-gray')+'"></i></div>' +
                            '</label>';
                        $element.find('.option-add').append(new_option_html);
                    } else {
                        let new_option_html = '<li value="'+ value +'" class="am-selected-item am-flex-row align-items-center" data-index="'+ list_length +'" data-group="0" data-value="'+ value +'">' +
                            '<div class="'+ multicolour_class +' am-flex-row align-items-center forminput-gap-5" style="'+ (add_option_data.is_multicolour == '1' ? ('background:' + params.color + ';color:#fff;border-radius:4px;') : 'padding: 0;') +'">' + val_name + '<i data-value="'+ value +'" data-id="'+ $forminput_add_option_id +'" data-subform-option-id="'+ $forminput_subform_add_option_id +'" data-subform-option-index="'+ $forminput_subform_add_option_index +'" class="add-option-icon iconfont icon-close '+ (add_option_data.is_multicolour == '1' ? '' : 'forminput-cr-gray')+'"></i></div>' +
                            '<i class="am-icon-check"></i></li>';
                        $element.find('.am-selected-list').append(new_option_html);
                        // 隐藏的select选项也需要添加进去新的内容
                        $element.parent().find('.forminput-select-multi').append('<option value="'+ value +'">'+ val_name +'</option>');
                    }
                    // 添加自定义选项的数据信息
                    add_option_data.custom_option_list.push(params);
                    // 自定义选项的输入框也需要添加进去新的内容
                    $element.parent().find('.forminput-custom-option-list').val(encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(add_option_data.custom_option_list)))));
                    // 每次更新操作完成需要抛出去，让其他人感知到
                    if (is_subform) {
                        ForminputVerifyWhenDataChanges(item.id);
                    } else {
                        OnItemEvent({ id: item.id, key: item.key, value: add_option_data.custom_option_list, is_error: '0', error_text: '' }, 'custom_option_list');
                    }
                }
            });
            // 添加成功，之后关闭弹出框
            $forminput_add_option_popup.modal('close');
            return false;
        }
    });
    // 删除新增选项的内容
    $(document).on('click', '.forminput-data-content .add-option-icon', function() { 
        const id = $(this).attr('data-id');
        const value = $(this).attr('data-value');
        // 主要是用于子表单的删除操作
        const subform_option_id = $(this).attr('data-subform-option-id');
        const subform_option_index = $(this).attr('data-subform-option-index');
        // 获取数据列表，并添加到指定的全局数据中
        $forminput_data_list.forEach(item => {
            if (item.id == id) {
                //  js删除选项的参数处理
                let $element = $('#' + id);
                // 获取数据详细信息
                let add_option_data = item.com_data;
                let is_subform = false; // 判断是否是子表单数据
                if (item.key == 'subform') {
                    is_subform = true;
                    $element = $('#' + subform_option_id + '-' + subform_option_index);
                    add_option_data = item.com_data.data_list[subform_option_index].find(item => item.id == subform_option_id).com_data || {};
                }
                // 执行删除数据
                const index = add_option_data.custom_option_list.findIndex(item => item.value == value);
                add_option_data.custom_option_list.splice(index, 1);
                // 如果是下拉复选框，需要删除对应的html内容和选中内容
                if (add_option_data.type !== 'checkbox') {
                    // 选中当前数据时，需要做判断
                    let old_element = $(this).parent().parent().parent().find('.am-selected-item.am-checked');
                    const selected_values = Array.from(old_element).map(item => item.dataset.value);
                    // 将独有的和所有的信息合并之后返回
                    const list = add_option_data.option_list.concat(add_option_data.custom_option_list);
                    const val_data = list.filter(item1 => selected_values.includes(item1.value));
                    // 先将所有的取消选中
                    $element.find('.forminput-visiable-hidden option').prop('selected', false);
                    // 先删除额外的参数
                    $element.find('.forminput-visiable-hidden option[value="'+ value +'"]').remove();
                    //  js添加选项的参数处理
                    if (val_data.length > 0) {
                        let new_option_html = '';
                        // 循环选中的数据，为每一个选中的添加选中效果
                        val_data.forEach((item2, index2) => {
                            const multicolour_class = filed_title_size_type == 'big' ? 'item-multicolour-big' : (filed_title_size_type == 'middle' ? 'item-multicolour' : 'item-multicolour-small');
                            // 添加选项的html数据拼接
                            new_option_html += '<span class="'+ multicolour_class +'" style="'+ (add_option_data.is_multicolour == '1' ? ('background:' + item2.color + ';color:'+ (item2.is_other && item2.is_other == '1' ? '#141E31' : '#fff') +';border-radius:4px;') : 'padding: 0;') +'">'+ (add_option_data.is_multicolour == '1' ? item2.name : (index2 !== 0 ? ',' + item2.name : item2.name)) +'</span>';
                            // 给对应的隐藏的select添加选中的属性
                            $element.find('.forminput-visiable-hidden option[value="'+ item2.value +'"]').prop('selected', true);
                        });
                        // 添加所有选中的数据
                        $element.find('.am-selected-status').html(new_option_html);
                    } else { 
                        // 如果没有选中的就添加默认文字显示
                        $element.find('.am-selected-status').html('<span class="forminput-cr-gray">' + add_option_data.placeholder + '</span>');
                    }
                }
                // 自定义选项的输入框也需要添加进去新的内容
                $element.parent().find('.forminput-custom-option-list').val(encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(add_option_data.custom_option_list)))));
                // 删除当前选项
                $(this).parent().parent().remove();
                // 每次更新操作完成需要抛出去，让其他人感知到
                if (is_subform) {
                    ForminputVerifyWhenDataChanges(item.id);
                } else {
                    OnItemEvent({ id: item.id, key: item.key, value: add_option_data.custom_option_list, is_error: '0', error_text: '' }, 'custom_option_list');
                }
            }
        })
    })
    // 下拉框切换选项逻辑处理
    $(document).on('click', '.forminput-data-content .am-selected-item', function () {
        // 获取当前父级的信息
        let parent = $(this).parent();
        const selectd_id = parent.parent().attr('id');
        if (IsEmpty(selectd_id)) {
            return;
        }
        // 获取当前父级的id
        let parent_id = selectd_id.replace('dropdown-', '');
        let subform_index = 0;
        let subform_id = '';
        if (parent_id.indexOf('-') > -1) {
            parent_id = parent.parent().data('subform-id');
            subform_index = parent.parent().data('index');
            subform_id = parent.parent().data('id');
        }
        let at_the_top_parent = parent.parent().parent();
        // 获取当前数据框整体数据
        let new_data = $forminput_data_list.find(item => item.id === parent_id);
        // 如果是子表单，需要更新的子表单对应数据
        if (new_data && new_data.key == 'subform') {
            new_data = new_data.com_data.data_list[subform_index].find(item => item.id == subform_id);
        }
        // 判断是否是单选数据
        if (new_data && ['single-text', 'select', 'radio-btns'].includes(new_data.key)) { 
            // 单选数据
            // 去掉其他选中的数据
            parent.find('.am-selected-item').removeClass('am-checked');
            // 给当前选中的添加对应的class
            $(this).addClass('am-checked');
            // 当前选中的id
            let value = $(this).attr('data-value');
            // 获取数据列表，并添加到指定的全局数据中
            $forminput_data_list.forEach(item => {
                if (item.id == parent_id) {
                    let subform_data = item.com_data;
                    let is_subform = false;
                    if (item.key == 'subform') {
                        is_subform = true;
                        const data = item.com_data.data_list[subform_index].find(item => item.id == subform_id);
                        subform_data = data.com_data || {};
                    }
                    // 将值赋值给原数组
                    subform_data.form_value = value;
                    const val_data = subform_data.option_list.find(item1 => item1.value == value);
                    //  js添加选项的参数处理
                    if (val_data) {
                        // 如果有选中的就移除错误提示
                        if (at_the_top_parent.find('.am-selected-btn.am-btn').hasClass('forminput-data-item-error')) {
                            at_the_top_parent.find('.am-selected-btn.am-btn').removeClass('forminput-data-item-error');
                        }
                        const multicolour_class = filed_title_size_type == 'big' ? 'item-multicolour-big' : (filed_title_size_type == 'middle' ? 'item-multicolour' : 'item-multicolour-small');
                        // 添加选项的html数据拼接
                        new_option_html = '<span class="'+ multicolour_class +'" style="'+ (subform_data.is_multicolour == '1' ? ('background:' + val_data.color + ';color:'+ (val_data.is_other && val_data.is_other == '1' ? '#141E31' : '#fff') +';border-radius:4px;') : 'padding: 0;') +'">'+ val_data.name +'</span>';
                        at_the_top_parent.find('.am-selected-status').html(new_option_html);
                        // 给对应的隐藏的select添加选中的属性
                        at_the_top_parent.find('.forminput-visiable-hidden option').prop('selected', false);
                        // 下拉框选中值赋值
                        at_the_top_parent.find('.forminput-visiable-hidden option[value="'+ value +'"]').prop('selected', true);
                        // 关闭下拉框
                        $(`.`+ parent_id + `-dropdown`).dropdown('close');
                        // 判断是否是选中其他
                        if (val_data.is_other && val_data.is_other == '1') {
                            // 判断是否存在
                            if  (at_the_top_parent.parent().find(`.`+ parent_id + `-other`).length > 0) {
                                return;
                            }
                            at_the_top_parent.parent().addClass('am-flex-col forminput-gap-10');
                            // 输入框的高度和不可输入区域的字体大小
                            const item_content_class = filed_title_size_type == 'big' ? 'item-content-big' : (filed_title_size_type == 'middle' ? 'item-content' : 'item-content-small');
                            // 输入框字体大小
                            const item_size = filed_title_size_type == 'big' ? 'item-big-size' : (filed_title_size_type == 'middle' ? 'item-size' : '');
                            const subform_html = `<div class="forminput-data-item-content `+ parent_id +`-other `+ item_content_class +`" style="` + item.common_style + `">
                                    <input 
                                        type="text" 
                                        name="`+ item.form_name +`_other_value" 
                                        value="`+ item.com_data.other_value +`"     
                                        placeholder="请输入其他内容" 
                                        class="forminput-input-other forminput-no-border forminput-w forminput-h ` + item_size + `"
                                    />
                             </div>`;
                            at_the_top_parent.parent().append(subform_html);
                        } else {
                            at_the_top_parent.parent().removeClass('am-flex-col forminput-gap-10');
                            // 移除其他输入框
                            at_the_top_parent.parent().find(`.`+ parent_id + `-other`).remove();
                        }
                    } else {
                        // 如果没有选中的就添加错误提示
                        if (subform_data.is_required == '1' && !at_the_top_parent.find('.am-selected-btn.am-btn').hasClass('forminput-data-item-error')) {
                            at_the_top_parent.find('.am-selected-btn.am-btn').addClass('forminput-data-item-error');
                        }
                        // 下拉框选中值赋值
                        at_the_top_parent.find('.forminput-visiable-hidden option[value="'+ value +'"]').prop('selected', true);
                        // 关闭下拉框
                        $(`.`+ parent_id + `-dropdown`).dropdown('close');
                        // 如果没有选中的就添加默认文字显示
                        at_the_top_parent.find('.am-selected-status').html('<span class="forminput-cr-gray">' + subform_data.placeholder + '</span>');
                    }
                    // 如果是子表单的话，处理一下显隐规则
                    if (item.key == 'subform') {
                        // 关闭所有子表单的下拉单选
                        $('.subform-dropdown.am-dropdown').dropdown('close');
                        // 重新处理显隐规则
                        ForminputSubformShowHiddenChange(item);
                    }
                    // 每次更新操作完成需要抛出去，让其他人感知到
                    if (is_subform) {
                        ForminputVerifyWhenDataChanges(item.id);
                    } else {
                        const isError = item.com_data.required == '1' && IsEmpty(subform_data.form_value);
                        OnItemEvent({ id: item.id, key: item.key, value: subform_data.form_value, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
                    }
                }
            });
            // 显隐规则逻辑处理
            ForminputShowHiddenChange();
        } else if (new_data && ['checkbox', 'select-multi'].includes(new_data.key)) {
            // 多选下拉框
            // 判断当前是否被选中,如果选中则取消选中，否则添加选中
            if ($(this).hasClass('am-checked')) {
                $(this).removeClass('am-checked');
            } else {
                $(this).addClass('am-checked');
            }
            // 选中当前数据时，需要做判断
            let old_element = parent.find('.am-selected-item.am-checked');
            const values = Array.from(old_element).map(item => item.dataset.value);
            // 获取数据列表，并添加到指定的全局数据中
            $forminput_data_list.forEach(item => {
                if (item.id == parent_id) {
                    let subform_data = item.com_data;
                    let is_subform = false;
                    if (item.key == 'subform') {
                        is_subform = true;
                        const data = item.com_data.data_list[subform_index].find(item => item.id == subform_id);
                        subform_data = data.com_data || {};
                    }
                    // 将值赋值给原数组
                    subform_data.form_value = values;
                    // 将独有的和所有的信息合并之后返回
                    const list = subform_data.option_list.concat(subform_data.custom_option_list);
                    const val_data = list.filter(item1 => values.includes(item1.value));
                    // 先将所有的取消选中
                    at_the_top_parent.find('.forminput-visiable-hidden option').prop('selected', false);
                    //  js添加选项的参数处理
                    if (val_data.length > 0) {
                        // 如果有选中的就移除错误提示
                        if (at_the_top_parent.find('.am-selected-btn.am-btn').hasClass('forminput-data-item-error')) {
                            at_the_top_parent.find('.am-selected-btn.am-btn').removeClass('forminput-data-item-error');
                        }
                        let new_option_html = '';
                        // 循环选中的数据，为每一个选中的添加选中效果
                        val_data.forEach((item2, index2) => {
                            const multicolour_class = filed_title_size_type == 'big' ? 'item-multicolour-big' : (filed_title_size_type == 'middle' ? 'item-multicolour' : 'item-multicolour-small');
                            // 添加选项的html数据拼接
                            new_option_html += '<span class="'+ multicolour_class +'" style="'+ (subform_data.is_multicolour == '1' ? ('background:' + item2.color + ';color:'+ (item2.is_other && item2.is_other == '1' ? '#141E31' : '#fff') +';border-radius:4px;') : 'padding: 0;') +'">'+ (subform_data.is_multicolour == '1' ? item2.name : (index2 !== 0 ? ',' + item2.name : item2.name)) +'</span>';
                            // 给对应的隐藏的select添加选中的属性
                            at_the_top_parent.find('.forminput-visiable-hidden option[value="'+ item2.value +'"]').prop('selected', true);
                        });
                        // 添加所有选中的数据
                        at_the_top_parent.find('.am-selected-status').html(new_option_html);
                    } else { 
                        // 如果没有选中的就添加错误提示
                        if (subform_data.is_required == '1' && !at_the_top_parent.find('.am-selected-btn.am-btn').hasClass('forminput-data-item-error')) {
                            at_the_top_parent.find('.am-selected-btn.am-btn').addClass('forminput-data-item-error');
                        }
                        // 如果没有选中的就添加默认文字显示
                        at_the_top_parent.find('.am-selected-status').html('<span class="forminput-cr-gray">' + subform_data.placeholder + '</span>');
                    }
                    // 每次更新操作完成需要抛出去，让其他人感知到
                    if (is_subform) {
                        ForminputVerifyWhenDataChanges(item.id);
                    } else {
                        const isError = item.com_data.required == '1' && IsEmpty(subform_data.form_value);
                        OnItemEvent({ id: item.id, key: item.key, value: subform_data.form_value, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
                    }
                }
            });
        }
    });
    // 单选按钮点击
    $(document).on('change', '.forminput-data-content .am-radio-click', function () { 
        let parent = $(this).parent().parent();
        const data = $(this).find('input').val();
        let id = parent.parent().attr('id');
        $forminput_data_list.forEach(function (item) {
            if (item.id == id) {
                item.com_data.form_value = data;
                // 判断是否是选中其他
                const val_data = item.com_data.option_list.find(item1 => item1.value == data);
                if (val_data.is_other && val_data.is_other == '1') {
                    if (parent.find(`.`+ id + `-other`).length > 0) {
                        return;
                    } else {
                        // 输入框的高度和不可输入区域的字体大小
                        const item_content_class = filed_title_size_type == 'big' ? 'item-content-big' : (filed_title_size_type == 'middle' ? 'item-content' : 'item-content-small');
                        // 输入框字体大小
                        const item_size = filed_title_size_type == 'big' ? 'item-big-size' : (filed_title_size_type == 'middle' ? 'item-size' : '');
                        const subform_html = `<div class="forminput-data-item-content `+ id +`-other `+ item_content_class +`" style="` + item.common_style + `">
                                <input 
                                    type="text" 
                                    name="`+ item.form_name +`_other_value" 
                                    value="`+ item.com_data.other_value +`"     
                                    placeholder="请输入其他内容" 
                                    class="forminput-input-other forminput-no-border forminput-w forminput-h ` + item_size + `"
                                />
                            </div>`;
                        parent.append(subform_html);
                    }
                } else {
                    // 移除其他输入框
                    parent.find(`.`+ id + `-other`).remove();
                }
                // 操作之后跑出去让其他用户能感知到
                const isError = item.com_data.required == '1' && IsEmpty(item.com_data.form_value);
                OnItemEvent({ id: item.id, key: item.key, value: item.com_data.form_value, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
            }
        });
        // 显隐规则逻辑处理
        ForminputShowHiddenChange();
    });

    // 多选按钮点击
    $(document).on('change', '.forminput-data-content .am-checkbox-click', function () { 
        let parent = $(this).parent().parent();
        // 获取复选框的值
        let tmp_all = [];
        let i = 0;
        $(this).parent().find('input[type="checkbox"]').each(function (key, tmp) {
            if ($(this).is(':checked')) {
                if (tmp_all == undefined) {
                    tmp_all = [];
                    i = 0;
                }
                tmp_all[i] = tmp.value;
                i++;
            } else {
                // 滑动开关、未选中则0
                if (typeof ($(this).attr('data-am-switch')) != 'undefined') {
                    tmp_all = [];
                }
            }
        });
        let id = parent.parent().attr('id');
        // 将值保存到数组中去
        $forminput_data_list.forEach(function (item) {
            if (item.id == id) {
                item.com_data.form_value = tmp_all;
                // 操作之后跑出去让其他用户能感知到
                const isError = item.com_data.required == '1' && IsEmpty(item.com_data.form_value);
                OnItemEvent({ id: item.id, key: item.key, value: item.com_data.form_value, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
            }
        });
    })
    // 鼠标移入评分图标时显示
    $(document).on('mouseover', '.forminput-data-content .forminput-score-icon', function () {
        let parent = $(this).parent().parent();
        // 获取当前的id
        let score_id = parent.attr('id');
        if (score_id.indexOf('-') > -1) {
            score_id = parent.data('subform-id');
        }
        // 获取当前评分数据
        let new_data = $forminput_data_list.find(item => item.id == score_id);
        if (new_data) {
            if (new_data.key == 'subform') {
                const index = parent.data('index');
                const id = parent.data('id');
                new_data = new_data.com_data.data_list[index].find(item => item.id == id);
            }
            // 获取当前分数
            let mouseover_index = $(this).data('index');
            ForminputScoreChange(parent, new_data, mouseover_index + 1);
        }
    });
    // 鼠标移出评分组件时隐藏
    $(document).on('mouseleave', '.forminput-data-content .forminput-score', function () {
        const parent = $(this).parent();
        // 获取当前的id
        let score_id = parent.attr('id');
        if (score_id.indexOf('-') > -1) {
            score_id = parent.data('subform-id');
        }
        // 获取当前评分的数据信息
        let new_data = $forminput_data_list.find(item => item.id == score_id);
        if (new_data) {
            if (new_data.key == 'subform') {
                const index = $(this).parent().data('index');
                const id = $(this).parent().data('id');
                new_data = new_data.com_data.data_list[index].find(item => item.id == id);
            }
            const selectd_index = new_data.com_data.form_value;
            // 根据不同类型区分不同的状态
            if (new_data.com_data.score_type == 0) { 
                // 先移除所有的选中样式
                $(this).find('.forminput-score-icon').removeClass('icon-pointed').addClass('icon-pointed-o').attr('style', 'color: #ccc;');
                // 添加选中样式
                for (let i = 0; i < selectd_index; i++) {
                    $(this).find('.forminput-score-icon').eq(i).removeClass('icon-pointed-o').addClass('icon-pointed').attr('style', 'color: ' + new_data.com_data.select_color + ';');
                }
            } else if (new_data.com_data.score_type == 1) { 
                // 先移除所有的选中样式
                $(this).find('.forminput-score-icon').removeClass('icon-the-heart').addClass('icon-the-heart-o').attr('style', 'color: #ccc;');
                // 添加选中样式
                for (let i = 0; i < selectd_index; i++) {
                    $(this).find('.forminput-score-icon').eq(i).removeClass('icon-the-heart-o').addClass('icon-the-heart').attr('style', 'color: ' + new_data.com_data.select_color + ';');
                }
            }
        }
    });
    // 鼠标点击评分
    $(document).on('click', '.forminput-data-content .forminput-score-icon', function () {
        let parent = $(this).parent().parent();
        let score_id = parent.attr('id');
        if (score_id.indexOf('-') > -1) {
            score_id = parent.data('subform-id');
        }
        // 获取当前评分的数据信息
        $forminput_data_list.forEach(item => {
            if (item.id == score_id) {
                let new_data = item;
                let is_subform = false;
                if (new_data.key == 'subform') {
                    is_subform = true;
                    const index = parent.data('index');
                    const id = parent.data('id');
                    new_data = new_data.com_data.data_list[index].find(item => item.id == id);
                }
                // 获取当前选中的索引
                let selectd_index = $(this).data('index') + 1;
                // 判断当前索引是否和当前值一致,一致的话就不进行处理
                if (selectd_index !== new_data.com_data.form_value) {
                    ForminputScoreChange(parent, new_data, selectd_index, 'click');
                    // 修改内部输入框的值，提交表单的时候可以直接处理
                    parent.find('input').attr('value', selectd_index);
                    new_data.com_data.form_value = selectd_index;
                }
                // 每次更新操作完成需要抛出去，让其他人感知到
                if (is_subform) {
                    ForminputVerifyWhenDataChanges(item.id);
                } else {
                    const isError = item.com_data.required == '1' && IsEmpty(new_data.com_data.form_value);
                    OnItemEvent({ id: item.id, key: item.key, value: new_data.com_data.form_value, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
                }
            }
        });
    });
    // 文件复制和下载处理
    $(document).on('click', '.forminput-data-content .attachments-click', function () { 
        let parent_parent = $(this).parent().parent().parent();
        let id = parent_parent.attr('id');
        if (id.indexOf('-') > -1) {
            id = parent_parent.data('subform-id');
        }
        // 获取当前对应的数据信息
        const new_data = $forminput_data_list.find(item => item.id == id);
        if (new_data) { 
            if (new_data.key == 'subform') {
                const index = parent.data('index');
                const id = parent.data('id');
                new_data = new_data.com_data.data_list[index].find(item => item.id == id);
            }
            const file_data = new_data.com_data.file[0] || {};
            if ($(this).children().is('.icon-copy')) {
                // 复制文件
                /* 创建一个临时的textarea元素 */
                const textarea = document.createElement('textarea');
                textarea.value = file_data.url;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    const successful = document.execCommand('copy');
                    const msg = successful ? '成功复制！' : '复制失败';
                    Prompt(msg, successful ? 'success' : '');
                } catch (err) {
                    console.error('复制失败', err);
                }       
                document.body.removeChild(textarea);
            } else if ($(this).children().is('.icon-download-btn')) {
                // 下载文件
                const link = document.createElement('a');
                link.href = file_data.url;
                link.download = file_data.original;
                link.target = "_blank"; // 可选，如果希望在新窗口中下载文件，请取消注释此行
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    });
    // 获取短信验证码
    $(document).on('click', '.forminput-data-content .verify-submit', function () { 
        // $phone_url = $(this).attr('data-url');
        let id = $(this).attr('id').replace('_verify', '');
        $forminput_phone_id = id;
        // 获取当前对应的数据信息
        const new_data = $forminput_data_list.find(item => item.id == id);
        // 如果数据为空的时候，则返回
        if (IsEmpty(new_data)) { 
            return;
        }
        $forminput_phone_data = new_data;
        if (new_data.com_data.is_img_sms_verification == '1') {
            // 短信验证码
            // 打开弹出框
            $forminput_user_verify_win.modal('open');
            $forminput_verify_img.trigger('click');
            $forminput_verify.val('');
            $forminput_verify.focus();
        } else {
            // 弹出框
            ForminputGetVerification('');
        }
    });
    // 图片验证码输入框修改
    $(document).on('click', '#forminput-user-verify-win .user-verify-win-submit', function () { 
        // 验证码参数处理
        verify = $forminput_verify.val().replace(/\s+/g, '');
        if(verify.length != 4)
        {
            Prompt($forminput_verify.data('validation-message'));
            $forminput_verify.focus();
            return false;
        }
        ForminputGetVerification(verify);
    });
    // 地址筛选处理
    $(document).on('click', '.forminput-data-content .am-cascader .am-cascader-dropdown .am-cascader-node', function () {
        let parent_parent = $(this).parent().parent().parent().parent();
        let id = parent_parent.attr('id');
        if (!IsEmpty(id)) {
            id = id.replace('-cascader-panel', '');
            if (id.indexOf('-') > 0) {
                id = parent_parent.data('subform-id');
            }
        }
        // 获取当前对应的数据信息
        let new_data = $forminput_data_list.find(item => item.id == id);
        // 如果数据为空的时候，则返回
        if (IsEmpty(new_data)) { 
            return;
        }
        // 获取表单名称
        let form_name = new_data.form_name || '';
        let _province = form_name + '_province';
        let _province_name = form_name + '_province_name';
        let _city = form_name + '_city';
        let _city_name = form_name + '_city_name';
        let _county = form_name + '_county';
        let _county_name = form_name + '_county_name';
        let subform_index = 0;
        let subform_id = '';
        if (new_data.key == 'subform') {
            const old_data = JSON.parse(JSON.stringify(new_data));
            subform_index = parent_parent.data('index');
            subform_id = parent_parent.data('id');
            if (subform_id) {
                new_data = new_data.com_data.data_list[subform_index].find(item => item.id == subform_id);
                if (IsEmpty(new_data)) {
                    return;
                } else {
                    const name = (old_data.form_name || '') + '[' + subform_index + '][' + (new_data.form_name || '');
                    _province = name + '_province]';
                    _province_name = name + '_province_name]';
                    _city = name + '_city]';
                    _city_name = name + '_city_name]';
                    _county = name + '_county]';
                    _county_name = name + '_county_name]';
                }
            }
        }
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
                $('input[name="'+ _province +'"]').val(level_1);
                $('input[name="'+ _province_name +'"]').val(level_1_name);
                $('input[name="'+ _city +'"]').val('');
                $('input[name="'+ _city_name +'"]').val('');
                $('input[name="'+ _county +'"]').val('');
                $('input[name="'+ _county_name +'"]').val('');
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
                $('input[name="'+ _province +'"]').val(level_1);
                $('input[name="'+ _province_name +'"]').val(level_1_name);
                $('input[name="'+ _city +'"]').val(level_2);
                $('input[name="'+ _city_name +'"]').val(level_2_name);
                $('input[name="'+ _county +'"]').val('');
                $('input[name="'+ _county_name +'"]').val('');
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
            $('input[name="'+ _province +'"]').val(level_1);
            $('input[name="'+ _province_name +'"]').val(level_1_name);
            $('input[name="'+ _city +'"]').val(level_2);
            $('input[name="'+ _city_name +'"]').val(level_2_name);
            $('input[name="'+ _county +'"]').val(level_3);
            $('input[name="'+ _county_name +'"]').val(level_3_name);
            parents_menu.parents('.am-cascader-dropdown').removeClass('am-active');
            parents_menu.parents('.am-cascader').find('.am-input-suffix i').removeClass('is-reverse');
        }
        // 如果省市区不为空，就删除报错信息
        if (!IsEmpty(level_1) || !IsEmpty(level_2) || !IsEmpty(level_3)) {
            if (parent_parent.parent().parent().find('.am-cascader-suffix  .forminput-data-item-content').hasClass('forminput-data-item-error')) {
                parent_parent.parent().parent().find('.am-cascader-suffix  .forminput-data-item-content').removeClass('forminput-data-item-error');
            }
        }
        if ($forminput_data_list.length > 0) {
            $forminput_data_list.forEach((item) => {
                if (item.id == id) {
                    let subform_data = item.com_data;
                    let is_subform = false;
                    if (item.key == 'subform') {
                        is_subform = true;
                        const data = item.com_data.data_list[subform_index].find(item => item.id == subform_id);
                        subform_data = data.com_data || {};
                    }
                    subform_data.form_value = [level_1, level_2, level_3];
                    subform_data.province_name = level_1_name;
                    subform_data.city_name = level_2_name;
                    subform_data.county_name = level_3_name;
                    // 每次更新操作完成需要抛出去，让其他人感知到
                    if (is_subform) {
                        ForminputVerifyWhenDataChanges(item.id);
                    } else {
                        const isError = item.com_data.required == '1' && IsEmpty(subform_data.form_value);
                        OnItemEvent({ id: item.id, key: item.key, value: subform_data.form_value, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
                    }
                }
            })
        }
    })
    // 富文本上传文件处理
    $(document).on('change', '.forminput-data-content .rich-file-upload-input', function () {
        if (IsEmpty($editor_id)) {
            return;
        }
        const file = this.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('upfile', file);
            formData.append('type', $rich_upload_type);
            formData.append('path_type', 'forminputdata-' + $forminput_id);
            $.ajax({
                url: RequestUrlHandle($(this).data('url')),
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(res)
                {
                    if(res.code == 0)
                    {
                        // 上传成功后，将图片插入到编辑器中
                        const url = res.data.url;
                        const alt = res.data.name;
                        // 弹出框结束的时候触发添加事件
                        if ($rich_upload_type == 'image') {
                            $upload_insert(url, alt);
                        } else {
                            $upload_insert(url);
                        }
                        Prompt('上传成功', 'success');
                    } else {
                        Prompt(res.msg);
                    }
                },
                error: function(xhr, type){ Prompt('上传失败'); }
            });
        }
    });
    // 删除上传文件处理
    $(document).on('click', '.forminput-data-content .file-upload-view-close', function () {
        let index = $(this).parent().attr('data-index');
        let id = $(this).parent().attr('data-id');
        let subform_id = $(this).parent().attr('data-subform-index-id');
        let subform_index = $(this).parent().attr('data-subform-index');
        let upload_input_data = [];
        // 显示删除弹出框
        $('#forminput-subform-data').modal({
            relatedTarget: this,
            onConfirm: function(options) {
                $forminput_data_list.forEach((item) => {
                    if (item.id == id) {
                        let new_data = item;
                        let new_id = id;
                        let is_subform = false;
                        // 用于赋值的id
                        if (item.key == 'subform') {
                            is_subform = true;
                            new_data = item.com_data.data_list[subform_index].find(item => item.id == subform_id);
                            new_id = subform_id + '-' + subform_index;
                        }
                        const $element = $('#' + new_id).find('.file-upload-view-list');
                        if (new_data.com_data.form_value) {
                            // 将新数据编辑重新赋值
                            let new_form_value_html = '';
                            // 删除选中的数据
                            new_data.com_data.form_value.splice(index, 1);
                            if (new_data.com_data.form_value.length > 0) {
                                new_data.com_data.form_value.forEach((form_value_item, form_value_index) => {
                                    if (new_data.key == 'upload-img') {
                                        new_form_value_html += ForminputFileUploadImg(form_value_index, form_value_item, id, new_data.id, subform_index);
                                    } else if (new_data.key == 'upload-video') {
                                        new_form_value_html += ForminputFileUploadVideo(form_value_index, form_value_item, id, new_data.id, subform_index);
                                    } else if (new_data.key == 'upload-attachments') {
                                        new_form_value_html += ForminputFileUploadAttachments(form_value_index, form_value_item, id, new_data.id, subform_index); 
                                    }       
                                });
                                // 将遍历好的塞入到html中
                                $element.html(new_form_value_html);
                            } else if (!$element.hasClass('file-upload-hidden')) {
                                $element.html('');
                                $element.addClass('file-upload-hidden');
                            }
                            // 将新增数据显示出来
                            upload_input_data = new_data.com_data.form_value;
                            // 赋值
                            $('#' + new_id).find('input').val(encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(upload_input_data)))));
                            // 每次更新操作完成需要抛出去，让其他人感知到
                            if (is_subform) {
                                ForminputVerifyWhenDataChanges(item.id);
                            } else {
                                const isError = item.com_data.required == '1' && IsEmpty(upload_input_data);
                                OnItemEvent({ id: item.id, key: item.key, value: upload_input_data, is_error: isError ? '1' : '0', error_text: isError ? '必选字段不能为空' : '' });
                            }
                        }
                    }
                })
            }
        });  
    }); 
    // 预览上传的文件
    $(document).on('click', '.forminput-data-content .file-upload-view-item-preview', function() { 
       let id = $(this).parent().attr('data-id');
       let index = $(this).parent().attr('data-index');
       // 获取数据
       let new_data = $forminput_data_list.find(item => item.id == id);
       if (new_data) {
            //如果是子表单的话，更新数据处理
            if (new_data.key == 'subform') {
                let subform_index = $(this).parent().attr('data-subform-index');
                let subform_index_id = $(this).parent().attr('data-subform-index-id');
                new_data = new_data.com_data.data_list[subform_index].find(item => item.id == subform_index_id);
            }
            const upload_preview_data = new_data.com_data.form_value[index];
            $('#forminput-upload-popup-preview').find('.am-popup-title').text(upload_preview_data.name);
            let new_form_value_html = '';
            // 图片的预览直接显示
            if (new_data.key == 'upload-img') {
                new_form_value_html = `<img src="` + upload_preview_data.url + `" style="width: 100%;height: 100%;object-fit: contain;" alt="`+ upload_preview_data.name + `"></img>`;
            } else if (new_data.key == 'upload-video') {
                // 视频预览
                new_form_value_html = `<video src="` + upload_preview_data.url + `" style="width: 100%;height: 100%;object-fit: contain;" controls="controls"></video>`;
            } else if (new_data.key == 'upload-attachments') {
                // 附件预览
                let index = upload_preview_data.name.lastIndexOf('.'); // 获取最后一个/的位置
                // 获取文件后缀
                let upload_ext = upload_preview_data.name.substring(index + 1); // 截取最后一个/后的值
                // 后缀符合图片上传或者视频上传的格式，点击执行预览，否则的话执行下载
                if (['png', 'jpg', 'jpeg', 'bmp', 'webp', 'gif'].includes(upload_ext)) {
                    new_form_value_html = `<img src="` + upload_preview_data.url + `" style="width: 100%;height: 100%;object-fit: contain;" alt="`+ upload_preview_data.name + `"></img>`;
                } else if (['flv', 'swf', 'mkv', 'avi', 'rm', 'rmvb', 'mpeg', 'mpg', 'ogg', 'ogv', 'mov', 'wmv', 'mp4', 'webm'].includes(upload_ext)) {
                    new_form_value_html = `<video src="` + upload_preview_data.url + `" style="width: 100%;height: 100%;object-fit: contain;" controls="controls"></video>`;
                } else {
                    // 下载文件
                    const link = document.createElement('a');
                    link.href = upload_preview_data.url;
                    link.download = upload_preview_data.name;
                    link.target = "_blank"; // 可选，如果希望在新窗口中下载文件，请取消注释此行
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    Prompt('下载成功', 'success');
                }
            }
            if (new_form_value_html != '') {
                $('#forminput-upload-popup-preview').find('.am-popup-bd').html(new_form_value_html);
                $('#forminput-upload-popup-preview').modal('open');
            }
       }
    });
    // 子表单详细数据显示
    $(document).on('click', '.forminput-data-content .operate-enlarge', function () {
        // 获取子表单ID
        const selected_id = $(this).data('id');
        // 获取点击的是第几行
        const index = $(this).data('index');
        
    });
    // 子表单数据删除
    $(document).on('click', '.forminput-data-content .operate-delete', function () {
        // 获取子表单ID
        const selected_id = $(this).data('id');
        // 获取点击的是第几行
        const index = $(this).data('index');
        $('forminput-subform-data').find('.am-modal-hd').html('子表单数据删除');
        // 显示删除弹出框
        $('#forminput-subform-data').modal({
            relatedTarget: this,
            onConfirm: function(options) {
                $forminput_data_list.forEach((item) => {
                    if (item.id == selected_id) {
                        item.com_data.data_list.splice(index, 1);
                        // 渲染数据
                        $('#' + selected_id).find('.subform-data-list').html(ForminputSetSubformData(item));
                        // 地址初始化
                        RegionLinkageInit();
                        // 刷新下拉框
                        $('.subform-dropdown.am-dropdown').dropdown();
                        // 重新处理显隐规则
                        ForminputSubformShowHiddenChange(item);
                        // 每次更新操作完成需要抛出去，让其他人感知到
                        ForminputVerifyWhenDataChanges(item.id);
                    }
                })
            }
        });   
    });     
    // 子表单数据添加
    $(document).on('click', '.forminput-data-content .subform-add', function () {
        // 获取子表单ID
        const selected_id = $(this).data('id');
        $forminput_data_list.forEach((item) => {
            if (item.id == selected_id) {
                // 刷新子表单数据
                const new_subform_html = ForminputSetOneLineData(item, item.com_data.data_list.length, item.com_data.children);
                $('#' + selected_id).find('.subform-data-list').append(new_subform_html);
                // 地址初始化
                RegionLinkageInit();
                $('.subform-dropdown.am-dropdown').dropdown();
                item.com_data.data_list.push(JSON.parse(JSON.stringify(item.com_data.children || [])))
                // 重新处理显隐规则
                ForminputSubformShowHiddenChange(item);
                // 每次更新操作完成需要抛出去，让其他人感知到
                ForminputVerifyWhenDataChanges(item.id);
            }
        })
    });
    // 子表单删除按钮
    $(document).on('click', '.forminput-data-content .subform-delete', function () {
        const parent = $(this).parent();
        // 隐藏添加和删除按钮
        parent.find('.subform-add').addClass('subform-button-hidden');
        parent.find('.subform-delete').addClass('subform-button-hidden');
        // 显示取消操作和删除选中按钮
        parent.find('.subform-cancel').removeClass('subform-button-hidden');
        parent.find('.subform-delete-selected').removeClass('subform-button-hidden');
        // 显示多选按钮组
        parent.parent().find('.forminput-table-container').addClass('forminput-table-checkbox');
        // 创造一个对应删除子表单列的数组
        const id = $(this).data('id');
        // 每次点击删除按钮的时候都将数组记为空，避免出现重复或者误删的情况
        $forminput_subform_check_list[id] = [];
        // 清空页面选中效果
        $('#' + id).find('.subform-checkbox').prop('checked', false);
        $('#' + id).find('.subform-num-checkbox .subform-index-checkbox').prop('checked', false);
    });
    // 子表单取消操作按钮
    $(document).on('click', '.forminput-data-content .subform-cancel', function () {
       const parent = $(this).parent();
       // 显示添加和删除按钮
       parent.find('.subform-add').removeClass('subform-button-hidden');
       parent.find('.subform-delete').removeClass('subform-button-hidden');
       // 隐藏取消操作和删除选中按钮
       parent.find('.subform-cancel').addClass('subform-button-hidden');
       parent.find('.subform-delete-selected').addClass('subform-button-hidden');
       // 隐藏多选按钮组
       parent.parent().find('.forminput-table-container').removeClass('forminput-table-checkbox');
    });
    $forminput_subform_check_list = {};
    // 全选
    $(document).on('click', '.forminput-data-content .subform-header-checkbox .subform-checkbox', function () {
        const id = $(this).data('id');
        const $element = $('#' + id).find('.subform-num-checkbox .subform-index-checkbox');
        // 判断是否为空，如果为空证明历史没有添加过，需要初始化
        if (IsEmpty($forminput_subform_check_list[id])) {
            $forminput_subform_check_list[id] = [];
        }
        // 全选
        if ($(this).prop('checked')) {
            // 全选
            $forminput_subform_check_list[id] = [];
            // 获取对应的id数据
            const find_data_list = $forminput_data_list.find(item => item.id === id);
            // 判断数据是否存在
            if (!IsEmpty(find_data_list)) {
                // 直接生成连续的索引数组，避免逐个检查和添加
                $forminput_subform_check_list[id] = Array.from({length: find_data_list.com_data.data_list.length}, (_, index) => index);
                // 选中
                $element.prop('checked', true);
            }
        } else {
            // 取消全选
            $forminput_subform_check_list[id] = [];
            // 取消
            $element.prop('checked', false);
        }
    });
    // 选中某一个
    $(document).on('click', '.forminput-data-content .subform-num-checkbox .subform-index-checkbox', function () {
        const id = $(this).data('id');
        const index = $(this).data('index');
        const $element = $('#' + id).find('.subform-checkbox');
        // 判断是否为空，如果为空证明历史没有添加过，需要初始化
        if (IsEmpty($forminput_subform_check_list[id])) {
            $forminput_subform_check_list[id] = [];
        }
        // 选中
        if ($(this).prop('checked')) {
            // 如果历史记录中已经包含当前index，证明已经选中过，所以不添加对应的数据
            if (!$forminput_subform_check_list[id].includes(index)) {
                $forminput_subform_check_list[id].push(index);
            }
            // 如果数量一致的话，添加选中逻辑，否则的话添加取消逻辑
            const check_flag = $forminput_data_list.some(item => item.id == id && item.com_data.data_list.length == $forminput_subform_check_list[id].length);
            if (check_flag) {
                $element.prop('checked', true);
            } else {
                $element.prop('checked', false);
            }
        } else {
            // 取消选中的时候移除当前点击的数据
            $forminput_subform_check_list[id] = $forminput_subform_check_list[id].filter((item) => item != index);
            // 如果全选是选中状态，取消全选
            if ($element.prop('checked')) {
                $element.prop('checked', false);
            }
            
        }
    });
    // 删除选中的内容
    $(document).on('click', '.forminput-data-content .subform-delete-selected', function () {
        const subform_id = $(this).data('id');
        // 判断选中的数量是否为空，为空则不处理
        if (!IsEmpty[$forminput_subform_check_list[subform_id]]) {
            $forminput_data_list.forEach((item) => {
                if (item.id == subform_id) {
                    const new_data = JSON.parse(JSON.stringify(item.com_data.data_list));
                    // 去除所有没有被选中的数据
                    item.com_data.data_list = new_data.filter((item, index) => !$forminput_subform_check_list[subform_id].includes(index));
                    // 重新计算删除数据
                    $forminput_subform_check_list[subform_id] = [];
                    // 渲染数据
                    $('#' + subform_id).find('.subform-data-list').html(ForminputSetSubformData(item));
                    // 地址初始化
                    RegionLinkageInit();
                    // 刷新下拉框
                    $('.subform-dropdown.am-dropdown').dropdown();
                    // 重新处理显隐规则
                    ForminputSubformShowHiddenChange(item);
                    // 每次更新操作完成需要抛出去，让其他人感知到
                    ForminputVerifyWhenDataChanges(item.id);
                }
            });
        }
    })
    // 子表单下拉框列表操作
    $(document).on('click', '.forminput-data-content .subform-dropdown-item', function () {
        // 获取子表单ID
        const selected_id = $(this).data('id');
        // 获取点击的是第几行
        const index = $(this).data('index');
        // 获取点击类型
        const type = $(this).data('type');
        $forminput_data_list.forEach((item) => {
            if (item.id == selected_id) {
                // 获取子表单指定索引的数据
                const data = JSON.parse(JSON.stringify(item.com_data.data_list[index]));
                // 获取的初始数据
                const children = JSON.parse(JSON.stringify(item.com_data.children));
                // 拷贝到下一行
                if (type == 'copy') {
                    item.com_data.data_list.splice(index, 0, data);
                } else if (type == 'copy_last') {
                    // 复制到最后一行
                    item.com_data.data_list.push(data);
                } else if (type == 'insert_top') {
                    // 插入上一行
                    item.com_data.data_list.splice(index, 0, children);
                } else if (type == 'insert_bottom') {
                    // 插入下一行
                    item.com_data.data_list.splice(index + 1, 0, children);
                }
                // 渲染数据
                $('#' + selected_id).find('.subform-data-list').html(ForminputSetSubformData(item));
                // 地址初始化
                RegionLinkageInit();
                // 刷新下拉框
                $('.subform-dropdown.am-dropdown').dropdown();
                // 重新处理显隐规则
                ForminputSubformShowHiddenChange(item);
                // 每次更新操作完成需要抛出去，让其他人感知到
                ForminputVerifyWhenDataChanges(item.id);
            }
        })
        
    });
    // 子表单中的下拉框位置显示问题
    $forminput_parent_offset = '';
    $forminput_selected_click_id = '';
    // 更新下来框显示位置
    $(document).on('click', '.forminput-data-content .selected-click', function () {
        const parent = $(this).parent();
        // const dropdown = parent.find('.select-fixed-position');
        $forminput_selected_click_id = $(this).data('id');
        // 获取整个组件在页面的位置
        $forminput_parent_offset = parent.offset();
        // 获取组件高度
        let parent_height = $(this).find('.forminput-data-item-content').outerHeight();
        let left = $forminput_parent_offset.left;
        // 如果没有获取到组件高度，则获取组件图标高度
        if (IsEmpty(parent_height)) {
            parent_height = $(this).find('.operate-icon').outerHeight();
            left = left - 14;
        }
        // 获取内容高度
        // const content_height = parent.find('.am-dropdown-content').outerHeight();
        let top = $forminput_parent_offset.top + parent_height;
        // // 下拉框内容高度超出屏幕高度时，调整位置
        // if (($(window).height() - 200) < ($forminput_parent_offset.top + parent_height + content_height)) {
        //     top = $forminput_parent_offset.top - parent_height;
        // }
        // 重新定位显示位置
        parent.find('.select-fixed-position').css({
            position: 'fixed',
            top: top,
            left: left
        });
    });
    // 监听整个容器的滚动，如果滚动大于100px，则隐藏下拉框内容
    $('#forminputScoll').on('scroll', function() {
        var scrollTop = $(this).scrollTop();
        if (scrollTop > 100 && !IsEmpty($forminput_parent_offset)) {
            // 移除地址选中样式
            $('.select-fixed-position').removeClass('am-active');
            $('.select-fixed-position').parent().find('.iconfont.icon-angle-down').removeClass('is-reverse');
            // 关闭下来框的显示
            if (!IsEmpty($forminput_selected_click_id)) {
                $('#' + $forminput_selected_click_id).dropdown('close');
            }
        }
    });
    // 提示信息移入
    $('.forminput-data-content .tooltip').on('mouseenter', function () {
        const offset = $(this).offset();
        const parent_height = $(this).outerHeight();
        const parent_width = $(this).outerWidth();
        $(this).find('.tooltip-text').css({
            position: 'fixed',
            display: 'block',
            top: offset.top - parent_height - 10,
            left: offset.left + (parent_width / 2)
        });
    });
    // 提示信息移出
    $('.forminput-data-content .tooltip').on('mouseleave', function () {
        $(this).find('.tooltip-text').css({
            display: 'none',
            position: 'absolute',
            top: 0,
            left: 0
        });
    });
    // 删除内容
    $(document).on('click', '.forminput-data-content a.input-clearout-submit', function () {
        let parent = $(this).parent().parent();
        // 获取组件ID
        let id = parent.attr('id');
        // 如果id为空的时候，再往上一层查询
        if (IsEmpty(id)) {
            parent = parent.parent();
            id = parent.attr('id');
        }
        // 获取子表单ID
        let subform_id = parent.data('subform-id');
        const subform_index = parent.data('index');
        const data_id = parent.data('id');
        // 获取子表单ID, 如果子表单的id为空，证明当前不属于子表单, 将subform_id设置为id
        if (IsEmpty(subform_id)) {
            subform_id = id;
        }
        // 遍历数据，将对应的数据清空
        $forminput_data_list.forEach(item => {
            if (item.id == subform_id) {
                if (item.key == 'subform') {
                    item.com_data.data_list[subform_index].forEach(data_list_item => {
                        if (data_list_item.id == data_id) {
                            // 查询所有的兄弟组件
                            const val_parent = $(this).siblings();
                            if (val_parent.hasClass('forminput-input-date-group-start')) { // 如果时间选择器
                                let start = '';
                                let end = '';
                                val_parent.each(function() {
                                    if ($(this).hasClass('forminput-input-date-group-start')) {
                                        start = $(this).val();
                                    } else if ($(this).hasClass('forminput-input-date-group-end')) {
                                        end = $(this).val();
                                    }
                                });
                                // 将获取到的开始和结束时间赋值给表单数据
                                data_list_item.com_data.form_value = [start, end];
                            } else {
                                data_list_item.com_data.form_value = '';
                            }
                        }
                    });
                    // 每次更新操作完成需要抛出去，让其他人感知到
                    ForminputVerifyWhenDataChanges(item.id);
                } else {
                    // 查询所有的兄弟组件
                    const val_parent = $(this).siblings();
                    // 如果是短信验证码的清空操作
                    if (val_parent.hasClass('forminput-input-verify')) {
                        item.com_data.form_value_code = '';
                    } else if (val_parent.hasClass('forminput-input-address')) { // 如果是详细地址
                        item.com_data.address = '';
                    } else if (val_parent.hasClass('forminput-input-other')) { // 如果是详细地址
                        item.com_data.other_value = '';
                    } else if (val_parent.hasClass('forminput-input-date-group-start')) { // 如果时间选择器
                        let start = '';
                        let end = '';
                        val_parent.each(function() {
                            if ($(this).hasClass('forminput-input-date-group-start')) {
                                start = $(this).val();
                            } else if ($(this).hasClass('forminput-input-date-group-end')) {
                                end = $(this).val();
                            }
                        });
                        // 将获取到的开始和结束时间赋值给表单数据
                        item.com_data.form_value = [start, end];
                    } else {
                        item.com_data.form_value = '';
                    }
                }
            }
        })
    });
    // 初始化富文本编辑器
    ForminputEditorInit();
    // 子表单的显隐规则处理
    $forminput_data_list.forEach(item => {
        if (item.key == 'subform') {
           ForminputSubformShowHiddenChange(item);
        }
    })
    // 时间选择器组开始时间离开焦点时触发
    $(document).on('change', '.forminput-data-content .forminput-input-date-group-start', function(e) {
        FormInputDateGroupHandle($(this), 'start')
    });
    // 时间选择器组结束时间离开焦点时触发
    $(document).on('change', '.forminput-data-content .forminput-input-date-group-end', function(e) {
        FormInputDateGroupHandle($(this), 'end')
    });
    // 时间选择器离开焦点时触发
    $(document).on('change', '.forminput-data-content .forminput-input-date', function(e) {
        // 获取当前数据框的父级数据
        let parentNode = $(this).parent();
        // 获取父级id
        let id = parentNode.parent().attr('id');
        let subform_id = parentNode.parent().data('subform-id');
        let data_id = '';
        let data_index = 0;
        if (!IsEmpty(subform_id)) {
            id = subform_id;
            data_id = parentNode.parent().data('id');
            data_index = parentNode.parent().data('index');
        }
        $forminput_data_list.forEach(item => {
            if (item.id == id) {
                let com_data = item.com_data;
                if (item.key == 'subform') {
                    const subform_data = com_data.data_list[data_index].find(item1 => item1.id == data_id);
                    if (!IsEmpty(subform_data)) {
                        com_data = subform_data.com_data
                    }
                } 
                com_data.form_value = $(this).val();
                if (item.key == 'subform') {
                    ForminputVerifyWhenDataChanges(item.id);
                } else {
                    OnItemEvent({ id: item.id, key: item.key, value: com_data.form_value, is_error: '0', error_text: '' });
                }
                
            }
        })
    });
    // 详细地址输入框离开焦点时触发
    $(document).on('blur', '.forminput-data-content .forminput-input-address', function(e) {
        // 获取当前数据框的父级数据
        let parentNode = $(this).parent().parent().parent();
        // 获取父级id
        let id = parentNode.attr('id');
        $forminput_data_list.forEach(item => {
            if (item.id == id) {
                item.com_data.address = $(this).val();
                OnItemEvent({ id: item.id, key: item.key, value: $(this).val(), is_error: '0', error_text: '' }, 'detailed');
            }
        })
    });
    // 验证码输入框离开焦点时触发
    $(document).on('blur', '.forminput-data-content .forminput-input-verify', function(e) {
        // 获取当前数据框的父级数据
        let parentNode = $(this).parent().parent().parent().parent();
        // 获取父级id
        let id = parentNode.attr('id');
        $forminput_data_list.forEach(item => {
            if (item.id == id) {
                item.com_data.form_value_code = $(this).val();
                OnItemEvent({ id: item.id, key: item.key, value: $(this).val(), is_error: '0', error_text: '' }, 'phone_code');
            }
        })
    });
    // 其他输入框离开焦点时触发
    $(document).on('blur', '.forminput-data-content .forminput-input-other', function(e) {
        // 获取当前数据框的父级数据
        let parentNode = $(this).parent();
        // 获取父级id
        let id = parentNode.parent().attr('id');
        if (IsEmpty(id)) {
            id = parentNode.parent().parent().attr('id');
        }
        $forminput_data_list.forEach(item => {
            if (item.id == id) {
                item.com_data.other_value = $(this).val();
                OnItemEvent({ id: item.id, key: item.key, value: $(this).val(), is_error: '0', error_text: '' }, 'other');
            }
        })
    });
});
// 时间选择器组数据更新
function FormInputDateGroupHandle(e, type = 'start') {
    // 获取当前数据框的父级数据
    let parentNode = e.parent().parent();
    // 获取父级id
    let id = parentNode.parent().attr('id');
    // 获取子表单的id
    let subform_id = parentNode.parent().data('subform-id');
    let data_id = '';
    let data_index = 0;
    // 子表单id不等于空，证明是子表单的数据，需要获取对应的index和内容
    if (!IsEmpty(subform_id)) {
        id = subform_id;
        data_id = parentNode.parent().data('id');
        data_index = parentNode.parent().data('index');
    }
    $forminput_data_list.forEach(item => {
        if (item.id == id) {
            let com_data = item.com_data;
            if (item.key == 'subform') {
                const subform_data = com_data.data_list[data_index].find(item1 => item1.id == data_id);
                if (!IsEmpty(subform_data)) {
                    com_data = subform_data.com_data
                }
            } 
            const val = e.val();
            com_data.form_value = [ type == 'start' ? val : (com_data.form_value[0] || ''), type == 'end' ? val : (com_data.form_value[1] || '')];
            if (item.key == 'subform') {
                ForminputVerifyWhenDataChanges(item.id);
            } else {
                OnItemEvent({ id: item.id, key: item.key, value: com_data.form_value, is_error: '0', error_text: '' });
            }
        }
    })
}
/*
* 没有办法监听用户离开时进行校验逻辑的组件，直接使用数据更新时的执行校验逻辑，并抛给父级
*/
function ForminputVerifyWhenDataChanges(id) { 
    const data = JSON.parse(JSON.stringify($forminput_data_list));
    const filter_data_list = data.filter((item) => ['subform', 'position', 'rich-text', 'upload-attachments', 'upload-img', 'upload-video'].includes(item.key) && item.id === id);
    if (filter_data_list.length > 0) {
        filter_data_list?.forEach((item) => {
            let com_data = item.com_data;
            let message = '';
            if (item.key === 'subform') {
                com_data = ForminputSubformDataCheck(com_data);
                // 子表单中每一行的数据显示
                const subform_data = ForminputSubformDataExtraction(com_data.data_list);
                let is_error = '0';
                // 如果子表单外层为error直接显示名称出来
                if (com_data.common_config.is_error == '1') {
                    is_error = '1';
                    message = `${com_data.title}「${com_data.common_config.error_text}」`;
                } else {
                    // 判断子表单每一行是否有报错提示
                    const error_data = com_data.data_list.some((item) => item.some((list_item) => list_item.com_data.common_config.is_error === '1'))
                    if (error_data) {
                        // 如果是内部问题，让用户自己检查子表单内的填写
                        message = `请检查${com_data.title}内的填写`;
                    } else {
                        message = '';
                    }
                }
                OnItemEvent({ id: item.id, key: item.key, value: subform_data, is_error: is_error, error_text: message }, 'subform');
            } else {
                // 跳过非必填项
                if (com_data.is_required === '1') {
                    if (ForminputFieldCheckMap[item.key]) {
                        let field_data = ForminputFieldCheckMap[item.key];
                        if (['single-text', 'select', 'radio-btns'].includes(com_data.type)) {
                            field_data = ForminputFieldCheckMap[com_data.type];
                        }
                        const { is_format, type } = field_data;
                        const { is_error = '0', error_text = '' } = ForminputGetFormatChecks(com_data, com_data.form_value, is_format, type);
                        // 文件进行校验
                        OnItemEvent({ id: item.id, key: item.key, value: com_data.form_value, is_error, error_text });
                    }
                }
            }
        });
    }
}
/*
* 子表单内的数据提取
*/
function ForminputSubformDataExtraction(data_list) {
    return data_list.map((subform_item) => {
        const data = {};
        // 子表单中每一行的数据显示
        subform_item.forEach((item1) => {
            // 子表单中每个独立的数据name
            const subform_name = IsEmpty(item1.form_name) ? item1.id : item1.form_name;
            const subform_com_data = item1.com_data;
            // 对应的value
            const subform_value = IsEmpty(subform_com_data.form_value) ? '' : subform_com_data.form_value;
            if (!this.filter_data.includes(item1.key)) {
                if (item1.key == 'address') {
                    data[`${ subform_name }_province_id`] = subform_value[0] || '';
                    data[`${ subform_name }_city_id`] = subform_value[1] || '';
                    data[`${ subform_name }_county_id`] = subform_value[2] || '';
                    // 省市区中文名称
                    data[`${ subform_name }_province_name`] = subform_com_data.province_name || '';
                    data[`${ subform_name }_city_name`] = subform_com_data.city_name || ''
                    data[`${ subform_name }_county_name`] = subform_com_data.county_name || ''
                } else if (item1.key ==='date-group') {
                    data[`${ subform_name }_start`] = subform_value[0] || '';
                    data[`${ subform_name }_end`] = subform_value[1] || '';
                } else if (['checkbox', 'select-multi'].includes(item1.key)) {
                    data[subform_name] = subform_value;
                    if (subform_com_data.is_add_option == '1') {
                        data[`${ subform_name }_custom_option_list`] = subform_com_data?.custom_option_list || [];
                    }
                } else {
                    data[subform_name] = subform_value;
                }
            }
        });
        return data;
    });
}
// 显隐规则逻辑处理
function ForminputSubformShowHiddenChange(item) { 
    // 判断字段是否为空
    if (IsEmpty(item.com_data.children)) {
        return;
    }
    const tiltle_list = ForminputSubformShowHiddenTypeChange(item.com_data.children, null, item.com_data.data_list);
    // 先隐藏所有
    $('#' + item.id).find('.subform-header').addClass('forminput-subform-hidden');
    // 循环处理表头显示
    tiltle_list.forEach((title_item, title_index) => {
        // 显示指定的
        $('#' + item.id).find('#subform-header-' + title_item.id).removeClass('forminput-subform-hidden');
    });
    const tiltle_list_id = tiltle_list.map(item => item.id);
    // 先隐藏所有子级内容,并且将显示内容处理一下
    $('#' + item.id).find('.subform-cell').addClass('forminput-subform-hidden').removeClass('forminput-subform-content-hidden');
    item.com_data.data_list.forEach((data_item, index) => { 
        const com_data_data_list = ForminputSubformShowHiddenTypeChange(item.com_data.children, index, item.com_data.data_list, 'index');
        const data_item_id = com_data_data_list.map(item => item.id);
        com_data_data_list.forEach((com_data_data_item, com_data_data_index) => {
            // 显示指定的
            $('#' + item.id).find('#' + com_data_data_item.id + '-' + index).removeClass('forminput-subform-hidden');
        });
        // 去除标题中包含的数据
        const filter_unique_id = tiltle_list_id.filter(filter_item => !data_item_id.includes(filter_item))
        if (filter_unique_id.length > 0) {
            filter_unique_id.forEach((filter_unique_id_item, filter_unique_id_index) => {
                $('#' + item.id).find('#' + filter_unique_id_item + '-' + index).removeClass('forminput-subform-hidden').addClass('forminput-subform-content-hidden');
            });
        }
        // 循环处理添加固定样式
        tiltle_list.forEach((title_item, title_index) => {
            const left_sticky = ForminputSubformLeftSticky(title_index + 1, tiltle_list, item.com_data);
            $('#' + item.id).find('#subform-header-' + title_item.id).attr('style', `width:`+ title_item.com_data.com_width +`px;` + (IsEmpty(left_sticky) ? '' : left_sticky + 'z-index:9999;'));
            $('#' + item.id).find('#' + title_item.id + '-' + index).attr('style', `width:`+ title_item.com_data.com_width +`px;` + (IsEmpty(left_sticky) ? '' : left_sticky + `z-index:`+ (9990 - index) +`;`));
        });
    });
}

// 规整数据，将子表单数据更新一下
function ForminputDataHandle(data) {
    if (IsEmpty(data)) {
        return [];
    }
    data.forEach(item => {
        if (item.key == 'subform') {
            item.com_data.data_list = [];
            let data_list = [];
            item.com_data.form_value.forEach((item1) => {
                const data = JSON.parse(JSON.stringify(item.com_data?.children || []));
                if (data.length > 0) {
                    data.forEach((child) => {
                        if (!IsEmpty(item1[child.form_name])) {
                            child.com_data.form_value = item1[child.form_name];
                            if (!IsEmpty(item1[child.form_name])) {
                                const subform_name = child.form_name;
                                const subform_com_data = child.com_data;
                                if (child.key == 'address') {
                                    subform_com_data.form_value = item1[subform_name] || [];
                                    // 省市区中文名称
                                    subform_com_data.province_name = item1[`${ subform_name }_province_name`] || '';
                                    subform_com_data.city_name = item1[`${ subform_name }_city_name`] || '';
                                    subform_com_data.county_name = item1[`${ subform_name }_county_name`] || '';
                                } else if (['checkbox', 'select-multi'].includes(child.key)) {
                                    subform_com_data.form_value = item1[subform_name] || [];
                                    if (subform_com_data.is_add_option == '1') {
                                        subform_com_data.custom_option_list = item1[`${ subform_name }_custom_option_list`] || [];
                                    }
                                } else {
                                    subform_com_data.form_value = item1[subform_name] || '';
                                }
                            }
                        }
                    });
                    data_list.push(data);
                }
            });
            item.com_data.data_list = data_list;
        } else if (item.key == 'phone') {
            // 判断输入框内容是否为空,为空的话获取验证码取消
            const { is_error = '0'} = ForminputHandlePhoneValidation(item.com_data);
            if (!IsEmpty(item.com_data.form_value) && is_error == '0') {
                $('#' + item.id).find('.verify-submit').prop('disabled', false);
                $('#' + item.id).find('.verify-submit').removeClass('forminput-btn-disabled').addClass('forminput-btn-primary');
            } else {
                $('#' + item.id).find('.verify-submit').prop('disabled', true);
                $('#' + item.id).find('.verify-submit').removeClass('forminput-btn-primary').addClass('forminput-btn-disabled');
            }
        }
    });
    return data;
}

function ForminputEditorInit() {
    const { createEditor, createToolbar } = window.wangEditor
    // 遍历所有组件，找到对应的富文本组件
    $forminput_data_list.forEach(item => {
        if (item.key === 'rich-text') {
            // 初始化富文本组件
            const editorConfig = {
                placeholder: item.com_data.placeholder,
                onChange(editor) {
                    const html = editor.getHtml()
                    // 将数据同步到表单中
                    $('#' + item.id + '-value').val(html);
                    // 将值传给原来的内容中
                    item.com_data.form_value = html;
                },
                onBlur(editor) {
                    const html = editor.getHtml()
                    const isError = item.com_data.required == '1' && IsEmpty(html);
                    OnItemEvent({ id: item.id, key: item.key, value: html, is_error: isError ? '1' : '0', error_text: '必填字段不能为空' });
                },
                MENU_CONF: {
                    // 自定义菜单配置
                    uploadImage: {
                        // 自定义选择图片
                        customBrowseAndUpload(insertFn) {
                            $editor_id = item.id;
                            $rich_upload_type = 'image';
                            $upload_insert = insertFn;
                            $('#' + item.id + '-rich-file-upload').prop('accept', 'image/*');
                            $('#' + item.id + '-rich-file-upload').click();
                        },
                    },
                    uploadVideo: {
                        // 自定义上传视频
                        customBrowseAndUpload(insertFn) {
                            $editor_id = item.id;
                            $rich_upload_type = 'video';
                            $upload_insert = insertFn;
                            $('#' + item.id + '-rich-file-upload').prop('accept', 'video/*');
                            $('#' + item.id + '-rich-file-upload').click();
                        },
                    },
                },
            }

            const editor = createEditor({
                selector: '#editor-container-' + item.id,
                html: item.com_data.form_value,
                config: editorConfig,
                mode: 'default', // or 'simple'
            })

            const toolbarConfig = {}

            const toolbar = createToolbar({
                editor,
                selector: '#toolbar-container-' + item.id,
                config: toolbarConfig,
                mode: 'default', // or 'simple'
            })
        }
    });
}
// 输入内容改变处理
function ForminputInputChange(e, type) {
    // 获取当前数据框的父级数据
    let parentNode = e.parent();
    // 获取父级id
    let id = parentNode.parent().attr('id');
    // 如果父级id为空，就从父级父级获取
    if (IsEmpty(id)) {
        id = parentNode.parent().parent().attr('id');
    }
    // 获取当前数据框的父级数据，选中之后添加边框
    if (parentNode.hasClass('forminput-data-item-focus')) {
        parentNode.removeClass('forminput-data-item-focus');
    } else {
        parentNode.addClass('forminput-data-item-focus');
    }
    if (id.indexOf('-') > -1) { 
        id = parentNode.parent().data('subform-id');
        if (IsEmpty(id)) {
            id = parentNode.parent().parent().data('subform-id');
        }
    }
    // 获取当前选中或者移开的数据
    let new_data = $forminput_data_list.find(item => item.id == id);
    if (new_data) { 
        let form_value = new_data.form_value;
        let is_subform = false;
        let is_error = '0';
        let message = '';
        // 判断当前是否为子表单
        if (new_data.key == 'subform') {
            let subform_index = parentNode.parent().data('index');
            let subform_id = parentNode.parent().data('id');
            if (IsEmpty(subform_id)) {
               parentNode.parent().parent().data('index');
               parentNode.parent().parent().data('id');
            }
            // 获取子表单的数据
            new_data = new_data.com_data.data_list[subform_index].find(item => item.id == subform_id);
            // 如果子表单数据为空，就取消执行
            if (IsEmpty(new_data)) {
                return;
            }
            is_subform = true;
            form_value = ForminputSubformDataExtraction(new_data.com_data.data_list);
        }
        // 获取焦点和失去焦点时做数据处理
        if (type == 'focus') {
            if (new_data.key == 'number') {
                // 不为空的时候，获取焦点的时候将千分位的转化为数字避免用户输入的时候出现问题
                if (!IsEmpty(new_data.com_data.form_value)) {
                    e.val(Number(ForminputFormatNumber(new_data.com_data.form_value, false)).toFixed(new_data.com_data.decimal_num));
                }
            }
        } else {
            if (new_data.key == 'number') {
                if (!IsEmpty(new_data.com_data.form_value)) {
                    let all_list = new_data.com_data.form_value.replace(/[^0-9.]/g, '');
                    // 去除不是数字和.的值
                    num = Number(ForminputFormatNumber(all_list, false)).toFixed(new_data.com_data.decimal_num);
                    // 为数字并且时千分位的是你
                    if (new_data.com_data.format == 'num' && new_data.com_data.is_thousandths_symbol == '1') {
                        // 如果有多个.的话，去除多个.
                        const parts = all_list.split('.');
                        if (parts.length > 2) {
                            // 如果有多个小数点，则只保留第一个
                            all_list = parts[0] + '.' + parts.slice(1).join('');
                        }
                        // let childrenWithClass = parentNode.querySelector('.forminput-input');
                        e.val(ForminputFormatNumber(Number(ForminputFormatNumber(all_list, false)).toFixed(new_data.com_data.decimal_num).toString(), true))
                    }
                }
            }
            // 输入框离开时做的校验逻辑
            if (e.hasClass('am-field-error')) {
                parentNode.addClass('forminput-data-item-error');
            } else {
                // 特殊字段验证：手机号
                if (new_data.key === 'phone') {
                    const { is_error = '0', error_text = '' } = ForminputHandlePhoneValidation(new_data.com_data);
                    if (is_error == '1') {
                        // 错误信息
                        type = is_error;
                        message = error_text;
                        parentNode.addClass('forminput-data-item-error');
                        Prompt(error_text);
                    } else {
                        parentNode.removeClass('forminput-data-item-error');
                    }
                } else {
                    let check_map = ForminputFieldCheckMap[new_data.key];
                    if (['single-text', 'select', 'radio-btns'].includes(new_data.key)) {
                        check_map = ForminputFieldCheckMap[new_data.com_data.type];
                    }
                    const { is_error, error_text} = ForminputGetFormatChecks(new_data.com_data, new_data.com_data.form_value, check_map.is_format, check_map.type);
                    if (is_error == '1') {
                        // 错误信息
                        type = is_error;
                        message = error_text;
                        parentNode.addClass('forminput-data-item-error');
                        Prompt(error_text);
                    } else {
                        parentNode.removeClass('forminput-data-item-error');
                    }
                }
            }
            if (type == 'blur') {
                if (is_subform) {
                    ForminputVerifyWhenDataChanges(new_data.id);
                } else {
                    OnItemEvent({ id: new_data.id, key: new_data.key, value: new_data.form_value, is_error: is_error, error_text: message });
                }
            }
        }
    }
}

/*
* 单个文件更新触发的事件
*/
function OnItemEvent(e, type = '') {
    // 判断方法是否存在元素事件
    if (IsExitsFunction($forminput_data_item_event)) {
        // 取出对应id的数据
        const data = $forminput_data_list.find((item) => item.id == e.id);
        // 判断是否为空，为空则不进行处理
        if (data) {
            let form_value = {};
            const com_data = data.com_data;
            const value = com_data.form_value;
            const form_name = data.form_name;
            if (data.key == 'address') {
                // 判断输入的是否是详细地址，如果不是详细地址就返回地址信息，否则的话就返回详细地址信息
                if (type != 'detailed') {
                    form_value[`${ form_name }_province_id`] = value[0] || '';
                    form_value[`${ form_name }_city_id`] = value[1] || '';
                    form_value[`${ form_name }_county_id`] = value[2] || '';
                    // 省市区中文名称
                    form_value[`${ form_name }_province_name`] = com_data.province_name || '';
                    form_value[`${ form_name }_city_name`] = com_data.city_name || ''
                    form_value[`${ form_name }_county_name`] = com_data.county_name || ''
                } else {
                    form_value[`${ form_name }_address`] = com_data?.address || '';
                }
            } else if (data.key == 'phone') {
                // 判断是否是短信验证码输入，否则的话，传递手机号显示
                if (type != 'phone_code') {
                    form_value[`${ form_name }`] = com_data?.form_value || '';
                } else {
                    form_value[`${ form_name }_verify`] = com_data?.form_value_code || '';
                }
            } else if (data.key ==='date-group') {
                // 如果是时间选择器，需要规整一下数据
                form_value[`${ form_name }_start`] = com_data?.form_value[0] || '';
                form_value[`${ form_name }_end`] = com_data?.form_value[1] || '';
            } else if (['checkbox', 'select-multi'].includes(data.key)) {
                // 如果是复选框和下拉复选框，判断一下是否是添加的新选项
                if (type != 'custom_option_list') {
                    form_value[`${ form_name }`] = com_data?.form_value || '';
                } else {
                    form_value[`${ form_name }_custom_option_list`] = com_data?.custom_option_list || '';
                }
            } else if (['select', 'radio-btns', 'single-text'].includes(data.key) && ['select', 'radio-btns'].includes(data.com_data.type)) {
                // 判断是否是输入的其他的内容
                if (type != 'other') {
                    form_value[`${ form_name }`] = com_data?.form_value || '';
                } else {
                    form_value[`${ form_name }_other_value`] = com_data?.other_value || '';
                }
            } else if (type == 'subform') {
                form_value = e.value;
            } else {
                form_value[`${ form_name }`] = com_data?.form_value || '';
            }
            // 方法执行的是单个数据更新操作
            window[$forminput_data_item_event]({ forminput_id: $forminput_id, status: e.is_error == '1' ? 'error' : 'success', message: e.error_text, value: form_value, form_name: data.form_name, form_title: data.com_data.title, key: data.key, type: data.com_data?.type || '' });
        }
    }

    // 判断方法是否存在， 方法执行的是获取所有数据更新
    if (IsExitsFunction($forminput_data_data_event)) {
        window[$forminput_data_data_event](ForminputSubmitDataParameterHandle())
    }
}
// 处理手机号验证逻辑
function ForminputHandlePhoneValidation(com_data) {
    // if (com_data.is_sms_verification === '1' && com_data.is_required === '1' && IsEmpty(com_data.form_value_code)) {
    //     return { is_error: '1', error_text: '短信验证码不能为空' };
    // }
    com_data.common_config.format = com_data.is_telephone === '1' ? 'telephone-number' : 'phone-number';
    return ForminputGetFormatChecks(com_data, com_data.form_value, true);
}
// 显隐规则逻辑处理
function ForminputShowHiddenChange(type = 'show-hidden') {
    const componentMap = new Map($forminput_data_list.map((item) => [item.id, item]));

    // 取出所有设置显隐规则的组件
    const list = $forminput_data_list.filter((item) => ['single-text', 'select', 'radio-btns'].includes(item.key) && ['select', 'radio-btns'].includes(item.com_data.type) && item.com_data.show_hidden_list.length > 0);
    const list_map = list.map((item) => ({ id: item.id, list: item.com_data.show_hidden_list }));
    const show_data = $forminput_data_list.filter((item) => {
        // 优先判断是否启用
        if (item.is_enable !== '1') return false;
        if (list_map.length === 0) return true;
        // 将所有的内容的组件进行筛选
        const isShownByRule = list_map.some((list_item) => {
            const targetComponent = componentMap.get(list_item.id);
            // 判断显隐规则对应的组件是否存在
            if (!targetComponent) return false;
            return list_item.list.some((hidden_item) => {
                // 判断当前组件是否在显隐规则中，如果不在，直接显示，否则的话判断值是否存在
                if (hidden_item.is_show.includes(item.id)) {
                    return targetComponent.com_data.form_value.includes(hidden_item.value);
                } else {
                    return true;
                }
            });
        });
        return isShownByRule;
    });
    if (type == 'show-hidden') {
        // 先隐藏所有组件
        $(document).find('.forminput-data-content .forminput-data-item').addClass('forminput-hidden');
        // 所有隐藏的组件进行显示
        show_data.forEach((item) => { 
            $('#' + item.id).parent().removeClass("forminput-hidden"); 
        });
    } else {
        return show_data;
    }
}
// 子表单的显隐处理
function ForminputSubformShowHiddenTypeChange(childrenList = [], index, data_list, type = 'all') { 
    const componentMap = new Map(childrenList.map((item) => [item.id, item]));
    // 取出所有设置显隐规则的组件
    const list = childrenList.filter((item) => ['single-text', 'select', 'radio-btns'].includes(item.key) && ['select', 'radio-btns'].includes(item.com_data.type) && item.com_data.show_hidden_list.length > 0);
    const list_map = list.map((item) => ({ id: item.id, list: item.com_data.show_hidden_list }));
    return children_list = childrenList.filter((item) => {
        // 优先判断是否启用
        if (item.is_enable !== '1') return false;

        if (list_map.length === 0) return true;
        // 将所有的内容的组件进行筛选
        const isShownByRule = list_map.some((list_item) => {
            const targetComponent = componentMap.get(list_item.id);
            // 判断显隐规则对应的组件是否存在
            if (!targetComponent) return false;
            return list_item.list.some((hidden_item) => {
                // 判断当前组件是否在显隐规则中，如果不在，直接显示，否则的话判断值是否存在
                if (hidden_item.is_show.includes(item.id)) {
                    if (type == 'all') {
                        // 判断所有的是否满足条件
                        const data = data_list.filter((form_item) => form_item.some((data_item_list) => data_item_list.id == list_item.id && data_item_list.com_data.form_value.includes(hidden_item.value)))
                        return data.length > 0;
                    } else {
                        // 判断是单个还是多个内容
                        if (index == null) {
                            return false;
                        } else {
                            // 否则判断当前组件的值是否存在
                            const data = data_list[index];
                            return data.some((data_item_list) => data_item_list.id == list_item.id && data_item_list.com_data.form_value.includes(hidden_item.value))
                        }
                    }
                } else {
                    return true;
                }
            });
        });
        return isShownByRule;
    });
}
/**
 * 计算左侧粘性定位样式
 * 
 * @param index - 当前元素的索引位置
 * @returns 返回CSS粘性定位样式字符串，若不符合条件则返回空字符串
*/
function ForminputSubformLeftSticky(index, childrenList = [], com_data) {
    // 从表单数据中获取是否启用固定和固定数量配置
    const { is_fixed = '0', fixed_num = 1 } = com_data?.computer || {};
    // 检查是否满足粘性定位条件：启用固定且索引在固定数量范围内
    if (is_fixed !== '1' || index >= fixed_num || fixed_num <= 0) {
        return '';
    }
    // 初始左侧偏移量：第一个元素为0，其他元素默认78px
    let left = index === 0 ? 0 : 78;
    
    // 计算当前元素之前的兄弟元素宽度总和作为偏移量
    if (index > 0) {
        for (let i = 1; i < index; i++) {
            left += childrenList[i - 1]?.com_data?.com_width || 0;
        }
    }
    
    // 生成粘性定位CSS样式
    return `position: sticky;left: ${left}px;`;
}
/**
 * 评分显示处理
 * @param parentNode 当前的父级节点
 * @param new_data 当前对应id的评分数据
 * @param index 当前评分的索引
 * @param type 触发方法的类型
 */
function ForminputScoreChange(parentNode, new_data, index, type = 'mouseover') {
    // 根据不同类型区分不同的状态
    if (new_data.com_data.score_type == 0) { 
        // 先移除所有的选中样式
        parentNode.find('.forminput-score-icon').removeClass('icon-pointed').addClass('icon-pointed-o').attr('style', 'color: #ccc;');
        // 添加选中样式
        for (let i = 0; i < index; i++) {
            parentNode.find('.forminput-score-icon').eq(i).removeClass('icon-pointed-o').addClass('icon-pointed').attr('style', 'color: ' + new_data.com_data.select_color + ';');
        }
    } else if (new_data.com_data.score_type == 1) { 
        // 先移除所有的选中样式
        parentNode.find('.forminput-score-icon').removeClass('icon-the-heart').addClass('icon-the-heart-o').attr('style', 'color: #ccc;');
        // 添加选中样式
        for (let i = 0; i < index; i++) {
            parentNode.find('.forminput-score-icon').eq(i).removeClass('icon-the-heart-o').addClass('icon-the-heart').attr('style', 'color: ' + new_data.com_data.select_color + ';');
        }
    } else if (type == 'click') {
        // 移除所有选中样式
        parentNode.find('.forminput-score-icon').attr('style', 'color: #666;');
        // 为当前选中的添加选中样式
        parentNode.find('.forminput-score-icon').eq(index - 1).attr('style', 'color: ' + new_data.com_data.select_color + ';');
    }
}
// 监听数据变化
function ForminputDataChange(e) {
    // 获取父级的数据
    let parentNode = e.parent().parent();
    // 获取父级id
    let id = parentNode.attr('id');
    // 如果父级id为空，就从父级父级获取
    if (IsEmpty(id)) {
        id = parentNode.parent().attr('id');
    }
    if (!IsEmpty(id)) {
        if (id.indexOf('-') > -1) {
            id = parentNode.data('subform-id');
            if (IsEmpty(id)) {
                id = parentNode.parent().data('subform-id');
            }
        }
        // 数据发生变化时更新对应id的数据， 确保不会影响保存时的数据处理
        $forminput_data_list.forEach(item => {
            if (item.id == id && item.key != 'subform') {
                item.com_data.form_value = e.val();
                // 手机号做特殊校验
                if (item.key == 'phone') {
                    // 判断输入框内容是否为空,为空的话获取验证码取消
                    const { is_error = '0'} = ForminputHandlePhoneValidation(item.com_data);
                    if (!IsEmpty(e.val()) && is_error == '0') {
                        parentNode.find('.verify-submit').prop('disabled', false);
                        parentNode.find('.verify-submit').removeClass('forminput-btn-disabled').addClass('forminput-btn-primary');
                    } else {
                        parentNode.find('.verify-submit').prop('disabled', true);
                        parentNode.find('.verify-submit').removeClass('forminput-btn-primary').addClass('forminput-btn-disabled');
                    }
                }
            } else if (item.id == id && item.key == 'subform') {
                // 子表单数据更新
                let index = parentNode.data('index');
                let subform_id = parentNode.data('id');
                if (IsEmpty(subform_id)) {
                    index = parentNode.parent().data('index');
                    subform_id = parentNode.parent().data('id');
                }
                item.com_data.data_list[index].forEach(item => {
                    if (item.id == subform_id) {
                        item.com_data.form_value = e.val();
                    }
                });
            }
        });
        
    }
}
// 定义字段类型与检查参数的映射
const ForminputFieldCheckMap = {
    'address': { is_format: false, type: 'address' },
    'number': { is_format: true, type: 'number' },
    'checkbox': { is_format: true, type: 'checkbox' },
    'select-multi': { is_format: true, type: 'checkbox' },
    'date': { is_format: false, type: 'time' },
    'date-group': { is_format: false, type: 'time' },
    'single-text': { is_format: true, type: '' },
    'multi-text': { is_format: false, type: '' },
    'rich-text': { is_format: false, type: '' },
    'radio-btns': { is_format: false, type: 'radio' },
    'select': { is_format: false, type: 'select' },
    'pwd': { is_format: false, type: '' },
    'score': { is_format: false, type: 'score' },
    'upload-attachments': { is_format: false, type: 'upload' },
    'upload-img': { is_format: false, type: 'upload' },
    'upload-video': { is_format: false, type: 'upload' }
};

/**
 * 格式检查函数
 * @param data 待检查的数据对象
 */
function ForminputGetFormatChecks(data, form_value, is_format = false, type = '') {
    let is_error = '0';
    let error_text = '';
    // 判断是否是必填字段,并且没有值
    if (data.is_required == '1' && IsEmpty(form_value)) {
        // 是否报错显示
        is_error = '1';
        error_text = `${ ['select', 'checkbox', 'upload', 'time', 'address', 'score', 'radio'].includes(type) ? '必选' : '必填'}字段不能为空`;
    } else {
        if (is_format) {
            if (type == 'number') {
                // 数字组件的校验逻辑
                return ForminputNumberRangeHandle(data, form_value);
            } else if (type == 'checkbox') {
                // 复选框和复选下拉框的校验逻辑
                return ForminputCheckboxRangeHandle(data, form_value);
            } else {
                // 单行文本的校验逻辑
                // 对字段进行格式检查
                return ForminputGetFormatChecksV2(data.common_config, form_value);
            }
        }
    }
    return { is_error, error_text }
};
// 复选框和复选下拉框的校验逻辑
function ForminputCheckboxRangeHandle(data, form_value) {
    const { min_num = '', max_num = '' } = data;
    const length = form_value?.length || 0;
    const minNum = Number(min_num);
    const maxNum = Number(max_num);
    let is_error = '0'
    let error_text = ''
    if ((!IsEmpty(min_num) && length < minNum) || (!IsEmpty(max_num) && length > maxNum)) {
        // 是否报错显示
        is_error = '1';
        error_text = `请选择${min_num}~${max_num}项`;
    }
    return { is_error, error_text }
};
/**
 * 格式化数字字符串或数值
 * 此函数根据是否需要转换，将输入的数字字符串或数值格式化为带有逗号分隔的字符串
 * 如果不需要转换，则移除输入中的所有逗号
 *
 * @param num - 输入的数字字符串或数值
 * @param is_convert - 指示是否需要转换的布尔值
 * @returns 格式化后的数字字符串
 */
function ForminputFormatNumber(num, is_convert) {
    let new_num = num.replace(/[^0-9.,]/g, '');
    if (is_convert) {
        // 将输入转换为字符串形式以便处理
        const number = new_num.toString();
        // 使用正则表达式将整数部分每三位用逗号分隔
        const integerPart = number.split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        // 避免小数为空的时候也处理
        const decimalPart = number.split('.')[1] == null ? '' : '.' + number.split('.')[1];
        // 组合整数部分和小数部分
        return integerPart + decimalPart;
    } else {
        // 如果不需要转换，移除所有逗号并返回
        return new_num.toString().replace(/,/g, '');
    }
};
// 数字组件的校验逻辑
function ForminputNumberRangeHandle(data, form_value) {
    const { min_num = '', max_num = '', format = 'num' } = data;
    const num = Number(form_value);
    const minNum = Number(min_num);
    const maxNum = Number(max_num);
    let is_error = '0'
    let error_text = ''
    if ((!IsEmpty(min_num) && num < minNum) || (!IsEmpty(max_num) && num > maxNum)) {
        // 是否报错显示
        is_error = '1';
        error_text = `请输入${min_num}${format == 'num' ? '' : '%'}~${max_num}${format == 'num' ? '' : '%'}之间的数`;
    }
    return { is_error, error_text }
};

const ForminputTypeConfig = [
    { name: '手机号码', value: 'phone-number', check: /^1((3|4|5|6|7|8|9){1}\d{1})\d{8}$/ },
    { name: '电话号码', value: 'telephone-number', check: [/^0\d{0,3}-?\d{7,8}$/, /^1((3|4|5|6|7|8|9){1}\d{1})\d{8}$/] },
    { name: '邮政编码', value: 'postal-code', check: /^[1-9]\d{5}$/ },
    { name: '身份证号码', value: 'id-no', check: /^[1-9]\d{5}(18|19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])\d{3}(\d|X|x)$/ },
    { name: '邮箱', value: 'email', check: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
];

// 构建 Map 提升查找效率
const ForminputTypeConfigMap = new Map(ForminputTypeConfig.map((item) => [item.value, item]));

/**
 * 根据通用配置和给定值进行格式校验
 * 该函数用于检查输入值是否符合特定的格式要求，主要应用于用户输入验证
 *
 * @param common_config 通用配置对象，包含格式和错误信息的配置
 * @param value 需要进行格式校验的值
 */
function ForminputGetFormatChecksV2(common_config, value) {
    let is_error = '0';
    let error_text = '';
    // 检查值是否为空，如果为空则直接重置错误状态
    if (!IsEmpty(value)) {
        // 根据通用配置中的格式，从类型配置映射中获取对应的格式检查项
        const item = ForminputTypeConfigMap.get(common_config.format);
        // 如果找不到对应的格式检查项，则不进行后续操作
        if (!item) return { is_error, error_text };

        // 初始化验证状态为不通过
        let isValid = false;
        // 检查项可能是一个数组，包含多个正则表达式，循环遍历直到找到一个匹配的正则表达式
        if (Array.isArray(item.check)) {
            for (const regex of item.check) {
                // 如果当前正则表达式匹配成功，则标记验证状态为通过，并停止循环
                if (regex.test(value)) {
                    isValid = true;
                    break;
                }
            }
        } else {
            // 如果检查项不是一个数组，直接进行正则表达式匹配
            isValid = item.check.test(value);
        }

        // 根据验证结果更新通用配置对象的错误状态和错误信息
        if (isValid) {
            is_error = '0';
            error_text = '';
        } else {
            is_error = '1';
            error_text = item.value == 'telephone-number' ? `请输入正确的电话号码或手机号码格式` : `请输入正确的${item.name}格式`;
        }
    } else {
        // 如果值为空，重置错误状态
        is_error = '0';
        error_text = '';
    }
    return { is_error, error_text };
};

/**
 * 生成一个随机数学字符串。
 * @returns {string} 一个6位的36进制随机字符串。
 */
function ForminputGetMath() {
    // 通过Math.random()生成随机数，并转换为36进制的字符串
    let randomString = Math.random().toString(36);
    // 确保随机字符串至少有6位，因为substring(2)可能会使短于6位的字符串产生错误。
    // 如果字符串长度不足6位，通过padStart将其前面填充为0，直到长度达到6位。
    randomString = randomString.length >= 6 ? randomString : randomString.padStart(6, '0');
    // 截取掉随机字符串开头的'0.'部分，获得最终的6位随机字符串。
    return randomString.substring(2);
}
// 定义一组预定义的颜色数组，用于在各种场景中轻松引用这些颜色
// 这些颜色包括从白色到黑色的不同灰度，以及一些鲜艳的颜色，格式有十六进制、RGB、RGBA、HSV、HSL等
var ForminputPredefineColors = ['#eb5050', '#f0a800', '#46c26f', '#a2c204', '#00aed1', '#5865f5', '#c643e0', '#f0437d', '#fa8118', '#d6c504', '#00b899', '#6ac73c', '#2f7deb', '#7e47eb', '#d941c0', '#485970', '#f9cbcb', '#fbe5b3', '#c8edd4', '#e3edb4', '#b3e7f1', '#cdd1fc', '#eec7f6', '#fbc7d8', '#fed9ba', '#f3eeb4', '#b3eae0', '#d2eec5', '#c1d8f9', '#d8c8f9', '#f4c6ec', '#c8cdd4'];

function ForminputColorChange(length) {
    // 如果大于这个大小，就按照多余的数量来获取颜色
    if (length > ForminputPredefineColors.length) {
        const new_length = ForminputPredefineColors.length - length;
        if (new_length > ForminputPredefineColors.length) {
            ForminputColorChange(new_length);
        } else {
            return ForminputPredefineColors[length];
        }
    } else {
        return ForminputPredefineColors[length];
    }
};
// 获取手机验证码
function ForminputGetVerification(verify) { 
    
    let forminput_this = $('#' + $forminput_phone_id).find('.verify-submit');
    // 验证码时间间隔
    var time_count = parseInt(forminput_this.data('time'));
    
    // 按钮交互
    forminput_this.button('loading');
    if($forminput_phone_data.com_data.is_img_sms_verification == '1')
    {
        $forminput_verify_win_submit.button('loading');
    }

    // 发送验证码
    $.ajax({
        url: RequestUrlHandle(forminput_this.data('url')),
        type: 'POST',
        data: {forminput_id: $forminput_id, forminput_item_id: $forminput_phone_id, accounts: $forminput_phone_data.com_data.form_value, verify: verify,  type: 'sms' },
        dataType: 'json',
        success: function(result)
        {
            if(result.code == 0)
            {
                var intervalid = setInterval(function()
                {
                    if(time_count == 0)
                    {
                        forminput_this.button('reset');
                        if($forminput_phone_data.com_data.is_img_sms_verification == '1')
                        {
                            $forminput_verify_win_submit.button('reset');
                        }
                        forminput_this.text(forminput_this.data('text'));
                        $forminput_verify.val('');
                        clearInterval(intervalid);
                    } else {
                        var send_msg = forminput_this.data('send-text').replace(/{time}/, time_count--);
                        forminput_this.text(send_msg);
                    }
                }, 1000);
                if($forminput_user_verify_win.length > 0)
                {
                    $forminput_user_verify_win.modal('close');
                }
            } else {
                forminput_this.button('reset');
                if($forminput_phone_data.com_data.is_img_sms_verification == '1')
                {
                    $forminput_verify_win_submit.button('reset');
                    $forminput_verify_img.trigger("click");
                }
                Prompt(result.msg);
            }
        },
        error: function(xhr, type)
        {
            forminput_this.button('reset');
            if($forminput_phone_data.com_data.is_img_sms_verification == '1')
            {
                $forminput_verify_win_submit.button('reset');
            }
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

function ForminputSetSubformData(subform_data) {
    let subform_html = '';
    if (subform_data && subform_data.com_data.data_list.length > 0) {
        subform_data.com_data.data_list.forEach((item, index) => {
            subform_html += ForminputSetOneLineData(subform_data, index, item);
        })
    }
    return subform_html;
}
// 子表单一行数据的添加
function ForminputSetOneLineData(subform_data, index, one_line_list) {
    // 子表单数字显示
    subform_html = ForminputSetSubformNum(subform_data, index);
    // 子表单详细数据
    subform_html += ForminputSetSubformOneLineData(subform_data, index, one_line_list);
    // 子表单详细数据结束
    subform_html += '</div>'
    return subform_html;
}
// 子表单数字显示
function ForminputSetSubformNum(subform_data, index) {
    return `<div class="table-row am-flex-row">
                <div class="cell-num am-flex-row align-items-center justify-content-center shrink re" style="` + (subform_data.com_data.computer.is_fixed == '1' ? 'position: sticky;left: 0;z-index: ' + (9999 - index) + ';' : '') + `">
                    <div class="row-num am-flex-row align-items-center justify-content-center">` + (index + 1) + `</div>
                    <div class="subform-num-checkbox">
                        <input type="checkbox" data-index="`+ index +`" data-id="` + subform_data.id + `" class="subform-index-checkbox">
                    </div>
                    <div class="operate am-flex-row align-items-center justify-content-center forminput-gap-5">
                        <!-- <i data-id="` + subform_data.id + `" data-index="` + index + `" class="iconfont icon-enlarge operate-icon operate-enlarge"></i> -->
                        <i data-id="` + subform_data.id + `" data-index="` + index + `" class="iconfont icon-delete operate-icon operate-delete"></i>
                        <div id="` + subform_data.id + `-` + index + `-selected" class="subform-dropdown am-dropdown" data-am-dropdown>
                            <div data-id="` + subform_data.id + `-`+ index +`-selected" class="selected-click am-dropdown-toggle">
                                <i class="iconfont icon-more-o operate-icon"></i>
                            </div>
                            <div class="am-dropdown-content am-radius select-fixed-position">
                                <div class="am-flex-col forminput-gap-10">
                                    <div data-id="` + subform_data.id + `" data-index="` + index + `" data-type="copy" class="am-dropdown-item am-cursor-pointer subform-dropdown-item">复制到下一行</div>
                                    <div data-id="` + subform_data.id + `" data-index="` + index + `" data-type="copy_last" class="am-dropdown-item am-cursor-pointer subform-dropdown-item">复制到最后一行</div>
                                    <div data-id="` + subform_data.id + `" data-index="` + index + `" data-type="insert_top" class="am-dropdown-item am-cursor-pointer subform-dropdown-item">向上插入一行</div>
                                    <div data-id="` + subform_data.id + `" data-index="` + index + `" data-type="insert_bottom" class="am-dropdown-item am-cursor-pointer subform-dropdown-item">向下插入一行</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
}
// 子表单详细数据
function ForminputSetSubformOneLineData(subform_data, index, item_data_list) {
    let subform_html = '';
    // 字体大小显示
    const filed_title_size_type = $forminput_data_config.style_settings.computer.filed_title_size_type;
    // 下拉框和复选单选框的样式
    const item_multicolour_class = (filed_title_size_type == 'big') ? 'item-multicolour-big' : ((filed_title_size_type == 'middle') ? 'item-multicolour' : 'item-multicolour-small');
    // 输入框的高度和不可输入区域的字体大小
    const item_content_class = filed_title_size_type == 'big' ? 'item-content-big' : (filed_title_size_type == 'middle' ? 'item-content' : 'item-content-small');
    // 输入框字体大小
    const item_size = filed_title_size_type == 'big' ? 'item-big-size' : (filed_title_size_type == 'middle' ? 'item-size' : '');
    // 报错描述
    const not_fill_in_error = $('.forminput-data-content').attr('data-not-fill-in-error') || '';
    const not_choice_error = $('.forminput-data-content').attr('data-not-choice-error') || '';
    // 请选择
    const please_select_tips = $('.forminput-data-content').attr('data-please-select-tips') || '';
    // 数据显示处理
    if (item_data_list.length > 0) {
        item_data_list.forEach((item_data, item_index) => {
            subform_html += `<div id="` + item_data.id + `-` + index +`" data-index="`+ index +`" data-subform-id="`+ subform_data.id +`" data-id="`+ item_data.id +`" class="am-flex-row align-items-center subform-cell">`;      
            if (['single-text', 'select', 'radio-btns'].includes(item_data.key) && item_data.com_data.type == 'single-text') {
                subform_html += `<div class="forminput-data-item-content ` + item_content_class +`" style="` + item_data.common_style + `">
                            <input 
                                type="text" 
                                name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" 
                                value="`+ item_data.com_data.form_value +`" 
                                `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_fill_in_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +`
                                placeholder="`+ item_data.com_data.placeholder +`" 
                                `+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.min_num)) ? 'minlength="'+ item_data.com_data.min_num +'"' : '') +`
                                `+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.max_num)) ? 'maxlength="'+ item_data.com_data.max_num +'"' : '') +`
                                class="forminput-input forminput-no-border forminput-w forminput-h ` + item_size + `"
                            />
                            `+ ((item_data.com_data.is_limit_num == '1' && item_data.com_data.max_num > 0) ? `<div class="forminput-cr-gray"><span class="limit-num">`+ item_data.com_data.form_value.length +`</span>/` + item_data.com_data.max_num + `</div>` : ``) +`
                        </div>`;
            } else if (item_data.key == 'number') { 
                subform_html += `<div class="forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                            `+ (item_data.com_data.is_display_money == '1' ? `<div class="forminput-cr-gray">` + item_data.com_data.money_sign + `</div>` : ``) +`        
                            <input type="text" 
                                name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" 
                                value="`+ item_data.com_data.form_value +`" 
                                `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_fill_in_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +`
                                placeholder="`+ item_data.com_data.placeholder +`" 
                                class="forminput-input forminput-no-border forminput-w forminput-h ` + item_size + `"
                            />
                            `+ (item_data.com_data.format == 'percentage' ? `<span class="forminput-percentage">%</span>` : ``) +`
                        </div>`;
            } else if (item_data.key == 'multi-text') {
                subform_html += `<div class="forminput-data-item-content `+ item_content_class +` forminput-re" style="`+ item_data.common_style +`height:auto;">
                            <textarea
                                rows="6" 
                                name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" 
                                `+ ( item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_fill_in_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : '') +`
                                placeholder="`+ item_data.com_data.placeholder +`" 
                                `+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.min_num)) ? 'minlength="'+ item_data.com_data.min_num +'"' : '') +`
                                `+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.max_num)) ? 'maxlength="'+ item_data.com_data.max_num +'"' : '') +` 
                                class="forminput-input forminput-no-border forminput-w ` + item_size + `"
                            >`+ (!IsEmpty(item_data.com_data.form_value) ? item_data.com_data.form_value : '') +`</textarea>
                            `+ (item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.max_num) ? `<div class="forminput-cr-gray forminput-textarea"><span class="limit-num">`+ item_data.com_data.form_value.length +`</span>/`+ item_data.com_data.max_num +`</div>` : '') +`
                        </div>`;
            } else if (item_data.key == 'pwd') { 
                subform_html += `<div class="forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                            `+ (!IsEmpty(item_data.com_data.icon_name) ? `<div class="iconfont icon-`+ item_data.com_data.icon_name +` forminput-cr-gray"></div>` : ``) +`
                            <input type="password" autocomplete="off"
                                name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" 
                                value="`+ item_data.com_data.form_value +`" 
                                `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_fill_in_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +`
                                placeholder="`+ item_data.com_data.placeholder +`" 
                                class="forminput-input forminput-no-border forminput-w forminput-h ` + item_size + `"
                            />
                            <div class="iconfont icon-eye forminput-cr-gray forminput-password"></div>
                        </div>`;
            } else if (item_data.key == 'date-group') { 
                subform_html += `<div class="forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                        `+ (['option1', 'option2'].includes(item_data.com_data.date_type) ? 
                            `<div class="form-table-search-section form-table-search-time am-flex-row forminput-w forminput-h align-items-center">
                                <input type="text" `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_choice_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +` autocomplete="off" class="am-form-field am-input-sm am-radius am-inline-block Wdate forminput-no-border forminput-w forminput-h forminput-input-text-center forminput-input-date-group-start `+ item_size +`" id="form-table-search-time-start-`+ item_data.id +`-`+ index +`" name="` + subform_data.form_name + `\[` + index + `\]\[` + item_data.form_name + `_start\]" value="`+ (!IsEmpty(item_data.com_data.form_value) ? item_data.com_data.form_value[0] : '') +`" placeholder="`+(!IsEmpty(item_data.com_data.start_placeholder) ? item_data.com_data.start_placeholder : '')+`" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'`+ item_data.com_data.format +`',maxDate:'#F{$dp.$D(\\'form-table-search-time-end-`+ item_data.id +`-`+ index +`\\',{d:-1});}'})" autocomplete="off" />
                                <span class="am-flex-row align-items-center forminput-divider">-</span>
                                <input type="text" `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_choice_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +` autocomplete="off" class="am-form-field am-input-sm am-radius am-inline-block Wdate forminput-no-border forminput-w forminput-h forminput-input-text-center forminput-input-date-group-end `+ item_size +`" id="form-table-search-time-end-`+ item_data.id +`-`+ index +`" name="` + subform_data.form_name + `\[` + index + `\]\[` + item_data.form_name + `_end\]" value="`+ (!IsEmpty(item_data.com_data.form_value) ? item_data.com_data.form_value[1] : '') +`" placeholder="`+(!IsEmpty(item_data.com_data.end_placeholder) ? item_data.com_data.end_placeholder : '')+`" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'`+ item_data.com_data.format +`',minDate:'#F{$dp.$D(\\'form-table-search-time-start-`+ item_data.id +`-`+ index +`\\',{d:+1});}'})" autocomplete="off" />
                            </div>` : 
                            `<div class="form-table-search-section form-table-search-time am-flex-row forminput-w forminput-h align-items-center">
                                <input type="text" `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_choice_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +` autocomplete="off" class="am-form-field am-input-sm am-radius am-inline-block Wdate forminput-no-border forminput-w forminput-h forminput-input-text-center forminput-input-date-group-start `+ item_size +`" id="form-table-search-time-start-`+ item_data.id +`-`+ index +`" name="` + subform_data.form_name + `\[` + index + `\]\[` + item_data.form_name + `_start\]" value="`+ (!IsEmpty(item_data.com_data.form_value) ? item_data.com_data.form_value[0] : '') +`" placeholder="`+(!IsEmpty(item_data.com_data.start_placeholder) ? item_data.com_data.start_placeholder : '')+`" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'`+ item_data.com_data.format +`',maxDate:'#F{$dp.$D(\\'form-table-search-time-end-`+ item_data.id +`-`+ index +`\\');}'})" autocomplete="off" />
                                <span class="am-flex-row align-items-center forminput-divider">-</span>
                                <input type="text" `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_choice_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +` autocomplete="off" class="am-form-field am-input-sm am-radius am-inline-block Wdate forminput-no-border forminput-w forminput-h forminput-input-text-center forminput-input-date-group-end `+ item_size +`" id="form-table-search-time-end-`+ item_data.id +`-`+ index +`" name="` + subform_data.form_name + `\[` + index + `\]\[` + item_data.form_name + `_end\]" value="`+ (!IsEmpty(item_data.com_data.form_value) ? item_data.com_data.form_value[1] : '') +`" placeholder="`+(!IsEmpty(item_data.com_data.end_placeholder) ? item_data.com_data.end_placeholder : '')+`" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'`+ item_data.com_data.format +`',minDate:'#F{$dp.$D(\\'form-table-search-time-start-`+ item_data.id +`-`+ index +`\\');}'})" autocomplete="off" />
                            </div>` ) +`
                        <i class="iconfont icon-`+ item_data.com_data.icon_name +`" style="color: 333;"></i>
                    </div>`
            } else if (item_data.key == 'date') {
                subform_html += `<div class="forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                        <input type="text" `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_choice_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +`  autocomplete="off" class="forminput-input-date am-form-field am-input-sm am-radius am-inline-block Wdate forminput-no-border forminput-w forminput-h `+ item_size +`" id="form-table-search-time-`+ item_data.id +`-`+ index +`" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]"  value="`+ (!IsEmpty(item_data.com_data.form_value) ? item_data.com_data.form_value : '') +`" placeholder="`+(!IsEmpty(item_data.com_data.placeholder) ? item_data.com_data.placeholder : '')+`" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'`+ item_data.com_data.format +`'})" autocomplete="off" />
                        <i class="iconfont icon-`+ item_data.com_data.icon_name +`" style="color: 333;"></i>
                    </div>`;
            } else if (item_data.key == 'score') {
                subform_html += `<div class="am-flex-row align-items-center forminput-gap-10 forminput-score am-flex-wrap">`
                // 评分样式
                const array = Array(item_data.com_data.total).fill(0);
                for (let index1 = 0; index1 < array.length; index1++) {
                    if (item_data.com_data.score_type == 0) {
                        subform_html += `<i data-index="`+ index1 +`" class="iconfont `+ ((index1 < item_data.com_data.form_value) ? 'icon-pointed' : 'icon-pointed-o')+` forminput-score-icon" style="color: `+ ( index1 < item_data.com_data.form_value ? item_data.com_data.select_color : '#ccc') +`"></i>`
                    } else if (item_data.com_data.score_type == 1) {
                        subform_html += `<i data-index="`+ index1 +`" class="iconfont `+ ((index1 < item_data.com_data.form_value) ? 'icon-the-heart' : 'icon-the-heart-o')+` forminput-score-icon" style="color: `+ ( index1 < item_data.com_data.form_value ? item_data.com_data.select_color : '#ccc') +`"></i>`
                    } else if (item_data.com_data.score_type == 2) {
                        subform_html += `<span data-index="`+ index1 +`" class="nowrap forminput-score-icon" style="color: `+ ( index1 == (item_data.com_data.form_value - 1) ? item_data.com_data.select_color : '#666') +`;">`+ (index1 + 1)+`分</span>`
                    }
                }
                subform_html += `<input type="text" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" class="am-input am-input-sm forminput-visiable-hidden" value="`+ item_data.com_data.form_value +`">
                    </div>`;
            } else if (item_data.key == 'text') {
                subform_html += `<div class="item-small-size `+ item_size +`" style="`+ item_data.common_style +`">`+ item_data.com_data.form_value +`</div>`;
            } else if (item_data.key == 'img') {
                subform_html += `<div>`;
                if (IsEmpty(item_data.com_data.img_src)) {
                    subform_html += `<div class="item-img-empty forminput-w forminput-h">
                                <img src="`+ __attachment_host__ +`/static/form_input/images/empty.png" style="width: 100%;height: 100%;object-fit: cover;"/>
                            </div>`;
                } else {
                    subform_html += `<img src="`+ item_data.com_data.img_src[0].url +`" data-rel="`+ item_data.com_data.img_src[0].url +`" class="common-annex-view-event" style="width: 100%;height: 100%;object-fit: cover;" data-am-pureviewed="1">`
                }
                subform_html += `</div>`;
            } else if (item_data.key == 'video') {
                subform_html += `<div>`;
                if (!IsEmpty(item_data.com_data.video)) {
                    subform_html += `<video src="`+ item_data.com_data.video[0].url +`" `+ (!IsEmpty(item_data.com_data.video_img) ? `poster="`+ item_data.com_data.video_img[0].url +`` : '') +` controls="controls" style="width: 100%;height: 100%;object-fit: contain;"></video>`;
                }
                subform_html += `</div>`;
            } else if (item_data.key == 'position') {
                subform_html += `<div class="forminput-data-item-disabled `+ item_content_class +` justify-content-center forminput-cr-gray" style="`+ item_data.common_style +`">
                            <div class="iconfont icon-address"></div>
                            <div class="am-text-truncate">请在移动端打开表单进行定位</div>
                        </div>`;
            } else if (item_data.key == 'attachments') {
                if (!IsEmpty(item_data.com_data.file)) {
                    subform_html += `<div class="am-flex-row align-items-center forminput-gap-10">
                                <span class="file-title am-text-truncate" style="width: auto;">`+ item_data.com_data.file[0].original +`</span>
                                <div class="forminput-oprate">
                                    <div class="icon attachments-click"><i class="iconfont icon-copy"></i></div>
                                    <span class="divider"></span>
                                    <div class="icon attachments-click"><i class="iconfont icon-download-b-line"></i></div>
                                </div>
                            </div>`
                } else {
                    subform_html += `<span class="file-title" style="color:#606266;font-size:14px;width: auto;">暂无文件</span>`
                }
            } else if (item_data.key == 'phone') {
                subform_html += `<div class="am-flex-col forminput-gap-10">
                            <div class="forminput-data-item-content am-flex-row `+ item_content_class +`" style="`+ item_data.common_style +`">
                                <i class="iconfont icon-`+ item_data.com_data.icon_name +`" style="color: 666;"></i>
                                <input type="text" 
                                    name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" 
                                    value="`+ item_data.com_data.form_value +`" 
                                    `+ (item_data.com_data.is_required == '1' ? `required="required" data-validation-message="`+ not_fill_in_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : '') +`
                                    placeholder="`+ item_data.com_data.placeholder +`" 
                                    class="forminput-input forminput-no-border forminput-w forminput-h `+ item_size +`"
                                />
                            </div>
                        </div>`;
            } else if (item_data.key == 'upload-img') {
                subform_html += `<div class="am-flex-col forminput-gap-10">
                            <button class="plug-file-upload-submit am-flex-row align-items-center justify-content-center `+ item_content_class +`" style="`+ item_data.common_style +`" data-dialog-type="images" data-is-direct="1" data-back-fun="upload_event" data-back-mark="`+ item_data.id +`-`+ index +`_`+ subform_data.id +`">
                                <div class="upload-text am-text-truncate"><span style="color: #2A94FF;">请选择</span>（点击选择图片，`+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.limit)) ? `最多`+ item_data.com_data.limit +`张` : '') +``+ ((item_data.com_data.is_limit_size == '1' && !IsEmpty(item_data.com_data.upload_size)) ? `、最多`+ item_data.com_data.upload_size +`MB` : ``) +`)</div>
                            </button>`
                if (!IsEmpty(item_data.com_data.form_value)) {
                    subform_html += `<div class="file-upload-view-list am-flex-row am-flex-wrap forminput-gap-10">`
                    item_data.com_data.form_value.forEach((form_value_item, form_value_index) => {
                        subform_html += ForminputFileUploadImg(form_value_index, form_value_item, subform_data, item_data.id, index);   
                    });
                    subform_html += `</div>
                                    <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" value="`+ encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(item_data.com_data.form_value)))) +`" />`
                } else {
                    subform_html += `<div class="file-upload-view-list am-flex-row am-flex-wrap forminput-gap-10 file-upload-hidden">
                            </div>
                            <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" value="" />`
                }
                subform_html += `</div>`;
            } else if (item_data.key == 'upload-video') {
                subform_html += `<div class="am-flex-col forminput-gap-10">
                            <button class="plug-file-upload-submit am-flex-row align-items-center justify-content-center `+ item_content_class +`" style="`+ item_data.common_style +`" data-dialog-type="video" data-is-direct="1" data-back-fun="upload_event" data-back-mark="`+ item_data.id +`-`+ index +`_`+ subform_data.id +`">
                                <div class="upload-text am-text-truncate"><span style="color: #2A94FF;">请选择</span>（点击选择视频，`+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.limit)) ? `最多`+ item_data.com_data.limit +`个` : '') +``+ ((item_data.com_data.is_limit_size == '1' && !IsEmpty(item_data.com_data.upload_size)) ? `、最多`+ item_data.com_data.upload_size +`MB` : ``) +`)</div>
                            </button>`
                if (!IsEmpty(item_data.com_data.form_value)) {
                    subform_html += `<div class="file-upload-view-list am-flex-row am-flex-wrap forminput-gap-10">`
                    item_data.com_data.form_value.forEach((form_value_item, form_value_index) => {
                        subform_html += ForminputFileUploadVideo(form_value_index, form_value_item, subform_data, item_data.id, index);     
                    });
                    subform_html += `</div>
                                    <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" value="`+ encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(item_data.com_data.form_value)))) +`" />`
                } else {
                    subform_html += `<div class="file-upload-view-list am-flex-row am-flex-wrap forminput-gap-10 file-upload-hidden">
                            </div>
                            <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" value="" />`
                }
                subform_html += `</div>`;
            } else if (item_data.key == 'upload-attachments') {
                subform_html += `<div class="am-flex-col forminput-gap-10">
                            <button class="plug-file-upload-submit am-flex-row align-items-center justify-content-center `+ item_content_class +`" style="`+ item_data.common_style +`" data-dialog-type="file" data-is-direct="1" data-back-fun="upload_event" data-back-mark="`+ item_data.id +`-`+ index +`_`+ subform_data.id +`">
                                <div class="upload-text am-text-truncate"><span style="color: #2A94FF;">请选择</span>（点击选择文件，`+ ((item_data.com_data.is_limit_num == '1' && !IsEmpty(item_data.com_data.limit)) ? `最多`+ item_data.com_data.limit +`个` : '') +``+ ((item_data.com_data.is_limit_size == '1' && !IsEmpty(item_data.com_data.upload_size)) ? `、最多`+ item_data.com_data.upload_size +`MB` : ``) +`)</div>
                            </button>`
                if (!IsEmpty(item_data.com_data.form_value)) {
                    subform_html += `<div class="file-upload-view-list am-flex-row am-flex-wrap forminput-gap-10">`
                    item_data.com_data.form_value.forEach((form_value_item, form_value_index) => {
                        subform_html += ForminputFileUploadAttachments(form_value_index, form_value_item, subform_data, item_data.id, index); 
                    });
                    subform_html += `</div>
                                    <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" value="`+ encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(item_data.com_data.form_value)))) +`" />`
                } else {
                    subform_html += `<div class="file-upload-view-list am-flex-row am-flex-wrap forminput-gap-10 file-upload-hidden">
                            </div>
                            <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" value="" />`
                }
                subform_html += `</div>`;
            } else if (['single-text', 'select', 'radio-btns'].includes(item_data.key) && ['radio-btns', 'select'].includes(item_data.com_data.type)) {
                subform_html += `<div id="`+ subform_data.id + `-`+ index +`-selected" class="am-selected subform-dropdown am-dropdown forminput-w am-flex-row" data-am-dropdown>
                            <select name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" class="forminput-visiable-hidden">`
                // 下拉选中的参数数据
                let select_html = `<option value="">请选择</option>`;
                // 选中并显示在页面上的数据
                let selected_html = ``;
                // 下拉框中显示的数据
                let option_html = `<li class="forminput-show-hidden-click am-selected-item am-flex-row align-items-center `+ (IsEmpty(item_data.com_data.form_value) ? `am-checked` : ``) +`" data-index="0" data-group="0" data-value="">
                            <span class="`+ item_multicolour_class +` am-selected-text am-color-grey am-padding-0">`+ please_select_tips +`</span>
                            </li>`;
                if (item_data.com_data.option_list.length > 0) {
                        item_data.com_data.option_list.forEach((option_item, option_index) => {
                        select_html += `<option style="color: #000" value="`+ option_item.value +`" `+ (item_data.com_data.form_value === option_item.value ? `selected` : ``) + `>
                                    `+ option_item.name +`
                                </option>`;
                        if (option_item.value == item_data.com_data.form_value) {
                            selected_html += `<span class="`+ item_multicolour_class +`" style="`+ (item_data.com_data.is_multicolour == '1' ? (`background: `+ option_item.color +`;color:`+ ((option_item.is_other && option_item.is_other == '1') ? `#141E31` : `#fff`) +`;border-radius:4px;`) : `padding: 0;`) +`">`+ option_item.name +`</span>`;
                        }
                        option_html += `<li value="`+ option_item.value +`" class="forminput-show-hidden-click am-selected-item am-flex-row align-items-center `+ (option_item.value == item_data.com_data.form_value ? `am-checked` : ``) +`" data-index="`+ option_index +`" data-group="0" data-value="`+ option_item.value +`">
                            <span class="`+ item_multicolour_class +` am-selected-text" style="`+ (item_data.com_data.is_multicolour == '1' ? (`background: `+ option_item.color +`;color:`+ ((option_item.is_other && option_item.is_other == '1') ? `#141E31` : `#fff`) +`;border-radius:4px;`) : `padding: 0;`) +`">`+ option_item.name +`</span>
                            <i class="am-icon-check"></i></li>`;
                    })
                }
                subform_html +=  select_html;              
                subform_html += `</select>
                        <div data-id="`+ subform_data.id + `-`+ index +`-selected" class="am-flex-row selected-click forminput-w forminput-h">
                            <button type="button" class="am-selected-btn am-btn am-dropdown-toggle am-flex-row align-items-center forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                                <div class="am-selected-status am-fl am-flex-row align-items-center forminput-gap-10">`;
                if (IsEmpty(item_data.com_data.form_value)) {
                    subform_html += `<span class="forminput-cr-gray">`+ item_data.com_data.placeholder +`</span>`;
                }  else {
                    subform_html +=  selected_html;                         
                }
                subform_html += `       </div>
                                <i class="am-selected-icon am-icon-caret-down"></i>
                            </button>  
                        </div>
                        <div id="dropdown-`+ item_data.id +`-`+ index +`" data-subform-id="`+ subform_data.id +`" data-id="`+ item_data.id +`" data-index="`+ index +`" class="am-selected-content am-dropdown-content am-radius select-fixed-position" style="min-width: 354px;">
                                <ul class="am-selected-list">`;
                subform_html +=  option_html;           
                subform_html += `       </ul>
                            </div>
                        </div>`               
            } else if (['checkbox', 'select-multi'].includes(item_data.key)) {
                subform_html += `<div id="`+ subform_data.id + `-`+ index +`-selected" class="am-selected subform-dropdown am-dropdown forminput-w am-flex-row" data-am-dropdown> 
                            <select name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`\]" multiple class="forminput-visiable-hidden forminput-select-multi">`
                // 下拉选中的参数数据
                let select_html = ``;
                // 选中并显示在页面上的数据
                let selected_html = ``;
                // 下拉框中显示的数据
                let option_html = ``;
                if (item_data.com_data.option_list.length > 0) {
                    const option_list = item_data.com_data.option_list.concat(item_data.com_data.custom_option_list);
                    option_list.forEach((option_item, option_index) => {
                        select_html += `<option value="`+ option_item.value +`" `+ ((!IsEmpty(item_data.com_data.form_value) && item_data.com_data.form_value.includes(option_item.value)) ? `selected` : ``) +`>`+ option_item.name +`</option>`;
                        if (!IsEmpty(item_data.com_data.form_value) && item_data.com_data.form_value.includes(option_item.value)) {
                            selected_html += `<span class="`+ item_multicolour_class +`" style="`+ (item_data.com_data.is_multicolour == '1' ? (`background: `+ option_item.color +`;color:`+ ((option_item.is_other && option_item.is_other == '1') ? `#141E31` : `#fff`) +`;border-radius:4px;`) : `padding: 0;`) +`">`+ option_item.name +`</span>`;
                        }
                        const close_html = `<i data-value="`+ option_item.value +`" data-id="`+ subform_data.id +`" data-subform-option-id="`+ item_data.id +`" data-subform-option-index="`+ item_index +`" class="add-option-icon iconfont icon-close `+ (item_data.com_data.is_multicolour == '1' ? '' : 'forminput-cr-gray')+`"></i>`;
                        option_html += `<li value="`+ option_item.value +`" class="forminput-show-hidden-click am-selected-item am-flex-row align-items-center `+ (option_item.value == item_data.com_data.form_value ? `am-checked` : ``) +`" data-index="`+ option_index +`" data-group="0" data-value="`+ option_item.value +`">
                            <div class="`+ item_multicolour_class +` am-selected-text am-flex-row align-items-center forminput-gap-5" style="`+ (item_data.com_data.is_multicolour == '1' ? (`background: `+ option_item.color +`;color:`+ ((option_item.is_other && option_item.is_other == '1') ? `#141E31` : `#fff`) +`;border-radius:4px;`) : `padding: 0;`) +`">`+ option_item.name +``+ (option_item.type && option_item.type == 'add' ? close_html : '') +`</div>
                            <i class="am-icon-check"></i></li>`;
                    })
                }
                subform_html += select_html;
                subform_html += `</select>
                            <div data-id="`+ subform_data.id + `-`+ index +`-selected" class="am-flex-row selected-click forminput-w forminput-h">
                                <button type="button" class="am-selected-btn am-btn am-dropdown-toggle am-flex-row align-items-center forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                                    <div class="am-selected-status am-fl am-flex-row align-items-center `+ (item_data.com_data.is_multicolour == '1' ? `forminput-gap-10` : ``)+`">`
                if (IsEmpty(item_data.com_data.form_value)) {
                    subform_html += `<span class="forminput-cr-gray">`+ item_data.com_data.placeholder +`</span>`;
                }  else {
                    subform_html +=  selected_html;                         
                }
                subform_html += `   </div>
                                <i class="am-selected-icon am-icon-caret-down"></i>
                                </button> 
                            </div>
                            <div id="dropdown-`+ item_data.id +`-`+ index +`" data-subform-id="`+ subform_data.id +`" data-id="`+ item_data.id +`" data-index="`+ index +`" class="am-selected-content am-dropdown-content am-radius select-fixed-position" style="min-width: 354px;">
                                <ul class="am-selected-list">`
                subform_html += option_html;
                subform_html += `</ul>`
                if (item_data.com_data.is_add_option == '1') {
                    subform_html += `<div class="add-option am-flex-row align-items-center forminput-gap-10 forminput-cursor-p">
                                        <i class="iconfont icon-add forminput-cr-blue"></i>
                                        <span class="size-14 forminput-cr-blue">添加选项</span>
                                <input type="hidden" name="`+ subform_data.form_name +`[`+ index +`]\[`+ item_data.form_name +`_custom_option_list\]" value="`+ encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(item_data.com_data.custom_option_list)))) +`" class="forminput-custom-option-list" placeholder="请输入选项名称">
                                </div>`
                }
               subform_html += `
                            </div>
                        </div>`;  
            } else if (item_data.key == 'address') {
                subform_html += `<div class="region-linkage" data-url="`+ $forminput_data_address_url +`" data-code-url="`+ $forminput_data_address_code_url +`">
                            <div class="am-cascader">
                                <div class="selected-click am-cascader-suffix forminput-reactive">
                                    <div class="forminput-data-item-content `+ item_content_class +`" style="`+ item_data.common_style +`">
                                        <input type="text" readonly="readonly" name="province_city_county" autocomplete="off" placeholder="`+ item_data.com_data.placeholder +`"
                                        value="`+ (!IsEmpty(item_data.com_data.province_name) ? item_data.com_data.province_name : '') + (!IsEmpty(item_data.com_data.city_name) ? `-` + item_data.com_data.city_name : '') + (!IsEmpty(item_data.com_data.county_name) ? `-` + item_data.com_data.county_name : '') +`"
                                        class="am-input-inner forminput-no-border forminput-w forminput-h `+ item_size +`"  `+ (item_data.com_data.required == '1' ? `required data-validation-message="`+ not_choice_error +`(` + subform_data.com_data.title + `-` + item_data.com_data.title + `)"` : ``) +`>
                                        <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`_province\]" value="`+ (!IsEmpty(item_data.com_data.form_value[0]) ? item_data.com_data.form_value[0] : '') +`">
                                        <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`_city\]" value="`+ (!IsEmpty(item_data.com_data.form_value[1]) ? item_data.com_data.form_value[1] : '') +`">
                                        <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`_county\]" value="`+ (!IsEmpty(item_data.com_data.form_value[2]) ? item_data.com_data.form_value[2] : '') +`">
                                        <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`_province_name\]" value="`+ (!IsEmpty(item_data.com_data.province_name) ? item_data.com_data.province_name : '') +`">
                                        <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`_city_name\]" value="`+ (!IsEmpty(item_data.com_data.city_name) ? item_data.com_data.city_name : '') +`">
                                        <input type="hidden" name="`+ subform_data.form_name +`\[`+ index +`\]\[`+ item_data.form_name +`_county_name\]" value="`+ (!IsEmpty(item_data.com_data.county_name) ? item_data.com_data.county_name : '') +`">
                                        <span class="am-input-suffix">
                                            <i class="iconfont icon-angle-down"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-cascader-dropdown select-fixed-position">
                                    <div id="`+ item_data.id +`-cascader-panel-`+ index +`" data-index="`+ index +`" data-subform-id="`+ subform_data.id +`" data-id="`+ item_data.id +`" class="am-cascader-panel">
                                        <div class="am-scrollbar province am-cascader-menu `+ (!IsEmpty(item_data.province) ? `am-active` : ``) +`" data-key="province">
                                            <div class="am-cascader-menu-wrap am-scrollbar-wrap am-scrollbar-wrap-hidden-default">
                                                <ul class="am-scrollbar-view am-cascader-menu-list">
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="am-scrollbar city am-cascader-menu `+ (!IsEmpty(item_data.city) ? `am-active` : ``) +`" data-key="city">
                                            <div class="am-cascader-menu-wrap am-scrollbar-wrap am-scrollbar-wrap-hidden-default">
                                                <ul class="am-scrollbar-view am-cascader-menu-list">
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="am-scrollbar county am-cascader-menu `+ (!IsEmpty(item_data.county) ? `am-active` : ``) +`" data-key="county">
                                            <div class="am-cascader-menu-wrap am-scrollbar-wrap am-scrollbar-wrap-hidden-default">
                                                <ul class="am-scrollbar-view am-cascader-menu-list">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="am-popper-arrow"></div>
                                </div>
                            </div>
                        </div>`;
            }
            subform_html += '</div>';
        })
    }
    return subform_html;
}
/*
* 表单提交校验逻辑
*/
function ForminputSubmitDataCheck() {
    const data = JSON.parse(JSON.stringify(ForminputShowHiddenChange('data')));
    // 遍历所有过滤后的自定义数据项
    data?.forEach((item) => {
        let com_data = item.com_data;
        // 跳过非必填项
        // 特殊字段验证：手机号
        if (item.key === 'phone') {
            // 判断输入框内容是否为空,为空的话获取验证码取消
            if (com_data.is_sms_verification === '1' && !IsEmpty(com_data.form_value) && IsEmpty(com_data.form_value_code)) {
                com_data.common_config.is_error = '1';
                com_data.common_config.error_text = '短信验证码不能为空';
            } else {
                const { is_error = '0', error_text = '' } = ForminputHandlePhoneValidation(com_data);
                com_data.common_config.is_error = is_error;
                com_data.common_config.error_text = error_text;
            }
        } else {
            if (com_data.is_required === '1') {
                // 其他字段的格式验证
                if (ForminputFieldCheckMap[item.key]) {
                    let field_data = ForminputFieldCheckMap[item.key];
                    if (['single-text', 'select', 'radio-btns'].includes(item.com_data.type)) {
                        field_data = ForminputFieldCheckMap[item.com_data.type];
                    }
                    const { is_format, type } = field_data;
                    const { is_error = '0', error_text = '' } = ForminputGetFormatChecks(com_data, com_data.form_value, is_format, type);
                    com_data.common_config.is_error = is_error;
                    com_data.common_config.error_text = error_text;
                }
            };
        }
        
        /**
         * 子表单处理逻辑
         * 1. 检查子表单中是否有必填项
         * 2. 验证子表单整体必填性
         * 3. 处理子表单内各字段的验证
         */
        if (item.key === 'subform') {
            com_data = ForminputSubformDataCheck(com_data);
        }
    });
    return data;
}

/*
* 子表单的校验逻辑
*/
function ForminputSubformDataCheck(com_data) { 
    // 子表单整体必填验证
    if (com_data.is_required === '1' && com_data.data_list.length <= 0) {
        com_data.common_config.is_error = '1';
        com_data.common_config.error_text = '请填写至少一条记录';
    } else {
        com_data.common_config.is_error = '0';
        com_data.common_config.error_text = '';
    }
    // 处理子表单内各字段的验证
    if (com_data.data_list.length > 0) {
        // 验证子表单内各字段
        com_data.data_list.forEach((form_item, index) => {
            // 取出对应列的数据信息
            const form_data = ForminputFilteredData(form_item);
            form_data.forEach((data_item) => {
                // 跳过非必填项
                if (data_item.com_data.is_required !== '1') return;
                // 执行字段验证
                const checkConfig = ForminputFieldCheckMap[data_item.key];
                if (checkConfig) {
                    // 特殊字段验证：手机号
                    if (data_item.key === 'phone') {
                        // 判断输入框内容是否为空,为空的话获取验证码取消
                        if (data_item.com_data.is_sms_verification === '1' && !IsEmpty(data_item.com_data.form_value) && IsEmpty(data_item.com_data.form_value_code)) {
                            data_item.com_data.common_config.is_error = '1';
                            data_item.com_data.common_config.error_text = '短信验证码不能为空';
                        } else {
                            const { is_error = '0', error_text = '' } = ForminputHandlePhoneValidation(data_item);
                            data_item.com_data.common_config.is_error = is_error;
                            data_item.com_data.common_config.error_text = error_text;
                        }
                    }
                    // 其他字段的格式验证
                    else if (ForminputFieldCheckMap[data_item.key]) {
                        let field_data = ForminputFieldCheckMap[data_item.key];
                        if (['single-text', 'select', 'radio-btns'].includes(data_item.com_data.type)) {
                            field_data = ForminputFieldCheckMap[data_item.com_data.type];
                        }
                        const { is_format, type } = field_data;
                        const { is_error = '0', error_text = '' } = ForminputGetFormatChecks(data_item.com_data, data_item.com_data.form_value, is_format, type);
                        data_item.com_data.common_config.is_error = is_error;
                        data_item.com_data.common_config.error_text = error_text;
                    }
                }
            });
        });
    }
    return com_data;
}

/*
* 子表单显隐规则数据处理
*/
function ForminputFilteredData(children) {
    const componentMap = new Map(children.map((item) => [item.id, item]));

    // 取出所有设置显隐规则的组件
    const list = children.filter((item) => ['single-text', 'select', 'radio-btns'].includes(item.key) && ['select', 'radio-btns'].includes(item.com_data.type) && item.com_data.show_hidden_list.length > 0);
    const list_map = list.map((item) => ({ id: item.id, list: item.com_data.show_hidden_list }));
    return children.filter((item) => {
        // 优先判断是否启用
        if (item.is_enable !== '1') return false;

        if (list_map.length === 0) return true;
        // 将所有的内容的组件进行筛选
        const isShownByRule = list_map.some((list_item) => {
            const targetComponent = componentMap.get(list_item.id);
            // 判断显隐规则对应的组件是否存在
            if (!targetComponent) return false;
            return list_item.list.some(hidden_item => {
                // 判断当前组件是否在显隐规则中，如果不在，直接显示，否则的话判断值是否存在
                if (hidden_item.is_show.includes(item.id)) {
                    return targetComponent.com_data.form_value.includes(hidden_item.value);
                } else {
                    return true;
                }
            });
        });
        return isShownByRule;
    });
}
/*
* 提交数据处理逻辑
*/
function ForminputOnSubmitEvent() { 
    try {
        // 校验所有的必填项和逻辑
        const new_data = ForminputSubmitDataCheck();
        // 处理所有的错误项
        const data = new_data.find((item) => item.com_data.common_config.is_error === '1' || (item.key === 'subform' && item.com_data.data_list.some((item1) => item1.some(list_item => list_item.com_data.common_config.is_error === '1'))));
        if (!IsEmpty(data)) {
            let message = '';
            if (data.key === 'subform') {
                // 如果子表单外层为error直接显示名称出来
                if (data.com_data.common_config.is_error == '1') {
                    message = `${data.com_data.title}「${data.com_data.common_config.error_text}」`;
                } else {
                    // 如果是内部问题，让用户自己检查子表单内的填写
                    message = `请检查${data.com_data.title}内的填写`;
                }
            } else {
                message = `${data.com_data.title}「${data.com_data.common_config.error_text}」`;
            }
            // 更新数据，将报错信息或者解除报错信息赋值给原数据
            const old_data = [...$forminput_data_list];
            const data_list = old_data.map(item => {
                const match = new_data.find(el => el.id === item.id);
                return { ...item, ...match };
            });
            data_list.forEach(item => {
                if (item.key === 'subform') {
                    item.com_data.data_list.forEach((item1, index) => {
                        item1.forEach((list_item) => {
                            if (list_item.com_data.common_config.is_error === '1') {
                                ForminputErrorHandle(list_item, list_item.id + '-' + index);
                            }
                        });
                    });
                } else {
                    if (item.com_data.common_config.is_error === '1') {
                        ForminputErrorHandle(item, item.id);
                    }
                }
            })
            return { forminput_id: $forminput_id, status: 'error', submit_data: {}, message: message };
        } else {
            return ForminputSubmitDataParameterHandle();
        }
    } catch (error) {
        return { forminput_id: $forminput_id, status: 'error', submit_data: {}, message: '数据错误'};
    }
}

/*
* 提交数据时页面显示错误样式
*/
function ForminputErrorHandle(item, id) {
    if ((['single-text', 'select', 'radio-btns'].includes(item.key) && ['radio-btns', 'select'].includes(item.com_data.type)) || (['checkbox', 'select-multi'].includes(item.key) && ['select-multi'].includes(item.com_data.type))) {
        const selected_parent = $(`#${id}`).find('.am-selected-btn.am-btn');
        if (!selected_parent.hasClass('forminput-data-item-error')) {
            selected_parent.addClass('forminput-data-item-error');
        }
    } else if (item.key === 'address') {
        const selected_parent = $(`#${id}`).find('.am-cascader-suffix  .forminput-data-item-content');
        if (!selected_parent.hasClass('forminput-data-item-error')) {
            selected_parent.addClass('forminput-data-item-error');
        }
    } else {
        const input_parent = $(`#${id}`).find('.forminput-input').parent();
        if (!input_parent.hasClass('forminput-data-item-error')) {
            input_parent.addClass('forminput-data-item-error');
        }
    }
}

/*
* 子表单内的数据提取
*/
function ForminputSubformDataExtraction(data_list) {
    return data_list.map((subform_item) => {
        const data = {};
        // 子表单中每一行的数据显示
        subform_item.forEach((item1) => {
            // 子表单中每个独立的数据name
            const subform_name = IsEmpty(item1.form_name) ? item1.id : item1.form_name;
            const subform_com_data = item1.com_data;
            // 对应的value
            const subform_value = IsEmpty(subform_com_data.form_value) ? '' : subform_com_data.form_value;
            if (!ForminputFilterData.includes(item1.key)) {
                if (item1.key == 'address') {
                    data[`${ subform_name }_province_id`] = subform_value[0] || '';
                    data[`${ subform_name }_city_id`] = subform_value[1] || '';
                    data[`${ subform_name }_county_id`] = subform_value[2] || '';
                    // 省市区中文名称
                    data[`${ subform_name }_province_name`] = subform_com_data.province_name || '';
                    data[`${ subform_name }_city_name`] = subform_com_data.city_name || ''
                    data[`${ subform_name }_county_name`] = subform_com_data.county_name || ''
                } else if (item1.key ==='date-group') {
                    data[`${ subform_name }_start`] = subform_value[0] || '';
                    data[`${ subform_name }_end`] = subform_value[1] || '';
                } else if (['checkbox', 'select-multi'].includes(item1.key)) {
                    data[subform_name] = subform_value;
                    if (subform_com_data.is_add_option == '1') {
                        data[`${ subform_name }_custom_option_list`] = subform_com_data?.custom_option_list || [];
                    }
                } else {
                    data[subform_name] = subform_value;
                }
            }
        });
        return data;
    });
}
// 过滤掉一些不需要提交的字段
const ForminputFilterData = ['video', 'img', 'auxiliary-line', 'position', 'rect', 'round', 'text', 'attachments'];
/*
* 表单提交参数处理
*/
function ForminputSubmitDataParameterHandle() {
    try { 
        const submit_data = {};
        const filteredDiyData = ForminputShowHiddenChange('data');
        // 规整字段信息
        filteredDiyData.forEach((item) => {
            if (!ForminputFilterData.includes(item.key)) {
                const name = IsEmpty(item.form_name) ? item.id : item.form_name;
                const value = IsEmpty(item.com_data.form_value) ? '' : item.com_data.form_value;
                const com_data = item.com_data;
                if (item.key ==='subform') {
                    const data_list = com_data.data_list;
                    // 子表单中每一行的数据显示
                    submit_data[name] = ForminputSubformDataExtraction(data_list);
                } else if (item.key ==='phone') {
                    submit_data[`${ name }`] = value || '';
                    // 判断是否需要发送短信验证码
                    if (com_data.is_sms_verification == '1') {
                        submit_data[`${ name }_verify`] = com_data?.form_value_code || '';
                    }
                } else if (item.key ==='date-group') {
                    submit_data[`${ name }_start`] = value[0] || '';
                    submit_data[`${ name }_end`] = value[1] || '';
                } else if (item.key == 'address') {
                    submit_data[`${ name }_province_id`] = value[0] || '';
                    submit_data[`${ name }_city_id`] = value[1] || '';
                    submit_data[`${ name }_county_id`] = value[2] || '';
                    // 省市区中文名称
                    submit_data[`${ name }_province_name`] = com_data.province_name || '';
                    submit_data[`${ name }_city_name`] = com_data.city_name || ''
                    submit_data[`${ name }_county_name`] = com_data.county_name || ''
                    // 判断类型是否包含详细地址
                    if (com_data.address_type == 'detailed') {
                        submit_data[`${ name }_address`] = com_data?.address || '';
                    }
                } else if (['select', 'radio-btns', 'single-text'].includes(item.key) && ['select', 'radio-btns'].includes(item.com_data.type)) {
                    submit_data[name] = value;
                    const value_list = com_data.option_list.filter((item) => item.is_other == '1');
                    if (value_list.length > 0) {
                        submit_data[`${ name }_other_value`] = com_data?.other_value || '';
                    }
                } else if (['checkbox', 'select-multi'].includes(item.key)) {
                    submit_data[name] = value;
                    if (com_data.is_add_option == '1') {
                        submit_data[`${ name }_custom_option_list`] = com_data?.custom_option_list || [];
                    }
                } else {
                    submit_data[name] = value;
                }
            }
        });
        return { forminput_id: $forminput_id, status: 'success', submit_data: submit_data, message: ''};
    } catch (error) {
        return { forminput_id: $forminput_id, status: 'error', submit_data: {}, message: '数据错误'};
    }
}