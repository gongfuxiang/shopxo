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
use app\service\CustomViewService;
use app\service\SystemBaseService;
use app\service\ResourcesService;

/**
 * 自定义页面
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class CustomView extends Common
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
        $data = null;
        if(!empty($this->data_request['id']))
        {
            $data_params = [
                'where' => [
                    'id' => intval($this->data_request['id']),
                ],
                'm' => 0,
                'n' => 1,
            ];
            $ret = CustomViewService::CustomViewList($data_params);
            if($ret['code'] == 0 && !empty($ret['data']) && !empty($ret['data'][0]))
            {
                $data = $ret['data'][0];

                // 标签处理，兼容小程序rich-text
                $data['html_content'] = ResourcesService::ApMiniRichTextContentHandle($data['html_content']);

                // 访问统计
                CustomViewService::CustomViewAccessCountInc(['id'=>$data['id']]);
            }
        }

        // 返回数据
        $result = [
            'data'  => $data,
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }
}
?>