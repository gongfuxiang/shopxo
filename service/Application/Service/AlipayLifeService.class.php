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
            'url'                           => isset($params['url']) ? $params['url'] : '',
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
                        $alipay_life_id = isset($alipay_life_ids[0]) ? $alipay_life_ids[0] : '';
                    } else {
                        $alipay_life_id = M('AlipayLifeUser')->where(['id'=>$alipay_life_message['alipay_life_user_id']])->getField('alipay_life_id');
                    }
                } else {
                    return DataReturn('消息主数据有误', -5);
                }
                if(!empty($alipay_life_id))
                {
                    $obj = new \Library\AlipayLife(['life_data'=>M('AlipayLife')->find($alipay_life_id)]);
                    $res = $obj->UploadImage(['file'=>ROOT_PATH.substr($data['image_url'], 1)]);
                    if($res['status'] != 0)
                    {
                        return DataReturn($res['msg'], -10);
                    }
                    $data['out_image_url'] = $res['data'];
                } else {
                    return DataReturn('消息生活号id有误', -10);
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

        // 获取数据
        $m = M('AlipayLifeMessage');
        $data = $m->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('消息记录不存在', -1);
        }
        $common_send_status_list = L('common_send_status_list');
        if(!in_array($data['status'], [0,4]))
        {
            return DataReturn('状态不可操作['.$common_send_status_list[$data['status']]['name'].']', -2);
        }

        // 获取数据内容
        $content_count = (int) M('AlipayLifeMessageContent')->where(['alipay_life_message_id'=>$data['id']])->count();
        if(empty($content_count))
        {
            return DataReturn('消息内容不能为空', -1);
        }

        switch($data['send_type'])
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
        $detail = [];
        if($data['send_type'] == 1)
        {
            $alipay_life_all = json_decode($data['alipay_life_ids'], true);
            foreach($alipay_life_all as $alipay_life_id)
            {
                $detail[] = [
                    'alipay_life_id'        => $alipay_life_id,
                    'alipay_life_message_id'=> $data['id'],
                ];
            }
        } else {
            $alipay_openid = M('User')->where(['id'=>$data['user_id']])->getField('alipay_openid');
            if(!empty($alipay_openid))
            {
                $detail[] = [
                    'user_id'               => $data['user_id'],
                    'alipay_life_id'        => M('AlipayLifeUser')->where(['id'=>$data['alipay_life_user_id']])->getField('alipay_life_id'),
                    'alipay_life_user_id'   => $data['alipay_life_user_id'],
                    'alipay_openid'         => $alipay_openid,
                    'alipay_life_message_id'=> $data['id'],
                ];
            }
            
        }

        // 入库详情表
        $m->startTrans();
        if(M('AlipayLifeMessageDetail')->addAll($detail) !== false)
        {
            if($m->where(['id'=>$data['id']])->save(['status'=>1, 'startup_time'=>time(), 'upd_time'=>time()]) !== false)
            {
                $m->commit();
                self::SyncJobSend($data['id'], 'message_id', 'MessageSend');
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
        echo '[data:'.$params['message_id']."]\n";

        // 开始处理
        $m = M('AlipayLifeMessage');
        $data = $m->find($params['message_id']);
        if(empty($data))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$params['message_id']}数据不存在]\n\n");
        }
        if(!in_array($data['status'], [1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['status']}状态不可操作]\n\n");
        }

        // 发送消息类型
        if($data['send_type'] == 1)
        {
            $alipay_life_all = json_decode($data['alipay_life_ids'], true);
        } else {
            $alipay_life_all = [M('AlipayLifeUser')->where(['id'=>$data['alipay_life_user_id']])->getField('alipay_life_id')];
        }

        // 消息内容
        $data['content'] = M('AlipayLifeMessageContent')->field('id,title,content,out_image_url,url,action_name')->where(['alipay_life_message_id'=>$data['id']])->select();
        if(empty($data['content']))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['id']}消息内容为空]\n\n");
        }

        // 生活号循环处理
        $detail_m = M('AlipayLifeMessageDetail');
        foreach($alipay_life_all as $alipay_life_id)
        {
            // 生活号
            $life = M('AlipayLife')->find($alipay_life_id);

            // 获取消息详情
            $detail = $detail_m->where(['alipay_life_message_id'=>$data['id'], 'status'=>0])->limit(30)->select();

            if(!empty($detail))
            {
                $obj = new \Library\AlipayLife(['life_data'=>$life]);
                foreach($detail as $v)
                {
                    // 群发
                    if($data['send_type'] == 1)
                    {
                        // 请求接口处理
                        $ret = $obj->GroupSend($data);
                    } else {
                        // 请求接口处理
                        $data['alipay_openid'] = $v['alipay_openid'];
                        $ret = $obj->CustomSend($data);
                    }

                    // 返回状态更新
                    $status = (isset($ret['status']) && $ret['status'] == 0) ? 2 : 4;
                    $detail_m->where(['id'=>$v['id']])->save(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$ret['msg']]);
                }
                echo '[count:'.count($detail).']';
            } else {
                $status_all = $detail_m->where(['alipay_life_message_id'=>$data['id']])->group('status')->getField('status', true);
                if(count($status_all) <= 1)
                {
                    $status = in_array(2, $status_all) ? 2 : 4;
                } else {
                    $status = 3;
                }
                $m->where(['id'=>$data['id']])->save(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
                echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
                echo '[data:'.$params['message_id']."]\n\n";
            }
        }

        // 继续运行脚本
        self::SyncJobSend($data['id'], 'message_id', 'MessageSend');

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
        if(isset($params['is_all']) && $params['is_all'] == 1)
        {
            $where = [];
        } else {
            $where = ['l.is_shelves'=>1];
        }

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

        // 获取数据
        $m = M('AlipayLifeMenu');
        $data = $m->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('菜单记录不存在', -1);
        }
        $common_release_status_list = L('common_release_status_list');
        if(!in_array($data['status'], [0,4]))
        {
            return DataReturn('状态不可操作['.$common_release_status_list[$data['status']]['name'].']', -2);
        }

        // 获取数据内容
        $content_list = M('AlipayLifeMenuContent')->field('id,name')->where(['alipay_life_menu_id'=>$data['id'], 'pid'=>0])->select();
        if(empty($content_list))
        {
            return DataReturn('菜单数据不能为空', -1);
        }

        $content_count = count($content_list);
        switch($data['type'])
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
        $detail = [];
        $alipay_life_all = json_decode($data['alipay_life_ids'], true);
        foreach($alipay_life_all as $alipay_life_id)
        {
            $detail[] = [
                'alipay_life_id'        => $alipay_life_id,
                'alipay_life_menu_id'   => $data['id'],
                'add_time'              => time(),
            ];
        }

        // 入库详情表
        $m->startTrans();
        if(M('AlipayLifeMenuDetail')->addAll($detail) !== false)
        {
            $upd_data = [
                'status'            => 1,
                'startup_time'      => time(),
                'upd_time'          => time()
            ];
            $status = M('AlipayLifeMenu')->where(['id'=>$data['id']])->save($upd_data);
            if($status !== false)
            {
                $m->commit();
                self::SyncJobSend($data['id'], 'menu_id', 'MenuRelease');
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
        echo '[data:'.$params['menu_id']."]\n";

        // 开始处理
        $m = M('AlipayLifeMenu');
        $data = $m->find($params['menu_id']);
        if(empty($data))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$params['menu_id']}数据不存在]\n\n");
        }
        if(!in_array($data['status'], [1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['status']}状态不可操作]\n\n");
        }

        // 生活号
        $alipay_life_all = json_decode($data['alipay_life_ids'], true);

        // 消息内容
        $field = 'id,pid,name,action_type,action_value,out_icon';
        $data['content'] = M('AlipayLifeMenuContent')->field($field)->where(['alipay_life_menu_id'=>$data['id'], 'pid'=>0])->order('sort asc')->select();
        if(empty($data['content']))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['id']}菜单内容为空]\n\n");
        }

        // 是否文本类型
        $action_type_list = L('common_alipay_life_menu_action_type_list');
        foreach($data['content'] as &$v)
        {
            // 外部事件值
            $v['action_type'] = $action_type_list[$v['action_type']]['out_value'];

            // 是否需要读取子集
            if($data['type'] == 0)
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
        $detail = $detail_m->where(['alipay_life_menu_id'=>$data['id'], 'status'=>0])->limit(10)->select();
        if(!empty($detail))
        {
            foreach($detail as $d)
            {
                // 生活号
                $life = M('AlipayLife')->find($d['alipay_life_id']);

                // 请求接口处理
                $obj = new \Library\AlipayLife(['life_data'=>$life]);
                $ret = $obj->MenuRelease($data);

                // 返回状态更新
                $status = (isset($ret['status']) && $ret['status'] == 0) ? 2 : 4;
                $detail_m->where(['id'=>$d['id']])->save(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$ret['msg']]);
            }
        } else {
            $status_all = $detail_m->where(['alipay_life_menu_id'=>$data['id']])->group('status')->getField('status', true);
            if(count($status_all) <= 1)
            {
                $status = in_array(2, $status_all) ? 2 : 4;
            } else {
                $status = 3;
            }
            $m->where(['id'=>$data['id']])->save(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
            echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
            echo '[data:'.$params['menu_id']."]\n\n";
        }

        // 继续运行脚本
        self::SyncJobSend($data['id'], 'menu_id', 'MenuRelease');

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

    /**
     * 批量上下架保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LifeStatusSave($params = [])
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
                'checked_type'      => 'in',
                'key_name'          => 'is_shelves',
                'checked_data'      => [0,1],
                'error_msg'         => '上下架状态选择有误',
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
            'is_shelves'        => intval($params['is_shelves']),
            'alipay_life_ids'   => empty($params['alipay_life_ids']) ? 0 : json_encode(explode(',', $params['alipay_life_ids'])),
        ];

        // 开始处理业务
        $status = false;
        $m = M('AlipayLifeStatus');
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
     * 批量上下架提交
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LifeStatusSubmit($params = [])
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

        // 获取数据
        $m = M('AlipayLifeStatus');
        $data = $m->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('数据不存在', -1);
        }
        $common_handle_status_list = L('common_handle_status_list');
        if(!in_array($data['status'], [0,4]))
        {
            return DataReturn('状态不可操作['.$common_handle_status_list[$data['status']]['name'].']', -2);
        }

        // 生活号
        $detail = [];
        $alipay_life_all = json_decode($data['alipay_life_ids'], true);
        foreach($alipay_life_all as $alipay_life_id)
        {
            $detail[] = [
                'alipay_life_id'        => $alipay_life_id,
                'alipay_life_status_id' => $data['id'],
                'add_time'              => time(),
            ];
        }

        // 入库详情表
        $m->startTrans();
        if(M('AlipayLifeStatusDetail')->addAll($detail) !== false)
        {
            $upd_data = [
                'status'            => 1,
                'startup_time'      => time(),
                'upd_time'          => time()
            ];
            $status = $m->where(['id'=>$data['id']])->save($upd_data);
            if($status !== false)
            {
                $m->commit();
                self::SyncJobSend($data['id'], 'status_id', 'StatusHandle');
                return DataReturn(L('common_submit_success'), 0);
            }
        }
        $m->rollback();
        return DataReturn(L('common_submit_error'), -100);
    }

    /**
     * 批量上下架处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function StatusHandle($params = [])
    {
        if(empty($params['status_id']))
        {
            die('[params_time:'.date('Y-m-d H:i:s')."][msg:id有误]\n\n");
        }

        // 启动开始
        echo '[start_time:'.date('Y-m-d H:i:s')."]\n";
        echo '[menu:'.$params['status_id']."]\n";

        // 开始处理
        $m = M('AlipayLifeStatus');
        $data = $m->find($params['status_id']);
        if(empty($data))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$params['status_id']}数据不存在]\n\n");
        }
        if(!in_array($data['status'], [1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['status']}状态不可操作]\n\n");
        }

        // 生活号循环处理
        $detail_m = M('AlipayLifeStatusDetail');
        $detail = $detail_m->where(['alipay_life_status_id'=>$data['id'], 'status'=>0])->limit(10)->select();
        $life_m = M('AlipayLife');
        if(!empty($detail))
        {
            foreach($detail as $v)
            {
                // 数据更新
                $status = 4;
                $msg = '';
                $life_m->startTrans();
                if($life_m->where(array('id'=>$v['alipay_life_id']))->save(array('is_shelves'=>$data['is_shelves'], 'upd_time'=>time())))
                {
                    $obj = new \Library\AlipayLife(['life_data'=>$life_m->find($v['alipay_life_id'])]);
                    if($data['is_shelves'] == 1)
                    {
                        $ret = $obj->LifeAboard();
                    } else {
                        $ret = $obj->LifeDebark();
                    }
                    if($ret['status'] == 0)
                    {
                        $life_m->commit();
                        $status = 2;
                    } else {
                        $life_m->rollback();
                    }
                    $msg = $ret['msg'];
                } else {
                    $life_m->rollback();
                    $msg = '主数据更新失败';
                }
                $detail_m->where(['id'=>$v['id']])->save(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$msg]);
            }
        } else {
            $status_all = $detail_m->where(['alipay_life_status_id'=>$data['id']])->group('status')->getField('status', true);
            if(count($status_all) <= 1)
            {
                $status = in_array(2, $status_all) ? 2 : 4;
            } else {
                $status = 3;
            }
            $m->where(['id'=>$data['id']])->save(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
            echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
            echo '[data:'.$params['status_id']."]\n\n";
        }

        // 继续运行脚本
        self::SyncJobSend($data['id'], 'status_id', 'StatusHandle');

        // end
        die('[end_time:'.date('Y-m-d H:i:s')."][msg:处理结束]\n\n");
    }

    /**
     * 批量上下架详情列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function StatusDetailList($params = [])
    {
        // 基础参数
        if(empty($params['status_id']))
        {
            return [];
        }

        // 条件
        $where = ['alipay_life_status_id' => intval($params['status_id'])];

        // 列表
        $data = M('AlipayLifeStatusDetail')->where($where)->order('id desc')->select();
        if(!empty($data))
        {
            $common_handle_status_list = L('common_handle_status_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_handle_status_list[$v['status']]['name'];

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