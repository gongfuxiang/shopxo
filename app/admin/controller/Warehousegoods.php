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

use app\service\WarehouseGoodsService;
use app\service\WarehouseService;
use app\service\GoodsService;

/**
 * 仓库商品管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-11
 * @desc    description
 */
class WarehouseGoods extends Common
{
    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 登录校验
        $this->IsLogin();

        // 权限校验
        $this->IsPower();
    }

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
        // 总数
        $total = WarehouseGoodsService::WarehouseGoodsTotal($this->form_where);

        // 分页
        $page_params = [
            'number'    =>  $this->page_size,
            'total'     =>  $total,
            'where'     =>  $this->data_request,
            'page'      =>  $this->page,
            'url'       =>  MyUrl('admin/warehousegoods/index'),
        ];
        $page = new \base\Page($page_params);

        // 获取数据列表
        $data_params = [
            'where'         => $this->form_where,
            'm'             => $page->GetPageStarNumber(),
            'n'             => $this->page_size,
            'order_by'      => $this->form_order_by['data'],
        ];
        $ret = WarehouseGoodsService::WarehouseGoodsList($data_params);

        // 有效仓库列表
        $data_params = [
            'field'     => 'id,name',
            'where'     => [
                'is_enable'         => 1,
                'is_delete_time'    => 0,
            ],
        ];
        $warehouse = WarehouseService::WarehouseList($data_params);
        MyViewAssign('warehouse_list', $warehouse['data']);

        // 商品分类
        MyViewAssign('goods_category_list', GoodsService::GoodsCategoryAll());

        // 基础参数赋值
        MyViewAssign('params', $this->data_request);
        MyViewAssign('page_html', $page->GetPageHtml());
        MyViewAssign('data_list', $ret['data']);
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
                    $ret = WarehouseGoodsService::DataHandle([$ret['data']['data']]);
                    $data = $ret[0];
                }
            }
        }
        MyViewAssign('spec', $spec);
        MyViewAssign('data', $data);
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

        // 数据
        MyViewAssign('data', $data);
        MyViewAssign('params', $params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return WarehouseGoodsService::WarehouseGoodsInventorySave($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return WarehouseGoodsService::WarehouseGoodsDelete($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return WarehouseGoodsService::WarehouseGoodsStatusUpdate($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 搜索数据
        $ret = WarehouseGoodsService::GoodsSearchList($this->data_request);
        if($ret['code'] == 0)
        {
            MyViewAssign('data', $ret['data']['data']);
            $ret['data']['data'] = MyView();
        }
        return $ret;
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return WarehouseGoodsService::WarehouseGoodsAdd($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return WarehouseGoodsService::WarehouseGoodsDel($params);
    }
}
?>