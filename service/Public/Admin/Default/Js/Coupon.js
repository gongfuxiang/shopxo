$(function()
{
    /**
     * 优惠券发放搜索用户
     */
    $('.search-submit').on('click', function()
    {
        // ajax请求
        $.ajax({
            url:$(this).data('url'),
            type:'POST',
            dataType:"json",
            timeout:10000,
            data:{"keyword": $('.search-keyword').val()},
            success:function(result)
            {
                if(result.code == 0)
                {
                    $('.data-success').removeClass('none');
                    $('.data-error-tips').addClass('none');
                    $('.data-success .base-username').text(result.data.username);
                    $('.data-success .base-nickname').text(result.data.nickname);
                    $('.data-success .base-mobile').text(result.data.mobile);
                    $('.data-success img').attr('src', result.data.avatar);
                    $('.join-submit').attr('data-id', result.data.id);
                } else {
                    $('.data-success').addClass('none');
                    $('.data-error-tips').removeClass('none');
                    $('.data-error-tips').text(result.msg);
                    Prompt(result.msg);
                }
            },
            error:function()
            {
                Prompt('网络异常错误');
            }
        });
    });

    /**
     * 用户加入列表
     */
    $('.join-submit').on('click', function()
    {
        var user_id = $(this).attr('data-id');
        var tag = 'user-list-'+user_id;
        var username = $('.data-success .base-username').text() || null;
        var nickname = $('.data-success .base-nickname').text() || null;
        var mobile = $('.data-success .base-mobile').text() || null;
        var user = '';
        if(username != null)
        {
            user += username;
        }
        if(nickname != null)
        {
            if(username != null || user != '')
            {
                user += ' - ';
            }
            user += nickname;
        }
        if(mobile != null)
        {
            if(nickname != null || user != '')
            {
                user += ' - ';
            }
            user += mobile;
        }

        var html = '<li class="'+tag+'"><input type="hidden" name="user_id[]" value="'+user_id+'" />';
            html += '<span>'+user+'</span>';
            html += '<button type="button" class="am-btn am-btn-danger am-radius am-btn-xs fr user-del-submit" data-tag="'+tag+'"><i class="am-icon-trash-o"></i>移除</button>';
            html += '</li>';

        if($('.user-list .'+tag).length == 0)
        {
            $('.user-list').removeClass('none');
            $('.user-list').append(html);
        }
    });

    /**
     * 用户移除
     */
    $(document).on('click', '.user-del-submit', function()
    {
        $('.'+$(this).data('tag')).remove();
        if($('.user-list li').length == 0)
        {
            $('.user-list').addClass('none');
        }
    });

    /**
     * 优惠券发送表单验证
     */
    $('.form-send-submit').on('click', function()
    {
        if($('.user-list li').length == 0)
        {
            Prompt('请先查询用户加入列表');
            return false;
        }
    });
});