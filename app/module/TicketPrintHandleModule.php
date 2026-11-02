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

/**
 * 小票可视化打印
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-10-03
 * @desc    块级模板 JSON 编译为小票 content 标签，不使用 hiprint
 */
class TicketPrintHandleModule
{
    /**
     * 组件类型数据（公共 + 辅助 + 业务）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [array]        $params   [common/business 业务字段]
     */
    public static function TypeData($params = [])
    {
        $common = [];
        if(!empty($params['common']) && is_array($params['common']))
        {
            $common = self::NormalizeFields($params['common']);
        }

        $assist = [
            [
                'type'    => 'text',
                'field'   => '',
                'title'   => MyLang('ticket_print.assist_custom_text'),
                'custom'  => 1,
                'sample'  => MyLang('ticket_print.assist_custom_text'),
            ],
            [
                'type'    => 'line',
                'field'   => '',
                'title'   => MyLang('ticket_print.assist_line'),
                'sample'  => '--------------------------------',
            ],
            [
                'type'    => 'blank',
                'field'   => '',
                'title'   => MyLang('ticket_print.assist_blank'),
                'sample'  => '',
            ],
            [
                'type'    => 'qr',
                'field'   => '',
                'title'   => MyLang('ticket_print.assist_qr'),
                'custom'  => 1,
                'sample'  => 'QR',
            ],
            [
                'type'    => 'cut',
                'field'   => '',
                'title'   => MyLang('ticket_print.assist_cut'),
                'sample'  => 'CUT',
            ],
        ];

        // 业务字段：显式传入优先，否则从 form 读取（含 Hook 注入的 ticket_print_extra_fields）
        $business = [];
        if(!empty($params['business']) && is_array($params['business']))
        {
            $business = self::NormalizeFields($params['business']);
        } elseif(!empty($params['control']) || (isset($params['is_plugins']) && intval($params['is_plugins']) == 1))
        {
            $business = self::FormDataToTicketFields($params);
        }

        return [
            'common'    => $common,
            'assist'    => $assist,
            'business'  => $business,
        ];
    }

    /**
     * 按 field 合并字段列表（后者不覆盖已有 field）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-07
     * @param   [array]        $base  [基础]
     * @param   [array]        $extra [追加]
     */
    public static function MergeFieldsByKey($base = [], $extra = [])
    {
        $seen = [];
        $result = [];
        foreach([$base, $extra] as $list)
        {
            if(empty($list) || !is_array($list))
            {
                continue;
            }
            foreach($list as $v)
            {
                if(empty($v) || !is_array($v))
                {
                    continue;
                }
                $field = empty($v['field']) ? '' : $v['field'];
                if($field !== '' && isset($seen[$field]))
                {
                    continue;
                }
                if($field !== '')
                {
                    $seen[$field] = 1;
                }
                $result[] = $v;
            }
        }
        return $result;
    }

