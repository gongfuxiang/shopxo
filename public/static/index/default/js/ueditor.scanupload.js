$(function()
{
    var $list = $('.uploader-list');
    var $ul = $list.find('ul');
    var category_id = $list.data('cid');
    var key = $list.data('key');
    var action = $list.data('action');
    var ext = $list.data('extensions') || null;
    var ext_arr = (ext == null) ? [] : ext.split(',');
    var accept = (action == 'uploadimage') ? {extensions: ext, mimeTypes: 'image/*'} : null;

    // 初始化WebUploader
    var uploader = WebUploader.create({
        // swf文件路径
        swf: $list.data('swf-url'),
        // 文件接收服务端。
        server: $list.data('server-url'),
        // 设置文件上传域的name
        fileVal: 'upfile',
        // 请求参数
        formData: {
            action: action,
            category_id: category_id,
            key: key,
            upload_source: 'scanupload'
        },
        // 指定文件格式
        accept: accept,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id: '#picker',
            innerHTML: '<button type="button" class="am-btn am-btn-secondary am-radius am-btn-sm">'+($list.data('choice-file-text') || '选择文件')+'</button>',
            multiple: true
        },
        // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
        resize: false,
        // 选择文件后自动开始上传
        //auto: true,
        // 上传并发数。允许同时最大上传进程数
        threads: 1,
        // 拖入功能
        dnd: '.uploader-list',
        disableGlobalDnd: false,
        // 粘贴
        paste: '.uploader-list'
    });

    // 开始上传
    $('.upload-submit').on('click', function()
    {
        uploader.upload();
    });

    // 清空所有
    $('.remove-all-submit').on('click', function()
    {
        $ul.find('li').each(function(item)
        {
            $(this).find('.remove').trigger('click');
        });
    });

    // 当有文件被添加进队列的时候
    uploader.on('fileQueued', function(file)
    {
        // 列表数据
        var $li = $(`<li class="am-nbfc" id="`+file.id+`">
                    <p class="am-text-truncate name">
                        `+(action == 'uploadimage' && ext_arr.indexOf(file.ext) != -1 ? '<img src="" class="am-radius" width="20" height="20" />' : '')+`
                        <span>`+file.name+`</span>
                    </p>
                    <p class="am-text-truncate size">`+AnnexSizeToUnit(file.size)+`</p>
                    <p class="am-text-truncate status">`+($list.data('upload-status-await-text') || '等待上传')+`</p>
                    <p class="am-text-truncate remove">`+(window['lang_operate_remove_name'] || '移除')+`</p>
                    <p class="progress am-hide"></p>
                </li>`);
        var $img = $li.find('.name img');
        $ul.append($li);

        // 缩略图、如果为非图片文件，可以不用调用此方法
        if($img.length > 0)
        {
            uploader.makeThumb(file, function(error, src)
            {
                if(!error && src)
                {
                    $img.attr('src', src);
                }
            }, 100, 100);
        }

        // 隐藏无数据提示
        $('.table-no').addClass('am-hide');

        // 移除
        $li.on('click', '.remove', function() {
            // 移除数据
            uploader.removeFile( file );
            $li.remove();

            // 显示无数据提示
            if($('.uploader-list li').length <= 1)
            {
                $('.table-no').removeClass('am-hide');
            }
        });
    });

    // 文件上传过程中创建进度条实时显示。
    // file {File}File对象
    // percentage {Number}上传进度
    uploader.on('uploadProgress', function(file, percentage)
    {
        var $li = $('#'+file.id);
        var ratio = percentage*100+'%';
        $li.find('p.status').text(($list.data('upload-status-progress-text') || '上传中')+'('+ratio+')');
        $progress = $li.find('.progress');
        $progress.css('width', ratio).removeClass('am-hide').addClass('progress');

        // 滚动到最后的位置
        $list.stop(true).animate({scrollTop: $list[0].scrollHeight}, 500);
    });

    // 当文件上传成功时触发。
    // file {File}File对象
    // response {Object}服务端返回的数据
    uploader.on('uploadSuccess', function(file, response)
    {
        if(response.code == 0)
        {
            var $li = $('#'+file.id);
            $li.find('p.status').text($list.data('upload-status-success-text') || '上传成功').removeClass('progress').addClass('success');
        } else {
            var $li = $('#'+file.id);
            $li.find('p.status').text(($list.data('upload-status-fail-text') || '上传失败')+'('+response.msg+')').removeClass('progress').addClass('fail');

            $progress = $li.find('.progress');
            $progress.css('width', '0%' ).addClass('am-hide');
        }
    });

    // 当文件上传出错时触发。
    // file {File}File对象
    // reason {String}出错的code
    uploader.on('uploadError', function(file, reason)
    {
        var $li = $('#'+file.id);
        $li.find('p.status').text(($list.data('upload-status-fail-text') || '上传失败')+'('+reason+')').removeClass('progress').addClass('fail');

        $progress = $li.find('.progress');
        $progress.css('width', '0%' ).addClass('am-hide');
    });
});