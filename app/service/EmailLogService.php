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
 * 邮件日志数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-04
 * @desc    description
 */
class EmailLogService
{
    /**
     * 邮件日志添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-11-19
     * @desc    description
     * @param   [string]          $smtp_host        [SMTP服务器]
     * @param   [string]          $smtp_port        [SMTP端口]
     * @param   [string]          $smtp_name        [邮箱用户名]
     * @param   [string]          $smtp_account     [发信人邮件]
     * @param   [string]          $smtp_send_name   [发件人姓名]
     * @param   [string|array]    $email            [收件邮箱]
     * @param   [string]          $title            [邮件标题]
     * @param   [string]          $template_value   [邮件内容]
     * @param   [string|array]    $template_var     [邮件变量]
     */
    public static function EmailLogAdd($smtp_host, $smtp_port, $smtp_name, $smtp_account, $smtp_send_name, $email, $title, $template_value, $template_var = '')
    {
        $data = [
            'status'          => 0,
            'smtp_host'       => empty($smtp_host) ? '' : $smtp_host,
            'smtp_port'       => empty($smtp_port) ? '' : $smtp_port,
            'smtp_name'       => empty($smtp_name) ? '' : $smtp_name,
            'smtp_account'    => empty($smtp_account) ? '' : $smtp_account,
            'smtp_send_name'  => empty($smtp_send_name) ? '' : $smtp_send_name,
            'email'           => empty($email) ? '' : (is_array($email) ? implode(', ', $email) : $email),
            'title'           => empty($title) ? '' : $title,
            'template_value'  => empty($template_value) ? '' : $template_value,
            'template_var'    => empty($template_var) ? '' : (is_array($template_var) ? json_encode($template_var, JSON_UNESCAPED_UNICODE) : $template_var),
            'add_time'        => time(),
        ];
        $data['id'] = Db::name('EmailLog')->insertGetId($data);
        if($data['id'] > 0)
        {
            // 邮件添加钩子
            $hook_name = 'plugins_service_email_log_add';
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
     * 邮件回调
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-11-19
     * @desc    description
     * @param   [int]          $log_id        [日志id]
     * @param   [int]          $status        [发送状态（0未发送，1已发送，2已失败）]
     * @param   [int]          $tsc           [耗时（秒）]
     * @param   [string]       $reason        [失败原因]
     */
    public static function EmailLogResponse($log_id, $status, $tsc, $reason = '')
    {
        return Db::name('EmailLog')->where(['id'=>$log_id])->update([
            'status'    => intval($status),
            'tsc'       => intval($tsc),
            'reason'    => $reason,
            'upd_time'  => time(),
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
    public static function EmailLogDelete($params = [])
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
        if(Db::name('EmailLog')->where(['id'=>$params['ids']])->delete())
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
    public static function EmailLogAllDelete($params = [])
    {
        $where = [
            ['id', '>', 0]
        ];
        if(Db::name('EmailLog')->where($where)->delete() === false)
        {
            return DataReturn(MyLang('operate_fail'), -100);
        }
        return DataReturn(MyLang('operate_success'));
    }
}
?>