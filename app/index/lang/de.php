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
 * 模块语言包-德语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Startseite Mall',
        'back_to_the_home_title'                => 'Zurück zur Startseite',
        'all_category_text'                     => 'Alle Kategorien',
        'login_title'                           => 'Anmelden',
        'register_title'                        => 'registrieren',
        'logout_title'                          => 'abmelden',
        'cancel_text'                           => 'Abbrechen',
        'save_text'                             => 'Konservierung',
        'more_text'                             => 'mehr',
        'processing_in_text'                    => 'Verarbeitung',
        'upload_in_text'                        => 'Hochladen',
        'navigation_main_quick_name'            => 'Schatztruhe',
        'no_relevant_data_tips'                 => 'Keine relevanten Daten',
        'avatar_upload_title'                   => 'Bild hochladen',
        'choice_images_text'                    => 'Bild auswählen',
        'choice_images_error_tips'              => 'Bitte wählen Sie das Bild zum Hochladen aus',
        'confirm_upload_title'                  => 'Upload bestätigen',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Japanisch',
        'header_top_nav_left_login_first'       => 'Hallo!',
        'header_top_nav_left_login_last'        => 'Willkommen in',
        // 搜索
        'search_input_placeholder'              => 'Eigentlich ist die Suche sehr einfach ^ _ ^!',
        'search_button_text'                    => 'Suche',
        // 用户
        'avatar_upload_tips'                    => [
            'Bitte vergrößern und verkleinern Sie den Arbeitsbereich und bewegen Sie die Auswahlbox, um den zu beschneidenden Bereich mit einem festen Schnittbreite-Höhenverhältnis auszuwählen;',
            'Der Effekt des Schneidens wird im Vorschaubild rechts angezeigt und tritt nach Bestätigung der Einreichung in Kraft;',
        ],
        'close_user_register_tips'              => 'Benutzerregistrierung vorübergehend schließen',
        'close_user_login_tips'                 => 'Benutzeranmeldung vorübergehend deaktivieren',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Hallo, willkommen bei',
        'banner_right_article_title'            => 'Schlagzeilen',
        'design_browser_seo_title'              => 'Homepage Design',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Keine Kommentardaten',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Produkt existiert nicht oder wurde gelöscht',
        'panel_can_choice_spec_name'            => 'Optionale Spezifikationen',
        'recommend_goods_title'                 => 'Schauen und sehen',
        'dynamic_scoring_name'                  => 'Dynamische Bewertung',
        'no_scoring_data_tips'                  => 'Keine Scoring-Daten',
        'no_comments_data_tips'                 => 'Keine Bewertungsdaten',
        'comments_first_name'                   => 'Kommentar zu',
        'admin_reply_name'                      => 'Antwort des Administrators:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Produktsuche',
        'filter_out_first_text'                 => 'Filtern',
        'filter_out_last_data_text'             => 'Daten',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Warenklassifizierung',
        'no_category_data_tips'                 => 'Keine Klassifizierungsdaten',
        'no_sub_category_data_tips'             => 'Keine Unterklassifizierungsdaten',
        'view_category_sub_goods_name'          => 'Produkte unter Kategorie ansehen',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Bitte wählen Sie ein Produkt',
        ],
        // 基础
        'browser_seo_title'                     => 'Warenkorb',
        'goods_list_thead_base'                 => 'Produktinformationen',
        'goods_list_thead_price'                => 'Einzelpreis',
        'goods_list_thead_number'               => 'Menge',
        'goods_list_thead_total'                => 'Geldbetrag',
        'goods_item_total_name'                 => 'insgesamt',
        'summary_selected_goods_name'           => 'Ausgewähltes Element',
        'summary_selected_goods_unit'           => 'Stück',
        'summary_nav_goods_total'               => 'insgesamt:',
        'summary_nav_button_name'               => 'Abrechnung',
        'no_cart_data_tips'                     => 'Ihr Warenkorb ist noch leer, Sie können',
        'no_cart_data_my_favor_name'            => 'Mein Favorit',
        'no_cart_data_my_order_name'            => 'Meine Bestellung',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Bitte wählen Sie eine Adresse',
            'payment_choice_tips'               => 'Bitte wählen Sie Zahlung',
        ],
        // 基础
        'browser_seo_title'                     => 'Auftragsbestätigung',
        'exhibition_not_allow_submit_tips'      => 'Auftragsübermittlung ist für Anzeigetyp nicht zulässig',
        'buy_item_order_title'                  => 'Bestellinformationen',
        'buy_item_payment_title'                => 'Zahlung auswählen',
        'confirm_delivery_address_name'         => 'Lieferadresse bestätigen',
        'use_new_address_name'                  => 'Neue Adresse verwenden',
        'no_delivery_address_tips'              => 'Keine Lieferadresse',
        'confirm_extraction_address_name'       => 'Selbstlieferadresse bestätigen',
        'choice_take_address_name'              => 'Abholadresse auswählen',
        'no_take_address_tips'                  => 'Bitte kontaktieren Sie den Administrator, um die Self-Service-Adresse zu konfigurieren',
        'no_address_tips'                       => 'Keine Adresse',
        'extraction_list_choice_title'          => 'Auswahl der Selbstabholung',
        'goods_list_thead_base'                 => 'Produktinformationen',
        'goods_list_thead_price'                => 'Einzelpreis',
        'goods_list_thead_number'               => 'Menge',
        'goods_list_thead_total'                => 'Geldbetrag',
        'goods_item_total_name'                 => 'insgesamt',
        'not_goods_tips'                        => 'Keine Produkte',
        'not_payment_tips'                      => 'Keine Zahlungsmethode',
        'user_message_title'                    => 'Mitteilung des Käufers',
        'user_message_placeholder'              => 'Anleitung zur optionalen und empfohlenen Füllung und Vereinbarung mit dem Verkäufer',
        'summary_title'                         => 'Tatsächliche Zahlung:',
        'summary_contact_name'                  => 'Kontakte:',
        'summary_address'                       => 'Adresse:',
        'summary_submit_order_name'             => 'Bestellung aufgeben',
        'payment_layer_title'                   => 'Zahlung überspringen, bitte schließen Sie die Seite nicht',
        'payment_layer_content'                 => 'Zahlung fehlgeschlagen oder hat lange Zeit nicht reagiert',
        'payment_layer_order_button_text'       => 'Meine Bestellung',
        'payment_layer_tips'                    => 'Zahlung kann nach',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Alle Artikel',
        'article_no_data_tips'                  => 'Artikel existiert nicht oder wurde gelöscht',
        'article_id_params_tips'                => 'Falsche Artikel-ID',
        'release_time'                          => 'Veröffentlicht:',
        'view_number'                           => 'Ansichten:',
        'prev_article'                          => 'Vorherige:',
        'next_article'                          => 'Weiter:',
        'article_category_name'                 => 'Artikelklassifizierung',
        'article_nav_text'                      => 'Navigation in der Seitenleiste',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'Die Seite existiert nicht oder wurde gelöscht',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'Die Seite existiert nicht oder wurde gelöscht',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Bestellnummer ist falsch',
            'payment_choice_tips'               => 'Bitte wählen Sie eine Zahlungsart',
            'rating_string'                     => 'Sehr arm, arm, durchschnittlich, gut, sehr gut',
            'not_choice_data_tips'              => 'Bitte zuerst Daten auswählen',
            'pay_url_empty_tips'                => 'Die Zahlungs-URL-Adresse ist falsch',
        ],
        // 基础
        'browser_seo_title'                     => 'Meine Bestellung',
        'detail_browser_seo_title'              => 'Bestelldetails',
        'comments_browser_seo_title'            => 'Auftragsüberprüfung',
        'batch_payment_name'                    => 'Chargenzahlung',
        'comments_goods_list_thead_base'        => 'Produktinformationen',
        'comments_goods_list_thead_price'       => 'Einzelpreis',
        'comments_goods_list_thead_content'     => 'Kommentar-Inhalt',
        'form_you_have_commented_tips'          => 'Sie haben bereits kommentiert',
        'form_payment_title'                    => 'Zahlung',
        'form_payment_no_data_tips'             => 'Keine Zahlungsmethode',
        'order_base_title'                      => 'Bestellinformationen',
        'order_base_warehouse_title'            => 'Versandservice:',
        'order_base_model_title'                => 'Bestellmodus:',
        'order_base_order_no_title'             => 'Bestellnummer:',
        'order_base_status_title'               => 'Bestellstatus:',
        'order_base_pay_status_title'           => 'Zahlungsstatus:',
        'order_base_payment_title'              => 'Zahlungsart:',
        'order_base_total_price_title'          => 'Gesamtbestellpreis:',
        'order_base_buy_number_title'           => 'Einkaufsmenge:',
        'order_base_returned_quantity_title'    => 'Rückgabemenge:',
        'order_base_user_note_title'            => 'Benutzernachricht:',
        'order_base_add_time_title'             => 'Bestellzeit:',
        'order_base_confirm_time_title'         => 'Bestätigungszeit:',
        'order_base_pay_time_title'             => 'Zahlungsfrist:',
        'order_base_delivery_time_title'        => 'Lieferzeit:',
        'order_base_collect_time_title'         => 'Empfangszeit:',
        'order_base_user_comments_time_title'   => 'Kommentarzeit:',
        'order_base_cancel_time_title'          => 'Stornierungszeit:',
        'order_base_express_title'              => 'Kurierdienst:',
        'order_base_express_website_title'      => 'Offizielle Website der Expresszustellung:',
        'order_base_express_number_title'       => 'Kuriernummer:',
        'order_base_price_title'                => 'Gesamtpreis der Waren:',
        'order_base_increase_price_title'       => 'Betrag erhöhen:',
        'order_base_preferential_price_title'   => 'Rabattbetrag:',
        'order_base_refund_price_title'         => 'Erstattungsbetrag:',
        'order_base_pay_price_title'            => 'Zahlungsbetrag:',
        'order_base_take_code_title'            => 'Abholcode:',
        'order_base_take_code_no_exist_tips'    => 'Der Abholcode existiert nicht. Bitte wenden Sie sich an den Administrator',
        'order_under_line_tips'                 => 'Derzeit handelt es sich um eine Offline-Zahlungsmethode [{:payment}], die eine Bestätigung durch den Administrator erfordert, bevor sie wirksam werden kann.',
        'order_delivery_tips'                   => 'Die Ware wird verpackt und aus dem Lager geliefert',
        'order_goods_no_data_tips'              => 'Keine Bestellposition Artikeldaten',
        'order_status_operate_first_tips'       => 'Sie können',
        'goods_list_thead_base'                 => 'Produktinformationen',
        'goods_list_thead_price'                => 'Einzelpreis',
        'goods_list_thead_number'               => 'Menge',
        'goods_list_thead_total'                => 'Geldbetrag',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Grundlegende Informationen',
            'goods_placeholder'     => 'Bitte geben Sie die Bestellnummer/Produktname/Modell ein',
            'status'                => 'Auftragsstatus',
            'pay_status'            => 'Zahlungsstatus',
            'total_price'           => 'Gesamtpreis (Yuan)',
            'pay_price'             => 'Zahlungsbetrag (Yuan)',
            'price'                 => 'Einzelpreis (Yuan)',
            'order_model'           => 'Bestellmodus',
            'client_type'           => 'Bestellplattform',
            'address'               => 'Adressinformationen',
            'take'                  => 'Abholinformationen',
            'refund_price'          => 'Erstattungsbetrag (Yuan)',
            'returned_quantity'     => 'Rückgabemenge',
            'buy_number_count'      => 'Einkäufe insgesamt',
            'increase_price'        => 'Betrag erhöhen (Yuan)',
            'preferential_price'    => 'Rabattbetrag (Yuan)',
            'payment_name'          => 'Zahlungsmethode',
            'user_note'             => 'Nachrichteninformationen',
            'extension'             => 'Erweiterte Informationen',
            'express_name'          => 'Kurierdienst',
            'express_number'        => 'Kuriernummer',
            'is_comments'           => 'Kommentar oder nicht',
            'confirm_time'          => 'Bestätigungszeit',
            'pay_time'              => 'Zahlungsfrist',
            'delivery_time'         => 'Lieferzeit',
            'collect_time'          => 'Fertigstellungszeit',
            'cancel_time'           => 'Zeit abbrechen',
            'close_time'            => 'Schließzeit',
            'add_time'              => 'Erstellungszeit',
            'upd_time'              => 'Aktualisierungszeit',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Gesamtbetrag der Bestellung',
            'pay_price'             => 'Gesamtzahlung',
            'buy_number_count'      => 'Gesamtzahl der Erzeugnisse',
            'refund_price'          => 'Erstattung',
            'returned_quantity'     => 'Rücksendung von Waren',
            'price_unit'            => 'Element',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Daten zum Erstattungsgrund sind leer',
        ],
        // 基础
        'browser_seo_title'                     => 'After Sales bestellen',
        'detail_browser_seo_title'              => 'Details zur Bestellung nach dem Verkauf',
        'view_orderaftersale_enter_name'        => 'Anzeigen von Kundenaufträgen',
        'operate_delivery_name'                 => 'Jetzt zurückkehren',
        'goods_list_thead_base'                 => 'Produktinformationen',
        'goods_list_thead_price'                => 'Einzelpreis',
        'goods_base_price_title'                => 'Gesamtpreis der Waren:',
        'goods_base_increase_price_title'       => 'Betrag erhöhen:',
        'goods_base_preferential_price_title'   => 'Rabattbetrag:',
        'goods_base_refund_price_title'         => 'Erstattungsbetrag:',
        'goods_base_pay_price_title'            => 'Zahlungsbetrag:',
        'goods_base_total_price_title'          => 'Gesamtbestellpreis:',
        'base_apply_title'                      => 'Anwendungsinformationen',
        'base_apply_type_title'                 => 'Art der Rückerstattung:',
        'base_apply_status_title'               => 'Aktueller Status:',
        'base_apply_reason_title'               => 'Grund für die Bewerbung:',
        'base_apply_number_title'               => 'Rückgabemenge:',
        'base_apply_price_title'                => 'Erstattungsbetrag:',
        'base_apply_msg_title'                  => 'Rückerstattungsanweisungen:',
        'base_apply_refundment_title'           => 'Erstattungsmethode:',
        'base_apply_refuse_reason_title'        => 'Ablehnungsgrund:',
        'base_apply_apply_time_title'           => 'Anwendungszeit:',
        'base_apply_confirm_time_title'         => 'Bestätigungszeit:',
        'base_apply_delivery_time_title'        => 'Rückgabe:',
        'base_apply_audit_time_title'           => 'Prüfungszeit:',
        'base_apply_cancel_time_title'          => 'Stornierungszeit:',
        'base_apply_add_time_title'             => 'Hinzugefügt:',
        'base_apply_upd_time_title'             => 'Aktualisiert:',
        'base_item_express_title'               => 'Kurierinformation',
        'base_item_express_name'                => 'Kurier:',
        'base_item_express_number'              => 'Ungerade Zahlen:',
        'base_item_express_time'                => 'Zeit:',
        'base_item_voucher_title'               => 'Gutschein',
        // 表单
        'form_delivery_title'                   => 'Rückgabe',
        'form_delivery_address_name'            => 'Rücksendeadresse',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Grundlegende Informationen',
            'goods_placeholder'     => 'Bitte geben Sie die Bestellnummer/Produktname/Modell ein',
            'status'                => 'Zustand',
            'type'                  => 'Anwendungstyp',
            'reason'                => 'Grund',
            'price'                 => 'Erstattungsbetrag (Yuan)',
            'number'                => 'Rückgabemenge',
            'msg'                   => 'Rückerstattungsanweisungen',
            'refundment'            => 'Art der Erstattung',
            'express_name'          => 'Kurierdienst',
            'express_number'        => 'Kuriernummer',
            'apply_time'            => 'Anwendungszeit',
            'confirm_time'          => 'Bestätigungszeit',
            'delivery_time'         => 'Rückgabefrist',
            'audit_time'            => 'Prüfungszeit',
            'add_time'              => 'Erstellungszeit',
            'upd_time'              => 'Aktualisierungszeit',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Benutzercenter',
        'forget_password_browser_seo_title'     => 'Passwortwiederherstellung',
        'user_register_browser_seo_title'       => 'Benutzerregistrierung',
        'user_login_browser_seo_title'          => 'Benutzeranmeldung',
        'password_reset_illegal_error_tips'     => 'Um Ihr Passwort zurückzusetzen, verlassen Sie bitte zuerst Ihr aktuelles Konto.',
        'register_illegal_error_tips'           => 'Sie haben sich eingeloggt. Um ein neues Konto anzulegen, verlassen Sie bitte zuerst Ihr aktuelles Konto.',
        'login_illegal_error_tips'              => 'Bereits eingeloggt, bitte nicht erneut einloggen',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Haben Sie noch kein Konto?',
        'login_close_tips'                      => 'Vorübergehend gesperrte Anmeldung',
        'login_type_username_title'             => 'Passwort für das Konto',
        'login_type_mobile_title'               => 'Mobile Verifizierungscode',
        'login_type_email_title'                => 'E-Mail-Verifizierungscode',
        'login_retrieve_password_title'         => 'Passwort abrufen',
        // 注册
        'register_top_login_tips'               => 'Ich habe mich bereits registriert, jetzt',
        'register_close_tips'                   => 'Die Registrierung ist vorübergehend geschlossen',
        'register_type_username_title'          => 'Kontoregistrierung',
        'register_type_mobile_title'            => 'Mobile Registrierung',
        'register_type_email_title'             => 'E-Mail Registrierung',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Haben Sie schon ein Konto?',
        // 表单
        'form_item_agreement'                   => 'Lesen und zustimmen',
        'form_item_agreement_message'           => 'Bitte markieren Sie das Agree Agreement',
        'form_item_service'                     => 'Dienstleistungsvertrag',
        'form_item_privacy'                     => 'Datenschutzerklärung',
        'form_item_username'                    => 'Benutzername',
        'form_item_username_message'            => 'Bitte verwenden Sie Buchstaben, Zahlen und Unterstriche für 2 bis 18 Zeichen',
        'form_item_password'                    => 'Login Passwort',
        'form_item_password_placeholder'        => 'Bitte geben Sie Ihr Login-Passwort ein',
        'form_item_password_message'            => 'Das Kennwortformat liegt zwischen 6- und 18-Zeichen',
        'form_item_mobile'                      => 'Telefonnummer',
        'form_item_mobile_placeholder'          => 'Bitte geben Sie Ihre Telefonnummer ein',
        'form_item_mobile_message'              => 'Fehler beim Formatieren von Mobilfunknummern',
        'form_item_email'                       => 'E-Mail',
        'form_item_email_placeholder'           => 'Bitte geben Sie eine E-Mail-Adresse ein',
        'form_item_email_message'               => 'Fehler im E-Mail-Format',
        'form_item_account'                     => 'Login-Konto',
        'form_item_account_placeholder'         => 'Bitte geben Sie Benutzername/Telefon/E-Mail ein',
        'form_item_account_message'             => 'Bitte geben Sie ein Login-Konto ein',
        'form_item_mobile_email'                => 'Mobiltelefon/E-Mail',
        'form_item_mobile_email_message'        => 'Bitte geben Sie ein gültiges Telefon-/E-Mail-Format ein',
        // 个人中心
        'base_avatar_title'                     => 'Avatar ändern',
        'base_personal_title'                   => 'Daten ändern',
        'base_address_title'                    => 'Meine Adresse',
        'base_message_title'                    => 'News',
        'order_nav_title'                       => 'Meine Bestellung',
        'order_nav_angle_title'                 => 'Alle Bestellungen anzeigen',
        'various_transaction_title'             => 'Transaktions-Erinnerung',
        'various_transaction_tips'              => 'Transaktionswarnungen können Ihnen helfen, den Bestellstatus und die Logistik zu verstehen',
        'various_cart_title'                    => 'Warenkorb',
        'various_cart_empty_title'              => 'Ihr Warenkorb ist noch leer',
        'various_cart_tips'                     => 'Wenn Sie die Artikel, die Sie kaufen möchten, in einen Warenkorb legen, wird es einfacher, zusammen zu bezahlen',
        'various_favor_title'                   => 'Produktsammlung',
        'various_favor_empty_title'             => 'Sie haben noch keine Artikel gesammelt',
        'various_favor_tips'                    => 'In den Lieblingsprodukten werden die neuesten Aktionen und Preisnachlässe angezeigt',
        'various_browse_title'                  => 'Meine Tracks',
        'various_browse_empty_title'            => 'Ihr Produkt-Browsing-Datensatz ist leer',
        'various_browse_tips'                   => 'Beeilen Sie sich und schauen Sie sich die Werbeaktivitäten im Einkaufszentrum an',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Meine Adresse',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Meine Tracks',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Produktinformationen',
            'goods_placeholder'     => 'Bitte geben Sie Produktname/kurze Beschreibung/SEO-Informationen ein',
            'price'                 => 'Verkaufspreis (RMB)',
            'original_price'        => 'Originalpreis (Yuan)',
            'add_time'              => 'Erstellungszeit',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'Produktsammlung',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Produktinformationen',
            'goods_placeholder'     => 'Bitte geben Sie Produktname/kurze Beschreibung/SEO-Informationen ein',
            'price'                 => 'Verkaufspreis (RMB)',
            'original_price'        => 'Originalpreis (Yuan)',
            'add_time'              => 'Erstellungszeit',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'Meine Punkte',
        // 页面
        'base_normal_title'                     => 'Normale Verfügbarkeit',
        'base_normal_tips'                      => 'Punkte, die normal verwendet werden können',
        'base_locking_title'                    => 'Aktuell gesperrt',
        'base_locking_tips'                     => 'In der Regel ist die Transaktion nicht abgeschlossen und entsprechende Punkte werden gesperrt',
        'base_integral_unit'                    => 'Integral',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Betriebsart',
            'operation_integral'    => 'Operatives Integral',
            'original_integral'     => 'Ursprüngliches Integral',
            'new_integral'          => 'Aktuelle Punkte',
            'msg'                   => 'Beschreibung',
            'add_time_time'         => 'Zeit',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'personenbezogene Daten',
        'edit_browser_seo_title'                => 'Profilbearbeitung',
        'form_item_nickname'                    => 'Spitzname',
        'form_item_nickname_message'            => 'Spitzname zwischen 2 und 16 Zeichen',
        'form_item_birthday'                    => 'Geburtstag',
        'form_item_birthday_message'            => 'Falsches Geburtstagsformat',
        'form_item_province'                    => 'Provinz',
        'form_item_province_message'            => 'Provinz mit maximal 30 Zeichen',
        'form_item_city'                        => 'Stadt',
        'form_item_city_message'                => 'Stadt mit maximal 30 Zeichen',
        'form_item_county'                      => 'Kreis/Kreis',
        'form_item_county_message'              => 'Bezirk/Kreis mit maximal 30 Zeichen',
        'form_item_address'                     => 'Detaillierte Adresse',
        'form_item_address_message'             => 'Detaillierte Adresse 2~30 Zeichen',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Meine Nachricht',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Nachrichtentyp',
            'business_type'         => 'Unternehmenstyp',
            'title'                 => 'Titel',
            'detail'                => 'Details',
            'is_read'               => 'Zustand',
            'add_time_time'         => 'Zeit',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'F&A/Nachricht',
        // 表单
        'form_title'                            => 'Fragen/Nachrichten',
        'form_item_name'                        => 'Spitzname',
        'form_item_name_message'                => 'Das Nickname-Format liegt zwischen 1 und 30 Zeichen',
        'form_item_tel'                         => 'Telefon',
        'form_item_tel_message'                 => 'Bitte geben Sie die Telefonnummer ein',
        'form_item_title'                       => 'Titel',
        'form_item_title_message'               => 'Titelformat zwischen 1 und 60 Zeichen',
        'form_item_content'                     => 'Inhalt',
        'form_item_content_message'             => 'Das Inhaltsformat liegt zwischen 5- und 1000-Zeichen',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'Kontakte',
            'tel'                   => 'Kontaktnummer',
            'content'               => 'Inhalt',
            'reply'                 => 'Antwortinhalt',
            'reply_time_time'       => 'Reaktionszeit',
            'add_time_time'         => 'Erstellungszeit',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'Sicherheitseinstellung',
        'password_update_browser_seo_title'     => 'Login Passwort Änderung in Sicherheitseinstellungen',
        'mobile_update_browser_seo_title'       => 'Änderung der Mobilfunknummer in Sicherheitseinstellungen',
        'email_update_browser_seo_title'        => 'E-Mail Änderung der Sicherheitseinstellungen',
        'logout_browser_seo_title'              => 'Kontoabmelden in Sicherheitseinstellungen',
        'original_account_check_error_tips'     => 'Ursprüngliche Kontoüberprüfung fehlgeschlagen',
        // 页面
        'logout_title'                          => 'Kontoauflösung',
        'logout_confirm_title'                  => 'Abmeldung bestätigen',
        'logout_confirm_tips'                   => 'Das Konto kann nach der Kündigung nicht wiederhergestellt werden. Sind Sie sicher, dass Sie fortfahren?',
        'email_title'                           => 'Originale E-Mail-Bestätigung',
        'email_new_title'                       => 'Neue E-Mail-Überprüfung',
        'mobile_title'                          => 'Überprüfung der ursprünglichen Handynummer',
        'mobile_new_title'                      => 'Überprüfung der neuen Handynummer',
        'login_password_title'                  => 'Änderung des Anmeldepassworts',
    ],
];
?>