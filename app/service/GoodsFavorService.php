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
use app\service\ResourcesService;
use app\service\UserService;
use app\service\GoodsService;

/**
 * 商品收藏服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-10-09
 * @desc    description
 */
class GoodsFavorService
{
    /**
     * 商品收藏/取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsFavorCancel($params = [])
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始操作
        $data = ['goods_id'=>intval($params['id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsFavor')->where($data)->find();
        if(empty($temp))
        {
            // 添加收藏
            $data['add_time'] = time();
            if(Db::name('GoodsFavor')->insertGetId($data) > 0)
            {
                return DataReturn('收藏成功', 0, [
                    'text'      => '已收藏',
                    'status'    => 1,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            } else {
                return DataReturn('收藏失败');
            }
        } else {
            // 是否强制收藏
            if(isset($params['is_mandatory_favor']) && $params['is_mandatory_favor'] == 1)
            {
                return DataReturn('收藏成功', 0, [
                    'text'      => '已收藏',
                    'status'    => 1,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            }

            // 删除收藏
            if(Db::name('GoodsFavor')->where($data)->delete() > 0)
            {
                return DataReturn('取消成功', 0, [
                    'text'      => '收藏',
                    'status'    => 0,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            } else {
                return DataReturn('取消失败');
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $data = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsFavor')->where($data)->find();
        return DataReturn('操作成功', 0, empty($temp) ? 0 : 1);
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
            ['g.is_delete_time', '=', 0]
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where[]= ['f.user_id', '=', $params['user']['id']];
        }

        if(!empty($params['keywords']))
        {
            $where[] = ['g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords', 'like', '%'.$params['keywords'].'%'];
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
        return (int) Db::name('GoodsFavor')->alias('f')->join('goods g', 'g.id=f.goods_id')->where($where)->count();
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
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? 'f.*, g.title, g.original_price, g.price, g.min_price, g.images' : $params['field'];
        $order_by = empty($params['order_by']) ? 'f.id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据
        $data = Db::name('GoodsFavor')->alias('f')->join('goods g', 'g.id=f.goods_id')->field($field)->where($where)->limit($m, $n)->order($order_by)->select()->toArray();
        if(!empty($data))
        {
            // 商品数据处理
            $ret = GoodsService::GoodsDataHandle($data, ['data_key_field'=>'goods_id']);
            $data = $ret['data'];

            // 是否公共读取
            $is_public = (isset($params['is_public']) && $params['is_public'] == 0) ? 0 : 1;
            $users = [];
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']) && $is_public == 0)
                {
                    if(!array_key_exists($v['user_id'], $users))
                    {
                        $users[$v['user_id']] = UserService::GetUserViewInfo($v['user_id']);
                    }
                    $v['user'] =  $users[$v['user_id']];
                }
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 商品收藏删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsFavorDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 条件
        $where = ['id' => $params['ids']];

        // 用户id
        if(!empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        // 删除
        if(Db::name('GoodsFavor')->where($where)->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
    }
}
?>