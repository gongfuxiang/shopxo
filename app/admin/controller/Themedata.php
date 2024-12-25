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
use app\service\ResourcesService;
use app\service\ThemeDataService;
use app\service\ThemeAdminService;
use app\service\GoodsCategoryService;
use app\service\BrandService;
use app\service\ArticleCategoryService;

/**
 * 主题数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-03-31
 * @desc    description
 */
class ThemeData extends Base
{
    /**
     * 列表
     * @author  Devil
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function Index()
    {
        // 主题数据类型
        MyViewAssign('theme_type_list', MyConst('common_theme_type_list'));
        return MyView();
    }

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function Detail()
    {
        // 公共视图数据
        $this->PageViewAssign();
        return MyView();
    }

    /**
     * 添加/编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function SaveInfo()
    {
        // 没有数据id则先新增
        if(empty($this->data_detail))
        {
            $ret = ThemeDataService::ThemeDataSave(array_merge($this->data_request, ['is_init'=>1]));
            if($ret['code'] == 0)
            {
                return MyRedirect(MyUrl('admin/themedata/saveinfo', ['id'=>$ret['data']]));
            } else {
                MyViewAssign('msg', $ret['msg']);
                return MyView('public/tips_error');
            }
        }

        // 当前访问页面
        $theme_type_list = MyConst('common_theme_type_list');
        $type = empty($this->data_detail['type']) ? 0 : intval($this->data_detail['type']);
        $view = isset($theme_type_list[$type]) ? $theme_type_list[$type]['type'] : $theme_type_list[0]['type'];

        // 参数处理
        unset($this->data_request['id'], $this->data_request['type']);

        // 模板数据
        MyViewAssign([
            // 参数
            'params'            => $this->data_request,
            // 编辑器文件存放地址定义
            'editor_path_type'  => ThemeDataService::AttachmentPathTypeValue($this->data_detail['id']),
            // 主题列表
            'theme_list'        => ThemeAdminService::ThemeAdminList(),
        ]);

        // 公共视图数据
        $this->PageViewAssign();
        return MyView('themedata/saveinfo/'.$view);
    }

    /**
     * 页面视图数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-02
     * @desc    description
     */
    public function PageViewAssign()
    {
        // 文章分类
        $article_category = ArticleCategoryService::ArticleCategoryList(['field'=>'id,name']);

        // 模板数据
        MyViewAssign([
            // 商品分类
            'goods_category_list'                       => GoodsCategoryService::GoodsCategoryAll(),
            // 品牌
            'brand_list'                                => BrandService::CategoryBrand(),
            // 文章分类
            'article_category_list'                     => $article_category['data'],
            // 静态数据
            'common_platform_type'                      => MyConst('common_platform_type'),
            'common_theme_goods_type_list'              => MyConst('common_theme_goods_type_list'),
            'common_theme_view_list'                    => MyConst('common_theme_view_list'),
            'common_theme_business_images_list'         => MyConst('common_theme_business_images_list'),
            'common_theme_business_text_input_list'     => MyConst('common_theme_business_text_input_list'),
            'common_theme_business_text_textarea_list'  => MyConst('common_theme_business_text_textarea_list'),
            // 文章选择类型
            'common_theme_article_type_list'            => MyConst('common_theme_article_type_list'),
            // 文章排序规则
            'common_article_order_by_type_list'         => MyConst('common_article_order_by_type_list'),
            // 商品排序规则
            'common_goods_order_by_type_list'           => MyConst('common_goods_order_by_type_list'),
            // 数据排序规则
            'common_data_order_by_rule_list'            => MyConst('common_data_order_by_rule_list'),
        ]);
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(ThemeDataService::ThemeDataSave($this->data_request));
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function StatusUpdate()
    {
        return ApiService::ApiDataReturn(ThemeDataService::ThemeDataStatusUpdate($this->data_request));
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(ThemeDataService::ThemeDataDelete($this->data_request));
    }

    /**
     * 上传
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function Upload()
    {
        return ApiService::ApiDataReturn(ThemeDataService::ThemeDataUpload($this->data_request));
    }

    /**
     * 打包下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     */
    public function Download()
    {
        $ret = ThemeDataService::ThemeDataDownload($this->data_request);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-25
     * @desc    description
     */
    public function GoodsSearch()
    {
        // 搜索数据
        $ret = ThemeDataService::GoodsSearchList($this->data_request);
        if($ret['code'] == 0)
        {
            $ret['data']['data'] = MyView('themedata/saveinfo/public/goods_search_content', ['data'=>$ret['data']['data']]);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 文章搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-25
     * @desc    description
     */
    public function ArticleSearch()
    {
        // 搜索数据
        $ret = ThemeDataService::ArticleSearchList($this->data_request);
        if($ret['code'] == 0)
        {
            $ret['data']['data'] = MyView('themedata/saveinfo/public/article_search_content', ['data'=>$ret['data']['data']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>