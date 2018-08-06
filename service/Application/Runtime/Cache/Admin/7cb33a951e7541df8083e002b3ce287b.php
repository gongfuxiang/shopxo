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
		<p class="fw-700 list-title"><?php echo L('os_view_title');?></p>
		<dl class="dl-content">
			<dt><?php echo L('ver_name');?></dt>
			<dd><?php echo ($data["ver"]); ?></dd>

			<dt><?php echo L('os_ver_name');?></dt>
			<dd><?php echo ($data["os_ver"]); ?></dd>

			<dt><?php echo L('php_ver_name');?></dt>
			<dd><?php echo ($data["php_ver"]); ?></dd>

			<dt><?php echo L('mysql_ver_name');?></dt>
			<dd><?php echo ($data["mysql_ver"]); ?></dd>

			<dt><?php echo L('server_ver_name');?></dt>
			<dd><?php echo ($data["server_ver"]); ?></dd>

			<dt><?php echo L('host_name');?></dt>
			<dd><?php echo ($data["host"]); ?></dd>
		</dl>
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