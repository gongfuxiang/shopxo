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
namespace app\api\controller;

use think\facade\Db;
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\RegionService;
use app\service\GoodsService;
use app\service\WarehouseGoodsService;

/**
 * 开发测试
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Devtest extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 附件初始化 1.6升级运行
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        $path_all = [
            'video' => __MY_ROOT_PUBLIC__.'static/upload/video/',
            'file' => __MY_ROOT_PUBLIC__.'static/upload/file/',
            'image' => __MY_ROOT_PUBLIC__.'static/upload/images/',
        ];
        foreach($path_all as $type=>$path)
        {
            $path = GetDocumentRoot() . (substr($path, 0, 1) == "/" ? "":"/") . $path;
            $handle = opendir($path);
            while(false !== ($file = readdir($handle)))
            {
                if($file != 'index.html' && $file != '.' && $file != '..' && substr($file, 0, 1) != '.')
                {
                    $ret = AttachmentService::AttachmentDiskFilesToDb($file);
                    if(isset($ret['msg']))
                    {
                        echo $ret['msg'];
                    }
                }
            }
        }
    }

    /**
     * 订单地址拆分到新的表，1.7升级1.8升级运行
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-12-13
     * @desc    description
     */
    public function OrderAddress()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');
        
        // 状态
        $success = 0;
        $fail = 0;

        // 获取数据
        // 一次处理100条
        $prefix = MyConfig('database.connections.mysql.prefix');
        $field = 'id, user_id, receive_address_id, receive_name, receive_tel, receive_province, receive_city, receive_county, receive_address';
        $sql = 'SELECT '.$field.' FROM '.$prefix.'order WHERE `id` NOT IN (SELECT `order_id` FROM '.$prefix.'order_address) LIMIT 500';
        $result = Db::query($sql);
        if(!empty($result))
        {
            foreach($result as $v)
            {
                $province_name = RegionService::RegionName($v['receive_province']);
                $city_name = RegionService::RegionName($v['receive_city']);
                $county_name = RegionService::RegionName($v['receive_county']);
                $data = [
                    'order_id'          => $v['id'],
                    'user_id'           => $v['user_id'],
                    'address_id'        => $v['receive_address_id'],
                    'name'              => $v['receive_name'],
                    'tel'               => $v['receive_tel'],
                    'province'          => $v['receive_province'],
                    'city'              => $v['receive_city'],
                    'county'            => $v['receive_county'],
                    'address'           => $v['receive_address'],
                    'province_name'     => empty($province_name) ? '' : $province_name,
                    'city_name'         => empty($city_name) ? '' : $city_name,
                    'county_name'       => empty($county_name) ? '' : $county_name,
                    'add_time'          => time(),
                ];
                if(Db::name('OrderAddress')->insert($data))
                {
                    $success++;
                } else {
                    $fail++;
                }
            }
        }
        echo 'count:'.count($result).', success:'.$success.', fail:'.$fail;
    }

    /**
     * 商品库存初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-29
     * @desc    description
     */
    public function GoodsInventoryHandle()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        $warehouse_id = 1;
        $warehouse = Db::name('Warehouse')->where(['id'=>$warehouse_id])->find();
        if(empty($warehouse))
        {
            $w_data = [
                'id'            => $warehouse_id,
                'name'          => '默认仓库',
                'is_default'    => 1,
                'add_time'      => time(),
            ];
            $warehouse_id = Db::name('Warehouse')->insertGetId($w_data);
        }

        // 状态
        $success = 0;
        $fail = 0;

        // 获取数据
        // 一次处理100条
        $prefix = MyConfig('database.connections.mysql.prefix');
        $field = 'id as goods_id';
        $sql = 'SELECT '.$field.' FROM `'.$prefix.'goods` WHERE `id` NOT IN (SELECT `goods_id` FROM `'.$prefix.'warehouse_goods` WHERE `warehouse_id`='.$warehouse_id.') ORDER BY `id` ASC LIMIT 500';
        $result = Db::query($sql);
        if(!empty($result))
        {
            foreach($result as $rv)
            {
                $goods_id = $rv['goods_id'];

                // 获取商品规格
                $res = GoodsService::GoodsSpecificationsActual($goods_id);
                $inventory_spec = [];
                if(!empty($res['value']) && is_array($res['value']))
                {
                    $inventory_arr = [];
                    $inventory_temp = Db::name('GoodsSpecBase')->where(['id'=>array_column($res['value'], 'base_id')])->field('id,inventory')->select()->toArray();
                    if(!empty($inventory_temp))
                    {
                        foreach($inventory_temp as $rv)
                        {
                            $inventory_arr[$rv['id']] = $rv['inventory'];
                        }
                        foreach($res['value'] as &$vv)
                        {
                            $vv['inventory'] = isset($inventory_arr[$vv['base_id']]) ? $inventory_arr[$vv['base_id']] : 0;
                        }
                    }
                    
                    // 获取当前配置的库存
                    $spec = array_column($res['value'], 'value');
                    foreach($spec as $k=>$v)
                    {
                        $arr = explode(GoodsService::$goods_spec_to_string_separator, $v);
                        $inventory_spec[] = [
                            'name'      => implode(' / ', $arr),
                            'spec'      => json_encode(WarehouseGoodsService::GoodsSpecMuster($v, $res['title']), JSON_UNESCAPED_UNICODE),
                            'md5_key'   => md5(implode('', $arr)),
                            'inventory' => isset($res['value'][$k]['inventory']) ? $res['value'][$k]['inventory'] : 0,
                        ];
                    }
                } else {
                    // 没有规格则处理默认规格 default
                    $str = 'default';
                    $inventory_spec[] = [
                        'name'      => '默认规格',
                        'spec'      => $str,
                        'md5_key'   => md5($str),
                        'inventory' => Db::name('GoodsSpecBase')->where(['goods_id'=>$goods_id])->value('inventory'),
                    ];
                }

                // 添加关联商品
                $warehouse_goods_id = Db::name('WarehouseGoods')->where(['warehouse_id'=>$warehouse_id, 'goods_id'=>$goods_id])->value('id');
                $inventory_total = array_sum(array_column($inventory_spec, 'inventory'));
                if(empty($warehouse_goods_id))
                {
                    $w_goods = [
                        'warehouse_id'  => $warehouse_id,
                        'goods_id'      => $goods_id,
                        'is_enable'     => 1,
                        'inventory'     => $inventory_total,
                        'add_time'      => time(),
                    ];
                    $warehouse_goods_id = Db::name('WarehouseGoods')->insertGetId($w_goods);
                } else {
                    Db::name('WarehouseGoods')->where(['warehouse_id'=>$warehouse_id, 'goods_id'=>$goods_id])->update(['inventory'=>$inventory_total, 'upd_time'=>time()]);
                }
                
                // 库存
                $w_values = [];
                if($warehouse_goods_id > 0)
                {
                    foreach($inventory_spec as $iv)
                    {
                        $w_values[] = [
                            'warehouse_id'          => $warehouse_id,
                            'warehouse_goods_id'    => $warehouse_goods_id,
                            'goods_id'              => $goods_id,
                            'md5_key'               => $iv['md5_key'],
                            'spec'                  => $iv['spec'],
                            'inventory'             => $iv['inventory'],
                            'add_time'              => time(),
                        ];
                    }
                } else {
                    $fail++;
                }        

                // 添加数据
                if(!empty($w_values))
                {
                    // 删除库存关联
                    Db::name('WarehouseGoodsSpec')->where(['warehouse_id'=>$warehouse_id, 'warehouse_goods_id'=>$warehouse_goods_id, 'goods_id'=>$goods_id])->delete();
                    // 写入
                    if(Db::name('WarehouseGoodsSpec')->insertAll($w_values) >= count($w_values))
                    {
                        $success++;
                    }
                } else {
                    $fail++;
                }
            }    
        }

        echo 'success:'.$success.', fail:'.$fail;
    }

    /**
     * 支付日志处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-26
     * @desc    description
     */
    public function PayLogHandle()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 状态
        $success = 0;
        $fail = 0;


        // 获取日志
        $data = Db::name('PayLog')->where(['is_handle'=>0])->limit(0, 500)->select()->toArray();
        if(!empty($data))
        {
            $business_type_list = [
                0 => '默认',
                1 => '订单',
                2 => '充值',
                3 => '提现',
            ];
            foreach($data as $v)
            {
                $upd_data = [
                    'is_handle'     => 1,
                    'business_type' => isset($business_type_list[$v['business_type']]) ? $business_type_list[$v['business_type']] : $v['business_type'],
                    'status'        => (empty($v['pay_price']) || $v['pay_price'] <= 0) ? 0 : 1,
                ];

                // 未支付则关闭
                if(empty($v['pay_price']) || $v['pay_price'] <= 0)
                {
                    $upd_data['close_time'] = time();
                }

                // 支付时间
                if(!empty($v['pay_price']) && $v['pay_price'] > 0)
                {
                    $upd_data['pay_time'] = time();
                }

                // 更新操作
                if(Db::name('PayLog')->where(['is_handle'=>0, 'id'=>$v['id']])->update($upd_data))
                {
                    // 新增关联数据
                    if(DB::name('PayLogValue')->insert([
                        'pay_log_id'    => $v['id'],
                        'business_id'   => $v['order_id'],
                        'business_no'   => '',
                        'add_time'      => $v['add_time'],
                    ]))
                    {
                        $success++;
                    } else {
                        $fail++;
                    }
                } else {
                    $fail++;
                }
            }
        }
        echo 'count:'.count($data).', success:'.$success.', fail:'.$fail;
    }

    /**
     * 退款日志处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-26
     * @desc    description
     */
    public function RefundLogHandle()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 状态
        $success = 0;
        $fail = 0;


        // 获取日志
        $data = Db::name('RefundLog')->where(['is_handle'=>0])->limit(0, 500)->select()->toArray();
        if(!empty($data))
        {
            $business_type_list = [
                0 => '默认',
                1 => '订单',
                2 => '充值',
                3 => '提现',
            ];
            foreach($data as $v)
            {
                $upd_data = [
                    'is_handle'     => 1,
                    'business_type' => isset($business_type_list[$v['business_type']]) ? $business_type_list[$v['business_type']] : $v['business_type'],
                ];

                // 更新操作
                if(Db::name('RefundLog')->where(['is_handle'=>0, 'id'=>$v['id']])->update($upd_data))
                {
                    $success++;
                } else {
                    $fail++;
                }
            }
        }
        echo 'count:'.count($data).', success:'.$success.', fail:'.$fail;
    }

    /**
     * 消息数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-26
     * @desc    description
     */
    public function MessageHandle()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 状态
        $success = 0;
        $fail = 0;


        // 获取日志
        $data = Db::name('Message')->where(['is_handle'=>0])->limit(0, 500)->select()->toArray();
        if(!empty($data))
        {
            $business_type_list = [
                0 => '默认',
                1 => '订单',
                2 => '充值',
                3 => '提现',
            ];
            foreach($data as $v)
            {
                $upd_data = [
                    'is_handle'     => 1,
                    'business_type' => isset($business_type_list[$v['business_type']]) ? $business_type_list[$v['business_type']] : $v['business_type'],
                ];

                // 更新操作
                if(Db::name('Message')->where(['is_handle'=>0, 'id'=>$v['id']])->update($upd_data))
                {
                    $success++;
                } else {
                    $fail++;
                }
            }
        }
        echo 'count:'.count($data).', success:'.$success.', fail:'.$fail;
    }

    /**
     * 品牌分类数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-01
     * @desc    description
     */
    public function BrandCategoryHandle()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 状态
        $success = 0;
        $fail = 0;

        // 获取品牌列表
        $data = Db::name('Brand')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as $v)
            {
                if(!empty($v['brand_category_id']))
                {
                    if(Db::name('BrandCategoryJoin')->where(['brand_id'=>$v['id']])->count() <= 0)
                    {
                        // 删除数据
                        Db::name('BrandCategoryJoin')->where(['brand_id'=>$v['id']])->delete();

                        // 添加数据
                        $temp_data = [
                            'brand_id'          => $v['id'],
                            'brand_category_id' => $v['brand_category_id'],
                            'add_time'          => time(),
                        ];
                        if(Db::name('BrandCategoryJoin')->insertGetId($temp_data) > 0)
                        {
                            $success++;
                        } else {
                            $fail++;
                        }
                    }
                }
            }
        }
        echo 'success:'.$success.', fail:'.$fail;
    }

    /**
     * 语言翻译生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-24
     * @desc    description
     */
    public function Fanyi()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 需要翻译的语言、参考 config/lang.php文件
        $to = 'swe';
        $to_name = '瑞典语';

        // 待翻译的目录
        $arr = [
            APP_PATH.'lang'.DS,
            APP_PATH.'admin'.DS.'lang'.DS,
            APP_PATH.'index'.DS.'lang'.DS,
            APP_PATH.'api'.DS.'lang'.DS,
        ];

        // 获取数据
        $zh_data = [];
        foreach($arr as $dir)
        {
            if(!is_dir($dir))
            {
                continue;
            }
            $zh_file = $dir.'zh.php';
            if(!file_exists($zh_file))
            {
                continue;
            }
            $temp = require $zh_file;
            $zh_data = array_merge($zh_data, $this->FanyiData($temp));
        }

        // 翻译数据 并 生成数据
        $params = [];
        $vers = get_class_vars(get_class($this));
        foreach($vers as $k=>$v)
        {
            if(property_exists($this, $k))
            {
                $params[$k] = $this->$k;
            }
        }
        $params['data_request']['to'] = $to;
        $params['data_request']['q'] = implode("\n", $zh_data);
        //$params['data_request']['q'] = "你好\n我是龚";
        $fanyi = PluginsControlCall('multilingual', 'index', 'fanyi', 'index', $params, 1);
        if(isset($fanyi['code']) && $fanyi['code'] != 0)
        {
            die($fanyi['msg']);
        }
        $fanyi_data = (!empty($fanyi['data']) && !empty($fanyi['data']['trans_result'])) ? $fanyi['data']['trans_result'] : [];
        if(empty($fanyi_data))
        {
            die('没有翻译数据');
        }

        // 替换数据
        $search = array_map(function($item)
            {
                return "'".$item."'";
            }, array_column($fanyi_data, 'src'));
        $replace = array_map(function($item)
            {
                return "'".str_replace("'", '', $item)."'";
            }, array_column($fanyi_data, 'dst'));
        // 加入标题名称
        $search[] = '公共语言包-中文';
        $search[] = '模块语言包-中文';
        $replace[] = '公共语言包-'.$to_name;
        $replace[] = '模块语言包-'.$to_name;

        // 开始生成文件并替换数据
        $success = 0;
        $fail = 0;
        foreach($arr as $dir)
        {
            // 复制文件
            $zh_file = $dir.'zh.php';
            if(!file_exists($zh_file))
            {
                continue;
            }
            $to_file = $dir.$to.'.php';
            if(!\base\FileUtil::CopyFile($zh_file, $to_file, true))
            {
                continue;
            }
            // 生成文件并替换
            $content = file_get_contents($to_file);
            if(file_put_contents($to_file, str_replace($search, $replace, $content)) !== false)
            {
                $success++;
            } else {
                $fail++;
            }
        }
        die('success:'.$success.', fail:'.$fail);
    }
    /**
     * 插件语言翻译生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-24
     * @desc    description
     */
    public function PluginsFanyi()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 需要翻译的语言、参考 config/lang.php文件
        $to = 'en';
        $to_name = '英文';
        $plugins = 'erp';

        // 待翻译的目录
        $arr = [
            APP_PATH.'plugins'.DS.$plugins.DS.'lang'.DS,
            APP_PATH.'plugins'.DS.$plugins.DS.'lang'.DS.'admin'.DS,
            APP_PATH.'plugins'.DS.$plugins.DS.'lang'.DS.'index'.DS,
        ];

        // 获取数据
        $zh_data = [];
        foreach($arr as $dir)
        {
            if(!is_dir($dir))
            {
                continue;
            }
            $zh_file = $dir.'zh.php';
            if(!file_exists($zh_file))
            {
                continue;
            }
            $temp = require $zh_file;
            $zh_data = array_merge($zh_data, $this->FanyiData($temp));
        }

        // 翻译数据 并 生成数据
        $params = [];
        $vers = get_class_vars(get_class($this));
        foreach($vers as $k=>$v)
        {
            if(property_exists($this, $k))
            {
                $params[$k] = $this->$k;
            }
        }
        $params['data_request']['to'] = $to;
        $params['data_request']['q'] = implode("\n", $zh_data);
        //$params['data_request']['q'] = "你好\n我是龚";
        $fanyi = PluginsControlCall('multilingual', 'index', 'fanyi', 'index', $params, 1);
        if(isset($fanyi['code']) && $fanyi['code'] != 0)
        {
            die($fanyi['msg']);
        }
        $fanyi_data = (!empty($fanyi['data']) && !empty($fanyi['data']['trans_result'])) ? $fanyi['data']['trans_result'] : [];
        if(empty($fanyi_data))
        {
            die('没有翻译数据');
        }

        // 替换数据
        $search = array_map(function($item)
            {
                return "'".$item."'";
            }, array_column($fanyi_data, 'src'));
        $replace = array_map(function($item)
            {
                return "'".str_replace("'", '', $item)."'";
            }, array_column($fanyi_data, 'dst'));
        // 加入标题名称
        $search[] = '语言包-中文';
        $replace[] = '语言包-'.$to_name;

        // 开始生成文件并替换数据
        $success = 0;
        $fail = 0;
        foreach($arr as $dir)
        {
            // 复制文件
            $zh_file = $dir.'zh.php';
            if(!file_exists($zh_file))
            {
                continue;
            }
            $to_file = $dir.$to.'.php';
            if(!\base\FileUtil::CopyFile($zh_file, $to_file, true))
            {
                continue;
            }
            // 生成文件并替换
            $content = file_get_contents($to_file);
            if(file_put_contents($to_file, str_replace($search, $replace, $content)) !== false)
            {
                $success++;
            } else {
                $fail++;
            }
        }
        die('success:'.$success.', fail:'.$fail);
    }
    /**
     * 语言翻译生成自定义文件片段
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-24
     * @desc    description
     */
    public function MyFanyi()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }
        die('禁止访问');

        // 需要翻译的语言、参考 config/lang.php文件
        $to = 'cht';
        $to_name = '瑞典语';

        // 待翻译的目录
        $arr = [
            APP_PATH.'lang'.DS,
        ];

        // 获取数据
        $zh_data = [];
        foreach($arr as $dir)
        {
            if(!is_dir($dir))
            {
                continue;
            }
            $zh_file = $dir.'zh-new.php';
            if(!file_exists($zh_file))
            {
                continue;
            }
            $temp = require $zh_file;
            $zh_data = array_merge($zh_data, $this->FanyiData($temp));
        }

        // 翻译数据 并 生成数据
        $params = [];
        $vers = get_class_vars(get_class($this));
        foreach($vers as $k=>$v)
        {
            if(property_exists($this, $k))
            {
                $params[$k] = $this->$k;
            }
        }
        $params['data_request']['to'] = $to;
        $params['data_request']['q'] = implode("\n", $zh_data);
        //$params['data_request']['q'] = "你好\n我是龚";
        $fanyi = PluginsControlCall('multilingual', 'index', 'fanyi', 'index', $params, 1);
        if(isset($fanyi['code']) && $fanyi['code'] != 0)
        {
            die($fanyi['msg']);
        }
        $fanyi_data = (!empty($fanyi['data']) && !empty($fanyi['data']['trans_result'])) ? $fanyi['data']['trans_result'] : [];
        if(empty($fanyi_data))
        {
            die('没有翻译数据');
        }

        // 替换数据
        $search = array_map(function($item)
            {
                return "'".$item."'";
            }, array_column($fanyi_data, 'src'));
        $replace = array_map(function($item)
            {
                return "'".str_replace("'", '', $item)."'";
            }, array_column($fanyi_data, 'dst'));
        // 加入标题名称
        $search[] = '公共语言包-中文';
        $search[] = '模块语言包-中文';
        $replace[] = '公共语言包-'.$to_name;
        $replace[] = '模块语言包-'.$to_name;

        // 开始生成文件并替换数据
        $success = 0;
        $fail = 0;
        foreach($arr as $dir)
        {
            // 复制文件
            $zh_file = $dir.'zh-new.php';
            if(!file_exists($zh_file))
            {
                continue;
            }
            $to_file = $dir.$to.'-new.php';
            if(!\base\FileUtil::CopyFile($zh_file, $to_file, true))
            {
                continue;
            }
            // 生成文件并替换
            $content = file_get_contents($to_file);
            if(file_put_contents($to_file, str_replace($search, $replace, $content)) !== false)
            {
                $success++;
            } else {
                $fail++;
            }
        }
        die('success:'.$success.', fail:'.$fail);
    }
    // 翻译数据递归获取
    public function FanyiData($data)
    {
        $result = [];
        if(!empty($data) && is_array($data))
        {
            foreach($data as $v)
            {
                if(!empty($v))
                {
                    if(is_array($v))
                    {
                        $result = array_merge($result, $this->FanyiData($v));
                    } else {
                        if(!ctype_alnum(str_replace(['%', 'x ', ' ', '-', '_', ',', '.', ':',], '', $v)) && !is_numeric($v))
                        {
                            $result[md5($v)] = $v;
                        }
                    }
                }
            }
        }
        return $result;
    }

    // 地区导入
    public function Region()
    {
        die('禁止访问');
        // $save_file = ROOT.'region_text.text';
        // excel读取
        // // 获取导入数据
        // $file = ROOT.'KEN_ALL_ROME.xlsx';
        // $ret = (new \base\Excel())->Import($file);
        // if($ret['code'] != 0)
        // {
        //     die($ret['msg']);
        // }
        // if(empty($ret['data']) || empty($ret['data']['title']) || empty($ret['data']['data']))
        // {
        //     die('导入数据为空');
        // }
        // // 数据组合
        // $data = [];
        // foreach(array_chunk($ret['data']['data'], 1000) as $v)
        // {
        //     foreach($v as $vs)
        //     {
        //         if(!array_key_exists($vs[1], $data))
        //         {
        //             $data[$vs[1]] = [
        //                 'name'  =>$vs[1],
        //                 'code'  => '',
        //                 'item'  => [],
        //             ];
        //         }
        //         $temp = str_replace([' ', '　'], '', $vs[2]);
        //         if(!array_key_exists($temp, $data[$vs[1]]['item']))
        //         {
        //             $data[$vs[1]]['item'][$temp] = [
        //                 'name'  => $temp,
        //                 'code'  => '',
        //                 'item'  => [],
        //             ];
        //         }
        //         $temp2 = explode('（', $vs[3]);
        //         if(!array_key_exists($temp2[0], $data[$vs[1]]['item'][$temp]['item']))
        //         {
        //             $data[$vs[1]]['item'][$temp]['item'][$temp2[0]] = [
        //                 'name'  => $temp2[0],
        //                 'code'  => $vs[0],
        //             ];
        //         }
        //     }
        // }
        // file_put_contents($save_file, json_encode($data, JSON_UNESCAPED_UNICODE));

        // echo 'ok';
        // echo '<pre>';
        // print_r($data);

        // 导入
        // $data = json_decode(file_get_contents($save_file), true);
        // // echo '<pre>';
        // // print_r($data);
        // foreach(array_chunk($data, 50) as $v)
        // {
        //     foreach($v as $v1)
        //     {
        //         $insert = [
        //             'name'      => $v1['name'],
        //             'code'      => empty($v1['code']) ? '' : $v1['code'],
        //             'level'     => 1,
        //             'add_time'  => time(),
        //         ];
        //         $id1 = Db::name('Region')->insertGetId($insert);
        //         if($id1 > 0 && !empty($v1['item']))
        //         {
        //             foreach($v1['item'] as $v2)
        //             {
        //                 $insert = [
        //                     'pid'       => $id1,
        //                     'name'      => $v2['name'],
        //                     'code'      => empty($v2['code']) ? '' : $v2['code'],
        //                     'level'     => 2,
        //                     'add_time'  => time(),
        //                 ];
        //                 $id2 = Db::name('Region')->insertGetId($insert);
        //                 if($id2 > 0 && !empty($v2['item']))
        //                 {
        //                     $all = [];
        //                     foreach($v2['item'] as $v3)
        //                     {
        //                         $all[] = [
        //                             'pid'       => $id2,
        //                             'name'      => $v3['name'],
        //                             'code'      => empty($v3['code']) ? '' : $v3['code'],
        //                             'level'     => 3,
        //                             'add_time'  => time(),
        //                         ];
        //                     }
        //                     Db::name('Region')->insertAll($all);
        //                 }
        //             }
        //         }
        //     }
        // }
        // echo 'ok';
        
    }
}
?>