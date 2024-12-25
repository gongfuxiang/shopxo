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
use app\service\ResourcesService;
use app\service\UserService;
use app\service\OrderService;

/**
 * OceanPayment支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class OceanPayment
{
    // 插件配置参数
    private $config;

    // 缓存key
    private $cache_key = 'plugins_oceanpayment_pay_data_';

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
            'name'          => 'OceanPayment',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => 'OceanPayment全球数字支付方案提供商，适用PC+H5，信用卡支付。 <a href="https://www.oceanpayment.com/?form=shopxo" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'account',
                'placeholder'   => 'account账户号',
                'title'         => 'account账户号',
                'is_required'   => 0,
                'message'       => '请填写account账户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'terminal',
                'placeholder'   => 'terminal终端号',
                'title'         => 'terminal终端号',
                'is_required'   => 0,
                'message'       => '请填写terminal终端号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'secure_code',
                'placeholder'   => 'secureCode',
                'title'         => 'secureCode',
                'is_required'   => 0,
                'message'       => '请填写secureCode',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'key',
                'placeholder'   => 'Oceanpayment公钥',
                'title'         => 'Oceanpayment公钥',
                'is_required'   => 0,
                'rows'          => 4,
                'message'       => '请填写Oceanpayment公钥',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => 'en_US',
                'name'          => 'lang',
                'placeholder'   => '语言',
                'title'         => '语言',
                'is_required'   => 0,
                'message'       => '请填写语言',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => 'USD',
                'name'          => 'order_currency',
                'placeholder'   => '交易币种',
                'title'         => '交易币种',
                'is_required'   => 0,
                'message'       => '请填写交易币种',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => 'US',
                'name'          => 'billing_country',
                'placeholder'   => '消费者的账单国家',
                'title'         => '消费者的账单国家',
                'is_required'   => 0,
                'message'       => '请填写消费者的账单国家',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => 'N/A',
                'name'          => 'billing_state',
                'placeholder'   => '消费者的州（省、郡）',
                'title'         => '消费者的州（省、郡）',
                'is_required'   => 0,
                'message'       => '请填写消费者的州（省、郡）',
            ],
            [
                'element'       => 'select',
                'title'         => '是否测试环境',
                'message'       => '请选择是否测试环境',
                'name'          => 'is_dev_env',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>0, 'name'=>'否'],
                    ['value'=>1, 'name'=>'是'],
                ],
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'show_images',
                'placeholder'   => '自定义展示图片地址',
                'title'         => '自定义展示图片地址',
                'is_required'   => 0,
                'message'       => '请填写自定义展示图片地址',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '进入我的订单',
                'name'          => 'button_order_name',
                'placeholder'   => '进入我的订单按钮名称',
                'title'         => '进入我的订单按钮名称',
                'is_required'   => 0,
                'message'       => '请填写进入我的订单按钮名称',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '立即支付',
                'name'          => 'button_pay_name',
                'placeholder'   => '支付按钮名称',
                'title'         => '支付按钮名称',
                'is_required'   => 0,
                'message'       => '请填写支付按钮名称',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '支付金额：',
                'name'          => 'pay_first_name',
                'placeholder'   => '价格提示名称',
                'title'         => '价格提示名称',
                'is_required'   => 0,
                'message'       => '请填写价格提示名称',
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
        if(empty($this->config) || empty($this->config['account']) || empty($this->config['terminal']) || empty($this->config['secure_code']) || empty($this->config['key']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 订单地址
        $address_data = (!empty($params['business_data']) && !empty($params['business_data'][0]) && !empty($params['business_data'][0]['address_data'])) ? $params['business_data'][0]['address_data'] : [];
        $tel = empty($address_data) ? (empty($params['user']['mobile']) ? 'N/A' : $params['user']['mobile']) : $address_data['tel'];
        $city = empty($address_data['city_name']) ? 'N/A' : $address_data['city_name'];
        $address = empty($address_data['address']) ? 'N/A' : $address_data['address'];

        // 支付数据
        $parameter = [
            'account'            => $this->config['account'],
            'terminal'           => $this->config['terminal'],
            'signValue'          => '',
            'key'                => $this->config['key'],
            'backUrl'            => $params['call_back_url'],
            'noticeUrl'          => $params['notify_url'],
            'methods'            => 'Credit Card',
            'order_number'       => $params['order_no'],
            'order_currency'     => empty($this->config['order_currency']) ? 'USD' : $this->config['order_currency'],
            'order_amount'       => $params['total_price'],
            'billing_firstName'  => $params['user']['id'],
            'billing_lastName'   => $params['user']['user_name_view'],
            'billing_email'      => empty($params['user']['email']) ? $params['user']['id'].'@'.__MY_HOST__ : $params['user']['email'],
            'billing_phone'      => $tel,
            'billing_country'    => empty($this->config['billing_country']) ? 'US' : $this->config['billing_country'],
            'billing_state'      => empty($this->config['billing_state']) ? 'N/A' : $this->config['billing_state'],
            'billing_city'       => $city,
            'billing_address'    => $address,
            'billing_zip'        => 'N/A',
            'billing_ip'         => GetClientIP(),
            'ship_firstName'     => $params['user']['id'],
            'ship_lastName'      => $params['user']['user_name_view'],
            'ship_email'         => empty($params['user']['email']) ? $params['user']['id'].'@'.__MY_HOST__ : $params['user']['email'],
            'ship_phone'         => $tel,
            'ship_country'       => empty($this->config['billing_country']) ? 'US' : $this->config['billing_country'],
            'ship_state'         => empty($this->config['billing_state']) ? 'N/A' : $this->config['billing_state'],
            'ship_city'          => $city,
            'ship_addr'          => $address,
            'ship_zip'           => 'N/A',
            'productSku'         => $params['name'],
            'productName'        => $params['site_name'].'-'.$params['name'],
            'productNum'         => 1,
            'productPrice'       => $params['total_price'],
        ];

        // 存储单号缓存
        MyCache($this->cache_key.$params['user']['id'], $params['order_no'], 3600);

        // 签名（account+terminal+order_number+order_currency+order_amount+billing_firstName+billing_lastName+billing_email+secureCode）
        $parameter['signValue'] = hash('sha256', $parameter['account'].$parameter['terminal'].$parameter['order_number'].$parameter['order_currency'].$parameter['order_amount'].$parameter['billing_firstName'].$parameter['billing_lastName'].$parameter['billing_email'].$this->config['secure_code']);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        die($this->PayHtml($parameter));
    }

    /**
     * 支付代码
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-25T00:07:52+0800
     * @param    [array]   $pay_data     [支付信息]
     */
    private function PayHtml($pay_data)
    {
        // 支付代码
        $html = '<!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Oceanpayment Debit/Credit Card|One-Page Checkout</title>
                        <style type="text/css">
                            #payForm, .show-images {
                                width: 600px;
                            }
                            #payForm {
                                margin: 0 auto;
                                margin-top: 50px;
                            }
                            .header {
                                position: relative;
                            }
                            .logo {
                                max-height: 80px;
                            }
                            .price {
                                position: absolute;
                                right: 0;
                                bottom: 0;
                                color: #666;
                            }
                            .total-price {
                                color: #F44336;
                                font-size: 22px;
                                font-weight: bold;
                            }
                            .form-content {
                                border: 1px solid #f5f5f5;
                                border-radius: 10px;
                                padding: 50px;
                                margin-top: 20px;
                            }
                            .bottom {
                                text-align: right;
                                padding-right: 50px;
                                overflow: hidden; 
                            }
                            .bottom .my-order {
                                text-decoration: none;
                                background-color: #fff;
                                border: 1px solid #ff5722;
                                color: #ff5722;
                                border-radius: 10px;
                                padding: 13px 0;
                                margin-right: 15px;
                            }
                            .bottom .pay-button {
                                background-color: #4caf50;
                                border: 1px solid #4caf50;
                                color: #fff;
                                border-radius: 10px;
                                padding: 16px 0;
                                cursor: pointer;
                            }
                            .bottom .my-order,
                            .bottom .pay-button {
                                display: block;
                                float: right;
                                width: 175px;
                                text-align: center;
                                font-size: 16px;
                            }
                            .show-images {
                                position: absolute;
                                left: auto;
                                bottom: 50px;
                                text-align: center;
                            }
                            .show-images img {
                                max-width: 100%;
                                max-height: 50px;
                            }
                            @media only screen and (max-width: 640px) {
                                #payForm, .show-images {
                                    max-width: 100%;
                                }
                                .form-content {
                                    padding: 50px 0;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <!--表单提交 -->
                        <form id="payForm">
                            <div class="header">
                                <img src="'.AttachmentPathViewHandle(MyC('home_site_logo')).'" alt="'.MyC('home_seo_site_title').'" class="logo" />
                                <span class="price">
                                    <span>'.(empty($this->config['pay_first_name']) ? '支付金额：' : $this->config['pay_first_name']).'</span>
                                    <string class="total-price">'.ResourcesService::CurrencyDataSymbol().$pay_data['order_amount'].'</string>
                                </span>
                            </div>
                            <div class="form-content">';
                            foreach($pay_data as $k=>$v)
                            {
                                $html .= '<input class="form-control" type="hidden" id="'.$k.'" name="'.$k.'" value="'.$v.'"/>';
                            }
                            $html .= '<!--加载Oceanpayment支付页面Div-->
                                <div id="oceanpayment-element"></div>
                                <div class="bottom">
                                    <button type="button" class="pay-button" onclick="pay();" data-status="0">'.(empty($this->config['button_pay_name']) ? '立即支付' : $this->config['button_pay_name']).'</button>
                                    <a href="'.MyUrl('index/order/index').'" class="my-order">'.(empty($this->config['button_order_name']) ? '进入我的订单' : $this->config['button_order_name']).'</a>
                                </div>
                            </div>';
                            if(!empty($this->config['show_images']))
                            {
                                $html .= '<div class="show-images">
                                            <img src="'.$this->config['show_images'].'" />
                                        </div>';
                            }
                        $html .= '</form>
                    </body>
                </html>
                <!-- 加载JS -->
                <script src="https://secure.oceanpayment.com/pub/js/jquery/jq.js"></script>
                <script src="https://secure.oceanpayment.com/pages/js/oceanpayment.js"></script>
                <script>
                    // 初始化
                    $(function() {
                        Oceanpayment.init('.(isset($this->config['is_dev_env']) && $this->config['is_dev_env'] == 1 ? 'true' : '""').', "","'.(empty($this->config['lang']) ? 'en_US' : $this->config['lang']).'");
                    });

                    // 回调处理
                    var oceanpaymentCallBack = function(data) {
                        if(data.msg){
                            $(".pay-button").attr("data-status", 0);
                            console.log("pay reset");
                            // 处理卡信息错误
                            alert(data.msg);
                        }else{
                            // 处理下单返回
                            console.log(data);
                            // 加载xml文档
                            var xml_doc = (new DOMParser()).parseFromString(data, "text/xml");
                            // 将XML对象转换为JSON对象
                            var obj = {};
                            var response = xml_doc.getElementsByTagName("response")[0];
                            if(response == undefined) {
                                $(".pay-button").attr("data-status", 0);
                                console.log("pay reset");
                                alert("xml error");
                                return false;
                            }
                            for(var i = 0; i < response.children.length; i++) {
                                var child = response.children[i];
                                obj[child.nodeName] = child.textContent;
                            }
                            // 是否需要3d验证
                            if((obj.pay_url || null) != null) {
                                $(".pay-button").attr("data-status", 0);
                                console.log("pay reset");
                                window.location.href = obj.pay_url;
                                return false;
                            }
                            // 成功进入回调页面
                            var back_url = "'.$pay_data['backUrl'].'";
                            var params = Object.keys(obj).map(key => key + "=" + obj[key]).join("&");
                            var join = back_url.indexOf("?") == -1 ? "?" : "&";
                            var url = back_url+join+params;
                            window.location.href = url;
                        } 
                    }

                    //  调用支付
                    function pay() {
                        var status = parseInt($(".pay-button").attr("data-status") || 0);
                        if(status == 1) {
                            console.log("progress");
                            return false;
                        }
                        console.log("pay...");
                        $(".pay-button").attr("data-status", 1);
                        // Jquery的serialize()方法
                        var formData = $("#payForm").serializeArray();
                        var obj = {}
                        for (var i in formData) {
                            obj[formData[i].name]=formData[i]["value"];
                        }
                        Oceanpayment.checkout(obj);
                    }
                </script>';
        return $html;
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
        // 接收参数
        if(empty($params))
        {
            $params = MyInput();
        }

        // 同步返回
        if(empty($params['order_number']))
        {
            // 用户信息
            $user = UserService::LoginUserInfo();
            if(empty($user))
            {
                return DataReturn('用户未登录', -1);
            }
            $order_no = MyCache($this->cache_key.$user['id']);
            if(empty($order_no))
            {
                return DataReturn('支付单号为空', -1);
            }

            // 轮训5秒查询结果
            $count = 5;
            for($i=0; $i<$count; $i++)
            {
                // 暂停1秒钟，等待异步通知处理
                sleep(1);
                // 查询订单状态
                $ret = OrderService::OrderPayCheck(['order_no'=>$order_no, 'user'=>$user]);
                if($ret['code'] == 0)
                {
                    return $ret;
                }
            }
            return DataReturn('支付失败，可能网络原因，请以订单状态为准', -1);
        }

        // 异步通知处理
        $sign = strtoupper(hash('sha256', $this->config['account'].$this->config['terminal'].$params['order_number'].$params['order_currency'].$params['order_amount'].(empty($params['order_notes']) ? '' : $params['order_notes']).$params['card_number'].$params['payment_id'].$params['payment_authType'].$params['payment_status'].(empty($params['payment_details']) ? '' : $params['payment_details']).(empty($params['payment_risk']) ? '' : $params['payment_risk']).$this->config['secure_code']));
        if($sign != strtoupper($params['signValue']))
        {
            return DataReturn('签名校验失败', -1);
        }
    
        $ret = DataReturn('支付失败', -1);
        if(isset($params['payment_status']) && $params['payment_status'] == 1)
        {
            $ret = DataReturn('支付成功', 0, $this->ReturnData($params));
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
        // 返回数据固定基础参数
        $data['trade_no']       = $data['payment_id'];        // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['pay_userId']) ? $data['pay_userId'] : '';       // 支付平台 - 用户
        $data['out_trade_no']   = $data['order_number'];    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['order_notes']) ? $data['order_notes'] : ''; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['order_amount'];    // 本系统发起支付的 - 总价

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
                'key_name'          => 'refund_price',
                'error_msg'         => '退款金额不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 退款参数
        $parameter = [
            'account'             => $this->config['account'],
            'terminal'            => $this->config['terminal'],
            'refund_number'       => $params['order_no'].GetNumberCode(),
            'out_trade_no'        => $params['order_no'],
            'payment_id'          => $params['trade_no'],
            'refund_type'         => ($params['refund_price'] < $params['pay_price']) ? 2 : 1,
            'refund_amount'       => $params['refund_price'],
            'refund_description'  => $refund_reason,
        ];

        // 签名（account+terminal+payment_id+refund_number+refund_type+refund_amount+refund_description+secureCode）
        $parameter['signValue'] = hash('sha256', $parameter['account'].$parameter['terminal'].$parameter['payment_id'].$parameter['refund_number'].$parameter['refund_type'].$parameter['refund_amount'].$parameter['refund_description'].$this->config['secure_code']);
        // 请求接口
        $ret = CurlPost($this->RequestUrl('service/applyRefund'), $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // xml处理
        $data = $this->XmlToArray($ret['data']);
        if(isset($data['refund_results']) && $data['refund_results'] == '00')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'    => isset($data['order_number']) ? $data['order_number'] : '',
                'trade_no'        => isset($data['refund_id']) ? $data['refund_id'] : '',
                'buyer_user'      => '',
                'refund_price'    => $params['refund_price'],
                'return_params'   => $data,
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        }
        $msg = empty($data['refund_description']) ? '退款失败' : $data['refund_description'];
        return DataReturn($msg, -1);
    }

    /**
     * 请求url
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-07-03
     * @desc    description
     * @param   [string]          $path [接口路径]
     */
    public function RequestUrl($path)
    {
        $url = 'https://query.oceanpayment.com/';
        if(isset($this->config['is_dev_env']) && $this->config['is_dev_env'] == 1)
        {
            $url = 'https://test-query.oceanpayment.com/';
        }
        return $url.$path;
    }

    /**
     * xml转数组
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [string]          $xml [xm数据]
     */
    private function XmlToArray($xml)
    {
        if(!$this->XmlParser($xml))
        {
            return is_string($xml) ? $xml : '接口返回数据有误';
        }

        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 判断字符串是否为xml格式
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [string]          $string [字符串]
     */
    function XmlParser($string)
    {
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser, $string, true))
        {
          xml_parser_free($xml_parser);
          return false;
        } else {
          return (json_decode(json_encode(simplexml_load_string($string)),true));
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
        return 'receive-ok';
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
        return 'receive-no';
    }
}
?>