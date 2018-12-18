<?php
namespace app\service;

use app\service\GoodsService;

/**
 * 品牌服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BrandService
{
    /**
     * 品牌列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function BrandList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取品牌列表
        $data = db('Brand')->where($where)->order($order_by)->limit($m, $n)->select();
        if(!empty($data))
        {
            $common_is_enable_tips = lang('common_is_enable_tips');
            $images_host = config('IMAGE_HOST');
            foreach($data as &$v)
            {
                // 是否启用
                $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];

                // 分类名称
                $v['brand_category_name'] = db('BrandCategory')->where(['id'=>$v['brand_category_id']])->value('name');

                // logo
                $v['logo'] =  empty($v['logo']) ? '' : $images_host.$v['logo'];

                // 添加时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return $data;
    }

    /**
     * 品牌总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function BrandTotal($where)
    {
        return (int) db('Brand')->where($where)->count();
    }

    /**
     * 获取所有分类及下面品牌
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function CategoryBrand($params = [])
    {
        $data = db('BrandCategory')->where(['is_enable'=>1])->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['items'] = db('Brand')->field('id,name')->where(['is_enable'=>1, 'brand_category_id'=>$v['id']])->order('sort asc')->select();
            }
        }
        return $data;
    }

    /**
     * 分类下品牌列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CategoryBrandList($params = [])
    {
        $brand_where = ['is_enable'=>1];
        if(!empty($params['category_id']))
        {
            // 根据分类获取品牌id
            $category_ids = GoodsService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $where = ['g.is_delete_time'=>0, 'g.is_shelves'=>1, 'gci.id'=>$category_ids];
            $brand_where['id'] = db('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->field('g.brand_id')->where($where)->group('g.brand_id')->column('brand_id');
        }

        // 获取品牌列表
        $brand = db('Brand')->where($brand_where)->field('id,name,logo,website_url')->select();
        if(!empty($brand))
        {
            $images_host = config('IMAGE_HOST');
            foreach($brand as &$v)
            {
                $v['logo_old'] = $v['logo'];
                $v['logo'] = empty($v['logo']) ? null : $images_host.$v['logo'];
                $v['website_url'] = empty($v['website_url']) ? null : $v['website_url'];
            }
        }
        return $brand;
    }

    /**
     * 获取品牌名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int]          $brand_id [地区id]
     */
    public static function BrandName($brand_id = 0)
    {
        return empty($brand_id) ? null : db('Brand')->where(['id'=>intval($brand_id)])->value('name');
    }
}
?>