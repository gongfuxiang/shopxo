<?php

namespace Service;

/**
 * 商品服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsService
{
    /**
     * 根据id获取一条商品分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryRow($params = [])
    {
        if(empty($params['id']))
        {
            return null;
        }
        $field = empty($params['field']) ? 'id,pid,icon,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended' : $params['field'];
        $data = self::GoodsCategoryDataDealWith([M('GoodsCategory')->field($field)->where(['is_enable'=>1, 'id'=>intval($params['id'])])->find()]);
        return empty($data[0]) ? null : $data[0];
    }

    /**
     * 获取大分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategory($params = [])
    {
        $data = self::GoodsCategoryList(['pid'=>0]);
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['items'] = self::GoodsCategoryList(['pid'=>$v['id']]);
                if(!empty($v['items']))
                {
                    foreach($v['items'] as &$vs)
                    {
                        $vs['items'] = self::GoodsCategoryList(['pid'=>$vs['id']]);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 根据pid获取商品分类列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryList($params = [])
    {
        $pid = isset($params['pid']) ? intval($params['pid']) : 0;
        $field = 'id,pid,icon,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended';
        $data = M('GoodsCategory')->field($field)->where(['is_enable'=>1, 'pid'=>$pid])->order('sort asc')->select();
        return self::GoodsCategoryDataDealWith($data);
    }

    /**
     * 商品分类数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-06
     * @desc    description
     * @param   [array]          $data [商品分类数据 二维数组]
     */
    private static function GoodsCategoryDataDealWith($data)
    {
        if(!empty($data) && is_array($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                if(is_array($v))
                {
                    if(isset($v['icon']))
                    {
                        $v['icon'] = empty($v['icon']) ? null : $images_host.$v['icon'];
                    }
                    if(isset($v['big_images']))
                    {
                        $v['big_images_old'] = $v['big_images'];
                        $v['big_images'] = empty($v['big_images']) ? null : $images_host.$v['big_images'];
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 获取首页楼层数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function HomeFloorList($params = [])
    {
        // 商品大分类
        $goods_category = self::GoodsCategory();
        if(!empty($goods_category))
        {
            foreach($goods_category as &$v)
            {
                $category_ids = self::GoodsCategoryItemsIds(['category_id'=>$v['id']]);
                $v['goods'] = self::GoodsList(['where'=>['gci.category_id'=>['in', $category_ids], 'is_home_recommended'=>1], 'm'=>0, 'n'=>6]);
            }
        }
        return $goods_category;
    }

    /**
     * 获取商品分类下的所有分类id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryItemsIds($params = [])
    {
        $data = M('GoodsCategory')->where(['pid'=>$params['category_id'], 'is_enable'=>1])->getField('id', true);
        if(!empty($data))
        {
            foreach($data as $v)
            {
                $temp = self::GoodsCategoryItemsIds(['category_id'=>$v]);
                if(!empty($temp))
                {
                    $data = array_merge($data, $temp);
                }
            }
        }
        return $data;
    }

    /**
     * 获取商品总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   array           $params [输入参数: where, field, is_photo]
     */
    public static function GoodsTotal($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        return (int) M('Goods')->alias('g')->join(' INNER JOIN __GOODS_CATEGORY_JOIN__ AS gci ON g.id=gci.goods_id')->where($where)->count('DISTINCT g.id');
    }

    /**
     * 获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   array           $params [输入参数: where, field, is_photo]
     */
    public static function GoodsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? 'g.*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'g.id desc' : trim($params['order_by']);

        $is_photo = (isset($params['is_photo']) && $params['is_photo'] == true) ? true : false;
        $is_attribute = (isset($params['is_attribute']) && $params['is_attribute'] == true) ? true : false;
        $is_category = (isset($params['is_category']) && $params['is_category'] == true) ? true : false;

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $data = M('Goods')->alias('g')->join(' INNER JOIN __GOODS_CATEGORY_JOIN__ AS gci ON g.id=gci.goods_id')->field($field)->where($where)->group('g.id')->order($order_by)->limit($m, $n)->select();
        if(!empty($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                // 商品url地址
                if(!empty($v['id']))
                {
                    $v['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$v['id']]);
                }

                // 商品封面图片
                if(isset($v['images']))
                {
                    $v['images_old'] = $v['images'];
                    $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];
                }

                // 视频
                if(isset($v['video']))
                {
                    $v['video_old'] = $v['video'];
                    $v['video'] = empty($v['video']) ? null : $images_host.$v['video'];
                }

                // 商品首页推荐图片，不存在则使用商品封面图片
                if(isset($v['home_recommended_images']))
                {
                    $v['home_recommended_images_old'] = $v['home_recommended_images'];
                    $v['home_recommended_images'] = empty($v['home_recommended_images']) ? (empty($v['images']) ? null : $v['images']) : $images_host.$v['home_recommended_images'];
                }

                // PC内容处理
                if(isset($v['content_web']))
                {
                    $v['content_web'] = ContentStaticReplace($v['content_web'], 'get');
                }

                // 产地
                if(!empty($v['place_origin']))
                {
                    $v['place_origin_text'] = M('Region')->where(['id'=>$v['place_origin']])->getField('name');
                }

                // 时间
                if(!empty($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(!empty($v['upd_time']))
                {
                    $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                }

                // 是否需要分类名称
                if($is_category && !empty($v['id']))
                {
                    $category_id = M('GoodsCategoryJoin')->where(['goods_id'=>$v['id']])->getField('category_id', true);
                    $category_name = M('GoodsCategory')->where(['id'=>['in', $category_id]])->getField('name', true);
                    $v['category_text'] = implode('，', $category_name);
                }

                // 获取相册
                if($is_photo && !empty($v['id']))
                {
                    $v['photo'] = M('GoodsPhoto')->where(['goods_id'=>$v['id'], 'is_show'=>1])->order('sort asc')->getField('images', true);
                    if(!empty($v['photo']))
                    {
                        foreach($v['photo'] as &$vs)
                        {
                            $vs = $images_host.$vs;
                        }
                    }
                }

                // 获取属性
                if($is_attribute && !empty($v['id']))
                {
                    $v['attribute'] = self::GoodsAttribute(['goods_id'=>$v['id']]);
                }
            }
        }
        return $data;
    }

    /**
     * 获取商品属性
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsAttribute($params = [])
    {
        $result = [];
        $data = M('GoodsAttributeType')->where(['goods_id'=>$params['goods_id']])->field('id,type,name')->order('sort asc')->select();
        if(!empty($data))
        {
            foreach($data as $v)
            {
                $v['find'] = M('GoodsAttribute')->field('id,name')->where(['goods_id'=>$params['goods_id'], 'attribute_type_id'=>$v['id']])->order('sort asc')->select();
                $result[$v['type']][] = $v;
            }
        } else {
            $data = [];
        }
        return $result;
    }

    /**
     * 商品收藏
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsFavor($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始操作
        $m = M('GoodsFavor');
        $data = ['goods_id'=>intval($params['id']), 'user_id'=>$params['user']['id']];
        $temp = $m->where($data)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if($m->add($data) > 0)
            {
                return DataReturn(L('common_favor_success'), 0, [
                    'text'      => L('common_favor_ok_text'),
                    'status'    => 1,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            } else {
                return DataReturn(L('common_favor_error'));
            }
        } else {
            if($m->where($data)->delete() > 0)
            {
                return DataReturn(L('common_cancel_success'), 0, [
                    'text'      => L('common_favor_not_text'),
                    'status'    => 0,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            } else {
                return DataReturn(L('common_cancel_error'));
            }
        }
    }

    /**
     * 用户是否收藏了商品
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [int]                    [1已收藏, 0未收藏]
     */
    public static function IsUserGoodsFavor($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $m = M('GoodsFavor');
        $data = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = $m->where($data)->find();
        return DataReturn(L('common_operation_success'), 0, empty($temp) ? 0 : 1);
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
    public static function GoodsCommentsTotal($goods_id)
    {
        return (int) M('OrderComments')->where(['goods_id'=>intval($goods_id)])->count();
    }

    /**
     * 前端商品收藏列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserGoodsFavorListWhere($params = [])
    {
        $where = [
            'g.is_delete_time'  => 0,
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where['f.user_id'] = $params['user']['id'];
        }

        if(!empty($params['keywords']))
        {
            $where['g.title'] = array('like', '%'.I('keywords').'%');
        }

        return $where;
    }

    /**
     * 商品收藏总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function GoodsFavorTotal($where = [])
    {
        return (int) M('GoodsFavor')->alias('f')->join('__GOODS__ AS g ON g.id=f.goods_id')->where($where)->count();
    }

    /**
     * 商品收藏列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsFavorList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'where',
                'error_msg'         => '条件不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'where',
                'error_msg'         => '条件格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_start',
                'error_msg'         => '分页起始值有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_number',
                'error_msg'         => '分页数量不能为空',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $limit_start = max(0, intval($params['limit_start']));
        $limit_number = max(1, intval($params['limit_number']));
        $order_by = empty($params['order_by']) ? 'f.id desc' : I('order_by', '', '', $params);
        $field = 'f.*, g.title, g.original_price, g.price, g.images';

        // 获取数据
        $data = M('GoodsFavor')->alias('f')->join('__GOODS__ AS g ON g.id=f.goods_id')->field($field)->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                // 图片
                $v['images_old'] = $v['images'];
                $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];

                $v['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$v['goods_id']]);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 商品访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsAccessCountInc($params = [])
    {
        if(!empty($params['goods_id']))
        {
            return M('Goods')->where(array('id'=>intval($params['goods_id'])))->setInc('access_count');
        }
        return false;
    }

    /**
     * 商品浏览保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsBrowseSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $m = M('GoodsBrowse');
        $where = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = $m->where($where)->find();

        $data = [
            'goods_id'  => intval($params['goods_id']),
            'user_id'   => $params['user']['id'],
            'upd_time'  => time(),
        ];
        if(empty($temp))
        {
            $data['add_time'] = time();
            $status = $m->add($data) > 0;
        } else {
            $status = $m->where($where)->save($data) !== false;
        }
        if($status)
        {
            return DataReturn('处理成功', 0);
        }
        return DataReturn('处理失败', -100);
    }

    /**
     * 前端商品浏览列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserGoodsBrowseListWhere($params = [])
    {
        $where = [
            'g.is_delete_time'  => 0,
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where['b.user_id'] = $params['user']['id'];
        }

        if(!empty($params['keywords']))
        {
            $where['g.title'] = array('like', '%'.I('keywords').'%');
        }

        return $where;
    }

    /**
     * 商品浏览总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function GoodsBrowseTotal($where = [])
    {
        return (int) M('GoodsBrowse')->alias('f')->join('__GOODS__ AS g ON g.id=f.goods_id')->where($where)->count();
    }

    /**
     * 商品浏览列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsBrowseList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'where',
                'error_msg'         => '条件不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'where',
                'error_msg'         => '条件格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_start',
                'error_msg'         => '分页起始值有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_number',
                'error_msg'         => '分页数量不能为空',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $limit_start = max(0, intval($params['limit_start']));
        $limit_number = max(1, intval($params['limit_number']));
        $order_by = empty($params['order_by']) ? 'b.id desc' : I('order_by', '', '', $params);
        $field = 'b.*, g.title, g.original_price, g.price, g.images';

        // 获取数据
        $data = M('GoodsBrowse')->alias('b')->join('__GOODS__ AS g ON g.id=b.goods_id')->field($field)->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['images_old'] = $v['images'];
                $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];
                $v['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$v['goods_id']]);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 商品浏览删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsBrowseDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '删除数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除
        $where = [
            'id'        => ['in', explode(',', $params['id'])],
            'user_id'   => $params['user']['id']
        ];
        if(M('GoodsBrowse')->where($where)->delete())
        {
            return DataReturn(L('common_operation_delete_success'), 0);
        }
        return DataReturn(L('common_operation_delete_error'), -100);
    }
}
?>