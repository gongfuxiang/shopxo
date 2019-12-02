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
use think\facade\Hook;
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
        $field = empty($params['field']) ? 'a.*' : $params['field'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        $data = Db::name('Article')->alias('a')->join(['__ARTICLE_CATEGORY__'=>'ac'], 'a.article_category_id=ac.id')->field($field)->where($where)->order('a.id desc')->limit($m, $n)->select();
        if(!empty($data))
        {
            $common_is_enable_tips = lang('common_is_enable_tips');
            foreach($data as &$v)
            {
                // url
                $v['url'] = MyUrl('index/article/index', ['id'=>$v['id']]);

                // 分类名称
                if(isset($v['article_category_id']))
                {
                    $v['article_category_name'] = Db::name('ArticleCategory')->where(['id'=>$v['article_category_id']])->value('name');
                }

                // 是否启用
                if(isset($v['is_enable']))
                {
                    $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];
                }

                // 内容
                if(isset($v['content']))
                {
                    $v['content'] = ResourcesService::ContentStaticReplace($v['content'], 'get');
                }

                // 图片
                if(isset($v['images']))
                {
                    if(!empty($v['images']))
                    {
                        $images = json_decode($v['images'], true);
                        foreach($images as &$img)
                        {
                            $img = ResourcesService::AttachmentPathViewHandle($img);
                        }
                        $v['images'] = $images;
                    }
                }

                if(isset($v['add_time']))
                {
                    $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                    $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                    $v['upd_time_date'] = date('Y-m-d', $v['upd_time']);
                }
            }
        }
        return DataReturn('处理成功', 0, $data);
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
        return (int) Db::name('Article')->alias('a')->join(['__ARTICLE_CATEGORY__'=>'ac'], 'a.article_category_id=ac.id')->where($where)->count();
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
    public static function ArticleListWhere($params = [])
    {
        $where = [];

        if(!empty($params['keywords']))
        {
            $where[] = ['a.title', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_enable']) && $params['is_enable'] > -1)
            {
                $where[] = ['a.is_enable', '=', intval($params['is_enable'])];
            }
            if(isset($params['article_category_id']) && $params['article_category_id'] > -1)
            {
                $where[] = ['a.article_category_id', '=', intval($params['article_category_id'])];
            }
            if(isset($params['is_home_recommended']) && $params['is_home_recommended'] > -1)
            {
                $where[] = ['a.is_home_recommended', '=', intval($params['is_home_recommended'])];
            }
            if(isset($params['access_count']) && $params['access_count'] > -1)
            {
                $where[] = ['a.access_count', '>', intval($params['access_count'])];
            }

            if(!empty($params['time_start']))
            {
                $where[] = ['a.add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['a.add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
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
                'error_msg'         => '标题长度 2~60 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'article_category_id',
                'error_msg'         => '请选择文章分类',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'jump_url',
                'checked_data'      => 'CheckUrl',
                'is_checked'        => 1,
                'error_msg'         => '跳转url地址格式有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '10,105000',
                'error_msg'         => '内容 10~105000 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_title',
                'checked_data'      => '100',
                'is_checked'        => 1,
                'error_msg'         => 'SEO标题格式 最多100个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_keywords',
                'checked_data'      => '130',
                'is_checked'        => 1,
                'error_msg'         => 'SEO关键字格式 最多130个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => 'SEO描述格式 最多230个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 编辑器内容
        $content = isset($params['content']) ? htmlspecialchars_decode($params['content']) : '';

        // 数据
        $images = self::MatchContentImage($content);
        $data = [
            'title'                 => $params['title'],
            'title_color'           => empty($params['title_color']) ? '' : $params['title_color'],
            'article_category_id'   => intval($params['article_category_id']),
            'jump_url'              => empty($params['jump_url']) ? '' : $params['jump_url'],
            'content'               => ResourcesService::ContentStaticReplace($content, 'add'),
            'images'                => empty($images) ? '' : json_encode($images),
            'images_count'          => count($images),
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'is_home_recommended'   => isset($params['is_home_recommended']) ? intval($params['is_home_recommended']) : 0,
            'seo_title'             => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'          => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'              => empty($params['seo_desc']) ? '' : $params['seo_desc'],
        ];

        // 文章保存处理钩子
        $hook_name = 'plugins_service_article_save_handle';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
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

        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Article')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('Article')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100); 
        }
    }

    /**
     * 正则匹配文章图片
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-22T18:06:53+0800
     * @param    [string]         $content [文章内容]
     * @return   [array]                   [文章图片数组（一维）]
     */
    private static function MatchContentImage($content)
    {
        if(!empty($content))
        {
            $pattern = '/<img.*?src=[\'|\"](\/static\/upload\/article\/image\/.*?[\.gif|\.jpg|\.jpeg|\.png|\.bmp])[\'|\"].*?[\/]?>/';
            preg_match_all($pattern, $content, $match);
            return empty($match[1]) ? [] : $match[1];
        }
        return array();
    }

    /**
     * 获取分类和所有文章
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleCategoryListContent($params = [])
    {
        $data = Db::name('ArticleCategory')->field('id,name')->where(['is_enable'=>1])->order('id asc, sort asc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $items = Db::name('Article')->field('id,title,title_color')->where(['article_category_id'=>$v['id'], 'is_enable'=>1])->select();
                if(!empty($items))
                {
                    foreach($items as &$vs)
                    {
                        // url
                        $vs['url'] = MyUrl('index/article/index', ['id'=>$vs['id']]);
                    }
                }
                $v['items'] = $items;
            }
        }
        return DataReturn('处理成功', 0, $data);
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
            return Db::name('Article')->where(array('id'=>intval($params['id'])))->setInc('access_count');
        }
        return false;
    }

    /**
     * 文章分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleCategoryList($params = [])
    {
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'sort asc' : trim($params['order_by']);

        $data = Db::name('ArticleCategory')->where(['is_enable'=>1])->field($field)->order($order_by)->select();
        
        return DataReturn('处理成功', 0, $data);
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

        // 删除操作
        if(Db::name('Article')->where(['id'=>$params['id']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => '操作字段有误',
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
        if(Db::name('Article')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state'])]))
        {
            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 获取文章分类节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ArticleCategoryNodeSon($params = [])
    {
        // id
        $id = isset($params['id']) ? intval($params['id']) : 0;

        // 获取数据
        $field = '*';
        $data = Db::name('ArticleCategory')->field($field)->where(['pid'=>$id])->order('sort asc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son']            =   (Db::name('ArticleCategory')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['ajax_url']          =   MyUrl('admin/articlecategory/getnodeson', array('id'=>$v['id']));
                $v['delete_url']        =   MyUrl('admin/articlecategory/delete');
                $v['json']              =   json_encode($v);
            }
            return DataReturn('操作成功', 0, $data);
        }
        return DataReturn('没有相关数据', -100);
    }

    /**
     * 文章分类保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ArticleCategorySave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,16',
                'error_msg'         => '名称格式 2~16 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'name'                  => $params['name'],
            'pid'                   => isset($params['pid']) ? intval($params['pid']) : 0,
            'sort'                  => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('ArticleCategory')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('ArticleCategory')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100);
        }
    }

    /**
     * 文章分类删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ArticleCategoryDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '删除数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始删除
        if(Db::name('ArticleCategory')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
    }
}
?>