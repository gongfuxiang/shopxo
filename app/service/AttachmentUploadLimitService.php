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

use app\service\AdminService;

/**
 * 附件上传登录与数量限制
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-10-20
 * @desc    description
 */
class AttachmentUploadLimitService
{
    // 不强制登录时仍允许的动作
    private static $open_action = ['config', 'uploadimage', 'uploadscrawl', 'uploadvideo', 'uploadfile'];

    // 时长单位对应秒数
    private static $unit_seconds = [
        'minute' => 60,
        'hour'   => 3600,
        'day'    => 86400,
    ];

    /**
     * 上传是否必须登录
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     */
    public static function LoginRequired()
    {
        return intval(MyC('common_attachment_upload_is_login', 0)) === 1;
    }

    /**
     * 不强制登录时可以开放的动作
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $action [动作]
     */
    public static function IsOpenAction($action)
    {
        return is_string($action) && in_array($action, self::$open_action, true);
    }

    /**
     * 数量限制使用的类型
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    涂鸦计入图片
     * @param   [string]          $attachment_type [附件类型]
     */
    public static function LimitType($attachment_type)
    {
        if($attachment_type === 'image' || $attachment_type === 'scrawl')
        {
            return 'image';
        }
        if($attachment_type === 'video')
        {
            return 'video';
        }
        return 'file';
    }

    /**
     * 即将上传的大小
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $field_name [表单字段]
     */
    public static function ComingSize($field_name)
    {
        if($field_name !== '' && !empty($_FILES[$field_name]['size']))
        {
            return intval($_FILES[$field_name]['size']);
        }
        if($field_name !== '' && !empty($_POST[$field_name]) && is_string($_POST[$field_name]))
        {
            return intval(strlen($_POST[$field_name]) * 3 / 4);
        }
        return 0;
    }

    /**
     * 是否超过数量限制
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $type [image|file|video]
     * @param   [int]             $size [本次字节]
     */
    public static function Check($type, $size = 0)
    {
        if(self::IsSkip())
        {
            return DataReturn('success', 0);
        }
        $size = max(0, intval($size));
        foreach(self::Dimensions() as $dim)
        {
            $identity = self::Identity($dim);
            foreach(self::Rules($type) as $rule)
            {
                $current = self::Counter($type, $dim, $identity, $rule['window']);
                if($rule['count'] > 0 && intval($current['count']) >= $rule['count'])
                {
                    return DataReturn(MyLang('attachment_upload_limit_exceed_tips'), -1);
                }
                if($rule['size'] > 0 && intval($current['size']) + $size > $rule['size'])
                {
                    return DataReturn(MyLang('attachment_upload_limit_exceed_tips'), -1);
                }
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 上传成功后计入数量
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $type [image|file|video]
     * @param   [int]             $size [本次字节]
     */
    public static function Record($type, $size = 0)
    {
        if(self::IsSkip())
        {
            return;
        }
        $size = max(0, intval($size));
        foreach(self::Dimensions() as $dim)
        {
            $identity = self::Identity($dim);
            foreach(self::Rules($type) as $rule)
            {
                $key = self::CacheKey($type, $dim, $identity, $rule['window']);
                $current = self::Counter($type, $dim, $identity, $rule['window']);
                $current['count'] = intval($current['count']) + 1;
                $current['size'] = intval($current['size']) + $size;
                cache($key, $current, $rule['window']);
            }
        }
    }

    /**
     * 后台管理员不计入，未勾选依据或该类型没有层级也不限制
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     */
    private static function IsSkip()
    {
        if(self::IsAdmin())
        {
            return true;
        }
        return empty(self::Dimensions());
    }

    /**
     * 当前是否后台管理员上传
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    只跳过后台入口。前台和用户中心即使浏览器还登录着后台，也按普通上传计数
     */
    private static function IsAdmin()
    {
        $module = RequestModule();
        if($module === 'admin')
        {
            $admin = MySession(AdminService::$admin_login_key);
            return !empty($admin);
        }
        if($module !== 'api')
        {
            return false;
        }
        $token = input('token');
        if(!is_string($token) || $token === '')
        {
            return false;
        }
        return !empty(AdminService::AdminTokenData($token));
    }

    /**
     * 限制依据
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     */
    private static function Dimensions()
    {
        $dims = self::ConfigArray('common_attachment_upload_limit_type');
        return array_values(array_intersect(['ip', 'uuid', 'client'], $dims));
    }

    /**
     * 某一类的层级
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $type [image|file|video]
     */
    private static function Rules($type)
    {
        $all = self::ConfigArray('common_attachment_upload_limit');
        if(empty($all[$type]) || !is_array($all[$type]))
        {
            return [];
        }
        $rules = [];
        foreach($all[$type] as $row)
        {
            if(!is_array($row))
            {
                continue;
            }
            $unit = isset($row['unit']) ? $row['unit'] : '';
            $time = isset($row['time']) ? intval($row['time']) : 0;
            $count = isset($row['count']) ? intval($row['count']) : 0;
            $size = isset($row['size']) ? intval($row['size']) : 0;
            if($time <= 0 || !isset(self::$unit_seconds[$unit]) || ($count <= 0 && $size <= 0))
            {
                continue;
            }
            $rules[] = [
                'window' => $time * self::$unit_seconds[$unit],
                'count'  => $count,
                'size'   => $size,
            ];
        }
        return $rules;
    }

    /**
     * 读取配置数组
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $key [配置标记]
     */
    private static function ConfigArray($key)
    {
        $value = MyC($key, []);
        if(is_array($value))
        {
            return $value;
        }
        if(is_string($value) && $value !== '')
        {
            $decoded = json_decode(html_entity_decode($value), true);
            if(is_array($decoded))
            {
                return $decoded;
            }
        }
        return [];
    }

    /**
     * 计数身份
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $dim [ip|client]
     */
    private static function Identity($dim)
    {
        if($dim === 'ip')
        {
            $ip = GetClientIP(false, true);
            return (is_string($ip) && $ip !== '') ? $ip : 'none';
        }
        if($dim === 'uuid')
        {
            $uuid = MySession('uuid');
            if(!is_string($uuid) || $uuid === '')
            {
                $uuid = MyCookie('uuid', '', false);
            }
            return (is_string($uuid) && $uuid !== '') ? $uuid : 'none';
        }
        $client = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
        return (is_string($client) && $client !== '') ? $client : 'none';
    }

    /**
     * 当前窗口计数
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $type     [类型]
     * @param   [string]          $dim      [依据]
     * @param   [string]          $identity [身份]
     * @param   [int]             $window   [窗口秒数]
     */
    private static function Counter($type, $dim, $identity, $window)
    {
        $current = cache(self::CacheKey($type, $dim, $identity, $window));
        if(!is_array($current))
        {
            return ['count' => 0, 'size' => 0];
        }
        return [
            'count' => isset($current['count']) ? intval($current['count']) : 0,
            'size'  => isset($current['size']) ? intval($current['size']) : 0,
        ];
    }

    /**
     * 计数缓存键
     * @author  Devil
     * @version 1.0.0
     * @date    2026-10-20
     * @desc    description
     * @param   [string]          $type     [类型]
     * @param   [string]          $dim      [依据]
     * @param   [string]          $identity [身份]
     * @param   [int]             $window   [窗口秒数]
     */
    private static function CacheKey($type, $dim, $identity, $window)
    {
        $bucket = intval(floor(time() / $window));
        return 'shopxo_attachment_upload_limit_'.$type.'_'.$dim.'_'.md5($identity).'_'.$window.'_'.$bucket;
    }
}
?>
