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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => '주문 거래 금액 추이',
            'order_trading_trend_name'          => '주문 거래 동향',
            'goods_hot_name'                    => '인기 상품',
            'goods_hot_tips'                    => '처음 30개 품목만 표시',
            'payment_name'                      => '지불 방식',
            'order_region_name'                 => '주문 지역 분포',
            'order_region_tips'                 => '30개의 데이터만 표시',
            'upgrade_check_loading_tips'        => '최신 콘텐츠를 가져오는 중입니다. 잠시 기다려 주십시오...',
            'upgrade_version_name'              => '업데이트 버전:',
            'upgrade_date_name'                 => '업데이트 날짜:',
        ],
        // 页面基础
        'base_update_button_title'              => '지금 업데이트',
        'base_item_base_stats_title'            => '쇼핑몰 통계',
        'base_item_base_stats_tips'             => '시간 필터링은 총 수량에만 유효합니다.',
        'base_item_user_title'                  => '총 사용자 수',
        'base_item_order_number_title'          => '총 주문',
        'base_item_order_complete_number_title' => '거래 총량',
        'base_item_order_complete_title'        => '총 주문',
        'base_item_last_month_title'            => '지난달',
        'base_item_same_month_title'            => '당월',
        'base_item_yesterday_title'             => '어제',
        'base_item_today_title'                 => '오늘',
        'base_item_order_profit_title'          => '주문 거래 금액 추이',
        'base_item_order_trading_title'         => '주문 거래 동향',
        'base_item_order_tips'                  => '모든 주문',
        'base_item_hot_sales_goods_title'       => '인기 상품',
        'base_item_hot_sales_goods_tips'        => '취소 닫힌 주문 없음',
        'base_item_payment_type_title'          => '지불 방식',
        'base_item_map_whole_country_title'     => '주문 지역 분포',
        'base_item_map_whole_country_tips'      => '취소 닫기 주문, 기본 차원 (절) 없음',
        'base_item_map_whole_country_province'  => '성',
        'base_item_map_whole_country_city'      => '시',
        'base_item_map_whole_country_county'    => '구 / 군',
        'system_info_title'                     => '시스템 정보',
        'system_ver_title'                      => '소프트웨어 버전',
        'system_os_ver_title'                   => '운영 체제',
        'system_php_ver_title'                  => 'PHP 버전',
        'system_mysql_ver_title'                => 'MySQL 버전',
        'system_server_ver_title'               => '서버측 정보',
        'system_host_title'                     => '현재 도메인 이름',
        'development_team_title'                => '개발팀',
        'development_team_website_title'        => '회사 홈페이지',
        'development_team_website_value'        => '상해종지격과학기술유한공사',
        'development_team_support_title'        => '기술 지원',
        'development_team_support_value'        => 'ShopXO 엔터프라이즈급 전자상거래 시스템 공급업체',
        'development_team_ask_title'            => '질문을 주고받다',
        'development_team_ask_value'            => 'ShopXO 채팅 질문',
        'development_team_agreement_title'      => '오픈 소스 프로토콜',
        'development_team_agreement_value'      => '오픈 소스 계약 보기',
        'development_team_update_log_title'     => '로그 업데이트',
        'development_team_update_log_value'     => '업데이트 로그 보기',
        'development_team_members_title'        => 'R&D 멤버',
        'development_team_members_value'        => [
            ['name' => '공 형', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => '사용자',
        // 动态表格
        'form_table'                            => [
            'id'                    => '사용자 ID',
            'number_code'           => '회원 번호',
            'system_type'           => '시스템 유형',
            'platform'              => '소유 플랫폼',
            'avatar'                => '프로필 사진',
            'username'              => '사용자 이름',
            'nickname'              => '닉네임',
            'mobile'                => '핸드폰',
            'email'                 => '메일박스',
            'gender_name'           => '성별',
            'status_name'           => '상태',
            'province'              => '소재 성',
            'city'                  => '소재 시',
            'county'                => '지역 / 군',
            'address'               => '상세 주소',
            'birthday'              => '생일',
            'integral'              => '사용 가능한 포인트',
            'locking_integral'      => '포인트 잠금',
            'referrer'              => '사용자 초대',
            'referrer_placeholder'  => '초대 사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'add_time'              => '등록 시간',
            'upd_time'              => '업데이트 시간',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => '사용자 주소',
        // 详情
        'detail_user_address_idcard_name'       => '이름',
        'detail_user_address_idcard_number'     => '번호',
        'detail_user_address_idcard_pic'        => '사진',
        // 动态表格
        'form_table'                            => [
            'user'              => '사용자 정보',
            'user_placeholder'  => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'alias'             => '별칭',
            'name'              => '연락처',
            'tel'               => '연락처',
            'province_name'     => '소속 성',
            'city_name'         => '소속 시',
            'county_name'       => '구 / 군',
            'address'           => '상세 주소',
            'position'          => '경위도',
            'idcard_info'       => '신분증 정보',
            'is_default'        => '기본값 여부',
            'add_time'          => '생성 시간',
            'upd_time'          => '업데이트 시간',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => '제거 후 저장이 유효합니다. 계속하시겠습니까?',
            'address_no_data'                   => '주소 데이터가 비어 있음',
            'address_not_exist'                 => '주소가 없습니다.',
            'address_logo_message'              => '로고 사진 올려주세요.',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => '기본 구성', 'type' => 'base'],
            ['name' => '사이트 설정', 'type' => 'siteset'],
            ['name' => '사이트 유형', 'type' => 'sitetype'],
            ['name' => '사용자 등록', 'type' => 'register'],
            ['name' => '사용자 로그인', 'type' => 'login'],
            ['name' => '비밀번호 찾기', 'type' => 'forgetpwd'],
            ['name' => '인증 코드', 'type' => 'verify'],
            ['name' => '주문 판매 후', 'type' => 'orderaftersale'],
            ['name' => '첨부 파일', 'type' => 'attachment'],
            ['name' => '캐시', 'type' => 'cache'],
            ['name' => '확장 항목', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => '첫 페이지', 'type' => 'index'],
            ['name' => '상품', 'type' => 'goods'],
            ['name' => '검색', 'type' => 'search'],
            ['name' => '주문', 'type' => 'order'],
            ['name' => '혜택', 'type' => 'discount'],
            ['name' => '확장', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => '사이트 상태',
        'base_item_site_domain_title'           => '사이트 도메인 이름 주소',
        'base_item_site_filing_title'           => '등록 정보',
        'base_item_site_other_title'            => '기타',
        'base_item_session_cache_title'         => 'Session 캐시 구성',
        'base_item_data_cache_title'            => '데이터 캐시 구성',
        'base_item_redis_cache_title'           => 'redis 캐시 구성',
        'base_item_crontab_config_title'        => '타이밍 스크립트 설정',
        'base_item_quick_nav_title'             => '빠른 탐색',
        'base_item_user_base_title'             => '사용자 기반',
        'base_item_user_address_title'          => '사용자 주소',
        'base_item_multilingual_title'          => '다국어',
        'base_item_site_auto_mode_title'        => '자동 모드',
        'base_item_site_manual_mode_title'      => '수동 모드',
        'base_item_default_payment_title'       => '기본 결제 방법',
        'base_item_display_type_title'          => '전시형',
        'base_item_self_extraction_title'       => '자제점',
        'base_item_fictitious_title'            => '가상 영업',
        'choice_upload_logo_title'              => '로고 선택',
        'add_goods_title'                       => '상품 추가',
        'add_self_extractio_address_title'      => '주소 추가',
        'site_domain_tips_list'                 => [
            '1. 사이트 도메인 이름이 설정되지 않은 경우 현재 사이트 도메인 이름 도메인 이름 주소 사용[ '.__MY_DOMAIN__.' ]',
            '2. 첨부 파일 및 정적 주소가 설정되지 않은 경우 현재 사이트 정적 도메인 이름 주소 사용[ '.__MY_PUBLIC_URL__.' ]',
            '3. 서버가 public을 루트로 설정하지 않은 경우 [첨부 파일 cdn 도메인 이름, css/js 정적 파일 cdn 도메인 이름] 을 구성하려면 다음에 public을 추가해야 합니다. 예:'.__MY_PUBLIC_URL__.'public/',
            '4. 명령줄 모드에서 프로젝트를 실행합니다. 이 영역 주소는 반드시 설정해야 합니다. 그렇지 않으면 프로젝트의 일부 주소에서 도메인 이름 정보가 누락됩니다.',
            '5. 함부로 설정하지 마십시오. 잘못된 주소는 웹 사이트에 접근할 수 없습니다. (주소 설정은 http로 시작합니다.) 자신이 설정한 https는 https로 시작합니다.',
        ],
        'site_cache_tips_list'                  => [
            '1. 기본적으로 사용되는 파일 캐시, Redis 캐시 PHP를 사용하려면 먼저 Redis 확장을 설치해야 합니다.',
            '2. Redis 서비스 안정성 확인(Session이 캐시를 사용한 후 서비스가 불안정하면 백그라운드에서도 로그인할 수 없음)',
            '3. Redis 서비스 예외로 관리 백그라운드에 로그인할 수 없는 경우 구성 파일 [config] 디렉토리에서 [session.php, cache.php] 파일을 수정합니다.',
        ],
        'goods_tips_list'                       => [
            '1.WEB 엔드 기본 프리젠테이션 레벨 3, 최소 레벨 1, 최대 레벨 3(레벨 0으로 설정하면 기본 레벨 3)',
            '2.휴대폰 기본 전시 0급(상품목록 모드), 최저 0급, 최고 3급(1~3은 분류 전시 모드)',
            '3. 계층이 다르고 프런트엔드 분류 페이지 스타일이 다를 수 있음',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. 층별로 최대 몇 개의 상품을 전시할지 구성',
            '2. 수량을 너무 크게 수정하는 것을 권장하지 않으며, PC의 왼쪽 공백을 너무 크게 만들 수 있다',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            '종합: 열도->판매량->최신 내림차순(desc) 정렬',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1.상품 제목을 클릭하여 드래그 정렬, 순서대로 전시 가능',
            '2. 많은 상품을 추가하는 것을 권장하지 않으며 PC의 왼쪽 공백이 너무 클 수 있습니다',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1.기본값은 [사용자 이름, 휴대폰, 메일박스]를 사용자 고유',
            '2. 켜면 【시스템 ID】를 추가하여 병렬로 사용자 유일',
        ],
        'extends_crontab_tips'                  => 'linux 타이밍 작업 타이밍 요청에 스크립트 주소를 추가하는 것이 좋습니다. (결과 sucs: 0, fail: 0 콜론 뒤에는 처리된 데이터 막대, sucs 성공, fali 실패)',
        'left_images_random_tips'               => '왼쪽 사진은 최대 3장의 이미지를 업로드할 수 있으며, 한 번에 한 장씩 랜덤으로 전시할 수 있다',
        'background_color_tips'                 => '배경 그림 사용자 정의 가능, 기본 바탕 회색',
        'site_setup_layout_tips'                => '드래그 모드는 첫 번째 디자인 페이지로 이동해야 합니다. 선택한 구성을 저장한 다음',
        'site_setup_layout_button_name'         => '페이지 디자인 > >',
        'site_setup_goods_category_tips'        => '더 많은 층을 전시하려면 먼저 / 상품 관리 -> 상품 분류, 1급 분류 설정 첫 페이지 추천',
        'site_setup_goods_category_no_data_tips'=> '데이터가 없습니다. 먼저 / 상품 관리 -> 상품 분류, 1급 분류 설정 첫 페이지 추천',
        'site_setup_order_default_payment_tips' => '서로 다른 플랫폼에 해당하는 기본 결제 방법을 설정할 수 있습니다. [사이트 관리->결제 방법]에 결제 플러그인을 설치하여 사용자에게 개방하십시오.',
        'site_setup_choice_payment_message'     => '{:name} 기본 결제 방법을 선택하십시오.',
        'sitetype_top_tips_list'                => [
            '1.택배, 일반 전자상거래 절차, 사용자가 수령 주소를 선택하여 지불-> 상가 발송-> 수령 확인-> 주문 완료',
            '2. 전시형, 제품만 전시, 상담 가능(주문 불가)',
            '3.셀프 인출점, 주문 시 셀프 인출 화물 주소 선택, 사용자 주문 지불 -> 인출 확인 -> 주문 완료',
            '4.가상 판매, 일반 전자상거래 프로세스, 사용자 주문 지불 -> 자동 발송 -> 수거 확인 -> 주문 완료',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => '권장 300 * 300px',
        'form_take_address_alias'               => '별칭',
        'form_take_address_alias_message'       => '앨리어스 형식 최대 16자',
        'form_take_address_name'                => '연락처',
        'form_take_address_name_message'        => '2~16자 사이의 연락처 형식',
        'form_take_address_tel'                 => '연락처',
        'form_take_address_tel_message'         => '연락처 입력',
        'form_take_address_address'             => '상세 주소',
        'form_take_address_address_message'     => '상세 주소 형식 1~80자',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => '백그라운드 로그인',
        'admin_login_info_bg_images_list_tips'  => [
            '1. 배경 그림은 [public/static/admin/default/images/login] 디렉토리에 있음',
            '2. 배경 그림 명명 규칙 (1~50), 예를 들어 1.jpg',
        ],
        'map_type_tips'                         => '각 집의 지도 표준이 다르기 때문에, 마음대로 지도를 전환하지 마십시오. 지도 좌표 표기가 정확하지 않은 상황을 초래할 수 있습니다.',
        'apply_map_baidu_name'                  => '바이두 지도 오픈 플랫폼으로 신청하세요',
        'apply_map_amap_name'                   => '고덕지도 오픈 플랫폼에서 신청하세요',
        'apply_map_tencent_name'                => '텐센트 지도 오픈 플랫폼으로 신청하세요',
        'apply_map_tianditu_name'               => '천지도 오픈 플랫폼에서 신청하세요',
        'cookie_domain_list_tips'               => [
            '1. 기본적으로 비어 있으면 현재 액세스 도메인 이름에만 유효',
            '2. 2단계 도메인 이름과 공유 쿠키가 필요한 경우 최상위 도메인 이름(예: baidu.com)을 입력합니다.',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => '브랜드',
        // 动态表格
        'form_table'                            => [
            'name'                 => '이름',
            'describe'             => '설명',
            'logo'                 => 'LOGO',
            'url'                  => '홈페이지 주소',
            'brand_category_text'  => '브랜드 분류',
            'is_enable'            => '사용 여부',
            'sort'                 => '정렬',
            'add_time'             => '생성 시간',
            'upd_time'             => '업데이트 시간',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => '브랜드 분류',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => '기사',
        'detail_content_title'                  => '자세한 내용',
        'detail_images_title'                   => '상세 이미지',
        // 动态表格
        'form_table'                            => [
            'info'                   => '제목',
            'jump_url'               => 'URL 주소 건너뛰기',
            'article_category_name'  => '분류',
            'is_enable'              => '사용 여부',
            'is_home_recommended'    => '홈페이지 추천',
            'images_count'           => '그림 수',
            'access_count'           => '방문 횟수',
            'add_time'               => '생성 시간',
            'upd_time'               => '업데이트 시간',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => '문장 분류',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => '사용자 지정 페이지',
        'detail_content_title'                  => '자세한 내용',
        'detail_images_title'                   => '상세 이미지',
        'save_view_tips'                        => '효과를 미리 보기 전에 저장하십시오.',
        // 动态表格
        'form_table'                            => [
            'info'            => '제목',
            'is_enable'       => '사용 여부',
            'is_header'       => '머리 여부',
            'is_footer'       => '끝부분 여부',
            'is_full_screen'  => '전체 화면',
            'images_count'    => '그림 수',
            'access_count'    => '방문 횟수',
            'add_time'        => '생성 시간',
            'upd_time'        => '업데이트 시간',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => '더 많은 디자인 템플릿 다운로드',
        'upload_list_tips'                      => [
            '1. 다운로드한 페이지 디자인 zip 패키지 선택',
            '2. 가져오기를 통해 자동으로 새 데이터 생성',
        ],
        'operate_sync_tips'                     => '데이터가 첫 번째 페이지의 드래그 시각 형상에 동기화되고 나중에 수정되더라도 관련 첨부 파일은 삭제하지 마십시오.',
        // 动态表格
        'form_table'                            => [
            'id'                => '데이터 ID',
            'info'              => '기본 정보',
            'info_placeholder'  => '이름을 입력하십시오.',
            'access_count'      => '방문 횟수',
            'is_enable'         => '사용 여부',
            'is_header'         => '머리 포함 여부',
            'is_footer'         => '꼬리 포함 여부',
            'seo_title'         => 'SEO 제목',
            'seo_keywords'      => 'SEO 키워드',
            'seo_desc'          => 'SEO 설명',
            'add_time'          => '생성 시간',
            'upd_time'          => '업데이트 시간',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => '문답',
        'user_info_title'                       => '사용자 정보',
        // 动态表格
        'form_table'                            => [
            'user'              => '사용자 정보',
            'user_placeholder'  => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'name'              => '연락처',
            'tel'               => '연락처',
            'content'           => '내용',
            'reply'             => '회신 내용',
            'is_show'           => '표시 여부',
            'is_reply'          => '응답 여부',
            'reply_time_time'   => '회신 시간',
            'access_count'      => '방문 횟수',
            'add_time_time'     => '생성 시간',
            'upd_time_time'     => '업데이트 시간',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => '창고',
        'top_tips_list'                         => [
            '1.가중치 수치가 클수록 가중치가 높음을 나타낸다.재고를 공제하면 가중치에 따라 순차적으로 공제한다)',
            '2. 창고는 소프트만 삭제, 삭제 후 사용할 수 없음, 데이터베이스에만 데이터 보존) 관련된 상품 데이터는 스스로 삭제할 수 있음',
            '3. 창고 비활성화 및 삭제, 관련 상품 재고는 즉시 방출',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => '이름 / 별칭',
            'level'          => '가중치',
            'is_enable'      => '사용 여부',
            'contacts_name'  => '연락처',
            'contacts_tel'   => '연락처',
            'province_name'  => '소재 성',
            'city_name'      => '소재 시',
            'county_name'    => '지역 / 군',
            'address'        => '상세 주소',
            'position'       => '경위도',
            'add_time'       => '생성 시간',
            'upd_time'       => '업데이트 시간',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => '저장소를 선택하십시오.',
        ],
        // 基础
        'add_goods_title'                       => '상품 추가',
        'no_spec_data_tips'                     => '사양 데이터 없음',
        'batch_setup_inventory_placeholder'     => '대량 설정 값',
        'base_spec_inventory_title'             => '사양 인벤토리',
        // 动态表格
        'form_table'                            => [
            'goods'              => '기본 정보',
            'goods_placeholder'  => '상품명 / 모델을 입력하십시오.',
            'warehouse_name'     => '창고',
            'is_enable'          => '사용 여부',
            'inventory'          => '총 재고',
            'spec_inventory'     => '사양 인벤토리',
            'add_time'           => '생성 시간',
            'upd_time'           => '업데이트 시간',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => '관리자 정보가 없습니다.',
        // 列表
        'top_tips_list'                         => [
            '1. admin 계정은 기본적으로 모든 권한을 가지고 있으며 변경할 수 없습니다.',
            '2. admin 계정은 변경할 수 없지만 데이터 테이블에서 수정할 수 있습니다('.MyConfig('database.connections.mysql.prefix').'admin) 필드 username',
        ],
        'base_nav_title'                        => '관리자',
        // 登录
        'login_type_username_title'             => '계정 비밀번호',
        'login_type_mobile_title'               => '휴대폰 인증번호',
        'login_type_email_title'                => '메일박스 인증 코드',
        'login_close_tips'                      => '로그인 일시 종료',
        // 忘记密码
        'form_forget_password_name'             => '비밀번호를 잊으셨습니까?',
        'form_forget_password_tips'             => '비밀번호를 재설정하려면 관리자에게 문의하십시오.',
        // 动态表格
        'form_table'                            => [
            'username'              => '관리자',
            'status'                => '상태',
            'gender'                => '성별',
            'mobile'                => '핸드폰',
            'email'                 => '메일박스',
            'role_name'             => '역할 그룹',
            'login_total'           => '로그인 횟수',
            'login_time'            => '마지막 로그인 시간',
            'add_time'              => '생성 시간',
            'upd_time'              => '업데이트 시간',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '사용자 등록 계약', 'type' => 'register'],
            ['name' => '사용자 개인 정보 보호 정책', 'type' => 'privacy'],
            ['name' => '계정 로그아웃 프로토콜', 'type' => 'logout']
        ],
        'top_tips'          => '프런트엔드 액세스 프로토콜 주소 증가 매개 변수 is _content=1은 순수 프로토콜 내용만 표시',
        'view_detail_name'                      => '자세히 보기',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '기본 구성', 'type' => 'index'],
            ['name' => 'APP/애플릿', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '현재 주제', 'type' => 'index'],
            ['name' => '테마 설치', 'type' => 'upload'],
            ['name' => '소스 패키지 다운로드', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => '추가 테마 다운로드',
        'nav_theme_download_name'               => '애플릿 패키지 자습서 보기',
        'nav_theme_download_tips'               => '모바일 테마는 uniapp 개발 (멀티엔드 애플릿 + H5 지원) 으로 앱도 긴급 적응 중이다.',
        'form_alipay_extend_title'              => '고객 서비스 구성',
        'form_alipay_extend_tips'               => 'PS: 【APP/애플릿】에서 오픈(온라인 고객 서비스 오픈)하는 경우 다음 구성은 [엔터프라이즈 코드] 및 [채팅창 코드] 필수',
        'form_theme_upload_tips'                => 'zip 압축 형식의 설치 패키지 업로드',
        'list_no_data_tips'                     => '관련 항목 패키지 없음',
        'list_author_title'                     => '작성자',
        'list_version_title'                    => '적용 가능한 버전',
        'package_generate_tips'                 => '생성 시간이 길기 때문에 브라우저 창을 닫지 마십시오!',
        // 动态表格
        'form_table'                            => [
            'name'  => '패키지 이름',
            'size'  => '크기',
            'url'   => '다운로드 주소',
            'time'  => '생성 시간',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '문자 설정', 'type' => 'index'],
            ['name' => '메시지 템플릿', 'type' => 'message'],
        ],
        'top_tips'                              => '알리 클라우드 문자 관리 주소',
        'top_to_aliyun_tips'                    => '알리 클라우드로 가서 문자 구매하기 클릭',
        'base_item_admin_title'                 => '백그라운드',
        'base_item_index_title'                 => '프런트엔드',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '사서함 설정', 'type' => 'index'],
            ['name' => '메시지 템플릿', 'type' => 'message'],
        ],
        'top_tips'                              => '서로 다른 사서함 플랫폼에 약간의 차이가 존재하고 설정도 다르기 때문에 구체적으로 사서함 플랫폼 구성 튜토리얼을 기준으로 한다',
        // 基础
        'test_title'                            => '테스트',
        'test_content'                          => '메시지 구성 - 테스트 내용 보내기',
        'base_item_admin_title'                 => '백그라운드',
        'base_item_index_title'                 => '프런트엔드',
        // 表单
        'form_item_test'                        => '받은 메일 주소 테스트',
        'form_item_test_tips'                   => '테스트를 수행하기 전에 구성을 저장하십시오.',
        'form_item_test_button_title'           => '테스트',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => '서버 환경 [Nginx, Apache, IIS]에 따라 서로 다른 의사 정적 규칙 구성',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => '상품',
        'goods_nav_list'                        => [
            'base'            => ['name' => '기본 정보', 'type'=>'base'],
            'specifications'  => ['name' => '상품 규격', 'type'=>'specifications'],
            'parameters'      => ['name' => '상품 매개 변수', 'type'=>'parameters'],
            'photo'           => ['name' => '상품 앨범', 'type'=>'photo'],
            'video'           => ['name' => '상품 비디오', 'type'=>'video'],
            'app'             => ['name' => '휴대폰 상세 정보', 'type'=>'app'],
            'web'             => ['name' => '컴퓨터 상세 정보', 'type'=>'web'],
            'fictitious'      => ['name' => '가상 정보', 'type'=>'fictitious'],
            'extends'         => ['name' => '확장 데이터', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO 정보', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => '상품 ID',
            'info'                    => '상품 정보',
            'category_text'           => '상품 분류',
            'brand_name'              => '브랜드',
            'price'                   => '판매가격 (원)',
            'original_price'          => '원가 (원)',
            'inventory'               => '재고 총량',
            'is_shelves'              => '상하가',
            'is_deduction_inventory'  => '재고를 삭감하다.',
            'site_type'               => '상품 유형',
            'model'                   => '상품 모델',
            'place_origin_name'       => '생산지',
            'give_integral'           => '구매 증정 포인트 비율',
            'buy_min_number'          => '1회 최소 구매 수량',
            'buy_max_number'          => '1회 최대 구매 수량',
            'sales_count'             => '판매량',
            'access_count'            => '방문 횟수',
            'add_time'                => '생성 시간',
            'upd_time'                => '업데이트 시간',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => '상품 분류',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => '상품 리뷰',
        // 动态表格
        'form_table'                            => [
            'user'               => '사용자 정보',
            'user_placeholder'   => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'goods'              => '기본 정보',
            'goods_placeholder'  => '상품명 / 모델을 입력하십시오.',
            'business_type'      => '비즈니스 유형',
            'content'            => '의견 내용',
            'images'             => '댓글 그림',
            'rating'             => '채점',
            'reply'              => '회신 내용',
            'is_show'            => '표시 여부',
            'is_anonymous'       => '익명 여부',
            'is_reply'           => '응답 여부',
            'reply_time_time'    => '회신 시간',
            'add_time_time'      => '생성 시간',
            'upd_time_time'      => '업데이트 시간',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => '상품 매개 변수',
        // 动态表格
        'form_table'                            => [
            'category_id'   => '상품 분류',
            'name'          => '이름',
            'is_enable'     => '사용 여부',
            'config_count'  => '매개변수 수',
            'add_time'      => '생성 시간',
            'upd_time'      => '업데이트 시간',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => '상품 분류',
            'name'         => '이름',
            'is_enable'    => '사용 여부',
            'content'      => '사양값',
            'add_time'     => '생성 시간',
            'upd_time'     => '업데이트 시간',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => '사용자 정보',
            'user_placeholder'   => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'goods'              => '상품 정보',
            'goods_placeholder'  => '상품명 / 약술 / SEO 정보를 입력하십시오.',
            'price'              => '판매가격 (원)',
            'original_price'     => '원가 (원)',
            'add_time'           => '생성 시간',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => '사용자 정보',
            'user_placeholder'   => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'goods'              => '상품 정보',
            'goods_placeholder'  => '상품명 / 약술 / SEO 정보를 입력하십시오.',
            'price'              => '판매가격 (원)',
            'original_price'     => '원가 (원)',
            'add_time'           => '생성 시간',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => '사용자 정보',
            'user_placeholder'   => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'goods'              => '상품 정보',
            'goods_placeholder'  => '상품명 / 약술 / SEO 정보를 입력하십시오.',
            'price'              => '판매가격 (원)',
            'original_price'     => '원가 (원)',
            'add_time'           => '생성 시간',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => '우정 링크',
        // 动态表格
        'form_table'                            => [
            'info'                => '이름',
            'url'                 => 'url 주소',
            'describe'            => '설명',
            'is_enable'           => '사용 여부',
            'is_new_window_open'  => '새 창이 열릴지 여부',
            'sort'                => '정렬',
            'add_time'            => '생성 시간',
            'upd_time'            => '업데이트 시간',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '중간 탐색', 'type' => 'header'],
            ['name' => '아래쪽 탐색', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => '사용자 지정',
            'article'           => '기사',
            'customview'        => '사용자 지정 페이지',
            'goods_category'    => '상품 분류',
            'design'            => '페이지 디자인',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => '탐색 이름',
            'data_type'           => '데이터 유형 탐색',
            'is_show'             => '표시 여부',
            'is_new_window_open'  => '새 창 열기',
            'sort'                => '정렬',
            'add_time'            => '생성 시간',
            'upd_time'            => '업데이트 시간',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => '주문 id 오류',
            'express_choice_tips'               => '택배 방법 선택하세요.',
            'payment_choice_tips'               => '결제 방법을 선택하십시오.',
        ],
        // 页面基础
        'form_delivery_title'                   => '배송 작업',
        'form_payment_title'                    => '결제 작업',
        'form_item_take'                        => '픽업 코드',
        'form_item_take_message'                => '4 자리 숫자 픽업 코드를 기입해 주십시오.',
        'form_item_express_number'              => '택배 송장 번호',
        'form_item_express_number_message'      => '택배 송장 번호를 기입해 주십시오.',
        // 地址
        'detail_user_address_title'             => '배송 주소',
        'detail_user_address_name'              => '받는 사람',
        'detail_user_address_tel'               => '수신 전화',
        'detail_user_address_value'             => '상세 주소',
        'detail_user_address_idcard'            => '신분증 정보',
        'detail_user_address_idcard_name'       => '이름',
        'detail_user_address_idcard_number'     => '번호',
        'detail_user_address_idcard_pic'        => '사진',
        'detail_take_address_title'             => '픽업 주소',
        'detail_take_address_contact'           => '연락처 정보',
        'detail_take_address_value'             => '상세 주소',
        'detail_fictitious_title'               => '키 정보',
        // 订单售后
        'detail_aftersale_status'               => '상태',
        'detail_aftersale_type'                 => '유형',
        'detail_aftersale_price'                => '금액',
        'detail_aftersale_number'               => '수량',
        'detail_aftersale_reason'               => '이유',
        // 商品
        'detail_goods_title'                    => '주문 상품',
        'detail_payment_amount_less_tips'       => '주문 결제 금액이 총 가격 금액보다 작다는 점에 유의하십시오.',
        'detail_no_payment_tips'                => '이 주문은 아직 지불되지 않았습니다.',
        // 动态表格
        'form_table'                            => [
            'goods'               => '기본 정보',
            'goods_placeholder'   => '주문 ID/주문 번호/상품 이름/모델을 입력하십시오.',
            'user'                => '사용자 정보',
            'user_placeholder'    => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'status'              => '주문 상태',
            'pay_status'          => '지불 상태',
            'total_price'         => '총 가격 (원)',
            'pay_price'           => '지불금액 (원)',
            'price'               => '단가 (원)',
            'warehouse_name'      => '출하창고',
            'order_model'         => '주문 모드',
            'client_type'         => '출처',
            'address'             => '주소 정보',
            'take'                => '수거 정보',
            'refund_price'        => '환불 금액 (위안)',
            'returned_quantity'   => '반품 수량',
            'buy_number_count'    => '총 구매',
            'increase_price'      => '증액액 (원)',
            'preferential_price'  => '할인 금액 (원)',
            'payment_name'        => '지불 방식',
            'user_note'           => '사용자 설명',
            'extension'           => '확장 정보',
            'express_name'        => '택배 회사',
            'express_number'      => '택배 송장 번호',
            'aftersale'           => '최신 판매 후',
            'is_comments'         => '사용자 설명 여부',
            'confirm_time'        => '확인 시간',
            'pay_time'            => '지불 시간',
            'delivery_time'       => '배송 시간',
            'collect_time'        => '완료 시간',
            'cancel_time'         => '취소 시간',
            'close_time'          => '종료 시간',
            'add_time'            => '생성 시간',
            'upd_time'            => '업데이트 시간',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => '승인 작업',
        'form_refuse_title'                     => '작업 거부',
        'form_user_info_title'                  => '사용자 정보',
        'form_apply_info_title'                 => '신청 정보',
        'forn_apply_info_type'                  => '유형',
        'forn_apply_info_price'                 => '금액',
        'forn_apply_info_number'                => '수량',
        'forn_apply_info_reason'                => '이유',
        'forn_apply_info_msg'                   => '설명',
        // 动态表格
        'form_table'                            => [
            'goods'              => '기본 정보',
            'goods_placeholder'  => '주문 번호 / 제품 이름 / 모델을 입력하십시오.',
            'user'               => '사용자 정보',
            'user_placeholder'   => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'status'             => '상태',
            'type'               => '신청 유형',
            'reason'             => '이유',
            'price'              => '환불 금액 (위안)',
            'number'             => '반품 수량',
            'msg'                => '환불 설명',
            'refundment'         => '환불 유형',
            'voucher'            => '자격 증명',
            'express_name'       => '택배 회사',
            'express_number'     => '택배 송장 번호',
            'refuse_reason'      => '거부 이유',
            'apply_time'         => '신청 시간',
            'confirm_time'       => '확인 시간',
            'delivery_time'      => '반품 기간',
            'audit_time'         => '감사 시간',
            'add_time'           => '생성 시간',
            'upd_time'           => '업데이트 시간',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => '지불 방식',
        'nav_store_payment_name'                => '추가 결제 방법 다운로드',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1. 클래스 이름은 파일 이름과 일치해야 합니다 (.php 제거). Alipay.php의 경우 Alipay',
            ],
            [
                'name'  => '2. 클래스가 정의해야 하는 방법',
                'item'  => [
                    '2.1 Config 구성 방법',
                    '2.2 Pay 결제 방법',
                    '2.3 Respond 콜백 방법',
                    '2.4. Notify 비동기식 콜백 메서드(옵션, 정의되지 않은 경우 Respond 메서드 호출)',
                    '2.5. Refund 환불 방법(선택 사항, 정의되지 않은 경우 원래의 환불을 시작할 수 없음)',
                ],
            ],
            [
                'name'  => '3. 사용자 정의 가능한 출력 내용 방법',
                'item'  => [
                    '3.1. SuccessReturn 결제 성공(옵션)',
                    '3.2. ErrorReturn 결제 실패(옵션)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: 위의 조건이 충족되지 않으면 플러그인을 볼 수 없습니다. 플러그인을.zip 압축 팩에 업로드, 하나의 압축에 여러 결제 플러그인 포함 지원',
        // 动态表格
        'form_table'                            => [
            'name'            => '이름',
            'logo'            => 'LOGO',
            'version'         => '버전',
            'apply_version'   => '적용 가능한 버전',
            'apply_terminal'  => '적용 터미널',
            'author'          => '작성자',
            'desc'            => '설명',
            'enable'          => '사용 여부',
            'open_user'       => '사용자 개방형',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => '택배',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => '현재 주제', 'type' => 'index'],
            ['name' => '테마 설치', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => '추가 테마 다운로드',
        'list_author_title'                     => '작성자',
        'list_version_title'                    => '적용 가능한 버전',
        'form_theme_upload_tips'                => 'zip 압축 형식의 테마 설치 패키지 업로드',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => '핸드폰 사용자 센터 내비게이션',
        // 动态表格
        'form_table'                            => [
            'name'           => '이름',
            'platform'       => '소유 플랫폼',
            'images_url'     => '탐색 아이콘',
            'event_type'     => '이벤트 유형',
            'event_value'    => '이벤트 값',
            'desc'           => '설명',
            'is_enable'      => '사용 여부',
            'is_need_login'  => '로그인 필요 여부',
            'sort'           => '정렬',
            'add_time'       => '생성 시간',
            'upd_time'       => '업데이트 시간',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => '핸드폰 홈페이지 내비게이션',
        // 动态表格
        'form_table'                            => [
            'name'           => '이름',
            'platform'       => '소유 플랫폼',
            'images'         => '탐색 아이콘',
            'event_type'     => '이벤트 유형',
            'event_value'    => '이벤트 값',
            'is_enable'      => '사용 여부',
            'is_need_login'  => '로그인 필요 여부',
            'sort'           => '정렬',
            'add_time'       => '생성 시간',
            'upd_time'       => '업데이트 시간',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => '지불 요청 로그',
        // 动态表格
        'form_table'                            => [
            'user'              => '사용자 정보',
            'user_placeholder'  => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'log_no'            => '지불 명세서 번호',
            'payment'           => '지불 방식',
            'status'            => '상태',
            'total_price'       => '업무 주문 금액 (원)',
            'pay_price'         => '지불금액 (원)',
            'business_type'     => '비즈니스 유형',
            'business_list'     => '비즈니스 id/싱글 번호',
            'trade_no'          => '결제 플랫폼 거래 번호',
            'buyer_user'        => '결제 플랫폼 사용자 계정',
            'subject'           => '주문 이름',
            'pay_time'          => '지불 시간',
            'close_time'        => '종료 시간',
            'add_time'          => '생성 시간',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => '지불 요청 로그',
        // 动态表格
        'form_table'                            => [
            'business_type'    => '비즈니스 유형',
            'request_params'   => '요청 매개변수',
            'response_data'    => '응답 데이터',
            'business_handle'  => '비즈니스 처리 결과',
            'request_url'      => '요청 URL 주소',
            'server_port'      => '포트 번호',
            'server_ip'        => '서버 IP',
            'client_ip'        => '클라이언트 IP',
            'os'               => '운영 체제',
            'browser'          => '브라우저',
            'method'           => '요청 유형',
            'scheme'           => 'http 유형',
            'version'          => 'http 버전',
            'client'           => '클라이언트 상세 정보',
            'add_time'         => '생성 시간',
            'upd_time'         => '업데이트 시간',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => '사용자 정보',
            'user_placeholder'  => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'payment'           => '지불 방식',
            'business_type'     => '비즈니스 유형',
            'business_id'       => '비즈니스 주문 id',
            'trade_no'          => '결제 플랫폼 거래 번호',
            'buyer_user'        => '결제 플랫폼 사용자 계정',
            'refundment_text'   => '환불 방법',
            'refund_price'      => '환불 금액',
            'pay_price'         => '주문 결제 금액',
            'msg'               => '설명',
            'add_time_time'     => '환불 시간',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => '애플리케이션 관리로 돌아가기 > >',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => '먼저 체크를 클릭하여 활성화 하세요',
            'save_no_data_tips'                 => '저장할 플러그인 데이터가 없습니다.',
        ],
        // 基础导航
        'base_nav_title'                        => '적용',
        'base_nav_list'                         => [
            ['name' => '애플리케이션 관리', 'type' => 'index'],
            ['name' => '애플리케이션 업로드', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => '추가 플러그인 다운로드',
        // 基础页面
        'base_search_input_placeholder'         => '이름 / 설명을 입력하십시오.',
        'base_top_tips_one'                     => '목록 정렬 방법 [사용자 정의 정렬 -> 최초 설치]',
        'base_top_tips_two'                     => '드래그 아이콘 단추를 눌러 플러그인 호출과 전시 순서를 조정할 수 있습니다',
        'base_open_sort_title'                  => '정렬 열기',
        'data_list_author_title'                => '작성자',
        'data_list_author_url_title'            => '홈 페이지',
        'data_list_version_title'               => '버전',
        'uninstall_confirm_tips'                => '설치를 제거하면 플러그인 기본 구성 데이터를 복구할 수 없고 작업을 확인할 수 없습니까?',
        'not_install_divide_title'              => '다음 플러그인이 설치되지 않았습니다.',
        'delete_plugins_text'                   => '1. 앱만 삭제',
        'delete_plugins_text_tips'              => '(애플리케이션 코드만 삭제하고 애플리케이션 데이터는 유지)',
        'delete_plugins_data_text'              => '2. 애플리케이션 삭제 및 데이터 삭제',
        'delete_plugins_data_text_tips'         => '(애플리케이션 코드 및 애플리케이션 데이터가 삭제됨)',
        'delete_plugins_ps_tips'                => 'PS: 다음 작업 후 복구할 수 없습니다. 주의하십시오!',
        'delete_plugins_button_name'            => '애플리케이션만 삭제',
        'delete_plugins_data_button_name'       => '애플리케이션 및 데이터 삭제',
        'cancel_delete_plugins_button_name'     => '다시 생각해 보다',
        'more_plugins_store_to_text'            => '앱스토어에서 플러그인 풍부한 사이트 선택 > >',
        'no_data_store_to_text'                 => '앱스토어에서 플러그인 풍부한 사이트 선택 > >',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => '백그라운드로 돌아가기',
        'get_loading_tips'                      => '가져오는 중...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => '역할',
        'admin_not_modify_tips'                 => '슈퍼 관리자는 기본적으로 모든 권한을 가지고 있으며 변경할 수 없습니다.',
        // 动态表格
        'form_table'                            => [
            'name'       => '역할 이름',
            'is_enable'  => '사용 여부',
            'add_time'   => '생성 시간',
            'upd_time'   => '업데이트 시간',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => '권한',
        'top_tips_list'                         => [
            '1. 전문 기술자가 아닌 사람은 이 페이지의 데이터를 조작하지 마십시오. 조작 실수로 인해 권한 메뉴가 혼란스러울 수 있습니다.',
            '2. 사용 권한 메뉴는 [사용, 조작] 두 가지 유형으로 나뉜다. 사용 메뉴는 일반적으로 켜져 있고 조작 메뉴는 숨겨야 한다.',
            '3. 권한 메뉴가 잘못되면 ['.MyConfig('database.connections.mysql.prefix').'power] 데이터 테이블의 데이터 복구를 다시 덮어쓸 수 있습니다.',
            '4. [슈퍼 관리자, admin 계정] 기본적으로 모든 권한이 있으며 변경할 수 없습니다.',
        ],
        'content_top_tips_list'                 => [
            '1. [컨트롤러 이름 및 메소드 이름] 에 해당하는 컨트롤러 및 메소드를 생성하기 위한 정의를 작성합니다.',
            '2. 컨트롤러 파일 위치 [app/admin/controller], 개발자만 사용',
            '3. 컨트롤러 이름 / 메서드 이름 및 사용자 지정 URL 주소, 둘 중 하나를 입력해야 함',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => '빠른 탐색',
        // 动态表格
        'form_table'                            => [
            'name'         => '이름',
            'platform'     => '소유 플랫폼',
            'images'       => '탐색 아이콘',
            'event_type'   => '이벤트 유형',
            'event_value'  => '이벤트 값',
            'is_enable'    => '사용 여부',
            'sort'         => '정렬',
            'add_time'     => '생성 시간',
            'upd_time'     => '업데이트 시간',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => '지역',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => '가격 필터링',
        'top_tips_list'                         => [
            '최소 가격 0 - 최대 가격 100은 100보다 작음',
            '최소 가격 1000 - 최대 가격 0은 1000보다 큽니다.',
            '최소 가격 100 - 최대 가격 500은 100보다 크고 500보다 작음',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => '윤방',
        // 动态表格
        'form_table'                            => [
            'name'         => '이름',
            'platform'     => '소유 플랫폼',
            'images'       => '그림',
            'event_type'   => '이벤트 유형',
            'event_value'  => '이벤트 값',
            'is_enable'    => '사용 여부',
            'sort'         => '정렬',
            'add_time'     => '생성 시간',
            'upd_time'     => '업데이트 시간',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => '사용자 정보',
            'user_placeholder'    => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'type'                => '작업 유형',
            'operation_integral'  => '운영 포인트',
            'original_integral'   => '원시 적분',
            'new_integral'        => '최신 포인트',
            'msg'                 => '작업 원인',
            'operation_id'        => '운영자 id',
            'add_time_time'       => '작업 시간',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => '사용자 정보',
            'user_placeholder'          => '사용자 이름 / 닉네임 / 핸드폰 / 메일박스 입력',
            'type'                      => '메시지 유형',
            'business_type'             => '비즈니스 유형',
            'title'                     => '제목',
            'detail'                    => '상세 정보',
            'is_read'                   => '읽었는지 여부',
            'user_is_delete_time_text'  => '사용자 삭제 여부',
            'add_time_time'             => '발송 시간',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: 개발자가 아닌 경우 SQL 문을 임의로 실행하지 마십시오. 이로 인해 전체 시스템 데이터베이스가 삭제될 수 있습니다.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'ShopXO 우수 응용 프로그램 목록, 여기에는 가장 베테랑, 가장 강력한 기술력, 가장 신뢰할 수 있는 ShopXO 개발자가 운집하여 당신의 플러그인, 스타일, 템플릿을 위해 전면적인 호위를 사용자 정의합니다.',
        'to_store_name'                         => '앱스토어에서 플러그인 선택 > >',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => '백그라운드 관리 시스템',
        'remove_cache_title'                    => '캐시 지우기',
        'user_status_title'                     => '사용자 상태',
        'user_status_message'                   => '사용자 상태를 선택하십시오.',
        // 商品参数
        'form_goods_params_config_error_tips'   => '상품 매개 변수 구성 정보',
        'form_goods_params_copy_no_tips'        => '먼저 구성 정보를 붙여넣습니다.',
        'form_goods_params_copy_error_tips'     => '구성 형식 오류',
        'form_goods_params_type_message'        => '상품 매개 변수 전시 유형을 선택하십시오.',
        'form_goods_params_params_name'         => '매개변수 이름',
        'form_goods_params_params_message'      => '매개변수 이름을 입력하십시오.',
        'form_goods_params_value_name'          => '매개변수 값',
        'form_goods_params_value_message'       => '매개 변수 값을 입력하십시오.',
        'form_goods_params_move_type_tips'      => '잘못된 작업 유형 구성',
        'form_goods_params_move_top_tips'       => '맨 위에',
        'form_goods_params_move_bottom_tips'    => '맨 아래',
        'form_goods_params_thead_type_title'    => '전시 범위',
        'form_goods_params_thead_name_title'    => '매개변수 이름',
        'form_goods_params_thead_value_title'   => '매개변수 값',
        'form_goods_params_row_add_title'       => '행 추가',
        'form_goods_params_list_tips'           => [
            '1.모두 (상품 기초 정보 및 상세 매개 변수 아래 전시)',
            '2. 상세 정보 (상품 상세 정보 매개 변수에서만 제공)',
            '3. 기본 (상품 기본 정보에서만 제공)',
            '4. 바로 가기 작업은 원래의 데이터를 지우고 페이지를 다시 로드하면 원래의 데이터를 복구할 수 있습니다 (상품을 저장한 후에만 적용).',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => '시스템 설정',
            'item'  => [
                'config_index'                 => '시스템 구성',
                'config_store'                 => '스토어 정보',
                'config_save'                  => '구성 저장',
                'index_storeaccountsbind'      => '앱스토어 계정 바인딩',
                'index_inspectupgrade'         => '시스템 업데이트 확인',
                'index_inspectupgradeconfirm'  => '시스템 업데이트 확인',
                'index_stats'                  => '홈 통계',
                'index_income'                 => '홈 통계 (수입 통계)',
            ]
        ],
        'site_index' => [
            'name'  => '사이트 구성',
            'item'  => [
                'site_index'                  => '사이트 설정',
                'site_save'                   => '사이트 설정 편집',
                'site_goodssearch'            => '사이트 설정 상품 검색',
                'layout_layoutindexhomesave'  => '홈 레이아웃 관리',
                'sms_index'                   => '문자 설정',
                'sms_save'                    => '문자 설정 편집',
                'email_index'                 => '사서함 설정',
                'email_save'                  => '사서함 설정 / 편집',
                'email_emailtest'             => '메시지 전송 테스트',
                'seo_index'                   => 'SEO 설정',
                'seo_save'                    => 'SEO 설정 편집',
                'agreement_index'             => '계약 관리',
                'agreement_save'              => '계약 설정 편집',
            ]
        ],
        'power_index' => [
            'name'  => '권한 제어',
            'item'  => [
                'admin_index'        => '관리자 목록',
                'admin_saveinfo'     => '관리자 페이지 추가 / 편집',
                'admin_save'         => '관리자 추가 / 편집',
                'admin_delete'       => '관리자 삭제',
                'admin_detail'       => '관리자 상세 정보',
                'role_index'         => '역할 관리',
                'role_saveinfo'      => '역할 그룹 추가 / 편집 페이지',
                'role_save'          => '역할 그룹 추가 / 편집',
                'role_delete'        => '역할 삭제',
                'role_statusupdate'  => '역할 상태 업데이트',
                'role_detail'        => '역할 상세 정보',
                'power_index'        => '권한 할당',
                'power_save'         => '권한 추가 / 편집',
                'power_delete'       => '권한 삭제',
            ]
        ],
        'user_index' => [
            'name'  => '사용자 관리',
            'item'  => [
                'user_index'            => '사용자 목록',
                'user_saveinfo'         => '사용자 편집 / 페이지 추가',
                'user_save'             => '사용자 추가 / 편집',
                'user_delete'           => '사용자 삭제',
                'user_detail'           => '사용자 상세 정보',
                'useraddress_index'     => '사용자 주소',
                'useraddress_saveinfo'  => '사용자 주소 편집 페이지',
                'useraddress_save'      => '사용자 주소 편집',
                'useraddress_delete'    => '사용자 주소 삭제',
                'useraddress_detail'    => '사용자 주소 상세 정보',
            ]
        ],
        'goods_index' => [
            'name'  => '상품 관리',
            'item'  => [
                'goods_index'                       => '상품 관리',
                'goods_saveinfo'                    => '상품 추가 / 편집 페이지',
                'goods_save'                        => '상품 추가 / 편집',
                'goods_delete'                      => '상품 삭제',
                'goods_statusupdate'                => '상품 상태 업데이트',
                'goods_basetemplate'                => '상품 기본 템플릿 가져오기',
                'goods_detail'                      => '상품 상세 정보',
                'goodscategory_index'               => '상품 분류',
                'goodscategory_save'                => '상품 분류 추가 / 편집',
                'goodscategory_delete'              => '상품 분류 삭제',
                'goodsparamstemplate_index'         => '상품 매개 변수',
                'goodsparamstemplate_delete'        => '상품 매개 변수 삭제',
                'goodsparamstemplate_statusupdate'  => '상품 매개 변수 상태 업데이트',
                'goodsparamstemplate_saveinfo'      => '상품 매개변수 추가 / 편집 페이지',
                'goodsparamstemplate_save'          => '상품 매개변수 추가 / 편집',
                'goodsparamstemplate_detail'        => '상품 매개 변수 상세 정보',
                'goodsspectemplate_index'           => '상품 규격',
                'goodsspectemplate_delete'          => '제품 사양 삭제',
                'goodsspectemplate_statusupdate'    => '제품 사양 상태 업데이트',
                'goodsspectemplate_saveinfo'        => '상품 사양 추가 / 편집 페이지',
                'goodsspectemplate_save'            => '상품 사양 추가 / 편집',
                'goodsspectemplate_detail'          => '상품 사양 상세 정보',
                'goodscomments_detail'              => '상품 리뷰 상세 정보',
                'goodscomments_index'               => '상품 리뷰',
                'goodscomments_reply'               => '상품 리뷰 응답',
                'goodscomments_delete'              => '상품평 삭제',
                'goodscomments_statusupdate'        => '상품 리뷰 상태 업데이트',
                'goodscomments_saveinfo'            => '상품 리뷰 페이지 추가 / 편집',
                'goodscomments_save'                => '상품평 추가/편집',
                'goodsbrowse_index'                 => '상품 탐색',
                'goodsbrowse_delete'                => '제품 찾아보기 삭제',
                'goodsbrowse_detail'                => '제품 찾아보기 상세 정보',
                'goodsfavor_index'                  => '상품 컬렉션',
                'goodsfavor_delete'                 => '컬렉션 삭제',
                'goodsfavor_detail'                 => '상품 컬렉션 상세 정보',
                'goodscart_index'                   => '상품 카트',
                'goodscart_delete'                  => '상품 카트 삭제',
                'goodscart_detail'                  => '상품 카트 상세 정보',
            ]
        ],
        'order_index' => [
            'name'  => '주문 관리',
            'item'  => [
                'order_index'             => '주문 관리',
                'order_delete'            => '주문 삭제',
                'order_cancel'            => '주문 취소',
                'order_delivery'          => '주문 배송',
                'order_collect'           => '주문 접수',
                'order_pay'               => '주문 결제',
                'order_confirm'           => '주문 확인',
                'order_detail'            => '주문 상세 정보',
                'orderaftersale_index'    => '주문 판매 후',
                'orderaftersale_delete'   => '주문 판매 후 삭제',
                'orderaftersale_cancel'   => '주문 판매 후 취소',
                'orderaftersale_audit'    => '주문 판매 후 검토',
                'orderaftersale_confirm'  => '주문 판매 후 확인',
                'orderaftersale_refuse'   => '주문 판매 후 거부',
                'orderaftersale_detail'   => '주문 판매 후 상세 정보',
            ]
        ],
        'navigation_index' => [
            'name'  => '사이트 관리',
            'item'  => [
                'navigation_index'         => '탐색 관리',
                'navigation_save'          => '탐색 추가 / 편집',
                'navigation_delete'        => '탐색 제거',
                'navigation_statusupdate'  => '탐색 상태 업데이트',
                'customview_index'         => '사용자 지정 페이지',
                'customview_saveinfo'      => '사용자 지정 페이지 페이지 추가 / 편집',
                'customview_save'          => '사용자 지정 페이지 추가 / 편집',
                'customview_delete'        => '사용자 지정 페이지 삭제',
                'customview_statusupdate'  => '사용자 지정 페이지 상태 업데이트',
                'customview_detail'        => '사용자 지정 페이지 상세 정보',
                'link_index'               => '우정 링크',
                'link_saveinfo'            => '우정 링크 추가 / 편집 페이지',
                'link_save'                => '우정 링크 추가 / 편집',
                'link_delete'              => '우정 링크 삭제',
                'link_statusupdate'        => '우정 링크 상태 업데이트',
                'link_detail'              => '우정 링크 상세 정보',
                'theme_index'              => '주제 관리',
                'theme_save'               => '주제 관리 추가 / 편집',
                'theme_upload'             => '테마 업로드 설치',
                'theme_delete'             => '테마 삭제',
                'theme_download'           => '테마 다운로드',
                'slide_index'              => '첫 페이지 윤방',
                'slide_saveinfo'           => '윤방 추가 / 편집 페이지',
                'slide_save'               => '라운드 캐스트 추가 / 편집',
                'slide_statusupdate'       => '라운드 플레이 상태 업데이트',
                'slide_delete'             => '윤방 삭제',
                'slide_detail'             => '라운드 캐스트 상세 정보',
                'screeningprice_index'     => '가격 필터링',
                'screeningprice_save'      => '필터링 가격 추가 / 편집',
                'screeningprice_delete'    => '필터링 가격 삭제',
                'region_index'             => '지역 관리',
                'region_save'              => '지역 추가 / 편집',
                'region_delete'            => '지역 삭제',
                'region_codedata'          => '지역 번호 데이터 가져오기',
                'express_index'            => '택배 관리',
                'express_save'             => '택배 추가 / 편집',
                'express_delete'           => '택배 삭제',
                'payment_index'            => '지불 방식',
                'payment_saveinfo'         => '결제 방법 설치 / 편집 페이지',
                'payment_save'             => '결제 방법 설치 / 편집',
                'payment_delete'           => '결제 방법 삭제',
                'payment_install'          => '결제 방법 설치',
                'payment_statusupdate'     => '결제 방법 상태 업데이트',
                'payment_uninstall'        => '결제 방법 제거',
                'payment_upload'           => '결제 방법 업로드',
                'quicknav_index'           => '빠른 탐색',
                'quicknav_saveinfo'        => '빠른 탐색 페이지 추가 / 편집',
                'quicknav_save'            => '빠른 탐색 추가 / 편집',
                'quicknav_statusupdate'    => '빠른 탐색 상태 업데이트',
                'quicknav_delete'          => '빠른 탐색 제거',
                'quicknav_detail'          => '빠른 탐색 상세 정보',
                'design_index'             => '페이지 디자인',
                'design_saveinfo'          => '페이지 디자인 페이지 추가 / 편집',
                'design_save'              => '페이지 디자인 추가 / 편집',
                'design_statusupdate'      => '페이지 디자인 상태 업데이트',
                'design_upload'            => '페이지 디자인 가져오기',
                'design_download'          => '페이지 디자인 다운로드',
                'design_sync'              => '페이지 디자인 동기화 첫 페이지',
                'design_delete'            => '페이지 디자인 삭제',
            ]
        ],
        'brand_index' => [
            'name'  => '브랜드 관리',
            'item'  => [
                'brand_index'           => '브랜드 관리',
                'brand_saveinfo'        => '브랜드 추가 / 편집 페이지',
                'brand_save'            => '브랜드 추가 / 편집',
                'brand_statusupdate'    => '브랜드 상태 업데이트',
                'brand_delete'          => '브랜드 삭제',
                'brand_detail'          => '브랜드 상세 정보',
                'brandcategory_index'   => '브랜드 분류',
                'brandcategory_save'    => '브랜드 분류 추가 / 편집',
                'brandcategory_delete'  => '브랜드 분류 삭제',
            ]
        ],
        'warehouse_index' => [
            'name'  => '창고 관리',
            'item'  => [
                'warehouse_index'               => '창고 관리',
                'warehouse_saveinfo'            => '창고 추가 / 편집 페이지',
                'warehouse_save'                => '저장소 추가 / 편집',
                'warehouse_delete'              => '저장소 삭제',
                'warehouse_statusupdate'        => '창고 상태 업데이트',
                'warehouse_detail'              => '창고 상세 정보',
                'warehousegoods_index'          => '창고 상품 관리',
                'warehousegoods_detail'         => '창고 상품 상세 정보',
                'warehousegoods_delete'         => '창고 상품 삭제',
                'warehousegoods_statusupdate'   => '창고 상품 상태 업데이트',
                'warehousegoods_goodssearch'    => '창고 상품 검색',
                'warehousegoods_goodsadd'       => '창고 상품 검색 추가',
                'warehousegoods_goodsdel'       => '창고 상품 검색 삭제',
                'warehousegoods_inventoryinfo'  => '창고 상품 인벤토리 편집 페이지',
                'warehousegoods_inventorysave'  => '창고 상품 재고 편집',
            ]
        ],
        'app_index' => [
            'name'  => '휴대폰 관리',
            'item'  => [
                'appconfig_index'            => '기본 구성',
                'appconfig_save'             => '기본 구성 저장',
                'apphomenav_index'           => '홈 탐색',
                'apphomenav_saveinfo'        => '홈 탐색 페이지 추가 / 편집',
                'apphomenav_save'            => '홈 탐색 추가 / 편집',
                'apphomenav_statusupdate'    => '홈 탐색 상태 업데이트',
                'apphomenav_delete'          => '홈 탐색 삭제',
                'apphomenav_detail'          => '홈 탐색 상세 정보',
                'appcenternav_index'         => '사용자 센터 탐색',
                'appcenternav_saveinfo'      => '사용자 센터 탐색 페이지 추가 / 편집',
                'appcenternav_save'          => '사용자 센터 탐색 추가 / 편집',
                'appcenternav_statusupdate'  => '사용자 센터 탐색 상태 업데이트',
                'appcenternav_delete'        => '사용자 센터 탐색 제거',
                'appcenternav_detail'        => '사용자 센터 탐색 상세 정보',
                'appmini_index'              => '애플릿 목록',
                'appmini_created'            => '애플릿 패키지 생성',
                'appmini_delete'             => '애플릿 패키지 삭제',
                'appmini_themeupload'        => '애플릿 테마 업로드',
                'appmini_themesave'          => '애플릿 테마 전환',
                'appmini_themedelete'        => '애플릿 테마 전환',
                'appmini_themedownload'      => '애플릿 테마 다운로드',
                'appmini_config'             => '애플릿 구성',
                'appmini_save'               => '애플릿 구성 저장',
            ]
        ],
        'article_index' => [
            'name'  => '문서 관리',
            'item'  => [
                'article_index'           => '문서 관리',
                'article_saveinfo'        => '기사 추가 / 편집 페이지',
                'article_save'            => '기사 추가 / 편집',
                'article_delete'          => '기사 삭제',
                'article_statusupdate'    => '기사 상태 업데이트',
                'article_detail'          => '기사 상세 정보',
                'articlecategory_index'   => '문장 분류',
                'articlecategory_save'    => '기사 분류 편집 / 추가',
                'articlecategory_delete'  => '문서 분류 삭제',
            ]
        ],
        'data_index' => [
            'name'  => '데이터 관리',
            'item'  => [
                'answer_index'          => '문답 메시지',
                'answer_reply'          => '문답 댓글 답장',
                'answer_delete'         => 'Q&A 메시지 삭제',
                'answer_statusupdate'   => '퀴즈 댓글 상태 업데이트',
                'answer_saveinfo'       => '질문 추가 / 편집 페이지',
                'answer_save'           => '질의 응답 추가 / 편집',
                'answer_detail'         => 'Q&A 메시지 상세 정보',
                'message_index'         => '메시지 관리',
                'message_delete'        => '메시지 삭제',
                'message_detail'        => '메시지 상세 정보',
                'paylog_index'          => '결제 로그',
                'paylog_detail'         => '결제 로그 상세 정보',
                'paylog_close'          => '결제 로그 닫기',
                'payrequestlog_index'   => '지불 요청 로그 목록',
                'payrequestlog_detail'  => '지불 요청 로그 상세 정보',
                'refundlog_index'       => '환불 로그',
                'refundlog_detail'      => '환불 로그 상세 정보',
                'integrallog_index'     => '포인트 로그',
                'integrallog_detail'    => '포인트 로그 상세 정보',
            ]
        ],
        'store_index' => [
            'name'  => '응용 센터',
            'item'  => [
                'pluginsadmin_index'         => '애플리케이션 관리',
                'plugins_index'              => '호출 관리 적용',
                'pluginsadmin_saveinfo'      => '적용 페이지 추가 / 편집',
                'pluginsadmin_save'          => '추가 / 편집 적용',
                'pluginsadmin_statusupdate'  => '상태 업데이트 적용',
                'pluginsadmin_delete'        => '삭제 적용',
                'pluginsadmin_upload'        => '업로드 적용',
                'pluginsadmin_download'      => '패키지 적용',
                'pluginsadmin_install'       => '설치 적용',
                'pluginsadmin_uninstall'     => '제거 적용',
                'pluginsadmin_sortsave'      => '정렬 저장 적용',
                'store_index'                => '앱스토어',
                'packageinstall_index'       => '패키지 설치 페이지',
                'packageinstall_install'     => '패키지 설치',
                'packageupgrade_upgrade'     => '패키지 업데이트',
            ]
        ],
        'tool_index' => [
            'name'  => '도구',
                'item'                  => [
                'cache_index'           => '캐시 관리',
                'cache_statusupdate'    => '사이트 캐시 업데이트',
                'cache_templateupdate'  => '템플릿 캐시 업데이트',
                'cache_moduleupdate'    => '모듈 캐시 업데이트',
                'cache_logdelete'       => '로그 삭제',
                'sqlconsole_index'      => 'SQL 콘솔',
                'sqlconsole_implement'  => 'SQL 실행',
            ]
        ],
    ],
];
?>