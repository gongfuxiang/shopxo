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
namespace app\admin\form;

use think\facade\Db;

/**
 * 支付请求日志动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class PayRequestLog
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/payrequestlog/index'),
                'is_middle'     => 0,
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '业务类型',
                    'view_type'     => 'field',
                    'view_key'      => 'business_type',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('business_type'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '请求参数',
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'payrequestlog/module/request_params',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'request_params',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '响应数据',
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'payrequestlog/module/response_data',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'response_data',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '业务处理结果',
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'payrequestlog/module/business_handle',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '请求url地址',
                    'view_type'     => 'field',
                    'view_key'      => 'request_url',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'business_handle',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '端口号',
                    'view_type'     => 'field',
                    'view_key'      => 'server_port',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('server_port'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '服务器ip',
                    'view_type'     => 'field',
                    'view_key'      => 'server_ip',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '客户端ip',
                    'view_type'     => 'field',
                    'view_key'      => 'client_ip',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '操作系统',
                    'view_type'     => 'field',
                    'view_key'      => 'os',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('os'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '浏览器',
                    'view_type'     => 'field',
                    'view_key'      => 'browser',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('browser'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '请求类型',
                    'view_type'     => 'field',
                    'view_key'      => 'method',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('method'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => 'http类型',
                    'view_type'     => 'field',
                    'view_key'      => 'scheme',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('scheme'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => 'http版本',
                    'view_type'     => 'field',
                    'view_key'      => 'version',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->PayRequestLogFieldWhereList('version'),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '客户端详情',
                    'view_type'     => 'field',
                    'view_key'      => 'client',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '更新时间',
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'payrequestlog/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }

    /**
     * 条件字段列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-23
     * @desc    description
     * @param   [string]          $field [字段名称]
     */
    public function PayRequestLogFieldWhereList($field)
    {
        return Db::name('PayRequestLog')->field($field.' as name')->group($field)->select()->toArray();
    }
}
?>