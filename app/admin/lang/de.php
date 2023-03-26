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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'Entwicklung des Auftragstransaktionsbetrags',
            'order_trading_trend_name'          => 'Trend des Orderhandels',
            'goods_hot_name'                    => 'Heiße Verkaufswaren',
            'goods_hot_tips'                    => 'Nur die ersten 30-Artikel anzeigen',
            'payment_name'                      => 'Zahlungsmethode',
            'order_region_name'                 => 'Reihenfolge geografische Verteilung',
            'order_region_tips'                 => 'Nur 30-Stück Daten anzeigen',
            'upgrade_check_loading_tips'        => 'Erhalten Sie die neuesten Inhalte, bitte warten Sie',
            'upgrade_version_name'              => 'Aktualisierte Version:',
            'upgrade_date_name'                 => 'Update Datum:',
        ],
        // 页面基础
        'base_update_button_title'              => 'Jetzt aktualisieren',
        'base_item_base_stats_title'            => 'Statistik der Einkaufszentren',
        'base_item_base_stats_tips'             => 'Zeitfilterung gilt nur für Summen',
        'base_item_user_title'                  => 'Benutzer insgesamt',
        'base_item_order_number_title'          => 'Gesamtbetrag der Bestellung',
        'base_item_order_complete_number_title' => 'Transaktionsvolumen insgesamt',
        'base_item_order_complete_title'        => 'Auftragsbetrag',
        'base_item_last_month_title'            => 'Letzten Monat',
        'base_item_same_month_title'            => 'im selben Monat',
        'base_item_yesterday_title'             => 'gestern',
        'base_item_today_title'                 => 'heute',
        'base_item_order_profit_title'          => 'Entwicklung des Auftragstransaktionsbetrags',
        'base_item_order_trading_title'         => 'Trend des Orderhandels',
        'base_item_order_tips'                  => 'Alle Bestellungen',
        'base_item_hot_sales_goods_title'       => 'Heiße Verkaufswaren',
        'base_item_hot_sales_goods_tips'        => 'Bestellungen ausschließen, die storniert und geschlossen wurden',
        'base_item_payment_type_title'          => 'Zahlungsmethode',
        'base_item_map_whole_country_title'     => 'Reihenfolge geografische Verteilung',
        'base_item_map_whole_country_tips'      => 'Bestellungen, die storniert wurden, und Standardmaße (Provinzen) ausschließen',
        'base_item_map_whole_country_province'  => 'Provinz',
        'base_item_map_whole_country_city'      => 'Stadt',
        'base_item_map_whole_country_county'    => 'Kreis/Kreis',
        'system_info_title'                     => 'Systeminformationen',
        'system_ver_title'                      => 'Softwareversion',
        'system_os_ver_title'                   => 'Betriebssystem',
        'system_php_ver_title'                  => 'PHP-Version',
        'system_mysql_ver_title'                => 'MySQL Version',
        'system_server_ver_title'               => 'Serverseitige Informationen',
        'system_host_title'                     => 'Aktueller Domainname',
        'development_team_title'                => 'das Entwicklungsteam',
        'development_team_website_title'        => 'Offizielle Website des Unternehmens',
        'development_team_website_value'        => 'Shanghai Zongzhige Technology Co., Ltd.',
        'development_team_support_title'        => 'technische Unterstützung',
        'development_team_support_value'        => 'ShopXO Enterprise E-Commerce System Provider',
        'development_team_ask_title'            => 'Kommunikationsfragen',
        'development_team_ask_value'            => 'Fragen zur Kommunikation mit ShopXO',
        'development_team_agreement_title'      => 'Open Source Protokoll',
        'development_team_agreement_value'      => 'Open-Source-Vereinbarungen anzeigen',
        'development_team_update_log_title'     => 'Protokoll aktualisieren',
        'development_team_update_log_value'     => 'Aktualisierungsprotokoll anzeigen',
        'development_team_members_title'        => 'F&E-Mitglieder',
        'development_team_members_value'        => [
            ['name' => 'Bruder Gong', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'Benutzer',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'Benutzer-ID',
            'number_code'           => 'Mitgliedscode',
            'system_type'           => 'Systemtyp',
            'platform'              => 'Plattform',
            'avatar'                => 'Kopfportrait',
            'username'              => 'Benutzername',
            'nickname'              => 'Spitzname',
            'mobile'                => 'Handy',
            'email'                 => 'Postfach',
            'gender_name'           => 'Geschlecht',
            'status_name'           => 'Zustand',
            'province'              => 'Provinz',
            'city'                  => 'Stadt',
            'county'                => 'Kreis/Kreis',
            'address'               => 'Detaillierte Adresse',
            'birthday'              => 'Geburtstag',
            'integral'              => 'Verfügbare Punkte',
            'locking_integral'      => 'Gesperrtes Integral',
            'referrer'              => 'Benutzer einladen',
            'referrer_placeholder'  => 'Bitte geben Sie den Einladungsnamen/Nickname/Telefon/E-Mail ein',
            'add_time'              => 'Anmeldezeit',
            'upd_time'              => 'Aktualisierungszeit',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Benutzeradresse',
        // 详情
        'detail_user_address_idcard_name'       => 'vollständiger Name',
        'detail_user_address_idcard_number'     => 'Zahl',
        'detail_user_address_idcard_pic'        => 'Foto',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Benutzerinformationen',
            'user_placeholder'  => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'alias'             => 'Alias',
            'name'              => 'Kontakte',
            'tel'               => 'Kontaktnummer',
            'province_name'     => 'Provinz',
            'city_name'         => 'Stadt',
            'county_name'       => 'Kreis/Kreis',
            'address'           => 'Detaillierte Adresse',
            'position'          => 'Längen- und Breitengrad',
            'idcard_info'       => 'Ausweisinformationen',
            'is_default'        => 'Standard oder nicht',
            'add_time'          => 'Erstellungszeit',
            'upd_time'          => 'Aktualisierungszeit',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Speichern wird nach dem Entfernen wirksam. Sind Sie sicher, dass Sie fortfahren?',
            'address_no_data'                   => 'Adressdaten sind leer',
            'address_not_exist'                 => 'Adresse existiert nicht',
            'address_logo_message'              => 'Bitte laden Sie ein Logo-Bild hoch',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Grundkonfiguration', 'type' => 'base'],
            ['name' => 'Site-Einstellungen', 'type' => 'siteset'],
            ['name' => 'Standorttyp', 'type' => 'sitetype'],
            ['name' => 'Benutzerregistrierung', 'type' => 'register'],
            ['name' => 'Benutzeranmeldung', 'type' => 'login'],
            ['name' => 'Passwortwiederherstellung', 'type' => 'forgetpwd'],
            ['name' => 'Prüfcode', 'type' => 'verify'],
            ['name' => 'After Sales bestellen', 'type' => 'orderaftersale'],
            ['name' => 'Gehäuse', 'type' => 'attachment'],
            ['name' => 'Cache', 'type' => 'cache'],
            ['name' => 'Erweiterungen', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'Startseite', 'type' => 'index'],
            ['name' => 'Ware', 'type' => 'goods'],
            ['name' => 'Suche', 'type' => 'search'],
            ['name' => 'Bestellung', 'type' => 'order'],
            ['name' => 'Rabatt', 'type' => 'discount'],
            ['name' => 'verlängern', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Standortstatus',
        'base_item_site_domain_title'           => 'Site Domain Name Adresse',
        'base_item_site_filing_title'           => 'Einreichung von Informationen',
        'base_item_site_other_title'            => 'andere',
        'base_item_session_cache_title'         => 'Sitzungs-Cache-Konfiguration',
        'base_item_data_cache_title'            => 'Konfiguration des Datencaches',
        'base_item_redis_cache_title'           => 'Redis-Cache-Konfiguration',
        'base_item_crontab_config_title'        => 'Konfiguration des Timing-Skripts',
        'base_item_quick_nav_title'             => 'Schnellnavigation',
        'base_item_user_base_title'             => 'Benutzerbasis',
        'base_item_user_address_title'          => 'Benutzeradresse',
        'base_item_multilingual_title'          => 'Mehrsprachig',
        'base_item_site_auto_mode_title'        => 'Automatischer Modus',
        'base_item_site_manual_mode_title'      => 'Manueller Modus',
        'base_item_default_payment_title'       => 'Standardzahlungsmethode',
        'base_item_display_type_title'          => 'Anzeigetyp',
        'base_item_self_extraction_title'       => 'Selbstansaugend',
        'base_item_fictitious_title'            => 'Virtueller Verkauf',
        'choice_upload_logo_title'              => 'Wählen Sie ein Logo',
        'add_goods_title'                       => 'Produktzusatz',
        'add_self_extractio_address_title'      => 'Adresse hinzufügen',
        'site_domain_tips_list'                 => [
            '1. Wenn der Websitedomänenname nicht festgelegt ist, werden der aktuelle Websitedomänenname, -domänenname und -adresse verwendet[ '.__MY_DOMAIN__.' ]',
            '2. Wenn Anhang und statische Adresse nicht festgelegt sind, wird die statische Domänenname-Adresse der aktuellen Website verwendet[ '.__MY_PUBLIC_URL__.' ]',
            '3. Wenn public auf der Serverseite nicht als Stammverzeichnis festgelegt ist, muss der Konfiguration von [attachment cdn domain name, css/js statische Datei cdn domain name] gefolgt werden, wie z.B.:'.__MY_PUBLIC_URL__.'public/',
            '"Wenn ein Projekt im Befehlszeilenmodus ausgeführt wird, muss die Adresse der Region konfiguriert werden, andernfalls fehlen bei einigen Adressen im Projekt Domänennamen-Informationen."',
            '5. Nicht zufällig konfigurieren. Eine falsche Adresse kann dazu führen, dass die Website unzugänglich ist (die Adresskonfiguration beginnt mit http). Wenn Ihre eigene Website mit https konfiguriert ist, beginnt sie mit https',
        ],
        'site_cache_tips_list'                  => [
            '1. Standard-Dateicaching und Redis-Caching PHP erfordern, dass die Redis-Erweiterung zuerst installiert wird',
            '2. Bitte achten Sie auf die Stabilität des Redis-Dienstes (nachdem Sie den Cache für eine Sitzung verwendet haben, kann der Unstable-Dienst dazu führen, dass sich der Hintergrund nicht anmelden kann)',
            '"Wenn Sie auf eine Redis-Dienstausnahme stoßen, können Sie sich nicht im Verwaltungshintergrund anmelden und die Datei [session.php, cache.php] im Verzeichnis [config] der Konfigurationsdatei ändern."',
        ],
        'goods_tips_list'                       => [
            '1. Die Standardanzeige auf der WEB-Seite ist Level 3, mit einem Minimum von Level 1 und einem Maximum von Level 3 (wenn auf Level 0 gesetzt, ist die Standardanzeige Level 3)',
            '2. Die Standardanzeige auf dem mobilen Terminal ist Niveau 0 (Produktlistenmodus), Mindestniveau 0 und Maximalpegel 3 (1-3 sind klassifizierte Anzeigemodi)',
            '3. Verschiedene Ebenen und Frontend-Kategorieseitenstile können auch unterschiedlich sein',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Konfigurieren Sie die maximale Anzahl von Produkten, die auf jeder Etage angezeigt werden',
            '2. Es wird nicht empfohlen, die Menge zu groß zu ändern, was dazu führt, dass der leere Bereich auf der linken Seite des PC-Terminals zu groß ist',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'Umfassend: Hitze ->Verkauf ->Neueste absteigend',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. Sie können auf den Produkttitel klicken, um ihn zu ziehen und zu sortieren und in der Reihenfolge anzuzeigen',
            '2. Es wird nicht empfohlen, viele Produkte hinzuzufügen, was dazu führen kann, dass der leere Bereich auf der linken Seite des PCs zu groß ist',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. Standardmäßig wird [Benutzername, Mobiltelefon, E-Mail] als eindeutiger Benutzer verwendet.',
            '2. Wenn aktiviert, fügen Sie die [System ID] hinzu und machen Sie sie einzigartig für den Benutzer',
        ],
        'extends_crontab_tips'                  => 'Es wird empfohlen, die Skriptadresse zur Zeitplanung für Linux-Aufgaben hinzuzufügen. (Das Ergebnis ist SUCS: 0, FAIL: 0, gefolgt vom Doppelpunkt, ist die Anzahl der verarbeiteten Datenstücke. SUCS erfolgreich, FALI fehlgeschlagen.)',
        'left_images_random_tips'               => 'Das linke Bild kann bis zu drei Bilder hochladen, von denen eines jedes Mal zufällig angezeigt werden kann',
        'background_color_tips'                 => 'Anpassbares Hintergrundbild, Standardhintergrund grau',
        'site_setup_layout_tips'                => 'Im Drag-and-Drop-Modus müssen Sie die Homepage-Design-Seite selbst aufrufen. Bitte speichern Sie die gewählte Konfiguration, bevor Sie fortfahren.',
        'site_setup_layout_button_name'         => 'Gehen Sie zur Design-Seite>>',
        'site_setup_goods_category_tips'        => 'Weitere Bodendisplays finden Sie unter/Produktmanagement ->Produktklassifizierung, Primäre Klassifikationseinstellungen Startseite Empfehlungen',
        'site_setup_goods_category_no_data_tips'=> 'Keine Daten verfügbar, bitte gehen Sie zu/Produktmanagement ->Produktklassifizierung, Primäre Klassifikationseinstellungen Startseite für Empfehlungen',
        'site_setup_order_default_payment_tips' => 'Sie können Standardzahlungsmethoden für verschiedene Plattformen festlegen. Bitte installieren Sie zuerst das Payment Plug-in in [Website Management ->Zahlungsmethoden], um es zu aktivieren und den Benutzern zur Verfügung zu stellen.',
        'site_setup_choice_payment_message'     => 'Bitte wählen Sie {:name} Standard Zahlungsmethode',
        'sitetype_top_tips_list'                => [
            '1. Express-Lieferung und herkömmliche E-Commerce-Prozesse, bei denen Benutzer eine Lieferadresse auswählen, um eine Bestellung für die Zahlung aufzugeben ->Händler Versand ->Empfang bestätigen ->Auftragsabschluss',
            '2. Anzeigetyp, Produkt nur Anzeige, Beratung kann initiiert werden (Aufträge können nicht platziert werden)',
            '3. Wählen Sie die Selbstabholungsadresse, wenn Sie eine Bestellung aufgeben, und der Benutzer gibt eine Bestellung zur Zahlung auf ->Lieferung bestätigen ->Auftragsabschluss',
            '4. Virtueller Verkauf, regelmäßige E-Commerce-Prozesse, Benutzeraufträge für Zahlung ->automatischer Versand ->Bestätigung der Abholung ->Auftragsabschluss',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'Empfohlen 300 bis 300px',
        'form_take_address_alias'               => 'Alias',
        'form_take_address_alias_message'       => 'Alias Format bis zu 16 Zeichen',
        'form_take_address_name'                => 'Kontakte',
        'form_take_address_name_message'        => 'Das Kontaktformat liegt zwischen 2- und 16-Zeichen',
        'form_take_address_tel'                 => 'Kontaktnummer',
        'form_take_address_tel_message'         => 'Bitte füllen Sie die Kontaktnummer aus',
        'form_take_address_address'             => 'Detaillierte Adresse',
        'form_take_address_address_message'     => 'Das detaillierte Adressformat liegt zwischen 1 und 80 Zeichen',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Hintergrund Anmeldung',
        'admin_login_info_bg_images_list_tips'  => [
            'Das Hintergrundbild befindet sich im Verzeichnis [public/static/admin/default/images/login].',
            'Benennungsregeln für Hintergrundbilder (1-50), z. B. 1.jpg',
        ],
        'map_type_tips'                         => 'Aufgrund der unterschiedlichen Kartenstandards der einzelnen Unternehmen wechseln Sie bitte nicht zufällig Karten, was zu ungenauen Kartenkoordinatenbezeichnungen führen kann.',
        'apply_map_baidu_name'                  => 'Bitte bewerben Sie sich bei Baidu Map Open Platform',
        'apply_map_amap_name'                   => 'Bitte bewerben Sie sich bei der Gaode Map Open Platform',
        'apply_map_tencent_name'                => 'Bitte bewerben Sie sich bei Tencent Map Open Platform',
        'apply_map_tianditu_name'               => 'Bitte bewerben Sie sich bei Tiantu Open Platform',
        'cookie_domain_list_tips'               => [
            '"Wenn der Standard leer ist, gilt er nur für den aktuell aufgerufenen Domainnamen."',
            '2. Wenn Sie einen sekundären Domainnamen benötigen, um Cookies zu teilen, geben Sie den Top-Level-Domainnamen ein, z. B. baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'Marke',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'Name',
            'describe'             => 'Beschreibung',
            'logo'                 => 'LOGO',
            'url'                  => 'Offizielle Website Adresse',
            'brand_category_text'  => 'Markenklassifizierung',
            'is_enable'            => 'Aktiviert oder nicht',
            'sort'                 => 'sortieren',
            'add_time'             => 'Erstellungszeit',
            'upd_time'             => 'Aktualisierungszeit',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Markenklassifizierung',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Artikel',
        'detail_content_title'                  => 'Details',
        'detail_images_title'                   => 'Detailbild',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'Titel',
            'jump_url'               => 'URL-Adresse springen',
            'article_category_name'  => 'Klassifizierung',
            'is_enable'              => 'Aktiviert oder nicht',
            'is_home_recommended'    => 'Startseite Empfehlung',
            'images_count'           => 'Anzahl der Bilder',
            'access_count'           => 'Anzahl der Besuche',
            'add_time'               => 'Erstellungszeit',
            'upd_time'               => 'Aktualisierungszeit',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Artikelklassifizierung',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Benutzerdefinierte Seite',
        'detail_content_title'                  => 'Details',
        'detail_images_title'                   => 'Detailbild',
        'save_view_tips'                        => 'Bitte speichern Sie vor der Vorschau des Effekts',
        // 动态表格
        'form_table'                            => [
            'info'            => 'Titel',
            'is_enable'       => 'Aktiviert oder nicht',
            'is_header'       => 'Kopf oder nicht',
            'is_footer'       => 'Schwanz',
            'is_full_screen'  => 'Ob der Bildschirm voll ist',
            'images_count'    => 'Anzahl der Bilder',
            'access_count'    => 'Anzahl der Besuche',
            'add_time'        => 'Erstellungszeit',
            'upd_time'        => 'Aktualisierungszeit',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Weitere Designvorlagen herunterladen',
        'upload_list_tips'                      => [
            '1. Wählen Sie das heruntergeladene Seitendesign Zip-Paket aus',
            '2. Beim Importieren wird automatisch ein neues Stück Daten hinzugefügt',
        ],
        'operate_sync_tips'                     => 'Die Datensynchronisierung mit der Visualisierung des Startbilds und nachfolgende Änderungen an den Daten werden nicht beeinträchtigt, löschen Sie jedoch keine zugehörigen Anhänge',
        // 动态表格
        'form_table'                            => [
            'id'                => 'Daten-ID',
            'info'              => 'Grundlegende Informationen',
            'info_placeholder'  => 'Bitte geben Sie einen Namen ein',
            'access_count'      => 'Anzahl der Besuche',
            'is_enable'         => 'Aktiviert oder nicht',
            'is_header'         => 'Einschließlich Kopf',
            'is_footer'         => 'Einschließlich Schwanz',
            'seo_title'         => 'SEO Titel',
            'seo_keywords'      => 'SEO Keyword',
            'seo_desc'          => 'SEO Beschreibung',
            'add_time'          => 'Erstellungszeit',
            'upd_time'          => 'Aktualisierungszeit',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Fragen und Antworten',
        'user_info_title'                       => 'Benutzerinformationen',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Benutzerinformationen',
            'user_placeholder'  => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'name'              => 'Kontakte',
            'tel'               => 'Kontaktnummer',
            'content'           => 'Inhalt',
            'reply'             => 'Antwortinhalt',
            'is_show'           => 'Ob angezeigt werden soll',
            'is_reply'          => 'Antworten oder nicht',
            'reply_time_time'   => 'Reaktionszeit',
            'access_count'      => 'Anzahl der Besuche',
            'add_time_time'     => 'Erstellungszeit',
            'upd_time_time'     => 'Aktualisierungszeit',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Lager',
        'top_tips_list'                         => [
            '"Je höher der Gewichtswert, desto höher das Gewicht. Inventar wird nach Gewicht abgezogen."',
            '2. Das Lager kann nur soft-deleted werden, steht nach dem Löschen nicht mehr zur Verfügung und nur die Daten in der Datenbank können beibehalten werden. Sie können die zugehörigen Produktdaten selbst löschen',
            '3. Lagerdeaktivierung und -löschung sowie zugehöriger Warenbestand werden sofort freigegeben',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Name/Alias',
            'level'          => 'Gewicht',
            'is_enable'      => 'Aktiviert oder nicht',
            'contacts_name'  => 'Kontakte',
            'contacts_tel'   => 'Kontaktnummer',
            'province_name'  => 'Provinz',
            'city_name'      => 'Stadt',
            'county_name'    => 'Kreis/Kreis',
            'address'        => 'Detaillierte Adresse',
            'position'       => 'Längen- und Breitengrad',
            'add_time'       => 'Erstellungszeit',
            'upd_time'       => 'Aktualisierungszeit',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Bitte wählen Sie ein Lager',
        ],
        // 基础
        'add_goods_title'                       => 'Produktzusatz',
        'no_spec_data_tips'                     => 'Keine Spezifikationsdaten',
        'batch_setup_inventory_placeholder'     => 'Batch-Set-Werte',
        'base_spec_inventory_title'             => 'Spezifikation Inventar',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Grundlegende Informationen',
            'goods_placeholder'  => 'Bitte geben Sie den Produktnamen/das Modell ein',
            'warehouse_name'     => 'Lager',
            'is_enable'          => 'Aktiviert oder nicht',
            'inventory'          => 'Gesamtbestand',
            'spec_inventory'     => 'Spezifikation Inventar',
            'add_time'           => 'Erstellungszeit',
            'upd_time'           => 'Aktualisierungszeit',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'Die Administratorinformationen existieren nicht',
        // 列表
        'top_tips_list'                         => [
            '1. Das Admin-Konto verfügt standardmäßig über alle Berechtigungen und kann nicht geändert werden.',
            '2. Das Admin-Konto kann nicht geändert werden, aber es kann in der Datentabelle geändert werden ('. MyConfig('database.connections.mysql.prefix'). 'admin) Feld Benutzername',
        ],
        'base_nav_title'                        => 'Administratoren',
        // 登录
        'login_type_username_title'             => 'Passwort für das Konto',
        'login_type_mobile_title'               => 'Mobile Verifizierungscode',
        'login_type_email_title'                => 'E-Mail-Verifizierungscode',
        'login_close_tips'                      => 'Vorübergehend gesperrte Anmeldung',
        // 忘记密码
        'form_forget_password_name'             => 'Passwort vergessen?',
        'form_forget_password_tips'             => 'Bitte kontaktieren Sie Ihren Administrator, um Ihr Passwort zurückzusetzen',
        // 动态表格
        'form_table'                            => [
            'username'              => 'Administratoren',
            'status'                => 'Zustand',
            'gender'                => 'Geschlecht',
            'mobile'                => 'Handy',
            'email'                 => 'Postfach',
            'role_name'             => 'Rollengruppen',
            'login_total'           => 'Anzahl der Anmeldungen',
            'login_time'            => 'Letzte Anmeldezeit',
            'add_time'              => 'Erstellungszeit',
            'upd_time'              => 'Aktualisierungszeit',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Benutzerregistrierungsvereinbarung', 'type' => 'register'],
            ['name' => 'Datenschutzerklärung der Nutzer', 'type' => 'privacy'],
            ['name' => 'Vertrag zur Kontoauflösung', 'type' => 'logout']
        ],
        'top_tips'          => 'Add parameter is to front-end access protocol address_ Inhalt=1 zeigt nur Protokollinhalte an',
        'view_detail_name'                      => 'Details anzeigen',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Grundkonfiguration', 'type' => 'index'],
            ['name' => 'APP/Applet', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Aktuelles Thema', 'type' => 'index'],
            ['name' => 'Theme-Installation', 'type' => 'upload'],
            ['name' => 'Quellpaket herunterladen', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Weitere Theme-Downloads',
        'nav_theme_download_name'               => 'Sehen Sie sich das Applet Packaging Tutorial an',
        'nav_theme_download_tips'               => 'Das Handy-Theme wird mit uniapp entwickelt (unterstützt Multi-Terminal applet+H5), und die App befindet sich auch in der Notfallanpassung.',
        'form_alipay_extend_title'              => 'Konfiguration des Kundenservice',
        'form_alipay_extend_tips'               => 'PS: Wenn es in [APP/applet] aktiviert ist (um Online-Kundenservice zu aktivieren), muss die folgende Konfiguration mit [Enterprise Code] und [Chat Window Code] ausgefüllt werden.',
        'form_theme_upload_tips'                => 'Laden Sie ein komprimiertes Installationspaket hoch',
        'list_no_data_tips'                     => 'Keine verwandten Themenpakete',
        'list_author_title'                     => 'Autor',
        'list_version_title'                    => 'Anwendbare Fassung',
        'package_generate_tips'                 => 'Die Generierungszeit ist relativ lang, bitte schließen Sie das Browserfenster nicht!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Paketname',
            'size'  => 'Größe',
            'url'   => 'Download-Adresse',
            'time'  => 'Erstellungszeit',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'SMS-Einstellungen', 'type' => 'index'],
            ['name' => 'Nachrichtenvorlage', 'type' => 'message'],
        ],
        'top_tips'                              => 'Alibaba Cloud SMS Management Adresse',
        'top_to_aliyun_tips'                    => 'Klicken Sie hier, um SMS von AliCloud zu kaufen',
        'base_item_admin_title'                 => 'Backstage',
        'base_item_index_title'                 => 'Frontend',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Postfacheinstellungen', 'type' => 'index'],
            ['name' => 'Nachrichtenvorlage', 'type' => 'message'],
        ],
        'top_tips'                              => 'Aufgrund einiger Unterschiede und Konfigurationen zwischen verschiedenen Postfachplattformen hat das spezifische Konfigurations-Tutorial für die Postfachplattform Vorrang',
        // 基础
        'test_title'                            => 'Prüfung',
        'test_content'                          => 'E-Mail-Konfigurator Testinhalt senden',
        'base_item_admin_title'                 => 'Backstage',
        'base_item_index_title'                 => 'Frontend',
        // 表单
        'form_item_test'                        => 'Testen Sie die erhaltene E-Mail-Adresse',
        'form_item_test_tips'                   => 'Bitte speichern Sie die Konfiguration vor dem Testen',
        'form_item_test_button_title'           => 'Prüfung',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Entsprechende pseudostatische Regeln entsprechend unterschiedlicher Serverumgebungen konfigurieren [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'Ware',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Grundlegende Informationen', 'type'=>'base'],
            'specifications'  => ['name' => 'Produktspezifikationen', 'type'=>'specifications'],
            'parameters'      => ['name' => 'Produktparameter', 'type'=>'parameters'],
            'photo'           => ['name' => 'Produktalbum', 'type'=>'photo'],
            'video'           => ['name' => 'Produkt Video', 'type'=>'video'],
            'app'             => ['name' => 'Gerätestatus', 'type'=>'app'],
            'web'             => ['name' => 'Computer Details', 'type'=>'web'],
            'fictitious'      => ['name' => 'Virtuelle Informationen', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Erweiterte Daten', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO Informationen', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Produkt-ID',
            'info'                    => 'Produktinformationen',
            'category_text'           => 'Warenklassifizierung',
            'brand_name'              => 'Marke',
            'price'                   => 'Verkaufspreis (RMB)',
            'original_price'          => 'Originalpreis (Yuan)',
            'inventory'               => 'Gesamtbestand',
            'is_shelves'              => 'Obere und untere Regale',
            'is_deduction_inventory'  => 'Lagerabzug',
            'site_type'               => 'Produkttyp',
            'model'                   => 'Produktmodell',
            'place_origin_name'       => 'Herstellungsort',
            'give_integral'           => 'Kaufbonus Punkte Prozentsatz',
            'buy_min_number'          => 'Mindestbestellmenge pro Zeit',
            'buy_max_number'          => 'Maximale Einzelabnahmemenge',
            'sales_count'             => 'Verkaufsvolumen',
            'access_count'            => 'Anzahl der Besuche',
            'add_time'                => 'Erstellungszeit',
            'upd_time'                => 'Aktualisierungszeit',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Warenklassifizierung',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Produktbewertungen',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Benutzerinformationen',
            'user_placeholder'   => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'goods'              => 'Grundlegende Informationen',
            'goods_placeholder'  => 'Bitte geben Sie den Produktnamen/das Modell ein',
            'business_type'      => 'Unternehmenstyp',
            'content'            => 'Kommentar-Inhalt',
            'images'             => 'Kommentar Bild',
            'rating'             => 'Punktzahl',
            'reply'              => 'Antwortinhalt',
            'is_show'            => 'Ob angezeigt werden soll',
            'is_anonymous'       => 'Anonym oder nicht',
            'is_reply'           => 'Antworten oder nicht',
            'reply_time_time'    => 'Reaktionszeit',
            'add_time_time'      => 'Erstellungszeit',
            'upd_time_time'      => 'Aktualisierungszeit',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Produktparameter',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Warenklassifizierung',
            'name'          => 'Name',
            'is_enable'     => 'Aktiviert oder nicht',
            'config_count'  => 'Anzahl der Parameter',
            'add_time'      => 'Erstellungszeit',
            'upd_time'      => 'Aktualisierungszeit',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Warenklassifizierung',
            'name'         => 'Name',
            'is_enable'    => 'Aktiviert oder nicht',
            'content'      => 'Spezifikationswert',
            'add_time'     => 'Erstellungszeit',
            'upd_time'     => 'Aktualisierungszeit',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Benutzerinformationen',
            'user_placeholder'   => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'goods'              => 'Produktinformationen',
            'goods_placeholder'  => 'Bitte geben Sie Produktname/kurze Beschreibung/SEO-Informationen ein',
            'price'              => 'Verkaufspreis (RMB)',
            'original_price'     => 'Originalpreis (Yuan)',
            'add_time'           => 'Erstellungszeit',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Benutzerinformationen',
            'user_placeholder'   => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'goods'              => 'Produktinformationen',
            'goods_placeholder'  => 'Bitte geben Sie Produktname/kurze Beschreibung/SEO-Informationen ein',
            'price'              => 'Verkaufspreis (RMB)',
            'original_price'     => 'Originalpreis (Yuan)',
            'add_time'           => 'Erstellungszeit',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Benutzerinformationen',
            'user_placeholder'   => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'goods'              => 'Produktinformationen',
            'goods_placeholder'  => 'Bitte geben Sie Produktname/kurze Beschreibung/SEO-Informationen ein',
            'price'              => 'Verkaufspreis (RMB)',
            'original_price'     => 'Originalpreis (Yuan)',
            'add_time'           => 'Erstellungszeit',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Freundliche Links',
        // 动态表格
        'form_table'                            => [
            'info'                => 'Name',
            'url'                 => 'Url-Adresse',
            'describe'            => 'Beschreibung',
            'is_enable'           => 'Aktiviert oder nicht',
            'is_new_window_open'  => 'Ob sich ein neues Fenster öffnet',
            'sort'                => 'sortieren',
            'add_time'            => 'Erstellungszeit',
            'upd_time'            => 'Aktualisierungszeit',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Zwischennavigation', 'type' => 'header'],
            ['name' => 'Navigation unten', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'kundenspezifisch',
            'article'           => 'Artikel',
            'customview'        => 'Benutzerdefinierte Seite',
            'goods_category'    => 'Warenklassifizierung',
            'design'            => 'Seitengestaltung',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Navigationsname',
            'data_type'           => 'Navigationsdatentyp',
            'is_show'             => 'Ob angezeigt werden soll',
            'is_new_window_open'  => 'Neues Fenster öffnen',
            'sort'                => 'sortieren',
            'add_time'            => 'Erstellungszeit',
            'upd_time'            => 'Aktualisierungszeit',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Bestellnummer ist falsch',
            'express_choice_tips'               => 'Bitte wählen Sie eine Versandart',
            'payment_choice_tips'               => 'Bitte wählen Sie eine Zahlungsart',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Versandvorgänge',
        'form_payment_title'                    => 'Zahlungsvorgänge',
        'form_item_take'                        => 'Abholcode',
        'form_item_take_message'                => 'Bitte geben Sie den 4-stelligen Abholcode ein',
        'form_item_express_number'              => 'Kuriernummer',
        'form_item_express_number_message'      => 'Bitte füllen Sie die Express-Rechnungsnummer aus',
        // 地址
        'detail_user_address_title'             => 'Lieferadresse',
        'detail_user_address_name'              => 'Adressat',
        'detail_user_address_tel'               => 'Telefon empfangen',
        'detail_user_address_value'             => 'Detaillierte Adresse',
        'detail_user_address_idcard'            => 'Ausweisinformationen',
        'detail_user_address_idcard_name'       => 'vollständiger Name',
        'detail_user_address_idcard_number'     => 'Zahl',
        'detail_user_address_idcard_pic'        => 'Foto',
        'detail_take_address_title'             => 'Abholadresse',
        'detail_take_address_contact'           => 'Kontaktinformationen',
        'detail_take_address_value'             => 'Detaillierte Adresse',
        'detail_fictitious_title'               => 'Wichtige Informationen',
        // 订单售后
        'detail_aftersale_status'               => 'Zustand',
        'detail_aftersale_type'                 => 'Typ',
        'detail_aftersale_price'                => 'Geldbetrag',
        'detail_aftersale_number'               => 'Menge',
        'detail_aftersale_reason'               => 'Grund',
        // 商品
        'detail_goods_title'                    => 'Artikel bestellen',
        'detail_payment_amount_less_tips'       => 'Bitte beachten Sie, dass der Bestellbetrag geringer ist als der Gesamtbetrag',
        'detail_no_payment_tips'                => 'Bitte beachten Sie, dass die Bestellung noch nicht bezahlt wurde',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Grundlegende Informationen',
            'goods_placeholder'   => 'Bitte geben Sie die Bestell-ID/Bestellnummer/Produktname/Modell ein',
            'user'                => 'Benutzerinformationen',
            'user_placeholder'    => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'status'              => 'Auftragsstatus',
            'pay_status'          => 'Zahlungsstatus',
            'total_price'         => 'Gesamtpreis (Yuan)',
            'pay_price'           => 'Zahlungsbetrag (Yuan)',
            'price'               => 'Einzelpreis (Yuan)',
            'warehouse_name'      => 'Versandlager',
            'order_model'         => 'Bestellmodus',
            'client_type'         => 'Quelle',
            'address'             => 'Adressinformationen',
            'take'                => 'Abholinformationen',
            'refund_price'        => 'Erstattungsbetrag (Yuan)',
            'returned_quantity'   => 'Rückgabemenge',
            'buy_number_count'    => 'Einkäufe insgesamt',
            'increase_price'      => 'Betrag erhöhen (Yuan)',
            'preferential_price'  => 'Rabattbetrag (Yuan)',
            'payment_name'        => 'Zahlungsmethode',
            'user_note'           => 'Benutzerkommentare',
            'extension'           => 'Erweiterte Informationen',
            'express_name'        => 'Kurierdienst',
            'express_number'      => 'Kuriernummer',
            'aftersale'           => 'Neuester Kundendienst',
            'is_comments'         => 'Ob der Benutzer kommentiert',
            'confirm_time'        => 'Bestätigungszeit',
            'pay_time'            => 'Zahlungsfrist',
            'delivery_time'       => 'Lieferzeit',
            'collect_time'        => 'Fertigstellungszeit',
            'cancel_time'         => 'Zeit abbrechen',
            'close_time'          => 'Schließzeit',
            'add_time'            => 'Erstellungszeit',
            'upd_time'            => 'Aktualisierungszeit',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'Auditmaßnahme',
        'form_refuse_title'                     => 'Operation ablehnen',
        'form_user_info_title'                  => 'Benutzerinformationen',
        'form_apply_info_title'                 => 'Anwendungsinformationen',
        'forn_apply_info_type'                  => 'Typ',
        'forn_apply_info_price'                 => 'Geldbetrag',
        'forn_apply_info_number'                => 'Menge',
        'forn_apply_info_reason'                => 'Grund',
        'forn_apply_info_msg'                   => 'erklären',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Grundlegende Informationen',
            'goods_placeholder'  => 'Bitte geben Sie die Bestellnummer/Produktname/Modell ein',
            'user'               => 'Benutzerinformationen',
            'user_placeholder'   => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'status'             => 'Zustand',
            'type'               => 'Anwendungstyp',
            'reason'             => 'Grund',
            'price'              => 'Erstattungsbetrag (Yuan)',
            'number'             => 'Rückgabemenge',
            'msg'                => 'Rückerstattungsanweisungen',
            'refundment'         => 'Art der Erstattung',
            'voucher'            => 'Gutschein',
            'express_name'       => 'Kurierdienst',
            'express_number'     => 'Kuriernummer',
            'refuse_reason'      => 'Ablehnungsgrund',
            'apply_time'         => 'Anwendungszeit',
            'confirm_time'       => 'Bestätigungszeit',
            'delivery_time'      => 'Rückgabefrist',
            'audit_time'         => 'Prüfungszeit',
            'add_time'           => 'Erstellungszeit',
            'upd_time'           => 'Aktualisierungszeit',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'Zahlungsmethode',
        'nav_store_payment_name'                => 'Weitere Theme-Downloads',
        'upload_top_list_tips'                  => [
            [
                'name'  => '"Der Klassenname muss mit dem Dateinamen übereinstimmen (remove.php). Wenn Alipay.php verwendet wird, wird Alipay verwendet."',
            ],
            [
                'name'  => '2. Methoden, die eine Klasse definieren muss',
                'item'  => [
                    '2.1. Konfigurationsmethode',
                    '2.2. Bezahlmethode',
                    '2.3. Rückrufmethode',
                    '2.4. Benachrichtigen Sie die asynchrone Callback-Methode (optional, rufen Sie die Response-Methode auf, wenn sie nicht definiert ist)',
                    '2.5. Rückerstattungsmethode (optional, falls nicht definiert, kann die Erstattung der ursprünglichen Route nicht eingeleitet werden)',
                ],
            ],
            [
                'name'  => '3. Anpassbare Ausgabeinhaltsmethode',
                'item'  => [
                    '3.1. Erfolgreiche Rückgabe Zahlung (optional)',
                    '3.2. ErrorReturn Payment Failure (optional)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: Wenn die oben genannten Bedingungen nicht erfüllt sind, kann das Plug-In nicht angezeigt werden. Laden Sie das Plug-In in ein komprimiertes Paket hoch und unterstützen Sie mehrere Zahlungs-Plug-Ins in einer Komprimierung',
        // 动态表格
        'form_table'                            => [
            'name'            => 'Name',
            'logo'            => 'LOGO',
            'version'         => 'Ausgabe',
            'apply_version'   => 'Anwendbare Fassung',
            'apply_terminal'  => 'Anwendbare Klemmen',
            'author'          => 'Autor',
            'desc'            => 'Beschreibung',
            'enable'          => 'Aktiviert oder nicht',
            'open_user'       => 'Offen für Benutzer',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'Express',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Aktuelles Thema', 'type' => 'index'],
            ['name' => 'Theme-Installation', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'Weitere Theme-Downloads',
        'list_author_title'                     => 'Autor',
        'list_version_title'                    => 'Anwendbare Fassung',
        'form_theme_upload_tips'                => 'Laden Sie ein zip komprimiertes Theme Installationspaket hoch',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Navigation im Mobile User Center',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Name',
            'platform'       => 'Plattform',
            'images_url'     => 'Navigationssymbol',
            'event_type'     => 'Ereignistyp',
            'event_value'    => 'Ereigniswert',
            'desc'           => 'Beschreibung',
            'is_enable'      => 'Aktiviert oder nicht',
            'is_need_login'  => 'Müssen Sie sich einloggen?',
            'sort'           => 'sortieren',
            'add_time'       => 'Erstellungszeit',
            'upd_time'       => 'Aktualisierungszeit',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Mobile Home Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Name',
            'platform'       => 'Plattform',
            'images'         => 'Navigationssymbol',
            'event_type'     => 'Ereignistyp',
            'event_value'    => 'Ereigniswert',
            'is_enable'      => 'Aktiviert oder nicht',
            'is_need_login'  => 'Müssen Sie sich einloggen?',
            'sort'           => 'sortieren',
            'add_time'       => 'Erstellungszeit',
            'upd_time'       => 'Aktualisierungszeit',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Protokoll Zahlungsanfragen',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Benutzerinformationen',
            'user_placeholder'  => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'log_no'            => 'Zahlungsauftrag Nr.',
            'payment'           => 'Zahlungsmethode',
            'status'            => 'Zustand',
            'total_price'       => 'Geschäftsauftragsbetrag (Yuan)',
            'pay_price'         => 'Zahlungsbetrag (Yuan)',
            'business_type'     => 'Unternehmenstyp',
            'business_list'     => 'Geschäftsausweis/Dokumentnummer',
            'trade_no'          => 'Transaktionsnummer der Zahlungsplattform',
            'buyer_user'        => 'Benutzerkonto der Zahlungsplattform',
            'subject'           => 'Bestellname',
            'pay_time'          => 'Zahlungsfrist',
            'close_time'        => 'Schließzeit',
            'add_time'          => 'Erstellungszeit',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Protokoll Zahlungsanfragen',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Unternehmenstyp',
            'request_params'   => 'Anfrageparameter',
            'response_data'    => 'Antwortdaten',
            'business_handle'  => 'Ergebnisse der Geschäftsabwicklung',
            'request_url'      => 'URL-Adresse anfordern',
            'server_port'      => 'Hafennummer',
            'server_ip'        => 'Server IP',
            'client_ip'        => 'Client IP',
            'os'               => 'Betriebssystem',
            'browser'          => 'Browser',
            'method'           => 'Art der Anfrage',
            'scheme'           => 'Http-Typ',
            'version'          => 'Http-Version',
            'client'           => 'Kundendetails',
            'add_time'         => 'Erstellungszeit',
            'upd_time'         => 'Aktualisierungszeit',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Benutzerinformationen',
            'user_placeholder'  => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'payment'           => 'Zahlungsmethode',
            'business_type'     => 'Unternehmenstyp',
            'business_id'       => 'Geschäftsauftragsnummer',
            'trade_no'          => 'Transaktionsnummer der Zahlungsplattform',
            'buyer_user'        => 'Benutzerkonto der Zahlungsplattform',
            'refundment_text'   => 'Erstattungsmethode',
            'refund_price'      => 'Erstattungsbetrag',
            'pay_price'         => 'Auftragszahlungsbetrag',
            'msg'               => 'Beschreibung',
            'add_time_time'     => 'Rückzahlungszeit',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Zurück zur Anwendungsverwaltung>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Bitte klicken Sie zuerst auf das Häkchen, um es zu aktivieren',
            'save_no_data_tips'                 => 'Keine Plugin-Daten zu speichern',
        ],
        // 基础导航
        'base_nav_title'                        => 'Anwendung',
        'base_nav_list'                         => [
            ['name' => 'Anwendungsverwaltung', 'type' => 'index'],
            ['name' => 'App hochladen', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'Weitere Plug-in-Downloads',
        // 基础页面
        'base_search_input_placeholder'         => 'Bitte geben Sie einen Namen/eine Beschreibung ein',
        'base_top_tips_one'                     => 'Sortiermethode für Listen [Benutzerdefinierte Sortierung ->früheste Installation]',
        'base_top_tips_two'                     => 'Klicken und ziehen Sie die Symbolschaltflächen, um den Aufruf und die Anzeigereihenfolge des Plug-Ins anzupassen',
        'base_open_sort_title'                  => 'Sortierung aktivieren',
        'data_list_author_title'                => 'Autor',
        'data_list_author_url_title'            => 'Startseite',
        'data_list_version_title'               => 'Ausgabe',
        'uninstall_confirm_tips'                => 'Die Deinstallation kann zum Verlust der grundlegenden Konfigurationsdaten des Plug-Ins führen, die nicht wiederhergestellt werden können.',
        'not_install_divide_title'              => 'Die folgenden Plugins sind nicht installiert',
        'delete_plugins_text'                   => '1. Nur Apps löschen',
        'delete_plugins_text_tips'              => '(Nur Anwendungscode löschen und Anwendungsdaten speichern)',
        'delete_plugins_data_text'              => '2. Löschen Sie die Anwendung und löschen Sie die Daten',
        'delete_plugins_data_text_tips'         => '(Bewerbungscode und Bewerbungsdaten werden gelöscht)',
        'delete_plugins_ps_tips'                => 'PS: Keine der folgenden Operationen kann wiederhergestellt werden, bitte mit Vorsicht arbeiten!',
        'delete_plugins_button_name'            => 'Nur Apps löschen',
        'delete_plugins_data_button_name'       => 'Apps und Daten löschen',
        'cancel_delete_plugins_button_name'     => 'Denken Sie noch einmal nach',
        'more_plugins_store_to_text'            => 'Gehen Sie zum App Store, um weitere Plugins auszuwählen, um die Website zu bereichern>>',
        'no_data_store_to_text'                 => 'Gehen Sie zum App Store, um Plug-In Rich Sites auszuwählen>>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Zurück zum Hintergrund',
        'get_loading_tips'                      => 'Erhalten',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'Rolle',
        'admin_not_modify_tips'                 => 'Der Superadministrator hat standardmäßig alle Berechtigungen und kann nicht geändert werden.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Rollenname',
            'is_enable'  => 'Aktiviert oder nicht',
            'add_time'   => 'Erstellungszeit',
            'upd_time'   => 'Aktualisierungszeit',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'Zuständigkeit',
        'top_tips_list'                         => [
            '1. Nicht professionelles technisches Personal sollte die Daten auf dieser Seite nicht bedienen. Fehlbedienungen können zu Verwirrungen im Berechtigungsmenü führen.',
            '2. Berechtigungsmenüs sind in zwei Typen unterteilt: [Verwendung und Betrieb]. Das Menü "Verwenden" wird normalerweise eingeschaltet und das Menü "Operation" muss ausgeblendet sein.',
            '3. Wenn das Berechtigungsmenü ungeordnet ist, können Sie die Datenwiederherstellung der Datentabelle ['.MyConfig('database.connections.mysql.prefix').'power] erneut überschreiben.',
            '4. [Super Administrator, Admin Account] hat standardmäßig alle Berechtigungen und kann nicht geändert werden.',
        ],
        'content_top_tips_list'                 => [
            '"Um [Controller Name und Methodenname] auszufüllen, müssen entsprechende Definitionen für Controller und Methode erstellt werden."',
            '2. Speicherort der Controller-Datei [app/admin/controller]. Dieser Vorgang wird nur von Entwicklern verwendet',
            '"Entweder der Controller-Name/Methodenname oder die benutzerdefinierte URL-Adresse muss ausgefüllt werden."',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Schnellnavigation',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Name',
            'platform'     => 'Plattform',
            'images'       => 'Navigationssymbol',
            'event_type'   => 'Ereignistyp',
            'event_value'  => 'Ereigniswert',
            'is_enable'    => 'Aktiviert oder nicht',
            'sort'         => 'sortieren',
            'add_time'     => 'Erstellungszeit',
            'upd_time'     => 'Aktualisierungszeit',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'Region',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Filterpreis',
        'top_tips_list'                         => [
            'Mindestpreis 0,max Preis 100 ist kleiner als 100',
            'Minimum price 1000,max price 0 ist größer als 1000',
            'Der Mindestpreis von 100.Der Maximalpreis von 500 ist größer oder gleich 100 und kleiner als 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Rotation',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Name',
            'platform'     => 'Plattform',
            'images'       => 'Bild',
            'event_type'   => 'Ereignistyp',
            'event_value'  => 'Ereigniswert',
            'is_enable'    => 'Aktiviert oder nicht',
            'sort'         => 'sortieren',
            'add_time'     => 'Erstellungszeit',
            'upd_time'     => 'Aktualisierungszeit',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Benutzerinformationen',
            'user_placeholder'    => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'type'                => 'Betriebsart',
            'operation_integral'  => 'Operatives Integral',
            'original_integral'   => 'Ursprüngliches Integral',
            'new_integral'        => 'Aktuelle Punkte',
            'msg'                 => 'Betriebsgrund',
            'operation_id'        => 'Operator ID',
            'add_time_time'       => 'Betriebszeit',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Benutzerinformationen',
            'user_placeholder'          => 'Bitte geben Sie Benutzername/Nickname/Telefon/E-Mail ein',
            'type'                      => 'Nachrichtentyp',
            'business_type'             => 'Unternehmenstyp',
            'title'                     => 'Titel',
            'detail'                    => 'Details',
            'is_read'                   => 'Lesen oder nicht',
            'user_is_delete_time_text'  => 'Ob der Benutzer gelöscht werden soll',
            'add_time_time'             => 'Sendezeit',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: Nicht-Entwickler sollten keine SQL-Anweisungen nach Belieben ausführen, da die Operation dazu führen kann, dass die gesamte Systemdatenbank gelöscht wird.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'Eine Liste ausgezeichneter ShopXO-Anwendungen ist eine Sammlung der erfahrensten, technisch versiertesten und vertrauenswürdigsten ShopXO-Entwickler, die eine umfassende Begleitung für Ihre Plug-in-, Stil- und Templateanpassung bieten.',
        'to_store_name'                         => 'Gehen Sie zum App Store, um Plug-ins auszuwählen>>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Hintergrundverwaltungssystem',
        'remove_cache_title'                    => 'Cache löschen',
        'user_status_title'                     => 'Benutzerstatus',
        'user_status_message'                   => 'Bitte Benutzerstatus auswählen',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Informationen zur Konfiguration der Produktparameter',
        'form_goods_params_copy_no_tips'        => 'Bitte fügen Sie zuerst die Konfigurationsinformationen ein',
        'form_goods_params_copy_error_tips'     => 'Fehler im Konfigurationsformat',
        'form_goods_params_type_message'        => 'Bitte wählen Sie einen Produktparameter Anzeigetyp',
        'form_goods_params_params_name'         => 'Parametername',
        'form_goods_params_params_message'      => 'Bitte geben Sie den Parameternamen ein',
        'form_goods_params_value_name'          => 'Parameterwert',
        'form_goods_params_value_message'       => 'Bitte geben Sie den Parameterwert ein',
        'form_goods_params_move_type_tips'      => 'Falsche Konfiguration des Betriebstyps',
        'form_goods_params_move_top_tips'       => 'Erreichte die Spitze',
        'form_goods_params_move_bottom_tips'    => 'Erreichte den Boden',
        'form_goods_params_thead_type_title'    => 'Anzeigebereich',
        'form_goods_params_thead_name_title'    => 'Parametername',
        'form_goods_params_thead_value_title'   => 'Parameterwert',
        'form_goods_params_row_add_title'       => 'Zeile hinzufügen',
        'form_goods_params_list_tips'           => [
            '1. Alle (angezeigt unter den grundlegenden Informationen und detaillierten Parametern des Produkts)',
            '2. Details (nur unter dem Parameter Produktdetails angezeigt)',
            '3. Grundlagen (nur unter grundlegenden Rohstoffinformationen angezeigt)',
            '4. Shortcut-Operation löscht die Originaldaten und lädt die Seite neu, um die Originaldaten wiederherzustellen (nur wirksam nach dem Speichern des Produkts)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Systemeinstellungen',
            'item'  => [
                'config_index'                 => 'Systemkonfiguration',
                'config_store'                 => 'Informationen speichern',
                'config_save'                  => 'Konfiguration Speichern',
                'index_storeaccountsbind'      => 'Kontobindung speichern',
                'index_inspectupgrade'         => 'Überprüfung der Systemaktualisierung',
                'index_inspectupgradeconfirm'  => 'Bestätigung der Systemaktualisierung',
                'index_stats'                  => 'Startseite Statistik',
                'index_income'                 => 'Startseite Statistik (Einnahmenstatistik)',
            ]
        ],
        'site_index' => [
            'name'  => 'Standortkonfiguration',
            'item'  => [
                'site_index'                  => 'Site-Einstellungen',
                'site_save'                   => 'Site-Einstellungen Bearbeiten',
                'site_goodssearch'            => 'Site-Einstellungen Produktsuche',
                'layout_layoutindexhomesave'  => 'Layout-Verwaltung der Startseite',
                'sms_index'                   => 'SMS-Einstellungen',
                'sms_save'                    => 'Bearbeitung der SMS-Einstellungen',
                'email_index'                 => 'Postfacheinstellungen',
                'email_save'                  => 'Postfacheinstellungen/Bearbeiten',
                'email_emailtest'             => 'Mail Delivery Test',
                'seo_index'                   => 'SEO Einstellungen',
                'seo_save'                    => 'SEO Einstellungen Bearbeiten',
                'agreement_index'             => 'Protokollverwaltung',
                'agreement_save'              => 'Protokolleinstellungen Bearbeiten',
            ]
        ],
        'power_index' => [
            'name'  => 'Berechtigungskontrolle',
            'item'  => [
                'admin_index'        => 'Manager',
                'admin_saveinfo'     => 'Admin Seite hinzufügen/bearbeiten',
                'admin_save'         => 'Administrator Hinzufügen/Bearbeiten',
                'admin_delete'       => 'Administrator Löschen',
                'admin_detail'       => 'Details des Administrators',
                'role_index'         => 'Rollenverwaltung',
                'role_saveinfo'      => 'Rollengruppe Seite hinzufügen/bearbeiten',
                'role_save'          => 'Rollengruppe hinzufügen/bearbeiten',
                'role_delete'        => 'Rollenlöschung',
                'role_statusupdate'  => 'Aktualisierung des Rollenstatus',
                'role_detail'        => 'Rollendetails',
                'power_index'        => 'Berechtigungszuweisung',
                'power_save'         => 'Berechtigung Hinzufügen/Bearbeiten',
                'power_delete'       => 'Löschen von Berechtigungen',
            ]
        ],
        'user_index' => [
            'name'  => 'Benutzerverwaltung',
            'item'  => [
                'user_index'            => 'Benutzerliste',
                'user_saveinfo'         => 'Benutzer Seite bearbeiten/hinzufügen',
                'user_save'             => 'Benutzer hinzufügen/bearbeiten',
                'user_delete'           => 'Benutzer löschen',
                'user_detail'           => 'Benutzerdetails',
                'useraddress_index'     => 'Benutzeradresse',
                'useraddress_saveinfo'  => 'Benutzeradresse Seite bearbeiten',
                'useraddress_save'      => 'Bearbeitung der Benutzeradresse',
                'useraddress_delete'    => 'Löschen der Benutzeradresse',
                'useraddress_detail'    => 'Benutzeradresse Details',
            ]
        ],
        'goods_index' => [
            'name'  => 'Rohstoffmanagement',
            'item'  => [
                'goods_index'                       => 'Rohstoffmanagement',
                'goods_saveinfo'                    => 'Produkt hinzufügen/bearbeiten Seite',
                'goods_save'                        => 'Produkt hinzufügen/bearbeiten',
                'goods_delete'                      => 'Produktlöschung',
                'goods_statusupdate'                => 'Aktualisierung des Produktstatus',
                'goods_basetemplate'                => 'Abrufen der Vorlage für die Produktbasis',
                'goods_detail'                      => 'Produktdetails',
                'goodscategory_index'               => 'Warenklassifizierung',
                'goodscategory_save'                => 'Produktkategorie Hinzufügen/Bearbeiten',
                'goodscategory_delete'              => 'Streichung der Produktklassifizierung',
                'goodsparamstemplate_index'         => 'Produktparameter',
                'goodsparamstemplate_delete'        => 'Löschen von Produktparametern',
                'goodsparamstemplate_statusupdate'  => 'Aktualisierung des Status der Produktparameter',
                'goodsparamstemplate_saveinfo'      => 'Produktparameter Seite hinzufügen/bearbeiten',
                'goodsparamstemplate_save'          => 'Produktparameter hinzufügen/bearbeiten',
                'goodsparamstemplate_detail'        => 'Details zu den Produktparametern',
                'goodsspectemplate_index'           => 'Produktspezifikationen',
                'goodsspectemplate_delete'          => 'Streichung der Produktspezifikation',
                'goodsspectemplate_statusupdate'    => 'Aktualisierung des Produktspezifikationsstatus',
                'goodsspectemplate_saveinfo'        => 'Produktspezifikation Seite hinzufügen/bearbeiten',
                'goodsspectemplate_save'            => 'Produktspezifikation Hinzufügen/Bearbeiten',
                'goodsspectemplate_detail'          => 'Produktdetails',
                'goodscomments_detail'              => 'Details zur Produktbewertung',
                'goodscomments_index'               => 'Produktbewertungen',
                'goodscomments_reply'               => 'Antwort auf die Produktbewertung',
                'goodscomments_delete'              => 'Löschung von Produktbewertungen',
                'goodscomments_statusupdate'        => 'Aktualisierung des Status der Produktbewertung',
                'goodscomments_saveinfo'            => 'Produktkommentar Seite hinzufügen/bearbeiten',
                'goodscomments_save'                => 'Produktkommentar hinzufügen/bearbeiten',
                'goodsbrowse_index'                 => 'Durchsuchen von Produkten',
                'goodsbrowse_delete'                => 'Produkt durchsuchen Löschen',
                'goodsbrowse_detail'                => 'Produktdetails',
                'goodsfavor_index'                  => 'Produktsammlung',
                'goodsfavor_delete'                 => 'Produktsammlung löschen',
                'goodsfavor_detail'                 => 'Details zur Produktsammlung',
                'goodscart_index'                   => 'Warenkorb',
                'goodscart_delete'                  => 'Warenkorb Löschen',
                'goodscart_detail'                  => 'Details zum Warenkorb',
            ]
        ],
        'order_index' => [
            'name'  => 'Auftragsverwaltung',
            'item'  => [
                'order_index'             => 'Auftragsverwaltung',
                'order_delete'            => 'Auftragslöschung',
                'order_cancel'            => 'Stornierung der Bestellung',
                'order_delivery'          => 'Bestellung Versand',
                'order_collect'           => 'Auftragseingang',
                'order_pay'               => 'Auftragszahlung',
                'order_confirm'           => 'Auftragsbestätigung',
                'order_detail'            => 'Bestelldetails',
                'orderaftersale_index'    => 'After Sales bestellen',
                'orderaftersale_delete'   => 'Bestellen After Sales Löschen',
                'orderaftersale_cancel'   => 'Stornierung der Bestellung nach dem Verkauf',
                'orderaftersale_audit'    => 'Überprüfung der Bestellung nach dem Verkauf',
                'orderaftersale_confirm'  => 'Auftragsbestätigung nach dem Verkauf',
                'orderaftersale_refuse'   => 'Auftragsabweichung',
                'orderaftersale_detail'   => 'Details zur Bestellung nach dem Verkauf',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Website Management',
            'item'  => [
                'navigation_index'         => 'Navigationsmanagement',
                'navigation_save'          => 'Navigation Hinzufügen/Bearbeiten',
                'navigation_delete'        => 'Navigation Löschen',
                'navigation_statusupdate'  => 'Aktualisierung des Navigationsstatus',
                'customview_index'         => 'Benutzerdefinierte Seite',
                'customview_saveinfo'      => 'Benutzerdefinierte Seite Seite hinzufügen/bearbeiten',
                'customview_save'          => 'Benutzerdefinierte Seite hinzufügen/bearbeiten',
                'customview_delete'        => 'Benutzerdefinierte Seite löschen',
                'customview_statusupdate'  => 'Aktualisierung des benutzerdefinierten Seitenstatus',
                'customview_detail'        => 'Benutzerdefinierte Seitendetails',
                'link_index'               => 'Freundliche Links',
                'link_saveinfo'            => 'Freundlicher Link Seite hinzufügen/bearbeiten',
                'link_save'                => 'Freundlicher Link hinzufügen/bearbeiten',
                'link_delete'              => 'Freundliches Löschen von Links',
                'link_statusupdate'        => 'Aktualisierung des Status des Freundlichen Links',
                'link_detail'              => 'Freundliche Link-Details',
                'theme_index'              => 'Themenverwaltung',
                'theme_save'               => 'Theme Management Hinzufügen/Bearbeiten',
                'theme_upload'             => 'Installation des Theme-Uploads',
                'theme_delete'             => 'Theme-Löschung',
                'theme_download'           => 'Theme Download',
                'slide_index'              => 'Rotation der Startseite',
                'slide_saveinfo'           => 'Umfrage Seite hinzufügen/bearbeiten',
                'slide_save'               => 'Broadcast Hinzufügen/Bearbeiten',
                'slide_statusupdate'       => 'Aktualisierung des Umfragestatus',
                'slide_delete'             => 'Umfrage Löschen',
                'slide_detail'             => 'Broadcast Details',
                'screeningprice_index'     => 'Filterpreis',
                'screeningprice_save'      => 'Filterpreis hinzufügen/bearbeiten',
                'screeningprice_delete'    => 'Filterpreis löschen',
                'region_index'             => 'Regionale Verwaltung',
                'region_save'              => 'Region hinzufügen/bearbeiten',
                'region_delete'            => 'Region löschen',
                'region_codedata'          => 'Abrufen von Flächennummerndaten',
                'express_index'            => 'Express-Verwaltung',
                'express_save'             => 'Express Hinzufügen/Bearbeiten',
                'express_delete'           => 'Express Löschen',
                'payment_index'            => 'Zahlungsmethode',
                'payment_saveinfo'         => 'Seite zur Installation/Bearbeitung der Zahlungsmethode',
                'payment_save'             => 'Installation/Bearbeitung der Zahlungsmethode',
                'payment_delete'           => 'Zahlungsmethode löschen',
                'payment_install'          => 'Installation der Zahlungsmethode',
                'payment_statusupdate'     => 'Aktualisierung des Status der Zahlungsmethode',
                'payment_uninstall'        => 'Deinstallation der Zahlungsmethode',
                'payment_upload'           => 'Upload der Zahlungsmethode',
                'quicknav_index'           => 'Schnellnavigation',
                'quicknav_saveinfo'        => 'Schnellnavigation Seite hinzufügen/bearbeiten',
                'quicknav_save'            => 'Schnelle Navigation Hinzufügen/Bearbeiten',
                'quicknav_statusupdate'    => 'Aktualisierung des Schnellnavigationsstatus',
                'quicknav_delete'          => 'Schnellnavigation Löschen',
                'quicknav_detail'          => 'Schnellnavigationsdetails',
                'design_index'             => 'Seitengestaltung',
                'design_saveinfo'          => 'Seitendesign Seite hinzufügen/bearbeiten',
                'design_save'              => 'Seitengestaltung hinzufügen/bearbeiten',
                'design_statusupdate'      => 'Aktualisierung des Seitenentwurfsstatus',
                'design_upload'            => 'Seitenentwurf importieren',
                'design_download'          => 'Seitendesign Download',
                'design_sync'              => 'Seitendesign Synchronisierung Startseite',
                'design_delete'            => 'Seitendesign Löschen',
            ]
        ],
        'brand_index' => [
            'name'  => 'Markenführung',
            'item'  => [
                'brand_index'           => 'Markenführung',
                'brand_saveinfo'        => 'Marke Seite hinzufügen/bearbeiten',
                'brand_save'            => 'Marke hinzufügen/bearbeiten',
                'brand_statusupdate'    => 'Aktualisierung des Markenstatus',
                'brand_delete'          => 'Markenlöschung',
                'brand_detail'          => 'Markendetails',
                'brandcategory_index'   => 'Markenklassifizierung',
                'brandcategory_save'    => 'Markenkategorie hinzufügen/bearbeiten',
                'brandcategory_delete'  => 'Markenkategorie löschen',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Lagerverwaltung',
            'item'  => [
                'warehouse_index'               => 'Lagerverwaltung',
                'warehouse_saveinfo'            => 'Lager Seite hinzufügen/bearbeiten',
                'warehouse_save'                => 'Lager hinzufügen/bearbeiten',
                'warehouse_delete'              => 'Lager löschen',
                'warehouse_statusupdate'        => 'Aktualisierung des Lagerstatus',
                'warehouse_detail'              => 'Lagerdetails',
                'warehousegoods_index'          => 'Warehouse Commodity Management',
                'warehousegoods_detail'         => 'Artikeldetails des Lagers',
                'warehousegoods_delete'         => 'Löschen von Lagerelementen',
                'warehousegoods_statusupdate'   => 'Aktualisierung des Lagerartikelstatus',
                'warehousegoods_goodssearch'    => 'Suche nach Lagerartikeln',
                'warehousegoods_goodsadd'       => 'Suche nach Lagerartikeln Hinzufügen',
                'warehousegoods_goodsdel'       => 'Lagerelement Suche Löschen',
                'warehousegoods_inventoryinfo'  => 'Bearbeitungsseite Lagerbestand',
                'warehousegoods_inventorysave'  => 'Lagerbestand bearbeiten',
            ]
        ],
        'app_index' => [
            'name'  => 'Telefonverwaltung',
            'item'  => [
                'appconfig_index'            => 'Grundkonfiguration',
                'appconfig_save'             => 'Einfache Konfigurationsspeicherung',
                'apphomenav_index'           => 'Navigation auf der Startseite',
                'apphomenav_saveinfo'        => 'Startseite Navigation Seite hinzufügen/bearbeiten',
                'apphomenav_save'            => 'Startseite Navigation hinzufügen/bearbeiten',
                'apphomenav_statusupdate'    => 'Aktualisierung des Navigationsstatus der Homepage',
                'apphomenav_delete'          => 'Löschen der Home-Navigation',
                'apphomenav_detail'          => 'Details zur Navigation auf der Startseite',
                'appcenternav_index'         => 'Navigation im Benutzercenter',
                'appcenternav_saveinfo'      => 'User Center Navigation Seite hinzufügen/bearbeiten',
                'appcenternav_save'          => 'Benutzercenter Navigation Hinzufügen/Bearbeiten',
                'appcenternav_statusupdate'  => 'Aktualisierung des Navigationsstatus im Benutzercenter',
                'appcenternav_delete'        => 'Benutzercenter Navigation Löschen',
                'appcenternav_detail'        => 'Details zur Navigation im Benutzercenter',
                'appmini_index'              => 'Appletliste',
                'appmini_created'            => 'Erzeugung kleiner Pakete',
                'appmini_delete'             => 'Applet-Paket löschen',
                'appmini_themeupload'        => 'Applet-Theme hochladen',
                'appmini_themesave'          => 'Applet-Theme-Schalter',
                'appmini_themedelete'        => 'Applet-Theme-Schalter',
                'appmini_themedownload'      => 'Applet-Design herunterladen',
                'appmini_config'             => 'Applet-Konfiguration',
                'appmini_save'               => 'Speichern der Applet-Konfiguration',
            ]
        ],
        'article_index' => [
            'name'  => 'Artikelverwaltung',
            'item'  => [
                'article_index'           => 'Artikelverwaltung',
                'article_saveinfo'        => 'Artikel Seite hinzufügen/bearbeiten',
                'article_save'            => 'Artikel hinzufügen/bearbeiten',
                'article_delete'          => 'Streichung des Artikels',
                'article_statusupdate'    => 'Aktualisierung des Artikelstatus',
                'article_detail'          => 'Artikeldetails',
                'articlecategory_index'   => 'Artikelklassifizierung',
                'articlecategory_save'    => 'Artikelkategorie Bearbeiten/Hinzufügen',
                'articlecategory_delete'  => 'Artikelkategorie Löschen',
            ]
        ],
        'data_index' => [
            'name'  => 'Datenmanagement',
            'item'  => [
                'answer_index'          => 'Q&A-Nachricht',
                'answer_reply'          => 'Antwort auf Fragen und Antworten',
                'answer_delete'         => 'Löschen von Fragen und Antworten',
                'answer_statusupdate'   => 'Aktualisierung des Status von Fragen und Antworten',
                'answer_saveinfo'       => 'Q&A Seite hinzufügen/bearbeiten',
                'answer_save'           => 'Q&A Hinzufügen/Bearbeiten',
                'answer_detail'         => 'Details zu Fragen und Antworten',
                'message_index'         => 'Nachrichtenverwaltung',
                'message_delete'        => 'Nachrichtenlöschung',
                'message_detail'        => 'Nachrichtendetails',
                'paylog_index'          => 'Zahlungsprotokoll',
                'paylog_detail'         => 'Details zum Zahlungsprotokoll',
                'paylog_close'          => 'Zahlungsprotokoll geschlossen',
                'payrequestlog_index'   => 'Liste der Zahlungsanfragen',
                'payrequestlog_detail'  => 'Details zum Zahlungsantrag',
                'refundlog_index'       => 'Rückerstattungsprotokoll',
                'refundlog_detail'      => 'Details zum Rückerstattungsprotokoll',
                'integrallog_index'     => 'Punkteprotokoll',
                'integrallog_detail'    => 'Details zum Punkteprotokoll',
            ]
        ],
        'store_index' => [
            'name'  => 'Anwendungscenter',
            'item'  => [
                'pluginsadmin_index'         => 'Anwendungsverwaltung',
                'plugins_index'              => 'Verwaltung von Anwendungsaufrufen',
                'pluginsadmin_saveinfo'      => 'App Hinzufügen/Bearbeiten Seite',
                'pluginsadmin_save'          => 'App hinzufügen/bearbeiten',
                'pluginsadmin_statusupdate'  => 'Aktualisierung des Anwendungsstatus',
                'pluginsadmin_delete'        => 'App löschen',
                'pluginsadmin_upload'        => 'App-Upload',
                'pluginsadmin_download'      => 'App Packaging',
                'pluginsadmin_install'       => 'Installation der Anwendung',
                'pluginsadmin_uninstall'     => 'Apps deinstallieren',
                'pluginsadmin_sortsave'      => 'Sortieren Speichern anwenden',
                'store_index'                => 'App Store',
                'packageinstall_index'       => 'Paketinstallationsseite',
                'packageinstall_install'     => 'Installation von Softwarepaketen',
                'packageupgrade_upgrade'     => 'Aktualisierung des Softwarepakets',
            ]
        ],
        'tool_index' => [
            'name'  => 'Werkzeug',
                'item'                  => [
                'cache_index'           => 'Cache-Verwaltung',
                'cache_statusupdate'    => 'Aktualisierung des Standortcaches',
                'cache_templateupdate'  => 'Aktualisierung des Vorlagencaches',
                'cache_moduleupdate'    => 'Modul Cache Update',
                'cache_logdelete'       => 'Protokolllöschung',
                'sqlconsole_index'      => 'SQL-Konsole',
                'sqlconsole_implement'  => 'SQL-Ausführung',
            ]
        ],
    ],
];
?>