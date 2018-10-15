<?php

namespace Home\Controller;

use Service\MessageService;

/**
 * 消息管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MessageController extends CommonController
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
     * 消息列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
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
        $where = MessageService::UserMessgeListWhere($params);

        // 获取总数
        $total = MessageService::MessageTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'url'       =>  U('Home/Message/Index'),
            );
        $page = new \Library\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'limit_start'   => $page->GetPageStarNumber(),
            'limit_number'  => $number,
            'where'         => $where,
        );
        $data = MessageService::MessageList($data_params);
        $this->assign('data_list', $data['data']);

        // 消息更新未已读
        MessageService::MessageRead($params);

        // 业务类型
        $this->assign('common_business_type_list', L('common_business_type_list'));

        // 消息类型
        $this->assign('common_message_type_list', L('common_message_type_list'));

        // 是否已读
        $this->assign('common_is_read_list', L('common_is_read_list'));

        // 参数
        $this->assign('params', $params);
        $this->display('Index');
    }

}
?>