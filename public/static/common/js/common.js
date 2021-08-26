/**
 * [Prompt 公共提示]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-10T14:32:39+0800
 * @param {[string]}	msg  [提示信息]
 * @param {[string]} 	type [类型（失败:error, 警告:warning, 成功:success）]
 * @param {[int]} 		time [自动关闭时间（秒）, 默认3秒]
 */
function Prompt(msg, type, time)
{
	if(msg != undefined && msg != '')
	{
		// 存在的提示信息则不继续
		var status = true;
		$('.common-prompt').each(function(k, v)
		{
			if(status && $(this).find('.prompt-msg').text() == msg)
			{
				status = false;
			}
		});
		if(status)
		{
			// 是否已存在提示条
			var height = 55;
			var length = $('.common-prompt').length;

			// 提示信息添加
			if((type || null) == null)
			{
				type = 'danger';
			}

			// icon图标, 默认错误
			var icon = 'am-icon-times-circle';
			switch(type)
			{
				// 成功
				case 'success' :
					icon = 'am-icon-check-circle';
					break;

				// 警告
				case 'warning' :
					icon = 'am-icon-exclamation-circle';
					break;
			}
			var index = parseInt(Math.random()*1000001);
			var html = '<div class="common-prompt common-prompt-'+index+' am-alert am-alert-'+type+' am-animation-slide-top" data-index="'+index+'" style="top:'+((height*length)+20)+'px;" data-am-alert><button type="button" class="am-close">&times;</button><div class="prompt-content"><i class="'+icon+' am-icon-sm am-margin-right-sm"></i><p class="prompt-msg">'+msg+'</p></div></div>';
			$('body').append(html);

			// 自动关闭提示
			setTimeout(function()
			{
				$('.common-prompt-'+index).slideToggle(300, function()
				{
					$('.common-prompt-'+index).remove();
					$('.common-prompt').each(function(k, v)
					{
						$(this).animate({'top':(k*height+20)+'px'});
					});
				});
			}, (time || 3)*1000);
			return true;
		}
	}
	return false;
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
 * @param    {[boolean]}    is_json [是否返回json对象（默认否）]
 * @return   {[object]}        		[josn对象]
 */
function GetFormVal(element, is_json)
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
			if($(this).is(':selected'))
			{
				var value = (tmp.value == undefined) ? '' : tmp.value;
				if($(this).parents('select').attr('multiple') != undefined)
				{
					// 多选择
					if(tmp_all[name] == undefined)
					{
						tmp_all[name] = [];
						i = 0;
					}
					tmp_all[name][i] = value;
					i++;
				} else {
					// 单选择
					if(object[name] == undefined)
					{
						object.append(name, value);
					}
				}
			}
		}
	});
	object = ArrayTurnJson(tmp_all, object);

	// input 复选框checkbox
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
			} else {
				// 滑动开关、未选中则0
				if(typeof($(this).attr('data-am-switch')) != 'undefined')
				{
					tmp_all[tmp.name] = 0;
				}
			}
		}
	});
	object = ArrayTurnJson(tmp_all, object);

	// 是否需要返回json对象
	if(is_json === true)
	{
		var json = {};
		object.forEach(function(value, key)
		{
			if((key || null) != null)
			{
				json[key] = value
			}
		});
		return json;
	}
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
	if($form.length <= 0)
	{
		return false;
	}
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
				var tag = $(validity.field).attr('data-choice-one-to');
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
			var $this = this;
			setTimeout(function()
			{
				// 错误信息
				var $field = $(validity.field);
				var msg = $field.data('validationMessage') || $this.getValidationMessage(validity);
				if($field.hasClass('am-field-error'))
				{
					Prompt(msg);
				}
			}, 100);
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

				// 获取表单数据
				var action = $form.attr('action') || null;
				var method = $form.attr('method') || null;
				var request_type = $form.attr('request-type') || null;
				var request_value = $form.attr('request-value') || null;
				// 以 ajax 开头的都会先请求再处理
				// ajax-reload  请求完成后刷新页面
				// ajax-url 	请求完成后调整到指定的请求值
				// ajax-fun 	请求完成后调用指定方法
				// ajax-view 	请求完成后仅提示文本信息
				// sync 		不发起请求、直接同步调用指定的方法
				// jump 		不发起请求、拼接数据参数跳转到指定 url 地址
				var request_handle = ['ajax-reload', 'ajax-url', 'ajax-fun', 'ajax-view', 'sync', 'jump', 'form'];

				// 参数校验
				if(request_handle.indexOf(request_type) == -1)
				{
	            	$button.button('reset');
	            	Prompt('表单[类型]参数配置有误');
	            	return false;
				}

				// 类型值必须配置校验
				var request_type_value = ['ajax-url', 'ajax-fun', 'sync', 'jump']
				if(request_type_value.indexOf(request_type) != -1 && request_value == null)
				{
	        		$button.button('reset');
					Prompt('表单[类型值]参数配置有误');
					return false;
				}

				// 请求类型
				switch(request_type)
				{
					// 是form表单直接通过
					case 'form' :
						return true;
						break;

					// 同步调用方法
					case 'sync' :
						$button.button('reset');
						if(IsExitsFunction(request_value))
	            		{
	            			window[request_value](GetFormVal(form_name, true));
	            		} else {
	            			Prompt('['+request_value+']表单定义的方法未定义');
	            		}
	            		return false;
						break;

					// 拼接参数跳转
					case 'jump' :
						var params = GetFormVal(form_name, true);
						var pv = '';
						for(var i in params)
						{
							if(params[i] != undefined && params[i] != '')
							{
								pv += i+'='+encodeURIComponent(params[i])+'&';
							}
						}
						if(pv != '')
						{
							var join = (request_value.indexOf('?') >= 0) ? '&' : '?';
							request_value += join+pv.substr(0, pv.length-1);
						}

						window.location.href = request_value;
						return false;
						break;

					// 调用自定义回调方法
					case 'ajax-fun' :
						if(!IsExitsFunction(request_value))
	            		{
	            			$button.button('reset');
	            			Prompt('['+request_value+']表单定义的方法未定义');
	            			return false;
	            		}
						break;

				}

				// 请求 url http类型
				if(action == null || method == null)
				{
	            	$button.button('reset');
	            	Prompt('表单[action或method]参数配置有误');
	            	return false;
				}

				// ajax请求
				$.AMUI.progress.start();
				$.ajax({
					url: action,
					type: method,
	                dataType: "json",
	                timeout: $form.attr('timeout') || 60000,
	                data: GetFormVal(form_name),
	                processData: false,
					contentType: false,
	                success: function(result)
	                {
	                	$.AMUI.progress.done();
	                	// 调用自定义回调方法
	                	if(request_type == 'ajax-fun')
	                	{
	                		if(IsExitsFunction(request_value))
	                		{
	                			window[request_value](result);
	                		} else {
		            			$button.button('reset');
	                			Prompt('['+request_value+']表单定义的方法未定义');
	                		}
	                	} else {
	                		// 统一处理
		            		if(result.code == 0)
		            		{
		            			switch(request_type)
		            			{
		            				// url跳转
		            				case 'ajax-url' :
			            				Prompt(result.msg, 'success');
			            				setTimeout(function()
										{
											window.location.href = request_value;
										}, 1500);
		            					break;

		            				// 页面刷新
		            				case 'ajax-reload' :
		            					Prompt(result.msg, 'success');
			            				setTimeout(function()
										{
											// 等于parent则刷新父窗口
											if(request_value == 'parent')
											{
												parent.location.reload();
											} else {
												window.location.reload();
											}
										}, 1500);
		            					break;

		            				// 默认仅提示
		            				default :
		            					$button.button('reset');
		            					Prompt(result.msg, 'success');
		            			}
							} else {
								Prompt(result.msg);
								$button.button('reset');
							}
						}
					},
					error: function(xhr, type)
		            {
		            	$.AMUI.progress.done();
		            	$button.button('reset');
		            	Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
		            }
	            });
			}
			return false;
		}
	});
}
// 默认初始化一次,默认标签[form.form-validation]
FromInit('form.form-validation');
// 公共列表 form 搜索条件
FromInit('form.form-validation-search');

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
			$form.find('input[type="radio"][name="'+i+'"]').each(function(k, v)
			{
				this.checked = (json[i] == $(this).val());
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

		// switch切换插件
		$form.find('.am-switch').each(function(k, v)
		{
			var name = $(this).find('input').attr('name') || null;
			var state = (name == null || (json[name] || 0) == 0) ? false : true;
			$(this).find('input').bootstrapSwitch('state', state);
		});

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
		timeout:60000,
		data:{"id":id},
		success:function(result)
		{
			if(result.code == 0 && result.data.length > 0)
			{
				html = '';
				for(var i in result.data)
				{
					html += TreeItemHtmlHandle(result.data[i], id, level, is_add_node, is_delete_all)
				}

				// 是否首次
				if(id == 0)
				{
					html = '<table class="am-table am-table-striped am-table-hover">'+html+'</table>';
					$('#tree').html(html);
				} else {
					$('.tree-pid-'+id).remove();
					$('#data-list-'+id).after(html);
					$('#data-list-'+id).find('.tree-submit').removeClass('am-icon-plus');
					$('#data-list-'+id).find('.tree-submit').addClass('am-icon-minus-square');
				}
			} else {
				$('#tree').find('p').text(result.msg);
				$('#tree').find('img').remove();
			}
		},
		error:function(xhr, type)
		{
			$('#tree').find('p').text(HtmlToString(xhr.responseText) || '异常错误');
			$('#tree').find('img').remove();
		}
	});
}

