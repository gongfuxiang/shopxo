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

namespace think\model\relation;

use Closure;
use think\db\BaseQuery as Query;
use think\db\exception\DbException as Exception;
use think\helper\Str;
use think\Model;
use think\model\Relation;

/**
 * 一对一关联基础类.
 */
abstract class OneToOne extends Relation
{
    /**
     * JOIN类型.
     *
     * @var string
     */
    protected $joinType = 'INNER';

    /**
     * 绑定的关联属性.
     *
     * @var array
     */
    protected $bindAttr = [];

    /**
     * 关联名.
     *
     * @var string
     */
    protected $relation;

    /**
     * 设置join类型.
     *
     * @param string $type JOIN类型
     *
     * @return $this
     */
    public function joinType(string $type)
    {
        $this->joinType = $type;

        return $this;
    }

    /**
     * 预载入关联查询（JOIN方式）.
     *
     * @param Query   $query    查询对象
     * @param string  $relation 关联名
     * @param mixed   $field    关联字段
     * @param string  $joinType JOIN方式
     * @param Closure $closure  闭包条件
     * @param bool    $first
     *
     * @return void
     */
    public function eagerly(Query $query, string $relation, $field = true, string $joinType = '', Closure $closure = null, bool $first = false): void
    {
        $name = Str::snake(class_basename($this->parent));

        if ($first) {
            $table = $query->getTable();
            $query->table([$table => $name]);

            if ($query->getOptions('field')) {
                $masterField = $query->getOptions('field');
                $query->removeOption('field');
            } else {
                $masterField = true;
            }

            $query->tableField($masterField, $table, $name);
        }

        // 预载入封装
        $joinTable  = $this->query->getTable();
        $joinAlias  = $relation;
        $joinType   = $joinType ?: $this->joinType;

        $query->via($joinAlias);

        if ($this instanceof BelongsTo) {
            $foreignKeyExp = $this->foreignKey;

            if (!str_contains($foreignKeyExp, '.')) {
                $foreignKeyExp = $name . '.' . $this->foreignKey;
            }

            $joinOn = $foreignKeyExp . '=' . $joinAlias . '.' . $this->localKey;
        } else {
            $foreignKeyExp = $this->foreignKey;

            if (!str_contains($foreignKeyExp, '.')) {
                $foreignKeyExp = $joinAlias . '.' . $this->foreignKey;
            }

            $joinOn = $name . '.' . $this->localKey . '=' . $foreignKeyExp;
        }

        if ($closure) {
            // 执行闭包查询
            $closure($query);

            // 使用withField指定获取关联的字段
            $withField = $this->query->getOptions('field');
            if ($withField) {
                $field = $withField;
            }
        }

        $query->join([$joinTable => $joinAlias], $joinOn, $joinType)
            ->tableField($field, $joinTable, $joinAlias, $relation . '__');
    }

    /**
     *  预载入关联查询（数据集）.
     *
     * @param array   $resultSet
     * @param string  $relation
     * @param array   $subRelation
     * @param Closure $closure
     *
     * @return mixed
     */
    abstract protected function eagerlySet(array &$resultSet, string $relation, array $subRelation = [], Closure $closure = null);

    /**
     * 预载入关联查询（数据）.
     *
     * @param Model   $result
     * @param string  $relation
     * @param array   $subRelation
     * @param Closure $closure
     *
     * @return mixed
     */
    abstract protected function eagerlyOne(Model $result, string $relation, array $subRelation = [], Closure $closure = null);

    /**
     * 预载入关联查询（数据集）.
     *
     * @param array   $resultSet   数据集
     * @param string  $relation    当前关联名
     * @param array   $subRelation 子关联名
     * @param Closure $closure     闭包
     * @param array   $cache       关联缓存
     * @param bool    $join        是否为JOIN方式
     *
     * @return void
     */
    public function eagerlyResultSet(array &$resultSet, string $relation, array $subRelation = [], Closure $closure = null, array $cache = [], bool $join = false): void
    {
        if ($join) {
            // 模型JOIN关联组装
            foreach ($resultSet as $result) {
                $this->match($this->model, $relation, $result);
            }
        } else {
            // IN查询
            $this->eagerlySet($resultSet, $relation, $subRelation, $closure, $cache);
        }
    }

