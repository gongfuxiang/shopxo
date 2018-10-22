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

            // 关注/取消
            case 'alipay.mobile.public.message.notify' :
                file_put_contents('./pppppp.php', "<?php\n\rreturn ".var_export($params['service'], true).";\n\r?>");
                $o->Life();
                break;

            // 默认
            default :
                exit('service error');
        }
    }

}
?>