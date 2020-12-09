<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\facade\Hook;
use app\service\WarehouseService;

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
        $this->assign('data_list', $ret['data']);

        // 加载百度地图api
        $this->assign('is_load_baidu_map_api', 1);

        // 基础参数赋值
        $this->assign('params', $this->data_request);
        return $this->fetch();
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
            $this->assign('data', $data);

            // 加载百度地图api
            $this->assign('is_load_baidu_map_api', 1);
        }
        return $this->fetch();
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
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'warehouse_id'  => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]));

        // 加载百度地图api
        $this->assign('is_load_baidu_map_api', 1);

        // 编辑器文件存放地址
        $this->assign('editor_path_type', 'warehouse');

        // 数据
        unset($params['id']);
        $this->assign('data', $data);
        $this->assign('params', $params);
        return $this->fetch();
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