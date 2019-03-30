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
namespace app\plugins\commonrightnavigation;

use think\Controller;
use app\service\AnswerService;
use app\service\BuyService;
use app\service\UserService;

/**
 * 右侧快捷导航 - 前端
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    /**
     * 留言
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function answer($params = [])
    {
        $params = input('post.');
        $params['user'] = UserService::LoginUserInfo();
        return AnswerService::AnswerSave($params);
    }

    /**
     * 购物车
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function cart($params = [])
    {
        // 当前模块/控制器/方法
        $module_name = strtolower(request()->module());
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());

        // 当前模块/控制器/方法
        $this->assign('module_controller_action', $module_name.$controller_name.$action_name);

        // 购物车
        $cart_list = BuyService::CartList(['user'=>UserService::LoginUserInfo()]);
    
        // 基础数据
        $base = [
            'total_price'   => empty($cart_list['data']) ? '0.00' : PriceNumberFormat(array_sum(array_column($cart_list['data'], 'total_price'))),
            'cart_count'   => empty($cart_list['data']) ? 0 : count($cart_list['data']),
            'ids'           => empty($cart_list['data']) ? '' : implode(',', array_column($cart_list['data'], 'id')),
        ];
        $data = [
            'cart_list' => $cart_list['data'],
            'base'      => $base,
        ];
        return DataReturn('操作成功', 0, $data);
    }
}
?>