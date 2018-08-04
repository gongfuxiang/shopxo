<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo C('DEFAULT_CHARSET');?>" />
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1, maximum-scale=1">
	<title><?php echo L('common_site_title');?></title>
	<link rel="stylesheet" type="text/css" href="/project/shop/service/Public/Common/Lib/assets/css/amazeui.css" />
	<link rel="stylesheet" type="text/css" href="/project/shop/service/Public/Common/Lib/amazeui-switch/amazeui.switch.css" />
	<link rel="stylesheet" type="text/css" href="/project/shop/service/Public/Common/Lib/amazeui-chosen/amazeui.chosen.css" />
	<link rel="stylesheet" type="text/css" href="/project/shop/service/Public/Common/Css/Common.css" />
	<link rel="stylesheet" type="text/css" href="/project/shop/service/Public/Admin/<?php echo ($default_theme); ?>/Css/Common.css" />
	<?php if(!empty($module_css)): ?><link rel="stylesheet" type="text/css" href="/project/shop/service/Public/<?php echo ($module_css); ?>" /><?php endif; ?>
</head>
<body>

<!-- right content start  -->
<div class="content-right">
	<div class="content">
		<!-- form start -->
		<form class="am-form view-list" action="<?php echo U('Admin/User/Index');?>" method="POST">
			<div class="am-g">
				<input type="hidden" name="organization_id" <?php if(isset($param['organization_id'])): ?>value="<?php echo ($param["organization_id"]); ?>"<?php endif; ?> />
				<input type="text" class="am-radius form-keyword" placeholder="<?php echo L('user_so_keyword_tips');?>" name="keyword" <?php if(isset($param['keyword'])): ?>value="<?php echo ($param["keyword"]); ?>"<?php endif; ?> />
				<button type="submit" class="am-btn am-btn-secondary am-btn-sm am-radius form-submit"><?php echo L('common_operation_query');?></button>
				<label class="fs-12 m-l-5 c-p fw-100 more-submit">
					<?php echo L('common_more_screening');?>
					<input type="checkbox" name="is_more" value="1" id="is_more" <?php if(isset($param['is_more']) and $param['is_more'] == 1): ?>checked<?php endif; ?> />
					<i class="am-icon-angle-down"></i>
				</label>

				<div class="more-where <?php if(!isset($param['is_more']) or $param['is_more'] != 1): ?>none<?php endif; ?>">
					<select name="gender" class="am-radius c-p m-t-10 m-l-5 param-where">
						<option value="-1"><?php echo L('common_view_gender_name');?></option>
						<?php if(is_array($common_gender_list)): foreach($common_gender_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($param['gender']) and $param['gender'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
					</select>
					<div class="param-date param-where m-l-5">
						<input type="text" name="time_start" class="Wdate am-radius m-t-10" placeholder="<?php echo L('common_time_start_name');?>" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'yyyy-MM-dd HH:mm:ss'})" <?php if(isset($param['time_start'])): ?>value="<?php echo ($param["time_start"]); ?>"<?php endif; ?>/>
						<span>~</span>
						<input type="text" class="Wdate am-radius m-t-10" placeholder="<?php echo L('common_time_end_name');?>" name="time_end" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'yyyy-MM-dd HH:mm:ss'})" <?php if(isset($param['time_end'])): ?>value="<?php echo ($param["time_end"]); ?>"<?php endif; ?>/>
					</div>
				</div>
			</div>
        </form>
        <!-- form end -->

        <!-- operation start -->
        <div class="am-g m-t-15">
            <a href="<?php echo U('Admin/User/SaveInfo');?>" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-plus"> <?php echo L('common_operation_add');?></a>
            <?php if(!IsMobile()): ?><a href="<?php echo ($excel_url); ?>" class="am-btn am-btn-success am-btn-xs m-l-10 am-icon-file-excel-o am-radius"> <?php echo L('common_operation_excel_export_name');?></a><?php endif; ?>
        </div>
        <!-- operation end -->

		<!-- list start -->
		<table class="am-table am-table-striped am-table-hover am-text-middle m-t-10 m-l-5">
			<thead>
				<tr>
					<th><?php echo L('user_avatar_name');?></th>
					<th><?php echo L('user_username_name');?></th>
					<th class="am-hide-sm-only"><?php echo L('common_mobile_name');?></th>
					<th class="am-hide-sm-only"><?php echo L('user_integral_name');?></th>
					<th class="am-hide-sm-only"><?php echo L('common_view_gender_name');?></th>
					<th class="am-hide-sm-only"><?php echo L('user_birthday_name');?></th>
					<th><?php echo L('common_more_name');?></th>
					<th><?php echo L('common_operation_name');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$v): ?><tr id="data-list-<?php echo ($v["id"]); ?>">
							<td>
								<?php if(!empty($v['avatar'])): ?><img src="<?php echo ($v['avatar']); ?>" class="am-img-thumbnail am-radius" width="60" height="60" />
								<?php else: ?>
									<span class="cr-ddd"><?php echo L('common_on_fill_in_images');?></span><?php endif; ?>
							</td>
							<td>
								<?php if(empty($v['username'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["username"]); endif; ?>
							</td>
							<td class="am-hide-sm-only">
								<?php if(empty($v['mobile'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["mobile"]); endif; ?>
							</td>
							<td class="am-hide-sm-only">
								<?php if(empty($v['integral'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["integral"]); endif; ?>
							</td>
							<td class="am-hide-sm-only">
								<?php if(empty($v['gender_text'])): ?><span class="cr-ddd"><?php echo L('common_not_set_text');?></span><?php else: echo ($v["gender_text"]); endif; ?>
							</td>
							<td class="am-hide-sm-only">
								<?php if(empty($v['birthday_text'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["birthday_text"]); endif; ?>
							</td>
							<td>
								<span class="am-icon-caret-down c-p" data-am-modal="{target: '#my-popup<?php echo ($v["id"]); ?>'}"> <?php echo L('common_see_more_name');?></span>
								<div class="am-popup am-radius" id="my-popup<?php echo ($v["id"]); ?>">
									<div class="am-popup-inner">
										<div class="am-popup-hd">
											<h4 class="am-popup-title"><?php echo L('common_detail_content');?></h4>
											<span data-am-modal-close class="am-close">&times;</span>
										</div>
										<div class="am-popup-bd">
											<dl class="dl-content">
												<dt><?php echo L('user_username_name');?></dt>
												<dd><?php if(empty($v['username'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["username"]); endif; ?></dd>

												<dt><?php echo L('user_nickname_name');?></dt>
												<dd><?php if(empty($v['nickname'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["nickname"]); endif; ?></dd>

												<dt><?php echo L('common_mobile_name');?></dt>
												<dd><?php if(empty($v['mobile'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["mobile"]); endif; ?></dd>

												<dt><?php echo L('common_email_name');?></dt>
												<dd><?php if(empty($v['email'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["email"]); endif; ?></dd>

												<dt><?php echo L('common_view_gender_name');?></dt>
												<dd><?php if(empty($v['gender_text'])): ?><span class="cr-ddd"><?php echo L('common_not_set_text');?></span><?php else: echo ($v["gender_text"]); endif; ?></dd>

												<dt><?php echo L('user_birthday_name');?></dt>
												<dd><?php if(empty($v['birthday_text'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["birthday_text"]); endif; ?></dd>

												<dt><?php echo L('user_province_name');?></dt>
												<dd><?php if(empty($v['province'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["province"]); endif; ?></dd>

												<dt><?php echo L('user_city_name');?></dt>
												<dd><?php if(empty($v['city'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["city"]); endif; ?></dd>

												<dt><?php echo L('common_address_text');?></dt>
												<dd><?php if(empty($v['address'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["address"]); endif; ?></dd>

												<dt><?php echo L('user_integral_name');?></dt>
												<dd><?php if(empty($v['integral'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["integral"]); endif; ?></dd>

												<dt><?php echo L('user_service_expire_time_name');?></dt>
												<dd><?php if(empty($v['service_expire_time_text'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["service_expire_time_text"]); endif; ?></dd>

												<dt><?php echo L('user_avatar_name');?></dt>
												<dd>
													<?php if(!empty($v['avatar'])): ?><img src="<?php echo ($v['avatar']); ?>" class="am-img-thumbnail am-radius" width="100" height="100" />
													<?php else: ?>
														<span class="cr-ddd"><?php echo L('common_on_fill_in_images');?></span><?php endif; ?>
												</dd>

												<dt><?php echo L('common_reg_time_name');?></dt>
												<dd><?php echo ($v["add_time"]); ?></dd>

												<dt><?php echo L('common_upd_time_name');?></dt>
												<dd><?php echo ($v["upd_time"]); ?></dd>
											</dl>
										</div>
									</div>
								</div>
							</td>
							<td class="view-operation">
								<a href="<?php echo U('Admin/User/SaveInfo', array_merge($param,array('id'=>$v['id'])));?>">
									<button class="am-btn am-btn-default am-btn-xs am-radius am-icon-edit"> <?php echo L('common_operation_edit');?></button>
								</a>
								<button class="am-btn am-btn-default am-btn-xs am-radius am-icon-trash-o submit-delete" data-url="<?php echo U('Admin/User/Delete');?>" data-id="<?php echo ($v["id"]); ?>"> <?php echo L('common_operation_delete');?></button>
							</td>
						</tr><?php endforeach; endif; ?>
				<?php else: ?>
					<tr><td colspan="20" class="table-no"><?php echo L('common_not_data_tips');?></td></tr><?php endif; ?>
			</tbody>
		</table>
		<div class="am-popup am-radius" id="my-popup-audit">
			<div class="am-popup-inner">
				<div class="am-popup-hd">
					<h4 class="am-popup-title"><?php echo L('common_operation_audit');?></h4>
					<span data-am-modal-close class="am-close">&times;</span>
				</div>
				<div class="am-popup-bd">
					<dl class="dl-content">
						<dt><?php echo L('user_username_name');?></dt>
						<dd class="audit-username"></dd>

						<dt><?php echo L('common_mobile_name');?></dt>
						<dd class="audit-mobile"></dd>

						<dt><?php echo L('common_view_gender_name');?></dt>
						<dd class="audit-gender"></dd>

						<dt><?php echo L('user_organization_name');?></dt>
						<dd class="audit-organization_name"></dd>

						<dt><?php echo L('user_birthday_name');?></dt>
						<dd class="audit-birthday"></dd>

						<dt><?php echo L('user_user_type_name');?></dt>
						<dd class="audit-user_type"></dd>

						<dt><?php echo L('user_salary_type_name');?></dt>
						<dd class="audit-salary_type"></dd>
					</dl>

					<form class="am-form form-validation" action="<?php echo U('Admin/User/Audit');?>" method="POST" request-type="ajax-reload" enctype="multipart/form-data">
						<div class="am-form-group">
							<label><?php echo L('user_refused_why_name');?></label>
							<textarea name="refused_why" rows="5" class="am-radius" placeholder="<?php echo L('user_refused_why_format');?>" data-validation-message="<?php echo L('user_refused_why_format');?>" maxlength="80"></textarea>
						</div>

						<div class="am-form-group audit-submit t-c">
							<input type="hidden" name="status" value="" />
							<input type="hidden" name="id" value="" />
							<button type="submit" class="am-btn am-btn-danger am-radius am-btn-sm" data-am-loading="{loadingText:'<?php echo L('common_form_loading_tips');?>'}" data-status="3">拒绝</button>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" data-am-loading="{loadingText:'<?php echo L('common_form_loading_tips');?>'}" data-status="2">同意</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- list end -->

		<!-- page start -->
		<?php if(!empty($list)): echo ($page_html); endif; ?>
		<!-- page end -->
	</div>
</div>
<!-- right content end  -->
		
<!-- footer start -->
<!-- commom html start -->
<!-- delete html start -->
<div class="am-modal am-modal-confirm" tabindex="-1" id="common-confirm-delete">
	<div class="am-modal-dialog am-radius">
		<div class="am-modal-bd"><?php echo L('common_delete_tips');?></div>
		<div class="am-modal-footer">
			<span class="am-modal-btn" data-am-modal-cancel><?php echo L('common_operation_cancel');?></span>
			<span class="am-modal-btn" data-am-modal-confirm><?php echo L('common_operation_confirm');?></span>
		</div>
	</div>
</div>
<!-- delete html end -->

<!-- delete html start -->
<div class="am-modal am-modal-confirm" tabindex="-1" id="common-confirm-cancel">
    <div class="am-modal-dialog am-radius">
        <div class="am-modal-bd"><?php echo L('common_cancel_tips');?></div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel><?php echo L('common_operation_cancel');?></span>
            <span class="am-modal-btn" data-am-modal-confirm><?php echo L('common_operation_confirm');?></span>
        </div>
    </div>
</div>
<!-- delete html end -->
<!-- commom html end -->
</body>
</html>

<!-- 类库 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/jquery/jquery-2.1.0.js"></script>
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/assets/js/amazeui.min.js"></script>
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/echarts/echarts.min.js"></script>

<!-- ueditor 编辑器 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/ueditor/lang/zh-cn/zh-cn.js"></script>

<!-- 颜色选择器 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/colorpicker/jquery.colorpicker.js"></script>

<!-- 元素拖拽排序插件 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/dragsort/jquery.dragsort-0.5.2.min.js"></script>

<!-- amazeui插件 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/amazeui-switch/amazeui.switch.min.js"></script>
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/amazeui-chosen/amazeui.chosen.min.js"></script>
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/amazeui-dialog/amazeui.dialog.min.js"></script>

<!-- 日期组件 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Lib/My97DatePicker/WdatePicker.js"></script>

<!-- 项目公共 -->
<script type="text/javascript" src="/project/shop/service/Public/Common/Js/Common.js"></script>

<!-- 控制器 -->
<?php if(!empty($module_js)): ?><script type="text/javascript" src="/project/shop/service/Public/<?php echo ($module_js); ?>"></script><?php endif; ?>
<!-- footer end -->