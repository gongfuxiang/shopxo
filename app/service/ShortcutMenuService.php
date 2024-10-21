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
use app\service\SystemService;
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\AdminPowerService;

/**
 * 常用功能服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ShortcutMenuService
{
    /**
     * 常用功能列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ShortcutMenuList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'sort asc,id asc' : trim($params['order_by']);

        // 常用功能列表读取前钩子
        $hook_name = 'plugins_service_shortcut_menu_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
        ]);

        // 获取列表
        return self::ShortcutMenuListHandle(Db::name('ShortcutMenu')->where($where)->field($field)->order($order_by)->select()->toArray(), $params);
    }

    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-11
     * @desc    description
     * @param   [array]          $data      [列表数据]
     * @param   [array]          $params    [输入参数]
     */
    public static function ShortcutMenuListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 权限菜单
            $menu = AdminPowerService::PowerMenuInit($params['admin']);

            // 当前站点地址
            $domain_url = SystemService::DomainUrl();

            // 是否编辑
            $is_edit = isset($params['is_edit']) ? intval($params['is_edit']) : 0;

            // 数据处理
            foreach($data as &$v)
            {
                // icon
                if(isset($v['icon']))
                {
                    $v['icon'] = ResourcesService::AttachmentPathViewHandle($v['icon']);
                }

                // 是否选择了菜单
                if(!empty($v['menu']))
                {
                    // 是否是插件
                    if(substr($v['menu'], 0, 8) == 'plugins-')
                    {
                        $v['url'] = PluginsAdminUrl(substr($v['menu'], 8), 'admin', 'index');
                    } else {
                        // 左侧菜单数据
                        if(!empty($menu['admin_left_menu']))
                        {
                            foreach($menu['admin_left_menu'] as $mv)
                            {
                                if(empty($mv['items']))
                                {
                                    if($mv['id'] == $v['menu'])
                                    {
                                        $v['url'] = $mv['url'];
                                        break;
                                    }
                                } else {
                                    foreach($mv['items'] as $mvs)
                                    {
                                        if(empty($mvs['items']))
                                        {
                                            if($mvs['id'] == $v['menu'])
                                            {
                                                $v['url'] = $mvs['url'];
                                                break 2;
                                            }
                                        } else {
                                            foreach($mvs['items'] as $mvss)
                                            {
                                                if($mvss['id'] == $v['menu'])
                                                {
                                                    $v['url'] = $mvss['url'];
                                                    break 3;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // 域名拼接处理
                if($is_edit == 0 && !empty($v['url']) && !in_array(substr($v['url'], 0, 6), ['http:/', 'https:']))
                {
                    if(substr($v['url'], 0, 1) == '/')
                    {
                        $v['url'] = substr($v['url'], 1);
                    }
                    $v['url'] = $domain_url.$v['url'];
                }

                // 时间
                if(array_key_exists('add_time', $v))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(array_key_exists('upd_time', $v))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
            }
        }
        return $data;
    }

    /**
     * 保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ShortcutMenuSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('common_service.shortcutmenu.form_item_name_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'icon',
                'error_msg'         => MyLang('common_service.shortcutmenu.form_item_icon_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        // 权限和链接地址必须填写一个
        if(empty($params['menu']) && empty($params['url']))
        {
            return DataReturn(MyLang('common_service.shortcutmenu.form_item_menu_or_url_message'), -1);
        }

        // 附件
        $attachment = ResourcesService::AttachmentParams($params, ['icon']);

        // 数据
        $data = [
            'name'  => $params['name'],
            'icon'  => $attachment['data']['icon'],
            'menu'  => empty($params['menu']) ? '' : $params['menu'],
            'url'   => empty($params['url']) ? '' : $params['url'],
        ];

        // 常用功能保存处理钩子
        $hook_name = 'plugins_service_shortcut_menu_save_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'data_id'       => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 编辑的数据
        $info = empty($params['id']) ? [] : Db::name('ShortcutMenu')->where(['id'=>intval($params['id'])])->find();

        // 捕获异常
        try {
            if(empty($info))
            {
                $data['add_time'] = time();
                if(Db::name('ShortcutMenu')->insertGetId($data) <= 0)
                {
                    throw new \Exception(MyLang('insert_fail'));
                }
            } else {
                $data['upd_time'] = time();
                if(Db::name('ShortcutMenu')->where(['id'=>$info['id']])->update($data) === false)
                {
                    throw new \Exception(MyLang('edit_fail'));
                }
            }

            // 编辑的时候，图片已改变则删除
            if(!empty($info) && $info['icon'] != $data['icon'])
            {
                AttachmentService::AttachmentUrlDelete($info['icon']);
            }

            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 排序
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ShortcutMenuSort($params = [])
    {
        // 参数是否有误
        if(empty($params['data']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['data']))
        {
            $params['data'] = json_decode($params['data'], true);
        }

        // 捕获异常
        Db::startTrans();
        try {
            foreach($params['data'] as $v)
            {
                if(!empty($v['id']) && isset($v['sort']))
                {
                    if(Db::name('ShortcutMenu')->where(['id'=>intval($v['id'])])->update(['sort'=>intval($v['sort']), 'upd_time'=>time()]) === false)
                    {
                        throw new \Exception(MyLang('operate_fail'));
                    }
                }
            }
            Db::commit();
            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
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
    public static function ShortcutMenuDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 获取图标地址
        $icon = array_filter(Db::name('ShortcutMenu')->where(['id'=>$params['ids']])->column('icon'));

        // 删除操作
        if(Db::name('ShortcutMenu')->where(['id'=>$params['ids']])->delete())
        {
            // 删除图片
            if(!empty($icon))
            {
                AttachmentService::AttachmentUrlDelete($icon);
            }
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>