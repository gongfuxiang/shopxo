<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

use think\facade\Db;
use app\service\UserService;
use app\service\SystemBaseService;

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
     * 编辑器中内容的静态资源替换
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
        // 仅处理字符串和数字类型
        if(is_string($content) || is_int($content))
        {
            // 配置文件附件url地址
            $attachment_host = SystemBaseService::AttachmentHost();
            if(empty($attachment_host))
            {
                $attachment_host = substr(__MY_PUBLIC_URL__, 0, -1);
            }
            $attachment_host_path = $attachment_host.'/static/';

            // 根据类型处理附件地址
            switch($type)
            {
                // 读取内容
                case 'get':
                    return str_replace('src="/static/', 'src="'.$attachment_host_path, $content);
                    break;

                // 内容写入
                case 'add':
                    $search = [
                        'src="'.__MY_PUBLIC_URL__.'static/',
                        'src="'.__MY_ROOT_PUBLIC__.'static/',
                        'src="'.$attachment_host_path,
                    ];
                    return str_replace($search, 'src="/static/', $content);
            }
        }
        return $content;
    }

    /**
     * 附件路径处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-12
     * @desc    description
     * @param   [string|array]    $value [附件路径地址]
     * @param   [string]          $field [url字段名称]
     */
    public static function AttachmentPathHandle($value, $field = 'url')
    {
        // 配置文件附件url地址
        $attachment_host = SystemBaseService::AttachmentHost();
        $attachment_host_path = empty($attachment_host) ? __MY_PUBLIC_URL__ : $attachment_host.DS;

        // 替换处理
        $search = [
            $attachment_host_path,
            __MY_PUBLIC_URL__,
            __MY_ROOT_PUBLIC__,
        ];

        // 是否数组
        if(!empty($value))
        {
            if(is_array($value))
            {
                foreach($value as &$v)
                {
                    // 是否二级
                    if(isset($v[$field]))
                    {
                        $v[$field] = empty($v[$field]) ? '' : str_replace($search, DS, $v[$field]);
                    } else {
                        $v = empty($v) ? '' : str_replace($search, DS, $v);
                    }
                }
            } else {
                $value = empty($value) ? '' : str_replace($search, DS, $value);
            }
        }
        return $value;
    }

    /**
     * 附件集合处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @param   [array]          $data   [字段列表]
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
     * @param    [string|array]   $value [附件地址]
     * @param    [string]         $field [url字段名称]
     */
    public static function AttachmentPathViewHandle($value, $field = 'url')
    {
        if(!empty($value))
        {
            // 是否数组
            if(is_array($value))
            {
                $host = SystemBaseService::AttachmentHost();
                foreach($value as &$v)
                {
                    // 是否二级
                    if(isset($v[$field]))
                    {
                        if(substr($v[$field], 0, 4) != 'http')
                        {
                            $v[$field] = $host.$v[$field];
                        }
                    } else {
                        if(substr($v, 0, 4) != 'http')
                        {
                            $v = $host.$v;
                        }
                    }
                }
            } else {
                if(substr($value, 0, 4) != 'http')
                {
                    $value = SystemBaseService::AttachmentHost().$value;
                }
            }
        }
        return $value;
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
        $config = MyConfig('ueditor');

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
                'error_msg'         => MyLang('common_service.resources.save_attachment_title_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'original',
                'error_msg'         => MyLang('common_service.resources.save_attachment_original_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'path_type',
                'error_msg'         => MyLang('common_service.resources.save_attachment_path_type_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'url',
                'error_msg'         => MyLang('common_service.resources.save_attachment_url_tips'),
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'size',
                'error_msg'         => MyLang('common_service.resources.save_attachment_size_tips'),
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'ext',
                'error_msg'         => MyLang('common_service.resources.save_attachment_ext_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'hash',
                'error_msg'         => MyLang('common_service.resources.save_attachment_hash_tips'),
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
        MyEventTrigger($hook_name, [
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
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
                'attachment_id' => $attachment_id,
            ]);

            $params['id'] = $attachment_id;
            $params['url'] = self::AttachmentPathViewHandle($data['url']);
            $params['add_time'] = date('Y-m-d H:i:s', $data['add_time']);
            return DataReturn(MyLang('insert_success'), 0, $params);
        }

        // 删除本地图片
        if(!empty($params['path']))
        {
            \base\FileUtil::UnlinkFile($params['path']);
        }
        return DataReturn(MyLang('insert_fail'), -100);
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
        $data = Db::name('Attachment')->where($params['where'])->order('id desc')->limit($m, $n)->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 附件列表处理前钩子
                $hook_name = 'plugins_service_attachment_list_handle_begin';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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
                'error_msg'         => MyLang('data_id_error_tips'),
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
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
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

                    $ret = DataReturn(MyLang('delete_success'), 0);
                } else {
                    $ret = DataReturn(MyLang('delete_fail'), -100);
                }
            } else {
                $ret = DataReturn(MyLang('common_service.resources.delete_no_power_tips'), -1);
            }
        } else {
            if(DB::name('Attachment')->where(['id'=>$data['id']])->delete())
            {
                $ret = DataReturn(MyLang('delete_success'), 0);
            } else {
                $ret = DataReturn(MyLang('delete_fail'), -100);
            }
        }

        // 处理
        if($ret['code'] == 0)
        {
            // 附件删除成功后处理钩子
            $hook_name = 'plugins_service_attachment_delete_success';
            MyEventTrigger($hook_name, [
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
        // 获取附件数据
        $where = ['path_type'=>$path_type];
        $data = DB::name('Attachment')->where($where)->select()->toArray();
        if(!empty($data))
        {
            // 删除数据库数据
            if(!DB::name('Attachment')->where($where)->delete())
            {
                return DataReturn(MyLang('delete_fail'), -1);
            }

            // 删除磁盘文件
            $path = substr(ROOT_PATH, 0, -1);
            foreach($data as $v)
            {
                $file = $path.$v['url'];
                if(file_exists($file) && is_writable($file))
                {
                    \base\FileUtil::UnlinkFile($file);
                }
            }
        }

        // 附件删除成功后处理钩子
        $hook_name = 'plugins_service_attachment_path_type_delete_success';
        MyEventTrigger($hook_name, [
            'hook_name'         => $hook_name,
            'is_backend'        => true,
            'data'              => $data,
        ]);

        return DataReturn(MyLang('delete_success'), 0);
    }

    /**
     * 附件根据地址删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T23:35:27+0800
     * @param    [string|array]              $url [图片url地址]
     */
    public static function AttachmentUrlDelete($url)
    {
        // url格式处理
        if(!is_array($url))
        {
            $url = explode(',', $url);
        }
        foreach($url as &$v)
        {
            $v = self::AttachmentPathHandle($v);
        }

        // 获取附件数据
        $data = DB::name('Attachment')->where(['url'=>$url])->select()->toArray();
        if(!empty($data))
        {
            // 删除数据库数据
            if(!DB::name('Attachment')->where(['id'=>array_column($data, 'id')])->delete())
            {
                return DataReturn(MyLang('delete_fail'), -1);
            }

            // 删除磁盘文件
            $path = substr(ROOT_PATH, 0, -1);
            foreach($data as $v)
            {
                $file = $path.$v['url'];
                if(file_exists($file) && is_writable($file))
                {
                    \base\FileUtil::UnlinkFile($file);
                }
            }
        }

        // 附件删除成功后处理钩子
        $hook_name = 'plugins_service_attachment_url_delete_success';
        MyEventTrigger($hook_name, [
            'hook_name'         => $hook_name,
            'is_backend'        => true,
            'data'              => $data,
        ]);

        return DataReturn(MyLang('delete_success'), 0);
    }

    /**
     * 磁盘附加同步到数据库
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-02
     * @desc    description
     * @param   [string]          $dir_path     [附件路径类型]
     * @param   [string]          $path_type    [附件路径值类型]
     */
    public static function AttachmentDiskFilesToDb($dir_path, $path_type = '')
    {
        // 未指定类型值则使用路径值
        if(empty($path_type))
        {
            $path_type = $dir_path;
        }

        // 处理状态总数
        $count = 0;
        $success = 0;
        $error = 0;

        // 视频/文件/图片
        $path_all = [
            'video' => __MY_ROOT_PUBLIC__.'static/upload/video/'.$dir_path.'/',
            'file'  => __MY_ROOT_PUBLIC__.'static/upload/file/'.$dir_path.'/',
            'image' => __MY_ROOT_PUBLIC__.'static/upload/images/'.$dir_path.'/',
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
                    $temp = Db::name('Attachment')->where(['title'=>$v['title'], 'hash'=>$v['hash'], 'path_type'=>$path_type])->find();
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
        return DataReturn(MyLang('common_service.resources.sync_file_to_db_tips', ['count'=>$count, 'success'=>$success, 'error'=>$error]), 0);
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
            '<img style="max-width:100%;height:auto;margin:0;padding:0;display:block;" ',
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

    /**
     * 正则匹配富文本图片
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-16
     * @desc    description
     * @param   [string]      $content  [内容]
     * @param   [string]      $business [业务模块名称]
     * @param   [string]      $type     [附件类型（images 图片, file 文件, video 视频）]
     */
    public static function RichTextMatchContentAttachment($content, $business, $type = 'images')
    {
        if(!empty($content))
        {
            $pattern = '/<img.*?src=[\'|\"](\/static\/upload\/'.$type.'\/'.$business.'\/.*?[\.png|\.jpg|\.jpeg|\.gif|\.bmp|\.flv|\.swf|\.mkv|\.avi|\.rm|\.rmvb|\.mpeg|\.mpg|\.ogg|\.ogv|\.mov|\.wmv|\.mp4|\.webm|\.mp3|\.wav|\.mid|\.rar|\.zip|\.tar|\.gz|\.7z|\.bz2|\.cab|\.iso|\.doc|\.docx|\.xls|\.xlsx|\.ppt|\.pptx|\.pdf|\.txt|\.md|\.xml])[\'|\"].*?[\/]?>/';
            preg_match_all($pattern, self::AttachmentPathHandle($content), $match);
            return empty($match[1]) ? [] : $match[1];
        }
        return [];
    }

    /**
     * 货币信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public static function CurrencyData()
    {
        // 默认从配置文件读取货币信息
        $data = [
            'currency_symbol'   => MyConfig('shopxo.currency_symbol'),
            'currency_code'     => MyConfig('shopxo.currency_code'),
            'currency_rate'     => MyConfig('shopxo.currency_rate'),
            'currency_name'     => MyConfig('shopxo.currency_name'),
        ];

        // 钩子
        $hook_name = 'plugins_service_currency_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 货币信息-符号
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public static function CurrencyDataSymbol()
    {
        $res = self::CurrencyData();
        return empty($res['currency_symbol']) ? MyConfig('shopxo.currency_symbol') : $res['currency_symbol'];
    }

    /**
     * 编辑器文件存放地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-27
     * @desc    description
     * @param   [string]          $value [位置路径名称（[ - ]作为目录分隔符）]
     */
    public static function EditorPathTypeValue($value)
    {
        // 当前操作名称, 兼容插件模块名称
        $module_name = RequestModule();
        $controller_name = RequestController();
        $action_name = RequestAction();

        // 钩子
        $hook_name = 'plugins_service_editor_path_type_'.$module_name.'_'.$controller_name.'_'.$action_name;
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'value'         => &$value,
        ]);

        return $value;
    }

    /**
     * zip压缩包扩展可用格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-02
     * @desc    description
     * @param   array           $params [description]
     */
    public static function ZipExtTypeList($params = [])
    {
        return [
            'application/zip',
            'application/octet-stream',
            'application/x-zip-compressed',
        ];
    }

    /**
     * 获取用户唯一id
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-15
     * @desc    未登录取[uuid]前端传过来的uuid、已登录取[用户id]、都没有则返回空字符串
     */
    public static function UserUniqueId()
    {
        // 取参数uuid、默认空
        $uid = input('uuid', '');

        // 取当当前session
        if(empty($uid))
        {
            $uid = MySession('uuid');
        }
        // 取当当前cookie
        if(empty($uid))
        {
            $uid = MyCookie('uuid');
        }

        // 用户信息
        $user = UserService::LoginUserInfo();
        if(!empty($user) && !empty($user['id']))
        {
            $uid = $user['id'];
        }

        return empty($uid) ? '' : md5($uid);
    }

    /**
     * 获取表结构
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-11-12
     * @desc    description
     * @param   [string]          $table [表名称、可以是大写字母会自动转为小写前面加下划线分隔]
     */
    public static function TableStructureData($table)
    {
        // 表名处理及sql
        $table_name = MyConfig('database.connections.mysql.prefix').strtolower(preg_replace('/\B([A-Z])/', '_$1', $table));
        $sql = "SELECT COLUMN_NAME AS field,COLUMN_COMMENT AS name FROM INFORMATION_SCHEMA.Columns WHERE `table_name`='".$table_name."'";

        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_table_structure_key').'_'.md5($sql);
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug'))
        {
            // 查询表结构
            $res = Db::query($sql);
            $data = empty($res) ? [] : array_column($res, 'name', 'field');

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }
}
?>