<?php

namespace Admin\Controller;

/**
 * 支付宝小程序管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppMiniAlipayListController extends CommonController
{
	private $application_name;
	private $old_path;
	private $new_path;

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

		// 当前小程序包名称
		$this->application_name = 'alipay';

		// 原包地址/操作地址
		$this->old_path = ROOT_PATH.'AppMini'.DS.'Old'.DS.$this->application_name;
		$this->new_path = ROOT_PATH.'AppMini'.DS.'New'.DS.$this->application_name;
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
		$this->assign('data', $this->GetDataList());
		$this->display('Index');
	}

	/**
	 * [GetDataList 获取小程序生成列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-05-10T10:24:40+0800
	 */
	private function GetDataList()
	{
		$result = array();
		if(is_dir($this->new_path))
		{
			if($dh = opendir($this->new_path))
			{
				while(($temp_file = readdir($dh)) !== false)
				{
					if($temp_file != '.' && $temp_file != '..')
					{
						$file_path = $this->new_path.DS.$temp_file;
						$url = __MY_URL__.'AppMini'.DS.'New'.DS.$this->application_name.DS.$temp_file;
						$result[] = [
							'name'	=> $temp_file,
							'url'	=> $url,
							'size'	=> FileSizeByteToUnit(filesize($file_path)),
							'time'	=> date('Y-m-d H:i:s', filectime($file_path)),
						];
					}
				}
				closedir($dh);
			}
		}
		return $result;
	}

	/**
	 * [Created 生成]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-05T20:12:30+0800
	 */
	public function Created()
	{
		// 配置内容
		$app_mini_alipay_title = MyC('common_app_mini_alipay_title');
		$app_mini_alipay_describe = MyC('common_app_mini_alipay_describe');
		if(empty($app_mini_alipay_title) || empty($app_mini_alipay_describe))
		{
			$this->ajaxReturn(L('appmini_alipaylist_config_error'), -1);
		}

		// 目录不存在则创建
		\Library\FileUtil::CreateDir($this->new_path);

		// 复制包目录
		$new_dir = $this->new_path.DS.date('YmdHis');
		if(\Library\FileUtil::CopyDir($this->old_path, $new_dir) != true)
		{
			$this->ajaxReturn(L('appmini_alipaylist_created_copy_error'), -2);
		}

		// 校验基础文件是否存在
		if(!file_exists($new_dir.DS.'app.js') || !file_exists($new_dir.DS.'app.json'))
		{
			$this->ajaxReturn(L('appmini_alipaylist_file_error'), -3);
		}

		// 替换内容
		// app.js
		file_put_contents($new_dir.DS.'app.js', str_replace(['{{request_url}}', '{{application_title}}', '{{application_describe}}'], [__MY_URL__, $app_mini_alipay_title, $app_mini_alipay_describe], file_get_contents($new_dir.DS.'app.js')));
		if($status === false)
		{
			$this->ajaxReturn(L('appmini_alipaylist_file_replace_error'), -4);
		}

		// app.json
		$status = file_put_contents($new_dir.DS.'app.json', str_replace(['{{application_title}}'], [$app_mini_alipay_title], file_get_contents($new_dir.DS.'app.json')));
		if($status === false)
		{
			$this->ajaxReturn(L('appmini_alipaylist_file_replace_error'), -4);
		}

		// 生成压缩包
		$zip = new \Library\ZipFolder();
		if(!$zip->zip($new_dir.'.zip', $new_dir))
		{
			$this->ajaxReturn(L('appmini_alipaylist_zip_error'), -100);
		}

		// 生成成功删除目录
		\Library\FileUtil::UnlinkDir($new_dir);

		$this->ajaxReturn(L('common_operation_created_success'), 0);
	}

	/**
	 * [Delete 删除包]
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

		// 删除压缩包
		if(\Library\FileUtil::UnlinkFile($this->new_path.DS.I('id')))
		{
			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			$this->ajaxReturn(L('common_operation_delete_error'), -100);
		}
	}
}
?>