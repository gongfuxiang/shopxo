<?php

namespace Api\Controller;

/**
 * 用户商品收藏
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserGoodsFavorController extends CommonController
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

        // 登录校验
        $this->Is_Login();
    }

    /**
     * [Index 获取列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $m = M('GoodsFavor');

        // 获取组织数据
        $number = 10;
        $page = intval(I('page', 1));
        $where = ['gf.user_id' => $this->user['id'], 'g.is_delete_time'=>0];
        $total = $m->alias('gf')->join('INNER JOIN __GOODS__ AS g ON gf.goods_id=g.id')->where($where)->count();
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);
        $field = 'g.id AS goods_id,g.title,g.title_color,g.original_price,g.price,g.images,gf.id,gf.add_time AS favor_time';
        $data = $m->alias('gf')->join('INNER JOIN __GOODS__ AS g ON gf.goods_id=g.id')->where($where)->field($field)->limit($start, $number)->order('gf.id desc')->select();

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $this->SetDataList($data),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * [SetDataList 数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-17T17:55:36+0800
     * @param    [array]      $data     [组织数据]
     * @return   [array]                [处理好的数据]
     */
    private function SetDataList($data)
    {
        if(!empty($data))
        {
            $image_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                // 收藏时间
                $v['favor_time'] = date('Y-m-d H:i:s', $v['favor_time']);

                // 商品图片
                $v['images'] = empty($v['images']) ? null : $image_host.$v['images'];
            }
        }
        return $data;
    }

    /**
     * 用户商品收藏取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     */
    public function Cancel()
    {
        // 参数校验
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '参数ID有误',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 开始操作
        $m = M('GoodsFavor');
        $where = ['id'=>intval($this->data_post['id']), 'user_id'=>$this->user['id']];
        if($m->where($where)->delete() !== false)
        {
            $this->ajaxReturn(L('common_cancel_success'), 0);
        } else {
            $this->ajaxReturn(L('common_cancel_error'));
        }
    }
}
?>