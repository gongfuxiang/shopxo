<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\module;

use think\Controller;

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
    // 搜索条件
    public $where;

    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     * @param   [string]          $module     [模块位置]
     * @param   [mixed]           $params     [参数数据]
     */
    public function Run($module, $params = [])
    {
        // 参数
        $this->out_params = $params;

        // 模块是否存在
        if(!class_exists($module))
        {
            return DataReturn('表格模块未定义['.$module.']', -1);
        }

        // 调用方法
        $action = 'Run';
        $this->module_obj = new $module();
        if(!method_exists($this->module_obj, $action))
        {
            return DataReturn('表格方法未定义['.$module.'->'.$action.'()]', -1);
        }

        // 获取表格配置数据
        $this->form_data = $this->module_obj->$action($this->out_params);
        if(empty($this->form_data['base']) || empty($this->form_data['form']))
        {
            return DataReturn('表格配置有误['.$module.'][base|form]', -1);
        }

        // 数据唯一主字段
        if(empty($this->form_data['base']['key_field']))
        {
            return DataReturn('表格唯一字段配置有误['.$module.']base->[key_field]', -1);
        }

        // 基础条件
        $this->BaseWhereHandle();

        // 表格处理数据
        $this->FormDataHandle();

        // 数据返回
        $data = [
            'table'     => $this->form_data,
            'where'     => $this->where,
            'params'    => $this->where_params,
        ];
        return DataReturn('success', 0, $data);
    }

    /**
     * 表格数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     */
    public function FormDataHandle()
    {
        if(!empty($this->form_data['form']))
        {
            foreach($this->form_data['form'] as $k=>&$v)
            {
                // 基础处理
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

                                // 提交参数处理
                                if(isset($this->out_params[$v['view_key']]))
                                {
                                    $value = urldecode($this->out_params[$v['view_key']]);
                                    if(!is_array($value))
                                    {
                                        $value = explode(',', $value);
                                    }
                                    $this->where_params[$v['view_key']] = $value;
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

                                // 提交参数处理
                                if(isset($this->out_params[$v['view_key']]))
                                {
                                    $value = urldecode($this->out_params[$v['view_key']]);
                                    $this->where_params[$v['view_key']] = $value;
                                }
                            }
                            break;
                    }
                }
                

                // 条件处理
                if(!empty($v['search_config']) && !empty($v['search_config']['form_type']) && !empty($v['search_config']['form_name']))
                {
                    // 基础数据处理
                    // 显示名称
                    $label = empty($v['label']) ? '' : $v['label'];

                    // 唯一 formkey
                    $form_key = 'fp'.$k;
                    $v['form_key'] = $form_key;

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
                    }

                    // 搜索条件数据处理
                    // 表单字段名称
                    $name = $v['search_config']['form_name'];
                    // 条件类型
                    $type = isset($v['search_config']['where_type']) ? $v['search_config']['where_type'] : $v['search_config']['form_type'];
                    // 是否自定义条件处理方法
                    $custom = isset($v['search_config']['where_custom']) ? $v['search_config']['where_custom'] : '';
                    // 根据条件类型处理
                    switch($type)
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
                                $value = $this->WhereValueHandle($value, $custom);

                                // 是否 like 条件
                                if($type == 'like')
                                {
                                    $value = '%'.$value.'%';
                                }

                                // 条件
                                $this->where[] = [$name, $type, $value];
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
                                $this->where[] = [$name, $type, $this->WhereValueHandle($value, $custom)];
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
                                $this->where[] = [$name, '>=', $this->WhereValueHandle($value, $custom, ['is_min'=>1])];
                            }
                            if(array_key_exists($key_max, $this->out_params) && $this->out_params[$key_max] !== null && $this->out_params[$key_max] !== '')
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$key_max]);
                                $this->where_params[$key_max] = $value;

                                // 条件
                                $this->where[] = [$name, '<=', $this->WhereValueHandle($value, $custom, ['is_end'=>1])];
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
                                $this->where[] = [$name, '>=', $this->WhereValueHandle(strtotime($value), $custom, ['is_start'=>1])];
                            }
                            if(array_key_exists($key_end, $this->out_params) && $this->out_params[$key_end] !== null && $this->out_params[$key_end] !== '')
                            {
                                // 参数值
                                $value = urldecode($this->out_params[$key_end]);
                                $this->where_params[$key_end] = $value;

                                // 条件
                                $this->where[] = [$name, '<=', $this->WhereValueHandle(strtotime($value), $custom, ['is_end'=>1])];

                            }
                            break;
                    }
                }
            }
        }
    }

    /**
     * 条件值处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-04
     * @desc    description
     * @param   [mixed]           $value    [条件值]
     * @param   [mixed]           $custom   [自定义处理方法名称]
     * @param   [array]           $params   [输入参数]
     */
    function WhereValueHandle($value, $custom = '', $params = [])
    {
        // 模块是否自定义方法处理条件
        if(!empty($custom) && method_exists($this->module_obj, $custom))
        {
            return $this->module_obj->$custom($value, $params);
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
    function BaseWhereHandle()
    {
        // 是否定义基础条件属性
        if(property_exists($this->module_obj, 'condition_base') && is_array($this->module_obj->condition_base))
        {
            $this->where = $this->module_obj->condition_base;
        }
    }
}
?>