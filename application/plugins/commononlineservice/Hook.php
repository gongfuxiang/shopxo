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
namespace app\plugins\commononlineservice;

use think\Controller;
use app\service\PluginsService;

/**
 * 在线客服 - 钩子入口
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
        // is_control 当前为控制器业务处理
        // hook_name 钩子名称
        if(isset($params['is_control']) && $params['is_control'] === true && !empty($params['hook_name']))
        {
            // 参数一   描述
            // 参数二   0 为处理成功, 负数为失败
            // 参数三   返回数据
            return DataReturn('返回描述', 0);

        // 默认返回视图
        } else {
            if(!empty($params['hook_name']))
            {
                switch($params['hook_name'])
                {
                    case 'plugins_css' :
                        $ret = __MY_ROOT_PUBLIC__.'static/plugins/css/commononlineservice/style.css';
                        break;

                    case 'plugins_js' :
                        $ret = __MY_ROOT_PUBLIC__.'static/plugins/js/commononlineservice/style.js';
                        break;

                    case 'plugins_view_common_bottom' :
                        $ret = $this->html($params);
                        break;

                    default :
                        $ret = '';
                }
                return $ret;
            } else {
                return '';
            }
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
        // 当前模块/控制器/方法
        $module_name = strtolower(request()->module());
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());

        // 获取应用数据
        $ret = PluginsService::PluginsData('commononlineservice', ['images']);
        if($ret['code'] == 0)
        {            
            // 非全局
            if($ret['data']['is_overall'] != 1)
            {
                // 非首页则空
                if($module_name.$controller_name.$action_name != 'indexindexindex')
                {
                    return '';
                }
            }

            // 客服
            $online_service = empty($ret['data']['online_service']) ? [] : explode("\n", $ret['data']['online_service']);
            $online_service_data = [];
            if(!empty($online_service))
            {
                foreach($online_service as $v)
                {
                    $items = explode('|', $v);
                    if(count($items) == 2)
                    {
                        $online_service_data[] = $items;
                    }
                }
            }
            $ret['data']['online_service'] = $online_service_data;

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/commononlineservice/index/content');
        } else {
            return $ret['msg'];
        }
    }
}
?>