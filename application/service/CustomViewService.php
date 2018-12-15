<?php
namespace app\service;

use app\service\ResourcesService;

/**
 * 自定义页面服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CustomViewService
{
    /**
     * 获取自定义列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CustomViewList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? 'id,title,content,is_header,is_footer,is_full_screen,access_count' : $params['field'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        $data = db('CustomView')->field($field)->where($where)->order('id desc')->limit($m, $n)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                if(isset($v['content']))
                {
                    $v['content'] = ResourcesService::ContentStaticReplace($v['content'], 'get');
                }
                if(isset($v['add_time']))
                {
                    $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                    $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                }
            }
        }
        return $data;
    }

    /**
     * 自定义页面访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CustomViewAccessCountInc($params = [])
    {
        if(!empty($params['id']))
        {
            return db('CustomView')->where(array('id'=>intval($params['id'])))->setInc('access_count');
        }
        return false;
    }
}
?>