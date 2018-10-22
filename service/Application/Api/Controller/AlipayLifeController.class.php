<?php

namespace Api\Controller;

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
        file_put_contents('./gggggg.txt', json_encode($_GET));
        file_put_contents('./pppppp.txt', json_encode($_POST));
        file_put_contents('./ffffff.txt', json_encode(file_get_contents("php://input")));

     

        // 参数
        $params = $_POST;
        if(empty($params['service']))
        {
            die('service error');
        }

        // 类库
        $o = new \Library\AlipayLife($params);

        // 根据方法处理
        switch($params['service'])
        {
            // 校验
            case 'alipay.service.check' :
                $o->Check();
                break;

            // 默认
            default :
                exit('service error');
        }
    }

}
?>