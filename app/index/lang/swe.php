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
 * 模块语言包-瑞典语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Mall hemsida',
        'back_to_the_home_title'                => 'Tillbaka till hemmet',
        'all_category_text'                     => 'Alla kategorier',
        'login_title'                           => 'Logga in',
        'register_title'                        => 'register',
        'logout_title'                          => 'Logga ut',
        'cancel_text'                           => 'avbryt',
        'save_text'                             => 'Bevarande',
        'more_text'                             => 'mer',
        'processing_in_text'                    => 'Bearbetning',
        'upload_in_text'                        => 'Ladda upp',
        'navigation_main_quick_name'            => 'Skattkistan',
        'no_relevant_data_tips'                 => 'Inga relevanta uppgifter',
        'avatar_upload_title'                   => 'uppladdning av bild',
        'choice_images_text'                    => 'Välj bild',
        'choice_images_error_tips'              => 'Välj bilden att ladda upp',
        'confirm_upload_title'                  => 'Bekräfta uppladdning',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Välkommen',
        'header_top_nav_left_login_first'       => 'Hallå!',
        'header_top_nav_left_login_last'        => 'Välkommen till',
        // 搜索
        'search_input_placeholder'              => 'Faktiskt är det väldigt enkelt att söka ^_^!',
        'search_button_text'                    => 'söka',
        // 用户
        'avatar_upload_tips'                    => [
            'Zooma in och ut i arbetsområdet och flytta urvalsrutan för att välja det område som ska trimmas, med ett fast klippbredd/höjd förhållande;',
            'Effekten av skärning visas i förhandsgranskningsbilden till höger och träder i kraft efter bekräftelse av inlämning;',
        ],
        'close_user_register_tips'              => 'Tillfälligt stängd användarregistrering',
        'close_user_login_tips'                 => 'Stäng tillfälligt av användarinloggning',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Hej, välkommen till',
        'banner_right_article_title'            => 'Nyheter rubriker',
        'design_browser_seo_title'              => 'Startsidans utformning',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Inga kommentarer',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Produkten finns inte eller har tagits bort',
        'panel_can_choice_spec_name'            => 'Frivilliga specifikationer',
        'recommend_goods_title'                 => 'Titta och se',
        'dynamic_scoring_name'                  => 'Dynamisk poängsättning',
        'no_scoring_data_tips'                  => 'Inga poängdata',
        'no_comments_data_tips'                 => 'Inga utvärderingsuppgifter',
        'comments_first_name'                   => 'Kommentar på',
        'admin_reply_name'                      => 'Handläggarens svar:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Produktsökning',
        'filter_out_first_text'                 => 'Filtrera ut',
        'filter_out_last_data_text'             => 'Data',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Varuklassificering',
        'no_category_data_tips'                 => 'Inga klassificeringsuppgifter',
        'no_sub_category_data_tips'             => 'Inga underklassificeringsuppgifter',
        'view_category_sub_goods_name'          => 'Visa produkter under kategori',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Välj en produkt',
        ],
        // 基础
        'browser_seo_title'                     => 'Varukorg',
        'goods_list_thead_base'                 => 'Produktinformation',
        'goods_list_thead_price'                => 'Enhetspris',
        'goods_list_thead_number'               => 'kvantitet',
        'goods_list_thead_total'                => 'belopp',
        'goods_item_total_name'                 => 'totalt',
        'summary_selected_goods_name'           => 'Markerat objekt',
        'summary_selected_goods_unit'           => 'bit',
        'summary_nav_goods_total'               => 'Totalt:',
        'summary_nav_button_name'               => 'avveckling',
        'no_cart_data_tips'                     => 'Din varukorg är fortfarande tom, du kan',
        'no_cart_data_my_favor_name'            => 'Min favorit',
        'no_cart_data_my_order_name'            => 'Min order',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Välj en adress',
            'payment_choice_tips'               => 'Välj betalning',
        ],
        // 基础
        'browser_seo_title'                     => 'Bekräftelse av order',
        'exhibition_not_allow_submit_tips'      => 'Beställningar är inte tillåtna för visningstyp',
        'buy_item_order_title'                  => 'Beställningsinformation',
        'buy_item_payment_title'                => 'Välj betalning',
        'confirm_delivery_address_name'         => 'Bekräfta leveransadress',
        'use_new_address_name'                  => 'Använd ny adress',
        'no_delivery_address_tips'              => 'Ingen leveransadress',
        'confirm_extraction_address_name'       => 'Bekräfta självleveransadress',
        'choice_take_address_name'              => 'Välj upphämtningsadress',
        'no_take_address_tips'                  => 'Kontakta administratören för att konfigurera självbetjäningsadressen',
        'no_address_tips'                       => 'Ingen adress',
        'extraction_list_choice_title'          => 'Val av självupphämtning',
        'goods_list_thead_base'                 => 'Produktinformation',
        'goods_list_thead_price'                => 'Enhetspris',
        'goods_list_thead_number'               => 'kvantitet',
        'goods_list_thead_total'                => 'belopp',
        'goods_item_total_name'                 => 'totalt',
        'not_goods_tips'                        => 'Inga produkter',
        'not_payment_tips'                      => 'Ingen betalningsmetod',
        'user_message_title'                    => 'Köparmeddelande',
        'user_message_placeholder'              => 'Instruktioner för valfri och rekommenderad fyllning och överenskommelse med säljaren',
        'summary_title'                         => 'Faktisk betalning:',
        'summary_contact_name'                  => 'Kontakter:',
        'summary_address'                       => 'Adress:',
        'summary_submit_order_name'             => 'Placera order',
        'payment_layer_title'                   => 'Betalning hoppar över, stäng inte sidan',
        'payment_layer_content'                 => 'Betalningen misslyckades eller svarade inte på länge',
        'payment_layer_order_button_text'       => 'Min order',
        'payment_layer_tips'                    => 'Betalningen kan startas om efter',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Alla artiklar',
        'article_no_data_tips'                  => 'Artikel finns inte eller har utgått',
        'article_id_params_tips'                => 'Felaktigt artikelID',
        'release_time'                          => 'Publicerad:',
        'view_number'                           => 'Vyer:',
        'prev_article'                          => 'Föregående:',
        'next_article'                          => 'Nästa:',
        'article_category_name'                 => 'Artikelklassificering',
        'article_nav_text'                      => 'Navigering i sidoraden',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'Sidan finns inte eller har tagits bort',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'Sidan finns inte eller har tagits bort',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Order ID är felaktigt',
            'payment_choice_tips'               => 'Välj betalningsmetod',
            'rating_string'                     => 'Mycket dålig, dålig, genomsnittlig, bra, mycket bra',
            'not_choice_data_tips'              => 'Välj data först',
            'pay_url_empty_tips'                => 'Adressen för betalning är felaktig',
        ],
        // 基础
        'browser_seo_title'                     => 'Min order',
        'detail_browser_seo_title'              => 'Beställningsinformation',
        'comments_browser_seo_title'            => 'Order Granskning',
        'batch_payment_name'                    => 'Satsbetalning',
        'comments_goods_list_thead_base'        => 'Produktinformation',
        'comments_goods_list_thead_price'       => 'Enhetspris',
        'comments_goods_list_thead_content'     => 'Kommentarinnehåll',
        'form_you_have_commented_tips'          => 'Du har redan kommenterat',
        'form_payment_title'                    => 'Betalning',
        'form_payment_no_data_tips'             => 'Ingen betalningsmetod',
        'order_base_title'                      => 'Beställningsinformation',
        'order_base_warehouse_title'            => 'Sjöfart:',
        'order_base_model_title'                => 'Ordningsläge:',
        'order_base_order_no_title'             => 'Beställningsnummer:',
        'order_base_status_title'               => 'Beställningsstatus:',
        'order_base_pay_status_title'           => 'Betalningsstatus:',
        'order_base_payment_title'              => 'Betalningsmetod:',
        'order_base_total_price_title'          => 'Totalt orderpris:',
        'order_base_buy_number_title'           => 'Inköpskvantitet:',
        'order_base_returned_quantity_title'    => 'Returkvantitet:',
        'order_base_user_note_title'            => 'Användarmeddelande:',
        'order_base_add_time_title'             => 'Beställningstid:',
        'order_base_confirm_time_title'         => 'Bekräftelsetid:',
        'order_base_pay_time_title'             => 'Betalningstid:',
        'order_base_delivery_time_title'        => 'Leveranstid:',
        'order_base_collect_time_title'         => 'Mottagningstid:',
        'order_base_user_comments_time_title'   => 'Kommentartid:',
        'order_base_cancel_time_title'          => 'Avbokning:',
        'order_base_express_title'              => 'Courier Services Company:',
        'order_base_express_website_title'      => 'Officiell webbplats för expressleverans:',
        'order_base_express_number_title'       => 'Kurirens nummer:',
        'order_base_price_title'                => 'Totalpris på varor:',
        'order_base_increase_price_title'       => 'Öka belopp:',
        'order_base_preferential_price_title'   => 'Rabattbelopp:',
        'order_base_refund_price_title'         => 'Återbetalningsbelopp:',
        'order_base_pay_price_title'            => 'Betalningsbelopp:',
        'order_base_take_code_title'            => 'Upphämtningskod:',
        'order_base_take_code_no_exist_tips'    => 'Upphämtningskoden finns inte. Kontakta administratören',
        'order_under_line_tips'                 => 'För närvarande är det en offline betalningsmetod [{:payment}] som kräver bekräftelse av administratören innan den kan träda i kraft. Om andra betalningar krävs kan du byta betalning och initiera betalningar igen.',
        'order_delivery_tips'                   => 'Varorna packas och levereras från lagret',
        'order_goods_no_data_tips'              => 'Inga uppgifter om orderraden',
        'order_status_operate_first_tips'       => 'Du kan',
        'goods_list_thead_base'                 => 'Produktinformation',
        'goods_list_thead_price'                => 'Enhetspris',
        'goods_list_thead_number'               => 'kvantitet',
        'goods_list_thead_total'                => 'belopp',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Grundläggande information',
            'goods_placeholder'     => 'Ange ordernummer/produktnamn/modell',
            'status'                => 'Beställningsstatus',
            'pay_status'            => 'Betalningsstatus',
            'total_price'           => 'Totalt pris (yuan)',
            'pay_price'             => 'Betalningsbelopp (yuan)',
            'price'                 => 'Enhetspris (yuan)',
            'order_model'           => 'Ordningsläge',
            'client_type'           => 'Beställningsplattform',
            'address'               => 'Adressinformation',
            'take'                  => 'Upphämtningsinformation',
            'refund_price'          => 'Återbetalningsbelopp (yuan)',
            'returned_quantity'     => 'Returkvantitet',
            'buy_number_count'      => 'Totalt inköp',
            'increase_price'        => 'Öka belopp (yuan)',
            'preferential_price'    => 'Rabattbelopp (yuan)',
            'payment_name'          => 'Betalningsmetod',
            'user_note'             => 'Meddelandeinformation',
            'extension'             => 'Utökad information',
            'express_name'          => 'Courier Services Company',
            'express_number'        => 'kurirens nummer',
            'is_comments'           => 'Kommentera eller inte',
            'confirm_time'          => 'Bekräftelsetid',
            'pay_time'              => 'Betalningstid',
            'delivery_time'         => 'Leveranstid',
            'collect_time'          => 'Slutförandetid',
            'cancel_time'           => 'Avbryt tid',
            'close_time'            => 'Stängningstid',
            'add_time'              => 'Skapandetid',
            'upd_time'              => 'Uppdateringstid',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Totalt orderbelopp',
            'pay_price'             => 'Totalt stöd',
            'buy_number_count'      => 'Totalt antal produkter',
            'refund_price'          => 'återbetalning',
            'returned_quantity'     => 'returvaror',
            'price_unit'            => 'element',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Uppgifterna om återbetalningskälet är tomma',
        ],
        // 基础
        'browser_seo_title'                     => 'Beställning efter försäljning',
        'detail_browser_seo_title'              => 'Order After Sales Details',
        'view_orderaftersale_enter_name'        => 'Visa beställningar efter försäljning',
        'operate_delivery_name'                 => 'Återgå nu',
        'goods_list_thead_base'                 => 'Produktinformation',
        'goods_list_thead_price'                => 'Enhetspris',
        'goods_base_price_title'                => 'Totalpris på varor:',
        'goods_base_increase_price_title'       => 'Öka belopp:',
        'goods_base_preferential_price_title'   => 'Rabattbelopp:',
        'goods_base_refund_price_title'         => 'Återbetalningsbelopp:',
        'goods_base_pay_price_title'            => 'Betalningsbelopp:',
        'goods_base_total_price_title'          => 'Totalt orderpris:',
        'base_apply_title'                      => 'Ansökningsinformation',
        'base_apply_type_title'                 => 'Typ av bidrag:',
        'base_apply_status_title'               => 'Aktuell status:',
        'base_apply_reason_title'               => 'Skäl till ansökan:',
        'base_apply_number_title'               => 'Returkvantitet:',
        'base_apply_price_title'                => 'Återbetalningsbelopp:',
        'base_apply_msg_title'                  => 'Instruktioner för återbetalning:',
        'base_apply_refundment_title'           => 'Bidragsmetod:',
        'base_apply_refuse_reason_title'        => 'Skäl till avslag:',
        'base_apply_apply_time_title'           => 'Tillämpningstid:',
        'base_apply_confirm_time_title'         => 'Bekräftelsetid:',
        'base_apply_delivery_time_title'        => 'Returtid:',
        'base_apply_audit_time_title'           => 'Revisionstid:',
        'base_apply_cancel_time_title'          => 'Avbokning:',
        'base_apply_add_time_title'             => 'Tillagd den:',
        'base_apply_upd_time_title'             => 'Uppdaterad:',
        'base_item_express_title'               => 'Kuririnformation',
        'base_item_express_name'                => 'Kurir:',
        'base_item_express_number'              => 'Udda tal:',
        'base_item_express_time'                => 'Tid:',
        'base_item_voucher_title'               => 'kupong',
        // 表单
        'form_delivery_title'                   => 'Returneringsåtgärd',
        'form_delivery_address_name'            => 'Returneringsadress',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Grundläggande information',
            'goods_placeholder'     => 'Ange ordernummer/produktnamn/modell',
            'status'                => 'tillstånd',
            'type'                  => 'Programtyp',
            'reason'                => 'skäl',
            'price'                 => 'Återbetalningsbelopp (yuan)',
            'number'                => 'Returkvantitet',
            'msg'                   => 'Instruktioner för återbetalning',
            'refundment'            => 'Typ av bidrag',
            'express_name'          => 'Courier Services Company',
            'express_number'        => 'kurirens nummer',
            'apply_time'            => 'Tillämpningstid',
            'confirm_time'          => 'Bekräftelsetid',
            'delivery_time'         => 'Returneringstid',
            'audit_time'            => 'Revisionstid',
            'add_time'              => 'Skapandetid',
            'upd_time'              => 'Uppdateringstid',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Användarcenter',
        'forget_password_browser_seo_title'     => 'Återställning av lösenord',
        'user_register_browser_seo_title'       => 'Användarregistrering',
        'user_login_browser_seo_title'          => 'Användarinloggning',
        'password_reset_illegal_error_tips'     => 'Du har loggat in. För att återställa ditt lösenord, vänligen avsluta ditt nuvarande konto först',
        'register_illegal_error_tips'           => 'Du har loggat in. För att registrera ett nytt konto, vänligen avsluta ditt nuvarande konto först',
        'login_illegal_error_tips'              => 'Redan inloggad, vänligen logga inte in igen',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Har du inget konto än?',
        'login_close_tips'                      => 'Tillfälligt stängd inloggning',
        'login_type_username_title'             => 'Kontolösenord',
        'login_type_mobile_title'               => 'Mobil verifieringskod',
        'login_type_email_title'                => 'Verifieringskod för e-post',
        'login_retrieve_password_title'         => 'Hämta lösenord',
        // 注册
        'register_top_login_tips'               => 'Jag har redan registrerat mig, nu',
        'register_close_tips'                   => 'Registreringen är tillfälligt stängd',
        'register_type_username_title'          => 'Kontoregistrering',
        'register_type_mobile_title'            => 'Mobil registrering',
        'register_type_email_title'             => 'E-postregistrering',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Har du redan ett konto?',
        // 表单
        'form_item_agreement'                   => 'Läs och godkänna',
        'form_item_agreement_message'           => 'Markera Avtalet',
        'form_item_service'                     => 'Tjänsteavtal',
        'form_item_privacy'                     => 'Sekretesspolicy',
        'form_item_username'                    => 'användarnamn',
        'form_item_username_message'            => 'Använd bokstäver, siffror och understreck för 2 till 18 tecken',
        'form_item_password'                    => 'Inloggningslösenord',
        'form_item_password_placeholder'        => 'Ange ditt inloggningslösenord',
        'form_item_password_message'            => 'Lösenordsformatet är mellan 6 och 18 tecken',
        'form_item_mobile'                      => 'telefonnummer',
        'form_item_mobile_placeholder'          => 'Ange ditt telefonnummer',
        'form_item_mobile_message'              => 'Fel i mobilnummerformat',
        'form_item_email'                       => 'E-post',
        'form_item_email_placeholder'           => 'Ange en e-postadress',
        'form_item_email_message'               => 'Fel i e-postformat',
        'form_item_account'                     => 'Inloggningskonto',
        'form_item_account_placeholder'         => 'Ange användarnamn/telefon/e-post',
        'form_item_account_message'             => 'Ange ett inloggningskonto',
        'form_item_mobile_email'                => 'Mobiltelefon/e-post',
        'form_item_mobile_email_message'        => 'Ange ett giltigt telefon-/e-postformat',
        // 个人中心
        'base_avatar_title'                     => 'Ändra avatar',
        'base_personal_title'                   => 'Ändra data',
        'base_address_title'                    => 'Min adress',
        'base_message_title'                    => 'nyheter',
        'order_nav_title'                       => 'Min order',
        'order_nav_angle_title'                 => 'Visa alla beställningar',
        'various_transaction_title'             => 'Transaktionspåminnelse',
        'various_transaction_tips'              => 'Transaktionsvarningar kan hjälpa dig att förstå orderstatus och logistik',
        'various_cart_title'                    => 'Varukorg',
        'various_cart_empty_title'              => 'Din varukorg är fortfarande tom',
        'various_cart_tips'                     => 'Att lägga de varor du vill köpa i en kundvagn gör det lättare att lösa ihop',
        'various_favor_title'                   => 'Produktsamling',
        'various_favor_empty_title'             => 'Du har inte samlat några föremål än',
        'various_favor_tips'                    => 'Favoritprodukter kommer att visa de senaste kampanjerna och prisminskningar',
        'various_browse_title'                  => 'Mina spår',
        'various_browse_empty_title'            => 'Din produktbläddringspost är tom',
        'various_browse_tips'                   => 'Skynda dig och kolla in kampanjaktiviteterna i köpcentret',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Min adress',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Mina spår',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Produktinformation',
            'goods_placeholder'     => 'Ange produktnamn/kortfattad beskrivning/SEO-information',
            'price'                 => 'Försäljningspris (RMB)',
            'original_price'        => 'Ursprungligt pris (yuan)',
            'add_time'              => 'Skapandetid',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'Produktsamling',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Produktinformation',
            'goods_placeholder'     => 'Ange produktnamn/kortfattad beskrivning/SEO-information',
            'price'                 => 'Försäljningspris (RMB)',
            'original_price'        => 'Ursprungligt pris (yuan)',
            'add_time'              => 'Skapandetid',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'Mina poäng',
        // 页面
        'base_normal_title'                     => 'Normal tillgänglighet',
        'base_normal_tips'                      => 'Punkter som kan användas normalt',
        'base_locking_title'                    => 'För närvarande låst',
        'base_locking_tips'                     => 'I allmänhet slutförs transaktionen inte och motsvarande punkter låses.',
        'base_integral_unit'                    => 'integrerad',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Typ av operation',
            'operation_integral'    => 'Operativ integrerad',
            'original_integral'     => 'Original integral',
            'new_integral'          => 'Senaste punkterna',
            'msg'                   => 'beskriva',
            'add_time_time'         => 'tid',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'personuppgifter',
        'edit_browser_seo_title'                => 'Profilredigering',
        'form_item_nickname'                    => 'smeknamn',
        'form_item_nickname_message'            => 'Smeknamn mellan 2 och 16 tecken',
        'form_item_birthday'                    => 'födelsedag',
        'form_item_birthday_message'            => 'Felaktigt födelsedagsformat',
        'form_item_province'                    => 'Provins',
        'form_item_province_message'            => 'Provins med högst 30 tecken',
        'form_item_city'                        => 'Stad',
        'form_item_city_message'                => 'Stad med högst 30 tecken',
        'form_item_county'                      => 'Distrikt/län',
        'form_item_county_message'              => 'Distrikt/län med högst 30 tecken',
        'form_item_address'                     => 'Detaljerad adress',
        'form_item_address_message'             => 'Detaljerad adress 2~30 tecken',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Mitt meddelande',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Brevtyp',
            'business_type'         => 'Typ av verksamhet',
            'title'                 => 'titel',
            'detail'                => 'detaljer',
            'is_read'               => 'tillstånd',
            'add_time_time'         => 'tid',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Frågor och svar/meddelande',
        // 表单
        'form_title'                            => 'Frågor/meddelanden',
        'form_item_name'                        => 'smeknamn',
        'form_item_name_message'                => 'Smeknamnsformatet är mellan 1 och 30 tecken',
        'form_item_tel'                         => 'Telefon',
        'form_item_tel_message'                 => 'Fyll i telefonnummer',
        'form_item_title'                       => 'titel',
        'form_item_title_message'               => 'Titelformatet är mellan 1 och 60 tecken',
        'form_item_content'                     => 'innehåll',
        'form_item_content_message'             => 'Innehållsformatet är mellan 5 och 1000 tecken',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'kontakter',
            'tel'                   => 'kontaktnummer',
            'content'               => 'innehåll',
            'reply'                 => 'Svarsinnehåll',
            'reply_time_time'       => 'Svarstid',
            'add_time_time'         => 'Skapandetid',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'säkerhetsinställning',
        'password_update_browser_seo_title'     => 'Ändring av inloggningslösenord - Säkerhetsinställningar',
        'mobile_update_browser_seo_title'       => 'Ändring av mobilnummer – säkerhetsinställningar',
        'email_update_browser_seo_title'        => 'Ändring av e-post – säkerhetsinställningar',
        'logout_browser_seo_title'              => 'Kontoinloggning - Säkerhetsinställningar',
        'original_account_check_error_tips'     => 'Verifiering av det ursprungliga kontot misslyckades',
        // 页面
        'logout_title'                          => 'Avbrytning av konto',
        'logout_confirm_title'                  => 'Bekräfta inloggning',
        'logout_confirm_tips'                   => 'Kontot kan inte återställas efter att ha avslutats. Är du säker på att fortsätta?',
        'email_title'                           => 'Verifiering av ursprunglig e-post',
        'email_new_title'                       => 'Verifiering av ny e-post',
        'mobile_title'                          => 'Verifiering av ursprungligt mobiltelefonnummer',
        'mobile_new_title'                      => 'Verifiering av nytt mobiltelefonnummer',
        'login_password_title'                  => 'Ändring av inloggningslösenord',
    ],
];
?>