$(function()
{
    // 帮助-地图查看
    $('.pets-help .map-submit').on('click', function()
    {
        var lng = parseFloat($(this).data('lng'));
        var lat = parseFloat($(this).data('lat'));
        if(lng > 0 && lat > 0)
        {
            var url = UrlFieldReplace('lat', lat, UrlFieldReplace('lng', lng, $('.pets-help').data('url')));
            ModalLoad(url, '地图', 'plugins-petscms-popup-modal-help');
        }
    });
});