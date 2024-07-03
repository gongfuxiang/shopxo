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

use app\service\SystemBaseService;

/**
 * 数据打印处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-06-23
 * @desc    description
 */
class DataPrintHandleModule
{
    /**
     * 类型模板数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-18
     * @desc    description
     * @param   [string]       $control  [控制器]
     * @param   [array]        $params   [输入参数]
     */
    public static function PrintTemplateTypeData($control, $params = [])
    {
        // 组件是否隐藏标题
        $is_hide_title = (isset($params['is_hide_title']) && $params['is_hide_title'] == 1) ? 1 : 0;

        // 文本居中，靠左
        $text_align = (isset($params['text_align']) && $params['text_align'] == 1) ? 'center' : 'left';

        // 文本样式类型
        $text_style_type_options = [
            // 普通文本样式
            'text' => [
                'height'        => 30,
                'width'         => 100,
                'textAlign'     => $text_align,
                'lineHeight'    => 30,
                'hideTitle'     => $is_hide_title,
            ],
            // 标题文本样式
            'title' => [
                'height'        => 30,
                'width'         => 200,
                'fontWeight'    => 700,
                'fontSize'      => 20,
                'textAlign'     => $text_align,
                'lineHeight'    => 30,
                'hideTitle'     => true,
            ],
        ];

        // 公共组件
        $common = [];
        if(!empty($params['common']) && is_array($params['common']))
        {
            foreach($params['common'] as $v)
            {
                if(!empty($v['title']) && !empty($v['field']) && isset($v['data']))
                {
                    // 没有指定测试数据则使用数据作为测试数据
                    $test_data = isset($v['test_data']) ? $v['test_data'] : $v['data'];
                    // 模块基础参数
                    $temp = [
                        'title'     => $v['title'],
                        'field'     => $v['field'],
                        'tid'       => 'config_module.'.$v['field'],
                        'type'      => empty($v['type']) ? 'text' : $v['type'],
                        'data'      => $v['data'],
                        'options'   => [
                            'field'     => $v['field'],
                            'testData'  => $test_data,
                        ],
                    ];
                    // 根据类型处理数据
                    switch($temp['type'])
                    {
                        // 文本
                        case 'text' :
                            // 没有指定文本样式类型则使用默认文本
                            $style_type = empty($v['style_type']) ? 'text' : $v['style_type'];
                            $options = isset($text_style_type_options[$style_type]) ? $text_style_type_options[$style_type] : $text_style_type_options['text'];
                            $temp['options'] = array_merge($options, $temp['options']);
                            break;

                        // 图片
                        case 'text' :
                            // 图片增加src参数
                            $temp['options']['sec'] = $test_data;
                            break;
                    }
                    $common[] = $temp;
                }
            }
        }

        // 辅助
        $assist = [];
        $style_arr = [
            'hline' => MyLang('data_print.template_assist_style_hline'),
            'vline' => MyLang('data_print.template_assist_style_hline'),
            'rect'  => MyLang('data_print.template_assist_style_rect'),
            'oval'  => MyLang('data_print.template_assist_style_oval'),
        ];
        foreach($style_arr as $k=>$v)
        {
            $assist[] = [
                'tid'   => 'config_module.'.$k,
                'title' => $v,
                'type'  => $k
            ];
        }
        // 自定义文本
        $assist[] = [
            'tid'       => 'config_module.module_system_custom_text',
            'title'     => MyLang('data_print.template_assist_style_custom_text'),
            'custom'    => true,
            'type'      => 'text',
            'options'   => $text_style_type_options['text'],
        ];
        // 自定义多行文本
        $assist[] = [
            'tid'       => 'config_module.module_system_custom_long_text',
            'title'     => MyLang('data_print.template_assist_style_custom_long_text'),
            'type'      => 'longText',
        ];
        // 自定义图片
        $assist[] = [
            'title'     => MyLang('data_print.template_assist_style_custom_image'),
            'tid'       => 'config_module.module_system_custom_image',
            'type'      => 'image',
            'data'      => SystemBaseService::AttachmentHost().'/static/common/images/default-images.jpg',
        ];

        // 获取业务表单数据
        $business = [];
        $ret = self::FormDataToPrintData($control, 'Run', 'index', array_merge($params, ['text_style_options'=>$text_style_type_options['text']]));
        if($ret['code'] == 0 && !empty($ret['data']))
        {
            $business = $ret['data'];
        }

        return [
            'common'    => $common,
            'assist'    => $assist,
            'business'  => $business,
        ];
    }

