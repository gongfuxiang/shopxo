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
            $response_xml = '<success>true</success><biz_content>'.$this->life_data['rsa_public'].'</biz_content>';
        } else {
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
        $res = "-----BEGIN RSA PRIVATE KEY-----\n";
        $res .= wordwrap($this->life_data['rsa_private'], 64, "\n", true);
        $res .= "\nEND RSA PRIVATE KEY-----";
        return openssl_sign($prestr, $sign, $res, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
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