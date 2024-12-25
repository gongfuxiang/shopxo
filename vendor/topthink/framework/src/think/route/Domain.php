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

namespace think\route;

use think\Route;

/**
 * 域名路由
 */
class Domain extends RuleGroup
{
    /**
     * 架构函数
     * @access public
     * @param  Route       $router   路由对象
     * @param  string      $name     路由域名
     * @param  mixed       $rule     域名路由
     * @param  bool        $lazy   延迟解析
     */
    public function __construct(Route $router, ?string $name = null, $rule = null, bool $lazy = false)
    {
        $this->router = $router;
        $this->domain = $name;
        $this->rule   = $rule;

        if (!$lazy && !is_null($rule)) {
            $this->parseGroupRule($rule);
        }
    }

}
