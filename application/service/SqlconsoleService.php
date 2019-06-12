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

/**
 * SQL控制台服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SqlconsoleService
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
                'error_msg'         => '执行SQL不能为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 表前缀，编码替换
        $sql = str_replace('{PREFIX}', config('database.prefix'), $params['sql']);
        $sql = str_replace('{CHARSET}', config('database.charset'), $sql);

        // 转为数组
        $sql_all = preg_split("/;[\r\n]+/", $sql);

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

        if($failure > 0)
        {
            return DataReturn('sql运行失败['.$failure.']条', -1);
        }

        return DataReturn('sql运行成功', 0, 'sql运行成功[success: '.$success.', failure: '.$failure.']');
    }
}
?>