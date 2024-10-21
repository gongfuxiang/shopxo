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
 * 错误日志数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-04
 * @desc    description
 */
class ErrorLogService
{
    /**
     * 错误日志添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-11-19
     * @desc    description
     * @param   [array]          $data         [日志数据]
     */
    public static function ErrorLogAdd($data)
    {
        if(Db::name('ErrorLog')->insertGetId($data))
        {
            return DataReturn(MyLang('insert_success'), 0, $data);
        }
        return DataReturn(MyLang('insert_fail'), -1);
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ErrorLogDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('ErrorLog')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 清空全部
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ErrorLogAllDelete($params = [])
    {
        $where = [
            ['id', '>', 0]
        ];
        if(Db::name('ErrorLog')->where($where)->delete() === false)
        {
            return DataReturn(MyLang('operate_fail'), -100);
        }
        return DataReturn(MyLang('operate_success'));
    }
}
?>