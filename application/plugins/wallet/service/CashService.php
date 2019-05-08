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
use app\service\MessageService;
use app\plugins\wallet\service\WalletService;

/**
 * 提现服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CashService
{
    // 提现状态
    public static $cash_status_list = [
        0 => ['value' => 0, 'name' => '未打款', 'checked' => true],
        1 => ['value' => 1, 'name' => '已打款'],
        2 => ['value' => 2, 'name' => '打款失败'],
    ];

    /**
     * 验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-05T19:17:10+0800
     * @param    [array]          $params [输入参数]
     */
    public static function VerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'account_type',
                'error_msg'         => '身份认证方式有误',
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

        // 账户
        if(empty($params['user'][$params['account_type']]))
        {
            return DataReturn('当前验证类型账号未绑定', -1);
        }

        // 验证码基础参数
        $img_verify_params = array(
                'key_prefix' => 'wallet_cash',
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $img_verify_params);
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 当前验证账户
        $accounts = $params['user'][$params['account_type']];

        // 发送验证码
        $verify_params = array(
                'key_prefix' => md5('wallet_cash_'.$accounts),
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );
        $code = GetNumberCode(6);
        if($params['account_type'] == 'mobile')
        {
            $obj = new \base\Sms($verify_params);
            $status = $obj->SendCode($accounts, $code, MyC('home_sms_user_mobile_binding'));
        } else {
            $obj = new \base\Email($verify_params);
            $email_params = array(
                    'email'     =>  $accounts,
                    'content'   =>  MyC('home_email_user_email_binding'),
                    'title'     =>  MyC('home_site_name').' - 账户安全认证',
                    'code'      =>  $code,
                );
            $status = $obj->SendHtml($email_params);
        }
        
        // 状态
        if($status)
        {
            // 清除验证码
            if(isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn('发送成功', 0);
        }
        return DataReturn('发送失败'.'['.$obj->error.']', -100);
    }

    /**
     * 是否开启图片验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-22T15:48:31+0800
     * @param    [array]    $params         [输入参数]
     * @param    [array]    $verify_params  [配置参数]
     * @return   [object]                   [图片验证码类对象]
     */
    private static function IsImaVerify($params, $verify_params)
    {
        if(MyC('home_img_verify_state') == 1)
        {
            if(empty($params['verify']))
            {
                return DataReturn('参数错误', -10);
            }
            $verify = new \base\Verify($verify_params);
            if(!$verify->CheckExpire())
            {
                return DataReturn('验证码已过期', -11);
            }
            if(!$verify->CheckCorrect($params['verify']))
            {
                return DataReturn('验证码错误', -12);
            }
            return DataReturn('操作成功', 0, $verify);
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-28T15:57:19+0800
     * @param    [array]          $params [输入参数]
     */
    public static function VerifyCheck($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'account_type',
                'error_msg'         => '身份认证方式有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
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

        // 账户
        if(empty($params['user'][$params['account_type']]))
        {
            return DataReturn('当前验证类型账号未绑定', -1);
        }

        // 当前验证账户
        $accounts = $params['user'][$params['account_type']];

        // 验证码校验
        $verify_params = array(
                'key_prefix' => md5('wallet_cash_'.$accounts),
                'expire_time' => MyC('common_verify_expire_time')
            );
        if($params['account_type'] == 'mobile')
        {
            $obj = new \base\Sms($verify_params);
        } else {
            $obj = new \base\Email($verify_params);
        }
        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn('验证码已过期', -10);
        }
        // 是否正确
        if($obj->CheckCorrect($params['verify']))
        {
            // 校验成功标记
            session('wallet_cash_'.$params['account_type'], true);

            // 清除验证码
            $obj->Remove();

            return DataReturn('验证正确', 0);
        }
        return DataReturn('验证码错误', -11);
    }

    /**
     * 提现创建
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-08
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function Create($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'money',
                'error_msg'         => '提现金额不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'bank_name',
                'checked_data'      => '1,60',
                'error_msg'         => '收款银行格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'bank_accounts',
                'checked_data'      => '1,60',
                'error_msg'         => '收款账号格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'bank_username',
                'checked_data'      => '1,30',
                'error_msg'         => '开户人姓名格式 1~30 个字符之间',
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

        // 用户钱包
        $user_wallet = WalletService::UserWallet($params['user']['id']);
        if($user_wallet['code'] != 0)
        {
            return $user_wallet;
        }

        // 提现金额
        $money = PriceNumberFormat($params['money']);
        if($money > $user_wallet['data']['normal_money'])
        {
            return DataReturn('提现金额不能大于有效金额', -1);
        }

        // 开始处理
        Db::startTrans();

        // 添加提现数据
        $data = [
            'cash_no'           => date('YmdHis').GetNumberCode(6),
            'user_id'           => $user_wallet['data']['user_id'],
            'wallet_id'         => $user_wallet['data']['id'],
            'status'            => 0,
            'money'             => $money,
            'bank_name'         => $params['bank_name'],
            'bank_accounts'     => $params['bank_accounts'],
            'bank_username'     => $params['bank_username'],
            'add_time'          => time(),
        ];
        $cash_id = Db::name('PluginsWalletCash')->insertGetId($data);
        if($cash_id <= 0)
        {
            Db::rollback();
            return DataReturn('提现操作失败', -100);
        }

        // 钱包更新
        $wallet_data = [
            'normal_money'  => PriceNumberFormat($user_wallet['data']['normal_money']-$money),
            'upd_time'      => time(),
        ];
        if(!Db::name('PluginsWallet')->where(['id'=>$user_wallet['data']['id']])->update($wallet_data))
        {
            Db::rollback();
            return DataReturn('钱包操作失败', -100);
        }

        // 日志
        $log_data = [
            'user_id'           => $user_wallet['data']['user_id'],
            'wallet_id'         => $user_wallet['data']['id'],
            'business_type'     => 2,
            'operation_type'    => 0,
            'money_type'        => 0,
            'operation_money'   => $money,
            'original_money'    => $user_wallet['data']['normal_money'],
            'latest_money'      => $wallet_data['normal_money'],
        ];
        $log_data['msg'] = '用户提现申请[ 减少有效金额'.$log_data['operation_money'].'元 ]';
        if(!WalletService::WalletLogInsert($log_data))
        {
            Db::rollback();
            return DataReturn('日志添加失败', -101);
        }

        // 消息通知
        MessageService::MessageAdd($user_wallet['data']['user_id'], '账户余额变动', $log_data['msg'], 0, $cash_id);

        // 提交事务
        Db::commit();
        return DataReturn('操作成功', 0);   
    }
}
?>