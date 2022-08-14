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

/**
 * 动态表格处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-02
 * @desc    description
 */
class FormHandleModule
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
    public $page_html;
    public $page_url;

    // 列表数据及详情
    public $data_total;
    public $data_list;
    public $data_detail;

    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     * @param   [string]          $module     [模块位置]
     * @param   [string]          $action     [模块方法（默认 Run 方法，可自动匹配控制器方法名）]
     * @param   [mixed]           $params     [参数数据]
     */
    public function Run($module, $action = 'Run', $params = [])
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

        // 数据列表处理
        $this->FormDataListHandle();

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
        $this->order_by['key'] = empty($this->out_params['fp_order_by_key']) ? '' : $this->out_params['fp_order_by_key'];
        $this->order_by['val'] = empty($this->out_params['fp_order_by_val']) ? '' : $this->out_params['fp_order_by_val'];
        $this->order_by['field'] = '';
        $this->order_by['data'] = '';

        // 分页信息
        $this->page = max(1, isset($this->out_params['page']) ? intval($this->out_params['page']) : 1);
        $this->page_size = min(empty($this->out_params['page_size']) ? MyC('common_page_size', 10, true) : intval($this->out_params['page_size']), 1000);

        // 当前系统操作名称
        $this->module_name = RequestModule();
        $this->controller_name = RequestController();
        $this->action_name = RequestAction();

        // 当前插件操作名称, 兼容插件模块名称
        if(empty($this->out_params['pluginsname']))
        {
            // 默认插件模块赋空值
            $this->plugins_module_name = '';
            $this->plugins_controller_name = '';
            $this->plugins_action_name = '';

            // 当前页面地址
            $this->page_url = MyUrl($this->module_name.'/'.$this->controller_name.'/'.$this->action_name);
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
        if(!empty($this->form_data['data']))
        {
            $form_data = $this->form_data['data'];

            // 列表和详情
            $list_action = isset($form_data['list_action']) ? (is_array($form_data['list_action']) ? $form_data['list_action'] : [$form_data['list_action']]) : ['index'];
            $detail_action = isset($form_data['detail_action']) ? (is_array($form_data['detail_action']) ? $form_data['detail_action'] : [$form_data['detail_action']]) : ['detail', 'saveinfo'];
            if(empty($this->plugins_module_name))
            {
                $is_list = in_array($this->action_name, $list_action);
                $is_detail = in_array($this->action_name, $detail_action);
            } else {
                $is_list = in_array($this->plugins_action_name, $list_action);
                $is_detail = in_array($this->plugins_action_name, $detail_action);
            }
            // 非列表和详情则不处理
            if(!$is_list && !$is_detail)
            {
                return false;
            }

            // 数据库对象
            $db = null;
            if(!empty($form_data['table_obj']) && is_object($form_data['table_obj']))
            {
                $db = $form_data['table_obj'];
            } elseif(!empty($form_data['table_name']))
            {
                $db = Db::name($form_data['table_name']);
            }
            if($db === null)
            {
                return false;
            }

            // 读取字段
            $select_field = empty($form_data['select_field']) ? '*' : $form_data['select_field'];
            $db->field($select_field);

            // 数据读取
            if($is_list)
            {
                // 加入条件
                $db->where($this->where);

                // 总数
                // 是否去重
                if(empty($form_data['distinct']))
                {
                    $this->data_total = (int) $db->count();
                } else {
                    $this->data_total = (int) $db->count('DISTINCT '.$form_data['distinct']);
                }
                if($this->data_total > 0)
                {
                    // 增加排序、未设置则默认[ id desc ]
                    $order_by = empty($this->order_by['data']) ? (array_key_exists('order_by', $form_data) ? $form_data['order_by'] : 'id desc') : $this->order_by['data'];
                    if(!empty($order_by))
                    {
                        $db->order($order_by);
                    }

                    // 分组
                    if(!empty($form_data['group']))
                    {
                        $db->group($form_data['group']);
                    }

                    // 是否使用分页
                    $is_page = (!isset($form_data['is_page']) || $form_data['is_page'] == 1);
                    if($is_page)
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
                        $this->page_start = $page->GetPageStarNumber();
                        $this->page_html = $page->GetPageHtml();

                        // 增加分页
                        $db->limit($this->page_start, $this->page_size);
                    }

                    // 读取数据
                    $this->data_list = $db->select()->toArray();
                }
            } else {
                // 默认条件
                $this->where = empty($form_data['detail_where']) ? [] : $form_data['detail_where'];

                // 单独处理条件
                $detail_dkey = empty($form_data['detail_dkey']) ? 'id' : $form_data['detail_dkey'];
                $detail_pkey = empty($form_data['detail_pkey']) ? 'id' : $form_data['detail_pkey'];
                $value = empty($this->out_params[$detail_pkey]) ? 0 : $this->out_params[$detail_pkey];
                $this->where[] = [$detail_dkey, '=', $value];
                $db->where($this->where);

                // 读取数据、仅读取一条
                $this->data_list = $db->limit(0, 1)->select()->toArray();
            }

            // 数据处理
            if(!empty($this->data_list))
            {
                // 是否已定义数据处理、必须存在双冒号
                $m = $this->ServiceActionModule($form_data, 'data_handle');
                if(!empty($m))
                {
                    $module = $m['module'];
                    $action = $m['action'];
                    // 数据请求参数
                    $data_params = empty($form_data['data_params']) ? [] : $form_data['data_params'];
                    $res = $module::$action($this->data_list, $data_params);
                    // 是否按照数据返回格式方法放回的数据
                    if(isset($res['code']) && isset($res['msg']) && isset($res['data']))
                    {
                        $this->data_list = $res['data'];
                    } else {
                        $this->data_list = $res;
                    }
                }

                // 是否详情页
                if($is_detail)
                {
                    $this->data_detail = $this->data_list[0];
                    $this->data_list = [];
                }
            }
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
        // 当前用户选择的字段
        $ret = FormTableService::FieldsSelectData(['md5_key'=>$this->md5_key]);
        if(empty($ret['data']))
        {
            // 未设置则读取所有带label的字段、默认显示
            $this->user_fields = array_filter(array_map(function($value)
            {
                if(!empty($value['label']) && $value['view_type'] != 'operate')
                {
                    return ['label'=>$value['label'], 'checked'=>1];
                }
            }, $this->form_data['form']));
        } else {
            $this->user_fields = $ret['data'];
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
            $temp_form = array_column($this->form_data['form'], null, 'label');
            foreach($this->user_fields as $k=>$v)
            {
                // 字段不存在数据中则移除
                if(array_key_exists($v['label'], $temp_form))
                {
                    $temp = $temp_form[$v['label']];

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
                                $v['checked_text'] = '反选';
                            }
                            if(empty($v['not_checked_text']))
                            {
                                $v['not_checked_text'] = '全选';
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
                                $v['label'] = '单选';
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
                    }

                    // 根据组件类型处理
                    switch($v['search_config']['form_type'])
                    {
                        // 单个输入
                        case 'input' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder']))
                            {
                                $v['search_config']['placeholder'] = '请输入'.$label;
                            }
                            break;

                        // 选择
                        case 'select' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder']))
                            {
                                $v['search_config']['placeholder'] = '请选择'.$label;
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
                                $v['search_config']['placeholder_min'] = '最小值';
                            }
                            if(empty($v['search_config']['placeholder_max']))
                            {
                                $v['search_config']['placeholder_max'] = '最大值';
                            }
                            break;

                        // 时间
                        case 'datetime' :
                        case 'date' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder_start']))
                            {
                                $v['search_config']['placeholder_start'] = '开始';
                            }
                            if(empty($v['search_config']['placeholder_end']))
                            {
                                $v['search_config']['placeholder_end'] = '结束';
                            }
                            break;

                        // 年月Ym
                        case 'ym' :
                            // 提示信息处理
                            if(empty($v['search_config']['placeholder']))
                            {
                                $v['search_config']['placeholder'] = '请选择年月';
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
                            if(array_key_exists($form_key, $this->out_params) && $this->out_params[$form_key] !== null && $this->out_params[$form_key] !== '')
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

                                    // 年月Ym、去掉横岗
                                    if($v['search_config']['form_type'] == 'ym')
                                    {
                                        $value = str_replace(['-', '/', '|'], '', $value);
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
                                $value = urldecode($this->out_params[$form_key]);
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

            // 排序key与字段
            $v['sort_key'] = $fk.'o';
            if($v['sort_key'] == $this->order_by['key'])
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
        // 模块是否自定义条件值方法处理条件
        $obj = is_object($object_custom) ? $object_custom : $this->module_obj;
        if(!empty($action_custom) && method_exists($obj, $action_custom))
        {
            return $obj->$action_custom($value, $params);
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
        // 是否定义基础条件属性
        if(property_exists($this->module_obj, 'condition_base') && is_array($this->module_obj->condition_base))
        {
            $this->where = $this->module_obj->condition_base;
        }
    }

    /**
     * 表格数据列表处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-06
     * @desc    description
     * @param   [array]           $data       [数据列表]
     * @param   [array]           $params     [参数数据]
     */
    public function FormTableDataListHandle($data, $params)
    {
        // 空或非数组则不处理
        if(empty($data) || !is_array($data) || empty($params) || !is_array($params))
        {
            return $data;
        }

        // 获取表格模型处理表格列表数据
        $module = FormModulePath($params);
        if(empty($module))
        {
            return $data;
        }

        // 参数校验
        $ret = $this->ParamsCheckHandle($module['module'], $module['action'], $params);
        if($ret['code'] != 0)
        {
            return $data;
        }

        // 获取表单配置数据处理
        $form = array_column($this->form_data['form'], null, 'view_key');
        foreach($data as $k=>&$v)
        {
            if(empty($v) || !is_array($v))
            {
                continue;
            }
            foreach($v as $ks=>$vs)
            {
                // view_type为field
                // 必须存在view_data数据
                if(!array_key_exists($ks, $form) || empty($form[$ks]['view_data']) || !is_array($form[$ks]['view_data']))
                {
                    continue;
                }

                // 是否指定view_data_key配置、指定则view_data为二维数组
                $key = $ks.'_name';
                if(empty($form[$ks]['view_data_key']))
                {
                    $v[$key] = isset($form[$ks]['view_data'][$vs]) ? $form[$ks]['view_data'][$vs] : '';
                } else {
                    $v[$key] = (isset($form[$ks]['view_data'][$vs]) && isset($form[$ks]['view_data'][$vs][$form[$ks]['view_data_key']])) ? $form[$ks]['view_data'][$vs][$form[$ks]['view_data_key']] : '';
                }
            }
        }
        return $data;
    }
}
?>