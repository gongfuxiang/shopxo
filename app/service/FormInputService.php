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
use app\module\FormInputModule;
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\StoreService;
use app\service\AppMiniUserService;
use app\service\ConfigService;

/**
 * form表单服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-14
 * @desc    description
 */
class FormInputService
{
    // 排除的文件后缀
    private static $exclude_ext = ['php'];

    /**
     * forminput数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-06
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function FormInputData($params = [])
    {
        $data = null;
        $id = empty($params['forminput_id']) ? (empty($params['id']) ? 0 : intval($params['id'])) : intval($params['forminput_id']);
        if(!empty($id))
        {
            $ret = self::FormInputList([
                'n'      => 1,
                'field'  => 'id,config',
                'where'  => [
                    ['is_enable', '=', 1],
                    ['id', '=', $id],
                ],
                'is_config_handle'  => 1,
            ]);
            $data = (empty($ret['data']) || empty($ret['data'][0])) ? null : $ret['data'][0];
        }
        return $data;
    }

    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-16
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function FormInputList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据
        $data = Db::name('FormInput')->where($where)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::FormInputListHandle($data, $params));
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
    public static function FormInputListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            $is_config_handle = isset($params['is_config_handle']) && $params['is_config_handle'] == 1;
            foreach($data as &$v)
            {
                // logo
                if(array_key_exists('logo', $v))
                {
                    $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
                }

                // 配置处理
                if($is_config_handle && array_key_exists('config', $v))
                {
                    $v['config'] = FormInputModule::ConfigViewHandle($v['config'], $params);
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
    public static function FormInputSave($params = [])
    {
        // 附件
        $attachment = ResourcesService::AttachmentParams($params, ['logo']);

        // 配置信息
        $config = empty($params['config']) ? '' : FormInputModule::ConfigSaveHandle($params['config']);

        // 数据
        $data = [
            'logo'       => $attachment['data']['logo'],
            'name'       => empty($params['name']) ? MyLang('common_service.forminput.create_name_default').''.date('mdHi') : $params['name'],
            'describe'   => empty($params['describe']) ? '' : $params['describe'],
            'config'     => $config,
            'is_enable'  => isset($params['is_enable']) ? intval($params['is_enable']) : 1,
        ];
        $info = empty($params['id']) ? [] : Db::name('FormInput')->where(['id'=>intval($params['id'])])->find();
        if(empty($info) || empty($info['md5_key']))
        {
            $data['md5_key'] = md5(time().GetNumberCode(10).RandomString(10));
        } else {
            $data['md5_key'] = $info['md5_key'];
        }
        if(empty($info))
        {
            $data['add_time'] = time();
            $data_id = Db::name('FormInput')->insertGetId($data);
            if($data_id <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -1);
            }
        } else {
            // 安全验证
            $ret = self::FormInputLegalCheck($data['md5_key'], $data);
            if($ret['code'] != 0)
            {
                return $ret;
            }
            // 更新数据
            $data_id = intval($params['id']);
            $data['upd_time'] = time();
            if(Db::name('FormInput')->where(['id'=>$data_id])->update($data) === false)
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
    public static function FormInputStatusUpdate($params = [])
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
        if(Db::name('FormInput')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
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
    public static function FormInputDelete($params = [])
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
        if(Db::name('FormInput')->where(['id'=>$params['ids']])->delete())
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
    public static function FormInputAccessCountInc($params = [])
    {
        if(!empty($params['forminput_id']))
        {
            return Db::name('FormInput')->where(['id'=>intval($params['forminput_id'])])->inc('access_count')->update();
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
        return 'forminput-'.$data_id;
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
    public static function FormInputDownload($params = [])
    {
        // 生成下载包
        $package = self::FormInputDownloadHandle($params);
        if($package['code'] != 0)
        {
            return $package;
        }

        // 开始下载
        if(\base\FileUtil::DownloadFile($package['data']['file'], $package['data']['data']['name'].'_v'.date('YmdHis').'.zip', true))
        {
            return DataReturn(MyLang('download_success'), 0);
        }
        return DataReturn(MyLang('download_fail'), -100);
    }

    /**
     * 上传到应用商店
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputStoreUpload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'describe',
                'error_msg'         => MyLang('common_service.forminput.form_item_desc_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_version',
                'error_msg'         => MyLang('common_service.forminput.form_item_apply_version_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 生成下载包
        $package = self::FormInputDownloadHandle($params);
        if($package['code'] != 0)
        {
            return $package;
        }

        // 帐号信息
        $user = StoreService::AccountsData();
        if(empty($user['accounts']) || empty($user['password']))
        {
            return DataReturn(MyLang('store_account_not_bind_tips'), -300);
        }

        // 上传到远程
        $params['md5_key'] = $package['data']['md5_key'];
        $params['file'] = new \CURLFile($package['data']['file']);
        $ret = StoreService::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_forminput_upload_url'), $params, 2);
        // 是个与否都删除本地生成的zip包
        @unlink($package['data']['file']);
        // 返回数据
        return $ret;
    }

    /**
     * 下载处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputDownloadHandle($params = [])
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
        $data = Db::name('FormInput')->where(['id'=>intval($params['id'])])->find();
        if(empty($data))
        {
            return DataReturn(MyLang('data_no_exist_error_tips'), -1);
        }

        // 安全验证
        $ret = self::FormInputLegalCheck($data['md5_key'], $data);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 目录不存在则创建
        $dir = ROOT.'runtime'.DS.'data'.DS.'forminput'.DS.GetNumberCode(6).$data['id'];
        \base\FileUtil::CreateDir($dir);

        // 临时数据id
        $data_id = GetNumberCode(6).time().GetNumberCode(6);

        // 解析下载数据
        $data_config = self::ConfigDownloadHandle($data_id, $data['config'], $dir);

        // 基础信息
        $config = [
            'data_id'   => $data_id,
            'name'      => $data['name'],
            'md5_key'   => $data['md5_key'],
            'logo'      => self::FileSave($data_id, $data['logo'], 'images', $dir),
            'config'    => $data_config,
        ];
        if(@file_put_contents($dir.DS.'config.json', JsonFormat($config)) === false)
        {
            return DataReturn(MyLang('common_service.forminput.download_config_file_create_fail_tips'), -1);
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

        // 返回数据
        return DataReturn('success', 0, [
            'file'     => $dir.'.zip',
            'config'   => $config,
            'data'     => $data,
            'md5_key'  => $data['md5_key'],
        ]);
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

            // 附件处理
            $config = self::ConfigDownloadAnnexHandle($config, $data_id, $dir);
        }
        return $config;
    }

    /**
     * 配置数据下载附件地址处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [array]          $data [配置数据]
     */
    public static function ConfigDownloadAnnexHandle($data, $data_id, $dir)
    {
        if(!empty($data) && is_array($data))
        {
            $system_first = '/static/app/tabbar/';
            foreach($data as $k=>$v)
            {
                if(!empty($v) && is_array($v))
                {
                    // 附件地址
                    if(!empty($v[0]) && isset($v[0]['url']))
                    {
                        if(!empty($v[0]['url']))
                        {
                            // 排除底部菜单系统默认图标
                            if(stripos($v[0]['url'], $system_first) === false)
                            {
                                $data[$k][0]['url'] = self::FileSave($data_id, $v[0]['url'], ResourcesService::AttachmentTypeMatch($v[0]['url']), $dir);
                            }
                        }

                    // 富文本
                    } elseif(!empty($v['content']) && !empty($v['content']['html']))
                    {
                        $annex = ResourcesService::RichTextMatchContentAttachment($data[$k]['content']['html']);
                        if(!empty($annex) && is_array($annex))
                        {
                            foreach($annex as $rtav)
                            {
                                $data[$k]['content']['html'] = str_replace($rtav, self::FileSave($data_id, $rtav, ResourcesService::AttachmentTypeMatch($rtav), $dir), $data[$k]['content']['html']);
                            }
                        }

                    // 数组继续处理
                    } else {
                        $data[$k] = self::ConfigDownloadAnnexHandle($v, $data_id, $dir);
                    }
                }
            }
        }
        return $data;
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
            static $forminput_file_save_data = [];
            $md5 = md5($file);
            if(!array_key_exists($md5, $forminput_file_save_data))
            {
                $arr = explode('/', $file);
                $path = 'static'.DS.'upload'.DS.$type.DS.'forminput'.DS.$data_id.DS.date('Y/m/d');
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
                $forminput_file_save_data[$md5] = DS.$filename;
            }
            return $forminput_file_save_data[$md5];
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
    public static function FormInputUpload($params = [])
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
        return self::FormInputUploadHandle($_FILES['file']['tmp_name'], $params);
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
    public static function FormInputUploadHandle($package_file, $params = [])
    {
        // 应用upload目录权限校验
        $app_upload_dir = ROOT.'public'.DS.'static'.DS.'upload';
        if(!is_writable($app_upload_dir))
        {
            return DataReturn(MyLang('common_service.forminput.upload_dir_no_power_tips').'['.$app_upload_dir.']', -3);
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
        $data_id = empty($params['data_id']) ? 0 : intval($params['data_id']);
        $name = '';
        for($i=0; $i<$zip->numFiles; $i++)
        {
            $file = $zip->getNameIndex($i);
            if(stripos($file, 'config.json') !== false)
            {
                // 打开文件资源
                $stream = $zip->getStream($file);
                if($stream === false)
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.forminput.upload_config_file_get_fail_tips'), -1);
                }

                // 获取配置信息并解析
                $file_content = stream_get_contents($stream);
                $config = empty($file_content) ? [] : json_decode($file_content, true);
                if(empty($config) || empty($config['data_id']) || empty($config['name']) || empty($config['md5_key']))
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.forminput.upload_config_file_error_tips'), -1);
                }

                // 安全验证
                $ret = self::FormInputLegalCheck($config['md5_key'], $config);
                if($ret['code'] != 0)
                {
                    $zip->close();
                    return $ret;
                }

                // 数据不存在则添加
                if(empty($data_id))
                {
                    $data = [
                        'name'      => $config['name'],
                        'md5_key'   => $config['md5_key'],
                        'describe'  => empty($config['describe']) ? '' : $config['describe'],
                        'add_time'  => time(),
                    ];
                    $data_id = Db::name('FormInput')->insertGetId($data);
                    if($data_id <= 0)
                    {
                        $zip->close();
                        return DataReturn(MyLang('insert_fail'), -1);
                    }
                }
                $name = $config['name'];

                // 更新配置信息
                $upd_data = [
                    'md5_key'   => $config['md5_key'],
                    'config'    => empty($config['config']) ? '' : str_replace($config['data_id'], $data_id, json_encode($config['config'], JSON_UNESCAPED_UNICODE)),
                    'upd_time'  => time(),
                ];
                if(!empty($config['logo']))
                {
                    $upd_data['logo'] = empty($config['logo']) ? '' : str_replace($config['data_id'], $data_id, $config['logo']);
                }
                if(Db::name('FormInput')->where(['id'=>$data_id])->update($upd_data) === false)
                {
                    $zip->close();
                    return DataReturn(MyLang('update_fail'), -1);
                }
                break;
            }
        }
        if(empty($config) || empty($data_id))
        {
            return DataReturn(MyLang('common_service.forminput.upload_config_file_handle_fail_tips'), -1);
        }

        // 文件处理
        $error = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && strpos($file, '__') === false)
            {
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
                            if(!file_put_contents($new_file, $file_content))
                            {
                                $error++;
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
        AttachmentService::AttachmentDiskFilesToDb('forminput'.DS.$data_id, self::AttachmentPathTypeValue($data_id), ['name'=>$name]);

        if($error > 0)
        {
            return DataReturn(MyLang('common_service.forminput.upload_invalid_packet_tips'), -1);
        }
        return DataReturn(MyLang('import_success'), 0, $data_id);
    }

    /**
     * 安全判断
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-05-26
     * @desc    description
     * @param   [string]         $md5_key  [唯一标识]
     * @param   [array]          $data     [forminput数据]
     */
    public static function FormInputLegalCheck($md5_key, $data = [])
    {
        if(RequestModule() == 'admin')
        {
            $key = 'forminput_legal_check_'.$md5_key;
            $ret = MyCache($key);
            if(empty($ret))
            {
                if(!is_array($data) && !empty($data))
                {
                    $data = json_decode($data, true);
                }
                if(!empty($data) && is_array($data) && isset($data['config']))
                {
                    unset($data['config']);
                }
                $check_params = [
                    'type'      => 'forminput',
                    'config'    => $data,
                    'plugins'   => $md5_key,
                ];
                $ret = StoreService::PluginsLegalCheck($check_params);
                MyCache($key, $ret, 3600);
            }
            if(!in_array($ret['code'], [0, -9999]))
            {
                return $ret;
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 预览数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-05
     * @desc    description
     * @param   [array]           $data [forminput数据]
     */
    public static function FormInputPreviewData($data = [])
    {
        // 数据
        if(empty($data))
        {
            return DataReturn(MyLang('no_data'), -1);
        }

        // pc地址
        $web_url = MyUrl('index/forminput/index', ['id'=>$data['id']]);

        // h5地址
        $h5_url = MyC('common_app_h5_url');

        // 生成各平台二维码
        $qrcode = [];
        $platform = MyConst('common_platform_type');
        if(!empty($platform) && is_array($platform))
        {
            // 自定义路径和名称
            $time_dir = date('Y/m/d', is_numeric($data['add_time']) ? $data['add_time'] : strtotime($data['add_time']));
            $filename = $data['id'].'.png';

            // 地址信息
            $page = 'pages/form-input/form-input';
            $query = 'id='.$data['id'];
            // h5地址拼接
            if(!empty($h5_url))
            {
                $h5_url .= $page.'?'.$query;
            }
            foreach($platform as $v)
            {
                // 存储信息
                $path = 'download'.DS.'forminput'.DS.'qrcode'.DS.$v['value'].DS.$time_dir.DS;
                // 二维码处理参数
                $dir_params = [
                    'path'      => DS.$path,
                    'filename'  => $filename,
                    'dir'       => ROOT.'public'.DS.$path.$filename,
                ];
                $status = false;
                if(!file_exists($dir_params['dir']))
                {
                    // 根据平台处理
                    switch($v['value'])
                    {
                        // pc
                        case 'pc' :
                            $ret = (new \base\Qrcode())->Create(array_merge($dir_params, ['content'=>$web_url]));
                            if($ret['code'] == 0)
                            {
                                $status = true;
                            }
                            break;

                        // h5
                        case 'h5' :
                            if(!empty($h5_url))
                            {
                                $ret = (new \base\Qrcode())->Create(array_merge($dir_params, ['content'=>$h5_url]));
                                if($ret['code'] == 0)
                                {
                                    $status = true;
                                }
                            }
                            break;

                        // 微信
                        case 'weixin' :
                            $appid = AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appid');
                            $appsecret = AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appsecret');
                            if(!empty($appid) && !empty($appsecret))
                            {
                                $request_params = [
                                    'page'  => $page,
                                    'scene' => $query,
                                    'width' => 300,
                                ];
                                $ret = (new \base\Wechat($appid, $appsecret))->MiniQrCodeCreate($request_params);
                                if($ret['code'] == 0)
                                {
                                    if(\base\FileUtil::CreateDir(ROOT.'public'.DS.$path))
                                    {
                                        if(@file_put_contents($dir_params['dir'], $ret['data']) !== false)
                                        {
                                            $status = true;
                                        }
                                    }
                                }
                            }
                            break;

                        // 支付宝小程序
                        case 'alipay' :
                            $appid = AppMiniUserService::AppMiniConfig('common_app_mini_alipay_appid');
                            if(!empty($appid))
                            {
                                $request_params = [
                                    'appid' => $appid,
                                    'page'  => $page,
                                    'scene' => $query,
                                    'width' => 300,
                                ];
                                $ret = (new \base\Alipay())->MiniQrCodeCreate($request_params);
                                if($ret['code'] == 0)
                                {
                                    if(\base\FileUtil::CreateDir(ROOT.'public'.DS.$path))
                                    {
                                        if(@file_put_contents($dir_params['dir'], RequestGet($ret['data'])) !== false)
                                        {
                                            $status = true;
                                        }
                                    }
                                }
                            }
                            break;

                        // 头条小程序
                        case 'toutiao' :
                            $config = [
                                'appid'   => AppMiniUserService::AppMiniConfig('common_app_mini_toutiao_appid'),
                                'secret'  => AppMiniUserService::AppMiniConfig('common_app_mini_toutiao_appsecret'),
                            ];
                            if(!empty($config['appid']) && !empty($config['secret']))
                            {
                                $request_params = [
                                    'page'  => $page,
                                    'scene' => $query,
                                    'width' => 300,
                                ];
                                $ret = (new \base\Toutiao($config))->MiniQrCodeCreate($request_params);
                                if($ret['code'] == 0)
                                {
                                    if(\base\FileUtil::CreateDir(ROOT.'public'.DS.$path))
                                    {
                                        if(@file_put_contents($dir_params['dir'], $ret['data']) !== false)
                                        {
                                            $status = true;
                                        }
                                    }
                                }
                            }
                            break;

                        // 百度小程序
                        case 'baidu' :
                            $config = [
                                'appid'   => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_appid'),
                                'key'     => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_appkey'),
                                'secret'  => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_appsecret'),
                            ];
                            if(!empty($config['appid']) && !empty($config['key']) && !empty($config['secret']))
                            {
                                $request_params = [
                                    'page'  => $page,
                                    'scene' => $query,
                                    'width' => 300,
                                ];
                                $ret = (new \base\Baidu($config))->MiniQrCodeCreate($request_params);
                                if($ret['code'] == 0)
                                {
                                    if(\base\FileUtil::CreateDir(ROOT.'public'.DS.$path))
                                    {
                                        if(@file_put_contents($dir_params['dir'], $ret['data']) !== false)
                                        {
                                            $status = true;
                                        }
                                    }
                                }
                            }
                            break;

                        // 快手小程序
                        case 'kuaishou' :
                            $appid = AppMiniUserService::AppMiniConfig('common_app_mini_kuaishou_appid');
                            if(!empty($appid))
                            {
                                $url = 'kwai://miniapp?appId='.$appid.'&KSMP_source=011012&KSMP_internal_source=011012&path='.urlencode($page.'?'.$query);
                                $ret = (new \base\Qrcode())->Create(array_merge($dir_params, ['content'=>$url]));
                                if($ret['code'] == 0)
                                {
                                    $status = true;
                                }
                            }
                            break;
                    }
                } else {
                    $status = true;
                }
                if($status)
                {
                    if(($v['value'] == 'h5' && !empty($h5_url)) || $v['value'] != 'h5')
                    {
                        $qrcode[] = [
                            'name'    => $v['name'],
                            'type'    => $v['value'],
                            'url'     => ($v['value'] == 'h5') ? $h5_url : (($v['value'] == 'pc') ? $web_url : ''),
                            'qrcode'  => ResourcesService::AttachmentPathViewHandle($dir_params['path'].$dir_params['filename']),
                        ];
                    }
                }
            }
        }
        return DataReturn('success', 0, [
            'qrcode'   => $qrcode,
            'h5_url'   => $h5_url,
            'web_url'  => $web_url,
        ]);
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
    public static function FormInputMarket($params = [])
    {
        $params['type'] = 'forminput';
        return StoreService::PackageDataList($params);
    }

    /**
     * 邮箱、短信验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function VerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => MyLang('accounts_empty_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => ['sms', 'email'],
                'error_msg'         => '类型范围值有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'forminput_id',
                'error_msg'         => 'form表单id为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'forminput_item_id',
                'error_msg'         => 'form表单数据项id为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 表单数据
        $form_input = self::FormInputData($params);
        if(empty($form_input))
        {
            return DataReturn('无表单数据', -1);
        }
        if(empty($form_input['config']))
        {
            return DataReturn('表单无配置数据', -1);
        }
        $diy_data = array_column($form_input['config']['diy_data'], null, 'id');
        if(empty($diy_data) || empty($diy_data[$params['forminput_item_id']]))
        {
            return DataReturn('表单数据项有误', -1);
        }
        $forminput_item = $diy_data[$params['forminput_item_id']];
        if(empty($forminput_item['com_data']) || empty($forminput_item['com_data']['is_sms_verification']))
        {
            return DataReturn('表单数据项未开启验证('.$forminput_item['name'].')', -1);
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix'    => 'forminput',
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];

        // 是否开启图片验证码
        if(isset($forminput_item['com_data']['is_img_sms_verification']) && $forminput_item['com_data']['is_img_sms_verification'] == 1)
        {
            $verify = self::IsImaVerify($params, $verify_params);
            if($verify['code'] != 0)
            {
                return $verify;
            }
        }

        // 账户校验
        $ret = self::AccountsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'forminput_'.md5($params['accounts']);

        // 发送验证码
        $code = GetNumberCode(4);
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, ConfigService::SmsTemplateValue('common_sms_currency_template'));
                break;
            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('common_email_currency_template'),
                    'title'     =>  MyC('home_site_name').' - 表单数据填写验证',
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn(MyLang('verify_code_not_support_send_error_tips'), -2);
        }
        if($status)
        {
            // 清除验证码
            if(!empty($verify) && isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }
            return DataReturn(MyLang('send_success'), 0);
        }
        return DataReturn(MyLang('send_fail').'['.$obj->error.']', -100);
    }

    /**
     * 账户校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    public static function AccountsCheck($params = [])
    {
        switch($params['type'])
        {
            // 手机
            case 'sms' :
                // 手机号码格式
                if(!CheckMobile($params['accounts']))
                {
                    return DataReturn(MyLang('mobile_format_error_tips'), -2);
                }
                break;
            // 邮箱
            case 'email' :
                // 电子邮箱格式
                if(!CheckEmail($params['accounts']))
                {
                     return DataReturn(MyLang('email_format_error_tips'), -2);
                }
                break;
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 图片验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-22T15:48:31+0800
     * @param    [array]    $params         [输入参数]
     * @param    [array]    $verify_params  [配置参数]
     * @return   [object]                   [图片验证码类对象]
     */
    public static function IsImaVerify($params, $verify_params)
    {
        if(empty($params['verify']))
        {
            return DataReturn(MyLang('verify_images_empty_tips'), -10);
        }
        $verify = new \base\Verify($verify_params);
        if(!$verify->CheckExpire())
        {
            return DataReturn(MyLang('verify_code_expire_tips'), -11);
        }
        if(!$verify->CheckCorrect($params['verify']))
        {
            return DataReturn(MyLang('verify_code_error_tips'), -12);
        }
        return DataReturn(MyLang('operate_success'), 0, $verify);
    }
}
?>