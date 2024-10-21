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
use app\service\ResourcesService;
use app\service\AttachmentCategoryService;

/**
 * 附件服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class AttachmentService
{
    /**
     * 相对路径文件新增
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-04-16
     * @desc    description
     * @param   [string]          $value        [相对路径文件 /static 开头]
     * @param   [string]          $path_type    [文件存储路径]
     * @param   [array]           $params       [输入参数]
     */
    public static function AttachmentPathAdd($value, $path_type, $params = [])
    {
        // 文件是否存在
        $file = ROOT.'public'.$value;
        if(!file_exists($file))
        {
            return DataReturn(MyLang('common_service.attachment.file_no_exist_tips'), -1);
        }

        // 配置信息
        $config = MyConfig('ueditor');

        // 文件信息
        if(empty($params['title']))
        {
            $info = pathinfo($file);
            $title = empty($info['basename']) ? substr(strrchr($file, '/'), 1) : $info['basename'];
        } else {
            $title = $params['title'];
        }
        $ext = empty($params['ext']) ? strtolower(strrchr($file, '.')) : $params['ext'];
        if(empty($params['type']))
        {
            $type = in_array($ext, $config['imageAllowFiles']) ? 'image' : (in_array($ext, $config['videoAllowFiles']) ? 'video' : 'file');
        } else {
            $type = $params['type'];
        }

        // 添加文件
        $data = [
            'url'          => $value,
            'path'         => $file,
            'title'        => $title,
            'original'     => $title,
            'ext'          => $ext,
            'size'         => filesize($file),
            'type'         => $type,
            'hash'         => hash_file('sha256', $file, false),
            'category_id'  => AttachmentCategoryService::AttachmentCategoryId($path_type),
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
                'error_msg'         => MyLang('common_service.attachment.form_item_title_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'original',
                'error_msg'         => MyLang('common_service.attachment.form_item_original_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => MyLang('common_service.attachment.form_item_category_id_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'url',
                'error_msg'         => MyLang('common_service.attachment.form_item_url_message'),
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'size',
                'error_msg'         => MyLang('common_service.attachment.form_item_size_message'),
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'ext',
                'error_msg'         => MyLang('common_service.attachment.form_item_ext_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'hash',
                'error_msg'         => MyLang('common_service.attachment.form_item_hash_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据组装
        $data = [
            'category_id'   => intval($params['category_id']),
            'original'      => empty($params['original']) ? '' : mb_substr($params['original'], -230, null, 'utf-8'),
            'title'         => $params['title'],
            'size'          => $params['size'],
            'ext'           => $params['ext'],
            'type'          => isset($params['type']) ? $params['type'] : 'file',
            'hash'          => $params['hash'],
            'url'           => ResourcesService::AttachmentPathHandle($params['url']),
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
            $params['url'] = ResourcesService::AttachmentPathViewHandle($data['url']);
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
     * 附件保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T00:13:33+0800
     * @param    [array]         $params [输入参数]
     */
    public static function AttachmentSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'original',
                'error_msg'         => MyLang('common_service.attachment.form_item_original_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        $data = [
            'original' => $params['original'],
        ];
        if(Db::name('Attachment')->where(['id'=>intval($params['id'])])->update($data) === false)
        {
            return DataReturn(MyLang('update_fail'), -100);
        }
        return DataReturn(MyLang('update_success'), 0);
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
                $v['url'] = ResourcesService::AttachmentPathViewHandle($v['url']);
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
     * 附件移动分类
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-25T23:35:27+0800
     * @param    [array]              $params [输入参数]
     */
    public static function AttachmentMoveCategory($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => MyLang('common_service.attachment.form_item_category_id_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 获取附件分类
        $category = Db::name('AttachmentCategory')->where(['id'=>intval($params['category_id'])])->find();
        if(empty($category))
        {
            return DataReturn(MyLang('common_service.attachment.form_item_category_id_message'), -1);
        }

        // 更新分类
        if(Db::name('Attachment')->where(['id'=>$params['ids']])->update(['category_id'=>intval($params['category_id'])]) !== false)
        {
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
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
        $ids = empty($params['ids']) ? (empty($params['id']) ? '' : $params['id']) : $params['ids'];
        if(empty($ids))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        if(!is_array($ids))
        {
            $ids = explode(',', $ids);
        }

        // 获取数据
        $data = Db::name('Attachment')->where(['id'=>$ids])->select()->toArray();
        if(empty($data))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }

        // 附件路径权限验证
        $path = substr(ROOT_PATH, 0, -1);
        foreach($data as $k=>$v)
        {
            if(!empty($v['url']) && file_exists($path.$v['url']))
            {
                if(!is_writable($path.$v['url']))
                {
                    return DataReturn(MyLang('common_service.attachment.delete_no_power_tips').'('.($k+1).')', -1);
                }
            }
        }

        // 删除文件
        foreach($data as $v)
        {
            if(!empty($v['url']) && file_exists($path.$v['url']))
            {
                if(is_writable($path.$v['url']))
                {
                    \base\FileUtil::UnlinkFile($path.$v['url']);
                }
            }
        }

        // 删除数据
        if(DB::name('Attachment')->where(['id'=>array_column($data, 'id')])->delete())
        {
            $ret = DataReturn(MyLang('delete_success'), 0);
        } else {
            $ret = DataReturn(MyLang('delete_fail'), -100);
        }
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
        $category_id = DB::name('AttachmentCategory')->where(['path'=>$path_type])->value('id');
        if(!empty($category_id))
        {
            $where = ['category_id'=>$category_id];
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
        }
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
            $v = ResourcesService::AttachmentPathHandle($v);
        }

        // 获取附件数据
        $data = DB::name('Attachment')->where(['url'=>$url])->select()->toArray();
        $path = substr(ROOT_PATH, 0, -1);
        if(!empty($data))
        {
            // 删除数据库数据
            if(!DB::name('Attachment')->where(['id'=>array_column($data, 'id')])->delete())
            {
                return DataReturn(MyLang('delete_fail'), -1);
            }

            // 删除磁盘文件
            foreach($data as $v)
            {
                $file = $path.$v['url'];
                if(file_exists($file) && is_writable($file))
                {
                    \base\FileUtil::UnlinkFile($file);
                }
            }
        } else {
            // 不存在数据库，但是存在硬盘中则也删除
            foreach($url as $uv)
            {
                $file = $path.$uv;
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
     * @param   [array]           $params       [输入参数]
     */
    public static function AttachmentDiskFilesToDb($dir_path, $path_type = '', $params = [])
    {
        // 未指定类型值则使用路径值
        if(empty($path_type))
        {
            $path_type = $dir_path;
        }

        // 分类id
        $category_id = AttachmentCategoryService::AttachmentCategoryId($path_type, $params);

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
        $document_root = GetDocumentRoot();
        foreach($path_all as $type=>$path)
        {
            $path = $document_root . (substr($path, 0, 1) == "/" ? "":"/") . $path;
            $files = self::AttachmentDiskFilesList($path, $type, $category_id);
            if(!empty($files))
            {
                $count += count($files);
                foreach($files as $v)
                {
                    $temp = Db::name('Attachment')->where(['title'=>$v['title'], 'hash'=>$v['hash'], 'category_id'=>$category_id])->find();
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
        return DataReturn(MyLang('common_service.attachment.sync_file_to_db_tips', ['count'=>$count, 'success'=>$success, 'error'=>$error]), 0);
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-17T23:24:59+0800
     * @param    [string]        $path          [路径地址]
     * @param    [string]        $type          [允许的文件]
     * @param    [int]           $category_id   [附件分类id]
     * @param    [array]         &$files        [数据]
     * @return   [array]                        [数据]
     */
    public static function AttachmentDiskFilesList($path, $type, $category_id, &$files = [])
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
                    self::AttachmentDiskFilesList($temp_path, $type, $category_id, $files);
                } else {
                    $url = ResourcesService::AttachmentPathHandle(substr($temp_path, strlen($document_root)));
                    $title = substr($url, strripos($url, '/')+1);
                    $root_path = ROOT.'public'.$url;
                    $files[] = array(
                        'url'          => $url,
                        'original'     => $title,
                        'title'        => $title,
                        'type'         => $type,
                        'category_id'  => $category_id,
                        'size'         => file_exists($root_path) ? filesize($root_path) : 0,
                        'hash'         => file_exists($root_path) ? hash_file('sha256', $root_path, false) : '',
                        'ext'          => substr($title, strripos($title, '.')),
                    );
                }
            }
        }
        return $files;
    }
}
?>