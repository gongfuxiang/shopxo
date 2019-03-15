<?php
namespace app\plugins\shopoauth;

use think\Db;
use app\service\PluginsService;
use app\service\UserService;

/**
 * 第三方登入 API - 钩子入口
 * @author   Guoguo
 * @blog     http://gadmin.cojz8.com
 * @version  1.0.0
 * @datetime 2019年3月14日
 */
class Hook
{
	/**
	 * 钩子入口
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
    public function run($params = [])
    {
        // 是否后端钩子
        if(!empty($params['hook_name']))
        {
            switch($params['hook_name'])
            {
                // 用户登录后更新关联表登录时间
                case 'plugins_service_user_login_end' :
                    $ret = $this->LoginUpdate($params);
                    break;

                // 顶部登录入口/登录信息
                case 'plugins_view_header_navigation_top_left' :
                    $ret = $this->LoginNavTopHtml($params);
                    break;

                // header代码
                case 'plugins_common_header' :
                    $ret = $this->Style($params);
                    break;

                // 用户中心资料列表
                case 'plugins_service_users_personal_show_field_list_handle' :
                    $ret = $this->UserPersonal($params);
                    break;

                default :
                    $ret = DataReturn('无需处理', 0);
            }
            return $ret;

        // 默认返回视图
        } else {
            return '';
        }
    }

    /**
     * 用户中心资料
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @param   [array]          $params [输入参数]
     * @desc    description
     */
    public function UserPersonal($params = [])
    {

        if(!empty($params['data']))
        {
            //读取用户信息
            $user = UserService::LoginUserInfo();
            if(!empty($user['id']))
            {
                //读取当前已经有的数据
                $ret = PluginsService::PluginsData('shopoauth');
                if(!empty($ret['data']['auth']))
                {
                    $html = '';
                    foreach($ret['data']['auth'] as $k=>$v)
                    {
                        if(isset($v['open']) && $v['open'] == 1)
                        {
                            $icon = strtoupper($k);
                            $name = empty($v['name']) ? $k : $v['name'];
                            $oauth = Db::name('PluginsShopoauthOauth')->where(['platform'=>$icon, 'user_id'=>$user['id']])->find();
                            $value = '未绑定';
                            if(!empty($oauth))
                            {
                                $value = $name.'('.$oauth['openname'].')';
                                $html .= '<a href="'.PluginsHomeUrl('shopoauth', 'auth', 'remove',['type'=>$k]).'">解绑</a>';
                            } else {
                                $html .= '<a href="'.PluginsHomeUrl('shopoauth', 'auth', 'login',['type'=>$k]).'"><i class="am-icon-'.$icon.'"></i> 前去绑定</a>';
                            }

                            $params['data'][] = [
                                'is_ext'    => 1,
                                'name'      => $name,
                                'value'     => $value,
                                'tips'      => $html,
                            ];
                        }
                    }
                }
            }    
        }
        return DataReturn('处理成功', 0);
    }

    /**
     * 前端顶部小导航展示登入
     * @author   Guoguo
     * @blog     http://gadmin.cojz8.com
     * @version  1.0.0
     * @datetime 2019年3月14日
     * @param    [array]          $params [输入参数]
     */
    public function LoginNavTopHtml($params = [])
    {
        // 获取已登录用户信息，已登录则不展示入口
        $user = UserService::LoginUserInfo();
        if(empty($user))
        {
            // 获取插件信息
            $ret = PluginsService::PluginsData('shopoauth');
            $html = '<div class="am-dropdown menu-hd plugins-shopoauth-nav-top" data-am-dropdown>
                        <a class="am-dropdown-toggle" href="javascript:;" target="_top" data-am-dropdown-toggle>
                            <i class="am-icon-cube am-icon-link"></i>
                            <span>第三方登入</span>
                            <i class="am-icon-caret-down"></i>
                        </a><ul class="am-dropdown-content">';
            if(!empty($ret['data']['auth']))
            {
                foreach($ret['data']['auth'] as $k=>$v)
                {
                    if(isset($v['open']) && $v['open'] == 1)
                    {
                        $name = empty($v['name']) ? $k : $v['name'];
                        $html .= '<li><a href="'.PluginsHomeUrl('shopoauth', 'auth', 'login',['type'=>$k]).'"><i class="am-icon-cube am-icon-qq"></i> '.$name.'</a></li>';
                    }
                }
                $html .= '</ul></div>';
                return $html;
            }
        }
        return '';
    }

    /**
     * css
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function Style($params = [])
    {
        return '<style type="text/css">
                    .plugins-shopoauth-nav-top { margin-left: 10px; }
                </style>';
    }

	/**
	 * 更新登录时间
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
    private function LoginUpdate($params)
    {
		$oauth_id = session('oauth_id');
		if(!empty($oauth_id) && !empty($params['user_id']))
        {
            $up_data = [
                'user_id'   => $params['user_id'],
                'logintime' => time()
            ];
            Db::name('PluginsShopoauthOauth')->where(['openid'=>$oauth_id])->update($up_data); 
		}
        return DataReturn('处理成功', 0);
    }
}
?>