/**
 * tree列表数据处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-19
 * @desc    description
 * @param    {[boject]}     item            [数据]
 * @param    {[int]}    	id    			[节点id]
 * @param    {[int]}      	level 			[层级]
 * @param    {[int]}      	is_add_node 	[是否开启新增子级按钮]
 * @param    {[int]}      	is_delete_all	[是否所有开启删除按钮]
 */
function TreeItemHtmlHandle(item, id, level, is_add_node, is_delete_all)
{
	// 基础参数处理
	is_add_node = is_add_node || 0;
	is_delete_all = is_delete_all || 0;
	var is_astrict_rank = parseInt($('#tree').attr('data-rank')) || 0;
				var delete_url = $('#tree').data('del-url');
	var class_name = $('#data-list-'+id).attr('class') || '';
		class_name = class_name.replace('tree-change-item', '').replace('am-active', '');
	var popup_tag = $('#tree').data('popup-tag') || ''+popup_tag+'';

	// 数据 start
	var is_active = (item['is_enable'] == 0) ? 'am-active' : '';
	html = '<tr id="data-list-'+item['id']+'" class="'+class_name+' tree-pid-'+id+' '+is_active+'"><td>';
	tmp_level = (id != 0) ? parseInt(level)+20 : parseInt(level);
	var son_css = '';
	if(item['is_son'] == 'ok')
	{
		html += '<a href="javascript:;" class="am-icon-plus tree-submit" data-id="'+item['id']+'" data-level="'+tmp_level+'" data-is_add_node="'+is_add_node+'" data-is_delete_all="'+is_delete_all+'" style="margin-right:8px;width:12px;';
		if(id != 0)
		{
			html += 'margin-left:'+tmp_level+'px;';
		}
		html += '"></a>';
	} else {
		son_css = 'padding-left:'+tmp_level+'px;';
	}
	html += '<span style="'+son_css+'">';
	if((item['icon'] || null) != null)
	{
		html += '<a href="'+item['icon']+'" target="_blank"><img src="'+item['icon']+'" width="20" height="20" class="am-vertical-align-middle am-margin-right-xs" /></a>';
	}
	html += '<span>'+(item['name_alias'] || item['name'])+'</span>';
	html += '</span>';
	// 数据 end

	// 操作项 start
	html += '<div class="am-fr am-margin-right-lg submit">';

	// 新增
	var rank = tmp_level/20+1;
	if(is_add_node == 1 && (is_astrict_rank == 0 || rank < is_astrict_rank))
	{
		html += '<button class="am-btn am-btn-success am-btn-xs am-radius am-icon-plus am-margin-right-sm tree-submit-add-node" data-am-modal="{target: \''+popup_tag+'\'}" data-id="'+item['id']+'" '+(item['is_enable'] == 0 ? 'style="display:none;"' : '')+'> 新增</button>';
	}

	// 编辑
	html += '<button class="am-btn am-btn-secondary am-btn-xs am-radius am-icon-edit submit-edit" data-am-modal="{target: \''+popup_tag+'\'}" data-json="'+encodeURIComponent(item['json'])+'" data-is-exist-son="'+item['is_son']+'"> 编辑</button>';
	if(item['is_son'] != 'ok' || is_delete_all == 1)
	{
		// 是否需要删除子数据
		var pid_class = is_delete_all == 1 ? '.tree-pid-'+item['id'] : '';

		// 删除
		html += '<button class="am-btn am-btn-danger am-btn-xs am-radius am-icon-trash-o am-margin-left-sm submit-delete" data-id="'+item['id']+'" data-url="'+delete_url+'" data-ext-delete-tag="'+pid_class+'"> 删除</button>';
	}
	html += '</div>';
	// 操作项 end
	
	html += '</td></tr>';
	return html;
}

