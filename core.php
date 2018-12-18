<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 开启缓冲区
ob_start();

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// HTTP类型
define('__MY_HTTP__', (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') ? 'http' : 'https');

// 根目录
define('__MY_ROOT__', empty($_SERVER['SCRIPT_NAME']) ? '' : substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1));

// 当前项目HOST
define('__MY_HOST__', empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST']);

// 完整URL地址
define('__MY_URL__',  empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__.'://'.__MY_HOST__.__MY_ROOT__);

// 当前页面url地址
$request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
define('__MY_VIEW_URL__', substr(__MY_URL__, 0, -1).$request_url);

// 检测是否是新安装
if(is_dir("./Install") && !file_exists("./Install/install.lock"))
{
    $url = __MY_URL__.'Install/index.php';
    exit(header('location:'.$url));
}

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', false);

// 分之模式,master,develop,test,debug
define('APP_STATUS', 'master');

/* 定义系统目录分隔符 */
//define('DS', DIRECTORY_SEPARATOR);
define('DS', '/');

// 系统根目录
define('ROOT_PATH', dirname(__FILE__).DS);

// 定义应用目录
define('APP_PATH', ROOT_PATH.'Application'.DS);

// 请求应用 [web, app] 默认web
define('APPLICATION', empty($_REQUEST['application']) ? 'web' : trim($_REQUEST['application']));

// 请求客户端 [default, ...] 默认default
define('APPLICATION_CLIENT', empty($_REQUEST['application_client']) ? 'default' : trim($_REQUEST['application_client']));

// 请求客户端 [pc, wap/h5, alipay, wechat, baidu] 默认pc
define('APPLICATION_CLIENT_TYPE', empty($_REQUEST['application_client_type']) ? 'pc' : trim($_REQUEST['application_client_type']));

?>