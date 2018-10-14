<?php

namespace Service;

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
     * @param    [int]              $business_type  [业务类型（0默认, 1订单, ...）]
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
            'add_time'          => time(),
        );
        return M('Message')->add($data) > 0;
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
    public static function HomeMessgeListWhere($params = [])
    {
        $where = [
            'is_delete_time'        => 0,
            'user_is_delete_time'   => 0,
        ];

        // id
        if(!empty($params['id']))
        {
            $where['id'] = intval($params['id']);
        }

        // 用户id
        if(!empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        if(!empty($params['keywords']))
        {
            $like_keywords = array('like', '%'.I('keywords').'%');
            $where[] = [
                    'title'     => $like_keywords,
                    'detail'    => $like_keywords,
                    '_logic'        => 'or',
                ];
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            // 等值
            if(I('business_type', -1) > -1)
            {
                $where['business_type'] = intval(I('business_type', 0));
            }
            if(I('type', -1) > -1)
            {
                $where['type'] = intval(I('type', 0));
            }
            if(I('is_read', -1) > -1)
            {
                $where['is_read'] = intval(I('is_read', 0));
            }

            // 时间
            if(!empty($_REQUEST['time_start']))
            {
                $where['add_time'][] = array('gt', strtotime(I('time_start')));
            }
            if(!empty($_REQUEST['time_end']))
            {
                $where['add_time'][] = array('lt', strtotime(I('time_end')));
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
        return (int) M('Message')->where($where)->count();
    }

    /**
     * 订单列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'where',
                'error_msg'         => '条件不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'where',
                'error_msg'         => '条件格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_start',
                'error_msg'         => '分页起始值有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_number',
                'error_msg'         => '分页数量不能为空',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $limit_start = max(0, intval($params['limit_start']));
        $limit_number = max(1, intval($params['limit_number']));
        $order_by = empty($params['order_by']) ? 'id desc' : I('order_by', '', '', $params);

        // 获取订单
        $data = M('Message')->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $common_business_type_list = L('common_business_type_list');
            $common_is_read_list = L('common_is_read_list');
            $common_message_type_list = L('common_message_type_list');
            foreach($data as &$v)
            {
                // 消息类型
                $v['type_name'] = $common_message_type_list[$v['type']]['name'];

                // 是否已读
                $v['is_read_name'] = $common_is_read_list[$v['is_read']]['name'];

                // 业务类型
                $v['business_type_name'] = $common_business_type_list[$v['business_type']]['name'];
            }
        }
        return DataReturn('处理成功', 0, $data);
    }
}
?>