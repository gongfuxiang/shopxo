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

use app\service\PayLogService;

/**
 * 快手
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Kuaishou
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
            'name'          => '快手',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['kuaishou'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用快手小程序，担保交易支付方式。 <a href="https://mp.kuaishou.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'name'          => 'app_secret',
                'placeholder'   => '小程序AppSecret',
                'title'         => '小程序AppSecret',
                'is_required'   => 0,
                'message'       => '请填写小程序AppSecret',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'type',
                'placeholder'   => '商品类型、担保支付商品类目编号',
                'title'         => '商品类型',
                'is_required'   => 0,
                'message'       => '请填写商品类型',
            ],
            [
                'element'       => 'message',
                'message'       => '将当前网站域名配置到快手小程序后台->权限管理->支付设置->支付回调域名中、网站必须是https请求访问',
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

        // 获取access_token
        $config = [
            'appid'     => $this->config['app_id'],
            'secret'    => $this->config['app_secret'],
        ];
        $access_token = (new \base\Kuaishou($config))->GetMiniAccessToken($params);
        if($access_token['code'] != 0)
        {
            return $access_token;
        }

        // 处理支付
        $parameter = [
            'out_order_no'  => $params['order_no'],
            'open_id'       => $params['user']['kuaishou_openid'],
            'total_amount'  => (int) (($params['total_price']*1000)/10),
            'subject'       => $params['name'],
            'detail'        => $params['site_name'].'-'.$params['name'],
            'type'          => $this->config['type'],
            'expire_time'   => $this->OrderAutoCloseTime(),
            'notify_url'    => $params['notify_url'],
        ];

        // 签名
        $parameter['sign'] = $this->GetParamSign(array_merge($parameter, ['app_id'=>$this->config['app_id']]));

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求接口
        $url = 'https://open.kuaishou.com/openapi/mp/developer/epay/create_order?app_id='.$this->config['app_id'].'&access_token='.$access_token['data'];
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0 && !empty($ret['data']['order_info']))
        {
            $ret['data'] = $ret['data']['order_info'];
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
        ksort($params);
        $prestr = '';
        foreach($params as $key=>$val)
        {
            if(!in_array($key, ['sign', 'access_token']))
            {
                $prestr .= "$key=$val&";
            }
        }
        $prestr = substr($prestr, 0, -1);
        return md5($prestr.$this->config['app_secret']);
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
        // 签名
        if(empty($_SERVER['HTTP_KWAISIGN']))
        {
            return DataReturn('授权签名为空', -1);
        }
        $body = file_get_contents('php://input');
        if(md5($body.$this->config['app_secret']) != $_SERVER['HTTP_KWAISIGN'])
        {
            return DataReturn('签名验证失败', -1);
        }
        if(empty($params['data']))
        {
            return DataReturn('支付数据为空', -1);
        }
        return DataReturn('支付成功', 0, $this->ReturnData($params['data']));
    }

    /**
     * 返回数据统一格式
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-06T16:54:24+0800
     * @param    [array]                   $data [返回数据]
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = isset($data['ks_order_no']) ? $data['ks_order_no'] : '';  // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['channel']) ? $data['channel'] : '';   // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_order_no'];   // 本系统发起支付的 - 订单号
        $data['subject']        = $data['trade_no'];   // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['order_amount']/100;   // 本系统发起支付的 - 总价

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

        // 获取access_token
        $config = [
            'appid'     => $this->config['app_id'],
            'secret'    => $this->config['app_secret'],
        ];
        $access_token = (new \base\Kuaishou($config))->GetMiniAccessToken($params);
        if($access_token['code'] != 0)
        {
            return $access_token;
        }

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 处理支付
        $parameter = [
            'app_id'        => $this->config['app_id'],
            'out_order_no'  => $params['order_no'],
            'out_refund_no' => md5(time().GetNumberCode(6)),
            'refund_amount' => (int) (($params['refund_price']*1000)/10),
            'reason'        => $refund_reason,
            'notify_url'    => __MY_URL__,
        ];

        // 签名
        $parameter['sign'] = $this->GetParamSign(array_merge($parameter, ['app_id'=>$this->config['app_id']]));

        // 请求接口
        $url = 'https://open.kuaishou.com/openapi/mp/developer/epay/apply_refund?app_id='.$this->config['app_id'].'&access_token='.$access_token['data'];
        $ret = $this->HttpRequest($url, $parameter);
        if($ret['code'] == 0 && !empty($ret['data']))
        {
            // 统一返回格式
            $data = [
                'trade_no'        => $ret['data']['refund_no'],
                'refund_price'    => $params['refund_price'],
                'return_params'   => $ret['data'],
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        } else {
            // 是否退款中、或退款完成
            if(stripos($ret['msg'], '10000687') !== false || stripos($ret['msg'], '10000607') !== false)
            {
                // 统一返回格式
                $data = [
                    'trade_no'        => '',
                    'refund_price'    => $params['refund_price'],
                    'return_params'   => $ret['msg'],
                    'request_params'  => $parameter,
                ];
                return DataReturn('退款成功', 0, $data);
            }
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
            if(empty($res) || !isset($res['result']))
            {
                return DataReturn('请求失败['.$result.']', -1);
            }
            if($res['result'] != 1)
            {
                return DataReturn($res['error_msg'].'('.$res['result'].')', -1);
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
        return '{"result": 1,"message_id": "'.MyInput('message_id').'"}';
    }
}
?>