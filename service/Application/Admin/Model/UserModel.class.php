<?php

namespace Admin\Model;
use Think\Model;

/**
 * 用户模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(		
		// 添加,编辑
		array('username', '0,30', '{%user_username_format}', 1, 'length', 3),
		array('mobile', 'CheckMobile', '{%common_mobile_format_error}', 2, 'function', 3),
		array('mobile', 'IsExistMobile', '{%common_mobile_exist_error}', 2, 'callback', 3),

		array('nickname', '0,30', '{%user_nickname_format}', 2, 'length', 3),
		array('gender', array(0,1,2), '{%common_gender_tips}', 2, 'in', 3),
		array('birthday', 'CheckBirthday', '{%user_birthday_format}', 2, 'callback', 3),
		array('email', 'CheckEmail', '{%common_email_format_error}', 2, 'function', 3),
		array('email', '', '{%common_email_exist_error}', 2, 'unique', 3),
	);

	/**
	 * [CheckBirthday 生日]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T15:12:32+0800
	 */
	public function CheckBirthday($value)
	{
		return (preg_match('/'.L('common_regex_date').'/', $value) == 1) ? true : false;
	}

    /**
     * [IsExistMobile 手机号码是否已存在]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T14:09:40+0800
     */
    public function IsExistMobile($value)
    {
        $id = $this->db(0)->where(array('mobile'=>$value, 'is_delete_time'=>0))->getField('id');
        if(!empty($id))
        {
            if(!empty($_POST['id']) && $id != I('id'))
            {
                return false;
            }
        }
        return true;
    }
}
?>