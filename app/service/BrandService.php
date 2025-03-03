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
        $order_by = empty($params['order_by']) ? 'sort,id asc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 品牌列表读取前钩子
        $hook_name = 'plugins_service_brand_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
            'm'             => &$m,
            'n'             => &$n,
        ]);

        // 获取列表
        $data = Db::name('Brand')->where($where)->field($field)->order($order_by)->limit($m, $n)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::BrandListHandle($data, $params));
    }

    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-11
     * @desc    description
     * @param   [array]          $data      [列表数据]
     * @param   [array]          $params    [输入参数]
     */
    public static function BrandListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 字段列表
            $keys = ArrayKeys($data);

            // 分类信息
            // 获取所有品牌关联的分类数据
            $category_group = [];
            if(in_array('id', $keys))
            {
                $category = Db::name('BrandCategoryJoin')->where(['brand_id'=>array_column($data, 'id')])->field('brand_id,brand_category_id')->select()->toArray();
                if(!empty($category))
                {
                    $ids = array_unique(array_column($category, 'brand_category_id'));
                    $names = Db::name('BrandCategory')->where(['id'=>$ids])->column('name', 'id');
                    if(!empty($names))
                    {
                        foreach($category as $c)
                        {
                            if(array_key_exists($c['brand_category_id'], $names))
                            {
                                if(!array_key_exists($c['brand_id'], $category_group))
                                {
                                    $category_group[$c['brand_id']]['ids'] = [];
                                    $category_group[$c['brand_id']]['names'] = [];
                                }
                                $category_group[$c['brand_id']]['ids'][] = $c['brand_category_id'];
                                $category_group[$c['brand_id']]['names'][] = $names[$c['brand_category_id']];
                            }
                        }
                    }
                }
            }

            // 数据处理
            foreach($data as $k=>&$v)
            {
                // 增加索引
                $v['data_index'] = $k+1;

                // url
                if(isset($v['id']))
                {
                    $v['url'] = (APPLICATION == 'web') ? MyUrl('index/search/index', ['brand'=>$v['id']]) : '/pages/goods-search/goods-search?brand='.$v['id'];
                }

                // 分类名称
                if(isset($v['id']))
                {
                    if(array_key_exists($v['id'], $category_group))
                    {
                        $v['brand_category_ids'] = $category_group[$v['id']]['ids'];
                        $v['brand_category_text'] = implode('，', $category_group[$v['id']]['names']);
                    } else {
                        $v['brand_category_ids'] = [];
                        $v['brand_category_text'] = '';
                    }
                }

                // logo
                if(isset($v['logo']))
                {
                    $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
                }

                // 品牌官方地址
                if(isset($v['website_url']))
                {
                    $v['website_url'] = empty($v['website_url']) ? null : $v['website_url'];
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
        return Db::name('Brand')->field('id,name')->where(['is_enable'=>1])->order('sort asc')->select()->toArray();
    }

    /**
     * 获取品牌名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array|int]          $brand_ids [快递id]
     */
    public static function BrandName($brand_ids = 0)
    {
        if(empty($brand_ids))
        {
            return null;
        }

        // 参数处理查询数据
        if(is_array($brand_ids))
        {
            $brand_ids = array_filter(array_unique($brand_ids));
        }
        if(!empty($brand_ids))
        {
            $data = Db::name('Brand')->where(['id'=>$brand_ids])->column('name', 'id');
        }

        // id数组则直接返回
        if(is_array($brand_ids))
        {
            return empty($data) ? [] : $data;
        }
        return (!empty($data) && is_array($data) && array_key_exists($brand_ids, $data)) ? $data[$brand_ids] : null;
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
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('common_service.brand.form_item_name_message'),
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'name',
                'checked_data'      => 'Brand',
                'checked_key'       => 'id',
                'error_msg'         => MyLang('common_service.brand.save_name_already_exist_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'brand_category_id',
                'error_msg'         => MyLang('common_service.brand.form_item_brand_category_id_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'describe',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.brand.form_item_describe_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'website_url',
                'checked_data'      => 'CheckUrl',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.brand.form_item_website_url_message'),
            ],
            [
                'checked_type'      => 'max',
                'key_name'          => 'sort',
                'checked_data'      => 255,
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_sort_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_title',
                'checked_data'      => '100',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_title_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_keywords',
                'checked_data'      => '130',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_keywords_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_desc_message'),
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
            'describe'          => $params['describe'],
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
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'data_id'       => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            if(empty($params['id']))
            {
                $data['add_time'] = time();
                $brand_id = Db::name('Brand')->insertGetId($data);
                if($brand_id <= 0)
                {
                    throw new \Exception(MyLang('insert_fail'));
                }
            } else {
                $data['upd_time'] = time();
                $brand_id = intval($params['id']);
                if(Db::name('Brand')->where(['id'=>$brand_id])->update($data) === false)
                {
                    throw new \Exception(MyLang('edit_fail'));
                }
            }

            // 添加分类
            $ret = self::BrandCategoryInsert(explode(',', $params['brand_category_id']), $brand_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }
            // 提交事务
            Db::commit();
            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
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
        return DataReturn(MyLang('insert_success'), 0);
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
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('Brand')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
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
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
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
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 指定读取品牌列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params    [输入参数]
     */
    public static function AppointBrandList($params = [])
    {
        $result = [];
        if(!empty($params['brand_ids']))
        {
            // 非数组则转为数组
            if(!is_array($params['brand_ids']))
            {
                $params['brand_ids'] = explode(',', $params['brand_ids']);
            }

            // 基础条件
            $where = [
                ['is_enable', '=', 1],
                ['id', 'in', array_unique($params['brand_ids'])]
            ];

            // 获取数据
            $ret = self::BrandList(['where'=>$where, 'm'=>0, 'n'=>0]);
            if(!empty($ret['data']))
            {
                $temp = array_column($ret['data'], null, 'id');
                foreach($params['brand_ids'] as $id)
                {
                    if(!empty($id) && array_key_exists($id, $temp))
                    {
                        $result[] = $temp[$id];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 自动读取品牌列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params [输入参数]
     */
    public static function AutoBrandList($params = [])
    {
        // 基础条件
        $where = [
            ['is_enable', '=', 1],
        ];

        // 品牌关键字
        if(!empty($params['brand_keywords']))
        {
            $where[] = ['name|describe', 'like', '%'.$params['brand_keywords'].'%'];
        }

        // 分类条件
        if(!empty($params['brand_category_ids']))
        {
            if(!is_array($params['brand_category_ids']))
            {
                $params['brand_category_ids'] = explode(',', $params['brand_category_ids']);
            }
            $ids = Db::name('BrandCategoryJoin')->where(['brand_category_id'=>$params['brand_category_ids']])->column('brand_id');
            $where[] = ['id', 'in', empty($ids) ? [0] : $ids];
        }

        // 排序
        $order_by_type_list = MyConst('common_brand_order_by_type_list');
        $order_by_rule_list = MyConst('common_data_order_by_rule_list');
        $order_by_type = !isset($params['brand_order_by_type']) || !array_key_exists($params['brand_order_by_type'], $order_by_type_list) ? $order_by_type_list[0]['value'] : $order_by_type_list[$params['brand_order_by_type']]['value'];
        $order_by_rule = !isset($params['brand_order_by_rule']) || !array_key_exists($params['brand_order_by_rule'], $order_by_rule_list) ? $order_by_rule_list[0]['value'] : $order_by_rule_list[$params['brand_order_by_rule']]['value'];
        $order_by = $order_by_type.' '.$order_by_rule;

        // 获取数据
        $ret = self::BrandList([
            'where'    => $where,
            'm'        => 0,
            'n'        => empty($params['brand_number']) ? 10 : intval($params['brand_number']),
            'order_by' => $order_by,
        ]);
        return empty($ret['data']) ? [] : $ret['data'];
    }
}
?>