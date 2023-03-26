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
 * 模块语言包-荷兰语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Winkelcentrum startpagina',
        'back_to_the_home_title'                => 'Terug naar Home',
        'all_category_text'                     => 'Alle categorieën',
        'login_title'                           => 'Inloggen',
        'register_title'                        => 'register',
        'logout_title'                          => 'afmelden',
        'cancel_text'                           => 'annuleren',
        'save_text'                             => 'conservering',
        'more_text'                             => 'meer',
        'processing_in_text'                    => 'Verwerking',
        'upload_in_text'                        => 'Uploaden',
        'navigation_main_quick_name'            => 'Schatkist',
        'no_relevant_data_tips'                 => 'Geen relevante gegevens',
        'avatar_upload_title'                   => 'foto uploaden',
        'choice_images_text'                    => 'Afbeelding selecteren',
        'choice_images_error_tips'              => 'Selecteer de afbeelding die u wilt uploaden',
        'confirm_upload_title'                  => 'Upload bevestigen',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Hallo, welkom bij',
        'header_top_nav_left_login_first'       => 'Hallo!',
        'header_top_nav_left_login_last'        => 'Welkom bij',
        // 搜索
        'search_input_placeholder'              => 'Eigenlijk is zoeken heel eenvoudig ^ _ ^!',
        'search_button_text'                    => 'zoeken',
        // 用户
        'avatar_upload_tips'                    => [
            'Zoom in en uit in het werkgebied en verplaats het selectievakje om het te trimmen bereik te selecteren, met een vaste snijbreedte/hoogte verhouding;',
            'Het effect van het snijden wordt weergegeven in de voorbeeldafbeelding aan de rechterkant. Het wordt van kracht na bevestiging van indiening;',
        ],
        'close_user_register_tips'              => 'Gebruikersregistratie tijdelijk afsluiten',
        'close_user_login_tips'                 => 'Gebruikersaanmelding tijdelijk uitschakelen',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Hallo, welkom bij',
        'banner_right_article_title'            => 'Nieuws koppen',
        'design_browser_seo_title'              => 'Ontwerp van de startpagina',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Geen commentaargegevens',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Product bestaat niet of is verwijderd',
        'panel_can_choice_spec_name'            => 'Facultatieve specificaties',
        'recommend_goods_title'                 => 'Kijk en zie',
        'dynamic_scoring_name'                  => 'Dynamische score',
        'no_scoring_data_tips'                  => 'Geen scoregegevens',
        'no_comments_data_tips'                 => 'Geen evaluatiegegevens',
        'comments_first_name'                   => 'Commentaar op',
        'admin_reply_name'                      => 'Antwoord van de beheerder:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Product zoeken',
        'filter_out_first_text'                 => 'Filteren',
        'filter_out_last_data_text'             => 'Gegevens',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Grondstoffenclassificatie',
        'no_category_data_tips'                 => 'Geen classificatiegegevens',
        'no_sub_category_data_tips'             => 'Geen subclassificatiegegevens',
        'view_category_sub_goods_name'          => 'Bekijk producten onder categorie',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Selecteer een product',
        ],
        // 基础
        'browser_seo_title'                     => 'Winkelwagen',
        'goods_list_thead_base'                 => 'Produktinformatie',
        'goods_list_thead_price'                => 'Eenheidsprijs',
        'goods_list_thead_number'               => 'hoeveelheid',
        'goods_list_thead_total'                => 'bedrag',
        'goods_item_total_name'                 => 'totaal',
        'summary_selected_goods_name'           => 'Geselecteerd item',
        'summary_selected_goods_unit'           => 'stuk',
        'summary_nav_goods_total'               => 'totaal:',
        'summary_nav_button_name'               => 'afwikkeling',
        'no_cart_data_tips'                     => 'Uw winkelwagen is nog leeg, u kunt',
        'no_cart_data_my_favor_name'            => 'Mijn favoriet',
        'no_cart_data_my_order_name'            => 'Mijn bestelling',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Selecteer een adres',
            'payment_choice_tips'               => 'Selecteer betaling',
        ],
        // 基础
        'browser_seo_title'                     => 'bevestiging van de bestelling',
        'exhibition_not_allow_submit_tips'      => 'Bestelling indienen is niet toegestaan voor weergavetype',
        'buy_item_order_title'                  => 'Orderinformatie',
        'buy_item_payment_title'                => 'Betaling selecteren',
        'confirm_delivery_address_name'         => 'Verzendadres bevestigen',
        'use_new_address_name'                  => 'Nieuw adres gebruiken',
        'no_delivery_address_tips'              => 'Geen verzendadres',
        'confirm_extraction_address_name'       => 'Bevestig zelf afleveradres',
        'choice_take_address_name'              => 'Pick-up adres selecteren',
        'no_take_address_tips'                  => 'Neem contact op met de beheerder om het selfservice adres te configureren',
        'no_address_tips'                       => 'Geen adres',
        'extraction_list_choice_title'          => 'Self pick-up selectie',
        'goods_list_thead_base'                 => 'Produktinformatie',
        'goods_list_thead_price'                => 'Eenheidsprijs',
        'goods_list_thead_number'               => 'hoeveelheid',
        'goods_list_thead_total'                => 'bedrag',
        'goods_item_total_name'                 => 'totaal',
        'not_goods_tips'                        => 'Geen producten',
        'not_payment_tips'                      => 'Geen betaalmethode',
        'user_message_title'                    => 'Kopersbericht',
        'user_message_placeholder'              => 'Instructies voor facultatieve en aanbevolen vulling en akkoord met de verkoper',
        'summary_title'                         => 'Werkelijke betaling:',
        'summary_contact_name'                  => 'contacten:',
        'summary_address'                       => 'Adres:',
        'summary_submit_order_name'             => 'bestelling plaatsen',
        'payment_layer_title'                   => 'Betaling overslaan, gelieve de pagina niet te sluiten',
        'payment_layer_content'                 => 'Betaling mislukt of heeft lange tijd niet gereageerd',
        'payment_layer_order_button_text'       => 'Mijn bestelling',
        'payment_layer_tips'                    => 'Betaling kan opnieuw worden gestart na',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Alle artikelen',
        'article_no_data_tips'                  => 'Artikel bestaat niet of is geschrapt',
        'article_id_params_tips'                => 'Foute artikel-ID',
        'release_time'                          => 'Gepubliceerd:',
        'view_number'                           => 'Weergaven:',
        'prev_article'                          => 'Vorige:',
        'next_article'                          => 'Volgende:',
        'article_category_name'                 => 'Articleclassificatie',
        'article_nav_text'                      => 'Navigatie in de zijbalk',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'De pagina bestaat niet of is verwijderd',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'De pagina bestaat niet of is verwijderd',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Bestelling ID is onjuist',
            'payment_choice_tips'               => 'Selecteer een betaalmethode',
            'rating_string'                     => 'Heel arm, arm, gemiddeld, goed, heel goed',
            'not_choice_data_tips'              => 'Selecteer eerst gegevens',
            'pay_url_empty_tips'                => 'Het betalingsadres is onjuist',
        ],
        // 基础
        'browser_seo_title'                     => 'Mijn bestelling',
        'detail_browser_seo_title'              => 'Orderdetails',
        'comments_browser_seo_title'            => 'Orderbeoordeling',
        'batch_payment_name'                    => 'Batch betaling',
        'comments_goods_list_thead_base'        => 'Produktinformatie',
        'comments_goods_list_thead_price'       => 'Eenheidsprijs',
        'comments_goods_list_thead_content'     => 'Inhoud van commentaar',
        'form_you_have_commented_tips'          => 'U heeft al commentaar gegeven',
        'form_payment_title'                    => 'betaling',
        'form_payment_no_data_tips'             => 'Geen betaalmethode',
        'order_base_title'                      => 'Orderinformatie',
        'order_base_warehouse_title'            => 'Verzenddienst:',
        'order_base_model_title'                => 'Ordermodus:',
        'order_base_order_no_title'             => 'Ordernummer:',
        'order_base_status_title'               => 'Orderstatus:',
        'order_base_pay_status_title'           => 'Betalingsstatus:',
        'order_base_payment_title'              => 'Betalingsmethode:',
        'order_base_total_price_title'          => 'Totale bestelprijs:',
        'order_base_buy_number_title'           => 'Aankoophoeveelheid:',
        'order_base_returned_quantity_title'    => 'Terugzending hoeveelheid:',
        'order_base_user_note_title'            => 'Gebruikersbericht:',
        'order_base_add_time_title'             => 'Ordertijd:',
        'order_base_confirm_time_title'         => 'Bevestigingstijd:',
        'order_base_pay_time_title'             => 'Betalingstijd:',
        'order_base_delivery_time_title'        => 'Levertijd:',
        'order_base_collect_time_title'         => 'Ontvangsttijd:',
        'order_base_user_comments_time_title'   => 'Opmerkingstijd:',
        'order_base_cancel_time_title'          => 'Annuleringstijd:',
        'order_base_express_title'              => 'Courier Services Company:',
        'order_base_express_website_title'      => 'Officiële website van expreslevering:',
        'order_base_express_number_title'       => 'koeriersnummer:',
        'order_base_price_title'                => 'Totale prijs van goederen:',
        'order_base_increase_price_title'       => 'Verhoogde hoeveelheid:',
        'order_base_preferential_price_title'   => 'Kortingsbedrag:',
        'order_base_refund_price_title'         => 'Restitutiebedrag:',
        'order_base_pay_price_title'            => 'Betalingsbedrag:',
        'order_base_take_code_title'            => 'Opnamecode:',
        'order_base_take_code_no_exist_tips'    => 'De afhaalcode bestaat niet. Neem contact op met de beheerder',
        'order_under_line_tips'                 => 'Momenteel is het een offline betaalmethode [{:payment}] die bevestiging van de beheerder vereist voordat deze van kracht kan worden. Als andere betalingen vereist zijn, kunt u overschakelen naar betalingen en opnieuw starten.',
        'order_delivery_tips'                   => 'De goederen worden verpakt en afgeleverd vanuit het magazijn',
        'order_goods_no_data_tips'              => 'Geen gegevens over orderregels',
        'order_status_operate_first_tips'       => 'U kunt:',
        'goods_list_thead_base'                 => 'Produktinformatie',
        'goods_list_thead_price'                => 'Eenheidsprijs',
        'goods_list_thead_number'               => 'hoeveelheid',
        'goods_list_thead_total'                => 'bedrag',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Basisinformatie',
            'goods_placeholder'     => 'Voer het bestelnummer/productnaam/model in',
            'status'                => 'Orderstatus',
            'pay_status'            => 'Betalingsstatus',
            'total_price'           => 'Totale prijs (yuan)',
            'pay_price'             => 'Betalingsbedrag (yuan)',
            'price'                 => 'Eenheidsprijs (yuan)',
            'order_model'           => 'Ordermodus',
            'client_type'           => 'Bestelplatform',
            'address'               => 'Adresgegevens',
            'take'                  => 'Opnameinformatie',
            'refund_price'          => 'Restitutiebedrag (yuan)',
            'returned_quantity'     => 'Terugzending',
            'buy_number_count'      => 'Totaal aankopen',
            'increase_price'        => 'Verhoogde hoeveelheid (yuan)',
            'preferential_price'    => 'Kortingsbedrag (yuan)',
            'payment_name'          => 'Betalingsmethode',
            'user_note'             => 'Berichtinformatie',
            'extension'             => 'Uitgebreide informatie',
            'express_name'          => 'Koerierdienst',
            'express_number'        => 'koeriersnummer',
            'is_comments'           => 'Commentaar of niet',
            'confirm_time'          => 'Bevestigingstijd',
            'pay_time'              => 'Betalingstermijn',
            'delivery_time'         => 'Levertijd',
            'collect_time'          => 'Voltooiingstijd',
            'cancel_time'           => 'Tijd annuleren',
            'close_time'            => 'Sluitijd',
            'add_time'              => 'Aanmaaktijd',
            'upd_time'              => 'Updatetijd',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Totaal bestelbedrag',
            'pay_price'             => 'Totale betaling',
            'buy_number_count'      => 'Totaal aantal producten',
            'refund_price'          => 'restitutie',
            'returned_quantity'     => 'goederen retourneren',
            'price_unit'            => 'element',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Gegevens over restitutieregeling zijn leeg',
        ],
        // 基础
        'browser_seo_title'                     => 'Bestelling na verkoop',
        'detail_browser_seo_title'              => 'Bestelling After Sales Details',
        'view_orderaftersale_enter_name'        => 'After Sales orders bekijken',
        'operate_delivery_name'                 => 'Terug nu',
        'goods_list_thead_base'                 => 'Produktinformatie',
        'goods_list_thead_price'                => 'Eenheidsprijs',
        'goods_base_price_title'                => 'Totale prijs van goederen:',
        'goods_base_increase_price_title'       => 'Verhoogde hoeveelheid:',
        'goods_base_preferential_price_title'   => 'Kortingsbedrag:',
        'goods_base_refund_price_title'         => 'Restitutiebedrag:',
        'goods_base_pay_price_title'            => 'Betalingsbedrag:',
        'goods_base_total_price_title'          => 'Totale bestelprijs:',
        'base_apply_title'                      => 'Applicatiegegevens',
        'base_apply_type_title'                 => 'Type terugbetaling:',
        'base_apply_status_title'               => 'Huidige status:',
        'base_apply_reason_title'               => 'Reden voor de aanvraag:',
        'base_apply_number_title'               => 'Terugzending hoeveelheid:',
        'base_apply_price_title'                => 'Restitutiebedrag:',
        'base_apply_msg_title'                  => 'Instructies voor terugbetaling:',
        'base_apply_refundment_title'           => 'Methode van terugbetaling:',
        'base_apply_refuse_reason_title'        => 'Reden voor afwijzing:',
        'base_apply_apply_time_title'           => 'Toepassingstijd:',
        'base_apply_confirm_time_title'         => 'Bevestigingstijd:',
        'base_apply_delivery_time_title'        => 'Terugkeertijd:',
        'base_apply_audit_time_title'           => 'Audittijd:',
        'base_apply_cancel_time_title'          => 'Annuleringstijd:',
        'base_apply_add_time_title'             => 'Toegevoegd aan:',
        'base_apply_upd_time_title'             => 'Bijgewerkt:',
        'base_item_express_title'               => 'Koerierinformatie',
        'base_item_express_name'                => 'Koerier:',
        'base_item_express_number'              => 'Oneven getallen:',
        'base_item_express_time'                => 'Tijd:',
        'base_item_voucher_title'               => 'voucher',
        // 表单
        'form_delivery_title'                   => 'Terugzending',
        'form_delivery_address_name'            => 'Retouradres',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Basisinformatie',
            'goods_placeholder'     => 'Voer het bestelnummer/productnaam/model in',
            'status'                => 'staat',
            'type'                  => 'Toepassingstype',
            'reason'                => 'reden',
            'price'                 => 'Restitutiebedrag (yuan)',
            'number'                => 'Terugzending',
            'msg'                   => 'Instructies voor terugbetaling',
            'refundment'            => 'Type restitutie',
            'express_name'          => 'Koerierdienst',
            'express_number'        => 'koeriersnummer',
            'apply_time'            => 'Toepassingstijd',
            'confirm_time'          => 'Bevestigingstijd',
            'delivery_time'         => 'Terugkeertijd',
            'audit_time'            => 'Audittijd',
            'add_time'              => 'Aanmaaktijd',
            'upd_time'              => 'Updatetijd',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Gebruikerscentrum',
        'forget_password_browser_seo_title'     => 'Wachtwoord herstel',
        'user_register_browser_seo_title'       => 'Gebruikersregistratie',
        'user_login_browser_seo_title'          => 'Gebruikersaanmelding',
        'password_reset_illegal_error_tips'     => 'U bent ingelogd. Om uw wachtwoord opnieuw in te stellen, verlaat u eerst uw huidige account.',
        'register_illegal_error_tips'           => 'U bent ingelogd. Om een nieuw account aan te maken, verlaat u eerst uw huidige account',
        'login_illegal_error_tips'              => 'Reeds ingelogd, gelieve niet opnieuw in te loggen',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Heb je nog geen account?',
        'login_close_tips'                      => 'Tijdelijk gesloten login',
        'login_type_username_title'             => 'Account wachtwoord',
        'login_type_mobile_title'               => 'Mobiele verificatiecode',
        'login_type_email_title'                => 'E-mailverificatiecode',
        'login_retrieve_password_title'         => 'Wachtwoord ophalen',
        // 注册
        'register_top_login_tips'               => 'Ik heb me al ingeschreven, nu',
        'register_close_tips'                   => 'Registratie is tijdelijk gesloten',
        'register_type_username_title'          => 'Registratie van de rekening',
        'register_type_mobile_title'            => 'Mobiele registratie',
        'register_type_email_title'             => 'E-mailregistratie',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Heb je al een account?',
        // 表单
        'form_item_agreement'                   => 'Lees en ga akkoord',
        'form_item_agreement_message'           => 'Vink de Overeenkomst aan',
        'form_item_service'                     => 'Dienstverleningsovereenkomst',
        'form_item_privacy'                     => 'Privacybeleid',
        'form_item_username'                    => 'gebruikersnaam',
        'form_item_username_message'            => 'Gebruik letters, cijfers en onderstrepen voor 2 tot 18 tekens',
        'form_item_password'                    => 'Login wachtwoord',
        'form_item_password_placeholder'        => 'Voer uw login wachtwoord in',
        'form_item_password_message'            => 'Wachtwoordformaat is tussen 6 en 18 tekens',
        'form_item_mobile'                      => 'telefoonnummer',
        'form_item_mobile_placeholder'          => 'Voer uw telefoonnummer in',
        'form_item_mobile_message'              => 'Fout bij het formatteren van mobiele nummers',
        'form_item_email'                       => 'E-mail',
        'form_item_email_placeholder'           => 'Voer een e-mailadres in',
        'form_item_email_message'               => 'Fout in e-mailformaat',
        'form_item_account'                     => 'Inloggen account',
        'form_item_account_placeholder'         => 'Voer gebruikersnaam/telefoon/e-mailadres in',
        'form_item_account_message'             => 'Voer een login account in',
        'form_item_mobile_email'                => 'Mobiele telefoon/e-mail',
        'form_item_mobile_email_message'        => 'Voer een geldig telefoon-/e-mailformaat in',
        // 个人中心
        'base_avatar_title'                     => 'Avatar wijzigen',
        'base_personal_title'                   => 'Gegevens wijzigen',
        'base_address_title'                    => 'Mijn adres',
        'base_message_title'                    => 'nieuws',
        'order_nav_title'                       => 'Mijn bestelling',
        'order_nav_angle_title'                 => 'Bekijk alle bestellingen',
        'various_transaction_title'             => 'Transactieherinnering',
        'various_transaction_tips'              => 'Transactiewaarschuwingen kunnen u helpen inzicht te krijgen in de orderstatus en logistiek',
        'various_cart_title'                    => 'Winkelwagen',
        'various_cart_empty_title'              => 'Uw winkelwagen is nog leeg',
        'various_cart_tips'                     => 'Door de artikelen die u wilt kopen in een winkelwagentje te plaatsen, kunt u gemakkelijker samen afrekenen',
        'various_favor_title'                   => 'Productverzameling',
        'various_favor_empty_title'             => 'Je hebt nog geen items verzameld',
        'various_favor_tips'                    => 'Favoriete producten tonen de laatste promoties en prijsverlagingen',
        'various_browse_title'                  => 'Mijn tracks',
        'various_browse_empty_title'            => 'Uw product browsing record is leeg',
        'various_browse_tips'                   => 'Schiet op en bekijk de promotieactiviteiten in het winkelcentrum',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Mijn adres',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Mijn tracks',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Produktinformatie',
            'goods_placeholder'     => 'Voer productnaam/korte beschrijving/SEO-informatie in',
            'price'                 => 'Verkoopprijs (RMB)',
            'original_price'        => 'Originele prijs (yuan)',
            'add_time'              => 'Aanmaaktijd',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'Productverzameling',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Produktinformatie',
            'goods_placeholder'     => 'Voer productnaam/korte beschrijving/SEO-informatie in',
            'price'                 => 'Verkoopprijs (RMB)',
            'original_price'        => 'Originele prijs (yuan)',
            'add_time'              => 'Aanmaaktijd',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'Mijn punten',
        // 页面
        'base_normal_title'                     => 'Normale beschikbaarheid',
        'base_normal_tips'                      => 'Punten die normaal kunnen worden gebruikt',
        'base_locking_title'                    => 'Momenteel vergrendeld',
        'base_locking_tips'                     => 'In het algemeen wordt de transactie niet voltooid en worden de overeenkomstige punten vergrendeld',
        'base_integral_unit'                    => 'integraal',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Operationstype',
            'operation_integral'    => 'Operationele integraal',
            'original_integral'     => 'Oorspronkelijke integraal',
            'new_integral'          => 'Laatste punten',
            'msg'                   => 'beschrijven',
            'add_time_time'         => 'tijd',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'persoonsgegevens',
        'edit_browser_seo_title'                => 'Profielbewerking',
        'form_item_nickname'                    => 'bijnaam',
        'form_item_nickname_message'            => 'Bijnaam tussen 2 en 16 tekens',
        'form_item_birthday'                    => 'verjaardag',
        'form_item_birthday_message'            => 'Fout verjaardagsformulaat',
        'form_item_province'                    => 'Provincie',
        'form_item_province_message'            => 'Provincie met maximaal 30 tekens',
        'form_item_city'                        => 'Stad',
        'form_item_city_message'                => 'Stad met maximaal 30 tekens',
        'form_item_county'                      => 'District/County',
        'form_item_county_message'              => 'District/County met maximaal 30 karakters',
        'form_item_address'                     => 'Gedetailleerd adres',
        'form_item_address_message'             => 'Gedetailleerd adres 2~30 tekens',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Mijn bericht',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Berichttype',
            'business_type'         => 'Bedrijfstype',
            'title'                 => 'titel',
            'detail'                => 'details',
            'is_read'               => 'staat',
            'add_time_time'         => 'tijd',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Q&A/Bericht',
        // 表单
        'form_title'                            => 'Vragen/berichten',
        'form_item_name'                        => 'bijnaam',
        'form_item_name_message'                => 'Bijnaam formaat is tussen 1 en 30 tekens',
        'form_item_tel'                         => 'Telefoon',
        'form_item_tel_message'                 => 'Vul het telefoonnummer in',
        'form_item_title'                       => 'titel',
        'form_item_title_message'               => 'Titel formaat is tussen 1 en 60 tekens',
        'form_item_content'                     => 'inhoud',
        'form_item_content_message'             => 'Inhoudsopgave is tussen 5 en 1000 tekens',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'contacten',
            'tel'                   => 'contactnummer',
            'content'               => 'inhoud',
            'reply'                 => 'Inhoud beantwoorden',
            'reply_time_time'       => 'Reactietijd',
            'add_time_time'         => 'Aanmaaktijd',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'beveiligingsinstelling',
        'password_update_browser_seo_title'     => 'Aanmeldwachtwoordwijziging in beveiligingsinstellingen',
        'mobile_update_browser_seo_title'       => 'Mobiele nummerwijziging beveiligingsinstellingen',
        'email_update_browser_seo_title'        => 'E-mail wijziging van beveiligingsinstellingen',
        'logout_browser_seo_title'              => 'Account afmelden bij Beveiligingsinstellingen',
        'original_account_check_error_tips'     => 'Oorspronkelijke accountverificatie mislukt',
        // 页面
        'logout_title'                          => 'Annulering van de rekening',
        'logout_confirm_title'                  => 'Afmelden bevestigen',
        'logout_confirm_tips'                   => 'Het account kan niet worden hersteld nadat het is geannuleerd. Weet u zeker dat u doorgaat?',
        'email_title'                           => 'Originele e-mailverificatie',
        'email_new_title'                       => 'Nieuwe e-mailverificatie',
        'mobile_title'                          => 'Verificatie van het originele mobiele telefoonnummer',
        'mobile_new_title'                      => 'Verificatie van nieuwe mobiele telefoonnummers',
        'login_password_title'                  => 'Aanmeldwachtwoord wijzigen',
    ],
];
?>