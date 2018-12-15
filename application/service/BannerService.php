<?php

namespace app\service;

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
     * 获取首页轮播 - PC
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PC($params = [])
    {
        // 轮播图片
        $banner = db('Slide')->field('name,jump_url,images_url,bg_color')->where(['is_enable'=>1])->order('sort asc')->select();
        if(!empty($banner))
        {
            $images_host = config('IMAGE_HOST');
            foreach($banner as &$v)
            {
                $v['images_url_old'] = $v['images_url'];
                $v['images_url'] = $images_host.$v['images_url'];
                $v['jump_url'] = empty($v['jump_url']) ? null : $v['jump_url'];
            }
        }
        return $banner;
    }

    /**
     * 获取轮播 - APP
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function App($params = [])
    {
        $banner = db('AppSlide')->field('name,images_url,event_value,event_type')->where(['platform'=>APPLICATION_CLIENT_TYPE, 'is_enable'=>1])->order('sort asc')->select();
        if(!empty($banner))
        {
            $images_host = config('IMAGE_HOST');
            foreach($banner as &$v)
            {
                $v['images_url_old'] = $v['images_url'];
                $v['images_url'] = $images_host.$v['images_url'];
                $v['event_value'] = empty($v['event_value']) ? null : $v['event_value'];
            }
        }
        return $banner;
    }
}
?>