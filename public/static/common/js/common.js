/**
 * [Prompt 公共提示]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:32:39+0800
 * @param {[string]}	msg  [提示信息]
 * @param {[string]} 	type [类型（失败：danger, 成功success）]
 * @param {[int]} 		time [自动关闭时间（秒）, 默认3秒]
 */
var temp_time_out;
function Prompt(msg, type, time, distance, animation_type, location)
{
	if(msg != undefined && msg != '')
	{
		// 是否已存在提示条
		if($('#common-prompt').length > 0)
		{
			clearTimeout(temp_time_out);
		}

		// 提示信息添加
		$('#common-prompt').remove();
		if((type || null) == null) type = 'danger';
		if((animation_type || null) == null) animation_type = 'top';
		if((location || null) == null) location = 'top';

		var style = '';
		if((distance || null) != null) style = 'margin-'+animation_type+':'+distance+'px;';
		var html = '<div id="common-prompt" class="am-alert am-alert-'+type+' am-animation-slide-'+animation_type+' prompt-'+location+'" style="'+style+'" data-am-alert><button type="button" class="am-close am-close-spin">&times;</button><p>'+msg+'</p></div>';
		$('body').append(html);

		// 自动关闭提示
		temp_time_out = setTimeout(function()
		{
			$('#common-prompt').slideToggle();
		}, (time || 3)*1000);
	}
}
// 中间提示信息
function PromptCenter(msg, type, time, distance)
{
	Prompt(msg, type, time, distance, 'top', 'center');
}
// 底部提示信息
function PromptBottom(msg, type, time, distance)
{
	Prompt(msg, type, time, distance, 'bottom', 'bottom');
}

/**
 * [ArrayTurnJson js数组转json]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:32:04+0800
 * @param  {[array]} 	all    	[需要被转的数组]
 * @param  {[object]} 	object 	[需要压进去的json对象]
 * @return {[object]} 			[josn对象]
 */
function ArrayTurnJson(all, object)
{
	for(var name in all)
	{
		object.append(name, all[name]);
	}
	return object;
}

/**
 * [GetFormVal 获取form表单的数据]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:31:19+0800
 * @param    {[string]}     element [元素的class或id]
 * @return   {[object]}        		[josn对象]
 */
function GetFormVal(element)
{
	var object = new FormData();

	// input 常用类型
	$(element).find('input[type="hidden"], input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="date"], input[type="url"], input[type="radio"]:checked, textarea, input[type="file"]').each(function(key, tmp)
	{
		if(tmp.type == 'file')
		{
			object.append(tmp.name, ($(this).get(0).files[0] == undefined) ? '' : $(this).get(0).files[0]);
		} else {
			object.append(tmp.name, tmp.value.replace(/^\s+|\s+$/g,""));
		}
	});

	// select 单选择和多选择
	var tmp_all = [];
	var i = 0;
	$(element).find('select').find('option').each(function(key, tmp)
	{
		var name = $(this).parents('select').attr('name');
		if(name != undefined && name != '')
		{
			if($(this).is(':selected') && tmp.value != undefined && tmp.value != '')
			{
				// 多选择
				if($(this).parents('select').attr('multiple') != undefined)
				{
					if(tmp_all[name] == undefined)
					{
						tmp_all[name] = [];
						i = 0;
					}
					tmp_all[name][i] = tmp.value;
					i++;
				} else {
					// 单选择
					object.append(name, tmp.value);
				}
			}
		}
	});
	object = ArrayTurnJson(tmp_all, object);

	// input 复选框checkboox
	tmp_all = [];
	i = 0;
	$(element).find('input[type="checkbox"]').each(function(key, tmp)
	{
		if(tmp.name != undefined && tmp.name != '')
		{
			if($(this).is(':checked'))
			{
				if(tmp_all[tmp.name] == undefined)
				{
					tmp_all[tmp.name] = [];
					i = 0;
				}
				tmp_all[tmp.name][i] = tmp.value;
				i++;
			}
		}
	});
	object = ArrayTurnJson(tmp_all, object);
	return object;
}

/**
 * [IsExitsFunction 方法是否已定义]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:30:37+0800
 * @param    {[string]}    fun_name [方法名]
 * @return 	 {[boolean]}        	[已定义true, 则false]
 */
function IsExitsFunction(fun_name)
{
    try
    {
        if(typeof(eval(fun_name)) == "function") return true;
    } catch(e) {}
    return false;
}

/**
 * [GetTagValue 根据tag对象获取值]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-10-07T20:53:40+0800
 * @param    {[object]}         tag_obj [tag对象]
 */
function GetTagValue(tag_obj)
{
	// 默认值
	var v = null;

	// 标签名称
	var tag_name = tag_obj.prop("tagName");

	// input
	if(tag_name == 'INPUT')
	{
		var type = tag_obj.attr('type');
		switch(type)
		{
			// 单选框
			case 'checkbox' :
				v = tag_obj.is(':checked') ? tag_obj.val() : null;
				break;

			// 其它选择
			default :
				v = tag_obj.val() || null;
		}
	}
	return v;
}

/**
 * [$form.validator 公共表单校验, 添加class form-validation 类的表单自动校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:22:39+0800
 * @param    {[string] [form_name] 		[标题class或id]}
 * @param    {[string] [action] 		[请求地址]}
 * @param    {[string] [method] 		[请求类型 POST, GET]}
 * @param    {[string] [request-type] 	[回调类型 ajax-url, ajax-fun, ajax-reload]}
 * @param    {[string] [request-value] 	[回调值 ajax-url地址 或 ajax-fun方法]}
 */

