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
                          body{color:#333;}
                          h1 {text-align:center;}
                          h1,.content {margin-top:50px;}
                          .content { text-align: left;margin:0 auto;max-width:800px;height:auto;border: 6px solid #eee;padding: 20px;}
                          ul {margin:0 0 15px 0;padding:0;background: #f5f5f5;border: 1px solid #eaeaea;}
                          ul li {list-style-type:none;line-height:42px;font-size:16px;padding: 0 10px;}
                          ul li:not(:last-child) {border-bottom: 1px solid #eee;}
                          .content img {width: 100%;}
                          .content .tips {margin-bottom: 15px;font-size: 14px;background: #ffddaa;border: 1px solid #ffb342;color: #875100;padding: 5px 10px;line-height: 22px;}
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