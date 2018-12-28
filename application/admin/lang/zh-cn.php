<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

/**
 * 模块语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
    // 站点关闭状态列表
    'site_site_state_list'          =>  array(
            0 => array('value' => 0, 'name' => '关闭', 'checked' => true),
            1 => array('value' => 1, 'name' => '开启'),
        ),

    // 是否开启用户注册
    'site_user_reg_state_list'          =>  array(
            0 => array('value' => 'sms', 'name' => '短信'),
            1 => array('value' => 'email', 'name' => '邮箱'),
        ),

    // 是否开启用户登录
    'site_user_login_state_list'            =>  array(
            0 => array('value' => 0, 'name' => '关闭'),
            1 => array('value' => 1, 'name' => '开启', 'checked' => true),
        ),

    // 获取验证码-强制使用图片验证码状态列表
    'site_img_verify_state_list'        =>  array(
            0 => array('value' => 0, 'name' => '关闭'),
            1 => array('value' => 1, 'name' => '开启', 'checked' => true),
        ),

    // 时区
    'site_timezone_list' => array(
        'Pacific/Pago_Pago' => '(标准时-11:00) 中途岛、萨摩亚群岛',
        'Pacific/Rarotonga' => '(标准时-10:00) 夏威夷',
        'Pacific/Gambier' => '(标准时-9:00) 阿拉斯加',
        'America/Dawson' => '(标准时-8:00) 太平洋时间(美国和加拿大)',
        'America/Creston' => '(标准时-7:00) 山地时间(美国和加拿大)',
        'America/Belize' => '(标准时-6:00) 中部时间(美国和加拿大)、墨西哥城',
        'America/Eirunepe' => '(标准时-5:00) 东部时间(美国和加拿大)、波哥大',
        'America/Antigua' => '(标准时-4:00) 大西洋时间(加拿大)、加拉加斯',
        'America/Argentina/Buenos_Aires' => '(标准时-3:00) 巴西、布宜诺斯艾利斯、乔治敦',
        'America/Noronha' => '(标准时-2:00) 中大西洋',
        'Atlantic/Cape_Verde' => '(标准时-1:00) 亚速尔群岛、佛得角群岛',
        'Africa/Ouagadougou' => '(格林尼治标准时) 西欧时间、伦敦、卡萨布兰卡',
        'Europe/Andorra' => '(标准时+1:00) 中欧时间、安哥拉、利比亚',
        'Europe/Mariehamn' => '(标准时+2:00) 东欧时间、开罗，雅典',
        'Asia/Bahrain' => '(标准时+3:00) 巴格达、科威特、莫斯科',
        'Asia/Dubai' => '(标准时+4:00) 阿布扎比、马斯喀特、巴库',
        'Asia/Kolkata' => '(标准时+5:00) 叶卡捷琳堡、伊斯兰堡、卡拉奇',
        'Asia/Dhaka' => '(标准时+6:00) 阿拉木图、 达卡、新亚伯利亚',
        'Indian/Christmas' => '(标准时+7:00) 曼谷、河内、雅加达',
        'Australia/Eucla' => '(标准时+8:00)北京、重庆、香港、新加坡',
        'Australia/Darwin' => '(标准时+9:00) 东京、汉城、大阪、雅库茨克',
        'Australia/Adelaide' => '(标准时+10:00) 悉尼、关岛',
        'Australia/Currie' => '(标准时+11:00) 马加丹、索罗门群岛',
        'Pacific/Fiji' => '(标准时+12:00) 奥克兰、惠灵顿、堪察加半岛'
    ),

    // seo
    // url模式列表
    'seo_url_model_list'        =>  array(
            0 => array('value' => 0, 'name' => '兼容模式', 'checked' => true),
            1 => array('value' => 1, 'name' => 'PATHINFO模式'),
        ),

    // 缓存类型列表
    'cache_type_list'           =>  array(
            array(
                'is_enable' => 1,
                'name' => '站点缓存',
                'url' => MyUrl('admin/cache/statusupdate'),
                'desc' => '数据转换后或前台不能正常访问时，可以使用此功能更新所有缓存'
            ),
            array(
                'is_enable' => 1,
                'name' => '模板缓存',
                'url' => MyUrl('admin/cache/templateupdate'),
                'desc' => '当页面显示不正常，可尝试使用此功能修复'
            ),
            array(
                'is_enable' => 0,
                'name' => '模块缓存',
                'url' => MyUrl('admin/cache/moduleupdate'),
                'desc' => '更新页面布局与模块后未生效，可尝试使用此功能修复'
            ),
            array(
                'is_enable' => 1,
                'name' => '日志清除',
                'url' => MyUrl('admin/cache/logdelete'),
                'desc' => '清除站点日志'
            ),
        ),

    // 用户excel导出标题列表
    'excel_user_title_list'     =>  array(
            'username'      =>  array(
                    'col' => 'A',
                    'name' => '姓名',
                    'type' => 'string',
                ),
            'nickname'      =>  array(
                    'col' => 'B',
                    'name' => '昵称',
                    'type' => 'int',
                ),
            'gender_text'   =>  array(
                    'col' => 'C',
                    'name' => '性别',
                    'type' => 'string',
                ),
            'birthday_text'=>   array(
                    'col' => 'D',
                    'name' => '生日',
                    'type' => 'string',
                ),
            'mobile'        =>  array(
                    'col' => 'E',
                    'name' => '手机号码',
                    'type' => 'int',
                ),
            'email'         =>  array(
                    'col' => 'F',
                    'name' => '电子邮箱',
                    'type' => 'string',
                ),
            'province'      =>  array(
                    'col' => 'G',
                    'name' => '所在省',
                    'type' => 'string',
                ),
            'city'      =>  array(
                    'col' => 'H',
                    'name' => '所在市',
                    'type' => 'string',
                ),
            'address'       =>  array(
                    'col' => 'I',
                    'name' => '详细地址',
                    'type' => 'string',
                ),
            'add_time'      =>  array(
                    'col' => 'J',
                    'name' => '注册时间',
                    'type' => 'string',
                ),
        ),
);
?>