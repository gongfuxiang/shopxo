<?php

/**
 * 公共方法
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */


/**
 * 生成前台页面url地址
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-12
 * @desc    description
 * @param   string          $c      [控制器名称]
 * @param   string          $a      [方法名称]
 * @param   array           $params [参数]
 */
/**
 * 生成前台页面url地址
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-12
 * @desc    description
 * @param   string          $c      [控制器名称]
 * @param   string          $a      [方法名称]
 * @param   array           $params [参数]
 * @param   string          $suffix [后缀名]
 * @param   boolean         $is_url [是否显示域名]
 */
function HomeUrl($c='Index', $a='Index', $params=[], $suffix='', $is_url=false)
{
    return str_replace('admin.php', 'index.php', U("Home/{$c}/{$a}", $params, $suffix, $is_url));
}

/**
 * [UserIntegralLogAdd 用户积分日志添加]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-05-18T16:51:12+0800
 * @param    [int]                   $user_id           [用户id]
 * @param    [int]                   $original_integral [原始积分]
 * @param    [int]                   $new_integral      [最新积分]
 * @param    [string]                $msg               [操作原因]
 * @param    [int]                   $type              [操作类型（0减少, 1增加）]
 * @param    [int]                   $operation_id      [操作人员id]
 * @return   [boolean]                                  [成功true, 失败false]
 */
function UserIntegralLogAdd($user_id, $original_integral, $new_integral, $msg = '', $type = 0, $operation_id = 0)
{
    $data = array(
        'user_id'           => intval($user_id),
        'original_integral' => intval($original_integral),
        'new_integral'      => intval($new_integral),
        'msg'               => $msg,
        'type'              => intval($type),
        'operation_id'      => intval($operation_id),
        'add_time'          => time(),
    );
    if(M('UserIntegralLog')->add($data) > 0)
    {
        $type_msg = L('common_integral_log_type_list')[$type]['name'];
        $integral = ($data['type'] == 0) ? $data['original_integral']-$data['new_integral'] : $data['new_integral']-$data['original_integral'];
        $detail = $msg.'积分'.$type_msg.$integral;
        CommonMessageAdd('积分变动', $detail, $user_id);
        return true;
    }
    return false;
}

/**
 * [CommonMessageAdd 消息添加]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-04-14T13:03:35+0800
 * @param    [string]           $title  [标题]
 * @param    [string]           $detail [内容]
 * @param    [int]              $user_id[用户id]
 * @param    [int]              $type   [类型（默认0  普通消息）]
 * @return   [boolean]                  [成功true, 失败false]
 */
