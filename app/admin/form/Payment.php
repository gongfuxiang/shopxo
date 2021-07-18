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
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'payment',
                'status_field'  => 'is_enable',
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '名称',
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                ],
                 [
                    'label'         => 'LOGO',
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/logo',
                ],
                [
                    'label'         => '插件版本',
                    'view_type'     => 'field',
                    'view_key'      => 'version',
                ],
                [
                    'label'         => '适用版本',
                    'view_type'     => 'apply_version',
                    'view_key'      => 'name',
                ],
                [
                    'label'         => '适用终端',
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/apply_terminal',
                ],
                [
                    'label'         => '作者',
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/author',
                ],
                [
                    'label'         => '描述',
                    'view_type'     => 'field',
                    'view_key'      => 'desc',
                    'grid_size'     => 'lg',
                ],
                [
                    'label'         => '是否启用',
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/enable',
                    'align'         => 'center',
                ],
                [
                    'label'         => '用户开放',
                    'view_type'     => 'module',
                    'view_key'      => 'payment/module/open_user',
                    'align'         => 'center',
                ],
                [
                    'label'         => '操作',
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