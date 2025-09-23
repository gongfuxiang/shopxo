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
use app\service\FormInputApiService;

/**
 * form表单接口
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class FormInputApi extends Base
{
    /**
     * 公共初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Init()
    {
        return ApiService::ApiDataReturn(FormInputApiService::Init($this->data_request));
    }

    /**
     * form表单详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function FormInputDetail()
    {
        return ApiService::ApiDataReturn(FormInputApiService::FormInputDetail($this->data_request));
    }

    /**
     * form表单保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function FormInputSave()
    {
        return ApiService::ApiDataReturn(FormInputApiService::FormInputSave($this->data_request));
    }

    /**
     * form表单导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function FormInputUpload()
    {
        return ApiService::ApiDataReturn(FormInputApiService::FormInputUpload($this->data_request));
    }

    /**
     * form表单导出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function FormInputDownload()
    {
        $ret = FormInputApiService::FormInputDownload($this->data_request);
        if($ret['code'] != 0)
        {
            return MyView('public/tips_error', ['msg'=>$ret['msg']]);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * form表单模板安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function FormInputInstall()
    {
        return ApiService::ApiDataReturn(FormInputApiService::FormInputInstall($this->data_request));
    }

    /**
     * form表单模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function FormInputMarket()
    {
        return ApiService::ApiDataReturn(FormInputApiService::FormInputMarket($this->data_request));
    }
}
?>