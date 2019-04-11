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
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 我的宠物
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        $this->assign('pets_attribute_is_text_list', Service::$pets_attribute_is_text_list);
        $this->assign('pets_attribute_gender_list', Service::$pets_attribute_gender_list);
        $this->assign('pets_attribute_type_list', Service::$pets_attribute_type_list);
        return $this->fetch('../../../plugins/view/petscms/pets/index');
    }

    /**
     * 宠物添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        $this->assign('data', []);
        $this->assign('pets_attribute_is_text_list', Service::$pets_attribute_is_text_list);
        $this->assign('pets_attribute_gender_list', Service::$pets_attribute_gender_list);
        $this->assign('pets_attribute_type_list', Service::$pets_attribute_type_list);
        return $this->fetch('../../../plugins/view/petscms/pets/saveinfo');
    }

    /**
     * 宠物添加/编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        $ret = Service::PestSave($params);
        print_r($ret);
        die;
    }
}
?>