function FromInit(form_name)
{
	if(form_name == undefined)
	{
		form_name = 'form.form-validation';
	}
	var editor_tag_name = 'editor-tag';
	var $form = $(form_name);
	var $editor_tag = $form.find('[id='+editor_tag_name+']');
	var editor_count = $editor_tag.length;
	if(editor_count > 0)
	{
		// 编辑器初始化
		var editor = UE.getEditor(editor_tag_name);

		// 编辑器内容变化时同步到 textarea
		editor.addListener('contentChange', function()
		{
			editor.sync();

			// 触发验证
			$editor_tag.trigger('change');
		});
	}
	$form.validator(
	{
		// 自定义校验规则
		validate: function(validity)
		{
			// 二选一校验
			if($(validity.field).is('.js-choice-one'))
			{
				var tag = $(validity.field).data('choice-one-to');
				if(typeof($(validity.field).attr('required')) == 'undefined' && typeof($(tag).attr('required')) == 'undefined')
				{
					validity.valid = true;
				} else {
					var v1 = GetTagValue($(validity.field));
					var v2 = GetTagValue($(tag));
					validity.valid = (v1 == null && v2 == null) ? false : true;
				}
			}
		},

		// 错误
		onInValid: function(validity)
		{
			// 错误信息
			var $field = $(validity.field);
			var msg = $field.data('validationMessage') || this.getValidationMessage(validity);
			Prompt(msg);
		},

		// 提交
		submit: function(e)
		{
			if(editor_count > 0)
			{
				// 同步编辑器数据
				editor.sync();

				// 表单验证未成功，而且未成功的第一个元素为 UEEditor 时，focus 编辑器
				if (!this.isFormValid() && $form.find('.' + this.options.inValidClass).eq(0).is($editor_tag))
				{
					// 编辑器获取焦点
					editor.focus();

					// 错误信息
					var msg = $editor_tag.data('validationMessage') || $editor_tag.getValidationMessage(validity);
					Prompt(msg);
				}
			}

			// 通过验证
			if(this.isFormValid())
			{
				// 多选插件校验
				if($form.find('.chosen-select'))
				{
					var is_success = true;
					$form.find('select.chosen-select').each(function(k, v)
					{
						var required = $(this).attr('required');
						if(($(this).attr('required') || null) == 'required')
						{
							var multiple = $(this).attr('multiple') || null;
							var minchecked = parseInt($(this).attr('minchecked')) || 0;
							var maxchecked = parseInt($(this).attr('maxchecked')) || 0;
							var msg = $(this).attr('data-validation-message');
							var value = $(this).val();
							if((value || null) == null && value != '0')
							{
								is_success = false;
								Prompt(msg || '请选择项');
								$(this).trigger('blur');
								return false;
							} else {
								if(multiple == 'multiple')
								{
									var count = value.length;
									if(minchecked > 0 && count < minchecked)
									{
										is_success = false;
										msg = msg || '至少选择'+minchecked+'项';
									}
									if(maxchecked > 0 && count > maxchecked)
									{
										is_success = false;
										msg = msg || '最多选择'+maxchecked+'项';
									}
									if(is_success === false)
									{
										Prompt(msg);
										$(this).trigger('blur');
										$(this).parents('.am-form-group').removeClass('am-form-success').addClass('am-form-error');
										return false;
									}
								}
							}
						}
					});
					if(is_success === false)
					{
						return false;
					}
				}

				// button加载
				var $button = $form.find('button[type="submit"]');
				$button.button('loading');

				// 开启进度条
				$.AMUI.progress.start();

				// 获取表单数据
				var action = $form.attr('action');
				var method = $form.attr('method');
				var request_type = $form.attr('request-type');
				var request_value = $form.attr('request-value');
				var ajax_all = ['ajax-reload', 'ajax-url', 'ajax-fun'];

				// 不是form表单直接通过
				if(request_type == 'form')
				{
					return true;
				}

				// 参数校验
				if(ajax_all.indexOf(request_type) == -1 || action == undefined || action == '' || method == undefined || method == '')
				{
					$.AMUI.progress.done();
	            	$button.button('reset');
	            	Prompt('表单参数配置有误');
	            	return false;
				}

				// 类型不等于刷新的时候，类型值必须填写
				if(request_type != 'ajax-reload' && (request_value == undefined || request_value == ''))
				{
					$.AMUI.progress.done();
	        		$button.button('reset');
					Prompt('表单[类型值]参数配置有误');
					return false;
				}

				// ajax请求
				$.ajax({
					url:action,
					type:method,
	                dataType:"json",
	                timeout:$form.attr('timeout') || 10000,
	                data:GetFormVal(form_name),
	                processData:false,
					contentType:false,
	                success:function(result)
	                {
	                	// 调用自定义回调方法
	                	if(request_type == 'ajax-fun')
	                	{
	                		if(IsExitsFunction(request_value))
	                		{
	                			window[request_value](result);
	                		} else {
	                			$.AMUI.progress.done();
		            			$button.button('reset');
	                			Prompt('['+request_value+']表单定义的方法未定义');
	                		}
	                	} else if(request_type == 'ajax-url' || request_type == 'ajax-reload')
	                	{
	                		$.AMUI.progress.done();
		            		if(result.code == 0)
		            		{
		            			// url跳转
		            			if(request_type == 'ajax-url')
		            			{
		            				Prompt(result.msg, 'success');
		            				setTimeout(function()
									{
										window.location.href = request_value;
									}, 1500);

		            			// 页面刷新
		            			} else if(request_type == 'ajax-reload')
		            			{
		            				Prompt(result.msg, 'success');
		            				setTimeout(function()
									{
										window.location.reload();
									}, 1500);
								}
							} else {
								Prompt(result.msg);
								$button.button('reset');
							}
						}
					},
					error:function(xhr, type)
		            {
		            	$.AMUI.progress.done();
		            	$button.button('reset');
		            	Prompt('服务器错误');
		            }
	            });
			}
			return false;
		}
	});
}
// 默认初始化一次,默认标签[form.form-validation]
FromInit('form.form-validation');

/**
 * [FormDataFill 表单数据填充]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-14T14:46:47+0800
 * @param    {[json]}    json [json数据对象]
 * @param    {[string]}  tag  [tag标签]
 */
function FormDataFill(json, tag)
{
	if(json != undefined)
	{
		if(tag == undefined)
		{
			tag = 'form.form-validation';
		}
		$form = $(tag);
		for(var i in json)
		{
			$form.find('input[type="hidden"][name="'+i+'"], input[type="text"][name="'+i+'"], input[type="password"][name="'+i+'"], input[type="email"][name="'+i+'"], input[type="number"][name="'+i+'"], input[type="date"][name="'+i+'"], textarea[name="'+i+'"], select[name="'+i+'"], input[type="url"][name="'+i+'"]').val(json[i]);

			// input radio
			$form.find('input[type="radio"][name="'+i+'"]').each(function(temp_value, temp_tag)
			{
				var state = (json[i] == temp_value);
				this.checked = state;
			});
		}

		// 是否存在pid和当前id相同
		if($form.find('select[name="pid"]').length > 0)
		{
			$form.find('select[name="pid"]').find('option').removeAttr('disabled');
			if((json['id'] || null) != null)
			{
				$form.find('select[name="pid"]').find('option[value="'+json['id']+'"]').attr('disabled', true);
			}
		}

		// 多选插件事件更新
		if($('.chosen-select').length > 0)
		{
			$('.chosen-select').trigger('chosen:updated');
		}
	}
}

