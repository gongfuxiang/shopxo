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
 * 支付方式动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-18
 * @desc    description
 */
class Payment
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
        $lang = MyLang('payment.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'payment',
                'status_field'  => 'is_enable',
                'is_middle'     => 0,
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => $lang['name'],
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                ],
                 [
                    'label'         => $lang['logo'],
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/logo',
                ],
                [
                    'label'         => $lang['version'],
                    'view_type'     => 'field',
                    'view_key'      => 'version',
                ],
                [
                    'label'         => $lang['apply_version'],
                    'view_type'     => 'field',
                    'view_key'      => 'apply_version',
                ],
                [
                    'label'         => $lang['apply_terminal'],
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/apply_terminal',
                ],
                [
                    'label'         => $lang['author'],
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/author',
                ],
                [
                    'label'         => $lang['desc'],
                    'view_type'     => 'field',
                    'view_key'      => 'desc',
                    'grid_size'     => 'lg',
                ],
                [
                    'label'         => $lang['enable'],
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/enable',
                    'align'         => 'center',
                ],
                [
                    'label'         => $lang['open_user'],
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/open_user',
                    'align'         => 'center',
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'payment/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>