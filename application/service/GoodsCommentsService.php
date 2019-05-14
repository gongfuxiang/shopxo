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
use app\service\UserService;

/**
 * 商品评论服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsCommentsService
{
    /**
     * 订单评价
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Comments($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'business_type',
                'error_msg'         => '业务类型标记不能为空',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品数据格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'rating',
                'error_msg'         => '评级有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'rating',
                'error_msg'         => '评级数据格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'content',
                'error_msg'         => '评价内容有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'content',
                'error_msg'         => '评价内容数据格式有误',
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

        // 获取订单信息
        $order_id = intval($params['id']);
        $where = ['id'=>$order_id, 'user_id'=>$params['user']['id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,shop_id,user_is_comments')->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if($order['status'] != 4)
        {
            $status_text = lang('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }
        if($order['user_is_comments'] != 0)
        {
            return DataReturn('该订单你已进行过评价', -10);
        }

        // 处理数据
        Db::startTrans();
        foreach($params['goods_id'] as $k=>$goods_id)
        {
            $data = [
                'user_id'       => $params['user']['id'],
                'shop_id'       => $order['shop_id'],
                'order_id'      => $order_id,
                'goods_id'      => $goods_id,
                'business_type' => $params['business_type'],
                'content'       => isset($params['content'][$k]) ? htmlspecialchars(trim($params['content'][$k])) : '',
                'rating'        => isset($params['rating'][$k]) ? intval($params['rating'][$k]) : 0,
                'is_anonymous'  => isset($params['is_anonymous']) ? min(1, intval($params['is_anonymous'])) : 0,
                'add_time'      => time(),
            ];
            if(Db::name('GoodsComments')->insertGetId($data) <= 0)
            {
                Db::rollback();
                return DataReturn('评价内容添加失败', -100);
            }
        }

        // 订单评价状态更新
        if(!Db::name('Order')->where($where)->update(['user_is_comments'=>time(), 'upd_time'=>time()]))
        {
            Db::rollback();
            return DataReturn('订单更新失败', -101);
        }

        Db::commit();
        return DataReturn('评价成功', 0);
    }

    /**
     * 获取商品评论列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCommentsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('GoodsComments')->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_is_text_list = lang('common_is_text_list');
            foreach($data as &$v)
            {
                // 用户信息
                $user = UserService::GetUserViewInfo($v['user_id']);
                if(!isset($params['is_public']) || $params['is_public'] == 1)
                {
                    $v['user'] = [
                        'avatar'            => $user['avatar'],
                        'user_name_view'    => ($v['is_anonymous'] == 1) ? '匿名' : substr($user['user_name_view'], 0, 3).'***'.substr($user['user_name_view'], -3),
                    ];
                } else {
                    $v['user'] = $user;
                }

                // 业务类型
                $msg = null;
                switch($v['business_type'])
                {
                    // 订单
                    case 'order' :
                        $msg = self::BusinessTypeOrderSpec($v['order_id'], $v['goods_id'], $v['user_id']);
                }
                $v['msg'] = empty($msg) ? null : $msg;

                // 是否
                $v['is_reply_text'] = isset($common_is_text_list[$v['is_reply']]) ? $common_is_text_list[$v['is_reply']]['name'] : '';
                $v['is_anonymous_text'] = isset($common_is_text_list[$v['is_anonymous']]) ? $common_is_text_list[$v['is_anonymous']]['name'] : '';

                // 评论时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);

                // 回复时间
                $v['reply_time_time'] = empty($v['reply_time']) ? null : date('Y-m-d H:i:s', $v['reply_time']);
                $v['reply_time_date'] = empty($v['reply_time']) ? null : date('Y-m-d', $v['reply_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 订单规格字符串处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-14
     * @desc    description
     * @param   [int]          $order_id    [订单id]
     * @param   [int]          $goods_id    [商品id]
     * @param   [int]           $user_id    [用户id]
     * @return  [string]                    [规格字符串]
     */
    private static function BusinessTypeOrderSpec($order_id, $goods_id, $user_id = 0)
    {
        $string = null;
        $spec = Db::name('OrderDetail')->where(['order_id'=>$order_id, 'goods_id'=>$goods_id])->value('spec');
        if(!empty($spec))
        {
            $spec = json_decode($spec, true);
            if(is_array($spec) && !empty($spec))
            {
                foreach($spec as $k=>$v)
                {
                    if($k > 0)
                    {
                        $string .= ' | ';
                    }
                    $string .= $v['type'].'：'.$v['value'];
                }
            }
        }
        return $string;
    }

    /**
     * 商品评论总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function GoodsCommentsTotal($where = [])
    {
        return (int) Db::name('GoodsComments')->where($where)->count();
    }

    /**
     * 商品评论列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCommentsListWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }
        
        // 关键字根据用户筛选,商品标题
        if(!empty($params['keywords']))
        {
            if(empty($params['user']))
            {
                $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
                if(!empty($user_ids))
                {
                    $where[] = ['user_id', 'in', $user_ids];
                } else {
                    // 无数据条件，走商品
                    $goods_ids = Db::name('Goods')->where('title', 'like', '%'.$params['keywords'].'%')->column('id');
                    if(!empty($goods_ids))
                    {
                        $where[] = ['goods_id', 'in', $goods_ids];
                    } else {
                        $where[] = ['id', '=', 0];
                    }
                }
            }
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_anonymous']) && $params['is_anonymous'] > -1)
            {
                $where[] = ['is_anonymous', '=', intval($params['is_anonymous'])];
            }
            if(isset($params['is_reply']) && $params['is_reply'] > -1)
            {
                $where[] = ['is_reply', '=', intval($params['is_reply'])];
            }
            if(!empty($params['business_type']))
            {
                $where[] = ['business_type', '='. $params['business_type']];
            }


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
}
?>