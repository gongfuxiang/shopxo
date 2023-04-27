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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'Trend för ordertransaktionsbeloppet',
            'order_trading_trend_name'          => 'Orderhandlingstrend',
            'goods_hot_name'                    => 'Varmt säljande varor',
            'goods_hot_tips'                    => 'Visa bara de första 30 objekten',
            'payment_name'                      => 'Betalningsmetod',
            'order_region_name'                 => 'Geografisk fördelning i ordning',
            'order_region_tips'                 => 'Visa endast 30 databitar',
            'upgrade_check_loading_tips'        => 'Hämta det senaste innehållet, vänta',
            'upgrade_version_name'              => 'Uppdaterad version:',
            'upgrade_date_name'                 => 'Uppdateringsdatum:',
        ],
        // 页面基础
        'base_update_button_title'              => 'Uppdatera nu',
        'base_item_base_stats_title'            => 'Mall statistik',
        'base_item_base_stats_tips'             => 'Tidsfiltrering är endast giltig för summor',
        'base_item_user_title'                  => 'Totalt antal användare',
        'base_item_order_number_title'          => 'Totalt orderbelopp',
        'base_item_order_complete_number_title' => 'Total transaktionsvolym',
        'base_item_order_complete_title'        => 'Beställningsbelopp',
        'base_item_last_month_title'            => 'Förra månaden',
        'base_item_same_month_title'            => 'samma månad',
        'base_item_yesterday_title'             => 'igår',
        'base_item_today_title'                 => 'idag',
        'base_item_order_profit_title'          => 'Trend för ordertransaktionsbeloppet',
        'base_item_order_trading_title'         => 'Orderhandlingstrend',
        'base_item_order_tips'                  => 'Alla order',
        'base_item_hot_sales_goods_title'       => 'Varmt säljande varor',
        'base_item_hot_sales_goods_tips'        => 'Exkludera beställningar som har annullerats och stängts',
        'base_item_payment_type_title'          => 'Betalningsmetod',
        'base_item_map_whole_country_title'     => 'Geografisk fördelning i ordning',
        'base_item_map_whole_country_tips'      => 'Exkludera order som har annullerats och standarddimensioner (provinser)',
        'base_item_map_whole_country_province'  => 'provins',
        'base_item_map_whole_country_city'      => 'stad',
        'base_item_map_whole_country_county'    => 'Distrikt/län',
        'system_info_title'                     => 'systeminformation',
        'system_ver_title'                      => 'Programversion',
        'system_os_ver_title'                   => 'operativsystem',
        'system_php_ver_title'                  => 'PHP- version',
        'system_mysql_ver_title'                => 'MySQL-version',
        'system_server_ver_title'               => 'Information om serversidan',
        'system_host_title'                     => 'Nuvarande domännamn',
        'development_team_title'                => 'utvecklingsgruppen',
        'development_team_website_title'        => 'Företagets officiella webbplats',
        'development_team_website_value'        => 'Shanghai Zongzhige Technology Co., Ltd',
        'development_team_support_title'        => 'tekniskt stöd',
        'development_team_support_value'        => 'ShopXO Enterprise E- handelssystemleverantör',
        'development_team_ask_title'            => 'Kommunikationsfrågor',
        'development_team_ask_value'            => 'ShopXO kommunikationsfrågor',
        'development_team_agreement_title'      => 'Öppen källkodsprotokoll',
        'development_team_agreement_value'      => 'Visa avtal med öppen källkod',
        'development_team_update_log_title'     => 'Uppdatera logg',
        'development_team_update_log_value'     => 'Visa uppdateringslogg',
        'development_team_members_title'        => 'FoU-medlemmar',
        'development_team_members_value'        => [
            ['name' => 'Broder Gong', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'användare',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'Användar- ID',
            'number_code'           => 'Medlemskod',
            'system_type'           => 'Systemtyp',
            'platform'              => 'Plattform',
            'avatar'                => 'Huvudporträtt',
            'username'              => 'användarnamn',
            'nickname'              => 'smeknamn',
            'mobile'                => 'mobiltelefon',
            'email'                 => 'brevlåda',
            'gender_name'           => 'Kön',
            'status_name'           => 'tillstånd',
            'province'              => 'Provins',
            'city'                  => 'Stad',
            'county'                => 'Distrikt/län',
            'address'               => 'Detaljerad adress',
            'birthday'              => 'födelsedag',
            'integral'              => 'Tillgängliga punkter',
            'locking_integral'      => 'Låst integrerad',
            'referrer'              => 'Bjud in användare',
            'referrer_placeholder'  => 'Ange inbjudans användarnamn/smeknamn/telefon/e-post',
            'add_time'              => 'Registreringstid',
            'upd_time'              => 'Uppdateringstid',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Användaradress',
        // 详情
        'detail_user_address_idcard_name'       => 'fullständigt namn',
        'detail_user_address_idcard_number'     => 'nummer',
        'detail_user_address_idcard_pic'        => 'Foto',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Användarinformation',
            'user_placeholder'  => 'Ange användarnamn/smeknamn/telefon/e-post',
            'alias'             => 'alias',
            'name'              => 'kontakter',
            'tel'               => 'kontaktnummer',
            'province_name'     => 'Provins',
            'city_name'         => 'Stad',
            'county_name'       => 'Distrikt/län',
            'address'           => 'Detaljerad adress',
            'position'          => 'Longitud och latitud',
            'idcard_info'       => 'ID-kortsinformation',
            'is_default'        => 'Standard eller inte',
            'add_time'          => 'Skapandetid',
            'upd_time'          => 'Uppdateringstid',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Spara träder i kraft efter borttagning. Är du säker på att fortsätta?',
            'address_no_data'                   => 'Adressdata är tom',
            'address_not_exist'                 => 'Adress finns inte',
            'address_logo_message'              => 'Ladda upp en logotypbild',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Grundläggande konfiguration', 'type' => 'base'],
            ['name' => 'Webbplatsinställningar', 'type' => 'siteset'],
            ['name' => 'Platstyp', 'type' => 'sitetype'],
            ['name' => 'Användarregistrering', 'type' => 'register'],
            ['name' => 'Användarinloggning', 'type' => 'login'],
            ['name' => 'Återställning av lösenord', 'type' => 'forgetpwd'],
            ['name' => 'Kontrollkod', 'type' => 'verify'],
            ['name' => 'Beställning efter försäljning', 'type' => 'orderaftersale'],
            ['name' => 'kapsling', 'type' => 'attachment'],
            ['name' => 'cache', 'type' => 'cache'],
            ['name' => 'Förlängningar', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'startsida', 'type' => 'index'],
            ['name' => 'råvara', 'type' => 'goods'],
            ['name' => 'söka', 'type' => 'search'],
            ['name' => 'order', 'type' => 'order'],
            ['name' => 'Rabatt', 'type' => 'discount'],
            ['name' => 'utvidga', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Webbplatsstatus',
        'base_item_site_domain_title'           => 'Webbplatsdomännamn Adress',
        'base_item_site_filing_title'           => 'Registreringsinformation',
        'base_item_site_other_title'            => 'Annat',
        'base_item_session_cache_title'         => 'Inställning av sessionscache',
        'base_item_data_cache_title'            => 'Inställning av datacache',
        'base_item_redis_cache_title'           => 'Redis cacheinställningar',
        'base_item_crontab_config_title'        => 'Inställning av tidsskript',
        'base_item_quick_nav_title'             => 'Snabbnavigering',
        'base_item_user_base_title'             => 'Användarbas',
        'base_item_user_address_title'          => 'Användaradress',
        'base_item_multilingual_title'          => 'Flerspråkiga',
        'base_item_site_auto_mode_title'        => 'Automatiskt läge',
        'base_item_site_manual_mode_title'      => 'Manuellt läge',
        'base_item_default_payment_title'       => 'Standardbetalningsmetod',
        'base_item_display_type_title'          => 'Bildtyp',
        'base_item_self_extraction_title'       => 'Självgrundande',
        'base_item_fictitious_title'            => 'Virtuell försäljning',
        'choice_upload_logo_title'              => 'Välj en logotyp',
        'add_goods_title'                       => 'Produkttillägg',
        'add_self_extractio_address_title'      => 'Lägg till adress',
        'site_domain_tips_list'                 => [
            '1. Om webbplatsdomännamnet inte är angivet används aktuella webbplatsdomännamn, domännamn och adress[ '.__MY_DOMAIN__.' ]',
            '2. Om bilagan och den statiska adressen inte är inställda används den statiska domännamnsadressen för den aktuella webbplatsen[ '.__MY_PUBLIC_URL__.' ]',
            '3. Om public inte är angiven som rotkatalog på serversidan måste konfigurationen av [attachment cdn domännamn, css/js statisk fil cdn domännamn] följas av public, till exempel:'.__MY_PUBLIC_URL__.'public/',
            '4. När ett projekt körs i kommandoradsläge måste regionens adress konfigureras, annars saknas domännamnsinformation för vissa adresser i projektet.',
            '5. Ställ inte in slumpmässigt. En felaktig adress kan göra att webbplatsen blir otillgänglig (adressinställningen börjar med http). Om din egen webbplats är konfigurerad med https, börjar den med https',
        ],
        'site_cache_tips_list'                  => [
            'Standard fil caching och Redis caching PHP kräver att Redis-tillägget installeras först',
            'Se till att Redis-tjänsten är stabil (efter att ha använt cacheminnet för en session kan den instabila tjänsten orsaka att bakgrunden inte kan logga in)',
            '3. Om du stöter på Redis-tjänsteundantag kan du inte logga in på hanteringsbakgrunden och ändra filen [session.php, cache.php] i katalogen [config] i konfigurationsfilen',
        ],
        'goods_tips_list'                       => [
            '1. Standardvisningen på WEBBsidan är nivå 3, med minst nivå 1 och högst nivå 3 (om den ställs in på nivå 0 är standard nivå 3)',
            'Standarddisplayen på mobilterminalen är nivå 0 (produktlistläge), lägsta nivå 0 och högsta nivå 3 (1-3 är klassificerade visningslägen).',
            '3. Olika nivåer och front-end kategori sidstilar kan också vara olika',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Konfigurera maximalt antal produkter som visas på varje våning',
            '2. Det rekommenderas inte att ändra mängden för stor, vilket gör att det tomma området på vänster sida av PC terminalen blir för stort',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'Övergripande: Värme ->Försäljning ->Senaste fallande',
        ],
        'goods_manual_mode_max_tips_list'       => [
            'Du kan klicka på produkttiteln för att dra och sortera den och visa den i ordning',
            '2. Det rekommenderas inte att lägga till många produkter, vilket kan göra att det tomma området på vänster sida av datorn blir för stort',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. Som standard används [Användarnamn, Mobiltelefon, E-post] som den unika användaren',
            '2. Om det är aktiverat lägger du till [System ID] och gör det unikt för användaren',
        ],
        'extends_crontab_tips'                  => 'Det rekommenderas att lägga till scriptadressen i begäran om schemalagd tidsplanering för Linux. (Resultatet är SUCS: 0, FAIL: 0, följt av kolon, är antalet bearbetade databitar. SUCS lyckades, FALI misslyckades.)',
        'left_images_random_tips'               => 'Den vänstra bilden kan ladda upp upp till 3 bilder, varav en kan visas slumpmässigt varje gång',
        'background_color_tips'                 => 'Anpassningsbar bakgrundsbild, standardbakgrundsgrå',
        'site_setup_layout_tips'                => 'Dra och släpp-läget kräver att du själv anger startsidans designsida. Spara den valda konfigurationen innan du fortsätter',
        'site_setup_layout_button_name'         => 'Gå till designsidan>>',
        'site_setup_goods_category_tips'        => 'För fler golvskärmar, gå till/Produkthantering ->Produktklassificering, inställningar för primärklassificering Startsida Rekommendationer',
        'site_setup_goods_category_no_data_tips'=> 'Inga data tillgängliga, gå till/Produkthantering ->Produktklassificering, inställningar för primärklassificering Startsida för rekommendationer',
        'site_setup_order_default_payment_tips' => 'Du kan ställa in standardbetalningsmetoder som motsvarar olika plattformar. Installera först betalningsinsticksprogrammet i [Webbplatshantering ->Betalningsmetoder] för att aktivera det och göra det tillgängligt för användare',
        'site_setup_choice_payment_message'     => 'Välj {:name} standardbetalningsmetod',
        'sitetype_top_tips_list'                => [
            '1. Expressleverans och konventionella e-handelsprocesser, där användare väljer en leveransadress för att lägga en beställning för betalning -> handelsförsändelse -> bekräfta mottagandet -> orderslutförande',
            '2. Display typ, endast produkt display, konsultation kan initieras (beställningar kan inte göras)',
            '3. Välj själv upphämtningsadress när du gör en beställning, och användaren gör en beställning för betalning -> Bekräfta leverans -> Order slutförd',
            '4. Virtuell försäljning, regelbundna e-handelsprocesser, användarorder för betalning -> automatisk leverans -> bekräftelse av upphämtning -> orderslutförande',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'Rekommenderad 300 * 300px',
        'form_take_address_alias'               => 'alias',
        'form_take_address_alias_message'       => 'Aliasformat upp till 16 tecken',
        'form_take_address_name'                => 'kontakter',
        'form_take_address_name_message'        => 'Kontaktformatet är mellan 2 och 16 tecken',
        'form_take_address_tel'                 => 'kontaktnummer',
        'form_take_address_tel_message'         => 'Fyll i kontaktnumret',
        'form_take_address_address'             => 'Detaljerad adress',
        'form_take_address_address_message'     => 'Det detaljerade adressformatet är mellan 1 och 80 tecken',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Inloggning i bakgrunden',
        'admin_login_info_bg_images_list_tips'  => [
            'Bakgrundsbilden finns i katalogen [public/static/admin/default/images/login]',
            'Namngivningsregler för bakgrundsbilder (1-50), till exempel 1.jpg',
        ],
        'map_type_tips'                         => 'På grund av de olika kartstandarderna för varje företag, vänligen byt inte kartor slumpmässigt, vilket kan leda till felaktig kartkoordinatmärkning.',
        'apply_map_baidu_name'                  => 'Vänligen ansök till Baidu Map Open Platform',
        'apply_map_amap_name'                   => 'Vänligen anmäl dig till Gaode Map Open Platform',
        'apply_map_tencent_name'                => 'Vänligen ansök på Tencent Map Open Platform',
        'apply_map_tianditu_name'               => 'Ansök på Tiantu Open Platform',
        'cookie_domain_list_tips'               => [
            '"Om standardvärdet är tomt är det endast giltigt för domännamnet som används för närvarande."',
            'Om du behöver ett sekundärt domännamn för att dela cookies fyller du i toppdomännamnet, till exempel baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'varumärke',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'namn',
            'describe'             => 'beskriva',
            'logo'                 => 'LOGO',
            'url'                  => 'Officiell webbplats adress',
            'brand_category_text'  => 'Varumärkesklassificering',
            'is_enable'            => 'Aktiverad eller ej',
            'sort'                 => 'sortera',
            'add_time'             => 'Skapandetid',
            'upd_time'             => 'Uppdateringstid',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Varumärkesklassificering',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'artikel',
        'detail_content_title'                  => 'Detaljer',
        'detail_images_title'                   => 'Detaljerad bild',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'titel',
            'jump_url'               => 'Hoppa URL- adress',
            'article_category_name'  => 'klassificering',
            'is_enable'              => 'Aktiverad eller ej',
            'is_home_recommended'    => 'Rekommendation på startsidan',
            'images_count'           => 'Antal bilder',
            'access_count'           => 'Antal besök',
            'add_time'               => 'Skapandetid',
            'upd_time'               => 'Uppdateringstid',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Artikelklassificering',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Anpassad sida',
        'detail_content_title'                  => 'Detaljer',
        'detail_images_title'                   => 'Detaljerad bild',
        'save_view_tips'                        => 'Spara innan effekten förhandsgranskas',
        // 动态表格
        'form_table'                            => [
            'info'            => 'titel',
            'is_enable'       => 'Aktiverad eller ej',
            'is_header'       => 'Huvud eller ej',
            'is_footer'       => 'svans',
            'is_full_screen'  => 'Om skärmen är full',
            'images_count'    => 'Antal bilder',
            'access_count'    => 'Antal besök',
            'add_time'        => 'Skapandetid',
            'upd_time'        => 'Uppdateringstid',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Ladda ner fler designmallar',
        'upload_list_tips'                      => [
            '1. Välj det nedladdade zip-paketet för siddesign',
            '2. Import kommer automatiskt att lägga till en ny bit data',
        ],
        'operate_sync_tips'                     => 'Datasynkronisering till visualisering av startsidan och efterföljande ändringar av data påverkas inte, men ta inte bort relaterade bilagor',
        // 动态表格
        'form_table'                            => [
            'id'                => 'Data ID',
            'info'              => 'Grundläggande information',
            'info_placeholder'  => 'Ange ett namn',
            'access_count'      => 'Antal besök',
            'is_enable'         => 'Aktiverad eller ej',
            'is_header'         => 'Inklusive huvud',
            'is_footer'         => 'Inklusive svans',
            'seo_title'         => 'SEO- rubrik',
            'seo_keywords'      => 'SEO nyckelord',
            'seo_desc'          => 'SEO-beskrivning',
            'add_time'          => 'Skapandetid',
            'upd_time'          => 'Uppdateringstid',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Frågor och svar',
        'user_info_title'                       => 'Användarinformation',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Användarinformation',
            'user_placeholder'  => 'Ange användarnamn/smeknamn/telefon/e-post',
            'name'              => 'kontakter',
            'tel'               => 'kontaktnummer',
            'content'           => 'innehåll',
            'reply'             => 'Svarsinnehåll',
            'is_show'           => 'Om du ska visa',
            'is_reply'          => 'Svara eller inte',
            'reply_time_time'   => 'Svarstid',
            'access_count'      => 'Antal besök',
            'add_time_time'     => 'Skapandetid',
            'upd_time_time'     => 'Uppdateringstid',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Lager',
        'top_tips_list'                         => [
            '"Ju högre viktvärde, desto högre vikt. Lager dras av i viktordning."',
            '2. Lagret kan endast raderas mjukt, kommer inte att finnas tillgängligt efter radering och endast uppgifterna i databasen kan lagras. Du kan själv radera tillhörande produktdata',
            '3. Inaktivering och radering av lager och tillhörande varulager kommer omedelbart att släppas',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Namn/alias',
            'level'          => 'vikt',
            'is_enable'      => 'Aktiverad eller ej',
            'contacts_name'  => 'kontakter',
            'contacts_tel'   => 'kontaktnummer',
            'province_name'  => 'Provins',
            'city_name'      => 'Stad',
            'county_name'    => 'Distrikt/län',
            'address'        => 'Detaljerad adress',
            'position'       => 'Longitud och latitud',
            'add_time'       => 'Skapandetid',
            'upd_time'       => 'Uppdateringstid',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Välj ett lager',
        ],
        // 基础
        'add_goods_title'                       => 'Produkttillägg',
        'no_spec_data_tips'                     => 'Inga specifikationsuppgifter',
        'batch_setup_inventory_placeholder'     => 'Satsningsvärden',
        'base_spec_inventory_title'             => 'Specifikationsförteckning',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Grundläggande information',
            'goods_placeholder'  => 'Ange produktnamn/modell',
            'warehouse_name'     => 'Lager',
            'is_enable'          => 'Aktiverad eller ej',
            'inventory'          => 'Total inventering',
            'spec_inventory'     => 'Specifikationsförteckning',
            'add_time'           => 'Skapandetid',
            'upd_time'           => 'Uppdateringstid',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'Administratörsinformationen finns inte',
        // 列表
        'top_tips_list'                         => [
            '1. Administratörskontot har alla behörigheter som standard och kan inte ändras.',
            '2. Administratörskontot kan inte ändras, men kan ändras i datatabellen( '.MyConfig('database.connections.mysql.prefix').'admin ) fält username',
        ],
        'base_nav_title'                        => 'administratörer',
        // 登录
        'login_type_username_title'             => 'Kontolösenord',
        'login_type_mobile_title'               => 'Mobil verifieringskod',
        'login_type_email_title'                => 'Verifieringskod för e-post',
        'login_close_tips'                      => 'Tillfälligt stängd inloggning',
        // 忘记密码
        'form_forget_password_name'             => 'Glömt lösenordet?',
        'form_forget_password_tips'             => 'Kontakta din administratör för att återställa ditt lösenord',
        // 动态表格
        'form_table'                            => [
            'username'              => 'administratörer',
            'status'                => 'tillstånd',
            'gender'                => 'Kön',
            'mobile'                => 'mobiltelefon',
            'email'                 => 'brevlåda',
            'role_name'             => 'Rollgrupper',
            'login_total'           => 'Antal inloggningar',
            'login_time'            => 'Senaste inloggningstid',
            'add_time'              => 'Skapandetid',
            'upd_time'              => 'Uppdateringstid',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Användarregistreringsavtal', 'type' => 'register'],
            ['name' => 'Sekretesspolicy för användare', 'type' => 'privacy'],
            ['name' => 'Avtal om annullering av konto', 'type' => 'logout']
        ],
        'top_tips'          => 'Lägg till parameter är till åtkomstprotokollets adress_ Innehåll=1 visar endast protokollinnehåll',
        'view_detail_name'                      => 'Visa detaljer',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Grundläggande konfiguration', 'type' => 'index'],
            ['name' => 'APP/miniprogram', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Aktuellt tema', 'type' => 'index'],
            ['name' => 'Temainstallation', 'type' => 'upload'],
            ['name' => 'Ladda ner källkodspaketet', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Fler temahämtningar',
        'nav_theme_download_name'               => 'Visa handledningen om paketering av miniprogram',
        'nav_theme_download_tips'               => 'Mobiltemat är utvecklat med uniapp (stöd för multi terminal applet + H5), och appen är också i nödanpassning.',
        'form_alipay_extend_title'              => 'Konfiguration av kundservice',
        'form_alipay_extend_tips'               => 'PS: Om den är aktiverad i [APP/applet] (för att aktivera onlinekundservice) måste följande konfiguration fyllas i med [Företagskod] och [Chattfönsterkod]',
        'form_theme_upload_tips'                => 'Ladda upp ett komprimerat installationspaket med zip',
        'list_no_data_tips'                     => 'Inga relaterade temapaket',
        'list_author_title'                     => 'författare',
        'list_version_title'                    => 'Tillämplig version',
        'package_generate_tips'                 => 'Generationstiden är relativt lång, stäng inte webbläsarfönstret!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Paketnamn',
            'size'  => 'storlek',
            'url'   => 'Hämtningsadress',
            'time'  => 'Skapandetid',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'SMS- inställningar', 'type' => 'index'],
            ['name' => 'Brevmall', 'type' => 'message'],
        ],
        'top_tips'                              => 'Alibaba Cloud SMS- hanteringsadress',
        'top_to_aliyun_tips'                    => 'Klicka för att köpa SMS från AliCloud',
        'base_item_admin_title'                 => 'backstage',
        'base_item_index_title'                 => 'främre änden',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Inställningar av brevlåda', 'type' => 'index'],
            ['name' => 'Brevmall', 'type' => 'message'],
        ],
        'top_tips'                              => 'På grund av vissa skillnader och konfigurationer mellan olika brevlådeplattformar ska den specifika konfigurationsguiden för brevlådeplattformen råda',
        // 基础
        'test_title'                            => 'test',
        'test_content'                          => 'Inställning av e-post - Skicka testinnehåll',
        'base_item_admin_title'                 => 'backstage',
        'base_item_index_title'                 => 'främre änden',
        // 表单
        'form_item_test'                        => 'Testa mottagen e-postadress',
        'form_item_test_tips'                   => 'Spara konfigurationen innan du testar',
        'form_item_test_button_title'           => 'test',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Konfigurera motsvarande pseudo statiska regler enligt olika servermiljöer [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'råvara',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Grundläggande information', 'type'=>'base'],
            'specifications'  => ['name' => 'Produktspecifikationer', 'type'=>'specifications'],
            'parameters'      => ['name' => 'Produktparametrar', 'type'=>'parameters'],
            'photo'           => ['name' => 'Produktalbum', 'type'=>'photo'],
            'video'           => ['name' => 'Produktvideo', 'type'=>'video'],
            'app'             => ['name' => 'Enhetsstatus', 'type'=>'app'],
            'web'             => ['name' => 'Datorinformation', 'type'=>'web'],
            'fictitious'      => ['name' => 'Virtuell information', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Utökade data', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO-information', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Produkt-ID',
            'info'                    => 'Produktinformation',
            'category_text'           => 'Varuklassificering',
            'brand_name'              => 'varumärke',
            'price'                   => 'Försäljningspris (RMB)',
            'original_price'          => 'Ursprungligt pris (yuan)',
            'inventory'               => 'Total inventering',
            'is_shelves'              => 'Övre och nedre hyllor',
            'is_deduction_inventory'  => 'Lageravdrag',
            'site_type'               => 'Produkttyp',
            'model'                   => 'Produktmodell',
            'place_origin_name'       => 'Produktionsort',
            'give_integral'           => 'Inköpsbonuspoäng Procent',
            'buy_min_number'          => 'Minsta inköpskvantitet per gång',
            'buy_max_number'          => 'Högsta inköpskvantitet',
            'sales_count'             => 'försäljningsvolym',
            'access_count'            => 'Antal besök',
            'add_time'                => 'Skapandetid',
            'upd_time'                => 'Uppdateringstid',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Varuklassificering',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Produktomdömen',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Användarinformation',
            'user_placeholder'   => 'Ange användarnamn/smeknamn/telefon/e-post',
            'goods'              => 'Grundläggande information',
            'goods_placeholder'  => 'Ange produktnamn/modell',
            'business_type'      => 'Typ av verksamhet',
            'content'            => 'Kommentarinnehåll',
            'images'             => 'Kommentar bild',
            'rating'             => 'poäng',
            'reply'              => 'Svarsinnehåll',
            'is_show'            => 'Om du ska visa',
            'is_anonymous'       => 'Anonym eller inte',
            'is_reply'           => 'Svara eller inte',
            'reply_time_time'    => 'Svarstid',
            'add_time_time'      => 'Skapandetid',
            'upd_time_time'      => 'Uppdateringstid',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Produktparametrar',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Varuklassificering',
            'name'          => 'namn',
            'is_enable'     => 'Aktiverad eller ej',
            'config_count'  => 'Antal parametrar',
            'add_time'      => 'Skapandetid',
            'upd_time'      => 'Uppdateringstid',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Varuklassificering',
            'name'         => 'namn',
            'is_enable'    => 'Aktiverad eller ej',
            'content'      => 'Specifikationsvärde',
            'add_time'     => 'Skapandetid',
            'upd_time'     => 'Uppdateringstid',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Användarinformation',
            'user_placeholder'   => 'Ange användarnamn/smeknamn/telefon/e-post',
            'goods'              => 'Produktinformation',
            'goods_placeholder'  => 'Ange produktnamn/kortfattad beskrivning/SEO-information',
            'price'              => 'Försäljningspris (RMB)',
            'original_price'     => 'Ursprungligt pris (yuan)',
            'add_time'           => 'Skapandetid',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Användarinformation',
            'user_placeholder'   => 'Ange användarnamn/smeknamn/telefon/e-post',
            'goods'              => 'Produktinformation',
            'goods_placeholder'  => 'Ange produktnamn/kortfattad beskrivning/SEO-information',
            'price'              => 'Försäljningspris (RMB)',
            'original_price'     => 'Ursprungligt pris (yuan)',
            'add_time'           => 'Skapandetid',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Användarinformation',
            'user_placeholder'   => 'Ange användarnamn/smeknamn/telefon/e-post',
            'goods'              => 'Produktinformation',
            'goods_placeholder'  => 'Ange produktnamn/kortfattad beskrivning/SEO-information',
            'price'              => 'Försäljningspris (RMB)',
            'original_price'     => 'Ursprungligt pris (yuan)',
            'add_time'           => 'Skapandetid',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Vänliga länkar',
        // 动态表格
        'form_table'                            => [
            'info'                => 'namn',
            'url'                 => 'Adressadress',
            'describe'            => 'beskriva',
            'is_enable'           => 'Aktiverad eller ej',
            'is_new_window_open'  => 'Om ett nytt fönster öppnas',
            'sort'                => 'sortera',
            'add_time'            => 'Skapandetid',
            'upd_time'            => 'Uppdateringstid',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Intermediate navigation', 'type' => 'header'],
            ['name' => 'Nedre navigering', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'anpassad',
            'article'           => 'artikel',
            'customview'        => 'Anpassad sida',
            'goods_category'    => 'Varuklassificering',
            'design'            => 'Siddesign',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Navigationsnamn',
            'data_type'           => 'Navigationsdatatyp',
            'is_show'             => 'Om du ska visa',
            'is_new_window_open'  => 'Nytt fönster Öppna',
            'sort'                => 'sortera',
            'add_time'            => 'Skapandetid',
            'upd_time'            => 'Uppdateringstid',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Order ID är felaktigt',
            'express_choice_tips'               => 'Välj leveransmetod',
            'payment_choice_tips'               => 'Välj betalningsmetod',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Sändningsverksamhet',
        'form_payment_title'                    => 'Betalningsåtgärder',
        'form_item_take'                        => 'Upphämtningskod',
        'form_item_take_message'                => 'Fyll i den fyrsiffriga upphämtningskoden',
        'form_item_express_number'              => 'kurirens nummer',
        'form_item_express_number_message'      => 'Fyll i expressräkningens nummer',
        // 地址
        'detail_user_address_title'             => 'Leveransadress',
        'detail_user_address_name'              => 'adressat',
        'detail_user_address_tel'               => 'Mottagare telefon',
        'detail_user_address_value'             => 'Detaljerad adress',
        'detail_user_address_idcard'            => 'ID-kortsinformation',
        'detail_user_address_idcard_name'       => 'fullständigt namn',
        'detail_user_address_idcard_number'     => 'nummer',
        'detail_user_address_idcard_pic'        => 'Foto',
        'detail_take_address_title'             => 'Hämtningsadress',
        'detail_take_address_contact'           => 'Kontaktinformation',
        'detail_take_address_value'             => 'Detaljerad adress',
        'detail_fictitious_title'               => 'Nyckelinformation',
        // 订单售后
        'detail_aftersale_status'               => 'tillstånd',
        'detail_aftersale_type'                 => 'typ',
        'detail_aftersale_price'                => 'belopp',
        'detail_aftersale_number'               => 'kvantitet',
        'detail_aftersale_reason'               => 'skäl',
        // 商品
        'detail_goods_title'                    => 'Beställningspost',
        'detail_payment_amount_less_tips'       => 'Observera att beställningsbeloppet är mindre än det totala beloppet',
        'detail_no_payment_tips'                => 'Observera att beställningen inte har betalats ännu',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Grundläggande information',
            'goods_placeholder'   => 'Ange beställnings-ID/ordernummer/produktnamn/modell',
            'user'                => 'Användarinformation',
            'user_placeholder'    => 'Ange användarnamn/smeknamn/telefon/e-post',
            'status'              => 'Beställningsstatus',
            'pay_status'          => 'Betalningsstatus',
            'total_price'         => 'Totalt pris (yuan)',
            'pay_price'           => 'Betalningsbelopp (yuan)',
            'price'               => 'Enhetspris (yuan)',
            'warehouse_name'      => 'Fraktlager',
            'order_model'         => 'Ordningsläge',
            'client_type'         => 'Källa',
            'address'             => 'Adressinformation',
            'take'                => 'Upphämtningsinformation',
            'refund_price'        => 'Återbetalningsbelopp (yuan)',
            'returned_quantity'   => 'Returkvantitet',
            'buy_number_count'    => 'Totalt inköp',
            'increase_price'      => 'Öka belopp (yuan)',
            'preferential_price'  => 'Rabattbelopp (yuan)',
            'payment_name'        => 'Betalningsmetod',
            'user_note'           => 'Användarkommentarer',
            'extension'           => 'Utökad information',
            'express_name'        => 'Courier Services Company',
            'express_number'      => 'kurirens nummer',
            'aftersale'           => 'Senaste kundservice',
            'is_comments'         => 'Om användaren kommenterar',
            'confirm_time'        => 'Bekräftelsetid',
            'pay_time'            => 'Betalningstid',
            'delivery_time'       => 'Leveranstid',
            'collect_time'        => 'Slutförandetid',
            'cancel_time'         => 'Avbryt tid',
            'close_time'          => 'Stängningstid',
            'add_time'            => 'Skapandetid',
            'upd_time'            => 'Uppdateringstid',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'Revisionsåtgärder',
        'form_refuse_title'                     => 'Avvisa åtgärd',
        'form_user_info_title'                  => 'Användarinformation',
        'form_apply_info_title'                 => 'Ansökningsinformation',
        'forn_apply_info_type'                  => 'typ',
        'forn_apply_info_price'                 => 'belopp',
        'forn_apply_info_number'                => 'kvantitet',
        'forn_apply_info_reason'                => 'skäl',
        'forn_apply_info_msg'                   => 'förklara',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Grundläggande information',
            'goods_placeholder'  => 'Ange ordernummer/produktnamn/modell',
            'user'               => 'Användarinformation',
            'user_placeholder'   => 'Ange användarnamn/smeknamn/telefon/e-post',
            'status'             => 'tillstånd',
            'type'               => 'Programtyp',
            'reason'             => 'skäl',
            'price'              => 'Återbetalningsbelopp (yuan)',
            'number'             => 'Returkvantitet',
            'msg'                => 'Instruktioner för återbetalning',
            'refundment'         => 'Typ av bidrag',
            'voucher'            => 'kupong',
            'express_name'       => 'Courier Services Company',
            'express_number'     => 'kurirens nummer',
            'refuse_reason'      => 'Skäl till avslag',
            'apply_time'         => 'Tillämpningstid',
            'confirm_time'       => 'Bekräftelsetid',
            'delivery_time'      => 'Returneringstid',
            'audit_time'         => 'Revisionstid',
            'add_time'           => 'Skapandetid',
            'upd_time'           => 'Uppdateringstid',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'Betalningsmetod',
        'nav_store_payment_name'                => 'Ladda ner fler betalningsmetoder',
        'upload_top_list_tips'                  => [
            [
                'name'  => '"Klassnamnet måste överensstämma med filnamnet (remove. php). Om Alipay.php används används Alipay."',
            ],
            [
                'name'  => '2. Metoder som en klass måste definiera',
                'item'  => [
                    '2.1 Inställningsmetod',
                    '2.2 Betalningsmetod',
                    '2.3 Metod för återkallelse av svar',
                    '2.4 Meddela asynkron återkallsmetod (valfritt, anropa svarsmetoden om den inte definieras)',
                    '2.5. Återbetalningsmetod (valfri, om inte definierad, ursprunglig återbetalning av rutten kan inte initieras)',
                ],
            ],
            [
                'name'  => '3. Anpassningsbar utmatningsinnehållsmetod',
                'item'  => [
                    '3.1 Lyckad returbetalning (frivillig)',
                    '3.2 FelÅterbetalningsmisslyckande (valfritt)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: Om ovanstående villkor inte uppfylls kan insticksprogrammet inte visas. Ladda upp insticksprogrammet i ett komprimerat paket. zip och stöd för flera betalningsinsticksprogram i en komprimering',
        // 动态表格
        'form_table'                            => [
            'name'            => 'namn',
            'logo'            => 'LOGO',
            'version'         => 'utgåva',
            'apply_version'   => 'Tillämplig version',
            'apply_terminal'  => 'Tillämpliga terminaler',
            'author'          => 'författare',
            'desc'            => 'beskriva',
            'enable'          => 'Aktiverad eller ej',
            'open_user'       => 'Öppen för användare',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'expressComment',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Aktuellt tema', 'type' => 'index'],
            ['name' => 'Temainstallation', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'Fler temahämtningar',
        'list_author_title'                     => 'författare',
        'list_version_title'                    => 'Tillämplig version',
        'form_theme_upload_tips'                => 'Ladda upp ett zip komprimerat temainstallationspaket',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Navigering i mobilt användarcenter',
        // 动态表格
        'form_table'                            => [
            'name'           => 'namn',
            'platform'       => 'Plattform',
            'images_url'     => 'Navigeringsikon',
            'event_type'     => 'Händelsetyp',
            'event_value'    => 'Händelsevärde',
            'desc'           => 'beskriva',
            'is_enable'      => 'Aktiverad eller ej',
            'is_need_login'  => 'Behöver du logga in',
            'sort'           => 'sortera',
            'add_time'       => 'Skapandetid',
            'upd_time'       => 'Uppdateringstid',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Navigation i mobilhem',
        // 动态表格
        'form_table'                            => [
            'name'           => 'namn',
            'platform'       => 'Plattform',
            'images'         => 'Navigeringsikon',
            'event_type'     => 'Händelsetyp',
            'event_value'    => 'Händelsevärde',
            'is_enable'      => 'Aktiverad eller ej',
            'is_need_login'  => 'Behöver du logga in',
            'sort'           => 'sortera',
            'add_time'       => 'Skapandetid',
            'upd_time'       => 'Uppdateringstid',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Logg över betalningsbegäran',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Användarinformation',
            'user_placeholder'  => 'Ange användarnamn/smeknamn/telefon/e-post',
            'log_no'            => 'Betalningsorder nr',
            'payment'           => 'Betalningsmetod',
            'status'            => 'tillstånd',
            'total_price'       => 'Företagsorderbelopp (yuan)',
            'pay_price'         => 'Betalningsbelopp (yuan)',
            'business_type'     => 'Typ av verksamhet',
            'business_list'     => 'Företagsnummer/dok nr',
            'trade_no'          => 'Transaktionsnummer för betalningsplattformen',
            'buyer_user'        => 'Användarkonto för betalningsplattformen',
            'subject'           => 'Ordningsnamn',
            'pay_time'          => 'Betalningstid',
            'close_time'        => 'Stängningstid',
            'add_time'          => 'Skapandetid',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Logg över betalningsbegäran',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Typ av verksamhet',
            'request_params'   => 'Parametrar för begäran',
            'response_data'    => 'Svarsdata',
            'business_handle'  => 'Resultat av företagsbehandling',
            'request_url'      => 'Begär webbadress',
            'server_port'      => 'Portnummer',
            'server_ip'        => 'Server IP',
            'client_ip'        => 'Klient IP',
            'os'               => 'operativsystem',
            'browser'          => 'webbläsare',
            'method'           => 'Typ av begäran',
            'scheme'           => 'Http-typ',
            'version'          => 'Http version',
            'client'           => 'Klientuppgifter',
            'add_time'         => 'Skapandetid',
            'upd_time'         => 'Uppdateringstid',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Användarinformation',
            'user_placeholder'  => 'Ange användarnamn/smeknamn/telefon/e-post',
            'payment'           => 'Betalningsmetod',
            'business_type'     => 'Typ av verksamhet',
            'business_id'       => 'Företagsorder ID',
            'trade_no'          => 'Transaktionsnummer för betalningsplattformen',
            'buyer_user'        => 'Användarkonto för betalningsplattformen',
            'refundment_text'   => 'Återbetalningsmetod',
            'refund_price'      => 'Återbetalningsbelopp',
            'pay_price'         => 'Betalningsbelopp för beställningen',
            'msg'               => 'beskriva',
            'add_time_time'     => 'Återbetalningstid',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Återgå till programhantering>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Klicka på kryssrutan först för att aktivera',
            'save_no_data_tips'                 => 'Inga insticksprogram att spara',
        ],
        // 基础导航
        'base_nav_title'                        => 'ansökan',
        'base_nav_list'                         => [
            ['name' => 'Programhantering', 'type' => 'index'],
            ['name' => 'Ladda upp app', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'Fler nedladdningar av insticksprogram',
        // 基础页面
        'base_search_input_placeholder'         => 'Ange namn/beskrivning',
        'base_top_tips_one'                     => 'Listsorteringsmetod [Anpassad sortering -> tidigaste installation]',
        'base_top_tips_two'                     => 'Klicka och dra ikonknappar för att justera insticksprogrammets anrop och visningsordning',
        'base_open_sort_title'                  => 'Aktivera sortering',
        'data_list_author_title'                => 'författare',
        'data_list_author_url_title'            => 'Hemsida',
        'data_list_version_title'               => 'utgåva',
        'uninstall_confirm_tips'                => 'Avinstallation kan resultera i förlust av grundläggande konfigurationsdata för plug-in som inte kan återställas. Är du säker på att fortsätta?',
        'not_install_divide_title'              => 'Följande insticksprogram är inte installerade',
        'delete_plugins_text'                   => '1. Ta bara bort appar',
        'delete_plugins_text_tips'              => '(Radera endast programkod och spara ansökningsdata)',
        'delete_plugins_data_text'              => '2. Radera ansökan och radera uppgifterna',
        'delete_plugins_data_text_tips'         => '(Ansökningskod och ansökningsdata kommer att raderas)',
        'delete_plugins_ps_tips'                => 'PS: Ingen av följande operationer kan återställas, var försiktig!',
        'delete_plugins_button_name'            => 'Ta bara bort appar',
        'delete_plugins_data_button_name'       => 'Ta bort appar och data',
        'cancel_delete_plugins_button_name'     => 'Tänk om igen',
        'more_plugins_store_to_text'            => 'Gå till App Store för att välja fler plugins för att berika webbplatsen>>',
        'no_data_store_to_text'                 => 'Gå till App Store för att välja plug-in rika webbplatser>>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Tillbaka till bakgrunden',
        'get_loading_tips'                      => 'Få',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'roll',
        'admin_not_modify_tips'                 => 'Superadministratören har alla behörigheter som standard och kan inte ändras.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Rollnamn',
            'is_enable'  => 'Aktiverad eller ej',
            'add_time'   => 'Skapandetid',
            'upd_time'   => 'Uppdateringstid',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'jurisdiktion',
        'top_tips_list'                         => [
            '1. Icke professionell teknisk personal bör inte använda uppgifterna på denna sida. Felaktig användning kan orsaka förvirring i behörighetsmenyn.',
            '2. Behörighetsmenyer är indelade i två typer: [Använd och Åtgärd]. Användarmenyn visas i allmänhet på och Åtgärdsmenyn måste vara dold.',
            '3. Om behörighetsmenyn är oreglerad kan du skriva över dataåterställningen i datatabellen [ '.MyConfig('database.connections.mysql.prefix').'power ] igen.',
            '4. [Superadministratör, administratörskonto] har alla behörigheter som standard och kan inte ändras.',
        ],
        'content_top_tips_list'                 => [
            '"För att fylla i [Controller Name and Method Name] är det nödvändigt att skapa motsvarande definitioner för controller och metod."',
            '2. Styrelsens filplats [app/admin/controller]. Den här åtgärden används endast av utvecklare',
            '"Antingen kontrollnamnet/metodnamnet eller den användardefinierade webbadressen måste fyllas i."',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Snabbnavigering',
        // 动态表格
        'form_table'                            => [
            'name'         => 'namn',
            'platform'     => 'Plattform',
            'images'       => 'Navigeringsikon',
            'event_type'   => 'Händelsetyp',
            'event_value'  => 'Händelsevärde',
            'is_enable'    => 'Aktiverad eller ej',
            'sort'         => 'sortera',
            'add_time'     => 'Skapandetid',
            'upd_time'     => 'Uppdateringstid',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'region',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Filterpris',
        'top_tips_list'                         => [
            'Lägsta pris 0 - högsta pris 100 är mindre än 100',
            'Minsta pris 1000 - maximalt pris 0 är större än 1000',
            'Minimipriset på 100 - det maximala priset på 500 är större än eller lika med 100 och mindre än 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Rotering',
        // 动态表格
        'form_table'                            => [
            'name'         => 'namn',
            'platform'     => 'Plattform',
            'images'       => 'bild',
            'event_type'   => 'Händelsetyp',
            'event_value'  => 'Händelsevärde',
            'is_enable'    => 'Aktiverad eller ej',
            'sort'         => 'sortera',
            'add_time'     => 'Skapandetid',
            'upd_time'     => 'Uppdateringstid',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Användarinformation',
            'user_placeholder'    => 'Ange användarnamn/smeknamn/telefon/e-post',
            'type'                => 'Typ av operation',
            'operation_integral'  => 'Operativ integrerad',
            'original_integral'   => 'Original integral',
            'new_integral'        => 'Senaste punkterna',
            'msg'                 => 'Driftskälet',
            'operation_id'        => 'Operatörens ID',
            'add_time_time'       => 'Driftstid',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Användarinformation',
            'user_placeholder'          => 'Ange användarnamn/smeknamn/telefon/e-post',
            'type'                      => 'Brevtyp',
            'business_type'             => 'Typ av verksamhet',
            'title'                     => 'titel',
            'detail'                    => 'detaljer',
            'is_read'                   => 'Läst eller ej',
            'user_is_delete_time_text'  => 'Om användaren ska tas bort',
            'add_time_time'             => 'Skicka tid',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: Icke-utvecklare bör inte köra några SQL-satser efter behag, eftersom operationen kan leda till att hela systemdatabasen tas bort.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'En lista över utmärkta ShopXO-applikationer är en samling av de mest erfarna, tekniskt kapabla och betrodda ShopXO-utvecklarna, som ger en omfattande eskort för din plug-in, stil och mallanpassning.',
        'to_store_name'                         => 'Gå till App Store för att välja plugins>>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Systemet för bakgrundshantering',
        'remove_cache_title'                    => 'Rensa cache',
        'user_status_title'                     => 'Användarstatus',
        'user_status_message'                   => 'Välj användarstatus',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Information om konfiguration av produktparametrar',
        'form_goods_params_copy_no_tips'        => 'Klistra in konfigurationsinformationen först',
        'form_goods_params_copy_error_tips'     => 'Konfigurationsformatfel',
        'form_goods_params_type_message'        => 'Välj produktparametervisningstyp',
        'form_goods_params_params_name'         => 'Parameternamn',
        'form_goods_params_params_message'      => 'Fyll i parameternamnet',
        'form_goods_params_value_name'          => 'Parametervärde',
        'form_goods_params_value_message'       => 'Fyll i parametervärdet',
        'form_goods_params_move_type_tips'      => 'Felaktig konfiguration av operationstyp',
        'form_goods_params_move_top_tips'       => 'Nått toppen',
        'form_goods_params_move_bottom_tips'    => 'Nått botten',
        'form_goods_params_thead_type_title'    => 'Visa räckvidd',
        'form_goods_params_thead_name_title'    => 'Parameternamn',
        'form_goods_params_thead_value_title'   => 'Parametervärde',
        'form_goods_params_row_add_title'       => 'Lägg till en rad',
        'form_goods_params_list_tips'           => [
            '1. Alla (visas under grundläggande information och detaljerade parametrar för produkten)',
            '2. Detaljer (visas endast under produktdetaljparametern)',
            '3. Grundämnen (visas endast under grundläggande varuinformation)',
            'Genvägsåtgärden rensar originaldata och laddar om sidan för att återställa originaldata (gäller endast efter att produkten sparats)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Systeminställningar',
            'item'  => [
                'config_index'                 => 'systemkonfiguration',
                'config_store'                 => 'Lagringsinformation',
                'config_save'                  => 'Inställning Spara',
                'index_storeaccountsbind'      => 'Lagringskontobindning',
                'index_inspectupgrade'         => 'Kontroll av systemuppdatering',
                'index_inspectupgradeconfirm'  => 'Bekräftelse av systemuppdatering',
                'index_stats'                  => 'Statistik över startsidan',
                'index_income'                 => 'Hemsida statistik (inkomststatistik)',
            ]
        ],
        'site_index' => [
            'name'  => 'Webbplatsinställning',
            'item'  => [
                'site_index'                  => 'Webbplatsinställningar',
                'site_save'                   => 'Redigera webbplatsinställningar',
                'site_goodssearch'            => 'Webbplatsinställningar Produktsökning',
                'layout_layoutindexhomesave'  => 'Hantering av hemsidalayouter',
                'sms_index'                   => 'SMS- inställningar',
                'sms_save'                    => 'Redigera SMS-inställningar',
                'email_index'                 => 'Inställningar av brevlåda',
                'email_save'                  => 'Inställningar av brevlåda/redigering',
                'email_emailtest'             => 'Test av postleverans',
                'seo_index'                   => 'SEO-inställningar',
                'seo_save'                    => 'SEO-inställningar Redigera',
                'agreement_index'             => 'Protokollhantering',
                'agreement_save'              => 'Redigera protokollinställningar',
            ]
        ],
        'power_index' => [
            'name'  => 'Tillståndskontroll',
            'item'  => [
                'admin_index'        => 'chefer',
                'admin_saveinfo'     => 'Administratör Lägg till/redigera sida',
                'admin_save'         => 'Administratör Lägg till/redigera',
                'admin_delete'       => 'Administratör Ta bort',
                'admin_detail'       => 'Administratörsuppgifter',
                'role_index'         => 'Rollhantering',
                'role_saveinfo'      => 'Rollgrupp Lägg till/redigera sida',
                'role_save'          => 'Lägg till/redigera rollgrupp',
                'role_delete'        => 'Radering av roller',
                'role_statusupdate'  => 'Uppdatering av rollstatus',
                'role_detail'        => 'Rollinformation',
                'power_index'        => 'Tilldelning av tillstånd',
                'power_save'         => 'Behörighet Lägg till/redigera',
                'power_delete'       => 'Radering av behörigheter',
            ]
        ],
        'user_index' => [
            'name'  => 'användarhantering',
            'item'  => [
                'user_index'            => 'Användarlista',
                'user_saveinfo'         => 'AnvändarRedigera/Lägg till sida',
                'user_save'             => 'Användare Lägg till/redigera',
                'user_delete'           => 'Ta bort användare',
                'user_detail'           => 'Användarinformation',
                'useraddress_index'     => 'Användaradress',
                'useraddress_saveinfo'  => 'Användaradress Redigera sida',
                'useraddress_save'      => 'Redigera användaradress',
                'useraddress_delete'    => 'Radering av användaradress',
                'useraddress_detail'    => 'Användaradressuppgifter',
            ]
        ],
        'goods_index' => [
            'name'  => 'Varuförvaltning',
            'item'  => [
                'goods_index'                       => 'Varuförvaltning',
                'goods_saveinfo'                    => 'Produkt Lägg till/redigera sida',
                'goods_save'                        => 'Lägg till/redigera produkt',
                'goods_delete'                      => 'Produktradering',
                'goods_statusupdate'                => 'Uppdatering av produktstatus',
                'goods_basetemplate'                => 'Hämta produktbasmallen',
                'goods_detail'                      => 'Produktinformation',
                'goodscategory_index'               => 'Varuklassificering',
                'goodscategory_save'                => 'Produktkategori Lägg till/redigera',
                'goodscategory_delete'              => 'Strykning av produktklassificering',
                'goodsparamstemplate_index'         => 'Produktparametrar',
                'goodsparamstemplate_delete'        => 'Radering av produktparametrar',
                'goodsparamstemplate_statusupdate'  => 'Statusuppdatering av produktparametrar',
                'goodsparamstemplate_saveinfo'      => 'Produktparameter Lägg till/redigera sida',
                'goodsparamstemplate_save'          => 'Lägg till/redigera produktparametrar',
                'goodsparamstemplate_detail'        => 'Detaljer om produktparametrar',
                'goodsspectemplate_index'           => 'Produktspecifikationer',
                'goodsspectemplate_delete'          => 'Strykning av produktspecifikation',
                'goodsspectemplate_statusupdate'    => 'Statusuppdatering av produktspecifikationen',
                'goodsspectemplate_saveinfo'        => 'Produktspecifikation Lägg till/redigera sida',
                'goodsspectemplate_save'            => 'Produktspecifikation Lägg till/redigera',
                'goodsspectemplate_detail'          => 'Detaljer om produktspecifikation',
                'goodscomments_detail'              => 'Detaljer om produktöversyn',
                'goodscomments_index'               => 'Produktomdömen',
                'goodscomments_reply'               => 'Svar på produktöversyn',
                'goodscomments_delete'              => 'Radering av produktöversyn',
                'goodscomments_statusupdate'        => 'Statusuppdatering för produktgranskning',
                'goodscomments_saveinfo'            => 'Produktkommentar Lägg till/redigera sida',
                'goodscomments_save'                => 'Produktkommentar Lägg till/redigera',
                'goodsbrowse_index'                 => 'Produktbläddring',
                'goodsbrowse_delete'                => 'Produktbläddring Ta bort',
                'goodsbrowse_detail'                => 'Information om produktbläddring',
                'goodsfavor_index'                  => 'Produktsamling',
                'goodsfavor_delete'                 => 'Produktsamling Ta bort',
                'goodsfavor_detail'                 => 'Detaljer om produktsamlingen',
                'goodscart_index'                   => 'Varukorg',
                'goodscart_delete'                  => 'Varukorg Ta bort',
                'goodscart_detail'                  => 'Detaljer om produktvagnen',
            ]
        ],
        'order_index' => [
            'name'  => 'Beställningshantering',
            'item'  => [
                'order_index'             => 'Beställningshantering',
                'order_delete'            => 'Radering av order',
                'order_cancel'            => 'Annullering av order',
                'order_delivery'          => 'Beställningsändning',
                'order_collect'           => 'Beställningskvitto',
                'order_pay'               => 'Beställningsbetalning',
                'order_confirm'           => 'Bekräftelse av order',
                'order_detail'            => 'Beställningsinformation',
                'orderaftersale_index'    => 'Beställning efter försäljning',
                'orderaftersale_delete'   => 'Beställ efter försäljning Ta bort',
                'orderaftersale_cancel'   => 'Annullering av order efter försäljning',
                'orderaftersale_audit'    => 'Översyn av order efter försäljning',
                'orderaftersale_confirm'  => 'Beställningsbekräftelse efter försäljning',
                'orderaftersale_refuse'   => 'Avslag av order',
                'orderaftersale_detail'   => 'Order After Sales Details',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Webbplatshantering',
            'item'  => [
                'navigation_index'         => 'Navigationshantering',
                'navigation_save'          => 'Navigering Lägg till/redigera',
                'navigation_delete'        => 'Navigering Ta bort',
                'navigation_statusupdate'  => 'Navigationsstatusuppdatering',
                'customview_index'         => 'Anpassad sida',
                'customview_saveinfo'      => 'Anpassad sida Lägg till/redigera sida',
                'customview_save'          => 'Anpassad sida Lägg till/redigera',
                'customview_delete'        => 'Anpassad sidborttagning',
                'customview_statusupdate'  => 'Anpassad sidstatusuppdatering',
                'customview_detail'        => 'Anpassad sidinformation',
                'link_index'               => 'Vänliga länkar',
                'link_saveinfo'            => 'Vänlig länk Lägg till/redigera sida',
                'link_save'                => 'Vänlig länk Lägg till/redigera',
                'link_delete'              => 'Ta bort vänlig länk',
                'link_statusupdate'        => 'Statusuppdatering för vänlig länk',
                'link_detail'              => 'Vänlig länk Detaljer',
                'theme_index'              => 'Temahantering',
                'theme_save'               => 'Temahantering Lägg till/redigera',
                'theme_upload'             => 'Installation av uppladdning av tema',
                'theme_delete'             => 'Radering av tema',
                'theme_download'           => 'Temanerladdning',
                'slide_index'              => 'Startsidans rotation',
                'slide_saveinfo'           => 'Poll Lägg till/redigera sida',
                'slide_save'               => 'Sänd Lägg till/redigera',
                'slide_statusupdate'       => 'Uppdatering av omröstningsstatus',
                'slide_delete'             => 'Poll Ta bort',
                'slide_detail'             => 'Sändningsinformation',
                'screeningprice_index'     => 'Filterpris',
                'screeningprice_save'      => 'Filtreringspris Lägg till/redigera',
                'screeningprice_delete'    => 'Filtreringspris Ta bort',
                'region_index'             => 'Regional förvaltning',
                'region_save'              => 'Region Lägg till/redigera',
                'region_delete'            => 'Region Ta bort',
                'region_codedata'          => 'Hämta data om områdenummer',
                'express_index'            => 'Expresshantering',
                'express_save'             => 'Express Lägg till/redigera',
                'express_delete'           => 'Uttryck Radera',
                'payment_index'            => 'Betalningsmetod',
                'payment_saveinfo'         => 'Sida för installation/redigering av betalningsmetod',
                'payment_save'             => 'Installation/redigering av betalningsmetod',
                'payment_delete'           => 'Betalningsmetod Ta bort',
                'payment_install'          => 'Installation av betalningsmetod',
                'payment_statusupdate'     => 'Statusuppdatering av betalningsmetod',
                'payment_uninstall'        => 'Avinstallering av betalningsmetod',
                'payment_upload'           => 'Ladda upp betalningsmetod',
                'quicknav_index'           => 'Snabbnavigering',
                'quicknav_saveinfo'        => 'Snabbnavigering lägg till/redigera sida',
                'quicknav_save'            => 'Snabbnavigering Lägg till/redigera',
                'quicknav_statusupdate'    => 'Snabbuppdatering av navigeringsstatus',
                'quicknav_delete'          => 'Snabbnavigering Ta bort',
                'quicknav_detail'          => 'Snabbnavigeringsinformation',
                'design_index'             => 'Siddesign',
                'design_saveinfo'          => 'Siddesign Lägg till/redigera sida',
                'design_save'              => 'Siddesign Lägg till/redigera',
                'design_statusupdate'      => 'Statusuppdatering för siddesign',
                'design_upload'            => 'Importera siddesign',
                'design_download'          => 'Siddesign Ladda ner',
                'design_sync'              => 'Synkronisering av siddesign Startsida',
                'design_delete'            => 'Siddesign Ta bort',
            ]
        ],
        'brand_index' => [
            'name'  => 'Varumärkeshantering',
            'item'  => [
                'brand_index'           => 'Varumärkeshantering',
                'brand_saveinfo'        => 'Märke Lägg till/redigera sida',
                'brand_save'            => 'Märke Lägg till/redigera',
                'brand_statusupdate'    => 'Uppdatering av varumärkesstatus',
                'brand_delete'          => 'Radering av varumärke',
                'brand_detail'          => 'Märkesinformation',
                'brandcategory_index'   => 'Varumärkesklassificering',
                'brandcategory_save'    => 'Varumärkeskategori Lägg till/redigera',
                'brandcategory_delete'  => 'Varumärkeskategori Ta bort',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Lagerhantering',
            'item'  => [
                'warehouse_index'               => 'Lagerhantering',
                'warehouse_saveinfo'            => 'Lager Lägg till/redigera sida',
                'warehouse_save'                => 'Lager Lägg till/redigera',
                'warehouse_delete'              => 'Lager Ta bort',
                'warehouse_statusupdate'        => 'Lagerstatusuppdatering',
                'warehouse_detail'              => 'Lagerinformation',
                'warehousegoods_index'          => 'Lagervaruhantering',
                'warehousegoods_detail'         => 'Lagerartikelinformation',
                'warehousegoods_delete'         => 'Radering av lagerobjekt',
                'warehousegoods_statusupdate'   => 'Statusuppdatering av lagerobjekt',
                'warehousegoods_goodssearch'    => 'Sök efter lagerobjekt',
                'warehousegoods_goodsadd'       => 'Sök efter lagerobjekt Lägg till',
                'warehousegoods_goodsdel'       => 'Sök efter lagerobjekt Ta bort',
                'warehousegoods_inventoryinfo'  => 'Lagerobjektsredigeringssida',
                'warehousegoods_inventorysave'  => 'Lagerobjektsregistrering Redigera',
            ]
        ],
        'app_index' => [
            'name'  => 'Telefonhantering',
            'item'  => [
                'appconfig_index'            => 'Grundläggande konfiguration',
                'appconfig_save'             => 'Grundläggande konfigurationsspårning',
                'apphomenav_index'           => 'Navigation på startsidan',
                'apphomenav_saveinfo'        => 'Hemnavigering Lägg till/redigera sida',
                'apphomenav_save'            => 'Hemnavigering lägg till/redigera',
                'apphomenav_statusupdate'    => 'Statusuppdatering för navigering på startsidan',
                'apphomenav_delete'          => 'Radering av hemnavigering',
                'apphomenav_detail'          => 'Information om navigering på startsidan',
                'appcenternav_index'         => 'Navigering i användarcenter',
                'appcenternav_saveinfo'      => 'Navigering i användarcenter Lägg till/redigera sida',
                'appcenternav_save'          => 'Navigering i användarcenter Lägg till/redigera',
                'appcenternav_statusupdate'  => 'Statusuppdatering för navigering i användarcenter',
                'appcenternav_delete'        => 'Navigering i användarcenter Ta bort',
                'appcenternav_detail'        => 'Navigeringsinformation för användarcenter',
                'appmini_index'              => 'Miniprogramlista',
                'appmini_created'            => 'Produktion av små paket',
                'appmini_delete'             => 'Radering av miniprogrampaket',
                'appmini_themeupload'        => 'Ladda upp miniprogramtema',
                'appmini_themesave'          => 'Byt miniprogramtema',
                'appmini_themedelete'        => 'Byt miniprogramtema',
                'appmini_themedownload'      => 'Ladda ner miniprogramtema',
                'appmini_config'             => 'Inställning av miniprogram',
                'appmini_save'               => 'Spara miniprogramkonfiguration',
            ]
        ],
        'article_index' => [
            'name'  => 'Artikel förvaltning',
            'item'  => [
                'article_index'           => 'Artikel förvaltning',
                'article_saveinfo'        => 'Artikel Lägg till/redigera sida',
                'article_save'            => 'Artikel Lägg till/redigera',
                'article_delete'          => 'Artikel utgående',
                'article_statusupdate'    => 'Uppdatering av artikelstatus',
                'article_detail'          => 'Artikel Detaljer',
                'articlecategory_index'   => 'Artikelklassificering',
                'articlecategory_save'    => 'Artikel Kategori Redigera/Lägg till',
                'articlecategory_delete'  => 'Artikel Kategori Stryk',
            ]
        ],
        'data_index' => [
            'name'  => 'Datahantering',
            'item'  => [
                'answer_index'          => 'Frågor och svar',
                'answer_reply'          => 'Svar på frågor och svar',
                'answer_delete'         => 'Radering av frågor och svar',
                'answer_statusupdate'   => 'Statusuppdatering för frågor och svar',
                'answer_saveinfo'       => 'Fråga och svar Lägg till/redigera sida',
                'answer_save'           => 'Fråga och svar Lägg till/redigera',
                'answer_detail'         => 'Information om frågor och svar',
                'message_index'         => 'Meddelandehantering',
                'message_delete'        => 'Radering av brev',
                'message_detail'        => 'Brevinformation',
                'paylog_index'          => 'Betalningslogg',
                'paylog_detail'         => 'Uppgifter om betalningslogg',
                'paylog_close'          => 'Betalningslogg stängd',
                'payrequestlog_index'   => 'Logglista över betalningsbegäran',
                'payrequestlog_detail'  => 'Uppgifter om betalningsbegäran',
                'refundlog_index'       => 'Återbetalningslogg',
                'refundlog_detail'      => 'Information om återbetalningslogg',
                'integrallog_index'     => 'Poänglogg',
                'integrallog_detail'    => 'Poänglogginformation',
            ]
        ],
        'store_index' => [
            'name'  => 'Programcenter',
            'item'  => [
                'pluginsadmin_index'         => 'Programhantering',
                'plugins_index'              => 'Hantering av ansökningsomgångar',
                'pluginsadmin_saveinfo'      => 'App Lägg till/redigera sida',
                'pluginsadmin_save'          => 'App Lägg till/redigera',
                'pluginsadmin_statusupdate'  => 'Programstatusuppdatering',
                'pluginsadmin_delete'        => 'Ta bort app',
                'pluginsadmin_upload'        => 'Ladda upp app',
                'pluginsadmin_download'      => 'App Packaging',
                'pluginsadmin_install'       => 'Installation av program',
                'pluginsadmin_uninstall'     => 'Avinstallera appar',
                'pluginsadmin_sortsave'      => 'Använd Sortera Spara',
                'store_index'                => 'App Store',
                'packageinstall_index'       => 'Sidan för paketinstallation',
                'packageinstall_install'     => 'Installation av programvarupaket',
                'packageupgrade_upgrade'     => 'Uppdatering av programvarupaket',
            ]
        ],
        'tool_index' => [
            'name'  => 'verktyg',
                'item'                  => [
                'cache_index'           => 'Cachehantering',
                'cache_statusupdate'    => 'Webbplatscacheuppdatering',
                'cache_templateupdate'  => 'Mallcacheuppdatering',
                'cache_moduleupdate'    => 'Modulcacheuppdatering',
                'cache_logdelete'       => 'Loggradering',
                'sqlconsole_index'      => 'SQL- konsol',
                'sqlconsole_implement'  => 'SQL- körning',
            ]
        ],
    ],
];
?>