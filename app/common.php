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

// 应用公共文件

use app\service\SystemBaseService;
use app\service\ResourcesService;
use app\service\PluginsService;
use app\service\ConstService;
use app\service\AdminService;
use app\service\AdminPowerService;
use app\service\MultilingualService;

/**
 * 图片转base64
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-03-08
 * @desc    description
 * @param   [string]          $image [图片地址]
 */
function ImageToBase64($image)
{
    return 'data:image/jpg/png/gif;base64,'.chunk_split(base64_encode(RequestGet($image)));
}

/**
 * 两个数组字段对比处理、arr1不存在arr2中的字段则移除
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-11-12
 * @desc    description
 * @param   [array]          $arr1 [待处理的数据]
 * @param   [array]          $arr2 [参考对比的数据]
 */
function ArrayFieldContrastHandle($arr1, $arr2)
{
    if(!empty($arr1) && is_array($arr1) && is_array($arr2))
    {
        foreach($arr1 as $fk=>$fv)
        {
            if(!array_key_exists($fk, $arr2))
            {
                unset($arr1[$fk]);
            }
        }
    }
    return $arr1;
}

/**
 * 获取汉字拼音、默认返回数组
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-09
 * @desc    description
 * @param   [string]          $string    [汉字]
 * @param   [boolean]         $is_string [返回字符串]
 * @param   [string]          $join      [字符串连接符号]
 */
function ChinesePinyin($string, $is_string = false, $join = '')
{
    $value = (new \Overtrue\Pinyin\Pinyin())->convert($string);
    return ($is_string && is_array($value)) ? implode($join, $value) : $value;
}

/**
 * 获取汉字首字母
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-09
 * @desc    description
 * @param   [string]          $string [汉字]
 */
function ChineseLetter($string)
{
    $value = (new \Overtrue\Pinyin\Pinyin())->abbr($string);
    return empty($value) ? '' : $value;
}

/**
 * 弹出内容处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-09-26
 * @desc    description
 * @param   [string]          $content [展示的内容]
 */
function PopoverContentHandle($content)
{
    return str_replace(["\n", "\r", "'", '"'], ['<br />', '', '', ''], $content);
}

/**
 * 生成uuid
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-10-30
 * @desc    description
 */
function UUId()  
{  
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-' 
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
    return $uuid;
}  

/**
 * 获取常量数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-14
 * @desc    description
 * @param   [string]         $key       [数据key]
 * @param   [mixed]          $default   [默认值]
 */
function MyConst($key = '', $default = null)
{
    return ConstService::Run($key, $default);
}

/**
 * session管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-17
 * @desc    description
 * @param   [string]         $name    [session名称]
 * @param   [mixed]          $value   [session值]
 */
function MySession($name = '', $value = '')
{
    // 调用框架session统一方法
    $res = session($name, $value);

    // 调用框架session数据保存、避免页面退出导致session保存失败
    // 框架是页面return才自动执行这个方法的
    if($value !== '' && $value !== null)
    {
        \think\facade\Session::save();
    }

    return $res;
}

/**
 * 获取语言
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-08-19
 * @desc    框架默认仅支持二级分组数据、这里做了支持N级处理（由于参数可能存在数组解析原因）这里单独处理不使用框架处理
 * @param   [string]          $key      [语言key（支持 . 多级）]
 * @param   [array]           $vars     [替换参数]
 * @param   [string]          $lang     [指定语言]
 * @param   [string]          $plugins  [指定插件]
 */
function MyLang($key, $vars = [], $lang = '', $plugins = '')
{
    $value = '';
    if(!empty($key))
    {
        // 当前语言
        $current_lang = empty($lang) ? MultilingualService::GetUserMultilingualValue() : $lang;

        // key使用 . 分隔
        $key_arr = explode('.', $key);

        // 语言数据容器
        static $lang_data = [];

        // 系统语言
        $request_module = RequestModule();
        $arr_file = [
            APP_PATH.$request_module.DS.'lang'.DS.$current_lang.'.php',
            APP_PATH.'lang'.DS.$current_lang.'.php',
        ];

        // 是否插件语言、未指定则处理
        if(empty($plugins))
        {
            $pluginsname = '';
            // 获取最新一条回溯跟踪
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if(!empty($backtrace) && !empty($backtrace[0]) && !empty($backtrace[0]['file']))
            {
                // 替换反斜杠为斜杠、避免操作系统不同存在兼容性问题
                $path = str_replace('\\', '/', $backtrace[0]['file']);
                $str = 'app/plugins/';
                $loc = stripos($path, $str);
                if($loc !== false)
                {
                    $temp = explode($str, $path);
                    if(count($temp) > 1)
                    {
                        $pluginsname = explode('/', $temp[1])[0];
                    }
                }
            }
            // 空则参数读取
            if(empty($pluginsname))
            {
                $pluginsname = MyInput('pluginsname');
            }
        } else {
            $pluginsname = $plugins;
        }
        if(!empty($pluginsname))
        {
            $plugins_dir = APP_PATH.'plugins'.DS.$pluginsname.DS.'lang'.DS;
            array_unshift($arr_file, $plugins_dir.$current_lang.'.php');
            array_unshift($arr_file, $plugins_dir.'common'.DS.$current_lang.'.php');
            array_unshift($arr_file, $plugins_dir.$request_module.DS.$current_lang.'.php');
        }

        // 循环获取语言时间
        foreach($arr_file as $file)
        {
            $md5_key = md5($file);
            if(!array_key_exists($md5_key, $lang_data) && file_exists($file))
            {
                $lang_data[$md5_key] = require $file;
            }
            if(!empty($lang_data[$md5_key]) && is_array($lang_data[$md5_key]))
            {
                $temp_lang_data = $lang_data[$md5_key];
                // 仅一级则直接读取
                if(count($key_arr) == 1)
                {
                    if(array_key_exists($key, $temp_lang_data))
                    {
                        $value = $temp_lang_data[$key];
                    }
                } else {
                    // 默认先读取第一级
                    if(array_key_exists($key_arr[0], $temp_lang_data))
                    {
                        $value = $temp_lang_data[$key_arr[0]];
                        if(!empty($value) && is_array($value))
                        {
                            // 移除第一级
                            array_shift($key_arr);
                            // 循环后面级别的数据
                            foreach($key_arr as $k=>$v)
                            {
                                if(array_key_exists($v, $value))
                                {
                                    $value = $value[$v];
                                    // 匹配到最后一级字段则结束外循环
                                    if($k == count($key_arr)-1)
                                    {
                                        break 2;
                                    }
                                } else {
                                    // 未匹配到则赋空值
                                    $value = '';
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        // 未找到对应语言
        if($value == '')
        {
            // 未指定语言则读取默认语言重新再去读取
            if(empty($lang))
            {
                // 非默认语言则读取默认语言
                $default_lang = MyConfig('lang.default_lang');
                if($default_lang != $lang)
                {
                    $value = MyLang($key, $vars, $default_lang, $pluginsname);
                } else {
                    $value = $key;
                }
            } else {
                $value = $key;
            }
        }

        // 变量解析
        if(is_string($value) && !empty($value) && !empty($vars) && is_array($vars))
        {
            // 为了检测的方便，数字索引的判断仅仅是参数数组的第一个元素的key为数字0
            // 数字索引采用的是系统的 sprintf 函数替换，用法请参考 sprintf 函数
            if(key($vars) === 0)
            {
                // 数字索引解析
                array_unshift($vars, $value);
                $value = call_user_func_array('sprintf', $vars);
            } else {
                // 关联索引解析
                $replace = array_keys($vars);
                foreach($replace as &$v)
                {
                    $v = "{:{$v}}";
                }
                $value = str_replace($replace, $vars, $value);
            }
        }
    }
    return $value;
}

/**
 * cookie管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-17
 * @desc    description
 * @param   [string]         $name              [cookie名称]
 * @param   [mixed]          $value             [cookie值]
 * @param   [boolean]        $is_encryption     [是否需要加密存储]
 */
function MyCookie($name = '', $value = '', $is_encryption = true)
{
    // 非空则转换数据
    if($value !== null && $value !== '' && $is_encryption)
    {
        $value = urlencode(Authcode(base64_encode(json_encode($value)), 'ENCODE'));
    }
    $res = cookie($name, $value);
    return ($res === null || $res === '' || !$is_encryption) ? $res : json_decode(base64_decode(Authcode(urldecode($res), 'DECODE')), true);
}

/**
 * 缓存管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-17
 * @desc    description
 * @param   [string]         $name    [缓存名称]
 * @param   [mixed]          $value   [缓存值]
 * @param   [mixed]          $options [缓存参数]
 * @param   [string]         $tag     [缓存标签]
 */
function MyCache($name = null, $value = '', $options = null, $tag = null)
{
    // 静态存储、不用每次都从磁盘读取
    static $object_cache = [];

    // 读取数据
    if($value === '')
    {
        if(!array_key_exists($name, $object_cache))
        {
            $object_cache[$name] = cache($name, $value, $options, $tag);
        }
        return $object_cache[$name];
    }

    // 设置数据
    return cache($name, $value, $options, $tag);
}

/**
 * 环境变量配置
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-17
 * @desc    description
 * @param   [string]          $key [key名称、支持.连接子数据，比如 shopxo.hello]
 * @param   [mixed]           $val [默认值]
 */
function MyEnv($key, $val = null)
{
    // 静态存储、不用每次都从磁盘读取
    static $object_env = [];
    if(!array_key_exists($key, $object_env))
    {
        $object_env[$key] = env($key, $val);
    }
    return $object_env[$key];
}

/**
 * 配置读取
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-17
 * @desc    description
 * @param   [string]          $key [key名称、支持.连接子数据，比如 shopxo.hello ]
 */
function MyConfig($key)
{
    // 静态存储、不用每次都从磁盘读取
    static $object_config = [];
    if(!array_key_exists($key, $object_config))
    {
        $object_config[$key] = config($key);
    }
    return $object_config[$key];
}

/**
 * 重定向
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 * @param   [string]          $url      [url地址 或 模块地址]
 * @param   [boolean]         $is_exit  [是否需要结束运行、默认 false]
 */
function MyRedirect($url, $is_exit = false)
{
    if(!IsUrl($url))
    {
        $url = MyUrl($url);
    }
    if($is_exit)
    {
        exit(header('location:'.$url));
    } else {
        return redirect($url);
    }
}

/**
 * 钩子事件定义
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 * @param   [string]          $key    [钩子名称]
 * @param   [array]           $params [输入参数]
 */
function MyEventTrigger($key, $params = [])
{
    return event($key, $params);
}

/**
 * 视图赋值
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 * @param   [string|array]    $data   [key名称|批量赋值（数组）]
 * @param   [mixed]           $value [数据值]
 */
function MyViewAssign($data, $value = '')
{
    // 模板引擎数据渲染分配钩子
    $hook_name = 'plugins_view_assign_data';
    MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'value'         => &$value,
        ]);

    \think\facade\View::assign($data, $value);
}

/**
 * 视图调用
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 * @param   [string]          $view   [视图地址]
 * @param   [array]           $data   [模板变量]
 */
function MyView($view = '', $data = [])
{
    // 模板引擎数据渲染前钩子
    $hook_name = 'plugins_view_fetch_begin';
    MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'view'          => &$view,
            'data'          => &$data,
        ]);

    // 调用框架视图方法
    $result = \think\facade\View::fetch($view, $data);

    // 模板引擎数据渲染后钩子
    $hook_name = 'plugins_view_fetch_end';
    MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'view'          => &$view,
            'data'          => $data,
            'result'        => &$result,
        ]);

    return $result;
}

/**
 * 当前请求模块组名
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 */
function RequestModule()
{
    static $request_module = null;
    if($request_module === null)
    {
        $request_module = strtolower(app('http')->getName());
    }
    return $request_module;
}

/**
 * 当前请求控制器名
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 */
function RequestController()
{
    static $request_controller = null;
    if($request_controller === null)
    {
        $request_controller = strtolower(request()->controller());
    }
    return $request_controller;
}

/**
 * 当前请求方法名
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 */
function RequestAction()
{
    static $request_action = null;
    if($request_action === null)
    {
        $request_action = strtolower(request()->action());
    }
    return $request_action;
}

/**
 * 插件当前请求名称
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 */
function PluginsRequestName()
{
    $plugins = MyInput('pluginsname');
    return empty($plugins) ? '' : strtolower($plugins);
}

/**
 * 插件当前请求方控制器
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 */
function PluginsRequestController()
{
    return strtolower(MyInput('pluginscontrol', 'index'));
}

/**
 * 插件当前请求方法名
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 */
function PluginsRequestAction()
{
    return strtolower(MyInput('pluginsaction', 'index'));
}

/**
 * 获取链接http状态码
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-09
 * @desc    description
 * @param   [string]      $url     [链接地址]
 * @param   [int]         $timeout [超时时间（秒）]
 */
function GetHttpCode($url, $timeout = 5)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // 返回结果
    $result = curl_exec($ch);
    if($result !== false)
    {
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return DataReturn('success', 0, $code);
    } else { 
        $error_code = curl_errno($ch);
        $error_msg = curl_error($ch);
        curl_close($ch);
        return DataReturn($error_msg.' ('.$error_code.')', -9999, $error_code);
    }
}

