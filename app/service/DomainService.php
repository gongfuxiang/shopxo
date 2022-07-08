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
 * 域名服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-05-10
 * @desc    description
 */
class DomainService
{
    /**
     * 域名更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-05-10
     * @desc    description
     * @param   [array]       $params           [输入参数]
     * @param   [array]       $params['domain'] [域名信息key=>val（'xxx'=>'search/index'）]
     * @param   [array]       $params['type']   [输入参数（0 移除, 1 增加）]
     */
    public static function DomainUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'inc_domain',
                'is_backend'        => 1,
                'error_msg'         => '增加域名数据必须为数组',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'dec_domain',
                'is_backend'        => 1,
                'error_msg'         => '移除域名数据必须为数组',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 存在一个增加或移除数据
        if(empty($params['inc_domain']) && empty($params['dec_domain']))
        {
            return DataReturn('增加或移除域名必须传递一项', -1);
        }

        // 域名配置文件及数据
        $config = [];
        $dir = ROOT.'config';
        $domain_file = $dir.'/domain.php';
        if(file_exists($domain_file))
        {
            if(is_writable($domain_file))
            {
                $res = require $domain_file;
                if(!empty($res) && is_array($res))
                {
                    $config = $res;
                }
            } else {
                return DataReturn('配置文件没写权限['.$domain_file.']', -1);
            }
        } else {
            if(!is_writable($dir))
            {
                return DataReturn('配置目录没写权限['.$dir.']', -1);
            }
        }

        // 去除域名
        if(!empty($config) && !empty($params['dec_domain']))
        {
            foreach($params['dec_domain'] as $k=>$v)
            {
                if(array_key_exists($k, $config))
                {
                    unset($config[$k]);
                }
            }
        }
        // 增加
        if(!empty($params['inc_domain']) && is_array($params['inc_domain']))
        {
            foreach($params['inc_domain'] as $k=>$v)
            {
                // 排除空字符串和null的key
                if($k !== '' && $k !== null && !empty($v))
                {
                    $config[$k] = $v;
                }
            }
        }

        // 生成域名配置文件
        $ret = @file_put_contents($domain_file, "<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// 域名配置文件\nreturn ".var_export($config, true).";\n?>");
        if($ret === false)
        {
            return DataReturn('域名配置部署失败', -10);
        }
        return DataReturn('操作成功', 0);
    }
}
?>