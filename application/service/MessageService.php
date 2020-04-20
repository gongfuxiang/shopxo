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
     * @param    [int]              $business_type  [业务类型（0默认, 1订单, 2充值, 3提现, ...）]
     * @param    [int]              $business_id    [业务id]
     * @param    [int]              $type           [类型（默认0  普通消息）]
     * @return   [boolean]                          [成功true, 失败false]
     */
    public static function MessageAdd($user_id, $title, $detail, $business_type = 0, $business_id = 0, $type = 0)
    {
        $data = array(
            'title'             => $title,
            'detail'            => $detail,
            'user_id'           => intval($user_id),
            'business_type'     => intval($business_type),
            'business_id'       => intval($business_id),
            'type'              => intval($type),
            'is_read'           => 0,
            'add_time'          => time(),
        );
        return Db::name('Message')->insertGetId($data) > 0;
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
            if(isset($params['business_type']) && $params['business_type'] > -1)
            {
                $where[] = ['business_type', '=', intval($params['business_type'])];
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
                'error_msg'         => '用户信息有误',
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
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('Message')->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_business_type_list = lang('common_business_type_list');
            $common_is_read_list = lang('common_is_read_list');
            $common_message_type_list = lang('common_message_type_list');
            foreach($data as &$v)
            {
                // 消息类型
                $v['type_name'] = $common_message_type_list[$v['type']]['name'];

                // 是否已读
                $v['is_read_name'] = $common_is_read_list[$v['is_read']]['name'];

                // 业务类型
                $v['business_type_name'] = $common_business_type_list[$v['business_type']]['name'];

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
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
                'error_msg'         => '用户信息有误',
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
        return DataReturn('处理成功', 0, $ret);
    }

    /**
     * 后台管理员列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AdminMessageList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = 'm.*,u.username,u.nickname,u.mobile,u.email,u.gender';
        $order_by = empty($params['order_by']) ? 'm.id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('Message')->alias('m')->join(['__USER__'=>'u'], 'u.id=m.user_id')->where($where)->field($field)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_business_type_list = lang('common_business_type_list');
            $common_is_read_list = lang('common_is_read_list');
            $common_message_type_list = lang('common_message_type_list');
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 消息类型
                $v['type_name'] = $common_message_type_list[$v['type']]['name'];

                // 是否已读
                $v['is_read_name'] = $common_is_read_list[$v['is_read']]['name'];

                // 业务类型
                $v['business_type_name'] = $common_business_type_list[$v['business_type']]['name'];

                // 用户是否已删除
                $v['user_is_delete_time_name'] = ($v['user_is_delete_time'] == 0) ? '否' : '是';

                // 性别
                $v['gender_text'] = $common_gender_list[$v['gender']]['name'];

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 后台消息总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function AdminMessageTotal($where = [])
    {
        return (int) Db::name('Message')->alias('m')->join(['__USER__'=>'u'], 'u.id=m.user_id')->where($where)->count();
    }

    /**
     * 后台消息列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AdminMessageListWhere($params = [])
    {
        $where = [
            ['m.is_delete_time', '=', 0],
        ];
        
        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['m.title|m.detail|u.username|u.nickname|u.mobile', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['business_type']) && $params['business_type'] > -1)
            {
                $where[] = ['m.business_type', '=', intval($params['business_type'])];
            }
            if(isset($params['type']) && $params['type'] > -1)
            {
                $where[] = ['m.type', '=', intval($params['type'])];
            }
            if(isset($params['is_read']) && $params['is_read'] > -1)
            {
                $where[] = ['m.is_read', '=', intval($params['is_read'])];
            }
            if(isset($params['gender']) && $params['gender'] > -1)
            {
                $where[] = ['u.gender', '=', intval($params['gender'])];
            }
            if(isset($params['user_is_delete_time']) && $params['user_is_delete_time'] > -1)
            {
                if(intval($params['user_is_delete_time']) == 0)
                {
                    $where[] = ['m.user_is_delete_time', '=', 0];
                } else {
                    $where[] = ['m.user_is_delete_time', '>', 0];
                }
            }

            if(!empty($params['time_start']))
            {
                $where[] = ['m.add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['m.add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
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
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除操作
        if(Db::name('Message')->where(['id'=>$params['id']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
    }
}
?>