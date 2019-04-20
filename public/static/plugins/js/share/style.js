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

        // 当前环境
        var env = MobileBrowserEnvironment();
        
        // 关闭弹层
        $('#plugins-share-layer').hide();

        // 根据分享类型处理
        switch(type)
        {
            // QQ
            case 'qq' :
                if(env == 'qq' || env == 'weixin' || env == 'qzone' || env == 'weibo')
                {
                    $('#plugins-share-layer').show();
                } else {
                    platform_url = 'https://connect.qq.com/widget/shareqq/index.html?url='+url+'&utm_medium=qqim&title='+title+'&desc='+desc+'&pics='+pic+'&site='+site
                }
                break;

            // QQ空间
            case 'qq-space' :
                if(env == 'qq' || env == 'weibo')
                {
                    $('#plugins-share-layer').show();
                } else {
                    platform_url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+url+'&utm_medium=qzone&title='+title+'&desc='+desc+'&pics='+pic+'&summary='+desc+'&site='+site;
                }
                break;

            // 新浪微博
            case 'sian' :
                platform_url = 'http://service.weibo.com/share/share.php?url='+url+'&utm_medium=sian&title='+title+'&desc='+desc+'&pics='+pic+'&site='+site;
                break;

            // 微信
            case 'weixin' :
                if(env == 'qq' || env == 'weixin' || env == 'qzone' || env == 'weibo')
                {
                    $('#plugins-share-layer').show();
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
                var $modal = $('#plugins-share-copy-modal');
                $modal.find('.am-input-group input').val(decodeURIComponent(url));
                $modal.modal({width: 300});
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
    var clipboard = new ClipboardJS('#plugins-share-copy-modal .am-input-group button.am-btn',
    {
        text: function()
        {
            return $('#plugins-share-copy-modal .am-input-group input').val();
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

    // 分享提示弹层关闭
    $('#plugins-share-layer').on('click', function()
    {
        $('#plugins-share-layer').hide();
    });


    // 初始化
    if($('.plugins-share-container').length > 0)
    {
        // 标签初始化
        if($('.plugins-share-view').length > 0)
        {
            $('.plugins-share-view').html($('.plugins-share-container').html());
        }   
    }
    
});