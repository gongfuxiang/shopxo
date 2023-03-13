$(function()
{
    // 开启基础编辑
    $('.base-edit-submit').on('click', function()
    {
        var $form = $('form.form-validation');
        $form.attr('data-opt-type', 0);
        $form.attr('request-type', 'sync');
        $('#popup-saveinfo').modal();
    });

    // 保存数据
    $('.page-save-submit').on('click', function()
    {
        var $form = $('form.form-validation');
        $form.attr('data-opt-type', 1);
        $form.attr('request-type', 'ajax-fun');
        $form.find('button[type="submit"]').trigger('click');
    });
});