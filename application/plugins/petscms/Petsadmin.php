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
use app\service\UserService;

/**
 * 宠物管理系统 - 宠物管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PetsAdmin extends Controller
{
    /**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = Service::PetsListWhere($params);

        // 获取总数
        $total = Service::PetsTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsAdminUrl('petscms', 'petsadmin', 'index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = Service::PetsList($data_params);
        $this->assign('data_list', $data['data']);
        $this->assign('params', $params);
        $this->assign('pets_attribute_status_list', Service::$pets_attribute_status_list);
        $this->assign('pets_attribute_is_text_list', Service::$pets_attribute_is_text_list);
        $this->assign('pets_attribute_gender_list', Service::$pets_attribute_gender_list);
        $this->assign('pets_attribute_type_list', Service::$pets_attribute_type_list);
        return $this->fetch('../../../plugins/view/petscms/petsadmin/index');
    }

    /**
     * 添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        // 获取数据
        $data = [];
        if(!empty($params['id']))
        {
            $data_params = array(
                'm'         => 0,
                'n'         => 1,
                'where'     => ['id' => intval($params['id'])],
            );
            $ret = Service::PetsList($data_params);
            if(!empty($ret['data'][0]))
            {
                $ret['data'][0]['lose_features'] = str_replace('<br />', "\n", $ret['data'][0]['lose_features']);
                $data = $ret['data'][0];
            }
            unset($params['id']);
        }
        $this->assign('params', $params);
        $this->assign('data', $data);
        $this->assign('pets_attribute_status_list', Service::$pets_attribute_status_list);
        $this->assign('pets_attribute_is_text_list', Service::$pets_attribute_is_text_list);
        $this->assign('pets_attribute_gender_list', Service::$pets_attribute_gender_list);
        $this->assign('pets_attribute_type_list', Service::$pets_attribute_type_list);
        return $this->fetch('../../../plugins/view/petscms/petsadmin/saveinfo');
    }

    /**
     * 宠物详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function detail($params = [])
    {
        // 获取数据
        $data = [];
        if(empty($params['id']))
        {
            return '宠物id有误';
        }
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => ['id' => intval($params['id']), 'status'=>[0,1,2]],
        );
        $ret = Service::PetsList($data_params);
        $data = empty($ret['data'][0]) ? [] : $ret['data'][0];

        $this->assign('data', $data);
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/petscms/petsadmin/detail');
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 用户
        return Service::PetsSave($params);
    }

    /**
     * 宠物解绑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function untying($params = [])
    {
        // 是否ajax
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始操作
        return Service::PetsUntying($params);
    }

    /**
     * 宠物删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function delete($params = [])
    {
        // 是否ajax
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始操作
        return Service::PetsDelete($params);
    }

    /**
     * 宠物帮助数据列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function help($params = [])
    {
        // 参数
        if(empty($params['pets_id']))
        {
            return '参数有误';
        }

        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = [
            'pets_id'   => intval($params['pets_id']),
        ];

        // 获取总数
        $total = Service::HelpTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsAdminUrl('petscms', 'petsadmin', 'index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = Service::HelpList($data_params);
        unset($params['pets_id']);
        $this->assign('data_list', $data['data']);
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/petscms/petsadmin/help');
    }

    /**
     * 宠物帮助数据地图展示
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function helpmap($params = [])
    {
        // 隐藏头尾
        $this->assign('is_header', 0);
        $this->assign('is_footer', 0);

        // 参数
        $params = input();
        if(!empty($params['lng']))
        {
            $params['lng'] = base64_decode($params['lng']);
        }
        if(!empty($params['lat']))
        {
            $params['lat'] = base64_decode($params['lat']);
        }
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/petscms/petsadmin/helpmap');
    }
}
?>