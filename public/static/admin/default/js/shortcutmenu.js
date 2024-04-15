$(function () {
    // 选择编辑
    $(document).on('click', '.shortcut-menu-sort ul li', function () {
        var $form_base_title = $('.shortcut-menu-form .form-base-title');
        var json = $(this).attr('data-json');
        if (json) {
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            $('select[name="menu"]').val(json.menu);
            if ((json.menu || null) == null) {
                $('input[name="url"]').val(json.url);
            } else {
                $('input[name="url"]').val('');
            }
            $('input[name="name"]').val(json.name);
            $('input[name="id"]').val(json.id);
            var html = '';
            html += '<li class="plug-file-upload-submit" data-view-tag="ul.shortcut-menu-icon">';
            html += '<input type="text" name="icon" value="' + json.icon + '">';
            html += '<img src="' + json.icon + '">';
            html += '<i class="iconfont icon-close"></i>';
            html += '</li>';
            $('.shortcut-menu-icon').html(html);
            SelectChosenInit();
            $form_base_title.text($form_base_title.data('base-title')+$form_base_title.data('edit-title'));
        } else {
            ShortCutMenuFormReset();
            $form_base_title.text($form_base_title.data('base-title')+$form_base_title.data('add-title'));
            // 动画提示
            var animation = 'am-animation-shake';
            if ($.AMUI.support.animation)
            {
                $('.shortcut-menu-form').addClass(animation).one($.AMUI.support.animation.end, function() {
                    $('.shortcut-menu-form').removeClass(animation);
                });
            }
        }
    })
    // 删除
    $(document).on('click', '.shortcut-menu-sort ul li .icon-close', function () {
        var $this = $(this);
        AMUI.dialog.confirm({
            title: window['lang_reminder_title'] || '温馨提示',
            content: window['lang_operate_confirm_tips'] || '操作后不可恢复、确认继续吗？',
            onConfirm: function (result) {
                var id = $this.parents('li').data('id');
                var ul = $this.parents('ul');
                var url = ul.data('url');
                $.ajax({
                    url: RequestUrlHandle(url),
                    type: 'POST',
                    dataType: 'json',
                    timeout: 30000,
                    data: { ids: id },
                    success: function (result) {
                        if (result.code == 0) {
                            sessionStorage.setItem('admin_shortcutmenu_oprate_status', 1);
                            Prompt(result.msg, 'success');
                            $this.parents('li').remove();
                            ShortCutMenuFormReset();
                            if (ul.find('li').length < 10) {
                                ul.find('.add-menu').removeClass('am-hide');
                            }
                        } else {
                            Prompt(result.msg);
                        }
                    },
                    error: function (xhr, type) {
                        Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
                    }
                });
            }
        });
    });

    // 拖拽
    $('.shortcut-menu-sort ul').dragsort({
        dragSelector: 'li.item',
        placeHolderTemplate: '<li><div class="drag-sort-dotted am-width am-height"></div></li>',
        dragEnd: function () { // 拖动结束时触发的回调函数
            var arry = [];
            var url = $(this).parents('ul').data('sort');
            $(this).parents('ul').find('li').each(function (k, v) {
                var obj = {}
                var id = parseInt($(v).data('id')) || 0;
                obj = {
                    sort: k,
                    id: id
                };
                arry.push(obj);

            });
            $.ajax({
                url: RequestUrlHandle(url),
                type: 'POST',
                dataType: 'json',
                timeout: 30000,
                data: { "data": arry },
                success: function (result) {
                    if (result.code == 0) {
                        sessionStorage.setItem('admin_shortcutmenu_oprate_status', 1);
                        Prompt(result.msg, 'success');
                        ShortCutMenuFormReset();
                    } else {
                        Prompt(result.msg);
                    }
                },
                error: function (xhr, type) {
                    Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
                }
            });
        }
    });
    // 权限菜单选择事件
    $(document).on('change', 'select[name="menu"]', function () {
        if($(this).find('option:selected').length > 0) {
            $('input[name="name"]').val($(this).find('option:selected').data('name') || '');
        }
    });
});
// 表单清空
function ShortCutMenuFormReset () {
    $('select[name="menu"]').val('');
    $('input[name="url"]').val('');
    $('input[name="name"]').val('');
    $('input[name="id"]').val('');
    $('.shortcut-menu-icon').html(`<li class="plug-file-upload-submit" data-view-tag="ul.shortcut-menu-icon">
                                        <i class="iconfont icon-upload-add"></i>
                                    </li>`);
    SelectChosenInit();
}
// 表单保存回调处理
function ShortCutMenuFormSaveBackHandle (e) {
    $.AMUI.progress.done();
    var $button = $('form.form-validation').find('button[type="submit"]');
    if (e.code == 0) {
        sessionStorage.setItem('admin_shortcutmenu_oprate_status', 1);
        Prompt(e.msg, 'success');
        setTimeout(function () {
            $button.button('reset');
            window.location.reload();
        }, 1500);
    } else {
        $button.button('reset');
        Prompt(e.msg);
    }
}