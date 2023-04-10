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
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'ショッピングモールのトップページ',
        'back_to_the_home_title'                => 'トップページに戻る',
        'all_category_text'                     => 'すべての分類',
        'login_title'                           => 'ログイン＃ログイン＃',
        'register_title'                        => '登録',
        'logout_title'                          => '終了',
        'cancel_text'                           => 'キャンセル',
        'save_text'                             => '保存＃ホゾン＃',
        'more_text'                             => '詳細',
        'processing_in_text'                    => '処理中...',
        'upload_in_text'                        => 'アップロード中...',
        'navigation_main_quick_name'            => '宝箱',
        'no_relevant_data_tips'                 => '関連データなし',
        'avatar_upload_title'                   => 'アバターアップロード',
        'choice_images_text'                    => '画像を選択',
        'choice_images_error_tips'              => 'アップロードする画像を選択してください',
        'confirm_upload_title'                  => 'アップロードの確認',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => '일본어',
        'header_top_nav_left_login_first'       => 'こんにちは',
        'header_top_nav_left_login_last'        => 'ああ、ようこそ',
        // 搜索
        'search_input_placeholder'              => '実は検索は簡単^_^！',
        'search_button_text'                    => '検索けんさく',
        // 用户
        'avatar_upload_tips'                    => [
            '作業領域でマーキーを拡大縮小して移動し、切り抜きたい範囲を選択して、切り抜き幅と高さの比例を固定してください。',
            'トリミング後の効果は右側のプレビュー図に表示され、コミットを確認して有効になります。',
        ],
        'close_user_register_tips'              => 'ユーザー登録の一時停止',
        'close_user_login_tips'                 => 'ユーザーログインの一時停止',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'こんにちは、ようこそいらっしゃいました',
        'banner_right_article_title'            => 'ニュースヘッドライン',
        'design_browser_seo_title'              => 'トップページのデザイン',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'コメントデータなし',
        ],
        // 基础
        'goods_no_data_tips'                    => '商品が存在しないか削除されました',
        'panel_can_choice_spec_name'            => 'オプション仕様',
        'recommend_goods_title'                 => '見てはまた見る',
        'dynamic_scoring_name'                  => '動的評価',
        'no_scoring_data_tips'                  => '評価データがありません',
        'no_comments_data_tips'                 => '評価データなし',
        'comments_first_name'                   => 'コメント＃コメント＃',
        'admin_reply_name'                      => '管理者からの返信：',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => '商品検索',
        'filter_out_first_text'                 => 'フィルタ',
        'filter_out_last_data_text'             => 'バーデータ',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => '商品の分類',
        'no_category_data_tips'                 => '分類データなし',
        'no_sub_category_data_tips'             => 'サブカテゴリデータがありません',
        'view_category_sub_goods_name'          => 'カテゴリー下の商品を見る',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => '商品をお選びください',
        ],
        // 基础
        'browser_seo_title'                     => 'ショッピングカート',
        'goods_list_thead_base'                 => '商品情報',
        'goods_list_thead_price'                => '単価',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '金額',
        'goods_item_total_name'                 => '合計＃ゴウケイ＃',
        'summary_selected_goods_name'           => '選択された商品',
        'summary_selected_goods_unit'           => '件',
        'summary_nav_goods_total'               => '合計:',
        'summary_nav_button_name'               => '決済',
        'no_cart_data_tips'                     => 'ショッピングカートはまだ空いていますので、どうぞ',
        'no_cart_data_my_favor_name'            => 'お気に入り',
        'no_cart_data_my_order_name'            => '私の注文',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => '住所を選択してください',
            'payment_choice_tips'               => '支払いを選択してください',
        ],
        // 基础
        'browser_seo_title'                     => '注文確認',
        'exhibition_not_allow_submit_tips'      => 'プレゼンテーションでは注文の提出は許可されていません',
        'buy_item_order_title'                  => 'オーダー情報',
        'buy_item_payment_title'                => '支払の選択',
        'confirm_delivery_address_name'         => '出荷先住所の確認',
        'use_new_address_name'                  => '新しいアドレスの使用',
        'no_delivery_address_tips'              => '出荷先住所なし',
        'confirm_extraction_address_name'       => 'オートパイロット・アドレスの確認',
        'choice_take_address_name'              => '出荷先住所の選択',
        'no_take_address_tips'                  => '管理者に連絡して自己ポイントアドレスを設定してください',
        'no_address_tips'                       => '住所なし',
        'extraction_list_choice_title'          => 'じこちょうてんせんたく',
        'goods_list_thead_base'                 => '商品情報',
        'goods_list_thead_price'                => '単価',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '金額',
        'goods_item_total_name'                 => '合計＃ゴウケイ＃',
        'not_goods_tips'                        => '商品がありません',
        'not_payment_tips'                      => '支払い方法がありません',
        'user_message_title'                    => 'バイヤーコメント',
        'user_message_placeholder'              => '選択、提案記入と売り手の合意に達した説明',
        'summary_title'                         => '支払実績：',
        'summary_contact_name'                  => '連絡先：',
        'summary_address'                       => '住所：',
        'summary_submit_order_name'             => '注文の発行',
        'payment_layer_title'                   => '支払いジャンプ中、ページを閉じないでください',
        'payment_layer_content'                 => '支払いに失敗したか、長い間応答していない',
        'payment_layer_order_button_text'       => '私の注文',
        'payment_layer_tips'                    => '後で支払いを再開できます',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'すべての記事',
        'article_no_data_tips'                  => '記事が存在しないか削除されました',
        'article_id_params_tips'                => '文章IDに誤りがある',
        'release_time'                          => 'リリース時間：',
        'view_number'                           => 'ブラウズ回数：',
        'prev_article'                          => '前の章：',
        'next_article'                          => '次の章：',
        'article_category_name'                 => '記事の分類',
        'article_nav_text'                      => 'サイドバーナビゲーション',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'ページが存在しないか削除されました',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'ページが存在しないか削除されました',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => '注文IDに誤りがあります',
            'payment_choice_tips'               => '支払い方法を選択してください',
            'rating_string'                     => '非常に悪い、悪い、普通、良い、非常に良い',
            'not_choice_data_tips'              => 'まずデータを選択してください',
            'pay_url_empty_tips'                => '支払いurlアドレスが間違っている',
        ],
        // 基础
        'browser_seo_title'                     => '私の注文',
        'detail_browser_seo_title'              => '注文の詳細',
        'comments_browser_seo_title'            => '注文コメント',
        'batch_payment_name'                    => '一括支払',
        'comments_goods_list_thead_base'        => '商品情報',
        'comments_goods_list_thead_price'       => '単価',
        'comments_goods_list_thead_content'     => 'コメントの内容',
        'form_you_have_commented_tips'          => 'あなたはコメントしました',
        'form_payment_title'                    => '支払い',
        'form_payment_no_data_tips'             => '支払い方法がありません',
        'order_base_title'                      => 'オーダー情報',
        'order_base_warehouse_title'            => '出荷サービス：',
        'order_base_model_title'                => '注文パターン：',
        'order_base_order_no_title'             => '注文番号：',
        'order_base_status_title'               => 'オーダーステータス：',
        'order_base_pay_status_title'           => '支払ステータス：',
        'order_base_payment_title'              => '支払い方法：',
        'order_base_total_price_title'          => '注文総額：',
        'order_base_buy_number_title'           => '購入数：',
        'order_base_returned_quantity_title'    => '返品数量：',
        'order_base_user_note_title'            => 'ユーザーコメント：',
        'order_base_add_time_title'             => '注文時間：',
        'order_base_confirm_time_title'         => '確認時間：',
        'order_base_pay_time_title'             => '支払い時間：',
        'order_base_delivery_time_title'        => '出荷時間：',
        'order_base_collect_time_title'         => '出荷先時間：',
        'order_base_user_comments_time_title'   => 'コメント時間：',
        'order_base_cancel_time_title'          => 'キャンセル時間：',
        'order_base_express_title'              => '宅配会社：',
        'order_base_express_website_title'      => '宅配便公式サイト：',
        'order_base_express_number_title'       => '宅配便番号：',
        'order_base_price_title'                => '商品の総価格：',
        'order_base_increase_price_title'       => '追加金額：',
        'order_base_preferential_price_title'   => '特典金額：',
        'order_base_refund_price_title'         => '払戻金額：',
        'order_base_pay_price_title'            => '支払い金額：',
        'order_base_take_code_title'            => '出荷コード：',
        'order_base_take_code_no_exist_tips'    => '出荷コードが存在しません。管理者に連絡してください',
        'order_under_line_tips'                 => '現在はオフラインでの支払い方法[{:payment}]、管理者が確認してから有効になり、他の支払いが必要な場合は支払いを切り替えて支払いを再開することができます。',
        'order_delivery_tips'                   => '荷物は倉庫で梱包、出庫中…',
        'order_goods_no_data_tips'              => '注文商品データがありません',
        'order_status_operate_first_tips'       => 'できます。',
        'goods_list_thead_base'                 => '商品情報',
        'goods_list_thead_price'                => '単価',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '金額',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基礎情報',
            'goods_placeholder'     => '注文番号/商品名/型番を入力してください',
            'status'                => '受注ステータス',
            'pay_status'            => '支払ステータス',
            'total_price'           => '合計価格（元）',
            'pay_price'             => '支払金額（元）',
            'price'                 => '単価（元）',
            'order_model'           => 'オーダー・モード',
            'client_type'           => '注文プラットフォーム',
            'address'               => 'アドレス情報',
            'take'                  => 'ピッキング情報',
            'refund_price'          => '払戻金額（元）',
            'returned_quantity'     => '返品数量',
            'buy_number_count'      => '購買合計',
            'increase_price'        => '追加金額（元）',
            'preferential_price'    => '割引金額（元）',
            'payment_name'          => '支払い方法',
            'user_note'             => 'メッセージメッセージ',
            'extension'             => '拡張情報',
            'express_name'          => '宅配会社',
            'express_number'        => '速達番号',
            'is_comments'           => 'コメント',
            'confirm_time'          => '確認時間',
            'pay_time'              => '支払い時間',
            'delivery_time'         => '出荷時刻',
            'collect_time'          => '完了時間',
            'cancel_time'           => 'キャンセル時間',
            'close_time'            => 'シャットダウン時間',
            'add_time'              => '作成時間',
            'upd_time'              => '更新日時',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => '注文総額',
            'pay_price'             => '支払総額',
            'buy_number_count'      => '商品総数',
            'refund_price'          => '払い戻し',
            'returned_quantity'     => '返品',
            'price_unit'            => '元',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => '払戻事由データが空です',
        ],
        // 基础
        'browser_seo_title'                     => '注文後',
        'detail_browser_seo_title'              => '注文後の詳細',
        'view_orderaftersale_enter_name'        => 'アフターサービス注文の表示',
        'operate_delivery_name'                 => '即時返品',
        'goods_list_thead_base'                 => '商品情報',
        'goods_list_thead_price'                => '単価',
        'goods_base_price_title'                => '商品の総価格：',
        'goods_base_increase_price_title'       => '追加金額：',
        'goods_base_preferential_price_title'   => '特典金額：',
        'goods_base_refund_price_title'         => '払戻金額：',
        'goods_base_pay_price_title'            => '支払い金額：',
        'goods_base_total_price_title'          => '注文総額：',
        'base_apply_title'                      => '購買依頼情報',
        'base_apply_type_title'                 => '払戻タイプ：',
        'base_apply_status_title'               => '現在の状態：',
        'base_apply_reason_title'               => '応募理由：',
        'base_apply_number_title'               => '返品数量：',
        'base_apply_price_title'                => '払戻金額：',
        'base_apply_msg_title'                  => '返金の説明：',
        'base_apply_refundment_title'           => '返金方法：',
        'base_apply_refuse_reason_title'        => '拒否理由：',
        'base_apply_apply_time_title'           => '申し込み時間：',
        'base_apply_confirm_time_title'         => '確認時間：',
        'base_apply_delivery_time_title'        => '返品時間：',
        'base_apply_audit_time_title'           => 'レビュー時間：',
        'base_apply_cancel_time_title'          => 'キャンセル時間：',
        'base_apply_add_time_title'             => '追加時間：',
        'base_apply_upd_time_title'             => '更新日時：',
        'base_item_express_title'               => '宅配便情報',
        'base_item_express_name'                => '速達：',
        'base_item_express_number'              => '番号:',
        'base_item_express_time'                => '時間:',
        'base_item_voucher_title'               => '資格情報',
        // 表单
        'form_delivery_title'                   => '返品処理',
        'form_delivery_address_name'            => '返品先住所',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基礎情報',
            'goods_placeholder'     => '注文番号/商品名/型番を入力してください',
            'status'                => 'ステータス',
            'type'                  => '購買依頼タイプ',
            'reason'                => '理由',
            'price'                 => '払戻金額（元）',
            'number'                => '返品数量',
            'msg'                   => '返金の説明',
            'refundment'            => '払戻タイプ',
            'express_name'          => '宅配会社',
            'express_number'        => '速達番号',
            'apply_time'            => '応募期間',
            'confirm_time'          => '確認時間',
            'delivery_time'         => '返品時間',
            'audit_time'            => 'レビュー時間',
            'add_time'              => '作成時間',
            'upd_time'              => '更新日時',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'ユーザーセンター',
        'forget_password_browser_seo_title'     => 'パスワードの検索',
        'user_register_browser_seo_title'       => 'ユーザー登録',
        'user_login_browser_seo_title'          => 'ユーザーログイン',
        'password_reset_illegal_error_tips'     => 'ログインしました。パスワードをリセットするには、まず現在のアカウントを終了してください',
        'register_illegal_error_tips'           => 'すでにログインしています。新しいアカウントを登録するには、まず現在のアカウントを終了してください',
        'login_illegal_error_tips'              => 'ログインしました。繰り返しログインしないでください',
        // 页面
        // 登录
        'login_top_register_tips'               => 'アカウントはまだありませんか？',
        'login_close_tips'                      => 'ログインを一時的に閉じました',
        'login_type_username_title'             => 'アカウントパスワード',
        'login_type_mobile_title'               => '携帯電話認証コード',
        'login_type_email_title'                => 'メールボックス認証コード',
        'login_retrieve_password_title'         => 'パスワードを取り戻す',
        // 注册
        'register_top_login_tips'               => '私はすでに登録して、今すぐ',
        'register_close_tips'                   => '登録を一時的に閉じました',
        'register_type_username_title'          => 'アカウント登録',
        'register_type_mobile_title'            => '携帯電話の登録',
        'register_type_email_title'             => 'メールボックス登録',
        // 忘记密码
        'forget_password_top_login_tips'        => '既存のアカウント？',
        // 表单
        'form_item_agreement'                   => '読んで同意する',
        'form_item_agreement_message'           => '同意契約をチェックしてください',
        'form_item_service'                     => '『サービス契約書』',
        'form_item_privacy'                     => '『プライバシーポリシー』',
        'form_item_username'                    => 'ユーザー名',
        'form_item_username_message'            => 'アルファベット、数字、アンダースコア2 ~ 18文字を使用してください',
        'form_item_password'                    => 'ログインパスワード',
        'form_item_password_placeholder'        => 'ログインパスワードを入力してください',
        'form_item_password_message'            => 'パスワードフォーマット6～18文字間',
        'form_item_mobile'                      => '携帯番号',
        'form_item_mobile_placeholder'          => '携帯電話番号を入力してください',
        'form_item_mobile_message'              => '携帯番号のフォーマットが間違っている',
        'form_item_email'                       => 'メールボックス',
        'form_item_email_placeholder'           => 'メールアドレスを入力してください',
        'form_item_email_message'               => 'メールボックス形式エラー',
        'form_item_account'                     => 'ログインアカウント',
        'form_item_account_placeholder'         => 'ユーザー名/携帯電話/メールアドレスを入力してください',
        'form_item_account_message'             => 'ログインアカウントを入力してください',
        'form_item_mobile_email'                => '携帯電話/メールアドレス',
        'form_item_mobile_email_message'        => '有効な携帯電話/メールアドレスのフォーマットを入力してください',
        // 个人中心
        'base_avatar_title'                     => 'アバターの修正',
        'base_personal_title'                   => '資料を修正する',
        'base_address_title'                    => '私の住所',
        'base_message_title'                    => 'メッセージ',
        'order_nav_title'                       => '私の注文',
        'order_nav_angle_title'                 => 'すべての注文の表示',
        'various_transaction_title'             => '取引リマインダ',
        'various_transaction_tips'              => 'トランザクションリマインダは、注文のステータスと物流の状況を理解するのに役立ちます',
        'various_cart_title'                    => 'ショッピングカート',
        'various_cart_empty_title'              => 'ショッピングカートはまだ空いています',
        'various_cart_tips'                     => '買いたい商品をカートに入れて、まとめて精算する方が楽です',
        'various_favor_title'                   => '商品コレクション',
        'various_favor_empty_title'             => 'あなたはまだ商品をコレクションしていません',
        'various_favor_tips'                    => 'お気に入りの商品には、最新のキャンペーンや値下げ状況が表示されます',
        'various_browse_title'                  => '私の足跡',
        'various_browse_empty_title'            => '商品閲覧履歴が空です',
        'various_browse_tips'                   => '早くショッピングモールに行ってキャンペーンを見ましょう',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => '私の住所',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => '私の足跡',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '商品情報',
            'goods_placeholder'     => '商品名/略述/SEO情報を入力してください',
            'price'                 => '販売価格（元）',
            'original_price'        => '原価（元）',
            'add_time'              => '作成時間',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => '商品コレクション',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '商品情報',
            'goods_placeholder'     => '商品名/略述/SEO情報を入力してください',
            'price'                 => '販売価格（元）',
            'original_price'        => '原価（元）',
            'add_time'              => '作成時間',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'マイアカウント',
        // 页面
        'base_normal_title'                     => '通常使用可能',
        'base_normal_tips'                      => '普通に使えるポイント',
        'base_locking_title'                    => '現在のロック',
        'base_locking_tips'                     => '一般的なポイント取引では、取引は完了しておらず、該当するポイントをロックしている',
        'base_integral_unit'                    => 'せきぶん',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => '操作タイプ',
            'operation_integral'    => 'オペレーションインテグラル',
            'original_integral'     => 'げんしせきぶん',
            'new_integral'          => '最新積分',
            'msg'                   => '説明',
            'add_time_time'         => '時間',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => '個人情報',
        'edit_browser_seo_title'                => 'プロファイル編集',
        'form_item_nickname'                    => 'ニックネーム',
        'form_item_nickname_message'            => 'ニックネーム2～16文字間',
        'form_item_birthday'                    => '誕生日',
        'form_item_birthday_message'            => '誕生日のフォーマットが間違っている',
        'form_item_province'                    => '所在地',
        'form_item_province_message'            => '所在省最大30文字',
        'form_item_city'                        => '所在地',
        'form_item_city_message'                => '所在市の最大30文字',
        'form_item_county'                      => '所在地/県',
        'form_item_county_message'              => '所在地/県最大30文字',
        'form_item_address'                     => '詳細アドレス',
        'form_item_address_message'             => '詳細アドレス2 ~ 30文字',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => '私のニュース',
        // 动态表格
        'form_table'                => [
            'type'                  => 'メッセージ・タイプ',
            'business_type'         => 'ビジネス・タイプ',
            'title'                 => 'タイトル',
            'detail'                => '詳細',
            'is_read'               => 'ステータス',
            'add_time_time'         => '時間',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Q&A/メッセージ',
        // 表单
        'form_title'                            => '質問/メッセージ',
        'form_item_name'                        => 'ニックネーム',
        'form_item_name_message'                => 'ニックネームフォーマット1～30文字間',
        'form_item_tel'                         => '電話番号',
        'form_item_tel_message'                 => '電話番号を記入してください',
        'form_item_title'                       => 'タイトル',
        'form_item_title_message'               => 'タイトルフォーマット1～60文字間',
        'form_item_content'                     => '内容',
        'form_item_content_message'             => 'コンテンツフォーマット5～1000文字間',
        // 动态表格
        'form_table'                            => [
            'name'                  => '連絡先',
            'tel'                   => '連絡先電話番号',
            'content'               => '内容',
            'reply'                 => '返信内容',
            'reply_time_time'       => 'ふっきじかん',
            'add_time_time'         => '作成時間',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'セキュリティ設定',
        'password_update_browser_seo_title'     => 'ログインパスワードの変更-セキュリティ設定',
        'mobile_update_browser_seo_title'       => '携帯電話番号の変更-セキュリティ設定',
        'email_update_browser_seo_title'        => '電子メールボックスの変更-セキュリティ設定',
        'logout_browser_seo_title'              => 'アカウントのログアウト-セキュリティ設定',
        'original_account_check_error_tips'     => '元のアカウントのチェックに失敗しました',
        // 页面
        'logout_title'                          => 'アカウントのログアウト',
        'logout_confirm_title'                  => 'ログアウトの確認',
        'logout_confirm_tips'                   => 'アカウントをログアウトした後は復元できません。続行してもよろしいですか？',
        'email_title'                           => 'オリジナルメールボックス検証',
        'email_new_title'                       => '新しいメールボックスチェック',
        'mobile_title'                          => '元の携帯電話番号の検証',
        'mobile_new_title'                      => '新しい携帯電話番号の検証',
        'login_password_title'                  => 'ログインパスワードの変更',
    ],
];
?>