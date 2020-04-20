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
     * 相对路径文件新增
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-04-16
     * @desc    description
     * @param   [string]          $value        [相对路径文件 /static 开头]
     * @param   [string]          $path_type    [文件存储路径]
     */
    public static function AttachmentPathAdd($value, $path_type)
    {
        // 文件是否存在
        $file = ROOT.'public'.$value;
        if(!file_exists($file))
        {
            return DataReturn('文件不存在', -1);
        }

        // 配置信息
        $config = config('ueditor.');

        // 文件信息
        $info = pathinfo($file);
        $title = empty($info['basename']) ? substr(strrchr($file, '/'), 1) : $info['basename'];
        $ext = strtolower(strrchr($file, '.'));
        $type = in_array($ext, $config['imageAllowFiles']) ? 'image' : (in_array($ext, $config['videoAllowFiles']) ? 'video' : 'file');

        // 添加文件
        $data = [
            "url"       => $value,
            "path"      => $file,
            "title"     => $title,
            "original"  => $title,
            "ext"       => $ext,
            "size"      => filesize($file),
            'type'      => $type,
            "hash"      => hash_file('sha256', $file, false),
            'path_type' => $path_type,
        ];
        return self::AttachmentAdd($data);
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
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'title',
                'error_msg'         => '名称有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'original',
                'error_msg'         => '原名有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'path_type',
                'error_msg'         => '路径标记有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'url',
                'error_msg'         => '地址有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'size',
                'error_msg'         => '文件大小有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'ext',
                'error_msg'         => '扩展名有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'hash',
                'error_msg'         => 'hash值有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据组装
        $data = [
            'path_type'     => $params['path_type'],
            'original'      => empty($params['original']) ? '' : mb_substr($params['original'], -160, null, 'utf-8'),
            'title'         => $params['title'],
            'size'          => $params['size'],
            'ext'           => $params['ext'],
            'type'          => isset($params['type']) ? $params['type'] : 'file',
            'hash'          => $params['hash'],
            'url'           => self::AttachmentPathHandle($params['url']),
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
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
                'attachment_id' => $attachment_id,
            ]);

            $params['id'] = $attachment_id;
            $params['url'] = self::AttachmentPathViewHandle($data['url']);
            $params['add_time'] = date('Y-m-d H:i:s', $data['add_time']);
            return DataReturn('添加成功', 0, $params);
        }

        // 删除本地图片
        if(!empty($params['path']))
        {
            \base\FileUtil::UnlinkFile($params['path']);
        }
        return DataReturn('添加失败', -100);
    }

    /**
     * 获取附件总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T22:44:52+0800
     * @param    [array]               $where [条件]
     */
    public static function AttachmentTotal($where)
    {
        return (int) Db::name('Attachment')->where($where)->count();
    }

    /**
     * 获取附件列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T22:44:52+0800
     * @param    [array]               $params [参数]
     */
    public static function AttachmentList($params = [])
    {
        $m = max(0, isset($params['m']) ? intval($params['m']) : 0);
        $n = max(1, isset($params['n']) ? intval($params['n']) : 20);
        $data = Db::name('Attachment')->where($params['where'])->order('id desc')->limit($m, $n)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 附件列表处理前钩子
                $hook_name = 'plugins_service_attachment_list_handle_begin';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'data'          => &$v,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                // 数据处理
                $v['url'] = self::AttachmentPathViewHandle($v['url']);
                $v['add_time'] = date('Y-m-d H:i:s');

                // 附件列表处理后钩子
                $hook_name = 'plugins_service_attachment_list_handle_end';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'data'          => &$v,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }
            }
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 附件删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T23:35:27+0800
     * @param    [array]              $params [输入参数]
     */
    public static function AttachmentDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $data = Db::name('Attachment')->find(intval($params['id']));
        if(empty($data))
        {
            return DataReturn('数据不存在或已删除', -1);
        }

        // 删除文件
        $path = substr(ROOT_PATH, 0, -1).$data['url'];
        if(file_exists($path))
        {
            if(is_writable($path))
            {
                if(DB::name('Attachment')->where(['id'=>$data['id']])->delete())
                {
                    // 删除附件
                    \base\FileUtil::UnlinkFile($path);

                    $ret = DataReturn('删除成功', 0);
                } else {
                    $ret = DataReturn('删除失败', -100);
                }
            } else {
                $ret = DataReturn('没有删除权限', -1);
            }
        } else {
            if(DB::name('Attachment')->where(['id'=>$data['id']])->delete())
            {
                $ret = DataReturn('删除成功', 0);
            } else {
                $ret = DataReturn('删除失败', -100);
            }
        }

        // 处理
        if($ret['code'] == 0)
        {
            // 附件删除成功后处理钩子
            $hook_name = 'plugins_service_attachment_delete_success';
            Hook::listen($hook_name, [
                'hook_name'         => $hook_name,
                'is_backend'        => true,
                'data'              => $data,
            ]);
        }
        return $ret;
    }

    /**
     * 附件根据标记删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T23:35:27+0800
     * @param    [string]              $path_type [唯一标记]
     */
    public static function AttachmentPathTypeDelete($path_type)
    {
        // 请求参数
        if(DB::name('Attachment')->where(['path_type'=>$path_type])->delete() !== false)
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
    }

    /**
     * 磁盘附加同步到数据库
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-02
     * @desc    description
     * @param   [string]          $path_type [附件路径类型]
     */
    public static function AttachmentDiskFilesToDb($path_type)
    {
        // 处理状态总数
        $count = 0;
        $success = 0;
        $error = 0;

        // 视频/文件/图片
        $path_all = [
            'video' => __MY_ROOT_PUBLIC__.'static/upload/video/'.$path_type.'/',
            'file'  => __MY_ROOT_PUBLIC__.'static/upload/file/'.$path_type.'/',
            'image' => __MY_ROOT_PUBLIC__.'static/upload/images/'.$path_type.'/',
        ];
        foreach($path_all as $type=>$path)
        {
            $path = GetDocumentRoot() . (substr($path, 0, 1) == "/" ? "":"/") . $path;
            $files =self::AttachmentDiskFilesList($path, $type, $path_type);
            if(!empty($files))
            {
                $count += count($files);
                foreach($files as $v)
                {
                    $temp = Db::name('Attachment')->where(['title'=>$v['title'], 'hash'=>$v['hash']])->find();
                    if(empty($temp))
                    {
                        $ret = self::AttachmentAdd($v);
                        if($ret['code'] == 0)
                        {
                            $success++;
                        } else {
                            $error++;
                        }
                    } else {
                        $success++;
                    }
                }
            }
        }
        return DataReturn('总数['.$count.'], 成功['.$success.'], 失败['.$error.']', 0);
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-17T23:24:59+0800
     * @param    [string]        $path          [路径地址]
     * @param    [string]        $type          [允许的文件]
     * @param    [string]        $path_type     [路径类型]
     * @param    [array]         &$files        [数据]
     * @return   [array]                        [数据]
     */
    public static function AttachmentDiskFilesList($path, $type, $path_type, &$files = [])
    {
        if(!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        $document_root = GetDocumentRoot();
        while(false !== ($file = readdir($handle)))
        {
            if($file != 'index.html' && $file != '.' && $file != '..' && substr($file, 0, 1) != '.')
            {
                $temp_path = $path . $file;
                if(is_dir($temp_path))
                {
                    self::AttachmentDiskFilesList($temp_path, $type, $path_type, $files);
                } else {
                    $url = self::AttachmentPathHandle(substr($temp_path, strlen($document_root)));
                    $title = substr($url, strripos($url, '/')+1);
                    $root_path = ROOT.'public'.$url;
                    $files[] = array(
                        'url'       => $url,
                        'original'  => $title,
                        'title'     => $title,
                        'type'      => $type,
                        'path_type' => $path_type,
                        'size'      => file_exists($root_path) ? filesize($root_path) : 0,
                        'hash'      => file_exists($root_path) ? hash_file('sha256', $root_path, false) : '',
                        'ext'       => substr($title, strripos($title, '.')),
                    );
                }
            }
        }
        return $files;
    }

    /**
     * 小程序富文本标签处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-04
     * @desc    description
     * @param   [string]          $content [需要处理的富文本内容]
     */
    public static function ApMiniRichTextContentHandle($content)
    {
        // 标签处理，兼容小程序rich-text
        $search = [
            '<img ',
            '<section',
            '/section>',
            '<p style="',
            '<p>',
            '<div>',
            '<table',
            '<tr',
            '<td',
        ];
        $replace = [
            '<img style="max-width:100%;margin:0;padding:0;display:block;" ',
            '<div',
            '/div>',
            '<p style="margin:0;',
            '<p style="margin:0;">',
            '<div style="margin:0;">',
            '<table style="width:100%;margin:0px;border-collapse:collapse;border-color:#ddd;border-style:solid;border-width:0 1px 1px 0;"',
            '<tr style="border-top:1px solid #ddd;"',
            '<td style="margin:0;padding:5px;border-left:1px solid #ddd;"',
        ];
        return str_replace($search, $replace, $content);
    }
}
?>