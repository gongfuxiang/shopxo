<?php

namespace Service;

/**
 * 支付宝生活号服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeService
{
    /**
     * 消息添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageAdd($params = [])
    {
        // 参数校验
        $ret = self::MessageAddCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始处理业务
        $data = [
            'user_id'                       => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'alipay_life_user_id'           => isset($params['alipay_life_user_id']) ? intval($params['alipay_life_user_id']) : 0,
            'alipay_life_id'                => empty($params['alipay_life_id']) ? 0 : intval($params['alipay_life_id']),
            'alipay_life_category_id'       => empty($params['alipay_life_category_id']) ? '' : json_encode(explode(',', $params['alipay_life_category_id'])),
            'type'                          => intval($params['type']),
            'send_type'                     => intval($params['send_type']),
            'status'                        => 0,
            'title'                         => I('title', '', null, $params),
            'content'                       => I('content', '', null, $params),
            'url'                           => I('url', '', null, $params),
            'action_name'                   => I('action_name', '', null, $params),
            'add_time'                      => time(),
        ];
        if(M('AlipayLifeMessage')->add($data))
        {
            return DataReturn(L('common_operation_add_success'), 0);
        }
        return DataReturn(L('common_operation_add_error'), -100);
    }

    /**
     * 消息添加参数校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageAddCheck($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => [0,1],
                'error_msg'         => '消息类型有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'send_type',
                'checked_data'      => [0,1],
                'error_msg'         => '发送类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'content',
                'error_msg'         => '消息内容有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 发送类型
        $p = [];
        switch($params['send_type'])
        {
            // 单条
            case 0 :
                $p[] = [
                    'checked_type'      => 'empty',
                    'key_name'          => 'user_id',
                    'error_msg'         => '指定用户id有误',
                ];
                $p[] = [
                    'checked_type'      => 'empty',
                    'key_name'          => 'alipay_life_user_id',
                    'error_msg'         => '指定生活号用户id有误',
                ];
                break;

            // 批量
            case 1 :
                if(empty($params['alipay_life_category_id']) && empty($params['alipay_life_id']))
                {
                    return DataReturn('批量id有误', -1);
                }
                break;

        }

        // 图文
        if($params['type'] == 1)
        {
            // 图片项
            if(empty($_FILES['file_image_url']))
            {
                return DataReturn('请上传封面图片', -1);
            }

            // 文本项
            $p[] = [
                'checked_type'      => 'isset',
                'key_name'          => 'title',
                'error_msg'         => '消息标题有误',
            ];
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'url',
                'error_msg'         => '图文url跳转地址有误',
            ];
            $p[] = [
                'checked_type'      => 'isset',
                'key_name'          => 'action_name',
                'error_msg'         => '链接文字有误',
            ];
        }

        // 验证
        if(!empty($p))
        {
           $ret = params_checked($params, $p);
            if($ret !== true)
            {
                return DataReturn($ret, -1);
            } 
        }
        
        return DataReturn('验证成功', 0);
    }

    /**
     * 根据appid获取一条生活号事件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppidLifeRow($params = [])
    {
        if(!empty($params['appid']))
        {
            return M('AlipayLife')->where(['appid'=>$params['appid']])->find();
        }
        return null;
    }

    /**
     * 用户取消关注生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [boolean]                [成功true, 失败false]
     */
    public static function UserUnfollow($params = [])
    {
        if(!empty($params['alipay_openid']))
        {
            $life = self::AppidLifeRow($params);
            $user = M('User')->where(['alipay_openid'=>$params['alipay_openid']])->find();
            if(!empty($life) && !empty($user))
            {
                return M('AlipayLifeUser')->where(['user_id'=>$user['id'], 'alipay_life_id'=>$life['id']])->delete() !== false;
            }
        }
        return false;
    }

    /**
     * 用户关注/进入生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [boolean]                [成功true, 失败false]
     */
    public static function UserEnter($params = [])
    {
        $life = self::AppidLifeRow($params);
        if(!empty($params['alipay_openid']) && !empty($life))
        {
            $user = M('User')->where(['alipay_openid'=>$params['alipay_openid']])->find();
            if(empty($user))
            {
                $data = [
                    'alipay_openid'     => $params['alipay_openid'],
                    'nickname'          => isset($params['user_name']) ? $params['user_name'] : '',
                    'add_time'          => time(),
                ];
                $user_id = M('User')->add($data);
            } else {
                $user_id = $user['id'];
            }
            if(!empty($user_id))
            {
                $life_user_data = [
                    'user_id'       => $user_id,
                    'alipay_life_id'=> $life['id'],
                ];
                $life_user = M('AlipayLifeUser')->where($life_user_data)->find();
                if(empty($life_user))
                {
                    $life_user_data['add_time'] = time();
                    return M('AlipayLifeUser')->add($life_user_data) > 0;
                } else {
                    return M('AlipayLifeUser')->where($life_user_data)->save(['enter_count'=>$life_user['enter_count']+1, 'upd_time'=>time()]) !== false;
                }
            }
        }
        return false;
    }

    /**
     * 消息发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageSubmit($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '消息id有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取消息
        $m = M('AlipayLifeMessage');
        $message = $m->find(intval($params['id']));
        if(empty($message))
        {
            return DataReturn('消息纪录不存在', -1);
        }
        $common_send_status_list = L('common_send_status_list');
        if($message['status'] != 0)
        {
            return DataReturn('状态不可操作['.$common_send_status_list[$message['status']]['name'].']', -2);
        }

        // 发送类型
        $user = [];
        if($message['send_type'] == 1)
        {
            if(!empty($message['alipay_life_category_id']))
            {
                $category_all = json_decode($message['alipay_life_category_id'], true);
                $alipay_life_all = M('AlipayLifeCategoryJoin')->where(['alipay_life_category_id'=>['in', $category_all]])->group('alipay_life_id')->getField('alipay_life_id', true);
            } else {
                $alipay_life_all = [$message['alipay_life_id']];
            }
            foreach($alipay_life_all as $alipay_life_id)
            {
                $temp_user = M('AlipayLifeUser')->where(['alipay_life_id'=>$alipay_life_id])->select();
                if(!empty($temp_user))
                {
                    foreach($temp_user as $u)
                    {
                        $alipay_openid = M('User')->where(['id'=>$u['user_id']])->getField('alipay_openid');
                        if(!empty($alipay_openid))
                        {
                            $user[] = [
                                'user_id'               => $u['user_id'],
                                'alipay_life_id'        => $u['alipay_life_id'],
                                'alipay_life_user_id'   => $u['id'],
                                'alipay_openid'         => $alipay_openid,
                                'alipay_life_message_id'=> $message['id'],
                            ];
                        }
                    }
                }
            }
        } else {
            $alipay_openid = M('User')->where(['id'=>$message['user_id']])->getField('alipay_openid');
            if(!empty($alipay_openid))
            {
                $user[] = [
                    'user_id'               => $message['user_id'],
                    'alipay_life_id'        => M('AlipayLifeUser')->where(['id'=>$message['alipay_life_user_id']])->getField('alipay_life_id'),
                    'alipay_life_user_id'   => $message['alipay_life_user_id'],
                    'alipay_openid'         => $alipay_openid,
                    'alipay_life_message_id'=> $message['id'],
                ];
            }
            
        }

        // 入库详情表
        $m->startTrans();
        if(M('AlipayLifeMessageDetail')->addAll($user) !== false)
        {
            if($m->where(['id'=>$message['id']])->save(['status'=>1, 'send_startup_time'=>time(), 'upd_time'=>time()]) !== false)
            {
                self::SyncJobSend($message['id']);
                $m->commit();
                return DataReturn(L('common_submit_success'), 0);
            }
        }
        $m->rollback();
        return DataReturn(L('common_submit_error'), -100);
    }

    /**
     * 消息异步发送触发
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [type]          $message_id [description]
     */
    public static function SyncJobSend($message_id)
    {
        SyncJob(ApiUrl('AlipayLife', 'Send', ['message_id'=>$message_id]));
    }

    /**
     * 消息发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageSend($params = [])
    {
        if(empty($params['message_id']))
        {
            die('[params_time:'.date('Y-m-d H:i:s')."][msg:id有误]\n\n");
        }

        // 启动开始
        echo '[start_time:'.date('Y-m-d H:i:s')."]\n";
        echo '[message:'.$params['message_id']."]\n";

        // 开始处理
        $m = M('AlipayLifeMessage');
        $message = $m->find($params['message_id']);
        if(empty($message))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$params['message_id']}数据不存在]\n\n");
        }
        if(!in_array($message['status'], [0,1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$message['status']}状态不可操作]\n\n");
        }

        // 消息类型
        if($message['send_type'] == 1)
        {
            if(!empty($message['alipay_life_category_id']))
            {
                $category_all = json_decode($message['alipay_life_category_id'], true);
                $alipay_life_all = M('AlipayLifeCategoryJoin')->where(['alipay_life_category_id'=>['in', $category_all]])->group('alipay_life_id')->getField('alipay_life_id', true);
            } else {
                $alipay_life_all = [$message['alipay_life_id']];
            }
        } else {
            $alipay_life_all = [M('AlipayLifeUser')->where(['id'=>$message['alipay_life_user_id']])->getField('alipay_life_id')];
        }

        // 生活号循环处理
        foreach($alipay_life_all as $alipay_life_id)
        {
            // 生活号
            $life = M('AlipayLife')->find($alipay_life_id);

            // 群发
            if($message['send_type'] == 1)
            {
                die('all');
            // 单个消息发送
            } else {
                $detail_m = M('AlipayLifeMessageDetail');
                $detail = $detail_m->where(['alipay_life_message_id'=>$message['id'], 'status'=>0])->limit(100)->select();
                if(!empty($detail))
                {
                    $obj = new \Library\AlipayLife(['life_data'=>$life]);
                    foreach($detail as $v)
                    {
                        // 请求接口处理
                        $message['alipay_openid'] = $v['alipay_openid'];
                        $ret = $obj->CustomSend($message);

                        // 返回状态更新
                        $status = (isset($ret['status']) && $ret['status'] == 0) ? 2 : 4;
                        $detail_m->where(['id'=>$v['id']])->save(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'send_return_msg'=>$ret['msg']]);
                    }
                    echo '[count:'.count($detail).']';
                } else {
                    $status_all = $detail_m->where(['alipay_life_message_id'=>$message['id']])->group('status')->getField('status', true);
                    if(count($status_all) <= 1)
                    {
                        $status = in_array(2, $status_all) ? 2 : 4;
                    } else {
                        $status = 3;
                    }
                    $m->where(['id'=>$message['id']])->save(['send_success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
                    echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
                    echo '[message:'.$params['message_id']."]\n\n";
                }
            }
        }

        // 继续运行脚本
        self::SyncJobSend($message['id']);

        // end
        die('[end_time:'.date('Y-m-d H:i:s')."][msg:处理结束]\n\n");
    }
}
?>