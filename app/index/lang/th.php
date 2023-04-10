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
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'เดอะมอลล์ โฮม',
        'back_to_the_home_title'                => 'กลับไปที่หน้าแรก',
        'all_category_text'                     => 'หมวดหมู่ทั้งหมด',
        'login_title'                           => 'เข้าสู่ระบบ',
        'register_title'                        => 'ลงทะเบียน',
        'logout_title'                          => 'ออกจาก',
        'cancel_text'                           => 'การยกเลิก',
        'save_text'                             => 'บันทึก',
        'more_text'                             => 'มากกว่า',
        'processing_in_text'                    => 'กำลังประมวลผล...',
        'upload_in_text'                        => 'อัพโหลด...',
        'navigation_main_quick_name'            => 'หีบสมบัติ',
        'no_relevant_data_tips'                 => 'ไม่มีข้อมูลที่เกี่ยวข้อง',
        'avatar_upload_title'                   => 'อัพโหลด Avatar',
        'choice_images_text'                    => 'เลือกรูปภาพ',
        'choice_images_error_tips'              => 'โปรดเลือกรูปภาพที่ต้องการอัปโหลด',
        'confirm_upload_title'                  => 'ยืนยันการอัปโหลด',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'ยินดีต้อนรับคุณ',
        'header_top_nav_left_login_first'       => 'สวัสดี',
        'header_top_nav_left_login_last'        => ',ยินดีต้อนรับสู่',
        // 搜索
        'search_input_placeholder'              => 'จริงๆแล้วการค้นหาเป็นเรื่องง่าย ^_^!',
        'search_button_text'                    => 'ค้นหา',
        // 用户
        'avatar_upload_tips'                    => [
            'โปรดซูมเข้าและย้ายกล่องเลือกในพื้นที่ทำงานเลือกช่วงที่คุณต้องการครอบตัดความกว้างตัดอัตราส่วนสูงคงที่',
            'ผลกระทบหลังจากการปลูกพืชจะแสดงในรูปภาพตัวอย่างด้านขวาที่มีผลหลังจากการยืนยันการส่ง;',
        ],
        'close_user_register_tips'              => 'ปิดการลงทะเบียนผู้ใช้ชั่วคราว',
        'close_user_login_tips'                 => 'ปิดการเข้าสู่ระบบผู้ใช้ชั่วคราว',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'สวัสดี ยินดีต้อนรับสู่',
        'banner_right_article_title'            => 'หัวข้อข่าว',
        'design_browser_seo_title'              => 'การออกแบบบ้าน',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'ไม่มีข้อมูลความคิดเห็น',
        ],
        // 基础
        'goods_no_data_tips'                    => 'ไม่มีสินค้าอยู่หรือถูกลบไปแล้ว',
        'panel_can_choice_spec_name'            => 'ข้อกำหนดเสริม',
        'recommend_goods_title'                 => 'ดู แล้ว ดู',
        'dynamic_scoring_name'                  => 'คะแนนแบบไดนามิก',
        'no_scoring_data_tips'                  => 'ไม่มีข้อมูลการให้คะแนน',
        'no_comments_data_tips'                 => 'ไม่มีข้อมูลการประเมิน',
        'comments_first_name'                   => 'แสดงความคิดเห็นใน',
        'admin_reply_name'                      => 'ผู้ดูแลระบบตอบกลับ:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'ค้นหาสินค้า',
        'filter_out_first_text'                 => 'กรองออก',
        'filter_out_last_data_text'             => 'ข้อมูลแถบ',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'การจำแนกประเภทสินค้า',
        'no_category_data_tips'                 => 'ไม่มีข้อมูลจำแนกประเภท',
        'no_sub_category_data_tips'             => 'ไม่มีข้อมูลการจำแนกประเภทย่อย',
        'view_category_sub_goods_name'          => 'ดูสินค้าภายใต้หมวดหมู่',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'กรุณาเลือกสินค้า',
        ],
        // 基础
        'browser_seo_title'                     => 'ตะกร้าสินค้า',
        'goods_list_thead_base'                 => 'ข้อมูลสินค้า',
        'goods_list_thead_price'                => 'ราคาต่อหน่วย',
        'goods_list_thead_number'               => 'ปริมาณ',
        'goods_list_thead_total'                => 'จำนวนเงิน',
        'goods_item_total_name'                 => 'รวม',
        'summary_selected_goods_name'           => 'สินค้าที่เลือก',
        'summary_selected_goods_unit'           => 'ชิ้น',
        'summary_nav_goods_total'               => 'รวม:',
        'summary_nav_button_name'               => 'การตั้งถิ่นฐาน',
        'no_cart_data_tips'                     => 'รถเข็นของคุณยังคงว่างเปล่าคุณสามารถ',
        'no_cart_data_my_favor_name'            => 'รายการโปรด',
        'no_cart_data_my_order_name'            => 'คำสั่งซื้อของฉัน',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'กรุณาเลือกที่อยู่',
            'payment_choice_tips'               => 'กรุณาเลือกการชำระเงิน',
        ],
        // 基础
        'browser_seo_title'                     => 'ยืนยันการสั่งซื้อ',
        'exhibition_not_allow_submit_tips'      => 'ประเภทโชว์รูม ไม่อนุญาตให้ส่งคำสั่งซื้อ',
        'buy_item_order_title'                  => 'ข้อมูลการสั่งซื้อ',
        'buy_item_payment_title'                => 'เลือกการชำระเงิน',
        'confirm_delivery_address_name'         => 'ยืนยันที่อยู่รับสินค้า',
        'use_new_address_name'                  => 'ใช้ที่อยู่ใหม่',
        'no_delivery_address_tips'              => 'ไม่มีที่อยู่สำหรับจัดส่ง',
        'confirm_extraction_address_name'       => 'ยืนยันที่อยู่ที่ได้รับด้วยตนเอง',
        'choice_take_address_name'              => 'เลือกที่อยู่ Pick up',
        'no_take_address_tips'                  => 'ติดต่อผู้ดูแลระบบเพื่อกำหนดค่าที่อยู่ยกด้วยตนเอง',
        'no_address_tips'                       => 'ไม่มีที่อยู่',
        'extraction_list_choice_title'          => 'Self-lift เลือก',
        'goods_list_thead_base'                 => 'ข้อมูลสินค้า',
        'goods_list_thead_price'                => 'ราคาต่อหน่วย',
        'goods_list_thead_number'               => 'ปริมาณ',
        'goods_list_thead_total'                => 'จำนวนเงิน',
        'goods_item_total_name'                 => 'รวม',
        'not_goods_tips'                        => 'ไม่มีสินค้า',
        'not_payment_tips'                      => 'ไม่มีวิธีการชำระเงิน',
        'user_message_title'                    => 'ข้อความจากผู้ซื้อ',
        'user_message_placeholder'              => 'คำแนะนำสำหรับการเลือกกรอกข้อเสนอแนะและการตกลงกับผู้ขาย',
        'summary_title'                         => 'การชำระเงินจริง:',
        'summary_contact_name'                  => 'ผู้ติดต่อ:',
        'summary_address'                       => 'ที่อยู่:',
        'summary_submit_order_name'             => 'ส่งคำสั่งซื้อ',
        'payment_layer_title'                   => 'การจ่ายเงินในกระโดดอย่าปิดหน้า',
        'payment_layer_content'                 => 'การจ่ายเงินล้มเหลวหรือไม่ได้รับการตอบรับเป็นเวลานาน',
        'payment_layer_order_button_text'       => 'คำสั่งซื้อของฉัน',
        'payment_layer_tips'                    => 'การชำระเงินสามารถเริ่มต้นใหม่หลังจาก',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'บทความทั้งหมด',
        'article_no_data_tips'                  => 'บทความไม่มีอยู่หรือถูกลบไปแล้ว',
        'article_id_params_tips'                => 'รหัสบทความผิดพลาด',
        'release_time'                          => 'เวลาโพสต์:',
        'view_number'                           => 'มุมมอง:',
        'prev_article'                          => 'ก่อนหน้านี้:',
        'next_article'                          => 'ต่อไป:',
        'article_category_name'                 => 'หมวดหมู่บทความ',
        'article_nav_text'                      => 'แถบนำทางด้านข้าง',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'ไม่มีหน้าหรือลบไปแล้ว',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'ไม่มีหน้าหรือลบไปแล้ว',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'รหัสการสั่งซื้อผิดพลาด',
            'payment_choice_tips'               => 'กรุณาเลือกวิธีการชำระเงิน',
            'rating_string'                     => 'แย่มาก แย่ ปานกลาง ดี ดีมาก',
            'not_choice_data_tips'              => 'โปรดตรวจสอบข้อมูลก่อน',
            'pay_url_empty_tips'                => 'ที่อยู่ URL การชำระเงินผิดพลาด',
        ],
        // 基础
        'browser_seo_title'                     => 'คำสั่งซื้อของฉัน',
        'detail_browser_seo_title'              => 'รายละเอียดการสั่งซื้อ',
        'comments_browser_seo_title'            => 'ความคิดเห็นการสั่งซื้อ',
        'batch_payment_name'                    => 'การชำระเงินจำนวนมาก',
        'comments_goods_list_thead_base'        => 'ข้อมูลสินค้า',
        'comments_goods_list_thead_price'       => 'ราคาต่อหน่วย',
        'comments_goods_list_thead_content'     => 'เนื้อหาแสดงความคิดเห็น',
        'form_you_have_commented_tips'          => 'คุณได้แสดงความคิดเห็น',
        'form_payment_title'                    => 'การชำระเงิน',
        'form_payment_no_data_tips'             => 'ไม่มีวิธีการชำระเงิน',
        'order_base_title'                      => 'ข้อมูลการสั่งซื้อ',
        'order_base_warehouse_title'            => 'บริการจัดส่ง:',
        'order_base_model_title'                => 'โหมดการสั่งซื้อ:',
        'order_base_order_no_title'             => 'หมายเลขการสั่งซื้อ:',
        'order_base_status_title'               => 'สถานะการสั่งซื้อ:',
        'order_base_pay_status_title'           => 'สถานะการชำระเงิน:',
        'order_base_payment_title'              => 'วิธีการชำระเงิน:',
        'order_base_total_price_title'          => 'ราคารวมของการสั่งซื้อ:',
        'order_base_buy_number_title'           => 'ปริมาณการซื้อ:',
        'order_base_returned_quantity_title'    => 'ปริมาณการคืนสินค้า:',
        'order_base_user_note_title'            => 'ข้อความจากผู้ใช้:',
        'order_base_add_time_title'             => 'เวลาสั่งซื้อ:',
        'order_base_confirm_time_title'         => 'ยืนยันเวลา:',
        'order_base_pay_time_title'             => 'ระยะเวลาการชำระเงิน:',
        'order_base_delivery_time_title'        => 'เวลาจัดส่ง:',
        'order_base_collect_time_title'         => 'เวลารับของ:',
        'order_base_user_comments_time_title'   => 'เวลาแสดงความคิดเห็น:',
        'order_base_cancel_time_title'          => 'เวลายกเลิก:',
        'order_base_express_title'              => 'บริษัท ด่วน:',
        'order_base_express_website_title'      => 'เว็บไซต์อย่างเป็นทางการของ Express:',
        'order_base_express_number_title'       => 'หมายเลขเอกสารด่วน:',
        'order_base_price_title'                => 'ราคาสินค้าทั้งหมด:',
        'order_base_increase_price_title'       => 'เพิ่มจำนวนเงิน:',
        'order_base_preferential_price_title'   => 'จำนวนข้อเสนอ:',
        'order_base_refund_price_title'         => 'จำนวนเงินคืน:',
        'order_base_pay_price_title'            => 'จำนวนเงินที่จ่าย:',
        'order_base_take_code_title'            => 'รับรหัส:',
        'order_base_take_code_no_exist_tips'    => 'ไม่มีรหัสรับสินค้า กรุณาติดต่อผู้ดูแลระบบ',
        'order_under_line_tips'                 => 'ปัจจุบันเป็นวิธีการชำระเงินแบบออฟไลน์ [{:payment}]] ต้องได้รับการยืนยันจากผู้ดูแลระบบหลังจากมีผลบังคับใช้หากต้องการชำระเงินอื่น ๆ สามารถสลับการชำระเงินเพื่อเริ่มการชำระเงินใหม่',
        'order_delivery_tips'                   => 'สินค้าถูกบรรจุออกจากคลังสินค้า',
        'order_goods_no_data_tips'              => 'ไม่มีข้อมูลการสั่งซื้อสินค้า',
        'order_status_operate_first_tips'       => 'คุณสามารถ',
        'goods_list_thead_base'                 => 'ข้อมูลสินค้า',
        'goods_list_thead_price'                => 'ราคาต่อหน่วย',
        'goods_list_thead_number'               => 'ปริมาณ',
        'goods_list_thead_total'                => 'จำนวนเงิน',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'ข้อมูลพื้นฐาน',
            'goods_placeholder'     => 'โปรดป้อนหมายเลขการสั่งซื้อ / ชื่อสินค้า / รุ่น',
            'status'                => 'สถานะการสั่งซื้อ',
            'pay_status'            => 'สถานะการชำระเงิน',
            'total_price'           => 'ราคารวม (CNY)',
            'pay_price'             => 'จำนวนเงินที่ต้องชำระ (CNY)',
            'price'                 => 'ราคาต่อหน่วย (CNY)',
            'order_model'           => 'โหมดการสั่งซื้อ',
            'client_type'           => 'แพลตฟอร์มการสั่งซื้อ',
            'address'               => 'ข้อมูลที่อยู่',
            'take'                  => 'ข้อมูลการรับสินค้า',
            'refund_price'          => 'จำนวนเงินคืน (CNY)',
            'returned_quantity'     => 'ปริมาณการคืนสินค้า',
            'buy_number_count'      => 'ยอดซื้อ',
            'increase_price'        => 'เพิ่มจำนวนเงิน (หยวน)',
            'preferential_price'    => 'จำนวนข้อเสนอ (CNY)',
            'payment_name'          => 'วิธีการชำระเงิน',
            'user_note'             => 'ฝากข้อความ',
            'extension'             => 'ขยายข้อมูล',
            'express_name'          => 'บริษัท เอ็กซ์เพรส',
            'express_number'        => 'หมายเลขเอกสารด่วน',
            'is_comments'           => 'ไม่ว่าจะเป็นการแสดงความคิดเห็น',
            'confirm_time'          => 'ยืนยันเวลา',
            'pay_time'              => 'เวลาชำระเงิน',
            'delivery_time'         => 'เวลาจัดส่ง',
            'collect_time'          => 'เวลาเสร็จสิ้น',
            'cancel_time'           => 'เวลายกเลิก',
            'close_time'            => 'เวลาปิด',
            'add_time'              => 'สร้างเวลา',
            'upd_time'              => 'อัปเดตเวลา',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'ยอดการสั่งซื้อทั้งหมด',
            'pay_price'             => 'การชำระเงินทั้งหมด',
            'buy_number_count'      => 'สินค้าทั้งหมด',
            'refund_price'          => 'การคืนเงิน',
            'returned_quantity'     => 'การคืนสินค้า',
            'price_unit'            => 'หยวน',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'ข้อมูลเหตุผลในการคืนเงินว่างเปล่า',
        ],
        // 基础
        'browser_seo_title'                     => 'สั่งซื้อหลังการขาย',
        'detail_browser_seo_title'              => 'รายละเอียดการสั่งซื้อหลังการขาย',
        'view_orderaftersale_enter_name'        => 'ดูคำสั่งซื้อหลังการขาย',
        'operate_delivery_name'                 => 'ส่งคืนเดี๋ยวนี้',
        'goods_list_thead_base'                 => 'ข้อมูลสินค้า',
        'goods_list_thead_price'                => 'ราคาต่อหน่วย',
        'goods_base_price_title'                => 'ราคาสินค้าทั้งหมด:',
        'goods_base_increase_price_title'       => 'เพิ่มจำนวนเงิน:',
        'goods_base_preferential_price_title'   => 'จำนวนข้อเสนอ:',
        'goods_base_refund_price_title'         => 'จำนวนเงินคืน:',
        'goods_base_pay_price_title'            => 'จำนวนเงินที่จ่าย:',
        'goods_base_total_price_title'          => 'ราคารวมของการสั่งซื้อ:',
        'base_apply_title'                      => 'ข้อมูลการสมัคร',
        'base_apply_type_title'                 => 'ประเภทการคืนเงิน:',
        'base_apply_status_title'               => 'สถานะปัจจุบัน:',
        'base_apply_reason_title'               => 'เหตุผลในการสมัคร:',
        'base_apply_number_title'               => 'ปริมาณการคืนสินค้า:',
        'base_apply_price_title'                => 'จำนวนเงินคืน:',
        'base_apply_msg_title'                  => 'คำแนะนำการคืนเงิน:',
        'base_apply_refundment_title'           => 'วิธีการคืนเงิน:',
        'base_apply_refuse_reason_title'        => 'เหตุผลในการปฏิเสธ:',
        'base_apply_apply_time_title'           => 'ระยะเวลาการสมัคร:',
        'base_apply_confirm_time_title'         => 'ยืนยันเวลา:',
        'base_apply_delivery_time_title'        => 'เวลาคืนสินค้า:',
        'base_apply_audit_time_title'           => 'เวลาในการตรวจสอบ:',
        'base_apply_cancel_time_title'          => 'เวลายกเลิก:',
        'base_apply_add_time_title'             => 'เพิ่มเวลา:',
        'base_apply_upd_time_title'             => 'อัปเดตเวลา:',
        'base_item_express_title'               => 'ข้อมูลด่วน',
        'base_item_express_name'                => 'จัดส่งด่วน:',
        'base_item_express_number'              => 'หมายเลขเดี่ยว:',
        'base_item_express_time'                => 'เวลา:',
        'base_item_voucher_title'               => 'คูปอง',
        // 表单
        'form_delivery_title'                   => 'การดำเนินการคืนสินค้า',
        'form_delivery_address_name'            => 'ที่อยู่ส่งคืน',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'ข้อมูลพื้นฐาน',
            'goods_placeholder'     => 'โปรดป้อนหมายเลขการสั่งซื้อ / ชื่อสินค้า / รุ่น',
            'status'                => 'สถานะ',
            'type'                  => 'ประเภทการสมัคร',
            'reason'                => 'เหตุผล',
            'price'                 => 'จำนวนเงินคืน (CNY)',
            'number'                => 'ปริมาณการคืนสินค้า',
            'msg'                   => 'คำแนะนำการคืนเงิน',
            'refundment'            => 'ประเภทการคืนเงิน',
            'express_name'          => 'บริษัท เอ็กซ์เพรส',
            'express_number'        => 'หมายเลขเอกสารด่วน',
            'apply_time'            => 'ระยะเวลาการสมัคร',
            'confirm_time'          => 'ยืนยันเวลา',
            'delivery_time'         => 'เวลาคืนสินค้า',
            'audit_time'            => 'เวลาตรวจสอบ',
            'add_time'              => 'สร้างเวลา',
            'upd_time'              => 'อัปเดตเวลา',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'ศูนย์ผู้ใช้',
        'forget_password_browser_seo_title'     => 'การกู้คืนรหัสผ่าน',
        'user_register_browser_seo_title'       => 'ลงทะเบียนผู้ใช้',
        'user_login_browser_seo_title'          => 'เข้าสู่ระบบผู้ใช้',
        'password_reset_illegal_error_tips'     => 'เข้าสู่ระบบแล้ว หากต้องการรีเซ็ตรหัสผ่าน กรุณาออกจากบัญชีปัจจุบันก่อน',
        'register_illegal_error_tips'           => 'ลงชื่อเข้าใช้แล้ว หากต้องการลงทะเบียนบัญชีใหม่ โปรดออกจากบัญชีปัจจุบันก่อน',
        'login_illegal_error_tips'              => 'เข้าสู่ระบบแล้ว อย่าเข้าสู่ระบบซ้ำ',
        // 页面
        // 登录
        'login_top_register_tips'               => 'ยังไม่มีหมายเลขบัญชี?',
        'login_close_tips'                      => 'ปิดล็อกอินชั่วคราว',
        'login_type_username_title'             => 'หมายเลขบัญชี รหัสผ่าน',
        'login_type_mobile_title'               => 'รหัสยืนยันโทรศัพท์มือถือ',
        'login_type_email_title'                => 'รหัสยืนยันกล่องจดหมาย',
        'login_retrieve_password_title'         => 'กู้คืนรหัสผ่าน',
        // 注册
        'register_top_login_tips'               => 'ฉันลงทะเบียนแล้วตอนนี้',
        'register_close_tips'                   => 'ปิดการลงทะเบียนชั่วคราว',
        'register_type_username_title'          => 'ลงทะเบียนหมายเลขบัญชี',
        'register_type_mobile_title'            => 'ลงทะเบียนโทรศัพท์มือถือ',
        'register_type_email_title'             => 'ลงทะเบียนกล่องจดหมาย',
        // 忘记密码
        'forget_password_top_login_tips'        => 'มีเลขบัญชีอยู่แล้ว?',
        // 表单
        'form_item_agreement'                   => 'อ่านและยินยอม',
        'form_item_agreement_message'           => 'กรุณาระบุข้อตกลงยินยอม',
        'form_item_service'                     => 'ข้อตกลงการให้บริการ',
        'form_item_privacy'                     => 'นโยบายความเป็นส่วนตัว"',
        'form_item_username'                    => 'ชื่อผู้ใช้',
        'form_item_username_message'            => 'กรุณาใช้ตัวอักษรตัวเลขขีดเส้นใต้ 2 ~ 18 ตัวอักษร',
        'form_item_password'                    => 'รหัสผ่านเข้าสู่ระบบ',
        'form_item_password_placeholder'        => 'กรุณากรอกรหัสผ่านเข้าสู่ระบบ',
        'form_item_password_message'            => 'รูปแบบรหัสผ่าน ระหว่าง 6 ~ 18 ตัวอักษร',
        'form_item_mobile'                      => 'หมายเลขโทรศัพท์มือถือ',
        'form_item_mobile_placeholder'          => 'กรุณากรอกหมายเลขโทรศัพท์มือถือ',
        'form_item_mobile_message'              => 'รูปแบบหมายเลขโทรศัพท์มือถือไม่ถูกต้อง',
        'form_item_email'                       => 'อีเมล์',
        'form_item_email_placeholder'           => 'กรุณากรอกอีเมลล์',
        'form_item_email_message'               => 'รูปแบบอีเมลไม่ถูกต้อง',
        'form_item_account'                     => 'ลงชื่อเข้าใช้บัญชี',
        'form_item_account_placeholder'         => 'โปรดป้อนชื่อผู้ใช้ / โทรศัพท์มือถือ / กล่องจดหมาย',
        'form_item_account_message'             => 'กรุณากรอกหมายเลขบัญชีเข้าสู่ระบบ',
        'form_item_mobile_email'                => 'โทรศัพท์มือถือ / กล่องจดหมาย',
        'form_item_mobile_email_message'        => 'โปรดป้อนรูปแบบโทรศัพท์มือถือ/กล่องจดหมายที่ถูกต้อง',
        // 个人中心
        'base_avatar_title'                     => 'แก้ไขอวตาร',
        'base_personal_title'                   => 'แก้ไขข้อมูล',
        'base_address_title'                    => 'ที่อยู่ของฉัน',
        'base_message_title'                    => 'ข้อความ',
        'order_nav_title'                       => 'คำสั่งซื้อของฉัน',
        'order_nav_angle_title'                 => 'ดูคำสั่งซื้อทั้งหมด',
        'various_transaction_title'             => 'การแจ้งเตือนการเทรด',
        'various_transaction_tips'              => 'การแจ้งเตือนการซื้อขายช่วยให้คุณทราบสถานะการสั่งซื้อและสถานการณ์โลจิสติกส์',
        'various_cart_title'                    => 'ตะกร้าสินค้า',
        'various_cart_empty_title'              => 'ตะกร้าสินค้าของคุณยังว่างเปล่า',
        'various_cart_tips'                     => 'วางสินค้าที่ต้องการซื้อในรถเข็นและชำระบัญชีร่วมกันได้ง่ายขึ้น',
        'various_favor_title'                   => 'คอลเลกชันสินค้า',
        'various_favor_empty_title'             => 'คุณยังไม่ได้สะสมสินค้า',
        'various_favor_tips'                    => 'สินค้าที่สะสมจะแสดงโปรโมชั่นล่าสุดและการลดราคา',
        'various_browse_title'                  => 'รอยเท้าของฉัน',
        'various_browse_empty_title'            => 'บันทึกการเรียกดูสินค้าของคุณว่างเปล่า',
        'various_browse_tips'                   => 'รีบไปดูโปรโมชั่นที่เดอะมอลล์',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'ที่อยู่ของฉัน',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'รอยเท้าของฉัน',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'ข้อมูลสินค้า',
            'goods_placeholder'     => 'โปรดป้อนชื่อการค้า / คำอธิบายสั้น ๆ / ข้อมูล SEO',
            'price'                 => 'ราคาขาย (CNY)',
            'original_price'        => 'ราคาเดิม (CNY)',
            'add_time'              => 'สร้างเวลา',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'คอลเลกชันสินค้า',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'ข้อมูลสินค้า',
            'goods_placeholder'     => 'โปรดป้อนชื่อการค้า / คำอธิบายสั้น ๆ / ข้อมูล SEO',
            'price'                 => 'ราคาขาย (CNY)',
            'original_price'        => 'ราคาเดิม (CNY)',
            'add_time'              => 'สร้างเวลา',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'คะแนนของฉัน',
        // 页面
        'base_normal_title'                     => 'ใช้ได้ตามปกติ',
        'base_normal_tips'                      => 'คะแนนที่สามารถใช้ได้ตามปกติ',
        'base_locking_title'                    => 'ล็อคปัจจุบัน',
        'base_locking_tips'                     => 'ในการทำธุรกรรมคะแนนทั่วไปการทำธุรกรรมยังไม่เสร็จสมบูรณ์ล็อคคะแนนที่สอดคล้องกัน',
        'base_integral_unit'                    => 'คะแนน',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'ประเภทการดำเนินงาน',
            'operation_integral'    => 'คะแนนการดำเนินงาน',
            'original_integral'     => 'คะแนนดิบ',
            'new_integral'          => 'คะแนนล่าสุด',
            'msg'                   => 'คำอธิบาย',
            'add_time_time'         => 'เวลา',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'ข้อมูลส่วนตัว',
        'edit_browser_seo_title'                => 'แก้ไขโปรไฟล์',
        'form_item_nickname'                    => 'ชื่อเล่น',
        'form_item_nickname_message'            => 'ชื่อเล่น ระหว่าง 2 ~ 16 ตัวอักษร',
        'form_item_birthday'                    => 'วันเกิด',
        'form_item_birthday_message'            => 'รูปแบบวันเกิดผิดพลาด',
        'form_item_province'                    => 'จังหวัด',
        'form_item_province_message'            => 'ไม่เกิน 30 ตัวอักษรในจังหวัดนั้นๆ',
        'form_item_city'                        => 'เมืองที่ตั้งอยู่',
        'form_item_city_message'                => 'สูงสุด 30 ตัวอักษรในเมืองของคุณ',
        'form_item_county'                      => 'อำเภอ/เขต',
        'form_item_county_message'              => 'เขต/อำเภอ ไม่เกิน 30 ตัวอักษร',
        'form_item_address'                     => 'รายละเอียดที่อยู่',
        'form_item_address_message'             => 'รายละเอียดที่อยู่ 2 ~ 30 ตัวอักษร',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'ข้อความของฉัน',
        // 动态表格
        'form_table'                => [
            'type'                  => 'ประเภทข้อความ',
            'business_type'         => 'ประเภทธุรกิจ',
            'title'                 => 'ชื่อเรื่อง',
            'detail'                => 'รายละเอียด',
            'is_read'               => 'สถานะ',
            'add_time_time'         => 'เวลา',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'ถาม-ตอบ / ฝากข้อความ',
        // 表单
        'form_title'                            => 'คำถาม/ฝากข้อความ',
        'form_item_name'                        => 'ชื่อเล่น',
        'form_item_name_message'                => 'รูปแบบชื่อเล่น ระหว่าง 1 ~ 30 ตัวอักษร',
        'form_item_tel'                         => 'โทรศัพท์',
        'form_item_tel_message'                 => 'กรุณากรอกเบอร์โทรศัพท์',
        'form_item_title'                       => 'ชื่อเรื่อง',
        'form_item_title_message'               => 'รูปแบบหัวเรื่อง ระหว่าง 1 ~ 60 ตัวอักษร',
        'form_item_content'                     => 'เนื้อหา',
        'form_item_content_message'             => 'รูปแบบเนื้อหา ระหว่าง 5 ~ 1,000 ตัวอักษร',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'ติดต่อ',
            'tel'                   => 'เบอร์ติดต่อ',
            'content'               => 'เนื้อหา',
            'reply'                 => 'ตอบกลับเนื้อหา',
            'reply_time_time'       => 'เวลาตอบกลับ',
            'add_time_time'         => 'สร้างเวลา',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'การตั้งค่าความปลอดภัย',
        'password_update_browser_seo_title'     => 'แก้ไขรหัสผ่านเข้าสู่ระบบ - การตั้งค่าความปลอดภัย',
        'mobile_update_browser_seo_title'       => 'การปรับเปลี่ยนหมายเลขโทรศัพท์มือถือ - การตั้งค่าความปลอดภัย',
        'email_update_browser_seo_title'        => 'การปรับเปลี่ยนกล่องจดหมายอิเล็กทรอนิกส์ - การตั้งค่าความปลอดภัย',
        'logout_browser_seo_title'              => 'หมายเลขบัญชีออกจากระบบ - การตั้งค่าความปลอดภัย',
        'original_account_check_error_tips'     => 'การตรวจสอบหมายเลขบัญชีเดิมล้มเหลว',
        // 页面
        'logout_title'                          => 'หมายเลขบัญชี ออกจากระบบ',
        'logout_confirm_title'                  => 'ยืนยันการออกจากระบบ',
        'logout_confirm_tips'                   => 'หมายเลขบัญชีไม่สามารถกู้คืนได้หลังจากออกจากระบบ, แน่ใจว่าจะดำเนินการต่อ?',
        'email_title'                           => 'การตรวจสอบอีเมลต้นฉบับ',
        'email_new_title'                       => 'การตรวจสอบอีเมลใหม่',
        'mobile_title'                          => 'การตรวจสอบหมายเลขโทรศัพท์มือถือเดิม',
        'mobile_new_title'                      => 'การตรวจสอบหมายเลขโทรศัพท์มือถือใหม่',
        'login_password_title'                  => 'แก้ไขรหัสผ่านเข้าสู่ระบบ',
    ],
];
?>