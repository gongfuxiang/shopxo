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
namespace app\plugins\usercentertopnotice;

use think\Controller;
use app\service\PluginsService;

/**
 * 用户中心顶部公告 - 钩子入口
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
        // 是否控制器钩子
        if(isset($params['is_control']) && $params['is_control'] === true && !empty($params['hook_name']))
        {
            return DataReturn('无需处理', 0);

        // 默认返回视图
        } else {
            return $this->html($params);
        }
    }

    /**
     * 视图
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function html($params = [])
    {
        // 获取应用数据
        $ret = PluginsService::PluginsData('usercentertopnotice');
        if($ret['code'] == 0)
        {
            // 内容是否为空
            if(empty($ret['data']['content']))
            {
                return '';
            }
            
            // 有效时间
            if(!empty($ret['data']['time_start']))
            {
                // 是否已开始
                if(strtotime($ret['data']['time_start']) > time())
                {
                    return '';
                }
            }
            if(!empty($ret['data']['time_end']))
            {
                // 是否已结束
                if(strtotime($ret['data']['time_end']) < time())
                {
                    return '';
                }
            }

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/usercentertopnotice/index/content');
        } else {
            return $ret['msg'];
        }
    }
}
?>