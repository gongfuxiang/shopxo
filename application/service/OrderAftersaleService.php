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
     * @param   [int]          $order_id [订单id]
     * @param   [int]          $goods_id [商品id]
     * @param   [int]          $user_id  [用户id]
     */
    public static function OrdferGoodsRow($order_id, $goods_id, $user_id)
    {
        // 获取列表
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => [
                'id'                => intval($order_id),
                'user_id'           => intval($user_id),
                'is_delete_time'    => 0,
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
                    if($goods_id == $v['goods_id'])
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
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
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
                'checked_data'      => '5,200',
                'error_msg'         => '退款说明 5~200 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'images',
                'data_type'         => 'array',
                'is_checked'        => 1,
                'checked_data'      => '3',
                'error_msg'         => '凭证图片不能超过3张',
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
        $order = self::OrdferGoodsRow($params['order_id'], $params['goods_id'], $params['user']['id']);
        if($order['code'] != 0)
        {
            return $order;
        }

        // 当前是否存在进行中
        $where = [
            ['order_id', '=', intval($params['order_id'])],
            ['goods_id', '=', intval($params['goods_id'])],
            ['user_id', '=', $params['user']['id']],
            ['status', '<=', 2],
        ];
        $count = (int) Db::name('OrderAftersale')->where($where)->count();
        if($count > 0)
        {
            return DataReturn('当前订单商品售后正在进行中，请勿重复申请', -1);
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
        if($price+$history_price > $order['data']['pay_price'])
        {
            return DataReturn('退款金额大于支付金额[ 历史退款 '.$history_price.' ]', -1);
        }

        // 退货数量
        $number = isset($params['number']) ? intval($params['number']) : 0;

        // 历史退货数量
        $where[] = ['goods_id', '=', intval($params['goods_id'])];
        $history_number = (int) Db::name('OrderAftersale')->where($where)->sum('number');
        if($params['type'] == 1)
        {
            if($number+$history_number > $order['data']['items']['buy_number'])
            {
                return DataReturn('退货数量大于购买数量[ 历史退货数量 '.$history_number.' ]', -1);
            }
        }

        // 附件处理
        $images = [];
        if(!empty($params['images']) && is_array($params['images']))
        {
            foreach($params['images'] as $v)
            {
                $images[] = ResourcesService::AttachmentPathHandle($v);
            }
        }

        // 数据
        $data = [
            'order_no'      => $order['data']['order_no'],
            'type'          => intval($params['type']),
            'order_id'      => intval($params['order_id']),
            'goods_id'      => intval($params['goods_id']),
            'user_id'       => $params['user']['id'],
            'number'        => ($params['type'] == 0) ? 0 : $number,
            'price'         => $price,
            'reason'        => $params['reason'],
            'msg'           => $params['msg'],
            'images'        => json_encode($images),
            'status'        => ($params['type'] == 0) ? 2 : 0,
            'add_time'      => time(),
            'apply_time'    => time(),
        ];
        if(Db::name('OrderAftersale')->insertGetId($data) > 0)
        {
            return DataReturn('申请成功', 0);
        }
        return DataReturn('申请失败', -100);
    }

    /**
     * 
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
        if(Db::name('OrderAftersale')->where($where)->update($data))
        {
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
                $order = self::OrdferGoodsRow($v['order_id'], $v['goods_id'], $v['user_id']);
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
                    $where[] = ['order_no', '=', $params['keywords']];
                }
            } else {
                // 用户走关键字
                $where[] = ['order_no', '=', $params['keywords']];
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

        // 数据更新
        if(Db::name('OrderAftersale')->where($where)->update(['status'=>5, 'cancel_time'=>time(), 'upd_time'=>time()]))
        {
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

        // 数据更新
        if(Db::name('OrderAftersale')->where($where)->update(['status'=>1, 'confirm_time'=>time(), 'upd_time'=>time()]))
        {
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
        $order = self::OrdferGoodsRow($aftersale['order_id'], $aftersale['goods_id'], $aftersale['user_id']);
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
        if($aftersale['price']+$history_price > $order['data']['pay_price'])
        {
            return DataReturn('退款金额大于支付金额[ 历史退款 '.$history_price.' ]', -1);
        }

        // 历史退货数量
        $where[] = ['goods_id', '=', $aftersale['goods_id']];
        $history_number = (int) Db::name('OrderAftersale')->where($where)->sum('number');
        if($aftersale['type'] == 1)
        {
            if($aftersale['number']+$history_number > $order['data']['items']['buy_number'])
            {
                return DataReturn('退货数量大于购买数量[ 历史退货数量 '.$history_number.' ]', -1);
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

        // 钱包校验
        if($params['refundment'] == 1)
        {
            $wallet = Db::name('Plugins')->where(['plugins'=>'wallet'])->find();
            if(empty($wallet))
            {
                return DataReturn('请先安装钱包插件[ Wallet ]', -1);
            }
        }

        // 退款方式
        $ret = DataReturn('退款方式未定义', -100);
        switch($params['refundment'])
        {
            // 原路退回
            case 0 :
                $ret = self::OriginalRoadRefundment($params, $aftersale, $order['data'], $pay_log);
                break;

            // 退至钱包
            case 1 :
                $ret = self::WalletRefundment($params, $aftersale, $order['data'], $pay_log);
                break;

            // 手动处理
            case 2 :
                $ret = DataReturn('退款成功', 0);
                break;
        }

        // 退款成功
        if($ret['code'] == 0)
        {
            // 开启事务
            Db::startTrans();
            $upd_data = [
                'status'        => 6,
                'pay_status'    => 2,
                'close_time'    => time(),
                'upd_time'      => time(),
            ];
            if(Db::name('Order')->where(['id'=>$order['data']['id']])->update($upd_data))
            {
                // 库存回滚
                $ret = BuyService::OrderInventoryRollback(['order_id'=>$order['data']['id'], 'order_data'=>$upd_data]);
                if($ret['code'] != 0)
                {
                    // 事务回滚
                    Db::rollback();
                    return DataReturn($ret['msg'], -10);
                }

                // 消息通知
                $detail = '订单退款成功，金额'.PriceBeautify($aftersale['price']).'元';
                MessageService::MessageAdd($order['data']['user_id'], '订单退款', $detail, 1, $order['data']['id']);

                // 订单状态日志
                $creator = isset($params['creator']) ? intval($params['creator']) : 0;
                $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
                OrderService::OrderHistoryAdd($order['data']['id'], $upd_data['status'], $order['data']['status'], '关闭', $creator, $creator_name);

                // 更新退款状态
                $upd_data = [
                    'status'        => 3,
                    'audit_time'    => time(),
                    'upd_time'      => time(),
                ];
                if(!Db::name('OrderAftersale')->where(['id'=>$aftersale['id']])->update($upd_data))
                {
                    return DataReturn('售后订单更新失败', -60);
                }

                // 提交事务
                Db::commit();
                return DataReturn('退款成功', 0);
            }

            // 事务回滚
            Db::rollback();
            return DataReturn('退款失败', -1);
        }
        return $ret;
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

        // 操作退款
        $pay_name = 'payment\\'.$pay_log['payment'];
        $pay_params = [
            'order_no'          => $order['order_no'],
            'trade_no'          => $pay_log['trade_no'],
            'refund_amount'     => $aftersale['price'],
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
            'total_price'   => $order['total_price'],
            'trade_no'      => isset($ret['data']['trade_no']) ? $ret['data']['trade_no'] : '',
            'buyer_user'    => isset($ret['data']['buyer_user_id']) ? $ret['data']['buyer_user_id'] : '',
            'refund_price'  => $aftersale['price'],
            'msg'           => $pay_params['refund_reason'],
            'payment'       => $pay_log['payment'],
            'payment_name'  => $pay_log['payment_name'],
            'business_type' => 1,
            'return_params' => $ret['data'],
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
        return DataReturn('开发中', -10);
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
        return DataReturn('开发中', -10);
    }
}
?>