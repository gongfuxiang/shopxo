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
 * 模块语言包-繁体中文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => '商城首頁',
        'back_to_the_home_title'                => '回到首頁',
        'all_category_text'                     => '全部分類',
        'login_title'                           => '登入',
        'register_title'                        => '注册',
        'logout_title'                          => '退出',
        'cancel_text'                           => '取消',
        'save_text'                             => '保存',
        'more_text'                             => '更多',
        'processing_in_text'                    => '處理中…',
        'upload_in_text'                        => '上傳中…',
        'navigation_main_quick_name'            => '更多入口',
        'no_relevant_data_tips'                 => '沒有相關資料',
        'avatar_upload_title'                   => '頭像上傳',
        'choice_images_text'                    => '選擇圖片',
        'choice_images_error_tips'              => '請選擇需要上傳的圖片',
        'confirm_upload_title'                  => '確認上傳',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => '歡迎您',
        'header_top_nav_left_login_first'       => '您好',
        'header_top_nav_left_login_last'        => '，歡迎來到',
        // 搜索
        'search_input_placeholder'              => '其實蒐索很簡單^_^！',
        'search_button_text'                    => '蒐索',
        // 用户
        'avatar_upload_tips'                    => [
            '請在工作區域放大縮小及移動選取框，選擇要裁剪的範圍，裁切寬高比例固定；',
            '裁切後的效果為右側預覽圖所顯示，確認提交後生效；',
        ],
        'close_user_register_tips'              => '暫時關閉用戶註冊',
        'close_user_login_tips'                 => '暫時關閉用戶登錄',
        // 底部
        'footer_icp_filing_text'                => 'ICP備案',
        'footer_public_security_filing_text'    => '警察備案',
        'footer_business_license_text'          => '電子營業執照亮照',
        // 购物车
        'user_cart_success_modal_tips'          => '商品已成功加入購物車！',
        'user_cart_success_modal_text_first'    => '購物車共',
        'user_cart_success_modal_text_last'     => '件商品',
        'user_cart_success_modal_cart_title'    => '去購物車結算',
        'user_cart_success_modal_buy_title'     => '繼續購物',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => '您好，歡迎來到',
        'banner_right_article_title'            => '資訊頭條',
        'design_base_nav_title'                 => '首頁設計',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => '沒有評論數據',
        ],
        // 基础
        'goods_no_data_tips'                    => '商品不存在或已删除',
        'panel_can_choice_spec_name'            => '可選規格',
        'recommend_goods_title'                 => '看了又看',
        'dynamic_scoring_name'                  => '動態評分',
        'no_scoring_data_tips'                  => '沒有評分數據',
        'no_comments_data_tips'                 => '沒有評估數據',
        'comments_first_name'                   => '評論於',
        'admin_reply_name'                      => '管理員回復：',
        'qrcode_mobile_buy_name'                => '手機購買',
    ],

    // 商品搜索
    'search'            => [
        'base_nav_title'                        => '商品搜索',
        'filter_out_first_text'                 => '篩選出',
        'filter_out_last_data_text'             => '條數據',
    ],

    // 商品分类
    'category'          => [
        'base_nav_title'                        => '商品分類',
        'no_category_data_tips'                 => '沒有分類數據',
        'no_sub_category_data_tips'             => '沒有子分類數據',
        'view_category_sub_goods_name'          => '查看分類下商品',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => '請選擇商品',
        ],
        // 基础
        'base_nav_title'                        => '購物車',
        'goods_list_thead_base'                 => '商品資訊',
        'goods_list_thead_price'                => '單價',
        'goods_list_thead_number'               => '數量',
        'goods_list_thead_total'                => '合計',
        'goods_item_total_name'                 => '總價',
        'summary_selected_goods_name'           => '已選商品',
        'summary_selected_goods_unit'           => '件',
        'summary_nav_goods_total'               => '合計：',
        'summary_nav_button_name'               => '結算',
        'no_cart_data_tips'                     => '您的購物車還是空的，您可以',
        'no_cart_data_my_favor_name'            => '我的我的最愛',
        'no_cart_data_my_order_name'            => '我的訂單',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => '請選擇地址',
            'payment_choice_tips'               => '請選擇支付',
        ],
        // 基础
        'base_nav_title'                        => '訂單確認',
        'exhibition_not_allow_submit_tips'      => '展示型不允許提交訂單',
        'buy_item_order_title'                  => '訂單資訊',
        'buy_item_payment_title'                => '選擇支付',
        'confirm_delivery_address_name'         => '確認收貨地址',
        'use_new_address_name'                  => '添加新地址',
        'no_address_info_tips'                  => '沒有地址資訊！',
        'confirm_extraction_address_name'       => '確認自提點地址',
        'choice_take_address_name'              => '選擇取貨地址',
        'no_take_address_tips'                  => '請聯系管理員配寘自提點地址',
        'no_address_tips'                       => '沒有地址',
        'extraction_list_choice_title'          => '自提點選擇',
        'goods_list_thead_base'                 => '商品資訊',
        'goods_list_thead_price'                => '單價',
        'goods_list_thead_number'               => '數量',
        'goods_list_thead_total'                => '合計',
        'goods_item_total_name'                 => '總價',
        'not_goods_tips'                        => '沒有商品',
        'not_payment_tips'                      => '沒有支付方式',
        'user_message_title'                    => '買家留言',
        'user_message_placeholder'              => '選填、建議填寫和賣家達成一致的說明',
        'summary_title'                         => '實付款：',
        'summary_contact_name'                  => '連絡人：',
        'summary_address'                       => '地址：',
        'summary_submit_order_name'             => '提交訂單',
        'payment_layer_title'                   => '支付跳轉中、請勿關閉頁面',
        'payment_layer_content'                 => '支付失敗或長時間未響應',
        'payment_layer_order_button_text'       => '我的訂單',
        'payment_layer_tips'                    => '後可以重新發起支付',
        'extraction_contact_name'               => '我的姓名',
        'extraction_contact_tel'                => '我的電話',
        'extraction_contact_tel_placeholder'    => '我的手機或座機',
    ],

    // 文章
    'article'            => [
        'category_base_nav_title'               => '所有文章',
        'article_no_data_tips'                  => '文章不存在或已删除',
        'article_id_params_tips'                => '文章ID有誤',
        'release_time'                          => '發佈時間：',
        'view_number'                           => '流覽次數：',
        'prev_article'                          => '上一篇：',
        'next_article'                          => '下一篇：',
        'article_category_name'                 => '文章分類',
        'recommended_article_name'              => '推薦文章',
        'article_nav_text'                      => '側欄導航',
        'article_search_placeholder'            => '輸入關鍵字蒐索',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => '頁面不存在或已删除',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => '頁面不存在或已删除',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => '訂單id有誤',
            'payment_choice_tips'               => '請選擇支付方式',
            'rating_string'                     => '非常差，差，一般，好，非常好',
            'not_choice_data_tips'              => '請先選中數據',
            'pay_url_empty_tips'                => '支付url地址有誤',
        ],
        // 基础
        'base_nav_title'                        => '我的訂單',
        'detail_base_nav_title'                 => '訂單詳情',
        'detail_take_title'                     => '取貨地址',
        'detail_shipping_address_title'         => '收貨地址',
        'comments_base_nav_title'               => '訂單評論',
        'batch_payment_name'                    => '批量支付',
        'comments_goods_list_thead_base'        => '商品資訊',
        'comments_goods_list_thead_price'       => '單價',
        'comments_goods_list_thead_content'     => '評論內容',
        'form_you_have_commented_tips'          => '你已進行過評論',
        'form_payment_title'                    => '支付',
        'form_payment_no_data_tips'             => '沒有支付方式',
        'order_base_title'                      => '訂單資訊',
        'order_status_title'                    => '訂單狀態',
        'order_contact_title'                   => '連絡人',
        'order_consignee_title'                 => '收貨人',
        'order_phone_title'                     => '手機號',
        'order_base_warehouse_title'            => '出貨服務：',
        'order_base_model_title'                => '訂單模式：',
        'order_base_order_no_title'             => '訂單編號：',
        'order_base_status_title'               => '訂單狀態：',
        'order_base_pay_status_title'           => '支付狀態：',
        'order_base_payment_title'              => '支付方式：',
        'order_base_total_price_title'          => '訂單總價：',
        'order_base_buy_number_title'           => '購買數量：',
        'order_base_returned_quantity_title'    => '退貨數量：',
        'order_base_user_note_title'            => '用戶留言：',
        'order_base_add_time_title'             => '下單時間：',
        'order_base_confirm_time_title'         => '確認時間：',
        'order_base_pay_time_title'             => '付款時間：',
        'order_base_collect_time_title'         => '收貨時間：',
        'order_base_user_comments_time_title'   => '評論時間：',
        'order_base_cancel_time_title'          => '取消時間：',
        'order_base_close_time_title'           => '關閉時間：',
        'order_base_price_title'                => '商品總價：',
        'order_base_increase_price_title'       => '新增金額：',
        'order_base_preferential_price_title'   => '優惠金額：',
        'order_base_refund_price_title'         => '退款金額：',
        'order_base_pay_price_title'            => '支付金額：',
        'order_base_take_code_title'            => '取貨碼：',
        'order_base_take_code_no_exist_tips'    => '取貨碼不存在、請聯系管理員',
        'order_under_line_tips'                 => '當前為線下支付方式[ {:payment} ]、需管理員確認後方可生效，如需其它支付可以切換支付重新發起支付。',
        'order_delivery_tips'                   => '貨物正在倉庫打包、出庫中…',
        'order_goods_no_data_tips'              => '沒有訂單商品數據',
        'order_base_service_name'               => '服務人員姓名',
        'order_base_service_mobile'             => '服務人員手機',
        'order_base_service_time'               => '服務時間',
        'order_status_operate_first_tips'       => '您可以',
        'goods_list_thead_base'                 => '商品資訊',
        'goods_list_thead_price'                => '單價',
        'goods_list_thead_number'               => '數量',
        'goods_list_thead_total'                => '合計',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基礎資訊',
            'goods_placeholder'     => '請輸入訂單號/商品名稱/型號',
            'status'                => '訂單狀態',
            'pay_status'            => '支付狀態',
            'total_price'           => '總價',
            'pay_price'             => '支付金額',
            'price'                 => '單價',
            'order_model'           => '訂單模式',
            'client_type'           => '下單平臺',
            'address'               => '地址資訊',
            'service'               => '服務資訊',
            'take'                  => '取貨資訊',
            'refund_price'          => '退款金額',
            'returned_quantity'     => '退貨數量',
            'buy_number_count'      => '購買總數',
            'increase_price'        => '新增金額',
            'preferential_price'    => '優惠金額',
            'payment_name'          => '支付方式',
            'user_note'             => '留言資訊',
            'extension'             => '擴展資訊',
            'express'               => '快遞資訊',
            'express_placeholder'   => '請輸入快遞單號',
            'is_comments'           => '是否評論',
            'confirm_time'          => '確認時間',
            'pay_time'              => '支付時間',
            'delivery_time'         => '發貨時間',
            'collect_time'          => '完成時間',
            'cancel_time'           => '取消時間',
            'close_time'            => '關閉時間',
            'add_time'              => '創建時間',
            'upd_time'              => '更新時間',
        ],
        // 动态表格统计数据
        'form_table_stats'                      => [
            'total_price'           => '訂單總額',
            'pay_price'             => '支付總額',
            'buy_number_count'      => '商品總數',
            'refund_price'          => '退款金額',
            'returned_quantity'     => '退貨數量',
        ],
        // 快递表格
        'form_table_express'                    => [
            'name'    => '快遞公司',
            'number'  => '快遞單號',
            'note'    => '快遞備註',
            'time'    => '發貨時間',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => '退款原因數據為空',
        ],
        // 基础
        'base_nav_title'                        => '訂單售後',
        'detail_base_nav_title'                 => '訂單售後詳情',
        'view_orderaftersale_enter_name'        => '查看售後訂單',
        'orderaftersale_apply_name'             => '申請售後',
        'operate_delivery_name'                 => '立即退貨',
        'goods_list_thead_base'                 => '商品資訊',
        'goods_list_thead_price'                => '單價',
        'goods_base_price_title'                => '商品總價：',
        'goods_list_thead_number'               => '數量',
        'goods_list_thead_total'                => '合計',
        'goods_base_price_title'                => '商品總價：',
        'goods_base_increase_price_title'       => '新增金額：',
        'goods_base_preferential_price_title'   => '優惠金額：',
        'goods_base_refund_price_title'         => '退款金額：',
        'goods_base_pay_price_title'            => '支付金額：',
        'goods_base_total_price_title'          => '訂單總價：',
        'base_apply_title'                      => '申請資訊',
        'goods_after_status_title'              => '售後狀態',
        'withdraw_title'                        => '取消申請',
        're_apply_title'                        => '再次申請',
        'select_service_type_title'             => '選擇服務類型',
        'goods_pay_price_title'                 => '商品實付金額：',
        'aftersale_service_title'               => '售後客服',
        'problems_contact_service_tips'         => '遇到問題請聯系客服',
        'base_apply_type_title'                 => '退款類型：',
        'base_apply_status_title'               => '當前狀態：',
        'base_apply_reason_title'               => '申請原因：',
        'base_apply_number_title'               => '退貨數量：',
        'base_apply_price_title'                => '退款金額：',
        'base_apply_msg_title'                  => '退款說明：',
        'base_apply_refundment_title'           => '退款管道：',
        'base_apply_refuse_reason_title'        => '拒絕原因：',
        'base_apply_apply_time_title'           => '申請時間：',
        'base_apply_confirm_time_title'         => '確認時間：',
        'base_apply_delivery_time_title'        => '退貨時間：',
        'base_apply_audit_time_title'           => '稽核時間：',
        'base_apply_cancel_time_title'          => '取消時間：',
        'base_apply_add_time_title'             => '添加時間：',
        'base_apply_upd_time_title'             => '更新時間：',
        'base_item_express_title'               => '快遞資訊',
        'base_item_express_name'                => '快遞：',
        'base_item_express_number'              => '單號：',
        'base_item_express_time'                => '時間：',
        'base_item_voucher_title'               => '憑證',
        // 表单
        'form_delivery_title'                   => '退貨操作',
        'form_delivery_address_name'            => '退貨地址',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基礎資訊',
            'goods_placeholder'     => '請輸入訂單號/商品名稱/型號',
            'status'                => '狀態',
            'type'                  => '申請類型',
            'reason'                => '原因',
            'price'                 => '退款金額',
            'number'                => '退貨數量',
            'msg'                   => '退款說明',
            'refundment'            => '退款類型',
            'express_name'          => '快遞公司',
            'express_number'        => '快遞單號',
            'apply_time'            => '申請時間',
            'confirm_time'          => '確認時間',
            'delivery_time'         => '退貨時間',
            'audit_time'            => '稽核時間',
            'add_time'              => '創建時間',
            'upd_time'              => '更新時間',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'base_nav_title'                        => '用戶中心',
        'forget_password_base_nav_title'        => '密碼找回',
        'user_register_base_nav_title'          => '用戶註冊',
        'user_login_base_nav_title'             => '用戶登錄',
        'password_reset_illegal_error_tips'     => '已經登入了，如要重置密碼，請先退出當前帳戶',
        'register_illegal_error_tips'           => '已經登入了，如要注册新帳戶，請先退出當前帳戶',
        'login_illegal_error_tips'              => '已經登入了，請勿重複登入',
        // 页面
        // 登录
        'login_nav_title'                       => '歡迎登錄',
        'login_top_register_tips'               => '還沒有帳號？',
        'login_close_tips'                      => '暫時關閉了登入',
        'login_type_username_title'             => '帳號密碼',
        'login_type_mobile_title'               => '手機驗證碼',
        'login_type_email_title'                => '郵箱驗證碼',
        'login_ahora_login_title'               => '立即登入',
        
        // 注册
        'register_nav_title'                    => '歡迎註冊',
        'register_top_login_tips'               => '我已經注册，去',
        'register_close_tips'                   => '暫時關閉了注册',
        'register_type_username_title'          => '帳號注册',
        'register_type_mobile_title'            => '手機註冊',
        'register_type_email_title'             => '郵箱註冊',
        'register_ahora_register_title'         => '立即注册',
        // 忘记密码
        'forget_password_nav_title'             => '找回密碼',
        'forget_password_reset_title'           => '重置密碼',
        'forget_password_top_login_tips'        => '已有帳號？',
        // 表单
        'form_item_agreement'                   => '閱讀並同意',
        'form_item_agreement_message'           => '請勾選同意協定',
        'form_item_service'                     => '《服務協議》',
        'form_item_privacy'                     => '《隱私政策》',
        'form_item_username'                    => '用戶名',
        'form_item_username_message'            => '請使用字母、數位、底線2~18個字',
        'form_item_password'                    => '登入密碼',
        'form_item_password_placeholder'        => '請輸入登入密碼',
        'form_item_password_message'            => '密碼格式6~18個字之間',
        'form_item_mobile'                      => '手機號碼',
        'form_item_mobile_placeholder'          => '請輸入手機號碼',
        'form_item_mobile_message'              => '手機號碼格式錯誤',
        'form_item_email'                       => '電子郵箱',
        'form_item_email_placeholder'           => '請輸入電子郵箱',
        'form_item_email_message'               => '電子郵箱格式錯誤',
        'form_item_account'                     => '登入帳號',
        'form_item_account_placeholder'         => '請輸入用戶名/手機/郵箱',
        'form_item_account_message'             => '請輸入登入帳號',
        'form_item_mobile_email'                => '手機/郵箱',
        'form_item_mobile_email_message'        => '請輸入有效的手機/郵箱格式',
        // 个人中心
        'base_avatar_title'                     => '修改頭像',
        'base_personal_title'                   => '修改資料',
        'base_address_title'                    => '我的地址',
        'base_message_title'                    => '消息',
        'order_nav_title'                       => '我的訂單',
        'order_nav_angle_title'                 => '查看全部訂單',
        'various_transaction_title'             => '交易提醒',
        'various_transaction_tips'              => '交易提醒可幫助您瞭解訂單狀態和物流情况',
        'various_cart_title'                    => '購物車',
        'various_cart_empty_title'              => '您的購物車還是空的',
        'various_cart_tips'                     => '將想買的商品放進購物車，一起結算更輕鬆',
        'various_favor_title'                   => '商品收藏',
        'various_favor_empty_title'             => '您還沒有收藏商品',
        'various_favor_tips'                    => '收藏的商品將顯示最新的促銷活動和降價情况',
        'various_browse_title'                  => '我的足迹',
        'various_browse_empty_title'            => '您的商品瀏覽記錄為空',
        'various_browse_tips'                   => '趕緊去商城看看促銷活動吧',
    ],

    // 用户地址
    'useraddress'       => [
        'base_nav_title'                        => '我的地址',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'base_nav_title'                        => '我的足迹',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '商品資訊',
            'goods_placeholder'     => '請輸入商品名稱/簡述/SEO資訊',
            'price'                 => '銷售價格',
            'original_price'        => '原價',
            'add_time'              => '創建時間',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'base_nav_title'                        => '商品收藏',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '商品資訊',
            'goods_placeholder'     => '請輸入商品名稱/簡述/SEO資訊',
            'price'                 => '銷售價格',
            'original_price'        => '原價',
            'add_time'              => '創建時間',
        ],
    ],

    // 用户商品评论
    'usergoodscomments' => [
        'base_nav_title'                        => '商品評論',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基礎資訊',
            'goods_placeholder'  => '請輸入商品名稱/型號',
            'business_type'      => '業務類型',
            'content'            => '評論內容',
            'images'             => '評論圖片',
            'rating'             => '評分',
            'reply'              => '回復內容',
            'is_show'            => '是否顯示',
            'is_anonymous'       => '是否匿名',
            'is_reply'           => '是否回復',
            'reply_time_time'    => '回復時間',
            'add_time_time'      => '創建時間',
            'upd_time_time'      => '更新時間',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'base_nav_title'                        => '我的積分',
        // 页面
        'base_normal_title'                     => '正常可用',
        'base_normal_tips'                      => '可以正常使用的積分',
        'base_locking_title'                    => '當前鎖定',
        'base_locking_tips'                     => '一般積分交易中，交易並未完成、鎖定相應的積分',
        'base_integral_unit'                    => '積分',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => '操作類型',
            'operation_integral'    => '操作積分',
            'original_integral'     => '原始積分',
            'new_integral'          => '最新積分',
            'msg'                   => '描述',
            'add_time_time'         => '時間',
        ],
    ],

    // 个人资料
    'personal'          => [
        'base_nav_title'                        => '個人資料',
        'edit_base_nav_title'                   => '個人資料編輯',
        'form_item_nickname'                    => '昵稱',
        'form_item_nickname_message'            => '昵稱2~16個字之間',
        'form_item_birthday'                    => '生日',
        'form_item_birthday_message'            => '生日格式有誤',
        'form_item_province'                    => '所在省',
        'form_item_province_message'            => '所在省最多30個字',
        'form_item_city'                        => '所在市',
        'form_item_city_message'                => '所在市最多30個字',
        'form_item_county'                      => '所在區/縣',
        'form_item_county_message'              => '所在區/縣最多30個字',
        'form_item_address'                     => '詳細地址',
        'form_item_address_message'             => '詳細地址2~30個字',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'base_nav_title'                        => '我的消息',
        // 动态表格
        'form_table'                => [
            'type'                  => '消息類型',
            'business_type'         => '業務類型',
            'title'                 => '標題',
            'detail'                => '詳情',
            'is_read'               => '狀態',
            'add_time_time'         => '時間',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'base_nav_title'                        => '安全設置',
        'password_update_base_nav_title'        => '登入密碼修改-安全設置',
        'mobile_update_base_nav_title'          => '手機號碼修改-安全設置',
        'email_update_base_nav_title'           => '電子郵箱修改-安全設置',
        'logout_base_nav_title'                 => '帳號註銷-安全設置',
        'original_account_check_error_tips'     => '原帳號校驗失敗',
        // 页面
        'logout_title'                          => '帳號註銷',
        'logout_confirm_title'                  => '確認註銷',
        'logout_confirm_tips'                   => '帳號註銷後不可恢復、確定繼續嗎？',
        'email_title'                           => '原電子郵箱校驗',
        'email_new_title'                       => '新電子郵箱校驗',
        'mobile_title'                          => '原手機號碼校驗',
        'mobile_new_title'                      => '新手機號碼校驗',
        'login_password_title'                  => '登入密碼修改',
    ],

    // 上传组件
    'ueditor' => [
        'base_nav_title'                        => '掃碼上傳'
    ],
];
?>