/**
 * 判断是否是url地址
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-09
 * @desc    description
 * @param   [string]          $value [地址值]
 */
function IsUrl($value)
{
    return (empty($value) || is_array($value)) ? false : in_array(substr($value, 0, 6), ['http:/', 'https:']);
}

/**
 * 快速排序
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-03-09
 * @desc    description
 * @param  [array] $data [需要排序的数据（选择一个基准元素，将待排序分成小和打两罐部分，以此类推递归的排序划分两罐部分）]
 * @param  [array]       [数组字段]
 * @param  [int]         [类型（0从小到大、1从大到小）]
 * @return [array]       [排序好的数据]
 */
function ArrayQuickSort($data, $field, $type = 0)
{
    if(!empty($data) && is_array($data))
    {
        $len = count($data);
        if($len <= 1) return $data;

        $base = $data[0];
        $left_array = array();
        $right_array = array();
        for($i=1; $i<$len; $i++)
        {
            if($type == 0)
            {
                if($base[$field] > $data[$i][$field])
                {
                    $left_array[] = $data[$i];
                } else {
                    $right_array[] = $data[$i];
                }
            } else {
                if($base[$field] < $data[$i][$field])
                {
                    $left_array[] = $data[$i];
                } else {
                    $right_array[] = $data[$i];
                }
            }
        }
        if(!empty($left_array))
        {
            $left_array = ArrayQuickSort($left_array, $field, $type);
        }
        if(!empty($right_array))
        {
            $right_array = ArrayQuickSort($right_array, $field, $type);
        }
        return array_merge($left_array, array($base), $right_array);
    }
}

/**
 * 是否base64加密的字符串
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-28
 * @desc    description
 * @param   [string]          $str [base加密的字符串]
 */
function IsBase64($str)
{
    $str = urldecode($str);
    return ($str == base64_encode(base64_decode($str)));
}

/**
 * 根据url地址解析顶级域名
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-25
 * @desc    description
 * @param   [string]          $url [url地址]
 */
function GetUrlHost($url)
{
    // 地址解析
    $arr = parse_url(strtolower($url));
    $host = (count($arr) == 1) ? (isset($arr['path']) ? $arr['path'] : '') : (empty($arr['host']) ? '' : $arr['host']);
    if(empty($host))
    {
        return $url;
    }

    // 是否存在斜杠
    if(stripos($host, '/') !== false)
    {
        $slash = explode('/', $host);
        $host = $slash[0];
    }

    // 查看是几级域名
    $data = explode('.', $host);
    $n = count($data);
    if(count($data) == 1)
    {
        return $host;
    }

    // 判断是否是双后缀
    $preg = '/[\w].+\.(com|net|org|gov|ac|bj|sh|tj|cq|he|sn|sx|nm|ln|jl|hl|js|zj|ah|fj|jx|sd|ha|hb|hn|gd|gx|hi|sc|gz|yn|gs|qh|nx|xj|tw|hk|mo|xz|edu|ge|dev|co)\.(cn|nz|mm|ec)$/';
    if(($n > 2) && preg_match($preg, $host))
    {
        // 双后缀取后3位
        $host = $data[$n-3].'.'.$data[$n-2].'.'.$data[$n-1];
    } else {
        // 非双后缀取后两位
        $host = $data[$n-2].'.'.$data[$n-1];
    }
    return $host;
}

/**
 * 文件配置数据读写
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-13
 * @desc    description
 * @param   [string]          $key      [数据缓存可以]
 * @param   [mixed]           $value    [数据值(空字符串:读, null:删除, 其他:写)]
 * @param   [mixed]           $default  [默认值]
 * @param   [boolean]         $mandatory[是否强制判断数据值(空字符串|null|0)视为空]
 * @return  [mixed]                     [缓存不存在:null, 则缓存数据]
 */
function MyFileConfig($key, $value = '', $default = null, $mandatory = false)
{
    // 静态存储、不用每次都从磁盘读取
    static $object_file_cache_config = [];

    // 目录不存在则创建
    $config_dir = ROOT.'runtime'.DS.'data'.DS.'config_data'.DS;
    \base\FileUtil::CreateDir($config_dir);

    // 数据文件
    $file = $config_dir.md5($key).'.txt';

    // 删除
    if($value === null)
    {
        return \base\FileUtil::UnlinkFile($file);
    } else {
        // 读内容
        if($value === '')
        {
            if(!array_key_exists($key, $object_file_cache_config))
            {
                $value = file_exists($file) ? unserialize(file_get_contents($file)) : $default;
                if($mandatory === true)
                {
                    if(empty($value))
                    {
                        $value = $default;
                    }
                }
                $object_file_cache_config[$key] = $value;
            } else {
                $value = $object_file_cache_config[$key];
            }
            return $value;
        // 写内容
        } else {
            // 目录是否有可写权限
            if(!is_writable($config_dir))
            {
                return false;
            }

            // 存在则校验写权限
            if(file_exists($file) && !is_writable($file))
            {
                return false;
            }

            // 存储内容
            if(file_put_contents($file, serialize($value)) !== false)
            {
                $object_file_cache_config[$key] = $value;
                return true;
            }
            return false;
        }
    }
}

/**
 * 获取参数数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-23
 * @desc    description
 * @param   [string]          $key     [参数key]
 * @param   [string]          $default [默认值]
 */
