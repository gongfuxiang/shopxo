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
 * 模块语言包-意大利语
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
            'order_transaction_amount_name'     => 'Evoluzione dellimporto delloperazione dellordine',
            'order_trading_trend_name'          => 'Tendenza del trading degli ordini',
            'goods_hot_name'                    => 'Prodotti venduti a caldo',
            'goods_hot_tips'                    => 'Mostra solo i primi 30 elementi',
            'payment_name'                      => 'Metodo di pagamento',
            'order_region_name'                 => 'Ordine distribuzione geografica',
            'order_region_tips'                 => 'Mostra solo 30 pezzi di dati',
            'upgrade_check_loading_tips'        => 'Ottenere gli ultimi contenuti, attendere',
            'upgrade_version_name'              => 'Versione aggiornata:',
            'upgrade_date_name'                 => 'Data di aggiornamento:',
        ],
        // 页面基础
        'base_update_button_title'              => 'Aggiorna ora',
        'base_item_base_stats_title'            => 'Statistiche dei centri commerciali',
        'base_item_base_stats_tips'             => 'Il filtro tempo è valido solo per i totali',
        'base_item_user_title'                  => 'Totale utenti',
        'base_item_order_number_title'          => 'Importo totale dellordine',
        'base_item_order_complete_number_title' => 'Volume totale delle transazioni',
        'base_item_order_complete_title'        => 'Importo dellordine',
        'base_item_last_month_title'            => 'Il mese scorso',
        'base_item_same_month_title'            => 'stesso mese',
        'base_item_yesterday_title'             => 'ieri',
        'base_item_today_title'                 => 'oggi',
        'base_item_order_profit_title'          => 'Evoluzione dellimporto delloperazione dellordine',
        'base_item_order_trading_title'         => 'Tendenza del trading degli ordini',
        'base_item_order_tips'                  => 'Tutti gli ordini',
        'base_item_hot_sales_goods_title'       => 'Prodotti venduti a caldo',
        'base_item_hot_sales_goods_tips'        => 'Escludere gli ordini annullati e chiusi',
        'base_item_payment_type_title'          => 'Metodo di pagamento',
        'base_item_map_whole_country_title'     => 'Ordine distribuzione geografica',
        'base_item_map_whole_country_tips'      => 'Escludere gli ordini annullati e le dimensioni predefinite (province)',
        'base_item_map_whole_country_province'  => 'provincia',
        'base_item_map_whole_country_city'      => 'città',
        'base_item_map_whole_country_county'    => 'Distretto/contea',
        'system_info_title'                     => 'informazioni sul sistema',
        'system_ver_title'                      => 'Versione software',
        'system_os_ver_title'                   => 'sistema operativo',
        'system_php_ver_title'                  => 'Versione PHP',
        'system_mysql_ver_title'                => 'Versione MySQL',
        'system_server_ver_title'               => 'Informazioni sul lato server',
        'system_host_title'                     => 'Nome di dominio corrente',
        'development_team_title'                => 'il team di sviluppo',
        'development_team_website_title'        => 'Sito ufficiale della società',
        'development_team_website_value'        => 'Shanghai Zongzhige Technology Co., Ltd.',
        'development_team_support_title'        => 'supporto tecnico',
        'development_team_support_value'        => 'Fornitore di sistemi di e-commerce Enterprise ShopXO',
        'development_team_ask_title'            => 'Domande sulla comunicazione',
        'development_team_ask_value'            => 'Domande sulla comunicazione ShopXO',
        'development_team_agreement_title'      => 'Protocollo open source',
        'development_team_agreement_value'      => 'Visualizza accordi open source',
        'development_team_update_log_title'     => 'Aggiornamento registro',
        'development_team_update_log_value'     => 'Visualizza registro degli aggiornamenti',
        'development_team_members_title'        => 'Membri R&S',
        'development_team_members_value'        => [
            ['name' => 'Fratello Gong', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'utente',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'ID utente',
            'number_code'           => 'Codice di appartenenza',
            'system_type'           => 'Tipo di sistema',
            'platform'              => 'Piattaforma',
            'avatar'                => 'ritratto della testa',
            'username'              => 'nome utente',
            'nickname'              => 'nickname',
            'mobile'                => 'telefono cellulare',
            'email'                 => 'cassetta postale',
            'gender_name'           => 'Sesso',
            'status_name'           => 'stato',
            'province'              => 'Provincia',
            'city'                  => 'Città',
            'county'                => 'Distretto/contea',
            'address'               => 'Indirizzo dettagliato',
            'birthday'              => 'compleanno',
            'integral'              => 'Punti disponibili',
            'locking_integral'      => 'Integrale bloccato',
            'referrer'              => 'Invita utenti',
            'referrer_placeholder'  => 'Inserisci il nome utente/nickname/telefono/email dellinvito',
            'add_time'              => 'Orario di registrazione',
            'upd_time'              => 'Tempo di aggiornamento',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Indirizzo utente',
        // 详情
        'detail_user_address_idcard_name'       => 'nome completo',
        'detail_user_address_idcard_number'     => 'numero',
        'detail_user_address_idcard_pic'        => 'Foto',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informazioni utente',
            'user_placeholder'  => 'Inserisci nome utente/nickname/telefono/email',
            'alias'             => 'alias',
            'name'              => 'contatti',
            'tel'               => 'numero di contatto',
            'province_name'     => 'Provincia',
            'city_name'         => 'Città',
            'county_name'       => 'Distretto/contea',
            'address'           => 'Indirizzo dettagliato',
            'position'          => 'Longitudine e latitudine',
            'idcard_info'       => 'Informazioni sulla carta didentità',
            'is_default'        => 'Predefinito o no',
            'add_time'          => 'Tempo di creazione',
            'upd_time'          => 'Tempo di aggiornamento',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Il salvataggio avrà effetto dopo la rimozione. Sei sicuro di continuare?',
            'address_no_data'                   => 'I dati dellindirizzo sono vuoti',
            'address_not_exist'                 => 'Indirizzo non esiste',
            'address_logo_message'              => 'Carica unimmagine del logo',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Configurazione di base', 'type' => 'base'],
            ['name' => 'Impostazioni sito', 'type' => 'siteset'],
            ['name' => 'Tipo di sito', 'type' => 'sitetype'],
            ['name' => 'Registrazione utente', 'type' => 'register'],
            ['name' => 'Accesso utente', 'type' => 'login'],
            ['name' => 'Recupero password', 'type' => 'forgetpwd'],
            ['name' => 'Codice di verifica', 'type' => 'verify'],
            ['name' => 'Ordine post vendita', 'type' => 'orderaftersale'],
            ['name' => 'recinzione', 'type' => 'attachment'],
            ['name' => 'cache', 'type' => 'cache'],
            ['name' => 'Estensioni', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'home page', 'type' => 'index'],
            ['name' => 'merce', 'type' => 'goods'],
            ['name' => 'ricerca', 'type' => 'search'],
            ['name' => 'ordine', 'type' => 'order'],
            ['name' => 'Sconto', 'type' => 'discount'],
            ['name' => 'estendere', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Stato del sito',
        'base_item_site_domain_title'           => 'Indirizzo del nome del dominio del sito',
        'base_item_site_filing_title'           => 'Informazioni di deposito',
        'base_item_site_other_title'            => 'altro',
        'base_item_session_cache_title'         => 'Configurazione cache sessione',
        'base_item_data_cache_title'            => 'Configurazione cache dati',
        'base_item_redis_cache_title'           => 'Configurazione cache Redis',
        'base_item_crontab_config_title'        => 'Configurazione dello script di temporizzazione',
        'base_item_quick_nav_title'             => 'Navigazione rapida',
        'base_item_user_base_title'             => 'Base di utenti',
        'base_item_user_address_title'          => 'Indirizzo utente',
        'base_item_multilingual_title'          => 'Multilingue',
        'base_item_site_auto_mode_title'        => 'Modalità automatica',
        'base_item_site_manual_mode_title'      => 'Modalità manuale',
        'base_item_default_payment_title'       => 'Metodo di pagamento predefinito',
        'base_item_display_type_title'          => 'Tipo di visualizzazione',
        'base_item_self_extraction_title'       => 'Autoadesiva',
        'base_item_fictitious_title'            => 'Vendite virtuali',
        'choice_upload_logo_title'              => 'Seleziona un logo',
        'add_goods_title'                       => 'Aggiunta del prodotto',
        'add_self_extractio_address_title'      => 'Aggiungi indirizzo',
        'site_domain_tips_list'                 => [
            '1. Se il nome di dominio del sito non è impostato, verranno utilizzati il nome di dominio del sito corrente, il nome di dominio e lindirizzo[ '.__MY_DOMAIN__.' ]',
            '2. Se lallegato e lindirizzo statico non sono impostati, verrà utilizzato lindirizzo del nome di dominio statico del sito corrente[ '.__MY_PUBLIC_URL__.' ]',
            '3. Se public non è impostato come directory root sul lato server, la configurazione di [attachment cdn domain name, css/js static file cdn domain name] deve essere seguita da public, ad esempio:'.__MY_PUBLIC_URL__.'public/',
            '4. Quando si esegue un progetto in modalità riga di comando, lindirizzo della regione deve essere configurato, altrimenti ad alcuni indirizzi del progetto mancano informazioni sul nome di dominio.',
            '5. Non configurare casualmente. Un indirizzo errato può causare linaccessibilità del sito web (la configurazione dellindirizzo inizia con http). Se il tuo sito è configurato con https, inizia con https',
        ],
        'site_cache_tips_list'                  => [
            '1. Il caching predefinito dei file e il caching Redis PHP richiedono che lestensione Redis venga installata prima',
            '2. Assicurarsi la stabilità del servizio Redis (dopo aver utilizzato la cache per una sessione, il servizio unstable potrebbe causare limpossibilità di accedere allo sfondo)',
            '"Se si incontra uneccezione di servizio Redis, non è possibile accedere allo sfondo di gestione e modificare il file [session.php, cache.php] nella directory [config] del file di configurazione."',
        ],
        'goods_tips_list'                       => [
            '1. La visualizzazione predefinita sul lato WEB è di livello 3, con un minimo di livello 1 e un massimo di livello 3 (se impostata sul livello 0, limpostazione predefinita è di livello 3)',
            '2. La visualizzazione predefinita sul terminale mobile è livello 0 (modalità elenco prodotti), livello minimo 0 e livello massimo 3 (1-3 sono modalità di visualizzazione classificate)',
            '3. Diversi livelli e stili di pagina di categoria front-end possono anche essere diversi',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Configurare il numero massimo di prodotti visualizzati su ogni piano',
            '2. Non è raccomandato modificare la quantità troppo grande, che causerà larea vuota sul lato sinistro del terminale del PC per essere troppo grande',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'Comprensivo: Calore ->Vendite ->Ultimo decrescente',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. È possibile fare clic sul titolo del prodotto per trascinarlo e ordinarlo e visualizzarlo in ordine',
            '2. Non è consigliabile aggiungere molti prodotti, che possono causare larea vuota sul lato sinistro del PC per essere troppo grande',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. Per impostazione predefinita, [Nome utente, Telefono cellulare, Email] viene utilizzato come utente unico',
            '2. Se abilitato, aggiungere [System ID] e renderlo unico per lutente',
        ],
        'extends_crontab_tips'                  => 'Si consiglia di aggiungere lindirizzo dello script alla richiesta di sincronizzazione delle attività pianificate di Linux. (Il risultato è SUCS: 0, FAIL: 0, seguito dai due punti, è il numero di pezzi di dati elaborati. SUCS riuscito, FALI fallito.)',
        'left_images_random_tips'               => 'Limmagine a sinistra può caricare fino a 3 immagini, una delle quali può essere visualizzata casualmente ogni volta',
        'background_color_tips'                 => 'Immagine di sfondo personalizzabile, sfondo predefinito grigio',
        'site_setup_layout_tips'                => 'La modalità Drag and Drop richiede di accedere alla pagina di progettazione della homepage da soli. Salvare la configurazione selezionata prima di procedere',
        'site_setup_layout_button_name'         => 'Vai alla pagina di progettazione>>',
        'site_setup_goods_category_tips'        => 'Per ulteriori display da pavimento, vai su/Gestione del prodotto ->Classificazione del prodotto, Impostazioni della classificazione primaria',
        'site_setup_goods_category_no_data_tips'=> 'Nessun dato disponibile, vai a/Gestione del prodotto ->Classificazione del prodotto, Impostazioni della classificazione primaria Home Page per le raccomandazioni',
        'site_setup_order_default_payment_tips' => 'È possibile impostare metodi di pagamento predefiniti corrispondenti a diverse piattaforme. Installare prima il plug-in di pagamento in [Gestione del sito ->Metodi di pagamento] per abilitarlo e renderlo disponibile agli utenti',
        'site_setup_choice_payment_message'     => 'Seleziona {:name} metodo di pagamento predefinito',
        'sitetype_top_tips_list'                => [
            '1. Consegna espressa e processi di e-commerce convenzionali, in cui gli utenti selezionano un indirizzo di spedizione per effettuare un ordine di pagamento -> spedizione commerciante -> conferma ricevuta -> completamento dellordine',
            '2. tipo di visualizzazione, esposizione solo del prodotto, la consultazione può essere iniziata (gli ordini non possono essere effettuati)',
            '3. Selezionare lindirizzo di ritiro automatico quando si effettua un ordine, e lutente effettua un ordine di pagamento -> Conferma la consegna -> Completamento dellordine',
            '4. vendite virtuali, processi regolari di e-commerce, ordini degli utenti per il pagamento -> spedizione automatica -> conferma del ritiro -> completamento dellordine',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'Raccomandato 300 * 300px',
        'form_take_address_alias'               => 'alias',
        'form_take_address_alias_message'       => 'Formato Alias fino a 16 caratteri',
        'form_take_address_name'                => 'contatti',
        'form_take_address_name_message'        => 'Il formato di contatto è compreso tra 2 e 16 caratteri',
        'form_take_address_tel'                 => 'numero di contatto',
        'form_take_address_tel_message'         => 'Inserisci il numero di contatto',
        'form_take_address_address'             => 'Indirizzo dettagliato',
        'form_take_address_address_message'     => 'Il formato dettagliato dellindirizzo è compreso tra 1 e 80 caratteri',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Accesso in background',
        'admin_login_info_bg_images_list_tips'  => [
            'Limmagine di sfondo si trova nella directory [public/static/admin/default/images/login]',
            'Regole di denominazione per le immagini di sfondo (1-50), ad esempio 1.jpg',
        ],
        'map_type_tips'                         => 'A causa dei diversi standard di mappa di ogni azienda, si prega di non cambiare mappe casualmente, il che potrebbe portare a unetichettatura imprecisa delle coordinate mappa.',
        'apply_map_baidu_name'                  => 'Si prega di applicare alla piattaforma aperta Baidu Map',
        'apply_map_amap_name'                   => 'Si prega di fare domanda alla piattaforma aperta Gaode Map',
        'apply_map_tencent_name'                => 'Si prega di fare domanda presso Tencent Map Open Platform',
        'apply_map_tianditu_name'               => 'Si prega di fare domanda presso Tiantu Open Platform',
        'cookie_domain_list_tips'               => [
            '"Se il valore predefinito è vuoto, è valido solo per il nome di dominio attualmente accessibile."',
            '2. Se hai bisogno di un nome di dominio secondario per condividere i cookie, inserisci il nome di dominio di primo livello, ad esempio baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'marca',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'nome',
            'describe'             => 'descrivere',
            'logo'                 => 'LOGO',
            'url'                  => 'Indirizzo del sito ufficiale',
            'brand_category_text'  => 'Classificazione del marchio',
            'is_enable'            => 'Abilitato o no',
            'sort'                 => 'ordinamento',
            'add_time'             => 'Tempo di creazione',
            'upd_time'             => 'Tempo di aggiornamento',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Classificazione del marchio',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'articolo',
        'detail_content_title'                  => 'Dettagli',
        'detail_images_title'                   => 'Quadro dettagliato',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'titolo',
            'jump_url'               => 'Indirizzo URL di salto',
            'article_category_name'  => 'classificazione',
            'is_enable'              => 'Abilitato o no',
            'is_home_recommended'    => 'Raccomandazione per la pagina iniziale',
            'images_count'           => 'Numero di immagini',
            'access_count'           => 'Numero di visitatori',
            'add_time'               => 'Tempo di creazione',
            'upd_time'               => 'Tempo di aggiornamento',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Classificazione degli articoli',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Pagina personalizzata',
        'detail_content_title'                  => 'Dettagli',
        'detail_images_title'                   => 'Quadro dettagliato',
        'save_view_tips'                        => 'Salva prima di visualizzare lanteprima delleffetto',
        // 动态表格
        'form_table'                            => [
            'info'            => 'titolo',
            'is_enable'       => 'Abilitato o no',
            'is_header'       => 'Testa o no',
            'is_footer'       => 'Coda',
            'is_full_screen'  => 'Indica se lo schermo è pieno',
            'images_count'    => 'Numero di immagini',
            'access_count'    => 'Numero di visitatori',
            'add_time'        => 'Tempo di creazione',
            'upd_time'        => 'Tempo di aggiornamento',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Scarica altri modelli di design',
        'upload_list_tips'                      => [
            '1. Selezionare il pacchetto zip di design della pagina scaricato',
            '2. Limportazione aggiungerà automaticamente un nuovo pezzo di dati',
        ],
        'operate_sync_tips'                     => 'La sincronizzazione dei dati con la visualizzazione di trascinamento della homepage e le successive modifiche ai dati non sono interessate, ma non eliminano gli allegati correlati',
        // 动态表格
        'form_table'                            => [
            'id'                => 'ID dati',
            'info'              => 'Informazioni di base',
            'info_placeholder'  => 'Inserisci un nome',
            'access_count'      => 'Numero di visitatori',
            'is_enable'         => 'Abilitato o no',
            'is_header'         => 'Compresa la testa',
            'is_footer'         => 'Compresa la coda',
            'seo_title'         => 'Titolo SEO',
            'seo_keywords'      => 'Parola chiave SEO',
            'seo_desc'          => 'Descrizione SEO',
            'add_time'          => 'Tempo di creazione',
            'upd_time'          => 'Tempo di aggiornamento',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Domande e risposte',
        'user_info_title'                       => 'Informazioni utente',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informazioni utente',
            'user_placeholder'  => 'Inserisci nome utente/nickname/telefono/email',
            'name'              => 'contatti',
            'tel'               => 'numero di contatto',
            'content'           => 'contenuto',
            'reply'             => 'Contenuto della risposta',
            'is_show'           => 'Indica se visualizzare',
            'is_reply'          => 'Rispondi o no',
            'reply_time_time'   => 'Tempo di risposta',
            'access_count'      => 'Numero di visitatori',
            'add_time_time'     => 'Tempo di creazione',
            'upd_time_time'     => 'Tempo di aggiornamento',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Magazzino',
        'top_tips_list'                         => [
            '"Maggiore è il valore del peso, maggiore è il peso. Linventario è dedotto in ordine di peso."',
            '2. Il magazzino può essere cancellato solo in modo soft, non sarà disponibile dopo la cancellazione e solo i dati contenuti nel database possono essere conservati. È possibile eliminare i dati relativi al prodotto da soli',
            '3. La disattivazione e la cancellazione del magazzino e linventario delle merci associate saranno immediatamente rilasciati',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Nome/Alias',
            'level'          => 'peso',
            'is_enable'      => 'Abilitato o no',
            'contacts_name'  => 'contatti',
            'contacts_tel'   => 'numero di contatto',
            'province_name'  => 'Provincia',
            'city_name'      => 'Città',
            'county_name'    => 'Distretto/contea',
            'address'        => 'Indirizzo dettagliato',
            'position'       => 'Longitudine e latitudine',
            'add_time'       => 'Tempo di creazione',
            'upd_time'       => 'Tempo di aggiornamento',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Selezionare un magazzino',
        ],
        // 基础
        'add_goods_title'                       => 'Aggiunta del prodotto',
        'no_spec_data_tips'                     => 'Nessun dato specifico',
        'batch_setup_inventory_placeholder'     => 'Valori set di lotti',
        'base_spec_inventory_title'             => 'Inventario delle specifiche',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Informazioni di base',
            'goods_placeholder'  => 'Inserisci il nome/modello del prodotto',
            'warehouse_name'     => 'Magazzino',
            'is_enable'          => 'Abilitato o no',
            'inventory'          => 'Inventario totale',
            'spec_inventory'     => 'Inventario delle specifiche',
            'add_time'           => 'Tempo di creazione',
            'upd_time'           => 'Tempo di aggiornamento',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'Le informazioni dellamministratore non esistono',
        // 列表
        'top_tips_list'                         => [
            '1. Laccount amministratore ha tutte le autorizzazioni per impostazione predefinita e non può essere modificato.',
            '2. Laccount amministratore non può essere modificato, ma il nome utente del campo ('.MyConfig('database.connections.mysql.prefix').'admin) può essere modificato nella tabella dati',
        ],
        'base_nav_title'                        => 'amministratori',
        // 登录
        'login_type_username_title'             => 'Password dellaccount',
        'login_type_mobile_title'               => 'Codice di verifica mobile',
        'login_type_email_title'                => 'Codice di verifica e-mail',
        'login_close_tips'                      => 'Accesso temporaneamente chiuso',
        // 忘记密码
        'form_forget_password_name'             => 'Hai dimenticato la password?',
        'form_forget_password_tips'             => 'Contatta il tuo amministratore per reimpostare la password',
        // 动态表格
        'form_table'                            => [
            'username'              => 'amministratori',
            'status'                => 'stato',
            'gender'                => 'Sesso',
            'mobile'                => 'telefono cellulare',
            'email'                 => 'cassetta postale',
            'role_name'             => 'Gruppi di ruolo',
            'login_total'           => 'Numero di accessi',
            'login_time'            => 'Ultimo accesso',
            'add_time'              => 'Tempo di creazione',
            'upd_time'              => 'Tempo di aggiornamento',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Accordo di registrazione dellutente', 'type' => 'register'],
            ['name' => 'Informativa sulla privacy degli utenti', 'type' => 'privacy'],
            ['name' => 'Accordo di cancellazione del conto', 'type' => 'logout']
        ],
        'top_tips'          => 'Aggiungere parametro è allindirizzo del protocollo di accesso front-end_ Content=1 mostra solo il contenuto del protocollo',
        'view_detail_name'                      => 'Visualizza dettagli',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Configurazione di base', 'type' => 'index'],
            ['name' => 'APP/applet', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Tema attuale', 'type' => 'index'],
            ['name' => 'Installazione tema', 'type' => 'upload'],
            ['name' => 'Scarica pacchetto sorgente', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Altri download dei temi',
        'nav_theme_download_name'               => 'Visualizza il tutorial sullimballaggio dellapplet',
        'nav_theme_download_tips'               => 'Il tema del telefono cellulare è sviluppato utilizzando uniapp (supporto multi terminale applet + H5), e lapp è anche in adattamento di emergenza.',
        'form_alipay_extend_title'              => 'Configurazione del servizio clienti',
        'form_alipay_extend_tips'               => 'PS: Se è abilitato in [APP/applet] (per abilitare il servizio clienti online), la configurazione seguente deve essere compilata con [Enterprise Code] e [Chat Window Code]',
        'form_theme_upload_tips'                => 'Carica un pacchetto di installazione compresso zip',
        'list_no_data_tips'                     => 'Nessun pacchetto tematico correlato',
        'list_author_title'                     => 'autore',
        'list_version_title'                    => 'Versione applicabile',
        'package_generate_tips'                 => 'Il tempo di generazione è relativamente lungo, si prega di non chiudere la finestra del browser!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Nome pacchetto',
            'size'  => 'dimensione',
            'url'   => 'Indirizzo di download',
            'time'  => 'Tempo di creazione',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Impostazioni SMS', 'type' => 'index'],
            ['name' => 'Modello di messaggio', 'type' => 'message'],
        ],
        'top_tips'                              => 'Indirizzo di gestione SMS Alibaba Cloud',
        'top_to_aliyun_tips'                    => 'Clicca per acquistare SMS da AliCloud',
        'base_item_admin_title'                 => 'backstage',
        'base_item_index_title'                 => 'anteriore',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Impostazioni casella postale', 'type' => 'index'],
            ['name' => 'Modello di messaggio', 'type' => 'message'],
        ],
        'top_tips'                              => 'A causa di alcune differenze e configurazioni tra diverse piattaforme di cassette postali, prevarrà il tutorial di configurazione specifico per la piattaforma di cassette postali',
        // 基础
        'test_title'                            => 'prova',
        'test_content'                          => 'Configurazione posta - Invia contenuto di prova',
        'base_item_admin_title'                 => 'backstage',
        'base_item_index_title'                 => 'anteriore',
        // 表单
        'form_item_test'                        => 'Verifica lindirizzo email ricevuto',
        'form_item_test_tips'                   => 'Si prega di salvare la configurazione prima del test',
        'form_item_test_button_title'           => 'prova',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Configurare regole pseudo statiche corrispondenti in base ai diversi ambienti server [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'merce',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Informazioni di base', 'type'=>'base'],
            'specifications'  => ['name' => 'Specifiche del prodotto', 'type'=>'specifications'],
            'parameters'      => ['name' => 'Parametri del prodotto', 'type'=>'parameters'],
            'photo'           => ['name' => 'Album prodotto', 'type'=>'photo'],
            'video'           => ['name' => 'Video prodotto', 'type'=>'video'],
            'app'             => ['name' => 'Stato del dispositivo', 'type'=>'app'],
            'web'             => ['name' => 'Dettagli del computer', 'type'=>'web'],
            'fictitious'      => ['name' => 'Informazioni virtuali', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Dati estesi', 'type'=>'extends'],
            'seo'             => ['name' => 'Informazioni SEO', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'ID prodotto',
            'info'                    => 'Informazioni sul prodotto',
            'category_text'           => 'Classificazione delle merci',
            'brand_name'              => 'marca',
            'price'                   => 'Prezzo di vendita (RMB)',
            'original_price'          => 'Prezzo originale (yuan)',
            'inventory'               => 'Inventario totale',
            'is_shelves'              => 'Ripiani superiori e inferiori',
            'is_deduction_inventory'  => 'Detrazione dellinventario',
            'site_type'               => 'Tipo di prodotto',
            'model'                   => 'Modello di prodotto',
            'place_origin_name'       => 'Luogo di produzione',
            'give_integral'           => 'Percentuale punti bonus di acquisto',
            'buy_min_number'          => 'Quantità minima di acquisto per volta',
            'buy_max_number'          => 'Quantità massima di acquisto singolo',
            'sales_count'             => 'volume delle vendite',
            'access_count'            => 'Numero di visitatori',
            'add_time'                => 'Tempo di creazione',
            'upd_time'                => 'Tempo di aggiornamento',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Classificazione delle merci',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Recensioni dei prodotti',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informazioni utente',
            'user_placeholder'   => 'Inserisci nome utente/nickname/telefono/email',
            'goods'              => 'Informazioni di base',
            'goods_placeholder'  => 'Inserisci il nome/modello del prodotto',
            'business_type'      => 'Tipo di attività',
            'content'            => 'Contenuto dei commenti',
            'images'             => 'Comment Image',
            'rating'             => 'punteggio',
            'reply'              => 'Contenuto della risposta',
            'is_show'            => 'Indica se visualizzare',
            'is_anonymous'       => 'Anonimo o no',
            'is_reply'           => 'Rispondi o no',
            'reply_time_time'    => 'Tempo di risposta',
            'add_time_time'      => 'Tempo di creazione',
            'upd_time_time'      => 'Tempo di aggiornamento',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Parametri del prodotto',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Classificazione delle merci',
            'name'          => 'nome',
            'is_enable'     => 'Abilitato o no',
            'config_count'  => 'Numero di parametri',
            'add_time'      => 'Tempo di creazione',
            'upd_time'      => 'Tempo di aggiornamento',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Classificazione delle merci',
            'name'         => 'nome',
            'is_enable'    => 'Abilitato o no',
            'content'      => 'Valore della specificazione',
            'add_time'     => 'Tempo di creazione',
            'upd_time'     => 'Tempo di aggiornamento',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informazioni utente',
            'user_placeholder'   => 'Inserisci nome utente/nickname/telefono/email',
            'goods'              => 'Informazioni sul prodotto',
            'goods_placeholder'  => 'Inserisci il nome del prodotto/breve descrizione/informazioni SEO',
            'price'              => 'Prezzo di vendita (RMB)',
            'original_price'     => 'Prezzo originale (yuan)',
            'add_time'           => 'Tempo di creazione',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informazioni utente',
            'user_placeholder'   => 'Inserisci nome utente/nickname/telefono/email',
            'goods'              => 'Informazioni sul prodotto',
            'goods_placeholder'  => 'Inserisci il nome del prodotto/breve descrizione/informazioni SEO',
            'price'              => 'Prezzo di vendita (RMB)',
            'original_price'     => 'Prezzo originale (yuan)',
            'add_time'           => 'Tempo di creazione',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informazioni utente',
            'user_placeholder'   => 'Inserisci nome utente/nickname/telefono/email',
            'goods'              => 'Informazioni sul prodotto',
            'goods_placeholder'  => 'Inserisci il nome del prodotto/breve descrizione/informazioni SEO',
            'price'              => 'Prezzo di vendita (RMB)',
            'original_price'     => 'Prezzo originale (yuan)',
            'add_time'           => 'Tempo di creazione',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Link amichevoli',
        // 动态表格
        'form_table'                            => [
            'info'                => 'nome',
            'url'                 => 'Indirizzo URL',
            'describe'            => 'descrivere',
            'is_enable'           => 'Abilitato o no',
            'is_new_window_open'  => 'Indica se si apre una nuova finestra',
            'sort'                => 'ordinamento',
            'add_time'            => 'Tempo di creazione',
            'upd_time'            => 'Tempo di aggiornamento',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Navigazione intermedia', 'type' => 'header'],
            ['name' => 'Navigazione in basso', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'personalizzato',
            'article'           => 'articolo',
            'customview'        => 'Pagina personalizzata',
            'goods_category'    => 'Classificazione delle merci',
            'design'            => 'Progettazione pagina',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Nome di navigazione',
            'data_type'           => 'Tipo di dati di navigazione',
            'is_show'             => 'Indica se visualizzare',
            'is_new_window_open'  => 'Nuova finestra aperta',
            'sort'                => 'ordinamento',
            'add_time'            => 'Tempo di creazione',
            'upd_time'            => 'Tempo di aggiornamento',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'LID dellordine non è corretto',
            'express_choice_tips'               => 'Seleziona un metodo di consegna',
            'payment_choice_tips'               => 'Seleziona un metodo di pagamento',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Operazioni di spedizione',
        'form_payment_title'                    => 'Operazioni di pagamento',
        'form_item_take'                        => 'Codice di prelievo',
        'form_item_take_message'                => 'Inserisci il codice di ritiro a 4 cifre',
        'form_item_express_number'              => 'numero del corriere',
        'form_item_express_number_message'      => 'Si prega di compilare il numero di fattura espresso',
        // 地址
        'detail_user_address_title'             => 'Indirizzo di spedizione',
        'detail_user_address_name'              => 'destinatario',
        'detail_user_address_tel'               => 'Ricezione del telefono',
        'detail_user_address_value'             => 'Indirizzo dettagliato',
        'detail_user_address_idcard'            => 'Informazioni sulla carta didentità',
        'detail_user_address_idcard_name'       => 'nome completo',
        'detail_user_address_idcard_number'     => 'numero',
        'detail_user_address_idcard_pic'        => 'Foto',
        'detail_take_address_title'             => 'Indirizzo di ritiro',
        'detail_take_address_contact'           => 'Informazioni di contatto',
        'detail_take_address_value'             => 'Indirizzo dettagliato',
        'detail_fictitious_title'               => 'Informazioni chiave',
        // 订单售后
        'detail_aftersale_status'               => 'stato',
        'detail_aftersale_type'                 => 'tipo',
        'detail_aftersale_price'                => 'importo del denaro',
        'detail_aftersale_number'               => 'quantità',
        'detail_aftersale_reason'               => 'ragione',
        // 商品
        'detail_goods_title'                    => 'Oggetto dellordine',
        'detail_payment_amount_less_tips'       => 'Si prega di notare che limporto del pagamento dellordine è inferiore allimporto totale',
        'detail_no_payment_tips'                => 'Si prega di notare che lordine non è stato ancora pagato',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Informazioni di base',
            'goods_placeholder'   => 'Inserisci lID dellordine/numero dordine/nome del prodotto/modello',
            'user'                => 'Informazioni utente',
            'user_placeholder'    => 'Inserisci nome utente/nickname/telefono/email',
            'status'              => 'Stato dellordine',
            'pay_status'          => 'Stato del pagamento',
            'total_price'         => 'Prezzo totale (yuan)',
            'pay_price'           => 'Importo del pagamento (yuan)',
            'price'               => 'Prezzo unitario (yuan)',
            'warehouse_name'      => 'Deposito di spedizione',
            'order_model'         => 'Modalità ordine',
            'client_type'         => 'fonte',
            'address'             => 'Informazioni sullindirizzo',
            'take'                => 'Informazioni sul ritiro',
            'refund_price'        => 'Importo del rimborso (yuan)',
            'returned_quantity'   => 'Quantità restituita',
            'buy_number_count'    => 'Totale acquisti',
            'increase_price'      => 'Aumento dellimporto (yuan)',
            'preferential_price'  => 'Importo dello sconto (yuan)',
            'payment_name'        => 'Metodo di pagamento',
            'user_note'           => 'Commenti degli utenti',
            'extension'           => 'Informazioni estese',
            'express_name'        => 'Società di servizi di corriere',
            'express_number'      => 'numero del corriere',
            'aftersale'           => 'Ultimo servizio post-vendita',
            'is_comments'         => 'Indica se lutente commenta',
            'confirm_time'        => 'Tempo di conferma',
            'pay_time'            => 'Tempi di pagamento',
            'delivery_time'       => 'Tempi di consegna',
            'collect_time'        => 'Tempo di completamento',
            'cancel_time'         => 'Annulla ora',
            'close_time'          => 'Orario di chiusura',
            'add_time'            => 'Tempo di creazione',
            'upd_time'            => 'Tempo di aggiornamento',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'Azione di audit',
        'form_refuse_title'                     => 'Rifiuta operazione',
        'form_user_info_title'                  => 'Informazioni utente',
        'form_apply_info_title'                 => 'Informazioni sullapplicazione',
        'forn_apply_info_type'                  => 'tipo',
        'forn_apply_info_price'                 => 'importo del denaro',
        'forn_apply_info_number'                => 'quantità',
        'forn_apply_info_reason'                => 'ragione',
        'forn_apply_info_msg'                   => 'spiegare',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Informazioni di base',
            'goods_placeholder'  => 'Inserisci il numero dordine/nome del prodotto/modello',
            'user'               => 'Informazioni utente',
            'user_placeholder'   => 'Inserisci nome utente/nickname/telefono/email',
            'status'             => 'stato',
            'type'               => 'Tipo di applicazione',
            'reason'             => 'ragione',
            'price'              => 'Importo del rimborso (yuan)',
            'number'             => 'Quantità restituita',
            'msg'                => 'Istruzioni per il rimborso',
            'refundment'         => 'Tipo di restituzione',
            'voucher'            => 'voucher',
            'express_name'       => 'Società di servizi di corriere',
            'express_number'     => 'numero del corriere',
            'refuse_reason'      => 'Motivo del rifiuto',
            'apply_time'         => 'Tempo di applicazione',
            'confirm_time'       => 'Tempo di conferma',
            'delivery_time'      => 'Tempo di ritorno',
            'audit_time'         => 'Tempo di audit',
            'add_time'           => 'Tempo di creazione',
            'upd_time'           => 'Tempo di aggiornamento',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'Metodo di pagamento',
        'nav_store_payment_name'                => 'Altri download dei temi',
        'upload_top_list_tips'                  => [
            [
                'name'  => '"Il nome della classe deve essere coerente con il nome del file (remove. php). Se Alipay.php è usato, Alipay è usato."',
            ],
            [
                'name'  => '2. Metodi che una classe deve definire',
                'item'  => [
                    '2.1. Metodo di configurazione di configurazione',
                    '2.2. Metodo di pagamento',
                    '2.3. Metodo di richiamo della risposta',
                    '2.4. Notificare il metodo di callback asincrono (facoltativo, chiamare il metodo di risposta se non definito)',
                    '2.5. Metodo di rimborso (facoltativo, se non definito, il rimborso originale del percorso non può essere avviato)',
                ],
            ],
            [
                'name'  => '3. Metodo di contenuto di output personalizzabile',
                'item'  => [
                    '3.1. Pagamento di reso riuscito (facoltativo)',
                    '3.2. ErroreRitorno di pagamento fallito (facoltativo)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: Se le condizioni di cui sopra non sono soddisfatte, il plug-in non può essere visualizzato. Caricare il plug-in in un pacchetto compresso. zip e supportare più plug-in di pagamento in una sola compressione',
        // 动态表格
        'form_table'                            => [
            'name'            => 'nome',
            'logo'            => 'LOGO',
            'version'         => 'edizione',
            'apply_version'   => 'Versione applicabile',
            'apply_terminal'  => 'Terminali applicabili',
            'author'          => 'autore',
            'desc'            => 'descrivere',
            'enable'          => 'Abilitato o no',
            'open_user'       => 'Aperto agli utenti',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'espresso',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Tema attuale', 'type' => 'index'],
            ['name' => 'Installazione tema', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'Altri download dei temi',
        'list_author_title'                     => 'autore',
        'list_version_title'                    => 'Versione applicabile',
        'form_theme_upload_tips'                => 'Carica un pacchetto di installazione del tema compresso zip',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Navigazione del centro utente mobile',
        // 动态表格
        'form_table'                            => [
            'name'           => 'nome',
            'platform'       => 'Piattaforma',
            'images_url'     => 'Icona di navigazione',
            'event_type'     => 'Tipo evento',
            'event_value'    => 'Valore evento',
            'desc'           => 'descrivere',
            'is_enable'      => 'Abilitato o no',
            'is_need_login'  => 'Hai bisogno di accedere',
            'sort'           => 'ordinamento',
            'add_time'       => 'Tempo di creazione',
            'upd_time'       => 'Tempo di aggiornamento',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Navigazione della casa mobile',
        // 动态表格
        'form_table'                            => [
            'name'           => 'nome',
            'platform'       => 'Piattaforma',
            'images'         => 'Icona di navigazione',
            'event_type'     => 'Tipo evento',
            'event_value'    => 'Valore evento',
            'is_enable'      => 'Abilitato o no',
            'is_need_login'  => 'Hai bisogno di accedere',
            'sort'           => 'ordinamento',
            'add_time'       => 'Tempo di creazione',
            'upd_time'       => 'Tempo di aggiornamento',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Registro delle richieste di pagamento',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informazioni utente',
            'user_placeholder'  => 'Inserisci nome utente/nickname/telefono/email',
            'log_no'            => 'Numero dordine di pagamento',
            'payment'           => 'Metodo di pagamento',
            'status'            => 'stato',
            'total_price'       => 'Importo dellordine commerciale (yuan)',
            'pay_price'         => 'Importo del pagamento (yuan)',
            'business_type'     => 'Tipo di attività',
            'business_list'     => 'ID aziendale/Doc n.',
            'trade_no'          => 'Numero di transazione della piattaforma di pagamento',
            'buyer_user'        => 'Account utente della piattaforma di pagamento',
            'subject'           => 'Nome dellordine',
            'pay_time'          => 'Tempi di pagamento',
            'close_time'        => 'Orario di chiusura',
            'add_time'          => 'Tempo di creazione',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Registro delle richieste di pagamento',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Tipo di attività',
            'request_params'   => 'Parametri di richiesta',
            'response_data'    => 'Dati di risposta',
            'business_handle'  => 'Risultati dellelaborazione aziendale',
            'request_url'      => 'Indirizzo URL richiesta',
            'server_port'      => 'Numero porta',
            'server_ip'        => 'IP server',
            'client_ip'        => 'IP client',
            'os'               => 'sistema operativo',
            'browser'          => 'browser',
            'method'           => 'Tipo di richiesta',
            'scheme'           => 'Tipo Http',
            'version'          => 'Versione Http',
            'client'           => 'Dettagli del cliente',
            'add_time'         => 'Tempo di creazione',
            'upd_time'         => 'Tempo di aggiornamento',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informazioni utente',
            'user_placeholder'  => 'Inserisci nome utente/nickname/telefono/email',
            'payment'           => 'Metodo di pagamento',
            'business_type'     => 'Tipo di attività',
            'business_id'       => 'ID dellordine aziendale',
            'trade_no'          => 'Numero di transazione della piattaforma di pagamento',
            'buyer_user'        => 'Account utente della piattaforma di pagamento',
            'refundment_text'   => 'Metodo di restituzione',
            'refund_price'      => 'importo del rimborso',
            'pay_price'         => 'Importo del pagamento dellordine',
            'msg'               => 'descrivere',
            'add_time_time'     => 'Tempo di rimborso',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Torna alla gestione delle applicazioni>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Fare clic prima sul segno di spunta per abilitare',
            'save_no_data_tips'                 => 'Nessun dato plugin da salvare',
        ],
        // 基础导航
        'base_nav_title'                        => 'applicazione',
        'base_nav_list'                         => [
            ['name' => 'Gestione delle applicazioni', 'type' => 'index'],
            ['name' => 'Carica app', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'Altri download di plug-in',
        // 基础页面
        'base_search_input_placeholder'         => 'Inserisci un nome/descrizione',
        'base_top_tips_one'                     => 'Metodo di ordinamento degli elenchi [ordinamento personalizzato ->prima installazione]',
        'base_top_tips_two'                     => 'Fare clic e trascinare i pulsanti dellicona per regolare linvocazione del plug-in e lordine di visualizzazione',
        'base_open_sort_title'                  => 'Abilita ordinamento',
        'data_list_author_title'                => 'autore',
        'data_list_author_url_title'            => 'homepage',
        'data_list_version_title'               => 'edizione',
        'uninstall_confirm_tips'                => 'La disinstallazione può causare la perdita dei dati di configurazione di base del plug-in che non possono essere recuperati.',
        'not_install_divide_title'              => 'I seguenti plug-in non sono installati',
        'delete_plugins_text'                   => '1. Elimina solo le app',
        'delete_plugins_text_tips'              => '(Cancellare solo il codice dellapplicazione e conservare i dati dellapplicazione)',
        'delete_plugins_data_text'              => '2. Cancellare lapplicazione e cancellare i dati',
        'delete_plugins_data_text_tips'         => '(Il codice dellapplicazione e i dati dellapplicazione saranno cancellati)',
        'delete_plugins_ps_tips'                => 'PS: Nessuna delle seguenti operazioni può essere recuperata, si prega di operare con cautela!',
        'delete_plugins_button_name'            => 'Elimina solo app',
        'delete_plugins_data_button_name'       => 'Elimina app e dati',
        'cancel_delete_plugins_button_name'     => 'Ripensaci',
        'more_plugins_store_to_text'            => 'Vai allapp store per scegliere altri plugin per arricchire il sito>>',
        'no_data_store_to_text'                 => 'Vai allapp store per selezionare i siti ricchi di plug-in>>',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Torna allo sfondo',
        'get_loading_tips'                      => 'Ottenere',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'ruolo',
        'admin_not_modify_tips'                 => 'Il super amministratore ha tutte le autorizzazioni di default e non può essere cambiato.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Nome ruolo',
            'is_enable'  => 'Abilitato o no',
            'add_time'   => 'Tempo di creazione',
            'upd_time'   => 'Tempo di aggiornamento',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'giurisdizione',
        'top_tips_list'                         => [
            '1. Il personale tecnico non professionale non dovrebbe utilizzare i dati di questa pagina. Il cattivo funzionamento può causare confusione nel menu di autorizzazione.',
            '2. I menu delle autorizzazioni sono divisi in due tipi: [Uso e Operazione]. Il menu di utilizzo è generalmente visualizzato e il menu delle operazioni deve essere nascosto.',
            '3. Se il menu autorizzazioni è disordinato, è possibile sovrascrivere nuovamente il recupero dati della tabella dati ['.MyConfig('database.connections.mysql.prefix').'power].',
            '4. [Super amministratore, account amministratore] ha tutte le autorizzazioni di default e non può essere modificato.',
        ],
        'content_top_tips_list'                 => [
            '"Per compilare [Nome controller e Nome metodo], è necessario creare definizioni corrispondenti per il controller e il metodo."',
            '2. Posizione del file controller [app/admin/controller]. Questa operazione è utilizzata solo dagli sviluppatori',
            '"Deve essere compilato il nome del controller/nome del metodo o lindirizzo URL definito dallutente."',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Navigazione rapida',
        // 动态表格
        'form_table'                            => [
            'name'         => 'nome',
            'platform'     => 'Piattaforma',
            'images'       => 'Icona di navigazione',
            'event_type'   => 'Tipo evento',
            'event_value'  => 'Valore evento',
            'is_enable'    => 'Abilitato o no',
            'sort'         => 'ordinamento',
            'add_time'     => 'Tempo di creazione',
            'upd_time'     => 'Tempo di aggiornamento',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'regione',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Prezzo filtro',
        'top_tips_list'                         => [
            'Prezzo minimo 0 - prezzo massimo 100 è inferiore a 100',
            'Prezzo minimo 1000 - prezzo massimo 0 è superiore a 1000',
            'Il prezzo minimo di 100 - il prezzo massimo di 500 è superiore o uguale a 100 e inferiore a 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Rotazione',
        // 动态表格
        'form_table'                            => [
            'name'         => 'nome',
            'platform'     => 'Piattaforma',
            'images'       => 'picture',
            'event_type'   => 'Tipo evento',
            'event_value'  => 'Valore evento',
            'is_enable'    => 'Abilitato o no',
            'sort'         => 'ordinamento',
            'add_time'     => 'Tempo di creazione',
            'upd_time'     => 'Tempo di aggiornamento',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Informazioni utente',
            'user_placeholder'    => 'Inserisci nome utente/nickname/telefono/email',
            'type'                => 'Tipo di operazione',
            'operation_integral'  => 'Integrale operativo',
            'original_integral'   => 'Integrale originale',
            'new_integral'        => 'Ultimi punti',
            'msg'                 => 'Motivo delloperazione',
            'operation_id'        => 'ID operatore',
            'add_time_time'       => 'Tempo di funzionamento',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Informazioni utente',
            'user_placeholder'          => 'Inserisci nome utente/nickname/telefono/email',
            'type'                      => 'Tipo messaggio',
            'business_type'             => 'Tipo di attività',
            'title'                     => 'titolo',
            'detail'                    => 'dettagli',
            'is_read'                   => 'Leggere o meno',
            'user_is_delete_time_text'  => 'Indica se eliminare lutente',
            'add_time_time'             => 'Tempo di invio',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: I non sviluppatori non dovrebbero eseguire alcuna istruzione SQL a piacimento, in quanto loperazione potrebbe causare leliminazione dellintero database di sistema.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'Un elenco di eccellenti applicazioni ShopXO è una raccolta degli sviluppatori ShopXO più esperti, tecnicamente capaci e affidabili, fornendo una escort completa per la personalizzazione di plug-in, stile e template.',
        'to_store_name'                         => 'Vai allapp store per selezionare i plug-in>>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Sistema di gestione del contesto',
        'remove_cache_title'                    => 'Cancella cache',
        'user_status_title'                     => 'Stato utente',
        'user_status_message'                   => 'Seleziona lo stato utente',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Informazioni sulla configurazione dei parametri del prodotto',
        'form_goods_params_copy_no_tips'        => 'Incolla prima le informazioni di configurazione',
        'form_goods_params_copy_error_tips'     => 'Errore formato di configurazione',
        'form_goods_params_type_message'        => 'Seleziona il tipo di visualizzazione di un parametro del prodotto',
        'form_goods_params_params_name'         => 'Nome parametro',
        'form_goods_params_params_message'      => 'Inserisci il nome del parametro',
        'form_goods_params_value_name'          => 'Valore parametro',
        'form_goods_params_value_message'       => 'Inserisci il valore del parametro',
        'form_goods_params_move_type_tips'      => 'Configurazione del tipo di operazione errata',
        'form_goods_params_move_top_tips'       => 'Raggiunta la cima',
        'form_goods_params_move_bottom_tips'    => 'Raggiunto il fondo',
        'form_goods_params_thead_type_title'    => 'Campo di visualizzazione',
        'form_goods_params_thead_name_title'    => 'Nome parametro',
        'form_goods_params_thead_value_title'   => 'Valore parametro',
        'form_goods_params_row_add_title'       => 'Aggiungi una riga',
        'form_goods_params_list_tips'           => [
            '1. Tutti (visualizzati sotto le informazioni di base e i parametri dettagliati del prodotto)',
            '2. Dettagli (visualizzati solo sotto il parametro dei dettagli del prodotto)',
            '3. Fondamenti (visualizzati solo sotto le informazioni di base sulle materie prime)',
            '4. loperazione di scorciatoia cancellerà i dati originali e ricaricarà la pagina per ripristinare i dati originali (efficace solo dopo aver salvato il prodotto)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Impostazioni di sistema',
            'item'  => [
                'config_index'                 => 'configurazione del sistema',
                'config_store'                 => 'Informazioni sul deposito',
                'config_save'                  => 'Configurazione Salva',
                'index_storeaccountsbind'      => 'Legatura dellaccount di archiviazione',
                'index_inspectupgrade'         => 'Controllo aggiornamento del sistema',
                'index_inspectupgradeconfirm'  => 'Conferma aggiornamento del sistema',
                'index_stats'                  => 'Statistiche della pagina iniziale',
                'index_income'                 => 'Statistiche della pagina iniziale (statistiche sulle entrate)',
            ]
        ],
        'site_index' => [
            'name'  => 'Configurazione sito',
            'item'  => [
                'site_index'                  => 'Impostazioni sito',
                'site_save'                   => 'Modifica impostazioni sito',
                'site_goodssearch'            => 'Impostazioni sito Ricerca prodotto',
                'layout_layoutindexhomesave'  => 'Gestione layout della pagina iniziale',
                'sms_index'                   => 'Impostazioni SMS',
                'sms_save'                    => 'Modifica delle impostazioni SMS',
                'email_index'                 => 'Impostazioni casella postale',
                'email_save'                  => 'Impostazioni/modifica della casella di posta',
                'email_emailtest'             => 'Test di consegna della posta',
                'seo_index'                   => 'Impostazioni SEO',
                'seo_save'                    => 'Modifica impostazioni SEO',
                'agreement_index'             => 'Gestione dei protocolli',
                'agreement_save'              => 'Modifica impostazioni protocollo',
            ]
        ],
        'power_index' => [
            'name'  => 'Controllo autorizzazioni',
            'item'  => [
                'admin_index'        => 'manager',
                'admin_saveinfo'     => 'Admin Aggiungi/Modifica pagina',
                'admin_save'         => 'Amministratore Aggiungi/Modifica',
                'admin_delete'       => 'Amministratore Elimina',
                'admin_detail'       => 'Dettagli dellamministratore',
                'role_index'         => 'Gestione dei ruoli',
                'role_saveinfo'      => 'Pagina Aggiungi/Modifica gruppo di ruoli',
                'role_save'          => 'Aggiungi/modifica gruppo di ruoli',
                'role_delete'        => 'Eliminazione del ruolo',
                'role_statusupdate'  => 'Aggiornamento dello stato del ruolo',
                'role_detail'        => 'Dettagli del ruolo',
                'power_index'        => 'Assegnazione autorizzazione',
                'power_save'         => 'Autorizzazione Aggiungi/Modifica',
                'power_delete'       => 'Eliminazione dei permessi',
            ]
        ],
        'user_index' => [
            'name'  => 'gestione utente',
            'item'  => [
                'user_index'            => 'Elenco utenti',
                'user_saveinfo'         => 'Modifica/Aggiungi pagina utente',
                'user_save'             => 'Utente Aggiungi/Modifica',
                'user_delete'           => 'Elimina utente',
                'user_detail'           => 'Dettagli utente',
                'useraddress_index'     => 'Indirizzo utente',
                'useraddress_saveinfo'  => 'Pagina di modifica indirizzo utente',
                'useraddress_save'      => 'Modifica indirizzi utente',
                'useraddress_delete'    => 'Eliminazione dellindirizzo utente',
                'useraddress_detail'    => 'Dettagli dellindirizzo utente',
            ]
        ],
        'goods_index' => [
            'name'  => 'Gestione delle merci',
            'item'  => [
                'goods_index'                       => 'Gestione delle merci',
                'goods_saveinfo'                    => 'Pagina Aggiungi/Modifica prodotto',
                'goods_save'                        => 'Aggiungi/Modifica prodotto',
                'goods_delete'                      => 'Eliminazione del prodotto',
                'goods_statusupdate'                => 'Aggiornamento dello stato del prodotto',
                'goods_basetemplate'                => 'Ottieni il modello di base del prodotto',
                'goods_detail'                      => 'Dettagli del prodotto',
                'goodscategory_index'               => 'Classificazione delle merci',
                'goodscategory_save'                => 'Categoria di prodotto Aggiungi/Modifica',
                'goodscategory_delete'              => 'Eliminazione della classificazione dei prodotti',
                'goodsparamstemplate_index'         => 'Parametri del prodotto',
                'goodsparamstemplate_delete'        => 'Eliminazione dei parametri del prodotto',
                'goodsparamstemplate_statusupdate'  => 'Aggiornamento dello stato dei parametri del prodotto',
                'goodsparamstemplate_saveinfo'      => 'Parametro del prodotto Aggiungi/Modifica pagina',
                'goodsparamstemplate_save'          => 'Aggiungi/Modifica parametri del prodotto',
                'goodsparamstemplate_detail'        => 'Dettagli dei parametri del prodotto',
                'goodsspectemplate_index'           => 'Specifiche del prodotto',
                'goodsspectemplate_delete'          => 'Eliminazione delle specifiche di prodotto',
                'goodsspectemplate_statusupdate'    => 'Aggiornamento dello stato delle specifiche del prodotto',
                'goodsspectemplate_saveinfo'        => 'Specificazione del prodotto Aggiungi / Modifica pagina',
                'goodsspectemplate_save'            => 'Specificazione del prodotto Aggiungi/Modifica',
                'goodsspectemplate_detail'          => 'Dettagli sulle specifiche del prodotto',
                'goodscomments_detail'              => 'Dettagli della recensione del prodotto',
                'goodscomments_index'               => 'Recensioni dei prodotti',
                'goodscomments_reply'               => 'Risposta alla valutazione del prodotto',
                'goodscomments_delete'              => 'Eliminazione della revisione del prodotto',
                'goodscomments_statusupdate'        => 'Aggiornamento dello stato della revisione del prodotto',
                'goodscomments_saveinfo'            => 'Pagina Aggiungi/Modifica commento prodotto',
                'goodscomments_save'                => 'Commento del prodotto Aggiungi/Modifica',
                'goodsbrowse_index'                 => 'Navigazione dei prodotti',
                'goodsbrowse_delete'                => 'Ricerca del prodotto Elimina',
                'goodsbrowse_detail'                => 'Dettagli di navigazione del prodotto',
                'goodsfavor_index'                  => 'Collezione prodotti',
                'goodsfavor_delete'                 => 'Raccolta di prodotti Elimina',
                'goodsfavor_detail'                 => 'Dettagli della collezione di prodotti',
                'goodscart_index'                   => 'Carrello',
                'goodscart_delete'                  => 'Carrello del prodotto Elimina',
                'goodscart_detail'                  => 'Dettagli del carrello del prodotto',
            ]
        ],
        'order_index' => [
            'name'  => 'Gestione degli ordini',
            'item'  => [
                'order_index'             => 'Gestione degli ordini',
                'order_delete'            => 'Eliminazione dellordine',
                'order_cancel'            => 'Annullamento dellordine',
                'order_delivery'          => 'Spedizione dellordine',
                'order_collect'           => 'Ricevuta dellordine',
                'order_pay'               => 'Pagamento dellordine',
                'order_confirm'           => 'conferma dellordine',
                'order_detail'            => 'Dettagli dellordine',
                'orderaftersale_index'    => 'Ordine post vendita',
                'orderaftersale_delete'   => 'Ordina dopo le vendite Elimina',
                'orderaftersale_cancel'   => 'Annullamento dellordine dopo le vendite',
                'orderaftersale_audit'    => 'Revisione post-vendita dellordine',
                'orderaftersale_confirm'  => 'Conferma post-vendita dellordine',
                'orderaftersale_refuse'   => 'Rifiuto dellordine',
                'orderaftersale_detail'   => 'Dettagli dellordine dopo le vendite',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Gestione siti web',
            'item'  => [
                'navigation_index'         => 'Gestione della navigazione',
                'navigation_save'          => 'Navigazione Aggiungi/Modifica',
                'navigation_delete'        => 'Navigazione Elimina',
                'navigation_statusupdate'  => 'Aggiornamento dello stato della navigazione',
                'customview_index'         => 'Pagina personalizzata',
                'customview_saveinfo'      => 'Pagina personalizzata Aggiungi/Modifica pagina',
                'customview_save'          => 'Aggiungi/modifica pagina personalizzata',
                'customview_delete'        => 'Elimina pagina personalizzata',
                'customview_statusupdate'  => 'Aggiornamento dello stato della pagina personalizzato',
                'customview_detail'        => 'Dettagli pagina personalizzata',
                'link_index'               => 'Link amichevoli',
                'link_saveinfo'            => 'Link amichevole Aggiungi/Modifica pagina',
                'link_save'                => 'Link amichevole Aggiungi/Modifica',
                'link_delete'              => 'Eliminazione amichevole del collegamento',
                'link_statusupdate'        => 'Aggiornamento dello stato del collegamento amichevole',
                'link_detail'              => 'Dettagli del collegamento amichevole',
                'theme_index'              => 'Gestione dei temi',
                'theme_save'               => 'Gestione temi Aggiungi/modifica',
                'theme_upload'             => 'Installazione di caricamento del tema',
                'theme_delete'             => 'Eliminazione tema',
                'theme_download'           => 'Scarica tema',
                'slide_index'              => 'Rotazione pagina iniziale',
                'slide_saveinfo'           => 'Aggiungi/Modifica pagina del sondaggio',
                'slide_save'               => 'Trasmetti Aggiungi/Modifica',
                'slide_statusupdate'       => 'Aggiornamento dello stato del sondaggio',
                'slide_delete'             => 'Elimina sondaggio',
                'slide_detail'             => 'Dettagli della trasmissione',
                'screeningprice_index'     => 'Prezzo filtro',
                'screeningprice_save'      => 'Filtro Prezzo Aggiungi/Modifica',
                'screeningprice_delete'    => 'Filtro Prezzo Elimina',
                'region_index'             => 'Gestione regionale',
                'region_save'              => 'Aggiungi/Modifica regione',
                'region_delete'            => 'Regione Elimina',
                'region_codedata'          => 'Ottenere i dati relativi al numero di area',
                'express_index'            => 'Gestione express',
                'express_save'             => 'Express Aggiungi/Modifica',
                'express_delete'           => 'Elimina espresso',
                'payment_index'            => 'Metodo di pagamento',
                'payment_saveinfo'         => 'Pagina di installazione/modifica del metodo di pagamento',
                'payment_save'             => 'Installazione/modifica del metodo di pagamento',
                'payment_delete'           => 'Metodo di pagamento Elimina',
                'payment_install'          => 'Installazione del metodo di pagamento',
                'payment_statusupdate'     => 'Aggiornamento dello stato del metodo di pagamento',
                'payment_uninstall'        => 'Disinstallazione del metodo di pagamento',
                'payment_upload'           => 'Carica metodo di pagamento',
                'quicknav_index'           => 'Navigazione rapida',
                'quicknav_saveinfo'        => 'Navigazione rapida aggiungi/modifica pagina',
                'quicknav_save'            => 'Navigazione rapida Aggiungi/Modifica',
                'quicknav_statusupdate'    => 'Aggiornamento dello stato della navigazione rapida',
                'quicknav_delete'          => 'Navigazione rapida Elimina',
                'quicknav_detail'          => 'Dettagli di navigazione rapida',
                'design_index'             => 'Progettazione pagina',
                'design_saveinfo'          => 'Progettazione pagina Aggiungi/Modifica pagina',
                'design_save'              => 'Progettazione pagina Aggiungi/modifica',
                'design_statusupdate'      => 'Aggiornamento dello stato della progettazione della pagina',
                'design_upload'            => 'Importa disegno pagina',
                'design_download'          => 'Progettazione pagina Scarica',
                'design_sync'              => 'Pagina iniziale di sincronizzazione del design della pagina',
                'design_delete'            => 'Disegno pagina Elimina',
            ]
        ],
        'brand_index' => [
            'name'  => 'Gestione del marchio',
            'item'  => [
                'brand_index'           => 'Gestione del marchio',
                'brand_saveinfo'        => 'Marca Aggiungi/Modifica pagina',
                'brand_save'            => 'Marca Aggiungi/Modifica',
                'brand_statusupdate'    => 'Aggiornamento dello stato del marchio',
                'brand_delete'          => 'Eliminazione del marchio',
                'brand_detail'          => 'Dettagli del marchio',
                'brandcategory_index'   => 'Classificazione del marchio',
                'brandcategory_save'    => 'Categoria del marchio Aggiungi/Modifica',
                'brandcategory_delete'  => 'Categoria marca Elimina',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Gestione del magazzino',
            'item'  => [
                'warehouse_index'               => 'Gestione del magazzino',
                'warehouse_saveinfo'            => 'Pagina Aggiungi/Modifica magazzino',
                'warehouse_save'                => 'Aggiungi/modifica magazzino',
                'warehouse_delete'              => 'Elimina magazzino',
                'warehouse_statusupdate'        => 'Aggiornamento dello stato del magazzino',
                'warehouse_detail'              => 'Dettagli del magazzino',
                'warehousegoods_index'          => 'Gestione delle merci di magazzino',
                'warehousegoods_detail'         => 'Dettagli dellarticolo del magazzino',
                'warehousegoods_delete'         => 'Eliminazione degli elementi del magazzino',
                'warehousegoods_statusupdate'   => 'Aggiornamento dello stato dellelemento del magazzino',
                'warehousegoods_goodssearch'    => 'Ricerca oggetti del magazzino',
                'warehousegoods_goodsadd'       => 'Ricerca oggetti del magazzino Aggiungi',
                'warehousegoods_goodsdel'       => 'Ricerca articolo del magazzino Elimina',
                'warehousegoods_inventoryinfo'  => 'Pagina di modifica dellinventario degli elementi del magazzino',
                'warehousegoods_inventorysave'  => 'Modifica inventario degli elementi del magazzino',
            ]
        ],
        'app_index' => [
            'name'  => 'Gestione telefono',
            'item'  => [
                'appconfig_index'            => 'Configurazione di base',
                'appconfig_save'             => 'Salvataggio di configurazione di base',
                'apphomenav_index'           => 'Navigazione della pagina iniziale',
                'apphomenav_saveinfo'        => 'Pagina di navigazione Aggiungi / Modifica',
                'apphomenav_save'            => 'Home navigation add/edit',
                'apphomenav_statusupdate'    => 'Aggiornamento dello stato della navigazione nella homepage',
                'apphomenav_delete'          => 'Cancellazione della navigazione Home',
                'apphomenav_detail'          => 'Dettagli di navigazione della home page',
                'appcenternav_index'         => 'Navigazione del centro utente',
                'appcenternav_saveinfo'      => 'Navigazione del centro utente Aggiungi/Modifica pagina',
                'appcenternav_save'          => 'Navigazione del centro utente Aggiungi/Modifica',
                'appcenternav_statusupdate'  => 'Aggiornamento dello stato di navigazione del centro utente',
                'appcenternav_delete'        => 'Navigazione del centro utente Elimina',
                'appcenternav_detail'        => 'Dettagli di navigazione del centro utente',
                'appmini_index'              => 'Elenco applet',
                'appmini_created'            => 'Generazione di piccoli pacchetti',
                'appmini_delete'             => 'Eliminazione pacchetto applet',
                'appmini_themeupload'        => 'Carica tema applet',
                'appmini_themesave'          => 'Cambia tema applet',
                'appmini_themedelete'        => 'Cambia tema applet',
                'appmini_themedownload'      => 'Scarica tema applet',
                'appmini_config'             => 'Configurazione applet',
                'appmini_save'               => 'Salvataggio configurazione applet',
            ]
        ],
        'article_index' => [
            'name'  => 'Gestione degli articoli',
            'item'  => [
                'article_index'           => 'Gestione degli articoli',
                'article_saveinfo'        => 'Articolo Aggiungi/Modifica pagina',
                'article_save'            => 'Articolo Aggiungi/Modifica',
                'article_delete'          => 'Soppressione dellarticolo',
                'article_statusupdate'    => 'Aggiornamento dello stato dellarticolo',
                'article_detail'          => 'Dettagli dellarticolo',
                'articlecategory_index'   => 'Classificazione degli articoli',
                'articlecategory_save'    => 'Categoria di articolo Modifica/Aggiungi',
                'articlecategory_delete'  => 'Categoria articolo Cancellare',
            ]
        ],
        'data_index' => [
            'name'  => 'gestione dei dati',
            'item'  => [
                'answer_index'          => 'Messaggio di domande e risposte',
                'answer_reply'          => 'Risposta al messaggio Q&A',
                'answer_delete'         => 'Eliminazione dei messaggi di domande e risposte',
                'answer_statusupdate'   => 'Aggiornamento dello stato del messaggio Q&A',
                'answer_saveinfo'       => 'Domande e risposte Aggiungi/Modifica pagina',
                'answer_save'           => 'Domande e risposte Aggiungi/Modifica',
                'answer_detail'         => 'Dettagli del messaggio Q&A',
                'message_index'         => 'Gestione messaggi',
                'message_delete'        => 'Cancellazione messaggio',
                'message_detail'        => 'Dettagli del messaggio',
                'paylog_index'          => 'Registro dei pagamenti',
                'paylog_detail'         => 'Dettagli del registro dei pagamenti',
                'paylog_close'          => 'Registro dei pagamenti chiuso',
                'payrequestlog_index'   => 'Elenco registro delle richieste di pagamento',
                'payrequestlog_detail'  => 'Dettagli del registro delle richieste di pagamento',
                'refundlog_index'       => 'Registro dei rimborsi',
                'refundlog_detail'      => 'Dettagli del registro dei rimborsi',
                'integrallog_index'     => 'Registro punti',
                'integrallog_detail'    => 'Dettagli del registro punti',
            ]
        ],
        'store_index' => [
            'name'  => 'Centro applicazioni',
            'item'  => [
                'pluginsadmin_index'         => 'Gestione delle applicazioni',
                'plugins_index'              => 'Gestione delle chiamate di applicazione',
                'pluginsadmin_saveinfo'      => 'Pagina Aggiungi/Modifica app',
                'pluginsadmin_save'          => 'Aggiungi/modifica app',
                'pluginsadmin_statusupdate'  => 'Aggiornamento dello stato dellapplicazione',
                'pluginsadmin_delete'        => 'Elimina app',
                'pluginsadmin_upload'        => 'Carica app',
                'pluginsadmin_download'      => 'Imballaggio app',
                'pluginsadmin_install'       => 'Installazione dellapplicazione',
                'pluginsadmin_uninstall'     => 'Disinstalla app',
                'pluginsadmin_sortsave'      => 'Applica Salva ordinamento',
                'store_index'                => 'App Store',
                'packageinstall_index'       => 'Pagina di installazione del pacchetto',
                'packageinstall_install'     => 'Installazione di pacchetti software',
                'packageupgrade_upgrade'     => 'Aggiornamento del pacchetto software',
            ]
        ],
        'tool_index' => [
            'name'  => 'strumento',
                'item'                  => [
                'cache_index'           => 'Gestione cache',
                'cache_statusupdate'    => 'Aggiornamento cache sito',
                'cache_templateupdate'  => 'Aggiornamento cache modello',
                'cache_moduleupdate'    => 'Aggiornamento cache modulo',
                'cache_logdelete'       => 'Cancellazione log',
                'sqlconsole_index'      => 'Console SQL',
                'sqlconsole_implement'  => 'Esecuzione SQL',
            ]
        ],
    ],
];
?>