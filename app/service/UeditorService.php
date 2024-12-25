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

use app\service\SystemBaseService;
use app\service\AttachmentService;
use app\service\AttachmentCategoryService;

/**
 * 百度编辑器附件服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UeditorService
{
    public static $current_action;
    public static $current_config;
    public static $params;
    public static $path_type;
    public static $category_id;

    /**
     * 请求入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Run($params = [])
    {
        // 配置信息
        self::$params = $params;
        self::$current_config = MyConfig('ueditor');
        self::$current_action = isset($params['action']) ? $params['action'] : '';
        self::$path_type = isset($params['path_type']) ? $params['path_type'] : PathToParams('path_type');
        if(empty(self::$path_type))
        {
            self::$path_type = self::CategoryIdPathType();
        }
        self::$category_id = self::PathTypeCategoryId();

        // action
        $ret = DataReturn(MyLang('common_service.ueditor.request_action_error_tips'), -1);
        switch(self::$current_action)
        {
            // 配置信息
            case 'config':
                $ret = DataReturn('success', 0, self::$current_config);
                break;

            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                $ret = self::ActionUpload();
                break;

            /* 列出图片 */
            case 'listimage':
            /* 列出文件 */
            case 'listfile':
            /* 列出视频 */
            case 'listvideo':
                $ret = self::ActionList();
                break;

            /* 抓取远程图片 */
            case 'catchimage':
            /* 抓取远程视频 */
            case 'catchvideo':
            /* 抓取远程文件 */
            case 'catchfile':
                $ret = self::ActionCrawler();
                break;

            /* 删除文件 */
            case 'deletefile':
                $ret = self::DeleteFile();
                break;

            /* 分类列表 */
            case 'categorylist':
                $ret = self::CategoryList();
                break;

            /* 扫码数据 */
            case 'scandata':
                $ret = self::ScanData();
                break;
        }
        return $ret;
    }

    /**
     * 扫码key是否存在
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-10
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ScanKeyIsExist($params = [])
    {
        // 请求参数
        if(empty($params['key']))
        {
            return DataReturn(MyLang('common_service.ueditor.scan_key_empty_tips'), -1);
        }

        // 是否扫码来源-扫码数据
        $key = self::ScanCacheKey($params['key']);
        $data = MyCache($key);
        if($data === null)
        {
            return DataReturn(MyLang('common_service.ueditor.scan_key_not_exist_tips'), -1);
        }
        return DataReturn('success', 0);
    }

    /**
     * 扫码数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-22
     * @desc    description
     */
    public static function ScanData()
    {
        // 请求参数
        if(empty(self::$params['key']))
        {
            return DataReturn(MyLang('common_service.ueditor.scan_key_empty_tips'), -1);
        }

        // 是否扫码来源-扫码数据
        $key = self::ScanCacheKey(self::$params['key']);
        $data = MyCache($key);
        if($data === null)
        {
            MyCache($key, [], 7200);
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 扫码上传缓存key
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-22
     * @desc    description
     * @param   [string]          $key [标识key]
     */
    public static function ScanCacheKey($key)
    {
        return 'cache_scanupload_data_'.$key;
    }

    /**
     * 分类id获取路径
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-17
     * @desc    description
     */
    public static function CategoryIdPathType()
    {
        $path = '';
        if(!empty(self::$params['category_id']))
        {
            $path = AttachmentCategoryService::AttachmentPathType(self::$params['category_id']);
        }
        return empty($path) ? 'other' : $path;
    }

    /**
     * 路径分类id
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-17
     * @desc    description
     */
    public static function PathTypeCategoryId()
    {
        return AttachmentCategoryService::AttachmentCategoryId(empty(self::$params['category_id']) ? self::$path_type : self::$params['category_id']);
    }

    /**
     * 分类列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-10
     * @desc    description
     */
    public static function CategoryList()
    {
        return DataReturn('success', 0, AttachmentCategoryService::AttachmentCategoryAll(array_merge(self::$params, ['is_enable'=>1])));
    }

    /**
     * 文件删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-10
     * @desc    description
     */
    public static function DeleteFile()
    {
        $ret = AttachmentService::AttachmentDelete(input());
        if($ret['code'] == 0 && !empty(self::$params['id']))
        {
            // 是否扫码来源-删除操作
            if(!empty(self::$params['upload_source']) && self::$params['upload_source'] == 'scanupload' && !empty(self::$params['key']))
            {
                $cache_key = self::ScanCacheKey(self::$params['key']);
                $cache_data = MyCache($cache_key);
                if(!empty($cache_data))
                {
                    $index = array_search(self::$params['id'], array_column($cache_data, 'id'));
                    if($index !== false)
                    {
                        array_splice($cache_data, $index, 1);
                        MyCache($cache_key, $cache_data, 7200);
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * 上传配置
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-17T22:45:06+0800
     */
    public static function ActionUpload()
    {
        $attachment_type = "file";
        switch(htmlspecialchars(self::$current_action))
        {
            case 'uploadimage':
                $temp_config = [
                    "pathFormat" => self::$current_config['imagePathFormat'],
                    "maxSize" => self::$current_config['imageMaxSize'],
                    "allowFiles" => self::$current_config['imageAllowFiles']
                ];
                $field_name = self::$current_config['imageFieldName'];
                $attachment_type = "image";
                break;

            case 'uploadscrawl':
                $temp_config = [
                    "pathFormat" => self::$current_config['scrawlPathFormat'],
                    "maxSize" => self::$current_config['scrawlMaxSize'],
                    "allowFiles" => self::$current_config['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                ];
                $field_name = self::$current_config['scrawlFieldName'];
                $attachment_type = "scrawl";
                break;

            case 'uploadvideo':
                $temp_config = [
                    "pathFormat" => self::$current_config['videoPathFormat'],
                    "maxSize" => self::$current_config['videoMaxSize'],
                    "allowFiles" => self::$current_config['videoAllowFiles']
                ];
                $field_name = self::$current_config['videoFieldName'];
                $attachment_type = "video";
                break;

            case 'uploadfile':
            default:
                $temp_config = [
                    "pathFormat" => self::$current_config['filePathFormat'],
                    "maxSize" => self::$current_config['fileMaxSize'],
                    "allowFiles" => self::$current_config['fileAllowFiles']
                ];
                $field_name = self::$current_config['fileFieldName'];
                $attachment_type = "file";
        }

        // 上传路径匹配
        // 路径非其他，并且配置路径中包含其他则使用新的路径替换
        if(self::$path_type != 'other' && stripos($temp_config['pathFormat'], '/other/{yyyy}') !== false)
        {
            $temp_config['pathFormat'] = str_replace('/other/{yyyy}', '/'.str_replace('-', '/', self::$path_type).'/{yyyy}', $temp_config['pathFormat']);
        }

        /* 生成上传实例对象并完成上传 */
        $up = new \base\Uploader($field_name, $temp_config, $attachment_type);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "path" => "",           //绝对地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         *     "hash" => "",           //sha256值
         * )
         */
        $data = $up->getFileInfo();
        if(isset($data['state']) && $data['state'] == 'SUCCESS')
        {
            if($attachment_type == 'scrawl')
            {
                $attachment_type = 'image';
            }
            $data['type'] = $attachment_type;
            $data['category_id'] = self::$category_id;
            $ret = AttachmentService::AttachmentAdd($data);
            if($ret['code'] == 0)
            {
                // 是否扫码来源-记录操作
                if(!empty(self::$params['upload_source']) && self::$params['upload_source'] == 'scanupload' && !empty(self::$params['key']))
                {
                    $cache_key = self::ScanCacheKey(self::$params['key']);
                    $cache_data = MyCache($cache_key);
                    if(empty($cache_data))
                    {
                        $cache_data = [$ret['data']];
                    } else {
                        array_push($cache_data, $ret['data']);
                    }
                    MyCache($cache_key, $cache_data, 7200);
                }
            }
            return $ret;
        }
        return DataReturn(isset($data['state']) ? $data['state'] : MyLang('upload_fail'), -1);
    }

    /**
     * 文件列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-17T22:55:16+0800
     */
    public static function ActionList()
    {
        /* 判断类型 */
        switch(self::$current_action)
        {
            /* 列出视频 */
            case 'listvideo':
                $allow_files = self::$current_config['videoManagerAllowFiles'];
                $list_size = self::$current_config['videoManagerListSize'];
                $path = self::$current_config['videoManagerListPath'];
                break;
            /* 列出文件 */
            case 'listfile':
                $allow_files = self::$current_config['fileManagerAllowFiles'];
                $list_size = self::$current_config['fileManagerListSize'];
                $path = self::$current_config['fileManagerListPath'];
                break;

            /* 列出图片 */
            case 'listimage':
            default:
                $allow_files = self::$current_config['imageManagerAllowFiles'];
                $list_size = self::$current_config['imageManagerListSize'];
                $path = self::$current_config['imageManagerListPath'];
        }
        $allow_files = substr(str_replace(".", "|", join("", $allow_files)), 1);

        /* 获取参数 */
        $size = isset(self::$params['size']) ? intval(self::$params['size']) : $list_size;
        $start = isset(self::$params['start']) ? intval(self::$params['start']) : 0;
        $end = $start + $size;

        // 参数
        $params = [
            'm'         => $start,
            'n'         => $size,
            'where'     => [
                ['type', '=', substr(self::$current_action, 4)],
            ],
        ];

        // 分类id
        if(!empty(self::$category_id))
        {
            $params['where'][] = ['category_id', '=', self::$category_id];
        }

        // 搜索关键字
        if(!empty(self::$params['keywords']))
        {
            $params['where'][] = ['original', 'like', '%'.self::$params['keywords'].'%'];
        }

        // 总数
        $total = AttachmentService::AttachmentTotal($params['where']);

        // 返回数据格式
        $result = [
            'size'        => $size,
            'start'       => $start,
            'total'       => $total,
            'page'        => intval($start/$size)+1,
            'page_total'  => ceil($total/$size),
            'list'        => [],
        ];

        // 获取数据
        if($total > 0)
        {
            $ret = AttachmentService::AttachmentList($params);
            if(!empty($ret['data']))
            {
                $result['list'] = $ret['data'];
                return DataReturn('success', 0, $result);
            }
        }
        return DataReturn(MyLang('no_data'), -1, $result);
    }

    /**
     * 抓取远程文件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-17T23:08:29+0800
     */
    public static function ActionCrawler()
    {
        $attachment_type = "file";
        switch(htmlspecialchars(self::$current_action))
        {
            case 'catchimage':
                $temp_config = [
                    "pathFormat" => self::$current_config['imagePathFormat'],
                    "maxSize" => self::$current_config['imageMaxSize'],
                    "allowFiles" => self::$current_config['imageAllowFiles']
                ];
                $attachment_type = "image";
                break;

            case 'catchscrawl':
                $temp_config = [
                    "pathFormat" => self::$current_config['scrawlPathFormat'],
                    "maxSize" => self::$current_config['scrawlMaxSize'],
                    "allowFiles" => self::$current_config['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                ];
                $attachment_type = "scrawl";
                break;

            case 'catchvideo':
                $temp_config = [
                    "pathFormat" => self::$current_config['videoPathFormat'],
                    "maxSize" => self::$current_config['videoMaxSize'],
                    "allowFiles" => self::$current_config['videoAllowFiles']
                ];
                $attachment_type = "video";
                break;

            case 'catchfile':
            default:
                $temp_config = [
                    "pathFormat" => self::$current_config['filePathFormat'],
                    "maxSize" => self::$current_config['fileMaxSize'],
                    "allowFiles" => self::$current_config['fileAllowFiles']
                ];
                $attachment_type = "file";
        }
        $field_name = self::$current_config['catcherFieldName'];

        // 上传路径匹配
        // 路径非其他，并且配置路径中包含其他则使用新的路径替换
        if(self::$path_type != 'other' && stripos($temp_config['pathFormat'], '/other/{yyyy}') !== false)
        {
            $temp_config['pathFormat'] = str_replace('/other/{yyyy}', '/'.str_replace('-', '/', self::$path_type).'/{yyyy}', $temp_config['pathFormat']);
        }

        // 抓取远程
        $list = [];
        if(!empty(self::$params[$field_name]))
        {
            $source = is_array(self::$params[$field_name]) ? self::$params[$field_name] : [self::$params[$field_name]];
            foreach($source as $url)
            {
                $up = new \base\Uploader($url, $temp_config, 'remote');
                /**
                 * 得到上传文件所对应的各个参数,数组结构
                 * array(
                 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
                 *     "url" => "",            //返回的地址
                 *     "path" => "",           //绝对地址
                 *     "title" => "",          //新文件名
                 *     "original" => "",       //原始文件名
                 *     "type" => ""            //文件类型
                 *     "size" => "",           //文件大小
                 *     "hash" => "",           //sha256值
                 * )
                 */
                $data = $up->getFileInfo();
                if(isset($data['state']) && $data['state'] == 'SUCCESS')
                {
                    $data['type'] = $attachment_type;
                    $data['category_id'] = self::$category_id;
                    $ret = AttachmentService::AttachmentAdd($data);
                    if($ret['code'] == 0)
                    {
                        $ret['data']['source'] = htmlspecialchars($url);
                        array_push($list, $ret['data']);
                    }
                }
            }
        }
        if(!empty($list))
        {
            return DataReturn('success', 0, $list);
        }
        return DataReturn(MyLang('no_data'), -1);
    }
}
?>