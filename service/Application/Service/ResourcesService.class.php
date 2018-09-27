<?php

namespace Service;

/**
 * 资源服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ResourcesService
{
    /**
     * 获取地区名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RegionName($params = [])
    {
        return M('Region')->where(['id'=>intval($params['region_id'])])->getField('name');
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
        $where = ['is_enable'=>1];
        $data = M('Express')->where($where)->field('id,icon,name,sort')->order('sort asc')->select();
        if(!empty($data) && is_array($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['icon_old'] = $v['icon'];
                $v['icon'] = empty($v['icon']) ? null : $images_host.$v['icon'];
            }
        }
        return $data;
    }

    /**
     * 支付方式列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PaymentList($params = [])
    {
        $where = empty($params['where']) ? ['is_enable'=>1] : $params['where'];
        $data = M('Payment')->where($where)->field('id,logo,name,sort,payment,config,apply_terminal,apply_terminal,element,is_enable')->order('sort asc')->select();
        if(!empty($data) && is_array($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['logo_old'] = $v['logo'];
                $v['logo'] = empty($v['logo']) ? null : $images_host.$v['logo'];
                $v['element'] = empty($v['element']) ? '' : json_decode($v['element'], true);
                $v['config'] = empty($v['config']) ? '' : json_decode($v['config'], true);
                $v['apply_terminal'] = empty($v['apply_terminal']) ? '' : json_decode($v['apply_terminal'], true);
            }
        }
        return $data;
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

        return M('Region')->where($where)->field($field)->order('id asc, sort asc')->select();
    }
}
?>