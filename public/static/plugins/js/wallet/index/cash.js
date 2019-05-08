$(function()
{
    $verify_win = $('#verify-win');

    // 原帐号验证码发送
    $('.verify-submit, .verify-submit-win').on('click', function()
    {
        // 是否选择验证账号类型
        var $account_type_tag = $('form.form-validation select[name="account_type"]');
        var account_type = $account_type_tag.val() || null;
        if(account_type == null)
        {
            $account_type_tag.trigger('change');
            Prompt($account_type_tag.data('validation-message'));
            return false;
        }

        // 图片验证码校验
        var $this = $(this);
        var $verify = $('#verify-img-value');
        var verify = '';

        // 是否需要先校验图片验证码
        if($this.data('verify') == 1)
        {
            // 开启图片验证码窗口
            $verify_win.modal('open');
            $verify.focus();
            return false;
        }

        // 验证码窗口操作按钮则更新按钮对象
        var is_win = $(this).data('win');
        if(is_win == 1)
        {
            $this = $('.verify-submit');

            // 验证码参数处理
            verify = $verify.val().replace(/\s+/g, '');

            if(verify.length < 6)
            {
                Prompt($verify.data('validation-message'));
                $verify.focus();
                return false;
            }
        }

        // 验证码时间间隔
        var time_count = parseInt($this.data('time'));
        
        // 按钮交互
        $this.button('loading');
        if(is_win == 1)
        {
            $('.verify-submit-win').button('loading');
        }

        // 发送验证码
        $.ajax({
            url:$('.verify-submit').data('url'),
            type:'POST',
            data:{"verify":verify, "account_type":account_type},
            dataType:'json',
            success:function(result)
            {
                if(result.code == 0)
                {
                    var intervalid = setInterval(function()
                    {
                        if(time_count == 0)
                        {
                            $this.button('reset');
                            if(is_win == 1)
                            {
                                $('.verify-submit-win').button('reset');
                            }
                            $this.text($this.data('text'));
                            $verify.val('');
                            clearInterval(intervalid);
                        } else {
                            var send_msg = $this.data('send-text').replace(/{time}/, time_count--);
                            $this.text(send_msg);
                        }
                    }, 1000);
                    $verify_win.modal('close');
                } else {
                    $this.button('reset');
                    if(is_win == 1)
                    {
                        $('.verify-submit-win').button('reset');
                    }
                    Prompt(result.msg);
                }
            },
            error:function()
            {
                $this.button('reset');
                if(is_win == 1)
                {
                    $('.verify-submit-win').button('reset');
                }
                Prompt('网络错误');
            }
        });
    });

});