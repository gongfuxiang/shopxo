$(function()
{
    // 定时查询订单是否已支付
    setInterval(function()
    {
        // ajax请求
        $.ajax({
            url: $('.qrcode-pay').data('ajax-url'),
            type: 'post',
            dataType: "json",
            timeout: 10000,
            data: {"order_no": $('.qrcode-pay').data('order-no')},
            success: function(result)
            {
                if(result.code == 0 && (result.data.url || null) != null)
                {
                    Prompt(result.msg, 'success');
                    setTimeout(function()
                    {
                        window.location.href = result.data.url;
                    }, 1500);
                } else if(result.code == -400 && (result.data.url || null) != null) {
                    Prompt(result.msg);
                    setTimeout(function()
                    {
                        window.location.href = result.data.url;
                    }, 1500);
                } else {
                    if(result.code != -300)
                    {
                        Prompt(result.msg);
                    }
                }
            },
            error: function(xhr, type)
            {
                Prompt('服务器错误');
            }
        });
    }, 2000);
});