<?php
namespace app\service;

use app\service\GoodsService;

/**
 * 问答/留言服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AnswerService
{
    /**
     * 总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function AnswerTotal($where = [])
    {
        return (int) db('Answer')->where($where)->count();
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
    public static function AnswerList($params = [])
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
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = db('Answer')->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $common_is_show_list = lang('common_is_show_list');
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 用户信息
                $user = db('User')->where(['id'=>$v['user_id']])->field('username,nickname,mobile,gender,avatar')->find();
                $v['username'] = empty($user['username']) ? '' : $user['username'];
                $v['nickname'] = empty($user['nickname']) ? '' : $user['nickname'];
                $v['mobile'] = empty($user['mobile']) ? '' : $user['mobile'];
                $v['avatar'] = empty($user['avatar']) ? '' : $user['avatar'];
                $v['gender_text'] = isset($user['gender']) ? $common_gender_list[$user['gender']]['name'] : '';

                // 是否显示
                $v['is_show_text'] = $common_is_show_list[$v['is_show']]['name'];

                // 创建时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AnswerListWhere($params = [])
    {
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

        if(!empty($params['keywords']))
        {
            $where[] = ['name|tel|content', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_show']) && $params['is_show'] > -1)
            {
                $where[] = ['is_show', '=', intval($params['is_show'])];
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
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AnswerDelete($params = [])
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
                'key_name'          => 'user_type',
                'error_msg'         => '用户类型有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'    => intval($params['id']),
        ];

        // 用户类型
        if($params['user_type'] == 'user')
        {
            if(empty($params['user']))
            {
                return DataReturn('用户信息有误', -1);
            }
            $where['user_id'] = $params['user']['id'];
        }

        // 获取数据
        $temp = db('Answer')->where($where)->field('id')->find();
        if(empty($temp))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }

        // 开始删除
        $data = [
            'is_delete_time'   => time(),
        ];
        if(db('Answer')->where($where)->update($data))
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -1);
    }

    /**
     * 回复
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AnswerReply($params = [])
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
                'key_name'          => 'reply',
                'error_msg'         => '回复内容不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'reply',
                'checked_data'      => '2,230',
                'error_msg'         => '回复内容格式 2~230 个字符',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'    => intval($params['id']),
        ];

        // 问答是否存在
        $temp = db('Answer')->where($where)->field('id')->find();
        if(empty($temp))
        {
            return DataReturn('资源不存在或已被删除', -2);
        }
        // 更新问答
        $data = [
            'reply'     => $params['reply'],
            'is_reply'  => 1,
            'upd_time'  => time()
        ];
        if(db('Answer')->where($where)->update($data))
        {
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AnswerStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(db('Answer')->where(['id'=>intval($params['id'])])->update(['is_show'=>intval($params['state'])]))
        {
            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }
}
?>