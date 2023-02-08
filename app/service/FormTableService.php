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
use app\service\AdminService;

/**
 * 动态表格服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class FormTableService
{
    /**
     * 用户字段选择保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FieldsSelectSave($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => MyLang('user_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'user_type',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('user_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'md5_key',
                'error_msg'         => MyLang('common_service.formtable.save_data_key_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'fields',
                'error_msg'         => MyLang('common_service.formtable.save_fields_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'user_id'   => intval($params['user_id']),
            'user_type' => intval($params['user_type']),
            'md5_key'   => $params['md5_key'],
            'fields'    => empty($params['fields']) ? '' : (is_array($params['fields']) ? json_encode($params['fields'], JSON_UNESCAPED_UNICODE) : $params['fields']),
            'add_time'  => time(),
            'upd_time'  => time(),
        ];
        if(Db::name('FormTableUserFields')->insertGetId($data) <= 0)
        {
            return DataReturn(MyLang('operate_fail'), -100);
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 用户字段选择重置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FieldsSelectReset($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => MyLang('user_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'user_type',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('user_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'md5_key',
                'error_msg'         => MyLang('common_service.formtable.save_data_key_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据删除
        $where = [
            'user_id'   => intval($params['user_id']),
            'user_type' => intval($params['user_type']),
            'md5_key'   => $params['md5_key'],
        ];
        if(Db::name('FormTableUserFields')->where($where)->delete() === false)
        {
            return DataReturn(MyLang('operate_fail'), -100);
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 获取用户选择的字段数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FieldsSelectData($params = [])
    {
        // 模块
        $module_name = RequestModule();

        // 当前用户
        if($module_name == 'admin')
        {
            $admin = AdminService::LoginInfo();
            $user_id = empty($admin['id']) ? 0 : $admin['id'];
            $user_type = 0;
        } else {
            $user = UserService::LoginUserInfo();
            $user_id = empty($user['id']) ? 0 : $user['id'];
            $user_type = 1;
        }
        if(empty($user_id))
        {
            return DataReturn(MyLang('user_info_incorrect_tips'), -1);
        }
        if(empty($params['md5_key']))
        {
            return DataReturn('数据key有误', -1);
        }

        // 获取数据
        $where = [
            'user_id'   => $user_id,
            'user_type' => $user_type,
            'md5_key'   => $params['md5_key'],
        ];
        // 获取数据
        $data = Db::name('FormTableUserFields')->where($where)->order('id desc')->find();
        $data = empty($data['fields']) ? [] : json_decode($data['fields'], true);
        return DataReturn(MyLang('handle_success'), 0, $data);
    }
}
?>