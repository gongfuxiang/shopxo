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
        $this->config['root_path'] = isset($params['root_path']) ? $params['root_path'] : ROOT.'public';
        $this->config['path'] = isset($params['path']) ? $params['path'] : DS.'static'.DS.'upload'.DS.'file'.DS.date('Y').DS.date('m').DS.date('d').DS;
    }

    /**
     * 文件存储
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $name [表单name]
     * @param   [int]        $index[多文件索引]
     * @return  [mixed]            [array | 错误信息]
     */
    function Save($name, $index = false)
    {
        // 基础校验
        $error = FileUploadError($name, $index);
        if($error !== true)
        {
            return ['status'=>false, 'msg'=>$error];
        }

        // 存储目录校验
        $dir = str_replace(['//', '\\\\'], ['/', '\\'], $this->config['root_path'].$this->config['path']);
        $this->IsMkdir($dir);

        // 临时文件数据
        if($index === false)
        {
            $original_name = $_FILES[$name]['name'];
            $temp_file = $_FILES[$name]['tmp_name'];
            $size = $_FILES[$name]['size'];
            $type = $_FILES[$name]['type'];
        } else {
            $original_name = $_FILES[$name]['name'][$index];
            $temp_file = $_FILES[$name]['tmp_name'][$index];
            $size = $_FILES[$name]['size'][$index];
            $type = $_FILES[$name]['type'][$index];
        }
        $ext_all = explode('.', $original_name);
        $ext = $ext_all[count($ext_all)-1];

        // 生成新的文件名称
        $filename = $this->RandNewFilename().'.'.$ext;

        // 存储
        if(move_uploaded_file($temp_file, $dir.$filename))
        {
            $data = [
                'name'          => $original_name,
                'url'           => $this->config['path'].$filename,
                'file_path'     => $this->config['path'],
                'file_name'     => $filename,
                'file_ext'      => $ext,
                'file_size'     => $size,
                'file_type'     => $type,
                'file_hash'     => hash_file('sha256', $dir.$filename, false),
            ];
            return ['status'=>true, 'msg'=>'上传成功', 'data'=>$data];
        }
        return ['status'=>false, 'msg'=>'文件存储失败'];
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
        return date('YmdHis').rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }
}
?>