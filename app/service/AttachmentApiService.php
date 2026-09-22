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

use app\service\UeditorService;
use app\service\AttachmentService;
use app\service\AttachmentCategoryService;
use app\service\AdminService;

/**
 * 附件api服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class AttachmentApiService
{
    /**
     * 校验后台管理员登录（附件写操作）
     * @author  Devil
     * @version 1.0.0
     * @date    2026-09-13
     * @return  [array]  DataReturn；成功时 data 为管理员信息
     */
    private static function RequireAdmin()
    {
        $admin = AdminService::LoginInfo();
        if(empty($admin))
        {
            return DataReturn(MyLang('login_failure_tips'), -400);
        }
        return DataReturn('success', 0, $admin);
    }

    /**
     * 附件分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Category($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        $result = [
            'attachment_category' => AttachmentCategoryService::AttachmentCategoryAll(),
        ];
        return DataReturn('success', 0, $result);
    }

    /**
     * 附件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Save($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        $params['admin'] = $auth['data'];
        return AttachmentService::AttachmentSave($params);
    }

    /**
     * 附件删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Delete($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        $params['admin'] = $auth['data'];
        return AttachmentService::AttachmentDelete($params);
    }

    /**
     * 附件上传
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Upload($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '附件类型有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        if(empty($params['category_id']) && empty($params['path_type']))
        {
            return DataReturn('附件分类和附件路径标识必须传一个', -1);
        }

        // 附件方法
        $params['action'] = 'upload'.$params['type'];
        return UeditorService::Run($params);
    }

    /**
     * 远程下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Catch($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '附件类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'source',
                'error_msg'         => '远程地址为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        if(empty($params['category_id']) && empty($params['path_type']))
        {
            return DataReturn('附件分类和附件路径标识必须传一个', -1);
        }

        // 附件方法
        $params['action'] = 'catch'.$params['type'];
        return UeditorService::Run($params);
    }

    /**
     * 附件扫码上传数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ScanUploadData($params = [])
    {
        $params['action'] = 'scandata';
        return UeditorService::Run($params);
    }

    /**
     * 附件移动分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function MoveCategory($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        return AttachmentService::AttachmentMoveCategory($params);
    }

    /**
     * 附件分类保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CategorySave($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        return AttachmentCategoryService::AttachmentCategorySave($params);
    }

    /**
     * 附件分类保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CategoryDelete($params = [])
    {
        $auth = self::RequireAdmin();
        if($auth['code'] != 0)
        {
            return $auth;
        }
        return AttachmentCategoryService::AttachmentCategoryDelete($params);
    }
}
?>