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
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\AttachmentCategoryService;

/**
 * 附件分类管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class AttachmentCategory extends Base
{
    /**
     * 列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Index()
    {
        return MyView();
    }

    /**
     * 获取节点子列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function GetNodeSon()
    {
        return ApiService::ApiDataReturn(AttachmentCategoryService::AttachmentCategoryNodeSon($this->data_request));
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Save()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return ApiService::ApiDataReturn(AttachmentCategoryService::AttachmentCategorySave($params));
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-31
     * @desc    description
     */
    public function StatusUpdate()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return ApiService::ApiDataReturn(AttachmentCategoryService::AttachmentCategoryStatusUpdate($params));
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        return ApiService::ApiDataReturn(AttachmentCategoryService::AttachmentCategoryDelete($params));
    }
}
?>