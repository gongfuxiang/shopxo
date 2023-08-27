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
namespace base;

/**
 * 地图驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-04-11T16:50:41+0800
 */
class Map
{
    // 配置信息
    private $config;

    // 当前默认地图
    private $map_type;

    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-07
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        // 当前默认地图
        $this->map_type = MyC('common_map_type', 'baidu', true);
    }

    /**
     * 地址逆解析
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-07
     * @desc    description
     * @param   [string]          $address [地址信息]
     */
    public function Geocoder($address)
    {
        // 配置初始化
        $ret = $this->ConfigInit();
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 根据类型处理
        switch($this->map_type)
        {
            // 百度
            case 'baidu' :
                $res = json_decode(CurlGet('https://api.map.baidu.com/geocoding/v3/?ak='.$this->config['common_baidu_map_ak_server'].'&address='.$address.'&output=json'), true);
                if(isset($res['status']) && $res['status'] == 0)
                {
                    return DataReturn('success', 0, [
                        'lng'    => $res['result']['location']['lng'],
                        'lat'    => $res['result']['location']['lat'],
                        'level'  => $res['result']['level'],
                    ]);
                }
                return DataReturn(empty($res['message']) ? MyLang('common_extend.base.map.address_geocoder_fail_tips') : $res['message'], -1);
                break;

            // 腾讯
            case 'tencent' :
                $res = json_decode(CurlGet('https://apis.map.qq.com/ws/geocoder/v1/?key='.$this->config['common_tencent_map_ak_server'].'&address='.$address.'&output=json'), true);
                if(isset($res['status']) && $res['status'] == 0)
                {
                    return DataReturn('success', 0, [
                        'lng'    => $res['result']['location']['lng'],
                        'lat'    => $res['result']['location']['lat'],
                        'level'  => $res['result']['level'],
                    ]);
                }
                return DataReturn(empty($res['message']) ? MyLang('common_extend.base.map.address_geocoder_fail_tips') : $res['message'], -1);
                break;

            // 高德
            case 'amap' :
                $res = json_decode(CurlGet('https://restapi.amap.com/v3/geocode/geo?key='.$this->config['common_amap_map_ak_server'].'&address='.$address.'&output=json'), true);
                if(isset($res['status']) && $res['status'] == 1)
                {
                    $temp = explode(',', $res['geocodes'][0]['location']);
                    return DataReturn('success', 0, [
                        'lng'    => $temp[0],
                        'lat'    => $temp[1],
                        'level'  => $res['geocodes'][0]['level'],
                    ]);
                }
                return DataReturn(empty($res['info']) ? MyLang('common_extend.base.map.address_geocoder_fail_tips') : $res['info'], -1);
                break;

            // 天地图
            case 'tianditu' :
                $res = json_decode(CurlGet('http://api.tianditu.gov.cn/geocoder?tk='.$this->config['common_tianditu_map_ak_server'].'&ds={"keyWord":"'.$address.'"}'), true);
                if(isset($res['status']) && $res['status'] == 0)
                {
                    return DataReturn('success', 0, [
                        'lng'    => $res['location']['lon'],
                        'lat'    => $res['location']['lat'],
                        'level'  => $res['location']['level'],
                    ]);
                }
                return DataReturn(empty($res['msg']) ? MyLang('common_extend.base.map.address_geocoder_fail_tips') : $res['msg'], -1);
                break;

            default :
                return DataReturn(MyLang('common_extend.base.map.type_not_docked_geocoder_tips').'('.$this->map_type.')', -1);
        }
    }

    /**
     * 配置初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-07
     * @desc    description
     */
    public function ConfigInit()
    {
        // 根据类型处理
        switch($this->map_type)
        {
            // 百度
            case 'baidu' :
                $this->config['common_baidu_map_ak_server'] = MyC('common_baidu_map_ak_server');
                if(empty($this->config['common_baidu_map_ak_server']))
                {
                    return DataReturn(MyLang('common_extend.base.map.baidu_config_empty_tips'), -1);
                }
                break;

            // 腾讯
            case 'tencent' :
                $this->config['common_tencent_map_ak_server'] = MyC('common_tencent_map_ak_server');
                if(empty($this->config['common_tencent_map_ak_server']))
                {
                    return DataReturn(MyLang('common_extend.base.map.tencent_config_empty_tips'), -1);
                }
                break;

            // 高德
            case 'amap' :
                $this->config['common_amap_map_ak_server'] = MyC('common_amap_map_ak_server');
                if(empty($this->config['common_amap_map_ak_server']))
                {
                    return DataReturn(MyLang('common_extend.base.map.amap_config_empty_tips'), -1);
                }
                break;

            // 天地图
            case 'tianditu' :
                $this->config['common_tianditu_map_ak_server'] = MyC('common_tianditu_map_ak_server');
                if(empty($this->config['common_tianditu_map_ak_server']))
                {
                    return DataReturn(MyLang('common_extend.base.map.tianditu_config_empty_tips'), -1);
                }
                break;

            default :
                return DataReturn(MyLang('common_extend.base.map.type_not_docked_tips').'('.$this->map_type.')', -1);
        }
        return DataReturn('success', 0);
    }
}
?>