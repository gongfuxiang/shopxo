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

/**
 * 支付请求日志服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PayRequestLogService
{
    /**
     * 支付响应日志添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-23
     * @desc    description
     * @param   [string]         $business_type    [业务类型名称]
     */
    public static function PayRequestLogInsert($business_type)
    {
        // 请求参数
        if(empty($business_type))
        {
            return DataReturn(MyLang('common_service.payrequestlog.save_business_type_empty_tips'), -1);
        }

        // 行为驱动
        $behavior_obj = new \base\Behavior();

        // 输入参数
        $params = file_get_contents("php://input");
        if(empty($params))
        {
            $params = array_merge($_GET, $_POST);
        }

        // 日志主数据
        $data = [
            'business_type'         => $business_type,
            'request_params'        => empty($params) ? '' : (is_array($params) ? json_encode($params, JSON_UNESCAPED_UNICODE) : htmlspecialchars_decode($params)),
            'response_data'         => '',
            'business_handle'       => '',
            'request_url'           => $behavior_obj->GetUrl('request'),
            'server_port'           => $behavior_obj->GetServerPort(),
            'server_ip'             => $behavior_obj->GetServerIP(),
            'client_ip'             => $behavior_obj->GetClientIP(),
            'os'                    => $behavior_obj->GetOs(),
            'browser'               => $behavior_obj->GetBrowser(),
            'method'                => $behavior_obj->GetMethod(),
            'scheme'                => $behavior_obj->GetScheme(),
            'version'               => $behavior_obj->GetHttpVersion(),
            'client'                => $behavior_obj->GetClinet(),
            'add_time'              => time(),
        ];
        $log_id = Db::name('PayRequestLog')->insertGetId($data);
        if($log_id > 0)
        {
            return DataReturn(MyLang('insert_success'), 0, $log_id);
        }
        return DataReturn(MyLang('common_service.payrequestlog.pay_request_log_insert_fail_tips'), -100);
    }

    /**
     * 支付响应日志添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-23
     * @desc    description
     * @param   [int]           $log_id [日志id]
     * @param   [array]         $data   [业务处理结果]
     * @param   [string]        $res    [响应数据]
     */
    public static function PayRequestLogEnd($log_id, $data, $res)
    {
        $data = [
            'business_handle'   => empty($data) ? '' : (is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data),
            'upd_time'          => time(),
            'response_data'     => empty($res) ? '' : (is_array($res) ? json_encode($res, JSON_UNESCAPED_UNICODE) : $res),
            'upd_time'          => time(),
        ];
        if(Db::name('PayRequestLog')->where(['id'=>$log_id])->update($data))
        {
            return DataReturn(MyLang('update_success'), 0, $log_id);
        }
        return DataReturn(MyLang('common_service.payrequestlog.pay_request_log_update_fail_tips'), -100);
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function PayRequestLogListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 循环处理数据
            foreach($data as &$v)
            {
                // 时间
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return $data;
    }
}
?>