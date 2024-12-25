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

use app\service\PayLogService;

/**
 * Csgjs支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Csgjs
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
            'name'          => 'Csgjs',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '熵基内部管理消费系统 <a href="https://www.zkteco.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'sn',
                'placeholder'   => '消费设备SN',
                'title'         => '消费设备SN',
                'is_required'   => 0,
                'message'       => '请填写消费设备SN',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'access_key',
                'placeholder'   => 'AccessKey',
                'title'         => 'AccessKey',
                'is_required'   => 0,
                'message'       => '请填写AccessKey秘钥',
            ],
            [
                'element'       => 'message',
                'message'       => '该支付插件无退款操作，接口均为内部环境使用、请勿外网使用！',
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
        // 配置信息
        if(empty($this->config) || empty($this->config['sn']) || empty($this->config['access_key']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 获取会员卡
        $card = $this->UserCard($params['user']['username']);
        if($card['code'] != 0)
        {
            return $card;
        }

        // 支付参数
        $parameter = [
            'SN'            => $this->config['sn'],
            'sequ'          => GetNumberCode(6),
            'verifytype'    => 5,
            'UserPIN'       => $params['user']['username'],
            'postime'       => date('Y-m-d H:i:s'),
            'posmoney'      => (int) (($params['total_price']*1000)/10),
            'card'          => $card['data'],
            'postype'       => 2,
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求接口
        $ret = RequestGet('http://zkserver.v.csgjs.com/iclock/pos_getrequest?'.http_build_query($parameter));
        $data = [];
        if(!empty($ret))
        {
            $ret = explode("\n", iconv('GB2312', 'UTF-8', $ret));
            if(!empty($ret) && is_array($ret))
            {
                foreach($ret as $v)
                {
                    $temp = explode('=', $v);
                    if(count($temp) == 2)
                    {
                        $data[$temp[0]] = $temp[1];
                    }
                }
            }
        }
        if(empty($data))
        {
            return DataReturn('支付返回结果有误', -1);
        }
        if($data['Ret'] != 'OK')
        {
            return DataReturn($data['errmsg'].'('.$data['retnumber'].')', -1);
        }
        // 加入单号
        $data['out_trade_no'] = $params['order_no'];
        MySession('plugins_csgjs_pay_data', $data);

        // 异步通知
        $join = stripos($params['notify_url'], '?') === false ? '?' : '&';
        CurlGet($params['notify_url'].$join.http_build_query($data));

        // 返回数据
        $join = stripos($params['call_back_url'], '?') === false ? '?' : '&';
        return DataReturn('success', 0, $params['call_back_url'].$join.http_build_query($data));
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
        // 无参数则从session读取
        $params = empty($params) ? MySession('plugins_csgjs_pay_data') : $params;
        if(!empty($params['out_trade_no']))
        {
            $ret = DataReturn('支付成功', 0, $this->ReturnData($params));
        } else {
            $ret = DataReturn('处理异常错误', -100);
        }
        return $ret;
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
        $data['trade_no']       = $data['sequ'];        // 支付平台 - 订单号
        $data['buyer_user']     = $data['username'];              // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = '';         // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['posmoney']/100;      // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * 获取用户会员卡
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-03
     * @desc    description
     * @param   [string]          $username [用户名]
     */
    private function UserCard($username)
    {
        $parameter = [
            'pinlist'   => $username,
            'offduty'   => 0,
        ];
        $ret = $this->HttpRequest('http://zkserver.v.csgjs.com/api/v2/employee/get/?key='.$this->config['access_key'], $parameter);
        if($ret['code'] == 0)
        {
            $res = empty($ret['data']['items']) ? [] : array_column($ret['data']['items'], null, 'pin');
            if(!empty($res) && array_key_exists($username, $res))
            {
                $ret['data'] = $res[$username]['Card'];
            } else {
                $ret = DataReturn('用户无会员卡', -1);
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
     * @param    [string]          $url     [请求地址]
     * @param    [array]           $data    发送数据]
     * @return   [mixed]                    [请求返回数据]
     */
    private function HttpRequest($url, $data)
    {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $headers = [
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Content-Length: '.strlen($body),
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if(curl_errno($ch))
        {
            return DataReturn(curl_error($ch), -1);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return DataReturn('http错误('.$httpStatusCode.')', -1);
            }
        }
        curl_close($ch);
        $res = json_decode($reponse, true);
        if(empty($res) || !isset($res['ret']))
        {
            return DataReturn('返回数据错误('.$reponse.')', -1);
        }
        if($res['ret'] != 0)
        {
            return DataReturn($res['msg'].'('.$res['ret'].')', -1);
        }
        return DataReturn('success', 0, $res['data']);
    }
}
?>