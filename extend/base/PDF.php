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
use TCPDF;

/**
 * PDF
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-03-07
 * @desc    html转pdf采用mpdt库、配置转pdf采用tcpdf库
 */
class PDF extends TCPDF
{
    public $filename;
    public $root_path;
    public $path;
    public $output_type;
    public $title;
    public $is_header;
    public $is_footer;
    public $header_logo;
    public $header_name;
    public $footer_content;
    public $background_images;
    public $watermark;

    /**
     * 参数初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-07
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        parent::__construct();
        // 标题
        $this->filename = empty($params['filename']) ? date('YmdHis').'.pdf' : $params['filename'];

        // 输出类型（I:在浏览器中打开、D:下载、F:在服务器生成pdf、S:只返回pdf的字符串）
        $this->output_type = empty($params['output_type']) ? 'I' : $params['output_type'];

        // 标题
        if(!empty($params['title']))
        {
            $this->title = $params['title'];
        }

        // 页眉页脚
        $this->is_header = isset($params['is_header']) ? $params['is_header'] : false;
        $this->is_footer = isset($params['is_footer']) ? $params['is_footer'] : false;

        // 头信息logo、名称
        if(!empty($params['header_logo']))
        {
            $this->header_logo = $params['header_logo'];
        }
        if(!empty($params['header_name']))
        {
            $this->header_name = $params['header_name'];
        }
        // 页脚内容
        if(!empty($params['footer_content']))
        {
            $this->footer_content = $params['footer_content'];
        }

        // 背景图片
        if(!empty($params['background_images']))
        {
            $this->background_images = $params['background_images'];
        }

        // 水印
        if(!empty($params['watermark']))
        {
            $this->watermark = $params['watermark'];
        }

        // 存储位置
        $this->root_path = isset($params['root_path']) ? $params['root_path'] : ROOT.'public';
        $this->path = isset($params['path']) ? $params['path'] : DS.'static'.DS.'upload'.DS.'file'.DS.'pdf'.DS.date('Y').DS.date('m').DS.date('d').DS;
    }

    /**
     * TCPDF基础初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-07
     * @desc    采用TCPDF组件
     * @param   [array]           $params [输入参数]
     * @return  [object]                  [实例对象]
     */
    public function BaseInit($params = [])
    {
        // 头页脚
        $this->setPrintHeader($this->is_header);
        $this->setPrintFooter($this->is_footer);

        // 页脚信息
        $this->setFooterData(array(0,64,0), array(0,64,128));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // 自动分页 (第二个参数可以设置距离底部多少距离时分页)
        $this->setAutoPageBreak(true, 15);

        // 设置边距(左 上 右 下) 右边距默认左侧值 下边距是bool值(是否覆盖默认页边距)
        $this->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // 定义默认的单间距字体 (设置为等宽字体)
        $this->SetDefaultMonospacedFont('courier');

        // 设置图像比例因子
        $this->setImageScale(1.25);

        // 设置字体
        $this->SetFont('stsongstdlight', '', 12, '', true);

        // 标题
        if(!empty($this->title))
        {
            $this->setTitle($this->title);
        }

        // 新增页面
        $this->AddPage();
    }

    /**
     * 头设置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-09
     * @desc    description
     */
    public function Header()
    {
        // 继承父级
        parent::Header();
        // 背景图
        $this->BackgroundImage();
        // 头logo
        if(!empty($this->header_logo))
        {
            $this->Image($this->header_logo, 15, 4, 30, 6);
        }
        // 头名称
        if(!empty($this->header_name))
        {
            $this->SetFont('stsongstdlight', '', 12, '', true);
            $this->Cell(0, 16, $this->header_name, 0, 1, 'R');
        }
    }

    /**
     * 尾设置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-09
     * @desc    description
     */
    public function Footer()
    {
        // 继承父级
        parent::Footer();
        // 背景图
        $this->BackgroundImage();
        // 页脚内容
        if(!empty($this->footer_content))
        {
            $this->SetY(-15);
            $this->SetFont('stsongstdlight', '', 8);
            $this->setTextColor(136, 136, 136);
            $this->Cell(0, 15, $this->footer_content, 0, 0, 'C');
        }
    }

