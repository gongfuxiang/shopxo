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
namespace app\plugins\membershiplevel\admin;

use think\Controller;
use app\plugins\membershiplevel\service\Service;
use app\service\PluginsService;

/**
 * 会员等级管理插件 - 管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Level extends Controller
{

    /**
     * 等级页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        $ret = Service::LevelDataList();
        if($ret['code'] == 0)
        {
            $this->assign('data_list', $ret['data']);
            $this->assign('params', $params);
            return $this->fetch('../../../plugins/view/membershiplevel/admin/level/index');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 等级编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        // 数据
        $data = [];
        if(!empty($params['id']))
        {
            $data_params = [
                'get_id'        => $params['id'],
            ];
            $ret = Service::LevelDataList($data_params);
            $data = empty($ret['data']) ? [] : $ret['data'];
        }
        $this->assign('data', $data);
        
        return $this->fetch('../../../plugins/view/membershiplevel/admin/level/saveinfo');
    }

    /**
     * 等级保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return Service::LevelDataSave($params);
    }

    /**
     * 等级状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     * @param    [array]          $params [输入参数]
     */
    public function statusupdate($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params['data_field'] = 'level_list';
        return Service::DataStatusUpdate($params);
    }

    /**
     * 等级删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     * @param    [array]          $params [输入参数]
     */
    public function delete($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params['data_field'] = 'level_list';
        return Service::DataDelete($params);
    }
}
?>