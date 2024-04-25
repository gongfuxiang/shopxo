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

/**
 * 轮播图服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SlideService
{
    /**
     * 数据保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SlideSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,60',
                'error_msg'         => MyLang('common_service.slide.form_item_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'describe',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.slide.form_item_describe_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'platform',
                'error_msg'         => MyLang('form_platform_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'event_type',
                'checked_data'      => array_column(MyConst('common_app_event_type'), 'value'),
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_event_type_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'event_value',
                'checked_data'      => '255',
                'error_msg'         => MyLang('form_event_value_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'images_url',
                'checked_data'      => '255',
                'error_msg'         => MyLang('form_upload_images_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '3',
                'error_msg'         => MyLang('form_sort_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 附件
        $data_fields = ['images_url'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);

        // 数据
        $data = [
            'name'          => $params['name'],
            'describe'      => empty($params['describe']) ? '' : $params['describe'],
            'platform'      => empty($params['platform']) ? '' : json_encode(explode(',', $params['platform'])),
            'event_type'    => (isset($params['event_type']) && $params['event_type'] != '') ? intval($params['event_type']) : -1,
            'event_value'   => $params['event_value'],
            'images_url'    => $attachment['data']['images_url'],
            'bg_color'      => isset($params['bg_color']) ? $params['bg_color'] : '',
            'sort'          => intval($params['sort']),
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'start_time'    => empty($params['start_time']) ? 0 : strtotime($params['start_time']),
            'end_time'      => empty($params['end_time']) ? 0 : strtotime($params['end_time']),
        ];

        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Slide')->insertGetId($data) > 0)
            {
                return DataReturn(MyLang('insert_success'), 0);
            }
            return DataReturn(MyLang('insert_fail'), -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('Slide')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn(MyLang('edit_success'), 0);
            }
            return DataReturn(MyLang('edit_fail'), -100); 
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
    public static function SlideDelete($params = [])
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
        if(Db::name('Slide')->where(['id'=>$params['ids']])->delete())
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
    public static function SlideStatusUpdate($params = [])
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
        if(Db::name('Slide')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
           return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }

    /**
     * 轮播列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SlideList($params = [])
    {
        // 缓存
        $key = SystemService::CacheKey('shopxo.cache_banner_list_key').APPLICATION_CLIENT_TYPE;
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug') || MyC('common_data_is_use_cache') != 1)
        {
            // 获取banner数据
            $field = 'name,describe,images_url,event_value,event_type,platform,bg_color';
            $order_by = 'sort asc,id asc';
            $where = [
                ['is_enable', '=', 1],
            ];
            $expire_where = '(`start_time` = 0 OR `start_time` <= '.time().') AND (`end_time` = 0 OR `end_time` >= '.time().')';
            $list = Db::name('Slide')->field($field)->where($where)->whereRaw($expire_where)->order($order_by)->select()->toArray();
            if(!empty($list))
            {
                $data = [];
                foreach($list as &$v)
                {
                    // 平台
                    if(!empty($v['platform']))
                    {
                        // json数据则必须存在其中，则为字符串等于（老数据）
                        $platform = json_decode($v['platform'], true);
                        if((!empty($platform) && is_array($platform) && in_array(APPLICATION_CLIENT_TYPE, $platform)) || ($v['platform'] == APPLICATION_CLIENT_TYPE))
                        {
                            // 图片地址
                            $v['images_url'] = ResourcesService::AttachmentPathViewHandle($v['images_url']);

                            // 事件值
                            if(!empty($v['event_value']))
                            {
                                // 地图
                                if($v['event_type'] == 3)
                                {
                                    $v['event_value_data'] = explode('|', $v['event_value']);
                                }
                                $v['event_value'] = htmlspecialchars_decode($v['event_value']);
                            } else {
                                $v['event_value'] = null;
                            }

                            // 加入数据
                            $data[] = $v;
                        }
                    }
                }
            } else {
                $data = [];
            }

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }
}
?>