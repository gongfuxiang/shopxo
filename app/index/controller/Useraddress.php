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
namespace app\index\controller;

use app\index\controller\Center;
use app\service\ApiService;
use app\service\SeoService;
use app\service\UserAddressService;
use app\service\ResourcesService;

/**
 * 用户地址管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserAddress extends Center
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
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 浏览器名称
            'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('useraddress.base_nav_title'), 1),
        ];
        // 用户地址列表
        $data = UserAddressService::UserAddressList(['user'=>$this->user]);
        $assign['user_address_list'] = $data['data'];

        // 模板赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 地址添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 模板数据
        $assign = [
            // 关闭头尾
            'is_header'         => 0,
            'is_footer'         => 0,
            // 加载地图
            'is_load_map_api'   => MyC('home_user_address_map_status'),
            // 编辑器文件存放地址
            'editor_path_type'  => ResourcesService::EditorPathTypeValue(UserAddressService::EditorAttachmentPathType($this->user['id'])),
        ];

        // 地址数据
        if(!empty($this->data_request))
        {
            $params = $this->data_request;
            $params['user'] = $this->user;
            $ret = UserAddressService::UserAddressRow($params);
            $data = $ret['data'];
        }
        $assign['data'] = $data;

        // 模板赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-23T22:36:18+0800
     */
    public function Save()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(UserAddressService::UserAddressSave($params));
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(UserAddressService::UserAddressDelete($params));
    }

    /**
     * 默认地址设置
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function SetDefault()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(UserAddressService::UserAddressDefault($params));
    }
}
?>