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
                'error_msg'         => MyLang('goods_id_error_tips'),
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

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck($params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 是否数组
        if(!is_array($params['id']))
        {
            $params['id'] = explode(',', $params['id']);
        }

        // 获取已收藏的数据
        $where = [
            ['goods_id', 'in', $params['id']],
            ['user_id', '=', $params['user']['id']],
        ];
        $temp = Db::name('GoodsFavor')->where($where)->column('goods_id');

        // 开始操作、是否强制收藏
        $is_mandatory_favor = isset($params['is_mandatory_favor']) && $params['is_mandatory_favor'] == 1;
        $insert_data = [];
        $cancel_data = [];
        foreach($params['id'] as $id)
        {
            if($is_mandatory_favor)
            {
                if(!in_array($id, $temp))
                {
                    $insert_data[] = [
                        'goods_id'  => $id,
                        'user_id'   => $params['user']['id'],
                        'add_time'  => time(),
                    ];
                }
            } else {
                // 存在取消、则添加
                if(in_array($id, $temp))
                {
                    $cancel_data[] = $id;
                } else {
                    $insert_data[] = [
                        'goods_id'  => $id,
                        'user_id'   => $params['user']['id'],
                        'add_time'  => time(),
                    ];
                }
            }
        }
        // 添加
        if(!empty($insert_data))
        {
            if(Db::name('GoodsFavor')->insertAll($insert_data) >= count($insert_data))
            {
                // 仅一个商品操作则返回收藏数据
                if(count($params['id']) == 1)
                {
                    return DataReturn(MyLang('favor_success'), 0, [
                        'text'      => MyLang('already_favor_title'),
                        'status'    => 1,
                        'count'     => self::GoodsFavorTotal(['goods_id'=>$params['id'][0]]),
                    ]);
                }
            } else {
                return DataReturn(MyLang('favor_fail'), -1);
            }
        }
        // 取消
        if(!empty($cancel_data))
        {
            $where = [
                ['goods_id', 'in', $cancel_data],
                ['user_id', '=', $params['user']['id']],
            ];
            if(Db::name('GoodsFavor')->where($where)->delete() > 0)
            {
                // 仅一个商品操作则返回收藏数据
                if(count($params['id']) == 1)
                {
                    return DataReturn(MyLang('cancel_success'), 0, [
                        'text'      => MyLang('favor_title'),
                        'status'    => 0,
                        'count'     => self::GoodsFavorTotal(['goods_id'=>$params['id'][0]]),
                    ]);
                }
            } else {
                return DataReturn(MyLang('cancel_fail'), -1);
            }
        }
        return DataReturn(MyLang('favor_success'), 0);
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
                'error_msg'         => MyLang('goods_id_error_tips'),
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

        $data = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsFavor')->where($data)->find();
        return DataReturn(MyLang('operate_success'), 0, empty($temp) ? 0 : 1);
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
        $field = empty($params['field']) ? 'f.*, g.title, g.original_price, g.price, g.min_price, g.images, g.inventory_unit, g.is_delete_time, g.is_shelves, g.inventory, g.site_type' : $params['field'];
        $order_by = empty($params['order_by']) ? 'f.id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据
        $data = Db::name('GoodsFavor')->alias('f')->join('goods g', 'g.id=f.goods_id')->field($field)->where($where)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::GoodsFavorListHandle($data, $params));
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
    public static function GoodsFavorListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 商品数据处理
            $ret = GoodsService::GoodsDataHandle($data, ['data_key_field'=>'goods_id', 'is_spec'=>1, 'is_cart'=>1]);
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
        return $data;
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
                'error_msg'         => MyLang('data_id_error_tips'),
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
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>