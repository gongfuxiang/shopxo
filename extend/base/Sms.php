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
namespace base;

/**
 * 短信驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Sms
{
    // 保存错误信息
    public $error;

    // Access Key ID
    private $accessKeyId = '';

    // Access Access Key Secret
    private $accessKeySecret = '';

    // 签名
    private $signName = '';

    // 模版ID
    private $templateCode = '';

    private $expire_time;
	private $key_code;

    /**
	 * [__construct 构造方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-07T14:03:02+0800
	 * @param    [int]        $param['interval_time'] 	[间隔时间（默认30）单位（秒）]
	 * @param    [int]        $param['expire_time'] 	[到期时间（默认30）单位（秒）]
	 * @param    [string]     $param['key_prefix'] 		[验证码种存储前缀key（默认 空）]
	 */
	public function __construct($param = array())
	{
		$this->interval_time = isset($param['interval_time']) ? intval($param['interval_time']) : 30;
		$this->expire_time = isset($param['expire_time']) ? intval($param['expire_time']) : 30;
		$this->key_code = isset($param['key_prefix']) ? trim($param['key_prefix']).'_sms_code' : '_sms_code';

		$this->signName = MyC('common_sms_sign');
		$this->accessKeyId = MyC('common_sms_apikey');
		$this->accessKeySecret = MyC('common_sms_apisecret');
	}

    private function percentEncode($string) {
        $string = urlencode ( $string );
        $string = preg_replace ( '/\+/', '%20', $string );
        $string = preg_replace ( '/\*/', '%2A', $string );
        $string = preg_replace ( '/%7E/', '~', $string );
        return $string;
    }
    /**
     * 签名
     *
     * @param unknown $parameters            
     * @param unknown $accessKeySecret            
     * @return string
     */
    private function computeSignature($parameters, $accessKeySecret) {
        ksort ( $parameters );
        $canonicalizedQueryString = '';
        foreach ( $parameters as $key => $value ) {
            $canonicalizedQueryString .= '&' . $this->percentEncode ( $key ) . '=' . $this->percentEncode ( $value );
        }
        $stringToSign = 'GET&%2F&' . $this->percentencode ( substr ( $canonicalizedQueryString, 1 ) );
        $signature = base64_encode ( hash_hmac ( 'sha1', $stringToSign, $accessKeySecret . '&', true ) );
        return $signature;
    }

    /**
     * 短信发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-04-02
     * @desc    description
     * @param   [string]            $mobile        [手机号码，多个以 英文逗号 , 分割]
     * @param   [string|array]      $code          [变量code（单个直接传入 code 即可，多个传入数组）]
     * @param   [string]            $template_code [模板 id]
     * @param   [boolean]           $sign_name     [自定义签名，默认使用基础配置的签名]
     */
    public function SendCode($mobile, $code, $template_code, $sign_name = '')
    {
    	// 单个验证码需要校验是否频繁
        if(is_string($code))
        {
            // 是否频繁操作
            if(!$this->IntervalTimeCheck())
            {
                $this->error = '防止造成骚扰，请勿频繁发送';
                return false;
            }
            $codes = ['code'=>$code];
        } else {
            $codes = $code;
        }

        // 签名
        $SignName = empty($sign_name) ? $this->signName : $sign_name;

        // 请求参数
        $params = array (   //此处作了修改
                'SignName' => $SignName,
                'Format' => 'JSON',
                'Version' => '2017-05-25',
                'AccessKeyId' => $this->accessKeyId,
                'SignatureVersion' => '1.0',
                'SignatureMethod' => 'HMAC-SHA1',
                'SignatureNonce' => uniqid (),
                'Timestamp' => gmdate ( 'Y-m-d\TH:i:s\Z' ),
                'Action' => 'SendSms',
                'TemplateCode' => $template_code,
                'PhoneNumbers' => $mobile,
                'TemplateParam' => json_encode($codes, JSON_UNESCAPED_UNICODE),
        );
        //print_r($params);die;
        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature ( $params, $this->accessKeySecret );
        // 发送请求（此处作了修改）
        //$url = 'https://sms.aliyuncs.com/?' . http_build_query ( $params );
        $url = 'http://dysmsapi.aliyuncs.com/?' . http_build_query ( $params );
        
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        $result = json_decode ( $result, true );
        //print_r($result);//die;
        if (isset ( $result ['Code'] ) && $result['Code'] != 'OK') {
            $this->error = $this->getErrorMessage ( $result ['Code'] );
            return false;
        }

        // 种session
        if(is_string($code))
        {
            $this->KindofSession($code);
        }

        return true;
    }
    /**
     * 获取详细错误信息
     *
     * @param unknown $status            
     */
    public function getErrorMessage($status) {
        // 阿里云的短信 乱八七糟的(其实是用的阿里大于)
        // https://api.alidayu.com/doc2/apiDetail?spm=a3142.7629140.1.19.SmdYoA&apiId=25450
        $message = array (
                'InvalidDayuStatus.Malformed' => '账户短信开通状态不正确',
                'InvalidSignName.Malformed' => '短信签名不正确或签名状态不正确',
                'InvalidTemplateCode.MalFormed' => '短信模板Code不正确或者模板状态不正确',
                'InvalidRecNum.Malformed' => '目标手机号不正确，单次发送数量不能超过100',
                'InvalidParamString.MalFormed' => '短信模板中变量不是json格式',
                'InvalidParamStringTemplate.Malformed' => '短信模板中变量与模板内容不匹配',
                'InvalidSendSms' => '触发业务流控',
                'InvalidDayu.Malformed' => '变量不能是url，可以将变量固化在模板中',
                'isv.RAM_PERMISSION_DENY' => 'RAM权限DENY',
                'isv.OUT_OF_SERVICE' => '业务停机',
                'isv.PRODUCT_UN_SUBSCRIPT' => '未开通云通信产品的阿里云客户',
                'isv.PRODUCT_UNSUBSCRIBE' => '产品未开通',
                'isv.ACCOUNT_NOT_EXISTS' => '账户不存在',
                'isv.ACCOUNT_ABNORMAL' => '账户异常',
                'isv.SMS_TEMPLATE_ILLEGAL' => '短信模板不合法',
                'isv.SMS_SIGNATURE_ILLEGAL' => '短信签名不合法',
                'isv.INVALID_PARAMETERS' => '参数异常',
                'isv.SYSTEM_ERROR' => '系统错误',
                'isv.MOBILE_NUMBER_ILLEGAL' => '非法手机号',
                'isv.MOBILE_COUNT_OVER_LIMIT' => '手机号码数量超过限制',
                'isv.TEMPLATE_MISSING_PARAMETERS' => '模板缺少变量',
                'isv.BUSINESS_LIMIT_CONTROL' => '业务限流',
                'isv.INVALID_JSON_PARAM' => 'JSON参数不合法，只接受字符串值',
                'isv.BLACK_KEY_CONTROL_LIMIT' => '黑名单管控',
                'isv.PARAM_LENGTH_LIMIT' => '参数超出长度限制',
                'isv.PARAM_NOT_SUPPORT_URL' => '不支持URL',
                'isv.AMOUNT_NOT_ENOUGH' => '账户余额不足',
        );
        if (isset ( $message [$status] )) {
            return $message [$status];
        }
        return $status;
    }

    /**
     * [KindofSession 种验证码session]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-07T14:59:13+0800
     * @param    [string]      $code [验证码]
     */
    private function KindofSession($code)
    {
        $data = array(
                'code' => $code,
                'time' => time(),
            );
        cache($this->key_code, $data, $this->expire_time);
    }

    /**
     * [CheckExpire 验证码是否过期]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-05T19:02:26+0800
     * @return   [boolean] [有效true, 无效false]
     */
    public function CheckExpire()
    {
        $data = cache($this->key_code);
        if(!empty($data))
        {
            return (time() <= $data['time']+$this->expire_time);
        }
        return false;
    }

	/**
	 * [CheckCorrect 验证码是否正确]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:00+0800
	 * @param    [string] $code    [验证码（默认从post读取）]
	 * @return   [booolean]        [正确true, 错误false]
	 */
	public function CheckCorrect($code = '')
	{
		$data = cache($this->key_code);
		if(!empty($data))
		{
			if(empty($code) && isset($_POST['code']))
			{
				$code = trim($_POST['code']);
			}
			return ($data['code'] == $code);
		}
		return false;
	}

	/**
	 * [Remove 验证码清除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-08T10:18:20+0800
	 * @return   [other] [无返回值]
	 */
	public function Remove()
	{
		cache($this->key_code, null);
	}

	/**
	 * [IntervalTimeCheck 是否已经超过控制的间隔时间]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T11:26:52+0800
	 * @return   [booolean]        [已超过间隔时间true, 未超过间隔时间false]
	 */
	private function IntervalTimeCheck()
	{
		$data = cache($this->key_code);
		if(!empty($data))
		{
			return (time() > $data['time']+$this->interval_time);
		}
		return true;
	}
}
?>