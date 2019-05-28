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
namespace app\admin\controller;

use app\service\GoodsCommentsService;

/**
 * 商品评论管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Goodscomments extends Common
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
     * 问答列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 参数
        $params = input();

        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = GoodsCommentsService::GoodsCommentsListWhere($params);

        // 获取总数
        $total = GoodsCommentsService::GoodsCommentsTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  MyUrl('admin/goodscomments/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
            'is_public' => 0,
        );
        $data = GoodsCommentsService::GoodsCommentsList($data_params);
        $this->assign('data_list', $data['data']);

        // 静态数据
        $this->assign('common_is_show_list', lang('common_is_show_list'));
        $this->assign('common_is_text_list', lang('common_is_text_list'));
        $this->assign('common_goods_comments_rating_list', lang('common_goods_comments_rating_list'));
        $this->assign('common_goods_rating_business_type_list', lang('common_goods_rating_business_type_list'));

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
    }

    /**
     * [SaveInfo 添加/编辑页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 参数
        $params = input();

        // 数据
        $data = [];
        if(!empty($params['id']))
        {
            // 获取列表
            $data_params = array(
                'm'         => 0,
                'n'         => 1,
                'where'     => ['id'=>intval($params['id'])],
                'is_public' => 0,
            );
            $ret = GoodsCommentsService::GoodsCommentsList($data_params);
            $data = empty($ret['data'][0]) ? [] : $ret['data'][0];
        }
        $this->assign('data', $data);

        // 静态数据
        $this->assign('common_is_show_list', lang('common_is_show_list'));
        $this->assign('common_is_text_list', lang('common_is_text_list'));
        $this->assign('common_goods_comments_rating_list', lang('common_goods_comments_rating_list'));
        $this->assign('common_goods_rating_business_type_list', lang('common_goods_rating_business_type_list'));

        // 参数
        unset($params['id']);
        $this->assign('params', $params);

        return $this->fetch();
    }

    /**
     * [Save 保存]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Save()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        return GoodsCommentsService::GoodsCommentsSave($params);
    }

    /**
     * 问答删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        return GoodsCommentsService::GoodsCommentsDelete($params);
    }

    /**
     * 问答回复处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-03-28T15:07:17+0800
     */
    public function Reply()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        return GoodsCommentsService::GoodsCommentsReply($params);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        return GoodsCommentsService::GoodsCommentsStatusUpdate($params);
    }
}
?>