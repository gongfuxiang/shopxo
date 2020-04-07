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
    // 配置
    private $config;

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
     * @param    [array]                    $params [输入参数]
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
     * @param    [array]                    $params [输入参数]
     */
    public function Create($params = [])
    {
        // 数据参数
        if(empty($params['content']))
        {
            return DataReturn('内容不能为空', -1);
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
            return DataReturn('二维码创建失败', -100);
        }
        
        //判断是否生成带logo的二维码
        if(!empty($params['logo']))
        {
            $logo = @file_get_contents($params['logo']);
            if($logo !== false)
            {            
                $qr = imagecreatefromstring(file_get_contents($dir.$filename));     //目标图象连接资源
                $logo = imagecreatefromstring($logo);                               //源图象连接资源
                
                $qr_width = imagesx($qr);
                $qr_height = imagesy($qr);
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $logo_qr_width = $qr_width / 5;                     //组合之后logo的宽度(占二维码的1/5)
                $scale = $logo_width/$logo_qr_width;                //logo的宽度缩放比(本身宽度/组合后的宽度)
                $logo_qr_height = $logo_height/$scale;              //组合之后logo的高度
                $from_width = ($qr_width - $logo_qr_width) / 2;     //组合之后logo左上角所在坐标点
                
                //重新组合图片并调整大小
                imagecopyresampled($qr, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
                
                //输出图片
                imagepng($qr, $dir.$filename);
                imagedestroy($qr);
                imagedestroy($logo);
            }
        }
        
        $result = [
            'dir'       => $dir.$filename,
            'root'      => $this->config['root_path'],
            'path'      => $this->config['path'],
            'filename'  => $filename,
        ];
        return DataReturn('创建成功', 0, $result);
    
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
        $filename = empty($params['filename']) ? date('YmdHis').GetNumberCode().'.png' : $params['filename'].'.png';

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
                return DataReturn('目录创建失败', -1);
            }
        }
        return DataReturn('操作成功', 0);
    }
}
?>