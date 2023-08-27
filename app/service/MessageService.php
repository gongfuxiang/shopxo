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
use app\service\UserService;

/**
 * 消息服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MessageService
{
    /**
     * 消息添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param    [int]              $user_id        [用户id]
     * @param    [string]           $title          [标题]
     * @param    [string]           $detail         [内容]
     * @param    [string]           $business_type  [业务类型（默认, 如：订单, 充值, 提现, ...）]
     * @param    [int]              $business_id    [业务id]
     * @param    [int]              $type           [类型（默认0  普通消息）]
     * @return   [boolean]                          [成功true, 失败false]
     */
    public static function MessageAdd($user_id, $title, $detail, $business_type = '', $business_id = 0, $type = 0)
    {
        $data = [
            'system_type'       => SYSTEM_TYPE,
            'title'             => $title,
            'detail'            => $detail,
            'user_id'           => intval($user_id),
            'business_type'     => empty($business_type) ? MyLang('common_service.message.save_business_type_default') : trim($business_type),
            'business_id'       => intval($business_id),
            'type'              => intval($type),
            'is_read'           => 0,
            'add_time'          => time(),
        ];
        $message_id = Db::name('Message')->insertGetId($data);
        if($message_id > 0)
        {
            // 消息添加钩子
            $hook_name = 'plugins_service_message_add';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => $data,
                'data_id'       => $message_id,
            ]);
            return true;
        }
        return false;
    }

    /**
     * 前端消息列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageListWhere($params = [])
    {
        // 条件初始化
        $where = [
            ['is_delete_time', '=', 0],
        ];

        // id
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', $params['id']];
        }

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }
        
        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['title|detail', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(!empty($params['business_type']))
            {
                $where[] = ['business_type', '=', $params['business_type']];
            }
            if(isset($params['type']) && $params['type'] > -1)
            {
                $where[] = ['type', '=', intval($params['type'])];
            }
            if(isset($params['is_read']) && $params['is_read'] > -1)
            {
                $where[] = ['is_read', '=', intval($params['is_read'])];
            }

            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
    }

    /**
     * 消息总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function MessageTotal($where = [])
    {
        // 消息总数读取前钩子
        $hook_name = 'plugins_service_message_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取总数
        return (int) Db::name('Message')->where($where)->count();
    }

    /**
     * 用户消息总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [int|string]             [超过99则返回 99+]
     */
    public static function UserMessageTotal($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return 0;
        }
        $total = self::MessageTotal(self::MessageListWhere($params));
        return ($total > 99) ? '99+' : $total;
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
    public static function MessageList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 消息列表读取前钩子
        $hook_name = 'plugins_service_message_list_begin';
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

        // 获取数据列表
        $data = Db::name('Message')->where($where)->field($field)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::MessageListHandle($data, $params));
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
    public static function MessageListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 字段列表
            $keys = ArrayKeys($data);
            
            // 用户列表
            if(in_array('user_id', $keys) && isset($params['is_public']) && $params['is_public'] == 0)
            {
                $user_list = UserService::GetUserViewInfo(array_column($data, 'user_id'));
            }

            $common_is_text_list = MyConst('common_is_text_list');
            $common_is_read_list = MyConst('common_is_read_list');
            $common_message_type_list = MyConst('common_message_type_list');
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']))
                {
                    if(isset($params['is_public']) && $params['is_public'] == 0)
                    {
                        $v['user'] = (!empty($user_list) && is_array($user_list) && array_key_exists($v['user_id'], $user_list)) ? $user_list[$v['user_id']] : [];
                    }
                }

                // 消息类型
                $v['type_text'] = $common_message_type_list[$v['type']]['name'];

                // 是否已读
                $v['is_read_text'] = $common_is_read_list[$v['is_read']]['name'];

                // 用户是否已删除
                $v['user_is_delete_time_text'] = ($v['user_is_delete_time'] == 0) ? MyLang('no_title') : MyLang('yes_title');

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
            }
        }
        return $data;
    }

    /**
     * 消息更新未已读
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageRead($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 更新用户未读消息为已读
        $where = ['user_id'=>$params['user']['id'], 'is_read'=>0];
        $ret = Db::name('Message')->where($where)->update(['is_read'=>1]);
        return DataReturn(MyLang('handle_success'), 0, $ret);
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
    public static function MessageDelete($params = [])
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
        if(Db::name('Message')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }

        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>