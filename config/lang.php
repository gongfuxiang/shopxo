<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 多语言设置
// +----------------------------------------------------------------------
return [
    // 默认语言
    'default_lang'    => MyFileConfig('common_multilingual_default_value', '', 'zh', true),
    // 允许的语言列表（preg 正则匹配、code 语言编码、语言名称在对应语言文件中）
    'allow_lang_list' => [
        // 简体中文        
        ['preg' => 'zh-c', 'code' => 'zh'],
        // 繁体中文
        ['preg' => 'zh-t', 'code' => 'cht'],
        // 英文
        ['preg' => 'en', 'code' => 'en'],
        // 俄语
        ['preg' => 'rn', 'code' => 'ru'],
        // 韩语
        ['preg' => 'ko', 'code' => 'kor'],
        // 泰语
        ['preg' => 'th', 'code' => 'th'],
        // 日语
        ['preg' => 'jp', 'code' => 'jp'],
        // 德语
        ['preg' => 'de', 'code' => 'de'],
        // 荷兰语
        ['preg' => 'nl', 'code' => 'nl'],
        // 越南语
        ['preg' => 'vi', 'code' => 'vie'],
        // 意大利语
        ['preg' => 'it', 'code' => 'it'],
        // 西班牙语
        ['preg' => 'es', 'code' => 'spa'],
        // 法语
        ['preg' => 'fr', 'code' => 'fra'],
        // 瑞典语
        ['preg' => 'sv', 'code' => 'swe'],
    ],
    // 多语言url请求自动侦测变量名
    'detect_var'      => 'lang',
    // 是否使用Cookie记录
    'use_cookie'      => true,
    // 多语言cookie变量
    'cookie_var'      => 'lang',
    // 多语言header变量
    'header_var'      => 'lang',
    // 扩展语言包
    'extend_list'     => [],
    // Accept-Language转义为对应语言包名称
    'accept_language' => [],
    // 是否支持语言分组
    'allow_group'     => false,
];
?>