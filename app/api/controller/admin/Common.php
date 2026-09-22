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
 * 后台 API 业务公共
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-09-18
 * @desc    仅解析管理员信息；是否强制登录由 Base / 业务控制器自行处理
 */
class Common
{
    // 公共属性参数数据
    protected $props_params;

    // 当前管理员
    protected $admin;

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
        $this->props_params = $params;
        $this->AdminInit();
    }

    /**
     * 管理员信息初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     */
    protected function AdminInit()
    {
        $req = empty($this->props_params['data_request']) ? [] : $this->props_params['data_request'];
        $token = '';
        if(!empty($req['token']))
        {
            $token = strval($req['token']);
        } elseif(!empty($req['admin_token']))
        {
            $token = strval($req['admin_token']);
        }
        if($token !== '')
        {
            $this->admin = AdminService::AdminTokenData($token);
        } else {
            $this->admin = AdminService::LoginInfo();
        }
    }

    /**
     * 是否已登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     */
    protected function IsAdminLogin()
    {
        if(empty($this->admin) || empty($this->admin['id']))
        {
            header('Content-Type: application/json; charset=utf-8');
            exit(json_encode(DataReturn(MyLang('login_failure_tips'), -400)));
        }
    }

    /**
     * 属性读取处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [string]          $name [属性名称]
     */
    public function __get($name)
    {
        return (!empty($this->props_params) && is_array($this->props_params) && isset($this->props_params[$name])) ? $this->props_params[$name] : null;
    }
}
?>
