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
use app\service\BrandService;
use app\service\BrandCategoryService;

/**
 * 品牌动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-19
 * @desc    description
 */
class Brand
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_enable',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/brand/index'),
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/brand/delete'),
                'delete_key'    => 'ids',
                'detail_title'  => '基础信息',
            ],
            // 表单配置
            'form' => [
                [
                    'view_type'         => 'checkbox',
                    'is_checked'        => 0,
                    'checked_text'      => '反选',
                    'not_checked_text'  => '全选',
                    'align'             => 'center',
                    'width'             => 80,
                ],
                [
                    'label'         => '名称',
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '描述',
                    'view_type'     => 'field',
                    'view_key'      => 'describe',
                    'grid_size'     => 'lg',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => 'LOGO',
                    'view_type'     => 'module',
                    'view_key'      => 'brand/module/logo',
                ],
                [
                    'label'         => '官网地址',
                    'view_type'     => 'module',
                    'view_key'      => 'brand/module/url',
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'website_url',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '品牌分类',
                    'view_type'     => 'field',
                    'view_key'      => 'brand_category_text',
                    'search_config' => [
                        'form_type'             => 'select',
                        'form_name'             => 'id',
                        'where_type'            => 'in',
                        'data'                  => $this->BrandCategoryList(),
                        'data_key'              => 'id',
                        'data_name'             => 'name',
                        'is_multiple'           => 1,
                        'where_value_custom'    => 'WhereValueBrandCategory',
                    ],
                ],
                [
                    'label'         => '是否启用',
                    'view_type'     => 'status',
                    'view_key'      => 'is_enable',
                    'post_url'      => MyUrl('admin/brand/statusupdate'),
                    'is_form_su'    => 1,
                    'align'         => 'center',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_is_enable_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '排序',
                    'view_type'     => 'field',
                    'view_key'      => 'sort',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '更新时间',
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'brand/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }

    /**
     * 品牌分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-19
     * @desc    description
     */
    public function BrandCategoryList()
    {
        $ret = BrandCategoryService::BrandCategoryList(['field'=>'id,name']);
        return isset($ret['data']) ? $ret['data'] : [];
    }

    /**
     * 品牌分类条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-03
     * @desc    description
     * @param   [array]           $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueBrandCategory($value, $params = [])
    {
        if(!empty($value))
        {
            // 是否为数组
            if(!is_array($value))
            {
                $value = [$value];
            }

            // 获取品牌 id
            $ids = Db::name('BrandCategoryJoin')->where(['brand_category_id'=>$value])->column('brand_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>