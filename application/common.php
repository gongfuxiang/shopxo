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

// 应用公共文件

/**
 * 钩子返回数据处理，是否存在错误
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-12-02
 * @desc    description
 * @param   [array]          $data [钩子返回的数据]
 */
function HookReturnHandle($data)
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
    return DataReturn('无钩子信息', 0);
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
    return app\service\ResourcesService::AttachmentPathViewHandle($value);
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
    if(app\service\PluginsService::PluginsStatus($plugins) != 1)
    {
        return DataReturn('插件状态异常['.$plugins.']', -1);
    }

    // 查看是否存在基础服务层并且定义获取基础配置方法
    $plugins_class = 'app\plugins\\'.$plugins.'\service\BaseService';
    if(class_exists($plugins_class) && method_exists($plugins_class, 'BaseConfig'))
    {
        return $plugins_class::BaseConfig();
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
    return app\service\PluginsService::PluginsData($plugins, $attachment);
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
            if(app\service\PluginsService::PluginsStatus($plugins) != 1)
            {
                return DataReturn('插件状态异常['.$plugins.']', -1);
            }

            // 调用方法返回数据
            return $plugins_class::$method($params);
        } else {
            return DataReturn('类方法未定义['.$plugins.'-'.$service.'-'.$method.']', -1);
        }
    }
    return DataReturn('类未定义['.$plugins.'-'.$service.']', -1);
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
    }
    return null;
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
        $color = $hex_color;
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
 * [FileSizeByteToUnit 文件大小转常用单位]
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
}

/**
 * [DataReturn 公共返回数据]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-07T22:03:40+0800
 * @param    [string]       $msg  [提示信息]
 * @param    [int]          $code [状态码]
 * @param    [mixed]        $data [数据]
 * @return   [json]               [json数据]
 */