function MyInput($key = null, $default = '')
{
    static $params = null;
    if($params === null)
    {
        $params = input();
        if(empty($params))
        {
            $params = file_get_contents("php://input");
        }
    }

    // 非数组则检查是否为json和xml数据
    if(!is_array($params))
    {
        if(IsJson($params))
        {
            $params = json_decode($params, true);
        } else {
            if(XmlParser($params))
            {
                $params = XmlArray($params);
            }
        }
    }

    // 是否指定key
    if(!empty($key) && is_array($params))
    {
        if(array_key_exists($key, $params)) 
        {
            return is_array($params[$key]) ? $params[$key] : htmlspecialchars(trim($params[$key]));
        }
        return $default;
    }

    // 未指定key则返回所有数据
    return $params;
}

/**
 * 当前应用平台
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-11
 * @desc    description
 */
function ApplicationClientType()
{
    // 平台
    $platform = APPLICATION_CLIENT_TYPE;

    // web端手机访问
    if($platform == 'pc' && IsMobile())
    {
        $platform = 'h5';
    }
    return $platform;
}

/**
 * 是否微信环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsWeixinEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false);
}

/**
 * 是否钉钉环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsDingdingEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'DingTalk') !== false);
}

/**
 * 是否QQ环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsQQEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false);
}

/**
 * 是否支付宝环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsAlipayEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false);
}

/**
 * 是否百度环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsBaiduEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp') !== false);
}

/**
 * 是否快手环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsKuaishouEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'Kwai') !== false);
}

/**
 * 是否新浪微博环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-26
 * @desc    description
 * @return  [boolean] [否false, 是true]
 */
function IsWeiboEnv()
{
    return (!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'Weibo') !== false);
}

/**
 * web环境
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-10-24
 * @desc    description
 */
function WebEnv()
{
    // 微信
    if(IsWeixinEnv())
    {
        return 'weixin';
    }

    // 支付宝
    if(IsAlipayEnv())
    {
        return 'alipay';
    }

    // 百度
    if(IsBaiduEnv())
    {
        return 'baidu';
    }

    // 快手
    if(IsKuaishouEnv())
    {
        return 'kuaishou';
    }

    // 钉钉
    if(IsDingdingEnv())
    {
        return 'dingding';
    }

    // QQ
    if(IsQQEnv())
    {
        return 'qq';
    }

    // 微博
    if(IsWeiboEnv())
    {
        return 'weibo';
    }

    return null;
}

/**
 * 判断当前是否小程序环境中
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-06-29T22:21:44+0800
 */
function MiniAppEnv()
{
    if(!empty($_SERVER['HTTP_USER_AGENT']))
    {
        // 微信小程序 miniProgram
        // QQ小程序 miniProgram
        if(stripos($_SERVER['HTTP_USER_AGENT'], 'miniProgram') !== false)
        {
            // 是否QQ小程序
            if(stripos($_SERVER['HTTP_USER_AGENT'], 'QQ') !== false)
            {
                return 'qq';
            }
            return 'weixin';
        }

        // 支付宝客户端 AlipayClient
        if(stripos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false)
        {
            return 'alipay';
        }

        // 百度小程序 swan-baiduboxapp
        if(stripos($_SERVER['HTTP_USER_AGENT'], 'swan-baiduboxapp') !== false)
        {
            return 'baidu';
        }

        // 头条小程序 ToutiaoMicroApp
        if(stripos($_SERVER['HTTP_USER_AGENT'], 'ToutiaoMicroApp') !== false)
        {
            return 'toutiao';
        }

        // 快手小程序 AllowKsCallApp
        if(stripos($_SERVER['HTTP_USER_AGENT'], 'AllowKsCallApp') !== false)
        {
            return 'kuaishou';
        }
    }
    return null;
}

/**
 * 笛卡尔积生成规格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-15
 * @desc    description
 * @param   [array]          $arr1 [要进行笛卡尔积的二维数组]
 * @param   [array]          $arr2 [最终实现的笛卡尔积组合,可不传]
 */
function SpecCartesian($arr1, $arr2 = [])
{
    $result = [];
    if(!empty($arr1))
    {
        // 去除第一个元素
        $first = array_splice($arr1, 0, 1);

        // 判断是否是第一次进行拼接
        if(count($arr2) > 0)
        {
            foreach($arr2 as $v)
            {
                foreach($first[0] as $vs)
                {
                    $result[] = $v.','.$vs;
                }
            }
        } else {
            foreach($first[0] as $vs)
            {
                $result[] = $vs;
            }
        }

        // 递归进行拼接
        if(count($arr1) > 0)
        {
            $result = SpecCartesian($arr1, $result);
        }
    }
    return $result;
}

/**
 * 后台管理权限校验方法
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-12
 * @desc    description
 * @param   [string]          $controller     [控制器（默认读取当前）]
 * @param   [string]          $action         [方法（默认读取当前）]
 * @param   [array]           $unwanted_power [不校验权限的方法（默认空）]
 */
function AdminIsPower($controller = null, $action = null, $unwanted_power = [])
{
    // 控制器/方法
    $controller = strtolower(empty($controller) ? request()->controller() : $controller);
    $action = strtolower(empty($action) ? request()->action() : $action);

    // 管理员
    $admin = AdminService::LoginInfo();
    if(!empty($admin))
    {
        // 不需要校验权限的方法
        if(!empty($unwanted_power) && in_array($action, $unwanted_power))
        {
            return true;
        }

        // 权限
        // 角色组权限列表校验
        $res = AdminPowerService::PowerMenuInit();
        if(!empty($res) && !empty($res['admin_power']) && is_array($res['admin_power']) && array_key_exists($controller.'_'.$action, $res['admin_power']))
        {
            return true;
        }
    }
    return false;
}

/**
 * 获取数组字段名称
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-12
 * @desc    description
 * @param   [array]          $data [数组（一维或二维数组）]
 */
function ArrayKeys($data)
{
    if(is_array($data))
    {
        // 是否二维数组
        if(isset($data[0]) && is_array($data[0]))
        {
            return array_keys($data[0]);
        }

        // 一维数组
        return array_keys($data);
    }
    return [];
}

/**
 * 商品销售模式
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-02
 * @desc    description
 * @param   [int]          $site_type [商品类型]
 * @return  [int]                     [销售模式]
 */
function GoodsSalesModelType($site_type)
{
    return ($site_type == -1) ? SystemBaseService::SiteTypeValue() : $site_type;
}

/**
 * 商品类型是否与站点类型一致
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-02
 * @desc    description
 * @param   [int]          $site_type [商品类型]
 * @return  [int]                     [一致 1 | 不一致 0]
 */
function IsGoodsSiteTypeConsistent($site_type)
{
    // 是否已设置
    if($site_type == -1)
    {
        return 1;
    }

    // 系统站点类型
    $common_site_type = SystemBaseService::SiteTypeValue();

    // 是否一致
    if($common_site_type == $site_type)
    {
        return 1;
    }

    // 系统是否为 销售+自提,包含其中
    if($common_site_type == 4 && in_array($site_type, [0, 2]))
    {
        return 1;
    }

    // 商品类型为 销售+自提、包含其中
    if($site_type == 4 && in_array($common_site_type, [0, 2]))
    {
        return 1;
    }

    // 不一致
    return 0;
}

/**
 * 缓存安全验证次数处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-03
 * @desc    description
 * @param   [string]          $key          [缓存 key]
 * @param   [int]             $type         [操作类型（0清除, 1验证）]
 * @param   [int]             $expire_time  [过期时间（默认30秒）]
 */
function SecurityPreventViolence($key, $type = 1, $expire_time = 30)
{
    // 安全缓存 key
    $mkey = md5($key.'_security_prevent_violence');

    // 清除缓存返
    if($type == 0)
    {
        MyCache($mkey, null);
        return true;
    }

    // 验证并增加次数
    $count = intval(MyCache($mkey))+1;
    $max = MyConfig('shopxo.security_prevent_violence_max');
    $status = false;
    if($count <= $max)
    {
        MyCache($mkey, $count, $expire_time);
        $status = true;
    }

    // 验证达到次数限制则清除验证信息
    if($count > $max)
    {
        MyCache($key, null);
        MyCache($mkey, null);
    }

    return $status;
}

/**
 * 获取动态表格 form 路径
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-05
 * @desc    description
 * @param   [array]           $params [输入参数]
 */
function FormModulePath($params = [])
{
    // 参数变量
    $path = '';
    $group = RequestModule();
    $controller = RequestController();
    $action = RequestAction();

    // 是否插件调用
    if($controller == 'plugins')
    {
        if(!empty($params['pluginsname']))
        {
            // 控制器和方法默认值处理
            $controller = empty($params['pluginscontrol']) ? 'index' : $params['pluginscontrol'];
            $action = empty($params['pluginsaction']) ? 'index' : $params['pluginsaction'];

            // 是否定义模块组、是否存在控制住+方法的form文件
            $path = '\app\plugins\\'.$params['pluginsname'].'\form\\'.$group.'\\'.ucfirst($controller.$action);
            if(!class_exists($path))
            {
                $path = '\app\plugins\\'.$params['pluginsname'].'\form\\'.$group.'\\'.ucfirst($controller);
                if(!class_exists($path))
                {
                    $path = '\app\plugins\\'.$params['pluginsname'].'\form\\'.ucfirst($controller.$action);
                    if(!class_exists($path))
                    {
                        $path = '\app\plugins\\'.$params['pluginsname'].'\form\\'.ucfirst($controller);
                    }
                }
            }
        }
    } else {
        // 是否存在控制住+方法的form文件
        $path = '\app\\'.$group.'\form\\'.ucfirst($controller.$action);
        if(!class_exists($path))
        {
            $path = '\app\\'.$group.'\form\\'.ucfirst($controller);
        }
    }

    return [
        'module'    => $path,
        'action'    => $action,
    ];
}

