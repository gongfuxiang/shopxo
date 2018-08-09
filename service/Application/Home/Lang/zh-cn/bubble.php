<?php

/**
 * 模块语言包-冒泡
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	'bubble_mood_placeholder'			=>	'说点什么吧',
	'bubble_mood_format'				=>	'先随便说两句吧......',
	'bubble_mood_error'					=>	'说说内容格式 1~168 个字符之间',
	'bubble_visible_text'				=>	'可见范围：',
	'bubble_visible_error'				=>	'可见范围值有误',
	'bubble_is_sign_hover'				=>	'同步至个人签名',
	'bubble_mood_no_exist_error'		=>	'该说说不存在',
	'bubble_mood_delete_error'			=>	'只能删除自己的说说',
	'bubble_mood_praise_error'			=>	'不能点赞自己的说说',
	'bubble_mood_comments_error'		=>	'不能评论自己的说说',
	'bubble_comments_placeholder'		=>	'评论内容',
	'bubble_comments_format'			=>	'先随便说两句吧......',
	'bubble_comments_content_error'		=>	'评论内容格式 1~255 个字符之间',
	'bubble_comments_mood_id_error'		=>	'说说记录不存在',
	'bubble_comments_reply_id_error'	=>	'评论记录不存在',
	'bubble_comments_parent_id_error'	=>	'父级评论记录不存在',
	'bubble_comments_reply_error'		=>	'不能回复自己的评论',
	'bubble_comments_delete_error'		=>	'只能删除自己的评论',
	'bubble_nav_list'					=>	array(
			array('type' => 'all', 'url' => U('Home/Bubble/Index', ['type'=>'all']), 'name' => '冒泡广场'),
			array('type' => 'own', 'url' => U('Home/Bubble/Index', ['type'=>'own']),'name' => '本人冒泡'),
		),
);
?>