    /**
     * 预载入关联查询（数据）.
     *
     * @param Model   $result      数据对象
     * @param string  $relation    当前关联名
     * @param array   $subRelation 子关联名
     * @param Closure $closure     闭包
     * @param array   $cache       关联缓存
     * @param bool    $join        是否为JOIN方式
     *
     * @return void
     */
    public function eagerlyResult(Model $result, string $relation, array $subRelation = [], Closure $closure = null, array $cache = [], bool $join = false): void
    {
        if ($join) {
            // 模型JOIN关联组装
            $this->match($this->model, $relation, $result);
        } else {
            // IN查询
            $this->eagerlyOne($result, $relation, $subRelation, $closure, $cache);
        }
    }

    /**
     * 保存（新增）当前关联数据对象
     *
     * @param array|Model $data    数据 可以使用数组 关联模型对象
     * @param bool  $replace 是否自动识别更新和写入
     *
     * @return Model|false
     */
    public function save(array|Model $data, bool $replace = true)
    {
        $model = $this->make();

        return $model->replace($replace)->save($data) ? $model : false;
    }

    /**
     * 创建关联对象实例.
     *
     * @param array|Model $data
     *
     * @return Model
     */
    public function make(array|Model $data = []): Model
    {
        if ($data instanceof Model) {
            $data = $data->getData();
        }

        // 保存关联表数据
        $data[$this->foreignKey] = $this->parent->{$this->localKey};

        return new $this->model($data);
    }

    /**
     * 绑定关联表的属性到父模型属性.
     *
     * @param array $attr 要绑定的属性列表
     *
     * @return $this
     */
    public function bind(array $attr)
    {
        $this->bindAttr = $attr;

        return $this;
    }

    /**
     * 获取绑定属性.
     *
     * @return array
     */
    public function getBindAttr(): array
    {
        return $this->bindAttr;
    }

    /**
     * 一对一 关联模型预查询拼装.
     *
     * @param string $model    模型名称
     * @param string $relation 关联名
     * @param Model  $result   模型对象实例
     *
     * @return void
     */
    protected function match(string $model, string $relation, Model $result): void
    {
        // 重新组装模型数据
        foreach ($result->getData() as $key => $val) {
            if (str_contains($key, '__')) {
                [$name, $attr] = explode('__', $key, 2);
                if ($name == $relation) {
                    $list[$name][$attr] = $val;
                    unset($result->$key);
                }
            }
        }

        if (isset($list[$relation])) {
            $array = array_unique($list[$relation]);

            if (count($array) == 1 && null === current($array)) {
                $relationModel = null;
            } else {
                $relationModel = new $model($list[$relation]);
                $relationModel->setParent(clone $result);
                $relationModel->exists(true);
            }

            if (!empty($this->bindAttr)) {
                $this->bindAttr($result, $relationModel);
            }
        } else {
            $relationModel = null;
        }

        $result->setRelation($relation, $relationModel);
    }

    /**
     * 绑定关联属性到父模型.
     *
     * @param Model $result 父模型对象
     * @param Model $model  关联模型对象
     *
     * @throws Exception
     *
     * @return void
     */
    protected function bindAttr(Model $result, Model $model = null): void
    {
        foreach ($this->bindAttr as $key => $attr) {
            $key    = is_numeric($key) ? $attr : $key;
            $value  = $result->getOrigin($key);

            if (!is_null($value)) {
                throw new Exception('bind attr has exists:' . $key);
            }

            $result->setAttr($key, $model?->$attr);
        }
    }

    /**
     * 一对一 关联模型预查询（IN方式）.
     *
     * @param array   $where       关联预查询条件
     * @param string  $key         关联键名
     * @param array   $subRelation 子关联
     * @param Closure $closure
     * @param array   $cache       关联缓存
     *
     * @return array
     */
    protected function eagerlyWhere(array $where, string $key, array $subRelation = [], Closure $closure = null, array $cache = [])
    {
        // 预载入关联查询 支持嵌套预载入
        if ($closure) {
            $this->baseQuery = true;
            $closure($this->query);
        }

        $list = $this->query
            ->where($where)
            ->with($subRelation)
            ->cache($cache[0] ?? false, $cache[1] ?? null, $cache[2] ?? null)
            ->select();

        // 组装模型数据
        $data = [];

        foreach ($list as $set) {
            if (!isset($data[$set->$key])) {
                $data[$set->$key] = $set;
            }
        }

        return $data;
    }
}
