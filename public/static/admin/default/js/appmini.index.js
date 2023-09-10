$(function()
{
    // 模板切换
    $(document).on('click', '.select-theme', function()
    {
        var theme = $(this).data('theme');
        if(!$(this).parent().hasClass('theme-active'))
        {
            var $this = $(this);
            if(theme != undefined)
            {
                // ajax请求
                $.AMUI.progress.start();
                $.ajax({
                    url: RequestUrlHandle($('.data-list').data('select-url')),
                    type: 'POST',
                    dataType: 'json',
                    timeout: 10000,
                    data: {"theme":theme, "nav_type":$('.data-list').data('nav-type')},
                    success: function(result)
                    {
                        $.AMUI.progress.done();
                        if(result.code == 0)
                        {
                            $('.am-gallery-item').removeClass('theme-active');
                            $this.parent().addClass('theme-active');
                            Prompt(result.msg, 'success');
                        } else {
                            Prompt(result.msg);
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