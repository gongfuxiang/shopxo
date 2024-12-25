<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2023 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\route\dispatch;

use think\exception\ClassNotFoundException;
use think\route\Dispatch;

/**
 * Callback Dispatcher
 */
class Callback extends Dispatch
{
    public function exec()
    {
        // 执行回调方法
        if (is_array($this->dispatch)) {
            [$class, $action] = $this->dispatch;

            // 设置当前请求的控制器、操作
            $controllerLayer = $this->rule->config('controller_layer') ?: 'controller';
            if (str_contains($class, '\\' . $controllerLayer . '\\')) {
                [$layer, $controller] = explode('/' . $controllerLayer . '/', trim(str_replace('\\', '/', $class), '/'));
                $layer                = trim(str_replace('app', '', $layer), '/');
            } else {
                $layer      = '';
                $controller = trim(str_replace('\\', '/', $class), '/');
            }

            if ($layer && !empty($this->option['auto_middleware'])) {
                // 自动为顶层layer注册中间件
                $alias = $this->app->config->get('middleware.alias', []);

                if (isset($alias[$layer])) {
                    $this->app->middleware->add($layer, 'route');
                }
            }

            $this->request
                ->setLayer($layer)
                ->setController($controller)
                ->setAction($action);

            if (class_exists($class)) {
                $instance = $this->app->invokeClass($class);
            } else {
                throw new ClassNotFoundException('class not exists:' . $class, $class);
            }

            return $this->responseWithMiddlewarePipeline($instance, $action);
        }

        $vars = $this->getActionBindVars();
        return $this->app->invoke($this->dispatch, $vars);
    }
}
