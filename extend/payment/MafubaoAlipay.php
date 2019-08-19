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
 * 码付宝-支付宝
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class MafubaoAlipay
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
            'name'          => '码付宝-支付宝',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用PC+H5，即时到帐支付方式，个人免签实现支付后立即通知。 <a href="http://pay.shopxo.net/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'appid',
                'placeholder'   => 'appid',
                'title'         => 'appid',
                'is_required'   => 0,
                'message'       => '请填写appid',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'appsecret',
                'placeholder'   => 'appsecret',
                'title'         => 'appsecret',
                'is_required'   => 0,
                'message'       => '请填写通信密钥appsecret',
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

        // 处理支付
        $parameter = array(
            'appid'         => $this->config['appid'],
            'channel'       => 'alipay',
            'notify_url'    => $params['notify_url'],
            'redirect_url'  => $params['call_back_url'],
            'trade_type'    => 'sync',

            // 业务参数
            'goodsname'     => $params['name'],
            'out_trade_no'  => $params['order_no'],
            'money'         => $params['total_price'],
        );

        $param = $this->GetParamSign($parameter);
        $url = 'http://payapi.shopxo.net/api/pay/mchCreateOrder/?'.$param['urls'].'&sign='.md5($param['sign'].'&key='.$this->config['appsecret']);
        return DataReturn('处理成功', 0, $url);
    }

    /**
     * [GetParamSign 签名生成]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:28:07+0800
     * @param   [array]           $params [输入参数]
     */
    private function GetParamSign($params = [])
    {
        $sign = '';
        $urls  = '';
        ksort($params);

        foreach($params AS $key => $val)
        {
            // 跳过这些不参数签名
            if($key == 'sign' || $val === '' || $val === null)
            {
                continue;
            }

            //后面追加&拼接URL
            if($sign != '')
            { 
                $sign .= "&";
                $urls .= "&";
            }
            $sign .= "$key=$val"; //拼接为url参数形式
            $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值
        }

        $result = array(
            'urls'  => $urls,
            'sign'  => $sign,
        );
        return $result;
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
        if(empty($this->config))
        {
            return DataReturn('配置有误', -1);
        }
        if(empty($params['trade_no']))
        {
            return DataReturn('支付失败', -1);
        }
        if(empty($params['sign']))
        {
            return DataReturn('签名为空', -1);
        }

        // 签名验证
        $param = $this->GetParamSign($params);
        if(md5($param['sign'].'&key='.$this->config['appsecret']) != $params['sign'])
        {
            return DataReturn('签名错误', -1);
        }

        // 支付状态
        if(isset($params['status']))
        {
            switch($params['status'])
            {
                // 未支付
                case 0 :
                    $ret = DataReturn('未支付', -100);
                    break;

                // 成功
                case 1 :
                   $ret = DataReturn('支付成功', 0, $this->ReturnData($params));
                   break;

                // 支付超时
                case 2 :
                case 3 :
                case 6 :
                   $ret = DataReturn('支付超时', -1001);
                   break;

                // 支付成功，通知失败
                case 4 :
                case 5 :
                   $ret = DataReturn('支付成功，通知失败', -1002);
                   break;

                // 默认
                default :
                    $ret = DataReturn('支付异常错误', -1003);
            }
        } else {
            $ret = DataReturn('支付异常错误', -1004);
        }
        return $ret;
    }

    /**
     * [ReturnData 返回数据统一格式]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-06T16:54:24+0800
     * @param    [array]                   $data [返回数据]
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = isset($data['trade_no']) ? $data['trade_no'] : '';  // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['mid']) ? $data['mid'] : '';   // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];   // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['goodsname']) ? $data['goodsname'] : '';   // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['money_real'];   // 本系统发起支付的 - 总价

        return $data;
    }
}
?>