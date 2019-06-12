$(function()
{
    $('.submit-delete-modal').on('click', function()
    {
        var $modal = $('#plugins-delete-modal');
        $modal.find('button.submit-ajax').attr('data-id', $(this).data('id'));
        $modal.modal('open');
    });
});