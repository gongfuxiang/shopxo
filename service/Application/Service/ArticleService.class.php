<?php

namespace Service;

/**
 * 文章
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

        $data = M('Article')->alias('a')->join(' INNER JOIN __ARTICLE_CATEGORY__ AS ac ON a.article_category_id=ac.id') ->field($field)->where($where)->order('a.id desc')->limit($m, $n)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                if(isset($v['content']))
                {
                    $v['content'] = ContentStaticReplace($v['content'], 'get');
                }
                if(isset($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
            }
        }
        return $data;
    }
}
?>