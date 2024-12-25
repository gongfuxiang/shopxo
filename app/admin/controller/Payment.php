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
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\PaymentService;
use app\service\StoreService;
use app\service\ResourcesService;

/**
 * 支付方式管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Payment extends Base
{
    private $nav_type;

    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 导航类型
        $this->nav_type = empty($this->data_request['type']) ? 0 : intval($this->data_request['type']);
    }

	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
        // 模板数据
        $assign = [
            // 应用商店
            'store_payment_url' => StoreService::StorePaymentUrl(),
        ];

        // 页面类型
        $view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];
        if($view_type == 'index')
        {
            // 插件列表
            $payment = PaymentService::PluginsPaymentList($this->nav_type);

            // 插件更新信息
            $upgrade = PaymentService::PaymentUpgradeInfo($payment['data']);

            // 模板数据
            $assign = array_merge($assign, [
                // 导航类型
                'nav_type'              => $this->nav_type,
                // 支付插件列表
                'data_list'             => empty($payment['data']) ? [] : $payment['data'],
                // 不能删除的支付方式
                'cannot_deleted_list'   => PaymentService::$cannot_deleted_list,
                // 管理导航
                'nav_data'              => MyLang('payment.base_nav_list'),
                // 适用平台
                'common_platform_type'  => MyConst('common_platform_type'),
                // 支付插件更新信息
                'upgrade_info'          => $upgrade['data'],
            ]);
        }
        MyViewAssign($assign);
        return MyView($view_type);
	}

    /**
     * 添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        $data = [];
        if(!empty($this->data_request['id']))
        {
            $data_params = [
                'where'             => ['id'=>$this->data_request['id']],
                'm'                 => 0,
                'n'                 => 1,
            ];
            $data = PaymentService::PaymentList($data_params);
            if(!empty($data) && !empty($data[0]))
            {
                $data = $data[0];
            }
        }
        // 模板数据
        $assign = [
            // 当前数据
            'data'                  => $data,
            // 适用平台
            'common_platform_type'  => MyConst('common_platform_type'),
            // 编辑器文件存放地址
            'editor_path_type'      => ResourcesService::EditorPathTypeValue('payment'),
        ];
        MyViewAssign($assign);
        return MyView();
    }

	/**
	 * 保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Save()
	{
        return ApiService::ApiDataReturn(PaymentService::PaymentSave($this->data_request));
	}

	/**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        return ApiService::ApiDataReturn(PaymentService::PaymentStatusUpdate($this->data_request));
    }

    /**
     * 安装
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Install()
    {
        return ApiService::ApiDataReturn(PaymentService::Install($this->data_request));
    }

    /**
     * 卸载
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Uninstall()
    {
        return ApiService::ApiDataReturn(PaymentService::Uninstall($this->data_request));
    }

    /**
     * 删除插件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(PaymentService::Delete($this->data_request));
    }

    /**
     * 上传插件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Upload()
    {
        return ApiService::ApiDataReturn(PaymentService::Upload($this->data_request));
    }

    /**
     * 应用市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Market()
    {
        $ret = PaymentService::PaymentMarket($this->data_request);
        if($ret['code'] == 0 && isset($ret['data']['data_list']))
        {
            $ret['data']['data_list'] = MyView('public/market/list', ['data_list'=>$ret['data']['data_list']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>