$(function()
{
    // 模板切换
    $(document).on('click', '.select-theme', function()
    {
        var $parent = $(this).parent();
        if(!$parent.hasClass('theme-active'))
        {
            var theme = $(this).data('theme') || null;
            if(theme != null)
            {
                // ajax请求
                $.AMUI.progress.start();
                $.ajax({
                    url: RequestUrlHandle($('.data-list').data('select-url')),
                    type: 'POST',
                    dataType: 'json',
                    timeout: 10000,
                    data: {"theme":theme},
                    success: function(result)
                    {
                        $.AMUI.progress.done();
                        if(result.code == 0)
                        {
                            // 选中
                            $('.am-gallery-item').removeClass('theme-active');
                            $parent.addClass('theme-active');
                            $('.am-gallery-item .theme-active-check').addClass('am-hide');
                            $parent.find('.theme-active-check').removeClass('am-hide');
                            // 管理
                            $('.am-gallery-item .theme-data-admin-submit').addClass('am-hide');
                            $parent.find('.theme-data-admin-submit').removeClass('am-hide');
                            // 删除
                            $('.am-gallery-item .submit-delete').removeClass('am-hide');
                            $parent.find('.submit-delete').addClass('am-hide');
                            Prompt(result.msg, 'success');
                            // 上传
                            $('.am-gallery-item .theme-upload-submit').removeClass('am-color-ccc');
                            $parent.find('.theme-upload-submit').addClass('am-color-ccc');
                            // 下载
                            $('.am-gallery-item .theme-download-submit').removeClass('am-color-ccc');
                            $parent.find('.theme-download-submit').addClass('am-color-ccc');
                        } else {
                            if(result.code == -300)
                            {
                                StoreAccountsPopupOpen();
                            } else {
                                Prompt(result.msg);
                            }
                        }
                    },
                    error: function(xhr, type)
                    {
                        $.AMUI.progress.done();
                        Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                    }
                });
            }
        }
    });
});