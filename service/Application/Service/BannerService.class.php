<?php

namespace Service;

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
     * 获取首页轮播
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Home($params = [])
    {
        // 轮播图片
        $banner = M('Slide')->field('jump_url,jump_url_type,images_url,name,bg_color')->where(['platform'=>APPLICATION_CLIENT_TYPE, 'is_enable'=>1])->select();
        if(!empty($banner))
        {
            $images_host = C('IMAGE_HOST');
            foreach($banner as &$v)
            {
                $v['images_url'] = $images_host.$v['images_url'];
                $v['jump_url'] = empty($v['jump_url']) ? null : $v['jump_url'];
            }
            $result['banner'] = $banner;
        }
        return $banner;
    }
}
?>