<?php
namespace app\plugins\ttttttsssstest88;

/**
 * 测试应用 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook
{
    // 应用响应入口
    public function run($params = [])
    {
        // 是否控制器钩子
        if(isset($params['is_control']) && $params['is_control'] === true)
        {
            return [];

        // 默认返回视图
        } else {
            return 'hello world!';
        }
    }
}
?>