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
namespace payment;

/**
 * 支付宝支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Alipay
{
    // 插件配置参数
    private $config;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]           $params [输入参数（支付配置参数）]
     */
    public function __construct($params = [])
    {
        $this->config = $params;
    }

    /**
     * 配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => '支付宝',  // 插件名称
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用PC+H5，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'account',
                'placeholder'   => '支付宝账号',
                'title'         => '支付宝账号',
                'is_required'   => 0,
                'message'       => '请填写支付宝账号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'partner',
                'placeholder'   => '合作者身份 partner ID',
                'title'         => '合作者身份 partner ID',
                'is_required'   => 0,
                'message'       => '请填写合作者身份 partner ID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '交易安全校验码 key',
                'title'         => '交易安全校验码 key',
                'is_required'   => 0,
                'message'       => '请填写交易安全校验码 key',
            ],
            // [
            //     'element'       => 'input',  // 表单标签
            //     'type'          => 'text',  // input类型
            //     'default'       => '',  // 默认值
            //     'name'          => 'testinput',  // name名称
            //     'placeholder'   => '测试输入框不需要验证',  // input默认显示文字
            //     'title'         => '测试输入框不需要验证',  // 展示title名称
            //     'is_required'   => 0,  // 是否需要强制填写/选择
            //     'message'       => '请填写测试输入框不需要验证', // 错误提示（is_required=1方可有效）
            // ],
            // [
            //     'element'       => 'textarea',
            //     'default'       => '',
            //     'name'          => 'rsa_private',
            //     'placeholder'   => 'RSA证书',
            //     'title'         => 'RSA证书',
            //     'is_required'   => 0,
            //     'minlength'     => 10,  // 最小输入字符（is_required=1方可有效）
            //     //'maxlength'     => 300,  // 最大输入字符, 填则不限（is_required=1方可有效）
            //     'rows'          => 6,
            // ],
            // [
            //     'element'       => 'input',
            //     'type'          => 'checkbox',
            //     'element_data'  => [
            //         ['value'=>1, 'name'=>'选项1'],
            //         ['value'=>2, 'name'=>'选项2',],
            //         ['value'=>3, 'name'=>'选项3'],
            //         ['value'=>4, 'name'=>'选项4']
            //     ],
            //     'is_block'      => 1,  // 是否每个选项行内展示（默认0）
            //     'minchecked'    => 2,  // 最小选项（默认以is_required=1至少一项，则0）
            //     'maxchecked'    => 3,  // 最大选项
            //     'name'          => 'checkbox',
            //     'title'         => '多选项测试',  // 展示title名称
            //     'is_required'   => 1,  // 是否需要强制填写/选择
            //     'message'       => '请选择多选项测试选择 至少选择2项最多选择3项',  // 错误提示信息
            // ],
            // [
            //     'element'       => 'input',
            //     'type'          => 'radio',
            //     'element_data'  => [
            //         ['value'=>1, 'name'=>'选项1',],
            //         ['value'=>2, 'name'=>'选项2'],
            //         ['value'=>3, 'name'=>'选项3'],
            //         ['value'=>4, 'name'=>'选项4']
            //     ],
            //     'is_block'      => 1,  // 是否每个选项行内展示（默认0）
            //     'name'          => 'radio',
            //     'title'         => '单选项测试',  // 展示title名称
            //     'is_required'   => 1,  // 是否需要强制填写/选择
            //     'message'       => '请选择单选项测试',
            // ],
            // [
            //     'element'       => 'select',
            //     'placeholder'   => '选一个撒1',
            //     'is_multiple'   => 0,  // 是否开启多选（默认0 关闭）
            //     'element_data'  => [
            //         ['value'=>1, 'name'=>'选项1'],
            //         ['value'=>2, 'name'=>'选项2'],
            //         ['value'=>3, 'name'=>'选项3'],
            //         ['value'=>4, 'name'=>'选项4']
            //     ],
            //     'name'          => 'select1',
            //     'title'         => '下拉单选测试',  // 展示title名称
            //     'is_required'   => 1,  // 是否需要强制填写/选择
            //     'message'       => '请选择下拉单选测试',
            // ],
            // [
            //     'element'       => 'select',
            //     'placeholder'   => '选一个撒2',
            //     'is_multiple'   => 1,  // 是否开启多选（默认0 关闭）
            //     'element_data'  => [
            //         ['value'=>1, 'name'=>'选项1'],
            //         ['value'=>2, 'name'=>'选项2'],
            //         ['value'=>3, 'name'=>'选项3'],
            //         ['value'=>4, 'name'=>'选项4']
            //     ],
            //     'minchecked'    => 2,  // 最小选项（默认以is_required=1至少一项，则0）
            //     'maxchecked'    => 3,  // 最大选项
            //     'name'          => 'select2',
            //     'title'         => '下拉多选测试',  // 展示title名称
            //     'is_required'   => 1,  // 是否需要强制填写/选择
            //     'message'       => '请选择下拉多选测试 至少选择2项最多选择3项',
            // ],
        ];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    /**
     * 支付入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Pay($params = [])
    {
        // 参数
        if(empty($params))
        {
            return DataReturn('参数不能为空', -1);
        }
        
        // 配置信息
        if(empty($this->config))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 手机/PC
        if(IsMobile())
        {
            $ret = $this->PayMobile($params);
        } else {
            $ret = $this->PayWeb($params);
        }
        return $ret;
    }

    /**
     * [PayMobile wap手机支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:41:09+0800
     * @param   [array]           $params [输入参数]
     */
    private function PayMobile($params = [])
    {
        // 获取请求token
        $ret = $this->GetRequestToken($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 拼接wap数据
        $req_data = '<auth_and_execute_req><request_token>'.$ret['data'].'</request_token></auth_and_execute_req>';
        $parameter = array(
            'service'               =>  'alipay.wap.auth.authAndExecute',
            'format'                =>  'xml',
            'v'                     =>  '2.0',
            'partner'               =>  $this->config['partner'],
            'sec_id'                =>  'MD5',
            'req_data'              =>  $req_data,
            'request_token'         =>  $ret['data']
        );

        $param = $this->GetParamSign($parameter);
        $url = 'http://wappaygw.alipay.com/service/rest.htm?'.$param['urlcode'].'&sign='.md5($param['sign']);
        return DataReturn('处理成功', 0, $url);
    }

    /**
     * [GetRequestToken 获取临时token]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:43:36+0800
     * @param   [array]           $params [输入参数]
     */
    private function GetRequestToken($params = [])
    {
        $parameter = array(
            'service'               =>  'alipay.wap.trade.create.direct',
            'format'                =>  'xml',
            'v'                     =>  '2.0',
            'partner'               =>  $this->config['partner'],
            'req_id'                =>  $params['order_no'],
            'sec_id'                =>  'MD5',
            'req_data'              =>  $this->GetReqData($params),
            'subject'               =>  $params['name'],
            'out_trade_no'          =>  $params['order_no'],
            'total_fee'             =>  $params['total_price'],
            'seller_account_name'   =>  $this->config['account'],
            'call_back_url'         =>  $params['call_back_url'],
            'notify_url'            =>  $params['notify_url'],
            'out_user'              =>  $params['out_user'],
            'merchant_url'          =>  isset($params['merchant_url']) ? $params['merchant_url'] : $params['call_back_url'],
        );
        
        $param = $this->GetParamSign($parameter);
        $ret = urldecode(file_get_contents('http://wappaygw.alipay.com/service/rest.htm?'.$param['urlcode'].'&sign='.md5($param['sign'])));

        // 把切割后的字符串数组变成变量与数值组合的数组
        $para_split = explode('&',$ret);
        $para_text = [];
        foreach($para_split as $item)
        {
            //获得第一个=字符的位置
            $npos = strpos($item, '=');
            //获得字符串长度
            $nlen = strlen($item);
            //获得变量名
            $key = substr($item, 0, $npos);
            //获得数值
            $value = substr($item, $npos+1, $nlen-$npos-1);
            //放入数组中
            $para_text[$key] = $value;
        }
        if(empty($para_text['res_data']))
        {
            return DataReturn('支付宝异常错误', -1);
        }

        $req = Xml_Array($para_text['res_data']);
        if(empty($req['request_token']))
        {
            return DataReturn('支付宝异常错误', -1);
        }
        return DataReturn('处理成功', 0, $req['request_token']);
    }

    /**
     * [GetReqData 订单信息拼接]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:46:02+0800
     * @param   [array]           $params [输入参数]
     */
    private function GetReqData($params = [])
    {
        return '<direct_trade_create_req>
                    <subject>'.$params['name'].'</subject>
                    <out_trade_no>'.$params['order_no'].'</out_trade_no>
                    <total_fee>'.$params['total_price'].'</total_fee>
                    <seller_account_name>'.$this->config['account'].'</seller_account_name>
                    <call_back_url>'.$params['call_back_url'].'</call_back_url>
                    <notify_url>'.$params['notify_url'].'</notify_url>
                    <out_user>'.$params['out_user'].'</out_user>
                    <merchant_url>'.(isset($params['merchant_url']) ? $params['merchant_url'] : $params['call_back_url']).'</merchant_url>
                    <pay_expire>3600</pay_expire>
                    <agent_id>0</agent_id>
                </direct_trade_create_req>';
    }

    /**
     * [PayWeb PC支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:23:04+0800
     * @param   [array]           $params [输入参数]
     */
    private function PayWeb($params = [])
    {
        $parameter = array(
            'service'           => 'create_direct_pay_by_user',
            'partner'           => $this->config['partner'],
            '_input_charset'    => 'utf-8',
            'notify_url'        => $params['notify_url'],
            'return_url'        => $params['call_back_url'],

            /* 业务参数 */
            'subject'           => $params['name'],
            'out_trade_no'      => $params['order_no'],
            'price'             => $params['total_price'],

            'quantity'          => 1,
            'payment_type'      => 1,

            /* 物流参数 */
            // 'logistics_type'    => 'EXPRESS',
            // 'logistics_fee'     => 0,
            // 'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',

            /* 买卖双方信息 */
            'seller_email'      => $this->config['account'],
        );

        $param = $this->GetParamSign($parameter);
        $url = 'https://mapi.alipay.com/gateway.do?'.$param['urlcode'].'&sign='.md5($param['sign']).'&sign_type=MD5';
        return DataReturn('处理成功', 0, $url);
    }

    /**
     * [GetParamSign 签名生成]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:28:07+0800
     * @param   [array]           $params [输入参数]
     */
    private function GetParamSign($params = [])
    {
        $urlcode = '';
        $url  = '';
        ksort($params);

        foreach($params AS $key => $val)
        {
            $urlcode .= "$key=" .urlencode($val). "&";
            $url  .= "$key=$val&";
        }

        $result = array(
            'urlcode'   => substr($urlcode, 0, -1),
            'url'       => substr($url, 0, -1),
            'sign'      => '',
        );
        if(!empty($this->config['key']))
        {
            $result['sign'] = $result['url'].$this->config['key'];
        }
        return $result;
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        if(empty($this->config))
        {
            return DataReturn('配置有误', -1);
        }

        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        ksort($data);

        $sign = '';
        if(isset($data['sec_id']) && $data['sec_id'] == 'MD5')
        {
            $data_xml = json_decode(json_encode((array) simplexml_load_string($data['notify_data'])), true);
            $data = array_merge($data, $data_xml);
            $sign = 'service='.$data['service'].'&v='.$data['v'].'&sec_id='.$data['sec_id'].'&notify_data='.$data['notify_data'];
        } else {
            foreach($data AS $key=>$val)
            {
                if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
                {
                    $sign .= "$key=$val&";
                }
            }
            $sign = substr($sign, 0, -1);
        }

        // 签名校验
        if(!isset($data['sign']) || md5($sign.$this->config['key']) != $data['sign'])
        {
            return DataReturn('签名校验失败', -1);
        }

        // 支付状态
        $status = isset($data['trade_status']) ? $data['trade_status'] : $data['result'];
        switch($status)
        {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
            case 'success':
                return DataReturn('支付成功', 0, $this->ReturnData($data));
                break;
        }
        return DataReturn('处理异常错误', -100);
    }

    /**
     * [ReturnData 返回数据统一格式]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-06T16:54:24+0800
     * @param    [array]                   $data [返回数据]
     */
    private function ReturnData($data)
    {
        // 兼容web版本支付参数
        $buyer_user = isset($data['buyer_logon_id']) ? $data['buyer_logon_id'] : (isset($data['buyer_email']) ? $data['buyer_email'] : '');
        $pay_price = isset($data['total_amount']) ? $data['total_amount'] : (isset($data['total_fee']) ? $data['total_fee'] : '');

        // 返回数据固定基础参数
        $data['trade_no']       = isset($data['trade_no']) ? $data['trade_no'] : '';            // 支付平台 - 订单号
        $data['buyer_user']     = $buyer_user;                                                  // 支付平台 - 用户
        $data['out_trade_no']   = isset($data['out_trade_no']) ? $data['out_trade_no'] : '';    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['subject']) ? $data['subject'] : '';              // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $pay_price;                                                   // 本系统发起支付的 - 总价

        return $data;
    }
}
?>