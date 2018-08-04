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
		<form class="am-form form-validation view-save" action="<?php echo U('Admin/Goods/Save');?>" method="POST" request-type="ajax-url" request-value="<?php echo U('Admin/Goods/Index');?>" enctype="multipart/form-data">
			<input type="hidden" name="max_file_size" value="<?php echo MyC('home_max_limit_image', 2048000);?>" />
			<legend>
				<span class="fs-16">
					<?php if(empty($data['id'])): echo L('goods_add_name');?>
					<?php else: ?>
						<?php echo L('goods_edit_name'); endif; ?>
				</span>
				<a href="<?php echo U('Admin/Goods/Index');?>" class="fr fs-14 m-t-5 am-icon-mail-reply"> <?php echo L('common_operation_back');?></a>
			</legend>

			<nav class="goods-nav">
				<ul>
					<li>
						<a href="#goods-nav-base"><?php echo L('goods_nav_base_name');?></a>
					</li>
					<li>
						<a href="#goods-nav-photo"><?php echo L('goods_nav_photo_name');?></a>
					</li>
					<!-- <li>
						<a href="#goods-nav-video"><?php echo L('goods_nav_video_name');?></a>
					</li> -->
					<li>
						<a href="#goods-nav-attribute"><?php echo L('goods_nav_attribute_name');?></a>
					</li>
					<li>
						<a href="#goods-nav-app"><?php echo L('goods_nav_app_name');?></a>
					</li>
					<li>
						<a href="#goods-nav-web"><?php echo L('goods_nav_web_name');?></a>
					</li>
				</ul>
			</nav>

			<!-- 基础信息 -->
			<div id="goods-nav-base" class="division-block">
				<label class="block nav-detail-title"><?php echo L('goods_nav_base_name');?></label>
				<div class="am-form-group">
					<label><?php echo L('goods_title_text');?></label>
					<div class="am-input-group am-input-group-sm">
						<input type="hidden" name="title_color" value="<?php if(!empty($data['title_color'])): echo ($data["title_color"]); endif; ?>" />
						<input type="text" name="title" placeholder="<?php echo L('goods_title_text');?>" minlength="2" maxlength="60" data-validation-message="<?php echo L('goods_title_format');?>" class="am-form-field am-radius" <?php if(!empty($data)): ?>value="<?php echo ($data["title"]); ?>"<?php endif; ?> <?php if(!empty($data['title_color'])): ?>style="color:<?php echo ($data["title_color"]); ?>;"<?php endif; ?> required />
						<span class="am-input-group-btn">
							<button class="am-btn am-btn-default colorpicker-submit" type="button" data-input-tag="input[name='title']" data-color-tag="input[name='title_color']">
								<img src="/project/shop/service/Public/Common/Images/colorpicker.png" />
							</button>
						</span>
					</div>
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_model_text');?></label>
					<input type="text" name="model" placeholder="<?php echo L('goods_model_text');?>" maxlength="30" data-validation-message="<?php echo L('goods_model_format');?>" class="am-radius" <?php if(!empty($data)): ?>value="<?php echo ($data["model"]); ?>"<?php endif; ?> />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_category_id_text');?></label>
					<br />
					<select name="category_id" class="am-radius chosen-select c-p" multiple="multiple" data-placeholder="<?php echo L('common_please_select_choose');?>" data-validation-message="<?php echo L('goods_category_id_format');?>" required>
						<?php if(!empty($category_list)): if(is_array($category_list)): foreach($category_list as $key=>$v): ?><optgroup label="<?php echo ($v["name"]); ?>">
									<?php if(!empty($category_list)): if(is_array($v["items"])): foreach($v["items"] as $key=>$vs): ?><option value="<?php echo ($vs["id"]); ?>" <?php if(!empty($data['category_ids']) and in_array($vs['id'], $data['category_ids'])): ?>selected<?php endif; ?>><?php echo ($vs["name"]); ?></option><?php endforeach; endif; endif; ?>
								</optgroup><?php endforeach; endif; endif; ?>
					</select>
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_place_origin_text');?></label>
					<br />
					<select name="place_origin" class="am-radius chosen-select c-p" data-placeholder="<?php echo L('common_please_select_choose');?>" data-validation-message="<?php echo L('goods_place_origin_format');?>">
						<option value="0"><?php echo L('common_please_select_choose');?></option>
						<?php if(!empty($region_province_list)): if(is_array($region_province_list)): foreach($region_province_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if(!empty($data['place_origin']) and $v['id'] == $data['place_origin']): ?>selected<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; endif; ?>
					</select>
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_inventory_text');?></label>
					<input type="number" name="inventory" placeholder="<?php echo L('goods_inventory_text');?>" min="1" max="100000000" data-validation-message="<?php echo L('goods_inventory_format');?>" class="am-radius" <?php if(!empty($data)): ?>value="<?php echo ($data["inventory"]); ?>"<?php endif; ?> required />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_inventory_unit_text');?></label>
					<input type="text" name="inventory_unit" placeholder="<?php echo L('goods_inventory_unit_text');?>" minlength="1" maxlength="6" data-validation-message="<?php echo L('goods_inventory_unit_format');?>" class="am-radius" <?php if(!empty($data)): ?>value="<?php echo ($data["inventory_unit"]); ?>"<?php endif; ?> required />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_original_price_text');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_price_tips');?>）</span></label>
					<input type="text" placeholder="<?php echo L('goods_price_text');?>" name="original_price" pattern="<?php echo L('common_regex_price');?>" data-validation-message="<?php echo L('goods_price_format');?>" class="am-radius" <?php if(!empty($data['original_price']) and $data['original_price'] > 0): ?>value="<?php echo ($data["original_price"]); ?>"<?php endif; ?> />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_price_text');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_price_tips');?>）</span></label>
					<input type="text" placeholder="<?php echo L('goods_price_text');?>" name="price" pattern="<?php echo L('common_regex_price');?>" data-validation-message="<?php echo L('goods_price_format');?>" class="am-radius" <?php if(isset($data)): ?>value="<?php echo ($data["price"]); ?>"<?php endif; ?> required />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_give_integral_text');?></label>
					<input type="number" name="give_integral" placeholder="<?php echo L('goods_give_integral_text');?>" max="100000000" data-validation-message="<?php echo L('goods_give_integral_format');?>" class="am-radius" value="<?php if(empty($data)): ?>0<?php else: echo ($data["give_integral"]); endif; ?>" required />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_buy_min_number_text');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_buy_min_number_tips');?>）</span></label>
					<input type="number" name="buy_min_number" placeholder="<?php echo L('goods_buy_min_number_text');?>" min="1" max="100000000" data-validation-message="<?php echo L('goods_buy_min_number_format');?>" class="am-radius" value="<?php if(empty($data)): ?>1<?php else: echo ($data["buy_min_number"]); endif; ?>" required />
				</div>
				<div class="am-form-group">
					<label><?php echo L('goods_buy_max_number_text');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_buy_max_number_tips');?>）</span></label>
					<input type="number" name="buy_max_number" placeholder="<?php echo L('goods_buy_max_number_text');?>" min="0" max="100000000" data-validation-message="<?php echo L('goods_buy_max_number_format');?>" class="am-radius" <?php if(!empty($data['buy_max_number'])): ?>value="<?php echo ($data["buy_max_number"]); ?>"<?php endif; ?> />
				</div>
				<div class="am-form-group">
					<label class="block"><?php echo L('goods_is_deduction_inventory_text');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_is_deduction_inventory_tips');?>）</span></label>
					<input name="is_deduction_inventory" value="1" type="checkbox" data-off-text="<?php echo L('common_operation_off_is_text');?>" data-on-text="<?php echo L('common_operation_on_is_text');?>" data-size="xs" data-on-color="success" data-off-color="default" data-handle-width="50" data-am-switch <?php if(!empty($data) and $data['is_deduction_inventory'] == 1): ?>checked="true"<?php endif; ?> />
				</div>
				<div class="am-form-group">
					<label class="block"><?php echo L('goods_is_shelves_text');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_is_shelves_tips');?>）</span></label>
					<input name="is_shelves" value="1" type="checkbox" data-off-text="<?php echo L('common_operation_off_goods_text');?>" data-on-text="<?php echo L('common_operation_on_goods_text');?>" data-size="xs" data-on-color="success" data-off-color="default" data-handle-width="50" data-am-switch <?php if(!empty($data) and $data['is_shelves'] == 1): ?>checked="true"<?php endif; ?> />
				</div>
			</div>
			<div id="goods-nav-photo" class="division-block">
				<label class="block nav-detail-title"><?php echo L('goods_nav_photo_name');?><span class="fs-12 fw-100 cr-999">（<?php echo L('goods_images_tips');?>）</span></label>
				<ul class="plug-images-list" data-max-count="10" data-required="1" data-name="photo_file[]" data-delete-text="<?php echo L('common_operation_delete');?>" data-format="<?php echo L('goods_images_format');?>">
					<?php if(!empty($data['photo'])): if(is_array($data["photo"])): foreach($data["photo"] as $key=>$v): ?><li>
								<label class="plug-images-add-prohibit">
									<input type="hidden" name="photo[]" value="<?php echo ($v["images"]); ?>" />
									<div class="img-resources">
										<img src="<?php echo ($image_host); echo ($v["images"]); ?>" />
										<button type="button" class="am-btn am-btn-danger am-btn-xs am-btn-block plug-images-delete-submit"><?php echo L('common_operation_delete');?></button>
									</div>
								</label>
							</li><?php endforeach; endif; endif; ?>
				</ul>
			</div>
			<!-- <div id="goods-nav-video" class="division-block">
				<label class="block nav-detail-title"><?php echo L('goods_nav_video_name');?></label>
				视频
			</div> -->
			<div id="goods-nav-attribute" class="division-block">
				<label class="block nav-detail-title"><?php echo L('goods_nav_attribute_name');?></label>
				<ul class="goods-attribute-items" data-name="attribute" data-attribute-type-name="<?php echo L('goods_attribute_type_name');?>" data-attribute-type-placeholder="<?php echo L('goods_attribute_type_placeholder');?>" data-attribute-type-format="<?php echo L('goods_attribute_type_format');?>" data-attribute-type-type-name="<?php echo L('goods_attribute_type_type_name');?>" data-attribute-type-type-show="<?php echo L('goods_attribute_type_type_show');?>" data-attribute-type-type-choose="<?php echo L('goods_attribute_type_type_choose');?>" data-attribute-type-type-format="<?php echo L('goods_attribute_type_type_format');?>" data-attribute-add-sub-text="<?php echo L('goods_attribute_add_sub_text');?>" data-attribute-name="<?php echo L('goods_attribute_name');?>" data-attribute-placeholder="<?php echo L('goods_attribute_placeholder');?>" data-attribute-format="<?php echo L('goods_attribute_format');?>" data-drag-sort-text="<?php echo L('common_drag_sort_title');?>">
					<?php if(!empty($data['attribute'])): if(is_array($data["attribute"])): foreach($data["attribute"] as $k=>$v): ?><li class="goods-attribute goods-attribute-<?php echo ($v["id"]); ?>">
								<div class="attribute-type am-radius">
									<i class="am-icon-times-circle am-icon-sm c-p attribute-type-rem-sub"></i>
									<p class="am-form-group">
										<span><?php echo L('goods_attribute_type_name');?>&nbsp;</span>
										<input type="text" name="attribute_<?php echo ($v["id"]); ?>_data_name" class="am-radius" placeholder="<?php echo L('goods_attribute_type_name');?>" minlength="1" maxlength="10" data-validation-message="<?php echo L('goods_attribute_type_format');?>" value="<?php echo ($v["name"]); ?>" required />
									</p>
									<p class="am-form-group">
										<span><?php echo L('goods_attribute_type_type_name');?>&nbsp;</span>
										<span class="am-btn-group attribute-type-se" data-am-button="">
											<label class="am-btn am-btn-default am-radius am-btn-sm <?php if($v['type'] == 'show'): ?>br-sed am-active<?php endif; ?>">
												<input type="radio" name="attribute_<?php echo ($v["id"]); ?>_data_type" value="show" data-validation-message="<?php echo L('goods_attribute_type_type_format');?>" <?php if($v['type'] == 'show'): ?>checked<?php endif; ?> required /><?php echo L('goods_attribute_type_type_show');?>
											</label>
											<label class="am-btn am-btn-default am-radius am-btn-sm <?php if($v['type'] == 'choose'): ?>br-sed am-active<?php endif; ?>">
												<input type="radio" name="attribute_<?php echo ($v["id"]); ?>_data_type" value="choose" data-validation-message="<?php echo L('goods_attribute_type_type_format');?>" <?php if($v['type'] == 'choose'): ?>checked<?php endif; ?> required /><?php echo L('goods_attribute_type_type_choose');?>
											</label>
										</span>
									</p>
								</div>
								<ul class="attribute-items-ul-<?php echo ($v["id"]); ?>">
									<?php if(!empty($v['find'])): if(is_array($v["find"])): foreach($v["find"] as $key=>$vs): ?><li class="attribute">
											<i class="am-icon-times-circle-o am-icon-sm c-p attribute-rem-sub"></i>
											<input type="text" name="attribute_<?php echo ($v["id"]); ?>_find_<?php echo ($vs["id"]); ?>_name" class="am-radius" placeholder="<?php echo L('goods_attribute_name');?>" minlength="1" maxlength="10" data-validation-message="<?php echo L('goods_attribute_format');?>" value="<?php echo ($vs["name"]); ?>" required />
											<i class="am-icon-list-ul am-icon-sm c-m drag-sort-submit"> <?php echo L('common_drag_sort_title');?></i></li><?php endforeach; endif; endif; ?>
								</ul>
								<i class="am-icon-plus-square-o am-icon-sm attribute-add-sub c-p" name="attribute_<?php echo ($v["id"]); ?>_find" data-tag=".attribute-items-ul-<?php echo ($v["id"]); ?>" index="<?php echo count($v['find']);?>"> <?php echo L('goods_attribute_add_sub_text');?></i>
								<i class="am-icon-list-ul am-icon-sm c-m drag-sort-submit"> <?php echo L('common_drag_sort_title');?></i>
							</li><?php endforeach; endif; endif; ?>
				</ul>
				<label class="am-icon-plus-square am-icon-sm c-p attribute-type-add-sub"> <?php echo L('goods_attribute_type_add_sub_text');?></label>
			</div>
			<div id="goods-nav-app" class="division-block">
				<label class="block nav-detail-title"><?php echo L('goods_nav_app_name');?></label>
				<ul class="content-app-items" data-max-count="10" data-required="1" data-images-name="content_app_images" data-content-name="content_app_text" data-images-text="<?php echo L('goods_content_app_images_text');?>" data-content-text="<?php echo L('goods_content_app_text_text');?>" data-images-default="<?php echo ($image_host); ?>/Public/Admin/Default/Images/default-images.png" data-delete-text="<?php echo L('common_operation_delete');?>" data-drag-sort-text="<?php echo L('common_drag_sort_title');?>" data-select-images-text="<?php echo L('common_select_images_text');?>" data-select-images-format="<?php echo L('common_select_images_tips');?>">
					<?php if(!empty($data['content_app'])): if(is_array($data["content_app"])): foreach($data["content_app"] as $key=>$v): ?><li>
								<div>
									<div class="am-form-group am-form-file">
										<label class="block"><?php echo L('goods_content_app_images_text');?></label>
										<button type="button" class="am-btn am-btn-default am-btn-sm am-radius"><i class="am-icon-cloud-upload"></i> <?php echo L('common_select_images_text');?></button>
										<input type="text" name="content_app_images_<?php echo ($v["id"]); ?>" class="am-radius js-choice-one original-images-url original-images-url-tag-<?php echo ($v["id"]); ?>" data-choice-one-to=".images-file-tag-<?php echo ($v["id"]); ?>" data-validation-message="<?php echo L('common_select_images_tips');?>" readonly="readonly" value="<?php if(!empty($v['images'])): echo ($v["images"]); endif; ?>" />
										<input type="file" name="content_app_images_file_<?php echo ($v["id"]); ?>" data-validation-message="<?php echo L('common_select_images_tips');?>" accept="image/gif,image/jpeg,image/jpg,image/png" class="js-choice-one images-file-tag-<?php echo ($v["id"]); ?>" data-choice-one-to=".original-images-url-tag-<?php echo ($v["id"]); ?>" data-tips-tag="#form-images_url-tips-<?php echo ($v["id"]); ?>" data-image-tag="#form-img-images_url-<?php echo ($v["id"]); ?>">
										<div id="form-images_url-tips-<?php echo ($v["id"]); ?>" class="m-t-5"></div>
										<img src="<?php if(!empty($v['images'])): echo ($image_host); echo ($v["images"]); else: echo ($image_host); ?>/Public/Admin/Default/Images/default-images.png<?php endif; ?>" id="form-img-images_url-<?php echo ($v["id"]); ?>" class="block m-t-5 am-img-thumbnail am-radius" height="150" data-default="<?php echo ($image_host); ?>/Public/Admin/Default/Images/default-images.png">
									</div>
									<div class="am-form-group fr">
										<label><?php echo L('goods_content_app_text_text');?></label>
										<textarea rows="6" name="content_app_text_<?php echo ($v["id"]); ?>" maxlength="105000" class="am-radius" placeholder="<?php echo L('goods_content_app_text_text');?>" data-validation-message="<?php echo L('goods_content_app_text_format');?>"><?php if(isset($v)): echo ($v["content"]); endif; ?></textarea>
									</div>
								</div>
								<i class="am-icon-times-circle am-icon-sm c-p content-app-items-rem-sub"> 删除</i><i class="am-icon-list-ul am-icon-sm c-m drag-sort-submit"> <?php echo L('common_drag_sort_title');?></i>
							</li><?php endforeach; endif; endif; ?>
				</ul>
				<label class="am-icon-plus-square am-icon-sm c-p content-app-items-add-sub"> <?php echo L('goods_content_app_add_sub_text');?></label>
			</div>
			<div id="goods-nav-web" class="division-block">
				<label class="block nav-detail-title"><?php echo L('goods_nav_web_name');?></label>
				<div class="am-form-group">
					<textarea class="am-radius am-validate" name="content_web" maxlength="105000" id="editor-tag" data-url="<?php echo U('Admin/Ueditor/Index', ['path_type'=>'Article']);?>" data-validation-message="<?php echo L('goods_content_web_format');?>"><?php if(!empty($data)): echo ($data["content_web"]); endif; ?></textarea>
				</div>
			</div>

			<div class="am-form-group">
				<input type="hidden" name="id" <?php if(!empty($data)): ?>value="<?php echo ($data["id"]); ?>"<?php endif; ?>" />
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

<!-- 拖拽排序初始化 -->
<script type="text/javascript">
$(function()
{
	<?php if(!empty($data['attribute'])): if(is_array($data["attribute"])): foreach($data["attribute"] as $key=>$v): ?>$('ul.attribute-items-ul-<?php echo ($v["id"]); ?>').dragsort({ dragSelector: 'i.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});<?php endforeach; endif; endif; ?>

	<?php if(!empty($data['content_app'])): if(is_array($data["content_app"])): foreach($data["content_app"] as $key=>$v): ?>img_file_upload_show('.images-file-tag-<?php echo ($v["id"]); ?>');<?php endforeach; endif; endif; ?>
});
</script>