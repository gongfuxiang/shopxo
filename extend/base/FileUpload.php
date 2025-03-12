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

/**
 * 文件上传
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-28
 * @desc    支持所有文件存储到硬盘
 */
class FileUpload
{
    // 配置
    private $config;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        $this->config['dir'] = isset($params['dir']) ? $params['dir'] : ROOT.'public';
        $this->config['path'] = isset($params['path']) ? $params['path'] : DS.'static'.DS.'upload'.DS.'file'.DS.date('Y').DS.date('m').DS.date('d').DS;
    }

    /**
     * 文件存储
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $file      [表单name]
     * @param   [string]     $name      [文件名称]
     * @param   [int]        $index     [多文件索引]
     * @param   [array]      $params    [输入参数]
     * @return  [mixed]                 [array | 错误信息]
     */
    function Save($file, $name = '', $index = false, $params = [])
    {
        // 基础校验
        $error = FileUploadError($file, $index);
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 存储目录校验
        $dir = str_replace(['//', '\\\\'], ['/', '\\'], $this->config['dir'].$this->config['path']);
        $this->IsMkdir($dir);

        // 临时文件数据
        if($index === false)
        {
            $original_name = $_FILES[$file]['name'];
            $temp_file = $_FILES[$file]['tmp_name'];
            $size = $_FILES[$file]['size'];
            $type = $_FILES[$file]['type'];
        } else {
            $original_name = $_FILES[$file]['name'][$index];
            $temp_file = $_FILES[$file]['tmp_name'][$index];
            $size = $_FILES[$file]['size'][$index];
            $type = $_FILES[$file]['type'][$index];
        }

        $info = getimagesize($temp_file);
        if(!empty($info) && !empty($info['mime']))
        {
            // 文件原始为空泽使用类型作为名称
            if(empty($original_name) && stripos($original_name, '.') === false)
            {
                $original_name .= str_replace('/', '.', $info['mime']);
            }
            // 是否php文件类型
            if($info['mime'] == 'text/x-php')
            {
                return DataReturn(MyLang('common_extend.base.fileupload.file_illegal_tips'), -1);
            }
        }

        // 安全验证
        if(!isset($params['is_security_check']) || $params['is_security_check'] == 1)
        {
            $ret = \base\FileUtil::FileContentSecurityCheck($temp_file);
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 后缀名称
        $ext_all = explode('.', $original_name);
        $ext = $ext_all[count($ext_all)-1];

        // 文件名称，未指定则生成新的文件名称
        $filename = empty($name) ? $this->RandNewFilename() : $name;
        if(stripos($filename, '.') === false)
        {
            $filename .= '.'.$ext;
        }

        // 存储
        if(move_uploaded_file($temp_file, $dir.$filename))
        {
            $data = [
                'title' => $original_name,
                'url'   => $this->config['path'].$filename,
                'path'  => $this->config['path'],
                'name'  => $filename,
                'ext'   => $ext,
                'size'  => $size,
                'type'  => $type,
                'hash'  => hash_file('sha256', $dir.$filename, false),
                'md5'   => md5_file($dir.$filename),
            ];
            return DataReturn(MyLang('upload_success'), 0, $data);
        }
        return DataReturn(MyLang('upload_fail'), -1);
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
            mkdir($dir, 0777, true);
        }
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
        return date('YmdHis').GetNumberCode();
    }
}
?>