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
 * 商品浏览服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-10-09
 * @desc    description
 */
class GoodsBrowseService
{
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
                'error_msg'         => MyLang('goods_id_error_tips'),
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsBrowse')->where($where)->find();

        $data = [
            'goods_id'  => intval($params['goods_id']),
            'user_id'   => $params['user']['id'],
        ];
        if(empty($temp))
        {
            $data['add_time'] = time();
            $status = Db::name('GoodsBrowse')->insertGetId($data) > 0;
        } else {
            $data['upd_time'] = time();
            $status = Db::name('GoodsBrowse')->where($where)->update($data) !== false;
        }
        if($status)
        {
            return DataReturn(MyLang('insert_success'), 0);
        }
        return DataReturn(MyLang('insert_fail'), -100);
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
            ['g.is_delete_time', '=', 0]
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['b.user_id', '=', $params['user']['id']];
        }

        if(!empty($params['keywords']))
        {
            $where[] = ['g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords', 'like', '%'.$params['keywords'].'%'];
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
        return (int) Db::name('GoodsBrowse')->alias('b')->join('goods g', 'g.id=b.goods_id')->where($where)->count();
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
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? 'b.*, g.title, g.original_price, g.price, g.min_price, g.images, g.inventory_unit, g.is_delete_time, g.is_shelves, g.inventory, g.site_type' : $params['field'];
        $order_by = empty($params['order_by']) ? 'b.id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据
        $data = Db::name('GoodsBrowse')->alias('b')->join('goods g', 'g.id=b.goods_id')->field($field)->where($where)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::GoodsBrowseListHandle($data, $params));
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
    public static function GoodsBrowseListHandle($data, $params = [])
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
        if(Db::name('GoodsBrowse')->where($where)->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>