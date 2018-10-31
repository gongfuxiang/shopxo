<?php

namespace Api\Controller;

use Service\AlipayLifeService;

/**
 * 支付宝生活号回调处理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class AlipayLifeController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();
    }

    /**
     * 购买确认
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    public function Index()
    {
        // 参数
        $params = $_POST;
        if(empty($params['service']))
        {
            die('service error');
        }

        // 类库
        $obj = new \Library\AlipayLife($params);
        
        // 根据方法处理
        switch($params['service'])
        {
            // 校验
            case 'alipay.service.check' :
                $obj->Check();
                break;

            // 关注/取消
            case 'alipay.mobile.public.message.notify' :
                $obj->Life();
                break;

            // 默认
            default :
                exit('service error');
        }
    }

    /**
     * 消息发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    public function MessageSend()
    {
        AlipayLifeService::MessageSend($_REQUEST);
    }

    /**
     * 菜单发布
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    public function MenuRelease()
    {
        AlipayLifeService::MenuRelease($_REQUEST);
    }

    /**
     * 生活号批量上下架
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    public function StatusHandle()
    {
        AlipayLifeService::StatusHandle($_REQUEST);
    }
}
?>