function DataReturn($msg = '', $code = 0, $data = '')
{
    // ajax的时候，success和error错误由当前方法接收
    if(IS_AJAX)
    {
        if(isset($msg['info']))
        {
            // success模式下code=0, error模式下code参数-1
            $result = array('msg'=>$msg['info'], 'code'=>-1, 'data'=>'');
        }
    }
    
    // 默认情况下，手动调用当前方法
    if(empty($result))
    {
        $result = array('msg'=>$msg, 'code'=>$code, 'data'=>$data);
    }

    // 错误情况下，防止提示信息为空
    if($result['code'] != 0 && empty($result['msg']))
    {
        $result['msg'] = '操作失败';
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
function MyUrl($path, $params=[])
{
    // 调用框架生成url
    $url = url($path, $params, true, true);

    // 是否根目录访问项目
    if(defined('IS_ROOT_ACCESS'))
    {
        $url = str_replace('public/', '', $url);
    }

    // tp框架url方法是否识别到https
    if(__MY_HTTP__ == 'https' && substr($url, 0, 5) != 'https')
    {
        $url = 'https'.mb_substr($url, 4, null, 'utf-8');
    }

    // 避免从后台生成url入口错误
    $script_name = CurrentScriptName();
    if($script_name != 'index.php' && substr($path, 0, 6) != 'admin/')
    {
        $url = str_replace($script_name, 'index.php', $url);
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
function PluginsHomeUrl($plugins_name, $plugins_control, $plugins_action, $params=[])
{
    $plugins = [
        'pluginsname'       => $plugins_name,
        'pluginscontrol'    => $plugins_control,
        'pluginsaction'     => $plugins_action,
    ];
    $url = url('index/plugins/index', $plugins+$params, true, true);

    // 是否根目录访问项目
    if(defined('IS_ROOT_ACCESS'))
    {
        $url = str_replace('public/', '', $url);
    }

    // tp框架url方法是否识别到https
    if(__MY_HTTP__ == 'https' && substr($url, 0, 5) != 'https')
    {
        $url = 'https'.mb_substr($url, 4, null, 'utf-8');
    }

    // 避免从后台生成url入口错误
    $url = str_replace(CurrentScriptName(), 'index.php', $url);

    return $url;
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
function PluginsAdminUrl($plugins_name, $plugins_control, $plugins_action, $params=[])
{
    $plugins = [
        'pluginsname'       => $plugins_name,
        'pluginscontrol'    => $plugins_control,
        'pluginsaction'     => $plugins_action,
    ];
    $url = url('admin/plugins/index', $plugins+$params, true, true);

    // 是否根目录访问项目
    if(defined('IS_ROOT_ACCESS'))
    {
        $url = str_replace('public/', '', $url);
    }

    // tp框架url方法是否识别到https
    if(__MY_HTTP__ == 'https' && substr($url, 0, 5) != 'https')
    {
        $url = 'https'.mb_substr($url, 4, null, 'utf-8');
    }

    return $url;
}

/**
 * [PriceBeautify 金额美化]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-04-12T16:54:51+0800
 * @param    [float]                  $price   [金额]
 * @param    [mixed]                  $default [默认值]
 */
function PriceBeautify($price = 0, $default = null)
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
 * [FileUploadError 文件上传错误校验]
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
            return '请选择需要上传的文件';
        }
    } else {
        if(empty($_FILES[$name]['name'][$index]))
        {
            return '请选择需要上传的文件';
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
    $file_error_list = lang('common_file_upload_error_list');
    if(isset($file_error_list[$error]))
    {
        return $file_error_list[$error];
    }
    return '未知错误'.'[file error '.$error.']';
}

/**
 * [LangValueKeyFlip 公共数据翻转]
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
 * [ScienceNumToString 科学数字转换成原始的数字]
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
 * [GetClientIP 客户端ip地址]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-02-09T12:53:13+0800
 * @param    [boolean]        $long [是否将ip转成整数]
 * @return   [string|int]           [ip地址|ip地址整数]
 */
function GetClientIP($long = false)
{
    $onlineip = ''; 
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
    { 
        $onlineip = getenv('HTTP_CLIENT_IP'); 
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
    {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR'); 
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
    {
        $onlineip = getenv('REMOTE_ADDR'); 
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
    {
        $onlineip = $_SERVER['REMOTE_ADDR']; 
    } 
    if($long)
    {
        $onlineip = sprintf("%u", ip2long($onlineip));
    }
    return $onlineip;
}

/**
 * [UrlParamJoin url参数拼接]
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
            $url_model= config('URL_MODEL');
            $join_tag = ($url_model == 0 || $url_model == 3) ? '&' : '?';
            $string = $join_tag.substr($string, 0, -1);
        }
    }
    return $string;
}

/**
 * [MyC 读取站点配置信息]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-29T17:17:25+0800
 * @param    [string]    $key           [索引名称]
 * @param    [mixed]     $default       [默认值]
 * @param    [boolean]   $mandatory     [是否强制校验值,默认false]
 * @return   [mixed]                    [配置信息值,没找到返回null]
 */
function MyC($key, $default = null, $mandatory = false)
{
    $data = cache(config('shopxo.cache_common_my_config_key'));
    if($mandatory === true)
    {
        return empty($data[$key]) ? $default : $data[$key];
    }
    return isset($data[$key]) ? $data[$key] : $default;
}

/**
 * [EmptyDir 清空目录下所有文件]
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
 * [Utf8Strlen 计算符串长度（中英文一致）]
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
 * [IsMobile 是否是手机访问]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-05T10:52:20+0800
 * @return  [boolean] [手机访问true, 则false]
 */
function IsMobile()
{
    /* 如果有HTTP_X_WAP_PROFILE则一定是移动设备 */
    if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) return true;
    
    /* 此条摘自TPM智能切换模板引擎，适合TPM开发 */
    if(isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT']) return true;
    
    /* 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 */
    if(isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap') !== false) return true;
    
    /* 判断手机发送的客户端标志,兼容性有待提高 */
    if(isset($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array(
            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipad','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
        );
        /* 从HTTP_USER_AGENT中查找手机浏览器的关键字 */
        if(preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
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
 * curl模拟post
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string]   $url        [请求地址]
 * @param    [array]    $post       [发送的post数据]
 * @param    [boolean]  $is_json    [是否使用 json 数据发送]
 * @return   [mixed]                [请求返回的数据]
 */
function CurlPost($url, $post, $is_json = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_URL, $url);

    // 是否 json
    if($is_json)
    {
        $data_string = json_encode($post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json; charset=utf-8",
                "Content-Length: " . strlen($data_string)
            )
        );
    } else {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            )
        );
    }

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
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
    $row = parse_MyUrl($url);
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
 * @param    [xml] $xmlstring [xml数据]
 * @return   [array]          [array数组]
 */
function XmlArray($xmlstring) {
    return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
}


/**
 * [CheckMobile 手机号码格式校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [int] $mobile [手机号码]
 * @return   [boolean]     [正确true，失败false]
 */
function CheckMobile($mobile)
{
    return (preg_match('/'.lang('common_regex_mobile').'/', $mobile) == 1) ? true : false;
}

/**
 * [CheckTel 电话号码格式校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $tel    [电话号码]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckTel($tel)
{
    return (preg_match('/'.lang('common_regex_tel').'/', $tel) == 1) ? true : false;
}

/**
 * [CheckEmail 电子邮箱格式校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $email  [电子邮箱]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckEmail($email)
{
    return (preg_match('/'.lang('common_regex_email').'/', $email) == 1) ? true : false;
}

/**
 * [CheckIdCard 身份证号码格式校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $number [身份证号码]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckIdCard($number)
{
    return (preg_match('/'.lang('common_regex_id_card').'/', $number) == 1) ? true : false;
}

/**
 * [CheckPrice 价格格式校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [float]  $price  [价格]
 * @return   [boolean]        [正确true，失败false]
 */
function CheckPrice($price)
{
    return (preg_match('/'.lang('common_regex_price').'/', $price) == 1) ? true : false;
}


/**
 * [CheckIp ip校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $ip [ip]
 */
function CheckIp($ip)
{
    return (preg_match('/'.lang('common_regex_ip').'/', $ip) == 1) ? true : false;
}

/**
 * [CheckUrl url校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $url [url地址]
 */
function CheckUrl($url)
{
    return (preg_match('/'.lang('common_regex_url').'/', $url) == 1) ? true : false;
}

/**
 * [CheckUserName 用户名校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $string [用户名]
 * @return   [boolean]        [成功true, 失败false]
 */
function CheckUserName($string)
{
    return (preg_match('/'.lang('common_regex_username').'/', $string) == 1) ? true : false;
}

/**
 * [CheckSort 排序值校验]
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
 * [CheckColor 颜色值校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $value [数据值]
 */
function CheckColor($value)
{
    return (preg_match('/'.lang('common_regex_color').'/', $value) == 1) ? true : false;
}

/**
 * [CheckLoginPwd 密码格式校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $string [登录密码]
 * @return   [boolean]        [正确true, 错误false]
 */
function CheckLoginPwd($string)
{
    return (preg_match('/'.lang('common_regex_pwd').'/', $string) == 1) ? true : false;
}

/**
 * [IsExistRemoteImage 检测一张网络图片是否存在]
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
 * [GetNumberCode 随机数生成生成]
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
 * [LoginPwdEncryption 登录密码加密]
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
 * [PwdPayEncryption 支付密码加密]
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
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * [PwdStrength 密码强度校验]
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
 *  param Distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
 *  return array 正方形的四个点的经纬度坐标
 */
function ReturnSquarePoint($lng, $lat, $Distance = 1.2)
{
    /* 地球半径，平均半径为6371km */
    $Radius = 6371;

    $d_lng =  2 * asin(sin($Distance / (2 * $Radius)) / cos(deg2rad($lat)));
    $d_lng = rad2deg($d_lng);

    $d_lat = $Distance/$Radius;
    $d_lat = rad2deg($d_lat);

    return array(
        'left-top'=>array('lat'=>$lat + $d_lat,'lng'=>$lng-$d_lng),
        'right-top'=>array('lat'=>$lat + $d_lat, 'lng'=>$lng + $d_lng),
        'left-bottom'=>array('lat'=>$lat - $d_lat, 'lng'=>$lng - $d_lng),
        'right-bottom'=>array('lat'=>$lat - $d_lat, 'lng'=>$lng + $d_lng)
    );
}

/**
 * [Authcode 明文或密文]
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
function Authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥
    $ckey_length = 4;
  
    // 密匙
    // $GLOBALS['discuz_auth_key'] 这里可以根据自己的需要修改
    $key = md5($key ? $key : 'devil'); 
  
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
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
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
 * [ParamsChecked 参数校验方法]
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
        return '内部调用参数配置有误';
    }

    foreach ($params as $v)
    {
        if(empty($v['key_name']) || empty($v['error_msg']))
        {
            return '内部调用参数配置有误';
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
                if(!isset($data[$v['key_name']]))
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
                if(empty($v['checked_data']) || !is_array($v['checked_data']))
                {
                    return '内部调用参数配置有误';
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
                    return '长度规则值未定义';
                }
                if(!is_string($v['checked_data']))
                {
                    return '内部调用参数配置有误';
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
                    return '验证函数为空或函数未定义';
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
                    return '验证最小值未定义';
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
                    return '验证最大值未定义';
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
                    return '验证相等未定义';
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
                    return '验证相等未定义';
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
                    return '验证唯一表参数未定义';
                }
                if(empty($data[$v['key_name']]))
                {
                    return $v['error_msg'];
                }
                $temp = db($v['checked_data'])->where([$v['key_name']=>$data[$v['key_name']]])->find();
                if(!empty($temp))
                {
                    return $v['error_msg'];
                }
                break;
        }
    }
    return true;
}
?>