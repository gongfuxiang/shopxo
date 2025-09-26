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
use app\service\SystemBaseService;
use app\service\ResourcesService;
use app\service\AttachmentCategoryService;
use app\service\FormInputService;
use app\service\PackageInstallService;
use app\service\StoreService;

/**
 * form表单服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class FormInputApiService
{
    /**
     * 公共初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Init($params = [])
    {
        // 返回数据
        $data = [
            'config'               => self::ConfigData(),
            // 附件分类
            'attachment_category'  => AttachmentCategoryService::AttachmentCategoryAll(),
            // 模块组件
            'module_list'          => self::ModuleList(),
        ];

        // 钩子
        $hook_name = 'plugins_service_forminputapi_init_data';
        MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'params'      => $params,
            'data'        => &$data,
        ]);

        return DataReturn('success', 0, $data);
    }

    /**
     * 配置数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-04
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ConfigData($params = [])
    {
        $h5_url = MyC('common_app_h5_url');
        return [
            // 站点信息
            'site_name'                  => MyC('home_site_name'),
            'site_logo'                  => AttachmentPathViewHandle(MyC('home_site_logo')),
            'site_logo_wap'              => AttachmentPathViewHandle(MyC('home_site_logo_wap')),
            'site_logo_app'              => AttachmentPathViewHandle(MyC('home_site_logo_app')),
            'site_logo_square'           => AttachmentPathViewHandle(MyC('home_site_logo_square')),
            // 地图密钥
            'common_map_type'            => MyC('common_map_type', 'baidu', true),
            'common_baidu_map_ak'        => MyC('common_baidu_map_ak', null, true),
            'common_amap_map_ak'         => MyC('common_amap_map_ak', null, true),
            'common_amap_map_safety_ak'  => MyC('common_amap_map_safety_ak', null, true),
            'common_tencent_map_ak'      => MyC('common_tencent_map_ak', null, true),
            'common_tianditu_map_ak'     => MyC('common_tianditu_map_ak', null, true),
            // 商店form表单下载地址
            'store_forminput_url'        => StoreService::StoreFormInputUrl(),
            // 货币符号
            'currency_symbol'            => ResourcesService::CurrencyDataSymbol(),
            // 附件host地址
            'attachment_host'            => SystemBaseService::AttachmentHost(),
            // 上传组件配置
            'ueditor'                    => [
                'image_suffix'  => MyConfig('ueditor.imageAllowFiles'),
                'video_suffix'  => MyConfig('ueditor.videoAllowFiles'),
                'file_suffix'   => MyConfig('ueditor.fileAllowFiles'),
            ],
            // 附件分类权限
            'attachment_category_operate' => [
                'is_add'   => 1,
                'is_edit'  => 1,
                'is_del'   => 1,
            ],
            // 附件管理权限
            'attachment_operate'          => [
                'is_move'    => 1,
                'is_upload'  => 1,
                'is_edit'    => 1,
                'is_del'     => 1,
            ],
            // 配置预览地址
            'config_preview_url'      => empty($h5_url) ? '' : $h5_url.'pages/form-input/form-preview',
            // 预览地址
            'preview_url'             => MyUrl('admin/forminput/preview'),
            // forminput装修 - 详情
            'forminput_detail_url'    => MyUrl('admin/forminputapi/forminputdetail'),
            // forminput装修 - 保存
            'forminput_save_url'      => MyUrl('admin/forminputapi/forminputsave'),
            // forminput装修 - 导入
            'forminput_upload_url'    => MyUrl('admin/forminputapi/forminputupload'),
            // forminput装修 - 导出
            'forminput_download_url'  => MyUrl('admin/forminputapi/forminputdownload'),
            // forminput装修 - 安装
            'forminput_install_url'   => MyUrl('admin/forminputapi/forminputinstall'),
            // forminput装修 - 模板市场
            'forminput_market_url'    => MyUrl('admin/forminputapi/forminputmarket'),
        ];
    }

    /**
     * 模块组件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-30
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ModuleList($params = [])
    {
        return [
            [
                'name'  => '基础',
                'key'   => 'base',
                'data'  => [
                    ['key'=>'single-text', 'name' => '单行文本'],
                    ['key'=>'multi-text', 'name' => '多行文本'],
                    ['key'=>'number', 'name' => '数字'],
                    ['key'=>'radio-btns', 'name' => '单选按钮组'],
                    ['key'=>'checkbox', 'name' => '复选框组'],
                    ['key'=>'select', 'name' => '下拉框'],
                    ['key'=>'select-multi', 'name' => '下拉复选框'],
                    ['key'=>'date', 'name' => '日期时间'],
                    ['key'=>'date-group', 'name' => '日期时间组'],
                ]
            ],
            [
                'name'  => '高级',
                'key'   => 'hight-level',
                'data'  => [
                    ['key' => 'position', 'name' => '定位'],
                    ['key' => 'address', 'name' => '地址'],
                    ['key' => 'pwd', 'name' => '密码'],
                    ['key' => 'phone', 'name' => '手机'],
                    ['key' => 'score', 'name' => '评分'],
                    ['key' => 'rich-text', 'name' => '富文本'],
                    ['key' => 'subform', 'name' => '子表单'],
                    ['key' => 'upload-img', 'name' => '上传图片'],
                    ['key' => 'upload-video', 'name' => '上传视频'],
                    ['key' => 'upload-attachments', 'name' => '上传文件'],
                ]
            ],
            [
                'name'  => '扩展',
                'key'   => 'extend',
                'data'  => [
                    ['key' => 'auxiliary-line', 'name' => '辅助线'],
                    ['key' => 'text', 'name' => '文本'],
                    ['key' => 'img', 'name' => '图片'],
                    ['key' => 'video', 'name' => '视频'],
                    ['key' => 'attachments', 'name' => '文件'],
                    ['key' => 'rect', 'name' => '矩形'],
                    ['key' => 'round', 'name' => '圆形'],
                ]
            ]
        ];
    }

    /**
     * form表单列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputList($params = [])
    {
        $params['control'] = 'forminput';
        $params['action'] = 'index';
        $params['is_enable'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * form表单详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputDetail($params = [])
    {
        $params['control'] = 'forminput';
        $params['action'] = 'detail';
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * form表单保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputSave($params = [])
    {
        return FormInputService::FormInputSave($params);
    }

    /**
     * form表单导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputUpload($params = [])
    {
        return FormInputService::FormInputUpload($params);
    }

    /**
     * form表单导出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputDownload($params = [])
    {
        return FormInputService::FormInputDownload($params);
    }

    /**
     * form表单模板安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputInstall($params = [])
    {
        $params['type'] = 'forminput';
        return PackageInstallService::Install($params);
    }

    /**
     * form表单模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function FormInputMarket($params = [])
    {
        return FormInputService::FormInputMarket($params);
    }
}
?>