/**
 * [Tree 树方法]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-13T10:30:23+0800
 * @param    {[int]}    	id    			[节点id]
 * @param    {[string]}   	url   			[请求url地址]
 * @param    {[int]}      	level 			[层级]
 * @param    {[int]}      	is_add_node 	[是否开启新增子级按钮]
 * @param    {[int]}      	is_delete_all	[是否所有开启删除按钮]
 */
function Tree(id, url, level, is_add_node, is_delete_all)
{
	$.ajax({
		url:url,
		type:'POST',
		dataType:"json",
		timeout:10000,
		data:{"id":id},
		success:function(result)
		{
			if(result.code == 0 && result.data.length > 0)
			{
				html = (id != 0) ? '' : '<table class="am-table am-table-striped am-table-hover">';
				is_add_node = is_add_node || 0;
				var is_astrict_rank = parseInt($('#tree').data('rank')) || 0;
				for(var i in result.data)
				{
					// 获取class
					var class_name = $('#data-list-'+id).attr('class') || '';

					// 数据 start
					var is_active = (result.data[i]['is_enable'] == 0) ? 'am-active' : '';
					html += '<tr id="data-list-'+result.data[i]['id']+'" class="'+class_name+' tree-pid-'+id+' '+is_active+'"><td>';
					tmp_level = (id != 0) ? parseInt(level)+20 : parseInt(level);
					var son_css = '';
					if(result.data[i]['is_son'] == 'ok')
					{
						html += '<i class="am-icon-plus c-p tree-submit" data-id="'+result.data[i]['id']+'" data-url="'+result.data[i]['ajax_url']+'" data-level="'+tmp_level+'" data-is_add_node="'+is_add_node+'" data-is_delete_all="'+is_delete_all+'" style="margin-right:8px;width:12px;';
						if(id != 0)
						{
							html += 'margin-left:'+tmp_level+'px;';
						}
						html += '"></i>';
					} else {
						son_css = 'padding-left:'+tmp_level+'px;';
					}
					html += '<span style="'+son_css+'">';
					if((result.data[i]['icon_url'] || null) != null)
					{
						html += '<a href="'+result.data[i]['icon_url']+'" target="_blank"><img src="'+result.data[i]['icon_url']+'" width="20" height="20" style="margin-right:5px;" /></a>';
					}
					html += '<span>'+(result.data[i]['name_alias'] || result.data[i]['name'])+'</span>';
					html += '</span>';


					// 数据 end

					// 操作项 start
					html += '<div class="fr m-r-20 submit">';

					// 新增
					var rank = tmp_level/20+1;
					if(is_add_node == 1 && (is_astrict_rank == 0 || rank < is_astrict_rank))
					{
						html += '<button class="am-btn am-btn-success am-btn-xs am-radius am-icon-plus c-p m-r-10 tree-submit-add-node" data-am-modal="{target: \'#data-save-win\'}" data-id="'+result.data[i]['id']+'"> 新增</button>';
					}

					// 编辑
					html += '<button class="am-btn am-btn-secondary am-btn-xs am-radius am-icon-edit c-p submit-edit" data-am-modal="{target: \'#data-save-win\'}" data-json=\''+result.data[i]['json']+'\' data-is_exist_son="'+result.data[i]['is_son']+'"> 编辑</button>';
					if(result.data[i]['is_son'] != 'ok' || is_delete_all == 1)
					{
						// 是否需要删除子数据
						var pid_class = is_delete_all == 1 ? '.tree-pid-'+result.data[i]['id'] : '';

						// 删除
						html += '<button class="am-btn am-btn-danger am-btn-xs am-radius am-icon-trash-o c-p m-l-10 submit-delete" data-id="'+result.data[i]['id']+'" data-url="'+result.data[i]['delete_url']+'" data-ext-delete-tag="'+pid_class+'"> 删除</button>';
					}
					html += '</div>';
					// 操作项 end
					
					html += '</td></tr>';
				}
				html += (id != 0) ? '' : '</table>';

				// 防止网络慢的情况下重复添加
				if($('#data-list-'+id).find('.tree-submit').attr('state') != 'ok')
				{
					if(id == 0)
					{
						$('#tree').html(html);
					} else {
						$('#data-list-'+id).after(html);
						$('#data-list-'+id).find('.tree-submit').attr('state', 'ok');
						$('#data-list-'+id).find('.tree-submit').removeClass('am-icon-plus');
						$('#data-list-'+id).find('.tree-submit').addClass('am-icon-minus-square');
					}
				}
			} else {
				$('#tree').find('p').text(result.msg);
				$('#tree').find('img').remove();
			}
		},
		error:function(xhr, type)
		{
			$('#tree').find('p').text('网络异常出错');
			$('#tree').find('img').remove();
		}
	});
}

/**
 * [ImageFileUploadShow 图片上传预览]
 * @param  {[string]} class_name 		[class名称]
 * @param  {[string]} show_img   		[预览图片id或class]
 * @param  {[string]} default_images    [默认图片]
 */
function ImageFileUploadShow(class_name, show_img, default_images)
{
	$(document).on("change", class_name, function(imgFile)
	{
		show_img = $(this).data('image-tag') || null;
		var status = false;
		if((imgFile.target.value || null) != null)
		{
			var filextension = imgFile.target.value.substring(imgFile.target.value.lastIndexOf("."),imgFile.target.value.length);
				filextension = filextension.toLowerCase();
			if((filextension!='.jpg') && (filextension!='.gif') && (filextension!='.jpeg') && (filextension!='.png') && (filextension!='.bmp'))
			{
				Prompt("图片格式错误，请重新上传");
			} else {
				if(document.all)
				{
					Prompt('ie浏览器不可用');
					/*imgFile.select();
					path = document.selection.createRange().text;
					$(this).parent().parent().find('img').attr('src', '');
					$(this).parent().parent().find('img').attr('src', path);  //使用滤镜效果  */
				} else {
					var url = window.URL.createObjectURL(imgFile.target.files[0]);// FF 7.0以上
					$(show_img).attr('src', url);
					status = true;
				}
			}
		}
		var default_img = $(show_img).data('default') || null;
		if(status == false && ((default_images || null) != null || default_img != null))
		{
			$(show_img).attr('src', default_images || default_img);
		}
	});
}

/**
 * [VideoFileUploadShow 视频上传预览]
 * @param  {[string]} class_name 		[class名称]
 * @param  {[string]} show_video   		[预览视频id或class]
 * @param  {[string]} default_video     [默认视频]
 */
