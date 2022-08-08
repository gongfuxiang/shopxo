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
 * 头条
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Toutiao
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
            'name'          => '头条',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['toutiao'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用头条小程序，担保交易支付方式。 <a href="https://microapp.bytedance.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'app_id',
                'placeholder'   => '小程序AppID',
                'title'         => '小程序AppID',
                'is_required'   => 0,
                'message'       => '请填写小程序AppID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'salt',
                'placeholder'   => '密钥SALT',
                'title'         => '密钥SALT',
                'is_required'   => 0,
                'message'       => '请填写密钥SALT',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'token',
                'desc'          => '自行输入英文或数字组合、不超过32个字符',
                'placeholder'   => 'Token(令牌)，自行输入英文或数字组合、不超过32个字符',
                'title'         => 'Token(令牌)',
                'is_required'   => 0,
                'message'       => '请填写Token(令牌)',
            ],
            [
                'element'       => 'message',
                'message'       => '异步通知地址，将该地址配置到头条小程序后台->支付->担保交易->担保交易设置中<br />'.__MY_URL__.'payment_default_order_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php',
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
            'app_id'        => $this->config['app_id'],
            'out_order_no'  => $params['order_no'],
            'total_amount'  => (int) (($params['total_price']*1000)/10),
            'subject'       => $params['name'],
            'body'          => $params['site_name'].'-'.$params['name'],
            'valid_time'    => $this->OrderAutoCloseTime(),
            'notify_url'    => $params['notify_url'],
        ];

        // 签名
        $parameter['sign'] = $this->GetParamSign($parameter);

        // 请求接口
        $url = 'https://developer.toutiao.com/api/apps/ecpay/v1/create_order';
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0 && !empty($ret['data']['data']))
        {
            $ret['data'] = $ret['data']['data'];
        }
        return $ret;
    }
    
    /**
     * 订单自动关闭的时间
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-24
     * @desc    description
     */
    public function OrderAutoCloseTime()
    {
        return intval(MyC('common_order_close_limit_time', 30, true))*60;
    }

    /**
     * 签名生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params       [输入参数]
     */
    private function GetParamSign($params = [])
    {
        $data = [];
        foreach($params as $k=>$v)
        {
            // 排除不参与签名的字段
            if(in_array($k, ['other_settle_params', 'app_id', 'sign', 'thirdparty_id']))
            {
                continue;
            }

            // 数据值处理
            $value = trim(strval($v));
            $len = strlen($value);
            if($len > 1 && substr($value, 0, 1) == '"' && substr($value, $len, $len-1) == '"')
            {
                $value = substr($value, 1, $len-1);
            }

            // 排除空值
            $value = trim($value);
            if($value == '' || $value == null)
            {
                continue;
            }

            // 加入待签
            array_push($data, $value);
        }

        // 加入密钥
        array_push($data, $this->config['salt']);

        // 单元被作为字符串来比较
        sort($data, SORT_STRING);
        return md5(implode('&', $data));
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
        // 参数
        if(empty($this->config))
        {
            return DataReturn('配置有误', -1);
        }
        if(empty($params['msg']) || empty($params['type']))
        {
            return DataReturn('支付参数有误', -1);
        }

        // 退款请求不处理、直接返回成功
        if($params['type'] == 'refund')
        {
            die($this->SuccessReturn());
        }

        // 请求数据
        $data = json_decode(htmlspecialchars_decode($params['msg']), true);
        if(empty($data))
        {
            return DataReturn('msg转换json数据为空', -1);
        }
        if(empty($data['cp_orderno']))
        {
            return DataReturn('回调订单号为空', -1);
        }

        // 查询订单信息
        $parameter = [
            'app_id'        => $this->config['app_id'],
            'out_order_no'  => $data['cp_orderno'],
        ];

        // 签名
        $parameter['sign'] = $this->GetParamSign($parameter);

        // 请求接口
        $url = 'https://developer.toutiao.com/api/apps/ecpay/v1/query_order';
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0 && !empty($ret['data']['payment_info']) && !empty($ret['data']['payment_info']['order_status']))
        {
            switch($ret['data']['payment_info']['order_status'])
            {
                // 处理中
                case 'PROCESSING' :
                    $ret = DataReturn('处理中', -10);
                    break;

                 // 超时
                case 'TIMEOUT' :
                   $ret = DataReturn('超时', -20);
                   break;

                // 成功
                case 'SUCCESS' :
                   $ret = DataReturn('支付成功', 0, $this->ReturnData($data));
                   break;

                // 默认
                default :
                    $ret = DataReturn('支付失败', -100);
            }
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
        $data['trade_no']       = isset($data['payment_order_no']) ? $data['payment_order_no'] : '';  // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['seller_uid']) ? $data['seller_uid'] : '';   // 支付平台 - 用户
        $data['out_trade_no']   = $data['cp_orderno'];   // 本系统发起支付的 - 订单号
        $data['subject']        = '';   // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_amount']/100;   // 本系统发起支付的 - 总价

        return $data;
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
        // 参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '订单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'trade_no',
                'error_msg'         => '交易平台订单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pay_price',
                'error_msg'         => '支付金额不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'refund_price',
                'error_msg'         => '退款金额不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pay_time',
                'error_msg'         => '支付时间不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 处理支付
        $parameter = [
            'app_id'        => $this->config['app_id'],
            'out_order_no'  => $params['order_no'],
            'out_refund_no' => $params['trade_no'],
            'refund_amount' => (int) (($params['refund_price']*1000)/10),
            'reason'        => $refund_reason,
        ];

        // 签名
        $parameter['sign'] = $this->GetParamSign($parameter);

        // 请求接口
        $url = 'https://developer.toutiao.com/api/apps/ecpay/v1/create_refund';
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0)
        {
            // 统一返回格式
            $data = [
                'trade_no'      => isset($ret['data']['refund_no']) ? $ret['data']['refund_no'] : '',
                'refund_price'  => $params['refund_price'],
                'return_params' => $ret['data'],
            ];
            return DataReturn('退款成功', 0, $data);
        }
        return $ret;
    }

    /**
     * 分账
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Settlement($params = [])
    {
        // 参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'out_settle_no',
                'error_msg'         => '分账单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '订单号不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 请求数据
        $parameter = [
            'app_id'        => $this->config['app_id'],
            'out_settle_no' => $params['out_settle_no'],
            'out_order_no'  => $params['order_no'],
            'settle_desc'   => '主动结算',
        ];

        // 签名
        $parameter['sign'] = $this->GetParamSign($parameter);

        // 请求接口
        $url = 'https://developer.toutiao.com/api/apps/ecpay/v1/settle';
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0)
        {
            $data = [
                'data'  => $ret['data'],
            ];
            if(isset($ret['data']['err_no']) && $ret['data']['err_no'] == 0)
            {
                $data['status'] = 0;
                $data['trade_no'] = $ret['data']['settle_no'];
            } else {
                $data['error'] = $ret['data']['err_tips'].'('.$ret['data']['err_no'].')';
            }
            return DataReturn('操作成功', 0, $data);
        }
        return $ret;
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
            if(empty($res) || !isset($res['err_no']))
            {
                return DataReturn('请求失败['.$result.']', -1);
            }
            if($res['err_no'] != 0)
            {
                return DataReturn($res['err_tips'].'['.$res['err_no'].']', -1);
            }
            return DataReturn('success', 0, $res);
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return DataReturn('curl出错，错误码['.$error.']', -1);
        }
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
        return '{"err_no": 0,"err_tips": "success"}';
    }
}
?>