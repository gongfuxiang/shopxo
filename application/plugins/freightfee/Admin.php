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
namespace app\plugins\freightfee;

use think\Db;
use think\Controller;
use app\service\PluginsService;
use app\service\RegionService;
use app\service\PaymentService;

/**
 * 运费设置 - 管理
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
        $ret = PluginsService::PluginsData('freightfee');
        if($ret['code'] == 0)
        {
            $this->assign('data', $this->DataHandle($ret['data']));
            return $this->fetch('../../../plugins/view/freightfee/admin/index');
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
        $ret = PluginsService::PluginsData('freightfee');
        if($ret['code'] == 0)
        {
            // 是否
            $is_whether_list =  [
                0 => array('id' => 0, 'name' => '按件数', 'checked' => true),
                1 => array('id' => 1, 'name' => '按重量'),
            ];

            // 地区
            $region = RegionService::RegionItems(['pid'=>0, 'field'=>'id,name']);
            if(!empty($region))
            {
                $region = array_map(function($v)
                {
                    $v['items'] = RegionService::RegionItems(['pid'=>$v['id'], 'field'=>'id,name']);
                    return $v;
                }, $region);
            }

            // 支付方式
            $this->assign('payment_list', PaymentService::PaymentList(['is_enable'=>1, 'is_open_user'=>1]));

            $this->assign('region_list', $region);
            $this->assign('is_whether_list', $is_whether_list);
            $this->assign('data', $this->DataHandle($ret['data']));
            return $this->fetch('../../../plugins/view/freightfee/admin/saveinfo');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     * @param   [array]          $data [应用数据]
     */
    private function DataHandle($data)
    {
        if(!empty($data['data']))
        {
            if(empty($data['payment']))
            {
                $data['payment'] = [];
                $data['payment_names'] = '';
            } else {
                $data['payment'] = explode(',', $data['payment']);
                $data['payment_names'] = implode('、', array_map(function($v){return mb_substr($v, strrpos($v, '-')+1, null, 'utf-8');}, $data['payment']));
            }

            foreach($data['data'] as &$v)
            {
                $v['region_names'] = empty($v['region_show']) ? '' : implode('、', Db::name('Region')->where('id', 'in', explode('-', $v['region_show']))->column('name'));
            }
        }
        return $data;
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
        return PluginsService::PluginsDataSave(['plugins'=>'freightfee', 'data'=>$params]);
    }
}
?>