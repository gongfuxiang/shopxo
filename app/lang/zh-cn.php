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

/**
 * 公共语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'page_common'           => [
        // 基础
        'chosen_select_no_results_text'     => '没有匹配到结果',
        'error_text'                        => '异常错误',
        'reminder_title'                    => '温馨提示',
        'operate_params_error'              => '操作参数有误',
        'not_operate_error'                 => '没有相关操作',
        'not_data_error'                    => '没有相关数据',
        'select_reverse_name'               => '反选',
        'select_all_name'                   => '全选',
        'loading_tips'                      => '加载中...',
        'goods_stock_max_tips'              => '最大限购数量',
        'goods_stock_min_tips'              => '最低起购数量',
        'goods_inventory_number_tips'       => '库存数量',
        'goods_no_choice_spec_tips'         => '请选择规格',
        'store_enabled_tips'                => '您的浏览器不支持本地存储。请禁用“专用模式”，或升级到现代浏览器。',
        // 上传下载
        'get_loading_tips'                  => '正在获取中..',
        'download_loading_tips'             => '正在下载中..',
        'update_loading_tips'               => '正在更新中..',
        'install_loading_tips'              => '正在安装中..',
        'system_download_loading_tips'      => '系统包正在下载中...',
        'upgrade_download_loading_tips'     => '升级包正在下载中...',
        // 公共common.js
        'select_not_chosen_tips'            => '请选择项',
        'select_chosen_min_tips'            => '至少选择{value}项',
        'select_chosen_max_tips'            => '最多选择{value}项',
        'upload_images_max_tips'            => '最多上传{value}张图片',
        'upload_video_max_tips'             => '最多上传{value}个视频',
        'upload_annex_max_tips'             => '最多上传{value}个附件',
        'form_config_type_params_tips'      => '表单[类型]参数配置有误',
        'form_config_value_params_tips'     => '表单[类型]参数配置有误',
        'form_call_fun_not_exist_tips'      => '表单定义的方法未定义',
        'form_config_main_tips'             => '表单[action或method]参数配置有误',
        'max_input_vars_tips'               => '请求参数数量已超出php.ini限制',
        'operate_add_name'                  => '新增',
        'operate_edit_name'                 => '编辑',
        'operate_delete_name'               => '删除',
        'upload_images_format_tips'         => '图片格式错误，请重新上传',
        'upload_video_format_tips'          => '视频格式错误，请重新上传',
        'ie_browser_tips'                   => 'ie浏览器不可用',
        'browser_api_error_tips'            => '浏览器不支持全屏API或已被禁用',
        'request_handle_loading_tips'       => '正在处理中、请稍候...',
        'params_error_tips'                 => '参数配置有误',
        'config_fun_not_exist_tips'         => '配置方法未定义',
        'delete_confirm_tips'               => '删除后不可恢复、确认操作吗？',
        'operate_confirm_tips'              => '操作后不可恢复、确认继续吗？',
        'window_close_confirm_tips'         => '您确定要关闭本页吗？',
        'fullscreen_open_name'              => '开启全屏',
        'fullscreen_exit_name'              => '退出全屏',
        'map_dragging_icon_tips'            => '拖动红色图标直接定位',
        'map_type_not_exist_tips'           => '该地图功能未定义',
        'map_address_analysis_tips'         => '您选择地址没有解析到结果！',
        'map_coordinate_tips'               => '坐标有误',
        'before_choice_data_tips'           => '请先选择数据',
        'address_data_empty_tips'           => '地址为空',
        'assembly_not_init_tips'            => '组件未初始化',
        'not_specified_container_tips'      => '未指定容器',
        'not_specified_assembly_tips'       => '未指定加载组建',
        'not_specified_form_name_tips'      => '未指定表单name名称',
        'not_load_lib_hiprint_error'        => '请先引入hiprint组件库',
    ],

    // 公共基础
    'common'            => [
        'error'             => '异常错误',
        'operate_fail'      => '操作失败',
        'operate_success'   => '操作成功',
        'update_fail'       => '更新失败',
        'update_success'    => '更新成功',
        'insert_fail'       => '添加失败',
        'insert_success'    => '添加成功',
        'edit_fail'         => '编辑失败',
        'edit_success'      => '编辑成功',
        'change_fail'       => '修改失败',
        'change_success'    => '修改成功',
        'delete_fail'       => '删除失败',
        'delete_success'    => '删除成功',
        'cancel_fail'       => '取消失败',
        'cancel_success'    => '取消成功',
        'close_fail'        => '关闭失败',
        'close_success'     => '关闭成功',
        'send_fail'         => '发送失败',
        'send_success'      => '发送成功',
        'join_fail'         => '加入失败',
        'join_success'      => '加入成功',
        'created_fail'      => '生成失败',
        'created_success'   => '生成成功',
        'auth_fail'         => '授权失败',
        'auth_success'      => '授权成功',
        'upload_fail'       => '上传失败',
        'upload_success'    => '上传成功',
        'apply_fail'        => '申请失败',
        'apply_success'     => '申请成功',
        'handle_fail'       => '处理失败',
        'handle_success'    => '处理成功',
        'handle_noneed'     => '无需处理',
        'loading_fail'      => '加载失败',
        'loading_success'   => '加载成功',
        'request_fail'      => '请求失败',
        'request_success'   => '请求成功',
        'logout_fail'       => '注销失败',
        'logout_success'    => '注销成功',
        'no_data'           => '没有相关数据',
    ],
];
?>