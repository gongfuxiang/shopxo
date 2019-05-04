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

/**
 * 钱包服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class WalletService
{
    // 钱包状态
    public static $wallet_status_list = [
        0 => ['value' => 0, 'name' => '正常', 'checked' => true],
        1 => ['value' => 1, 'name' => '异常'],
        2 => ['value' => 2, 'name' => '已注销'],
    ];

    // 业务类型
    public static $business_type_list = [
        0 => ['value' => 0, 'name' => '充值', 'checked' => true],
        1 => ['value' => 1, 'name' => '提现'],
        2 => ['value' => 2, 'name' => '消费'],
    ];

    // 操作类型
    public static $operation_type_list = [
        0 => ['value' => 0, 'name' => '减少', 'checked' => true],
        1 => ['value' => 1, 'name' => '增加'],
    ];

    // 金额类型
    public static $money_type_list = [
        0 => ['value' => 0, 'name' => '正常', 'checked' => true],
        1 => ['value' => 1, 'name' => '冻结'],
    ];

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
                // 状态
                $v['status_text'] = (isset($v['status']) && isset($wallet_status_list[$v['status']])) ? $wallet_status_list[$v['status']]['name'] : '未知';

                // 创建时间
                $v['add_time_text'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
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
            $user_ids = Db::name('User')->where('username|mobile|email', 'like', '%'.$params['keywords'].'%')->column('id');
            if(!empty($user_ids))
            {
                $where[] = ['user_id', 'in', $user_ids];
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
     * 用户钱包
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserWallet($params = [])
    {
        // 请求参数
        $p = [
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

        // 获取钱包, 不存在则创建
        $wallet = Db::name('PluginsWallet')->where(['user_id' => $params['user']['id']])->find();
        if(empty($wallet))
        {
            $data = [
                'user_id'       => $params['user']['id'],
                'status'        => 0,
                'add_time'      => time(),
            ];
            $wallet_id = Db::name('PluginsWallet')->insertGetId($data);
            if($wallet_id > 0)
            {
                $wallet = Db::name('PluginsWallet')->find($wallet_id);
            } else {
                return DataReturn('钱包添加失败', -100);
            }
        }

        return DataReturn('操作成功', 0, $wallet);
    }
}
?>