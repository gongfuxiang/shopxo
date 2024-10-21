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
use app\service\GoodsService;
use app\service\GoodsCategoryService;

/**
 * 站点设置服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SiteService
{
    /**
     * redis连接测试
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-26
     * @desc    description
     * @param   [string]        $host     [连接地址]
     * @param   [int]           $port     [端口]
     * @param   [string]        $password [密码]
     */
    public static function RedisCheckConnectPing($host, $port, $password)
    {
        // 参数处理
        $host = empty($host) ? '127.0.0.1' : $host;
        $port = empty($port) ? 6379 : $port;
        $password = empty($password) ? '' : $password;

        // 是否已安装redis扩展
        if(!extension_loaded('redis'))
        {
            return DataReturn(MyLang('common_service.site.redis_extend_no_install_tips'), -1);
        }

        // 捕获异常
        try {
            // 连接redis
            $redis = new \Redis();
            $redis->connect($host, $port);
            if($password != '')
            {
                $redis->auth($password);
            }
        } catch(\Exception $e) {
            return DataReturn(MyLang('common_service.site.redis_connect_fail_tips').'['.$e->getMessage().']', -1);
        }

        // 检测是否连接成功
        if($redis->ping())
        {
            return DataReturn(MyLang('common_service.site.redis_connect_success_tips'), 0);
        }
        return DataReturn(MyLang('common_service.site.redis_connect_fail_tips'), -1);
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSearchList($params = [])
    {
        // 返回数据
        $result = [
            'page_total'    => 0,
            'page_size'     => 32,
            'page'          => max(1, isset($params['page']) ? intval($params['page']) : 1),
            'total'         => 0,
            'data'          => [],
        ];

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
            $category_ids = GoodsCategoryService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where[] = ['gci.category_id', 'in', $category_ids];
        }

        // 获取商品总数
        $result['total'] = GoodsService::CategoryGoodsTotal($where);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 基础参数
            $field = 'g.id,g.title,g.images';
            $order_by = 'g.sort_level desc, g.id desc';

            // 分页计算
            $m = intval(($result['page']-1)*$result['page_size']);
            $goods = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>$m, 'n'=>$result['page_size'], 'field'=>$field, 'order_by'=>$order_by]);
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
             // 数据处理
            if(!empty($result['data']) && is_array($result['data']) && !empty($params['goods_ids']) && is_array($params['goods_ids']))
            {
                foreach($result['data'] as &$v)
                {
                    // 是否已添加
                    $v['is_exist'] = in_array($v['id'], $params['goods_ids']) ? 1 : 0;
                }
            }
        }
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 楼层自定义商品展示数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-25
     * @desc    description
     * @param   [array]          $data [配置数据]
     */
    public static function FloorManualModeGoodsViewHandle($data)
    {
        $result = [];
        if(!empty($data) && is_array($data))
        {
            // 商品id集合
            $goods_ids = [];
            foreach($data as $gid)
            {
                $goods_ids = array_merge($goods_ids, $gid);
            }
            // 获取商品数据
            $ret = GoodsService::GoodsList([
                'where' => [
                    ['id', 'in', array_unique($goods_ids)],
                    ['is_shelves', '=', 1],
                ],
                'field' => 'id,title,images',
                'm'     => 0,
                'n'     => 0,
            ]);
            // 使用商品id作为key返回
            $goods = empty($ret['data']) ? [] : array_column($ret['data'], null, 'id');
            
            // 数据组合
            foreach($data as $k=>$v)
            {
                $temp = [];
                foreach($v as $vs)
                {
                    if(array_key_exists($vs, $goods))
                    {
                        $temp[] = $goods[$vs];
                    }
                }
                $result[$k] = $temp;
            }
        }
        return DataReturn(MyLang('handle_success'), 0, $result);
    }
}
?>