function VideoFileUploadShow(class_name, show_video, default_video)
{
	$(document).on("change", class_name, function(imgFile)
	{
		show_video = $(this).data('video-tag') || null;
		var status = false;
		if((imgFile.target.value || null) != null)
		{
			var filextension = imgFile.target.value.substring(imgFile.target.value.lastIndexOf("."),imgFile.target.value.length);
				filextension = filextension.toLowerCase();
			if(filextension != '.mp4')
			{
				Prompt("视频格式错误，请重新上传");
			} else {
				if(document.all)
				{
					Prompt('ie浏览器不可用');
					/*imgFile.select();
					path = document.selection.createRange().text;
					$(this).parent().parent().find('img').attr('src', '');
					$(this).parent().parent().find('img').attr('src', path);  //使用滤镜效果  */
				} else {
					var url = window.URL.createObjectURL(imgFile.target.files[0]);// FF 7.0以上
					$(show_video).attr('src', url);
					status = true;
				}
			}
		}
		var default_video = $(show_video).data('default') || null;
		if(status == false && ((default_video || null) != null || default_video != null))
		{
			$(show_video).attr('src', default_video || default_video);
		}
	});
}

 
// 校验浏览器是否支持视频播放
function CheckVideo()
{
	if(document.createElement('video').canPlayType)
	{
		var vid_test = document.createElement("video");
		var ogg_test = vid_test.canPlayType('video/ogg; codecs="theora, vorbis"');
		if(!ogg_test)
		{
			h264_test = vid_test.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');
			if(!h264_test)
			{
				document.getElementById("checkVideoResult").innerHTML = "Sorry. No video support."
			} else {
				if(h264_test == "probably")
				{
					document.getElementById("checkVideoResult").innerHTML = "Yes! Full support!";
				} else {
					document.getElementById("checkVideoResult").innerHTML = "Well. Some support.";
				}
			}
		} else {
			if(ogg_test == "probably")
			{
				document.getElementById("checkVideoResult").innerHTML = "Yes! Full support!";
			} else {
				document.getElementById("checkVideoResult").innerHTML = "Well. Some support.";
			}
		}
	} else {
		document.getElementById("checkVideoResult").innerHTML = "Sorry. No video support."
	}
}

/**
 * 弹窗加载
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-13
 * @desc    description
 * @param   {[string]}        url   	[加载url]
 * @param   {[string]}        title 	[标题]
 * @param   {[string]}        tag   	[指定id标记]
 * @param   {[string]}        class_tag [指定class]
 */
function ModalLoad(url, title, tag, class_tag)
{
	tag = tag || 'common-popup-modal';
	if($('#'+tag).length > 0)
	{
		$('#'+tag).remove();
	}

	var html = '<div class="am-popup popup-iframe '+class_tag+'" id="'+tag+'">';
		html += '<div class="am-popup-inner">';
	    html += '<div class="am-popup-hd">';
	    html += '<h4 class="am-popup-title">'+(title || '温馨提示')+'</h4>';
	    html += '<span data-am-modal-close class="am-close">&times;</span>';
		html += '</div>';
	    html += '<iframe src="'+url+'"></iframe>';
		html += '</div>';
		html += '</div>';
	$('body').append(html);
	$('#'+tag).modal();
}

/**
 * 价格四舍五入，并且指定保留小数位数
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-14
 * @desc    description
 * @param   {[float]}      value [金额]
 * @param   {[int]}        pos   [位数 默认2]
 */
function FomatFloat(value, pos)
{
	pos = pos || 2;
	var f_x = Math.round(value*Math.pow(10, pos))/Math.pow(10, pos);

	var s_x = f_x.toString();
	var pos_decimal = s_x.indexOf('.');
	if(pos_decimal < 0)
	{
	  pos_decimal = s_x.length;
	  s_x += '.';
	}
	while (s_x.length <= pos_decimal + 2)
	{
	  s_x += '0';
	}
	return s_x;
}

/**
 * [DataDelete 数据删除]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-24T08:24:58+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function DataDelete(e)
{
	var id = e.data('id');
	var url = e.data('url');
	var view = e.data('view') || 'delete';
	var value = e.data('value') || null;
	var ext_delete_tag = e.data('ext-delete-tag') || null;

	if((id || null) == null || (url || null) == null)
	{
		Prompt('参数配置有误');
		return false;
	}

	// 请求删除数据
	$.ajax({
		url:url,
		type:'POST',
		dataType:"json",
		timeout:10000,
		data:{"id":id},
		success:function(result)
		{
			if(result.code == 0)
			{
				Prompt(result.msg, 'success');

				switch(view)
				{
					// 成功则删除数据列表
					case 'delete' :
						Prompt(result.msg, 'success');
						$('#data-list-'+id).remove();
						if(ext_delete_tag != null)
						{
							$(ext_delete_tag).remove();
						}
						break;

					// 刷新
					case 'reload' :
						Prompt(result.msg, 'success');
						setTimeout(function()
						{
							window.location.reload();
						}, 1500);
						break;

					// 回调函数
					case 'fun' :
						if(IsExitsFunction(value))
                		{
                			result['data_id'] = id;
                			window[value](result);
                		} else {
                			Prompt('['+value+']配置方法未定义');
                		}
						break;

					// 跳转
					case 'jump' :
						Prompt(result.msg, 'success');
						if(value != null)
						{
							setTimeout(function()
							{
								window.location.href = value;
							}, 1500);
						}
						break;

					// 默认提示成功
					default :
						Prompt(result.msg, 'success');
				}
				// 成功则删除数据列表
				$('#data-list-'+id).remove();
			} else {
				Prompt(result.msg);
			}
		},
		error:function(xhr, type)
		{
			Prompt('网络异常出错');
		}
	});
}

/**
 * [ConfirmDataDelete 数据删除]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-24T08:24:58+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function ConfirmDataDelete(e)
{
	var title = e.data('title') || '温馨提示';
	var msg = e.data('msg') || '删除后不可恢复、确认操作吗？';
	var is_confirm = (e.data('is-confirm') == undefined || e.data('is-confirm') == 1) ? 1 : 0;

	if(is_confirm == 1)
	{
		AMUI.dialog.confirm({
			title: title,
			content: msg,
			onConfirm: function(options)
			{
				DataDelete(e);
			},
			onCancel: function(){}
		});
	} else {
		DataDelete(e);
	}
}

/**
 * [ConfirmNetworkAjax 确认网络请求]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-24T08:24:58+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function ConfirmNetworkAjax(e)
{
	var id = e.data('id');
	var value = e.data('value') || '';
	var url = e.data('url');
	var view = e.data('view') || '';
	var title = e.data('title') || '温馨提示';
	var msg = e.data('msg') || '操作后不可恢复、确认继续吗？';

	AMUI.dialog.confirm({
		title: title,
		content: msg,
		onConfirm: function(e)
		{
			// ajax
			$.ajax({
				url:url,
				type:'POST',
				dataType:"json",
				timeout:10000,
				data:{id:id, value: value},
				success:function(result)
				{
					if(result.code == 0)
					{
						switch(view)
						{
							// 成功则删除数据列表
							case 'delete' :
								Prompt(result.msg, 'success');
								$('#data-list-'+id).remove();
								break;

							// 刷新
							case 'reload' :
								Prompt(result.msg, 'success');
								setTimeout(function()
								{
									window.location.reload();
								}, 1500);
								break;

							// 回调函数
							case 'fun' :
								if(IsExitsFunction(value))
		                		{
		                			window[value](result);
		                		} else {
		                			Prompt('['+value+']配置方法未定义');
		                		}
								break;

							// 默认提示成功
							default :
								Prompt(result.msg, 'success');
						}
					} else {
						Prompt(result.msg);
					}
				},
				error:function(xhr, type)
				{
					Prompt('网络异常出错');
				}
			});
		},
		onCancel: function(){}
	});
}

/**
 * 开启全屏
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-01
 * @desc    description
 */
