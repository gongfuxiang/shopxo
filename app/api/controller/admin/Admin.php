<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\api\controller\admin;

use app\service\AdminService;

/**
 * 管理员（对齐后台 app/admin/controller/Admin.php）
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-09-18
 * @desc    继承 Common；登录/验证码/退出免登，其它方法自行 LoginCheck
 */
class Admin extends Common
{
    // 当前业务方法（小写）
    protected $business_action;

    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->LoginCheck();
    }

    /**
     * 当前业务方法名
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     */
    protected function BusinessActionName()
    {
        if(!empty($this->props_params['business_action']))
        {
            return strtolower(strval($this->props_params['business_action']));
        }
        if(!empty($this->props_params['data_request']['business_action']))
        {
            return strtolower(strval($this->props_params['data_request']['business_action']));
        }
        return '';
    }

    /**
     * 本控制器无需登录的方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     */
    protected function NoLoginActionList()
    {
        return [
            'login',
            'logout',
            'loginverifysend',
            'adminverifyentry',
        ];
    }

    /**
     * 登录校验（白名单方法除外）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     */
    protected function LoginCheck()
    {
        $business_action = $this->BusinessActionName();
        if($business_action !== '' && in_array($business_action, $this->NoLoginActionList(), true))
        {
            return;
        }
        $this->IsAdminLogin();
    }

    /**
     * 账号密码登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     */
    public function Login($params = [])
    {
        if(empty($params['type']))
        {
            $params['type'] = 'username';
        }
        return AdminService::Login($params);
    }

    /**
     * 登录验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     */
    public function LoginVerifySend($params = [])
    {
        return AdminService::LoginVerifySend($params);
    }

    /**
     * 图形验证码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     */
    public function AdminVerifyEntry($params = [])
    {
        $verify = new \base\Verify([
            'width'         => 100,
            'height'        => 28,
            'key_prefix'    => 'admin_login',
            'expire_time'   => MyC('common_verify_expire_time'),
        ]);
        $verify->Entry();
    }

    /**
     * 退出登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     */
    public function Logout($params = [])
    {
        return AdminService::ApiLoginLogout(empty($this->admin) ? [] : $this->admin);
    }

    /**
     * 当前管理员信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     */
    public function Token($params = [])
    {
        return DataReturn('success', 0, $this->admin);
    }
}
?>
