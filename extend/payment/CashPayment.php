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

use app\service\PayLogService;

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
            'version'       => '2.0.1',  // 插件版本
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
            if(APPLICATION == 'web')
            {
                $h1_margin = '50px;';
                $margin = '50px;';
                $padding = '30px;';
                $radius = '2px;';
            } else {
                $h1_margin = '10px;';
                $margin = '20px;';
                $padding = '10px;';
                $radius = '10px;';
            }
            $html = '<h1 style="text-align:center;margin-top:'.$h1_margin.'">按照以下信息进行打款</h1>
                    <div style="text-align: left;margin:0 auto;max-width:800px;height:auto;border: 1px solid #f4f4f4;padding: '.$padding.';background:#fff;margin-top:'.$margin.'border-radius:'.$radius.'">';

            // 文本信息
            if(!empty($this->config['content']))
            {
                $html .= '<ul style="margin:0;padding:0;background: #fafafa;border: 1px solid #f4f4f4;border-radius:'.$radius.'">';
                $content = explode("\n", $this->config['content']);
                foreach($content as $k=>$v)
                {
                    $temp_style = ($k > 0) ? 'border-top: 1px solid #f2f2f2;' : '';
                    $html .= '<li style="'.$temp_style.'list-style-type:none;line-height:22px;font-size:14px;padding: 5px 10px;">'.$v.'</li>';
                }
                $html .= '</ul>';
            }

            // 支付金额
            $html .= '<p style="margin-top: 15px;font-size: 14px;line-height: 24px;">打款金额：<strong style="color:#E22C08;">￥'.$params['total_price'].'</strong></p>';

            // 备注
            $html .= '<p style="margin-top: 5px;font-size: 14px;line-height: 24px;">打款备注：<strong style="color:#2196f3;">'.$params['order_no'].'</strong></p>';

            // 订单关闭提示
            $order_close_time = time()+((MyC('common_order_close_limit_time', 30, true)-5)*60);
            $html .= '<div style="margin-top: 15px;"><p style="color:#f89703;font-size: 14px;line-height: 24px;">订单预计[ <span style="color:#ff5722;">'.date('m月d号H点i分', $order_close_time).'</span> ]自动关闭、请尽快完成支付!</p></div>';

            // 特别提示文字
            if(!empty($this->config['tips']))
            {
                $html .= '<p class="tips" style="margin-top: 15px;font-size: 14px;background: #fff2df;border: 1px solid #ffeacc;color: #f99600;padding: 5px 10px;line-height: 22px;border-radius:'.$radius.'">'.$this->config['tips'].'</p>';
            }

            // 图片信息
            if(!empty($this->config['images_url']))
            {
                $html .= '<div style="margin-top: 15px;"><img src="'.$this->config['images_url'].'" alt="支付信息" style="width: 100%;border-radius: 2px;" /></div>';
            }

            // 导航入口
            if(APPLICATION == 'web')
            {
                $home_url = __MY_URL__;
                $order_url = MySession('payment_business_order_index_url');
                if(empty($order_url))
                {
                    $order_url = MyUrl('index/order/index');
                }
                $html .= '<div style="text-align: center;padding: 10px 0;margin-top:30px;"><a href="'.$home_url.'" style="text-decoration: none;background: #666;padding: 6px 12px;border-radius: 4px;color: #fff;background: #d2364c;font-size: 14px;">回到首页</a><a href="'.$order_url.'" style="text-decoration: none;background: #666;padding: 6px 12px;border-radius: 4px;color: #fff;background: #4caf50;margin-left:50px;font-size: 14px;">进入我的订单</a></div>';
            }

            // 闭合
            $html .= '</div>';

            // app则返回固定错误码和html代码、返回固定错误码
            if(APPLICATION == 'app')
            {
                return DataReturn('success', -6666, $html);
            }

            // 表单html
            $parameter = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>支付信息</title></head><body style="color: #333;background: #f7f7f7;">'.$html.'</body></html>';

            // 支付请求记录
            PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

            // web端直接输出html
            die($parameter);
        }

        // 默认方式
        $parameter = $params['call_back_url'].'?';
        $parameter .= 'out_trade_no='.$params['order_no'];
        $parameter .= '&subject='.$params['name'];
        $parameter .= '&total_price='.$params['total_price'];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        return DataReturn('success', 0, $parameter);
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
        return DataReturn('success', 0, $params);
    }
}
?>