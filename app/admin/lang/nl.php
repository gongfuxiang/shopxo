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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'Ontwikkeling van het bedrag van de ordertransactie',
            'order_trading_trend_name'          => 'Trend in orderhandel',
            'goods_hot_name'                    => 'Warme verkoopgoederen',
            'goods_hot_tips'                    => 'Alleen de eerste 30-items tonen',
            'payment_name'                      => 'Betalingsmethode',
            'order_region_name'                 => 'Orde geografische spreiding',
            'order_region_tips'                 => 'Toon slechts 30 stukken gegevens',
            'upgrade_check_loading_tips'        => 'Ontvang de nieuwste inhoud, wacht alstublieft',
            'upgrade_version_name'              => 'Bijgewerkte versie:',
            'upgrade_date_name'                 => 'Update datum:',
        ],
        // 页面基础
        'base_update_button_title'              => 'Nu bijwerken',
        'base_item_base_stats_title'            => 'Winkelstatistieken',
        'base_item_base_stats_tips'             => 'Tijdfiltering is alleen geldig voor totalen',
        'base_item_user_title'                  => 'Totaal gebruikers',
        'base_item_order_number_title'          => 'Totaal bestelbedrag',
        'base_item_order_complete_number_title' => 'Totaal transactievolume',
        'base_item_order_complete_title'        => 'Orderbedrag',
        'base_item_last_month_title'            => 'Vorige maand',
        'base_item_same_month_title'            => 'dezelfde maand',
        'base_item_yesterday_title'             => 'gisteren',
        'base_item_today_title'                 => 'vandaag',
        'base_item_order_profit_title'          => 'Ontwikkeling van het bedrag van de ordertransactie',
        'base_item_order_trading_title'         => 'Trend in orderhandel',
        'base_item_order_tips'                  => 'Alle bestellingen',
        'base_item_hot_sales_goods_title'       => 'Warme verkoopgoederen',
        'base_item_hot_sales_goods_tips'        => 'Orders uitsluiten die zijn geannuleerd en gesloten',
        'base_item_payment_type_title'          => 'Betalingsmethode',
        'base_item_map_whole_country_title'     => 'Orde geografische spreiding',
        'base_item_map_whole_country_tips'      => 'Orders die zijn geannuleerd en standaardafmetingen (provincies) uitsluiten',
        'base_item_map_whole_country_province'  => 'provincie',
        'base_item_map_whole_country_city'      => 'stad',
        'base_item_map_whole_country_county'    => 'District/County',
        'system_info_title'                     => 'systeeminformatie',
        'system_ver_title'                      => 'Softwareversie',
        'system_os_ver_title'                   => 'besturingssysteem',
        'system_php_ver_title'                  => 'PHP-versie',
        'system_mysql_ver_title'                => 'MySQL-versie',
        'system_server_ver_title'               => 'Informatie aan de serverzijde',
        'system_host_title'                     => 'Huidige domeinnaam',
        'development_team_title'                => 'het ontwikkelingsteam',
        'development_team_website_title'        => 'Officiële website van het bedrijf',
        'development_team_website_value'        => 'Shanghai Zongzhige Technology Co., Ltd',
        'development_team_support_title'        => 'technische ondersteuning',
        'development_team_support_value'        => 'ShopXO Enterprise E-commerce System Provider',
        'development_team_ask_title'            => 'Communicatiekwesties',
        'development_team_ask_value'            => 'Communicatie-vragen van ShopXO',
        'development_team_agreement_title'      => 'Open Source Protocol',
        'development_team_agreement_value'      => 'Open source overeenkomsten bekijken',
        'development_team_update_log_title'     => 'Log bijwerken',
        'development_team_update_log_value'     => 'Update logboek bekijken',
        'development_team_members_title'        => 'O&O-leden',
        'development_team_members_value'        => [
            ['name' => 'Broeder Gong.', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'gebruiker',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'Gebruiker ID',
            'number_code'           => 'Lidmaatschapscode',
            'system_type'           => 'Systeemtype',
            'platform'              => 'Platform',
            'avatar'                => 'hoofdportret',
            'username'              => 'gebruikersnaam',
            'nickname'              => 'bijnaam',
            'mobile'                => 'mobiele telefoon',
            'email'                 => 'postvak',
            'gender_name'           => 'Geslacht',
            'status_name'           => 'staat',
            'province'              => 'Provincie',
            'city'                  => 'Stad',
            'county'                => 'District/County',
            'address'               => 'Gedetailleerd adres',
            'birthday'              => 'verjaardag',
            'integral'              => 'Beschikbare punten',
            'locking_integral'      => 'Gesloten integraal',
            'referrer'              => 'Gebruikers uitnodigen',
            'referrer_placeholder'  => 'Voer de gebruikersnaam/bijnaam/telefoon/e-mail van de uitnodiging in',
            'add_time'              => 'Registratietijd',
            'upd_time'              => 'Updatetijd',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Gebruikersadres',
        // 详情
        'detail_user_address_idcard_name'       => 'volledige naam',
        'detail_user_address_idcard_number'     => 'nummer',
        'detail_user_address_idcard_pic'        => 'Foto',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Gebruikersinformatie',
            'user_placeholder'  => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'alias'             => 'alias',
            'name'              => 'contacten',
            'tel'               => 'contactnummer',
            'province_name'     => 'Provincie',
            'city_name'         => 'Stad',
            'county_name'       => 'District/County',
            'address'           => 'Gedetailleerd adres',
            'position'          => 'Lengte en breedtegraad',
            'idcard_info'       => 'ID-kaartgegevens',
            'is_default'        => 'Standaard of niet',
            'add_time'          => 'Aanmaaktijd',
            'upd_time'          => 'Updatetijd',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Opslaan wordt van kracht na verwijdering. Weet u zeker dat u doorgaat?',
            'address_no_data'                   => 'Adresgegevens zijn leeg',
            'address_not_exist'                 => 'Adres bestaat niet',
            'address_logo_message'              => 'Upload een logo afbeelding',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Basisconfiguratie', 'type' => 'base'],
            ['name' => 'Site-instellingen', 'type' => 'siteset'],
            ['name' => 'Type site', 'type' => 'sitetype'],
            ['name' => 'Gebruikersregistratie', 'type' => 'register'],
            ['name' => 'Gebruikersaanmelding', 'type' => 'login'],
            ['name' => 'Wachtwoord herstel', 'type' => 'forgetpwd'],
            ['name' => 'Verificatiecode', 'type' => 'verify'],
            ['name' => 'Bestelling na verkoop', 'type' => 'orderaftersale'],
            ['name' => 'behuizing', 'type' => 'attachment'],
            ['name' => 'cache', 'type' => 'cache'],
            ['name' => 'Uitbreidingen', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'startpagina', 'type' => 'index'],
            ['name' => 'grondstof', 'type' => 'goods'],
            ['name' => 'zoeken', 'type' => 'search'],
            ['name' => 'volgorde', 'type' => 'order'],
            ['name' => 'Korting', 'type' => 'discount'],
            ['name' => 'verlengen', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Site-status',
        'base_item_site_domain_title'           => 'Site Domeinnaam Adres',
        'base_item_site_filing_title'           => 'Informatie indienen',
        'base_item_site_other_title'            => 'andere',
        'base_item_session_cache_title'         => 'Sessiecache-configuratie',
        'base_item_data_cache_title'            => 'Configuratie van gegevenscache',
        'base_item_redis_cache_title'           => 'Redis cache configuratie',
        'base_item_crontab_config_title'        => 'Timing script configuratie',
        'base_item_quick_nav_title'             => 'Snelle navigatie',
        'base_item_user_base_title'             => 'Gebruikersbasis',
        'base_item_user_address_title'          => 'Gebruikersadres',
        'base_item_multilingual_title'          => 'Meertalig',
        'base_item_site_auto_mode_title'        => 'Automatische modus',
        'base_item_site_manual_mode_title'      => 'Handmatige modus',
        'base_item_default_payment_title'       => 'Standaard betalingsmethode',
        'base_item_display_type_title'          => 'Weergavetype',
        'base_item_self_extraction_title'       => 'Zelfaanzuigend',
        'base_item_fictitious_title'            => 'Virtuele verkoop',
        'choice_upload_logo_title'              => 'Selecteer een logo',
        'add_goods_title'                       => 'Toevoeging van producten',
        'add_self_extractio_address_title'      => 'Adres toevoegen',
        'site_domain_tips_list'                 => [
            '1. Als de site domeinnaam niet is ingesteld, worden de huidige site domeinnaam, domeinnaam en adres gebruikt[ '.__MY_DOMAIN__.' ]',
            '2. Als de bijlage en het statische adres niet zijn ingesteld, wordt het statische domeinnaam adres van de huidige site gebruikt[ '.__MY_PUBLIC_URL__.' ]',
            '3. Als public niet is ingesteld als de hoofdmap aan de serverzijde, moet de configuratie van [attachment cdn domeinnaam, css/js statisch bestand cdn domeinnaam] gevolgd worden door public, zoals:'.__MY_PUBLIC_URL__.'public/',
            '"Wanneer een project in opdrachtregelmodus wordt uitgevoerd, moet het adres van de regio worden geconfigureerd, anders ontbreekt bij sommige adressen in het project domeinnaaminformatie."',
            '5. Niet willekeurig configureren. Een onjuist adres kan ervoor zorgen dat de website ontoegankelijk is (de adresconfiguratie begint met http). Als uw eigen site is geconfigureerd met https, begint deze met https',
        ],
        'site_cache_tips_list'                  => [
            '1. Standaard bestandscaching en Redis caching PHP vereisen dat de extensie Redis eerst geïnstalleerd wordt',
            '2. Zorg voor de stabiliteit van de Redis-service (na gebruik van de cache voor een sessie kan de onstabiele service ervoor zorgen dat de achtergrond niet kan inloggen)',
            '"Als u een Redis service uitzondering tegenkomt, kunt u niet inloggen op de beheerachtergrond en het bestand [session.php, cache.php] wijzigen in de map [config] van het configuratiebestand."',
        ],
        'goods_tips_list'                       => [
            '1. De standaard weergave aan de WEB kant is niveau 3, met een minimum van niveau 1 en een maximum van niveau 3 (indien ingesteld op niveau 0, is de standaard niveau 3)',
            '2. De standaardweergave op de mobiele terminal is niveau 0 (modus productlijst), minimum niveau 0, en maximum niveau 3 (1-3 zijn geclassificeerde weergavemodi)',
            '3. Verschillende niveaus en frontend categoriepaginastijlen kunnen ook verschillend zijn',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Configureer het maximale aantal producten weergegeven op elke verdieping',
            '2. Het wordt niet aanbevolen om de hoeveelheid te groot te wijzigen, waardoor het lege gebied aan de linkerkant van de pc-terminal te groot zal zijn',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'Uitgebreid: Heat ->Verkoop ->Laatste aflopend',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. U kunt op de producttitel klikken om deze te slepen en sorteren en in volgorde weer te geven',
            '2. Het wordt niet aanbevolen om veel producten toe te voegen, waardoor het lege gebied aan de linkerkant van de pc te groot kan zijn',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. Standaard wordt [Gebruikersnaam, Mobiele Telefoon, E-mail] gebruikt als de unieke gebruiker',
            '2. Indien ingeschakeld, voeg de [Systeem ID] toe en maak deze uniek voor de gebruiker',
        ],
        'extends_crontab_tips'                  => 'Het wordt aanbevolen om het scriptadres toe te voegen aan de Linux geplande taak timing aanvraag. (Het resultaat is SUCS: 0, FAIL: 0, gevolgd door de dubbele punt, is het aantal verwerkte gegevens. SUCS geslaagd, FALI mislukt.)',
        'left_images_random_tips'               => 'De linker afbeelding kan tot drie afbeeldingen uploaden, waarvan er één willekeurig elke keer kan worden weergegeven',
        'background_color_tips'                 => 'Aanpasbare achtergrondafbeelding, standaard achtergrond grijs',
        'site_setup_layout_tips'                => 'Voor de sleep-en-drop-modus moet u zelf de ontwerppagina van de startpagina openen. Sla de geselecteerde configuratie op voordat u verdergaat',
        'site_setup_layout_button_name'         => 'Ga naar ontwerppagina>>',
        'site_setup_goods_category_tips'        => 'Voor meer vloerdisplays, ga naar/Product Management ->Product Classificatie, Primaire Classificatie Instellingen Startpagina Aanbevelingen',
        'site_setup_goods_category_no_data_tips'=> 'Geen gegevens beschikbaar, ga naar/Product Management ->Product Classificatie, Primaire Classificatie Instellingen Home Page voor aanbevelingen',
        'site_setup_order_default_payment_tips' => 'U kunt standaard betaalmethoden instellen die overeenkomen met verschillende platforms. Installeer eerst de betalingsplug-in in [Website Management ->Betalingsmethoden] om deze in te schakelen en beschikbaar te stellen voor gebruikers.',
        'site_setup_choice_payment_message'     => 'Selecteer {:naam} standaard betaalmethode',
        'sitetype_top_tips_list'                => [
            '1. Express levering en conventionele e-commerce processen, waarbij gebruikers een verzendadres selecteren om een bestelling voor betaling te plaatsen ->verkoper verzending ->ontvangst bevestigen ->bestelling voltooien',
            '2. Weergave type, product alleen vertoning, overleg kan worden gestart (bestellingen kunnen niet worden geplaatst)',
            '3. Selecteer het zelf ophaaladres bij het plaatsen van een bestelling, en de gebruiker plaatst een bestelling voor betaling ->Bevestig levering ->Bestelling voltooien',
            '4. Virtuele verkoop, regelmatige e-commerce processen, gebruikersorders voor betaling ->automatische verzending ->bevestiging van picking up ->ordervoltooiing',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'Aanbevolen 300 tot 300px',
        'form_take_address_alias'               => 'alias',
        'form_take_address_alias_message'       => 'Alias opmaak tot 16 tekens',
        'form_take_address_name'                => 'contacten',
        'form_take_address_name_message'        => 'Contactformaat is tussen 2 en 16 tekens',
        'form_take_address_tel'                 => 'contactnummer',
        'form_take_address_tel_message'         => 'Vul het contactnummer in',
        'form_take_address_address'             => 'Gedetailleerd adres',
        'form_take_address_address_message'     => 'Het gedetailleerde adresformaat ligt tussen 1 en 80 tekens',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Achtergrondaanmelding',
        'admin_login_info_bg_images_list_tips'  => [
            'De achtergrondafbeelding bevindt zich in de map [public/static/admin/default/images/login]',
            'Naamregels voor achtergrondafbeeldingen (1-50), zoals 1.jpg',
        ],
        'map_type_tips'                         => 'Vanwege de verschillende kaartstandaarden van elk bedrijf, wissel niet willekeurig van kaarten, wat kan leiden tot onnauwkeurige kaartcoördinatenabel.',
        'apply_map_baidu_name'                  => 'Meld u aan bij Baidu Map Open Platform',
        'apply_map_amap_name'                   => 'Meld je aan bij het Gaode Map Open Platform',
        'apply_map_tencent_name'                => 'Meld je aan bij Tencent Map Open Platform',
        'apply_map_tianditu_name'               => 'Meld je aan bij Tiantu Open Platform',
        'cookie_domain_list_tips'               => [
            '"Als de standaard leeg is, is deze alleen geldig voor de huidige domeinnaam."',
            '2. Als u een secundaire domeinnaam nodig hebt om cookies te delen, vult u de domeinnaam op het hoogste niveau in, zoals baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'merk',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'naam',
            'describe'             => 'beschrijven',
            'logo'                 => 'LOGO',
            'url'                  => 'Officiële website adres',
            'brand_category_text'  => 'Merkclassificatie',
            'is_enable'            => 'Ingeschakeld of niet',
            'sort'                 => 'sorteren',
            'add_time'             => 'Aanmaaktijd',
            'upd_time'             => 'Updatetijd',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Merkclassificatie',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'artikel',
        'detail_content_title'                  => 'Details',
        'detail_images_title'                   => 'Gedetailleerd beeld',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'titel',
            'jump_url'               => 'Jump URL-adres',
            'article_category_name'  => 'classificatie',
            'is_enable'              => 'Ingeschakeld of niet',
            'is_home_recommended'    => 'Aanbeveling van de startpagina',
            'images_count'           => 'Aantal fotos',
            'access_count'           => 'Aantal bezoeken',
            'add_time'               => 'Aanmaaktijd',
            'upd_time'               => 'Updatetijd',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Articleclassificatie',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Aangepaste pagina',
        'detail_content_title'                  => 'Details',
        'detail_images_title'                   => 'Gedetailleerd beeld',
        'save_view_tips'                        => 'Sla op voordat u een voorbeeld van het effect bekijkt',
        // 动态表格
        'form_table'                            => [
            'info'            => 'titel',
            'is_enable'       => 'Ingeschakeld of niet',
            'is_header'       => 'Hoofd of niet',
            'is_footer'       => 'Staart',
            'is_full_screen'  => 'Of het scherm vol is',
            'images_count'    => 'Aantal fotos',
            'access_count'    => 'Aantal bezoeken',
            'add_time'        => 'Aanmaaktijd',
            'upd_time'        => 'Updatetijd',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Download meer ontwerpsjablonen',
        'upload_list_tips'                      => [
            '1. Selecteer het gedownloade zip-pakket voor paginaontwerp',
            '2. Importeren zal automatisch een nieuw stukje gegevens toevoegen',
        ],
        'operate_sync_tips'                     => 'Synchronisatie van gegevens met visualisatie van slepen op de startpagina en latere wijzigingen aan de gegevens worden niet beïnvloed, maar verwijder geen gerelateerde bijlagen',
        // 动态表格
        'form_table'                            => [
            'id'                => 'Gegevens-ID',
            'info'              => 'Basisinformatie',
            'info_placeholder'  => 'Voer een naam in',
            'access_count'      => 'Aantal bezoeken',
            'is_enable'         => 'Ingeschakeld of niet',
            'is_header'         => 'Inclusief hoofd',
            'is_footer'         => 'Inclusief staart',
            'seo_title'         => 'SEO titel',
            'seo_keywords'      => 'SEO trefwoord',
            'seo_desc'          => 'SEO Beschrijving',
            'add_time'          => 'Aanmaaktijd',
            'upd_time'          => 'Updatetijd',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Q&A',
        'user_info_title'                       => 'Gebruikersinformatie',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Gebruikersinformatie',
            'user_placeholder'  => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'name'              => 'contacten',
            'tel'               => 'contactnummer',
            'content'           => 'inhoud',
            'reply'             => 'Inhoud beantwoorden',
            'is_show'           => 'Of te tonen',
            'is_reply'          => 'Antwoord of niet',
            'reply_time_time'   => 'Reactietijd',
            'access_count'      => 'Aantal bezoeken',
            'add_time_time'     => 'Aanmaaktijd',
            'upd_time_time'     => 'Updatetijd',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Pakhuis',
        'top_tips_list'                         => [
            '"Hoe hoger de gewichtswaarde, hoe hoger het gewicht. De voorraad wordt afgetrokken in volgorde van gewicht."',
            '2.Het magazijn kan alleen soft-delete worden, zal niet beschikbaar zijn na verwijdering en alleen de gegevens in de database kunnen worden bewaard. U kunt de bijbehorende productgegevens zelf verwijderen',
            '3. De deactivering en verwijdering van het pakhuis en de bijbehorende goederenvoorraad zullen onmiddellijk worden vrijgegeven',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Naam/alias',
            'level'          => 'gewicht',
            'is_enable'      => 'Ingeschakeld of niet',
            'contacts_name'  => 'contacten',
            'contacts_tel'   => 'contactnummer',
            'province_name'  => 'Provincie',
            'city_name'      => 'Stad',
            'county_name'    => 'District/County',
            'address'        => 'Gedetailleerd adres',
            'position'       => 'Lengte en breedtegraad',
            'add_time'       => 'Aanmaaktijd',
            'upd_time'       => 'Updatetijd',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Selecteer een magazijn',
        ],
        // 基础
        'add_goods_title'                       => 'Toevoeging van producten',
        'no_spec_data_tips'                     => 'Geen specificatiegegevens',
        'batch_setup_inventory_placeholder'     => 'Batch ingestelde waarden',
        'base_spec_inventory_title'             => 'Specificatie inventaris',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Basisinformatie',
            'goods_placeholder'  => 'Voer de productnaam/model in',
            'warehouse_name'     => 'Pakhuis',
            'is_enable'          => 'Ingeschakeld of niet',
            'inventory'          => 'Totale inventaris',
            'spec_inventory'     => 'Specificatie inventaris',
            'add_time'           => 'Aanmaaktijd',
            'upd_time'           => 'Updatetijd',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'De beheerdersinformatie bestaat niet',
        // 列表
        'top_tips_list'                         => [
            '1. Het beheerdersaccount heeft standaard alle machtigingen en kan niet worden gewijzigd.',
            '2. Het admin account kan niet worden gewijzigd, maar kan worden gewijzigd in de gegevenstabel ('. MyConfig('database.connections.mysql.prefix'). 'admin) veld gebruikersnaam',
        ],
        'base_nav_title'                        => 'beheerders',
        // 登录
        'login_type_username_title'             => 'Account wachtwoord',
        'login_type_mobile_title'               => 'Mobiele verificatiecode',
        'login_type_email_title'                => 'E-mailverificatiecode',
        'login_close_tips'                      => 'Tijdelijk gesloten login',
        // 忘记密码
        'form_forget_password_name'             => 'Je wachtwoord vergeten?',
        'form_forget_password_tips'             => 'Neem contact op met uw beheerder om uw wachtwoord opnieuw in te stellen',
        // 动态表格
        'form_table'                            => [
            'username'              => 'beheerders',
            'status'                => 'staat',
            'gender'                => 'Geslacht',
            'mobile'                => 'mobiele telefoon',
            'email'                 => 'postvak',
            'role_name'             => 'Rolgroepen',
            'login_total'           => 'Aantal aanmeldingen',
            'login_time'            => 'Laatste aanmeldingstijd',
            'add_time'              => 'Aanmaaktijd',
            'upd_time'              => 'Updatetijd',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Gebruikersregistratieovereenkomst', 'type' => 'register'],
            ['name' => 'Privacybeleid voor gebruikers', 'type' => 'privacy'],
            ['name' => 'Overeenkomst tot annulering van de rekening', 'type' => 'logout']
        ],
        'top_tips'          => 'Add parameter is to front-end access protocol address_ Content=1 toont alleen protocolinhoud',
        'view_detail_name'                      => 'Details bekijken',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Basisconfiguratie', 'type' => 'index'],
            ['name' => 'APP/applet', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Huidige thema', 'type' => 'index'],
            ['name' => 'Thema installatie', 'type' => 'upload'],
            ['name' => 'Bronpakket downloaden', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Meer thema downloads',
        'nav_theme_download_name'               => 'Bekijk de tutorial voor het verpakken van applets',
        'nav_theme_download_tips'               => 'Het mobiele telefoonthema is ontwikkeld met behulp van uniapp (ondersteuning voor multiterminal applet+H5), en de app is ook in noodaanpassing.',
        'form_alipay_extend_title'              => 'Configuratie van de klantenservice',
        'form_alipay_extend_tips'               => 'PS: Als deze is ingeschakeld in [APP/applet] (om online klantenservice in te schakelen), moet de volgende configuratie worden ingevuld met [Enterprise Code] en [Chat Window Code]',
        'form_theme_upload_tips'                => 'Upload een zip gecomprimeerd installatiepakket',
        'list_no_data_tips'                     => 'Geen gerelateerde themapakketten',
        'list_author_title'                     => 'auteur',
        'list_version_title'                    => 'Toepasselijke versie',
        'package_generate_tips'                 => 'De generatietijd is relatief lang, sluit het browservenster niet!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Pakketnaam',
            'size'  => 'grootte',
            'url'   => 'Downloadadres',
            'time'  => 'Aanmaaktijd',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'SMS-instellingen', 'type' => 'index'],
            ['name' => 'Berichtsjabloon', 'type' => 'message'],
        ],
        'top_tips'                              => 'Alibaba Cloud SMS management adres',
        'top_to_aliyun_tips'                    => 'Klik om SMS van AliCloud te kopen',
        'base_item_admin_title'                 => 'backstage',
        'base_item_index_title'                 => 'frontend',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Postvak-instellingen', 'type' => 'index'],
            ['name' => 'Berichtsjabloon', 'type' => 'message'],
        ],
        'top_tips'                              => 'Vanwege sommige verschillen en configuraties tussen verschillende mailbox platforms, zal de specifieke configuratie tutorial voor het mailbox platform prevaleren',
        // 基础
        'test_title'                            => 'test',
        'test_content'                          => 'E-mailconfiguratieprogramma Testinhoud verzenden',
        'base_item_admin_title'                 => 'backstage',
        'base_item_index_title'                 => 'frontend',
        // 表单
        'form_item_test'                        => 'Test het ontvangen e-mailadres',
        'form_item_test_tips'                   => 'Sla de configuratie op voor het testen',
        'form_item_test_button_title'           => 'test',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Corresponderende pseudo statische regels configureren volgens verschillende serveromgevingen [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'grondstof',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Basisinformatie', 'type'=>'base'],
            'specifications'  => ['name' => 'Productspecificaties', 'type'=>'specifications'],
            'parameters'      => ['name' => 'Productparameters', 'type'=>'parameters'],
            'photo'           => ['name' => 'Productalbum', 'type'=>'photo'],
            'video'           => ['name' => 'Productvideo', 'type'=>'video'],
            'app'             => ['name' => 'Apparaatstatus', 'type'=>'app'],
            'web'             => ['name' => 'Computergegevens', 'type'=>'web'],
            'fictitious'      => ['name' => 'Virtuele informatie', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Uitgebreide gegevens', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO-informatie', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Product ID',
            'info'                    => 'Produktinformatie',
            'category_text'           => 'Grondstoffenclassificatie',
            'brand_name'              => 'merk',
            'price'                   => 'Verkoopprijs (RMB)',
            'original_price'          => 'Originele prijs (yuan)',
            'inventory'               => 'Totale inventaris',
            'is_shelves'              => 'Bovenste en onderste planken',
            'is_deduction_inventory'  => 'Inventarisatie aftrek',
            'site_type'               => 'Producttype',
            'model'                   => 'Productmodel',
            'place_origin_name'       => 'Plaats van productie',
            'give_integral'           => 'Percentage bonuspunten kopen',
            'buy_min_number'          => 'Minimumaankoophoeveelheid per tijd',
            'buy_max_number'          => 'Maximale eenmalige aankoophoeveelheid',
            'sales_count'             => 'verkoopvolume',
            'access_count'            => 'Aantal bezoeken',
            'add_time'                => 'Aanmaaktijd',
            'upd_time'                => 'Updatetijd',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Grondstoffenclassificatie',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Productbeoordelingen',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Gebruikersinformatie',
            'user_placeholder'   => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'goods'              => 'Basisinformatie',
            'goods_placeholder'  => 'Voer de productnaam/model in',
            'business_type'      => 'Bedrijfstype',
            'content'            => 'Inhoud van commentaar',
            'images'             => 'Afbeelding commentaar',
            'rating'             => 'score',
            'reply'              => 'Inhoud beantwoorden',
            'is_show'            => 'Of te tonen',
            'is_anonymous'       => 'Anoniem of niet',
            'is_reply'           => 'Antwoord of niet',
            'reply_time_time'    => 'Reactietijd',
            'add_time_time'      => 'Aanmaaktijd',
            'upd_time_time'      => 'Updatetijd',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Productparameters',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Grondstoffenclassificatie',
            'name'          => 'naam',
            'is_enable'     => 'Ingeschakeld of niet',
            'config_count'  => 'Aantal parameters',
            'add_time'      => 'Aanmaaktijd',
            'upd_time'      => 'Updatetijd',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Grondstoffenclassificatie',
            'name'         => 'naam',
            'is_enable'    => 'Ingeschakeld of niet',
            'content'      => 'Specificatiewaarde',
            'add_time'     => 'Aanmaaktijd',
            'upd_time'     => 'Updatetijd',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Gebruikersinformatie',
            'user_placeholder'   => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'goods'              => 'Produktinformatie',
            'goods_placeholder'  => 'Voer productnaam/korte beschrijving/SEO-informatie in',
            'price'              => 'Verkoopprijs (RMB)',
            'original_price'     => 'Originele prijs (yuan)',
            'add_time'           => 'Aanmaaktijd',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Gebruikersinformatie',
            'user_placeholder'   => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'goods'              => 'Produktinformatie',
            'goods_placeholder'  => 'Voer productnaam/korte beschrijving/SEO-informatie in',
            'price'              => 'Verkoopprijs (RMB)',
            'original_price'     => 'Originele prijs (yuan)',
            'add_time'           => 'Aanmaaktijd',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Gebruikersinformatie',
            'user_placeholder'   => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'goods'              => 'Produktinformatie',
            'goods_placeholder'  => 'Voer productnaam/korte beschrijving/SEO-informatie in',
            'price'              => 'Verkoopprijs (RMB)',
            'original_price'     => 'Originele prijs (yuan)',
            'add_time'           => 'Aanmaaktijd',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Vriendelijke koppelingen',
        // 动态表格
        'form_table'                            => [
            'info'                => 'naam',
            'url'                 => 'Url-adres',
            'describe'            => 'beschrijven',
            'is_enable'           => 'Ingeschakeld of niet',
            'is_new_window_open'  => 'Of een nieuw venster wordt geopend',
            'sort'                => 'sorteren',
            'add_time'            => 'Aanmaaktijd',
            'upd_time'            => 'Updatetijd',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Tussennavigatie', 'type' => 'header'],
            ['name' => 'Ondernavigatie', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'op maat',
            'article'           => 'artikel',
            'customview'        => 'Aangepaste pagina',
            'goods_category'    => 'Grondstoffenclassificatie',
            'design'            => 'Pagina-ontwerp',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Navigatienaam',
            'data_type'           => 'Navigatiegegevenstype',
            'is_show'             => 'Of te tonen',
            'is_new_window_open'  => 'Nieuw venster openen',
            'sort'                => 'sorteren',
            'add_time'            => 'Aanmaaktijd',
            'upd_time'            => 'Updatetijd',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Bestelling ID is onjuist',
            'express_choice_tips'               => 'Selecteer een leveringsmethode',
            'payment_choice_tips'               => 'Selecteer een betaalmethode',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Zendingsoperaties',
        'form_payment_title'                    => 'Betalingsoperaties',
        'form_item_take'                        => 'Opnamecode',
        'form_item_take_message'                => 'Vul de 4-cijferige afhaalcode in',
        'form_item_express_number'              => 'koeriersnummer',
        'form_item_express_number_message'      => 'Gelieve het expressrekeningnummer in te vullen',
        // 地址
        'detail_user_address_title'             => 'Verzendadres',
        'detail_user_address_name'              => 'geadresseerde',
        'detail_user_address_tel'               => 'Telefoon ontvangen',
        'detail_user_address_value'             => 'Gedetailleerd adres',
        'detail_user_address_idcard'            => 'ID-kaartgegevens',
        'detail_user_address_idcard_name'       => 'volledige naam',
        'detail_user_address_idcard_number'     => 'nummer',
        'detail_user_address_idcard_pic'        => 'Foto',
        'detail_take_address_title'             => 'Opnameadres',
        'detail_take_address_contact'           => 'Contactgegevens',
        'detail_take_address_value'             => 'Gedetailleerd adres',
        'detail_fictitious_title'               => 'Belangrijke informatie',
        // 订单售后
        'detail_aftersale_status'               => 'staat',
        'detail_aftersale_type'                 => 'type',
        'detail_aftersale_price'                => 'bedrag',
        'detail_aftersale_number'               => 'hoeveelheid',
        'detail_aftersale_reason'               => 'reden',
        // 商品
        'detail_goods_title'                    => 'Item bestellen',
        'detail_payment_amount_less_tips'       => 'Houd er rekening mee dat het bedrag van de bestelling lager is dan het totale bedrag',
        'detail_no_payment_tips'                => 'Houd er rekening mee dat de bestelling nog niet betaald is',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Basisinformatie',
            'goods_placeholder'   => 'Voer het bestelnummer/bestelnummer/productnaam/model in',
            'user'                => 'Gebruikersinformatie',
            'user_placeholder'    => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'status'              => 'Orderstatus',
            'pay_status'          => 'Betalingsstatus',
            'total_price'         => 'Totale prijs (yuan)',
            'pay_price'           => 'Betalingsbedrag (yuan)',
            'price'               => 'Eenheidsprijs (yuan)',
            'warehouse_name'      => 'Verzendmagazijn',
            'order_model'         => 'Ordermodus',
            'client_type'         => 'bron',
            'address'             => 'Adresgegevens',
            'take'                => 'Opnameinformatie',
            'refund_price'        => 'Restitutiebedrag (yuan)',
            'returned_quantity'   => 'Terugzending',
            'buy_number_count'    => 'Totaal aankopen',
            'increase_price'      => 'Verhoogde hoeveelheid (yuan)',
            'preferential_price'  => 'Kortingsbedrag (yuan)',
            'payment_name'        => 'Betalingsmethode',
            'user_note'           => 'Reacties van gebruikers',
            'extension'           => 'Uitgebreide informatie',
            'express_name'        => 'Koerierdienst',
            'express_number'      => 'koeriersnummer',
            'aftersale'           => 'Laatste service na verkoop',
            'is_comments'         => 'Of de gebruiker commentaar geeft',
            'confirm_time'        => 'Bevestigingstijd',
            'pay_time'            => 'Betalingstermijn',
            'delivery_time'       => 'Levertijd',
            'collect_time'        => 'Voltooiingstijd',
            'cancel_time'         => 'Tijd annuleren',
            'close_time'          => 'Sluitijd',
            'add_time'            => 'Aanmaaktijd',
            'upd_time'            => 'Updatetijd',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'Auditactie',
        'form_refuse_title'                     => 'Bewerking weigeren',
        'form_user_info_title'                  => 'Gebruikersinformatie',
        'form_apply_info_title'                 => 'Applicatiegegevens',
        'forn_apply_info_type'                  => 'type',
        'forn_apply_info_price'                 => 'bedrag',
        'forn_apply_info_number'                => 'hoeveelheid',
        'forn_apply_info_reason'                => 'reden',
        'forn_apply_info_msg'                   => 'uitleggen',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Basisinformatie',
            'goods_placeholder'  => 'Voer het bestelnummer/productnaam/model in',
            'user'               => 'Gebruikersinformatie',
            'user_placeholder'   => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'status'             => 'staat',
            'type'               => 'Toepassingstype',
            'reason'             => 'reden',
            'price'              => 'Restitutiebedrag (yuan)',
            'number'             => 'Terugzending',
            'msg'                => 'Instructies voor terugbetaling',
            'refundment'         => 'Type restitutie',
            'voucher'            => 'voucher',
            'express_name'       => 'Koerierdienst',
            'express_number'     => 'koeriersnummer',
            'refuse_reason'      => 'Reden voor afwijzing',
            'apply_time'         => 'Toepassingstijd',
            'confirm_time'       => 'Bevestigingstijd',
            'delivery_time'      => 'Terugkeertijd',
            'audit_time'         => 'Audittijd',
            'add_time'           => 'Aanmaaktijd',
            'upd_time'           => 'Updatetijd',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'Betalingsmethode',
        'nav_store_payment_name'                => 'Meer thema downloads',
        'upload_top_list_tips'                  => [
            [
                'name'  => '"De klassenaam moet consistent zijn met de bestandsnaam (remove.php). Als Alipay.php wordt gebruikt, wordt Alipay gebruikt."',
            ],
            [
                'name'  => '2. Methoden die een klasse moet definiëren',
                'item'  => [
                    '2.1. Configuratiemethode',
                    '2.2. Betaalwijze',
                    '2.3. Response callback methode',
                    '2.4. Asynchrone callback-methode op de hoogte stellen (optioneel, bel de Response-methode als deze niet is gedefinieerd)',
                    '2.5. Restitutiemethode (optioneel, indien niet gedefinieerd, kan de oorspronkelijke route niet worden teruggestort)',
                ],
            ],
            [
                'name'  => '3. Aanpasbare uitvoerinhoudsmethode',
                'item'  => [
                    '3.1. Succesvolle Terugbetaling (facultatief)',
                    '3.2. FoutReturn Payment Failure (facultatief)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: Als aan bovenstaande voorwaarden niet wordt voldaan, kan de plug-in niet worden weergegeven. Upload de plug-in in een gecomprimeerd pakket en ondersteun meerdere betalingsplug-ins in één compressie',
        // 动态表格
        'form_table'                            => [
            'name'            => 'naam',
            'logo'            => 'LOGO',
            'version'         => 'editie',
            'apply_version'   => 'Toepasselijke versie',
            'apply_terminal'  => 'Toepasselijke terminals',
            'author'          => 'auteur',
            'desc'            => 'beschrijven',
            'enable'          => 'Ingeschakeld of niet',
            'open_user'       => 'Open voor gebruikers',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'express',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Huidige thema', 'type' => 'index'],
            ['name' => 'Thema installatie', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'Meer thema downloads',
        'list_author_title'                     => 'auteur',
        'list_version_title'                    => 'Toepasselijke versie',
        'form_theme_upload_tips'                => 'Upload een zip gecomprimeerd thema installatiepakket',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Navigatie in het mobiele gebruikerscentrum',
        // 动态表格
        'form_table'                            => [
            'name'           => 'naam',
            'platform'       => 'Platform',
            'images_url'     => 'Navigatiepictogram',
            'event_type'     => 'Type gebeurtenis',
            'event_value'    => 'Gebeurteniswaarde',
            'desc'           => 'beschrijven',
            'is_enable'      => 'Ingeschakeld of niet',
            'is_need_login'  => 'Moet u inloggen?',
            'sort'           => 'sorteren',
            'add_time'       => 'Aanmaaktijd',
            'upd_time'       => 'Updatetijd',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Navigatie voor mobiele apparaten',
        // 动态表格
        'form_table'                            => [
            'name'           => 'naam',
            'platform'       => 'Platform',
            'images'         => 'Navigatiepictogram',
            'event_type'     => 'Type gebeurtenis',
            'event_value'    => 'Gebeurteniswaarde',
            'is_enable'      => 'Ingeschakeld of niet',
            'is_need_login'  => 'Moet u inloggen?',
            'sort'           => 'sorteren',
            'add_time'       => 'Aanmaaktijd',
            'upd_time'       => 'Updatetijd',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Log betalingsverzoek',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Gebruikersinformatie',
            'user_placeholder'  => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'log_no'            => 'Nr. betalingsopdracht',
            'payment'           => 'Betalingsmethode',
            'status'            => 'staat',
            'total_price'       => 'Bedrag bedrijfsorder (yuan)',
            'pay_price'         => 'Betalingsbedrag (yuan)',
            'business_type'     => 'Bedrijfstype',
            'business_list'     => 'Bedrijfs-ID/doc-nummer',
            'trade_no'          => 'Transactienummer van het betalingsplatform',
            'buyer_user'        => 'Gebruikersaccount voor betaalplatforms',
            'subject'           => 'Ordernaam',
            'pay_time'          => 'Betalingstermijn',
            'close_time'        => 'Sluitijd',
            'add_time'          => 'Aanmaaktijd',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Log betalingsverzoek',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Bedrijfstype',
            'request_params'   => 'Verzoekparameters',
            'response_data'    => 'Responsegegevens',
            'business_handle'  => 'Resultaten van bedrijfsverwerking',
            'request_url'      => 'URL-adres aanvragen',
            'server_port'      => 'Poortnummer',
            'server_ip'        => 'Server IP',
            'client_ip'        => 'Client IP',
            'os'               => 'besturingssysteem',
            'browser'          => 'browser',
            'method'           => 'Type verzoek',
            'scheme'           => 'Http-type',
            'version'          => 'Http-versie',
            'client'           => 'Clientgegevens',
            'add_time'         => 'Aanmaaktijd',
            'upd_time'         => 'Updatetijd',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Gebruikersinformatie',
            'user_placeholder'  => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'payment'           => 'Betalingsmethode',
            'business_type'     => 'Bedrijfstype',
            'business_id'       => 'Bedrijfsorder-ID',
            'trade_no'          => 'Transactienummer van het betalingsplatform',
            'buyer_user'        => 'Gebruikersaccount voor betaalplatforms',
            'refundment_text'   => 'Restitutiemethode',
            'refund_price'      => 'restitutiebedrag',
            'pay_price'         => 'Betalingsbedrag van de bestelling',
            'msg'               => 'beschrijven',
            'add_time_time'     => 'Restitutietijd',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Terug naar applicatiebeheer>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Klik eerst op het vinkje om in te schakelen',
            'save_no_data_tips'                 => 'Geen plugingegevens om op te slaan',
        ],
        // 基础导航
        'base_nav_title'                        => 'toepassing',
        'base_nav_list'                         => [
            ['name' => 'Applicatiebeheer', 'type' => 'index'],
            ['name' => 'Upload app', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'Meer plug-in downloads',
        // 基础页面
        'base_search_input_placeholder'         => 'Voer een naam/beschrijving in',
        'base_top_tips_one'                     => 'Sorteermethode lijst [Aangepaste sortering ->vroegste installatie]',
        'base_top_tips_two'                     => 'Klik en sleep pictogrammenknoppen om invoegtoepassingen en weergavevolgorde aan te passen',
        'base_open_sort_title'                  => 'Sorteren inschakelen',
        'data_list_author_title'                => 'auteur',
        'data_list_author_url_title'            => 'homepage',
        'data_list_version_title'               => 'editie',
        'uninstall_confirm_tips'                => 'Het verwijderen van de installatie kan leiden tot verlies van basisconfiguratiegegevens van de plug-in die niet kunnen worden hersteld.',
        'not_install_divide_title'              => 'De volgende plug-ins zijn niet geïnstalleerd',
        'delete_plugins_text'                   => '1. Alleen apps verwijderen',
        'delete_plugins_text_tips'              => '(Verwijder alleen applicatiecode en bewaar applicatiegegevens)',
        'delete_plugins_data_text'              => '2. Verwijder de applicatie en verwijder de gegevens',
        'delete_plugins_data_text_tips'         => '(Applicatiecode en applicatiegegevens worden verwijderd)',
        'delete_plugins_ps_tips'                => 'PS: Geen van de volgende bewerkingen kan worden hersteld, wees voorzichtig!',
        'delete_plugins_button_name'            => 'Alleen apps verwijderen',
        'delete_plugins_data_button_name'       => 'Apps en gegevens verwijderen',
        'cancel_delete_plugins_button_name'     => 'Denk nog eens na',
        'more_plugins_store_to_text'            => 'Ga naar de app store om meer plugins te kiezen om de site te verrijken>>',
        'no_data_store_to_text'                 => 'Ga naar de app store om pluginrijke sites te selecteren>>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Terug naar achtergrond',
        'get_loading_tips'                      => 'Getting',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'rol',
        'admin_not_modify_tips'                 => 'De superbeheerder heeft standaard alle rechten en kan niet worden gewijzigd.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Rolnaam',
            'is_enable'  => 'Ingeschakeld of niet',
            'add_time'   => 'Aanmaaktijd',
            'upd_time'   => 'Updatetijd',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'jurisdictie',
        'top_tips_list'                         => [
            '1. Niet professioneel technisch personeel mag de gegevens op deze pagina niet gebruiken. Miswerking kan verwarring veroorzaken in het toestemmingsmenu.',
            '2. Machtigingsmenus zijn onderverdeeld in twee typen: [Gebruik en Operatie]. Het gebruiksmenu wordt over het algemeen weergegeven aan en het bewerkingsmenu moet verborgen zijn.',
            '3. Als het machtigingenmenu niet geordend is, kunt u het gegevensherstel van de gegevenstabel ['.MyConfig('database.connections.mysql.prefix').'power] opnieuw overschrijven.',
            '4. [Super administrator, admin account] heeft standaard alle machtigingen en kan niet worden gewijzigd.',
        ],
        'content_top_tips_list'                 => [
            '"Om [Controllernaam en Methodnaam] in te vullen, is het noodzakelijk om overeenkomstige definities te maken voor de controller en methode."',
            '2. Locatie controller bestand [app/admin/controller]. Deze bewerking wordt alleen gebruikt door ontwikkelaars',
            '"Ofwel de controller naam/methodenaam of het door de gebruiker gedefinieerde url adres moet worden ingevuld."',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Snelle navigatie',
        // 动态表格
        'form_table'                            => [
            'name'         => 'naam',
            'platform'     => 'Platform',
            'images'       => 'Navigatiepictogram',
            'event_type'   => 'Type gebeurtenis',
            'event_value'  => 'Gebeurteniswaarde',
            'is_enable'    => 'Ingeschakeld of niet',
            'sort'         => 'sorteren',
            'add_time'     => 'Aanmaaktijd',
            'upd_time'     => 'Updatetijd',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'regio',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Filterprijs',
        'top_tips_list'                         => [
            'Minimale prijs 0-b maximumprijs 100 is minder dan 100',
            'Minimumprijs 1000,5mm maximumprijs 0 is groter dan 1000',
            'De minimumprijs van 100.De maximumprijs van 500 is groter dan of gelijk aan 100 en minder dan 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Rotatie',
        // 动态表格
        'form_table'                            => [
            'name'         => 'naam',
            'platform'     => 'Platform',
            'images'       => 'foto',
            'event_type'   => 'Type gebeurtenis',
            'event_value'  => 'Gebeurteniswaarde',
            'is_enable'    => 'Ingeschakeld of niet',
            'sort'         => 'sorteren',
            'add_time'     => 'Aanmaaktijd',
            'upd_time'     => 'Updatetijd',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Gebruikersinformatie',
            'user_placeholder'    => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'type'                => 'Operationstype',
            'operation_integral'  => 'Operationele integraal',
            'original_integral'   => 'Oorspronkelijke integraal',
            'new_integral'        => 'Laatste punten',
            'msg'                 => 'Werkingsreden',
            'operation_id'        => 'Operator ID',
            'add_time_time'       => 'Bedieningstijd',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Gebruikersinformatie',
            'user_placeholder'          => 'Voer gebruikersnaam/bijnaam/telefoon/e-mailadres in',
            'type'                      => 'Berichttype',
            'business_type'             => 'Bedrijfstype',
            'title'                     => 'titel',
            'detail'                    => 'details',
            'is_read'                   => 'Lees of niet',
            'user_is_delete_time_text'  => 'Of de gebruiker moet worden verwijderd',
            'add_time_time'             => 'Verzendtijd',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: Niet-ontwikkelaars mogen geen SQL-instructies uitvoeren naar eigen inzicht, omdat de operatie ertoe kan leiden dat de hele systeemdatabase wordt verwijderd.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'Een lijst met uitstekende ShopXO-toepassingen is een verzameling van de meest ervaren, technisch capabele en vertrouwde ShopXO-ontwikkelaars, die een uitgebreide escort bieden voor uw plug-in, stijl en template aanpassing.',
        'to_store_name'                         => 'Ga naar de app store om plug-ins te selecteren>>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Achtergrondbeheersysteem',
        'remove_cache_title'                    => 'Cache wissen',
        'user_status_title'                     => 'Gebruikersstatus',
        'user_status_message'                   => 'Selecteer gebruikersstatus',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Informatie over de configuratie van productparameters',
        'form_goods_params_copy_no_tips'        => 'Plak eerst de configuratie informatie',
        'form_goods_params_copy_error_tips'     => 'Fout in configuratie-indeling',
        'form_goods_params_type_message'        => 'Selecteer een weergavetype productparameter',
        'form_goods_params_params_name'         => 'Parameternaam',
        'form_goods_params_params_message'      => 'Vul de parameternaam in',
        'form_goods_params_value_name'          => 'Parameterwaarde',
        'form_goods_params_value_message'       => 'Vul de parameterwaarde in',
        'form_goods_params_move_type_tips'      => 'Foute configuratie van het bewerkingstype',
        'form_goods_params_move_top_tips'       => 'De top bereikt',
        'form_goods_params_move_bottom_tips'    => 'Bereikte de bodem',
        'form_goods_params_thead_type_title'    => 'Bereik weergeven',
        'form_goods_params_thead_name_title'    => 'Parameternaam',
        'form_goods_params_thead_value_title'   => 'Parameterwaarde',
        'form_goods_params_row_add_title'       => 'Een rij toevoegen',
        'form_goods_params_list_tips'           => [
            '1. Alles (weergegeven onder de basisinformatie en gedetailleerde parameters van het product)',
            '2. Details (alleen weergegeven onder de parameter productdetails)',
            '3. Basisinformatie (alleen weergegeven onder basisinformatie over grondstoffen)',
            '4. Snelkoppeling zal de oorspronkelijke gegevens wissen en de pagina herladen om de oorspronkelijke gegevens te herstellen (alleen effectief na het opslaan van het product)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Systeeminstellingen',
            'item'  => [
                'config_index'                 => 'systeemconfiguratie',
                'config_store'                 => 'Informatie opslaan',
                'config_save'                  => 'Configuratie opslaan',
                'index_storeaccountsbind'      => 'Accountbinding opslaan',
                'index_inspectupgrade'         => 'Systeemupdatecontrole',
                'index_inspectupgradeconfirm'  => 'Bevestiging van systeemupdate',
                'index_stats'                  => 'Statistieken van de startpagina',
                'index_income'                 => 'Statistieken van de startpagina (inkomstenstatistieken)',
            ]
        ],
        'site_index' => [
            'name'  => 'Site-configuratie',
            'item'  => [
                'site_index'                  => 'Site-instellingen',
                'site_save'                   => 'Site-instellingen Bewerken',
                'site_goodssearch'            => 'Site-instellingen Product zoeken',
                'layout_layoutindexhomesave'  => 'Beheer van de lay-out van de startpagina',
                'sms_index'                   => 'SMS-instellingen',
                'sms_save'                    => 'SMS-instellingen bewerken',
                'email_index'                 => 'Postvak-instellingen',
                'email_save'                  => 'Postvak-instellingen/bewerken',
                'email_emailtest'             => 'Test voor postbezorging',
                'seo_index'                   => 'SEO-instellingen',
                'seo_save'                    => 'SEO-instellingen Bewerken',
                'agreement_index'             => 'Protocolbeheer',
                'agreement_save'              => 'Protocol instellingen Bewerken',
            ]
        ],
        'power_index' => [
            'name'  => 'Toestemmingscontrole',
            'item'  => [
                'admin_index'        => 'managers',
                'admin_saveinfo'     => 'Admin Pagina toevoegen/bewerken',
                'admin_save'         => 'Beheerder Toevoegen/Bewerken',
                'admin_delete'       => 'Beheerder Verwijderen',
                'admin_detail'       => 'Beheersgegevens',
                'role_index'         => 'Rolenbeheer',
                'role_saveinfo'      => 'Rolgroep Pagina toevoegen/bewerken',
                'role_save'          => 'Rolgroep toevoegen/bewerken',
                'role_delete'        => 'Rolverwijdering',
                'role_statusupdate'  => 'Rolstatus bijwerken',
                'role_detail'        => 'Roldetails',
                'power_index'        => 'Toewijzing van machtigingen',
                'power_save'         => 'Toestemming toevoegen/bewerken',
                'power_delete'       => 'Verwijdering van machtigingen',
            ]
        ],
        'user_index' => [
            'name'  => 'gebruikersbeheer',
            'item'  => [
                'user_index'            => 'Gebruikerslijst',
                'user_saveinfo'         => 'Gebruiker Pagina bewerken/toevoegen',
                'user_save'             => 'Gebruiker toevoegen/bewerken',
                'user_delete'           => 'Gebruiker verwijderen',
                'user_detail'           => 'Gebruikersgegevens',
                'useraddress_index'     => 'Gebruikersadres',
                'useraddress_saveinfo'  => 'Gebruikersadres bewerken pagina',
                'useraddress_save'      => 'Gebruikersadres bewerken',
                'useraddress_delete'    => 'Verwijdering van gebruikersadressen',
                'useraddress_detail'    => 'Gebruikersadresgegevens',
            ]
        ],
        'goods_index' => [
            'name'  => 'Grondstoffenbeheer',
            'item'  => [
                'goods_index'                       => 'Grondstoffenbeheer',
                'goods_saveinfo'                    => 'Product Pagina toevoegen/bewerken',
                'goods_save'                        => 'Product toevoegen/bewerken',
                'goods_delete'                      => 'Verwijdering van het product',
                'goods_statusupdate'                => 'Update van de productstatus',
                'goods_basetemplate'                => 'Het productbasissjabloon ophalen',
                'goods_detail'                      => 'Productgegevens',
                'goodscategory_index'               => 'Grondstoffenclassificatie',
                'goodscategory_save'                => 'Productcategorie Toevoegen/Bewerken',
                'goodscategory_delete'              => 'Verwijdering van productclassificatie',
                'goodsparamstemplate_index'         => 'Productparameters',
                'goodsparamstemplate_delete'        => 'Verwijdering van productparameters',
                'goodsparamstemplate_statusupdate'  => 'Status van productparameters bijwerken',
                'goodsparamstemplate_saveinfo'      => 'Productparameter Pagina toevoegen/bewerken',
                'goodsparamstemplate_save'          => 'Productparameters toevoegen/bewerken',
                'goodsparamstemplate_detail'        => 'Productparametergegevens',
                'goodsspectemplate_index'           => 'Productspecificaties',
                'goodsspectemplate_delete'          => 'Verwijdering van productspecificaties',
                'goodsspectemplate_statusupdate'    => 'Update van de productspecificatiestatus',
                'goodsspectemplate_saveinfo'        => 'Productspecificatie Pagina toevoegen/bewerken',
                'goodsspectemplate_save'            => 'Productspecificatie Toevoegen/bewerken',
                'goodsspectemplate_detail'          => 'Productspecificaties',
                'goodscomments_detail'              => 'Informatie over productbeoordeling',
                'goodscomments_index'               => 'Productbeoordelingen',
                'goodscomments_reply'               => 'Reactie op productbeoordeling',
                'goodscomments_delete'              => 'Verwijdering van productbeoordeling',
                'goodscomments_statusupdate'        => 'Update van de status van productbeoordeling',
                'goodscomments_saveinfo'            => 'Productcommentaar Pagina toevoegen/bewerken',
                'goodscomments_save'                => 'Productcommentaar toevoegen/bewerken',
                'goodsbrowse_index'                 => 'Product browsen',
                'goodsbrowse_delete'                => 'Product Bladeren Verwijderen',
                'goodsbrowse_detail'                => 'Informatie over het browsen van producten',
                'goodsfavor_index'                  => 'Productverzameling',
                'goodsfavor_delete'                 => 'Productverzameling verwijderen',
                'goodsfavor_detail'                 => 'Details van de productcollectie',
                'goodscart_index'                   => 'Winkelwagen',
                'goodscart_delete'                  => 'Product Cart Verwijderen',
                'goodscart_detail'                  => 'Details van de winkelwagen',
            ]
        ],
        'order_index' => [
            'name'  => 'Orderbeheer',
            'item'  => [
                'order_index'             => 'Orderbeheer',
                'order_delete'            => 'Bestelling verwijderen',
                'order_cancel'            => 'Annulering van de bestelling',
                'order_delivery'          => 'Bestelling verzending',
                'order_collect'           => 'Orderbevestiging',
                'order_pay'               => 'Orderbetaling',
                'order_confirm'           => 'bevestiging van de bestelling',
                'order_detail'            => 'Orderdetails',
                'orderaftersale_index'    => 'Bestelling na verkoop',
                'orderaftersale_delete'   => 'Bestelling na verkoop verwijderen',
                'orderaftersale_cancel'   => 'Bestelling annuleren na verkoop',
                'orderaftersale_audit'    => 'Beoordeling na verkoop van de bestelling',
                'orderaftersale_confirm'  => 'Bevestiging na verkoop',
                'orderaftersale_refuse'   => 'Orderweigering',
                'orderaftersale_detail'   => 'Bestelling After Sales Details',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Website beheer',
            'item'  => [
                'navigation_index'         => 'Navigatiebeheer',
                'navigation_save'          => 'Navigatie Toevoegen/Bewerken',
                'navigation_delete'        => 'Navigatie Verwijderen',
                'navigation_statusupdate'  => 'Navigatiestatus bijwerken',
                'customview_index'         => 'Aangepaste pagina',
                'customview_saveinfo'      => 'Aangepaste pagina Pagina toevoegen/bewerken',
                'customview_save'          => 'Aangepaste pagina toevoegen/bewerken',
                'customview_delete'        => 'Aangepaste pagina verwijderen',
                'customview_statusupdate'  => 'Aangepaste paginastatus bijwerken',
                'customview_detail'        => 'Aangepaste pagina details',
                'link_index'               => 'Vriendelijke koppelingen',
                'link_saveinfo'            => 'Vriendelijke koppeling Pagina toevoegen/bewerken',
                'link_save'                => 'Vriendelijke koppeling toevoegen/bewerken',
                'link_delete'              => 'Vriendelijke link verwijderen',
                'link_statusupdate'        => 'Vriendelijke link status update',
                'link_detail'              => 'Vriendelijke link details',
                'theme_index'              => 'Themabeheer',
                'theme_save'               => 'Themabeheer Toevoegen/bewerken',
                'theme_upload'             => 'Installatie voor het uploaden van themas',
                'theme_delete'             => 'Thema verwijderen',
                'theme_download'           => 'Thema downloaden',
                'slide_index'              => 'Rotatie van de startpagina',
                'slide_saveinfo'           => 'Poll Pagina toevoegen/bewerken',
                'slide_save'               => 'Broadcast Toevoegen/Bewerken',
                'slide_statusupdate'       => 'Update van de peilstatus',
                'slide_delete'             => 'Poll Verwijderen',
                'slide_detail'             => 'Uitzendingsdetails',
                'screeningprice_index'     => 'Filterprijs',
                'screeningprice_save'      => 'Filterprijs Toevoegen/bewerken',
                'screeningprice_delete'    => 'Filterprijs verwijderen',
                'region_index'             => 'Regionaal beheer',
                'region_save'              => 'Regio toevoegen/bewerken',
                'region_delete'            => 'Regio verwijderen',
                'region_codedata'          => 'Gebiednummergegevens verkrijgen',
                'express_index'            => 'Express beheer',
                'express_save'             => 'Express toevoegen/bewerken',
                'express_delete'           => 'Express verwijderen',
                'payment_index'            => 'Betalingsmethode',
                'payment_saveinfo'         => 'Installatie/bewerkingspagina van betalingsmethode',
                'payment_save'             => 'Installatie/bewerking van de betalingsmethode',
                'payment_delete'           => 'Betalingsmethode verwijderen',
                'payment_install'          => 'Installatie van de betalingsmethode',
                'payment_statusupdate'     => 'Update van de betalingsmethode',
                'payment_uninstall'        => 'Verwijdering van de betalingsmethode',
                'payment_upload'           => 'Upload van betalingsmethode',
                'quicknav_index'           => 'Snelle navigatie',
                'quicknav_saveinfo'        => 'Snelle navigatie pagina toevoegen/bewerken',
                'quicknav_save'            => 'Snelle navigatie Toevoegen/Bewerken',
                'quicknav_statusupdate'    => 'Snelle navigatiestatusupdate',
                'quicknav_delete'          => 'Snelle navigatie verwijderen',
                'quicknav_detail'          => 'Snelle navigatiedetails',
                'design_index'             => 'Pagina-ontwerp',
                'design_saveinfo'          => 'Pagina-ontwerp Pagina toevoegen/bewerken',
                'design_save'              => 'Pagina-ontwerp Toevoegen/bewerken',
                'design_statusupdate'      => 'Pagina-ontwerpstatus bijwerken',
                'design_upload'            => 'Pagina-ontwerp importeren',
                'design_download'          => 'Pagina-ontwerp downloaden',
                'design_sync'              => 'Synchronisatie van paginaontwerp Startpagina',
                'design_delete'            => 'Pagina-ontwerp verwijderen',
            ]
        ],
        'brand_index' => [
            'name'  => 'Merkbeheer',
            'item'  => [
                'brand_index'           => 'Merkbeheer',
                'brand_saveinfo'        => 'Merk Pagina toevoegen/bewerken',
                'brand_save'            => 'Merk toevoegen/bewerken',
                'brand_statusupdate'    => 'Update merkstatus',
                'brand_delete'          => 'Merkverwijdering',
                'brand_detail'          => 'Merkdetails',
                'brandcategory_index'   => 'Merkclassificatie',
                'brandcategory_save'    => 'Merkcategorie Toevoegen/Bewerken',
                'brandcategory_delete'  => 'Merkcategorie verwijderen',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Magazijnbeheer',
            'item'  => [
                'warehouse_index'               => 'Magazijnbeheer',
                'warehouse_saveinfo'            => 'Magazijnpagina toevoegen/bewerken',
                'warehouse_save'                => 'Magazijn toevoegen/bewerken',
                'warehouse_delete'              => 'Magazijn verwijderen',
                'warehouse_statusupdate'        => 'Update van de pakhuisstatus',
                'warehouse_detail'              => 'Magazijngegevens',
                'warehousegoods_index'          => 'Warenhuishoudbeheer',
                'warehousegoods_detail'         => 'Details van pakhuis-item',
                'warehousegoods_delete'         => 'Verwijdering van pakhuisitems',
                'warehousegoods_statusupdate'   => 'Update van de status van magazijnitems',
                'warehousegoods_goodssearch'    => 'Zoeken naar pakhuisitems',
                'warehousegoods_goodsadd'       => 'Magazijnitem zoeken Toevoegen',
                'warehousegoods_goodsdel'       => 'Magazijnitem zoeken Verwijderen',
                'warehousegoods_inventoryinfo'  => 'Bewerken van voorraad magazijnitems',
                'warehousegoods_inventorysave'  => 'Inventaris magazijnitems bewerken',
            ]
        ],
        'app_index' => [
            'name'  => 'Telefoonbeheer',
            'item'  => [
                'appconfig_index'            => 'Basisconfiguratie',
                'appconfig_save'             => 'Basisconfiguratie opslaan',
                'apphomenav_index'           => 'Navigatie op de startpagina',
                'apphomenav_saveinfo'        => 'Home Navigatie Pagina toevoegen/bewerken',
                'apphomenav_save'            => 'Home navigatie toevoegen/bewerken',
                'apphomenav_statusupdate'    => 'Update van de navigatiestatus van de startpagina',
                'apphomenav_delete'          => 'Verwijdering van startnavigatie',
                'apphomenav_detail'          => 'Navigatiegegevens van de startpagina',
                'appcenternav_index'         => 'Navigatie in gebruikerscentrum',
                'appcenternav_saveinfo'      => 'Navigatie in gebruikerscentrum Pagina toevoegen/bewerken',
                'appcenternav_save'          => 'Navigatie in gebruikerscentrum Toevoegen/Bewerken',
                'appcenternav_statusupdate'  => 'Update van de navigatiestatus van het gebruikerscentrum',
                'appcenternav_delete'        => 'Navigatie in gebruikerscentrum verwijderen',
                'appcenternav_detail'        => 'Navigatiegegevens voor gebruikerscentrum',
                'appmini_index'              => 'Appletlijst',
                'appmini_created'            => 'Generatie van kleine pakketten',
                'appmini_delete'             => 'Appletpakket verwijderen',
                'appmini_themeupload'        => 'Appletthema uploaden',
                'appmini_themesave'          => 'Appletthema switch',
                'appmini_themedelete'        => 'Appletthema switch',
                'appmini_themedownload'      => 'Appletthema downloaden',
                'appmini_config'             => 'Appletconfiguratie',
                'appmini_save'               => 'Appletconfiguratie opslaan',
            ]
        ],
        'article_index' => [
            'name'  => 'Artikelbeheer',
            'item'  => [
                'article_index'           => 'Artikelbeheer',
                'article_saveinfo'        => 'Artikel Pagina toevoegen/bewerken',
                'article_save'            => 'Artikel toevoegen/bewerken',
                'article_delete'          => 'Artikel schrappen',
                'article_statusupdate'    => 'Bijwerking van de artikelstatus',
                'article_detail'          => 'Artikeldetails',
                'articlecategory_index'   => 'Articleclassificatie',
                'articlecategory_save'    => 'Artikelcategorie Bewerken/Toevoegen',
                'articlecategory_delete'  => 'Artikelcategorie Verwijderen',
            ]
        ],
        'data_index' => [
            'name'  => 'gegevensbeheer',
            'item'  => [
                'answer_index'          => 'Q&A-bericht',
                'answer_reply'          => 'Antwoord op vragen en antwoorden',
                'answer_delete'         => 'Q&A bericht verwijderen',
                'answer_statusupdate'   => 'Statusupdate van Q&A-berichten',
                'answer_saveinfo'       => 'Q&A Pagina toevoegen/bewerken',
                'answer_save'           => 'Q&A Toevoegen/Bewerken',
                'answer_detail'         => 'Details van het Q&A-bericht',
                'message_index'         => 'Berichtbeheer',
                'message_delete'        => 'Bericht verwijderen',
                'message_detail'        => 'Berichtdetails',
                'paylog_index'          => 'Betalingslogistratie',
                'paylog_detail'         => 'Betalingslooggegevens',
                'paylog_close'          => 'Betalingslog gesloten',
                'payrequestlog_index'   => 'Lijst met betalingsaanvragen',
                'payrequestlog_detail'  => 'Logboekgegevens voor betalingsaanvragen',
                'refundlog_index'       => 'Teruggavelogboek',
                'refundlog_detail'      => 'Details van het terugbetalingslogboek',
                'integrallog_index'     => 'Puntenlogboek',
                'integrallog_detail'    => 'Puntenloggegevens',
            ]
        ],
        'store_index' => [
            'name'  => 'Toepassingscentrum',
            'item'  => [
                'pluginsadmin_index'         => 'Applicatiebeheer',
                'plugins_index'              => 'Applicatiebeheer',
                'pluginsadmin_saveinfo'      => 'Pagina toevoegen/bewerken van apps',
                'pluginsadmin_save'          => 'App toevoegen/bewerken',
                'pluginsadmin_statusupdate'  => 'Update van de toepassingsstatus',
                'pluginsadmin_delete'        => 'App verwijderen',
                'pluginsadmin_upload'        => 'App uploaden',
                'pluginsadmin_download'      => 'App-verpakking',
                'pluginsadmin_install'       => 'Installatie van toepassingen',
                'pluginsadmin_uninstall'     => 'Apps verwijderen',
                'pluginsadmin_sortsave'      => 'Sorteren opslaan toepassen',
                'store_index'                => 'App store',
                'packageinstall_index'       => 'Pakketinstallatiepagina',
                'packageinstall_install'     => 'Installatie van softwarepakketten',
                'packageupgrade_upgrade'     => 'Softwarepakket update',
            ]
        ],
        'tool_index' => [
            'name'  => 'gereedschap',
                'item'                  => [
                'cache_index'           => 'Cachebeheer',
                'cache_statusupdate'    => 'Site cache-update',
                'cache_templateupdate'  => 'Sjablooncache-update',
                'cache_moduleupdate'    => 'Module cache bijwerken',
                'cache_logdelete'       => 'Log verwijderen',
                'sqlconsole_index'      => 'SQL Console',
                'sqlconsole_implement'  => 'SQL-uitvoering',
            ]
        ],
    ],
];
?>