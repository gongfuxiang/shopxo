<?php

namespace Library\Payment;

/**
 * 支付宝支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Alipay
{
    // 插件配置参数
    private $params;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]           $params [输入参数（支付配置参数）]
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * 配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => '支付宝',  // 插件名称
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '1.0~1.3',  // 适用系统版本描述
            'desc'          => '即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://gong.gg/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'account',
                'placeholder'   => '支付宝账号',
                'title'         => '支付宝账号',
                'is_required'   => 1,
                'message'       => '请填写支付宝账号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '交易安全校验码 key',
                'title'         => '交易安全校验码 key',
                'is_required'   => 1,
                'message'       => '请填写交易安全校验码 key',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'partner',
                'placeholder'   => '合作者身份 partner ID',
                'title'         => '合作者身份 partner ID',
                'is_required'   => 1,
                'message'       => '请填写合作者身份 partner ID',
            ],
            [
                'element'       => 'input',  // 表单标签
                'type'          => 'text',  // input类型
                'default'       => '',  // 默认值
                'name'          => 'testinput',  // name名称
                'placeholder'   => '测试输入框不需要验证',  // input默认显示文字
                'title'         => '测试输入框不需要验证',  // 展示title名称
                'is_required'   => 0,  // 是否需要强制填写/选择
                'message'       => '请填写测试输入框不需要验证', // 错误提示（is_required=1方可有效）
            ],
            [
                'element'       => 'textarea',
                'default'       => '',
                'name'          => 'rsa_private',
                'placeholder'   => 'RSA证书',
                'title'         => 'RSA证书',
                'is_required'   => 0,
                'minlength'     => 10,  // 最小输入字符（is_required=1方可有效）
                //'maxlength'     => 300,  // 最大输入字符, 填则不限（is_required=1方可有效）
                'rows'          => 6,
            ],
            [
                'element'       => 'input',
                'type'          => 'checkbox',
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1'],
                    ['value'=>2, 'name'=>'选项2', 'is_checked'=>1],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'is_block'      => 1,  // 是否每个选项行内展示（默认0）
                'minchecked'    => 2,  // 最小选项（默认以is_required=1至少一项，则0）
                'maxchecked'    => 3,  // 最大选项
                'name'          => 'checkbox',
                'title'         => '多选项测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择多选项测试选择 至少选择2项最多选择3项',  // 错误提示信息
            ],
            [
                'element'       => 'input',
                'type'          => 'radio',
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1', 'is_checked'=>1],
                    ['value'=>2, 'name'=>'选项2'],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'is_block'      => 1,  // 是否每个选项行内展示（默认0）
                'name'          => 'radio',
                'title'         => '单选项测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择单选项测试',
            ],
            [
                'element'       => 'select',
                'placeholder'   => '选一个撒1',
                'is_multiple'   => 0,  // 是否开启多选（默认0 关闭）
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1'],
                    ['value'=>2, 'name'=>'选项2'],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'name'          => 'select1',
                'title'         => '下拉单选测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择下拉单选测试',
            ],
            [
                'element'       => 'select',
                'placeholder'   => '选一个撒2',
                'is_multiple'   => 1,  // 是否开启多选（默认0 关闭）
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1'],
                    ['value'=>2, 'name'=>'选项2'],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'minchecked'    => 2,  // 最小选项（默认以is_required=1至少一项，则0）
                'maxchecked'    => 3,  // 最大选项
                'name'          => 'select2',
                'title'         => '下拉多选测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择下拉多选测试 至少选择2项最多选择3项',
            ],
        ];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    /**
     * 支付入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Pay($params = [])
    {
        // 编写代码
        
        return 'Pay success';
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        // 编写代码
        
        return 'Respond success';
    }
}
?>