<?php
namespace app\admin\controller;

/**
 * 主题管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Theme extends Common
{
	private $html_path;
	private $static_path;

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

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();

		// 静态目录和html目录
		$this->html_path = 'application'.DS.'index'.DS.'view'.DS;
		$this->static_path = 'public'.DS.'static'.DS.'index'.DS;

		// 小导航
		$this->view_type = input('view_type', 'home');
	}

	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 模板列表
		$this->assign('data_list', $this->GetThemeList());

		// 默认主题
		$theme = cache('cache_common_default_theme_data');
		$this->assign('theme', empty($theme) ? 'Default' : $theme);

		// 导航参数
		$this->assign('view_type', $this->view_type);

		return $this->fetch();
	}

	/**
     * 模板安装
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function UploadInfo()
	{
		// 导航参数
		$this->assign('view_type', $this->view_type);

		return $this->fetch();
	}

	/**
	 * [GetThemeList 获取模板列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-05-10T10:24:40+0800
	 * @return   [array] [模板列表]
	 */
	private function GetThemeList()
	{
		$result = [];
		$dir = ROOT.$this->html_path;
		if(is_dir($dir))
		{
			if($dh = opendir($dir))
			{
				$default_preview = __MY_URL__.'static'.DS.'common'.DS.'images'.DS.'default-preview.jpg';
				while(($temp_file = readdir($dh)) !== false)
				{
					$config = $dir.$temp_file.DS.'config.json';
					if(!file_exists($config))
					{
						continue;
					}

					// 读取配置文件
					$data = json_decode(file_get_contents($config), true);
					if(!empty($data) && is_array($data))
					{
						if(empty($data['name']) || empty($data['ver']) || empty($data['author']))
						{
							continue;
						}
						$preview = ROOT.$this->static_path.$temp_file.DS.'images'.DS.'preview.jpg';
						$result[] = array(
							'theme'		=>	$temp_file,
							'name'		=>	htmlentities($data['name']),
							'ver'		=>	str_replace(array('，',','), ', ', htmlentities($data['ver'])),
							'author'	=>	htmlentities($data['author']),
							'home'		=>	isset($data['home']) ? $data['home'] : '',
							'preview'	=>	file_exists($preview) ? __MY_URL__.'static'.DS.'index'.DS.$temp_file.DS.'images'.DS.'preview.jpg' : $default_preview,
						);
					}
				}
				closedir($dh);
			}
		}
		return $result;
	}

	/**
	 * [Save 数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-05T20:12:30+0800
	 */
	public function Save()
	{
		// 配置更新
		$this->MyConfigSave();
	}

	/**
	 * [Delete 删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-09T21:13:47+0800
	 */
	public function Delete()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 主题
		$id = str_replace(array('.', '/', '\\'), '', strip_tags(I('id')));
		if(empty($id))
		{
			$this->error('主题名称有误');
		}

		// 默认主题
		$theme = cache('cache_common_default_theme_data');
		$theme = empty($theme) ? 'Default' : $theme;

		// 不能删除正在使用的主题
		if($theme == $id)
		{
			$this->ajaxReturn('不能删除正在使用的主题', -2);
		}

		// 开始删除主题
		if(\base\FileUtil::UnlinkDir($this->html_path.$id) && \base\FileUtil::UnlinkDir($this->static_path.$id))
		{
			$this->ajaxReturn('删除成功');
		} else {
			$this->ajaxReturn('删除失败或资源不存在', -100);
		}
	}

	/**
	 * [Upload 模板上传安装]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-05-10T16:27:09+0800
	 */
	public function Upload()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 文件上传校验
		$error = FileUploadError('theme');
		if($error !== true)
		{
			$this->ajaxReturn($error, -1);
		}

		// 文件格式化校验
		$type = array('application/zip', 'application/octet-stream');
		if(!in_array($_FILES['theme']['type'], $type))
		{
			$this->ajaxReturn('文件格式有误，请上传zip压缩包', -2);
		}

		// 开始解压文件
		$resource = zip_open($_FILES['theme']['tmp_name']);
		while(($temp_resource = zip_read($resource)) !== false)
		{
			if(zip_entry_open($resource, $temp_resource))
			{
				// 当前压缩包中项目名称
				$file = zip_entry_name($temp_resource);

				// 排除临时文件和临时目录
				if(strpos($file, '/.') === false && strpos($file, '__') === false)
				{
					// 拼接路径
					if(strpos($file, '_html') !== false)
					{
						$file = $this->html_path.$file;
					} else if(strpos($file, '_static') !== false)
					{
						$file = $this->static_path.$file;
					} else {
						continue;
					}
					$file = str_replace(array('_static/', '_html/'), '', $file);

					// 截取文件路径
					$file_path = substr($file, 0, strrpos($file, '/'));

					// 路径不存在则创建
					if(!is_dir($file_path))
					{
						mkdir($file_path, 0777, true);
					}

					// 如果不是目录则写入文件
					if(!is_dir($file))
					{
						// 读取这个文件
						$file_size = zip_entry_filesize($temp_resource);
						$file_content = zip_entry_read($temp_resource, $file_size);
						file_put_contents($file, $file_content);
					}
					// 关闭目录项  
					zip_entry_close($temp_resource);
				}
			}
		}
		$this->ajaxReturn('操作成功');
	}
}
?>