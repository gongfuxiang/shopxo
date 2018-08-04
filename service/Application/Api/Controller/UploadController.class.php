<?php

namespace Api\Controller;

/**
 * 用户
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class UploadController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }
    }

    /**
     * 图片上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-07
     * @desc    description
     */
    public function Images()
    {
        if(!empty($_FILES['file']))
        {
            // 文件上传校验
            $error = FileUploadError('file');
            if($error !== true)
            {
                $this->ajaxReturn($error, -1);
            }

            // 文件类型
            list($type, $suffix) = explode('/', $_FILES['file']['type']);
            if(empty($suffix))
            {
                $suffix = 'jpg';
            }
            $path = 'Public'.DS.'Upload'.DS.'common'.DS.date('Y').DS.date('m').DS.date('d').DS;
            if(!is_dir($path))
            {
                mkdir(ROOT_PATH.$path, 0777, true);
            }
            $filename = date('YmdHis').GetNumberCode(6).'.'.$suffix;
            $file = $path.$filename;

            if(move_uploaded_file($_FILES['file']['tmp_name'], ROOT_PATH.$file))
            {
                $data = [
                    'url'   => C('IMAGE_HOST').DS.$file,
                    'path'  => DS.$file,
                ];
                $this->ajaxReturn(L('common_upload_success'), 0, $data);
            }
            $this->ajaxReturn(L('common_upload_error'));
        }
        $this->ajaxReturn(L('common_param_error'));
    }
}
?>