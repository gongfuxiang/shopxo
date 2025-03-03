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
 * 模块语言包-西班牙语
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Página de inicio del centro comercial',
        'back_to_the_home_title'                => 'Volver a la página de inicio',
        'all_category_text'                     => 'Clasificación completa',
        'login_title'                           => 'Iniciar sesión',
        'register_title'                        => 'Registro',
        'logout_title'                          => 'Salida',
        'cancel_text'                           => 'Cancelación',
        'save_text'                             => 'Guardar',
        'more_text'                             => 'Más',
        'processing_in_text'                    => 'En proceso...',
        'upload_in_text'                        => 'Subiendo...',
        'navigation_main_quick_name'            => 'Más entradas',
        'no_relevant_data_tips'                 => 'No hay datos relevantes',
        'avatar_upload_title'                   => 'Carga de avatares',
        'choice_images_text'                    => 'Seleccionar imagen',
        'choice_images_error_tips'              => 'Por favor, elija la imagen que necesita cargar',
        'confirm_upload_title'                  => 'Confirmar carga',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Bienvenido',
        'header_top_nav_left_login_first'       => 'Hola.',
        'header_top_nav_left_login_last'        => 'Bienvenido a',
        // 搜索
        'search_input_placeholder'              => '¡De hecho, la búsqueda es muy simple..!',
        'search_button_text'                    => 'Buscar',
        // 用户
        'avatar_upload_tips'                    => [
            'Ampliar y reducir el área de trabajo y mover el cuadro de selección, seleccionar el rango a cortar y fijar la proporción de ancho a alto;',
            'El efecto después del Corte se muestra en la vista previa derecha, que es efectiva después de confirmar la presentación;',
        ],
        'close_user_register_tips'              => 'Cierre temporal del registro de usuarios',
        'close_user_login_tips'                 => 'Cierre temporalmente el inicio de sesión del usuario',
        // 底部
        'footer_icp_filing_text'                => 'Registro ICP',
        'footer_public_security_filing_text'    => 'Registro de seguridad pública',
        'footer_business_license_text'          => 'Licencia comercial electrónica iluminada',
        // 购物车
        'user_cart_success_modal_tips'          => '¡¡ la mercancía se ha unido con éxito al carrito de la compra!',
        'user_cart_success_modal_text_first'    => 'Carrito de compras en total',
        'user_cart_success_modal_text_last'     => 'Artículo',
        'user_cart_success_modal_cart_title'    => 'Ir al carrito de la compra para liquidar',
        'user_cart_success_modal_buy_title'     => 'Sigue comprando',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Hola, bienvenidos.',
        'banner_right_article_title'            => 'Titulares de noticias',
        'design_base_nav_title'                 => 'Diseño de la página de inicio',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'No hay datos de comentarios',
        ],
        // 基础
        'goods_no_data_tips'                    => 'La mercancía no existe o ha sido eliminada',
        'panel_can_choice_spec_name'            => 'Especificaciones opcionales',
        'recommend_goods_title'                 => 'Mira y mira',
        'dynamic_scoring_name'                  => 'Puntuación dinámica',
        'no_scoring_data_tips'                  => 'No hay datos de puntuación',
        'no_comments_data_tips'                 => 'No hay datos de evaluación',
        'comments_first_name'                   => 'Comentario en',
        'admin_reply_name'                      => 'El Administrador responde:',
        'qrcode_mobile_buy_name'                => 'Teléfono móvil',
    ],

    // 商品搜索
    'search'            => [
        'base_nav_title'                        => 'Búsqueda de productos',
        'filter_out_first_text'                 => 'Selección',
        'filter_out_last_data_text'             => 'Datos de barra',
    ],

    // 商品分类
    'category'          => [
        'base_nav_title'                        => 'Clasificación de mercancías',
        'no_category_data_tips'                 => 'No hay datos clasificados',
        'no_sub_category_data_tips'             => 'No hay datos subclasificados',
        'view_category_sub_goods_name'          => 'Ver los productos bajo clasificación',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Por favor, elija el producto',
        ],
        // 基础
        'base_nav_title'                        => 'Carrito de compras',
        'goods_list_thead_base'                 => 'Información sobre productos básicos',
        'goods_list_thead_price'                => 'Precio unitario',
        'goods_list_thead_number'               => 'Cantidad',
        'goods_list_thead_total'                => 'Total',
        'goods_item_total_name'                 => 'Precio total',
        'summary_selected_goods_name'           => 'Productos seleccionados',
        'summary_selected_goods_unit'           => 'Piezas',
        'summary_nav_goods_total'               => 'Total:',
        'summary_nav_button_name'               => 'Liquidación',
        'no_cart_data_tips'                     => 'Su carrito de la compra todavía está vacío, puede',
        'no_cart_data_my_favor_name'            => 'Mi colección',
        'no_cart_data_my_order_name'            => 'Mi pedido',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Por favor, elija la dirección',
            'payment_choice_tips'               => 'Por favor, elija pagar',
        ],
        // 基础
        'base_nav_title'                        => 'Confirmación del pedido',
        'exhibition_not_allow_submit_tips'      => 'El tipo de exhibición no permite la presentación de pedidos',
        'buy_item_order_title'                  => 'Información del pedido',
        'buy_item_payment_title'                => 'Optar por pagar',
        'confirm_delivery_address_name'         => 'Confirmar la dirección de recepción',
        'use_new_address_name'                  => 'Añadir nueva dirección',
        'no_address_info_tips'                  => '¡No hay información de dirección!',
        'confirm_extraction_address_name'       => 'Confirmar la dirección del punto de recogida',
        'choice_take_address_name'              => 'Elija la dirección de recogida',
        'no_take_address_tips'                  => 'Póngase en contacto con el Administrador para configurar la dirección de autostop.',
        'no_address_tips'                       => 'Sin dirección',
        'extraction_list_choice_title'          => 'Selección de puntos de referencia',
        'goods_list_thead_base'                 => 'Información sobre productos básicos',
        'goods_list_thead_price'                => 'Precio unitario',
        'goods_list_thead_number'               => 'Cantidad',
        'goods_list_thead_total'                => 'Total',
        'goods_item_total_name'                 => 'Precio total',
        'not_goods_tips'                        => 'Sin mercancía',
        'not_payment_tips'                      => 'No hay método de pago',
        'user_message_title'                    => 'Mensaje del comprador',
        'user_message_placeholder'              => 'Selección, sugerencias y instrucciones acordadas por el vendedor',
        'summary_title'                         => 'Pago real:',
        'summary_contact_name'                  => 'Contactos:',
        'summary_address'                       => 'Dirección:',
        'summary_submit_order_name'             => 'Presentación de pedidos',
        'payment_layer_title'                   => 'En el salto de pago, no cierre la página',
        'payment_layer_content'                 => 'El pago falló o no respondió durante mucho tiempo',
        'payment_layer_order_button_text'       => 'Mi pedido',
        'payment_layer_tips'                    => 'Después de eso, se puede reiniciar el pago.',
        'extraction_contact_name'               => 'Mi nombre',
        'extraction_contact_tel'                => 'Mi teléfono',
        'extraction_contact_tel_placeholder'    => 'Mi teléfono móvil o fijo',
    ],

    // 文章
    'article'            => [
        'category_base_nav_title'               => 'Todos los artículos',
        'article_no_data_tips'                  => 'El artículo no existe o ha sido eliminado',
        'article_id_params_tips'                => 'El ID del artículo es incorrecto',
        'release_time'                          => 'Tiempo de lanzamiento:',
        'view_number'                           => 'Número de vistas:',
        'prev_article'                          => 'Artículo anterior:',
        'next_article'                          => 'Siguiente artículo:',
        'article_category_name'                 => 'Clasificación de artículos',
        'recommended_article_name'              => 'Artículos recomendados',
        'article_nav_text'                      => 'Navegación de la barra lateral',
        'article_search_placeholder'            => 'Introducir búsqueda de palabras clave',
    ],

    // 自定义页面
    'customview'        => [
        'custom_view_no_data_tips'              => 'La página no existe o ha sido eliminada',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'La página no existe o ha sido eliminada',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'El ID del pedido es incorrecto',
            'payment_choice_tips'               => 'Por favor, elija el método de pago.',
            'rating_string'                     => 'Muy malo, malo, general, bueno, muy bueno',
            'not_choice_data_tips'              => 'Por favor, seleccione los datos primero.',
            'pay_url_empty_tips'                => 'La Dirección de la dirección de la dirección de pago es incorrecta.',
        ],
        // 基础
        'base_nav_title'                        => 'Mi pedido',
        'detail_base_nav_title'                 => 'Detalles del pedido',
        'detail_take_title'                     => 'Pickup address',
        'detail_shipping_address_title'         => 'Address',
        'comments_base_nav_title'               => 'Comentarios sobre pedidos',
        'batch_payment_name'                    => 'Pago por lotes',
        'comments_goods_list_thead_base'        => 'Información sobre productos básicos',
        'comments_goods_list_thead_price'       => 'Precio unitario',
        'comments_goods_list_thead_content'     => 'Contenido del comentario',
        'form_you_have_commented_tips'          => 'Ya has comentado',
        'form_payment_title'                    => 'Pago',
        'form_payment_no_data_tips'             => 'No hay método de pago',
        'order_base_title'                      => 'Información del pedido',
        'order_status_title'                    => 'Estado del pedido',
        'order_contact_title'                   => 'Contactos',
        'order_consignee_title'                 => 'Destinatario',
        'order_phone_title'                     => 'Número de teléfono móvil',
        'order_base_warehouse_title'            => 'Servicio de envío:',
        'order_base_model_title'                => 'Modo de pedido:',
        'order_base_order_no_title'             => 'Número de pedido:',
        'order_base_status_title'               => 'Estado del pedido:',
        'order_base_pay_status_title'           => 'Estado de pago:',
        'order_base_payment_title'              => 'Método de pago:',
        'order_base_total_price_title'          => 'Precio total del pedido:',
        'order_base_buy_number_title'           => 'Número de compras:',
        'order_base_returned_quantity_title'    => 'Número de devoluciones:',
        'order_base_user_note_title'            => 'Mensaje del usuario:',
        'order_base_add_time_title'             => 'Tiempo de pedido:',
        'order_base_confirm_time_title'         => 'Tiempo de confirmación:',
        'order_base_pay_time_title'             => 'Tiempo de pago:',
        'order_base_collect_time_title'         => 'Tiempo de recepción:',
        'order_base_user_comments_time_title'   => 'Tiempo de comentarios:',
        'order_base_cancel_time_title'          => 'Hora de cancelación:',
        'order_base_close_time_title'           => 'Horario de cierre:',
        'order_base_price_title'                => 'Precio total de los productos básicos:',
        'order_base_increase_price_title'       => 'Aumento de la cantidad:',
        'order_base_preferential_price_title'   => 'Importe preferencial:',
        'order_base_refund_price_title'         => 'Importe del reembolso:',
        'order_base_pay_price_title'            => 'Importe pagado:',
        'order_base_take_code_title'            => 'Código de recogida:',
        'order_base_take_code_no_exist_tips'    => 'El Código de recogida no existe, Póngase en contacto con el Administrador',
        'order_under_line_tips'                 => 'En la actualidad, es el método de pago fuera de línea (...: pago)), que necesita la confirmación del Administrador antes de que pueda entrar en vigor, y si necesita otros pagos, puede cambiar el pago y reiniciar el pago.',
        'order_delivery_tips'                   => 'Las mercancías se están empacando y saliendo del almacén...',
        'order_goods_no_data_tips'              => 'No hay datos de productos pedidos',
        'order_base_service_name'               => 'Nombre del personal de servicio',
        'order_base_service_mobile'             => 'Teléfono móvil del personal de servicio',
        'order_base_service_time'               => 'Tiempo de servicio',
        'order_status_operate_first_tips'       => 'Puede',
        'goods_list_thead_base'                 => 'Información sobre productos básicos',
        'goods_list_thead_price'                => 'Precio unitario',
        'goods_list_thead_number'               => 'Cantidad',
        'goods_list_thead_total'                => 'Total',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Información básica',
            'goods_placeholder'     => 'Por favor, introduzca el número de pedido / nombre del producto / Modelo',
            'status'                => 'Estado del pedido',
            'pay_status'            => 'Estado de pago',
            'total_price'           => 'Precio total (yuan)',
            'pay_price'             => 'Monto de pago (yuan)',
            'price'                 => 'Precio unitario (yuan)',
            'order_model'           => 'Modo de pedido',
            'client_type'           => 'Plataforma de pedidos',
            'address'               => 'Información de dirección',
            'service'               => 'Información de servicio',
            'take'                  => 'Información de recogida',
            'refund_price'          => 'Monto del reembolso (yuan)',
            'returned_quantity'     => 'Número de devoluciones',
            'buy_number_count'      => 'Total de compras',
            'increase_price'        => 'Aumento de la cantidad (yuan)',
            'preferential_price'    => 'Cantidad preferencial (yuan)',
            'payment_name'          => 'Método de pago',
            'user_note'             => 'Mensaje de mensaje',
            'extension'             => 'Información extendida',
            'express'               => 'Información de mensajería',
            'express_placeholder'   => 'Estado del Número de la lista de mensajería',
            'is_comments'           => 'Si comentar',
            'confirm_time'          => 'Tiempo de confirmación',
            'pay_time'              => 'Tiempo de pago',
            'delivery_time'         => 'Tiempo de envío',
            'collect_time'          => 'Tiempo de finalización',
            'cancel_time'           => 'Hora de cancelación',
            'close_time'            => 'Tiempo de cierre',
            'add_time'              => 'Tiempo de creación',
            'upd_time'              => 'Tiempo de actualización',
        ],
        // 动态表格统计数据
        'form_table_stats'                      => [
            'total_price'           => 'Total de pedidos',
            'pay_price'             => 'Total de pagos',
            'buy_number_count'      => 'Total de productos básicos',
            'refund_price'          => 'Importe del reembolso',
            'returned_quantity'     => 'Número de devoluciones',
        ],
        // 快递表格
        'form_table_express'                    => [
            'name'    => 'Empresa de mensajería',
            'number'  => 'Número de la lista de mensajería',
            'note'    => 'Nota de mensajería',
            'time'    => 'Tiempo de envío',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Los datos de la razón del reembolso están vacíos',
        ],
        // 基础
        'base_nav_title'                        => 'Posventa de pedidos',
        'detail_base_nav_title'                 => 'Detalles de la posventa del pedido',
        'view_orderaftersale_enter_name'        => 'Ver pedidos post - venta',
        'orderaftersale_apply_name'             => 'Solicitar posventa',
        'operate_delivery_name'                 => 'Devolución inmediata',
        'goods_list_thead_base'                 => 'Información sobre productos básicos',
        'goods_list_thead_price'                => 'Precio unitario',
        'goods_base_price_title'                => 'Precio total de los productos básicos:',
        'goods_list_thead_number'               => 'Cantidad',
        'goods_list_thead_total'                => 'Total',
        'goods_base_price_title'                => 'Precio total de los productos básicos:',
        'goods_base_increase_price_title'       => 'Aumento de la cantidad:',
        'goods_base_preferential_price_title'   => 'Importe preferencial:',
        'goods_base_refund_price_title'         => 'Importe del reembolso:',
        'goods_base_pay_price_title'            => 'Importe pagado:',
        'goods_base_total_price_title'          => 'Precio total del pedido:',
        'base_apply_title'                      => 'Información de la solicitud',
        'goods_after_status_title'              => 'Estado post - venta',
        'withdraw_title'                        => 'Cancelación de la solicitud',
        're_apply_title'                        => 'Volver a solicitar',
        'select_service_type_title'             => 'Selección del tipo de servicio',
        'goods_pay_price_title'                 => 'Importe pagado de la mercancía:',
        'aftersale_service_title'               => 'Servicio al cliente post - venta',
        'problems_contact_service_tips'         => 'Si tiene problemas, Póngase en contacto con el servicio al cliente.',
        'base_apply_type_title'                 => 'Tipo de reembolso:',
        'base_apply_status_title'               => 'Estado actual:',
        'base_apply_reason_title'               => 'Razones de la solicitud:',
        'base_apply_number_title'               => 'Número de devoluciones:',
        'base_apply_price_title'                => 'Importe del reembolso:',
        'base_apply_msg_title'                  => 'Instrucciones de reembolso:',
        'base_apply_refundment_title'           => 'Método de reembolso:',
        'base_apply_refuse_reason_title'        => 'Razones del rechazo:',
        'base_apply_apply_time_title'           => 'Tiempo de solicitud:',
        'base_apply_confirm_time_title'         => 'Tiempo de confirmación:',
        'base_apply_delivery_time_title'        => 'Tiempo de devolución:',
        'base_apply_audit_time_title'           => 'Tiempo de revisión:',
        'base_apply_cancel_time_title'          => 'Hora de cancelación:',
        'base_apply_add_time_title'             => 'Tiempo de adición:',
        'base_apply_upd_time_title'             => 'Tiempo de actualización:',
        'base_item_express_title'               => 'Información de mensajería',
        'base_item_express_name'                => 'Mensajería:',
        'base_item_express_number'              => 'Número único:',
        'base_item_express_time'                => 'Tiempo:',
        'base_item_voucher_title'               => 'Comprobante',
        // 表单
        'form_delivery_title'                   => 'Operación de devolución',
        'form_delivery_address_name'            => 'Dirección de devolución',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Información básica',
            'goods_placeholder'     => 'Por favor, introduzca el número de pedido / nombre del producto / Modelo',
            'status'                => 'Estado',
            'type'                  => 'Tipo de solicitud',
            'reason'                => 'Causa',
            'price'                 => 'Monto del reembolso (yuan)',
            'number'                => 'Número de devoluciones',
            'msg'                   => 'Instrucciones de reembolso',
            'refundment'            => 'Tipo de reembolso',
            'express_name'          => 'Empresa de mensajería',
            'express_number'        => 'Número de la lista de mensajería',
            'apply_time'            => 'Tiempo de solicitud',
            'confirm_time'          => 'Tiempo de confirmación',
            'delivery_time'         => 'Tiempo de devolución',
            'audit_time'            => 'Tiempo de revisión',
            'add_time'              => 'Tiempo de creación',
            'upd_time'              => 'Tiempo de actualización',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => 'Reembolso total',
            'number'  => 'Número total de devoluciones',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'base_nav_title'                        => 'Centro de usuarios',
        'forget_password_base_nav_title'        => 'Recuperación de la contraseña',
        'user_register_base_nav_title'          => 'Registro de usuarios',
        'user_login_base_nav_title'             => 'Inicio de sesión del usuario',
        'password_reset_illegal_error_tips'     => 'Ya está conectado, para restablecer la contraseña, salga de la cuenta actual primero',
        'register_illegal_error_tips'           => 'Ya está conectado. para registrar una nueva cuenta, salga de la cuenta actual primero.',
        'login_illegal_error_tips'              => 'Ya está conectado, no repita el inicio de sesión',
        // 页面
        // 登录
        'login_nav_title'                       => 'Bienvenido a iniciar sesión',
        'login_top_register_tips'               => '¿Todavía no hay cuenta?',
        'login_close_tips'                      => 'Inicio de sesión cerrado temporalmente',
        'login_type_username_title'             => 'Contraseña de la cuenta',
        'login_type_mobile_title'               => 'Código de verificación del teléfono móvil',
        'login_type_email_title'                => 'Código de verificación del buzón',
        'login_ahora_login_title'               => 'Iniciar sesión ahora',
        // 注册
        'register_nav_title'                    => 'Bienvenido a registrarse',
        'register_top_login_tips'               => 'Ya me he registrado y ahora',
        'register_close_tips'                   => 'Se cierra temporalmente el registro',
        'register_type_username_title'          => 'Registro de cuentas',
        'register_type_mobile_title'            => 'Registro de teléfonos móviles',
        'register_type_email_title'             => 'Registro de buzón',
        'register_ahora_register_title'         => 'Registrarse de inmediato',
        // 忘记密码
        'forget_password_nav_title'             => 'Recuperar la contraseña',
        'forget_password_reset_title'           => 'Restablecer la contraseña',
        'forget_password_top_login_tips'        => '¿¿ ya tienes una cuenta?',
        // 表单
        'form_item_agreement'                   => 'Leer y aceptar',
        'form_item_agreement_message'           => 'Por favor, marque el Acuerdo de consentimiento',
        'form_item_service'                     => 'Acuerdo de servicio',
        'form_item_privacy'                     => 'Política de privacidad',
        'form_item_username'                    => 'Nombre de usuario',
        'form_item_username_message'            => 'Use letras, números, subrayado de 2 a 18 caracteres',
        'form_item_password'                    => 'Contraseña de inicio de sesión',
        'form_item_password_placeholder'        => 'Introduzca la contraseña de inicio de sesión',
        'form_item_password_message'            => 'Formato de contraseña entre 6 y 18 caracteres',
        'form_item_mobile'                      => 'Número de teléfono móvil',
        'form_item_mobile_placeholder'          => 'Por favor, introduzca el número de teléfono móvil',
        'form_item_mobile_message'              => 'Formato incorrecto del número de teléfono móvil',
        'form_item_email'                       => 'Correo electrónico',
        'form_item_email_placeholder'           => 'Por favor, introduzca el correo electrónico',
        'form_item_email_message'               => 'Formato de correo electrónico incorrecto',
        'form_item_account'                     => 'Iniciar sesión en la cuenta',
        'form_item_account_placeholder'         => 'Introduzca el nombre de usuario / teléfono móvil / buzón',
        'form_item_account_message'             => 'Introduzca la cuenta de inicio de sesión',
        'form_item_mobile_email'                => 'Teléfono móvil / buzón',
        'form_item_mobile_email_message'        => 'Introduzca un formato válido de teléfono móvil / buzón',
        // 个人中心
        'base_avatar_title'                     => 'Modificar avatar',
        'base_personal_title'                   => 'Modificar la información',
        'base_address_title'                    => 'Mi dirección',
        'base_message_title'                    => 'Noticias',
        'order_nav_title'                       => 'Mi pedido',
        'order_nav_angle_title'                 => 'Ver todos los pedidos',
        'various_transaction_title'             => 'Recordatorio de transacción',
        'various_transaction_tips'              => 'Los recordatorios de transacciones le ayudan a comprender el Estado del pedido y la logística.',
        'various_cart_title'                    => 'Carrito de compras',
        'various_cart_empty_title'              => 'Su carrito de la compra todavía está vacío.',
        'various_cart_tips'                     => 'Poner los productos que quieres comprar en el carrito de la compra es más fácil de liquidar juntos.',
        'various_favor_title'                   => 'Colección de productos básicos',
        'various_favor_empty_title'             => 'Todavía no tiene una colección.',
        'various_favor_tips'                    => 'La colección mostrará las últimas promociones y reducciones de precios',
        'various_browse_title'                  => 'Mis huellas',
        'various_browse_empty_title'            => 'El registro de navegación de su producto está vacío.',
        'various_browse_tips'                   => 'Date prisa y ve al centro comercial a ver las promociones.',
    ],

    // 用户地址
    'useraddress'       => [
        'base_nav_title'                        => 'Mi dirección',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'base_nav_title'                        => 'Mis huellas',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Información sobre productos básicos',
            'goods_placeholder'     => 'Introduzca el nombre del producto / breve descripción / información seo',
            'price'                 => 'Precio de venta (yuan)',
            'original_price'        => 'Precio original (yuan)',
            'add_time'              => 'Tiempo de creación',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'base_nav_title'                        => 'Colección de productos básicos',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Información sobre productos básicos',
            'goods_placeholder'     => 'Introduzca el nombre del producto / breve descripción / información seo',
            'price'                 => 'Precio de venta (yuan)',
            'original_price'        => 'Precio original (yuan)',
            'add_time'              => 'Tiempo de creación',
        ],
    ],

    // 用户商品评论
    'usergoodscomments'         => [
        'base_nav_title'                        => 'Comentarios sobre productos básicos',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Información básica',
            'goods_placeholder'  => 'Por favor, introduzca el nombre / modelo del producto.',
            'business_type'      => 'Tipo de negocio',
            'content'            => 'Contenido del comentario',
            'images'             => 'Imágenes de comentarios',
            'rating'             => 'Puntuación',
            'reply'              => 'Contenido de la respuesta',
            'is_show'            => 'Si se muestra',
            'is_anonymous'       => 'Anonimato o no',
            'is_reply'           => 'Si responder',
            'reply_time_time'    => 'Tiempo de respuesta',
            'add_time_time'      => 'Tiempo de creación',
            'upd_time_time'      => 'Tiempo de actualización',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'base_nav_title'                        => 'Mis puntos',
        // 页面
        'base_normal_title'                     => 'Normal disponible',
        'base_normal_tips'                      => 'Puntos que se pueden usar normalmente',
        'base_locking_title'                    => 'Bloqueo actual',
        'base_locking_tips'                     => 'En las transacciones generales de puntos, las transacciones no se completan y los puntos correspondientes están bloqueados.',
        'base_integral_unit'                    => 'Puntos',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Tipo de operación',
            'operation_integral'    => 'Puntos de operación',
            'original_integral'     => 'Puntos originales',
            'new_integral'          => 'Los últimos puntos',
            'msg'                   => 'Descripción',
            'add_time_time'         => 'Tiempo',
        ],
    ],

    // 个人资料
    'personal'          => [
        'base_nav_title'                        => 'Datos personales',
        'edit_base_nav_title'                   => 'Edición de perfiles',
        'form_item_nickname'                    => 'Apodo',
        'form_item_nickname_message'            => 'Entre 2 y 16 caracteres de apodo',
        'form_item_birthday'                    => 'Cumpleaños',
        'form_item_birthday_message'            => 'El formato de cumpleaños es incorrecto',
        'form_item_province'                    => 'Provincia',
        'form_item_province_message'            => 'Hasta 30 caracteres en la provincia',
        'form_item_city'                        => 'Ciudad',
        'form_item_city_message'                => 'Hasta 30 caracteres en la ciudad',
        'form_item_county'                      => 'Distrito / Condado',
        'form_item_county_message'              => 'Hasta 30 caracteres en el distrito / Condado',
        'form_item_address'                     => 'Dirección detallada',
        'form_item_address_message'             => 'Dirección detallada de 2 a 30 caracteres',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'base_nav_title'                        => 'Mis noticias',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Tipo de mensaje',
            'business_type'         => 'Tipo de negocio',
            'title'                 => 'Título',
            'detail'                => 'Detalles',
            'is_read'               => 'Estado',
            'add_time_time'         => 'Tiempo',
        ],
    ],

    // 安全
    'safety'            => [
        // 基础
        'base_nav_title'                        => 'Configuración de Seguridad',
        'password_update_base_nav_title'        => 'Modificación de la contraseña de inicio de sesión - configuración de Seguridad',
        'mobile_update_base_nav_title'          => 'Modificación del número de teléfono móvil - configuración de Seguridad',
        'email_update_base_nav_title'           => 'Modificación del correo electrónico - configuración de Seguridad',
        'logout_base_nav_title'                 => 'Cancelación de la cuenta - configuración de Seguridad',
        'original_account_check_error_tips'     => 'Falló la verificación de la cuenta original',
        // 页面
        'logout_title'                          => 'Cancelación de la cuenta',
        'logout_confirm_title'                  => 'Confirmación de la cancelación',
        'logout_confirm_tips'                   => '¿La cuenta no se puede restaurar después de la cancelación, ¿ está seguro de continuar?',
        'email_title'                           => 'Verificación del correo electrónico original',
        'email_new_title'                       => 'Nueva verificación de correo electrónico',
        'mobile_title'                          => 'Verificación del número de teléfono móvil original',
        'mobile_new_title'                      => 'Verificación del nuevo número de teléfono móvil',
        'login_password_title'                  => 'Modificación de la contraseña de inicio de sesión',
    ],

    // 上传组件
    'ueditor' => [
        'base_nav_title'                        => 'Escanear el Código para cargar'
    ],
];
?>