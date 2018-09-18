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
function Prompt(msg, type, time)
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
		if(type == undefined || type == '') type = 'danger';
		var html = '<div id="common-prompt" class="am-alert am-alert-'+type+' am-animation-slide-top" data-am-alert><button type="button" class="am-close am-close-spin">&times;</button><p>'+msg+'</p></div>';
		$('body').append(html);

		// 自动关闭提示
		temp_time_out = setTimeout(function()
		{
			$('#common-prompt').slideToggle();
		}, (time || 3)*1000);
	}
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
	temp_field = '';
	i = 0;
	$(element).find('input[type="checkbox"]').each(function(key, tmp)
	{
		if(tmp.name != undefined && tmp.name != '')
		{
			// name不一样的时候初始化索引值
			if(temp_field != tmp.name)
			{
				i = 0;
			}
			if($(this).is(':checked'))
			{
				if(tmp_all[tmp.name] == undefined) tmp_all[tmp.name] = [];
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
 * [Get_Tag_Value 根据tag对象获取值]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-10-07T20:53:40+0800
 * @param    {[object]}         tag_obj [tag对象]
 */
function Get_Tag_Value(tag_obj)
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
					var v1 = Get_Tag_Value($(validity.field));
					var v2 = Get_Tag_Value($(tag));
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
	                timeout:10000,
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
		            	Prompt('网络异常错误');
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
 * @param    {[int]}    	id    		[节点id]
 * @param    {[string]}   	url   		[请求url地址]
 * @param    {[int]}      	level 		[层级]
 * @param    {[int]}      	is_add_node [是否开启新增子级按钮]
 */
function Tree(id, url, level, is_add_node)
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
						html += '<i class="am-icon-plus c-p tree-submit" data-id="'+result.data[i]['id']+'" data-url="'+result.data[i]['ajax_url']+'" data-level="'+tmp_level+'" data-is_add_node="'+is_add_node+'" style="margin-right:8px;width:12px;';
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
						html += '<span class="am-icon-plus am-icon-sm c-p m-r-20 tree-submit-add-node" data-am-modal="{target: \'#data-save-win\'}" data-id="'+result.data[i]['id']+'"></span>';
					}

					// 编辑
					html += '<span class="am-icon-edit am-icon-sm c-p submit-edit" data-am-modal="{target: \'#data-save-win\'}" data-json=\''+result.data[i]['json']+'\' data-is_exist_son="'+result.data[i]['is_son']+'"></span>';
					if(result.data[i]['is_son'] != 'ok')
					{
						// 删除
						html += '<span class="am-icon-trash-o am-icon-sm c-p m-l-20 m-r-15 submit-delete" data-id="'+result.data[i]['id']+'" data-url="'+result.data[i]['delete_url']+'"></span>';
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
 * [img_file_upload_show 图片上传预览]
 * @param  {[string]} class_name 		[class名称]
 * @param  {[string]} show_img   		[预览图片id或class]
 * @param  {[string]} default_images    [默认图片]
 */
function img_file_upload_show(class_name, show_img, default_images)
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


// 公共数据操作
$(function()
{
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
		var id = $(this).data('id');
		var url = $(this).data('url');
		var title = $(this).data('title') || '温馨提示';
		var msg = $(this).data('msg') || '删除后不可恢复、确认操作吗？';

		AMUI.dialog.confirm({
			title: title,
			content: msg,
			onConfirm: function(options)
			{
				if((id || null) == null || (url || null) == null)
				{
					Prompt('参数配置有误');
				} else {
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
			},
			onCancel: function(){}
		});
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
	function fun_save_win_additional(data, type)
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
		var data = fun_save_win_additional($(this).data('json'), 'edit');

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
		tree_form_init();

		// 父节点赋值
		var id = parseInt($(this).data('id')) || 0;
		$('#data-save-win').find('input[name="pid"]').val(id);
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
			if(id > 0 && url != '')
			{
				Tree(id, url, level, is_add_node);
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
		tree_form_init();
	});

	/**
	 * 添加窗口初始化
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-08-06
	 * @desc    description
	 */
	function tree_form_init()
	{
		// 更改窗口名称
		$title = $('#data-save-win').find('.am-popup-title');
		$title.text($title.data('add-title'));

		// 填充数据
		var data = {"id":"", "pid":0, "name":"", "sort":0, "is_enable":1, "icon":""};

		// 额外处理数据
		data = fun_save_win_additional(data, 'init');

		// 清空表单
		FormDataFill(data);

		// 移除菜单禁止状态
		$('form select[name="pid"]').removeAttr('disabled');

		// 校验成功状态增加失去焦点
		$('form').find('.am-field-valid').each(function()
		{
			$(this).blur();
		});
	}

	/**
	 * [submit-ajax 公共数据ajax操作]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:22:39+0800
	 * @param    {[int] 	[data-id] 	[数据id]}
	 * @param    {[int] 	[data-view] [完成操作（delete删除数据, reload刷新页面]}
	 * @param    {[string] 	[data-url] 	[请求地址]}
	 */
	$(document).on('click', '.submit-ajax', function()
	{
		var id = $(this).data('id');
		var value = $(this).data('value') || '';
		var url = $(this).data('url');
		var view = $(this).data('view') || 'reload';
		var title = $(this).data('title') || '温馨提示';
		var msg = $(this).data('msg') || '操作后不可恢复、确认操作吗？';

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
							Prompt(result.msg, 'success');

							if(view == 'delete')
							{
								// 成功则删除数据列表
								$('#data-list-'+id).remove();
							} else if(view == 'reload')
							{
								setTimeout(function()
								{
									window.location.reload();
								}, 1500);
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
	});

	// 地区联动
	$('.region-linkage select').on('change', function()
	{
		var temp_name = $(this).attr('name');
		var find_all = (temp_name == 'province') ? ["city", "county"] : ((temp_name == 'city') ? ["county"] : 'no');

		var name = (temp_name == 'province') ? 'city' : ((temp_name == 'city') ? 'county' : 'no');
		var v = $(this).val();
		var $this_next_obj = $('.region-linkage select[name='+name+']');
		if(name !== 'no')
		{
			$.ajax({
				url:$(this).parent().data('url'),
				type:'POST',
				data:{"pid": v},
				dataType:'json',
				success:function(result)
				{
					if(result.code == 0)
					{
						/* html拼接 */
						var html = '';
						for(var i in result.data)
						{
							html += '<option value="'+result.data[i]['id']+'">'+result.data[i]['name']+'</option>';
						}

						/* 下一级数据添加 */
						$this_next_obj.append(html);
					} else {
						Prompt(result.msg);
					}
				}
			});

			/* 子级元素数据清空 */
			if(find_all != 'no')
			{
				for(var i in find_all)
				{
					var $temp_obj = $('.region-linkage select[name='+find_all[i]+']');
					var temp_find = $temp_obj.find('option').first().text();
					var temp_html = '<option value="">'+temp_find+'</option>';
					$temp_obj.html(temp_html);
				}
			}
		}
	});

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
		map.centerAndZoom(point,12);

		// 创建地址解析器实例
		var myGeo = new BMap.Geocoder();
		// 将地址解析结果显示在地图上,并调整地图视野
		myGeo.getPoint(address, function(point) {
			if (point) {
				map.centerAndZoom(point, 16);
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
    	img_file_upload_show('.images-file-event');
    }

    // 图片组合input清除按钮
    $(document).on('click', '.original-images-url-delete', function()
    {
    	var images = $($(this).data('image-tag')).data('del-default') || $($(this).data('image-tag')).attr('data-default');
    	$($(this).data('input-tag')).val('');
    	$($(this).data('image-tag')).attr('src', images);
    	$($(this).data('image-tag')).attr('data-default', images);
    	$($(this).data('tips-tag')).html('');
    	var $file_tag = $($(this).data('file-tag'));
		if($file_tag.val().length > 0)
		{
			$file_tag.after($file_tag.clone().val(''));
			$file_tag.val('');
		}
    });

	// 颜色选择器
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

    // 多图片上传预览
    $plug_images_list = $('.plug-images-list');
    $(document).on('change', '.plug-images-add', function(imgFile)
    {
        var status = false;
        var url = '';
        var filextension = imgFile.target.value.substring(imgFile.target.value.lastIndexOf("."),imgFile.target.value.length);
            filextension = filextension.toLowerCase();
        if((filextension!='.jpg') && (filextension!='.gif') && (filextension!='.jpeg') && (filextension!='.png') && (filextension!='.bmp'))
        {
            Prompt("图片格式错误，请重新上传");
        } else {
            if(document.all)
            {
                Prompt('ie浏览器不可用'); 
            } else {
                url = window.URL.createObjectURL(imgFile.target.files[0]);// FF 7.0以上
                status = true;
            }
        }

        // 数据赋值
        if(status == true)
        {
            var html = '<img src="'+url+'" class="c-m" />';
                html += '<button type="button" class="am-btn am-btn-danger am-btn-xs am-btn-block plug-images-delete-submit">'+($plug_images_list.data('delete-text') || '删除')+'</button>';
            $(this).find('.img-resources').html(html);
            
            // 禁止当前选项点击事件
            $(this).addClass('plug-images-add-prohibit');
            $(this).parent().removeClass('plug-images-add-tag');

            // 继续添加
            plug_images_list_add('add');
        }
    });

    // 多图片上传 - 继续添加
    function plug_images_list_add(event)
    {
    	var max = $plug_images_list.data('max-count') || 0;
        var count = $plug_images_list.find('li').length;
        var add_tag_count = $plug_images_list.find('.plug-images-add-tag').length;
        if(add_tag_count > 0 && count == 1)
        {
        	$plug_images_list.html('');
        	count = 0;
        }
        if(count > 0 && event == 'del')
        {
        	return false;
        }
        if(max == 0 || count < max)
        {
        	var is_required = $plug_images_list.data('required') || 0;
        	var required = (is_required == 1 && count == 0) ? 'required' : '';
        	var html = '<li class="plug-images-add-tag"><label class="plug-images-add">';
        		html += '<input type="file" name="'+$plug_images_list.data('name')+'" value="" accept="image/gif,image/jpeg,image/jpg,image/png" data-validation-message="'+($plug_images_list.data('format') || '请选择图片')+'" '+required+' />';
        		html += '<div class="img-resources">';
        		html += '<i class="add-icon">+</i></div></label></li>';
        	$plug_images_list.append(html);
        }
    }
    // 初始化
    if($plug_images_list.length > 0)
    {
    	plug_images_list_add('init');
    }

    // 多图片上传 - 点击事件禁止
    $(document).on('click', '.plug-images-add-prohibit', function()
    {
        return false;
    });

    // 多图片上传 - 删除
    $(document).on('click', '.plug-images-delete-submit', function()
    {
        $(this).parent().parent().parent().remove();
        plug_images_list_add('del');
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