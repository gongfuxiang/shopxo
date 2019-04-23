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
namespace app\plugins\footercustomerservice;

use think\Controller;
use app\plugins\footercustomerservice\Service;
use app\service\PluginsService;

/**
 * 底部客户服务介绍插件 - 管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin extends Controller
{
    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        $ret = PluginsService::PluginsData('footercustomerservice', null, false);
        if($ret['code'] == 0)
        {
            // 数据列表
            $list = Service::DataList();
            $this->assign('data_list', $list['data']);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/footercustomerservice/admin/index');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        $ret = PluginsService::PluginsData('footercustomerservice', null, false);
        if($ret['code'] == 0)
        {
            // 是否
            $is_whether_list =  [
                0 => array('id' => 0, 'name' => '否', 'checked' => true),
                1 => array('id' => 1, 'name' => '是'),
            ];
            $this->assign('is_whether_list', $is_whether_list);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/footercustomerservice/admin/saveinfo');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 数据保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        $list = Service::DataList();
        $params['data_list'] = $list['data'];
        return PluginsService::PluginsDataSave(['plugins'=>'footercustomerservice', 'data'=>$params]);
    }



    /**
     * 数据列表页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function dataindex($params = [])
    {
        $list = Service::DataList();
        if($list['code'] == 0)
        {
            $this->assign('data_list', $list['data']);
            return $this->fetch('../../../plugins/view/footercustomerservice/admin/dataindex');
        } else {
            return $list['msg'];
        }
    }

    /**
     * 数据列表编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function datainfo($params = [])
    {
        // 数据
        $data = [];
        if(!empty($params['id']))
        {
            $data_params = [
                'get_id'    => $params['id'],
            ];
            $ret = Service::DataList($data_params);
            $data = empty($ret['data']) ? [] : $ret['data'];
        }
        $this->assign('data', $data);
        
        return $this->fetch('../../../plugins/view/footercustomerservice/admin/datainfo');
    }

    /**
     * 数据列表保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function datasave($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return Service::DataSave($params);
    }

    /**
     * 数据列表删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     * @param    [array]          $params [输入参数]
     */
    public function datadelete($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return Service::DataDelete($params);
    }

    /**
     * 数据列表状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     * @param    [array]          $params [输入参数]
     */
    public function datastatusupdate($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return Service::DataStatusUpdate($params);
    }
}
?>