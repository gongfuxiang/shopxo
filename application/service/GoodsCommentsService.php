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
use app\service\GoodsService;

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
     * 订单评论
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
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'rating',
                'error_msg'         => '评级有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'content',
                'error_msg'         => '评论内容有误',
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

        // 参数处理
        if(!is_array($params['goods_id']))
        {
            $params['goods_id'] = json_decode(htmlspecialchars_decode($params['goods_id']), true);
        }
        if(!is_array($params['rating']))
        {
            $params['rating'] = json_decode(htmlspecialchars_decode($params['rating']), true);
        }
        if(!is_array($params['content']))
        {
            $params['content'] = json_decode(htmlspecialchars_decode($params['content']), true);
        }

        // 评分
        if(min($params['rating']) <= 0)
        {
            return DataReturn('评级有误', -1);
        }
        if(min($params['rating']) <= 0 || max($params['rating']) > 5)
        {
            return DataReturn('评级有误', -1);
        }

        // 评论内容
        foreach($params['content'] as $v)
        {
            $len = mb_strlen($v, 'utf-8');
            if($len < 6 || $len > 230)
            {
                return DataReturn('评论内容 6~230 个字符之间', -1);
            }
        }

        // 附件处理
        if(!empty($params['images']))
        {
            if(!is_array($params['images']))
            {
                $params['images'] = json_decode(htmlspecialchars_decode($params['images']), true);
            }
            foreach($params['images'] as &$v)
            {
                if(!empty($v))
                {
                    foreach($v as &$vs)
                    {
                        $vs = ResourcesService::AttachmentPathHandle($vs);
                    }
                    if(count($v) > 3)
                    {
                        return DataReturn('每项评论图片不能超过3张', -1);
                    }
                }
            }
        }

        // 获取订单信息
        $order_id = intval($params['id']);
        $where = ['id'=>$order_id, 'user_id'=>$params['user']['id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,user_is_comments')->find();
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
            return DataReturn('该订单你已进行过评论', -10);
        }

        // 处理数据
        Db::startTrans();
        $is_anonymous = isset($params['is_anonymous']) ? min(1, intval($params['is_anonymous'])) : 0;
        foreach($params['goods_id'] as $k=>$goods_id)
        {
            $data = [
                'user_id'       => $params['user']['id'],
                'order_id'      => $order_id,
                'goods_id'      => $goods_id,
                'business_type' => $params['business_type'],
                'content'       => isset($params['content'][$k]) ? htmlspecialchars(trim($params['content'][$k])) : '',
                'images'        => empty($params['images'][$k]) ? '' : json_encode($params['images'][$k]),
                'rating'        => isset($params['rating'][$k]) ? intval($params['rating'][$k]) : 0,
                'is_anonymous'  => $is_anonymous,
                'add_time'      => time(),
            ];
            if(Db::name('GoodsComments')->insertGetId($data) <= 0)
            {
                Db::rollback();
                return DataReturn('评论内容添加失败', -100);
            }
        }

        // 订单评论状态更新
        if(!Db::name('Order')->where($where)->update(['user_is_comments'=>time(), 'upd_time'=>time()]))
        {
            Db::rollback();
            return DataReturn('订单更新失败', -101);
        }

        Db::commit();
        return DataReturn('评论成功', 0);
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
            $common_goods_comments_rating_list = lang('common_goods_comments_rating_list');
            $common_goods_rating_business_type_list = lang('common_goods_rating_business_type_list');
            foreach($data as &$v)
            {
                // 用户信息
                $user = UserService::GetUserViewInfo($v['user_id']);
                if(!isset($params['is_public']) || $params['is_public'] == 1)
                {
                    $v['user'] = [
                        'avatar'            => $user['avatar'],
                        'user_name_view'    => ($v['is_anonymous'] == 1) ? '匿名' : mb_substr($user['user_name_view'], 0, 3, 'utf-8').'***'.mb_substr($user['user_name_view'], -3, null, 'utf-8'),
                    ];
                } else {
                    $v['user'] = $user;
                }

                // 图片
                if(isset($v['images']))
                {
                    if(!empty($v['images']))
                    {
                        $images = json_decode($v['images'], true);
                        foreach($images as &$img)
                        {
                            $img = ResourcesService::AttachmentPathViewHandle($img);
                        }
                        $v['images'] = $images;
                    }
                }

                // 获取商品信息
                $goods_params = [
                    'where' => [
                        'id'                => $v['goods_id'],
                        'is_delete_time'    => 0,
                    ],
                    'field'  => 'id,title,images,price,min_price',
                ];
                $ret = GoodsService::GoodsList($goods_params);
                $v['goods'] = isset($ret['data'][0]) ? $ret['data'][0] : [];

                // 业务类型
                $v['business_type_text'] = array_key_exists($v['business_type'], $common_goods_rating_business_type_list) ? $common_goods_rating_business_type_list[$v['business_type']] : null;
                $msg = null;
                switch($v['business_type'])
                {
                    // 订单
                    case 'order' :
                        $msg = self::BusinessTypeOrderSpec($v['order_id'], $v['goods_id'], $v['user_id']);
                }
                $v['msg'] = empty($msg) ? null : $msg;

                // 评分
                $v['rating_text'] = $common_goods_comments_rating_list[$v['rating']]['name'];

                // 是否
                $v['is_reply_text'] = isset($common_is_text_list[$v['is_reply']]) ? $common_is_text_list[$v['is_reply']]['name'] : '';
                $v['is_anonymous_text'] = isset($common_is_text_list[$v['is_anonymous']]) ? $common_is_text_list[$v['is_anonymous']]['name'] : '';
                $v['is_show_text'] = isset($common_is_text_list[$v['is_show']]) ? $common_is_text_list[$v['is_show']]['name'] : '';

                // 回复时间
                $v['reply_time_time'] = empty($v['reply_time']) ? null : date('Y-m-d H:i:s', $v['reply_time']);
                $v['reply_time_date'] = empty($v['reply_time']) ? null : date('Y-m-d', $v['reply_time']);

                // 评论时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);

                // 更新时间
                $v['upd_time_time'] = empty($v['upd_time']) ? null : date('Y-m-d H:i:s', $v['upd_time']);
                $v['upd_time_date'] = empty($v['upd_time']) ? null : date('Y-m-d', $v['upd_time']);
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
            if(isset($params['is_show']) && $params['is_show'] > -1)
            {
                $where[] = ['is_show', '=', intval($params['is_show'])];
            }
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
                $where[] = ['business_type', '=', $params['business_type']];
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

    /**
     * 评论保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCommentsSave($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'business_type',
                'checked_data'      => array_keys(lang('common_order_aftersale_refundment_list')),
                'error_msg'         => '请选择业务类型',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '6,230',
                'error_msg'         => '评论内容 6~230 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'reply',
                'checked_data'      => '230',
                'error_msg'         => '回复内容最多 230 个字符',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'rating',
                'checked_data'      => array_keys(lang('common_goods_comments_rating_list')),
                'error_msg'         => '请选择评分',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始操作
        $data = [
            'content'           => $params['content'],
            'reply'             => $params['reply'],
            'business_type'     => $params['business_type'],
            'rating'            => intval($params['rating']),
            'reply_time'        => empty($params['reply_time']) ? 0 : strtotime($params['reply_time']),
            'is_reply'          => isset($params['is_reply']) ? intval($params['is_reply']) : 0,
            'is_show'           => isset($params['is_show']) ? intval($params['is_show']) : 0,
            'is_anonymous'      => isset($params['is_anonymous']) ? intval($params['is_anonymous']) : 0,
            'upd_time'          => time(),
        ];

        // 更新
        if(Db::name('GoodsComments')->where(['id'=>intval($params['id'])])->update($data))
        {
            return DataReturn('编辑成功', 0);
        }
        return DataReturn('编辑失败或数据不存在', -100); 
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCommentsDelete($params = [])
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

        // 开始删除
        if(Db::name('GoodsComments')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败或数据不存在', -100);
    }

    /**
     * 回复
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCommentsReply($params = [])
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
                'key_name'          => 'reply',
                'error_msg'         => '回复内容不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'reply',
                'checked_data'      => '1,230',
                'error_msg'         => '回复内容格式 1~230 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 评论是否存在
        $comments_id = Db::name('GoodsComments')->field('id')->find(intval($params['id']));
        if(empty($comments_id))
        {
            return DataReturn('资源不存在或已被删除', -2);
        }
        // 更新问答
        $data = [
            'reply'         => $params['reply'],
            'is_reply'      => 1,
            'reply_time'    => time(),
            'upd_time'      => time()
        ];
        if(Db::name('GoodsComments')->where(['id'=>$comments_id])->update($data))
        {
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCommentsStatusUpdate($params = [])
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
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'field',
                'checked_data'      => ['is_anonymous', 'is_show', 'is_reply'],
                'error_msg'         => '操作字段有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        $data = [
            $params['field']    => intval($params['state']),
            'upd_time'          => time(),
        ];
        if(Db::name('GoodsComments')->where(['id'=>intval($params['id'])])->update($data))
        {
            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 商品动态评分
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-15
     * @desc    description
     * @param   [int]           $goods_id [商品id]
     */
    public static function GoodsCommentsScore($goods_id)
    {
        // 默认
        $rating_list = [
            1 => ['rating'=>1, 'name'=>'1分', 'count'=>0, 'portion'=>0],
            2 => ['rating'=>2, 'name'=>'2分', 'count'=>0, 'portion'=>0],
            3 => ['rating'=>3, 'name'=>'3分', 'count'=>0, 'portion'=>0],
            4 => ['rating'=>4, 'name'=>'4分', 'count'=>0, 'portion'=>0],
            5 => ['rating'=>5, 'name'=>'5分', 'count'=>0, 'portion'=>0],
        ];
        $where = [
            ['goods_id', '=', $goods_id],
            ['rating', '>', 0],
        ];
        $data = Db::name('GoodsComments')->where($where)->group('rating')->column('count(*) as count, rating', 'rating');
        if(!empty($data))
        {
            $sum = array_sum($data);
            foreach($data as $rating=>$count)
            {
                if($rating > 0 && $rating <= 5)
                {
                    $rating_list[$rating]['count'] = $count;
                    $rating_list[$rating]['portion'] = round(($count/$sum)*100);
                }
            }
        }

        sort($rating_list);
        $result = [
            'avg'       => PriceNumberFormat(Db::name('GoodsComments')->where($where)->avg('rating'), 1),
            'rating'    => $rating_list,
        ];
        return DataReturn('操作成功', 0, $result);
    }
}
?>