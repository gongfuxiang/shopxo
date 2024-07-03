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

/**
 * Excel驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-10T21:51:08+0800
 */
class Excel
{
	private $filename;
	private $file_type;
	private $suffix;
	private $data;
	private $title;
	private $jump_url;
	private $msg;
	private $horizontal_center;
	private $vertical_center;
	private $warap_text;

	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:09:17+0800
	 * @param    [string]       $params['filename']				[文件名称（追加当前时间）]
	 * @param    [string]       $params['suffix']				[文件后缀名（默认xls）]
	 * @param    [string]       $params['jump_url']				[出错跳转url地址（默认上一个页面）]
	 * @param    [string]       $params['msg']					[错误提示信息]
	 * @param    [string]       $params['file_type']			[导出文件类型（默认excel）]
	 * @param    [array]        $params['title']				[标题（二维数组）]
	 * @param    [array]        $params['data']					[数据（二维数组）]
	 * @param    [int]          $params['horizontal_center']	[是否水平居中 1是]
	 * @param    [int]          $params['vertical_center']		[是否垂直居中 1是]
	 * @param    [int]          $params['warap_text']			[是否内容自动换行 1是]
	 */
	public function __construct($params = [])
	{
		// 文件名称
		$date = date('YmdHis');
		$this->filename = isset($params['filename']) ? $params['filename'].'-'.$date : $date;

		// 文件类型, 默认excel
		$type_all = array('excel' => 'vnd.ms-excel', 'pdf' => 'pdf');
		$this->file_type = (isset($params['file_type']) && isset($type_all[$params['file_type']])) ? $type_all[$params['file_type']] : $type_all['excel'];

		// 文件后缀名称
		$this->suffix = empty($params['suffix']) ? 'xls' : $params['suffix'];

		// 标题
		$this->title = isset($params['title']) ? $params['title'] : [];

		// 数据
		$this->data = isset($params['data']) ? $params['data'] : [];

		// 出错跳转地址
		$this->jump_url = empty($params['jump_url']) ? (empty($_SERVER['HTTP_REFERER']) ? __MY_URL__ : htmlspecialchars($_SERVER['HTTP_REFERER'])) : $params['jump_url'];

		// 错误提示信息
		$this->msg = empty($params['msg']) ? 'title or data cannot be empty!' : $params['msg'];

		// 水平,垂直居中
		$this->horizontal_center = isset($params['horizontal_center']) ? intval($params['horizontal_center']) : 1;
		$this->vertical_center = isset($params['vertical_center']) ? intval($params['vertical_center']) : 1;

		// 内容自动换行
		$this->warap_text = isset($params['warap_text']) ? intval($params['warap_text']) : 1;
	}

	/**
	 * 文件导出
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:12:01+0800
	 */
	public function Export()
	{
		// 错误校验
		$this->IsErrorCheck();

		// 导出类型
		$export_type = MyC('common_excel_export_type', 0, true);
		if($export_type == 0)
		{
			$this->ExportCsv();
		} else {
			$this->ExportExcel();
		}
	}

	/**
	 * csv导出文件
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2022-06-22
	 * @desc    description
	 */
	public function ExportCsv()
	{
		// 获取配置编码类型
		$excel_charset = MyC('admin_excel_charset', 0);
		$charset = MyConst('common_excel_charset_list')[$excel_charset]['value'];

		// 拼接文件信息，这里注意两点
		// 1、字段与字段之间用逗号分隔开
		// 2、行与行之间需要换行符
		// 3、英文逗号替换未中文逗号、避免与csv分隔符冲突
	    $csv_title = implode(',', array_map(function($v) {
	    	return str_replace([',', "\n"], ['，', ''], $v['name']);
	    }, $this->title));
	    $csv_content = (($excel_charset == 0) ? $csv_title : mb_convert_encoding($csv_title, $charset, 'utf-8'))."\n";
		foreach($this->data as $v)
		{
			$temp = '';
			$index = 0;
			foreach($this->title as $tk=>$tv)
			{
				$temp .= ($index == 0 ? '' : ',').((array_key_exists($tk, $v) && !is_array($v[$tk]) && !empty($v[$tk])) ? str_replace([',', "\n"], [ '，', ''], $v[$tk]) : '')."\t";
				$index++;
			}
			$csv_content .= (($excel_charset == 0) ? $temp : mb_convert_encoding($temp, $charset, 'utf-8'))."\n";
		}

	    // 头信息设置
	    header("Content-type:text/csv");
	    header("Content-Disposition:attachment;filename=" . $this->filename.'.csv');
	    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
	    header('Expires:0');
	    header('Pragma:public');
	    echo $csv_content;
	    exit;
	}

	/**
	 * 根据字段个数，设置表头排序字母
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2022-01-10
	 * @desc    description
	 */
	public function GetLetterData()
	{
		$letter_str = '';
		if(!empty($this->title) && is_array($this->title))
		{
			$count = count($this->title);
			for($i='A',$k=0; $i<='Z'; $i++, $k++)
			{
			    if($k == $count)
			    {
			        break;
			    }

			    // 最后一个取消逗号
			    if($k == ($count-1))
			    {
			        $letter_str .= $i;
			    } else {
			        $letter_str .= $i.',';
			    }
			}
		}
		return explode(',', $letter_str);
	}

