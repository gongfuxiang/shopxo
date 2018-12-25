<?php
namespace app\service;

use think\Db;
use app\service\ResourcesService;

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
        $m = Db::name('AlipayLifeMessage');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->insertGetId($data))
            {
                $status = true;
                $msg = '新增成功';
            } else {
                $msg = '新增失败';
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->update($data))
            {
                $status = true;
                $msg = '编辑成功';
            } else {
                $msg = '编辑失败或数据未改变';
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
                $alipay_life_message = Db::name('AlipayLifeMessage')->find($data['alipay_life_message_id']);
                if(!empty($alipay_life_message))
                {
                    if($alipay_life_message['send_type'] == 1 && !empty($alipay_life_message['alipay_life_ids']))
                    {
                        $alipay_life_ids = json_decode($alipay_life_message['alipay_life_ids'], true);
                        $alipay_life_id = isset($alipay_life_ids[0]) ? $alipay_life_ids[0] : '';
                    } else {
                        $alipay_life_id = Db::name('AlipayLifeUser')->where(['id'=>$alipay_life_message['alipay_life_user_id']])->value('alipay_life_id');
                    }
                } else {
                    return DataReturn('消息主数据有误', -5);
                }
                if(!empty($alipay_life_id))
                {
                    $obj = new \Library\AlipayLife(['life_data'=>Db::name('AlipayLife')->find($alipay_life_id)]);
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
        $m = Db::name('AlipayLifeMessageContent');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->insertGetId($data))
            {
                $status = true;
                $msg = '新增成功';
            } else {
                $msg = '新增失败';
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->update($data))
            {
                $status = true;
                $msg = '编辑成功';
            } else {
                $msg = '编辑失败或数据未改变';
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
        $ret = ParamsChecked($params, $p);
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
           $ret = ParamsChecked($params, $p);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 主数据
        $message = Db::name('AlipayLifeMessage')->find($params['message_id']);
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
           $ret = ParamsChecked($params, $p);
            if($ret !== true)
            {
                return DataReturn($ret, -1);
            } 
        }
        
        return DataReturn('验证成功', 0);
    }

    /**
     * 根据appid获取一条生活号
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
            return Db::name('AlipayLife')->where(['appid'=>$params['appid']])->find();
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
            $user = Db::name('User')->where(['alipay_openid'=>$params['alipay_openid']])->find();
            if(!empty($life) && !empty($user))
            {
                return Db::name('AlipayLifeUser')->where(['user_id'=>$user['id'], 'alipay_life_id'=>$life['id']])->delete() !== false;
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
            $user = Db::name('User')->where(['alipay_openid'=>$params['alipay_openid']])->find();
            if(empty($user))
            {
                $data = [
                    'alipay_openid'     => $params['alipay_openid'],
                    'nickname'          => isset($params['user_name']) ? $params['user_name'] : '',
                    'add_time'          => time(),
                ];
                $user_id = Db::name('User')->insertGetId($data);
            } else {
                $user_id = $user['id'];
            }
            if(!empty($user_id))
            {
                $life_user_data = [
                    'user_id'       => $user_id,
                    'alipay_life_id'=> $life['id'],
                ];
                $life_user = Db::name('AlipayLifeUser')->where($life_user_data)->find();
                if(empty($life_user))
                {
                    $life_user_data['add_time'] = time();
                    return Db::name('AlipayLifeUser')->insertGetId($life_user_data) > 0;
                } else {
                    return Db::name('AlipayLifeUser')->where($life_user_data)->update(['enter_count'=>$life_user['enter_count']+1, 'upd_time'=>time()]) !== false;
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $m = Db::name('AlipayLifeMessage');
        $data = $m->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('消息记录不存在', -1);
        }
        $common_send_status_list = lang('common_send_status_list');
        if(!in_array($data['status'], [0,4]))
        {
            return DataReturn('状态不可操作['.$common_send_status_list[$data['status']]['name'].']', -2);
        }

        // 获取数据内容
        $content_count = (int) Db::name('AlipayLifeMessageContent')->where(['alipay_life_message_id'=>$data['id']])->count();
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
            $alipay_openid = Db::name('User')->where(['id'=>$data['user_id']])->value('alipay_openid');
            if(!empty($alipay_openid))
            {
                $detail[] = [
                    'user_id'               => $data['user_id'],
                    'alipay_life_id'        => Db::name('AlipayLifeUser')->where(['id'=>$data['alipay_life_user_id']])->value('alipay_life_id'),
                    'alipay_life_user_id'   => $data['alipay_life_user_id'],
                    'alipay_openid'         => $alipay_openid,
                    'alipay_life_message_id'=> $data['id'],
                ];
            }
            
        }

        // 入库详情表
        $m->startTrans();
        if(Db::name('AlipayLifeMessageDetail')->addAll($detail) !== false)
        {
            if($m->where(['id'=>$data['id']])->update(['status'=>1, 'startup_time'=>time(), 'upd_time'=>time()]) !== false)
            {
                $m->commit();
                self::SyncJobSend($data['id'], 'message_id', 'MessageSend');
                return DataReturn('提交成功', 0);
            }
        }
        $m->rollback();
        return DataReturn('提交失败', -100);
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
        $m = Db::name('AlipayLifeMessage');
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
            $alipay_life_all = [Db::name('AlipayLifeUser')->where(['id'=>$data['alipay_life_user_id']])->value('alipay_life_id')];
        }

        // 消息内容
        $data['content'] = Db::name('AlipayLifeMessageContent')->field('id,title,content,out_image_url,url,action_name')->where(['alipay_life_message_id'=>$data['id']])->select();
        if(empty($data['content']))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['id']}消息内容为空]\n\n");
        }

        // 获取消息详情
        $detail_m = Db::name('AlipayLifeMessageDetail');
        $detail = $detail_m->where(['alipay_life_message_id'=>$data['id'], 'status'=>0])->limit(30)->select();
        if(!empty($detail))
        {
            foreach($detail as $v)
            {
                // 生活号
                $life = Db::name('AlipayLife')->find($v['alipay_life_id']);
                $obj = new \Library\AlipayLife(['life_data'=>$life]);

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
                $detail_m->where(['id'=>$v['id']])->update(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$ret['msg']]);
            }
            echo '[count:'.count($detail).']';
        } else {
            $status_all = $detail_m->where(['alipay_life_message_id'=>$data['id']])->group('status')->column('status');
            if(count($status_all) <= 1)
            {
                $status = in_array(2, $status_all) ? 2 : 4;
            } else {
                $status = 3;
            }
            $m->where(['id'=>$data['id']])->update(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
            echo '[success_time:'.date('Y-m-d H:i:s')."]\n";
            echo '[data:'.$params['message_id']."]\n\n";
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
        $data = Db::name('AlipayLife')->alias('l')->join(' INNER JOIN __ALIPAY_LIFE_CATEGORY_JOIN__ AS lc ON l.id=lc.alipay_life_id')->field('l.id,l.name')->group('l.id')->where($where)->select();

        if(empty($data))
        {
            return DataReturn('没有相关数据', -100);
        } else {
            return DataReturn('操作成功', 0, $data);
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
        $data = Db::name('AlipayLifeMessageDetail')->where($where)->order('id desc')->select();
        if(!empty($data))
        {
            $common_send_status_list = lang('common_send_status_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_send_status_list[$v['status']]['name'];

                // 生活号
                $v['alipay_life_name'] = empty($v['alipay_life_id']) ? '' : Db::name('AlipayLife')->where(['id'=>$v['alipay_life_id']])->value('name');

                // 用户
                $v['alipay_openid'] = empty($v['user_id']) ? '' :  Db::name('User')->where(['id'=>$v['user_id']])->value('alipay_openid');

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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        Db::startTrans();
        if(Db::name('AlipayLife')->where(['id'=>$params['alipay_life_id']])->update(['is_shelves'=>$params['status'], 'upd_time'=>time()]))
        {
            $obj = new \base\AlipayLife(['life_data'=>Db::name('AlipayLife')->find($params['alipay_life_id'])]);
            if($params['status'] == 1)
            {
                $ret = $obj->LifeAboard();
            } else {
                $ret = $obj->LifeDebark();
            }
            if($ret['status'] == 0)
            {
                Db::commit();
                return DataReturn('编辑成功', 0);
            } else {
                Db::rollback();
                return DataReturn($ret['msg'], -100);
            }
        }

        Db::rollback();
        return DataReturn('编辑失败或数据未改变', -100);
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
        $ret = ParamsChecked($params, $p);
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
        $m = Db::name('AlipayLifeMenu');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->insertGetId($data))
            {
                $status = true;
                $msg = '新增成功';
            } else {
                $msg = '新增失败';
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->update($data))
            {
                $status = true;
                $msg = '编辑成功';
            } else {
                $msg = '编辑失败或数据未改变';
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
                $alipay_life_menu = Db::name('AlipayLifeMenu')->find($data['alipay_life_menu_id']);
                if(!empty($alipay_life_menu))
                {
                    if(!empty($alipay_life_menu['alipay_life_ids']))
                    {
                        $alipay_life_ids = json_decode($alipay_life_menu['alipay_life_ids'], true);
                        $alipay_life_id = isset($alipay_life_ids[0]) ? $alipay_life_ids[0] : '';

                        $obj = new \Library\AlipayLife(['life_data'=>Db::name('AlipayLife')->find($alipay_life_id)]);
                        $res = $obj->UploadImage(['file'=>ROOT_PATH.substr($data['icon'], 1)]);
                        if($res['status'] != 0)
                        {
                            return DataReturn($res['msg'], -10);
                        }
                        $data['out_icon'] = $res['data'];
                    } else {
                        return DataReturn('菜单生活号id有误', -10);
                    }
                } else {
                    return DataReturn('菜单主数据有误', -5);
                }
            }
        }

        // 开始处理业务
        $status = false;
        $m = Db::name('AlipayLifeMenuContent');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->insertGetId($data))
            {
                $status = true;
                $msg = '新增成功';
            } else {
                $msg = '新增失败';
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->update($data))
            {
                $status = true;
                $msg = '编辑成功';
            } else {
                $msg = '编辑失败或数据未改变';
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 主数据
        $menu = Db::name('AlipayLifeMenu')->find($params['menu_id']);
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
           $ret = ParamsChecked($params, $p);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $m = Db::name('AlipayLifeMenu');
        $data = $m->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('菜单记录不存在', -1);
        }
        $common_release_status_list = lang('common_release_status_list');
        if(!in_array($data['status'], [0,4]))
        {
            return DataReturn('状态不可操作['.$common_release_status_list[$data['status']]['name'].']', -2);
        }

        // 获取数据内容
        $content_list = Db::name('AlipayLifeMenuContent')->field('id,name')->where(['alipay_life_menu_id'=>$data['id'], 'pid'=>0])->select();
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
                    $temp_count = Db::name('AlipayLifeMenuContent')->where(['pid'=>$v['id']])->count();
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
        if(Db::name('AlipayLifeMenuDetail')->addAll($detail) !== false)
        {
            $upd_data = [
                'status'            => 1,
                'startup_time'      => time(),
                'upd_time'          => time()
            ];
            $status = Db::name('AlipayLifeMenu')->where(['id'=>$data['id']])->update($upd_data);
            if($status !== false)
            {
                $m->commit();
                self::SyncJobSend($data['id'], 'menu_id', 'MenuRelease');
                return DataReturn('提交成功', 0);
            }
        }
        $m->rollback();
        return DataReturn('提交失败', -100);
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
        $m = Db::name('AlipayLifeMenu');
        $data = $m->find($params['menu_id']);
        if(empty($data))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$params['menu_id']}数据不存在]\n\n");
        }
        if(!in_array($data['status'], [1]))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['status']}状态不可操作]\n\n");
        }

        // 消息内容
        $field = 'id,pid,name,action_type,action_value,out_icon';
        $data['content'] = Db::name('AlipayLifeMenuContent')->field($field)->where(['alipay_life_menu_id'=>$data['id'], 'pid'=>0])->order('sort asc')->select();
        if(empty($data['content']))
        {
            die('[time:'.date('Y-m-d H:i:s')."][msg:{$data['id']}菜单内容为空]\n\n");
        }

        // 是否文本类型
        $action_type_list = lang('common_alipay_life_menu_action_type_list');
        foreach($data['content'] as &$v)
        {
            // 外部事件值
            $v['action_type'] = $action_type_list[$v['action_type']]['out_value'];

            // 是否需要读取子集
            if($data['type'] == 0)
            {
                $items = Db::name('AlipayLifeMenuContent')->field($field)->where(['pid'=>$v['id']])->order('sort asc')->select();
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
        $detail_m = Db::name('AlipayLifeMenuDetail');
        $detail = $detail_m->where(['alipay_life_menu_id'=>$data['id'], 'status'=>0])->limit(10)->select();
        if(!empty($detail))
        {
            foreach($detail as $d)
            {
                // 生活号
                $life = Db::name('AlipayLife')->find($d['alipay_life_id']);

                // 请求接口处理
                $obj = new \Library\AlipayLife(['life_data'=>$life]);
                $ret = $obj->MenuRelease($data);

                // 返回状态更新
                $status = (isset($ret['status']) && $ret['status'] == 0) ? 2 : 4;
                $detail_m->where(['id'=>$d['id']])->update(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$ret['msg']]);
            }
        } else {
            $status_all = $detail_m->where(['alipay_life_menu_id'=>$data['id']])->group('status')->column('status');
            if(count($status_all) <= 1)
            {
                $status = in_array(2, $status_all) ? 2 : 4;
            } else {
                $status = 3;
            }
            $m->where(['id'=>$data['id']])->update(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
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
        if(empty($params['menu_id']))
        {
            return [];
        }

        // 条件
        $where = ['alipay_life_menu_id' => intval($params['menu_id'])];

        // 列表
        $data = Db::name('AlipayLifeMenuDetail')->where($where)->order('id desc')->select();
        if(!empty($data))
        {
            $common_release_status_list = lang('common_release_status_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_release_status_list[$v['status']]['name'];

                // 生活号
                $v['alipay_life_name'] = empty($v['alipay_life_id']) ? '' : Db::name('AlipayLife')->where(['id'=>$v['alipay_life_id']])->value('name');

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
        $ret = ParamsChecked($params, $p);
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
        $m = Db::name('AlipayLifeStatus');
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if($m->insertGetId($data))
            {
                $status = true;
                $msg = '新增成功';
            } else {
                $msg = '新增失败';
            }
        } else {
            $data['upd_time'] = time();
            if($m->where(array('id'=>intval(I('id'))))->update($data))
            {
                $status = true;
                $msg = '编辑成功';
            } else {
                $msg = '编辑失败或数据未改变';
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $m = Db::name('AlipayLifeStatus');
        $data = $m->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('数据不存在', -1);
        }
        $common_handle_status_list = lang('common_handle_status_list');
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
        if(Db::name('AlipayLifeStatusDetail')->addAll($detail) !== false)
        {
            $upd_data = [
                'status'            => 1,
                'startup_time'      => time(),
                'upd_time'          => time()
            ];
            $status = $m->where(['id'=>$data['id']])->update($upd_data);
            if($status !== false)
            {
                $m->commit();
                self::SyncJobSend($data['id'], 'status_id', 'StatusHandle');
                return DataReturn('提交成功', 0);
            }
        }
        $m->rollback();
        return DataReturn('提交失败', -100);
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
        $m = Db::name('AlipayLifeStatus');
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
        $detail_m = Db::name('AlipayLifeStatusDetail');
        $detail = $detail_m->where(['alipay_life_status_id'=>$data['id'], 'status'=>0])->limit(10)->select();
        $life_m = Db::name('AlipayLife');
        if(!empty($detail))
        {
            foreach($detail as $v)
            {
                // 数据更新
                $status = 4;
                $msg = '';
                $life_m->startTrans();
                if($life_m->where(array('id'=>$v['alipay_life_id']))->update(array('is_shelves'=>$data['is_shelves'], 'upd_time'=>time())))
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
                $detail_m->where(['id'=>$v['id']])->update(['status'=>$status, 'send_time'=>time(), 'upd_time'=>time(), 'return_msg'=>$msg]);
            }
        } else {
            $status_all = $detail_m->where(['alipay_life_status_id'=>$data['id']])->group('status')->column('status');
            if(count($status_all) <= 1)
            {
                $status = in_array(2, $status_all) ? 2 : 4;
            } else {
                $status = 3;
            }
            $m->where(['id'=>$data['id']])->update(['success_time'=>time(), 'status'=>$status, 'upd_time'=>time()]);
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
        $data = Db::name('AlipayLifeStatusDetail')->where($where)->order('id desc')->select();
        if(!empty($data))
        {
            $common_handle_status_list = lang('common_handle_status_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_handle_status_list[$v['status']]['name'];

                // 生活号
                $v['alipay_life_name'] = empty($v['alipay_life_id']) ? '' : Db::name('AlipayLife')->where(['id'=>$v['alipay_life_id']])->value('name');

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                $v['send_time'] = empty($v['send_time']) ? '' : date('Y-m-d H:i:s', $v['send_time']);
            }
        }
        return $data;
    }

    /**
     * 生活号列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AlipayLifeList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? 'a.*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'a.id desc' : trim($params['order_by']);

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取品牌列表
        $data = Db::name('AlipayLife')->alias('a')->field($field)->join(['__ALIPAY_LIFE_CATEGORY_JOIN__'=>'cj'], 'a.id=cj.alipay_life_id')->where($where)->limit($m, $n)->order($order_by)->group('a.id')->select();
        if(!empty($data))
        {
            $common_is_enable_tips = lang('common_is_enable_tips');
            $image_host = config('IMAGE_HOST');
            foreach($data as &$v)
            {
                // 是否启用
                if(isset($v['is_enable']))
                {
                    $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];
                }

                // 分类名称
                $category_ids = Db::name('AlipayLifeCategoryJoin')->where(['alipay_life_id'=>$v['id']])->column('alipay_life_category_id');
                $v['alipay_life_category_text'] = Db::name('AlipayLifeCategory')->where(['id'=>$category_ids])->column('name');

                // logo
                if(isset($v['logo']))
                {
                    $v['logo_old'] = $v['logo'];
                    $v['logo'] =  empty($v['logo']) ? '' : $image_host.$v['logo'];
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                    $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                    $v['upd_time_date'] = date('Y-m-d', $v['upd_time']);
                }
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 生活号总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function AlipayLifeTotal($where)
    {
        return (int) Db::name('AlipayLife')->alias('a')->join(['__ALIPAY_LIFE_CATEGORY_JOIN__'=>'cj'], 'a.id=cj.alipay_life_id')->where($where)->count();
    }

    /**
     * 生活号列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AlipayLifeListWhere($params = [])
    {
        $where = [];

        if(!empty($params['keywords']))
        {
            $where[] = ['a.name', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_shelves']) && $params['is_shelves'] > -1)
            {
                $where[] = ['a.is_shelves', '=', intval($params['is_shelves'])];
            }
            if(isset($params['alipay_life_category_id']) && $params['alipay_life_category_id'] > -1)
            {
                $where[] = ['cj.alipay_life_category_id', '=', intval($params['alipay_life_category_id'])];
            }

            if(!empty($params['time_start']))
            {
                $where[] = ['a.add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['a.add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
    }

    /**
     * 生活号保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AlipayLifeSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,30',
                'error_msg'         => '名称格式 2~30 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'alipay_life_category_id',
                'error_msg'         => '请选择生活号分类',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'appid',
                'checked_data'      => '1,60',
                'error_msg'         => 'appid格式 1~60 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'out_rsa_public',
                'checked_data'      => '1,2000',
                'error_msg'         => '应用公钥格式 1~2000 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'rsa_private',
                'checked_data'      => '1,2000',
                'error_msg'         => '应用私钥格式 1~2000 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'out_rsa_public',
                'checked_data'      => '1,2000',
                'error_msg'         => '支付宝公钥格式 1~2000 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 附件
        $data_fields = ['logo'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'              => $params['name'],
            'appid'             => $params['appid'],
            'logo'              => $attachment['data']['logo'],
            'rsa_public'        => empty($params['rsa_public']) ? '' : $params['rsa_public'],
            'rsa_private'       => empty($params['rsa_private']) ? '' : $params['rsa_private'],
            'out_rsa_public'    => empty($params['out_rsa_public']) ? '' : $params['out_rsa_public'],
        ];

        // 开启事务
        Db::startTrans();
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $alipay_life_id = Db::name('AlipayLife')->insertGetId($data);
            if($alipay_life_id > 0)
            {
                $ret = self::AlipayLifeCategoryInsert($params, $alipay_life_id);
                if($ret['code'] != 0)
                {
                    Db::rollback();
                    return $ret;
                }

                Db::commit();
                return DataReturn('添加成功', 0);
            }

            Db::rollback();
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('AlipayLife')->where(['id'=>intval($params['id'])])->update($data))
            {
                $ret = self::AlipayLifeCategoryInsert($params, intval($params['id']));
                if($ret['code'] != 0)
                {
                    Db::rollback();
                    return $ret;
                }

                Db::commit();
                return DataReturn('编辑成功', 0);
            }

            Db::rollback();
            return DataReturn('编辑失败', -100); 
        }
    }

    /**
     * 生活号分类添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param   [array]           $params         [输入参数]
     * @param   [array]           $alipay_life_id [生活号id]
     */
    private static function AlipayLifeCategoryInsert($params = [], $alipay_life_id)
    {
        // 删除关联分类
        Db::name('AlipayLifeCategoryJoin')->where(['alipay_life_id'=>intval($params['id'])])->delete();

        // 开始添加
        if(!empty($params['alipay_life_category_id']))
        {
            $data = [];
            foreach(explode(',', $params['alipay_life_category_id']) as $v)
            {
                $data[] = [
                    'alipay_life_id'            => $alipay_life_id,
                    'alipay_life_category_id'   => $v,
                    'add_time'                  => time(),
                ];
            }
            if(Db::name('AlipayLifeCategoryJoin')->insertAll($data) < count($data))
            {
                return DataReturn('生活号分类添加失败', -10);
            }
        }
        return DataReturn('生活号分类添加成功', 0);
    }

    /**
     * 生活号删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AlipayLifeDelete($params = [])
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
        Db::startTrans();
        if(Db::name('AlipayLife')->where(['id'=>intval($params['id'])])->delete() && Db::name('AlipayLifeCategoryJoin')->where(['alipay_life_id'=>intval($params['id'])])->delete() !== false)
        {
            Db::commit();
            return DataReturn('删除成功');
        }

        Db::rollback();
        return DataReturn('删除失败或资源不存在', -100);
    }

    /**
     * 生活号分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AlipayLifeCategoryList($params = [])
    {
        $where = empty($params['where']) ? ['is_enable'=>1] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'sort asc' : trim($params['order_by']);

        $data = Db::name('AlipayLifeCategory')->where($where)->field($field)->order($order_by)->select();
        
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 生活号分类id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AlipayLifeCategoryIds($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['alipay_life_category_id']) ? 'alipay_life_category_id' : $params['field'];
        $data = Db::name('AlipayLifeCategoryJoin')->where($where)->column($field);
        return DataReturn('处理成功', 0, $data);
    }
}
?>