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
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use app\service\ErrorLogService;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 请求对象
        $request = request();
        // 请求参数
        $params = $request->param();
        //运行时长（单位 秒）
        $runtime = round(microtime(true) - app()->getBeginTime(), 10);
        // 内存占用
        $memory_use = (memory_get_usage() -  app()->getBeginMem()) / 1024;
        if($memory_use > 1024)
        {
            $memory_use = $memory_use / 1024;
            if($memory_use > 1024)
            {
                $memory_use = number_format($memory_use / 1024, 2).'GB';
            } else {
                $memory_use = number_format($memory_use, 2).'MB';
            }
        } else {
            $memory_use = number_format($memory_use, 2).'KB';
        }
        $data = [
            'message'         => $this->getMessage($exception),
            'file'            => $exception->getFile(),
            'line'            => $exception->getLine(),
            'code'            => $this->getCode($exception),
            'ip'              => $request->ip(),
            'uri'             => $request->url(),
            'request_params'  => empty($params) ? '' : (is_array($params) ? json_encode($params, JSON_UNESCAPED_UNICODE) : $params),
            'tsc'             => number_format($runtime, 6),
            'memory_use'      => $memory_use,
            'add_time'        => time(),
        ];
        ErrorLogService::ErrorLogAdd($data);

        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 存在token也返回json处理结果
        $token = $request->param('token');
        // 添加自定义异常处理机制
        if(IS_AJAX || !empty($token))
        {
            // 参数验证错误
            if($e instanceof ValidateException)
            {
                $msg = $e->getError();
                $code = -422;
            }

            // 请求异常
            if($e instanceof HttpException && request()->isAjax())
            {
                $msg = $e->getMessage();
                $code = $e->getStatusCode();
            }

            if(!isset($code))
            {
                $code = -500;
            }
            if(empty($msg))
            {
                if(method_exists($e, 'getMessage'))
                {
                    $msg = $e->getMessage();
                } else {
                    $msg = '服务器错误';
                }
            }

            // 结束并设置响应头
            header('Content-Type: application/json; charset=utf-8');
            exit(json_encode(DataReturn($msg, $code)));
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
?>