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

namespace think\db;

use PDOStatement;
use think\db\exception\DbException as Exception;

/**
 * PDO数据查询类.
 */
class Query extends BaseQuery
{
    use concern\JoinAndViewQuery;
    use concern\ParamsBind;
    use concern\TableFieldInfo;

    /**
     * 表达式方式指定Field排序.
     *
     * @param string $field 排序字段
     * @param array  $bind  参数绑定
     *
     * @return $this
     */
    public function orderRaw(string $field, array $bind = [])
    {
        $this->options['order'][] = new Raw($field, $bind);

        return $this;
    }

    /**
     * 表达式方式指定查询字段.
     *
     * @param string $field 字段名
     *
     * @return $this
     */
    public function fieldRaw(string $field)
    {
        $this->options['field'][] = new Raw($field);

        return $this;
    }

    /**
     * 指定Field排序 orderField('id',[1,2,3],'desc').
     *
     * @param string $field  排序字段
     * @param array  $values 排序值
     * @param string $order  排序 desc/asc
     *
     * @return $this
     */
    public function orderField(string $field, array $values, string $order = '')
    {
        if (!empty($values)) {
            $values['sort'] = $order;

            $this->options['order'][$field] = $values;
        }

        return $this;
    }

    /**
     * 随机排序.
     *
     * @return $this
     */
    public function orderRand()
    {
        $this->options['order'][] = '[rand]';

        return $this;
    }

    /**
     * 使用表达式设置数据.
     *
     * @param string $field 字段名
     * @param string $value 字段值
     *
     * @return $this
     */
    public function exp(string $field, string $value)
    {
        $this->options['data'][$field] = new Raw($value);

        return $this;
    }

    /**
     * 表达式方式指定当前操作的数据表.
     *
     * @param mixed $table 表名
     *
     * @return $this
     */
    public function tableRaw(string $table)
    {
        $this->options['table'] = new Raw($table);

        return $this;
    }

    /**
     * 获取执行的SQL语句而不进行实际的查询.
     *
     * @param bool $fetch 是否返回sql
     *
     * @return $this|Fetch
     */
    public function fetchSql(bool $fetch = true)
    {
        $this->options['fetch_sql'] = $fetch;

        if ($fetch) {
            return new Fetch($this);
        }

        return $this;
    }

    /**
     * 批处理执行SQL语句
     * 批处理的指令都认为是execute操作.
     *
     * @param array $sql SQL批处理指令
     *
     * @return bool
     */
    public function batchQuery(array $sql = []): bool
    {
        return $this->connection->batchQuery($this, $sql);
    }

    /**
     * USING支持 用于多表删除.
     *
     * @param mixed $using USING
     *
     * @return $this
     */
    public function using($using)
    {
        $this->options['using'] = $using;

        return $this;
    }

    /**
     * 存储过程调用.
     *
     * @param bool $procedure 是否为存储过程查询
     *
     * @return $this
     */
    public function procedure(bool $procedure = true)
    {
        $this->options['procedure'] = $procedure;

        return $this;
    }

    /**
     * 指定group查询.
     *
     * @param string|array $group GROUP
     *
     * @return $this
     */
    public function group($group)
    {
        $this->options['group'] = $group;

        return $this;
    }

    /**
     * 指定having查询.
     *
     * @param string $having having
     *
     * @return $this
     */
    public function having(string $having)
    {
        $this->options['having'] = $having;

        return $this;
    }

    /**
     * 指定distinct查询.
     *
     * @param bool $distinct 是否唯一
     *
     * @return $this
     */
    public function distinct(bool $distinct = true)
    {
        $this->options['distinct'] = $distinct;

        return $this;
    }

    /**
     * 指定强制索引.
     *
     * @param string $force 索引名称
     *
     * @return $this
     */
    public function force(string $force)
    {
        $this->options['force'] = $force;

        return $this;
    }

    /**
     * 查询注释.
     *
     * @param string $comment 注释
     *
     * @return $this
     */
    public function comment(string $comment)
    {
        $this->options['comment'] = $comment;

        return $this;
    }

