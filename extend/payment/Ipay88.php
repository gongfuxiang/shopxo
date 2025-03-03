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
 * iPay88
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class Ipay88
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
     * @date    2020-06-26
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => 'iPay88',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部
            'desc'          => '适用PC+H5，马来西亚用户量最大的在线支付服务商，可以处理多达26种货币，包括MYR，AUD，EUR，SGD，USD，THB，RMB。<a href="https://www.ipay88.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'placeholder'   => '账户',
                'title'         => '账户',
                'is_required'   => 0,
                'message'       => '请填写账户account',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '密钥key',
                'title'         => '密钥key',
                'is_required'   => 0,
                'message'       => '请填写密钥key',
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
     * @date    2020-06-26
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

        // 支付参数
        $data_username      = $params['user']['user_name_view'];
        $data_useremail     = $params['user']['email'];
        $data_usercontact   = $params['user']['mobile'];
        $data_vid           = trim($this->config['account']);
        $data_orderid       = $params['order_no'];
        $data_vamount       = $params['total_price'];
        $data_vmoneytype    = 'MYR';
        $data_vpaykey       = trim($this->config['key']);
        $data_response_url  = $params['call_back_url'];
        $data_backend_url   = $params['notify_url'];
        $data_remark        = $params['order_id'];  

        $ipay_signature = '';
        $hash_amount = str_replace([',','.'], '', $data_vamount);
        $str = sha1($data_vpaykey  . $data_vid . $data_orderid . $hash_amount . $data_vmoneytype);
        for ($i=0;$i<strlen($str);$i=$i+2)
        {
            $ipay_signature .= chr(hexdec(substr($str,$i,2)));
        }
        $ipay_signature = base64_encode($ipay_signature);

        $html  = "<form id='ipay88submit' name='ipay88submit' style='text-align:center;' method=post action='https://www.mobile88.com/epayment/entry.asp'>";
        $html .= "<input type='hidden' name='MerchantCode' value='".$data_vid."'>";
        $html .= "<input type='hidden' name='PaymentId' value=''>";
        $html .= "<input type='hidden' name='RefNo' value='".$data_orderid."'>";
        $html .= "<input type='hidden' name='Amount' value='".$data_vamount."'>";
        $html .= "<input type='hidden' name='Currency' value='".$data_vmoneytype."'>";
        $html .= "<input type='hidden' name='ProdDesc' value='".$data_orderid."'>";
        $html .= "<input type='hidden' name='UserName' value='".$data_username."'>";
        $html .= "<input type='hidden' name='UserEmail' value='".$data_useremail."'>";
        $html .= "<input type='hidden' name='UserContact' value='".$data_usercontact."'>";
        $html .= "<input type='hidden' name='Remark' value='".$data_remark."'>";
        $html .= "<input type='hidden' name='Lang' value='UTF-8'>";
        $html .= "<input type='hidden' name='ResponseURL' value='".$data_response_url."'>";
        $html .= "<input type='hidden' name='BackendURL' value='".$data_backend_url."'>";
        $html .= "<input type='hidden' name='Signature' value='".$ipay_signature."'>";
        $html .= "<input type='submit' value='Pay Now via IPAY88'></form>";

        //submit按钮控件请不要含有name属性
        $html .= "<script>document.forms['ipay88submit'].submit();</script>";

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$html]);

        die($html);
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        if(empty($this->config))
        {
            return DataReturn('配置有误', -1);
        }
        if(empty($params['RefNo']))
        {
            return DataReturn('支付失败', -1);
        }
        if(empty($params['Signature']))
        {
            return DataReturn('签名为空', -1);
        }

        // 支付参数
        $mer_code = $params['MerchantCode'];
        $payment_id = $params['PaymentId'];
        $ref_no = $params['RefNo'];
        $amount = $params['Amount'];
        $currency = $params['Currency'];
        $remark = $params['Remark'];
        $trans_id = $params['TransId'];
        $auth_code = $params['AuthCode'];
        $istatus = $params['Status'];
        $err_desc = $params['ErrDesc'];
        $sign = $params['Signature'];

        // 签名
        $ipay_signature = '';
        $hash_amount = str_replace([',','.'], '', $amount);
        $str = sha1($this->config['key']  . $mer_code . $payment_id . $ref_no . $hash_amount . $currency . $istatus);
        for($i=0; $i<strlen($str); $i=$i+2)
        {
            $ipay_signature .= chr(hexdec(substr($str, $i, 2)));
        }    
        $ipay_signature = base64_encode($ipay_signature);
        if($sign == $ipay_signature && $istatus == '1')
        {
            return DataReturn('支付成功', 0, $this->ReturnData($params));
        }
        return DataReturn('签名错误', -1);
    }

    /**
     * 返回数据统一格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     * @param   [array]          $data [返回数据]
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = isset($data['MerchantCode']) ? $data['RefNo'] : '';  // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['TransId']) ? $data['TransId'] : '';   // 支付平台 - 用户
        $data['out_trade_no']   = isset($data['RefNo']) ? $data['RefNo'] : '';   // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['Remark']) ? $data['Remark'] : '';   // 本系统发起支付的 - 商品名称
        $data['pay_price']      = isset($data['Amount']) ? str_replace(',', '', $data['Amount']) : 0;   // 本系统发起支付的 - 总价

        return $data;
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
        return 'RECEIVEOK';
    }
}
?>