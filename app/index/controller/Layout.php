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
namespace app\index\controller;

use app\layout\service\BaseLayout;

/**
 * 布局自动化
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Layout extends Common
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
        parent::__construct();
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-16
     * @desc    description
     */
    public function GoodsSearch()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 获取数据
        $params = $this->data_post;
        $params['user'] = $this->user;
        $ret = BaseLayout::GoodsSearchList($params);
        if($ret['code'] == 0)
        {
            MyViewAssign('data', $ret['data']['data']);
            $ret['data']['data'] = MyView('../../../layout/view/public/common/goodssearch');
        }
        return $ret;
    }

    /**
     * 商品数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-16
     * @desc    description
     */
    public function GoodsData()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 获取数据
        $params = $this->data_post;
        $params['user'] = $this->user;
        return BaseLayout::GoodsDataList($params);
    }
}
?>