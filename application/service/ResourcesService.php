<?php
namespace app\service;

use think\Db;

/**
 * 资源服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ResourcesService
{
    /**
     * [ContentStaticReplace 编辑器中内容的静态资源替换]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-22T16:07:58+0800
     * @param    [string]    $content [在这个字符串中查找进行替换]
     * @param    [string]    $type    [操作类型[get读取额你让, add写入内容](编辑/展示传入get,数据写入数据库传入add)]
     * @return   [string]             [正确返回替换后的内容, 则返回原内容]
     */
    public static function ContentStaticReplace($content, $type = 'get')
    {
        switch($type)
        {
            // 读取内容
            case 'get':
                return str_replace('/static/', __MY_PUBLIC_URL__.'static/', $content);
                break;

            // 内容写入
            case 'add':
                return str_replace(array(__MY_PUBLIC_URL__.'static/', __MY_ROOT__.'static/'), '/static/', $content);
        }
        return $content;
    }

    /**
     * 附件路径处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-12
     * @desc    description
     * @param   [string]          $value [附件路径地址]
     */
    public static function AttachmentPathHandle($value)
    {
        return empty($value) ? '' : str_replace([__MY_PUBLIC_URL__, __MY_ROOT__], DS, $value);
    }

    /**
     * 附件集合处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-07
     * @desc    description
     * @param    [array]          $params [输入参数]
     * @param   [array]           $data   [字段列表]
     */
    public static function AttachmentParams($params, $data)
    {
        $result = [];
        if(!empty($data))
        {
            foreach($data as $field)
            {
                $result[$field] = isset($params[$field]) ? self::AttachmentPathHandle($params[$field]) : '';
            }
        }

        return DataReturn('success', 0, $result);
    }
}
?>