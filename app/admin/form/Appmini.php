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

/**
 * 小程序管理动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-22
 * @desc    description
 */
class Appmini
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
        $lang = MyLang('appmini.form_table');
        $nav_type = empty($params['nav_type']) ? 'weixin' : trim($params['nav_type']);
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'name',
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/appmini/delete', ['nav_type'=>$nav_type]),
                'delete_key'    => 'ids',
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
                    'label'         => $lang['name'],
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                ],
                [
                    'label'         => $lang['size'],
                    'view_type'     => 'field',
                    'view_key'      => 'size',
                ],
                [
                    'label'         => $lang['url'],
                    'view_type'     => 'field',
                    'view_key'      => 'url',
                    'grid_size'     => 'auto',
                ],
                [
                    'label'         => $lang['time'],
                    'view_type'     => 'field',
                    'view_key'      => 'time',
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'appmini/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>