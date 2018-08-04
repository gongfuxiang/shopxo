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
		<form class="am-form view-list" action="<?php echo U('Admin/Goods/Index');?>" method="POST">
			<div class="am-g">
				<input type="text" class="am-radius form-keyword" placeholder="<?php echo L('goods_so_keyword_tips');?>" name="keyword" <?php if(isset($param['keyword'])): ?>value="<?php echo ($param["keyword"]); ?>"<?php endif; ?> />
				<button type="submit" class="am-btn am-btn-secondary am-btn-sm am-radius form-submit"><?php echo L('common_operation_query');?></button>
				<label class="fs-12 m-l-5 c-p fw-100 more-submit">
					<?php echo L('common_more_screening');?>
					<input type="checkbox" name="is_more" value="1" id="is_more" <?php if(isset($param['is_more']) and $param['is_more'] == 1): ?>checked<?php endif; ?> />
					<i class="am-icon-angle-down"></i>
				</label>

				<div class="more-where <?php if(!isset($param['is_more']) or $param['is_more'] != 1): ?>none<?php endif; ?>">
					<select name="is_shelves" class="am-radius c-p m-t-10 m-l-5 param-where">
						<option value="-1"><?php echo L('goods_is_shelves_text');?></option>
						<?php if(is_array($common_goods_is_shelves_list)): foreach($common_goods_is_shelves_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(isset($param['is_shelves']) and $param['is_shelves'] == $v['id']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
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
            <a href="<?php echo U('Admin/Goods/SaveInfo');?>" class="am-btn am-btn-secondary am-radius am-btn-xs am-icon-plus"> <?php echo L('common_operation_add');?></a>
        </div>
        <!-- operation end -->

		<!-- list start -->
		<table class="am-table am-table-striped am-table-hover am-text-middle m-t-10">
			<thead>
				<tr>
					<th><?php echo L('goods_title_text');?></th>
					<th><?php echo L('goods_price_text');?></th>
					<th><?php echo L('goods_is_shelves_text');?></th>
					<th class="am-hide-sm-only"><?php echo L('goods_inventory_text');?></th>
					<th class="am-hide-sm-only"><?php echo L('goods_model_text');?></th>
					<th class="am-hide-sm-only"><?php echo L('common_create_time_name');?></th>
					<th><?php echo L('common_more_name');?></th>
					<th><?php echo L('common_operation_name');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$v): ?><tr id="data-list-<?php echo ($v["id"]); ?>" <?php if($v['is_shelves'] == 0): ?>class="am-active"<?php endif; ?>>
							<td>
								<a href="javascript:;" target="_blank" title="<?php echo ($v["title"]); ?>">
									<img src="<?php echo ($image_host); echo ($v['images']); ?>" class="am-img-thumbnail am-radius goods-images am-hide-sm-only" />
								</a>
								<a href="javascript:;" target="_blank" title="<?php echo ($v["title"]); ?>" <?php if(!empty($v['title_color'])): ?>style="color:<?php echo ($v["title_color"]); ?>;"<?php endif; ?> ><?php echo ($v["title"]); ?></a>
							</td>
							<td>
								<?php echo ($v["price"]); ?>
								<?php if(!empty($v['original_price']) and $v['original_price'] > 0): ?><br /><span class="am-badge am-radius"><?php echo L('goods_original_price_icon');?> <?php echo ($v["original_price"]); ?></span><?php endif; ?>
							</td>
							<td>
								<a href="javascript:;" class="am-icon-btn am-icon-check submit-state <?php if($v['is_shelves'] == 1): ?>am-success<?php else: ?>am-default<?php endif; ?>" data-url="<?php echo U('Admin/Goods/StatusUpdate');?>" data-id="<?php echo ($v["id"]); ?>" data-state="<?php echo ($v['is_shelves']); ?>"></a>
							</td>
							<td class="am-hide-sm-only"><?php echo ($v["inventory"]); ?> <?php echo ($v["inventory_unit"]); ?></td>
							<td class="am-hide-sm-only"><?php echo ($v["model"]); ?></td>
							<td class="am-hide-sm-only"><?php echo ($v["add_time"]); ?></td>
							<td>
								<span class="am-icon-caret-down c-p" data-am-modal="{target: '#my-popup<?php echo ($v["id"]); ?>'}"> <?php echo L('common_see_more_name');?></span>
								<div class="am-popup am-radius" id="my-popup<?php echo ($v["id"]); ?>">
									<div class="am-popup-inner">
										<div class="am-popup-hd">
											<h4 class="am-popup-title"><?php echo L('common_detail_content');?></h4>
											<span data-am-modal-close
											class="am-close">&times;</span>
										</div>
										<div class="am-popup-bd">
											<dl class="dl-content">
												<dt><?php echo L('goods_title_text');?></dt>
												<dd><?php echo ($v["title"]); ?></dd>

												<dt><?php echo L('goods_price_text');?></dt>
												<dd>
													<?php echo ($v["price"]); ?>
													<?php if(!empty($v['original_price']) and $v['original_price'] > 0): ?><br /><span class="am-badge am-radius"><?php echo L('goods_original_price_icon');?> <?php echo ($v["original_price"]); ?></span><?php endif; ?>
												</dd>

												<dt><?php echo L('goods_is_shelves_text');?></dt>
												<dd><?php echo L('common_goods_is_shelves_list')[$v['is_shelves']]['name'];?></dd>

												<dt><?php echo L('goods_inventory_text');?></dt>
												<dd><?php echo ($v["inventory"]); ?> <?php echo ($v["inventory_unit"]); ?></dd>

												<dt><?php echo L('goods_buy_min_number_text');?></dt>
												<dd><?php echo ($v["buy_min_number"]); ?> <?php echo ($v["inventory_unit"]); ?></dd>

												<dt><?php echo L('goods_buy_max_number_text');?></dt>
												<dd><?php if($v['buy_max_number'] == 0): ?><span class="cr-ddd"><?php echo L('common_unlimited_text');?></span><?php else: echo ($v["buy_max_number"]); ?> <?php echo ($v["inventory_unit"]); endif; ?></dd>

												<dt><?php echo L('goods_model_text');?></dt>
												<dd><?php echo ($v["model"]); ?></dd>

												<dt><?php echo L('goods_place_origin_text');?></dt>
												<dd><?php echo ($v["place_origin_text"]); ?></dd>

												<dt><?php echo L('goods_category_id_text');?></dt>
												<dd><?php echo ($v["category_text"]); ?></dd>

												<dt><?php echo L('goods_give_integral_text');?></dt>
												<dd><?php echo ($v["give_integral"]); ?></dd>

												<dt><?php echo L('goods_is_deduction_inventory_text');?></dt>
												<dd><?php echo L('common_is_text_list')[$v['is_deduction_inventory']]['name'];?></dd>

												<dt><?php echo L('common_view_access_count_text');?></dt>
												<dd><?php echo ($v["access_count"]); ?></dd>

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
								<a href="<?php echo U('Admin/Goods/SaveInfo', array('id'=>$v['id']));?>">
									<button class="am-btn am-btn-default am-btn-xs am-radius am-icon-edit"></button>
								</a>
								<button class="am-btn am-btn-default am-btn-xs am-radius am-icon-trash-o submit-delete" data-url="<?php echo U('Admin/Goods/Delete');?>" data-id="<?php echo ($v["id"]); ?>"></button>
							</td>
						</tr><?php endforeach; endif; ?>
				<?php else: ?>
					<tr><td colspan="10" class="table-no"><?php echo L('common_not_data_tips');?></td></tr><?php endif; ?>
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