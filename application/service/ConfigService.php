<?php
namespace app\service;

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
        return db('Config')->column($field);
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
            if(db('Config')->where(['only_tag'=>$k])->update(['value'=>$v, 'upd_time'=>time()]))
            {
                $success++;
            }
        }
        if($success > 0)
        {
            // 配置信息更新
            self::ConfigInit(1);

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
        $key = config('cache_common_my_config_key');
        $data = cache($key);
        if($status == 1 || empty($data))
        {
            // 所有配置
            $data = db('Config')->column('value', 'only_tag');

            // 数据处理
            // 开启用户注册列表
            if(isset($data['home_user_reg_state']))
            {
                $data['home_user_reg_state'] = explode(',', $data['home_user_reg_state']);
            }

            cache($key, $data);

            // 时区
            if(isset($data['common_timezone']))
            {
                cache('cache_common_timezone_data', $data['common_timezone']);
            }

            // 默认模板
            if(isset($data['common_default_theme']))
            {
                cache('cache_common_default_theme_data', $data['common_default_theme']);
            }
        }
    }
}
?>