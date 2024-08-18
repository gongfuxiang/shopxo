<?php
namespace think\exception;

use Attribute;

/**
 * 异常类可以定义类的注解属性 则始终输出错误信息
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AlwaysErrorMsg
{
}
