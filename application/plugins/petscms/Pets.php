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
namespace app\plugins\petscms;

use think\Controller;
use app\plugins\petscms\Service;
use app\service\PluginsService;

/**
 * 宠物管理系统 - 用户宠物管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pets extends Controller
{
    /**
     * 订单查询入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        return $this->fetch('../../../plugins/view/petscms/pets/index');
    }
}
?>