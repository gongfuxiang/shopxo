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
            [
                'element'       => 'select',
                'placeholder'   => '收银台类型',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>'STANDARD', 'name'=>'标准版'],
                    ['value'=>'DECLARE', 'name'=>'申报版'],
                    ['value'=>'CUSTOMS', 'name'=>'海淘版按照收银台类型值判断'],
                    ['value'=>'DECLARE', 'name'=>'需要同时提交身份信息和贸易 背景。STANDARD 不需要提交身份信息和 贸易背景。CUSTOMS 同申报版相似，区 别在于可不传银行卡号'],
                ],
                'name'          => 'cashierVersion',
                'title'         => '收银台类型',
                'is_required'   => 0,
                'message'       => '请选择收银台类型',
            ],
            [
                'element'       => 'select',
                'placeholder'   => '贸易背景',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>'GOODSTRADE', 'name'=>'货物贸易'],
                    ['value'=>'PLANETICKET', 'name'=>'机票'],
                    ['value'=>'HOTELACCOMMODATIO', 'name'=>'酒店'],
                    ['value'=>'STUDYABROAD', 'name'=>' 留学']
                ],
                'name'          => 'forUse',
                'title'         => '贸易背景',
                'is_required'   => 0,
                'message'       => '请选择贸易背景',
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
        $params['notify_url'] = 'https://test.shopxo.net/
    [notifyUrl] => http://tp5-dev.com/payment_order_payease_notify.php';
        $params['call_back_url'] = 'https://test.shopxo.net/payment_order_payease_respond.php';

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
            'orderAmount'       => $params['total_price']/100,
            'orderCurrency'     => ' CNY',
            'requestId'         => $params['order_no'],
            'notifyUrl'         => $params['notify_url'],
            'callbackUrl'       => $params['call_back_url'],
            'cashierVersion'    => $this->config['cashierVersion'],
            'forUse'            => $this->config['forUse'],
        ];
        $payer = [
            'name'  => '龚福祥',
            'phoneNum'  => '17602128368',
            'idType'    => 'IDCARD',
            'idNum'     => '522228199102111214',
        ];
        $data['payer'] = json_encode($payer, JSON_UNESCAPED_UNICODE);
        $data['payer'] = $payer;
        $detail = [
            [
                'name'      => '新款苹果手机iphone6s',
                'quantity'  => 1,
                'amount'    => $params['total_price']/100,
            ]
        ];
        $data['productDetails'] = json_encode($detail, JSON_UNESCAPED_UNICODE);
        $data['productDetails'] = $detail;

        $private_key = ROOT.'rsakeys/client.pfx';
        $public_key = ROOT.'rsakeys/server.cer';
        $str = $this->buildJson($private_key, $this->config['password'], $data);
        $date = $this->creatdate($str, $public_key);
        //print_r($str);die;

        $url = 'https://apis.5upay.com/onlinePay/order';
        $ret = $this->execute(
            $private_key,
            $this->config['password'],
            $public_key,
            $url,
            $date
        );
        echo '<pre>';
        print_r($ret);die;

        
        return 100;
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
        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        ksort($data);

        // 参数字符串
        $prestr = '';
        foreach($data AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $prestr .= "$key=$val&";
            }
        }
        $prestr = substr($prestr, 0, -1);

        // 签名
        if(!$this->OutRsaVerify($prestr, $data['sign']))
        {
            return DataReturn('签名校验失败', -1);
        }

        // 支付状态
        if(!empty($data['trade_no']) || (isset($data['trade_status']) && in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])))
        {
            return DataReturn('支付成功', 0, $this->ReturnData($data));
        }
        return DataReturn('处理异常错误', -100);
    }



    public function creatdate($strdata,$public_key) {
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
        /*
       * 去除空值的元素
       */

        function clearBlank($arr)
        {
            return $arr;
            function odd($var)
            {
                return($var<>'');//return true or false
            }
            return (array_filter($arr, "odd"));
        }

        function array_remove_empty(& $arr, $trim = true){
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    array_remove_empty($arr[$key]);
                } else {
                    $value = trim($value);
                    if ($value == '') {
                        unset($arr[$key]);
                    } elseif ($trim) {
                        $arr[$key] = $value;
                    }
                }
            }
        }
        $encrypt_str = clearBlank($encrypt_str);
        return $this->hmacSign($encrypt_str,$public_key);
    }


    public function execute($private_key,$password,$public_key,$url, $param)
    {
        $data = $this->httpRequestPost($url, $param,$public_key,$password,$private_key);
        $this->handle($data);
        return $data;
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
            return $data;
        }else if(isset($data['status']) && $data['status'] == 'INIT'){
            return $data;
        }else{
            return array(
                'error_description'=>'Response Error',
                'responseData'=>$data
            );
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
            throw new InvalidRequestException(array(
                'error_description'=> 'Request Error'
            ));
        }
        curl_close($curl);
        preg_match_all('/(encryptKey|merchantId|data"):(\s+|")([^"\s]+)/s',$responseText,$m);
        list($encryptKey, $merchantId, $data) = $m[3];
        $responsedata = array("data" =>$data,"encryptKey"=>$encryptKey,"merchantId"=>$merchantId);
        if ($responsedata['merchantId'] == null){
            throw new InvalidRequestException(array(
                'error_description'=>'Request error',
                'responseData'=>$responseText
            ));
        }
        $date = $this->checkHmac($private_key,$public_key,$responsedata,$password);
        return $date;
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
            }else if(is_object($var) && $var instanceof AbstractModel){
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
            if (is_array($value)) {
                ksort($value);
                foreach ($value as $key2 => $value2) {

                    if (is_object($value2)) {
                        $value2 = array_filter((array)$value2);
                        ksort($value2);
                        foreach ($value2 as $oKey => $oValue) {
                            $oValue .= '#';
                            $hmacSource .= trim($oValue);

                        }
                    } else if(is_array($value2)){
                        ksort($value2);
                        foreach ($value2 as $key3 => $value3) {
                            if (is_object($value3)) {
                                $value3 = array_filter((array)$value3);
                                ksort($value3);
                                foreach ($value3 as $oKey => $oValue) {
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
            throw new HmacVerifyException(array(
                'error_description'=>'hmac validation error'
            ));
        }
        return $encrypt_str;
    }
}
?>