    /**
     * 设置是否REPLACE.
     *
     * @param bool $replace 是否使用REPLACE写入数据
     *
     * @return $this
     */
    public function replace(bool $replace = true)
    {
        $this->options['replace'] = $replace;

        return $this;
    }

    /**
     * 设置当前查询所在的分区.
     *
     * @param string|array $partition 分区名称
     *
     * @return $this
     */
    public function partition($partition)
    {
        $this->options['partition'] = $partition;

        return $this;
    }

    /**
     * 设置DUPLICATE.
     *
     * @param array|string|Raw $duplicate DUPLICATE信息
     *
     * @return $this
     */
    public function duplicate($duplicate)
    {
        $this->options['duplicate'] = $duplicate;

        return $this;
    }

    /**
     * 设置查询的额外参数.
     *
     * @param string $extra 额外信息
     *
     * @return $this
     */
    public function extra(string $extra)
    {
        $this->options['extra'] = $extra;

        return $this;
    }

    /**
     * 创建子查询SQL.
     *
     * @param bool $sub 是否添加括号
     *
     * @throws Exception
     *
     * @return string
     */
    public function buildSql(bool $sub = true): string
    {
        return $sub ? '( ' . $this->fetchSql()->select() . ' )' : $this->fetchSql()->select();
    }

    /**
     * 获取当前数据表的主键.
     *
     * @return string|array
     */
    public function getPk()
    {
        if (empty($this->pk)) {
            $this->pk = $this->connection->getPk($this->getTable());
        }

        return $this->pk;
    }

    /**
     * 指定数据表自增主键.
     *
     * @param string $autoinc 自增键
     *
     * @return $this
     */
    public function autoinc(string $autoinc)
    {
        $this->autoinc = $autoinc;

        return $this;
    }

    /**
     * 获取当前数据表的自增主键.
     *
     * @return string|null
     */
    public function getAutoInc()
    {
        $tableName = $this->getTable();

        if (empty($this->autoinc) && $tableName) {
            $this->autoinc = $this->connection->getAutoInc($tableName);
        }

        return $this->autoinc;
    }

    /**
     * 字段值增长
     *
     * @param string $field 字段名
     * @param float  $step  增长值
     *
     * @return $this
     */
    public function inc(string $field, float $step = 1)
    {
        $this->options['data'][$field] = ['INC', $step];

        return $this;
    }

    /**
     * 字段值减少.
     *
     * @param string $field 字段名
     * @param float  $step  增长值
     *
     * @return $this
     */
    public function dec(string $field, float $step = 1)
    {
        $this->options['data'][$field] = ['DEC', $step];

        return $this;
    }

    /**
     * 字段值增长（支持延迟写入）
     *
     * @param string    $field 字段名
     * @param int       $step  步进值
     * @param int       $lazyTime 延迟时间（秒）
     *
     * @return int|false
     */
    public function setInc(string $field, int $step = 1, $lazyTime = 0)
    {
        if (empty($this->options['where']) && $this->model) {
            $this->where($this->model->getWhere());
        }

        if (empty($this->options['where'])) {
            // 如果没有任何更新条件则不执行
            throw new Exception('miss update condition');
        }

        if ($lazyTime > 0) {
            $guid = $this->getLazyFieldCacheKey($field);
            $step = $this->lazyWrite('inc', $guid, $step, $lazyTime);
            if (false === $step) {
                return true;
            }
        }

        return $this->inc($field, $step)->update();
    }

    /**
     * 字段值减少（支持延迟写入）
     *
     * @param string    $field 字段名
     * @param int       $step  步进值
     * @param int       $lazyTime 延迟时间（秒）
     *
     * @return int|false
     */
    public function setDec(string $field, int $step = 1, int $lazyTime = 0)
    {
        if (empty($this->options['where']) && $this->model) {
            $this->where($this->model->getWhere());
        }

        if (empty($this->options['where'])) {
            // 如果没有任何更新条件则不执行
            throw new Exception('miss update condition');
        }

        if ($lazyTime > 0) {
            $guid = $this->getLazyFieldCacheKey($field);
            $step = $this->lazyWrite('dec', $guid, $step, $lazyTime);
            if (false === $step) {
                return true;
            }
            return $this->inc($field, $step)->update();
        }

        return $this->dec($field, $step)->update();
    }

