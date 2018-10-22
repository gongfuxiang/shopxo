<?php

namespace Library;

use Service\AlipayLifeService;

/**
 * 支付宝生活号
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-07-20
 */
class AlipayLife
{
    // 参数
    private $params;

    // xml
    private $xml_data;

    // 当前生活号数据
    private $life_data;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        $this->params = $params;
        $this->xml_data = isset($params['biz_content']) ? $this->xmlToArray($params['biz_content']) : '';
        $this->life_data = isset($this->xml_data['AppId']) ? AlipayLifeService::AppidLifeRow(['appid'=>$this->xml_data['AppId']]) : '';

        // 当前生活号是否存在
        if(empty($this->life_data))
        {
            file_put_contents('./ssssss.txt', 111);
            die('life error');
        }
    }

    /**
     * xml转属组
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-22
     * @desc    description
     * @param   [string]          $xmltext [xml数据]
     * @return  [array]                    [属组]
     */
    public function xmlToArray($xmltext)
    {
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xmltext, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 属组转url字符串
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-22
     * @desc    description
     * @param   [array]          $data [输入参数-数组]
     * @return  [string]               [url字符串]
     */
    public function ArrayToUrlString($data)
    {
        $ur_lstring = '';
        ksort($data);
        foreach($data AS $key=>$val)
        {
            if(!in_array($key, ['sign']))
            {
                $ur_lstring .= "$key=$val&";
            }
        }
        return substr($ur_lstring, 0, -1);
    }

    /**
     * 校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-22
     * @desc    description
     */
    public function Check()
    {
        if($this->OutRsaVerify($this->ArrayToUrlString($this->params), $this->params['sign']))
        {
            file_put_contents('./ssssss.txt', 222);
            $response_xml = '<success>true</success><biz_content>'.$this->life_data['rsa_public'].'</biz_content>';
        } else {
            file_put_contents('./ssssss.txt', 333);
            $response_xml = '<success>false</success><error_code>VERIFY_FAILED</error_code><biz_content>'.$this->life_data['rsa_public'].'</biz_content>';
        }
        $return_xml = '<?xml version="1.0" encoding="GBK"?>
                <alipay>
                    <response>
                        <biz_content>'.$this->life_data['rsa_public'].'</biz_content>
                        <success>true</success>
                    </response>
                    <sign>'.$this->MyRsaSign($response_xml).'</sign>
                    <sign_type>RSA2</sign_type>
                </alipay>';
                file_put_contents('./ssssss.txt', $return_xml);
        die($return_xml);
    }


    /**
     * [MyRsaSign 签名字符串]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:38:28+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @return   [string]                           [签名结果]
     */
    private function MyRsaSign($prestr)
    {
        $res = "-----BEGIN PRIVATE KEY-----\n";
        $res .= wordwrap($this->life_data['rsa_private'], 64, "\n", true);
        $res .= "\n-----END PRIVATE KEY-----";
$res = '-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC7EBdtmK19oAsN
p6+tZUhO94y7Z+Hcvy3en/lYY80RU6oKcve/wR5hlY4tK4EeGklotvtDces9bKfO
lwnYLXv4PEJVLcmzwn+ICCIkYkHh/zBm48HCoqdwZd2sC03oRincf0rUhB4KYY31
2Lge3uKAMNESdVatQhzVDo7rgoODxguFZpN/YUwW908qIf22KMhm6+1jHE5SQeaA
bo3v4gi8o8t1h17R2395PLovXBtdJahK1+YVh3fy/LC3nU3oCUKGH9cBCZQZhOrW
xb48VeDYysPLJYgRnEZvV0g2GUyBcyrFOuimjfWRdrT29R98289ZWZHfyJP2Vj6f
plZJXMfdAgMBAAECggEAXXHCYkscj169ZsrXZUTtBBWBRbS1DTKrVUSQqGjibb9f
d+zKeg2cgZ7V8RaEX2c+OIL/rUdg/cQjZ33nuwetn+lqMWa4FYYZcvitJYO36Y8y
vJMVnYbnIayhOWpENr2l97HWzaZZ41GsOp1SDInGl8bLCe93pwEZqgyltFv0GoSf
Nu3trFFxPZgZJalV0t5M7+RchutkHskwrwI9BdnCJs38lh08jHHppQdkgcpyCiCd
u/b4f+n9z97Op5Va8WY1M+wwqRk76Ias8mqwJXT/+t/sXhqkMv1ylAb89+b3rgiO
U7KlZMpIAercW/ZRojnDjpY9ViaCxwWPwb/VkPrDgQKBgQDkDuie0DAIDP5C74dP
j/Z0mapsU9bKlcgC+nowEUaEO7A9cwMVFal0x9p7BKIJsV2b6d1qJGP7rM9YtRMl
dJQmuxPcHOKPcZR8pGLqFYT2QGKGurohb/o+btGda/SGwJfi6jwQUF0AE+1k+Dj9
P3hDxHgkj6ZMkHEBtqUj520VTQKBgQDR+1rPPex8zTQgl9uSY0hlXPyYEhpXicNh
zyet1Su+TV8wdGNUr2YeuDHEu6oiRocBaT8DEwpy9EToe56EK3Ht2AQ76NBSUp9E
Ol1twocebM42etJSJZGpB1AgP+R/hmUbcBPXEwXdy5XeYnYmpVUcoizzKrnRDxg3
TRF3kIX00QKBgGc49EMFmefa8a6cOdNiJrvp3YBAhkSVfL0UX/+nohIx7fgyOV/u
uQ9ZceMiWrEmbWcneAcVx4dfVU4iTzMxy+in3jpPfKBOWVX9FaQ77z2CMNYoaBzA
UTS29ftZpIjlXRngySTdKurhGh8MVscRVj7eCz8JIc0fx3ZuE9rnYbE1AoGAJoqJ
L3LBPmL3x2e4IJVii2BW6J6iASFDIGfCc7Cl18chyqYCOV/8UXUjhWWgo6voScUE
kM7k4xacs0NFZCMJRUuZ81kXK5UIsKA519SVsmrsKqm+gt9sbebuuQyhJxsG4dNf
gOF3+S7N8kSGRS+hgKDvuS5Fbu7jVfsqUpTPUZECgYAcbq3mqWwExY2Kn0I660Oq
OFk620pGsSY7gECUQintCZioYemzC1TN9pM6fKnOIYriV4Ou7iswhEfVX+5bwMjH
2ujmu8KDdpkpdhRoFCw3GUn/PDelQrptaKkKXnOIJe/R8m+TUxYCtECTlKlYS4hT
st7YhTDz5sQHcXRtveATZQ==
-----END PRIVATE KEY-----
';
        $pkeyid = openssl_pkey_get_private($res);
        return openssl_sign($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
    }

    /**
     * [MyRsaDecrypt RSA解密]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T09:12:06+0800
     * @param    [string]                   $content [需要解密的内容，密文]
     * @return   [string]                            [解密后内容，明文]
     */
    private function MyRsaDecrypt($content)
    {
        $res = "-----BEGIN PUBLIC KEY-----\n";
        $res .= wordwrap($this->life_data['rsa_private'], 64, "\n", true);
        $res .= "\n-----END PUBLIC KEY-----";
        $res = openssl_get_privatekey($res);
        $content = base64_decode($content);
        $result  = '';
        for($i=0; $i<strlen($content)/128; $i++)
        {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res, OPENSSL_ALGO_SHA256);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * [OutRsaVerify 支付宝验证签名]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:39:50+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @param    [string]                   $sign   [签名结果]
     * @return   [boolean]                          [正确true, 错误false]
     */
    private function OutRsaVerify($prestr, $sign)
    {
        $res = "-----BEGIN PUBLIC KEY-----\n";
        $res .= wordwrap($this->life_data['out_rsa_public'], 64, "\n", true);
        $res .= "\n-----END PUBLIC KEY-----";
        $pkeyid = openssl_pkey_get_public($res);
        $sign = base64_decode($sign);
        if($pkeyid)
        {
            $verify = openssl_verify($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256);
            openssl_free_key($pkeyid);
        }
        return (isset($verify) && $verify == 1) ? true : false;
    }
}
?>