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

/**
 * 充值服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class RechargeService
{
    // 充值支付状态
    public static $recharge_status_list = [
        0 => ['value' => 0, 'name' => '未支付', 'checked' => true],
        1 => ['value' => 1, 'name' => '已支付'],
    ];

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
                'checked_type'      => 'empty',
                'key_name'          => 'user_wallet',
                'error_msg'         => '用户钱包有误',
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
            'wallet_id'     => $params['user_wallet']['id'],
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
                'is_checked'        => 2,
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
        ];
        if(!empty($params['user']['id']))
        {
            $where['user_id'] = $params['user']['id'];
        }
        if(Db::name('PluginsWalletRecharge')->where($where)->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败或资源不存在', -100);
    }

}
?>