    /**
     * 设置背景图
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-09
     * @desc    description
     */
    public function BackgroundImage()
    {
        if(!empty($this->background_images))
        {
            // get the current page break margin
            $break_margin = $this->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $this->AutoPageBreak;
            $this->SetAlpha(0.08);
            // disable auto-page-break
            $this->SetAutoPageBreak(false, 0);
            // set background image
            $this->Image($this->background_images, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $break_margin);
            // set the starting point for the page content
            $this->setPageMark();
            $this->SetAlpha(1);
        }
    }

    /**
     * html转PDF
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-07
     * @desc    采用MPDF组件
     * @param   [array]           $params [输入参数]
     */
    public function HtmlToPDF($params = [])
    {
        // 基础参数
        if(empty($params['html']))
        {
            return DataReturn(MyLang('common_extend.base.pdf.content_empty_tips'), -1);
        }

        // 生成pdf对象
        $pdf = new \Mpdf\Mpdf([
            // 自动匹配语言字体
            'autoScriptToLang'  => true,
            'autoLangToFont'    => true,
            // 指定字体、避免中文符号乱码
            'default_font'      => 'gb',
            // 编码
            'mode'              => 'utf-8',
        ]);

        // 参数一是图片的位置(图片相对目录 为处理脚本的相对目录)，参数二是透明度0.1-1
        if(!empty($this->watermark))
        {
            $pdf->SetWatermarkImage($this->watermark, 0.5);
            $pdf->showWatermarkImage = true;
        }

        // 设置PDF页眉内容
        if($this->is_header && (!empty($this->header_logo) || !empty($this->header_name)))
        {
            $header = '<table width="100%" style="margin:0 auto;border-bottom: 1px solid #666; vertical-align: middle; font-family:serif;"><tr>';
            $header .= '<td width="40%">';
            if(!empty($this->header_logo))
            {
                $header .= '<img src="'.$this->header_logo.'" height="15" />';
            }
            $header .= '</td>';
            $header .= '<td width="40%" align="right" style="font-size: 9pt; color: #666;">';
            if(!empty($this->header_name))
            {
                $header .= $this->header_name;
            }
            $header .= '</td>';
            $header .= '</tr></table>';
            $pdf->SetHTMLHeader($header);
        }

        // 页脚内容
        if($this->is_footer)
        {
            // 设置PDF页脚内容 在页脚html中添加 {PAGENO}/{nb} (当前页/总页数) 可添加页码
            $footer = '<table width="100%" style=" vertical-align: bottom; font-family:serif; font-size: 9pt;"><tr>
                    <td width="10%"></td>';
            if(!empty($this->footer_content))
            {
                $footer .= '<td width="80%" align="center" style="font-size:14px;color:#999">'.$this->footer_content.'</td>';
            }
            $footer .= '<td width="10%" align="right" style="color: #666;">{PAGENO}/{nb}</td>
                </tr></table>';
            $pdf->SetHTMLFooter($footer);
        }

        // 存储目录校验
        $dir = $this->IsMkdir();
        if($dir['code'] != 0)
        {
            return $dir;
        }

        // 标题
        if(!empty($this->title))
        {
            $pdf->SetTitle($this->title);
        }

        // 加入内容
        $pdf->WriteHTML($params['html']);
    
        // PDF输出   I：在浏览器中打开，D：下载，F：在服务器生成pdf ，S：只返回pdf的字符串（此模式下$filename会被忽视）
        $type = empty($params['type']) ? 'I' : $params['type'];
        $file = ($type == 'F') ? $dir['data'].$this->filename : $this->filename;
        $pdf->Output($file, $type);

        // 服务器生成则返回
        if($type == 'F')
        {
            $result = [
                'dir'       => $dir['data'].$this->filename,
                'root'      => $this->root_path,
                'path'      => $this->path,
                'filename'  => $this->filename,
                'url'       => ResourcesService::AttachmentPathViewHandle($this->path.$this->filename),
            ];
            return DataReturn(MyLang('operate_success'), 0, $result);
        }
        die;
    }

    /**
     * 路径不存在则创建
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     */
    private function IsMkdir()
    {
        $dir = str_replace(['//', '\\\\'], ['/', '\\'], $this->root_path.$this->path);
        if(!is_dir($dir))
        {
            // 创建目录
            if(mkdir($dir, 0777, true) === false)
            {
                return DataReturn(MyLang('common_extend.base.pdf.dir_create_fail_tips'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $dir);
    }
}
?>