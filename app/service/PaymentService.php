<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

use think\facade\Db;
use app\service\SystemService;
use app\service\ResourcesService;
use app\service\StoreService;

/**
 * 支付方式服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PaymentService
{
    // 插件目录
    public static $payment_dir;

    // 支付业务类型
    public static $payment_business_type_all;

    // 不删除的支付方式
    public static $cannot_deleted_list;

    // 入口文件位置
    public static $dir_root_path;

    // 支付缓存key
    public static $pay_cashier_cache_key = 'payment_cashier_key_';

    /**
     * 初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-24
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private static function Init($params = [])
    {
        // 插件目录
        self::$payment_dir = ROOT.'extend'.DS.'payment'.DS;

        // 支付业务类型
        self::$payment_business_type_all = MyConfig('shopxo.payment_business_type_all');

        // 不删除的支付方式
        self::$cannot_deleted_list = ['DeliveryPayment', 'CashPayment'];

        // 入口文件位置
        self::$dir_root_path = defined('IS_ROOT_ACCESS') ? ROOT : ROOT.'public'.DS;
    }

    /**
     * 获取支付插件列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [int]           $type [null全部、0已安装、1未安装]
     */
    public static function PluginsPaymentList($type = null)
    {
        // 初始化
        self::Init();

        // 开始处理
        $data = [];
        if(is_dir(self::$payment_dir))
        {
            if($dh = opendir(self::$payment_dir))
            {
                $common_platform_type = MyConst('common_platform_type');
                while(($temp_file = readdir($dh)) !== false)
                {
                    if(substr($temp_file, 0, 1) != '.')
                    {
                        // 获取模块配置信息
                        $payment = htmlentities(str_replace('.php', '', $temp_file));
                        $config = self::GetPaymentConfig($payment);
                        if($config !== false)
                        {
                            // 数据组装
                            $temp = self::DataAnalysis($config);
                            $temp['id'] = date('YmdHis').GetNumberCode(8);
                            $temp['payment'] = $payment;

                            // 获取数据库配置信息
                            $db_config = self::PaymentList(['where'=>['payment'=>$payment]]);
                            if(!empty($db_config[0]))
                            {
                                $temp['is_install'] = 1;
                                $temp['id'] = $db_config[0]['id'];
                                $temp['name'] = $db_config[0]['name'];
                                $temp['logo'] = $db_config[0]['logo'];
                                $temp['config'] = $db_config[0]['config'];
                                $temp['is_enable'] = $db_config[0]['is_enable'];
                                $temp['is_open_user'] = $db_config[0]['is_open_user'];

                                // 支付平台类型
                                $apply_terminal_names = [];
                                if(!empty($db_config[0]['apply_terminal']) && is_array($db_config[0]['apply_terminal']))
                                {
                                    foreach($common_platform_type as $platform_type)
                                    {
                                        if(in_array($platform_type['value'], $db_config[0]['apply_terminal']))
                                        {
                                            $apply_terminal_names[] = $platform_type['name'];
                                        }
                                    }
                                }
                                $temp['apply_terminal_names'] = $apply_terminal_names;
                                $temp['apply_terminal'] = $db_config[0]['apply_terminal'];
                            }

                            // 未指定状态，已安装
                            if($type === null || ($type == 0 && isset($temp['is_install']) && $temp['is_install'] == 1))
                            {
                                $data[] = $temp;
                            } else {
                                // 未安装
                                if($type == 1 && (!isset($temp['is_install']) || $temp['is_install'] != 1))
                                {
                                    $data[] = $temp;
                                }
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }

        // 所有支付方式列表钩子
        $hook_name = 'plugins_service_payment_all_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
        ]);

        return DataReturn('success', 0, $data);
    }

    /**
     * 获取支付模块配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [string]          $payment [模块名称]
     */
    public static function GetPaymentConfig($payment)
    {
        $payment = '\payment\\'.$payment;
        if(class_exists($payment))
        {
            $obj = new $payment();
            if(method_exists($obj, 'Config') && method_exists($obj, 'Pay') && method_exists($obj, 'Respond'))
            {
                return $obj->Config();
            }
        }
        return false;
    }

    /**
     * 数据解析
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $data [插件配置信息]
     */
    private static function DataAnalysis($data)
    {
        return [
            'name'                  => isset($data['base']['name']) ? htmlentities($data['base']['name']) : '',
            'version'               => isset($data['base']['version']) ? htmlentities($data['base']['version']) : '',
            'apply_version'         => isset($data['base']['apply_version']) ? htmlentities($data['base']['apply_version']) : '',
            'desc'                  => isset($data['base']['desc']) ? $data['base']['desc'] : '',
            'author'                => isset($data['base']['author']) ? htmlentities($data['base']['author']) : '',
            'author_url'            => isset($data['base']['author_url']) ? htmlentities($data['base']['author_url']) : '',
            'element'               => isset($data['element']) ? $data['element'] : [],
            'logo'                  => '',
            'is_enable'             => 0,
            'is_open_user'          => 0,
            'is_install'            => 0,
            'apply_terminal'        => empty($data['base']['apply_terminal']) ? array_column(MyConst('common_platform_type'), 'value') : $data['base']['apply_terminal'],
            'config'                => '',
        ];
    }

    /**
     * 支付方式列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PaymentList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        if(isset($params['is_enable']))
        {
            $where['is_enable'] = intval($params['is_enable']);
        }
        if(isset($params['is_open_user']))
        {
            $where['is_open_user'] = intval($params['is_open_user']);
        }
        $field = empty($params['field']) ? 'id,logo,name,sort,payment,config,apply_terminal,apply_terminal_old,element,is_enable,is_open_user' : $params['field'];

        return self::DataListHandle(Db::name('Payment')->where($where)->field($field)->order('sort asc')->select()->toArray());
    }

    /**
     * 获取支付方式数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-16
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PaymentData($params = [])
    {
        // 获取支付数据
        $res = self::PaymentList($params);
        $data = empty($res) || empty($res[0]) ? [] : $res[0];

        // 支付方式数据钩子
        $hook_name = 'plugins_service_payment_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-16
     * @desc    description
     * @param   [array]          $data [支付方式列表]
     */
    public static function DataListHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as &$v)
            {
                if(array_key_exists('logo', $v))
                {
                    $v['logo_old'] = $v['logo'];
                    $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
                }
                if(array_key_exists('element', $v))
                {
                    $v['element'] = empty($v['element']) ? '' : json_decode($v['element'], true);
                }
                if(array_key_exists('config', $v))
                {
                    $v['config'] = empty($v['config']) ? '' : json_decode($v['config'], true);
                }
                if(array_key_exists('apply_terminal', $v))
                {
                    $v['apply_terminal'] = empty($v['apply_terminal']) ? '' : json_decode($v['apply_terminal'], true);
                }
                if(array_key_exists('apply_terminal_old', $v))
                {
                    $v['apply_terminal_old'] = empty($v['apply_terminal_old']) ? '' : json_decode($v['apply_terminal_old'], true);
                }
            }
        }
        return $data;
    }

    /**
     * 获取支付方式列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    下订单根据终端自动筛选支付方式
     * @param   [array]          $params [输入参数]
     */
    public static function BuyPaymentList($params = [])
    {
        $res = self::PaymentList($params);
        $data = [];
        if(!empty($res))
        {
            foreach($res as $v)
            {
                // 根据终端类型筛选
                if(in_array(APPLICATION_CLIENT_TYPE, $v['apply_terminal']))
                {
                    unset($v['config'], $v['element'], $v['apply_terminal'], $v['author'], $v['author_url'], $v['is_open_user'], $v['is_enable']);
                    $data[] = $v;
                }
            }
        }

        // 支付方式下单选择列表钩子
        $hook_name = 'plugins_service_payment_buy_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 获取订单支付名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int|array]      $business_ids       [业务订单id]
     */
    public static function OrderPaymentName($business_ids = 0)
    {
        if(empty($business_ids))
        {
            return null;
        }

        // 参数处理查询数据
        if(is_array($business_ids))
        {
            $business_ids = array_filter(array_unique($business_ids));
        }
        if(!empty($business_ids))
        {
            $res = Db::name('PayLog')->alias('pl')->join('pay_log_value plv', 'pl.id=plv.pay_log_id')->where(['plv.business_id'=>$business_ids])->order('pl.id desc')->field('plv.business_id,pl.payment_name')->select()->toArray();
            $data = [];
            if(!empty($res) && is_array($res))
            {
                foreach($res as $v)
                {
                    if(!array_key_exists($v['business_id'], $data))
                    {
                        $data[$v['business_id']] = $v['payment_name'];
                    }
                }
            }
        }

        // id数组则直接返回
        if(is_array($business_ids))
        {
            return empty($data) ? [] : $data;
        }
        return (!empty($data) && is_array($data) && array_key_exists($business_ids, $data)) ? $data[$business_ids] : null;
    }

    /**
     * 数据保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PaymentSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,60',
                'error_msg'         => MyLang('common_service.payment.form_item_name_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_terminal',
                'error_msg'         => MyLang('common_service.payment.form_item_apply_terminal_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '3',
                'error_msg'         => MyLang('form_sort_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $info = Db::name('Payment')->where(['id'=>intval($params['id'])])->find();
        if(empty($info))
        {
            return DataReturn(MyLang('no_data'), -1);
        }

        // 安全判断
        $ret = self::PaymentLegalCheck($info['payment']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 附件
        $data_fields = ['logo'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'              => $params['name'],
            'apply_terminal'    => empty($params['apply_terminal']) ? '' : json_encode(explode(',', $params['apply_terminal'])),
            'logo'              => $attachment['data']['logo'],
            'config'            => json_encode(self::GetPluginsConfig($params)),
            'sort'              => intval($params['sort']),
            'is_enable'         => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'is_open_user'      => isset($params['is_open_user']) ? intval($params['is_open_user']) : 0,
        ];

        $data['upd_time'] = time();
        if(Db::name('Payment')->where(['id'=>$info['id']])->update($data))
        {
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100); 
    }

    /**
     * 支付方式安全判断
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-05-26
     * @desc    description
     * @param   [string]          $payment [支付方式标识]
     */
    public static function PaymentLegalCheck($payment)
    {
        if(RequestModule() == 'admin')
        {
            $key = 'payment_legal_check_'.$payment;
            $ret = MyCache($key);
            if(empty($ret))
            {
                $config = self::GetPaymentConfig($payment);
                if(empty($config))
                {
                    return DataReturn(MyLang('common_service.pluginsupgrade.payment_config_error_tips'), -1);
                }
                $check_params = [
                    'type'      => 'payment',
                    'config'    => $config['base'],
                    'plugins'   => $payment,
                    'author'    => $config['base']['author'],
                    'ver'       => $config['base']['version'], 
                ];
                $ret = StoreService::PluginsLegalCheck($check_params);
                MyCache($key, $ret, 3600);
            }
            if(!in_array($ret['code'], [0, -9999]))
            {
                return $ret;
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 支付插件配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function GetPluginsConfig($params = [])
    {
        $data = [];
        foreach($params as $k=>$v)
        {
            if(substr($k, 0, 8) == 'plugins_')
            {
                $data[substr($k, 8)] = $v;
            }
        }
        return $data;
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PaymentStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Payment')->where(['payment'=>$params['id']])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 权限校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-29T00:01:49+0800
     */
    private static function PowerCheck()
    {
        // 入口文件目录
        if(!is_writable(self::$dir_root_path))
        {
            return DataReturn(MyLang('common_service.payment.dir_no_power_tips').'['.self::$dir_root_path.']', -3);
        }

        // 插件权限
        if(!is_writable(self::$payment_dir))
        {
            return DataReturn(MyLang('common_service.payment.dir_no_power_tips').'['.self::$payment_dir.']', -4);
        }

        return DataReturn(MyLang('check_success'), 0);
    }

    /**
     * 上传插件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Upload($params = [])
    {
        // 文件上传校验
        $error = FileUploadError('file');
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 文件格式化校验
        $type = ResourcesService::ZipExtTypeList();
        if(!in_array($_FILES['file']['type'], $type))
        {
            return DataReturn(MyLang('form_upload_zip_message'), -2);
        }

        // 上传处理
        return self::UploadHandle($_FILES['file']['tmp_name'], $params);
    }

    /**
     * 上传插件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [string]         $package_file [软件包地址]
     * @param   [array]          $params       [输入参数]
     */
    public static function UploadHandle($package_file, $params = [])
    {
        // 初始化
        self::Init();

        // 权限
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource !== true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }

        $success = 0;
        $error = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && strpos($file, '__') === false)
            {
                // 忽略非php文件
                if(substr($file, -4) != '.php')
                {
                    $error++;
                    continue;
                }

                // 文件名称
                $payment = str_replace(array('.', '/', '\\', ':'), '', substr($file, 0, -4));

                // 是否已有存在插件
                if(file_exists(self::$payment_dir.$payment))
                {
                    $error++;
                    continue;
                }

                // 如果不是目录则写入文件
                $new_file = self::$payment_dir.$payment.'.php';
                if(!is_dir($new_file))
                {
                    // 读取这个文件
                    $stream = $zip->getStream($file);
                    if($stream !== false)
                    {
                        $file_content = stream_get_contents($stream);
                        if($file_content !== false && strpos($file_content, 'eval(') === false)
                        {
                            if(@file_put_contents($new_file, $file_content) !== false)
                            {
                                // 文件校验
                                $config = self::GetPaymentConfig($payment);
                                if($config === false)
                                {
                                    $error++;
                                    @unlink($new_file);
                                } else {
                                    // 安全验证
                                    $ret = self::PaymentLegalCheck($payment);
                                    if($ret['code'] != 0)
                                    {
                                        @unlink($new_file);
                                        $zip->close();
                                        return $ret;
                                    }

                                    // 安装成功
                                    $success++;
                                }
                            }
                        }
                        fclose($stream);
                    }
                }
            }
        }
        // 关闭zip  
        $zip->close();

        if($success > 0)
        {
            return DataReturn(MyLang('common_service.payment.upload_success_tips', ['success'=>$success, 'error'=>$error]), 0);
        }
        return DataReturn(MyLang('common_service.payment.upload_fail_tips', ['error'=>$error]), -10);
    }

    /**
     * 安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Install($params = [])
    {
        // 初始化
        self::Init();

        // 参数
        if(empty($params['id']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }

        // 数据处理
        $payment = $params['id'];
        $config = self::GetPaymentConfig($payment);
        if($config !== false)
        {
            $data = self::DataAnalysis($config);
            $apply_terminal = empty($data['apply_terminal']) ? '' : json_encode($data['apply_terminal']);
            $data['payment'] = $payment;
            $data['element'] = empty($data['element']) ? '' : json_encode($data['element']);
            $data['apply_terminal_old'] = $apply_terminal;
            $data['apply_terminal'] = $apply_terminal;
            $data['sort'] = 0;
            $data['add_time'] = time();

            // 移除多余的字段
            unset($data['is_install']);

            // 开启事务
            Db::startTrans();
            if(Db::name('Payment')->insertGetId($data) > 0)
            {
                // 提交事务
                Db::commit();

               return DataReturn(MyLang('install_success'), 0); 
            } else {
                // 事务回滚
                Db::rollback();
                return DataReturn(MyLang('install_fail'), -100);
            }
        } else {
            return DataReturn(MyLang('plugins_config_error_tips'), -10);
        }
    }

    /**
     * 删除插件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Delete($params = [])
    {
        // 初始化
        self::Init();

        // 权限
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 参数
        if(empty($params['id']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }

        // 是否禁止删除
        $payment = $params['id'];
        if(in_array($payment, self::$cannot_deleted_list))
        {
            return DataReturn(MyLang('common_service.payment.payment_not_allow_delete_tips'), -10);
        }

        // 是否存在
        $file = self::$payment_dir.$payment.'.php';
        if(!file_exists($file))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -2);
        }

        // 权限
        if(!is_writable($file))
        {
            return DataReturn(MyLang('common_service.payment.file_no_power_tips').'['.$file.']', -3);
        }

        // 删除
        if(!@unlink($file))
        {
            return DataReturn(MyLang('delete_fail'), -100);
        }

        // 删除入口文件
        self::PaymentEntranceDelete(['payment' => $payment]);

        return DataReturn(MyLang('delete_success'), 0);
    }

    /**
     * 卸载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Uninstall($params = [])
    {
        // 参数
        if(empty($params['id']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }

        // 初始化
        self::Init();

        // 开始卸载
        $payment = $params['id'];
        if(Db::name('Payment')->where(['payment'=>$payment])->delete())
        {
            // 删除入口文件
            self::PaymentEntranceDelete(['payment' => $payment]);

            return DataReturn(MyLang('uninstall_success'), 0);
        }
        return DataReturn(MyLang('uninstall_fail'), -100);
    }

    /**
     * 入口文件创建
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T23:38:52+0800
     * @param    [array]            $params                 [输入参数]
     * @param    [string]           $params['payment']      [支付唯一标记]
     * @param    [array]            $params['business']     [处理业务, 默认配置文件读取]
     * @param    [array]            $params['not_notify']   [不生成异步入口]
     * @param    [string]           $params['respond']      [同步参数值]
     * @param    [string]           $params['notify']       [异步参数值]
     */
    public static function PaymentEntranceCreated($params = [])
    {
        // 初始化
        self::Init();

        // 参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'payment',
                'error_msg'         => MyLang('common_service.payment.create_payment_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'respond',
                'error_msg'         => MyLang('common_service.payment.create_respond_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'notify',
                'error_msg'         => MyLang('common_service.payment.create_notify_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 权限
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 获取地址所属模块名称、地址标记去除模块名称
        $module_notify = 'index';
        $module_respond = 'index';
        if(substr($params['notify'], 0, 5) == '/api/')
        {
            $module_notify = 'api';
            $params['notify'] = substr($params['notify'], 5);
        } else {
            $params['notify'] = substr($params['notify'], 7);
        }
        if(substr($params['respond'], 0, 5) == '/api/')
        {
            $module_respond = 'api';
            $params['respond'] = substr($params['respond'], 5);
        } else {
            $params['respond'] = substr($params['respond'], 7);
        }

        // 不生成异步入口
        $not_notify = empty($params['not_notify']) ? MyConfig('shopxo.under_line_list') : $params['not_notify'];

        // 处理业务
        $business_all = empty($params['business']) ? self::$payment_business_type_all : $params['business'];

        // 系统类型
        $system_type = SystemService::SystemTypeValue();

        // 批量创建
        foreach($business_all as $v)
        {
            $business_name = strtolower($v['name']);
            if(defined('IS_ROOT_ACCESS'))
            {
// 异步
$notify=<<<php
<?php

/**
 * {$v['desc']}支付异步入口
 */
namespace think;

// 默认绑定模块
\$_GET['s'] = '{$params["notify"]}';

// 指定系统类型
define('SYSTEM_TYPE', '{$system_type}');

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 根目录入口
define('IS_ROOT_ACCESS', true);

// 引入公共入口文件
require __DIR__.'/public/core.php';

// 加载基础文件
require __DIR__ . '/vendor/autoload.php';

// 执行HTTP应用并响应
\$http = (new App())->http;
\$response = \$http->name('{$module_notify}')->run();
\$response->send();
\$http->end(\$response);
?>
php;

// 同步
$respond=<<<php
<?php

/**
 * {$v['desc']}支付同步入口
 */
namespace think;

// 默认绑定模块
\$_GET['s'] = '{$params["respond"]}';

// 指定系统类型
define('SYSTEM_TYPE', '{$system_type}');

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 根目录入口
define('IS_ROOT_ACCESS', true);

// 引入公共入口文件
require __DIR__.'/public/core.php';

// 加载基础文件
require __DIR__ . '/vendor/autoload.php';

// 执行HTTP应用并响应
\$http = (new App())->http;
\$response = \$http->name('{$module_respond}')->run();
\$response->send();
\$http->end(\$response);
?>
php;

            } else {

// 异步
$notify=<<<php
<?php

/**
 * {$v['desc']}支付异步入口
 */
namespace think;

// 默认绑定模块
\$_GET['s'] = '{$params["notify"]}';

// 指定系统类型
define('SYSTEM_TYPE', '{$system_type}');

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 引入公共入口文件
require __DIR__.'/core.php';

// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
\$http = (new App())->http;
\$response = \$http->name('{$module_notify}')->run();
\$response->send();
\$http->end(\$response);
?>
php;

// 同步
$respond=<<<php
<?php

/**
 * {$v['desc']}支付同步入口
 */
namespace think;

// 默认绑定模块
\$_GET['s'] = '{$params["respond"]}';

// 指定系统类型
define('SYSTEM_TYPE', '{$system_type}');

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 引入公共入口文件
require __DIR__.'/core.php';

// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
\$http = (new App())->http;
\$response = \$http->name('{$module_respond}')->run();
\$response->send();
\$http->end(\$response);
?>
php;
            }
 
            // 文件名称
            $file = self::EntranceFileData($params['payment'], $business_name);

            // 同步文件
            @file_put_contents(self::$dir_root_path.$file['respond'], $respond);

            // 线下支付不生成异步入口文件
            if(!in_array($params['payment'], $not_notify))
            {
                @file_put_contents(self::$dir_root_path.$file['notify'], $notify);
            }
        }

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * [PaymentEntranceDelete 入口文件删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T23:38:52+0800
     * @param    [array]            $params                 [输入参数]
     * @param    [string]           $params['payment']      [支付唯一标记]
     * @param    [array]            $params['business']     [处理业务, 默认配置文件读取]
     */
    public static function PaymentEntranceDelete($params = [])
    {
        // 初始化
        self::Init();

        // 权限
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }
        // 标识是否为空
        if(empty($params['payment']))
        {
            return DataReturn(MyLang('common_service.payment.create_payment_empty_tips'), -1);
        }

        // 处理业务
        $business_all = empty($params['business']) ? self::$payment_business_type_all : $params['business'];
        foreach($business_all as $v)
        {
            $file = self::EntranceFileData($params['payment'], $v['name']);
            if(file_exists(self::$dir_root_path.$file['notify']))
            {
                @unlink(self::$dir_root_path.$file['notify']);
            }
            if(file_exists(self::$dir_root_path.$file['respond']))
            {
                @unlink(self::$dir_root_path.$file['respond']);
            }
        }

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 入口文件信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-26
     * @desc    description
     * @param   [string]          $payment [支付标记]
     * @param   [string]          $name    [支付业务方式名称]
     */
    public static function EntranceFileData($payment, $name)
    {
        // 系统类型
        $system_type = SystemService::SystemTypeValue();

        // 地址路径名称
        $dir = 'payment_'.$system_type.'_'.strtolower($name).'_'.strtolower($payment);
        return [
            'respond'  => $dir.'_respond.php',
            'notify'  => $dir.'_notify.php',
        ];
    }

    /**
     * 入口文件检查
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-26
     * @desc    description
     * @param   [string]          $payment [支付标记]
     * @param   [string]          $name    [支付业务方式名称]
     */
    public static function EntranceFileChecked($payment, $name)
    {
        // 文件名称
        $file = self::EntranceFileData($payment, $name);

        // 统一返回数据
        $result = [
            'respond'   => __MY_URL__.$file['respond'],
            'notify'    => __MY_URL__.$file['notify'],
        ];

        // 同步返回文件
        if(!file_exists(self::$dir_root_path.$file['respond']))
        {
            return DataReturn(MyLang('common_service.payment.pay_respond_file_no_exist_tips'), -10, $result);
        }

        // 线下支付不生成异步入口文件
        if(!in_array($payment, MyConfig('shopxo.under_line_list')))
        {
            if(!file_exists(self::$dir_root_path.$file['notify']))
            {
                return DataReturn(MyLang('common_service.payment.pay_notify_file_no_exist_tips'), -11, $result);
            }
        }
        return DataReturn(MyLang('check_success'), 0, $result);
    }

    /**
     * 支付插件更新信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PaymentUpgradeInfo($params = [])
    {
        if(!empty($params))
        {
            // 数据处理
            $data = [];
            foreach($params as $v)
            {
                if(!empty($v['name']) && !empty($v['version']) && !empty($v['payment']) && !empty($v['author']))
                {
                    $data[] = [
                        'plugins'   => $v['payment'],
                        'name'      => $v['name'],
                        'ver'       => $v['version'],
                        'author'    => $v['author'],
                    ];
                }
            }
            if(!empty($data))
            {
                // 获取更新信息
                $request_params = [
                    'plugins_type'  => 'payment',
                    'plugins_data'  => $data,
                ];
                $res = StoreService::PluginsUpgradeInfo($request_params);
                if(!empty($res['data']) && is_array($res['data']))
                {
                    $res['data'] = array_column($res['data'], null, 'plugins');
                }
                return $res;
            }
        }
        return DataReturn(MyLang('plugins_no_data_tips'), 0);
    }

    /**
     * 购买默认支付方式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-07-31
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BuyDefaultPayment($params = [])
    {
        $payment_id = 0;
        if(empty($params) || empty($params['payment_id']))
        {
            $default_payment = MyC('common_default_payment');
            if(!empty($default_payment) && !empty($default_payment[APPLICATION_CLIENT_TYPE]))
            {
                $where = [
                    ['payment', '=', $default_payment[APPLICATION_CLIENT_TYPE]],
                    ['is_enable', '=', 1],
                    ['is_open_user', '=', 1],
                ];
                $payment_id = Db::name('Payment')->where($where)->value('id');
            }
        } else {
            $payment_id = $params['payment_id'];
        }
        return $payment_id;
    }

    /**
     * 应用市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PaymentMarket($params = [])
    {
        $params['type'] = 'payment';
        return StoreService::PackageDataList($params);
    }
}
?>