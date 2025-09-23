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
namespace app\api\controller;

use app\api\controller\Base;
use app\service\ApiService;
use app\service\AttachmentApiService;

/**
 * 附件api接口
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class AttachmentApi extends Base
{
    /**
     * 附件列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function List()
    {
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleStructReturn($this->form_table_data, 'page_struct')));
    }

    /**
     * 附件分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Category()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::Category($this->data_request));
    }

    /**
     * 附件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::Save($this->data_request));
    }

    /**
     * 附件删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::Delete($this->data_request));
    }

    /**
     * 附件上传
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Upload()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::Upload($this->data_request));
    }

    /**
     * 远程下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Catch()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::Catch($this->data_request));
    }

    /**
     * 附件扫码上传数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function ScanUploadData()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::ScanUploadData($this->data_request));
    }

    /**
     * 附件移动分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function MoveCategory()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::MoveCategory($this->data_request));
    }

    /**
     * 附件分类保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function CategorySave()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::CategorySave($this->data_request));
    }

    /**
     * 附件分类删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function CategoryDelete()
    {
        return ApiService::ApiDataReturn(AttachmentApiService::CategoryDelete($this->data_request));
    }
}
?>