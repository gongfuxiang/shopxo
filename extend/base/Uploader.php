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
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
class Uploader
{
    private $fileField; //文件域名
    private $file; //文件上传对象
    private $type; //类型
    private $base64; //文件上传对象
    private $config; //配置信息
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct($fileField, $config, $type = 'file')
    {
        $this->fileField = $fileField;
        $this->config = $config;
        $this->type = $type;
        switch($this->type)
        {
            // 抓取远程文件
            case 'remote' :
                $this->saveRemote();
                break;

            // base64文件
            case 'base64' :
            case 'scrawl' :
                $this->uploadBase64();
                break;

            // 图片
            case 'image' :
                $this->uploadImage();
                break;

            // 文件、视频
            case 'file' :
            case 'video' :
                $this->uploadFile();
                break;

            // 默认
            default :
                $this->stateInfo = $this->getStateErrorInfo('error_upload_type');
        }
    }

    /**
     * 文件上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-26
     * @desc    description
     */
    private function uploadFile()
    {
        if(empty($_FILES[$this->fileField]))
        {
            $this->stateInfo = $this->getStateErrorInfo('error_size_exceed');
            return;
        }
        
        $this->file = $_FILES[$this->fileField];
        if (!$this->file) {
            $this->stateInfo = $this->getStateErrorInfo('error_file_not_found');
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getFileErrorInfo($this->file['error']);
            return;
        } else if (!file_exists($this->file['tmp_name'])) {
            $this->stateInfo = $this->getStateErrorInfo('error_tmp_file_not_found');
            return;
        } else if (!is_uploaded_file($this->file['tmp_name'])) {
            $this->stateInfo = $this->getStateErrorInfo('error_tmp_file');
            return;
        }

        $this->oriName = $this->file['name'];
        $this->fileSize = $this->file['size'];
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateErrorInfo('error_size_exceed');
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateErrorInfo('error_type_not_allowed');
            return;
        }

        //创建目录失败
        if (!is_dir($dirname) && !@mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateErrorInfo('error_create_dir');
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateErrorInfo('error_dir_not_writeable');
            return;
        }

        // 安全验证
        $ret = \base\FileUtil::FileContentSecurityCheck($this->file['tmp_name']);
        if($ret['code'] != 0)
        {
            $this->stateInfo = $ret['msg'];
            return;
        }

        //移动文件
        if (!(move_uploaded_file($this->file['tmp_name'], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateErrorInfo('error_file_move');
        } else { //移动成功
            $this->stateInfo = 'SUCCESS';
        }
    }

    /**
     * 图片上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-26
     * @desc    description
     */
    private function uploadImage()
    {
        $this->file = empty($_FILES[$this->fileField]) ? '' : $_FILES[$this->fileField];
        if (!$this->file) {
            $this->stateInfo = $this->getStateErrorInfo('error_file_not_found');
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateErrorInfo($this->file['error']);
            return;
        } else if (!file_exists($this->file['tmp_name'])) {
            $this->stateInfo = $this->getStateErrorInfo('error_tmp_file_not_found');
            return;
        } else if (!is_uploaded_file($this->file['tmp_name'])) {
            $this->stateInfo = $this->getStateErrorInfo('error_tmp_file');
            return;
        }

        // 防止原名称没有带后缀
        $info = getimagesize($this->file['tmp_name']);
        if(stripos($this->file['name'], '.') === false)
        {
            $this->file['name'] .= str_replace('/', '.', $info['mime']);
        }

        $this->oriName = $this->file['name'];
        $this->fileSize = $this->file['size'];
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateErrorInfo('error_size_exceed');
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateErrorInfo('error_type_not_allowed');
            return;
        }

        //创建目录失败
        if (!is_dir($dirname) && !@mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateErrorInfo('error_create_dir');
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateErrorInfo('error_dir_not_writeable');
            return;
        }

        // 是否php文件类型
        if($info['mime'] == 'text/x-php')
        {
            $this->stateInfo = $this->getStateErrorInfo('invalid_file');
            return;
        }

        // 安全验证
        $ret = \base\FileUtil::FileContentSecurityCheck($this->file['tmp_name']);
        if($ret['code'] != 0)
        {
            $this->stateInfo = $ret['msg'];
            return;
        }

        // 是否需要直接存储文件
        if(!move_uploaded_file($this->file['tmp_name'], $this->filePath))
        {
            $this->stateInfo = $this->getStateErrorInfo('error_file_move');
        }

        // 图片是否存储成功
        if(!file_exists($this->filePath))
        {
            $this->stateInfo = $this->getStateErrorInfo('error_image_save');
        } else {
            $this->stateInfo = 'SUCCESS';
        }
    }

