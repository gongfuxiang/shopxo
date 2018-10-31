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
     * 消息保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageSave($params = [])
    {
        // 参数校验
        $ret = self::MessageSaveCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 数据项
        $data = [
            'user_id'                       => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'alipay_life_user_id'           => isset($params['alipay_life_user_id']) ? intval($params['alipay_life_user_id']) : 0,
            'alipay_life_ids'               => empty($params['alipay_life_ids']) ? 0 : json_encode(explode(',', $params['alipay_life_ids'])),
            'msg_type'                      => intval($params['msg_type']),
            'send_type'                     => intval($params['send_type']),
            'status'                        => 0,
        ];

        // 开始处理业务
        $status = false;
        $m = M('AlipayLifeMessage');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->add($data))
            {
                $status = true;
                $msg = L('common_operation_add_success');
            } else {
                $msg = L('common_operation_add_error');
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->save($data))
            {
                $status = true;
                $msg = L('common_operation_edit_success');
            } else {
                $msg = L('common_operation_edit_error');
            }
        }

        return DataReturn($msg, $status ? 0 : -100);
    }

    /**
     * 消息保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageContentSave($params = [])
    {
        // 参数校验
        $ret = self::MessageContentSaveCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始处理业务
        $data = [
            'alipay_life_message_id'        => intval($params['message_id']),
            'title'                         => I('title', '', null, $params),
            'content'                       => I('content', '', null, $params),
            'url'                           => I('url', '', null, $params),
            'action_name'                   => I('action_name', '', null, $params),
            'image_url'                     => isset($params['image_url']) ? $params['image_url'] : '',
            'add_time'                      => time(),
        ];

        // 图片
        if(isset($_FILES['file_image_url']['error']))
        {
            $path = DS.'Public'.DS.'Upload'.DS.'alipay_life_message'.DS.date('Y').DS.date('m').DS.date('d').DS;
            $file_obj = new \Library\FileUpload(['root_path'=>ROOT_PATH, 'path'=>$path]);
            $ret = $file_obj->Save('file_image_url');
            if($ret['status'] === true)
            {
                $data['image_url'] = $ret['data']['url'];

                // 图片上传至支付宝
                $alipay_life_message = M('AlipayLifeMessage')->find($data['alipay_life_message_id']);
                if(!empty($alipay_life_message))
                {
                    if($alipay_life_message['send_type'] == 1 && !empty($alipay_life_message['alipay_life_ids']))
                    {
                        $alipay_life_ids = json_decode($alipay_life_message['alipay_life_ids'], true);
                        $$alipay_life_id = isset($alipay_life_ids[0]) ? $alipay_life_ids[0] : '';
                    } else {
                        $alipay_life_id = M('AlipayLifeUser')->where(['id'=>$alipay_life_message['alipay_life_user_id']])->getField('alipay_life_id');
                    }
                }
                if(!empty($alipay_life_id))
                {
                    $obj = new \Library\AlipayLife(['life_data'=>M('AlipayLife')->find($alipay_life_id)]);
                    $res = $obj->UploadImage(['file'=>ROOT_PATH.substr($data['image_url'], 1)]);
                    $data['out_image_url'] = (isset($res['status']) && $res['status'] == 0) ? $res['data'] : '';
                }
            }
        }

        // 开始处理业务
        $status = false;
        $m = M('AlipayLifeMessageContent');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->add($data))
            {
                $status = true;
                $msg = L('common_operation_add_success');
            } else {
                $msg = L('common_operation_add_error');
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->save($data))
            {
                $status = true;
                $msg = L('common_operation_edit_success');
            } else {
                $msg = L('common_operation_edit_error');
            }
        }

        return DataReturn($msg, $status ? 0 : -100);
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
    public static function MessageSaveCheck($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'in',
                'key_name'          => 'msg_type',
                'checked_data'      => [0,1],
                'error_msg'         => '消息类型有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'send_type',
                'checked_data'      => [0,1],
                'error_msg'         => '发送类型有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 发送类型
        $p = [];
        if($params['send_type'] == 0)
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '指定用户id有误',
            ];
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'alipay_life_user_id',
                'error_msg'         => '指定用户生活号关联id有误',
            ];
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'alipay_life_ids',
                'error_msg'         => '指定用户生活号id有误',
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
     * 消息内容添加参数校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageContentSaveCheck($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'message_id',
                'error_msg'         => '消息id有误',
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

        // 主数据
        $message = M('AlipayLifeMessage')->find($params['message_id']);
        if(empty($message))
        {
            return DataReturn('消息数据不存在', -1);
        }

        // 图文
        $p = [];
        if($message['msg_type'] == 1)
        {
            // 图片
            if(empty($_FILES['file_image_url']) && empty($params['image_url']))
            {
                return DataReturn('请上传封面图片', -1);
            }

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

        // 获取消息内容
        $content_count = (int) M('AlipayLifeMessageContent')->where(['alipay_life_message_id'=>$message['id']])->count();
        if(empty($content_count))
        {
            return DataReturn('消息内容不能为空', -1);
        }

        switch($message['send_type'])
        {
            case 0 :
                if($content_count > 1)
                {
                    return DataReturn('消息内容不存在', -1);
                }
                break;

            case 1 :
                if($content_count > 10)
                {
                    return DataReturn('群发消息不能超过10条内容', -1);
                }
                break;
        }

        // 发送类型
        $data = [];
        if($message['send_type'] == 1)
        {
            $alipay_life_all = json_decode($message['alipay_life_ids'], true);
            foreach($alipay_life_all as $alipay_life_id)
            {
                $data[] = [
                    'alipay_life_id'        => $alipay_life_id,
                    'alipay_life_message_id'=> $message['id'],
                ];
            }
        } else {
            $alipay_openid = M('User')->where(['id'=>$message['user_id']])->getField('alipay_openid');
            if(!empty($alipay_openid))
            {
                $data[] = [
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
        if(M('AlipayLifeMessageDetail')->addAll($data) !== false)
        {
            if($m->where(['id'=>$message['id']])->save(['status'=>1, 'startup_time'=>time(), 'upd_time'=>time()]) !== false)
            {
                $m->commit();
                self::SyncJobSend($message['id'], 'message_id', 'MessageSend');
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
     * @param   [int]          $value_id    [id值]
     * @param   [string]       $field_name  [字段名称]
     * @param   [string]       $action      [方法]
     */
    public static function SyncJobSend($value_id, $field_name, $action)
    {
        SyncJob(ApiUrl('AlipayLife', $action, [$field_name=>$value_id]));
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
        if(!in_array($message['status'], [1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$message['status']}状态不可操作]\n\n");
        }

        // 发送消息类型
        if($message['send_type'] == 1)
        {
            $alipay_life_all = json_decode($message['alipay_life_ids'], true);
        } else {
            $alipay_life_all = [M('AlipayLifeUser')->where(['id'=>$message['alipay_life_user_id']])->getField('alipay_life_id')];
        }

        // 消息内容
        $message['content'] = M('AlipayLifeMessageContent')->field('id,title,content,out_image_url,url,action_name')->where(['alipay_life_message_id'=>$message['id']])->select();
        if(empty($message['content']))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$message['id']}消息内容为空]\n\n");
        }

        // 生活号循环处理
        $detail_m = M('AlipayLifeMessageDetail');
        foreach($alipay_life_all as $alipay_life_id)
        {
            // 生活号
            $life = M('AlipayLife')->find($alipay_life_id);

            // 获取消息详情
            $detail = $detail_m->where(['alipay_life_message_id'=>$message['id'], 'status'=>0])->limit(100)->select();

            if(!empty($detail))
            {
                $obj = new \Library\AlipayLife(['life_data'=>$life]);
                foreach($detail as $v)
                {
                    // 群发
                    if($message['send_type'] == 1)
                    {
                        // 请求接口处理
                        $ret = $obj->GroupSend($message);
                    } else {
                        // 请求接口处理
                        $message['alipay_openid'] = $v['alipay_openid'];
                        $ret = $obj->CustomSend($message);
                    }

                    // 返回状态更新
                    $status = (isset($ret['status']) && $ret['status'] == 0) ? 2 : 4;
                    $detail_m->where(['id'=>$v['id']])->save(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$ret['msg']]);
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
                $m->where(['id'=>$message['id']])->save(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
                echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
                echo '[message:'.$params['message_id']."]\n\n";
            }
        }

        // 继续运行脚本
        self::SyncJobSend($message['id'], 'message_id', 'MessageSend');

        // end
        die('[end_time:'.date('Y-m-d H:i:s')."][msg:处理结束]\n\n");
    }

    /**
     * 生活号搜索
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AlipayLifeSearch($params = [])
    {
        $where = ['l.is_shelves'=>1];

        // 分类
        if(!empty($params['category_id']))
        {
            $where['lc.alipay_life_category_id'] = intval($params['category_id']);
        }

        // 关键字
        if(!empty($params['keywords']))
        {
            $where['l.name'] = ['like', '%'.I('keywords', '', '', $params).'%'];
        }

        // 查询数据
        $data = M('AlipayLife')->alias('l')->join(' INNER JOIN __ALIPAY_LIFE_CATEGORY_JOIN__ AS lc ON l.id=lc.alipay_life_id')->field('l.id,l.name')->group('l.id')->where($where)->select();

        if(empty($data))
        {
            return DataReturn(L('common_not_data_tips'), -100);
        } else {
            return DataReturn(L('common_operation_success'), 0, $data);
        }
    }

    /**
     * 消息详情列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MessageDetailList($params = [])
    {
        // 基础参数
        if(empty($params['message_id']))
        {
            return [];
        }

        // 条件
        $where = ['alipay_life_message_id' => intval($params['message_id'])];

        // 列表
        $data = M('AlipayLifeMessageDetail')->where($where)->order('id desc')->select();
        if(!empty($data))
        {
            $common_send_status_list = L('common_send_status_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_send_status_list[$v['status']]['name'];

                // 生活号
                $v['alipay_life_name'] = empty($v['alipay_life_id']) ? '' : M('AlipayLife')->where(['id'=>$v['alipay_life_id']])->getField('name');

                // 用户
                $v['alipay_openid'] = empty($v['user_id']) ? '' :  M('User')->where(['id'=>$v['user_id']])->getField('alipay_openid');

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                $v['send_time'] = empty($v['send_time']) ? '' : date('Y-m-d H:i:s', $v['send_time']);
            }
        }
        return $data;
    }

    /**
     * 生活号状态操作
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    上架/下架操作
     * @param   [array]          $params [输入参数]
     */
    public static function LifeStatus($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'alipay_life_id',
                'error_msg'         => '生活号id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
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
        $m = M('AlipayLife');
        $m->startTrans();
        if($m->where(array('id'=>$params['alipay_life_id']))->save(array('is_shelves'=>$params['status'], 'upd_time'=>time())))
        {
            $obj = new \Library\AlipayLife(['life_data'=>$m->find($params['alipay_life_id'])]);
            if($params['status'] == 1)
            {
                $ret = $obj->LifeAboard();
            } else {
                $ret = $obj->LifeDebark();
            }
            if($ret['status'] == 0)
            {
                $m->commit();
                return DataReturn(L('common_operation_edit_success'), 0);
            } else {
                $m->rollback();
                return DataReturn($ret['msg'], -100);
            }
        }

        $m->rollback();
        return DataReturn(L('common_operation_edit_error'), -100);
    }

    /**
     * 菜单保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MenuSave($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '名称不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '30',
                'error_msg'         => '名称最多30个字符',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'type',
                'error_msg'         => '请选择菜单类型',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'alipay_life_ids',
                'error_msg'         => '请选择生活号',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据项
        $data = [
            'name'              => I('name', '', '', $params),
            'type'              => intval(I('type', 0, '', $params)),
            'alipay_life_ids'   => empty($params['alipay_life_ids']) ? 0 : json_encode(explode(',', $params['alipay_life_ids'])),
        ];

        // 开始处理业务
        $status = false;
        $m = M('AlipayLifeMenu');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->add($data))
            {
                $status = true;
                $msg = L('common_operation_add_success');
            } else {
                $msg = L('common_operation_add_error');
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->save($data))
            {
                $status = true;
                $msg = L('common_operation_edit_success');
            } else {
                $msg = L('common_operation_edit_error');
            }
        }

        return DataReturn($msg, $status ? 0 : -100);
    }

    /**
     * 菜单内容保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MenuContentSave($params = [])
    {
        // 参数校验
        $ret = self::MenuContentSaveCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始处理业务
        $data = [
            'alipay_life_menu_id'   => intval($params['menu_id']),
            'pid'                   => intval(I('pid', 0, null, $params)),
            'name'                  => I('name', '', null, $params),
            'action_type'           => intval(I('action_type', 0, null, $params)),
            'action_value'          => I('action_value', '', null, $params),
            'icon'                  => isset($params['icon']) ? $params['icon'] : '',
            'sort'                  => intval(I('sort', 0)),
            'add_time'              => time(),
        ];

        // 图片
        if(isset($_FILES['file_icon']['error']))
        {
            $path = DS.'Public'.DS.'Upload'.DS.'alipay_life_menu'.DS.date('Y').DS.date('m').DS.date('d').DS;
            $file_obj = new \Library\FileUpload(['root_path'=>ROOT_PATH, 'path'=>$path]);
            $ret = $file_obj->Save('file_icon');
            if($ret['status'] === true)
            {
                $data['icon'] = $ret['data']['url'];

                // 图片上传至支付宝
                $alipay_life_message = M('AlipayLifeMessage')->find($data['alipay_life_message_id']);
                if(!empty($alipay_life_message))
                {
                    if($alipay_life_message['send_type'] == 1 && !empty($alipay_life_message['alipay_life_ids']))
                    {
                        $alipay_life_ids = json_decode($alipay_life_message['alipay_life_ids'], true);
                        $$alipay_life_id = isset($alipay_life_ids[0]) ? $alipay_life_ids[0] : '';
                    } else {
                        $alipay_life_id = M('AlipayLifeUser')->where(['id'=>$alipay_life_message['alipay_life_user_id']])->getField('alipay_life_id');
                    }
                }
                if(!empty($alipay_life_id))
                {
                    $obj = new \Library\AlipayLife(['life_data'=>M('AlipayLife')->find($alipay_life_id)]);
                    $res = $obj->UploadImage(['file'=>ROOT_PATH.substr($data['icon'], 1)]);
                    $data['out_icon'] = (isset($res['status']) && $res['status'] == 0) ? $res['data'] : '';
                }
            }
        }

        // 开始处理业务
        $status = false;
        $m = M('AlipayLifeMenuContent');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->add($data))
            {
                $status = true;
                $msg = L('common_operation_add_success');
            } else {
                $msg = L('common_operation_add_error');
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->save($data))
            {
                $status = true;
                $msg = L('common_operation_edit_success');
            } else {
                $msg = L('common_operation_edit_error');
            }
        }

        return DataReturn($msg, $status ? 0 : -100);
    }

    /**
     * 菜单内容参数校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MenuContentSaveCheck($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'menu_id',
                'error_msg'         => '菜单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '名称不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '5',
                'error_msg'         => '名称最多5个字符',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'action_type',
                'error_msg'         => '请选择事件类型',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 主数据
        $menu = M('AlipayLifeMenu')->find($params['menu_id']);
        if(empty($menu))
        {
            return DataReturn('消息数据不存在', -1);
        }

        // 图标
        if($menu['type'] == 1)
        {
            // 图片
            if(empty($_FILES['file_icon']) && empty($params['icon']))
            {
                return DataReturn('请上传图标', -1);
            }
        }

        // 事件值
        $p = [];
        if($params['action_type'] < 1)
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'action_value',
                'error_msg'         => '事件值不能为空',
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
     * 菜单发布提交
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MenuSubmit($params = [])
    {
        // 基础参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '菜单id有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取消息
        $m = M('AlipayLifeMenu');
        $menu = $m->find(intval($params['id']));
        if(empty($menu))
        {
            return DataReturn('菜单纪录不存在', -1);
        }
        $common_release_status_list = L('common_release_status_list');
        if(!in_array($menu['status'], [0,4]))
        {
            return DataReturn('状态不可操作['.$common_release_status_list[$menu['status']]['name'].']', -2);
        }

        // 获取消息内容
        $content_list = M('AlipayLifeMenuContent')->field('id,name')->where(['alipay_life_menu_id'=>$menu['id'], 'pid'=>0])->select();
        if(empty($content_list))
        {
            return DataReturn('菜单数据不能为空', -1);
        }

        $content_count = count($content_list);
        switch($menu['type'])
        {
            case 0 :
                if($content_count > 4)
                {
                    return DataReturn('文字菜单最多4个', -1);
                }

                // 子集校验
                foreach($content_list as $v)
                {
                    $temp_count = M('AlipayLifeMenuContent')->where(['pid'=>$v['id']])->count();
                    if($temp_count > 5)
                    {
                        return DataReturn('['.$v['name'].']二级菜单不能超过5个', -1);
                    }
                }
                break;

            case 1 :
                if($content_count > 8)
                {
                    return DataReturn('图标菜单最多8个', -1);
                }
                break;
        }

        // 生活号
        $data = [];
        $alipay_life_all = json_decode($menu['alipay_life_ids'], true);
        foreach($alipay_life_all as $alipay_life_id)
        {
            $data[] = [
                'alipay_life_id'    => $alipay_life_id,
                'alipay_life_menu_id'    => $menu['id'],
                'add_time'          => time(),
            ];
        }

        // 入库详情表
        $m->startTrans();
        if(M('AlipayLifeMenuDetail')->addAll($data) !== false)
        {
            $menu_data = [
                'status'            => 1,
                'startup_time'      => time(),
                'upd_time'          => time()
            ];
            $status = M('AlipayLifeMenu')->where(['id'=>$menu['id']])->save($menu_data);
            if($status !== false)
            {
                $m->commit();
                self::SyncJobSend($menu['id'], 'menu_id', 'MenuRelease');
                return DataReturn(L('common_submit_success'), 0);
            }
        }
        $m->rollback();
        return DataReturn(L('common_submit_error'), -100);
    }

    /**
     * 菜单发布
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MenuRelease($params = [])
    {
        if(empty($params['menu_id']))
        {
            die('[params_time:'.date('Y-m-d H:i:s')."][msg:id有误]\n\n");
        }

        // 启动开始
        echo '[start_time:'.date('Y-m-d H:i:s')."]\n";
        echo '[menu:'.$params['menu_id']."]\n";

        // 开始处理
        $m = M('AlipayLifeMenu');
        $menu = $m->find($params['menu_id']);
        if(empty($menu))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$params['menu_id']}数据不存在]\n\n");
        }
        if(!in_array($menu['status'], [1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$menu['status']}状态不可操作]\n\n");
        }

        // 生活号
        $alipay_life_all = json_decode($menu['alipay_life_ids'], true);

        // 消息内容
        $field = 'id,pid,name,action_type,action_value,out_icon';
        $menu['content'] = M('AlipayLifeMenuContent')->field($field)->where(['alipay_life_menu_id'=>$menu['id'], 'pid'=>0])->order('sort asc')->select();
        if(empty($menu['content']))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$menu['id']}菜单内容为空]\n\n");
        }

        // 是否文本类型
        $action_type_list = L('common_alipay_life_menu_action_type_list');
        foreach($menu['content'] as &$v)
        {
            // 外部事件值
            $v['action_type'] = $action_type_list[$v['action_type']]['out_value'];

            // 是否需要读取子集
            if($menu['type'] == 0)
            {
                $items = M('AlipayLifeMenuContent')->field($field)->where(['pid'=>$v['id']])->order('sort asc')->select();
                if(!empty($items))
                {
                    foreach($items as &$vs)
                    {
                        // 外部事件值
                        $vs['action_type'] = $action_type_list[$vs['action_type']]['out_value'];
                    }
                }
                $v['items'] = $items;
            }
        }

        // 生活号循环处理
        $detail_m = M('AlipayLifeMenuDetail');
        $detail = $detail_m->where(['alipay_life_menu_id'=>$menu['id'], 'status'=>0])->select();
        if(!empty($detail))
        {
            foreach($detail as $d)
            {
                // 生活号
                $life = M('AlipayLife')->find($d['alipay_life_id']);

                // 请求接口处理
                $obj = new \Library\AlipayLife(['life_data'=>$life]);
                $ret = $obj->MenuRelease($menu);

                // 返回状态更新
                $status = (isset($ret['status']) && $ret['status'] == 0) ? 2 : 4;
                $detail_m->where(['id'=>$d['id']])->save(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$ret['msg']]);
            }
        } else {
            $status_all = $detail_m->where(['alipay_life_menu_id'=>$menu['id']])->group('status')->getField('status', true);
            if(count($status_all) <= 1)
            {
                $status = in_array(2, $status_all) ? 2 : 4;
            } else {
                $status = 3;
            }
            $m->where(['id'=>$menu['id']])->save(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
            echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
            echo '[menu:'.$params['menu_id']."]\n\n";
        }

        // 继续运行脚本
        self::SyncJobSend($menu['id'], 'menu_id', 'MenuRelease');

        // end
        die('[end_time:'.date('Y-m-d H:i:s')."][msg:处理结束]\n\n");
    }

    /**
     * 菜单详情列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MenuDetailList($params = [])
    {
        // 基础参数
        if(empty($params['message_id']))
        {
            return [];
        }

        // 条件
        $where = ['alipay_life_message_id' => intval($params['message_id'])];

        // 列表
        $data = M('AlipayLifeMenuDetail')->where($where)->order('id desc')->select();
        if(!empty($data))
        {
            $common_release_status_list = L('common_release_status_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_release_status_list[$v['status']]['name'];

                // 生活号
                $v['alipay_life_name'] = empty($v['alipay_life_id']) ? '' : M('AlipayLife')->where(['id'=>$v['alipay_life_id']])->getField('name');

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                $v['send_time'] = empty($v['send_time']) ? '' : date('Y-m-d H:i:s', $v['send_time']);
            }
        }
        return $data;
    }
}
?>