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

use app\service\ResourcesService;

/**
 * form表单处理服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-14
 * @desc    description
 */
class FormInputModule
{
    /**
     * 配置数据展示处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-22
     * @desc    description
     * @param   [array]          $config [配置数据]
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigViewHandle($config, $params = [])
    {
        // 是否为空
        if(!empty($config))
        {
            // 非数组处理
            if(!is_array($config))
            {
                $config = json_decode($config, true);
            }

            // 数据处理
            $config = self::ConfigViewAnnexHandle($config);

            // forminput显示数据处理钩子
            $hook_name = 'plugins_module_forminput_view_data_handle';
            MyEventTrigger($hook_name, [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'config'      => &$config,
                'params'      => $params,
            ]);
        }
        return $config;
    }

    /**
     * 配置数据展示附件地址处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [array]          $data [配置数据]
     */
    public static function ConfigViewAnnexHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if(!empty($v) && is_array($v))
                {
                    // 附件
                    if(!empty($v[0]) && isset($v[0]['url']))
                    {
                        $data[$k][0]['url'] = ResourcesService::AttachmentPathViewHandle($v[0]['url']);
                    } else {
                        $data[$k] = self::ConfigViewAnnexHandle($v);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 配置数据保存处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-22
     * @desc    description
     * @param   [array]          $config [配置数据]
     */
    public static function ConfigSaveHandle($config)
    {
        if(!empty($config))
        {
            // 非数组处理
            if(!is_array($config))
            {
                $config = json_decode(htmlspecialchars_decode($config), true);
            }

            // 数据处理
            $config = self::ConfigSaveAnnexHandle($config);

            // forminput保存数据处理钩子
            $hook_name = 'plugins_module_forminput_save_data_handle';
            MyEventTrigger($hook_name, [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'config'      => &$config,
            ]);

            // 转为json格式
            $config = json_encode($config, JSON_UNESCAPED_UNICODE);
        }
        return $config;
    }

    /**
     * 配置数据保存附件地址处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [array]          $data [配置数据]
     */
    public static function ConfigSaveAnnexHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if(!empty($v) && is_array($v))
                {
                    // 附件
                    if(!empty($v[0]) && isset($v[0]['url']))
                    {
                        $data[$k][0]['url'] = ResourcesService::AttachmentPathHandle($v[0]['url']);
                    } else {
                        $data[$k] = self::ConfigSaveAnnexHandle($v);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 表单数据保存处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-07-30
     * @desc    description
     * @param   [array]          $config [表单配置]
     * @param   [array]          $data   [保存的表单数据]
     */
    public static function FormInputDataWriteHandle($config, $data)
    {
        $result = [];
        if(!empty($config) && !empty($config['diy_data']))
        {
            foreach($config['diy_data'] as $v)
            {
                $form_name = empty($v['form_name']) ? (empty($v['id']) ? '' : $v['id']) : $v['form_name'];
                if(!empty($form_name))
                {
                    $custom_option_list = empty($data[$form_name.'_custom_option_list']) ? [] : $data[$form_name.'_custom_option_list'];
                    switch($v['key'])
                    {
                        // 富文本
                        case 'rich-text' :
                            if(array_key_exists($form_name, $data))
                            {
                                $value = $data[$form_name];
                                if(!empty($value))
                                {
                                    $value = ResourcesService::ContentStaticReplace(htmlspecialchars_decode($value), 'add');
                                }
                                $result[$form_name] = [
                                    'name'   => $v['name'],
                                    'value'  => $value,
                                    'key'    => $v['key'],
                                ];
                            }
                            break;

                        // 附件
                        case 'upload-img' :
                        case 'upload-video' :
                        case 'upload-attachments' :
                            if(array_key_exists($form_name, $data))
                            {
                                $value = $data[$form_name];
                                if(!empty($value) && is_array($value))
                                {
                                    $value = ResourcesService::AttachmentPathHandle($value, 'url');
                                }
                                $result[$form_name] = [
                                    'name'   => $v['name'],
                                    'value'  => $value,
                                    'key'    => $v['key'],
                                ];
                            }
                            break;

                        // 复选
                        case 'checkbox' :
                        // 下拉多选
                        case 'select-multi' :
                            if(array_key_exists($form_name, $data))
                            {
                                $temp_value = [];
                                $value = $data[$form_name];
                                if(!empty($value) && is_array($value))
                                {
                                    $option_arr = array_merge(empty($v['com_data']['option_list']) ? [] : $v['com_data']['option_list'], $custom_option_list);
                                    if(!empty($option_arr))
                                    {
                                        foreach($option_arr as $ov)
                                        {
                                            if(!empty($ov['value']) && in_array($ov['value'], $value))
                                            {
                                                $temp_value[] = $ov;
                                            }
                                        }
                                    }
                                }
                                $result[$form_name] = [
                                    'name'        => $v['name'],
                                    'value'       => $temp_value,
                                    'key'         => $v['key'],
                                    'value_text'  => empty($temp_value) ? '' : implode(', ', array_column($temp_value, 'name')),
                                ];
                                if(!empty($custom_option_list))
                                {
                                    $result[$form_name]['custom_option_list'] = $custom_option_list;
                                }
                            }
                            break;
                        // 单选
                        case 'radio-btns' :
                        // 下拉单选
                        case 'select' :
                            if(array_key_exists($form_name, $data))
                            {
                                $value = $data[$form_name];
                                if($value !== '')
                                {
                                    $option_arr = empty($v['com_data']['option_list']) ? [] : $v['com_data']['option_list'];
                                    if(!empty($option_arr))
                                    {
                                        foreach($option_arr as $ov)
                                        {
                                            if(!empty($ov['value']) && $ov['value'] == $value)
                                            {
                                                $value = $ov;
                                                break;
                                            }
                                        }
                                    }
                                }
                                $result[$form_name] = [
                                    'name'   => $v['name'],
                                    'value'  => $value,
                                    'key'    => $v['key'],
                                ];
                                if(isset($data[$form_name.'_other_value']))
                                {
                                    $result[$form_name]['other_value'] = $data[$form_name.'_other_value'];
                                }
                                $result[$form_name]['value_text'] = empty($value) ? '' : ((isset($value['is_other']) && $value['is_other'] == 1) ? (isset($result[$form_name]['other_value']) ? $result[$form_name]['other_value'] : '') : $value['name']);
                            }
                            break;

                        // 时间组
                        case 'date-group' :
                            $temp_value = [];
                            $start_key = $form_name.'_start';
                            $temp_value['start'] = array_key_exists($start_key, $data) ? $data[$start_key] : '';
                            $end_key = $form_name.'_end';
                            $temp_value['end'] = array_key_exists($end_key, $data) ? $data[$end_key] : '';
                            if(count(array_filter($temp_value)) > 0)
                            {
                                $result[$form_name] = [
                                    'name'        => $v['name'],
                                    'value'       => $temp_value,
                                    'key'         => $v['key'],
                                    'value_text'  => implode('~', $temp_value),
                                ];
                            }
                            break;

                        // 地区地址
                        case 'address' :
                            $temp_value = [];
                            $arr = [
                                'province_id',
                                'city_id',
                                'county_id',
                                'province_name',
                                'city_name',
                                'county_name',
                                'address',
                            ];
                            foreach($arr as $tv)
                            {
                                $ads_key = $form_name.'_'.$tv;
                                $temp_value[$tv] = array_key_exists($ads_key, $data) ? $data[$ads_key] : '';
                            }
                            if(count(array_filter($temp_value)) > 0)
                            {
                                $result[$form_name] = [
                                    'name'        => $v['name'],
                                    'value'       => $temp_value,
                                    'key'         => $v['key'],
                                    'value_text'  => $temp_value['province_name'].$temp_value['city_name'].$temp_value['county_name'].$temp_value['address'],
                                ];
                            }
                            break;

                        // 默认
                        default :
                            if(array_key_exists($form_name, $data))
                            {
                                $result[$form_name] = [
                                    'name'   => $v['name'],
                                    'value'  => $data[$form_name],
                                    'key'    => $v['key'],
                                ];
                            }
                            break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 表单数据显示处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-07-30
     * @desc    description
     * @param   [array]          $data [表单保存的数据]
     */
    public static function FormInputDataViewHandle($data)
    {
        if(!empty($data))
        {
            if(!is_array($data))
            {
                $data = json_decode($data, true);
            }
            foreach($data as &$v)
            {
                if(!empty($v['key']))
                {
                    switch($v['key'])
                    {
                        // 富文本
                        case 'rich-text' :
                            $v['value'] = ResourcesService::ContentStaticReplace($v['value'], 'get');
                            break;
                        // 附件
                        case 'upload-img' :
                        case 'upload-video' :
                        case 'upload-attachments' :
                            if(is_array($v['value']))
                            {
                                $v['value'] = ResourcesService::AttachmentPathViewHandle($v['value'], 'url');
                            }
                            break;
                    }
                }                
            }
        }
        return $data;
    }
}
?>