    /**
     * 处理base64编码的图片上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-26
     * @desc    description
     */
    private function uploadBase64()
    {
        $base64Data = empty($_POST[$this->fileField]) ? '' : $_POST[$this->fileField];
        $img = base64_decode($base64Data);

        $this->oriName = $this->config['oriName'];
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateErrorInfo('error_size_exceed');
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateErrorInfo('error_create_dir');
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateErrorInfo('error_dir_not_writeable');
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) {
            $this->stateInfo = $this->getStateErrorInfo('error_write_content');
        } else {
            $this->stateInfo = 'SUCCESS';
        }
    }

    /**
     * 拉取远程图片
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-26
     * @desc    description
     */
    private function saveRemote()
    {
        $remoteUrl = htmlspecialchars($this->fileField);
        $remoteUrl = str_replace('&amp;', '&', $remoteUrl);

        //检查是否不允许的文件格式
        $ext = explode('?', strtolower(strrchr($remoteUrl, '.')));
        if (!$this->checkType($ext[0])) {
            $this->stateInfo = $this->getStateErrorInfo('error_type_not_allowed');
            return;
        }

        //http开头验证
        if (strpos($remoteUrl, 'http') !== 0) {
            $this->stateInfo = $this->getStateErrorInfo('error_http_link');
            return;
        }

        preg_match('/(^https*:\/\/[^:\/]+)/', $remoteUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            $this->stateInfo = $this->getStateErrorInfo('invalid_url');
            return;
        }
        preg_match('/^https*:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 ip 也有可能是域名，先获取 ip
        $ip = gethostbyname($host_without_protocol);
        // 判断是否是私有 ip
        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            $this->stateInfo = $this->getStateErrorInfo('invalid_ip');
            return;
        }

        //打开输出缓冲区并获取远程文件
        $reponse = RequestGet($remoteUrl);
        if(empty($reponse)) {
            $this->stateInfo = $this->getStateErrorInfo('error_dead_link');
            return;
        }
        preg_match('/[\/]([^\/]*)[\.]?[^\.\/]*$/', $remoteUrl, $m);

        $this->oriName = $m ? $m[1]:"";
        $this->fileSize = strlen($reponse);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        // 安全验证
        $ret = \base\FileUtil::FileContentSecurityCheck($reponse, false);
        if($ret['code'] != 0)
        {
            $this->stateInfo = $ret['msg'];
            return;
        }

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateErrorInfo('error_size_exceed');
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateErrorInfo('error_create_dir');
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateErrorInfo('error_dir_not_writeable');
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $reponse) && file_exists($this->filePath))) {
            $this->stateInfo = $this->getStateErrorInfo('error_write_conent');
        } else {
            $this->stateInfo = 'SUCCESS';
        }
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower(strrchr($this->oriName, '.'));
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName()
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date('Y-y-m-d-H-i-s'));
        $format = $this->config['pathFormat'];
        $format = str_replace('{yyyy}', $d[0], $format);
        $format = str_replace('{yy}', $d[1], $format);
        $format = str_replace('{mm}', $d[2], $format);
        $format = str_replace('{dd}', $d[3], $format);
        $format = str_replace('{hh}', $d[4], $format);
        $format = str_replace('{ii}', $d[5], $format);
        $format = str_replace('{ss}', $d[6], $format);
        $format = str_replace('{time}', $t, $format);

        //过滤文件名的非法自负,并替换文件名
        $oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
        $oriName = preg_replace('/[\|\?\"\<\>\/\*\\\\]+/', '', $oriName);
        $format = str_replace('{filename}', $oriName, $format);

        //替换随机字符串
        $randNum = rand(1, 1000) . rand(1, 1000) . rand(1, 1000);
        if (preg_match('/\{rand\:([\d]*)\}/i', $format, $matches)) {
            $format = preg_replace('/\{rand\:[\d]*\}/i', substr($randNum, 0, $matches[1]), $format);
        }

        $ext = $this->getFileExt();
        return $format . $ext;
    }

    /**
     * 获取文件名
     * @return string
     */
    private function getFileName () {
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 获取文件完整路径
     * @return string
     */
    private function getFilePath()
    {
        $fullname = $this->fullName;
        $rootPath = GetDocumentRoot();

        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }

        return $rootPath . $fullname;
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType($ext = null)
    {
        $ext = empty($ext) ? $this->getFileExt() : $ext;
        return in_array($ext, $this->config['allowFiles']);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= $this->config['maxSize'];
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {
        return [
            'state'     => $this->stateInfo,
            'url'       => $this->fullName,
            'path'      => $this->filePath,
            'title'     => $this->fileName,
            'original'  => $this->oriName,
            'ext'       => $this->fileType,
            'size'      => $this->fileSize,
            'hash'      => (!empty($this->filePath) && file_exists($this->filePath)) ? hash_file('sha256', $this->filePath, false) : '',
        ];
    }

    /**
     * 文件错误信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-12
     * @desc    description
     * @param   [string]          $key [错误码]
     * @return  [string]               [错误信息]
     */
    public function getFileErrorInfo($key)
    {
        $file_error_list = MyConst('common_file_upload_error_list');
        return array_key_exists($key, $file_error_list) ? $file_error_list[$key] : MyLang('error');
    }

    /**
     * 状态错误信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-12
     * @desc    description
     * @param   [string]          $key [错误码]
     * @return  [string]               [错误信息]
     */
    public function getStateErrorInfo($key)
    {
        $data = MyLang('common_extend.base.uploader');
        return array_key_exists($key, $data) ? $data[$key] : MyLang('error');
    }
}
?>