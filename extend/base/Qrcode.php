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
namespace base;

/**
 * 二维码驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Qrcode
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:13:10+0800
     */
    public function __construct()
    {
        require_once ROOT.'extend'.DS.'qrcode'.DS.'phpqrcode.php';
    }

    /**
     * 二维码展示
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:13:16+0800
     * @param    [array]                    $params [输入参数]
     */
    public function View($params = [])
    {
        // 容错率
        $level = isset($params['level']) && in_array($params['level'], array('L','M','Q','H')) ? $params['level'] : 'L';

        // 大小，最小1，最大10
        $point_size = isset($params['size']) ? min(max(intval($params['size']), 1), 10) : 6;

        // 外边距
        $mr = isset($params['mr']) ? intval($params['mr']) : 1;

        // 内容
        $content = isset($params['content']) ? base64_decode(urldecode(trim($params['content']))) : __MY_URL__;

        // 生成二维码并输出页面显示
        \QRcode::png($content, false, $level, $point_size, $mr);
    }

    /**
     * 二维码下载
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:23:01+0800
     * @param    [array]                    $params [输入参数]
     */
    public function Download($params = [])
    {
        // 图片地址
        $url = base64_decode(urldecode($params['url']));

        // 随机文件名
        $filename = time().GetNumberCode().'.png';

        // 设置头信息
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        readfile($url);
    }
}
?>