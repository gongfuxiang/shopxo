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
	private $string;
	private $jump_url;
	private $msg;

	/**
	 * [__construct 构造方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:09:17+0800
	 * @param    [string]       $param['filename']	[文件名称（追加当前时间）]
	 * @param    [string]       $param['suffix']	[文件后缀名（默认xls）]
	 * @param    [string]       $param['jump_url']	[出错跳转url地址（默认上一个页面）]
	 * @param    [string]       $param['msg']		[错误提示信息]
	 * @param    [string]       $param['file_type']	[导出文件类型（默认excel）]
	 * @param    [array]        $param['title']		[标题（二维数组）]
	 * @param    [array]        $param['data']		[数据（二维数组）]
	 */
	public function __construct($param = array())
	{
		// 文件名称
		$date = date('YmdHis');
		$this->filename = isset($param['filename']) ? $param['filename'].'-'.$date : $date;

		// 文件类型, 默认excel
		$type_all = array('excel' => 'vnd.ms-excel', 'pdf' => 'pdf');
		$this->file_type = (isset($param['file_type']) && isset($type_all[$param['file_type']])) ? $type_all[$param['file_type']] : $type_all['excel'];

		// 文件后缀名称
		$this->suffix = empty($param['suffix']) ? 'xls' : $param['suffix'];

		// 标题
		$this->title = isset($param['title']) ? $param['title'] : array();

		// 数据
		$this->data = isset($param['data']) ? $param['data'] : array();

		// 出错跳转地址
		$this->jump_url = empty($param['jump_url']) ? $_SERVER['HTTP_REFERER'] : $param['jump_url'];

		// 错误提示信息
		$this->msg = empty($param['msg']) ? 'title or data cannot be empty!' : $param['msg'];

		// 引入PHPExcel类库
		require ROOT.'extend'.DS.'phpexcel'.DS.'PHPExcel.php';
	}

	/**
	 * [Export excel文件导出]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:12:01+0800
	 */
	public function Export()
	{
		// 是否有数据
		if(empty($this->title) && empty($this->data))
		{
			echo '<script>alert("'.$this->msg.'");</script>';
			echo '<script>window.location.href="'.$this->jump_url.'"</script>';
			die;
		}

		// excel对象
		$excel = new \PHPExcel();

		// 操作第一个工作表
		$excel->setActiveSheetIndex(0);

		// 文件输出类型
		switch($this->file_type)
		{
			// PDF
			case 'pdf':
				$writer = PHPExcel_IOFactory::createWriter($excel, 'PDF');
				$writer->setSheetIndex(0);
				break;
			
			// 默认EXCEL
			default:
				$writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		}

		// 获取配置编码类型
		$excel_charset = MyC('admin_excel_charset', 0);
		$charset = lang('common_excel_charset_list')[$excel_charset]['value'];

		// 标题
		$temp_key = 0;
		foreach($this->title as $k=>$v)
		{
			$col = \PHPExcel_Cell::stringFromColumnIndex($temp_key);
			$excel->getActiveSheet()->setCellValue($col.'1', ($excel_charset == 0) ? $v['name'] : iconv('utf-8', $charset, $v['name']));
			$temp_key++;
		}
		
		// 内容
		foreach($this->data as $k=>$v)
		{
			$i = $k+2;
			if(is_array($v) && !empty($v))
			{
				$temp_key = 0;
				foreach($this->title as $tk=>$tv)
				{
					$col = \PHPExcel_Cell::stringFromColumnIndex($temp_key);
					$excel->getActiveSheet()->setCellValueExplicit($col.$i, ($excel_charset == 0) ? $v[$tk] : iconv('utf-8', $charset, $v[$tk]), \PHPExcel_Cell_DataType::TYPE_STRING);
					$temp_key++;
				}
			}
		}

		// 头部
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type: application/'.$this->file_type.';;charset='.$charset);
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header('Content-Disposition:attachment;filename='.$this->filename.'.'.$this->suffix);
		header('Content-Transfer-Encoding:binary');
		$writer->save('php://output');
	}

	/**
	 * [Import excel文件导入]
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
		if(empty($file) && !empty($_FILES['excel']['tmp_name']))
		{
			$file = $_FILES['excel']['tmp_name'];
		}

		// 文件地址是否有误,title数据是否有数据
		if(empty($file) || empty($this->title))
		{
			echo '<script>alert("'.$this->msg.'");</script>';
			echo '<script>window.location.href="'.$this->jump_url.'"</script>';
			die;
		}

		// 取得文件基础数据
		$reader = \PHPExcel_IOFactory::createReader('Excel5');
		$excel = $reader->load($file);

		// 取得总行数
		$worksheet = $excel->getActiveSheet();

		// 取得总列数
		$highest_row = $worksheet->getHighestRow();

		// 取得最高的列
		$highest_column = $worksheet->getHighestColumn();

		// 总列数
		$highest_column_index = \PHPExcel_Cell::columnIndexFromString($highest_column);

		// 定义变量
		$result = array();
		$field = array();

		// 读取数据
		for($row=1; $row<=$highest_row; $row++)
		{
			// 临时数据
			$info = array();

			// 注意 highest_column_index 的列数索引从0开始
			for($col = 0; $col < $highest_column_index; $col++)
			{
				$value = $worksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
				if($row == 1)
				{
					foreach($this->title as $tk=>$tv)
					{
						if($value == $tv['name'])
						{
							$tv['field'] = $tk;
							$field[$col] = $tv;
						}
					}
				} else {
					if(!empty($field))
					{
						$info[$field[$col]['field']] = ($field[$col]['type'] == 'int') ? trim(ScienceNumToString($value)) : trim($value);
					}
				}
			}
			if($row > 1)
			{
				$result[] = $info;
			}
		}
		return $result;
	}
}
?>