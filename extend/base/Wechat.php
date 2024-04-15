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
namespace base;

/**
 * 微信驱动
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-28
 * @desc    支持所有文件存储到硬盘
 */
class Wechat
{
    // appid
    private $_appid;

    // appsecret
    private $_appsecret;

    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:04:05+0800
     * @param   [string]     $app_id         [应用appid]
     * @param   [string]     $app_secret     [应用密钥]
     */
    public function __construct($app_id, $app_secret)
    {
        $this->_appid       = $app_id;
        $this->_appsecret   = $app_secret;
    }

    /**
     * 小程序发送订阅消息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-06
     * @desc    description
     * @param    [string]  $params['page']      [页面地址]
     * @param    [string]  $params['scene']     [参数]
     * @return   [string]                       [成功返回文件流, 失败则空]
     */
    public function MiniSubscribeMessage($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'touser',
                'error_msg'         => MyLang('common_extend.base.wechat.touser_openid_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'template_id',
                'error_msg'         => MyLang('common_extend.base.wechat.template_id_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'data',
                'error_msg'         => MyLang('common_extend.base.wechat.data_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 发送订阅消息
        $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token='.$access_token;
        $data = [
            'touser'      => $params['touser'],
            'template_id' => $params['template_id'],
            'data'        => $params['data'],
        ];
        
        // 跳转页面，可以不传 仅限本小程序内的页面。支持带参数,（示例index?foo=bar）
        if(isset($params['page']) && !empty($params['page'])){
            $data['page'] = $params['page'];
        }
        
        // 跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
        if(isset($params['miniprogram_state']) && !empty($params['miniprogram_state'])){
            $data['miniprogram_state'] = $params['miniprogram_state'];
        }
        
        // 进入小程序查看的语言类型，支持zh_CN(简中)、en_US(英文)、zh_HK(繁中)、zh_TW(繁中)，默认为zh_CN
        if(isset($params['lang']) && !empty($params['lang'])){
            $data['lang'] = $params['lang'];
        }
        
        $res = $this->HttpRequestPost($url, $data, false);
        if(!empty($res))
        {
            if(stripos($res, 'errcode') === false)
            {
                return DataReturn(MyLang('send_success'), 0, $res);
            }
            $res = json_decode($res, true);
            $msg = isset($res['errmsg']) ? $res['errmsg'] : MyLang('send_fail');
            if($msg == 'ok'){
                return DataReturn(MyLang('send_success'), 0, $res);
            }else{
                $msg = isset($res['errcode']) ? $res['errcode'].':'.$msg : $msg;
            }
        } else {
            $msg = MyLang('send_fail').'2';
        }
        return DataReturn($msg, -1);
    }

    /**
     * 小程序发货信息录入
     * @author    Shon Wu
     * @blog      https://github.com/mantoufan/
     * @version   1.0.0
     * @date      2023-08-21
     * @desc      description
     * @param     [string]    $params['order_model']      [订单模式]
     * @param     [string]    $params['trade_no']         [支付平台交易号]
     * @param     [string]    $params['buyer_user']       [支付平台用户账号]
     * @param     [string]    $params['goods_title']      [商品标题]
     * @optional  [string]    $params['express_name']     [快递公司名称]
     * @optional  [string]    $params['express_number']   [快递单号]
     * @optional  [string]    $params['tel']              [收货联系人手机号]
     * @return    [array|null]                            [成功原样返回微信小程序接口响应, 失败则空]
     */
    public function MiniUploadShippingInfo($params = []) 
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'trade_no',
                'error_msg'         => MyLang('common_extend.base.wechat.trade_no_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'buyer_user',
                'error_msg'         => MyLang('common_extend.base.wechat.buyer_user_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_title',
                'error_msg'         => MyLang('common_extend.base.wechat.goods_title_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 是否已开通发货管理
        $key = 'wechat_is_trade_managed_'.$this->_appid;
        $trade_managed = MyCache($key);
        if(empty($trade_managed))
        {
            $url = 'https://api.weixin.qq.com/wxa/sec/order/is_trade_managed?access_token='.$access_token;
            $trade_managed = $this->HttpRequestPost($url, ['appid'=>$this->_appid], true);
            MyCache($key, $trade_managed);
        }
        if(empty($trade_managed) || !isset($trade_managed['errcode']) || $trade_managed['errcode'] != 0 || !isset($trade_managed['is_trade_managed']) || $trade_managed['is_trade_managed'] != 1)
        {
            return DataReturn(MyLang('common_extend.base.common.not_opened_trade_managed_msg'), 0);
        }

        // 订单模式类型
        $order_model_list = [
            // 商城订单模式：销售型（快递 / 快递 + 自提） <-> 微信小程序：实体物流配送采用快递公司进行实体物流配送形式
            0 => 1,
            // 商城订单模式：展示型 <-> 微信小程序：终止，不抛出错误
            1 => null,
            // 商城订单模式：自提型 <-> 微信小程序：用户自提
            2 => 4,
            // 商城订单模式：虚拟销售 <-> 微信小程序：虚拟商品
            3 => 3,
        ];
        $logistics_type = array_key_exists($params['order_model'], $order_model_list) ? $order_model_list[$params['order_model']] : null;
        // 展示模式表示为无效的订单，不处理
        if($logistics_type === null)
        {
            return DataReturn(MyLang('common_extend.base.wechat.no_match_logistics_mode'), 0);
        }

        // 商城参数转换为微信小程序参数
        if(!is_array($params['goods_title']))
        {
            $params['goods_title'] = explode(',', $params['goods_title']);
        }
        $shipping_list = array_map(function($item)
        {
            return ['item_desc'=>$item];
        }, $params['goods_title']);

        // 物流发货匹配快递信息
        if($logistics_type === 1)
        {
            // 当商城为销售型时，传入快递公司编码和快递单号，传入收件人和发件人手机号供顺丰使用
            $express_res = $this->GetMiniDeliveryIdByName($params['express_name']);
            if($express_res['code'] == 0) 
            {
                $consignor_tel = empty($params['consignor_tel']) ? '' : substr($params['consignor_tel'], 0, 3).'****'.substr($params['consignor_tel'], -4);
                $receiver_tel = empty($params['receiver_tel']) ? '' : substr($params['receiver_tel'], 0, 3).'****'.substr($params['receiver_tel'], -4);
                foreach($shipping_list as &$v)
                {
                    $v['express_company'] = $express_res['data'];
                    $v['tracking_no'] = $params['express_number'];
                    if(!empty($consignor_tel) || !empty($receiver_tel))
                    {
                        $v['contact'] = [
                            'consignor_contact'  => $consignor_tel,
                            'receiver_contact'   => $receiver_tel,
                        ];
                    }
                }
            } else {
                // 没有匹配到快递则使用同城类型
                $logistics_type = 2;
            }
        }

        // 非快递模式物流信息只能为一项
        if($logistics_type != 1 && count($shipping_list) > 1)
        {
            $shipping_list = [$shipping_list[0]];
        }

        // 请求参数
        $data = [
            'order_key'         => [
                // 使用微信支付单号
                'order_number_type'  => 2, 
                // 原支付交易对应的微信订单号
                'transaction_id'     => $params['trade_no'],
            ],
            // 发货模式：1 统一发货，2 分拆发货
            'delivery_mode'     => (count($shipping_list) == 1) ? 1 : 2,
            // 物流信息列表
            'shipping_list'     => $shipping_list,
            // 分拆发货模式时、是否已全部发货 true | false
            'is_all_delivered'  => true,
            // 物流模式，发货方式枚举值：1、实体物流配送采用快递公司进行实体物流配送形式 2、同城配送 3、虚拟商品，虚拟商品，例如话费充值，点卡等，无实体配送形式 4、用户自提
            'logistics_type'    => $logistics_type,
            // 支付者，支付者信息
            'payer'             => ['openid' => $params['buyer_user']],
            // 上传时间
            'upload_time'       => date('Y-m-d\TH:i:sP'),
        ];

        // 录入发货信息接口
        $url = 'https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info?access_token='.$access_token;
        $res = $this->HttpRequestPost($url, $data, true);
        $code = isset($res['errcode']) ? $res['errcode'] : '';
        $msg = isset($res['errmsg']) ? $res['errmsg'] : MyLang('send_fail');
        // 这几种情况则视为正常，不影响业务
        // 10060001 支付单不存在
        // 10060002 支付单已完成发货
        // 10060003 无法继续发货
        // 10060023 发货信息未更新
        // 10060004 支付单处于不可发货的状态
        if(in_array($code, [10060001, 10060002, 10060003, 10060023, 10060004]) || $code == 0 && $msg == 'ok')
        {
            return DataReturn(MyLang('send_success'), 0, $res); 
        } else {
            $msg = ($code !== '') ? $code.':'.$msg : $msg;
        }
        return DataReturn($msg, -1);
    }

    /**
     * 获取微信小程序中的快递公司编码：根据快递公司名称
     * @author  Shon Wu
     * @blog    https://github.com/mantoufan/
     * @version 1.0.0
     * @date    2023-08-21
     * @desc    description
     * @param   [string]  $express_name      [快递公司名称]
     * @return  [string|null]                [成功返回快递公司编码, 失败则空]
     */
    function GetMiniDeliveryIdByName($express_name)
    {
        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 获取运力id列表接口
        $url = 'https://api.weixin.qq.com/cgi-bin/express/delivery/open_msg/get_delivery_list?access_token='.$access_token;
        // 需要 POST {} 给接口
        $res = $this->HttpRequestPost($url, (object)[], true);
        $msg = isset($res['errmsg']) ? $res['errmsg'] : MyLang('get_fail');
        if(isset($res['errcode']) && $res['errcode'] == 0) 
        {
            $delivery_list = $res['delivery_list'];
            $delivery_names = array_column($delivery_list, 'delivery_name');
            $delivery_index = array_search($express_name, $delivery_names);
            if($delivery_index === false)
            {
                // 精确匹配失败后，如果公司名称包含快递、物流、速运、速递和邮政，进行模糊匹配。例如：顺丰快递 - 顺丰速运，韵达快递 - 韵达速递 
                // 关键词的笛卡尔积，例如：顺丰快递未匹配，则依次查找顺丰速运、顺丰速递、顺丰物流、顺丰，直到找到一个匹配编码为止
                $to_be_replaceds = [ 
                    '快递' => ['速运', '速递', '物流', ''],
                    '物流' => ['快递', '速运', '速递', ''],
                    '速运' => ['快递', '速递', '物流', ''],
                    '速递' => ['快递', '速运', '物流', ''], // EMS速递 - EMS
                    '邮政' => ['邮政快递', '国际邮政'], // 邮政包裹 - 邮政快递包裹 / 国际邮政包裹
                ];
                foreach($to_be_replaceds as $pattern => $replaceds) 
                {
                    if(stripos($express_name, $pattern) !== false) {
                        foreach($replaceds as $replaced) 
                        {
                            $express_name_replaced = str_replace($pattern, $replaced, $express_name);
                            $delivery_index = array_search($express_name_replaced, $delivery_names);
                            if($delivery_index !== false)
                            {
                                break 2;
                            }
                        }
                        break;
                    } 
                }
            }
            if($delivery_index !== false)
            {
                return DataReturn(MyLang('get_success'), 0, $delivery_list[$delivery_index]['delivery_id']);
            }
            // 无匹配物流公司编码
            $msg = MyLang('common_extend.base.wechat.no_match_logistics_company_code');
        } else {
            $msg = isset($res['errcode']) ? $res['errcode'].':'.$msg : $msg;
        }
        return DataReturn($msg, -1);
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:20:53+0800
     * @param    [string]  $encrypted_data     [加密的用户数据]
     * @param    [string]  $iv                 [与用户数据一同返回的初始向量]
     * @param    [string]  $openid             [解密后的原文]
     * @return   [array|string]                [成功返回用户信息数组, 失败返回错误信息]
     */
    public function DecryptData($encrypted_data, $iv, $openid)
    {
        // 登录授权session
        $login_key = 'wechat_user_login_'.$openid;
        $session_data = MyCache($login_key);
        if(empty($session_data))
        {
            return DataReturn(MyLang('common_extend.base.common.session_key_empty_tips'), -1);
        }

        // iv长度
        if(strlen($iv) != 24)
        {
            return DataReturn(MyLang('common_extend.base.common.iv_error_tips'), -1);
        }

        // 加密函数
        if(!function_exists('openssl_decrypt'))
        {
            return DataReturn(MyLang('openssl_no_support_tips'), -1);
        }

        $result = openssl_decrypt(base64_decode($encrypted_data), "AES-128-CBC", base64_decode($session_data['session_key']), 1, base64_decode($iv));
        $data = json_decode($result, true);
        if($data == NULL)
        {
            return DataReturn(MyLang('common_extend.base.common.please_try_again_tips'), -1);
        }
        if($data['watermark']['appid'] != $this->_appid)
        {
            return DataReturn(MyLang('appid_mismatch_tips'), -1);
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function GetAuthSessionKey($params = [])
    {
        if(empty($params['authcode']))
        {
            return DataReturn(MyLang('common_extend.base.common.auth_code_empty_tips'), -1);
        }

        // 请求获取session_key
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->_appid.'&secret='.$this->_appsecret.'&js_code='.$params['authcode'].'&grant_type=authorization_code';
        $result = $this->HttpRequestGet($url);
        if(empty($result))
        {
            return DataReturn(MyLang('common_extend.base.common.auth_api_request_fail_tips'), -1);
        }
        if(!empty($result['openid']))
        {
            // 缓存SessionKey
            $key = 'wechat_user_login_'.$result['openid'];

            // 缓存存储
            MyCache($key, $result);
            return DataReturn(MyLang('auth_success'), 0, $result);
        }
        $msg = empty($result['errmsg']) ? MyLang('common_extend.base.common.auth_api_request_error_tips') : $result['errmsg'];
        return DataReturn($msg, -1);
    }

    /**
     * 二维码创建
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-02T19:53:10+0800
     * @param    [string]  $params['page']      [页面地址]
     * @param    [string]  $params['scene']     [参数]
     * @return   [string]                       [成功返回文件流, 失败则空]
     */
    public function MiniQrCodeCreate($params)
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'page',
                'error_msg'         => MyLang('common_extend.base.common.page_empty_tips'),
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '1,32',
                'key_name'          => 'scene',
                'error_msg'         => MyLang('common_extend.base.common.scene_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 获取二维码
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token;
        $data = [
            'page'  => $params['page'],
            'scene' => $params['scene'],
            'width' => empty($params['width']) ? 1000 : intval($params['width']),
        ];
        $res = $this->HttpRequestPost($url, $data, false);
        if(!empty($res))
        {
            if(stripos($res, 'errcode') === false)
            {
                return DataReturn(MyLang('get_success'), 0, $res);
            }
            $res = json_decode($res, true);
            $msg = isset($res['errmsg']) ? $res['errmsg'] : MyLang('common_extend.base.common.get_qrcode_fail_tips');
        } else {
            $msg = MyLang('common_extend.base.common.get_qrcode_fail_tips');
        }
        return DataReturn($msg, -1);
    }

    /**
     * 获取用户手机号码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-26
     * @desc    description
     * @param   [string]          $code  [临时code]
     */
    public function GetUserPhoneNumber($code)
    {
        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 获取手机
        $url = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token='.$access_token;
        $data = [
            'code'  => $code,
        ];
        $res = $this->HttpRequestPost($url, $data);
        if(!empty($res))
        {
            if(isset($res['errcode']) && $res['errcode'] == 0 && !empty($res['phone_info']))
            {
                return DataReturn('success', 0, $res['phone_info']['purePhoneNumber']);
            }
            return DataReturn($res['errmsg'].'('.$res['errcode'].')', -1);
        }
        return DataReturn(MyLang('api_request_fail_tips'), -1);
    }

    /**
     * 小程序获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     */
    public function GetMiniAccessToken()
    {
        return $this->GetAccessToken();
    }

    /**
     * 获取微信环境签名配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function GetSignPackage($params = [])
    {
        $access_token = $this->GetAccessToken();
        if(!empty($access_token))
        {
            // 获取 ticket
            $ticket = $this->GetTicket($access_token);

            // 注意 URL 一定要动态获取，不能 hardcode.
            $url = empty($params['url']) ? __MY_VIEW_URL__ : urldecode(htmlspecialchars_decode($params['url']));

            $timestamp = time();
            $nonce_str = $this->CreateNonceStr();

            // 这里参数的顺序要按照 key 值 ASCII 码升序排序
            $string = "jsapi_ticket={$ticket}&noncestr={$nonce_str}&timestamp={$timestamp}&url={$url}";
            return [
              'appId'     => $this->_appid,
              'nonceStr'  => $nonce_str,
              'timestamp' => $timestamp,
              'url'       => $url,
              'signature' => sha1($string),
              'rawString' => $string
            ];
        }
        return [];
    }

    /**
     * 签名随机字符串创建
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     * @param   [int]         $length [长度]
     */
    public function CreateNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 公共获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     */
    public function GetAccessToken()
    {
        // 缓存key
        $key = $this->_appid.'_access_token';
        $result = MyCache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['access_token'];
            }
        }

        // 网络请求
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_appsecret;
        $result = $this->HttpRequestGet($url);
        if(!empty($result) && !empty($result['access_token']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            MyCache($key, $result);
            return $result['access_token'];
        }
        return false;
    }

    /**
     * 获取授权页ticket
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     * @param   [string]          $access_token [access_token]
     * @param   [string]          $type [类型(默认jsapi)]
     */
    public function GetTicket($access_token, $type = 'jsapi')
    {
        // 缓存key
        $key = $this->_appid.'_get_ticket';
        $result = MyCache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['ticket'];
            }
        }

        // 网络请求
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type='.$type;
        $result = $this->HttpRequestGet($url);
        if(!empty($result) && !empty($result['ticket']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            MyCache($key, $result);
            return $result['ticket'];
        }
        return false;
    }

    /**
     * get请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-03T19:21:38+0800
     * @param    [string]           $url [url地址]
     * @return   [array]                 [返回数据]
     */
    public function HttpRequestGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res, true);
    }

    /**
     * curl模拟post
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]   $url        [请求地址]
     * @param    [array]    $data       [发送的post数据]
     * @param    [array]    $is_parsing [是否需要解析数据]
     * @return   [array]                [返回的数据]
     */
    public function HttpRequestPost($url, $data, $is_parsing = true)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($curl, CURLOPT_POST, true);
        $res = curl_exec($curl);
        curl_close($curl);
        if($is_parsing === true)
        {
            return json_decode($res, true);
        }
        return $res;
    }
}
?>