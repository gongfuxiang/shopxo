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
 * 码支付-QQ
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class CodePayQQ
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
            'name'          => '码支付-QQ',  // 插件名称
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用PC+H5，即时到帐支付方式，免费帮助个人实现支付后立即通知。 <a href="https://codepay.fateqq.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'id',
                'placeholder'   => '码支付ID',
                'title'         => '码支付ID',
                'is_required'   => 0,
                'message'       => '请填写码支付ID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '通信密钥',
                'title'         => '通信密钥',
                'is_required'   => 0,
                'message'       => '请填写通信密钥',
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
            'id'            => $this->config['id'],
            'type'          => 2,
            'notify_url'    => $params['notify_url'],
            'return_url'    => $params['call_back_url'],

            /* 业务参数 */
            'pay_id'        => $params['order_no'].GetNumberCode(6),
            'price'         => $params['total_price'],
            'param'         => $params['name']
        );

        $param = $this->GetParamSign($parameter);
        $url = 'http://api2.fateqq.com:52888/creat_order/?'.$param['urls'].'&sign='.md5($param['sign'].$this->config['key']);
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
        reset($params);

        foreach($params AS $key => $val)
        {
            // 跳过这些不参数签名
            if($val == '' || $key == 'sign')
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
        if(empty($params['pay_no']))
        {
            return DataReturn('支付失败', -1);
        }

        // 签名验证
        $param = $this->GetParamSign($params);
        if(md5($param['sign'].$this->config['key']) != $params['sign'])
        {
            return DataReturn('签名错误', -1);
        }

        // 支付状态
        if(isset($params['status']))
        {
            switch($params['status'])
            {
                // 成功
                case 0 :
                    $ret = DataReturn('支付成功', 0, $this->ReturnData($params));
                    break;

                // 失败
                case 1 :
                   $ret = DataReturn('支付失败', -100);
                   break;

                // 参数有误
                case 2 :
                   $ret = DataReturn('支付参数有误', -1001);
                   break;

                // 默认
                default :
                    $ret = DataReturn('支付异常错误', -1002);
            }
        } else {
            $ret = DataReturn('支付异常错误', -1003);
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
        $data['trade_no']       = isset($data['pay_no']) ? $data['pay_no'] : '';            // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['pay_id']) ? $data['pay_id'] : '';                                                  // 支付平台 - 用户
        $data['out_trade_no']   = substr($data['pay_id'], 0, strlen($data['pay_id'])-6);    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['param']) ? $data['param'] : '';              // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['money'];                                           // 本系统发起支付的 - 总价

        return $data;
    }
}
?>