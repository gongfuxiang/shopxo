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

use app\service\UserService;

/**
 * GMO 信用卡 3d支付 - 接口
 * @author  liuhj
 * @version 1.0.0
 * @date    2021-6-29
 * @desc    description
 */
class Gmo3d1pay
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
            'name'          => 'Gmo3d1',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5', 'ios', 'android', 'toutiao'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '2.0版本，适用PC+H5+APP+头条小程序，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'liuhj',  // 开发者
            'author_url'    => '',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'host_url',
                'placeholder'   => 'host url',
                'title'         => 'host url',
                'is_required'   => 0,
                'message'       => '请输入服务URL',
            ]
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
        if(empty($this->config) || empty($this->config['host_url']) )
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 支付方式
        $ret = $this->PayWeb($params);
        
        return $ret;
    }

    /**
     * [PayWeb PC支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:23:04+0800
     * @param   [array]           $params [输入参数]
     */
    private function PayWeb($params = [])
    {
        // 用户信息
        $user = UserService::LoginUserInfo();
        if(empty($user['token']))
        {
            $user = UserService::UserTokenUpdate($user['id'], $user);
        }

        // 取引登録
        $entryTranUrl = $this->config['host_url'].'EntryTran.json';

        $entryTranParameter = [
            'shopID'       => 'tshop00001176',
            'shopPass'     => 'e6csh5bt',
            'orderID'      => $params['order_no'],
            'jobCd'        => 'CAPTURE',
            'itemCode'     => '0000990',
            'amount'       =>  $params['total_price'].'',
            'tax'          => '10',
            'tdFlag'       => '1',
            'tdTenantName' => base64_encode(mb_convert_encoding('ﾃｽﾄｼｮｯﾌﾟ', 'EUCJP', 'UTF-8')),
            'tds2Type'     => '1'
        ];
        $entryTranResult = $this->HttpRequestJson($entryTranUrl,$entryTranParameter);

        // エラーがある場合
        if( array_key_exists( 'errCode', $entryTranResult )  ){
            // エラー
            return false;
        }

        // 決済実行

        $execTranUrl = $this->config['host_url'].'ExecTran.json';

        $execTranParameter = [
            'accessID'        => $entryTranResult["accessID"],
            'accessPass'      => $entryTranResult["accessPass"],
            'orderID'         => $params['order_no'],
            'method'          => '1',
            'payTimes'        => '0',
            'cardNo'        => '4123450131003312',
            'expire'        => '2604',
            'securityCode'        => '000',
            'token'           => '',
            'httpAccept'      => 'SampleHttpAccept',
            'httpUserAgent'   => 'HttpUserAgent',
            'deviceCategory'  => '0',
            'clientField1'    => $params['total_price'].'',
            'clientField2'    => 'SampleClientField2',
            'clientField3'    => 'SampleClientField3',
            'clientFieldFlag' => '1',
            'tokenType'       => '1'
            // 'retUrl'          => $params['call_back_url'] 
        ];
        $execTranResult = $this->HttpRequestJson($execTranUrl,$execTranParameter);
        // エラーがある場合
        if( array_key_exists( 'errCode', $execTranResult )  ){
            // エラー
            return false;
        }

        // ACS呼出必要
        if($execTranResult["acs"]){
            $this->PayHtml($execTranResult, $params['call_back_url'].'?token='.$user['token']);
        }
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
        return intval(MyC('common_order_close_limit_time', 30, true)).'m';
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
        echo 100;
        echo '<pre>';
        print_r($params);die;
        // $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        // ksort($data);

        // 参数字符串
        // $prestr = '';
        // foreach($data AS $key=>$val)
        // {
        //     if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
        //     {
        //         $prestr .= "$key=$val&";
        //     }
        // }
        // $prestr = substr($prestr, 0, -1);

        // // 签名
        // if(!$this->OutRsaVerify($prestr, $data['sign']))
        // {
        //     return DataReturn('签名校验失败', -1);
        // }

        // 認証後決済実行

        $secureTranUrl = $this->config['host_url'].'SecureTran.json';

        $secureTranParameter = [
            'paRes'        => $params["PaRes"],
            'md'           => $params["MD"]
        ];
        $data = $this->HttpRequestJson($secureTranUrl,$secureTranParameter);

        // 支付状态
        if(!empty($data['tranID']))
        {
            $status = false;
            if(isset($data['approve']))
            {
                    $status = true;
            }

            if($status)
            {
                return DataReturn('支付成功', 0, $this->ReturnData($data));
            }
        }
        return DataReturn('处理异常错误', -100);
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
        $data['trade_no']       = $data['tranID'];        // 支付平台 - 订单号
        $data['buyer_user']     = '';//$data['seller_id'];       // 支付平台 - 用户
        $data['out_trade_no']   = $data['orderID'];//$data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['subject']) ? $data['subject'] : ''; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['clientField1'];//$data['total_amount'];    // 本系统发起支付的 - 总价

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
        
    }


    /**
     * [HttpRequest 网络请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url  [请求url]
     * @param    [array]           $data [发送数据]
     * @return   [mixed]                 [请求返回数据]
     */
    private function HttpRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $body_string = '';
        if(is_array($data) && 0 < count($data))
        {
            foreach($data as $k => $v)
            {
                $body_string .= $k.'='.urlencode($v).'&';
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
        }
        $headers = array('content-type: application/x-www-form-urlencoded;charset=windows-31j');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        var_dump($reponse);
        if(curl_errno($ch))
        {
            return false;
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return false;
            }
        }
        curl_close($ch);
        return json_decode($reponse, true);
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
    private function HttpRequestJson($url, $data, $second = 30)
    {
        $ch = curl_init();
        $header = ['Content-Type: application/json;charset=UTF-8'];

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
            return json_decode($result, true);
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return "curl出错，错误码:$error";
        }
    }
    /**
     * 获取签名内容
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]          $params [需要签名的参数]
     */
    public function GetSignContent($params)
    {
        ksort($params);
        $string = "";
        $i = 0;
        foreach($params as $k => $v)
        {
            if(!empty($v) && "@" != substr($v, 0, 1))
            {
                if ($i == 0) {
                    $string .= "$k" . "=" . "$v";
                } else {
                    $string .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset($k, $v);
        return $string;
    }

    /**
     * 跳转 
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-25T00:07:52+0800
     * @param    [array]                   $pay_data     [支付信息]
     * @param    [string]                  $redirect_url [支付结束后跳转url]
     */
    private function PayHtml($pay_data,$redirect_url)
    {
        // 支付代码
        exit('<html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=Windows-31J">
            </head>
            <body OnLoad=\'OnLoadEvent();\'>
            <form name="ACSCall" action="'.$pay_data["acsurl"].'" method="POST">
            <noscript>
            <br>
            <br>
            <center>
            <h2>
            3-Dセキュア認証を続けます。<br>
            ボタンをクリックしてください。
            </h2>
            <input type="submit" value="OK">
            </center>
            </noscript>
            <input type="hidden" name="PaReq" value="'.$pay_data["paReq"].'">
            <input type="hidden" name="TermUrl" value="'.$redirect_url.'">
            <input type="hidden" name="MD" value="'.$pay_data["md"].'">
            </form>
            <script >
                function OnLoadEvent() {
                    document.ACSCall.submit();
                }
            </script>
            </body>

        </html>');
    }
}
?>