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
namespace app\service;

use think\Db;
use app\service\ResourcesService;

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
        self::$payment_business_type_all = config('shopxo.payment_business_type_all');

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
     */
    public static function PlugPaymentList()
    {
        // 初始化
        self::Init();

        // 开始处理
        $data = [];
        if(is_dir(self::$payment_dir))
        {
            if($dh = opendir(self::$payment_dir))
            {
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
                                $temp['apply_terminal'] = $db_config[0]['apply_terminal'];
                                $temp['config'] = $db_config[0]['config'];
                                $temp['is_enable'] = $db_config[0]['is_enable'];
                                $temp['is_open_user'] = $db_config[0]['is_open_user'];
                            }
                            $data[] = $temp;
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $data;
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
    private static function GetPaymentConfig($payment)
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
            'name'          => isset($data['base']['name']) ? htmlentities($data['base']['name']) : '',
            'version'       => isset($data['base']['version']) ? htmlentities($data['base']['version']) : '',
            'apply_version' => isset($data['base']['apply_version']) ? htmlentities($data['base']['apply_version']) : '',
            'desc'          => isset($data['base']['desc']) ? $data['base']['desc'] : '',
            'author'        => isset($data['base']['author']) ? htmlentities($data['base']['author']) : '',
            'author_url'    => isset($data['base']['author_url']) ? htmlentities($data['base']['author_url']) : '',
            'element'       => isset($data['element']) ? $data['element'] : [],

            'logo'          => '',
            'is_enable'     => 0,
            'is_open_user'  => 0,
            'is_install'    => 0,
            'apply_terminal'=> empty($data['base']['apply_terminal']) ? array_column(lang('common_platform_type'), 'value') : $data['base']['apply_terminal'],
            'config'        => '',
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

        $data = Db::name('Payment')->where($where)->field('id,logo,name,sort,payment,config,apply_terminal,apply_terminal,element,is_enable,is_open_user')->order('sort asc')->select();
        if(!empty($data) && is_array($data))
        {
            foreach($data as &$v)
            {
                $v['logo_old'] = $v['logo'];
                $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
                $v['element'] = empty($v['element']) ? '' : json_decode($v['element'], true);
                $v['config'] = empty($v['config']) ? '' : json_decode($v['config'], true);
                $v['apply_terminal'] = empty($v['apply_terminal']) ? '' : json_decode($v['apply_terminal'], true);
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
        $data = self::PaymentList($params);
        $result = [];
        if(!empty($data))
        {
            foreach($data as $v)
            {
                // 根据终端类型筛选
                if(in_array(APPLICATION_CLIENT_TYPE, $v['apply_terminal']))
                {
                    unset($v['config'], $v['element'], $v['apply_terminal'], $v['author'], $v['author_url'], $v['is_open_user'], $v['is_enable']);
                    $result[] = $v;
                }
            }
        }
        return $result;
    }

    /**
     * 获取订单支付名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int]          $order_id    [订单id]
     * @param   [int]          $payment_id  [支付方式id]
     */
    public static function OrderPaymentName($order_id = 0, $payment_id = 0)
    {
        $name = null;
        if(!empty($order_id))
        {
            $name = Db::name('PayLog')->where(['order_id'=>intval($order_id)])->value('payment_name');
            if(empty($anme) && !empty($payment_id))
            {
                $name = Db::name('Payment')->where(['id'=>intval($payment_id)])->value('name');
            }
        }
        return $name;
    }

    /**
     * 数据更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PaymentUpdate($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,60',
                'error_msg'         => '名称长度 2~60 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_terminal',
                'error_msg'         => '至少选择一个适用终端',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '3',
                'error_msg'         => '顺序 0~255 之间的数值',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 附件
        $data_fields = ['logo'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'              => $params['name'],
            'apply_terminal'    => empty($params['apply_terminal']) ? '' : json_encode(explode(',', $params['apply_terminal'])),
            'logo'              => $attachment['data']['logo'],
            'config'            => json_encode(self::GetPlugConfig($params)),
            'sort'              => intval($params['sort']),
            'is_enable'         => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'is_open_user'      => isset($params['is_open_user']) ? intval($params['is_open_user']) : 0,
        ];

        $data['upd_time'] = time();
        if(Db::name('Payment')->where(['id'=>intval($params['id'])])->update($data))
        {
            return DataReturn('编辑成功', 0);
        }
        return DataReturn('编辑失败', -100); 
    }

    /**
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function GetPlugConfig($params = [])
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => '未指定操作字段',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
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
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
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
            return DataReturn('目录没有操作权限'.'['.self::$dir_root_path.']', -3);
        }

        // 插件权限
        if(!is_writable(self::$payment_dir))
        {
            return DataReturn('目录没有操作权限'.'['.self::$payment_dir.']', -4);
        }

        return DataReturn('验证成功', 0);
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
        // 初始化
        self::Init();

        // 权限
        $ret = self::PowerCheck();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 文件上传校验
        $error = FileUploadError('file');
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 文件格式化校验
        $type = array('application/zip', 'application/octet-stream', 'application/x-zip-compressed');
        if(!in_array($_FILES['file']['type'], $type))
        {
            return DataReturn('文件格式有误，请上传zip压缩包', -2);
        }

        // 开始解压文件
        $resource = zip_open($_FILES['file']['tmp_name']);
        if(!is_resource($resource))
        {
            return DataReturn('压缩包打开失败['.$resource.']', -10);
        }

        $success = 0;
        $error = 0;
        while(($temp_resource = zip_read($resource)) !== false)
        {
            if(zip_entry_open($resource, $temp_resource))
            {
                // 当前压缩包中项目名称
                $file = zip_entry_name($temp_resource);

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
                    } else {
                        $file = self::$payment_dir.$payment.'.php';
                    }

                    // 如果不是目录则写入文件
                    if(!is_dir($file))
                    {
                        // 读取这个文件
                        $file_size = zip_entry_filesize($temp_resource);
                        $file_content = zip_entry_read($temp_resource, $file_size);
                        if(@file_put_contents($file, $file_content) !== false)
                        {
                            // 文件校验
                            $config = self::GetPaymentConfig($payment);
                            if($config === false)
                            {
                                $error++;
                                @unlink($file);
                            } else {
                                $success++;
                            }
                        }
                    }
                }
            }
        }

        if($success > 0)
        {
            return DataReturn('上传成功[成功'.$success.'个, 失败'.$error.'个]', 0);
        }
        return DataReturn('上传失败'.$error.'个', -10);
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
            return DataReturn('参数错误', -1);
        }

        // 数据处理
        $payment = $params['id'];
        $config = self::GetPaymentConfig($payment);
        if($config !== false)
        {
            $data = self::DataAnalysis($config);
            $data['payment'] = $payment;
            $data['element'] = empty($data['element']) ? '' : json_encode($data['element']);
            $data['apply_terminal'] = empty($data['apply_terminal']) ? '' : json_encode($data['apply_terminal']);
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

               return DataReturn('安装成功'); 
            } else {
                // 事务回滚
                Db::rollback();
                return DataReturn('安装失败', -100);
            }
        } else {
            return DataReturn('插件配置有误', -10);
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
            return DataReturn('参数错误', -1);
        }

        // 是否禁止删除
        $payment = $params['id'];
        if(in_array($payment, self::$cannot_deleted_list))
        {
            return DataReturn('该支付方式禁止删除', -10);
        }

        // 是否存在
        $file = self::$payment_dir.$payment.'.php';
        if(!file_exists($file))
        {
            return DataReturn('资源不存在或已被删除', -2);
        }

        // 权限
        if(!is_writable($file))
        {
            return DataReturn('没操作权限['.$file.']', -3);
        }

        // 删除
        if(!@unlink($file))
        {
            return DataReturn('删除失败', -100);
        }

        // 删除入口文件
        self::PaymentEntranceDelete(['payment' => $payment]);

        return DataReturn('删除成功');
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
            return DataReturn('参数错误', -1);
        }

        // 初始化
        self::Init();

        // 开始卸载
        $payment = $params['id'];
        if(db('Payment')->where(['payment'=>$payment])->delete())
        {
            // 删除入口文件
            self::PaymentEntranceDelete(['payment' => $payment]);

            return DataReturn('卸载成功', 0);
        }
        return DataReturn('卸载失败', -100);
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
                'error_msg'         => '支付唯一标记不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'respond',
                'error_msg'         => '支付同步地址参数不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'notify',
                'error_msg'         => '支付异步地址参数不能为空',
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

        if(empty($params['payment']))
        {
            return '支付唯一标记不能为空';
        }

        // 不生成异步入口
        $not_notify = empty($params['not_notify']) ? config('shopxo.under_line_list') : $params['not_notify'];

        // 处理业务
        $business_all = empty($params['business']) ? self::$payment_business_type_all : $params['business'];

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

// 默认绑定模块
\$_GET['s'] = '{$params["notify"]}';

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 根目录入口
define('IS_ROOT_ACCESS', true);

// 引入公共入口文件
require './public/index.php';
?>
php;

// 同步
$respond=<<<php
<?php

/**
 * {$v['desc']}支付同步入口
 */

// 默认绑定模块
\$_GET['s'] = '{$params["respond"]}';

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 根目录入口
define('IS_ROOT_ACCESS', true);

// 引入公共入口文件
require './public/index.php';
?>
php;

            } else {

// 异步
$notify=<<<php
<?php

/**
 * {$v['desc']}支付异步入口
 */

// 默认绑定模块
\$_GET['s'] = '{$params["notify"]}';

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 引入入口文件
require __DIR__.'/index.php';
?>
php;

// 同步
$respond=<<<php
<?php

/**
 * {$v['desc']}支付同步入口
 */

// 默认绑定模块
\$_GET['s'] = '{$params["respond"]}';

// 支付模块标记
define('PAYMENT_TYPE', '{$params["payment"]}');

// 引入入口文件
require __DIR__.'/index.php';
?>
php;
            }

            @file_put_contents(self::$dir_root_path.'payment_'.$business_name.'_'.strtolower($params['payment']).'_respond.php', $respond);

            // 线下支付不生成异步入口文件
            if(!in_array($params['payment'], $not_notify))
            {
                @file_put_contents(self::$dir_root_path.'payment_'.$business_name.'_'.strtolower($params['payment']).'_notify.php', $notify);
            }
        }

        return DataReturn('操作成功', 0);
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
        if(empty($params['payment']))
        {
            return '支付唯一标记不能为空';
        }

        // 处理业务
        $business_all = empty($params['business']) ? self::$payment_business_type_all : $params['business'];

        $payment = strtolower($params['payment']);
        foreach($business_all as $v)
        {
            $business_name = strtolower($v['name']);
            if(file_exists(self::$dir_root_path.'payment_'.$business_name.'_'.$payment.'_notify.php'))
            {
                @unlink(self::$dir_root_path.'payment_'.$business_name.'_'.$payment.'_notify.php');
            }
            if(file_exists(self::$dir_root_path.'payment_'.$business_name.'_'.$payment.'_respond.php'))
            {
                @unlink(self::$dir_root_path.'payment_'.$business_name.'_'.$payment.'_respond.php');
            }
        }

        return DataReturn('操作成功', 0);
    }

    /**
     * 入库文件检查
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
        // 同步返回文件
        if(!file_exists(self::$dir_root_path.'payment_'.strtolower($name).'_'.strtolower($payment).'_respond.php'))
        {
            return DataReturn('支付返回入口文件不存在，请联系管理员处理', -10);
        }

        // 线下支付不生成异步入口文件
        if(!in_array($payment, config('shopxo.under_line_list')))
        {
            if(!file_exists(self::$dir_root_path.'payment_'.strtolower($name).'_'.strtolower($payment).'_notify.php'))
            {
                return DataReturn('支付通知入口文件不存在，请联系管理员处理', -10);
            }
        }
        return DataReturn('校验成功', 0);
    }
}
?>