<?php

namespace Service;

/**
 * 支付宝生活号服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeService
{
    /**
     * 根据appid获取一条生活号事件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppidLifeRow($params = [])
    {
        if(!empty($params['appid']))
        {
            return M('AlipayLife')->where(['appid'=>$params['appid']])->find();
        }
        return '';
    }
}
?>