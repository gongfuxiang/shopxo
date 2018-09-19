<?php

/**
 * 模块语言包-支付方式
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	// 添加/编辑
	'payment_edit_name'                =>  '支付方式编辑',

    'payment_name_text'                =>  '名称',
    'payment_name_format'              =>  '名称格式 2~30 个字符',
    'payment_payment_format'           =>  '唯一标记格式 1~60 个字符',

    'payment_apply_version_text'       =>  '适用版本',

    'payment_desc_text'                =>  '描述',
    'payment_desc_format'              =>  '描述式 0~255 个字符',

    'payment_apply_terminal_text'      =>  '适用终端',
    'payment_apply_terminal_format'    =>  '至少选择一个适用终端',

    'payment_author_text'              =>  '作者',

    'payment_logo_text'                =>  'LOGO',
    'payment_logo_format'              =>  'LOGO 0~255 个字符',

    'payment_upload_tips'              =>  '1 类名必须于文件名一致（去除 .class.php ），如 Alipay.class.php 则取 Alipay <br />2 类必须定义三个方法<br />&nbsp;&nbsp;&nbsp; 2.1 Config 配置方法<br />&nbsp;&nbsp;&nbsp; 2.2 Pay 支付方法<br />&nbsp;&nbsp;&nbsp; 2.3 Respond 回调方法',
    'payment_upload_ps'                =>  'PS：以上条件不满足则无法查看插件',
    'payment_upload_format'            =>  '文件格式有误，必须php文件',
    'payment_upload_error'             =>  '插件编写有误，请参考文档编写',
    'payment_plugins_element_tips'     =>  '该区域为插件配置填写项，请按照插件文档填写相应的值。',
);
?>