    /**
     * form 配置转小票业务字段（文本 field + ticket_print_extra_fields；原始 detail_form_list 不自动当商品）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-07
     * @param   [array]        $params [与 FormModuleData 相同的 control/is_plugins 等]
     */
    public static function FormDataToTicketFields($params = [])
    {
        // 未指定则跟随当前模块；多商户由调用方传 is_plugins/plugins/group=index，勿默认强行走 admin
        if(!isset($params['is_plugins']))
        {
            $params['is_plugins'] = 0;
        }
        if(empty($params['group']))
        {
            $params['group'] = RequestModule();
        }
        if(empty($params['action']))
        {
            $params['action'] = 'index';
        }

        // form 不存在、权限不足、语言键缺失等：直接空字段，不向外抛错
        try {
            $ret = DataPrintHandleModule::FormConfigData($params);
        } catch(\Throwable $e) {
            return [];
        }
        if(empty($ret) || !is_array($ret) || !isset($ret['code']) || $ret['code'] != 0 || empty($ret['data']) || !is_array($ret['data']))
        {
            return [];
        }
        $form_data = $ret['data'];
        $result = [];
        $seen = [];

        // 1. 表单基础文本字段（排除 module/images/operate 等）
        if(!empty($form_data['form']) && is_array($form_data['form']))
        {
            foreach($form_data['form'] as $v)
            {
                if(empty($v) || !is_array($v) || empty($v['view_key']) || !isset($v['label']) || $v['label'] === '' || $v['label'] === null)
                {
                    continue;
                }
                // 仅文本 field；module 等一律排除
                if(!isset($v['view_type']) || $v['view_type'] != 'field')
                {
                    continue;
                }
                if(isset($seen[$v['view_key']]))
                {
                    continue;
                }
                $seen[$v['view_key']] = 1;
                // 拖入设计器：标题进前缀输入框；样例内容走 sample_data，勿把标题塞进数据
                // ticket_label 显式指定前缀（含空串：整段内容字段如「合计描述」不再叠「合计描述：」）
                $ticket_label = array_key_exists('ticket_label', $v) ? $v['ticket_label'] : ($v['label'].'：');
                $result[] = [
                    'type'    => 'text',
                    'field'   => $v['view_key'],
                    'title'   => $v['label'],
                    'label'   => $ticket_label,
                    'sample'  => '',
                    'align'   => empty($v['ticket_align']) ? 'left' : $v['ticket_align'],
                    'size'    => empty($v['ticket_size']) ? 'normal' : $v['ticket_size'],
                ];
            }
        }

        // 2. 插件钩子追加的小票专用字段（ticket_print_extra_fields）
        if(!empty($form_data['ticket_print_extra_fields']) && is_array($form_data['ticket_print_extra_fields']))
        {
            foreach($form_data['ticket_print_extra_fields'] as $v)
            {
                if(empty($v) || !is_array($v) || empty($v['title']))
                {
                    continue;
                }
                $field = empty($v['field']) ? '' : $v['field'];
                if($field !== '' && isset($seen[$field]))
                {
                    continue;
                }
                if($field !== '')
                {
                    $seen[$field] = 1;
                }
                $result[] = $v;
            }
        }

        // 3. 仅读取带 ticket_type 标记的详情列表（原始 detail_form_list 如地址/快递等不自动当商品）
        if(!empty($form_data['detail_form_list']) && is_array($form_data['detail_form_list']))
        {
            foreach($form_data['detail_form_list'] as $v)
            {
                if(empty($v) || !is_array($v) || empty($v['ticket_type']) || empty($v['field']) || empty($v['label']))
                {
                    continue;
                }
                if(isset($seen[$v['field']]))
                {
                    continue;
                }
                $seen[$v['field']] = 1;
                $ticket_type = $v['ticket_type'];
                if(!in_array($ticket_type, ['text', 'items', 'qr', 'barcode'], true))
                {
                    $ticket_type = 'items';
                }
                $result[] = [
                    'type'    => $ticket_type,
                    'field'   => $v['field'],
                    'title'   => $v['label'],
                    'mode'    => empty($v['ticket_mode']) ? ($ticket_type == 'items' ? 'table' : '') : $v['ticket_mode'],
                    'header'  => isset($v['ticket_header']) ? $v['ticket_header'] : '',
                    'sample'  => isset($v['ticket_sample']) ? $v['ticket_sample'] : [],
                ];
            }
        }

        return self::NormalizeFields($result);
    }

    /**
     * 设计器引入数据组装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-07
     * @param   [array]        $params [form_name/config/control/business/sample_data/default_templates/...]
     */
    public static function DesignerModuleData($params = [])
    {
        $type_params = $params;
        if(empty($type_params['business']) && empty($type_params['control']) && empty($type_params['is_plugins']))
        {
            // 无业务来源时保持空业务面板
            $type_params['business'] = [];
        }
        $sample = [];
        if(!empty($params['sample_data']) && is_array($params['sample_data']))
        {
            $sample = $params['sample_data'];
        } elseif(!empty($params['control']) || (isset($params['is_plugins']) && intval($params['is_plugins']) == 1))
        {
            $row = DataPrintHandleModule::PrintTemplateBusinessData($params);
            $sample = empty($row) ? [] : $row;
        }
        return [
            'form_name'           => empty($params['form_name']) ? 'ticket_print_template' : $params['form_name'],
            'list_mode'           => empty($params['list_mode']) ? 0 : 1,
            'config'              => isset($params['config']) ? $params['config'] : '',
            'type_data'           => self::TypeData($type_params),
            'sample_data'         => $sample,
            'template_keys'       => empty($params['template_keys']) ? [] : $params['template_keys'],
            'default_templates'   => empty($params['default_templates']) ? [] : $params['default_templates'],
        ];
    }

