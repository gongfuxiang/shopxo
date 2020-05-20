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
namespace app\service;

use think\Db;

/**
 * 商品表单
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class GoodsForm
{
    /**
     * 表单
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Table($params = [])
    {
        $data = [
            [
                // 标题名称
                'label'         => '商品信息',
                // 展示数据类型(field 字段取值, file 文件引入内容, status 状态操作, )
                'view_type'     => 'field',
                // 展示数据的 key名称
                'view_key'      => 'title',
                // 内容位置(left 居左, center 居中, right 居右)默认 left
                'align'         => 'left',
                // 格子大小(lg 350px, sm 200px, xs 150px)默认空(100px)
                'grid_size'     => 'lg',
            ],
        ];
    }

    /**
     * 条件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Search($params = [])
    {

    }
}