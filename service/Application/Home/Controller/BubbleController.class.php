<?php

namespace Home\Controller;

/**
 * 冒泡
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class BubbleController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();
	}

	/**
	 * [Index 冒泡]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function Index()
	{
		// 条件
		$where = array();
		if(I('type') == 'own')
		{
			$where['u.id'] = $this->user['id'];
		}

		// 模型
		$m = M('Mood');

		// 分页
		$number = 10;
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->alias('m')->join('__USER__ AS u ON m.user_id=u.id')->where($where)->count(),
				'where'		=>	$_GET,
				'url'		=>	U('Home/Bubble/Index'),
			);
		$page = new \My\Page($page_param);

		// 查询字段
		$field = array('m.id', 'm.user_id', 'm.content', 'm.visible', 'm.add_time', 'u.nickname');

		// 数据处理
		$data = $this->MoodDataHandle($m->alias('m')->join('__USER__ AS u ON m.user_id=u.id')->field($field)->where($where)->limit($page->GetPageStarNumber(), $number)->order('m.id desc')->select());
		$this->assign('data', $data);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 基础数据
		$this->assign('common_user_visible_list', L('common_user_visible_list'));
		$this->assign('bubble_nav_list', L('bubble_nav_list'));

		$this->display('Index');
	}

	/**
	 * [MoodDataHandle 说说数据处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-08T20:14:19+0800
	 * @param    [array]     $data [需要处理的数据]
	 * @return   [array]           [处理好的说说数据]
	 */
	private function MoodDataHandle($data)
	{
		if(!empty($data) && is_array($data))
		{
			$mp = M('MoodPraise');
			$mc = M('MoodComments');
			foreach($data as $k=>&$v)
			{
				// 昵称
				if(empty($v['nickname']))
				{
					$v['nickname'] = L('common_bubble_mood_nickname');
				}

				// 发表时间
				$v['add_time'] = date('m-d H:i', $v['add_time']);

				// 点赞
				$v['praise_count'] = $mp->where(array('mood_id'=>$v['id']))->count();

				// 用户是否已点赞过
				$v['is_praise'] = ($mp->where(array('mood_id'=>$v['id'], 'user_id'=>$this->user['id']))->getField('id') > 0) ? 'ok' : 'no';

				// 评论总数
				$v['comments_count'] = $mc->where(array('mood_id'=>$v['id']))->count();

				// 评论列表
				$v['comments'] = $this->GetMoodComments($v['id']);
			}
		}
		return $data;
	}

	/**
	 * [UserIsStateCheck 用户状态校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-12T15:29:45+0800
	 */
	private function UserIsStateCheck()
	{
		$state = M('User')->where(array('id'=>$this->user['id']))->getField('state');
		if($state > 0)
		{
			$this->ajaxReturn(L('common_user_state_list')[$state]['tips'], -10);
		}
	}

	/**
	 * [GetMoodComments 获取说说评论]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-10T14:38:00+0800
	 * @param    [int]       $mood_id [说说id]
	 * @return   [array]              [评论列表]
	 */
	private function GetMoodComments($mood_id)
	{
		// 参数
		if(empty($mood_id))
		{
			return array();
		}

		// 评论列表
		$m = M('MoodComments');
		$field = array('mc.id', 'mc.user_id', 'mc.content', 'mc.reply_id', 'mc.add_time', 'u.nickname');
		$where = array('m.id'=>$mood_id, 'mc.reply_id'=>0);
		$data = $m->alias('mc')->join('__MOOD__ AS m ON mc.mood_id=m.id')->join('__USER__ AS u ON mc.user_id=u.id')->field($field)->where($where)->order('mc.id asc')->select();

		// 回复列表
		if(!empty($data))
		{
			$u = M('User');
			foreach($data as &$v)
			{
				// 评论时间
				$v['add_time'] = date('m-d H:i', $v['add_time']);

				// 评论内容
				$v['content'] = str_replace("\n", "<br />", $v['content']);

				$item_where = array('m.id'=>$mood_id, 'mc.parent_id'=>$v['id'], 'reply_id'=>array('gt', 0));
				$item = $m->alias('mc')->join('__MOOD__ AS m ON mc.mood_id=m.id')->join('__USER__ AS u ON mc.user_id=u.id')->field($field)->where($item_where)->order('mc.id asc')->select();
				if(!empty($item))
				{
					foreach($item as &$vs)
					{
						// 评论时间
						$vs['add_time'] = date('m-d H:i', $vs['add_time']);

						// 评论内容
						$vs['content'] = str_replace("\n", "<br />", $vs['content']);

						// 被回复的用户
						if($vs['reply_id'] > 0)
						{
							$uid = $m->where(array('id'=>$vs['reply_id']))->getField('user_id');
							if(!empty($uid))
							{
								$user = $u->field(array('id AS reply_user_id', 'nickname AS reply_nickname'))->find($uid);
								if(empty($user['reply_nickname']))
								{
									$user['reply_nickname'] = L('common_bubble_mood_nickname');
								}
								$vs = array_merge($vs, $user);
							}
						}
					}
					$v['item'] = $item;
				}
			}
		}
		return $data;
	}

	/**
	 * [MoodSave 说说保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-08T13:21:34+0800
	 */
	public function MoodSave()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 用户状态校验
		$this->UserIsStateCheck();

		// 说说模型
		$m = D('Mood');

		// 编辑
		if($m->create($_POST, 1) !== false)
		{
			$m->user_id		=	$this->user['id'];
			$m->content 	=	I('content');
			$m->add_time	=	time();

			// 开启事务
			$m->startTrans();

			// 更新用户签名
			if(I('is_sign') == 1)
			{
				$data = array(
						'signature'	=>	$m->content,
						'upd_time'	=>	time(),
					);
				$user_state = (M('User')->where(array('id'=>$this->user['id']))->save($data) !== false);
			} else {
				$user_state = true;
			}

			// 添加历史签名
			$mood_state = $m->add();

			// 状态
			if($user_state && $mood_state > 0)
			{
				// 提交事务
				$m->commit();

				// 更新用户session数据
				if(I('is_sign') == 1)
				{
					$this->UserLoginRecord($this->user['id']);
				}

				$this->ajaxReturn(L('common_operation_publish_success'));
			} else {

				// 回滚事务
				$m->rollback();
				$this->ajaxReturn(L('common_operation_publish_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [MoodDelete 说说删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function MoodDelete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$m = M('Mood');
		$id = I('id');

		// 只能删除自己的说说
		$user_id = $m->where(array('id'=>$id))->getField('user_id');
		if($user_id != $this->user['id'])
		{
			$this->ajaxReturn(L('bubble_mood_delete_error'), -2);
		}

		// 开启事务
		$m->startTrans();

		// 数据删除[说说,点赞,评论]
		$mood_state = $m->where(array('id'=>$id, 'user_id'=>$this->user['id']))->delete();
		$praise_state = M('MoodPraise')->where(array('mood_id'=>$id))->delete();
		$comments_state = M('MoodComments')->where(array('mood_id'=>$id))->delete();
		if($mood_state !== false && $praise_state !== false && $comments_state !== false)
		{
			// 提交事务
			$m->commit();

			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			// 回滚事务
			$m->rollback();

			$this->ajaxReturn(L('common_operation_delete_error'), -100);
		}
	}

	/**
	 * [MoodCommentsDelete 说说评论删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function MoodCommentsDelete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 模型
		$m = M('MoodComments');

		// 只能删除自己的评论
		$user_id = $m->where(array('id'=>I('id')))->getField('user_id');
		if($user_id != $this->user['id'])
		{
			$this->ajaxReturn(L('bubble_comments_delete_error'), -2);
		}

		// 开启事务
		$m->startTrans();

		// 数据删除
		$state = $m->where(array('id'=>I('id'), 'user_id'=>$this->user['id']))->delete();
		$item_state = $m->where(array('parent_id'=>I('id')))->delete();
		$reply_state = $m->where(array('reply_id'=>I('id')))->delete();
		if($state !== false && $item_state !== false && $reply_state !== false)
		{
			// 提交事务
			$m->commit();

			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			// 回滚事务
			$m->rollback();

			$this->ajaxReturn(L('common_operation_delete_error'), -100);
		}
	}

	/**
	 * [MoodPraise 说说点赞]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-09T12:24:24+0800
	 */
	public function MoodPraise()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 用户状态校验
		$this->UserIsStateCheck();

		// 参数
		if(empty($_POST['id']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}
		$id = I('id');

		// 不能点赞自己的说说
		$mood = M('Mood')->field(array('id', 'user_id'))->find($id);
		if(empty($mood))
		{
			$this->ajaxReturn(L('bubble_mood_no_exist_error'), -2);
		} else {
			if($mood['user_id'] == $this->user['id'])
			{
				$this->ajaxReturn(L('bubble_mood_praise_error'), -3);
			}
		}

		// 查询数据
		$m = M('MoodPraise');
		$where = array('user_id'=>$this->user['id'], 'mood_id'=>$id);
		$temp = $m->where($where)->getField('id');

		// 数据存在删除, 则添加
		if(empty($temp))
		{
			$data = array(
					'mood_id'	=>	$id,
					'user_id'	=>	$this->user['id'],
					'add_time'	=>	time(),
				);
			$state = ($m->add($data) > 0);
		} else {
			$state = ($m->where($where)->delete() !== false);
		}

		// 状态
		if($state)
		{
			$this->ajaxReturn(L('common_operation_success'));
		} else {
			$this->ajaxReturn(L('common_operation_error'), -100);
		}
	}

	/**
	 * [MoodComments 说说评论]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-09T18:52:32+0800
	 */
	public function MoodComments()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 用户状态校验
		$this->UserIsStateCheck();

		// 说说评论模型
		$m = D('MoodComments');

		// 编辑
		if($m->create($_POST, 1) !== false)
		{
			// 不能回复自己的评论
			if($m->reply_id > 0)
			{
				$user_id = $m->where(array('id'=>$m->reply_id))->getField('user_id');
				if($user_id == $this->user['id'])
				{
					$this->ajaxReturn(L('bubble_comments_reply_error'), -2);
				}
			}

			// 额外数据处理
			$m->add_time	=	time();
			$m->content 	=	I('content');
			$m->user_id		=	$this->user['id'];
			
			// 写入数据库
			if($m->add())
			{
				$this->ajaxReturn(L('common_operation_comments_success'));
			} else {
				$this->ajaxReturn(L('common_operation_comments_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}
}
?>