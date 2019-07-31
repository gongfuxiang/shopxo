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
 * 首信易支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class PayEase
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
            'name'          => '首信易支付',  // 插件名称
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用PC+H5，致力于打造汇通全球的、领先的国际支付平台，为商家提供更优质、更安全的支付清算服务。<a href="https://www.beijing.com.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'merchantId',
                'placeholder'   => '商户编号',
                'title'         => '商户编号',
                'is_required'   => 0,
                'message'       => '请填写商户编号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'password',
                'placeholder'   => '密码',
                'title'         => '密码',
                'is_required'   => 0,
                'message'       => '请填写密码',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'public_key',
                'placeholder'   => '应用公钥',
                'title'         => '应用公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用公钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'private_key',
                'placeholder'   => '应用私钥',
                'title'         => '应用私钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用私钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'out_rsa_public',
                'placeholder'   => '首信易公钥',
                'title'         => '首信易公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写首信易公钥',
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

        $data = [
            'merchantId'        => $this->config['merchantId'],
            'orderAmount'       => $params['total_price']*100,
            'orderCurrency'     => 'CNY',
            'requestId'         => $params['order_no'].GetNumberCode(6),
            'notifyUrl'         => $params['notify_url'],
            'callbackUrl'       => $params['call_back_url'],
        ];
        $payer = [
            'idType'    => 'IDCARD',
        ];
        $data['payer'] = $payer;
        $detail = [
            [
                'name'      => '新款苹果手机iphone6s',
                'quantity'  => 1,
                'amount'    => $params['total_price']*100,
            ]
        ];
        $data['productDetails'] = $detail;

        $private_key = ROOT.'rsakeys/client.pfx';
        $public_key = ROOT.'rsakeys/server.cer';
        $str = $this->buildJson($private_key, $this->config['password'], $data);
        $date = $this->creatdate($str, $public_key);

        $url = 'https://apis.5upay.com/onlinePay/order';
        return $this->execute(
            $private_key,
            $this->config['password'],
            $public_key,
            $url,
            $date
        );
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
        // file_put_contents(ROOT.'eeeeeeee.txt', json_encode($params));//die;
        // file_put_contents(ROOT.'hhhhhhhh.txt', json_encode($_SERVER));
 
        // 同步返回，直接进入订单详情页面
        if(substr(CurrentScriptName(), -20) == '_payease_respond.php' && empty($params['hmac']) && !empty($params['requestId']))
        {
            exit(header('location:'.MyUrl('index/order/detail', ['orderno'=>substr($params['requestId'], 0, strlen($params['requestId'])-6)])));
        }



        // $params = json_decode('{"data":"GwrbZZFVl2cVM9G5ACPr+b8RhiZgQkLtC5xHdYwkqEOwMDhUD+wzoeWoVj3swTJ0Q36JNCtjPRZ8TPYe5SkPuo261i\/i\/gzbgrGO1QZxxrqYCMEmLHj3dajdXu2+FZsW4AyIxgpsp0CJ3HQBezmRiKzG4rYZvMDXUgIBArT8T9bcl3HySzyfLovtAWw8jwC2MPYi8d1nsHKEP3uB5NPCA2y\/vXMt0iq2xGNlgVFD0OVk2RkADDYmPxZFH+cH1LH0mX2+Fe5Ga2\/KbUQlR7HTeAkxK1w5sHvzdBK5vtbWJD4vF\/XaNsTuJk4QIw12LtZHGeUNUeWa900CMMt+Jy3DbxnIRutQhudBB7KEFUDlcIWGDBhyikZyMzUWG1CZrbWyih3u6Pv3jgFjHXE0v8kvazWueumV2+\/MfOUYIs8Ax8QVnZVWBYggR3ZpwIQTayjoSLGtQOtvcHUJux\/K6TzRq8xXwVZnqy+yxQArIL4lbfdyEB6TDhQGLsykqwI9b5OhBpj83PQhhHljUhcGkPMgjME1KWL\/JayoS1m+MvYV\/f8K1bphFd4RIf5KxiEkjCeLYXlXC24Eu8V89F6RcVKZE8mZAFaoS7hFISWncTVhZQEAeqbrDl6cPk2CvGbmfJY1DLVDka+ueyl6hqaCtWjTB9D9UH5kh7sCr4WOD9XgHYMw1Kariju9BvUr7f6mhvLGWvFJj8qFejCe2a4\/CbHikmGedljz6EqjU3Op3o6eTe1PyHvXrKktdFWE6yV+VrHBlY9wEHk6fT32ovUkMbCvNX8liRgYaEJX4qtPmvsPs8gSQ0dMplccYGsHclzWTW5TC56CzjOp7pGujHZJ4kVsEIxLpDQDFV9AlxC\/4aNQPNisGv9rBmJ7gxITfrquOpaScAeCSjaTsEj4MS2OE89gAkZ+0YepULRV5F3C+pTWUYtHXam9BL6\/56oj6F\/oL0te"}', true);

        // $_SERVER['HTTP_ENCRYPTKEY'] = 'gQ3QJ98euDJGQOI9UshCfcayWZwKO8OkPREZTVlTTQ4uz6cjTRvhQbvh0dgaPAGZcmHoC4wt0EAlzOCXiF7v+ipMKradhshfJhgOvXJrFJ8Hq7/vHzCCHm7Myqe8U00N20Gs/PJERMsZES3dYM1hlcK8jLBD/okWh2UJW8ocbZiR6hjs3jCzGC/tqv9A1VZi50A5/hnv9CXW2PFppTtZ6wSTVazp0+bAscqquqwABh21HTj65Kkv98OArF7fxeLS2LUPF6HkgkFS4711wcSXplvcssa+gecZ4Z9BVz4JmWKsvLCFoOIg3gwRcPRBdfSTw2tENDVxtKWC+1g6XpBxew==';
        // $_SERVER['HTTP_MERCHANTID'] = '890000593';

        // 异步处理
        $private_key = ROOT.'rsakeys/client.pfx';
        $public_key = ROOT.'rsakeys/test.cer';
        $params['encryptKey'] = isset($_SERVER['HTTP_ENCRYPTKEY']) ? $_SERVER['HTTP_ENCRYPTKEY'] : '';
        $params['merchantId'] = isset($_SERVER['HTTP_MERCHANTID']) ? $_SERVER['HTTP_MERCHANTID'] : '';
        $ret = $this->NotifyCheckHmac($private_key, $params, $public_key, $this->config['password']);

        // 支付状态
        if(isset($ret['code']) && $ret['code'] == 0 && isset($ret['data']['status']) && $ret['data']['status'] == 'SUCCESS')
        {
            return DataReturn('支付成功', 0, $this->ReturnData($ret['data']));
        }

        return DataReturn('支付失败', -100);
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
        // 参数处理
        $out_trade_no = substr($data['requestId'], 0, strlen($data['requestId'])-6);

        // 返回数据固定基础参数
        $data['trade_no']       = isset($data['serialNumber']) ? $data['serialNumber'] : '';  // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['bindCardId']) ? $data['bindCardId'] : '';      // 支付平台 - 用户
        $data['subject']        = isset($data['orderCurrency']) ? $data['orderCurrency'] : ''; // 本系统发起支付的 - 商品名称
        $data['out_trade_no']   = $out_trade_no; // 本系统发起支付的 - 订单号
        $data['pay_price']      = $data['orderAmount']/100;   // 本系统发起支付的 - 总价
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
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        $private_key = ROOT.'rsakeys/client.pfx';
        $public_key = ROOT.'rsakeys/server.cer';
        $data = [
            'merchantId'        => $this->config['merchantId'],
            'requestId'         => $params['order_no'].GetNumberCode(6),
            'amount'            => $params['refund_price']*100,
            'orderId'           => $params['order_no'],
            //'notifyUrl'         => $params['notify_url'],
            'remark'            => $refund_reason,
        ];
        $str = $this->buildJson($private_key, $this->config['password'], $data);
        $date = $this->creatdate($str, $public_key);

        $url = 'https://apis.5upay.com/onlinePay/refund';
        $ret = $this->execute(
            $private_key,
            $this->config['password'],
            $public_key,
            $url,
            $date
        );
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            if(isset($ret['data']['status']) && $ret['data']['status'] == 'SUCCESS')
            {
                // 统一返回格式
                $data = [
                    'out_trade_no'  => isset($result['requestId']) ? $result['requestId'] : '',
                    'trade_no'      => isset($result['serialNumber']) ? $result['serialNumber'] : '',
                    'buyer_user'    => isset($result['currency']) ? $result['currency'] : '',
                    'refund_price'  => isset($result['amount']) ? $result['amount']/100 : 0.00,
                    'return_params' => $ret['data'],
                ];
                return DataReturn('退款成功', 0, $data);
            }
        }
        return DataReturn('退款失败', -100);

        // $this->requestId = $params["requestId"];
        // $this->amount = $params["amount"];
        // $this->orderId = $params["orderId"];
        // $this->remark = $params["remark"];
        // $this->notifyUrl = $params["notifyUrl"];
        // $handle = new RefundHandle();

        // $str = $this->buildJson($private_key,$password);
        // $date = $this->creatdate($str,$public_key);
        // return $this->execute(
        //     $private_key,
        //     $password,
        //     $public_key,
        //     ConfigurationUtils::getInstance()->getOnlinepayRefundUrl(),
        //     json_encode($date),
        //     $handle
        // );
    }



    public function creatdate($strdata,$public_key)
    {
        /*
          * 生成16位随机数（AES秘钥）
          */
        $str1='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randStr = str_shuffle($str1);//打乱字符串
        $rands= substr($randStr,0,16);//生成16位aes密钥

        $encrypted=$this->rsaPublicEncode($public_key,$rands);

        $date=$this->aesEncrypt($strdata,$rands);
        $json = array("data" =>$date,"encryptKey"=>$encrypted,"merchantId"=>$strdata['merchantId'],"requestId"=>$strdata['requestId']);


        return $json;
    }

    /**
     * 填充算法
     * @param string $source
     * @return string
     */
    private function addPKCS7Padding($string, $blocksize = 16) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;

    }



    /**
     * hmac 验证
     * @return mixed
     */
    public function checkHmac($private_key,$public_key,$data,$password)
    {
        $aeskey=$this->rsaPrivateDecode($data,$private_key,$password);
        $encrypt_str=$this->aesDesc($data,$aeskey);
        return $this->hmacSign($encrypt_str,$public_key);
    }


    public function execute($private_key,$password,$public_key,$url, $param)
    {
        $ret = $this->httpRequestPost($url, $param,$public_key,$password,$private_key);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $this->handle($ret['data']);
        return $ret;
    }

    public function handle($data = array())
    {
        if (isset($data['status']) && $data['status'] == 'REDIRECT'){
            header("Location: {$data['redirectUrl']}");
            exit;
        } else if(isset($data['status']) && $data['status'] == 'SUCCESS'){
            
            $aa=$data["scanCode"];
            $img = base64_decode($aa);
            header('Content-type: image/jpg');
            print_r($img);
                
        } else if(isset($data['status']) && $data['status'] == 'CANCEL'){
            return DataReturn('处理失败', -1, $data);
        }else if(isset($data['status']) && $data['status'] == 'INIT'){
            return DataReturn('处理失败', -1, $data);
        }else{
            return DataReturn('响应错误', -1);
        }
    }

    /**
     * post请求
     * @return mixed
     */
    public function httpRequestPost($url, $param,$public_key,$password,$private_key)
    {
        $curl = curl_init($this->absoluteUrl($url));
        curl_setopt($curl,CURLOPT_HEADER, 1 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_HTTPHEADER,array(
            'Content-Type: application/vnd.5upay-v3.0+json',
            'encryptKey: '.$param['encryptKey'],
            'merchantId: '.$param['merchantId'],
            'requestId: '.$param['requestId']
        ));
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$param['data']);// post传输数据
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证

        $responseText = curl_exec($curl);
        if (curl_errno($curl) || $responseText === false) {
            curl_close($curl);
            return DataReturn('请求错误', -1);
        }
        curl_close($curl);
        preg_match_all('/(encryptKey|merchantId|data"):(\s+|")([^"\s]+)/s',$responseText,$m);
        list($encryptKey, $merchantId, $data) = $m[3];
        $responsedata = array("data" =>$data,"encryptKey"=>$encryptKey,"merchantId"=>$merchantId);
        if ($responsedata['merchantId'] == null){
            return DataReturn('请求错误', -1);
        }
        return $this->checkHmac($private_key,$public_key,$responsedata,$password);
    }


    /**
     *
     * @return string
     */
    private function buildJson($private_key,$password,$para=null)
    {
        unset($para['response_hmac_fields']);
        $data = array();
        foreach($para as $k=>$var){
            if(is_scalar($var) && $var !== '' && $var !== null){
                $data[$k] = $var;
            }else if(is_object($var)){
                $data[$k] =array_filter((array) $var);
            }else if(is_array($var)){
                $data[$k] =array_filter($var);

            }
            if(empty($data[$k])){
                unset($data[$k]);
            }
        }
        ksort($data);
        $hmacSource = '';
        foreach($data as $key => $value){
            if (is_array($value)){
                ksort($value);
                foreach ($value as $key2 => $value2) {
                    if(is_array($value2)) {
                        $value2 = array_filter((array)$value2);
                        ksort($value2);
                        foreach ($value2 as $oKey => $oValue) {
                            $oValue.='#';
                            $hmacSource .= trim($oValue);
                        }
                    }else{
                        $value2.='#';
                        $hmacSource .= trim($value2);
                    }
                }
            } else {
                $value.='#';
                $hmacSource .= trim($value);
            }
        }
        $sha1=sha1($hmacSource, true);
        $hmac=$this->rsaPrivateSign($sha1,$private_key,$password);
        $data['hmac'] = $hmac;
        return $data;
    }


    private function absoluteUrl($url, $param=null)
    {
        if ($param !== null) {
            $parse = parse_url($url);

            $port = '';
            if ( ($parse['scheme'] == 'http') && ( empty($parse['port']) || $parse['port'] == 80) ){
                $port = '';
            }else{
                $port = $parse['port'];
            }
            $url = $parse['scheme'].'//'.$parse['host'].$port. $parse['path'];

            if(!empty($parse['query'])){
                parse_str($parse['query'], $output);
                $param = array_merge($output, $param);
            }
            $url .= '?'. http_build_query($param);
        }

        return $url;
    }
    /**
     * AES加密方法
     * @param string $str
     * @return string
     */

    function aesEncrypt($data,$decrypted){
        $str =json_encode($data);
        $str = trim($str);
        $str = $this->addPKCS7Padding($str);
        $encrypt_str= openssl_encrypt($str, 'AES-128-ECB', $decrypted, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING);
        return base64_encode($encrypt_str);
    }
    /**
     * AES解密方法
     * @param string $str
     * @return string
     */

    function aesDesc($data,$decrypted){
        $date = json_encode($data['data']);
        $screct_key = $decrypted;
        // $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        // $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $date, MCRYPT_MODE_ECB, $iv);
        $encrypt_str=  openssl_decrypt($date,"AES-128-ECB",$screct_key);
        $encrypt_str = preg_replace('/[\x00-\x1F]/','', $encrypt_str);
        $encrypt_str = json_decode($encrypt_str,true);
        return $encrypt_str;
    }
    /**
     * 移去填充算法
     * @param string $source
     * @return string
     */
    function stripPKSC7Padding($source){
        $source = trim($source);
        $char = substr($source, -1);
        $num = ord($char);
        if($num==62)return $source;
        $source = substr($source,0,-$num);
        return $source;
    }

    /*
        * 去除空值的元素
        */

    function clearBlank($arr)
    {
        function odd($var)
        {
            return($var<>'');//return true or false
        }
        return (array_filter($arr, "odd"));
    }
    /*
       * RSA公钥加密
       */
    function rsaPublicEncode($public_key,$rands){

        $encryptKey=file_get_contents($public_key);
        $pem = chunk_split(base64_encode($encryptKey),64,"\n");//转换为pem格式的公钥
        $public_key = "-----BEGIN CERTIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
        $pu_key =  openssl_pkey_get_public($public_key);
        openssl_public_encrypt($rands,$encrypted,$pu_key);
        $encryptKey=base64_encode($encrypted);

     //   $pem = chunk_split($public_key,64,"\n");//转换为pem格式的公钥
     //   $pem = "-----BEGIN PUBLIC KEY-----\n".$pem."-----END PUBLIC KEY-----\n";
     //   $publicKey = openssl_pkey_get_public($pem);//获取公钥内容
     //   openssl_public_encrypt($rands,$encryptKey,$publicKey,OPENSSL_PKCS1_PADDING);
     //   $encryptKey = base64_encode($encryptKey);//
        return $encryptKey;
    }
    /*
     * RSA公钥解密
     *
     */
    function rsaPublicDecode($public_key,$data){
        $pubkey=file_get_contents($public_key);
        $encryptKey =$data['encryptKey'];
        $pem1 = chunk_split(base64_encode($pubkey),64,"\n");
        $pem1 = "-----BEGIN CERTIFICATE-----\n".$pem1."-----END CERTIFICATE-----\n";
        $pi_key =  openssl_pkey_get_public($pem1);
        openssl_public_decrypt($encryptKey,$decrypted,$pem1);
        return base64_encode($decrypted);
    }
    /*
    * RSA私钥解密
    *
    */
    function rsaPrivateDecode($data,$private_key,$password){
        $prikey=file_get_contents($private_key);
        $encryptKey =$data['encryptKey'];
        $results=array();
        openssl_pkcs12_read($prikey,$results,$password);
        $private_key=$results['pkey'];
        $pi_key =  openssl_pkey_get_public($private_key);
        openssl_private_decrypt(base64_decode($encryptKey),$decrypted,$private_key);
        return $decrypted;
    }

    /*
     * RSA私钥签名
     */
    function rsaPrivateSign($data,$path,$password){
        $pubKey = file_get_contents($path);
        $results=array();
        openssl_pkcs12_read($pubKey,$results,$password);
        $private_key=$results['pkey'];
        $pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_sign($data, $signature,$private_key,"md5");
        $signature=base64_encode($signature);
        return $signature;
    }

    /*
     * RSA公钥验签
     *
     */
     function rsaPubilcSign($data,$path,$hmac){
         $public_key=file_get_contents($path);
         $pem1 = chunk_split(base64_encode($public_key),64,"\n");
         $pem1 = "-----BEGIN CERTIFICATE-----\n".$pem1."-----END CERTIFICATE-----\n";
         $pi_key =  openssl_pkey_get_public($pem1);
         $result=openssl_verify($data,base64_decode($hmac),$pem1,OPENSSL_ALGO_MD5);
         return $result;

     }

    /*
      * hamc签名验证
      *
      *
      */
    function hmacSign($encrypt_str,$path){

        if (empty($encrypt_str['hmac'])){
            return $encrypt_str;
        }
        $hmac = $encrypt_str['hmac'];
        unset($encrypt_str['hmac']);

        ksort($encrypt_str);
        $hmacSource = '';
        foreach($encrypt_str as $key => $value){
            if($value == '')
            {
                continue;
            }
            if (is_array($value)) {
                ksort($value);
                foreach ($value as $key2 => $value2) {
                    if($value2 == '')
                    {
                        continue;
                    }
                    if (is_object($value2)) {
                        $value2 = array_filter((array)$value2);
                        ksort($value2);
                        foreach ($value2 as $oKey => $oValue) {
                            if($oValue == '')
                            {
                                continue;
                            }
                            $oValue .= '#';
                            $hmacSource .= trim($oValue);

                        }
                    } else if(is_array($value2)){
                        ksort($value2);
                        foreach ($value2 as $key3 => $value3) {
                            if($value3 == '')
                            {
                                continue;
                            }
                            if (is_object($value3)) {
                                $value3 = array_filter((array)$value3);
                                ksort($value3);
                                foreach ($value3 as $oKey => $oValue) {
                                    if($oValue == '')
                                    {
                                        continue;
                                    }
                                    $oValue .= '#';
                                    $hmacSource .= trim($oValue);
                                }
                            } else{
                                $value3 .= '#';
                                $hmacSource .= trim($value3);
                            }
                        }
                    } else{
                        $value2 .= '#';
                        $hmacSource .= trim($value2);
                    }
                }
            } else {
                $value .= '#';
                $hmacSource .= trim($value);
            }
        }
         $sourceHmac=sha1($hmacSource, true);
        $hh=$this->rsaPubilcSign($sourceHmac,$path,$hmac);
        if ($hh==0||$hh==-1){
            return DataReturn('HMAC验证错误', -1);
        }
        return DataReturn('校验成功', 0, $encrypt_str);
    }



    /**
     * hmac 验证
     * @return mixed
     */
    public function NotifyCheckHmac($private_key,$data,$public_key,$password)
    {
        $prikey=file_get_contents($private_key);
        $encryptKey =$data['encryptKey'];
        $results=array();
        openssl_pkcs12_read($prikey,$results,$password);
        $private_key=$results['pkey'];
        $pi_key =  openssl_pkey_get_public($private_key);
        openssl_private_decrypt(base64_decode($encryptKey),$decrypted,$private_key);

        $date = json_encode($data['data']);
        $screct_key = $decrypted;
        $encrypt_str=  openssl_decrypt($date,"AES-128-ECB",$screct_key);
        $encrypt_str = preg_replace('/[\x00-\x1F]/','', $encrypt_str);
        $encrypt_str = json_decode($encrypt_str,true);

        if (empty($encrypt_str['hmac'])){
            return DataReturn('HMAC验证错误', -1);
        }
        $hmac = $encrypt_str['hmac'];
        unset($encrypt_str['hmac']);

        ksort($encrypt_str);
        $hmacSource = '';
        foreach($encrypt_str as $key => $value){
            if($value == '')
            {
                continue;
            }
            if (is_array($value)) {
                ksort($value);
                foreach ($value as $key2 => $value2) {
                    if($value2 == '')
                    {
                        continue;
                    }
                    if (is_array($value2)) {
                        $value2 = array_filter((array)$value2);
                        ksort($value2);
                        foreach ($value2 as $oKey => $oValue) {
                            if($oValue == '')
                            {
                                continue;
                            }
                            $oValue .= '#';
                            $hmacSource .= trim($oValue);
                        }
                    } else {
                        $value2 .= '#';
                        $hmacSource .= trim($value2);
                    }
                }
            } else {
                $value .= '#';
                $hmacSource .= trim($value);
            }
        }
        $sourceHmac=sha1($hmacSource, true);

        $public_key=file_get_contents($public_key);
        $pem1 = chunk_split(base64_encode($public_key),64,"\n");
        $pem1 = "-----BEGIN CERTIFICATE-----\n".$pem1."-----END CERTIFICATE-----\n";
        $pi_key =  openssl_pkey_get_public($pem1);
        $result=openssl_verify($sourceHmac,base64_decode($hmac),$pem1,OPENSSL_ALGO_MD5);

        if($result==0||$result==-1)
        {
            return DataReturn('签名错误', -1);
        }
        return DataReturn('校验成功', 0, $encrypt_str);
    }
}
?>