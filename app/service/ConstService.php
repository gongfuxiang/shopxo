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

/**
 * 公共常量数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-13
 * @desc    description
 */
class ConstService
{
    /**
     * 获取数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-13
     * @desc    description
     * @param   [string]           $key     [数据key]
     * @param   [mixed]            $default [默认数据]
     */
    public static function Run($key = '', $default = null)
    {
        // 数据定义
        $container = self::ConstData();

        // 是否读取全部
        if(empty($key))
        {
            $data = $container;
        } else {
            // 是否存在多级
            $arr = explode('.', $key);
            if(count($arr) == 1)
            {
                $data = array_key_exists($key, $container) ? $container[$key] : $default;
            } else {
                $data = $container;
                foreach($arr as $v)
                {
                    if(isset($data[$v]))
                    {
                        $data = $data[$v];
                    } else {
                        $data = $default;
                        break;
                    }
                }
            }
        }

        // 常量数据读取钩子
        $hook_name = 'plugins_service_const_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'key'           => $key,
            'default'       => $default,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 数据定义容器
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-14
     * @desc    description
     */
    public static function ConstData()
    {
        return [
            // -------------------- 公共 --------------------
            // 系统版本列表
            'common_system_version_list'          =>  [
                '1.1.0' => ['value' => '1.1.0', 'name' => 'v1.1.0'],
                '1.2.0' => ['value' => '1.2.0', 'name' => 'v1.2.0'],
                '1.3.0' => ['value' => '1.3.0', 'name' => 'v1.3.0'],
                '1.4.0' => ['value' => '1.4.0', 'name' => 'v1.4.0'],
                '1.5.0' => ['value' => '1.5.0', 'name' => 'v1.5.0'],
                '1.6.0' => ['value' => '1.6.0', 'name' => 'v1.6.0'],
                '1.7.0' => ['value' => '1.7.0', 'name' => 'v1.7.0'],
                '1.8.0' => ['value' => '1.8.0', 'name' => 'v1.8.0'],
                '1.8.1' => ['value' => '1.8.1', 'name' => 'v1.8.1'],
                '1.9.0' => ['value' => '1.9.0', 'name' => 'v1.9.0'],
                '1.9.1' => ['value' => '1.9.1', 'name' => 'v1.9.1'],
                '1.9.2' => ['value' => '1.9.2', 'name' => 'v1.9.2'],
                '1.9.3' => ['value' => '1.9.3', 'name' => 'v1.9.3'],
                '2.0.0' => ['value' => '2.0.0', 'name' => 'v2.0.0'],
                '2.0.1' => ['value' => '2.0.1', 'name' => 'v2.0.1'],
                '2.0.2' => ['value' => '2.0.2', 'name' => 'v2.0.2'],
                '2.0.3' => ['value' => '2.0.3', 'name' => 'v2.0.3'],
                '2.1.0' => ['value' => '2.1.0', 'name' => 'v2.1.0'],
                '2.2.0' => ['value' => '2.2.0', 'name' => 'v2.2.0'],
                '2.2.1' => ['value' => '2.2.1', 'name' => 'v2.2.1'],
                '2.2.2' => ['value' => '2.2.2', 'name' => 'v2.2.2'],
                '2.2.3' => ['value' => '2.2.3', 'name' => 'v2.2.3'],
                '2.2.4' => ['value' => '2.2.4', 'name' => 'v2.2.4'],
                '2.2.5' => ['value' => '2.2.5', 'name' => 'v2.2.5'],
                '2.2.6' => ['value' => '2.2.6', 'name' => 'v2.2.6'],
                '2.2.7' => ['value' => '2.2.7', 'name' => 'v2.2.7'],
                '2.2.8' => ['value' => '2.2.8', 'name' => 'v2.2.8'],
                '2.2.9' => ['value' => '2.2.9', 'name' => 'v2.2.9'],
                '2.3.0' => ['value' => '2.3.0', 'name' => 'v2.3.0'],
                '2.3.1' => ['value' => '2.3.1', 'name' => 'v2.3.1'],
                '2.3.2' => ['value' => '2.3.2', 'name' => 'v2.3.2'],
                '2.3.3' => ['value' => '2.3.3', 'name' => 'v2.3.3'],
            ],

            // -------------------- 正则 --------------------
            // 用户名
            'common_regex_username'             =>  '^[A-Za-z0-9_]{2,18}$',
            // 用户名
            'common_regex_pwd'                  =>  '^.{6,18}$',
            // 包含字母和数字、6~16个字符
            'common_regex_alpha_number'         => '^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$',
            // 手机号码
            'common_regex_mobile'               =>  '^1((3|4|5|6|7|8|9){1}\d{1})\d{8}$',
            // 座机号码
            'common_regex_tel'                  =>  '^\d{3,4}-?\d{8}$',
            // 电子邮箱
            'common_regex_email'                =>  '^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$',
            // 身份证号码
            'common_regex_id_card'              =>  '^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$',
            // 价格格式
            'common_regex_price'                =>  '^([0-9]{1}\d{0,7})(\.\d{1,2})?$',
            // ip
            'common_regex_ip'                   =>  '^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$',
            // url
            'common_regex_url'                  =>  '^http[s]?:\/\/[A-Za-z0-9-]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$',
            // 控制器名称
            'common_regex_control'              =>  '^[A-Za-z]{1}[A-Za-z0-9_]{0,29}$',
            // 方法名称
            'common_regex_action'               =>  '^[A-Za-z]{1}[A-Za-z0-9_]{0,29}$',
            // 顺序
            'common_regex_sort'                 =>  '^[0-9]{1,3}$',
            // 日期
            'common_regex_date'                 =>  '^\d{4}-\d{2}-\d{2}$',
            // 分数
            'common_regex_score'                =>  '^[0-9]{1,3}$',
            // 分页
            'common_regex_page_number'          =>  '^[1-9]{1}[0-9]{0,2}$',
            // 时段格式 10:00-10:45
            'common_regex_interval'             =>  '^\d{2}\:\d{2}\-\d{2}\:\d{2}$',
            // 颜色
            'common_regex_color'                =>  '^(#([a-fA-F0-9]{6}|[a-fA-F0-9]{3}))?$',
            // id逗号隔开
            'common_regex_id_comma_split'       =>  '^\d(\d|,?)*\d$',
            // url伪静态后缀
            'common_regex_url_html_suffix'      =>  '^[a-z]{0,8}$',
            // 图片比例值
            'common_regex_image_proportion'     =>  '^([1-9]{1}[0-9]?|[1-9]{1}[0-9]?\.{1}[0-9]{1,2}|100|0)?$',
            // 版本号
            'common_regex_version'              => '^[0-9]{1,6}\.[0-9]{1,6}\.[0-9]{1,6}$',
        ];
    }
}
?>