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
     * @param   [array]          $config        [表单配置]
     * @param   [array]          $data          [保存的表单数据]
     * @param   [string]         $parent_name   [父级表单名称]
     */
    public static function FormInputDataWriteHandle($config, $data, $parent_name = '')
    {
        $result = [];
        if(!empty($config) && !empty($config['diy_data']))
        {
            foreach($config['diy_data'] as $v)
            {
                // 是否启用
                if(!isset($v['is_enable']) || $v['is_enable'] != 1)
                {
                    continue;
                }

                // 是否存在表单名称
                $form_name = empty($v['form_name']) ? (empty($v['id']) ? '' : $v['id']) : $v['form_name'];
                if(empty($form_name))
                {
                    continue;
                }

                // 配置和基础
                if(empty($v['com_data']) || empty($v['key']))
                {
                    continue;
                }

                // 非这些多数据格式的数据，是否需要填写
                if(!in_array($v['key'], ['checkbox', 'select-multi', 'radio-btns', 'select', 'date-group', 'address']))
                {
                    if(isset($v['com_data']['is_required']) && $v['com_data']['is_required'] == 1 && empty($data[$form_name]))
                    {
                        if(in_array($v['key'], ['upload-img', 'upload-attachments', 'upload-video']))
                        {
                            // 上传
                            $msg = MyLang('not_upload_error');
                        } else if(in_array($v['key'], ['score']))
                        {
                            // 选择
                            $msg = MyLang('not_choice_error');
                        } else {
                            // 填写
                            $msg = MyLang('not_fill_in_error');
                        }
                        return DataReturn($msg.'('.$parent_name.$v['name'].')', -1);
                    }
                }

                // 自定义列
                $custom_option_list = empty($data[$form_name.'_custom_option_list']) ? [] : (is_array($data[$form_name.'_custom_option_list']) ? $data[$form_name.'_custom_option_list'] : json_decode(base64_decode(urldecode($data[$form_name.'_custom_option_list'])), true));
                $name = empty($v['com_data']['title']) ? $v['name'] : $v['com_data']['title'];
                switch($v['key'])
                {
                    // 子表单
                    case 'subform' :
                        $temp_item = [];
                        if(!empty($data[$form_name]))
                        {
                            foreach($data[$form_name] as $sbk=>$sbv)
                            {
                                $res = self::FormInputDataWriteHandle(['diy_data'=>$v['com_data']['children']], $sbv, $name.'-');

                                if($res['code'] != 0)
                                {
                                    return $res;
                                }
                                $temp_item[] = $res['data'];
                            }
                        }
                        if(!empty($temp_item))
                        {
                            $result[$form_name] = [
                                'name'   => $name,
                                'value'  => $temp_item,
                                'key'    => $v['key'],
                            ];
                        }
                        break;

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
                                'name'   => $name,
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
                            if(!empty($value))
                            {
                                $value = is_array($value) ? $value : json_decode(base64_decode(urldecode($value)), true);
                                if(!empty($value) && is_array($value))
                                {
                                    $value = ResourcesService::AttachmentPathHandle($value, 'url');
                                } else {
                                    $value = '';
                                }
                            }
                            $result[$form_name] = [
                                'name'   => $name,
                                'value'  => $value,
                                'key'    => $v['key'],
                            ];
                        }
                        break;

                    // 复选
                    case 'checkbox' :
                    // 下拉多选
                    case 'select-multi' :
                        $temp_item = [];
                        if(array_key_exists($form_name, $data))
                        {
                            $temp_value = [];
                            $value = $data[$form_name];
                            if($value !== '')
                            {
                                if(!is_array($value))
                                {
                                    $value = explode(',', $value);
                                }
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
                            $temp_item = [
                                'name'        => $name,
                                'value'       => empty($temp_value) ? '' : array_column($temp_value, 'value'),
                                'key'         => $v['key'],
                                'value_text'  => empty($temp_value) ? '' : implode(', ', array_column($temp_value, 'name')),
                            ];
                            if(!empty($custom_option_list))
                            {
                                $temp_item['custom_option_list'] = $custom_option_list;
                            }
                        }
                        if(!empty($temp_item))
                        {
                            $result[$form_name] = $temp_item;
                        } else {
                            if(isset($v['com_data']['is_required']) && $v['com_data']['is_required'] == 1 && empty($data[$form_name]))
                            {
                                return DataReturn(MyLang('not_fill_in_error').'('.$parent_name.$name.')', -1);
                            }
                        }
                        break;
                    // 单选
                    case 'radio-btns' :
                    // 下拉单选
                    case 'select' :
                        $temp_item = [];
                        if(array_key_exists($form_name, $data))
                        {
                            $temp_value_text = '';
                            $temp_value = '';
                            if($data[$form_name] !== '')
                            {
                                $option_arr = array_merge(empty($v['com_data']['option_list']) ? [] : $v['com_data']['option_list'], $custom_option_list);
                                if(!empty($option_arr))
                                {
                                    foreach($option_arr as $ov)
                                    {
                                        if(!empty($ov['value']) && $ov['value'] == $data[$form_name])
                                        {
                                            $temp_value_text = $ov['name'];
                                            $temp_value = $ov['value'];
                                            break;
                                        }
                                    }
                                }
                            }
                            $temp_item = [
                                'name'   => $name,
                                'value'  => $temp_value,
                                'key'    => $v['key'],
                            ];
                            if(isset($data[$form_name.'_other_value']))
                            {
                                $temp_item['other_value'] = $data[$form_name.'_other_value'];
                            }
                            $temp_item['value_text'] = empty($temp_item['other_value']) ? $temp_value_text : $temp_item['other_value'];
                        }
                        if(!empty($temp_item))
                        {
                            $result[$form_name] = $temp_item;
                        } else {
                            if(isset($v['com_data']['is_required']) && $v['com_data']['is_required'] == 1 && empty($data[$form_name]))
                            {
                                return DataReturn(MyLang('not_fill_in_error').'('.$parent_name.$name.')', -1);
                            }
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
                                'name'        => $name,
                                'value'       => $temp_value,
                                'key'         => $v['key'],
                                'value_text'  => implode(' ~ ', $temp_value),
                            ];
                        } else {
                            if(isset($v['com_data']['is_required']) && $v['com_data']['is_required'] == 1 && empty($data[$form_name]))
                            {
                                return DataReturn(MyLang('not_fill_in_error').'('.$parent_name.$name.')', -1);
                            }
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
                                'name'        => $name,
                                'value'       => $temp_value,
                                'key'         => $v['key'],
                                'value_text'  => $temp_value['province_name'].$temp_value['city_name'].$temp_value['county_name'].$temp_value['address'],
                            ];
                        } else {
                            if(isset($v['com_data']['is_required']) && $v['com_data']['is_required'] == 1 && empty($data[$form_name]))
                            {
                                return DataReturn(MyLang('not_fill_in_error').'('.$parent_name.$name.')', -1);
                            }
                        }
                        break;

                    // 手机
                    case 'phone' :
                        // 是否需要短信验证码
                        if(!empty($data[$form_name]) && isset($v['com_data']['is_sms_verification']) && $v['com_data']['is_sms_verification'] == 1)
                        {
                            // 是否存在验证码
                            if(empty($data[$form_name.'_verify']))
                            {
                                return DataReturn(MyLang('verify_code_empty_tips').'('.$parent_name.$name.')', -1);
                            }
                            // 验证码校验
                            $verify_params = [
                                'key_prefix'    => 'forminput_'.md5($data[$form_name]),
                                'expire_time'   => MyC('common_verify_expire_time'),
                            ];
                            $obj = new \base\Sms($verify_params);
                            // 是否已过期
                            if(!$obj->CheckExpire())
                            {
                                return DataReturn(MyLang('verify_code_expire_tips').'('.$parent_name.$name.')', -1);
                            }
                            // 是否正确
                            if(!$obj->CheckCorrect($data[$form_name.'_verify']))
                            {
                                return DataReturn(MyLang('verify_code_error_tips').'('.$parent_name.$name.')', -1);
                            }
                        }
                        if(!empty($data[$form_name]))
                        {
                            $result[$form_name] = [
                                'name'   => $name,
                                'value'  => $data[$form_name],
                                'key'    => $v['key'],
                            ];
                        }
                        break;

                    // 默认
                    default :
                        // 是否限制字数
                        if(isset($data[$form_name]) && isset($v['com_data']['is_limit_num']) && $v['com_data']['is_limit_num'] == 1)
                        {
                            // 当前数值长度
                            $len = strlen($data[$form_name]);
                            // 最小值
                            if(!empty($v['com_data']['min_num']) && $len < $v['com_data']['min_num'])
                            {
                                return DataReturn(MyLang('data_length_min_tips').'('.$len.'<'.$v['com_data']['min_num'].' '.$name.')', -1);
                            }
                            // 最大值
                            if(!empty($v['com_data']['max_num']) && $len > $v['com_data']['max_num'])
                            {
                                return DataReturn(MyLang('data_length_max_tips').'('.$v['com_data']['max_num'].'>'.$len.' '.$name.')', -1);
                            }
                        }
                        if(array_key_exists($form_name, $data))
                        {
                            $result[$form_name] = [
                                'name'   => $name,
                                'value'  => $data[$form_name],
                                'key'    => $v['key'],
                            ];
                        }
                        break;
                }
            }
        }
        return DataReturn('success', 0, $result);
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

    public static function FormInputDataValueMerge($config, $data)
    {
        if(!empty($config['diy_data']) && !empty($data))
        {
            foreach($config['diy_data'] as &$v)
            {
                $form_name = empty($v['form_name']) ? (empty($v['id']) ? '' : $v['id']) : $v['form_name'];
                if(!empty($v['key']) && isset($data[$form_name]))
                {
                    // 数据
                    $temp = $data[$form_name];

                    // 覆盖默认值
                    $v['com_data']['form_value'] = isset($temp['value']) ? $temp['value'] : '';
                    // 根据类型处理数据
                    switch($v['key'])
                    {
                        // 子表单
                        case 'subform' :
                            if(!empty($v['com_data']['form_value']) && is_array($v['com_data']['form_value']))
                            {
                                foreach($v['com_data']['form_value'] as $sk=>$sv)
                                {
                                    if(!empty($sv) && is_array($sv))
                                    {
                                        foreach($sv as $sks=>$svs)
                                        {
                                            $v['com_data']['form_value'][$sk][$sks] = isset($svs['value']) ? $svs['value'] : '';
                                        }
                                    }
                                }
                            }
                            break;

                        // 时间组
                        case 'date-group' :
                            if(!empty($v['com_data']['form_value']) && is_array($v['com_data']['form_value']))
                            {
                                $v['com_data']['form_value'] = array_values($v['com_data']['form_value']);
                            }
                            break;
                    }

                    // 其他值
                    if($v['com_data']['form_value'] == 'other')
                    {
                        $v['com_data']['other_value'] = isset($temp['other_value']) ? $temp['other_value'] : '';
                    }

                    // 自定义数据项
                    if(!empty($temp['custom_option_list']))
                    {
                        $v['com_data']['custom_option_list'] = $temp['custom_option_list'];
                    }
                }
            }
        }
        // print_r($config);
        // print_r($data);
        // die;

        return $config;
    }
}
?>