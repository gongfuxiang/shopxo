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
 * 标签可视化打印
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-10-14
 * @desc    坐标模板 JSON 编译为飞鹅标签机指令，与小票设计器互不引用
 */
class LabelPrintHandleModule
{
    /**
     * 组件类型（业务字段由调用方传入）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $params [business 业务字段]
     */
    public static function TypeData($params = [])
    {
        $business = [];
        if(!empty($params['business']) && is_array($params['business']))
        {
            $business = self::NormalizeFields($params['business']);
        } elseif(!empty($params['control']) || (isset($params['is_plugins']) && intval($params['is_plugins']) == 1))
        {
            // 与订单一致：优先读列表 form，再追加插件专用字段
            $business = self::FormDataToLabelFields($params);
        }
        if(!empty($params['label_print_extra_fields']) && is_array($params['label_print_extra_fields']))
        {
            $extra = self::NormalizeFields($params['label_print_extra_fields']);
            $business = empty($business) ? $extra : self::MergeFieldsByKey($business, $extra);
        }
        $assist = [
            [
                'type'   => 'text',
                'field'  => '',
                'title'  => MyLang('label_print.assist_custom_text'),
                'custom' => 1,
                'sample' => MyLang('label_print.assist_custom_text'),
            ],
            [
                'type'   => 'qr',
                'field'  => '',
                'title'  => MyLang('label_print.assist_qr'),
                'custom' => 1,
                'sample' => 'QR',
            ],
            [
                'type'   => 'barcode',
                'field'  => '',
                'title'  => MyLang('label_print.assist_barcode'),
                'custom' => 1,
                'sample' => '12345678',
            ],
        ];
        return [
            'business' => $business,
            'assist'   => $assist,
        ];
    }

