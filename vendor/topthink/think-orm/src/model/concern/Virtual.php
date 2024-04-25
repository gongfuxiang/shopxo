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
declare(strict_types=1);

namespace think\model\concern;

use think\db\BaseQuery as Query;
use think\db\exception\DbException as Exception;
use think\Model;

/**
 * 虚拟模型.
 */
trait Virtual
{
    /**
     * 获取当前模型的数据库查询对象
     *
     * @param array $scope 设置不使用的全局查询范围
     *
     * @return Query
     */
    public function db($scope = []): Query
    {
        throw new Exception('virtual model not support db query');
    }

    /**
     * 获取字段类型信息.
     *
     * @param string $field 字段名
     *
     * @return string|null
     */
    public function getFieldType(string $field)
    {
    }

    /**
     * 保存当前数据对象
     *
     * @param array|object  $data     数据
     * @param string $sequence 自增序列名
     *
     * @return bool
     */
    public function save(array|object $data = [], string $sequence = null): bool
    {
        if ($data instanceof Model) {
            $data = $data->getData();
        } elseif (is_object($data)) {
            $data = get_object_vars($data);
        }

        // 数据对象赋值
        $this->setAttrs($data);

        if ($this->isEmpty() || false === $this->trigger('BeforeWrite')) {
            return false;
        }

        // 写入回调
        $this->trigger('AfterWrite');

        $this->exists(true);

        return true;
    }

    /**
     * 删除当前的记录.
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$this->isExists() || $this->isEmpty() || false === $this->trigger('BeforeDelete')) {
            return false;
        }

        // 关联删除
        if (!empty($this->relationWrite)) {
            $this->autoRelationDelete();
        }

        $this->trigger('AfterDelete');

        $this->exists(false);

        return true;
    }
}
