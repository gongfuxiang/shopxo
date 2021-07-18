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
namespace app\index\controller;

/**
 * 二维码生成控制层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class QrCode extends Common
{
    /**
     * [__construct 构造方法]
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 二维码显示
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:52:09+0800
     */
    public function Index()
    {
        $params = input();
        if(empty($params['content']))
        {
            MyViewAssign('msg', '内容参数为空');
            return MyView('public/tips_error');
        }

        (new \base\Qrcode())->View($params);
    }

    /**
     * 二维码下载
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:52:18+0800
     */
    public function Download()
    {
        $params = input();
        $ret = (new \base\Qrcode())->Download($params);
        if(!empty($ret) && isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
    }
}
?>