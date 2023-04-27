<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

/**
 * 模块语言包-泰语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'การเคลื่อนไหวของปริมาณการซื้อขายตามคำสั่ง',
            'order_trading_trend_name'          => 'ความเคลื่อนไหวการซื้อขายคำสั่ง',
            'goods_hot_name'                    => 'สินค้าขายร้อน',
            'goods_hot_tips'                    => 'แสดงเฉพาะ 30 รายการแรก',
            'payment_name'                      => 'วิธีการชำระเงิน',
            'order_region_name'                 => 'การสั่งซื้อการกระจายภูมิภาค',
            'order_region_tips'                 => 'แสดงเพียง 30 ข้อมูล',
            'upgrade_check_loading_tips'        => 'กำลังรับข้อมูลล่าสุด โปรดรอสักครู่...',
            'upgrade_version_name'              => 'รุ่นปรับปรุง:',
            'upgrade_date_name'                 => 'อัปเดต:',
        ],
        // 页面基础
        'base_update_button_title'              => 'อัปเดตตอนนี้',
        'base_item_base_stats_title'            => 'สถิติเดอะมอลล์',
        'base_item_base_stats_tips'             => 'การคัดกรองเวลามีผลเฉพาะกับยอดรวม',
        'base_item_user_title'                  => 'จำนวนผู้ใช้',
        'base_item_order_number_title'          => 'ยอดการสั่งซื้อทั้งหมด',
        'base_item_order_complete_number_title' => 'ปริมาณการซื้อขาย',
        'base_item_order_complete_title'        => 'ยอดการสั่งซื้อทั้งหมด',
        'base_item_last_month_title'            => 'เดือนที่แล้ว',
        'base_item_same_month_title'            => 'เดือนปัจจุบัน',
        'base_item_yesterday_title'             => 'เมื่อวานนี้',
        'base_item_today_title'                 => 'วันนี้',
        'base_item_order_profit_title'          => 'การเคลื่อนไหวของปริมาณการซื้อขายตามคำสั่ง',
        'base_item_order_trading_title'         => 'ความเคลื่อนไหวการซื้อขายคำสั่ง',
        'base_item_order_tips'                  => 'คำสั่งซื้อทั้งหมด',
        'base_item_hot_sales_goods_title'       => 'สินค้าขายร้อน',
        'base_item_hot_sales_goods_tips'        => 'ไม่มีคำสั่งยกเลิกการปิด',
        'base_item_payment_type_title'          => 'วิธีการชำระเงิน',
        'base_item_map_whole_country_title'     => 'การสั่งซื้อการกระจายภูมิภาค',
        'base_item_map_whole_country_tips'      => 'ไม่รวมคำสั่งยกเลิกการปิด,มิติเริ่มต้น (จังหวัด)',
        'base_item_map_whole_country_province'  => 'จังหวัด',
        'base_item_map_whole_country_city'      => 'เมือง',
        'base_item_map_whole_country_county'    => 'อำเภอ/เขต',
        'system_info_title'                     => 'ข้อมูลระบบ',
        'system_ver_title'                      => 'รุ่นซอฟต์แวร์',
        'system_os_ver_title'                   => 'ระบบปฏิบัติการ',
        'system_php_ver_title'                  => 'เวอร์ชัน PHP',
        'system_mysql_ver_title'                => 'รุ่น MySQL',
        'system_server_ver_title'               => 'ข้อมูลฝั่งเซิร์ฟเวอร์',
        'system_host_title'                     => 'ชื่อโดเมนปัจจุบัน',
        'development_team_title'                => 'ทีมพัฒนา',
        'development_team_website_title'        => 'เว็บไซต์อย่างเป็นทางการของ บริษัท',
        'development_team_website_value'        => 'เซี่ยงไฮ้ Lingzhige เทคโนโลยี จำกัด',
        'development_team_support_title'        => 'การสนับสนุนทางเทคนิค',
        'development_team_support_value'        => 'ShopXO ผู้ให้บริการระบบอีคอมเมิร์ซระดับองค์กร',
        'development_team_ask_title'            => 'แลกเปลี่ยนคำถาม',
        'development_team_ask_value'            => 'ShopXO แลกเปลี่ยนคำถาม',
        'development_team_agreement_title'      => 'โปรโตคอลโอเพนซอร์ส',
        'development_team_agreement_value'      => 'ดูโปรโตคอลโอเพนซอร์ส',
        'development_team_update_log_title'     => 'บันทึกการปรับปรุง',
        'development_team_update_log_value'     => 'ดูบันทึกการปรับปรุง',
        'development_team_members_title'        => 'สมาชิก R & D',
        'development_team_members_value'        => [
            ['name' => 'พี่กุ้ง', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'ผู้ใช้',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'รหัสผู้ใช้',
            'number_code'           => 'รหัสสมาชิก',
            'system_type'           => 'ประเภทของระบบ',
            'platform'              => 'แพลตฟอร์มที่เป็น',
            'avatar'                => 'อวตาร',
            'username'              => 'ชื่อผู้ใช้',
            'nickname'              => 'ชื่อเล่น',
            'mobile'                => 'โทรศัพท์มือถือ',
            'email'                 => 'กล่องจดหมาย',
            'gender_name'           => 'เพศ',
            'status_name'           => 'สถานะ',
            'province'              => 'จังหวัด',
            'city'                  => 'เมืองที่ตั้งอยู่',
            'county'                => 'อำเภอ/เขต',
            'address'               => 'รายละเอียดที่อยู่',
            'birthday'              => 'วันเกิด',
            'integral'              => 'คะแนนที่มีอยู่',
            'locking_integral'      => 'ล็อคคะแนน',
            'referrer'              => 'เชิญผู้ใช้',
            'referrer_placeholder'  => 'โปรดป้อนชื่อผู้ใช้คำเชิญ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'add_time'              => 'เวลาลงทะเบียน',
            'upd_time'              => 'อัปเดตเวลา',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'ที่อยู่ผู้ใช้',
        // 详情
        'detail_user_address_idcard_name'       => 'ชื่อ-นามสกุล',
        'detail_user_address_idcard_number'     => 'หมายเลข',
        'detail_user_address_idcard_pic'        => 'รูปภาพ',
        // 动态表格
        'form_table'                            => [
            'user'              => 'ข้อมูลผู้ใช้',
            'user_placeholder'  => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'alias'             => 'นามแฝง',
            'name'              => 'ติดต่อ',
            'tel'               => 'เบอร์ติดต่อ',
            'province_name'     => 'จังหวัดที่สังกัด',
            'city_name'         => 'เมืองที่เกี่ยวข้อง',
            'county_name'       => 'อำเภอ/เขต',
            'address'           => 'รายละเอียดที่อยู่',
            'position'          => 'ลองจิจูดและละติจูด',
            'idcard_info'       => 'ข้อมูลบัตรประชาชน',
            'is_default'        => 'ปริยายหรือไม่',
            'add_time'          => 'สร้างเวลา',
            'upd_time'          => 'อัปเดตเวลา',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'บันทึกมีผลหลังจากการลบยืนยันการดำเนินการต่อ?',
            'address_no_data'                   => 'ข้อมูลที่อยู่ว่างเปล่า',
            'address_not_exist'                 => 'ไม่มีที่อยู่',
            'address_logo_message'              => 'กรุณาอัพโหลดรูปภาพโลโก้',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'การกำหนดค่าพื้นฐาน', 'type' => 'base'],
            ['name' => 'การตั้งค่าเว็บไซต์', 'type' => 'siteset'],
            ['name' => 'ประเภทของเว็บไซต์', 'type' => 'sitetype'],
            ['name' => 'ลงทะเบียนผู้ใช้', 'type' => 'register'],
            ['name' => 'เข้าสู่ระบบผู้ใช้', 'type' => 'login'],
            ['name' => 'การกู้คืนรหัสผ่าน', 'type' => 'forgetpwd'],
            ['name' => 'รหัสยืนยัน', 'type' => 'verify'],
            ['name' => 'สั่งซื้อหลังการขาย', 'type' => 'orderaftersale'],
            ['name' => 'อุปกรณ์เสริม', 'type' => 'attachment'],
            ['name' => 'แคช', 'type' => 'cache'],
            ['name' => 'ส่วนขยาย', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'หน้าหลัก', 'type' => 'index'],
            ['name' => 'สินค้าโภคภัณฑ์', 'type' => 'goods'],
            ['name' => 'ค้นหา', 'type' => 'search'],
            ['name' => 'การสั่งซื้อ', 'type' => 'order'],
            ['name' => 'ข้อเสนอพิเศษ', 'type' => 'discount'],
            ['name' => 'ส่วนขยาย', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'สถานะเว็บไซต์',
        'base_item_site_domain_title'           => 'ที่อยู่โดเมนเว็บไซต์',
        'base_item_site_filing_title'           => 'ข้อมูลการยื่น',
        'base_item_site_other_title'            => 'อื่นๆ',
        'base_item_session_cache_title'         => 'การกำหนดค่าแคช Session',
        'base_item_data_cache_title'            => 'การกำหนดค่าแคชข้อมูล',
        'base_item_redis_cache_title'           => 'การกำหนดค่าแคช redis',
        'base_item_crontab_config_title'        => 'การกำหนดค่าสคริปต์แบบตั้งเวลา',
        'base_item_quick_nav_title'             => 'นำทางด่วน',
        'base_item_user_base_title'             => 'ฐานผู้ใช้',
        'base_item_user_address_title'          => 'ที่อยู่ผู้ใช้',
        'base_item_multilingual_title'          => 'หลายภาษา',
        'base_item_site_auto_mode_title'        => 'โหมดอัตโนมัติ',
        'base_item_site_manual_mode_title'      => 'โหมดแมนนวล',
        'base_item_default_payment_title'       => 'วิธีการชำระเงินเริ่มต้น',
        'base_item_display_type_title'          => 'ประเภทการแสดงผล',
        'base_item_self_extraction_title'       => 'การยกระดับด้วยตนเอง',
        'base_item_fictitious_title'            => 'การขายเสมือนจริง',
        'choice_upload_logo_title'              => 'เลือกโลโก้',
        'add_goods_title'                       => 'สินค้า เพิ่ม',
        'add_self_extractio_address_title'      => 'เพิ่มที่อยู่',
        'site_domain_tips_list'                 => [
            '1. ชื่อโดเมนเว็บไซต์ ไม่ได้ตั้งค่า แล้วใช้ชื่อโดเมนเว็บไซต์ปัจจุบัน ชื่อโดเมน ที่อยู่[ '.__MY_DOMAIN__.' ]',
            '2. สิ่งที่แนบมาและที่อยู่แบบคงที่ไม่ได้ตั้งค่า จากนั้นใช้ที่อยู่โดเมนแบบคงที่ของเว็บไซต์ปัจจุบัน[ '.__MY_PUBLIC_URL__.' ]',
            '3. เช่นฝั่งเซิร์ฟเวอร์ที่ไม่ได้รากเป็นสาธารณะ, กำหนดค่าที่นี่ [แนบชื่อโดเมน cdn, css / js ไฟล์คงที่ชื่อโดเมน cdn] จำเป็นต้องเพิ่มสาธารณชนเช่น:'.__MY_PUBLIC_URL__.'public/',
            '4 เรียกใช้โครงการในโหมดบรรทัดคำสั่งที่อยู่ของพื้นที่จะต้องกำหนดค่ามิฉะนั้นที่อยู่บางอย่างในโครงการจะหายไปข้อมูลโดเมน',
            '5. อย่ากำหนดค่าอย่างสะเปะสะปะที่อยู่ผิดทำให้เว็บไซต์ไม่สามารถเข้าถึงได้ (การกำหนดค่าที่อยู่เริ่มต้นด้วย http) หากการกำหนดค่าของสถานีของตัวเองเริ่มต้นด้วย https',
        ],
        'site_cache_tips_list'                  => [
            '1 แคชไฟล์ที่ใช้ค่าเริ่มต้นแคช PHP กับ Redis ต้องติดตั้งส่วนขยาย Redis ก่อน',
            '2. โปรดตรวจสอบความเสถียรของบริการ Redis (หลังจาก Session ใช้แคชบริการไม่เสถียรอาจทำให้พื้นหลังไม่สามารถเข้าสู่ระบบได้)',
            '3. หากคุณพบความผิดปกติของบริการ Redis ไม่สามารถเข้าสู่ระบบพื้นหลังการจัดการแก้ไขไฟล์ [session.php, cache.php] ใต้ไดเรกทอรี [config]',
        ],
        'goods_tips_list'                       => [
            '1. สิ้นสุดเว็บจะแสดง 3 ระดับโดยค่าเริ่มต้นต่ำสุด 1 ระดับและสูงสุด 3 ระดับ (ค่าเริ่มต้นคือ 3 ระดับถ้าตั้งค่าไว้ที่ระดับ 0)',
            '2. การแสดงผลค่าเริ่มต้นของโทรศัพท์ 0 ระดับ (โหมดรายการสินค้า), ต่ำสุด 0 ระดับ, สูงสุด 3 ระดับ (1 ~ 3 เป็นโหมดการจัดหมวดหมู่การแสดงผล)',
            '3. ระดับที่แตกต่างกันและรูปแบบหน้าการจัดหมวดหมู่ด้านหน้าจะแตกต่างกัน',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. กำหนดค่าจำนวนสินค้าที่แสดงสูงสุดต่อชั้น',
            '2. ไม่แนะนำให้ปรับเปลี่ยนปริมาณมากเกินไปอาจทำให้พื้นที่ว่างด้านซ้ายของปลายพีซีมีขนาดใหญ่เกินไป',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'รวมเป็น: ความร้อน -> ยอดขาย -> ล่าสุดดำเนินการเรียงลำดับจากมากไปหาน้อย (desc)',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. สามารถคลิกชื่อสินค้าเพื่อลากเรียงลำดับและแสดงตามลำดับ',
            '2. ไม่แนะนำให้เพิ่มสินค้าจำนวนมากอาจทำให้พื้นที่ว่างด้านซ้ายของปลายพีซีมีขนาดใหญ่เกินไป',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. เริ่มต้นด้วย [ชื่อผู้ใช้โทรศัพท์มือถือกล่องจดหมาย] เป็นเอกลักษณ์ของผู้ใช้',
            '2. เปิดแล้วเข้าร่วม [การระบุระบบ] ขนานเป็นเอกลักษณ์ของผู้ใช้',
        ],
        'extends_crontab_tips'                  => 'ขอแนะนำให้เพิ่มที่อยู่สคริปต์ไปยังคำขอกำหนดเวลางานลินุกซ์ก็โอเค (ผลลัพธ์ sucs: 0, fail: 0 ทวิภาคตามด้วยจำนวนแถบข้อมูลที่ประมวลผล sucs สำเร็จ, fali ล้มเหลว)',
        'left_images_random_tips'               => 'รูปภาพด้านซ้ายสามารถอัปโหลดได้สูงสุด 3 ภาพ สุ่มแสดงภาพหนึ่งในแต่ละครั้ง',
        'background_color_tips'                 => 'ภาพพื้นหลังที่ปรับแต่งได้, สีเทาฐานเริ่มต้น',
        'site_setup_layout_tips'                => 'โหมดลากต้องเข้าสู่หน้าการออกแบบหน้าแรกด้วยตัวเองโปรดบันทึกการกำหนดค่าที่เลือกไว้ก่อน',
        'site_setup_layout_button_name'         => 'ไปที่หน้าออกแบบ >>',
        'site_setup_goods_category_tips'        => 'สำหรับการแสดงชั้นเพิ่มเติมโปรดมาถึงก่อน / การจัดการสินค้า -> การจำแนกประเภทสินค้าการจัดหมวดหมู่ชั้นหนึ่งหน้าแรก แนะนำ',
        'site_setup_goods_category_no_data_tips'=> 'ไม่มีข้อมูลในขณะนี้โปรดมาถึงก่อน / การจัดการสินค้า -> การจำแนกประเภทสินค้า, การตั้งค่าการจำแนกประเภทชั้นหนึ่ง หน้าแรก แนะนำ',
        'site_setup_order_default_payment_tips' => 'สามารถตั้งค่าวิธีการชำระเงินเริ่มต้นที่สอดคล้องกับแพลตฟอร์มที่แตกต่างกันโปรดติดตั้งปลั๊กอินการชำระเงินที่ดีใน [การจัดการเว็บไซต์ -> วิธีการชำระเงิน] เปิดใช้งานและเปิดให้ผู้ใช้',
        'site_setup_choice_payment_message'     => 'โปรดเลือก {:name} วิธีการชำระเงินเริ่มต้น',
        'sitetype_top_tips_list'                => [
            '1. จัดส่งด่วนกระบวนการอีคอมเมิร์ซปกติผู้ใช้เลือกที่อยู่รับสินค้าสั่งซื้อและชำระเงิน -> ร้านค้าจัดส่ง -> ยืนยันการรับสินค้า -> สั่งซื้อเสร็จสมบูรณ์',
            '2. ประเภทการแสดงผลแสดงผลิตภัณฑ์เท่านั้นสามารถเริ่มให้คำปรึกษาได้ (ไม่สามารถสั่งซื้อได้)',
            '3. จุดรับตัวเองเลือกที่อยู่รับสินค้าด้วยตนเองเมื่อสั่งซื้อผู้ใช้สั่งซื้อการชำระเงิน -> ยืนยันการรับสินค้า -> การสั่งซื้อเสร็จสมบูรณ์',
            '4. การขายเสมือนจริงกระบวนการทางอีคอมเมิร์ซปกติการชำระเงินสำหรับการสั่งซื้อของผู้ใช้ -> การจัดส่งอัตโนมัติ -> ยืนยันการรับสินค้า -> การสั่งซื้อเสร็จสมบูรณ์',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'แนะนำ 300 * 300px',
        'form_take_address_alias'               => 'นามแฝง',
        'form_take_address_alias_message'       => 'รูปแบบนามแฝง สูงสุด 16 ตัวอักษร',
        'form_take_address_name'                => 'ติดต่อ',
        'form_take_address_name_message'        => 'รูปแบบการติดต่อ ระหว่าง 2 ~ 16 ตัวอักษร',
        'form_take_address_tel'                 => 'เบอร์ติดต่อ',
        'form_take_address_tel_message'         => 'กรุณากรอกเบอร์ติดต่อ',
        'form_take_address_address'             => 'รายละเอียดที่อยู่',
        'form_take_address_address_message'     => 'รูปแบบที่อยู่โดยละเอียด ระหว่าง 1 ~ 80 ตัวอักษร',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'เข้าสู่ระบบ Back Office',
        'admin_login_info_bg_images_list_tips'  => [
            '1. ภาพพื้นหลังอยู่ภายใต้ไดเรกทอรี [public/static/admin/default/images/login]',
            '2. กฎการตั้งชื่อภาพพื้นหลัง (1 ~ 50) เช่น 1.jpg',
        ],
        'map_type_tips'                         => 'เนื่องจากแต่ละบ้านมีมาตรฐานแผนที่ไม่เท่ากัน,ห้ามสลับแผนที่ตามอำเภอใจ,กรณีที่จะทำให้มีการทำเครื่องหมายพิกัดแผนที่ไม่ถูกต้อง',
        'apply_map_baidu_name'                  => 'สมัครที่ Baidu Maps Open Platform',
        'apply_map_amap_name'                   => 'กรุณาสมัครที่ Gaoder Map Open Platform',
        'apply_map_tencent_name'                => 'กรุณาสมัครที่ Tencent Map Open Platform',
        'apply_map_tianditu_name'               => 'กรุณาสมัครที่ Skymap Open Platform',
        'cookie_domain_list_tips'               => [
            '1. ค่าเริ่มต้นที่ว่างเปล่าแล้วใช้ได้กับชื่อโดเมนที่เข้าถึงปัจจุบันเท่านั้น',
            '2. หากต้องการใช้ชื่อโดเมนรองเพื่อแบ่งปันคุกกี้ให้กรอกชื่อโดเมนระดับบนสุดเช่น: baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'แบรนด์',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'ชื่อ',
            'describe'             => 'คำอธิบาย',
            'logo'                 => 'LOGO',
            'url'                  => 'ที่อยู่เว็บไซต์อย่างเป็นทางการ',
            'brand_category_text'  => 'การจำแนกประเภทแบรนด์',
            'is_enable'            => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'sort'                 => 'เรียงลำดับ',
            'add_time'             => 'สร้างเวลา',
            'upd_time'             => 'อัปเดตเวลา',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'การจำแนกประเภทแบรนด์',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'บทความ',
        'detail_content_title'                  => 'เนื้อหารายละเอียด',
        'detail_images_title'                   => 'ภาพรายละเอียด',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'ชื่อเรื่อง',
            'jump_url'               => 'ที่อยู่ URL กระโดด',
            'article_category_name'  => 'หมวดหมู่',
            'is_enable'              => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'is_home_recommended'    => 'บ้าน แนะนำ',
            'images_count'           => 'จำนวนรูปภาพ',
            'access_count'           => 'จำนวนการเข้าชม',
            'add_time'               => 'สร้างเวลา',
            'upd_time'               => 'อัปเดตเวลา',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'หมวดหมู่บทความ',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'ปรับแต่งหน้า',
        'detail_content_title'                  => 'เนื้อหารายละเอียด',
        'detail_images_title'                   => 'ภาพรายละเอียด',
        'save_view_tips'                        => 'โปรดบันทึกก่อนดูตัวอย่างผล',
        // 动态表格
        'form_table'                            => [
            'info'            => 'ชื่อเรื่อง',
            'is_enable'       => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'is_header'       => 'ไม่ว่าจะเป็นหัว',
            'is_footer'       => 'ไม่ว่าจะเป็นหาง',
            'is_full_screen'  => 'ไม่ว่าจะเป็นแบบเต็มหน้าจอ',
            'images_count'    => 'จำนวนรูปภาพ',
            'access_count'    => 'จำนวนการเข้าชม',
            'add_time'        => 'สร้างเวลา',
            'upd_time'        => 'อัปเดตเวลา',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'ดาวน์โหลดเทมเพลตการออกแบบเพิ่มเติม',
        'upload_list_tips'                      => [
            '1. เลือกแพคเกจ zip ออกแบบหน้าเว็บที่ดาวน์โหลดแล้ว',
            '2. การนำเข้าจะเพิ่มข้อมูลโดยอัตโนมัติ',
        ],
        'operate_sync_tips'                     => 'ข้อมูลซิงค์กับหน้าแรก ลากภาพ แก้ไขข้อมูลภายหลัง ไม่ได้รับผลกระทบ แต่ไม่ลบสิ่งที่แนบมา',
        // 动态表格
        'form_table'                            => [
            'id'                => 'รหัสข้อมูล',
            'info'              => 'ข้อมูลพื้นฐาน',
            'info_placeholder'  => 'กรุณากรอกชื่อ',
            'access_count'      => 'จำนวนการเข้าชม',
            'is_enable'         => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'is_header'         => 'ไม่ว่าจะมีส่วนหัว',
            'is_footer'         => 'ไม่ว่าจะมีส่วนหาง',
            'seo_title'         => 'หัวข้อ SEO',
            'seo_keywords'      => 'คำหลัก SEO',
            'seo_desc'          => 'คำอธิบาย SEO',
            'add_time'          => 'สร้างเวลา',
            'upd_time'          => 'อัปเดตเวลา',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'คำถามและคำตอบ',
        'user_info_title'                       => 'ข้อมูลผู้ใช้',
        // 动态表格
        'form_table'                            => [
            'user'              => 'ข้อมูลผู้ใช้',
            'user_placeholder'  => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'name'              => 'ติดต่อ',
            'tel'               => 'เบอร์ติดต่อ',
            'content'           => 'เนื้อหา',
            'reply'             => 'ตอบกลับเนื้อหา',
            'is_show'           => 'ไม่ว่าจะเป็นการแสดง',
            'is_reply'          => 'ไม่ว่าจะเป็นการตอบกลับ',
            'reply_time_time'   => 'เวลาตอบกลับ',
            'access_count'      => 'จำนวนการเข้าชม',
            'add_time_time'     => 'สร้างเวลา',
            'upd_time_time'     => 'อัปเดตเวลา',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'คลังสินค้า',
        'top_tips_list'                         => [
            '1. ค่าน้ำหนักที่มากขึ้นแสดงถึงน้ำหนักที่สูงขึ้นการหักสินค้าคงคลังจะถูกหักตามน้ำหนัก)',
            '2. คลังสินค้าเพียงลบอ่อนนุ่มจะไม่สามารถใช้งานได้หลังจากลบเฉพาะข้อมูลที่เก็บไว้ในฐานข้อมูล) สามารถลบข้อมูลสินค้าที่เกี่ยวข้องได้ด้วยตัวเอง',
            '3. คลังสินค้าปิดการใช้งานและลบสินค้าคงคลังที่เกี่ยวข้องจะถูกปล่อยออกทันที',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'ชื่อ / นามแฝง',
            'level'          => 'น้ำหนัก',
            'is_enable'      => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'contacts_name'  => 'ติดต่อ',
            'contacts_tel'   => 'เบอร์ติดต่อ',
            'province_name'  => 'จังหวัด',
            'city_name'      => 'เมืองที่ตั้งอยู่',
            'county_name'    => 'อำเภอ/เขต',
            'address'        => 'รายละเอียดที่อยู่',
            'position'       => 'ลองจิจูดและละติจูด',
            'add_time'       => 'สร้างเวลา',
            'upd_time'       => 'อัปเดตเวลา',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'กรุณาเลือกคลังสินค้า',
        ],
        // 基础
        'add_goods_title'                       => 'สินค้า เพิ่ม',
        'no_spec_data_tips'                     => 'ไม่มีข้อมูลจำเพาะ',
        'batch_setup_inventory_placeholder'     => 'ค่าของการตั้งค่าแบทช์',
        'base_spec_inventory_title'             => 'สเปค สต็อก',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'ข้อมูลพื้นฐาน',
            'goods_placeholder'  => 'กรุณาใส่ชื่อสินค้า / รุ่น',
            'warehouse_name'     => 'คลังสินค้า',
            'is_enable'          => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'inventory'          => 'สินค้าคงคลังทั้งหมด',
            'spec_inventory'     => 'สเปค สต็อก',
            'add_time'           => 'สร้างเวลา',
            'upd_time'           => 'อัปเดตเวลา',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'ไม่มีข้อมูลผู้ดูแลระบบ',
        // 列表
        'top_tips_list'                         => [
            '1. บัญชี admin มีสิทธิ์ทั้งหมดโดยค่าเริ่มต้นและไม่สามารถเปลี่ยนแปลงได้',
            '2. admin บัญชีไม่สามารถเปลี่ยนแปลงได้ แต่สามารถแก้ไขได้ ('.MyConfig ('database.connections.mysql.prefix').'admin) ฟิลด์ username

',
        ],
        'base_nav_title'                        => 'ผู้ดูแลระบบ',
        // 登录
        'login_type_username_title'             => 'หมายเลขบัญชี รหัสผ่าน',
        'login_type_mobile_title'               => 'รหัสยืนยันโทรศัพท์มือถือ',
        'login_type_email_title'                => 'รหัสยืนยันกล่องจดหมาย',
        'login_close_tips'                      => 'ปิดล็อกอินชั่วคราว',
        // 忘记密码
        'form_forget_password_name'             => 'ลืมรหัสผ่าน?',
        'form_forget_password_tips'             => 'กรุณาติดต่อผู้ดูแลระบบเพื่อรีเซ็ตรหัสผ่าน',
        // 动态表格
        'form_table'                            => [
            'username'              => 'ผู้ดูแลระบบ',
            'status'                => 'สถานะ',
            'gender'                => 'เพศ',
            'mobile'                => 'โทรศัพท์มือถือ',
            'email'                 => 'กล่องจดหมาย',
            'role_name'             => 'กลุ่มบทบาท',
            'login_total'           => 'จำนวนการเข้าสู่ระบบ',
            'login_time'            => 'เวลาเข้าสู่ระบบล่าสุด',
            'add_time'              => 'สร้างเวลา',
            'upd_time'              => 'อัปเดตเวลา',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'ข้อตกลงการลงทะเบียนผู้ใช้', 'type' => 'register'],
            ['name' => 'นโยบายความเป็นส่วนตัวของผู้ใช้', 'type' => 'privacy'],
            ['name' => 'ข้อตกลงการออกจากบัญชี', 'type' => 'logout']
        ],
        'top_tips'          => 'ที่อยู่โปรโตคอลการเข้าถึงส่วนหน้าเพิ่มพารามิเตอร์ is_ เนื้อหา = 1 แสดงเฉพาะเนื้อหาโปรโตคอลบริสุทธิ์',
        'view_detail_name'                      => 'ดูรายละเอียด',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'การกำหนดค่าพื้นฐาน', 'type' => 'index'],
            ['name' => 'แอป / แอปเพล็ต', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'หัวข้อปัจจุบัน', 'type' => 'index'],
            ['name' => 'การติดตั้งธีม', 'type' => 'upload'],
            ['name' => 'ดาวน์โหลด Source Pack', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'หัวข้อเพิ่มเติม ดาวน์โหลด',
        'nav_theme_download_name'               => 'ดูการกวดวิชาแพ็คแอปเพล็ต',
        'nav_theme_download_tips'               => 'ชุดรูปแบบปลายโทรศัพท์ได้รับการพัฒนาโดย uniapp (รองรับแอปเพล็ต multi-end + H5) และ APP ก็อยู่ในการปรับตัวอย่างเร่งด่วน',
        'form_alipay_extend_title'              => 'การกำหนดค่าการบริการลูกค้า',
        'form_alipay_extend_tips'               => 'PS: เช่นใน [APP / แอปเพล็ต] เปิด (เปิดให้บริการลูกค้าออนไลน์) การกำหนดค่าต่อไปนี้จะต้อง [Enterprise Code] และ [Chat Window Code]',
        'form_theme_upload_tips'                => 'อัปโหลดแพคเกจการติดตั้งในรูปแบบการบีบอัดซิป',
        'list_no_data_tips'                     => 'ไม่มีแพ็คเกจธีมที่เกี่ยวข้อง',
        'list_author_title'                     => 'ผู้เขียน',
        'list_version_title'                    => 'รุ่นที่ใช้งานได้',
        'package_generate_tips'                 => 'เวลาในการสร้างค่อนข้างนานโปรดอย่าปิดหน้าต่างเบราว์เซอร์!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'ชื่อแพ็คเกจ',
            'size'  => 'ขนาด',
            'url'   => 'ดาวน์โหลดที่อยู่',
            'time'  => 'สร้างเวลา',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'การตั้งค่า SMS', 'type' => 'index'],
            ['name' => 'แม่แบบข้อความ', 'type' => 'message'],
        ],
        'top_tips'                              => 'Ali Cloud ที่อยู่การจัดการ SMS',
        'top_to_aliyun_tips'                    => 'คลิกไปที่ Ali Cloud เพื่อซื้อ SMS',
        'base_item_admin_title'                 => 'หลังเวที',
        'base_item_index_title'                 => 'หน้าด้านหน้า',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'การตั้งค่ากล่องจดหมาย', 'type' => 'index'],
            ['name' => 'แม่แบบข้อความ', 'type' => 'message'],
        ],
        'top_tips'                              => 'เนื่องจากมีความแตกต่างบางอย่างในแพลตฟอร์มกล่องจดหมายที่แตกต่างกันการกำหนดค่าก็แตกต่างกันไปตามบทเรียนการกำหนดค่าแพลตฟอร์มกล่องจดหมายโดยเฉพาะ',
        // 基础
        'test_title'                            => 'การทดสอบ',
        'test_content'                          => 'การกำหนดค่าจดหมาย - ส่งเนื้อหาการทดสอบ',
        'base_item_admin_title'                 => 'หลังเวที',
        'base_item_index_title'                 => 'หน้าด้านหน้า',
        // 表单
        'form_item_test'                        => 'ทดสอบที่อยู่อีเมลที่ได้รับ',
        'form_item_test_tips'                   => 'โปรดบันทึกการกำหนดค่าก่อนทำการทดสอบ',
        'form_item_test_button_title'           => 'การทดสอบ',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'กำหนดค่ากฎ pseudostatic ที่สอดคล้องกันตามสภาพแวดล้อมของเซิร์ฟเวอร์ [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'สินค้าโภคภัณฑ์',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'ข้อมูลพื้นฐาน', 'type'=>'base'],
            'specifications'  => ['name' => 'ข้อกำหนดสินค้า', 'type'=>'specifications'],
            'parameters'      => ['name' => 'พารามิเตอร์สินค้า', 'type'=>'parameters'],
            'photo'           => ['name' => 'อัลบั้มสินค้า', 'type'=>'photo'],
            'video'           => ['name' => 'สินค้าวิดีโอ', 'type'=>'video'],
            'app'             => ['name' => 'รายละเอียดโทรศัพท์มือถือ', 'type'=>'app'],
            'web'             => ['name' => 'รายละเอียดคอมพิวเตอร์', 'type'=>'web'],
            'fictitious'      => ['name' => 'ข้อมูลเสมือนจริง', 'type'=>'fictitious'],
            'extends'         => ['name' => 'ขยายข้อมูล', 'type'=>'extends'],
            'seo'             => ['name' => 'ข้อมูล SEO', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'หมายเลขสินค้า',
            'info'                    => 'ข้อมูลสินค้า',
            'category_text'           => 'การจำแนกประเภทสินค้า',
            'brand_name'              => 'แบรนด์',
            'price'                   => 'ราคาขาย (CNY)',
            'original_price'          => 'ราคาเดิม (CNY)',
            'inventory'               => 'สินค้าคงคลังทั้งหมด',
            'is_shelves'              => 'ชั้นวางบนและล่าง',
            'is_deduction_inventory'  => 'ลดสินค้าคงคลัง',
            'site_type'               => 'ประเภทสินค้า',
            'model'                   => 'รูปแบบสินค้า',
            'place_origin_name'       => 'สถานที่ผลิต',
            'give_integral'           => 'ซื้อสัดส่วนคะแนนแจก',
            'buy_min_number'          => 'ปริมาณการสั่งซื้อขั้นต่ำเพียงครั้งเดียว',
            'buy_max_number'          => 'จำนวนสูงสุดของการซื้อครั้งเดียว',
            'sales_count'             => 'ยอดขาย',
            'access_count'            => 'จำนวนการเข้าชม',
            'add_time'                => 'สร้างเวลา',
            'upd_time'                => 'อัปเดตเวลา',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'การจำแนกประเภทสินค้า',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'รีวิวสินค้า',
        // 动态表格
        'form_table'                            => [
            'user'               => 'ข้อมูลผู้ใช้',
            'user_placeholder'   => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'goods'              => 'ข้อมูลพื้นฐาน',
            'goods_placeholder'  => 'กรุณาใส่ชื่อสินค้า / รุ่น',
            'business_type'      => 'ประเภทธุรกิจ',
            'content'            => 'เนื้อหาแสดงความคิดเห็น',
            'images'             => 'รีวิวรูปภาพ',
            'rating'             => 'การให้คะแนน',
            'reply'              => 'ตอบกลับเนื้อหา',
            'is_show'            => 'ไม่ว่าจะเป็นการแสดง',
            'is_anonymous'       => 'ไม่ว่าจะเป็นการไม่ระบุชื่อ',
            'is_reply'           => 'ไม่ว่าจะเป็นการตอบกลับ',
            'reply_time_time'    => 'เวลาตอบกลับ',
            'add_time_time'      => 'สร้างเวลา',
            'upd_time_time'      => 'อัปเดตเวลา',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'พารามิเตอร์สินค้า',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'การจำแนกประเภทสินค้า',
            'name'          => 'ชื่อ',
            'is_enable'     => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'config_count'  => 'จำนวนพารามิเตอร์',
            'add_time'      => 'สร้างเวลา',
            'upd_time'      => 'อัปเดตเวลา',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'การจำแนกประเภทสินค้า',
            'name'         => 'ชื่อ',
            'is_enable'    => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'content'      => 'สเปค มูลค่า',
            'add_time'     => 'สร้างเวลา',
            'upd_time'     => 'อัปเดตเวลา',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'ข้อมูลผู้ใช้',
            'user_placeholder'   => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'goods'              => 'ข้อมูลสินค้า',
            'goods_placeholder'  => 'โปรดป้อนชื่อการค้า / คำอธิบายสั้น ๆ / ข้อมูล SEO',
            'price'              => 'ราคาขาย (CNY)',
            'original_price'     => 'ราคาเดิม (CNY)',
            'add_time'           => 'สร้างเวลา',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'ข้อมูลผู้ใช้',
            'user_placeholder'   => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'goods'              => 'ข้อมูลสินค้า',
            'goods_placeholder'  => 'โปรดป้อนชื่อการค้า / คำอธิบายสั้น ๆ / ข้อมูล SEO',
            'price'              => 'ราคาขาย (CNY)',
            'original_price'     => 'ราคาเดิม (CNY)',
            'add_time'           => 'สร้างเวลา',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'ข้อมูลผู้ใช้',
            'user_placeholder'   => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'goods'              => 'ข้อมูลสินค้า',
            'goods_placeholder'  => 'โปรดป้อนชื่อการค้า / คำอธิบายสั้น ๆ / ข้อมูล SEO',
            'price'              => 'ราคาขาย (CNY)',
            'original_price'     => 'ราคาเดิม (CNY)',
            'add_time'           => 'สร้างเวลา',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'ลิงค์มิตรภาพ',
        // 动态表格
        'form_table'                            => [
            'info'                => 'ชื่อ',
            'url'                 => 'ที่อยู่ URL',
            'describe'            => 'คำอธิบาย',
            'is_enable'           => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'is_new_window_open'  => 'เปิดหน้าต่างใหม่หรือไม่',
            'sort'                => 'เรียงลำดับ',
            'add_time'            => 'สร้างเวลา',
            'upd_time'            => 'อัปเดตเวลา',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'การนำทางระดับกลาง', 'type' => 'header'],
            ['name' => 'การนำทางด้านล่าง', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'การปรับแต่ง',
            'article'           => 'บทความ',
            'customview'        => 'ปรับแต่งหน้า',
            'goods_category'    => 'การจำแนกประเภทสินค้า',
            'design'            => 'การออกแบบหน้า',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'ชื่อนำทาง',
            'data_type'           => 'ประเภทข้อมูลการนำทาง',
            'is_show'             => 'ไม่ว่าจะเป็นการแสดง',
            'is_new_window_open'  => 'เปิดหน้าต่างใหม่',
            'sort'                => 'เรียงลำดับ',
            'add_time'            => 'สร้างเวลา',
            'upd_time'            => 'อัปเดตเวลา',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'รหัสการสั่งซื้อผิดพลาด',
            'express_choice_tips'               => 'กรุณาเลือกวิธีการจัดส่ง',
            'payment_choice_tips'               => 'กรุณาเลือกวิธีการชำระเงิน',
        ],
        // 页面基础
        'form_delivery_title'                   => 'การดำเนินการจัดส่งสินค้า',
        'form_payment_title'                    => 'การดำเนินการชำระเงิน',
        'form_item_take'                        => 'รับรหัส',
        'form_item_take_message'                => 'กรุณากรอกรหัสรับสินค้า 4 หลัก',
        'form_item_express_number'              => 'หมายเลขเอกสารด่วน',
        'form_item_express_number_message'      => 'กรุณากรอกหมายเลขพัสดุ',
        // 地址
        'detail_user_address_title'             => 'ที่อยู่รับสินค้า',
        'detail_user_address_name'              => 'ผู้รับ',
        'detail_user_address_tel'               => 'รับโทรศัพท์',
        'detail_user_address_value'             => 'รายละเอียดที่อยู่',
        'detail_user_address_idcard'            => 'ข้อมูลบัตรประชาชน',
        'detail_user_address_idcard_name'       => 'ชื่อ-นามสกุล',
        'detail_user_address_idcard_number'     => 'หมายเลข',
        'detail_user_address_idcard_pic'        => 'รูปภาพ',
        'detail_take_address_title'             => 'ที่อยู่รับสินค้า',
        'detail_take_address_contact'           => 'ข้อมูลการติดต่อ',
        'detail_take_address_value'             => 'รายละเอียดที่อยู่',
        'detail_fictitious_title'               => 'ข้อมูลคีย์',
        // 订单售后
        'detail_aftersale_status'               => 'สถานะ',
        'detail_aftersale_type'                 => 'ประเภท',
        'detail_aftersale_price'                => 'จำนวนเงิน',
        'detail_aftersale_number'               => 'ปริมาณ',
        'detail_aftersale_reason'               => 'เหตุผล',
        // 商品
        'detail_goods_title'                    => 'สั่งซื้อสินค้า',
        'detail_payment_amount_less_tips'       => 'โปรดทราบว่าคำสั่งซื้อนี้จ่ายเงินน้อยกว่าราคารวม',
        'detail_no_payment_tips'                => 'ทราบว่าคำสั่งดังกล่าวยังไม่ได้ชำระ',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'ข้อมูลพื้นฐาน',
            'goods_placeholder'   => 'กรุณากรอกรหัสการสั่งซื้อ / หมายเลขการสั่งซื้อ / ชื่อสินค้า / รุ่น',
            'user'                => 'ข้อมูลผู้ใช้',
            'user_placeholder'    => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'status'              => 'สถานะการสั่งซื้อ',
            'pay_status'          => 'สถานะการชำระเงิน',
            'total_price'         => 'ราคารวม (CNY)',
            'pay_price'           => 'จำนวนเงินที่ต้องชำระ (CNY)',
            'price'               => 'ราคาต่อหน่วย (CNY)',
            'warehouse_name'      => 'คลังสินค้าจัดส่ง',
            'order_model'         => 'โหมดการสั่งซื้อ',
            'client_type'         => 'แหล่งที่มา',
            'address'             => 'ข้อมูลที่อยู่',
            'take'                => 'ข้อมูลการรับสินค้า',
            'refund_price'        => 'จำนวนเงินคืน (CNY)',
            'returned_quantity'   => 'ปริมาณการคืนสินค้า',
            'buy_number_count'    => 'ยอดซื้อ',
            'increase_price'      => 'เพิ่มจำนวนเงิน (หยวน)',
            'preferential_price'  => 'จำนวนข้อเสนอ (CNY)',
            'payment_name'        => 'วิธีการชำระเงิน',
            'user_note'           => 'หมายเหตุผู้ใช้',
            'extension'           => 'ขยายข้อมูล',
            'express_name'        => 'บริษัท เอ็กซ์เพรส',
            'express_number'      => 'หมายเลขเอกสารด่วน',
            'aftersale'           => 'ล่าสุดหลังการขาย',
            'is_comments'         => 'ไม่ว่าผู้ใช้จะแสดงความคิดเห็น',
            'confirm_time'        => 'ยืนยันเวลา',
            'pay_time'            => 'เวลาชำระเงิน',
            'delivery_time'       => 'เวลาจัดส่ง',
            'collect_time'        => 'เวลาเสร็จสิ้น',
            'cancel_time'         => 'เวลายกเลิก',
            'close_time'          => 'เวลาปิด',
            'add_time'            => 'สร้างเวลา',
            'upd_time'            => 'อัปเดตเวลา',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'การดำเนินการตรวจสอบ',
        'form_refuse_title'                     => 'ปฏิเสธการดำเนินการ',
        'form_user_info_title'                  => 'ข้อมูลผู้ใช้',
        'form_apply_info_title'                 => 'ข้อมูลการสมัคร',
        'forn_apply_info_type'                  => 'ประเภท',
        'forn_apply_info_price'                 => 'จำนวนเงิน',
        'forn_apply_info_number'                => 'ปริมาณ',
        'forn_apply_info_reason'                => 'เหตุผล',
        'forn_apply_info_msg'                   => 'คำแนะนำ',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'ข้อมูลพื้นฐาน',
            'goods_placeholder'  => 'โปรดป้อนหมายเลขการสั่งซื้อ / ชื่อสินค้า / รุ่น',
            'user'               => 'ข้อมูลผู้ใช้',
            'user_placeholder'   => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'status'             => 'สถานะ',
            'type'               => 'ประเภทการสมัคร',
            'reason'             => 'เหตุผล',
            'price'              => 'จำนวนเงินคืน (CNY)',
            'number'             => 'ปริมาณการคืนสินค้า',
            'msg'                => 'คำแนะนำการคืนเงิน',
            'refundment'         => 'ประเภทการคืนเงิน',
            'voucher'            => 'คูปอง',
            'express_name'       => 'บริษัท เอ็กซ์เพรส',
            'express_number'     => 'หมายเลขเอกสารด่วน',
            'refuse_reason'      => 'เหตุผลในการปฏิเสธ',
            'apply_time'         => 'ระยะเวลาการสมัคร',
            'confirm_time'       => 'ยืนยันเวลา',
            'delivery_time'      => 'เวลาคืนสินค้า',
            'audit_time'         => 'เวลาตรวจสอบ',
            'add_time'           => 'สร้างเวลา',
            'upd_time'           => 'อัปเดตเวลา',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'วิธีการชำระเงิน',
        'nav_store_payment_name'                => 'วิธีการชำระเงินเพิ่มเติม ดาวน์โหลด',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1. ชื่อคลาสต้องสอดคล้องกับชื่อไฟล์ (ถอด .php) เช่น Alipay.php ใช้ Alipay',
            ],
            [
                'name'  => '2 วิธีการที่คลาสต้องกำหนด',
                'item'  => [
                    '2.1 วิธีการกำหนดค่า',
                    '2.2. วิธีการชำระเงิน',
                    '2.3. วิธีการโทรกลับ Respond',
                    '2.4 วิธีการโทรกลับแบบอะซิงโครนัสของ Notify (ไม่จำเป็นหากไม่ได้กำหนดจะเรียกใช้วิธีRespond)',
                    '2.5. วิธีการคืนเงิน Refund (ไม่จำเป็นหากไม่ได้กำหนดจะไม่สามารถเริ่มต้นการคืนเงินแบบเดิมได้)',
                ],
            ],
            [
                'name'  => '3. สามารถปรับแต่งวิธีการเนื้อหาเอาต์พุตได้',
                'item'  => [
                    '3.1. SuccessReturn ชำระเงินสำเร็จ (ไม่จำเป็น)',
                    '3.2. การชำระเงิน ErrorReturn ล้มเหลว (อุปกรณ์เสริม)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: เงื่อนไขข้างต้นไม่ตอบสนองแล้วคุณไม่สามารถดูปลั๊กอิน, ใส่ปลั๊กอินลงในแพคเกจการบีบอัด .zip เพื่ออัปโหลด, สนับสนุนการรวมปลั๊กอินการชำระเงินหลายในการบีบอัดเดียว',
        // 动态表格
        'form_table'                            => [
            'name'            => 'ชื่อ',
            'logo'            => 'LOGO',
            'version'         => 'รุ่น',
            'apply_version'   => 'รุ่นที่ใช้งานได้',
            'apply_terminal'  => 'เทอร์มินัลที่ใช้งานได้',
            'author'          => 'ผู้เขียน',
            'desc'            => 'คำอธิบาย',
            'enable'          => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'open_user'       => 'เปิดผู้ใช้',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'จัดส่งด่วน',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'หัวข้อปัจจุบัน', 'type' => 'index'],
            ['name' => 'การติดตั้งธีม', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'หัวข้อเพิ่มเติม ดาวน์โหลด',
        'list_author_title'                     => 'ผู้เขียน',
        'list_version_title'                    => 'รุ่นที่ใช้งานได้',
        'form_theme_upload_tips'                => 'อัปโหลดชุดติดตั้งชุดรูปแบบในรูปแบบการบีบอัดซิป',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'การนำทางศูนย์ผู้ใช้โทรศัพท์มือถือ',
        // 动态表格
        'form_table'                            => [
            'name'           => 'ชื่อ',
            'platform'       => 'แพลตฟอร์มที่เป็น',
            'images_url'     => 'ไอคอนนำทาง',
            'event_type'     => 'ประเภทของกิจกรรม',
            'event_value'    => 'ค่าเหตุการณ์',
            'desc'           => 'คำอธิบาย',
            'is_enable'      => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'is_need_login'  => 'ต้องการเข้าสู่ระบบหรือไม่',
            'sort'           => 'เรียงลำดับ',
            'add_time'       => 'สร้างเวลา',
            'upd_time'       => 'อัปเดตเวลา',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'โทรศัพท์บ้าน Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'ชื่อ',
            'platform'       => 'แพลตฟอร์มที่เป็น',
            'images'         => 'ไอคอนนำทาง',
            'event_type'     => 'ประเภทของกิจกรรม',
            'event_value'    => 'ค่าเหตุการณ์',
            'is_enable'      => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'is_need_login'  => 'ต้องการเข้าสู่ระบบหรือไม่',
            'sort'           => 'เรียงลำดับ',
            'add_time'       => 'สร้างเวลา',
            'upd_time'       => 'อัปเดตเวลา',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'บันทึกคำขอชำระเงิน',
        // 动态表格
        'form_table'                            => [
            'user'              => 'ข้อมูลผู้ใช้',
            'user_placeholder'  => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'log_no'            => 'หมายเลขสลิปการชำระเงิน',
            'payment'           => 'วิธีการชำระเงิน',
            'status'            => 'สถานะ',
            'total_price'       => 'จำนวนการสั่งซื้อธุรกิจ (หยวน)',
            'pay_price'         => 'จำนวนเงินที่ต้องชำระ (CNY)',
            'business_type'     => 'ประเภทธุรกิจ',
            'business_list'     => 'รหัสธุรกิจ / หมายเลขเดี่ยว',
            'trade_no'          => 'หมายเลขธุรกรรมของแพลตฟอร์มการชำระเงิน',
            'buyer_user'        => 'หมายเลขบัญชีผู้ใช้แพลตฟอร์มการชำระเงิน',
            'subject'           => 'ชื่อการสั่งซื้อ',
            'pay_time'          => 'เวลาชำระเงิน',
            'close_time'        => 'เวลาปิด',
            'add_time'          => 'สร้างเวลา',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'บันทึกคำขอชำระเงิน',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'ประเภทธุรกิจ',
            'request_params'   => 'พารามิเตอร์การร้องขอ',
            'response_data'    => 'ข้อมูลการตอบสนอง',
            'business_handle'  => 'ผลการประมวลผลทางธุรกิจ',
            'request_url'      => 'ขอที่อยู่ URL',
            'server_port'      => 'หมายเลขพอร์ต',
            'server_ip'        => 'เซิร์ฟเวอร์ IP',
            'client_ip'        => 'IP ของลูกค้า',
            'os'               => 'ระบบปฏิบัติการ',
            'browser'          => 'เบราว์เซอร์',
            'method'           => 'ประเภทคำขอ',
            'scheme'           => 'ประเภท http',
            'version'          => 'รุ่น http',
            'client'           => 'รายละเอียดลูกค้า',
            'add_time'         => 'สร้างเวลา',
            'upd_time'         => 'อัปเดตเวลา',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'ข้อมูลผู้ใช้',
            'user_placeholder'  => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'payment'           => 'วิธีการชำระเงิน',
            'business_type'     => 'ประเภทธุรกิจ',
            'business_id'       => 'รหัสการสั่งซื้อธุรกิจ',
            'trade_no'          => 'หมายเลขธุรกรรมของแพลตฟอร์มการชำระเงิน',
            'buyer_user'        => 'หมายเลขบัญชีผู้ใช้แพลตฟอร์มการชำระเงิน',
            'refundment_text'   => 'วิธีการคืนเงิน',
            'refund_price'      => 'จำนวนเงินคืน',
            'pay_price'         => 'จำนวนการชำระเงินสำหรับการสั่งซื้อ',
            'msg'               => 'คำอธิบาย',
            'add_time_time'     => 'เวลาคืนเงิน',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'กลับไปที่ Application Management >>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'กรุณาแตะติ๊กก่อนเพื่อเปิดใช้งาน',
            'save_no_data_tips'                 => 'ไม่มีข้อมูลปลั๊กอินที่สามารถบันทึกได้',
        ],
        // 基础导航
        'base_nav_title'                        => 'ใบสมัคร',
        'base_nav_list'                         => [
            ['name' => 'การจัดการแอปพลิเคชัน', 'type' => 'index'],
            ['name' => 'อัพโหลดแอป', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'ดาวน์โหลดปลั๊กอินเพิ่มเติม',
        // 基础页面
        'base_search_input_placeholder'         => 'กรุณาใส่ชื่อ/คำอธิบาย',
        'base_top_tips_one'                     => 'รายการจัดเรียงตาม [จัดเรียงที่กำหนดเอง -> ติดตั้งได้เร็วที่สุด]',
        'base_top_tips_two'                     => 'คลิกปุ่มไอคอนลากเพื่อปรับการโทรปลั๊กอินและลำดับการแสดงผล',
        'base_open_sort_title'                  => 'เปิดการเรียงลำดับ',
        'data_list_author_title'                => 'ผู้เขียน',
        'data_list_author_url_title'            => 'หน้าหลัก',
        'data_list_version_title'               => 'รุ่น',
        'uninstall_confirm_tips'                => 'ถอนการติดตั้งอาจสูญเสียข้อมูลการกำหนดค่าพื้นฐานของปลั๊กอินไม่สามารถกู้คืนยืนยันการดำเนินการ?',
        'not_install_divide_title'              => 'ปลั๊กอินต่อไปนี้ไม่ได้ติดตั้ง',
        'delete_plugins_text'                   => '1. ลบเฉพาะแอปพลิเคชัน',
        'delete_plugins_text_tips'              => '(ลบเฉพาะรหัสแอปพลิเคชันและเก็บข้อมูลแอปพลิเคชัน)',
        'delete_plugins_data_text'              => '2. ลบแอปและลบข้อมูล',
        'delete_plugins_data_text_tips'         => '(รหัสแอปพลิเคชันและข้อมูลแอปพลิเคชันจะถูกลบ)',
        'delete_plugins_ps_tips'                => 'PS: ไม่สามารถกู้คืนได้หลังจากการดำเนินการต่อไปนี้โปรดดำเนินการด้วยความระมัดระวัง!',
        'delete_plugins_button_name'            => 'ลบเฉพาะแอปพลิเคชัน',
        'delete_plugins_data_button_name'       => 'ลบแอปและข้อมูล',
        'cancel_delete_plugins_button_name'     => 'พิจารณาอีกครั้ง',
        'more_plugins_store_to_text'            => 'ไปที่ App Store เพื่อเลือกปลั๊กอินเพิ่มเติม Rich Sites >>',
        'no_data_store_to_text'                 => 'ไปที่ App Store เพื่อเลือกปลั๊กอินที่อุดมไปด้วยไซต์ >>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'กลับไปที่พื้นหลัง',
        'get_loading_tips'                      => 'กำลังหา...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'บทบาท',
        'admin_not_modify_tips'                 => 'ผู้ดูแลระบบซุปเปอร์มีสิทธิ์ทั้งหมดโดยค่าเริ่มต้นและไม่สามารถเปลี่ยนแปลงได้',
        // 动态表格
        'form_table'                            => [
            'name'       => 'ชื่อตัวละคร',
            'is_enable'  => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'add_time'   => 'สร้างเวลา',
            'upd_time'   => 'อัปเดตเวลา',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'การอนุญาต',
        'top_tips_list'                         => [
            '1. ช่างเทคนิคที่ไม่ใช่มืออาชีพอย่าใช้ข้อมูลของหน้านี้การดำเนินงานที่ผิดพลาดอาจทำให้เกิดความผิดพลาดในเมนูการอนุญาต',
            '2. เมนูการอนุญาตแบ่งออกเป็น [การใช้การดำเนินการ] สองประเภทการใช้เมนูโดยทั่วไปเพื่อเปิดการแสดงผลเมนูการทำงานจะต้องซ่อน',
            '3. การกู้คืนข้อมูลของแผ่นข้อมูล ['.MyConfig('database.connections.mysql.prefix').'power] สามารถเขียนทับได้หากเกิดความผิดพลาดในเมนูการอนุญาต',
            '4. [Superadmin, บัญชี admin] มีสิทธิ์ทั้งหมดโดยค่าเริ่มต้นและไม่สามารถเปลี่ยนแปลงได้',
        ],
        'content_top_tips_list'                 => [
            '1. กรอก [ชื่อคอนโทรลเลอร์และชื่อวิธีการ] ต้องใช้คำจำกัดความของตัวควบคุมและวิธีการสร้างที่สอดคล้องกัน',
            '2. ตำแหน่งไฟล์ควบคุม [app / admin / controller] การดำเนินการนี้ใช้โดยนักพัฒนาเท่านั้น',
            '3. ชื่อควบคุม / ชื่อวิธีการที่มีที่อยู่ URL ที่กำหนดเองทั้งสองต้องกรอกข้อมูล',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'นำทางด่วน',
        // 动态表格
        'form_table'                            => [
            'name'         => 'ชื่อ',
            'platform'     => 'แพลตฟอร์มที่เป็น',
            'images'       => 'ไอคอนนำทาง',
            'event_type'   => 'ประเภทของกิจกรรม',
            'event_value'  => 'ค่าเหตุการณ์',
            'is_enable'    => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'sort'         => 'เรียงลำดับ',
            'add_time'     => 'สร้างเวลา',
            'upd_time'     => 'อัปเดตเวลา',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'ภูมิภาค',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'ราคาคัดกรอง',
        'top_tips_list'                         => [
            'ราคาต่ำสุด 0 - สูงสุด 100 ใช่ น้อยกว่า 100',
            'ราคาต่ำสุด 1000 - ราคาสูงสุด 0 คือ มากกว่า 1000',
            'ราคาต่ำสุด 100 - ราคาสูงสุด 500 ใช่ มากกว่า เท่ากับ 100 และน้อยกว่า 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'หมุนเล่น',
        // 动态表格
        'form_table'                            => [
            'name'         => 'ชื่อ',
            'platform'     => 'แพลตฟอร์มที่เป็น',
            'images'       => 'รูปภาพ',
            'event_type'   => 'ประเภทของกิจกรรม',
            'event_value'  => 'ค่าเหตุการณ์',
            'is_enable'    => 'ไม่ว่าจะเป็นการเปิดใช้งาน',
            'sort'         => 'เรียงลำดับ',
            'add_time'     => 'สร้างเวลา',
            'upd_time'     => 'อัปเดตเวลา',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'ข้อมูลผู้ใช้',
            'user_placeholder'    => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'type'                => 'ประเภทการดำเนินงาน',
            'operation_integral'  => 'คะแนนการดำเนินงาน',
            'original_integral'   => 'คะแนนดิบ',
            'new_integral'        => 'คะแนนล่าสุด',
            'msg'                 => 'เหตุผลในการดำเนินงาน',
            'operation_id'        => 'รหัสผู้ปฏิบัติงาน',
            'add_time_time'       => 'เวลาทำงาน',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'ข้อมูลผู้ใช้',
            'user_placeholder'          => 'โปรดป้อนชื่อผู้ใช้ / ชื่อเล่น / โทรศัพท์มือถือ / กล่องจดหมาย',
            'type'                      => 'ประเภทข้อความ',
            'business_type'             => 'ประเภทธุรกิจ',
            'title'                     => 'ชื่อเรื่อง',
            'detail'                    => 'รายละเอียด',
            'is_read'                   => 'ไม่ว่าจะเป็นการอ่าน',
            'user_is_delete_time_text'  => 'ไม่ว่าผู้ใช้จะลบหรือไม่',
            'add_time_time'             => 'เวลาส่ง',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: ไม่ใช่นักพัฒนาโปรดอย่าดำเนินการคำสั่ง SQL ใด ๆ ตามอำเภอใจการดำเนินการอาจนำไปสู่การลบฐานข้อมูลระบบทั้งหมด',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'รายการแอพ ShopXO ที่ยอดเยี่ยมที่นี่รวบรวมนักพัฒนา ShopXO ที่มีประสบการณ์มากที่สุดความสามารถทางเทคนิคที่แข็งแกร่งและเชื่อถือได้มากที่สุดเพื่อปรับแต่ง Escorts เต็มรูปแบบสำหรับปลั๊กอินสไตล์แม่แบบของคุณ',
        'to_store_name'                         => 'ไปที่ App Store เพื่อเลือกปลั๊กอิน >>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'ระบบการจัดการพื้นหลัง',
        'remove_cache_title'                    => 'ล้างแคช',
        'user_status_title'                     => 'สถานะผู้ใช้',
        'user_status_message'                   => 'กรุณาเลือกสถานะผู้ใช้',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'ข้อมูลการกำหนดค่าพารามิเตอร์สินค้า',
        'form_goods_params_copy_no_tips'        => 'กรุณาวางข้อมูลการกำหนดค่าก่อน',
        'form_goods_params_copy_error_tips'     => 'ปรับแต่งรูปแบบผิดพลาด',
        'form_goods_params_type_message'        => 'โปรดเลือกพารามิเตอร์สินค้า ประเภทการแสดง',
        'form_goods_params_params_name'         => 'ชื่อพารามิเตอร์',
        'form_goods_params_params_message'      => 'กรุณากรอกชื่อพารามิเตอร์',
        'form_goods_params_value_name'          => 'ค่าพารามิเตอร์',
        'form_goods_params_value_message'       => 'กรุณากรอกค่าพารามิเตอร์',
        'form_goods_params_move_type_tips'      => 'การกำหนดค่าประเภทการทำงานผิดพลาด',
        'form_goods_params_move_top_tips'       => 'ขึ้นไปด้านบนสุดแล้ว',
        'form_goods_params_move_bottom_tips'    => 'มาถึงด้านล่างสุดแล้ว',
        'form_goods_params_thead_type_title'    => 'ขอบเขตการแสดงผล',
        'form_goods_params_thead_name_title'    => 'ชื่อพารามิเตอร์',
        'form_goods_params_thead_value_title'   => 'ค่าพารามิเตอร์',
        'form_goods_params_row_add_title'       => 'เพิ่มบรรทัด',
        'form_goods_params_list_tips'           => [
            '1. ทั้งหมด (แสดงภายใต้ข้อมูลพื้นฐานของสินค้าและพารามิเตอร์รายละเอียด)',
            '2. รายละเอียด (แสดงภายใต้พารามิเตอร์รายละเอียดของสินค้าเท่านั้น)',
            '3. ฐาน (แสดงภายใต้ข้อมูลพื้นฐานของสินค้าเท่านั้น)',
            '4. การดำเนินการที่รวดเร็วจะล้างข้อมูลเดิมหน้าภาระหนักสามารถกู้คืนข้อมูลเดิมได้ (มีผลหลังจากบันทึกสินค้าเท่านั้น)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'การตั้งค่าระบบ',
            'item'  => [
                'config_index'                 => 'การกำหนดค่าระบบ',
                'config_store'                 => 'ข้อมูลร้านค้า',
                'config_save'                  => 'กำหนดค่าการบันทึก',
                'index_storeaccountsbind'      => 'การผูกหมายเลขบัญชี App Store',
                'index_inspectupgrade'         => 'การตรวจสอบการปรับปรุงระบบ',
                'index_inspectupgradeconfirm'  => 'ยืนยันการปรับปรุงระบบ',
                'index_stats'                  => 'หน้าแรก สถิติ',
                'index_income'                 => 'หน้าแรก สถิติ (สถิติรายได้)',
            ]
        ],
        'site_index' => [
            'name'  => 'การกำหนดค่าเว็บไซต์',
            'item'  => [
                'site_index'                  => 'การตั้งค่าเว็บไซต์',
                'site_save'                   => 'แก้ไขการตั้งค่าเว็บไซต์',
                'site_goodssearch'            => 'การตั้งค่าเว็บไซต์ ค้นหาสินค้า',
                'layout_layoutindexhomesave'  => 'การจัดการเค้าโครงบ้าน',
                'sms_index'                   => 'การตั้งค่า SMS',
                'sms_save'                    => 'แก้ไขการตั้งค่า SMS',
                'email_index'                 => 'การตั้งค่ากล่องจดหมาย',
                'email_save'                  => 'การตั้งค่า/การแก้ไขกล่องจดหมาย',
                'email_emailtest'             => 'การทดสอบการส่งจดหมาย',
                'seo_index'                   => 'การตั้งค่า SEO',
                'seo_save'                    => 'แก้ไขการตั้งค่า SEO',
                'agreement_index'             => 'การจัดการโปรโตคอล',
                'agreement_save'              => 'แก้ไขการตั้งค่าโปรโตคอล',
            ]
        ],
        'power_index' => [
            'name'  => 'การควบคุมสิทธิ์',
            'item'  => [
                'admin_index'        => 'รายชื่อผู้ดูแลระบบ',
                'admin_saveinfo'     => 'ผู้ดูแลระบบ เพิ่ม / แก้ไขหน้า',
                'admin_save'         => 'ผู้ดูแลระบบ เพิ่ม / แก้ไข',
                'admin_delete'       => 'ลบโดยผู้ดูแลระบบ',
                'admin_detail'       => 'รายละเอียดผู้ดูแลระบบ',
                'role_index'         => 'การจัดการบทบาท',
                'role_saveinfo'      => 'เพิ่มกลุ่มบทบาท / แก้ไขหน้า',
                'role_save'          => 'เพิ่มกลุ่มบทบาท / แก้ไข',
                'role_delete'        => 'การกำจัดบทบาท',
                'role_statusupdate'  => 'อัปเดตสถานะบทบาท',
                'role_detail'        => 'รายละเอียดบทบาท',
                'power_index'        => 'การจัดสรรสิทธิ์',
                'power_save'         => 'เพิ่ม/แก้ไขสิทธิ์',
                'power_delete'       => 'การลบสิทธิ์',
            ]
        ],
        'user_index' => [
            'name'  => 'การจัดการผู้ใช้',
            'item'  => [
                'user_index'            => 'รายชื่อผู้ใช้',
                'user_saveinfo'         => 'ผู้ใช้แก้ไข / เพิ่มหน้า',
                'user_save'             => 'ผู้ใช้เพิ่ม / แก้ไข',
                'user_delete'           => 'การลบของผู้ใช้',
                'user_detail'           => 'รายละเอียดผู้ใช้',
                'useraddress_index'     => 'ที่อยู่ผู้ใช้',
                'useraddress_saveinfo'  => 'ที่อยู่ผู้ใช้ แก้ไขหน้า',
                'useraddress_save'      => 'แก้ไขที่อยู่ผู้ใช้',
                'useraddress_delete'    => 'การลบที่อยู่ของผู้ใช้',
                'useraddress_detail'    => 'รายละเอียดที่อยู่ผู้ใช้',
            ]
        ],
        'goods_index' => [
            'name'  => 'การจัดการสินค้า',
            'item'  => [
                'goods_index'                       => 'การจัดการสินค้า',
                'goods_saveinfo'                    => 'การเพิ่มสินค้า / แก้ไขหน้า',
                'goods_save'                        => 'เพิ่ม/แก้ไขสินค้า',
                'goods_delete'                      => 'การลบสินค้า',
                'goods_statusupdate'                => 'อัพเดทสถานะสินค้า',
                'goods_basetemplate'                => 'รับเทมเพลตพื้นฐานสินค้าโภคภัณฑ์',
                'goods_detail'                      => 'รายละเอียดสินค้า',
                'goodscategory_index'               => 'การจำแนกประเภทสินค้า',
                'goodscategory_save'                => 'การจำแนกประเภทสินค้า เพิ่ม / แก้ไข',
                'goodscategory_delete'              => 'การจำแนกประเภทสินค้า การลบ',
                'goodsparamstemplate_index'         => 'พารามิเตอร์สินค้า',
                'goodsparamstemplate_delete'        => 'การกำจัดพารามิเตอร์สินค้า',
                'goodsparamstemplate_statusupdate'  => 'อัพเดทสถานะพารามิเตอร์สินค้า',
                'goodsparamstemplate_saveinfo'      => 'พารามิเตอร์สินค้า เพิ่ม / แก้ไขหน้า',
                'goodsparamstemplate_save'          => 'พารามิเตอร์สินค้า เพิ่ม / แก้ไข',
                'goodsparamstemplate_detail'        => 'รายละเอียดพารามิเตอร์สินค้า',
                'goodsspectemplate_index'           => 'ข้อกำหนดสินค้า',
                'goodsspectemplate_delete'          => 'การลบข้อมูลจำเพาะของสินค้า',
                'goodsspectemplate_statusupdate'    => 'อัพเดทสถานะสินค้าโภคภัณฑ์',
                'goodsspectemplate_saveinfo'        => 'ข้อกำหนดสินค้า เพิ่ม / แก้ไขหน้า',
                'goodsspectemplate_save'            => 'ข้อกำหนดสินค้า เพิ่ม / แก้ไข',
                'goodsspectemplate_detail'          => 'รายละเอียดสินค้า',
                'goodscomments_detail'              => 'รายละเอียดรีวิวสินค้า',
                'goodscomments_index'               => 'รีวิวสินค้า',
                'goodscomments_reply'               => 'ความคิดเห็นสินค้า ตอบกลับ',
                'goodscomments_delete'              => 'ลบความคิดเห็นเกี่ยวกับสินค้า',
                'goodscomments_statusupdate'        => 'อัพเดทสถานะสินค้าโภคภัณฑ์',
                'goodscomments_saveinfo'            => 'รีวิวสินค้า เพิ่ม / แก้ไขหน้า',
                'goodscomments_save'                => 'รีวิวสินค้า เพิ่ม / แก้ไข',
                'goodsbrowse_index'                 => 'สินค้าโภคภัณฑ์',
                'goodsbrowse_delete'                => 'สินค้าโภคภัณฑ์เรียกดู ลบ',
                'goodsbrowse_detail'                => 'รายละเอียดสินค้าโภคภัณฑ์',
                'goodsfavor_index'                  => 'คอลเลกชันสินค้า',
                'goodsfavor_delete'                 => 'การกำจัดคอลเลกชันสินค้า',
                'goodsfavor_detail'                 => 'รายละเอียดของคอลเลกชันสินค้า',
                'goodscart_index'                   => 'รถเข็นสินค้า',
                'goodscart_delete'                  => 'การลบรถเข็นสินค้า',
                'goodscart_detail'                  => 'รายละเอียดรถเข็นสินค้า',
            ]
        ],
        'order_index' => [
            'name'  => 'การจัดการคำสั่งซื้อ',
            'item'  => [
                'order_index'             => 'การจัดการคำสั่งซื้อ',
                'order_delete'            => 'การลบคำสั่ง',
                'order_cancel'            => 'การยกเลิกคำสั่งซื้อ',
                'order_delivery'          => 'การจัดส่งสินค้า',
                'order_collect'           => 'การสั่งซื้อสินค้า',
                'order_pay'               => 'การชำระเงินสำหรับการสั่งซื้อ',
                'order_confirm'           => 'ยืนยันการสั่งซื้อ',
                'order_detail'            => 'รายละเอียดการสั่งซื้อ',
                'orderaftersale_index'    => 'สั่งซื้อหลังการขาย',
                'orderaftersale_delete'   => 'ลบคำสั่งซื้อหลังการขาย',
                'orderaftersale_cancel'   => 'การยกเลิกคำสั่งซื้อหลังการขาย',
                'orderaftersale_audit'    => 'การตรวจสอบหลังการสั่งซื้อ',
                'orderaftersale_confirm'  => 'ยืนยันการสั่งซื้อหลังการขาย',
                'orderaftersale_refuse'   => 'การปฏิเสธคำสั่งซื้อหลังการขาย',
                'orderaftersale_detail'   => 'รายละเอียดการสั่งซื้อหลังการขาย',
            ]
        ],
        'navigation_index' => [
            'name'  => 'การจัดการเว็บไซต์',
            'item'  => [
                'navigation_index'         => 'การจัดการระบบนำทาง',
                'navigation_save'          => 'นำทางเพิ่ม / แก้ไข',
                'navigation_delete'        => 'การนำทาง ลบ',
                'navigation_statusupdate'  => 'อัปเดตสถานะการนำทาง',
                'customview_index'         => 'ปรับแต่งหน้า',
                'customview_saveinfo'      => 'ปรับแต่งหน้า เพิ่ม / แก้ไขหน้า',
                'customview_save'          => 'การเพิ่ม / แก้ไขหน้าเว็บที่กำหนดเอง',
                'customview_delete'        => 'การลบหน้าที่กำหนดเอง',
                'customview_statusupdate'  => 'การปรับปรุงสถานะหน้าเว็บที่กำหนดเอง',
                'customview_detail'        => 'ปรับแต่งรายละเอียดหน้า',
                'link_index'               => 'ลิงค์มิตรภาพ',
                'link_saveinfo'            => 'ลิงค์เพื่อนเพิ่ม / แก้ไขหน้า',
                'link_save'                => 'ลิงค์มิตรภาพ เพิ่ม / แก้ไข',
                'link_delete'              => 'การลบลิงก์เพื่อน',
                'link_statusupdate'        => 'อัพเดทสถานะลิงก์มิตรภาพ',
                'link_detail'              => 'รายละเอียดลิงค์มิตรภาพ',
                'theme_index'              => 'การจัดการหัวข้อ',
                'theme_save'               => 'การจัดการธีม เพิ่ม / แก้ไข',
                'theme_upload'             => 'การติดตั้งการอัปโหลดธีม',
                'theme_delete'             => 'หัวข้อลบ',
                'theme_download'           => 'หัวข้อดาวน์โหลด',
                'slide_index'              => 'บ้าน การหมุน',
                'slide_saveinfo'           => 'การเพิ่ม / แก้ไขหน้า',
                'slide_save'               => 'เพิ่ม / แก้ไขการหมุนเวียน',
                'slide_statusupdate'       => 'อัปเดตสถานะการหมุนเวียน',
                'slide_delete'             => 'การลบหมุนเวียน',
                'slide_detail'             => 'รายละเอียดการหมุนเวียน',
                'screeningprice_index'     => 'ราคาคัดกรอง',
                'screeningprice_save'      => 'กรองราคา เพิ่ม / แก้ไข',
                'screeningprice_delete'    => 'กรองราคา ลบ',
                'region_index'             => 'การจัดการพื้นที่',
                'region_save'              => 'ภูมิภาค เพิ่ม / แก้ไข',
                'region_delete'            => 'การกำจัดภูมิภาค',
                'region_codedata'          => 'รับข้อมูลหมายเลขเขต',
                'express_index'            => 'การจัดการด่วน',
                'express_save'             => 'Express เพิ่ม / แก้ไข',
                'express_delete'           => 'ลบด่วน',
                'payment_index'            => 'วิธีการชำระเงิน',
                'payment_saveinfo'         => 'วิธีการชำระเงิน ติดตั้ง / แก้ไขหน้า',
                'payment_save'             => 'วิธีการชำระเงิน การติดตั้ง / แก้ไข',
                'payment_delete'           => 'วิธีการชำระเงิน ลบ',
                'payment_install'          => 'วิธีการชำระเงิน การติดตั้ง',
                'payment_statusupdate'     => 'อัปเดตสถานะวิธีการชำระเงิน',
                'payment_uninstall'        => 'วิธีการชำระเงิน ถอนการติดตั้ง',
                'payment_upload'           => 'วิธีการชำระเงิน อัปโหลด',
                'quicknav_index'           => 'นำทางด่วน',
                'quicknav_saveinfo'        => 'เพิ่ม/แก้ไขหน้า',
                'quicknav_save'            => 'เพิ่ม/แก้ไข',
                'quicknav_statusupdate'    => 'อัพเดตสถานะการนำทางลัด',
                'quicknav_delete'          => 'ลบการนำทางลัด',
                'quicknav_detail'          => 'รายละเอียดการนำทางด่วน',
                'design_index'             => 'การออกแบบหน้า',
                'design_saveinfo'          => 'ออกแบบหน้าเพิ่ม / แก้ไขหน้า',
                'design_save'              => 'ออกแบบหน้าเพิ่ม / แก้ไข',
                'design_statusupdate'      => 'อัปเดตสถานะการออกแบบหน้า',
                'design_upload'            => 'การนำเข้าออกแบบหน้า',
                'design_download'          => 'ออกแบบหน้า ดาวน์โหลด',
                'design_sync'              => 'การออกแบบหน้าซิงค์หน้าแรก',
                'design_delete'            => 'ลบการออกแบบหน้า',
            ]
        ],
        'brand_index' => [
            'name'  => 'การจัดการแบรนด์',
            'item'  => [
                'brand_index'           => 'การจัดการแบรนด์',
                'brand_saveinfo'        => 'เพิ่มแบรนด์ / แก้ไขหน้า',
                'brand_save'            => 'เพิ่ม/แก้ไขแบรนด์',
                'brand_statusupdate'    => 'อัพเดทสถานะแบรนด์',
                'brand_delete'          => 'ลบแบรนด์',
                'brand_detail'          => 'รายละเอียดของแบรนด์',
                'brandcategory_index'   => 'การจำแนกประเภทแบรนด์',
                'brandcategory_save'    => 'การจำแนกประเภทแบรนด์ เพิ่ม / แก้ไข',
                'brandcategory_delete'  => 'การจำแนกประเภทแบรนด์ ลบ',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'การจัดการคลังสินค้า',
            'item'  => [
                'warehouse_index'               => 'การจัดการคลังสินค้า',
                'warehouse_saveinfo'            => 'เพิ่มคลังสินค้า / แก้ไขหน้า',
                'warehouse_save'                => 'เพิ่มคลังสินค้า / แก้ไข',
                'warehouse_delete'              => 'การกำจัดคลังสินค้า',
                'warehouse_statusupdate'        => 'อัพเดทสถานะคลังสินค้า',
                'warehouse_detail'              => 'รายละเอียดคลังสินค้า',
                'warehousegoods_index'          => 'การจัดการสินค้าคลังสินค้า',
                'warehousegoods_detail'         => 'รายละเอียดสินค้าคลังสินค้า',
                'warehousegoods_delete'         => 'การกำจัดสินค้าในคลังสินค้า',
                'warehousegoods_statusupdate'   => 'อัพเดทสถานะสินค้าคลังสินค้า',
                'warehousegoods_goodssearch'    => 'ค้นหาคลังสินค้าสินค้า',
                'warehousegoods_goodsadd'       => 'คลังสินค้า สินค้า ค้นหา เพิ่ม',
                'warehousegoods_goodsdel'       => 'คลังสินค้า ค้นหาสินค้า ลบ',
                'warehousegoods_inventoryinfo'  => 'แก้ไขหน้าคลังสินค้าสินค้าคงคลัง',
                'warehousegoods_inventorysave'  => 'แก้ไขสต็อกสินค้าคลังสินค้า',
            ]
        ],
        'app_index' => [
            'name'  => 'การจัดการโทรศัพท์มือถือ',
            'item'  => [
                'appconfig_index'            => 'การกำหนดค่าพื้นฐาน',
                'appconfig_save'             => 'บันทึกการกำหนดค่าพื้นฐาน',
                'apphomenav_index'           => 'หน้าแรก นำทาง',
                'apphomenav_saveinfo'        => 'หน้าแรก การนำทาง เพิ่ม / แก้ไขหน้า',
                'apphomenav_save'            => 'หน้าแรก การนำทาง เพิ่ม / แก้ไข',
                'apphomenav_statusupdate'    => 'หน้าแรก อัพเดทสถานะการนำทาง',
                'apphomenav_delete'          => 'บ้าน การนำทาง ลบ',
                'apphomenav_detail'          => 'หน้าแรก Navigation Details',
                'appcenternav_index'         => 'การนำทางศูนย์ผู้ใช้',
                'appcenternav_saveinfo'      => 'ศูนย์ผู้ใช้นำทางเพิ่ม / แก้ไขหน้า',
                'appcenternav_save'          => 'การนำทางศูนย์ผู้ใช้ เพิ่ม / แก้ไข',
                'appcenternav_statusupdate'  => 'อัปเดตสถานะการนำทางศูนย์ผู้ใช้',
                'appcenternav_delete'        => 'ศูนย์ผู้ใช้ การนำทาง ลบ',
                'appcenternav_detail'        => 'รายละเอียดการนำทางศูนย์ผู้ใช้',
                'appmini_index'              => 'รายการแอพเพล็ต',
                'appmini_created'            => 'สร้างแพคเกจแอพเพล็ต',
                'appmini_delete'             => 'การลบแพ็คเกจแอพเพล็ต',
                'appmini_themeupload'        => 'การอัปโหลดธีมแอพเพล็ต',
                'appmini_themesave'          => 'วิธีการตกแต่งแอพเพล็ต toggle',
                'appmini_themedelete'        => 'วิธีการตกแต่งแอพเพล็ต toggle',
                'appmini_themedownload'      => 'ดาวน์โหลดแอพเพล็ตธีม',
                'appmini_config'             => 'การกำหนดค่าแอพเพล็ต',
                'appmini_save'               => 'บันทึกการกำหนดค่าแอพเพล็ต',
            ]
        ],
        'article_index' => [
            'name'  => 'การจัดการบทความ',
            'item'  => [
                'article_index'           => 'การจัดการบทความ',
                'article_saveinfo'        => 'เพิ่มบทความ / แก้ไขหน้า',
                'article_save'            => 'เพิ่มบทความ / แก้ไข',
                'article_delete'          => 'ลบบทความ',
                'article_statusupdate'    => 'อัพเดทสถานะบทความ',
                'article_detail'          => 'รายละเอียดบทความ',
                'articlecategory_index'   => 'หมวดหมู่บทความ',
                'articlecategory_save'    => 'หมวดหมู่บทความ แก้ไข/เพิ่ม',
                'articlecategory_delete'  => 'บทความ หมวดหมู่ ลบ',
            ]
        ],
        'data_index' => [
            'name'  => 'การจัดการข้อมูล',
            'item'  => [
                'answer_index'          => 'คำถามและคำตอบ',
                'answer_reply'          => 'คำถามและคำตอบ ทิ้งข้อความตอบกลับ',
                'answer_delete'         => 'คำถามและคำตอบ ลบข้อความ',
                'answer_statusupdate'   => 'ถาม-ตอบ อัพเดทสถานะข้อความ',
                'answer_saveinfo'       => 'Q&A เพิ่ม / แก้ไขหน้า',
                'answer_save'           => 'คำถามและคำตอบ เพิ่ม / แก้ไข',
                'answer_detail'         => 'คำถามและคำตอบ รายละเอียดข้อความ',
                'message_index'         => 'การจัดการข้อความ',
                'message_delete'        => 'การลบข้อความ',
                'message_detail'        => 'รายละเอียดข้อความ',
                'paylog_index'          => 'บันทึกการชำระเงิน',
                'paylog_detail'         => 'รายละเอียดบันทึกการชำระเงิน',
                'paylog_close'          => 'ปิดบันทึกการชำระเงิน',
                'payrequestlog_index'   => 'รายการบันทึกการร้องขอการชำระเงิน',
                'payrequestlog_detail'  => 'รายละเอียดบันทึกคำขอชำระเงิน',
                'refundlog_index'       => 'บันทึกการคืนเงิน',
                'refundlog_detail'      => 'รายละเอียดบันทึกการคืนเงิน',
                'integrallog_index'     => 'บันทึกคะแนน',
                'integrallog_detail'    => 'รายละเอียดบันทึกคะแนน',
            ]
        ],
        'store_index' => [
            'name'  => 'ศูนย์การสมัคร',
            'item'  => [
                'pluginsadmin_index'         => 'การจัดการแอปพลิเคชัน',
                'plugins_index'              => 'ใช้การจัดการการโทร',
                'pluginsadmin_saveinfo'      => 'สมัครเพิ่ม / แก้ไขหน้า',
                'pluginsadmin_save'          => 'สมัครเพิ่ม / แก้ไข',
                'pluginsadmin_statusupdate'  => 'อัปเดตสถานะการสมัคร',
                'pluginsadmin_delete'        => 'ใช้การลบ',
                'pluginsadmin_upload'        => 'อัพโหลดแอป',
                'pluginsadmin_download'      => 'ใบสมัคร การบรรจุ',
                'pluginsadmin_install'       => 'ใบสมัคร การติดตั้ง',
                'pluginsadmin_uninstall'     => 'ถอนการติดตั้งโปรแกรม',
                'pluginsadmin_sortsave'      => 'ใช้การเรียงลำดับ บันทึก',
                'store_index'                => 'แอปสโตร์',
                'packageinstall_index'       => 'หน้าติดตั้งแพ็กเกจ',
                'packageinstall_install'     => 'การติดตั้งแพ็กเกจ',
                'packageupgrade_upgrade'     => 'อัพเดทแพ็กเกจ',
            ]
        ],
        'tool_index' => [
            'name'  => 'เครื่องมือ',
                'item'                  => [
                'cache_index'           => 'การจัดการแคช',
                'cache_statusupdate'    => 'การปรับปรุงแคชไซต์',
                'cache_templateupdate'  => 'การปรับปรุงแคชเทมเพลต',
                'cache_moduleupdate'    => 'การปรับปรุงแคชโมดูล',
                'cache_logdelete'       => 'การลบบันทึก',
                'sqlconsole_index'      => 'SQL คอนโซล',
                'sqlconsole_implement'  => 'การดำเนินการ SQL',
            ]
        ],
    ],
];
?>