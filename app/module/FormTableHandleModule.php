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
namespace app\module;

use think\facade\Db;
use app\service\FormTableService;
use app\service\ResourcesService;
use app\service\UserService;
use app\service\GoodsService;

/**
 * 动态表格处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-02
 * @desc    description
 */
class FormTableHandleModule
{
    // 模块对象
    public $module_obj;
    // form 配置数据
    public $form_data;
    // 外部参数
    public $out_params;
    // 条件参数
    public $where_params;
    // md5key
    public $md5_key;
    // 搜索条件
    public $where;
    // 用户选择字段字段
    public $user_fields;
    // 排序
    public $order_by;
    // 自定义属性条件
    public $condition_base_where;

    // 当前系统操作名称
    public $module_name;
    public $controller_name;
    public $action_name;

    // 当前插件操作名称
    public $plugins_module_name;
    public $plugins_controller_name;
    public $plugins_action_name;

    // 分页信息
    public $page;
    public $page_start;
    public $page_size;
    public $page_total;
    public $page_html;
    public $page_url;

    // 数据db
    public $db;

    // 列表数据及详情
    public $data_total;
    public $data_list;
    public $data_detail;

    // 是否导出excel
    public $is_export_excel;

    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     * @param   [string]          $module     [模块位置]
     * @param   [string]          $action     [模块方法（默认 Index/Run 方法，可自动匹配控制器方法名）]
     * @param   [mixed]           $params     [参数数据]
     */
    public function Run($module, $action = 'Index', $params = [])
    {
        // 参数校验
        $ret = $this->ParamsCheckHandle($module, $action, $params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 钩子-开始
        $hv = explode('\\', $module);
        if(isset($hv[2]) && isset($hv[4]) && in_array($hv[2], MyConfig('shopxo.module_form_hook_group')))
        {
            // 动态钩子名称 plugins_module_form_group_controller_action
            $hook_name = 'plugins_module_form_'.strtolower($hv[2]).'_'.strtolower($hv[4]).'_'.strtolower($action);
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $this->out_params,
                'data'          => &$this->form_data,
            ]);
        }

        // 初始化
        $this->Init();

        // md5key
        $this->FromMd5Key($module, $action);

        // 基础条件
        $this->BaseWhereHandle();

        // 表格配置处理
        $this->FormConfigHandle();

        // 基础数据结尾处理
        $this->FormBaseLastHandle();

        // 用户字段选择处理
        $this->FormFieldsUserSelect();

        // 排序字段处理
        $this->FormOrderByHandle();

        // 数据读取
        $this->FormDataListQuery();

        // 数据列表处理
        $this->FormDataListHandle();

        // 导出excel处理
        $this->FormDataExportExcelHandle();

        // 数据返回
        $data = [
            'table'         => $this->form_data,
            'where'         => $this->where,
            'params'        => $this->where_params,
            'md5_key'       => $this->md5_key,
            'user_fields'   => $this->user_fields,
            'order_by'      => $this->order_by,
            'page'          => $this->page,
            'page_start'    => $this->page_start,
            'page_size'     => $this->page_size,
            'page_total'    => $this->page_total,
            'page_url'      => $this->page_url,
            'page_html'     => $this->page_html,
            'data_total'    => $this->data_total,
            'data_list'     => $this->data_list,
            'data_detail'   => $this->data_detail,
        ];

