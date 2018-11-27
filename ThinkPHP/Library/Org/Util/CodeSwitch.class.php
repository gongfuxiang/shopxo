<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Org\Util;
class CodeSwitch {
    // 错误信息
    static private $error = array();
    // 提示信息
    static private $info = array();
    // 记录错误
    static private function error($msg) {
        self::$error[]   =  $msg;
    }
    // 记录信息
    static private function info($info) {
        self::$info[]     = $info;
    }
	/**
     * 编码转换函数,对整个文件进行编码转换
	 * 支持以下转换
	 * GB2312、UTF-8 WITH BOM转换为UTF-8
	 * UTF-8、UTF-8 WITH BOM转换为GB2312
     * @access public
     * @param string $filename		文件名
	 * @param string $out_charset	转换后的文件编码,与iconv使用的参数一致
     * @return void
     */
	static function DetectAndSwitch($filename,$out_charset) {
		$fpr = fopen($filename,"r");
		$char1 = fread($fpr,1);
		$char2 = fread($fpr,1);
		$char3 = fread($fpr,1);

		$originEncoding = "";

		if($char1==chr(239) && $char2==chr(187) && $char3==chr(191))//UTF-8 WITH BOM
			$originEncoding = "UTF-8 WITH BOM";
		elseif($char1==chr(255) && $char2==chr(254))//UNICODE LE
		{
			self::error("不支持从UNICODE LE转换到UTF-8或GB编码");
			fclose($fpr);
			return;
		}elseif($char1==chr(254) && $char2==chr(255)){//UNICODE BE
			self::error("不支持从UNICODE BE转换到UTF-8或GB编码");
			fclose($fpr);
			return;
		}else{//没有文件头,可能是GB或UTF-8
			if(rewind($fpr)===false){//回到文件开始部分,准备逐字节读取判断编码
				self::error($filename."文件指针后移失败");
				fclose($fpr);
				return;
			}

			while(!feof($fpr)){
				$char = fread($fpr,1);
				//对于英文,GB和UTF-8都是单字节的ASCII码小于128的值
				if(ord($char)<128)
					continue;

				//对于汉字GB编码第一个字节是110*****第二个字节是10******(有特例,比如联字)
				//UTF-8编码第一个字节是1110****第二个字节是10******第三个字节是10******
				//按位与出来结果要跟上面非星号相同,所以应该先判断UTF-8
				//因为使用GB的掩码按位与,UTF-8的111得出来的也是110,所以要先判断UTF-8
				if((ord($char)&224)==224) {
					//第一个字节判断通过
					$char = fread($fpr,1);
					if((ord($char)&128)==128) {
						//第二个字节判断通过
						$char = fread($fpr,1);
						if((ord($char)&128)==128) {
							$originEncoding = "UTF-8";
							break;
						}
					}
				}
				if((ord($char)&192)==192) {
					//第一个字节判断通过
					$char = fread($fpr,1);
					if((ord($char)&128)==128) {
						//第二个字节判断通过
						$originEncoding = "GB2312";
						break;
					}
				}
			}
		}

		if(strtoupper($out_charset)==$originEncoding) {
			self::info("文件".$filename."转码检查完成,原始文件编码".$originEncoding);
			fclose($fpr);
		}else {
			//文件需要转码
			$originContent = "";

			if($originEncoding == "UTF-8 WITH BOM") {
				//跳过三个字节,把后面的内容复制一遍得到utf-8的内容
				fseek($fpr,3);
				$originContent = fread($fpr,filesize($filename)-3);
				fclose($fpr);
			}elseif(rewind($fpr)!=false){//不管是UTF-8还是GB2312,回到文件开始部分,读取内容
				$originContent = fread($fpr,filesize($filename));
				fclose($fpr);
			}else{
				self::error("文件编码不正确或指针后移失败");
				fclose($fpr);
				return;
			}

			//转码并保存文件
			$content = iconv(str_replace(" WITH BOM","",$originEncoding),strtoupper($out_charset),$originContent);
			$fpw = fopen($filename,"w");
			fwrite($fpw,$content);
			fclose($fpw);

			if($originEncoding!="")
				self::info("对文件".$filename."转码完成,原始文件编码".$originEncoding.",转换后文件编码".strtoupper($out_charset));
			elseif($originEncoding=="")
				self::info("文件".$filename."中没有出现中文,但是可以断定不是带BOM的UTF-8编码,没有进行编码转换,不影响使用");
		}
	}

	/**
     * 目录遍历函数
     * @access public
     * @param string $path		要遍历的目录名
     * @param string $mode		遍历模式,一般取FILES,这样只返回带路径的文件名
     * @param array $file_types		文件后缀过滤数组
	 * @param int $maxdepth		遍历深度,-1表示遍历到最底层
     * @return void
     */
	static function searchdir($path,$mode = "FULL",$file_types = array(".html",".php"),$maxdepth = -1,$d = 0) {
	   if(substr($path,strlen($path)-1) != '/')
		   $path .= '/';
	   $dirlist = array();
	   if($mode != "FILES")
			$dirlist[] = $path;
	   if($handle = @opendir($path)) {
		   while(false !== ($file = readdir($handle)))
		   {
			   if($file != '.' && $file != '..')
			   {
				   $file = $path.$file ;
				   if(!is_dir($file))
				   {
						if($mode != "DIRS")
						{
							$extension = "";
							$extpos = strrpos($file, '.');
							if($extpos!==false)
								$extension = substr($file,$extpos,strlen($file)-$extpos);
							$extension=strtolower($extension);
							if(in_array($extension, $file_types))
								$dirlist[] = $file;
						}
				   }
				   elseif($d >= 0 && ($d < $maxdepth || $maxdepth < 0))
				   {
					   $result = self::searchdir($file.'/',$mode,$file_types,$maxdepth,$d + 1) ;
					   $dirlist = array_merge($dirlist,$result);
				   }
			   }
		   }
		   closedir ( $handle ) ;
	   }
	   if($d == 0)
		   natcasesort($dirlist);

	   return($dirlist) ;
	}

	/**
     * 对整个项目目录中的PHP和HTML文件行进编码转换
     * @access public
     * @param string $app		要遍历的项目路径
     * @param string $mode		遍历模式,一般取FILES,这样只返回带路径的文件名
     * @param array $file_types		文件后缀过滤数组
     * @return void
     */
	static function CodingSwitch($app = "./",$charset='UTF-8',$mode = "FILES",$file_types = array(".html",".php")) {
		self::info("注意: 程序使用的文件编码检测算法可能对某些特殊字符不适用");
		$filearr = self::searchdir($app,$mode,$file_types);
		foreach($filearr as $file)
			self::DetectAndSwitch($file,$charset);
	}

    static public function getError() {
        return self::$error;
    }

    static public function getInfo() {
        return self::$info;
    }
}