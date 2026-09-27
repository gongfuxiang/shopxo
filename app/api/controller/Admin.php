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
namespace app\api\controller;

use app\service\ApiService;
use app\service\ResourcesService;

/**
 * 后台管理员 API 入口（仅转接，不定义业务）
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-09-18
 * @desc    对齐 plugins/chat/api/Seller.php：business_control / business_action → admin 子目录
 */
class Admin extends Common
{
    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 映射入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @desc    api.php?s=admin/index&business_control=admin&business_action=login
     */
    public function Index()
    {
        $params = $this->data_request;
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'business_control',
                'error_msg'         => '业务控制器为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'business_action',
                'error_msg'         => '业务方法为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return ApiService::ApiDataReturn(DataReturn($ret, -5000));
        }

        $control = ResourcesService::BusinessCallNameSafe($params['business_control']);
        $action = ResourcesService::BusinessCallNameSafe($params['business_action']);
        if($control === '' || $action === '')
        {
            return ApiService::ApiDataReturn(DataReturn('业务控制器或方法无效', -5000));
        }

        $class = '\\app\\api\\controller\\admin\\'.ucfirst($control);
        if(!class_exists($class))
        {
            return ApiService::ApiDataReturn(DataReturn('业务控制器未定义['.$control.']', -1));
        }
        $action = ucfirst($action);
        $obj = new $class([
            'data_request'     => $params,
            'business_control' => $control,
            'business_action'  => $action,
        ]);
        if(!method_exists($obj, $action))
        {
            return ApiService::ApiDataReturn(DataReturn('业务方法未定义['.$action.']', -1));
        }
        return ApiService::ApiDataReturn($obj->$action($params));
    }
}
?>
