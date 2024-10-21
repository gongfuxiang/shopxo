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
namespace app\install\controller;

use think\facade\Db;
use app\service\SystemService;

/**
 * 安装程序
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-11-30
 * @desc    description
 */
class Index extends Common
{
    // 编码类型
    private $charset_type_list;

    // 安装日志上报
    private $behavior_obj;

    // 记录管理后台入口文件key
    private $admin_run_key = 'cache_install_system_admin_run_key';

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

        // 编码类型
        $this->charset_type_list = [
            'utf8mb4'   => [
                'charset'   => 'utf8mb4',
                'collate'   => 'utf8mb4_general_ci',
                'version'   => 5.6,
            ],
            'utf8'      => [
                'charset'   => 'utf8',
                'collate'   => 'utf8_general_ci',
                'version'   => 5.0,
            ],
        ];

        // 安装日志上报类库
        $this->behavior_obj = new \base\Behavior();
    }

    /**
     * 是否已安装
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    private function IsInstall()
    {
        if(file_exists(ROOT.'config/database.php'))
        {
            return DataReturn('你已经安装过该系统，重新安装需要先删除 ./config/database.php 文件', -1);
        }
        return DataReturn('success', 0);
    }

    /**
     * 协议
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    public function Index()
    {
        $ret = $this->IsInstall();
        if($ret['code'] != 0)
        {
            exit($ret['msg']);
        }
        $this->behavior_obj->ReportInstallLog(['msg'=>'协议阅读']);
        return MyView();
    }

    /**
     * 检查
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    public function Check()
    {
        $ret = $this->IsInstall();
        if($ret['code'] != 0)
        {
            exit($ret['msg']);
        }
        $this->behavior_obj->ReportInstallLog(['msg'=>'环境检测']);
        return MyView();
    }

    /**
     * 创建数据库
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    public function Create()
    {
        $ret = $this->IsInstall();
        if($ret['code'] != 0)
        {
            exit($ret['msg']);
        }
        $this->behavior_obj->ReportInstallLog(['msg'=>'数据信息填写']);

        MyViewAssign('charset_type_list' , $this->charset_type_list);
        return MyView();
    }

    /**
     * 完成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    public function Successful()
    {
        // 安装验证
        SystemService::SystemInstallCheck();

        // 管理入口地址
        MyViewAssign('admin_run', MySession($this->admin_run_key));
        return MyView();
    }

    /**
     * 确认、安装应用模块创建数据库配置文件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    public function Confirm()
    {
        // 是否ajax
        if(!IS_AJAX)
        {
            MyViewAssign('msg', '非法访问');
            return MyView('public/error');
        }

        // 参数
        $params = $this->RequestParams();
        $ret = $this->ParamsCheck($params);
        if($ret['code'] != 0)
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'参数校验['.$ret['msg'].']']);
            return $ret;
        }

        // 配置文件校验
        $ret = $this->IsInstall();
        if($ret['code'] != 0)
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'Confirm():'.$ret['msg']]);
            return $ret;
        }

        // 安装应用数据库配置文件生成
        return $this->CreateDbConfig(APP_PATH.'install/config', $params);
    }

    /**
     * 安装
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     */
    public function Add()
    {
        // 是否ajax
        if(!IS_AJAX)
        {
            MyViewAssign('msg', '非法访问');
            return MyView('public/error');
        }

        // 配置文件校验
        $ret = $this->IsInstall();
        if($ret['code'] != 0)
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'Add():'.$ret['msg']]);
            return $ret;
        }

        // 开始安装
        $params = $this->RequestParams();
        $db = $this->DbObj($params);
        if(!is_object($db))
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'数据库连接失败']);
            return DataReturn('数据库连接失败', -1);
        }

        // mysql版本
        $ret = $this->IsVersion($db, $params['DB_CHARSET']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 创建数据表
        $ret = $this->CreateTable($db, $params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 生成数据库配置文件
        $ret = $this->CreateDbConfig(ROOT.'config', $params);
        if($ret['code'] == 0)
        {
            $ret['msg'] = '安装成功';
            $this->behavior_obj->ReportInstallLog(['msg'=>'安装成功']);
        }

        // 管理入口文件处理
        return $this->AdminRunFileHandle();
    }

    /**
     * 请求参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-09-23
     * @desc    description
     */
    public function RequestParams()
    {
        $params = input('post.');
        if(!empty($params) && is_array($params))
        {
            // 需要去除的特殊字符
            $search = ['<?php', '<?', '?>', '\'', '"', '&quot;', '\\', 'eval(', '&amp;', '&lt;', '&gt;'];
            foreach($params as &$v)
            {
                // 去除的特殊字符
                if(!is_array($v))
                {
                    $v = str_replace($search, '', $v);
                }
            }
        }
        return $params;
    }

    /**
     * 管理入口文件处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [array]          $params    [输入参数]
     */
    private function AdminRunFileHandle($params = [])
    {
        // 处理文件名称修改
        $admin_filename_new = 'admin'.strtolower(RandomString(6)).'.php';
        $arr = [ROOT, ROOT.'public'.DS];
        foreach($arr as $v)
        {
            if(file_exists($v.'admin.php'))
            {
                \base\FileUtil::MoveFile($v.'admin.php', $v.$admin_filename_new);
            }
        }

        // 记录管理地址
        MySession($this->admin_run_key, __MY_URL__.$admin_filename_new.'?s=index/index.html');

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 生成配置文件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [string]         $dir       [目录地址]
     * @param   [array]          $params    [输入参数]
     */
    private function CreateDbConfig($dir, $params = [])
    {
        $db_str=<<<php
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

// +----------------------------------------------------------------------
// | 数据库配置
// +----------------------------------------------------------------------
return [
    // 默认使用的数据库连接配置
    'default'         => 'mysql',

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'            => '{$params['DB_TYPE']}',
            // 服务器地址
            'hostname'        => '{$params['DB_HOST']}',
            // 数据库名
            'database'        => '{$params['DB_NAME']}',
            // 用户名
            'username'        => '{$params['DB_USER']}',
            // 密码
            'password'        => '{$params['DB_PWD']}',
            // 端口
            'hostport'        => '{$params['DB_PORT']}',
            // 数据库连接参数
            'params'          => [
                \PDO::ATTR_CASE => \PDO::CASE_LOWER,
                \PDO::ATTR_EMULATE_PREPARES => true,
            ],
            // 数据库编码默认采用utf8mb4
            'charset'         => '{$params['DB_CHARSET']}',
            // 数据库表前缀
            'prefix'          => '{$params['DB_PREFIX']}',

            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'          => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'     => false,
            // 读写分离后 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql'     => false,
            // 开启字段缓存
            'fields_cache'    => false,
        ]
    ]
];
?>
php;
        if(@file_put_contents($dir.'/database.php', $db_str) === false)
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'配置文件创建失败['.$dir.']']);
            return DataReturn('配置文件创建失败', -1);
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [object]          $db      [db对象]
     * @param   [array]           $params  [输入参数]
     */
    private function CreateTable($db, $params)
    {
        if(!file_exists(ROOT.'config/shopxo.sql'))
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'数据库sql文件不存在']);
            return DataReturn('数据库sql文件不存在', -1);
        }

        // sql文件
        $sql = file_get_contents(ROOT.'config/shopxo.sql');

        // 替换表前缀
        if($params['DB_PREFIX'] != 'sxo_')
        {
            $sql = str_replace("`sxo_", " `{$params['DB_PREFIX']}", $sql);
        }

        // 编码替换处理
        $charset = $this->charset_type_list[$params['DB_CHARSET']];
        $charset_old = $this->charset_type_list[($charset['charset'] == 'utf8') ? 'utf8mb4' : 'utf8'];
        // 编码替换操作
        $sql = str_replace("SET NAMES {$charset_old['charset']};", "SET NAMES {$charset['charset']};", $sql);
        $sql = str_replace("SET {$charset_old['charset']} ", "SET {$charset['charset']} ", $sql);
        $sql = str_replace("COLLATE {$charset_old['collate']} ", "COLLATE {$charset['collate']} ", $sql);

        $sql = str_replace(["SET = {$charset_old['charset']} ", "SET={$charset_old['charset']} "], "SET={$charset['charset']} ", $sql);
        $sql = str_replace(["COLLATE = {$charset_old['collate']} ", "COLLATE={$charset_old['collate']} "], "COLLATE={$charset['collate']} ", $sql);

        $sql = str_replace(["CHARSET = {$charset_old['charset']} ", "CHARSET={$charset_old['charset']} "], "CHARSET={$charset['charset']} ", $sql);
        $sql = str_replace($charset_old['collate'], $charset['collate'], $sql);

        // 转为数组
        $sql_all = preg_split("/;[\r\n]+/", $sql);

        $success = 0;
        $failure = 0;
        foreach($sql_all as $v)
        {
            if (!empty($v))
            {
                if($db->execute($v) !== false)
                {
                    $success++;
                } else {
                    $failure++;
                }               
            }
        }

        $result = [
            'success'   => $success,
            'failure'   => $failure,
        ];
        $this->behavior_obj->ReportInstallLog(['msg'=>'sql运行 成功['.$success.']条, 失败['.$failure.']条']);
        if($failure > 0)
        {
            return DataReturn('sql运行失败['.$failure.']条', -1);
        }

        // 更新管理员账号密码
        $login_salt = GetNumberCode(6);
        $login_pwd = LoginPwdEncryption($params['ADMIN_PWD'], $login_salt);
        $db->execute('UPDATE `'.$params['DB_PREFIX'].'admin` SET `username`="'.$params['ADMIN_USERNAME'].'", login_pwd="'.$login_pwd.'", `login_salt`="'.$login_salt.'" WHERE `id`=1');

        // 更新加密串
        $db->execute('UPDATE `'.$params['DB_PREFIX'].'config` SET `value`="'.md5(time().rand(100, 99999)).'" WHERE `only_tag`="common_data_encryption_secret"');

        return DataReturn('success', 0, $result);
    }

    /**
     * 数据库版本校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [object]          $db           [db对象]
     * @param   [string]          $db_charset   [数据库编码]
     */
    private function IsVersion($db, $db_charset)
    {
        $data = $db->query('SELECT VERSION() AS `version`');
        if(empty($data[0]['version']))
        {
            $this->behavior_obj->ReportInstallLog(['msg'=>'查询数据库版本失败']);
            return DataReturn('查询数据库版本失败', -1);
        } else {
            $mysql_version = str_replace('-log', '', $data[0]['version']);
            if($mysql_version < $this->charset_type_list[$db_charset]['version'])
            {
                $msg = '数据库版本过低、需要>='.$this->charset_type_list[$db_charset]['version'].'、当前'.$mysql_version;
                $this->behavior_obj->ReportInstallLog(['msg'=>$msg, 'mysql_version'=>$mysql_version]);
                return DataReturn($msg, -1);
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 获取数据库操作对象
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   array           $params  [输入参数]
     * @param   string          $db_name [数据库名称]
     */
    private function DbObj($params = [], $db_name = '')
    {
        return Db::connect('mysql');
    }

    /**
     * 参数校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private function ParamsCheck($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_TYPE',
                'error_msg'         => '请选择数据库类型',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'DB_CHARSET',
                'checked_data'      => array_column($this->charset_type_list, 'charset'),
                'error_msg'         => '请选择数据编码',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_HOST',
                'error_msg'         => '请填写数据库服务器地址',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_PORT',
                'error_msg'         => '请填写数据库端口',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_NAME',
                'error_msg'         => '请填写数据库名',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_USER',
                'error_msg'         => '请填写数据库用户名',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_PWD',
                'error_msg'         => '请填写数据库密码',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'DB_PREFIX',
                'error_msg'         => '请填写数据表前缀',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ADMIN_USERNAME',
                'error_msg'         => '请填写管理员账号',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ADMIN_PWD',
                'error_msg'         => '请填写管理员密码',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        return DataReturn('success', 0);
    }
}
?>