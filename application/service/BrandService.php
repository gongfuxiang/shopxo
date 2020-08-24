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
use app\service\GoodsService;
use app\service\ResourcesService;

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
        $order_by = empty($params['order_by']) ? 'sort asc' : trim($params['order_by']);

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取品牌列表
        $data = Db::name('Brand')->where($where)->order($order_by)->limit($m, $n)->select();
        if(!empty($data))
        {
            // 分类名称
            $cnames = [];
            $cids = array_column($data, 'brand_category_id');
            if(!empty($cids))
            {
                $cnames = Db::name('BrandCategory')->where(['id'=>$cids])->column('name', 'id');
            }

            $common_is_enable_tips = lang('common_is_enable_tips');
            foreach($data as &$v)
            {
                // 是否启用
                if(isset($v['is_enable']))
                {
                    $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];
                }

                // 分类名称
                if(isset($v['id']))
                {
                    $v['brand_category_ids'] = Db::name('BrandCategoryJoin')->where(['brand_id'=>$v['id']])->column('brand_category_id');
                    $category_name = Db::name('BrandCategory')->where(['id'=>$v['brand_category_ids']])->column('name');
                    $v['brand_category_text'] = implode('，', $category_name);
                }

                // logo
                if(isset($v['logo']))
                {
                    $v['logo_old'] = $v['logo'];
                    $v['logo'] =  ResourcesService::AttachmentPathViewHandle($v['logo']);
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
            }
        }
        return DataReturn('处理成功', 0, $data);
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
        return (int) Db::name('Brand')->where($where)->count();
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
        return Db::name('Brand')->field('id,name')->where(['is_enable'=>1])->order('sort asc')->select();
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

        // 分类id
        if(!empty($params['category_id']))
        {
            // 根据分类获取品牌id
            $category_ids = GoodsService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where = ['g.is_delete_time'=>0, 'g.is_shelves'=>1, 'gci.category_id'=>$category_ids];
            $brand_where['id'] = Db::name('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->field('g.brand_id')->where($where)->group('g.brand_id')->column('brand_id');
        }

        // 关键字
        if(!empty($params['keywords']))
        {
            $where = [
                ['title', 'like', '%'.$params['keywords'].'%']
            ];
            $brand_where['id'] = Db::name('Goods')->where($where)->group('brand_id')->column('brand_id');
        }

        // 获取品牌列表
        $brand = Db::name('Brand')->where($brand_where)->field('id,name,logo,website_url')->select();
        if(!empty($brand))
        {
            foreach($brand as &$v)
            {
                $v['logo_old'] = $v['logo'];
                $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
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
        return empty($brand_id) ? null : Db::name('Brand')->where(['id'=>intval($brand_id)])->value('name');
    }

    /**
     * 保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BrandSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,30',
                'error_msg'         => '名称格式 2~30 个字符',
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'name',
                'checked_data'      => 'Brand',
                'checked_key'       => 'id',
                'error_msg'         => '品牌已存在[{$var}]',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'brand_category_id',
                'error_msg'         => '请选择品牌分类',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'website_url',
                'checked_data'      => 'CheckUrl',
                'is_checked'        => 1,
                'error_msg'         => '官网地址格式有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '3',
                'error_msg'         => '顺序 0~255 之间的数值',
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

        // 附件
        $data_fields = ['logo'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'              => $params['name'],
            'logo'              => $attachment['data']['logo'],
            'website_url'       => empty($params['website_url']) ? '' : $params['website_url'],
            'sort'              => intval($params['sort']),
            'is_enable'         => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'seo_title'         => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'      => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'          => empty($params['seo_desc']) ? '' : $params['seo_desc'],
        ];

        // 品牌保存处理钩子
        $hook_name = 'plugins_service_brand_save_handle';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'brand_id'      => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 启动事务
        Db::startTrans();
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $brand_id = Db::name('Brand')->insertGetId($data);
            if($brand_id <= 0)
            {
                Db::rollback();
                return DataReturn('添加失败', -100);
            }
        } else {
            $data['upd_time'] = time();
            $brand_id = intval($params['id']);
            if(Db::name('Brand')->where(['id'=>$brand_id])->update($data) === false)
            {
                Db::rollback();
                return DataReturn('编辑失败', -100); 
            }
        }

        // 添加分类
        $ret = self::BrandCategoryInsert(explode(',', $params['brand_category_id']), $brand_id);
        if($ret['code'] != 0)
        {
            // 回滚事务
            Db::rollback();
            return $ret;
        }

        // 提交事务
        Db::commit();
        return DataReturn('操作成功', 0);
    }

    /**
     * 品牌分类添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-01
     * @desc    description
     * @param   [array]        $data     [分类数据]
     * @param   [int]          $brand_id [品牌id]
     */
    private static function BrandCategoryInsert($data, $brand_id)
    {
        Db::name('BrandCategoryJoin')->where(['brand_id'=>$brand_id])->delete();
        if(!empty($data))
        {
            foreach($data as $category_id)
            {
                $temp_category = [
                    'brand_id'          => $brand_id,
                    'brand_category_id' => $category_id,
                    'add_time'          => time(),
                ];
                if(Db::name('BrandCategoryJoin')->insertGetId($temp_category) <= 0)
                {
                    return DataReturn('品牌分类添加失败', -1);
                }
            }
        }
        return DataReturn('添加成功', 0);
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
    public static function BrandDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn('操作id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('Brand')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败', -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function BrandStatusUpdate($params = [])
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
                'error_msg'         => '未指定操作字段',
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
        if(Db::name('Brand')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }
}
?>