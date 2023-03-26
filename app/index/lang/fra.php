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
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Le centre commercial Home',
        'back_to_the_home_title'                => 'Retour à la page daccueil',
        'all_category_text'                     => 'Toutes les catégories',
        'login_title'                           => 'Se connecter',
        'register_title'                        => 'Enregistrement',
        'logout_title'                          => 'Quitter',
        'cancel_text'                           => 'Annulation',
        'save_text'                             => 'Sauvegarder',
        'more_text'                             => 'Plus',
        'processing_in_text'                    => 'Dans le traitement...',
        'upload_in_text'                        => 'Dans upload...',
        'navigation_main_quick_name'            => 'La boîte aux trésors',
        'no_relevant_data_tips'                 => 'Aucune donnée pertinente',
        'avatar_upload_title'                   => 'Avatar upload',
        'choice_images_text'                    => 'Sélectionner une image',
        'choice_images_error_tips'              => 'Veuillez sélectionner une image que vous devez télécharger',
        'confirm_upload_title'                  => 'Confirmer le téléchargement',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Bonjour et bienvenue à',
        'header_top_nav_left_login_first'       => 'Bonjour',
        'header_top_nav_left_login_last'        => ', bienvenue à',
        // 搜索
        'search_input_placeholder'              => 'En fait, la recherche est simple ^ ^!',
        'search_button_text'                    => 'Rechercher',
        // 用户
        'avatar_upload_tips'                    => [
            'Sil vous plaît zoomer sur la zone de travail pour réduire et déplacer la boîte de sélection, sélectionnez la plage à recadrer, recadrer la largeur et la hauteur fixe;',
            'Leffet après recadrage est affiché dans laperçu de droite, valide après confirmation de la soumission;',
        ],
        'close_user_register_tips'              => 'Fermer temporairement linscription des utilisateurs',
        'close_user_login_tips'                 => 'Fermer temporairement le login de lutilisateur',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Bonjour et bienvenue à',
        'banner_right_article_title'            => 'Titre de linformation',
        'design_browser_seo_title'              => 'Home Design',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Aucune donnée de commentaire',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Article inexistant ou supprimé',
        'panel_can_choice_spec_name'            => 'Spécifications optionnelles',
        'recommend_goods_title'                 => 'Regarde et regarde',
        'dynamic_scoring_name'                  => 'Score dynamique',
        'no_scoring_data_tips'                  => 'Pas de données de score',
        'no_comments_data_tips'                 => 'Aucune donnée dévaluation',
        'comments_first_name'                   => 'Commentaires sur',
        'admin_reply_name'                      => 'Réponse de lAdministrateur:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Recherche de produits',
        'filter_out_first_text'                 => 'Filtrer hors',
        'filter_out_last_data_text'             => 'Données de larticle',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Classification des marchandises',
        'no_category_data_tips'                 => 'Pas de données catégorielles',
        'no_sub_category_data_tips'             => 'Pas de données de sous - Classification',
        'view_category_sub_goods_name'          => 'Voir les produits sous catégories',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Veuillez sélectionner un article',
        ],
        // 基础
        'browser_seo_title'                     => 'Panier dachat',
        'goods_list_thead_base'                 => 'Informations sur les marchandises',
        'goods_list_thead_price'                => 'Prix unitaire',
        'goods_list_thead_number'               => 'Quantité',
        'goods_list_thead_total'                => 'Montant',
        'goods_item_total_name'                 => 'Total',
        'summary_selected_goods_name'           => 'Marchandises sélectionnées',
        'summary_selected_goods_unit'           => 'Pièces',
        'summary_nav_goods_total'               => 'Total:',
        'summary_nav_button_name'               => 'Règlement',
        'no_cart_data_tips'                     => 'Votre panier est encore vide, vous pouvez',
        'no_cart_data_my_favor_name'            => 'Mes Favoris',
        'no_cart_data_my_order_name'            => 'Ma commande',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Veuillez sélectionner une adresse',
            'payment_choice_tips'               => 'Veuillez choisir de payer',
        ],
        // 基础
        'browser_seo_title'                     => 'Confirmation de commande',
        'exhibition_not_allow_submit_tips'      => 'Type daffichage ne permet pas de soumettre des commandes',
        'buy_item_order_title'                  => 'Informations sur la commande',
        'buy_item_payment_title'                => 'Choisir de payer',
        'confirm_delivery_address_name'         => 'Confirmer ladresse de réception',
        'use_new_address_name'                  => 'Utiliser la nouvelle adresse',
        'no_delivery_address_tips'              => 'Pas dadresse de réception',
        'confirm_extraction_address_name'       => 'Confirmer ladresse du point dauto - levée',
        'choice_take_address_name'              => 'Choisissez une adresse de ramassage',
        'no_take_address_tips'                  => 'Veuillez contacter lAdministrateur pour configurer ladresse du point dauto - levée',
        'no_address_tips'                       => 'Pas dadresse',
        'extraction_list_choice_title'          => 'Auto - Sélection',
        'goods_list_thead_base'                 => 'Informations sur les marchandises',
        'goods_list_thead_price'                => 'Prix unitaire',
        'goods_list_thead_number'               => 'Quantité',
        'goods_list_thead_total'                => 'Montant',
        'goods_item_total_name'                 => 'Total',
        'not_goods_tips'                        => 'Aucune marchandise',
        'not_payment_tips'                      => 'Aucun moyen de paiement',
        'user_message_title'                    => 'Message de lacheteur',
        'user_message_placeholder'              => 'Remplissage, suggestions de remplissage et instructions convenues par le vendeur',
        'summary_title'                         => 'Paiement réel:',
        'summary_contact_name'                  => 'Personne à contacter:',
        'summary_address'                       => 'Adresse:',
        'summary_submit_order_name'             => 'Soumettre une commande',
        'payment_layer_title'                   => 'Paiement en cours de saut, ne fermez pas la page',
        'payment_layer_content'                 => 'Échec du paiement ou absence de réponse prolongée',
        'payment_layer_order_button_text'       => 'Ma commande',
        'payment_layer_tips'                    => 'Le paiement peut être relancé après',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Tous les articles',
        'article_no_data_tips'                  => 'Article inexistant ou supprimé',
        'article_id_params_tips'                => 'Lid de larticle est erroné',
        'release_time'                          => 'Heure de publication:',
        'view_number'                           => 'Nombre de vues:',
        'prev_article'                          => 'Précédent:',
        'next_article'                          => 'Suivant:',
        'article_category_name'                 => 'Catégories darticles',
        'article_nav_text'                      => 'Navigation dans la barre latérale',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'Page inexistante ou supprimée',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'Page inexistante ou supprimée',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Erreur dans lid de commande',
            'payment_choice_tips'               => 'Veuillez sélectionner un mode de paiement',
            'rating_string'                     => 'Très mauvais mauvais moyen bon très bon',
            'not_choice_data_tips'              => 'Veuillez dabord sélectionner les données',
            'pay_url_empty_tips'                => 'Ladresse URL de paiement est erronée',
        ],
        // 基础
        'browser_seo_title'                     => 'Ma commande',
        'detail_browser_seo_title'              => 'Détails de la commande',
        'comments_browser_seo_title'            => 'Commentaires sur la commande',
        'batch_payment_name'                    => 'Paiement en vrac',
        'comments_goods_list_thead_base'        => 'Informations sur les marchandises',
        'comments_goods_list_thead_price'       => 'Prix unitaire',
        'comments_goods_list_thead_content'     => 'Contenu des commentaires',
        'form_you_have_commented_tips'          => 'Vous avez commenté',
        'form_payment_title'                    => 'Paiement',
        'form_payment_no_data_tips'             => 'Aucun moyen de paiement',
        'order_base_title'                      => 'Informations sur la commande',
        'order_base_warehouse_title'            => 'Service de livraison:',
        'order_base_model_title'                => 'Mode de commande:',
        'order_base_order_no_title'             => 'Numéro de commande:',
        'order_base_status_title'               => 'Statut de la commande:',
        'order_base_pay_status_title'           => 'État du paiement:',
        'order_base_payment_title'              => 'Mode de paiement:',
        'order_base_total_price_title'          => 'Prix total de la commande:',
        'order_base_buy_number_title'           => 'Quantité achetée:',
        'order_base_returned_quantity_title'    => 'Nombre de retours:',
        'order_base_user_note_title'            => 'Message de lutilisateur:',
        'order_base_add_time_title'             => 'Temps pour passer une commande:',
        'order_base_confirm_time_title'         => 'Heure de confirmation:',
        'order_base_pay_time_title'             => 'Délai de paiement:',
        'order_base_delivery_time_title'        => 'Délai de livraison:',
        'order_base_collect_time_title'         => 'Heure de réception:',
        'order_base_user_comments_time_title'   => 'Heure des commentaires:',
        'order_base_cancel_time_title'          => 'Heure dannulation:',
        'order_base_express_title'              => 'Société de messagerie:',
        'order_base_express_website_title'      => 'Site officiel Express:',
        'order_base_express_number_title'       => 'Numéro de livraison express:',
        'order_base_price_title'                => 'Prix total de larticle:',
        'order_base_increase_price_title'       => 'Montant de laugmentation:',
        'order_base_preferential_price_title'   => 'Montant de loffre:',
        'order_base_refund_price_title'         => 'Montant du remboursement:',
        'order_base_pay_price_title'            => 'Montant à payer:',
        'order_base_take_code_title'            => 'Code de ramassage:',
        'order_base_take_code_no_exist_tips'    => 'Le Code de retrait nexiste pas, veuillez contacter lAdministrateur',
        'order_under_line_tips'                 => 'Actuellement, le mode de paiement hors ligne [{:payment}], sous réserve de confirmation par lAdministrateur, peut être activé et dautres paiements peuvent être changés pour relancer le paiement.',
        'order_delivery_tips'                   => 'Les marchandises sont emballées dans l’entrepôt, hors stock…',
        'order_goods_no_data_tips'              => 'Pas de données sur les articles commandés',
        'order_status_operate_first_tips'       => 'Vous pouvez',
        'goods_list_thead_base'                 => 'Informations sur les marchandises',
        'goods_list_thead_price'                => 'Prix unitaire',
        'goods_list_thead_number'               => 'Quantité',
        'goods_list_thead_total'                => 'Montant',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Informations de base',
            'goods_placeholder'     => 'Veuillez entrer le numéro de commande / nom darticle / modèle',
            'status'                => 'Statut de la commande',
            'pay_status'            => 'État des paiements',
            'total_price'           => 'Prix total (En dollars)',
            'pay_price'             => 'Montant à payer (En dollars)',
            'price'                 => 'Prix unitaire (Yuan)',
            'order_model'           => 'Mode dordre',
            'client_type'           => 'Plateforme pour passer des commandes',
            'address'               => 'Informations sur ladresse',
            'take'                  => 'Informations de retrait',
            'refund_price'          => 'Montant du remboursement (En dollars)',
            'returned_quantity'     => 'Nombre de retours',
            'buy_number_count'      => 'Total des achats',
            'increase_price'        => 'Montant de laugmentation (en yuans)',
            'preferential_price'    => 'Montant de loffre (En dollars)',
            'payment_name'          => 'Mode de paiement',
            'user_note'             => 'Message dinformation',
            'extension'             => 'Informations étendues',
            'express_name'          => 'Société de livraison express',
            'express_number'        => 'Numéro de livraison express',
            'is_comments'           => 'Commentaires ou pas',
            'confirm_time'          => 'Confirmer lheure',
            'pay_time'              => 'Temps de paiement',
            'delivery_time'         => 'Temps dexpédition',
            'collect_time'          => 'Temps dachèvement',
            'cancel_time'           => 'Heure dannulation',
            'close_time'            => 'Heures de fermeture',
            'add_time'              => 'Temps de création',
            'upd_time'              => 'Temps de mise à jour',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Total des commandes',
            'pay_price'             => 'Total des paiements',
            'buy_number_count'      => 'Total des marchandises',
            'refund_price'          => 'Remboursement',
            'returned_quantity'     => 'Retour de marchandises',
            'price_unit'            => 'Le dollar',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Les données de motif de remboursement sont vides',
        ],
        // 基础
        'browser_seo_title'                     => 'Après la commande',
        'detail_browser_seo_title'              => 'Détails après - vente de la commande',
        'view_orderaftersale_enter_name'        => 'Voir les commandes après - vente',
        'operate_delivery_name'                 => 'Retour immédiat',
        'goods_list_thead_base'                 => 'Informations sur les marchandises',
        'goods_list_thead_price'                => 'Prix unitaire',
        'goods_base_price_title'                => 'Prix total de larticle:',
        'goods_base_increase_price_title'       => 'Montant de laugmentation:',
        'goods_base_preferential_price_title'   => 'Montant de loffre:',
        'goods_base_refund_price_title'         => 'Montant du remboursement:',
        'goods_base_pay_price_title'            => 'Montant à payer:',
        'goods_base_total_price_title'          => 'Prix total de la commande:',
        'base_apply_title'                      => 'Informations sur la demande',
        'base_apply_type_title'                 => 'Type de remboursement:',
        'base_apply_status_title'               => 'État actuel:',
        'base_apply_reason_title'               => 'Raison de lapplication:',
        'base_apply_number_title'               => 'Nombre de retours:',
        'base_apply_price_title'                => 'Montant du remboursement:',
        'base_apply_msg_title'                  => 'Description du remboursement:',
        'base_apply_refundment_title'           => 'Modalités de remboursement:',
        'base_apply_refuse_reason_title'        => 'Raisons du refus:',
        'base_apply_apply_time_title'           => 'Heure dapplication:',
        'base_apply_confirm_time_title'         => 'Heure de confirmation:',
        'base_apply_delivery_time_title'        => 'Temps de retour:',
        'base_apply_audit_time_title'           => 'Temps daudit:',
        'base_apply_cancel_time_title'          => 'Heure dannulation:',
        'base_apply_add_time_title'             => 'Ajouter le temps:',
        'base_apply_upd_time_title'             => 'Temps de mise à jour:',
        'base_item_express_title'               => 'Informations Express',
        'base_item_express_name'                => 'Express:',
        'base_item_express_number'              => 'Numéro unique:',
        'base_item_express_time'                => 'Le temps:',
        'base_item_voucher_title'               => 'Le Voucher',
        // 表单
        'form_delivery_title'                   => 'Opérations de retour',
        'form_delivery_address_name'            => 'Adresse de retour',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Informations de base',
            'goods_placeholder'     => 'Veuillez entrer le numéro de commande / nom darticle / modèle',
            'status'                => 'Statut',
            'type'                  => 'Type de demande',
            'reason'                => 'Les causes',
            'price'                 => 'Montant du remboursement (En dollars)',
            'number'                => 'Nombre de retours',
            'msg'                   => 'Instructions de remboursement',
            'refundment'            => 'Type de remboursement',
            'express_name'          => 'Société de livraison express',
            'express_number'        => 'Numéro de livraison express',
            'apply_time'            => 'Temps dapplication',
            'confirm_time'          => 'Confirmer lheure',
            'delivery_time'         => 'Temps de retour',
            'audit_time'            => 'Temps daudit',
            'add_time'              => 'Temps de création',
            'upd_time'              => 'Temps de mise à jour',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Centre utilisateur',
        'forget_password_browser_seo_title'     => 'Récupération du mot de passe',
        'user_register_browser_seo_title'       => 'Enregistrement des utilisateurs',
        'user_login_browser_seo_title'          => 'Login utilisateur',
        'password_reset_illegal_error_tips'     => 'Déjà connecté, pour réinitialiser votre mot de passe, quittez dabord votre compte actuel',
        'register_illegal_error_tips'           => 'Déjà connecté, pour créer un nouveau compte, quittez dabord votre compte actuel',
        'login_illegal_error_tips'              => 'Déjà connecté, ne répétez pas la connexion',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Pas encore de compte?',
        'login_close_tips'                      => 'Connexion temporairement fermée',
        'login_type_username_title'             => 'Numéro de compte mot de passe',
        'login_type_mobile_title'               => 'Code de vérification du téléphone portable',
        'login_type_email_title'                => 'Code de vérification de la boîte aux lettres',
        'login_retrieve_password_title'         => 'Récupérer le mot de passe',
        // 注册
        'register_top_login_tips'               => 'Je suis déjà inscrit maintenant',
        'register_close_tips'                   => 'Inscription temporairement fermée',
        'register_type_username_title'          => 'Enregistrement du numéro de compte',
        'register_type_mobile_title'            => 'Enregistrement mobile',
        'register_type_email_title'             => 'Inscription à la boîte aux lettres',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Vous avez déjà un compte?',
        // 表单
        'form_item_agreement'                   => 'Lire et accepter',
        'form_item_agreement_message'           => 'Veuillez cocher lAccord de consentement',
        'form_item_service'                     => 'Accord sur les services',
        'form_item_privacy'                     => 'Politique de confidentialité',
        'form_item_username'                    => 'Nom dutilisateur',
        'form_item_username_message'            => 'Sil vous plaît utiliser des lettres, des chiffres, soulignement 2 ~ 18 caractères',
        'form_item_password'                    => 'Login mot de passe',
        'form_item_password_placeholder'        => 'Veuillez entrer votre mot de passe de connexion',
        'form_item_password_message'            => 'Format de mot de passe entre 6 ~ 18 caractères',
        'form_item_mobile'                      => 'Numéro de téléphone portable',
        'form_item_mobile_placeholder'          => 'Veuillez entrer un numéro de téléphone portable',
        'form_item_mobile_message'              => 'Format incorrect du numéro de téléphone portable',
        'form_item_email'                       => 'E - mail',
        'form_item_email_placeholder'           => 'Veuillez entrer votre e - mail',
        'form_item_email_message'               => 'Format incorrect de la boîte e - mail',
        'form_item_account'                     => 'Login numéro de compte',
        'form_item_account_placeholder'         => 'Veuillez entrer votre nom dutilisateur / téléphone portable / boîte aux lettres',
        'form_item_account_message'             => 'Veuillez entrer le numéro de compte de connexion',
        'form_item_mobile_email'                => 'Téléphone portable / boîte aux lettres',
        'form_item_mobile_email_message'        => 'Veuillez entrer un format de téléphone / boîte aux lettres valide',
        // 个人中心
        'base_avatar_title'                     => 'Modifier lAvatar',
        'base_personal_title'                   => 'Modifier les informations',
        'base_address_title'                    => 'Mon adresse',
        'base_message_title'                    => 'Message',
        'order_nav_title'                       => 'Ma commande',
        'order_nav_angle_title'                 => 'Voir toutes les commandes',
        'various_transaction_title'             => 'Rappels de transactions',
        'various_transaction_tips'              => 'Les rappels de transaction vous aident à comprendre létat de votre commande et la logistique',
        'various_cart_title'                    => 'Panier dachat',
        'various_cart_empty_title'              => 'Votre panier est encore vide',
        'various_cart_tips'                     => 'Placez les articles que vous souhaitez acheter dans votre panier et réglez - les plus facilement ensemble',
        'various_favor_title'                   => 'Collection de marchandises',
        'various_favor_empty_title'             => 'Vous navez pas encore darticles de collection',
        'various_favor_tips'                    => 'Les articles de la collection afficheront les dernières promotions et réductions de prix',
        'various_browse_title'                  => 'Mes pas',
        'various_browse_empty_title'            => 'Votre enregistrement de navigation darticle est vide',
        'various_browse_tips'                   => 'Allez voir les promotions au centre commercial',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Mon adresse',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Mes pas',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Informations sur les marchandises',
            'goods_placeholder'     => 'Veuillez saisir le nom commercial / le résumé / les informations SEO',
            'price'                 => 'Prix de vente (En dollars)',
            'original_price'        => 'Prix dorigine (Yuan)',
            'add_time'              => 'Temps de création',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'Collection de marchandises',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Informations sur les marchandises',
            'goods_placeholder'     => 'Veuillez saisir le nom commercial / le résumé / les informations SEO',
            'price'                 => 'Prix de vente (En dollars)',
            'original_price'        => 'Prix dorigine (Yuan)',
            'add_time'              => 'Temps de création',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'Mes points',
        // 页面
        'base_normal_title'                     => 'Normalement disponible',
        'base_normal_tips'                      => 'Points pouvant être utilisés normalement',
        'base_locking_title'                    => 'Actuellement verrouillé',
        'base_locking_tips'                     => 'Trading général de points, la transaction nest pas terminée, les points correspondants sont verrouillés',
        'base_integral_unit'                    => 'Points',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Type dopération',
            'operation_integral'    => 'Points opérationnels',
            'original_integral'     => 'Points originaux',
            'new_integral'          => 'Derniers points',
            'msg'                   => 'Description',
            'add_time_time'         => 'Le temps',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'Données personnelles',
        'edit_browser_seo_title'                => 'Modification des données personnelles',
        'form_item_nickname'                    => 'Surnom',
        'form_item_nickname_message'            => 'Surnom entre 2 ~ 16 caractères',
        'form_item_birthday'                    => 'Anniversaire',
        'form_item_birthday_message'            => 'Format danniversaire incorrect',
        'form_item_province'                    => 'Province du lieu',
        'form_item_province_message'            => 'Province jusquà 30 caractères',
        'form_item_city'                        => 'Ville située',
        'form_item_city_message'                => 'Maximum de 30 caractères dans la ville',
        'form_item_county'                      => 'Région / Comté',
        'form_item_county_message'              => 'Région / Comté jusquà 30 caractères',
        'form_item_address'                     => 'Adresse détaillée',
        'form_item_address_message'             => 'Adresse détaillée 2 ~ 30 caractères',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Mes messages',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Type de message',
            'business_type'         => 'Type dentreprise',
            'title'                 => 'Titre',
            'detail'                => 'Détails',
            'is_read'               => 'Statut',
            'add_time_time'         => 'Le temps',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Questions et réponses / message',
        // 表单
        'form_title'                            => 'Poser une question / laisser un message',
        'form_item_name'                        => 'Surnom',
        'form_item_name_message'                => 'Format de pseudonyme entre 1 ~ 30 caractères',
        'form_item_tel'                         => 'Téléphone',
        'form_item_tel_message'                 => 'Veuillez remplir le téléphone',
        'form_item_title'                       => 'Titre',
        'form_item_title_message'               => 'Format de titre entre 1 ~ 60 caractères',
        'form_item_content'                     => 'Contenu',
        'form_item_content_message'             => 'Format de contenu entre 5 ~ 1000 caractères',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'Contact',
            'tel'                   => 'Téléphone de contact',
            'content'               => 'Contenu',
            'reply'                 => 'Contenu de la réponse',
            'reply_time_time'       => 'Temps de réponse',
            'add_time_time'         => 'Temps de création',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'Paramètres de sécurité',
        'password_update_browser_seo_title'     => 'Modification du mot de passe - Paramètres de sécurité',
        'mobile_update_browser_seo_title'       => 'Modification du numéro de téléphone portable - Paramètres de sécurité',
        'email_update_browser_seo_title'        => 'Modification de la boîte e - mail - Paramètres de sécurité',
        'logout_browser_seo_title'              => 'Déconnexion du numéro de compte - Paramètres de sécurité',
        'original_account_check_error_tips'     => 'Le contrôle du compte original a échoué',
        // 页面
        'logout_title'                          => 'Annulation du numéro de compte',
        'logout_confirm_title'                  => 'Confirmer la déconnexion',
        'logout_confirm_tips'                   => 'Non récupérable après la déconnexion du compte, sûr de continuer?',
        'email_title'                           => 'Vérification de la boîte e - mail originale',
        'email_new_title'                       => 'Nouveau contrôle E - mail',
        'mobile_title'                          => 'Vérification du numéro de téléphone portable original',
        'mobile_new_title'                      => 'Nouveau numéro de téléphone portable check',
        'login_password_title'                  => 'Login mot de passe Modifier',
    ],
];
?>