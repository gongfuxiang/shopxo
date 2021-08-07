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
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\UserAddressService;
use app\service\ResourcesService;
use app\service\PaymentService;
use app\service\ConfigService;
use app\service\OrderSplitService;
use app\service\WarehouseGoodsService;
use app\service\OrderCurrencyService;

/**
 * 购买服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BuyService
{
    /**
     * 购物车添加/更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'stock',
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 获取商品
        $goods_id = intval($params['goods_id']);
        $goods = Db::name('Goods')->where(['id'=>$goods_id, 'is_shelves'=>1, 'is_delete_time'=>0])->find();
        if(empty($goods))
        {
            return DataReturn('商品不存在或已删除', -2);
        }

        // 无封面图片
        if(empty($goods['images']))
        {
            $goods['images'] = ResourcesService::AttachmentPathHandle(GoodsService::GoodsImagesCoverHandle($goods_id));
        }

        // 是否支持购物车操作
        $ret = GoodsService::IsGoodsSiteTypeConsistent($goods_id, $goods['site_type']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 规格处理
        $spec = self::GoodsSpecificationsHandle($params);

        // 获取商品基础信息
        $goods_base = GoodsService::GoodsSpecDetail(['id'=>$goods_id, 'spec'=>$spec]);
        if($goods_base['code'] != 0)
        {
            return $goods_base;
        }

        // 是否存在规格
        if(!empty($spec))
        {
            // 获取商品规格图片
            $images = self::BuyGoodsSpecImages($goods_id, $spec);
            if(!empty($images))
            {
                $goods['images'] = $images;
                $goods['images_old'] = ResourcesService::AttachmentPathViewHandle($images);
            }

            // 从规格获取库存
            $base = GoodsService::GoodsSpecDetail(['id'=>$goods_id, 'spec'=>$spec]);
            if($base['code'] == 0)
            {
                // 规格库存赋值
                $goods['inventory'] = $base['data']['spec_base']['inventory'];
            } else {
                return $base;
            }
        }


        // 数量
        $stock = ($goods['buy_max_number'] > 0 && $params['stock'] > $goods['buy_max_number']) ? $goods['buy_max_number'] : $params['stock'];

        // 库存
        if($stock > $goods['inventory'])
        {
            return DataReturn('库存不足', -1);
        }

        // 添加购物车
        $data = [
            'user_id'       => $params['user']['id'],
            'goods_id'      => $goods_id,
            'title'         => $goods['title'],
            'images'        => $goods['images'],
            'original_price'=> $goods_base['data']['spec_base']['original_price'],
            'price'         => $goods_base['data']['spec_base']['price'],
            'stock'         => $stock,
            'spec'          => empty($spec) ? '' : json_encode($spec, JSON_UNESCAPED_UNICODE),
        ];

        // 存在则更新
        $where = ['user_id'=>$data['user_id'], 'goods_id'=>$data['goods_id'], 'spec'=>$data['spec']];
        $temp = Db::name('Cart')->where($where)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if(Db::name('Cart')->insertGetId($data) > 0)
            {
                return DataReturn('加入成功', 0, self::UserCartTotal($params));
            }
        } else {
            $data['upd_time'] = time();
            $data['stock'] += $temp['stock'];
            if($data['stock'] > $goods['inventory'])
            {
                $data['stock'] = $goods['inventory'];
            }
            if($goods['buy_max_number'] > 0 && $data['stock'] > $goods['buy_max_number'])
            {
                $data['stock'] = $goods['buy_max_number'];
            }
            if(Db::name('Cart')->where($where)->update($data))
            {
                return DataReturn('加入成功', 0, self::UserCartTotal($params));
            }
        }
        
        return DataReturn('加入失败', -100);
    }

    /**
     * 商品规格解析
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSpecificationsHandle($params = [])
    {
        $spec = '';
        if(!empty($params['spec']))
        {
            if(!is_array($params['spec']))
            {
                $spec = json_decode(htmlspecialchars_decode($params['spec']), true);
            } else {
                $spec = $params['spec'];
            }
        }
        return empty($spec) ? '' : $spec;
    }

    /**
     * 获取购物车列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where['c.user_id'] = $params['user']['id'];

        $field = 'c.*, g.inventory_unit, g.is_shelves, g.is_delete_time, g.buy_min_number, g.buy_max_number, g.model, g.site_type';
        $data = Db::name('Cart')->alias('c')->leftJoin('goods g', 'g.id=c.goods_id')->where($where)->field($field)->order('c.id desc')->select()->toArray();

        // 数据处理
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 规格
                $v['spec'] = empty($v['spec']) ? null : json_decode($v['spec'], true);

                // 获取商品基础信息
                $goods_base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>$v['spec']]);
                $v['is_invalid'] = 0;
                if($goods_base['code'] == 0)
                {
                    $v['inventory'] = $goods_base['data']['spec_base']['inventory'];
                    $v['price'] = (float) $goods_base['data']['spec_base']['price'];
                    $v['original_price'] = (float) $goods_base['data']['spec_base']['original_price'];
                    $v['spec_weight'] = $goods_base['data']['spec_base']['weight'];
                    $v['spec_coding'] = $goods_base['data']['spec_base']['coding'];
                    $v['spec_barcode'] = $goods_base['data']['spec_base']['barcode'];
                    $v['extends'] = $goods_base['data']['spec_base']['extends'];
                } else {
                    $v['is_invalid'] = 1;
                    $v['inventory'] = 0;
                    $v['spec_weight'] = 0;
                    $v['spec_coding'] = '';
                    $v['spec_barcode'] = '';
                    $v['extends'] = '';
                }

                // 无封面图片
                if(empty($v['images']))
                {
                    $v['images'] = ResourcesService::AttachmentPathHandle(GoodsService::GoodsImagesCoverHandle($v['goods_id']));
                }

                // 基础信息
                $v['goods_url'] = MyUrl('index/goods/index', ['id'=>$v['goods_id']]);
                $v['images_old'] = $v['images'];
                $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                $v['total_price'] = $v['stock']* ((float) $v['price']);
                $v['buy_max_number'] = ($v['buy_max_number'] <= 0) ? $v['inventory']: $v['buy_max_number'];

                // 错误处理
                $v['is_error'] = 0;
                $v['error_msg'] = '';
                if($v['is_delete_time'] != 0)
                {
                    $v['is_error'] = 1;
                    $v['error_msg'] = '商品已作废';
                }
                if(empty($v['error_msg']) && $v['is_invalid'] == 1)
                {
                    $v['is_error'] = 1;
                    $v['error_msg'] = '商品已失效';
                }
                if(empty($v['error_msg']) && $v['is_shelves'] != 1)
                {
                    $v['is_error'] = 1;
                    $v['error_msg'] = '商品已下架';
                }
                if(empty($v['error_msg']) && $v['inventory'] <= 0)
                {
                    $v['is_error'] = 1;
                    $v['error_msg'] = '商品没货了';
                }
                if(empty($v['error_msg']))
                {
                    $ret = GoodsService::IsGoodsSiteTypeConsistent($v['goods_id'], $v['site_type']);
                    if($ret['code'] != 0)
                    {
                        $v['is_error'] = 1;
                        $v['error_msg'] = $ret['msg'];
                    }
                }
            }
        }

        return DataReturn('操作成功', 0, $data);
    }

    /**
     * 购物车删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartDelete($params = [])
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
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 删除
        $where = [
            'id'        => explode(',', $params['id']),
            'user_id'   => $params['user']['id']
        ];
        if(Db::name('Cart')->where($where)->delete())
        {
            return DataReturn('删除成功', 0, self::UserCartTotal($params));
        }
        return DataReturn('删除失败或资源不存在', -100);
    }

    /**
     * 购物车数量保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartStock($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'stock',
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 条件
        $where = [
            'id'        => intval($params['id']),
            'goods_id'  => intval($params['goods_id']),
            'user_id'   => intval($params['user']['id']),
        ];

        // 数量
        $stock = intval($params['stock']);

        // 获取购物车数据
        $cart = Db::name('Cart')->where($where)->select()->toArray();
        if(empty($cart))
        {
            return DataReturn('请先加入购物车', -1);
        }
        $cart[0]['stock'] = $stock;

        // 商品校验
        $ret = self::BuyGoodsCheck(['goods'=>$cart]);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 更新数据
        $data = [
            'stock'     => $stock,
            'upd_time'  => time(),
        ];
        if(Db::name('Cart')->where($where)->update($data))
        {
            return DataReturn('更新成功', 0);
        }
        return DataReturn('更新失败', -100);
    }

    /**
     * 下订单 - 正常购买
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyGoods($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'stock',
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'spec',
                'error_msg'         => '规格参数有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取商品
        $p = [
            'where' => [
                'id'                => intval($params['goods_id']),
                'is_delete_time'    => 0,
                'is_shelves'        => 1,
            ],
            'field' => 'id, id AS goods_id, title, images, inventory_unit, buy_min_number, buy_max_number, model',
        ];
        $ret = GoodsService::GoodsList($p);
        if(empty($ret['data'][0]))
        {
            return DataReturn('资源不存在或已被删除', -10);
        }

        // 规格
        $ret['data'][0]['spec'] = self::GoodsSpecificationsHandle($params);

        // 获取商品基础信息
        $goods_base = GoodsService::GoodsSpecDetail(['id'=>$ret['data'][0]['goods_id'], 'spec'=>$ret['data'][0]['spec']]);
        if($goods_base['code'] == 0)
        {
            $ret['data'][0]['inventory'] = $goods_base['data']['spec_base']['inventory'];
            $ret['data'][0]['price'] = (float) $goods_base['data']['spec_base']['price'];
            $ret['data'][0]['original_price'] = (float) $goods_base['data']['spec_base']['original_price'];
            $ret['data'][0]['spec_weight'] = $goods_base['data']['spec_base']['weight'];
            $ret['data'][0]['spec_coding'] = $goods_base['data']['spec_base']['coding'];
            $ret['data'][0]['spec_barcode'] = $goods_base['data']['spec_base']['barcode'];
            $ret['data'][0]['extends'] = $goods_base['data']['spec_base']['extends'];
        } else {
            return $goods_base;
        }

        // 获取商品规格图片
        if(!empty($ret['data'][0]['spec']))
        {
            $images = self::BuyGoodsSpecImages($ret['data'][0]['goods_id'], $ret['data'][0]['spec']);
            if(!empty($images))
            {
                $ret['data'][0]['images'] = ResourcesService::AttachmentPathViewHandle($images);
                $ret['data'][0]['images_old'] = $images;
            }
        }

        // 数量/小计
        $ret['data'][0]['stock'] = $params['stock'];
        $ret['data'][0]['total_price'] = $params['stock']* ((float) $ret['data'][0]['price']);

        return DataReturn('操作成功', 0, $ret['data']);
    }

    /**
     * 下订单 - 购物车
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyCart($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => '购物车id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取购物车数据
        $params['where'] = [
            'g.is_delete_time'  => 0,
            'g.is_shelves'      => 1,
            'c.id'              => explode(',', $params['ids']),
        ];
        return self::CartList($params);
    }

    /**
     * 获取规格图片
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-02
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @param   [string]       $spec     [图片地址或空字符串]
     */
    public static function BuyGoodsSpecImages($goods_id, $spec)
    {
        if(!empty($spec))
        {
            $data = Db::name('GoodsSpecType')->where(['goods_id'=>$goods_id])->field('name,value')->select()->toArray();
            if(!empty($data))
            {
                $spec_images = [];
                foreach($data as $v)
                {
                    if(!empty($v['value']))
                    {
                        foreach(json_decode($v['value'], true) as $vs)
                        {
                            if(!empty($vs['images']))
                            {
                                $spec_images[$v['name']][$vs['name']] = $vs['images'];
                            }
                        }
                    }
                }
                if(!empty($spec_images))
                {
                    foreach($spec as $v)
                    {
                        if(array_key_exists($v['type'], $spec_images) && array_key_exists($v['value'], $spec_images[$v['type']]))
                        {
                            return $spec_images[$v['type']][$v['value']];
                        }
                    }
                }
            }
        }
        return '';
    }

    /**
     * 下订单购物车删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-12T00:42:49+0800
     * @param   [array]          $params [输入参数]
     */
    public static function BuyCartDelete($params = [])
    {
        if(isset($params['buy_type']) && $params['buy_type'] == 'cart' && !empty($params['ids']))
        {
            Db::name('Cart')->where(['id'=>explode(',', $params['ids'])])->delete();
        }
    }

    /**
     * 根据购买类型获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyTypeGoodsList($params = [])
    {
        if(isset($params['buy_type']))
        {
            switch($params['buy_type'])
            {
                // 正常购买
                case 'goods' :
                    $ret = self::BuyGoods($params);
                    break;

                // 购物车
                case 'cart' :
                    $ret = self::BuyCart($params);
                    break;

                // 默认
                default :
                    $ret = DataReturn('参数有误', -1);
            }
        } else {
            $ret = DataReturn('参数有误', -1);
        }

        // 数据组装
        if($ret['code'] == 0)
        {
            // 商品数据
            $goods = $ret['data'];

            // 站点模式 0销售, 2自提, 4销售+自提, 则其它正常模式
            $user_site_model = isset($params['site_model']) ? intval($params['site_model']) : 0;
            $common_site_type = SystemBaseService::SiteTypeValue();
            $site_model = ($common_site_type == 4) ? $user_site_model : $common_site_type;

            // 商品销售模式
            // 商品小于等于1则使用商品的类型
            if(count($goods) <= 1 && isset($goods[0]) && isset($goods[0]['goods_id']))
            {
                $ret = GoodsService::GoodsSalesModelType($goods[0]['goods_id']);
                $common_site_type = $ret['data'];
                $site_model = ($ret['data'] == 4) ? $user_site_model : $ret['data'];
            }

            // 数据处理
            $address = null;
            $extraction_address = [];

            // 站点模式 - 用户收货地址（未选择则取默认地址）
            // 销售, 销售+自提(指定为销售)
            if($site_model == 0)
            {
                $address_params = [
                    'user'  => $params['user'],
                ];
                if(!empty($params['address_id']))
                {
                    $address_params['where'] = ['id' => $params['address_id']];
                }
                $ads = UserAddressService::UserDefaultAddress($address_params);
                if(!empty($ads['data']))
                {
                    $address = $ads['data'];
                }
            }

            // 自提模式 - 自提点地址
            // 自提, 销售+自提(指定为自提)
            if($site_model == 2)
            {
                $extraction = self::SiteExtractionAddress($params);
                if(!empty($extraction['data']['data_list']))
                {
                    $extraction_address = $extraction['data']['data_list'];
                }
                if(!empty($extraction['data']['default']))
                {
                    $address = $extraction['data']['default'];
                }
            }

            // 订单拆分
            $order_split = OrderSplitService::Run([
                'site_model'            => $site_model,
                'common_site_type'      => $common_site_type,
                'address'               => $address,
                'extraction_address'    => $extraction_address,
                'goods'                 => $goods,
                'params'                => $params,
            ]);
            if($order_split['code'] != 0)
            {
                return $order_split;
            }

            // 订单总计基础信息字段处理
            $base_fields = [
                'total_price'           => 0,
                'actual_price'          => 0,
                'preferential_price'    => 0,
                'increase_price'        => 0,
                'goods_count'           => 0,
                'spec_weight_total'     => 0,
                'buy_count'             => 0,
            ];
            if(!empty($order_split['data']))
            {
                $order_base = array_column($order_split['data'], 'order_base');
                foreach($base_fields as $field=>$value)
                {
                    $base_fields[$field] = array_sum(array_column($order_base, $field));
                }
            }

            // 订单总计基础信息组合
            $base = [
                // 总价
                'total_price'           => $base_fields['total_price'],

                // 订单实际支付金额(已减去优惠金额, 已加上增加金额)
                'actual_price'          => $base_fields['actual_price'],

                // 优惠金额
                'preferential_price'    => $base_fields['preferential_price'],

                // 增加金额
                'increase_price'        => $base_fields['increase_price'],

                // 商品数量
                'goods_count'           => $base_fields['goods_count'],

                // 规格重量总计
                'spec_weight_total'     => $base_fields['spec_weight_total'],

                // 购买总数
                'buy_count'             => $base_fields['buy_count'],

                // 默认地址
                'address'               => $address,

                // 自提地址列表
                'extraction_address'    => $extraction_address,

                // 当前使用的站点模式
                'site_model'            => $site_model,

                // 公共站点模式
                'common_site_type'      => $common_site_type,
            ];

            // 返回数据
            $result = [
                'goods'             => $order_split['data'],
                'base'              => $base,
            ];

            // 生成订单数据处理钩子
            $hook_name = 'plugins_service_buy_handle';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data'          => &$result,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回数据再次处理，防止钩子插件处理不够完善
            $result['base']['total_price'] = ($result['base']['total_price'] <= 0) ? 0.00 : PriceNumberFormat($result['base']['total_price']);
            $result['base']['actual_price'] = ($result['base']['actual_price'] <= 0) ? 0.00 : PriceNumberFormat($result['base']['actual_price']);
            $result['base']['preferential_price'] = ($result['base']['preferential_price'] <= 0) ? 0.00 : PriceNumberFormat($result['base']['preferential_price']);
            $result['base']['increase_price'] = ($result['base']['increase_price'] <= 0) ? 0.00 : PriceNumberFormat($result['base']['increase_price']);

            return DataReturn('操作成功', 0, $result);
        }

        return $ret;
    }

    /**
     * 购买商品校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyGoodsCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods',
                'error_msg'         => '商品信息为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'goods',
                'error_msg'         => '商品信息有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 商品总数
        $count = count($params['goods']);

        // 提交订单则需要存在多个商品的时候校验
        // 是否需要校验商品类型
        $is_check_goods_site_type = (!isset($params['is_buy']) || $params['is_buy'] != 1 ||  ($count > 1 && $params['is_buy'] == 1));

        // 数据校验
        foreach($params['goods'] as $v)
        {
            // 获取商品信息
            $goods = Db::name('Goods')->field('id,title,price,is_shelves,inventory,buy_min_number,buy_max_number,site_type')->find($v['goods_id']);
            if(empty($goods))
            {
                return DataReturn('商品不存在['.$v['title'].']', -1);
            }

            // 基础判断
            if($goods['is_shelves'] != 1)
            {
                return DataReturn('商品已下架['.$goods['title'].']', -1);
            }

            // 限购
            if($goods['buy_min_number'] > 1 && $v['stock'] < $goods['buy_min_number'])
            {
                return DataReturn('低于商品起购数量['.$goods['title'].']['.$v['stock'].'<'.$goods['buy_min_number'].']', -1);
            }
            if($goods['buy_max_number'] > 0 && $v['stock'] > $goods['buy_max_number'])
            {
                return DataReturn('超过商品限购数量['.$goods['title'].']['.$v['stock'].'>'.$goods['buy_max_number'].']', -1);
            }

            // 是否支持购物车操作
            if($is_check_goods_site_type)
            {
                $ret = GoodsService::IsGoodsSiteTypeConsistent($goods['id'], $goods['site_type']);
                if($ret['code'] != 0)
                {
                    return DataReturn($ret['msg'].'['.$v['title'].']', $ret['code']);
                }
            }

            // 规格
            $goods_base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>isset($v['spec']) ? $v['spec'] : []]);
            if($goods_base['code'] == 0)
            {
                $goods['price'] = $goods_base['data']['spec_base']['price'];
                $goods['inventory'] = $goods_base['data']['spec_base']['inventory'];
            } else {
                return $goods_base;
            }

            // 库存
            if($v['stock'] > $goods['inventory'])
            {
                return DataReturn('购买数量超过商品库存数量['.$goods['title'].']['.$v['stock'].'>'.$goods['inventory'].']', -1);
            }
        }

        return DataReturn('校验成功', 0);
    }

    /**
     * 订单添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderInsert($params = [])
    {
        // 站点类型，是否开启了展示型
        $common_site_type = SystemBaseService::SiteTypeValue();
        if($common_site_type == 1)
        {
            return DataReturn('展示型不允许提交订单', -1);
        }

        // 销售+自提, 用户自选站点类型
        $user_site_model = isset($params['site_model']) ? intval($params['site_model']) : 0;
        $site_model = ($common_site_type == 4) ? $user_site_model : $common_site_type;

        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];

        // // 销售型,自提点,销售+自提 则校验地址
        if(in_array($site_model, [0,2]))
        {
            $p[] = [
                'checked_type'      => 'isset',
                'key_name'          => 'address_id',
                'error_msg'         => '请选择地址',
            ];
        }

        // 非预约模式则校验支付方式
        if(MyC('common_order_is_booking', 0) != 1)
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'payment_id',
                'error_msg'         => '支付方式有误',
            ];
        }
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 支付方式
        $payment_id = 0;
        $is_under_line = 0;
        if(!empty($params['payment_id']))
        {
            $payment = PaymentService::PaymentList(['where'=>['id'=>intval($params['payment_id'])]]);
            if(empty($payment[0]))
            {
                return DataReturn('支付方式有误', -1);
            }
            $payment_id = $payment[0]['id'];
            $is_under_line = in_array($payment[0]['payment'], MyConfig('shopxo.under_line_list')) ? 1 : 0;
        }

        // 清单商品
        $params['is_order_submit'] = 1;
        $buy = self::BuyTypeGoodsList($params);
        if(!isset($buy['code']) || $buy['code'] != 0)
        {
            return $buy;
        }

        // 用户留言
        $user_note = empty($params['user_note']) ? '' : str_replace(['"', "'"], '', strip_tags($params['user_note']));

        // 订单默认状态
        $order_status = (intval(MyC('common_order_is_booking', 0)) == 1) ? 0 : 1;

        // 订单来源
        $client_type = ApplicationClientType();

        // 开始事务
        Db::startTrans();

        // 循环处理
        $order_ids = [];
        foreach($buy['data']['goods'] as $v)
        {
            // 商品销售模式
            // 商品小于等于1则使用商品的类型
            if($v['order_base']['goods_count'] == 1)
            {
                $ret = GoodsService::GoodsSalesModelType($v['goods_items'][0]['goods_id']);
                $site_model = ($ret['data'] == 4) ? $user_site_model : $ret['data'];
            }

            // 商品校验
            $check = self::BuyGoodsCheck(['goods'=>$v['goods_items'], 'is_buy'=>1]);
            if($check['code'] != 0)
            {
                return $check;
            }

            // 销售型,自提点,销售+自提 地址处理
            $address = [];
            if(in_array($site_model, [0,2]))
            {
                if(empty($v['order_base']['address']))
                {
                    return DataReturn('地址有误', -1);
                } else {
                    $address = $v['order_base']['address'];
                }
            }

            // 订单主信息
            $order = [
                'order_no'              => date('YmdHis').GetNumberCode(6),
                'user_id'               => $params['user']['id'],
                'warehouse_id'          => $v['id'],
                'user_note'             => $user_note,
                'status'                => $order_status,
                'preferential_price'    => ($v['order_base']['preferential_price'] <= 0.00) ? 0.00 : $v['order_base']['preferential_price'],
                'increase_price'        => ($v['order_base']['increase_price'] <= 0.00) ? 0.00 : $v['order_base']['increase_price'],
                'price'                 => ($v['order_base']['total_price'] <= 0.00) ? 0.00 : $v['order_base']['total_price'],
                'total_price'           => ($v['order_base']['actual_price'] <= 0.00) ? 0.00 : $v['order_base']['actual_price'],
                'extension_data'        => empty($v['order_base']['extension_data']) ? '' : json_encode($v['order_base']['extension_data'], JSON_UNESCAPED_UNICODE),
                'payment_id'            => $payment_id,
                'buy_number_count'      => $v['order_base']['buy_count'],
                'client_type'           => $client_type,
                'order_model'           => $site_model,
                'is_under_line'         => $is_under_line,
                'add_time'              => time(),
            ];
            if($order['status'] == 1)
            {
                $order['confirm_time'] = time();
            }

            // 订单添加前钩子
            $hook_name = 'plugins_service_buy_order_insert_begin';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => $v,
                'order'         => &$order,
                'goods'         => &$v['goods_items'],
                'params'        => $params,
                
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 订单添加
            $order_id = Db::name('Order')->insertGetId($order);
            if($order_id > 0)
            {
                foreach($v['goods_items'] as $k=>$vs)
                {
                    // 添加订单详情数据,data返回自增id
                    $detail_ret = self::OrderDetailInsert($order_id, $params['user']['id'], $vs);
                    if($detail_ret['code'] == 0)
                    {
                        $v['goods_items'][$k]['id'] = $detail_ret['data'];
                    } else {
                        Db::rollback();
                        return $ret;
                    }

                    // 订单模式 - 虚拟信息添加
                    if($site_model == 3)
                    {
                        $ret = self::OrderFictitiousValueInsert($order_id, $detail_ret['data'], $params['user']['id'], $vs['goods_id']);
                        if($ret['code'] != 0)
                        {
                            Db::rollback();
                            return $ret;
                        }
                    }
                }

                // 订单模式处理
                // 销售型模式,自提模式,销售+自提
                if(in_array($site_model, [0,2]))
                {
                    // 添加订单(收货|取货)地址
                    if(!empty($address))
                    {
                        $ret = self::OrderAddressInsert($order_id, $params['user']['id'], $address);
                        if($ret['code'] != 0)
                        {
                            Db::rollback();
                            return $ret;
                        }
                    }

                    // 自提模式 添加订单取货码
                    if($site_model == 2)
                    {
                        $ret = self::OrderExtractionCcodeInsert($order_id, $params['user']['id']);
                        if($ret['code'] != 0)
                        {
                            Db::rollback();
                            return $ret;
                        }
                    }
                }

                // 订单货币
                $ret = OrderCurrencyService::OrderCurrencyInsert($order_id, $params['user']['id']);
                if($ret['code'] != 0)
                {
                    Db::rollback();
                    return $ret;
                }
            } else {
                Db::rollback();
                return DataReturn('订单添加失败', -1);
            }

            // 库存扣除
            if($order['status'] == 1)
            {
                $ret = self::OrderInventoryDeduct(['order_id'=>$order_id, 'order_data'=>$order]);
                if($ret['code'] != 0)
                {
                    // 事务回滚
                    Db::rollback();
                    return DataReturn($ret['msg'], -10);
                }
            }

            // 订单添加成功钩子
            $hook_name = 'plugins_service_buy_order_insert_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'order_id'      => $order_id,
                'order'         => $order,
                'data'          => $v,
                'goods'         => $v['goods_items'],
                'address'       => $address,
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return $ret;
            }

            // 订单id集合
            $order_ids[] = $order_id;
        }

        // 订单全部提交成功
        Db::commit();

        // 订单添加成功钩子, 不校验返回值
        $hook_name = 'plugins_service_buy_order_insert_success';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_ids'     => $order_ids,
            'params'        => $params,
        ]);

        // 删除购物车
        self::BuyCartDelete($params);

        // 返回信息
        $result = [
            'order_status'  => $order_status,
            'order_ids'     => $order_ids,
            'jump_url'      => MyUrl('index/order/index'),
        ];


        // 获取订单信息
        switch($order_status)
        {
            // 预约成功
            case 0 :
                $msg = '预约成功';
                break;

            // 提交成功,进入合并支付
            case 1 :
                $msg = '提交成功';
                $result['jump_url'] = MyUrl('index/order/pay', ['ids'=>implode(',', $order_ids)]);
                break;

            // 默认操作成功
            default :
                $msg = '操作成功';
        }

        return DataReturn($msg, 0, $result);
    }

    /**
     * 订单详情添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-20
     * @desc    description
     * @param   [int]          $order_id    [订单id]
     * @param   [int]          $user_id     [用户id]
     * @param   [array]        $detail      [商品详情数据]
     */
    private static function OrderDetailInsert($order_id, $user_id, $detail)
    {
        $data = [
            'order_id'          => $order_id,
            'user_id'           => $user_id,
            'goods_id'          => $detail['goods_id'],
            'title'             => $detail['title'],
            'images'            => $detail['images_old'],
            'original_price'    => $detail['original_price'],
            'price'             => $detail['price'],
            'total_price'       => PriceNumberFormat($detail['stock']*$detail['price']),
            'spec'              => empty($detail['spec']) ? '' : json_encode($detail['spec'], JSON_UNESCAPED_UNICODE),
            'spec_weight'       => empty($detail['spec_weight']) ? 0.00 : (float) $detail['spec_weight'],
            'spec_coding'       => empty($detail['spec_coding']) ? '' : $detail['spec_coding'],
            'spec_barcode'      => empty($detail['spec_barcode']) ? '' : $detail['spec_barcode'],
            'buy_number'        => intval($detail['stock']),
            'model'             => $detail['model'],
            'add_time'          => time(),
        ];

        // 订单详情添加前钩子
        $hook_name = 'plugins_service_buy_order_detail_insert_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'user_id'       => $user_id,
            'order_id'      => $order_id,
            'data'          => &$data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 添加订单详情数据
        $order_detail_id = Db::name('OrderDetail')->insertGetId($data);
        if($order_detail_id > 0)
        {
            return DataReturn('添加成功', 0, $order_detail_id);
        }
        return DataReturn('订单详情添加失败', -1);
    }

    /**
     * 订单关联自提取货码添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-20
     * @desc    description
     * @param   [int]          $order_id            [订单id]
     * @param   [int]          $user_id             [用户id]
     */
    private static function OrderExtractionCcodeInsert($order_id, $user_id)
    {
        $data = [
            'order_id'      => $order_id,
            'user_id'       => $user_id,
            'code'          => GetNumberCode(4),
            'add_time'      => time(),
        ];

        // 订单取货码添加前钩子
        $hook_name = 'plugins_service_buy_order_extraction_code_insert_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'             => $hook_name,
            'is_backend'            => true,
            'user_id'               => $user_id,
            'order_id'              => $order_id,
            'data'                  => &$data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 添加订单虚拟数据
        if(Db::name('OrderExtractionCode')->insertGetId($data) > 0)
        {
            return DataReturn('添加成功', 0);
        }
        return DataReturn('订单取货码添加失败', -1);
    }

    /**
     * 订单关联虚拟销售数据添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-20
     * @desc    description
     * @param   [int]          $order_id            [订单id]
     * @param   [int]          $order_detail_id     [订单详情id]
     * @param   [int]          $user_id             [用户id]
     * @param   [int]          $goods_id            [商品id]
     */
    private static function OrderFictitiousValueInsert($order_id, $order_detail_id, $user_id, $goods_id)
    {
        $data = [
            'order_id'              => $order_id,
            'order_detail_id'       => $order_detail_id,
            'user_id'               => $user_id,
            'value'                 => Db::name('Goods')->where(['id'=>$goods_id])->value('fictitious_goods_value'),
            'add_time'              => time(),
        ];

        // 订单虚拟数据添加前钩子
        $hook_name = 'plugins_service_buy_order_fictitious_insert_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'             => $hook_name,
            'is_backend'            => true,
            'user_id'               => $user_id,
            'order_id'              => $order_id,
            'order_detail_id'       => $order_detail_id,
            'goods_id'              => $goods_id,
            'data'                  => &$data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 添加订单虚拟数据
        if(Db::name('OrderFictitiousValue')->insertGetId($data) > 0)
        {
            return DataReturn('添加成功', 0);
        }
        return DataReturn('订单虚拟信息添加失败', -1);
    }

    /**
     * 订单关联地址添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-20
     * @desc    description
     * @param   [int]          $order_id [订单id]
     * @param   [int]          $user_id  [用户id]
     * @param   [array]        $address  [地址]
     */
    private static function OrderAddressInsert($order_id, $user_id, $address)
    {
        // 坐标处理
        if(in_array(APPLICATION_CLIENT_TYPE, MyConfig('shopxo.coordinate_transformation')))
        {
            // 坐标转换 火星(高德，谷歌，腾讯坐标) 转 百度
            if(isset($address['lng']) && isset($address['lat']))
            {
                $map = \base\GeoTransUtil::GcjToBd($address['lng'], $address['lat']);
                if(isset($map['lng']) && isset($map['lat']))
                {
                    $address['lng'] = $map['lng'];
                    $address['lat'] = $map['lat'];
                }
            }
        }

        // 订单收货地址
        $data = [
            'order_id'      => $order_id,
            'user_id'       => $user_id,
            'address_id'    => isset($address['id']) ? intval($address['id']) : 0,
            'alias'         => isset($address['alias']) ? $address['alias'] : '',
            'name'          => isset($address['name']) ? $address['name'] : '',
            'tel'           => isset($address['tel']) ? $address['tel'] : '',
            'province'      => isset($address['province']) ? intval($address['province']) : 0,
            'city'          => isset($address['city']) ? intval($address['city']) : 0,
            'county'        => isset($address['county']) ? intval($address['county']) : 0,
            'address'       => isset($address['address']) ? $address['address'] : '',
            'province_name' => isset($address['province_name']) ? $address['province_name'] : '',
            'city_name'     => isset($address['city_name']) ? $address['city_name'] : '',
            'county_name'   => isset($address['county_name']) ? $address['county_name'] : '',
            'lng'           => isset($address['lng']) ? (float) $address['lng'] : '0.0000000000',
            'lat'           => isset($address['lat']) ? (float) $address['lat'] : '0.0000000000',
            'idcard_name'   => empty($address['idcard_name']) ? '' : $address['idcard_name'],
            'idcard_number' => empty($address['idcard_number']) ? '' : $address['idcard_number'],
            'idcard_front'  => empty($address['idcard_front']) ? '' : ResourcesService::AttachmentPathHandle($address['idcard_front']),
            'idcard_back'   => empty($address['idcard_back']) ? '' : ResourcesService::AttachmentPathHandle($address['idcard_back']),
            'add_time'      => time(),
        ];

        // 订单地址添加前钩子
        $hook_name = 'plugins_service_buy_order_address_insert_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'user_id'       => $user_id,
            'order_id'      => $order_id,
            'data'          => &$data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 添加订单地址
        if(Db::name('OrderAddress')->insertGetId($data) > 0)
        {
            return DataReturn('添加成功', 0);
        }
        return DataReturn('订单地址添加失败', -1);
    }

    /**
     * 购物车总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function CartTotal($where = [])
    {
        return (int) Db::name('Cart')->where($where)->count();
    }

    /**
     * 用户购物车总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [int|string]             [超过99则返回 99+]
     */
    public static function UserCartTotal($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return 0;
        }

        // 获取购物车总数
        $total = self::CartTotal(['user_id'=>$params['user']['id']]);
        return ($total > 99) ? '99+' : $total;
    }

    /**
     * 单个订单支付前校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SingleOrderPayBeginCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => '订单id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否扣除库存
        $common_is_deduction_inventory = MyC('common_is_deduction_inventory', 0);
        if($common_is_deduction_inventory != 1)
        {
            return DataReturn('未开启扣除库存', 0);
        }

        // 扣除库存规则
        $common_deduction_inventory_rules = MyC('common_deduction_inventory_rules', 1);

        // 这里仅订单支付规则类型下校验
        if($common_deduction_inventory_rules == 1)
        {
            // 获取订单商品
            $order_detail = Db::name('OrderDetail')->field('id,goods_id,buy_number,spec')->where(['order_id'=>$params['order_id']])->select()->toArray();
            if(empty($order_detail))
            {
                return DataReturn('订单详情有误', -1);
            }

            // 数据校验
            foreach($order_detail as $v)
            {
                $ret = self::BuyOrderPayBeginGoodsCheck($v);
                if($ret['code'] != 0)
                {
                    return $ret;
                }
            }
        }
        return DataReturn('校验成功', 0);
    }

    /**
     * 多个订单下单库存校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-04
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function MoreOrderPayBeginCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_data',
                'error_msg'         => '订单数据不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => '订单数据有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否扣除库存
        $common_is_deduction_inventory = MyC('common_is_deduction_inventory', 0);
        if($common_is_deduction_inventory != 1)
        {
            return DataReturn('未开启扣除库存', 0);
        }

        // 扣除库存规则
        $common_deduction_inventory_rules = MyC('common_deduction_inventory_rules', 1);

        // 这里仅订单支付规则类型下校验
        if($common_deduction_inventory_rules == 1)
        {
            // 数据集合
            $detail = Db::name('OrderDetail')->field('id,order_id,goods_id,buy_number,spec')->where(['order_id'=>array_column($params['order_data'], 'id')])->select()->toArray();
            if(empty($detail))
            {
                return DataReturn('订单详情有误', -1);
            }

            // 订单集合
            $order_group = [];
            foreach($params['order_data'] as $o)
            {
                $order_group[$o['id']] = $o['warehouse_id'];
            }
        
            // 订单详情
            $data = [];
            foreach($detail as $d)
            {
                $key = md5(empty($d['spec']) ? 'default' : $d['spec']);
                if(!isset($data[$order_group[$d['order_id']]][$d['goods_id']][$key]))
                {
                    $data[$order_group[$d['order_id']]][$d['goods_id']][$key] = $d;
                } else {
                    $data[$order_group[$d['order_id']]][$d['goods_id']][$key]['buy_number'] += $d['buy_number'];
                }
            }

            // 数据校验
            foreach($data as $w)
            {
                foreach($w as $g)
                {
                    foreach($g as $v)
                    {
                        $ret = self::BuyOrderPayBeginGoodsCheck($v);
                        if($ret['code'] != 0)
                        {
                            return $ret;
                        }
                    }
                }
            }
        }

        return DataReturn('校验成功', 0);
    }

    /**
     * 订单支付前商品校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-05
     * @desc    description
     * @param   [array]        $detail   [订单详情]
     */
    public static function BuyOrderPayBeginGoodsCheck($detail)
    {
        // 获取商品
        $goods = Db::name('Goods')->field('is_shelves,is_deduction_inventory,inventory,title')->find($detail['goods_id']);
        if(empty($goods))
        {
            return DataReturn('商品不存在', -10);
        }

        // 商品状态
        if($goods['is_shelves'] != 1)
        {
            return DataReturn('商品已下架['.$goods['title'].']', -10);
        }

        // 库存
        if(isset($goods['is_deduction_inventory']) && $goods['is_deduction_inventory'] == 1)
        {
            // 先判断商品库存是否不足
            if($goods['inventory'] < $detail['buy_number'])
            {
                return DataReturn('库存不足['.$goods['title'].'('.$goods['inventory'].'<'.$detail['buy_number'].')]', -10);
            }

            // 规格库存
            $spec = empty($detail['spec']) ? '' : json_decode($detail['spec'], true);
            $base = GoodsService::GoodsSpecDetail(['id'=>$detail['goods_id'], 'spec'=>$spec]);
            if($base['code'] == 0)
            {
                // 先判断商品规格库存是否不足
                if($base['data']['spec_base']['inventory'] < $detail['buy_number'])
                {
                    return DataReturn('库存不足['.$goods['title'].'('.$base['data']['spec_base']['inventory'].'<'.$detail['buy_number'].')]', -10);
                }
            } else {
                return $base;
            }
        }
        return DataReturn('校验成功', 0);
    }

    /**
     * 库存扣除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderInventoryDeduct($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否扣除库存
        $common_is_deduction_inventory = MyC('common_is_deduction_inventory', 0);
        if($common_is_deduction_inventory != 1)
        {
            return DataReturn('未开启扣除库存', 0);
        }

        // 扣除库存规则
        $common_deduction_inventory_rules = MyC('common_deduction_inventory_rules', 1);
        switch($common_deduction_inventory_rules)
        {
            // 订单确认成功
            case 0 :
                if($params['order_data']['status'] != 1)
                {
                    return DataReturn('当前订单状态未操作确认-不扣除库存['.$params['order_id'].']', 0);
                }
                break;

            // 订单支付成功
            case 1 :
                if($params['order_data']['status'] != 2)
                {
                    return DataReturn('当前订单状态未操作支付-不扣除库存['.$params['order_id'].']', 0);
                }
                break;

            // 订单发货
            case 2 :
                if($params['order_data']['status'] != 3)
                {
                    return DataReturn('当前订单状态未操作发货-不扣除库存['.$params['order_id'].']', 0);
                }
                break;
        }

        // 获取订单商品
        $order_detail = Db::name('OrderDetail')->field('id,goods_id,buy_number,spec')->where(['order_id'=>$params['order_id']])->select()->toArray();
        if(!empty($order_detail))
        {
            foreach($order_detail as $v)
            {
                // 查看是否已扣除过库存,避免更改模式导致重复扣除
                $temp = Db::name('OrderGoodsInventoryLog')->where(['order_id'=>$params['order_id'], 'order_detail_id'=>$v['id'], 'goods_id'=>$v['goods_id']])->find();
                if(empty($temp))
                {
                    $goods = Db::name('Goods')->field('is_deduction_inventory,inventory,title')->find($v['goods_id']);
                    if(isset($goods['is_deduction_inventory']) && $goods['is_deduction_inventory'] == 1)
                    {
                        // 先判断商品库存是否不足
                        if($goods['inventory'] < $v['buy_number'])
                        {
                            return DataReturn('商品库存不足['.$goods['title'].'('.$goods['inventory'].'<'.$v['buy_number'].')]', -10);
                        }

                        // 扣除操作
                        if(!Db::name('Goods')->where(['id'=>$v['goods_id']])->dec('inventory', $v['buy_number'])->update())
                        {
                            return DataReturn('商品库存扣减失败['.$params['order_id'].'-'.$v['id'].'-'.$v['goods_id'].'('.$goods['inventory'].'-'.$v['buy_number'].')]', -10);
                        }

                        // 扣除规格库存
                        $spec = empty($v['spec']) ? '' : json_decode($v['spec'], true);
                        $base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>$spec]);
                        if($base['code'] == 0)
                        {
                            // 先判断商品规格库存是否不足
                            if($base['data']['spec_base']['inventory'] < $v['buy_number'])
                            {
                                return DataReturn('商品规格库存不足['.$goods['title'].'('.$base['data']['spec_base']['inventory'].'<'.$v['buy_number'].']', -10);
                            }

                            // 扣除规格操作
                            if(!Db::name('GoodsSpecBase')->where(['id'=>$base['data']['spec_base']['id'], 'goods_id'=>$v['goods_id']])->dec('inventory', $v['buy_number'])->update())
                            {
                                return DataReturn('规格库存扣减失败['.$params['order_id'].'-'.$v['goods_id'].'('.$goods['inventory'].'-'.$v['buy_number'].')]', -10);
                            }
                        } else {
                            return $base;
                        }

                        // 仓库库存扣除
                        $we_ret = WarehouseGoodsService::WarehouseGoodsInventoryDeduct($params['order_id'], $v['goods_id'], $spec, $v['buy_number']);
                        if($we_ret['code'] != 0)
                        {
                            return $we_ret;
                        }

                        // 扣除日志添加
                        $log_data = [
                            'order_id'              => $params['order_id'],
                            'order_detail_id'       => $v['id'],
                            'goods_id'              => $v['goods_id'],
                            'order_status'          => $params['order_data']['status'],
                            'original_inventory'    => $goods['inventory'],
                            'new_inventory'         => Db::name('Goods')->where(['id'=>$v['goods_id']])->value('inventory'),
                            'add_time'              => time(),
                        ];
                        if(Db::name('OrderGoodsInventoryLog')->insertGetId($log_data) <= 0)
                        {
                            return DataReturn('库存扣减日志添加失败['.$params['order_id'].'-'.$v['goods_id'].']', -100);
                        }
                    }
                }
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('没有需要扣除库存的数据', 0);
    }

    /**
     * 库存回滚
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderInventoryRollback($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 订单状态
        if(isset($params['order_data']['status']))
        {
            if(!in_array($params['order_data']['status'], [5,6]))
            {
                return DataReturn('当前订单状态不允许回滚库存['.$params['order_id'].'-'.$params['order_data']['status'].']', 0);
            }
        }

        // 是否指定商品和数量
        $appoint_buy_number = empty($params['appoint_buy_number']) ? 0 : intval($params['appoint_buy_number']);
        $detail_where = ['order_id' => $params['order_id']];
        if(!empty($params['appoint_order_detail_id']))
        {
            $detail_where['id'] = intval($params['appoint_order_detail_id']);
        }

        // 获取订单商品
        $order_detail = Db::name('OrderDetail')->field('goods_id,buy_number,spec')->where($detail_where)->select()->toArray();
        if(!empty($order_detail))
        {
            foreach($order_detail as $v)
            {
                // 查看是否已扣除过库存
                $temp = Db::name('OrderGoodsInventoryLog')->where(['order_id'=>$params['order_id'], 'goods_id'=>$v['goods_id'], 'is_rollback'=>0])->find();
                if(!empty($temp))
                {
                    // 数量
                    $buy_number = ($appoint_buy_number == 0) ? $v['buy_number'] : $appoint_buy_number;

                    // 回滚操作
                    if(!Db::name('Goods')->where(['id'=>$v['goods_id']])->inc('inventory', $buy_number)->update())
                    {
                        return DataReturn('商品库存回滚失败['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                    }

                    // 回滚规格库存
                    $spec = empty($v['spec']) ? '' : json_decode($v['spec'], true);
                    $base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>$spec]);
                    if($base['code'] == 0)
                    {
                        // 回滚规格操作
                        if(!Db::name('GoodsSpecBase')->where(['id'=>$base['data']['spec_base']['id'], 'goods_id'=>$v['goods_id']])->inc('inventory', $buy_number)->update())
                        {
                            return DataReturn('规格库存回滚失败['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                        }
                    } else {
                        return $base;
                    }

                    // 仓库库存回滚
                    $we_ret = WarehouseGoodsService::WarehouseGoodsInventoryRollback($params['order_id'], $v['goods_id'], $spec, $v['buy_number']);
                    if($we_ret['code'] != 0)
                    {
                        return $we_ret;
                    }

                    // 回滚日志更新
                    $log_data = [
                        'is_rollback'   => 1,
                        'rollback_time' => time(),
                    ];
                    if(!Db::name('OrderGoodsInventoryLog')->where(['id'=>$temp['id']])->update($log_data))
                    {
                        return DataReturn('库存回滚日志更新失败['.$temp['id'].'-'.$params['order_id'].']', -100);
                    }
                }
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('没有需要回滚的数据', 0);
    }

    /**
     * 自提点地址选中地址获取
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-18
     * @desc    description
     * @param   [int]       $params['address_id'] [自提点地址索引值]
     * @param   [array]     $params['address_id'] [自提点地址列表]
     */
    public static function SiteExtractionAddress($params = [])
    {
        // 自提地址列表
        $address = ConfigService::SiteTypeExtractionAddressList();

        // 选中地址处理
        $default = null;
        if(isset($params['address_id']) && $params['address_id'] !== null && !empty($address['data']) && is_array($address['data']))
        {
            if(isset($address['data'][$params['address_id']]))
            {
                $default = $address['data'][$params['address_id']];
            }
        }

        // 默认地址
        if(empty($default) && !empty($address['data']))
        {
            foreach($address['data'] as $v)
            {
                if(isset($v['is_default']) && $v['is_default'] == 1)
                {
                    $default = $v;
                    break;
                }
            }
        }

        // 返回数据
        $result = [
            'data_list'     => $address['data'],
            'default'       => $default,
        ];

        // 自提点地址数据钩子
        $hook_name = 'plugins_service_site_extraction_address_handle';
        $ret = MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$result,
        ]);

        return DataReturn('操作成功', 0, $result);
    }
}
?>