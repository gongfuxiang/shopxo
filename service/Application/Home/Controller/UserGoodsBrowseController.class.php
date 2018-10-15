<?php

namespace Home\Controller;

use Service\GoodsService;

/**
 * 用户商品浏览
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserGoodsBrowseController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否登录
        $this->Is_Login();
    }
    
    /**
     * 商品浏览列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-09
     * @desc    description
     */
    public function Index()
    {
        // 参数
        $params = array_merge($_POST, $_GET);
        $params['user'] = $this->user;

        // 分页
        $number = 10;

        // 条件
        $where = GoodsService::UserGoodsBrowseListWhere($params);

        // 获取总数
        $total = GoodsService::GoodsBrowseTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'url'       =>  U('Home/UserGoodsBrowse/Goods'),
            );
        $page = new \Library\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'limit_start'   => $page->GetPageStarNumber(),
            'limit_number'  => $number,
            'where'         => $where,
        );
        $data = GoodsService::GoodsBrowseList($data_params);
        $this->assign('data_list', $data['data']);
        $this->assign('ids', empty($data['data']) ? '' : implode(',', array_column($data['data'], 'id')));

        // 参数
        $this->assign('params', $params);
        $this->display('Index');
    }

    /**
     * 商品浏览删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        $params = $_POST;
        $params['user'] = $this->user;
        $ret = GoodsService::GoodsBrowseDelete($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>