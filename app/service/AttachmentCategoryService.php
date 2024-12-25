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
 * 附件分类服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AttachmentCategoryService
{
    /**
     * 分类id获取路径标识
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-17
     * @desc    description
     * @param   [int]          $category_id [分类id]
     */
    public static function AttachmentPathType($category_id)
    {
        return Db::name('AttachmentCategory')->where(['id'=>$category_id])->value('path');
    }

    /**
     * 根据路径标识附件分类id
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-17
     * @desc    description
     * @param   [int|string]      $value     [分类id或路径标识]
     * @param   [array]           $params    [输入参数]
     */
    public static function AttachmentCategoryId($value, $params = [])
    {
        // 是否存在数据
        $id = Db::name('AttachmentCategory')->where(['id|path'=>$value])->value('id');
        if(empty($id))
        {
            // 匹配名称
            $lang = MyConst('common_attachment_category_path_name_list');

            // 父级id
            $pid = 0;

            // 是否插件
            $plugins = 'plugins_';
            if(substr($value, 0, strlen($plugins)) == $plugins)
            {
                $pid = Db::name('AttachmentCategory')->where(['path'=>'plugins'])->value('id');
                if(empty($pid))
                {
                    // 父级处理
                    $name = (empty($lang) || empty($lang['plugins'])) ? 'plugins' : $lang['plugins'];
                    $ret = self::AttachmentCategorySave(['name'=>$name, 'path'=>'plugins', 'is_enable'=>1]);
                    $pid = empty($ret['data']) ? 0 : $ret['data']['id'];
                }

            // 横杠分割的数据
            } else {
                $loc = stripos($value, '-');
                if($loc !== false)
                {
                    // 父级处理
                    $first = substr($value, 0, $loc);
                    $pid = Db::name('AttachmentCategory')->where(['path'=>$first])->value('id');
                    if(empty($pid))
                    {
                        $name = (empty($lang) || empty($lang[$first])) ? $first : $lang[$first];
                        $ret = self::AttachmentCategorySave(['name'=>$name, 'path'=>$first, 'is_enable'=>1]);
                        $pid = empty($ret['data']) ? 0 : $ret['data']['id'];
                    }
                }
            }

            // 添加当前数据
            $name = (empty($lang) || empty($lang[$value])) ? (empty($params['name']) ? $value : $params['name'])  : $lang[$value];
            $ret = self::AttachmentCategorySave(['name'=>$name, 'path'=>$value, 'is_enable'=>1, 'pid'=>$pid]);
            return empty($ret['data']) ? 0 : $ret['data']['id'];
        }
        return $id;
    }

    /**
     * 附件分类所有（2级）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-15
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentCategoryAll($params = [])
    {
        // 获取分类
        if(empty($params['where']))
        {
            $params['where'] = [
                ['pid', '=', 0],
            ];
            // 非后端则限制分类条件
            if(RequestModule() != 'admin')
            {
                $params['where'][] = ['path', '=', empty($params['path_type']) ? '0-0-0' : $params['path_type']];
            }
        }
        $data = self::AttachmentCategoryList($params);
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
            $two = self::AttachmentCategoryList($params);
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
     * 附件分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AttachmentCategoryList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        if(isset($params['is_enable']))
        {
            $where[] = ['is_enable', '=', intval($params['is_enable'])];
        }
        $data = Db::name('AttachmentCategory')->where($where)->field('id,pid,icon,name,path,sort,is_enable')->order('sort asc')->select()->toArray();
        return self::DataHandle($data);
    }

    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     * @param   [array]          $data   [分类数据]
     * @param   [array]          $params [输入参数]
     */
    public static function DataHandle($data, $params = [])
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                if(array_key_exists('icon', $v))
                {
                    $v['icon'] = ResourcesService::AttachmentPathViewHandle($v['icon']);
                }
            }
        }
        return $data;
    }

    /**
     * 获取附件分类节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AttachmentCategoryNodeSon($params = [])
    {
        $id = isset($params['id']) ? intval($params['id']) : 0;
        $field = 'id,pid,icon,name,path,sort,is_enable';
        $data = Db::name('AttachmentCategory')->field($field)->where(['pid'=>$id])->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            $data = self::DataHandle($data);
            foreach($data as &$v)
            {
                $v['is_son']      = (Db::name('AttachmentCategory')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['ajax_url']    = MyUrl('admin/pluginscategory/getnodeson', array('id'=>$v['id']));
                $v['delete_url']  = MyUrl('admin/pluginscategory/delete');
                $v['json']        = json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 附件分类保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AttachmentCategorySave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.attachmentcategory.form_item_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'path',
                'checked_data'      => '1,230',
                'error_msg'         => MyLang('common_service.attachmentcategory.form_item_path_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 其它附件
        $attachment = ResourcesService::AttachmentParams($params, ['icon']);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 数据
        $data = [
            'icon'       => $attachment['data']['icon'],
            'name'       => $params['name'],
            'path'       => $params['path'],
            'pid'        => isset($params['pid']) ? intval($params['pid']) : 0,
            'sort'       => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'  => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 分类数据
        if(!empty($params['id']))
        {
            $info = Db::name('AttachmentCategory')->where(['id'=>intval($params['id'])])->find();
            if(empty($info))
            {
                return DataReturn(MyLang('data_id_error_tips'), -1);
            }
        }

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('AttachmentCategory')->insertGetId($data);
            if($data['id'] <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('AttachmentCategory')->where(['id'=>intval($params['id'])])->update($data) === false)
            {
                return DataReturn(MyLang('edit_fail'), -100);
            } else {
                $data['id'] = $params['id'];
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $data);
    }

    /**
     * 附件分类状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AttachmentCategoryStatusUpdate($params = [])
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
            if(!Db::name('AttachmentCategory')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
            {
                throw new \Exception(MyLang('operate_fail'));
            }

            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 附件分类删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AttachmentCategoryDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始删除
        if(Db::name('AttachmentCategory')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>