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
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Pagina iniziale del centro commerciale',
        'back_to_the_home_title'                => 'Torna a casa',
        'all_category_text'                     => 'Tutte le categorie',
        'login_title'                           => 'Accedi',
        'register_title'                        => 'registro',
        'logout_title'                          => 'Esci',
        'cancel_text'                           => 'annulla',
        'save_text'                             => 'conservazione',
        'more_text'                             => 'di più',
        'processing_in_text'                    => 'Lavorazione',
        'upload_in_text'                        => 'Caricamento',
        'navigation_main_quick_name'            => 'Cassettiera del tesoro',
        'no_relevant_data_tips'                 => 'Nessun dato rilevante',
        'avatar_upload_title'                   => 'Carica immagine',
        'choice_images_text'                    => 'Seleziona immagine',
        'choice_images_error_tips'              => 'Seleziona limmagine da caricare',
        'confirm_upload_title'                  => 'Conferma caricamento',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Giapponese',
        'header_top_nav_left_login_first'       => 'Ciao!',
        'header_top_nav_left_login_last'        => 'Benvenuti a',
        // 搜索
        'search_input_placeholder'              => 'In realtà, cercare è molto semplice ^_^!',
        'search_button_text'                    => 'ricerca',
        // 用户
        'avatar_upload_tips'                    => [
            'Riduci e ingrandisci larea di lavoro e sposta la casella di selezione per selezionare lintervallo da tagliare, con un rapporto larghezza/altezza di taglio fisso;',
            'Leffetto del taglio è mostrato nellimmagine di anteprima a destra ed entra in vigore dopo la conferma dellinvio;',
        ],
        'close_user_register_tips'              => 'Registrazione temporanea dellutente',
        'close_user_login_tips'                 => 'Disattivare temporaneamente laccesso utente',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Ciao, benvenuti a',
        'banner_right_article_title'            => 'Titoli di notizie',
        'design_browser_seo_title'              => 'Design della home page',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Nessun dato di commento',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Il prodotto non esiste o è stato cancellato',
        'panel_can_choice_spec_name'            => 'Specifiche facoltative',
        'recommend_goods_title'                 => 'Guarda e vedi',
        'dynamic_scoring_name'                  => 'Punteggio dinamico',
        'no_scoring_data_tips'                  => 'Nessun dato di punteggio',
        'no_comments_data_tips'                 => 'Nessun dato di valutazione',
        'comments_first_name'                   => 'Comment on',
        'admin_reply_name'                      => 'Risposta dellamministratore:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Ricerca prodotto',
        'filter_out_first_text'                 => 'Filtra fuori',
        'filter_out_last_data_text'             => 'Dati',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Classificazione delle merci',
        'no_category_data_tips'                 => 'Nessun dato di classificazione',
        'no_sub_category_data_tips'             => 'Nessun dato di sottoclassificazione',
        'view_category_sub_goods_name'          => 'Visualizza prodotti nella categoria',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Seleziona un prodotto',
        ],
        // 基础
        'browser_seo_title'                     => 'Carrello',
        'goods_list_thead_base'                 => 'Informazioni sul prodotto',
        'goods_list_thead_price'                => 'Prezzo unitario',
        'goods_list_thead_number'               => 'quantità',
        'goods_list_thead_total'                => 'importo del denaro',
        'goods_item_total_name'                 => 'totale',
        'summary_selected_goods_name'           => 'Elemento selezionato',
        'summary_selected_goods_unit'           => 'pezzo',
        'summary_nav_goods_total'               => 'totale:',
        'summary_nav_button_name'               => 'regolamento',
        'no_cart_data_tips'                     => 'Il tuo carrello è ancora vuoto, puoi',
        'no_cart_data_my_favor_name'            => 'Il mio preferito',
        'no_cart_data_my_order_name'            => 'Il mio ordine',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Seleziona un indirizzo',
            'payment_choice_tips'               => 'Seleziona il pagamento',
        ],
        // 基础
        'browser_seo_title'                     => 'conferma dellordine',
        'exhibition_not_allow_submit_tips'      => 'Linvio dellordine non è consentito per il tipo di visualizzazione',
        'buy_item_order_title'                  => 'Informazioni sullordine',
        'buy_item_payment_title'                => 'Seleziona pagamento',
        'confirm_delivery_address_name'         => 'Conferma indirizzo di spedizione',
        'use_new_address_name'                  => 'Usa un nuovo indirizzo',
        'no_delivery_address_tips'              => 'Nessun indirizzo di spedizione',
        'confirm_extraction_address_name'       => 'Conferma indirizzo di consegna automatica',
        'choice_take_address_name'              => 'Seleziona indirizzo di ritiro',
        'no_take_address_tips'                  => 'Contatta lamministratore per configurare lindirizzo self-service',
        'no_address_tips'                       => 'Nessun indirizzo',
        'extraction_list_choice_title'          => 'Selezione del ritiro automatico',
        'goods_list_thead_base'                 => 'Informazioni sul prodotto',
        'goods_list_thead_price'                => 'Prezzo unitario',
        'goods_list_thead_number'               => 'quantità',
        'goods_list_thead_total'                => 'importo del denaro',
        'goods_item_total_name'                 => 'totale',
        'not_goods_tips'                        => 'Nessun prodotto',
        'not_payment_tips'                      => 'Nessun metodo di pagamento',
        'user_message_title'                    => 'Messaggio dellacquirente',
        'user_message_placeholder'              => 'Istruzioni per il riempimento facoltativo e consigliato e accordo con il venditore',
        'summary_title'                         => 'Pagamento effettivo:',
        'summary_contact_name'                  => 'contatti:',
        'summary_address'                       => 'Indirizzo:',
        'summary_submit_order_name'             => 'ordine',
        'payment_layer_title'                   => 'Saltare il pagamento, si prega di non chiudere la pagina',
        'payment_layer_content'                 => 'Il pagamento non è riuscito o non ha risposto per molto tempo',
        'payment_layer_order_button_text'       => 'Il mio ordine',
        'payment_layer_tips'                    => 'Il pagamento può essere riavviato dopo',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Tutti gli articoli',
        'article_no_data_tips'                  => 'Larticolo non esiste o è stato soppresso',
        'article_id_params_tips'                => 'ID articolo errato',
        'release_time'                          => 'Pubblicato:',
        'view_number'                           => 'Views:',
        'prev_article'                          => 'Precedente:',
        'next_article'                          => 'Prossimo:',
        'article_category_name'                 => 'Classificazione degli articoli',
        'article_nav_text'                      => 'Navigazione della barra laterale',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'La pagina non esiste o è stata cancellata',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'La pagina non esiste o è stata cancellata',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'LID dellordine non è corretto',
            'payment_choice_tips'               => 'Seleziona un metodo di pagamento',
            'rating_string'                     => 'Molto povero, povero, medio, buono, molto buono',
            'not_choice_data_tips'              => 'Seleziona prima i dati',
            'pay_url_empty_tips'                => 'Lindirizzo URL del pagamento non è corretto',
        ],
        // 基础
        'browser_seo_title'                     => 'Il mio ordine',
        'detail_browser_seo_title'              => 'Dettagli dellordine',
        'comments_browser_seo_title'            => 'Revisione dellordine',
        'batch_payment_name'                    => 'Pagamento in lotti',
        'comments_goods_list_thead_base'        => 'Informazioni sul prodotto',
        'comments_goods_list_thead_price'       => 'Prezzo unitario',
        'comments_goods_list_thead_content'     => 'Contenuto dei commenti',
        'form_you_have_commented_tips'          => 'Avete già commentato',
        'form_payment_title'                    => 'pagamento',
        'form_payment_no_data_tips'             => 'Nessun metodo di pagamento',
        'order_base_title'                      => 'Informazioni sullordine',
        'order_base_warehouse_title'            => 'Servizio di spedizione:',
        'order_base_model_title'                => 'Modalità ordine:',
        'order_base_order_no_title'             => 'Numero dordine:',
        'order_base_status_title'               => 'Stato dellordine:',
        'order_base_pay_status_title'           => 'Stato di pagamento:',
        'order_base_payment_title'              => 'Modalità di pagamento:',
        'order_base_total_price_title'          => 'Prezzo totale dellordine:',
        'order_base_buy_number_title'           => 'Quantità di acquisto:',
        'order_base_returned_quantity_title'    => 'Quantità di reso:',
        'order_base_user_note_title'            => 'Messaggio utente:',
        'order_base_add_time_title'             => 'Tempo di inserimento dellordine:',
        'order_base_confirm_time_title'         => 'Tempo di conferma:',
        'order_base_pay_time_title'             => 'Tempi di pagamento:',
        'order_base_delivery_time_title'        => 'Tempi di consegna:',
        'order_base_collect_time_title'         => 'Tempo di ricezione:',
        'order_base_user_comments_time_title'   => 'Comment time:',
        'order_base_cancel_time_title'          => 'Tempo di cancellazione:',
        'order_base_express_title'              => 'Azienda di servizi di corriere:',
        'order_base_express_website_title'      => 'Sito ufficiale della consegna espressa:',
        'order_base_express_number_title'       => 'numero del corriere:',
        'order_base_price_title'                => 'Prezzo totale delle merci:',
        'order_base_increase_price_title'       => 'Aumento dellimporto:',
        'order_base_preferential_price_title'   => 'Importo dello sconto:',
        'order_base_refund_price_title'         => 'Importo del rimborso:',
        'order_base_pay_price_title'            => 'Importo del pagamento:',
        'order_base_take_code_title'            => 'Codice di prelievo:',
        'order_base_take_code_no_exist_tips'    => 'Il codice di ritiro non esiste. Si prega di contattare lamministratore',
        'order_under_line_tips'                 => 'Attualmente, è un metodo di pagamento offline [{:payment}] che richiede conferma da parte dellamministratore prima che possa entrare in vigore. Se sono necessari altri pagamenti, è possibile cambiare i pagamenti e avviare nuovamente i pagamenti.',
        'order_delivery_tips'                   => 'La merce viene imballata e consegnata dal magazzino',
        'order_goods_no_data_tips'              => 'Nessun dato della riga di ordine',
        'order_status_operate_first_tips'       => 'Si può',
        'goods_list_thead_base'                 => 'Informazioni sul prodotto',
        'goods_list_thead_price'                => 'Prezzo unitario',
        'goods_list_thead_number'               => 'quantità',
        'goods_list_thead_total'                => 'importo del denaro',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Informazioni di base',
            'goods_placeholder'     => 'Inserisci il numero dordine/nome del prodotto/modello',
            'status'                => 'Stato dellordine',
            'pay_status'            => 'Stato del pagamento',
            'total_price'           => 'Prezzo totale (yuan)',
            'pay_price'             => 'Importo del pagamento (yuan)',
            'price'                 => 'Prezzo unitario (yuan)',
            'order_model'           => 'Modalità ordine',
            'client_type'           => 'Piattaforma di ordinazione',
            'address'               => 'Informazioni sullindirizzo',
            'take'                  => 'Informazioni sul ritiro',
            'refund_price'          => 'Importo del rimborso (yuan)',
            'returned_quantity'     => 'Quantità restituita',
            'buy_number_count'      => 'Totale acquisti',
            'increase_price'        => 'Aumento dellimporto (yuan)',
            'preferential_price'    => 'Importo dello sconto (yuan)',
            'payment_name'          => 'Metodo di pagamento',
            'user_note'             => 'Informazioni sul messaggio',
            'extension'             => 'Informazioni estese',
            'express_name'          => 'Società di servizi di corriere',
            'express_number'        => 'numero del corriere',
            'is_comments'           => 'Commento o no',
            'confirm_time'          => 'Tempo di conferma',
            'pay_time'              => 'Tempi di pagamento',
            'delivery_time'         => 'Tempi di consegna',
            'collect_time'          => 'Tempo di completamento',
            'cancel_time'           => 'Annulla ora',
            'close_time'            => 'Orario di chiusura',
            'add_time'              => 'Tempo di creazione',
            'upd_time'              => 'Tempo di aggiornamento',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Importo totale dellordine',
            'pay_price'             => 'Totale pagamento',
            'buy_number_count'      => 'Numero totale di prodotti',
            'refund_price'          => 'rimborso',
            'returned_quantity'     => 'reso merci',
            'price_unit'            => 'elemento',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'I dati relativi al motivo del rimborso sono vuoti',
        ],
        // 基础
        'browser_seo_title'                     => 'Ordine post vendita',
        'detail_browser_seo_title'              => 'Dettagli dellordine dopo le vendite',
        'view_orderaftersale_enter_name'        => 'Visualizza ordini post vendita',
        'operate_delivery_name'                 => 'Ritorna ora',
        'goods_list_thead_base'                 => 'Informazioni sul prodotto',
        'goods_list_thead_price'                => 'Prezzo unitario',
        'goods_base_price_title'                => 'Prezzo totale delle merci:',
        'goods_base_increase_price_title'       => 'Aumento dellimporto:',
        'goods_base_preferential_price_title'   => 'Importo dello sconto:',
        'goods_base_refund_price_title'         => 'Importo del rimborso:',
        'goods_base_pay_price_title'            => 'Importo del pagamento:',
        'goods_base_total_price_title'          => 'Prezzo totale dellordine:',
        'base_apply_title'                      => 'Informazioni sullapplicazione',
        'base_apply_type_title'                 => 'Tipo di rimborso:',
        'base_apply_status_title'               => 'Stato attuale:',
        'base_apply_reason_title'               => 'Motivo della domanda:',
        'base_apply_number_title'               => 'Quantità di reso:',
        'base_apply_price_title'                => 'Importo del rimborso:',
        'base_apply_msg_title'                  => 'Istruzioni per il rimborso:',
        'base_apply_refundment_title'           => 'Metodo di restituzione:',
        'base_apply_refuse_reason_title'        => 'Motivo del rifiuto:',
        'base_apply_apply_time_title'           => 'Tempo di applicazione:',
        'base_apply_confirm_time_title'         => 'Tempo di conferma:',
        'base_apply_delivery_time_title'        => 'Tempo di restituzione:',
        'base_apply_audit_time_title'           => 'Tempo di verifica:',
        'base_apply_cancel_time_title'          => 'Tempo di cancellazione:',
        'base_apply_add_time_title'             => 'Aggiunto il:',
        'base_apply_upd_time_title'             => 'Aggiornamento:',
        'base_item_express_title'               => 'Informazioni sul corriere',
        'base_item_express_name'                => 'Corriere:',
        'base_item_express_number'              => 'Numeri dispari:',
        'base_item_express_time'                => 'Tempo:',
        'base_item_voucher_title'               => 'voucher',
        // 表单
        'form_delivery_title'                   => 'Operazione di restituzione',
        'form_delivery_address_name'            => 'Indirizzo di ritorno',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Informazioni di base',
            'goods_placeholder'     => 'Inserisci il numero dordine/nome del prodotto/modello',
            'status'                => 'stato',
            'type'                  => 'Tipo di applicazione',
            'reason'                => 'ragione',
            'price'                 => 'Importo del rimborso (yuan)',
            'number'                => 'Quantità restituita',
            'msg'                   => 'Istruzioni per il rimborso',
            'refundment'            => 'Tipo di restituzione',
            'express_name'          => 'Società di servizi di corriere',
            'express_number'        => 'numero del corriere',
            'apply_time'            => 'Tempo di applicazione',
            'confirm_time'          => 'Tempo di conferma',
            'delivery_time'         => 'Tempo di ritorno',
            'audit_time'            => 'Tempo di audit',
            'add_time'              => 'Tempo di creazione',
            'upd_time'              => 'Tempo di aggiornamento',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Centro utente',
        'forget_password_browser_seo_title'     => 'Recupero password',
        'user_register_browser_seo_title'       => 'Registrazione utente',
        'user_login_browser_seo_title'          => 'Accesso utente',
        'password_reset_illegal_error_tips'     => 'Hai effettuato laccesso. Per reimpostare la password, esci prima dal tuo account corrente',
        'register_illegal_error_tips'           => 'Hai effettuato laccesso. Per registrare un nuovo account, esci prima dal tuo account corrente',
        'login_illegal_error_tips'              => 'Già effettuato laccesso, si prega di non effettuare il login di nuovo',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Non hai ancora un account?',
        'login_close_tips'                      => 'Accesso temporaneamente chiuso',
        'login_type_username_title'             => 'Password dellaccount',
        'login_type_mobile_title'               => 'Codice di verifica mobile',
        'login_type_email_title'                => 'Codice di verifica e-mail',
        'login_retrieve_password_title'         => 'Recupera password',
        // 注册
        'register_top_login_tips'               => 'Mi sono già registrato, ora',
        'register_close_tips'                   => 'La registrazione è temporaneamente chiusa',
        'register_type_username_title'          => 'Registrazione del conto',
        'register_type_mobile_title'            => 'Registrazione mobile',
        'register_type_email_title'             => 'Registrazione via e-mail',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Hai gia un conto?',
        // 表单
        'form_item_agreement'                   => 'Leggi e accetta',
        'form_item_agreement_message'           => 'Seleziona lAccordo daccordo',
        'form_item_service'                     => 'Accordo di servizio',
        'form_item_privacy'                     => 'Informativa sulla privacy',
        'form_item_username'                    => 'nome utente',
        'form_item_username_message'            => 'Utilizzare lettere, numeri e sottolineature per 2-18 caratteri',
        'form_item_password'                    => 'Password di accesso',
        'form_item_password_placeholder'        => 'Inserisci la tua password di accesso',
        'form_item_password_message'            => 'Il formato della password è compreso tra 6 e 18 caratteri',
        'form_item_mobile'                      => 'numero di telefono',
        'form_item_mobile_placeholder'          => 'Inserisci il tuo numero di telefono',
        'form_item_mobile_message'              => 'Errore di formato del numero mobile',
        'form_item_email'                       => 'E-mail',
        'form_item_email_placeholder'           => 'Inserisci un indirizzo email',
        'form_item_email_message'               => 'Errore di formato e-mail',
        'form_item_account'                     => 'Account di accesso',
        'form_item_account_placeholder'         => 'Inserisci nome utente/telefono/email',
        'form_item_account_message'             => 'Inserisci un account di accesso',
        'form_item_mobile_email'                => 'Telefono cellulare/e-mail',
        'form_item_mobile_email_message'        => 'Inserisci un formato di telefono/email valido',
        // 个人中心
        'base_avatar_title'                     => 'Modifica avatar',
        'base_personal_title'                   => 'Modifica dati',
        'base_address_title'                    => 'Il mio indirizzo',
        'base_message_title'                    => 'news',
        'order_nav_title'                       => 'Il mio ordine',
        'order_nav_angle_title'                 => 'Visualizza tutti gli ordini',
        'various_transaction_title'             => 'Promemoria delle transazioni',
        'various_transaction_tips'              => 'Gli avvisi sulle transazioni possono aiutarti a capire lo stato degli ordini e la logistica',
        'various_cart_title'                    => 'Carrello',
        'various_cart_empty_title'              => 'Il tuo carrello è ancora vuoto',
        'various_cart_tips'                     => 'Mettere gli articoli che si desidera acquistare in un carrello rende più facile da sistemare insieme',
        'various_favor_title'                   => 'Collezione prodotti',
        'various_favor_empty_title'             => 'Non hai ancora raccolto nessun oggetto',
        'various_favor_tips'                    => 'I prodotti preferiti mostreranno le ultime promozioni e riduzioni di prezzo',
        'various_browse_title'                  => 'Le mie tracce',
        'various_browse_empty_title'            => 'Il record di navigazione del prodotto è vuoto',
        'various_browse_tips'                   => 'Sbrigati a controllare le attività promozionali al centro commerciale',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Il mio indirizzo',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Le mie tracce',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Informazioni sul prodotto',
            'goods_placeholder'     => 'Inserisci il nome del prodotto/breve descrizione/informazioni SEO',
            'price'                 => 'Prezzo di vendita (RMB)',
            'original_price'        => 'Prezzo originale (yuan)',
            'add_time'              => 'Tempo di creazione',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'Collezione prodotti',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Informazioni sul prodotto',
            'goods_placeholder'     => 'Inserisci il nome del prodotto/breve descrizione/informazioni SEO',
            'price'                 => 'Prezzo di vendita (RMB)',
            'original_price'        => 'Prezzo originale (yuan)',
            'add_time'              => 'Tempo di creazione',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'I miei punti',
        // 页面
        'base_normal_title'                     => 'Disponibilità normale',
        'base_normal_tips'                      => 'Punti che possono essere utilizzati normalmente',
        'base_locking_title'                    => 'Attualmente bloccato',
        'base_locking_tips'                     => 'In generale, la transazione non viene completata e i punti corrispondenti sono bloccati',
        'base_integral_unit'                    => 'integrale',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Tipo di operazione',
            'operation_integral'    => 'Integrale operativo',
            'original_integral'     => 'Integrale originale',
            'new_integral'          => 'Ultimi punti',
            'msg'                   => 'descrivere',
            'add_time_time'         => 'tempo',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'dati personali',
        'edit_browser_seo_title'                => 'Modifica profilo',
        'form_item_nickname'                    => 'nickname',
        'form_item_nickname_message'            => 'Soprannome tra 2 e 16 caratteri',
        'form_item_birthday'                    => 'compleanno',
        'form_item_birthday_message'            => 'Formato compleanno errato',
        'form_item_province'                    => 'Provincia',
        'form_item_province_message'            => 'Provincia con un massimo di 30 caratteri',
        'form_item_city'                        => 'Città',
        'form_item_city_message'                => 'Città con un massimo di 30 caratteri',
        'form_item_county'                      => 'Distretto/contea',
        'form_item_county_message'              => 'Distretto/Contea con un massimo di 30 caratteri',
        'form_item_address'                     => 'Indirizzo dettagliato',
        'form_item_address_message'             => 'Indirizzo dettagliato 2~30 caratteri',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Il mio messaggio',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Tipo messaggio',
            'business_type'         => 'Tipo di attività',
            'title'                 => 'titolo',
            'detail'                => 'dettagli',
            'is_read'               => 'stato',
            'add_time_time'         => 'tempo',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Domande e risposte/messaggio',
        // 表单
        'form_title'                            => 'Domande/messaggi',
        'form_item_name'                        => 'nickname',
        'form_item_name_message'                => 'Il formato del nickname è compreso tra 1 e 30 caratteri',
        'form_item_tel'                         => 'Telefono',
        'form_item_tel_message'                 => 'Inserisci il numero di telefono',
        'form_item_title'                       => 'titolo',
        'form_item_title_message'               => 'Il formato del titolo è compreso tra 1 e 60 caratteri',
        'form_item_content'                     => 'contenuto',
        'form_item_content_message'             => 'Il formato del contenuto è compreso tra 5 e 1000 caratteri',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'contatti',
            'tel'                   => 'numero di contatto',
            'content'               => 'contenuto',
            'reply'                 => 'Contenuto della risposta',
            'reply_time_time'       => 'Tempo di risposta',
            'add_time_time'         => 'Tempo di creazione',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'impostazione di sicurezza',
        'password_update_browser_seo_title'     => 'Modifica della password di accesso - Impostazioni di sicurezza',
        'mobile_update_browser_seo_title'       => 'Modifica del numero di cellulare - impostazioni di sicurezza',
        'email_update_browser_seo_title'        => 'Modifica e-mail - impostazioni di sicurezza',
        'logout_browser_seo_title'              => 'Logout account - Impostazioni di sicurezza',
        'original_account_check_error_tips'     => 'Verifica dellaccount originale non riuscita',
        // 页面
        'logout_title'                          => 'Cancellazione del conto',
        'logout_confirm_title'                  => 'Conferma disconnessione',
        'logout_confirm_tips'                   => 'Laccount non può essere ripristinato dopo la cancellazione. Sei sicuro di continuare?',
        'email_title'                           => 'Verifica e-mail originale',
        'email_new_title'                       => 'Verifica nuova e-mail',
        'mobile_title'                          => 'Verifica originale del numero di cellulare',
        'mobile_new_title'                      => 'Nuova verifica del numero di cellulare',
        'login_password_title'                  => 'Modifica della password di accesso',
    ],
];
?>