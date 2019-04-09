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
namespace app\admin\controller;

/**
 * 百度编辑器控制器入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Ueditor extends Common
{
	private $current_action;
	private $current_config;
	private $current_result;

	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();
	}

	/**
     * [Index 附件上传入口]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 配置信息
		$this->current_config = config('ueditor_config');
		$this->current_action = input('action');

		// 非获取配置信息登录校验
		if($this->current_action != 'config')
		{
			// 登录校验
			$this->IsLogin();
		}

		// action
		switch($this->current_action)
		{
			// 配置信息
			case 'config':
				$this->current_result =  json_encode($this->current_config);
				break;

			/* 上传图片 */
			case 'uploadimage':
			/* 上传涂鸦 */
			case 'uploadscrawl':
			/* 上传视频 */
			case 'uploadvideo':
			/* 上传文件 */
			case 'uploadfile':
				$this->ActionUpload();
				break;

			/* 列出图片 */
			case 'listimage':
			/* 列出文件 */
			case 'listfile':
			/* 列出视频 */
			case 'listvideo':
				$this->ActionList();
				break;

			/* 抓取远程文件 */
			case 'catchimage':
				$this->ActionCrawler();
				break;

			/* 删除文件 */
			case 'deletefile':
				$this->DeleteFile();
				break;

			default:
				$this->current_result = json_encode(array(
					'state'=> '请求地址出错'
				));
		}

		// 输出结果
		if(input('callback'))
		{
			if(preg_match("/^[\w_]+$/", input('callback')))
			{
				echo htmlspecialchars(input('callback')) . '(' . $this->current_result . ')';
			} else {
				echo json_encode(array(
					'state'=> 'callback参数不合法'
				));
			}
		} else {
			echo $this->current_result;
		}
		exit();
	}

	/**
	 * 文件删除
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-12-10
	 * @desc    description
	 */
	private function DeleteFile()
	{
		$path = input('path');
		if(!empty($path))
		{
			$path = (__MY_ROOT_PUBLIC__ == '/') ? substr(ROOT_PATH, 0, -1).$path : str_replace(__MY_ROOT_PUBLIC__, ROOT_PATH, $path);
			if(file_exists($path))
			{
				if(is_writable($path))
				{
					if(unlink($path))
					{
						$this->current_result = json_encode(array(
							'state'=> 'SUCCESS'
						));
					} else {
						$this->current_result = json_encode(array(
							'state'=> '删除成功'
						));
					}
				} else {
					$this->current_result = json_encode(array(
						'state'=> '没有删除权限'
					));
				}
			} else {
				$this->current_result = json_encode(array(
					'state'=> '文件不存在'
				));
			}
		} else {
			$this->current_result = json_encode(array(
				'state'=> '删除文件路径不能为空'
			));
		}
	}

	/**
	 * [ActionUpload 上传配置]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-17T22:45:06+0800
	 */
	private function ActionUpload()
	{
		$type = "file";
		switch(htmlspecialchars($this->current_action))
		{
			case 'uploadimage':
				$temp_config = array(
						"pathFormat" => $this->current_config['imagePathFormat'],
						"maxSize" => $this->current_config['imageMaxSize'],
						"allowFiles" => $this->current_config['imageAllowFiles']
					);
				$field_name = $this->current_config['imageFieldName'];
				$type = "image";
				break;

			case 'uploadscrawl':
				$temp_config = array(
						"pathFormat" => $this->current_config['scrawlPathFormat'],
						"maxSize" => $this->current_config['scrawlMaxSize'],
						"allowFiles" => $this->current_config['scrawlAllowFiles'],
						"oriName" => "scrawl.png"
					);
				$field_name = $this->current_config['scrawlFieldName'];
				$type = "base64";
				break;

			case 'uploadvideo':
				$temp_config = array(
						"pathFormat" => $this->current_config['videoPathFormat'],
						"maxSize" => $this->current_config['videoMaxSize'],
						"allowFiles" => $this->current_config['videoAllowFiles']
					);
				$field_name = $this->current_config['videoFieldName'];
				$type = "video";
				break;

			case 'uploadfile':
			default:
				$temp_config = array(
						"pathFormat" => $this->current_config['filePathFormat'],
						"maxSize" => $this->current_config['fileMaxSize'],
						"allowFiles" => $this->current_config['fileAllowFiles']
					);
				$field_name = $this->current_config['fileFieldName'];
				$type = "file";
		}

		/* 生成上传实例对象并完成上传 */
		$up = new \base\Uploader($field_name, $temp_config, $type);

		/**
		 * 得到上传文件所对应的各个参数,数组结构
		 * array(
		 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
		 *     "url" => "",            //返回的地址
		 *     "title" => "",          //新文件名
		 *     "original" => "",       //原始文件名
		 *     "type" => ""            //文件类型
		 *     "size" => "",           //文件大小
		 * )
		 */

		// 返回数据
		$this->current_result = json_encode($up->getFileInfo());
	}

	/**
	 * [ActionList 文件列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-17T22:55:16+0800
	 */
	private function ActionList()
	{
		/* 判断类型 */
		switch($this->current_action)
		{
			/* 列出视频 */
			case 'listvideo':
				$allow_files = $this->current_config['videoManagerAllowFiles'];
				$list_size = $this->current_config['videoManagerListSize'];
				$path = $this->current_config['videoManagerListPath'];
				break;
			/* 列出文件 */
			case 'listfile':
				$allow_files = $this->current_config['fileManagerAllowFiles'];
				$list_size = $this->current_config['fileManagerListSize'];
				$path = $this->current_config['fileManagerListPath'];
				break;

			/* 列出图片 */
			case 'listimage':
			default:
				$allow_files = $this->current_config['imageManagerAllowFiles'];
				$list_size = $this->current_config['imageManagerListSize'];
				$path = $this->current_config['imageManagerListPath'];
		}
		$allow_files = substr(str_replace(".", "|", join("", $allow_files)), 1);

		/* 获取参数 */
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $list_size;
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;

		/* 获取文件列表 */
		$path = GetDocumentRoot() . (substr($path, 0, 1) == "/" ? "":"/") . $path;
		$files = $this->GetFilesList($path, $allow_files);

		// 倒序
		//$files = $this->ArrayQuickSort($files);

		if (!count($files)) {
			$this->current_result =  json_encode(array(
				"state" => "no match file",
				"list" => array(),
				"start" => $start,
				"total" => count($files)
			));
		}

		/* 获取指定范围的列表 */
		$len = count($files);
		$list = [];
		for ($i = min($end, $len) - 1; $i < $len && $i >= 0 && $i >= $start; $i--)
		{
			$list[] = $files[$i];
		}

		/* 返回数据 */
		$this->current_result = json_encode(array(
			"state" => "SUCCESS",
			"list" => $list,
			"start" => $start,
			"total" => count($files)
		));
	}

	/**
	 * 文件快速排序
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-12-25
	 * @desc    description
	 * @param  [array] $data [需要排序的数据（选择一个基准元素，将待排序分成小和打两罐部分，以此类推递归的排序划分两罐部分）]
	 * @return [array]       [排序好的数据]
	 */
	private function ArrayQuickSort($data)
	{
		if(!empty($data) && is_array($data))
		{
			$len = count($data);
			if($len <= 1) return $data;

			$base = $data[0];
			$left_array = array();
			$right_array = array();
			for($i=1; $i<$len; $i++)
			{
				if($base['mtime'] < $data[$i]['mtime'])
				{
					$left_array[] = $data[$i];
				} else {
					$right_array[] = $data[$i];
				}
			}
			if(!empty($left_array)) $left_array = $this->ArrayQuickSort($left_array);
			if(!empty($right_array)) $right_array = $this->ArrayQuickSort($right_array);

			return array_merge($left_array, array($base), $right_array);
		}
	}

	/**
	 * [GetFilesList 遍历获取目录下的指定类型的文件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-17T23:24:59+0800
	 * @param    [string]        $path       	[路径地址]
	 * @param    [string]        $allow_files 	[允许的文件]
	 * @param    [array]         &$files     	[数据]
	 * @return   [array]                     	[数据]
	 */
	private function GetFilesList($path, $allow_files, &$files = array())
	{
		if(!is_dir($path)) return null;
		if(substr($path, strlen($path) - 1) != '/') $path .= '/';
		$handle = opendir($path);
		$document_root = GetDocumentRoot();
		while(false !== ($file = readdir($handle)))
		{
			if($file != '.' && $file != '..')
			{
				$path2 = $path . $file;
				if(is_dir($path2))
				{
					$this->GetFilesList($path2, $allow_files, $files);
				} else {
					if(preg_match("/\.(".$allow_files.")$/i", $file))
					{
						$files[] = array(
								'url'=> substr($path2, strlen($document_root)),
								'mtime'=> filemtime($path2)
							);
					}
				}
			}
		}
		return $files;
	}

	/**
	 * [ActionCrawler 抓取远程文件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-17T23:08:29+0800
	 */
	private function ActionCrawler()
	{
		$temp_config = array(
				"pathFormat" => $this->current_config['catcherPathFormat'],
				"maxSize" => $this->current_config['catcherMaxSize'],
				"allowFiles" => $this->current_config['catcherAllowFiles'],
				"oriName" => "remote.png"
			);
		$field_name = $this->current_config['catcherFieldName'];

		/* 抓取远程图片 */
		$list = array();
		$source = isset($_POST[$field_name]) ? $_POST[$field_name] : $_GET[$field_name];
		foreach($source as $imgUrl)
		{
			$item = new \base\Uploader($imgUrl, $temp_config, "remote");
			$info = $item->getFileInfo();
			array_push($list, array(
				"state" => $info["state"],
				"url" => $info["url"],
				"size" => $info["size"],
				"title" => htmlspecialchars($info["title"]),
				"original" => htmlspecialchars($info["original"]),
				"source" => htmlspecialchars($imgUrl)
			));
		}

		/* 返回抓取数据 */
		$this->current_result = json_encode(array(
				'state'=> count($list) ? 'SUCCESS':'ERROR',
				'list'=> $list
			));
	}
}
?>