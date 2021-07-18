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
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsParamsTemplateList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取列表
        $data = Db::name('GoodsParamsTemplate')->where($where)->order($order_by)->field($field)->limit($m, $n)->select()->toArray();
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
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsParamsTemplateTotal($where)
    {
        return (int) Db::name('GoodsParamsTemplate')->where($where)->count();
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
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,30',
                'error_msg'         => '名称格式 2~30 个字符',
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
                    throw new \Exception('添加失败');
                }
            } else {
                $data['upd_time'] = time();
                if(Db::name('GoodsParamsTemplate')->where(['id'=>intval($params['id'])])->update($data) === false)
                {
                    throw new \Exception('更新失败');
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
                    throw new \Exception('规格参数添加失败');
                }
            }

            // 完成
            Db::commit();
            return DataReturn('操作成功', 0);
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
            return DataReturn('操作id有误', -1);
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
                throw new \Exception('模板删除失败');
            }

            // 参数配置删除
            if(Db::name('GoodsParamsTemplateConfig')->where(['template_id'=>$params['ids']])->delete() === false)
            {
                throw new \Exception('规格参数删除失败');
            }

            // 完成
            Db::commit();
            return DataReturn('删除成功', 0);
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => '未指定操作字段',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
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
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
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
                return DataReturn('处理成功', 0, $data);
            }
        }
        return DataReturn('请填写参数配置', -1);
    }
}
?>