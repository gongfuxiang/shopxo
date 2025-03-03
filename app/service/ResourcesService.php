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

use think\facade\Db;
use app\service\UserService;
use app\service\SystemBaseService;
use app\service\AttachmentCategoryService;

/**
 * 资源服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ResourcesService
{
    /**
     * 编辑器中内容的静态资源替换
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-22T16:07:58+0800
     * @param    [string]    $content [在这个字符串中查找进行替换]
     * @param    [string]    $type    [操作类型[get读取额你让, add写入内容](编辑/展示传入get,数据写入数据库传入add)]
     * @return   [string]             [正确返回替换后的内容, 则返回原内容]
     */
    public static function ContentStaticReplace($content, $type = 'get')
    {
        // 仅处理字符串和数字类型
        if(is_string($content) || is_int($content))
        {
            // 配置文件附件url地址
            $attachment_host = SystemBaseService::AttachmentHost();
            if(empty($attachment_host))
            {
                $attachment_host = substr(__MY_PUBLIC_URL__, 0, -1);
            }
            $attachment_host_path = $attachment_host.'/static/';

            // 根据类型处理附件地址
            switch($type)
            {
                // 读取内容
                case 'get':
                    return str_replace('src="/static/', 'src="'.$attachment_host_path, $content);
                    break;

                // 内容写入
                case 'add':
                    $search = [
                        'src="'.__MY_PUBLIC_URL__.'static/',
                        'src="'.__MY_ROOT_PUBLIC__.'static/',
                        'src="'.$attachment_host_path,
                    ];
                    return str_replace($search, 'src="/static/', $content);
            }
        }
        return $content;
    }

    /**
     * 附件路径处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-12
     * @desc    description
     * @param   [string|array]    $value [附件路径地址]
     * @param   [string]          $field [url字段名称]
     */
    public static function AttachmentPathHandle($value, $field = 'url')
    {
        if(!empty($value))
        {
            // 配置文件附件url地址
            $attachment_host = SystemBaseService::AttachmentHost();
            $attachment_host_path = empty($attachment_host) ? __MY_PUBLIC_URL__ : $attachment_host.DS;

            // 替换处理
            $search = [
                $attachment_host_path,
                __MY_PUBLIC_URL__,
                __MY_ROOT_PUBLIC__,
            ];

            // 是否数组
            if(is_array($value))
            {
                foreach($value as &$v)
                {
                    // 是否二级
                    if(isset($v[$field]))
                    {
                        $v[$field] = empty($v[$field]) ? '' : str_replace($search, DS, $v[$field]);
                    } else {
                        $v = empty($v) ? '' : str_replace($search, DS, $v);
                    }
                }
            } else {
                $value = empty($value) ? '' : str_replace($search, DS, $value);
            }
        }
        return $value;
    }

    /**
     * 附件集合处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @param   [array]          $data   [字段列表]
     */
    public static function AttachmentParams($params, $data)
    {
        $result = [];
        if(!empty($data))
        {
            foreach($data as $field)
            {
                $result[$field] = isset($params[$field]) ? self::AttachmentPathHandle($params[$field]) : '';
            }
        }

        return DataReturn('success', 0, $result);
    }

    /**
     * 附件展示地址处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-01-13T15:13:30+0800
     * @param    [string|array]   $value [附件地址]
     * @param    [string]         $field [url字段名称]
     */
    public static function AttachmentPathViewHandle($value, $field = 'url')
    {
        if(!empty($value))
        {
            // 是否数组
            if(is_array($value))
            {
                $host = SystemBaseService::AttachmentHost();
                foreach($value as &$v)
                {
                    // 是否二级
                    if(isset($v[$field]))
                    {
                        if(substr($v[$field], 0, 4) != 'http')
                        {
                            $v[$field] = $host.$v[$field];
                        }
                    } else {
                        if(substr($v, 0, 4) != 'http')
                        {
                            $v = $host.$v;
                        }
                    }
                }
            } else {
                if(substr($value, 0, 4) != 'http')
                {
                    $value = SystemBaseService::AttachmentHost().$value;
                }
            }
        }
        return $value;
    }

    /**
     * 小程序富文本标签处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-04
     * @desc    description
     * @param   [string]          $content [需要处理的富文本内容]
     */
    public static function ApMiniRichTextContentHandle($content)
    {
        // 标签处理，兼容小程序rich-text
        $search = [
            '<img ',
            '<section',
            '/section>',
            '<p style="',
            '<p>',
            '<div>',
            '<table',
            '<tr',
            '<td',
        ];
        $replace = [
            '<img style="max-width:100%;height:auto;margin:0;padding:0;display:block;" ',
            '<div',
            '/div>',
            '<p style="margin:0;',
            '<p style="margin:0;">',
            '<div style="margin:0;">',
            '<table style="width:100%;margin:0px;border-collapse:collapse;border-color:#ddd;border-style:solid;border-width:0 1px 1px 0;"',
            '<tr style="border-top:1px solid #ddd;"',
            '<td style="margin:0;padding:5px;border-left:1px solid #ddd;"',
        ];
        return str_replace($search, $replace, $content);
    }

    /**
     * 正则匹配富文本附件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-16
     * @desc    description
     * @param   [string]      $content  [内容]
     * @param   [string]      $business [业务模块名称]
     * @param   [string]      $type     [附件类型（images 图片, file 文件, video 视频）]
     */
    public static function RichTextMatchContentAttachment($content, $business = '', $type = '')
    {
        if(!empty($content))
        {
            $pattern = (!empty($type) && in_array($type, ['images', 'video'])) ? '/<'.($type == 'images' ? 'img' : $type) : '/<';
            $pattern .= '.*?src=[\'|\"](\/static\/upload';
            if(!empty($type))
            {
                $pattern .= '\/'.$type;
            }
            if(!empty($business))
            {
                $pattern .= '\/'.$business;
            }
            $file_suffix = str_replace('.', '\.', implode('|', MyConfig('ueditor.fileAllowFiles')));
            $pattern .= '\/.*?['.$file_suffix.'])[\'|\"].*?[\/]?>/';
            preg_match_all($pattern, self::AttachmentPathHandle($content), $match);
            return empty($match[1]) ? [] : $match[1];
        }
        return [];
    }

    /**
     * 文件类型匹配
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [string]          $file [文件地址]
     */
    public static function AttachmentTypeMatch($file)
    {
        // 截取后缀
        $ext = strtolower(strrchr($file, '.'));
        // 图片
        if(in_array($ext, MyConfig('ueditor.imageAllowFiles')))
        {
            return 'images';
        }
        // 视频
        if(in_array($ext, MyConfig('ueditor.videoAllowFiles')))
        {
            return 'video';
        }
        // 默认文件
        return 'file';
    }

    /**
     * 购买填写时间数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BuyDatetimeData($params = [])
    {
        // 默认配置
        $config = MyC('common_buy_datetime_info', [], true);
        $data = [
            'is_select'     => in_array(0, $config) ? 1 : 0,
            'required'      => in_array(1, $config) ? 1 : 0,
            'title'         => MyLang('appoint_time_title'),
            'placeholder'   => MyLang('choice_time_title'),
            'error_msg'     => MyLang('form_time_message'),
            'time_start'    => '',
            'time_end'      => '',
            'range_type'    => 1,
            'range_day'     => 7,
            'disabled'      => [],
        ];

        // 钩子
        $hook_name = 'plugins_service_buy_datetime_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 购买填写客户信息数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BuyExtractionContactData($params = [])
    {
        // 默认配置
        $config = MyC('common_buy_extraction_contact_info', [], true);
        $data = [
            'is_write'   => in_array(0, $config) ? 1 : 0,
            'required'   => in_array(1, $config) ? 1 : 0,
            'name'       => (empty($params['user']) || empty($params['user']['nickname'])) ? '' : $params['user']['nickname'],
            'tel'        => (empty($params['user']) || empty($params['user']['mobile'])) ? '' : $params['user']['mobile'],
            'error_msg'  => MyLang('form_name_tel_message'),
        ];

        // 钩子
        $hook_name = 'plugins_service_buy_extraction_contact_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 购买站点类型切换数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     * @param   [int]           $common_site_type   [站点类型]
     * @param   [int]           $site_model         [指定站点类型]
     * @param   [array]         $buy_goods          [购买的商品]
     * @param   [array]         $params             [输入参数]
     */
    public static function BuySiteModelData($common_site_type, $site_model, $buy_goods = [], $params = [])
    {
        // 默认配置
        $data = [];

        // 多选切换订单类型
        // 0 快递
        // 1 同城
        // 2 自提
        // 3 虚拟
        // 4 展示
        // -----
        // 5 快递+自提
        // 6 同城+自提
        // 7 快递+同城
        // 8 快递+同城+自提
        if($common_site_type >= 5)
        {
            $common_site_type_list = MyConst('common_site_type_list');
            foreach($common_site_type_list as $v)
            {
                switch($common_site_type)
                {
                    // 快递+自提
                    case 5 :
                        if(in_array($v['value'], [0,2]))
                        {
                            $data[] = $v;
                        }
                        break;

                    // 同城+自提
                    case 6 :
                        if(in_array($v['value'], [1,2]))
                        {
                            $data[] = $v;
                        }
                        break;

                    // 快递+同城
                    case 7 :
                        if(in_array($v['value'], [0,1]))
                        {
                            $data[] = $v;
                        }
                        break;

                    // 快递+同城+自提
                    case 8 :
                        if(in_array($v['value'], [0,1,2]))
                        {
                            $data[] = $v;
                        }
                        break;
                }
            }
        }

        // 钩子
        $hook_name = 'plugins_service_buy_site_model_data';
        MyEventTrigger($hook_name, [
            'hook_name'         => $hook_name,
            'is_backend'        => true,
            'params'            => $params,
            'buy_goods'         => $buy_goods,
            'common_site_type'  => $common_site_type,
            'site_model'        => &$site_model,
            'data'              => &$data,
        ]);

        // 指定类型不在当前范围则使用第一个赋值
        if(!empty($data))
        {
            $site_model_arr = array_column($data, 'value');
            if(!empty($site_model_arr) && ($site_model == -1 || !in_array($site_model, $site_model_arr)))
            {
                $site_model = $site_model_arr[0];
            }
        }

        return [
            'data'        => $data,
            'site_model'  => $site_model,
        ];
    }

    /**
     * 货币信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CurrencyData($params = [])
    {
        // 默认从配置文件读取货币信息
        $data = [
            'currency_symbol'   => MyConfig('shopxo.currency_symbol'),
            'currency_code'     => MyConfig('shopxo.currency_code'),
            'currency_rate'     => MyConfig('shopxo.currency_rate'),
            'currency_name'     => MyConfig('shopxo.currency_name'),
        ];

        // 钩子
        $hook_name = 'plugins_service_currency_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 货币信息-符号
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CurrencyDataSymbol($params = [])
    {
        $res = self::CurrencyData($params);
        return empty($res['currency_symbol']) ? MyConfig('shopxo.currency_symbol') : $res['currency_symbol'];
    }

    /**
     * 编辑器文件存放地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-27
     * @desc    description
     * @param   [string]          $value [位置路径名称（[ - ]作为目录分隔符）]
     */
    public static function EditorPathTypeValue($value)
    {
        // 当前操作名称, 兼容插件模块名称
        $module_name = RequestModule();
        $controller_name = RequestController();
        $action_name = RequestAction();

        // 钩子
        $hook_name = 'plugins_service_editor_path_type_'.$module_name.'_'.$controller_name.'_'.$action_name;
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'value'         => &$value,
        ]);

        return $value;
    }

    /**
     * zip压缩包扩展可用格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-02
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ZipExtTypeList($params = [])
    {
        return [
            'application/zip',
            'application/octet-stream',
            'application/x-zip-compressed',
        ];
    }

    /**
     * 获取用户唯一id
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-15
     * @desc    未登录取[uuid]前端传过来的uuid、已登录取[用户id]、都没有则返回空字符串
     */
    public static function UserUniqueId()
    {
        // 取参数uuid、默认空
        $uid = input('uuid', '');

        // 取当当前session
        if(empty($uid))
        {
            $uid = MySession('uuid');
        }
        // 取当当前cookie
        if(empty($uid))
        {
            $uid = MyCookie('uuid');
        }

        // 用户信息
        $user = UserService::LoginUserInfo();
        if(!empty($user) && !empty($user['id']))
        {
            $uid = $user['id'];
        }

        return empty($uid) ? '' : md5($uid);
    }

    /**
     * 获取表结构
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-11-12
     * @desc    description
     * @param   [string]          $table [表名称、可以是大写字母会自动转为小写前面加下划线分隔]
     */
    public static function TableStructureData($table)
    {
        // 表名处理及sql
        $table_name = MyConfig('database.connections.mysql.prefix').strtolower(preg_replace('/\B([A-Z])/', '_$1', $table));
        $sql = "SELECT COLUMN_NAME AS field,COLUMN_COMMENT AS name FROM INFORMATION_SCHEMA.Columns WHERE `table_name`='".$table_name."'";

        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_table_structure_key').'_'.md5($sql);
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug'))
        {
            // 查询表结构
            $res = Db::query($sql);
            $data = empty($res) ? [] : array_column($res, 'name', 'field');

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }

    /**
     * 页面静态资源地址信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-01-29
     * @desc    description
     * @param   [string]          $theme      [当前主题标识]
     * @param   [string]          $group      [当前模块]
     * @param   [string]          $controller [控制器]
     * @param   [string]          $action     [方法]
     */
    public static function StaticCssOrJsPathData($theme, $group, $controller, $action)
    {
        // 公共css,js
        $common_css = $group.DS.$theme.DS.'css'.DS.'common.css';
        if(!file_exists(ROOT_PATH.'static'.DS.$common_css))
        {
            $default_common_css = $group.DS.'default'.DS.'css'.DS.'common.css';
            if(file_exists(ROOT_PATH.'static'.DS.$default_common_css))
            {
                $common_css = $default_common_css;
            } else {
                $common_css = '';
            }
        }
        $common_js = $group.DS.$theme.DS.'js'.DS.'common.js';
        if(!file_exists(ROOT_PATH.'static'.DS.$common_js))
        {
            $default_common_js = $group.DS.'default'.DS.'js'.DS.'common.js';
            if(file_exists(ROOT_PATH.'static'.DS.$default_common_js))
            {
                $common_js = $default_common_js;
            } else {
                $common_js = '';
            }
        }

        // 主题指定引入css,js
        $theme_import_css = [];
        $theme_import_js = [];
        $config = APP_PATH.$group.DS.'view'.DS.$theme.DS.'config.json';
        if(file_exists($config))
        {
            $theme_config = json_decode(file_get_contents($config), true);
            if(!empty($theme_config['import_css']))
            {
                if(is_array($theme_config['import_css']))
                {
                    foreach($theme_config['import_css'] as $v)
                    {
                        if(!empty($v) && !is_array($v))
                        {
                            // 前面是否增加了斜杠、则去除
                            if(substr($v, 0, 1) == DS)
                            {
                                $v = substr($v, 1);
                            }
                            if(file_exists(ROOT_PATH.'static'.DS.$v))
                            {
                                $theme_import_css[] = $v;
                            }
                        }
                    }
                } else {
                    // 前面是否增加了斜杠、则去除
                    if(substr($theme_config['import_css'], 0, 1) == DS)
                    {
                        $theme_config['import_css'] = substr($theme_config['import_css'], 1);
                    }
                    if(file_exists(ROOT_PATH.'static'.DS.$theme_config['import_css']))
                    {
                        $theme_import_css[] = $theme_config['import_css'];
                    }
                }
            }
            if(!empty($theme_config['import_js']))
            {
                if(is_array($theme_config['import_js']))
                {
                    foreach($theme_config['import_js'] as $v)
                    {
                        if(!empty($v) && !is_array($v))
                        {
                            // 前面是否增加了斜杠、则去除
                            if(substr($v, 0, 1) == DS)
                            {
                                $v = substr($v, 1);
                            }
                            if(file_exists(ROOT_PATH.'static'.DS.$v))
                            {
                                $theme_import_js[] = $v;
                            }
                        }
                    }
                } else {
                    // 前面是否增加了斜杠、则去除
                    if(substr($theme_config['import_js'], 0, 1) == DS)
                    {
                        $theme_config['import_js'] = substr($theme_config['import_js'], 1);
                    }
                    if(file_exists(ROOT_PATH.'static'.DS.$theme_config['import_js']))
                    {
                        $theme_import_js[] = $theme_config['import_js'];
                    }
                }
            }
        }

        // 主题专属css,js
        $other_css = $group.DS.$theme.DS.'css'.DS.'other.css';
        if(!file_exists(ROOT_PATH.'static'.DS.$other_css))
        {
            $default_other_css = $group.DS.'default'.DS.'css'.DS.'other.css';
            if(file_exists(ROOT_PATH.'static'.DS.$default_other_css))
            {
                $other_css = $default_other_css;
            } else {
                $other_css = '';
            }
        }
        $other_js = $group.DS.$theme.DS.'js'.DS.'other.js';
        if(!file_exists(ROOT_PATH.'static'.DS.$other_js))
        {
            $default_other_js = $group.DS.'default'.DS.'js'.DS.'other.js';
            if(file_exists(ROOT_PATH.'static'.DS.$default_other_js))
            {
                $other_js = $default_other_js;
            } else {
                $other_js = '';
            }
        }

        // 公共模块css,js
        $module_css = $group.DS.$theme.DS.'css'.DS.'module.css';
        if(!file_exists(ROOT_PATH.'static'.DS.$module_css))
        {
            $default_module_css = $group.DS.'default'.DS.'css'.DS.'module.css';
            if(file_exists(ROOT_PATH.'static'.DS.$default_module_css))
            {
                $module_css = $default_module_css;
            } else {
                $module_css = '';
            }
        }
        $module_js = $group.DS.$theme.DS.'js'.DS.'module.js';
        if(!file_exists(ROOT_PATH.'static'.DS.$module_js))
        {
            $default_module_js = $group.DS.'default'.DS.'js'.DS.'module.js';
            if(file_exists(ROOT_PATH.'static'.DS.$default_module_js))
            {
                $module_js = $default_module_js;
            } else {
                $module_js = '';
            }
        }

        // 控制器静态文件状态css,js
        // 页面css
        $page_css = '';
        $css = $group.DS.$theme.DS.'css'.DS.$controller;
        // 对应方法不存在 或 非默认主题则走默认主题的文件
        if(file_exists(ROOT_PATH.'static'.DS.$css.'.'.$action.'.css') && $theme != 'default')
        {
            $page_css = $css.'.'.$action.'.css';
        } else {
            $default_css = $group.DS.'default'.DS.'css'.DS.$controller;
            if(file_exists(ROOT_PATH.'static'.DS.$default_css.'.'.$action.'.css'))
            {
                $page_css = $default_css.'.'.$action.'.css';
            }
        }
        if(empty($page_css))
        {
            $page_css = $css.'.css';
            if(!file_exists(ROOT_PATH.'static'.DS.$page_css))
            {
                // 不存在则赋空
                $page_css = '';

                // 非默认主题则走默认主题的文件
                if($theme != 'default')
                {
                    $default_css = $group.DS.'default'.DS.'css'.DS.$controller.'.css';
                    if(file_exists(ROOT_PATH.'static'.DS.$default_css))
                    {
                        $page_css = $default_css;
                    }
                }
            }
        }
        // 页面js
        $page_js = '';
        $js = $group.DS.$theme.DS.'js'.DS.$controller;
        // 对应方法不存在 或 非默认主题则走默认主题的文件
        if(file_exists(ROOT_PATH.'static'.DS.$js.'.'.$action.'.js') && $theme != 'default')
        {
            $page_js = $js.'.'.$action.'.js';
        } else {
            $default_js = $group.DS.'default'.DS.'js'.DS.$controller;
            if(file_exists(ROOT_PATH.'static'.DS.$default_js.'.'.$action.'.js'))
            {
                $page_js = $default_js.'.'.$action.'.js';
            }
        }
        if(empty($page_js))
        {
            $page_js = $js.'.js';
            if(!file_exists(ROOT_PATH.'static'.DS.$page_js))
            {
                // 不存在则赋空
                $page_js = '';

                // 非默认主题则走默认主题的文件
                if($theme != 'default')
                {
                    $default_js = $group.DS.'default'.DS.'js'.DS.$controller.'.js';
                    if(file_exists(ROOT_PATH.'static'.DS.$default_js))
                    {
                        $page_js = $default_js;
                    }
                }
            }
        }

        // 是否插件
        $plugins_css = '';
        $plugins_js = '';
        $control = RequestController();
        $plugins_name = PluginsRequestName();
        if($control == 'plugins' && !empty($plugins_name))
        {
            $plugins_control = PluginsRequestController();
            $plugins_action = PluginsRequestAction();

            // 新版本插件目录
            // 页面css
            $pcss = $control.DS.$plugins_name.DS.'css'.DS.$group.DS.$plugins_control;
            $pcss .= file_exists(ROOT_PATH.'static'.DS.$pcss.'.'.$plugins_action.'.css') ? '.'.$plugins_action.'.css' : '.css';
            $page_css = file_exists(ROOT_PATH.'static'.DS.$pcss) ? $pcss : '';
            if(empty($page_css))
            {
                // 页面css - 老版本
                $pcss = $control.DS.'css'.DS.$plugins_name.DS.$group.DS.$plugins_control;
                $pcss .= file_exists(ROOT_PATH.'static'.DS.$pcss.'.'.$plugins_action.'.css') ? '.'.$plugins_action.'.css' : '.css';
                $page_css = file_exists(ROOT_PATH.'static'.DS.$pcss) ? $pcss : '';
            }

            // 页面js
            $pjs = $control.DS.$plugins_name.DS.'js'.DS.$group.DS.$plugins_control;
            $pjs .= file_exists(ROOT_PATH.'static'.DS.$pjs.'.'.$plugins_action.'.js') ? '.'.$plugins_action.'.js' : '.js';
            $page_js = file_exists(ROOT_PATH.'static'.DS.$pjs) ? $pjs : '';
            if(empty($page_js))
            {
                // 页面js - 老版本
                $pjs = $control.DS.'js'.DS.$plugins_name.DS.$group.DS.$plugins_control;
                $pjs .= file_exists(ROOT_PATH.'static'.DS.$pjs.'.'.$plugins_action.'.js') ? '.'.$plugins_action.'.js' : '.js';
                $page_js = file_exists(ROOT_PATH.'static'.DS.$pjs) ? $pjs : '';
            }

            // 应用公共css
            $plugins_css = $control.DS.$plugins_name.DS.'css'.DS.$group.DS.'common.css';
            $plugins_css = file_exists(ROOT_PATH.'static'.DS.$plugins_css) ? $plugins_css : '';
            if(empty($plugins_css))
            {
                // 应用公共css - 老版本
                $plugins_css = $control.DS.'css'.DS.$plugins_name.DS.$group.DS.'common.css';
                $plugins_css = file_exists(ROOT_PATH.'static'.DS.$plugins_css) ? $plugins_css : '';
            }
            // 应用公共js
            $plugins_js = $control.DS.$plugins_name.DS.'js'.DS.$group.DS.'common.js';
            $plugins_js = file_exists(ROOT_PATH.'static'.DS.$plugins_js) ? $plugins_js : '';
            if(empty($plugins_js))
            {
                // 应用公共js - 老版本
                $plugins_js = $control.DS.'js'.DS.$plugins_name.DS.$group.DS.'common.js';
                $plugins_js = file_exists(ROOT_PATH.'static'.DS.$plugins_js) ? $plugins_js : '';
            }
        }

        return [
            // 公共
            'common_css'        => $common_css,
            'common_js'         => $common_js,
            // 主题指定
            'theme_import_css'  => $theme_import_css,
            'theme_import_js'   => $theme_import_js,
            // 主题专属
            'other_css'         => $other_css,
            'other_js'          => $other_js,
            // 公共模块
            'module_css'        => $module_css,
            'module_js'         => $module_js,
            // 当前页面
            'page_css'          => $page_css,
            'page_js'           => $page_js,
            // 插件
            'plugins_css'       => $plugins_css,
            'plugins_js'        => $plugins_js,
        ];
    }
}
?>