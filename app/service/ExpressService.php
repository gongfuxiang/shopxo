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
 * 快递服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ExpressService
{
    /**
     * 获取快递名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array|int]          $express_ids [快递id]
     */
    public static function ExpressName($express_ids = 0)
    {
        // 参数处理
        if(empty($express_ids))
        {
            return null;
        }
        // 参数处理查询数据
        if(is_array($express_ids))
        {
            $express_ids = array_filter(array_unique($express_ids));
        }

        // 静态数据容器，确保每一个名称只读取一次，避免重复读取浪费资源
        static $express_name_static_data = [];
        $temp_express_ids = [];
        $params_express_ids = is_array($express_ids) ? $express_ids : explode(',', $express_ids);
        foreach($params_express_ids as $rid)
        {
            if(empty($express_name_static_data) || !array_key_exists($rid, $express_name_static_data))
            {
                $temp_express_ids[] = $rid;
            }
        }
        // 存在未读取的数据库读取
        if(!empty($temp_express_ids))
        {
            $data = Db::name('Express')->where(['id'=>$temp_express_ids])->column('name', 'id');
            if(!empty($data))
            {
                foreach($data as $rid=>$rv)
                {
                    $express_name_static_data[$rid] = $rv;
                }
            }
            // 空数据记录、避免重复查询
            foreach($temp_express_ids as $rid)
            {
                if(!array_key_exists($rid, $express_name_static_data))
                {
                    $express_name_static_data[$rid] = null;
                }
            }
        }

        // id数组则直接返回
        if(is_array($express_ids))
        {
            $result = [];
            if(!empty($express_name_static_data))
            {
                foreach($express_ids as $id)
                {
                    if(isset($express_name_static_data[$id]))
                    {
                        $result[$id] = $express_name_static_data[$id];
                    }
                }
            }
            return $result;
        }
        return (!empty($express_name_static_data) && is_array($express_name_static_data) && array_key_exists($express_ids, $express_name_static_data)) ? $express_name_static_data[$express_ids] : null;
    }

    /**
     * 获取快递信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array|int]          $express_ids [快递id]
     */
    public static function ExpressData($express_ids = 0)
    {
        if(empty($express_ids))
        {
            return null;
        }

        // 参数处理查询数据
        if(is_array($express_ids))
        {
            $express_ids = array_filter(array_unique($express_ids));
        }
        if(!empty($express_ids))
        {
            $data = self::DataHandle(Db::name('Express')->where(['id'=>$express_ids])->column('id,name,website_url,icon', 'id'));
        }

        // id数组则直接返回
        if(is_array($express_ids))
        {
            return empty($data) ? [] : $data;
        }
        return (!empty($data) && is_array($data) && array_key_exists($express_ids, $data)) ? $data[$express_ids] : null;
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
        $where = [];
        if(isset($params['is_enable']))
        {
            $where['is_enable'] = intval($params['is_enable']);
        }
        $data = Db::name('Express')->where($where)->field('id,icon,name,sort,is_enable')->order('sort asc')->select()->toArray();
        return self::DataHandle($data);
    }

    /**
     * 数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-06
     * @desc    description
     * @param   [array]          $data [二维数组]
     */
    public static function DataHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as &$v)
            {
                if(is_array($v))
                {
                    if(array_key_exists('icon', $v))
                    {
                        $v['icon'] = ResourcesService::AttachmentPathViewHandle($v['icon']);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 获取快递节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ExpressNodeSon($params = [])
    {
        $id = isset($params['id']) ? intval($params['id']) : 0;
        $field = 'id,pid,icon,name,website_url,sort,is_enable';
        $data = Db::name('Express')->field($field)->where(['pid'=>$id])->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            $data = self::DataHandle($data);
            foreach($data as &$v)
            {
                $v['is_son']    = (Db::name('Express')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['json']      = json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 快递保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ExpressSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.express.form_item_name_message'),
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
            'pid'           => isset($params['pid']) ? intval($params['pid']) : 0,
            'name'          => $params['name'],
            'website_url'   => empty($params['website_url']) ? '' : $params['website_url'],
            'sort'          => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'icon'          => $attachment['data']['icon'],
        ];

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('Express')->insertGetId($data);
            if($data['id'] <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('Express')->where(['id'=>intval($params['id'])])->update($data) === false)
            {
                return DataReturn(MyLang('edit_fail'), -100);
            } else {
                $data['id'] = $params['id'];
            }
        }

        $res = self::DataHandle([$data]);
        return DataReturn(MyLang('operate_success'), 0, $res[0]);
    }

    /**
     * 快递删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ExpressDelete($params = [])
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

        // 开始删除
        if(Db::name('Express')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>