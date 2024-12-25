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
namespace app\service;

use think\facade\Db;
use app\service\UserService;
use app\service\ResourcesService;
use app\service\RefundLogService;
use app\service\OrderService;
use app\service\MessageService;
use app\service\IntegralService;
use app\service\WarehouseService;
use app\service\OrderCurrencyService;
use app\plugins\wallet\service\WalletService;

/**
 * 订单售后服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OrderAftersaleService
{
    /**
     * 获取一条订单,附带一条指定商品
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     * @param   [int]          $order_id        [订单id]
     * @param   [int]          $order_detail_id [订单详情id]
     * @param   [int]          $user_id         [用户id]
     */
    public static function OrdferGoodsRow($order_id, $order_detail_id, $user_id)
    {
        // 获取列表
        $data_params = [
            'm'         => 0,
            'n'         => 1,
            'where'     => [
                ['id', '=', intval($order_id)],
                ['user_id', '=', intval($user_id)],
            ],
        ];
        $ret = OrderService::OrderList($data_params);
        if($ret['code'] == 0 && !empty($ret['data'][0]))
        {
            // 商品处理
            $goods = [];
            if(!empty($ret['data'][0]['items']))
            {
                foreach($ret['data'][0]['items'] as $v)
                {
                    if($order_detail_id == $v['id'])
                    {
                        $goods = $v;
                        break;
                    }
                }
            }
            $ret['data'][0]['items'] = $goods;
            $ret['data'] = $ret['data'][0];

            return $ret;
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 售后创建
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AftersaleCreate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_detail_id',
                'error_msg'         => MyLang('order_detail_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(MyConst('common_order_aftersale_type_list'), 'value'),
                'error_msg'         => MyLang('operate_type_error_tips'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'price',
                'checked_data'      => 'CheckPrice',
                'error_msg'         => MyLang('common_service.orderaftersale.save_price_format_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'reason',
                'checked_data'      => '180',
                'error_msg'         => MyLang('common_service.orderaftersale.save_reason_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'msg',
                'checked_data'      => '200',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.orderaftersale.form_item_msg_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单数据
        $order = self::OrdferGoodsRow($params['order_id'], $params['order_detail_id'], $params['user']['id']);
        if($order['code'] != 0)
        {
            return $order;
        }

        // 订单是否可发起售后
        if($order['data']['is_can_launch_aftersale'] != 1)
        {
            return DataReturn(MyLang('common_service.orderaftersale.order_aftersale_overdue_tips'), -1);
        }

        // 当前是否存在进行中
        $where = [
            ['order_id', '=', intval($params['order_id'])],
            ['order_detail_id', '=', intval($params['order_detail_id'])],
            ['user_id', '=', $params['user']['id']],
            ['status', '<=', 2],
        ];
        $count = (int) Db::name('OrderAftersale')->where($where)->count();
        if($count > 0)
        {
            return DataReturn(MyLang('common_service.orderaftersale.order_aftersale_have_in_hand_tips'), -1);
        }

        // 获取历史申请售后条件
        $where = [
            ['order_id', '=', intval($params['order_id'])],
            ['user_id', '=', $params['user']['id']],
            ['status', '<=', 3],
        ];

        // 退款金额
        $price = PriceNumberFormat($params['price']);

        // 历史退款金额
        $history_price = PriceNumberFormat(Db::name('OrderAftersale')->where($where)->sum('price'));
        if(PriceNumberFormat($price+$history_price) > $order['data']['pay_price'])
        {
            return DataReturn(MyLang('common_service.orderaftersale.refund_amount_max_order_price_tips', ['history_price'=>$history_price, 'order_price'=>$order['data']['pay_price']]), -1);
        }

        // 退货数量
        $number = isset($params['number']) ? intval($params['number']) : 0;

        // 历史退货数量
        $where[] = ['order_detail_id', '=', intval($params['order_detail_id'])];
        $history_number = (int) Db::name('OrderAftersale')->where($where)->sum('number');
        if($params['type'] == 1)
        {
            if(intval($number+$history_number) > $order['data']['items']['buy_number'])
            {
                return DataReturn(MyLang('common_service.orderaftersale.return_quantity_max_order_number_tips', ['history_number'=>$history_number, 'buy_number'=>$order['data']['items']['buy_number']]), -1);
            }
        }

        // 附件处理
        $images = [];
        if(!empty($params['images']))
        {
            if(!is_array($params['images']))
            {
                $params['images'] = json_decode(htmlspecialchars_decode($params['images']), true);
            }
            foreach($params['images'] as $v)
            {
                $images[] = ResourcesService::AttachmentPathHandle($v);
            }
            if(count($images) > 3)
            {
                return DataReturn(MyLang('common_service.orderaftersale.form_item_images_tips'), -1);
            }
        }

        // 数据
        $data = [
            'system_type'       => SYSTEM_TYPE,
            'order_no'          => $order['data']['order_no'],
            'type'              => intval($params['type']),
            'order_detail_id'   => intval($params['order_detail_id']),
            'order_id'          => intval($params['order_id']),
            'goods_id'          => $order['data']['items']['goods_id'],
            'user_id'           => $params['user']['id'],
            'number'            => ($params['type'] == 0) ? 0 : $number,
            'price'             => $price,
            'reason'            => empty($params['reason']) ? '' : $params['reason'],
            'msg'               => empty($params['msg']) ? '' : $params['msg'],
            'images'            => json_encode($images),
            'status'            => ($params['type'] == 0) ? 2 : 0,
            'add_time'          => time(),
            'apply_time'        => time(),
        ];

        // 订单售后添加前钩子
        $hook_name = 'plugins_service_order_aftersale_insert_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 处理数据
        Db::startTrans();
        try {
            // 数据添加
            $data_id = Db::name('OrderAftersale')->insertGetId($data);
            if($data_id <= 0)
            {
                throw new \Exception(MyLang('apply_fail'));
            }

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderAftersaleHistoryAdd($data_id, $data['status'], '', MyLang('apply_title'), $creator, $creator_name);

            // 订单售后添加成功钩子
            $hook_name = 'plugins_service_order_aftersale_insert_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $data_id,
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            Db::commit();
            return DataReturn(MyLang('apply_success'), 0, $data_id);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 用户退货
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AftersaleDelivery($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'express_name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.orderaftersale.form_item_express_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'express_number',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.orderaftersale.form_item_express_number_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取申请数据
        $where = [
            'id'        => intval($params['id']),
            'user_id'   => $params['user']['id'],
        ];
        $aftersale = Db::name('OrderAftersale')->where($where)->find();
        if(empty($aftersale))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 状态
        if($aftersale['type'] == 0)
        {
            return DataReturn(MyLang('common_service.orderaftersale.refund_only_not_can_return_goods_tips'), -1);
        }
        if($aftersale['status'] != 1)
        {
            $common_order_aftersale_status_list = MyConst('common_order_aftersale_status_list');
            return DataReturn(MyLang('common_service.orderaftersale.status_not_can_operate_tips').'['.$common_order_aftersale_status_list[$aftersale['status']]['name'].']', -10);
        }

        // 数据
        $data = [
            'status'            => 2,
            'express_name'      => $params['express_name'],
            'express_number'    => $params['express_number'],
            'delivery_time'     => time(),
            'upd_time'          => time(),
        ];

        // 订单售后单退货前钩子
        $hook_name = 'plugins_service_order_aftersale_delivery_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data_id'       => $params['id'],
            'data'          => &$data,
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 处理数据
        Db::startTrans();
        try {
            // 更新数据
            if(!Db::name('OrderAftersale')->where($where)->update($data))
            {
                throw new \Exception(MyLang('operate_fail'));
            }

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderAftersaleHistoryAdd($aftersale['id'], $data['status'], $aftersale['status'], MyLang('retreat_goods_title'), $creator, $creator_name);

            // 订单售后退货成功钩子
            $hook_name = 'plugins_service_order_aftersale_delivery_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            Db::commit();
            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 获取订单售后纪录列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderAftersaleList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 订单售后列表读取前钩子
        $hook_name = 'plugins_service_order_aftersale_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
            'm'             => &$m,
            'n'             => &$n,
        ]);

        // 获取数据列表
        $data = Db::name('OrderAftersale')->field($field)->where($where)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('get_success'), 0, self::OrderAftersaleListHandle($data, $params));
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function OrderAftersaleListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 订单售后列表钩子-前面
            $hook_name = 'plugins_service_order_aftersale_list_handle_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);

            // 字段列表
            $keys = ArrayKeys($data);
            $order_aftersale_ids = array_column($data, 'id');

            // 其它额外处理
            $is_status_history = isset($params['is_status_history']) ? intval($params['is_status_history']) : 0;

            // 订单日志
            $status_history_data = ($is_status_history == 1) ? self::OrderStatusHistoryList($order_aftersale_ids) : [];

            // 静态数据
            $type_list = MyConst('common_order_aftersale_type_list');
            $status_list = MyConst('common_order_aftersale_status_list');
            $refundment_list = MyConst('common_order_aftersale_refundment_list');
            foreach($data as &$v)
            {
                // 订单售后处理前钩子
                $hook_name = 'plugins_service_order_aftersale_handle_begin';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'order'         => &$v,
                    'order_id'      => $v['order_id']
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                // 订单商品
                $order = self::OrdferGoodsRow($v['order_id'], $v['order_detail_id'], $v['user_id']);
                $v['order_data'] = $order['data'];

                // 用户信息
                $user = UserService::GetUserViewInfo($v['user_id']);
                if(isset($params['is_public']) && $params['is_public'] == 0)
                {
                    $v['user'] = $user;
                } else {
                    $v['user'] = null;
                }

                // 类型
                $v['type_text'] = array_key_exists($v['type'], $type_list) ? $type_list[$v['type']]['name'] : '';

                // 状态
                $v['status_text'] = array_key_exists($v['status'], $status_list) ? $status_list[$v['status']]['name'] : '';

                // 退款方式
                $v['refundment_text'] = ($v['status'] == 3 && array_key_exists($v['refundment'], $refundment_list)) ? $refundment_list[$v['refundment']]['name'] : '';

                // 图片
                if(!empty($v['images']))
                {
                    $images = json_decode($v['images'], true);
                    foreach($images as $ik=>$iv)
                    {
                        $images[$ik] = ResourcesService::AttachmentPathViewHandle($iv);
                    }
                    $v['images'] = $images;
                } else {
                    $v['images'] = '';
                }

                // 订单日志
                if($is_status_history == 1)
                {
                    $v['status_history_data'] = array_key_exists($v['id'], $status_history_data) ? $status_history_data[$v['id']] : [];
                }

                // 申请时间
                $v['apply_time'] = empty($v['apply_time']) ? '' : date('Y-m-d H:i:s', $v['apply_time']);

                // 确认时间
                $v['confirm_time'] = empty($v['confirm_time']) ? '' : date('Y-m-d H:i:s', $v['confirm_time']);

                // 退货时间
                $v['delivery_time'] = empty($v['delivery_time']) ? '' : date('Y-m-d H:i:s', $v['delivery_time']);

                // 审核时间
                $v['audit_time'] = empty($v['audit_time']) ? '' : date('Y-m-d H:i:s', $v['audit_time']);

                // 取消时间
                $v['cancel_time'] = empty($v['cancel_time']) ? '' : date('Y-m-d H:i:s', $v['cancel_time']);

                // 添加时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);

                // 订单售后处理后钩子
                $hook_name = 'plugins_service_order_aftersale_handle_end';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'order'         => &$v,
                    'order_id'      => $v['order_id']
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }
            }

            // 订单售后列表钩子-后面
            $hook_name = 'plugins_service_order_aftersale_list_handle_end';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);
        }
        return $data;
    }

    /**
     * 订单售后列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderAftersaleListWhere($params = [])
    {
        // 用户类型
        $user_type = isset($params['user_type']) ? $params['user_type'] : 'user';

        // 条件初始化
        $where = [];

        // id
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', intval($params['id'])];
        }
        
        // 用户类型
        if(isset($params['user_type']) && $params['user_type'] == 'user')
        {
            // 用户id
            if(!empty($params['user']))
            {
                $where[] = ['user_id', '=', $params['user']['id']];
            }
        }

        // 关键字根据用户筛选
        if(!empty($params['keywords']))
        {
            if(empty($params['user']))
            {
                $user_ids = Db::name('User')->where('number_code|username|nickname|mobile|email', '=', $params['keywords'])->column('id');
                if(!empty($user_ids))
                {
                    $where[] = ['user_id', 'in', $user_ids];
                } else {
                    // 无数据条件，走单号条件
                    $where[] = ['order_no|express_number', '=', $params['keywords']];
                }
            } else {
                // 用户走关键字
                $where[] = ['order_no|express_number', '=', $params['keywords']];
            }
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['type']) && $params['type'] > -1)
            {
                $where[] = ['type', '=', intval($params['type'])];
            }
            if(isset($params['refundment']) && $params['refundment'] > -1)
            {
                $where[] = ['refundment', '=', intval($params['refundment'])];
            }
            if(isset($params['status']) && $params['status'] > -1)
            {
                // 多个状态,字符串以半角逗号分割
                if(!is_array($params['status']))
                {
                    $params['status'] = explode(',', $params['status']);
                }
                $where[] = ['status', 'in', $params['status']];
            }
            if(!empty($params['express_number']))
            {
                $where[] = ['express_number', '=', $params['express_number']];
            }

            // 时间
            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }
        }
        return $where;
    }

    /**
     * 订单售后总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function OrderAftersaleTotal($where = [])
    {
        // 订单售后总数读取前钩子
        $hook_name = 'plugins_service_order_aftersale_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取总数
        return (int) Db::name('OrderAftersale')->where($where)->count();
    }

    /**
     * 订单日志数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-28
     * @desc    description
     * @param   [array|int]          $order_aftersale_ids [订单售后id]
     */
    public static function OrderStatusHistoryList($order_aftersale_ids)
    {
        $data = Db::name('OrderAftersaleStatusHistory')->where(['order_aftersale_id'=>$order_aftersale_ids])->select()->toArray();
        if(!empty($data))
        {
            $group = [];
            foreach($data as &$v)
            {
                // 添加时间
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);

                // 订单id是数组则处理分组
                if(is_array($order_aftersale_ids))
                {
                    if(!array_key_exists($v['order_aftersale_id'], $group))
                    {
                        $group[$v['order_aftersale_id']] = [];
                    }
                    $group[$v['order_aftersale_id']][] = $v;
                }
            }
            // 如果订单id是数组则赋值分组
            if(is_array($order_aftersale_ids) && !empty($group))
            {
                $data = $group;
            }
        }
        return $data;
    }

    /**
     * 订单售后日志添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [int]          $order_aftersale_id  [订单id]
     * @param   [int]          $new_status          [更新后的状态]
     * @param   [int]          $original_status     [原始状态]
     * @param   [string]       $msg                 [描述]
     * @param   [int]          $creator             [操作人]
     * @param   [string]       $creator_name        [操作人名称]
     * @return  [boolean]                           [成功 true, 失败 false]
     */
    public static function OrderAftersaleHistoryAdd($order_aftersale_id, $new_status, $original_status, $msg = '', $creator = 0, $creator_name = '')
    {
        // 状态描述
        $status_list = MyConst('common_order_aftersale_status_list');
        $original_status_name = ($original_status != '' && isset($status_list[$original_status])) ? $status_list[$original_status]['name'] : '';
        $new_status_name = ($new_status != '' && isset($status_list[$new_status])) ? $status_list[$new_status]['name'] : '';
        if(!empty($original_status_name) && !empty($new_status_name))
        {
            $msg .= '['.$original_status_name.'-'.$new_status_name.']';
        } else if(!empty($original_status_name))
        {
            $msg .= '['.$original_status_name.']';
        } else if(!empty($new_status_name))
        {
            $msg .= '['.$new_status_name.']';
        }

        // 添加
        $data = [
            'order_aftersale_id'  => intval($order_aftersale_id),
            'new_status'          => intval($new_status),
            'original_status'     => intval($original_status),
            'msg'                 => htmlentities($msg),
            'creator'             => intval($creator),
            'creator_name'        => htmlentities($creator_name),
            'add_time'            => time(),
        ];

        // 日志添加
        if(Db::name('OrderAftersaleStatusHistory')->insertGetId($data) > 0)
        {
            // 订单售后状态改变添加日志钩子
            $hook_name = 'plugins_service_order_aftersale_status_change_history_success_handle';
            MyEventTrigger($hook_name, [
                'hook_name'           => $hook_name,
                'is_backend'          => true,
                'data'                => $data,
                'order_aftersale_id'  => $data['order_aftersale_id']
            ]);

            return true;
        }
        return false;
    }

    /**
     * 订单售后取消
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AftersaleCancel($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'is_checked'        => 2,
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'    => intval($params['id']),
        ];
        if(!empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        // 售后订单
        $aftersale = Db::name('OrderAftersale')->where($where)->find();
        if(empty($aftersale))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 状态校验
        if(in_array($aftersale['status'], [3,5]))
        {
            $status_list = MyConst('common_order_aftersale_status_list');
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 数据
        $data = [
            'status'        => 5,
            'cancel_time'   => time(),
            'upd_time'      => time()
        ];

        // 订单售后单取消前钩子
        $hook_name = 'plugins_service_order_aftersale_cacnel_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data_id'       => $params['id'],
            'data'          => &$data,
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 处理数据
        Db::startTrans();
        try {
            // 更新数据
            if(!Db::name('OrderAftersale')->where($where)->update($data))
            {
                throw new \Exception(MyLang('cancel_fail'));
            }

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderAftersaleHistoryAdd($aftersale['id'], $data['status'], $aftersale['status'], MyLang('cancel_title'), $creator, $creator_name);

            // 订单售后取消成功钩子
            $hook_name = 'plugins_service_order_aftersale_cacnel_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            Db::commit();
            return DataReturn(MyLang('cancel_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 确认
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AftersaleConfirm($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'    => intval($params['id']),
        ];

        // 售后订单
        $aftersale = Db::name('OrderAftersale')->where($where)->find();
        if(empty($aftersale))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 状态校验
        if($aftersale['status'] != 0)
        {
            $status_list = MyConst('common_order_aftersale_status_list');
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 类型
        if($aftersale['type'] != 1)
        {
            $aftersale_type_list = MyConst('common_order_aftersale_type_list');
            return DataReturn('类型不可操作['.$aftersale_type_list[$aftersale['type']]['name'].']', -1);
        }

        // 数据
        $data = [
            'status'        => 1,
            'confirm_time'  => time(),
            'upd_time'      => time()
        ];

        // 订单售后单确认前钩子
        $hook_name = 'plugins_service_order_aftersale_confirm_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data_id'       => $params['id'],
            'data'          => &$data,
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 处理数据
        Db::startTrans();
        try {
            // 更新数据
            if(!Db::name('OrderAftersale')->where($where)->update($data))
            {
                throw new \Exception(MyLang('confirm_fail'));
            }

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderAftersaleHistoryAdd($aftersale['id'], $data['status'], $aftersale['status'], MyLang('confirm_title'), $creator, $creator_name);

            // 订单售后单确认成功钩子
            $hook_name = 'plugins_service_order_aftersale_confirm_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            Db::commit();
            return DataReturn(MyLang('confirm_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 审核
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AftersaleAudit($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'refundment',
                'checked_data'      => array_column(MyConst('common_order_aftersale_refundment_list'), 'value'),
                'error_msg'         => MyLang('common_service.orderaftersale.form_item_refundment_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 售后订单
        $aftersale = Db::name('OrderAftersale')->where(['id' => intval($params['id'])])->find();
        if(empty($aftersale))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 状态校验
        if($aftersale['status'] != 2)
        {
            $status_list = MyConst('common_order_aftersale_status_list');
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 获取订单数据
        $order = self::OrdferGoodsRow($aftersale['order_id'], $aftersale['order_detail_id'], $aftersale['user_id']);
        if($order['code'] != 0)
        {
            return $order;
        }

        // 获取历史申请售后条件
        $where = [
            ['order_id', '=', $aftersale['order_id']],
            ['status', '=', 3],
            ['id', '<>', $aftersale['id']],
        ];

        // 历史退款金额
        $history_price = PriceNumberFormat(Db::name('OrderAftersale')->where($where)->sum('price'));
        if(PriceNumberFormat($aftersale['price']+$history_price) > $order['data']['pay_price'])
        {
            return DataReturn(MyLang('common_service.orderaftersale.refund_amount_max_order_price_tips', ['history_price'=>$history_price, 'order_price'=>$order['data']['pay_price']]), -1);
        }

        // 历史退货数量
        $where[] = ['order_detail_id', '=', $aftersale['order_detail_id']];
        $history_number = (int) Db::name('OrderAftersale')->where($where)->sum('number');
        if($aftersale['type'] == 1)
        {
            if(intval($aftersale['number']+$history_number) > $order['data']['items']['buy_number'])
            {
                return DataReturn(MyLang('common_service.orderaftersale.return_quantity_max_order_number_tips',['history_number'=>$history_number, 'buy_number'=>$order['data']['items']['buy_number']]), -1);
            }
        }

        // 订单支付方式校验
        $pay_log = Db::name('PayLog')->alias('pl')->join('pay_log_value plv', 'pl.id=plv.pay_log_id')->where(['plv.business_id'=>$order['data']['id'], 'pl.business_type'=>OrderService::BusinessTypeName(), 'pl.status'=>1])->field('pl.*')->find();

        // 手动处理不校验支付日志
        if($params['refundment'] != 2)
        {
            if(empty($pay_log))
            {
                return DataReturn(MyLang('common_service.orderaftersale.pay_log_empty_tips'), -1);
            }
        }

        // 原路退回
        if($params['refundment'] == 0)
        {
            if(in_array($pay_log['payment'], MyConfig('shopxo.under_line_list')))
            {
                return DataReturn(MyLang('common_service.orderaftersale.under_line_not_tetrace_tips').'[ '.$pay_log['payment_name'].' ]', -1);
            } else {
                $payment = 'payment\\'.$pay_log['payment'];
                if(class_exists($payment))
                {
                    if(!method_exists((new $payment()), 'Refund'))
                    {
                        return DataReturn(MyLang('common_service.orderaftersale.payment_plugins_not_refund_tips').'[ '.$pay_log['payment'].' ]', -1);
                    }
                } else {
                    return DataReturn(MyLang('common_service.orderaftersale.payment_plugins_no_exist_tips').'[ '.$pay_log['payment'].' ]', -1);
                }
            }
        }

        // 原路退回(钱包支付方式使用退至钱包)/退到钱包(走事务处理)/手动处理
        $is_walet = false;
        if($params['refundment'] == 0)
        {
            if($pay_log['payment'] == 'WalletPay')
            {
                $is_walet = true;
            } else {
                // 原路退回
                $refund = self::OriginalRoadRefundment($params, $aftersale, $order['data'], $pay_log);
            }
        } elseif($params['refundment'] == 1)
        {
            $is_walet = true;
        } else {
            // 手动处理不涉及金额
            $refund = DataReturn(MyLang('refund_success'), 0);
        }

        // 退款成功
        if(isset($refund['code']) && $refund['code'] != 0)
        {
            return $refund;
        }

        // 钱包校验
        if($is_walet === true)
        {
            $wallet = Db::name('Plugins')->where(['plugins'=>'wallet'])->find();
            if(empty($wallet))
            {
                return DataReturn(MyLang('common_service.orderaftersale.no_wallet_payment_plugins_tips').'[ Wallet ]', -1);
            }
        }

        // 启动事务
        Db::startTrans();
        // 捕获异常
        try {
            // 钱包退款
            if($is_walet === true)
            {
                $ret = self::WalletRefundment($params, $aftersale, $order['data'], $pay_log);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }
            }

            // 是否需要自动退回数量
            // 仅退款类型、申请退款金额+已退款金额  大于等于  订单商品详情总额-订单优惠金额时
            // 非已发货和已完成、或虚拟订单模式
            $is_refund_only_number = false;
            if($aftersale['type'] == 0 && $aftersale['price']+$order['data']['items']['refund_price'] >= $order['data']['items']['total_price']-$order['data']['preferential_price'] && (!in_array($order['data']['status'], [3,4]) || $order['data']['order_model'] == 3))
            {
                $is_refund_only_number = true;
                $aftersale['number'] = $order['data']['items']['buy_number']-$order['data']['items']['returned_quantity'];
            }

            // 更新主订单
            $refund_price = PriceNumberFormat($order['data']['refund_price']+$aftersale['price']);
            $returned_quantity = intval($order['data']['returned_quantity']+$aftersale['number']);
            $order_upd_data = [
                'pay_status'        => ($refund_price >= $order['data']['pay_price']) ? 2 : 3,
                'refund_price'      => $refund_price,
                'returned_quantity' => $returned_quantity,
                'upd_time'          => time(),
            ];

            // 如果退款金额和退款数量到达订单实际是否金额和购买数量则关闭订单
            if($refund_price >= $order['data']['pay_price'] && $returned_quantity >= $order['data']['buy_number_count'])
            {
                $order_upd_data['status'] = 6;
                $order_upd_data['close_time'] = time();
            }
            
            // 更新主订单
            if(!Db::name('Order')->where(['id'=>$order['data']['id']])->update($order_upd_data))
            {
                throw new \Exception(MyLang('common_service.orderaftersale.order_update_fail_tips'));
            }

            // 订单详情
            $detail_upd_data = [
                'refund_price'      => PriceNumberFormat($order['data']['items']['refund_price']+$aftersale['price']),
                'returned_quantity' => intval($order['data']['items']['returned_quantity']+$aftersale['number']),
                'upd_time'          => time(),
            ];
            if(!Db::name('OrderDetail')->where(['id'=>$aftersale['order_detail_id']])->update($detail_upd_data))
            {
                throw new \Exception(MyLang('common_service.orderaftersale.order_detail_update_fail_tips'));
            }

            // 库存回滚
            if($aftersale['type'] == 1 || $is_refund_only_number == true)
            {
                $ret = BuyService::OrderInventoryRollback(['order_id'=>$order['data']['id'], 'order_data'=>$order_upd_data, 'appoint_order_detail_id'=>$aftersale['order_detail_id'], 'appoint_buy_number'=>$aftersale['number']]);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }
            }

            // 积分释放
            $ret = IntegralService::OrderGoodsIntegralRollback(['order_id'=>$order['data']['id'], 'order_detail_id'=>$aftersale['order_detail_id']]);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 已完成订单、商品销量释放
            // 规则 0 订单支付、1 订单收货（默认）
            $status = (MyC('common_goods_sales_count_inc_rules', 1) == 1) ? ($order['data']['status'] == 4) : ($order['data']['pay_status'] != 0);
            if($status && $aftersale['number'] > 0)
            {
                if(!Db::name('Goods')->where(['id'=>intval($aftersale['goods_id'])])->dec('sales_count', $aftersale['number'])->update())
                {
                    throw new \Exception(MyLang('common_service.orderaftersale.goods_sales_count_release_fail_tips'));
                }
            }

            // 消息通知
            $msg = MyLang('common_service.orderaftersale.pay_log_refund_reason', ['order_no'=>$order['data']['order_no'], 'price'=>$aftersale['price']]);
            MessageService::MessageAdd($order['data']['user_id'],  MyLang('common_service.orderaftersale.refund_user_message_title'), $msg, MyLang('common_service.orderaftersale.refund_message_business_type_name'), $order['data']['id']);

            // 订单状态日志
            if(isset($order_upd_data['status']))
            {
                $creator = isset($params['creator']) ? intval($params['creator']) : 0;
                $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
                OrderService::OrderHistoryAdd($order['data']['id'], $order_upd_data['status'], $order['data']['status'], MyLang('close_title'), $creator, $creator_name);
            }

            // 更新退款状态
            $data = [
                'status'        => 3,
                'refundment'    => $params['refundment'],
                'audit_time'    => time(),
                'upd_time'      => time(),
            ];

            // 仅退款是否退了数量
            if($is_refund_only_number == true)
            {
                $data['number'] = $aftersale['number'];
            }

            // 订单售后单审核前钩子
            $hook_name = 'plugins_service_order_aftersale_audit_handle_begin';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $aftersale['id'],
                'data'          => &$data,
                'order_id'      => $order['data']['id'],
                'params'        => $params,
                
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 数据更新
            if(!Db::name('OrderAftersale')->where(['id'=>$aftersale['id']])->update($data))
            {
                throw new \Exception(MyLang('common_service.orderaftersale.order_aftersale_update_fail_tips'));
            }

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderAftersaleHistoryAdd($aftersale['id'], $data['status'], $aftersale['status'], MyLang('audit_title'), $creator, $creator_name);

            // 订单售后审核处理完毕钩子
            $hook_name = 'plugins_service_order_aftersale_audit_handle_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data_id'       => $aftersale['id'],
                'order_id'      => $order['data']['id'],
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 提交事务
            Db::commit();
            return DataReturn(MyLang('refund_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 原路退回
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-27
     * @desc    description
     * @param   [array]          $params    [输入参数]
     * @param   [array]          $aftersale [售后订单数据]
     * @param   [array]          $order     [订单数据]
     * @param   [array]          $pay_log   [订单支付日志]
     */
    private static function OriginalRoadRefundment($params, $aftersale, $order, $pay_log)
    {
        // 支付方式
        $payment = PaymentService::PaymentData([
            'where'     => [
                'payment'   => $pay_log['payment']
            ],
            'is_refund' => 1,
            'log_id'    => $pay_log['id'],
            'data_id'   => $order['id'],
            'data_type' => 'order',
        ]);
        if(empty($payment))
        {
            return DataReturn(MyLang('payment_method_error_tips'), -1);
        }

        // 交易平台单号
        if(empty($pay_log['trade_no']))
        {
            return DataReturn(MyLang('common_service.orderaftersale.pay_log_trade_empty_tips'), -1);
        }

        // 订单货币
        $currency_data = OrderCurrencyService::OrderCurrencyGroupList($order['id']);

        // 操作退款
        $pay_name = 'payment\\'.$pay_log['payment'];
        $msg = MyLang('common_service.orderaftersale.pay_log_refund_reason', ['order_no'=>$order['order_no'], 'price'=>$aftersale['price']]);
        // 如果支付金额与支付单总额仅相差一分钱则使用支付单总额（该问题可能在有些支付会转换为分的情况下出现精度原因造成金额不一致）
        $pay_price = ($pay_log['total_price']-$pay_log['pay_price'] <= 0.01) ? $pay_log['total_price'] : $pay_log['pay_price'];
        $pay_params = [
            'order_no'          => $pay_log['log_no'],
            'trade_no'          => $pay_log['trade_no'],
            'pay_price'         => $pay_price,
            'pay_time'          => $pay_log['pay_time'],
            'refund_price'      => $aftersale['price'],
            'aftersale'         => $aftersale,
            'order'             => $order,
            'business_id'       => $order['id'],
            'business_no'       => $order['order_no'],
            'client_type'       => $order['client_type'],
            'refund_reason'     => $msg,
            'pay_log_data'      => $pay_log,
            'currency_data'     => $currency_data,
        ];

        // 订单发起售后原路退回前钩子
        $hook_name = 'plugins_service_order_aftersale_original_road_refund_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order'         => $order,
            'aftersale'     => $aftersale,
            'params'        => $params,
            'pay_log'       => &$pay_log,
            'payment'       => &$payment,
            'pay_params'    => &$pay_params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 操作退回
        $ret = (new $pay_name($payment['config']))->Refund($pay_params);
        if(!isset($ret['code']))
        {
            return DataReturn(MyLang('common_service.orderaftersale.payment_plugins_tetrace_fail_tips'), -1);
        }
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 写入退款日志
        $refund_log = [
            'user_id'         => $order['user_id'],
            'business_id'     => $order['id'],
            'pay_price'       => $order['pay_price'],
            'trade_no'        => isset($ret['data']['trade_no']) ? $ret['data']['trade_no'] : '',
            'buyer_user'      => isset($ret['data']['buyer_user']) ? $ret['data']['buyer_user'] : '',
            'request_params'  => isset($ret['data']['request_params']) ? $ret['data']['request_params'] : '',
            'refund_price'    => isset($ret['data']['refund_price']) ? $ret['data']['refund_price'] : '',
            'msg'             => $pay_params['refund_reason'],
            'pay_id'          => $pay_log['id'],
            'payment'         => $pay_log['payment'],
            'payment_name'    => $pay_log['payment_name'],
            'refundment'      => $params['refundment'],
            'business_type'   => OrderService::BusinessTypeName(),
            'return_params'   => isset($ret['data']['return_params']) ? $ret['data']['return_params'] : '',
        ];
        RefundLogService::RefundLogInsert($refund_log);
        return $ret;
    }

    /**
     * 退至钱包
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-27
     * @desc    description
     * @param   [array]          $params    [输入参数]
     * @param   [array]          $aftersale [售后订单数据]
     * @param   [array]          $order     [订单数据]
     * @param   [array]          $pay_log   [订单支付日志]
     */
    private static function WalletRefundment($params, $aftersale, $order, $pay_log)
    {
        // 获取用户钱包校验
        $user_wallet = WalletService::UserWallet($order['user_id']);
        if($user_wallet['code'] != 0)
        {
            return $user_wallet;
        }

        // 钱包更新数据
        $data = [
            'normal_money'      => PriceNumberFormat($user_wallet['data']['normal_money']+$aftersale['price']),
            'upd_time'          => time(),
        ];
        if(Db::name('PluginsWallet')->where(['id'=>$user_wallet['data']['id']])->update($data) === false)
        {
            return DataReturn(MyLang('common_service.orderaftersale.wallet_update_fail_tips'), -10);
        }

        // 钱包变更日志
        $msg = MyLang('common_service.orderaftersale.pay_log_refund_reason', ['order_no'=>$order['order_no'], 'price'=>$aftersale['price']]);
        $log_data = [
            'user_id'           => $user_wallet['data']['user_id'],
            'wallet_id'         => $user_wallet['data']['id'],
            'business_type'     => 0,
            'operation_type'    => 1,
            'money_type'        => 0,
            'operation_money'   => $aftersale['price'],
            'original_money'    => $user_wallet['data']['normal_money'],
            'latest_money'      => $data['normal_money'],
            'msg'               => $msg,
        ];
        if(!WalletService::WalletLogInsert($log_data))
        {
            return DataReturn(MyLang('common_service.orderaftersale.wallet_log_insert_fail_tips'), -101);
        }

        // 写入退款日志
        $refund_log = [
            'user_id'       => $order['user_id'],
            'order_id'      => $order['id'],
            'pay_price'     => $order['pay_price'],
            'trade_no'      => '',
            'buyer_user'    => '',
            'refund_price'  => $aftersale['price'],
            'msg'           => $msg,
            'pay_id'        => $pay_log['id'],
            'payment'       => $pay_log['payment'],
            'payment_name'  => $pay_log['payment_name'],
            'refundment'    => $params['refundment'],
            'business_type' => 1,
            'return_params' => '',
        ];
        RefundLogService::RefundLogInsert($refund_log);

        // 消息通知
        MessageService::MessageAdd($order['user_id'], MyLang('common_service.orderaftersale.wallet_log_refund_user_message_title'), $msg, MyLang('common_service.orderaftersale.refund_message_business_type_name'), $order['id']);

        return DataReturn(MyLang('refund_success'), 0);   
    }

    /**
     * 拒绝
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AftersaleRefuse($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'refuse_reason',
                'checked_data'      => '2,230',
                'error_msg'         => MyLang('form_refuse_reason_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'    => intval($params['id']),
        ];

        // 售后订单
        $aftersale = Db::name('OrderAftersale')->where($where)->find();
        if(empty($aftersale))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 状态校验
        if(!in_array($aftersale['status'], [0,2]))
        {
            $status_list = MyConst('common_order_aftersale_status_list');
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 数据
        $data = [
            'status'            => 4,
            'refuse_reason'     => $params['refuse_reason'],
            'audit_time'        => time(),
            'upd_time'          => time(),
        ];

        // 订单售后单拒绝前钩子
        $hook_name = 'plugins_service_order_aftersale_refuse_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data_id'       => $params['id'],
            'data'          => &$data,
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 处理数据
        Db::startTrans();
        try {
            // 更新数据
            if(!Db::name('OrderAftersale')->where($where)->update($data))
            {
                throw new \Exception(MyLang('refuse_fail'));
            }

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderAftersaleHistoryAdd($aftersale['id'], $data['status'], $aftersale['status'], MyLang('refuse_title'), $creator, $creator_name);

            // 订单售后单拒绝成功钩子
            $hook_name = 'plugins_service_order_aftersale_refuse_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            Db::commit();
            return DataReturn(MyLang('refuse_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AftersaleDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 售后订单
        $aftersale = Db::name('OrderAftersale')->where(['id' => intval($params['id'])])->find();
        if(empty($aftersale))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 状态校验
        if(!in_array($aftersale['status'], [4,5]))
        {
            $status_list = MyConst('common_order_aftersale_status_list');
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 删除操作
        if(Db::name('OrderAftersale')->where(['id' => intval($params['id'])])->delete())
        {
            // 删除日志
            Db::name('OrderAftersaleStatusHistory')->where(['order_aftersale_id' => intval($params['id'])])->delete();

            // 订单售后单删除成功钩子
            $hook_name = 'plugins_service_order_aftersale_delete_success';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回成功
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 订单售后退款退货计算
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-30
     * @desc    description
     * @param   [int]          $order_id        [订单id]
     * @param   [int]          $order_detail_id [订单详情id]
     */
    public static function OrderAftersaleCalculation($order_id, $order_detail_id)
    {
        // 可退数量/金额
        $returned_quantity = 0;
        $refund_price = 0.00;

        // 历史退货数量/金额
        $history_returned_quantity = 0;
        $history_refund_price = 0.00;

        // 未发生退货数量/金额
        $not_returned_quantity = 0;
        $not_refund_price = 0.00;
        
        // 获取订单数据
        $order = Db::name('Order')->where(['id'=>$order_id])->field('id,status,pay_status,buy_number_count,increase_price,preferential_price,price,total_price,pay_price,refund_price,returned_quantity')->find();
        if(!empty($order))
        {
            $dateil = Db::name('OrderDetail')->where(['order_id'=>$order_id])->field('id,price,total_price,buy_number,refund_price,returned_quantity')->select()->toArray();
            if(!empty($dateil))
            {
                // 如果只有个商品则直接使用支付金额
                if(count($dateil) == 1)
                {
                    $refund_price = $order['pay_price'];
                    $returned_quantity = $dateil[0]['buy_number'];
                } else {
                    foreach($dateil as $v)
                    {
                        // 从订单售后中获取进行中的数据
                        $where = [
                            ['order_detail_id', '=', $v['id']],
                            ['status', '<=', 2],
                        ];
                        $aftersale = Db::name('OrderAftersale')->where($where)->field('sum(number) as number, sum(price) as price')->find();
                        if(!empty($aftersale['number']))
                        {
                            $v['returned_quantity'] += $aftersale['number'];
                        }
                        if(!empty($aftersale['price']))
                        {
                            $v['refund_price'] += $aftersale['price'];
                        }

                        // 累计
                        $history_returned_quantity += $v['returned_quantity'];
                        $history_refund_price += $v['refund_price'];

                        // 当前指定详情
                        if($v['id'] == $order_detail_id)
                        {
                            $returned_quantity = $v['buy_number']-$v['returned_quantity'];
                            $refund_price = $v['price']*$returned_quantity;
                            if($refund_price+$v['refund_price'] > $v['total_price'])
                            {
                                $refund_price = $v['total_price']-$v['refund_price'];
                            }
                        } else {
                            // 未发生
                            if($v['returned_quantity'] <= 0 && $v['refund_price'] <= 0.00)
                            {
                                $not_returned_quantity += $v['buy_number'];
                                $not_refund_price += $v['total_price'];
                            }
                        }
                    }
                }
            }

            // 未发生售后
            if($not_returned_quantity > 0)
            {
                if(PriceNumberFormat($refund_price+$not_refund_price) > $order['price'])
                {
                    $refund_price -= $not_refund_price;
                }
            }

            // 如果最后一件退款则加上增加的金额，减去优惠金额
            if(PriceNumberFormat($history_refund_price+$refund_price) >= $order['price'])
            {
                $refund_price += $order['increase_price'];
                $refund_price -= $order['preferential_price'];
            }

            // 如果退款金额大于支付金额，则支付金额减去已退款金额
            $temp_price = PriceNumberFormat($refund_price+$history_refund_price);
            if($temp_price > $order['pay_price'])
            {
                $refund_price = $order['pay_price']-$history_refund_price;
            }

            // 防止负数
            if($refund_price <= 0)
            {
                $refund_price = 0.00;
            }
        }

        return DataReturn(MyLang('operate_success'), 0, ['returned_quantity'=>$returned_quantity, 'refund_price'=>PriceNumberFormat($refund_price)]);
    }

    /**
     * 订单售后提示信息
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-10-04T13:56:35+0800
     * @desc     description
     * @param    [array]             $orderaftersale [订单售后数据]
     */
    public static function OrderAftersaleTipsMsg($orderaftersale = [])
    {
        $msg_all = MyLang('common_service.orderaftersale.orderaftersale_step_tips_msg');
        if(!empty($msg_all) && is_array($msg_all) && isset($orderaftersale['status']) && array_key_exists($orderaftersale['status'], $msg_all))
        {
            // [status 待退货], [type 0仅退款 1退货退款
            if($orderaftersale['status'] == 1 && $orderaftersale['type'] == 0)
            {
                $msg_all[1] = $msg_all[0];
            }
            return $msg_all[$orderaftersale['status']];
        }
        return '';
    }

    /**
     * 订单售后进度
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-10-04T15:22:06+0800
     * @desc     description
     * @param    [array]             $orderaftersale [订单售后数据]
     */
    public static function OrderAftersaleStepData($orderaftersale)
    {
        $lang = MyLang('common_service.orderaftersale.orderaftersale_step_data');
        // 仅退款
        $step0 = [
            [
                'number'    => 1,
                'name'      => $lang[0]['add'],
                'is_caret'  => 1,
                'is_angle'  => 1,
                'is_active' => 1,
                'is_end'    => (empty($orderaftersale) || $orderaftersale['status'] > 3) ? 1 : 0,
            ],
            [
                'number'    => 2,
                'name'      => $lang[0]['audit'],
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2,3])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] <= 2) ? 1 : 0,
            ],
            [
                'number'    => 3,
                'name'      => $lang[0]['success'],
                'is_caret'  => 0,
                'is_angle'  => 0,
                'is_active' => (isset($orderaftersale['status']) && $orderaftersale['status'] == 3) ? 1 : 0,
                'is_end'    => 0,
            ]
        ];

        // 退货退款
        $step1 = [
            [
                'number'    => 1,
                'name'      => $lang[1]['add'],
                'is_caret'  => 1,
                'is_angle'  => 1,
                'is_active' => 1,
                'is_end'    => (empty($orderaftersale) || $orderaftersale['status'] > 3) ? 1 : 0,
            ],
            [
                'number'    => 2,
                'name'      => $lang[1]['confirm'],
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] == 0) ? 1 : 0,
            ],
            [
                'number'    => 3,
                'name'      => $lang[1]['delivery'],
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [1,2,3])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [1,2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] == 1) ? 1 : 0,
            ],
            [
                'number'    => 4,
                'name'      => $lang[1]['audit'],
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [2,3])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] == 2) ? 1 : 0,
            ],
            [
                'number'    => 5,
                'name'      => $lang[1]['success'],
                'is_caret'  => 0,
                'is_angle'  => 0,
                'is_active' => (isset($orderaftersale['status']) && $orderaftersale['status'] == 3) ? 1 : 0,
                'is_end'    => 0,
            ]
        ];
        return ['step0'=>$step0, 'step1'=>$step1];
    }

    /**
     * 订单是否可发起售后
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-24
     * @desc    description
     * @param   [int]          $collect_time [收货时间]
     */
    public static function OrderIsCanLaunchAftersale($collect_time)
    {
        // 售后周期、0则关闭售后
        $launch_day = intval(MyC('home_order_aftersale_return_launch_day', 30));
        if($launch_day <= 0)
        {
            return 0;
        }

        // 未收货
        if(empty($collect_time))
        {
            return 1;
        }

        // 是否超出限制时间
        $end_time = $collect_time+($launch_day*86400);
        return ($end_time >= time()) ? 1 : 0;
    }

    /**
     * 附件存储路径标识
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-14
     * @desc    description
     * @param   [int]          $user_id         [用户id]
     * @param   [int]          $order_id        [订单id]
     * @param   [int]          $order_detail_id [订单详情id]
     */
    public static function EditorAttachmentPathType($user_id, $order_id, $order_detail_id)
    {
        return 'order_aftersale-'.intval($user_id%(3*24)/24).'-'.$order_id.'-'.$order_detail_id;
    }
    
    /**
     * 商品退货地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-08
     * @desc    description
     * @param   [int]          $order_id [订单id]
     */
    public static function OrderAftersaleReturnGoodsAddress($order_id)
    {
        // 退货地址信息
        $name = MyC('home_order_aftersale_return_goods_contacts_name');
        $tel = MyC('home_order_aftersale_return_goods_contacts_tel');
        $address = MyC('home_order_aftersale_return_goods_address');

        // 是否是否仓库地址
        if(MyC('home_order_aftersale_is_use_warehouse_address', 0, true) == 1)
        {
            // 获取订单所属仓库id
            $warehouse_id = Db::name('Order')->where(['id'=>intval($order_id)])->value('warehouse_id');
            if(!empty($warehouse_id))
            {
                // 获取仓库信息
                $data_params = [
                    'm'             => 0,
                    'n'             => 1,
                    'where'         => [
                        ['id', '=', $warehouse_id],
                    ],
                ];
                $ret = WarehouseService::WarehouseList($data_params);
                $warehouse = (empty($ret['data']) || empty($ret['data'][0])) ? [] : $ret['data'][0];
                if(!empty($warehouse) && !empty($warehouse['contacts_name']) && !empty($warehouse['contacts_tel']) && !empty($warehouse['province_name']) && !empty($warehouse['city_name']) && !empty($warehouse['county_name']) && !empty($warehouse['address']))
                {
                    $name = $warehouse['contacts_name'];
                    $tel = $warehouse['contacts_tel'];
                    $address = $warehouse['province_name'].$warehouse['city_name'].$warehouse['county_name'].$warehouse['address'];
                }
            }
        }

        // 订单售后退货地址钩子
        $hook_name = 'plugins_service_order_aftersale_return_address';
        MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'order_id'    => $order_id,
            'name'        => &$name,
            'tel'         => &$tel,
            'address'     => &$address,
        ]);

        // 返回退货地址信息
        return [
            'name'     => $name,
            'tel'      => $tel,
            'address'  => $address,
        ];
    }
}
?>