/**
 * 获取动态表格数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-10-08
 * @desc    description
 * @param   [array]           $params [输入参数]
 */
function FormModuleData($params = [])
{
    $data = [];
    if(!empty($params['control']) && !empty($params['id']))
    {
        // 模块组、默认前端
        $group = empty($params['group']) ? 'index' : $params['group'];
        // 控制器
        $control = strtolower($params['control']);
        // 方法、默认详情
        $action = empty($params['action']) ? 'detail' : strtolower($params['action']);

        // 请求参数
        $request_params = [
            // 数据id
            'id'           => $params['id'],
            // 详情数据key
            'detail_dkey'  => empty($params['detail_dkey']) ? 'id' : $params['detail_dkey'],
            // 详情额外参数
            'detail_where' => empty($params['where']) ? [] : $params['where'],
        ];

        // 是否插件
        if(empty($params['plugins']))
        {
            // 模块地址
            $module = '\app\\'.$group.'\form\\'.ucfirst($control);
            // 模块参数
            $request_params['module_name'] = $group;
            $request_params['controller_name'] = $control;
            $request_params['action_name'] = $action;
        } else {
            // 模块地址
            $module = '\app\plugins\\'.$params['plugins'].'\form\\'.$group.'\\'.ucfirst($control);
            // 模块参数
            $request_params['pluginsname'] = $params['plugins'];
            $request_params['pluginscontrol'] = $control;
            $request_params['pluginsaction'] = $action;
        }

        // 模块运行方法
        $run = empty($params['run']) ? 'Run' : $params['run'];

        // 调用模块获取数据
        $ret = (new app\module\FormHandleModule())->Run($module, $run, $request_params);
        if($ret['code'] == 0 && !empty($ret['data']) && is_array($ret['data']))
        {
            // 全部数据
            if(empty($params['data_type']) || $params['data_type'] == 'all')
            {
                $data = $ret['data'];
            } else {
                if(array_key_exists($params['data_type'], $ret['data']))
                {
                    $data = $ret['data'][$params['data_type']];
                }
            }
        }
    }
    return $data;
}

/**
 * 模块视图动态加载方法
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-25
 * @desc    description
 * @param   [string]          $template [视图路径]
 * @param   [mixed]           $data     [参数数据]
 * @param   [mixed]           $params   [额外参数]
 */
function ModuleInclude($template, $data = [], $params = [])
{
    // 应用控制器
    $module = '\app\module\ViewIncludeModule';
    if(!class_exists($module))
    {
        return MyLang('common_function.module_view_control_undefined_tips').'['.$module.']';
    }

    // 调用方法
    $action = 'Run';
    $obj = new $module();
    if(!method_exists($obj, $action))
    {
        return MyLang('common_function.module_view_action_undefined_tips').'['.$module.'->'.$action.'()]';
    }

    return $obj->Run($template, $data, $params);
}

/**
 * 钩子返回数据处理，是否存在错误
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-12-02
 * @desc    description
 * @param   [array]          $data [钩子返回的数据]
 */
function EventReturnHandle($data)
{
    if(!empty($data) && is_array($data))
    {
        foreach($data as $v)
        {
            if(is_array($v) && isset($v['code']) && $v['code'] != 0)
            {
                return $v;
            }
        }
    }
    return DataReturn(MyLang('common_function.hook_empty_tips'), 0);
}

/**
 * 附件地址处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-10-16
 * @desc    用于页面展示处理，非绝对路径的情况下自动加上http
 * @param   [string]          $value [附件地址]
 */
function AttachmentPathViewHandle($value)
{
    return ResourcesService::AttachmentPathViewHandle($value);
}

/**
 * 路径解析指定参数
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-08-06
 * @desc    description
 * @param   [string]            $key        [指定key]
 * @param   [mixed]             $default    [默认值]
 * @param   [string]            $path       [参数字符串 格式如： a/aa/b/bb/c/cc ]
 */
function PathToParams($key = null, $default = null, $path = '')
{
    $data = $_REQUEST;
    if(empty($path) && isset($_REQUEST['s']))
    {
        $path = $_REQUEST['s'];
    }
    if(empty($path))
    {
        $path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (empty($_SERVER['REDIRECT_URL']) ? (empty($_SERVER['REQUEST_URI']) ? (empty($_SERVER['PATH_TRANSLATED']) ? '' : $_SERVER['PATH_TRANSLATED']) : $_SERVER['REQUEST_URI']) : $_SERVER['REDIRECT_URL']);
    }

    if(!empty($path) && !array_key_exists($key, $data))
    {
        if(substr($path, 0, 1) == '/')
        {
            $path = mb_substr($path, 1, mb_strlen($path, 'utf-8')-1, 'utf-8');
        }
        $position = strrpos($path, '.');
        if($position !== false)
        {
            $path = mb_substr($path, 0, $position, 'utf-8');
        }
        $arr = explode('/', $path);

        
        $index = 0;
        foreach($arr as $k=>$v)
        {
            if($index != $k)
            {
                $data[$arr[$index]] = $v;
                $index = $k;
            }
        }
    }

    if($key !== null)
    {
        return array_key_exists($key, $data) ? $data[$key] : $default;
    }
    return $data;
}

/**
 * 应用控制器调用
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @date     2020-01-02
 * @param    [string]          $plugins        [应用标记]
 * @param    [string]          $control        [应用控制器]
 * @param    [string]          $action         [应用方法]
 * @param    [string]          $group          [应用组(admin, index, api)]
 * @param    [array]           $params         [输入参数]
 * @param    [int]             $is_ret_data    [是否直接返回data数据]
 */
function PluginsControlCall($plugins, $control, $action, $group = 'index', $params = [], $is_ret_data = 0)
{
    $ret = PluginsService::PluginsControlCall($plugins, $control, $action, $group, $params);
    return ($is_ret_data == 1) ? $ret['data'] : $ret;
}

/**
 * 调用插件服务层方法 - 获取插件配置信息
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-10-16T22:03:48+0800
 * @param    [string]          $plugins             [插件名称]
 * @param    [array]           $attachment_field    [自定义附件字段]
 * @param    [string]          $service_name        [附件定义的服务层类名]
 * @param    [string]          $attachment_property [附件属性名称]
 */
function CallPluginsData($plugins, $attachment_field = [], $service_name = '', $attachment_property = 'base_config_attachment_field')
{
    // 插件是否启用
    if(PluginsService::PluginsStatus($plugins) != 1)
    {
        return DataReturn(MyLang('common_function.plugins_status_error_tips').'['.$plugins.']', -1);
    }

    // 查看是否存在基础服务层并且定义获取基础配置方法
    $plugins_class = 'app\plugins\\'.$plugins.'\service\BaseService';
    if(class_exists($plugins_class))
    {
        if(method_exists($plugins_class, 'BaseConfig'))
        {
            return $plugins_class::BaseConfig();
        }
    }

    // 未指定附件字段则自动去获取
    $attachment = $attachment_field;
    if(empty($attachment_field) && !empty($attachment_property))
    {
        // 类自定义或者默认两个类
        $service_all = empty($service_name) ? ['BaseService', 'Service'] : [$service_name];
        foreach($service_all as $service)
        {
            // 服务层获取附件属性
            $plugins_class = 'app\plugins\\'.$plugins.'\service\\'.$service;
            if(class_exists($plugins_class) && property_exists($plugins_class, $attachment_property))
            {
                $attachment = $plugins_class::${$attachment_property};
                break;
            }
        }
    }
    // 获取配置信息
    return PluginsService::PluginsData($plugins, $attachment);
}

/**
 * 调用插件服务层方法 - 访问为静态
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-07-10T22:03:48+0800
 * @param    [string]          $plugins   [插件名称]
 * @param    [string]          $service   [服务层名称]
 * @param    [string]          $method    [方法名称]
 * @param    [mixed]           $params    [参数]
 */
function CallPluginsServiceMethod($plugins, $service, $method, $params = null)
{
    $plugins_class = 'app\plugins\\'.$plugins.'\service\\'.$service;
    if(class_exists($plugins_class))
    {
        if(method_exists($plugins_class, $method))
        {
            // 插件是否启用
            if(PluginsService::PluginsStatus($plugins) != 1)
            {
                return DataReturn(MyLang('common_function.plugins_status_error_tips').'['.$plugins.']', -1);
            }

            // 调用方法返回数据
            return $plugins_class::$method($params);
        } else {
            return DataReturn(MyLang('common_function.plugins_class_action_no_exist_tips').'['.$plugins.'-'.$service.'-'.$method.']', -1);
        }
    }
    return DataReturn(MyLang('common_function.plugins_class_no_exist_tips').'['.$plugins.'-'.$service.']', -1);
}

/**
 * RGB 转 十六进制
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-06-08T18:38:16+0800
 * @param    [string]        $rgb [reg颜色值]
 */
