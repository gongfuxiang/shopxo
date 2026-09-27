/**
 * 系统配置页面JS
 * 多语言域名绑定行添加/移除（多语言tab）
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 */

$(function ()
{
    // 添加域名
    $(document).on('click', '.domain-submit-add', function () {
        var please_select_tip = $(this).data('please-select-tips') || '请选择...';
        var select_html = '<option value="0">' + please_select_tip + '</option>';
        var json = $(this).data('json') || null;
        if (json != null) {
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for (var i in json) {
                select_html += '<option value="' + i + '">' + json[i] + '</option>';
            }
        }
        var form_name = $(this).data('form-name');
        var index = parseInt(Math.random() * 1000001);
        var html = `<li class="am-flex am-flex-row am-flex-items-center am-gap-1">
                        <input type="text" name="`+ form_name + `[` + index + `][domain]" placeholder="` + ($(this).data('domain-placeholder') || '域名') + `" data-validation-message="` + ($(this).data('domain-message') || '请填写域名') + `" class="am-radius am-inline-block item-domain-input" value="" />
                        <div class="am-inline-block item-multilingual-choice">
                            <select name="`+ form_name + `[` + index + `][lang]" class="am-radius chosen-select" data-placeholder="` + please_select_tip + `" data-validation-message="` + ($(this).data('select-message') || '请选择域名对应语言') + `">
                                `+ select_html + `
                            </select>
                        </div>
                        <div class="am-fr am-margin-top-xs">
                            <a href="javascript:;" class="am-btn am-btn-primary-plain am-radius delete-submit delete-btn"><i class="iconfont icon-btn-del am-text-xs"></i> `+ ($(this).data('remove-title') || '移除') + `</a>
                        </div>
                    </li>`;
        $('.domain-multilingual-list > ul').append(html);
        // 下拉选择组件初始化
        SelectChosenInit();
    });
    // cookie域名移除
    $(document).on('click', '.domain-multilingual-list .delete-submit', function () {
        var $parent = $(this).parents('li');
        AMUI.dialog.confirm({
            title: window['lang_reminder_title'] || '温馨提示',
            content: window['lang_remove_confirm_tips'] || '移除后保存生效、确认继续吗？',
            onConfirm: function (options) {
                $parent.remove();
            },
            onCancel: function () { }
        });
    });
});
