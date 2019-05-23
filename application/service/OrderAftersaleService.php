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

/**
 * 订单售后服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OrderAftersaleService
{
    // 订单售后状态
    public static $order_aftersale_status_list = [
        0 => '待确认',
        1 => '待退货',
        2 => '待审核',
        3 => '已完成',
        4 => '已拒绝',
        5 => '已取消',
    ];

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
            ['status', '<=', 1],
        ];
        $count = (int) Db::name('OrderAftersale')->where($where)->count();
        if($count > 0)
        {
            return DataReturn('当前订单商品售后正在进行中，请勿重复申请', -1);
        }

        // 获取历史申请售后条件
        $where = [
            ['order_id', '=', intval($params['order_id'])],
            ['goods_id', '=', intval($params['goods_id'])],
            ['user_id', '=', $params['user']['id']],
            ['status', '<=', 2],
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
            'type'          => intval($params['type']),
            'order_id'      => intval($params['order_id']),
            'goods_id'      => intval($params['goods_id']),
            'user_id'       => $params['user']['id'],
            'number'        => ($params['type'] == 0) ? 0 : $number,
            'price'         => $price,
            'reason'        => $params['reason'],
            'msg'           => $params['msg'],
            'images'        => json_encode($images),
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
            return DataReturn('该售后订单状态不可操作['.self::$order_aftersale_status_list[$aftersale['status']].']', -10);
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
     * 获取订单商品售后纪录列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderGoodsAftersaleList($params = [])
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
            foreach($data as &$v)
            {

            }
        }
        return DataReturn('获取成功', 0, $data);
    }
}
?>