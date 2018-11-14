<?php

namespace Api\Controller;

use Service\ResourcesService;

/**
 * 购买确认
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class BuyController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
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
     * 购买确认
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    public function Index()
    {
        // 清单商品
        $goods = $this->GetBuyGoods();

        // 用户地址
        $address = $this->GetBuyUserAddress();

        $result = [
            'goods'             => $goods['goods'],
            'total_price'       => $goods['total_price'],
            'address'           => $address,
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * 获取用户地址
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    private function GetBuyUserAddress()
    {
        $where = ['user_id'=>$this->user['id'], 'is_delete_time'=>0];
        if(empty($this->data_post['address_id']))
        {
            $where['is_default'] = 1;
        } else {
            $where['id'] = intval($this->data_post['address_id']);
        }
        $data = M('UserAddress')->where($where)->find();
        if(!empty($data))
        {
            $data['province_name'] = ResourcesService::RegionName($data['province']);
            $data['city_name'] = ResourcesService::RegionName($data['city']);
            $data['county_name'] = ResourcesService::RegionName($data['county']);
        }
        return $data;
    }

    /**
     * 获取清单商品
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    private function GetBuyGoods()
    {
        // 请求参数
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods',
                'error_msg'         => '商品信息有误',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 商品信息
        $data = json_decode($_POST['goods'], true);
        if(empty($data))
        {
            $this->ajaxReturn('商品信息有误');
        }

        $result = [];
        $total_price = 0;
        $m = M('Goods');
        $attr_type_m = M('GoodsAttributeType');
        $attr_m = M('GoodsAttribute');
        $image_host = C('IMAGE_HOST');
        foreach($data as $v)
        {
            $goods = $m->find($v['goods_id']);

            // 基础判断
            if(empty($goods))
            {
                $this->ajaxReturn('['.$v['goods_id'].']商品不存在');
            }
            if($goods['is_shelves'] != 1)
            {
                $this->ajaxReturn('['.$v['goods_id'].']商品已下架');
            }
            if($v['buy_number'] > $goods['inventory'])
            {
                $this->ajaxReturn('['.$v['goods_id'].']商品超过库存数量['.$v['buy_number'].'>'.$goods['inventory'].']');
            }
            if($goods['buy_min_number'] > 1 && $v['buy_number'] < $goods['buy_min_number'])
            {
                $this->ajaxReturn('['.$v['goods_id'].']商品购买低于起购数量['.$v['buy_number'].'<'.$goods['buy_min_number'].']');
            }
            if($goods['buy_max_number'] > 1 && $v['buy_number'] > $goods['buy_max_number'])
            {
                $this->ajaxReturn('['.$v['goods_id'].']商品购买超过限购数量['.$v['buy_number'].'>'.$goods['buy_max_number'].']');
            }

            // 属性
            $attribute_all = [];
            if(!empty($v['attribute']))
            {
                $attribute = explode(',', $v['attribute']);
                if(!empty($attribute))
                {
                    foreach($attribute as $vs)
                    {
                        $attr = explode(':', $vs);
                        if(empty($attr) || count($attr) < 2)
                        {
                            $this->ajaxReturn('['.$v['goods_id'].']商品属性有误-'.$vs);
                        }

                        // 属性类型
                        $attr_type = $attr_type_m->find($attr[0]);
                        if(empty($attr_type))
                        {
                            $this->ajaxReturn('['.$v['goods_id'].']商品属性类型有误-'.$attr[0]);
                        }

                        // 具体属性
                        $attr_content = $attr_m->find($attr[1]);
                        if(empty($attr_content))
                        {
                            $this->ajaxReturn('['.$v['goods_id'].']商品属性有误-'.$attr[1]);
                        }

                        // 属性组装
                        $attribute_all[] = $attr_type['name'].':'.$attr_content['name'];
                    }
                }
            }
            $goods['attribute'] = empty($attribute_all) ? '' : implode(',', $attribute_all);
            

            // 数据处理
            $goods['images_url'] = empty($goods['images']) ? null : $image_host.$goods['images'];
            $goods['buy_number'] = $v['buy_number'];

            // 总价
            $total_price += $goods['price']*$v['buy_number'];

            $result[] = $goods;
        }
        return [
            'goods'         => $result,
            'total_price'   => round($total_price, 2),
        ];
    }

    /**
     * 订单提交
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    public function Submit()
    {
        // 清单商品
        $goods = $this->GetBuyGoods();

        // 用户地址
        $address = $this->GetBuyUserAddress();
        if(empty($address))
        {
            $this->ajaxReturn('地址有误');
        }

        // 优惠金额
        $preferential_price = 0.00;

        // 店铺
        $shop_id = 0;

        // 订单写入
        $order = [
            'user_id'               => $this->user['id'],
            'shop_id'               => $shop_id,
            'receive_address_id'    => $address['id'],
            'receive_name'          => $address['name'],
            'receive_tel'           => $address['tel'],
            'receive_province'      => $address['province'],
            'receive_city'          => $address['city'],
            'receive_county'        => $address['county'],
            'receive_address'       => $address['address'],
            'user_note'             => I('user_note'),
            'status'                => 1,
            'preferential_price'    => $preferential_price,
            'price'                 => $goods['total_price'],
            'total_price'           => $goods['total_price']-$preferential_price,
            'add_time'              => time(),
            'confirm_time'          => time(),
        ];

        // 开始事务
        $m = M('Order');
        $m->startTrans();

        // 订单添加
        $order_id = $m->add($order);
        if($order_id > 0)
        {
            $detail_m = M('OrderDetail');
            foreach($goods['goods'] as $v)
            {
                $detail = [
                    'order_id'          => $order_id,
                    'user_id'           => $this->user['id'],
                    'shop_id'           => $shop_id,
                    'goods_id'          => $v['id'],
                    'title'             => $v['title'],
                    'images'            => $v['images'],
                    'original_price'    => $v['original_price'],
                    'price'             => $v['price'],
                    'attribute'         => $v['attribute'],
                    'buy_number'        => $v['buy_number'],
                    'add_time'          => time(),
                ];
                if($detail_m->add($detail) <= 0)
                {
                    $m->rollback();
                    $this->ajaxReturn('订单详情添加失败');
                }
            }
        } else {
            $m->rollback();
            $this->ajaxReturn('订单添加失败');
        }
        $m->commit();

        // 获取订单信息
        $data = $m->find($order_id);
        switch($order['status'])
        {
            // 预约成功
            case 0 :
                $msg = L('common_booking_success');
                break;

            // 提交成功
            case 1 :
                $msg = L('common_submit_success');
                break;

            // 默认操作成功
            default :
                $msg = L('common_operation_success');
        }
        $this->ajaxReturn($msg, 0, $data);
    }
}
?>