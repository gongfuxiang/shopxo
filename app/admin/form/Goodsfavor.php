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
 * 商品收藏管理动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-30
 * @desc    description
 */
class GoodsFavor
{
    // 基础条件
    public $condition_base = [];

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
        $lang = MyLang('goodsfavor.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/goodsfavor/delete'),
                'delete_key'    => 'ids',
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
                    'label'         => $lang['user'],
                    'view_type'     => 'module',
                    'view_key'      => 'lib/module/user',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'f.user_id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'SystemModuleUserWhereHandle',
                        'placeholder'           => $lang['user_placeholder'],
                    ],
                ],
                [
                    'label'         => $lang['goods'],
                    'view_type'     => 'module',
                    'view_key'      => 'goodsfavor/module/goods',
                    'grid_size'     => 'lg',
                    'is_sort'       => 1,
                    'sort_field'    => 'g.title',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords',
                        'where_type'        => 'like',
                        'placeholder'       => $lang['goods_placeholder'],
                    ],
                ],
                [
                    'label'         => $lang['price'],
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'g.min_price',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => $lang['original_price'],
                    'view_type'     => 'field',
                    'view_key'      => 'original_price',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'g.min_original_price',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => $lang['add_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'f.add_time',
                    ],
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'goodsfavor/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_obj'     => Db::name('GoodsFavor')->alias('f')->join('goods g', 'g.id=f.goods_id'),
                'select_field'  => 'f.*, g.title, g.original_price, g.price, g.min_price, g.images, g.inventory_unit',
                'order_by'      => 'f.id desc',
                'detail_dkey'   => 'f.id',
                'data_handle'   => 'GoodsFavorService::GoodsFavorListHandle',
                'data_params'   => [
                    'is_public'     => 0,
                    'user_type'     => 'admin',
                ],
            ],
        ];
    }
}
?>