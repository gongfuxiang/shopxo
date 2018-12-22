<?php
namespace app\service;

use think\Db;

/**
 * 快递服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ExpressService
{
    /**
     * 获取地区名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int]          $express_id [快递id]
     */
    public static function ExpressName($express_id = 0)
    {
        return empty($express_id) ? null : Db::name('Express')->where(['id'=>intval($express_id)])->value('name');
    }

    /**
     * 快递列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ExpressList($params = [])
    {
        $where = [];
        if(isset($params['is_enable']))
        {
            $where['is_enable'] = intval($params['is_enable']);
        }
        $data = Db::name('Express')->where($where)->field('id,icon,name,sort,is_enable')->order('sort asc')->select();
        if(!empty($data) && is_array($data))
        {
            $images_host = config('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['icon_old'] = $v['icon'];
                $v['icon'] = empty($v['icon']) ? null : $images_host.$v['icon'];
            }
        }
        return $data;
    }
}
?>