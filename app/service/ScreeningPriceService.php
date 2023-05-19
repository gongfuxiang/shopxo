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
 * 筛选价格服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ScreeningPriceService
{
    /**
     * 获取节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ScreeningPriceNodeSon($params = [])
    {
        // id
        $id = isset($params['id']) ? intval($params['id']) : 0;

        // 获取数据
        $field = 'id,name,sort,is_enable,min_price,max_price';
        $data = Db::name('ScreeningPrice')->field($field)->where(['pid'=>$id])->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son']  =   (Db::name('ScreeningPrice')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['json']    =   json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 数据保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ScreeningPriceSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.screeningprice.form_item_name_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'name'                  => $params['name'],
            'pid'                   => isset($params['pid']) ? intval($params['pid']) : 0,
            'min_price'             => intval($params['min_price']),
            'max_price'             => intval($params['max_price']),
            'sort'                  => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('ScreeningPrice')->insertGetId($data);
            if($data['id'] <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('ScreeningPrice')->where(['id'=>intval($params['id'])])->update($data) === false)
            {
                return DataReturn(MyLang('edit_fail'), -100);
            } else {
                $data['id'] = $params['id'];
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $data);
    }

    /**
     * 删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ScreeningPriceDelete($params = [])
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
                'key_name'          => 'admin',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始删除
        if(Db::name('ScreeningPrice')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>