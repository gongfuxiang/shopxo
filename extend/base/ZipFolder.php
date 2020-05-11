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
 * 压缩包驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2018-12-01T21:51:08+0800
 */
class ZipFolder
{
    protected $zip;
    protected $root;
    protected $ignored_names;

    /**
     * 构造函数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-11-28T00:43:18+0800
     */
    public function __construct()
    {
        $this->zip = new \ZipArchive();
    }

    /**
     * 解压zip文件到指定文件夹
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $zipfile       [压缩文件路径]
     * @param   [string]     $path          [压缩包解压到的目标路径]
     * @return  [boolean]                   [true | false]
     */
    public function Unzip($zipfile, $path)
    {
        if($this->zip->open($zipfile) === true)
        {
            $file_tmp = @fopen($zipfile, "rb");
            $bin = fread($file_tmp, 15); //只读15字节 各个不同文件类型，头信息不一样。
            fclose($file_tmp);
            /* 只针对zip的压缩包进行处理 */
            if(true === $this->GetTypeList($bin))
            {
                $result = $this->zip->extractTo($path);
                $this->zip->close();
                return $result;
            }
        }
        return false;
    }

    /**
     * 创建压缩文件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $zipfile       [将要生成的压缩文件路径]
     * @param   [string]     $folder        [将要被压缩的文件夹路径]
     * @param   [string]     $ignored       [要忽略的文件列表]
     * @return  [boolean]                   [true | false]
     */
    public function Zip($zipfile, $folder, $ignored = null)
    {
        $this->ignored_names = is_array($ignored) ? $ignored : ($ignored ? array($ignored) : array());
        if($this->zip->open($zipfile, \ZipArchive::CREATE) !== true)
        {
            throw new Exception("cannot open <$zipfile>\n");
        }
        $folder = substr($folder, -1) == '/' ? substr($folder, 0, strlen($folder)-1) : $folder;
        if(strstr($folder, '/'))
        {
            $this->root = substr($folder, 0, strrpos($folder, '/')+1);
            $folder = substr($folder, strrpos($folder, '/')+1);
        }
        $this->CreateZip($folder);
        return $this->zip->close();
    }

    /**
     * 递归添加文件到压缩包
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $folder       [添加到压缩包的文件夹路径]
     * @param   [string]     $parent       [添加到压缩包的文件夹上级路径]
     */
    private function CreateZip($folder, $parent = null)
    {
        $full_path = $this->root . $parent . $folder;
        $zip_path = $parent . $folder;
        $this->zip->addEmptyDir($zip_path);
        $dir = new \DirectoryIterator($full_path);
        foreach($dir as $file)
        {
            if(!$file->isDot())
            {
                $filename = $file->getFilename();
                if(!in_array($filename, $this->ignored_names))
                {
                    if($file->isDir())
                    {
                    $this->CreateZip($filename, $zip_path.'/');
                    } else {
                        $this->zip->addFile($full_path.'/'.$filename, $zip_path.'/'.$filename);
                    }
                }
            }
        }
    }

    /**
     * 读取压缩包文件与目录列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $zipfile       [压缩包文件]
     * @return  [array]                     [文件与目录列表]
     */
    public function FileList($zipfile)
    {
        $file_dir_list = array();
        $file_list = array();
        if($this->zip->open($zipfile) == true)
        {
            for ($i = 0; $i < $this->zip->numFiles; $i++)
            {
                $numfiles = $this->zip->getNameIndex($i);
                if(preg_match('/\/$/i', $numfiles))
                {
                    $file_dir_list[] = $numfiles;
                } else {
                    $file_list[] = $numfiles;
                }
            }
        }
        return array('files'=>$file_list, 'dirs'=>$file_dir_list);
    }
    
    /**
     * 得到文件头与文件类型映射表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-29
     * @desc    description
     * @param   [string]     $bin       [文件的二进制前一段字符]
     * @return  [boolean]                   [true | false]
     */
    private function GetTypeList($bin)
    {
        $array = array(
            array("504B0304", "zip")
        );
        foreach($array as $v)
        {
            $blen = strlen(pack("H*", $v[0])); //得到文件头标记字节数
            $tbin = substr($bin, 0, intval($blen)); ///需要比较文件头长度
            if(strtolower($v[0]) == strtolower(array_shift(unpack("H*", $tbin))))
            {
                return true;
            }
        }
        return false;
    }
}
?>