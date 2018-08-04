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
		<form class="am-form form-validation view-save" action="<?php echo U('Admin/Config/Save');?>" method="POST" request-type="ajax-url" request-value="<?php echo U('Admin/Config/Index');?>">
			<div class="am-form-group">
				<label><?php echo ($data["admin_excel_charset"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["admin_excel_charset"]["describe"]); ?>）</span></label>
				<select name="<?php echo ($data["admin_excel_charset"]["only_tag"]); ?>" class="am-radius chosen-select c-p" data-validation-message="<?php echo ($data["admin_excel_charset"]["error_tips"]); ?>" required>
					<?php if(is_array($common_excel_charset_list)): foreach($common_excel_charset_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($data['admin_excel_charset']['value']) and $data['admin_excel_charset']['value'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["admin_page_number"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["admin_page_number"]["describe"]); ?>）</span></label>
				<input type="number" name="<?php echo ($data["admin_page_number"]["only_tag"]); ?>" placeholder="<?php echo ($data["admin_page_number"]["name"]); ?>" pattern="<?php echo L('common_regex_page_number');?>" data-validation-message="<?php echo ($data["admin_page_number"]["error_tips"]); ?>" class="am-radius" <?php if(isset($data)): ?>value="<?php echo ($data["admin_page_number"]["value"]); ?>"<?php endif; ?> required />
			</div>
			<div class="am-form-group">
			    <label class="block"><?php echo ($data["common_is_deduction_inventory"]["name"]); ?></label>
			    <input name="<?php echo ($data["common_is_deduction_inventory"]["only_tag"]); ?>" value="1" type="checkbox" data-off-text="<?php echo L('common_operation_off_is_text');?>" data-on-text="<?php echo L('common_operation_on_is_text');?>" data-size="xs" data-on-color="success" data-off-color="default" data-handle-width="50" data-am-switch <?php if(!empty($data) and $data['common_is_deduction_inventory']['value'] == 1): ?>checked="true"<?php endif; ?> />
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_deduction_inventory_rules"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["common_deduction_inventory_rules"]["describe"]); ?>）</span></label>
				<select name="<?php echo ($data["common_deduction_inventory_rules"]["only_tag"]); ?>" class="am-radius chosen-select c-p" data-validation-message="<?php echo ($data["common_deduction_inventory_rules"]["error_tips"]); ?>" required>
					<?php if(is_array($common_deduction_inventory_rules_list)): foreach($common_deduction_inventory_rules_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($data['common_deduction_inventory_rules']['value']) and $data['common_deduction_inventory_rules']['value'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_share_giving_integral_frequency"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["common_share_giving_integral_frequency"]["describe"]); ?>）</span></label>
				<input type="number" name="<?php echo ($data["common_share_giving_integral_frequency"]["only_tag"]); ?>" placeholder="<?php echo ($data["common_share_giving_integral_frequency"]["name"]); ?>" data-validation-message="<?php echo ($data["common_share_giving_integral_frequency"]["error_tips"]); ?>" class="am-radius" <?php if(isset($data)): ?>value="<?php echo ($data["common_share_giving_integral_frequency"]["value"]); ?>"<?php endif; ?> />
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_share_giving_integral"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["common_share_giving_integral"]["describe"]); ?>）</span></label>
				<input type="number" name="<?php echo ($data["common_share_giving_integral"]["only_tag"]); ?>" placeholder="<?php echo ($data["common_share_giving_integral"]["name"]); ?>" data-validation-message="<?php echo ($data["common_share_giving_integral"]["error_tips"]); ?>" class="am-radius" <?php if(isset($data)): ?>value="<?php echo ($data["common_share_giving_integral"]["value"]); ?>"<?php endif; ?> />
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_share_view_desc"]["name"]); ?></label>
				<textarea rows="3" name="<?php echo ($data["common_share_view_desc"]["only_tag"]); ?>" class="am-radius" placeholder="<?php echo ($data["common_share_view_desc"]["name"]); ?>" data-validation-message="<?php echo ($data["common_share_view_desc"]["error_tips"]); ?>"><?php if(isset($data)): echo ($data["common_share_view_desc"]["value"]); endif; ?></textarea>
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_user_center_notice"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["common_user_center_notice"]["describe"]); ?>）</span></label>
				<textarea rows="3" name="<?php echo ($data["common_user_center_notice"]["only_tag"]); ?>" class="am-radius" placeholder="<?php echo ($data["common_user_center_notice"]["name"]); ?>" data-validation-message="<?php echo ($data["common_user_center_notice"]["error_tips"]); ?>"><?php if(isset($data)): echo ($data["common_user_center_notice"]["value"]); endif; ?></textarea>
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_baidu_map_ak"]["name"]); ?><span class="fs-12 fw-100 cr-999">（<?php echo ($data["common_baidu_map_ak"]["describe"]); ?>）</span></label>
				<input type="text" name="<?php echo ($data["common_baidu_map_ak"]["only_tag"]); ?>" placeholder="<?php echo ($data["common_baidu_map_ak"]["describe"]); ?>" data-validation-message="<?php echo ($data["common_baidu_map_ak"]["error_tips"]); ?>" class="am-radius" <?php if(isset($data)): ?>value="<?php echo ($data["common_baidu_map_ak"]["value"]); ?>"<?php endif; ?> />
			</div>
			<div class="am-form-group">
				<label><?php echo ($data["common_customer_service_tel"]["name"]); ?></label>
				<input type="text" name="<?php echo ($data["common_customer_service_tel"]["only_tag"]); ?>" placeholder="<?php echo ($data["common_customer_service_tel"]["describe"]); ?>" data-validation-message="<?php echo ($data["common_customer_service_tel"]["error_tips"]); ?>" class="am-radius" <?php if(isset($data)): ?>value="<?php echo ($data["common_customer_service_tel"]["value"]); ?>"<?php endif; ?> />
			</div>
			<div class="am-form-group">
				<button type="submit" class="am-btn am-btn-primary am-radius btn-loading-example am-btn-sm w100" data-am-loading="{loadingText:'<?php echo L('common_form_loading_tips');?>'}"><?php echo L('common_operation_save');?></button>
			</div>
		</form>
        <!-- form end -->
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