function FullscreenOpen()
{
    var elem = document.body;
    if(elem.webkitRequestFullScreen)
    {
        elem.webkitRequestFullScreen();
    } else if (elem.mozRequestFullScreen)
    {
        elem.mozRequestFullScreen();
    } else if (elem.requestFullScreen)
    {
        elem.requestFullScreen();
    } else {
        Prompt("浏览器不支持全屏API或已被禁用");
        return false;
    }
    return true;
}

/**
 * 关闭全屏
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-01
 * @desc    description
 */
function FullscreenExit()
{
    var elem = document;
    if (elem.webkitCancelFullScreen)
    {
        elem.webkitCancelFullScreen();
    } else if (elem.mozCancelFullScreen)
    {
        elem.mozCancelFullScreen();
    } else if (elem.cancelFullScreen)
    {
        elem.cancelFullScreen();
    } else if (elem.exitFullscreen)
    {
        elem.exitFullscreen();
    } else {
        Prompt("浏览器不支持全屏API或已被禁用");
        return false;
    }
    return true;
}

/**
 * 全屏ESC监听
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-01
 * @desc    description
 */
var fullscreen_counter = 0;
function FullscreenEscEvent()
{
	fullscreen_counter++;
	if(fullscreen_counter%2 == 0)
	{
		var $fullscreen = $('.fullscreen-event');
		if(($fullscreen.attr('data-status') || 0) == 1)
		{
			$fullscreen.find('.fullscreen-text').text($fullscreen.data('fulltext-open') || '开启全屏');
			$fullscreen.attr('data-status', 0);
		}
	}
}

/**
 * url参数替换，参数不存在则添加
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-03-20
 * @desc    description
 * @param   {[string]}        field [字段名称]
 * @param   {[string]}        value [字段值]
 * @param   {[string]}        url   [自定义url]
 */
function UrlFieldReplace(field, value, url)
{
    // 当前页面url地址
    url = url || window.location.href;

    // 锚点
    var anchor = '';
    if(url.indexOf('#') >= 0)
    {
        anchor = url.substr(url.indexOf('#'));
        url = url.substr(0, url.indexOf('#'));
    }

    if(url.indexOf('?') >= 0)
    {
        var str = url.substr(0, url.lastIndexOf('.'));
        var ext = url.substr(url.lastIndexOf('.'));
        if(str.indexOf(field) >= 0)
        {
            var first = str.substr(0, str.lastIndexOf(field));
            var last = str.substr(str.lastIndexOf(field));
                last = last.replace(new RegExp(field+'/', 'g'), '');
                last = (last.indexOf('/') >= 0) ? last.substr(last.indexOf('/')) : '';
                url = first+field+'/'+value+last+ext;
        } else {
            if(ext.indexOf('?') >= 0)
            {
                var p = '';
                exts = ext.substr(ext.indexOf('?')+1);
                if(ext.indexOf(field) >= 0)
                {
                    var params_all = exts.split('&');
                    for(var i in params_all)
                    {
                        var temp = params_all[i].split('=');

                        if(temp.length >= 2)
                        {
                            if(i > 0)
                            {
                                p += '&';
                            }
                            if(temp[0] == field)
                            {
                                p += field+'='+value;
                            } else {
                                p += params_all[i];
                            }
                        }
                    }
                } else {
                    p = exts+'&'+field+'='+value;
                }
                url = str+(ext.substr(0, ext.indexOf('?')))+'?'+p;
            } else {
                url = str+'/'+field+'/'+value+ext;
            }
        }
    } else {
        url += '?'+field+'='+value;
    }
    return url+anchor;
}


