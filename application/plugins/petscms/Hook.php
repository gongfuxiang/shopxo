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
namespace app\plugins\petscms;

use think\Controller;
use app\service\PluginsService;

/**
 * 顶部公告 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function run($params = [])
    {
        if(!empty($params['hook_name']))
        {
            switch($params['hook_name'])
            {
                // 用户中心左侧导航
                case 'plugins_service_users_center_left_menu_handle' :
                    $ret = $this->UserCenterLeftMenuHandle($params);
                    break;

                // 顶部小导航右侧-我的业务
                case 'plugins_service_header_navigation_top_right_handle' :
                    $ret = $this->CommonTopNavRightMenuHandle($params);
                    break;

                default :
                    $ret = '';
            }
            return $ret;
        }
    }

    /**
     * 用户中心左侧菜单处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-11
     * @desc    description
     * @param   array           $params [description]
     */
    public function UserCenterLeftMenuHandle($params = [])
    {
        $menu = [[
            'name'      =>  '宠物管理',
            'is_show'   =>  1,
            'icon'      =>  'am-icon-drupal',
            'item'      =>  [
                [
                    'name'      =>  '我的宠物',
                    'url'       =>  PluginsHomeUrl('petscms', 'pets', 'index'),
                    'contains'  =>  ['petsindex', 'petssaveinfo', 'petshelp'],
                    'is_show'   =>  1,
                    'icon'      =>  'am-icon-github-alt',
                ],
            ]
        ]];
        array_splice($params['data'], 2, 0, $menu);
    }

    /**
     * 顶部小导航右侧-我的业务
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-11
     * @desc    description
     * @param   array           $params [description]
     */
    public function CommonTopNavRightMenuHandle($params = [])
    {
        $menu = [
            'name'  => '我的宠物',
            'url'   => PluginsHomeUrl('petscms', 'pets', 'index'),
        ];
        array_push($params['data'][1]['items'], $menu);
    }
}
?>