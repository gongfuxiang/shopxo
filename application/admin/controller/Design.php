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

use app\service\DesignService;
use app\service\GoodsService;
use app\service\BrandService;
use app\layout\service\BaseLayout;

/**
 * 页面设计管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class Design extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
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
     * 首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Index()
    {
        // 总数
        $total = DesignService::DesignTotal($this->form_where);

        // 分页
        $page_params = [
            'number'    =>  $this->page_size,
            'total'     =>  $total,
            'where'     =>  $this->data_request,
            'page'      =>  $this->page,
            'url'       =>  MyUrl('admin/design/index'),
        ];
        $page = new \base\Page($page_params);

        // 获取列表
        $data_params = [
            'm'             => $page->GetPageStarNumber(),
            'n'             => $this->page_size,
            'where'         => $this->form_where,
            'order_by'      => $this->form_order_by['data'],
        ];
        $ret = DesignService::DesignList($data_params);

        // 基础参数赋值
        $this->assign('params', $this->data_request);
        $this->assign('page_html', $page->GetPageHtml());
        $this->assign('data_list', $ret['data']);
        return $this->fetch();
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
        // 是否指定id、不存在则增加数据
        if(empty($this->data_request['id']))
        {
            $ret = DesignService::DesignSave();
            if($ret['code'] == 0)
            {
                return redirect(MyUrl('admin/design/saveinfo', ['id'=>$ret['data']]));
            } else {
                $this->assign('msg', $ret['msg']);
                return $this->fetch('public/tips_error');
            }
        }

        // 获取数据
        $data_params = [
            'where' => [
                'id' => intval($this->data_request['id']),
            ],
            'm' => 0,
            'n' => 1,
        ];
        $ret = DesignService::DesignList($data_params);
        if(empty($ret['data']) || empty($ret['data'][0]))
        {
            $this->assign('to_title', '去添加 >>');
            $this->assign('to_url', MyUrl('admin/design/saveinfo'));
            $this->assign('msg', '编辑数据为空、请重新添加');
            return $this->fetch('public/tips_error');
        }
        $data = $ret['data'][0];

        // 配置处理
        $layout_data = BaseLayout::ConfigAdminHandle($data['config']);
        $this->assign('layout_data', $layout_data);
        $this->assign('data', $data);
        unset($data['config']);

        // 页面列表
        $pages_list = BaseLayout::PagesList();
        $this->assign('pages_list', $pages_list);

        // 商品分类
        $goods_category = GoodsService::GoodsCategoryAll();
        $this->assign('goods_category_list', $goods_category);

        // 商品搜索分类（分类）
        $this->assign('layout_goods_category', $goods_category);
        $this->assign('layout_goods_category_field', 'gci.category_id');

        // 品牌
        $this->assign('brand_list', BrandService::CategoryBrand());

        // 静态数据
        $this->assign('border_style_type_list', BaseLayout::$border_style_type_list);
        $this->assign('goods_view_list_show_style', BaseLayout::$goods_view_list_show_style);
        $this->assign('many_images_view_list_show_style', BaseLayout::$many_images_view_list_show_style);

        // 首页商品排序规则
        $this->assign('goods_order_by_type_list', lang('goods_order_by_type_list'));
        $this->assign('goods_order_by_rule_list', lang('goods_order_by_rule_list'));

        // 加载布局样式+管理
        $this->assign('is_load_layout', 1);
        $this->assign('is_load_layout_admin', 1);

        // 编辑器文件存放地址定义
        $this->assign('editor_path_type', DesignService::AttachmentPathTypeValue($data['id']));
        return $this->fetch();
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return DesignService::DesignSave($this->data_post);
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
        // 是否ajax
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始操作
        return DesignService::DesignStatusUpdate($this->data_post);
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
        // 是否ajax
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始操作
        return DesignService::DesignDelete($this->data_post);
    }
}
?>