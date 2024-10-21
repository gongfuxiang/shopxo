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
use app\service\DesignService;
use app\service\BrandService;
use app\service\StoreService;
use app\service\GoodsService;
use app\service\GoodsCategoryService;
use app\module\LayoutModule;

/**
 * 页面设计管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class Design extends Base
{
    /**
     * 首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Index()
    {
        // 应用商店
        MyViewAssign('store_design_url', StoreService::StoreDesignUrl());
        return MyView();
    }

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Detail()
    {
        return MyView();
    }

    /**
     * 编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function SaveInfo()
    {
        // 数据
        $data = $this->data_detail;
        if(empty($data))
        {
            $ret = DesignService::DesignSave();
            if($ret['code'] == 0)
            {
                return MyRedirect(MyUrl('admin/design/saveinfo', ['id'=>$ret['data']]));
            } else {
                MyViewAssign('msg', $ret['msg']);
                return MyView('public/tips_error');
            }
        }

        // 配置处理
        $layout_data = LayoutModule::ConfigAdminHandle($data['config']);
        unset($data['config']);

        // 商品分类
        $goods_category = GoodsCategoryService::GoodsCategoryAll();

        // 模板数据
        MyViewAssign([
            // 当前数据
            'layout_data'                               => $layout_data,
            'data'                                      => $data,
            // 页面列表
            'pages_list'                                => LayoutModule::PagesList(),
            // 商品分类
            'goods_category_list'                       => $goods_category,
            // 商品搜索分类（分类）
            'layout_goods_category'                     => $goods_category,
            'layout_goods_category_field'               => 'gci.category_id',
            // 品牌
            'brand_list'                                => BrandService::CategoryBrand(),
            // 静态数据
            'border_style_type_list'                    => LayoutModule::ConstData('border_style_type_list'),
            'goods_view_list_show_style'                => LayoutModule::ConstData('goods_view_list_show_style'),
            'many_images_view_list_show_style'          => LayoutModule::ConstData('many_images_view_list_show_style'),
            'images_text_view_list_show_style'          => LayoutModule::ConstData('images_text_view_list_show_style'),
            'images_magic_cube_view_list_show_style'    => LayoutModule::ConstData('images_magic_cube_view_list_show_style'),
            // 首页商品排序规则
            'common_goods_order_by_type_list'           => MyConst('common_goods_order_by_type_list'),
            'common_data_order_by_rule_list'           => MyConst('common_data_order_by_rule_list'),
            // 加载布局样式+管理
            'is_load_layout'                            => 1,
            'is_load_layout_admin'                      => 1,
            // 编辑器文件存放地址定义
            'editor_path_type'                          => DesignService::AttachmentPathTypeValue($data['id']),
        ]);
        return MyView();
    }

    /**
     * 下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     */
    public function Download()
    {
        $ret = DesignService::DesignDownload($this->data_request);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(DesignService::DesignSave($this->data_request));
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-31
     * @desc    description
     */
    public function StatusUpdate()
    {
        return ApiService::ApiDataReturn(DesignService::DesignStatusUpdate($this->data_request));
    }
    
    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-31
     * @desc    description
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(DesignService::DesignDelete($this->data_request));
    }

    /**
     * 同步到首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Sync()
    {
        return ApiService::ApiDataReturn(DesignService::DesignSync($this->data_request));
    }

    /**
     * 导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Upload()
    {
        return ApiService::ApiDataReturn(DesignService::DesignUpload($this->data_request));
    }

    /**
     * 模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Market()
    {
        $ret = DesignService::DesignMarket($this->data_request);
        if($ret['code'] == 0 && isset($ret['data']['data_list']))
        {
            $ret['data']['data_list'] = MyView('public/market/list', ['data_list'=>$ret['data']['data_list']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>