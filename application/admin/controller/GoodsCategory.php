<?php
namespace app\admin\controller;

use app\service\GoodsService;

/**
 * 分类管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsCategory extends Common
{
	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 分类列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 是否启用
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));

        // 是否
        $this->assign('common_is_text_list', lang('common_is_text_list'));

        // 编辑器文件存放地址
		$this->assign('editor_path_type', 'goods_category');

		return $this->fetch();
	}

	/**
	 * [GetNodeSon 获取节点子列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T15:19:45+0800
	 */
	public function GetNodeSon()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$ret = GoodsService::GoodsCategoryNodeSon(input());
		return json($ret);
	}


	/**
	 * [Save 分类保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$ret = GoodsService::GoodsCategorySave(input());
		return json($ret);
	}

	/**
	 * [Delete 分类删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Delete()
	{
		if(!IS_AJAX)
		{
			$this->error(lang('common_unauthorized_access'));
		}

		$m = D('GoodsCategory');
		if($m->create($_POST, 5))
		{
			if($m->delete(I('id')))
			{
				return json(lang('common_operation_delete_success'));
			} else {
				return json(lang('common_operation_delete_error'), -100);
			}
		} else {
			return json($m->getError(), -1);
		}
	}
}
?>