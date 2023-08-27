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

/**
 * 自定义页面服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CustomViewService
{
    /**
     * 获取自定义列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CustomViewList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        $data = Db::name('CustomView')->field($field)->where($where)->order($order_by)->limit($m, $n)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::CustomViewListHandle($data, $params));
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
    public static function CustomViewListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            $common_is_text_list = MyConst('common_is_text_list');
            foreach($data as &$v)
            {
                // logo
                if(array_key_exists('logo', $v))
                {
                    $v['logo'] = ResourcesService::AttachmentPathViewHandle($v['logo']);
                }

                // 内容
                if(isset($v['html_content']))
                {
                    $v['html_content'] = ResourcesService::ContentStaticReplace($v['html_content'], 'get');
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
            }
        }
        return $data;
    }

    /**
     * 自定义页面访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CustomViewAccessCountInc($params = [])
    {
        if(!empty($params['id']))
        {
            return Db::name('CustomView')->where(array('id'=>intval($params['id'])))->inc('access_count')->update();
        }
        return false;
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
    public static function CustomViewSave($params = [])
    {
        // 附件
        $data_fields = ['logo'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'            => empty($params['name']) ? MyLang('common_service.customview.create_name_default').''.date('mdHi') : $params['name'],
            'logo'            => $attachment['data']['logo'],
            'html_content'    => empty($params['html_content']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['html_content']), 'add'),
            'css_content'     => empty($params['css_content']) ? '' : htmlspecialchars_decode($params['css_content']),
            'js_content'      => empty($params['js_content']) ? '' : htmlspecialchars_decode($params['js_content']),
            'is_enable'       => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'is_header'       => isset($params['is_header']) ? intval($params['is_header']) : 0,
            'is_footer'       => isset($params['is_footer']) ? intval($params['is_footer']) : 0,
            'is_full_screen'  => isset($params['is_full_screen']) ? intval($params['is_full_screen']) : 0,
            'seo_title'       => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'    => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'        => empty($params['seo_desc']) ? '' : $params['seo_desc'],
        ];
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data_id = Db::name('CustomView')->insertGetId($data);
            if($data_id <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -1);
            }
        } else {
            $data_id = intval($params['id']);
            $data['upd_time'] = time();
            if(Db::name('CustomView')->where(['id'=>$data_id])->update($data) === false)
            {
                return DataReturn(MyLang('save_fail'), -1);
            }
        }
        return DataReturn(MyLang('save_success'), 0, $data_id);
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
    public static function CustomViewDelete($params = [])
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

        // 删除操作
        if(Db::name('CustomView')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function CustomViewStatusUpdate($params = [])
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
        if(Db::name('CustomView')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
           return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }
}
?>