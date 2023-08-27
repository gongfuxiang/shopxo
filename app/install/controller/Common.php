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
namespace app\install\controller;

use app\BaseController;
use app\service\SystemService;
use app\service\SystemBaseService;
use app\service\MultilingualService;

/**
 * 安装程序-公共
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-11-30
 * @desc    description
 */
class Common extends BaseController
{
    // 输入参数
    protected $data_request;

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
        // 输入参数
        $this->data_request = input();

        // 系统运行开始
        SystemService::SystemBegin($this->data_request);

        // 模板数据
        $assign = [
            // 当前方法
            'action'                     => RequestAction(),
            
            // 系统类型
            'system_type'                => SystemService::SystemTypeValue(),
            
            // 系统环境参数最大数
            'env_max_input_vars_count'   => SystemService::EnvMaxInputVarsCount(),
            
            // 默认不加载地图api、类型默认百度地图
            'is_load_map_api'            => 0,
            
            'load_map_type'              => MyC('common_map_type', 'baidu', true),
            
            // 页面语言
            'lang_data'                  => SystemService::PageViewLangData(),
            
            // 多语言
            'multilingual_default_code'  => MultilingualService::GetUserMultilingualValue(),
            
            // 附件host地址
            'attachment_host'            => SystemBaseService::AttachmentHost(),
            
            // css/js引入host地址
            'public_host'                => MyConfig('shopxo.public_host'),
            
            // 当前url地址
            'my_domain'                  => __MY_DOMAIN__,
            
            // 当前host地址
            'my_host'                    => __MY_HOST__,
            
            // 当前完整url地址
            'my_url'                     => __MY_URL__,
            
            // 项目public目录URL地址
            'my_public_url'              => __MY_PUBLIC_URL__,
            
            // 当前http类型
            'my_http'                    => __MY_HTTP__,
        ];
        MyViewAssign($assign);
    }

    /**
     * 析构函数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     */
    public function __destruct()
    {
        // 系统运行结束
        SystemService::SystemEnd($this->data_request);
    }

    /**
     * 空方法响应
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     * @param   [string]         $method [方法名称]
     * @param   [array]          $args   [参数]
     */
    public function __call($method, $args)
    {
        if(IS_AJAX)
        {
            return DataReturn($method.' 非法访问', -1000);
        } else {
            MyViewAssign('msg', $method.' 非法访问');
            return MyView('public/error');
        }
    }
}
?>