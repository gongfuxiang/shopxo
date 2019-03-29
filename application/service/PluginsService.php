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
 * 应用服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PluginsService
{
    /**
     * 根据应用标记获取数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [string]          $plugins          [应用标记]
     * @param   [array]           $attachment_field [附件字段]
     */
    public static function PluginsData($plugins, $attachment_field = [])
    {
        // 从缓存获取数据
        $key = config('shopxo.cache_plugins_data_key').$plugins;
        $data = cache($key);
        if(empty($data))
        {
            // 获取数据
            $data = Db::name('Plugins')->where(['plugins'=>$plugins])->value('data');
            if(!empty($data))
            {
                $data = json_decode($data, true);

                // 是否有图片需要处理
                if(!empty($attachment_field) && is_array($attachment_field))
                {
                    foreach($attachment_field as $field)
                    {
                        if(isset($data[$field]))
                        {
                            $data[$field.'_old'] = $data[$field];
                            $data[$field] = ResourcesService::AttachmentPathViewHandle($data[$field]);
                        }
                    }
                }

                // 存储缓存
                cache($key, $data);
            }
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
     * @param   [string]          $plugins          [应用标记]
     * @param   [array]           $attachment_field [附件字段]
     */
    public static function PluginsDataSave($params = [], $attachment_field = [])
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

        // 附件处理
        $attachment = ResourcesService::AttachmentParams($params['data'], $attachment_field);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }
        if(!empty($attachment['data']))
        {
            foreach($attachment['data'] as $field=>$value)
            {
                $params['data'][$field] = $value;
            }
        }

        // 数据更新
        if(Db::name('Plugins')->where(['plugins'=>$params['plugins']])->update(['data'=>json_encode($params['data']), 'upd_time'=>time()]))
        {
            // 删除缓存
            cache(config('shopxo.cache_plugins_data_key').$params['plugins'], null);
            
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }
}
?>