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

use BackedEnum;
use Closure;
use InvalidArgumentException;
use Stringable;
use think\db\Raw;
use think\helper\Str;
use think\Model;
use think\model\contract\EnumTransform;
use think\model\contract\FieldTypeTransform;
use think\model\Relation;

/**
 * 模型数据处理.
 */
trait Attribute
{
    /**
     * 数据表主键 复合主键使用数组定义.
     *
     * @var string|array
     */
    protected $pk = 'id';

    /**
     * 数据表主键自增.
     *
     * @var bool|null|string
     */
    protected $autoInc;

    /**
     * 数据表字段信息 留空则自动获取.
     *
     * @var array
     */
    protected $schema = [];

    /**
     * 当前允许写入的字段.
     *
     * @var array
     */
    protected $field = [];

    /**
     * 字段自动类型转换.
     *
     * @var array
     */
    protected $type = [];

    /**
     * 数据表废弃字段.
     *
     * @var array
     */
    protected $disuse = [];

    /**
     * 数据表只读字段.
     *
     * @var array
     */
    protected $readonly = [];

    /**
     * 当前模型数据.
     *
     * @var array
     */
    private $data = [];

    /**
     * 原始数据.
     *
     * @var array
     */
    private $origin = [];

    /**
     * JSON数据表字段.
     *
     * @var array
     */
    protected $json = [];

    /**
     * JSON数据表字段类型.
     *
     * @var array
     */
    protected $jsonType = [];

    /**
     * JSON数据取出是否需要转换为数组.
     *
     * @var bool
     */
    protected $jsonAssoc = false;

    /**
     * Enum数据取出自动转换为name.
     *
     * @var bool|string
     */
    protected $enumReadName = false;

    /**
     * 严格检查Enum数据类型.
     *
     * @var bool
     */
    protected $enumStrict = false;

    /**
     * 是否严格字段大小写.
     *
     * @var bool
     */
    protected $strict = true;

    /**
     * 获取器数据.
     *
     * @var array
     */
    private $get = [];

    /**
     * 动态获取器.
     *
     * @var array
     */
    private $withAttr = [];

    /**
     * 自动写入字段.
     *
     * @var array
     */
    protected $insert = [];

    /**
     * 获取模型对象的主键.
     *
     * @return string|array
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * 判断一个字段名是否为主键字段.
     *
     * @param string $key 名称
     *
     * @return bool
     */
    protected function isPk(string $key): bool
    {
        $pk = $this->getPk();

        if (is_string($pk) && $pk == $key) {
            return true;
        } elseif (is_array($pk) && in_array($key, $pk)) {
            return true;
        }

        return false;
    }

    /**
     * 获取模型对象的主键值
     *
     * @return mixed
     */
    public function getKey()
    {
        $pk = $this->getPk();

        if (is_string($pk) && array_key_exists($pk, $this->data)) {
            return $this->data[$pk];
        }
    }

