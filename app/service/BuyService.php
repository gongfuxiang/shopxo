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
use app\service\UserService;
use app\service\GoodsService;
use app\service\GoodsCartService;
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
                'key_name'          => 'goods_data',
                'error_msg'         => MyLang('common_service.buy.buy_goods_data_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        if(!is_array($params['goods_data']))
        {
            $params['goods_data'] = json_decode(base64_decode(urldecode($params['goods_data'])), true);
        }

        // 获取商品
        $goods_ids = array_column($params['goods_data'], 'goods_id');
        $goods_params = array_merge($params, [
            'where' => [
                ['id', 'in', $goods_ids],
                ['is_delete_time', '=', 0],
                ['is_shelves', '=', 1],
            ],
            'field' => 'id,id AS goods_id,title,images,inventory_unit,is_shelves,buy_min_number,buy_max_number,model,site_type',
            'm'     => 0,
            'n'     => 0,
        ]);
        $ret = GoodsService::GoodsList($goods_params);
        if(empty($ret['data'][0]))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -10);
        }

        // 商品处理
        $data = [];
        $temp_goods = array_column($ret['data'], null, 'id');
        foreach($params['goods_data'] as $v)
        {
            if(array_key_exists($v['goods_id'], $temp_goods))
            {
                // 商品
                $goods = $temp_goods[$v['goods_id']];

                // 规格
                $goods['spec'] = self::GoodsSpecificationsHandle($v);

                // id处理、避免不同规格导致id一样
                $goods['id'] = md5($goods['goods_id'].(empty($goods['spec']) ? 'default' : implode('', array_column($goods['spec'], 'value'))));

                // 获取商品基础信息
                $spec_params = array_merge($params, [
                    'id'    => $goods['goods_id'],
                    'spec'  => $goods['spec'],
                    'stock' => $v['stock']
                ]);
                $goods_base = GoodsService::GoodsSpecDetail($spec_params);
                if($goods_base['code'] == 0)
                {
                    $goods['inventory'] = $goods_base['data']['spec_base']['inventory'];
                    $goods['price'] = (float) $goods_base['data']['spec_base']['price'];
                    $goods['original_price'] = (float) $goods_base['data']['spec_base']['original_price'];
                    $goods['spec_base_id'] = $goods_base['data']['spec_base']['id'];
                    $goods['spec_buy_min_number'] = $goods_base['data']['spec_base']['buy_min_number'];
                    $goods['spec_buy_max_number'] = $goods_base['data']['spec_base']['buy_max_number'];
                    $goods['spec_weight'] = $goods_base['data']['spec_base']['weight'];
                    $goods['spec_volume'] = $goods_base['data']['spec_base']['volume'];
                    $goods['spec_coding'] = $goods_base['data']['spec_base']['coding'];
                    $goods['spec_barcode'] = $goods_base['data']['spec_base']['barcode'];
                    $goods['extends'] = $goods_base['data']['spec_base']['extends'];

                    // 商品价格容器赋值规格价格
                    $goods['price_container']['price'] = $goods['price'];
                    $goods['price_container']['original_price'] = $goods['original_price'];
                } else {
                    return $goods_base;
                }

                // 获取商品规格图片
                if(!empty($goods['spec']))
                {
                    $images = self::BuyGoodsSpecImages($goods['goods_id'], $goods['spec']);
                    if(!empty($images))
                    {
                        $goods['images'] = ResourcesService::AttachmentPathViewHandle($images);
                        $goods['images_old'] = $images;
                    }
                }

                // 数量/小计
                $goods['stock'] = $v['stock'];
                $goods['total_price'] = $v['stock']* ((float) $goods['price']);
                $data[] = $goods;
            }
        }
        $ret['data'] = $data;
        return $ret;
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
                'error_msg'         => MyLang('common_service.buy.cart_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取购物车数据
        $params['where'] = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1],
            ['c.id', 'in', explode(',', $params['ids'])],
        ];
        return GoodsCartService::GoodsCartList($params);
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
                    $ret = DataReturn(MyLang('params_error_tips'), -1);
            }
        } else {
            $ret = DataReturn(MyLang('params_error_tips'), -1);
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

            // 订单拆分处理
            return self::OrderSplitHandle($site_model, $common_site_type, $address, $extraction_address, $goods, $params);
        }
        return $ret;
    }

    /**
     * 订单拆分处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-09-06
     * @desc    description
     * @param   [int]            $site_model         [当前指定类型]
     * @param   [int]            $common_site_type   [当前站点类型]
     * @param   [array]          $address            [收货地址]
     * @param   [array]          $extraction_address [自提地址]
     * @param   [array]          $goods              [订单商品]
     * @param   [array]          $params             [输入参数]
     */
    public static function OrderSplitHandle($site_model, $common_site_type, $address, $extraction_address, $goods, $params)
    {
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
            'spec_volume_total'     => 0,
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

            // 规格体积总计
            'spec_volume_total'     => $base_fields['spec_volume_total'],

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
            'goods'                 => $order_split['data'],
            'base'                  => $base,
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

        return DataReturn(MyLang('operate_success'), 0, $result);
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
                'error_msg'         => MyLang('goods_data_empty_tips'),
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'goods',
                'error_msg'         => MyLang('goods_info_incorrect_tips'),
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否需要校验商品类型、is_buy、1校验、默认0不校验
        $is_check_goods_site_type = (isset($params['is_buy']) && $params['is_buy'] == 1) ? 1 : 0;
        // 商品小于等于1不校验
        if($is_check_goods_site_type == 1 && 
            count(array_unique(array_column($params['goods'], 'goods_id'))) <= 1)
        {
            $is_check_goods_site_type = 0;
        }

        // 是否存在字段缺失
        $goods_data = [];
        if(!array_key_exists('is_shelves', $params['goods'][0]))
        {
            $goods_data = Db::name('Goods')->where(['id'=>array_column($params['goods'], 'goods_id')])->column('is_shelves,buy_min_number,buy_max_number,site_type', 'id');
        }

        // 数据校验
        foreach($params['goods'] as $v)
        {
            // 数据合并
            if(!empty($goods_data) && array_key_exists($v['goods_id'], $goods_data))
            {
                $v = array_merge($v, $goods_data[$v['goods_id']]);
            }

            // 是否存在规格所属数据
            if(!array_key_exists('spec_buy_min_number', $v) || !array_key_exists('spec_buy_max_number', $v))
            {
                $goods_base = GoodsService::GoodsSpecDetail(array_merge($params, [
                    'id'    => $v['goods_id'],
                    'spec'  => isset($v['spec']) ? $v['spec'] : [],
                ]));
                if($goods_base['code'] == 0)
                {
                    $v['price'] = $goods_base['data']['spec_base']['price'];
                    $v['inventory'] = $goods_base['data']['spec_base']['inventory'];
                    $v['spec_buy_min_number'] = $goods_base['data']['spec_base']['buy_min_number'];
                    $v['spec_buy_max_number'] = $goods_base['data']['spec_base']['buy_max_number'];
                } else {
                    return $goods_base;
                }
            }

            // 基础判断
            if($v['is_shelves'] != 1)
            {
                return DataReturn(MyLang('common_service.buy.goods_already_shelves_tips').'['.$v['title'].']', -1);
            }

            // 先判断规格的起购数、则再判断商品的起购数
            $min = (isset($v['spec_buy_min_number']) && $v['spec_buy_min_number'] > 0) ? $v['spec_buy_min_number'] : (isset($v['buy_min_number']) ? $v['buy_min_number'] : 0);
            if($min > 0 && $v['stock'] < $min)
            {
                return DataReturn(MyLang('common_service.buy.goods_buy_min_error_tips').'['.$v['title'].']['.$v['stock'].'<'.$min.']', -1);
            }

            // 先判断规格的限购数、则再判断商品的限购数
            $max = (isset($v['spec_buy_max_number']) && $v['spec_buy_max_number'] > 0) ? $v['spec_buy_max_number'] : (isset($v['buy_max_number']) ? $v['buy_max_number'] : 0);
            if($max > 0 && $v['stock'] > $max)
            {
                return DataReturn(MyLang('common_service.buy.goods_buy_max_error_tips').'['.$v['title'].']['.$v['stock'].'>'.$max.']', -1);
            }

            // 是否支持购物车操作
            if($is_check_goods_site_type)
            {
                $ret = GoodsService::IsGoodsSiteTypeConsistent($v['goods_id'], $v['site_type']);
                if($ret['code'] != 0)
                {
                    return DataReturn($ret['msg'].'['.$v['title'].']', $ret['code']);
                }
            }

            // 库存
            if($v['stock'] > $v['inventory'])
            {
                return DataReturn(MyLang('common_service.buy.goods_buy_exceed_inventory_tips').'['.$v['title'].']['.$v['stock'].'>'.$v['inventory'].']', -1);
            }
        }
        return DataReturn(MyLang('check_success'), 0);
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
            return DataReturn(MyLang('common_service.buy.exhibition_not_allow_submit_tips'), -1);
        }

        // 销售+自提, 用户自选站点类型
        $user_site_model = isset($params['site_model']) ? intval($params['site_model']) : 0;
        $site_model = ($common_site_type == 4) ? $user_site_model : $common_site_type;

        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];

        // // 快递,自提点,销售+自提 则校验地址
        if(in_array($site_model, [0,2]))
        {
            $p[] = [
                'checked_type'      => 'isset',
                'key_name'          => 'address_id',
                'error_msg'         => MyLang('common_service.buy.choice_not_address_tips'),
            ];
        }
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否预约模式
        $common_order_is_booking = MyC('common_order_is_booking', 0);

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck($params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 清单商品
        $params['is_order_submit'] = 1;
        $buy = self::BuyTypeGoodsList($params);
        if(!isset($buy['code']) || $buy['code'] != 0)
        {
            return $buy;
        }

        // 金额大于0、非预约模式 必须选择支付方式
        if($buy['data']['base']['actual_price'] > 0 && $common_order_is_booking != 1)
        {
            if(empty($params['payment_id']))
            {
                return DataReturn(MyLang('payment_method_error_tips'), -1);
            }
        }

        // 用户留言
        $user_note = empty($params['user_note']) ? '' : str_replace(['"', "'"], '', strip_tags($params['user_note']));

        // 订单默认状态
        $order_status = ($common_order_is_booking == 1) ? 0 : 1;

        // 支付方式
        $payment_id = 0;
        $is_under_line = 0;
        if(!empty($params['payment_id']))
        {
            $payment = PaymentService::PaymentData(['where'=>['id'=>intval($params['payment_id'])]]);
            if(empty($payment))
            {
                return DataReturn(MyLang('payment_method_error_tips'), -1);
            }
            $payment_id = $payment['id'];
            $is_under_line = in_array($payment['payment'], MyConfig('shopxo.under_line_list')) ? 1 : 0;

            // 线下支付订单是否直接成功
            // 是否开启线下订单正常进入流程
            if($common_order_is_booking != 1 && $is_under_line == 1 && MyC('common_is_under_line_order_normal') == 1)
            {
                $order_status = 2;
            }
        }
        $payment_id = intval($payment_id);

        // 循环处理数据
        $order_data = [];
        foreach($buy['data']['goods'] as $v)
        {
            // 商品销售模式
            // 当前整体仓库商品等于1则使用商品的类型
            if($buy['data']['base']['goods_count'] == 1)
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

            // 快递,自提点,销售+自提 地址处理
            $address = [];
            if(in_array($site_model, [0,2]))
            {
                if(empty($v['order_base']['address']))
                {
                    return DataReturn(MyLang('common_service.buy.address_empty_tips'), -1);
                } else {
                    $address = $v['order_base']['address'];
                }
            }

            // 订单主信息
            $order = [
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
                'client_type'           => APPLICATION_CLIENT_TYPE,
                'order_model'           => $site_model,
                'is_under_line'         => $is_under_line,
            ];

            // 订单地址
            $order['address_data'] = $address;

            // 订单详情
            $order['detail_data'] = [];
            foreach($v['goods_items'] as $vs)
            {
                $order['detail_data'][] = [
                    'user_id'           => $order['user_id'],
                    'goods_id'          => $vs['goods_id'],
                    'title'             => $vs['title'],
                    'images'            => $vs['images'],
                    'original_price'    => $vs['original_price'],
                    'price'             => $vs['price'],
                    'total_price'       => PriceNumberFormat($vs['stock']*$vs['price']),
                    'spec'              => empty($vs['spec']) ? '' : json_encode($vs['spec'], JSON_UNESCAPED_UNICODE),
                    'spec_weight'       => empty($vs['spec_weight']) ? 0.00 : (float) $vs['spec_weight'],
                    'spec_volume'       => empty($vs['spec_volume']) ? 0.00 : (float) $vs['spec_volume'],
                    'spec_coding'       => empty($vs['spec_coding']) ? '' : $vs['spec_coding'],
                    'spec_barcode'      => empty($vs['spec_barcode']) ? '' : $vs['spec_barcode'],
                    'buy_number'        => intval($vs['stock']),
                    'model'             => $vs['model'],
                    'inventory_unit'    => $vs['inventory_unit'],
                    'extends'           => empty($vs['extends']) ? [] : json_decode($vs['extends'], true),
                ];
            }
            $order_data[] = $order;
        }

        // 订单添加处理
        $order_ids = [];
        $ret = self::OrderInsertHandle($order_data, $params);
        if($ret['code'] == 0)
        {
            $order_ids = $ret['data'];
        } else {
            return $ret;
        }

        // 删除购物车
        self::BuyCartDelete($params);

        // 返回信息
        $result = [
            'order_status'  => $order_status,
            'order_ids'     => $order_ids,
            'payment_id'    => $payment_id,
            'jump_url'      => MyUrl('index/order/index'),
        ];


        // 获取订单信息
        switch($order_status)
        {
            // 预约成功
            case 0 :
                $msg = MyLang('prebook_success');
                break;

            // 提交成功,进入合并支付
            case 1 :
                $msg = MyLang('submit_success');
                $result['jump_url'] = MyUrl('index/order/pay', ['ids'=>implode(',', $order_ids)]);
                break;

            // 默认操作成功
            default :
                $msg = MyLang('operate_success');
        }

        return DataReturn($msg, 0, $result);
    }

    /**
     * 订单添加处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-09-05
     * @desc    description
     * @param   [array]          $data   [订单数据]
     * @param   [array]          $params [输入参数]
     */
    public static function OrderInsertHandle($data, $params = [])
    {
        // 所有订单id
        $order_ids = [];

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 循环处理
            foreach($data as $v)
            {
                // 订单主信息
                $v['order_no']  = self::OrderNoCreate();
                $v['add_time']  = time();

                // 确认时间
                if($v['status'] == 1)
                {
                    $v['confirm_time'] = time();
                }

                // 订单数据
                $order = $v;
                unset($order['address_data'], $order['detail_data']);

                // 订单添加前钩子
                $hook_name = 'plugins_service_buy_order_insert_begin';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'data'          => $v,
                    'order'         => &$order,
                    'goods'         => &$v['detail_data'],
                    'params'        => $params,
                    
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 未指定系统类型则增加默认
                if(empty($order['system_type']))
                {
                    $order['system_type'] = SYSTEM_TYPE;
                }
                // 订单添加
                $order_id = Db::name('Order')->insertGetId($order);
                if($order_id <= 0)
                {
                    throw new \Exception(MyLang('common_service.buy.order_insert_fail_tips'));
                }
                $order['id'] = $order_id;

                // 订单详情添加
                foreach($v['detail_data'] as &$vs)
                {
                    // 添加订单详情数据,data返回自增id
                    $order_detail_id = 0;
                    $ret = self::OrderDetailInsert($order_id, $order['user_id'], $vs);
                    if($ret['code'] == 0)
                    {
                        $order_detail_id = $ret['data'];
                        $vs['id'] = $order_detail_id;
                    } else {
                        throw new \Exception($ret['msg']);
                    }

                    // 订单模式 - 虚拟信息添加
                    if($order['order_model'] == 3)
                    {
                        $ret = self::OrderFictitiousValueInsert($order_id, $order_detail_id, $order['user_id'], $vs['goods_id']);
                        if($ret['code'] != 0)
                        {
                            throw new \Exception($ret['msg']);
                        }
                    }
                }

                // 订单模式处理
                // 快递模式,自提模式,销售+自提
                if(in_array($order['order_model'], [0,2]))
                {
                    // 添加订单(收货|取货)地址
                    if(!empty($v['address_data']))
                    {
                        $ret = self::OrderAddressInsert($order_id, $order['user_id'], $v['address_data']);
                        if($ret['code'] != 0)
                        {
                            throw new \Exception($ret['msg']);
                        }
                    }

                    // 自提模式 添加订单取货码
                    if($order['order_model'] == 2)
                    {
                        $ret = self::OrderExtractionCcodeInsert($order_id, $order['user_id']);
                        if($ret['code'] != 0)
                        {
                            throw new \Exception($ret['msg']);
                        }
                    }
                }

                // 订单货币
                $ret = OrderCurrencyService::OrderCurrencyInsert($order_id, $order['user_id']);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 库存扣除
                if(in_array($order['status'], [1,2]))
                {
                    $ret = self::OrderInventoryDeduct(['order_id'=>$order_id, 'opt_type'=>'confirm']);
                    if($ret['code'] != 0)
                    {
                        throw new \Exception($ret['msg']);
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
                    'goods'         => $v['detail_data'],
                    'address'       => $v['address_data'],
                    'params'        => $params,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 订单id集合
                $order_ids[] = $order_id;
            }

            // 完成
            Db::commit();
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }

        // 订单添加成功钩子, 不校验返回值
        $hook_name = 'plugins_service_buy_order_insert_success';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_ids'     => $order_ids,
            'params'        => $params,
        ]);

        // 返回信息
        return DataReturn(MyLang('operate_success'), 0, $order_ids);
    }

    /**
     * 订单号生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-17
     * @desc    description
     */
    public static function OrderNoCreate()
    {
        return date('YmdHis').GetNumberCode(6);
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
            'images'            => ResourcesService::AttachmentPathHandle($detail['images']),
            'original_price'    => $detail['original_price'],
            'price'             => $detail['price'],
            'total_price'       => PriceNumberFormat(isset($detail['total_price']) ? $detail['total_price'] : $detail['total_price']*$detail['buy_number']),
            'spec'              => empty($detail['spec']) ? '' : (is_array($detail['spec']) ? json_encode($detail['spec'], JSON_UNESCAPED_UNICODE) : $detail['spec']),
            'spec_weight'       => empty($detail['spec_weight']) ? 0.00 : (float) $detail['spec_weight'],
            'spec_volume'       => empty($detail['spec_volume']) ? 0.00 : (float) $detail['spec_volume'],
            'spec_coding'       => empty($detail['spec_coding']) ? '' : $detail['spec_coding'],
            'spec_barcode'      => empty($detail['spec_barcode']) ? '' : $detail['spec_barcode'],
            'buy_number'        => intval($detail['buy_number']),
            'model'             => $detail['model'],
            'add_time'          => time(),
        ];

        // 订单详情添加前钩子
        $hook_name = 'plugins_service_buy_order_detail_insert_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_id'      => $order_id,
            'user_id'       => $user_id,
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
            return DataReturn(MyLang('insert_success'), 0, $order_detail_id);
        }
        return DataReturn(MyLang('common_service.buy.order_detail_insert_fail_tips'), -1);
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
            return DataReturn(MyLang('insert_success'), 0);
        }
        return DataReturn(MyLang('common_service.buy.order_take_insert_fail_tips'), -1);
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
            return DataReturn(MyLang('insert_success'), 0);
        }
        return DataReturn(MyLang('common_service.buy.order_fictitious_insert_fail_tips'), -1);
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
            return DataReturn(MyLang('insert_success'), 0);
        }
        return DataReturn(MyLang('common_service.buy.order_address_insert_fail_tips'), -1);
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
                'error_msg'         => MyLang('order_id_error_tips'),
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
            return DataReturn(MyLang('common_service.buy.inventory_dec_not_enable_tips'), 0);
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
                return DataReturn(MyLang('common_service.buy.order_detail_data_error_tips'), -1);
            }

            // 数据校验
            foreach($order_detail as $v)
            {
                $ret = self::BuyOrderPayBeginGoodsCheck($v, $params);
                if($ret['code'] != 0)
                {
                    return $ret;
                }
            }
        }
        return DataReturn(MyLang('check_success'), 0);
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
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => MyLang('common_service.buy.order_data_error_tips'),
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
            return DataReturn(MyLang('common_service.buy.inventory_dec_not_enable_tips'), 0);
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
                return DataReturn(MyLang('common_service.buy.order_detail_data_error_tips'), -1);
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
                        $ret = self::BuyOrderPayBeginGoodsCheck($v, $params);
                        if($ret['code'] != 0)
                        {
                            return $ret;
                        }
                    }
                }
            }
        }
        return DataReturn(MyLang('check_success'), 0);
    }

    /**
     * 订单支付前商品校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-05
     * @desc    description
     * @param   [array]        $detail   [订单详情]
     * @param   [array]        $params   [输入参数]
     */
    public static function BuyOrderPayBeginGoodsCheck($detail, $params)
    {
        // 获取商品
        $goods = Db::name('Goods')->field('is_shelves,is_deduction_inventory,inventory,title')->find($detail['goods_id']);
        if(empty($goods))
        {
            return DataReturn(MyLang('common_service.buy.goods_no_exist_tips'), -10);
        }

        // 商品状态
        if($goods['is_shelves'] != 1)
        {
            return DataReturn(MyLang('common_service.buy.goods_already_shelves_tips').'['.$goods['title'].']', -10);
        }

        // 库存
        if(isset($goods['is_deduction_inventory']) && $goods['is_deduction_inventory'] == 1)
        {
            // 先判断商品库存是否不足
            if($goods['inventory'] < $detail['buy_number'])
            {
                return DataReturn(MyLang('common_service.buy.goods_inventory_not_enough_tips').'['.$goods['title'].'('.$goods['inventory'].'<'.$detail['buy_number'].')]', -10);
            }

            // 规格库存
            $spec = empty($detail['spec']) ? '' : json_decode($detail['spec'], true);
            $spec_params = array_merge($params, [
                'id'    => $detail['goods_id'],
                'spec'  => $spec,
            ]);
            $base = GoodsService::GoodsSpecDetail($spec_params);
            if($base['code'] == 0)
            {
                // 先判断商品规格库存是否不足
                if($base['data']['spec_base']['inventory'] < $detail['buy_number'])
                {
                    return DataReturn(MyLang('common_service.buy.goods_inventory_not_enough_tips').'['.$goods['title'].'('.$base['data']['spec_base']['inventory'].'<'.$detail['buy_number'].')]', -10);
                }
            } else {
                return $base;
            }
        }
        return DataReturn(MyLang('check_success'), 0);
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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'opt_type',
                'checked_data'      => ['confirm', 'pay', 'delivery'],
                'error_msg'         => MyLang('common_service.buy.order_inventory_dec_type_error_tips'),
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
            return DataReturn(MyLang('common_service.buy.inventory_dec_not_enable_tips'), 0);
        }

        // 扣除库存规则
        $common_deduction_inventory_rules = MyC('common_deduction_inventory_rules', 1);
        switch($common_deduction_inventory_rules)
        {
            // 订单确认成功
            case 0 :
                if($params['opt_type'] != 'confirm')
                {
                    return DataReturn(MyLang('common_service.buy.inventory_dec_not_confirm_tips').'['.$params['order_id'].']', 0);
                }
                break;

            // 订单支付成功
            case 1 :
                if($params['opt_type'] != 'pay')
                {
                    return DataReturn(MyLang('common_service.buy.inventory_dec_not_pay_tips').'['.$params['order_id'].']', 0);
                }
                break;

            // 订单发货
            case 2 :
                if($params['opt_type'] != 'delivery')
                {
                    return DataReturn(MyLang('common_service.buy.inventory_dec_not_delivery_tips').'['.$params['order_id'].']', 0);
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
                            return DataReturn(MyLang('common_service.buy.goods_inventory_not_enough_tips').'['.$goods['title'].'('.$goods['inventory'].'<'.$v['buy_number'].')]', -10);
                        }

                        // 扣除操作
                        if(!Db::name('Goods')->where(['id'=>$v['goods_id']])->dec('inventory', $v['buy_number'])->update())
                        {
                            return DataReturn(MyLang('common_service.buy.goods_inventory_dec_fail_tips').'['.$params['order_id'].'-'.$v['id'].'-'.$v['goods_id'].'('.$goods['inventory'].'-'.$v['buy_number'].')]', -10);
                        }

                        // 扣除规格库存
                        $spec = empty($v['spec']) ? '' : json_decode($v['spec'], true);
                        $spec_params = array_merge($params, [
                            'id'    => $v['goods_id'],
                            'spec'  => $spec,
                        ]);
                        $base = GoodsService::GoodsSpecDetail($spec_params);
                        if($base['code'] == 0)
                        {
                            // 先判断商品规格库存是否不足
                            if($base['data']['spec_base']['inventory'] < $v['buy_number'])
                            {
                                return DataReturn(MyLang('common_service.buy.goods_spec_inventory_not_enough_tips').'['.$goods['title'].'('.$base['data']['spec_base']['inventory'].'<'.$v['buy_number'].']', -10);
                            }

                            // 扣除规格操作
                            if(!Db::name('GoodsSpecBase')->where(['id'=>$base['data']['spec_base']['id'], 'goods_id'=>$v['goods_id']])->dec('inventory', $v['buy_number'])->update())
                            {
                                return DataReturn(MyLang('common_service.buy.goods_spec_inventory_dec_fail_tips').'['.$params['order_id'].'-'.$v['goods_id'].'('.$goods['inventory'].'-'.$v['buy_number'].')]', -10);
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
                            'order_status'          => Db::name('Order')->where(['id'=>$params['order_id']])->value('status'),
                            'original_inventory'    => $goods['inventory'],
                            'new_inventory'         => Db::name('Goods')->where(['id'=>$v['goods_id']])->value('inventory'),
                            'add_time'              => time(),
                        ];
                        if(Db::name('OrderGoodsInventoryLog')->insertGetId($log_data) <= 0)
                        {
                            return DataReturn(MyLang('common_service.buy.inventory_dec_log_insert_fail_tips').'['.$params['order_id'].'-'.$v['goods_id'].']', -100);
                        }
                    }
                }
            }
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('common_service.buy.inventory_dec_no_data_tips'), 0);
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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => MyLang('common_service.buy.order_data_error_tips'),
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
            // 仅订单取消、关闭操作库存回滚
            if(!in_array($params['order_data']['status'], [5,6]))
            {
                return DataReturn(MyLang('common_service.buy.inventory_revert_not_allow_tips').'['.$params['order_id'].'-'.$params['order_data']['status'].']', 0);
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

                    // 商品回滚操作
                    $temp_goods = Db::name('Goods')->where(['id'=>$v['goods_id']])->value('id');
                    if(!empty($temp_goods))
                    {
                            if(!Db::name('Goods')->where(['id'=>$v['goods_id']])->inc('inventory', $buy_number)->update())
                        {
                            return DataReturn(MyLang('common_service.buy.inventory_revert_goods_fail_tips').'['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                        }
                    }

                    // 回滚规格库存
                    $spec = empty($v['spec']) ? '' : json_decode($v['spec'], true);
                    $spec_params = array_merge($params, [
                        'id'    => $v['goods_id'],
                        'spec'  => $spec,
                    ]);
                    $base = GoodsService::GoodsSpecDetail($spec_params);
                    if($base['code'] == 0)
                    {
                        // 回滚规格操作
                        if(!Db::name('GoodsSpecBase')->where(['id'=>$base['data']['spec_base']['id'], 'goods_id'=>$v['goods_id']])->inc('inventory', $buy_number)->update())
                        {
                            return DataReturn(MyLang('common_service.buy.inventory_revert_goods_spec_fail_tips').'['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                        }
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
                        return DataReturn(MyLang('common_service.buy.inventory_revert_log_fail_tips').'['.$temp['id'].'-'.$params['order_id'].']', -100);
                    }
                }
            }
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('common_service.buy.inventory_revert_no_data_tips'), 0);
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

        return DataReturn(MyLang('operate_success'), 0, $result);
    }

    /**
     * 购买订单初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-07-31
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BuyOrderInit($params = [])
    {
        // 商品数据
        $ret = self::BuyTypeGoodsList($params);
        if(isset($ret['code']) && $ret['code'] == 0 && !empty($ret['data']))
        {
            // 是否开启虚拟订单快速创建订单
            if($ret['data']['base']['site_model'] == 3 && MyC('common_fictitious_order_direct_pay') == 1)
            {
                // 指定订单类型
                $params['site_model'] = $ret['data']['base']['site_model'];
                // 调用订单添加
                $ret = self::OrderInsert($params);
                if($ret['code'] == 0)
                {
                    // 标记订单已提交
                    $ret['data']['is_order_submit'] = 1;
                }
            }
        }
        return $ret;
    }
}
?>