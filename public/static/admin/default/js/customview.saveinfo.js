$(function()
{
    // 保存数据
    $('.page-save-submit').on('click', function()
    {
        var $form = $('form.form-validation');
        $form.attr('request-type', 'ajax-reload');
        $form.find('button[type="submit"]').trigger('click');
    });
});