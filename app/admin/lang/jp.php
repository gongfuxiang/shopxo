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
 * 模块语言包-日语
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
            'order_transaction_amount_name'     => '受注成約金額の推移',
            'order_trading_trend_name'          => '受注取引の動向',
            'goods_hot_name'                    => 'ヒット商品',
            'goods_hot_tips'                    => '上位30商品のみ表示',
            'payment_name'                      => '支払い方法',
            'order_region_name'                 => '受注地域の配分',
            'order_region_tips'                 => '30データのみ表示',
            'upgrade_check_loading_tips'        => '最新のコンテンツを取得しています、お待ちください...',
            'upgrade_version_name'              => '更新バージョン：',
            'upgrade_date_name'                 => '更新日：',
        ],
        // 页面基础
        'base_update_button_title'              => '今すぐ更新',
        'base_item_base_stats_title'            => 'ショッピングモール統計',
        'base_item_base_stats_tips'             => '時間フィルタは合計数に対してのみ有効です',
        'base_item_user_title'                  => 'ユーザーの合計',
        'base_item_order_number_title'          => '受注合計',
        'base_item_order_complete_number_title' => '成約総量',
        'base_item_order_complete_title'        => 'オーダー合計',
        'base_item_last_month_title'            => '先月',
        'base_item_same_month_title'            => '当月',
        'base_item_yesterday_title'             => '昨日',
        'base_item_today_title'                 => '今日',
        'base_item_order_profit_title'          => '受注成約金額の推移',
        'base_item_order_trading_title'         => '受注取引の動向',
        'base_item_order_tips'                  => 'すべての注文',
        'base_item_hot_sales_goods_title'       => 'ヒット商品',
        'base_item_hot_sales_goods_tips'        => 'クローズ解除済オーダーは含まれていません',
        'base_item_payment_type_title'          => '支払い方法',
        'base_item_map_whole_country_title'     => '受注地域の配分',
        'base_item_map_whole_country_tips'      => 'クローズ解除済受注、デフォルト次元は含まれていません（省）',
        'base_item_map_whole_country_province'  => '省',
        'base_item_map_whole_country_city'      => '市',
        'base_item_map_whole_country_county'    => '区/県',
        'system_info_title'                     => 'システム情報',
        'system_ver_title'                      => 'ソフトウェアバージョン',
        'system_os_ver_title'                   => 'オペレーティングシステム',
        'system_php_ver_title'                  => 'PHPバージョン',
        'system_mysql_ver_title'                => 'MySQLバージョン',
        'system_server_ver_title'               => 'サーバ側情報',
        'system_host_title'                     => '現在のドメイン名',
        'development_team_title'                => '開発チーム',
        'development_team_website_title'        => '会社ホームページ',
        'development_team_website_value'        => '上海縦之格科技有限公司',
        'development_team_support_title'        => 'テクニカルサポート',
        'development_team_support_value'        => 'ShopXOエンタープライズクラス電子商取引システムプロバイダ',
        'development_team_ask_title'            => '質問の交換',
        'development_team_ask_value'            => 'ShopXOコミュニケーションに関する質問',
        'development_team_agreement_title'      => 'オープンソースプロトコル',
        'development_team_agreement_value'      => 'オープンソースプロトコルの表示',
        'development_team_update_log_title'     => '更新ログ',
        'development_team_update_log_value'     => '更新ログの表示',
        'development_team_members_title'        => '研究開発メンバー',
        'development_team_members_value'        => [
            ['name' => 'ゴン兄', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'ユーザー',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'ユーザID',
            'number_code'           => '会員コード',
            'system_type'           => 'システムタイプ',
            'platform'              => '所属プラットフォーム',
            'avatar'                => 'アイコン',
            'username'              => 'ユーザー名',
            'nickname'              => 'ニックネーム',
            'mobile'                => '携帯電話',
            'email'                 => 'メールボックス',
            'gender_name'           => '性別',
            'status_name'           => 'ステータス',
            'province'              => '所在地',
            'city'                  => '所在地',
            'county'                => '所在地/県',
            'address'               => '詳細アドレス',
            'birthday'              => '誕生日',
            'integral'              => 'ゆうこうせきぶん',
            'locking_integral'      => 'ロック積分',
            'referrer'              => '招待ユーザー',
            'referrer_placeholder'  => '招待ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'add_time'              => '登録時間',
            'upd_time'              => '更新日時',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'ユーザアドレス',
        // 详情
        'detail_user_address_idcard_name'       => '名前',
        'detail_user_address_idcard_number'     => '番号',
        'detail_user_address_idcard_pic'        => '写真',
        // 动态表格
        'form_table'                            => [
            'user'              => 'ユーザー情報',
            'user_placeholder'  => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'alias'             => '別名＃ベツメイ＃',
            'name'              => '連絡先',
            'tel'               => '連絡先電話番号',
            'province_name'     => '所属する省',
            'city_name'         => '所属市',
            'county_name'       => '所属区/県',
            'address'           => '詳細アドレス',
            'position'          => '経緯度',
            'idcard_info'       => '身分証明書情報',
            'is_default'        => 'デフォルト',
            'add_time'          => '作成時間',
            'upd_time'          => '更新日時',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => '削除後に有効を保存し、続行を確認しますか？',
            'address_no_data'                   => 'アドレスデータがNULLです',
            'address_not_exist'                 => 'アドレスが存在しません',
            'address_logo_message'              => 'ロゴ画像をアップロードしてください',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => '基本構成', 'type' => 'base'],
            ['name' => 'Webサイトの設定', 'type' => 'siteset'],
            ['name' => 'サイトタイプ', 'type' => 'sitetype'],
            ['name' => 'ユーザー登録', 'type' => 'register'],
            ['name' => 'ユーザーログイン', 'type' => 'login'],
            ['name' => 'パスワードの検索', 'type' => 'forgetpwd'],
            ['name' => '認証コード', 'type' => 'verify'],
            ['name' => '注文後', 'type' => 'orderaftersale'],
            ['name' => '添付ファイル', 'type' => 'attachment'],
            ['name' => 'キャッシュ', 'type' => 'cache'],
            ['name' => '拡張子', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'トップページ', 'type' => 'index'],
            ['name' => '商品', 'type' => 'goods'],
            ['name' => '検索けんさく', 'type' => 'search'],
            ['name' => 'オーダー', 'type' => 'order'],
            ['name' => '特恵', 'type' => 'discount'],
            ['name' => '拡張', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'サイトステータス',
        'base_item_site_domain_title'           => 'サイトドメイン名アドレス',
        'base_item_site_filing_title'           => '届出情報',
        'base_item_site_other_title'            => 'その他',
        'base_item_session_cache_title'         => 'セッションキャッシュ構成',
        'base_item_data_cache_title'            => 'データキャッシュ構成',
        'base_item_redis_cache_title'           => 'redisキャッシュ構成',
        'base_item_crontab_config_title'        => 'タイミングスクリプト設定',
        'base_item_quick_nav_title'             => 'ショートカットナビゲーション',
        'base_item_user_base_title'             => 'ユーザーベース',
        'base_item_user_address_title'          => 'ユーザアドレス',
        'base_item_multilingual_title'          => '多言語',
        'base_item_site_auto_mode_title'        => '自動モード',
        'base_item_site_manual_mode_title'      => '手動モード',
        'base_item_default_payment_title'       => 'デフォルトの支払い方法',
        'base_item_display_type_title'          => 'プレゼンテーション',
        'base_item_self_extraction_title'       => 'じこちゅうしゅつてん',
        'base_item_fictitious_title'            => '仮想販売',
        'choice_upload_logo_title'              => 'ロゴを選択',
        'add_goods_title'                       => '商品の追加',
        'add_self_extractio_address_title'      => 'アドレスを追加',
        'site_domain_tips_list'                 => [
            '1. サイトドメイン名が設定されていない場合は、現在のサイトドメイン名アドレスが使用されます[ '.__MY_DOMAIN__.' ]',
            '2. 添付ファイルと静的アドレスが設定されていない場合は、現在のサイト静的ドメイン名アドレスが使用されます[ '.__MY_PUBLIC_URL__.' ]',
            '3. サーバー側がpublicをルートディレクトリとして設定していない場合は、ここで【添付ファイルcdnドメイン名、css/js静的ファイルcdnドメイン名】を構成するには、次にpublicを追加する必要があります。'.__MY_PUBLIC_URL__.'public/',
            '4.コマンドラインモードでプロジェクトを実行します。このエリアアドレスは設定する必要があります。そうしないと、プロジェクト内のいくつかのアドレスにドメイン名情報が欠落します',
            '5.構成を乱さないでください、間違ったアドレスはサイトにアクセスできません（アドレス構成はhttpで始まります）、自局の構成がhttpsであればhttpsで始まります',
        ],
        'site_cache_tips_list'                  => [
            '1.デフォルトで使用されているファイルキャッシュ、RedisキャッシュPHPを使用するには、まずRedis拡張をインストールする必要があります',
            '2.Redisサービスの安定性を確認してください（Sessionがキャッシュを使用した後、サービスが不安定でバックグラウンドにログインできない可能性があります）',
            '3.Redisサービス異常が発生した場合、管理バックグラウンドにログインできず、プロファイル［config］ディレクトリ下の［session.php，cache.php］ファイルを変更できない',
        ],
        'goods_tips_list'                       => [
            '1.WEB側デフォルト展示3級、最低1級、最高3級（0級に設定すると3級にデフォルト）',
            '2.携帯端末のデフォルト展示0級（商品リストモード）、最低0級、最高3級（1 ~ 3は分類展示モード）',
            '3.階層が異なる、フロント分類ページのスタイルも異なる',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1.各フロアに最大何個の商品を展示するかを配置する',
            '2.数を大きく変更することは推奨されておらず、PC側の左側の空白領域が大きすぎる',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            '統合：熱->販売量->最新の降順（desc）ソート',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1.商品タイトルをクリックしてドラッグ＆ソートし、順番に展示することができる',
            '2.多くの商品を追加することは推奨されていない、PC側の左側の空白領域が大きすぎる',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1.デフォルトでは【ユーザー名、携帯電話、メールボックス】がユーザー固有',
            '2.オンにすると【システムID】を加入してユーザとして並列する',
        ],
        'extends_crontab_tips'                  => 'スクリプトアドレスをlinuxタイミングタスクタイミング要求に追加することをお勧めします（結果sucs：0、fail：0コロンの後に処理されたデータエントリ、sucs成功、fali失敗）',
        'left_images_random_tips'               => '左側の画像は最大3枚の画像をアップロードし、ランダムに1枚ずつ表示することができます',
        'background_color_tips'                 => '背景画像、デフォルトのボトムグレーをカスタマイズ可能',
        'site_setup_layout_tips'                => 'ドラッグモードは自分でトップページのデザインページに入る必要があります。選択した構成を保存してから',
        'site_setup_layout_button_name'         => 'ページをデザインする>>',
        'site_setup_goods_category_tips'        => 'より多くのフロア展示が必要な場合は、先着/商品管理->商品分類、一級分類設定トップページ推奨',
        'site_setup_goods_category_no_data_tips'=> 'データがありませんので、先着/商品管理->商品分類、一級分類設定トップページ推奨',
        'site_setup_order_default_payment_tips' => '異なるプラットフォームに対応するデフォルトの支払い方法を設定できます。まず、[Webサイト管理->支払い方法]に支払いプラグインをインストールして有効にし、ユーザーに開放してください',
        'site_setup_choice_payment_message'     => '{:name}デフォルトの支払い方法を選択してください',
        'sitetype_top_tips_list'                => [
            '1.宅配便、通常の電子商取引プロセス、ユーザーは出荷先住所を選択して注文して支払う->業者出荷->出荷の確認->注文完了',
            '2.展示型、展示製品のみ、問い合わせ可能（注文不可）',
            '3.自荷受点、注文時に自荷受地を選択し、ユーザーが注文して支払う->集荷の確認->注文完了',
            '4.仮想販売、通常の電子商取引プロセス、ユーザーの注文支払い->自動出荷->集荷の確認->注文完了',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => '推奨300*300 px',
        'form_take_address_alias'               => '別名＃ベツメイ＃',
        'form_take_address_alias_message'       => 'エイリアスフォーマット最大16文字',
        'form_take_address_name'                => '連絡先',
        'form_take_address_name_message'        => '連絡先フォーマット2～16文字間',
        'form_take_address_tel'                 => '連絡先電話番号',
        'form_take_address_tel_message'         => '連絡先電話番号を記入してください',
        'form_take_address_address'             => '詳細アドレス',
        'form_take_address_address_message'     => '詳細アドレスフォーマット1 ~ 80文字間',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'バックグラウンドログイン',
        'admin_login_info_bg_images_list_tips'  => [
            '1.背景画像は[public/static/admin/default/images/login]ディレクトリの下にあります',
            '2.背景画像命名規則（1 ~ 50）、例えば1.jpg',
        ],
        'map_type_tips'                         => '各家庭の地図基準が異なるため、地図を勝手に切り替えないでください、地図座標の表示が正確ではない場合があります。',
        'apply_map_baidu_name'                  => '百度地図オープンプラットフォームで申請してください',
        'apply_map_amap_name'                   => '高徳地図オープンプラットフォームでお申し込みください',
        'apply_map_tencent_name'                => 'テンセント地図オープンプラットフォームで申請してください',
        'apply_map_tianditu_name'               => '天地図オープンプラットフォームでお申し込みください',
        'cookie_domain_list_tips'               => [
            '1.デフォルトのNULLでは、現在のアクセスドメイン名に対してのみ有効です',
            '2.2級ドメインもCookieを共有する必要がある場合は、baidu.comなどのトップレベルドメインを記入する',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'ブランド',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'の名前をあげる',
            'describe'             => '説明',
            'logo'                 => 'LOGO',
            'url'                  => '公式サイト',
            'brand_category_text'  => 'ブランド分類',
            'is_enable'            => '有効かどうか',
            'sort'                 => 'ソート＃ソート＃',
            'add_time'             => '作成時間',
            'upd_time'             => '更新日時',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'ブランド分類',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => '文章',
        'detail_content_title'                  => '詳細',
        'detail_images_title'                   => '詳細画像',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'タイトル',
            'jump_url'               => 'ジャンプurlアドレス',
            'article_category_name'  => '分類',
            'is_enable'              => '有効かどうか',
            'is_home_recommended'    => 'トップページ推奨',
            'images_count'           => '画像数',
            'access_count'           => 'アクセス回数',
            'add_time'               => '作成時間',
            'upd_time'               => '更新日時',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => '記事の分類',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'カスタムページ',
        'detail_content_title'                  => '詳細',
        'detail_images_title'                   => '詳細画像',
        'save_view_tips'                        => '保存してから効果をプレビューしてください',
        // 动态表格
        'form_table'                            => [
            'info'            => 'タイトル',
            'is_enable'       => '有効かどうか',
            'is_header'       => '頭部かどうか',
            'is_footer'       => '尾部かどうか',
            'is_full_screen'  => 'フルスクリーンかどうか',
            'images_count'    => '画像数',
            'access_count'    => 'アクセス回数',
            'add_time'        => '作成時間',
            'upd_time'        => '更新日時',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'その他のデザインテンプレートのダウンロード',
        'upload_list_tips'                      => [
            '1.ダウンロードしたページデザインzipパッケージを選択する',
            '2.インポートにより自動的にデータが追加されます',
        ],
        'operate_sync_tips'                     => 'データをトップページのドラッグ可視化に同期させ、その後データを変更しても影響はありませんが、関連する添付ファイルは削除しないでください',
        // 动态表格
        'form_table'                            => [
            'id'                => 'データID',
            'info'              => '基礎情報',
            'info_placeholder'  => '名前を入力してください',
            'access_count'      => 'アクセス回数',
            'is_enable'         => '有効かどうか',
            'is_header'         => '頭部を含むかどうか',
            'is_footer'         => '尾を含むかどうか',
            'seo_title'         => 'SEOタイトル',
            'seo_keywords'      => 'SEOキーワード',
            'seo_desc'          => 'SEO説明',
            'add_time'          => '作成時間',
            'upd_time'          => '更新日時',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'に質問',
        'user_info_title'                       => 'ユーザー情報',
        // 动态表格
        'form_table'                            => [
            'user'              => 'ユーザー情報',
            'user_placeholder'  => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'name'              => '連絡先',
            'tel'               => '連絡先電話番号',
            'content'           => '内容',
            'reply'             => '返信内容',
            'is_show'           => '表示するかどうか',
            'is_reply'          => '返信するかどうか',
            'reply_time_time'   => 'ふっきじかん',
            'access_count'      => 'アクセス回数',
            'add_time_time'     => '作成時間',
            'upd_time_time'     => '更新日時',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => '倉庫',
        'top_tips_list'                         => [
            '1.重み値が大きいほど重みが高くなり、在庫を差し引いて重みで順次差し引く）',
            '2.倉庫はソフト削除のみ、削除後は使用不可、データベースにデータのみ保持）関連する商品データは自分で削除できます',
            '3.倉庫の使用停止と削除、関連商品の在庫はすぐに放出される',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => '名前/別名',
            'level'          => 'ウェイト(Weight)',
            'is_enable'      => '有効かどうか',
            'contacts_name'  => '連絡先',
            'contacts_tel'   => '連絡先電話番号',
            'province_name'  => '所在地',
            'city_name'      => '所在地',
            'county_name'    => '所在地/県',
            'address'        => '詳細アドレス',
            'position'       => '経緯度',
            'add_time'       => '作成時間',
            'upd_time'       => '更新日時',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => '倉庫を選択してください',
        ],
        // 基础
        'add_goods_title'                       => '商品の追加',
        'no_spec_data_tips'                     => '仕様データなし',
        'batch_setup_inventory_placeholder'     => '一括設定の値',
        'base_spec_inventory_title'             => '仕様在庫',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基礎情報',
            'goods_placeholder'  => '商品名/型番を入力してください',
            'warehouse_name'     => '倉庫',
            'is_enable'          => '有効かどうか',
            'inventory'          => '総在庫',
            'spec_inventory'     => '仕様在庫',
            'add_time'           => '作成時間',
            'upd_time'           => '更新日時',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => '管理者情報は存在しません',
        // 列表
        'top_tips_list'                         => [
            '1.adminアカウントはデフォルトですべての権限を持ち、変更できません。',
            '2. admin 账户不可更改，但是可以在数据表中修改( '.MyConfig('database.connections.mysql.prefix').'admin ) 字段 username',
        ],
        'base_nav_title'                        => '管理者',
        // 登录
        'login_type_username_title'             => 'アカウントパスワード',
        'login_type_mobile_title'               => '携帯電話認証コード',
        'login_type_email_title'                => 'メールボックス認証コード',
        'login_close_tips'                      => 'ログインを一時的に閉じました',
        // 忘记密码
        'form_forget_password_name'             => 'パスワードを忘れる？',
        'form_forget_password_tips'             => '管理者に連絡してパスワードをリセットしてください',
        // 动态表格
        'form_table'                            => [
            'username'              => '管理者',
            'status'                => 'ステータス',
            'gender'                => '性別',
            'mobile'                => '携帯電話',
            'email'                 => 'メールボックス',
            'role_name'             => 'ロールグループ',
            'login_total'           => 'ログイン回数',
            'login_time'            => '最終ログイン時間',
            'add_time'              => '作成時間',
            'upd_time'              => '更新日時',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'ユーザー登録プロトコル', 'type' => 'register'],
            ['name' => 'ユーザープライバシーポリシー', 'type' => 'privacy'],
            ['name' => 'アカウント登録解除プロトコル', 'type' => 'logout']
        ],
        'top_tips'          => 'フロントエンドアクセスプロトコルアドレス追加パラメータis _content=1では純粋なプロトコルの内容のみを表示',
        'view_detail_name'                      => '詳細の表示',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '基本構成', 'type' => 'index'],
            ['name' => 'APP/ウィジェット', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '現在のトピック', 'type' => 'index'],
            ['name' => 'トピックのインストール', 'type' => 'upload'],
            ['name' => 'ソースパッケージのダウンロード', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'その他のトピックのダウンロード',
        'nav_theme_download_name'               => 'アプレット・パッケージング・チュートリアルの表示',
        'nav_theme_download_tips'               => '携帯電話端末のテーマはuniapp開発（マルチエンドウィジェット+H 5対応）を採用し、APPも緊急対応中です。',
        'form_alipay_extend_title'              => 'カスタマーサービスの構成',
        'form_alipay_extend_tips'               => 'PS：【APP/ウィジェット】でオン（オンラインカスタマーサービスをオン）にする場合、以下の構成には［エンタープライズコード］と［チャットウィンドウコード］が必要',
        'form_theme_upload_tips'                => 'zip圧縮形式のインストールパッケージをアップロードする',
        'list_no_data_tips'                     => '関連するトピックパッケージがありません',
        'list_author_title'                     => '作者',
        'list_version_title'                    => '適用バージョン',
        'package_generate_tips'                 => '生成時間が長いので、ブラウザウィンドウを閉じないでください！',
        // 动态表格
        'form_table'                            => [
            'name'  => 'パッケージ名',
            'size'  => 'サイズ',
            'url'   => 'ダウンロードアドレス',
            'time'  => '作成時間',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'メッセージの設定', 'type' => 'index'],
            ['name' => 'メッセージテンプレート', 'type' => 'message'],
        ],
        'top_tips'                              => '阿里雲SMS管理アドレス',
        'top_to_aliyun_tips'                    => '阿里雲にメールを購入するにはクリックしてください',
        'base_item_admin_title'                 => 'バックグラウンド',
        'base_item_index_title'                 => 'フロントエンド',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'メールボックスの設定', 'type' => 'index'],
            ['name' => 'メッセージテンプレート', 'type' => 'message'],
        ],
        'top_tips'                              => '異なるメールボックスプラットフォームにはいくつかの違いがあるため、構成も異なります。具体的にはメールボックスプラットフォームの構成チュートリアルに準拠しています',
        // 基础
        'test_title'                            => 'テスト',
        'test_content'                          => 'メール設定-テストコンテンツの送信',
        'base_item_admin_title'                 => 'バックグラウンド',
        'base_item_index_title'                 => 'フロントエンド',
        // 表单
        'form_item_test'                        => '受信したメールアドレスのテスト',
        'form_item_test_tips'                   => 'テストする前に構成を保存してください',
        'form_item_test_button_title'           => 'テスト',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'サーバ環境［Nginx、Apache、IIS］に応じた擬似静的ルールの構成',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => '商品',
        'goods_nav_list'                        => [
            'base'            => ['name' => '基礎情報', 'type'=>'base'],
            'specifications'  => ['name' => '商品仕様', 'type'=>'specifications'],
            'parameters'      => ['name' => '商品パラメータ', 'type'=>'parameters'],
            'photo'           => ['name' => '商品アルバム', 'type'=>'photo'],
            'video'           => ['name' => '動画リスト', 'type'=>'video'],
            'app'             => ['name' => '携帯電話の詳細', 'type'=>'app'],
            'web'             => ['name' => 'コンピュータの詳細', 'type'=>'web'],
            'fictitious'      => ['name' => '仮想情報', 'type'=>'fictitious'],
            'extends'         => ['name' => '拡張データ', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO情報', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => '商品ID',
            'info'                    => '商品情報',
            'category_text'           => '商品の分類',
            'brand_name'              => 'ブランド',
            'price'                   => '販売価格（元）',
            'original_price'          => '原価（元）',
            'inventory'               => '在庫合計',
            'is_shelves'              => '上下フレーム',
            'is_deduction_inventory'  => '在庫の控除',
            'site_type'               => '商品の種類',
            'model'                   => '商品の型番',
            'place_origin_name'       => 'せいさんち',
            'give_integral'           => '購入によるポイント付与割合',
            'buy_min_number'          => '1回あたりの最低購入数',
            'buy_max_number'          => 'シングル購入最大数',
            'sales_count'             => '販売量',
            'access_count'            => 'アクセス回数',
            'add_time'                => '作成時間',
            'upd_time'                => '更新日時',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => '商品の分類',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => '商品レビュー',
        // 动态表格
        'form_table'                            => [
            'user'               => 'ユーザー情報',
            'user_placeholder'   => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'goods'              => '基礎情報',
            'goods_placeholder'  => '商品名/型番を入力してください',
            'business_type'      => 'ビジネス・タイプ',
            'content'            => 'コメントの内容',
            'images'             => 'コメント画像',
            'rating'             => '評価',
            'reply'              => '返信内容',
            'is_show'            => '表示するかどうか',
            'is_anonymous'       => '匿名かどうか',
            'is_reply'           => '返信するかどうか',
            'reply_time_time'    => 'ふっきじかん',
            'add_time_time'      => '作成時間',
            'upd_time_time'      => '更新日時',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => '商品パラメータ',
        // 动态表格
        'form_table'                            => [
            'category_id'   => '商品の分類',
            'name'          => 'の名前をあげる',
            'is_enable'     => '有効かどうか',
            'config_count'  => 'パラメータ数',
            'add_time'      => '作成時間',
            'upd_time'      => '更新日時',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => '商品の分類',
            'name'         => 'の名前をあげる',
            'is_enable'    => '有効かどうか',
            'content'      => '規格値',
            'add_time'     => '作成時間',
            'upd_time'     => '更新日時',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'ユーザー情報',
            'user_placeholder'   => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'goods'              => '商品情報',
            'goods_placeholder'  => '商品名/略述/SEO情報を入力してください',
            'price'              => '販売価格（元）',
            'original_price'     => '原価（元）',
            'add_time'           => '作成時間',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'ユーザー情報',
            'user_placeholder'   => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'goods'              => '商品情報',
            'goods_placeholder'  => '商品名/略述/SEO情報を入力してください',
            'price'              => '販売価格（元）',
            'original_price'     => '原価（元）',
            'add_time'           => '作成時間',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'ユーザー情報',
            'user_placeholder'   => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'goods'              => '商品情報',
            'goods_placeholder'  => '商品名/略述/SEO情報を入力してください',
            'price'              => '販売価格（元）',
            'original_price'     => '原価（元）',
            'add_time'           => '作成時間',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => '相互リンク',
        // 动态表格
        'form_table'                            => [
            'info'                => 'の名前をあげる',
            'url'                 => 'urlアドレス',
            'describe'            => '説明',
            'is_enable'           => '有効かどうか',
            'is_new_window_open'  => '新しいウィンドウを開くかどうか',
            'sort'                => 'ソート＃ソート＃',
            'add_time'            => '作成時間',
            'upd_time'            => '更新日時',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '中間ナビゲーション', 'type' => 'header'],
            ['name' => '下へ移動', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'カスタム＃カスタム＃',
            'article'           => '文章',
            'customview'        => 'カスタムページ',
            'goods_category'    => '商品の分類',
            'design'            => 'ページデザイン',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'ナビゲーション名',
            'data_type'           => 'データ型のナビゲーション',
            'is_show'             => '表示するかどうか',
            'is_new_window_open'  => '新しいウィンドウを開く',
            'sort'                => 'ソート＃ソート＃',
            'add_time'            => '作成時間',
            'upd_time'            => '更新日時',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => '注文IDに誤りがあります',
            'express_choice_tips'               => '宅配便をお選びください',
            'payment_choice_tips'               => '支払い方法を選択してください',
        ],
        // 页面基础
        'form_delivery_title'                   => '出荷オペレーション',
        'form_payment_title'                    => '支払アクション',
        'form_item_take'                        => 'ピッキングコード',
        'form_item_take_message'                => '4桁の出荷コードを記入してください',
        'form_item_express_number'              => '速達番号',
        'form_item_express_number_message'      => '宅配便番号を記入してください',
        // 地址
        'detail_user_address_title'             => '出荷先住所',
        'detail_user_address_name'              => '受信者',
        'detail_user_address_tel'               => '受信電話',
        'detail_user_address_value'             => '詳細アドレス',
        'detail_user_address_idcard'            => '身分証明書情報',
        'detail_user_address_idcard_name'       => '名前',
        'detail_user_address_idcard_number'     => '番号',
        'detail_user_address_idcard_pic'        => '写真',
        'detail_take_address_title'             => '出荷先住所',
        'detail_take_address_contact'           => '連絡先情報',
        'detail_take_address_value'             => '詳細アドレス',
        'detail_fictitious_title'               => '鍵情報',
        // 订单售后
        'detail_aftersale_status'               => 'ステータス',
        'detail_aftersale_type'                 => 'を選択してオプションを設定します。',
        'detail_aftersale_price'                => '金額',
        'detail_aftersale_number'               => '数量',
        'detail_aftersale_reason'               => '理由',
        // 商品
        'detail_goods_title'                    => '商品を注文する',
        'detail_payment_amount_less_tips'       => '注意してください、この注文の支払い金額は総額金額より小さいです',
        'detail_no_payment_tips'                => 'ご注意ください、この注文は未払いです',
        // 动态表格
        'form_table'                            => [
            'goods'               => '基礎情報',
            'goods_placeholder'   => '注文ID/注文番号/商品名/型番を入力してください',
            'user'                => 'ユーザー情報',
            'user_placeholder'    => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'status'              => '受注ステータス',
            'pay_status'          => '支払ステータス',
            'total_price'         => '合計価格（元）',
            'pay_price'           => '支払金額（元）',
            'price'               => '単価（元）',
            'warehouse_name'      => '出荷倉庫',
            'order_model'         => 'オーダー・モード',
            'client_type'         => 'ソース',
            'address'             => 'アドレス情報',
            'take'                => 'ピッキング情報',
            'refund_price'        => '払戻金額（元）',
            'returned_quantity'   => '返品数量',
            'buy_number_count'    => '購買合計',
            'increase_price'      => '追加金額（元）',
            'preferential_price'  => '割引金額（元）',
            'payment_name'        => '支払い方法',
            'user_note'           => 'ユーザーコメント',
            'extension'           => '拡張情報',
            'express_name'        => '宅配会社',
            'express_number'      => '速達番号',
            'aftersale'           => '最新のアフターサービス',
            'is_comments'         => 'ユーザーがコメントするかどうか',
            'confirm_time'        => '確認時間',
            'pay_time'            => '支払い時間',
            'delivery_time'       => '出荷時刻',
            'collect_time'        => '完了時間',
            'cancel_time'         => 'キャンセル時間',
            'close_time'          => 'シャットダウン時間',
            'add_time'            => '作成時間',
            'upd_time'            => '更新日時',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => '監査アクション',
        'form_refuse_title'                     => 'アクションの拒否',
        'form_user_info_title'                  => 'ユーザー情報',
        'form_apply_info_title'                 => '購買依頼情報',
        'forn_apply_info_type'                  => 'を選択してオプションを設定します。',
        'forn_apply_info_price'                 => '金額',
        'forn_apply_info_number'                => '数量',
        'forn_apply_info_reason'                => '理由',
        'forn_apply_info_msg'                   => '説明',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基礎情報',
            'goods_placeholder'  => '注文番号/商品名/型番を入力してください',
            'user'               => 'ユーザー情報',
            'user_placeholder'   => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'status'             => 'ステータス',
            'type'               => '購買依頼タイプ',
            'reason'             => '理由',
            'price'              => '払戻金額（元）',
            'number'             => '返品数量',
            'msg'                => '返金の説明',
            'refundment'         => '払戻タイプ',
            'voucher'            => '資格情報',
            'express_name'       => '宅配会社',
            'express_number'     => '速達番号',
            'refuse_reason'      => '拒否理由',
            'apply_time'         => '応募期間',
            'confirm_time'       => '確認時間',
            'delivery_time'      => '返品時間',
            'audit_time'         => 'レビュー時間',
            'add_time'           => '作成時間',
            'upd_time'           => '更新日時',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => '支払い方法',
        'nav_store_payment_name'                => 'その他の支払い方法のダウンロード',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1.クラス名はファイル名に一致している必要があります（.phpを除く）、Alipay.phpの場合はAlipay',
            ],
            [
                'name'  => '2.クラスが定義しなければならない方法',
                'item'  => [
                    '2.1.Config構成方法',
                    '2.2.Pay支払い方法',
                    '2.3.Respondコールバック方法',
                    '2.4.Notify非同期コールバック方法（オプション、未定義の場合はRespond方法を呼び出す）',
                    '2.5.Refund返金方法（オプション、未定義の場合は元の返金を開始できない）',
                ],
            ],
            [
                'name'  => '3.出力内容のカスタマイズ方法',
                'item'  => [
                    '3.1.SuccessReturn支払い成功（オプション）',
                    '3.2.ErrorReturn支払いに失敗した（オプション）',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS：以上の条件が満たされなければプラグインを見ることができず、プラグインを.zip圧縮パッケージに入れてアップロードし、1つの圧縮に複数の支払プラグインを含むことをサポートする',
        // 动态表格
        'form_table'                            => [
            'name'            => 'の名前をあげる',
            'logo'            => 'LOGO',
            'version'         => 'バージョン',
            'apply_version'   => '適用バージョン',
            'apply_terminal'  => '適用端末',
            'author'          => '作者',
            'desc'            => '説明',
            'enable'          => '有効かどうか',
            'open_user'       => 'ユーザーオープン',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => '速達',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => '現在のトピック', 'type' => 'index'],
            ['name' => 'トピックのインストール', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'その他のトピックのダウンロード',
        'list_author_title'                     => '作者',
        'list_version_title'                    => '適用バージョン',
        'form_theme_upload_tips'                => 'zip圧縮形式のテーマインストールパッケージをアップロードする',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => '携帯電話ユーザーセンターのナビゲーション',
        // 动态表格
        'form_table'                            => [
            'name'           => 'の名前をあげる',
            'platform'       => '所属プラットフォーム',
            'images_url'     => 'ナビゲーションアイコン',
            'event_type'     => 'イベントタイプ',
            'event_value'    => 'イベント値',
            'desc'           => '説明',
            'is_enable'      => '有効かどうか',
            'is_need_login'  => 'ログインする必要があるかどうか',
            'sort'           => 'ソート＃ソート＃',
            'add_time'       => '作成時間',
            'upd_time'       => '更新日時',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => '携帯電話のトップページナビゲーション',
        // 动态表格
        'form_table'                            => [
            'name'           => 'の名前をあげる',
            'platform'       => '所属プラットフォーム',
            'images'         => 'ナビゲーションアイコン',
            'event_type'     => 'イベントタイプ',
            'event_value'    => 'イベント値',
            'is_enable'      => '有効かどうか',
            'is_need_login'  => 'ログインする必要があるかどうか',
            'sort'           => 'ソート＃ソート＃',
            'add_time'       => '作成時間',
            'upd_time'       => '更新日時',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => '支払要求ログ',
        // 动态表格
        'form_table'                            => [
            'user'              => 'ユーザー情報',
            'user_placeholder'  => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'log_no'            => '支払い番号',
            'payment'           => '支払い方法',
            'status'            => 'ステータス',
            'total_price'       => 'ビジネスオーダー金額（元）',
            'pay_price'         => '支払金額（元）',
            'business_type'     => 'ビジネス・タイプ',
            'business_list'     => 'ビジネスID/番号',
            'trade_no'          => '支払プラットフォーム取引番号',
            'buyer_user'        => '支払いプラットフォームのユーザーアカウント',
            'subject'           => 'オーダー名',
            'pay_time'          => '支払い時間',
            'close_time'        => 'シャットダウン時間',
            'add_time'          => '作成時間',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => '支払要求ログ',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'ビジネス・タイプ',
            'request_params'   => '要求パラメータ',
            'response_data'    => 'レスポンスデータ',
            'business_handle'  => 'ビジネス処理結果',
            'request_url'      => '要求urlアドレス',
            'server_port'      => 'ポート番号',
            'server_ip'        => 'サーバip',
            'client_ip'        => 'クライアントip',
            'os'               => 'オペレーティングシステム',
            'browser'          => 'エクスプローラ',
            'method'           => '要求タイプ',
            'scheme'           => 'httpタイプ',
            'version'          => 'httpバージョン',
            'client'           => 'クライアントの詳細',
            'add_time'         => '作成時間',
            'upd_time'         => '更新日時',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'ユーザー情報',
            'user_placeholder'  => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'payment'           => '支払い方法',
            'business_type'     => 'ビジネス・タイプ',
            'business_id'       => 'ビジネスオーダーID',
            'trade_no'          => '支払プラットフォーム取引番号',
            'buyer_user'        => '支払いプラットフォームのユーザーアカウント',
            'refundment_text'   => '返金方法',
            'refund_price'      => '払戻金額',
            'pay_price'         => 'オーダー支払金額',
            'msg'               => '説明',
            'add_time_time'     => '払戻時間',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'アプリケーション管理に戻る>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'まずチェックをクリックして有効にしてください',
            'save_no_data_tips'                 => '保存可能なプラグインデータがありません',
        ],
        // 基础导航
        'base_nav_title'                        => '適用＃テキヨウ＃',
        'base_nav_list'                         => [
            ['name' => 'アプリケーション管理', 'type' => 'index'],
            ['name' => 'アプリケーションのアップロード', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'その他のプラグインのダウンロード',
        // 基础页面
        'base_search_input_placeholder'         => '名前/説明を入力してください',
        'base_top_tips_one'                     => 'リストのソート方法[カスタムソート->最初のインストール]',
        'base_top_tips_two'                     => 'ドラッグアイコンボタンをクリックしてプラグインの呼び出しと表示順序を調整することができます',
        'base_open_sort_title'                  => 'ソートを開く',
        'data_list_author_title'                => '作者',
        'data_list_author_url_title'            => 'ホーム・ページ',
        'data_list_version_title'               => 'バージョン',
        'uninstall_confirm_tips'                => 'アンインストールによりプラグインの基礎構成データが失われる可能性があります。復元、確認操作はできませんか？',
        'not_install_divide_title'              => '以下のプラグインはインストールされていません',
        'delete_plugins_text'                   => '1.アプリケーションのみ削除',
        'delete_plugins_text_tips'              => '（アプリケーションコードのみ削除し、アプリケーションデータを保持）',
        'delete_plugins_data_text'              => '2.アプリケーションを削除してデータを削除する',
        'delete_plugins_data_text_tips'         => '（アプリケーションコードとアプリケーションデータが削除されます）',
        'delete_plugins_ps_tips'                => 'PS：以下の操作後は回復できませんので、慎重に操作してください！',
        'delete_plugins_button_name'            => 'アプリケーションのみ削除',
        'delete_plugins_data_button_name'       => 'アプリケーションとデータの削除',
        'cancel_delete_plugins_button_name'     => 'もう一度考えてみよう',
        'more_plugins_store_to_text'            => 'アプリケーションストアに行ってプラグインの豊富なサイトをもっと選ぶ>>',
        'no_data_store_to_text'                 => 'アプリケーションストアでプラグインの豊富なサイトを選択>>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'バックグラウンドに戻る',
        'get_loading_tips'                      => '取得中...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'ロール＃ロール＃',
        'admin_not_modify_tips'                 => 'スーパー管理者はデフォルトですべての権限を持っており、変更することはできません。',
        // 动态表格
        'form_table'                            => [
            'name'       => 'ロール名',
            'is_enable'  => '有効かどうか',
            'add_time'   => '作成時間',
            'upd_time'   => '更新日時',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'アクセス権',
        'top_tips_list'                         => [
            '1. 非専門技術者は、ページデータを操作したり、操作ミスをしたりすると、権限メニューが乱れる可能性があります。',
            '2. 権限メニューには［使用、操作］の2種類があり、使用メニューは一般的に表示をオンにし、操作メニューは非表示にしなければならない。',
            '3. 권한 메뉴가 잘못되면 ['.MyConfig('database.connections.mysql.prefix').'power] 데이터 테이블의 데이터 복구를 다시 덮어쓸 수 있습니다.',
            '4.［スーパー管理者、adminアカウント］はデフォルトですべての権限を持っており、変更することはできません。',
        ],
        'content_top_tips_list'                 => [
            '1.［コントローラ名とメソッド名］を入力するには、対応するコントローラとメソッドの定義を作成する必要があります',
            '2.コントローラファイルの場所［app/admin/controller］、この操作は開発者のみが使用する',
            '3.コントローラ名/メソッド名とカスタムurlアドレス、両方を1つ記入する必要があります',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'ショートカットナビゲーション',
        // 动态表格
        'form_table'                            => [
            'name'         => 'の名前をあげる',
            'platform'     => '所属プラットフォーム',
            'images'       => 'ナビゲーションアイコン',
            'event_type'   => 'イベントタイプ',
            'event_value'  => 'イベント値',
            'is_enable'    => '有効かどうか',
            'sort'         => 'ソート＃ソート＃',
            'add_time'     => '作成時間',
            'upd_time'     => '更新日時',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'エリア',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => '価格のフィルタ',
        'top_tips_list'                         => [
            '最小価格0-最大価格100は100未満',
            '最小価格1000-最大価格0は1000より大きい',
            '最小価格100-最大価格500は100以上500未満',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'ローテーション',
        // 动态表格
        'form_table'                            => [
            'name'         => 'の名前をあげる',
            'platform'     => '所属プラットフォーム',
            'images'       => '画像',
            'event_type'   => 'イベントタイプ',
            'event_value'  => 'イベント値',
            'is_enable'    => '有効かどうか',
            'sort'         => 'ソート＃ソート＃',
            'add_time'     => '作成時間',
            'upd_time'     => '更新日時',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'ユーザー情報',
            'user_placeholder'    => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'type'                => '操作タイプ',
            'operation_integral'  => 'オペレーションインテグラル',
            'original_integral'   => 'げんしせきぶん',
            'new_integral'        => '最新積分',
            'msg'                 => '操作理由',
            'operation_id'        => 'オペレータID',
            'add_time_time'       => 'そうさじかん',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'ユーザー情報',
            'user_placeholder'          => 'ユーザー名/ニックネーム/携帯電話/メールアドレスを入力してください',
            'type'                      => 'メッセージ・タイプ',
            'business_type'             => 'ビジネス・タイプ',
            'title'                     => 'タイトル',
            'detail'                    => '詳細',
            'is_read'                   => '読み取り済みかどうか',
            'user_is_delete_time_text'  => 'ユーザーが削除するかどうか',
            'add_time_time'             => '送信時間',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS：非開発者はSQL文を勝手に実行しないでください。操作によってシステムデータベース全体が削除される可能性があります。',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'ShopXOの優れたアプリケーションリスト。ここには最もベテランで技術力が最も強く、最も信頼できるShopXO開発者が集まり、プラグイン、スタイル、テンプレートのために全面的な護衛をカスタマイズしています。',
        'to_store_name'                         => 'アプリケーションストアでプラグインを選択>>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'バックグラウンド管理システム',
        'remove_cache_title'                    => 'キャッシュのクリア',
        'user_status_title'                     => 'ユーザーのステータス',
        'user_status_message'                   => 'ユーザーステータスを選択してください',
        // 商品参数
        'form_goods_params_config_error_tips'   => '商品パラメータ配置情報',
        'form_goods_params_copy_no_tips'        => 'まず構成情報を貼り付けてください',
        'form_goods_params_copy_error_tips'     => '構成形式エラー',
        'form_goods_params_type_message'        => '商品パラメータ展示タイプを選択してください',
        'form_goods_params_params_name'         => 'パラメータ名',
        'form_goods_params_params_message'      => 'パラメータ名を入力してください',
        'form_goods_params_value_name'          => 'パラメータ値',
        'form_goods_params_value_message'       => 'パラメータ値を入力してください',
        'form_goods_params_move_type_tips'      => '操作タイプの構成に誤りがある',
        'form_goods_params_move_top_tips'       => '最上部に到達',
        'form_goods_params_move_bottom_tips'    => '最下部に到達',
        'form_goods_params_thead_type_title'    => '表示範囲',
        'form_goods_params_thead_name_title'    => 'パラメータ名',
        'form_goods_params_thead_value_title'   => 'パラメータ値',
        'form_goods_params_row_add_title'       => '行を追加',
        'form_goods_params_list_tips'           => [
            '1.すべて（商品基礎情報と詳細パラメータの下に表示）',
            '2.詳細（商品詳細パラメータのみで表示）',
            '3.基礎（商品基礎情報の下でのみ表示）',
            '4.ショートカット操作で元のデータが消去され、ページを再ロードすると元のデータが復元されます（商品を保存した後のみ有効）',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'システム設定',
            'item'  => [
                'config_index'                 => 'システム構成',
                'config_store'                 => '店舗情報',
                'config_save'                  => '設定の保存',
                'index_storeaccountsbind'      => 'ストアアカウントバインドの適用',
                'index_inspectupgrade'         => 'システム更新チェック',
                'index_inspectupgradeconfirm'  => 'システム更新の確認',
                'index_stats'                  => 'トップページ統計',
                'index_income'                 => 'トップページ統計（収入統計）',
            ]
        ],
        'site_index' => [
            'name'  => 'サイト構成',
            'item'  => [
                'site_index'                  => 'サイトの設定',
                'site_save'                   => 'サイト設定の編集',
                'site_goodssearch'            => 'サイト設定商品検索',
                'layout_layoutindexhomesave'  => 'トップページレイアウト管理',
                'sms_index'                   => 'メッセージの設定',
                'sms_save'                    => 'メッセージ設定の編集',
                'email_index'                 => 'メールボックスの設定',
                'email_save'                  => 'メールボックスの設定/編集',
                'email_emailtest'             => 'メール送信テスト',
                'seo_index'                   => 'SEO設定',
                'seo_save'                    => 'SEO設定編集',
                'agreement_index'             => 'プロトコル管理',
                'agreement_save'              => 'プロトコル設定の編集',
            ]
        ],
        'power_index' => [
            'name'  => '権限制御',
            'item'  => [
                'admin_index'        => '管理者リスト',
                'admin_saveinfo'     => '管理者ページの追加/編集',
                'admin_save'         => '管理者の追加/編集',
                'admin_delete'       => '管理者による削除',
                'admin_detail'       => '管理者の詳細',
                'role_index'         => 'ロール管理',
                'role_saveinfo'      => 'ロールグループの追加/編集ページ',
                'role_save'          => 'ロールグループの追加/編集',
                'role_delete'        => 'ロールの削除',
                'role_statusupdate'  => 'ロールステータスの更新',
                'role_detail'        => '役割の詳細',
                'power_index'        => '権限の割り当て',
                'power_save'         => '権限の追加/編集',
                'power_delete'       => '権限削除',
            ]
        ],
        'user_index' => [
            'name'  => 'ユーザー管理',
            'item'  => [
                'user_index'            => 'ユーザーリスト',
                'user_saveinfo'         => 'ユーザー編集/ページの追加',
                'user_save'             => 'ユーザーの追加/編集',
                'user_delete'           => 'ユーザー削除',
                'user_detail'           => 'ユーザーの詳細',
                'useraddress_index'     => 'ユーザアドレス',
                'useraddress_saveinfo'  => 'ユーザーアドレス編集ページ',
                'useraddress_save'      => 'ユーザーアドレスの編集',
                'useraddress_delete'    => 'ユーザーアドレス削除',
                'useraddress_detail'    => 'ユーザーアドレスの詳細',
            ]
        ],
        'goods_index' => [
            'name'  => '商品の管理',
            'item'  => [
                'goods_index'                       => '商品の管理',
                'goods_saveinfo'                    => '商品追加/編集ページ',
                'goods_save'                        => '商品の追加/編集',
                'goods_delete'                      => '商品の削除',
                'goods_statusupdate'                => '商品ステータスの更新',
                'goods_basetemplate'                => '商品基本テンプレートの取得',
                'goods_detail'                      => '商品の詳細',
                'goodscategory_index'               => '商品の分類',
                'goodscategory_save'                => '商品分類追加/編集',
                'goodscategory_delete'              => '商品分類の削除',
                'goodsparamstemplate_index'         => '商品パラメータ',
                'goodsparamstemplate_delete'        => '商品パラメータ削除',
                'goodsparamstemplate_statusupdate'  => '商品パラメータ状態更新',
                'goodsparamstemplate_saveinfo'      => '商品パラメータ追加/編集ページ',
                'goodsparamstemplate_save'          => '商品パラメータ追加/編集',
                'goodsparamstemplate_detail'        => '商品パラメータ詳細',
                'goodsspectemplate_index'           => '商品仕様',
                'goodsspectemplate_delete'          => '商品仕様削除',
                'goodsspectemplate_statusupdate'    => '商品仕様ステータス更新',
                'goodsspectemplate_saveinfo'        => '商品仕様追加/編集ページ',
                'goodsspectemplate_save'            => '商品仕様追加/編集',
                'goodsspectemplate_detail'          => '商品仕様詳細',
                'goodscomments_detail'              => '商品レビュー詳細',
                'goodscomments_index'               => '商品レビュー',
                'goodscomments_reply'               => '商品レビューへの返信',
                'goodscomments_delete'              => '商品レビュー削除',
                'goodscomments_statusupdate'        => '商品レビューステータス更新',
                'goodscomments_saveinfo'            => '商品レビュー追加/編集ページ',
                'goodscomments_save'                => '商品レビュー追加/編集',
                'goodsbrowse_index'                 => '商品一覧',
                'goodsbrowse_delete'                => '商品閲覧削除',
                'goodsbrowse_detail'                => '商品一覧詳細',
                'goodsfavor_index'                  => '商品コレクション',
                'goodsfavor_delete'                 => '商品コレクション削除',
                'goodsfavor_detail'                 => '商品コレクション詳細',
                'goodscart_index'                   => '商品カート',
                'goodscart_delete'                  => 'カート削除',
                'goodscart_detail'                  => '商品カート詳細',
            ]
        ],
        'order_index' => [
            'name'  => 'オーダー管理',
            'item'  => [
                'order_index'             => 'オーダー管理',
                'order_delete'            => '注文の削除',
                'order_cancel'            => '注文取消',
                'order_delivery'          => 'オーダー出荷',
                'order_collect'           => '受注受入',
                'order_pay'               => 'オーダー支払い',
                'order_confirm'           => '注文確認',
                'order_detail'            => '注文の詳細',
                'orderaftersale_index'    => '注文後',
                'orderaftersale_delete'   => '受注後の削除',
                'orderaftersale_cancel'   => '注文後のキャンセル',
                'orderaftersale_audit'    => '受注後のレビュー',
                'orderaftersale_confirm'  => '注文後の確認',
                'orderaftersale_refuse'   => '注文後の拒否',
                'orderaftersale_detail'   => '注文後の詳細',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Webサイト管理',
            'item'  => [
                'navigation_index'         => 'ナビゲーション管理',
                'navigation_save'          => 'ナビゲーション追加/編集',
                'navigation_delete'        => 'ナビゲーション削除',
                'navigation_statusupdate'  => 'ナビゲーションステータスの更新',
                'customview_index'         => 'カスタムページ',
                'customview_saveinfo'      => 'カスタムページ追加/編集ページ',
                'customview_save'          => 'カスタムページの追加/編集',
                'customview_delete'        => 'カスタムページ削除',
                'customview_statusupdate'  => 'カスタムページステータスの更新',
                'customview_detail'        => 'カスタムページの詳細',
                'link_index'               => '相互リンク',
                'link_saveinfo'            => '相互リンク追加/ページ編集',
                'link_save'                => '相互リンクの追加/編集',
                'link_delete'              => '相互リンクの削除',
                'link_statusupdate'        => '相互リンク状態の更新',
                'link_detail'              => '相互リンクの詳細',
                'theme_index'              => 'トピック管理',
                'theme_save'               => 'トピック管理の追加/編集',
                'theme_upload'             => 'トピックのアップロードインストール',
                'theme_delete'             => 'トピックの削除',
                'theme_download'           => 'トピックのダウンロード',
                'slide_index'              => 'トップページの輪番放送',
                'slide_saveinfo'           => 'ページの追加/編集',
                'slide_save'               => 'ローテーションの追加/編集',
                'slide_statusupdate'       => 'ローテーション状態更新',
                'slide_delete'             => 'ローテーション削除',
                'slide_detail'             => '輪番放送の詳細',
                'screeningprice_index'     => '価格のフィルタ',
                'screeningprice_save'      => 'フィルタ価格追加/編集',
                'screeningprice_delete'    => 'フィルタ価格削除',
                'region_index'             => 'ちいきかんり',
                'region_save'              => '地域の追加/編集',
                'region_delete'            => '地域削除',
                'region_codedata'          => '地域番号データの取得',
                'express_index'            => '速達管理',
                'express_save'             => '宅配便の追加/編集',
                'express_delete'           => '宅配便の削除',
                'payment_index'            => '支払い方法',
                'payment_saveinfo'         => '支払い方法インストール/編集ページ',
                'payment_save'             => '支払い方法のインストール/編集',
                'payment_delete'           => '支払い方法の削除',
                'payment_install'          => '支払い方法のインストール',
                'payment_statusupdate'     => '支払方法ステータスの更新',
                'payment_uninstall'        => '支払い方法アンインストール',
                'payment_upload'           => '支払い方法のアップロード',
                'quicknav_index'           => 'ショートカットナビゲーション',
                'quicknav_saveinfo'        => 'ショートカットナビゲーションページの追加/編集',
                'quicknav_save'            => 'ショートカットナビゲーション追加/編集',
                'quicknav_statusupdate'    => 'ショートカットナビゲーションステータスの更新',
                'quicknav_delete'          => 'ショートカットナビゲーションの削除',
                'quicknav_detail'          => 'ショートカットナビゲーションの詳細',
                'design_index'             => 'ページデザイン',
                'design_saveinfo'          => 'ページデザインページの追加/編集',
                'design_save'              => 'ページデザインの追加/編集',
                'design_statusupdate'      => 'ページデザインステータスの更新',
                'design_upload'            => 'ページデザインのインポート',
                'design_download'          => 'ページデザインのダウンロード',
                'design_sync'              => 'ページデザイン同期トップページ',
                'design_delete'            => 'ページデザインの削除',
            ]
        ],
        'brand_index' => [
            'name'  => 'ブランド管理',
            'item'  => [
                'brand_index'           => 'ブランド管理',
                'brand_saveinfo'        => 'ブランドの追加/編集ページ',
                'brand_save'            => 'ブランドの追加/編集',
                'brand_statusupdate'    => 'ブランドステータスの更新',
                'brand_delete'          => 'ブランド削除',
                'brand_detail'          => 'ブランドの詳細',
                'brandcategory_index'   => 'ブランド分類',
                'brandcategory_save'    => 'ブランド分類の追加/編集',
                'brandcategory_delete'  => 'ブランド分類の削除',
            ]
        ],
        'warehouse_index' => [
            'name'  => '倉庫管理',
            'item'  => [
                'warehouse_index'               => '倉庫管理',
                'warehouse_saveinfo'            => '倉庫の追加/編集ページ',
                'warehouse_save'                => 'ウェアハウスの追加/編集',
                'warehouse_delete'              => '倉庫削除',
                'warehouse_statusupdate'        => '倉庫ステータスの更新',
                'warehouse_detail'              => '倉庫の詳細',
                'warehousegoods_index'          => '倉庫商品管理',
                'warehousegoods_detail'         => '倉庫商品詳細',
                'warehousegoods_delete'         => '倉庫商品削除',
                'warehousegoods_statusupdate'   => '倉庫商品ステータス更新',
                'warehousegoods_goodssearch'    => '倉庫商品検索',
                'warehousegoods_goodsadd'       => '倉庫商品検索追加',
                'warehousegoods_goodsdel'       => '倉庫商品検索削除',
                'warehousegoods_inventoryinfo'  => '倉庫商品在庫編集ページ',
                'warehousegoods_inventorysave'  => '倉庫商品在庫編集',
            ]
        ],
        'app_index' => [
            'name'  => '携帯電話の管理',
            'item'  => [
                'appconfig_index'            => '基本構成',
                'appconfig_save'             => '基本構成の保存',
                'apphomenav_index'           => 'トップページのナビゲーション',
                'apphomenav_saveinfo'        => 'トップページナビゲーション追加/編集ページ',
                'apphomenav_save'            => 'トップページナビゲーションの追加/編集',
                'apphomenav_statusupdate'    => 'トップページナビゲーションステータスの更新',
                'apphomenav_delete'          => 'トップページナビゲーションの削除',
                'apphomenav_detail'          => 'トップページナビゲーションの詳細',
                'appcenternav_index'         => 'ユーザーセンターのナビゲーション',
                'appcenternav_saveinfo'      => 'ユーザーセンターナビゲーション追加/編集ページ',
                'appcenternav_save'          => 'ユーザーセンターナビゲーション追加/編集',
                'appcenternav_statusupdate'  => 'ユーザーセンターナビゲーションステータスの更新',
                'appcenternav_delete'        => 'ユーザーセンターナビゲーションの削除',
                'appcenternav_detail'        => 'ユーザーセンターナビゲーションの詳細',
                'appmini_index'              => 'アプレットリスト',
                'appmini_created'            => 'ウィジェットパッケージ生成',
                'appmini_delete'             => 'ウィジェットパッケージの削除',
                'appmini_themeupload'        => 'アプレットトピックのアップロード',
                'appmini_themesave'          => 'アプレットテーマの切り替え',
                'appmini_themedelete'        => 'アプレットテーマの切り替え',
                'appmini_themedownload'      => 'アプレットトピックのダウンロード',
                'appmini_config'             => 'アプレット構成',
                'appmini_save'               => 'アプレット構成の保存',
            ]
        ],
        'article_index' => [
            'name'  => '記事の管理',
            'item'  => [
                'article_index'           => '記事の管理',
                'article_saveinfo'        => '記事の追加/編集ページ',
                'article_save'            => '記事の追加/編集',
                'article_delete'          => '記事の削除',
                'article_statusupdate'    => '記事ステータスの更新',
                'article_detail'          => '記事の詳細',
                'articlecategory_index'   => '記事の分類',
                'articlecategory_save'    => '記事分類の編集/追加',
                'articlecategory_delete'  => '記事分類の削除',
            ]
        ],
        'data_index' => [
            'name'  => 'データ管理',
            'item'  => [
                'answer_index'          => '質疑応答メッセージ',
                'answer_reply'          => '質疑応答メッセージ返信',
                'answer_delete'         => '問答メッセージ削除',
                'answer_statusupdate'   => 'Q&Aメッセージステータスの更新',
                'answer_saveinfo'       => 'Q&Aページの追加/編集',
                'answer_save'           => 'Q&A追加/編集',
                'answer_detail'         => 'コメント詳細',
                'message_index'         => 'メッセージ管理',
                'message_delete'        => 'メッセージ削除',
                'message_detail'        => 'メッセージの詳細',
                'paylog_index'          => '支払いログ',
                'paylog_detail'         => '支払ログの詳細',
                'paylog_close'          => '支払ログのクローズ',
                'payrequestlog_index'   => '支払要求ログリスト',
                'payrequestlog_detail'  => '支払要求ログ詳細',
                'refundlog_index'       => '返金ログ',
                'refundlog_detail'      => '払戻ログの詳細',
                'integrallog_index'     => 'せきぶんログ',
                'integrallog_detail'    => '積分ログ詳細',
            ]
        ],
        'store_index' => [
            'name'  => 'アプリケーションセンター',
            'item'  => [
                'pluginsadmin_index'         => 'アプリケーション管理',
                'plugins_index'              => '呼び出し管理の適用',
                'pluginsadmin_saveinfo'      => 'ページの追加/編集の適用',
                'pluginsadmin_save'          => '追加/編集の適用',
                'pluginsadmin_statusupdate'  => 'ステータス更新の適用',
                'pluginsadmin_delete'        => '削除の適用',
                'pluginsadmin_upload'        => 'アップロードの適用',
                'pluginsadmin_download'      => 'パッケージの適用',
                'pluginsadmin_install'       => 'インストールの適用',
                'pluginsadmin_uninstall'     => 'アンインストールの適用',
                'pluginsadmin_sortsave'      => 'ソート保存の適用',
                'store_index'                => 'アプリケーションストア',
                'packageinstall_index'       => 'パッケージのインストールページ',
                'packageinstall_install'     => 'パッケージのインストール',
                'packageupgrade_upgrade'     => 'パッケージの更新',
            ]
        ],
        'tool_index' => [
            'name'  => 'ツール',
                'item'                  => [
                'cache_index'           => 'キャッシュ管理',
                'cache_statusupdate'    => 'サイトキャッシュ更新',
                'cache_templateupdate'  => 'テンプレートキャッシュの更新',
                'cache_moduleupdate'    => 'モジュールキャッシュ更新',
                'cache_logdelete'       => 'ログの削除',
                'sqlconsole_index'      => 'SQLコンソール',
                'sqlconsole_implement'  => 'SQL実行',
            ]
        ],
    ],
];
?>