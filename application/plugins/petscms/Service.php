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
namespace app\plugins\petscms;

use think\Db;
use app\service\ResourcesService;

/**
 * 宠物管理系统 - 服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    // 类型
    public static $pets_attribute_type_list = [
        'cat' => ['value' => 'cat', 'name' => '猫咪'],
        'dog' => ['value' => 'dog', 'name' => '狗狗'],
        'other' => ['value' => 'other', 'name' => '其它'],
    ];

    // 是否
    public static $pets_attribute_is_text_list = [
        0 => ['value' => 0, 'name' => '否', 'checked' => true],
        1 => ['value' => 1, 'name' => '是'],
    ];

    // 性别
    public static $pets_attribute_gender_list = [
        0 => ['value' => 0, 'name' => '公'],
        1 => ['value' => 1, 'name' => '母'],
    ];

    /**
     * 宠物保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PestSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '1,60',
                'is_checked'        => 2,
                'error_msg'         => '标题格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '宠物名字格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'birthday',
                'is_checked'        => 2,
                'error_msg'         => '请填写出生日期',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column($pets_attribute_type_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物类型有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'varieties',
                'checked_data'      => '30',
                'is_checked'        => 2,
                'error_msg'         => '品种格式最多 30 个字符',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => array_column($pets_attribute_gender_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物性别有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'sterilization',
                'checked_data'      => array_column($pets_attribute_gender_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物是否绝育有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'photo',
                'is_checked'        => 2,
                'error_msg'         => '请上传宠物相册',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '105000',
                'is_checked'        => 2,
                'error_msg'         => '宠物简介内容最多 105000 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_name',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '主人姓名格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_tel',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '主人电话格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_weixin',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '主人微信格式 1~30 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 宠物数据
        $data = [

        ];
    }
}
?>