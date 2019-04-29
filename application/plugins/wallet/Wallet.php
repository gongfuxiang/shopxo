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
namespace app\plugins\wallet;

use think\Controller;
use app\plugins\wallet\Service;
use app\service\PluginsService;
use app\service\UserService;
use app\service\IntegralService;

use app\service\PaymentService;

/**
 * 我的钱包 - 钱包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Wallet extends Controller
{
    private $user;

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

        // 用户信息
        $this->user = UserService::LoginUserInfo();

        // 登录校验
        if(empty($this->user))
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
            } else {
                return $this->redirect('index/user/logininfo');
            }
        }

        // 发起支付 - 支付方式
        $this->assign('buy_payment_list', PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));
    }

    /**
     * 钱包明细
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        // 参数
        $params = input();
        $params['user'] = $this->user;

        // 分页
        $number = 10;

        // 条件
        $where = IntegralService::UserIntegralLogListWhere($params);

        // 获取总数
        $total = IntegralService::UserIntegralLogTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  MyUrl('index/userintegral/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = IntegralService::UserIntegralLogList($data_params);
        $this->assign('data_list', $data['data']);

        // 操作类型
        $this->assign('common_integral_log_type_list', lang('common_integral_log_type_list'));

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/wallet/index');
    }

    /**
     * 充值明细
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function recharge($params = [])
    {
        // 参数
        $params['user'] = $this->user;

        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = Service::RechargeListWhere($params);

        // 获取总数
        $total = Service::RechargeTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsHomeUrl('wallet', 'wallet', 'recharge'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = Service::RechargeList($data_params);
        $this->assign('data_list', $data['data']);

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/wallet/recharge');
    }

    /**
     * 余额提现
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function cash($params = [])
    {
        // 参数
        $params = input();
        $params['user'] = $this->user;

        $this->assign('data_list', []);


        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/wallet/cash');
    }

    /**
     * 充值订单创建
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function rechargecreate($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 用户
        $params['user'] = $this->user;
        return Service::RechargeCreate($params);
    }

    /**
     * 支付
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function pay($params = [])
    {
        // 用户
        $params['user'] = $this->user;
        return Service::Pay($params);
    }

    /**
     * 支付状态校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function paycheck($params = [])
    {
        if(input('post.'))
        {
            $params['user'] = $this->user;
            return Service::RechargePayCheck($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * 支付同步页面
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function respond($params = [])
    {
        $this->assign('msg', '支付失败');
        return $this->fetch('public/pay_error');
    }

    /**
     * 支付异步通知
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function notify($params = [])
    {
        $this->assign('msg', '支付成功');
        return $this->fetch('public/pay_success');
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

        // 用户
        $params['user_id'] = $this->user['id'];
        return Service::PetsUntying($params);
    }

    /**
     * 丢失提供信息添加/编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function helpsave($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 用户
        $params['user_id'] = $this->user['id'];
        return Service::HelpSave($params);
    }
}
?>