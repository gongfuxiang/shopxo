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
namespace app\plugins\homemiddleadv;

use think\Controller;
use app\plugins\homemiddleadv\Service;
/**
 * 首页中间广告插件 - 钩子入口
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
                // 楼层数据上面
                case 'plugins_view_home_floor_top' :
                    $ret = $this->HomeFloorTopAdv($params);
                    break;
                default :
                    $ret = '';
            }
            return $ret;
        }
    }

    /**
     * 首页楼层顶部广告
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function HomeFloorTopAdv($params = [])
    {
        $ret = Service::DataList();
        if($ret['code'] == 0 && !empty($ret['data']))
        {
            $this->assign('data_list', $ret['data']);
            return $this->fetch('../../../plugins/view/homemiddleadv/index/content');
        }
        return '';
    }
}
?>