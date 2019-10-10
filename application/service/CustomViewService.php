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
namespace app\service;

use think\Db;
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
        $field = empty($params['field']) ? 'id,title,content,is_header,is_footer,is_full_screen,access_count,is_enable' : $params['field'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        $data = Db::name('CustomView')->field($field)->where($where)->order('id desc')->limit($m, $n)->select();
        if(!empty($data))
        {
            $common_is_enable_list = lang('common_is_enable_list');
            foreach($data as &$v)
            {
                // 是否启用
                if(isset($v['is_enable']))
                {
                    $v['is_enable_text'] = $common_is_enable_list[$v['is_enable']]['name'];
                }

                // 内容
                if(isset($v['content']))
                {
                    $v['content'] = ResourcesService::ContentStaticReplace($v['content'], 'get');
                }

                // 图片
                if(isset($v['images']))
                {
                    if(!empty($v['images']))
                    {
                        $images = json_decode($v['images'], true);
                        foreach($images as &$img)
                        {
                            $img = ResourcesService::AttachmentPathViewHandle($img);
                        }
                        $v['images'] = $images;
                    }
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                    $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                    $v['upd_time_date'] = date('Y-m-d', $v['upd_time']);
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
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function CustomViewTotal($where = [])
    {
        return (int) Db::name('CustomView')->where($where)->count();
    }

    /**
     * 列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CustomViewListWhere($params = [])
    {
        $where = [];

        // id
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', $params['id']];
        }

        if(!empty($params['keywords']))
        {
            $where[] = ['title', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_enable']) && $params['is_enable'] > -1)
            {
                $where[] = ['is_enable', '=', intval($params['is_enable'])];
            }
            if(isset($params['is_full_screen']) && $params['is_full_screen'] > -1)
            {
                $where[] = ['is_full_screen', '=', intval($params['is_full_screen'])];
            }
            if(isset($params['is_header']) && $params['is_header'] > -1)
            {
                $where[] = ['is_header', '=', intval($params['is_header'])];
            }
            if(isset($params['is_footer']) && $params['is_footer'] > -1)
            {
                $where[] = ['is_footer', '=', intval($params['is_footer'])];
            }

            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
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
            return Db::name('CustomView')->where(array('id'=>intval($params['id'])))->setInc('access_count');
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
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '2,60',
                'error_msg'         => '标题长度 2~60 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '50,105000',
                'error_msg'         => '内容长度最少 50~105000 个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 编辑器内容
        $content = isset($params['content']) ? htmlspecialchars_decode($params['content']) : '';

        // 数据
        $images = self::MatchContentImage($content);
        $data = [
            'title'         => $params['title'],
            'content'       => ResourcesService::ContentStaticReplace($content, 'add'),
            'images'        => empty($images) ? '' : json_encode($images),
            'images_count'  => count($images),
            'is_enable'     => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'is_header'     => isset($params['is_header']) ? intval($params['is_header']) : 0,
            'is_footer'     => isset($params['is_footer']) ? intval($params['is_footer']) : 0,
            'is_full_screen'=> isset($params['is_full_screen']) ? intval($params['is_full_screen']) : 0,
        ];

        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('CustomView')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('CustomView')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100); 
        }
    }

    /**
     * 正则匹配文章图片
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-22T18:06:53+0800
     * @param    [string]         $content [文章内容]
     * @return   [array]                   [文章图片数组（一维）]
     */
    private static function MatchContentImage($content)
    {
        if(!empty($content))
        {
            $pattern = '/<img.*?src=[\'|\"](\/static\/upload\/customview\/image\/.*?[\.gif|\.jpg|\.jpeg|\.png|\.bmp])[\'|\"].*?[\/]?>/';
            preg_match_all($pattern, $content, $match);
            return empty($match[1]) ? [] : $match[1];
        }
        return [];
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
        if(Db::name('CustomView')->where(['id'=>$params['id']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => '字段有误',
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
        if(Db::name('CustomView')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state'])]))
        {
           return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }
}
?>