    /**
     * 规范化字段
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [array]        $list [字段列表]
     */
    public static function NormalizeFields($list = [])
    {
        $result = [];
        foreach($list as $v)
        {
            if(empty($v) || !is_array($v) || empty($v['title']))
            {
                continue;
            }
            $type = empty($v['type']) ? 'text' : $v['type'];
            if(!in_array($type, ['text', 'items', 'qr', 'barcode', 'line', 'blank', 'cut']))
            {
                $type = 'text';
            }
            $result[] = [
                'type'     => $type,
                'field'    => empty($v['field']) ? '' : $v['field'],
                'title'    => $v['title'],
                'label'    => isset($v['label']) ? $v['label'] : '',
                // sample 显式空串表示无内置样例（预览用 sample_data），勿回退成 title 与前缀叠在一起
                'sample'   => array_key_exists('sample', $v) ? $v['sample'] : (isset($v['data']) ? $v['data'] : $v['title']),
                'align'    => empty($v['align']) ? 'left' : $v['align'],
                'size'     => empty($v['size']) ? 'normal' : $v['size'],
                'mode'     => empty($v['mode']) ? ($type == 'items' ? 'table' : '') : $v['mode'],
                'header'   => isset($v['header']) ? $v['header'] : '',
            ];
        }
        return $result;
    }

    /**
     * 模板是否可用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [mixed]        $template [模板]
     */
    public static function IsValidTemplate($template)
    {
        $template = self::ParseTemplate($template);
        return !empty($template) && !empty($template['elements']) && is_array($template['elements']);
    }

    /**
     * 解析模板 JSON
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [mixed]        $template [模板]
     */
    public static function ParseTemplate($template)
    {
        if(empty($template))
        {
            return [];
        }
        if(is_string($template))
        {
            $template = json_decode(htmlspecialchars_decode($template), true);
        }
        return is_array($template) ? $template : [];
    }

    /**
     * 按打印机模板 key 取一份
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [mixed]        $templates [一份或多份]
     * @param   [mixed]        $key       [key，空则取 default 或首份]
     */
    public static function PickTemplate($templates, $key = null)
    {
        $list = self::NormalizeList($templates);
        if(empty($list))
        {
            $templates = self::ParseTemplate($templates);
            if(empty($templates))
            {
                return [];
            }
            if(isset($templates['elements']))
            {
                return $templates;
            }
            if($key !== null && $key !== '')
            {
                if(isset($templates[$key]) && is_array($templates[$key]))
                {
                    return $templates[$key];
                }
                $str_key = strval($key);
                if(isset($templates[$str_key]) && is_array($templates[$str_key]))
                {
                    return $templates[$str_key];
                }
            }
            if(isset($templates['default']) && is_array($templates['default']))
            {
                return $templates['default'];
            }
            foreach($templates as $v)
            {
                if(is_array($v) && isset($v['elements']))
                {
                    return $v;
                }
            }
            return [];
        }
        if($key !== null && $key !== '')
        {
            foreach($list as $item)
            {
                if(isset($item['id']) && strval($item['id']) === strval($key))
                {
                    return $item;
                }
            }
        }
        return isset($list[0]) ? $list[0] : [];
    }

    /**
     * 模板列表规范化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [mixed]        $templates [原始配置]
     * @param   [array]        $seed      [空数据时的默认列表]
     */
    public static function NormalizeList($templates, $seed = [])
    {
        $templates = self::ParseTemplate($templates);
        $list = [];
        if(!empty($templates) && is_array($templates))
        {
            if(isset($templates['elements']))
            {
                $list[] = self::WrapListItem($templates, isset($templates['id']) ? $templates['id'] : 'default', isset($templates['name']) ? $templates['name'] : '');
            } elseif(isset($templates[0]) && is_array($templates[0]) && array_key_exists('id', $templates[0]))
            {
                foreach($templates as $item)
                {
                    if(!is_array($item))
                    {
                        continue;
                    }
                    $list[] = self::WrapListItem($item, isset($item['id']) ? $item['id'] : '', isset($item['name']) ? $item['name'] : '');
                }
            } else {
                foreach($templates as $id=>$item)
                {
                    if(!is_array($item) || !isset($item['elements']))
                    {
                        continue;
                    }
                    $list[] = self::WrapListItem($item, $id, isset($item['name']) ? $item['name'] : strval($id));
                }
            }
        }
        if(empty($list) && !empty($seed) && is_array($seed))
        {
            foreach($seed as $item)
            {
                if(!is_array($item))
                {
                    continue;
                }
                $list[] = self::WrapListItem($item, isset($item['id']) ? $item['id'] : '', isset($item['name']) ? $item['name'] : '');
            }
        }
        $result = [];
        foreach($list as $item)
        {
            if($item['id'] === '')
            {
                continue;
            }
            $result[] = $item;
        }
        return $result;
    }

