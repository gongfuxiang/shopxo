<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\service\GoodsParamsService;

/**
 * 商品参数管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-27
 * @desc    description
 */
class GoodsParamsTemplate extends Common
{
	/**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		$this->IsLogin();

		// 权限校验
		$this->IsPower();
	}

	/**
     * 列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
	public function Index()
	{
        return MyView();
	}

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
    public function Detail()
    {
        // 数据
        $data = $this->data_detail;

        // 商品参数类型
        MyViewAssign('common_goods_parameters_type_list', MyConst('common_goods_parameters_type_list'));

        // 参数配置
        MyViewAssign('parameters', empty($data['config_data']) ? [] : $data['config_data']);

        return MyView();
    }

    /**
     * 添加/编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
    public function SaveInfo()
    {
        // 参数
        $params = $this->data_request;

        // 数据
        $data = $this->data_detail;

        // 商品参数类型
        MyViewAssign('common_goods_parameters_type_list', MyConst('common_goods_parameters_type_list'));

        // 参数配置
        MyViewAssign('parameters', empty($data['config_data']) ? [] : $data['config_data']);

        // 编辑页面钩子
        $hook_name = 'plugins_view_admin_goods_params_template_save';
        MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data_id'       => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]));

        // 数据
        unset($params['id']);
        MyViewAssign('data', $data);
        MyViewAssign('params', $params);
        return MyView();
    }

	/**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
	public function Save()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return GoodsParamsService::GoodsParamsTemplateSave($params);
	}

	/**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
	public function Delete()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        $params['user_type'] = 'admin';
        return GoodsParamsService::GoodsParamsTemplateDelete($params);
	}

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-27
     * @desc    description
     */
    public function StatusUpdate()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return GoodsParamsService::GoodsParamsTemplateStatusUpdate($params);
    }
}
?>