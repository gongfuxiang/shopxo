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

// 系统版本
define('APPLICATION_VERSION', 'v6.5.0');

// 检测PHP环境
if(version_compare(PHP_VERSION, '8.0.2','<'))
{
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type:text/html;charset=utf-8');
    die('<div style="margin-top:15%;text-align:center;font-size:16px;padding:0 10px;"><p><a href="https://shopxo.net/" target="_blank" title="ShopXO电商系统" style="font-size:36px;font-weight:bold;text-decoration:none;"><span style="color:#026ed9;">Shop</span><span style="color:#f00;">XO</span></a> <span style="color:#888;">'.APPLICATION_VERSION.'</span></p><p style="color:#f00;margin-top:10px;">需要PHP版本最低 v8.0.2，当前PHP版本 v'.PHP_VERSION.'</p></div>');
}

// 定义系统目录分隔符
define('DS', '/');

// HTTP类型
define('__MY_HTTP__', (
    (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)
    || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && (strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off' || $_SERVER['HTTP_FRONT_END_HTTPS'] == 'https'))
    || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    || (!empty($_SERVER['HTTP_FROM_HTTPS']) && $_SERVER['HTTP_FROM_HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME'] == 'https')
    || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
) ? 'https' : 'http');

// 根目录
$my_root = '';
if(!empty($_SERVER['SCRIPT_NAME']))
{
    $index_pos = strpos($_SERVER['SCRIPT_NAME'], 'index.php');
    if($index_pos !== false)
    {
        $my_root = substr($_SERVER['SCRIPT_NAME'], 1, $index_pos-1);
    } else {
        $my_root = substr($_SERVER['SCRIPT_NAME'], 1, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }
}
define('__MY_ROOT__', defined('IS_ROOT_ACCESS') ? $my_root : str_replace('public'.DS, '', $my_root));
define('__MY_ROOT_PUBLIC__', defined('IS_ROOT_ACCESS') ? DS.$my_root.'public'.DS : DS.$my_root);

// 当前服务器ip
define('__MY_ADDR__', empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR']);

// 主域名，排除ipv4、ipv6
if(empty($_SERVER['HTTP_HOST']) || is_numeric(str_replace('.', '', $_SERVER['HTTP_HOST'])) || substr_count($_SERVER['HTTP_HOST'], ':') > 1)
{
    $main_domain = '';
} else {
    $main_domain = strtolower($_SERVER['HTTP_HOST']);
    // 去掉端口
    if(stripos($main_domain, ':') !== false)
    {
        $arr = explode(':', $main_domain);
        $main_domain = $arr[0];
    }
    // 是否ip
    if(is_numeric(str_replace('.', '', $main_domain)))
    {
        $main_domain = '';
    } else {
        // 查看是几级域名
        $arr = explode('.', $main_domain);
        $len = count($arr);
        if($len < 2)
        {
            $main_domain = '';
        } else {
            // 判断是否是双后缀
            $preg = '/[\w].+\.(com|net|org|gov|ac|bj|sh|tj|cq|he|sn|sx|nm|ln|jl|hl|js|zj|ah|fj|jx|sd|ha|hb|hn|gd|gx|hi|sc|gz|yn|gs|qh|nx|xj|tw|hk|mo|xz|edu|ge|dev|co)\.(cn|nz|mm|ec|my|kz|sg)$/';
            if($len > 2 && preg_match($preg, $main_domain))
            {
                // 双后缀取后3位
                $main_domain = $arr[$len-3].'.'.$arr[$len-2].'.'.$arr[$len-1];
            } else {
                // 非双后缀取后两位
                $main_domain = $arr[$len-2].'.'.$arr[$len-1];
            }
        }
    }
}
define('__MY_MAIN_DOMAIN__', $main_domain);

// 项目HOST
define('__MY_HOST__', empty($_SERVER['HTTP_HOST']) ? '' : strtolower($_SERVER['HTTP_HOST']));

// 项目HOST地址
define('__MY_DOMAIN__',  empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__.'://'.__MY_HOST__.DS);

// 项目完整HOST地址
define('__MY_URL__',  empty($_SERVER['HTTP_HOST']) ? '' : __MY_DOMAIN__.$my_root);

// 项目public目录URL地址
define('__MY_PUBLIC_URL__',  empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__.'://'.__MY_HOST__.__MY_ROOT_PUBLIC__);

// 当前页面url地址
$request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
if(!empty($request_url) && !empty($my_root))
{
    // 去除多余的子目录路径
    $request_url = str_replace($my_root, '', $request_url);
}
define('__MY_VIEW_URL__', substr(__MY_URL__, 0, -1).$request_url);

// 系统根目录,强制转换win反斜杠
define('ROOT_PATH', str_replace('\\', DS, dirname(__FILE__)).DS);

// 系统根目录 去除public
define('ROOT', substr(ROOT_PATH, 0, -7));

// 定义应用目录
define('APP_PATH', ROOT.'app'.DS);

// 防止独立入口文件已定义，系统类型 [default] 默认default、可根据终端区分系统类型
if(!defined('SYSTEM_TYPE'))
{
    define('SYSTEM_TYPE', empty($_REQUEST['system_type']) ? 'default' : trim($_REQUEST['system_type']));
}

// 请求应用 [web, app] 默认web(ios|android|小程序 均为app)
define('APPLICATION', empty($_REQUEST['application']) ? 'web' : trim($_REQUEST['application']));

// 请求客户端 [pc, h5, ios, android, alipay, weixin, baidu, toutiao, qq, kuaishou] 默认pc(目前系统为自适应,h5需自行校验)
define('APPLICATION_CLIENT_TYPE', empty($_REQUEST['application_client_type']) ? 'pc' : trim($_REQUEST['application_client_type']));

// 请求客户端手机品牌（调试模式为 devtools）
define('APPLICATION_CLIENT_BRAND', empty($_REQUEST['application_client_brand']) ? '' : trim($_REQUEST['application_client_brand']));

// 是否get
define('IS_GET', isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET');

// 是否post
define('IS_POST', isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST');

// 是否ajax
define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) || isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 'ajax'));

// 当前访问脚本名称
$script_name = '';
if(empty($_SERVER['SCRIPT_NAME']))
{
    if(empty($_SERVER['PHP_SELF']))
    {
        if(!empty($_SERVER['SCRIPT_FILENAME']))
        {
            $script_name = $_SERVER['SCRIPT_FILENAME'];
        }
    } else {
        $script_name = $_SERVER['PHP_SELF'];
    }
} else {
    $script_name = $_SERVER['SCRIPT_NAME'];
}
if(!empty($script_name))
{
    $loc = strripos($script_name, '/');
    if($loc !== false)
    {
        $script_name = substr($script_name, $loc+1);
    } 
}
define('SCRIPT_NAME', $script_name);

// 独立域名页面绑定
if(!IS_AJAX)
{
    $domain_file = ROOT.'config'.DS.'domain.php';
    if(file_exists($domain_file))
    {
        $data = include($domain_file);
        if(!empty($data) && !empty($data[__MY_HOST__]))
        {
            $_GET['s'] = $data[__MY_HOST__];
        }
    }
}

// 二级域名页面绑定
if(!IS_AJAX && substr_count(__MY_HOST__, '.') > 1 && !is_numeric(str_replace('.', '', __MY_HOST__)))
{
    $domain_file = ROOT.'config'.DS.'domain.php';
    $second_domain = substr(__MY_HOST__, 0, strpos(__MY_HOST__, '.'));
    if(!empty($second_domain) && file_exists($domain_file) && $second_domain != 'www')
    {
        $data = include($domain_file);
        if(!empty($data) && !empty($data[$second_domain]))
        {
            define('SECOND_DOMAIN', $second_domain);
            $_GET['s'] = $data[$second_domain];
        }
    }
}

// 站点默认首页绑定处理
if(SCRIPT_NAME == 'index.php' && empty($_GET['s']))
{
    $home_file = APP_PATH.'index'.DS.'config'.DS.'home.php';
    if(file_exists($home_file))
    {
        $data = include($home_file);
        if(!empty($data))
        {
            $_GET['s'] = $data;
        }
    }
}
?>