$(function()
{
    // 分享事件
    $(document).on('click', '.plugins-share ul li', function()
    {
        // 分享类型
        var type = $(this).data('type');

        // 来源站点
        var site = 'shopxo';

        // url
        var url = $(this).parents('.plugins-share').data('url') || window.location.href;
            url = encodeURIComponent(url);

        // 标题
        var title = $(this).parents('.plugins-share').data('title') || document.title || null;
            title = title == null ? '' : encodeURIComponent(title);

        // 描述
        var desc = $(this).parents('.plugins-share').data('desc') || $('meta[name="description"]').attr('content') || null;
            desc = desc == null ? '' : encodeURIComponent(desc);

        // 封面图
        var pic = $(this).parents('.plugins-share').data('pic') || null;
            pic = pic == null ? '' : encodeURIComponent(pic);

        // 平台地址
        var platform_url = null;
        switch(type)
        {
            // QQ
            case 'qq' :
                platform_url = 'https://connect.qq.com/widget/shareqq/index.html?url='+url+'&utm_medium=qqim&title='+title+'&desc='+desc+'&pics='+pic+'&site='+site
                break;

            // QQ空间
            case 'qq-space' :
                platform_url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+url+'&utm_medium=qzone&title='+title+'&desc='+desc+'&pics='+pic+'&summary='+desc+'&site='+site;
                break;

            // 新浪
            case 'sian' :
                platform_url = 'http://service.weibo.com/share/share.php?url='+url+'&utm_medium=sian&title='+title+'&desc='+desc+'&pics='+pic+'&site='+site;
                break;

            // 微信
            case 'weixin' :
                // 是否微信环境中
                alert(IsEnvironment)
                if(IsEnvironment == 'weixin')
                {
                    
                } else {
                    var $modal = $('#plugins-share-weixin-modal');
                    $modal.find('.weixin-qrcode').empty().qrcode({
                        text: decodeURIComponent(url),
                        width: 200,
                        height: 200
                    });
                    $modal.modal({width: 260});
                    $modal.modal('open');
                }
                break;

            // url
            case 'url' :
                var $modal = $('#plugins-share-url-modal');
                $modal.find('.am-input-group input').val(decodeURIComponent(url));
                $modal.modal({width: 350});
                $modal.modal('open');
                break;
        }
        
        // 跳转
        if(platform_url != null)
        {
            window.open(platform_url);  
        }
    });

    // url复制
    var clipboard = new ClipboardJS('#plugins-share-url-modal .am-input-group button.am-btn',
    {
        text: function()
        {
            return $('#plugins-share-url-modal .am-input-group input').val();
        }
    });
    clipboard.on('success', function(e)
    {
        Prompt('复制成功', 'success');
    });
    clipboard.on('error', function(e)
    {
        Prompt('复制失败，请手动复制！');
    });
});