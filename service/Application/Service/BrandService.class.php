<?php

namespace Service;

use Service\GoodsService;

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
            $category_ids = GoodsService::GoodsCategoryItemsIds(['category_id'=>$params['category_id']]);
            $where = ['g.is_delete_time'=>0, 'g.is_shelves'=>1, 'gci.id'=>['in', $category_ids]];
            $brand_ids = M('Goods')->alias('g')->join(' INNER JOIN __GOODS_CATEGORY_JOIN__ AS gci ON g.id=gci.goods_id')->field('g.brand_id')->where($where)->group('g.brand_id')->getField('brand_id', true);
            $brand_where['id'] = ['in', $brand_ids];
        }

        // 获取品牌列表
        $brand = M('Brand')->where($brand_where)->field('id,name,logo,website_url')->select();
        if(!empty($brand))
        {
            $images_host = C('IMAGE_HOST');
            foreach($brand as &$v)
            {
                $v['logo'] = $images_host.$v['logo'];
                $v['website_url'] = empty($v['website_url']) ? null : $v['website_url'];
            }
        }
        return $brand;
    }
}
?>