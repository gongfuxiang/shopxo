<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

use think\Db;
use app\service\ResourcesService;

/**
 * 应用服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PluginsService
{
    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('Plugins')->limit($m, $n)->order($order_by)->select();
        
        return DataReturn('处理成功', 0, self::PluginsDataHandle($data));
    }

    /**
     * 数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $data [数据]
     */
    private static function PluginsDataHandle($data)
    {
        $result = [];
        if(!empty($data))
        {
            foreach($data as $v)
            {
                $config = self::GetPluginsConfig($v['plugins']);
                if($config !== false)
                {
                    $base = $config['base'];
                    $result[] = [
                        'id'            => $v['id'],
                        'plugins'       => $v['plugins'],
                        'logo_old'      => $v['logo'],
                        'logo'          => ResourcesService::AttachmentPathViewHandle($v['logo']),
                        'is_enable'     => $v['is_enable'],
                        'name'          => isset($base['name']) ? $base['name'] : '',
                        'author'        => isset($base['author']) ? $base['author'] : '',
                        'author_url'    => isset($base['author_url']) ? $base['author_url'] : '',
                        'version'       => isset($base['version']) ? $base['version'] : '',
                        'desc'          => isset($base['desc']) ? $base['desc'] : '',
                        'apply_version' => isset($base['apply_version']) ? $base['apply_version'] : [],
                        'apply_terminal'=> isset($base['apply_terminal']) ? $base['apply_terminal'] : [],
                        'add_time_time' => date('Y-m-d H:i:s', $v['add_time']),
                        'add_time_date' => date('Y-m-d', $v['add_time']),
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * 总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function PluginsTotal($where = [])
    {
        return (int) Db::name('Plugins')->where($where)->count();
    }

    /**
     * 列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsListWhere($params = [])
    {
        $where = [];
        return $where;
    }

    /**
     * 根据应用标记获取数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [string]          $plugins [应用标记]
     */
    public static function PluginsData($plugins)
    {
        // 获取数据
        $data = Db::name('Plugins')->where(['plugins'=>$plugins])->value('data');
        if(!empty($data))
        {
            $data = json_decode($data, true);
            $data['images_old'] = $data['images'];
            $data['images'] = ResourcesService::AttachmentPathViewHandle($data['images']);
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 应用数据保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [string]          $plugins [应用标记]
     */
    public static function PluginsDataSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'plugins',
                'error_msg'         => '应用标记不能为空',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'data',
                'error_msg'         => '数据参数不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Plugins')->where(['plugins'=>$params['plugins']])->update(['data'=>json_encode($params['data']), 'upd_time'=>time()]))
        {
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PluginsStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
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
        if(Db::name('Plugins')->where(['id'=>$params['id']])->update(['is_enable'=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Delete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        //  tttttttttt
        return DataReturn('删除失败或资源不存在', -100);

        // 删除操作
        if(Db::name('Plugins')->where(['id'=>$params['id']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
    }

    /**
     * 获取应用配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [string]          $plugins [应用名称]
     */
    private static function GetPluginsConfig($plugins)
    {
        $plugins = '\app\plugins\\'.$plugins.'\\'.ucfirst($plugins);
        if(class_exists($plugins))
        {
            $obj = new $plugins();
            if(method_exists($obj, 'config'))
            {
                return $obj->config();
            }
        }
        return false;
    }
}
?>