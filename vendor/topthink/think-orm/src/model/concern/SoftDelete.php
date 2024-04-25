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
use think\Model;

/**
 * 数据软删除
 *
 * @mixin Model
 *
 * @method $this withTrashed()
 * @method $this onlyTrashed()
 */
trait SoftDelete
{
    public function db($scope = []): Query
    {
        $query = parent::db($scope);
        $this->withNoTrashed($query);

        return $query;
    }

    /**
     * 判断当前实例是否被软删除.
     *
     * @return bool
     */
    public function trashed(): bool
    {
        $field = $this->getDeleteTimeField();

        if ($field && !empty($this->getOrigin($field))) {
            return true;
        }

        return false;
    }

    public function scopeWithTrashed(Query $query): void
    {
        $query->removeOption('soft_delete');
    }

    public function scopeOnlyTrashed(Query $query): void
    {
        $field = $this->getDeleteTimeField(true);

        if ($field) {
            $query->useSoftDelete($field, $this->getWithTrashedExp());
        }
    }

    /**
     * 获取软删除数据的查询条件.
     *
     * @return array
     */
    protected function getWithTrashedExp(): array
    {
        return is_null($this->defaultSoftDelete) ? ['notnull', ''] : ['<>', $this->defaultSoftDelete];
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

        $name = $this->getDeleteTimeField();
        $force = $this->isForce();

        if ($name && !$force) {
            // 软删除
            $this->set($name, $this->autoWriteTimestamp());

            $this->exists()->withEvent(false)->save();

            $this->withEvent(true);
        } else {
            // 读取更新条件
            $where = $this->getWhere();

            // 删除当前模型数据
            $this->db()
                ->where($where)
                ->removeOption('soft_delete')
                ->delete();
        }

        // 关联删除
        if (!empty($this->relationWrite)) {
            $this->autoRelationDelete($force);
        }

        $this->trigger('AfterDelete');

        $this->exists(false);

        return true;
    }

    /**
     * 删除记录.
     *
     * @param mixed $data  主键列表 支持闭包查询条件
     * @param bool  $force 是否强制删除
     *
     * @return bool
     */
    public static function destroy($data, bool $force = false): bool
    {
        // 传入空值（包括空字符串和空数组）的时候不会做任何的数据删除操作，但传入0则是有效的
        if (empty($data) && 0 !== $data) {
            return false;
        }
        $model = (new static());

        $query = $model->db(false);

        // 仅当强制删除时包含软删除数据
        if ($force) {
            $query->removeOption('soft_delete');
        }

        if (is_array($data) && key($data) !== 0) {
            $query->where($data);
            $data = [];
        } elseif ($data instanceof \Closure) {
            call_user_func_array($data, [&$query]);
            $data = [];
        }

        $resultSet = $query->select((array) $data);

        foreach ($resultSet as $result) {
            /** @var Model $result */
            $result->force($force)->delete();
        }

        return true;
    }

    /**
     * 恢复被软删除的记录.
     *
     * @param array $where 更新条件
     *
     * @return bool
     */
    public function restore(array $where = []): bool
    {
        $name = $this->getDeleteTimeField();

        if (!$name || false === $this->trigger('BeforeRestore')) {
            return false;
        }

        if (empty($where)) {
            $pk = $this->getPk();
            if (is_string($pk)) {
                $where[] = [$pk, '=', $this->getData($pk)];
            }
        }

        // 恢复删除
        $this->db(false)
            ->where($where)
            ->useSoftDelete($name, $this->getWithTrashedExp())
            ->update([$name => $this->defaultSoftDelete]);

        $this->trigger('AfterRestore');

        return true;
    }

    /**
     * 获取软删除字段.
     *
     * @param bool $read 是否查询操作 写操作的时候会自动去掉表别名
     *
     * @return string|false
     */
    public function getDeleteTimeField(bool $read = false): bool|string
    {
        $field = property_exists($this, 'deleteTime') && isset($this->deleteTime) ? $this->deleteTime : 'delete_time';

        if (false === $field) {
            return false;
        }

        if (!str_contains($field, '.')) {
            $field = '__TABLE__.' . $field;
        }

        if (!$read && str_contains($field, '.')) {
            $array = explode('.', $field);
            $field = array_pop($array);
        }

        return $field;
    }

    /**
     * 查询的时候默认排除软删除数据.
     *
     * @param Query $query
     *
     * @return void
     */
    protected function withNoTrashed(Query $query): void
    {
        $field = $this->getDeleteTimeField(true);

        if ($field) {
            $condition = is_null($this->defaultSoftDelete) ? ['null', ''] : ['=', $this->defaultSoftDelete];
            $query->useSoftDelete($field, $condition);
        }
    }
}
