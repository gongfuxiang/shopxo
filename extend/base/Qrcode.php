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
namespace base;

use app\service\ResourcesService;
use app\service\SystemBaseService;

/**
 * 二维码驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Qrcode
{
    // 配置
    private $config;

    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @param    [array]       $params [输入参数]
     * @datetime 2019-04-16T21:13:10+0800
     */
    public function __construct($params = [])
    {
        require_once ROOT.'extend'.DS.'qrcode'.DS.'phpqrcode.php';

        // 默认配置
        $this->config['root_path'] = isset($params['root_path']) ? $params['root_path'] : ROOT.'public';
        $this->config['path'] = isset($params['path']) ? $params['path'] : DS.'static'.DS.'upload'.DS.'images'.DS.'qrcode'.DS.date('Y').DS.date('m').DS.date('d').DS;
    }

    /**
     * 二维码展示
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:13:16+0800
     * @param    [array]       $params [输入参数]
     */
    public function View($params = [])
    {
        // 容错率
        $level = isset($params['level']) && in_array($params['level'], array('L','M','Q','H')) ? $params['level'] : 'L';

        // 大小，最小1，最大10
        $point_size = isset($params['size']) ? min(max(intval($params['size']), 1), 30) : 6;

        // 外边距
        $mr = isset($params['mr']) ? intval($params['mr']) : 1;

        // 内容
        $content = isset($params['content']) ? base64_decode(urldecode(trim($params['content']))) : __MY_URL__;

        // 生成二维码并输出页面显示
        if(ob_get_length() > 0)
        {
            ob_clean();
        }
        \QRcode::png($content, false, $level, $point_size, $mr);
        die;
    }

    /**
     * 二维码创建
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-19
     * @desc    description
     * @param    [array]       $params [输入参数]
     */
    public function Create($params = [])
    {
        // 数据参数
        if(empty($params['content']))
        {
            return DataReturn(MyLang('common_extend.base.qrcode.content_empty_tips'), -1);
        }

        // 自定义路径
        if(!empty($params['root_path']))
        {
            $this->config['root_path'] = $params['root_path'];
        }
        if(!empty($params['path']))
        {
            $this->config['path'] = $params['path'];
        }

        // 存储目录校验
        $dir = str_replace(['//', '\\\\'], ['/', '\\'], $this->config['root_path'].$this->config['path']);
        $ret = $this->IsMkdir($dir);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 文件名称
        $filename = empty($params['filename']) ? $this->RandNewFilename().'.png' : $params['filename'];

        // 是否已经存在、存在是否需要强制重新生成
        if(!file_exists($dir.$filename) || (isset($params['is_force']) && $params['is_force'] == 1))
        {
            // 容错率
            $level = isset($params['level']) && in_array($params['level'], array('L','M','Q','H')) ? $params['level'] : 'L';

            // 大小，最小1，最大10
            $point_size = isset($params['size']) ? min(max(intval($params['size']), 1), 30) : 10;

            // 外边距
            $mr = isset($params['mr']) ? intval($params['mr']) : 2;

            // 生成二维码
            \QRcode::png($params['content'], $dir.$filename, $level, $point_size, $mr);
            if(!file_exists($dir.$filename))
            {
                return DataReturn(MyLang('common_extend.base.qrcode.qrcode_create_fail_tips'), -100);
            }

            //判断是否生成带logo的二维码
            if(!empty($params['logo']))
            {
                $logo = @file_get_contents($params['logo']);
                if($logo !== false)
                {
                    //目标图象连接资源
                    $qr = imagecreatefromstring(file_get_contents($dir.$filename));
                    //源图象连接资源
                    $logo = imagecreatefromstring($logo);

                    $qr_width = intval(imagesx($qr));
                    $qr_height = intval(imagesy($qr));
                    $logo_width = intval(imagesx($logo));
                    $logo_height = intval(imagesy($logo));
                    //组合之后logo的宽度(占二维码的1/5)
                    $logo_qr_width = intval($qr_width / 5);
                    //logo的宽度缩放比(本身宽度/组合后的宽度)
                    $scale = intval($logo_width/$logo_qr_width);
                    //组合之后logo的高度
                    $logo_qr_height = intval($logo_height/$scale);
                    //组合之后logo左上角所在坐标点
                    $from_width = intval(($qr_width - $logo_qr_width) / 2);
                    
                    //重新组合图片并调整大小
                    imagecopyresampled($qr, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
                    
                    //输出图片
                    imagepng($qr, $dir.$filename);
                    imagedestroy($qr);
                    imagedestroy($logo);
                }
            }
        }

        $result = [
            'dir'       => $dir.$filename,
            'root'      => $this->config['root_path'],
            'path'      => $this->config['path'],
            'filename'  => $filename,
            'url'       => ResourcesService::AttachmentPathViewHandle($this->config['path'].$filename),
        ];
        return DataReturn(MyLang('operate_success'), 0, $result);
    }

    /**
     * 二维码下载
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-16T21:23:01+0800
     * @param    [array]       $params [输入参数]
     */
    public function Download($params = [])
    {
        // 图片地址
        $url = empty($params['url']) ? '' : base64_decode(urldecode($params['url']));
        if(empty($url))
        {
            return DataReturn(MyLang('common_extend.base.qrcode.url_empty_tips'), -1);
        }

        // 是否存在问号、去掉问号后面的参数
        $arr = explode('?', $url);
        if(count($arr) > 0)
        {
            $url = $arr[0];
        }

        // 文件是否存在
        $file = ROOT.'public'.ResourcesService::AttachmentPathHandle($url);
        if(!file_exists($file))
        {
            return DataReturn(MyLang('common_extend.base.qrcode.url_illegal_tips'), -1);
        }

        // 格式校验，希望仅下载图片文件
        $len = strripos($arr[0], '.');
        if($len === false)
        {
            return DataReturn(MyLang('common_extend.base.qrcode.url_invalid_tips'), -1);
        }

        // 防止存在锚点
        $ext_arr = MyConfig('ueditor.imageManagerAllowFiles');
        $ext = mb_substr($arr[0], $len, null, 'utf-8');
        $temp_ext = explode('#', $ext);
        if(!in_array($temp_ext[0], $ext_arr))
        {
            return DataReturn(MyLang('common_extend.base.qrcode.images_url_invalid_tips'), -1);
        }

        // 随机文件名
        $filename = empty($params['filename']) ? date('YmdHis').GetNumberCode().'.png' : $params['filename'].'.png';

        // 设置头信息
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header('Content-Type: application/octet-stream;charset=utf-8');
        header('Content-Type: application/download');
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        echo RequestGet($file);
    }

    /**
     * 生成新的文件名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @return  [string]          [文件名称]
     */
    private function RandNewFilename()
    {
        return date('YmdHis').rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }

    /**
     * 路径不存在则创建
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]          $dir  [文件路径]
     */
    private function IsMkdir($dir)
    {
        if(!is_dir($dir))
        {
            // 创建目录
            if(mkdir($dir, 0777, true) === false)
            {
                return DataReturn(MyLang('common_extend.base.qrcode.dir_create_fail_tips'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }
}
?>