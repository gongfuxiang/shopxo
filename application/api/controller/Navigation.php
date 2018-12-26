<?php
namespace app\api\controller;

use app\service\AppNavService;

/**
 * 导航
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Navigation extends Common
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * [Index 入口]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-25T11:03:59+0800
     */
    public function Index()
    {
        // 获取轮播
        $data = AppNavService::AppHomeNav();

        // 返回数据
        return json(DataReturn('success', 0, $data));
    }
}
?>