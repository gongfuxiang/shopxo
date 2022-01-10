<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2021 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\middleware;

use Closure;
use think\App;
use think\Config;
use think\Cookie;
use think\Lang;
use think\Request;
use think\Response;

/**
 * 多语言加载
 */
class LoadLangPack
{
    protected $app;
    protected $lang;
    protected $config;

    public function __construct(App $app, Lang $lang, Config $config)
    {
        $this->app    = $app;
        $this->lang   = $lang;
        $this->config = $lang->getConfig();
    }

    /**
     * 路由初始化（路由规则注册）
     * @access public
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        // 自动侦测当前语言
        $langset = $this->detect($request);

        if ($this->lang->defaultLangSet() != $langset) {
            $this->lang->switchLangSet($langset);
        }

        $this->saveToCookie($this->app->cookie, $langset);

        return $next($request);
    }

    /**
     * 自动侦测设置获取语言选择
     * @access protected
     * @param Request $request
     * @return string
     */
    protected function detect(Request $request): string
    {
        // 自动侦测设置获取语言选择
        $langSet = '';

        if ($request->get($this->config['detect_var'])) {
            // url中设置了语言变量
            $langSet = strtolower($request->get($this->config['detect_var']));
        } elseif ($request->header($this->config['header_var'])) {
            // Header中设置了语言变量
            $langSet = strtolower($request->header($this->config['header_var']));
        } elseif ($request->cookie($this->config['cookie_var'])) {
            // Cookie中设置了语言变量
            $langSet = strtolower($request->cookie($this->config['cookie_var']));
        } elseif ($request->server('HTTP_ACCEPT_LANGUAGE')) {
            // 自动侦测浏览器语言
            $match = preg_match('/^([a-z\d\-]+)/i', $request->server('HTTP_ACCEPT_LANGUAGE'), $matches);
            if ($match) {
                $langSet = strtolower($matches[1]);
                if (isset($this->config['accept_language'][$langSet])) {
                    $langSet = $this->config['accept_language'][$langSet];
                }
            }
        }

        if (empty($this->config['allow_lang_list']) || in_array($langSet, $this->config['allow_lang_list'])) {
            // 合法的语言
            $range = $langSet;
            $this->lang->setLangSet($range);
        } else {
            $range = $this->lang->getLangSet();
        }

        return $range;
    }

    /**
     * 保存当前语言到Cookie
     * @access protected
     * @param Cookie $cookie Cookie对象
     * @param string $langSet 语言
     * @return void
     */
    protected function saveToCookie(Cookie $cookie, string $langSet)
    {
        if ($this->config['use_cookie']) {
            $cookie->set($this->config['cookie_var'], $langSet);
        }
    }

}
