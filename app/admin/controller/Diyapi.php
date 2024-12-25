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
     * 公共初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Init()
    {
        return ApiService::ApiDataReturn(DiyApiService::Init($this->data_request));
    }

    /**
     * 附件分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentCategory()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentCategory($this->data_request));
    }

    /**
     * 附件列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentList()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentList($this->data_request));
    }

    /**
     * 附件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentSave()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentSave($this->data_request));
    }

    /**
     * 附件删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentDelete()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentDelete($this->data_request));
    }

    /**
     * 附件上传
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentUpload()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentUpload($this->data_request));
    }

    /**
     * 远程下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentCatch()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentCatch($this->data_request));
    }

    /**
     * 附件扫码上传数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentScanUploadData()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentScanUploadData($this->data_request));
    }

    /**
     * 附件移动分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentMoveCategory()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentMoveCategory($this->data_request));
    }

    /**
     * 附件分类保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentCategorySave()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentCategorySave($this->data_request));
    }

    /**
     * 附件分类删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AttachmentCategoryDelete()
    {
        return ApiService::ApiDataReturn(DiyApiService::AttachmentCategoryDelete($this->data_request));
    }

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
        return ApiService::ApiDataReturn(DiyApiService::GoodsList($this->data_request));
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
        return ApiService::ApiDataReturn(DiyApiService::CustomViewList($this->data_request));
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
        return ApiService::ApiDataReturn(DiyApiService::DesignList($this->data_request));
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
        return ApiService::ApiDataReturn(DiyApiService::ArticleList($this->data_request));
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
        return ApiService::ApiDataReturn(DiyApiService::BrandList($this->data_request));
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
        return ApiService::ApiDataReturn(DiyApiService::DiyList($this->data_request));
    }

    /**
     * Diy装修详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyDetail()
    {
        return ApiService::ApiDataReturn(DiyApiService::DiyDetail($this->data_request));
    }

    /**
     * Diy装修保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiySave()
    {
        return ApiService::ApiDataReturn(DiyApiService::DiySave($this->data_request));
    }

    /**
     * Diy装修导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyUpload()
    {
        return ApiService::ApiDataReturn(DiyApiService::DiyUpload($this->data_request));
    }

    /**
     * Diy装修导出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyDownload()
    {
        $ret = DiyApiService::DiyDownload($this->data_request);
        if($ret['code'] != 0)
        {
            return MyView('public/tips_error', ['msg'=>$ret['msg']]);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * diy模板安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function DiyInstall()
    {
        return ApiService::ApiDataReturn(DiyApiService::DiyInstall($this->data_request));
    }

    /**
     * diy模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function DiyMarket()
    {
        return ApiService::ApiDataReturn(DiyApiService::DiyMarket($this->data_request));
    }

    /**
     * 底部菜单保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AppTabbarSave()
    {
        return ApiService::ApiDataReturn(DiyApiService::AppTabbarSave($this->data_request));
    }

    /**
     * 底部菜单数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AppTabbarData()
    {
        return ApiService::ApiDataReturn(DiyApiService::AppTabbarData($this->data_request));
    }



    /**
     * 商品初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GoodsInit()
    {
        return ApiService::ApiDataReturn(DiyApiService::GoodsInit($this->data_request));
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
}
?>