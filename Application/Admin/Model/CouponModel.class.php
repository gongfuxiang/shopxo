<?php

namespace Admin\Model;
use Think\Model;

/**
 * 优惠券模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CouponModel extends CommonModel
{
    // 数据自动校验
    protected $_validate = array(       
        // 添加,编辑
        array('name', '0,60', '{%coupon_name_format}', 1, 'length', 3),
        array('type', array(0,1), '{%coupon_type_format}', 1, 'in', 3),
        array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),

        array('valid_start_time', 'require', '{%coupon_valid_start_time_format}', 1),
        array('valid_end_time', 'require', '{%coupon_valid_end_time_format}', 1),
        array('price', 'CheckPrice', '{%coupon_price_format}', 1, 'function', 3),
    );
}
?>