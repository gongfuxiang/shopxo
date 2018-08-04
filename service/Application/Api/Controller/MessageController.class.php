<?php

namespace Home\Controller;

/**
 * 消息
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MessageController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 登录校验
        $this->Is_Login();

        // 站点校验
        if(APPLICATION_CLIENT == 'shanghu')
        {
            $this->Is_Merchant();
        }
    }

    /**
     * [Index 获取记录]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:08:01+0800
     */
    public function Index()
    {
        $m = M('Message');

        // 获取数据
        $number = 15;
        $page = intval(I('page', 1));
        $where = $this->GetIndexWhere();
        $total = $m->where($where)->count();
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);
        $field = 'id,title,detail,is_read,add_time';
        $data = $m->where($where)->field($field)->limit($start, $number)->order('id desc')->select();

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $this->SetData($data),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * [SetData 数据设置]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-13T23:08:38+0800
     * @param    [array]       $data [数据]
     */
    private function SetData($data)
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['add_time']  = date('Y-m-d H:i', $v['add_time']);
            }
        }
        return $data;
    }

    /**
     * [GetIndexWhere 列表条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:13:32+0800
     */
    private function GetIndexWhere()
    {
        $where = array(
            'is_delete_time'    => 0,
            'user_id'           => $this->user['id'],
        );
        return $where;
    }
}
?>