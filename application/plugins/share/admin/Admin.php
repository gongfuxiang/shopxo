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
namespace app\plugins\share\admin;

use think\Controller;
use app\service\PluginsService;

/**
 * 分享 - 管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin extends Controller
{
    // 是否开启
    private $share_is_enable_list = [
        0 => ['value' => 0, 'name' => '关闭', 'checked' => true],
        1 => ['value' => 1, 'name' => '开启'],
    ];

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
        $ret = PluginsService::PluginsData('share', ['pic']);
        if($ret['code'] == 0)
        {
            // 是否开启
            $this->assign('share_is_enable_list', $this->share_is_enable_list);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/share/admin/admin/index');
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
        $ret = PluginsService::PluginsData('share', ['pic']);
        if($ret['code'] == 0)
        {
            // 是否开启
            $this->assign('share_is_enable_list', $this->share_is_enable_list);
            
            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/share/admin/admin/saveinfo');
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
        return PluginsService::PluginsDataSave(['plugins'=>'share', 'data'=>$params], ['pic']);
    }
}
?>