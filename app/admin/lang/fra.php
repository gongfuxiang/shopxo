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
 * 模块语言包-法语
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
            'order_transaction_amount_name'     => 'Mouvement du montant de clôture des commandes',
            'order_trading_trend_name'          => 'Mouvement des transactions dordre',
            'goods_hot_name'                    => 'Produits de vente chauds',
            'goods_hot_tips'                    => 'Afficher uniquement les 30 premiers articles',
            'payment_name'                      => 'Mode de paiement',
            'order_region_name'                 => 'Répartition géographique des commandes',
            'order_region_tips'                 => 'Afficher seulement 30 données',
            'upgrade_check_loading_tips'        => 'Obtenez les derniers contenus, veuillez patienter…',
            'upgrade_version_name'              => 'Version mise à jour:',
            'upgrade_date_name'                 => 'Date de mise à jour:',
        ],
        // 页面基础
        'base_update_button_title'              => 'Mettre à jour maintenant',
        'base_item_base_stats_title'            => 'Statistiques du centre commercial',
        'base_item_base_stats_tips'             => 'Le filtre temporel nest valable que pour le total',
        'base_item_user_title'                  => 'Total des utilisateurs',
        'base_item_order_number_title'          => 'Total des commandes',
        'base_item_order_complete_number_title' => 'Total des transactions',
        'base_item_order_complete_title'        => 'Total des commandes',
        'base_item_last_month_title'            => 'Le mois dernier',
        'base_item_same_month_title'            => 'Le mois',
        'base_item_yesterday_title'             => 'Hier',
        'base_item_today_title'                 => 'Aujourdhui',
        'base_item_order_profit_title'          => 'Mouvement du montant de clôture des commandes',
        'base_item_order_trading_title'         => 'Mouvement des transactions dordre',
        'base_item_order_tips'                  => 'Toutes les commandes',
        'base_item_hot_sales_goods_title'       => 'Produits de vente chauds',
        'base_item_hot_sales_goods_tips'        => 'Commandes sans annulation fermer',
        'base_item_payment_type_title'          => 'Mode de paiement',
        'base_item_map_whole_country_title'     => 'Répartition géographique des commandes',
        'base_item_map_whole_country_tips'      => 'Commandes sans annulation fermer, dimension par défaut (province)',
        'base_item_map_whole_country_province'  => 'Province',
        'base_item_map_whole_country_city'      => 'La ville',
        'base_item_map_whole_country_county'    => 'District / Comté',
        'system_info_title'                     => 'Informations système',
        'system_ver_title'                      => 'Version du logiciel',
        'system_os_ver_title'                   => 'Système dexploitation',
        'system_php_ver_title'                  => 'Version PHP',
        'system_mysql_ver_title'                => 'Version MySQL',
        'system_server_ver_title'               => 'Informations côté serveur',
        'system_host_title'                     => 'Nom de domaine actuel',
        'development_team_title'                => 'Équipe de développement',
        'development_team_website_title'        => 'Site officiel de la société',
        'development_team_website_value'        => 'Shanghai jonggi Technology Co., Ltd',
        'development_team_support_title'        => 'Soutien technique',
        'development_team_support_value'        => 'Shopxo fournisseur de systèmes de commerce électronique pour les entreprises',
        'development_team_ask_title'            => 'Échange de questions',
        'development_team_ask_value'            => 'Questions sur shopxo Exchange',
        'development_team_agreement_title'      => 'Protocole Open Source',
        'development_team_agreement_value'      => 'Voir le Protocole Open Source',
        'development_team_update_log_title'     => 'Mettre à jour le journal',
        'development_team_update_log_value'     => 'Voir le Journal des mises à jour',
        'development_team_members_title'        => 'Membres R & D',
        'development_team_members_value'        => [
            ['name' => 'Frère Gong', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'Utilisateurs',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'ID utilisateur',
            'number_code'           => 'Code membre',
            'system_type'           => 'Type de système',
            'platform'              => 'La plateforme à laquelle appartient',
            'avatar'                => 'Image de tête',
            'username'              => 'Nom dutilisateur',
            'nickname'              => 'Surnom',
            'mobile'                => 'Téléphone portable',
            'email'                 => 'Boîte aux lettres',
            'gender_name'           => 'Sexe',
            'status_name'           => 'Statut',
            'province'              => 'Province du lieu',
            'city'                  => 'Ville située',
            'county'                => 'Région / Comté',
            'address'               => 'Adresse détaillée',
            'birthday'              => 'Anniversaire',
            'integral'              => 'Points disponibles',
            'locking_integral'      => 'Points verrouillés',
            'referrer'              => 'Inviter les utilisateurs',
            'referrer_placeholder'  => 'Veuillez entrer votre nom dutilisateur dinvitation / pseudonyme / téléphone portable / boîte aux lettres',
            'add_time'              => 'Heures dinscription',
            'upd_time'              => 'Temps de mise à jour',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Adresse de lutilisateur',
        // 详情
        'detail_user_address_idcard_name'       => 'Nom et prénom',
        'detail_user_address_idcard_number'     => 'Numéro',
        'detail_user_address_idcard_pic'        => 'Photos',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informations utilisateur',
            'user_placeholder'  => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'alias'             => 'Alias',
            'name'              => 'Contact',
            'tel'               => 'Téléphone de contact',
            'province_name'     => 'Province à laquelle appartient',
            'city_name'         => 'Municipalité à laquelle appartient',
            'county_name'       => 'Région / Comté',
            'address'           => 'Adresse détaillée',
            'position'          => 'Longitude Latitude',
            'idcard_info'       => 'Informations sur la carte didentité',
            'is_default'        => 'Par défaut ou non',
            'add_time'          => 'Temps de création',
            'upd_time'          => 'Temps de mise à jour',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Sauvegarder prend effet après la suppression, confirmer continuer?',
            'address_no_data'                   => 'Les données dadresse sont vides',
            'address_not_exist'                 => 'Ladresse nexiste pas',
            'address_logo_message'              => 'Veuillez télécharger une image logo',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Configuration de base', 'type' => 'base'],
            ['name' => 'Paramètres du site', 'type' => 'siteset'],
            ['name' => 'Type de site', 'type' => 'sitetype'],
            ['name' => 'Enregistrement des utilisateurs', 'type' => 'register'],
            ['name' => 'Login utilisateur', 'type' => 'login'],
            ['name' => 'Récupération du mot de passe', 'type' => 'forgetpwd'],
            ['name' => 'Code de vérification', 'type' => 'verify'],
            ['name' => 'Après la commande', 'type' => 'orderaftersale'],
            ['name' => 'Annexes', 'type' => 'attachment'],
            ['name' => 'Cache', 'type' => 'cache'],
            ['name' => 'Extensions', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'Accueil', 'type' => 'index'],
            ['name' => 'Marchandises', 'type' => 'goods'],
            ['name' => 'Rechercher', 'type' => 'search'],
            ['name' => 'Commandes', 'type' => 'order'],
            ['name' => 'Offres', 'type' => 'discount'],
            ['name' => 'Extensions', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'État du site',
        'base_item_site_domain_title'           => 'Adresse du nom de domaine du site',
        'base_item_site_filing_title'           => 'Informations sur le dépôt',
        'base_item_site_other_title'            => 'Autres',
        'base_item_session_cache_title'         => 'Configuration du cache de session',
        'base_item_data_cache_title'            => 'Configuration du cache de données',
        'base_item_redis_cache_title'           => 'Configuration du cache redis',
        'base_item_crontab_config_title'        => 'Configuration des scripts temporels',
        'base_item_quick_nav_title'             => 'Navigation rapide',
        'base_item_user_base_title'             => 'Base dutilisateurs',
        'base_item_user_address_title'          => 'Adresse de lutilisateur',
        'base_item_multilingual_title'          => 'Multilingue',
        'base_item_site_auto_mode_title'        => 'Mode automatique',
        'base_item_site_manual_mode_title'      => 'Mode manuel',
        'base_item_default_payment_title'       => 'Méthode de paiement par défaut',
        'base_item_display_type_title'          => 'Type daffichage',
        'base_item_self_extraction_title'       => 'Point dauto - levée',
        'base_item_fictitious_title'            => 'Vente virtuelle',
        'choice_upload_logo_title'              => 'Choisir un logo',
        'add_goods_title'                       => 'Produits ajoutés',
        'add_self_extractio_address_title'      => 'Ajouter une adresse',
        'site_domain_tips_list'                 => [
            '1. Nom de domaine du site non défini, le nom de domaine du site actuel est utilisé adresse de domaine[ '.__MY_DOMAIN__.' ]',
            '2. Les pièces jointes et les adresses statiques ne sont pas définies, ladresse de domaine statique du site actuel est utilisée[ '.__MY_PUBLIC_URL__.' ]',
            '3. Si le serveur na pas public à la racine, la configuration [nom de domaine CDN de la pièce jointe, nom de domaine CDN du fichier statique CSS / JS] doit être suivie de public, par exemple:'.__MY_PUBLIC_URL__.'public/',
            '4. Exécutez le projet en mode ligne de commande, ladresse de la zone doit être configurée, sinon certaines adresses du projet manqueront dinformations de nom de domaine',
            '5. Ne pas encombrer la configuration, ladresse incorrecte peut rendre le site Web inaccessible (la configuration de ladresse commence par HTTP), si la configuration de votre propre station est HTTPS, elle commence par HTTPS',
        ],
        'site_cache_tips_list'                  => [
            'Mise en cache de fichiers utilisée par défaut, mise en cache avec redis PHP nécessite linstallation de lextension redis',
            'Sil vous plaît assurez - vous que le service redis est stable (après que la session utilise le cache, linstabilité du service peut entraîner limpossibilité de se connecter en arrière - plan)',
            'Si vous rencontrez une exception de service redis qui ne vous connecte pas au back - office dadministration, modifiez le fichier [session.php, cache.php] sous le répertoire [config]',
        ],
        'goods_tips_list'                       => [
            '1. Le côté Web affiche le niveau 3 par défaut, le niveau 1 le plus bas, le niveau 3 le plus élevé (le niveau 3 par défaut si Défini sur le niveau 0)',
            '2. Affichage par défaut du téléphone portable niveau 0 (mode liste darticles), niveau 0 minimum, niveau 3 maximum (1 ~ 3 est le mode daffichage classé)',
            '3. Le niveau nest pas le même, le style de page de classification frontale ne sera pas le même',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Configurez le nombre maximum de produits affichés par étage',
            '2. Il nest pas recommandé de modifier la quantité trop grande, entraînera une zone vide trop grande sur le côté gauche du PC',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'Synthétisé en: chaleur - > ventes - > nouveautés en ordre décroissant (DESC) Trier par',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1.cliquable titre de larticle glisser trier, afficher dans lordre',
            '2. Il nest pas recommandé dajouter beaucoup de produits, cela entraînera une zone vide trop grande sur le côté gauche du PC',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. Par défaut, [nom dutilisateur, téléphone, boîte aux lettres] comme unique utilisateur',
            '2. Ouvrez puis ajoutez [identification système] en tant quutilisateur unique',
        ],
        'extends_crontab_tips'                  => 'Il est recommandé dajouter ladresse du script à la requête Linux Timing Task Timing (résultat sucs: 0, fail: 0 deux - points suivi du nombre dentrées de données traitées, sucs réussi, fail échoué)',
        'left_images_random_tips'               => 'Image de gauche peut télécharger jusquà 3 images, afficher lune dentre elles au hasard à chaque fois',
        'background_color_tips'                 => 'Image darrière - plan personnalisable, gris de fond par défaut',
        'site_setup_layout_tips'                => 'Le mode drag doit aller à la page de conception de la maison par vous - même, veuillez enregistrer la configuration sélectionnée avant',
        'site_setup_layout_button_name'         => 'Aller à la page design > >',
        'site_setup_goods_category_tips'        => 'Pour plus daffichage détage, venez dabord / gestion des marchandises - > classification des marchandises, configuration de la classification de niveau 1 Home recommander',
        'site_setup_goods_category_no_data_tips'=> 'Pas de données pour le moment, veuillez dabord arriver / gestion des marchandises - > classification des marchandises, configuration de la classification de niveau 1 Home recommendations',
        'site_setup_order_default_payment_tips' => 'Il est possible de définir le mode de paiement par défaut correspondant à différentes plates - formes, veuillez dabord installer le plugin de paiement dans [Administration du site - > modes de paiement] activé et ouvert aux utilisateurs',
        'site_setup_choice_payment_message'     => 'Veuillez sélectionner {:name} méthode de paiement par défaut',
        'sitetype_top_tips_list'                => [
            '1. Express, processus e - commerce régulier, lutilisateur choisit ladresse de réception pour passer la commande paiement - > expédition du marchand - > confirmer la réception - > commande terminée',
            '2. Type daffichage, produits daffichage seulement, peut lancer la consultation (ne peut pas passer la commande)',
            '3. Self pick up point, choisissez ladresse de livraison Self pick up lorsque vous passez la commande, lutilisateur passe la commande paiement - > confirmer le ramassage - > la commande est terminée',
            '4. Vente virtuelle, processus de - commerce régulier, lutilisateur passe une commande paiement - > expédition automatique - > confirmer le ramassage - > commande terminée',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => 'Recommandé 300 * 300px',
        'form_take_address_alias'               => 'Alias',
        'form_take_address_alias_message'       => 'Format alias jusquà 16 caractères',
        'form_take_address_name'                => 'Contact',
        'form_take_address_name_message'        => 'Format de contact entre 2 ~ 16 caractères',
        'form_take_address_tel'                 => 'Téléphone de contact',
        'form_take_address_tel_message'         => 'Veuillez remplir le numéro de contact',
        'form_take_address_address'             => 'Adresse détaillée',
        'form_take_address_address_message'     => 'Format dadresse détaillé entre 1 ~ 80 caractères',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Connexion back office',
        'admin_login_info_bg_images_list_tips'  => [
            '1. Limage darrière - plan se trouve dans le répertoire [public / static / admin / default / images / login]',
            '2. Règles de nommage de limage de fond (1 ~ 50), comme 1.jpg',
        ],
        'map_type_tips'                         => 'Étant donné que les normes de carte de chaque maison ne sont pas les mêmes, ne changez pas de carte à volonté, ce qui entraînera des situations où les coordonnées de la carte ne sont pas étiquetées correctement.',
        'apply_map_baidu_name'                  => 'Demandez à Baidu Maps Open Platform',
        'apply_map_amap_name'                   => 'Postulez à la plate - forme ouverte Gaudet Maps',
        'apply_map_tencent_name'                => 'Postulez à Tencent Maps Open Platform',
        'apply_map_tianditu_name'               => 'Venez postuler à la plateforme ouverte de Sky Map',
        'cookie_domain_list_tips'               => [
            '1. Null par défaut, valide uniquement pour le nom de domaine daccès actuel',
            'Si vous avez besoin dun nom de domaine de deuxième niveau qui partage également des cookies, remplissez le nom de domaine de premier niveau, par exemple: baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'La marque',
        // 动态表格
        'form_table'                            => [
            'name'                 => 'Le nom',
            'describe'             => 'Description',
            'logo'                 => 'LOGO',
            'url'                  => 'Adresse du site officiel',
            'brand_category_text'  => 'Classification des marques',
            'is_enable'            => 'Activé ou non',
            'sort'                 => 'Trier',
            'add_time'             => 'Temps de création',
            'upd_time'             => 'Temps de mise à jour',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Classification des marques',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Articles',
        'detail_content_title'                  => 'Détails contenu',
        'detail_images_title'                   => 'Détails images',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'Titre',
            'jump_url'               => 'Aller à ladresse URL',
            'article_category_name'  => 'Classification',
            'is_enable'              => 'Activé ou non',
            'is_home_recommended'    => 'Home recommandé',
            'images_count'           => 'Nombre dimages',
            'access_count'           => 'Nombre de visites',
            'add_time'               => 'Temps de création',
            'upd_time'               => 'Temps de mise à jour',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Catégories darticles',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Personnaliser la page',
        'detail_content_title'                  => 'Détails contenu',
        'detail_images_title'                   => 'Détails images',
        'save_view_tips'                        => 'Veuillez enregistrer avant de prévisualiser leffet',
        // 动态表格
        'form_table'                            => [
            'info'            => 'Titre',
            'is_enable'       => 'Activé ou non',
            'is_header'       => 'Si la tête',
            'is_footer'       => 'Si la queue',
            'is_full_screen'  => 'Plein écran ou pas',
            'images_count'    => 'Nombre dimages',
            'access_count'    => 'Nombre de visites',
            'add_time'        => 'Temps de création',
            'upd_time'        => 'Temps de mise à jour',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Plus de modèles de design à télécharger',
        'upload_list_tips'                      => [
            '1 sélectionnez le package zip page design que vous avez téléchargé',
            '2. Limportation ajoutera automatiquement une donnée',
        ],
        'operate_sync_tips'                     => 'Synchronisation des données dans la visualisation Home drag, puis modification des données nest pas affectée, mais ne supprimez pas les pièces jointes associées',
        // 动态表格
        'form_table'                            => [
            'id'                => 'ID des données',
            'info'              => 'Informations de base',
            'info_placeholder'  => 'Veuillez entrer un nom',
            'access_count'      => 'Nombre de visites',
            'is_enable'         => 'Activé ou non',
            'is_header'         => 'Avec ou sans tête',
            'is_footer'         => 'Avec ou sans queue',
            'seo_title'         => 'Titre SEO',
            'seo_keywords'      => 'Mots clés SEO',
            'seo_desc'          => 'Description du SEO',
            'add_time'          => 'Temps de création',
            'upd_time'          => 'Temps de mise à jour',
        ],
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Questions et réponses',
        'user_info_title'                       => 'Informations utilisateur',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informations utilisateur',
            'user_placeholder'  => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'name'              => 'Contact',
            'tel'               => 'Téléphone de contact',
            'content'           => 'Contenu',
            'reply'             => 'Contenu de la réponse',
            'is_show'           => 'Afficher ou non',
            'is_reply'          => 'Répondre ou non',
            'reply_time_time'   => 'Temps de réponse',
            'access_count'      => 'Nombre de visites',
            'add_time_time'     => 'Temps de création',
            'upd_time_time'     => 'Temps de mise à jour',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Entrepôt',
        'top_tips_list'                         => [
            '1. Plus la valeur du poids est élevée, plus le poids est élevé, moins les stocks sont déduits en fonction du poids)',
            '2.warehouse seulement suppression douce, ne sera pas disponible après la suppression, seules les données sont conservées dans la base de données) peut supprimer les données de marchandises associées par lui - même',
            '3. Désactivation et suppression de lentrepôt, les stocks de marchandises associés sont immédiatement libérés',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Nom / alias',
            'level'          => 'Poids',
            'is_enable'      => 'Activé ou non',
            'contacts_name'  => 'Contact',
            'contacts_tel'   => 'Téléphone de contact',
            'province_name'  => 'Province du lieu',
            'city_name'      => 'Ville située',
            'county_name'    => 'Région / Comté',
            'address'        => 'Adresse détaillée',
            'position'       => 'Longitude Latitude',
            'add_time'       => 'Temps de création',
            'upd_time'       => 'Temps de mise à jour',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Veuillez sélectionner un entrepôt',
        ],
        // 基础
        'add_goods_title'                       => 'Produits ajoutés',
        'no_spec_data_tips'                     => 'Aucune donnée de spécification',
        'batch_setup_inventory_placeholder'     => 'Valeurs définies par lot',
        'base_spec_inventory_title'             => 'Spécifications en stock',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Informations de base',
            'goods_placeholder'  => 'Veuillez entrer le nom de larticle / modèle',
            'warehouse_name'     => 'Entrepôt',
            'is_enable'          => 'Activé ou non',
            'inventory'          => 'Stock total',
            'spec_inventory'     => 'Spécifications en stock',
            'add_time'           => 'Temps de création',
            'upd_time'           => 'Temps de mise à jour',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'Linformation administrateur nexiste pas',
        // 列表
        'top_tips_list'                         => [
            '1. Le compte admin a toutes les autorisations par défaut et nest pas modifiable.',
            '2. Le compte admin nest pas modifiable, mais peut être modifié dans la fiche technique( '.MyConfig('database.connections.mysql.prefix').'admin ) Les champs username',
        ],
        'base_nav_title'                        => 'Administrateur',
        // 登录
        'login_type_username_title'             => 'Numéro de compte mot de passe',
        'login_type_mobile_title'               => 'Code de vérification du téléphone portable',
        'login_type_email_title'                => 'Code de vérification de la boîte aux lettres',
        'login_close_tips'                      => 'Connexion temporairement fermée',
        // 忘记密码
        'form_forget_password_name'             => 'Mot de passe oublié?',
        'form_forget_password_tips'             => 'Veuillez contacter lAdministrateur pour réinitialiser votre mot de passe',
        // 动态表格
        'form_table'                            => [
            'username'              => 'Administrateur',
            'status'                => 'Statut',
            'gender'                => 'Sexe',
            'mobile'                => 'Téléphone portable',
            'email'                 => 'Boîte aux lettres',
            'role_name'             => 'Groupe de rôles',
            'login_total'           => 'Nombre de logins',
            'login_time'            => 'Dernière heure de connexion',
            'add_time'              => 'Temps de création',
            'upd_time'              => 'Temps de mise à jour',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Contrat denregistrement de lutilisateur', 'type' => 'register'],
            ['name' => 'Politique de confidentialité des utilisateurs', 'type' => 'privacy'],
            ['name' => 'Contrat de radiation du numéro de compte', 'type' => 'logout']
        ],
        'top_tips'          => 'Ladresse du Protocole daccès frontal augmente le paramètre is Content = 1 affiche uniquement le contenu du Protocole pur',
        'view_detail_name'                      => 'Voir les détails',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Configuration de base', 'type' => 'index'],
            ['name' => 'Apps / applets', 'type' => 'app'],
        ],
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Thèmes actuels', 'type' => 'index'],
            ['name' => 'Installation du thème', 'type' => 'upload'],
            ['name' => 'Télécharger le paquet source', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Plus de thèmes à télécharger',
        'nav_theme_download_name'               => 'Voir le tutoriel Packaging applet',
        'nav_theme_download_tips'               => 'Le thème côté téléphone est développé avec uniapp (applet Multi - extrémité + h5 prise en charge) et lapp est également dans ladaptation durgence.',
        'form_alipay_extend_title'              => 'Configuration du service client',
        'form_alipay_extend_tips'               => 'PS: Si activé dans [app / applet] (activer le service client en ligne), les configurations suivantes sont obligatoires [codage dentreprise] et [codage de fenêtre de discussion]',
        'form_theme_upload_tips'                => 'Télécharger un package dinstallation au format zip compressé',
        'list_no_data_tips'                     => 'Pas de packs thématiques associés',
        'list_author_title'                     => 'Auteur',
        'list_version_title'                    => 'Version applicable',
        'package_generate_tips'                 => 'Le temps de génération est relativement long, ne fermez pas la fenêtre du navigateur!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Nom du paquet',
            'size'  => 'Taille',
            'url'   => 'Adresse de téléchargement',
            'time'  => 'Temps de création',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Paramètres SMS', 'type' => 'index'],
            ['name' => 'Modèle de message', 'type' => 'message'],
        ],
        'top_tips'                              => 'Adresse de gestion SMS Alibaba Cloud',
        'top_to_aliyun_tips'                    => 'Cliquez pour acheter des SMS Alibaba Cloud',
        'base_item_admin_title'                 => 'Back - Office',
        'base_item_index_title'                 => 'Le Front - end',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Paramètres de boîte aux lettres', 'type' => 'index'],
            ['name' => 'Modèle de message', 'type' => 'message'],
        ],
        'top_tips'                              => 'En raison de certaines différences entre les différentes plates - formes de boîte aux lettres, la configuration est également différente, en particulier sous réserve du Tutoriel de configuration de la plate - forme de boîte aux lettres',
        // 基础
        'test_title'                            => 'Test',
        'test_content'                          => 'Mail Configuration - envoyer du contenu de test',
        'base_item_admin_title'                 => 'Back - Office',
        'base_item_index_title'                 => 'Le Front - end',
        // 表单
        'form_item_test'                        => 'Tester ladresse mail reçue',
        'form_item_test_tips'                   => 'Veuillez sauvegarder la configuration avant de la tester',
        'form_item_test_button_title'           => 'Test',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Configurer les règles pseudo - statiques correspondantes différemment selon lenvironnement serveur [nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'Marchandises',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Informations de base', 'type'=>'base'],
            'specifications'  => ['name' => 'Spécifications des marchandises', 'type'=>'specifications'],
            'parameters'      => ['name' => 'Paramètres des marchandises', 'type'=>'parameters'],
            'photo'           => ['name' => 'Album de marchandises', 'type'=>'photo'],
            'video'           => ['name' => 'Marchandises vidéo', 'type'=>'video'],
            'app'             => ['name' => 'Détails du téléphone', 'type'=>'app'],
            'web'             => ['name' => 'Détails de lordinateur', 'type'=>'web'],
            'fictitious'      => ['name' => 'Informations virtuelles', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Données étendues', 'type'=>'extends'],
            'seo'             => ['name' => 'Informations SEO', 'type'=>'seo'],
        ],
        // 动态表格
        'form_table'                            => [
            'id'                      => 'ID du produit',
            'info'                    => 'Informations sur les marchandises',
            'category_text'           => 'Classification des marchandises',
            'brand_name'              => 'La marque',
            'price'                   => 'Prix de vente (En dollars)',
            'original_price'          => 'Prix dorigine (Yuan)',
            'inventory'               => 'Total des stocks',
            'is_shelves'              => 'Monter et descendre létagère',
            'is_deduction_inventory'  => 'Déduction des stocks',
            'site_type'               => 'Type de marchandises',
            'model'                   => 'Modèle darticle',
            'place_origin_name'       => 'Lieu de production',
            'give_integral'           => 'Proportion de points cadeaux achetés',
            'buy_min_number'          => 'Quantité minimum dachat à partir dune seule fois',
            'buy_max_number'          => 'Quantité maximale dachat unique',
            'sales_count'             => 'Volume des ventes',
            'access_count'            => 'Nombre de visites',
            'add_time'                => 'Temps de création',
            'upd_time'                => 'Temps de mise à jour',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Classification des marchandises',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Commentaires sur les produits',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informations utilisateur',
            'user_placeholder'   => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'goods'              => 'Informations de base',
            'goods_placeholder'  => 'Veuillez entrer le nom de larticle / modèle',
            'business_type'      => 'Type dentreprise',
            'content'            => 'Contenu des commentaires',
            'images'             => 'Commentaires images',
            'rating'             => 'Score',
            'reply'              => 'Contenu de la réponse',
            'is_show'            => 'Afficher ou non',
            'is_anonymous'       => 'Anonyme ou non',
            'is_reply'           => 'Répondre ou non',
            'reply_time_time'    => 'Temps de réponse',
            'add_time_time'      => 'Temps de création',
            'upd_time_time'      => 'Temps de mise à jour',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Paramètres des marchandises',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Classification des marchandises',
            'name'          => 'Le nom',
            'is_enable'     => 'Activé ou non',
            'config_count'  => 'Nombre de paramètres',
            'add_time'      => 'Temps de création',
            'upd_time'      => 'Temps de mise à jour',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Classification des marchandises',
            'name'         => 'Le nom',
            'is_enable'    => 'Activé ou non',
            'content'      => 'Spécifications valeur',
            'add_time'     => 'Temps de création',
            'upd_time'     => 'Temps de mise à jour',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informations utilisateur',
            'user_placeholder'   => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'goods'              => 'Informations sur les marchandises',
            'goods_placeholder'  => 'Veuillez saisir le nom commercial / le résumé / les informations SEO',
            'price'              => 'Prix de vente (En dollars)',
            'original_price'     => 'Prix dorigine (Yuan)',
            'add_time'           => 'Temps de création',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informations utilisateur',
            'user_placeholder'   => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'goods'              => 'Informations sur les marchandises',
            'goods_placeholder'  => 'Veuillez saisir le nom commercial / le résumé / les informations SEO',
            'price'              => 'Prix de vente (En dollars)',
            'original_price'     => 'Prix dorigine (Yuan)',
            'add_time'           => 'Temps de création',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Informations utilisateur',
            'user_placeholder'   => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'goods'              => 'Informations sur les marchandises',
            'goods_placeholder'  => 'Veuillez saisir le nom commercial / le résumé / les informations SEO',
            'price'              => 'Prix de vente (En dollars)',
            'original_price'     => 'Prix dorigine (Yuan)',
            'add_time'           => 'Temps de création',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Liens damitié',
        // 动态表格
        'form_table'                            => [
            'info'                => 'Le nom',
            'url'                 => 'Adresse URL',
            'describe'            => 'Description',
            'is_enable'           => 'Activé ou non',
            'is_new_window_open'  => 'Une nouvelle fenêtre souvre ou non',
            'sort'                => 'Trier',
            'add_time'            => 'Temps de création',
            'upd_time'            => 'Temps de mise à jour',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Navigation intermédiaire', 'type' => 'header'],
            ['name' => 'Navigation en bas', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'Personnalisation',
            'article'           => 'Articles',
            'customview'        => 'Personnaliser la page',
            'goods_category'    => 'Classification des marchandises',
            'design'            => 'Conception de la page',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Nom de la navigation',
            'data_type'           => 'Type de données de navigation',
            'is_show'             => 'Afficher ou non',
            'is_new_window_open'  => 'Une nouvelle fenêtre souvre',
            'sort'                => 'Trier',
            'add_time'            => 'Temps de création',
            'upd_time'            => 'Temps de mise à jour',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Erreur dans lid de commande',
            'express_choice_tips'               => 'Veuillez choisir une méthode Express',
            'payment_choice_tips'               => 'Veuillez sélectionner un mode de paiement',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Opérations dexpédition',
        'form_payment_title'                    => 'Opérations de paiement',
        'form_item_take'                        => 'Code de ramassage',
        'form_item_take_message'                => 'Veuillez remplir le Code de retrait à 4 chiffres',
        'form_item_express_number'              => 'Numéro de livraison express',
        'form_item_express_number_message'      => 'Veuillez remplir le numéro de lettre express',
        // 地址
        'detail_user_address_title'             => 'Adresse de réception des marchandises',
        'detail_user_address_name'              => 'Le destinataire',
        'detail_user_address_tel'               => 'Téléphone de réception',
        'detail_user_address_value'             => 'Adresse détaillée',
        'detail_user_address_idcard'            => 'Informations sur la carte didentité',
        'detail_user_address_idcard_name'       => 'Nom et prénom',
        'detail_user_address_idcard_number'     => 'Numéro',
        'detail_user_address_idcard_pic'        => 'Photos',
        'detail_take_address_title'             => 'Adresse de ramassage',
        'detail_take_address_contact'           => 'Informations de contact',
        'detail_take_address_value'             => 'Adresse détaillée',
        'detail_fictitious_title'               => 'Informations clés',
        // 订单售后
        'detail_aftersale_status'               => 'Statut',
        'detail_aftersale_type'                 => 'Type',
        'detail_aftersale_price'                => 'Montant',
        'detail_aftersale_number'               => 'Quantité',
        'detail_aftersale_reason'               => 'Les causes',
        // 商品
        'detail_goods_title'                    => 'Produits commandés',
        'detail_payment_amount_less_tips'       => 'Veuillez noter que le montant payé pour cette commande est inférieur au montant total du prix',
        'detail_no_payment_tips'                => 'Veuillez noter que cette commande na pas encore été payée',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Informations de base',
            'goods_placeholder'   => 'Veuillez entrer lid de commande / numéro de commande / nom darticle / modèle',
            'user'                => 'Informations utilisateur',
            'user_placeholder'    => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'status'              => 'Statut de la commande',
            'pay_status'          => 'État des paiements',
            'total_price'         => 'Prix total (En dollars)',
            'pay_price'           => 'Montant à payer (En dollars)',
            'price'               => 'Prix unitaire (Yuan)',
            'warehouse_name'      => 'Entrepôt de livraison',
            'order_model'         => 'Mode dordre',
            'client_type'         => 'Sources',
            'address'             => 'Informations sur ladresse',
            'take'                => 'Informations de retrait',
            'refund_price'        => 'Montant du remboursement (En dollars)',
            'returned_quantity'   => 'Nombre de retours',
            'buy_number_count'    => 'Total des achats',
            'increase_price'      => 'Montant de laugmentation (en yuans)',
            'preferential_price'  => 'Montant de loffre (En dollars)',
            'payment_name'        => 'Mode de paiement',
            'user_note'           => 'Remarques de lutilisateur',
            'extension'           => 'Informations étendues',
            'express_name'        => 'Société de livraison express',
            'express_number'      => 'Numéro de livraison express',
            'aftersale'           => 'Dernière après - vente',
            'is_comments'         => 'Les utilisateurs commentent - ils',
            'confirm_time'        => 'Confirmer lheure',
            'pay_time'            => 'Temps de paiement',
            'delivery_time'       => 'Temps dexpédition',
            'collect_time'        => 'Temps dachèvement',
            'cancel_time'         => 'Heure dannulation',
            'close_time'          => 'Heures de fermeture',
            'add_time'            => 'Temps de création',
            'upd_time'            => 'Temps de mise à jour',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => 'Audit des opérations',
        'form_refuse_title'                     => 'Refuser lopération',
        'form_user_info_title'                  => 'Informations utilisateur',
        'form_apply_info_title'                 => 'Informations sur la demande',
        'forn_apply_info_type'                  => 'Type',
        'forn_apply_info_price'                 => 'Montant',
        'forn_apply_info_number'                => 'Quantité',
        'forn_apply_info_reason'                => 'Les causes',
        'forn_apply_info_msg'                   => 'Description',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Informations de base',
            'goods_placeholder'  => 'Veuillez entrer le numéro de commande / nom darticle / modèle',
            'user'               => 'Informations utilisateur',
            'user_placeholder'   => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'status'             => 'Statut',
            'type'               => 'Type de demande',
            'reason'             => 'Les causes',
            'price'              => 'Montant du remboursement (En dollars)',
            'number'             => 'Nombre de retours',
            'msg'                => 'Instructions de remboursement',
            'refundment'         => 'Type de remboursement',
            'voucher'            => 'Le Voucher',
            'express_name'       => 'Société de livraison express',
            'express_number'     => 'Numéro de livraison express',
            'refuse_reason'      => 'Raisons du refus',
            'apply_time'         => 'Temps dapplication',
            'confirm_time'       => 'Confirmer lheure',
            'delivery_time'      => 'Temps de retour',
            'audit_time'         => 'Temps daudit',
            'add_time'           => 'Temps de création',
            'upd_time'           => 'Temps de mise à jour',
        ],
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'Mode de paiement',
        'nav_store_payment_name'                => 'Plus de thèmes à télécharger',
        'upload_top_list_tips'                  => [
            [
                'name'  => 'Le nom de la classe doit correspondre au nom du fichier (supprimer.Php), comme alipay.php prend AliPay',
            ],
            [
                'name'  => '2. Méthode que la classe doit définir',
                'item'  => [
                    '2.1 méthode de configuration de config',
                    '2.2. Méthode de paiement par pay',
                    '2.3 méthode de rappel de la réponse',
                    '2.4. Méthode de rappel asynchrone notify (optionnel, indéfini, la méthode RESPOND est appelée)',
                    '2.5. Méthode de remboursement refund (optionnel, non défini, aucun remboursement original ne peut être initié)',
                ],
            ],
            [
                'name'  => '3. Méthode de contenu de sortie personnalisable',
                'item'  => [
                    '3.1 succès du paiement successreturn (facultatif)',
                    '3.2 Échec du paiement errorreturn (facultatif)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: les conditions ci - dessus ne sont pas remplies, vous ne pouvez pas voir le plugin, mettre le plugin dans le package de compression.Zip pour le téléchargement, soutenir plusieurs plugins de paiement dans une seule compression',
        // 动态表格
        'form_table'                            => [
            'name'            => 'Le nom',
            'logo'            => 'LOGO',
            'version'         => 'Versions',
            'apply_version'   => 'Version applicable',
            'apply_terminal'  => 'Terminal applicable',
            'author'          => 'Auteur',
            'desc'            => 'Description',
            'enable'          => 'Activé ou non',
            'open_user'       => 'Utilisateurs ouverts',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'Express',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Thèmes actuels', 'type' => 'index'],
            ['name' => 'Installation du thème', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'Plus de thèmes à télécharger',
        'list_author_title'                     => 'Auteur',
        'list_version_title'                    => 'Version applicable',
        'form_theme_upload_tips'                => 'Télécharger un package dinstallation de thème au format zip compressé',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Navigation du Centre utilisateur du téléphone',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Le nom',
            'platform'       => 'La plateforme à laquelle appartient',
            'images_url'     => 'Icônes de navigation',
            'event_type'     => 'Type dévénement',
            'event_value'    => 'Valeur de lévénement',
            'desc'           => 'Description',
            'is_enable'      => 'Activé ou non',
            'is_need_login'  => 'Est - il nécessaire de se connecter',
            'sort'           => 'Trier',
            'add_time'       => 'Temps de création',
            'upd_time'       => 'Temps de mise à jour',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Mobile Home Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Le nom',
            'platform'       => 'La plateforme à laquelle appartient',
            'images'         => 'Icônes de navigation',
            'event_type'     => 'Type dévénement',
            'event_value'    => 'Valeur de lévénement',
            'is_enable'      => 'Activé ou non',
            'is_need_login'  => 'Est - il nécessaire de se connecter',
            'sort'           => 'Trier',
            'add_time'       => 'Temps de création',
            'upd_time'       => 'Temps de mise à jour',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Journal des demandes de paiement',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informations utilisateur',
            'user_placeholder'  => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'log_no'            => 'Numéro de paiement',
            'payment'           => 'Mode de paiement',
            'status'            => 'Statut',
            'total_price'       => 'Montant de lordre daffaires (META)',
            'pay_price'         => 'Montant à payer (En dollars)',
            'business_type'     => 'Type dentreprise',
            'business_list'     => 'Business ID / numéro unique',
            'trade_no'          => 'Numéro de transaction de la plateforme de paiement',
            'buyer_user'        => 'Numéro de compte utilisateur de la plateforme de paiement',
            'subject'           => 'Nom de la commande',
            'pay_time'          => 'Temps de paiement',
            'close_time'        => 'Heures de fermeture',
            'add_time'          => 'Temps de création',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Journal des demandes de paiement',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Type dentreprise',
            'request_params'   => 'Paramètres de la demande',
            'response_data'    => 'Données de réponse',
            'business_handle'  => 'Résultats du traitement des affaires',
            'request_url'      => 'Adresse URL de la demande',
            'server_port'      => 'Numéro de port',
            'server_ip'        => 'IP du serveur',
            'client_ip'        => 'IP du client',
            'os'               => 'Système dexploitation',
            'browser'          => 'Le navigateur',
            'method'           => 'Type de demande',
            'scheme'           => 'Type de http',
            'version'          => 'Version http',
            'client'           => 'Détails du client',
            'add_time'         => 'Temps de création',
            'upd_time'         => 'Temps de mise à jour',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Informations utilisateur',
            'user_placeholder'  => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'payment'           => 'Mode de paiement',
            'business_type'     => 'Type dentreprise',
            'business_id'       => 'Id de commande commerciale',
            'trade_no'          => 'Numéro de transaction de la plateforme de paiement',
            'buyer_user'        => 'Numéro de compte utilisateur de la plateforme de paiement',
            'refundment_text'   => 'Modalités de remboursement',
            'refund_price'      => 'Montant du remboursement',
            'pay_price'         => 'Montant du paiement de la commande',
            'msg'               => 'Description',
            'add_time_time'     => 'Temps de remboursement',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Retour à gestion des applications > >',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Veuillez dabord cliquer sur la coche pour activer',
            'save_no_data_tips'                 => 'Pas de données plugin à sauvegarder',
        ],
        // 基础导航
        'base_nav_title'                        => 'Application',
        'base_nav_list'                         => [
            ['name' => 'Gestion des applications', 'type' => 'index'],
            ['name' => 'Télécharger une application', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'Plus de plugins à télécharger',
        // 基础页面
        'base_search_input_placeholder'         => 'Veuillez entrer un nom / une description',
        'base_top_tips_one'                     => 'Liste Trier par [tri personnalisé - > installation la plus ancienne]',
        'base_top_tips_two'                     => 'Bouton dicône cliquable pour ajuster lordre dappel et daffichage des plugins',
        'base_open_sort_title'                  => 'Ouvrir le tri',
        'data_list_author_title'                => 'Auteur',
        'data_list_author_url_title'            => 'Page daccueil',
        'data_list_version_title'               => 'Versions',
        'uninstall_confirm_tips'                => 'La désinstallation peut - elle perdre des données de configuration de base du plugin non récupérables, action confirmée?',
        'not_install_divide_title'              => 'Les plugins suivants ne sont pas installés',
        'delete_plugins_text'                   => '1. Supprimer uniquement les applications',
        'delete_plugins_text_tips'              => '(seul le Code de lapplication est supprimé, les données de lapplication sont conservées)',
        'delete_plugins_data_text'              => '2. Supprimer lapplication et supprimer les données',
        'delete_plugins_data_text_tips'         => '(le Code de lapplication et les données de lapplication seront supprimés)',
        'delete_plugins_ps_tips'                => 'PS: aucune des opérations suivantes nest récupérable, faites preuve de prudence!',
        'delete_plugins_button_name'            => 'Supprimer uniquement les applications',
        'delete_plugins_data_button_name'       => 'Supprimer des applications et des données',
        'cancel_delete_plugins_button_name'     => 'Réfléchissez à nouveau',
        'more_plugins_store_to_text'            => 'Aller à lapp store choisir plus de plugins sites enrichis > >',
        'no_data_store_to_text'                 => 'Aller à lapp store choisir un plugin site denrichissement > >',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Retour au back office',
        'get_loading_tips'                      => 'En cours dacquisition...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'Rôle',
        'admin_not_modify_tips'                 => 'Le super Administrateur a toutes les autorisations par défaut et nest pas modifiable.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Nom du rôle',
            'is_enable'  => 'Activé ou non',
            'add_time'   => 'Temps de création',
            'upd_time'   => 'Temps de mise à jour',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'Permissions',
        'top_tips_list'                         => [
            '1. Les techniciens non professionnels ne manipulent pas les données de la page, une erreur dopération peut entraîner un menu dautorisation désordonné.',
            '2. Le menu dautorisation est divisé en deux types [utilisation, action], utilisez le menu pour ouvrir laffichage en général, le menu daction doit être masqué.',
            '3. Si le menu des autorisations est désordonné, vous pouvez remplacer la récupération de données de la table de données [ '.MyConfig('database.connections.mysql.prefix').'power ].',
            '4. [super admin, compte admin] a toutes les autorisations par défaut et ne peut pas être modifié.',
        ],
        'content_top_tips_list'                 => [
            '1. Remplir [nom du Contrôleur et nom de la méthode] doit correspondre à la création de la définition correspondante du Contrôleur et de la méthode',
            'Emplacement du fichier du Contrôleur [app / admin / Controller], cette action est uniquement utilisée par les développeurs',
            '3. Nom du Contrôleur / nom de la méthode avec ladresse URL personnalisée, les deux doivent être remplis un',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Navigation rapide',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Le nom',
            'platform'     => 'La plateforme à laquelle appartient',
            'images'       => 'Icônes de navigation',
            'event_type'   => 'Type dévénement',
            'event_value'  => 'Valeur de lévénement',
            'is_enable'    => 'Activé ou non',
            'sort'         => 'Trier',
            'add_time'     => 'Temps de création',
            'upd_time'     => 'Temps de mise à jour',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'Région',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Filtrer les prix',
        'top_tips_list'                         => [
            'Prix minimum 0 - prix maximum 100 est inférieur à 100',
            'Prix minimum 1000 - prix maximum 0 est supérieur à 1000',
            'Prix minimum 100 - prix maximum 500 est supérieur ou égal à 100 et inférieur à 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Rotation',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Le nom',
            'platform'     => 'La plateforme à laquelle appartient',
            'images'       => 'Images',
            'event_type'   => 'Type dévénement',
            'event_value'  => 'Valeur de lévénement',
            'is_enable'    => 'Activé ou non',
            'sort'         => 'Trier',
            'add_time'     => 'Temps de création',
            'upd_time'     => 'Temps de mise à jour',
        ],
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Informations utilisateur',
            'user_placeholder'    => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'type'                => 'Type dopération',
            'operation_integral'  => 'Points opérationnels',
            'original_integral'   => 'Points originaux',
            'new_integral'        => 'Derniers points',
            'msg'                 => 'Raisons opérationnelles',
            'operation_id'        => 'Opérateur id',
            'add_time_time'       => 'Temps de fonctionnement',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Informations utilisateur',
            'user_placeholder'          => 'Veuillez entrer votre nom dutilisateur / pseudonyme / téléphone portable / boîte aux lettres',
            'type'                      => 'Type de message',
            'business_type'             => 'Type dentreprise',
            'title'                     => 'Titre',
            'detail'                    => 'Détails',
            'is_read'                   => 'Est - ce Lu',
            'user_is_delete_time_text'  => 'Lutilisateur supprime - t - il',
            'add_time_time'             => 'Heure denvoi',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: les non - développeurs sil vous plaît ne pas exécuter des instructions SQL au hasard, les actions peuvent entraîner la suppression de la base de données système entière.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'Liste des meilleures applications shopxo, voici un nuage des développeurs shopxo les plus expérimentés, les plus techniquement compétents et les plus fiables pour personnaliser votre escorte complète pour vos plugins, styles et modèles.',
        'to_store_name'                         => 'Aller à lApp Store pour choisir un plugin > >',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Système de gestion de back office',
        'remove_cache_title'                    => 'Effacer le cache',
        'user_status_title'                     => 'Statut de lutilisateur',
        'user_status_message'                   => 'Veuillez sélectionner un statut dutilisateur',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Informations de configuration des paramètres des marchandises',
        'form_goods_params_copy_no_tips'        => 'Veuillez dabord coller les informations de configuration',
        'form_goods_params_copy_error_tips'     => 'Erreur de format de configuration',
        'form_goods_params_type_message'        => 'Veuillez sélectionner un paramètre darticle type daffichage',
        'form_goods_params_params_name'         => 'Nom du paramètre',
        'form_goods_params_params_message'      => 'Veuillez remplir le nom du paramètre',
        'form_goods_params_value_name'          => 'Valeur du paramètre',
        'form_goods_params_value_message'       => 'Veuillez remplir la valeur du paramètre',
        'form_goods_params_move_type_tips'      => 'Erreur de configuration du type dopération',
        'form_goods_params_move_top_tips'       => 'A atteint le Sommet',
        'form_goods_params_move_bottom_tips'    => 'A atteint le bas',
        'form_goods_params_thead_type_title'    => 'Gamme daffichage',
        'form_goods_params_thead_name_title'    => 'Nom du paramètre',
        'form_goods_params_thead_value_title'   => 'Valeur du paramètre',
        'form_goods_params_row_add_title'       => 'Ajouter une ligne',
        'form_goods_params_list_tips'           => [
            '1. Tous (tous affichés sous linformation de base de marchandises et les paramètres de détail)',
            '2. Détails (tous affichés uniquement sous les paramètres de détail des marchandises)',
            '3. Base (tous affichés uniquement sous linformation de base des marchandises)',
            '4. Laction rapide effacera les données dorigine, surcharge de la page peut restaurer les données dorigine (ne prend effet quaprès lenregistrement des marchandises)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Paramètres du système',
            'item'  => [
                'config_index'                 => 'Configuration du système',
                'config_store'                 => 'Informations sur la boutique',
                'config_save'                  => 'Configurer la sauvegarde',
                'index_storeaccountsbind'      => 'Liaison de compte app store',
                'index_inspectupgrade'         => 'Vérification des mises à jour du système',
                'index_inspectupgradeconfirm'  => 'Confirmation de la mise à jour du système',
                'index_stats'                  => 'Home statistiques',
                'index_income'                 => 'Home statistiques (statistiques des revenus)',
            ]
        ],
        'site_index' => [
            'name'  => 'Configuration du site',
            'item'  => [
                'site_index'                  => 'Paramètres du site',
                'site_save'                   => 'Paramètres du site Modifier',
                'site_goodssearch'            => 'Paramètres du site recherche de marchandises',
                'layout_layoutindexhomesave'  => 'Accueil Layout Management',
                'sms_index'                   => 'Paramètres SMS',
                'sms_save'                    => 'Paramètres SMS Modifier',
                'email_index'                 => 'Paramètres de boîte aux lettres',
                'email_save'                  => 'Configuration / modification de la boîte aux lettres',
                'email_emailtest'             => 'Test denvoi de courrier',
                'seo_index'                   => 'Paramètres SEO',
                'seo_save'                    => 'Paramètres SEO Modifier',
                'agreement_index'             => 'Gestion des accords',
                'agreement_save'              => 'Paramètres du Protocole Modifier',
            ]
        ],
        'power_index' => [
            'name'  => 'Contrôle des permissions',
            'item'  => [
                'admin_index'        => 'Liste des administrateurs',
                'admin_saveinfo'     => 'Admin ajouter / modifier des pages',
                'admin_save'         => 'Admin ajouter / modifier',
                'admin_delete'       => 'Administrateur Supprimer',
                'admin_detail'       => 'Détails de lAdministrateur',
                'role_index'         => 'Gestion des rôles',
                'role_saveinfo'      => 'Groupes de rôles ajouter / modifier des pages',
                'role_save'          => 'Groupe de rôles ajouter / modifier',
                'role_delete'        => 'Suppression de rôle',
                'role_statusupdate'  => 'Mise à jour du Statut du rôle',
                'role_detail'        => 'Détails du rôle',
                'power_index'        => 'Attribution des permissions',
                'power_save'         => 'Permissions ajouter / modifier',
                'power_delete'       => 'Autorisations Supprimer',
            ]
        ],
        'user_index' => [
            'name'  => 'Gestion des utilisateurs',
            'item'  => [
                'user_index'            => 'Liste des utilisateurs',
                'user_saveinfo'         => 'Modification / ajout de pages par lutilisateur',
                'user_save'             => 'Utilisateur ajouter / modifier',
                'user_delete'           => 'Suppression par lutilisateur',
                'user_detail'           => 'Détails de lutilisateur',
                'useraddress_index'     => 'Adresse de lutilisateur',
                'useraddress_saveinfo'  => 'Adresse de lutilisateur modifier la page',
                'useraddress_save'      => 'Adresse de lutilisateur Modifier',
                'useraddress_delete'    => 'Adresse utilisateur Supprimer',
                'useraddress_detail'    => 'Détails de ladresse de lutilisateur',
            ]
        ],
        'goods_index' => [
            'name'  => 'Gestion des marchandises',
            'item'  => [
                'goods_index'                       => 'Gestion des marchandises',
                'goods_saveinfo'                    => 'Article ajouter / modifier une page',
                'goods_save'                        => 'Article ajouter / modifier',
                'goods_delete'                      => 'Marchandises Supprimer',
                'goods_statusupdate'                => 'Mise à jour du statut des marchandises',
                'goods_basetemplate'                => 'Obtenir un modèle de base de marchandises',
                'goods_detail'                      => 'Détails des marchandises',
                'goodscategory_index'               => 'Classification des marchandises',
                'goodscategory_save'                => 'Classification des produits ajouter / modifier',
                'goodscategory_delete'              => 'Classification des marchandises Supprimer',
                'goodsparamstemplate_index'         => 'Paramètres des marchandises',
                'goodsparamstemplate_delete'        => 'Paramètres des marchandises Supprimer',
                'goodsparamstemplate_statusupdate'  => 'Mise à jour du statut des paramètres des marchandises',
                'goodsparamstemplate_saveinfo'      => 'Paramètres des marchandises ajouter / modifier une page',
                'goodsparamstemplate_save'          => 'Paramètres des marchandises ajouter / modifier',
                'goodsparamstemplate_detail'        => 'Détails des paramètres des marchandises',
                'goodsspectemplate_index'           => 'Spécifications des marchandises',
                'goodsspectemplate_delete'          => 'Spécifications des marchandises Supprimer',
                'goodsspectemplate_statusupdate'    => 'Mise à jour du statut des spécifications des marchandises',
                'goodsspectemplate_saveinfo'        => 'Spécifications de larticle ajouter / modifier la page',
                'goodsspectemplate_save'            => 'Spécifications de larticle ajouter / modifier',
                'goodsspectemplate_detail'          => 'Détails des spécifications des marchandises',
                'goodscomments_detail'              => 'Détails des commentaires sur les produits',
                'goodscomments_index'               => 'Commentaires sur les produits',
                'goodscomments_reply'               => 'Commentaires sur les produits Réponse',
                'goodscomments_delete'              => 'Commentaires sur les produits Supprimer',
                'goodscomments_statusupdate'        => 'Mise à jour du statut des commentaires sur les produits',
                'goodscomments_saveinfo'            => 'Article commentaires ajouter / modifier une page',
                'goodscomments_save'                => 'Article commentaires ajouter / modifier',
                'goodsbrowse_index'                 => 'Navigation des produits',
                'goodsbrowse_delete'                => 'Commodity browse Supprimer',
                'goodsbrowse_detail'                => 'Détails de navigation des produits',
                'goodsfavor_index'                  => 'Collection de marchandises',
                'goodsfavor_delete'                 => 'Collection de marchandises Supprimer',
                'goodsfavor_detail'                 => 'Détails de la collection de marchandises',
                'goodscart_index'                   => 'Panier dachat de marchandises',
                'goodscart_delete'                  => 'Article panier Supprimer',
                'goodscart_detail'                  => 'Détails du panier dachat darticles',
            ]
        ],
        'order_index' => [
            'name'  => 'Gestion des commandes',
            'item'  => [
                'order_index'             => 'Gestion des commandes',
                'order_delete'            => 'Ordre supprimé',
                'order_cancel'            => 'Annulation de commande',
                'order_delivery'          => 'Expédition des commandes',
                'order_collect'           => 'Réception des commandes',
                'order_pay'               => 'Paiement de la commande',
                'order_confirm'           => 'Confirmation de commande',
                'order_detail'            => 'Détails de la commande',
                'orderaftersale_index'    => 'Après la commande',
                'orderaftersale_delete'   => 'Suppression après vente de la commande',
                'orderaftersale_cancel'   => 'Annulation de la commande après vente',
                'orderaftersale_audit'    => 'Audit après - vente des commandes',
                'orderaftersale_confirm'  => 'Confirmation de commande après vente',
                'orderaftersale_refuse'   => 'Refus après vente de la commande',
                'orderaftersale_detail'   => 'Détails après - vente de la commande',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Gestion du site',
            'item'  => [
                'navigation_index'         => 'Gestion de la navigation',
                'navigation_save'          => 'Navigation ajouter / modifier',
                'navigation_delete'        => 'Navigation Supprimer',
                'navigation_statusupdate'  => 'Mise à jour du statut de navigation',
                'customview_index'         => 'Personnaliser la page',
                'customview_saveinfo'      => 'Personnaliser une page ajouter / modifier une page',
                'customview_save'          => 'Personnaliser la page ajouter / modifier',
                'customview_delete'        => 'Personnaliser la suppression de la page',
                'customview_statusupdate'  => 'Personnaliser la mise à jour de létat de la page',
                'customview_detail'        => 'Personnaliser les détails de la page',
                'link_index'               => 'Liens damitié',
                'link_saveinfo'            => 'Liens damitié ajouter / modifier une page',
                'link_save'                => 'Liens damitié ajouter / modifier',
                'link_delete'              => 'Lien damitié Supprimer',
                'link_statusupdate'        => 'Mise à jour du Statut du lien damitié',
                'link_detail'              => 'Détails des liens damitié',
                'theme_index'              => 'Gestion thématique',
                'theme_save'               => 'Gestion des thèmes ajouter / modifier',
                'theme_upload'             => 'Thème upload installation',
                'theme_delete'             => 'Sujet Supprimer',
                'theme_download'           => 'Thème télécharger',
                'slide_index'              => 'Accueil Carrousel',
                'slide_saveinfo'           => 'Carrousel ajouter / modifier des pages',
                'slide_save'               => 'Carrousel ajouter / modifier',
                'slide_statusupdate'       => 'Mise à jour du Statut du Carrousel',
                'slide_delete'             => 'Rotation Supprimer',
                'slide_detail'             => 'Détails du Carrousel',
                'screeningprice_index'     => 'Filtrer les prix',
                'screeningprice_save'      => 'Filtrer prix ajouter / modifier',
                'screeningprice_delete'    => 'Filtrer prix Supprimer',
                'region_index'             => 'Gestion régionale',
                'region_save'              => 'Région ajouter / modifier',
                'region_delete'            => 'Région Supprimer',
                'region_codedata'          => 'Obtenir des données de numéro de région',
                'express_index'            => 'Gestion Express',
                'express_save'             => 'Express ajouter / modifier',
                'express_delete'           => 'Express Supprimer',
                'payment_index'            => 'Mode de paiement',
                'payment_saveinfo'         => 'Méthode de paiement installer / modifier la page',
                'payment_save'             => 'Moyens de paiement installation / modification',
                'payment_delete'           => 'Méthode de paiement Supprimer',
                'payment_install'          => 'Mode de paiement installation',
                'payment_statusupdate'     => 'Mise à jour du statut des méthodes de paiement',
                'payment_uninstall'        => 'Méthode de paiement désinstaller',
                'payment_upload'           => 'Méthode de paiement upload',
                'quicknav_index'           => 'Navigation rapide',
                'quicknav_saveinfo'        => 'Navigation rapide ajouter / modifier des pages',
                'quicknav_save'            => 'Navigation rapide ajouter / modifier',
                'quicknav_statusupdate'    => 'Mise à jour rapide du statut de navigation',
                'quicknav_delete'          => 'Navigation rapide Supprimer',
                'quicknav_detail'          => 'Détails de navigation rapide',
                'design_index'             => 'Conception de la page',
                'design_saveinfo'          => 'Page Design ajouter / modifier une page',
                'design_save'              => 'Page Design ajouter / modifier',
                'design_statusupdate'      => 'Mise à jour de létat de conception de la page',
                'design_upload'            => 'Importation de design de page',
                'design_download'          => 'Page Design télécharger',
                'design_sync'              => 'Page Design sync accueil',
                'design_delete'            => 'Page Design Supprimer',
            ]
        ],
        'brand_index' => [
            'name'  => 'Gestion de la marque',
            'item'  => [
                'brand_index'           => 'Gestion de la marque',
                'brand_saveinfo'        => 'Marques ajouter / modifier une page',
                'brand_save'            => 'Marque ajouter / modifier',
                'brand_statusupdate'    => 'Mise à jour du statut de la marque',
                'brand_delete'          => 'Suppression de la marque',
                'brand_detail'          => 'Détails de la marque',
                'brandcategory_index'   => 'Classification des marques',
                'brandcategory_save'    => 'Marques catégories ajouter / modifier',
                'brandcategory_delete'  => 'Catégorie de marque Supprimer',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Gestion des entrepôts',
            'item'  => [
                'warehouse_index'               => 'Gestion des entrepôts',
                'warehouse_saveinfo'            => 'Entrepôt ajouter / modifier des pages',
                'warehouse_save'                => 'Entrepôt ajouter / modifier',
                'warehouse_delete'              => 'Entrepôt Supprimer',
                'warehouse_statusupdate'        => 'Mise à jour de létat de lentrepôt',
                'warehouse_detail'              => 'Détails de lentrepôt',
                'warehousegoods_index'          => 'Gestion des marchandises en entrepôt',
                'warehousegoods_detail'         => 'Détails des marchandises dentrepôt',
                'warehousegoods_delete'         => 'Entrepôt marchandises Supprimer',
                'warehousegoods_statusupdate'   => 'Mise à jour de létat des marchandises de lentrepôt',
                'warehousegoods_goodssearch'    => 'Recherche de produits dentrepôt',
                'warehousegoods_goodsadd'       => 'Entrepôt Merchandise Search ajouter',
                'warehousegoods_goodsdel'       => 'Warehouse Merchandise Search Supprimer',
                'warehousegoods_inventoryinfo'  => 'Stock de marchandises dentrepôt modifier la page',
                'warehousegoods_inventorysave'  => 'Stock de marchandises dentrepôt Modifier',
            ]
        ],
        'app_index' => [
            'name'  => 'Gestion des téléphones portables',
            'item'  => [
                'appconfig_index'            => 'Configuration de base',
                'appconfig_save'             => 'Sauvegarde de la configuration de base',
                'apphomenav_index'           => 'Home Navigation',
                'apphomenav_saveinfo'        => 'Home navigation ajouter / modifier une page',
                'apphomenav_save'            => 'Accueil navigation ajouter / modifier',
                'apphomenav_statusupdate'    => 'Home navigation mise à jour du Statut',
                'apphomenav_delete'          => 'Home navigation Supprimer',
                'apphomenav_detail'          => 'Home navigation détails',
                'appcenternav_index'         => 'Navigation du Centre utilisateur',
                'appcenternav_saveinfo'      => 'Navigation du Centre utilisateur ajouter / modifier des pages',
                'appcenternav_save'          => 'Navigation du Centre utilisateur ajouter / modifier',
                'appcenternav_statusupdate'  => 'Mise à jour du statut de navigation du Centre utilisateur',
                'appcenternav_delete'        => 'Navigation du Centre utilisateur Supprimer',
                'appcenternav_detail'        => 'Détails de navigation du Centre utilisateur',
                'appmini_index'              => 'Liste des applets',
                'appmini_created'            => 'Génération de paquets dapplets',
                'appmini_delete'             => 'Suppression des applets Packages',
                'appmini_themeupload'        => 'Applet thème upload',
                'appmini_themesave'          => 'Applet changement de thème',
                'appmini_themedelete'        => 'Applet changement de thème',
                'appmini_themedownload'      => 'Applet Theme télécharger',
                'appmini_config'             => 'Configuration des applets',
                'appmini_save'               => 'Sauvegarde de la configuration des applets',
            ]
        ],
        'article_index' => [
            'name'  => 'Gestion des articles',
            'item'  => [
                'article_index'           => 'Gestion des articles',
                'article_saveinfo'        => 'Articles ajouter / modifier une page',
                'article_save'            => 'Article ajouter / modifier',
                'article_delete'          => 'Article supprimé',
                'article_statusupdate'    => 'Mise à jour du statut de larticle',
                'article_detail'          => 'Détails de larticle',
                'articlecategory_index'   => 'Catégories darticles',
                'articlecategory_save'    => 'Articles catégories Edit / Add',
                'articlecategory_delete'  => 'Article catégories Supprimer',
            ]
        ],
        'data_index' => [
            'name'  => 'Gestion des données',
            'item'  => [
                'answer_index'          => 'Questions et réponses message',
                'answer_reply'          => 'Questions et réponses laisser un commentaire répondre',
                'answer_delete'         => 'Questions et réponses message Supprimer',
                'answer_statusupdate'   => 'Questions et réponses message mise à jour du Statut',
                'answer_saveinfo'       => 'Questions et réponses ajouter / modifier une page',
                'answer_save'           => 'Questions et réponses ajouter / modifier',
                'answer_detail'         => 'Questions et réponses message détails',
                'message_index'         => 'Gestion des messages',
                'message_delete'        => 'Message supprimé',
                'message_detail'        => 'Détails du message',
                'paylog_index'          => 'Journal des paiements',
                'paylog_detail'         => 'Détails du Journal de paiement',
                'paylog_close'          => 'Journal des paiements fermer',
                'payrequestlog_index'   => 'Liste des journaux des demandes de paiement',
                'payrequestlog_detail'  => 'Détails du Journal des demandes de paiement',
                'refundlog_index'       => 'Journal des remboursements',
                'refundlog_detail'      => 'Détails du Journal de remboursement',
                'integrallog_index'     => 'Journal des points',
                'integrallog_detail'    => 'Détails du Journal des points',
            ]
        ],
        'store_index' => [
            'name'  => 'Centre dapplication',
            'item'  => [
                'pluginsadmin_index'         => 'Gestion des applications',
                'plugins_index'              => 'Appliquer la gestion des appels',
                'pluginsadmin_saveinfo'      => 'Apply ajouter / modifier une page',
                'pluginsadmin_save'          => 'Apply ajouter / modifier',
                'pluginsadmin_statusupdate'  => 'Mise à jour du statut de lapplication',
                'pluginsadmin_delete'        => 'Appliquer la suppression',
                'pluginsadmin_upload'        => 'Téléchargement dapplication',
                'pluginsadmin_download'      => 'Packaging dapplication',
                'pluginsadmin_install'       => 'Installation de lapplication',
                'pluginsadmin_uninstall'     => 'Désinstallation de lapplication',
                'pluginsadmin_sortsave'      => 'Appliquer le tri enregistrer',
                'store_index'                => 'L app store',
                'packageinstall_index'       => 'Page dinstallation du paquet',
                'packageinstall_install'     => 'Installation du paquet',
                'packageupgrade_upgrade'     => 'Mise à jour du package',
            ]
        ],
        'tool_index' => [
            'name'  => 'Outils',
                'item'                  => [
                'cache_index'           => 'Gestion du cache',
                'cache_statusupdate'    => 'Mise à jour du cache du site',
                'cache_templateupdate'  => 'Mise à jour du cache du modèle',
                'cache_moduleupdate'    => 'Mise à jour du cache du module',
                'cache_logdelete'       => 'Suppression des logs',
                'sqlconsole_index'      => 'Console SQL',
                'sqlconsole_implement'  => 'Exécution SQL',
            ]
        ],
    ],
];
?>