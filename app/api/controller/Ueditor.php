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

use app\service\ApiService;
use app\service\UeditorService;
use app\service\AttachmentUploadLimitService;

/**
 * 附件上传
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Ueditor extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 上传必须登录关闭时，仅开放上传和配置；列表、删除、远程抓取仍需登录
        $action = (!empty($this->data_request['action']) && is_string($this->data_request['action'])) ? $this->data_request['action'] : '';
        if(AttachmentUploadLimitService::LoginRequired() || !AttachmentUploadLimitService::IsOpenAction($action))
        {
            $this->IsLogin();
        }
    }

    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-06
     * @desc    description
     */
    public function Index()
    {
        $action = empty($this->data_request['action']) ? 'config' : $this->data_request['action'];
        if($action === 'config' && function_exists('session_write_close'))
        {
            session_write_close();
        }
        return ApiService::ApiDataReturn(UeditorService::Run($this->data_request));
    }
}
?>
