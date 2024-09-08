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
// | 缓存设置
// +----------------------------------------------------------------------
return [
    // 用户侧登录配置
    'user'  => [
        //  $provider_url = null, string $client_id = null, string $client_secret = null
        'provider_url' => 'https://sso.freeb.vip/realms/test/',
        'client_id'   =>  'shopxo',
        'client_secret'   =>  'FXO9XrWD4EYjPtw299slqLF9M7rMnmjz',
        'redirect_url'   =>  '?s=user/loginInfo.html',
    ],
];
?>