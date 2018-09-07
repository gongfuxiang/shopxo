<?php

namespace Service;

/**
 * 导航服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class NavigationService
{
    /**
     * 获取首页导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Home($params = [])
    {
        // 读取缓存数据
        $header = S(C('cache_common_home_nav_header_key'));
        $footer = S(C('cache_common_home_nav_footer_key'));

        // 导航模型
        $m = M('Navigation');
        $field = array('id', 'pid', 'name', 'url', 'value', 'data_type', 'is_new_window_open');

        // 缓存没数据则从数据库重新读取,顶部菜单
        if(empty($header))
        {
            $header = NavDataDealWith($m->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>0))->order('sort')->select());
            if(!empty($header))
            {
                foreach($header as $k=>$v)
                {
                    $header[$k]['item'] = NavDataDealWith($m->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>$v['id']))->order('sort')->select());
                }
            }
            S(C('cache_common_home_nav_header_key'), $header);
        }

        // 底部导航
        if(empty($footer))
        {
            $footer = NavDataDealWith($m->field($field)->where(array('nav_type'=>'footer', 'is_show'=>1))->order('sort')->select());
            S(C('cache_common_home_nav_footer_key'), $footer);
        }

        return [
            'header' => $header,
            'footer' => $footer,
        ];
    }
}
?>