    /**
     * 设置允许写入的字段.
     *
     * @param array $field 允许写入的字段
     *
     * @return $this
     */
    public function allowField(array $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * 设置只读字段.
     *
     * @param array $field 只读字段
     *
     * @return $this
     */
    public function readonly(array $field)
    {
        $this->readonly = $field;

        return $this;
    }

    /**
     * 获取实际的字段名.
     *
     * @param string $name 字段名
     *
     * @return string
     */
    protected function getRealFieldName(string $name): string
    {
        if ($this->convertNameToCamel || !$this->strict) {
            return Str::snake($name);
        }

        return $name;
    }

    /**
     * 设置数据对象值
     *
     * @param array|object $data  数据
     * @param bool  $set   是否调用修改器
     * @param array $allow 允许的字段名
     *
     * @return $this
     */
    public function data(array | object $data, bool $set = false, array $allow = [])
    {
        if ($data instanceof Model) {
            $data = $data->getData();
        } elseif (is_object($data)) {
            $data = get_object_vars($data);
        }

        // 清空数据
        $this->data = [];

        // 废弃字段
        foreach ($this->disuse as $key) {
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
        }

        if (!empty($allow)) {
            $result = [];
            foreach ($allow as $name) {
                if (isset($data[$name])) {
                    $result[$name] = $data[$name];
                }
            }
            $data = $result;
        }

        $this->appendData($data, $set);

        return $this;
    }

    /**
     * 批量追加数据对象值
     *
     * @param array $data 数据
     * @param bool  $set  是否需要进行数据处理
     *
     * @return $this
     */
    public function appendData(array $data, bool $set = false)
    {
        if ($set) {
            $this->setAttrs($data);
        } else {
            $this->data = array_merge($this->data, $data);
        }

        return $this;
    }

    /**
     * 刷新对象原始数据（为当前数据）.
     *
     * @return $this
     */
    public function refreshOrigin()
    {
        $this->origin = $this->data;

        return $this;
    }

    /**
     * 获取对象原始数据 如果不存在指定字段返回null.
     *
     * @param string $name 字段名 留空获取全部
     *
     * @return mixed
     */
    public function getOrigin(?string $name = null)
    {
        if (is_null($name)) {
            return $this->origin;
        }

        $fieldName = $this->getRealFieldName($name);

        return array_key_exists($fieldName, $this->origin) ? $this->origin[$fieldName] : null;
    }

    /**
     * 获取当前对象数据 如果不存在指定字段返回false.
     *
     * @param string $name 字段名 留空获取全部
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function getData(?string $name = null)
    {
        if (is_null($name)) {
            return $this->data;
        }

        $fieldName = $this->getRealFieldName($name);

        if (array_key_exists($fieldName, $this->data)) {
            return $this->data[$fieldName];
        }

        if (array_key_exists($fieldName, $this->relation)) {
            return $this->relation[$fieldName];
        }

        throw new InvalidArgumentException('property not exists:' . static::class . '->' . $name);
    }

    /**
     * 获取变化的数据 并排除只读数据.
     *
     * @return array
     */
    public function getChangedData(): array
    {
        $data = $this->force ? $this->data : array_udiff_assoc($this->data, $this->origin, function ($a, $b) {
            if ((empty($a) || empty($b)) && $a !== $b) {
                return 1;
            }

            return is_object($a) || $a != $b ? 1 : 0;
        });

        // 只读字段不允许更新
        foreach ($this->readonly as $field) {
            if (array_key_exists($field, $data)) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    /**
     * 直接设置数据对象值
     *
     * @param string $name  属性名
     * @param mixed  $value 值
     *
     * @return void
     */
    public function set(string $name, $value): void
    {
        $name = $this->getRealFieldName($name);

        $this->data[$name] = $value;
        unset($this->get[$name]);
    }

    /**
     * 通过修改器 批量设置数据对象值
     *
     * @param array $data 数据
     *
     * @return void
     */
    public function setAttrs(array $data): void
    {
        // 进行数据处理
        foreach ($data as $key => $value) {
            $this->setAttr($key, $value, $data);
        }
    }

    /**
     * 通过修改器 设置数据对象值
     *
     * @param string $name  属性名
     * @param mixed  $value 属性值
     * @param array  $data  数据
     *
     * @return void
     */
    public function setAttr(string $name, $value, array $data = []): void
    {
        $name = $this->getRealFieldName($name);

        // 检测修改器
        $method = 'set' . Str::studly($name) . 'Attr';

        if (method_exists($this, $method)) {
            $array = $this->data;
            $value = $this->$method($value, array_merge($this->data, $data));

            if (is_null($value) && $array !== $this->data) {
                return;
            }
        } elseif (!in_array($name, $this->json) && isset($this->type[$name])) {
            // 类型转换
            if ($this->enumStrict && is_subclass_of($this->type[$name], BackedEnum::class) && !($value instanceof BackedEnum)) {
                throw new InvalidArgumentException('data type error: ' . $name . ' => ' . $this->type[$name]);
            }
            $value = $this->writeTransform($value, $this->type[$name]);
        } elseif ($this->isRelationAttr($name)) {
            // 关联属性
            $this->relation[$name] = $value;
            $this->with[$name]     = true;
        } elseif ((array_key_exists($name, $this->origin) || empty($this->origin)) && $value instanceof Stringable) {
            // 对象类型
            $value = $value->__toString();
        }

        // 设置数据对象属性
        $this->data[$name] = $value;
        unset($this->get[$name]);
    }

    /**
     * 数据写入 类型转换.
     *
     * @param mixed        $value 值
     * @param string|array $type  要转换的类型
     *
     * @return mixed
     */
    protected function writeTransform($value, string | array $type)
    {
        if (null === $value) {
            return;
        }

        if ($value instanceof Raw) {
            return $value;
        }

        if (is_array($type)) {
            [$type, $param] = $type;
        } elseif (str_contains($type, ':')) {
            [$type, $param] = explode(':', $type, 2);
        }

        $typeTransform = static function (string $type, $value, $model) {
            if (str_contains($type, '\\') && class_exists($type)) {
                if (is_subclass_of($type, FieldTypeTransform::class)) {
                    $value = $type::set($value, $model);
                } elseif ($value instanceof BackedEnum) {
                    $value = $value->value;
                } elseif ($value instanceof Stringable) {
                    $value = $value->__toString();
                }
            }

            return $value;
        };

        return match ($type) {
            'string' => (string) $value,
            'integer' => (int) $value,
            'float' => empty($param) ? (float) $value : (float) number_format($value, (int) $param, '.', ''),
            'boolean' => (bool) $value,
            'timestamp' => !is_numeric($value) ? strtotime($value) : $value,
            'datetime' => $this->formatDateTime('Y-m-d H:i:s.u', $value, true),
            'object' => is_object($value) ? json_encode($value, JSON_FORCE_OBJECT) : $value,
            'array' => json_encode((array) $value, !empty($param) ? (int) $param : JSON_UNESCAPED_UNICODE),
            'json' => json_encode($value, !empty($param) ? (int) $param : JSON_UNESCAPED_UNICODE),
            'serialize' => serialize($value),
            default => $typeTransform($type, $value, $this),
        };
    }

    /**
     * 获取器 获取数据对象的值
     *
     * @param string $name 名称
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function getAttr(string $name)
    {
        try {
            $relation = false;
            $value    = $this->getData($name);
        } catch (InvalidArgumentException $e) {
            $relation = $this->isRelationAttr($name);
            $value    = null;
        }

        return $this->getValue($name, $value, $relation);
    }

    /**
     * 获取经过获取器处理后的数据对象的值
     *
     * @param string      $name     字段名称
     * @param mixed       $value    字段值
     * @param bool|string $relation 是否为关联属性或者关联名
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    protected function getValue(string $name, $value, bool | string $relation = false)
    {
        // 检测属性获取器
        $fieldName = $this->getRealFieldName($name);

        if (array_key_exists($fieldName, $this->get)) {
            return $this->get[$fieldName];
        }

        $method = 'get' . Str::studly($name) . 'Attr';
        if (isset($this->withAttr[$fieldName])) {
            if ($relation) {
                $value = $this->getRelationValue($relation);
            }

            if (in_array($fieldName, $this->json) && is_array($this->withAttr[$fieldName])) {
                $value = $this->getJsonValue($fieldName, $value);
            } else {
                $closure = $this->withAttr[$fieldName];
                if ($closure instanceof \Closure) {
                    $value = $closure($value, $this->data, $this);
                }
            }
        } elseif (method_exists($this, $method)) {
            if ($relation) {
                $value = $this->getRelationValue($relation);
            }

            $value = $this->$method($value, $this->data);
        } elseif (!in_array($fieldName, $this->json) && isset($this->type[$fieldName])) {
            // 类型转换
            $value = $this->readTransform($value, $this->type[$fieldName]);
        } elseif ($this->autoWriteTimestamp && in_array($fieldName, [$this->createTime, $this->updateTime])) {
            $value = $this->getTimestampValue($value);
        } elseif ($relation) {
            $value = $this->getRelationValue($relation);
            // 保存关联对象值
            $this->relation[$name] = $value;
        }

        $this->get[$fieldName] = $value;

        return $value;
    }

    /**
     * 获取JSON字段属性值
     *
     * @param string $name  属性名
     * @param mixed  $value JSON数据
     *
     * @return mixed
     */
    protected function getJsonValue(string $name, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        foreach ($this->withAttr[$name] as $key => $closure) {
            if ($this->jsonAssoc) {
                $value[$key] = $closure($value[$key] ?? '', $value);
            } else {
                $value->$key = $closure($value->$key ?? '', $value);
            }
        }

        return $value;
    }

    /**
     * 获取关联属性值
     *
     * @param string $relation 关联名
     *
     * @return mixed
     */
    protected function getRelationValue(string $relation)
    {
        $modelRelation = $this->$relation();

        return $modelRelation instanceof Relation ? $this->getRelationData($modelRelation) : null;
    }

    /**
     * 数据读取 类型转换.
     *
     * @param mixed        $value 值
     * @param string|array $type  要转换的类型
     *
     * @return mixed
     */
    protected function readTransform($value, string | array $type)
    {
        if (is_null($value)) {
            return;
        }

        if (is_array($type)) {
            [$type, $param] = $type;
        } elseif (str_contains($type, ':')) {
            [$type, $param] = explode(':', $type, 2);
        }

        $call = function ($value) {
            try {
                $value = unserialize($value);
            } catch (\Exception $e) {
                $value = null;
            }
            return $value;
        };

        $typeTransform = static function (string $type, $value, $model) {
            if (str_contains($type, '\\') && class_exists($type)) {
                if (is_subclass_of($type, FieldTypeTransform::class)) {
                    $value = $type::get($value, $model);
                } elseif (is_subclass_of($type, BackedEnum::class)) {
                    $value = $type::from($value);
                    if (is_subclass_of($type, EnumTransform::class)) {
                        $value = $value->value();
                    } elseif ($model->enumReadName) {
                        $method = $model->enumReadName;
                        $value  = is_string($method) ? $value->$method() : $value->name;
                    }
                } else {
                    // 对象类型
                    $value = new $type($value);
                }
            }

            return $value;
        };

        return match ($type) {
            'string' => (string) $value,
            'integer' => (int) $value,
            'float' => empty($param) ? (float) $value : (float) number_format($value, (int) $param, '.', ''),
            'boolean' => (bool) $value,
            'timestamp' => !is_null($value) ? $this->formatDateTime(!empty($param) ? $param : $this->dateFormat, $value, true) : null,
            'datetime' => !is_null($value) ? $this->formatDateTime(!empty($param) ? $param : $this->dateFormat, $value) : null,
            'json' => json_decode($value, true),
            'array' => empty($value) ? [] : json_decode($value, true),
            'object' => empty($value) ? new \stdClass() : json_decode($value),
            'serialize' => $call($value),
            default => $typeTransform($type, $value, $this),
        };
    }

    /**
     * 设置数据字段获取器.
     *
     * @param string|array $name     字段名
     * @param Closure     $callback 闭包获取器
     *
     * @return $this
     */
    public function withFieldAttr(string | array $name, ?Closure $callback = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->withFieldAttr($key, $val);
            }
        } else {
            $name = $this->getRealFieldName($name);
            $this->append([$name], true);

            if (str_contains($name, '.')) {
                [$name, $key] = explode('.', $name);

                $this->withAttr[$name][$key] = $callback;
            } else {
                $this->withAttr[$name] = $callback;
            }
        }

        return $this;
    }

    /**
     * 设置枚举类型自动读取数据方式
     * true 表示使用name值返回
     * 字符串 表示使用枚举类的方法返回
     *
     * @return $this
     */
    public function withEnumRead(bool | string $method = true)
    {
        $this->enumReadName = $method;

        return $this;
    }
}