function RgbToHex($rgb)
{
    $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
    preg_match($regexp, $rgb, $match);
    $re = array_shift($match);
    $hex_color = "#";
    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    for ($i = 0; $i < 3; $i++)
    {
        $r = null;
        $c = $match[$i];
        $hex_array = [];
        while ($c > 16)
        {
            $r = $c % 16;
            $c = ($c / 16) >> 0;
            array_push($hex_array, $hex[$r]);
        }
        array_push($hex_array, $hex[$c]);
        $ret = array_reverse($hex_array);
        $item = implode('', $ret);
        $item = str_pad($item, 2, '0', STR_PAD_LEFT);
        $hex_color .= $item;
    }
    return $hex_color;
}

/**
 * 十六进制 转 RGB
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-06-08T18:33:45+0800
 * @param    [string]        $hex_color [十六进制颜色值]
 */
function HexToRgb($hex_color) {
    $color = str_replace('#', '', $hex_color);
    if(strlen($color) > 3)
    {
        $rgb = [
            'r' => hexdec(substr($color, 0, 2)),
            'g' => hexdec(substr($color, 2, 2)),
            'b' => hexdec(substr($color, 4, 2))
        ];
    } else {
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = [
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b)
        ];
    }
    return $rgb;
}

/**
 * 字符串转ascii
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-06-02T01:13:47+0800
 * @param    [string]          $str [字符串]
 * @return   [string]               [转换后的ascii]
 */
function StrToAscii($str)
{
    $change_after = '';
    if(!empty($str))
    {
        // 编码处理
        $encode = mb_detect_encoding($str);
        if($encode != 'UTF-8')
        {
            $str = mb_convert_encoding($str, 'UTF-8', $encode);
        }

        // 开始转换
        for($i=0; $i<strlen($str); $i++)
        {
            $temp_str = dechex(ord($str[$i]));
            if(isset($temp_str[1]))
            {
                $change_after .= $temp_str[1];
            }
            if(isset($temp_str[0]))
            {
                $change_after .= $temp_str[0];
            }
        }
    }
    return strtoupper($change_after);
}


/**
 * ascii转字符串
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2019-06-02T01:14:04+0800
 * @param    [string]       $ascii [ascii]
 * @return   [string]              [转换后的字符串]
 */
function AsciiToStr($ascii)
{
    $str = '';
    if(!empty($ascii))
    {
        // 开始转换
        $asc_arr = str_split(strtolower($ascii), 2);
        for($i=0; $i<count($asc_arr); $i++)
        {
            $str .= chr(hexdec($asc_arr[$i][1].$asc_arr[$i][0]));
        }

        // 编码处理
        $encode = mb_detect_encoding($str);
        if($encode != 'UTF-8')
        {
            $str = mb_convert_encoding($str, 'UTF-8', $encode);
        }
    }
    return $str;
}

/**
 * 获取当前系统所在根路径
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-04-09
 * @desc    description
 */
function GetDocumentRoot()
{
    // 当前所在的文档根目录
    if(!empty($_SERVER['DOCUMENT_ROOT']))
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    // 处理iis服务器DOCUMENT_ROOT路径为空
    if(!empty($_SERVER['PHP_SELF']))
    {
        // 当前执行程序的绝对路径及文件名
        if(!empty($_SERVER['SCRIPT_FILENAME']))
        {
            return str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 -strlen($_SERVER['PHP_SELF'])));
        }

        // 当前所在绝对路径
        if(!empty($_SERVER['PATH_TRANSLATED']))
        {
            return str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 -strlen($_SERVER['PHP_SELF'])));
        }
    }

    // 服务器root没有获取到默认使用系统root_path
    return (substr(ROOT_PATH, -1) == '/') ? substr(ROOT_PATH, 0, -1) : ROOT_PATH;
}

/**
 * 生成随机字符串
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-04-04
 * @desc    description
 * @param   [int]          $length [长度 默认6]
 */
function RandomString($length = 6)
{
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    $pattern_length = strlen($pattern)-1;
    $output = '';
    for($i=0; $i<$length; $i++)   
    {
        $output .= $pattern[mt_rand(0, $pattern_length)];
    }
    return $output;
}

/**
 * each函数
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-02-26
 * @desc    description
 * @param   [array]          &$data [输入参数]
 */
function FunEach(&$data)
{
    if(!is_array($data))
    {
        return false;
    }

    $res = [];
    $key = key($data);
    if($key !== null)
    {
        next($data); 
        $res[1] = $res['value'] = $data[$key];
        $res[0] = $res['key'] = $key;
    } else {
        $res = false;
    }
    return $res;
}

/**
 * 金额格式化
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-02-20
 * @desc    description
 * @param   [float]         $value     [金额]
 * @param   [int]           $decimals  [保留的位数]
 * @param   [string]        $dec_point [保留小数分隔符]
 */
function PriceNumberFormat($value, $decimals = 2, $dec_point = '.')
{
    return number_format((float) $value, $decimals, $dec_point, '');
}

/**
 * json带格式输出
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-02-13
 * @desc    description
 * @param   [array]          $data   [数据]
 * @param   [string]         $indent [缩进字符，默认4个空格 ]
 */
function JsonFormat($data, $indent = null)
{
    // json encode  
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    // 缩进处理  
    $ret = '';
    $pos = 0;
    $length = strlen($data);
    $indent = isset($indent)? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;
    for($i=0; $i<=$length; $i++)
    {
        $char = substr($data, $i, 1);
  
        if($char == '"' && $prevchar != '\\')
        {
            $outofquotes = !$outofquotes;  
        } elseif(($char == '}' || $char == ']') && $outofquotes)
        {
            $ret .= $newline;
            $pos--;
            for($j=0; $j<$pos; $j++)
            {
                $ret .= $indent;
            }
        }
  
        $ret .= $char;  

        if(($char == ',' || $char == '{' || $char == '[') && $outofquotes)
        {
            $ret .= $newline;
            if($char == '{' || $char == '[')
            {
                $pos++;
            }
            for($j=0; $j<$pos; $j++)
            {
                $ret .= $indent;
            }
        }
  
        $prevchar = $char;
    }
    return $ret;  
}

/**
 * 文件大小转常用单位
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-11-28T01:05:29+0800
 * @param    [int]                   $bit [字节数]
 */
function FileSizeByteToUnit($bit)
{
    //单位每增大1024，则单位数组向后移动一位表示相应的单位
    $type = array('Bytes','KB','MB','GB','TB');
    for($i = 0; $bit >= 1024; $i++)
    {
        $bit/=1024;
    }

    //floor是取整函数，为了防止出现一串的小数，这里取了两位小数
    return (floor($bit*100)/100).$type[$i];
}

/**
 * 异步调用方法
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-11
 * @desc    异步运行url地址方法
 * @param   [string]          $url [url地址 支持get参数]
 */
function SyncJob($url, $port = 80, $time = 30)
{
    // curl
    if(function_exists('curl_init'))
    {
        CurlGet($url, 1);
        return true;

    // fsockopen
    } elseif(function_exists('fsockopen'))
    {
        $url_str = str_replace(array('http://', 'https://'), '', $url);
        $location = strpos($url_str, '/');
        $host = substr($url_str, 0, $location);
        $fp = fsockopen($host, $port, $errno, $errstr, $time);
        if($fp)
        {
            $out = "GET ".str_replace($host, '', $url_str)." HTTP/1.1\r\n";
            $out .= "Host: ".$host."\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fputs($fp, $out);
            fclose($fp);
        }
        return true;
    }
    return false;
}

/**
 * 公共返回数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-16
 * @desc    description
 * @param   [string]       $msg  [提示信息]
 * @param   [int]          $code [状态码]
 * @param   [mixed]        $data [数据]
 * @return  [json]               [json数据]
 */
function DataReturn($msg = '', $code = 0, $data = '')
{
    // 默认情况下，手动调用当前方法
    $result = ['msg'=>$msg, 'code'=>$code, 'data'=>$data];

    // 错误情况下，防止提示信息为空
    if($result['code'] != 0 && empty($result['msg']))
    {
        $result['msg'] = MyLang('operate_fail');
    }

    return $result;
}

/**
 * 获取当前脚本名称
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-06-20
 * @desc    description
 */
function CurrentScriptName()
{
    $name = '';
    if(empty($_SERVER['SCRIPT_NAME']))
    {
        if(empty($_SERVER['PHP_SELF']))
        {
            if(!empty($_SERVER['SCRIPT_FILENAME']))
            {
                $name = $_SERVER['SCRIPT_FILENAME'];
            }
        } else {
            $name = $_SERVER['PHP_SELF'];
        }
    } else {
        $name = $_SERVER['SCRIPT_NAME'];
    }
    if(!empty($name))
    {
        $loc = strripos($name, '/');
        if($loc !== false)
        {
            $name = substr($name, $loc+1);
        } 
    }
    return $name;
}

/**
 * 生成url地址
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-12
 * @desc    description
 * @param   string          $path      [路径地址]
 * @param   array           $params    [参数]
 */
