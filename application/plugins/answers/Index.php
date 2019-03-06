<?php
namespace app\plugins\answers;

use think\Controller;

/**
 * 问答 - 前端独立页面入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    // 前端页面入口
    public function index($params = [])
    {
        // 数组组装
        $this->assign('data', ['hello', 'world!']);
        $this->assign('msg', 'hello world! index');
        return $this->fetch('../../../plugins/view/answers/index/index');
    }
}
?>