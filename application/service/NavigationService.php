<?php

namespace app\service;

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
        $header = cache(config('cache_common_home_nav_header_key'));
        $footer = cache(config('cache_common_home_nav_footer_key'));

        // 导航模型
        $m = db('Navigation');
        $field = array('id', 'pid', 'name', 'url', 'value', 'data_type', 'is_new_window_open');

        // 缓存没数据则从数据库重新读取,顶部菜单
        if(empty($header))
        {
            $header = self::NavDataDealWith($m->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>0))->order('sort')->select());
            if(!empty($header))
            {
                foreach($header as $k=>$v)
                {
                    $header[$k]['item'] = self::NavDataDealWith($m->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>$v['id']))->order('sort')->select());
                }
            }
            cache(config('cache_common_home_nav_header_key'), $header);
        }

        // 底部导航
        if(empty($footer))
        {
            $footer = self::NavDataDealWith($m->field($field)->where(array('nav_type'=>'footer', 'is_show'=>1))->order('sort')->select());
            cache(config('cache_common_home_nav_footer_key'), $footer);
        }

        return [
            'header' => $header,
            'footer' => $footer,
        ];
    }

    /**
     * [NavDataDealWith 导航数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-05T21:36:46+0800
     * @param    [array]      $data [需要处理的数据]
     * @return   [array]            [处理好的数据]
     */
    public static function NavDataDealWith($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                // url处理
                switch($v['data_type'])
                {
                    // 文章分类
                    case 'article':
                        $v['url'] = HomeUrl('article', 'index', ['id'=>$v['value']]);
                        break;

                    // 自定义页面
                    case 'customview':
                        $v['url'] = HomeUrl('customview', 'index', ['id'=>$v['value']]);
                        break;

                    // 商品分类
                    case 'goods_category':
                        $v['url'] = HomeUrl('search', 'index', ['category_id'=>$v['value']]);
                        break;
                }
                $data[$k] = $v;
            }
        }
        return $data;
    }
}
?>