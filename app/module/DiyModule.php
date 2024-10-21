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

        // 非数组处理
        if(!is_array($config))
        {
            $config = json_decode($config, true);
        }
        // 数据处理
        $config = self::ConfigViewAnnexHandle($config);

        // 头尾、// 唯一key生成
        if(!empty($config['header']))
        {
            $config['header']['id'] = empty($config['header']['com_data']) ? (empty($config['header']['id']) ? '' : $config['header']['id']) : md5(json_encode($config['header']['com_data'], JSON_UNESCAPED_UNICODE));
        }
        if(!empty($config['footer']))
        {
            $config['footer']['id'] = empty($config['footer']['com_data']) ? (empty($config['footer']['id']) ? '' : $config['footer']['id']) : md5(json_encode($config['footer']['com_data'], JSON_UNESCAPED_UNICODE));
        }

        // 处理数据
        if(isset($params['is_config_data_handle']) && $params['is_config_data_handle'] == 1 && !empty($config['diy_data']))
        {
            // 指定数据id
            $goods_ids = [];
            $article_ids = [];
            $brand_ids = [];
            foreach($config['diy_data'] as $v)
            {
                if(!empty($v['key']) && !empty($v['com_data']) && !empty($v['com_data']['content']))
                {
                    $content = $v['com_data']['content'];
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

                        // 数据魔方
                        case 'data-magic' :
                            if(!empty($content['data_magic_list']))
                            {
                                foreach($content['data_magic_list'] as $dmv)
                                {
                                    if(!empty($dmv['data_content']) && isset($dmv['data_content']['data_type']) && $dmv['data_content']['data_type'] == 'goods' && !empty($dmv['data_content']['goods_ids']))
                                    {
                                        if(!is_array($dmv['data_content']['goods_ids']))
                                        {
                                            $dmv['data_content']['goods_ids'] = explode(',', $dmv['data_content']['goods_ids']);
                                        }
                                        $goods_ids = array_merge($goods_ids, $dmv['data_content']['goods_ids']);
                                    }
                                }
                            }
                            break;

                        // 自定义
                        case 'custom' :
                            if(!empty($content['data_source']) && !empty($content['data_source_content_value']))
                            {
                                $temp_source = $content['data_source'].'_ids';
                                if(isset($temp_source) && isset($$temp_source))
                                {
                                    $$temp_source[] = $content['data_source_content_value'];
                                }
                            }
                            break;
                    }
                }
            }
            // 读取指定商品数据
            $goods_data = empty($goods_ids) ? [] : array_column(GoodsService::AppointGoodsList($goods_ids, ['is_spec'=>1, 'is_cart'=>1]), null, 'id');
            // 读取指定文章数据
            $article_data = empty($article_ids) ? [] : array_column(ArticleService::AppointArticleList($article_ids), null, 'id');
            // 读取指定品牌数据
            $brand_data = empty($brand_ids) ? [] : array_column(BrandService::AppointBrandList($brand_ids), null, 'id');

            // 处理数据
            foreach($config['diy_data'] as &$v)
            {
                if(!empty($v['com_data']) && !empty($v['com_data']['content']))
                {
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

                        // 数据魔方
                        case 'data-magic' :
                            if(!empty($v['com_data']['content']['data_magic_list']))
                            {
                                foreach($v['com_data']['content']['data_magic_list'] as &$dmv)
                                {
                                    if(!empty($dmv['data_content']) && isset($dmv['data_content']['data_type']) && $dmv['data_content']['data_type'] == 'goods' && !empty($dmv['data_content']['goods_ids']) && !empty($dmv['data_content']['goods_list']))
                                    {
                                        $temp = self::ConfigViewGoodsHandle(['data_type'=>0, 'data_ids'=>$dmv['data_content']['goods_ids'], 'data_list'=>$dmv['data_content']['goods_list']], $goods_data);
                                        if(!empty($temp['data_list']))
                                        {
                                            $dmv['data_content']['goods_list'] = $temp['data_list'];
                                        }
                                    }
                                }
                            }
                            break;

                        // 自定义
                        case 'custom' :
                            if(!empty($v['com_data']['content']['data_source']))
                            {
                                // 动态数据
                                if(!empty($v['com_data']['content']['data_source_content_value']))
                                {
                                    // 使用可变变量自动映射数据
                                    $temp_value = $v['com_data']['content']['data_source_content_value'];
                                    $temp_source = $v['com_data']['content']['data_source'].'_data';
                                    if(isset($temp_source) && isset($$temp_source) && is_array($$temp_source) && array_key_exists($temp_value, $$temp_source))
                                    {
                                        $v['com_data']['content']['data_source_content'] = $$temp_source[$temp_value];
                                    }
                                }

                                // 固定数据、用户信息
                                if($v['com_data']['content']['data_source'] == 'user-info')
                                {
                                    $ret = DiyApiService::UserHeadData();
                                    $ret['data']['user_avatar'] = empty($ret['data']['user']) ? UserDefaultAvatar() : $ret['data']['user']['avatar'];
                                    $ret['data']['user_name_view'] = empty($ret['data']['user']) ? '用户名称' : $ret['data']['user']['user_name_view'];
                                    unset($ret['data']['user']);
                                    $v['com_data']['content']['data_source_content'] = $ret['data'];
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

                // 唯一key生成
                $v['id'] = empty($v['com_data']) ? (empty($v['id']) ? '' : $v['id']) : md5(json_encode($v['com_data'], JSON_UNESCAPED_UNICODE));
            }

            // diy显示数据处理钩子
            $hook_name = 'plugins_module_diy_view_data_handle';
            MyEventTrigger($hook_name, [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'config'      => &$config,
                'params'      => $params,
            ]);
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
                'article_category_ids'   => isset($config['category_ids']) ? $config['category_ids'] : '',
                'article_number'         => isset($config['number']) ? $config['number'] : 4,
                'article_order_by_type'  => isset($config['order_by_type']) ? $config['order_by_type'] : 0,
                'article_order_by_rule'  => isset($config['order_by_rule']) ? $config['order_by_rule'] : 0,
                'article_is_cover'       => isset($config['is_cover']) ? $config['is_cover'] : 0,
            ];
            $config['data_auto_list'] = ArticleService::AutoArticleList($data_params);
        } else {
            if(!empty($config['data_list']) && !empty($article_data))
            {
                foreach($config['data_list'] as $dk=>$dv)
                {
                    if(!empty($dv['data_id']) && array_key_exists($dv['data_id'], $article_data))
                    {
                        $config['data_list'][$dk]['data'] = $article_data[$dv['data_id']];
                    }
                }
            }
        }
        return$config;
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
                'goods_category_ids'   => isset($config['category_ids']) ? $config['category_ids'] : '',
                'goods_brand_ids'      => isset($config['brand_ids']) ? $config['brand_ids'] : '',
                'goods_number'         => isset($config['number']) ? $config['number'] : 4,
                'goods_order_by_type'  => isset($config['order_by_type']) ? $config['order_by_type'] : 0,
                'goods_order_by_rule'  => isset($config['order_by_rule']) ? $config['order_by_rule'] : 0,
            ];
            $config['data_auto_list'] = GoodsService::AutoGoodsList($data_params, ['is_spec'=>1, 'is_cart'=>1]);
        } else {
            if(!empty($config['data_list']) && !empty($goods_data))
            {
                foreach($config['data_list'] as $dk=>$dv)
                {
                    if(!empty($dv['data_id']) && array_key_exists($dv['data_id'], $goods_data))
                    {
                        $config['data_list'][$dk]['data'] = $goods_data[$dv['data_id']];
                    }
                }
            }
        }
        return$config;
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