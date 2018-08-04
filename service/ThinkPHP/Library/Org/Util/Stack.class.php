<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Org\Util;

/**
 * Stack实现类
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    liu21st <liu21st@gmail.com>
 */
class Stack extends ArrayList {

    /**
     * 架构函数
     * @access public
     * @param array $values  初始化数组元素
     */
    public function __construct($values = array()) {
        parent::__construct($values);
    }

    /**
     * 将堆栈的内部指针指向第一个单元
     * @access public
     * @return mixed
     */
    public function peek() {
        return reset($this->toArray());
    }

    /**
     * 元素进栈
     * @access public
     * @param mixed $value
     * @return mixed
     */
    public function push($value) {
        $this->add($value);
        return $value;
    }

}
