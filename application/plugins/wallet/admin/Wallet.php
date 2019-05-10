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
namespace app\plugins\wallet\admin;

use think\Controller;
use app\plugins\wallet\service\WalletService;
use app\plugins\wallet\service\BaseService;

/**
 * 钱包插件 - 钱包管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Wallet extends Controller
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
        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = BaseService::WalletWhere($params);

        // 获取总数
        $total = BaseService::WalletTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsAdminUrl('wallet', 'wallet', 'index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        if($total > 0)
        {
            $data_params = array(
                'm'         => $page->GetPageStarNumber(),
                'n'         => $number,
                'where'     => $where,
            );
            $data = BaseService::WalletList($data_params);
            $this->assign('data_list', $data['data']);
        } else {
            $this->assign('data_list', []);
        }

        // 静态数据
        $this->assign('wallet_status_list', WalletService::$wallet_status_list);

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/admin/wallet/index');
    }

    /**
     * 钱包编辑页面
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-05
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        $data = [];
        if(!empty($params['id']))
        {
            $data_params = array(
                'm'         => 0,
                'n'         => 1,
                'where'     => ['id'=>intval($params['id'])],
            );
            $ret = BaseService::WalletList($data_params);
            if(!empty($ret['data'][0]))
            {
                $data = $ret['data'][0];

                // 静态数据
                $this->assign('wallet_status_list', WalletService::$wallet_status_list);
            } else {
                $this->assign('msg', '钱包有误');
            }
        } else {
            $this->assign('msg', '钱包id有误');
        }

        $this->assign('data', $data);
        return $this->fetch('../../../plugins/view/wallet/admin/wallet/saveinfo');
    }

    /**
     * 钱包编辑
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-06
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        return WalletService::WalletEdit($params);
    }
}
?>