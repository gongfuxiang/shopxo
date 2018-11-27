<?php

namespace Admin\Model;
use Think\Model;

/**
 * 手机管理-首页导航模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppHomeNavModel extends CommonModel
{
    // 数据自动校验
    protected $_validate = array(       
        // 添加,编辑
        array('name', '2,60', '{%app_home_nav_name_format}', 1, 'length', 3),
        array('platform', array('h5','app','alipay','wechat','baidu'), '{%common_platform_format}', 1, 'in', 3),
        array('event_value', '0,255', '{%common_app_event_value_format}', 2, 'length', 3),
        array('event_type', array(0,1,2,3,4), '{%common_app_event_type_format}', 1, 'in', 3),
        array('images_url', 'require', '{%app_home_nav_images_url_format}', 1, '', 3),
        array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
    );
}
?>