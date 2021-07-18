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
 * 品牌分类服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BrandCategoryService
{
    /**
     * 品牌分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BrandCategoryList($params = [])
    {
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'sort asc' : trim($params['order_by']);

        $data = Db::name('BrandCategory')->where(['is_enable'=>1])->field($field)->order($order_by)->select()->toArray();
        
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 获取品牌分类节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function BrandCategoryNodeSon($params = [])
    {
        // 获取数据
        $field = '*';
        $data = Db::name('BrandCategory')->field($field)->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son']        = 'no';
                $v['ajax_url']      = MyUrl('admin/brandcategory/getnodeson', array('id'=>$v['id']));
                $v['delete_url']    = MyUrl('admin/brandcategory/delete');
                $v['json']          = json_encode($v);
            }
            return DataReturn('操作成功', 0, $data);
        }
        return DataReturn('没有相关数据', -100);
    }

    /**
     * 品牌分类保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function BrandCategorySave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,16',
                'error_msg'         => '名称格式 2~16 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据
        $data = [
            'name'      => $params['name'],
            'sort'      => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable' => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        // 添加
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('BrandCategory')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('BrandCategory')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100);
        }
    }

    /**
     * 品牌分类删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function BrandCategoryDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '删除数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始删除
        if(Db::name('BrandCategory')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
    }
}
?>