function MyUrl($path, $params = [])
{
    // 空或数组则返回空字符串
    if(empty($path) || is_array($path))
    {
        return '';
    }

    // 当前脚本名称
    $script_name = CurrentScriptName();

    // url模式
    $url_model = MyC('home_seo_url_model', 0);

    // 模块组状态
    $is_api = (substr($path, 0, 4) == 'api/');
    $is_admin = (substr($path, 0, 6) == 'admin/');
    $is_index = (substr($path, 0, 6) == 'index/');
    $is_install = (substr($path, 0, 8) == 'install/');

    // 调用框架生成url
    $url = url($path, $params, true);

    // 非 admin 则使用配置后缀
    if(!$is_admin && !$is_install)
    {
        $url = $url->suffix(MyFileConfig('home_seo_url_html_suffix', '', 'html', true));
    }

    // 转 url字符串
    $url = MyConfig('shopxo.domain_url').substr((string) $url, 1);

    // 去除组名称
    $ds = ($script_name == 'index.php') ? '/' : '';
    $join = ($script_name != 'index.php' || $url_model == 0) ? $ds.'?s=' : '/';
    $len = $is_api ? 4 : ($is_install ? 8 : 6);
    $path = substr($path, $len);
    $url = str_replace('/'.$path, $join.$path, $url);

    // 避免非当前目录生成url索引错误
    if($script_name != 'index.php' && $is_index)
    {
        if($url_model == 0)
        {
            // 替换索引为 index.php
            $url = str_replace($script_name, 'index.php', $url);
        } else {
            // 去除入口和?s=
            $url = str_replace([$script_name.'?s=', '/'.$script_name], '', $url);
        }
    }
    // 替换索引为 api.php
    if($script_name != 'api.php' && $is_api)
    {
        $url = str_replace($script_name, 'api.php', $url);
    }
    // 替换索引为 install.php
    if($script_name != 'install.php' && $is_install)
    {
        $url = str_replace($script_name, 'install.php', $url);
    }

    // 前端则去除 index.php
    $url = str_replace('/index.php', '', $url);

    // 是否根目录访问项目
    if(defined('IS_ROOT_ACCESS'))
    {
        $url = str_replace('/public/', '/', $url);
    }

    return $url;
}

/**
 * 生成url地址 - 应用前端
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-12
 * @desc    description
 * @param   string          $plugins_name      [应用名称]
 * @param   string          $plugins_control   [应用控制器]
 * @param   string          $plugins_action    [应用方法]
 * @param   array           $params            [参数]
 */
function PluginsHomeUrl($plugins_name, $plugins_control = '', $plugins_action = '', $params = [])
{
    // 控制器和方法都为index的时候置空、缩短url地址
    if($plugins_control == 'index' && $plugins_action == 'index' && empty($params))
    {
        $plugins_control = '';
        $plugins_action = '';
    }

    // 插件基础参数
    $plugins = [
        'pluginsname'       => $plugins_name,
        'pluginscontrol'    => $plugins_control,
        'pluginsaction'     => $plugins_action,
    ];
    $path = 'index/plugins/index';
    return MyUrl($path, $plugins+$params);
}

/**
 * 生成url地址 - 应用后端
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-12
 * @desc    description
 * @param   string          $plugins_name      [应用名称]
 * @param   string          $plugins_control   [应用控制器]
 * @param   string          $plugins_action    [应用方法]
 * @param   array           $params            [参数]
 */
function PluginsAdminUrl($plugins_name, $plugins_control, $plugins_action, $params = [])
{
    // 插件基础参数
    $plugins = [
        'pluginsname'       => $plugins_name,
        'pluginscontrol'    => $plugins_control,
        'pluginsaction'     => $plugins_action,
    ];
    $path = 'admin/plugins/index';
    return MyUrl($path, $plugins+$params);
}

/**
 * 金额美化
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-04-12T16:54:51+0800
 * @param    [float]                  $price   [金额]
 * @param    [mixed]                  $default [默认值]
 */
function PriceBeautify($price = 0, $default = '')
{
    if(empty($price))
    {
        return $default;
    }

    $price = str_replace('.00', '', $price);
    if(strpos($price, '.') !== false)
    {
        if(substr($price, -1) == 0)
        {
            $price = substr($price, 0, -1);
        }
        if(substr($price, -1) == '.')
        {
            $price = substr($price, 0, -1);
        }
    }
    return $price;
}

/**
 * 文件上传错误校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-04-12T17:21:51+0800
 * @param    [string]     $name [表单name]
 * @param    [int]        $index[多文件索引]
 * @return   [mixed]            [true | 错误信息]
 */
function FileUploadError($name, $index = false)
{
    // 是否存在该name表单
    if($index === false)
    {
        if(empty($_FILES[$name]))
        {
            return MyLang('form_upload_file_message');
        }
    } else {
        if(empty($_FILES[$name]['name'][$index]))
        {
            return MyLang('form_upload_file_message');
        }
    }

    // 是否正常
    $error = null;
    if($index === false)
    {
        if($_FILES[$name]['error'] == 0)
        {
            return true;
        }
        $error = $_FILES[$name]['error'];
    } else {
        if($_FILES[$name]['error'][$index] == 0)
        {
            return true;
        }
        $error = $_FILES[$name]['error'][$index];
    }

    // 错误码对应的错误信息
    $file_error_list = MyConst('common_file_upload_error_list');
    if(isset($file_error_list[$error]))
    {
        return $file_error_list[$error];
    }
    return MyLang('error').'[file error '.$error.']';
}

/**
 * 公共数据翻转
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-04-07T11:32:02+0800
 * @param    [array]       $data        [需要翻转的数据]
 * @param    [mixed]       $default     [默认值]
 * @param    [string]      $value_field [value值字段名称]
 * @param    [string]      $name_field  [name值字段名称]
 * @return   [array]                    [翻转后的数据]
 */
function LangValueKeyFlip($data, $default = false, $value_field = 'id', $name_field = 'name')
{
    $result = array();
    if(!empty($data) && is_array($data))
    {
        foreach($data as $k=>$v)
        {
            $result[$v[$name_field]] = $v[$value_field];
            if(isset($v['checked']) && $v['checked'] == true)
            {
                $result['default'] = $v[$value_field];
            }
        }
    }
    if($default !== false)
    {
        $result['default'] = $default;
    }
    return $result;
}

/**
 * 科学数字转换成原始的数字
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-04-06T17:21:51+0800
 * @param    [int]   $num [科学数字]
 * @return   [string]     [数据原始的值]
 */
function ScienceNumToString($num)
{
    if(stripos($num, 'e') === false) return $num;

    // 出现科学计数法，还原成字符串 
    $num = trim(preg_replace('/[=\'"]/','',$num,1),'"');
    $result = ''; 
    while($num > 0)
    { 
        $v = $num-floor($num/10)*10; 
        $num = floor($num/10); 
        $result   =   $v.$result; 
    }
    return $result; 
}

/**
 * 客户端ip地址
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-02-09T12:53:13+0800
 * @param    [boolean]        $long     [是否将ip转成整数]
 * @return   [string|int]               [ip地址|ip地址整数]
 * @param    [boolean]        $is_single[是否仅获取一个ip]
 */
