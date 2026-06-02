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
namespace base;

use app\service\ResourcesService;

/**
 * 文本转图片（基础类库：PNG 存 public/download/sensitive_data，常用于敏感联系方式等）
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2026-05-13
 * @desc     固定画布高度、宽度随文本；样式可传参覆盖，缓存文件名随样式变化
 */
class TextCreateImage
{
    /**
     * 默认绘制样式
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     可通过 ImageUrl / PngBinary 的 $params 或 $params['style'] 覆盖
     * @return   [array]  默认样式键值，各字段说明见方法内注释
     */
    public static function DefaultStyle()
    {
        return [
            // 初始字号(px)；单行过宽或字高超画布时会递减，直至 font_size_min
            'font_size'               => 22,
            // 最小字号；仍排不下则生成失败
            'font_size_min'           => 7,
            // 画布固定高度(px)，宽度随文本自适应
            'canvas_h'                => 36,
            // 左右留白(px)
            'pad_x'                   => 3,
            // 单行文本最大内宽(px)
            'max_inner_width'         => 640,
            // 相对画布高度的上下余量(px)，参与可绘字高上限
            'text_vertical_margin'    => 2,
            // 文字颜色 [R,G,B]，各分量 0～255
            'color_rgb'               => [0, 0, 0],
            // 为 true 时输出 PNG 透明底（适配深色顶栏等）；为 false 时用 bg_rgb 铺底
            'bg_transparent'          => true,
            // 背景色 [R,G,B]（仅 bg_transparent 为 false 时生效）
            'bg_rgb'                  => [255, 255, 255],
            // 字体粗细：thin/细、regular/常规，类库内映射为 public/static/common/typeface 下具体 TTF
            'font_variant'            => 'thin',
            // 参与缓存文件名（模板目录名等），不参与像素绘制
            'theme'                   => '',
        ];
    }

    /**
     * 生成或复用图片访问地址
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     文件不存在或大小为 0 时写入 public/download/sensitive_data；已存在则直接返回展示地址
     * @param    [string]          $text   [展示文本，最长 128 字符（UTF-8）]
     * @param    [array]           $params [与 DefaultStyle 键名一致可平铺或放在 params['style']；font_variant 取 thin/细、regular/常规；高级用法可传 font_candidates 覆盖]
     * @return   [string]                     [成功返回带域名的图片 URL，失败返回空字符串]
     */
    public static function ImageUrl($text, $params = [])
    {
        $text = is_string($text) ? trim($text) : '';
        if($text === '')
        {
            return '';
        }
        if(mb_strlen($text, 'UTF-8') > 128)
        {
            $text = mb_substr($text, 0, 128, 'UTF-8');
        }

        $style = self::MergeStyle($params);
        $secret = MyC('common_data_encryption_secret', 'shopxo', true);
        $filename = md5($text.$secret.'|'.self::StyleCacheKey($style)).'.png';
        $path = 'download'.DS.'sensitive_data'.DS;
        $dir_full = ROOT.'public'.DS.$path;
        $file_full = $dir_full.$filename;

        if(!file_exists($file_full) || @filesize($file_full) <= 0)
        {
            if(FileUtil::CreateDir($dir_full) !== true)
            {
                return '';
            }
            $binary = self::PngBinaryWithStyle($text, $style);
            if($binary === null || @file_put_contents($file_full, $binary, LOCK_EX) === false)
            {
                return '';
            }
        }

        return ResourcesService::AttachmentPathViewHandle(DS.$path.$filename);
    }

    /**
     * 文本渲染为 PNG 二进制（不落盘）
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     仅内存生成 PNG，不写文件；文本为空或超长返回 null
     * @param    [string]          $text   [utf-8 文本，最长 128 字符]
     * @param    [array]           $params [同 ImageUrl，用于覆盖默认样式]
     * @return   [string|null]              [成功返回 PNG 二进制，失败返回 null]
     */
    public static function PngBinary($text, $params = [])
    {
        $text = is_string($text) ? trim($text) : '';
        if($text === '' || mb_strlen($text, 'UTF-8') > 128)
        {
            return null;
        }
        return self::PngBinaryWithStyle($text, self::MergeStyle($params));
    }

