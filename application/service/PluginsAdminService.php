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
namespace app\service;

use think\Db;
use app\service\ResourcesService;
use app\service\SqlconsoleService;

/**
 * 应用管理服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PluginsAdminService
{
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
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('Plugins')->where($where)->limit($m, $n)->order($order_by)->select();
        
        return DataReturn('处理成功', 0, self::PluginsDataHandle($data));
    }

    /**
     * 数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $data [数据]
     */
    private static function PluginsDataHandle($data)
    {
        $result = [];
        if(!empty($data))
        {
            foreach($data as $v)
            {
                $config = self::GetPluginsConfig($v['plugins']);
                if($config !== false)
                {
                    $base = $config['base'];
                    $result[] = [
                        'id'            => $v['id'],
                        'plugins'       => $v['plugins'],
                        'is_enable'     => $v['is_enable'],
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
                        'add_time_time' => date('Y-m-d H:i:s', $v['add_time']),
                        'add_time_date' => date('Y-m-d', $v['add_time']),
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * 总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function PluginsTotal($where = [])
    {
        return (int) Db::name('Plugins')->where($where)->count();
    }

    /**
     * 列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsListWhere($params = [])
    {
        $where = [];
        return $where;
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
        if(Db::name('Plugins')->where(['id'=>$params['id']])->update(['is_enable'=>intval($params['state']), 'upd_time'=>time()]))
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
        $tags_file = APP_PATH.'tags.php';
        if(!is_writable($tags_file))
        {
            return DataReturn('钩子配置文件没有操作权限'.'['.$tags_file.']', -3);
        }

        // 钩子容器
        $result = [];

        // 系统自带钩子处理
        if(file_exists($tags_file))
        {
            $tags = require $tags_file;
            if(!empty($tags) && is_array($tags))
            {
                $system_hook_list = [
                    'app_init',
                    'app_dispatch',
                    'app_begin',
                    'module_init',
                    'action_begin',
                    'view_filter',
                    'app_end',
                    'log_write',
                    'log_level',
                    'response_send',
                    'response_end'
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
        $data = Db::name('Plugins')->where(['is_enable'=>1])->column('plugins');
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
        $ret = @file_put_contents($tags_file, "<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// 应用行为扩展定义文件\nreturn ".var_export($result, true).";\n?>");
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
        
        // 获取应用标记
        $where = ['id'=>intval($params['id'])];
        $plugins = Db::name('Plugins')->where($where)->value('plugins');
        if(empty($plugins))
        {
           return DataReturn('应用不存在', -10); 
        }

        // 开启事务
        Db::startTrans();

        // 删除操作
        if(Db::name('Plugins')->where($where)->delete())
        {
            // 钩子部署
            $ret = self::PluginsHookDeployment();
            if($ret['code'] == 0)
            {
                // sql运行
                $uninstall_sql = APP_PATH.'plugins'.DS.$plugins.DS.'uninstall.sql';
                if(file_exists($uninstall_sql))
                {
                    SqlconsoleService::Implement(['sql'=>file_get_contents($uninstall_sql)]);
                }

                // 删除应用文件
                self::PluginsResourcesDelete($plugins);

                // 提交事务
                Db::commit();
                return DataReturn('删除成功');
            }
        } else {
            $ret = DataReturn('删除失败或资源不存在', -100);
        }

        // 事务回退
        Db::rollback();
        return $ret;
    }

    /**
     * 应用资源删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-13
     * @desc    description
     * @param   [string]          $plugins [唯一标记]
     */
    private static function PluginsResourcesDelete($plugins)
    {
        \base\FileUtil::UnlinkDir(APP_PATH.'plugins'.DS.$plugins);
        \base\FileUtil::UnlinkDir(APP_PATH.'plugins'.DS.'view'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images'.DS.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'upload'.DS.'images'.DS.'plugins_'.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'upload'.DS.'video'.DS.'plugins_'.$plugins);
        \base\FileUtil::UnlinkDir(ROOT.'public'.DS.'static'.DS.'upload'.DS.'file'.DS.'plugins_'.$plugins);
    }

    /**
     * 获取应用配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [string]          $plugins [应用名称]
     */
    private static function GetPluginsConfig($plugins)
    {
        $config_file = APP_PATH.'plugins'.DS.$plugins.DS.'config.json';
        if(file_exists($config_file))
        {
            return json_decode(file_get_contents($config_file), true);
        }
        return false;
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

        // 应用不存在则添加
        $ret = self::PluginsExistInsert($params, $plugins);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 应用目录不存在则创建
        $app_dir = APP_PATH.'plugins'.DS.$plugins;
        if(\base\FileUtil::CreateDir($app_dir) !== true)
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

        return DataReturn(empty($params['id']) ? '创建成功' : '更新成功', 0);
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
namespace app\plugins\\$plugins;

use think\Controller;

/**
 * {$params['name']} - 后台管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin extends Controller
{
    // 后台管理入口
    public function index(\$params = [])
    {
        // 数组组装
        \$this->assign('data', ['hello', 'world!']);
        \$this->assign('msg', 'hello world! admin');
        return \$this->fetch('../../../plugins/view/$plugins/admin/index');
    }
}
?>
php;

$hook=<<<php
<?php
namespace app\plugins\\$plugins;

use think\Controller;

/**
 * {$params['name']} - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    // 应用响应入口
    public function run(\$params = [])
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
namespace app\plugins\\$plugins;

use think\Controller;

/**
 * {$params['name']} - 前端独立页面入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    // 前端页面入口
    public function index(\$params = [])
    {
        // 数组组装
        \$this->assign('data', ['hello', 'world!']);
        \$this->assign('msg', 'hello world! index');
        return \$this->fetch('../../../plugins/view/$plugins/index/index');
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
        if(\base\FileUtil::CreateDir($app_static_css_dir) !== true)
        {
            return DataReturn('应用静态目录创建失败[css]', -10);
        }

        // 编辑模式下不生成后端文件
        if(empty($params['id']))
        {
            // 创建文件
            if(@file_put_contents($app_dir.DS.'Admin.php', $admin) === false)
            {
                return DataReturn('应用文件创建失败[admin]', -11);
            }
            if(@file_put_contents($app_dir.DS.'Hook.php', $hook) === false)
            {
                return DataReturn('应用文件创建失败[hook]', -11);
            }

            // 应用后台视图目录不存在则创建
            $app_view_admin_dir = APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'admin';
            if(\base\FileUtil::CreateDir($app_view_admin_dir) !== true)
            {
                return DataReturn('应用视图目录创建失败[admin]', -10);
            }
            if(@file_put_contents($app_view_admin_dir.DS.'index.html', $admin_view) === false)
            {
                return DataReturn('应用视图文件创建失败[admin-view]', -11);
            }

            // css创建
            if(@file_put_contents($app_static_css_dir.DS.'admin.css', $admin_css) === false)
            {
                return DataReturn('应用静态文件创建失败[admin-css]', -11);
            }
        }


        // 是否有前端页面
        if(isset($params['is_home']) && $params['is_home'] == 1)
        {
            // 创建文件
            if(@file_put_contents($app_dir.DS.'Index.php', $index) === false)
            {
                return DataReturn('应用文件创建失败[index]', -11);
            }

            // 应用前端视图目录不存在则创建
            $app_view_index_dir = APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'index';
            if(\base\FileUtil::CreateDir($app_view_index_dir) !== true)
            {
                return DataReturn('应用视图目录创建失败[index]', -10);
            }
            if(@file_put_contents($app_view_index_dir.DS.'index.html', $index_view) === false)
            {
                return DataReturn('应用视图文件创建失败[index-view]', -11);
            }

            // css创建
            if(@file_put_contents($app_static_css_dir.DS.'index.css', $index_css) === false)
            {
                return DataReturn('应用静态文件创建失败[index-css]', -11);
            }

        // 没有独立前端页面则删除文件
        } else {
            \base\FileUtil::UnlinkFile($app_dir.DS.'Index.php');
            \base\FileUtil::UnlinkDir(APP_PATH.'plugins'.DS.'view'.DS.trim($params['plugins']).DS.'index');
            \base\FileUtil::UnlinkFile($app_static_css_dir.DS.'index.css');
        }

        return DataReturn('创建成功', 0);
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
        $hook = empty($config['hook']) ? [] : $config['hook'];

        // 配置信息组装
        $data = [
            // 基础信息
            'base'  => [
                'plugins'           => $plugins,
                'name'              => $params['name'],
                'logo'              => $params['logo'],
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

        // 文件存在是否有权限
        $config_file = $app_dir.DS.'config.json';
        if(file_exists($config_file) && !is_writable($config_file))
        {
            return DataReturn('应用配置文件没有操作权限'.'['.$config_file.']', -3);
        }

        // 创建配置文件
        if(@file_put_contents($config_file, JsonFormat($data)) === false)
        {
            return DataReturn('应用配置文件创建失败', -10);
        }

        return DataReturn('创建成功', 0);
    }

    /**
     * 应用添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]           $params    [输入参数]
     * @param   [string]          $plugins   [应用唯一标记]
     */
    private static function PluginsExistInsert($params, $plugins)
    {
        $temp_plugins = Db::name('Plugins')->where(['plugins'=>$plugins])->value('plugins');
        if(empty($temp_plugins))
        {
           if(Db::name('Plugins')->insertGetId(['plugins'=>$plugins, 'is_enable'=>0, 'add_time'=>time()]) <= 0)
            {
                return DataReturn('应用添加失败', -1);
            } 
        } else {
            if(empty($params['id']) && $temp_plugins == $plugins)
            {
                return DataReturn('应用名称已存在['.$plugins.']', -1);
            }
        }
        return DataReturn('添加成功', 0);
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
        $type = array('application/zip', 'application/octet-stream', 'application/x-zip-compressed');
        if(!in_array($_FILES['file']['type'], $type))
        {
            return DataReturn('文件格式有误，请上传zip压缩包', -2);
        }

        // 权限校验
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 资源目录
        $dir_list = [
            '_controller_'      => APP_PATH.'plugins'.DS,
            '_view_'            => APP_PATH.'plugins'.DS.'view'.DS,
            '_css_'             => ROOT.'public'.DS.'static'.DS.'plugins'.DS.'css'.DS,
            '_js_'              => ROOT.'public'.DS.'static'.DS.'plugins'.DS.'js'.DS,
            '_images_'          => ROOT.'public'.DS.'static'.DS.'plugins'.DS.'images'.DS,
            '_uploadfile_'      => ROOT.'public'.DS.'static'.DS.'upload'.DS.'file'.DS,
            '_uploadimages_'    => ROOT.'public'.DS.'static'.DS.'upload'.DS.'images'.DS,
            '_uploadvideo_'     => ROOT.'public'.DS.'static'.DS.'upload'.DS.'video'.DS,
        ];

        // 包名
        $plugins_name = '';

        // 开始解压文件
        $resource = zip_open($_FILES['file']['tmp_name']);
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
                if(empty($plugins_name))
                {
                    // 应用不存在则添加
                    $plugins_name = substr($file, 0, strpos($file, '/'));
                    $ret = self::PluginsExistInsert([], $plugins_name);
                    if($ret['code'] != 0)
                    {
                        zip_entry_close($temp_resource);
                        return $ret;
                    }
                }

                // 去除包名
                $file = substr($file, strpos($file, '/')+1);

                // 排除临时文件和临时目录
                if(strpos($file, '/.') === false && strpos($file, '__') === false)
                {
                    // 文件包对应系统所在目录
                    $is_has_find = false;
                    foreach($dir_list as $dir_key=>$dir_value)
                    {
                        if(strpos($file, $dir_key) !== false)
                        {
                            $file = str_replace($dir_key.'/', '', $dir_value.$file);
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

        // sql运行
        $install_sql = APP_PATH.'plugins'.DS.$plugins_name.DS.'install.sql';
        if(!empty($plugins_name) && file_exists($install_sql))
        {
            // 开始处理
            $ret = SqlconsoleService::Implement(['sql'=>file_get_contents($install_sql)]);
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        return DataReturn('安装成功');
    }

    /**
     * 应用打包
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     * @param    [array]          $params [输入参数]
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
        
        // 获取应用标记
        $where = ['id'=>intval($params['id'])];
        $plugins = Db::name('Plugins')->where($where)->value('plugins');
        if(empty($plugins))
        {
           return DataReturn('应用不存在', -10); 
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

        // 生成压缩包
        $zip = new \base\ZipFolder();
        if(!$zip->zip($new_dir.'.zip', $new_dir))
        {
            return DataReturn('压缩包生成失败', -100);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($new_dir);

        // 开始下载
        if(\base\FileUtil::DownloadFile($new_dir.'.zip', $plugins.'.zip'))
        {
            @unlink($new_dir.'.zip');
        } else {
            return DataReturn('下载失败', -100);
        }
    }
}
?>