    /**
     * 列表项
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function WrapListItem($item, $id, $name)
    {
        return [
            'id'        => strval($id),
            'name'      => $name === '' || $name === null ? strval($id) : strval($name),
            'width'     => empty($item['width']) ? 58 : intval($item['width']),
            'elements'  => empty($item['elements']) || !is_array($item['elements']) ? [] : $item['elements'],
        ];
    }

    /**
     * 编译为小票 content
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [mixed]        $template [模板]
     * @param   [array]        $data     [业务数据]
     * @param   [array]        $options  [is_sort/is_br]
     */
    public static function Compile($template, $data = [], $options = [])
    {
        $template = is_array($template) && isset($template['elements']) ? $template : self::ParseTemplate($template);
        if(empty($template) || empty($template['elements']) || !is_array($template['elements']))
        {
            return '';
        }
        $content = '';
        foreach($template['elements'] as $el)
        {
            if(empty($el) || !is_array($el) || empty($el['type']))
            {
                continue;
            }
            $piece = self::CompileElement($el, $data, $options);
            if($piece !== '')
            {
                $content .= $piece;
            }
        }
        return $content;
    }

    /**
     * 编译单个组件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function CompileElement($el, $data, $options = [])
    {
        $type = $el['type'];
        switch($type)
        {
            case 'line' :
                return '--------------------------------<BR>';

            case 'blank' :
                return '<BR>';

            case 'cut' :
                // 切刀在打印头上方：切前走纸，避免裁到上方内容
                return '<BR><BR><BR><CUT>';

            case 'qr' :
                $value = self::ElementValue($el, $data);
                if($value === '' || $value === null)
                {
                    return '';
                }
                return '<QR>'.$value.'</QR>';

            case 'barcode' :
                $value = self::ElementValue($el, $data);
                if($value === '' || $value === null)
                {
                    return '';
                }
                return '<BC128>'.$value.'</BC128>';

            case 'items' :
                return self::CompileItems($el, $data, $options);

            default :
                $value = self::ElementValue($el, $data);
                $hide_empty = !isset($el['hide_empty']) || intval($el['hide_empty']) == 1;
                if($hide_empty && ($value === '' || $value === null))
                {
                    return '';
                }
                $label = isset($el['label']) ? strval($el['label']) : '';
                $text = $label.$value;
                if($text === '')
                {
                    return '';
                }
                return self::WrapStyle($text, $el).'<BR>';
        }
    }

    /**
     * 列表组件默认表头语言键
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-08
     * @param   [string]        $field [字段]
     */
    public static function ItemsHeaderLangKey($field = '')
    {
        switch(strval($field))
        {
            case 'address_data' :
                return 'ticket_print.address_header';
            case 'express_data' :
                return 'ticket_print.express_header';
            case 'service_data' :
                return 'ticket_print.service_header';
            default :
                return 'ticket_print.items_header';
        }
    }

    /**
     * 内置默认表头（各语言），用于识别模板里落库的默认文案并按当前语言输出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-08
     * @param   [string]        $lang_key [语言键]
     */
    public static function BuiltinItemsHeaders($lang_key = 'ticket_print.items_header')
    {
        static $map = [
            'ticket_print.items_header'   => [
                '名称           单价  数量 金额',
                '名稱           單價  數量 金額',
                'Name           Price Qty Amount',
                'Nombre          Precio Cant Importe',
            ],
            'ticket_print.address_header' => [
                '收货人         电话        地址',
                '收貨人         電話        地址',
                'Contact        Phone       Address',
                'Contacto        Teléfono    Dirección',
            ],
            'ticket_print.express_header' => [
                '快递公司                    单号',
                '快遞公司                    單號',
                'Courier                      Tracking No.',
                'Mensajería                   Nº seguimiento',
            ],
            'ticket_print.service_header' => [
                '服务人员       电话        备注',
                '服務人員       電話        備註',
                'Staff          Phone       Note',
                'Personal        Teléfono    Nota',
            ],
        ];
        return empty($map[$lang_key]) ? [] : $map[$lang_key];
    }

