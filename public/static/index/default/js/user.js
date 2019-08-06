// 表单初始化
FromInit('form.form-validation-username');
FromInit('form.form-validation-sms');
FromInit('form.form-validation-email');

$(function()
{
	// 查看密码
	$('.eye-submit').on('click', function()
	{
		var $obj = $(this).parent().prev();
		if($obj.attr('type') == 'password')
		{
			$(this).addClass('cr-green');
			$obj.attr('type', 'text');
		} else {
			$(this).removeClass('cr-green');
			$obj.attr('type', 'password');
		}
	});


	// 短信验证码获取
	$('.verify-submit, .verify-submit-win').on('click', function()
	{
		// 表单发送按钮
		var form_tag = $(this).data('form-tag') || null;
		if(form_tag != null)
		{
			$('body').attr('data-form-tag', form_tag);
		}
		
		// 验证账户
		var $this = $(this);
		var $form_tag = $($('body').attr('data-form-tag'));
		var $accounts = $form_tag.find('input[name="accounts"]');
		var $verify = $('#verify-img-value');
		var $verify_img = $('#verify-img');
		var verify = '';
		if($accounts.hasClass('am-field-valid'))
		{
			// 是否需要先校验图片验证码
			if($this.data('verify') == 1)
			{
				// 开启图片验证码窗口
				$('#verify-win').modal('open');
				$verify_img.trigger("click");
				$verify.focus();
				return false;
			}

			// 验证码窗口操作按钮则更新按钮对象
			var is_win = $(this).data('win');
			if(is_win == 1)
			{
				$this = $form_tag.find('.verify-submit');

				// 验证码参数处理
				verify = $verify.val().replace(/\s+/g, '');

				if(verify.length != 4)
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
				data:{"accounts":$accounts.val(), "verify":verify, "type":$form_tag.find('input[name="type"]').val()},
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
						if($('#verify-win').length > 0)
						{
							$('#verify-win').modal('close');
						}
					} else {
						$this.button('reset');
						if(is_win == 1)
						{
							$('.verify-submit-win').button('reset');
							$verify_img.trigger("click");
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
		} else {
			if($('#verify-win').length > 0)
			{
				$('#verify-win').modal('close');
			}
			$accounts.focus();
		}
	});

});