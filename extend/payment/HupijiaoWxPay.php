<?php

namespace payment;

use think\facade\Db;
use app\service\PayLogService;

/**
 * 虎皮椒微信支付
 */
class HupijiaoWxPay {
    //插件配置参数
    private $config;

    public function __construct($params = []) {
        $this->config = $params;
    }

    /**
     * 配置信息
     */
    public function Config() {
        // 基础信息
        $base = [
            'name' => '虎皮椒微信支付',
            'version' => '1.0.1',
            'apply_version' => '不限',
            'apply_terminal' => ['pc','h5','weixin'],// 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc' => '个人微信支付接口，无需提现，100%资金安全，官方清算，金额无限制，支持各种网站系统。是个人收款的最佳解决方案。<a href="https://admin.xunhupay.com/sign-in.html" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author' => '迅虎网络',  // 开发者
            'author_url' => 'https://www.wpweixin.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element' => 'input',
                'type' => 'text',
                'default' => '',
                'name' => 'app_id',
                'placeholder' => 'AppID',
                'title' => 'AppID',
                'is_required' => 0,
                'message' => '请填写虎皮椒分配的AppID',
            ],
            [
                'element' => 'input',
                'type' => 'text',
                'default' => '',
                'name' => 'app_secret',
                'placeholder' => 'AppSecret',
                'title' => 'AppSecret',
                'is_required' => 0,
                'message' => '请填写虎皮椒分配的AppSecret',
            ],
            [
                'element' => 'input',
                'type' => 'text',
                'default' => 'https://api.xunhupay.com/payment/do.html',
                'name' => 'gateway_url',
                'placeholder' => '网关地址',
                'title' => '网关地址',
                'is_required' => 0,
                'message' => '请填写虎皮椒支付网关地址',
            ]
        ];

        return [
            'base' => $base,
            'element' => $element,
        ];
    }


    /**
     * 支付入口
     */
    public function Pay($params = []) {
        // 参数
        if (empty($params)) {
            return DataReturn('参数不能为空', -1);
        }

        // 配置信息
        if (empty($this->config)) {
            return DataReturn('支付缺少配置', -1);
        }
        $parameter=$this->GetPayParams($params);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        $response=$this->HttpRequest($this->config['gateway_url'],$parameter);
        $response=json_decode($response,true);
        if(isset($response['errcode'])&&$response['errcode']==0){
            return DataReturn('success', 0, $response['url']);
        }
        return DataReturn($response['errmsg'], -1);
    }

    /**
     * 支付回调处理
     */
    public function Respond($params = []) {
        //支付异步回调
        if(isset($_POST['hash'])){
            file_put_contents(dirname(__FILE__).'/log.txt',json_encode($_POST)."\r\n",FILE_APPEND);
            $respose=$_POST;
            $this->checkRespose($respose);
            return DataReturn('支付成功', 0, $this->ReturnData($respose));
        }
        //查询订单支付状态
        if(isset($_GET['order_no'])){
            $order_no=$_GET['order_no'];
            $payData=Db::name('PayLog')->where('log_no',$order_no)->order('id desc')->find();
            if(!$payData){
                return DataReturn('订单暂无支付信息！', -100);
            }
            $data=[
                'trade_no'=>$payData['trade_no'],
                'buyer_user'=>$payData['buyer_user'],
                'out_trade_no'=>$order_no,
                'subject'=>$payData['subject'],
                'pay_price'=>$payData['pay_price'],
            ];
            return DataReturn('支付成功', 0,$data);
        }
        //同步-因同步不携带验签参数，所以需要ajax查询订单是否支付
        return DataReturn('处理异常错误', -100);
    }

    /**
     * 退款处理
     */
    public function Refund($params = []){
        return DataReturn('该支付方式暂未提供退款接口', -1);
    }

    /**
     * 返回数据统一格式
     */
    private function ReturnData($data) {
        // 返回数据固定基础参数
        $data['trade_no']       = $data['transaction_id'];  // 支付平台 - 订单号
        $data['buyer_user']     = $data['appid'];           // 支付平台 - 用户
        $data['out_trade_no']   = $data['trade_order_id'];  // 本系统发起支付的 - 订单号
        $data['subject']        = $data['plugins'];         // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_fee'];       // 本系统发起支付的 - 总价
        return $data;
    }

    /**
     * 查询
     */

    /**
     * 获取支付参数
     */
    private function GetPayParams($params = []){
        $data = [
            'version' => '1.1',
            'appid' => $this->config['app_id'],
            'trade_order_id'=> $params['order_no'],
            'payment' => 'wechat',
            'total_fee' => round($params['total_price'],2),
            'title' => $params['name'].'-订单号:'.$params['order_no'],
            'time' => time(),
            'notify_url'=> $params['notify_url'],
            'return_url'=> $params['call_back_url'].'?order_no='.$params['order_no'],
            'nonce_str' => md5(time()),
            'plugins'=>$params['name']
        ];
        $data['hash']=$this->GenerateHash($data);
        return $data;
    }

    /**
     * 生成hash
     */
    private function GenerateHash($data=[]){
        if(array_key_exists('hash',$data)){
            unset($data['hash']);
        }
        ksort($data);
        $string=$this->FormatUrlParams($data).$this->config['app_secret'];
        return md5($string);
    }

    /**
     * 格式化参数
     */
    private function FormatUrlParams($data=[]){
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "hash" && $v !== "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * http请求
     */
    private function HttpRequest($url, $data = '',$headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    // 获取响应状态码
        $error = curl_error($ch);
        curl_close($ch);
        if ($http_code != 200) {
            exit("error:{$error}");
        }
        return $output;
    }

    /**
     * 校验参数
     */
    private function CheckRespose($data){
        if($data['status']!='OD'){
            exit($data['status']);
        }
        //校验签名
        if(!$this->CheckHash($data)){
            exit('签名校验失败');
        }
    }

    /**
     * 校验签名
     */
    private function CheckHash($data){
        $hish=$this->GenerateHash($data);
        if($hish==$data['hash']){
            return true;
        }
        return false;
    }

}

?>