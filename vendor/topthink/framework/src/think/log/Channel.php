<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2023 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
declare(strict_types = 1);

namespace think\log;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;
use think\contract\LogHandlerInterface;
use think\Event;
use think\event\LogRecord;
use think\event\LogWrite;

class Channel implements LoggerInterface
{
    use LoggerTrait;

    /**
     * 日志信息
     * @var array
     */
    protected $log = [];

    /**
     * 关闭日志
     * @var bool
     */
    protected $close = false;

    public function __construct(protected string $name, protected LogHandlerInterface $logger, protected array $allow, protected bool $lazy, protected Event $event)
    {
    }

    /**
     * 关闭通道
     */
    public function close(): void
    {
        $this->clear();
        $this->close = true;
    }

    /**
     * 清空日志
     */
    public function clear(): void
    {
        $this->log = [];
    }

    /**
     * 记录日志信息
     * @access public
     * @param mixed  $msg     日志信息
     * @param string $type    日志级别
     * @param array  $context 替换内容
     * @param bool   $lazy
     * @return $this
     */
    public function record($msg, string $type = 'info', array $context = [], bool $lazy = true)
    {
        if ($this->close || (!empty($this->allow) && !in_array($type, $this->allow))) {
            return $this;
        }

        if ($msg instanceof Stringable) {
            $msg = $msg->__toString();
        }

        if (is_string($msg) && !empty($context)) {
            $replace = [];
            foreach ($context as $key => $val) {
                $replace['{' . $key . '}'] = $val;
            }

            $msg = strtr($msg, $replace);
        }

        if (!empty($msg) || 0 === $msg) {
            $this->log[$type][] = $msg;
            if ($this->event) {
                $this->event->trigger(new LogRecord($type, $msg));
            }
        }

        if (!$this->lazy || !$lazy) {
            $this->save();
        }

        return $this;
    }

    /**
     * 实时写入日志信息
     * @access public
     * @param mixed  $msg     调试信息
     * @param string $type    日志级别
     * @param array  $context 替换内容
     * @return $this
     */
    public function write($msg, string $type = 'info', array $context = [])
    {
        return $this->record($msg, $type, $context, false);
    }

    /**
     * 获取日志信息
     * @return array
     */
    public function getLog(): array
    {
        return $this->log;
    }

    /**
     * 保存日志
     * @return bool
     */
    public function save(): bool
    {
        $log = $this->log;
        if ($this->event) {
            $event = new LogWrite($this->name, $log);
            $this->event->trigger($event);
            $log = $event->log;
        }

        if ($this->logger->save($log)) {
            $this->clear();
            return true;
        }

        return false;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed             $level
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $this->record($message, $level, $context);
    }

    public function __call($method, $parameters)
    {
        $this->log($method, ...$parameters);
    }
}
