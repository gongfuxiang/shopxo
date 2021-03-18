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
namespace payment;

/**
 * 现金支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class CashPayment
{
    // 插件配置参数
    private $config;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]           $params [输入参数（支付配置参数）]
     */
    public function __construct($params = [])
    {
        $this->config = $params;
    }

    /**
     * 配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => '现金支付',  // 插件名称
            'version'       => '2.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'desc'          => '现金方式支付货款、支持配置自定义支付信息',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'select',
                'title'         => '自定义支付信息展示',
                'desc'          => '仅web端有效',
                'message'       => '请选择是否开启自定义支付',
                'name'          => 'is_custom_pay',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>0, 'name'=>'关闭'],
                    ['value'=>1, 'name'=>'开启'],
                ],
            ],
            [
                'element'       => 'textarea',
                'name'          => 'content',
                'placeholder'   => '自定义文本',
                'title'         => '自定义文本',
                'desc'          => '可换行、一行一条数据',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写自定义文本',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'tips',
                'placeholder'   => '特别提示信息',
                'title'         => '特别提示信息',
                'is_required'   => 0,
                'message'       => '请填写特别提示信息',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'images_url',
                'placeholder'   => '图片地址',
                'title'         => '图片地址',
                'desc'          => '可自定义图片展示',
                'is_required'   => 0,
                'message'       => '请填写图片自定义的地址',
            ],
        ];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    /**
     * 支付入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Pay($params = [])
    {
        // 是否开启了自定义支付信息
        if(!empty($this->config) && isset($this->config['is_custom_pay']) && $this->config['is_custom_pay'] == 1)
        {
            $html = '<!DOCTYPE html>
                        <html>
                        <head>
                        <meta charset="utf-8">
                        <title>支付信息</title>
                        <style>
                          body{color:#333;background: #f7f7f7;}
                          h1 {text-align:center;}
                          h1,.content {margin-top:50px;}
                          .content { text-align: left;margin:0 auto;max-width:800px;height:auto;border: 1px solid #e5e5e5;padding: 30px;background:#fff;}
                          ul {margin:0 0 15px 0;padding:0;background: #fcfcfc;border: 1px solid #eeeeee;}
                          ul li {list-style-type:none;line-height:42px;font-size:16px;padding: 0 10px;}
                          ul li:not(:last-child) {border-bottom: 1px solid #f2f2f2;}
                          .content img {width: 100%;}
                          .content .tips {margin-bottom: 15px;font-size: 14px;background: #ffddaa;border: 1px solid #ffb342;color: #875100;padding: 5px 10px;line-height: 22px;}
                          .content .pay-price strong {color:#c00;}
                          .content .pay-price,.content .tips-time {margin-bottom: 15px;font-size: 16px;line-height: 24px;}
                          .content .tips-time {color:#2196f3;}
                          .content .tips-time strong {color:#f00;}
                          .content .btn-list{text-align: center;padding: 10px 0;margin-top:30px;}
                          .content .btn-list a:not(:last-child) {margin-right:50px;}
                          .content .btn-list a {text-decoration: none;background: #666;padding: 5px 10px;border-radius: 2px;color: #fff;}
                          .content .btn-list a:first-child{background: #d2364c;}
                          .content .btn-list a:last-child{background: #4caf50;}
                        </style>
                        </head>
                        <body>
                          <h1>按照以下支付信息进行打款</h1>
                          <div class="content">';

            // 文本信息
            if(!empty($this->config['content']))
            {
                $html .= '<ul>';
                $content = explode("\n", $this->config['content']);
                foreach($content as $v)
                {
                    $html .= '<li>'.$v.'</li>';
                }
                $html .= '</ul>';
            }

            // 支付金额
            $html .= '<p class="pay-price">打款金额：<strong>￥'.$params['total_price'].'</strong></p>';

            // 订单关闭提示
            $order_close_time = time()+((MyC('common_order_close_limit_time', 30, true)-5)*60);
            $html .= '<p class="tips-time">订单预计[ <strong>'.date('m月d号H点i分', $order_close_time).'</strong> ]自动关闭、请尽快完成支付，打款备注：<strong>'.$params['order_no'].'</strong></p>';

            // 特别提示文字
            if(!empty($this->config['tips']))
            {
                $html .= '<p class="tips">'.$this->config['tips'].'</p>';
            }

            // 图片信息
            if(!empty($this->config['images_url']))
            {
                $html .= '<img src="'.$this->config['images_url'].'" alt="支付信息" />';
            }

            // 导航入口
            $home_url = __MY_URL__;
            $order_url = MyUrl('index/order/index');
            $html .= '<div class="btn-list"><a href="'.$home_url.'">回到首页</a><a href="'.$order_url.'">进入我的订单</a></div>';
                            
            $html .= '</div>
                    </body>
                </html>';

            die($html);
        }

        // 默认方式
        $url = $params['call_back_url'].'?';
        $url .= 'out_trade_no='.$params['order_no'];
        $url .= '&subject='.$params['name'];
        $url .= '&total_price='.$params['total_price'];
        return DataReturn('处理成功', 0, $url);
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        return DataReturn('处理成功', 0, $params);
    }
}
?>