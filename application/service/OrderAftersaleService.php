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
namespace app\service;

use think\Db;
use think\facade\Hook;
use app\service\UserService;
use app\service\ResourcesService;
use app\service\RefundLogService;
use app\service\OrderService;
use app\service\MessageService;
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
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => [
                'id'                => intval($order_id),
                'user_id'           => intval($user_id),
            ],
        );
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
        return DataReturn('没有相关数据', -100);
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
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_detail_id',
                'error_msg'         => '订单详情id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => [0,1],
                'error_msg'         => '操作类型有误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'price',
                'checked_data'      => 'CheckPrice',
                'error_msg'         => '退款金额格式有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'reason',
                'checked_data'      => '180',
                'error_msg'         => '退款原因最多 180 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'msg',
                'checked_data'      => '200',
                'error_msg'         => '退款说明最多 200 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
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
            return DataReturn('订单售后正在进行中，请勿重复申请', -1);
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
            return DataReturn('退款金额大于支付金额[ 历史退款'.$history_price.'元, 订单金额'.$order['data']['pay_price'].'元 ]', -1);
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
                return DataReturn('退货数量大于购买数量[ 历史退货数量 '.$history_number.', 订单商品数量 '.$order['data']['items']['buy_number'].' ]', -1);
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
                return DataReturn('凭证图片不能超过3张', -1);
            }
        }

        // 数据
        $data = [
            'order_no'          => $order['data']['order_no'],
            'type'              => intval($params['type']),
            'order_detail_id'   => intval($params['order_detail_id']),
            'order_id'          => intval($params['order_id']),
            'goods_id'          => $order['data']['items']['goods_id'],
            'user_id'           => $params['user']['id'],
            'number'            => ($params['type'] == 0) ? 0 : $number,
            'price'             => $price,
            'reason'            => $params['reason'],
            'msg'               => $params['msg'],
            'images'            => json_encode($images),
            'status'            => ($params['type'] == 0) ? 2 : 0,
            'add_time'          => time(),
            'apply_time'        => time(),
        ];

        // 订单售后添加前钩子
        $hook_name = 'plugins_service_order_aftersale_insert_begin';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 数据添加
        $data_id = Db::name('OrderAftersale')->insertGetId($data);
        if($data_id > 0)
        {
            // 订单售后添加成功钩子
            $hook_name = 'plugins_service_order_aftersale_insert_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
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

            // 返回成功
            return DataReturn('申请成功', 0);
        }
        return DataReturn('申请失败', -100);
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'express_name',
                'checked_data'      => '1,60',
                'error_msg'         => '快递名称格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'express_number',
                'checked_data'      => '1,60',
                'error_msg'         => '快递单号格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
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
            return DataReturn('数据不存在或已删除', -1);
        }

        // 状态
        if($aftersale['type'] == 0)
        {
            return DataReturn('该售后订单为仅退款，不能操作退货操作', -1);
        }
        if($aftersale['status'] != 1)
        {
            $common_order_aftersale_status_list = lang('common_order_aftersale_status_list');
            return DataReturn('该售后订单状态不可操作['.$common_order_aftersale_status_list[$aftersale['status']]['name'].']', -10);
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
        $ret = HookReturnHandle(Hook::listen($hook_name, [
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

        // 数据更新
        if(Db::name('OrderAftersale')->where($where)->update($data))
        {
            // 订单售后退货成功钩子
            $hook_name = 'plugins_service_order_aftersale_delivery_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回成功
            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', -100);
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
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('OrderAftersale')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_order_aftersale_type_list = lang('common_order_aftersale_type_list');
            $common_order_aftersale_status_list = lang('common_order_aftersale_status_list');
            $common_order_aftersale_refundment_list = lang('common_order_aftersale_refundment_list');
            foreach($data as &$v)
            {
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
                $v['type_text'] = $common_order_aftersale_type_list[$v['type']]['name'];

                // 状态
                $v['status_text'] = $common_order_aftersale_status_list[$v['status']]['name'];

                // 退款方式
                $v['refundment_text'] = $common_order_aftersale_refundment_list[$v['refundment']]['name'];

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
                    $v['images'] = null;
                }

                // 申请时间
                $v['apply_time_time'] = empty($v['apply_time']) ? null : date('Y-m-d H:i:s', $v['apply_time']);
                $v['apply_time_date'] = empty($v['apply_time']) ? null : date('Y-m-d', $v['apply_time']);

                // 确认时间
                $v['confirm_time_time'] = empty($v['confirm_time']) ? null : date('Y-m-d H:i:s', $v['confirm_time']);
                $v['confirm_time_date'] = empty($v['confirm_time']) ? null : date('Y-m-d', $v['confirm_time']);

                // 退货时间
                $v['delivery_time_time'] = empty($v['delivery_time']) ? null : date('Y-m-d H:i:s', $v['delivery_time']);
                $v['delivery_time_date'] = empty($v['delivery_time']) ? null : date('Y-m-d', $v['delivery_time']);

                // 审核时间
                $v['audit_time_time'] = empty($v['audit_time']) ? null : date('Y-m-d H:i:s', $v['audit_time']);
                $v['audit_time_date'] = empty($v['audit_time']) ? null : date('Y-m-d', $v['audit_time']);

                // 取消时间
                $v['cancel_time_time'] = empty($v['cancel_time']) ? null : date('Y-m-d H:i:s', $v['cancel_time']);
                $v['cancel_time_date'] = empty($v['cancel_time']) ? null : date('Y-m-d', $v['cancel_time']);

                // 添加时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);

                // 更新时间
                $v['upd_time_time'] = empty($v['upd_time']) ? null : date('Y-m-d H:i:s', $v['upd_time']);
                $v['upd_time_date'] = empty($v['upd_time']) ? null : date('Y-m-d', $v['upd_time']);
                
            }
        }
        return DataReturn('获取成功', 0, $data);
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
                $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
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
                $where[] = ['status', '=', intval($params['status'])];
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
        return (int) Db::name('OrderAftersale')->where($where)->count();
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'is_checked'        => 2,
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'        => intval($params['id']),
        ];
        if(!empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        // 售后订单
        $aftersale = Db::name('OrderAftersale')->where($where)->find();
        if(empty($aftersale))
        {
            return DataReturn('数据不存在或已删除', -1);
        }

        // 状态校验
        if(in_array($aftersale['status'], [3,5]))
        {
            $status_list = lang('common_order_aftersale_status_list');
            return DataReturn('状态不可操作['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 数据
        $data = [
            'status'        => 5,
            'cancel_time'   => time(),
            'upd_time'      => time()
        ];

        // 订单售后单取消前钩子
        $hook_name = 'plugins_service_order_aftersale_cacnel_begin';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
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

        // 更新数据
        if(Db::name('OrderAftersale')->where($where)->update($data))
        {
            // 订单售后取消成功钩子
            $hook_name = 'plugins_service_order_aftersale_cacnel_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回成功
            return DataReturn('取消成功');
        }
        return DataReturn('取消失败', -100);
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
                'error_msg'         => '操作id有误',
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
            return DataReturn('数据不存在或已删除', -1);
        }

        // 状态校验
        if($aftersale['status'] != 0)
        {
            $status_list = lang('common_order_aftersale_status_list');
            return DataReturn('状态不可操作['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 类型
        if($aftersale['type'] != 1)
        {
            $aftersale_type_list = lang('common_order_aftersale_type_list');
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
        $ret = HookReturnHandle(Hook::listen($hook_name, [
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

        // 更新数据
        if(Db::name('OrderAftersale')->where($where)->update($data))
        {
            // 订单售后单确认成功钩子
            $hook_name = 'plugins_service_order_aftersale_confirm_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回成功
            return DataReturn('确认成功');
        }
        return DataReturn('确认失败', -100);
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'refundment',
                'checked_data'      => array_column(lang('common_order_aftersale_refundment_list'), 'value'),
                'error_msg'         => '退款方式有误',
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
            return DataReturn('数据不存在或已删除', -1);
        }

        // 状态校验
        if($aftersale['status'] != 2)
        {
            $status_list = lang('common_order_aftersale_status_list');
            return DataReturn('状态不可操作['.$status_list[$aftersale['status']]['name'].']', -1);
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
            return DataReturn('退款金额大于支付金额[ 历史退款'.$history_price.'元, 订单金额'.$order['data']['pay_price'].'元 ]', -1);
        }

        // 历史退货数量
        $where[] = ['order_detail_id', '=', $aftersale['order_detail_id']];
        $history_number = (int) Db::name('OrderAftersale')->where($where)->sum('number');
        if($aftersale['type'] == 1)
        {
            if(intval($aftersale['number']+$history_number) > $order['data']['items']['buy_number'])
            {
                return DataReturn('退货数量大于购买数量[ 历史退货数量 '.$history_number.', 订单商品数量 '.$order['data']['items']['buy_number'].' ]', -1);
            }
        }

        // 订单支付方式校验
        $pay_log = Db::name('PayLog')->where(['order_id'=>$order['data']['id'], 'business_type'=>1])->find();

        // 手动处理不校验支付日志
        if($params['refundment'] != 2)
        {
            if(empty($pay_log))
            {
                return DataReturn('支付日志不存在，请使用手动处理方式', -1);
            }
        }

        // 原路退回
        if($params['refundment'] == 0)
        {
            if(in_array($pay_log['payment'], config('shopxo.under_line_list')))
            {
                return DataReturn('线下支付方式不能原路退回[ '.$pay_log['payment_name'].' ]', -1);
            } else {
                $payment = 'payment\\'.$pay_log['payment'];
                if(class_exists($payment))
                {
                    if(!method_exists((new $payment()), 'Refund'))
                    {
                        return DataReturn('支付插件没退款功能[ '.$pay_log['payment'].' ]', -1);
                    }
                } else {
                    return DataReturn('支付插件不存在[ '.$pay_log['payment'].' ]', -1);
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
            $refund = DataReturn('退款成功', 0);
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
                return DataReturn('请先安装钱包插件[ Wallet ]', -1);
            }
        }

        // 开启事务
        Db::startTrans();

        // 钱包退款
        if($is_walet === true)
        {
            $ret = self::WalletRefundment($params, $aftersale, $order['data'], $pay_log);
            if($ret['code'] != 0)
            {
                Db::rollback();
                return $ret;
            }
        }

        // 是否仅退款操作需要退数量操作
        $is_refund_only_number = false;
        if($aftersale['type'] == 0 && $order['data']['status'] <= 2)
        {
            $is_refund_only_number = true;
            $aftersale['number'] = $order['data']['items']['buy_number'];
        }

        // 更新主订单
        $refund_price = PriceNumberFormat($order['data']['refund_price']+$aftersale['price']);
        $returned_quantity = intval($order['data']['returned_quantity']+$aftersale['number']);
        $order_upd_data = [
            'pay_status'        => ($refund_price >= $order['data']['pay_price']) ? 2 : 3,
            'refund_price'      => $refund_price,
            'returned_quantity' => $returned_quantity,
            'close_time'        => time(),
            'upd_time'          => time(),
        ];

        // 如果退款金额和退款数量到达订单实际是否金额和购买数量则关闭订单
        if($refund_price >= $order['data']['pay_price'] && $returned_quantity >= $order['data']['buy_number_count'])
        {
            $order_upd_data['status'] = 6;
        }
        
        // 更新主订单
        if(!Db::name('Order')->where(['id'=>$order['data']['id']])->update($order_upd_data))
        {
            Db::rollback();
            return DataReturn('主订单更新失败', -1);
        }

        // 订单详情
        $detail_upd_data = [
            'refund_price'      => PriceNumberFormat($order['data']['items']['refund_price']+$aftersale['price']),
            'returned_quantity' => intval($order['data']['items']['returned_quantity']+$aftersale['number']),
            'upd_time'          => time(),
        ];
        if(!Db::name('OrderDetail')->where(['id'=>$aftersale['order_detail_id']])->update($detail_upd_data))
        {
            Db::rollback();
            return DataReturn('订单详情更新失败', -1);
        }

        // 库存回滚
        if($aftersale['type'] == 1 || $is_refund_only_number == true)
        {
            $ret = BuyService::OrderInventoryRollback(['order_id'=>$order['data']['id'], 'order_data'=>$order_upd_data, 'appoint_order_detail_id'=>$aftersale['order_detail_id'], 'appoint_buy_number'=>$aftersale['number']]);
            if($ret['code'] != 0)
            {
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }
        }

        // 消息通知
        $detail = '订单退款成功，金额'.PriceBeautify($aftersale['price']).'元';
        MessageService::MessageAdd($order['data']['user_id'], '订单退款', $detail, 1, $order['data']['id']);

        // 订单状态日志
        if(isset($order_upd_data['status']))
        {
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            OrderService::OrderHistoryAdd($order['data']['id'], $order_upd_data['status'], $order['data']['status'], '关闭', $creator, $creator_name);
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
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data_id'       => $aftersale['id'],
            'data'          => &$data,
            'order_id'      => $order['data']['id'],
            'params'        => $params,
            
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 数据更新
        if(!Db::name('OrderAftersale')->where(['id'=>$aftersale['id']])->update($data))
        {
            Db::rollback();
            return DataReturn('售后订单更新失败', -60);
        }

        // 订单售后审核处理完毕钩子
        $hook_name = 'plugins_service_order_aftersale_audit_handle_end';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'order_id'      => $order['data']['id'],
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            Db::rollback();
            return $ret;
        }

        // 提交事务
        Db::commit();
        return DataReturn('退款成功', 0);
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
        $payment = PaymentService::PaymentList(['where'=>['payment'=>$pay_log['payment']]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 交易平台单号
        if(empty($pay_log['trade_no']))
        {
            return DataReturn('平台单号为空，请确认支付日志是否存在', -1);
        }

        // 操作退款
        $pay_name = 'payment\\'.$pay_log['payment'];
        $pay_params = [
            'order_id'          => $order['id'],
            'order_no'          => $order['order_no'],
            'trade_no'          => $pay_log['trade_no'],
            'pay_price'         => $order['pay_price'],
            'refund_price'      => $aftersale['price'],
            'client_type'       => $order['client_type'],
            'refund_reason'     => $order['order_no'].'订单退款'.$aftersale['price'].'元',
        ];
        $ret = (new $pay_name($payment[0]['config']))->Refund($pay_params);
        if(!isset($ret['code']))
        {
            return DataReturn('支付插件退款处理有误', -1);
        }
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 写入退款日志
        $refund_log = [
            'user_id'       => $order['user_id'],
            'order_id'      => $order['id'],
            'pay_price'     => $order['pay_price'],
            'trade_no'      => isset($ret['data']['trade_no']) ? $ret['data']['trade_no'] : '',
            'buyer_user'    => isset($ret['data']['buyer_user']) ? $ret['data']['buyer_user'] : '',
            'refund_price'  => isset($ret['data']['refund_price']) ? $ret['data']['refund_price'] : '',
            'msg'           => $pay_params['refund_reason'],
            'payment'       => $pay_log['payment'],
            'payment_name'  => $pay_log['payment_name'],
            'refundment'    => $params['refundment'],
            'business_type' => 1,
            'return_params' => isset($ret['data']['return_params']) ? $ret['data']['return_params'] : '',
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
        if(!Db::name('PluginsWallet')->where(['id'=>$user_wallet['data']['id']])->update($data))
        {
            return DataReturn('钱包更新失败', -10);
        }

        // 钱包变更日志
        $msg = $order['order_no'].'订单退款'.$aftersale['price'].'元';
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
            return DataReturn('钱包日志添加失败', -101);
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
            'payment'       => $pay_log['payment'],
            'payment_name'  => $pay_log['payment_name'],
            'refundment'    => $params['refundment'],
            'business_type' => 1,
            'return_params' => '',
        ];
        RefundLogService::RefundLogInsert($refund_log);

        // 消息通知
        MessageService::MessageAdd($order['user_id'], '账户余额变动', $msg, 1, $order['id']);

        return DataReturn('退款成功', 0);   
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'refuse_reason',
                'checked_data'      => '2,230',
                'error_msg'         => '拒绝原因格式 2~230 个字符',
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
            return DataReturn('数据不存在或已删除', -1);
        }

        // 状态校验
        if(!in_array($aftersale['status'], [0,2]))
        {
            $status_list = lang('common_order_aftersale_status_list');
            return DataReturn('状态不可操作['.$status_list[$aftersale['status']]['name'].']', -1);
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
        $ret = HookReturnHandle(Hook::listen($hook_name, [
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

        // 更新数据
        if(Db::name('OrderAftersale')->where(['id' => intval($params['id'])])->update($data))
        {
            // 订单售后单拒绝成功钩子
            $hook_name = 'plugins_service_order_aftersale_refuse_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data_id'       => $params['id'],
                'data'          => $data,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回成功
            return DataReturn('拒绝成功', 0);
        }
        return DataReturn('拒绝失败', -100);
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
                'error_msg'         => '操作id有误',
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
            return DataReturn('数据不存在或已删除', -1);
        }

        // 状态校验
        if(!in_array($aftersale['status'], [4,5]))
        {
            $status_list = lang('common_order_aftersale_status_list');
            return DataReturn('状态不可操作['.$status_list[$aftersale['status']]['name'].']', -1);
        }

        // 删除操作
        if(Db::name('OrderAftersale')->where(['id' => intval($params['id'])])->delete())
        {
            // 订单售后单删除成功钩子
            $hook_name = 'plugins_service_order_aftersale_delete_success';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
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
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
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
            $dateil = Db::name('OrderDetail')->where(['order_id'=>$order_id])->field('id,price,total_price,buy_number,refund_price,returned_quantity')->select();
            if(!empty($dateil))
            {
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

        return DataReturn('操作成功', 0, ['returned_quantity'=>$returned_quantity, 'refund_price'=>PriceNumberFormat($refund_price)]);
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
        $msg_all = [
            0 => '订单售后已提交申请，等待管理员确认中！',
            1 => '订单售后，管理员已确认，请尽快完成退货！',
            2 => '订单售后已退货，等待管理员审核中！',
            3 => '订单售后已处理结束！',
            4 => '订单售后申请已被拒绝！',
            5 => '订单售后申请已关闭！',
        ];
        if(isset($orderaftersale['status']) && array_key_exists($orderaftersale['status'], $msg_all))
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
    public static function OrderAftersaleStep($orderaftersale)
    {
        // 仅退款
        $step0 = [
            [
                'number'    => 1,
                'name'      => '申请仅退款',
                'is_caret'  => 1,
                'is_angle'  => 1,
                'is_active' => 1,
                'is_end'    => (empty($orderaftersale) || $orderaftersale['status'] > 3) ? 1 : 0,
            ],
            [
                'number'    => 2,
                'name'      => '管理员审核',
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2,3])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] <= 2) ? 1 : 0,
            ],
            [
                'number'    => 3,
                'name'      => '退款完毕',
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
                'name'      => '申请退货退款',
                'is_caret'  => 1,
                'is_angle'  => 1,
                'is_active' => 1,
                'is_end'    => (empty($orderaftersale) || $orderaftersale['status'] > 3) ? 1 : 0,
            ],
            [
                'number'    => 2,
                'name'      => '管理员确认',
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [0,1,2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] == 0) ? 1 : 0,
            ],
            [
                'number'    => 3,
                'name'      => '用户退货',
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [1,2,3])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [1,2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] == 1) ? 1 : 0,
            ],
            [
                'number'    => 4,
                'name'      => '管理员审核',
                'is_caret'  => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [2,3])) ? 1 : 0,
                'is_angle'  => 1,
                'is_active' => (isset($orderaftersale['status']) && in_array($orderaftersale['status'], [2,3])) ? 1 : 0,
                'is_end'    => (isset($orderaftersale['status']) && $orderaftersale['status'] == 2) ? 1 : 0,
            ],
            [
                'number'    => 5,
                'name'      => '退款完毕',
                'is_caret'  => 0,
                'is_angle'  => 0,
                'is_active' => (isset($orderaftersale['status']) && $orderaftersale['status'] == 3) ? 1 : 0,
                'is_end'    => 0,
            ]
        ];
        
        return ['step0'=>$step0, 'step1'=>$step1];
    }
}
?>