    /**
     * 合并并校验绘制样式
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     以 DefaultStyle 为底，合并 params['style'] 与 params 顶层同名字段；font_variant 细/常规在合并末解析为 font_candidates
     * @param    [array]           $params [输入参数，见 ImageUrl]
     * @return   [array]                    [合并后的样式数组]
     */
    private static function MergeStyle($params)
    {
        $style = self::DefaultStyle();
        if(!empty($params['style']) && is_array($params['style']))
        {
            $style = array_merge($style, $params['style']);
        }
        foreach(array_keys(self::DefaultStyle()) as $k)
        {
            if(array_key_exists($k, $params))
            {
                $style[$k] = $params[$k];
            }
        }

        $style['font_size'] = max(1, (int) $style['font_size']);
        $style['font_size_min'] = max(1, (int) $style['font_size_min']);
        if($style['font_size_min'] > $style['font_size'])
        {
            $style['font_size_min'] = $style['font_size'];
        }
        $style['canvas_h'] = max(8, (int) $style['canvas_h']);
        $style['pad_x'] = max(0, (int) $style['pad_x']);
        $style['max_inner_width'] = max(16, (int) $style['max_inner_width']);
        $style['text_vertical_margin'] = max(0, (int) $style['text_vertical_margin']);
        $style['color_rgb'] = self::NormalizeRgb($style['color_rgb'], [0, 0, 0]);
        $style['bg_transparent'] = !empty($style['bg_transparent']);
        $style['bg_rgb'] = self::NormalizeRgb($style['bg_rgb'], [255, 255, 255]);
        $tag = isset($style['theme']) && is_string($style['theme']) ? $style['theme'] : '';
        $tag = preg_replace('/[^a-zA-Z0-9_-]/', '', $tag);
        if(strlen($tag) > 48)
        {
            $tag = substr($tag, 0, 48);
        }
        $style['theme'] = $tag;
        self::FinalizeFontCandidates($style, $params);

        return $style;
    }

    /**
     * 显式 font_candidates 优先；否则按 font_variant（thin/细、regular/常规）解析为 TTF 文件名
     * @author   Devil
     * @blog    http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @param    [array]           $style  [引用，合并中的样式]
     * @param    [array]           $params [原始入参，用于读取顶层 font_candidates]
     * @return   [void]
     */
    private static function FinalizeFontCandidates(array &$style, array $params)
    {
        $explicit = null;
        if(array_key_exists('font_candidates', $params) && is_array($params['font_candidates']))
        {
            $explicit = $params['font_candidates'];
        }
        if($explicit === null && isset($style['font_candidates']) && is_array($style['font_candidates']))
        {
            $explicit = $style['font_candidates'];
        }
        if($explicit !== null)
        {
            $explicit = array_values(array_filter($explicit, function ($v) {
                return is_string($v) && $v !== '';
            }));
        }
        if(!empty($explicit))
        {
            $style['font_candidates'] = $explicit;
        } else {
            $style['font_candidates'] = self::FontVariantToFilenames(isset($style['font_variant']) ? $style['font_variant'] : 'thin');
        }
        unset($style['font_variant']);
    }

    /**
     * font_variant 与 typeface 下文件名映射（细/常规）
     * @author   Devil
     * @blog    http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @param    [mixed]           $variant [thin|细、regular|常规，其它按 thin]
     * @return   [array]
     */
    private static function FontVariantToFilenames($variant)
    {
        if(!is_string($variant))
        {
            return ['Alibaba-PuHuiTi-Thin.ttf'];
        }
        $v = trim($variant);
        $ascii = strtolower($v);
        if($ascii === 'thin' || $v === '细')
        {
            return ['Alibaba-PuHuiTi-Thin.ttf'];
        }
        if($ascii === 'regular' || $v === '常规')
        {
            return ['Alibaba-PuHuiTi-Regular.ttf'];
        }
        return ['Alibaba-PuHuiTi-Thin.ttf'];
    }

    /**
     * 归一化 RGB 颜色
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     非数组或分量不足时使用 $fallback；各分量限制在 0～255
     * @param    [mixed]           $rgb        [颜色，形如 [R,G,B] 的索引数组]
     * @param    [array]           $fallback   [回退 RGB，长度 3]
     * @return   [array]                       [长度为 3 的整型 RGB]
     */
    private static function NormalizeRgb($rgb, $fallback)
    {
        if(!is_array($rgb) || count($rgb) < 3)
        {
            return $fallback;
        }
        $out = [];
        for($i = 0; $i < 3; $i++)
        {
            $v = (int) $rgb[$i];
            if($v < 0)
            {
                $v = 0;
            }
            if($v > 255)
            {
                $v = 255;
            }
            $out[] = $v;
        }
        return $out;
    }

