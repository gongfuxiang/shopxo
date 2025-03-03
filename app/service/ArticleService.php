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

use think\facade\Db;
use app\service\SystemService;
use app\service\ResourcesService;

/**
 * 文章服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ArticleService
{
    /**
     * 推荐文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function RecommendedArticleList($params = [])
    {
        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_home_article_list_key').APPLICATION_CLIENT_TYPE;
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug') || MyC('common_data_is_use_cache') != 1)
        {
            // 文章
            $params = [
                'where'  => ['is_enable'=>1, 'is_home_recommended'=>1],
                'field'  => 'id,title,title_color,article_category_id',
                'm'      => 0,
                'n'      => 9,
            ];
            $ret = self::ArticleList($params);
            $data = empty($ret['data']) ? [] : $ret['data'];

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }

    /**
     * 获取文章列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleList($params)
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? self::ArticleByOrder($params) : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        $data = Db::name('Article')->field($field)->where($where)->order($order_by)->limit($m, $n)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::ArticleListHandle($data, $params));
    }

    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-09
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 字段列表
            $keys = ArrayKeys($data);

            // 分类名称
            if(in_array('article_category_id', $keys))
            {
                $category_names = Db::name('ArticleCategory')->where(['id'=>array_column($data, 'article_category_id')])->column('name', 'id');
            }

            foreach($data as $k=>&$v)
            {
                // 增加索引
                $v['data_index'] = $k+1;

                // url
                $v['url'] = (APPLICATION == 'web') ? MyUrl('index/article/index', ['id'=>$v['id']]) : '/pages/article-detail/article-detail?id='.$v['id'];

                // 分类名称
                if(isset($v['article_category_id']))
                {
                    $v['article_category_name'] = (!empty($category_names) && isset($category_names[$v['article_category_id']])) ? $category_names[$v['article_category_id']] : '';
                    $v['category_url'] = (APPLICATION == 'web') ? MyUrl('index/article/category', ['id'=>$v['article_category_id']]) : '/pages/article-category/article-category?id='.$v['article_category_id'];
                }

                // 内容
                if(isset($v['content']))
                {
                    $v['content'] = ResourcesService::ContentStaticReplace($v['content'], 'get');
                }

                // 封面图片
                if(isset($v['cover']))
                {
                    $v['cover'] = ResourcesService::AttachmentPathViewHandle($v['cover']);
                }

                // 分享图片
                if(isset($v['share_images']))
                {
                    $v['share_images'] = ResourcesService::AttachmentPathViewHandle($v['share_images']);
                }

                // 图片
                if(!empty($v['images']))
                {
                    $images = json_decode($v['images'], true);
                    if(!empty($images) && is_array($images))
                    {
                        foreach($images as $ik=>$iv)
                        {
                            $images[$ik] = ResourcesService::AttachmentPathViewHandle($iv);
                        }
                        $v['images'] = $images;
                    }
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
            }
        }
        return $data;
    }

    /**
     * 文章总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function ArticleTotal($where)
    {
        return (int) Db::name('Article')->where($where)->count();
    }

    /**
     * 条件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleWhere($params = [])
    {
        // 默认条件
        $where = [
            ['is_enable', '=', 1],
        ];

        // 分类id
        if(!empty($params['id']))
        {
            $where[] = ['article_category_id', '=', intval($params['id'])];
        }

        // 搜索关键字
        if(!empty($params['awd']))
        {
            // WEB端则处理关键字
            if(APPLICATION_CLIENT_TYPE == 'pc')
            {
                $params['awd'] = AsciiToStr($params['awd']);
            }
            $where[] = ['title', 'like', '%'.$params['awd'].'%'];
        }

        return $where;
    }

    /**
     * 排序
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleByOrder($params = [])
    {
        $order_by = 'id desc';
        if(!empty($params['order_by_key']) && !empty($params['order_by_val']))
        {
            $arr = [
                'hot'  => 'access_count',
                'new'  => 'id',
            ];
            if(array_key_exists($params['order_by_key'], $arr))
            {
                $order_by = $arr[$params['order_by_key']].' '.$params['order_by_val'];
            }
        }
        return $order_by;
    }

    /**
     * 文章保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '2,60',
                'error_msg'         => MyLang('common_service.article.form_item_title_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'article_category_id',
                'error_msg'         => MyLang('common_service.article.form_item_article_category_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'jump_url',
                'checked_data'      => 'CheckUrl',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.article.form_item_jump_url_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'describe',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.article.form_item_describe_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_title',
                'checked_data'      => '100',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_title_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_keywords',
                'checked_data'      => '130',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_keywords_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_desc_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 其它附件
        $attachment = ResourcesService::AttachmentParams($params, ['cover', 'share_images']);

        // 编辑器内容
        $content = empty($params['content']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['content']), 'add');

        // 详情图片
        $images = ResourcesService::RichTextMatchContentAttachment($content, 'article', 'images');

        // 数据
        $data = [
            'title'                 => $params['title'],
            'title_color'           => empty($params['title_color']) ? '' : $params['title_color'],
            'article_category_id'   => intval($params['article_category_id']),
            'jump_url'              => empty($params['jump_url']) ? '' : $params['jump_url'],
            'describe'              => empty($params['describe']) ? '' : strip_tags($params['describe']),
            'content'               => $content,
            'cover'                 => $attachment['data']['cover'],
            'images'                => empty($images) ? '' : json_encode($images),
            'images_count'          => count($images),
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'is_home_recommended'   => isset($params['is_home_recommended']) ? intval($params['is_home_recommended']) : 0,
            'share_images'          => $attachment['data']['share_images'],
            'seo_title'             => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'          => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'              => empty($params['seo_desc']) ? '' : $params['seo_desc'],
        ];

        // 文章保存处理钩子
        $hook_name = 'plugins_service_article_save_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'article_id'    => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 添加或保存
        if(empty($params['id']))
        {
            // 增加描述为空则截取内容前面部分
            if(empty($data['describe']) && !empty($content))
            {
                $data['describe'] = mb_substr(strip_tags($content), 0, 200, 'utf-8');
            }

            $data['add_time'] = time();
            $article_id = Db::name('Article')->insertGetId($data);
            if($article_id <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            $article_id = intval($params['id']);
            if(!Db::name('Article')->where(['id'=>$article_id])->update($data))
            {
                return DataReturn(MyLang('edit_fail'), -100);
            }
        }

        // 文章保存处理成功钩子
        $hook_name = 'plugins_service_article_save_success_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => $data,
            'article_id'    => $article_id,
        ]);

        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 文章访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleAccessCountInc($params = [])
    {
        if(!empty($params['id']))
        {
            return Db::name('Article')->where(array('id'=>intval($params['id'])))->inc('access_count')->update();
        }
        return false;
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
    public static function ArticleDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('Article')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }

        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ArticleStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Article')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }

    /**
     * 上一篇、下一篇数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-09
     * @desc    description
     * @param   [int]          $article_id [文章id]
     */
    public static function ArticleLastNextData($article_id)
    {
        // 指定字段
        $field = 'id,title,add_time';

        // 上一条数据
        $where = [
            ['is_enable', '=', 1],
            ['id', '<', $article_id],
        ];
        $last = self::ArticleListHandle(Db::name('Article')->where($where)->field($field)->order('id desc')->limit(1)->select()->toArray());

        // 下一条数据
        $where = [
            ['is_enable', '=', 1],
            ['id', '>', $article_id],
        ];
        $next = self::ArticleListHandle(Db::name('Article')->where($where)->field($field)->order('id asc')->limit(1)->select()->toArray());

        return [
            'last'  => empty($last) ? null : $last[0],
            'next'  => empty($next) ? null : $next[0],
        ];
    }

    /**
     * 指定读取文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params      [输入参数]
     */
    public static function AppointArticleList($params = [])
    {
        $result = [];
        if(!empty($params['article_ids']))
        {
            // 非数组则转为数组
            if(!is_array($params['article_ids']))
            {
                $params['article_ids'] = explode(',', $params['article_ids']);
            }

            // 基础条件
            $where = [
                ['is_enable', '=', 1],
                ['id', 'in', array_unique($params['article_ids'])]
            ];

            // 获取数据
            $ret = self::ArticleList(['where'=>$where, 'm'=>0, 'n'=>0]);
            if(!empty($ret['data']))
            {
                $temp = array_column($ret['data'], null, 'id');
                foreach($params['article_ids'] as $id)
                {
                    if(!empty($id) && array_key_exists($id, $temp))
                    {
                        $result[] = $temp[$id];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 自动读取文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params [输入参数]
     */
    public static function AutoArticleList($params = [])
    {
        // 基础条件
        $where = [
            ['is_enable', '=', 1],
        ];

        // 文章关键字
        if(!empty($params['article_keywords']))
        {
            $where[] = ['title|describe', 'like', '%'.$params['article_keywords'].'%'];
        }

        // 分类条件
        if(!empty($params['article_category_ids']))
        {
            if(!is_array($params['article_category_ids']))
            {
                $params['article_category_ids'] = explode(',', $params['article_category_ids']);
            }
            $where[] = ['article_category_id', 'in', GoodsCategoryService::GoodsCategoryItemsIds($params['article_category_ids'], 1)];
        }

        // 是否有封面
        if(isset($params['article_is_cover']) && $params['article_is_cover'] == 1)
        {
            $where[] = ['cover', '<>', ''];
        }

        // 排序
        $order_by_type_list = MyConst('common_article_order_by_type_list');
        $order_by_rule_list = MyConst('common_data_order_by_rule_list');
        $order_by_type = !isset($params['article_order_by_type']) || !array_key_exists($params['article_order_by_type'], $order_by_type_list) ? $order_by_type_list[0]['value'] : $order_by_type_list[$params['article_order_by_type']]['value'];
        $order_by_rule = !isset($params['article_order_by_rule']) || !array_key_exists($params['article_order_by_rule'], $order_by_rule_list) ? $order_by_rule_list[0]['value'] : $order_by_rule_list[$params['article_order_by_rule']]['value'];
        $order_by = $order_by_type.' '.$order_by_rule;

        // 获取数据
        $ret = self::ArticleList([
            'where'    => $where,
            'm'        => 0,
            'n'        => empty($params['article_number']) ? 10 : intval($params['article_number']),
            'order_by' => $order_by,
        ]);
        return empty($ret['data']) ? [] : $ret['data'];
    }
}
?>