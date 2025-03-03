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
namespace app\module;

use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\ArticleService;
use app\service\BrandService;
use app\service\DiyApiService;
use app\service\UserService;
use app\service\MessageService;
use app\service\GoodsCartService;

/**
 * DIY装修处理服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-14
 * @desc    description
 */
class DiyModule
{
    /**
     * 配置数据展示处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-22
     * @desc    description
     * @param   [array]          $config [配置数据]
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigViewHandle($config, $params = [])
    {
        // 是否为空
        if(empty($config))
        {
            return $config;
        }

        // 是否展示查看
        $is_view = isset($params['is_view']) && $params['is_view'] == 1;

        // 是否需要处理数据
        $is_config_data_handle = isset($params['is_config_data_handle']) && $params['is_config_data_handle'] == 1;

        // 非数组处理
        if(!is_array($config))
        {
            $config = json_decode($config, true);
        }
        // 数据处理
        $config = self::ConfigViewAnnexHandle($config);

        // 头尾、唯一key生成
        if(!empty($config['header']))
        {
            // 角标数量处理
            if(!empty($config['header']['com_data']) && !empty($config['header']['com_data']['content']) && !empty($config['header']['com_data']['content']['icon_setting']))
            {
                $user = UserService::LoginUserInfo();
                foreach($config['header']['com_data']['content']['icon_setting'] as $k=>$v)
                {
                    $badge = '';
                    if(!empty($user) && !empty($v['link']) && !empty($v['link']['page']))
                    {
                        switch($v['link']['page'])
                        {
                            // 消息
                            case '/pages/message/message' :
                                $message_total = MessageService::UserMessageTotal(['user'=>$user, 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user']);
                                $badge = $message_total;
                                break;

                            // 购物车
                            case '/pages/cart/cart' :
                            case '/pages/cart-page/cart-page' :
                                $cart_res = GoodsCartService::UserGoodsCartTotal(['user'=>$user]);
                                $badge = $cart_res['buy_number'];
                                break;
                        }
                    }
                    $config['header']['com_data']['content']['icon_setting'][$k]['badge'] = $badge;
                }
            }

            // id处理
            $config['header']['id'] = empty($config['header']['com_data']) ? (empty($config['header']['id']) ? '' : $config['header']['id']) : md5(json_encode($config['header']['com_data'], JSON_UNESCAPED_UNICODE));
        }

        if(!empty($config['footer']))
        {
            $config['footer']['id'] = empty($config['footer']['com_data']) ? (empty($config['footer']['id']) ? '' : $config['footer']['id']) : md5(json_encode($config['footer']['com_data'], JSON_UNESCAPED_UNICODE));
        }

        // 处理数据
        if($is_config_data_handle && !empty($config['diy_data']))
        {
            // 指定数据id
            $goods_ids = [];
            $article_ids = [];
            $brand_ids = [];
            foreach($config['diy_data'] as $v)
            {
                if(!empty($v['key']) && !empty($v['com_data']) && !empty($v['com_data']['content']))
                {
                    // 主体内容
                    $content = $v['com_data']['content'];

                    // 展示查看模式是否限制时间
                    if($is_view && !self::ValidTimeCheck($content))
                    {
                        continue;
                    }

                    // 根据模块处理
                    switch($v['key'])
                    {
                        // 商品列表
                        case 'goods-list' :
                        // 文章列表
                        case 'article-list' :
                            if(isset($content['data_type']) && $content['data_type'] == 0 && !empty($content['data_ids']))
                            {
                                if(!is_array($content['data_ids']))
                                {
                                    $content['data_ids'] = explode(',', $content['data_ids']);
                                }
                                if($v['key'] == 'goods-list')
                                {
                                    $goods_ids = array_merge($goods_ids, $content['data_ids']);
                                } else {
                                    $article_ids = array_merge($article_ids, $content['data_ids']);
                                }
                            }
                            break;

                        // 商品选项卡
                        case 'goods-tabs' :
                        // 文章选项卡
                        case 'article-tabs' :
                            if(!empty($content['tabs_list']))
                            {
                                foreach($content['tabs_list'] as $atv)
                                {
                                    if(isset($atv['data_type']) && $atv['data_type'] == 0 && !empty($atv['data_ids']))
                                    {
                                        if(!is_array($atv['data_ids']))
                                        {
                                            $atv['data_ids'] = explode(',', $atv['data_ids']);
                                        }
                                        if($v['key'] == 'goods-tabs')
                                        {
                                            $goods_ids = array_merge($goods_ids, $atv['data_ids']);
                                        } else {
                                            $article_ids = array_merge($article_ids, $atv['data_ids']);
                                        }
                                    }
                                }
                            }
                            break;

                        // 数据选项卡
                        case 'data-tabs' :
                            if(!empty($content['tabs_list']))
                            {
                                foreach($content['tabs_list'] as $dtv)
                                {
                                    if(!empty($dtv['tabs_data_type']) && !empty($dtv[$dtv['tabs_data_type'].'_config']))
                                    {
                                        $tabs_data_config = $dtv[$dtv['tabs_data_type'].'_config'];
                                        if(!empty($tabs_data_config['content']))
                                        {
                                            $content = $tabs_data_config['content'];
                                            switch($dtv['tabs_data_type'])
                                            {
                                                // 商品
                                                case 'goods' :
                                                // 文章
                                                case 'article' :
                                                    if(isset($content['data_type']) && $content['data_type'] == 0 && !empty($content['data_ids']))
                                                    {
                                                        if(!is_array($content['data_ids']))
                                                        {
                                                            $content['data_ids'] = explode(',', $content['data_ids']);
                                                        }
                                                        if($dtv['tabs_data_type'] == 'goods')
                                                        {
                                                            $goods_ids = array_merge($goods_ids, $content['data_ids']);
                                                        } else {
                                                            $article_ids = array_merge($article_ids, $content['data_ids']);
                                                        }
                                                    }
                                                    break;

                                                // 自定义
                                                case 'custom' :
                                                    // 商品、文章、品牌
                                                    if(!empty($content['data_source']) && !empty($content['data_source_content']) && in_array($content['data_source'], ['goods', 'article', 'brand']) && !empty($content['data_source_content']['data_ids']))
                                                    {
                                                        // 手动模式选择的数据id
                                                        if(isset($content['data_source_content']['data_type']) && $content['data_source_content']['data_type'] == 0 && !empty($content['data_source_content']['data_ids']))
                                                        {
                                                            if(!is_array($content['data_source_content']['data_ids']))
                                                            {
                                                                $content['data_source_content']['data_ids'] = explode(',', $content['data_source_content']['data_ids']);
                                                            }
                                                            $temp_source = $content['data_source'].'_ids';
                                                            if(isset($temp_source) && isset($$temp_source))
                                                            {
                                                                $$temp_source = array_merge($$temp_source, $content['data_source_content']['data_ids']);
                                                            }
                                                        }
                                                    }
                                                    break;
                                            }
                                        }
                                    }
                                }
                            }
                            break;

                        // 数据魔方
                        case 'data-magic' :
                            if(!empty($content['data_magic_list']))
                            {
                                foreach($content['data_magic_list'] as $dmv)
                                {
                                    if(!empty($dmv['data_content']) && isset($dmv['data_content']['data_type']))
                                    {
                                        switch($dmv['data_content']['data_type'])
                                        {
                                            // 商品
                                            case 'goods' :
                                                if(!empty($dmv['data_content']['goods_ids']))
                                                {
                                                    if(!is_array($dmv['data_content']['goods_ids']))
                                                    {
                                                        $dmv['data_content']['goods_ids'] = explode(',', $dmv['data_content']['goods_ids']);
                                                    }
                                                    $goods_ids = array_merge($goods_ids, $dmv['data_content']['goods_ids']);
                                                }
                                                break;

                                            // 自定义
                                            case 'custom' :
                                                // 商品、文章、品牌
                                                if(in_array($dmv['data_content']['data_source'], ['goods', 'article', 'brand']) && !empty($dmv['data_content']['data_source_content']['data_ids']))
                                                {
                                                    // 手动模式选择的数据id
                                                    if(isset($dmv['data_content']['data_source_content']['data_type']) && $dmv['data_content']['data_source_content']['data_type'] == 0 && !empty($dmv['data_content']['data_source_content']['data_ids']))
                                                    {
                                                        if(!is_array($dmv['data_content']['data_source_content']['data_ids']))
                                                        {
                                                            $dmv['data_content']['data_source_content']['data_ids'] = explode(',', $dmv['data_content']['data_source_content']['data_ids']);
                                                        }
                                                        $temp_source = $dmv['data_content']['data_source'].'_ids';
                                                        if(isset($temp_source) && isset($$temp_source))
                                                        {
                                                            $$temp_source = array_merge($$temp_source, $dmv['data_content']['data_source_content']['data_ids']);
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                }
                            }
                            break;

                        // 自定义
                        case 'custom' :
                            if(!empty($content['data_source']))
                            {
                                // 商品、文章、品牌
                                if(in_array($content['data_source'], ['goods', 'article', 'brand']) && !empty($content['data_source_content']['data_ids']))
                                {
                                    // 手动模式选择的数据id
                                    if(isset($content['data_source_content']['data_type']) && $content['data_source_content']['data_type'] == 0 && !empty($content['data_source_content']['data_ids']))
                                    {
                                        if(!is_array($content['data_source_content']['data_ids']))
                                        {
                                            $content['data_source_content']['data_ids'] = explode(',', $content['data_source_content']['data_ids']);
                                        }
                                        $temp_source = $content['data_source'].'_ids';
                                        if(isset($temp_source) && isset($$temp_source))
                                        {
                                            $$temp_source = array_merge($$temp_source, $content['data_source_content']['data_ids']);
                                        }
                                    }
                                }
                            }
                            break;
                    }
                }
            }
            // 读取指定商品数据
            $goods_data = empty($goods_ids) ? [] : array_column(GoodsService::AppointGoodsList(['goods_ids'=>$goods_ids, 'is_spec'=>1, 'is_cart'=>1]), null, 'id');
            // 读取指定文章数据
            $article_data = empty($article_ids) ? [] : array_column(ArticleService::AppointArticleList(['article_ids'=>$article_ids]), null, 'id');
            // 读取指定品牌数据
            $brand_data = empty($brand_ids) ? [] : array_column(BrandService::AppointBrandList(['brand_ids'=>$brand_ids]), null, 'id');

            // 处理数据
            foreach($config['diy_data'] as $k=>&$v)
            {
                if(!empty($v['com_data']) && !empty($v['com_data']['content']))
                {
                    // 展示查看模式是否限制时间
                    if($is_view && !self::ValidTimeCheck($v['com_data']['content']))
                    {
                        unset($config['diy_data'][$k]);
                        continue;
                    }

                    // 根据模块处理
                    switch($v['key'])
                    {
                        // 商品列表
                        case 'goods-list' :
                            $v['com_data']['content'] = self::ConfigViewGoodsHandle($v['com_data']['content'], $goods_data);
                            break;

                        // 商品选项卡
                        case 'goods-tabs' :
                            if(!empty($v['com_data']['content']['tabs_list']))
                            {
                                foreach($v['com_data']['content']['tabs_list'] as &$gtv)
                                {
                                    $gtv = self::ConfigViewGoodsHandle($gtv, $goods_data);
                                }
                            }
                            break;

                        // 文章列表
                        case 'article-list' :
                            $v['com_data']['content'] = self::ConfigViewArticleHandle($v['com_data']['content'], $article_data);
                            break;

                        // 文章选项卡
                        case 'article-tabs' :
                            if(!empty($v['com_data']['content']['tabs_list']))
                            {
                                foreach($v['com_data']['content']['tabs_list'] as &$gtv)
                                {
                                    $gtv = self::ConfigViewArticleHandle($gtv, $article_data);
                                }
                            }
                            break;

                        // 数据选项卡
                        case 'data-tabs' :
                            if(!empty($v['com_data']['content']['tabs_list']))
                            {
                                foreach($v['com_data']['content']['tabs_list'] as &$dtv)
                                {
                                    if(!empty($dtv['tabs_data_type']) && !empty($dtv[$dtv['tabs_data_type'].'_config']))
                                    {
                                        $tabs_data_config = $dtv[$dtv['tabs_data_type'].'_config'];
                                        if(!empty($tabs_data_config['content']))
                                        {
                                            switch($dtv['tabs_data_type'])
                                            {
                                                // 商品
                                                case 'goods' :
                                                    $tabs_data_config['content'] = self::ConfigViewGoodsHandle($tabs_data_config['content'], $goods_data);
                                                    break;

                                                // 文章
                                                case 'article' :
                                                    $tabs_data_config['content'] = self::ConfigViewGoodsHandle($tabs_data_config['content'], $article_data);
                                                    break;

                                                // 自定义
                                                case 'custom' :
                                                    if(!empty($tabs_data_config['content']['data_source']))
                                                    {
                                                        // 商品、文章、品牌
                                                        if(in_array($tabs_data_config['content']['data_source'], ['goods', 'article', 'brand']))
                                                        {
                                                            switch($tabs_data_config['content']['data_source'])
                                                            {
                                                                // 商品
                                                                case 'goods' :
                                                                    $tabs_data_config['content']['data_source_content'] = self::ConfigViewGoodsHandle($tabs_data_config['content']['data_source_content'], $goods_data);
                                                                    break;

                                                                // 文章
                                                                case 'article' :
                                                                    $tabs_data_config['content']['data_source_content'] = self::ConfigViewArticleHandle($tabs_data_config['content']['data_source_content'], $article_data);
                                                                    break;

                                                                // 品牌
                                                                case 'brand' :
                                                                    $tabs_data_config['content']['data_source_content'] = self::ConfigViewBrandHandle($tabs_data_config['content']['data_source_content'], $brand_data);
                                                                    break;
                                                            }
                                                        }

                                                        // 固定数据、用户信息
                                                        if($tabs_data_config['content']['data_source'] == 'user-info')
                                                        {
                                                            $ret = DiyApiService::UserHeadData();
                                                            $ret['data']['user_avatar'] = empty($ret['data']['user']) ? UserDefaultAvatar() : $ret['data']['user']['avatar'];
                                                            $ret['data']['user_name_view'] = empty($ret['data']['user']) ? '用户名称' : $ret['data']['user']['user_name_view'];
                                                            unset($ret['data']['user']);
                                                            $tabs_data_config['content']['data_source_content']['data_list'][] = $ret['data'];
                                                        }
                                                    }
                                                    break;
                                            }
                                            $dtv[$dtv['tabs_data_type'].'_config'] = $tabs_data_config;
                                        }
                                    }
                                }
                            }
                            break;

                        // 数据魔方
                        case 'data-magic' :
                            if(!empty($v['com_data']['content']['data_magic_list']))
                            {
                                foreach($v['com_data']['content']['data_magic_list'] as &$dmv)
                                {
                                    if(!empty($dmv['data_content']) && isset($dmv['data_content']['data_type']))
                                    {
                                        switch($dmv['data_content']['data_type'])
                                        {
                                            // 商品
                                            case 'goods' :
                                                if(!empty($dmv['data_content']['goods_ids']) && !empty($dmv['data_content']['goods_list']))
                                                {
                                                    $temp = self::ConfigViewGoodsHandle(['data_type'=>0, 'data_ids'=>$dmv['data_content']['goods_ids'], 'data_list'=>$dmv['data_content']['goods_list']], $goods_data);
                                                    if(!empty($temp['data_list']))
                                                    {
                                                        $dmv['data_content']['goods_list'] = $temp['data_list'];
                                                    }
                                                }
                                                break;

                                            // 自定义
                                            case 'custom' :
                                                if(!empty($dmv['data_content']['data_source']))
                                                {
                                                    // 商品、文章、品牌
                                                    if(in_array($dmv['data_content']['data_source'], ['goods', 'article', 'brand']))
                                                    {
                                                        switch($dmv['data_content']['data_source'])
                                                        {
                                                            // 商品
                                                            case 'goods' :
                                                                $dmv['data_content']['data_source_content'] = self::ConfigViewGoodsHandle($dmv['data_content']['data_source_content'], $goods_data);
                                                                break;

                                                            // 文章
                                                            case 'article' :
                                                                $dmv['data_content']['data_source_content'] = self::ConfigViewArticleHandle($dmv['data_content']['data_source_content'], $article_data);
                                                                break;

                                                            // 品牌
                                                            case 'brand' :
                                                                $dmv['data_content']['data_source_content'] = self::ConfigViewBrandHandle($dmv['data_content']['data_source_content'], $brand_data);
                                                                break;
                                                        }
                                                    }

                                                    // 固定数据、用户信息
                                                    if($dmv['data_content']['data_source'] == 'user-info')
                                                    {
                                                        $ret = DiyApiService::UserHeadData();
                                                        $ret['data']['user_avatar'] = empty($ret['data']['user']) ? UserDefaultAvatar() : $ret['data']['user']['avatar'];
                                                        $ret['data']['user_name_view'] = empty($ret['data']['user']) ? '用户名称' : $ret['data']['user']['user_name_view'];
                                                        unset($ret['data']['user']);
                                                        $dmv['data_content']['data_source_content']['data_list'][] = $ret['data'];
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                }
                            }
                            break;

                        // 自定义
                        case 'custom' :
                            if(!empty($v['com_data']['content']['data_source']))
                            {
                                // 商品、文章、品牌
                                if(in_array($v['com_data']['content']['data_source'], ['goods', 'article', 'brand']))
                                {
                                    switch($v['com_data']['content']['data_source'])
                                    {
                                        // 商品
                                        case 'goods' :
                                            $v['com_data']['content']['data_source_content'] = self::ConfigViewGoodsHandle($v['com_data']['content']['data_source_content'], $goods_data);
                                            break;

                                        // 文章
                                        case 'article' :
                                            $v['com_data']['content']['data_source_content'] = self::ConfigViewArticleHandle($v['com_data']['content']['data_source_content'], $article_data);
                                            break;

                                        // 品牌
                                        case 'brand' :
                                            $v['com_data']['content']['data_source_content'] = self::ConfigViewBrandHandle($v['com_data']['content']['data_source_content'], $brand_data);
                                            break;
                                    }
                                }

                                // 固定数据、用户信息
                                if($v['com_data']['content']['data_source'] == 'user-info')
                                {
                                    $ret = DiyApiService::UserHeadData();
                                    $ret['data']['user_avatar'] = empty($ret['data']['user']) ? UserDefaultAvatar() : $ret['data']['user']['avatar'];
                                    $ret['data']['user_name_view'] = empty($ret['data']['user']) ? '用户名称' : $ret['data']['user']['user_name_view'];
                                    unset($ret['data']['user']);
                                    $v['com_data']['content']['data_source_content']['data_list'][] = $ret['data'];
                                }
                            }
                            break;

                        // 用户信息
                        case 'user-info' :
                            if(!empty($v['com_data']['content']))
                            {
                                $business_type = empty($v['com_data']['content']['config']) ? [] : $v['com_data']['content']['config'];
                                $ret = DiyApiService::UserHeadData();
                                $v['com_data']['content']['data'] = $ret['data'];
                            }
                            break;
                    }
                }
            }
            // 展示查看模式，则重新处理数组索引，上面处理会移除非有效数据导致索引无序
            if($is_view)
            {
                $config['diy_data'] = array_values($config['diy_data']);
            }

            // diy显示数据处理钩子
            $hook_name = 'plugins_module_diy_view_data_handle';
            MyEventTrigger($hook_name, [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'config'      => &$config,
                'params'      => $params,
            ]);

            // 唯一id数据
            if(!empty($config['diy_data']) && is_array($config['diy_data']))
            {
                foreach($config['diy_data'] as $tk=>$tv)
                {
                    // 唯一key生成
                    $config['diy_data'][$tk]['id'] = empty($tv['com_data']) ? (empty($tv['id']) ? '' : $tv['id']) : md5(json_encode($tv['com_data'], JSON_UNESCAPED_UNICODE));
                }
            }
        }
        return $config;
    }

    /**
     * 有效时间验证
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-01-14
     * @desc    description
     * @param   [array]          $data [组件数据]
     */
    public static function ValidTimeCheck($data)
    {
        if(!empty($data['content_top']) && !empty($data['content_top']['time_value']) && is_array($data['content_top']['time_value']))
        {
            $time_value = $data['content_top']['time_value'];
            // 开始时间
            if(!empty($time_value[0]))
            {
                $start_time = strtotime($time_value[0]);
                if(!empty($start_time) && $start_time > time())
                {
                    return false;
                }
            }
            // 结束时间
            if(!empty($time_value[1]))
            {
                $end_time = strtotime($time_value[1]);
                if(!empty($end_time) && $end_time < time())
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 品牌配置显示数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-14
     * @desc    description
     * @param   [array]          $config       [配置数据]
     * @param   [array]          $brand_data   [指定文章的数据]
     */
    public static function ConfigViewBrandHandle($config, $brand_data = [])
    {
        $data_type = isset($config['data_type']) ? $config['data_type'] : 0;
        if($data_type == 1)
        {
            $data_params = [
                'brand_category_ids'   => isset($config['brand_category_ids']) ? $config['brand_category_ids'] : (isset($config['category_ids']) ? $config['category_ids'] : ''),
                'brand_keywords'       => isset($config['brand_keywords']) ? $config['brand_keywords'] : (isset($config['keywords']) ? $config['keywords'] : ''),
                'brand_number'         => isset($config['brand_number']) ? $config['brand_number'] : (isset($config['number']) ? $config['number'] : 4),
                'brand_order_by_type'  => isset($config['brand_order_by_type']) ? $config['brand_order_by_type'] : (isset($config['order_by_type']) ? $config['order_by_type'] : 0),
                'brand_order_by_rule'  => isset($config['brand_order_by_rule']) ? $config['brand_order_by_rule'] : (isset($config['order_by_rule']) ? $config['order_by_rule'] : 0),
                'brand_is_cover'       => isset($config['brand_is_cover']) ? $config['brand_is_cover'] : (isset($config['is_cover']) ? $config['is_cover'] : 0),
            ];
            $config['data_auto_list'] = BrandService::AutoBrandList($data_params);
        } else {
            if(!empty($config['data_list']) && !empty($brand_data))
            {
                $index = 0;
                foreach($config['data_list'] as $dk=>$dv)
                {
                    if(!empty($dv['data_id']) && array_key_exists($dv['data_id'], $brand_data))
                    {
                        $config['data_list'][$dk]['data'] = $brand_data[$dv['data_id']];
                        $config['data_list'][$dk]['data']['data_index'] = $index+1;
                        $index++;
                    }
                }
            }
        }
        return $config;
    }

    /**
     * 文章配置显示数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-14
     * @desc    description
     * @param   [array]          $config       [配置数据]
     * @param   [array]          $article_data [指定文章的数据]
     */
    public static function ConfigViewArticleHandle($config, $article_data = [])
    {
        $data_type = isset($config['data_type']) ? $config['data_type'] : 0;
        if($data_type == 1)
        {
            $data_params = [
                'article_category_ids'   => isset($config['article_category_ids']) ? $config['article_category_ids'] : (isset($config['category_ids']) ? $config['category_ids'] : ''),
                'article_keywords'       => isset($config['article_keywords']) ? $config['article_keywords'] : (isset($config['keywords']) ? $config['keywords'] : ''),
                'article_number'         => isset($config['article_number']) ? $config['article_number'] : (isset($config['number']) ? $config['number'] : 4),
                'article_order_by_type'  => isset($config['article_order_by_type']) ? $config['article_order_by_type'] : (isset($config['order_by_type']) ? $config['order_by_type'] : 0),
                'article_order_by_rule'  => isset($config['article_order_by_rule']) ? $config['article_order_by_rule'] : (isset($config['order_by_rule']) ? $config['order_by_rule'] : 0),
                'article_is_cover'       => isset($config['article_is_cover']) ? $config['article_is_cover'] : (isset($config['is_cover']) ? $config['is_cover'] : 0),
            ];
            $config['data_auto_list'] = ArticleService::AutoArticleList($data_params);
        } else {
            if(!empty($config['data_list']) && !empty($article_data))
            {
                $index = 0;
                foreach($config['data_list'] as $dk=>$dv)
                {
                    if(!empty($dv['data_id']) && array_key_exists($dv['data_id'], $article_data))
                    {
                        $config['data_list'][$dk]['data'] = $article_data[$dv['data_id']];
                        $config['data_list'][$dk]['data']['data_index'] = $index+1;
                        $index++;
                    }
                }
            }
        }
        return $config;
    }

    /**
     * 商品配置显示数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-14
     * @desc    description
     * @param   [array]          $config     [配置数据]
     * @param   [array]          $goods_data [指定商品的数据]
     */
    public static function ConfigViewGoodsHandle($config, $goods_data = [])
    {
        $data_type = isset($config['data_type']) ? $config['data_type'] : 0;
        if($data_type == 1)
        {
            $data_params = [
                'goods_category_ids'   => isset($config['goods_category_ids']) ? $config['goods_category_ids'] : (isset($config['category_ids']) ? $config['category_ids'] : ''),
                'goods_brand_ids'      => isset($config['goods_brand_ids']) ? $config['goods_brand_ids'] : (isset($config['brand_ids']) ? $config['brand_ids'] : ''),
                'goods_keywords'       => isset($config['goods_keywords']) ? $config['goods_keywords'] : (isset($config['keywords']) ? $config['keywords'] : ''),
                'goods_number'         => isset($config['goods_number']) ? $config['goods_number'] : (isset($config['number']) ? $config['number'] : 4),
                'goods_order_by_type'  => isset($config['goods_order_by_type']) ? $config['goods_order_by_type'] : (isset($config['order_by_type']) ? $config['order_by_type'] : 0),
                'goods_order_by_rule'  => isset($config['goods_order_by_rule']) ? $config['goods_order_by_rule'] : (isset($config['order_by_rule']) ? $config['order_by_rule'] : 0),
            ];
            $config['data_auto_list'] = GoodsService::AutoGoodsList(array_merge($data_params, ['is_spec'=>1, 'is_cart'=>1]));
        } else {
            if(!empty($config['data_list']) && !empty($goods_data))
            {
                $index = 0;
                foreach($config['data_list'] as $dk=>$dv)
                {
                    if(!empty($dv['data_id']) && array_key_exists($dv['data_id'], $goods_data))
                    {
                        $config['data_list'][$dk]['data'] = $goods_data[$dv['data_id']];
                        $config['data_list'][$dk]['data']['data_index'] = $index+1;
                        $index++;
                    }
                }
            }
        }
        return $config;
    }

    /**
     * 配置数据展示附件地址处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [array]          $data [配置数据]
     */
    public static function ConfigViewAnnexHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if(!empty($v) && is_array($v))
                {
                    // 附件
                    if(!empty($v[0]) && isset($v[0]['url']))
                    {
                        $data[$k][0]['url'] = ResourcesService::AttachmentPathViewHandle($v[0]['url']);

                    // 富文本
                    } elseif(!empty($v['content']) && !empty($v['content']['html']))
                    {
                        $data[$k]['content']['html'] = ResourcesService::ContentStaticReplace($v['content']['html'], 'get');
                    } else {
                        $data[$k] = self::ConfigViewAnnexHandle($v);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 配置数据保存处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-22
     * @desc    description
     * @param   [array]          $config [配置数据]
     */
    public static function ConfigSaveHandle($config)
    {
        if(!empty($config))
        {
            // 非数组处理
            if(!is_array($config))
            {
                $config = json_decode(htmlspecialchars_decode($config), true);
            }

            // 数据处理
            $config = self::ConfigSaveAnnexHandle($config);

            // diy保存数据处理钩子
            $hook_name = 'plugins_module_diy_save_data_handle';
            MyEventTrigger($hook_name, [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'config'      => &$config,
            ]);

            // 转为json格式
            $config = json_encode($config, JSON_UNESCAPED_UNICODE);
        }
        return $config;
    }

    /**
     * 配置数据保存附件地址处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [array]          $data [配置数据]
     */
    public static function ConfigSaveAnnexHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if(!empty($v) && is_array($v))
                {
                    // 附件
                    if(!empty($v[0]) && isset($v[0]['url']))
                    {
                        $data[$k][0]['url'] = ResourcesService::AttachmentPathHandle($v[0]['url']);

                    // 富文本
                    } elseif(!empty($v['content']) && !empty($v['content']['html']))
                    {
                        $data[$k]['content']['html'] = ResourcesService::ContentStaticReplace($v['content']['html'], 'add');
                    } else {
                        $data[$k] = self::ConfigSaveAnnexHandle($v);
                    }
                }
            }
        }
        return $data;
    }
}
?>