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

use app\service\ResourcesService;
use app\service\AppMiniUserService;

/**
 * 二维码生成服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-02-06
 * @desc    description
 */
class QrCodeService
{
    /**
     * 二维码生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]      $params               [输入参数]
     * @param   [string]     $params['platform']   [所属平台]
     * @param   [string]     $params['path']       [存储路径]
     * @param   [string]     $params['url']        [url地址]
     * @param   [string]     $params['filename']   [文件名称]
     */
    public static function QrCodeCreate($params = [])
    {
        // 参数
        if(empty($params['platform']) || empty($params['path']) || empty($params['url']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }
        $params['query'] = empty($params['query']) ? '' : $params['query'];

        // 是否指定文件名称，则自动生成
        if(empty($params['filename']))
        {
            $params['filename'] = md5($params['path'].$params['query']).'.png';
        }
        // 存储路径
        if(empty($params['root_path']))
        {
            $params['root_path'] = ROOT.'public';
        }
        // 路径第一个字符不为路径分割则增加
        if(substr($params['path'], 0, 1) != DS)
        {
            $params['path'] = DS.$params['path'];
        }
        $params['dir'] = $params['root_path'].$params['path'].$params['filename'];

        // 目录不存在则创建
        if(\base\FileUtil::CreateDir(ROOT.'public'.DS.$params['path']) !== true)
        {
            return DataReturn(MyLang('common_service.qrcode.dir_create_fail'), -1);
        }

        // 不存在则创建、或者强制创建
        if(!file_exists($params['dir']) || (isset($params['is_force']) && $params['is_force'] == 1))
        {
            // 根据客户端类型生成不同的二维码
            switch($params['platform'])
            {
                // 微信小程序
                case 'weixin' :
                    $ret = self::CreateMiniWechatQrcode($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                    break;

                // QQ小程序
                case 'qq' :
                    $ret = self::CreateMiniQQQrcode($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                    break;

                // 支付宝小程序
                case 'alipay' :
                    $ret = self::CreateMiniAlipayQrcode($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                    break;

                // 头条小程序
                case 'toutiao' :
                    $ret = self::CreateMiniToutiaoQrcode($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                    break;

                // 百度小程序
                case 'baidu' :
                    $ret = self::CreateMiniBaiduQrcode($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                    break;

                // 快手小程序
                case 'kuaishou' :
                    $ret = self::CreateMiniKuaishouQrcode($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                    break;

                // 默认
                default :
                    $ret = self::SiteUrl($params);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
            }
        }
        return DataReturn(MyLang('handle_success'), 0, ResourcesService::AttachmentPathViewHandle($params['path'].$params['filename']));
    }

    /**
     * url地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SiteUrl($params = [])
    {
        // h5端地址处理
        if($params['platform'] == 'h5')
        {
            $h5_url = MyC('common_app_h5_url');
            if(!empty($h5_url))
            {
                $params['url'] = $h5_url.$params['url'].(empty($params['query']) ? '' : '?'.$params['query']);
            }
        }
        // url
        $params['content'] = $params['url'];

        // 创建二维码
        return (new \base\Qrcode())->Create($params);
    }

    /**
     * 快手小程序获取二维码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function CreateMiniKuaishouQrcode($params = [])
    {
        // 配置信息
        $appid = AppMiniUserService::AppMiniConfig('common_app_mini_kuaishou_appid');

        // 二维码内容
        $url = 'kwai://miniapp?appId='.$appid.'&KSMP_source=011012&KSMP_internal_source=011012&path='.urlencode($params['url'].(empty($params['query']) ? '' : '?'.$params['query']));
        $params['content'] = $url;

        // 创建二维码
        $ret = (new \base\Qrcode())->Create($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        return DataReturn(MyLang('get_success'), 0);
    }

    /**
     * 微信小程序获取二维码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function CreateMiniWechatQrcode($params = [])
    {
        // 配置信息
        $appid = AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appid');
        $appsecret = AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appsecret');
        if(empty($appid) || empty($appsecret))
        {
            return DataReturn(MyLang('common_service.qrcode.weixin_config_tips'), -1);
        }

        // 请求参数
        $wx_params = [
            'page'  => $params['url'],
            'scene' => $params['query'],
            'width' => empty($params['width']) ? 300 : intval($params['width']),
        ];
        $obj = new \base\Wechat($appid, $appsecret);
        $ret = $obj->MiniQrCodeCreate($wx_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 保存二维码
        if(@file_put_contents($params['dir'], $ret['data']) !== false)
        {
            return DataReturn(MyLang('get_success'), 0);
        }
        return DataReturn(MyLang('common_service.qrcode.save_fail'), -1);
    }

    /**
     * QQ小程序获取二维码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function CreateMiniQQQrcode($params = [])
    {
        // 配置信息
        $appid = AppMiniUserService::AppMiniConfig('common_app_mini_qq_appid');

        // 二维码内容
        $url = 'https://m.q.qq.com/a/p/'.$appid.'?s='.urlencode($params['url'].(empty($params['query']) ? '' : '?'.$params['query']));
        $params['content'] = $url;

        // 创建二维码
        $ret = (new \base\Qrcode())->Create($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        return DataReturn(MyLang('get_success'), 0);
    }

    /**
     * 支付宝小程序获取二维码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function CreateMiniAlipayQrcode($params = [])
    {
        // 配置信息
        $appid = AppMiniUserService::AppMiniConfig('common_app_mini_alipay_appid');
        if(empty($appid))
        {
            return DataReturn(MyLang('common_service.qrcode.alipay_config_tips'), -1);
        }

        // 请求参数
        $request_params = [
            'appid' => $appid,
            'page'  => $params['url'],
            'scene' => $params['query'],
            'width' => empty($params['width']) ? 300 : intval($params['width']),
        ];
        $ret = (new \base\Alipay())->MiniQrCodeCreate($request_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 保存二维码
        if(@file_put_contents($params['dir'], RequestGet($ret['data'])) !== false)
        {
            return DataReturn(MyLang('get_success'), 0);
        }
        return DataReturn(MyLang('common_service.qrcode.save_fail'), -1);
    }

    /**
     * 头条小程序获取二维码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function CreateMiniToutiaoQrcode($params = [])
    {
        // 配置信息
        $config = [
            'appid'     => AppMiniUserService::AppMiniConfig('common_app_mini_toutiao_appid'),
            'secret'    => AppMiniUserService::AppMiniConfig('common_app_mini_toutiao_appsecret'),
        ];
        if(empty($config['appid']) || empty($config['secret']))
        {
            return DataReturn(MyLang('common_service.qrcode.toutiao_config_tips'), -1);
        }

        // 请求参数
        $request_params = [
            'page'  => $params['url'],
            'scene' => $params['query'],
            'width' => empty($params['width']) ? 300 : intval($params['width']),
        ];
        $ret = (new \base\Toutiao($config))->MiniQrCodeCreate($request_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 保存二维码
        if(@file_put_contents($params['dir'], $ret['data']) !== false)
        {
            return DataReturn(MyLang('get_success'), 0);
        }
        return DataReturn(MyLang('common_service.qrcode.save_fail'), -1);
    }

    /**
     * 百度小程序获取二维码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function CreateMiniBaiduQrcode($params = [])
    {
        // 配置信息
        $config = [
            'appid'     => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_appid'),
            'key'       => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_appkey'),
            'secret'    => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_appsecret'),
        ];
        if(empty($config['appid']) || empty($config['key']) || empty($config['secret']))
        {
            return DataReturn(MyLang('common_service.qrcode.baidu_config_tips'), -1);
        }

        // 请求参数
        $request_params = [
            'page'  => $params['url'],
            'scene' => $params['query'],
            'width' => empty($params['width']) ? 300 : intval($params['width']),
        ];
        $ret = (new \base\Baidu($config))->MiniQrCodeCreate($request_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 保存二维码
        if(@file_put_contents($params['dir'], $ret['data']) !== false)
        {
            return DataReturn(MyLang('get_success'), 0);
        }
        return DataReturn(MyLang('common_service.qrcode.save_fail'), -1);
    }
}
?>