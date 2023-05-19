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

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\AdminRoleService;

/**
 * 角色管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Role extends Base
{
    /**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function Index()
    {
        return MyView();
    }

    /**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-05T08:21:54+0800
     */
    public function Detail()
    {
        return MyView();
    }

    /**
     * 添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 参数
        $params = $this->data_request;

        // 数据
        $data = $this->data_detail;
        if(!empty($data))
        {
            // 权限关联数据
            $params['role_id'] =  $data['id'];
        }

        // 模板数据
        $assign = [];

        // 权限列表
        $power = AdminRoleService::RolePowerEditData($params);
        $assign['power'] = $power;

        // 角色编辑页面钩子
        $hook_name = 'plugins_view_admin_role_save';
        $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'role_id'       => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'power'         => &$power,
            'params'        => &$params,
        ]);

        // 数据/参数
        unset($params['id']);
        $assign['data'] = $data;
        $assign['params'] = $params;

        // 模板赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 添加/编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(AdminRoleService::RoleSave($this->data_request));
    }

    /**
     * 删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(AdminRoleService::RoleDelete($this->data_request));
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return ApiService::ApiDataReturn(AdminRoleService::RoleStatusUpdate($params));
    }
}
?>