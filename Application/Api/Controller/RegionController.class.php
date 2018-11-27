<?php

namespace Api\Controller;

/**
 * 地区
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class RegionController extends CommonController
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
     * 获取json数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-07
     * @desc    description
     */
    public function GetJson()
    {
        $this->ajaxReturn(L('common_operation_success'), 0);

        $data = $this->GetGetNodes(0);
        if(!empty($data))
        {
            foreach($data as &$province_v)
            {
                $province_v['items'] = $this->GetGetNodes($province_v['id']);
                if(!empty($province_v['items']))
                {
                    foreach($province_v['items'] as &$city_v)
                    {
                        $city_v['items'] = $this->GetGetNodes($city_v['id']);
                    }
                }
            }
        }
        print_r($data);
    }

    /**
     * 获取节点数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-07
     * @desc    description
     * @param   int         $pid [父级id]
     */
    private function GetGetNodes($pid = 0)
    {
        // 条件
        $where = $this->GetIndexWhere();
        $where['pid'] = $pid;

        // 获取数据
        $field = 'id,name,level,letters';
        return M('Region')->where($where)->field($field)->order('id asc, sort asc')->select();
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
        // 条件
        $where = $this->GetIndexWhere();

        // 获取数据
        $field = 'id,name';
        $data = M('Region')->where($where)->field($field)->order('id asc, sort asc')->select();
        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * [GetIndexWhere 条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-16T14:25:15+0800
     */
    private function GetIndexWhere()
    {
        // 基础条件
        $where = [
            'is_enable'     => 1,
            'pid'           => empty($this->data_post['pid']) ? 0 : intval($this->data_post['pid']),
        ];

        return $where;
    }
}
?>