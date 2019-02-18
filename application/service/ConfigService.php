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
 * 配置服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ConfigService
{
    /**
     * 配置列表，唯一标记作为key
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigList($params = [])
    {
        $field = isset($params['field']) ? $params['field'] : 'only_tag,name,describe,value,error_tips';
        return Db::name('Config')->column($field);
    }

    /**
     * 配置数据保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-02T23:08:19+0800
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigSave($params = [])
    {
        // 参数校验
        if(empty($params))
        {
            return DataReturn('参数不能为空', -1);
        }

        // 附件
        $data_fields = ['home_site_logo', 'home_site_logo_wap', 'home_site_desktop_icon'];

        // 当前参数中不存在则移除
        foreach($data_fields as $key=>$field)
        {
            if(!isset($params[$field]))
            {
                unset($data_fields[$key]);
            }
        }

        // 获取附件
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);
        foreach($attachment['data'] as $k=>$v)
        {
            $params[$k] = $v;
        }

        // 循环保存数据
        $success = 0;

        // 不实例化的字段
        $no_all = array(
            'home_footer_info',
            'home_email_user_reg',
            'home_email_user_forget_pwd',
            'home_email_user_email_binding',
            'home_site_close_reason',
        );

        // 开始更新数据
        foreach($params as $k=>$v)
        {
            if(!in_array($k, $no_all))
            {
                $v = htmlentities($v);
            }
            if(Db::name('Config')->where(['only_tag'=>$k])->update(['value'=>$v, 'upd_time'=>time()]))
            {
                $success++;
            }
        }
        if($success > 0)
        {
            // 配置信息更新
            self::ConfigInit(1);

            // 是否需要更新路由规则
            $ret = self::RouteSeparatorHandle($params);
            if($ret['code'] != 0)
            {
                return $ret;
            }

            return DataReturn('编辑成功'.'['.$success.']');
        }
        return DataReturn('编辑失败', -100);
    }

    /**
     * 系统配置信息初始化
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-03T21:36:55+0800
     * @param    [int] $status [是否更新数据,0否,1是]
     */
    public static function ConfigInit($status = 0)
    {
        $key = config('shopxo.cache_common_my_config_key');
        $data = cache($key);
        if($status == 1 || empty($data))
        {
            // 所有配置
            $data = Db::name('Config')->column('value', 'only_tag');

            // 数据处理
            // 开启用户注册列表
            if(isset($data['home_user_reg_state']))
            {
                $data['home_user_reg_state'] = explode(',', $data['home_user_reg_state']);
            }
            cache($key, $data);
        }
    }

    /**
     * 路由规则处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-02T23:08:19+0800
     * @param   [array]          $params [输入参数]
     */
    public static function RouteSeparatorHandle($params = [])
    {
        if(isset($params['home_seo_url_model']))
        {
            $route_file = ROOT.'route'.DS.'route.config';
            $route_file_php = ROOT.'route'.DS.'route.php';

            // 文件目录
            if(!is_writable(ROOT.'route'))
            {
                return DataReturn('路由目录没有操作权限'.'[./route]', -11);
            }

            // 路配置文件权限
            if(file_exists($route_file_php) && !is_writable($route_file_php))
            {
                return DataReturn('路由配置文件没有操作权限'.'[./route/route.php]', -11);
            }

            // pathinfo+短地址模式
            if($params['home_seo_url_model'] == 2)
            {
                
                if(!file_exists($route_file))
                {
                    return DataReturn('路由规则文件不存在'.'[./route/route.config]', -14);
                }

                // 开始生成规则文件
                if(file_put_contents($route_file_php, file_get_contents($route_file)) === false)
                {
                    return DataReturn('路由规则文件生成失败', -10);
                }

            // 兼容模式+pathinfo模式
            } else {
                if(file_exists($route_file_php) && @unlink($route_file_php) === false)
                {
                    return DataReturn('路由规则处理失败', -10);
                }
            }
            return DataReturn('处理成功', 0);
        }
        return DataReturn('无需处理', 0);
    }
}
?>