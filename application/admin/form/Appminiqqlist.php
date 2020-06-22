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
namespace app\admin\form;

/**
 * QQ小程序管理动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-22
 * @desc    description
 */
class Appminiqqlist
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'name',
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/appminiqqlist/delete'),
                'delete_key'    => 'ids',
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
                    'label'         => '包名',
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                ],
                [
                    'label'         => '大小',
                    'view_type'     => 'field',
                    'view_key'      => 'size',
                ],
                [
                    'label'         => '下载地址',
                    'view_type'     => 'field',
                    'view_key'      => 'url',
                    'grid_size'     => 'auto',
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'time',
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'appminiqqlist/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>