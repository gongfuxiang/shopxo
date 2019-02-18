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
     * [Index 首页方法]
     */
    public function Index()
    {
        require_once ROOT.'extend'.DS.'qrcode'.DS.'phpqrcode.php';
        
        $params = input();
        $level = isset($params['level']) && in_array($params['level'], array('L','M','Q','H')) ? $params['level'] : 'L';
        $point_size = isset($params['size']) ? min(max(intval($params['size']), 1), 10) : 6;
        $mr = isset($params['mr']) ? intval($params['mr']) : 1;
        $content = isset($params['content']) ? base64_decode(urldecode(trim($params['content']))) : __MY_URL__;
        \QRcode::png($content, false, $level, $point_size, $mr);
    }
}
?>