/**
 * tree数据保存回调处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-19
 * @desc    description
 * @param   {[boject]}        e [当前回调数据]
 */
function TreeFormSaveBackHandle(e)
{
	$.AMUI.progress.done();
	$('form.form-validation').find('button[type="submit"]').button('reset');
    if(e.code == 0)
    {
        Prompt(e.msg, 'success');
        var $popup = $($('#tree').data('popup-tag') || ''+popup_tag+'');
        
        // 数据处理
        if((e.data || null) != null)
        {
        	var json = JSON.parse(decodeURIComponent(e.data));
        	if((json.id || null) != null)
        	{
        		// 是否存在pid节点数据
    			var pid_arr = [];
    			if($popup.find('select[name="pid"]').length > 0)
    			{
	    			$popup.find('select[name="pid"] option').each(function()
	    			{
						var value = $(this).val();
						if(value != '')
						{
							pid_arr.push(value);
						}
					});
				}

    			// 存在数据编辑、则添加
        		var $obj = $('#data-list-'+json.id);
        		if($obj.length > 0)
        		{
        			// 原始json数据
        			var json_old = JSON.parse(decodeURIComponent($obj.find('.submit-edit').attr('data-json')));

        			// 名称更新
	        		$obj.find('td>span>span').text(json.name_alias || json.name);

	        		// 状态处理
	        		if(json.is_enable != json_old.is_enable)
	        		{
	        			if($obj.hasClass('am-active'))
	        			{
	        				$obj.removeClass('am-active');
	        			} else {
	        				$obj.addClass('am-active');
	        			}
	        		}

	        		// 属性json数据更新
	        		$obj.find('.submit-edit').attr('data-json', encodeURIComponent(e.data));

	        		// pid改变后不可再次操作
	        		if(pid_arr.length > 0)
	        		{
		        		if(json_old.pid != json.pid)
		        		{
		        			// 移出操作项（由于修改父级后，数据可能已经不在当前节点下、禁止再次编辑）
		        			$obj.find('.submit').remove();

		        			// 更新记录样式
		        			$obj.addClass('tree-change-item');

		        			// 存在父节点数据则移出当前的元素option
		        			// 防止交叉关联导致造成垃圾数据残留在数据库中
	        				// 移出当前存在父节点中的数据
	        				$popup.find('select[name="pid"] option[value="'+json['id']+'"]').remove();
	        				// 多选插件事件更新
							if($('.chosen-select').length > 0)
							{
								$('.chosen-select').trigger('chosen:updated');
							}
		        		} else {
		        			// 是否未启用
		        			if(json.is_enable != 1)
		        			{
		        				$obj.find('.tree-submit-add-node').hide();
		        			} else {
		        				$obj.find('.tree-submit-add-node').show();
		        			}

		        			// 如果当前节点id不存在pid节点中则隐藏新增操作按钮
		        			if(pid_arr.indexOf(json.id) == -1)
		        			{
		        				$obj.find('.tree-submit-add-node').hide();
		        			}
		        		}
	        		}
        		} else {
        			// 存在pid直接拉取下级数据,则追加新数据
        			if(json.pid > 0)
        			{
        				Tree(json.pid, $('#tree').data('node-url'), 1, 1, 1);
        			} else {
        				json['json'] = e.data;

        				// 拼接html数据
	        			var html = TreeItemHtmlHandle(json, 0, 1, 1);

	        			// 首次则增加table标签容器
	        			if($('#tree table tbody').length > 0)
	        			{
	        				$('#tree table tbody').append(html);
	        				
	        			} else {
	        				$('#tree').html('<table class="am-table am-table-striped am-table-hover"><tbody>'+html+'</tbody></table>');
	        			}
        				// 刚刚创建的数据不支持新增操作
        				// 因为级数据还不在父级选择节点数据中
        				// 需要刷新页面重新加载数据
        				// 如果当前节点id不存在pid节点中则隐藏新增操作按钮
	        			if(pid_arr.indexOf(json.id) == -1)
	        			{
	        				$('#data-list-'+json.id).find('.tree-submit-add-node').hide();
	        			}
	        		}
        		}
        	}
        }
        $popup.modal('close');
    } else {
        Prompt(e.msg);
    }
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
		show_img = $(this).attr('data-image-tag') || null;
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
		var default_img = $(show_img).attr('data-default') || null;
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
		show_video = $(this).attr('data-video-tag') || null;
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
		var default_video = $(show_video).attr('data-default') || null;
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
 * @param   {[string]}        class_tag [指定class]
 * @param   {[int]}        	  full 		[是否满屏（0否, 1是）]
 * @param   {[int]}        	  full_max	[满屏最大限制（max-width:1200px）（0否, 1是）]
 */
function ModalLoad(url, title, class_tag, full, full_max)
{
	// class 定义
	var ent = 'popup-iframe';

	// 自定义 class
	if((class_tag || null) != null)
	{
		ent += ' '+class_tag;
	}

	// 是否满屏
	if((full || 0) == 1)
	{
		ent += ' popup-full';
	}

	// 满屏最大限制
	if((full_max || 0) == 1)
	{
		ent += ' popup-full-max';
	}
	
	// 调用弹窗组件
	AMUI.dialog.popup({
		title: title || '',
		content: '<iframe src="'+url+'" width="100%" height="100%"></iframe>',
		class: ent
	});
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
	// 参数获取
	var id = e.attr('data-id');
	var key = e.attr('data-key') || 'id';
	var url = e.attr('data-url');
	var view = e.attr('data-view') || 'delete';
	var value = e.attr('data-value') || null;
	var ext_delete_tag = e.attr('data-ext-delete-tag') || null;

	// 参数校验
	if((id || null) == null || (url || null) == null)
	{
		Prompt('参数配置有误');
		return false;
	}

	// 请求数据
	var data = {};
		data[key] = id;

	// 请求删除数据
	$.AMUI.progress.start();
	$.ajax({
		url:url,
		type:'POST',
		dataType:"json",
		timeout:e.attr('data-timeout') || 60000,
		data:data,
		success:function(result)
		{
			$.AMUI.progress.done();
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
			$.AMUI.progress.done();
			Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
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
	var title = e.attr('data-title') || '温馨提示';
	var msg = e.attr('data-msg') || '删除后不可恢复、确认操作吗？';
	var is_confirm = (e.attr('data-is-confirm') == undefined || e.attr('data-is-confirm') == 1) ? 1 : 0;

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
 * [AjaxRequest ajax网络请求]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-04-30T00:25:21+0800
 * @param    {[object]}                 e [当前元素对象]
 */
function AjaxRequest(e)
{
	// 参数
	var id = e.attr('data-id');
	var key = e.attr('data-key') || 'id';
	var field = e.attr('data-field') || '';
	var value = e.attr('data-value') || '';
	var url = e.attr('data-url');
	var view = e.attr('data-view') || '';

	// 请求数据
	var data = {"value": value, "field": field};
		data[key] = id;

	// ajax
	$.AMUI.progress.start();
	$.ajax({
		url:url,
		type:'POST',
		dataType:"json",
		timeout:e.attr('data-timeout') || 60000,
		data:data,
		success:function(result)
		{
			$.AMUI.progress.done();
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
			$.AMUI.progress.done();
			Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
		}
	});
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
	var title = e.attr('data-title') || '温馨提示';
	var msg = e.attr('data-msg') || '操作后不可恢复、确认继续吗？';

	AMUI.dialog.confirm({
		title: title,
		content: msg,
		onConfirm: function(result)
		{
			AjaxRequest(e);
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
			$fullscreen.find('.fullscreen-text').text($fullscreen.attr('data-fulltext-open') || '开启全屏');
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
 * @param   {[string]}        value [字段值, null 则去除字段]
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
                if(value === null)
                {
                	if(first.substr(-1) == '/')
                	{
                		first = first.substr(0, first.length-1);
                	}
                	url = first+last+ext;
                } else {
                	url = first+field+'/'+value+last+ext;
                }
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
                            if(temp[0] == field)
                            {
                            	if(value !== null)
                            	{
                            		p += '&'+field+'='+value;
                            	}
                            } else {
                                p += '&'+params_all[i];
                            }
                        }
                    }
                } else {
                	if(value === null)
                	{
                		p = exts;
                	} else {
                		p = exts+'&'+field+'='+value;
                	}
                }
                url = str+(ext.substr(0, ext.indexOf('?')))
                if((p || null) != null)
                {
                	if(p.substr(0, 1) == '&')
                	{
                		p = p.substr(1);
                	}
                	url += '?'+p;
                }
            } else {
            	if(value === null)
                {
                    url = str+ext;
                } else {
                    url = str+'/'+field+'/'+value+ext;
                }
            }
        }
    } else {
    	if(value !== null)
    	{
    		url += '?'+field+'='+value;
    	}
    }

    return url+anchor;
}

/**
 * 当前手机浏览器环境
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-04-20T19:48:59+0800
 * @return   {string} [weixin,weibo,qq]
 */
function MobileBrowserEnvironment()
{
	// 浏览器标识
	var ua = navigator.userAgent.toLowerCase();

	// 微信
	if(ua.match(/MicroMessenger/i) == 'micromessenger')
	{
		return 'weixin';
	}

	// 新浪微博
	if(ua.match(/WeiBo/i) == 'weibo')
	{
		return 'weibo';
	}

	// QQ空间
	if(ua.match(/qzone/i) == 'qzone')
	{
		return 'qzone';
	}

	// QQ
	if(ua.match(/QQ/i) == 'qq')
	{
		return 'qq';
	}
	return null;
}

/**
 * [pagelibrary 分页按钮获取]
 * @param  {[int]} total      [数据总条数]
 * @param  {[int]} number     [页面数据显示条数]
 * @param  {[int]} page       [当前页码数]
 * @param  {[int]} sub_number [按钮生成个数]
 * @return {[string]}         [html代码]
 */
function PageLibrary(total, number, page, sub_number)
{
	if((page || null) == null) page = 1;
	if((number || null) == null) number = 15;
	if((sub_number || null) == null) sub_number = 2;

	var page_total = Math.ceil(total/number);
	if(page > page_total) page = page_total;
	page = (page <= 0) ? 1 : parseInt(page);

	var html = '<ul class="am-pagination am-pagination-centered pagelibrary"><li ';
		html += (page > 1) ? '' : 'class="am-disabled"';
		page_x = page-1;
		html += '><a href="javascript:;" data-page="'+page_x+'" class="am-radius">&laquo;</a></li>';

		var html_before = '';
		var html_after = '';
		var html_page = '<li class="am-active"><a href="javascript:;" data-is-active="1" class="am-radius">'+page+'</a></li>';
		if(sub_number > 0)
		{
			// 前按钮
			if(page > 1)
			{
				total = (page-sub_number < 1) ? 1 : page-sub_number;
				for(var i=page-1; i>=total; i--)
				{
					html_before = '<li><a href="javascript:;" data-page="'+i+'" class="am-radius">'+i+'</a></li>'+html_before;
				}
			}

			// 后按钮
			if(page_total > page)
			{
				total = (page+sub_number > page_total) ? page_total : page+sub_number;
				for(var i=page+1; i<=total; i++)
				{
					html_after += '<li><a href="javascript:;" data-page="'+i+'" class="am-radius">'+i+'</a></li>';
				}
			}
		}

		html += html_before+html_page+html_after;
		html += '<li';
		html += (page > 0 && page < page_total) ? '' : ' class="am-disabled"';
		page_y = page+1;
		html += '><a href="javascript:;" data-page="'+page_y+'" class="am-radius">&raquo;</a></li></ul>';
	return html;
}

/**
 * [RegionNodeData 地区联动]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-09-23T22:00:30+0800
 * @param    {[int]}          pid     	[pid数据值]
 * @param    {[string]}       name      [当前节点name名称]
 * @param    {[string]}       next_name [下一个节点名称（数据渲染节点）]
 * @param    {[int]}          value 	[需要选中的值]
 */
function RegionNodeData(pid, name, next_name, value)
{
	if(pid != null)
	{
		$.ajax({
			url:$('.region-linkage').attr('data-url'),
			type:'POST',
			data:{"pid": pid},
			dataType:'json',
			success:function(result)
			{
				if(result.code == 0)
				{
					/* html拼接 */
					var html = '<option value="">'+$('.region-linkage select[name='+next_name+']').find('option:eq(0)').text()+'</option>';

					/* 没有指定选中值则从元素属性读取 */
					value = value || $('.region-linkage select[name='+next_name+']').attr('data-value') || null;
					for(var i in result.data)
					{
						html += '<option value="'+result.data[i]['id']+'"';
						if(value != null && value == result.data[i]['id'])
						{
							html += ' selected ';
						}
						html += '>'+result.data[i]['name']+'</option>';
					}

					/* 下一级数据添加 */
					$('.region-linkage select[name='+next_name+']').html(html).trigger('chosen:updated');
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
 * 添加窗口初始化
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-08-06
 * @desc    description
 */
function TreeFormInit()
{
	// popup窗口
	var $popup = $($('#tree').data('popup-tag') || ''+popup_tag+'');

	// 更改窗口名称
	var $title = $popup.find('.am-popup-title');
	$title.text($title.attr('data-add-title'));

	// 填充数据
	var data = {"id":"", "pid":0, "name":"", "sort":0, "is_enable":1, "icon":"", "seo_title":"", "seo_keywords":"", "seo_desc":""};

	// 额外处理数据
	data = FunSaveWinAdditional(data, 'init');

	// 清空表单
	FormDataFill(data);

	// 移除菜单禁止状态
	$popup.find('form select[name="pid"]').removeAttr('disabled');

	// 校验成功状态增加失去焦点
	$popup.find('form').find('.am-field-valid').each(function()
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
 * 地图初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 * @param   {[float]}        	lng   		[经度]
 * @param   {[float]}        	lat   		[维度]
 * @param   {[int]}        		level 		[层级]
 * @param   {[object]}        	point 		[中心对象]
 * @param   {[boolean]}        	is_dragend 	[标注是否可拖拽]
 * @param   {[string]}        	mapid 	    [地图id（默认 map）]
 */
function MapInit(lng, lat, level, point, is_dragend, mapid)
{
	// 百度地图API功能
	if((mapid || null) == null)
	{
		mapid = 'map';
	}
    var map = new BMap.Map(mapid, {enableMapClick:false});
    level = level || $('#'+mapid).data('level') || 16;
    point = point || (new BMap.Point(lng || 116.400244, lat || 39.92556));
    map.centerAndZoom(point, level);

    // 添加控件
    var navigationControl = new BMap.NavigationControl({
        // 靠左上角位置
        anchor: BMAP_ANCHOR_TOP_LEFT,
        // LARGE类型
        type: BMAP_NAVIGATION_CONTROL_LARGE,
    });
    map.addControl(navigationControl);

    // 创建标注
    // 将标注添加到地图中
    var marker = new BMap.Marker(point);
    map.addOverlay(marker);

    // 标注是否可拖拽
    if(is_dragend == undefined || is_dragend == true)
    {
    	marker.enableDragging();
	    marker.addEventListener("dragend", function(e) {
	        map.panTo(e.point);
	        if($('#form-lng').length > 0 && $('#form-lat').length > 0)
		    {
		    	$('#form-lng').val(e.point.lng);
				$('#form-lat').val(e.point.lat);
		    }
	    });

	    // 设置标注提示信息
	    var cr = new BMap.CopyrightControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT});
	    map.addControl(cr); //添加版权控件
	    var bs = map.getBounds();   //返回地图可视区域
	    cr.addCopyright({id: 1, content: "<div class='map-copy'><span>拖动红色图标直接定位</span></div>", bounds:bs});
    }

    //获取地址坐标
    var p = marker.getPosition();
    if($('#form-lng').length > 0 && $('#form-lat').length > 0)
    {
    	$('#form-lng').val(p.lng);
		$('#form-lat').val(p.lat);
    }
}

/**
 * 表格容器处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-02-29
 * @desc    description
 */
function FormTableContainerInit()
{
	if($('.am-table-scrollable-horizontal').length > 0)
	{
		// 表格容器距离计算
		var $obj = $('.am-table-scrollable-horizontal');
		var document_width = $(window).width();
		var parent_left = $obj.offset().left;
		var parent_width = $obj[0].scrollWidth;
		var parent_right = document_width-parent_width-parent_left-2;

		// 左固定
		$('.am-table-scrollable-horizontal > table .am-grid-fixed-left').each(function(k, v)
		{
			var width = parseInt($(this).attr('data-width') || $(this).innerWidth());
			var left = parseInt($(this).attr('data-left') || $(this).position().left);
			$(this).attr('data-width', width);
			$(this).attr('data-left', left);
			$(this).css({"width":width+"px", "left":left+"px"});
		});

		// 右固定
		$('.am-table-scrollable-horizontal > table .am-grid-fixed-right').each(function(k, v)
		{
			var width = parseInt($(this).attr('data-width') || $(this).innerWidth());
			var right = $(this).attr('data-right') || undefined;
			if(right == undefined)
			{
				var left = $(this).offset().left;
					right = parent_width-left-width+parent_left;
				if(right < 0)
				{
					right = 0;
				}
			} else {
				right = parseInt(right);
			}
			$(this).attr('data-width', width);
			$(this).attr('data-right', right);
			$(this).css({"width":width+"px", "right":right+"px"});
		});

		// 左边最后一列、右边第一列设置阴影样式
		$('.am-table-scrollable-horizontal > table tr').each(function(k, v)
		{
			$(this).find('.am-grid-fixed-left').last().addClass('am-grid-fixed-left-shadow');
			$(this).find('.am-grid-fixed-right').first().addClass('am-grid-fixed-right-shadow');
		});
	}
}

/**
 * 动态表格选中的值
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-26
 * @desc    description
 * @param   {[string]}        form 	[表单名称]
 * @param   {[string]}        tag 	[表单父级标签class或id]
 */
function FromTableCheckedValues(form, tag)
{
	// 获取复选框选中的值
	var values = [];
	$(tag).find('input[name="'+form+'"]').each(function(key, tmp)
	{
		if($(this).is(':checked'))
		{
			values.push(tmp.value);
		}
	});

	// 获取单选选中的值
	if(values.length <= 0)
	{
		var val = $(tag).find('input[name="'+form+'"]:checked').val();
		if(val != undefined)
		{
			values.push(val);
		}
	}
	return values;
}

/**
 * 判断变量是否为数组
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-28
 * @desc    description
 * @param   {[mixed]}        value [变量值]
 */
function IsArray(value)
{
	return Object.prototype.toString.call(value) == '[object Array]';
}

/**
 * html转字符串
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-12-30
 * @desc    description
 * @param   {[string]}        html_str [html代码]
 */
function HtmlToString(html_str)
{
	function ToTxt(str)
	{
		var rex = /\<|\>|\"|\'|\&|　| /g;
		str = str.replace(rex, function(match_str)
		{
			switch(match_str)
			{
				case '<' :
					return '&lt;';
					break;
				case '>' :
					return '&gt;';
					break;
				case '"' :
					return '&quot;';
					break;
				case '\'' :
					return '&#39;';
					break;
				case '&' :
					return '&amp;';
					break;
				case ' ' :
					return '&ensp;';
					break;
				case '　' :
					return '&emsp;';
					break;
			}
		});
		return str;
	}
	return ToTxt(html_str).replace(/\&lt\;br[\&ensp\;|\&emsp\;]*[\/]?\&gt\;|\r\n|\n/g, '<br/>');
}

/**
 * 获取浏览器参数
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-22
 * @desc    description
 * @param   {[string]}        field [参数字段名称]
 */
function GetQueryValue(field)
{
	var query = window.location.search.substring(1);
	var vars = query.split('&');
	for(var i=0;i<vars.length;i++)
	{
		var pair = vars[i].split('=');
		if(pair[0] == field)
		{
			return pair[1];
		}
	}
	return false;
}



// 公共数据操作
$(function()
{
    // 表格初始化
    FormTableContainerInit();

    // 表格字段数据排序
    $('.form-sort-container .sort-icon').on('click', function()
    {
    	var key = $(this).data('key') || null;
    	var val = $(this).data('val') || null;
    	if(key == null || val == null)
    	{
    		Prompt('排序数据值有误');
    		return false;
    	}

    	// 赋值并搜索
    	var $parent = $(this).parents('form.form-validation-search');
    	$parent.find('input[name="fp_order_by_key"]').val(key);
    	$parent.find('input[name="fp_order_by_val"]').val(val);
    	$parent.find('button[type="submit"]').trigger('click');
    });

    // 表格字显示段拖拽排序
    if($('ul.form-table-fields-content-container').length > 0)
    {
    	$('ul.form-table-fields-content-container').dragsort({ dragSelector: 'li', placeHolderTemplate: '<li class="drag-sort-dotted am-margin-left-sm"></li>'});
    }

    // 表格字段选择确认
    $('.form-table-field-confirm-submit').on('click', function()
    {
    	// 获取复选框选中的值
		var fields = [];
		$('.form-table-fields-list-container').find('input[name="form_field_checkbox_value"]').each(function(key, tmp)
		{
			fields.push({
				"label": tmp.value,
				"checked": $(this).is(':checked') ? 1 : 0
			});
		});

    	// 是否有选择的数据
		if(fields.length <= 0)
		{
			Prompt('请先选择数据');
			return false;
		}

		// 表单唯一md5key
		var md5_key = $('.am-table-scrollable-horizontal').data('md5-key') || '';

		// ajax请求操作
		var $button = $(this);
		$button.button('loading');
		$.AMUI.progress.start();
		$.ajax({
			url: $button.data('url'),
			type: 'POST',
			dataType: "json",
			data: {"fields": fields, "md5_key": md5_key},
			success: function(result)
			{
				$.AMUI.progress.done();
				if(result.code == 0)
				{
					Prompt(result.msg, 'success');
					setTimeout(function()
					{
						window.location.reload();
					}, 1500);
					
				} else {
					$button.button('reset');
					Prompt(result.msg);
				}
			},
			error: function(xhr, type)
			{
				$.AMUI.progress.done();
				$button.button('reset');
				Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
			}
		});
    });

    // 表格字段复选框操作 全选/反选
    $('.form-table-field-checkbox-submit').on('click', function()
    {
    	var value = parseInt($(this).attr('data-value')) || 0;
        if(value == 1)
        {
        	var not_checked_text = $(this).data('not-checked-text') || '全选';
            $(this).text(not_checked_text);
            $('.form-table-fields-list-container ul li').find('input[type="checkbox"]').uCheck('uncheck');
        } else {
            var checked_text = $(this).data('checked-text') || '反选';
            $(this).text(checked_text);
            $('.form-table-fields-list-container ul li').find('input[type="checkbox"]').uCheck('check');
        }
        $(this).attr('data-value', value == 1 ? 0 : 1);
    });

    // 表格复选框操作 全选/反选
    $('.form-table-operate-checkbox-submit').on('click', function()
    {
    	var value = parseInt($(this).attr('data-value')) || 0;
        if(value == 1)
        {
        	var not_checked_text = $(this).data('not-checked-text') || '全选';
            $(this).text(not_checked_text);
            $('.form-table-operate-checkbox').find('input[type="checkbox"]').uCheck('uncheck');
        } else {
            var checked_text = $(this).data('checked-text') || '反选';
            $(this).text(checked_text);
            $('.form-table-operate-checkbox').find('input[type="checkbox"]').uCheck('check');
        }
        $(this).attr('data-value', value == 1 ? 0 : 1);
    });

    // 表格公共删除操作
    $('.form-table-operate-top-delete-submit').on('click', function()
    {
    	// 请求 url
    	var url = $(this).data('url') || null;
    	if(url == null)
    	{
    		Prompt('url参数有误');
    		return false;
    	}

    	// form name 名称
    	var form = $(this).data('form') || null;
    	if(form == null)
    	{
    		Prompt('form参数有误');
    		return false;
    	}

		// 是否有选择的数据
		var values = FromTableCheckedValues(form, '.am-table-scrollable-horizontal');
		if(values.length <= 0)
		{
			Prompt('请先选中数据');
			return false;
		}

		// 提交字段名称|超时时间|标题|描述
		var key = $(this).data('key') || form;
		var timeout = $(this).data('timeout') || 60000;
		var title = $(this).data('confirm-title') || '温馨提示';
		var msg = $(this).data('confirm-msg') || '删除后不可恢复、确认操作吗？';

		// 再次确认
		AMUI.dialog.confirm({
			title: title,
			content: msg,
			onConfirm: function(result)
			{
				// 数组转对象
				var data = {};
					data[key] = {};
				for(var i in values)
				{
					data[key][i] = values[i];
				}

				// ajax请求操作
				$.AMUI.progress.start();
				$.ajax({
					url: url,
					type: 'POST',
					dataType: "json",
					timeout: timeout,
					data: data,
					success: function(result)
					{
						$.AMUI.progress.done();
						if(result.code == 0)
						{
							// 成功则删除数据列表
							Prompt(result.msg, 'success');
							for(var i in values)
							{
								$('#data-list-'+values[i]).remove();
							}
						} else {
							Prompt(result.msg);
						}
					},
					error: function(xhr, type)
					{
						$.AMUI.progress.done();
						Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
					}
				});
			},
			onCancel: function(){}
		});
    });

    /**
	 * 页面加载 loading
	 */
	if($('.am-page-loading').length > 0)
	{
		$('.am-page-loading').fadeOut(500);
	}

	// 全屏操作
	$('.fullscreen-event').on('click', function()
	{
		var status = $(this).attr('data-status') || 0;
		if(status == 0)
		{
			if(FullscreenOpen())
			{
				$(this).find('.fullscreen-text').text($(this).attr('data-fulltext-exit') || '退出全屏');
			}
		} else {
			if(FullscreenExit())
			{
				$(this).find('.fullscreen-text').text($(this).attr('data-fulltext-open') || '开启全屏');
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
			search_contains: true,
			no_results_text: '没有匹配到结果'
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
		var id = $tag.attr('data-id');
		var state = ($tag.attr('data-state') == 1) ? 0 : 1;
		var url = $tag.attr('data-url');
		var field = $tag.attr('data-field') || '';
		var is_update_status = $tag.attr('data-is-update-status') || 0;
		if(id == undefined || url == undefined)
		{
			Prompt('参数配置有误');
			return false;
		}

		// 请求更新数据
		$.AMUI.progress.start();
		$.ajax({
			url:url,
			type:'POST',
			dataType:"json",
			timeout:$tag.attr('data-timeout') || 60000,
			data:{"id":id, "state":state, "field":field},
			success:function(result)
			{
				$.AMUI.progress.done();
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
					$tag.attr('data-state', state);
				} else {
					Prompt(result.msg);
				}
			},
			error:function(xhr, type)
			{
				$.AMUI.progress.done();
				Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
			}
		});
	});

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
		var tag = $(this).attr('data-tag') || 'data-save-win';

		// 更改窗口名称
		if($('#'+tag).length > 0)
		{
			$title = $('#'+tag).find('.am-popup-title');
			$title.text($title.attr('data-edit-title'));
		}

		// 填充数据
		var json = JSON.parse(decodeURIComponent($(this).attr('data-json')));
		var data = FunSaveWinAdditional(json, 'edit');

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
	$(document).on('click', '#tree .tree-submit-add-node', function()
	{
		// 清空表单数据
		TreeFormInit();

		// 父节点赋值
		var id = parseInt($(this).attr('data-id')) || 0;
		$($(this).parents('#tree').data('popup-tag') || ''+popup_tag+'').find('input[name="pid"], select[name="pid"]').val(id);

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
	$(document).on('click', '#tree .tree-submit', function()
	{
		var id = parseInt($(this).attr('data-id')) || 0;
		// 状态
		if($('.tree-pid-'+id).length > 0)
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
			var url = $(this).parents('#tree').data('node-url') || '';
			var level = parseInt($(this).attr('data-level')) || 0;
			var is_add_node = parseInt($(this).attr('data-is_add_node')) || 0;
			var is_delete_all = parseInt($(this).attr('data-is_delete_all')) || 0;
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
		var is_confirm = $(this).attr('data-is-confirm');
		if(is_confirm == undefined || is_confirm == 1)
		{
			ConfirmNetworkAjax($(this));
		} else {
			AjaxRequest($(this));
		}
	});

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
		var value = $('.region-linkage select[name=province]').attr('data-value') || 0;
		if(value != 0)
		{
			RegionNodeData(value, 'city', 'city');
		}

		// 区/县初始化
		var value = $('.region-linkage select[name=city]').attr('data-value') || 0;
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
			var v = $temp_obj.find('option:selected').val() || null;
			if(v != null)
			{
				if(i == 0)
				{
					province = $temp_obj.find('option:selected').text() || '';
				}
				address += $temp_obj.find('option:selected').text() || '';
			}
		}
		address += $('#form-address').val();
		if(province.length <= 0 || address.length <= 0)
		{
			Prompt('地址为空');
			return false;
		}

		// 创建地址解析器实例
		var myGeo = new BMap.Geocoder();
		// 将地址解析结果显示在地图上,并调整地图视野
		myGeo.getPoint(address, function(point) {
			if (point) {
				MapInit(null, null, null, point);
			} else {
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
        $($(this).attr('data-tips-tag')).html(fileNames);

        // 触发配合显示input地址事件
        var input_tag = $(this).attr('data-choice-one-to') || null;
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
        $($(this).attr('data-tips-tag')).html(fileNames);

        // 触发配合显示input地址事件
        var input_tag = $(this).attr('data-choice-one-to') || null;
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
		$('.colorpicker-submit').each(function(k, v)
		{
			$(this).colorpicker(
		    {
		    	target: $(this),
		        fillcolor: true,
		        success: function(o, color)
		        {
		        	var style_arr = (o.context.dataset.colorStyle || 'color').split('|');
		        	var style_value = {};
		        	for(var i in style_arr)
		        	{
		        		style_value[style_arr[i]] = color;
		        	}
		            $(o.context.dataset.inputTag).css(style_value);
		            $(o.context.dataset.colorTag).val(color);
		            $(o.context.dataset.colorTag).trigger('change');
		        },
		        reset: function(o)
		        {
		        	var color = '';
		        	var style_arr = (o.context.dataset.colorStyle || 'color').split('|');
		        	var style_value = {};
		        	for(var i in style_arr)
		        	{
		        		style_value[style_arr[i]] = color;
		        	}
		            $(o.context.dataset.inputTag).css(style_value);
		            $(o.context.dataset.colorTag).val(color);
		            $(o.context.dataset.colorTag).trigger('change');
		        }
		    });
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
	                var max_number = $tag.attr('data-max-number') || 0;
	                var is_delete = ($tag.attr('data-delete') == undefined) ? 1 : $tag.attr('data-delete');
	                var form_name = $tag.attr('data-form-name') || '';
	                var is_attr = $tag.attr('data-is-attr') || null;

	                // 只限制一条
	                if(max_number <= 1)
	                {
	                    $tag.find('li').remove();
	                }

	                // 循环处理
	                for(var i in result)
	                {
	                	// 是否直接赋值属性
	                	if(i == 0 && is_attr != null)
	                	{
	                		$('form [name="'+form_name+'"]').val(result[i].src);
	                		$tag.attr(is_attr, result[i].src);
	                		break;
	                	}

	                	// 是否限制数量
	                    if(max_number > 0 && $tag.find('li').length >= max_number)
	                    {
	                        Prompt('最多上传'+max_number+'张图片');
	                        break;
	                    }

	                    var html = '<li>';
	                        html += '<input type="text" name="'+form_name+'" value="'+result[i].src+'" />';
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
	                var max_number = $tag.attr('data-max-number') || 0;
	                var is_delete = ($tag.attr('data-delete') == undefined) ? 1 : $tag.attr('data-delete');
	                var form_name = $tag.attr('data-form-name') || '';
	                var is_attr = $tag.attr('data-is-attr') || null;

	                // 只限制一条
	                if(max_number <= 1)
	                {
	                    $tag.find('li').remove();
	                }

	                // 循环处理
	                for(var i in result)
	                {
	                	// 是否直接赋值属性
	                	if(i == 0 && is_attr != null)
	                	{
	                		$('form [name="'+form_name+'"]').val(result[i].src);
	                		$tag.attr(is_attr, result[i].src);
	                		break;
	                	}

	                	// 是否限制数量
	                    if(max_number > 0 && $tag.find('li').length >= max_number)
	                    {
	                        Prompt('最多上传'+max_number+'个视频');
	                        break;
	                    }

	                    var html = '<li>';
	                        html += '<input type="text" name="'+form_name+'" value="'+result[i].src+'" />';
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
	            if(result.length > 0)
	            {
	                var $tag = $($('body').attr('view-tag'));
	                var max_number = $tag.attr('data-max-number') || 0;
	                var is_delete = ($tag.attr('data-delete') == undefined) ? 1 : $tag.attr('data-delete');
	                var form_name = $tag.attr('data-form-name') || '';
	                var is_attr = $tag.attr('data-is-attr') || null;

	                // 只限制一条
	                if(max_number <= 1)
	                {
	                    $tag.find('li').remove();
	                }

	                // 循环处理
	                for(var i in result)
	                {
	                	// 是否直接赋值属性
	                	if(i == 0 && is_attr != null)
	                	{
	                		$('form [name="'+form_name+'"]').val(result[i].url);
	                		$tag.attr(is_attr, result[i].url);
	                		break;
	                	}

	                	// 是否限制数量
	                    if(max_number > 0 && $tag.find('li').length >= max_number)
	                    {
	                        Prompt('最多上传'+max_number+'个附件');
	                        break;
	                    }

	                    var index = parseInt($tag.find('li').length) || 0;
	                    var html = '<li>';
	                        html += '<input type="text" name="'+form_name+'['+index+'][title]" value="'+result[i].title+'" />';
	                        html += '<input type="text" name="'+form_name+'['+index+'][url]" value="'+result[i].url+'" />';
	                        html += '<a href="'+result[i].url+'" title="'+result[i].title+'" target="_blank">'+result[i].title+'</a>';
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
        if(($(this).attr('data-view-tag') || null) == null)
        {
            Prompt('未指定容器');
            return false;
        }

        // 容器
        var $view_tag = $($(this).attr('data-view-tag'));

        // 加载组建类型
        var dialog_type = null;
        switch($view_tag.attr('data-dialog-type'))
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
        if(($view_tag.attr('data-form-name') || null) == null)
        {
            Prompt('未指定表单name名称');
            return false;
        }

        // 打开组建
        var dialog = upload_editor.getDialog(dialog_type);
        dialog.render();
        dialog.open();

        // 赋值参数
        $('body').attr('view-tag',$(this).attr('data-view-tag'));
    });

    // 删除容器中的内容
    $(document).on('click', '.plug-file-upload-view li i', function()
    {
        // 容器
        var $tag = $(this).parents('ul.plug-file-upload-view');

        // 删除数据
        $(this).parent().remove();

        // 数据处理
        var max_number = $tag.attr('data-max-number') || 0;
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

	// 加载 loading popup 弹层
    $(document).on('click', '.submit-popup', function()
    {
    	var url = $(this).data('url') || null;
    	if(url == null)
    	{
    		Prompt('url未配置');
    		return false;
    	}

    	// 基础参数
    	var title = $(this).data('title') || '';
    	var class_tag = $(this).data('class') || '';
    	var full = parseInt($(this).data('full')) || 0;
    	var full_max = parseInt($(this).data('full-max')) || 0;

    	// 调用弹窗方法
        ModalLoad(url, title, class_tag, full, full_max);
    });

    // 地图弹窗
    $(document).on('click', '.submit-map-popup', function()
    {
    	// 参数
    	var lng = $(this).data('lng') || null;
    	var lat = $(this).data('lat') || null;
    	if(lng == null || lat == null)
    	{
    		Prompt('坐标有误');
    		return false;
    	}

    	// 基础参数
    	var title = $(this).data('title') || '';
    	var class_tag = $(this).data('class') || '';

    	// 弹窗
    	AMUI.dialog.popup({
    		title: title,
			content: '<div id="map" data-level="17"></div>',
			class: 'map-popup '+class_tag
		});
		MapInit(lng, lat, null, null, false);
    });

    // 弹窗全屏
    $(document).on('click', '.am-popup-hd .am-full', function()
    {
    	var $parent = $(this).parents('.am-popup');
    	if($parent.hasClass('popup-full'))
    	{
    		$parent.removeClass('popup-full');
    	} else {
    		$parent.addClass('popup-full');
    	}
    });

    // 关闭窗口
    $(document).on('click', '.window-close-event', function()
    {
    	if(confirm($(this).data('msg') || '您确定要关闭本页吗？'))
    	{
			var user_agent = navigator.userAgent;
		    if(user_agent.indexOf('Firefox') != -1 || user_agent.indexOf('Chrome') != -1)
		    {
		        location.href = 'about:blank';
		    } else {
		        window.opener = null;
		        window.open('', '_self');
		    }
		    window.close();
		}
    });

});