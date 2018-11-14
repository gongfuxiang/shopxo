<?php

/**
 * 安装向导
 */
// 编码
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set("PRC");

// 检测是否安装过
if(file_exists('./install.lock'))
{
    exit('你已经安装过该系统，重新安装需要先删除./Install/install.lock 文件');
}

// 行为类库
require './behavior.class.php';

// 参数
$c = isset($_GET['c']) ? trim($_GET['c']) : '';

// 同意协议页面
if($c == 'agreement' || empty($c))
{
    new behavior(array('msg'=>'协议阅读'));
    exit(require './agreement.html');
}
// 环境检测页面
if($c == 'test')
{
    new behavior(array('msg'=>'环境检测'));
    exit(require './test.html');
}
// 创建数据库页面
if($c == 'create')
{
    new behavior(array('msg'=>'数据信息填写'));
    exit(require './create.html');
}
// 安装成功页面
if($c == 'success')
{
    // mysql版本信息
    $mysql_ver = '';

    // 判断是否为post
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $data = $_POST;
        // 连接数据库
        $link = @mysqli_connect($data['DB_HOST'], $data['DB_USER'], $data['DB_PWD'], null, $data['DB_PORT']);

        // 获取错误信息
        if (!$link) {
            // 转义防止和alert中的引号冲突
            $error = addslashes(mysqli_connect_error().'['.mysqli_connect_errno().']');

            // 数据库连接失败上报
            new behavior(array('msg'=>'数据库连接失败['.$error.']'));
            die("<script>alert('数据库链接失败:$error');history.go(-1)</script>");
        }

        // 设置字符集
        mysqli_query($link, "SET NAMES 'utf8mb4'");

        // 数据库版本校验
        $mysql_ver = mysqli_get_server_info($link);
        if($mysql_ver < 5.0)
        {
            // 数据库版本过低上报
            new behavior(array('msg'=>'数据库版本过低['.$mysql_ver.']', 'mysql_version'=>$mysql_ver));
            die("<script>alert('请将您的mysql升级到5.0以上');history.go(-1)</script>");
        }

        // 创建数据库并选中
        if(!mysqli_select_db($link, $data['DB_NAME'])){
            $create_sql = 'CREATE DATABASE IF NOT EXISTS '.$data['DB_NAME'].' DEFAULT CHARACTER SET utf8mb4;';
            if(!mysqli_query($link, $create_sql))
            {
                // 数据库创建失败上报
                new behavior(array('msg'=>'创建数据库失败', 'mysql_version'=>$mysql_ver));
                die("<script>alert('创建数据库失败');history.go(-1)</script>");
            }
            mysqli_select_db($link, $data['DB_NAME']);
        }

        // 导入sql数据并创建表
        $sql_array = preg_split("/;[\r\n]+/", str_replace('`s_', '`'.$data['DB_PREFIX'], file_get_contents('./shopxo.sql')));
        $success = 0;
        $failure = 0;
        foreach ($sql_array as $k => $v) {
            if (!empty($v)) {
                if(mysqli_query($link, $v))
                {
                    $success++;
                } else {
                    $failure++;
                }
            }
        }
        mysqli_close($link);

        // 数据表创建上报
        new behavior(array('msg'=>'运行sql[成功'.$success.', 失败'.$failure.']', 'mysql_version'=>$mysql_ver));

        // 配置文件信息处理
        $db_str=<<<php
<?php

/**
 * 数据库配置信息-自动安装生成
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2018-11-11T21:51:08+0800
 */
return array(
    // 数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '{$data['DB_HOST']}', // 服务器地址
    'DB_NAME'   => '{$data['DB_NAME']}', // 数据库名
    'DB_USER'   => '{$data['DB_USER']}', // 用户名
    'DB_PWD'    => '{$data['DB_PWD']}', // 密码
    'DB_PORT'   => {$data['DB_PORT']}, // 端口
    'DB_PARAMS' =>  array(), // 数据库连接参数
    'DB_PREFIX' => '{$data['DB_PREFIX']}', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8mb4', // 字符集
    'DB_DEBUG'  =>  false, // 数据库调试模式 开启后可以记录SQL日志
);
?>
php;
        // 创建数据库链接配置文件,master,develop,test,debug 分别都更新，core模式没更改
        @file_put_contents('../Application/Common/Conf/master.php', $db_str);
        @file_put_contents('../Application/Common/Conf/develop.php', $db_str);
        @file_put_contents('../Application/Common/Conf/test.php', $db_str);
        @file_put_contents('../Application/Common/Conf/debug.php', $db_str);
        @touch('./install.lock');

        // 安装完成上报
        new behavior(array('msg'=>'安装完成', 'mysql_version'=>$mysql_ver));

        // 显示安装成功信息
        exit(require './success.html');
    }
}
exit('非法访问');
?>