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
namespace payment;

use app\service\AppMiniUserService;
use app\service\PaymentService;

/**
 * 微信APP小程序支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class WeixinAppMini
{
    // 插件配置参数
    private $config;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]           $params [输入参数（支付配置参数）]
     */
    public function __construct($params = [])
    {
        $this->config = $params;
    }

    /**
     * 配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => '微信APP小程序支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '6.7+',  // 适用系统版本描述
            'apply_terminal'=> ['ios', 'android'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用APP跳转到微信APP小程序收银台支付，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://pay.weixin.qq.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'name'          => 'appid',
                'title'         => '小程序appid',
                'desc'          => '默认取系统配置的微信小程序appid',
                'placeholder'   => '小程序appid',
                'message'       => '请填写小程序appid',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'name'          => 'path',
                'title'         => '小程序页面路径',
                'desc'          => '默认 收银台地址',
                'placeholder'   => '小程序页面路径',
                'message'       => '请填写小程序页面路径',
            ],
            [
                'element'       => 'select',
                'title'         => '打开微信APP小程序方式',
                'message'       => '请选择打开微信APP小程序方式',
                'name'          => 'is_weixinapp_type',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>0, 'name'=>'原生方式'],
                    ['value'=>1, 'name'=>'明文schem方式'],
                ],
            ],
            [
                'element'       => 'select',
                'title'         => '明文schem方式（环境版本）',
                'message'       => '请选择明文schem方式（环境版本）',
                'name'          => 'env_version',
                'element_data'  => [
                    ['value'=>'release', 'name'=>'正式版本'],
                    ['value'=>'trial', 'name'=>'体验版本'],
                    ['value'=>'develop', 'name'=>'开发版本'],
                ],
            ],
        ];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    /**
     * 支付入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Pay($params = [])
    {
        // 配置参数验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '支付单号为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $key = 'cache_payment_system_weixin_mini_cashier_pay_data_'.$params['order_no'];
        // 没有openid则跳转到小程序
        if(empty($params['weixin_openid']))
        {
            // 存储支付数据
            MyCache($key, $params);

            // 支付链接
            $path = empty($this->config['path']) ? 'pages/cashier/cashier' : $this->config['path'];
            $is_weixinapp_type = isset($this->config['is_weixinapp_type']) ? $this->config['is_weixinapp_type'] : 0;
            $query = 'order_no='.$params['order_no'];
            if($is_weixinapp_type == 1)
            {
                // 跳转支付 release 正式版本,  trial 体验版本,  develop 开发版本
                $env_version = empty($this->config['env_version']) ? 'release' : $this->config['env_version'];
                $appid = empty($this->config['appid']) ? AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appid') : $this->config['appid'];
                $url = 'weixin://dl/business/?appid='.$appid.'&path='.$path.'&query='.urlencode($query).'&env_version='.$env_version;
            } else {
                $url = 'weixinapp://'.$path.'?'.$query;
            }
            return DataReturn('success', 0, $url);
        }

        // 获取支付数据
        $cache_res = MyCache($key);
        if(empty($cache_res))
        {
            return DataReturn('支付数据丢失，请重新发起支付！', -1);
        }

        // 调用支付处理
        $cache_res['weixin_openid'] = $params['weixin_openid'];
        $cache_res['is_cashier'] = 1;
        $ret = $this->IsCall();
        if($ret['code'] != 0)
        {
            return $ret;
        }
        return $ret['data']->Pay($cache_res);
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        $ret = $this->IsCall();
        if($ret['code'] != 0)
        {
            return $ret;
        }
        return $ret['data']->Respond($params);
    }

    /**
     * 退款处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Refund($params = [])
    {
        $ret = $this->IsCall();
        if($ret['code'] != 0)
        {
            return $ret;
        }
        return $ret['data']->Refund($params);
    }

    /**
     * 是否可以调用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-11-23
     * @desc    description
     */
    public function IsCall()
    {
        $class_path = '\payment\Weixin';
        if(!class_exists($class_path))
        {
            return DataReturn('请先安装【微信支付】支付插件', -1);
        }
        $payment = PaymentService::PaymentData(['where'=>['payment'=>'Weixin']]);
        $config = (empty($payment) || empty($payment['config'])) ? [] : $payment['config'];
        return DataReturn('success', 0, new $class_path($config));
    }
}
?>