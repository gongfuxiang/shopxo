<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\plugins\answers;

use think\Db;
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\AnswerService;

/**
 * 问答系统服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    /**
     * 幻灯片数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SlideList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $data = Db::name('PluginsAnswersSlide')->where($where)->order('sort asc')->select();
        if(!empty($data))
        {
            $common_is_enable_tips = lang('common_is_enable_tips');
            foreach($data as &$v)
            {
                // 是否启用
                $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];

                // 图片地址
                $v['images_url_old'] = $v['images_url'];
                $v['images_url'] = ResourcesService::AttachmentPathViewHandle($v['images_url']);

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                $v['upd_time_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                $v['upd_time_date'] = date('Y-m-d', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 幻灯片数据保存
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
                'error_msg'         => '名称长度 2~60 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'images_url',
                'checked_data'      => '255',
                'error_msg'         => '请上传图片',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'url',
                'is_checked'        => 1,
                'checked_data'      => 'CheckUrl',
                'error_msg'         => 'url格式有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '3',
                'error_msg'         => '顺序 0~255 之间的数值',
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
            'url'           => $params['url'],
            'images_url'    => $attachment['data']['images_url'],
            'sort'          => intval($params['sort']),
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
        ];

        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('PluginsAnswersSlide')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('PluginsAnswersSlide')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100); 
        }
    }

    /**
     * 幻灯片删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SlideDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除操作
        if(Db::name('PluginsAnswersSlide')->where(['id'=>$params['id']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
    }

    /**
     * 幻灯片状态更新
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
                'error_msg'         => '操作id有误',
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
        if(Db::name('PluginsAnswersSlide')->where(['id'=>intval($params['id'])])->update(['is_enable'=>intval($params['state'])]))
        {
           return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 商品搜索
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSearchList($params = [])
    {
        // 条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1]
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['g.title', 'like', '%'.$params['keywords'].'%'];
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $category_ids = GoodsService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where[] = ['gci.category_id', 'in', $category_ids];
        }

        // 指定字段
        $field = 'g.id,g.title';

        // 获取数据
        return GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>0, 'n'=>100, 'field'=>$field, 'is_admin_access'=>1]);
    }

    /**
     * 关联商品保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-07
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSave($params = [])
    {
        // 清除商品id
        Db::name('PluginsAnswersGoods')->where('id', '>', 0)->delete();

        // 写入商品id
        if(!empty($params['category_ids']))
        {
            $ids_all = explode(',', $params['category_ids']);
            $data = [];
            foreach($ids_all as $goods_id)
            {
                $data[] = [
                    'goods_id'  => $goods_id,
                    'add_time'  => time(),
                ];
            }
            if(Db::name('PluginsAnswersGoods')->insertAll($data) < count($data))
            {
                return DataReturn('操作失败', -100);
            }
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 商品列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsList($params = [])
    {
        // 获取推荐商品id
        $goods_ids = Db::name('PluginsAnswersGoods')->column('goods_id');
        if(empty($goods_ids))
        {
            return DataReturn('没有商品', 0, ['goods'=>[], 'goods_ids'=>[]]);
        }

        // 条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1],
            ['g.id', 'in', $goods_ids],
        ];

        // 指定字段
        $field = 'g.id,g.title,g.images,g.min_price';

        // 获取数据
        $ret = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>0, 'n'=>100, 'field'=>$field]);
        return DataReturn('操作成功', 0, ['goods'=>$ret['data'], 'goods_ids'=>$goods_ids]);
    }

    /**
     * 条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AnswerListWhere($params = [])
    {
        // 条件
        $where = [
            ['is_delete_time', '=', 0],
            ['is_show', '=', 1],
        ];

        // 搜索关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['title|content', 'like', '%'.$params['keywords'].'%'];
        }

        // 指定问答id
        if(!empty($params['category_ids']))
        {
            $where[] = ['id', 'in', explode(',', $params['category_ids'])];
        }

        return $where;
    }

    /**
     * 问答列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AnswerList($params = [])
    {
        // 字段
        $field = empty($params['field']) ? 'id,name,title,content,reply,is_reply,reply_time,add_time' : $params['field'];

        // 获取列表
        $data_params = array(
            'm'         => 0,
            'n'         => isset($params['n']) ? intval($params['n']) : 10,
            'where'     => self::AnswerListWhere($params),
            'field'     => $field,
        );
        return AnswerService::AnswerList($data_params);
    }

    /**
     * 获取一条问答
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AnswerRow($params = [])
    {
        // 参数
        if(empty($params['id']))
        {
            return DataReturn('问答id有误', -1);
        }
        // 条件
        $where = [
            ['is_delete_time', '=', 0],
            ['is_show', '=', 1],
            ['id', '=', intval($params['id'])],
        ];

        // 字段
        $field = 'id,name,title,content,reply,is_reply,access_count,reply_time,add_time';

        // 获取列表
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => $where,
            'field'     => $field,
        );
        $ret = AnswerService::AnswerList($data_params);
        if(isset($ret['data'][0]))
        {
            $ret['data'] = $ret['data'][0];
        }
        return $ret;
    }
}
?>