function CommonMessageAdd($title = '', $detail = '', $user_id = 0, $type = 0)
{
    $data = array(
        'user_id'   => intval($user_id),
        'title'     => $title,
        'detail'    => $detail,
        'type'      => intval($type),
        'add_time'  => time(),
    );
    return (M('Message')->add($data) > 0);
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
    if(strpos ($price, '.') !== false)
    {
        if(substr($price, -1) == 0)
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
            return L('common_select_file_tips');
        }
    } else {
        if(empty($_FILES[$name]['name'][$index]))
        {
            return L('common_select_file_tips');
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
    $file_error_list = L('common_file_upload_error_list');
    if(isset($file_error_list[$error]))
    {
        return $file_error_list[$error];
    }
    return L('common_unknown_error').'[file error '.$error.']';
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
 * [GenerateStudentNumber 学生编号生成-年份+自增id(不足以0前置补齐)]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T12:13:06+0800
 * @param    [int]    $student_id [学生自增id]
 * @return   [string]             [学生编号]
 */
function GenerateStudentNumber($student_id)
{
    $number = date('Y');
    for($i=0; $i<8-strlen($student_id); $i++)
    {
        $number .= '0';
    }
    return $number.$student_id;
}

/**
 * [MyConfigInit 系统配置信息初始化]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-03T21:36:55+0800
 * @param    [int] $state [是否更新数据,0否,1是]
 */
function MyConfigInit($state = 0)
{
    $key = C('cache_common_my_config_key');
    $data = S($key);
    if($state == 1 || empty($data))
    {
        // 所有配置
        $data = M('Config')->getField('only_tag,value');

        // 数据处理
        // 开启用户注册列表
        if(isset($data['home_user_reg_state']))
        {
            $data['home_user_reg_state'] = explode(',', $data['home_user_reg_state']);
        }

        S($key, $data);

        // 时区
        if(isset($data['common_timezone']))
        {
            S('cache_common_timezone_data', $data['common_timezone']);
        }

        // 默认模板
        if(isset($data['common_default_theme']))
        {
            S('cache_common_default_theme_data', $data['common_default_theme']);
        }
    }
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
 * [NavDataDealWith 导航数据处理]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-02-05T21:36:46+0800
 * @param    [array]      $data [需要处理的数据]
 * @return   [array]            [处理好的数据]
 */
function NavDataDealWith($data)
{
    if(!empty($data) && is_array($data))
    {
        foreach($data as $k=>$v)
        {
            // url处理
            switch($v['data_type'])
            {
                // 文章分类
                case 'article_class':
                    $v['url'] = str_replace('admin.php', 'index.php', U('Home/Channel/Index', array('id'=>$v['value'], 'viewid'=>$v['id'])));
                    break;

                // 自定义页面
                case 'customview':
                    $v['url'] = str_replace('admin.php', 'index.php', U('Home/CustomView/Index', array('id'=>$v['value'], 'viewid'=>$v['id'])));
                    break;
            }
            $data[$k] = $v;
        }
    }
    return $data;
}

/**
 * [ContentStaticReplace 编辑器中内容的静态资源替换]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-22T16:07:58+0800
 * @param    [string]    $content [在这个字符串中查找进行替换]
 * @param    [string]    $type    [操作类型[get读取额你让, add写入内容](编辑/展示传入get,数据写入数据库传入add)]
 * @return   [string]             [正确返回替换后的内容, 则返回原内容]
 */
function ContentStaticReplace($content, $type = 'get')
{
    switch($type)
    {
        // 读取内容
        case 'get':
            return str_replace('/Public/', __MY_URL__.'Public/', $content);
            break;

        // 内容写入
        case 'add':
            return str_replace(array(__MY_URL__.'Public/', __MY_ROOT__.'Public/'), '/Public/', $content);
    }
    return $content;
}

/**
 * [DelDirFile 删除指定目录下的所有文件]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-11T18:30:37+0800
 * @param    [string]     $dir_name   [目录地址]
 * @param    [boolean]    $is_del_dir [是否删除目录（默认false）]
 * @return   [boolean]                [成功true, 失败false]
 */
function DelDirFile($dir_name, $is_del_dir = false)  
{
    $error = 0;
    if($handle = opendir($dir_name))
    {
        while(false !== ($item = readdir($handle)))
        {
            if($item != '.' && $item != '..' )
            {
                if(is_dir("{$dir_name}/{$item}"))
                {
                    DelDirFile("$dir_name/$item", $is_del_dir);  
                } else {
                    if(!is_writable("$dir_name/$item") || !unlink("$dir_name/$item"))
                    {
                        $error++;
                    }
                }
            }
        }
        
        // 关闭目录句柄
        closedir($handle);

        // 是否删除目录
        if($is_del_dir == true && !rmdir($dir_name))
        {
            $error++;
        }
    }
    return ($error == 0);
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
            $url_model= C('URL_MODEL');
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
    $data = S(C('cache_common_my_config_key'));
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
 * [Is_Json 校验json数据是否合法]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $jsonstr [需要校验的json字符串]
 * @return   [boolean] [合法true, 则false]
 */
function Is_Json($jsonstr)
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
 * [Curl_Post curl模拟post]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $url  [请求地址]
 * @param    [array]  $post [发送的post数据]
 */
function Curl_Post($url, $post)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * [Fsockopen_Post fsockopen方式]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [string] $url  [url地址]
 * @param    [string] $data [发送参数]
 */
function Fsockopen_Post($url, $data = '')
{
    $row = parse_url($url);
    $host = $row['host'];
    $port = isset($row['port']) ? $row['port'] : 80;
    $file = $row['path'];
    $post = '';
    while (list($k,$v) = each($data)) 
    {
        if(isset($k) && isset($v)) $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
    }
    $post = substr( $post , 0 , -1 );
    $len = strlen($post);
    $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
    if (!$fp) {
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
 * [Xml_Array xml转数组]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param    [xml] $xmlstring [xml数据]
 * @return   [array]          [array数组]
 */
function Xml_Array($xmlstring) {
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
    return (preg_match('/'.L('common_regex_mobile').'/', $mobile) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_tel').'/', $tel) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_email').'/', $email) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_id_card').'/', $number) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_price').'/', $price) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_ip').'/', $ip) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_url').'/', $url) == 1) ? true : false;
}

