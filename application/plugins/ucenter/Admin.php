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
namespace app\plugins\ucenter;

use think\Controller;
use app\service\PluginsService;

/**
 * UCenter - 后台管理
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
        $ret = PluginsService::PluginsData('ucenter');
        if($ret['code'] == 0)
        {
            $ret['data']['login_sync_url'] = empty($ret['data']['login_sync_url']) ? '' : str_replace("\n", '<br />', $ret['data']['login_sync_url']);
            $ret['data']['login_async_url'] = empty($ret['data']['login_async_url']) ? '' : str_replace("\n", '<br />', $ret['data']['login_async_url']);
            $ret['data']['register_sync_url'] = empty($ret['data']['register_sync_url']) ? '' : str_replace("\n", '<br />', $ret['data']['register_sync_url']);
            $ret['data']['register_async_url'] = empty($ret['data']['register_async_url']) ? '' : str_replace("\n", '<br />', $ret['data']['register_async_url']);
            $ret['data']['logout_sync_url'] = empty($ret['data']['logout_sync_url']) ? '' : str_replace("\n", '<br />', $ret['data']['logout_sync_url']);
            $ret['data']['logout_async_url'] = empty($ret['data']['logout_async_url']) ? '' : str_replace("\n", '<br />', $ret['data']['logout_async_url']);
            $ret['data']['loginpwdupdate_async_url'] = empty($ret['data']['loginpwdupdate_async_url']) ? '' : str_replace("\n", '<br />', $ret['data']['loginpwdupdate_async_url']);
            $ret['data']['accounts_async_url'] = empty($ret['data']['accounts_async_url']) ? '' : str_replace("\n", '<br />', $ret['data']['accounts_async_url']);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/ucenter/admin/index');
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
        $ret = PluginsService::PluginsData('ucenter');
        if($ret['code'] == 0)
        {
            // 是否
            $is_whether_list =  [
                0 => array('id' => 0, 'name' => '否', 'checked' => true),
                1 => array('id' => 1, 'name' => '是'),
            ];

            $this->assign('is_whether_list', $is_whether_list);
            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/ucenter/admin/saveinfo');
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
        return PluginsService::PluginsDataSave(['plugins'=>'ucenter', 'data'=>$params]);
    }
}
?>