        // 钩子-结束
        $hv = explode('\\', $module);
        if(isset($hv[2]) && isset($hv[4]) && in_array($hv[2], MyConfig('shopxo.module_form_hook_group')))
        {
            // 动态钩子名称 plugins_module_form_group_controller_action_end
            $hook_name = 'plugins_module_form_'.strtolower($hv[2]).'_'.strtolower($hv[4]).'_'.strtolower($action).'_end';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $this->out_params,
                'data'          => &$data,
            ]);
        }

        return DataReturn('success', 0, $data);
    }

    /**
     * 参数校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-06
     * @desc    description
     * @param   [string]          $module     [模块位置]
     * @param   [string]          $action     [模块方法（默认 Run 方法，可自动匹配控制器方法名）]
     * @param   [mixed]           $params     [参数数据]
     */
    public function ParamsCheckHandle($module, $action, $params)
    {
        // 参数
        $this->out_params = $params;

        // 模块是否存在
        if(!class_exists($module))
        {
            return DataReturn('表格模块未定义['.$module.']', -1);
        }

        // 指定方法检测
        $this->module_obj = new $module($this->out_params);
        if(!method_exists($this->module_obj, $action))
        {
            // 默认方法检测
            $action = 'Run';
            if(!method_exists($this->module_obj, $action))
            {
                return DataReturn('表格方法未定义['.$module.'->'.$action.'()]', -1);
            }
        }

        // 获取表格配置数据
        $this->form_data = $this->module_obj->$action($this->out_params);
        if(empty($this->form_data['base']) || !is_array($this->form_data['base']) || empty($this->form_data['form']) || !is_array($this->form_data['form']))
        {
            return DataReturn('表格配置有误['.$module.'][base|form]', -1);
        }

        // 数据唯一主字段
        if(empty($this->form_data['base']['key_field']))
        {
            return DataReturn('表格唯一字段配置有误['.$module.']base->[key_field]', -1);
        }

        // 是否上下居中（0否,1是）默认1
        if(!isset($this->form_data['base']['is_middle']))
        {
            $this->form_data['base']['is_middle'] = 1;
        }

        return DataReturn('success', 0);
    }

    /**
     * 初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-29
     * @desc    description
     */
    public function Init()
    {
        // 排序
        $this->order_by['key'] = empty($this->out_params['fp_order_by_key']) ? (empty($this->out_params['order_by_key']) ? '' : $this->out_params['order_by_key']) : $this->out_params['fp_order_by_key'];
        $this->order_by['val'] = empty($this->out_params['fp_order_by_val']) ? (empty($this->out_params['order_by_val']) ? '' : $this->out_params['order_by_val']) : $this->out_params['fp_order_by_val'];
        $this->order_by['field'] = '';
        $this->order_by['data'] = '';

        // 分页信息
        $this->page = max(1, isset($this->out_params['page']) ? intval($this->out_params['page']) : 1);
        $this->page_size = min(empty($this->out_params['page_size']) ? MyC('common_page_size', 10, true) : intval($this->out_params['page_size']), 1000);

        // 当前系统操作名称、是否指定模块
        $this->module_name = empty($this->out_params['module_name']) ? RequestModule() : $this->out_params['module_name'];
        $this->controller_name = empty($this->out_params['controller_name']) ? RequestController() : $this->out_params['controller_name'];
        $this->action_name = empty($this->out_params['action_name']) ? RequestAction() : $this->out_params['action_name'];

        // 是否开启删除
        $is_delete = isset($this->form_data['base']['is_delete']) && $this->form_data['base']['is_delete'] == 1;
        // 是否开启全部删除
        $is_alldelete = isset($this->form_data['base']['is_alldelete']) && $this->form_data['base']['is_alldelete'] == 1;
        // 删除数据key默认ids
        if($is_delete && empty($this->form_data['base']['delete_key']))
        {
            $this->form_data['base']['delete_key'] = 'ids';
        }

        // 当前插件操作名称, 兼容插件模块名称
        if(empty($this->out_params['pluginsname']))
        {
            // 默认插件模块赋空值
            $this->plugins_module_name = '';
            $this->plugins_controller_name = '';
            $this->plugins_action_name = '';

            // 当前页面地址
            $this->page_url = MyUrl($this->module_name.'/'.$this->controller_name.'/'.$this->action_name);

            // 已开启删除功能未配置删除数据地址
            if($is_delete && empty($this->form_data['base']['delete_url']))
            {
                $this->form_data['base']['delete_url'] = MyUrl($this->module_name.'/'.$this->controller_name.'/delete');
            }

            // 已开启全部删除功能未配置删除数据地址
            if($is_alldelete && empty($this->form_data['base']['alldelete_url']))
            {
                $this->form_data['base']['alldelete_url'] = MyUrl($this->module_name.'/'.$this->controller_name.'/alldelete');
            }
        } else {
            // 处理插件页面模块
            $this->plugins_module_name = $this->out_params['pluginsname'];
            $this->plugins_controller_name = empty($this->out_params['pluginscontrol']) ? 'index' : $this->out_params['pluginscontrol'];
            $this->plugins_action_name = empty($this->out_params['pluginsaction']) ? 'index' : $this->out_params['pluginsaction'];

            // 当前页面地址
            if($this->module_name == 'admin')
            {
                $this->page_url = PluginsAdminUrl($this->plugins_module_name, $this->plugins_controller_name, $this->plugins_action_name);
            } else {
                $this->page_url = PluginsHomeUrl($this->plugins_module_name, $this->plugins_controller_name, $this->plugins_action_name);
            }

            // 已开启删除功能未配置删除数据地址
            if($is_delete && empty($this->form_data['base']['delete_url']))
            {
                if($this->module_name == 'admin')
                {
                    $this->form_data['base']['delete_url'] = PluginsAdminUrl($this->plugins_module_name, $this->plugins_controller_name, 'delete');
                } else {
                    $this->form_data['base']['delete_url'] = PluginsHomeUrl($this->plugins_module_name, $this->plugins_controller_name, 'delete');
                }
            }

            // 已开启全部删除功能未配置删除数据地址
            if($is_alldelete && empty($this->form_data['base']['alldelete_url']))
            {
                if($this->module_name == 'admin')
                {
                    $this->form_data['base']['alldelete_url'] = PluginsAdminUrl($this->plugins_module_name, $this->plugins_controller_name, 'alldelete');
                } else {
                    $this->form_data['base']['alldelete_url'] = PluginsHomeUrl($this->plugins_module_name, $this->plugins_controller_name, 'alldelete');
                }
            }
        }

        // 是否开启搜索
        if(isset($this->form_data['base']['is_search']) && $this->form_data['base']['is_search'] == 1)
        {
            // 是否设置搜索重置链接
            if(empty($this->form_data['base']['search_url']))
            {
                $this->form_data['base']['search_url'] = $this->page_url;
            }
        }

        // 是否导出excel
        $this->is_export_excel = (isset($this->out_params['form_table_is_export_excel']) && $this->out_params['form_table_is_export_excel'] == 1);

        // 是否定义基础条件属性
        $this->condition_base_where = (property_exists($this->module_obj, 'condition_base') && is_array($this->module_obj->condition_base)) ? $this->module_obj->condition_base : [];
    }

    /**
     * 数据读取
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     */
    public function FormDataListQuery()
    {
        // 已定义读取数据配置，指定读取数据、未指定则默认读取
        if(!empty($this->form_data['data']) && (!isset($this->out_params['is_data_query']) || $this->out_params['is_data_query'] == 1))
        {
            $form_data = $this->form_data['data'];

            // 基础数据
            $base = $this->FormDataBase($form_data);

            // 非列表和详情则不处理
            if(!$base['is_list'] && !$base['is_detail'])
            {
                return false;
            }

            // 数据库对象
            $this->db = null;
            if(!empty($form_data['table_obj']) && is_object($form_data['table_obj']))
            {
                $this->db = $form_data['table_obj'];
            } elseif(!empty($form_data['table_name']))
            {
                $this->db = Db::name($form_data['table_name']);
            }
            if($this->db === null)
            {
                return false;
            }

            // 读取字段
            $select_field = empty($form_data['select_field']) ? '*' : $form_data['select_field'];
            $this->db->field($select_field);

            // 数据读取
            if($base['is_list'])
            {
                // 加入条件
                $this->db->where($this->where);

                // 总数
                // 是否去重
                if(empty($form_data['distinct']))
                {
                    $this->data_total = (int) $this->db->count();
                } else {
                    $this->data_total = (int) $this->db->count('DISTINCT '.$form_data['distinct']);
                }
                if($this->data_total > 0)
                {
                    // 分页总数、分页不能大于总数
                    $this->page_total = ceil($this->data_total/$this->page_size);
                    if($this->page > $this->page_total)
                    {
                        $this->page = $this->page_total;
                    }

                    // 增加排序、未设置则默认[ id desc ]
                    $order_by = empty($this->order_by['data']) ? (array_key_exists('order_by', $form_data) ? $form_data['order_by'] : 'id desc') : $this->order_by['data'];
                    if(!empty($order_by))
                    {
                        $this->db->order($order_by);
                    }

                    // 分组
                    if(!empty($form_data['group']))
                    {
                        $this->db->group($form_data['group']);
                    }

                    // 增加分页
                    if($base['is_page'] && !$this->is_export_excel)
                    {
                        $this->page_start = intval(($this->page-1)*$this->page_size);
                        $this->db->limit($this->page_start, $this->page_size);
                    }
                    // 读取数据
                    $this->data_list = $this->db->select()->toArray();
                }
            } else {
                // 详情不走列表的条件模式
                // 1. 传入外部条件和指定详情条件
                // 2. 合并指定的属性条件
                $this->where = array_merge($this->condition_base_where, empty($this->out_params['detail_where']) ? ((!empty($form_data['detail_where']) && is_array($form_data['detail_where'])) ? $form_data['detail_where'] : []) : $this->out_params['detail_where']);

                // 单独处理条件
                $detail_dkey = empty($this->out_params['detail_dkey']) ? (empty($form_data['detail_dkey']) ? 'id' : $form_data['detail_dkey']) : $this->out_params['detail_dkey'];
                $detail_pkey = empty($this->out_params['detail_pkey']) ? (empty($form_data['detail_pkey']) ? 'id' : $form_data['detail_pkey']) : $this->out_params['detail_pkey'];
                $value = empty($this->out_params[$detail_pkey]) ? 0 : $this->out_params[$detail_pkey];
                $this->where[] = [$detail_dkey, '=', $value];
                $this->db->where($this->where);

                // 读取数据、仅读取一条
                $this->data_list = $this->db->limit(0, 1)->select()->toArray();
            }
        }
    }

    /**
     * 数据列表处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     */
    public function FormDataListHandle()
    {
        if(!empty($this->data_list))
        {
            // 表单配置数据
            $form_data = empty($this->form_data['data']) ? [] : $this->form_data['data'];

            // 基础数据
            $base = $this->FormDataBase($form_data);

            // 数据处理
            // 合并数据
            $data_merge = (!empty($form_data['data_merge']) && is_array($form_data['data_merge'])) ? $form_data['data_merge'] : [];

            // 是否处理用户数据
            $is_handle_user_field = isset($form_data['is_handle_user_field']) && $form_data['is_handle_user_field'] == 1;
            $handle_user_data = [];
            $handle_user_ids = [];
            if(empty($form_data['handle_user_data']))
            {
                $handle_user_data[] = [
                        'key'    => 'user_id',
                        'field'  => 'user',
                    ];
                $handle_user_ids = array_unique(array_filter(array_column($this->data_list, 'user_id')));
            } else {
                if(is_array($form_data['handle_user_data']))
                {
                    foreach($form_data['handle_user_data'] as $uv)
                    {
                        if(!empty($uv['key']))
                        {
                            if(empty($uv['field']))
                            {
                                $temp_user_field = (substr($uv['key'], -3) == '_id') ? substr($uv['key'], 0, -3) : $uv['key'].'_user';
                            } else {
                                $temp_user_field = $uv['field'];
                            }
                            $handle_user_data[] = [
                                    'key'    => $uv['key'],
                                    'field'  => $temp_user_field,
                                ];
                            $handle_user_ids = array_unique(array_merge($handle_user_ids, array_filter(array_column($this->data_list, $uv['key']))));
                        }
                    }
                }
            }

            // 是否处理商品数据
            $is_handle_goods_field = isset($form_data['is_handle_goods_field']) && $form_data['is_handle_goods_field'] == 1;
            $handle_goods_data = [];
            $handle_goods_ids = [];
            if(empty($form_data['handle_goods_data']))
            {
                $handle_goods_data[] = [
                        'key'    => 'goods_id',
                        'field'  => 'goods',
                    ];
                $handle_goods_ids = array_unique(array_filter(array_column($this->data_list, 'goods_id')));
            } else {
                if(is_array($form_data['handle_goods_data']))
                {
                    foreach($form_data['handle_goods_data'] as $uv)
                    {
                        if(!empty($uv['key']))
                        {
                            if(empty($uv['field']))
                            {
                                $temp_goods_field = (substr($uv['key'], -3) == '_id') ? substr($uv['key'], 0, -3) : $uv['key'].'_goods';
                            } else {
                                $temp_goods_field = $uv['field'];
                            }
                            $handle_goods_data[] = [
                                    'key'    => $uv['key'],
                                    'field'  => $temp_goods_field,
                                ];
                            $handle_goods_ids = array_unique(array_merge($handle_goods_ids, array_filter(array_column($this->data_list, $uv['key']))));
                        }
                    }
                }
            }

            // 时间字段和格式
            $is_handle_time_field = isset($form_data['is_handle_time_field']) && $form_data['is_handle_time_field'] == 1;
            $handle_time_format = empty($form_data['handle_time_format']) ? '' : $form_data['handle_time_format'];

            // 固定值名称
            $is_fixed_name_field = isset($form_data['is_fixed_name_field']) && $form_data['is_fixed_name_field'] == 1;
            $fixed_name_data = empty($form_data['fixed_name_data']) ? [] : $form_data['fixed_name_data'];

            // 附件字段
            $is_handle_annex_field = isset($form_data['is_handle_annex_field']) && $form_data['is_handle_annex_field'] == 1;
            $handle_annex_fields = empty($form_data['handle_annex_fields']) ? ['cover', 'logo', 'icon', 'images', 'images_url', 'video', 'video_url'] : (is_array($form_data['handle_annex_fields']) ? $form_data['handle_annex_fields'] : explode(',', $form_data['handle_annex_fields']));

            // 附件字节转单位
            $is_handle_annex_size_unit = isset($form_data['is_handle_annex_size_unit']) && $form_data['is_handle_annex_size_unit'] == 1;
            $handle_annex_size_unit_fields = empty($form_data['handle_annex_size_unit_fields']) ? ['size', 'file_size', 'images_size', 'image_size', 'video_size'] : (is_array($form_data['handle_annex_size_unit_fields']) ? $form_data['handle_annex_size_unit_fields'] : explode(',', $form_data['handle_annex_size_unit_fields']));

            // json数据
            $is_json_data_handle = isset($form_data['is_json_data_handle']) && $form_data['is_json_data_handle'] == 1;
            $json_config_data = empty($form_data['json_config_data']) ? [] : $form_data['json_config_data'];

            // 换行数据转数组
            $is_ln_to_array_handle = isset($form_data['is_ln_to_array_handle']) && $form_data['is_ln_to_array_handle'] == 1;
            $ln_to_array_fields = empty($form_data['ln_to_array_fields']) ? [] : $form_data['ln_to_array_fields'];

            // 1. 展示字段指定数组转换处理
            // 2. 状态字段按照搜索列表转换处理
            $field_show_data = [];
            $field_status_data = [];
            foreach($this->form_data['form'] as $fv)
            {
                switch($fv['view_type'])
                {
                    // 展示字段
                    case 'field' :
                        if(!empty($fv['view_data']))
                        {
                            $field_show_data[$fv['view_key']] = $fv;
                        }
                        break;

                    // 状态字段
                    case 'status' :
                        if(!empty($fv['search_config']) && !empty($fv['search_config']['data']))
                        {
                            $field_status_data[$fv['view_key']] = $fv;
                        }
                        break;
                }
            }

            // 数据处理
            if(!empty($field_show_data) || !empty($field_status_data) || !empty($data_merge) || $is_handle_user_field || $is_handle_goods_field || $is_handle_time_field || $is_fixed_name_field || $is_handle_annex_field || $is_handle_annex_size_unit || $is_json_data_handle || $is_ln_to_array_handle)
            {
                // 获取用户数据
                $user_data = [];
                if($is_handle_user_field && !empty($handle_user_ids))
                {
                    $user_data = UserService::GetUserViewInfo($handle_user_ids);
                }

                // 获取商品数据
                $goods_data = [];
                if($is_handle_goods_field && !empty($handle_goods_ids))
                {
                    $goods_data = Db::name('Goods')->where(['id'=>$handle_goods_ids])->column('id,title,images,price,min_price,max_price,original_price,min_original_price,max_original_price,inventory,inventory_unit', 'id');
                    if(!empty($goods_data))
                    {
                        $goods_data = array_map(function($item)
                        {
                            $item['goods_url'] = GoodsService::GoodsUrlCreate($item['id']);
                            $item['images'] = ResourcesService::AttachmentPathViewHandle($item['images']);
                            return $item;
                        }, $goods_data);
                    }
                }

                // 处理列表数据
                foreach($this->data_list as &$v)
                {
                    if(!empty($v) && is_array($v))
                    {
                        // 合并数据
                        if(!empty($data_merge))
                        {
                            $v = array_merge($v, $data_merge);
                        }

                        // 用户信息处理
                        if($is_handle_user_field && !empty($handle_user_data))
                        {
                            foreach($handle_user_data as $uv)
                            {
                                if(!empty($uv['key']) && !empty($uv['field']))
                                {
                                    $v[$uv['field']] = (empty($user_data) || empty($v[$uv['key']]) || !array_key_exists($v[$uv['key']], $user_data)) ? null : $user_data[$v[$uv['key']]];
                                }
                            }
                        }

                        // 商品信息处理
                        if($is_handle_goods_field && !empty($handle_goods_data))
                        {
                            foreach($handle_goods_data as $uv)
                            {
                                if(!empty($uv['key']) && !empty($uv['field']))
                                {
                                    $v[$uv['field']] = (empty($goods_data) || empty($v[$uv['key']]) || !array_key_exists($v[$uv['key']], $goods_data)) ? null : $goods_data[$v[$uv['key']]];
                                }
                            }
                        }

                        // 其他单独字段数据处理
                        foreach($v as $ks=>&$vs)
                        {
                            // json数据处理
                            if($is_json_data_handle && !empty($json_config_data) && array_key_exists($ks, $json_config_data) && !empty($vs) && !is_array($vs))
                            {
                                $temp_json_data = json_decode($vs, true);
                                if(!empty($temp_json_data))
                                {
                                    // 是否为附件处理地址
                                    $temm_json_config = $json_config_data[$ks];
                                    if(!empty($temm_json_config) && isset($temm_json_config['type']) && $temm_json_config['type'] == 'annex')
                                    {
                                        foreach($temp_json_data as &$jsonv)
                                        {
                                            if(!empty($jsonv))
                                            {
                                                // 是否指定多个字段
                                                if(empty($temm_json_config['key']))
                                                {
                                                    if(!is_array($jsonv))
                                                    {
                                                        $jsonv = ResourcesService::AttachmentPathViewHandle($jsonv);
                                                    }
                                                } else {
                                                    // 多个字段处理，必须存在值
                                                    if(is_array($jsonv))
                                                    {
                                                        // key是否数组
                                                        if(is_array($temm_json_config['key']))
                                                        {
                                                            foreach($temm_json_config['key'] as $jckv)
                                                            {
                                                                if(!empty($jsonv[$jckv]) && !is_array($jsonv[$jckv]))
                                                                {
                                                                    $jsonv[$jckv] = ResourcesService::AttachmentPathViewHandle($jsonv[$jckv]);
                                                                }
                                                            }
                                                        } else {
                                                            // key单个字段
                                                            if(!empty($jsonv[$temm_json_config['key']]) && !is_array($jsonv[$temm_json_config['key']]))
                                                            {
                                                                $jsonv[$temm_json_config['key']] = ResourcesService::AttachmentPathViewHandle($jsonv[$temm_json_config['key']]);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $vs = $temp_json_data;
                            }

                            // 时间处理
                            if($is_handle_time_field && (substr($ks, -5) == '_time' || (!empty($handle_time_format) && is_array($handle_time_format) && array_key_exists($ks, $handle_time_format))))
                            {
                                $format = empty($handle_time_format) ? 'Y-m-d H:i:s' : (is_array($handle_time_format) ? (empty($handle_time_format[$ks]) ? 'Y-m-d H:i:s' : $handle_time_format[$ks]) : $handle_time_format);
                                $vs = empty($vs) ? '' : (is_numeric($vs) ? date($format, $vs) : $vs);
                            }

                            // 固定值名称处理
                            if($is_fixed_name_field && !empty($fixed_name_data) && is_array($fixed_name_data) && array_key_exists($ks, $fixed_name_data) && !empty($fixed_name_data[$ks]['data']))
                            {
                                $temp_data = $fixed_name_data[$ks]['data'];
                                $temp_field = empty($fixed_name_data[$ks]['field']) ? $ks.'_name' : $fixed_name_data[$ks]['field'];
                                $temp_key = empty($fixed_name_data[$ks]['key']) ? 'name' : $fixed_name_data[$ks]['key'];
                                // 是否数组
                                if(is_array($vs))
                                {
                                    $temp_arr = [];
                                    foreach($vs as $fixed_v)
                                    {
                                        if(!is_array($fixed_v) && array_key_exists($fixed_v, $temp_data))
                                        {
                                            $temp = $temp_data[$fixed_v];
                                            if(is_array($temp))
                                            {
                                                if(isset($temp[$temp_key]))
                                                {
                                                    $temp_arr[] = $temp[$temp_key];
                                                }
                                            } else {
                                                $temp_arr[] = $temp;
                                            }
                                        }
                                    }
                                    $v[$temp_field] = empty($temp_arr) ? '' : implode(', ', $temp_arr);
                                } else {
                                    $temp = array_key_exists($vs, $temp_data) ? $temp_data[$vs] : '';
                                    $v[$temp_field] = empty($temp) ? '' : (is_array($temp) ? (isset($temp[$temp_key]) ? $temp[$temp_key] : '') : $temp);
                                }
                            }

                            // 附件字段处理
                            if($is_handle_annex_field && !empty($handle_annex_fields) && in_array($ks, $handle_annex_fields) && !empty($vs))
                            {
                                $vs = ResourcesService::AttachmentPathViewHandle($vs);
                            }

                            // 附件字节转单位
                            if($is_handle_annex_size_unit && !empty($handle_annex_size_unit_fields) && in_array($ks, $handle_annex_size_unit_fields) && !empty($vs))
                            {
                                $vs = AnnexSizeToUnit($vs);
                            }

                            // 展示字段指定数组转换处理、默认增加 _name 后缀
                            if(!empty($field_show_data) && array_key_exists($ks, $field_show_data))
                            {
                                $temp = $field_show_data[$ks];
                                $value = array_key_exists($vs, $temp['view_data']) ? $temp['view_data'][$vs] : null;
                                $key = $ks.'_name';
                                if($value === null)
                                {
                                    $v[$key] = '';
                                } else {
                                    if(is_array($value))
                                    {
                                        $v[$key] = (!empty($temp['view_data_key']) && array_key_exists($temp['view_data_key'], $value)) ? $value[$temp['view_data_key']] : '';
                                    } else {
                                        $v[$key] = $value;
                                    }
                                }
                            }

                            // 状态字段按照搜索列表转换处理、默认增加 _name 后缀
                            if(!empty($field_status_data) && array_key_exists($ks, $field_status_data) && !empty($field_status_data[$ks]['search_config']) && !empty($field_status_data[$ks]['search_config']['data']))
                            {
                                $temp = $field_status_data[$ks]['search_config'];
                                $value = array_key_exists($vs, $temp['data']) ? $temp['data'][$vs] : null;
                                $key = $ks.'_name';
                                if($value === null)
                                {
                                    $v[$key] = '';
                                } else {
                                    if(is_array($value))
                                    {
                                        $v[$key] = (!empty($temp['data_name']) && array_key_exists($temp['data_name'], $value)) ? $value[$temp['data_name']] : '';
                                    } else {
                                        $v[$key] = $value;
                                    }
                                }
                            }

                            // 换行转数组
                            if($is_ln_to_array_handle && !empty($ln_to_array_fields) && in_array($ks, $ln_to_array_fields))
                            {
                                $vs = empty($vs) ? [] : (is_array($vs) ? $vs : explode("\n", $vs));
                            }
                        }
                    }
                }
            }

            // 是否已定义数据处理、必须存在双冒号
            $m = $this->ServiceActionModule($form_data, 'data_handle');
            if(!empty($m))
            {
                $module = $m['module'];
                $action = $m['action'];
                // 数据请求参数
                $data_params = (!empty($form_data['data_params']) && is_array($form_data['data_params'])) ? $form_data['data_params'] : [];
                // 列表和详情自定义参数合并
                $data_params_field = $base['is_detail'] ? 'detail_params' : 'list_params';
                if(!empty($form_data[$data_params_field]))
                {
                    $data_params = array_merge($data_params, $form_data[$data_params_field]);
                }
                $res = $module::$action($this->data_list, $data_params);
                // 是否按照数据返回格式方法放回的数据
                if(isset($res['code']) && isset($res['msg']) && isset($res['data']))
                {
                    $this->data_list = $res['data'];
                } else {
                    $this->data_list = $res;
                }
            }

            // 分页处理
            if($base['is_page'] && $base['is_list'] && !$this->is_export_excel)
            {
                // 是否定义分页提示信息
                $tips_msg = '';
                $m = $this->ServiceActionModule($form_data, 'page_tips_handle');
                if(!empty($m))
                {
                    $module = $m['module'];
                    $action = $m['action'];
                    $tips_msg = $module::$action($this->where);
                }

                // 分页统计数据
                if(isset($form_data['is_page_stats']) && $form_data['is_page_stats'] == 1 && !empty($form_data['page_stats_data']) && is_array($form_data['page_stats_data']))
                {
                    // 当前数据字段列
                    $data_item_fields = (empty($this->data_list) || empty($this->data_list[0])) ? [] : array_keys($this->data_list[0]);

                    // 统计数据集合
                    $stats_data = [];
                    foreach($form_data['page_stats_data'] as $pv)
                    {
                        if(!empty($pv['name']))
                        {
                            // 数据字段
                            $field = empty($pv['field']) ? 'id' : $pv['field'];
                            $stats_fun = empty($pv['fun']) ? 'sum' : $pv['fun'];
                            $res = $this->db->field($stats_fun.'('.$field.') as value')->find();
                            $value = (empty($res) || empty($res['value'])) ? 0 : $res['value'];
                            $stats_data[] = $pv['name'].$value.(empty($pv['unit']) ? '' : $pv['unit']);
                        }
                    }
                    if(!empty($stats_data))
                    {
                        $tips_msg .= implode('&nbsp;&nbsp;&nbsp;', $stats_data);
                    }
                }

                // 分页组件
                $page_params = [
                    'number'    => $this->page_size,
                    'total'     => $this->data_total,
                    'where'     => $this->out_params,
                    'page'      => $this->page,
                    'url'       => $this->page_url,
                    'tips_msg'  => $tips_msg,
                ];
                $page = new \base\Page($page_params);
                $this->page_html = $page->GetPageHtml();
            }

            // 是否详情页
            if($base['is_detail'])
            {
                $this->data_detail = $this->data_list[0];
                $this->data_list = [];
            }
        }
    }

    /**
     * 数据列表基础
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-08
     * @desc    description
     * @param   [array]          $form_data [表格数据]
     */
    public function FormDataBase($form_data)
    {
        // 列表和详情
        $list_action = isset($form_data['list_action']) ? (is_array($form_data['list_action']) ? $form_data['list_action'] : [$form_data['list_action']]) : ['index'];
        $detail_action = isset($form_data['detail_action']) ? (is_array($form_data['detail_action']) ? $form_data['detail_action'] : [$form_data['detail_action']]) : ['detail', 'saveinfo', 'save', 'delete', 'statusupdate'];
        if(empty($this->plugins_module_name))
        {
            $is_list = in_array($this->action_name, $list_action);
            $is_detail = in_array($this->action_name, $detail_action);
        } else {
            $is_list = in_array($this->plugins_action_name, $list_action);
            $is_detail = in_array($this->plugins_action_name, $detail_action);
        }

        // 是否使用分页
        $is_page = (!isset($form_data['is_page']) || $form_data['is_page'] == 1);

        return [
            'is_list'    => $is_list,
            'is_detail'  => $is_detail,
            'is_page'    => $is_page,
        ];
    }

    /**
     * excel导出处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-20
     * @desc    description
     */
    public function FormDataExportExcelHandle()
    {
        if($this->is_export_excel)
        {
            // 错误提示
            $error_msg = '';
            if(empty($this->form_data['data']))
            {
                $error_msg = '请先定义数据配置';
            }

            // 是否存在数据
            if(empty($error_msg) && empty($this->data_list) && empty($this->data_detail))
            {
                $error_msg = '没有相关数据、请重新输入搜索条件再试！';
            }

            // 根据表单配置标题处理
            // 仅获取[ field、status、images ]类型的配置项
            $title = [];
            foreach($this->form_data['form'] as $v)
            {
                if(isset($v['view_type']) && in_array($v['view_type'], ['field', 'images', 'status']) && !empty($v['label']) && !empty($v['view_key']))
                {
                    // key避免多数组
                    $key = is_array($v['view_key']) ? $v['view_key'][0] : $v['view_key'];

                    // 数据转换字段再加 _name 后缀
                    // 1. field是否指定转换数据
                    // 2. 状态类型
                    if(($v['view_type'] == 'field' && !empty($v['view_data'])) || $v['view_type'] == 'status')
                    {
                        $key .= '_name';
                    }

                    //加入可导出容器
                    $title[$key] = [
                        'name' => $v['label'],
                        'type' => 'string',
                    ];
                }
            }
            if(empty($error_msg) && empty($title))
            {
                $error_msg = '没有相关field类型的表单配置';
            }

            // 提示错误
            if(!empty($error_msg))
            {
                die('<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8" />
                        <title>错误提示</title>
                    </head>
                    <body style="text-align:center;">
                        <p style="color:#666;font-size:14px;margin-top:10%;margin-bottom:30px;">'.$error_msg.'</p>
                        <a href="javascript:;" onClick="WindowClose()" style="text-decoration:none;color:#fff;background:#f00;padding:5px 15px;border-radius:2px;font-size:12px;">关闭页面</a>
                    </body>
                        <script type="text/javascript">
                            function WindowClose()
                            {
                                var user_agent = navigator.userAgent;
                                if(user_agent.indexOf("Firefox") != -1 || user_agent.indexOf("Chrome") != -1)
                                {
                                    location.href = "about:blank";
                                } else {
                                    window.opener = null;
                                    window.open("", "_self");
                                }
                                window.close();
                            }
                        </script>
                    </html>');
            }

            // 列表或详情数据
            $list = empty($this->data_list) ? [$this->data_detail] : $this->data_list;

            // 是否存在详情列表数据定义
            $data = [];
            if(!empty($this->form_data['detail_form_list']))
            {
                $is_table_title = true;
                $detail_form_count = count($this->form_data['detail_form_list']);
                foreach($list as $v)
                {
                    // 当前详情数据最大数记录
                    $detail_data_row_max = 0;
                    foreach($this->form_data['detail_form_list'] as $dv)
                    {
                        // 追加表头
                        if($is_table_title && !empty($dv) && !empty($dv['label']) && !empty($dv['field']) && !empty($dv['data']))
                        {
                            foreach($dv['data'] as $dvk=>$dvv)
                            {
                                $title[$dv['field'].'_'.$dvk] = [
                                    'name' => $dv['label'].' - '.$dvv,
                                    'type' => 'string',
                                ];
                            }
                        }
                        // 当前详情数据最大数记录
                        if(isset($v[$dv['field']]))
                        {
                            $temp_max = (count($v[$dv['field']]) == count($v[$dv['field']], 1)) ? 1 : count($v[$dv['field']]);
                            if($temp_max > $detail_data_row_max)
                            {
                                $detail_data_row_max = $temp_max;
                            }
                        }
                    }
                    $is_table_title = false;

                    // 根据详情数据追加数据
                    for($i=0; $i<$detail_data_row_max; $i++)
                    {
                        $temp = $v;
                        for($t=0; $t<$detail_form_count; $t++)
                        {
                            $dv = $this->form_data['detail_form_list'][$t];
                            if(!empty($v[$dv['field']]))
                            {
                                $dv_data = array_keys($dv['data']);
                                foreach($dv_data as $df)
                                {
                                    $fk = $dv['field'];
                                    $field = $dv['field'].'_'.$df;
                                    // 非二维数组则转二维数组
                                    if(count($v[$fk]) == count($v[$fk], 1))
                                    {
                                        $v[$fk] = [$v[$fk]];
                                    }
                                    // 存在数据则追加数据字段
                                    if(isset($v[$fk][$i]) && isset($v[$fk][$i][$df]))
                                    {
                                        $temp[$field] = $v[$fk][$i][$df];
                                    }
                                }
                            }
                        }
                        $data[] = $temp;
                    }
                }
            }
            if(empty($data) && !empty($list))
            {
                $data = $list;
                unset($list);
            }

            // Excel驱动导出数据
            $excel = new \base\Excel(['title'=>$title, 'data'=>$data]);
            $excel->Export();
        }
    }

    /**
     * 服务层方法模块
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-02
     * @desc    description
     * @param   [string]          $data     [模块数据]
     * @param   [string]          $field    [字段]
     */
    public function ServiceActionModule($data, $field)
    {
        $result = [];
        if(!empty($data) && !empty($data[$field]) && stripos($data[$field], '::') !== false)
        {
            $arr = explode('::', $data[$field]);
            // 是否存在命名空间反斜杠
            if(stripos($arr[0], '\\') === false)
            {
                if(empty($this->plugins_module_name))
                {
                    $module = 'app\service\\'.$arr[0];
                } else {
                    $module = 'app\plugins\\'.$this->plugins_module_name.'\service\\'.$arr[0];
                }
            } else {
                $module = $arr[0];
            }
            $action = $arr[1];
            if(class_exists($module));
            {
                if(method_exists($module, $action))
                {
                    $result = [
                        'module'    => $module,
                        'action'    => $action,
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * 排序字段处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-24
     * @desc    description
     */
    public function FormOrderByHandle()
    {
        if(!empty($this->order_by['field']) && !empty($this->order_by['val']))
        {
            $this->order_by['data'] = $this->order_by['field'].' '.$this->order_by['val'];
        }
    }

    /**
     * 字段用户选择处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-09
     * @desc    description
     */
    public function FormFieldsUserSelect()
    {
        // 用户选择字段数据
        $user_choice_fields = $this->UserChoiceFieldsData();
        $temp_user_choice_fields = array_column($user_choice_fields, null, 'key');

        // 当前用户选择的字段
        $ret = FormTableService::FieldsSelectData(['md5_key'=>$this->md5_key]);
        if(empty($ret['data']))
        {
            // 未设置则读取所有带label的字段、默认显示
            $this->user_fields = $user_choice_fields;
        } else {
            $this->user_fields = $ret['data'];
            // 将每项的数据增加进去
            foreach($this->user_fields as &$fv)
            {
                $fv['data'] = (!empty($fv['key']) && array_key_exists($fv['key'], $temp_user_choice_fields)) ? $temp_user_choice_fields[$fv['key']] : '';
            }
        }

        // 如用户已选择字段则排除数据
        if(!empty($this->user_fields))
        {
            $data = [];
            // 无标题元素放在前面
            foreach($this->form_data['form'] as $v)
            {
                if(empty($v['label']))
                {
                    $data[] = $v;
                }
            }

            // 根据用户选择顺序追加数据
            foreach($this->user_fields as $k=>$v)
            {
                // 字段不存在数据中则移除
                if(!empty($v['key']) && array_key_exists($v['key'], $temp_user_choice_fields) && !empty($temp_user_choice_fields[$v['key']]['data']))
                {
                    $temp = $temp_user_choice_fields[$v['key']]['data'];

                    // 是否存在设置不展示列表、则移除字段
                    if(isset($temp['is_list']) && $temp['is_list'] == 0)
                    {
                        unset($this->user_fields[$k]);
                    }

                    // 避免已定义了列表是否显示字段、导致覆盖成为展示
                    if(!isset($temp['is_list']))
                    {
                        $temp['is_list'] = $v['checked'];
                    }
                    $data[] = $temp;
                } else {
                    unset($this->user_fields[$k]);
                }
            }

            // 操作元素放在最后面
            foreach($this->form_data['form'] as $v)
            {
                if(isset($v['view_type']) && $v['view_type'] == 'operate')
                {
                    $data[] = $v;
                }
            }

            $this->form_data['form'] = $data;
        }
    }

    /**
     * 用户选择字段数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-06-29
     * @desc    description
     */
    public function UserChoiceFieldsData()
    {
        return array_filter(array_map(function($value)
        {
            if(!empty($value['label']) && $value['view_type'] != 'operate' && !empty($value['view_key']))
            {
                $key = is_array($value['view_key']) ? implode('-', $value['view_key']) : str_replace(['../', './', '.', '/'], ['', '', '', '-'], $value['view_key']);
                return [
                    // 基础数据
                    'label'    => $value['label'],
                    'key'      => $key,
                    // 默认选中数据
                    'checked'  => 1,
                    // 原始数据
                    'data'     => $value,
                ];
            }
        }, $this->form_data['form']));
    }

    /**
     * 表单md5key值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-08
     * @desc    description
     * @param   [string]          $module     [模块位置]
     * @param   [string]          $action     [模块方法（默认 Run 方法，可自动匹配控制器方法名）]
     */
    public function FromMd5Key($module, $action)
    {
        $this->md5_key = md5($module.'\\'.$action);
    }

    /**
     * 表格配置处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     */
    public function FormConfigHandle()
    {
        $lang = MyLang('form_table_search_first');
        foreach($this->form_data['form'] as $k=>&$v)
        {
            // 基础字段处理
            // 是否上下居中（0否,1是）默认1
            if(!isset($v['is_middle']))
            {
                $v['is_middle'] = isset($this->form_data['base']['is_middle']) ? $this->form_data['base']['is_middle'] : 1;
            }

            // 基础数据类型处理
            if(!empty($v['view_type']))
            {
                switch($v['view_type'])
                {
                    // 状态操作
                    // 复选框
                    // 单选框
                    case 'status' :
                    case 'checkbox' :
                    case 'radio' :
                        // 未指定唯一字段名称则使用基础中的唯一字段
                        if(empty($v['key_field']))
                        {
                            $v['key_field'] = $this->form_data['base']['key_field'];
                        }

                        // 复选框
                        if($v['view_type'] == 'checkbox')
                        {
                            // 选择/未选中文本
                            if(empty($v['checked_text']))
                            {
                                $v['checked_text'] = MyLang('reverse_select_title');
                            }
                            if(empty($v['not_checked_text']))
                            {
                                $v['not_checked_text'] = MyLang('select_all_title');
                            }

                            // 是否选中 默认否
                            $v['is_checked'] = isset($v['is_checked']) ? intval($v['is_checked']) : 0;

                            // view key 默认 form_ids_checkbox
                            if(empty($v['view_key']))
                            {
                                $v['view_key'] = 'form_checkbox_value';
                            }
                        }

                        // 单选框
                        if($v['view_type'] == 'radio')
                        {
                            // 单选标题
                            if(empty($v['label']))
                            {
                                $v['label'] = MyLang('single_select_title');
                            }

                            // view key 默认 form_ids_radio
                            if(empty($v['view_key']))
                            {
                                $v['view_key'] = 'form_radio_value';
                            }
                        }

                        // 复选+单选
                        if(in_array($v['view_type'], ['checkbox', 'radio']))
                        {
                            // 是否部分不显示控件
                            // 可配置 not_show_type 字段指定类型（0 eq 等于、 1 gt 大于、 2 lt 小于）
                            if(isset($v['not_show_data']) && !is_array($v['not_show_data']))
                            {
                                // 存在英文逗号则转数组
                                if(stripos($v['not_show_data'], ',') !== false)
                                {
                                    $v['not_show_data'] = explode(',', $v['not_show_data']);
                                }
                            }
                            // 数据 key 字段默认主键 id [base->key_field]
                            if(!empty($v['not_show_data']) && empty($v['not_show_key']))
                            {
                                $v['not_show_key'] = $this->form_data['base']['key_field'];
                            }
                        }
                        break;

                    // 字段
                    case 'field' :
                        // 是否开启弹出提示
                        if(isset($v['is_popover']) && $v['is_popover'] == 1)
                        {
                            // 是否指定弹出提示数据字段
                            if(empty($v['popover_field']) && !empty($v['view_key']) && !is_array($v['view_key']))
                            {
                                $v['popover_field'] = $v['view_key'];
                            }
                        }
                        break;

                    // 图片
                    case 'images' :
                        if(empty($v['images_shape']))
                        {
                            $v['images_shape'] = 'radius';
                        }
                        break;
                }
            }

            // 表单key
            $fk = 'f'.$k;

            // 表单名称
            $form_name = (!empty($v['search_config']) && !empty($v['search_config']['form_name'])) ? $v['search_config']['form_name'] : (isset($v['view_key']) ? $v['view_key'] : '');

            // 条件处理
            if(!empty($v['search_config']) && !empty($v['search_config']['form_type']))
            {
                // 搜索 key 未指定则使用显示数据的字段名称
                if(empty($v['search_config']['form_name']))
                {
                    $v['search_config']['form_name'] = $form_name;
                }
                
                // 基础数据处理
                if(!empty($v['search_config']['form_name']))
                {
                    // 显示名称
                    $label = empty($v['label']) ? '' : $v['label'];

                    // 唯一 formkey
                    $form_key = $fk.'p';
                    $v['form_key'] = $form_key;

                    // 是否指定了数据/表单唯一key作为条件、则复制当前key数据
                    // 用于根据key指定条件（指定不宜使用这里拼接的key）
                    $params_where_name = empty($v['params_where_name']) ? $form_name : $v['params_where_name'];
                    if(array_key_exists($params_where_name, $this->out_params) && $this->out_params[$params_where_name] !== null && $this->out_params[$params_where_name] !== '')
                    {
                        $this->out_params[$form_key] = $this->out_params[$params_where_name];
                    // min字段
                    } elseif(array_key_exists($params_where_name.'_min', $this->out_params) && $this->out_params[$params_where_name.'_min'] !== null && $this->out_params[$params_where_name.'_min'] !== '')
                    {
                        $this->out_params[$form_key.'_min'] = $this->out_params[$params_where_name.'_min'];
                    // max字段
                    } elseif(array_key_exists($params_where_name.'_max', $this->out_params) && $this->out_params[$params_where_name.'_max'] !== null && $this->out_params[$params_where_name.'_max'] !== '')
                    {
                        $this->out_params[$form_key.'_max'] = $this->out_params[$params_where_name.'_max'];
                    // start字段
                    } elseif(array_key_exists($params_where_name.'_start', $this->out_params) && $this->out_params[$params_where_name.'_start'] !== null && $this->out_params[$params_where_name.'_start'] !== '')
                    {
                        $this->out_params[$form_key.'_start'] = $this->out_params[$params_where_name.'_start'];
                    // end字段
                    } elseif(array_key_exists($params_where_name.'_end', $this->out_params) && $this->out_params[$params_where_name.'_end'] !== null && $this->out_params[$params_where_name.'_end'] !== '')
                    {
                        $this->out_params[$form_key.'_end'] = $this->out_params[$params_where_name.'_end'];
                    }

                    // 根据组件类型处理
                    switch($v['search_config']['form_type'])
                    {
                        // 单个输入
                        case 'input' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder']))
                            {
                                $v['search_config']['placeholder'] = $lang['input'].$label;
                            }
                            break;

                        // 选择
                        case 'select' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder']))
                            {
                                $v['search_config']['placeholder'] = $lang['select'].$label;
                            }

                            // 选择数据 key=>name
                            if(empty($v['search_config']['data_key']))
                            {
                                $v['search_config']['data_key'] = 'id';
                            }
                            if(empty($v['search_config']['data_name']))
                            {
                                $v['search_config']['data_key'] = 'name';
                            }
                            break;

                        // 区间
                        case 'section' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder_min']))
                            {
                                $v['search_config']['placeholder_min'] = $lang['section_min'];
                            }
                            if(empty($v['search_config']['placeholder_max']))
                            {
                                $v['search_config']['placeholder_max'] = $lang['section_max'];
                            }
                            break;

                        // 时间
                        case 'datetime' :
                        case 'date' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder_start']))
                            {
                                $v['search_config']['placeholder_start'] = $lang['date_start'];
                            }
                            if(empty($v['search_config']['placeholder_end']))
                            {
                                $v['search_config']['placeholder_end'] = $lang['date_end'];
                            }
                            break;

                        // 年月Ym
                        case 'ym' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder']))
                            {
                                $v['search_config']['placeholder'] = $lang['ym'];
                            }
                            break;
                    }

                    // 搜索条件数据处理
                    // 表单字段名称
                    $where_name = $form_name;
                    // 条件类型
                    $where_type = isset($v['search_config']['where_type']) ? $v['search_config']['where_type'] : $v['search_config']['form_type'];
                    // 条件默认值处理
                    $where_type_default_arr = [
                        'input'     => '=',
                        'select'    => 'in',
                        'ym'        => '=',
                    ];
                    if(array_key_exists($where_type, $where_type_default_arr))
                    {
                        $where_type = $where_type_default_arr[$where_type];
                    }

                    // 是否自定义条件处理
                    $where_custom = isset($v['search_config']['where_type_custom']) ? $v['search_config']['where_type_custom'] : '';
                    // 条件类型
                    $where_symbol = $this->WhereSymbolHandle($form_key, $where_custom, $where_type);
                    // 是否自定义条件处理方法
                    $value_custom = isset($v['search_config']['where_value_custom']) ? $v['search_config']['where_value_custom'] : '';
                    // 是否自定义条件处理类对象（默认表格定义文件的对象）
                    $object_custom = isset($v['search_config']['where_object_custom']) ? $v['search_config']['where_object_custom'] : null;

                    // 根据条件类型处理
                    switch($where_type)
                    {
                        // 单个值
                        case '=' :
                        case '<' :
                        case '>' :
                        case '<=' :
                        case '>=' :
                        case 'like' :
                            if(array_key_exists($form_key, $this->out_params) && $this->out_params[$form_key] !== null && $this->out_params[$form_key] !== '' && !is_array($this->out_params[$form_key]))
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$form_key]);
                                $this->where_params[$form_key] = $value;

                                // 条件值处理
                                $value = $this->WhereValueHandle($value, $value_custom, $object_custom);
                                if($value !== null && $value !== '')
                                {
                                    // 是否 like 条件
                                    if($where_type == 'like' && is_string($value))
                                    {
                                        $value = '%'.$value.'%';
                                    }

                                    // 年月Ym、去掉横杠
                                    if($v['search_config']['form_type'] == 'ym')
                                    {
                                        $value = str_replace(['-', '/', '|'], '', $value);
                                    }

                                    // id字段则判断数据值是否包含半角逗号，则条件类型强制为in
                                    if(!is_array($value) && ($where_name == 'id' || stripos($where_name, '.id') !== false) && stripos($value, ',') !== false)
                                    {
                                        $where_symbol = 'in';
                                        $value = explode(',', $value);
                                    }

                                    // 条件
                                    $this->where[] = [$where_name, $where_symbol, $value];
                                }
                            }
                            break;

                        // in
                        case 'in' :
                            if(array_key_exists($form_key, $this->out_params) && $this->out_params[$form_key] !== null && $this->out_params[$form_key] !== '')
                            {
                                // 参数值
                                $value = $this->out_params[$form_key];
                                if(!empty($value) && !is_array($value))
                                {
                                    $value = urldecode($value);
                                }
                                if(!is_array($value))
                                {
                                    $value = explode(',', $value);
                                }
                                $this->where_params[$form_key] = $value;

                                // 条件
                                $value = $this->WhereValueHandle($value, $value_custom, $object_custom);
                                // in条件必须存在值也必须是数组
                                if($where_symbol == 'in')
                                {
                                    if(!empty($value) && is_array($value))
                                    {
                                        $this->where[] = [$where_name, $where_symbol, $value];
                                    }
                                } else {
                                    if($value !== null && $value !== '')
                                    {
                                        $this->where[] = [$where_name, $where_symbol, $value];
                                    }
                                }
                            }
                            break;

                        // 区间值
                        case 'section' :
                            $key_min = $form_key.'_min';
                            $key_max = $form_key.'_max';
                            if(array_key_exists($key_min, $this->out_params) && $this->out_params[$key_min] !== null && $this->out_params[$key_min] !== '')
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$key_min]);
                                $this->where_params[$key_min] = $value;

                                // 条件
                                $value = $this->WhereValueHandle($value, $value_custom, $object_custom, ['is_min'=>1]);
                                if($value !== null && $value !== '')
                                {
                                    $this->where[] = [$where_name, '>=', $value];
                                }
                            }
                            if(array_key_exists($key_max, $this->out_params) && $this->out_params[$key_max] !== null && $this->out_params[$key_max] !== '')
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$key_max]);
                                $this->where_params[$key_max] = $value;

                                // 条件
                                $value = $this->WhereValueHandle($value, $value_custom, $object_custom, ['is_end'=>1]);
                                if($value !== null && $value !== '')
                                {
                                    $this->where[] = [$where_name, '<=', $value];
                                }
                            }
                            break;

                        // 时间
                        case 'datetime' :
                        case 'date' :
                            $key_start = $form_key.'_start';
                            $key_end = $form_key.'_end';
                            if(array_key_exists($key_start, $this->out_params) && $this->out_params[$key_start] !== null && $this->out_params[$key_start] !== '')
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$key_start]);
                                $this->where_params[$key_start] = $value;

                                // 条件
                                $value = $this->WhereValueHandle(strtotime($value), $value_custom, $object_custom, ['is_start'=>1]);
                                if($value !== null && $value !== '')
                                {
                                    $this->where[] = [$where_name, '>=', $value];
                                }
                            }
                            if(array_key_exists($key_end, $this->out_params) && $this->out_params[$key_end] !== null && $this->out_params[$key_end] !== '')
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$key_end]);
                                $this->where_params[$key_end] = $value;

                                // 条件
                                $value = $this->WhereValueHandle(strtotime($value), $value_custom, $object_custom, ['is_end'=>1]);
                                if($value !== null && $value !== '')
                                {
                                    $this->where[] = [$where_name, '<=', $value];
                                }
                            }
                            break;
                    }
                }
            }

            // 排序key与字段、自动拼接的字段 或者 指定的form_key和form_name字段
            $v['sort_key'] = $fk.'o';
            if($v['sort_key'] == $this->order_by['key'] || $form_name == $this->order_by['key'])
            {
                $this->order_by['field'] = empty($v['sort_field']) ? $form_name : $v['sort_field'];
            }

            // 唯一key，避免是模块路径、直接取最后一段
            $unique_key = '';
            if(!empty($v['view_key']))
            {
                // 多字段情况下
                if(is_array($v['view_key']))
                {
                    $unique_key = isset($v['view_key'][0]) ? $v['view_key'][0] : '';
                } else {
                    // 字段名称、模块路径
                    $temp = explode('/', $v['view_key']);
                    $unique_key = empty($temp) ? '' : end($temp);
                }
            }
            $v['unique_key'] = $unique_key;
        }
    }

    /**
     * 基础数据结尾处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-06
     * @desc    description
     */
    public function FormBaseLastHandle()
    {
        // 异步请求超时时间
        if(empty($this->form_data['base']['timeout']))
        {
            $this->form_data['base']['timeout'] = 30000;
        }

        // 是否开启删除
        if(isset($this->form_data['base']['is_delete']) && $this->form_data['base']['is_delete'] == 1)
        {
            // 是否指定选择列字段名称
            // 默认一（第一个复选框）
            // 默认二（第一个单选框）
            if(empty($this->form_data['base']['delete_form']))
            {
                // 所有 form 类型
                $form_type = array_column($this->form_data['form'], 'view_type');
                if(!empty($form_type))
                {
                    // 是否存在复选框
                    if(in_array('checkbox', $form_type))
                    {
                        $index = array_search('checkbox', $form_type);
                        if($index !== false)
                        {
                            $this->form_data['base']['delete_form'] = $this->form_data['form'][$index]['view_key'];
                        }
                    }

                    // 是否存在单选框
                    if(empty($this->form_data['base']['delete_form']) && in_array('radio', $form_type))
                    {
                        $index = array_search('radio', $form_type);
                        if($index !== false)
                        {
                            $this->form_data['base']['delete_form'] = $this->form_data['form'][$index]['view_key'];
                        }
                    }
                }

                // 未匹配到则默认 ids
                if(empty($this->form_data['base']['delete_form']))
                {
                    $this->form_data['base']['delete_form'] = 'ids';
                }
            }

            // 提交数据的字段名称
            if(empty($this->form_data['base']['delete_key']))
            {
                $this->form_data['base']['delete_key'] = $this->form_data['base']['delete_form'];
            }

            // 确认框信息 标题/描述
            if(empty($this->form_data['base']['confirm_title']))
            {
                $this->form_data['base']['confirm_title'] = '温馨提示';
            }
            if(empty($this->form_data['base']['confirm_msg']))
            {
                $this->form_data['base']['confirm_msg'] = '删除后不可恢复、确认操作吗？';
            }
        }
    }

    /**
     * 条件符号处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-20
     * @desc    description
     * @param   [string]          $form_key     [表单key]
     * @param   [stribg]          $where_custom [自定义条件值]
     * @param   [stribg]          $where_type   [条件类型]
     */
    public function WhereSymbolHandle($form_key, $where_custom, $where_type)
    {
        // 是否已定义自定义条件符号
        if(!empty($where_custom))
        {
            // 模块是否自定义条件方法处理条件
            if(method_exists($this->module_obj, $where_custom))
            {
                $value = $this->module_obj->$where_custom($form_key, $this->out_params);
                if(!empty($value))
                {
                    return $value;
                }
            } else {
                return $where_custom;
            }
        }

        // 默认条件类型
        return $where_type;
    }

    /**
     * 条件值处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-04
     * @desc    description
     * @param   [mixed]           $value            [条件值]
     * @param   [string]          $action_custom    [自定义处理方法名称]
     * @param   [object]          $object_custom    [自定义处理类对象]
     * @param   [array]           $params           [输入参数]
     */
    public function WhereValueHandle($value, $action_custom = '', $object_custom = null, $params = [])
    {
        // 根据方法名称处理条件
        switch($action_custom)
        {
            // 系统用户条件处理
            case 'SystemModuleUserWhereHandle' :
                if(!empty($value))
                {
                    // 获取用户id
                    $ids = Db::name('User')->where('number_code|username|nickname|mobile|email', 'like', '%'.$value.'%')->column('id');
                    // 避免空条件造成无效的错觉
                    return empty($ids) ? [0] : $ids;
                }
                break;

            // 系统商品条件处理
            case 'SystemModuleGoodsWhereHandle' :
                if(!empty($value))
                {
                    // 获取商品id
                    $ids = Db::name('Goods')->alias('g')->join('goods_spec_base gb', 'g.id=gb.goods_id')->where('g.title|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords|gb.coding|gb.barcode', 'like', '%'.$value.'%')->column('g.id');
                    // 避免空条件造成无效的错觉
                    return empty($ids) ? [0] : $ids;
                }
                break;

            // 默认走自定义模块处理
            default :
                // 模块是否自定义条件值方法处理条件
                $obj = is_object($object_custom) ? $object_custom : $this->module_obj;
                if(!empty($action_custom) && method_exists($obj, $action_custom))
                {
                    return $obj->$action_custom($value, $params);
                }
        }

        // 默认直接返回值
        return $value;
    }

    /**
     * 基础条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-05
     * @desc    description
     */
    public function BaseWhereHandle()
    {
        $this->where = $this->condition_base_where;
    }

    /**
     * 表格数据列表处理（仅供外部调用、非当前文件调用）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-06
     * @desc    description
     * @param   [array]           $data       [数据列表]
     * @param   [array]           $params     [参数数据]
     */
    public function FormTableDataListHandle($data, $params = [])
    {
        // 数据为空则不处理
        if(empty($data) || !is_array($data))
        {
            return $data;
        }
        $this->data_list = $data;

        // 获取表格模型处理表格列表数据、支持使用后端模块form配置结构
        $module = FormModulePath(array_merge(input(), ['is_admin_module'=>1]));
        if(!empty($module))
        {
            // 参数校验
            $ret = $this->ParamsCheckHandle($module['module'], $module['action'], $params);
            if($ret['code'] == 0)
            {
                // 数据处理
                $this->FormDataListHandle();
            }
        }

        // 返回处理的数据
        if(isset($params['return_data_struct']) && $params['return_data_struct'] == 'all')
        {
            return [
                'table'         => $this->form_data,
                'where'         => $this->where,
                'params'        => $this->where_params,
                'md5_key'       => $this->md5_key,
                'user_fields'   => $this->user_fields,
                'order_by'      => $this->order_by,
                'page'          => $this->page,
                'page_start'    => $this->page_start,
                'page_size'     => $this->page_size,
                'page_total'    => $this->page_total,
                'page_url'      => $this->page_url,
                'page_html'     => $this->page_html,
                'data_total'    => $this->data_total,
                'data_list'     => $this->data_list,
                'data_detail'   => $this->data_detail,
            ];
        }
        return $this->data_list;
    }
}
?>