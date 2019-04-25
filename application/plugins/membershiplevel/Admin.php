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
namespace app\plugins\membershiplevel;

use think\Controller;
use app\plugins\membershiplevel\Service;
use app\service\PluginsService;

/**
 * 会员等级插件 - 管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin extends Controller
{
    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        $ret = PluginsService::PluginsData('membershiplevel', Service::$base_config_attachment_field, false);
        if($ret['code'] == 0)
        {
            // 等级规则
            $this->assign('members_level_rules_list', Service::$members_level_rules_list);
            
            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/membershiplevel/admin/index');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        $ret = PluginsService::PluginsData('membershiplevel', Service::$base_config_attachment_field, false);
        if($ret['code'] == 0)
        {
            // 等级规则
            $this->assign('members_level_rules_list', Service::$members_level_rules_list);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/membershiplevel/admin/saveinfo');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 数据保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        // 会员等级数据
        $level = Service::LevelDataList();
        $params['level_list'] = $level['data'];
        return PluginsService::PluginsDataSave(['plugins'=>'membershiplevel', 'data'=>$params]);
    }
}
?>