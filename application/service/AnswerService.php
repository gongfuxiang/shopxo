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
use app\service\UserService;

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
        return (int) Db::name('Answer')->where($where)->count();
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
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('Answer')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_is_show_list = lang('common_is_show_list');
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']))
                {
                    $user = UserService::GetUserViewInfo($v['user_id']);
                    if(!isset($params['is_public']) || $params['is_public'] == 1)
                    {
                        $v['user'] = [
                            'avatar'            => $user['avatar'],
                            'user_name_view'    => $user['user_name_view'],
                        ];
                    } else {
                        $v['user'] = $user;
                    }
                }

                // 是否显示
                if(isset($v['is_show']))
                {
                    $v['is_show_text'] = $common_is_show_list[$v['is_show']]['name'];
                }

                // 内容
                if(!empty($v['content']))
                {
                    $v['content'] = str_replace("\n", '<br />', $v['content']);
                }

                // 回复内容
                if(!empty($v['reply']))
                {
                    $v['reply'] = str_replace("\n", '<br />', $v['reply']);
                }

                // 回复时间
                if(isset($v['reply_time']))
                {
                    $reply_time = $v['reply_time'];
                    $v['reply_time'] = empty($reply_time) ? '' : date('Y-m-d H:i:s', $reply_time);
                    $v['reply_time_date'] = empty($reply_time) ? '' : date('Y-m-d', $reply_time);
                }

                // 创建时间
                if(isset($v['add_time']))
                {
                    $add_time = $v['add_time'];
                    $v['add_time'] = empty($add_time) ? '' : date('Y-m-d H:i:s', $add_time);
                    $v['add_time_date'] = empty($add_time) ? '' : date('Y-m-d', $add_time);
                }

                // 更新时间
                if(isset($v['upd_time']))
                {
                    $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                }
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
        $where = [];

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
            $where[] = ['name|tel|title|content', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_show']) && $params['is_show'] > -1)
            {
                $where[] = ['is_show', '=', intval($params['is_show'])];
            }
            if(isset($params['is_reply']) && $params['is_reply']> -1)
            {
                $where[] = ['is_reply', '=', intval($params['is_reply'])];
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
     * 用户留言保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AnswerSave($params = [])
    {
        // 是否开启登录留言,管理员登录状态可继续操作
        if(MyC('common_is_login_answer') == 1 && session('admin') === null)
        {
            $user = UserService::LoginUserInfo();
            if(empty($user))
            {
                return DataReturn('请先登录', -400);
            }
        }

        // 参数校验
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => '联系人最多30个字符',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'tel',
                'error_msg'         => '联系电话有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '60',
                'is_checked'        => 1,
                'error_msg'         => '标题最多60个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'content',
                'error_msg'         => '详细内容不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '1000',
                'error_msg'         => '详细内容格式 2~1000 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始操作
        $data = [
            'user_id'       => isset($params['user']['id']) ? intval($params['user']['id']) : (isset($params['user_id']) ? intval($params['user_id']) : 0),
            'name'          => isset($params['name']) ? $params['name'] : '',
            'tel'           => isset($params['tel']) ? $params['tel'] : '',
            'title'         => isset($params['title']) ? $params['title'] : '',
            'content'       => $params['content'],
            'reply'         => isset($params['reply']) ? $params['reply'] : '',
            'access_count'  => isset($params['access_count']) ? intval($params['access_count']) : 0,
            'is_reply'      => isset($params['is_reply']) ? intval($params['is_reply']) : 0,
            'is_show'       => isset($params['is_show']) ? intval($params['is_show']) : 0,
            'add_time'      => time(),
        ];

        // 回复时间
        $data['reply_time'] = (isset($data['is_reply']) && $data['is_reply'] == 1) ? time() : 0;

        // 不存在添加，则更新
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Answer')->insertGetId($data) > 0)
            {
                return DataReturn('提交成功', 0);
            }
            return DataReturn('提交失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('Answer')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100); 
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
        $ret = ParamsChecked($params, $p);
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

        // 开始删除
        if(Db::name('Answer')->where($where)->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败或数据不存在', -1);
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
                'checked_data'      => '2,1000',
                'error_msg'         => '回复内容格式 2~1000 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $where = [
            'id'    => intval($params['id']),
        ];

        // 问答是否存在
        $temp = Db::name('Answer')->where($where)->field('id')->find();
        if(empty($temp))
        {
            return DataReturn('资源不存在或已被删除', -2);
        }
        // 更新问答
        $data = [
            'reply'         => $params['reply'],
            'is_reply'      => 1,
            'reply_time'    => time(),
            'upd_time'      => time()
        ];
        if(Db::name('Answer')->where($where)->update($data))
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Answer')->where(['id'=>intval($params['id'])])->update(['is_show'=>intval($params['state'])]))
        {
            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AnswerAccessCountInc($params = [])
    {
        if(!empty($params['answer_id']))
        {
            return Db::name('Answer')->where(['id'=>intval($params['answer_id'])])->setInc('access_count');
        }
        return false;
    }
}
?>