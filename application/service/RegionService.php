<?php
namespace app\service;

use think\Db;

/**
 * 地区服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class RegionService
{
    /**
     * 获取地区名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int]          $region_id [地区id]
     */
    public static function RegionName($region_id = 0)
    {
        return empty($region_id) ? null : Db::name('Region')->where(['id'=>intval($region_id)])->value('name');
    }

    /**
     * 获取地区idx下列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-09T00:13:02+0800
     * @param    [array]                    $param [输入参数]
     */
    public static function RegionItems($param = [])
    {
        $pid = isset($param['pid']) ? intval($param['pid']) : 0;
        return Db::name('Region')->where(['pid'=>$pid, 'is_enable'=>1])->select();
    }

    /**
     * 获取地区节点数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RegionNode($params = [])
    {
        $field = empty($params['field']) ? 'id,name,level,letters' : $params['field'];
        $where = empty($params['where']) ? [] : $params['where'];
        $where['is_enable'] = 1;

        return Db::name('Region')->where($where)->field($field)->order('id asc, sort asc')->select();
    }
}
?>