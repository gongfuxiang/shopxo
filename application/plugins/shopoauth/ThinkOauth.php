<?php

namespace app\plugins\shopoauth;

use app\service\PluginsService;
use app\plugins\shopoauth\LoginEvent;

/**
 * ThinkOauth 
 * @author   Guoguo
 * @blog     http://gadmin.cojz8.com
 * @version  1.0.0
 * @datetime 2019年3月14日
 */

abstract class ThinkOauth
{

    /**
     * oauth版本
     * @var string
     */
    protected $Version = '3.0';

    /**
     * 申请应用时分配的app_key
     * @var string
     */
    protected $AppKey = '';

    /**
     * 申请应用时分配的 app_secret
     * @var string
     */
    protected $AppSecret = '';

    /**
     * 授权类型 response_type 目前只能为code
     * @var string
     */
    protected $ResponseType = 'code';

    /**
     * grant_type 目前只能为 authorization_code
     * @var string
     */
    protected $GrantType = 'authorization_code';

    /**
     * 回调页面URL  可以通过配置文件配置
     * @var string
     */
    protected $Callback = '';

    /**
     * 获取request_code的额外参数 URL查询字符串格式
     * @var srting
     */
    protected $Authorize = '';

    /**
     * 获取request_code请求的URL
     * @var string
     */
    protected $GetRequestCodeURL = '';

    /**
     * 获取access_token请求的URL
     * @var string
     */
    protected $GetAccessTokenURL = '';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = '';

    /**
     * 授权后获取到的TOKEN信息
     * @var array
     */
    protected $Token = null;

    /**
     * 调用接口类型
     * @var string
     */
    private $Type = '';

    /**
     * 构造方法，配置应用信息
     * @param array $token
     */
    public function __construct($token = null)
    {
        $class = get_class($this);
		$this->Type = explode('\\', strtoupper(substr($class, 0, strlen($class) - 3)))[4];
		$ret = PluginsService::PluginsData('shopoauth');
		foreach($ret['data']['auth'] as $k=>$v){
			if($v['open']==1){
				$configs[strtoupper($k)] = $v;
				$configs[strtoupper($k)]['CALLBACK'] = PluginsHomeUrl('shopoauth', 'Auth', 'callback',['type'=>$k]);
			}
		}
		$config =$configs[$this->Type];

        if (empty($config['app_key']) || empty($config['app_secret'])) {
            throw new \think\Exception('请配置您申请的APP_KEY和APP_SECRET', 100001);
        } else {
            $this->AppKey = $config['app_key'];
            $this->AppSecret = $config['app_secret'];
            $this->Token = $token; //设置获取到的TOKEN
        }
    }

    /**
     * 取得Oauth实例
     * @static
     * @return mixed 返回Oauth
     */
    public static function getInstance($type, $token = null)
    {
        $name = ucfirst(strtolower($type)) . 'SDK';
        if (class_exists("app\plugins\shopoauth\sdk\\{$name}")) {
            $class_name = "\app\plugins\shopoauth\sdk\\{$name}";
            return new $class_name($token);
        } else {
            throw new \think\Exception('CLASS_NOT_EXIST:' . $name, 100002);
        }
    }

    /**
     * 请求code
     */
    public function getRequestCodeURL()
    {
        $this->config();
        //Oauth 标准参数
        $params = array(
            'client_id' => $this->AppKey,
            'redirect_uri' => $this->Callback,
            'response_type' => $this->ResponseType,
        );
        //获取额外参数
        if ($this->Authorize) {
            parse_str($this->Authorize, $_param);
            if (is_array($_param)) {
                $params = array_merge($params, $_param);
            } else {
                throw new \think\Exception('AUTHORIZE配置不正确！',100003);
            }
        }
        return $this->GetRequestCodeURL . '?' . http_build_query($params);
    }

    /**
     * 初始化配置
     */
    private function config()
    {
        $class = get_class($this);
		$this->Type = explode('\\', strtoupper(substr($class, 0, strlen($class) - 3)))[4];
		$ret = PluginsService::PluginsData('shopoauth');
		foreach($ret['data']['auth'] as $k=>$v){
			if($v['open']==1){
				$configs[strtoupper($k)] = $v;
				$configs[strtoupper($k)]['CALLBACK'] = PluginsHomeUrl('shopoauth', 'Auth', 'callback',['type'=>$k]);
			}
		}
		$config =$configs[$this->Type];
        if (!empty($config['AUTHORIZE']))
            $this->Authorize = $config['AUTHORIZE'];
        if (!empty($config['CALLBACK']))
            $this->Callback = $config['CALLBACK'];
        else
            throw new \think\Exception('请配置回调页面地址',100004);
    }

    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     *      $code = $_GET['code']
     */
    public function getAccessToken($code, $extend = null)
    {
        $this->config();
        $params = array(
            'client_id' => $this->AppKey,
            'client_secret' => $this->AppSecret,
            'grant_type' => $this->GrantType,
            'code' => $code,
            'redirect_uri' => $this->Callback,
        );
        $data = $this->http($this->GetAccessTokenURL, $params, 'POST');
        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }

    /**
     * 发送HTTP请求方法，目前只支持CURL发送请求
     * @param  string $url 请求URL
     * @param  array $params 请求参数
     * @param  string $method 请求方法GET/POST
     * @return array  $data   响应数据
     */
    protected function http($url, $params, $method = 'GET', $header = array(), $multi = false)
    {
        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $header
        );

        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new \think\Exception('不支持的请求方式！',100005);
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error)
            throw new \think\Exception('请求发生错误：' . $error,100006);
        return $data;
    }

    /**
     * 抽象方法，在SNSSDK中实现
     * 解析access_token方法请求后的返回值
     */
    abstract protected function parseToken($result, $extend);

    /**
     * 抽象方法，在SNSSDK中实现
     * 获取当前授权用户的SNS标识
     */
    abstract public function openid();

    /**
     * 合并默认参数和额外参数
     * @param array $params 默认参数
     * @param array /string $param 额外参数
     * @return array:
     */
    protected function param($params, $param)
    {
        if (is_string($param))
            parse_str($param, $param);
        return array_merge($params, $param);
    }

    /**
     * 获取指定API请求的URL
     * @param  string $api API名称
     * @param  string $fix api后缀
     * @return string      请求的完整URL
     */
    protected function url($api, $fix = '')
    {
        return $this->ApiBase . $api . $fix;
    }

    /**
     * 抽象方法，在SNSSDK中实现
     * 组装接口调用参数 并调用接口
     */
    abstract protected function call($api, $param = '', $method = 'GET', $multi = false);
}
