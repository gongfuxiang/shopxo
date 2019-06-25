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
namespace app\api\controller;

use think\facade\Hook;
use app\service\ResourcesService;

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
		$ret = ResourcesService::AttachmentDelete(input());
		if($ret['code'] == 0)
		{
			$this->current_result = json_encode(array(
				'state'=> 'SUCCESS'
			));
		} else {
			$this->current_result = json_encode(array(
				'state'=> $ret['msg']
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
		$attachment_type = "file";
		switch(htmlspecialchars($this->current_action))
		{
			case 'uploadimage':
				$temp_config = array(
						"pathFormat" => $this->current_config['imagePathFormat'],
						"maxSize" => $this->current_config['imageMaxSize'],
						"allowFiles" => $this->current_config['imageAllowFiles']
					);
				$field_name = $this->current_config['imageFieldName'];
				$attachment_type = "image";
				break;

			case 'uploadscrawl':
				$temp_config = array(
						"pathFormat" => $this->current_config['scrawlPathFormat'],
						"maxSize" => $this->current_config['scrawlMaxSize'],
						"allowFiles" => $this->current_config['scrawlAllowFiles'],
						"oriName" => "scrawl.png"
					);
				$field_name = $this->current_config['scrawlFieldName'];
				$attachment_type = "scrawl";
				break;

			case 'uploadvideo':
				$temp_config = array(
						"pathFormat" => $this->current_config['videoPathFormat'],
						"maxSize" => $this->current_config['videoMaxSize'],
						"allowFiles" => $this->current_config['videoAllowFiles']
					);
				$field_name = $this->current_config['videoFieldName'];
				$attachment_type = "video";
				break;

			case 'uploadfile':
			default:
				$temp_config = array(
						"pathFormat" => $this->current_config['filePathFormat'],
						"maxSize" => $this->current_config['fileMaxSize'],
						"allowFiles" => $this->current_config['fileAllowFiles']
					);
				$field_name = $this->current_config['fileFieldName'];
				$attachment_type = "file";
		}

		/* 生成上传实例对象并完成上传 */
		$up = new \base\Uploader($field_name, $temp_config, $attachment_type);

		/**
		 * 得到上传文件所对应的各个参数,数组结构
		 * array(
		 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
		 *     "url" => "",            //返回的地址
		 *     "path" => "",           //绝对地址
		 *     "title" => "",          //新文件名
		 *     "original" => "",       //原始文件名
		 *     "type" => ""            //文件类型
		 *     "size" => "",           //文件大小
		 *     "hash" => "",   		   //sha256值
		 * )
		 */
		$data = $up->getFileInfo();
		if(isset($data['state']) && $data['state'] == 'SUCCESS')
		{
			$data['type'] = $attachment_type;
			$ret = ResourcesService::AttachmentAdd($data);
			if($ret['code'] == 0)
			{
				$this->current_result = json_encode($ret['data']);
			} else {
				$this->current_result = json_encode(['state'=>$ret['msg']]);
			}
		} else {
			$this->current_result = json_encode($data);
		}
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

		// 参数
		$params = [
			'm'			=> $start,
			'n'			=> $size,
			'where'		=> [
	            'type'      => substr($this->current_action, 4),
	            'path_type' => input('path_type', 'other'),
	        ],
		];

		// 数据初始化
		$data = array(
			'state' 	=> "没有相关数据",
			'list' 		=> [],
			'start' 	=> $start,
			'total' 	=> ResourcesService::AttachmentTotal($params['where']),
		);

		// 获取数据
		$ret = ResourcesService::AttachmentList($params);
		if(!empty($ret['data']))
		{
			$data['state'] = 'SUCCESS';
			$data['list'] = $ret['data'];
		}
		
		$this->current_result = json_encode($data);
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
			/**
			 * 得到上传文件所对应的各个参数,数组结构
			 * array(
			 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
			 *     "url" => "",            //返回的地址
			 *     "path" => "",           //绝对地址
			 *     "title" => "",          //新文件名
			 *     "original" => "",       //原始文件名
			 *     "type" => ""            //文件类型
			 *     "size" => "",           //文件大小
			 *     "hash" => "",   		   //sha256值
			 * )
			 */
			$data = $up->getFileInfo();
			if(isset($data['state']) && $data['state'] == 'SUCCESS')
			{
				$data['type'] = 'remote';
				$ret = ResourcesService::AttachmentAdd($data);
				if($ret['code'] != 0)
				{
					$data['state'] = $ret['msg'];
				}
			}
			array_push($list, array(
				"state" => $data["state"],
				"url" => $data["url"],
				"size" => $data["size"],
				"title" => htmlspecialchars($data["title"]),
				"original" => htmlspecialchars($data["original"]),
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