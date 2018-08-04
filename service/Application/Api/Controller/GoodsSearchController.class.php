<?php

namespace Api\Controller;

/**
 * 商品搜索
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsSearchController extends CommonController
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
     * 商品搜索
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-12
     * @desc    description
     */
    public function Index()
    {
        $m = M('Goods');

        // 获取数据
        $number = 10;
        $page = intval(I('page', 1));
        $where = $this->GetIndexWhere();
        $total = $m->alias('g')->join('INNER JOIN __GOODS_CATEGORY_JOIN__ AS gcj ON g.id=gcj.goods_id INNER JOIN __GOODS_CATEGORY__ AS gc ON gcj.category_id=gc.id')->where($where)->count('DISTINCT g.id');
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);
        $field = 'g.id, g.title, g.title_color, g.original_price, g.price, g.images, g.buy_min_number, g.buy_max_number, g.inventory, g.access_count, g.give_integral, gc.name AS category_name';
        $data = $m->alias('g')->join('INNER JOIN __GOODS_CATEGORY_JOIN__ AS gcj ON g.id=gcj.goods_id INNER JOIN __GOODS_CATEGORY__ AS gc ON gcj.category_id=gc.id')->where($where)->field($field)->limit($start, $number)->group('g.id')->order('g.id desc')->select();

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $this->SetGoodsData($data),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * 数据设置
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-13T23:08:38+0800
     * @param    [array]       $data [数据]
     */
    private function SetGoodsData($data)
    {
        if(!empty($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];
            }
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 列表条件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:13:32+0800
     */
    private function GetIndexWhere()
    {
        $where = array(
            'g.is_delete_time'      => 0,
            'g.is_shelves'          => 1,
        );

        // 分类
        if(!empty($this->data_post['category_id']))
        {
            $category = M('GoodsCategory')->where(['pid'=>intval($this->data_post['category_id']), 'is_enable'=>1])->getField('id', true);
            if(empty($category))
            {
                $category = [intval($this->data_post['category_id'])];
            }
            $where['gc.id'] = ['in', $category];
        }

        return $where;
    }
}
?>