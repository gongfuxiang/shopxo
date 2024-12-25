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
use app\service\ResourcesService;

/**
 * 商品分类服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-10-09
 * @desc    description
 */
class GoodsCategoryService
{
    /**
     * 根据id获取一条商品分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryRow($params = [])
    {
        if(empty($params['id']))
        {
            return null;
        }
        $field = empty($params['field']) ? 'id,pid,icon,icon_active,realistic_images,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended' : $params['field'];
        $data = self::GoodsCategoryDataHandle([Db::name('GoodsCategory')->field($field)->where(['is_enable'=>1, 'id'=>intval($params['id'])])->find()]);
        return empty($data[0]) ? null : $data[0];
    }

    /**
     * 获取所有分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryAll($params = [])
    {
        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_goods_category_key');
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug') || MyC('common_data_is_use_cache') != 1)
        {
            // 获取分类
            $params['where'] = [
                ['pid', '=', 0],
                ['is_enable', '=', 1],
            ];
            $data = self::GoodsCategory($params);

            // 存储缓存
            MyCache($key, $data, 180);
        }

        // 所有商品分类数据钩子
        $hook_name = 'plugins_service_goods_category_all_data';
        MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'params'      => $params,
            'data'        => &$data,
        ]);

        return $data;
    }

    /**
     * 获取分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategory($params = [])
    {
        // 获取分类
        if(empty($params['where']))
        {
            $params['where'] = [
                ['pid', '=', 0],
                ['is_enable', '=', 1],
            ];
        }
        $data = self::GoodsCategoryList($params);
        if(!empty($data))
        {
            // 基础条件、去除pid
            $where_base = $params['where'];
            $temp_column = array_column($where_base, 0);
            if(in_array('pid', $temp_column))
            {
                unset($where_base[array_search('pid', $temp_column)]);
                sort($where_base);
            }

            // 获取所有二级
            $two_group = [];
            $params['where'] = array_merge($where_base, [['pid', 'in', array_column($data, 'id')]]);
            $two = self::GoodsCategoryList($params);
            if(!empty($two))
            {
                // 二级分组
                foreach($two as $tv)
                {
                    if(!array_key_exists($tv['pid'], $two_group))
                    {
                        $two_group[$tv['pid']] = [];
                    }
                    $two_group[$tv['pid']][] = $tv;
                }

                // 获取所有三级
                $three_group = [];
                $params['where'] = array_merge($where_base, [['pid', 'in', array_column($two, 'id')]]);
                $three = self::GoodsCategoryList($params);
                if(!empty($three))
                {
                    // 三级分组
                    foreach($three as $tv)
                    {
                        if(!array_key_exists($tv['pid'], $three_group))
                        {
                            $three_group[$tv['pid']] = [];
                        }
                        $three_group[$tv['pid']][] = $tv;
                    }
                }

                // 数据组合
                foreach($data as &$v)
                {
                    $v['items'] = (empty($two_group) || !array_key_exists($v['id'], $two_group)) ? [] : $two_group[$v['id']];
                    if(!empty($v['items']))
                    {
                        foreach($v['items'] as &$vs)
                        {
                            $vs['items'] = (empty($three_group) || !array_key_exists($vs['id'], $three_group)) ? [] : $three_group[$vs['id']];
                        }
                    }
                }
            }
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 根据pid获取商品分类列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $where[] = ['is_enable', '=', 1];
        $key = md5(json_encode($where));
        static $goods_category_list_data = [];
        if(!array_key_exists($key, $goods_category_list_data))
        {
            $order_by = empty($params['order_by']) ? 'sort asc' : trim($params['order_by']);
            $field = empty($params['field']) ? 'id,pid,icon,icon_active,realistic_images,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended,seo_title,seo_keywords,seo_desc' : $params['field'];
            $m = isset($params['m']) ? intval($params['m']) : 0;
            $n = isset($params['n']) ? intval($params['n']) : 0;

            // 商品分类列表读取前钩子
            $hook_name = 'plugins_service_goods_category_list_begin';
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

            // 获取商品分类数据
            $goods_category_list_data[$key] = self::GoodsCategoryDataHandle(Db::name('GoodsCategory')->field($field)->where($where)->order($order_by)->limit($m, $n)->select()->toArray());
        }
        return $goods_category_list_data[$key];
    }

    /**
     * 获取商品分类下的所有分类id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]         $ids       [分类id数组]
     * @param   [int]           $is_enable [是否启用 null, 0否, 1是]
     * @param   [int]           $level     [指定级别 null, 整数、默认则全部下级]
     */
    public static function GoodsCategoryItemsIds($ids = [], $is_enable = null, $level = null)
    {
        if(!is_array($ids))
        {
            $ids = explode(',', $ids);
        }
        $where = [
            ['pid', 'in', $ids],
        ];
        if($is_enable !== null)
        {
            $where[] = ['is_enable', '=', $is_enable];
        }

        // 级别记录处理
        if($level !== null)
        {
            if(is_array($level))
            {
                $level['temp'] += 1;
            } else {
                $level = [
                    'value' => $level,
                    'temp'  => 1,
                ];
            }
        }

        // 是否超过级别限制
        if($level === null || $level['temp'] < $level['value'])
        {
            static $goods_category_items_ids_data = [];
            $key = md5(json_encode($where));
            if(!array_key_exists($key, $goods_category_items_ids_data))
            {
                $goods_category_items_ids_data[$key] = Db::name('GoodsCategory')->where($where)->column('id');
            }
            $data = $goods_category_items_ids_data[$key];
            if(!empty($data))
            {
                $temp = self::GoodsCategoryItemsIds($data, $is_enable, $level);
                if(!empty($temp))
                {
                    $data = array_merge($data, $temp);
                }
            }
        }
        return empty($data) ? $ids : array_unique(array_merge($ids, $data));
    }

