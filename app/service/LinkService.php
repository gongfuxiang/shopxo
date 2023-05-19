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
use app\service\GoodsService;

/**
 * 友情链接服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class LinkService
{
    /**
     * 首页展示列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function HomeLinkList($params = [])
    {
        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_home_link_list_key');
        $data = MyCache($key);
        if($data == null || MyEnv('app_debug'))
        {
            $is_mobile = IsMobile();
            if(!$is_mobile || ($is_mobile && MyC('home_index_friendship_link_status') == 1))
            {
                $ret = self::LinkList(['where'=>['is_enable'=>1]]);
                $data = empty($ret['data']) ? [] : $ret['data'];
            } else {
                $data = [];
            }

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }

    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LinkList($params = [])
    {
        $field = empty($params['field']) ? '*' : $params['field'];
        $where = empty($params['where']) ? [] : $params['where'];
        $order_by = empty($params['order_by']) ? 'sort asc,id desc' : trim($params['order_by']);
        $data = Db::name('Link')->field($field)->where($where)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::LinkListHandle($data, $params));
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function LinkListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
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
     * 保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LinkSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.link.form_item_name_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'url',
                'checked_data'      => 'CheckUrl',
                'error_msg'         => MyLang('common_service.link.form_item_url_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '3',
                'error_msg'         => MyLang('form_sort_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_new_window_open',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_is_new_window_open_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_enable',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_is_enable_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'describe',
                'checked_data'      => '60',
                'error_msg'         => MyLang('common_service.link.form_item_desc_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'name'                  => $params['name'],
            'describe'              => $params['describe'],
            'url'                   => $params['url'],
            'sort'                  => intval($params['sort']),
            'is_enable'             => intval($params['is_enable']),
            'is_new_window_open'    => intval($params['is_new_window_open']),
        ];

        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Link')->insertGetId($data) > 0)
            {
                return DataReturn(MyLang('insert_success'), 0);
            }
            return DataReturn(MyLang('insert_fail'), -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('Link')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn(MyLang('edit_success'), 0);
            }
            return DataReturn(MyLang('edit_fail'), -100); 
        }
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LinkDelete($params = [])
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
        if(Db::name('Link')->where(['id'=>$params['ids']])->delete())
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
    public static function LinkStatusUpdate($params = [])
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
        if(Db::name('Link')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }
}
?>