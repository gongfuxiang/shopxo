<?php

namespace Home\Controller;

/**
 * 优惠券
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2018-05-21T21:51:08+0800
 */
class CouponController extends CommonController
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
     * [Index 获取优惠券列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T13:18:01+0800
     */
    public function Index()
    {
        // 条件
        $where = $this->GetIndexWhere();

        // 获取数据
        $field = 'c.type AS coupon_type, c.name AS coupon_name, c.use_where_price, uc.id AS user_coupon_id, uc.user_id, uc.price, uc.valid_start_time, uc.valid_end_time, uc.status';
        $data = $this->SetDataList(M('UserCoupon')->alias('uc')->join('__COUPON__ AS c ON c.id=uc.coupon_id')->where($where)->field($field)->order('uc.status asc, uc.id desc')->select());
        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * [GetIndexWhere 获取优惠券列表 - 条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:13:32+0800
     */
    private function GetIndexWhere()
    {
        $where = [
            'uc.user_id'            => $this->user['id'],
            'uc.is_delete_time'     => 0
        ];

        // 状态
        if(isset($this->data_post['status']) && strlen($this->data_post['status']) > 0 && in_array($this->data_post['status'], array_keys(L('common_user_coupon_status'))))
        {
            $where['uc.status'] = intval($this->data_post['status']);
        }

        // 条件金额
        if(!empty($this->data_post['order_price']))
        {
            if(!CheckPrice($this->data_post['order_price']))
            {
                $this->ajaxReturn('订单金额格式有误');
            }
            $where['c.use_where_price'] = ['ELT', $this->data_post['order_price']];
        }

        return $where;
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
            $common_coupon_type = L('common_coupon_type');
            $common_user_coupon_status = L('common_user_coupon_status');
            $coupon_m = M('Coupon');
            foreach($data as &$v)
            {
                // 优惠券信息加入
                $v['coupon_type_text'] = $common_coupon_type[$v['coupon_type']]['name'];
                $v['coupon_use_where'] = ($v['use_where_price'] <= 0.00) ? '不限' : '金额满'.PriceBeautify($v['use_where_price']);
                unset($v['use_where_price'], $v['coupon_type']);

                // 有效时间
                $v['valid_start_time_text'] = empty($v['valid_start_time']) ? '' : date('Y-m-d H:i:s', $v['valid_start_time']);
                $v['valid_end_time_text'] = empty($v['valid_end_time']) ? '' : date('Y-m-d H:i:s', $v['valid_end_time']);
                unset($v['valid_start_time'], $v['valid_end_time']);

                // 金额美化
                $v['price'] = PriceBeautify($v['price']);

                // 是否启用
                $v['status_text'] = $common_user_coupon_status[$v['status']]['name'];
            }
        }
        return $data;
    }
}
?>