    /**
     * 获取商品分类的所有上级分类id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]         $ids       [分类id数组]
     * @param   [int]           $is_enable [是否启用 null, 0否, 1是]
     * @param   [int]           $level     [指定级别 null, 整数（1~3）、默认则全部上级]
     */
    public static function GoodsCategoryParentIds($ids = [], $is_enable = null, $level = null)
    {
        if(!is_array($ids))
        {
            $ids = explode(',', $ids);
        }
        $where = [
            ['id', 'in', $ids],
            ['pid', '>', 0],
        ];
        if($is_enable !== null)
        {
            $where[] = ['is_enable', '=', $is_enable];
        }

        // 级别记录处理
        if($level !== null)
        {
            if(is_array($level))
            {
                $level['temp'] += 1;
            } else {
                $level = [
                    'value' => $level,
                    'temp'  => 1,
                ];
            }
        }

        // 是否超过级别限制
        if($level === null || $level['temp'] < $level['value'])
        {
            static $goods_category_parent_ids_data = [];
            $key = md5(json_encode($where));
            if(!array_key_exists($key, $goods_category_parent_ids_data))
            {
                $goods_category_parent_ids_data[$key] = Db::name('GoodsCategory')->where($where)->column('pid');
            }
            $data = $goods_category_parent_ids_data[$key];
            if(!empty($data))
            {
                $temp = self::GoodsCategoryParentIds($data, $is_enable, $level);
                if(!empty($temp))
                {
                    $data = array_merge($data, $temp);
                }
            }
        }
        return empty($data) ? $ids : array_unique(array_merge($ids, $data));
    }

    /**
     * 获取商品关联的所有分类id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]         $goods_ids [商品id]
     */
    public static function GoodsJoinCategoryIds($goods_ids = [])
    {
        if(!is_array($goods_ids))
        {
            $goods_ids = explode(',', $goods_ids);
        }
        return array_unique(Db::name('GoodsCategoryJoin')->where(['goods_id' => $goods_ids])->column('category_id'));
    }

