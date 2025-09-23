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
use app\module\FormInputModule;
use app\service\ResourcesService;
use app\service\FormInputService;

/**
 * form表单数据服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class FormInputDataService
{
    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-06-18
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function FormInputDataListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            $forminput_ids = array_column($data, 'forminput_id');
            $form_input_data = empty($forminput_ids) ? [] : Db::name('FormInput')->where(['id'=>$forminput_ids])->column('name', 'id');
            foreach($data as &$v)
            {
                // 表单名称
                $v['forminput_name'] = (!empty($form_input_data) && array_key_exists($v['forminput_id'], $form_input_data)) ? $form_input_data[$v['forminput_id']] : '';

                // 表单数据文本
                $v['form_data'] = empty($v['form_data']) ? '' : FormInputModule::FormInputDataViewHandle($v['form_data']);
                // 展示文本信息
                $v['form_data_text'] = '';
                if(!empty($v['form_data']))
                {
                    $temp_form_data_value = array_filter(array_map(function($item)
                        {
                            if(!empty($item['value']) && !empty($item['key']) && $item['key'] != 'rich-text')
                            {
                                return empty($item['value_text']) ? (is_array($item['value']) ? '' : $item['value']) : $item['value_text'];
                            }
                        }, $v['form_data']));
                    if(!empty($temp_form_data_value))
                    {
                        $v['form_data_text'] = implode('; ', $temp_form_data_value);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 表单数据保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-06-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputDataSave($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'forminput_id',
                'error_msg'         => '表单id为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 表单数据
        $form_input = FormInputService::FormInputData($params);
        if(empty($form_input))
        {
            return DataReturn('无表单数据', -1);
        }
        if(empty($form_input['config']))
        {
            return DataReturn('表单无配置数据', -1);
        }

        // 表单数据处理
        $form_data = FormInputModule::FormInputDataWriteHandle($form_input['config'], $params);

        // 当前用户
        $user_id = empty($params['user']) ? 0 : $params['user']['id'];

        // 是否存在数据
        $info = (!empty($user_id) && !empty($params['id'])) ? Db::name('FormInputData')->where(['id'=>intval($params['id']), 'user_id'=>$user_id, 'forminput_id'=>$form_input['id']])->find() : [];

        // 添加或更新数据
        $data = [
            'forminput_id'  => $form_input['id'],
            'user_id'       => $user_id,
            'form_data'     => empty($form_data) ? '' : json_encode($form_data, JSON_UNESCAPED_UNICODE),
        ];
        if(empty($info))
        {
            $data['add_time'] = time();
            if(Db::name('FormInputData')->insertGetId($data) <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('FormInputData')->where(['id'=>$info['id']])->update($data) === false)
            {
                return DataReturn(MyLang('update_fail'), -100);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 表单数据删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function FormInputDataDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

        // 条件
        $where = [
            ['id', 'in', $params['ids']],
        ];
        if($user_type == 'user')
        {
            $where[] = ['user_id', '=', empty($params['user']) ? -1 : $params['user']['id']];
        }

        // 删除
        if(Db::name('FormInputData')->where($where)->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>