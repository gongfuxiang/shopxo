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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'Số lượng lệnh giao dịch',
            'order_trading_trend_name'          => 'Lệnh giao dịch',
            'goods_hot_name'                    => 'Hàng nóng',
            'goods_hot_tips'                    => 'Chỉ hiển thị 30 sản phẩm đầu tiên',
            'payment_name'                      => 'Phương thức thanh toán',
            'order_region_name'                 => 'Phân phối khu vực đặt hàng',
            'order_region_tips'                 => 'Chỉ hiển thị 30 dữ liệu',
            'upgrade_check_loading_tips'        => 'Đang nhận nội dung mới nhất, vui lòng chờ...',
            'upgrade_version_name'              => 'Cập nhật phiên bản:',
            'upgrade_date_name'                 => 'Cập nhật:',
        ],
        // 页面基础
        'base_update_button_title'              => 'Cập nhật ngay',
        'base_item_base_stats_title'            => 'Thống kê Mall',
        'base_item_base_stats_tips'             => 'Bộ lọc thời gian chỉ có giá trị cho tổng số',
        'base_item_user_title'                  => 'Tổng số người dùng',
        'base_item_order_number_title'          => 'Tổng số đơn đặt hàng',
        'base_item_order_complete_number_title' => 'Tổng số giao dịch',
        'base_item_order_complete_title'        => 'Tổng đơn hàng',
        'base_item_last_month_title'            => 'Tháng trước',
        'base_item_same_month_title'            => 'Tháng hiện tại',
        'base_item_yesterday_title'             => 'Hôm qua',
        'base_item_today_title'                 => 'Hôm nay',
        'base_item_order_profit_title'          => 'Số lượng lệnh giao dịch',
        'base_item_order_trading_title'         => 'Lệnh giao dịch',
        'base_item_order_tips'                  => 'Tất cả đơn đặt hàng',
        'base_item_hot_sales_goods_title'       => 'Hàng nóng',
        'base_item_hot_sales_goods_tips'        => 'Không có lệnh hủy đóng',
        'base_item_payment_type_title'          => 'Phương thức thanh toán',
        'base_item_map_whole_country_title'     => 'Phân phối khu vực đặt hàng',
        'base_item_map_whole_country_tips'      => 'Không bao gồm lệnh hủy đóng, kích thước mặc định (tỉnh)',
        'base_item_map_whole_country_province'  => 'Tỉnh',
        'base_item_map_whole_country_city'      => 'Thành phố',
        'base_item_map_whole_country_county'    => 'Quận/Huyện',
        'system_info_title'                     => 'Thông tin hệ thống',
        'system_ver_title'                      => 'Phiên bản phần mềm',
        'system_os_ver_title'                   => 'Hệ điều hành',
        'system_php_ver_title'                  => 'Phiên bản PHP',
        'system_mysql_ver_title'                => 'Phiên bản MySQL',
        'system_server_ver_title'               => 'Thông tin phía máy chủ',
        'system_host_title'                     => 'Tên miền hiện tại',
        'development_team_title'                => 'Đội ngũ phát triển',
        'development_team_website_title'        => 'Website công ty',
        'development_team_website_value'        => 'Thượng Hải Longzhige Công nghệ Công ty TNHH',
        'development_team_support_title'        => 'Hỗ trợ kỹ thuật',
        'development_team_support_value'        => 'Nhà cung cấp hệ thống thương mại điện tử cấp doanh nghiệp ShopXO',
        'development_team_ask_title'            => 'Trao đổi câu hỏi',
        'development_team_ask_value'            => 'ShopXO trao đổi câu hỏi',
        'development_team_agreement_title'      => 'Giao thức nguồn mở',
        'development_team_agreement_value'      => 'Xem các giao thức nguồn mở',
        'development_team_update_log_title'     => 'Cập nhật nhật ký',
        'development_team_update_log_value'     => 'Xem nhật ký cập nhật',
        'development_team_members_title'        => 'Thành viên R&D',
        'development_team_members_value'        => [
            ['name' => 'Tam ca', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'Người dùng',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'ID người dùng',
            'number_code'           => 'Mã thành viên',
            'system_type'           => 'Loại hệ thống',
            'platform'              => 'Nền tảng sở hữu',
            'avatar'                => 'Đầu',
            'username'              => 'Tên người dùng',
            'nickname'              => 'Biệt danh',
            'mobile'                => 'Điện thoại',
            'email'                 => 'Hộp thư',
            'gender_name'           => 'Giới tính',
            'status_name'           => 'Trạng thái',
            'province'              => 'Tỉnh',
            'city'                  => 'Thành phố',
            'county'                => 'Quận/County',
            'address'               => 'Chi tiết địa chỉ',
            'birthday'              => 'Sinh nhật',
            'integral'              => 'Điểm có sẵn',
            'locking_integral'      => 'Khóa điểm',
            'referrer'              => 'Mời người dùng',
            'referrer_placeholder'  => 'Vui lòng nhập tên người dùng mời/biệt danh/điện thoại/hộp thư',
            'add_time'              => 'Thời gian đăng ký',
            'upd_time'              => 'Thời gian cập nhật',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Địa chỉ người dùng',
        // 详情
        'detail_user_address_idcard_name'       => 'Tên',
        'detail_user_address_idcard_number'     => 'Số',
        'detail_user_address_idcard_pic'        => 'Hình ảnh',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Thông tin người dùng',
            'user_placeholder'  => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'alias'             => 'Tên khác',
            'name'              => 'Liên hệ',
            'tel'               => 'Số điện thoại',
            'province_name'     => 'Tỉnh trực thuộc',
            'city_name'         => 'Thành phố sở hữu',
            'county_name'       => 'Quận/Huyện',
            'address'           => 'Chi tiết địa chỉ',
            'position'          => 'Kinh độ và Vĩ độ',
            'idcard_info'       => 'Thông tin ID',
            'is_default'        => 'Mặc định',
            'add_time'          => 'Thời gian tạo',
            'upd_time'          => 'Thời gian cập nhật',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Lưu có hiệu lực sau khi xóa, xác nhận tiếp tục không?',
            'address_no_data'                   => 'Dữ liệu địa chỉ trống',
            'address_not_exist'                 => 'Địa chỉ không tồn tại',
            'address_logo_message'              => 'Vui lòng tải lên hình ảnh logo',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Cấu hình nền tảng', 'type' => 'base'],
            ['name' => 'Thiết lập trang web', 'type' => 'siteset'],
            ['name' => 'Loại trang web', 'type' => 'sitetype'],
            ['name' => 'Đăng ký thành viên', 'type' => 'register'],
            ['name' => 'Đăng nhập người dùng', 'type' => 'login'],
            ['name' => 'Tìm lại mật khẩu', 'type' => 'forgetpwd'],
            ['name' => 'Mã xác nhận', 'type' => 'verify'],
            ['name' => 'Sau khi đặt hàng', 'type' => 'orderaftersale'],
            ['name' => 'Phụ kiện', 'type' => 'attachment'],
            ['name' => 'Bộ nhớ tạm', 'type' => 'cache'],
            ['name' => 'Phần mở rộng', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'Trang chủ', 'type' => 'index'],
            ['name' => 'Hàng hóa', 'type' => 'goods'],
            ['name' => 'Tìm kiếm', 'type' => 'search'],
            ['name' => 'Đặt hàng', 'type' => 'order'],
            ['name' => 'Ưu đãi', 'type' => 'discount'],
            ['name' => 'Mở rộng', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Trạng thái trang web',
        'base_item_site_domain_title'           => 'Địa chỉ tên miền trang web',
        'base_item_site_filing_title'           => 'Thông tin nộp hồ sơ',
        'base_item_site_other_title'            => 'Khác',
        'base_item_session_cache_title'         => 'Cấu hình cache session',
        'base_item_data_cache_title'            => 'Cấu hình bộ nhớ tạm dữ liệu',
        'base_item_redis_cache_title'           => 'Cấu hình bộ nhớ tạm redis',
        'base_item_crontab_config_title'        => 'Cấu hình tập lệnh hẹn giờ',
        'base_item_quick_nav_title'             => 'Chuyển hướng nhanh',
        'base_item_user_base_title'             => 'Cơ sở người dùng',
        'base_item_user_address_title'          => 'Địa chỉ người dùng',
        'base_item_multilingual_title'          => 'Đa ngôn ngữ',
        'base_item_site_auto_mode_title'        => 'Chế độ tự động',
        'base_item_site_manual_mode_title'      => 'Chế độ thủ công',
        'base_item_default_payment_title'       => 'Phương thức thanh toán mặc định',
        'base_item_display_type_title'          => 'Loại hiển thị',
        'base_item_self_extraction_title'       => 'Điểm tự nâng',
        'base_item_fictitious_title'            => 'Bán hàng ảo',
        'choice_upload_logo_title'              => 'Chọn Logo',
        'add_goods_title'                       => 'Thêm sản phẩm',
        'add_self_extractio_address_title'      => 'Thêm địa chỉ',
        'site_domain_tips_list'                 => [
            '1. Tên miền trang web không được thiết lập sử dụng địa chỉ tên miền trang web hiện tại[ '.__MY_DOMAIN__.' ]',
            '2. Phụ kiện và địa chỉ tĩnh không được thiết lập sử dụng địa chỉ tên miền tĩnh trang web hiện tại[ '.__MY_PUBLIC_URL__.' ]',
            '3. Nếu phía máy chủ không phải là thư mục gốc của public, cấu hình ở đây [tên miền cdn tệp đính kèm, tên miền css/js tệp tĩnh cdn] cần thêm public sau, chẳng hạn như:'.__MY_PUBLIC_URL__.'public/',
            '4. Chạy dự án ở chế độ dòng lệnh, địa chỉ khu vực phải được cấu hình, nếu không một số địa chỉ trong dự án sẽ thiếu thông tin tên miền',
            'Đừng cấu hình lộn xộn, địa chỉ sai có thể khiến trang web không thể truy cập (cấu hình địa chỉ bắt đầu bằng http), nếu trang web của bạn được cấu hình https thì bắt đầu bằng https',
        ],
        'site_cache_tips_list'                  => [
            '1. Bộ nhớ cache tập tin được sử dụng mặc định, sử dụng Redis Cache PHP yêu cầu cài đặt phần mở rộng Redis trước',
            'Vui lòng đảm bảo tính ổn định của dịch vụ Redis (sau khi sử dụng bộ nhớ cache, dịch vụ không ổn định có thể khiến nền không thể đăng nhập)',
            'Nếu bạn gặp lỗi Redis Service Exception không thể đăng nhập vào nền quản lý, thay đổi tập tin cấu hình [session.php, cache.php] trong thư mục [config]',
        ],
        'goods_tips_list'                       => [
            '1. WEB cuối hiển thị mặc định 3 cấp, thấp nhất 1 cấp, cao nhất 3 cấp (nếu thiết lập là 0 cấp thì mặc định là 3 cấp)',
            '2. Màn hình hiển thị mặc định của điện thoại di động cấp 0 (chế độ danh sách hàng hóa), thấp nhất cấp 0, cao nhất cấp 3 (1~3 là chế độ hiển thị phân loại)',
            'Cấp độ khác nhau, phong cách trang phân loại phía trước cũng sẽ khác nhau',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Cấu hình tối đa bao nhiêu mặt hàng được hiển thị trên mỗi tầng',
            '2. Không nên sửa đổi số lượng quá lớn, có thể gây ra vùng trống bên trái của PC quá lớn',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'Ví dụ: Heat ->Sales ->Latest Desc',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. Có thể nhấp vào tiêu đề hàng hóa kéo và thả sắp xếp, hiển thị theo thứ tự',
            'Không nên thêm nhiều sản phẩm, sẽ gây ra một khu vực trống bên trái của PC quá lớn',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. Mặc định lấy [Tên người dùng, điện thoại di động, hộp thư] làm người dùng duy nhất',
            'B5-05=giá trị thông số Kd, (cài 2)',
        ],
        'extends_crontab_tips'                  => 'Đề nghị thêm địa chỉ kịch bản vào yêu cầu thời gian tác vụ Linux (sucs:0, fail:0 theo sau là số lượng thanh dữ liệu được xử lý, sucs thành công, fali không thành công)',
        'left_images_random_tips'               => 'Bạn có thể tải lên tối đa 3 ảnh bên trái, hiển thị một ảnh ngẫu nhiên mỗi lần',
        'background_color_tips'                 => 'Hình nền tùy chỉnh, màu xám cơ sở mặc định',
        'site_setup_layout_tips'                => 'Chế độ kéo cần tự vào trang thiết kế trang đầu tiên, hãy lưu cấu hình đã chọn trước',
        'site_setup_layout_button_name'         => 'Đi đến trang thiết kế>>',
        'site_setup_goods_category_tips'        => 'Để hiển thị nhiều tầng hơn, vui lòng đến trước/Quản lý hàng hóa ->Phân loại hàng hóa, Thiết lập phân loại cấp 1 Trang chủ Đề xuất',
        'site_setup_goods_category_no_data_tips'=> 'Thông số sản phẩm Thông tin sản phẩm Bình Luận(',
        'site_setup_order_default_payment_tips' => 'Có thể thiết lập phương thức thanh toán mặc định tương ứng với các nền tảng khác nhau, vui lòng cài đặt plugin thanh toán trong [Quản lý trang web ->Phương thức thanh toán] Kích hoạt và mở cho người dùng',
        'site_setup_choice_payment_message'     => 'Vui lòng chọn phương thức thanh toán mặc định {:name}',
        'sitetype_top_tips_list'                => [
            '1. Chuyển phát nhanh, quy trình thương mại điện tử thông thường, người dùng chọn địa chỉ nhận hàng để đặt hàng thanh toán ->Thương gia giao hàng ->Xác nhận nhận hàng ->Hoàn thành đơn đặt hàng',
            '2. Loại hiển thị, chỉ hiển thị sản phẩm, có thể bắt đầu tư vấn (không thể đặt hàng)',
            '3. Tự vận chuyển, chọn địa chỉ hàng hóa tự vận chuyển khi đặt hàng, người dùng đặt thanh toán ->Xác nhận nhận hàng ->Hoàn thành đơn đặt hàng',
            '4. Bán hàng ảo, quy trình thương mại điện tử thông thường, người dùng đặt hàng thanh toán ->Giao hàng tự động ->Xác nhận nhận hàng ->Hoàn thành đơn hàng',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'Khuyến nghị 300 * 300px',
        'form_take_address_alias'               => 'Tên khác',
        'form_take_address_alias_message'       => 'Định dạng bí danh Tối đa 16 ký tự',
        'form_take_address_name'                => 'Liên hệ',
        'form_take_address_name_message'        => 'Định dạng liên lạc giữa 2~16 ký tự',
        'form_take_address_tel'                 => 'Số điện thoại',
        'form_take_address_tel_message'         => 'Vui lòng điền số điện thoại liên hệ',
        'form_take_address_address'             => 'Chi tiết địa chỉ',
        'form_take_address_address_message'     => 'Định dạng địa chỉ chi tiết 1~80 ký tự',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Đăng nhập nền',
        'admin_login_info_bg_images_list_tips'  => [
            'Hình nền nằm trong thư mục [public/static/admin/default/images/login]',
            '2. Quy tắc đặt tên hình nền (1~50), chẳng hạn như 1.jpg',
        ],
        'map_type_tips'                         => 'Do tiêu chuẩn bản đồ của mỗi nhà không giống nhau, xin vui lòng không tùy ý chuyển đổi bản đồ, sẽ dẫn đến tình huống đánh dấu tọa độ bản đồ không chính xác.',
        'apply_map_baidu_name'                  => 'Mời bạn tham khảo phần mềm mở Baidu Maps',
        'apply_map_amap_name'                   => 'Vui lòng đăng ký Nền tảng Mở Bản đồ Gauder',
        'apply_map_tencent_name'                => 'Đăng ký nền tảng mở Tencent Maps',
        'apply_map_tianditu_name'               => 'Vui lòng đăng ký nền tảng mở bản đồ',
        'cookie_domain_list_tips'               => [
            '1. Mặc định trống, thì chỉ hợp lệ cho tên miền truy cập hiện tại',
            'Nếu bạn cần chia sẻ cookie với tên miền cấp hai, hãy điền vào tên miền cấp cao nhất, ví dụ: baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'Thương hiệu',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'Tên',
            'describe'             => 'Mô tả',
            'logo'                 => 'LOGO',
            'url'                  => 'Địa chỉ website',
            'brand_category_text'  => 'Phân loại thương hiệu',
            'is_enable'            => 'Bật hay không',
            'sort'                 => 'Sắp xếp',
            'add_time'             => 'Thời gian tạo',
            'upd_time'             => 'Thời gian cập nhật',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Phân loại thương hiệu',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Bài viết',
        'detail_content_title'                  => 'Nội dung chi tiết',
        'detail_images_title'                   => 'Hình ảnh chi tiết',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'Tiêu đề',
            'jump_url'               => 'Nhảy đến địa chỉ URL',
            'article_category_name'  => 'Phân loại',
            'is_enable'              => 'Bật hay không',
            'is_home_recommended'    => 'Trang chủ Đề xuất',
            'images_count'           => 'Số lượng ảnh',
            'access_count'           => 'Số lượt truy cập',
            'add_time'               => 'Thời gian tạo',
            'upd_time'               => 'Thời gian cập nhật',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Phân loại bài viết',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Trang tùy chỉnh',
        'detail_content_title'                  => 'Nội dung chi tiết',
        'detail_images_title'                   => 'Hình ảnh chi tiết',
        'save_view_tips'                        => 'Xin lưu trước khi xem trước hiệu ứng',
        // 动态表格
        'form_table'                            => [
            'info'            => 'Tiêu đề',
            'is_enable'       => 'Bật hay không',
            'is_header'       => 'Nếu đầu',
            'is_footer'       => 'đuôi hay không',
            'is_full_screen'  => 'Màn hình đầy',
            'images_count'    => 'Số lượng ảnh',
            'access_count'    => 'Số lượt truy cập',
            'add_time'        => 'Thời gian tạo',
            'upd_time'        => 'Thời gian cập nhật',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Tải thêm mẫu thiết kế',
        'upload_list_tips'                      => [
            '1. Chọn gói zip thiết kế trang đã tải xuống',
            '2. Nhập khẩu sẽ tự động thêm một phần dữ liệu mới',
        ],
        'operate_sync_tips'                     => 'Dữ liệu được đồng bộ hóa vào trực quan kéo trang đầu tiên và sau đó sửa đổi dữ liệu mà không bị ảnh hưởng, nhưng không xóa các tệp đính kèm liên quan',
        // 动态表格
        'form_table'                            => [
            'id'                => 'ID dữ liệu',
            'info'              => 'Thông tin cơ bản',
            'info_placeholder'  => 'Vui lòng nhập tên',
            'access_count'      => 'Số lượt truy cập',
            'is_enable'         => 'Bật hay không',
            'is_header'         => 'Bao gồm đầu',
            'is_footer'         => 'Bao gồm đuôi',
            'seo_title'         => 'Tiêu đề SEO',
            'seo_keywords'      => 'Từ khóa SEO',
            'seo_desc'          => 'SEO Mô tả',
            'add_time'          => 'Thời gian tạo',
            'upd_time'          => 'Thời gian cập nhật',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Hỏi đáp',
        'user_info_title'                       => 'Thông tin người dùng',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Thông tin người dùng',
            'user_placeholder'  => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'name'              => 'Liên hệ',
            'tel'               => 'Số điện thoại',
            'content'           => 'Nội dung',
            'reply'             => 'Nội dung trả lời',
            'is_show'           => 'Hiện hay không',
            'is_reply'          => 'Trả lời',
            'reply_time_time'   => 'Thời gian trả lời',
            'access_count'      => 'Số lượt truy cập',
            'add_time_time'     => 'Thời gian tạo',
            'upd_time_time'     => 'Thời gian cập nhật',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Trang chủ',
        'top_tips_list'                         => [
            'B5-05=giá trị thông số Kd, (cài 2)',
            '2. Kho chỉ xóa mềm, sau khi xóa sẽ không có sẵn, chỉ giữ lại dữ liệu trong cơ sở dữ liệu) có thể tự xóa dữ liệu hàng hóa liên quan',
            '3. Vô hiệu hóa và xóa kho, hàng tồn kho liên quan sẽ được phát hành ngay lập tức',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Tên/Bí danh',
            'level'          => 'Trọng lượng',
            'is_enable'      => 'Bật hay không',
            'contacts_name'  => 'Liên hệ',
            'contacts_tel'   => 'Số điện thoại',
            'province_name'  => 'Tỉnh',
            'city_name'      => 'Thành phố',
            'county_name'    => 'Quận/County',
            'address'        => 'Chi tiết địa chỉ',
            'position'       => 'Kinh độ và Vĩ độ',
            'add_time'       => 'Thời gian tạo',
            'upd_time'       => 'Thời gian cập nhật',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Vui lòng chọn kho',
        ],
        // 基础
        'add_goods_title'                       => 'Thêm sản phẩm',
        'no_spec_data_tips'                     => 'Không có dữ liệu Specifications',
        'batch_setup_inventory_placeholder'     => 'Giá trị của Batch Settings',
        'base_spec_inventory_title'             => 'Thông số kỹ thuật Trong kho',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Thông tin cơ bản',
            'goods_placeholder'  => 'Vui lòng nhập tên sản phẩm/Model',
            'warehouse_name'     => 'Trang chủ',
            'is_enable'          => 'Bật hay không',
            'inventory'          => 'Tổng cổ phiếu',
            'spec_inventory'     => 'Thông số kỹ thuật Trong kho',
            'add_time'           => 'Thời gian tạo',
            'upd_time'           => 'Thời gian cập nhật',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'Thông tin quản trị viên không tồn tại',
        // 列表
        'top_tips_list'                         => [
            '1. Tài khoản admin có tất cả các quyền theo mặc định và không thể thay đổi.',
            '2. Tài khoản admin không thể thay đổi, nhưng có thể được sửa đổi trong bảng dữ liệu ('.MyConfig('database.connections.mysql.prefix').'admin) trường username',
        ],
        'base_nav_title'                        => 'Trang chủ',
        // 登录
        'login_type_username_title'             => 'Mật khẩu tài khoản',
        'login_type_mobile_title'               => 'Điện thoại Captcha',
        'login_type_email_title'                => 'Mã xác minh hộp thư',
        'login_close_tips'                      => 'Tạm thời đóng đăng nhập',
        // 忘记密码
        'form_forget_password_name'             => 'Quên mật khẩu?',
        'form_forget_password_tips'             => 'Vui lòng liên hệ với quản trị viên để đặt lại mật khẩu',
        // 动态表格
        'form_table'                            => [
            'username'              => 'Trang chủ',
            'status'                => 'Trạng thái',
            'gender'                => 'Giới tính',
            'mobile'                => 'Điện thoại',
            'email'                 => 'Hộp thư',
            'role_name'             => 'Nhóm nhân vật',
            'login_total'           => 'Số lần đăng nhập',
            'login_time'            => 'Thời gian đăng nhập cuối cùng',
            'add_time'              => 'Thời gian tạo',
            'upd_time'              => 'Thời gian cập nhật',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Thỏa thuận đăng ký người dùng', 'type' => 'register'],
            ['name' => 'Chính sách bảo mật người dùng', 'type' => 'privacy'],
            ['name' => 'Thỏa thuận hủy tài khoản', 'type' => 'logout']
        ],
        'top_tips'          => 'Địa chỉ giao thức truy cập front-end tăng tham số is_ Content=1 chỉ hiển thị nội dung giao thức thuần túy.',
        'view_detail_name'                      => 'Xem chi tiết',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Cấu hình nền tảng', 'type' => 'index'],
            ['name' => 'Ứng dụng/Tiểu dụng', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Chủ đề hiện tại', 'type' => 'index'],
            ['name' => 'Cài đặt sắc thái', 'type' => 'upload'],
            ['name' => 'Tải về gói mã nguồn', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Thêm chủ đề Download',
        'nav_theme_download_name'               => 'Xem hướng dẫn đóng gói applet',
        'nav_theme_download_tips'               => 'Các chủ đề điện thoại di động được phát triển với uniapp (hỗ trợ các applet đa điểm+H5) và các ứng dụng cũng đang được điều chỉnh khẩn cấp.',
        'form_alipay_extend_title'              => 'Cấu hình dịch vụ khách hàng',
        'form_alipay_extend_tips'               => 'PS: Nếu [APP/chương trình nhỏ] được mở (mở dịch vụ khách hàng trực tuyến), thì cấu hình sau đây là bắt buộc [mã hóa doanh nghiệp] và [mã hóa cửa sổ trò chuyện]',
        'form_theme_upload_tips'                => 'Tải lên một gói cài đặt ở định dạng nén zip',
        'list_no_data_tips'                     => 'Không có gói chủ đề liên quan',
        'list_author_title'                     => 'Tác giả',
        'list_version_title'                    => 'Phiên bản áp dụng',
        'package_generate_tips'                 => 'Thời gian tạo tương đối dài, xin đừng đóng cửa sổ trình duyệt!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Tên gói',
            'size'  => 'Kích thước',
            'url'   => 'Địa chỉ download',
            'time'  => 'Thời gian tạo',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Thiết lập SMS', 'type' => 'index'],
            ['name' => 'Thông điệp mẫu', 'type' => 'message'],
        ],
        'top_tips'                              => 'Địa chỉ quản lý SMS Alibaba Cloud',
        'top_to_aliyun_tips'                    => 'Click vào Aliyun để mua SMS',
        'base_item_admin_title'                 => 'Trang chủ',
        'base_item_index_title'                 => 'Mặt trận',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Thiết lập hộp thư', 'type' => 'index'],
            ['name' => 'Thông điệp mẫu', 'type' => 'message'],
        ],
        'top_tips'                              => 'Bởi vì các nền tảng hộp thư khác nhau tồn tại một số khác biệt, cấu hình cũng khác nhau, cụ thể lấy hướng dẫn cấu hình nền tảng hộp thư làm chuẩn',
        // 基础
        'test_title'                            => 'Thử nghiệm',
        'test_content'                          => 'Cấu hình thư - Gửi nội dung thử nghiệm',
        'base_item_admin_title'                 => 'Trang chủ',
        'base_item_index_title'                 => 'Mặt trận',
        // 表单
        'form_item_test'                        => 'Kiểm tra địa chỉ email nhận được',
        'form_item_test_tips'                   => 'Vui lòng lưu cấu hình trước khi kiểm tra',
        'form_item_test_button_title'           => 'Thử nghiệm',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Cấu hình các quy tắc giả tĩnh tương ứng tùy thuộc vào môi trường máy chủ [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'Hàng hóa',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Thông tin cơ bản', 'type'=>'base'],
            'specifications'  => ['name' => 'Thông số sản phẩm', 'type'=>'specifications'],
            'parameters'      => ['name' => 'Thông số hàng hóa', 'type'=>'parameters'],
            'photo'           => ['name' => 'Album hàng hóa', 'type'=>'photo'],
            'video'           => ['name' => 'Video sản phẩm', 'type'=>'video'],
            'app'             => ['name' => 'Chi tiết điện thoại', 'type'=>'app'],
            'web'             => ['name' => 'Chi tiết máy tính', 'type'=>'web'],
            'fictitious'      => ['name' => 'Thông tin ảo', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Dữ liệu mở rộng', 'type'=>'extends'],
            'seo'             => ['name' => 'Thông tin SEO', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Mã sản phẩm',
            'info'                    => 'Thông tin sản phẩm',
            'category_text'           => 'Phân loại hàng hóa',
            'brand_name'              => 'Thương hiệu',
            'price'                   => 'Giá bán (NDT)',
            'original_price'          => 'Giá gốc',
            'inventory'               => 'Tổng cổ phiếu',
            'is_shelves'              => 'Lên và xuống kệ',
            'is_deduction_inventory'  => 'Giảm hàng tồn kho',
            'site_type'               => 'Loại sản phẩm',
            'model'                   => 'Mô hình hàng hóa',
            'place_origin_name'       => 'Nơi sản xuất',
            'give_integral'           => 'Mua tỷ lệ tặng điểm',
            'buy_min_number'          => 'Số lượng mua tối thiểu một lần',
            'buy_max_number'          => 'Số lượng mua tối đa một lần',
            'sales_count'             => 'Bán hàng',
            'access_count'            => 'Số lượt truy cập',
            'add_time'                => 'Thời gian tạo',
            'upd_time'                => 'Thời gian cập nhật',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Phân loại hàng hóa',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Đánh giá sản phẩm',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Thông tin người dùng',
            'user_placeholder'   => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'goods'              => 'Thông tin cơ bản',
            'goods_placeholder'  => 'Vui lòng nhập tên sản phẩm/Model',
            'business_type'      => 'Loại hình kinh doanh',
            'content'            => 'Nội dung bình luận',
            'images'             => 'Hình ảnh bình luận',
            'rating'             => 'Đánh giá',
            'reply'              => 'Nội dung trả lời',
            'is_show'            => 'Hiện hay không',
            'is_anonymous'       => 'Ẩn danh hay không',
            'is_reply'           => 'Trả lời',
            'reply_time_time'    => 'Thời gian trả lời',
            'add_time_time'      => 'Thời gian tạo',
            'upd_time_time'      => 'Thời gian cập nhật',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Thông số hàng hóa',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Phân loại hàng hóa',
            'name'          => 'Tên',
            'is_enable'     => 'Bật hay không',
            'config_count'  => 'Số tham số',
            'add_time'      => 'Thời gian tạo',
            'upd_time'      => 'Thời gian cập nhật',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Phân loại hàng hóa',
            'name'         => 'Tên',
            'is_enable'    => 'Bật hay không',
            'content'      => 'Giá trị Specification',
            'add_time'     => 'Thời gian tạo',
            'upd_time'     => 'Thời gian cập nhật',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Thông tin người dùng',
            'user_placeholder'   => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'goods'              => 'Thông tin sản phẩm',
            'goods_placeholder'  => 'Vui lòng nhập tên sản phẩm/tóm tắt/thông tin SEO',
            'price'              => 'Giá bán (NDT)',
            'original_price'     => 'Giá gốc',
            'add_time'           => 'Thời gian tạo',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Thông tin người dùng',
            'user_placeholder'   => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'goods'              => 'Thông tin sản phẩm',
            'goods_placeholder'  => 'Vui lòng nhập tên sản phẩm/tóm tắt/thông tin SEO',
            'price'              => 'Giá bán (NDT)',
            'original_price'     => 'Giá gốc',
            'add_time'           => 'Thời gian tạo',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Thông tin người dùng',
            'user_placeholder'   => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'goods'              => 'Thông tin sản phẩm',
            'goods_placeholder'  => 'Vui lòng nhập tên sản phẩm/tóm tắt/thông tin SEO',
            'price'              => 'Giá bán (NDT)',
            'original_price'     => 'Giá gốc',
            'add_time'           => 'Thời gian tạo',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Liên kết thân thiện',
        // 动态表格
        'form_table'                            => [
            'info'                => 'Tên',
            'url'                 => 'Địa chỉ URL',
            'describe'            => 'Mô tả',
            'is_enable'           => 'Bật hay không',
            'is_new_window_open'  => 'Cửa sổ mới đang mở',
            'sort'                => 'Sắp xếp',
            'add_time'            => 'Thời gian tạo',
            'upd_time'            => 'Thời gian cập nhật',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Điều hướng trung gian', 'type' => 'header'],
            ['name' => 'Điều hướng đáy', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'Tùy chỉnh',
            'article'           => 'Bài viết',
            'customview'        => 'Trang tùy chỉnh',
            'goods_category'    => 'Phân loại hàng hóa',
            'design'            => 'Thiết kế trang',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Điều hướng Name',
            'data_type'           => 'Kiểu dữ liệu Navigation',
            'is_show'             => 'Hiện hay không',
            'is_new_window_open'  => 'Cửa sổ mới Mở',
            'sort'                => 'Sắp xếp',
            'add_time'            => 'Thời gian tạo',
            'upd_time'            => 'Thời gian cập nhật',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Lệnh id bị lỗi',
            'express_choice_tips'               => 'Vui lòng chọn phương thức chuyển phát nhanh',
            'payment_choice_tips'               => 'Vui lòng chọn phương thức thanh toán',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Hoạt động vận chuyển',
        'form_payment_title'                    => 'Hoạt động thanh toán',
        'form_item_take'                        => 'Lấy mã',
        'form_item_take_message'                => 'Vui lòng điền 4 chữ số vào mã nhận hàng',
        'form_item_express_number'              => 'Số đơn chuyển phát nhanh',
        'form_item_express_number_message'      => 'Vui lòng điền số chuyển phát nhanh',
        // 地址
        'detail_user_address_title'             => 'Địa chỉ nhận hàng',
        'detail_user_address_name'              => 'Người nhận',
        'detail_user_address_tel'               => 'Điện thoại nhận',
        'detail_user_address_value'             => 'Chi tiết địa chỉ',
        'detail_user_address_idcard'            => 'Thông tin ID',
        'detail_user_address_idcard_name'       => 'Tên',
        'detail_user_address_idcard_number'     => 'Số',
        'detail_user_address_idcard_pic'        => 'Hình ảnh',
        'detail_take_address_title'             => 'Địa chỉ nhận hàng',
        'detail_take_address_contact'           => 'Thông tin liên hệ',
        'detail_take_address_value'             => 'Chi tiết địa chỉ',
        'detail_fictitious_title'               => 'Thông tin khóa',
        // 订单售后
        'detail_aftersale_status'               => 'Trạng thái',
        'detail_aftersale_type'                 => 'Loại',
        'detail_aftersale_price'                => 'Số lượng',
        'detail_aftersale_number'               => 'Số lượng',
        'detail_aftersale_reason'               => 'Nguyên nhân',
        // 商品
        'detail_goods_title'                    => 'Đặt hàng',
        'detail_payment_amount_less_tips'       => 'Xin lưu ý rằng số tiền thanh toán cho đơn đặt hàng này nhỏ hơn tổng giá trị',
        'detail_no_payment_tips'                => 'Xin lưu ý rằng lệnh này chưa được thanh toán.',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Thông tin cơ bản',
            'goods_placeholder'   => 'Vui lòng nhập ID đơn hàng/Số đơn hàng/Tên sản phẩm/Model',
            'user'                => 'Thông tin người dùng',
            'user_placeholder'    => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'status'              => 'Trạng thái đặt hàng',
            'pay_status'          => 'Trạng thái thanh toán',
            'total_price'         => 'Tổng giá trị',
            'pay_price'           => 'Kim ngạch thanh toán (Nhân dân tệ)',
            'price'               => 'Đơn giá (Nhân dân tệ)',
            'warehouse_name'      => 'Kho vận chuyển hàng',
            'order_model'         => 'Chế độ đặt hàng',
            'client_type'         => 'Nguồn',
            'address'             => 'Thông tin địa chỉ',
            'take'                => 'Nhận thông tin',
            'refund_price'        => 'Số tiền hoàn lại (NDT)',
            'returned_quantity'   => 'Số lượng hoàn trả',
            'buy_number_count'    => 'Tổng số mua',
            'increase_price'      => 'Tăng kim ngạch',
            'preferential_price'  => 'Kim ngạch ưu đãi (đồng)',
            'payment_name'        => 'Phương thức thanh toán',
            'user_note'           => 'Ghi chú người dùng',
            'extension'           => 'Thông tin mở rộng',
            'express_name'        => 'Công ty chuyển phát nhanh',
            'express_number'      => 'Số đơn chuyển phát nhanh',
            'aftersale'           => 'Sau bán hàng mới nhất',
            'is_comments'         => 'Người dùng có bình luận hay không',
            'confirm_time'        => 'Thời gian xác nhận',
            'pay_time'            => 'Thời gian thanh toán',
            'delivery_time'       => 'Thời gian vận chuyển',
            'collect_time'        => 'Thời gian hoàn thành',
            'cancel_time'         => 'Thời gian hủy',
            'close_time'          => 'Thời gian đóng cửa',
            'add_time'            => 'Thời gian tạo',
            'upd_time'            => 'Thời gian cập nhật',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'Hoạt động kiểm toán',
        'form_refuse_title'                     => 'Từ chối hoạt động',
        'form_user_info_title'                  => 'Thông tin người dùng',
        'form_apply_info_title'                 => 'Thông tin ứng tuyển',
        'forn_apply_info_type'                  => 'Loại',
        'forn_apply_info_price'                 => 'Số lượng',
        'forn_apply_info_number'                => 'Số lượng',
        'forn_apply_info_reason'                => 'Nguyên nhân',
        'forn_apply_info_msg'                   => 'Mô tả',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Thông tin cơ bản',
            'goods_placeholder'  => 'Vui lòng nhập số đặt hàng/tên sản phẩm/mô hình',
            'user'               => 'Thông tin người dùng',
            'user_placeholder'   => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'status'             => 'Trạng thái',
            'type'               => 'Loại ứng dụng',
            'reason'             => 'Nguyên nhân',
            'price'              => 'Số tiền hoàn lại (NDT)',
            'number'             => 'Số lượng hoàn trả',
            'msg'                => 'Hướng dẫn hoàn tiền',
            'refundment'         => 'Loại hoàn tiền',
            'voucher'            => 'Chứng từ',
            'express_name'       => 'Công ty chuyển phát nhanh',
            'express_number'     => 'Số đơn chuyển phát nhanh',
            'refuse_reason'      => 'Lý do từ chối',
            'apply_time'         => 'Thời gian nộp đơn',
            'confirm_time'       => 'Thời gian xác nhận',
            'delivery_time'      => 'Thời gian hoàn trả',
            'audit_time'         => 'Thời gian xét duyệt',
            'add_time'           => 'Thời gian tạo',
            'upd_time'           => 'Thời gian cập nhật',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'Phương thức thanh toán',
        'nav_store_payment_name'                => 'Thêm chủ đề Download',
        'upload_top_list_tips'                  => [
            [
                'name'  => 'Tên lớp phải phù hợp với tên tệp (loại bỏ.php), chẳng hạn như Alipay.php hoặc Alipay.php',
            ],
            [
                'name'  => '2 Các method mà class phải định nghĩa',
                'item'  => [
                    '2.1 Cấu hình Config',
                    '2.2 Phương thức thanh toán',
                    '2.3 Phương pháp gọi lại Respond',
                    'REFERENCES [Tên bảng tham chiếu] (',
                    'REFERENCES [Tên bảng tham chiếu] (',
                ],
            ],
            [
                'name'  => '3. Phương pháp nội dung đầu ra có thể được tùy chỉnh',
                'item'  => [
                    'B5-03=giá trị thông số Ki, (cài 3)',
                    'B5-05=giá trị thông số Kd, (cài 2)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: Điều kiện trên không đáp ứng thì không thể xem plugin, đưa plugin vào gói nén.zip tải lên, hỗ trợ một nén chứa nhiều plugin thanh toán',
        // 动态表格
        'form_table'                            => [
            'name'            => 'Tên',
            'logo'            => 'LOGO',
            'version'         => 'Phiên bản',
            'apply_version'   => 'Phiên bản áp dụng',
            'apply_terminal'  => 'Thiết bị đầu cuối áp dụng',
            'author'          => 'Tác giả',
            'desc'            => 'Mô tả',
            'enable'          => 'Bật hay không',
            'open_user'       => 'Người dùng mở',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'Chuyển phát nhanh',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Chủ đề hiện tại', 'type' => 'index'],
            ['name' => 'Cài đặt sắc thái', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'Thêm chủ đề Download',
        'list_author_title'                     => 'Tác giả',
        'list_version_title'                    => 'Phiên bản áp dụng',
        'form_theme_upload_tips'                => 'Tải lên một gói cài đặt chủ đề ở định dạng nén zip',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Trung tâm người dùng di động Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Tên',
            'platform'       => 'Nền tảng sở hữu',
            'images_url'     => 'Biểu tượng điều hướng',
            'event_type'     => 'Loại sự kiện',
            'event_value'    => 'Giá trị sự kiện',
            'desc'           => 'Mô tả',
            'is_enable'      => 'Bật hay không',
            'is_need_login'  => 'Cần đăng nhập',
            'sort'           => 'Sắp xếp',
            'add_time'       => 'Thời gian tạo',
            'upd_time'       => 'Thời gian cập nhật',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Điện thoại di động Home Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Tên',
            'platform'       => 'Nền tảng sở hữu',
            'images'         => 'Biểu tượng điều hướng',
            'event_type'     => 'Loại sự kiện',
            'event_value'    => 'Giá trị sự kiện',
            'is_enable'      => 'Bật hay không',
            'is_need_login'  => 'Cần đăng nhập',
            'sort'           => 'Sắp xếp',
            'add_time'       => 'Thời gian tạo',
            'upd_time'       => 'Thời gian cập nhật',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Nhật ký yêu cầu thanh toán',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Thông tin người dùng',
            'user_placeholder'  => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'log_no'            => 'Số đơn thanh toán',
            'payment'           => 'Phương thức thanh toán',
            'status'            => 'Trạng thái',
            'total_price'       => 'Kim ngạch đơn đặt hàng nghiệp vụ (NDT)',
            'pay_price'         => 'Kim ngạch thanh toán (Nhân dân tệ)',
            'business_type'     => 'Loại hình kinh doanh',
            'business_list'     => 'ID nghiệp vụ/Số đơn',
            'trade_no'          => 'Nền tảng thanh toán Số giao dịch',
            'buyer_user'        => 'Tài khoản người dùng nền tảng thanh toán',
            'subject'           => 'Tên đặt hàng',
            'pay_time'          => 'Thời gian thanh toán',
            'close_time'        => 'Thời gian đóng cửa',
            'add_time'          => 'Thời gian tạo',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Nhật ký yêu cầu thanh toán',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Loại hình kinh doanh',
            'request_params'   => 'Yêu cầu tham số',
            'response_data'    => 'Dữ liệu phản hồi',
            'business_handle'  => 'Kết quả xử lý kinh doanh',
            'request_url'      => 'Yêu cầu địa chỉ URL',
            'server_port'      => 'Số cổng',
            'server_ip'        => 'IP máy chủ',
            'client_ip'        => 'IP khách hàng',
            'os'               => 'Hệ điều hành',
            'browser'          => 'Trình duyệt',
            'method'           => 'Loại yêu cầu',
            'scheme'           => 'Kiểu http',
            'version'          => 'Phiên bản http',
            'client'           => 'Chi tiết khách hàng',
            'add_time'         => 'Thời gian tạo',
            'upd_time'         => 'Thời gian cập nhật',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Thông tin người dùng',
            'user_placeholder'  => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'payment'           => 'Phương thức thanh toán',
            'business_type'     => 'Loại hình kinh doanh',
            'business_id'       => 'Đơn đặt hàng ID',
            'trade_no'          => 'Nền tảng thanh toán Số giao dịch',
            'buyer_user'        => 'Tài khoản người dùng nền tảng thanh toán',
            'refundment_text'   => 'Phương thức hoàn tiền',
            'refund_price'      => 'Số tiền hoàn trả',
            'pay_price'         => 'Số tiền thanh toán đơn hàng',
            'msg'               => 'Mô tả',
            'add_time_time'     => 'Thời gian hoàn tiền',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Quay lại quản lý ứng dụng>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Vui lòng bấm nút để kích hoạt',
            'save_no_data_tips'                 => 'Không có dữ liệu bổ sung có thể lưu',
        ],
        // 基础导航
        'base_nav_title'                        => 'Ứng dụng',
        'base_nav_list'                         => [
            ['name' => 'Quản lý ứng dụng', 'type' => 'index'],
            ['name' => 'Tải lên ứng dụng', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'Tải thêm plugin',
        // 基础页面
        'base_search_input_placeholder'         => 'Vui lòng nhập tên/mô tả',
        'base_top_tips_one'                     => 'Danh sách sắp xếp theo [Tùy chỉnh sắp xếp ->Cài đặt sớm nhất]',
        'base_top_tips_two'                     => 'Nút biểu tượng kéo có thể nhấp để điều chỉnh trình tự gọi và hiển thị plugin',
        'base_open_sort_title'                  => 'Mở Sắp xếp',
        'data_list_author_title'                => 'Tác giả',
        'data_list_author_url_title'            => 'Trang chủ',
        'data_list_version_title'               => 'Phiên bản',
        'uninstall_confirm_tips'                => 'Gỡ cài đặt có thể mất plugin cấu hình cơ sở dữ liệu không thể phục hồi, xác nhận hoạt động?',
        'not_install_divide_title'              => 'Các plugin sau không được cài đặt',
        'delete_plugins_text'                   => '1. Chỉ xóa ứng dụng',
        'delete_plugins_text_tips'              => '(Chỉ xóa mã ứng dụng, giữ lại dữ liệu ứng dụng)',
        'delete_plugins_data_text'              => '2. Xóa ứng dụng và xóa dữ liệu',
        'delete_plugins_data_text_tips'         => '(Mã ứng dụng và dữ liệu ứng dụng sẽ bị xóa)',
        'delete_plugins_ps_tips'                => 'PS: sau đây hoạt động không thể phục hồi, xin vui lòng hoạt động thận trọng!',
        'delete_plugins_button_name'            => 'Chỉ xóa ứng dụng',
        'delete_plugins_data_button_name'       => 'Xóa ứng dụng và dữ liệu',
        'cancel_delete_plugins_button_name'     => 'Nghĩ lại đi.',
        'more_plugins_store_to_text'            => 'Truy cập App Store để chọn thêm các trang web giàu plugin>>',
        'no_data_store_to_text'                 => 'Đến cửa hàng ứng dụng chọn plugin làm phong phú trang web>>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Quay lại nền',
        'get_loading_tips'                      => 'Đang nhận......',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'Vai trò',
        'admin_not_modify_tips'                 => 'Super Admin có tất cả các quyền theo mặc định và không thể thay đổi.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Tên nhân vật',
            'is_enable'  => 'Bật hay không',
            'add_time'   => 'Thời gian tạo',
            'upd_time'   => 'Thời gian cập nhật',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'Quyền hạn',
        'top_tips_list'                         => [
            '1. Nhân viên kỹ thuật không chuyên nghiệp không thao tác dữ liệu trang này, thao tác sai có thể dẫn đến rối loạn menu quyền hạn.',
            '2. Menu quyền được chia thành hai loại [sử dụng, thao tác], sử dụng menu thường để mở hiển thị, menu thao tác phải được ẩn.',
            '3. 如果出现权限菜单错乱，可以重新覆盖[ '.MyConfig('database.connections.mysql.prefix').'power ]数据表的数据恢复。',
            '[super admin, admin account] Có tất cả các quyền mặc định và không thể thay đổi.',
        ],
        'content_top_tips_list'                 => [
            '1. Điền vào [tên bộ điều khiển và tên phương thức] yêu cầu tạo ra các định nghĩa tương ứng của bộ điều khiển và phương thức',
            '2. vị trí tập tin điều khiển [app/admin/controller], hoạt động này chỉ được sử dụng bởi các nhà phát triển',
            '3. tên điều khiển/tên phương thức và địa chỉ url tùy chỉnh, cả hai phải được điền vào một',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Chuyển hướng nhanh',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Tên',
            'platform'     => 'Nền tảng sở hữu',
            'images'       => 'Biểu tượng điều hướng',
            'event_type'   => 'Loại sự kiện',
            'event_value'  => 'Giá trị sự kiện',
            'is_enable'    => 'Bật hay không',
            'sort'         => 'Sắp xếp',
            'add_time'     => 'Thời gian tạo',
            'upd_time'     => 'Thời gian cập nhật',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'Quận',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Lọc giá',
        'top_tips_list'                         => [
            'Giá tối thiểu 0 - Giá tối đa 100 là nhỏ hơn 100',
            'Giá tối thiểu 1000 - Giá tối đa 0 là lớn hơn 1000',
            'Giá tối thiểu 100 - Giá tối đa 500 là lớn hơn hoặc bằng 100 và nhỏ hơn 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Vòng quay',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Tên',
            'platform'     => 'Nền tảng sở hữu',
            'images'       => 'Hình ảnh',
            'event_type'   => 'Loại sự kiện',
            'event_value'  => 'Giá trị sự kiện',
            'is_enable'    => 'Bật hay không',
            'sort'         => 'Sắp xếp',
            'add_time'     => 'Thời gian tạo',
            'upd_time'     => 'Thời gian cập nhật',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Thông tin người dùng',
            'user_placeholder'    => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'type'                => 'Loại hoạt động',
            'operation_integral'  => 'Điểm hoạt động',
            'original_integral'   => 'Tích phân gốc',
            'new_integral'        => 'Điểm mới nhất',
            'msg'                 => 'Lý do hoạt động',
            'operation_id'        => 'ID người vận hành',
            'add_time_time'       => 'Thời gian hoạt động',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Thông tin người dùng',
            'user_placeholder'          => 'Vui lòng nhập tên người dùng/biệt danh/điện thoại/hộp thư',
            'type'                      => 'Kiểu tin nhắn',
            'business_type'             => 'Loại hình kinh doanh',
            'title'                     => 'Tiêu đề',
            'detail'                    => 'Chi tiết',
            'is_read'                   => 'Đã đọc chưa',
            'user_is_delete_time_text'  => 'Người dùng có xóa hay không',
            'add_time_time'             => 'Thời gian gửi',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: Người không phải là nhà phát triển xin vui lòng không thực hiện bất kỳ câu lệnh SQL ngẫu nhiên, hành động có thể dẫn đến việc xóa toàn bộ cơ sở dữ liệu hệ thống.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'Danh sách các ứng dụng tuyệt vời của ShopXO, nơi đây tập hợp các nhà phát triển ShopXO lâu năm nhất, kỹ thuật mạnh nhất, đáng tin cậy nhất, tùy chỉnh hộ tống toàn diện cho các plugin, phong cách, mẫu của bạn.',
        'to_store_name'                         => 'Truy cập App Store và chọn plugin »',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Hệ thống quản lý nền',
        'remove_cache_title'                    => 'Xoá bộ nhớ tạm',
        'user_status_title'                     => 'Trạng thái người dùng',
        'user_status_message'                   => 'Chọn User Status',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Thông tin cấu hình thông số hàng hóa',
        'form_goods_params_copy_no_tips'        => 'Vui lòng dán thông tin cấu hình trước',
        'form_goods_params_copy_error_tips'     => 'Định dạng cấu hình sai',
        'form_goods_params_type_message'        => 'Vui lòng chọn loại hiển thị thông số sản phẩm',
        'form_goods_params_params_name'         => 'Tên tham số',
        'form_goods_params_params_message'      => 'Vui lòng điền tên tham số',
        'form_goods_params_value_name'          => 'Giá trị tham số',
        'form_goods_params_value_message'       => 'Vui lòng điền vào giá trị tham số',
        'form_goods_params_move_type_tips'      => 'Cấu hình kiểu hoạt động sai',
        'form_goods_params_move_top_tips'       => 'Đã lên tới đỉnh',
        'form_goods_params_move_bottom_tips'    => 'Đã tới đáy.',
        'form_goods_params_thead_type_title'    => 'Phạm vi hiển thị',
        'form_goods_params_thead_name_title'    => 'Tên tham số',
        'form_goods_params_thead_value_title'   => 'Giá trị tham số',
        'form_goods_params_row_add_title'       => 'Thêm một dòng',
        'form_goods_params_list_tips'           => [
            '1. Tất cả (được hiển thị dưới thông tin cơ bản hàng hóa và thông số chi tiết)',
            '2. Chi tiết (chỉ hiển thị dưới các thông số chi tiết hàng hóa)',
            'B5-03=giá trị thông số Ki, (cài 3)',
            'Thao tác nhanh sẽ xóa dữ liệu ban đầu, tải lại trang để khôi phục dữ liệu ban đầu (chỉ có hiệu lực sau khi lưu hàng hóa)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Thiết lập hệ thống',
            'item'  => [
                'config_index'                 => 'Cấu hình hệ thống',
                'config_store'                 => 'Thông tin cửa hàng',
                'config_save'                  => 'Cấu hình lưu',
                'index_storeaccountsbind'      => 'Tài khoản App Store',
                'index_inspectupgrade'         => 'Kiểm tra cập nhật hệ thống',
                'index_inspectupgradeconfirm'  => 'Cập nhật hệ thống xác nhận',
                'index_stats'                  => 'Home Thống kê',
                'index_income'                 => 'Thống kê thu nhập (Income Statistics)',
            ]
        ],
        'site_index' => [
            'name'  => 'Cấu hình trang web',
            'item'  => [
                'site_index'                  => 'Thiết lập trang web',
                'site_save'                   => 'Chỉnh sửa thiết lập trang web',
                'site_goodssearch'            => 'Thiết lập trang web Tìm kiếm sản phẩm',
                'layout_layoutindexhomesave'  => 'Trang chủ Quản lý bố cục',
                'sms_index'                   => 'Thiết lập SMS',
                'sms_save'                    => 'Cài đặt SMS Edit',
                'email_index'                 => 'Thiết lập hộp thư',
                'email_save'                  => 'Thiết lập hộp thư/Chỉnh sửa',
                'email_emailtest'             => 'Kiểm tra gửi thư',
                'seo_index'                   => 'Thiết lập SEO',
                'seo_save'                    => 'Thiết lập SEO Edit',
                'agreement_index'             => 'Quản lý giao thức',
                'agreement_save'              => 'Thiết lập giao thức Edit',
            ]
        ],
        'power_index' => [
            'name'  => 'Kiểm soát quyền',
            'item'  => [
                'admin_index'        => 'Danh sách quản trị viên',
                'admin_saveinfo'     => 'Quản trị viên Add/Edit Page',
                'admin_save'         => 'Quản trị viên Add/Edit',
                'admin_delete'       => 'Quản trị viên gỡ bỏ',
                'admin_detail'       => 'Chi tiết Admin',
                'role_index'         => 'Quản lý vai trò',
                'role_saveinfo'      => 'Thêm/chỉnh sửa trang cho nhóm vai trò',
                'role_save'          => 'Thêm/chỉnh sửa nhóm vai trò',
                'role_delete'        => 'Xóa vai trò',
                'role_statusupdate'  => 'Cập nhật trạng thái vai trò',
                'role_detail'        => 'Chi tiết nhân vật',
                'power_index'        => 'Phân bổ quyền',
                'power_save'         => 'Thêm/sửa quyền',
                'power_delete'       => 'Xóa quyền',
            ]
        ],
        'user_index' => [
            'name'  => 'Quản lý người dùng',
            'item'  => [
                'user_index'            => 'Danh sách người dùng',
                'user_saveinfo'         => 'Người dùng chỉnh sửa/thêm trang',
                'user_save'             => 'Người dùng Add/Edit',
                'user_delete'           => 'Xoá người dùng',
                'user_detail'           => 'Chi tiết người dùng',
                'useraddress_index'     => 'Địa chỉ người dùng',
                'useraddress_saveinfo'  => 'Địa chỉ người dùng Chỉnh sửa trang',
                'useraddress_save'      => 'Sửa địa chỉ người dùng',
                'useraddress_delete'    => 'Xóa địa chỉ người dùng',
                'useraddress_detail'    => 'Chi tiết địa chỉ người dùng',
            ]
        ],
        'goods_index' => [
            'name'  => 'Quản lý hàng hóa',
            'item'  => [
                'goods_index'                       => 'Quản lý hàng hóa',
                'goods_saveinfo'                    => 'Sản phẩm Add/Edit page',
                'goods_save'                        => 'Sản phẩm Add/Edit',
                'goods_delete'                      => 'Xóa hàng',
                'goods_statusupdate'                => 'Cập nhật tình trạng hàng hóa',
                'goods_basetemplate'                => 'Lấy mẫu cơ bản hàng hóa',
                'goods_detail'                      => 'Chi tiết sản phẩm',
                'goodscategory_index'               => 'Phân loại hàng hóa',
                'goodscategory_save'                => 'Danh mục sản phẩm Add/Edit',
                'goodscategory_delete'              => 'Loại bỏ phân loại hàng hóa',
                'goodsparamstemplate_index'         => 'Thông số hàng hóa',
                'goodsparamstemplate_delete'        => 'Loại bỏ tham số hàng hóa',
                'goodsparamstemplate_statusupdate'  => 'Cập nhật trạng thái thông số hàng hóa',
                'goodsparamstemplate_saveinfo'      => 'Thông số sản phẩm Add/Edit page',
                'goodsparamstemplate_save'          => 'Thông số sản phẩm Add/Edit',
                'goodsparamstemplate_detail'        => 'Thông số sản phẩm Chi tiết',
                'goodsspectemplate_index'           => 'Thông số sản phẩm',
                'goodsspectemplate_delete'          => 'Loại bỏ thông số sản phẩm',
                'goodsspectemplate_statusupdate'    => 'Thông số kỹ thuật hàng hóa Cập nhật trạng thái',
                'goodsspectemplate_saveinfo'        => 'Thông số sản phẩm Add/Edit page',
                'goodsspectemplate_save'            => 'Thông số sản phẩm Add/Edit',
                'goodsspectemplate_detail'          => 'Thông số kỹ thuật hàng hóa Chi tiết',
                'goodscomments_detail'              => 'Chi tiết đánh giá sản phẩm',
                'goodscomments_index'               => 'Đánh giá sản phẩm',
                'goodscomments_reply'               => 'Sản phẩm Review Reply',
                'goodscomments_delete'              => 'Sản phẩm Comments Remove',
                'goodscomments_statusupdate'        => 'Cập nhật trạng thái đánh giá hàng hóa',
                'goodscomments_saveinfo'            => 'Bình luận sản phẩm Add/Edit page',
                'goodscomments_save'                => 'Product Review Add/Edit thêm vào',
                'goodsbrowse_index'                 => 'Duyệt sản phẩm',
                'goodsbrowse_delete'                => 'Xóa trình duyệt sản phẩm',
                'goodsbrowse_detail'                => 'Xem chi tiết sản phẩm',
                'goodsfavor_index'                  => 'Bộ sưu tập hàng hóa',
                'goodsfavor_delete'                 => 'Xóa bộ sưu tập hàng hóa',
                'goodsfavor_detail'                 => 'Chi tiết bộ sưu tập hàng hóa',
                'goodscart_index'                   => 'Giỏ hàng',
                'goodscart_delete'                  => 'Sản phẩm Giỏ hàng Remove',
                'goodscart_detail'                  => 'Chi tiết giỏ hàng',
            ]
        ],
        'order_index' => [
            'name'  => 'Quản lý đơn hàng',
            'item'  => [
                'order_index'             => 'Quản lý đơn hàng',
                'order_delete'            => 'Xóa đơn hàng',
                'order_cancel'            => 'Hủy đặt hàng',
                'order_delivery'          => 'Đặt hàng vận chuyển',
                'order_collect'           => 'Đặt hàng nhận hàng',
                'order_pay'               => 'Thanh toán đơn hàng',
                'order_confirm'           => 'Xác nhận đơn hàng',
                'order_detail'            => 'Chi tiết đặt hàng',
                'orderaftersale_index'    => 'Sau khi đặt hàng',
                'orderaftersale_delete'   => 'Xóa sau khi đặt hàng',
                'orderaftersale_cancel'   => 'Hủy sau khi đặt hàng',
                'orderaftersale_audit'    => 'Kiểm toán sau bán hàng',
                'orderaftersale_confirm'  => 'Xác nhận sau khi đặt hàng',
                'orderaftersale_refuse'   => 'Từ chối sau khi đặt hàng',
                'orderaftersale_detail'   => 'Chi tiết sau khi đặt hàng',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Quản lý website',
            'item'  => [
                'navigation_index'         => 'Quản lý Navigation',
                'navigation_save'          => 'Điều hướng Thêm/Chỉnh sửa',
                'navigation_delete'        => 'Xóa điều hướng',
                'navigation_statusupdate'  => 'Cập nhật trạng thái Navigation',
                'customview_index'         => 'Trang tùy chỉnh',
                'customview_saveinfo'      => 'Tùy chỉnh thêm/chỉnh sửa trang',
                'customview_save'          => 'Thêm/chỉnh sửa trang tùy chỉnh',
                'customview_delete'        => 'Xóa trang tùy chỉnh',
                'customview_statusupdate'  => 'Cập nhật trạng thái trang tùy chỉnh',
                'customview_detail'        => 'Chi tiết trang tùy chỉnh',
                'link_index'               => 'Liên kết thân thiện',
                'link_saveinfo'            => 'Liên kết hữu nghị Add/Edit page',
                'link_save'                => 'Liên kết hữu nghị Add/Edit',
                'link_delete'              => 'Xóa liên kết bạn bè',
                'link_statusupdate'        => 'Cập nhật trạng thái liên kết thân thiện',
                'link_detail'              => 'Chi tiết liên kết thân thiện',
                'theme_index'              => 'Quản lý chủ đề',
                'theme_save'               => 'Quản lý sắc thái Add/Edit',
                'theme_upload'             => 'Cài đặt Upload Theme',
                'theme_delete'             => 'Xóa sắc thái',
                'theme_download'           => 'Tải xuống chủ đề',
                'slide_index'              => 'Trang chủ Roundup',
                'slide_saveinfo'           => 'Thêm/chỉnh sửa trang luân phiên',
                'slide_save'               => 'Thêm/chỉnh sửa luân phiên',
                'slide_statusupdate'       => 'Cập nhật trạng thái luân phiên',
                'slide_delete'             => 'Xoá vòng',
                'slide_detail'             => 'Chi tiết Round',
                'screeningprice_index'     => 'Lọc giá',
                'screeningprice_save'      => 'Lọc giá Thêm/chỉnh sửa',
                'screeningprice_delete'    => 'Lọc giá Xóa',
                'region_index'             => 'Quản lý khu vực',
                'region_save'              => 'Khu vực Add/Edit',
                'region_delete'            => 'Xoá vùng',
                'region_codedata'          => 'Lấy dữ liệu số vùng',
                'express_index'            => 'Quản lý chuyển phát nhanh',
                'express_save'             => 'Chuyển phát nhanh Add/Edit',
                'express_delete'           => 'Chuyển phát nhanh',
                'payment_index'            => 'Phương thức thanh toán',
                'payment_saveinfo'         => 'Phương thức thanh toán Cài đặt/Chỉnh sửa trang',
                'payment_save'             => 'Phương thức thanh toán Cài đặt/Chỉnh sửa',
                'payment_delete'           => 'Phương thức thanh toán Xóa',
                'payment_install'          => 'Phương thức thanh toán Cài đặt',
                'payment_statusupdate'     => 'Cập nhật trạng thái phương thức thanh toán',
                'payment_uninstall'        => 'Phương thức thanh toán Gỡ cài đặt',
                'payment_upload'           => 'Phương thức thanh toán Upload',
                'quicknav_index'           => 'Chuyển hướng nhanh',
                'quicknav_saveinfo'        => 'Điều hướng nhanh Thêm/Sửa trang',
                'quicknav_save'            => 'Điều hướng nhanh Thêm/Chỉnh sửa',
                'quicknav_statusupdate'    => 'Cập nhật trạng thái điều hướng nhanh',
                'quicknav_delete'          => 'Xóa điều hướng nhanh',
                'quicknav_detail'          => 'Chi tiết điều hướng nhanh',
                'design_index'             => 'Thiết kế trang',
                'design_saveinfo'          => 'Thiết kế trang Thêm/chỉnh sửa trang',
                'design_save'              => 'Thiết kế trang Add/Edit',
                'design_statusupdate'      => 'Cập nhật trạng thái thiết kế trang',
                'design_upload'            => 'Thiết kế trang Import',
                'design_download'          => 'Thiết kế trang Download',
                'design_sync'              => 'Thiết kế trang Đồng bộ Trang chủ',
                'design_delete'            => 'Thiết kế trang Xóa',
            ]
        ],
        'brand_index' => [
            'name'  => 'Quản lý thương hiệu',
            'item'  => [
                'brand_index'           => 'Quản lý thương hiệu',
                'brand_saveinfo'        => 'Thương hiệu Add/Edit Page',
                'brand_save'            => 'Thương hiệu Add/Edit',
                'brand_statusupdate'    => 'Cập nhật trạng thái thương hiệu',
                'brand_delete'          => 'Xóa nhãn hiệu',
                'brand_detail'          => 'Chi tiết thương hiệu',
                'brandcategory_index'   => 'Phân loại thương hiệu',
                'brandcategory_save'    => 'Phân loại thương hiệu Add/Edit',
                'brandcategory_delete'  => 'Loại bỏ phân loại thương hiệu',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Quản lý kho',
            'item'  => [
                'warehouse_index'               => 'Quản lý kho',
                'warehouse_saveinfo'            => 'Trang bổ sung/chỉnh sửa kho',
                'warehouse_save'                => 'Nhà kho Add/Edit',
                'warehouse_delete'              => 'Xóa kho',
                'warehouse_statusupdate'        => 'Cập nhật trạng thái kho',
                'warehouse_detail'              => 'Chi tiết kho',
                'warehousegoods_index'          => 'Quản lý hàng hóa kho',
                'warehousegoods_detail'         => 'Chi tiết hàng hóa kho',
                'warehousegoods_delete'         => 'Xóa hàng hóa kho',
                'warehousegoods_statusupdate'   => 'Cập nhật tình trạng hàng hóa kho',
                'warehousegoods_goodssearch'    => 'Tìm kiếm hàng hóa kho',
                'warehousegoods_goodsadd'       => 'Kho hàng Search Thêm',
                'warehousegoods_goodsdel'       => 'Kho hàng Tìm kiếm Xóa',
                'warehousegoods_inventoryinfo'  => 'Trang biên tập hàng tồn kho kho',
                'warehousegoods_inventorysave'  => 'Kho hàng tồn kho Edit',
            ]
        ],
        'app_index' => [
            'name'  => 'Quản lý điện thoại',
            'item'  => [
                'appconfig_index'            => 'Cấu hình nền tảng',
                'appconfig_save'             => 'Lưu cấu hình cơ sở',
                'apphomenav_index'           => 'Trang chủ Navigation',
                'apphomenav_saveinfo'        => 'Trang chủ Điều hướng Thêm/Chỉnh sửa trang',
                'apphomenav_save'            => 'Trang chủ Navigation Thêm/Chỉnh sửa',
                'apphomenav_statusupdate'    => 'Trang chủ Cập nhật trạng thái Navigation',
                'apphomenav_delete'          => 'Trang chủ Navigation Xóa',
                'apphomenav_detail'          => 'Trang chủ Chi tiết điều hướng',
                'appcenternav_index'         => 'Điều hướng trung tâm người dùng',
                'appcenternav_saveinfo'      => 'Điều hướng Trung tâm Người dùng Thêm/Chỉnh sửa Trang',
                'appcenternav_save'          => 'Điều hướng Trung tâm Người dùng Thêm/Chỉnh sửa',
                'appcenternav_statusupdate'  => 'Cập nhật trạng thái điều hướng của Trung tâm người dùng',
                'appcenternav_delete'        => 'Trung tâm người dùng Navigation Remove',
                'appcenternav_detail'        => 'Chi tiết điều hướng trung tâm người dùng',
                'appmini_index'              => 'Danh sách các applet',
                'appmini_created'            => 'Tạo gói applet',
                'appmini_delete'             => 'Gỡ bỏ gói nhỏ',
                'appmini_themeupload'        => 'Tải lên theme applet',
                'appmini_themesave'          => 'Name',
                'appmini_themedelete'        => 'Name',
                'appmini_themedownload'      => 'Name',
                'appmini_config'             => 'Cấu hình Tiểu dụng',
                'appmini_save'               => 'Cấu hình Tiểu dụng Lưu',
            ]
        ],
        'article_index' => [
            'name'  => 'Quản lý bài viết',
            'item'  => [
                'article_index'           => 'Quản lý bài viết',
                'article_saveinfo'        => 'Thêm bài viết/Chỉnh sửa trang',
                'article_save'            => 'Bài viết Add/Edit',
                'article_delete'          => 'Xóa bài viết',
                'article_statusupdate'    => 'Cập nhật trạng thái bài viết',
                'article_detail'          => 'Chi tiết bài viết',
                'articlecategory_index'   => 'Phân loại bài viết',
                'articlecategory_save'    => 'Bài viết thể loại Edit/Add',
                'articlecategory_delete'  => 'Phân loại bài viết Xóa',
            ]
        ],
        'data_index' => [
            'name'  => 'Quản lý dữ liệu',
            'item'  => [
                'answer_index'          => 'Hỏi đáp tin nhắn',
                'answer_reply'          => 'Hỏi đáp Tin nhắn Trả lời',
                'answer_delete'         => 'Hỏi đáp Xóa tin nhắn',
                'answer_statusupdate'   => 'Hỏi đáp Cập nhật trạng thái tin nhắn',
                'answer_saveinfo'       => 'Hỏi&Đáp Add/Edit page',
                'answer_save'           => 'Hỏi&Đáp Add/Edit',
                'answer_detail'         => 'Hỏi đáp Chi tiết tin nhắn',
                'message_index'         => 'Quản lý tin nhắn',
                'message_delete'        => 'Xóa tin nhắn',
                'message_detail'        => 'Thông tin chi tiết',
                'paylog_index'          => 'Nhật ký thanh toán',
                'paylog_detail'         => 'Chi tiết nhật ký thanh toán',
                'paylog_close'          => 'Nhật ký thanh toán Đóng',
                'payrequestlog_index'   => 'Danh sách nhật ký yêu cầu thanh toán',
                'payrequestlog_detail'  => 'Chi tiết nhật ký yêu cầu thanh toán',
                'refundlog_index'       => 'Nhật ký hoàn tiền',
                'refundlog_detail'      => 'Chi tiết nhật ký hoàn tiền',
                'integrallog_index'     => 'Nhật ký tích phân',
                'integrallog_detail'    => 'Chi tiết nhật ký điểm',
            ]
        ],
        'store_index' => [
            'name'  => 'Trung tâm ứng dụng',
            'item'  => [
                'pluginsadmin_index'         => 'Quản lý ứng dụng',
                'plugins_index'              => 'Ứng dụng Call Management',
                'pluginsadmin_saveinfo'      => 'Áp dụng Add/Edit Page',
                'pluginsadmin_save'          => 'Áp dụng Add/Edit',
                'pluginsadmin_statusupdate'  => 'Áp dụng cập nhật trạng thái',
                'pluginsadmin_delete'        => 'Áp dụng Xóa',
                'pluginsadmin_upload'        => 'Tải lên ứng dụng',
                'pluginsadmin_download'      => 'Áp dụng đóng gói',
                'pluginsadmin_install'       => 'Cài đặt ứng dụng',
                'pluginsadmin_uninstall'     => 'Gỡ cài đặt ứng dụng',
                'pluginsadmin_sortsave'      => 'Áp dụng lưu sắp xếp',
                'store_index'                => 'Cửa hàng ứng dụng',
                'packageinstall_index'       => 'Trang cài đặt gói',
                'packageinstall_install'     => 'Cài đặt gói',
                'packageupgrade_upgrade'     => 'Cập nhật gói',
            ]
        ],
        'tool_index' => [
            'name'  => 'Công cụ',
                'item'                  => [
                'cache_index'           => 'Quản lý bộ nhớ cache',
                'cache_statusupdate'    => 'Cập nhật bộ nhớ cache trang web',
                'cache_templateupdate'  => 'Cập nhật bộ nhớ tạm mẫu',
                'cache_moduleupdate'    => 'Cập nhật bộ nhớ cache mô-đun',
                'cache_logdelete'       => 'Xóa nhật ký',
                'sqlconsole_index'      => 'Bảng điều khiển SQL',
                'sqlconsole_implement'  => 'Thực thi SQL',
            ]
        ],
    ],
];
?>