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
use app\service\RegionService;

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

    // 状态（0正常, 1丢失, 2去世, 3关闭）
    public static $pets_attribute_status_list = [
        0 => ['value' => 0, 'name' => '正常'],
        1 => ['value' => 1, 'name' => '丢失'],
        2 => ['value' => 2, 'name' => '去世'],
        3 => ['value' => 3, 'name' => '关闭'],
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
        $order_by = empty($params['order_by']) ? 'status asc, id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsPetscmsPets')->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 类型
                $v['type_name'] = (isset($v['type']) && isset(self::$pets_attribute_type_list[$v['type']])) ? self::$pets_attribute_type_list[$v['type']]['name'] : '';

                // 性别
                $v['gender_name'] = (isset($v['gender']) && isset(self::$pets_attribute_gender_list[$v['gender']])) ? self::$pets_attribute_gender_list[$v['gender']]['name'] : '';

                // 是否绝育
                $v['sterilization_name'] = (isset($v['sterilization']) && isset(self::$pets_attribute_is_text_list[$v['sterilization']])) ? self::$pets_attribute_is_text_list[$v['sterilization']]['name'] : '';

                // 是否疫苗
                $v['vaccine_name'] = (isset($v['vaccine']) && isset(self::$pets_attribute_is_text_list[$v['vaccine']])) ? self::$pets_attribute_is_text_list[$v['vaccine']]['name'] : '';

                // 状态
                $v['status_name'] = self::$pets_attribute_status_list[$v['status']]['name'];

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
                $v['photo'] = empty($v['photo']) ? null : self::GetPetsPhotoHandle($v['photo']);

                // 丢失时间
                $v['lose_time_name'] = empty($v['lose_time']) ? '' : date('Y-m-d', $v['lose_time']);

                // 丢失宠物特征
                $v['lose_features'] = str_replace("\n", '<br />', $v['lose_features']);

                // 二维码
                $v['qrcode_url'] = MyUrl('index/qrcode/index', ['content'=>urlencode(base64_encode(PluginsHomeUrl('petscms', 'pets', 'detail', ['id'=>$v['id']])))]);
                $v['qrcode_download'] = MyUrl('index/qrcode/download', ['url'=>urlencode(base64_encode($v['qrcode_url']))]);

                // 地址
                $v['province_name'] = RegionService::RegionName($v['lose_province']);
                $v['city_name'] = RegionService::RegionName($v['lose_city']);
                $v['county_name'] = RegionService::RegionName($v['lose_county']);

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
    private static function GetPetsPhotoHandle($photo)
    {
        $result = [];
        if(!empty($photo))
        {
            if(is_string($photo))
            {
                $photo = json_decode($photo, true);
            }
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
            if(isset($params['status']) && $params['status'] > -1)
            {
                $where[] = ['status', '=', intval($params['status'])];
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
    public static function PetsSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '1,60',
                'is_checked'        => 1,
                'error_msg'         => '标题格式 1~60 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,30',
                'is_checked'        => 1,
                'error_msg'         => '宠物名字格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'birthday',
                'is_checked'        => 1,
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
                'is_checked'        => 1,
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
                'is_checked'        => 1,
                'error_msg'         => '请上传宠物相册',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'content',
                'checked_data'      => '105000',
                'is_checked'        => 1,
                'error_msg'         => '宠物简介内容最多 105000 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_name',
                'checked_data'      => '1,30',
                'is_checked'        => 1,
                'error_msg'         => '主人姓名格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_tel',
                'checked_data'      => '1,30',
                'is_checked'        => 1,
                'error_msg'         => '主人电话格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'person_weixin',
                'checked_data'      => '1,30',
                'is_checked'        => 1,
                'error_msg'         => '主人微信格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(self::$pets_attribute_status_list, 'value'),
                'is_checked'        => 2,
                'error_msg'         => '宠物状态有误',
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
            'user_id'       => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'title'         => isset($params['title']) ? $params['title'] : '',
            'name'          => isset($params['name']) ? $params['name'] : '',
            'birthday'      => empty($params['birthday']) ? 0 : strtotime($params['birthday']),
            'type'          => isset($params['type']) ? $params['type'] : '',
            'varieties'     => isset($params['varieties']) ? $params['varieties'] : '',
            'gender'        => isset($params['gender']) ? $params['gender'] : -1,
            'sterilization' => isset($params['sterilization']) ? $params['sterilization'] : -1,
            'vaccine'       => isset($params['vaccine']) ? $params['vaccine'] : -1,
            'photo'         => empty($photo['data']) ? '' : json_encode($photo['data']),
            'content'       => $content,
            'person_name'   => isset($params['person_name']) ? $params['person_name'] : '',
            'person_tel'    => isset($params['person_tel']) ? $params['person_tel'] : '',
            'person_weixin' => isset($params['person_weixin']) ? $params['person_weixin'] : '',

            'lose_time'     => empty($params['lose_time']) ? 0 : strtotime($params['lose_time']),
            'lose_reward_amount' => !empty($params['lose_reward_amount']) ? PriceNumberFormat($params['lose_reward_amount']) : 0.00,
            'lose_features' => isset($params['lose_features']) ? $params['lose_features'] : '',
            'lose_province' => isset($params['province']) ? intval($params['province']) : 0,
            'lose_city'     => isset($params['city']) ? intval($params['city']) : 0,
            'lose_county'   => isset($params['county']) ? intval($params['county']) : 0,
            'lose_lng'      => empty($params['lng']) ? 0.00 : floatval($params['lng']),
            'lose_lat'      => empty($params['lat']) ? 0.00 : floatval($params['lat']),
            'lose_address'  => isset($params['address']) ? $params['address'] : '',
            'status'        => isset($params['status']) ? intval($params['status']) : 0,
        ];

        // 绑定编号
        $edit_msg_title = '编辑';
        if(!empty($params['pest_no']))
        {
            $pets = Db::name('PluginsPetscmsPets')->where(['pest_no'=>$params['pest_no']])->field('id,pest_no,user_id')->find();
            if(empty($pets))
            {
                return DataReturn('宠物编号不存在['.$params['pest_no'].']', -10);
            }

            // 是否被其他用户绑定
            if(!empty($pets['user_id']))
            {
                return DataReturn('宠物编号已被绑定['.$params['pest_no'].']', -11);
            }

            // 使用编辑模式
            $params['id'] = $pets['id'];
            $edit_msg_title = '绑定';
        }

        // 添加/编辑
        if(empty($params['id']))
        {
            $data['pest_no'] = date('YmdHis').GetNumberCode(6);
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
                return DataReturn($edit_msg_title.'成功', 0);
            }
            return DataReturn($edit_msg_title.'失败', -100);
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

    /**
     * 丢失提供信息保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function HelpSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pets_id',
                'error_msg'         => '宠物id有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'contacts_name',
                'checked_data'      => '1,30',
                'error_msg'         => '联系人姓名格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'province',
                'error_msg'         => '请选择省份',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'city',
                'error_msg'         => '请选择城市',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'county',
                'error_msg'         => '请选择区/县',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'address',
                'checked_data'      => '1,80',
                'error_msg'         => '详细地址格式 1~80 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 电话微信只至少填写一个
        if(empty($params['contacts_tel']) && empty($params['contacts_weixin']))
        {
            return DataReturn('电话/微信只至少填写一个', -1);
        }

        // 宠物数据
        $data = [
            'user_id'           => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'pets_id'           => intval($params['pets_id']),
            'contacts_name'     => $params['contacts_name'],
            'contacts_tel'      => isset($params['contacts_tel']) ? $params['contacts_tel'] : '',
            'contacts_weixin'   => isset($params['contacts_weixin']) ? $params['contacts_weixin'] : '',
            'province'          => intval($params['province']),
            'city'              => intval($params['city']),
            'county'            => intval($params['county']),
            'address'           => $params['address'],
            'lng'               => empty($params['lng']) ? 0.00 : floatval($params['lng']),
            'lat'               => empty($params['lat']) ? 0.00 : floatval($params['lat']),
        ];

        // 添加/编辑
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('PluginsPetscmsHelp')->insertGetId($data) > 0)
            {
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('PluginsPetscmsHelp')->where(['id'=>intval($params['id'])])->update($data))
            {
                return DataReturn('编辑成功', 0);
            }
            return DataReturn('编辑失败', -100);
        }
    }

    /**
     * 宠物帮助数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function HelpList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsPetscmsHelp')->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 地址
                $v['province_name'] = RegionService::RegionName($v['province']);
                $v['city_name'] = RegionService::RegionName($v['city']);
                $v['county_name'] = RegionService::RegionName($v['county']);

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                $v['upd_time_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                $v['upd_time_date'] = empty($v['upd_time']) ? '' : date('Y-m-d', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 宠物帮助数据总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function HelpTotal($where = [])
    {
        return (int) Db::name('PluginsPetscmsHelp')->where($where)->count();
    }

    /**
     * 宠物解绑
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PetsUntying($params = [])
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

        // 条件
        $where = ['id'=>intval($params['id'])];
        if(!empty($params['user_id']))
        {
            $where['user_id'] = intval($params['user_id']);
        }

        // 解绑操作
        if(Db::name('PluginsPetscmsPets')->where($where)->update(['user_id'=>0, 'upd_time'=>time()]))
        {
            return DataReturn('解绑成功');
        }

        return DataReturn('解绑失败或资源不存在', -100);
    }

    /**
     * 宠物删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PetsDelete($params = [])
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
        if(Db::name('PluginsPetscmsPets')->where(['id'=>intval($params['id'])])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
    }
}
?>