<?php
/* 
 * Edition:	ET080708
 * Desc:	ET Template
 * File:	template.ease.php
 * Author:	David Meng
 * Site:	http://www.systn.com
 * Email:	mdchinese@gmail.com
 * 
 */

//引入核心文件
if (is_file(dirname(__FILE__).'/template.core.php')){
	include dirname(__FILE__).'/template.core.php';
}else {
	die('Sorry. Not load core file.');
}

Class template extends ETCore{
	
	/**
	*	声明模板用法
	*/
	function template(
		$set = array(
				'ID'		 =>'1',					//缓存ID
				'TplType'	 =>'htm',				//模板格式
				'CacheDir'	 =>'cache',				//缓存目录
				'TemplateDir'=>'template' ,			//模板存放目录
				'AutoImage'	 =>'on' ,				//自动解析图片目录开关 on表示开放 off表示关闭
				'LangDir'	 =>'language' ,			//语言文件存放的目录
				'Language'	 =>'default' ,			//语言的默认文件
				'Copyright'	 =>'off' ,				//版权保护
				'MemCache'	 =>'' ,					//Memcache服务器地址例如:127.0.0.1:11211
			)
		){
		
		parent::ETCoreStart($set);
	}

}
?>