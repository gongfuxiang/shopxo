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
        <!-- operation start -->
        <div class="am-g">
            <button class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-plus tree-submit-add" data-am-modal="{target: '#data-save-win'}"> <?php echo L('common_operation_add');?></button>
        </div>
        <!-- operation end -->

        <!-- save win start -->
        <div class="am-popup am-radius" id="data-save-win">
			<div class="am-popup-inner">
				<div class="am-popup-hd">
					<h4 class="am-popup-title" data-add-title="<?php echo L('express_add_name');?>" data-edit-title="<?php echo L('express_edit_name');?>"><?php echo L('express_add_name');?></h4>
					<span data-am-modal-close class="am-close">&times;</span>
				</div>
				<div class="am-popup-bd">
					<!-- form start -->
					<form class="am-form form-validation admin-save" action="<?php echo U('Admin/Express/Save');?>" method="POST" request-type="ajax-reload" request-value="">
						<div class="am-form-group am-form-file">
							<label class="block"><?php echo L('common_icon_text');?></label>
							<button type="button" class="am-btn am-btn-default am-btn-sm am-radius">
							<i class="am-icon-cloud-upload"></i> <?php echo L('common_select_images_text');?></button>
							<input type="text" name="icon" class="am-radius js-choice-one original-images-url original-images-url-delete" data-choice-one-to=".images-file-event" <?php if(!empty($data)): ?>value="<?php echo ($data["images_url"]); ?>"<?php endif; ?>" data-validation-message="<?php echo L('common_select_images_tips');?>" readonly="readonly" />
							<i class="am-icon-trash-o am-icon-sm original-images-url-delete" data-input-tag=".original-images-url"></i>
							<input type="file" name="file_icon" multiple data-validation-message="<?php echo L('common_select_images_tips');?>" accept="image/gif,image/jpeg,image/jpg,image/png" class="js-choice-one images-file-event" data-choice-one-to=".original-images-url" data-tips-tag="#form-images_url-tips" data-image-tag="#form-img-images_url" />
							<div id="form-images_url-tips" class="m-t-5"></div>
							<img src="<?php if(!empty($data['images_url'])): echo ($image_host); echo ($data["images_url"]); else: echo ($image_host); ?>/Public/Admin/Default/Images/default-images.png<?php endif; ?>" id="form-img-images_url" class="block m-t-5 am-img-thumbnail am-radius" width="50" height="50" data-default="<?php if(!empty($data['images_url'])): echo ($image_host); echo ($data["images_url"]); else: echo ($image_host); ?>/Public/Admin/Default/Images/default-images.png<?php endif; ?>" />
						</div>
						<div class="am-form-group">
							<label><?php echo L('common_name_text');?></label>
							<input type="text" placeholder="<?php echo L('common_name_text');?>" name="name" minlength="2" maxlength="16" data-validation-message="<?php echo L('common_name_format');?>" class="am-radius" required />
						</div>
						<div class="am-form-group">
							<label><?php echo L('common_view_sort_title');?></label>
							<input type="number" placeholder="<?php echo L('common_view_sort_title');?>" name="sort" min="0" max="255" data-validation-message="<?php echo L('common_sort_error');?>" class="am-radius" value="0" required />
						</div>
						<!-- 是否启用 开始 -->
<div class="am-form-group">
	<label><?php echo L('common_view_enable_title');?></label>
	<div>
		<?php if(is_array($common_is_enable_list)): foreach($common_is_enable_list as $key=>$v): ?><label class="am-radio-inline m-r-10">
				<input type="radio" name="is_enable" value="<?php echo ($v["id"]); ?>" <?php if(isset($data['is_enable']) and $data['is_enable'] == $v['id']): ?>checked="checked"<?php else: if(!isset($data['is_enable']) and isset($v['checked']) and $v['checked'] == true): ?>checked="checked"<?php endif; endif; ?> data-am-ucheck /> <?php echo ($v["name"]); ?>
			</label><?php endforeach; endif; ?>
	</div>
</div>
<!-- 是否启用 结束 -->
						<div class="am-form-group">
							<input type="hidden" name="id" />
							<button type="submit" class="am-btn am-btn-primary am-radius btn-loading-example am-btn-sm w100" data-am-loading="{loadingText:'<?php echo L('common_form_loading_tips');?>'}"><?php echo L('common_operation_save');?></button>
						</div>
					</form>
					<!-- form end -->
				</div>
			</div>
		</div>
		<!-- save win end -->

        <!-- list start -->
		<div id="tree" class="m-t-15">
			<div class="m-t-30 t-c">
				<img src="/project/shop/service/Public/Common/Images/loading.gif" />
				<p><?php echo L('common_form_loading_tips');?></p>
			</div>
		</div>
		<!-- list end -->
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
<script>
	Tree(0, "<?php echo U('Admin/Express/GetNodeSon');?>", 0);
</script>