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
 * 验证码驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Verify
{
	private $img;
	private $rand_string;
	private $width;
	private $height;
	private $length;
	private $use_point_back;
	private $use_line_back;
	private $use_bg_color_back;
	private $use_text_color_back;
	private $key_verify;
	private $expire_time;

	/**
	 * [__construct 构造方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:42:49+0800
	 * @param    [int]        $param['width'] 				[宽度（默认65）]
	 * @param    [int]        $param['height'] 				[高度（默认30）]
	 * @param    [int]        $param['length'] 				[验证码位数（默认6）]
	 * @param    [boolean]    $param['use_point_back'] 		[是否添加干扰点（默认 true）]
	 * @param    [boolean]    $param['use_line_back'] 		[是否添加干扰线（默认 true）]
	 * @param    [boolean]    $param['use_bg_color_back']	[是否使用彩色背景（默认 true）]
	 * @param    [boolean]    $param['use_text_color_back']	[是否使用彩色文本（默认 true）]
	 * @param    [string]     $param['key_prefix'] 			[验证码种存储前缀key（默认 空）]
	 * @param    [int]        $param['expire_time'] 		[到期时间（默认30）单位（秒）]
	 */
	public function __construct($param = array())
	{
		// 验证码规则
		$rules = MyC('common_images_verify_rules', [], true);

		// 参数处理
		$this->width = isset($param['width']) ? intval($param['width']) : 65;
		$this->height = isset($param['height']) ? intval($param['height']) : 30;
		$this->length = isset($param['length']) ? intval($param['length']) : 4;
		$this->use_point_back = isset($param['use_point_back']) ? $param['use_point_back'] : in_array('point', $rules);
		$this->use_line_back = isset($param['use_line_back']) ? $param['use_line_back'] : in_array('line', $rules);
		$this->use_bg_color_back = isset($param['use_bg_color_back']) ? $param['use_bg_color_back'] : in_array('bgcolor', $rules);
		$this->use_text_color_back = isset($param['use_text_color_back']) ? $param['use_text_color_back'] : in_array('textcolor', $rules);
		$this->key_verify = isset($param['key_prefix']) ? trim($param['key_prefix']).'_verify_code' : '_verify_code';
		$this->expire_time = isset($param['expire_time']) ? intval($param['expire_time']) : 30;
	}

	/**
	 * [Entry 验证码生成]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:19+0800
	 */
	public function Entry()
	{
		// 验证码生成
		$this->rand_string = $this->GetRandString();

		// 创建一个画布（真色彩）
		$this->img = imagecreatetruecolor($this->width,  $this->height);

		// 画背景
		if($this->use_bg_color_back == true)
		{
			$back_color = imagecolorallocate($this->img, rand(200,255), rand(200,255), rand(200,255));
		} else {
			$back_color = imagecolorallocate($this->img, 255, 255, 255); 
		}
        imagefilledrectangle($this->img, 0, 0, $this->width, $this->height, $back_color);

		// 加入干扰，画出多条线
		if($this->use_line_back == true)
		{
			$this->InterferenceLine();
		}
		
		// 加入干扰，画出点
		if($this->use_point_back == true)
		{
			$this->InterferencePoint();
		}

		// 将生成好的字符串写入图像
		$each_width = intval($this->width/$this->length);
		$first = 40/100*$each_width;
		foreach(str_split($this->rand_string) as $k=>$v)
		{
			// 是否使用彩色文本
			if($this->use_text_color_back == true)
			{
				$fgcolor = imagecolorallocate($this->img, rand(0,200), rand(0,255), rand(0,255));
			} else {
				$fgcolor = imagecolorallocate($this->img, 0, 0, 0);
			}

			$temp_height = 95/100*$this->height;
			if($this->height-$temp_height < 15)
			{
				$temp_height = $this->height-15;
			}
			imagestring($this->img, rand(3,5), $k*$each_width+$first, rand(5/100*$this->height,$temp_height), strtoupper($v), $fgcolor);
		}

		// 种session
		$this->KindofSession();

		// 输出图像
		if(ob_get_length() > 0)
        {
            ob_clean();
        }
		header('Content-Type: image/gif');
		imagegif($this->img);

		// 销毁图像
		imagedestroy($this->img);
	}

	/**
	 * [CheckExpire 验证码是否过期]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T19:02:26+0800
	 * @return   [boolean] [有效true, 无效false]
	 */
	public function CheckExpire()
	{
		if(isset($_SESSION[$this->key_verify]))
		{
			$data = $_SESSION[$this->key_verify];
			return (time() <= $data['time']+$this->expire_time);
		}
		return false;
	}

	/**
	 * [CheckCorrect 验证码是否正确]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:00+0800
	 * @param    [string] $verify    [验证码（默认从post读取）]
	 * @return   [booolean]          [正确true, 错误false]
	 */
	public function CheckCorrect($verify = '')
	{
		if(isset($_SESSION[$this->key_verify]['verify']))
		{
			if(empty($verify) && isset($_POST['verify']))
			{
				$verify = trim($_POST['verify']);
			}
			return ($_SESSION[$this->key_verify]['verify'] == strtolower($verify));
		}
		return false;
	}

	/**
	 * [Remove 验证码清除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-08T10:18:20+0800
	 * @return   [other] [无返回值]
	 */
	public function Remove()
	{
		if(isset($_SESSION[$this->key_verify]))
		{
			unset($_SESSION[$this->key_verify]);
		}
	}

	/**
	 * [KindofSession 种验证码session]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T18:52:45+0800
	 */
	private function KindofSession()
	{
		$_SESSION[$this->key_verify] = array(
				'verify' => $this->rand_string,
				'time' => time(),
			);
	}

	/**
	 * [InterferencePoint 加入干扰，画点]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:34+0800
	 */
	private function InterferencePoint()
	{
		for($i=0; $i<200; $i++)
		{
			//产生随机的颜色
			$bgcolor = imagecolorallocate($this->img, rand(0,255), rand(0,255), rand(0,255));
			imagesetpixel($this->img, rand()%$this->width, rand()%$this->height, $bgcolor); 
		}
	}

	/**
	 * [InterferenceLine 加入干扰，画出多条线]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:46+0800
	 */
	private function InterferenceLine()
	{
		for($i=0; $i<5; $i++)
		{
			//产生随机的颜色
			$bgcolor = imagecolorallocate($this->img, rand(0,255), rand(0,255), rand(0,255));
			imageline($this->img, rand(10,90), 0, rand(0,$this->width*2), $this->height, $bgcolor);
		}
	}

	/**
	 * [GetRandString 生成随机数值]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:54+0800
	 */
	private function GetRandString()
	{
		$origstr = '3456789abxdefghijkmnprstuvwxy';
		$string = '';
		$len = strlen($origstr);
		for($i=0; $i<$this->length; $i++)
		{
			$index = mt_rand(0, $len-1);
			$char = $origstr[$index];
			$string .= $char;
		}
		return $string;
	}
}
?>