<?php

namespace Api\Controller;

/**
 * 商品
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsController extends CommonController
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
     * 获取商品详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-12
     * @desc    description
     */
    public function Detail()
    {
        $goods_id = I('goods_id');
        $goods = M('Goods')->where(['id'=>$goods_id, 'is_shelves'=>1, 'is_delete_time'=>0])->find();
        if(empty($goods))
        {
            $this->ajaxReturn(L('common_data_no_exist_error'), -1);
        }
        $goods['images'] = empty($goods['images']) ? null : C('IMAGE_HOST').$goods['images'];
        unset($goods['content_web']);

        // 产地
        $goods['place_origin_name'] = GetRegionName($goods['place_origin']);

        // 是否已收藏
        $goods['is_favor'] = $this->IsGoodsUserFavor($goods_id);

        $result = [
            // 商品基础数据
            'goods'         => $goods,

            // 相册
            'photo'         => $this->GetGoodsPhoto($goods_id),

            // 手机详情
            'content_app'   => $this->GetGoodsContentApp($goods_id),

            // 属性
            'attribute'     => $this->GetGoodsAttribute($goods_id),
        ];

        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * 用户是否收藏商品
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @return  [int]                    [0,1]
     */
    private function IsGoodsUserFavor($goods_id)
    {
        if(empty($this->user['id']))
        {
            return 0;
        }

        $data = M('GoodsFavor')->where(['goods_id'=>$goods_id, 'user_id'=>$this->user['id']])->find();
        return empty($data) ? 0 : 1;
    }

    /**
     * 获取商品手机详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @return  [array]                  [属性]
     */
    private function GetGoodsContentApp($goods_id)
    {
        $data = M('GoodsContentApp')->where(['goods_id'=>$goods_id])->field('id,images,content')->order('sort asc')->select();
        if(!empty($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];
                $v['content'] = empty($v['content']) ? null : explode("\n", $v['content']);
            }
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 获取商品相册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @return  [array]                  [属性]
     */
    private function GetGoodsPhoto($goods_id)
    {
        $data = M('GoodsPhoto')->where(['goods_id'=>$goods_id, 'is_show'=>1])->order('sort asc')->getField('images', true);
        if(!empty($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v = $images_host.$v;
            }
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 获取商品属性
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @return  [array]                  [属性]
     */
    private function GetGoodsAttribute($goods_id)
    {
        $result = [];
        $data = M('GoodsAttributeType')->where(['goods_id'=>$goods_id])->field('id,type,name')->order('sort asc')->select();
        if(!empty($data))
        {
            foreach($data as $v)
            {
                $v['find'] = M('GoodsAttribute')->field('id,name')->where(['goods_id'=>$goods_id, 'attribute_type_id'=>$v['id']])->order('sort asc')->select();
                $result[$v['type']][] = $v;
            }
        } else {
            $data = [];
        }
        return $result;
    }

    /**
     * 用户商品收藏
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     */
    public function Favor()
    {
        // 登录校验
        $this->Is_Login();

        // 参数校验
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品ID有误',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 开始操作
        $m = M('GoodsFavor');
        $data = ['goods_id'=>intval($this->data_post['goods_id']), 'user_id'=>$this->user['id']];
        $temp = $m->where($data)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if($m->add($data) > 0)
            {
                $this->ajaxReturn(L('common_favor_success'), 0);
            } else {
                $this->ajaxReturn(L('common_favor_error'));
            }
        } else {
            if($m->where($data)->delete() > 0)
            {
                $this->ajaxReturn(L('common_cancel_success'), 0);
            } else {
                $this->ajaxReturn(L('common_cancel_error'));
            }
        }
    }

}
?>