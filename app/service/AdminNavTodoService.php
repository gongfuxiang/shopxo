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

/**
 * 后台顶部导航「待处理」铃铛数据
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  1.0.0
 * @datetime 2026-05-13
 */
class AdminNavTodoService
{
    /**
     * 待处理铃铛数据（用于后台 nav）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-05-13
     * @param   [array]           $params [admin=管理员数组，可空则取登录态]
     * @return  [array]                   [data=>[[key,name,count,url,title]], total, has_pending]
     */
    public static function PendingTodoData($params = [])
    {
        // data 单项字段：
        // key   唯一标识
        // name  列表展示名称
        // count 待处理数量
        // url   业务页地址（模板用 submit-popup + data-offcanvas=right 在右侧侧栏打开）
        // title 侧栏标题
        // 扩展事件入参含 admin、data（引用，可 push 与上同结构的元素）
        $admin = isset($params['admin']) ? $params['admin'] : AdminService::LoginInfo();
        $data = [];
        if(empty($admin))
        {
            return [
                'data'        => [],
                'total'       => 0,
                'has_pending' => false,
            ];
        }

        // 订单待发货（status=2 与订单列表筛选项一致）
        if(AdminIsPower('order', 'index'))
        {
            $count = (int) Db::name('Order')->where([
                ['status', '=', 2],
                ['is_delete_time', '=', 0],
            ])->count();
            if($count > 0)
            {
                $data[] = [
                    'key'   => 'order',
                    'name'  => MyLang('common.nav_pending_todo_order_delivery'),
                    'count' => $count,
                    'url'   => MyUrl('admin/order/index', ['status' => 2]),
                    'title' => MyLang('common.nav_pending_todo_order_delivery'),
                ];
            }
        }

        // 订单售后待确认/待审核（status 0、2 与列表筛选项一致）
        if(AdminIsPower('orderaftersale', 'index'))
        {
            $count = (int) Db::name('OrderAftersale')->where([
                ['status', 'in', [0, 2]],
            ])->count();
            if($count > 0)
            {
                $data[] = [
                    'key'   => 'order_aftersale',
                    'name'  => MyLang('common.nav_pending_todo_order_aftersale_audit'),
                    'count' => $count,
                    'url'   => MyUrl('admin/orderaftersale/index', ['status' => '0,2']),
                    'title' => MyLang('common.nav_pending_todo_order_aftersale_audit'),
                ];
            }
        }

        // 商品评论待回复（is_reply=0 与列表筛选项一致）
        if(AdminIsPower('goodscomments', 'index'))
        {
            $count = (int) Db::name('GoodsComments')->where([
                ['is_reply', '=', 0],
            ])->count();
            if($count > 0)
            {
                $data[] = [
                    'key'   => 'goods_comments',
                    'name'  => MyLang('common.nav_pending_todo_goods_comments_reply'),
                    'count' => $count,
                    'url'   => MyUrl('admin/goodscomments/index', ['is_reply' => 0]),
                    'title' => MyLang('common.nav_pending_todo_goods_comments_reply'),
                ];
            }
        }

        // 用户待审核（status=3 与用户列表筛选项「待审核」一致）
        if(AdminIsPower('user', 'index'))
        {
            $count = (int) Db::name('User')->where([
                ['status', '=', 3],
                ['is_delete_time', '=', 0],
            ])->count();
            if($count > 0)
            {
                $data[] = [
                    'key'   => 'user_pending_audit',
                    'name'  => MyLang('common.nav_pending_todo_user_audit'),
                    'count' => $count,
                    'url'   => MyUrl('admin/user/index', ['status' => 3]),
                    'title' => MyLang('common.nav_pending_todo_user_audit'),
                ];
            }
        }

        $hook_name = 'plugins_service_admin_nav_pending_todo_data';
        MyEventTrigger($hook_name, [
            'hook_name'  => $hook_name,
            'is_backend' => true,
            'admin'      => $admin,
            'data'       => &$data,
        ]);

        $total = 0;
        if(!empty($data) && is_array($data))
        {
            foreach($data as $v)
            {
                if(!empty($v['count']))
                {
                    $total += (int) $v['count'];
                }
            }
        }

        return [
            'data'        => is_array($data) ? $data : [],
            'total'       => $total,
            'has_pending' => $total > 0,
        ];
    }
}
?>