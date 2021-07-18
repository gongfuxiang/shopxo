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

use app\service\WarehouseService;
use app\service\ResourcesService;

/**
 * 仓库管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-07
 * @desc    description
 */
class Warehouse extends Common
{
    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-07
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
     * @date    2020-07-07
     * @desc    description
     */
    public function Index()
    {
        // 获取列表
        $data_params = [
            'where'         => $this->form_where,
            'order_by'      => $this->form_order_by['data'],
        ];
        $ret = WarehouseService::WarehouseList($data_params);
        MyViewAssign('data_list', $ret['data']);

        // 加载百度地图api
        MyViewAssign('is_load_baidu_map_api', 1);

        // 基础参数赋值
        MyViewAssign('params', $this->data_request);
        return MyView();
    }

    /**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-07-07
     */
    public function Detail()
    {
        if(!empty($this->data_request['id']))
        {
            // 条件
            $where = [
                ['id', '=', intval($this->data_request['id'])],
            ];

            // 获取列表
            $data_params = [
                'm'             => 0,
                'n'             => 1,
                'where'         => $where,
            ];
            $ret = WarehouseService::WarehouseList($data_params);
            $data = (empty($ret['data']) || empty($ret['data'][0])) ? [] : $ret['data'][0];
            MyViewAssign('data', $data);

            // 加载百度地图api
            MyViewAssign('is_load_baidu_map_api', 1);
        }
        return MyView();
    }

    /**
     * 添加/编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-07
     * @desc    description
     */
    public function SaveInfo()
    {
        // 参数
        $params = $this->data_request;

        // 数据
        $data = [];
        if(!empty($params['id']))
        {
            // 获取列表
            $data_params = array(
                'where' => ['id'=>intval($params['id'])],
            );
            $ret = WarehouseService::WarehouseList($data_params);
            $data = empty($ret['data'][0]) ? [] : $ret['data'][0];
        }

        // 编辑页面钩子
        $hook_name = 'plugins_view_admin_warehouse_save';
        MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'warehouse_id'  => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]));

        // 加载百度地图api
        MyViewAssign('is_load_baidu_map_api', 1);

        // 编辑器文件存放地址
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue('warehouse'));

        // 数据
        unset($params['id']);
        MyViewAssign('data', $data);
        MyViewAssign('params', $params);
        return MyView();
    }

    /**
     * 添加/编辑
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-07
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
        return WarehouseService::WarehouseSave($params);
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-07
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
        $params['admin'] = $this->admin;
        return WarehouseService::WarehouseDelete($params);
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-07
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
        $params['admin'] = $this->admin;
        return WarehouseService::WarehouseStatusUpdate($params);
    }
}
?>