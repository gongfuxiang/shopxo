<?php

namespace Admin\Controller;

/**
 * 主题管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ThemeController extends CommonController
{
	private $html_path;
	private $static_path;

	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();

		// 静态目录和html目录
		$this->html_path = 'Application'.DS.'Home'.DS.'View'.DS;
		$this->static_path = 'Public'.DS.'Home'.DS;

		// 小导航
		$this->view_type = I('view_type', 'home');
	}

	/**
     * [Index 列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 模板
		switch($this->view_type)
		{
			// 模板安装
			case 'upload':
				$this->display('Upload');
				break;

			// 当前模板
			default:
				// 模板列表
				$this->assign('data', $this->GetThemeList());

				// 默认主题
				$theme = S('cache_common_default_theme_data');
				$this->assign('theme', empty($theme) ? 'Default' : $theme);

				$this->display('Index');
		}

		// 导航参数
		$this->assign('view_type', $this->view_type);
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
		$result = array();
		$dir = 'Application'.DS.'Home'.DS.'View'.DS;
		if(is_dir($dir))
		{
			if($dh = opendir($dir))
			{
				$default_preview = 'Public'.DS.'Common'.DS.'Images'.DS.'default-preview.jpg';
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
						$result[] = array(
							'theme'		=>	$temp_file,
							'name'		=>	I('data.name', '', '',$data),
							'ver'		=>	str_replace(array('，',','), ', ', I('data.ver', '', '',$data)),
							'author'	=>	I('data.author', '', '',$data),
							'home'		=>	I('data.home', '', '',$data),
							'preview'	=>	file_exists($dir.$temp_file.DS.'preview.jpg') ? $dir.$temp_file.DS.'preview.jpg' : $default_preview,
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
			$this->error(L('common_unauthorized_access'));
		}

		// 主题
		$id = str_replace(array('.', '/', '\\'), '', strip_tags(I('id')));
		if(empty($id))
		{
			$this->error(L('theme_empty_error'));
		}

		// 默认主题
		$theme = S('cache_common_default_theme_data');
		$theme = empty($theme) ? 'Default' : $theme;

		// 不能删除正在使用的主题
		if($theme == $id)
		{
			$this->ajaxReturn(L('theme_delete_error'), -2);
		}

		// 开始删除主题
		if(\Library\FileUtil::UnlinkDir($this->html_path.$id) && \Library\FileUtil::UnlinkDir($this->static_path.$id))
		{
			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			$this->ajaxReturn(L('common_operation_delete_error'), -100);
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
			$this->error(L('common_unauthorized_access'));
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
			$this->ajaxReturn(L('theme_upload_error'), -2);
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
					if(strpos($file, '_Html') !== false)
					{
						$file = $this->html_path.$file;
					} else if(strpos($file, '_Static') !== false)
					{
						$file = $this->static_path.$file;
					} else {
						continue;
					}
					$file = str_replace(array('_Static/', '_Html/'), '', $file);

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
		$this->ajaxReturn(L('common_operation_success'));
	}
}
?>