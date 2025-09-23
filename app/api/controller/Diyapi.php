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
namespace app\api\controller;

use app\api\controller\Base;
use app\service\ApiService;
use app\service\DiyApiService;

/**
 * DiyApi接口
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class DiyApi extends Base
{
    /**
     * 商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsList()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * 自定义页面列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function CustomViewList()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * 页面设计列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DesignList()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * 文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function ArticleList()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * 品牌列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function BrandList()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * Diy装修列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyList()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * 商品指定数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsAppointData()
    {
        return ApiService::ApiDataReturn(DiyApiService::GoodsAppointData($this->data_request));
    }

    /**
     * 商品自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsAutoData()
    {
        return ApiService::ApiDataReturn(DiyApiService::GoodsAutoData($this->data_request));
    }

    /**
     * 文章指定数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function ArticleAppointData()
    {
        return ApiService::ApiDataReturn(DiyApiService::ArticleAppointData($this->data_request));
    }

    /**
     * 文章自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function ArticleAutoData()
    {
        return ApiService::ApiDataReturn(DiyApiService::ArticleAutoData($this->data_request));
    }

    /**
     * 品牌指定数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function BrandAppointData()
    {
        return ApiService::ApiDataReturn(DiyApiService::BrandAppointData($this->data_request));
    }

    /**
     * 品牌自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function BrandAutoData()
    {
        return ApiService::ApiDataReturn(DiyApiService::BrandAutoData($this->data_request));
    }

    /**
     * 用户头部数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function UserHeadData()
    {
        return ApiService::ApiDataReturn(DiyApiService::UserHeadData($this->data_request));
    }

    /**
     * 商品收藏自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsFavorAutoData()
    {
        return ApiService::ApiDataReturn(DiyApiService::GoodsFavorAutoData($this->data_request));
    }

    /**
     * 商品浏览自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsBrowseAutoData()
    {
        return ApiService::ApiDataReturn(DiyApiService::GoodsBrowseAutoData($this->data_request));
    }

    /**
     * 自定义初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function CustomInit()
    {
        return ApiService::ApiDataReturn(DiyApiService::CustomInit($this->data_request));
    }

    /**
     * 商品魔方初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsMagicInit()
    {
        return ApiService::ApiDataReturn(DiyApiService::GoodsMagicInit($this->data_request));
    }
}
?>