    /**
     * 表格数据转打印数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-18
     * @desc    description
     * @param   [string]          $control    [控制器名称]
     * @param   [string]          $action     [方法]
     * @param   [string]          $group      [组 admin 或 index, 空则自动读取当前模块组]
     * @param   [array]           $params     [输入参数]
     */
    public static function FormDataToPrintData($control, $action = 'Run', $group = '', $params = [])
    {
        // 组空则当前模块组
        if(empty($group))
        {
            $group = RequestModule();
        }

        // 获取表格配置数据
        $ret = self::FormConfigData($group, $control, $action, $params);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $form_data = $ret['data'];

        // 数据组合
        $result = [];
        foreach($form_data['form'] as $v)
        {
            if(isset($v['view_type']) && $v['view_type'] == 'field' && !empty($v['view_key']))
            {
                $temp = [
                    'field'     => $v['view_key'],
                    'tid'       => 'config_module.'.$v['view_key'],
                    'type'      => 'text',
                    'data'      => $v['label'],
                    'title'     => $v['label'],
                ];
                if(!empty($params['text_style_options']))
                {
                    $temp['options'] = $params['text_style_options'];
                    $temp['options']['field'] = $v['view_key'];
                    $temp['options']['testData'] = $v['label'];
                }
                $result[] = $temp;
            }
        }

        // 是否存在详情列表字段数据定义
        if(!empty($form_data['detail_form_list']) && is_array($form_data['detail_form_list']))
        {
            // table可编辑配置
            $table_options = self::PrintTemplateTableEditParams($params);

            // 新增扩展字段数量
            $extends_number = isset($params['extends_number']) ? intval($params['extends_number']) : 0;

            // 数据格式处理
            $formatter2 = 'function(value,row,index,options){ return ((value || null) != null && (([".png", ".jpg", ".gif", ".bmp"].indexOf(value.toString().substr(-4)) != -1) || (value.toString().substr(-5) == ".jpeg"))) ? \'<img src="\'+value+\'" style="max-width:100%;max-height:100%;padding:0.5rem;" />\' : value; }';

            // 开始处理数据
            foreach($form_data['detail_form_list'] as $v)
            {
                if(!empty($v) && is_array($v) && !empty($v['label']) && !empty($v['field']) && !empty($v['data']))
                {
                    // 详情列表
                    $columns = [];
                    foreach($v['data'] as $ks=>$vs)
                    {
                        $columns[] = [
                            'title'       => $vs,
                            'align'       => 'center',
                            'field'       => $ks,
                            'width'       => 100,
                            'colspan'     => 1,
                            'rowspan'     => 1,
                            'checked'     => true,
                            'formatter2'  => $formatter2,
                        ];
                    }

                    // 详情列表添加扩展字段
                    if($extends_number > 0)
                    {
                        for($i=1; $i<=$extends_number; $i++)
                        {
                            $columns[] = [
                                'title'       => MyLang('data_print.template_table_extends_field').$i,
                                'align'       => 'center',
                                'field'       => 'extends_'.$i,
                                'width'       => 100,
                                'colspan'     => 1,
                                'rowspan'     => 1,
                                'checked'     => true,
                                'formatter2'  => $formatter2,
                            ];
                        }
                    }

                    // 加入到模板列表
                    $result[] = array_merge([
                        'tid'       => 'config_module.'.$v['field'],
                        'field'     => $v['field'],
                        'title'     => $v['label'],
                        'type'      => 'table',
                        'columns'   => [$columns],
                        'options'   => [
                            'field'     => $v['field'],
                            'testData'  => $v['label'],
                        ],
                    ], $table_options);
                }
            }
        }

        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 表单配置数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-21
     * @desc    description
     * @param   [string]          $group      [组]
     * @param   [string]          $control    [控制器]
     * @param   [string]          $run        [表单入口方法]
     * @param   [array]           $params     [输入参数]
     */
    public static function FormConfigData($group, $control, $run = 'Run', $params = [])
    {
        // 获取表格配置
        $result = FormModuleData([
            'group'          => $group,
            'control'        => $control,
            'run'            => $run,
            'data_type'      => 'table',
            'is_data_query'  => 0,
        ]);
        if(empty($result))
        {
            return DataReturn(MyLang('data_print.template_table_config_error_tips').'['.$control.']', -1);
        }

        // 配置是否正确
        if(empty($result['form']) || !is_array($result['form']))
        {
            return DataReturn(MyLang('data_print.template_table_config_error_tips').'['.$control.'-form]', -1);
        }

        // 返回表格配置数据
        return DataReturn('success', 0, $result);
    }

    /**
     * 表格是否可以编辑
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-19
     * @desc    description
     * @param   [array]           $params     [输入参数]
     */
    public static function PrintTemplateTableEditParams($params = [])
    {
        $result = [];
        if(isset($params['template_table_edit']) && $params['template_table_edit'] == 1)
        {
            $result = [
                'editable'                      => true,
                'columnDisplayEditable'         => true,
                'columnDisplayIndexEditable'    => true,
                'columnTitleEditable'           => true,
                'columnResizable'               => true,
                'columnAlignEditable'           => true,
            ];
        }
        return $result;
    }

    /**
     * 模板配置处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-19
     * @desc    description
     * @param   [array]        $config   [配置数据]
     * @param   [array]        $params   [输入参数]
     */
    public static function PrintTemplateConfigHandle($config, $params = [])
    {
        if(!empty($config))
        {
            // 数据处理
            $data = is_array($config) ? $config : json_decode(htmlspecialchars_decode($config), true);

            // 表格是否开启编辑
            $table_options = self::PrintTemplateTableEditParams($params);
            if(!empty($data['panels']) && !empty($data['panels'][0]) && !empty($data['panels'][0]['printElements']))
            {
                foreach($data['panels'][0]['printElements'] as &$v)
                {
                    // 表格
                    if(!empty($v['printElementType']) && isset($v['printElementType']['type']) && $v['printElementType']['type'] == 'table')
                    {
                        $v['printElementType'] = array_merge($v['printElementType'], $table_options);
                    }
                }
            }
            return is_array($config) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return $config;
    }

    /**
     * 获取打印模板一条业务数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-18
     * @desc    description
     * @param   [string]       $control   [控制器]
     * @param   [array]        $params    [输入参数]
     */
    public static function PrintTemplateBusinessData($control, $params = [])
    {
        $result = FormModuleData([
            'control'    => $control,
            'action'     => 'index',
            'page_size'  => 1,
        ]);
        return (empty($result) || empty($result['data_list']) || empty($result['data_list'][0])) ? null : $result['data_list'][0];
    }
}
?>