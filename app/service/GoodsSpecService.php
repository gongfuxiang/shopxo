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
use app\service\GoodsCategoryService;

/**
 * 商品规格服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-27
 * @desc    description
 */
class GoodsSpecService
{
    /**
     * 商品分类规格模板
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-25
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsCategorySpecTemplateList($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_ids',
                'error_msg'         => MyLang('common_service.goodsspectemplate.form_item_category_id_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取分类下所有分类id
        $data = [];
        $ids = GoodsCategoryService::GoodsCategoryParentIds($params['category_ids']);
        if(!empty($ids))
        {
            $where = [
                ['category_id', 'in', $ids],
                ['is_enable', '=', 1],
            ];
            $data = self::GoodsSpecTemplateListHandle(Db::name('GoodsSpecTemplate')->where($where)->field('id,name,content')->order('id desc')->select()->toArray(), $params);
        }
        return DataReturn(MyLang('operate_success'), 0, $data);
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
    public static function GoodsSpecTemplateListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
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
    public static function GoodsSpecTemplateSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => MyLang('common_service.goodsspectemplate.form_item_category_id_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('common_service.goodsspectemplate.form_item_name_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'content',
                'error_msg'         => MyLang('common_service.goodsspectemplate.save_content_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'category_id'   => intval($params['category_id']),
            'name'          => $params['name'],
            'content'       => $params['content'],
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 保存处理钩子
        $hook_name = 'plugins_service_goods_spec_template_save_handle';
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

        // 捕获异常
        try {
            // 添加/编辑
            if(empty($params['id']))
            {
                $data['add_time'] = time();
                $template_id = Db::name('GoodsSpecTemplate')->insertGetId($data);
                if($template_id <= 0)
                {
                    throw new \Exception(MyLang('insert_fail'));
                }
            } else {
                $data['upd_time'] = time();
                if(Db::name('GoodsSpecTemplate')->where(['id'=>intval($params['id'])])->update($data) === false)
                {
                    throw new \Exception(MyLang('update_fail'));
                } else {
                    $template_id = $params['id'];
                }
            }

            // 完成
            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
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
    public static function GoodsSpecTemplateDelete($params = [])
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

        // 捕获异常
        try {
            // 模板删除
            if(!Db::name('GoodsSpecTemplate')->where(['id'=>$params['ids']])->delete())
            {
                throw new \Exception(MyLang('delete_fail'));
            }

            // 完成
            return DataReturn(MyLang('delete_success'), 0);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSpecTemplateStatusUpdate($params = [])
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
        if(Db::name('GoodsSpecTemplate')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }
}
?>