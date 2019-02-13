<?php
namespace app\plugins\ttttttsssstest88;

/**
 * 测试应用 - 前端独立页面入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index
{
    // 前端页面入口
    public function index($params = [])
    {
        // 数组组装
        $data = [
            'data'  => ['hello', 'world!'],
            'msg'   => 'hello world! index',
        ];
        return DataReturn('处理成功', 0, $data);
    }
}
?>