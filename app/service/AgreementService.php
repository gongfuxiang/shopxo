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
namespace app\service;

use app\service\ConfigService;

/**
 * 协议服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-07-25
 * @desc    description
 */
class AgreementService
{
    /**
     * 协议数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-25
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AgreementData($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'document',
                'error_msg'         => MyLang('common_service.agreement.form_item_document_type_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取内容
        return ConfigService::ConfigContentRow('common_agreement_'.$params['document']);
    }
}
?>