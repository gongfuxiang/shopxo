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
namespace app\plugins\wallet\service;

use think\Db;
use app\service\PluginsService;
use app\service\ResourcesService;
use app\service\PaymentService;
use app\plugins\wallet\service\WalletService;
use app\plugins\wallet\service\PayService;

/**
 * 基础服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BaseService
{
    /**
     * 充值列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-30T00:13:14+0800
     * @param   [array]          $params [输入参数]
     */
    public static function RechargeList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsWalletRecharge')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 用户信息
                if(!empty($v['user_id']))
                {
                    $user = Db::name('User')->where(['id'=>$v['user_id']])->field('username,nickname,mobile,gender,avatar')->find();
                    $v['username'] = empty($user['username']) ? '' : $user['username'];
                    $v['nickname'] = empty($user['nickname']) ? '' : $user['nickname'];
                    $v['mobile'] = empty($user['mobile']) ? '' : $user['mobile'];
                    $v['avatar'] = empty($user['avatar']) ? '' : $user['avatar'];
                    $v['gender_text'] = isset($user['gender']) ? $common_gender_list[$user['gender']]['name'] : '';
                }

                // 支付状态
                $v['status_text'] = isset($v['status']) ? PayService::$recharge_status_list[$v['status']]['name'] : '';

                // 支付时间
                $v['pay_time_text'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);

                // 创建时间
                $v['add_time_text'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 充值列表总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function RechargeTotal($where = [])
    {
        return (int) Db::name('PluginsWalletRecharge')->where($where)->count();
    }

    /**
     * 充值列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RechargeWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['recharge_no', '=', $params['keywords']];
        }

        return $where;
    }

    /**
     * 充值订单创建
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function RechargeCreate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'money',
                'checked_data'      => 'CheckPrice',
                'error_msg'         => '请输入有效的充值金额',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'money',
                'checked_data'      => 0.01,
                'error_msg'         => '请输入大于0的充值金额',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 添加
        $data = [
            'recharge_no'   => date('YmdHis').GetNumberCode(6),
            'user_id'       => $params['user']['id'],
            'money'         => PriceNumberFormat($params['money']),
            'status'        => 0,
            'add_time'      => time(),

        ];
        $recharge_id = Db::name('PluginsWalletRecharge')->insertGetId($data);
        if($recharge_id > 0)
        {
            return DataReturn('添加成功',0, [
                'recharge_id'   => $recharge_id,
                'recharge_no'   => $data['recharge_no'],
                'money'         => $data['money'],
            ]);
        }
        return DataReturn('添加失败', -100);
    }

    /**
     * 充值纪录删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RechargeDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '删除数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除
        $where = [
            'id'        => intval($params['id']),
            'user_id'   => $params['user']['id']
        ];
        if(Db::name('PluginsWalletRecharge')->where($where)->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败或资源不存在', -100);
    }


    /**
     * 钱包明细列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-30T00:13:14+0800
     * @param   [array]          $params [输入参数]
     */
    public static function WalletLogList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsWalletLog')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $business_type_list = WalletService::$business_type_list;
            $operation_type_list = WalletService::$operation_type_list;
            $money_type_list = WalletService::$money_type_list;
            foreach($data as &$v)
            {
                // 业务类型
                $v['business_type_text'] = (isset($v['business_type']) && isset($business_type_list[$v['business_type']])) ? $business_type_list[$v['business_type']]['name'] : '未知';

                // 操作类型
                $v['operation_type_text'] = (isset($v['operation_type']) && isset($operation_type_list[$v['operation_type']])) ? $operation_type_list[$v['operation_type']]['name'] : '未知';

                // 金额类型
                $v['money_type_text'] = (isset($v['money_type']) && isset($money_type_list[$v['money_type']])) ? $money_type_list[$v['money_type']]['name'] : '未知';

                // 操作原因
                $v['msg'] = empty($v['msg']) ? '' : str_replace("\n", '<br />', $v['msg']);

                // 创建时间
                $v['add_time_text'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 钱包明细总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function WalletLogTotal($where = [])
    {
        return (int) Db::name('PluginsWalletLog')->where($where)->count();
    }

    /**
     * 钱包明细条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WalletLogWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // 业务类型
        if(isset($params['business_type']) && $params['business_type'] > -1)
        {
            $where[] = ['business_type', '=', $params['business_type']];
        }

        // 操作类型
        if(isset($params['operation_type']) && $params['operation_type'] > -1)
        {
            $where[] = ['operation_type', '=', $params['operation_type']];
        }

        // 金额类型
        if(isset($params['money_type']) && $params['money_type'] > -1)
        {
            $where[] = ['money_type', '=', $params['money_type']];
        }

        return $where;
    }

}
?>