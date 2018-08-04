<?php

namespace Admin\Model;
use Think\Model;

/**
 * 商品模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(		
		// 添加,编辑
		array('title', '2,60', '{%goods_title_format}', 1, 'length', 3),
		array('model', '0,30', '{%goods_model_format}', 1, 'length', 3),
		array('category_id', 'require', '{%goods_category_id_format}', 1, '', 3),
		array('inventory', 'require', '{%goods_inventory_format}', 1, '', 3),
		array('inventory_unit', 'require', '{%goods_inventory_unit_format}', 1, '', 3),
		array('price', 'require', '{%goods_price_format}', 1, '', 3),
		array('buy_min_number', 'require', '{%goods_buy_min_number_format}', 1, '', 3),
	);
}
?>