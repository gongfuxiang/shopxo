<?php

namespace Admin\Model;
use Think\Model;

/**
 * 品牌模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BrandModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
        array('logo', '0,255', '{%brand_logo_format}', 1, 'length', 3),
		array('name', '2,16', '{%brand_name_format}', 1, 'length', 3),
		array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
		array('sort', 'CheckSort', '{%common_sort_error}', 1, 'function', 3),
        array('brand_category_id', 'require', '{%brand_category_id_format}', 1, '', 3),
	);
}
?>