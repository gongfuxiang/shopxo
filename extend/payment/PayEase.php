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
namespace payment;

/**
 * 首信易支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class PayEase
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
            'name'          => '首信易支付',  // 插件名称
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用PC+H5，致力于打造汇通全球的、领先的国际支付平台，为商家提供更优质、更安全的支付清算服务。<a href="https://www.beijing.com.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'merchantId',
                'placeholder'   => '商户编号',
                'title'         => '商户编号',
                'is_required'   => 0,
                'message'       => '请填写商户编号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'password',
                'placeholder'   => '密码',
                'title'         => '密码',
                'is_required'   => 0,
                'message'       => '请填写密码',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'public_key',
                'placeholder'   => '应用公钥',
                'title'         => '应用公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用公钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'private_key',
                'placeholder'   => '应用私钥',
                'title'         => '应用私钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用私钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'out_rsa_public',
                'placeholder'   => '首信易公钥',
                'title'         => '首信易公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写首信易公钥',
            ],
            [
                'element'       => 'select',
                'placeholder'   => '贸易背景',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>'GOODSTRADE', 'name'=>'货物贸易'],
                    ['value'=>'PLANETICKET', 'name'=>'机票'],
                    ['value'=>'HOTELACCOMMODATIO', 'name'=>'酒店'],
                    ['value'=>'STUDYABROAD', 'name'=>' 留学']
                ],
                'name'          => 'forUse',
                'title'         => '贸易背景',
                'is_required'   => 0,
                'message'       => '请选择贸易背景',
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
        // 参数
        if(empty($params))
        {
            return DataReturn('参数不能为空', -1);
        }
        
        // 配置信息
        if(empty($this->config))
        {
            return DataReturn('支付缺少配置', -1);
        }

        
        return 100;
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
        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        ksort($data);

        // 参数字符串
        $prestr = '';
        foreach($data AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $prestr .= "$key=$val&";
            }
        }
        $prestr = substr($prestr, 0, -1);

        // 签名
        if(!$this->OutRsaVerify($prestr, $data['sign']))
        {
            return DataReturn('签名校验失败', -1);
        }

        // 支付状态
        if(!empty($data['trade_no']) || (isset($data['trade_status']) && in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])))
        {
            return DataReturn('支付成功', 0, $this->ReturnData($data));
        }
        return DataReturn('处理异常错误', -100);
    }
}
?>