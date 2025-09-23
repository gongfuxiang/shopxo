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
use app\service\FormInputService;
use app\service\StoreService;

/**
 * form表单
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class FormInput extends Base
{
    /**
     * 首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Index()
    {
        // 页面类型
        $view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];
        // 应用商店
        MyViewAssign('store_forminput_url', StoreService::StoreFormInputUrl());
        return MyView($view_type);
    }

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Detail()
    {
        return MyView();
    }

    /**
     * 预览
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Preview()
    {
        MyViewAssign('data', FormInputService::FormInputPreviewData($this->data_detail));
        return MyView();
    }

    /**
     * 编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function SaveInfo()
    {
        $data = $this->data_detail;
        if(empty($data))
        {
            $is_auto_create = isset($this->data_request['is_auto_create']) ? intval($this->data_request['is_auto_create']) : 1;
            if($is_auto_create == 1)
            {
                $ret = FormInputService::FormInputSave();
                if($ret['code'] == 0)
                {
                    return MyRedirect(MyUrl('admin/forminput/saveinfo', ['id'=>$ret['data']]));
                }
                return MyView('public/tips_error', ['msg'=>$ret['msg']]);
            }
        } else {
            if(!empty($data['md5_key']))
            {
                $ret = FormInputService::FormInputLegalCheck($data['md5_key'], $data);
                if($ret['code'] != 0 && $ret['code'] != -300)
                {
                    return MyView('public/tips_error', ['msg'=>$ret['msg']]);
                }
            }
        }
        return MyView();
    }

    /**
     * 上传页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function StoreUploadInfo()
    {
        MyViewAssign(['choice_system_new_version'=>APPLICATION_VERSION]);
        return MyView();
    }

    /**
     * 下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     */
    public function Download()
    {
        $ret = FormInputService::FormInputDownload($this->data_request);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(FormInputService::FormInputSave($this->data_request));
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
        return ApiService::ApiDataReturn(FormInputService::FormInputStatusUpdate($this->data_request));
    }
    
    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-31
     * @desc    description
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(FormInputService::FormInputDelete($this->data_request));
    }

    /**
     * 上传到商店
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function StoreUpload()
    {
        return ApiService::ApiDataReturn(FormInputService::FormInputStoreUpload($this->data_request));
    }

    /**
     * 导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Upload()
    {
        return ApiService::ApiDataReturn(FormInputService::FormInputUpload($this->data_request));
    }

    /**
     * 模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Market()
    {
        $ret = FormInputService::FormInputMarket($this->data_request);
        if($ret['code'] == 0 && isset($ret['data']['data_list']))
        {
            $ret['data']['data_list'] = MyView('public/market/list', ['data_list'=>$ret['data']['data_list']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>