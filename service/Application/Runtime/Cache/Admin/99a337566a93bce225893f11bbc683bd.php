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
		<form class="am-form view-list" action="<?php echo U('Admin/SendOrder/Index');?>" method="POST">
			<div class="am-g">
				<input type="hidden" name="organization_id" <?php if(isset($param['organization_id'])): ?>value="<?php echo ($param["organization_id"]); ?>"<?php endif; ?> />
				<input type="text" class="am-radius form-keyword" placeholder="<?php echo L('sendorder_so_keyword_tips');?>" name="keyword" <?php if(isset($param['keyword'])): ?>value="<?php echo ($param["keyword"]); ?>"<?php endif; ?> />
				<button type="submit" class="am-btn am-btn-secondary am-btn-sm am-radius form-submit"><?php echo L('common_operation_query');?></button>
				<label class="fs-12 m-l-5 c-p fw-100 more-submit">
					<?php echo L('common_more_screening');?>
					<input type="checkbox" name="is_more" value="1" id="is_more" <?php if(isset($param['is_more']) and $param['is_more'] == 1): ?>checked<?php endif; ?> />
					<i class="am-icon-angle-down"></i>
				</label>

				<div class="more-where <?php if(!isset($param['is_more']) or $param['is_more'] != 1): ?>none<?php endif; ?>">
					<select name="status" class="am-radius c-p m-t-10 m-l-5 param-where">
						<option value="-1"><?php echo L('sendorder_status_text');?></option>
						<?php if(is_array($common_sendorder_admin_status)): foreach($common_sendorder_admin_status as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($param['status']) and $param['status'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
					</select>
					<select name="express_id" class="am-radius c-p m-t-10 m-l-5 param-where">
						<option value="-1"><?php echo L('sendorder_express_name_text');?></option>
						<?php if(is_array($express_list)): foreach($express_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($param['express_id']) and $param['express_id'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
					</select>
					<select name="goods_id" class="am-radius c-p m-t-10 m-l-5 param-where">
						<option value="-1"><?php echo L('sendorder_goods_name_text');?></option>
						<?php if(is_array($goods_list)): foreach($goods_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($param['goods_id']) and $param['goods_id'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
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

		<!-- list start -->
		<table class="am-table am-table-striped am-table-hover am-text-middle m-t-10 m-l-5">
			<thead>
				<tr>
					<th class="am-hide-sm-only"><?php echo L('sendorder_merchant_name_text');?></th>
					<th class="am-hide-sm-only"><?php echo L('sendorder_sender_text');?></th>
					<th><?php echo L('sendorder_receive_text');?></th>
					<th class="am-hide-sm-only"><?php echo L('sendorder_express_text');?></th>
					<th class="am-hide-sm-only"><?php echo L('sendorder_goods_name_text');?></th>
					<th><?php echo L('sendorder_status_text');?></th>
					<th><?php echo L('sendorder_price_text');?></th>
					<th><?php echo L('common_more_name');?></th>
					<th><?php echo L('common_operation_name');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$v): ?><tr id="data-list-<?php echo ($v["id"]); ?>">
							<td class="am-hide-sm-only"><?php echo ($v["merchant_name"]); ?></td>
							<td class="am-hide-sm-only">
								<?php echo ($v["sender_name"]); ?><br />
								<?php echo ($v["sender_tel"]); ?><br />
								<?php echo ($v["sender_address"]); ?>
							</td>
							<td>
								<?php echo ($v["receive_name"]); ?><br />
								<?php echo ($v["receive_tel"]); ?><br />
								<?php echo ($v["receive_address"]); ?>
							</td>
							<td class="am-hide-sm-only">
								<?php if(empty($v['express_name'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span>
								<?php else: ?>
									<?php echo ($v["express_name"]); ?><br /><?php echo ($v["express_number"]); endif; ?>
							</td>
							<td class="am-hide-sm-only"><?php echo ($v["goods_name"]); ?></td>
							<td><?php echo ($v["status_text"]); ?></td>
							<td>
								<?php if($v['price'] <= 0.00): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["price"]); endif; ?>
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
												<dt><?php echo L('sendorder_merchant_name_text');?></dt>
												<dd><?php if(empty($v['merchant_name'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["merchant_name"]); endif; ?></dd>

												<dt><?php echo L('sendorder_sender_text');?></dt>
												<dd><?php if(empty($v['sender_address'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["sender_name"]); ?><br /><?php echo ($v["sender_tel"]); ?><br /><?php echo ($v["sender_address"]); endif; ?></dd>

												<dt><?php echo L('sendorder_receive_text');?></dt>
												<dd><?php if(empty($v['receive_address'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["receive_name"]); ?><br /><?php echo ($v["receive_tel"]); ?><br /><?php echo ($v["receive_address"]); endif; ?></dd>

												<dt><?php echo L('sendorder_express_text');?></dt>
												<dd><?php if(empty($v['express_name'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["express_name"]); ?><br /><?php echo ($v["express_number"]); endif; ?></dd>

												<dt><?php echo L('sendorder_goods_name_text');?></dt>
												<dd><?php if(empty($v['goods_name'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["goods_name"]); endif; ?></dd>

												<dt><?php echo L('sendorder_user_note_text');?></dt>
												<dd><?php if(empty($v['user_note'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["user_note"]); endif; ?></dd>

												<dt><?php echo L('sendorder_price_text');?></dt>
												<dd><?php if(empty($v['price'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["price"]); endif; ?></dd>

												<dt><?php echo L('sendorder_status_text');?></dt>
												<dd><?php if(empty($v['status_text'])): ?><span class="cr-ddd"><?php echo L('common_not_set_text');?></span><?php else: echo ($v["status_text"]); endif; ?></dd>

												<dt><?php echo L('sendorder_sender_time_text');?></dt>
												<dd><?php if(empty($v['sender_time'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["sender_time"]); endif; ?></dd>

												<dt><?php echo L('sendorder_booking_time_text');?></dt>
												<dd><?php if(empty($v['booking_time'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["booking_time"]); endif; ?></dd>

												<dt><?php echo L('sendorder_budget_time_text');?></dt>
												<dd><?php if(empty($v['budget_time'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["budget_time"]); endif; ?></dd>

												<dt><?php echo L('sendorder_collection_time_text');?></dt>
												<dd><?php if(empty($v['collection_time'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["collection_time"]); endif; ?></dd>

												<dt><?php echo L('sendorder_success_time_text');?></dt>
												<dd><?php if(empty($v['success_time'])): ?><span class="cr-ddd"><?php echo L('common_on_fill_in_the_text');?></span><?php else: echo ($v["success_time"]); endif; ?></dd>

												<dt><?php echo L('common_create_time_name');?></dt>
												<dd><?php echo ($v["add_time"]); ?></dd>

												<dt><?php echo L('common_upd_time_name');?></dt>
												<dd><?php echo ($v["upd_time"]); ?></dd>
											</dl>
										</div>
									</div>
								</div>
							</td>
							<td class="view-operation">
								<?php if(!in_array($v['status'], [4,5])): ?><button class="am-btn am-btn-default am-btn-xs am-radius am-icon-paint-brush submit-ajax" data-url="<?php echo U('Admin/SendOrder/Cancel');?>" data-id="<?php echo ($v["id"]); ?>" data-view="reload" data-msg="<?php echo L('common_cancel_tips');?>"> <?php echo L('common_operation_cancel');?></button><?php endif; ?>
								<button class="am-btn am-btn-default am-btn-xs am-radius am-icon-trash-o submit-delete" data-url="<?php echo U('Admin/SendOrder/Delete');?>" data-id="<?php echo ($v["id"]); ?>"> <?php echo L('common_operation_delete');?></button>
							</td>
						</tr><?php endforeach; endif; ?>
				<?php else: ?>
					<tr><td colspan="20" class="table-no"><?php echo L('common_not_data_tips');?></td></tr><?php endif; ?>
			</tbody>
		</table>
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