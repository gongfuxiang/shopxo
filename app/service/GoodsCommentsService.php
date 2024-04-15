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
use app\service\GoodsService;
use app\service\SystemBaseService;

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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'business_type',
                'checked_data'      => array_keys(MyConst('common_goods_comments_business_type_list')),
                'error_msg'         => MyLang('common_service.goodscomments.form_item_business_type_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => MyLang('goods_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'rating',
                'error_msg'         => MyLang('common_service.goodscomments.save_rating_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'content',
                'error_msg'         => MyLang('common_service.goodscomments.save_content_empty_tips'),
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
            return DataReturn(MyLang('common_service.goodscomments.form_item_rating_message'), -1);
        }
        if(min($params['rating']) <= 0 || max($params['rating']) > 5)
        {
            return DataReturn(MyLang('common_service.goodscomments.form_item_rating_message'), -1);
        }

        // 评论内容
        foreach($params['content'] as $v)
        {
            $len = mb_strlen($v, 'utf-8');
            if($len < 6 || $len > 230)
            {
                return DataReturn(MyLang('common_service.goodscomments.form_item_content_message'), -1);
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
                        return DataReturn(MyLang('common_service.goodscomments.form_item_images_message'), -1);
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
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }
        if($order['status'] != 4)
        {
            $status_text = MyConst('common_order_status')[$order['status']]['name'];
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.']', -1);
        }
        if($order['user_is_comments'] != 0)
        {
            return DataReturn(MyLang('common_service.goodscomments.save_order_already_comments_tips'), -10);
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
                'content'       => isset($params['content'][$k]) ? str_replace(['"', "'", '&quot', '&lt;', '&gt;'], '', htmlspecialchars(trim($params['content'][$k]))) : '',
                'images'        => empty($params['images'][$k]) ? '' : json_encode($params['images'][$k]),
                'rating'        => isset($params['rating'][$k]) ? intval($params['rating'][$k]) : 0,
                'is_anonymous'  => $is_anonymous,
                'add_time'      => time(),
            ];
            if(Db::name('GoodsComments')->insertGetId($data) <= 0)
            {
                Db::rollback();
                return DataReturn(MyLang('common_service.goodscomments.save_comments_add_fail_tips'), -100);
            }
        }

        // 订单评论状态更新
        if(!Db::name('Order')->where($where)->update(['user_is_comments'=>time(), 'upd_time'=>time()]))
        {
            Db::rollback();
            return DataReturn(MyLang('common_service.goodscomments.save_order_comments_update_tail_tips'), -101);
        }

        Db::commit();
        return DataReturn(MyLang('comments_success'), 0);
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
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据列表
        $data = Db::name('GoodsComments')->where($where)->field($field)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::GoodsCommentsListHandle($data, $params));
    }

    /**
     * 数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-14
     * @desc    description
     * @param   [array]          $data      [数据]
     * @param   [array]          $params    [输入参数]
     */
    public static function GoodsCommentsListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 获取商品信息
            $is_goods = (isset($params['is_goods']) && $params['is_goods'] == 1) ? 1 : 0;
            $goods = [];
            if($is_goods == 1)
            {
                $goods_params = [
                    'where' => [
                        ['id', 'in', array_unique(array_column($data, 'goods_id'))],
                        ['is_delete_time', '=', 0],
                    ],
                    'field' => 'id,title,images,price,min_price',
                    'm'     => 0,
                    'n'     => 0,
                ];
                $ret = GoodsService::GoodsList($goods_params);
                if(!empty($ret['data']))
                {
                    foreach($ret['data'] as $g)
                    {
                        $goods[$g['id']] = $g;
                    }
                }
            }

            // 静态数据
            $common_is_text_list = MyConst('common_is_text_list');
            $comments_rating_list = MyConst('common_goods_comments_rating_list');
            $comments_business_type_list = MyConst('common_goods_comments_business_type_list');

            // 用户默认头像
            $default_avatar = UserDefaultAvatar();

            // 数据处理
            $username_default = MyLang('common_service.goodscomments.comments_username_default');
            foreach($data as &$v)
            {
                // 用户信息
                if(array_key_exists('user_id', $v))
                {
                    $user = UserService::GetUserViewInfo($v['user_id']);
                    if(!isset($params['is_public']) || $params['is_public'] == 1)
                    {
                        $v['user'] = [
                            'avatar'            => ((isset($v['is_anonymous']) && $v['is_anonymous'] == 1) || empty($user['avatar'])) ? $default_avatar : $user['avatar'],
                            'user_name_view'    => (!isset($v['is_anonymous']) || $v['is_anonymous'] == 1 || empty($user['user_name_view'])) ? $username_default : mb_substr($user['user_name_view'], 0, 1, 'utf-8').'***'.mb_substr($user['user_name_view'], -1, null, 'utf-8'),
                        ];
                    } else {
                        $v['user'] = $user;
                    }
                }

                // 图片
                if(array_key_exists('images', $v))
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

                // 商品信息
                if(array_key_exists('goods_id', $v) && $is_goods == 1)
                {
                    $v['goods'] = isset($goods[$v['goods_id']]) ? $goods[$v['goods_id']] : null;   
                }

                // 业务类型
                if(array_key_exists('business_type', $v))
                {
                    $v['business_type_text'] = array_key_exists($v['business_type'], $comments_business_type_list) ? $comments_business_type_list[$v['business_type']]['name'] : null;
                    $msg = null;
                    switch($v['business_type'])
                    {
                        // 订单
                        case 'order' :
                            if(!empty($v['order_id']) && !empty($v['goods_id']) && !empty($v['user_id']))
                            {
                                $msg = self::BusinessTypeOrderSpec($v['order_id'], $v['goods_id'], $v['user_id']);
                            }
                    }
                    $v['msg'] = empty($msg) ? null : $msg;
                }

                // 评分
                if(array_key_exists('rating', $v))
                {
                    $v['rating_text'] = $comments_rating_list[$v['rating']]['name'];
                    $v['rating_badge'] = $comments_rating_list[$v['rating']]['badge'];
                }

                // 是否
                if(array_key_exists('is_reply', $v))
                {
                    $v['is_reply_text'] = isset($common_is_text_list[$v['is_reply']]) ? $common_is_text_list[$v['is_reply']]['name'] : '';
                }
                if(array_key_exists('is_anonymous', $v))
                {
                    $v['is_anonymous_text'] = isset($common_is_text_list[$v['is_anonymous']]) ? $common_is_text_list[$v['is_anonymous']]['name'] : '';
                }
                if(array_key_exists('is_show', $v))
                {
                    $v['is_show_text'] = isset($common_is_text_list[$v['is_show']]) ? $common_is_text_list[$v['is_show']]['name'] : '';
                }

                // 回复时间
                if(array_key_exists('reply_time', $v))
                {
                    $v['reply_time_time'] = empty($v['reply_time']) ? null : date('Y-m-d H:i:s', $v['reply_time']);
                    $v['reply_time_date'] = empty($v['reply_time']) ? null : date('m-d H:i', $v['reply_time']);
                }

                // 评论时间
                if(array_key_exists('add_time', $v))
                {
                    $v['add_time_hour'] = date('H:i:s', $v['add_time']);
                    $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                    $v['add_time_date'] = date('m-d H:i', $v['add_time']);
                }

                // 更新时间
                if(array_key_exists('upd_time', $v))
                {
                    $v['upd_time_time'] = empty($v['upd_time']) ? null : date('Y-m-d H:i:s', $v['upd_time']);
                    $v['upd_time_date'] = empty($v['upd_time']) ? null : date('m-d H:I', $v['upd_time']);
                }
            }
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 前端商品评论列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserGoodsCommentsListWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[]= ['user_id', '=', $params['user']['id']];
        }

        if(!empty($params['keywords']))
        {
            $goods_where = [
                ['g.is_delete_time', '=', 0],
                ['g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords', 'like', '%'.$params['keywords'].'%'],
            ];
            $goods_ids = Db::name('Goods')->alias('g')->join('goods_comments gc', 'g.id=gc.goods_id')->where($goods_where)->column('g.id');
            if(!empty($goods_ids))
            {
                $where[] = ['goods_id', 'in', $goods_ids];
            }
        }

        return $where;
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
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '6,230',
                'error_msg'         => MyLang('common_service.goodscomments.form_item_content_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'rating',
                'checked_data'      => array_keys(MyConst('common_goods_comments_rating_list')),
                'error_msg'         => MyLang('common_service.goodscomments.form_item_rating_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

        // 管理员操作
        if($user_type == 'admin')
        {
            $p = [
                [
                    'checked_type'      => 'in',
                    'key_name'          => 'business_type',
                    'checked_data'      => array_keys(MyConst('common_goods_comments_business_type_list')),
                    'error_msg'         => MyLang('common_service.goodscomments.form_item_business_type_message'),
                ],
                [
                    'checked_type'      => 'length',
                    'key_name'          => 'reply',
                    'checked_data'      => '230',
                    'error_msg'         => MyLang('common_service.goodscomments.form_item_reply_message'),
                ],
            ];
            $ret = ParamsChecked($params, $p);
            if($ret !== true)
            {
                return DataReturn($ret, -1);
            }
        }

        // 开始操作
        $data = [
            'content'       => $params['content'],
            'rating'        => intval($params['rating']),
            'is_anonymous'  => isset($params['is_anonymous']) ? intval($params['is_anonymous']) : 0,
            'upd_time'      => time(),
        ];

        // 管理员操作
        if($user_type == 'admin')
        {
            $data = array_merge($data, [
                'business_type'  => $params['business_type'],
                'reply'          => $params['reply'],
                'reply_time'     => empty($params['reply_time']) ? 0 : strtotime($params['reply_time']),
                'is_reply'       => isset($params['is_reply']) ? intval($params['is_reply']) : 0,
                'is_show'        => isset($params['is_show']) ? intval($params['is_show']) : 0,
            ]);
        }

        // 更新条件
        $where = [
            ['id', '=', intval($params['id'])]
        ];

        // 是否用户操作
        if($user_type == 'user')
        {
            $user_id = empty($params['user']) ? 0 : intval($params['user']['id']);
            $where[] = ['user_id', '=', $user_id];
        }

        // 更新
        if(Db::name('GoodsComments')->where($where)->update($data))
        {
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100); 
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
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

        // 更新条件
        $where = [
            ['id', 'in', $params['ids']]
        ];

        // 是否用户操作
        if($user_type == 'user')
        {
            $user_id = empty($params['user']) ? 0 : intval($params['user']['id']);
            $where[] = ['user_id', '=', $user_id];
        }

        // 开始删除
        if(Db::name('GoodsComments')->where($where)->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
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
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'reply',
                'checked_data'      => '1,230',
                'error_msg'         => MyLang('common_service.goodscomments.form_item_reply_content_message'),
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
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -2);
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
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
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
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'field',
                'checked_data'      => ['is_anonymous', 'is_show', 'is_reply'],
                'error_msg'         => MyLang('operate_field_error_tips'),
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
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
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
        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_goods_comments_score_key');
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug'))
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
            $data = Db::name('GoodsComments')->where($where)->group('rating')->column('count(*) as count', 'rating');
            if(!empty($data))
            {
                $sum = array_sum($data);
                $temp_rating = null;
                $temp_portion = 0;
                foreach($data as $rating=>$count)
                {
                    if($rating > 0 && $rating <= 5)
                    {
                        $rating_list[$rating]['count'] = $count;
                        $rating_list[$rating]['portion'] = round(($count/$sum)*100);
                        if($rating_list[$rating]['portion'] > $temp_portion)
                        {
                            $temp_rating = $rating;
                            $temp_portion = $rating_list[$rating]['portion'];
                        }
                    }
                }
                // 合计是否超出%100
                $sum_portion = array_sum(array_column($rating_list, 'portion'));
                if($sum_portion > 100 && $temp_rating !== null)
                {
                    $rating_list[$temp_rating]['portion'] -= $sum_portion-100;
                }
            }
            sort($rating_list);
            $avg = PriceNumberFormat(Db::name('GoodsComments')->where($where)->avg('rating'), 1);
            $rate = ($avg <= 0) ? 100 : intval(($avg/5)*100);
            $data = [
                'avg'       => $avg,
                'rate'      => $rate,
                'rating'    => $rating_list,
            ];

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }

    /**
     * 商品最新几条评论
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-15
     * @desc    description
     * @param   [int]           $goods_id [商品id]
     * @param   [int]           $number   [获取数量、默认3条]
     */
    public static function GoodsFirstSeveralComments($goods_id, $number = 3)
    {
        $where = [
            ['goods_id', '=', $goods_id],
            ['is_show', '=', 1],
        ];
        $field = 'id,user_id,order_id,business_type,content,reply,is_reply,rating,images,is_anonymous,reply_time,add_time';
        return self::GoodsCommentsListHandle(Db::name('GoodsComments')->where($where)->field($field)->limit(0, $number)->order('id desc')->select()->toArray());
    }
}
?>