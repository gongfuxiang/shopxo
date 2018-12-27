<?php
namespace app\api\controller;

use app\service\RegionService;

/**
 * 地区
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Region extends Common
{
    /**
     * [__construct 构造方法]
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
     * [Index 获取地区]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:08:01+0800
     */
    public function Index()
    {
        // 获取地区
        $params = [
            'where' => [
                'pid'   => isset($this->data_post['pid']) ? intval($this->data_post['pid']) : 0,
            ],
        ];
        $data = RegionService::RegionNode($params);
        return json(DataReturn('success', 0, $data));
    }
}
?>