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
use app\service\ResourcesService;
use app\service\UserService;
use app\plugins\wallet\service\WalletService;
use app\plugins\wallet\service\CashService;
use app\plugins\wallet\service\RechargeService;

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
     * 钱包列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-30T00:13:14+0800
     * @param   [array]          $params [输入参数]
     */
    public static function WalletList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsWallet')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $wallet_status_list = WalletService::$wallet_status_list;
            foreach($data as &$v)
            {
                // 用户信息
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);

                // 状态
                $v['status_text'] = (isset($v['status']) && isset($wallet_status_list[$v['status']])) ? $wallet_status_list[$v['status']]['name'] : '未知';

                // 时间
                $v['add_time_text'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time_text'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 钱包总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function WalletTotal($where = [])
    {
        return (int) Db::name('PluginsWallet')->where($where)->count();
    }

    /**
     * 钱包条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WalletWhere($params = [])
    {
        $where = [];

        // 用户
        if(!empty($params['keywords']))
        {
            $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
            if(!empty($user_ids))
            {
                $where[] = ['user_id', 'in', $user_ids];
            } else {
                // 无数据条件，避免用户搜索条件没有数据造成的错觉
                $where[] = ['id', '=', 0];
            }
        }

        // 状态
        if(isset($params['status']) && $params['status'] > -1)
        {
            $where[] = ['status', '=', $params['status']];
        }

        return $where;
    }

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
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);

                // 支付状态
                $v['status_text'] = isset($v['status']) ? RechargeService::$recharge_status_list[$v['status']]['name'] : '';

                // 支付时间
                $v['pay_time_text'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);

                // 创建时间
                $v['add_time_text'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
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

        // 关键字根据用户筛选
        if(!empty($params['keywords']))
        {
            if(empty($params['user']))
            {
                $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
                if(!empty($user_ids))
                {
                    $where[] = ['user_id', 'in', $user_ids];
                } else {
                    // 无数据条件，走单号条件
                    $where[] = ['recharge_no', '=', $params['keywords']];
                }
            }
        }

        // 状态
        if(isset($params['status']) && $params['status'] > -1)
        {
            $where[] = ['status', '=', $params['status']];
        }

        return $where;
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
                // 用户信息
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);
                
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

        // 用户
        if(!empty($params['keywords']))
        {
            $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
            if(!empty($user_ids))
            {
                $where[] = ['user_id', 'in', $user_ids];
            } else {
                // 无数据条件，避免用户搜索条件没有数据造成的错觉
                $where[] = ['id', '=', 0];
            }
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

    /**
     * 提现列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-30T00:13:14+0800
     * @param   [array]          $params [输入参数]
     */
    public static function CashList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsWalletCash')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 用户信息
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);

                // 提现状态
                $v['status_text'] = isset($v['status']) ? CashService::$cash_status_list[$v['status']]['name'] : '';

                // 备注
                $v['msg'] = empty($v['msg']) ? '' : str_replace("\n", '<br />', $v['msg']);

                // 时间
                $v['pay_time_text'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);
                $v['add_time_text'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time_text'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 提现列表总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function CashTotal($where = [])
    {
        return (int) Db::name('PluginsWalletCash')->where($where)->count();
    }

    /**
     * 提现列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CashWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // 关键字根据用户筛选
        if(!empty($params['keywords']))
        {
            if(empty($params['user']))
            {
                $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
                if(!empty($user_ids))
                {
                    $where[] = ['user_id', 'in', $user_ids];
                } else {
                    // 无数据条件，走单号条件
                    $where[] = ['cash_no', '=', $params['keywords']];
                }
            }
        }

        // 状态
        if(isset($params['status']) && $params['status'] > -1)
        {
            $where[] = ['status', '=', $params['status']];
        }

        return $where;
    }

}
?>