// 公共数据操作
$(function()
{
	// 全屏操作
	$('.fullscreen-event').on('click', function()
	{
		var status = $(this).attr('data-status') || 0;
		if(status == 0)
		{
			if(FullscreenOpen())
			{
				$(this).find('.fullscreen-text').text($(this).data('fulltext-exit') || '退出全屏');
			}
		} else {
			if(FullscreenExit())
			{
				$(this).find('.fullscreen-text').text($(this).data('fulltext-open') || '开启全屏');
			}
		}
		$(this).attr('data-status', status == 0 ? 1 : 0);
		$(this).attr('data-status-y', status);
	});
	
	// esc退出全屏事件
	document.addEventListener("fullscreenchange", function(e) {
	  FullscreenEscEvent();
	});
	document.addEventListener("mozfullscreenchange", function(e) {
	  FullscreenEscEvent();
	});
	document.addEventListener("webkitfullscreenchange", function(e) {
	  FullscreenEscEvent();
	});
	document.addEventListener("msfullscreenchange", function(e) {
	  FullscreenEscEvent();
	});


	// 多选插件初始化
	if($('.chosen-select').length > 0)
	{
		$('.chosen-select').chosen({
			inherit_select_classes: true,
			enable_split_word_search: true,
			search_contains: true
		});
	}
	// 多选插件 空内容失去焦点验证bug兼容处理
	$(document).on('blur', 'ul.chosen-choices .search-field, div.chosen-select .chosen-search', function()
	{
		if($(this).parent().find('li').length <= 1 || $(this).parent().parent().find('.chosen-default').length >= 1)
		{
			$(this).parent().parent().prev().trigger('blur');
		}
	});
	
	/**
	 * [submit-delete 删除数据列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:22:39+0800
	 * @param    {[int] 	[data-id] 	[数据id]}
	 * @param    {[string] 	[data-url] 	[请求地址]}
	 */
	$(document).on('click', '.submit-delete', function()
	{
		ConfirmDataDelete($(this));
	});

	/**
	 * [submit-state 公共数据状态操作]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:22:39+0800
	 * @param    {[int] 	[data-id] 	[数据id]}
	 * @param    {[int] 	[data-state][状态值]}
	 * @param    {[string] 	[data-url] 	[请求地址]}
	 */
	$(document).on('click', '.submit-state', function()
	{		
		// 获取参数
		var $tag = $(this);
		var id = $tag.data('id');
		var state = ($tag.data('state') == 1) ? 0 : 1;
		var url = $tag.data('url');
		var field = $tag.data('field') || '';
		var is_update_status = $tag.data('is-update-status') || 0;
		if(id == undefined || url == undefined)
		{
			Prompt('参数配置有误');
			return false;
		}

		// 请求更新数据
		$.ajax({
			url:url,
			type:'POST',
			dataType:"json",
			timeout:10000,
			data:{"id":id, "state":state, "field":field},
			success:function(result)
			{
				if(result.code == 0)
				{
					Prompt(result.msg, 'success');

					// 成功则更新数据样式
					if($tag.hasClass('am-success'))
					{
						$tag.removeClass('am-success');
						$tag.addClass('am-default');
						if(is_update_status == 1)
						{
							if($('#data-list-'+id).length > 0)
							{
								$('#data-list-'+id).addClass('am-active');
							}
						}
					} else {
						$tag.removeClass('am-default');
						$tag.addClass('am-success');
						if(is_update_status == 1)
						{
							if($('#data-list-'+id).length > 0)
							{
								$('#data-list-'+id).removeClass('am-active');
							}
						}
					}
					$tag.data('state', state);
				} else {
					Prompt(result.msg);
				}
			},
			error:function(xhr, type)
			{
				Prompt('网络异常出错');
			}
		});
	});

	/**
	 * 编辑窗口额为参数处理
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-08-07
	 * @desc    description
	 * @param   {[object]}        data [数据]
	 * @param   {[string]}        type [edit, add]
	 * @return  {[object]}             [处理后的数据]
	 */
	function FunSaveWinAdditional(data, type)
	{
		// 额外处理数据
		if($('#tree').length > 0)
		{
			var additional = $('#tree').data('additional') || null;
			if(additional != null)
			{
				for(var i in additional)
				{
					var value = (type == 'add') ? (additional[i]['value'] || '') : (data[additional[i]['field']] || additional[i]['value'] || '');
					switch(additional[i]['type'])
					{
						// 表单
						case 'input' :
						case 'select' :
						case 'textarea' :
							data[additional[i]['field']] = value;
							break;

						// 样式处理
						case 'css' :
							$(additional[i]['tag']).css(additional[i]['style'], value);
							break;

						// 文件
						case 'file' :
							var $file_tag = $(additional[i]['tag']);
							if($file_tag.val().length > 0)
							{
								$file_tag.after($file_tag.clone().val(''));
								$file_tag.val('');
							}
							break;

						// 属性
						case 'attr' :
							$(additional[i]['tag']).attr(additional[i]['style'], value);
							break;

						// 内容替换
						case 'html' :
							$(additional[i]['tag']).html(value);
							break;
					}
				}

			}
		}

		return data;
	}

	/**
	 * [submit-edit 公共编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T13:53:13+0800
	 */
	$(document).on('click', '.submit-edit', function()
	{
		// 窗口标签
		var tag = $(this).data('tag') || 'data-save-win';

		// 更改窗口名称
		if($('#'+tag).length > 0)
		{
			$title = $('#'+tag).find('.am-popup-title');
			$title.text($title.data('edit-title'));
		}
		
		// 填充数据
		var data = FunSaveWinAdditional($(this).data('json'), 'edit');

		// 开始填充数据
		FormDataFill(data, '#'+tag);
	});

	/**
	 * [tree-submit-add-node 公共无限节点 - 新子节点]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:12:10+0800
	 */
	$('#tree').on('click', '.tree-submit-add-node', function()
	{
		// 清空表单数据
		TreeFormInit();

		// 父节点赋值
		var id = parseInt($(this).data('id')) || 0;
		$('#data-save-win').find('input[name="pid"], select[name="pid"]').val(id);

		// 多选插件事件更新
		if($('.chosen-select').length > 0)
		{
			$('.chosen-select').trigger('chosen:updated');
		}
	});

	/**
	 * [tree-submit 公共无限节点]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:12:10+0800
	 */
	$('#tree').on('click', '.tree-submit', function()
	{
		var id = parseInt($(this).data('id')) || 0;
		// 状态
		if($('#data-list-'+id).find('.tree-submit').attr('state') == 'ok')
		{
			if($(this).hasClass('am-icon-plus'))
			{
				$(this).removeClass('am-icon-plus');
				$(this).addClass('am-icon-minus-square');
				$('.tree-pid-'+id).css('display', 'grid');
			} else {
				$(this).removeClass('am-icon-minus-square');
				$(this).addClass('am-icon-plus');
				$('.tree-pid-'+id).css('display', 'none');
			}
		} else {
			var url = $(this).data('url') || '';
			var level = parseInt($(this).data('level')) || 0;
			var is_add_node = parseInt($(this).data('is_add_node')) || 0;
			var is_delete_all = parseInt($(this).data('is_delete_all')) || 0;
			if(id > 0 && url != '')
			{
				Tree(id, url, level, is_add_node, is_delete_all);
			} else {
				Prompt('参数有误');
			}
		}
	});

	/**
	 * [tree-submit-add 公共无限节点新增按钮处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:11:34+0800
	 */
	$('.tree-submit-add').on('click', function()
	{
		TreeFormInit();
	});

	/**
	 * 添加窗口初始化
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-08-06
	 * @desc    description
	 */
	function TreeFormInit()
	{
		// 更改窗口名称
		$title = $('#data-save-win').find('.am-popup-title');
		$title.text($title.data('add-title'));

		// 填充数据
		var data = {"id":"", "pid":0, "name":"", "sort":0, "is_enable":1, "icon":""};

		// 额外处理数据
		data = FunSaveWinAdditional(data, 'init');

		// 清空表单
		FormDataFill(data);

		// 移除菜单禁止状态
		$('form select[name="pid"]').removeAttr('disabled');

		// 校验成功状态增加失去焦点
		$('form').find('.am-field-valid').each(function()
		{
			$(this).blur();
		});

		// 多选插件事件更新
		if($('.chosen-select').length > 0)
		{
			$('.chosen-select').trigger('chosen:updated');
		}
	}

	/**
	 * [submit-ajax 公共数据ajax操作]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:22:39+0800
	 * @param    {[int] 	[data-id] 	[数据id]}
	 * @param    {[int] 	[data-view] [完成操作（delete删除数据, reload刷新页面, fun方法回调(data-value)]}
	 * @param    {[string] 	[data-url] 	[请求地址]}
	 */
	$(document).on('click', '.submit-ajax', function()
	{
		ConfirmNetworkAjax($(this));
	});

	/**
	 * [RegionNodeData 地区联动]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-09-23T22:00:30+0800
	 * @param    {[int]}                 value     [数据值]
	 * @param    {[string]}              name      [当前节点name名称]
	 * @param    {[string]}              next_name [下一个节点名称（数据渲染节点）]
	 */
	function RegionNodeData(value, name, next_name)
	{
		if(value != null)
		{
			$.ajax({
				url:$('.region-linkage').data('url'),
				type:'POST',
				data:{"pid": value},
				dataType:'json',
				success:function(result)
				{
					if(result.code == 0)
					{
						/* html拼接 */
						var html = '';
						var value = $('.region-linkage select[name='+next_name+']').data('value') || 0;
						for(var i in result.data)
						{
							html += '<option value="'+result.data[i]['id']+'"';
							if(value != 0 && value == result.data[i]['id'])
							{
								html += ' selected ';
							}
							html += '>'+result.data[i]['name']+'</option>';
						}

						/* 下一级数据添加 */
						$('.region-linkage select[name='+next_name+']').append(html).trigger('chosen:updated');
					} else {
						Prompt(result.msg);
					}
				}
			});
		}

		/* 子级元素数据清空 */
		var child = null;
		switch(name)
		{
			case 'province' :
				child = ['city', 'county'];
				break;

			case 'city' :
				child = ['county'];
				break;
		}
		if(child != null)
		{
			for(var i in child)
			{
				var $temp_obj = $('.region-linkage select[name='+child[i]+']');
				var temp_find = $temp_obj.find('option').first().text();
				var temp_html = '<option value="">'+temp_find+'</option>';
				$temp_obj.html(temp_html).trigger('chosen:updated');
			}
		}
	}

	// 地区联动
	$('.region-linkage select').on('change', function()
	{
		var name = $(this).attr('name') || null;
		var next_name = (name == 'province') ? 'city' : ((name == 'city') ? 'county' : null);
		var value = $(this).val() || null;
		if(next_name != null)
		{
			RegionNodeData(value, name, next_name);
		}
	});
	if($('.region-linkage select').length > 0)
	{
		// 省初始化
		RegionNodeData(0, 'province', 'province');

		// 市初始化
		var value = $('.region-linkage select[name=province]').data('value') || 0;
		if(value != 0)
		{
			RegionNodeData(value, 'city', 'city');
		}

		// 区/县初始化
		var value = $('.region-linkage select[name=city]').data('value') || 0;
		if(value != 0)
		{
			RegionNodeData(value, 'county', 'county');
		}
	}

	// 根据字符串地址获取坐标位置
	$('#map-location-submit').on('click', function()
	{
		var region = ["province", "city", "county"];
		var province = '';
		var address = '';
		for(var i in region)
		{
			var $temp_obj = $('.region-linkage select[name='+region[i]+']');
			var v = $temp_obj.find('option:selected').val();
			if(v.length > 0)
			{
				if(i == 0) province = $temp_obj.find('option:selected').text();
				address += $temp_obj.find('option:selected').text();
			}
		}
		address += $('#form-address').val();

		var map = new BMap.Map("map", {enableMapClick:false});
		var point = new BMap.Point(116.331398,39.897445);
		var level = $('#map').data('level') || 16;
		map.centerAndZoom(point, level);

		// 创建地址解析器实例
		var myGeo = new BMap.Geocoder();
		// 将地址解析结果显示在地图上,并调整地图视野
		myGeo.getPoint(address, function(point) {
			if (point) {
				map.centerAndZoom(point, level);
				var navigationControl = new BMap.NavigationControl({
					// 靠左上角位置
					anchor: BMAP_ANCHOR_TOP_LEFT,
					// LARGE类型
					type: BMAP_NAVIGATION_CONTROL_LARGE,
				});
				map.addControl(navigationControl);

	          	var marker = new BMap.Marker(point);  // 创建标注
				map.addOverlay(marker);              // 将标注添加到地图中
	            marker.enableDragging();           // 可拖拽

	            /* 设置版权控件位置 */
				var cr = new BMap.CopyrightControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT});
				map.addControl(cr); //添加版权控件
				var bs = map.getBounds();   //返回地图可视区域
				cr.addCopyright({id: 1, content: "<div class='map-copy'><span>拖动红色图标直接定位</span></div>", bounds:bs});

	            //增加拖动后事件
	            marker.addEventListener("dragend", function(e) {
	            	map.panTo(e.point);
					$('#form-lng').val(e.point.lng);
					$('#form-lat').val(e.point.lat);
				});

	            //获取地址坐标
	            var p = marker.getPosition();       //获取marker的位置
	            $('#form-lng').val(p.lng);
				$('#form-lat').val(p.lat);
			}else{
				Prompt("您选择地址没有解析到结果!");
			}
		}, province);
	});

	// 图片上传
    $(document).on('change', '.images-file-event, .file-event', function()
    {
    	// 显示选择的图片名称
        var fileNames = '';
        $.each(this.files, function()
        {
            fileNames += '<span class="am-badge">' + this.name + '</span> ';
        });
        $($(this).data('tips-tag')).html(fileNames);

        // 触发配合显示input地址事件
        var input_tag = $(this).data('choice-one-to') || null;
        if(input_tag != null)
        {
        	$(input_tag).trigger('blur');
        }
    });

    // 图片预览
    if($('.images-file-event').length > 0)
    {
    	ImageFileUploadShow('.images-file-event');
    }

    // 视频上传
    $(document).on('change', '.video-file-event', function()
    {
    	// 显示选择的图片名称
        var fileNames = '';
        $.each(this.files, function()
        {
            fileNames += '<span class="am-badge">' + this.name + '</span> ';
        });
        $($(this).data('tips-tag')).html(fileNames);

        // 触发配合显示input地址事件
        var input_tag = $(this).data('choice-one-to') || null;
        if(input_tag != null)
        {
        	$(input_tag).trigger('blur');
        }
    });

    // 视频预览
    if($('.video-file-event').length > 0)
    {
    	VideoFileUploadShow('.video-file-event');
    }


	// 颜色选择器
	if($('.colorpicker-submit').length > 0)
	{
	    $('.colorpicker-submit').colorpicker(
	    {
	        fillcolor:true,
	        success:function(o, color)
	        {
	        	var style = o.context.dataset.colorStyle || 'color';
	            $(o.context.dataset.inputTag).css(style, color);
	            $(o.context.dataset.colorTag).val(color);
	        },
	        reset:function(o)
	        {
	        	var style = o.context.dataset.colorStyle || 'color';
	            $(o.context.dataset.inputTag).css(style, '');
	            $(o.context.dataset.colorTag).val('');
	        }
	    });
	}


    // 监听多图上传和上传附件组件的插入动作
    if(typeof(upload_editor) == 'object')
    {
	    upload_editor.ready(function()
	    {
	        // 图片上传动作
	        upload_editor.addListener("beforeInsertImage", function(t, result)
	        {
	            if(result.length > 0)
	            {
	                var $tag = $($('body').attr('view-tag'));
	                var max_number = $tag.data('max-number') || 0;
	                var is_delete = ($tag.data('delete') == undefined) ? 1 : $tag.data('delete');

	                // 只限制一条
	                if(max_number <= 1)
	                {
	                    $tag.find('li').remove();
	                }

	                // 循环处理
	                for(var i in result)
	                {
	                    if(max_number > 0 && $tag.find('li').length >= max_number)
	                    {
	                        Prompt('最多上传'+max_number+'张图片');
	                        break;
	                    }

	                    var html = '<li>';
	                        html += '<input type="text" name="'+$tag.data('form-name')+'" value="'+result[i].src+'" />';
	                        html += '<img src="'+result[i].src+'" />';
	                        if(is_delete == 1)
	                        {
	                        	html += '<i>×</i>';
	                        }
	                        html += '</li>';
	                    $tag.append(html);
	                }
	            }
	        });

	        // 视频上传
	        upload_editor.addListener("beforeInsertVideo", function(t, result)
	        {
	            if(result.length > 0)
	            {
	                var $tag = $($('body').attr('view-tag'));
	                var max_number = $tag.data('max-number') || 0;
	                var is_delete = ($tag.data('delete') == undefined) ? 1 : $tag.data('delete');

	                // 只限制一条
	                if(max_number <= 1)
	                {
	                    $tag.find('li').remove();
	                }

	                // 循环处理
	                for(var i in result)
	                {
	                    if(max_number > 0 && $tag.find('li').length >= max_number)
	                    {
	                        Prompt('最多上传'+max_number+'个视频');
	                        break;
	                    }

	                    var $tag = $($('body').attr('view-tag'));
	                    var html = '<li>';
	                        html += '<input type="text" name="'+$tag.data('form-name')+'" value="'+result[i].src+'" />';
	                        html += '<video src="'+result[i].src+'" controls>your browser does not support the video tag</video>';
	                        if(is_delete == 1)
	                        {
	                        	html += '<i>×</i>';
	                        }
	                        html += '</li>';
	                    $tag.append(html);
	                }
	            }
	        });

	        // 文件上传
	        upload_editor.addListener("beforeInsertFile", function(t, result)
	        {
	            var fileHtml = '';
	            for(var i in result){
	                fileHtml += '<li><a href="'+result[i].url+'" target="_blank">'+result[i].url+'</a></li>';
	            }
	            document.getElementById('upload_video_wrap').innerHTML = fileHtml;

	            if(result.length > 0)
	            {
	                var $tag = $($('body').attr('view-tag'));
	                var max_number = $tag.data('max-number') || 0;
	                var is_delete = ($tag.data('delete') == undefined) ? 1 : $tag.data('delete');

	                // 只限制一条
	                if(max_number <= 1)
	                {
	                    $tag.find('li').remove();
	                }

	                // 循环处理
	                for(var i in result)
	                {
	                    if(max_number > 0 && $tag.find('li').length >= max_number)
	                    {
	                        Prompt('最多上传'+max_number+'个附件');
	                        break;
	                    }

	                    var $tag = $($('body').attr('view-tag'));
	                    var html = '<li>';
	                        html += '<input type="text" name="'+$tag.data('form-name')+'" value="'+result[i].src+'" />';
	                        html += '<a href="'+result[i].src+'">'+result[i].src+'</a>';
	                        if(is_delete == 1)
	                        {
	                        	html += '<i>×</i>';
	                        }
	                        html += '</li>';
	                    $tag.append(html);
	                }
	            }
	        });
	    });
	}

    // 打开编辑器插件
    $(document).on('click', '.plug-file-upload-submit', function()
    {
    	// 组件是否初始化
    	if(typeof(upload_editor) != 'object')
    	{
    		Prompt('组件未初始化');
            return false;
    	}

    	// 容器是否指定
        if(($(this).data('view-tag') || null) == null)
        {
            Prompt('未指定容器');
            return false;
        }

        // 容器
        var $view_tag = $($(this).data('view-tag'));

        // 加载组建类型
        var dialog_type = null;
        switch($view_tag.data('dialog-type'))
        {
            // 视频
            case 'video' :
                dialog_type = 'insertvideo';
                break;

            // 图片
            case 'images' :
                dialog_type = 'insertimage';
                break;

            // 文件
            case 'file' :
                dialog_type = 'attachment';
                break;
        }
        if(dialog_type == null)
        {
            Prompt('未指定加载组建');
            return false;
        }

        // 是否指定form名称
        if(($view_tag.data('form-name') || null) == null)
        {
            Prompt('未指定表单name名称');
            return false;
        }

        // 打开组建
        var dialog = upload_editor.getDialog(dialog_type);
        dialog.render();
        dialog.open();

        // 赋值参数
        $('body').attr('view-tag',$(this).data('view-tag'));
    });

    // 删除容器中的内容
    $(document).on('click', '.plug-file-upload-view li i', function()
    {
        // 容器
        var $tag = $(this).parents('ul.plug-file-upload-view');

        // 删除数据
        $(this).parent().remove();

        // 数据处理
        var max_number = $tag.data('max-number') || 0;
        if(max_number > 0)
        {
            if($tag.find('li').length < max_number)
            {
                $('.plug-file-upload-submit').show();
            }
        }
    });


    /* 搜索切换 */
	var $more_where = $('.more-where');
	$more_submit = $('.more-submit');
	$more_submit.find('input[name="is_more"]').change(function()
	{
		if($more_submit.find('i').hasClass('am-icon-angle-down'))
		{
			$more_submit.find('i').removeClass('am-icon-angle-down');
			$more_submit.find('i').addClass('am-icon-angle-up');
		} else {
			$more_submit.find('i').addClass('am-icon-angle-down');
			$more_submit.find('i').removeClass('am-icon-angle-up');
		}
	
		if($more_submit.find('input[name="is_more"]:checked').val() == undefined)
		{
			$more_where.addClass('none');
		} else {
			$more_where.removeClass('none');
		}
	});

});