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

/**
 * 文章分类服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ArticleCategoryService
{
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
        $data = Db::name('ArticleCategory')->where(['is_enable'=>1])->field($field)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::CategoryDataHandle($data, $params));
    }

    /**
     * 分类处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     * @param   [array]          $data   [分类数据]
     * @param   [array]          $params [输入参数]
     */
    public static function CategoryDataHandle($data, $params = [])
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                if(APPLICATION == 'web')
                {
                    $request_params = ['id'=>$v['id']];
                    if(!empty($params['awd']))
                    {
                        $request_params['awd'] = $params['awd'];
                    }
                    $v['url'] = MyUrl('index/article/category', $request_params);
                } else {
                    $v['url'] = '/pages/article-category/article-category?id='.$v['id'];
                }
            }
        }
        return $data;
    }

    /**
     * 获取分类信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     * @param   [array]           $data   [指定分类数据列表]
     */
    public static function ArticleCategoryInfo($params = [], $data = [])
    {
        // 数据不存在则读取
        if(!empty($params['id']))
        {
            if(empty($data))
            {
                $data = Db::name('ArticleCategory')->where(['is_enable'=>1,'id'=>intval($params['id'])])->field('*')->order('sort asc')->select()->toArray();
            } else {
                $temp = array_column($data, null, 'id');
                $data = array_key_exists($params['id'], $temp) ? [$temp[$params['id']]] : [];
            }
        } else {
            $data = [];
        }
        $data = self::CategoryDataHandle($data);
        return (empty($data) || empty($data[0])) ? null : $data[0];
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
        $data = Db::name('ArticleCategory')->field($field)->where(['pid'=>$id])->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son']  =   (Db::name('ArticleCategory')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['json']    =   json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
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
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.articlecategory.form_item_name_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'name'       => $params['name'],
            'pid'        => isset($params['pid']) ? intval($params['pid']) : 0,
            'sort'       => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'  => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('ArticleCategory')->insertGetId($data);
            if($data['id'] <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
            
        } else {
            $data['upd_time'] = time();
            if(Db::name('ArticleCategory')->where(['id'=>intval($params['id'])])->update($data) === false)
            {
                return DataReturn(MyLang('edit_fail'), -100);
            } else {
                $data['id'] = $params['id'];
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $data);
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
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
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
        $data = Db::name('ArticleCategory')->field('id,name')->where(['is_enable'=>1])->order('id asc, sort asc')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $items = Db::name('Article')->field('id,title,title_color')->where(['article_category_id'=>$v['id'], 'is_enable'=>1])->select()->toArray();
                if(!empty($items))
                {
                    foreach($items as &$vs)
                    {
                        // url
                        $vs['url'] = (APPLICATION == 'web') ? MyUrl('index/article/index', ['id'=>$vs['id']]) : '/pages/article-detail/article-detail?id='.$vs['id'];
                    }
                }
                $v['items'] = $items;
            }
        }
        return DataReturn(MyLang('handle_success'), 0, $data);
    }
}
?>