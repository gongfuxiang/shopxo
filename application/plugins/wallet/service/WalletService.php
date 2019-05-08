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
use app\service\MessageService;

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
        0 => ['value' => 0, 'name' => '系统', 'checked' => true],
        1 => ['value' => 1, 'name' => '充值'],
        2 => ['value' => 2, 'name' => '提现'],
        3 => ['value' => 3, 'name' => '消费'],
    ];

    // 操作类型
    public static $operation_type_list = [
        0 => ['value' => 0, 'name' => '减少', 'checked' => true],
        1 => ['value' => 1, 'name' => '增加'],
    ];

    // 金额类型
    public static $money_type_list = [
        0 => ['value' => 0, 'name' => '有效', 'checked' => true],
        1 => ['value' => 1, 'name' => '冻结'],
        2 => ['value' => 2, 'name' => '赠送'],
    ];

    /**
     * 用户钱包
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-30
     * @desc    description
     * @param   [int]          $user_id [用户id]
     */
    public static function UserWallet($user_id)
    {
        // 请求参数
        if(empty($user_id))
        {
            return DataReturn('用户id有误', -1);
        }

        // 获取钱包, 不存在则创建
        $wallet = Db::name('PluginsWallet')->where(['user_id' => $user_id])->find();
        if(empty($wallet))
        {
            $data = [
                'user_id'       => $user_id,
                'status'        => 0,
                'add_time'      => time(),
            ];
            $wallet_id = Db::name('PluginsWallet')->insertGetId($data);
            if($wallet_id > 0)
            {
                return DataReturn('操作成功', 0, Db::name('PluginsWallet')->find($wallet_id));
            } else {
                return DataReturn('钱包添加失败', -100);
            }
        } else {
            return self::UserWalletStatusCheck($wallet);
        }
    }

    /**
     * 用户钱包状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-08
     * @desc    description
     * @param   [array]          $user_wallet [用户钱包]
     */
    public static function UserWalletStatusCheck($user_wallet)
    {
        // 用户钱包状态
        $wallet_error = '';
        if(isset($user_wallet['status']))
        {
            if($user_wallet['status'] != 0)
            {
                $wallet_error = array_key_exists($user_wallet['status'], self::$wallet_status_list) ? '用户钱包[ '.self::$wallet_status_list[$user_wallet['status']]['name'].' ]' : '用户钱包状态异常错误';
            }
        } else {
            $wallet_error = '用户钱包异常错误';
        }

        if(!empty($wallet_error))
        {
            return DataReturn($wallet_error, -30);
        }
        return DataReturn('操作成功', 0, $user_wallet);
    }

    /**
     * 钱包日志添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-07T00:57:36+0800
     * @param   [array]          $params [输入参数]
     * @return  [boolean]                [成功true, 失败false]
     */
    public static function WalletLogInsert($params = [])
    {
        $data = [
            'user_id'           => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'wallet_id'         => isset($params['wallet_id']) ? intval($params['wallet_id']) : 0,
            'business_type'     => isset($params['business_type']) ? intval($params['business_type']) : 0,
            'operation_type'    => isset($params['operation_type']) ? intval($params['operation_type']) : 0,
            'money_type'        => isset($params['money_type']) ? intval($params['money_type']) : 0,
            'operation_money'   => isset($params['operation_money']) ? PriceNumberFormat($params['operation_money']) : 0.00,
            'original_money'    => isset($params['original_money']) ? PriceNumberFormat($params['original_money']) : 0.00,
            'latest_money'      => isset($params['latest_money']) ? PriceNumberFormat($params['latest_money']) : 0.00,
            'msg'               => empty($params['msg']) ? '系统' : $params['msg'],
            'add_time'          => time(),
        ];
        return Db::name('PluginsWalletLog')->insertGetId($data) > 0;
    }

    /**
     * 钱包编辑
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WalletEdit($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '钱包id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(self::$wallet_status_list, 'value'),
                'error_msg'         => '钱包状态有误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'normal_money',
                'checked_data'      => 'CheckPrice',
                'is_checked'        => 1,
                'error_msg'         => '有效金额格式有误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'frozen_money',
                'checked_data'      => 'CheckPrice',
                'is_checked'        => 1,
                'error_msg'         => '冻结金额格式有误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'give_money',
                'checked_data'      => 'CheckPrice',
                'is_checked'        => 1,
                'error_msg'         => '赠送金额格式有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取钱包
        $wallet = Db::name('PluginsWallet')->find(intval($params['id']));
        if(empty($wallet))
        {
            return DataReturn('钱包不存在或已删除', -10);
        }

        // 开始处理
        Db::startTrans();

        // 数据
        $data = [
            'status'        => intval($params['status']),
            'normal_money'  => empty($params['normal_money']) ? 0.00 : PriceNumberFormat($params['normal_money']),
            'frozen_money'  => empty($params['frozen_money']) ? 0.00 : PriceNumberFormat($params['frozen_money']),
            'give_money'    => empty($params['give_money']) ? 0.00 : PriceNumberFormat($params['give_money']),
            'upd_time'      => time(),
        ];
        if(!Db::name('PluginsWallet')->where(['id'=>$wallet['id']])->update($data))
        {
            Db::rollback();
            return DataReturn('操作失败', -100);
        }

        // 日志
        // 字段名称 金额类型 金额名称
        $money_field = [
            ['field' => 'normal_money', 'money_type' => 0],
            ['field' => 'frozen_money', 'money_type' => 1],
            ['field' => 'give_money', 'money_type' => 2],
        ];

        // 是否发送消息
        $is_send_message = (isset($params['is_send_message']) && $params['is_send_message'] == 1) ? 1 : 0;

        // 操作原因
        $operation_msg = empty($params['msg']) ? '' : ' [ '.$params['msg'].' ]';
        foreach($money_field as $v)
        {
            // 有效金额
            if($wallet[$v['field']] != $data[$v['field']])
            {
                $log_data = [
                    'user_id'           => $wallet['user_id'],
                    'wallet_id'         => $wallet['id'],
                    'business_type'     => 0,
                    'operation_type'    => ($wallet[$v['field']] < $data[$v['field']]) ? 1 : 0,
                    'money_type'        => $v['money_type'],
                    'operation_money'   => ($wallet[$v['field']] < $data[$v['field']]) ? PriceNumberFormat($data[$v['field']]-$wallet[$v['field']]) : PriceNumberFormat($wallet[$v['field']]-$data[$v['field']]),
                    'original_money'    => $wallet[$v['field']],
                    'latest_money'      => $data[$v['field']],
                ];
                $operation_type_text = ($log_data['operation_type'] == 1) ? '增加' : '减少';
                $log_data['msg'] = '管理员操作[ '.self::$money_type_list[$v['money_type']]['name'].'金额'.$operation_type_text.$log_data['operation_money'].'元 ]'.$operation_msg;
                if(!self::WalletLogInsert($log_data))
                {
                    Db::rollback();
                    return DataReturn('日志添加失败', -101);
                }

                // 消息通知
                if($is_send_message == 1)
                {
                    MessageService::MessageAdd($wallet['user_id'], '账户余额变动', $log_data['msg'], 0, $wallet['id']);
                }
            }
        }

        // 处理成功
        Db::commit();
        return DataReturn('操作成功', 0);
    }
}
?>