    /**
     * 解析列表表头：空或内置默认文案时按当前语言输出，自定义表头保持原样
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-08
     * @param   [array]         $el [元素]
     */
    public static function ResolveItemsHeader($el = [])
    {
        $field = empty($el['field']) ? 'items' : $el['field'];
        $lang_key = self::ItemsHeaderLangKey($field);
        $current = MyLang($lang_key);
        $header = isset($el['header']) ? strval($el['header']) : '';
        if($header === '')
        {
            return $current;
        }
        $defaults = self::BuiltinItemsHeaders($lang_key);
        if(!empty($defaults) && in_array($header, $defaults, true))
        {
            return $current;
        }
        return $header;
    }

    /**
     * 商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function CompileItems($el, $data, $options = [])
    {
        $items = self::GetValue($data, empty($el['field']) ? 'items' : $el['field']);
        if(empty($items) || !is_array($items))
        {
            return '';
        }
        $mode = empty($el['mode']) ? 'table' : $el['mode'];
        $is_sort = !empty($options['is_sort']);
        $is_br = !empty($options['is_br']);
        $content = '';
        if($mode == 'table')
        {
            $header = self::ResolveItemsHeader($el);
            if($header !== '')
            {
                $content .= $header.'<BR>';
            }
            $content .= '--------------------------------<BR>';
        }
        $index = 0;
        foreach($items as $item)
        {
            if(empty($item) || !is_array($item))
            {
                continue;
            }
            if($index > 0 && $is_br)
            {
                $content .= '<BR>';
            }
            $line = self::ComposeItemsLine($item, $el, $mode, $is_sort, $index);
            if($line === '')
            {
                continue;
            }
            if($mode == 'simple')
            {
                $content .= self::WrapStyle($line, array_merge($el, ['size'=>empty($el['size']) ? 'B' : $el['size']])).'<BR>';
            } else {
                $content .= $line;
            }
            $index++;
        }
        return $content;
    }

    /**
     * 列表行文本（简洁：非商品字段拼接全部列；商品仅标题+数量）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-07
     * @param   [array]        $item    [行数据]
     * @param   [array]        $el      [元素]
     * @param   [string]       $mode    [table/simple]
     * @param   [boolean]      $is_sort [是否序号]
     * @param   [int]          $index   [下标]
     */
    public static function ComposeItemsLine($item, $el = [], $mode = 'table', $is_sort = false, $index = 0)
    {
        $name = isset($item['name']) ? strval($item['name']) : (isset($item['title']) ? strval($item['title']) : '');
        $price = isset($item['price']) ? strval($item['price']) : '';
        $num = isset($item['num']) ? strval($item['num']) : '';
        $total = isset($item['total']) ? strval($item['total']) : '';
        $field = empty($el['field']) ? 'items' : $el['field'];
        if($mode == 'simple')
        {
            // 地址/快递/服务：一行展示全部必要字段
            if(in_array($field, ['address_data', 'express_data', 'service_data'], true))
            {
                $parts = [];
                foreach([$name, $price, $num, $total] as $part)
                {
                    if($part !== '')
                    {
                        $parts[] = $part;
                    }
                }
                $text = implode(' ', $parts);
            } else {
                // 商品简洁：标题 + 数量
                $text = $name;
                if($num !== '')
                {
                    $text .= '（'.$num.'）';
                }
            }
            if($text === '')
            {
                return '';
            }
            if($is_sort)
            {
                $text = '('.($index+1).')'.$text;
            }
            return $text;
        }
        if($is_sort)
        {
            $name = '('.($index+1).')'.$name;
        }
        return self::ComposeTableRow(
            $name,
            $price,
            $num,
            $total,
            isset($el['col1']) ? intval($el['col1']) : 14,
            isset($el['col2']) ? intval($el['col2']) : 6,
            isset($el['col3']) ? intval($el['col3']) : 3,
            isset($el['col4']) ? intval($el['col4']) : 6
        );
    }

