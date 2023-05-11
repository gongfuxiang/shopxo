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
 * 模块语言包-越南语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Trang chủ Mall',
        'back_to_the_home_title'                => 'Quay lại trang đầu',
        'all_category_text'                     => 'Tất cả danh mục',
        'login_title'                           => 'Đăng nhập',
        'register_title'                        => 'Đăng ký',
        'logout_title'                          => 'Thoát',
        'cancel_text'                           => 'Hủy bỏ',
        'save_text'                             => 'Lưu',
        'more_text'                             => 'Thêm',
        'processing_in_text'                    => 'Đang xử lý......',
        'upload_in_text'                        => 'Đang tải lên......',
        'navigation_main_quick_name'            => 'Hộp kho báu',
        'no_relevant_data_tips'                 => 'Không có dữ liệu liên quan',
        'avatar_upload_title'                   => 'Avatar tải lên',
        'choice_images_text'                    => 'Chọn ảnh',
        'choice_images_error_tips'              => 'Vui lòng chọn ảnh cần upload',
        'confirm_upload_title'                  => 'Xác nhận tải lên',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Chào mừng bạn',
        'header_top_nav_left_login_first'       => 'Xin chào',
        'header_top_nav_left_login_last'        => 'Chào mừng đến với.',
        // 搜索
        'search_input_placeholder'              => 'Thật ra tìm kiếm rất đơn giản ^^!',
        'search_button_text'                    => 'Tìm kiếm',
        // 用户
        'avatar_upload_tips'                    => [
            'Vui lòng phóng to thu nhỏ và di chuyển hộp chọn trong khu vực làm việc, chọn phạm vi bạn muốn cắt, cố định tỷ lệ rộng và cao;',
            'Hiệu ứng sau khi cắt được hiển thị trong bản vẽ xem trước bên phải, xác nhận có hiệu lực sau khi gửi;',
        ],
        'close_user_register_tips'              => 'Tạm thời đóng đăng ký người dùng',
        'close_user_login_tips'                 => 'Tạm thời tắt đăng nhập người dùng',
        // 底部
        'footer_icp_filing_text'                => 'Hồ sơ ICP',
        'footer_public_security_filing_text'    => 'Công an lập hồ sơ',
        'footer_business_license_text'          => 'Giấy phép kinh doanh điện tử',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Chào mừng đến với',
        'banner_right_article_title'            => 'Thông tin tiêu đề',
        'design_browser_seo_title'              => 'Thiết kế nhà',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Không có dữ liệu bình luận',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Sản phẩm không tồn tại hoặc bị xóa',
        'panel_can_choice_spec_name'            => 'Đặc điểm kỹ thuật tùy chọn',
        'recommend_goods_title'                 => 'Nhìn và nhìn',
        'dynamic_scoring_name'                  => 'Đánh giá động',
        'no_scoring_data_tips'                  => 'Không có dữ liệu đánh giá',
        'no_comments_data_tips'                 => 'Không có dữ liệu đánh giá',
        'comments_first_name'                   => 'Bình luận on',
        'admin_reply_name'                      => 'Admin trả lời:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Tìm kiếm sản phẩm',
        'filter_out_first_text'                 => 'Lọc ra',
        'filter_out_last_data_text'             => 'Dữ liệu thanh',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Phân loại hàng hóa',
        'no_category_data_tips'                 => 'Không có dữ liệu phân loại',
        'no_sub_category_data_tips'             => 'Không có dữ liệu phân loại con',
        'view_category_sub_goods_name'          => 'Xem các sản phẩm được phân loại',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Vui lòng chọn sản phẩm',
        ],
        // 基础
        'browser_seo_title'                     => 'Giỏ hàng',
        'goods_list_thead_base'                 => 'Thông tin sản phẩm',
        'goods_list_thead_price'                => 'Đơn giá',
        'goods_list_thead_number'               => 'Số lượng',
        'goods_list_thead_total'                => 'Số lượng',
        'goods_item_total_name'                 => 'Tổng cộng',
        'summary_selected_goods_name'           => 'Sản phẩm đã chọn',
        'summary_selected_goods_unit'           => 'Phần',
        'summary_nav_goods_total'               => 'Tổng cộng:',
        'summary_nav_button_name'               => 'Giải quyết',
        'no_cart_data_tips'                     => 'Giỏ hàng của bạn vẫn còn trống, bạn có thể',
        'no_cart_data_my_favor_name'            => 'Mục yêu thích của tôi',
        'no_cart_data_my_order_name'            => 'Lệnh của tôi',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Vui lòng chọn địa chỉ',
            'payment_choice_tips'               => 'Vui lòng chọn thanh toán',
        ],
        // 基础
        'browser_seo_title'                     => 'Xác nhận đơn hàng',
        'exhibition_not_allow_submit_tips'      => 'Loại hiển thị Không cho phép gửi đơn đặt hàng',
        'buy_item_order_title'                  => 'Thông tin đặt hàng',
        'buy_item_payment_title'                => 'Chọn thanh toán',
        'confirm_delivery_address_name'         => 'Xác nhận địa chỉ nhận hàng',
        'use_new_address_name'                  => 'Sử dụng địa chỉ mới',
        'no_delivery_address_tips'              => 'Không có địa chỉ nhận hàng',
        'confirm_extraction_address_name'       => 'Xác nhận địa chỉ Self-Extract',
        'choice_take_address_name'              => 'Chọn địa chỉ nhận hàng',
        'no_take_address_tips'                  => 'Vui lòng liên hệ với quản trị viên để cấu hình địa chỉ Self-Extract',
        'no_address_tips'                       => 'Không có địa chỉ',
        'extraction_list_choice_title'          => 'Chọn điểm tự động',
        'goods_list_thead_base'                 => 'Thông tin sản phẩm',
        'goods_list_thead_price'                => 'Đơn giá',
        'goods_list_thead_number'               => 'Số lượng',
        'goods_list_thead_total'                => 'Số lượng',
        'goods_item_total_name'                 => 'Tổng cộng',
        'not_goods_tips'                        => 'Không có sản phẩm',
        'not_payment_tips'                      => 'Không có phương thức thanh toán',
        'user_message_title'                    => 'Tin nhắn người mua',
        'user_message_placeholder'              => 'Chọn, đề xuất điền và hướng dẫn mà người bán đồng ý',
        'summary_title'                         => 'Thanh toán thực:',
        'summary_contact_name'                  => 'Liên hệ:',
        'summary_address'                       => 'Địa chỉ:',
        'summary_submit_order_name'             => 'Gửi đơn đặt hàng',
        'payment_layer_title'                   => 'Thanh toán đang nhảy, đừng đóng trang',
        'payment_layer_content'                 => 'Thanh toán không thành công hoặc không phản hồi trong một thời gian dài',
        'payment_layer_order_button_text'       => 'Lệnh của tôi',
        'payment_layer_tips'                    => 'Sau đó có thể khởi động lại thanh toán.',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Tất cả bài viết',
        'article_no_data_tips'                  => 'Bài viết không tồn tại hoặc đã bị xóa',
        'article_id_params_tips'                => 'ID bài viết bị lỗi',
        'release_time'                          => 'Thời gian phát hành:',
        'view_number'                           => 'Số lần xem:',
        'prev_article'                          => 'Trước:',
        'next_article'                          => 'Tiếp theo:',
        'article_category_name'                 => 'Phân loại bài viết',
        'article_nav_text'                      => 'Điều hướng thanh bên',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'Trang không tồn tại hoặc đã bị xóa',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'Trang không tồn tại hoặc đã bị xóa',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Lệnh id bị lỗi',
            'payment_choice_tips'               => 'Vui lòng chọn phương thức thanh toán',
            'rating_string'                     => 'Rất xấu, xấu, trung bình, tốt, rất tốt',
            'not_choice_data_tips'              => 'Vui lòng chọn dữ liệu trước',
            'pay_url_empty_tips'                => 'Địa chỉ URL bị lỗi',
        ],
        // 基础
        'browser_seo_title'                     => 'Lệnh của tôi',
        'detail_browser_seo_title'              => 'Chi tiết đặt hàng',
        'comments_browser_seo_title'            => 'Nhận xét đơn hàng',
        'batch_payment_name'                    => 'Thanh toán hàng loạt',
        'comments_goods_list_thead_base'        => 'Thông tin sản phẩm',
        'comments_goods_list_thead_price'       => 'Đơn giá',
        'comments_goods_list_thead_content'     => 'Nội dung bình luận',
        'form_you_have_commented_tips'          => 'Bạn đã bình luận',
        'form_payment_title'                    => 'Thanh toán',
        'form_payment_no_data_tips'             => 'Không có phương thức thanh toán',
        'order_base_title'                      => 'Thông tin đặt hàng',
        'order_base_warehouse_title'            => 'Dịch vụ vận chuyển:',
        'order_base_model_title'                => 'Chế độ đặt hàng:',
        'order_base_order_no_title'             => 'Số thứ tự:',
        'order_base_status_title'               => 'Trạng thái đặt hàng:',
        'order_base_pay_status_title'           => 'Trạng thái thanh toán:',
        'order_base_payment_title'              => 'Phương thức thanh toán:',
        'order_base_total_price_title'          => 'Tổng giá đặt hàng:',
        'order_base_buy_number_title'           => 'Số lượng mua:',
        'order_base_returned_quantity_title'    => 'Số lượng hoàn trả:',
        'order_base_user_note_title'            => 'Tin nhắn người dùng:',
        'order_base_add_time_title'             => 'Thời gian đặt hàng:',
        'order_base_confirm_time_title'         => 'Thời gian xác nhận:',
        'order_base_pay_time_title'             => 'Thời gian thanh toán:',
        'order_base_delivery_time_title'        => 'Thời gian vận chuyển:',
        'order_base_collect_time_title'         => 'Thời gian nhận hàng:',
        'order_base_user_comments_time_title'   => 'Thời gian bình luận:',
        'order_base_cancel_time_title'          => 'Thời gian hủy:',
        'order_base_express_title'              => 'Công ty chuyển phát nhanh:',
        'order_base_express_website_title'      => 'Website chuyển phát nhanh:',
        'order_base_express_number_title'       => 'Số đơn chuyển phát nhanh:',
        'order_base_price_title'                => 'Tổng giá hàng:',
        'order_base_increase_price_title'       => 'Số tiền tăng:',
        'order_base_preferential_price_title'   => 'Số tiền ưu đãi:',
        'order_base_refund_price_title'         => 'Số tiền hoàn lại:',
        'order_base_pay_price_title'            => 'Số tiền thanh toán:',
        'order_base_take_code_title'            => 'Lấy mã:',
        'order_base_take_code_no_exist_tips'    => 'Mã nhận hàng không tồn tại, vui lòng liên hệ với quản trị viên',
        'order_under_line_tips'                 => 'Hiện tại, phương thức thanh toán ngoại tuyến [{:payment}] có thể có hiệu lực sau khi được quản trị viên xác nhận, nếu cần thanh toán khác có thể chuyển đổi thanh toán để khởi động lại thanh toán.',
        'order_delivery_tips'                   => 'Hàng hóa đang được đóng gói, xuất kho......',
        'order_goods_no_data_tips'              => 'Không có dữ liệu hàng hóa đặt hàng',
        'order_status_operate_first_tips'       => 'Bạn có thể',
        'goods_list_thead_base'                 => 'Thông tin sản phẩm',
        'goods_list_thead_price'                => 'Đơn giá',
        'goods_list_thead_number'               => 'Số lượng',
        'goods_list_thead_total'                => 'Số lượng',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Thông tin cơ bản',
            'goods_placeholder'     => 'Vui lòng nhập số đặt hàng/tên sản phẩm/mô hình',
            'status'                => 'Trạng thái đặt hàng',
            'pay_status'            => 'Trạng thái thanh toán',
            'total_price'           => 'Tổng giá trị',
            'pay_price'             => 'Kim ngạch thanh toán (Nhân dân tệ)',
            'price'                 => 'Đơn giá (Nhân dân tệ)',
            'order_model'           => 'Chế độ đặt hàng',
            'client_type'           => 'Đặt nền tảng',
            'address'               => 'Thông tin địa chỉ',
            'take'                  => 'Nhận thông tin',
            'refund_price'          => 'Số tiền hoàn lại (NDT)',
            'returned_quantity'     => 'Số lượng hoàn trả',
            'buy_number_count'      => 'Tổng số mua',
            'increase_price'        => 'Tăng kim ngạch',
            'preferential_price'    => 'Kim ngạch ưu đãi (đồng)',
            'payment_name'          => 'Phương thức thanh toán',
            'user_note'             => 'Tin nhắn',
            'extension'             => 'Thông tin mở rộng',
            'express_name'          => 'Công ty chuyển phát nhanh',
            'express_number'        => 'Số đơn chuyển phát nhanh',
            'is_comments'           => 'Bình luận',
            'confirm_time'          => 'Thời gian xác nhận',
            'pay_time'              => 'Thời gian thanh toán',
            'delivery_time'         => 'Thời gian vận chuyển',
            'collect_time'          => 'Thời gian hoàn thành',
            'cancel_time'           => 'Thời gian hủy',
            'close_time'            => 'Thời gian đóng cửa',
            'add_time'              => 'Thời gian tạo',
            'upd_time'              => 'Thời gian cập nhật',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Tổng đơn hàng',
            'pay_price'             => 'Tổng số tiền thanh toán',
            'buy_number_count'      => 'Tổng số sản phẩm',
            'refund_price'          => 'Hoàn tiền',
            'returned_quantity'     => 'Trả lại',
            'price_unit'            => 'Nguyên',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Lý do hoàn tiền Dữ liệu trống',
        ],
        // 基础
        'browser_seo_title'                     => 'Sau khi đặt hàng',
        'detail_browser_seo_title'              => 'Chi tiết sau khi đặt hàng',
        'view_orderaftersale_enter_name'        => 'Xem đơn đặt hàng sau bán hàng',
        'operate_delivery_name'                 => 'Trả lại ngay',
        'goods_list_thead_base'                 => 'Thông tin sản phẩm',
        'goods_list_thead_price'                => 'Đơn giá',
        'goods_base_price_title'                => 'Tổng giá hàng:',
        'goods_base_increase_price_title'       => 'Số tiền tăng:',
        'goods_base_preferential_price_title'   => 'Số tiền ưu đãi:',
        'goods_base_refund_price_title'         => 'Số tiền hoàn lại:',
        'goods_base_pay_price_title'            => 'Số tiền thanh toán:',
        'goods_base_total_price_title'          => 'Tổng giá đặt hàng:',
        'base_apply_title'                      => 'Thông tin ứng tuyển',
        'base_apply_type_title'                 => 'Loại hoàn tiền:',
        'base_apply_status_title'               => 'Trạng thái hiện tại:',
        'base_apply_reason_title'               => 'Lý do ứng dụng:',
        'base_apply_number_title'               => 'Số lượng hoàn trả:',
        'base_apply_price_title'                => 'Số tiền hoàn lại:',
        'base_apply_msg_title'                  => 'Hướng dẫn hoàn tiền:',
        'base_apply_refundment_title'           => 'Phương thức hoàn tiền:',
        'base_apply_refuse_reason_title'        => 'Lý do từ chối:',
        'base_apply_apply_time_title'           => 'Thời gian áp dụng:',
        'base_apply_confirm_time_title'         => 'Thời gian xác nhận:',
        'base_apply_delivery_time_title'        => 'Thời gian hoàn trả:',
        'base_apply_audit_time_title'           => 'Thời gian xét duyệt:',
        'base_apply_cancel_time_title'          => 'Thời gian hủy:',
        'base_apply_add_time_title'             => 'Thêm thời gian:',
        'base_apply_upd_time_title'             => 'Cập nhật thời gian:',
        'base_item_express_title'               => 'Thông tin chuyển phát nhanh',
        'base_item_express_name'                => 'Chuyển phát nhanh:',
        'base_item_express_number'              => 'Số đơn:',
        'base_item_express_time'                => 'Thời gian:',
        'base_item_voucher_title'               => 'Chứng từ',
        // 表单
        'form_delivery_title'                   => 'Hoạt động hoàn trả',
        'form_delivery_address_name'            => 'Địa chỉ trả lại',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Thông tin cơ bản',
            'goods_placeholder'     => 'Vui lòng nhập số đặt hàng/tên sản phẩm/mô hình',
            'status'                => 'Trạng thái',
            'type'                  => 'Loại ứng dụng',
            'reason'                => 'Nguyên nhân',
            'price'                 => 'Số tiền hoàn lại (NDT)',
            'number'                => 'Số lượng hoàn trả',
            'msg'                   => 'Hướng dẫn hoàn tiền',
            'refundment'            => 'Loại hoàn tiền',
            'express_name'          => 'Công ty chuyển phát nhanh',
            'express_number'        => 'Số đơn chuyển phát nhanh',
            'apply_time'            => 'Thời gian nộp đơn',
            'confirm_time'          => 'Thời gian xác nhận',
            'delivery_time'         => 'Thời gian hoàn trả',
            'audit_time'            => 'Thời gian xét duyệt',
            'add_time'              => 'Thời gian tạo',
            'upd_time'              => 'Thời gian cập nhật',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Trung tâm người dùng',
        'forget_password_browser_seo_title'     => 'Tìm lại mật khẩu',
        'user_register_browser_seo_title'       => 'Đăng ký thành viên',
        'user_login_browser_seo_title'          => 'Đăng nhập người dùng',
        'password_reset_illegal_error_tips'     => 'Đã đăng nhập, để đặt lại mật khẩu, vui lòng thoát khỏi tài khoản hiện tại',
        'register_illegal_error_tips'           => 'Đã đăng nhập, để đăng ký tài khoản mới, vui lòng thoát khỏi tài khoản hiện tại',
        'login_illegal_error_tips'              => 'Đã đăng nhập, đừng đăng nhập lại',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Chưa có tài khoản?',
        'login_close_tips'                      => 'Tạm thời đóng đăng nhập',
        'login_type_username_title'             => 'Mật khẩu tài khoản',
        'login_type_mobile_title'               => 'Điện thoại Captcha',
        'login_type_email_title'                => 'Mã xác minh hộp thư',
        'login_retrieve_password_title'         => 'Lấy lại mật khẩu',
        // 注册
        'register_top_login_tips'               => 'Tôi đã đăng ký, bây giờ',
        'register_close_tips'                   => 'Tạm thời đóng đăng ký',
        'register_type_username_title'          => 'Đăng ký tài khoản',
        'register_type_mobile_title'            => 'Đăng ký di động',
        'register_type_email_title'             => 'Đăng ký hộp thư',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Đã có tài khoản?',
        // 表单
        'form_item_agreement'                   => 'Đọc và đồng ý',
        'form_item_agreement_message'           => 'Vui lòng chọn Agree Agreement',
        'form_item_service'                     => 'Thỏa thuận dịch vụ',
        'form_item_privacy'                     => 'Chính sách bảo mật',
        'form_item_username'                    => 'Tên người dùng',
        'form_item_username_message'            => 'Vui lòng sử dụng chữ cái, số, gạch dưới 2~18 ký tự',
        'form_item_password'                    => 'Mật khẩu đăng nhập',
        'form_item_password_placeholder'        => 'Vui lòng nhập mật khẩu đăng nhập',
        'form_item_password_message'            => 'Định dạng mật khẩu giữa 6~18 ký tự',
        'form_item_mobile'                      => 'Số điện thoại',
        'form_item_mobile_placeholder'          => 'Vui lòng nhập số điện thoại di động',
        'form_item_mobile_message'              => 'Định dạng số điện thoại di động sai',
        'form_item_email'                       => 'Thư điện tử',
        'form_item_email_placeholder'           => 'Vui lòng nhập email',
        'form_item_email_message'               => 'Lỗi định dạng email',
        'form_item_account'                     => 'Đăng nhập tài khoản',
        'form_item_account_placeholder'         => 'Vui lòng nhập tên người dùng/điện thoại/hộp thư',
        'form_item_account_message'             => 'Vui lòng nhập tài khoản đăng nhập',
        'form_item_mobile_email'                => 'Điện thoại/Hộp thư',
        'form_item_mobile_email_message'        => 'Vui lòng nhập định dạng điện thoại/hộp thư hợp lệ',
        // 个人中心
        'base_avatar_title'                     => 'Thay đổi avatar',
        'base_personal_title'                   => 'Thay đổi dữ liệu',
        'base_address_title'                    => 'Địa chỉ của tôi',
        'base_message_title'                    => 'Thông điệp',
        'order_nav_title'                       => 'Lệnh của tôi',
        'order_nav_angle_title'                 => 'Xem tất cả đơn đặt hàng',
        'various_transaction_title'             => 'Nhắc nhở giao dịch',
        'various_transaction_tips'              => 'Cảnh báo giao dịch giúp bạn hiểu tình trạng đơn đặt hàng và hậu cần',
        'various_cart_title'                    => 'Giỏ hàng',
        'various_cart_empty_title'              => 'Giỏ hàng của bạn vẫn trống',
        'various_cart_tips'                     => 'Đặt hàng muốn mua vào giỏ hàng, thanh toán cùng nhau dễ dàng hơn',
        'various_favor_title'                   => 'Bộ sưu tập hàng hóa',
        'various_favor_empty_title'             => 'Bạn chưa có bộ sưu tập sản phẩm',
        'various_favor_tips'                    => 'Các mặt hàng được sưu tầm sẽ hiển thị các chương trình khuyến mãi và giảm giá mới nhất',
        'various_browse_title'                  => 'Dấu chân của tôi',
        'various_browse_empty_title'            => 'Hồ sơ duyệt sản phẩm của bạn trống',
        'various_browse_tips'                   => 'Mau đến trung tâm thương mại xem hoạt động khuyến mãi đi.',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Địa chỉ của tôi',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Dấu chân của tôi',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Thông tin sản phẩm',
            'goods_placeholder'     => 'Vui lòng nhập tên sản phẩm/tóm tắt/thông tin SEO',
            'price'                 => 'Giá bán (NDT)',
            'original_price'        => 'Giá gốc',
            'add_time'              => 'Thời gian tạo',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'Bộ sưu tập hàng hóa',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Thông tin sản phẩm',
            'goods_placeholder'     => 'Vui lòng nhập tên sản phẩm/tóm tắt/thông tin SEO',
            'price'                 => 'Giá bán (NDT)',
            'original_price'        => 'Giá gốc',
            'add_time'              => 'Thời gian tạo',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'Điểm của tôi',
        // 页面
        'base_normal_title'                     => 'Bình thường có sẵn',
        'base_normal_tips'                      => 'Điểm có thể được sử dụng bình thường',
        'base_locking_title'                    => 'Khóa hiện tại',
        'base_locking_tips'                     => 'Trong giao dịch điểm chung, giao dịch không hoàn thành, khóa điểm tương ứng',
        'base_integral_unit'                    => 'Tích phân',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Loại hoạt động',
            'operation_integral'    => 'Điểm hoạt động',
            'original_integral'     => 'Tích phân gốc',
            'new_integral'          => 'Điểm mới nhất',
            'msg'                   => 'Mô tả',
            'add_time_time'         => 'Thời gian',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'Thông tin cá nhân',
        'edit_browser_seo_title'                => 'Chỉnh sửa hồ sơ',
        'form_item_nickname'                    => 'Biệt danh',
        'form_item_nickname_message'            => 'Biệt danh từ 2 đến 16 ký tự',
        'form_item_birthday'                    => 'Sinh nhật',
        'form_item_birthday_message'            => 'Sinh nhật bị lỗi',
        'form_item_province'                    => 'Tỉnh',
        'form_item_province_message'            => 'Tối đa 30 ký tự',
        'form_item_city'                        => 'Thành phố',
        'form_item_city_message'                => 'Thành phố tối đa 30 ký tự',
        'form_item_county'                      => 'Quận/County',
        'form_item_county_message'              => 'Tối đa 30 ký tự trong quận/quận của bạn',
        'form_item_address'                     => 'Chi tiết địa chỉ',
        'form_item_address_message'             => 'Địa chỉ chi tiết 2~30 ký tự',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Tin tức',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Kiểu tin nhắn',
            'business_type'         => 'Loại hình kinh doanh',
            'title'                 => 'Tiêu đề',
            'detail'                => 'Chi tiết',
            'is_read'               => 'Trạng thái',
            'add_time_time'         => 'Thời gian',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Hỏi&Đáp/Tin nhắn',
        // 表单
        'form_title'                            => 'Câu hỏi/Tin nhắn',
        'form_item_name'                        => 'Biệt danh',
        'form_item_name_message'                => 'Định dạng biệt danh 1~30 ký tự',
        'form_item_tel'                         => 'Điện thoại',
        'form_item_tel_message'                 => 'Vui lòng điền số điện thoại',
        'form_item_title'                       => 'Tiêu đề',
        'form_item_title_message'               => 'Định dạng tiêu đề Giữa 1~60 ký tự',
        'form_item_content'                     => 'Nội dung',
        'form_item_content_message'             => 'Định dạng nội dung Giữa 5~1000 ký tự',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'Liên hệ',
            'tel'                   => 'Số điện thoại',
            'content'               => 'Nội dung',
            'reply'                 => 'Nội dung trả lời',
            'reply_time_time'       => 'Thời gian trả lời',
            'add_time_time'         => 'Thời gian tạo',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'Cài đặt bảo mật',
        'password_update_browser_seo_title'     => 'Thay đổi mật khẩu đăng nhập - Cài đặt bảo mật',
        'mobile_update_browser_seo_title'       => 'Sửa đổi số điện thoại di động - Cài đặt bảo mật',
        'email_update_browser_seo_title'        => 'Sửa đổi email - Cài đặt bảo mật',
        'logout_browser_seo_title'              => 'Đăng xuất tài khoản - Cài đặt bảo mật',
        'original_account_check_error_tips'     => 'Kiểm tra tài khoản cũ thất bại',
        // 页面
        'logout_title'                          => 'Đăng xuất tài khoản',
        'logout_confirm_title'                  => 'Xác nhận đăng xuất',
        'logout_confirm_tips'                   => 'Sau khi hủy tài khoản không thể khôi phục, xác định tiếp tục sao?',
        'email_title'                           => 'Kiểm tra email gốc',
        'email_new_title'                       => 'Kiểm tra email mới',
        'mobile_title'                          => 'Kiểm tra số điện thoại di động gốc',
        'mobile_new_title'                      => 'Kiểm tra số điện thoại di động mới',
        'login_password_title'                  => 'Thay đổi mật khẩu đăng nhập',
    ],
];
?>