    /**
     * 按 field 合并字段（后者不覆盖已有 field）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
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
     * 从列表 form 读取文本字段，再追加 label_print_extra_fields
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $params [control/is_plugins/group]
     */
    public static function FormDataToLabelFields($params = [])
    {
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
        if(!empty($form_data['form']) && is_array($form_data['form']))
        {
            foreach($form_data['form'] as $v)
            {
                if(empty($v) || !is_array($v) || !isset($v['view_key']) || $v['view_key'] === '' || $v['view_key'] === null || !isset($v['label']) || $v['label'] === '' || $v['label'] === null)
                {
                    continue;
                }
                if(!isset($v['view_type']) || $v['view_type'] != 'field')
                {
                    continue;
                }
                // view_key 可能是数组（如库存+单位），拆成多个可拖字段
                $keys = is_array($v['view_key']) ? $v['view_key'] : [$v['view_key']];
                $multi = count($keys) > 1;
                foreach($keys as $field)
                {
                    if(!is_string($field) && !is_numeric($field))
                    {
                        continue;
                    }
                    $field = strval($field);
                    if($field === '' || isset($seen[$field]))
                    {
                        continue;
                    }
                    $seen[$field] = 1;
                    $title = $v['label'];
                    if($multi)
                    {
                        // 多字段列：标题后带字段名，避免同名
                        $title = $v['label'].'('.$field.')';
                    }
                    $label = array_key_exists('label_print_label', $v) ? $v['label_print_label'] : ($title.'：');
                    $result[] = [
                        'type'   => 'text',
                        'field'  => $field,
                        'title'  => $title,
                        'label'  => $label,
                        'sample' => '',
                    ];
                }
            }
        }
        if(!empty($form_data['label_print_extra_fields']) && is_array($form_data['label_print_extra_fields']))
        {
            foreach($form_data['label_print_extra_fields'] as $v)
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
                $v['type'] = 'text';
                $result[] = $v;
            }
        }
        return self::NormalizeFields($result);
    }

    /**
     * 设计器页面数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $params [form_name/config/business/sample_data/paper_url/show_repeat/default_template]
     */
    public static function DesignerModuleData($params = [])
    {
        $config = self::ParseTemplate(isset($params['config']) ? $params['config'] : '');
        if(!self::IsValidTemplate($config) && !empty($params['default_template']) && is_array($params['default_template']))
        {
            $config = self::ParseTemplate($params['default_template']);
        }
        if(empty($config))
        {
            $config = self::EmptyTemplate();
        }
        return [
            'form_name'         => empty($params['form_name']) ? 'label_print_template' : $params['form_name'],
            'config'            => $config,
            'type_data'         => self::TypeData($params),
            'sample_data'       => empty($params['sample_data']) || !is_array($params['sample_data']) ? [] : $params['sample_data'],
            'paper_url'         => empty($params['paper_url']) ? '' : $params['paper_url'],
            'show_repeat'       => empty($params['show_repeat']) ? 0 : 1,
            'show_repeat_item'  => (isset($params['show_repeat_item']) && empty($params['show_repeat_item'])) ? 0 : 1,
            'show_repeat_qty'   => empty($params['show_repeat_qty']) ? 0 : 1,
            'repeat_once_text'  => empty($params['repeat_once_text']) ? '' : $params['repeat_once_text'],
            'repeat_item_text'  => empty($params['repeat_item_text']) ? '' : $params['repeat_item_text'],
            'repeat_qty_text'   => empty($params['repeat_qty_text']) ? '' : $params['repeat_qty_text'],
            'popup'             => empty($params['popup']) ? 0 : 1,
            'popup_id'          => empty($params['popup_id']) ? '' : $params['popup_id'],
            'popup_title'       => empty($params['popup_title']) ? '' : $params['popup_title'],
            'designed'          => empty($params['designed']) ? 0 : 1,
        ];
    }

    /**
     * 空白模板
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     */
    public static function EmptyTemplate()
    {
        return [
            'width'     => 60,
            'height'    => 40,
            'direction' => 1,
            'repeat'    => 'once',
            'elements'  => [],
        ];
    }

    /**
     * 解析模板
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [mixed]        $template [模板]
     */
    public static function ParseTemplate($template)
    {
        if(is_string($template))
        {
            $template = json_decode(htmlspecialchars_decode($template), true);
        }
        if(empty($template) || !is_array($template))
        {
            return [];
        }
        $width = isset($template['width']) ? intval($template['width']) : 60;
        $height = isset($template['height']) ? intval($template['height']) : 40;
        if($width < 20)
        {
            $width = 20;
        }
        if($width > 150)
        {
            $width = 150;
        }
        if($height < 10)
        {
            $height = 10;
        }
        if($height > 200)
        {
            $height = 200;
        }
        $direction = (isset($template['direction']) && intval($template['direction']) === 0) ? 0 : 1;
        $repeat = (isset($template['repeat']) && in_array($template['repeat'], ['item', 'qty'], true)) ? $template['repeat'] : 'once';
        $elements = [];
        if(!empty($template['elements']) && is_array($template['elements']))
        {
            foreach($template['elements'] as $el)
            {
                if(empty($el) || !is_array($el) || empty($el['type']))
                {
                    continue;
                }
                if(!in_array($el['type'], ['text', 'qr', 'barcode']))
                {
                    continue;
                }
                $elements[] = $el;
            }
        }
        return [
            'width'     => $width,
            'height'    => $height,
            'direction' => $direction,
            'repeat'    => $repeat,
            'elements'  => $elements,
        ];
    }

    /**
     * 是否可打印
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [mixed]        $template [模板]
     */
    public static function IsValidTemplate($template)
    {
        $template = is_array($template) && isset($template['elements']) ? $template : self::ParseTemplate($template);
        return !empty($template['elements']) && is_array($template['elements']);
    }

    /**
     * 按商品数量打印的张数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-15
     * @param   [mixed]        $number [购买或售后数量]
     */
    public static function RepeatTimes($number)
    {
        $times = is_numeric($number) ? intval($number) : 0;
        if($times < 1)
        {
            $times = 1;
        }
        if($times > 99)
        {
            $times = 99;
        }
        return $times;
    }

    /**
     * 单独下发的纸张指令（不可与打印内容同时发送）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [mixed]        $template [模板或宽高]
     */
    public static function PaperContent($template)
    {
        if(is_array($template) && isset($template['width']))
        {
            $width = intval($template['width']);
            $height = intval($template['height']);
        } else {
            $width = 60;
            $height = 40;
        }
        if($width < 20)
        {
            $width = 20;
        }
        if($height < 10)
        {
            $height = 10;
        }
        return '<SIZE>'.$width.','.$height.'</SIZE>';
    }

    /**
     * 编译为标签打印内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [mixed]        $template [模板]
     * @param   [array]        $data     [业务数据]
     */
    public static function Compile($template, $data = [])
    {
        $template = is_array($template) && isset($template['elements']) ? self::ParseTemplate($template) : self::ParseTemplate($template);
        if(empty($template) || empty($template['elements']))
        {
            return '';
        }
        if(empty($data) || !is_array($data))
        {
            $data = [];
        }
        $content = '<DIRECTION>'.intval($template['direction']).'</DIRECTION>';
        $max_x = $template['width'] * 8;
        $max_y = $template['height'] * 8;
        foreach($template['elements'] as $el)
        {
            $piece = self::CompileElement($el, $data, $max_x, $max_y);
            if($piece !== '')
            {
                $content .= $piece;
            }
        }
        return $content;
    }

    /**
     * 编译单个元素
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $el    [元素]
     * @param   [array]        $data  [数据]
     * @param   [int]          $max_x [纸宽点]
     * @param   [int]          $max_y [纸高点]
     */
    public static function CompileElement($el, $data, $max_x, $max_y)
    {
        $type = $el['type'];
        $value = self::ElementValue($el, $data);
        $hide_empty = !isset($el['hide_empty']) || intval($el['hide_empty']) == 1;
        if($type !== 'text')
        {
            if($value === '' || $value === null)
            {
                return '';
            }
        }
        $x = self::Clamp(isset($el['x']) ? intval($el['x']) : 0, 0, $max_x);
        $y = self::Clamp(isset($el['y']) ? intval($el['y']) : 0, 0, $max_y);
        $align = self::ElementAlign($el);
        if($type == 'qr')
        {
            $level = self::QrLevel(isset($el['e']) ? $el['e'] : 'L');
            $w = self::Clamp(isset($el['w']) ? intval($el['w']) : 5, 1, 10);
            if($align !== '')
            {
                $x = self::AlignX($align, self::QrSide($value, $level, $w), $max_x, self::AlignGap($el), self::AlignSafe($align, 'qr'));
            }
            return '<QR x="'.$x.'" y="'.$y.'" e="'.$level.'" w="'.$w.'">'.self::Escape($value).'</QR>';
        }
        if($type == 'barcode')
        {
            $code = (isset($el['code']) && strtoupper($el['code']) === 'BC39') ? 'BC39' : 'BC128';
            $h = self::Clamp(isset($el['h']) ? intval($el['h']) : 80, 1, 255);
            $s = (isset($el['s']) && intval($el['s']) === 0) ? 0 : 1;
            $r = self::Rotate(isset($el['r']) ? $el['r'] : 0);
            $n = self::Clamp(isset($el['n']) ? intval($el['n']) : 1, 1, 10);
            $w = self::Clamp(isset($el['w']) ? intval($el['w']) : 1, 1, 10);
            $text = self::BarcodeText($value);
            if($text === '')
            {
                return '';
            }
            $span = self::BarcodeWidth($text, $code, $n, $w);
            $box_h = $h + ($s === 0 ? 0 : 24);
            $visual_w = ($r === 90 || $r === 270) ? $box_h : $span;
            if($align !== '')
            {
                $x = self::AlignX($align, $visual_w, $max_x, self::AlignGap($el), self::AlignSafe($align, 'barcode'));
            }
            $anchor = self::RotateAnchor($r, $x, $y, $span, $box_h, $max_x, $max_y);
            return '<'.$code.' x="'.$anchor[0].'" y="'.$anchor[1].'" h="'.$h.'" s="'.$s.'" r="'.$r.'" n="'.$n.'" w="'.$w.'">'.$text.'</'.$code.'>';
        }

        $label = isset($el['label']) ? strval($el['label']) : '';
        $text = $label.$value;
        if($hide_empty && ($value === '' || $value === null))
        {
            return '';
        }
        if($text === '')
        {
            return '';
        }
        $font = self::Font(isset($el['font']) ? $el['font'] : 12);
        $scale_w = self::Clamp(isset($el['w']) ? intval($el['w']) : 1, 1, 10);
        $scale_h = self::Clamp(isset($el['h']) ? intval($el['h']) : 1, 1, 10);
        $r = self::Rotate(isset($el['r']) ? $el['r'] : 0);
        $gap = self::AlignGap($el);
        $safe = self::AlignSafe($align);
        $wrap = ($align !== '') ? max(1, $max_x - (($align === 'center') ? 0 : $gap) - $safe) : ($max_x - $x);
        $lines = self::WrapLines($text, $font, $scale_w, $wrap);
        $metric = self::FontMetric($font);
        $line_h = intval($metric[1] * $scale_h);
        if($line_h < 12)
        {
            $line_h = 12;
        }
        $html = '';
        foreach($lines as $i=>$line)
        {
            $yy = $y + ($i * $line_h);
            if($yy > $max_y)
            {
                break;
            }
            $line_w = self::TextWidth($line, $font, $scale_w);
            $line_x = ($align !== '') ? self::AlignX($align, $line_w, $max_x, $gap, $safe) : $x;
            $anchor = self::RotateAnchor($r, $line_x, $yy, $line_w, $line_h, $max_x, $max_y);
            $html .= '<TEXT x="'.$anchor[0].'" y="'.$anchor[1].'" font="'.$font.'" w="'.$scale_w.'" h="'.$scale_h.'" r="'.$r.'">'.self::Escape($line).'</TEXT>';
        }
        return $html;
    }

    /**
     * 元素取值：绑定字段优先，否则用自定义内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $el   [元素]
     * @param   [array]        $data [数据]
     */
    public static function ElementValue($el, $data)
    {
        $field = isset($el['field']) ? strval($el['field']) : '';
        if($field !== '' && is_array($data) && array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '')
        {
            $value = $data[$field];
        } else {
            $value = isset($el['value']) ? $el['value'] : '';
        }
        if(is_array($value))
        {
            $value = implode(' ', $value);
        }
        return str_replace(["\r", "\n", "\t"], ' ', strval($value));
    }

    /**
     * 文本转义（小于号、大于号）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $text [文本]
     */
    public static function Escape($text)
    {
        return str_replace(['<', '>'], ['&lt;', '&gt;'], strval($text));
    }

    /**
     * 按剩余宽度折行
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $text    [文本]
     * @param   [int]          $font    [字号]
     * @param   [int]          $scale_w [宽度倍率]
     * @param   [int]          $max     [剩余点数]
     */
    public static function WrapLines($text, $font, $scale_w, $max)
    {
        $text = strval($text);
        if($text === '')
        {
            return [];
        }
        $metric = self::FontMetric($font);
        $full = max(8, intval($metric[0] * $scale_w));
        $half = max(4, intval($full / 2));
        if($max < $half)
        {
            $max = $full * 8;
        }
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if(empty($chars))
        {
            return [$text];
        }
        $lines = [];
        $line = '';
        $width = 0;
        foreach($chars as $ch)
        {
            $cw = (strlen($ch) === 1) ? $half : $full;
            if($line !== '' && ($width + $cw) > $max)
            {
                $lines[] = $line;
                $line = '';
                $width = 0;
                if(count($lines) >= 8)
                {
                    break;
                }
            }
            $line .= $ch;
            $width += $cw;
        }
        if($line !== '' && count($lines) < 8)
        {
            $lines[] = $line;
        }
        return empty($lines) ? [$text] : $lines;
    }

    /**
     * 字体宽高（点）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [int]          $font [字体编号]
     */
    public static function FontMetric($font)
    {
        $map = [
            1  => [8, 12],
            2  => [12, 20],
            3  => [16, 24],
            4  => [24, 32],
            5  => [32, 48],
            12 => [24, 24],
        ];
        $font = intval($font);
        return isset($map[$font]) ? $map[$font] : $map[12];
    }

    /**
     * 字体编号
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [mixed]        $font [字体]
     */
    public static function Font($font)
    {
        $font = intval($font);
        if(in_array($font, [1, 2, 3, 4, 5, 12]))
        {
            return $font;
        }
        return 12;
    }

    /**
     * 旋转角度
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [mixed]        $r [角度]
     */
    public static function Rotate($r)
    {
        $r = intval($r);
        return in_array($r, [0, 90, 180, 270]) ? $r : 0;
    }

    /**
     * 对齐方式（空为自由拖动）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $el [元素]
     */
    public static function ElementAlign($el)
    {
        $align = (is_array($el) && isset($el['align'])) ? strval($el['align']) : '';
        return in_array($align, ['left', 'center', 'right'], true) ? $align : '';
    }

    /**
     * 按内容宽度计算横坐标
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $align  [left/center/right]
     * @param   [int]          $width  [内容宽度，点]
     * @param   [int]          $paper  [纸张宽度，点]
     */
    public static function AlignGap($el)
    {
        $align = self::ElementAlign($el);
        if($align !== 'left' && $align !== 'right')
        {
            return 0;
        }
        $gap = (is_array($el) && isset($el['gap'])) ? intval($el['gap']) : 0;
        return ($gap < 0) ? 0 : $gap;
    }

    /**
     * 居右打印时固定多让出的点数，避开纸边打不全
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $align [对齐]
     * @param   [string]       $type  [text/qr/barcode]
     */
    public static function AlignSafe($align, $type = 'text')
    {
        if($align !== 'right')
        {
            return 0;
        }
        $safe = 30;
        if($type === 'qr' || $type === 'barcode')
        {
            $safe += 3;
        }
        return $safe;
    }

    /**
     * 设计器坐标是旋转后外框的左上角。飞鹅以该点顺时针旋转，180 度时内容画在点的左上方。
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [int]          $r      [角度]
     * @param   [int]          $x      [外框横坐标]
     * @param   [int]          $y      [外框纵坐标]
     * @param   [int]          $width  [未旋转宽度]
     * @param   [int]          $height [未旋转高度]
     * @param   [int]          $max_x  [纸宽点]
     * @param   [int]          $max_y  [纸高点]
     */
    public static function RotateAnchor($r, $x, $y, $width, $height, $max_x, $max_y)
    {
        $x = intval($x);
        $y = intval($y);
        $width = max(0, intval($width));
        $height = max(0, intval($height));
        if(intval($r) === 180)
        {
            $x += $width;
            $y += $height;
        }
        if($x < 0)
        {
            $x = 0;
        }
        if($y < 0)
        {
            $y = 0;
        }
        if($x > $max_x)
        {
            $x = $max_x;
        }
        if($y > $max_y)
        {
            $y = $max_y;
        }
        return [$x, $y];
    }

    public static function AlignX($align, $width, $paper, $gap = 0, $safe = 0)
    {
        $width = intval($width);
        $paper = intval($paper);
        $gap = intval($gap);
        $safe = intval($safe);
        if($gap < 0)
        {
            $gap = 0;
        }
        if($safe < 0)
        {
            $safe = 0;
        }
        if($align == 'center')
        {
            $x = intval(($paper - $width) / 2);
        } else if($align == 'right')
        {
            $x = $paper - $width - $gap - $safe;
        } else {
            $x = $gap;
        }
        if($x < 0)
        {
            $x = 0;
        }
        if($x > $paper)
        {
            $x = $paper;
        }
        return $x;
    }

    /**
     * 文本宽度（点）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $text    [文本]
     * @param   [int]          $font    [字体]
     * @param   [int]          $scale_w [宽度倍率]
     */
    public static function TextWidth($text, $font, $scale_w)
    {
        $metric = self::FontMetric($font);
        $full = max(8, intval($metric[0] * $scale_w));
        $half = max(4, intval($full / 2));
        $chars = preg_split('//u', strval($text), -1, PREG_SPLIT_NO_EMPTY);
        if(empty($chars))
        {
            return $full;
        }
        $width = 0;
        foreach($chars as $ch)
        {
            $width += (strlen($ch) === 1) ? $half : $full;
        }
        return max($width, $full);
    }

    /**
     * 二维码边长（点）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $text  [内容]
     * @param   [string]       $level [纠错]
     * @param   [int]          $cell  [单元格宽度]
     */
    public static function QrSide($text, $level, $cell)
    {
        $len = function_exists('mb_strlen') ? mb_strlen(strval($text), 'UTF-8') : strlen(strval($text));
        if($len < 1)
        {
            $len = 1;
        }
        $ratio = ['L'=>1, 'M'=>0.8, 'Q'=>0.62, 'H'=>0.45];
        $level = strtoupper(strval($level));
        $factor = isset($ratio[$level]) ? $ratio[$level] : 1;
        $caps = [17, 32, 53, 78, 106, 134, 154, 192, 230, 271, 321, 367, 425, 458, 520];
        $version = count($caps);
        foreach($caps as $i=>$cap)
        {
            if($len <= intval(floor($cap * $factor)))
            {
                $version = $i + 1;
                break;
            }
        }
        $cell = self::Clamp($cell, 1, 10);
        return (21 + ($version - 1) * 4) * $cell;
    }

    /**
     * 条码宽度（点）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $text   [内容]
     * @param   [string]       $code   [BC128/BC39]
     * @param   [int]          $narrow [窄条]
     * @param   [int]          $wide   [宽条]
     */
    public static function BarcodeWidth($text, $code, $narrow, $wide)
    {
        $narrow = self::Clamp($narrow, 1, 10);
        $wide = self::Clamp($wide, 1, 10);
        $chars = strlen(strval($text));
        if($chars < 1)
        {
            $chars = 1;
        }
        if(strtoupper(strval($code)) == 'BC39')
        {
            $width = ($chars + 2) * (3 * $wide + 7 * $narrow);
        } else {
            $width = intval(round((11 * $chars + 35) * $narrow * (0.65 + 0.35 * ($wide / $narrow))));
        }
        return max(8, $width);
    }

    /**
     * 二维码纠错等级
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $level [等级]
     */
    public static function QrLevel($level)
    {
        $level = strtoupper(strval($level));
        return in_array($level, ['L', 'M', 'Q', 'H']) ? $level : 'L';
    }

    /**
     * 条码内容只保留可打印字符
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [string]       $text [内容]
     */
    public static function BarcodeText($text)
    {
        $text = strval($text);
        $text = preg_replace('/\s+/', '', $text);
        return str_replace(['<', '>'], '', $text);
    }

    /**
     * 数值限制
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [int]          $n   [值]
     * @param   [int]          $min [最小]
     * @param   [int]          $max [最大]
     */
    public static function Clamp($n, $min, $max)
    {
        $n = intval($n);
        if($n < $min)
        {
            return $min;
        }
        if($n > $max)
        {
            return $max;
        }
        return $n;
    }

    /**
     * 规范字段
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-14
     * @param   [array]        $list [字段]
     */
    public static function NormalizeFields($list)
    {
        $result = [];
        $seen = [];
        foreach($list as $v)
        {
            if(empty($v) || !is_array($v) || empty($v['title']))
            {
                continue;
            }
            $type = empty($v['type']) ? 'text' : $v['type'];
            if(!in_array($type, ['text', 'qr', 'barcode']))
            {
                $type = 'text';
            }
            $field = isset($v['field']) ? strval($v['field']) : '';
            $key = $type.'|'.$field;
            if($field !== '' && isset($seen[$key]))
            {
                continue;
            }
            if($field !== '')
            {
                $seen[$key] = 1;
            }
            $result[] = [
                'type'   => $type,
                'field'  => $field,
                'title'  => strval($v['title']),
                'label'  => isset($v['label']) ? strval($v['label']) : '',
                'sample' => isset($v['sample']) ? $v['sample'] : '',
                'custom' => empty($v['custom']) ? 0 : 1,
            ];
        }
        return $result;
    }
}
?>
