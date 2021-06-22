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
 * 星链-微信
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class SliWeixin
{
    // 插件配置参数
    private $config;

    //支付方式（1=wechat、2=alipay、4=个人银行）
    private $pay_type = 4;

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
            'name'          => '星链-微信',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用PC+H5，即时到帐支付方式，跨境支付。 <a href="https://www.sli.money/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'accounts',
                'placeholder'   => '登录帐号',
                'title'         => '登录帐号',
                'is_required'   => 0,
                'message'       => '请填写登录帐号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'secret',
                'placeholder'   => '密钥',
                'title'         => '密钥',
                'is_required'   => 0,
                'message'       => '请填写密钥',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => 'CNY',
                'name'          => 'currency',
                'placeholder'   => '货币',
                'title'         => '货币',
                'is_required'   => 0,
                'message'       => '请填写货币',
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
        $parameter = [
            'login'         => $this->config['accounts'],
            'orderno'       => $params['order_no'],
            'paymode'       => $this->pay_type,
            'currency'      => $this->config['currency'],
            'amount'        => (int) (($params['total_price']*1000)/10),
            'product'       => $params['name'],
            'ip'            => GetClientIP(),
            'notifyurl'     => $params['notify_url'],
            'callbackurl'   => $params['redirect_url'],
            'rejecturl'     => $params['call_back_url'],
        ];

        // 签名
        $parameter['md5check'] = $this->GetParamSign($parameter);

        $url = 'https://www.sli.money/api/main/v1/payment/request';
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0)
        {
            return DataReturn('success', 0, $ret['data']['payurl']);
        }
        return $ret;
    }
    
    /**
     * 签名生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params       [输入参数]
     * @param   [boolean]         $is_ksort     [是否需要排序]
     * @param   [string]          $key_field    [密钥字段key]
     */
    private function GetParamSign($params = [], $is_ksort = false, $key_field = 'secret')
    {
        $string = '';
        if($is_ksort)
        {
            ksort($params);
        }
        foreach($params AS $key=>$val)
        {
            if(!in_array($key, ['sign']))
            {
                $string .= $key.'='.$val.'&';
            }
        }

        return md5($string.$key_field.'='.$this->config['secret']);
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
        if(empty($params['refno']))
        {
            return DataReturn('支付失败', -1);
        }
        if(empty($params['sign']))
        {
            return DataReturn('签名为空', -1);
        }

        // 签名验证
        $sign = $this->GetParamSign($params, true, 'key');
        if($sign != $params['sign'])
        {
            return DataReturn('签名错误', -1);
        }

        // 支付状态
        if(isset($params['errcode']))
        {
            switch($params['errcode'])
            {
                // 收款方报故障单
                case 9040 :
                    $ret = DataReturn('收款平台故障', -10);
                    break;

                 // 交易取消
                case 9099 :
                   $ret = DataReturn('交易取消', -20);
                   break;

                // 成功
                case 9000 :
                case 9044 :
                case 9046 :
                   $ret = DataReturn('支付成功', 0, $this->ReturnData($params));
                   break;

                // 默认
                default :
                    $ret = DataReturn('支付错误['.$params['errmsg'].']', -100);
            }
        } else {
            $ret = DataReturn('支付异常错误', -1000);
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
        $data['trade_no']       = isset($data['refno']) ? $data['refno'] : '';  // 支付平台 - 订单号
        $data['buyer_user']     = '';   // 支付平台 - 用户
        $data['out_trade_no']   = $data['orderno'];   // 本系统发起支付的 - 订单号
        $data['subject']        = '';   // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['amount']/100;   // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * 网络请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url         [请求url]
     * @param    [array]           $data        [发送数据]
     * @param    [int]             $second      [超时]
     * @return   [mixed]                        [请求返回数据]
     */
    private function HttpRequest($url, $data, $second = 30)
    {
        $ch = curl_init();
        $header = ['Content-Type: application/json;charset=utf-8'];
        curl_setopt_array($ch, array(
            CURLOPT_URL                => $url,
            CURLOPT_HTTPHEADER         => $header,
            CURLOPT_POST               => true,
            CURLOPT_SSL_VERIFYPEER     => false,
            CURLOPT_SSL_VERIFYHOST     => false,
            CURLOPT_RETURNTRANSFER     => true,
            CURLOPT_POSTFIELDS         => json_encode($data),
            CURLOPT_TIMEOUT            => $second,
        ));
        $result = curl_exec($ch);

        //返回结果
        if($result)
        {
            curl_close($ch);
            $res = json_decode($result, true);
            if(empty($res) || empty($res['data']) || empty($res['data']['errcode']))
            {
                return DataReturn('请求失败['.$result.']', -1);
            }
            if($res['data']['errcode'] != 200)
            {
                return DataReturn($this->ErrorCodeToMsg($res['data']['errcode'], $res['data']['errmsg']), -1);
            }
            return DataReturn('success', 0, $res['data']);
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return DataReturn('curl出错，错误码['.$error.']', -1);
        }
    }

    /**
     * 错误处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-08
     * @desc    description
     * @param   [string]          $code [错误码]
     * @param   [string]          $msg  [错误信息]
     */
    public function ErrorCodeToMsg($code, $msg)
    {
        $data = [
            '4001' => '第三方登录信息不能为空',
            '4002' => '第三方登录信息不存在',
            '4003' => '第三方订单号信息不能为空',
            '4004' => '第三方订单号不能重复',
            '4005' => '第三方订单支付方式不能为空',
            '4006' => '第三方订单支付方式应该为1-5 ',
            '4007' => '第三方订单支付金额不能为空',
            '4008' => '第三方订单支付金额应该大于等于0 第三方订单币种汇率不能为空',
            '4010' => '第三方订单md5check不能为空',
            '4011' => '第三方订单callbackurl不能为空',
            '4012' => '第三方订单notifyurl不能为空',
            '4013' => '第三方订单rejecturl不能为空',
            '4014' => '第三方登录帐号已经关闭',
            '4015' => '没有该订单号',
            '4016' => '第三方订单号不能大於30位文字',
            '4020' => '支付金额大于流量，请先买流量',
            '4021' => '支付金额大于最大限额，请把支付金额调小',
            '4021' => '请把支付金额调小',
            '4023' => '不能用这种支付方式，请找客服确认无效IP地址',
            '4030' => '你没有当前货币的权限，请找客服确认',
            '4040' => 'md5check错误',
            '4041' => '不允许HTTPGET方法',
            '4133' => '账户余额不足',
        ];
        return (array_key_exists($code, $data) ? $data[$code] : $msg).'['.$code.']';
    }
}
?>