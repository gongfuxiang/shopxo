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

namespace think\model\concern;

/**
 * 自动写入主键.
 */
trait AutoWriteId
{
    /**
     * 是否需要自动写入主键.
     *
     * @var false|callable
     */
    protected $autoWriteId = false;

    /**
     * 是否需要自动写入.
     *
     * @return bool
     */
    public function isAutoWriteId(): bool
    {
        return $this->autoWriteId ? true : false;
    }

    /**
     * 设置自动写入方法.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function setAutoWriteId(callable $callable)
    {
        $this->autoWriteId = $callable;

        return $this;
    }

    /**
     * 自动写入主键.
     *
     * @return mixed
     */
    protected function autoWriteId()
    {
        $build = $this->autoWriteId;

        return is_callable($build) ? $build() : '';
    }

}