/**
 * [CheckUserName 用户名校验]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param 	 [string] $string [用户名]
 * @return 	 [boolean]        [成功true, 失败false]
 */
function CheckUserName($string)
{
    return (preg_match('/'.L('common_regex_username').'/', $string) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_color').'/', $value) == 1) ? true : false;
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
    return (preg_match('/'.L('common_regex_pwd').'/', $string) == 1) ? true : false;
    // $len = strlen($string);
    // return ($len >= 6 && $len <= 18);
}

/**
 * [Sms_Code_Send 验证码通道]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param  	 [staing] $content      [内容]
 * @param  	 [string] $mobile_phone [手机号码]
 * @return 	 [boolean]              [成功true, 失败false]
 */
function Sms_Code_Send($content, $mobile_phone)
{
    $post = array(
      'apikey'  =>  '17171d4ff3510ae8f532a70401e41067',
      'text'    =>  '【美啦网】'.$content,
      'mobile'  =>  $mobile_phone,
    );
    $result = json_decode(Fsockopen_Post('http://yunpian.com/v1/sms/send.json', $post), true);
    if(empty($result)) return false;
    return ($result['msg'] == 'OK');
}

/**
 * [Sms_Notice_Send 通知短信通道]
 * @param  [staing] $content      [内容]
 * @param  [string] $mobile_phone [手机号码]
 * @return [boolean]              [成功true, 失败false]
 */
function Sms_Notice_Send($content, $mobile_phone)
{
    $post = array(
      'action'  =>  'sendOnce',
      'ac'      =>  '1001@501186640001',
      'authkey' =>  'C511BEF448D2D063972EEC015C3E95C6',
      'cgid'    =>  '4534',
      'csid'    =>  '4717',
      'c'       =>  $content,
      'm'       =>  $mobile_phone,
    );
    $return = Xml_Array(Fsockopen_Post('http://smsapi.c123.cn/OpenPlatform/OpenApi', $post));
    if(!isset($return['@attributes']['result']) || $return['@attributes']['result'] != 1) return false;
    return true;
}

/**
 * [IsExistWebImg 检测一张网络图片是否存在]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-03T21:58:54+0800
 * @param  	 [string] $url [图片地址]
 * @return 	 [boolean]     [存在true, 则false]
 */
function IsExistWebImg($url)
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
 * @param  	 [int] $length [生成位数]
 * @return 	 [int]         [生成的随机数]
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
 * @param  	 [string] $pwd  [需要加密的密码]
 * @param  	 [string] $salt [配合密码加密的随机数]
 * @return 	 [string]       [加密后的密码]
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
 * @param  	 [string] $pwd  [需要加密的密码]
 * @param  	 [string] $salt [配合密码加密的随机数]
 * @return 	 [string]       [加密后的密码]
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
 * @param  	 [string] $pwd [需要校验的密码]
 * @return 	 [int]         [密码强度值0~10]
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
 * @param  	 [string]  $string    [明文或密文]
 * @param  	 [string]  $operation [加密ENCODE, 解密DECODE]
 * @param  	 [string]  $key       [密钥]
 * @param  	 [integer] $expiry    [密钥有效期]
 * @return 	 [string]             [加密或解密后的数据]
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
 * [SS 设置缓存]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-09-24T19:01:00+0800
 * @param    [string]              $key  [缓存key]
 * @param    [mixed]               $data [需要存储的数据]
 * @return   [boolean]                   [成功true, 失败false]
 */
function SS($key, $data)
{
    if(empty($key) || empty($data))
    {
        return false;
    }
    $dir = C('data_cache_dir');
    if(!is_dir($dir))
    {
        mkdir($dir, 0777, true);
    }
    return (file_put_contents($dir.md5($key).'.txt', serialize($data)) !== false);
}

/**
 * [GS 获取缓存]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-09-24T18:54:54+0800
 * @param    [string]          $key             [缓存key]
 * @param    [integer]         $expires_time    [默认过期时间0长期有效（单位秒）]
 * @param    [boolean]         $is_filem_time   [是否返回文件上一次更新时间]
 * @return   [boolean|mixed]                    [没数据false, 则数据]
 */
function GS($key, $expires_time = 0, $is_filem_time = false)
{
    if(empty($key))
    {
        return false;
    }
    $file_name = C('data_cache_dir').md5($key).'.txt';
    if(file_exists($file_name))
    {
        /* 文件上次修改时间 */
        $filem_time = filemtime($file_name);

        /* 如果过期时间大于0则判断数据是否过期 */
        $expires_time = intval($expires_time);
        if($expires_time > 0)
        {
            if($filem_time+$expires_time < time())
            {
                return false;
            }
        }
        
        $data = unserialize(file_get_contents($file_name));
        if(empty($data))
        {
            return false;
        } else {
            if($is_filem_time == true)
            {
                $data['filemtime'] = $filem_time;
            }
            return $data;
        }
    }
    return false;
}

/**
 * [DS 删除缓存]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-09-24T19:01:00+0800
 * @param    [string]              $key  [缓存key]
 * @return   [boolean]                   [成功true, 失败false]
 */
function DS($key)
{
    if(!empty($key))
    {
        $file_name = C('data_cache_dir').md5($key).'.txt';
        if(file_exists($file_name))
        {
            return unlink($file_name);
        }
    }
    return false;
}

/**
 * [params_checked 参数校验方法]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2017-12-12T15:26:13+0800
 * @param    [array]                   $data   [原始数据]
 * @param    [array]                   $params [校验数据]
 * @return   [boolean|string]                  [成功true, 失败 错误信息]
 */
function params_checked($data, $params)
{
    if (empty($params) || !is_array($data) || !is_array($params)) {
        return '内部调用参数配置有误';
    }

    foreach ($params as $v) {
        if (empty($v['key_name']) || empty($v['error_msg'])) {
            return '内部调用参数配置有误';
        }

        $checked_type = isset($v['checked_type']) ? $v['checked_type'] : 'isset';
        switch ($checked_type) {
            case 'isset' :
                if (!isset($data[$v['key_name']])) {
                    return $v['error_msg'];
                }
                break;

            case 'empty' :
                if (empty($data[$v['key_name']])) {
                    return $v['error_msg'];
                }
                break;

            case 'in' :
                if (empty($v['checked_data']) || !is_array($v['checked_data'])) {
                    return '内部调用参数配置有误';
                }
                if (!isset($data[$v['key_name']]) || !in_array($data[$v['key_name']], $v['checked_data'])) {
                    return $v['error_msg'];
                }
        }
    }
    return true;
}

/**
 * [UserServiceExpire 用户服务有效]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-05-17T17:18:29+0800
 * @param    [int]     $service_expire_time [description]
 * @return   [boolean|int]                  [有效则返回时间, 无效则false]
 */
function UserServiceExpire($service_expire_time)
{
    if(empty($service_expire_time))
    {
        return false;
    }

    $service_time = strtotime(date('Y-m-d', $service_expire_time));
    $day_time = strtotime(date('Y-m-d'));
    if($service_time >= $day_time)
    {
        return date('Y-m-d', $service_time);
    }
    return false;
}

/**
 * [GetRegionName 获取地区名称]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-05-17T18:03:39+0800
 * @param    [int]         $region_id [地区id]
 * @return   [string]                 [地区名称]
 */
function GetRegionName($region_id)
{
    return M('Region')->where(['id'=>$region_id])->getField('name');
}

/**
 * [GetExpressName 获取快递名称]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-05-17T18:03:39+0800
 * @param    [int]         $express_id [快递id]
 * @return   [string]                  [快递名称]
 */
function GetExpressName($express_id)
{
    return M('Express')->where(['id'=>$express_id])->getField('name');
}

/**
 * [GetGoodsName 获取物品类型名称]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-05-17T18:03:39+0800
 * @param    [int]         $goods_id    [快递id]
 * @return   [string]                   [快递名称]
 */
function GetGoodsName($goods_id)
{
    return M('Goods')->where(['id'=>$goods_id])->getField('name');
}

/**
 * [GetMerchantName 获取站点名称]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2018-05-17T18:03:39+0800
 * @param    [int]         $merchant_id [站点id]
 * @return   [string]                   [站点名称]
 */
function GetMerchantName($merchant_id)
{
    return M('Merchant')->where(['id'=>$merchant_id])->getField('name');
}

?>