	/**
	 * excel文件导出
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:12:01+0800
	 */
	public function ExportExcel()
	{
		// 获取配置编码类型
		$excel_charset = MyC('admin_excel_charset', 0);
		$charset = MyConst('common_excel_charset_list')[$excel_charset]['value'];

		// 获取字母
		$letter_data = $this->GetLetterData();

		// excel对象
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// 标题
		$temp_cum = 0;
		$temp_row = 1;
		foreach($this->title as $k=>$v)
		{
			if(array_key_exists($temp_cum, $letter_data))
			{
				$temp_letter = $letter_data[$temp_cum].$temp_row;
				$value = ($excel_charset == 0) ? $v['name'] : mb_convert_encoding($v['name'], $charset, 'utf-8');
	    		$sheet->setCellValue($temp_letter, $value);
	    		$sheet->getStyle($temp_letter)->getFont()->setBold(true);
	    		$temp_cum++;
			}
		}

		// 内容
		$temp_row = 2;
		foreach($this->data as $k=>$v)
		{
			if(is_array($v) && !empty($v))
			{
				$temp_cum = 0;
				foreach($this->title as $tk=>$tv)
				{
					if(array_key_exists($temp_cum, $letter_data))
					{
						$temp_letter = $letter_data[$temp_cum];
						$height = isset($tv['height']) ? intval($tv['height']) : 0;
						$width = isset($tv['width']) ? intval($tv['width']) : $height;
						if($tv['type'] == 'images' && !empty($v[$tk]))
						{
							$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				            $drawing->setPath($v[$tk]);

				            // 设置宽度高度
				            $number = empty($height) ? 50 : $height-10;
				            $drawing->setHeight($number);
				            $drawing->setWidth($number);
				            $drawing->setCoordinates($temp_letter.$temp_row);

				            // 图片偏移距离
				            $x = ($width > 0) ? (($width-$number)/2)+15 : 15;
				            $drawing->setOffsetX($x);
				            $drawing->setOffsetY(15);
				            $drawing->setWorksheet($spreadsheet->getActiveSheet());
						} else {
							$value = (array_key_exists($tk, $v) && !is_array($v[$tk])) ? (($excel_charset == 0) ? $v[$tk] : mb_convert_encoding($v[$tk], $charset, 'utf-8')) : '';
							$sheet->setCellValueByColumnAndRow($temp_cum+1, $temp_row, $value);
						}

						// 单元格宽高
						if($width > 0)
						{
							$spreadsheet->getActiveSheet()->getColumnDimension($temp_letter)->setWidth($width/5);
						}

						// 水平,垂直居中
						if($this->horizontal_center == 1)
						{
							$spreadsheet->getActiveSheet()->getStyle($temp_letter)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						}
						if($this->vertical_center == 1)
						{
							$spreadsheet->getActiveSheet()->getStyle($temp_letter)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						}

						// 自动换行
						if($this->warap_text == 1)
						{
							$sheet->getStyle($temp_letter)->getAlignment()->setWrapText(true);
						}
						$temp_cum++;
					}

					// 行高度
					if($height > 0)
					{
						$spreadsheet->getActiveSheet()->getRowDimension($temp_row)->setRowHeight($height);
					}
				}
				$temp_row++;
			}
		}

		// 丢弃输出缓冲区中的内容
        if(ob_get_length() > 0)
        {
            ob_clean();
        }

        // 头部
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/'.$this->file_type.';charset='.$charset);
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header('Content-Disposition:attachment;filename='.$this->filename.'.'.$this->suffix);
		header('Content-Transfer-Encoding:binary');
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, ucfirst($this->suffix));
		$writer->save('php://output');
	}

	/**
	 * excel文件导入
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-06T18:18:55+0800
	 * @param    [string]    $file [文件位置,空则读取全局excel的临时文件]
	 * @return   [array]           [数据列表]
	 */
	public function Import($file = '')
	{
		// 文件为空则取全局文变量excel的临时文件
		if(empty($file) && (empty($_FILES['file']) || empty($_FILES['file']['tmp_name'])))
		{
			return DataReturn(MyLang('common_extend.base.excel.file_empty_tips'), -1);
		}
		$file = empty($file) ? $_FILES['file']['tmp_name'] : $file;

		// 取得文件基础数据及类型判断
		$extension = empty($_FILES['file']['name']) ? 'xlsx' : substr($_FILES['file']['name'], strripos($_FILES['file']['name'], '.')+1);
		if(!in_array($extension, ['csv', 'xls', 'xlsx']))
		{
			return DataReturn(MyLang('common_extend.base.excel.excel_format_error_tips'), -1);
		}
		if('csv' == $extension)
		{     
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else if('xls' == $extension)
		{     
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		} else { 
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}
		$spreadsheet = $reader->load($file);

		// 定义变量
		$data = [];
		$title = [];
		$sheet = $spreadsheet->getActiveSheet();
		foreach($sheet->getRowIterator(1) as $rk=>$row)
		{
			$tmp = [];
			foreach($row->getCellIterator() as $cell)
			{
				$value = $cell->getFormattedValue();
				if($rk == 1)
				{
					$title[] = $value;
				} else {
					$tmp[] = $value;
				}
			}
			// 避免正行为空
			if(count(array_filter($tmp)) > 0)
			{
				$data[] = $tmp;
			}
		}
		$result = [
			'title'	=> $title,
			'data'	=> $data,
		];
		return DataReturn(MyLang('handle_success'), 0, $result);
	}

	/**
	 * 错误校验
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2022-09-20
	 * @desc    description
	 */
	public function IsErrorCheck()
	{
		// 是否有数据
		if(empty($this->title) && empty($this->data))
		{
			die('<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8" />
                        <title>'.MyLang('common_extend.base.excel.error_title').'</title>
                    </head>
                    <body style="text-align:center;">
                        <p style="color:#666;font-size:14px;margin-top:10%;margin-bottom:30px;">'.$this->msg.'</p>
                        <a href="javascript:;" onClick="WindowClose()" style="text-decoration:none;color:#fff;background:#f00;padding:5px 15px;border-radius:2px;font-size:12px;">'.MyLang('common_extend.base.excel.close_page_title').'</a>
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
	}
}
?>