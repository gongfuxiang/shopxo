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
namespace app\plugins\freightfee;

use think\Db;
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\AnswerService;

/**
 * 问答系统服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    /**
     * 数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     * @param   [array]          $data [应用数据]
     */
    public static function DataHandle($data)
    {
        if(!empty($data['data']))
        {
            // 支付方式
            if(empty($data['payment']))
            {
                $data['payment'] = [];
                $data['payment_names'] = '';
            } else {
                $data['payment'] = explode(',', $data['payment']);
                $data['payment_names'] = implode('、', array_map(function($v){return mb_substr($v, strrpos($v, '-')+1, null, 'utf-8');}, $data['payment']));
            }

            // 地区
            foreach($data['data'] as &$v)
            {
                $v['region_names'] = empty($v['region_show']) ? '' : implode('、', Db::name('Region')->where('id', 'in', explode('-', $v['region_show']))->column('name'));
            }

            // 商品列表
            $data['goods_list'] = empty($data['goods_ids']) ? [] : self::GoodsList($data['goods_ids']);
        }
        return $data;
    }

    /**
     * 商品搜索
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSearchList($params = [])
    {
        // 条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1]
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['g.title', 'like', '%'.$params['keywords'].'%'];
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $category_ids = GoodsService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where[] = ['gci.category_id', 'in', $category_ids];
        }

        // 指定字段
        $field = 'g.id,g.title';

        // 获取数据
        return GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>0, 'n'=>100, 'field'=>$field, 'is_admin_access'=>1]);
    }

    /**
     * 商品列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [string]          $goods_ids [商品id]
     */
    public static function GoodsList($goods_ids = [])
    {
        // 商品id
        if(empty($goods_ids))
        {
            return [];
        } else {
            $goods_ids = explode(',', $goods_ids);
        }

        // 条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1],
            ['g.id', 'in', $goods_ids],
        ];

        // 指定字段
        $field = 'g.id,g.title,g.images,g.price';

        // 获取数据
        $ret = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>0, 'n'=>100, 'field'=>$field]);
        return $ret['data'];
    }
}
?>