<?php
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luofei614<www.3g4k.com>
// +----------------------------------------------------------------------
namespace Behavior;
/**
 * 升级短信通知， 如果有ThinkPHP新版升级，或者重要的更新，会发送短信通知你。
 * 需要使用SAE的短信服务。请先找一个SAE的应用开通短信服务。
 * 使用步骤如下：
 * 1，在项目的Conf目录下建立tags.php配置文件，内容如下：
 * <code>
 * <?php
 * return array(
 *   'app_init' =>  array('UpgradeNotice')
 * );
 * </code>
 *
 * 2，将此文件放在应用的Lib/Behavior文件夹下。
 *注：在SAE上面使用时，以上两步可以省略 
 * 3，在config.php中配置：
 *  'UPGRADE_NOTICE_ON'=>true,//开启短信升级提醒功能 
 * 'UPGRADE_NOTICE_AKEY'=>'your akey',//SAE应用的AKEY，如果在SAE上使用可以不填
 * 'UPGRADE_NOTICE_SKEY'=>'your skey',//SAE应用的SKEY，如果在SAE上使用可以不填
 *'UPGRADE_NOTICE_MOBILE'=>'136456789',//接受短信的手机号
 *'UPGRADE_NOTICE_CHECK_INTERVAL' => 604800,//检测频率,单位秒,默认是一周
 *'UPGRADE_CURRENT_VERSION'=>'0',//升级后的版本号，会在短信中告诉你填写什么
 *UPGRADE_NOTICE_DEBUG=>true, //调试默认，如果为true，UPGRADE_NOTICE_CHECK_INTERVAL配置不起作用，每次都会进行版本检查，此时用于调试，调试完毕后请设置次配置为false
 *
 */

class UpgradeNoticeBehavior {

    protected $header_ = '';
    protected $httpCode_;
    protected $httpDesc_;
    protected $accesskey_;
    protected $secretkey_;
    public function run(&$params) {
        if (C('UPGRADE_NOTICE_ON') && (!S('think_upgrade_interval') || C('UPGRADE_NOTICE_DEBUG'))) {
            if(IS_SAE && C('UPGRADE_NOTICE_QUEUE') && !isset($_POST['think_upgrade_queque'])){
                $queue=new SaeTaskQueue(C('UPGRADE_NOTICE_QUEUE'));
                $queue->addTask('http://'.$_SERVER['HTTP_HOST'].__APP__,'think_upgrade_queque=1');
                if(!$queue->push()){
                    trace('升级提醒队列执行失败,错误原因：'.$queue->errmsg(), '升级通知出错', 'NOTIC', true);
                }
                return ;
            }
            $akey = C('UPGRADE_NOTICE_AKEY',null,'');
            $skey = C('UPGRADE_NOTICE_SKEY',null,'');
            $this->accesskey_ = $akey ? $akey : (defined('SAE_ACCESSKEY') ? SAE_ACCESSKEY : '');
            $this->secretkey_ = $skey ? $skey : (defined('SAE_SECRETKEY') ? SAE_SECRETKEY : '');
            $current_version = C('UPGRADE_CURRENT_VERSION',null,0);
            //读取接口
            $info = $this->send('http://sinaclouds.sinaapp.com/thinkapi/upgrade.php?v=' . $current_version);
             if ($info['version'] != $current_version) {
                    if($this->send_sms($info['msg']))  trace($info['msg'], '升级通知成功', 'NOTIC', true); //发送升级短信
            }
            S('think_upgrade_interval', true, C('UPGRADE_NOTICE_CHECK_INTERVAL',null,604800));
        }
    }
    private function send_sms($msg) {
        $timestamp=time();
        $url = 'http://inno.smsinter.sina.com.cn/sae_sms_service/sendsms.php'; //发送短信的接口地址
        $content = "FetchUrl" . $url . "TimeStamp" . $timestamp . "AccessKey" . $this->accesskey_;
        $signature = (base64_encode(hash_hmac('sha256', $content, $this->secretkey_, true)));
        $headers = array(
            "FetchUrl: $url",
            "AccessKey: ".$this->accesskey_,
            "TimeStamp: " . $timestamp,
            "Signature: $signature"
        );
        $data = array(
            'mobile' => C('UPGRADE_NOTICE_MOBILE',null,'') ,
            'msg' => $msg,
            'encoding' => 'UTF-8'
        );
        if(!$ret = $this->send('http://g.apibus.io', $data, $headers)){
            return false;
        }
        if (isset($ret['ApiBusError'])) {
            trace('errno:' . $ret['ApiBusError']['errcode'] . ',errmsg:' . $ret['ApiBusError']['errdesc'], '升级通知出错', 'NOTIC', true);
            
            return false;
        }
        
        return true;
    }
    private function send($url, $params = array() , $headers = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $txt = curl_exec($ch);
        if (curl_errno($ch)) {
            trace(curl_error($ch) , '升级通知出错', 'NOTIC', true);
            
            return false;
        }
        curl_close($ch);
        $ret = json_decode($txt, true);
        if (!$ret) {
            trace('接口[' . $url . ']返回格式不正确', '升级通知出错', 'NOTIC', true);
            
            return false;
        }
        
        return $ret;
    }
}
