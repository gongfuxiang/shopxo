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
use app\service\SystemBaseService;
use app\service\FormInputService;

/**
 * form表单
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class FormInput extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

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
        if(!empty($this->data_request['id']))
        {
            // 获取diy数据
            $data = FormInputService::FormInputData($this->data_request);

            // 返回数据
            $result = SystemBaseService::DataReturn([
                'data'  => $data
            ]);

            // 访问统计
            if(!empty($result['data']) && !empty($result['data']['data']))
            {
                FormInputService::FormInputAccessCountInc(['forminput_id'=>$result['data']['data']['id']]);
            }
            return ApiService::ApiDataReturn($result);
        }
        return ApiService::ApiDataReturn(DataReturn(MyLang('no_data'), -1));
    }

    /**
     * 邮箱、短信验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function VerifySend()
    {
        return ApiService::ApiDataReturn(FormInputService::VerifySend($this->data_request));
    }

    /**
     * 图片验证码显示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function VerifyEntry()
    {
        $params = [
                'width'         => 100,
                'height'        => 28,
                'key_prefix'    => input('type', 'forminput'),
                'expire_time'   => MyC('common_verify_expire_time'),
            ];
        $verify = new \base\Verify($params);
        $verify->Entry();
    }
}
?>