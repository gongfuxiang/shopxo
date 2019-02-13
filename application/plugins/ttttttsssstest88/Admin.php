<?php
namespace app\plugins\ttttttsssstest88;

/**
 * 测试应用 - 后台管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin
{
    // 后台管理入口
    public function index($params = [])
    {
        // 数组组装
        $data = [
            'data'  => ['hello', 'world!'],
            'msg'   => 'hello world! admin',
        ];
        return DataReturn('处理成功', 0, $data);
    }
}
?>