    /**
     * 样式参与缓存文件名的稳定摘要
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     对样式数组递归 ksort 后 json 编码再 md5，保证相同样式得到相同缓存键
     * @param    [array]           $style [已合并、已校验的样式]
     * @return   [string]                 [32 位小写 md5 摘要]
     */
    private static function StyleCacheKey($style)
    {
        $s = $style;
        self::KsortRecursive($s);
        return md5(json_encode($s, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 递归按键名排序数组
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     用于生成稳定的 StyleCacheKey；子数组同样递归排序
     * @param    [array]           $arr [引用传入，原地排序]
     * @return   [void]
     */
    private static function KsortRecursive(&$arr)
    {
        if(!is_array($arr))
        {
            return;
        }
        ksort($arr);
        foreach($arr as &$v)
        {
            if(is_array($v))
            {
                self::KsortRecursive($v);
            }
        }
    }

    /**
     * 按已合并样式将文本绘制成 PNG 二进制
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2026-05-13
     * @desc     在 public/static/common/typeface 下按 font_candidates 寻找首个可用 TTF；画布高度固定，宽度随文本；超长或过高时递减字号直至满足 max_inner_width 与 max_text_h
     * @param    [string]          $text  [utf-8 文本]
     * @param    [array]           $style [已 MergeStyle 的样式]
     * @return   [string|null]           [PNG 二进制，环境不支持 GD/字体或无法排下时返回 null]
     */
    private static function PngBinaryWithStyle($text, $style)
    {
        $font_dir = ROOT.'public'.DS.'static'.DS.'common'.DS.'typeface'.DS;
        $font_path = '';
        foreach($style['font_candidates'] as $font_file)
        {
            $p = $font_dir.$font_file;
            if(file_exists($p))
            {
                $font_path = $p;
                break;
            }
        }
        if(!function_exists('imagecreatetruecolor') || $font_path === '' || !function_exists('imagettfbbox') || !function_exists('imagettftext'))
        {
            return null;
        }

        $canvas_h = $style['canvas_h'];
        $pad_x = $style['pad_x'];
        $max_inner_width = $style['max_inner_width'];
        $max_text_h = $canvas_h - $style['text_vertical_margin'];
        if($max_text_h < 4)
        {
            $max_text_h = max(4, $canvas_h - 1);
        }
        $color_rgb = $style['color_rgb'];
        $bg_rgb = $style['bg_rgb'];

        $font_size = $style['font_size'];
        $font_size_min = $style['font_size_min'];

        while($font_size >= $font_size_min)
        {
            $box = imagettfbbox($font_size, 0, $font_path, $text);
            if($box === false)
            {
                return null;
            }
            $minx = min($box[0], $box[2], $box[4], $box[6]);
            $maxx = max($box[0], $box[2], $box[4], $box[6]);
            $miny = min($box[1], $box[3], $box[5], $box[7]);
            $maxy = max($box[1], $box[3], $box[5], $box[7]);
            $text_width = $maxx - $minx;
            $text_height = $maxy - $miny;
            if($text_width <= $max_inner_width && $text_height <= $max_text_h)
            {
                break;
            }
            $font_size--;
        }
        if($font_size < $font_size_min)
        {
            return null;
        }

        $box = imagettfbbox($font_size, 0, $font_path, $text);
        if($box === false)
        {
            return null;
        }
        $minx = min($box[0], $box[2], $box[4], $box[6]);
        $maxx = max($box[0], $box[2], $box[4], $box[6]);
        $miny = min($box[1], $box[3], $box[5], $box[7]);
        $maxy = max($box[1], $box[3], $box[5], $box[7]);
        $text_width = $maxx - $minx;
        $text_height = $maxy - $miny;
        $img_w = (int) ceil($text_width + $pad_x * 2);
        $img_h = (int) $canvas_h;

        $im = imagecreatetruecolor($img_w, $img_h);
        if($im === false)
        {
            return null;
        }
        if(!empty($style['bg_transparent']))
        {
            imagesavealpha($im, true);
            imagealphablending($im, false);
            $bg = imagecolorallocatealpha($im, 0, 0, 0, 127);
            imagefilledrectangle($im, 0, 0, $img_w, $img_h, $bg);
            imagealphablending($im, true);
        } else {
            $bg = imagecolorallocate($im, $bg_rgb[0], $bg_rgb[1], $bg_rgb[2]);
            imagefilledrectangle($im, 0, 0, $img_w, $img_h, $bg);
        }
        $fg = imagecolorallocate($im, $color_rgb[0], $color_rgb[1], $color_rgb[2]);

        $x = (int) round($pad_x - $minx);
        $y = (int) round(($canvas_h - $text_height) / 2 - $miny);
        imagettftext($im, $font_size, 0, $x, $y, $fg, $font_path, $text);

        ob_start();
        imagepng($im);
        $binary = ob_get_clean();
        imagedestroy($im);

        return ($binary === false || $binary === '') ? null : $binary;
    }
}
?>