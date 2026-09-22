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

/**
 * 后台 API 业务基础（强制登录）
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-09-18
 * @desc    必须登录的业务接口直接继承此类
 */
class Base extends Common
{
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
        $this->IsAdminLogin();
    }
}
?>
