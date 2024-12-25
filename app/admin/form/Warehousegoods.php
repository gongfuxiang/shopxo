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
use app\service\WarehouseService;
use app\service\RegionService;

/**
 * 仓库商品动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-12
 * @desc    description
 */
class WarehouseGoods
{
    // 基础条件
    public $condition_base = [];

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
        $lang = MyLang('warehousegoods.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_enable',
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
                    'label'             => $lang['goods'],
                    'view_type'         => 'module',
                    'view_key'          => 'warehousegoods/module/goods',
                    'grid_size'         => 'lg',
                    'is_sort'           => 1,
                    'sort_field'        => 'goods_id',
                    'params_where_name' => 'goods_id',
                    'search_config'     => [
                        'form_type'             => 'input',
                        'form_name'             => 'wg.id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'WhereGoodsInfo',
                        'placeholder'           => $lang['goods_placeholder'],
                    ],
                ],
                [
                    'label'             => $lang['warehouse_name'],
                    'view_type'         => 'field',
                    'view_key'          => 'warehouse_name',
                    'is_sort'           => 1,
                    'params_where_name' => 'warehouse_id',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'wg.warehouse_id',
                        'data'              => $this->WarehouseList(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'where_type'        => 'in',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'             => $lang['is_enable'],
                    'view_type'         => 'status',
                    'view_key'          => 'is_enable',
                    'post_url'          => MyUrl('admin/warehousegoods/statusupdate'),
                    'is_form_su'        => 1,
                    'align'             => 'center',
                    'is_sort'           => 1,
                    'params_where_name' => 'is_enable',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'wg.is_enable',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_is_text_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'             => $lang['inventory'],
                    'view_type'         => 'field',
                    'view_key'          => 'inventory',
                    'is_sort'           => 1,
                    'params_where_name' => 'inventory',
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'wg.inventory',
                    ],
                ],
                [
                    'label'             => $lang['spec_inventory'],
                    'view_type'         => 'module',
                    'view_key'          => 'warehousegoods/module/spec_inventory',
                    'is_sort'           => 1,
                    'grid_size'         => 'lg',
                    'is_detail'         => 0,
                    'params_where_name' => 'spec_inventory',
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'wgs.inventory',
                    ],
                ],
                [
                    'label'             => $lang['add_time'],
                    'view_type'         => 'field',
                    'view_key'          => 'add_time',
                    'is_sort'           => 1,
                    'params_where_name' => 'add_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'wg.add_time',
                    ],
                ],
                [
                    'label'         => $lang['upd_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'wg.upd_time',
                    ],
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'warehousegoods/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_obj'     => Db::name('WarehouseGoods')->alias('wg')->leftJoin('warehouse_goods_spec wgs', 'wg.id=wgs.warehouse_goods_id'),
                'select_field'  => 'wg.*',
                'order_by'      => 'wg.id desc',
                'group'         => 'wg.id',
                'distinct'      => 'wg.id',
                'detail_dkey'   => 'wg.id',
                'data_handle'   => 'WarehouseGoodsService::WarehouseGoodsListHandle',
            ],
        ];
    }

    /**
     * 获取仓库数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-12
     * @desc    description
     */
    public function WarehouseList()
    {
        $result = [];
        $ids = array_unique(Db::name('WarehouseGoods')->column('warehouse_id'));
        if(!empty($ids))
        {
            $ret = WarehouseService::WarehouseIdsAllList($ids);
            if($ret['code'] == 0)
            {
                $result = $ret['data'];
            }
        }
        return $result;
    }

    /**
     * 商品信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-12
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereGoodsInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取关联的商品 id
            $ids = Db::name('WarehouseGoods')->alias('wg')->join('goods g', 'wg.goods_id=g.id')->where('g.title|g.model', 'like', '%'.$value.'%')->column('wg.id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>