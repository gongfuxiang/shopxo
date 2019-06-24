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
use think\facade\Hook;

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
                return str_replace('src="/static/', 'src="'.__MY_PUBLIC_URL__.'static/', $content);
                break;

            // 内容写入
            case 'add':
                return str_replace(array('src="'.__MY_PUBLIC_URL__.'static/', 'src="'.__MY_ROOT_PUBLIC__.'static/'), 'src="/static/', $content);
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
        return empty($value) ? '' : str_replace([__MY_PUBLIC_URL__, __MY_ROOT_PUBLIC__], DS, $value);
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

    /**
     * 附件展示地址处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-01-13T15:13:30+0800
     * @param    [type]                   $value [description]
     */
    public static function AttachmentPathViewHandle($value)
    {
        if(!empty($value))
        {
            if(substr($value, 0, 4) != 'http')
            {
                return config('shopxo.attachment_host').$value;
            }
            return $value;
        }
        return '';
    }

    /**
     * 附件添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T00:13:33+0800
     * @param    [array]         $params [输入参数]
     */
    public static function AttachmentAdd($params = [])
    {
        if(!empty($params['title']))
        {
            // 数据组装
            $data = [
                'path_type'     => input('path_type', 'other'),
                'original'      => empty($params['original']) ? '' : mb_substr($params['original'], -160, null, 'utf-8'),
                'title'         => $params['title'],
                'size'          => $params['size'],
                'type'          => $params['type'],
                'hash'          => $params['hash'],
                'path'          => self::AttachmentPathHandle($params['url']),
                'add_time'      => time(),
            ];

            // 附件上传前处理钩子
            $hook_name = 'plugins_service_attachment_handle_begin';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data'          => &$data,
            ]);

            // 添加到数据库
            $attachment_id = Db::name('Attachment')->insertGetId($data);
            if($attachment_id > 0)
            {
                // 附件上传后处理钩子
                $hook_name = 'plugins_service_attachment_handle_end';
                $ret = Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'data'          => &$data,
                    'attachment_id' => $attachment_id,
                ]);

                return DataReturn('添加成功', 0, $params);
            }
            return DataReturn('添加失败', 0);
        }
        return DataReturn('附件不能为空', -1);
    }
}
?>