    /**
     * 商品分类数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-06
     * @desc    description
     * @param   [array]          $data [商品分类数据 二维数组]
     */
    public static function GoodsCategoryDataHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            $attachment_fleid = ['icon', 'icon_active', 'realistic_images', 'big_images'];
            foreach($data as &$v)
            {
                if(is_array($v))
                {
                    // 附件字段处理
                    foreach($attachment_fleid as $afv)
                    {
                        if(array_key_exists($afv, $v))
                        {
                            $v[$afv] = ResourcesService::AttachmentPathViewHandle($v[$afv]);
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 商品分类保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategorySave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.goodscategory.form_item_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'vice_name',
                'checked_data'      => '60',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.goodscategory.form_item_vice_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'describe',
                'checked_data'      => '200',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.goodscategory.form_item_describe_message'),
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

        // 其它附件
        $data_fields = ['icon', 'icon_active', 'realistic_images', 'big_images'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 数据
        $data = [
            'name'                  => $params['name'],
            'pid'                   => isset($params['pid']) ? intval($params['pid']) : 0,
            'vice_name'             => isset($params['vice_name']) ? $params['vice_name'] : '',
            'describe'              => isset($params['describe']) ? $params['describe'] : '',
            'bg_color'              => isset($params['bg_color']) ? $params['bg_color'] : '',
            'is_home_recommended'   => isset($params['is_home_recommended']) ? intval($params['is_home_recommended']) : 0,
            'sort'                  => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'icon'                  => $attachment['data']['icon'],
            'icon_active'           => $attachment['data']['icon_active'],
            'realistic_images'      => $attachment['data']['realistic_images'],
            'big_images'            => $attachment['data']['big_images'],
            'seo_title'             => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'          => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'              => empty($params['seo_desc']) ? '' : $params['seo_desc'],
        ];

        // 父级id宇当前id不能相同
        if(!empty($params['id']) && $params['id'] == $data['pid'])
        {
            return DataReturn(MyLang('common_service.goodscategory.save_current_parent_identical_tips'), -10);
        }

        // 添加/编辑
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('GoodsCategory')->insertGetId($data);
            if($data['id'] <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('GoodsCategory')->where(['id'=>intval($params['id'])])->update($data) === false)
            {
                return DataReturn(MyLang('edit_fail'), -100);
            } else {
                $data['id'] = $params['id'];
            }
        }

        // 删除大分类缓存
        MyCache(SystemService::CacheKey('shopxo.cache_goods_category_key'), null);

        $res = self::GoodsCategoryDataHandle([$data]);
        return DataReturn(MyLang('operate_success'), 0, $res[0]);
    }

    /**
     * 商品状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategoryStatusUpdate($params = [])
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

        // 捕获异常
        try {
            // 数据更新
            if(!Db::name('GoodsCategory')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
            {
                throw new \Exception(MyLang('operate_fail'));
            }

            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 获取商品分类节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategoryNodeSon($params = [])
    {
        // id
        $id = isset($params['id']) ? intval($params['id']) : 0;

        // 获取数据
        $field = 'id,pid,icon,icon_active,realistic_images,name,sort,is_enable,bg_color,big_images,vice_name,describe,is_home_recommended,seo_title,seo_keywords,seo_desc';
        $data = Db::name('GoodsCategory')->field($field)->where(['pid'=>$id])->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            $data = self::GoodsCategoryDataHandle($data);
            foreach($data as &$v)
            {
                $v['is_son']    = (Db::name('GoodsCategory')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['json']      = json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 商品分类删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategoryDelete($params = [])
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
                'key_name'          => 'admin',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取分类下所有分类id
        $ids = self::GoodsCategoryItemsIds([$params['id']]);

        // 开始删除
        if(Db::name('GoodsCategory')->where(['id'=>$ids])->delete())
        {
            // 删除大分类缓存
            MyCache(SystemService::CacheKey('shopxo.cache_goods_category_key'), null);

            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 根据商品id获取分类名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsCategoryNames($goods_id)
    {
        $data = Db::name('GoodsCategory')->alias('gc')->join('goods_category_join gci', 'gc.id=gci.category_id')->where(['gci.goods_id'=>$goods_id])->column('gc.name');
        return DataReturn(MyLang('get_success'), 0, $data);
    }
}
?>