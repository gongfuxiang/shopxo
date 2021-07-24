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
use app\service\PluginsService;
use app\service\ResourcesService;
use app\service\SqlConsoleService;

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
                            $db_config = array_key_exists($base['plugins'], $temp_data) ? $temp_data[$base['plugins']] : [];

                            // 数据组装
                            $dir_data[$base['plugins']] = [
                                'id'            => empty($db_config['id']) ? 0 : $db_config['id'],
                                'plugins'       => $base['plugins'],
                                'is_enable'     => isset($db_config['is_enable']) ? $db_config['is_enable'] : 0,
                                'is_install'    => empty($db_config) ? 0 : 1,
                                'logo_old'      => $base['logo'],
                                'logo'          => ResourcesService::AttachmentPathViewHandle($base['logo']),
                                'is_home'       => isset($base['is_home']) ? $base['is_home'] : false,
                                'name'          => isset($base['name']) ? $base['name'] : '',
                                'author'        => isset($base['author']) ? $base['author'] : '',
                                'author_url'    => isset($base['author_url']) ? $base['author_url'] : '',
                                'version'       => isset($base['version']) ? $base['version'] : '',
                                'desc'          => isset($base['desc']) ? $base['desc'] : '',
                                'apply_version' => isset($base['apply_version']) ? $base['apply_version'] : [],
                                'apply_terminal'=> isset($base['apply_terminal']) ? $base['apply_terminal'] : [],
                                'add_time_time' => isset($db_config['add_time']) ? date('Y-m-d H:i:s', $db_config['add_time']) : '',
                                'add_time_date' => isset($db_config['add_time']) ? date('Y-m-d', $db_config['add_time']) : '',
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
        return DataReturn('处理成功', 0, $data);
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
            return DataReturn('参数错误', -1);
        }

        // 数据处理
        $plugins = $params['id'];
        $config = self::GetPluginsConfig($plugins);
        if(!empty($config) && !empty($config['base']) && !empty($config['base']['name']))
        {
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

               return DataReturn('安装成功');
            } else {
                return DataReturn('安装失败', -100);
            }
        } else {
            return DataReturn('插件配置有误', -10);
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
            return DataReturn('参数错误', -1);
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

                return DataReturn('卸载成功');
            }
        } else {
            $ret = DataReturn('卸载失败', -100);
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
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
                return DataReturn('操作成功');
            }
        } else {
            $ret = DataReturn('操作失败', -100);
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
                return DataReturn('文件没有权限'.'['.$event_file.']', -3);
            }
        } else {
            // 文件不存在则确认目录是否可写
            if(!is_writable(APP_PATH))
            {
                return DataReturn('目录操作权限'.'['.$event_file.']', -3);
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
            return DataReturn('应用钩子部署失败', -10);
        }

        return DataReturn('处理成功', 0);
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
                'error_msg'         => '操作id有误',
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
           return DataReturn('应用不存在', -10); 
        }
        
        // 获取应用标记
        $data = Db::name('Plugins')->where(['plugins'=>$plugins])->find();
        if(!empty($data['is_enable']))
        {
           return DataReturn('请先卸载应用', -10);
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

            // 删除数据库附件
            ResourcesService::AttachmentPathTypeDelete('plugins_'.$plugins);

            // 插件事件回调
            PluginsService::PluginsEventCall($plugins, 'Delete', $params);

            // 删除应用文件
            self::PluginsResourcesDelete($plugins, $is_delete_data);

            return DataReturn('删除成功');
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
                'error_msg'         => '应用唯一标记不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'logo',
                'error_msg'         => '请上传LOGO',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '应用名称不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'author',
                'error_msg'         => '作者不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'author_url',
                'error_msg'         => '作者主页不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'version',
                'error_msg'         => '版本号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'desc',
                'error_msg'         => '描述不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_terminal',
                'error_msg'         => '请至少选择一个适用终端',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_version',
                'error_msg'         => '请至少选择一个适用系统版本',
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
            return DataReturn('应用主目录创建失败', -10);
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

        return DataReturn('操作成功', 0);
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
            return DataReturn('应用静态目录创建失败[css]', -10);
        } else {
            // 后端css目录创建
            if(!is_dir($app_static_css_dir.DS.'admin') && \base\FileUtil::CreateDir($app_static_css_dir.DS.'admin') !== true)
            {
                return DataReturn('应用静态目录创建失败[css/admin]', -10);
            }
        }

        // 后端admin目录创建
        if(!is_dir($app_dir.DS.'admin') && \base\FileUtil::CreateDir($app_dir.DS.'admin') !== true)
        {
            return DataReturn('应用后端目录创建失败[admin]', -10);
        }

        // 创建文件
        if(!file_exists($app_dir.DS.'admin'.DS.'Admin.php') && @file_put_contents($app_dir.DS.'admin'.DS.'Admin.php', $admin) === false)
        {
            return DataReturn('应用文件创建失败[Admin.php]', -11);
        }
        if(!file_exists($app_dir.DS.'Hook.php') && @file_put_contents($app_dir.DS.'Hook.php', $hook) === false)
        {
            return DataReturn('应用文件创建失败[Hook.php]', -11);
        }

        // 应用后台视图目录不存在则创建
        $app_view_admin_dir = APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'admin'.DS.'admin';
        if(!is_dir($app_view_admin_dir) && \base\FileUtil::CreateDir($app_view_admin_dir) !== true)
        {
            return DataReturn('应用视图目录创建失败[admin]', -10);
        }
        if(!file_exists($app_view_admin_dir.DS.'index.html') && @file_put_contents($app_view_admin_dir.DS.'index.html', $admin_view) === false)
        {
            return DataReturn('应用视图文件创建失败[admin-view]', -11);
        }

        // css创建
        if(!file_exists($app_static_css_dir.DS.'admin'.DS.'admin.css') && @file_put_contents($app_static_css_dir.DS.'admin'.DS.'admin.css', $admin_css) === false)
        {
            return DataReturn('应用静态文件创建失败[admin-css]', -11);
        }

        // 是否有前端页面
        if(isset($params['is_home']) && $params['is_home'] == 1)
        {
            // 前端index目录创建
            if(!is_dir($app_dir.DS.'index') && \base\FileUtil::CreateDir($app_dir.DS.'index') !== true)
            {
                return DataReturn('应用前端目录创建失败[index]', -10);
            }

            // 创建文件
            if(!file_exists($app_dir.DS.'index'.DS.'Index.php') && @file_put_contents($app_dir.DS.'index'.DS.'Index.php', $index) === false)
            {
                return DataReturn('应用文件创建失败[index]', -11);
            }

            // 应用前端视图目录不存在则创建
            $app_view_index_dir = APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'index'.DS.'index';
            if(!is_dir($app_view_index_dir) && \base\FileUtil::CreateDir($app_view_index_dir) !== true)
            {
                return DataReturn('应用视图目录创建失败[index]', -10);
            }
            if(!file_exists($app_view_index_dir.DS.'index.html') && @file_put_contents($app_view_index_dir.DS.'index.html', $index_view) === false)
            {
                return DataReturn('应用视图文件创建失败[index-view]', -11);
            }

            // 前端css目录创建
            if(!is_dir($app_static_css_dir.DS.'index') && \base\FileUtil::CreateDir($app_static_css_dir.DS.'index') !== true)
            {
                return DataReturn('应用静态目录创建失败[css/index]', -10);
            }

            // css创建
            if(!file_exists($app_static_css_dir.DS.'index'.DS.'index.css') && @file_put_contents($app_static_css_dir.DS.'index'.DS.'index.css', $index_css) === false)
            {
                return DataReturn('应用静态文件创建失败[index-css]', -11);
            }
        }

        return DataReturn('操作成功', 0);
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
                return DataReturn('应用配置文件没有操作权限'.'['.$config_file.']', -3);
            }
        }

        // 创建配置文件
        if(@file_put_contents($config_file, JsonFormat($data)) === false)
        {
            return DataReturn('应用配置文件创建失败', -10);
        }

        return DataReturn('操作成功', 0);
    }

    /**
     * 名称校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-13T00:00:45+0800
     * @param   [string]          $plugins   [应用唯一标记]
     */
    public static function PluginsVerification($plugins)
    {
        // 排除校验
        if(in_array($plugins, self::$plugins_exclude_verification))
        {
            return DataReturn('不能使用限制的名称['.$plugins.']', -1);
        }

        return DataReturn('校验成功', 0);
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
            return DataReturn('应用目录没有操作权限'.'['.$app_dir.']', -3);
        }

        // 应用视图目录
        $app_view_dir = APP_PATH.'plugins'.DS.'view';
        if(!is_writable($app_view_dir))
        {
            return DataReturn('应用视图目录没有操作权限'.'['.$app_view_dir.']', -3);
        }

        // 应用css目录
        $app_static_css_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css';
        if(!is_writable($app_static_css_dir))
        {
            return DataReturn('应用css目录没有操作权限'.'['.$app_static_css_dir.']', -3);
        }

        // 应用js目录
        $app_static_js_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js';
        if(!is_writable($app_static_js_dir))
        {
            return DataReturn('应用js目录没有操作权限'.'['.$app_static_js_dir.']', -3);
        }

        // 应用images目录
        $app_static_images_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images';
        if(!is_writable($app_static_images_dir))
        {
            return DataReturn('应用images目录没有操作权限'.'['.$app_static_images_dir.']', -3);
        }

        // 应用upload目录
        $app_upload_dir = ROOT.'public'.DS.'static'.DS.'upload';
        if(!is_writable($app_upload_dir))
        {
            return DataReturn('应用upload目录没有操作权限'.'['.$app_upload_dir.']', -3);
        }

        return DataReturn('权限正常', 0);
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
            return DataReturn('文件格式有误，请上传zip压缩包', -2);
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

        return DataReturn('安装成功');
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
        $resource = zip_open($package_file);
        if(!is_resource($resource))
        {
            return DataReturn('压缩包打开失败['.$resource.']', -10);
        }

        while(($temp_resource = zip_read($resource)) !== false)
        {
            if(zip_entry_open($resource, $temp_resource))
            {
                // 当前压缩包中项目名称
                $file = zip_entry_name($temp_resource);

                // 获取包名
                if(empty($plugins))
                {
                    // 应用名称
                    $plugins = substr($file, 0, strpos($file, '/'));
                    if(empty($plugins))
                    {
                        // 应用名称为空、则校验是否为支付插件
                        $file_size = zip_entry_filesize($temp_resource);
                        $file_content = zip_entry_read($temp_resource, $file_size);
                        if(stripos($file_content, 'namespace payment') !== false)
                        {
                            return DataReturn('支付插件请到[ 网站管理->支付方式 ]模块里面去上传安装', -1);
                        }

                        // 不是支付插件则提示插件包错误
                        return DataReturn('插件包有误', -30);
                    }

                    // 业务类型处理
                    switch($type)
                    {
                        // 上传安装
                        case 0 :
                            // 应用不存在则添加
                            $ret = self::PluginsVerification($plugins);
                            if($ret['code'] != 0)
                            {
                                zip_entry_close($temp_resource);
                                return $ret;
                            }

                            // 应用是否存在
                            if(self::PluginsExist($plugins))
                            {
                                zip_entry_close($temp_resource);
                                return DataReturn('应用名称已存在['.$plugins.']', -1);
                            }
                            break;

                        // 更新
                        case 1 :
                            // 应用是否存在
                            if($plugins != $plugins_old)
                            {
                                zip_entry_close($temp_resource);
                                return DataReturn('应用标识与指定不一致['.$plugins.'<>'.$plugins_old.']', -1);
                            }
                            break;
                    }
                }

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
                                    if(isset($info['extension']) && in_array($info['extension'], self::$exclude_ext))
                                    {
                                        continue;
                                    }
                                }
                            }

                            // 匹配成功文件路径处理、跳出循环
                            $file = str_replace($plugins.'/'.$dir_key.'/', '', $dir_value.$file);
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
                    $file_path = substr($file, 0, strrpos($file, '/'));

                    // 路径不存在则创建
                    \base\FileUtil::CreateDir($file_path);

                    // 如果不是目录则写入文件
                    if(!is_dir($file))
                    {
                        // 读取这个文件
                        $file_size = zip_entry_filesize($temp_resource);
                        $file_content = zip_entry_read($temp_resource, $file_size);
                        @file_put_contents($file, $file_content);
                    }

                    // 关闭目录项  
                    zip_entry_close($temp_resource);
                }
            }
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
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启开发者模式
        if(MyConfig('shopxo.is_develop') !== true)
        {
            return DataReturn('请先开启开发者模式', -1); 
        }

        // 获取应用标记
        // 防止路径回溯
        $plugins = htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($params['id'])));
        if(empty($plugins))
        {
            return DataReturn('插件标识有误', -1);
        }

        // 配置信息
        $config = self::GetPluginsConfig($plugins);
        if(empty($config))
        {
            return DataReturn('插件配置有误', -10);
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
                return DataReturn('项目包复制失败[控制器]', -2);
            }
        }

        // 复制包目录 - 视图
        $old_dir = APP_PATH.'plugins'.DS.'view'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_view_'.DS.$plugins) != true)
            {
                return DataReturn('项目包复制失败[视图]', -2);
            }
        }

        // 复制包目录 - css
        $old_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_css_'.DS.$plugins) != true)
            {
                return DataReturn('项目包复制失败[css]', -2);
            }
        }

        // 复制包目录 - js
        $old_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_js_'.DS.$plugins) != true)
            {
                return DataReturn('项目包复制失败[js]', -2);
            }
        }

        // 复制包目录 - images
        $old_dir = ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images'.DS.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_images_'.DS.$plugins) != true)
            {
                return DataReturn('项目包复制失败[images]', -2);
            }
        }

        // 复制包目录 - uploadimages
        $old_dir = ROOT.'public'.DS.'static'.DS.'upload'.DS.'images'.DS.'plugins_'.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_uploadimages_'.DS.'plugins_'.$plugins) != true)
            {
                return DataReturn('项目包复制失败[uploadimages]', -2);
            }
        }

        // 复制包目录 - uploadvideo
        $old_dir = ROOT.'public'.DS.'static'.DS.'upload'.DS.'video'.DS.'plugins_'.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_uploadvideo_'.DS.'plugins_'.$plugins) != true)
            {
                return DataReturn('项目包复制失败[uploadvideo]', -2);
            }
        }

        // 复制包目录 - uploadfile
        $old_dir = ROOT.'public'.DS.'static'.DS.'upload'.DS.'file'.DS.'plugins_'.$plugins;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_uploadfile_'.DS.'plugins_'.$plugins) != true)
            {
                return DataReturn('项目包复制失败[uploadfile]', -2);
            }
        }

        // 配置文件历史信息更新
        $new_config_file = $new_dir.DS.'_controller_'.DS.$plugins.DS.'config.json';
        if(!file_exists($new_config_file))
        {
            return DataReturn('新配置文件有误', -10);
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
            return DataReturn('新应用配置文件更新失败', -11);
        }

        // 生成压缩包
        $zip = new \base\ZipFolder();
        if(!$zip->zip($new_dir.'.zip', $new_dir))
        {
            return DataReturn('压缩包生成失败', -100);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($new_dir);

        // 开始下载
        if(\base\FileUtil::DownloadFile($new_dir.'.zip', $config['base']['name'].'_v'.$config['base']['version'].'.zip'))
        {
            // 删除文件
            @unlink($new_dir.'.zip');

            // 插件事件回调
            PluginsService::PluginsEventCall($plugins, 'Download', $params);
        } else {
            return DataReturn('下载失败', -100);
        }
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
            return DataReturn('插件标识为空', -1);
        }

        // 应用是否存在
        if(!self::PluginsExist($params['plugins_value']))
        {
            return DataReturn('应用不存在['.$params['plugins_value'].']、请先安装', -1);
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

        return DataReturn('更新成功');
    }

    /**
     * 排序保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-05
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SortSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'data',
                'error_msg'         => '没有可保存的插件数据',
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
                foreach($data as $k=>$v)
                {
                    $upd_data = [
                        'sort'      => intval($k),
                        'add_time'  => time(),
                    ];
                    if(Db::name('Plugins')->where(['id'=>intval($v)])->update($upd_data) === false)
                    {
                        throw new \Exception('操作失败');
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
                return DataReturn('操作成功', 0);
            } catch(\Exception $e) {
                Db::rollback();
                return DataReturn($e->getMessage(), -1);
            }
        }
        return DataReturn('插件排序数据有误', -1);
    }
}
?>