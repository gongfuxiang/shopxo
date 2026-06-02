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

// +----------------------------------------------------------------------
// | Cookie设置（部分项读取后台 系统配置 → Cookie配置）
// +----------------------------------------------------------------------
$cookie_domain = MyFileConfig('common_cookie_domain', '', '', true);
if(!empty($cookie_domain))
{
    if(substr($cookie_domain, 0, 1) != '.')
    {
        $cookie_domain = '.'.$cookie_domain;
    }
} else {
    $cookie_domain = __MY_MAIN_DOMAIN__;
}

$cookie_secure = ((int) MyFileConfig('common_cookie_secure', '', 0, true) == 1);
$cookie_httponly = ((int) MyFileConfig('common_cookie_httponly', '', 0, true) == 1);

$cookie_samesite_raw = MyFileConfig('common_cookie_samesite', '', '', true);
$cookie_samesite_raw = is_string($cookie_samesite_raw) ? strtolower(trim($cookie_samesite_raw)) : '';
$cookie_samesite = '';
if($cookie_samesite_raw == 'strict')
{
    $cookie_samesite = 'strict';
} elseif($cookie_samesite_raw == 'lax')
{
    $cookie_samesite = 'lax';
}

$cookie_expire_cfg = MyFileConfig('common_cookie_expire', '', '', true);
$cookie_expire = empty($cookie_expire_cfg) ? 0 : (int) $cookie_expire_cfg;

return [
    // cookie 保存时间（秒）
    'expire'    => $cookie_expire,
    // cookie 保存路径
    'path'      => '/',
    // cookie 有效域名
    'domain'    => $cookie_domain,
    //  cookie 启用安全传输
    'secure'    => $cookie_secure,
    // httponly设置
    'httponly'  => $cookie_httponly,
    // 是否使用 setcookie
    'setcookie' => true,
    // samesite 设置（与文件配置一致用小写 strict / lax；空表示由浏览器默认）
    'samesite'  => $cookie_samesite,
];
?>