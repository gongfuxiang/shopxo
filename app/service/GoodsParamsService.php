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
 * 商品参数服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-27
 * @desc    description
 */
class GoodsParamsService
{
    /**
     * 商品分类参数模板
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryParamsTemplateList($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_ids',
                'error_msg'         => MyLang('common_service.goodsparamstemplate.form_item_category_id_message'),
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
            $data = self::GoodsParamsTemplateListHandle(Db::name('GoodsParamsTemplate')->where($where)->field('id,name,config_count')->order('id desc')->select()->toArray(), $params);
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
    public static function GoodsParamsTemplateListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 获取配置数据
            $res = Db::name('GoodsParamsTemplateConfig')->where(['template_id'=>array_column($data, 'id')])->field('id,template_id,type,name,value')->order('id asc')->select()->toArray();
            $config = [];
            if(!empty($res))
            {
                foreach($res as $c)
                {
                    $config[$c['template_id']][] = $c;
                }
            }

            foreach($data as &$v)
            {
                // 参数配置
                $v['config_data'] = empty($config[$v['id']]) ? [] : $config[$v['id']];

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
    public static function GoodsParamsTemplateSave($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => MyLang('common_service.goodsparamstemplate.form_item_category_id_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('common_service.goodsparamstemplate.form_item_name_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取参数解析并添加
        $config = self::GoodsParamsTemplateHandle($params);
        if($config['code'] != 0)
        {
            return $config;
        }

        // 数据
        $data = [
            'category_id'   => intval($params['category_id']),
            'name'          => $params['name'],
            'config_count'  => count($config['data']),
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 保存处理钩子
        $hook_name = 'plugins_service_goods_params_template_save_handle';
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

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 添加/编辑
            if(empty($params['id']))
            {
                $data['add_time'] = time();
                $template_id = Db::name('GoodsParamsTemplate')->insertGetId($data);
                if($template_id <= 0)
                {
                    throw new \Exception(MyLang('insert_fail'));
                }
            } else {
                $data['upd_time'] = time();
                if(Db::name('GoodsParamsTemplate')->where(['id'=>intval($params['id'])])->update($data) === false)
                {
                    throw new \Exception(MyLang('update_fail'));
                } else {
                    $template_id = $params['id'];
                }
            }

            // 删除商品参数
            Db::name('GoodsParamsTemplateConfig')->where(['template_id'=>$template_id])->delete();

            // 参数配置
            if($config['code'] == 0 && !empty($config['data']))
            {
                foreach($config['data'] as &$v)
                {
                    $v['template_id'] = $template_id;
                    $v['add_time'] = time();
                }
                if(Db::name('GoodsParamsTemplateConfig')->insertAll($config['data']) < count($config['data']))
                {
                    throw new \Exception(MyLang('common_service.goodsparamstemplate.save_params_data_insert_fail_tips'));
                }
            }

            // 完成
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
    public static function GoodsParamsTemplateDelete($params = [])
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

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 模板删除
            if(!Db::name('GoodsParamsTemplate')->where(['id'=>$params['ids']])->delete())
            {
                throw new \Exception(MyLang('common_service.goodsparamstemplate.delete_params_template_fail_tips'));
            }

            // 参数配置删除
            if(Db::name('GoodsParamsTemplateConfig')->where(['template_id'=>$params['ids']])->delete() === false)
            {
                throw new \Exception(MyLang('common_service.goodsparamstemplate.delete_params_data_fail_tips'));
            }

            // 完成
            Db::commit();
            return DataReturn(MyLang('delete_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
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
    public static function GoodsParamsTemplateStatusUpdate($params = [])
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
        if(Db::name('GoodsParamsTemplate')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 商品参数处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-31
     * @desc    description
     * @param   [array]             $params   [输入参数]
     */
    public static function GoodsParamsTemplateHandle($params = [])
    {
        // 展示范围、参数名称、参数值
        if(!empty($params['parameters_type']) && !empty($params['parameters_name']) && !empty($params['parameters_value']) && is_array($params['parameters_type']) && is_array($params['parameters_name']) && is_array($params['parameters_value']))
        {
            $data = [];
            foreach($params['parameters_type'] as $k=>$v)
            {
                if(isset($params['parameters_name'][$k]) && isset($params['parameters_value'][$k]))
                {
                    $data[] = [
                        'type'  => $v,
                        'name'  => $params['parameters_name'][$k],
                        'value' => $params['parameters_value'][$k],
                    ];
                }
            }
            if(!empty($data))
            {
                return DataReturn(MyLang('handle_success'), 0, $data);
            }
        }
        return DataReturn(MyLang('common_service.goodsparamstemplate.save_params_data_empty_tips'), -1);
    }
}
?>