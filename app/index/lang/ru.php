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
 * 模块语言包-俄语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Торговый центр Начальная страница',
        'back_to_the_home_title'                => 'Вернуться на главную страницу',
        'all_category_text'                     => 'Все категории',
        'login_title'                           => 'Регистрация',
        'register_title'                        => 'Регистрация',
        'logout_title'                          => 'выход',
        'cancel_text'                           => 'отмена',
        'save_text'                             => 'Сохранить',
        'more_text'                             => 'Больше.',
        'processing_in_text'                    => 'В процессе обработки...',
        'upload_in_text'                        => 'Загрузить...',
        'navigation_main_quick_name'            => 'Сто сокровищ',
        'no_relevant_data_tips'                 => 'Нет соответствующих данных',
        'avatar_upload_title'                   => 'Загрузить заголовок',
        'choice_images_text'                    => 'Выберите изображение',
        'choice_images_error_tips'              => 'Выберите изображение, которое нужно загрузить',
        'confirm_upload_title'                  => 'Подтвердить загрузку',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => ' Добро пожаловать.',
        'header_top_nav_left_login_first'       => 'Здравствуйте.',
        'header_top_nav_left_login_last'        => 'Добро пожаловать',
        // 搜索
        'search_input_placeholder'              => 'Поиск очень простой.',
        'search_button_text'                    => 'Поиск',
        // 用户
        'avatar_upload_tips'                    => [
            'Увеличьте, уменьшите и переместите поле выбора в рабочей области, выберите диапазон для обрезки, разрежьте высокий процент фиксированной ширины;',
            'Эффект после резки показан на диаграмме предварительного просмотра справа, подтверждающей вступление в силу после представления;',
        ],
        'close_user_register_tips'              => 'Временное закрытие регистрации пользователей',
        'close_user_login_tips'                 => 'Временно отключить вход пользователя',
        // 底部
        'footer_icp_filing_text'                => 'Регистрация ICP',
        'footer_public_security_filing_text'    => 'Регистрация общественной безопасности',
        'footer_business_license_text'          => 'Электронная лицензия.',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Здравствуйте, добро пожаловать',
        'banner_right_article_title'            => 'Заголовки новостей',
        'design_browser_seo_title'              => 'Главная страница Дизайн',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'Нет данных комментариев',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Товары отсутствуют или удалены',
        'panel_can_choice_spec_name'            => 'Дополнительные спецификации',
        'recommend_goods_title'                 => 'Смотри и смотри.',
        'dynamic_scoring_name'                  => 'Динамическая оценка',
        'no_scoring_data_tips'                  => 'Нет данных оценки',
        'no_comments_data_tips'                 => 'Нет данных для оценки',
        'comments_first_name'                   => 'Комментарии к',
        'admin_reply_name'                      => 'Ответ администратора:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Поиск товаров',
        'filter_out_first_text'                 => 'Отфильтровать',
        'filter_out_last_data_text'             => 'полосовые данные',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'классификация товаров',
        'no_category_data_tips'                 => 'Нет дезагрегированных данных',
        'no_sub_category_data_tips'             => 'Нет подкатегорических данных',
        'view_category_sub_goods_name'          => 'Посмотреть товары по категории',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Пожалуйста, выберите товар.',
        ],
        // 基础
        'browser_seo_title'                     => 'Тележка для покупок',
        'goods_list_thead_base'                 => 'Информация о товарах',
        'goods_list_thead_price'                => 'Стоимость единицы',
        'goods_list_thead_number'               => 'Количество',
        'goods_list_thead_total'                => 'Сумма',
        'goods_item_total_name'                 => 'Всего',
        'summary_selected_goods_name'           => 'Выбранные товары',
        'summary_selected_goods_unit'           => 'Товар',
        'summary_nav_goods_total'               => 'Итого:',
        'summary_nav_button_name'               => 'Расчеты',
        'no_cart_data_tips'                     => 'Ваша корзина все еще пуста, вы можете',
        'no_cart_data_my_favor_name'            => 'Моя коллекция.',
        'no_cart_data_my_order_name'            => 'Мой заказ.',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Выберите адрес',
            'payment_choice_tips'               => 'Выберите оплату',
        ],
        // 基础
        'browser_seo_title'                     => 'Подтверждение заказа',
        'exhibition_not_allow_submit_tips'      => 'Презентация не позволяет подавать заказы',
        'buy_item_order_title'                  => 'Информация о заказе',
        'buy_item_payment_title'                => 'Выбор оплаты',
        'confirm_delivery_address_name'         => 'Подтверждение адреса получения',
        'use_new_address_name'                  => 'Использовать новый адрес',
        'no_delivery_address_tips'              => 'Нет адреса получения',
        'confirm_extraction_address_name'       => 'Подтвердить адрес',
        'choice_take_address_name'              => 'Выберите адрес получения',
        'no_take_address_tips'                  => 'Свяжитесь с администратором, чтобы настроить свой адрес.',
        'no_address_tips'                       => 'Адреса нет.',
        'extraction_list_choice_title'          => 'Автономный выбор',
        'goods_list_thead_base'                 => 'Информация о товарах',
        'goods_list_thead_price'                => 'Стоимость единицы',
        'goods_list_thead_number'               => 'Количество',
        'goods_list_thead_total'                => 'Сумма',
        'goods_item_total_name'                 => 'Всего',
        'not_goods_tips'                        => 'Нет товаров',
        'not_payment_tips'                      => 'Нет способа оплаты',
        'user_message_title'                    => 'Покупатель оставил сообщение',
        'user_message_placeholder'              => 'Описание выбора, рекомендуемого заполнения и согласия продавца',
        'summary_title'                         => 'Фактические выплаты:',
        'summary_contact_name'                  => 'Контактные лица:',
        'summary_address'                       => 'Адрес:',
        'summary_submit_order_name'             => 'Представление заказов',
        'payment_layer_title'                   => 'Платить при переходе, не закрывайте страницу',
        'payment_layer_content'                 => 'Неудачный платеж или длительное отсутствие ответа',
        'payment_layer_order_button_text'       => 'Мой заказ.',
        'payment_layer_tips'                    => 'После этого платеж может быть возобновлен.',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'Все статьи',
        'article_no_data_tips'                  => 'Статья не существует или удалена.',
        'article_id_params_tips'                => 'Ошибка с идентификатором статьи',
        'release_time'                          => 'Время публикации:',
        'view_number'                           => 'Количество просмотров:',
        'prev_article'                          => 'Предыдущая статья:',
        'next_article'                          => 'Следующий пост:',
        'article_category_name'                 => 'классификация статей',
        'article_nav_text'                      => 'Навигация на боковой панели',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'Страницы не существует или удалены.',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'Страницы не существует или удалены.',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Ошибка ID заказа',
            'payment_choice_tips'               => 'Пожалуйста, выберите способ оплаты.',
            'rating_string'                     => 'Очень плохо, плохо, вообще, хорошо, очень хорошо',
            'not_choice_data_tips'              => 'Сначала выберите данные.',
            'pay_url_empty_tips'                => 'Ошибка с адресом URL',
        ],
        // 基础
        'browser_seo_title'                     => 'Мой заказ.',
        'detail_browser_seo_title'              => 'Подробности заказа',
        'comments_browser_seo_title'            => 'Комментарии к заказу',
        'batch_payment_name'                    => 'Оплата оптом',
        'comments_goods_list_thead_base'        => 'Информация о товарах',
        'comments_goods_list_thead_price'       => 'Стоимость единицы',
        'comments_goods_list_thead_content'     => 'Замечания',
        'form_you_have_commented_tips'          => 'Вы сделали комментарий.',
        'form_payment_title'                    => 'Выплаты',
        'form_payment_no_data_tips'             => 'Нет способа оплаты',
        'order_base_title'                      => 'Информация о заказе',
        'order_base_warehouse_title'            => 'Услуги доставки:',
        'order_base_model_title'                => 'Режим заказа:',
        'order_base_order_no_title'             => 'Номер заказа:',
        'order_base_status_title'               => 'Статус заказа:',
        'order_base_pay_status_title'           => 'Статус оплаты:',
        'order_base_payment_title'              => 'Способ оплаты:',
        'order_base_total_price_title'          => 'Общая стоимость заказа:',
        'order_base_buy_number_title'           => 'Количество покупок:',
        'order_base_returned_quantity_title'    => 'Количество возвращенных товаров:',
        'order_base_user_note_title'            => 'Сообщение пользователя:',
        'order_base_add_time_title'             => 'Время заказа:',
        'order_base_confirm_time_title'         => 'Время подтверждения:',
        'order_base_pay_time_title'             => 'Время оплаты:',
        'order_base_delivery_time_title'        => 'Время отгрузки:',
        'order_base_collect_time_title'         => 'Время получения:',
        'order_base_user_comments_time_title'   => 'Время комментариев:',
        'order_base_cancel_time_title'          => 'Время отмены:',
        'order_base_express_title'              => 'Экспедиционная компания:',
        'order_base_express_website_title'      => 'Официальный сайт курьера:',
        'order_base_express_number_title'       => 'Номер курьера:',
        'order_base_price_title'                => 'Общая стоимость товаров:',
        'order_base_increase_price_title'       => 'Увеличение:',
        'order_base_preferential_price_title'   => 'Преференциальная сумма:',
        'order_base_refund_price_title'         => 'Сумма возврата:',
        'order_base_pay_price_title'            => 'Сумма платежа:',
        'order_base_take_code_title'            => 'Код получения:',
        'order_base_take_code_no_exist_tips'    => 'Код доставки отсутствует. Свяжитесь с администратором.',
        'order_under_line_tips'                 => 'В настоящее время для оффлайн - платежей [{:payment}] требуется подтверждение администратора, прежде чем они вступят в силу, и если требуется другой платеж, вы можете переключить платеж и перезапустить платеж.',
        'order_delivery_tips'                   => 'Товар упаковывается на складе и вывозится из него...',
        'order_goods_no_data_tips'              => 'Нет данных о заказах',
        'order_status_operate_first_tips'       => 'Вы можете',
        'goods_list_thead_base'                 => 'Информация о товарах',
        'goods_list_thead_price'                => 'Стоимость единицы',
        'goods_list_thead_number'               => 'Количество',
        'goods_list_thead_total'                => 'Сумма',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Базовая информация',
            'goods_placeholder'     => 'Введите номер заказа / название товара / модель',
            'status'                => 'Статус заказа',
            'pay_status'            => 'Состояние платежей',
            'total_price'           => 'Общая стоимость (в долларах)',
            'pay_price'             => 'Сумма платежа (в долларах)',
            'price'                 => 'Цена за единицу (в долларах)',
            'order_model'           => 'Режим заказа',
            'client_type'           => 'Платформа под заказ',
            'address'               => 'Адресная информация',
            'take'                  => 'Информация о получении груза',
            'refund_price'          => 'Сумма возврата (в долларах)',
            'returned_quantity'     => 'Количество возвращенных товаров',
            'buy_number_count'      => 'Общее количество закупок',
            'increase_price'        => 'Увеличение суммы (в долларах)',
            'preferential_price'    => 'Преференциальная сумма (в долларах)',
            'payment_name'          => 'форма платежа',
            'user_note'             => 'Сообщение',
            'extension'             => 'Расширенная информация',
            'express_name'          => 'Экспедиционная компания',
            'express_number'        => 'Экспедиционный номер.',
            'is_comments'           => 'Комментарии',
            'confirm_time'          => 'Время подтверждения',
            'pay_time'              => 'Время оплаты',
            'delivery_time'         => 'Время отгрузки',
            'collect_time'          => 'Время завершения',
            'cancel_time'           => 'Время отмены',
            'close_time'            => 'Время закрытия',
            'add_time'              => 'Время создания',
            'upd_time'              => 'Время обновления',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'Общий объем заказов',
            'pay_price'             => 'Общая сумма выплат',
            'buy_number_count'      => 'Всего товаров',
            'refund_price'          => 'Возврат средств',
            'returned_quantity'     => 'Возврат',
            'price_unit'            => 'Юань',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Причины возврата денег пусты',
        ],
        // 基础
        'browser_seo_title'                     => 'после продажи заказа',
        'detail_browser_seo_title'              => 'Подробности заказа после продажи.',
        'view_orderaftersale_enter_name'        => 'Посмотреть послепродажный заказ',
        'operate_delivery_name'                 => 'Немедленно вернуть товар',
        'goods_list_thead_base'                 => 'Информация о товарах',
        'goods_list_thead_price'                => 'Стоимость единицы',
        'goods_base_price_title'                => 'Общая стоимость товаров:',
        'goods_base_increase_price_title'       => 'Увеличение:',
        'goods_base_preferential_price_title'   => 'Преференциальная сумма:',
        'goods_base_refund_price_title'         => 'Сумма возврата:',
        'goods_base_pay_price_title'            => 'Сумма платежа:',
        'goods_base_total_price_title'          => 'Общая стоимость заказа:',
        'base_apply_title'                      => 'Информация о заявке',
        'base_apply_type_title'                 => 'Тип возврата:',
        'base_apply_status_title'               => 'Текущее состояние:',
        'base_apply_reason_title'               => 'Причины подачи заявки:',
        'base_apply_number_title'               => 'Количество возвращенных товаров:',
        'base_apply_price_title'                => 'Сумма возврата:',
        'base_apply_msg_title'                  => 'Описание возврата:',
        'base_apply_refundment_title'           => 'Способ возврата:',
        'base_apply_refuse_reason_title'        => 'Причины отказа:',
        'base_apply_apply_time_title'           => 'Время подачи заявки:',
        'base_apply_confirm_time_title'         => 'Время подтверждения:',
        'base_apply_delivery_time_title'        => 'Время возврата:',
        'base_apply_audit_time_title'           => 'Время проверки:',
        'base_apply_cancel_time_title'          => 'Время отмены:',
        'base_apply_add_time_title'             => 'Добавить время:',
        'base_apply_upd_time_title'             => 'Время обновления:',
        'base_item_express_title'               => 'Экспедиционная информация',
        'base_item_express_name'                => 'Экспресс:',
        'base_item_express_number'              => 'Единый номер:',
        'base_item_express_time'                => 'Время:',
        'base_item_voucher_title'               => 'Свидетельство',
        // 表单
        'form_delivery_title'                   => 'Операции по возврату',
        'form_delivery_address_name'            => 'Адрес возврата',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Базовая информация',
            'goods_placeholder'     => 'Введите номер заказа / название товара / модель',
            'status'                => 'Статус',
            'type'                  => 'Тип заявления',
            'reason'                => 'Причины',
            'price'                 => 'Сумма возврата (в долларах)',
            'number'                => 'Количество возвращенных товаров',
            'msg'                   => 'Описание возврата',
            'refundment'            => 'Тип возврата',
            'express_name'          => 'Экспедиционная компания',
            'express_number'        => 'Экспедиционный номер.',
            'apply_time'            => 'Время подачи заявки',
            'confirm_time'          => 'Время подтверждения',
            'delivery_time'         => 'Время возврата',
            'audit_time'            => 'Время проверки',
            'add_time'              => 'Время создания',
            'upd_time'              => 'Время обновления',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'browser_seo_title'                     => 'Пользовательский центр',
        'forget_password_browser_seo_title'     => 'Вернуть пароль',
        'user_register_browser_seo_title'       => 'Регистрация пользователей',
        'user_login_browser_seo_title'          => 'Регистрация пользователей',
        'password_reset_illegal_error_tips'     => 'Уже зарегистрирован. Чтобы сбросить пароль, сначала выйдите из текущей учетной записи',
        'register_illegal_error_tips'           => 'Уже зарегистрирован, чтобы зарегистрировать новую учетную запись, сначала выйдите из текущей учетной записи',
        'login_illegal_error_tips'              => 'Уже зарегистрирован, пожалуйста, не повторяйте вход',
        // 页面
        // 登录
        'login_top_register_tips'               => 'Нет аккаунта?',
        'login_close_tips'                      => 'Вход временно закрыт.',
        'login_type_username_title'             => 'Код счета',
        'login_type_mobile_title'               => 'Код проверки мобильного телефона',
        'login_type_email_title'                => 'Код проверки почтового ящика',
        'login_retrieve_password_title'         => 'Вернуть пароль',
        // 注册
        'register_top_login_tips'               => 'Я зарегистрировался, сейчас',
        'register_close_tips'                   => 'Регистрация временно закрыта.',
        'register_type_username_title'          => 'Регистрация счетов',
        'register_type_mobile_title'            => 'Регистрация мобильного телефона',
        'register_type_email_title'             => 'Регистрация почтового ящика',
        // 忘记密码
        'forget_password_top_login_tips'        => 'Уже есть аккаунт?',
        // 表单
        'form_item_agreement'                   => 'Читать и соглашаться',
        'form_item_agreement_message'           => 'Пожалуйста, отметьте согласие.',
        'form_item_service'                     => 'Соглашение об обслуживании',
        'form_item_privacy'                     => 'Политика конфиденциальности',
        'form_item_username'                    => 'Имя пользователя',
        'form_item_username_message'            => 'Используйте буквы, цифры, подчёркивание 2 ~ 18 символов',
        'form_item_password'                    => 'пароль входа',
        'form_item_password_placeholder'        => 'Введите пароль входа',
        'form_item_password_message'            => 'Формат пароля от 6 до 18 символов',
        'form_item_mobile'                      => 'Номер мобильного телефона',
        'form_item_mobile_placeholder'          => 'Пожалуйста, введите номер телефона.',
        'form_item_mobile_message'              => 'Ошибка формата номера телефона',
        'form_item_email'                       => 'Электронная почта',
        'form_item_email_placeholder'           => 'Введите электронную почту',
        'form_item_email_message'               => 'Ошибка формата электронной почты',
        'form_item_account'                     => 'Номер учётной записи',
        'form_item_account_placeholder'         => 'Введите имя пользователя / телефон / почтовый ящик',
        'form_item_account_message'             => 'Введите номер учетной записи.',
        'form_item_mobile_email'                => 'Мобильный телефон / почтовый ящик',
        'form_item_mobile_email_message'        => 'Введите действительный формат телефона / почтового ящика',
        // 个人中心
        'base_avatar_title'                     => 'Изменить заголовок',
        'base_personal_title'                   => 'Изменить данные',
        'base_address_title'                    => 'Мой адрес.',
        'base_message_title'                    => 'Сообщение',
        'order_nav_title'                       => 'Мой заказ.',
        'order_nav_angle_title'                 => 'Посмотреть все заказы',
        'various_transaction_title'             => 'Напоминание о сделке',
        'various_transaction_tips'              => 'Напоминания о транзакциях помогут вам понять статус заказа и логистику',
        'various_cart_title'                    => 'Тележка для покупок',
        'various_cart_empty_title'              => 'Ваш шопинг пуст.',
        'various_cart_tips'                     => 'Поставьте товары, которые вы хотите купить, в корзину, и вам будет легче расплатиться вместе.',
        'various_favor_title'                   => 'коллекция товаров',
        'various_favor_empty_title'             => 'Вы еще не собрали товар.',
        'various_favor_tips'                    => 'Коллекция товаров покажет последние рекламные кампании и снижение цен',
        'various_browse_title'                  => 'Мои следы.',
        'various_browse_empty_title'            => 'Запись вашего товара пуста.',
        'various_browse_tips'                   => 'Поезжайте в магазин и проверьте рекламную кампанию.',
    ],

    // 用户地址
    'useraddress'       => [
        'browser_seo_title'                     => 'Мой адрес.',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'browser_seo_title'                     => 'Мои следы.',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Информация о товарах',
            'goods_placeholder'     => 'Введите название товара / краткое описание / информацию SEO',
            'price'                 => 'Продажная цена (в долларах)',
            'original_price'        => 'Первоначальная цена (в долларах)',
            'add_time'              => 'Время создания',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'browser_seo_title'                     => 'коллекция товаров',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Информация о товарах',
            'goods_placeholder'     => 'Введите название товара / краткое описание / информацию SEO',
            'price'                 => 'Продажная цена (в долларах)',
            'original_price'        => 'Первоначальная цена (в долларах)',
            'add_time'              => 'Время создания',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'browser_seo_title'                     => 'Мои очки.',
        // 页面
        'base_normal_title'                     => 'Нормальный доступ',
        'base_normal_tips'                      => 'Нормально используемые интегралы.',
        'base_locking_title'                    => 'Текущая блокировка',
        'base_locking_tips'                     => 'В обычных интегральных транзакциях транзакции не завершены, соответствующие интегралы заблокированы',
        'base_integral_unit'                    => 'Интеграция',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Тип операции',
            'operation_integral'    => 'Операционный интеграл',
            'original_integral'     => 'Исходный интеграл',
            'new_integral'          => 'Последний интеграл',
            'msg'                   => 'Описание',
            'add_time_time'         => 'Время',
        ],
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'Личные данные',
        'edit_browser_seo_title'                => 'Редактор личных данных',
        'form_item_nickname'                    => 'Прозвище',
        'form_item_nickname_message'            => 'Прозвище от 2 до 16 символов',
        'form_item_birthday'                    => 'День рождения',
        'form_item_birthday_message'            => 'Ошибка формата дня рождения.',
        'form_item_province'                    => 'Провинция',
        'form_item_province_message'            => 'В провинции до 30 символов.',
        'form_item_city'                        => 'Город',
        'form_item_city_message'                => 'В городе до 30 символов.',
        'form_item_county'                      => 'Район / округ',
        'form_item_county_message'              => 'Регион / округ до 30 символов',
        'form_item_address'                     => 'Подробный адрес',
        'form_item_address_message'             => 'Подробный адрес 2 ~ 30 символов',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'Мои новости.',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Тип сообщения',
            'business_type'         => 'Вид деятельности',
            'title'                 => 'Заголовок',
            'detail'                => 'Подробности',
            'is_read'               => 'Статус',
            'add_time_time'         => 'Время',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Вопросы / сообщения',
        // 表单
        'form_title'                            => 'Вопросы / сообщения',
        'form_item_name'                        => 'Прозвище',
        'form_item_name_message'                => 'Формат прозвища от 1 до 30 символов',
        'form_item_tel'                         => 'Телефон',
        'form_item_tel_message'                 => 'Пожалуйста, заполните телефон.',
        'form_item_title'                       => 'Заголовок',
        'form_item_title_message'               => 'Формат заголовка от 1 до 60 символов',
        'form_item_content'                     => 'Содержание',
        'form_item_content_message'             => 'Формат контента от 5 до 1000 символов',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'Контактные лица',
            'tel'                   => 'Контактный телефон',
            'content'               => 'Содержание',
            'reply'                 => 'Ответить',
            'reply_time_time'       => 'Время ответа',
            'add_time_time'         => 'Время создания',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'browser_seo_title'                     => 'Параметры безопасности',
        'password_update_browser_seo_title'     => 'Изменение пароля входа - параметры безопасности',
        'mobile_update_browser_seo_title'       => 'Изменить номер телефона - Настройки безопасности',
        'email_update_browser_seo_title'        => 'Изменение электронной почты - параметры безопасности',
        'logout_browser_seo_title'              => 'Списание счетов - параметры безопасности',
        'original_account_check_error_tips'     => 'Ошибка проверки оригинальной учетной записи',
        // 页面
        'logout_title'                          => 'Списание счетов',
        'logout_confirm_title'                  => 'Подтверждение списания',
        'logout_confirm_tips'                   => 'Счет не может быть восстановлен после списания, вы уверены, что он продолжается?',
        'email_title'                           => 'Проверка исходной электронной почты',
        'email_new_title'                       => 'Проверка новой электронной почты',
        'mobile_title'                          => 'Проверка исходного номера телефона',
        'mobile_new_title'                      => 'Проверка нового номера телефона',
        'login_password_title'                  => 'Изменить пароль входа',
    ],
];
?>