function GetClientIP($long = false, $is_single = true)
{
    $ip = '';
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
    { 
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
    {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
    {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // 整数或单ip
    if($long || $is_single)
    {
        // 单ip
        if($is_single && stripos($ip, ',') !== false)
        {
            $temp = explode(',', $ip);
            $ip = $temp[0];
        }
        // 转整数
        if($long)
        {
            $ip = sprintf("%u", ip2long($ip));
        }
    }
    return $ip;
}

/**
 * url参数拼接
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-09T23:33:44+0800
 * @param    [array]      $param [url参数一维数组]
 * @return   [string]            [url参数字符串]
 */
function UrlParamJoin($param)
{
    $string = '';
    if(!empty($param) && is_array($param))
    {
        foreach($param as $k=>$v)
        {
            if(is_string($v))
            {
                $string .= $k.'='.$v.'&';
            }
        }
        if(!empty($string))
        {
            $join = (MyC('home_seo_url_model', 0) == 0) ? '&' : '?';
            $string = $join.substr($string, 0, -1);
        }
    }
    return $string;
}

/**
 * 读取站点配置信息
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-13
 * @desc    description
 * @param   [string]    $key           [索引名称]
 * @param   [mixed]     $default       [默认值]
 * @param   [boolean]   $mandatory     [是否强制校验值,默认false]
 * @return  [mixed]                    [配置信息值,没找到返回null]
 */
function MyC($key, $default = null, $mandatory = false)
{
    $data = MyCache($key);
    if($mandatory === true)
    {
        return empty($data) ? $default : $data;
    }
    return ($data === null) ? $default : $data;
}

/**
 * 清空目录下所有文件
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-21T19:25:57+0800
 * @param    [string]    $dir_path [目录地址]
 * @return   [boolean]             [成功true, 失败false]
 */
function EmptyDir($dir_path)
{
    if(is_dir($dir_path))
    {
        $dn = @opendir($dir_path);
        if($dn !== false)
        {
            while(false !== ($file = readdir($dn)))
            {
                if($file != '.' && $file != '..')
                {
                    if(!unlink($dir_path.$file))
                    {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    return true;
}

/**
 * 计算符串长度（中英文一致）
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-13T21:34:09+0800
 * @param    [string]      $string [需要计算的字符串]
 * @return   [int]                 [字符长度]
 */
function Utf8Strlen($string = null)
{
    preg_match_all("/./us", $string, $match);
    return count($match[0]);
}

/**
 * 是否是手机访问
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-03-20
 * @desc    description
 * @param   [string]          $agent [是否指定agent信息]
 * @return  [boolean]                [手机访问true, 则false]
 */
function IsMobile($agent = '')
{
    /* 如果有HTTP_X_WAP_PROFILE则一定是移动设备 */
    if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) return true;
    
    /* 此条摘自TPM智能切换模板引擎，适合TPM开发 */
    if(isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT']) return true;
    
    /* 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 */
    if(isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap') !== false) return true;
    
    /* 判断手机发送的客户端标志,兼容性有待提高 */
    $agent = empty($agent) ? (empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT']) : $agent;
    if($agent)
    {
        $clientkeywords = array(
            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipad','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
        );
        /* 从HTTP_USER_AGENT中查找手机浏览器的关键字 */
        if(preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($agent))) {
            return true;
        }
    }

    /* 协议法，因为有可能不准确，放到最后判断 */
    if(isset($_SERVER['HTTP_ACCEPT']))
    {
        /* 如果只支持wml并且不支持html那一定是移动设备 */
        /* 如果支持wml和html但是wml在html之前则是移动设备 */
        if((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) return true;
    }
    return false;
}


/**
 * 校验json数据是否合法
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $jsonstr [需要校验的json字符串]
 * @return   [boolean] [合法true, 则false]
 */
function IsJson($jsonstr)
{
    if(PHP_VERSION > 5.3)
    {
        json_decode($jsonstr);
        return (json_last_error() == JSON_ERROR_NONE);
    } else {
        return is_null(json_decode($jsonstr)) ? false : true;
    }
}

/**
 * 请求get，支持本地文件
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-08-07
 * @desc    description
 * @param   [string]          $value        [本地文件路径或者远程url地址]
 * @param   [int]             $timeout      [超时时间（默认10秒）]
 */
function RequestGet($value, $timeout = 10)
{
    // 远程
    if(substr($value, 0, 4) == 'http')
    {
        // 是否有curl模块
        if(function_exists('curl_init'))
        {
            return CurlGet($value, $timeout);
        }
        return file_get_contents($value);
    }

    // 本地文件
    return file_exists($value) ? file_get_contents($value) : '';
}

/**
 * curl模拟get请求
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-01-03T19:21:38+0800
 * @param    [string]   $url            [url地址]
 * @param    [int]      $timeout        [超时时间（默认10秒）]
 * @param    [string]   $request_type   [请求类型（GET、POST、PUT、DELETE）]
 * @return   [array]                    [返回数据]
 */
function CurlGet($url, $timeout = 10, $request_type = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    if(!empty($request_type))
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_type);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * curl模拟post
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string]   $url            [请求地址]
 * @param    [array]    $post           [发送的post数据]
 * @param    [int]      $data_type      [数据类型（0普通参数、1json、2文件）]
 * @param    [int]      $timeout        [超时时间]
 * @param    [string]   $request_type   [请求类型（GET、POST、PUT、DELETE）]
 * @return   [mixed]                    [请求返回的数据]
 */
function CurlPost($url, $post, $data_type = 0, $timeout = 30, $request_type = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    if(empty($request_type))
    {
        curl_setopt($ch, CURLOPT_POST, true);
    } else {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_type);
    }

    // 根据数据类型处理
    switch($data_type)
    {
        // 是否json
        case 1 :
            $data_string = json_encode($post);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        "Content-Type: application/json; charset=utf-8",
                        "Content-Length: " . strlen($data_string)
                    ]
                );
            break;

        // 是否存在文件上传对象
        case 2 :
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: multipart/form-data; charset=utf-8",
                    "cache-control: no-cache"
                ]
            );
            break;

        default :
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
                    "cache-control: no-cache"
                ]
            );
    }

    // 返回结果
    $result = curl_exec($ch);
    if($result !== false)
    {
        curl_close($ch);
        return DataReturn('success', 0, $result);
    } else { 
        $error_code = curl_errno($ch);
        $error_msg = curl_error($ch);
        curl_close($ch);
        return DataReturn($error_msg.' ('.$error_code.')', -9999, $error_code);
    }
}

/**
 * fsockopen方式
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $url  [url地址]
 * @param    [string] $data [发送参数]
 */
function FsockopenPost($url, $data = '')
{
    $row = parse_url($url);
    $host = $row['host'];
    $port = isset($row['port']) ? $row['port'] : 80;
    $file = $row['path'];
    $post = '';
    while (list($k,$v) = FunEach($data)) 
    {
        if(isset($k) && isset($v)) $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
    }
    $post = substr( $post , 0 , -1 );
    $len = strlen($post);
    $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
    if(!$fp) {
        return "$errstr ($errno)\n";
    } else {
        $receive = '';
        $out = "POST $file HTTP/1.0\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Content-Length: $len\r\n\r\n";
        $out .= $post;    
        fwrite($fp, $out);
        while (!feof($fp)) {
          $receive .= fgets($fp, 128);
        }
        fclose($fp);
        $receive = explode("\r\n\r\n",$receive);
        unset($receive[0]);
        return implode("",$receive);
    }
}

/**
 * xml转数组
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [xml]       $xml [xml数据]
 * @return   [array]          [array数组]
 */
function XmlArray($xml) {
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

/**
 * 判断字符串是否为xml格式
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-01-07
 * @desc    description
 * @param   [string]          $string [字符串]
 */
function XmlParser($string)
{
    $xml_parser = xml_parser_create();
    if(!xml_parse($xml_parser, $string, true))
    {
      xml_parser_free($xml_parser);
      return false;
    } else {
      return (json_decode(json_encode(simplexml_load_string($string)),true));
    }
}

/**
 * 手机号码格式校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [int] $mobile [手机号码]
 * @return   [boolean]     [正确true，失败false]
 */
function CheckMobile($mobile)
{
    return (preg_match('/'.MyConst('common_regex_mobile').'/', $mobile) == 1) ? true : false;
}

/**
 * 电话号码格式校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $tel    [电话号码]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckTel($tel)
{
    return (preg_match('/'.MyConst('common_regex_tel').'/', $tel) == 1) ? true : false;
}

/**
 * 电子邮箱格式校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $email  [电子邮箱]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckEmail($email)
{
    return (preg_match('/'.MyConst('common_regex_email').'/', $email) == 1) ? true : false;
}

/**
 * 身份证号码格式校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $number [身份证号码]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckIdCard($number)
{
    return (preg_match('/'.MyConst('common_regex_id_card').'/', $number) == 1) ? true : false;
}

/**
 * 价格格式校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [float]  $price  [价格]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckPrice($price)
{
    return (preg_match('/'.MyConst('common_regex_price').'/', $price) == 1) ? true : false;
}


/**
 * ip校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $ip [ip]
 */
function CheckIp($ip)
{
    return (preg_match('/'.MyConst('common_regex_ip').'/', $ip) == 1) ? true : false;
}

/**
 * url校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $url [url地址]
 */
function CheckUrl($url)
{
    return (preg_match('/'.MyConst('common_regex_url').'/', $url) == 1) ? true : false;
}

/**
 * 版本号校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $ver [版本]
 */
function CheckVersion($ver)
{
    return (preg_match('/'.MyConst('common_regex_version').'/', $ver) == 1) ? true : false;
}

/**
 * 用户名校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $string [用户名]
 * @return   [boolean]        [成功true, 失败false]
 */
function CheckUserName($string)
{
    return (preg_match('/'.MyConst('common_regex_username').'/', $string) == 1) ? true : false;
}

/**
 * 排序值校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [int] $value  [数据值]
 * @return   [boolean]     [成功true, 失败false]
 */
function CheckSort($value)
{
    $temp = intval($value);
    return ($temp >= 0 && $temp <= 255);
}

/**
 * 颜色值校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $value [数据值]
 */
function CheckColor($value)
{
    return (preg_match('/'.MyConst('common_regex_color').'/', $value) == 1) ? true : false;
}

/**
 * 密码格式校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $string [登录密码]
 * @return   [boolean]        [正确true, 错误false]
 */
function CheckLoginPwd($string)
{
    return (preg_match('/'.MyConst('common_regex_pwd').'/', $string) == 1) ? true : false;
}

/**
 * 包含字母和数字
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $string [登录密码]
 * @return   [boolean]        [正确true, 错误false]
 */
function CheckAlphaNumber($string)
{
    return (preg_match('/'.MyConst('common_regex_alpha_number').'/', $string) == 1) ? true : false;
}

/**
 * 检测一张网络图片是否存在
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $url [图片地址]
 * @return   [boolean]     [存在true, 则false]
 */
function IsExistRemoteImage($url)
{
    if(!empty($url))
    {
        $content = get_headers($url, 1);
        if(!empty($content[0]))
        {
            return preg_match('/200/',$content[0]);
        }
    }
    return false;
}

/**
 * 随机数生成生成
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [int] $length [生成位数]
 * @return   [int]         [生成的随机数]
 */
function GetNumberCode($length = 6)
{
    $code = '';
    for($i=0; $i<intval($length); $i++) $code .= rand(0, 9);
    return $code;
}

/**
 * 登录密码加密
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $pwd  [需要加密的密码]
 * @param    [string] $salt [配合密码加密的随机数]
 * @return   [string]       [加密后的密码]
 */
function LoginPwdEncryption($pwd, $salt)
{
    return md5($salt.trim($pwd));
}

/**
 * 支付密码加密
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $pwd  [需要加密的密码]
 * @param    [string] $salt [配合密码加密的随机数]
 * @return   [string]       [加密后的密码]
 */
function PwdPayEncryption($pwd, $salt)
{
    return md5(md5(trim($pwd).$salt));
}

/**
 * 密码强度校验
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $pwd [需要校验的密码]
 * @return   [int]         [密码强度值0~10]
 */
function PwdStrength($pwd)
{ 
    $score = 0; 
    if(preg_match("/[0-9]+/", $pwd)) $score ++; 
    if(preg_match("/[0-9]{3,}/", $pwd)) $score ++;
    if(preg_match("/[a-z]+/", $pwd)) $score ++;
    if(preg_match("/[a-z]{3,}/", $pwd)) $score ++;
    if(preg_match("/[A-Z]+/", $pwd)) $score ++;
    if(preg_match("/[A-Z]{3,}/", $pwd)) $score ++;
    if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/", $pwd)) $score += 2;
    if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/", $pwd)) $score ++ ;
    if(strlen($pwd) >= 10) $score ++;
    return $score;
}
 
/**
 * 坐标距离计算
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 *  $lng = '116.655540';
 *  $lat = '39.910980';
 *  $squares = returnSquarePoint($lng, $lat);
 *       
 *  print_r($squares);
 *  $info_sql = "select id,locateinfo,lat,lng from `lbs_info` where lat<>0 and lat>{$squares['right-bottom']['lat']} and lat<{$squares['left-top']['lat']} and lng>{$squares['left-top']['lng']} and lng<{$squares['right-bottom']['lng']} ";
 *  计算某个经纬度的周围某段距离的正方形的四个点
 *
 *  param lng float 经度
 *  param lat float 纬度
 *  param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为1.2千米
 *  return array 正方形的四个点的经纬度坐标
 */
function ReturnSquarePoint($lng, $lat, $distance = 1.2)
{
    /* 地球半径，平均半径为6371km */
    $radius = 6371;

    $d_lng =  2 * asin(sin($distance / (2 * $radius)) / cos(deg2rad($lat)));
    $d_lng = rad2deg($d_lng);

    $d_lat = $distance/$radius;
    $d_lat = rad2deg($d_lat);

    return array(
        'left-top'=>array('lat'=>$lat + $d_lat,'lng'=>$lng-$d_lng),
        'right-top'=>array('lat'=>$lat + $d_lat, 'lng'=>$lng + $d_lng),
        'left-bottom'=>array('lat'=>$lat - $d_lat, 'lng'=>$lng - $d_lng),
        'right-bottom'=>array('lat'=>$lat - $d_lat, 'lng'=>$lng + $d_lng)
    );
}

/**
 * 明文或密文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string]  $string    [明文或密文]
 * @param    [string]  $operation [加密ENCODE, 解密DECODE]
 * @param    [string]  $key       [密钥]
 * @param    [integer] $expiry    [密钥有效期]
 * @return   [string]             [加密或解密后的数据]
 */
function Authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥
    $ckey_length = 4;
  
    // 密匙
    $key = md5(empty($key) ? MyC('common_data_encryption_secret', 'shopxo', true) : $key); 
  
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for($i = 0; $i <= 255; $i++)
    {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++)
    {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++)
    {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE')
    {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if($result !== '' && $result !== null && (substr($result, 0, 10) == 0 || intval(substr($result, 0, 10)) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
        {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

/**
 * 参数校验方法
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-12-12T15:26:13+0800
 * @param    [array]                   $data   [原始数据]
 * @param    [array]                   $params [校验数据]
 * @return   [boolean|string]                  [成功true, 失败 错误信息]
 */
function ParamsChecked($data, $params)
{
    if(empty($params) || !is_array($data) || !is_array($params))
    {
        return MyLang('common_function.check_config_error_tips');
    }

    foreach($params as $v)
    {
        if(empty($v['key_name']) || empty($v['error_msg']))
        {
            return MyLang('common_function.check_config_error_tips');
        }

        // 是否需要验证
        $is_checked = true;

        // 数据或字段存在则验证
        // 1 数据存在则验证
        // 2 字段存在则验证
        if(isset($v['is_checked']))
        {
            if($v['is_checked'] == 1)
            {
                if(empty($data[$v['key_name']]))
                {
                    $is_checked = false;
                }
            } else if($v['is_checked'] == 2)
            {
                if(!isset($data[$v['key_name']]))
                {
                    $is_checked = false;
                }
            }
        }

        // 是否需要验证
        if($is_checked === false)
        {
            continue;
        }

        // 数据类型,默认字符串类型
        $data_type = empty($v['data_type']) ? 'string' : $v['data_type'];

        // 验证规则，默认isset
        $checked_type = isset($v['checked_type']) ? $v['checked_type'] : 'isset';
        switch($checked_type)
        {
            // 是否存在
            case 'isset' :
                if(!array_key_exists($v['key_name'], $data))
                {
                    return $v['error_msg'];
                }
                break;

            // 是否为空
            case 'empty' :
                if(empty($data[$v['key_name']]))
                {
                    return $v['error_msg'];
                }
                break;

            // 是否存在于验证数组中
            case 'in' :
                if(empty($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_in_empty_tips').'['.$v['key_name'].']';
                }
                if(!is_array($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_in_error_tips').'['.$v['key_name'].']';
                }
                if(!isset($data[$v['key_name']]) || !in_array($data[$v['key_name']], $v['checked_data']))
                {
                    return $v['error_msg'];
                }
                break;

            // 是否为数组
            case 'is_array' :
                if(!isset($data[$v['key_name']]) || !is_array($data[$v['key_name']]))
                {
                    return $v['error_msg'];
                }
                break;

            // 长度
            case 'length' :
                if(!isset($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_length_empty_tips').'['.$v['key_name'].']';
                }
                if(!isset($data[$v['key_name']]))
                {
                    return $v['error_msg'];
                }
                if($data_type == 'array')
                {
                    $length = count($data[$v['key_name']]);
                } else {
                    $length = mb_strlen($data[$v['key_name']], 'utf-8');
                }
                $rule = explode(',', $v['checked_data']);
                if(count($rule) == 1)
                {
                    if($length > intval($rule[0]))
                    {
                        return $v['error_msg'];
                    }
                } else {
                    if($length < intval($rule[0]) || $length > intval($rule[1]))
                    {
                        return $v['error_msg'];
                    }
                }
                break;

            // 自定义函数
            case 'fun' :
                if(empty($v['checked_data']) || !function_exists($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_fun_error_tips').'['.$v['key_name'].']';
                }
                $fun = $v['checked_data'];
                if(!isset($data[$v['key_name']]) || !$fun($data[$v['key_name']]))
                {
                    return $v['error_msg'];
                }
                break;

            // 最小
            case 'min' :
                if(!isset($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_min_error_tips').'['.$v['key_name'].']';
                }
                if(!isset($data[$v['key_name']]) || $data[$v['key_name']] < $v['checked_data'])
                {
                    return $v['error_msg'];
                }
                break;

            // 最大
            case 'max' :
                if(!isset($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_max_error_tips').'['.$v['key_name'].']';
                }
                if(!isset($data[$v['key_name']]) || $data[$v['key_name']] > $v['checked_data'])
                {
                    return $v['error_msg'];
                }
                break;

            // 相等
            case 'eq' :
                if(!isset($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_eq_error_tips').'['.$v['key_name'].']';
                }
                if(!isset($data[$v['key_name']]) || $data[$v['key_name']] == $v['checked_data'])
                {
                    return $v['error_msg'];
                }
                break;

            // 不相等
            case 'neq' :
                if(!isset($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_neq_error_tips').'['.$v['key_name'].']';
                }
                if(!isset($data[$v['key_name']]) || $data[$v['key_name']] != $v['checked_data'])
                {
                    return $v['error_msg'];
                }
                break;

            // 数据库唯一
            case 'unique' :
                if(!isset($v['checked_data']))
                {
                    return MyLang('common_function.check_checked_data_unique_empty_tips').'['.$v['key_name'].']';
                }
                if(empty($data[$v['key_name']]))
                {
                    return str_replace('{$var}', MyLang('common_function.check_checked_data_unique_error_name'), $v['error_msg']);
                }
                $temp = \think\facade\Db::name($v['checked_data'])->where([$v['key_name']=>$data[$v['key_name']]])->find();
                if(!empty($temp))
                {
                    // 错误数据变量替换
                    $error_msg = str_replace('{$var}', $data[$v['key_name']], $v['error_msg']);

                    // 是否需要排除当前操作数据
                    if(isset($v['checked_key']))
                    {
                        if(empty($data[$v['checked_key']]) || (isset($temp[$v['checked_key']]) && $temp[$v['checked_key']] != $data[$v['checked_key']]))
                        {
                            return $error_msg;
                        }
                    } else {
                        return $error_msg;
                    }
                }
                break;
        }
    }
    return true;
}

// php系统方法不存在则定义
if(!function_exists('str_starts_with'))
{
    /**
     * 检查字符串是否以给定的子字符串开头
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-15
     * @desc    description
     * @param   [string]          $haystack [待检查的字符串]
     * @param   [string]          $needle   [需检查的字符串]
     * @return  [boolean]                   [true|false]
     */
    function str_starts_with($haystack, $needle)
    {
        $len = mb_strlen($needle, 'utf-8');
        return mb_substr($haystack, 0, $len, 'utf-8') === $needle;
    }
}
if(!function_exists('str_ends_with'))
{
    /**
     * 检查字符串是否以给定的子字符串结尾
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-15
     * @desc    description
     * @param   [string]          $haystack [待检查的字符串]
     * @param   [string]          $needle   [需检查的字符串]
     * @return  [boolean]                   [true|false]
     */
    function str_ends_with($haystack, $needle)
    {
        $len = mb_strlen($needle, 'utf-8');
        return mb_substr($haystack, -1, $len, 'utf-8') === $needle;
    }
}
?>