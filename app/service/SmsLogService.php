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
 * 短信日志数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-04
 * @desc    description
 */
class SmsLogService
{
    /**
     * 短信日志添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-11-19
     * @desc    description
     * @param   [string]          $platform         [平台类型]
     * @param   [string]          $mobile           [手机号码]
     * @param   [string]          $sign_name        [短信签名]
     * @param   [string]          $template_value   [短信模板内容]
     * @param   [string|array]    $template_var     [短信模板变量]
     * @param   [string]          $request_url      [请求接口url地址]
     * @param   [string|array]    $request_params   [请求接口参数]
     */
    public static function SmsLogAdd($platform, $mobile, $sign_name, $template_value, $template_var = '', $request_url = '', $request_params = '')
    {
        $data = [
            'status'            => 0,
            'platform'          => empty($platform) ? '' : $platform,
            'mobile'            => empty($mobile) ? '' : (is_array($mobile) ? implode(', ', $mobile) : $mobile),
            'sign_name'         => empty($sign_name) ? '' : $sign_name,
            'request_url'       => empty($request_url) ? '' : $request_url,
            'template_value'    => empty($template_value) ? '' : $template_value,
            'template_var'      => empty($template_var) ? '' : (is_array($template_var) ? json_encode($template_var, JSON_UNESCAPED_UNICODE) : $template_var),
            'request_params'    => empty($request_params) ? '' : (is_array($request_params) ? json_encode($request_params, JSON_UNESCAPED_UNICODE) : $request_params),
            'add_time'          => time(),
        ];
        $data['id'] = Db::name('SmsLog')->insertGetId($data);
        if($data['id'] > 0)
        {
            // 短信添加钩子
            $hook_name = 'plugins_service_sms_log_add';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => $data,
                'data_id'       => $data['id'],
            ]);
            return DataReturn(MyLang('insert_success'), 0, $data);
        }
        return DataReturn(MyLang('insert_fail'), -1);
    }

    /**
     * 短信回调
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-11-19
     * @desc    description
     * @param   [int]          $log_id        [日志id]
     * @param   [int]          $status        [发送状态（0未发送，1已发送，2已失败）]
     * @param   [string|array] $response_data [请求接口返回数据]
     * @param   [int]          $tsc           [耗时（秒）]
     * @param   [string]       $reason        [失败原因]
     */
    public static function SmsLogResponse($log_id, $status, $response_data, $tsc, $reason = '')
    {
        return Db::name('SmsLog')->where(['id'=>$log_id])->update([
            'status'         => intval($status),
            'response_data'  => empty($response_data) ? '' : (is_array($response_data) ? json_encode($response_data, JSON_UNESCAPED_UNICODE) : $response_data),
            'tsc'            => intval($tsc),
            'reason'         => $reason,
            'upd_time'       => time(),
        ]);
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SmsLogDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('SmsLog')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 清空全部
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SmsLogAllDelete($params = [])
    {
        $where = [
            ['id', '>', 0]
        ];
        if(Db::name('SmsLog')->where($where)->delete() === false)
        {
            return DataReturn(MyLang('operate_fail'), -100);
        }
        return DataReturn(MyLang('operate_success'));
    }
}
?>