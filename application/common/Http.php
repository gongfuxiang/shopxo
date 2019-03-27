<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\common;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;

/**
 * 异常处理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Http extends Handle
{
    /**
     * 异常处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-14
     * @desc    description
     * @param   Exception       $e [错误对象]
     */
    public function render(Exception $e)
    {
        // ajax请求处理
        if(IS_AJAX)
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
            exit(json_encode(DataReturn($msg, $code)));

        // web错误交给系统处理
        } else {
            return parent::render($e);
        }
    }
}
?>