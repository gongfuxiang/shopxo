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

        // 开始操作
        $m = M('GoodsFavor');
        $data = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = $m->where($data)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if($m->add($data) > 0)
            {
                return DataReturn(L('common_favor_success'), 0, ['text'=>L('common_favor_ok_text'), 'status'=>1]);
            } else {
                return DataReturn(L('common_favor_error'));
            }
        } else {
            if($m->where($data)->delete() > 0)
            {
                return DataReturn(L('common_cancel_success'), 0, ['text'=>L('common_favor_not_text'), 'status'=>0]);
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
}
?>