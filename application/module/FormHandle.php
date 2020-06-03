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
namespace app\module;

use think\Controller;

/**
 * 动态表格处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-02
 * @desc    description
 */
class FormHandle
{
    /**
     * 条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     * @param   [array]           $data     [动态表格配置信息]
     * @param   [array]           $params   [输入参数]
     */
    public function Run($data, $params = [])
    {
        $w = [];
        $p = [];
        if(!empty($data['form']))
        {
            foreach($data['form'] as $k=>&$v)
            {
                // 基础数据处理
                
                
                // 条件处理
                if(isset($v['search_config']) && !empty($v['search_config']['form_type']) && !empty($v['search_config']['form_name']))
                {
                    $key = 'fp'.$k;
                    $name = $v['search_config']['form_name'];
                    $type = isset($v['search_config']['where_type']) ? $v['search_config']['where_type'] : $v['search_config']['form_type'];
                    switch($type)
                    {
                        // 单个值
                        case '=' :
                        case '<' :
                        case '>' :
                        case '<=' :
                        case '>=' :
                        case 'like' :
                            if(array_key_exists($key, $params) && $params[$key] !== null && $params[$key] !== '')
                            {
                                // 参数值
                                $value = urldecode($params[$key]);
                                $p[$key] = $value;

                                // 条件
                                $w[] = [$name, $type, $value];
                            }
                            break;

                        // in
                        case 'in' :
                            if(array_key_exists($key, $params) && $params[$key] !== null && $params[$key] !== '')
                            {
                                // 参数值
                                $value = urldecode($params[$key]);
                                if(!is_array($value))
                                {
                                    $value = explode(',', $value);
                                }
                                $p[$key] = $value;

                                // 条件
                                $w[] = [$name, $type, $value];
                            }
                            break;

                        // 区间值
                        case 'section' :
                            $key_min = $key.'_min';
                            $key_max = $key.'_max';
                            if(array_key_exists($key_min, $params) && $params[$key_min] !== null && $params[$key_min] !== '')
                            {
                                // 参数值
                                $value = urldecode($params[$key_min]);
                                $p[$key_min] = $value;

                                // 条件
                                $w[] = [$name, '>=', $value];
                            }
                            if(array_key_exists($key_max, $params) && $params[$key_max] !== null && $params[$key_max] !== '')
                            {
                                // 参数值
                                $value = urldecode($params[$key_max]);
                                $p[$key_max] = $value;

                                // 条件
                                $w[] = [$name, '<=', $value];
                            }
                            break;

                        // 时间
                        case 'datetime' :
                        case 'date' :
                            $key_start = $key.'_start';
                            $key_end = $key.'_end';
                            if(array_key_exists($key_start, $params) && $params[$key_start] !== null && $params[$key_start] !== '')
                            {
                                // 参数值
                                $value = urldecode($params[$key_start]);
                                $p[$key_start] = $value;

                                // 条件
                                $w[] = [$name, '>=', strtotime($value)];
                            }
                            if(array_key_exists($key_end, $params) && $params[$key_end] !== null && $params[$key_end] !== '')
                            {
                                // 参数值
                                $value = urldecode($params[$key_end]);
                                $p[$key_end] = $value;

                                // 条件
                                $w[] = [$name, '<=', strtotime($value)];

                            }
                            break;
                    }
                }
            }
        }
        return [
            'where'     => $w,
            'params'    => $p,
        ];
    }
}
?>