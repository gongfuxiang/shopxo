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
namespace app\service;

use think\facade\Db;
use app\service\MultilingualService;
use app\service\ResourcesService;

/**
 * 多语言数据服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-08-31
 * @desc    业务数据多语言（后台输入框弹窗录入、前台按当前语言替换输出）
 */
class I18nService
{
	// 数据静态缓存（同请求内避免重复查询）
	private static $values_static = [];

	/**
	 * 可翻译字段配置
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @desc    type（text普通文本、editor富文本、dynamic动态列表（字段_索引存储，索引为提交顺序））；系统表写在本方法，插件表通过钩子 plugins_service_i18n_fields_config 登记
	 * @param   [string]          $table_name [业务表名（空则全部）]
	 */
	public static function FieldsConfig($table_name = '')
	{
		$config = [
			'goods' => [
				'title'                   => 'text',
				'simple_desc'             => 'text',
				'spec_desc'               => 'text',
				'approval_number'         => 'text',
				'batch_number'            => 'text',
				'produce_company'         => 'text',
				'inventory_unit'          => 'text',
				'content_web'             => 'editor',
				'use_guide'               => 'editor',
				'fictitious_goods_value'  => 'editor',
				'seo_title'               => 'text',
				'seo_keywords'            => 'text',
				'seo_desc'                => 'text',
				'content_app_text'        => 'dynamic',
				'parameters_name'         => 'dynamic',
				'parameters_value'        => 'dynamic',
				'spec_name'               => 'dynamic',
				'spec_value'              => 'dynamic',
			],
			'goods_category' => [
				'name'          => 'text',
				'vice_name'     => 'text',
				'describe'      => 'text',
				'seo_title'     => 'text',
				'seo_keywords'  => 'text',
				'seo_desc'      => 'text',
			],
			'brand' => [
				'name'          => 'text',
				'describe'      => 'text',
				'seo_title'     => 'text',
				'seo_keywords'  => 'text',
				'seo_desc'      => 'text',
			],
			'brand_category' => [
				'name'          => 'text',
			],
			'warehouse' => [
				'name'          => 'text',
				'alias'         => 'text',
				'address'       => 'text',
			],
			'app_center_nav' => [
				'name'          => 'text',
				'desc'          => 'text',
			],
			'app_home_nav' => [
				'name'          => 'text',
			],
			'theme_data' => [
				'name'          => 'text',
				// 业务文本/自定义数据：内容键 theme_data_text_{md5} / theme_data_custom_*_{md5}，business_id 归属主题数据行 id
				'theme_data_text'         => 'dynamic',
				'theme_data_custom_name'  => 'dynamic',
				'theme_data_custom_value' => 'dynamic',
			],
			'goods_spec_template' => [
				'spec_name'        => 'dynamic',
				'spec_value'       => 'dynamic',
			],
			'goods_params_template' => [
				'name'             => 'text',
				'parameters_name'  => 'dynamic',
				'parameters_value' => 'dynamic',
			],
			'design' => [
				'name'          => 'text',
				'seo_title'     => 'text',
				'seo_keywords'  => 'text',
				'seo_desc'      => 'text',
			],
			'navigation' => [
				'name'          => 'text',
				'value'         => 'text',
			],
			'customview' => [
				'name'          => 'text',
			],
			'link' => [
				'name'          => 'text',
				'describe'      => 'text',
			],
			'screening_price' => [
				'name'          => 'text',
			],
			'slider' => [
				'name'          => 'text',
				'describe'      => 'text',
			],
			'region' => [
				'name'          => 'text',
			],
			'express' => [
				'name'          => 'text',
			],
			'order_express' => [
				// 发货备注：字段键 note_{快递id}_{单号md5}，business_id 归属订单 id
				'note'          => 'dynamic',
			],
			'payment' => [
				'name'          => 'text',
			],
			'quick_nav' => [
				'name'          => 'text',
			],
			'article_category' => [
				'name'          => 'text',
			],
			'article' => [
				'title'         => 'text',
				'describe'      => 'text',
				'content'       => 'editor',
				'seo_title'     => 'text',
				'seo_keywords'  => 'text',
				'seo_desc'      => 'text',
			],

			// 配置键值型（business_id=0、字段名为配置only_tag）
			'config' => [
				// 协议管理（富文本）
				'common_agreement_userregister'                    => 'editor',
				'common_agreement_userprivacy'                     => 'editor',
				'common_agreement_userlogout'                      => 'editor',
				// 站点基础
				'home_site_name'                                   => 'text',
				'home_site_close_reason'                           => 'text',
				'home_footer_info'                                 => 'editor',
				// SEO设置
				'home_seo_site_title'                              => 'text',
				'home_seo_site_keywords'                           => 'text',
				'home_seo_site_description'                        => 'text',
				// 展览模式/虚拟信息
				'common_exhibition_mode_hide_price_text'           => 'text',
				'common_is_exhibition_mode_btn_text'               => 'text',
				'common_site_fictitious_title'                     => 'text',
				'common_site_fictitious_use_tips'                  => 'text',
				// 商店信息
				'common_customer_store_address'                    => 'text',
				'common_customer_store_describe'                   => 'text',
				// 售后设置
				'home_order_aftersale_return_goods_address'        => 'text',
				'home_order_aftersale_return_only_money_reason'    => 'text',
				'home_order_aftersale_return_money_goods_reason'   => 'text',
				'home_order_aftersale_return_goods_contacts_name'  => 'text',
				// 网站设置
				'home_navigation_main_quick_name'                  => 'text',
				'home_index_floor_top_right_keywords'              => 'dynamic',
				// 手机配置（公告）
				'common_shop_notice'                               => 'text',
				'common_user_center_notice'                        => 'text',
				// 小程序配置（名称/描述/隐私弹窗说明）
				'common_app_mini_weixin_title'                     => 'text',
				'common_app_mini_weixin_describe'                  => 'text',
				'common_app_mini_weixin_privacy_content'           => 'text',
				'common_app_mini_alipay_title'                     => 'text',
				'common_app_mini_alipay_describe'                  => 'text',
				'common_app_mini_baidu_title'                      => 'text',
				'common_app_mini_baidu_describe'                   => 'text',
				'common_app_mini_toutiao_title'                    => 'text',
				'common_app_mini_toutiao_describe'                 => 'text',
				'common_app_mini_qq_title'                         => 'text',
				'common_app_mini_qq_describe'                      => 'text',
				'common_app_mini_kuaishou_title'                   => 'text',
				'common_app_mini_kuaishou_describe'                => 'text',
				// 备案信息（弹窗行编辑、内容寻址键 字段_内容md5）
				'home_site_filing_name'                            => 'dynamic',
				'home_site_filing_show_name'                       => 'dynamic',
				// 自提地址（弹窗行编辑、内容寻址键 字段_内容md5）
				'common_self_extraction_address_alias'             => 'dynamic',
				'common_self_extraction_address_name'              => 'dynamic',
				'common_self_extraction_address_address'           => 'dynamic',
				// 邮箱消息模板
				'admin_email_login_template'                       => 'editor',
				'common_email_currency_template'                   => 'editor',
				'home_email_login_template'                        => 'editor',
				'home_email_user_reg_template'                     => 'editor',
				'home_email_user_forget_pwd_template'              => 'editor',
				'home_email_user_email_binding_template'           => 'editor',
				// 短信消息模板
				'admin_sms_login_template'                         => 'text',
				'common_sms_currency_template'                     => 'text',
				'home_sms_login_template'                          => 'text',
				'home_sms_user_reg_template'                       => 'text',
				'home_sms_user_forget_pwd_template'                => 'text',
				'home_sms_user_mobile_binding_template'            => 'text',
			],
		];
		$ext = self::PluginI18nExt();
		foreach($ext['tables'] as $table=>$fields)
		{
			$config[$table] = $fields;
		}
		$config['plugins_config'] = $ext['plugins_config'];
		return empty($table_name) ? $config : (isset($config[$table_name]) ? $config[$table_name] : []);
	}

	/**
	 * 插件多语言扩展配置
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-09-05
	 * @desc    钩子 plugins_service_i18n_fields_config；插件仅登记 plugins_* 业务表与 plugins_config 自身字段
	 */
	private static function PluginI18nExt()
	{
		static $result = null;
		static $loading = false;
		$empty = [
			'tables'          => [],
			'plugins_config'  => [],
			'array_struct'    => [],
			'nested_map'      => [],
			'tagsinput'       => [],
		];
		if($result !== null)
		{
			return $result;
		}
		if($loading)
		{
			return $empty;
		}
		$loading = true;
		$data = $empty;
		$hook_name = 'plugins_service_i18n_fields_config';
		MyEventTrigger($hook_name, [
			'hook_name'     => $hook_name,
			'is_backend'    => true,
			'data'          => &$data,
		]);
		$loading = false;
		$result = self::SanitizePluginI18nExt(is_array($data) ? $data : $empty);
		return $result;
	}

