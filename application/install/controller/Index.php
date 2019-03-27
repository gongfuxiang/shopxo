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
namespace app\install\controller;

use think\Db;

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
        // 是否已安装
        if(file_exists(ROOT.'config/database.php'))
        {
            exit('你已经安装过该系统，重新安装需要先删除 ./config/database.php 文件');
        }
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
        $this->IsInstall();
        new \base\Behavior(['msg'=>'协议阅读']);
        return $this->fetch();
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
        $this->IsInstall();
        new \base\Behavior(['msg'=>'环境检测']);
        return $this->fetch();
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
        $this->IsInstall();
        new \base\Behavior(['msg'=>'数据信息填写']);

        $this->assign('charset_type_list' , $this->charset_type_list);
        return $this->fetch();
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
        // 检测是否是新安装
        if(is_dir(ROOT.'config') && !file_exists(ROOT.'config/database.php'))
        {
            if(empty($_GET['s']) || stripos($_GET['s'], 'install') === false)
            {
                $url = __MY_URL__.'index.php?s=/install/index/index';
                exit(header('location:'.$url));
            }
        }
        return $this->fetch();
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
            die('非法访问');
        }

        // 参数
        $params = input('post.');
        $ret = $this->ParamsCheck($params);
        if($ret['code'] != 0)
        {
            new \base\Behavior(['msg'=>'参数校验['.$ret['msg'].']']);
            return $ret;
        }

        // 配置文件校验
        if(file_exists(ROOT.'config/database.php'))
        {
            new \base\Behavior(['msg'=>'你已经安装过该系统，重新安装需要先删除[./config/database.php 文件]']);
            return DataReturn('你已经安装过该系统，重新安装需要先删除[./config/database.php 文件]', -1);
        }

        // 开始安装
        $db = $this->DbObj($params);
        if(!is_object($db))
        {
            new \base\Behavior(['msg'=>'数据库连接失败']);
            return DataReturn('数据库连接失败', -1);
        }

        // mysql版本
        $ret = $this->IsVersion($db, $params['DB_CHARSET']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 检查数据表是否存在
        if(!$this->IsDbExist($db, $params['DB_NAME']))
        {
            if($this->DbNameCreate($db, $params['DB_NAME'], $params['DB_CHARSET']))
            {
                $db = $this->DbObj($params, $params['DB_NAME']);
            } else {
                new \base\Behavior(['msg'=>'数据库创建失败']);
                return DataReturn('数据库创建失败', -1);
            }
        } else {
            $db = $this->DbObj($params, $params['DB_NAME']);
        }
        if(!is_object($db))
        {
            new \base\Behavior(['msg'=>'数据库连接失败']);
            return DataReturn('数据库连接失败', -1);
        }

        // 创建数据表
        $ret = $this->CreateTable($db, $params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 生成配置文件
        return $this->CreateConfig($params);
    }

    /**
     * 生成配置文件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private function CreateConfig($params = [])
    {
                // 配置文件信息处理
        $db_str=<<<php
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

// +----------------------------------------------------------------------
// | 数据库配置
// +----------------------------------------------------------------------
return [
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
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [
        \PDO::ATTR_CASE => \PDO::CASE_LOWER,
        \PDO::ATTR_EMULATE_PREPARES => true,
    ],
    // 数据库编码默认采用utf8mb4
    'charset'         => '{$params['DB_CHARSET']}',
    // 数据库表前缀
    'prefix'          => '{$params['DB_PREFIX']}',
    // 数据库调试模式
    'debug'           => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 自动读取主库数据
    'read_master'     => false,
    // 是否严格检查字段是否存在
    'fields_strict'   => true,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
    // Builder类
    'builder'         => '',
    // Query类
    'query'           => '\\think\\db\\Query',
    // 是否需要断线重连
    'break_reconnect' => false,
    // 断线标识字符串
    'break_match_str' => [],
];
?>
php;
        if(@file_put_contents(ROOT.'config/database.php', $db_str) === false)
        {
            new \base\Behavior(['msg'=>'配置文件创建失败']);
            return DataReturn('配置文件创建失败', -1);
        }

        new \base\Behavior(['msg'=>'安装成功']);
        return DataReturn('安装成功', 0);
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
            new \base\Behavior(['msg'=>'数据库sql文件不存在']);
            return DataReturn('数据库sql文件不存在', -1);
        }

        // sql文件
        $sql = file_get_contents(ROOT.'config/shopxo.sql');

        //替换表前缀
        $sql = str_replace("`s_", " `{$params['DB_PREFIX']}", $sql);

        // 编码替换,utf8mb4则做替换操作
        $charset = $this->charset_type_list[$params['DB_CHARSET']];
        if($charset['charset'] != 'utf8mb4')
        {
            $sql = str_replace('SET NAMES utf8mb4;', "SET NAMES {$charset['charset']};", $sql);
            $sql = str_replace('CHARSET=utf8mb4', "CHARSET={$charset['charset']}", $sql);
            $sql = str_replace('utf8mb4_general_ci', "{$charset['collate']}", $sql);
        }

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
        new \base\Behavior(['msg'=>'sql运行 成功['.$success.']条, 失败['.$failure.']条']);
        if($failure > 0)
        {
            return DataReturn('sql运行失败['.$failure.']条', -1);
        }

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
        $data = $db->query("select version() AS version");
        if(empty($data[0]['version']))
        {
            new \base\Behavior(['msg'=>'查询数据库版本失败']);
            return DataReturn('查询数据库版本失败', -1);
        } else {
            $mysql_version = str_replace('-log', '', $data[0]['version']);
            if($mysql_version < $this->charset_type_list[$db_charset]['version'])
            {
                $msg = '数据库版本过低、需要>='.$this->charset_type_list[$db_charset]['version'].'、当前'.$mysql_version;
                new \base\Behavior(['msg'=>$msg, 'mysql_version'=>$mysql_version]);
                return DataReturn($msg, -1);
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 数据库创建
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [object]          $db           [db对象]
     * @param   [string]          $db_name      [数据库名称]
     * @param   [string]          $db_charset   [数据库编码]
     */
    private function DbNameCreate($db, $db_name, $db_charset)
    {
        $sql = "CREATE DATABASE {$db_name} DEFAULT CHARSET {$this->charset_type_list[$db_charset]['charset']} COLLATE {$this->charset_type_list[$db_charset]['collate']}";
        if($db->execute($sql) !== false)
        {
            return $this->IsDbExist($db, $db_name);
        }
        return false;
    }

    /**
     * 检查数据库是否存在
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-28
     * @desc    description
     * @param   [object]          $db      [db对象]
     * @param   [string]          $db_name [数据库名称]
     */
    private function IsDbExist($db, $db_name)
    {
        $temp = $db->query("show databases like '{$db_name}'");
        return !empty($temp);
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
        return Db::connect([
            // 数据库类型
            'type'        => $params['DB_TYPE'],
            // 数据库连接DSN配置
            'dsn'         => '',
            // 服务器地址
            'hostname'    => $params['DB_HOST'],
            // 数据库名
            'database'    => $db_name,
            // 数据库用户名
            'username'    => $params['DB_USER'],
            // 数据库密码
            'password'    => $params['DB_PWD'],
            // 数据库连接端口
            'hostport'    => $params['DB_PORT'],
            // 数据库连接参数
            'params'      => [
                \PDO::ATTR_CASE => \PDO::CASE_LOWER,
                \PDO::ATTR_EMULATE_PREPARES => true,
            ],
            // 数据库编码默认采用utf8mb4
            'charset'     => $params['DB_CHARSET'],
            // 数据库表前缀
            'prefix'      => $params['DB_PREFIX'],
        ]);
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