    /**
     * 延时更新检查 返回false表示需要延时
     * 否则返回实际写入的数值
     * @access protected
     * @param  string  $type     自增或者自减
     * @param  string  $guid     写入标识
     * @param  int     $step     写入步进值
     * @param  int     $lazyTime 延时时间(s)
     * @return false|integer
     */
    protected function lazyWrite(string $type, string $guid, int $step, int $lazyTime)
    {
        $cache = $this->getCache();
        if (!$cache->has($guid . '_time')) {
            // 计时开始
            $cache->set($guid . '_time', time());
            $cache->$type($guid, $step);
        } elseif (time() > $cache->get($guid . '_time') + $lazyTime) {
            // 删除缓存
            $value = $cache->$type($guid, $step);
            $cache->delete($guid);
            $cache->delete($guid . '_time');
            return 0 === $value ? false : $value;
        } else {
            // 更新缓存
            $cache->$type($guid, $step);
        }

        return false;
    }

    /**
     * 获取延迟写入字段值.
     *
     * @param string $field 字段名称
     * @param mixed  $id    主键值
     *
     * @return int
     */
    protected function getLazyFieldValue(string $field, $id = null): int
    {
        return (int) $this->getCache()->get($this->getLazyFieldCacheKey($field, $id));
    }

    /**
     * 获取延迟写入字段的缓存Key
     *
     * @param string  $field 字段名
     * @param mixed   $id    主键值
     *
     * @return string
     */
    protected function getLazyFieldCacheKey(string $field, $id = null): string
    {
        return 'lazy_' . $this->getTable() . '_' . $field . '_' . ($id ?: $this->getKey());
    }

    /**
     * 获取当前的查询标识.
     *
     * @param mixed $data 要序列化的数据
     *
     * @return string
     */
    public function getQueryGuid($data = null): string
    {
        return md5($this->getConfig('database') . serialize(var_export($data ?: $this->options, true)) . serialize($this->getBind(false)));
    }

    /**
     * 执行查询但只返回PDOStatement对象
     *
     * @return PDOStatement
     */
    public function getPdo(): PDOStatement
    {
        return $this->connection->pdo($this);
    }

    /**
     * 使用游标查找记录.
     *
     * @param mixed $data 数据
     *
     * @return \Generator
     */
    public function cursor($data = null)
    {
        if (!is_null($data)) {
            // 主键条件分析
            $this->parsePkWhere($data);
        }

        $this->options['data'] = $data;

        $connection = clone $this->connection;

        return $connection->cursor($this);
    }

    /**
     * 分批数据返回处理.
     *
     * @param int          $count    每次处理的数据数量
     * @param callable     $callback 处理回调方法
     * @param string|array $column   分批处理的字段名
     * @param string       $order    字段排序
     *
     * @throws Exception
     *
     * @return bool
     */
    public function chunk(int $count, callable $callback, string|array $column = null, string $order = 'asc'): bool
    {
        $options = $this->getOptions();
        $column = $column ?: $this->getPk();

        if (isset($options['order'])) {
            unset($options['order']);
        }

        $bind = $this->bind;

        if (is_array($column)) {
            $times = 1;
            $query = $this->options($options)->page($times, $count);
        } else {
            $query = $this->options($options)->limit($count);

            if (str_contains($column, '.')) {
                [$alias, $key] = explode('.', $column);
            } else {
                $key = $column;
            }
        }

        $resultSet = $query->order($column, $order)->select();

        while (count($resultSet) > 0) {
            if (false === call_user_func($callback, $resultSet)) {
                return false;
            }

            if (isset($times)) {
                $times++;
                $query = $this->options($options)->page($times, $count);
            } else {
                $end = $resultSet->pop();
                $lastId = is_array($end) ? $end[$key] : $end->getData($key);

                $query = $this->options($options)
                    ->limit($count)
                    ->where($column, 'asc' == strtolower($order) ? '>' : '<', $lastId);
            }

            $resultSet = $query->bind($bind)->order($column, $order)->select();
        }

        return true;
    }
}
