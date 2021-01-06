<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\service\BaseService;

/**
 * 基础公共接口
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-12
 * @desc    description
 */
class Base extends Common
{
    /**
     * 配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-12
     * @desc    description
     */
    public function Common()
    {
        // 参数
        $params = $this->data_post;
        $params['user'] = $this->user;
        return BaseService::Common($params);
    }
}
?>