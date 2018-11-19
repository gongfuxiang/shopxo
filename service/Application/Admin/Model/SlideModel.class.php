<?php

namespace Admin\Model;
use Think\Model;

/**
 * 轮播图片模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SlideModel extends CommonModel
{
    // 数据自动校验
    protected $_validate = array(       
        // 添加,编辑
        array('name', '2,60', '{%slide_name_format}', 1, 'length', 3),
        array('jump_url', '0,255', '{%common_jump_url_format}', 2, 'length', 3),
        array('images_url', 'require', '{%slide_images_url_format}', 1, '', 3),
        array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
    );
}
?>