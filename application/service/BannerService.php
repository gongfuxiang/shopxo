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
 * 轮播服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BannerService
{
    /**
     * 获取轮播
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Banner($params = [])
    {
        // 平台
        $platform = APPLICATION_CLIENT_TYPE;

        // web端手机访问
        if($platform == 'pc' && IsMobile())
        {
            $platform = 'h5';
        }

        // 缓存
        $key = config('shopxo.cache_banner_list_key').$platform;
        $data = cache($key);

        if(empty($data))
        {
            // 获取banner数据
            $field = 'name,images_url,event_value,event_type,bg_color';
            $order_by = 'sort asc,id asc';
            $data = Db::name('Slide')->field($field)->where(['platform'=>$platform, 'is_enable'=>1])->order($order_by)->select();
            if(!empty($data))
            {
                foreach($data as &$v)
                {
                    // 图片地址
                    $v['images_url_old'] = $v['images_url'];
                    $v['images_url'] = ResourcesService::AttachmentPathViewHandle($v['images_url']);

                    // 事件值
                    if(!empty($v['event_value']))
                    {
                        // 地图
                        if($v['event_type'] == 3)
                        {
                            $v['event_value_data'] = explode('|', $v['event_value']);
                        }
                        $v['event_value'] = $v['event_value'];
                    } else {
                        $v['event_value'] = null;
                    }
                }
            }

            // 存储缓存
            cache($key, $data, 3600*24);
        }
        return $data;
    }
}
?>