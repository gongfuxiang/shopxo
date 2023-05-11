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
 * 模块语言包-韩语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => '쇼핑몰 홈페이지',
        'back_to_the_home_title'                => '첫 페이지로 돌아가기',
        'all_category_text'                     => '전체 분류',
        'login_title'                           => '로그인',
        'register_title'                        => '등록',
        'logout_title'                          => '종료',
        'cancel_text'                           => '취소',
        'save_text'                             => '저장',
        'more_text'                             => '추가',
        'processing_in_text'                    => '처리 중...',
        'upload_in_text'                        => '업로드 중...',
        'navigation_main_quick_name'            => '보물상자',
        'no_relevant_data_tips'                 => '관련 데이터 없음',
        'avatar_upload_title'                   => '프로필 사진 업로드',
        'choice_images_text'                    => '그림 선택',
        'choice_images_error_tips'              => '업로드할 그림을 선택하세요',
        'confirm_upload_title'                  => '업로드 확인',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => '환영합니다',
        'header_top_nav_left_login_first'       => '안녕하세요',
        'header_top_nav_left_login_last'        => ', 오신 걸 환영합니다',
        // 搜索
        'search_input_placeholder'              => '사실 검색은 쉬워요 ^ _ ^!',
        'search_button_text'                    => '검색',
        // 用户
        'avatar_upload_tips'                    => [
            '작업 영역에서 커버를 확대 / 축소 및 이동하고 트림할 범위를 선택하여 트림의 폭과 비율을 고정합니다.',
            '잘라낸 효과는 오른쪽 미리보기에 표시되며 제출을 확인한 후 적용됩니다.',
        ],
        'close_user_register_tips'              => '사용자 등록 일시 해제',
        'close_user_login_tips'                 => '사용자 로그인 일시 닫기',
        // 底部
        'footer_icp_filing_text'                => 'ICP 등록',
        'footer_public_security_filing_text'    => '공안 등록',
        'footer_business_license_text'          => '전자 영업 허가증',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => '안녕하세요, 어서 오세요',
        'banner_right_article_title'            => '뉴스 헤드라인',
        'design_browser_seo_title'              => '홈 디자인',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => '댓글 데이터 없음',
        ],
        // 基础
        'goods_no_data_tips'                    => '상품이 없거나 삭제됨',
        'panel_can_choice_spec_name'            => '옵션 사양',
        'recommend_goods_title'                 => '보고 또 보고',
        'dynamic_scoring_name'                  => '동적 채점',
        'no_scoring_data_tips'                  => '채점 데이터 없음',
        'no_comments_data_tips'                 => '평가 자료 없음',
        'comments_first_name'                   => '의견',
        'admin_reply_name'                      => '관리자 응답:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => '상품 검색',
        'filter_out_first_text'                 => '필터링',
        'filter_out_last_data_text'             => '스트라이프 데이터',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => '상품 분류',
        'no_category_data_tips'                 => '분류 데이터 없음',
        'no_sub_category_data_tips'             => '하위 분류 데이터 없음',
        'view_category_sub_goods_name'          => '분류된 품목 보기',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => '상품을 선택하세요',
        ],
        // 基础
        'browser_seo_title'                     => '쇼핑 카트',
        'goods_list_thead_base'                 => '상품 정보',
        'goods_list_thead_price'                => '단가',
        'goods_list_thead_number'               => '수량',
        'goods_list_thead_total'                => '금액',
        'goods_item_total_name'                 => '합계',
        'summary_selected_goods_name'           => '선택한 품목',
        'summary_selected_goods_unit'           => '부품',
        'summary_nav_goods_total'               => '합계:',
        'summary_nav_button_name'               => '결제',
        'no_cart_data_tips'                     => '카트가 아직 비어 있습니다.',
        'no_cart_data_my_favor_name'            => '내 즐겨찾기',
        'no_cart_data_my_order_name'            => '내 주문',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => '주소를 선택하십시오.',
            'payment_choice_tips'               => '결제 선택',
        ],
        // 基础
        'browser_seo_title'                     => '주문 확인',
        'exhibition_not_allow_submit_tips'      => '프리젠테이션에서는 주문을 제출할 수 없습니다.',
        'buy_item_order_title'                  => '주문 정보',
        'buy_item_payment_title'                => '결제 선택',
        'confirm_delivery_address_name'         => '수령 주소 확인',
        'use_new_address_name'                  => '새 주소 사용',
        'no_delivery_address_tips'              => '배송 주소 없음',
        'confirm_extraction_address_name'       => '프록시 주소 확인',
        'choice_take_address_name'              => '수거 주소 선택',
        'no_take_address_tips'                  => '관리자에게 연락하여 프롬프트 주소 구성',
        'no_address_tips'                       => '주소 없음',
        'extraction_list_choice_title'          => '자체 제시점 선택',
        'goods_list_thead_base'                 => '상품 정보',
        'goods_list_thead_price'                => '단가',
        'goods_list_thead_number'               => '수량',
        'goods_list_thead_total'                => '금액',
        'goods_item_total_name'                 => '합계',
        'not_goods_tips'                        => '상품 없음',
        'not_payment_tips'                      => '결제 방법 없음',
        'user_message_title'                    => '구매자 의견',
        'user_message_placeholder'              => '선택, 제안 작성 및 판매자 합의 지침',
        'summary_title'                         => '실제 지불:',
        'summary_contact_name'                  => '연락처:',
        'summary_address'                       => '주소:',
        'summary_submit_order_name'             => '주문 전송',
        'payment_layer_title'                   => '지불 점프 중, 페이지를 닫지 마십시오',
        'payment_layer_content'                 => '결제 실패 또는 장시간 응답하지 않음',
        'payment_layer_order_button_text'       => '내 주문',
        'payment_layer_tips'                    => '나중에 결제 다시 시작 가능',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => '모든 기사',
        'article_no_data_tips'                  => '문서가 없거나 삭제됨',
        'article_id_params_tips'                => '기사 ID가 잘못되었습니다.',
        'release_time'                          => '출시 날짜:',
        'view_number'                           => '검색 횟수:',
        'prev_article'                          => '이전:',
        'next_article'                          => '다음:',
        'article_category_name'                 => '문장 분류',
        'article_nav_text'                      => '사이드바 탐색',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => '페이지가 없거나 삭제됨',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => '페이지가 없거나 삭제됨',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => '주문 id 오류',
            'payment_choice_tips'               => '결제 방법을 선택하십시오.',
            'rating_string'                     => '아주 나빠, 나빠, 보통, 좋아, 아주 좋아',
            'not_choice_data_tips'              => '데이터를 먼저 선택하십시오.',
            'pay_url_empty_tips'                => '결제 URL 주소가 잘못되었습니다.',
        ],
        // 基础
        'browser_seo_title'                     => '내 주문',
        'detail_browser_seo_title'              => '주문 상세 정보',
        'comments_browser_seo_title'            => '주문 검토',
        'batch_payment_name'                    => '대량 결제',
        'comments_goods_list_thead_base'        => '상품 정보',
        'comments_goods_list_thead_price'       => '단가',
        'comments_goods_list_thead_content'     => '의견 내용',
        'form_you_have_commented_tips'          => '댓글 달아주셨어요.',
        'form_payment_title'                    => '지불',
        'form_payment_no_data_tips'             => '결제 방법 없음',
        'order_base_title'                      => '주문 정보',
        'order_base_warehouse_title'            => '출하 서비스:',
        'order_base_model_title'                => '주문 모드:',
        'order_base_order_no_title'             => '주문 번호:',
        'order_base_status_title'               => '주문 상태:',
        'order_base_pay_status_title'           => '지불 상태:',
        'order_base_payment_title'              => '결제 방법:',
        'order_base_total_price_title'          => '총 주문 가격:',
        'order_base_buy_number_title'           => '구매 수량:',
        'order_base_returned_quantity_title'    => '반품 수량:',
        'order_base_user_note_title'            => '사용자 메모:',
        'order_base_add_time_title'             => '주문 시간:',
        'order_base_confirm_time_title'         => '확인 시간:',
        'order_base_pay_time_title'             => '결제 시간:',
        'order_base_delivery_time_title'        => '배송 시간:',
        'order_base_collect_time_title'         => '수거 시간:',
        'order_base_user_comments_time_title'   => '댓글 시간:',
        'order_base_cancel_time_title'          => '취소 시간:',
        'order_base_express_title'              => '택배회사:',
        'order_base_express_website_title'      => '택배 홈페이지:',
        'order_base_express_number_title'       => '송장번호:',
        'order_base_price_title'                => '총 상품 가격:',
        'order_base_increase_price_title'       => '증가 금액:',
        'order_base_preferential_price_title'   => '할인 금액:',
        'order_base_refund_price_title'         => '환불 금액:',
        'order_base_pay_price_title'            => '지불 금액:',
        'order_base_take_code_title'            => '픽업 코드:',
        'order_base_take_code_no_exist_tips'    => '픽업 코드가 없습니다. 관리자에게 문의하십시오.',
        'order_under_line_tips'                 => '현재 오프라인 결제 방법 [{:payment}], 관리자가 확인해야 효력이 발생하며, 다른 결제가 필요하면 결제를 전환하여 다시 지불을 시작할 수 있습니다.',
        'order_delivery_tips'                   => '화물은 창고에서 포장, 출고 중...',
        'order_goods_no_data_tips'              => '주문 상품 데이터 없음',
        'order_status_operate_first_tips'       => '다음을 수행할 수 있습니다.',
        'goods_list_thead_base'                 => '상품 정보',
        'goods_list_thead_price'                => '단가',
        'goods_list_thead_number'               => '수량',
        'goods_list_thead_total'                => '금액',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '기본 정보',
            'goods_placeholder'     => '주문 번호 / 제품 이름 / 모델을 입력하십시오.',
            'status'                => '주문 상태',
            'pay_status'            => '지불 상태',
            'total_price'           => '총 가격 (원)',
            'pay_price'             => '지불금액 (원)',
            'price'                 => '단가 (원)',
            'order_model'           => '주문 모드',
            'client_type'           => '주문 플랫폼',
            'address'               => '주소 정보',
            'take'                  => '수거 정보',
            'refund_price'          => '환불 금액 (위안)',
            'returned_quantity'     => '반품 수량',
            'buy_number_count'      => '총 구매',
            'increase_price'        => '증액액 (원)',
            'preferential_price'    => '할인 금액 (원)',
            'payment_name'          => '지불 방식',
            'user_note'             => '메시지 메시지',
            'extension'             => '확장 정보',
            'express_name'          => '택배 회사',
            'express_number'        => '택배 송장 번호',
            'is_comments'           => '설명 여부',
            'confirm_time'          => '확인 시간',
            'pay_time'              => '지불 시간',
            'delivery_time'         => '배송 시간',
            'collect_time'          => '완료 시간',
            'cancel_time'           => '취소 시간',
            'close_time'            => '종료 시간',
            'add_time'              => '생성 시간',
            'upd_time'              => '업데이트 시간',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => '총 주문',
            'pay_price'             => '지불 총액',
            'buy_number_count'      => '총 상품 수',
            'refund_price'          => '환불',
            'returned_quantity'     => '반품',
            'price_unit'            => '원',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => '환불 사유 데이터가 비어 있음',
        ],
        // 基础
        'browser_seo_title'                     => '주문 판매 후',
        'detail_browser_seo_title'              => '주문 판매 후 상세 정보',
        'view_orderaftersale_enter_name'        => '판매 후 주문 보기',
        'operate_delivery_name'                 => '즉시 반품',
        'goods_list_thead_base'                 => '상품 정보',
        'goods_list_thead_price'                => '단가',
        'goods_base_price_title'                => '총 상품 가격:',
        'goods_base_increase_price_title'       => '증가 금액:',
        'goods_base_preferential_price_title'   => '할인 금액:',
        'goods_base_refund_price_title'         => '환불 금액:',
        'goods_base_pay_price_title'            => '지불 금액:',
        'goods_base_total_price_title'          => '총 주문 가격:',
        'base_apply_title'                      => '신청 정보',
        'base_apply_type_title'                 => '환불 유형:',
        'base_apply_status_title'               => '현재 상태:',
        'base_apply_reason_title'               => '신청 이유:',
        'base_apply_number_title'               => '반품 수량:',
        'base_apply_price_title'                => '환불 금액:',
        'base_apply_msg_title'                  => '환불 지침:',
        'base_apply_refundment_title'           => '환불 방법:',
        'base_apply_refuse_reason_title'        => '거부 이유:',
        'base_apply_apply_time_title'           => '신청 시간:',
        'base_apply_confirm_time_title'         => '확인 시간:',
        'base_apply_delivery_time_title'        => '반품 시간:',
        'base_apply_audit_time_title'           => '감사 시간:',
        'base_apply_cancel_time_title'          => '취소 시간:',
        'base_apply_add_time_title'             => '추가 시간:',
        'base_apply_upd_time_title'             => '업데이트 시간:',
        'base_item_express_title'               => '택배 정보',
        'base_item_express_name'                => '택배:',
        'base_item_express_number'              => '단일 번호:',
        'base_item_express_time'                => '시간:',
        'base_item_voucher_title'               => '자격 증명',
        // 表单
        'form_delivery_title'                   => '반품 작업',
        'form_delivery_address_name'            => '반품 주소',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '기본 정보',
            'goods_placeholder'     => '주문 번호 / 제품 이름 / 모델을 입력하십시오.',
            'status'                => '상태',
            'type'                  => '신청 유형',
            'reason'                => '이유',
            'price'                 => '환불 금액 (위안)',
            'number'                => '반품 수량',
            'msg'                   => '환불 설명',
            'refundment'            => '환불 유형',
            'express_name'          => '택배 회사',
            'express_number'        => '택배 송장 번호',
            'apply_time'            => '신청 시간',
            'confirm_time'          => '확인 시간',
            'delivery_time'         => '반품 기간',
            'audit_time'            => '감사 시간',
            'add_time'              => '생성 시간',
            'upd_time'              => '업데이트 시간',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => '사용자 센터',
        'forget_password_browser_seo_title'     => '비밀번호 찾기',
        'user_register_browser_seo_title'       => '사용자 등록',
        'user_login_browser_seo_title'          => '사용자 로그인',
        'password_reset_illegal_error_tips'     => '로그인했습니다. 비밀번호를 재설정하려면 현재 계정을 종료하십시오.',
        'register_illegal_error_tips'           => '로그인했습니다. 새 계정을 등록하려면 현재 계정을 종료하십시오.',
        'login_illegal_error_tips'              => '로그인했습니다. 다시 로그인하지 마십시오.',
        // 页面
        // 登录
        'login_top_register_tips'               => '아직 계좌번호 없어요?',
        'login_close_tips'                      => '로그인 일시 종료',
        'login_type_username_title'             => '계정 비밀번호',
        'login_type_mobile_title'               => '휴대폰 인증번호',
        'login_type_email_title'                => '메일박스 인증 코드',
        'login_retrieve_password_title'         => '비밀번호 찾기',
        // 注册
        'register_top_login_tips'               => '등록했어요 지금 당장요',
        'register_close_tips'                   => '등록 일시 종료',
        'register_type_username_title'          => '계정 등록',
        'register_type_mobile_title'            => '휴대폰 등록',
        'register_type_email_title'             => '메일박스 등록',
        // 忘记密码
        'forget_password_top_login_tips'        => '계정이 있습니까?',
        // 表单
        'form_item_agreement'                   => '읽고 동의',
        'form_item_agreement_message'           => '동의 계약서 선택',
        'form_item_service'                     => '서비스 계약',
        'form_item_privacy'                     => '프라이버시 정책',
        'form_item_username'                    => '사용자 이름',
        'form_item_username_message'            => '문자, 숫자, 밑줄 2~18자를 사용하십시오.',
        'form_item_password'                    => '로그인 암호',
        'form_item_password_placeholder'        => '로그인 암호를 입력하십시오.',
        'form_item_password_message'            => '암호 형식 6~18자 사이',
        'form_item_mobile'                      => '핸드폰 번호',
        'form_item_mobile_placeholder'          => '핸드폰 번호를 입력하세요.',
        'form_item_mobile_message'              => '휴대폰 번호 형식 오류',
        'form_item_email'                       => '이메일',
        'form_item_email_placeholder'           => '이메일을 입력하십시오.',
        'form_item_email_message'               => '이메일 형식 오류',
        'form_item_account'                     => '로그인 계정',
        'form_item_account_placeholder'         => '사용자 이름 / 휴대폰 / 사서함 을 입력하십시오.',
        'form_item_account_message'             => '로그인 계정을 입력하세요',
        'form_item_mobile_email'                => '휴대폰 / 메일박스',
        'form_item_mobile_email_message'        => '올바른 휴대폰 / 메일박스 형식을 입력하십시오.',
        // 个人中心
        'base_avatar_title'                     => '아바타 수정',
        'base_personal_title'                   => '자료 수정',
        'base_address_title'                    => '내 주소',
        'base_message_title'                    => '메시지',
        'order_nav_title'                       => '내 주문',
        'order_nav_angle_title'                 => '모든 주문 보기',
        'various_transaction_title'             => '거래 알림',
        'various_transaction_tips'              => '거래 알림은 주문 상태와 물류 상황을 이해하는 데 도움이 됩니다.',
        'various_cart_title'                    => '쇼핑 카트',
        'various_cart_empty_title'              => '카트가 아직 비어 있습니다.',
        'various_cart_tips'                     => '사고 싶은 상품을 카트에 넣어서 같이 결제하는 게 편해요.',
        'various_favor_title'                   => '상품 컬렉션',
        'various_favor_empty_title'             => '당신은 아직 상품을 소장하지 않았습니다',
        'various_favor_tips'                    => '소장한 상품은 최신 판촉 활동과 가격 인하 상황을 나타낼 것이다',
        'various_browse_title'                  => '나의 발자취',
        'various_browse_empty_title'            => '품목 조회 기록이 비어 있음',
        'various_browse_tips'                   => '빨리 쇼핑몰에 가서 판촉 행사를 보세요.',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => '내 주소',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => '나의 발자취',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '상품 정보',
            'goods_placeholder'     => '상품명 / 약술 / SEO 정보를 입력하십시오.',
            'price'                 => '판매가격 (원)',
            'original_price'        => '원가 (원)',
            'add_time'              => '생성 시간',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => '상품 컬렉션',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '상품 정보',
            'goods_placeholder'     => '상품명 / 약술 / SEO 정보를 입력하십시오.',
            'price'                 => '판매가격 (원)',
            'original_price'        => '원가 (원)',
            'add_time'              => '생성 시간',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => '내 포인트',
        // 页面
        'base_normal_title'                     => '정상적으로 사용 가능',
        'base_normal_tips'                      => '정상적으로 사용할 수 있는 포인트',
        'base_locking_title'                    => '현재 잠금',
        'base_locking_tips'                     => '일반 포인트 거래 중, 거래가 완성되지 않아 상응하는 포인트를 고정',
        'base_integral_unit'                    => '포인트',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => '작업 유형',
            'operation_integral'    => '운영 포인트',
            'original_integral'     => '원시 적분',
            'new_integral'          => '최신 포인트',
            'msg'                   => '설명',
            'add_time_time'         => '시간',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => '프로필',
        'edit_browser_seo_title'                => '프로필 편집',
        'form_item_nickname'                    => '닉네임',
        'form_item_nickname_message'            => '닉네임 2~16자 사이',
        'form_item_birthday'                    => '생일',
        'form_item_birthday_message'            => '생일 형식이 틀렸다',
        'form_item_province'                    => '소재 성',
        'form_item_province_message'            => '최대 30자 이하',
        'form_item_city'                        => '소재 시',
        'form_item_city_message'                => '시 최대 30자',
        'form_item_county'                      => '지역 / 군',
        'form_item_county_message'              => '지역 / 카운티 최대 30자',
        'form_item_address'                     => '상세 주소',
        'form_item_address_message'             => '상세 주소 2~30자',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => '내 메시지',
        // 动态表格
        'form_table'                => [
            'type'                  => '메시지 유형',
            'business_type'         => '비즈니스 유형',
            'title'                 => '제목',
            'detail'                => '상세 정보',
            'is_read'               => '상태',
            'add_time_time'         => '시간',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => '질문 / 메시지',
        // 表单
        'form_title'                            => '질문 / 메시지',
        'form_item_name'                        => '닉네임',
        'form_item_name_message'                => '닉네임 형식 1~30자 사이',
        'form_item_tel'                         => '전화기',
        'form_item_tel_message'                 => '전화번호를 기입해 주십시오.',
        'form_item_title'                       => '제목',
        'form_item_title_message'               => '머리글 형식 ~ 60자 사이',
        'form_item_content'                     => '내용',
        'form_item_content_message'             => '컨텐츠 형식 5 ~ 100자',
        // 动态表格
        'form_table'                            => [
            'name'                  => '연락처',
            'tel'                   => '연락처',
            'content'               => '내용',
            'reply'                 => '회신 내용',
            'reply_time_time'       => '회신 시간',
            'add_time_time'         => '생성 시간',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => '보안 설정',
        'password_update_browser_seo_title'     => '로그인 암호 수정 - 보안 설정',
        'mobile_update_browser_seo_title'       => '휴대폰 번호 수정 - 보안 설정',
        'email_update_browser_seo_title'        => '이메일 수정 - 보안 설정',
        'logout_browser_seo_title'              => '계정 로그아웃 - 보안 설정',
        'original_account_check_error_tips'     => '원래 계정 확인 실패',
        // 页面
        'logout_title'                          => '계정 로그아웃',
        'logout_confirm_title'                  => '로그아웃 확인',
        'logout_confirm_tips'                   => '계정 로그아웃 후 복구 불가, 계속하시겠습니까?',
        'email_title'                           => '원래 이메일 검사',
        'email_new_title'                       => '새 이메일 확인',
        'mobile_title'                          => '원래 핸드폰 번호 검사',
        'mobile_new_title'                      => '새 핸드폰 번호 검사',
        'login_password_title'                  => '로그인 암호 수정',
    ],
];
?>