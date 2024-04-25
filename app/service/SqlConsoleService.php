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

/**
 * SQL控制台服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SqlConsoleService
{
    /**
     * SQL执行
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Implement($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'sql',
                'error_msg'         => MyLang('common_service.sqlconsole.form_sql_message'),
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 表前缀，编码替换
        $config = MyConfig('database.connections.mysql');
        $sql = str_replace('{PREFIX}', $config['prefix'], $params['sql']);
        $sql = str_replace('{CHARSET}', $config['charset'], $sql);
        // 引号转换
        $sql = str_replace(['&#039;', '&quot;'], ["'", '"'], $sql);

        // 转为数组
        $sql_all = preg_split("/;[\r\n]+/", $sql);

        // 开始处理
        $success = 0;
        $failure = 0;
        foreach($sql_all as $v)
        {
            if (!empty($v))
            {
                if(Db::execute($v) !== false)
                {
                    $success++;
                } else {
                    $failure++;
                }               
            }
        }
        if($success == 0 && $failure > 0)
        {
            return DataReturn(MyLang('common_service.sqlconsole.implement_fail_tips', ['failure'=>$failure]), -1);
        }
        return DataReturn(MyLang('operate_success'), 0, MyLang('common_service.sqlconsole.implement_success_tips', ['success'=>$success, 'failure'=>$failure]));
    }
}
?>