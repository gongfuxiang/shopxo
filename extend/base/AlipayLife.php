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

use app\service\AlipayLifeService;

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
        $this->xml_data = isset($params['biz_content']) ? $this->XmlToArray($params['biz_content']) : '';
        
        // 生活号
        if(!empty($params['life_data']))
        {
            $this->life_data = $params['life_data'];
        } else {
            $this->life_data = isset($this->xml_data['AppId']) ? AlipayLifeService::AppidLifeRow(['appid'=>$this->xml_data['AppId']]) : '';
        }
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
        $res .= "\n-----END RSA PRIVATE KEY-----";
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
    public function XmlToArray($xmltext)
    {
        $xmltext = iconv("GBK", "UTF-8", urldecode($xmltext));
        $objectxml = simplexml_load_string($xmltext, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmljson = json_encode($objectxml);
        return json_decode($xmljson, true);
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
        $url_string = '';
        ksort($data);
        foreach($data AS $key=>$val)
        {
            if(!in_array($key, ['sign']))
            {
                $url_string .= "$key=$val&";
            }
        }
        return substr($url_string, 0, -1);
    }

    /**
     * 返回操作状态
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-23T01:07:28+0800
     * @param    boolean                  $status [description]
     */
    public function Respond($status = false)
    {
        if($status === true)
        {
            $response_xml = '<biz_content>'.$this->life_data['rsa_public'].'</biz_content><success>true</success>';
        } else {
            $response_xml = '<error_code>VERIFY_FAILED</error_code><biz_content>'.$this->life_data['rsa_public'].'</biz_content><success>false</success>';
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
        $encode_array = [];
        $post_multipart = false;
        if(is_array($data) && 0 < count($data))
        {
            foreach($data as $k => $v)
            {
                if ('@' != substr($v, 0, 1))
                {
                    $body_string .= $k.'='.urlencode($v).'&';
                    $encode_array[$k] = $v;
                } else {
                    $post_multipart = true;
                    $encode_array[$k] = new \CURLFile(substr($v, 1));
                }
            }
        }

        curl_setopt($ch, CURLOPT_POST, true);
        if($post_multipart === true)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encode_array);

            list($s1, $s2) = explode(' ', microtime());
            $millisecond = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
            $headers = array('content-type: multipart/form-data;charset=UTF-8;boundary='.$millisecond);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, substr($body_string, 0, -1));
            $headers = array('content-type: application/x-www-form-urlencoded;charset=UTF-8');
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
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
     * 校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-22
     * @desc    description
     */
    public function Check()
    {
        // 当前生活号是否存在
        if(empty($this->life_data))
        {
            die('life error');
        }

        // 开始处理
        $status = $this->OutRsaVerify($this->ArrayToUrlString($this->params), $this->params['sign']);
        $this->Respond($status);
    }

    /**
     * 生活号事件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-23T00:38:21+0800
     */
    public function Life()
    {
        // 当前生活号是否存在
        if(empty($this->life_data))
        {
            die('life error');
        }

        // 开始处理
        $status = false;
        if($this->OutRsaVerify($this->ArrayToUrlString($this->params), $this->params['sign']))
        {
            $userinfo = empty($this->xml_data['UserInfo']) ? '' : json_decode($this->xml_data['UserInfo'], true);
            $data = [
                'appid'             => $this->xml_data['AppId'],
                'alipay_openid'     => $this->xml_data['FromAlipayUserId'],
                'user_id'           => empty($this->xml_data['FromUserId']) ? '' : $this->xml_data['FromUserId'],
                'logon_id'          => empty($userinfo['logon_id']) ? '' : $userinfo['logon_id'],
                'user_name'         => empty($userinfo['user_name']) ? '' : $userinfo['user_name'],
            ];
            switch($this->xml_data['EventType'])
            {
                // 取消关注
                case 'unfollow' :
                    $status = AlipayLifeService::UserUnfollow($data);
                    break;

                // 关注/进入生活号
                case 'follow' :
                case 'enter' :
                    $status = AlipayLifeService::UserEnter($data);
                    break;
            }
        }
        $this->Respond($status);
    }

    /**
     * [SyncRsaVerify 同步返回签名验证]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T13:13:39+0800
     * @param    [array]                   $data [返回数据]
     * @param    [boolean]                 $key  [数据key]
     */
    private function SyncRsaVerify($data, $key)
    {
        $string = json_encode($data[$key], JSON_UNESCAPED_UNICODE);
        return $this->OutRsaVerify($string, $data['sign']);
    }

    /**
     * 获取公共参数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    private function RequestCommonParams()
    {
        return [
            'app_id'        => $this->life_data['appid'],
            'format'        => 'JSON',
            'charset'       => 'utf-8',
            'sign_type'     => 'RSA2',
            'timestamp'     => date('Y-m-d H:i:s'),
            'version'       => '1.0',
        ];
    }

    /**
     * 消息发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function MessageSend($params = [])
    {
        // 参数处理
        $p = $this->RequestCommonParams();
        $p['method'] = $params['method'];
        $biz_content = [
            'msg_type'      => ($params['msg_type'] == 0) ? 'text' : 'image-text',
            'chat'          => isset($params['chat']) ? intval($params['chat']) : 0,
        ];

        // 消息内容
        if(empty($params['content']) || !is_array($params['content']))
        {
            return ['status'=>-1, 'msg'=>'消息内容有误'];
        }

        // 发送类型 单条
        if($params['send_type'] == 0)
        {
            if(empty($params['alipay_openid']))
            {
                return ['status'=>-1, 'msg'=>'指定用户openid不能为空'];
            }
            $biz_content['to_user_id'] = $params['alipay_openid'];
        }

        // 消息类型
        if($params['msg_type'] == 1)
        {
            $biz_content['articles'] = [];
            foreach($params['content'] as $k=>$v)
            {
                if($k < 10)
                {
                    $biz_content['articles'][] = [
                        'title'         => $v['title'],
                        'desc'          => $v['content'],
                        'image_url'     => $v['out_image_url'],
                        'url'           => $v['url'],
                        'action_name'   => $v['action_name'],
                    ];
                }
            }
        } else {
            $biz_content['text'] = ['content'=>$params['content'][0]['content']];
        }
        $p['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名
        $p['sign'] = $this->MyRsaSign($this->ArrayToUrlString($p));

        // 请求接口
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $p);
        $key = str_replace('.', '_', $p['method']).'_response';

        // 验证签名
        if(!$this->SyncRsaVerify($result, $key))
        {
            return ['status'=>-1, 'msg'=>'签名验证错误'];
        }

        // 状态
        if(isset($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            return ['status'=>0, 'msg'=>'发送成功'];
        }
        return ['status'=>-100, 'msg'=>$result[$key]['sub_msg'].'['.$result[$key]['code'].']'];
    }

    /**
     * 单条消息发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function CustomSend($params = [])
    {
        $params['method'] = 'alipay.open.public.message.custom.send';
        return $this->MessageSend($params);
    }

    /**
     * 群发消息发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function GroupSend($params = [])
    {
        $params['method'] = 'alipay.open.public.message.total.send';
        return $this->MessageSend($params);
    }

    /**
     * 图片上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-25
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function UploadImage($params = [])
    {
        // 参数校验
        if(empty($params['file']))
        {
            return ['status'=>-1, 'msg'=>'图片地址有误'];
        }

        // 参数处理
        $p = $this->RequestCommonParams();
        $p['method'] = 'alipay.offline.material.image.upload';

        // 图片参数
        $p['image_type'] = isset($params['image_type']) ? $params['image_type'] : 'jpg';
        $p['image_name'] = isset($params['image_name']) ? $params['image_name'] : 'image';

        // 生成签名
        $p['sign'] = $this->MyRsaSign($this->ArrayToUrlString($p));

        // 图片内容不参与签名
        $p['image_content'] = '@'.$params['file'];

        // 请求接口
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $p);

        // 状态
        if(isset($result['alipay_offline_material_image_upload_response']['code']) && $result['alipay_offline_material_image_upload_response']['code'] == 10000)
        {
            return ['status'=>0, 'msg'=>'上传成功', 'data'=>$result['alipay_offline_material_image_upload_response']['image_url']];
        }
        return ['status'=>-100, 'msg'=>$result['alipay_offline_material_image_upload_response']['sub_msg'].'['.$result['alipay_offline_material_image_upload_response']['code'].']', 'data'=>''];
    }

    /**
     * 上架生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function LifeStatus($params = [])
    {
        // 参数处理
        $p = $this->RequestCommonParams();
        $p['method'] = $params['method'];
        $biz_content = [];
        $p['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名
        $p['sign'] = $this->MyRsaSign($this->ArrayToUrlString($p));

        // 请求接口
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $p);
        $key = str_replace('.', '_', $p['method']).'_response';

        // 验证签名
        if(!$this->SyncRsaVerify($result, $key))
        {
            return ['status'=>-1, 'msg'=>'签名验证错误'];
        }

        // 状态
        if(isset($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            return ['status'=>0, 'msg'=>'操作成功'];
        }

        // 是否相同状态下操作
        if(stripos($result[$key]['sub_code'], 'LIFE_ALREADY_DEBARK') !== false || stripos($result[$key]['sub_code'], 'LIFE_ALREADY_ON_PLATFORM') !== false)
        {
            return ['status'=>0, 'msg'=>$result[$key]['sub_msg'].'['.$result[$key]['code'].']'];
        }

        return ['status'=>-100, 'msg'=>$result[$key]['sub_msg'].'['.$result[$key]['code'].']'];
    }

    /**
     * 上架生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function LifeAboard($params = [])
    {
        $params['method'] = 'alipay.open.public.life.aboard.apply';
        return $this->LifeStatus($params);
    }

    /**
     * 下架生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function LifeDebark($params = [])
    {
        $params['method'] = 'alipay.open.public.life.debark.apply';
        return $this->LifeStatus($params);
    }

    /**
     * 菜单发布
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $data [输入参数]
     */
    public function MenuRelease($params = [])
    {
        // 参数处理
        $p = $this->RequestCommonParams();
        $p['method'] = empty($params['method']) ? 'alipay.open.public.menu.modify' : $params['method'];
        $biz_content = [
            'type'      => ($params['type'] == 1) ? 'icon' : 'text',
            'button'    => [],
        ];
        foreach($params['content'] as $v)
        {
            $button = [
                'name'          => mb_substr($v['name'], 0, 5, 'utf-8'),
                'action_type'   => $v['action_type'],
                'action_param'  => $v['action_value'],
            ];

            // 是否为图标
            if($params['type'] == 1)
            {
                $button['icon'] = $v['out_icon'];
            }

            // 不为图标并且存在二级
            if($params['type'] != 1 && !empty($v['items']))
            {
                foreach($v['items'] as $vs)
                {
                    $button['sub_button'][] = [
                        'name'          => mb_substr($vs['name'], 0, 5, 'utf-8'),
                        'action_type'   => $vs['action_type'],
                        'action_param'  => $vs['action_value'],
                    ];
                }
            }
            $biz_content['button'][] = $button;
        }
        $p['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名
        $p['sign'] = $this->MyRsaSign($this->ArrayToUrlString($p));

        // 请求接口
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $p);
        $key = str_replace('.', '_', $p['method']).'_response';

        // 验证签名
        if(!$this->SyncRsaVerify($result, $key))
        {
            return ['status'=>-1, 'msg'=>'签名验证错误'];
        }

        // 状态
        if(isset($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            return ['status'=>0, 'msg'=>'操作成功'];
        }

        // 错误是否为菜单不存在,则重新调用创建接口
        if(stripos($result[$key]['sub_code'], 'MENU_MODIFY_NOT_EXIST') !== false)
        {
            $params['method'] = 'alipay.open.public.menu.create';
            return $this->MenuRelease($params);
        }

        // 失败
        return ['status'=>-100, 'msg'=>$result[$key]['sub_msg'].'['.$result[$key]['code'].'-'.$result[$key]['sub_code'].']'];
    }

}
?>