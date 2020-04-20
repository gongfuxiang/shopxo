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
use think\facade\Hook;
use app\service\BuyService;
use app\service\MessageService;
use app\service\OrderService;
use app\service\GoodsService;

/**
 * 导航服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class NavigationService
{
    /**
     * 获取导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Nav($params = [])
    {
        // 读取缓存数据
        $header = cache(config('shopxo.cache_common_home_nav_header_key'));
        $footer = cache(config('shopxo.cache_common_home_nav_footer_key'));

        // 导航模型
        $field = array('id', 'pid', 'name', 'url', 'value', 'data_type', 'is_new_window_open');

        // 缓存没数据则从数据库重新读取,顶部菜单
        if(empty($header))
        {
            // 获取导航数据
            $header = self::NavDataDealWith(Db::name('Navigation')->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>0))->order('sort')->select());
            if(!empty($header))
            {
                foreach($header as &$v)
                {
                    $v['items'] = self::NavDataDealWith(Db::name('Navigation')->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>$v['id']))->order('sort')->select());
                }
            }

            // 大导航钩子
            $hook_name = 'plugins_service_navigation_header_handle';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'header'        => &$header,
            ]);
 
            cache(config('shopxo.cache_common_home_nav_header_key'), $header);
        }

        // 底部导航
        if(empty($footer))
        {
            $footer = self::NavDataDealWith(Db::name('Navigation')->field($field)->where(array('nav_type'=>'footer', 'is_show'=>1, 'pid'=>0))->order('sort')->select());
            if(!empty($footer))
            {
                foreach($footer as &$v)
                {
                    $v['items'] = self::NavDataDealWith(Db::name('Navigation')->field($field)->where(array('nav_type'=>'footer', 'is_show'=>1, 'pid'=>$v['id']))->order('sort')->select());
                }
            }

            // 底部导航钩子
            $hook_name = 'plugins_service_navigation_footer_handle';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'footer'        => &$footer,
            ]);

            cache(config('shopxo.cache_common_home_nav_footer_key'), $footer);
        }

        // 中间大导航添加首页导航
        array_unshift($header, [
            'id'                    => 0,
            'pid'                   => 0,
            'name'                  => '首页',
            'url'                   => __MY_URL__,
            'data_type'             => 'system',
            'is_show'               => 1,
            'is_new_window_open'    => 0,
            'items'                 => [],
        ]);

        // 选中处理
        if(!empty($header))
        {
            foreach($header as &$v)
            {
                $v['active'] = ($v['url'] == __MY_VIEW_URL__) ? 1 : 0;
                if($v['active'] == 0 && !empty($v['items']))
                {
                    $status = false;
                    foreach($v['items'] as &$vs)
                    {
                        if($vs['url'] == __MY_VIEW_URL__)
                        {
                            $vs['active'] = 1;
                            $status = true;
                        } else {
                            $vs['active'] = 0;
                        }
                    }

                    // 当子元素被选中则父级也选中
                    if($status)
                    {
                        $v['active'] = 1;
                    }
                }
            }
        }

        return [
            'header' => $header,
            'footer' => $footer,
        ];
    }

    /**
     * [NavDataDealWith 导航数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-05T21:36:46+0800
     * @param    [array]      $data [需要处理的数据]
     * @return   [array]            [处理好的数据]
     */
    public static function NavDataDealWith($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                // url处理
                switch($v['data_type'])
                {
                    // 文章分类
                    case 'article':
                        $v['url'] = MyUrl('index/article/index', ['id'=>$v['value']]);
                        break;

                    // 自定义页面
                    case 'customview':
                        $v['url'] = MyUrl('index/customview/index', ['id'=>$v['value']]);
                        break;

                    // 商品分类
                    case 'goods_category':
                        $v['url'] = MyUrl('index/search/index', ['category_id'=>$v['value']]);
                        break;
                }
                $data[$k] = $v;
            }
        }
        return $data;
    }

    /**
     * 获取导航列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function NavList($params = [])
    {
        if(empty($params['nav_type']))
        {
            return [];
        }

        $field = 'id,pid,name,url,value,data_type,sort,is_show,is_new_window_open';
        $data = self::NavDataDealWith(Db::name('Navigation')->field($field)->where(['nav_type'=>$params['nav_type'], 'pid'=>0])->order('sort')->select());
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['items'] = self::NavDataDealWith(Db::name('Navigation')->field($field)->where(['nav_type'=>$params['nav_type'], 'pid'=>$v['id']])->order('sort')->select());
            }
        }
        return $data;
    }

    /**
     * 获取一级导航列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LevelOneNav($params = [])
    {
        if(empty($params['nav_type']))
        {
            return [];
        }

        return Db::name('Navigation')->field('id,name')->where(['is_show'=>1, 'pid'=>0, 'nav_type'=>$params['nav_type']])->select();
    }

    /**
     * 导航保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-07T21:58:19+0800
     * @param   [array]          $params [输入参数]
     */
    public static function NavSave($params = [])
    {
        if(empty($params['data_type']))
        {
            return DataReturn('操作类型有误', -1);
        }

        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '4',
                'error_msg'         => '顺序 0~255 之间的数值',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_show',
                'checked_data'      => [0,1],
                'error_msg'         => '是否显示范围值有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_new_window_open',
                'checked_data'      => [0,1],
                'error_msg'         => '是否新窗口打开范围值有误',
            ]
        ];
        switch($params['data_type'])
        {
            // 自定义导航
            case 'custom':
                $p = [
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'nav_type',
                        'checked_data'      => ['header', 'footer'],
                        'error_msg'         => '数据类型有误',
                    ],
                    [
                        'checked_type'      => 'length',
                        'key_name'          => 'name',
                        'checked_data'      => '2,16',
                        'error_msg'         => '导航名称格式 2~16 个字符',
                    ],
                    [
                        'checked_type'      => 'fun',
                        'key_name'          => 'url',
                        'checked_data'      => 'CheckUrl',
                        'error_msg'         => 'url格式有误',
                    ],
                ];
                break;

            // 文章分类导航
            case 'article':
                $p = [
                    [
                        'checked_type'      => 'length',
                        'key_name'          => 'name',
                        'checked_data'      => '2,16',
                        'is_checked'        => 1,
                        'error_msg'         => '导航名称格式 2~16 个字符',
                    ],
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => '文章选择有误',
                    ],
                ];
                break;

            // 自定义页面导航
            case 'customview':
                $p = [
                    [
                        'checked_type'      => 'length',
                        'key_name'          => 'name',
                        'checked_data'      => '2,16',
                        'is_checked'        => 1,
                        'error_msg'         => '导航名称格式 2~16 个字符',
                    ],
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => '自定义页面选择有误',
                    ],
                ];
                break;

            // 商品分类导航
            case 'goods_category':
                $p = [
                    [
                        'checked_type'      => 'length',
                        'key_name'          => 'name',
                        'checked_data'      => '2,16',
                        'is_checked'        => 1,
                        'error_msg'         => '导航名称格式 2~16 个字符',
                    ],
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => '商品分类选择有误',
                    ],
                ];
                break;

            // 没找到
            default :
                return DataReturn('操作类型有误', -1);
        }

        // 参数
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 保存数据
        return self::NacDataSave($params); 
    }

    /**
     * 导航数据保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-05T20:12:30+0800
     * @param   [array]          $params [输入参数]
     */
    public static function NacDataSave($params = [])
    {
        // 非自定义导航数据处理
        if(empty($params['name']))
        {
            switch($params['data_type'])
            {
                // 文章分类导航
                case 'article':
                    $temp_name = Db::name('Article')->where(['id'=>$params['value']])->value('title');
                    break;

                // 自定义页面导航
                case 'customview':
                    $temp_name = Db::name('CustomView')->where(['id'=>$params['value']])->value('title');
                    break;

                // 商品分类导航
                case 'goods_category':
                    $temp_name = Db::name('GoodsCategory')->where(['id'=>$params['value']])->value('name');
                    break;
            }
            // 只截取16个字符
            $params['name'] = mb_substr($temp_name, 0, 16, config('shopxo.default_charset'));
        }

        // 清除缓存
        cache(config('cache_common_home_nav_'.$params['nav_type'].'_key'), null);

        // 数据
        $data = [
            'pid'                   => isset($params['pid']) ? intval($params['pid']) : 0,
            'value'                 => isset($params['value']) ? intval($params['value']) : 0,
            'name'                  => $params['name'],
            'url'                   => isset($params['url']) ? $params['url'] : '',
            'nav_type'              => $params['nav_type'],
            'data_type'             => $params['data_type'],
            'sort'                  => intval($params['sort']),
            'is_show'               => intval($params['is_show']),
            'is_new_window_open'    => intval($params['is_new_window_open']),
        ];

        // id为空则表示是新增
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Navigation')->insertGetId($data) > 0)
            {
                // 清除缓存
                cache(config('cache_common_home_nav_'.$params['nav_type'].'_key'), null);
                
                return DataReturn('新增成功', 0);
            } else {
                return DataReturn('新增失败', -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('Navigation')->where(['id'=>intval($params['id'])])->update($data))
            {
                // 清除缓存
                cache(config('cache_common_home_nav_'.$params['nav_type'].'_key'), null);

                return DataReturn('编辑成功', 0);
            } else {
                return DataReturn('编辑失败或数据未改变', -100);
            }
        }
    }

    /**
     * 导航删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function NavDelete($params = [])
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

        // 启动事务
        Db::startTrans();

        // 删除操作
        if(Db::name('Navigation')->where(['id'=>$params['id']])->delete() !== false && Db::name('Navigation')->where(['pid'=>$params['id']])->delete() !== false)
        {
            // 提交事务
            Db::commit();

            // 清除缓存
            cache(config('shopxo.cache_common_home_nav_header_key'), null);
            cache(config('shopxo.cache_common_home_nav_footer_key'), null);

            return DataReturn('删除成功');
        }

        // 回滚事务
        Db::rollback();

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
    public static function NavStatusUpdate($params = [])
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
        if(Db::name('Navigation')->where(['id'=>intval($params['id'])])->update(['is_show'=>intval($params['state'])]))
        {
            // 清除缓存
            cache(config('shopxo.cache_common_home_nav_header_key'), null);
            cache(config('shopxo.cache_common_home_nav_footer_key'), null);

            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 获取前端顶部右侧导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function HomeHavTopRight($params = [])
    {
        $common_cart_total = 0;
        $common_message_total = -1;
        if(!empty($params['user']))
        {
            // 购物车商品总数
            $common_cart_total = BuyService::UserCartTotal(['user'=>$params['user']]);

            // 未读消息总数
            $message_params = ['user'=>$params['user'], 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user'];
            $common_message_total = MessageService::UserMessageTotal($message_params);
            $common_message_total = ($common_message_total <= 0) ? -1 : $common_message_total;
        }
        
        // 列表
        $data = [
            [
                'name'      => '个人中心',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'am-icon-user',
                'url'       => MyUrl('index/user/index'),
                'items'     => [],
            ],
            [
                'name'      => '我的商城',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'am-icon-cube',
                'url'       => '',
                'items'     => [
                    [
                        'name'  => '我的订单',
                        'url'   => MyUrl('index/order/index'),
                    ],
                ],
            ],
            [
                'name'      => '我的收藏',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'am-icon-heart',
                'url'       => '',
                'items'     => [
                    [
                        'name'  => '商品收藏',
                        'url'   => MyUrl('index/userfavor/goods'),
                    ],
                ],
            ],
            [
                'name'      => '购物车',
                'is_login'  => 1,
                'badge'     => $common_cart_total,
                'icon'      => 'am-icon-shopping-cart',
                'url'       => MyUrl('index/cart/index'),
                'items'     => [],
            ],
            [
                'name'      => '消息',
                'is_login'  => 1,
                'badge'     => $common_message_total,
                'icon'      => 'am-icon-bell',
                'url'       => MyUrl('index/message/index'),
                'items'     => [],
            ],
        ];

        // 顶部小导航右侧钩子
        $hook_name = 'plugins_service_header_navigation_top_right_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户中心资料修改展示字段
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UsersPersonalShowFieldList($params = [])
    {
        // is_ext       扩展数据 1, key不存在用户字段中可使用该扩展
        // name         显示名称
        // value        扩展自定义值
        // tips         html提示操作内容
        $data = [
            'avatar'            =>  [
                'name' => '头像',
                'tips' => '<a href="javascript:;" data-am-modal="{target:\'#user-avatar-popup\'}">修改</a>'
            ],
            'nickname'          =>  [
                'name' => '昵称'
            ],
            'gender_text'       =>  [
                'name' => '性别'
            ],
            'birthday_text'     =>  [
                'name' => '生日'
            ],
            'mobile_security'   =>  [
                'name' => '手机号码',
                'tips' => '<a href="'.MyUrl('index/safety/mobileinfo').'">修改</a>'
            ],
            'email_security'    =>  [
                'name' => '电子邮箱',
                'tips' => '<a href="'.MyUrl('index/safety/emailinfo').'">修改</a>'
            ],
            'add_time_text'     =>  [
                'name' => '注册时间'
            ],
            'upd_time_text'     =>  [
                'name' => '更新时间'
            ],
        ];

        // 用户中心资料修改展示字段钩子
        $hook_name = 'plugins_service_users_personal_show_field_list_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户安全项列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UsersSafetyPanelList($params = [])
    {
        $data = [
            [
                'title'     =>  '登录密码',
                'msg'       =>  '互联网存在被盗风险，建议您定期更改密码以保护安全。',
                'url'       =>  MyUrl('index/safety/loginpwdinfo'),
                'type'      =>  'loginpwd',
            ],
            [
                'title'     =>  '手机号码',
                'no_msg'    =>  '您还没有绑定手机号码',
                'ok_msg'    =>  '已绑定手机 #accounts#',
                'tips'      =>  '可用于登录，密码找回，账户安全管理校验，接受账户提醒通知。',
                'url'       =>  MyUrl('index/safety/mobileinfo'),
                'type'      =>  'mobile',
            ],
            [
                'title'     =>  '电子邮箱',
                'no_msg'    =>  '您还没有绑定电子邮箱',
                'ok_msg'    =>  '已绑定电子邮箱 #accounts#',
                'tips'      =>  '可用于登录，密码找回，账户安全管理校验，接受账户提醒邮件。',
                'url'       =>  MyUrl('index/safety/emailinfo'),
                'type'      =>  'email',
            ],
        ];

        // 用户安全项列表钩子
        $hook_name = 'plugins_service_users_safety_panel_list_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户中心左侧菜单
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UsersCenterLeftList($params = [])
    {
        // name        名称
        // url         页面地址
        // is_show     是否显示（0否, 1是）
        // contains    包含的子页面（包括自身） 如用户中心（index 组, user 控制器, index 方法 [ indexuserindex ]）
        // icon        icon类
        // item        二级数据
        // is_system   是否系统内置菜单（0否, 1是）扩展数据可空或0

        // 菜单列表
        $data = [
            'center' => [
                'name'      =>  '个人中心',
                'url'       =>  MyUrl('index/user/index'),
                'is_show'   =>  1,
                'contains'  =>  ['indexuserindex'],
                'icon'      =>  'am-icon-home',
                'is_system' =>  1,
            ],
            'business' => [
                'name'      =>  '业务管理',
                'is_show'   =>  1,
                'icon'      =>  'am-icon-cube',
                'is_system' =>  1,
                'item'      =>  [
                    [
                        'name'      =>  '订单管理',
                        'url'       =>  MyUrl('index/order/index'),
                        'is_show'   =>  1,
                        'contains'  =>  ['indexorderindex', 'indexorderdetail', 'indexordercomments'],
                        'icon'      =>  'am-icon-th-list',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '订单售后',
                        'url'       =>  MyUrl('index/orderaftersale/index'),
                        'is_show'   =>  1,
                        'contains'  =>  ['indexorderaftersaleindex', 'indexorderaftersaleaftersale'],
                        'icon'      =>  'am-icon-puzzle-piece',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '我的收藏',
                        'url'       =>  MyUrl('index/userfavor/goods'),
                        'contains'  =>  ['indexuserfavorgoods'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-heart-o',
                        'is_system' =>  1,
                    ],
                ]
            ],
            'property' => [
                'name'      =>  '财产中心',
                'is_show'   =>  1,
                'icon'      =>  'am-icon-trophy',
                'is_system' =>  1,
                'item'      =>  [
                    [
                        'name'      =>  '我的积分',
                        'url'       =>  MyUrl('index/userintegral/index'),
                        'contains'  =>  ['indexuserintegralindex'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-fire',
                        'is_system' =>  1,
                    ],
                ]
            ],
            'base' => [
                'name'      =>  '资料管理',
                'is_show'   =>  1,
                'icon'      =>  'am-icon-user',
                'is_system' =>  1,
                'item'      =>  [
                    [
                        'name'      =>  '个人资料',
                        'url'       =>  MyUrl('index/personal/index'),
                        'contains'  =>  ['indexpersonalindex', 'indexpersonalsaveinfo'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-gear',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '我的地址',
                        'url'       =>  MyUrl('index/useraddress/index'),
                        'contains'  =>  ['indexuseraddressindex', 'indexuseraddresssaveinfo'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-street-view',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '安全设置',
                        'url'       =>  MyUrl('index/safety/index'),
                        'contains'  =>  ['indexsafetyindex', 'indexsafetyloginpwdinfo', 'indexsafetymobileinfo', 'indexsafetynewmobileinfo', 'indexsafetyemailinfo', 'indexsafetynewemailinfo'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-user-secret',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '我的消息',
                        'url'       =>  MyUrl('index/message/index'),
                        'contains'  =>  ['indexmessageindex'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-bell-o',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '我的足迹',
                        'url'       =>  MyUrl('index/usergoodsbrowse/index'),
                        'contains'  =>  ['indexusergoodsbrowseindex'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-lastfm',
                        'is_system' =>  1,
                    ],
                    [
                        'name'      =>  '问答/留言',
                        'url'       =>  MyUrl('index/answer/index'),
                        'contains'  =>  ['indexanswerindex'],
                        'is_show'   =>  1,
                        'icon'      =>  'am-icon-question',
                        'is_system' =>  1,
                    ],
                ]
            ],
            'logout' => [
                'name'      =>  '安全退出',
                'url'       =>  MyUrl('index/user/logout'),
                'contains'  =>  ['indexuserlogout'],
                'is_show'   =>  1,
                'icon'      =>  'am-icon-power-off',
                'is_system' =>  1,
            ],
        ];

        // 用户中心左侧菜单钩子
        $hook_name = 'plugins_service_users_center_left_menu_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 获取网站底部导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function BottomNavigation($params = [])
    {
        $common_cart_total = 0;
        if(!empty($params['user']))
        {
            // 购物车商品总数
            $common_cart_total = BuyService::UserCartTotal(['user'=>$params['user']]);
        }
        
        // 列表
        $data = [
            [
                'name'      => '首页',
                'is_login'  => 0,
                'badge'     => null,
                'icon'      => 'nav-icon-home',
                'only_tag'  => 'indexindex',
                'url'       => __MY_URL__,
            ],
            [
                'name'      => '分类',
                'is_login'  => 0,
                'badge'     => null,
                'icon'      => 'nav-icon-category',
                'only_tag'  => 'categoryindex',
                'url'       => MyUrl('index/category/index'),
            ],
            [
                'name'      => '购物车',
                'is_login'  => 1,
                'badge'     => $common_cart_total,
                'icon'      => 'nav-icon-cart',
                'only_tag'  => 'cartindex',
                'url'       => MyUrl('index/cart/index'),
            ],
            [
                'name'      => '我的',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'nav-icon-user',
                'only_tag'  => 'userindex',
                'url'       => MyUrl('index/user/index'),
            ],
        ];

        // 网站底部导航
        $hook_name = 'plugins_service_bottom_navigation_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户中心基础信息中 mini 导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UserCenterMiniNavigation($params = [])
    {
        if(empty($params['user']))
        {
            $user_order_count = 0;
            $user_goods_favor_count = 0;
            $user_goods_browse_count = 0;
            $user_integral = 0;
        } else {
            // 订单总数
            $where = ['user_id'=>$params['user']['id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
            $user_order_count = OrderService::OrderTotal($where);

            // 商品收藏总数
            $where = ['user_id'=>$params['user']['id']];
            $user_goods_favor_count = GoodsService::GoodsFavorTotal($where);

            // 我的足迹总数
            $where = ['user_id'=>$params['user']['id']];
            $user_goods_browse_count = GoodsService::GoodsBrowseTotal($where);

            // 用户积分
            $user_integral = isset($params['user']['integral']) ? $params['user']['integral'] : 0;
        }
        
        // 列表
        $data = [
            [
                'name'      => '订单总数',
                'value'     => $user_order_count,
                'url'       => MyUrl('index/order/index'),
            ],
            [
                'name'      => '商品收藏',
                'value'     => $user_goods_favor_count,
                'url'       => MyUrl('index/userfavor/goods'),
            ],
            [
                'name'      => '我的足迹',
                'value'     => $user_goods_browse_count,
                'url'       => MyUrl('index/usergoodsbrowse/index'),
            ],
            [
                'name'      => '我的积分',
                'value'     => $user_integral,
                'url'       => MyUrl('index/userintegral/index'),
            ],
        ];

        // 用户中心基础信息中mini导航
        $hook_name = 'plugins_service_user_center_mini_navigation_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }
}
?>