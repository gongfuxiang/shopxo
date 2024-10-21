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
use app\module\LayoutModule;
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\StoreService;

/**
 * 页面设计服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-14
 * @desc    description
 */
class DesignService
{
    // 排除的文件后缀
    private static $exclude_ext = ['php'];

    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-16
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DesignList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据
        $data = Db::name('Design')->where($where)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::DesignListHandle($data, $params));
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function DesignListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // logo
                if(array_key_exists('logo', $v))
                {
                    $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
                }

                // 时间
                if(array_key_exists('upd_time', $v))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
                if(array_key_exists('add_time', $v))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
            }
        }
        return $data;
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignSave($params = [])
    {
        // 附件
        $attachment = ResourcesService::AttachmentParams($params, ['logo']);

        // 配置信息
        $config = empty($params['config']) ? '' : LayoutModule::ConfigSaveHandle($params['config']);

        // 数据
        $data = [
            'name'          => empty($params['name']) ? MyLang('common_service.design.create_name_default').''.date('mdHi') : $params['name'],
            'logo'          => $attachment['data']['logo'],
            'config'        => $config,
            'seo_title'     => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'  => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'      => empty($params['seo_desc']) ? '' : $params['seo_desc'],
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 1,
            'is_header'     => isset($params['is_header']) ? intval($params['is_header']) : 1,
            'is_footer'     => isset($params['is_footer']) ? intval($params['is_footer']) : 1,
        ];
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data_id = Db::name('Design')->insertGetId($data);
            if($data_id <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -1);
            }
        } else {
            $data_id = intval($params['id']);
            $data['upd_time'] = time();
            if(Db::name('Design')->where(['id'=>$data_id])->update($data) === false)
            {
                return DataReturn(MyLang('update_fail'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $data_id);
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignStatusUpdate($params = [])
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
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Design')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
           return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DesignDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('Design')->where(['id'=>$params['ids']])->delete())
        {
            // 删除数据库附件
            foreach($params['ids'] as $v)
            {
                AttachmentService::AttachmentPathTypeDelete(self::AttachmentPathTypeValue($v));
            }
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
    
    /**
     * 页面访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DesignAccessCountInc($params = [])
    {
        if(!empty($params['design_id']))
        {
            return Db::name('Design')->where(['id'=>intval($params['design_id'])])->inc('access_count')->update();
        }
        return false;
    }

    /**
     * 附件标识
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-23
     * @desc    description
     * @param   [int]          $data_id [数据 id]
     */
    public static function AttachmentPathTypeValue($data_id)
    {
        return 'design-'.$data_id;
    }

    /**
     * 同步到首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignSync($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $data = Db::name('Design')->where(['id'=>intval($params['id'])])->field('config')->find();
        if(empty($data))
        {
            return DataReturn(MyLang('data_no_exist_error_tips'), -1);
        }

        return LayoutService::LayoutConfigSave('home', $data);
    }

    /**
     * 下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignDownload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取数据
        $data = Db::name('Design')->where(['id'=>intval($params['id'])])->find();
        if(empty($data))
        {
            return DataReturn(MyLang('data_no_exist_error_tips'), -1);
        }

        // 目录不存在则创建
        $dir = ROOT.'runtime'.DS.'data'.DS.'design'.DS.GetNumberCode(6).$data['id'];
        \base\FileUtil::CreateDir($dir);

        // 临时数据id
        $data_id = GetNumberCode(6).time().GetNumberCode(6);

        // 解析下载数据
        $config = self::ConfigDownloadHandle($data_id, $data['config'], $dir);

        // 基础信息
        $base = [
            'data_id'   => $data_id,
            'name'      => $data['name'],
            'logo'      => self::FileSave($data_id, $data['logo'], 'images', $dir),
            'is_header' => $data['is_header'],
            'is_footer' => $data['is_footer'],
            'config'    => $config,
        ];
        if(@file_put_contents($dir.DS.'config.json', JsonFormat($base)) === false)
        {
            return DataReturn(MyLang('common_service.design.download_config_file_create_fail_tips'), -1);
        }

        // 生成压缩包
        $dir_zip = $dir.'.zip';
        $zip = new \base\ZipFolder();
        if(!$zip->zip($dir_zip, $dir))
        {
            return DataReturn(MyLang('form_generate_zip_message'), -2);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($dir);

        // 开始下载
        if(\base\FileUtil::DownloadFile($dir_zip, $data['name'].'_v'.date('YmdHis').'.zip', true))
        {
            return DataReturn(MyLang('download_success'), 0);
        } else {
            return DataReturn(MyLang('download_fail'), -100);
        }
    }

    /**
     * 配置数据下载处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     * @param   [int]            $data_id [数据id]
     * @param   [array]          $config  [配置数据]
     * @param   [string]         $dir     [存储目录]
     */
    public static function ConfigDownloadHandle($data_id, $config, $dir)
    {
        if(!empty($config))
        {
            // 非数组则解析
            if(!is_array($config))
            {
                $config = json_decode($config, true);
            }

            // 开始处理数据
            foreach($config as &$v)
            {
                // 配置处理
                if(!empty($v['config']))
                {
                    $v['config'] = self::ConfigAttachmentDownloadHandle($dir, $data_id, $v['config']);
                }

                // 是否存在下级数据
                if(empty($v['children']))
                {
                    continue;
                }
                foreach($v['children'] as &$vs)
                {
                    // 配置处理
                    if(!empty($vs['config']))
                    {
                        $vs['config'] = self::ConfigAttachmentDownloadHandle($dir, $data_id, $vs['config']);
                    }

                    // 是否存在下级数据
                    if(empty($vs['children']))
                    {
                        continue;
                    }
                    foreach($vs['children'] as &$vss)
                    {
                        if(empty($vss['config']))
                        {
                            continue;
                        }

                        // 配置处理
                        $vss['config'] = self::ConfigAttachmentDownloadHandle($dir, $data_id, $vss['config']);

                        // 根据模块处理
                        switch($vss['value'])
                        {
                            // 单图
                            case 'images' :
                                $vss['config']['content_images'] = self::FileSave($data_id, $vss['config']['content_images'], 'images', $dir);
                                break;

                            // 多图
                            case 'many-images' :
                                if(!empty($vss['config']['data_list']))
                                {
                                    foreach($vss['config']['data_list'] as &$miv)
                                    {
                                        $miv['images'] = self::FileSave($data_id, $miv['images'], 'images', $dir);
                                    }
                                }
                                break;

                            // 图文
                            case 'images-text' :
                                if(!empty($vss['config']['data_list']))
                                {
                                    foreach($vss['config']['data_list'] as &$itv)
                                    {
                                        $itv['images'] = self::FileSave($data_id, $itv['images'], 'images', $dir);
                                    }
                                }
                                break;

                            // 图片魔方
                            case 'images-magic-cube' :
                                if(!empty($vss['config']['data_list']))
                                {
                                    foreach($vss['config']['data_list'] as &$imcv)
                                    {
                                        $imcv['images'] = self::FileSave($data_id, $imcv['images'], 'images', $dir);
                                    }
                                }
                                break;

                            // 视频
                            case 'video' :
                                $vss['config']['content_video'] = self::FileSave($data_id, $vss['config']['content_video'], 'video', $dir);
                                break;
                        }
                    }
                }
            }
        }
        return $config;
    }

    /**
     * 配置信息附件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]       $dir     [存储路径]
     * @param   [int]          $data_id [数据id]
     * @param   [array]        $config  [配置信息]
     */
    public static function ConfigAttachmentDownloadHandle($dir, $data_id, $config)
    {
        // 背景图片
        if(!empty($config['style_background_images']))
        {
            $config['style_background_images'] = self::FileSave($data_id, $config['style_background_images'], 'images', $dir);
        }
        return $config;
    }

    /**
     * 文件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-18
     * @desc    description
     * @param   [int]             $data_id  [数据id]
     * @param   [string]          $file     [文件地址]
     * @param   [string]          $type     [类型]
     * @param   [string]          $dir      [存储路径]
     */
    public static function FileSave($data_id, $file, $type, $dir)
    {
        if(!empty($file))
        {
            $arr = explode('/', $file);
            $path = 'static'.DS.'upload'.DS.$type.DS.'design'.DS.$data_id.DS.date('Y/m/d');
            $filename = $path.DS.$arr[count($arr)-1];
            \base\FileUtil::CreateDir($dir.DS.$path);

            $status = false;
            if(substr($file, 0, 4) == 'http')
            {
                $temp = ResourcesService::AttachmentPathHandle($file);
                if(substr($temp, 0, 4) == 'http' || !file_exists(ROOT.'public'.$temp))
                {
                    // 远程下载
                    $temp_data = RequestGet($file);
                    if(!empty($temp_data))
                    {
                        file_put_contents($dir.DS.$filename, $temp_data);
                        $status = true;
                    }
                } else {
                    $file = $temp;
                }
            }
            if(!$status)
            {
                \base\FileUtil::CopyFile(ROOT.'public'.$file, $dir.DS.$filename);
            }
            return DS.$filename;
        }
        return '';
    }

    /**
     * 导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignUpload($params = [])
    {
        // 文件上传校验
        $error = FileUploadError('file');
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 文件格式化校验
        $type = ResourcesService::ZipExtTypeList();
        if(!in_array($_FILES['file']['type'], $type))
        {
            return DataReturn(MyLang('form_upload_zip_message'), -2);
        }

        // 上传处理
        return self::DesignUploadHandle($_FILES['file']['tmp_name'], $params);
    }
    
    /**
     * 导入处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [string]         $package_file [软件包地址]
     * @param   [array]          $params       [输入参数]
     */
    public static function DesignUploadHandle($package_file, $params = [])
    {
        // 应用upload目录权限校验
        $app_upload_dir = ROOT.'public'.DS.'static'.DS.'upload';
        if(!is_writable($app_upload_dir))
        {
            return DataReturn(MyLang('common_service.design.upload_dis_no_power_tips').'['.$app_upload_dir.']', -3);
        }

        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource !== true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }

        // 配置信息
        $config = [];
        $data_id = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            $file = $zip->getNameIndex($i);
            if(stripos($file, 'config.json') !== false)
            {
                $stream = $zip->getStream($file);
                if($stream === false)
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.design.upload_config_file_get_fail_tips'), -1);
                }

                // 排除后缀文件
                $pos = strripos($file, '.');
                if($pos !== false)
                {
                    $info = pathinfo($file);
                    if(isset($info['extension']) && in_array(strtolower($info['extension']), self::$exclude_ext))
                    {
                        continue;
                    }
                }

                // 获取配置信息并解析
                $file_content = stream_get_contents($stream);
                $config = empty($file_content) ? [] : json_decode($file_content, true);
                if(empty($config) || empty($config['data_id']) || empty($config['name']))
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.design.upload_config_file_error_tips'), -1);
                }

                // 数据添加
                $data = [
                    'name'      => $config['name'],
                    'is_header' => (isset($config['is_header']) && $config['is_header'] == 1) ? 1 : 0,
                    'is_footer' => (isset($config['is_footer']) && $config['is_footer'] == 1) ? 1 : 0,
                    'add_time'  => time(),
                ];
                $data_id = Db::name('Design')->insertGetId($data);
                if($data_id <= 0)
                {
                    $zip->close();
                    return DataReturn(MyLang('insert_fail'), -1);
                }
                // 更新配置信息和logo
                if(!empty($config['config']) || !empty($config['logo']))
                {
                    $upd_data = [
                        'logo'      => empty($config['logo']) ? '' : str_replace($config['data_id'], $data_id, $config['logo']),
                        'config'    => empty($config['config']) ? '' : str_replace($config['data_id'], $data_id, json_encode($config['config'], JSON_UNESCAPED_UNICODE)),
                        'upd_time'  => time(),
                    ];
                    if(!Db::name('Design')->where(['id'=>$data_id])->update($upd_data))
                    {
                        $zip->close();
                        return DataReturn(MyLang('update_fail'), -1);
                    }
                }
                break;
            }
        }
        if(empty($config) || empty($data_id))
        {
            return DataReturn(MyLang('common_service.design.upload_config_file_handle_fail_tips'), -1);
        }

        // 文件处理
        $success = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && strpos($file, '__') === false)
            {
                // 去除第一个目录（为原始数据的id）
                $temp_file = substr($file, strpos($file, '/')+1);
                if(empty($temp_file) || in_array($temp_file, ['static/', 'static/upload/', 'config.json']))
                {
                    continue;
                }

                // 截取文件路径
                $new_file = ROOT.'public'.DS.str_replace($config['data_id'], $data_id, $temp_file);
                $file_path = substr($new_file, 0, strrpos($new_file, '/'));

                // 路径不存在则创建
                \base\FileUtil::CreateDir($file_path);

                // 如果不是目录则写入文件
                if(!is_dir($new_file))
                {
                    // 读取这个文件
                    $stream = $zip->getStream($file);
                    if($stream !== false)
                    {
                        $file_content = stream_get_contents($stream);
                        if($file_content !== false)
                        {
                            if(file_put_contents($new_file, $file_content))
                            {
                                $success++;
                            }
                        }
                        fclose($stream);
                    }
                }
            }
        }
        // 关闭zip
        $zip->close();

        // 附件同步到数据库
        AttachmentService::AttachmentDiskFilesToDb('design'.DS.$data_id, self::AttachmentPathTypeValue($data_id));

        if($success > 0)
        {
            return DataReturn(MyLang('import_success'), 0, $data_id);
        }
        return DataReturn(MyLang('common_service.design.upload_invalid_packet_tips'), -1);
    }

    /**
     * 模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignMarket($params = [])
    {
        $params['type'] = 'design';
        return StoreService::PackageDataList($params);
    }
}
?>