	/**
	 * 清洗插件多语言钩子数据（禁止覆盖系统表、字段类型仅 text/editor/dynamic）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-09-05
	 * @param   [array]          $data [钩子合并结果]
	 */
	private static function SanitizePluginI18nExt($data)
	{
		$allow_type = ['text'=>'text', 'editor'=>'editor', 'dynamic'=>'dynamic'];
		$out = [
			'tables'          => [],
			'plugins_config'  => [],
			'array_struct'    => [],
			'nested_map'      => [],
			'tagsinput'       => [],
		];
		if(!empty($data['tables']) && is_array($data['tables']))
		{
			foreach($data['tables'] as $table=>$fields)
			{
				if(!is_string($table) || strpos($table, 'plugins_') !== 0 || $table == 'plugins_config' || !is_array($fields))
				{
					continue;
				}
				$clean = [];
				foreach($fields as $field=>$type)
				{
					if(is_string($field) && isset($allow_type[$type]))
					{
						$clean[$field] = $allow_type[$type];
					}
				}
				if(!empty($clean))
				{
					$out['tables'][$table] = $clean;
				}
			}
		}
		if(!empty($data['plugins_config']) && is_array($data['plugins_config']))
		{
			foreach($data['plugins_config'] as $plugin=>$fields)
			{
				if(!is_string($plugin) || $plugin === '' || !is_array($fields))
				{
					continue;
				}
				$clean = [];
				foreach($fields as $field=>$type)
				{
					if(is_string($field) && isset($allow_type[$type]))
					{
						$clean[$field] = $allow_type[$type];
					}
				}
				if(!empty($clean))
				{
					$out['plugins_config'][$plugin] = $clean;
				}
			}
		}
		if(!empty($data['array_struct']) && is_array($data['array_struct']))
		{
			foreach($data['array_struct'] as $plugin=>$struct)
			{
				if(!is_string($plugin) || $plugin === '' || !is_array($struct))
				{
					continue;
				}
				$clean = [];
				foreach($struct as $key=>$subs)
				{
					if(!is_string($key))
					{
						continue;
					}
					$sub_clean = [];
					if(is_array($subs))
					{
						foreach($subs as $sf)
						{
							if(is_string($sf) && $sf !== '')
							{
								$sub_clean[] = $sf;
							}
						}
					}
					$clean[$key] = $sub_clean;
				}
				if(!empty($clean))
				{
					$out['array_struct'][$plugin] = $clean;
				}
			}
		}
		if(!empty($data['nested_map']) && is_array($data['nested_map']))
		{
			foreach($data['nested_map'] as $table=>$lists)
			{
				if(!is_string($table) || strpos($table, 'plugins_') !== 0 || !is_array($lists))
				{
					continue;
				}
				$clean = [];
				foreach($lists as $list_key=>$subs)
				{
					if(!is_string($list_key) || !is_array($subs))
					{
						continue;
					}
					$sub_clean = [];
					foreach($subs as $sub=>$prefix)
					{
						if(is_string($sub) && is_string($prefix) && $prefix !== '')
						{
							$sub_clean[$sub] = $prefix;
						}
					}
					if(!empty($sub_clean))
					{
						$clean[$list_key] = $sub_clean;
					}
				}
				if(!empty($clean))
				{
					$out['nested_map'][$table] = $clean;
				}
			}
		}
		if(!empty($data['tagsinput']) && is_array($data['tagsinput']))
		{
			foreach($data['tagsinput'] as $table=>$fields)
			{
				if(!is_string($table) || strpos($table, 'plugins_') !== 0 || !is_array($fields))
				{
					continue;
				}
				$clean = [];
				foreach($fields as $field)
				{
					if(is_string($field) && $field !== '')
					{
						$clean[] = $field;
					}
				}
				if(!empty($clean))
				{
					$out['tagsinput'][$table] = $clean;
				}
			}
		}
		return $out;
	}

	/**
	 * 插件配置数组结构（内容寻址替换）
	 * 有子字段：行列表逐项替换，存储键=插件名_配置键_子字段_内容md5
	 * 无子字段：join 后整块替换，存储键=插件名_配置键_内容md5
	 */
	private static function PluginsConfigArrayStruct()
	{
		$ext = self::PluginI18nExt();
		return empty($ext['array_struct']) ? [] : $ext['array_struct'];
	}

	/**
	 * 行内嵌套内容寻址（list_key => [子字段 => 存储字段前缀]）
	 */
	private static function NestedContentReplaceMap()
	{
		$ext = self::PluginI18nExt();
		return empty($ext['nested_map']) ? [] : $ext['nested_map'];
	}

	/**
	 * tagsinput 逗号多值字段（按原文逐项 字段_内容md5 替换）
	 */
	private static function TagsinputReplaceMap()
	{
		$ext = self::PluginI18nExt();
		return empty($ext['tagsinput']) ? [] : $ext['tagsinput'];
	}

	/**
	 * 解析提交字段对应的白名单类型
	 * @param   [string]          $plugins [plugins_config 时传入插件标识]
	 */
	private static function ResolveFieldType($table_name, $field, $fields_config, $plugins = '')
	{
		if($table_name == 'plugins_config')
		{
			return self::PluginsConfigFieldType($field, $plugins);
		}
		if(array_key_exists($field, $fields_config))
		{
			return $fields_config[$field];
		}
		if(self::IsDynamicField($field, $fields_config))
		{
			return 'dynamic';
		}
		return null;
	}

