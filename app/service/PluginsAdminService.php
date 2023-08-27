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
use app\service\SystemService;
use app\service\PluginsService;
use app\service\ResourcesService;
use app\service\SqlConsoleService;
use app\service\AdminPowerService;
use app\service\AdminService;
use app\service\StoreService;

/**
 * 应用管理服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PluginsAdminService
{
    // 排除不能使用的名称
    public static $plugins_exclude_verification = ['view', 'shopxo', 'www'];

    // 排除的文件后缀
    public static $exclude_ext = ['php'];

    // 读取插件排序方式(自定义排序 -> 最早安装)
    public static $plugins_order_by = 'sort asc,id asc';

    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsList($params = [])
    {
        $data = [];
        $db_data = [];
        $dir_data = [];
        $temp_data = [];
        $plugins_dir = APP_PATH.'plugins'.DS;
        if(is_dir($plugins_dir))
        {
            if($dh = opendir($plugins_dir))
            {
                // 是否需要校验权限
                $power_plugins = [];
                $is_power = (isset($params['is_power']) && $params['is_power'] == true);
                if($is_power)
                {
                    // 获取当前登录账户的插件权限
                    $admin = AdminService::LoginInfo();
                    if(!empty($admin))
                    {
                        if($admin['id'] != 1)
                        {
                            // 超级管理员id等于1则不处理权限
                            $is_power = false;
                        } else {
                            $res = MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$admin['id']);
                            if(!empty($res))
                            {
                                $power_plugins = $res;
                            }
                        }
                    }
                }

                // 获取数据库已安装插件
                $temp_data = Db::name('Plugins')->order(self::$plugins_order_by)->column('*', 'plugins');

                // 获取目录所有插件
                while(($temp_file = readdir($dh)) !== false)
                {
                    if(!in_array($temp_file, ['.', '..', '/', 'view', 'index.html']) && substr($temp_file, 0, 1) != '.')
                    {
                        // 获取插件配置信息
                        $config = self::GetPluginsConfig($temp_file);
                        if(!empty($config) && !empty($config['base']))
                        {
                            // 获取数据库配置信息
                            $base = $config['base'];

                            // 是否需要判断权限
                            if($is_power == true)
                            {
                                if(empty($power_plugins) || !array_key_exists($base['plugins'], $power_plugins))
                                {
                                    continue;
                                }
                            }

                            // 数据组装
                            $db_config = array_key_exists($base['plugins'], $temp_data) ? $temp_data[$base['plugins']] : [];
                            $dir_data[$base['plugins']] = [
                                'id'                   => empty($db_config['id']) ? 0 : $db_config['id'],
                                'plugins'              => $base['plugins'],
                                'plugins_category_id'  => isset($db_config['plugins_category_id']) ? $db_config['plugins_category_id'] : 0,
                                'plugins_menu_control'  => isset($db_config['plugins_menu_control']) ? $db_config['plugins_menu_control'] : '',
                                'is_enable'            => isset($db_config['is_enable']) ? $db_config['is_enable'] : 0,
                                'is_install'           => empty($db_config) ? 0 : 1,
                                'logo_old'             => $base['logo'],
                                'logo'                 => ResourcesService::AttachmentPathViewHandle($base['logo']),
                                'is_home'              => isset($base['is_home']) ? $base['is_home'] : false,
                                'name'                 => isset($base['name']) ? $base['name'] : '',
                                'author'               => isset($base['author']) ? $base['author'] : '',
                                'author_url'           => isset($base['author_url']) ? $base['author_url'] : '',
                                'version'              => isset($base['version']) ? $base['version'] : '',
                                'desc'                 => isset($base['desc']) ? $base['desc'] : '',
                                'apply_version'        => isset($base['apply_version']) ? $base['apply_version'] : [],
                                'apply_terminal'      => isset($base['apply_terminal']) ? $base['apply_terminal'] : [],
                                'add_time_time'        => isset($db_config['add_time']) ? date('Y-m-d H:i:s', $db_config['add_time']) : '',
                                'add_time_date'        => isset($db_config['add_time']) ? date('Y-m-d', $db_config['add_time']) : '',
                            ];
                        }
                    }
                }
                closedir($dh);
            }
        }

        // 存在插件和数据库插件数据则处理排序合并
        if(!empty($dir_data) && !empty($temp_data))
        {
            foreach($temp_data as $v)
            {
                if(array_key_exists($v['plugins'], $dir_data))
                {
                    $db_data[] = $dir_data[$v['plugins']];
                    unset($dir_data[$v['plugins']]);
                }
            }
        }

        $data = [
            'db_data'   => $db_data,
            'dir_data'  => array_values($dir_data),
        ];
        return DataReturn(MyLang('handle_success'), 0, $data);
    }

    /**
     * 应用安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsInstall($params = [])
    {
        // 参数
        if(empty($params['id']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }

        // 数据处理
        $plugins = $params['id'];
        $config = self::GetPluginsConfig($plugins);
        if(!empty($config) && !empty($config['base']) && !empty($config['base']['name']))
        {
            // 插件前置事件
            $ret = PluginsService::PluginsEventCall($plugins, 'BeginInstall', $params);
            if(!empty($ret) && isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 添加处理
            $cache = PluginsService::PluginsCacheData($plugins);
            $data = [
                'name'      => $config['base']['name'],
                'plugins'   => $plugins,
                'data'      => empty($cache) ? '' : json_encode($cache),
                'is_enable' => 0,
                'add_time'  => time(),
            ];

            // 添加数据
            if(Db::name('Plugins')->insertGetId($data) > 0)
            {
                // 附件同步到数据库
                ResourcesService::AttachmentDiskFilesToDb('plugins_'.$plugins);

                // 插件事件回调
                PluginsService::PluginsEventCall($plugins, 'Install', $params);

               return DataReturn(MyLang('install_success'), 0);
            } else {
                return DataReturn(MyLang('install_fail'), -100);
            }
        } else {
            return DataReturn(MyLang('plugins_config_error_tips'), -10);
        }
    }

    /**
     * 卸载应用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsUninstall($params = [])
    {
        // 参数
        if(empty($params['id']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }

        // 开启事务
        Db::startTrans();

        // 开始卸载
        $plugins = $params['id'];
        if(DB::name('Plugins')->where(['plugins'=>$plugins])->delete())
        {
            // 钩子部署
            $ret = self::PluginsHookDeployment();
            if($ret['code'] == 0)
            {
                // 提交事务
                Db::commit();

                // 插件事件回调
                PluginsService::PluginsEventCall($plugins, 'Uninstall', $params);

                return DataReturn(MyLang('uninstall_success'), 0);
            }
        } else {
            $ret = DataReturn(MyLang('uninstall_fail'), -100);
        }

        // 事务回退
        Db::rollback();
        return $ret;
    }

    /**
     * 获取插件配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [string]          $plugins [插件名称]
     */
    public static function GetPluginsConfig($plugins)
    {
        $config = [];
        $file = APP_PATH.'plugins'.DS.$plugins.DS.'config.json';
        if(file_exists($file))
        {
            $config = json_decode(file_get_contents($file), true);
        }
        return empty($config) ? [] : $config;
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PluginsStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开启事务
        Db::startTrans();

        // 数据更新
        if(Db::name('Plugins')->where(['plugins'=>$params['id']])->update(['is_enable'=>intval($params['state']), 'upd_time'=>time()]))
        {
            // 钩子部署
            $ret = self::PluginsHookDeployment();
            if($ret['code'] == 0)
            {
                // 提交事务
                Db::commit();
                return DataReturn(MyLang('operate_success'), 0);
            }
        } else {
            $ret = DataReturn(MyLang('operate_fail'), -100);
        }

        // 事务回退
        Db::rollback();
        return $ret;
    }

    /**
     * 应用钩子部署
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-13
     * @desc    description
     */
    private static function PluginsHookDeployment()
    {
        // 钩子配置文件
        $event_file = APP_PATH.'event.php';
        if(file_exists($event_file))
        {
            if(!is_writable($event_file))
            {
                return DataReturn(MyLang('common_service.pluginsadmin.file_no_power_tips').'['.$event_file.']', -3);
            }
        } else {
            // 文件不存在则确认目录是否可写
            if(!is_writable(APP_PATH))
            {
                return DataReturn(MyLang('common_service.pluginsadmin.dir_no_power_tips').'['.$event_file.']', -3);
            }
        }

        // 钩子容器
        $result = [];

        // 系统自带钩子处理
        if(file_exists($event_file))
        {
            $tags = require $event_file;
            if(!empty($tags) && is_array($tags))
            {
                $system_hook_list = [
                    'AppInit',
                    'HttpRun',
                    'HttpEnd',
                    'RouteLoaded',
                    'LogRecord'
                ];
                foreach($system_hook_list as $system_hook)
                {
                    if(isset($tags[$system_hook]))
                    {
                        $result[$system_hook] = $tags[$system_hook];
                    }
                }
            }
        }

        // 处理应用钩子
        $data = Db::name('Plugins')->where(['is_enable'=>1])->order(self::$plugins_order_by)->column('plugins');
        if(!empty($data))
        {
            foreach($data as $plugins)
            {
                if(file_exists(APP_PATH.'plugins'.DS.$plugins.DS.'config.json'))
                {
                    $config = json_decode(file_get_contents(APP_PATH.'plugins'.DS.$plugins.DS.'config.json'), true);
                    if(!empty($config['hook']) && is_array($config['hook']))
                    {
                        foreach($config['hook'] as $hook_key=>$hook_value)
                        {
                            if(isset($result[$hook_key]))
                            {
                               $result[$hook_key] = array_merge($result[$hook_key], $hook_value); 
                            } else {
                                $result[$hook_key] = $hook_value;
                            }
                        }
                    }
                }
            }
        }

        // 部署钩子到文件
        $ret = @file_put_contents($event_file, "<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// 应用行为扩展定义文件\nreturn ".var_export(['listen'=>$result], true).";\n?>");
        if($ret === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_deployment_fail_tips'), -10);
        }

        return DataReturn(MyLang('handle_success'), 0);
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 应用是否存在
        $plugins = $params['id'];
        if(!file_exists(APP_PATH.'plugins'.DS.$plugins))
        {
           return DataReturn(MyLang('common_service.pluginsadmin.app_no_exist_tips'), -10); 
        }
        
        // 获取应用标记
        $data = Db::name('Plugins')->where(['plugins'=>$plugins])->find();
        if(!empty($data['is_enable']))
        {
           return DataReturn(MyLang('common_service.pluginsadmin.please_uninstall_tips'), -10);
        }

        // 钩子部署
        $ret = self::PluginsHookDeployment();
        if($ret['code'] == 0)
        {
            // 是否需要删除应用数据,sql运行
            $is_delete_data = (isset($params['value']) && $params['value'] == 1);

            // 删除数据
            if($is_delete_data === true)
            {
                // 删除缓存
                PluginsService::PluginsCacheDelete($plugins);

                // 执行卸载sql
                $uninstall_sql = APP_PATH.'plugins'.DS.$plugins.DS.'uninstall.sql';
                if(file_exists($uninstall_sql))
                {
                    SqlConsoleService::Implement(['sql'=>file_get_contents($uninstall_sql)]);
                }
            }

            // 删除应用文件
            self::PluginsResourcesDelete($plugins, $is_delete_data);

            // 删除数据
            if($is_delete_data === true)
            {
                // 删除数据库附件
                ResourcesService::AttachmentPathTypeDelete('plugins_'.$plugins);
            }

            // 插件事件回调
            PluginsService::PluginsEventCall($plugins, 'Delete', $params);

            return DataReturn(MyLang('delete_success'), 0);
        }
        return $ret;
    }

    /**
     * 应用资源删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-13
     * @desc    description
     * @param   [string]          $plugins          [唯一标记]
     * @param   [boolean]         $is_delete_data   [是否删除应用数据]
     */
    private static function PluginsResourcesDelete($plugins, $is_delete_data = false)
    {
        \base\FileUtil::UnlinkDir(APP_PATH.'plugins'.DS.$plugins);
        \base\FileUtil::UnlinkDir(APP_PATH.'plugins'.DS.'view'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images'.DS.$plugins);

        // 是否需要删除应用数据
        if($is_delete_data === true)
        {
            \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'upload'.DS.'images'.DS.'plugins_'.$plugins);
            \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'upload'.DS.'video'.DS.'plugins_'.$plugins);
            \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'upload'.DS.'file'.DS.'plugins_'.$plugins);
        }
    }

    /**
     * 保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'plugins',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_plugins_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'logo',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_logo_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_name_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'author',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_author_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'author_url',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_author_url_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'version',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_version_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'desc',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_desc_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_terminal',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_apply_terminal_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_version',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_apply_version_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 权限校验
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 应用唯一标记
        $plugins = trim($params['plugins']);

        // 应用校验
        $ret = self::PluginsVerification($plugins);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 应用目录不存在则创建
        $app_dir = APP_PATH.'plugins'.DS.$plugins;
        if(!is_dir($app_dir) && \base\FileUtil::CreateDir($app_dir) !== true)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_dir_create_fial_tips'), -10);
        }

        // 生成配置文件
        $ret = self::PluginsConfigCreated($params, $app_dir);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 应用主文件生成
        $ret = self::PluginsApplicationCreated($params, $app_dir);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 应用文件生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params    [输入参数]
     * @param   [string]         $app_dir   [主目录地址]
     */
    private static function PluginsApplicationCreated($params, $app_dir)
    {
        $plugins = trim($params['plugins']);
$admin=<<<php
<?php
namespace app\plugins\\$plugins\admin;

// {$params['name']} - 后台管理
class Admin
{
    // 后台管理入口
    public function index(\$params = [])
    {
        // 数组组装
        MyViewAssign('data', ['hello', 'world!']);
        MyViewAssign('msg', 'hello world! admin');
        return MyView('../../../plugins/view/$plugins/admin/admin/index');
    }
}
?>
php;

$hook=<<<php
<?php
namespace app\plugins\\$plugins;

// {$params['name']} - 钩子入口
class Hook
{
    // 应用响应入口
    public function handle(\$params = [])
    {
        // 是否控制器钩子
        // is_backend 当前为后端业务处理
        // hook_name 钩子名称
        if(isset(\$params['is_backend']) && \$params['is_backend'] === true && !empty(\$params['hook_name']))
        {
            // 参数一   描述
            // 参数二   0 为处理成功, 负数为失败
            // 参数三   返回数据
            return DataReturn('返回描述', 0);

        // 默认返回视图
        } else {
            return 'hello world!';
        }
    }
}
?>
php;

$index=<<<php
<?php
namespace app\plugins\\$plugins\index;

// {$params['name']} - 前端独立页面入口
class Index
{
    // 前端页面入口
    public function index(\$params = [])
    {
        // 数组组装
        MyViewAssign('data', ['hello', 'world!']);
        MyViewAssign('msg', 'hello world! index');
        return MyView('../../../plugins/view/$plugins/index/index/index');
    }
}
?>
php;

$admin_view=<<<php
{{include file="public/header" /}}

<!-- right content start  -->
<div class="content-right">
    <div class="content">
        <h1>后台管理页面</h1>
        {{:print_r(\$data)}}
        <p class="msg">{{\$msg}}</p>
    </div>
</div>
<!-- right content end  -->

<!-- footer start -->
{{include file="public/footer" /}}
<!-- footer end -->
php;

$index_view=<<<php
{{include file="public/header" /}}

<!-- nav start -->
{{include file="public/nav" /}}
<!-- nav end -->

<!-- header top nav -->
{{include file="public/header_top_nav" /}}

<!-- search -->
{{include file="public/nav_search" /}}

<!-- header nav -->
{{include file="public/header_nav" /}}

<!-- goods category -->
{{include file="public/goods_category" /}}

<!-- content start -->
<div class="am-g my-content">
    <div class="am-u-md-6 am-u-sm-centered">
        <h1>前端页面</h1>
        {{:print_r(\$data)}}
        <p class="msg">{{\$msg}}</p>
    </div>
</div>
<!-- content end -->

<!-- footer start -->
{{include file="public/footer" /}}
<!-- footer end -->
php;

$admin_css=<<<php
h1 {
    font-size: 60px;
}
.msg {
    font-size: 38px;
    color: #F00;
}
php;

$index_css=<<<php
h1 {
    font-size: 60px;
}
.msg {
    font-size: 68px;
    color: #4CAF50;
}
php;
        // 静态文件目录
        $app_static_css_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS.trim($params['plugins']);
        if(!is_dir($app_static_css_dir) && \base\FileUtil::CreateDir($app_static_css_dir) !== true)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_static_dir_create_fail_tips').'[css]', -10);
        } else {
            // 后端css目录创建
            if(!is_dir($app_static_css_dir.DS.'admin') && \base\FileUtil::CreateDir($app_static_css_dir.DS.'admin') !== true)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_static_dir_create_fail_tips').'[css/admin]', -10);
            }
        }

        // 后端admin目录创建
        if(!is_dir($app_dir.DS.'admin') && \base\FileUtil::CreateDir($app_dir.DS.'admin') !== true)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_admin_dir_create_fail_tips').'[admin]', -10);
        }

        // 创建文件
        if(!file_exists($app_dir.DS.'admin'.DS.'Admin.php') && @file_put_contents($app_dir.DS.'admin'.DS.'Admin.php', $admin) === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_file_create_fail_tips').'[Admin.php]', -11);
        }
        if(!file_exists($app_dir.DS.'Hook.php') && @file_put_contents($app_dir.DS.'Hook.php', $hook) === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_file_create_fail_tips').'[Hook.php]', -11);
        }

        // 应用后台视图目录不存在则创建
        $app_view_admin_dir = APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'admin'.DS.'admin';
        if(!is_dir($app_view_admin_dir) && \base\FileUtil::CreateDir($app_view_admin_dir) !== true)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_view_dir_create_fail_tips').'[admin]', -10);
        }
        if(!file_exists($app_view_admin_dir.DS.'index.html') && @file_put_contents($app_view_admin_dir.DS.'index.html', $admin_view) === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_view_file_create_fail_tips').'[admin-view]', -11);
        }

        // css创建
        if(!file_exists($app_static_css_dir.DS.'admin'.DS.'admin.css') && @file_put_contents($app_static_css_dir.DS.'admin'.DS.'admin.css', $admin_css) === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_static_file_create_fail_tips').'[admin-css]', -11);
        }

        // 是否有前端页面
        if(isset($params['is_home']) && $params['is_home'] == 1)
        {
            // 前端index目录创建
            if(!is_dir($app_dir.DS.'index') && \base\FileUtil::CreateDir($app_dir.DS.'index') !== true)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_home_dir_create_fail_tips').'[index]', -10);
            }

            // 创建文件
            if(!file_exists($app_dir.DS.'index'.DS.'Index.php') && @file_put_contents($app_dir.DS.'index'.DS.'Index.php', $index) === false)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_file_create_fail_tips').'[index]', -11);
            }

            // 应用前端视图目录不存在则创建
            $app_view_index_dir = APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'index'.DS.'index';
            if(!is_dir($app_view_index_dir) && \base\FileUtil::CreateDir($app_view_index_dir) !== true)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_view_dir_create_fail_tips').'[index]', -10);
            }
            if(!file_exists($app_view_index_dir.DS.'index.html') && @file_put_contents($app_view_index_dir.DS.'index.html', $index_view) === false)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_view_file_create_fail_tips').'[index-view]', -11);
            }

            // 前端css目录创建
            if(!is_dir($app_static_css_dir.DS.'index') && \base\FileUtil::CreateDir($app_static_css_dir.DS.'index') !== true)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_static_dir_create_fail_tips').'[css/index]', -10);
            }

            // css创建
            if(!file_exists($app_static_css_dir.DS.'index'.DS.'index.css') && @file_put_contents($app_static_css_dir.DS.'index'.DS.'index.css', $index_css) === false)
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_static_file_create_fail_tips').'[index-css]', -11);
            }
        }

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 应用配置文件生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params    [输入参数]
     * @param   [string]         $app_dir   [主目录地址]
     */
    private static function PluginsConfigCreated($params, $app_dir)
    {
        // 模块名称
        $plugins = trim($params['plugins']);

        // 配置信息
        $config = self::GetPluginsConfig($plugins);
        $hook = (empty($config) || empty($config['hook'])) ? [] : $config['hook'];

        // 配置信息组装
        $data = [
            // 基础信息
            'base'  => [
                'plugins'           => $plugins,
                'name'              => $params['name'],
                'logo'              => ResourcesService::AttachmentPathHandle($params['logo']),
                'author'            => $params['author'],
                'author_url'        => $params['author_url'],
                'version'           => $params['version'],
                'desc'              => $params['desc'],
                'apply_terminal'    => explode(',', $params['apply_terminal']),
                'apply_version'     => explode(',', $params['apply_version']),
                'is_home'           => (isset($params['is_home']) && $params['is_home'] == 1) ? true : false,
            ],

            // 钩子配置
            'hook'  => (object) $hook,
        ];

        // 文件是否存在、存在判断权限、则创建
        $config_file = $app_dir.DS.'config.json';
        if(file_exists($config_file))
        {
            // 文件存在是否有权限
            if(!is_writable($config_file))
            {
                return DataReturn(MyLang('common_service.pluginsadmin.app_config_no_power_tips').'['.$config_file.']', -3);
            }
        }

        // 创建配置文件
        if(@file_put_contents($config_file, JsonFormat($data)) === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_config_create_tail_tips'), -10);
        }

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 名称排除校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-13T00:00:45+0800
     * @param   [string]          $plugins   [应用唯一标记]
     */
    public static function PluginsVerification($plugins)
    {
        if(in_array($plugins, self::$plugins_exclude_verification))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_name_exclude_tips').'['.$plugins.']', -1);
        }
        return DataReturn(MyLang('check_success'), 0);
    }

    /**
     * 应用是否存在
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [string]          $plugins   [应用唯一标记]
     */
    private static function PluginsExist($plugins)
    {
        return is_dir(APP_PATH.'plugins'.DS.$plugins);
    }

    /**
     * 权限校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-29T00:01:49+0800
     */
    private static function PowerCheck()
    {
        // 应用目录
        $app_dir = APP_PATH.'plugins';
        if(!is_writable($app_dir))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_dir_no_power_tips').'['.$app_dir.']', -3);
        }

        // 应用视图目录
        $app_view_dir = APP_PATH.'plugins'.DS.'view';
        if(!is_writable($app_view_dir))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_view_dir_no_power_tips').'['.$app_view_dir.']', -3);
        }

        // 应用css目录
        $app_static_css_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css';
        if(!is_writable($app_static_css_dir))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_css_dir_no_power_tips').'['.$app_static_css_dir.']', -3);
        }

        // 应用js目录
        $app_static_js_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js';
        if(!is_writable($app_static_js_dir))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_js_dir_no_power_tips').'['.$app_static_js_dir.']', -3);
        }

        // 应用images目录
        $app_static_images_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images';
        if(!is_writable($app_static_images_dir))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_images_dir_no_power_tips').'['.$app_static_images_dir.']', -3);
        }

        // 应用upload目录
        $app_upload_dir = ROOT.'public'.DS.'static'.DS.'upload';
        if(!is_writable($app_upload_dir))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_upload_dir_no_power_tips').'['.$app_upload_dir.']', -3);
        }

        return DataReturn('success', 0);
    }

    /**
     * 应用上传
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:53:45+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PluginsUpload($params = [])
    {
        // 文件上传校验
        $error = FileUploadError('file');
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 文件格式化校验
        $type = ResourcesService::ZipExtTypeList();
        if(!in_array($_FILES['file']['type'], $type))
        {
            return DataReturn(MyLang('form_upload_zip_message'), -2);
        }

        // 上传处理
        return self::PluginsUploadHandle($_FILES['file']['tmp_name'], $params);
    }

    /**
     * 应用上传处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:53:45+0800
     * @param    [string]         $package_file [软件包地址]
     * @param    [array]          $params       [输入参数]
     */
    public static function PluginsUploadHandle($package_file, $params = [])
    {
        // 权限校验
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 包处理
        $ret = self::PluginsPackageHandle($package_file, 0);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $plugins = $ret['data'];

        // 强制刷新用户权限缓存
        AdminPowerService::PowerMenuInit(null, true);

        // 附件同步到数据库
        ResourcesService::AttachmentDiskFilesToDb('plugins_'.$plugins);

        // sql运行
        $install_sql = APP_PATH.'plugins'.DS.$plugins.DS.'install.sql';
        if(!empty($plugins) && file_exists($install_sql))
        {
            SqlConsoleService::Implement(['sql'=>file_get_contents($install_sql)]);
        }

        // 插件事件回调
        PluginsService::PluginsEventCall($plugins, 'Upload', $params);

        return DataReturn(MyLang('install_success'), 0);
    }

    /**
     * 插件包处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-24
     * @desc    description
     * @param   [string]      $package_file [包地址]
     * @param   [int]         $type         [类型（0上传, 1更新）]
     * @param   [string]      $plugins_old  [原插件标识名称]
     */
    public static function PluginsPackageHandle($package_file, $type = 0, $plugins_old = '')
    {
        // 资源目录
        $dir_list = self::PluginsDirStructureMapping();

        // 包名
        $plugins = '';

        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource != true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }

        // 文件第一个目录为当前插件名称
        $entry = $zip->statIndex(0);
        $file = $entry['name'];
        //根据第一个文件是目录，还是包含namespace payment，判断插件类型
        if(str_ends_with($file, '/'))
        {
            //获取plugins
            $plugins = substr($file, 0, strpos($file, '/'));

            // 业务类型处理
            switch($type)
            {
                // 上传安装
                case 0 :
                    // 应用不存在则添加
                    $ret = self::PluginsVerification($plugins);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }

                    // 应用是否存在
                    if(self::PluginsExist($plugins))
                    {
                        return DataReturn(MyLang('common_service.pluginsadmin.app_name_exist_tips').'['.$plugins.']', -1);
                    }
                    break;

                // 更新
                case 1 :
                    // 应用是否存在
                    if($plugins != $plugins_old)
                    {
                        return DataReturn(MyLang('common_service.pluginsadmin.app_name_appoint_error_tips').'['.$plugins.'<>'.$plugins_old.']', -1);
                    }
                    break;
            }
        } else {
            // 应用名称为空、则校验是否为支付插件
            $stream = $zip->getStream($file);
            if($stream !== false)
            {
                $file_content = stream_get_contents($stream);
                if($file_content !== false)
                {
                    if(stripos($file_content, 'namespace payment') !== false)
                    {
                        return DataReturn(MyLang('common_service.pluginsadmin.plugins_package_error_tips'), -1);
                    }
                }
                fclose($stream);
            }

            // 不是支付插件则提示插件包错误
            return DataReturn(MyLang('common_service.pluginsadmin.plugins_package_empty_tips'), -30);
        }

        // 应用文件处理
        $success = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && strpos($file, '__') === false)
            {
                // 文件包对应系统所在目录
                $is_has_find = false;
                foreach($dir_list as $dir_key=>$dir_value)
                {
                    if(strpos($file, $dir_key) !== false)
                    {
                        // 仅控制器模块支持php文件
                        if($dir_key != '_controller_')
                        {
                            // 排除后缀文件
                            $pos = strripos($file, '.');
                            if($pos !== false)
                            {
                                $info = pathinfo($file);
                                if(isset($info['extension']) && in_array(strtolower($info['extension']), self::$exclude_ext))
                                {
                                    continue;
                                }
                            }
                        }

                        // 匹配成功文件路径处理、跳出循环
                        $new_file = str_replace($plugins.'/'.$dir_key.'/', '', $dir_value.$file);
                        $is_has_find = true;
                        break;
                    }
                }

                // 没有匹配到则指定目录跳过
                if($is_has_find == false)
                {
                    continue;
                }

                // 截取文件路径
                $file_path = substr($new_file, 0, strrpos($new_file, '/'));

                // 路径不存在则创建
                \base\FileUtil::CreateDir($file_path);

                // 如果不是目录则写入文件
                if(!is_dir($new_file))
                {
                    // 读取这个文件
                    $stream = $zip->getStream($file);
                    if($stream !== false)
                    {
                        $file_content = stream_get_contents($stream);
                        if($file_content !== false)
                        {
                            if(file_put_contents($new_file, $file_content))
                            {
                                $success++;
                            }
                        }
                        fclose($stream);
                    }
                }
            }
        }
        // 关闭zip  
        $zip->close();

        // 未匹配成功一个文件则认为插件包无效
        if($success <= 0)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.plugins_package_invalid_tips'), -1);
        }
        return DataReturn('success', 0, $plugins);
    }

    /**
     * 插件目录结构
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     */
    public static function PluginsDirStructureMapping()
    {
        return [
            '_controller_'      => APP_PATH.'plugins'.DS,
            '_view_'            => APP_PATH.'plugins'.DS.'view'.DS,
            '_css_'             => ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS,
            '_js_'              => ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js'.DS,
            '_images_'          => ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images'.DS,
            '_uploadfile_'      => ROOT.'public'.DS.'static'.DS.'upload'.DS.'file'.DS,
            '_uploadimages_'    => ROOT.'public'.DS.'static'.DS.'upload'.DS.'images'.DS,
            '_uploadvideo_'     => ROOT.'public'.DS.'static'.DS.'upload'.DS.'video'.DS,
        ];
    }

    /**
     * 应用打包
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsDownload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 生成下载包
        $ret = self::PluginsDownloadHandle($params['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始下载
        if(\base\FileUtil::DownloadFile($ret['data']['file'], $ret['data']['config']['base']['name'].'_v'.$ret['data']['config']['base']['version'].'.zip'))
        {
            // 删除文件
            @unlink($ret['data']['file']);

            // 插件事件回调
            PluginsService::PluginsEventCall($ret['data']['plugins'], 'Download', $params);
        } else {
            return DataReturn(MyLang('download_fail'), -100);
        }
    }

    /**
     * 上传到应用商店
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PluginsStoreUpload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'plugins',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_plugins_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'version_new',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_version_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'describe',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_desc_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_version',
                'error_msg'         => MyLang('common_service.pluginsadmin.form_item_apply_version_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 生成下载包
        $package = self::PluginsDownloadHandle($params['plugins']);
        if($package['code'] != 0)
        {
            return $package;
        }

        // 帐号信息
        $user = StoreService::AccountsData();
        if(empty($user['accounts']) || empty($user['password']))
        {
            return DataReturn(MyLang('store_account_not_bind_tips'), -300);
        }

        // 上传到远程
        $params['file'] = new \CURLFile($package['data']['file']);
        $ret = StoreService::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_plugins_upload_url'), $params, 2);
        // 是个与否都删除本地生成的zip包
        @unlink($package['data']['file']);
        // 返回数据
        return $ret;
    }

    /**
     * 应用下载打包处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-17
     * @desc    description
     * @param   [string]           $plugins [应用标识]
     */
    public static function PluginsDownloadHandle($plugins)
    {
        // 是否开启开发者模式
        if(MyConfig('shopxo.is_develop') !== true)
        {
            return DataReturn(MyLang('not_open_developer_mode_tips'), -1); 
        }

        // 获取应用标记
        // 防止路径回溯
        $plugins = htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($plugins)));
        if(empty($plugins))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.plugins_identification_error_tips'), -1);
        }

        // 配置信息
        $config = self::GetPluginsConfig($plugins);
        if(empty($config))
        {
            return DataReturn(MyLang('plugins_config_error_tips'), -10);
        }

        // 安全判断
        $ret = PluginsService::PluginsLegalCheck($plugins);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 目录不存在则创建
        $new_dir = ROOT.'runtime'.DS.'data'.DS.'plugins_package'.DS.$plugins;
        \base\FileUtil::CreateDir($new_dir);

        // 复制包目录 - 控制器
        $old_dir = APP_PATH.'plugins'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_controller_'.DS.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'['.MyLang('common_service.pluginsadmin.plugins_copy_control_fail_tips').']', -2);
            }
        }

        // 复制包目录 - 视图
        $old_dir = APP_PATH.'plugins'.DS.'view'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_view_'.DS.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'['.MyLang('common_service.pluginsadmin.plugins_copy_view_fail_tips').']', -2);
            }
        }

        // 复制包目录 - css
        $old_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_css_'.DS.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'[css]', -2);
            }
        }

        // 复制包目录 - js
        $old_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_js_'.DS.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'[js]', -2);
            }
        }

        // 复制包目录 - images
        $old_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_images_'.DS.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'[images]', -2);
            }
        }

        // 复制包目录 - uploadimages
        $old_dir = ROOT.'public'.DS.'static'.DS.'upload'.DS.'images'.DS.'plugins_'.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_uploadimages_'.DS.'plugins_'.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'[uploadimages]', -2);
            }
        }

        // 复制包目录 - uploadvideo
        $old_dir = ROOT.'public'.DS.'static'.DS.'upload'.DS.'video'.DS.'plugins_'.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_uploadvideo_'.DS.'plugins_'.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'[uploadvideo]', -2);
            }
        }

        // 复制包目录 - uploadfile
        $old_dir = ROOT.'public'.DS.'static'.DS.'upload'.DS.'file'.DS.'plugins_'.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_uploadfile_'.DS.'plugins_'.$plugins) != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'[uploadfile]', -2);
            }
        }

        // 配置文件历史信息更新
        $new_config_file = $new_dir.DS.'_controller_'.DS.$plugins.DS.'config.json';
        if(!file_exists($new_config_file))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.plugins_new_config_error_tips'), -10);
        }
        if(empty($config['history']))
        {
            $config['history'] = [];
        }
        $config['history'][] = [
            'host'  => __MY_HOST__,
            'url'   => __MY_URL__,
            'ip'    => __MY_ADDR__,
            'time'  => date('Y-m-d H:i:s'),
        ];
        if(@file_put_contents($new_config_file, JsonFormat($config)) === false)
        {
            return DataReturn(MyLang('common_service.pluginsadmin.plugins_new_config_update_fail_tips'), -11);
        }

        // 生成压缩包
        $zip = new \base\ZipFolder();
        if(!$zip->zip($new_dir.'.zip', $new_dir))
        {
            return DataReturn(MyLang('form_generate_zip_message'), -100);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($new_dir);

        // 返回数据
        return DataReturn('success', 0, [
            'file'     => $new_dir.'.zip',
            'config'   => $config,
            'plugins'  => $plugins,
        ]);
    }

    /**
     * 插件更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [string]          $package_file     [插件包文件]
     * @param   [array]           $params           [输入参数]
     */
    public static function PluginsUpgradeHandle($package_file, $params = [])
    {
        // 权限校验
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 基础业务参数
        if(empty($params['plugins_value']))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.plugins_identification_empty_tips'), -1);
        }

        // 应用是否存在
        if(!self::PluginsExist($params['plugins_value']))
        {
            return DataReturn(MyLang('common_service.pluginsadmin.app_update_no_exist_tips', ['plugins'=>$params['plugins_value']]), -1);
        }

        // 插件前置事件
        $ret = PluginsService::PluginsEventCall($params['plugins_value'], 'BeginUpgrade', $params);
        if(!empty($ret) && isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 包处理
        $ret = self::PluginsPackageHandle($package_file, 1, $params['plugins_value']);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $plugins = $ret['data'];

        // 更新sql
        $sql_file = APP_PATH.'plugins'.DS.$plugins.DS.'update.sql';
        if(!empty($plugins) && file_exists($sql_file))
        {
            $sql = file_get_contents($sql_file);
            if(!empty($sql))
            {
                SqlConsoleService::Implement(['sql'=>$sql]);
            }
        }

        // 钩子部署
        $ret = self::PluginsHookDeployment();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 插件事件回调
        PluginsService::PluginsEventCall($plugins, 'Upgrade', $params);

        return DataReturn(MyLang('update_success'));
    }

    /**
     * 设置保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-05
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SetupSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'data',
                'error_msg'         => MyLang('common_service.pluginsadmin.setup_save_data_empty_tips'),
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 排序数据、非数组则当做json转换一次
        $data = is_array($params['data']) ? $params['data'] : json_decode($params['data'], true);
        if(!empty($data) && is_array($data))
        {
            // 启动事务
            Db::startTrans();

            // 捕获异常
            try {
                foreach($data as $v)
                {
                    if(!empty($v['id']))
                    {
                        $upd_data = [
                            'sort'                  => empty($v['sort']) ? 0 : intval($v['sort']),
                            'plugins_category_id'   => empty($v['category_id']) ? 0 : intval($v['category_id']),
                            'plugins_menu_control'  => empty($v['menu_control']) ? '' : strtolower($v['menu_control']),
                            'upd_time'              => time(),
                        ];
                        if(Db::name('Plugins')->where(['id'=>intval($v['id'])])->update($upd_data) === false)
                        {
                            throw new \Exception(MyLang('operate_fail'));
                        }
                    }
                }

                // 钩子部署
                $ret = self::PluginsHookDeployment();
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 完成
                Db::commit();
                return DataReturn(MyLang('operate_success'), 0);
            } catch(\Exception $e) {
                Db::rollback();
                return DataReturn($e->getMessage(), -1);
            }
        }
        return DataReturn(MyLang('common_service.pluginsadmin.setup_save_data_error_tips'), -1);
    }
}
?>