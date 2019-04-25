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
namespace app\plugins\membershiplevel;

use app\service\PluginsService;
use app\service\ResourcesService;

/**
 * 会员等级服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    // 基础数据附件字段
    public static $base_config_attachment_field = [
        'default_level_images'
    ];

    // 等级规则
    public static $members_level_rules_list = [
        0 => ['value' => 0, 'name' => '积分（可用积分）', 'checked' => true],
        1 => ['value' => 1, 'name' => '消费总额（已完成订单）'],
    ];

    /**
     * 获取等级数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function LevelDataList($params = [])
    {
        // 数据字段
        $data_field = 'level_list';

        // 获取数据
        $ret = PluginsService::PluginsData('membershiplevel', self::$base_config_attachment_field);
        $data = (empty($ret['data']) || empty($ret['data'][$data_field])) ? [] : $ret['data'][$data_field];
        if(!empty($data))
        {
            $common_is_enable_tips = lang('common_is_enable_tips');
            foreach($data as &$v)
            {
                // 是否启用
                $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];
                
                // 图片地址
                $v['images_url_old'] = $v['images_url'];
                $v['images_url'] = ResourcesService::AttachmentPathViewHandle($v['images_url']);

                // 时间
                $v['operation_time_time'] = empty($v['operation_time']) ? '' : date('Y-m-d H:i:s', $v['operation_time']);
                $v['operation_time_date'] = empty($v['operation_time']) ? '' : date('Y-m-d', $v['operation_time']);
            }
        }

        // 是否读取单条
        if(!empty($params['get_id']) && isset($data[$params['get_id']]))
        {
            $data = $data[$params['get_id']];
        }

        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 获取等级数据保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function LevelDataSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,30',
                'error_msg'         => '名称长度 1~30 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'images_url',
                'checked_data'      => '255',
                'error_msg'         => '请上传图标',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'rules_min',
                'error_msg'         => '请填写规则最小值',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'rules_max',
                'error_msg'         => '请填写规则最大值',
            ],
            [
                'checked_type'      => 'max',
                'key_name'          => 'discount_rate',
                'checked_data'      => 0.99,
                'is_checked'        => 1,
                'error_msg'         => '折扣率应输入 0.00~0.99 的数字,小数保留两位',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'order_price',
                'checked_data'      => 'CheckPrice',
                'is_checked'        => 1,
                'error_msg'         => '请输入有效的订单满金额',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'full_reduction_price',
                'checked_data'      => 'CheckPrice',
                'is_checked'        => 1,
                'error_msg'         => '请输入有效的满减金额',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 请求参数
        $p = [
            [
                'checked_type'      => 'eq',
                'key_name'          => 'rules_min',
                'checked_data'      => $params['rules_max'],
                'error_msg'         => '规则最小值不能最大值相等',
            ],
            [
                'checked_type'      => 'eq',
                'key_name'          => 'rules_max',
                'checked_data'      => $params['rules_min'],
                'error_msg'         => '规则最大值不能最小值相等',
            ],
            [
                'checked_type'      => 'max',
                'key_name'          => 'rules_min',
                'checked_data'      => intval($params['rules_max']),
                'error_msg'         => '规则最小值不能大于最大值['.intval($params['rules_max']).']',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'rules_max',
                'checked_data'      => intval($params['rules_min']),
                'error_msg'         => '规则最大值不能小于最小值['.intval($params['rules_min']).']',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据字段
        $data_field = 'level_list';

        // 附件
        $data_fields = ['images_url'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'                  => $params['name'],
            'rules_min'             => intval($params['rules_min']),
            'rules_max'             => intval($params['rules_max']),
            'images_url'            => $attachment['data']['images_url'],
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'discount_rate'         => isset($params['discount_rate']) ? $params['discount_rate'] : 0,
            'order_price'           => empty($params['order_price']) ? 0.00 : PriceNumberFormat($params['order_price']),
            'full_reduction_price'  => empty($params['full_reduction_price']) ? 0.00 : PriceNumberFormat($params['full_reduction_price']),
            'operation_time'        => time(),
        ];

        // 原有数据
        $ret = PluginsService::PluginsData('membershiplevel', self::$base_config_attachment_field, false);

        // 数据id
        $data['id'] = (empty($params['id']) || empty($ret['data']) || empty($ret['data'][$data_field][$params['id']])) ? date('YmdHis').GetNumberCode(6) : $params['id'];
        $ret['data'][$data_field][$data['id']] = $data;

        // 保存
        return PluginsService::PluginsDataSave(['plugins'=>'membershiplevel', 'data'=>$ret['data']]);
    }

    /**
     * 数据删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DataDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据字段
        $data_field = empty($params['data_field']) ? 'data_list' : $params['data_field'];

        // 原有数据
        $ret = PluginsService::PluginsData('membershiplevel', self::$base_config_attachment_field, false);
        $ret['data'][$data_field] = (empty($ret['data']) || empty($ret['data'][$data_field])) ? [] : $ret['data'][$data_field];

        // 删除操作
        if(isset($ret['data'][$data_field][$params['id']]))
        {
            unset($ret['data'][$data_field][$params['id']]);
        }
        
        // 保存
        return PluginsService::PluginsDataSave(['plugins'=>'membershiplevel', 'data'=>$ret['data']]);
    }

    /**
     * 数据状态更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DataStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => '操作字段有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据字段
        $data_field = empty($params['data_field']) ? 'data_list' : $params['data_field'];

        // 原有数据
        $ret = PluginsService::PluginsData('membershiplevel', self::$base_config_attachment_field, false);
        $ret['data'][$data_field] = (empty($ret['data']) || empty($ret['data'][$data_field])) ? [] : $ret['data'][$data_field];

        // 删除操作
        if(isset($ret['data'][$data_field][$params['id']]) && is_array($ret['data'][$data_field][$params['id']]))
        {
            $ret['data'][$data_field][$params['id']][$params['field']] = intval($params['state']);
            $ret['data'][$data_field][$params['id']]['operation_time'] = time();
        }
        
        // 保存
        return PluginsService::PluginsDataSave(['plugins'=>'membershiplevel', 'data'=>$ret['data']]);
    }
}
?>