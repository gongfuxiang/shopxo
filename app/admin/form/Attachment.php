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

use app\service\AttachmentCategoryService;

/**
 * 附件动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-16
 * @desc    description
 */
class Attachment
{
    // 基础条件
    public $condition_base = [];

    // 附件分类
    public $category_list;

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
        // 附件分类
        $res = AttachmentCategoryService::AttachmentCategoryList(['field'=>'id,name']);
        $this->category_list = empty($res) ? [] : array_column($res, 'name', 'id');
    }

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('attachment.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'is_delete'     => 1,
                'is_middle'     => 0,
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
                    'label'              => $lang['category_name'],
                    'view_type'          => 'field',
                    'view_key'           => 'category_name',
                    'is_sort'            => 1,
                    'width'              => 160,
                    'params_where_name'  => 'category_id',
                    'search_config'      => [
                        'form_type'         => 'select',
                        'form_name'         => 'category_id',
                        'where_type'        => 'in',
                        'data'              => $this->category_list,
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'              => $lang['type_name'],
                    'view_type'          => 'field',
                    'view_key'           => 'type_name',
                    'is_sort'            => 1,
                    'width'              => 120,
                    'params_where_name'  => 'type',
                    'search_config'      => [
                        'form_type'         => 'select',
                        'form_name'         => 'type',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_attachment_type_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['info'],
                    'view_type'     => 'module',
                    'view_key'      => 'attachment/module/info',
                    'width'         => 80,
                ],
                [
                    'label'              => $lang['original'],
                    'view_type'          => 'field',
                    'view_key'           => 'original',
                    'grid_size'          => 'sm',
                    'is_sort'            => 1,
                    'params_where_name'  => 'keywords',
                    'search_config'      => [
                        'form_type'         => 'input',
                        'form_name'         => 'original|title',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['title'],
                    'view_type'     => 'field',
                    'view_key'      => 'title',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['size'],
                    'view_type'     => 'field',
                    'view_key'      => 'size',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['ext'],
                    'view_type'     => 'field',
                    'view_key'      => 'ext',
                    'is_sort'       => 1,
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => $lang['url'],
                    'view_type'     => 'field',
                    'view_key'      => 'url',
                    'is_copy'       => 1,
                    'is_sort'       => 1,
                    'grid_size'     => 'xl',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['hash'],
                    'view_type'     => 'field',
                    'view_key'      => 'hash',
                    'is_copy'       => 1,
                    'is_sort'       => 1,
                    'width'         => 270,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
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
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'attachment/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'                 => 'Attachment',
                'is_page'                    => 1,
                'is_handle_annex_size_unit'  => 1,
                'is_handle_annex_field'      => 1,
                'handle_annex_fields'        => ['url'],
                'is_handle_time_field'       => 1,
                'is_fixed_name_field'        => 1,
                'fixed_name_data'            => [
                    'category_id'   => [
                        'data'   => $this->category_list,
                        'field'  => 'category_name',
                    ],
                    'type'          => [
                        'data'   => MyConst('common_attachment_type_list'),
                    ],
                ],
            ],
        ];
    }
}
?>