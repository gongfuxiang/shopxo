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

namespace think\model;

use Closure;
use think\db\BaseQuery as Query;
use think\db\exception\DbException as Exception;
use think\Model;

/**
 * 模型关联基础类.
 *
 * @mixin Query
 */
abstract class Relation
{
    /**
     * 父模型对象
     *
     * @var Model
     */
    protected $parent;

    /**
     * 当前关联的模型类名.
     *
     * @var string
     */
    protected $model;

    /**
     * 关联模型查询对象
     *
     * @var Query
     */
    protected $query;

    /**
     * 关联表外键.
     *
     * @var string
     */
    protected $foreignKey;

    /**
     * 关联表主键.
     *
     * @var string
     */
    protected $localKey;

    /**
     * 是否执行关联基础查询.
     *
     * @var bool
     */
    protected $baseQuery;

    /**
     * 是否为自关联.
     *
     * @var bool
     */
    protected $selfRelation = false;

    /**
     * 关联数据字段限制.
     *
     * @var array
     */
    protected $withField;

    /**
     * 排除关联数据字段.
     *
     * @var array
     */
    protected $withoutField;

    /**
     * 默认数据.
     *
     * @var mixed
     */
    protected $default;

    /**
     * 获取关联的所属模型.
     *
     * @return Model
     */
    public function getParent(): Model
    {
        return $this->parent;
    }

    /**
     * 获取当前的关联模型类的Query实例.
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * 获取关联表外键.
     *
     * @return string
     */
    public function getForeignKey(): string
    {
        return $this->foreignKey;
    }

    /**
     * 获取关联表主键.
     *
     * @return string
     */
    public function getLocalKey(): string
    {
        return $this->localKey;
    }

    /**
     * 获取当前的关联模型类的实例.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->query->getModel();
    }

    /**
     * 当前关联是否为自关联.
     *
     * @return bool
     */
    public function isSelfRelation(): bool
    {
        return $this->selfRelation;
    }

    /**
     * 封装关联数据集.
     *
     * @param array $resultSet 数据集
     * @param Model $parent    父模型
     *
     * @return mixed
     */
    protected function resultSetBuild(array $resultSet, Model $parent = null)
    {
        return (new $this->model())->toCollection($resultSet)->setParent($parent);
    }

    protected function getQueryFields(string $model)
    {
        $fields = $this->query->getOptions('field');

        return $this->getRelationQueryFields($fields, $model);
    }

    protected function getRelationQueryFields($fields, string $model)
    {
        if (empty($fields) || '*' == $fields) {
            return $model . '.*';
        }

        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        foreach ($fields as &$field) {
            if (!str_contains($field, '.')) {
                $field = $model . '.' . $field;
            }
        }

        return $fields;
    }

    protected function getQueryWhere(array &$where, string $relation): void
    {
        foreach ($where as $key => &$val) {
            if (is_string($key)) {
                $where[] = [!str_contains($key, '.') ? $relation . '.' . $key : $key, '=', $val];
                unset($where[$key]);
            } elseif (isset($val[0]) && !str_contains($val[0], '.')) {
                $val[0] = $relation . '.' . $val[0];
            }
        }
    }

    /**
     * 获取关联数据默认值
     *
     * @param mixed $data 模型数据
     *
     * @return mixed
     */
    protected function getDefaultModel($data)
    {
        if (is_array($data)) {
            $model = (new $this->model())->data($data);
        } elseif ($data instanceof Closure) {
            $model = new $this->model();
            $data($model);
        } else {
            $model = $data;
        }

        return $model;
    }

    /**
     * 执行基础查询（仅执行一次）.
     *
     * @return void
     */
    protected function baseQuery(): void
    {
    }

    public function __call($method, $args)
    {
        if ($this->query) {
            // 执行基础查询
            $this->baseQuery();

            $result = call_user_func_array([$this->query, $method], $args);

            return $result === $this->query ? $this : $result;
        }

        throw new Exception('method not exists:' . __CLASS__ . '->' . $method);
    }
}
