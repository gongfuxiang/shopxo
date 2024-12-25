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
namespace app\service;

use app\service\UserService;
use app\service\AdminService;

/**
 * 多语言服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MultilingualService
{
    // 多语言选择缓存key
    public static $cache_key = 'multilingual_language';

    /**
     * 获取当前默认语言信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-13
     * @desc    description
     */
    public static function MultilingualData()
    {
        static $user_multilingual_static_result = [];
        if(empty($user_multilingual_static_result))
        {
            $default = [];
            $select = [];
            $allow_lang_list = MyConfig('lang.allow_lang_list');
            $multilingual_list = self::MultilingualCanChooseList();
            if(!empty($multilingual_list) && is_array($multilingual_list) && !empty($allow_lang_list) && is_array($allow_lang_list))
            {
                // 域名绑定语言
                $domain_multilingual = array_column(MyC('common_domain_multilingual_bind_list', [], true), 'domain', 'lang');
                // 可使用的语言
                $allow_lang_list = array_column($allow_lang_list, null, 'code');
                // 当前语言
                $user_lang = self::GetUserMultilingualValue();
                // 默认语言
                $default_lang = MyConfig('lang.default_lang');
                $url = self::CurrentViewUrl();
                foreach($multilingual_list as $k=>$v)
                {
                    if(array_key_exists($k, $allow_lang_list) || $k == $default_lang)
                    {
                        // 加入语言名称
                        $temp = array_merge($allow_lang_list[$k], [
                            'name'  => $v['name'],
                            'icon'  => $v['icon'],
                            'url'   => $url.$k,
                        ]);

                        // 域名语言
                        if(!empty($domain_multilingual) && is_array($domain_multilingual) && array_key_exists($k, $domain_multilingual))
                        {
                            $temp['url'] = __MY_HTTP__.'://'.$domain_multilingual[$k];
                        }

                        // 可选语言
                        $select[] = $temp;

                        // 默认语言
                        if(empty($default) && !empty($default_lang) && $k == $default_lang)
                        {
                            $default = $temp;
                        }

                        // 当前选择的语言
                        if(!empty($user_lang) && $k == $user_lang)
                        {
                            $default = $temp;
                        }
                    }
                }
                // 未匹配到则使用第一个
                if(empty($default) && !empty($select))
                {
                    $default = $select[0];
                }
            }
            $user_multilingual_static_result = [
                'default'  => $default,
                'data'     => $select,
            ];
        }
        return $user_multilingual_static_result;
    }

    /**
     * 语言列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-22
     * @desc    description
     */
    public static function MultilingualCanChooseList()
    {
        $result = [];
        $data = MyConst('common_multilingual_list');
        $choose_list = MyC('common_multilingual_choose_list');
        if(!empty($data) && is_array($data) && !empty($choose_list) && is_array($choose_list))
        {
            foreach($data as $k=>$v)
            {
                if(array_key_exists($k, $choose_list) && isset($choose_list[$k]) && isset($choose_list[$k]['status']) && $choose_list[$k]['status'] == 1)
                {
                    $result[$k] = [
                        'name'  => $v,
                        'icon'  => empty($choose_list[$k]['icon']) ? '' : $choose_list[$k]['icon'],
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * 当前页面url地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-13
     * @desc    description
     */
    public static function CurrentViewUrl()
    {
        // 去除当前存在的参数
        $url = __MY_VIEW_URL__;
        $lang_var = MyConfig('lang.detect_var');
        if(stripos($url, $lang_var) !== false)
        {
            $arr1 = explode('?', $url);
            if(!empty($arr1[1]))
            {
                $arr2 = explode('&', $arr1[1]);
                foreach($arr2 as $k=>$v)
                {
                    if(stripos($v, $lang_var) !== false)
                    {
                        unset($arr2[$k]);
                    }
                }
                $url = '?'.implode('&', $arr2);
            }
        }

        // 当前页面地址
        $join = (stripos($url, '?') === false) ? '?' : '&';
        return $url.$join.$lang_var.'=';
    }

    /**
     * 当前语言
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public static function GetUserMultilingualValue()
    {
        static $user_multilingual_static_value = null;
        if(is_null($user_multilingual_static_value))
        {
            // 参数指定
            $value = input(MyConfig('lang.detect_var'));

            // 请求头
            if(empty($value))
            {
                $header_var = MyConfig('lang.header_var');
                if(!empty($_SERVER[$header_var]))
                {
                    $value = strtolower($_SERVER[$header_var]);
                }
            }

            // cookie
            if(empty($value))
            {
                $value = MyCookie(MyConfig('lang.cookie_var'));
            }

            // session读取
            if(empty($value))
            {
                $value = MySession(self::CacheKey(self::$cache_key));
            }

            // 根据uuid读取
            if(empty($value))
            {
                $uuid = input('uuid');
                if(!empty($uuid))
                {
                    $value = MyCache(self::CacheKey(self::$cache_key.'_'.$uuid));
                }
            }

            // 根据用户读取
            if(empty($value))
            {
                $user = (RequestModule() == 'admin') ? AdminService::LoginInfo() : UserService::CacheLoginUserInfo();
                if(!empty($user['id']))
                {
                    // 缓存读取
                    $value = MyCache(self::CacheKey(self::$cache_key.'_'.$user['id']));
                }
            }

            // 域名绑定语言
            if(empty($value))
            {
                $domain_multilingual = MyC('common_domain_multilingual_bind_list');
                if(!empty($domain_multilingual) && is_array($domain_multilingual))
                {
                    foreach($domain_multilingual as $v)
                    {
                        if(!empty($v['domain']) && !empty($v['lang']) && $v['domain'] == __MY_HOST__)
                        {
                            $value = $v['lang'];
                        }
                    }
                }
            }

            // 自动检测
            if(empty($value) && MyC('common_multilingual_auto_status') == 1)
            {
                $value = self::BrowserHttpAcceptLanguage();
            }

            // 默认语言
            if(empty($value))
            {
                $value = MyConfig('lang.default_lang');
            }
            $user_multilingual_static_value = htmlspecialchars(str_replace(['.', '/'], '', $value));
        }
        return $user_multilingual_static_value;
    }

    /**
     * 设置用户选择的语言
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public static function SetUserMultilingualValue()
    {
        $value = input(MyConfig('lang.detect_var'));
        if(!empty($value))
        {
            // session
            MySession(self::CacheKey(self::$cache_key), $value);

            // 当前用户
            $user = UserService::LoginUserInfo();
            if(!empty($user['id']))
            {
                MyCache(self::CacheKey(self::$cache_key.'_'.$user['id']), $value);
            }

            // uuid
            $uuid = input('uuid');
            if(!empty($uuid))
            {
                MyCache(self::CacheKey(self::$cache_key.'_'.$uuid), $value);
            }
        }
        return true;
    }

    /**
     * 缓存key
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-05-14
     * @desc    description
     * @param   [string]          $key [缓存key]
     */
    public static function CacheKey($key)
    {
        return RequestModule().'_'.$key;
    }

    /**
     * 获取浏览器默认语言
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-22
     * @desc    description
     */
    public static function BrowserHttpAcceptLanguage()
    {
        $value = '';
        if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            $lang_list = MyConfig('lang.allow_lang_list');
            if(!empty($lang_list) && is_array($lang_list))
            {
                //只取前4位，这样只判断最优先的语言。如果取前5位，可能出现en,zh的情况，影响判断。
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
                foreach($lang_list as $v)
                {
                    if(!empty($v['preg']) && preg_match('/'.$v['preg'].'/i', $lang))
                    {
                        $value = $v['code'];
                        break;
                    }
                }
            }
        }
        return $value;
    }
}
?>