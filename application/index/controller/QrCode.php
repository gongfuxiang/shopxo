<?php
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
        
        $level = isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')) ? $_REQUEST['level'] : 'L';
        $point_size = isset($_REQUEST['size']) ? min(max(intval($_REQUEST['size']), 1), 10) : 6;
        $mr = isset($_REQUEST['mr']) ? intval($_REQUEST['mr']) : 1;
        $content = isset($_REQUEST['content']) ? urldecode(trim($_REQUEST['content'])) : __MY_URL__;
        \QRcode::png($content, false, $level, $point_size, $mr);
    }
}
?>