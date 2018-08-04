<?php

namespace Api\Controller;
use Think\Controller;

/**
 * 前台
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CommonController extends Controller
{
	// 用户信息
	protected $user;

    // 输入参数 post
    protected $data_post;

    // 输入参数 get
    protected $data_get;

    // 输入参数 request
    protected $data_request;

	/**
	 * [__construt 构造方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:29:53+0800
	 * @param    [string]       $msg  [提示信息]
	 * @param    [int]          $code [状态码]
	 * @param    [mixed]        $data [数据]
	 */
	protected function _initialize()
	{
		// 配置信息初始化
		MyConfigInit();

        // 页面初始化
        $this->ViewInit();

        // 网站状态
        $this->SiteStstusCheck();

		// 公共数据初始化
		$this->CommonInit();

        // 输入参数
        $this->data_post = I('post.');
        $this->data_get = I('get.');
        $this->data_request = I('request.');
	}

    /**
     * [SiteStstusCheck 网站状态]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-18T16:20:58+0800
     */
    private function SiteStstusCheck()
    {
        if(MyC('home_site_state') != 1)
        {
            if(IS_AJAX)
            {
                $this->ajaxReturn(Myc('home_site_close_reason'));
            } else {
                $this->assign('home_seo_site_title', L('home_site_close_msg'));
                $this->assign('msg', MyC('home_site_close_reason', L('common_site_maintenance_tips'), true));
                $this->assign('is_footer', 0);
                $this->display('/Public/Error');
                exit;
            }
        }
    }

	/**
	 * [ajaxReturn 重写ajax返回方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-07T22:03:40+0800
	 * @param    [string]       $msg  [提示信息]
	 * @param    [int]          $code [状态码]
	 * @param    [mixed]        $data [数据]
	 * @return   [json]               [json数据]
	 */
	protected function ajaxReturn($msg = '', $code = -1, $data = '')
	{
		// ajax的时候，success和error错误由当前方法接收
		if(IS_AJAX)
		{
			if(isset($msg['info']))
			{
				// success模式下code=0, error模式下code参数-1
				$result = array('msg'=>$msg['info'], 'code'=>-1, 'data'=>'');
			}
		}
		
		// 默认情况下，手动调用当前方法
		if(empty($result))
		{
			$result = array('msg'=>$msg, 'code'=>$code, 'data'=>$data);
		}

		// 错误情况下，防止提示信息为空
		if($result['code'] != 0 && empty($result['msg']))
		{
			$result['msg'] = L('common_operation_error');
		}
		exit(json_encode($result));
	}

	/**
	 * [Is_Login 登录校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:43:48+0800
	 */
	protected function Is_Login()
	{
		$user_id = I('request.user_id');
		if(empty($user_id))
		{
			$this->ajaxReturn('请先获取用户授权信息', -1000);
		}
	}

    /**
     * 站点校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-15
     */
    protected function Is_Merchant()
    {
        if(empty($this->merchant))
        {
            $this->ajaxReturn('站点资料有误', -2000);
        }
    }

	/**
	 * [CommonInit 公共数据初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:43:48+0800
	 */
	private function CommonInit()
	{
		// 用户数据
		$user_id = I('request.user_id');
		if(!empty($user_id))
		{
			$this->user = M('User')->where(['is_delete_time'=>0, 'id'=>$user_id])->find();

            // 站点
            if(!empty($this->user))
            {
                $this->merchant = M('Merchant')->where(['is_delete_time'=>0, 'user_id'=>$user_id])->find();
            }
		}
	}

    /**
     * [ViewInit 视图初始化]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:30:06+0800
     */
    public function ViewInit()
    {
        // 主题
        $default_theme = C('DEFAULT_THEME');
        $this->assign('default_theme', $default_theme);

        // 控制器静态文件状态css,js
        $module_css = MODULE_NAME.DS.$default_theme.DS.'Css'.DS.CONTROLLER_NAME.'.css';
        $this->assign('module_css', file_exists(ROOT_PATH.'Public'.DS.$module_css) ? $module_css : '');
        $module_js = MODULE_NAME.DS.$default_theme.DS.'Js'.DS.CONTROLLER_NAME.'.js';
        $this->assign('module_js', file_exists(ROOT_PATH.'Public'.DS.$module_js) ? $module_js : '');

        // 页面最大宽度
        $max_width = MyC('home_content_max_width', 0, true);
        $max_width_style = ($max_width == 0) ? '' : 'max-width:'.$max_width.'px;';
        $this->assign('max_width_style', $max_width_style);

        // 图片host地址
        $this->assign('image_host', C('IMAGE_HOST'));
    }

	/**
	 * [_empty 空方法操作]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-25T15:47:50+0800
	 * @param    [string]      $name [方法名称]
	 */
	protected function _empty($name)
	{
		$this->assign('msg', L('common_unauthorized_access'));
		$this->assign('is_footer', 0);
		$this->display('/Public/Error');
	}

    /**
     * [GetSubLatLngSql 根据坐标返回条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-25T15:47:50+0800
     * @param [float]       $lng        [经度]
     * @param [float]       $lat        [纬度]
     * @param [int]         $distance   [范围(千米)]
     * @return[sql条件]                 [坐标sql条件]
     */
    protected function GetLatLngWhere($lng, $lat, $distance = 3)
    {
        if(empty($lng) || empty($lat)) return [];

        $where = [];
        $coordinate = ReturnSquarePoint($lng, $lat, $distance);
        if(!empty($coordinate))
        {
            $where[] = [
                'lat' => [
                    ['GT', $coordinate['right-bottom']['lat']],
                    ['LT', $coordinate['left-top']['lat']],
                ],
                'lng' => [
                    ['GT', $coordinate['left-top']['lng']],
                    ['LT', $coordinate['right-bottom']['lng']],
                ],
            ];
        }
        return $where;
    }

    /**
     * 用户钱包操作
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-20
     * @desc    description
     * @param   [int]          $user_id        [用户id]
     * @param   [float]        $price          [操作金额]
     * @param   [string]       $price_type     [金额类型（k可用金额, d冻结金额）]
     * @param   [string]       $towork         [操作类型（z增加, j减少）]
     * @param   [string]       $pay_type       [支付类型（0支付宝, 1微信）]
     * @param   [string]       $monew_type     [金额操作类型（s手动修改, c充值, t提现, x消费, r收入）]
     * @param   [string]       $remarks        [备注]
     * @param   [string]       $operation_id   [操作人id（0为系统, 管理员id或用户id）]
     * @param   [string]       $operating_type [操作用户类型（x系统, a管理员, u用户）]
     */
    protected function CommonUserWalletUpdate($user_id, $price, $price_type, $towork, $pay_type, $monew_type, $remarks='', $operation_id=0, $operating_type='x')
    {
        // 参数
        if(empty($user_id) || $price <= 0.00 || !in_array($price_type, ['k', 'd']) || !in_array($towork, ['z', 'j']))
        {
            return -1;
        }

        // 模型
        $wallet_m = M('Wallet');

        // 条件
        $where = ['user_id' => $user_id];

        // 获取钱包原始数据
        $wallet_data = $wallet_m->where($where)->find();
        if(empty($wallet_data))
        {
            return -2;
        }

        // 操作字段
        $field = ($price_type == 'k') ? 'available' : 'frozen';

        // 操作类型
        $status = false;
        switch($towork)
        {
            case 'z' :
                $status = $wallet_m->where($where)->setInc($field, $price);
                break;

            case 'j' :
                $status = $wallet_m->where($where)->setDec($field, $price);
                break;
        }
        if($status)
        {
            // 日志
            $log_data = [
                'wallet_id'     => $wallet_data['id'],
                'user_id'       => $user_id,
                'towork'        => $towork,
                'money_type'    => $price_type,
                'raw'           => $wallet_data[$field],
                'new'           => $wallet_m->where($where)->getField($field),
                'monew'         => $price,
                'pay_type'      => $pay_type,
                'monew_type'    => $monew_type,
                'remarks'       => $remarks,
                'operation_id'  => $operation_id,
                'operating_type'=> $operating_type,
                'add_time'      => time(),
            ];
            return (M('WalletLog')->add($log_data) > 0);
        }
        return false;
    }
}
?>