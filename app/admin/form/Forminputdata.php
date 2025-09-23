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
namespace app\admin\form;

use think\facade\Db;

/**
 * form表单数据动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-30
 * @desc    description
 */
class FormInputData
{
    // 基础条件
    public $condition_base = [];

    // url条件
    public $url_where = [];

    /**
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        // 指定表单
        if(!empty($params['fid']))
        {
            $fid = intval($params['fid']);
            $this->condition_base[] = ['forminput_id', '=', $fid];
            $this->url_where = ['fid'=>$fid];
        }
    }

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-30
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('forminputdata.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'is_delete'     => 1,
                'delete_key'    => 'ids',
                'search_url'    => MyUrl('admin/forminputdata/index', $this->url_where),
            ],
            // 表单配置
            'form' => [
                [
                    'view_type'         => 'checkbox',
                    'is_checked'        => 0,
                    'checked_text'      => MyLang('reverse_select_title'),
                    'not_checked_text'  => MyLang('select_all_title'),
                    'align'             => 'center',
                    'width'             => 80,
                ],
                [
                    'label'         => $lang['user'],
                    'view_type'     => 'module',
                    'view_key'      => 'lib/module/user',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'user_id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'SystemModuleUserWhereHandle',
                        'placeholder'           => $lang['user_placeholder'],
                    ],
                ],
                [
                    'label'              => $lang['forminput_name'],
                    'view_type'          => 'field',
                    'view_key'           => 'forminput_name',
                    'is_sort'            => 1,
                    'params_where_name'  => 'forminput_name',
                    'search_config'      => [
                        'form_type'             => 'input',
                        'form_name'             => 'forminput_id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'WhereFormInputInfo',
                    ],
                ],
                [
                    'label'              => $lang['form_data'],
                    'view_type'          => 'field',
                    'view_key'           => 'form_data_text',
                    'grid_size'          => 'lg',
                    'text_truncate'      => 2,
                    'is_sort'            => 1,
                    'params_where_name'  => 'form_data',
                    'search_config'      => [
                        'form_type'         => 'input',
                        'form_name'         => 'form_data',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['add_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => $lang['upd_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'forminputdata/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 120,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'            => 'FormInputData',
                'data_handle'           => 'FormInputDataService::FormInputDataListHandle',
                'detail_where'          => $this->condition_base,
                'is_handle_time_field'  => 1,
                'is_handle_user_field'  => 1,
            ],
        ];
    }

    /**
     * form表单信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereFormInputInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取关联的 id
            $ids = Db::name('FormInput')->where('name|describe', 'like', '%'.$value.'%')->column('id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>