	/**
	 * 多语言组件JS配置（前后台通用、注入window.$I18nConfig）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date     2026-08-31
	 * @param   [string]          $type [模块类型（admin后台、index用户端）]
	 * @return  [string|null]          [json配置字符串、未启用多语言返回null]
	 */
	public static function JsConfig($type)
	{
		$language_list = self::AdminLanguageList();
		if(empty($language_list))
		{
			return null;
		}
		return json_encode([
			'language_list'  => $language_list,
			'default_tips'   => MyLang('common_service.i18n.default_tips'),
			'url'            => MyUrl($type.'/i18n/index'),
			'lang'           => [
				'popup_title'       => MyLang('common_service.i18n.popup_title'),
				'default_tips'      => MyLang('common_service.i18n.default_tips'),
				'save_tips'         => MyLang('common_service.i18n.save_tips'),
				'editor_tips'       => MyLang('common_service.i18n.editor_tips'),
				'confirm_title'     => MyLang('confirm_title'),
				'cancel_title'      => MyLang('cancel_title'),
				'load_fail_tips'    => MyLang('common_service.i18n.load_fail_tips'),
				'loading_tips'      => MyLang('common_service.i18n.loading_tips'),
			],
		], JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 后台弹窗可选语言列表（网站设置-扩展启用的语言、去除默认语言）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public static function AdminLanguageList()
	{
		static $result = null;
		if(is_null($result))
		{
			$result = [];
			$list = MultilingualService::MultilingualCanChooseList();
			if(!empty($list) && is_array($list))
			{
				$default_lang = self::DefaultLang();
				foreach($list as $lang=>$item)
				{
					if($lang != $default_lang)
					{
						$result[] = [
							'code'  => $lang,
							'name'  => isset($item['name']) ? $item['name'] : $lang,
							'icon'  => isset($item['icon']) ? $item['icon'] : '',
						];
					}
				}
			}
		}
		return $result;
	}

	/**
     * 默认语言
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-31
     */
    public static function DefaultLang()
    {
        return MyConfig('lang.default_lang');
    }

	/**
	 * 当前语言
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public static function CurrentLang()
	{
		static $lang = null;
		static $detecting = false;
		static $lang_for_runtime = null;

		// 检测链内部重入（语言检测依赖的配置读取会再进MyC）直接返回默认、阻断递归
		if($detecting)
		{
			return self::DefaultLang();
		}

		// Socket/CLI 运行时语言：RequestController 为空时也必须可用，且切换语言时清缓存
		$runtime = MultilingualService::GetRuntimeMultilingualValue();
		if($runtime !== '')
		{
			if($lang_for_runtime !== $runtime)
			{
				$lang = $runtime;
				$lang_for_runtime = $runtime;
			}
			return $lang;
		}

		// 控制器执行前不检测（session等组件未就绪、早期检测会误判默认语言钉死整个请求）
		// 控制器执行期session必已就绪、检测一次即缓存（含默认语言、结果可信、避免重复检测开销）
		if(RequestController() === '')
		{
			return self::DefaultLang();
		}

		if(empty($lang) || $lang_for_runtime !== null)
		{
			// 离开 runtime 后重新检测
			$lang_for_runtime = null;
			$detecting = true;
			try
			{
				$lang = MultilingualService::GetUserMultilingualValue();
			} finally {
				$detecting = false;
			}
			if(empty($lang))
			{
				return self::DefaultLang();
			}
		}
		return $lang;
	}

	/**
	 * 是否需要替换数据（默认仅前台和api模块、且非默认语言；可强制跳过模块限制）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [boolean]         $is_force [是否强制（跳过模块限制，仍要求非默认语言）]
	 */
	public static function IsHandle($is_force = false)
	{
		// 语言实时判断（早期调用session尚未初始化时检测为空、后续调用会得到正确值、不能缓存）
		$lang = self::CurrentLang();
		if(empty($lang) || $lang == self::DefaultLang())
		{
			return false;
		}

		// 强制场景（如后台查看物流展示端文案）仅校验语言
		if($is_force)
		{
			return true;
		}

		// 模块判断缓存（请求内不变、应用名未解析时不下结论避免空值钉死）
		static $module_result = null;
		if(is_null($module_result))
		{
			$module = RequestModule();
			if($module === '')
			{
				return false;
			}
			$module_result = in_array($module, ['index', 'api']);
		}
		return $module_result;
	}

	/**
	 * 按关键字匹配业务 id（仅非默认语言查 i18n_value；默认语言直接空，不访问表）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-03-26
	 * @param   [string]          $table_name [业务表名，如 goods / plugins_blog]
	 * @param   [string]          $keyword    [关键字]
	 * @param   [array]           $fields     [参与匹配的 i18n 字段]
	 */
	public static function BusinessIdsByKeyword($table_name, $keyword, $fields = [])
	{
		if(!self::IsHandle() || $keyword === '' || $keyword === null || empty($table_name) || empty($fields) || !is_array($fields))
		{
			return [];
		}
		$ids = Db::name('I18nValue')->where([
				['table_name', '=', $table_name],
				['lang', '=', self::CurrentLang()],
				['field', 'in', $fields],
				['value', 'like', '%'.$keyword.'%'],
			])->column('business_id');
		return empty($ids) ? [] : array_values(array_unique(array_map('intval', $ids)));
	}

	/**
	 * 缓存语言key（后台模块统一使用默认语言、避免后台未翻译数据污染前台语言缓存）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public static function CacheLangKey()
	{
		$lang = (RequestModule() == 'admin') ? self::DefaultLang() : self::CurrentLang();
		return empty($lang) ? 'zh' : $lang;
	}

	/**
	 * 配置键值文案多语言替换（MyC/ConfigContentRow输出端、前台非默认语言、注册过的配置键才处理）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date     2026-08-31
	 * @param   [string]          $key   [配置唯一标记]
	 * @param   [string]          $value [原值]
	 * @return  [string]
	 */
	public static function ConfigValueHandle($key, $value)
	{
		// 系统配置多语言替换（前台非默认语言、注册过的配置键）
		if(is_string($value) && self::IsHandle())
		{
			// 当前语言全部配置翻译（一次加载）
			static $values = null;
			if(is_null($values))
			{
				$temp = Db::name('I18nValue')->where(['lang'=>self::CurrentLang(), 'table_name'=>'config', 'business_id'=>0])->column('value', 'field');
				$values = empty($temp) ? [] : $temp;
			}

			// JSON列表结构子字段替换（备案信息/自提地址弹窗行编辑、内容寻址键 字段_子字段_内容md5）
			$json_list_fields = [
				'home_site_filing'             => ['name', 'show_name'],
				'common_self_extraction_address' => ['alias', 'name', 'address'],
			];
			if(isset($json_list_fields[$key]))
			{
				$arr = json_decode($value, true);
				if(is_array($arr))
				{
					$changed = false;
					foreach($arr as $ri=>$row)
					{
						if(!is_array($row))
						{
							continue;
						}
						foreach($json_list_fields[$key] as $sf)
						{
							if(!isset($row[$sf]) || !is_string($row[$sf]) || trim($row[$sf]) === '')
							{
								continue;
							}
							$vk = $key.'_'.$sf.'_'.self::ContentKey($row[$sf]);
							if(isset($values[$vk]) && $values[$vk] !== '')
							{
								$arr[$ri][$sf] = $values[$vk];
								$changed = true;
							}
						}
					}
					if($changed)
					{
						$value = json_encode($arr, JSON_UNESCAPED_UNICODE);
					}
				}
			} else if($key == 'home_index_floor_top_right_keywords') {
				// 首页楼层顶部右侧关键字（json二维结构：分类id=>逗号分隔关键字、逐关键字内容键 field_分类id_内容md5 替换）
				$arr = json_decode($value, true);
				if(is_array($arr))
				{
					$changed = false;
					foreach($arr as $catid=>$kws)
					{
						if(!is_string($kws))
						{
							continue;
						}
						$items = explode(',', $kws);
						$cat_changed = false;
						foreach($items as $ik=>$kw)
						{
							$kw_t = trim($kw);
							if($kw_t === '')
							{
								continue;
							}
							$vk = $key.'_'.$catid.'_'.self::ContentKey($kw_t);
							if(isset($values[$vk]) && $values[$vk] !== '')
							{
								$items[$ik] = $values[$vk];
								$cat_changed = true;
							}
						}
						if($cat_changed)
						{
							$arr[$catid] = implode(',', $items);
							$changed = true;
						}
					}
					if($changed)
					{
						$value = json_encode($arr, JSON_UNESCAPED_UNICODE);
					}
				}
			} else if(isset($values[$key]) && $values[$key] !== '') {
				// 富文本字段存储为附件路径格式、输出转回可访问地址
				$type = self::FieldsConfig('config');
				$value = (isset($type[$key]) && $type[$key] == 'editor') ? ResourcesService::ContentStaticReplace($values[$key], 'get') : $values[$key];
			}
		}

		// 配置值处理钩子（插件可按自身多语言覆盖，如版权修改的站点名/底部版权）
		$hook_name = 'plugins_service_config_value_handle';
		MyEventTrigger($hook_name, [
			'hook_name'   => $hook_name,
			'is_backend'  => true,
			'key'         => $key,
			'value'       => &$value,
		]);

		return $value;
	}

	/**
	 * 插件配置多语言替换（读取出口统一走PluginsService::PluginsData、字段名=插件名_配置键）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date    2026-08-31
	 * @param   [string]          $plugins_name [插件标识]
	 * @param   [array]           $data         [插件配置数据]
	 * @param   [boolean]         $is_force     [是否强制（跳过模块限制）]
	 */
	public static function PluginsConfigHandle($plugins_name, &$data, $is_force = false)
	{
		if(empty($data) || !is_array($data) || !self::IsHandle($is_force))
		{
			return;
		}

		// 仅已注册的插件配置键参与替换（白名单按插件标识嵌套）
		$fields_config = self::FieldsConfig('plugins_config');
		if(empty($fields_config) || !isset($fields_config[$plugins_name]))
		{
			return;
		}

		// 当前语言当前插件配置翻译（按 plugins+lang 按需加载，避免长进程钉死首语）
		static $values_by_plugin = [];
		$lang = self::CurrentLang();
		$cache_key = $plugins_name.'|'.$lang;
		if(!array_key_exists($cache_key, $values_by_plugin))
		{
			$temp = Db::name('I18nValue')->where(['lang'=>$lang, 'table_name'=>'plugins_config', 'business_id'=>0, 'plugins'=>$plugins_name])->column('value', 'field');
			$values_by_plugin[$cache_key] = empty($temp) ? [] : $temp;
		}
		$values = $values_by_plugin[$cache_key];
		if(empty($values))
		{
			return;
		}

		// 数组/列表结构配置（内容寻址键逐项替换、键=配置字段[_子字段]_内容md5，plugins 列隔离插件）
		$array_struct = self::PluginsConfigArrayStruct();

		foreach($data as $k=>&$v)
		{
			// 字符串键：已注册且存在翻译即覆盖（原值未设置/为空也覆盖、多语言优先于语言包默认文案）
			if(is_string($v))
			{
				$field = $k;
				if(!isset($fields_config[$plugins_name][$k]))
				{
					continue;
				}
				$type = $fields_config[$plugins_name][$k];
				// 动态整块文本（data-i18n-content）：按内容寻址键覆盖
				if($type == 'dynamic' && $v !== '')
				{
					$ck = $field.'_'.self::ContentKey($v);
					if(isset($values[$ck]) && $values[$ck] !== '')
					{
						$v = $values[$ck];
						continue;
					}
				}
				if(isset($values[$field]) && $values[$field] !== '')
				{
					$v = $values[$field];
				}
			} else if(is_array($v) && isset($array_struct[$plugins_name][$k])) {
				// 行列表：行内子字段
				if(!empty($array_struct[$plugins_name][$k]))
				{
					foreach($v as $ri=>&$row)
					{
						if(!is_array($row))
						{
							continue;
						}
						foreach($array_struct[$plugins_name][$k] as $sf)
						{
							if(!isset($row[$sf]))
							{
								continue;
							}
							// 优先按行 id 定址（如首页轮播指定商品自定义名称），改原文也不丢翻译
							$replaced = false;
							if(isset($row['id']) && $row['id'] !== '' && $row['id'] !== null)
							{
								$ik = $k.'_'.$sf.'_'.$row['id'];
								if(isset($values[$ik]) && $values[$ik] !== '')
								{
									if(is_array($row[$sf]))
									{
										$row[$sf] = explode("\n", $values[$ik]);
									} else {
										$row[$sf] = $values[$ik];
									}
									$replaced = true;
								}
							}
							if($replaced)
							{
								continue;
							}
							// 多行文本存数组时按 join 后内容键替换，再 explode 回数组
							if(is_array($row[$sf]))
							{
								$joined = implode("\n", array_map('strval', $row[$sf]));
								if($joined === '')
								{
									continue;
								}
								$ck = $k.'_'.$sf.'_'.self::ContentKey($joined);
								if(isset($values[$ck]) && $values[$ck] !== '')
								{
									$row[$sf] = explode("\n", $values[$ck]);
								}
							} else if(is_string($row[$sf]) && $row[$sf] !== '') {
								$ck = $k.'_'.$sf.'_'.self::ContentKey($row[$sf]);
								if(isset($values[$ck]) && $values[$ck] !== '')
								{
									$row[$sf] = $values[$ck];
								}
							}
						}
					}
					unset($row);
				} else {
					// 整组文本行：join后整块内容键替换（翻译值按行explode回数组）
					$joined = implode("\n", array_map('strval', $v));
					$ck = $k.'_'.self::ContentKey($joined);
					if(isset($values[$ck]) && $values[$ck] !== '')
					{
						$v = explode("\n", $values[$ck]);
					}
				}
			}
		}
		unset($v);

		// tagsinput 逗号多值：按原文逐项 字段_内容md5 替换（与业务表 TagsinputReplaceMap 一致）
		$tags_map = self::TagsinputReplaceMap();
		$tags_fields = empty($tags_map['plugins_config']) ? [] : $tags_map['plugins_config'];
		if(!empty($tags_fields))
		{
			foreach($data as $k=>&$v)
			{
				if(!is_string($v) || $v === '' || !isset($fields_config[$plugins_name][$k]))
				{
					continue;
				}
				if(!in_array($k, $tags_fields, true) && !in_array($plugins_name.'_'.$k, $tags_fields, true))
				{
					continue;
				}
				$items = explode(',', $v);
				$changed = false;
				foreach($items as $ik=>$item)
				{
					$item_t = trim($item);
					if($item_t === '')
					{
						continue;
					}
					$ck = $k.'_'.self::ContentKey($item_t);
					if(isset($values[$ck]) && $values[$ck] !== '')
					{
						$items[$ik] = $values[$ck];
						$changed = true;
					}
				}
				if($changed)
				{
					$v = implode(',', $items);
				}
			}
			unset($v);
		}

		// 配置中未出现的已注册文本键：仍用翻译写入（为空走语言包之前先吃多语言）
		foreach($fields_config[$plugins_name] as $k=>$type)
		{
			if(($type == 'text' || $type == 'editor') && (!isset($data[$k]) || $data[$k] === '' || $data[$k] === null))
			{
				if(isset($values[$k]) && $values[$k] !== '')
				{
					$data[$k] = $values[$k];
				}
			}
		}
	}

	/**
	 * 插件配置存储字段类型（field 为配置键，不再带插件名前缀；靠 plugins 列隔离）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date     2026-09-02
	 * @param   [string]          $field   [存储字段名（配置键 / 配置键_后缀）]
	 * @param   [string]          $plugins [插件标识]
	 * @return  [string|null]
	 */
	private static function PluginsConfigFieldType($field, $plugins = '')
	{
		$fields_config = self::FieldsConfig('plugins_config');
		if(empty($fields_config))
		{
			return null;
		}

		$plugins = trim(strval($plugins));
		// 指定插件：按配置键匹配（兼容历史「插件名_配置键」）
		// 先精确匹配配置键，避免字段名本身以插件名_开头时被误剥前缀（如 chat 插件的 chat_right_content）
		if($plugins !== '' && isset($fields_config[$plugins]) && is_array($fields_config[$plugins]))
		{
			$map = $fields_config[$plugins];
			if(isset($map[$field]))
			{
				return $map[$field];
			}
			$base = $field;
			while(preg_match('/^(.+)_(\d+|[a-f0-9]{32})$/', $base, $match))
			{
				$base = $match[1];
			}
			if($base !== $field && isset($map[$base]) && $map[$base] == 'dynamic')
			{
				return 'dynamic';
			}
			$prefix = $plugins.'_';
			if(strpos($field, $prefix) === 0)
			{
				$key = substr($field, strlen($prefix));
				if(isset($map[$key]))
				{
					return $map[$key];
				}
				$base = $key;
				while(preg_match('/^(.+)_(\d+|[a-f0-9]{32})$/', $base, $match))
				{
					$base = $match[1];
				}
				if($base !== $key && isset($map[$base]) && $map[$base] == 'dynamic')
				{
					return 'dynamic';
				}
			}
			return null;
		}

		// 未传插件：兼容旧数据按「插件名_」前缀解析
		$plugin_keys = array_keys($fields_config);
		usort($plugin_keys, function($a, $b){ return strlen($b) - strlen($a); });
		foreach($plugin_keys as $plugin)
		{
			if(strpos($field, $plugin.'_') === 0)
			{
				return self::PluginsConfigFieldType(substr($field, strlen($plugin)+1), $plugin);
			}
		}
		return null;
	}

	/**
	 * 插件配置多语言仅保留当前插件字段（plugins_config 共用 business_id=0、避免串入其它插件键）
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-09-04
	 * @param   [string]          $plugin [插件标识]
	 * @param   [array]           $data   [多语言数据 field => lang => value]
	 * @return  [array]
	 */
	public static function FilterPluginsConfigData($plugin, $data)
	{
		if(!is_array($data) || $plugin === '' || $plugin === null)
		{
			return is_array($data) ? $data : [];
		}
		$fields_config = self::FieldsConfig('plugins_config');
		$allow = (!empty($fields_config[$plugin]) && is_array($fields_config[$plugin])) ? $fields_config[$plugin] : [];
		$prefix = $plugin.'_';
		$result = [];
		foreach($data as $field=>$langs)
		{
			// 先精确匹配配置键，避免字段名以插件名_开头时被误剥（如 chat_right_content）
			if(isset($allow[$field]))
			{
				$result[$field] = $langs;
				continue;
			}
			$base = $field;
			while(preg_match('/^(.+)_(\d+|[a-f0-9]{32})$/', $base, $match))
			{
				$base = $match[1];
			}
			if($base !== $field && isset($allow[$base]) && $allow[$base] == 'dynamic')
			{
				$result[$field] = $langs;
				continue;
			}
			// 兼容历史带插件前缀的提交（插件名_配置键）
			if(strpos($field, $prefix) === 0)
			{
				$key = substr($field, strlen($prefix));
				if(isset($allow[$key]))
				{
					$result[$key] = $langs;
					continue;
				}
				$base = $key;
				while(preg_match('/^(.+)_(\d+|[a-f0-9]{32})$/', $base, $match))
				{
					$base = $match[1];
				}
				if($base !== $key && isset($allow[$base]) && $allow[$base] == 'dynamic')
				{
					$result[$key] = $langs;
				}
			}
		}
		return $result;
	}

	/**
	 * 动态字段基础名校验（支持多段后缀：字段_序号 / 字段_内容md5 / 字段_序号_内容md5）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date     2026-09-02
	 * @param   [string]          $field        [字段名]
	 * @param   [array]           $fields_config [字段配置]
	 * @return  [boolean]
	 */
	public static function IsDynamicField($field, $fields_config)
	{
		$base = $field;
		while(preg_match('/^(.+)_(\d+|[a-f0-9]{32})$/', $base, $match))
		{
			$base = $match[1];
		}
		return ($base !== $field && array_key_exists($base, $fields_config) && $fields_config[$base] == 'dynamic');
	}

	/**
	 * 业务数据多语言值（后台弹窗回显）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date     2026-08-31
	 * @param   [string]          $table_name  [业务表名]
	 * @param   [int]             $business_id [业务数据id]
	 */
	public static function ValueData($table_name, $business_id, $plugins = '')
	{
		$result = [];
		// 键值型表允许business_id=0（config配置键、plugins_config插件配置键）；行表含动态键时仍按传入 business_id 存取
		$has_dynamic_field = !empty($table_name) && in_array('dynamic', self::FieldsConfig($table_name));
		if(!empty($table_name) && ($business_id > 0 || $has_dynamic_field || in_array($table_name, ['config', 'plugins_config'])))
		{
			$fields_config = self::FieldsConfig($table_name);
			// 严格按 business_id 读取（行表不合并 0 池，避免多主题数据/多商品互串）；插件表可再按 plugins 按需过滤
			$where = ['table_name'=>$table_name, 'business_id'=>intval($business_id)];
			$plugins = trim(strval($plugins));
			if($plugins !== '')
			{
				$where['plugins'] = $plugins;
			}
			$data = Db::name('I18nValue')->where($where)->field('lang,field,value')->select()->toArray();
			if(!empty($fields_config) && !empty($data))
			{
				foreach($data as $v)
				{
					// 富文本字段存储为附件路径格式、回显转回可访问地址
					$field = $v['field'];
					$type = null;
					if($table_name == 'plugins_config')
					{
						$type = self::PluginsConfigFieldType($field, $plugins);
					} else if(array_key_exists($field, $fields_config)) {
						$type = $fields_config[$field];
					} else {
						// 动态列表字段（字段_序号 / 字段_内容md5 / 字段_序号_内容md5）
						if(self::IsDynamicField($field, $fields_config))
						{
							$type = 'dynamic';
						}
					}
					if($type == 'editor')
					{
						$v['value'] = ResourcesService::ContentStaticReplace($v['value'], 'get');
					}
					$result[$field][$v['lang']] = $v['value'];
				}
			}
		}

		// 插件合并扩展翻译（如商品编辑页内插件字段、存 plugins_config 按业务 id 定址）
		$hook_name = 'plugins_service_i18n_value_data';
		MyEventTrigger($hook_name, [
			'hook_name'    => $hook_name,
			'is_backend'   => true,
			'table_name'   => $table_name,
			'business_id'  => intval($business_id),
			'plugins'      => trim(strval($plugins)),
			'data'         => &$result,
		]);
		return $result;
	}

	/**
	 * 批量获取多语言值
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [string]          $table_name [业务表名]
	 * @param   [array]           $ids        [业务数据id集合]
	 * @return  [array]                       [id][field][lang] => value
	 */
	public static function ValuesBatch($table_name, $ids)
	{
		$result = [];
		// 保留0（内容寻址键池business_id=0）、仅去除负数和重复
		$ids = array_values(array_unique(array_filter(array_map('intval', (is_array($ids) ? $ids : [$ids])), function($v){ return $v >= 0; })));
		if(!empty($ids) && !empty($table_name))
		{
			// 去除已查询的
			$need = [];
			foreach($ids as $id)
			{
				if(!array_key_exists($id, self::$values_static[$table_name] ?? []))
				{
					$need[] = $id;
				}
			}
			if(!empty($need))
			{
				$data = Db::name('I18nValue')->where(['table_name'=>$table_name])->where('business_id', 'in', $need)->field('business_id,lang,field,value')->select()->toArray();
				foreach($data as $v)
				{
					self::$values_static[$table_name][$v['business_id']][$v['field']][$v['lang']] = $v['value'];
				}
				foreach($need as $id)
				{
					if(!isset(self::$values_static[$table_name][$id]))
					{
						self::$values_static[$table_name][$id] = [];
					}
				}
			}
			foreach($ids as $id)
			{
				$result[$id] = self::$values_static[$table_name][$id];
			}
		}
		return $result;
	}

	/**
	 * 数据列表字段替换（前台输出）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $data       [数据（单条或列表）]
	 * @param   [string]          $table_name [业务表名]
	 * @param   [boolean]         $is_force   [是否强制（跳过模块限制）]
	 */
	public static function DataHandle(&$data, $table_name, $is_force = false)
	{
		if(!empty($data) && self::IsHandle($is_force))
		{
			$fields_config = self::FieldsConfig($table_name);
			if(!empty($fields_config))
			{
				$lang = self::CurrentLang();

				// 单条数据
				if(is_array($data) && array_key_exists('id', $data))
				{
					self::RowHandle($data, $fields_config, $lang, $table_name);
				} else {
					foreach($data as &$v)
					{
						if(is_array($v))
						{
							self::RowHandle($v, $fields_config, $lang, $table_name);
						}
					}
				}
			}
		}
	}

	/**
	 * 单行数据替换
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	private static function RowHandle(&$row, $fields_config, $lang, $table_name)
	{
		if(!empty($row['id']))
		{
			// 仅取本行 business_id（不合并 0 池）
			$values = self::ValuesBatch($table_name, [$row['id']]);
			$vals = $values[$row['id']] ?? [];
			if(!empty($vals))
			{
				foreach($fields_config as $field=>$type)
				{
					// 动态列表字段单独处理
					if($type != 'dynamic' && array_key_exists($field, $row) && !empty($vals[$field][$lang]))
					{
						$row[$field] = $vals[$field][$lang];
					}
				}
			}

			// 行内嵌套结构按内容寻址键替换
			$nested_map = self::NestedContentReplaceMap();
			if(!empty($nested_map[$table_name]) && !empty($vals))
			{
				foreach($nested_map[$table_name] as $list_key=>$subs)
				{
					if(empty($row[$list_key]) || !is_array($row[$list_key]))
					{
						continue;
					}
					foreach($row[$list_key] as $ik=>$item)
					{
						if(!is_array($item))
						{
							continue;
						}
						foreach($subs as $sub=>$prefix)
						{
							if(!empty($item[$sub]) && is_string($item[$sub]))
							{
								$ck = $prefix.'_'.self::ContentKey($item[$sub]);
								if(!empty($vals[$ck][$lang]))
								{
									$row[$list_key][$ik][$sub] = $vals[$ck][$lang];
								}
							}
						}
					}
				}
			}

			// tagsinput 逗号多值：按原文逐项替换（整字段 dynamic 不会走上面的行字段覆盖）
			$tags_map = self::TagsinputReplaceMap();
			if(!empty($tags_map[$table_name]) && !empty($vals))
			{
				foreach($tags_map[$table_name] as $field)
				{
					if(empty($row[$field]) || !is_string($row[$field]))
					{
						continue;
					}
					$items = explode(',', $row[$field]);
					$changed = false;
					foreach($items as $ik=>$item)
					{
						$item_t = trim($item);
						if($item_t === '')
						{
							continue;
						}
						$ck = $field.'_'.self::ContentKey($item_t);
						if(!empty($vals[$ck][$lang]))
						{
							$items[$ik] = $vals[$ck][$lang];
							$changed = true;
						}
					}
					if($changed)
					{
						$row[$field] = implode(',', $items);
					}
				}
			}

			// 插件再次覆盖（插件自有字段多语言，不进系统表白名单）
			$hook_name = 'plugins_service_i18n_data_handle';
			MyEventTrigger($hook_name, [
				'hook_name'    => $hook_name,
				'is_backend'   => true,
				'table_name'   => $table_name,
				'lang'         => $lang,
				'data'         => &$row,
			]);
		}
	}

	/**
	 * 名称键值对数据替换（id => name）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $data       [数据（id => name）]
	 * @param   [string]          $table_name [业务表名]
	 */
	public static function NameHandle(&$data, $table_name)
	{
		if(!empty($data) && is_array($data) && self::IsHandle())
		{
			$lang = self::CurrentLang();
			$values = self::ValuesBatch($table_name, array_keys($data));
			foreach($data as $id=>&$name)
			{
				if(!empty($values[$id]['name'][$lang]))
				{
					$name = $values[$id]['name'][$lang];
				}
			}
		}
	}

	/**
	 * 主题数据多语言替换（ThemeDataListHandle 内调用）
	 * name / data json 内业务文本(text_xxx.value) / 自定义数据(custom_data.name/value) 均按主题数据行 id 取翻译
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date    2026-09-02
	 * @param   [array]           $data [主题数据列表]
	 */
	public static function ThemeDataHandle(&$data)
	{
		if(empty($data) || !is_array($data) || !self::IsHandle())
		{
			return;
		}
		$lang = self::CurrentLang();
		$values = self::ValuesBatch('theme_data', array_column($data, 'id'));

		foreach($data as &$v)
		{
			$vals = isset($values[$v['id']]) ? $values[$v['id']] : [];
			if(empty($vals))
			{
				continue;
			}

			// 名称
			if(isset($vals['name'][$lang]))
			{
				$v['name'] = $vals['name'][$lang];
			}

			// data json 内容（先解析、替换、循环内原逻辑继续处理）
			if(!empty($v['data']) && is_string($v['data']))
			{
				$temp = json_decode($v['data'], true);
				if(is_array($temp))
				{
					$changed = false;

					// 业务文本（text_xxx => [value]、含多图文内层data数组的text）
					foreach($temp as $dk=>$dv)
					{
						if(substr($dk, 0, 5) == 'text_' && is_array($dv) && isset($dv['value']) && is_string($dv['value']) && $dv['value'] !== '')
						{
							$ck = 'theme_data_text_'.self::ContentKey($dv['value']);
							if(isset($vals[$ck][$lang]))
							{
								$temp[$dk]['value'] = $vals[$ck][$lang];
								$changed = true;
							}
						}
						// 多图文内层（data => [ [text_xxx=>[value]], ])
						if($dk == 'data' && is_array($dv))
						{
							foreach($dv as $mi=>$mrow)
							{
								if(!is_array($mrow)) continue;
								foreach($mrow as $mk=>$mv)
								{
									if(substr($mk, 0, 5) == 'text_' && is_array($mv) && isset($mv['value']) && is_string($mv['value']) && $mv['value'] !== '')
									{
										$mck = 'theme_data_text_'.self::ContentKey($mv['value']);
										if(isset($vals[$mck][$lang]))
										{
											$temp[$dk][$mi][$mk]['value'] = $vals[$mck][$lang];
											$changed = true;
										}
									}
								}
							}
						}
					}

					// 自定义数据（custom_data => [[name,value],])
					if(isset($temp['custom_data']) && is_array($temp['custom_data']))
					{
						foreach($temp['custom_data'] as $ci=>$cv)
						{
							foreach(['name', 'value'] as $cf)
							{
								if(isset($cv[$cf]) && is_string($cv[$cf]) && $cv[$cf] !== '')
								{
									$ck = 'theme_data_custom_'.$cf.'_'.self::ContentKey($cv[$cf]);
									if(isset($vals[$ck][$lang]))
									{
										$temp['custom_data'][$ci][$cf] = $vals[$ck][$lang];
										$changed = true;
									}
								}
							}
						}
					}

					if($changed)
					{
						$v['data'] = json_encode($temp, JSON_UNESCAPED_UNICODE);
					}
				}
			}
		}
	}

	/**
	 * 商品手机详情多语言替换（GoodsAppData 数据格式 goods_id => 索引列表）
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $data [商品手机详情数据]
	 */
	public static function GoodsContentAppHandle(&$data)
	{
		if(!empty($data) && is_array($data) && self::IsHandle())
		{
			$lang = self::CurrentLang();
			$values = self::ValuesBatch('goods', array_keys($data));
			foreach($data as $goods_id=>&$items)
			{
				if(empty($items) || !is_array($items) || empty($values[$goods_id]))
				{
					continue;
				}
				foreach($items as $index=>$item)
				{
					// 内容md5为键、顺序调整不影响
					$value = isset($values[$goods_id]['content_app_text_'.self::ContentKey($item['content_old'])][$lang]) ? $values[$goods_id]['content_app_text_'.self::ContentKey($item['content_old'])][$lang] : '';
					if($value !== '')
					{
						$items[$index]['content'] = explode("\n", $value);
						$items[$index]['content_old'] = $value;
					}
				}
			}
		}
	}

	/**
	 * 获取请求中的多语言数据（表单隐藏域）
	 * 全局输入过滤会转义json中的引号、需先还原再解析；兼容已解析的数组格式
	 * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-31
	 * @param   [array]           $params [请求参数]
	 * @return  [array|null]              [null未提交不处理、array正常数据（空数组表示清空）]
	 */
	public static function RequestData($params)
	{
		if(!is_array($params) || !array_key_exists('i18n_data', $params))
		{
			return null;
		}
		$data = $params['i18n_data'];
		if(is_string($data))
		{
			if($data === '')
			{
				return null;
			}
			$data = json_decode(htmlspecialchars_decode($data), true);
		}
		// 解析失败或非数组则不处理
		if(is_array($data))
		{
			// 空对象（无任何字段）不处理、避免误清空全部数据
			return empty($data) ? null : $data;
		}
		return null;
	}

	/**
	 * 请求中的插件标识（表单 data-i18n-plugins → 隐藏域 i18n_plugins）
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-09-09
	 * @param   [array]          $params [请求参数，空则读当前请求]
	 * @return  [string]
	 */
	public static function RequestPlugins($params = null)
	{
		if(is_array($params) && array_key_exists('i18n_plugins', $params))
		{
			return trim(strval($params['i18n_plugins']));
		}
		$v = input('i18n_plugins', '');
		return is_string($v) ? trim($v) : '';
	}

	/**
	 * 动态字段内容键（换行统一\n并去首尾空白后md5、与前端Md5Content一致）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [string]          $str [内容]
	 * @return  [string]               [md5]
	 */
	public static function ContentKey($str)
	{
		return md5(trim(str_replace(["\r\n", "\r"], "\n", strval($str))));
	}

	/**
	 * 订单快递备注多语言字段键（按快递公司id+单号区分，避免同订单多条快递互串）
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-09-09
	 * @param   [int]             $express_id     [快递公司id]
	 * @param   [string]          $express_number [快递单号]
	 * @return  [string]                          [字段名，无效时返回空串]
	 */
	public static function OrderExpressNoteField($express_id, $express_number)
	{
		$express_id = intval($express_id);
		$express_number = trim(str_replace(["\r\n", "\r"], "\n", strval($express_number)));
		if($express_id <= 0 || $express_number === '')
		{
			return '';
		}
		return 'note_'.$express_id.'_'.self::ContentKey($express_number);
	}

	/**
	 * 商品参数多语言替换（GoodsParametersData 平铺列表、按提交顺序索引）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $list       [商品参数平铺列表（id asc顺序）]
	 * @param   [string]          $table_name [业务表名、默认 goods]
	 */
	public static function GoodsParamsHandle(&$list, $table_name = 'goods')
	{
		if(!empty($list) && is_array($list) && self::IsHandle())
		{
			$lang = self::CurrentLang();
			$values = self::ValuesBatch(empty($table_name) ? 'goods' : $table_name, array_unique(array_column($list, 'goods_id')));

			// 非自定义模式：参数名称/选项值来自参数模板、以模板翻译覆盖（模板翻译一次全商品生效）
			$is_custom = (MyC('common_is_goods_parameters_custom_mode', 0) == 1);
			$template_maps = $is_custom ? ['name'=>[], 'value'=>[]] : self::ParamsTemplateOverlayData(array_unique(array_column($list, 'name')));

			foreach($list as $k=>&$v)
			{
				$gv = isset($values[$v['goods_id']]) ? $values[$v['goods_id']] : [];

				// 名称：商品自身翻译 -> 模板翻译（各以名称内容md5为键）
				$name = isset($gv['parameters_name_'.self::ContentKey($v['name'])][$lang]) ? $gv['parameters_name_'.self::ContentKey($v['name'])][$lang] : '';
				if($name === '' && isset($template_maps['name'][$v['name']]))
				{
					$name = $template_maps['name'][$v['name']];
				}
				if($name !== '')
				{
					$v['name'] = $name;
				}

				// 值：商品自身翻译 -> 模板选项翻译（各以值内容md5为键）
				$value = isset($gv['parameters_value_'.self::ContentKey($v['value'])][$lang]) ? $gv['parameters_value_'.self::ContentKey($v['value'])][$lang] : '';
				if($value === '' && isset($template_maps['value'][$v['value']]))
				{
					$value = $template_maps['value'][$v['value']];
				}
				if($value !== '')
				{
					$v['value'] = $value;
				}
			}
		}
	}

	/**
	 * 参数模板覆盖数据（非自定义模式、按名称/选项原文取翻译）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $names [参数名称集合]
	 * @return  [array]                  [name => 翻译名集合、value => 选项原文 => 翻译值集合]
	 */
	public static function ParamsTemplateOverlayData($names)
	{
		$result = ['name'=>[], 'value'=>[]];
		$lang = self::CurrentLang();
		if(!empty($names))
		{
			$configs = Db::name('GoodsParamsTemplateConfig')->where([['name', 'in', $names]])->field('template_id,name,value')->select()->toArray();
			if(!empty($configs))
			{
				$values = self::ValuesBatch('goods_params_template', array_unique(array_column($configs, 'template_id')));
				foreach($configs as $c)
				{
					$tv = isset($values[$c['template_id']]) ? $values[$c['template_id']] : [];

					// 名称翻译（内容md5键）
					if(!isset($result['name'][$c['name']]))
					{
						$name = isset($tv['parameters_name_'.self::ContentKey($c['name'])][$lang]) ? $tv['parameters_name_'.self::ContentKey($c['name'])][$lang] : '';
						if($name !== '')
						{
							$result['name'][$c['name']] = $name;
						}
					}

					// 选项值翻译：原文行与译文行按位置对应成映射
					$trans_value = isset($tv['parameters_value_'.self::ContentKey($c['value'])][$lang]) ? $tv['parameters_value_'.self::ContentKey($c['value'])][$lang] : '';
					if($trans_value !== '' && !empty($c['value']))
					{
						$base_lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $c['value']));
						$trans_lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $trans_value));
						if(count($base_lines) == count($trans_lines))
						{
							foreach($base_lines as $i=>$line)
							{
								$line = trim($line);
								if($line !== '' && $trans_lines[$i] !== '' && !isset($result['value'][$line]))
								{
									$result['value'][$line] = trim($trans_lines[$i]);
								}
							}
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * 商品参数模板配置多语言替换（配置平铺列表、按模板内行序号索引）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $configs [配置列表（含template_id、id asc顺序）]
	 */
	public static function ParamsTemplateConfigHandle(&$configs)
	{
		if(!empty($configs) && is_array($configs) && self::IsHandle())
		{
			$lang = self::CurrentLang();
			$values = self::ValuesBatch('goods_params_template', array_unique(array_column($configs, 'template_id')));
			foreach($configs as $k=>&$c)
			{
				$tv = isset($values[$c['template_id']]) ? $values[$c['template_id']] : [];
				$name = isset($tv['parameters_name_'.self::ContentKey($c['name'])][$lang]) ? $tv['parameters_name_'.self::ContentKey($c['name'])][$lang] : '';
				if($name !== '')
				{
					$c['name'] = $name;
				}
				$value = (isset($c['value']) && $c['value'] !== '') && isset($tv['parameters_value_'.self::ContentKey($c['value'])][$lang]) ? $tv['parameters_value_'.self::ContentKey($c['value'])][$lang] : '';
				if($value !== '')
				{
					$c['value'] = $value;
				}
			}
		}
	}

	/**
	 * 规格模板多语言保存（值块按逗号拆分逐值存储、内容md5为键）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [string]          $table_name  [业务表名]
	 * @param   [int]             $template_id [模板id]
	 * @param   [array]           $data        [多语言数据]
	 * @param   [string]          $content     [模板规格值原文（逗号分隔）]
	 */
	public static function SpecTemplateSaveData($table_name, $template_id, $data, $content)
	{
		if(empty($table_name) || empty($template_id) || !is_array($data))
		{
			return;
		}

		// 规格值块（弹窗按整块翻译提交）拆分：原文第N项 <-> 译文第N项
		$content_key = self::ContentKey($content);
		foreach($data as $field=>$langs)
		{
			if($field != 'spec_value_'.$content_key || !is_array($langs))
			{
				continue;
			}
			$base_items = array_values(array_filter(array_map('trim', explode(',', strval($content)))));
			if(empty($base_items))
			{
				continue;
			}
			foreach($langs as $lang=>$trans)
			{
				// 按位置对应（译文数量可少于原文、只对应前N个）
				$trans_items = explode(',', strval($trans));
				foreach($base_items as $i=>$item)
				{
					if($i >= count($trans_items))
					{
						break;
					}
					$trans_item = trim($trans_items[$i]);
					if($trans_item !== '')
					{
						$data['spec_value_'.md5($item)][$lang] = $trans_item;
					}
				}
				unset($data[$field][$lang]);
			}
			if(empty($data[$field]))
			{
				unset($data[$field]);
			}
		}

		if(!empty($data))
		{
			self::SaveData($table_name, $template_id, $data);
		}
	}

	/**
	 * 规格模板翻译继承（已停用写入商品行：避免模板其它语种写入本商品 business_id）
	 * 前台展示仍走 GoodsSpecNameHandle / GoodsSpecValueHandle 的模板回退
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $spec_titles [规格名称集合]
	 * @param   [array]           $spec_values [规格值集合（多列）]
	 * @param   [array|null]      $i18n_data   [用户确认的多语言数据]
	 * @return  [array|null]                   [原样返回，不再合并模板]
	 */
	public static function SpecTemplateInherit($spec_titles = [], $spec_values = [], $i18n_data = null)
	{
		return $i18n_data;
	}

	/**
	 * 商品规格弹窗预填（已停用：编辑回显严格按商品 business_id，禁止合并规格模板全量语种）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $data [商品已有多语言数据]
	 * @return  [array]
	 */
	public static function SpecPrefillMerge($data)
	{
		return is_array($data) ? $data : [];
	}

	/**
	 * 商品规格值处理（输出附加key=基础值md5、显示值仅替换本商品 business_id 下翻译）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $temp_group [商品规格分组数据（goods_id => ['choose'=>[类型行...]]、类型行value为已解码的值列表）]
	 * @param   [string]          $table_name [业务表名、默认 goods]
	 */
	public static function GoodsSpecValueHandle(&$temp_group, $table_name = 'goods')
	{
		if(!empty($temp_group) && is_array($temp_group))
		{
			// key为语言无关的匹配标识、任何语言都要输出；翻译仅非默认语言处理
			$is_handle = self::IsHandle();
			$lang = self::CurrentLang();
			$values = $is_handle ? self::ValuesBatch(empty($table_name) ? 'goods' : $table_name, array_keys($temp_group)) : [];

			foreach($temp_group as $gid=>&$gv)
			{
				$gvals = isset($values[$gid]) ? $values[$gid] : [];
				foreach($gv['choose'] as &$type)
				{
					if(empty($type['value']) || !is_array($type['value']))
					{
						continue;
					}
					foreach($type['value'] as &$item)
					{
						if(!isset($item['name']))
						{
							continue;
						}
						// 基础值key（翻译前计算、所有语言输出）
						$item['key'] = self::ContentKey($item['name']);
						if(!$is_handle)
						{
							continue;
						}

						// 仅本商品 business_id 翻译，无翻译则保留原文（不回退规格模板）
						$field = 'spec_value_'.self::ContentKey($item['name']);
						$val = isset($gvals[$field][$lang]) ? $gvals[$field][$lang] : '';
						if($val !== '')
						{
							$item['name'] = $val;
						}
					}
				}
			}
		}
	}

	/**
	 * 规格模板值翻译数据（所有模板、内容md5键聚合）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public static function SpecTemplateValueMaps()
	{
		static $result = null;
		if(is_null($result))
		{
			$result = [];
			$rows = Db::name('I18nValue')->where(['table_name'=>'goods_spec_template'])->field('field,lang,value')->select()->toArray();
			foreach($rows as $v)
			{
				if(strpos($v['field'], 'spec_value_') === 0)
				{
					$result[$v['field']][$v['lang']] = $v['value'];
				}
			}
		}
		return $result;
	}

	/**
	 * 规格基础值映射（基础值md5 => 原文、用于快照还原默认语言与key匹配场景）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [int]             $goods_id [商品id]
	 * @return  [array]                     [md5 => 原文值]
	 */
	public static function SpecBaseValueMap($goods_id)
	{
		$result = [];
		$rows = Db::name('GoodsSpecType')->where(['goods_id'=>intval($goods_id)])->column('value');
		if(!empty($rows))
		{
			foreach($rows as $json)
			{
				$items = json_decode($json, true);
				if(!empty($items) && is_array($items))
				{
					foreach($items as $item)
					{
						if(!empty($item['name']))
						{
							$result[self::ContentKey($item['name'])] = $item['name'];
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * 当前语言展示规格（订单确认页等场景：仅本商品 business_id 翻译、无翻译回退原文）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [int]             $goods_id [商品id]
	 * @param   [array]           $spec     [基础规格（含key、type/value为默认语言原文）]
	 * @return  [array]                     [当前语言的展示规格]
	 */
	public static function SpecShowData($goods_id, $spec)
	{
		$result = [];
		if(!empty($spec) && is_array($spec))
		{
			$is_handle = self::IsHandle();
			$lang = self::CurrentLang();
			$values = $is_handle ? self::ValuesBatch('goods', [$goods_id]) : [];
			$gv = isset($values[$goods_id]) ? $values[$goods_id] : [];

			foreach($spec as $v)
			{
				$item = ['type'=>(isset($v['type']) ? $v['type'] : ''), 'value'=>(isset($v['value']) ? $v['value'] : '')];
				if($is_handle && !empty($v['key']))
				{
					// 名称：仅本商品
					$name_field = 'spec_name_'.self::ContentKey($item['type']);
					$name = isset($gv[$name_field][$lang]) ? $gv[$name_field][$lang] : '';
					if($name !== '')
					{
						$item['type'] = $name;
					}

					// 值：仅本商品
					$value_field = 'spec_value_'.self::ContentKey($item['value']);
					$val = isset($gv[$value_field][$lang]) ? $gv[$value_field][$lang] : '';
					if($val !== '')
					{
						$item['value'] = $val;
					}
				}
				$result[] = $item;
			}
		}
		return $result;
	}

	/**
	 * 规格模板名称翻译数据（所有模板、内容md5键聚合）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public static function SpecTemplateNameMaps()
	{
		static $result = null;
		if(is_null($result))
		{
			$result = [];
			$rows = Db::name('I18nValue')->where(['table_name'=>'goods_spec_template'])->field('field,lang,value')->select()->toArray();
			foreach($rows as $v)
			{
				if(strpos($v['field'], 'spec_name_') === 0)
				{
					$result[$v['field']][$v['lang']] = $v['value'];
				}
			}
		}
		return $result;
	}

	/**
	 * 规格key反解为基础规格（默认语言、匹配与快照语言无关）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [int]             $goods_id [商品id]
	 * @param   [array]           $spec     [提交的规格（含key）]
	 * @return  [array]                     [基础规格 type=维度名 value=值（均为原文）、保留key]
	 */
	public static function SpecBaseSpecResolve($goods_id, $spec)
	{
		static $goods_spec_maps = [];
		$goods_id = intval($goods_id);
		if(!array_key_exists($goods_id, $goods_spec_maps))
		{
			$maps = [];
			$rows = Db::name('GoodsSpecType')->where(['goods_id'=>$goods_id])->field('name,value')->select()->toArray();
			foreach($rows as $v)
			{
				$items = json_decode($v['value'], true);
				if(!empty($items) && is_array($items))
				{
					foreach($items as $item)
					{
						if(!empty($item['name']))
						{
							// key => [基础维度名, 基础值]
							$maps[self::ContentKey($item['name'])] = [$v['name'], $item['name']];
						}
					}
				}
			}
			$goods_spec_maps[$goods_id] = $maps;
		}

		$result = [];
		foreach($spec as $v)
		{
			if(!empty($v['key']) && isset($goods_spec_maps[$goods_id][$v['key']]))
			{
				$result[] = [
					'type'  => $goods_spec_maps[$goods_id][$v['key']][0],
					'value' => $goods_spec_maps[$goods_id][$v['key']][1],
					'key'   => $v['key'],
				];
			} else {
				// 无key或未匹配、原样保留（走值串兼容逻辑）
				$result[] = $v;
			}
		}
		return $result;
	}

	/**
	 * 规格值翻译（接口输出场景、原文 => 当前语言显示值，仅本商品 business_id）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [int]             $goods_id [商品id]
	 * @param   [array]           $values   [原文值集合]
	 * @return  [array]                     [原文 => 显示值]
	 */
	public static function SpecValueTranslateMap($goods_id, $values)
	{
		$result = [];
		if(!empty($values) && self::IsHandle())
		{
			$lang = self::CurrentLang();
			$gv = self::ValuesBatch('goods', [$goods_id]);
			$gv = isset($gv[$goods_id]) ? $gv[$goods_id] : [];
			foreach($values as $base)
			{
				$field = 'spec_value_'.self::ContentKey($base);
				$val = isset($gv[$field][$lang]) ? $gv[$field][$lang] : '';
				if($val !== '')
				{
					$result[$base] = $val;
				}
			}
		}
		return $result;
	}

	/**
	 * 商品规格名称多语言替换（仅本商品 business_id，无翻译保留原文）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [array]           $list       [规格类型列表（含name）]
	 * @param   [string]          $table_name [业务表名、默认 goods]
	 */
	public static function GoodsSpecNameHandle(&$list, $table_name = 'goods')
	{
		if(!empty($list) && is_array($list) && self::IsHandle())
		{
			$lang = self::CurrentLang();
			$values = self::ValuesBatch(empty($table_name) ? 'goods' : $table_name, array_unique(array_column($list, 'goods_id')));

			foreach($list as $k=>&$v)
			{
				$gv = isset($values[$v['goods_id']]) ? $values[$v['goods_id']] : [];
				$field = 'spec_name_'.self::ContentKey($v['name']);
				$name = isset($gv[$field][$lang]) ? $gv[$field][$lang] : '';
				if($name !== '')
				{
					$v['name'] = $name;
				}
			}
		}
	}

	/**
	 * 多语言数据保存（全量覆盖该业务数据的翻译）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [string]          $table_name  [业务表名]
	 * @param   [int]             $business_id [业务数据id]
	 * @param   [array]           $data        [多语言数据 field => lang => value]
	 * @param   [string]          $plugins     [插件标识，空则尝试读请求 i18n_plugins]
	 */
	public static function SaveData($table_name, $business_id, $data, $plugins = '')
	{
		// 键值型表允许business_id=0（config/plugins_config）；行表动态键随传入的 business_id 存取
		$has_dynamic_field_save = !empty($table_name) && in_array('dynamic', self::FieldsConfig($table_name));
		if(empty($table_name) || (empty($business_id) && !$has_dynamic_field_save && !in_array($table_name, ['config', 'plugins_config'])))
		{
			return;
		}

		// 插件标识（插件表单显式传入 / 隐藏域 i18n_plugins；系统表为空）
		$plugins = trim(strval($plugins));
		if($plugins === '')
		{
			$plugins = self::RequestPlugins();
		}

		// 删除原数据（含动态字段索引、避免索引变化产生冗余数据）
		// config/plugins_config/order_express/plugins_supplier_order_express：按本次提交字段精准删除（同订单多条快递备注共用 business_id、全删会误清其它快递翻译）
		if($table_name == 'config' || $table_name == 'plugins_config' || $table_name == 'order_express' || $table_name == 'plugins_supplier_order_express')
		{
			$fields_config = self::FieldsConfig($table_name);
			$del_fields = [];
			$del_bases = [];
			foreach((is_array($data) ? $data : []) as $field=>$tmp)
			{
				$type = null;
				if($table_name == 'plugins_config')
				{
					$type = self::PluginsConfigFieldType($field, $plugins);
				} else if(array_key_exists($field, $fields_config)) {
					$type = $fields_config[$field];
				} else if(self::IsDynamicField($field, $fields_config)) {
					$type = 'dynamic';
				}
				if($type === null)
				{
					continue;
				}
				if($type == 'dynamic')
				{
					// 提交的可能是基础名本身（未带内容寻址后缀）、精确删避免唯一键冲突
					if(!in_array($field, $del_fields))
					{
						$del_fields[] = $field;
					}
					// order_express / plugins_supplier_order_express：字段键已含快递id+单号md5，多快递共用 business_id，禁止按 note_% 全清
					if($table_name == 'order_express' || $table_name == 'plugins_supplier_order_express')
					{
						continue;
					}
					// 动态键删除同基础名全部变体（内容可能变化）
					$base = $field;
					while(preg_match('/^(.+)_(\d+|[a-f0-9]{32})$/', $base, $match))
					{
						$base = $match[1];
					}
					if(!in_array($base, $del_bases))
					{
						$del_bases[] = $base;
					}
					if(!in_array($base, $del_fields))
					{
						$del_fields[] = $base;
					}
				} else if(!in_array($field, $del_fields)) {
					$del_fields[] = $field;
				}
			}
			$del_where = ['table_name'=>$table_name, 'business_id'=>intval($business_id)];
			if($table_name == 'plugins_config' && $plugins !== '')
			{
				$del_where['plugins'] = $plugins;
			}
			if(!empty($del_fields))
			{
				Db::name('I18nValue')->where($del_where)->where('field', 'in', $del_fields)->delete();
			}
			foreach($del_bases as $base)
			{
				Db::name('I18nValue')->where($del_where)->whereLike('field', $base.'\_%')->delete();
			}
		} else {
			$fields_config = self::FieldsConfig($table_name);
			$bid = intval($business_id);
			if($bid > 0)
			{
				// 行全量覆盖（含该行动态键，规格/参数等归属本商品，不进 0 池）
				Db::name('I18nValue')->where(['table_name'=>$table_name, 'business_id'=>$bid])->delete();
				// 兼容清理：历史错误写入 0 池的同名字段，按本次提交精确删除，避免继续串给其它行
				$del_zero_fields = [];
				foreach((is_array($data) ? $data : []) as $field=>$tmp)
				{
					if(self::ResolveFieldType($table_name, $field, $fields_config, $plugins) == 'dynamic')
					{
						$del_zero_fields[] = $field;
					}
				}
				if(!empty($del_zero_fields))
				{
					Db::name('I18nValue')->where(['table_name'=>$table_name, 'business_id'=>0])->where('field', 'in', $del_zero_fields)->delete();
				}
			} else {
				// business_id=0 按本次字段精准删除
				$del_zero_fields = [];
				foreach((is_array($data) ? $data : []) as $field=>$tmp)
				{
					if(self::ResolveFieldType($table_name, $field, $fields_config, $plugins) !== null)
					{
						$del_zero_fields[] = $field;
					}
				}
				if(!empty($del_zero_fields))
				{
					Db::name('I18nValue')->where(['table_name'=>$table_name, 'business_id'=>0])->where('field', 'in', $del_zero_fields)->delete();
				}
			}
		}
		if(!empty($data) && is_array($data))
		{
			$fields_config = self::FieldsConfig($table_name);
			if(!empty($fields_config))
			{
				// 语言白名单
				$allow_lang_list = array_column(self::AdminLanguageList(), 'name', 'code');
				$time = time();
				$insert = [];
				$save_bid = intval($business_id);
				foreach($data as $field=>$langs)
				{
					if(!is_array($langs))
					{
						continue;
					}

					// 字段类型校验（动态列表字段为 字段名_序号 / 字段名_内容md5 / 字段名_序号_内容md5）
					$type = self::ResolveFieldType($table_name, $field, $fields_config, $plugins);
					if($type === null)
					{
						continue;
					}

					foreach($langs as $lang=>$value)
					{
						if(!array_key_exists($lang, $allow_lang_list) || !is_string($value) || $value === '')
						{
							continue;
						}

						// 富文本内容处理、与主表存储保持一致
						if($type == 'editor')
						{
							$value = str_replace("\n", '', ResourcesService::ContentStaticReplace(htmlspecialchars_decode($value), 'add'));
						}
						// 有业务行 id 时动态键也归属该行（商品规格/参数等禁止进 0 池互串）；仅配置/内容池（business_id=0）才写 0 池
						$insert[] = [
							'lang'        => $lang,
							'table_name'  => $table_name,
							'business_id' => $save_bid,
							'plugins'     => $plugins,
							'field'       => $field,
							'value'       => $value,
							'add_time'    => $time,
						];
					}
				}
				if(!empty($insert))
				{
					// 同批去重（插件配置共用 id=0 池时，会话可能带入重复键）
					$uniq = [];
					foreach($insert as $row)
					{
						$uniq[$row['lang'].'-'.$row['table_name'].'-'.$row['business_id'].'-'.$row['plugins'].'-'.$row['field']] = $row;
					}
					Db::name('I18nValue')->insertAll(array_values($uniq));
				}
			}
		}
	}

	/**
	 * 多语言数据删除
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 * @param   [string]          $table_name   [业务表名]
	 * @param   [array|int]       $business_ids [业务数据id集合]
	 */
	public static function DeleteData($table_name, $business_ids)
	{
		if(!empty($table_name) && !empty($business_ids))
		{
			$ids = is_array($business_ids) ? $business_ids : [$business_ids];
			Db::name('I18nValue')->where(['table_name'=>$table_name])->where('business_id', 'in', $ids)->delete();
		}
	}

	/**
	 * 清理商品分类相关缓存（含多语言缓存key）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public static function GoodsCategoryCacheClear()
	{
		$key = SystemService::CacheKey('shopxo.cache_goods_category_key');
		$lang_list = array_merge([self::DefaultLang()], array_column(MultilingualService::MultilingualCanChooseList(), 'code'));
		foreach($lang_list as $lang)
		{
			MyCache($key.'_'.$lang, null);
		}
	}
}
?>