    /**
     * 标准四列商品行（飞鹅字节对齐）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function ComposeTableRow($name, $price, $num, $prices, $col1 = 14, $col2 = 6, $col3 = 3, $col4 = 6)
    {
        $kw3 = '';
        $kw1 = '';
        $kw2 = '';
        $kw4 = '';
        $str = $name;
        $blank_num = $col1;
        $lan = mb_strlen($str, 'utf-8');
        $m = 0;
        $j = 1;
        $blank_num++;
        $result = [];
        $tail = '';
        if(strlen($price) < $col2)
        {
            $k1 = $col2 - strlen($price);
            for($q=0; $q<$k1; $q++)
            {
                $kw1 .= ' ';
            }
            $price = $price.$kw1;
        }
        if(strlen($num) < $col3)
        {
            $k2 = $col3 - strlen($num);
            for($q=0; $q<$k2; $q++)
            {
                $kw2 .= ' ';
            }
            $num = $num.$kw2;
        }
        if(strlen($prices) < $col4)
        {
            $k3 = $col4 - strlen($prices);
            for($q=0; $q<$k3; $q++)
            {
                $kw4 .= ' ';
            }
            $prices = $prices.$kw4;
        }
        for($i=0; $i<$lan; $i++)
        {
            $new = mb_substr($str, $m, $j, 'utf-8');
            $j++;
            if(mb_strwidth($new, 'utf-8') < $blank_num)
            {
                if($m+$j > $lan)
                {
                    $m = $m+$j;
                    $tail = $new;
                    $lenght = @iconv('UTF-8', 'GBK//IGNORE', $new);
                    $k = $col1 - strlen($lenght);
                    for($q=0; $q<$k; $q++)
                    {
                        $kw3 .= ' ';
                    }
                    if($m == $j)
                    {
                        $tail .= $kw3.' '.$price.' '.$num.' '.$prices;
                    } else {
                        $tail .= $kw3.'<BR>';
                    }
                    break;
                } else {
                    $next_new = mb_substr($str, $m, $j, 'utf-8');
                    if(mb_strwidth($next_new, 'utf-8') < $blank_num)
                    {
                        continue;
                    } else {
                        $m = $i+1;
                        $result[] = $new;
                        $j = 1;
                    }
                }
            }
        }

        $head = '';
        foreach($result as $key=>$value)
        {
            if($key < 1)
            {
                $v_lenght = @iconv('UTF-8', 'GBK//IGNORE', $value);
                $v_lenght = strlen($v_lenght);
                if($v_lenght == 13)
                {
                    $value = $value.' ';
                }
                $head .= $value.' '.$price.' '.$num.' '.$prices;
            } else {
                $head .= $value.'<BR>';
            }
        }
        return $head.$tail;
    }

    /**
     * 组件取值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function ElementValue($el, $data)
    {
        if(!empty($el['custom_text']))
        {
            return strval($el['custom_text']);
        }
        if(empty($el['field']))
        {
            return '';
        }
        $value = self::GetValue($data, $el['field']);
        if(is_array($value))
        {
            return '';
        }
        return ($value === null) ? '' : strval($value);
    }

    /**
     * 点语法取值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function GetValue($data, $field)
    {
        if($field === '' || $field === null || !is_array($data))
        {
            return '';
        }
        if(array_key_exists($field, $data))
        {
            return $data[$field];
        }
        $current = $data;
        foreach(explode('.', $field) as $part)
        {
            if(!is_array($current) || !array_key_exists($part, $current))
            {
                return '';
            }
            $current = $current[$part];
        }
        return $current;
    }

    /**
     * 小票样式包裹
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     */
    public static function WrapStyle($text, $el)
    {
        $size = empty($el['size']) ? 'normal' : $el['size'];
        $align = empty($el['align']) ? 'left' : $el['align'];
        if($size == 'CB')
        {
            $size = 'B';
            if($align != 'right')
            {
                $align = 'center';
            }
        }
        // 兼容旧值：BOLD/BB 转为 bold 标记 + 可选放大
        $bold = !empty($el['bold']) && intval($el['bold']) == 1;
        if($size == 'BB')
        {
            $size = 'B';
            $bold = true;
        } else if($size == 'BOLD')
        {
            $size = 'normal';
            $bold = true;
        }
        if($bold)
        {
            $text = '<BOLD>'.$text.'</BOLD>';
        }
        if(!empty($el['tall']) && intval($el['tall']) == 1)
        {
            $text = '<L>'.$text.'</L>';
        }
        if(!empty($el['wide']) && intval($el['wide']) == 1)
        {
            $text = '<W>'.$text.'</W>';
        }
        if($size == 'B')
        {
            if($align == 'center')
            {
                $text = '<CB>'.$text.'</CB>';
            } else {
                $text = '<B>'.$text.'</B>';
            }
        } else if($align == 'center')
        {
            $text = '<C>'.$text.'</C>';
        }
        if($align == 'right')
        {
            $text = '<RIGHT>'.$text.'</RIGHT>';
        }
        return $text;
    }

