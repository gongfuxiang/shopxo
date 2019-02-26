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
namespace app\plugins\usernotloginhidegoodsprice;

use think\Controller;
use app\service\PluginsService;

/**
 * 未登录隐藏商品价格 - 后台管理
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
        $ret = PluginsService::PluginsData('usernotloginhidegoodsprice');
        if($ret['code'] == 0)
        {
            // 限制终端
            $common_platform_type = lang('common_platform_type');
            $limit_terminal_all = [];
            if(!empty($ret['data']['limit_terminal']))
            {
                foreach(explode(',', $ret['data']['limit_terminal']) as $type)
                {
                    if(isset($common_platform_type[$type]))
                    {
                        $limit_terminal_all[] = $common_platform_type[$type]['name'];
                    }
                }
            }
            $ret['data']['limit_terminal_text'] = implode('，', $limit_terminal_all);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/usernotloginhidegoodsprice/admin/index');
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
        $ret = PluginsService::PluginsData('usernotloginhidegoodsprice');
        if($ret['code'] == 0)
        {
            // 限制终端
            $ret['data']['limit_terminal'] = empty($ret['data']['limit_terminal']) ? [] : explode(',', $ret['data']['limit_terminal']);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/usernotloginhidegoodsprice/admin/saveinfo');
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
        return PluginsService::PluginsDataSave(['plugins'=>'usernotloginhidegoodsprice', 'data'=>$params]);
    }
}
?>