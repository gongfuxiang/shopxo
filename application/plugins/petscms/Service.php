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
namespace app\plugins\petscms;

use think\Db;
use app\service\ResourcesService;

/**
 * 宠物管理系统 - 服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    // 类型
    public static $pets_attribute_type_list = [
        'cat' => ['value' => 'cat', 'name' => '猫咪'],
        'dog' => ['value' => 'dog', 'name' => '狗狗'],
        'other' => ['value' => 'other', 'name' => '其它'],
    ];

    // 是否
    public static $pets_attribute_is_text_list = [
        0 => ['value' => 0, 'name' => '否', 'checked' => true],
        1 => ['value' => 1, 'name' => '是'],
    ];

    // 性别
    public static $pets_attribute_gender_list = [
        0 => ['value' => 0, 'name' => '公'],
        1 => ['value' => 1, 'name' => '母'],
    ];

    /**
     * 宠物列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PetsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsPetscmsPets')->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 类型
                $v['type_name'] = self::$pets_attribute_type_list[$v['type']]['name'];

                // 性别
                $v['gender_name'] = self::$pets_attribute_gender_list[$v['gender']]['name'];

                // 是否绝育
                $v['sterilization_name'] = self::$pets_attribute_is_text_list[$v['sterilization']]['name'];

                // 是否疫苗
                $v['vaccine_name'] = self::$pets_attribute_is_text_list[$v['vaccine']]['name'];

                // 生日/年龄
                if(empty($v['birthday']))
                {
                    $v['birthday_name'] = null;
                    $v['age'] = '0岁';
                } else {
                    $v['birthday_name'] = date('Y-m-d', $v['birthday']);
                    $age = \base\Age::CalAge($v['birthday_name']);
                    $v['age'] = $age['year'].'年'.$age['month'].'月'.$age['day'].'天';
                }

                // 内容
                $v['content'] = ResourcesService::ContentStaticReplace($v['content'], 'get');

                // 相册
                $v['photo'] = empty($v['photo']) ? null : self::GetPestPhotoHandle($v['photo']);

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                $v['upd_time_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                $v['upd_time_date'] = empty($v['upd_time']) ? '' : date('Y-m-d', $v['upd_time']);
            }
        }
        //print_r($data);
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 宠物相册获取处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-11T22:56:49+0800
     * @param    [array]                   $photo [相册数据]
     */
    private static function GetPestPhotoHandle($photo)
    {
        $result = [];
        if(!empty($photo) && is_array($photo))
        {
            foreach($photo as &$v)
            {
                $result[] = [
                    'images_old'    => $v,
                    'images'        => ResourcesService::AttachmentPathViewHandle($v),
                ];
            }
        }
        return $result;
    }

    /**
     * 宠物列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PetsListWhere($params = [])
    {
        // 条件初始化
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }
        
        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['title|detail', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(!empty($params['type']))
            {
                $where[] = ['type', '=', $params['type']];
            }
            if(isset($params['gender']) && $params['gender'] > -1)
            {
                $where[] = ['gender', '=', intval($params['gender'])];
            }
            if(isset($params['sterilization']) && $params['sterilization'] > -1)
            {
                $where[] = ['sterilization', '=', intval($params['sterilization'])];
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
     * 宠物总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function PetsTotal($where = [])
    {
        return (int) Db::name('PluginsPetscmsPets')->where($where)->count();
    }

    /**
     * 宠物保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PestSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '1,60',
                'is_checked'        => 2,
                'error_msg'         => '标题格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '宠物名字格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'birthday',
                'is_checked'        => 2,
                'error_msg'         => '请填写出生日期',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(self::$pets_attribute_type_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物类型有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'varieties',
                'checked_data'      => '30',
                'is_checked'        => 2,
                'error_msg'         => '品种格式最多 30 个字符',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => array_column(self::$pets_attribute_gender_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物性别有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'sterilization',
                'checked_data'      => array_column(self::$pets_attribute_gender_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物是否绝育有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'photo',
                'is_checked'        => 2,
                'error_msg'         => '请上传宠物相册',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '105000',
                'is_checked'        => 2,
                'error_msg'         => '宠物简介内容最多 105000 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_name',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '主人姓名格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_tel',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '主人电话格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_weixin',
                'checked_data'      => '1,30',
                'is_checked'        => 2,
                'error_msg'         => '主人微信格式 1~30 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 相册
        $photo = self::GetFormPetsPhotoParams($params);
        if($photo['code'] != 0)
        {
            return $photo;
        }

        // 编辑器内容
        $content = empty($params['content']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['content']), 'add');

        // 宠物数据
        $data = [
            'user_id'       => isset($params['user']['id']) ? intval($params['user']['id']) : 0,
            'title'         => isset($params['title']) ? $params['title'] : '',
            'name'          => isset($params['name']) ? $params['name'] : '',
            'birthday'      => empty($params['birthday']) ? 0 : strtotime($params['birthday']),
            'type'          => isset($params['type']) ? $params['type'] : '',
            'varieties'     => isset($params['varieties']) ? $params['varieties'] : '',
            'gender'        => isset($params['gender']) ? intval($params['gender']) : 0,
            'sterilization' => isset($params['sterilization']) ? intval($params['sterilization']) : 0,
            'vaccine'       => isset($params['vaccine']) ? intval($params['vaccine']) : 0,
            'photo'         => empty($photo['data']) ? '' : json_encode($photo['data']),
            'content'       => $content,
            'person_name'   => isset($params['person_name']) ? $params['person_name'] : '',
            'person_tel'    => isset($params['person_tel']) ? $params['person_tel'] : '',
            'person_weixin' => isset($params['person_weixin']) ? $params['person_weixin'] : '',
        ];

        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('PluginsPetscmsPets')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('PluginsPetscmsPets')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100); 
        }
    }

    /**
     * 获取宠物相册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [array]                  [一维数组但图片地址]
     */
    private static function GetFormPetsPhotoParams($params = [])
    {
        $result = [];
        if(!empty($params['photo']) && is_array($params['photo']))
        {
            foreach($params['photo'] as $v)
            {
                $result[] = ResourcesService::AttachmentPathHandle($v);
            }
        }
        return DataReturn('success', 0, $result);
    }
}
?>