    /**
     * 默认模板
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-03
     * @param   [string]       $scene [default/realstore_standard/realstore_simple]
     */
    public static function DefaultTemplate($scene = 'default')
    {
        switch($scene)
        {
            case 'realstore_simple' :
                return [
                    'width'     => 58,
                    'elements'  => [
                        ['type'=>'text', 'field'=>'store_name', 'label'=>'', 'align'=>'center', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'blank'],
                        ['type'=>'items', 'field'=>'items', 'mode'=>'simple', 'size'=>'B'],
                        ['type'=>'line'],
                        ['type'=>'text', 'field'=>'total_line', 'label'=>'', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'order_type_name', 'label'=>'类型：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'created_type_name', 'label'=>'来源：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'order_allot_no', 'label'=>'单号：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'add_time_short', 'label'=>'时间：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'delivery_title', 'label'=>'配送：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'user_note', 'label'=>'备注：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'table_name', 'label'=>'桌号：', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'blank'],
                        ['type'=>'qr', 'field'=>'order_allot_no'],
                    ],
                ];
            case 'realstore_standard' :
                return [
                    'width'     => 58,
                    'elements'  => [
                        ['type'=>'text', 'field'=>'store_name', 'label'=>'', 'align'=>'center', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'items', 'field'=>'items', 'mode'=>'table', 'header'=>MyLang('ticket_print.items_header')],
                        ['type'=>'line'],
                        ['type'=>'text', 'field'=>'total_price_text', 'label'=>'合计：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'order_type_name', 'label'=>'订单类型：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'created_type_name', 'label'=>'订单来源：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'order_allot_no', 'label'=>'订单编号：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'add_time', 'label'=>'订单时间：', 'hide_empty'=>1],
                        ['type'=>'line'],
                        ['type'=>'text', 'field'=>'address_title_text', 'label'=>'', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'contact_name', 'label'=>'联系人：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'contact_tel', 'label'=>'联系电话：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'appoint_time', 'label'=>'指定时间：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'user_note', 'label'=>'用户备注：', 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'table_name', 'label'=>'桌号：', 'hide_empty'=>1],
                        ['type'=>'line'],
                        ['type'=>'text', 'field'=>'service_tel', 'label'=>'客服电话：', 'hide_empty'=>1],
                        ['type'=>'blank'],
                        ['type'=>'qr', 'field'=>'order_allot_no'],
                    ],
                ];
            default :
                // 前缀进 label，打印/预览数据为纯内容（由业务 map / sample_data 提供）
                return [
                    'width'     => 58,
                    'elements'  => [
                        ['type'=>'text', 'field'=>'printer_title', 'label'=>'', 'align'=>'center', 'size'=>'B', 'hide_empty'=>1],
                        ['type'=>'items', 'field'=>'items', 'mode'=>'table', 'header'=>MyLang('ticket_print.items_header')],
                        ['type'=>'line'],
                        ['type'=>'text', 'field'=>'total_text', 'label'=>MyLang('ticket_print.label_total'), 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'order_model_name', 'label'=>MyLang('ticket_print.label_order_model'), 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'client_type_name', 'label'=>MyLang('ticket_print.label_order_source'), 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'order_no', 'label'=>MyLang('ticket_print.label_order_no'), 'hide_empty'=>1],
                        ['type'=>'text', 'field'=>'add_time', 'label'=>MyLang('ticket_print.label_order_time'), 'hide_empty'=>1],
                        ['type'=>'line'],
                        ['type'=>'items', 'field'=>'address_data', 'mode'=>'table', 'header'=>MyLang('ticket_print.address_header')],
                        ['type'=>'text', 'field'=>'user_note', 'label'=>MyLang('ticket_print.label_user_note'), 'hide_empty'=>1],
                        ['type'=>'line'],
                        ['type'=>'text', 'field'=>'printer_service_tel', 'label'=>MyLang('ticket_print.label_service_tel'), 'hide_empty'=>1],
                    ],
                ];
        }
    }
}
?>
