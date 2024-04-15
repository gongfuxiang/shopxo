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
namespace app\api\controller;

use app\service\ApiService;
use app\service\SystemBaseService;
use app\service\ArticleService;
use app\service\ArticleCategoryService;
use app\service\ResourcesService;

/**
 * 文章
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-11-08
 * @desc    description
 */
class Article extends Common
{
    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }
    
    /**
     * 初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     */
    public function Index()
    {
        // 获取分类
        $article_category = ArticleCategoryService::ArticleCategoryList();

        // 返回数据
        $result = [
            'category_list' => $article_category['data'],
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }
    
    /**
     * 分类文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     */
    public function DataList()
    {
        // 参数
        $params = $this->data_request;

        // 条件
        $where = ArticleService::ArticleWhere($params);

        // 获取总数
        $total = ArticleService::ArticleTotal($where);
        $page_total = ceil($total/$this->page_size);
        $start = intval(($this->page-1)*$this->page_size);

        // 获取列表
        $data_params = array_merge($params, [
            'm'         => $start,
            'n'         => $this->page_size,
            'where'     => $where,
        ]);
        $data = ArticleService::ArticleList($data_params);
        
        // 返回数据
        $result = [
            'total'         => $total,
            'page_total'    => $page_total,
            'data'          => $data['data'],
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }

    /**
     * 文章详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     */
    public function Detail()
    {
        // 获取文章
        if(!empty($this->data_request['id']))
        {
            // 获取数据
            $id = intval($this->data_request['id']);
            $params = [
                'where'  => [
                    'is_enable' => 1,
                    'id'        => $id,
                ],
                'field'  => 'id,title,title_color,jump_url,content,access_count,article_category_id,seo_title,seo_keywords,seo_desc,add_time',
                'm'      => 0,
                'n'      => 1,
            ];
            $data = ArticleService::ArticleList($params);
            if(!empty($data['data'][0]))
            {
                // 访问统计
                ArticleService::ArticleAccessCountInc(['id'=>$id]);

                // 标签处理，兼容小程序rich-text
                $data['data'][0]['content'] = ResourcesService::ApMiniRichTextContentHandle($data['data'][0]['content']);

                // 上一篇、下一篇
                $last_next_data = ArticleService::ArticleLastNextData($id);

                // 返回数据
                $result = [
                    'data'      => $data['data'][0],
                    'last_next' => $last_next_data,
                ];
                $ret = SystemBaseService::DataReturn($result);
            } else {
                $ret = DataReturn(MyLang('article.article_no_data_tips'), -1);
            }
        } else {
            $ret = DataReturn(MyLang('article.article_id_params_tips'), -1);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>