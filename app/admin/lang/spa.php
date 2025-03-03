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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'Evolución de la facturación de pedidos',
            'order_trading_trend_name'          => 'Tendencia de las transacciones de pedidos',
            'goods_hot_name'                    => 'Productos de venta caliente',
            'goods_hot_tips'                    => 'Solo se muestran los primeros 13 productos',
            'payment_name'                      => 'Método de pago',
            'order_region_name'                 => 'Distribución geográfica de los pedidos',
            'order_region_tips'                 => 'Solo se muestran 10 datos',
            'new_user_name'                     => 'Nuevos usuarios',
            'buy_user_name'                     => 'Usuarios que hacen pedidos',
            'upgrade_check_loading_tips'        => 'Obteniendo el último contenido, por favor Espere...',
            'upgrade_version_name'              => 'Versión actualizada:',
            'upgrade_date_name'                 => 'Fecha de actualización:',
        ],
        // 页面基础
        'base_update_system_title'              => 'Actualización del sistema',
        'base_update_button_title'              => 'Actualización inmediata',
        'base_item_base_stats_title'            => 'Estadísticas del centro comercial',
        'base_item_base_stats_tips'             => 'El cribado temporal solo es válido para el total',
        'base_item_user_title'                  => 'Número total de usuarios',
        'base_item_order_number_title'          => 'Total de pedidos',
        'base_item_order_complete_number_title' => 'Volumen total de transacciones',
        'base_item_order_complete_title'        => 'Total de pedidos',
        'base_item_last_month_title'            => 'El mes pasado',
        'base_item_same_month_title'            => 'Ese mes',
        'base_item_yesterday_title'             => 'Ayer',
        'base_item_today_title'                 => 'Hoy',
        'base_item_order_profit_title'          => 'Evolución de la facturación de pedidos',
        'base_item_order_trading_title'         => 'Tendencia de las transacciones de pedidos',
        'base_item_order_tips'                  => 'Todos los pedidos',
        'base_item_hot_sales_goods_title'       => 'Productos de venta caliente',
        'base_item_hot_sales_goods_tips'        => 'No incluye pedidos cancelados y cerrados',
        'base_item_payment_type_title'          => 'Método de pago',
        'base_item_map_whole_country_title'     => 'Distribución geográfica de los pedidos',
        'base_item_map_whole_country_tips'      => 'No incluye pedidos cancelados, dimensiones predeterminadas (provincias)',
        'base_item_map_whole_country_province'  => 'Provincias',
        'base_item_map_whole_country_city'      => 'Ciudad',
        'base_item_map_whole_country_county'    => 'Distrito / Condado',
        'base_item_new_user_title'              => 'Nuevos usuarios',
        'base_item_buy_user_title'              => 'Usuarios que hacen pedidos',
        'system_info_title'                     => 'Información del sistema',
        'system_ver_title'                      => 'Versión de software',
        'system_os_ver_title'                   => 'Sistema operativo',
        'system_php_ver_title'                  => 'Versión de pH',
        'system_mysql_ver_title'                => 'Versión MySQL',
        'system_server_ver_title'               => 'Información del lado del servidor',
        'system_host_title'                     => 'Nombre de dominio actual',
        'development_team_title'                => 'Equipo de desarrollo',
        'development_team_website_title'        => 'Sitio web oficial de la compañía',
        'development_team_website_value'        => 'Shanghai zongzhige Technology co., Ltd.',
        'development_team_support_title'        => 'Soporte técnico',
        'development_team_support_value'        => 'Proveedor de sistemas de comercio electrónico empresarial shopxo',
        'development_team_ask_title'            => 'Intercambiar preguntas',
        'development_team_ask_value'            => 'Shopxo intercambia preguntas',
        'development_team_agreement_title'      => 'Protocolo de código abierto',
        'development_team_agreement_value'      => 'Ver Protocolo de código abierto',
        'development_team_update_log_title'     => 'Actualizar el registro',
        'development_team_update_log_value'     => 'Ver registro de actualización',
        'development_team_members_title'        => 'Miembros de I + D',
        'development_team_members_value'        => [
            ['name' => 'Hermano Gong', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'Usuarios',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'ID del usuario',
            'number_code'           => 'Código de membresía',
            'system_type'           => 'Tipo de sistema',
            'platform'              => 'Plataforma a la que pertenece',
            'avatar'                => 'Avatar',
            'username'              => 'Nombre de usuario',
            'nickname'              => 'Apodo',
            'mobile'                => 'Teléfono móvil',
            'email'                 => 'Buzón',
            'gender_name'           => 'Género',
            'status_name'           => 'Estado',
            'province'              => 'Provincia',
            'city'                  => 'Ciudad',
            'county'                => 'Distrito / Condado',
            'address'               => 'Dirección detallada',
            'birthday'              => 'Cumpleaños',
            'integral'              => 'Puntos disponibles',
            'locking_integral'      => 'Puntos bloqueados',
            'referrer'              => 'Invitar a los usuarios',
            'referrer_placeholder'  => 'Por favor, introduzca el nombre de usuario de la invitación / apodo / teléfono móvil / buzón',
            'add_time'              => 'Tiempo de registro',
            'upd_time'              => 'Tiempo de actualización',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'Dirección del usuario',
        // 详情
        'detail_user_address_idcard_name'       => 'Nombre',
        'detail_user_address_idcard_number'     => 'Número',
        'detail_user_address_idcard_pic'        => 'Foto',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Información del usuario',
            'user_placeholder'  => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'alias'             => 'Alias',
            'name'              => 'Contactos',
            'tel'               => 'Número de teléfono de contacto',
            'province_name'     => 'Provincia a la que pertenece',
            'city_name'         => 'Ciudad a la que pertenece',
            'county_name'       => 'Distrito / condado al que pertenece',
            'address'           => 'Dirección detallada',
            'address_last_code' => 'El último nivel de Codificación de la dirección',
            'position'          => 'Longitud y latitud',
            'idcard_info'       => 'Información de la tarjeta de identificación',
            'is_default'        => 'Si predeterminado',
            'add_time'          => 'Tiempo de creación',
            'upd_time'          => 'Tiempo de actualización',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => '¿Después de la eliminación, ¿ el ahorro es válido y la confirmación continúa?',
            'address_no_data'                   => 'Los datos de la dirección están vacíos',
            'address_not_exist'                 => 'La Dirección no existe',
            'address_logo_message'              => 'Por favor, suba la imagen del logotipo',
        ],
        // 主导航
        'base_nav_list'                       => [
            ['name' => 'Configuración básica', 'type' => 'base'],
            ['name' => 'Configuración del sitio web', 'type' => 'siteset'],
            ['name' => 'Tipo de sitio', 'type' => 'sitetype'],
            ['name' => 'Registro de usuarios', 'type' => 'register'],
            ['name' => 'Inicio de sesión del usuario', 'type' => 'login'],
            ['name' => 'Recuperación de la contraseña', 'type' => 'forgetpwd'],
            ['name' => 'Código de verificación', 'type' => 'verify'],
            ['name' => 'Posventa de pedidos', 'type' => 'orderaftersale'],
            ['name' => 'Anexo', 'type' => 'attachment'],
            ['name' => 'Caché', 'type' => 'cache'],
            ['name' => 'Extensión', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'Página de inicio', 'type' => 'index'],
            ['name' => 'Buscar', 'type' => 'search'],
            ['name' => 'Pedidos', 'type' => 'order'],
            ['name' => 'Productos Básicos', 'type' => 'goods'],
            ['name' => 'Carrito de compras', 'type' => 'cart'],
            ['name' => 'Expansión', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Estado del sitio',
        'base_item_site_domain_title'           => 'Dirección del nombre de dominio del sitio',
        'base_item_site_filing_title'           => 'Información de registro',
        'base_item_site_other_title'            => 'Otros',
        'base_item_session_cache_title'         => 'Configuración de la caché de sesiones',
        'base_item_data_cache_title'            => 'Configuración de la caché de datos',
        'base_item_redis_cache_title'           => 'Configuración de la caché redis',
        'base_item_crontab_config_title'        => 'Configuración del guión de tiempo',
        'base_item_regex_config_title'          => 'Configuración regular',
        'base_item_quick_nav_title'             => 'Navegación rápida',
        'base_item_user_base_title'             => 'Base de usuarios',
        'base_item_user_address_title'          => 'Dirección del usuario',
        'base_item_multilingual_title'          => 'Multilingüismo',
        'base_item_site_auto_mode_title'        => 'Modo automático',
        'base_item_site_manual_mode_title'      => 'Modo manual',
        'base_item_default_payment_title'       => 'Método de pago predeterminado',
        'base_item_display_type_title'          => 'Tipo de exhibición',
        'base_item_self_extraction_title'       => 'Punto de referencia propio',
        'base_item_fictitious_title'            => 'Ventas virtuales',
        'choice_upload_logo_title'              => 'Elija el logotipo',
        'add_goods_title'                       => 'Adición de productos básicos',
        'add_self_extractio_address_title'      => 'Añadir dirección',
        'site_domain_tips_list'                 => [
            '1. 站点域名未设置则使用当前站点域名域名地址[ '.__MY_DOMAIN__.' ]',
            '2. 附件和静态地址未设置则使用当前站点静态域名地址[ '.__MY_PUBLIC_URL__.' ]',
            '3. 如服务器端不是以public设为根目录的、则这里配置【附件cdn域名、css/js静态文件cdn域名】需要后面再加public、如：'.__MY_PUBLIC_URL__.'public/',
            '4. para ejecutar el proyecto en modo línea de órdenes, la dirección de la zona debe configurarse, de lo contrario algunas direcciones en el proyecto carecerán de información de nombre de dominio.',
            '5. no configure indiscriminadamente, la Dirección incorrecta puede hacer que el sitio web no sea accesible (la configuración de la dirección comienza con https), si la configuración de su propia estación es https comienza con https',
        ],
        'site_cache_tips_list'                  => [
            '1. la caché de archivos utilizada por defecto y el uso de la caché de redis para phis requieren la instalación de la extensión de redis primero.',
            '2. asegúrese de la estabilidad del servicio redis (después de que la sesión utilice la caché, la inestabilidad del servicio puede hacer que el backstage no pueda iniciar sesión)',
            '3. si se encuentra con una anomalía en el servicio redis, no puede iniciar sesión en el Fondo de gestión, modificar el archivo de configuración bajo el catálogo [config] [session.ph, caché.ph] archivo',
        ],
        'goods_tips_list'                       => [
            '1. el lado Web muestra el nivel 3 por defecto, el nivel 1 más bajo y el nivel 3 más alto (si se establece en el nivel 0, el nivel 3 por defecto)',
            '2. el nivel 0 de visualización predeterminada en el teléfono móvil (modo de lista de productos), el nivel 0 más bajo y el nivel 3 más alto (1 a 3 es el modo de visualización clasificado)',
            '3. el nivel es diferente y el estilo de la página de clasificación frontal será diferente.',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. configurar cuántos productos se muestran como máximo en cada piso',
            '2. no se recomienda modificar demasiado el número, lo que hará que el área en blanco en el lado izquierdo del lado de la pc sea demasiado grande.',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'La combinación es: calor - > ventas - > último orden descendente (desc)',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. puede hacer clic en el título del producto para arrastrar y ordenar y mostrarlo en orden.',
            '2. no se recomienda agregar muchos productos, lo que hará que el área en blanco en el lado izquierdo del lado de la pc sea demasiado grande.',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. por defecto, el [nombre de usuario, teléfono móvil, buzón] es el único usuario.',
            '2. cuando se abre, se añade el [logotipo del sistema] paralelo como único usuario',
        ],
        'extends_crontab_tips'                  => 'Se recomienda agregar la dirección del guión a la solicitud de tiempo de la tarea de tiempo de Linux (resultado sucs: 0, detrás del error: 0 puntos está el número de barras de datos procesadas, sucs tiene éxito, Fali falló)',
        'left_images_random_tips'               => 'La imagen izquierda puede cargar hasta 3 imágenes y mostrar una de ellas al azar cada vez.',
        'background_color_tips'                 => 'Se puede personalizar la imagen de fondo, el gris de fondo predeterminado',
        'site_setup_layout_tips'                => 'El modo de arrastre necesita ingresar a la página de diseño de la página de inicio por sí mismo. por favor, guarde la configuración seleccionada antes de hacerlo.',
        'site_setup_layout_button_name'         => 'Ir a la página de diseño',
        'site_setup_goods_category_tips'        => 'Para más exhibición de pisos, por favor vaya primero / gestión de productos básicos - > clasificación de productos básicos, configuración de clasificación de primer nivel, recomendación de la página de inicio',
        'site_setup_goods_category_no_data_tips'=> 'No hay datos por el momento, por favor vaya primero / gestión de productos básicos - > clasificación de productos básicos, configuración de clasificación de primer nivel recomendación de la página de inicio',
        'site_setup_order_default_payment_tips' => 'Se puede configurar el método de pago predeterminado correspondiente a diferentes plataformas, primero instale el plug - in de pago en [gestión del sitio web - > método de pago] para habilitarlo y abrirlo a los usuarios.',
        'site_setup_choice_payment_message'     => 'Por favor, elija el método de pago predeterminado ({:name}).',
        'sitetype_top_tips_list'                => [
            '1. mensajería: proceso convencional de comercio electrónico, el usuario elige la dirección de recepción para hacer un pedido y pagar - > el comerciante distribuye el envío a la logística de terceros - > confirma la recepción - > el pedido se completa',
            '2. la misma ciudad: los jinetes de la misma ciudad o la distribución por sí mismos, los usuarios eligen la dirección de recepción para hacer un pedido de pago - > los comerciantes lo envían a terceros de la misma ciudad para la distribución o la distribución por sí mismos - > confirmar la recepción - > se completa el pedido',
            '3. exhibición: solo muestra el producto, se puede iniciar una consulta (no se puede hacer un pedido)',
            '4. autorecogida: al hacer un pedido, elija la dirección de la mercancía autorecogida, y el usuario haga un pedido para pagar - > confirmar la recogida - > completar el pedido',
            '5. virtual: proceso convencional de comercio electrónico, el usuario hace un pedido para pagar - > Envío automático - > confirmación de la recogida - > finalización del pedido',
        ],
        // 添加自提地址表单
        'form_take_address_title'                  => 'Dirección de recogida propia',
        'form_take_address_logo'                   => 'LOGO',
        'form_take_address_logo_tips'              => 'Recomendación 300 * 300px',
        'form_take_address_alias'                  => 'Alias',
        'form_take_address_alias_message'          => 'Formato alias hasta 16 caracteres',
        'form_take_address_name'                   => 'Contactos',
        'form_take_address_name_message'           => 'Formato de contacto entre 2 y 16 caracteres',
        'form_take_address_tel'                    => 'Número de teléfono de contacto',
        'form_take_address_tel_message'            => 'Por favor, rellene el número de contacto.',
        'form_take_address_address'                => 'Dirección detallada',
        'form_take_address_address_message'        => 'Formato de dirección detallado entre 1 y 80 caracteres',
        // 域名绑定语言
        'form_domain_multilingual_domain_name'     => 'Nombre de dominio',
        'form_domain_multilingual_domain_message'  => 'Por favor, rellene el nombre de dominio',
        'form_domain_multilingual_select_message'  => 'Por favor, elija el idioma correspondiente al nombre de dominio',
        'form_domain_multilingual_add_title'       => 'Añadir nombre de dominio',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Iniciar sesión en segundo plano',
        'admin_login_info_bg_images_list_tips'  => [
            '1. la imagen de fondo se encuentra en el catálogo [público / estático / admin / default / images / login]',
            '2. reglas de nomenclatura de imágenes de fondo (1 a 50), como 1.png',
        ],
        'map_type_tips'                         => 'Debido a que los estándares de mapa de cada familia son diferentes, no cambie el mapa a voluntad, lo que dará lugar a coordenadas inexactas del mapa.',
        'apply_map_baidu_name'                  => 'Por favor, solicite en la plataforma abierta de mapas de baidu.',
        'apply_map_amap_name'                   => 'Por favor, solicite en la plataforma abierta de gaode maps.',
        'apply_map_tencent_name'                => 'Por favor, solicite en la plataforma abierta de mapas de tencent.',
        'apply_map_tianditu_name'               => 'Por favor, solicite en la plataforma abierta tianmap.',
        'cookie_domain_list_tips'               => [
            '1. si está vacío por defecto, solo es válido para el nombre de dominio de acceso actual.',
            '2. si se necesita un nombre de dominio secundario y una Cookie compartida, rellene el nombre de dominio de nivel superior, como: baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'Marca',
        // 动态表格
        'form_table'                            => [
            'id'                   => 'ID de marca',
            'name'                 => 'Nombre',
            'describe'             => 'Descripción',
            'logo'                 => 'LOGO',
            'url'                  => 'Dirección del sitio web oficial',
            'brand_category_text'  => 'Clasificación de la marca',
            'is_enable'            => 'Si habilitar',
            'sort'                 => 'Ordenar',
            'add_time'             => 'Tiempo de creación',
            'upd_time'             => 'Tiempo de actualización',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'Clasificación de la marca',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Artículo',
        'detail_content_title'                  => 'Contenido de los detalles',
        'detail_images_title'                   => 'Detalles de la imagen',
        // 动态表格
        'form_table'                            => [
            'id'                     => 'ID del artículo',
            'cover'                  => 'Portada',
            'info'                   => 'Título',
            'describe'               => 'Descripción',
            'article_category_name'  => 'Clasificación',
            'is_enable'              => 'Si habilitar',
            'is_home_recommended'    => 'Recomendación de la página de inicio',
            'jump_url'               => 'Dirección de la dirección de la dirección de salto',
            'images_count'           => 'Número de imágenes',
            'access_count'           => 'Número de visitas',
            'add_time'               => 'Tiempo de creación',
            'upd_time'               => 'Tiempo de actualización',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'Clasificación de artículos',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Página personalizada',
        'detail_content_title'                  => 'Contenido de los detalles',
        'detail_images_title'                   => 'Detalles de la imagen',
        'save_view_tips'                        => 'Guarde antes de Previsualizar el efecto',
        // 动态表格
        'form_table'                            => [
            'id'              => 'ID de datos',
            'logo'            => 'logo',
            'name'            => 'Nombre',
            'is_enable'       => 'Si habilitar',
            'is_header'       => 'Si la cabeza',
            'is_footer'       => 'Si la cola',
            'is_full_screen'  => 'Si la pantalla está llena',
            'images_count'    => 'Número de imágenes',
            'access_count'    => 'Número de visitas',
            'add_time'        => 'Tiempo de creación',
            'upd_time'        => 'Tiempo de actualización',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Descarga de más plantillas de diseño',
        'upload_list_tips'                      => [
            '1. seleccione la página descargada para diseñar el paquete ZIP',
            '2. la importación agregará automáticamente un nuevo dato',
        ],
        'operate_sync_tips'                     => 'Los datos se sincronizan a la página de inicio y se arrastran a la visualización, y luego se modifican los datos sin verse afectados, pero no se eliminan los archivos adjuntos relacionados.',
        // 动态表格
        'form_table'                            => [
            'id'                => 'ID de datos',
            'logo'              => 'logo',
            'name'              => 'Nombre',
            'access_count'      => 'Número de visitas',
            'is_enable'         => 'Si habilitar',
            'is_header'         => 'Si incluye la cabeza',
            'is_footer'         => 'Si contiene cola',
            'seo_title'         => 'Título seo',
            'seo_keywords'      => 'Palabras clave seo',
            'seo_desc'          => 'Descripción seo',
            'add_time'          => 'Tiempo de creación',
            'upd_time'          => 'Tiempo de actualización',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Almacén',
        'top_tips_list'                         => [
            '1. cuanto mayor sea el valor del peso, mayor será el peso representativo, y el inventario deducido se deducirá de acuerdo con el peso)',
            '2. el almacén solo se eliminará suavemente, no estará disponible después de la eliminación y solo conservará datos en la base de datos) puede eliminar los datos de productos asociados por sí mismo.',
            '3. las existencias de productos desactivadas y eliminadas del almacén y asociadas se liberarán inmediatamente.',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Nombre / alias',
            'level'          => 'Peso',
            'is_enable'      => 'Si habilitar',
            'contacts_name'  => 'Contactos',
            'contacts_tel'   => 'Número de teléfono de contacto',
            'province_name'  => 'Provincia',
            'city_name'      => 'Ciudad',
            'county_name'    => 'Distrito / Condado',
            'address'        => 'Dirección detallada',
            'position'       => 'Longitud y latitud',
            'add_time'       => 'Tiempo de creación',
            'upd_time'       => 'Tiempo de actualización',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Por favor, elija el almacén',
        ],
        // 基础
        'add_goods_title'                       => 'Adición de productos básicos',
        'no_spec_data_tips'                     => 'Datos sin especificaciones',
        'batch_setup_inventory_placeholder'     => 'Valores establecidos por lotes',
        'base_spec_inventory_title'             => 'Inventario de especificaciones',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Información básica',
            'goods_placeholder'  => 'Por favor, introduzca el nombre / modelo del producto.',
            'warehouse_name'     => 'Almacén',
            'is_enable'          => 'Si habilitar',
            'inventory'          => 'Inventario total',
            'spec_inventory'     => 'Inventario de especificaciones',
            'add_time'           => 'Tiempo de creación',
            'upd_time'           => 'Tiempo de actualización',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'La información del Administrador no existe',
        // 列表
        'top_tips_list'                         => [
            '1. la cuenta de Administrador tiene todos los permisos por defecto y no se puede cambiar.',
            '2.  Учетная запись admin не может быть изменена, но может быть изменена в таблице данных ('.MyConfig(' database.connections.mysql.prefix').'admin) Поле username',
        ],
        'base_nav_title'                        => 'Administrador',
        // 登录
        'login_type_username_title'             => 'Contraseña de la cuenta',
        'login_type_mobile_title'               => 'Código de verificación del teléfono móvil',
        'login_type_email_title'                => 'Código de verificación del buzón',
        'login_close_tips'                      => 'Inicio de sesión cerrado temporalmente',
        // 忘记密码
        'form_forget_password_name'             => '¿Olvidar la contraseña?',
        'form_forget_password_tips'             => 'Póngase en contacto con el Administrador para restablecer la contraseña',
        // 动态表格
        'form_table'                            => [
            'username'              => 'Administrador',
            'status'                => 'Estado',
            'gender'                => 'Género',
            'mobile'                => 'Teléfono móvil',
            'email'                 => 'Buzón',
            'role_name'             => 'Grupo de personajes',
            'login_total'           => 'Número de conexiones',
            'login_time'            => 'Última Hora de inicio de sesión',
            'add_time'              => 'Tiempo de creación',
            'upd_time'              => 'Tiempo de actualización',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Protocolo de registro de usuarios', 'type' => 'register'],
            ['name' => 'Política de privacidad de los usuarios', 'type' => 'privacy'],
            ['name' => 'Acuerdo de cancelación de cuenta', 'type' => 'logout']
        ],
        'top_tips'          => 'La dirección del Protocolo de acceso frontal aumenta el parámetro is Contenido = 1 solo muestra contenido de protocolo puro',
        'view_detail_name'                      => 'Ver detalles',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Configuración básica', 'type' => 'index'],
            ['name' => 'APP / applet', 'type' => 'app'],
        ],
        'home_diy_template_title'               => 'Plantilla DIY de página de inicio',
        'online_service_title'                  => 'Servicio al cliente en línea',
        'user_base_popup_title'                 => 'Consejos de ventana emergente de información básica del usuario',
        'user_onekey_bind_mobile_tips_list'     => [
            '1. Obtenga la cuenta actual de la Plataforma de applets o el número de teléfono móvil de esta máquina para iniciar sesión con un solo clic, y actualmente solo admite [applet de wechat, applet de baidu, applet de titulares]',
            '2. La Dependencia requiere abrir el "teléfono obligatorio" válido',
            '3. Algunas plataformas de applets pueden necesitar solicitar permisos, por favor solicite de acuerdo con los requisitos de la Plataforma de applets antes de abrirlos en consecuencia.',
        ],
        'user_address_platform_import_tips_list'=> [
            '1. Obtiene la dirección de recepción de la cuenta de aplicación de la Plataforma de applets actual, y actualmente solo admite [applets]',
            '2. Después de confirmar la importación, se añade directamente a la dirección de recepción del usuario del sistema.',
            '3. Algunas plataformas de applets pueden necesitar solicitar permisos, por favor solicite de acuerdo con los requisitos de la Plataforma de applets antes de abrirlos en consecuencia.',
        ],
        'user_base_popup_top_tips_list'         => [
            '1. En la actualidad, solo la Plataforma de applets de Wechat autoriza automáticamente el inicio de sesión sin apodos de usuario e información de avatar.',
        ],
        'online_service_top_tips_list'          => [
            '1. el Protocolo https de servicio al cliente personalizado se abre en webview',
            '2. orden de prioridad del servicio al cliente [sistema de servicio al cliente - > Servicio al cliente personalizado - > Servicio al cliente de Wechat corporativo (solo la aplicación + H5 + applet de Wechat está en vigor) - > Servicio al cliente de cada plataforma final - > Servicio al cliente telefónico]',
        ],
        'home_diy_template_tips'                => 'Si no selecciona la plantilla DIY, seguirá la configuración unificada de la página de inicio por defecto.',
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Temas actuales', 'type' => 'index'],
            ['name' => 'Instalación temática', 'type' => 'upload'],
            ['name' => 'Descarga de paquetes de código fuente', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'Descarga de más temas',
        'nav_theme_download_name'               => 'Ver el tutorial de embalaje de applets',
        'nav_theme_download_tips'               => 'El tema del teléfono móvil se desarrolla con una aplicación uniapp (admite applets multiterminales, h5, app)',
        'form_alipay_extend_title'              => 'Configuración del servicio al cliente',
        'form_alipay_extend_tips'               => 'Ps: si se abre en [app / applet] (se abre el servicio al cliente en línea), se deben rellenar las siguientes configuraciones [código corporativo] y [código de ventana de chat]',
        'form_theme_upload_tips'                => 'Cargar un paquete de instalación en formato zip comprimido',
        'list_no_data_tips'                     => 'No hay paquetes temáticos relevantes',
        'list_author_title'                     => 'Autor',
        'list_version_title'                    => 'Versión aplicable',
        'package_generate_tips'                 => '¡El tiempo de generación es relativamente largo, ¡ por favor, no cierre la ventana del navegador!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Nombre del paquete',
            'size'  => 'Tamaño',
            'url'   => 'Dirección de descarga',
            'time'  => 'Tiempo de creación',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Configuración de SMS', 'type' => 'index'],
            ['name' => 'Plantilla de mensajes', 'type' => 'message'],
        ],
        'top_tips'                              => 'Dirección de gestión de mensajes de texto de Alibaba Cloud',
        'top_to_aliyun_tips'                    => 'Haga clic para comprar mensajes de texto en Alibaba Cloud',
        'base_item_admin_title'                 => 'Backstage',
        'base_item_index_title'                 => 'Frontal',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Configuración del buzón', 'type' => 'index'],
            ['name' => 'Plantilla de mensajes', 'type' => 'message'],
        ],
        'top_tips'                              => 'Debido a algunas diferencias en las diferentes plataformas de buzón, la configuración también es diferente, específicamente sujeto al tutorial de configuración de la Plataforma de buzón.',
        // 基础
        'test_title'                            => 'Prueba',
        'test_content'                          => 'Configuración del correo - enviar contenido de prueba',
        'base_item_admin_title'                 => 'Backstage',
        'base_item_index_title'                 => 'Frontal',
        // 表单
        'form_item_test'                        => 'Prueba de la dirección de correo recibida',
        'form_item_test_tips'                   => 'Guarde la configuración antes de realizar la prueba',
        'form_item_test_button_title'           => 'Prueba',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'De acuerdo con el entorno del servidor [nginx, apache, iis] diferentes configuraciones de las reglas pseudo - estáticas correspondientes',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'Productos Básicos',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Información básica', 'type'=>'base'],
            'spec'            => ['name' => 'Especificaciones del producto', 'type'=>'spec'],
            'spec_images'     => ['name' => 'Imagen de las especificaciones', 'type'=>'spec_images'],
            'parameters'      => ['name' => 'Parámetros de los productos básicos', 'type'=>'parameters'],
            'photo'           => ['name' => 'Álbum de fotos de productos', 'type'=>'photo'],
            'video'           => ['name' => 'Video de productos básicos', 'type'=>'video'],
            'app'             => ['name' => 'Detalles del teléfono móvil', 'type'=>'app'],
            'web'             => ['name' => 'Detalles de la computadora', 'type'=>'web'],
            'fictitious'      => ['name' => 'Información virtual', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Datos ampliados', 'type'=>'extends'],
            'seo'             => ['name' => 'Información seo', 'type'=>'seo'],
        ],
        'delete_only_goods_text'                => 'Solo productos básicos',
        'delete_goods_and_images_text'          => 'Productos e imágenes',
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Identificación de la mercancía',
            'info'                    => 'Información sobre productos básicos',
            'info_placeholder'        => 'Introduzca el nombre del producto / descripción / código / código de barras / información seo',
            'category_text'           => 'Clasificación de mercancías',
            'brand_name'              => 'Marca',
            'price'                   => 'Precio de venta (yuan)',
            'original_price'          => 'Precio original (yuan)',
            'inventory'               => 'Inventario total',
            'is_shelves'              => 'Subir y bajar de los estantes',
            'is_deduction_inventory'  => 'Deducción de inventario',
            'site_type'               => 'Tipo de mercancía',
            'model'                   => 'Modelo de producto',
            'place_origin_name'       => 'Lugar de producción',
            'give_integral'           => 'Proporción de puntos de regalo de compra',
            'buy_min_number'          => 'Número mínimo de compras a partir de una sola vez',
            'buy_max_number'          => 'Cantidad máxima de compra única',
            'sort_level'              => 'Peso de clasificación',
            'sales_count'             => 'Ventas',
            'access_count'            => 'Número de visitas',
            'add_time'                => 'Tiempo de creación',
            'upd_time'                => 'Tiempo de actualización',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'Clasificación de mercancías',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'Comentarios sobre productos básicos',
        // 动态表格
        'form_table'                            => [
            'user'               => 'Información del usuario',
            'user_placeholder'   => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
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

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'Parámetros de los productos básicos',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Clasificación de mercancías',
            'name'          => 'Nombre',
            'is_enable'     => 'Si habilitar',
            'config_count'  => 'Número de parámetros',
            'add_time'      => 'Tiempo de creación',
            'upd_time'      => 'Tiempo de actualización',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Clasificación de mercancías',
            'name'         => 'Nombre',
            'is_enable'    => 'Si habilitar',
            'content'      => 'Valor de la especificación',
            'add_time'     => 'Tiempo de creación',
            'upd_time'     => 'Tiempo de actualización',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Información del usuario',
            'user_placeholder'   => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'goods'              => 'Información sobre productos básicos',
            'goods_placeholder'  => 'Introduzca el nombre del producto / breve descripción / información seo',
            'price'              => 'Precio de venta (yuan)',
            'original_price'     => 'Precio original (yuan)',
            'add_time'           => 'Tiempo de creación',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Información del usuario',
            'user_placeholder'   => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'goods'              => 'Información sobre productos básicos',
            'goods_placeholder'  => 'Introduzca el nombre del producto / breve descripción / información seo',
            'price'              => 'Precio de venta (yuan)',
            'original_price'     => 'Precio original (yuan)',
            'add_time'           => 'Tiempo de creación',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'Información del usuario',
            'user_placeholder'   => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'goods'              => 'Información sobre productos básicos',
            'goods_placeholder'  => 'Introduzca el nombre del producto / breve descripción / información seo',
            'price'              => 'Precio de venta (yuan)',
            'original_price'     => 'Precio original (yuan)',
            'add_time'           => 'Tiempo de creación',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Enlace amistoso',
        // 动态表格
        'form_table'                            => [
            'info'                => 'Nombre',
            'url'                 => 'Dirección URL',
            'describe'            => 'Descripción',
            'is_enable'           => 'Si habilitar',
            'is_new_window_open'  => 'Si se abre una ventana nueva',
            'sort'                => 'Ordenar',
            'add_time'            => 'Tiempo de creación',
            'upd_time'            => 'Tiempo de actualización',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Navegación intermedia', 'type' => 'header'],
            ['name' => 'Navegación inferior', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'Personalizado',
            'article'           => 'Artículo',
            'customview'        => 'Página personalizada',
            'goods_category'    => 'Clasificación de mercancías',
            'design'            => 'Diseño de página',
            'plugins'           => 'Plug - in de página de inicio',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => 'Nombre de la navegación',
            'data_type'           => 'Tipo de datos de navegación',
            'is_show'             => 'Si se muestra',
            'is_new_window_open'  => 'Se abre una nueva ventana',
            'sort'                => 'Ordenar',
            'add_time'            => 'Tiempo de creación',
            'upd_time'            => 'Tiempo de actualización',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'El ID del pedido es incorrecto',
            'payment_choice_tips'               => 'Por favor, elija el método de pago.',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Operación de envío',
        'form_service_title'                    => 'Operación de servicio',
        'form_payment_title'                    => 'Operaciones de pago',
        'form_item_take'                        => 'Código de recogida',
        'form_item_take_message'                => 'Por favor, rellene el Código de recogida de 4 dígitos.',
        'form_item_express_add_name'            => 'Añadir mensajería',
        'form_item_express_choice_win_name'     => 'Selección de mensajería',
        'form_item_express_id'                  => 'Método de mensajería',
        'form_item_express_number'              => 'Número de la lista de mensajería',
        'form_item_service_name'                => 'Nombre del personal de servicio',
        'form_item_service_name_message'        => 'Por favor, rellene el nombre del personal de servicio.',
        'form_item_service_mobile'              => 'Teléfono móvil del personal de servicio',
        'form_item_service_mobile_message'      => 'Por favor, rellene el teléfono móvil del personal de servicio.',
        'form_item_service_time'                => 'Tiempo de servicio',
        'form_item_service_start_time'          => 'Hora de inicio del servicio',
        'form_item_service_start_time_message'  => 'Por favor, elija la hora de inicio del servicio',
        'form_item_service_end_time'            => 'Hora de finalización del servicio',
        'form_item_service_end_time_message'    => 'Por favor, elija la hora de finalización del servicio',
        'form_item_note'                        => 'Nota información',
        'form_item_note_message'                => 'La información de la nota tiene un máximo de 200 caracteres',
        // 地址
        'detail_user_address_title'             => 'Dirección de recepción',
        'detail_user_address_name'              => 'Destinatarios',
        'detail_user_address_tel'               => 'Teléfono de recepción',
        'detail_user_address_value'             => 'Dirección detallada',
        'detail_user_address_idcard'            => 'Información de la tarjeta de identificación',
        'detail_user_address_idcard_name'       => 'Nombre',
        'detail_user_address_idcard_number'     => 'Número',
        'detail_user_address_idcard_pic'        => 'Foto',
        'detail_take_address_title'             => 'Dirección de recogida',
        'detail_take_address_contact'           => 'Información de contacto',
        'detail_take_address_value'             => 'Dirección detallada',
        'detail_service_title'                  => 'Información de servicio',
        'detail_fictitious_title'               => 'Información clave',
        // 订单售后
        'detail_aftersale_status'               => 'Estado',
        'detail_aftersale_type'                 => 'Tipo',
        'detail_aftersale_price'                => 'Importe',
        'detail_aftersale_number'               => 'Cantidad',
        'detail_aftersale_reason'               => 'Causa',
        // 商品
        'detail_goods_title'                    => 'Productos pedidos',
        'detail_payment_amount_less_tips'       => 'Tenga en cuenta que el monto pagado por el pedido es inferior al monto total del precio.',
        'detail_no_payment_tips'                => 'Tenga en cuenta que el pedido aún no se ha pagado',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Información básica',
            'goods_placeholder'   => 'Introduzca el id del pedido / número de pedido / nombre del producto / Modelo',
            'user'                => 'Información del usuario',
            'user_placeholder'    => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'status'              => 'Estado del pedido',
            'pay_status'          => 'Estado de pago',
            'total_price'         => 'Precio total (yuan)',
            'pay_price'           => 'Monto de pago (yuan)',
            'price'               => 'Precio unitario (yuan)',
            'warehouse_name'      => 'Almacén de envío',
            'order_model'         => 'Modo de pedido',
            'client_type'         => 'Fuente',
            'address'             => 'Información de dirección',
            'service'             => 'Información de servicio',
            'take'                => 'Información de recogida',
            'refund_price'        => 'Monto del reembolso (yuan)',
            'returned_quantity'   => 'Número de devoluciones',
            'buy_number_count'    => 'Total de compras',
            'increase_price'      => 'Aumento de la cantidad (yuan)',
            'preferential_price'  => 'Cantidad preferencial (yuan)',
            'payment_name'        => 'Método de pago',
            'user_note'           => 'Nota del usuario',
            'extension'           => 'Información extendida',
            'express'             => 'Información de mensajería',
            'express_placeholder' => 'Introduzca su Número de la lista de mensajería',
            'aftersale'           => 'Último post - venta',
            'is_comments'         => 'Si el usuario comenta',
            'confirm_time'        => 'Tiempo de confirmación',
            'pay_time'            => 'Tiempo de pago',
            'delivery_time'       => 'Tiempo de envío',
            'collect_time'        => 'Tiempo de finalización',
            'cancel_time'         => 'Hora de cancelación',
            'close_time'          => 'Tiempo de cierre',
            'add_time'            => 'Tiempo de creación',
            'upd_time'            => 'Tiempo de actualización',
        ],
        // 动态表格统计字段
        'form_table_stats'                      => [
            'total_price'        => 'Total de pedidos',
            'pay_price'          => 'Total de pagos',
            'buy_number_count'   => 'Total de productos básicos',
            'refund_price'       => 'Importe del reembolso',
            'returned_quantity'  => 'Número de devoluciones',
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
    'orderaftersale'        => [
        'form_audit_title'                      => 'Operación de auditoría',
        'form_refuse_title'                     => 'Operación rechazada',
        'form_user_info_title'                  => 'Información del usuario',
        'form_apply_info_title'                 => 'Información de la solicitud',
        'forn_apply_info_type'                  => 'Tipo',
        'forn_apply_info_price'                 => 'Importe',
        'forn_apply_info_number'                => 'Cantidad',
        'forn_apply_info_reason'                => 'Causa',
        'forn_apply_info_msg'                   => 'Explicación',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Información básica',
            'goods_placeholder'  => 'Introduzca el id del pedido / número de pedido / nombre del producto / Modelo',
            'user'               => 'Información del usuario',
            'user_placeholder'   => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'status'             => 'Estado',
            'type'               => 'Tipo de solicitud',
            'reason'             => 'Causa',
            'price'              => 'Monto del reembolso (yuan)',
            'number'             => 'Número de devoluciones',
            'msg'                => 'Instrucciones de reembolso',
            'refundment'         => 'Tipo de reembolso',
            'voucher'            => 'Comprobante',
            'express_name'       => 'Empresa de mensajería',
            'express_number'     => 'Número de la lista de mensajería',
            'refuse_reason'      => 'Razones del rechazo',
            'apply_time'         => 'Tiempo de solicitud',
            'confirm_time'       => 'Tiempo de confirmación',
            'delivery_time'      => 'Tiempo de devolución',
            'audit_time'         => 'Tiempo de revisión',
            'add_time'           => 'Tiempo de creación',
            'upd_time'           => 'Tiempo de actualización',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => 'Reembolso total',
            'number'  => 'Número total de devoluciones',
        ],
    ],

    // 支付方式
    'payment'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Instalado', 'type' => 0],
            ['name' => 'No instalado', 'type' => 1],
        ],
        'base_nav_title'                        => 'Método de pago',
        'base_upload_payment_name'              => 'Importar pago',
        'base_nav_store_payment_name'           => 'Descarga de más métodos de pago',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1. el nombre de la clase debe ser consistente con el nombre del archivo (eliminar. php), como alipay.php, Alipay',
            ],
            [
                'name'  => '2. los métodos que deben definirse en las categorías',
                'item'  => [
                    '2.1. método de configuración de config',
                    '2.2. método de pago de pay',
                    '2.3. método de devolución de llamada de respuesta',
                    '2.4. método de devolución de llamada asíncrona notificy (opcional, sin definir, se llama método de respuesta)',
                    '2.5. método de reembolso de fondos (opcional, no definido, no se puede iniciar el reembolso original)',
                ],
            ],
            [
                'name'  => '3. se puede personalizar el método de contenido de salida',
                'item'  => [
                    '3.1. pago exitoso de succesreturn (opcional)',
                    '3.2. falló el pago de errorreturn (opcional)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'Ps: si no se cumplen las condiciones anteriores, no se puede ver el plug - in, cargar el plug - in en el paquete de compresión. zip, soportar una compresión que contiene varios plug - INS de pago',
        // 动态表格
        'form_table'                            => [
            'name'            => 'Nombre',
            'logo'            => 'LOGO',
            'version'         => 'Versión',
            'apply_version'   => 'Versión aplicable',
            'apply_terminal'  => 'Terminales aplicables',
            'author'          => 'Autor',
            'desc'            => 'Descripción',
            'enable'          => 'Si habilitar',
            'open_user'       => 'Los usuarios están abiertos',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'Expreso',
    ],

    // 主题管理
    'themeadmin'            => [
        'base_nav_list'                         => [
            ['name' => 'Temas actuales', 'type' => 'index'],
            ['name' => 'Instalación temática', 'type' => 'upload'],
        ],
        'base_upload_theme_name'                => 'Importar temas',
        'base_nav_store_theme_name'             => 'Descarga de más temas',
        'list_author_title'                     => 'Autor',
        'list_version_title'                    => 'Versión aplicable',
        'form_theme_upload_tips'                => 'Cargar un paquete de instalación de temas en formato zip comprimido',
    ],

    // 主题数据
    'themedata'             => [
        'base_nav_title'                        => 'Datos temáticos',
        'upload_list_tips'                      => [
            '1. seleccione el paquete zip de datos temáticos descargado',
            '2. la importación agregará automáticamente un nuevo dato',
        ],
        // 动态表格
        'form_table'                            => [
            'unique'    => 'Identificación única',
            'name'      => 'Nombre',
            'type'      => 'Tipo de datos',
            'theme'     => 'Tema',
            'view'      => 'Página',
            'is_enable' => 'Si habilitar',
            'add_time'  => 'añadir tiempo',
            'upd_time'  => 'tiempo de actualización',
        ],
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Navegación del Centro de usuarios móviles',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Nombre',
            'platform'       => 'Plataforma a la que pertenece',
            'images_url'     => 'Iconos de navegación',
            'event_type'     => 'Tipo de evento',
            'event_value'    => 'Valor del evento',
            'desc'           => 'Descripción',
            'is_enable'      => 'Si habilitar',
            'is_need_login'  => 'Si necesita iniciar sesión',
            'sort'           => 'Ordenar',
            'add_time'       => 'Tiempo de creación',
            'upd_time'       => 'Tiempo de actualización',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => 'Navegación de la página de inicio del teléfono móvil',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Nombre',
            'platform'       => 'Plataforma a la que pertenece',
            'images'         => 'Iconos de navegación',
            'event_type'     => 'Tipo de evento',
            'event_value'    => 'Valor del evento',
            'is_enable'      => 'Si habilitar',
            'is_need_login'  => 'Si necesita iniciar sesión',
            'sort'           => 'Ordenar',
            'add_time'       => 'Tiempo de creación',
            'upd_time'       => 'Tiempo de actualización',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Registro de solicitudes de pago',
        // 动态表格
        'form_table'                            => [
            'user'              => 'Información del usuario',
            'user_placeholder'  => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'log_no'            => 'Número de orden de pago',
            'payment'           => 'Método de pago',
            'status'            => 'Estado',
            'total_price'       => 'Monto del pedido de negocio (yuan)',
            'pay_price'         => 'Monto de pago (yuan)',
            'business_type'     => 'Tipo de negocio',
            'business_list'     => 'Identificación comercial / número único',
            'trade_no'          => 'Número de transacción de la Plataforma de pago',
            'buyer_user'        => 'Cuenta de usuario de la Plataforma de pago',
            'subject'           => 'Nombre del pedido',
            'request_params'    => 'Parámetros de solicitud',
            'pay_time'          => 'Tiempo de pago',
            'close_time'        => 'Tiempo de cierre',
            'add_time'          => 'Tiempo de creación',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Registro de solicitudes de pago',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Tipo de negocio',
            'request_params'   => 'Parámetros de solicitud',
            'response_data'    => 'Datos de respuesta',
            'business_handle'  => 'Resultados del procesamiento de Negocios',
            'request_url'      => 'Dirección de la URL solicitada',
            'server_port'      => 'Número de Puerto',
            'server_ip'        => 'IP del servidor',
            'client_ip'        => 'IP del cliente',
            'os'               => 'Sistema operativo',
            'browser'          => 'Navegador',
            'method'           => 'Tipo de solicitud',
            'scheme'           => 'Tipo https',
            'version'          => 'Versión https',
            'client'           => 'Detalles del cliente',
            'add_time'         => 'Tiempo de creación',
            'upd_time'         => 'Tiempo de actualización',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'Información del usuario',
            'user_placeholder'  => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'payment'           => 'Método de pago',
            'business_type'     => 'Tipo de negocio',
            'business_id'       => 'ID de pedido de negocio',
            'trade_no'          => 'Número de transacción de la Plataforma de pago',
            'buyer_user'        => 'Cuenta de usuario de la Plataforma de pago',
            'refundment_text'   => 'Método de reembolso',
            'refund_price'      => 'Importe del reembolso',
            'pay_price'         => 'Monto del pago del pedido',
            'msg'               => 'Descripción',
            'request_params'    => 'Parámetros de solicitud',
            'return_params'     => 'Parámetros de respuesta',
            'add_time_time'     => 'Tiempo de reembolso',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Volver a la gestión de la aplicación > *',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Por favor, haga clic en gancho para activarlo primero.',
            'save_no_data_tips'                 => 'No hay datos de plug - in guardables',
        ],
        // 基础导航
        'base_nav_title'                        => 'Aplicación',
        'base_upload_application_name'          => 'Importar aplicaciones',
        'base_nav_more_plugins_download_name'   => 'Descarga de más plug - ins',
        // 基础页面
        'base_search_input_placeholder'         => 'Introduzca el nombre / descripción',
        'base_top_tips_one'                     => 'Método de clasificación de lista [clasificación personalizada - > instalación más temprana]',
        'base_top_tips_two'                     => 'Se puede hacer clic y arrastrar para ajustar el orden de llamada y exhibición del plug - IN.',
        'base_open_setup_title'                 => 'Activar configuración',
        'data_list_author_title'                => 'Autor',
        'data_list_author_url_title'            => 'Página principal',
        'data_list_version_title'               => 'Versión',
        'data_list_second_domain_title'         => 'Nombre de dominio secundario',
        'data_list_second_domain_tips'          => 'Por favor, configure el nombre de dominio principal válido de la Cookie en segundo plano [sistema - > configuración del sistema - > seguridad]',
        'uninstall_confirm_tips'                => '¿¿ desinstalar puede perder los datos de configuración básicos del plug - in que no se pueden restaurar y confirmar?',
        'not_install_divide_title'              => 'Los siguientes plug - ins no están instalados',
        'delete_plugins_text'                   => '1. eliminar solo la aplicación',
        'delete_plugins_text_tips'              => '(solo se elimina el Código de aplicación y se conservan los datos de la aplicación)',
        'delete_plugins_data_text'              => '2. eliminar la aplicación y eliminar los datos',
        'delete_plugins_data_text_tips'         => '(se eliminarán el Código de aplicación y los datos de la aplicación)',
        'delete_plugins_ps_tips'                => '¡Ps: las siguientes operaciones no se pueden restaurar después, ¡ por favor, opere con precaución!',
        'delete_plugins_button_name'            => 'Eliminar solo la aplicación',
        'delete_plugins_data_button_name'       => 'Eliminar aplicaciones y datos',
        'cancel_delete_plugins_button_name'     => 'Piénsalo de nuevo.',
        'more_plugins_store_to_text'            => 'Ir a la tienda de aplicaciones para seleccionar más plug - ins para enriquecer el sitio > > 1',
        'no_data_store_to_text'                 => 'Ir a la tienda de aplicaciones para seleccionar los sitios ricos en plug - ins > >',
        'plugins_category_title'                => 'Clasificación de aplicaciones',
        'plugins_category_admin_title'          => 'Gestión clasificada',
        'plugins_menu_control_title'            => 'Menú izquierdo',
    ],

    // 插件分类
    'pluginscategory'       => [
        'base_nav_title'                        => 'Clasificación de aplicaciones',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Volver al fondo',
        'get_loading_tips'                      => 'En proceso de adquisición...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'Personajes',
        'admin_not_modify_tips'                 => 'El superadministrador tiene todos los permisos por defecto y no se puede cambiar.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Nombre del personaje',
            'is_enable'  => 'Si habilitar',
            'add_time'   => 'Tiempo de creación',
            'upd_time'   => 'Tiempo de actualización',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'Permisos',
        'top_tips_list'                         => [
            '1. los técnicos no profesionales no deben operar los datos de la página, los errores de operación pueden causar confusión en el menú de permisos.',
            '2. el menú de permisos se divide en dos tipos [uso, operación], el menú de uso generalmente se abre para mostrar, y el menú de operación debe ocultarse.',
            '3. Si se produce una confusión en el menú de permisos, se puede volver a sobreescribir[ '.MyConfig('database.connections.mysql.prefix').'power ]Recuperación de datos de la tabla de datos.',
            '4. [superadministrador, cuenta de administrador] tiene todos los permisos por defecto y no se puede cambiar.',
        ],
        'content_top_tips_list'                 => [
            '1. rellene [nombre del controlador y nombre del método] necesita crear la definición del controlador y método correspondiente.',
            '2. ubicación del archivo del controlador [app / admin / controlador] esta operación solo es utilizada por el desarrollador',
            '3. el nombre del controlador / nombre del método y la dirección URL personalizada, ambos deben rellenar uno',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'Navegación rápida',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Nombre',
            'platform'     => 'Plataforma a la que pertenece',
            'images'       => 'Iconos de navegación',
            'event_type'   => 'Tipo de evento',
            'event_value'  => 'Valor del evento',
            'is_enable'    => 'Si habilitar',
            'sort'         => 'Ordenar',
            'add_time'     => 'Tiempo de creación',
            'upd_time'     => 'Tiempo de actualización',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'Región',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'Precio de selección',
        'top_tips_list'                         => [
            'Precio mínimo 0 - precio máximo 100 es inferior a 100',
            'Precio mínimo 1000 - precio máximo 0 es superior a 1000',
            'Precio mínimo 100 - precio máximo 500 es superior o igual a 100 y inferior a 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Rotación',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Nombre',
            'describe'     => 'Descripción',
            'platform'     => 'Plataforma a la que pertenece',
            'images'       => 'Imagen',
            'event_type'   => 'Tipo de evento',
            'event_value'  => 'Valor del evento',
            'is_enable'    => 'Si habilitar',
            'sort'         => 'Ordenar',
            'start_time'   => 'Hora de inicio',
            'end_time'     => 'Fin del tiempo',
            'add_time'     => 'Tiempo de creación',
            'upd_time'     => 'Tiempo de actualización',
        ],
    ],

    // diy装修
    'diy'                   => [
        'nav_store_diy_name'                    => 'Más descargas de plantillas DIY',
        'nav_apptabbar_name'                    => 'Menú inferior',
        'upload_list_tips'                      => [
            '1. Elija el paquete zip de diseño DIY descargado',
            '2. La importación agregará automáticamente un nuevo dato',
        ],
        // 动态表格
        'form_table'                            => [
            'id'            => 'ID de datos',
            'md5_key'       => 'Identificación única',
            'logo'          => 'logo',
            'name'          => 'Nombre',
            'describe'      => 'Descripción',
            'access_count'  => 'Número de visitas',
            'is_enable'     => 'Si habilitar',
            'add_time'      => 'Tiempo de creación',
            'upd_time'      => 'Tiempo de actualización',
        ],
    ],

    // 附件
    'attachment'                 => [
        'base_nav_title'                        => 'Anexo',
        'category_admin_title'                  => 'Gestión clasificada',
        // 动态表格
        'form_table'                            => [
            'category_name'  => 'Clasificación',
            'type_name'      => 'Tipo',
            'info'           => 'Anexo',
            'original'       => 'Nombre del archivo original',
            'title'          => 'Nuevo nombre de archivo',
            'size'           => 'Tamaño',
            'ext'            => 'Sufijo',
            'url'            => 'Dirección URL ',
            'hash'           => 'hash',
            'add_time'       => 'Tiempo de creación',
        ],
    ],

    // 附件分类
    'attachmentcategory'        => [
        'base_nav_title'                        => 'Clasificación de los anexos',
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'Información del usuario',
            'user_placeholder'    => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'type'                => 'Tipo de operación',
            'operation_integral'  => 'Puntos de operación',
            'original_integral'   => 'Puntos originales',
            'new_integral'        => 'Los últimos puntos',
            'msg'                 => 'Razones de la operación',
            'operation_id'        => 'Identificación del operador',
            'add_time_time'       => 'Tiempo de operación',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'Información del usuario',
            'user_placeholder'          => 'Introduzca su nombre de usuario / apodo / teléfono móvil / buzón',
            'type'                      => 'Tipo de mensaje',
            'business_type'             => 'Tipo de negocio',
            'title'                     => 'Título',
            'detail'                    => 'Detalles',
            'is_read'                   => 'Si se ha leído',
            'user_is_delete_time_text'  => 'Si el usuario elimina',
            'add_time_time'             => 'Tiempo de envío',
        ],
    ],

    // 短信日志
    'smslog'               => [
        // 动态表格
        'form_table'                            => [
            'platform'        => 'sms platform',
            'status'          => 'Estado',
            'mobile'          => 'mobile',
            'template_value'  => 'contenido de la plantilla',
            'template_var'    => 'variables de plantilla',
            'sign_name'       => 'firma de sms',
            'request_url'     => 'Interfaz de solicitud',
            'request_params'  => 'parámetros de solicitud',
            'response_data'   => 'datos de respuesta',
            'reason'          => 'causa del fracaso',
            'tsc'             => 'tiempo(segundos)',
            'add_time'        => 'añadir tiempo',
            'upd_time'        => 'tiempo de actualización',
        ],
    ],

    // 邮件日志
    'emaillog'               => [
        // 动态表格
        'form_table'                            => [
            'email'           => 'Buzón de recepción',
            'status'          => 'Estado',
            'title'           => 'Título del correo',
            'template_value'  => 'Contenido del correo',
            'template_var'    => 'Variables de correo',
            'reason'          => 'causa del fracaso',
            'smtp_host'       => 'Servidor SMTP',
            'smtp_port'       => 'Puerto SMTP',
            'smtp_name'       => 'Nombre de usuario del buzón',
            'smtp_account'    => 'Correo del remitente',
            'smtp_send_name'  => 'Nombre del remitente',
            'tsc'             => 'tiempo(segundos)',
            'add_time'        => 'añadir tiempo',
            'upd_time'        => 'tiempo de actualización',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'Ps: los no desarrolladores no deben ejecutar ninguna instrucción SQL a voluntad, la operación puede causar la eliminación de toda la base de datos del sistema.',
    ],

    // 应用商店
    'store'                 => [
        'to_store_name'                         => 'Ir a la tienda de aplicaciones para seleccionar plug - ins',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Sistema de gestión de fondo',
        'remove_cache_title'                    => 'Borrar caché',
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Información sobre la configuración de los parámetros de los productos básicos',
        'form_goods_params_copy_no_tips'        => 'Por favor, pegue la información de configuración primero.',
        'form_goods_params_copy_error_tips'     => 'Error de formato de configuración',
        'form_goods_params_type_message'        => 'Por favor, elija el tipo de visualización de los parámetros del producto.',
        'form_goods_params_params_name'         => 'Nombre del parámetro',
        'form_goods_params_params_message'      => 'Por favor, rellene el nombre del parámetro',
        'form_goods_params_value_name'          => 'Valor del parámetro',
        'form_goods_params_value_message'       => 'Por favor, rellene el valor del parámetro',
        'form_goods_params_move_type_tips'      => 'Configuración incorrecta del tipo de operación',
        'form_goods_params_move_top_tips'       => 'Ha llegado a la cima',
        'form_goods_params_move_bottom_tips'    => 'Ha llegado a la parte inferior',
        'form_goods_params_thead_type_title'    => 'Alcance de la exhibición',
        'form_goods_params_thead_name_title'    => 'Nombre del parámetro',
        'form_goods_params_thead_value_title'   => 'Valor del parámetro',
        'form_goods_params_row_add_title'       => 'Añadir una línea',
        'form_goods_params_list_tips'           => [
            '1. todo (se muestra bajo la información básica del producto y los parámetros de detalle)',
            '2. detalles (solo se muestran bajo los parámetros de detalles del producto)',
            '3. básico (solo se muestra bajo la información básica de los productos básicos)',
            '4. la operación rápida eliminará los datos originales y sobrecargará la página para restaurar los datos originales (solo entrará en vigor después de guardar el producto)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => 'Configuración del sistema',
            'item'  => [
                'config_index'                 => 'Configuración del sistema',
                'config_store'                 => 'Información de la tienda',
                'config_save'                  => 'Configuración guardar',
                'index_storeaccountsbind'      => 'Vinculación de la cuenta de la tienda de aplicaciones',
                'index_inspectupgrade'         => 'Inspección de actualización del sistema',
                'index_inspectupgradeconfirm'  => 'Confirmación de la actualización del sistema',
                'index_stats'                  => 'Estadísticas de la página de inicio',
                'index_income'                 => 'Estadísticas de la página de inicio (estadísticas de ingresos)',
                'shortcutmenu_index'           => 'Funciones comunes',
                'shortcutmenu_save'            => 'Añadir / editar funciones comunes',
                'shortcutmenu_sort'            => 'Clasificación de funciones comunes',
                'shortcutmenu_delete'          => 'Eliminación de funciones comunes',
            ]
        ],
        'site_index' => [
            'name'  => 'Configuración del sitio',
            'item'  => [
                'site_index'                  => 'Configuración del sitio',
                'site_save'                   => 'Edición de la configuración del sitio',
                'site_goodssearch'            => 'El sitio establece la búsqueda de productos',
                'layout_layoutindexhomesave'  => 'Gestión del diseño de la página de inicio',
                'sms_index'                   => 'Configuración de SMS',
                'sms_save'                    => 'Configuración y Edición de mensajes cortos',
                'email_index'                 => 'Configuración del buzón',
                'email_save'                  => 'Configuración / edición del buzón',
                'email_emailtest'             => 'Prueba de envío de correo',
                'seo_index'                   => 'Configuración seo',
                'seo_save'                    => 'Edición de la configuración seo',
                'agreement_index'             => 'Gestión de acuerdos',
                'agreement_save'              => 'Edición de la configuración del Protocolo',
            ]
        ],
        'power_index' => [
            'name'  => 'Control de permisos',
            'item'  => [
                'admin_index'        => 'Lista de administradores',
                'admin_saveinfo'     => 'El Administrador agrega / edita la página',
                'admin_save'         => 'Administrador añadir / editar',
                'admin_delete'       => 'Eliminación del Administrador',
                'admin_detail'       => 'Detalles del Administrador',
                'role_index'         => 'Gestión de roles',
                'role_saveinfo'      => 'Grupo de personajes añadir / editar Página',
                'role_save'          => 'Añadir / editar Grupo de personajes',
                'role_delete'        => 'Eliminación de roles',
                'role_statusupdate'  => 'Actualización del Estado del personaje',
                'role_detail'        => 'Detalles del personaje',
                'power_index'        => 'Asignación de permisos',
                'power_save'         => 'Permisos para agregar / editar',
                'power_statusupdate' => 'Actualización del Estado de permisos',
                'power_delete'       => 'Eliminación de permisos',
            ]
        ],
        'user_index' => [
            'name'  => 'Gestión de usuarios',
            'item'  => [
                'user_index'            => 'Lista de usuarios',
                'user_saveinfo'         => 'Usuario editar / agregar Página',
                'user_save'             => 'Usuario añadir / editar',
                'user_delete'           => 'Eliminación del usuario',
                'user_detail'           => 'Detalles del usuario',
                'useraddress_index'     => 'Dirección del usuario',
                'useraddress_saveinfo'  => 'Página de Edición de la dirección del usuario',
                'useraddress_save'      => 'Edición de la dirección del usuario',
                'useraddress_delete'    => 'Eliminación de la dirección del usuario',
                'useraddress_detail'    => 'Detalles de la dirección del usuario',
            ]
        ],
        'goods_index' => [
            'name'  => 'Gestión de productos básicos',
            'item'  => [
                'goods_index'                       => 'Gestión de productos básicos',
                'goods_saveinfo'                    => 'Página de adición / edición de productos',
                'goods_save'                        => 'Añadir / editar productos',
                'goods_delete'                      => 'Eliminación de productos',
                'goods_statusupdate'                => 'Actualización del Estado de los productos básicos',
                'goods_basetemplate'                => 'Obtener la plantilla de base de productos básicos',
                'goods_detail'                      => 'Detalles del producto',
                'goodscategory_index'               => 'Clasificación de mercancías',
                'goodscategory_save'                => 'Añadir / editar clasificación de productos',
                'goodscategory_statusupdate'        => 'Actualización del Estado de clasificación de mercancías',
                'goodscategory_delete'              => 'Eliminación de la clasificación de mercancías',
                'goodsparamstemplate_index'         => 'Parámetros de los productos básicos',
                'goodsparamstemplate_delete'        => 'Eliminación de parámetros de productos básicos',
                'goodsparamstemplate_statusupdate'  => 'Actualización del Estado de los parámetros de los productos básicos',
                'goodsparamstemplate_saveinfo'      => 'Parámetros del producto añadir / editar Página',
                'goodsparamstemplate_save'          => 'Añadir / editar parámetros del producto',
                'goodsparamstemplate_detail'        => 'Detalles de los parámetros de la mercancía',
                'goodsspectemplate_index'           => 'Especificaciones del producto',
                'goodsspectemplate_delete'          => 'Eliminación de las especificaciones de los productos',
                'goodsspectemplate_statusupdate'    => 'Actualización del Estado de las especificaciones de los productos básicos',
                'goodsspectemplate_saveinfo'        => 'Página de adición / edición de especificaciones de productos',
                'goodsspectemplate_save'            => 'Añadir / editar especificaciones de productos',
                'goodsspectemplate_detail'          => 'Detalles de las especificaciones del producto',
                'goodscomments_detail'              => 'Detalles de la revisión de productos',
                'goodscomments_index'               => 'Comentarios sobre productos básicos',
                'goodscomments_reply'               => 'Respuesta al comentario de la mercancía',
                'goodscomments_delete'              => 'Eliminar comentarios de productos',
                'goodscomments_statusupdate'        => 'Actualización del Estado de comentarios de productos básicos',
                'goodscomments_saveinfo'            => 'Comentarios de productos añadir / editar Página',
                'goodscomments_save'                => 'Añadir / editar comentarios de productos',
                'goodsbrowse_index'                 => 'Navegación por productos',
                'goodsbrowse_delete'                => 'Navegación y eliminación de productos',
                'goodsbrowse_detail'                => 'Detalles de la navegación del producto',
                'goodsfavor_index'                  => 'Colección de productos básicos',
                'goodsfavor_delete'                 => 'Eliminación de la colección de productos',
                'goodsfavor_detail'                 => 'Detalles de la colección de productos',
                'goodscart_index'                   => 'Carrito de compras de productos básicos',
                'goodscart_delete'                  => 'Eliminación del carrito de la compra de productos básicos',
                'goodscart_detail'                  => 'Detalles del carrito de la compra de productos básicos',
            ]
        ],
        'order_index' => [
            'name'  => 'Gestión de pedidos',
            'item'  => [
                'order_index'             => 'Gestión de pedidos',
                'order_delete'            => 'Eliminación de pedidos',
                'order_cancel'            => 'Cancelación de pedidos',
                'order_delivery'          => 'Envío de pedidos',
                'order_collect'           => 'Recepción de pedidos',
                'order_pay'               => 'Pago de pedidos',
                'order_confirm'           => 'Confirmación del pedido',
                'order_detail'            => 'Detalles del pedido',
                'order_deliveryinfo'      => 'Página de envío de pedidos',
                'order_serviceinfo'       => 'Página de servicio de pedidos',
                'orderaftersale_index'    => 'Posventa de pedidos',
                'orderaftersale_delete'   => 'Eliminación post - venta de pedidos',
                'orderaftersale_cancel'   => 'Cancelación posventa de pedidos',
                'orderaftersale_audit'    => 'Revisión post - venta de pedidos',
                'orderaftersale_confirm'  => 'Confirmación post - venta del pedido',
                'orderaftersale_refuse'   => 'Rechazo posventa de pedidos',
                'orderaftersale_detail'   => 'Detalles de la posventa del pedido',
            ]
        ],
        'navigation_index' => [
            'name'  => 'Gestión del sitio web',
            'item'  => [
                'navigation_index'                 => 'Gestión de la navegación',
                'navigation_save'                  => 'Navegación añadir / editar',
                'navigation_delete'                => 'Eliminar navegación',
                'navigation_statusupdate'          => 'Actualización del Estado de navegación',
                'customview_index'                 => 'Página personalizada',
                'customview_saveinfo'              => 'Página personalizada para agregar / editar Página',
                'customview_save'                  => 'Añadir / editar página personalizada',
                'customview_delete'                => 'Eliminar página personalizada',
                'customview_statusupdate'          => 'Actualización del Estado de la página personalizada',
                'customview_detail'                => 'Detalles de la página personalizada',
                'link_index'                       => 'Enlace amistoso',
                'link_saveinfo'                    => 'Enlace de amistad para agregar / editar Página',
                'link_save'                        => 'Añadir / editar enlaces de amistad',
                'link_delete'                      => 'Eliminar enlaces de amistad',
                'link_statusupdate'                => 'Actualización del Estado del enlace de amistad',
                'link_detail'                      => 'Detalles del enlace amistoso',
                'themeadmin_index'                 => 'Gestión temática',
                'themeadmin_save'                  => 'Gestión temática añadir / editar',
                'themeadmin_upload'                => 'Instalación de carga de temas',
                'themeadmin_delete'                => 'Eliminación del tema',
                'themeadmin_download'              => 'Descarga del tema',
                'themeadmin_market'                => 'Plantilla temática Mercado',
                'themeadmin_storeuploadinfo'       => 'Tema cargar Página',
                'themeadmin_storeupload'           => 'Carga del tema',
                'themedata_index'                  => 'Datos temáticos',
                'themedata_saveinfo'               => 'Página de adición / edición de datos temáticos',
                'themedata_save'                   => 'Añadir / editar datos temáticos',
                'themedata_upload'                 => 'Carga de datos temáticos',
                'themedata_delete'                 => 'Eliminación de datos temáticos',
                'themedata_download'               => 'Descarga de datos temáticos',
                'slide_index'                      => 'Rotación de la página de inicio',
                'slide_saveinfo'                   => 'Página de adición / edición de la rotación',
                'slide_save'                       => 'Añadir / editar a la rotación',
                'slide_statusupdate'               => 'Actualización del Estado de rotación',
                'slide_delete'                     => 'Eliminación de la rotación',
                'slide_detail'                     => 'Detalles de la rotación',
                'screeningprice_index'             => 'Precio de selección',
                'screeningprice_save'              => 'Filtrar precio añadir / editar',
                'screeningprice_delete'            => 'Eliminar el precio del filtro',
                'region_index'                     => 'Gestión regional',
                'region_save'                      => 'Área añadir / editar',
                'region_statusupdate'              => 'Actualización del Estado regional',
                'region_delete'                    => 'Área eliminada',
                'region_codedata'                  => 'Obtención de datos de numeración regional',
                'express_index'                    => 'Gestión de mensajería',
                'express_save'                     => 'Añadir / editar mensajería',
                'express_delete'                   => 'Eliminación de mensajería',
                'payment_index'                    => 'Método de pago',
                'payment_saveinfo'                 => 'Página de instalación / edición del método de pago',
                'payment_save'                     => 'Instalación / edición del método de pago',
                'payment_delete'                   => 'Eliminación del método de pago',
                'payment_install'                  => 'Instalación del método de pago',
                'payment_statusupdate'             => 'Actualización del Estado del método de pago',
                'payment_uninstall'                => 'Descarga del método de pago',
                'payment_upload'                   => 'Método de pago cargado',
                'payment_market'                   => 'Mercado de complementos de pago',
                'quicknav_index'                   => 'Navegación rápida',
                'quicknav_saveinfo'                => 'Navegación rápida para agregar / editar páginas',
                'quicknav_save'                    => 'Navegación rápida añadir / editar',
                'quicknav_statusupdate'            => 'Actualización del Estado de navegación rápida',
                'quicknav_delete'                  => 'Navegación rápida eliminada',
                'quicknav_detail'                  => 'Detalles de navegación rápida',
                'design_index'                     => 'Diseño de página',
                'design_saveinfo'                  => 'Diseño de página para agregar / editar páginas',
                'design_save'                      => 'Diseño de página añadir / editar',
                'design_statusupdate'              => 'Actualización del Estado de diseño de la página',
                'design_upload'                    => 'Importación de diseño de página',
                'design_download'                  => 'Descarga de diseño de página',
                'design_sync'                      => 'Página de inicio de sincronización de diseño de página',
                'design_delete'                    => 'Diseño de página eliminado',
                'design_market'                    => 'Plantilla de diseño de página Mercado',
                'attachment_index'                 => 'Gestión de Anexos',
                'attachment_detail'                => 'Detalles de la gestión de Anexos',
                'attachment_saveinfo'              => 'Gestión de accesorios añadir / editar Página',
                'attachment_save'                  => 'Gestión de anexos añadir / editar',
                'attachment_delete'                => 'Eliminación de la gestión de Anexos',
                'attachmentcategory_index'         => 'Clasificación de los anexos',
                'attachmentcategory_save'          => 'Clasificación de anexos añadir / editar',
                'attachmentcategory_statusupdate'  => 'Actualización del Estado del Anexo',
                'attachmentcategory_delete'        => 'Eliminación de la clasificación de Anexos',
            ]
        ],
        'brand_index' => [
            'name'  => 'Gestión de la marca',
            'item'  => [
                'brand_index'           => 'Gestión de la marca',
                'brand_saveinfo'        => 'Página de adición / edición de marca',
                'brand_save'            => 'Añadir / editar marca',
                'brand_statusupdate'    => 'Actualización del Estado de la marca',
                'brand_delete'          => 'Eliminación de la marca',
                'brand_detail'          => 'Detalles de la marca',
                'brandcategory_index'   => 'Clasificación de la marca',
                'brandcategory_save'    => 'Clasificación de marca añadir / editar',
                'brandcategory_delete'  => 'Eliminación de la clasificación de la marca',
            ]
        ],
        'warehouse_index' => [
            'name'  => 'Gestión de almacenes',
            'item'  => [
                'warehouse_index'               => 'Gestión de almacenes',
                'warehouse_saveinfo'            => 'Página de adición / edición del almacén',
                'warehouse_save'                => 'Añadir / editar almacén',
                'warehouse_delete'              => 'Eliminación del almacén',
                'warehouse_statusupdate'        => 'Actualización del Estado del almacén',
                'warehouse_detail'              => 'Detalles del almacén',
                'warehousegoods_index'          => 'Gestión de productos básicos del almacén',
                'warehousegoods_detail'         => 'Detalles de los productos del almacén',
                'warehousegoods_delete'         => 'Eliminación de productos del almacén',
                'warehousegoods_statusupdate'   => 'Actualización del Estado de los productos del almacén',
                'warehousegoods_goodssearch'    => 'Búsqueda de productos del almacén',
                'warehousegoods_goodsadd'       => 'Búsqueda y adición de productos del almacén',
                'warehousegoods_goodsdel'       => 'Búsqueda y eliminación de productos del almacén',
                'warehousegoods_inventoryinfo'  => 'Página de Edición de inventario de productos del almacén',
                'warehousegoods_inventorysave'  => 'Edición de inventario de productos del almacén',
            ]
        ],
        'app_index' => [
            'name'  => 'Gestión de teléfonos móviles',
            'item'  => [
                'appconfig_index'                  => 'Configuración básica',
                'appconfig_save'                   => 'Guardar configuración básica',
                'appmini_index'                    => 'Lista de applets',
                'appmini_created'                  => 'Generación de pequeños paquetes',
                'appmini_delete'                   => 'Eliminación de paquetes pequeños',
                'appmini_themeupload'              => 'Carga del tema del applet',
                'appmini_themesave'                => 'Cambio de tema del applet',
                'appmini_themedelete'              => 'Cambio de tema del applet',
                'appmini_themedownload'            => 'Descarga del tema del applet',
                'appmini_config'                   => 'Configuración del applet',
                'appmini_save'                     => 'Guardar la configuración del applet',
                'diy_index'                        => 'Decoración de bricolaje',
                'diy_saveinfo'                     => 'Página añadir / editar decoración de bricolaje',
                'diy_save'                         => 'Añadir / editar decoración de bricolaje',
                'diy_statusupdate'                 => 'Actualización del Estado de decoración de bricolaje',
                'diy_delete'                       => 'Decoración de bricolaje eliminada',
                'diy_download'                     => 'Exportación de decoración de bricolaje',
                'diy_upload'                       => 'Importación de decoración de bricolaje',
                'diy_detail'                       => 'Detalles de la decoración de bricolaje',
                'diy_preview'                      => 'Vista previa de la decoración de bricolaje',
                'diy_market'                       => 'Plantilla de decoración de bricolaje Mercado',
                'diy_apptabbar'                    => 'Menú inferior de la decoración de bricolaje',
                'diy_storeuploadinfo'              => 'Página de carga de decoración de bricolaje',
                'diy_storeupload'                  => 'Carga de decoración de bricolaje',
                'diyapi_init'                      => 'Decoración de bricolaje - iniciación pública',
                'diyapi_attachmentcategory'        => 'Decoración de bricolaje - Clasificación de accesorios',
                'diyapi_attachmentlist'            => 'Decoración de bricolaje - Lista de accesorios',
                'diyapi_attachmentsave'            => 'Decoración de bricolaje - conservación de accesorios',
                'diyapi_attachmentdelete'          => 'Decoración de bricolaje - Eliminación de accesorios',
                'diyapi_attachmentupload'          => 'Decoración de bricolaje - carga de accesorios',
                'diyapi_attachmentcatch'           => 'Decoración de bricolaje - descarga remota de accesorios',
                'diyapi_attachmentscanuploaddata'  => 'Decoración de bricolaje - Código de escaneo de accesorios para cargar datos',
                'diyapi_attachmentmovecategory'    => 'Decoración de bricolaje - Clasificación móvil de accesorios',
                'diyapi_attachmentcategorysave'    => 'Decoración de bricolaje - conservación clasificada de accesorios',
                'diyapi_attachmentcategorydelete'  => 'Decoración de bricolaje - Eliminación de la clasificación de accesorios',
                'diyapi_goodslist'                 => 'Decoración de bricolaje - Lista de productos',
                'diyapi_customviewlist'            => 'Decoración de bricolaje - Lista de páginas personalizadas',
                'diyapi_designlist'                => 'Decoración de bricolaje - Lista de diseños de páginas',
                'diyapi_articlelist'               => 'Decoración de bricolaje - Lista de artículos',
                'diyapi_brandlist'                 => 'Decoración de bricolaje - Lista de marcas',
                'diyapi_diylist'                   => 'Decoración de bricolaje - Lista de decoración de bricolaje',
                'diyapi_diydetail'                 => 'Decoración de bricolaje - detalles de la decoración de bricolaje',
                'diyapi_diysave'                   => 'Decoración de bricolaje - conservación de la decoración de bricolaje',
                'diyapi_diyupload'                 => 'Decoración de bricolaje - importación de decoración de bricolaje',
                'diyapi_diydownload'               => 'Decoración de bricolaje - exportación de decoración de bricolaje',
                'diyapi_diyinstall'                => 'Decoración de bricolaje - instalación de ENCOFRADOS de decoración de bricolaje',
                'diyapi_diymarket'                 => 'Decoración de bricolaje - mercado de ENCOFRADOS de decoración de bricolaje',
                'diyapi_goodsappointdata'          => 'Decoración de bricolaje - datos de designación de productos',
                'diyapi_goodsautodata'             => 'Decoración de bricolaje - datos automáticos de la mercancía',
                'diyapi_articleappointdata'        => 'Decoración de bricolaje - datos especificados en el artículo',
                'diyapi_articleautodata'           => 'Decoración de bricolaje - datos automáticos del artículo',
                'diyapi_brandappointdata'          => 'Decoración de bricolaje - datos de designación de marca',
                'diyapi_brandautodata'             => 'Decoración de bricolaje - datos automáticos de la marca',
                'diyapi_userheaddata'              => 'Decoración de bricolaje - datos de la cabeza del usuario',
                'diyapi_custominit'                => 'Decoración de bricolaje - iniciación personalizada',
                'apphomenav_index'                 => 'Navegación en la página de inicio',
                'apphomenav_saveinfo'              => 'Página de inicio navegación añadir / editar Página',
                'apphomenav_save'                  => 'Navegación de página de inicio añadir / editar',
                'apphomenav_statusupdate'          => 'Actualización del Estado de navegación de la página de inicio',
                'apphomenav_delete'                => 'Eliminar la navegación de la página de inicio',
                'apphomenav_detail'                => 'Detalles de la navegación en la página de inicio',
                'appcenternav_index'               => 'Navegación del Centro de usuarios',
                'appcenternav_saveinfo'            => 'Navegación del Centro de usuarios para agregar / editar páginas',
                'appcenternav_save'                => 'Navegación del Centro de usuarios añadir / editar',
                'appcenternav_statusupdate'        => 'Actualización del Estado de navegación del Centro de usuarios',
                'appcenternav_delete'              => 'Eliminación de navegación del Centro de usuarios',
                'appcenternav_detail'              => 'Detalles de navegación del Centro de usuarios',
            ]
        ],
        'article_index' => [
            'name'  => 'Gestión de artículos',
            'item'  => [
                'article_index'           => 'Gestión de artículos',
                'article_saveinfo'        => 'Artículo añadir / editar Página',
                'article_save'            => 'Artículo añadir / editar',
                'article_delete'          => 'Artículo eliminado',
                'article_statusupdate'    => 'Actualización del Estado del artículo',
                'article_detail'          => 'Detalles del artículo',
                'articlecategory_index'   => 'Clasificación de artículos',
                'articlecategory_save'    => 'Clasificación de artículos editar / agregar',
                'articlecategory_delete'  => 'Eliminación de la clasificación de artículos',
            ]
        ],
        'data_index' => [
            'name'  => 'Gestión de datos',
            'item'  => [
                'message_index'         => 'Gestión de mensajes',
                'message_delete'        => 'Eliminación de mensajes',
                'message_detail'        => 'Detalles del mensaje',
                'paylog_index'          => 'Diario de pagos',
                'paylog_detail'         => 'Detalles del registro de pagos',
                'paylog_close'          => 'Cierre del Diario de pagos',
                'payrequestlog_index'   => 'Lista de registros de solicitudes de pago',
                'payrequestlog_detail'  => 'Detalles del registro de solicitudes de pago',
                'refundlog_index'       => 'Registro de reembolso',
                'refundlog_detail'      => 'Detalles del registro de reembolso',
                'integrallog_index'     => 'Registro de puntos',
                'integrallog_detail'    => 'Detalles del registro de puntos',
                'smslog_index'          => 'Registro de mensajes cortos',
                'smslog_detail'         => 'Detalles del registro de mensajes cortos',
                'smslog_delete'         => 'Eliminación del registro de mensajes cortos',
                'smslog_alldelete'      => 'Todos los registros de mensajes cortos se eliminan',
                'emaillog_index'        => 'Registro de correo',
                'emaillog_detail'       => 'Detalles del registro de correo',
                'emaillog_delete'       => 'Eliminación del registro de correo',
                'emaillog_alldelete'    => 'Eliminar todos los registros de correo',
                'errorlog_index'        => 'Registro de errores',
                'errorlog_detail'       => 'Detalles del registro de errores',
                'errorlog_delete'       => 'Eliminación del registro de errores',
                'errorlog_alldelete'    => 'Todos los registros de errores se eliminan',
            ]
        ],
        'store_index' => [
            'name'  => 'Centro de aplicaciones',
            'item'  => [
                'pluginsadmin_index'            => 'Gestión de aplicaciones',
                'plugins_index'                 => 'Gestión de llamadas de aplicaciones',
                'pluginsadmin_saveinfo'         => 'Aplicación añadir / editar Página',
                'pluginsadmin_save'             => 'Añadir / editar aplicación',
                'pluginsadmin_statusupdate'     => 'Actualización del Estado de aplicación',
                'pluginsadmin_delete'           => 'Eliminar aplicación',
                'pluginsadmin_upload'           => 'Carga de la aplicación',
                'pluginsadmin_download'         => 'Paquete de aplicaciones',
                'pluginsadmin_install'          => 'Instalación de aplicaciones',
                'pluginsadmin_uninstall'        => 'Desinstalación de aplicaciones',
                'pluginsadmin_sortsave'         => 'Ordenar y guardar aplicaciones',
                'pluginsadmin_market'           => 'Mercado de plug - INS de aplicaciones',
                'store_index'                   => 'Tienda de aplicaciones',
                'packageinstall_index'          => 'Página de instalación de paquetes de software',
                'packageinstall_install'        => 'Instalación de paquetes de software',
                'packageupgrade_upgrade'        => 'Actualización de paquetes de software',
                'pluginscategory_index'         => 'Clasificación de aplicaciones',
                'pluginscategory_save'          => 'Añadir / editar clasificación de aplicaciones',
                'pluginscategory_statusupdate'  => 'Actualización del Estado de clasificación de la aplicación',
                'pluginscategory_delete'        => 'Eliminar la clasificación de la aplicación',
                'store_market'                  => 'Mercado de aplicaciones',
            ]
        ],
        'tool_index' => [
            'name'  => 'Herramientas',
                'item'                  => [
                'cache_index'           => 'Gestión de caché',
                'cache_statusupdate'    => 'Actualización de la caché del sitio',
                'cache_templateupdate'  => 'Actualización de la caché de plantillas',
                'cache_moduleupdate'    => 'Actualización de la caché del módulo',
                'cache_logdelete'       => 'Eliminación de registros',
                'sqlconsole_index'      => 'Consola SQL',
                'sqlconsole_implement'  => 'Ejecución de SQL',
            ]
        ],
    ],
];
?>