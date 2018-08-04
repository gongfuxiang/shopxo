<?php

namespace Api\Controller;

/**
 * 资源
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ResourcesController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }
    }

    /**
     * [Express 获取快递]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:08:01+0800
     */
    public function Express()
    {
        // 条件
        $where = ['is_enable' => 1];
        // 获取数据
        $field = 'id,name';
        $data = M('Express')->where($where)->field($field)->order('id asc, sort asc')->select();
        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * [Goods 获取物品类型]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:08:01+0800
     */
    public function Goods()
    {
        // 条件
        $where = ['is_enable' => 1];
        // 获取数据
        $field = 'id,name';
        $data = M('Goods')->where($where)->field($field)->order('id asc, sort asc')->select();
        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * 购买服务介绍
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-12
     * @desc    客户端购买服务页面介绍
     */
    public function ServiceBuyInit()
    {
        $data = [
            'service_price'         => MyC('common_service_price'),
            'service_buy_desc'      => MyC('common_service_buy_desc'),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * 获取json数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-07
     * @desc    description
     */
    public function Shelves()
    {
        $data = $this->GetShelvesNodes('id,name');
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                unset($v['id']);
                $v['subList'] = $this->GetShelvesNodes('name', $v['id']);
            }
        }
          $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * 获取货架节点数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-07
     * @desc    description
     * @param   string      $field [制定返回字段]
     * @param   int         $pid   [父级id]
     */
    private function GetShelvesNodes($field, $pid = 0)
    {
        // 条件
        $where = [
            'is_enable'   => 1,
            'pid'         => intval($pid),
        ];

        // 获取数据
        return M('Shelves')->where($where)->field($field)->order('id asc, sort asc')->select();
    }

}
?>