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
namespace app\service;

/**
 * app服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppService
{
    /**
     * 商品详情导航更多列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function GoodsNavMoreList($params = [])
    {
        $data = [];
        if(!empty($params['page']))
        {
            $is_goods_detail_show_left_more = MyC('common_is_goods_detail_show_left_more', 0) == 1;
            if($is_goods_detail_show_left_more)
            {
                $lang = MyLang('app_goods_nav_more_list_data');
                switch($params['page'])
                {
                    // 商品页面
                    // icon 参考各终端
                    // web http://amazeui.shopxo.net/css/icon
                    // uniapp https://hellouniapp.dcloud.net.cn/pages/extUI/icons/icons
                    case 'goods' :
                        if($is_goods_detail_show_left_more)
                        {
                            $data = [
                                [
                                    'name'  => $lang['goodsfavor'],
                                    'url'   => '/pages/user-favor/user-favor',
                                    'icon'  => 'heart'
                                ],
                                [
                                    'name'  => $lang['goodsbrowse'],
                                    'url'   => '/pages/user-goods-browse/user-goods-browse',
                                    'icon'  => 'eye'
                                ],
                                [
                                    'name'  => $lang['home'],
                                    'url'   => '/pages/index/index',
                                    'icon'  => 'home'
                                ]
                            ];
                        }
                        break;
                }
            }
        }

        // 导航更多信息钩子
        $hook_name = 'plugins_service_app_goods_more_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 首页右侧icon列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function HomeRightIconList($params = [])
    {
        // 消息总数
        $message_total = empty($params['message_total']) ? 0 : $params['message_total'];

        // 列表数据
        // name 名称（必填）
        // icon 图标（必填、参考uniapp扩展图标文档）
        // url  访问地址（可选）
        $lang = MyLang('app_home_right_list_data');
        $data = [
            [
                'name'  => $lang['goodsfavor'],
                'icon'  => 'icon-star',
                'url'   => '/pages/user-favor/user-favor',
            ],
            [
                'name'  => $lang['usermessage'],
                'icon'  => 'icon-message',
                'badge' => $message_total,
                'url'   => '/pages/message/message',
            ]
        ];

        // 钩子
        $hook_name = 'plugins_service_app_home_right_icon_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => $params,
        ]);

        return $data;
    }
}
?>