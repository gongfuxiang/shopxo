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
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\WarehouseGoodsService;
use app\service\WarehouseService;
use app\service\GoodsCategoryService;

/**
 * 仓库商品管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-11
 * @desc    description
 */
class WarehouseGoods extends Base
{
    /**
     * 列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 商品分类
            'goods_category_list'   => GoodsCategoryService::GoodsCategoryAll(),
        ];

        // 有效仓库列表
        $data_params = [
            'field'     => 'id,name',
            'where'     => [
                ['is_enable', '=', 1],
                ['is_delete_time', '=', 0],
            ],
        ];
        $warehouse = WarehouseService::WarehouseList($data_params);
        $assign['warehouse_list'] = $warehouse['data'];
        
        // 数据赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-07-11
     */
    public function Detail()
    {
        // 商品和规格数据
        $data = [];
        $spec = [];
        if(!empty($this->data_request['id']))
        {
            // 获取规格库存
            $ret = WarehouseGoodsService::WarehouseGoodsInventoryData(['id'=>intval($this->data_request['id'])]);
            if($ret['code'] == 0)
            {
                // 规格
                if(!empty($ret['data']['spec']))
                {
                    $spec = $ret['data']['spec'];
                }

                // 详情数据
                if(!empty($ret['data']['data']))
                {
                    $ret = WarehouseGoodsService::WarehouseGoodsListHandle([$ret['data']['data']]);
                    $data = $ret[0];
                }
            }
        }

        // 数据赋值
        $assign = [
            'spec'   => $spec,
            'data'   => $data,
        ];
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 库存编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     */
    public function InventoryInfo()
    {
        // 参数
        $params = $this->data_request;

        // 数据
        $data = [];
        if(!empty($params['id']))
        {
            $ret = WarehouseGoodsService::WarehouseGoodsInventoryData($params);
            $data = empty($ret['data']) ? [] : $ret['data'];
        }

        // 数据赋值
        $assign = [
            'params'    => $params,
            'data'      => $data,
        ];
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 库存编辑
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     */
    public function InventorySave()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(WarehouseGoodsService::WarehouseGoodsInventorySave($params));
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return ApiService::ApiDataReturn(WarehouseGoodsService::WarehouseGoodsDelete($params));
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     */
    public function StatusUpdate()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return ApiService::ApiDataReturn(WarehouseGoodsService::WarehouseGoodsStatusUpdate($params));
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     */
    public function GoodsSearch()
    {
        $ret = WarehouseGoodsService::GoodsSearchList($this->data_request);
        if($ret['code'] == 0)
        {
            MyViewAssign('data', $ret['data']['data']);
            $ret['data']['data'] = MyView();
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 仓库商品添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-14
     * @desc    description
     */
    public function GoodsAdd()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(WarehouseGoodsService::WarehouseGoodsAdd($params));
    }

    /**
     * 仓库商品删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-14
     * @desc    description
     */
    public function GoodsDel()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(WarehouseGoodsService::WarehouseGoodsDel($params));
    }
}
?>