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

use app\plugins\allocation\service\PaymentHandleService;

/**
 * 银联分账 - 云闪付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-11-14
 * @desc    description
 */
class ChinaumsAllocationUac
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
            'name'          => '银联分账-云闪付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5', 'ios', 'android'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用微信，分账模式支付方式。 <a href="https://www.chinaums.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'select',
                'title'         => '支付模式',
                'message'       => '请选择支付模式',
                'name'          => 'inst_mid',
                'element_data'  => [
                    ['value'=>'H5DEFAULT', 'name'=>'H5版本'],
                    ['value'=>'APPDEFAULT', 'name'=>'APP版本'],
                ],
            ],
            [
                'element'       => 'message',
                'message'       => '支付参数请在【分账系统】插件中配置',
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
        $ret = $this->IsCall();
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $params['inst_mid'] = $this->InstMidValue();
        return PaymentHandleService::Pay('uac'.($params['inst_mid'] == 'H5DEFAULT' ? '' : '-app'), $params);
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
        $params['inst_mid'] = $this->InstMidValue();
        return PaymentHandleService::Respond($params);
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
        $params['inst_mid'] = $this->InstMidValue();
        return PaymentHandleService::Refund($params);
    }

    /**
     * 机构商户号
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-02-26
     * @desc    description
     */
    public function InstMidValue()
    {
        return empty($this->config['inst_mid']) ? 'H5DEFAULT' : $this->config['inst_mid'];
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
        $service_class = '\app\plugins\allocation\service\PaymentHandleService';
        if(!class_exists($service_class))
        {
            return DataReturn('请先安装【分账系统】插件', -1);
        }
        return DataReturn('success', 0);
    }

    /**
     * 自定义成功返回内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     */
    public function SuccessReturn()
    {
        return 'SUCCESS';
    }

    /**
     * 自定义失败返回内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     */
    public function ErrorReturn()
    {
        return 'FAILED';
    }
}
?>