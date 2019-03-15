<?php
namespace app\plugins\shopoauth;

use think\Controller;
use app\plugins\shopoauth\ThinkOauth;
use app\service\PluginsService;

/**
 * 第三方登入 API - 钩子入口
 * @author   Guoguo
 * @blog     http://gadmin.cojz8.com
 * @version  1.0.0
 * @datetime 2019年3月14日
 */
class Admin extends Controller
{
    /**
	 * 后台首页
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
    public function index($params = [])
    {
        $ret = PluginsService::PluginsData('shopoauth');
        if($ret['code'] == 0)
        {
            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/shopoauth/admin/index');
        } else {
            return $ret['msg'];
        }
        
    }
	/**
	 * 参数配置
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
    public function saveinfo($params = [])
    {
        $ret = PluginsService::PluginsData('shopoauth');
        if($ret['code'] == 0)
        {
            // 是否
            $is_whether_list =  [
                0 => array('id' => 0, 'name' => '否'),
                1 => array('id' => 1, 'name' => '是', 'checked' => true),
            ];
            $this->assign('is_whether_list', $is_whether_list);
            
            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/shopoauth/admin/saveinfo');
        } else {
            return $ret['msg'];
        }
    }
	/**
	 * 数据编辑
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
    public function save($params = [])
    {
        return PluginsService::PluginsDataSave(['plugins'=>'shopoauth', 'data'=>$params]);
    }
}
?>