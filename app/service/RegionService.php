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
use app\service\SystemService;

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
     * @param   [array|int]          $region_ids [地区id]
     */
    public static function RegionName($region_ids = 0)
    {
        // 参数处理
        if(empty($region_ids))
        {
            return null;
        }
        // 参数处理查询数据
        if(is_array($region_ids))
        {
            $region_ids = array_filter(array_unique($region_ids));
        }

        // 静态数据容器，确保每一个名称只读取一次，避免重复读取浪费资源
        static $region_name_static_data = [];
        $temp_region_ids = [];
        $params_region_ids = is_array($region_ids) ? $region_ids : explode(',', $region_ids);
        foreach($params_region_ids as $rid)
        {
            if(empty($region_name_static_data) || !array_key_exists($rid, $region_name_static_data))
            {
                $temp_region_ids[] = $rid;
            }
        }
        // 存在未读取的数据库读取
        if(!empty($temp_region_ids))
        {
            $data = Db::name('Region')->where(['id'=>$temp_region_ids])->column('name', 'id');
            if(!empty($data))
            {
                foreach($data as $rid=>$rv)
                {
                    $region_name_static_data[$rid] = $rv;
                }
            }
            // 空数据记录、避免重复查询
            foreach($temp_region_ids as $rid)
            {
                if(!array_key_exists($rid, $region_name_static_data))
                {
                    $region_name_static_data[$rid] = null;
                }
            }
        }

        // id数组则直接返回
        if(is_array($region_ids))
        {
            $result = [];
            if(!empty($region_name_static_data))
            {
                foreach($region_ids as $id)
                {
                    if(isset($region_name_static_data[$id]))
                    {
                        $result[$id] = $region_name_static_data[$id];
                    }
                }
            }
            return $result;
        }
        return (!empty($region_name_static_data) && is_array($region_name_static_data) && array_key_exists($region_ids, $region_name_static_data)) ? $region_name_static_data[$region_ids] : null;
    }

    /**
     * 获取地区id下列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-09T00:13:02+0800
     * @param    [array]                    $params [输入参数]
     */
    public static function RegionItems($params = [])
    {
        $pid = isset($params['pid']) ? intval($params['pid']) : 0;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'sort asc,id asc' : trim($params['order_by']);
        return Db::name('Region')->field($field)->where(['pid'=>$pid, 'is_enable'=>1])->order($order_by)->select()->toArray();
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
        // 数据参数
        $field = empty($params['field']) ? 'id,pid,name,level,letters,code,lng,lat,sort,is_enable' : $params['field'];
        $where = empty($params['where']) ? [] : $params['where'];
        $order_by = empty($params['order_by']) ? 'sort asc,id asc' : trim($params['order_by']);

        // 基础条件
        $where[] = ['is_enable', '=', 1];
        $data = Db::name('Region')->where($where)->field($field)->order($order_by)->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son'] = (Db::name('Region')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
            }
        }
        return $data;
    }

    /**
     * 获取地区节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RegionNodeSon($params = [])
    {
        // id
        $id = isset($params['id']) ? intval($params['id']) : 0;

        // 获取数据
        $field = 'id,pid,name,level,letters,code,lng,lat,sort,is_enable';
        $data = Db::name('Region')->field($field)->where(['pid'=>$id])->order('sort asc,id asc')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son'] = (Db::name('Region')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['json'] = json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 地区保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RegionSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.region.form_item_name_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'name'      => $params['name'],
            'pid'       => isset($params['pid']) ? intval($params['pid']) : 0,
            'letters'   => empty($params['letters']) ? '' : $params['letters'],
            'code'      => empty($params['code']) ? '' : $params['code'],
            'lng'       => isset($params['lng']) ? floatval($params['lng']) : 0,
            'lat'       => isset($params['lat']) ? floatval($params['lat']) : 0,
            'sort'      => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable' => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];
        if(!empty($params['id']))
        {
            $params['id'] = intval($params['id']);
        }

		// 得到level 
		$data['level'] = ($data['pid'] > 0) ? (Db::name('Region')->where(['id'=>$data['pid']])->value('level')+1) : 1;

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('Region')->insertGetId($data);
            if($data['id'] <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('Region')->where(['id'=>intval($params['id'])])->update($data) === false)
            {
                return DataReturn(MyLang('edit_fail'), -100);
            } else {
                $data['id'] = $params['id'];
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $data);
    }

    /**
     * 地区状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RegionStatusUpdate($params = [])
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
            if(!Db::name('Region')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
            {
                throw new \Exception(MyLang('operate_fail'));
            }

            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 地区删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RegionDelete($params = [])
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
        $ids = self::RegionItemsIds([$params['id']]);
        $ids[] = $params['id'];

        // 开始删除
        if(Db::name('Region')->where(['id'=>$ids])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 获取地区下的所有子级id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $ids       [id数组]
     * @param   [int]            $is_enable [是否启用 null, 0否, 1是]
     * @param   [string]         $order_by  [排序, 默认sort asc]
     */
    public static function RegionItemsIds($ids = [], $is_enable = null, $order_by = 'sort asc')
    {
        $where = ['pid'=>$ids];
        if($is_enable !== null)
        {
            $where['is_enable'] = $is_enable;
        }
        $data = Db::name('Region')->where($where)->order($order_by)->column('id');
        if(!empty($data))
        {
            $temp = self::RegionItemsIds($data, $is_enable, $order_by);
            if(!empty($temp))
            {
                $data = array_merge($data, $temp);
            }
        }
        $data = empty($data) ? $ids : array_unique(array_merge($ids, $data));
        return $data;
    }

    /**
     * 获取地区所有数据、最多三级
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RegionAll($params = [])
    {
        // 缓存
        $key = SystemService::CacheKey('shopxo.cache_region_all_key');
        $data = MyCache($key);
        if(empty($data))
        {
            // 所有一级
            $field = 'id,pid,name,level,letters,code,lng,lat';
            $data = self::RegionNode(['field'=>$field,'where'=>[['pid', '=', 0]]]);
            if(!empty($data))
            {
                // 所有二级
                $two = self::RegionNode(['field'=>$field,'where'=>[['pid', 'in', array_column($data, 'id')]]]);
                $two_group = [];
                $three_group = [];
                if(!empty($two))
                {
                    // 所有三级
                    $three = self::RegionNode(['field'=>$field,'where'=>[['pid', 'in', array_column($two, 'id')]]]);
                    if(!empty($three))
                    {
                        // 三级集合组
                        foreach($three as $v)
                        {
                            if(!array_key_exists($v['pid'], $three_group))
                            {
                                $three_group[$v['pid']] = [];
                            }
                            $pid = $v['pid'];
                            unset($v['pid']);
                            $three_group[$pid][] = $v;
                        }
                    }

                    // 二级集合
                    foreach($two as $v)
                    {
                        // 是否存在三级数据
                        $v['items'] = array_key_exists($v['id'], $three_group) ? $three_group[$v['id']] : [];
                        

                        // 集合组
                        if(!array_key_exists($v['pid'], $two_group))
                        {
                            $two_group[$v['pid']] = [];
                        }
                        $pid = $v['pid'];
                        unset($v['pid']);
                        $two_group[$pid][] = $v;
                    }
                }

                // 一级集合
                foreach($data as $k=>$v)
                {
                    $data[$k]['items'] = array_key_exists($v['id'], $two_group) ? $two_group[$v['id']] : [];
                }

                // 存储缓存
                MyCache($key, $data, 60);
            }
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 根据编号获取地区数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function RegionCodeData($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'code',
                'error_msg'         => MyLang('common_service.region.region_code_search_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取地区
        $result = [
            'province' =>['id'=>0, 'name'=>''],
            'city'     =>['id'=>0, 'name'=>''],
            'county'   =>['id'=>0, 'name'=>''],
        ];
        $field = 'id,pid,level,name,code';
        $region = self::RegionNode(['field'=>$field, 'where'=>[['code', '=', $params['code']]]]);
        if(!empty($region) && !empty($region[0]))
        {
            $arr = [1=>'province', 2=>'city', 3=>'county'];
            if(array_key_exists($region[0]['level'], $arr))
            {
                $result[$arr[$region[0]['level']]] = ['id'=>$region[0]['id'], 'name'=>$region[0]['name']];
                // 上一级
                if($region[0]['level'] > 1)
                {
                    $region = self::RegionNode(['field'=>$field, 'where'=>[['id', '=', $region[0]['pid']]]]);
                    if(!empty($region) && !empty($region[0]))
                    {
                        if(array_key_exists($region[0]['level'], $arr))
                        {
                            $result[$arr[$region[0]['level']]] = ['id'=>$region[0]['id'], 'name'=>$region[0]['name']];
                            // 上一级
                            if($region[0]['level'] > 1)
                            {
                                $region = self::RegionNode(['field'=>$field, 'where'=>[['id', '=', $region[0]['pid']]]]);
                                if(!empty($region) && !empty($region[0]))
                                {
                                    if(array_key_exists($region[0]['level'], $arr))
                                    {
                                        $result[$arr[$region[0]['level']]] = ['id'=>$region[0]['id'], 'name'=>$region[0]['name']];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(empty($result) || count(array_filter(array_column($result, 'id'))) == 0)
        {
            return DataReturn(MyLang('common_service.region.region_no_data_tips'), -1);
        }
        return DataReturn(MyLang('get_success'), 0, $result);
    }
}
?>