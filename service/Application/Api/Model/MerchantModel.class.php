<?php

namespace Home\Model;
use Think\Model;

/**
 * 站点模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MerchantModel extends CommonModel
{
    // 数据自动校验
    protected $_validate = array(       
        // 添加,编辑
        array('name', '2,60', '{%merchant_name_format}', 1, 'length', 3),
        array('license_name', '2,80', '{%merchant_license_name_format}', 1, 'length', 1),
        array('license_number', '2,60', '{%merchant_license_number_format}', 1, 'length', 1),
        array('province', 'require', '{%merchant_province_format}', 1, '', 3),
        array('city', 'require', '{%merchant_city_format}', 1, '', 3),
        array('county', 'require', '{%merchant_county_format}', 1, '', 3),
        array('address', '2,80', '{%merchant_address_format}', 1, 'length', 3),
        array('lng', 'require', '{%merchant_coordinate_format}', 1, '', 3),
        array('lat', 'require', '{%merchant_coordinate_format}', 1, '', 3),
        array('contacts_name', '2,60', '{%merchant_contacts_name_format}', 1, 'length', 3),
        array('contacts_tel', '2,15', '{%merchant_contacts_tel_format}', 1, 'length', 3),
        array('courier_number', 'require', '{%merchant_courier_number_format}', 1, '', 3),
        array('license_images', 'require', '{%merchant_license_images_format}', 1, '', 1),
        array('courier_health_images', 'require', '{%merchant_courier_health_images_format}', 1, '', 3),
    );
}
?>