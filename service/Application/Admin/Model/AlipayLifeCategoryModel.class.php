<?php

namespace Admin\Model;
use Think\Model;

/**
 * 生活号分类模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeCategoryModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
		array('name', '2,16', '{%common_name_format}', 1, 'length', 3),
		array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
		array('sort', 'CheckSort', '{%common_sort_error}', 1, 'function', 3),

		// 删除校验是否存在子级
		array('id', 'IsExistSon', '{%common_is_exist_son_error}', 1, 'callback', 5),
	);

	/**
	 * [IsExistSon 校验节点下是否存在子级数据]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 */
	public function IsExistSon()
	{
		return ($this->db(0)->where(array('pid'=>I('id')))->count() == 0);
	}
}
?>