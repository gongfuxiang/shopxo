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
 * 基础服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BaseService
{
    // 基础数据附件字段
    public static $base_config_attachment_field = [
        'default_level_images'
    ];

    // 充值支付状态
    public static $recharge_status_list = [
        0 => ['value' => 0, 'name' => '未支付', 'checked' => true],
        1 => ['value' => 1, 'name' => '已支付'],
    ];

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
                $v['status_text'] = isset($v['status']) ? self::$recharge_status_list[$v['status']]['name'] : '';

                // 支付时间
                $v['pay_time_text'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);

                // 创建时间
                $v['add_time_text'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 总数
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
    public static function RechargeListWhere($params = [])
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
     * 支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-29
     * @desc    description
     * @param   array           $params [description]
     */
    public static function Pay($params = [])
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
                'key_name'          => 'recharge_id',
                'error_msg'         => '充值日志id不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'payment_id',
                'error_msg'         => '请选择支付方式',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 支付方式;
        $payment = PaymentService::PaymentList(['where'=>['id'=>intval($params['payment_id']), 'is_enable'=>1, 'is_open_user'=>1]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付入口文件检查
        $pay_checked = PaymentService::EntranceFileChecked($payment[0]['payment'], 'wallet');
        if($pay_checked['code'] != 0)
        {
            // 入口文件不存在则创建
            $payment_params = [
                'payment'       => $payment[0]['payment'],
                'business'      => [
                    ['name' => 'Wallet', 'desc' => '钱包'],
                ],
                'respond'       => '/index/plugins/index/pluginsname/wallet/pluginscontrol/wallet/pluginsaction/respond',
                'notify'        => '/index/plugins/index/pluginsname/wallet/pluginscontrol/wallet/pluginsaction/notify',
            ];
            $ret = PaymentService::PaymentEntranceCreated($payment_params);
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 非线上支付方式不可用
        if(in_array($payment[0]['payment'], config('shopxo.under_line_list')))
        {
            return DataReturn('不能使用非线上支付方式进行充值', -10);
        }

        // 获取充值数据
        $recharge = Db::name('PluginsWalletRecharge')->where(['id'=>intval($params['recharge_id'])])->find();
        if(empty($recharge))
        {
            return DataReturn('充值数据不存在', -1);
        }
        if($recharge['status'] == 1)
        {
            return DataReturn('该数据已充值，请重新创建充值订单', -2);
        }

        // 回调地址
        $url = __MY_URL__.'payment_wallet_'.strtolower($payment[0]['payment']);

        // url模式, pathinfo模式下采用自带url生成url, 避免非index.php多余
        if(MyC('home_seo_url_model', 0) == 0)
        {
            $call_back_url = $url.'_respond.php';
        } else {
            $call_back_url = PluginsHomeUrl('wallet', 'recharge', 'respond', ['paymentname'=>$payment[0]['payment']]);
            if(stripos($call_back_url, '?') !== false)
            {
                $call_back_url = $url.'_respond.php';
            }
        }

        // 发起支付
        $pay_data = array(
            'user'          => $params['user'],
            'out_user'      => md5($params['user']['id']),
            'order_id'      => $recharge['id'],
            'order_no'      => $recharge['recharge_no'],
            'name'          => '钱包充值',
            'total_price'   => $recharge['money'],
            'notify_url'    => $url.'_notify.php',
            'call_back_url' => $call_back_url,
            'site_name'     => MyC('home_site_name', 'ShopXO', true),
            'ajax_url'      => PluginsHomeUrl('wallet', 'recharge', 'paycheck')
        );
        $pay_name = 'payment\\'.$payment[0]['payment'];
        $ret = (new $pay_name($payment[0]['config']))->Pay($pay_data);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            return $ret;
        }
        return DataReturn(empty($ret['msg']) ? '支付接口异常' : $ret['msg'], -1);
    }

    /**
     * 支付状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RechargePayCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '充值单号有误',
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

        // 获取订单状态
        $where = ['recharge_no'=>$params['order_no'], 'user_id'=>$params['user']['id']];
        $recharge = Db::name('PluginsWalletRecharge')->where($where)->field('id,status')->find();
        if(empty($recharge))
        {
            return DataReturn('充值数据不存在', -400, ['url'=>__MY_URL__]);
        }
        if($recharge['status'] == 1)
        {
            return DataReturn('支付成功', 0, ['url'=>PluginsHomeUrl('wallet', 'recharge', 'index')]);
        }
        return DataReturn('支付中', -300);
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
}
?>