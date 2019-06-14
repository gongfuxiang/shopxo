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
namespace app\plugins\membershiplevel\service;

use think\Db;
use app\service\PluginsService;
use app\service\ResourcesService;
use app\service\UserService;

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

        // 数据处理
        return self::LevelDataHandle($data, $params);
    }

    /**
     * 用户等级数据列表处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-27T01:08:23+0800
     * @param    [array]                   $data   [等级数据]
     * @param    [array]                   $params [输入参数]
     */
    public static function LevelDataHandle($data, $params = [])
    {
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
        ];
        if(intval($params['rules_max']) > 0)
        {
            $p[] = [
                'checked_type'      => 'max',
                'key_name'          => 'rules_min',
                'checked_data'      => intval($params['rules_max']),
                'error_msg'         => '规则最小值不能大于最大值['.intval($params['rules_max']).']',
            ];
            $p[] = [
                'checked_type'      => 'min',
                'key_name'          => 'rules_max',
                'checked_data'      => intval($params['rules_min']),
                'error_msg'         => '规则最大值不能小于最小值['.intval($params['rules_min']).']',
            ];
        }
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
            'rules_min'             => $params['rules_min'],
            'rules_max'             => $params['rules_max'],
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

    /**
     * 优惠价格计算
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-26
     * @desc    description
     * @param   [string]          $price            [商品展示金额]
     * @param   [int]             $plugins_discount [折扣系数]
     * @param   [int]             $plugins_price    [减金额]
     */
    public static function PriceCalculate($price, $plugins_discount = 0, $plugins_price = 0)
    {
        if($plugins_discount <= 0 && $plugins_price <= 0)
        {
            return $price;
        }

        // 折扣
        if($plugins_discount > 0)
        {
            if(stripos($price, '-') !== false)
            {
                $text = explode('-', $price);
                $min_price = $text[0]*$plugins_discount;
                $max_price = $text[1]*$plugins_discount;
                $price = ($min_price <= 0) ? '0.00' : PriceNumberFormat($min_price);
                $price .= '-'.(($max_price <= 0) ? '0.00' : PriceNumberFormat($max_price));
            } else {
                $price = (float) $price *$plugins_discount;
                $price = ($price <= 0) ? '0.00' : PriceNumberFormat($price);
            }
        }

        // 减金额
        if($plugins_price > 0)
        {
            if(stripos($price, '-') !== false)
            {
                $text = explode('-', $price);
                $min_price = $text[0]-$plugins_price;
                $max_price = $text[1]-$plugins_price;
                $price = ($min_price <= 0) ? '0.00' : PriceNumberFormat($min_price);
                $price .= '-'.(($max_price <= 0) ? '0.00' : PriceNumberFormat($max_price));
            } else {
                $price = (float) $price-$plugins_price;
                $price = ($price <= 0) ? '0.00' : PriceNumberFormat($price);
            }
        }
        return $price;
    }

    /**
     * 用户等级匹配
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-28
     * @desc    description
     * @param   [array]           $user [用户信息]
     */
    public static function UserLevelMatching($user = [])
    {
        // 未指定用户信息，则从服务层读取
        if(empty($user))
        {
            $user = UserService::LoginUserInfo();
        }
        if(!empty($user))
        {
            // 缓存key
            $key = 'plugins_membershiplevel_cache_user_level_'.$user['id'];
            $level = cache($key);

            // 应用配置
            if(empty($level) || config('app_debug') == true)
            {
                $base = PluginsService::PluginsData('membershiplevel', Service::$base_config_attachment_field);
                if(!empty($base['data']['level_list']))
                {
                    // 规则
                    $level_rules = isset($base['data']['level_rules']) ? $base['data']['level_rules'] : 0;

                    // 匹配类型
                    $value = 0;
                    switch($level_rules)
                    {
                        // 积分（可用积分）
                        case 0 :
                            $value = isset($user['integral']) ? intval($user['integral']) : 0;
                            break;

                        // 消费总额（已完成订单）
                        // 订单状态（0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）
                        case 1 :
                            $where = ['user_id'=>$user['id'], 'status'=>4];
                            $value = (float) Db::name('Order')->where($where)->sum('total_price');
                            break;
                    }
                    
                    // 匹配相应的等级
                    $level_list = self::LevelDataHandle($base['data']['level_list']);
                    foreach($level_list['data'] as $rules)
                    {
                        if(isset($rules['is_enable']) && $rules['is_enable'] == 1)
                        {
                            // 0-*
                            if($rules['rules_min'] <= 0 && $rules['rules_max'] > 0 && $value < $rules['rules_max'])
                            {
                                $level = $rules;
                                break;
                            }

                            // *-*
                            if($rules['rules_min'] > 0 && $rules['rules_max'] > 0 && $value >= $rules['rules_min'] && $value < $rules['rules_max'])
                            {
                                $level = $rules;
                                break;
                            }

                            // *-0
                            if($rules['rules_max'] <= 0 && $rules['rules_min'] > 0 && $value > $rules['rules_min'])
                            {
                                $level = $rules;
                                break;
                            }
                        }
                    }

                    // 等级icon
                    if(!empty($level) && empty($level['images_url']))
                    {
                        $level['images_url'] = empty($base['data']['default_level_images']) ? config('shopxo.attachment_host').'/static/plugins/images/membershiplevel/level-default-images.png' : $base['data']['default_level_images'];
                    }
                    cache($key, $level);
                }
            }
            return $level;
        }
        return [];
    }
}
?>