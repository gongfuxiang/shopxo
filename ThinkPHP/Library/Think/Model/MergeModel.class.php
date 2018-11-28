<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Model;
use Think\Model;
/**
 * ThinkPHP 聚合模型扩展 
 */
class MergeModel extends Model {

    protected $modelList    =   array();    //  包含的模型列表 第一个必须是主表模型
    protected $masterModel  =   '';         //  主模型
    protected $joinType     =   'INNER';    //  聚合模型的查询JOIN类型
    protected $fk           =   '';         //  外键名 默认为主表名_id
    protected $mapFields    =   array();    //  需要处理的模型映射字段，避免混淆 array( id => 'user.id'  )

    /**
     * 架构函数
     * 取得DB类的实例对象 字段检查
     * @access public
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($name='',$tablePrefix='',$connection=''){
        parent::__construct($name,$tablePrefix,$connection);
        // 聚合模型的字段信息
        if(empty($this->fields) && !empty($this->modelList)){
            $fields     =   array();
            foreach($this->modelList as $model){
                // 获取模型的字段信息
                $result     =   $this->db->getFields(M($model)->getTableName());
                $_fields    =   array_keys($result);
               // $this->mapFields  =   array_intersect($fields,$_fields);
                $fields     =   array_merge($fields,$_fields);
            }
            $this->fields   =   $fields;
        }

        // 设置第一个模型为主表模型
        if(empty($this->masterModel) && !empty($this->modelList)){
            $this->masterModel  =   $this->modelList[0];
        }
        // 主表的主键名
        $this->pk =   M($this->masterModel)->getPk();

        // 设置默认外键名 仅支持单一外键
        if(empty($this->fk)){
            $this->fk  =   strtolower($this->masterModel).'_id';
        }

    }

    /**
     * 得到完整的数据表名
     * @access public
     * @return string
     */
    public function getTableName() {
        if(empty($this->trueTableName)) {
            $tableName  =   array();
            $models     =   $this->modelList;
            foreach($models as $model){
                $tableName[]    =   M($model)->getTableName().' '.$model;
            }
            $this->trueTableName    =   implode(',',$tableName);
        }
        return $this->trueTableName;
    }

    /**
     * 自动检测数据表信息
     * @access protected
     * @return void
     */
    protected function _checkTableInfo() {}

    /**
     * 新增聚合数据
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @param boolean $replace 是否replace
     * @return mixed
     */
    public function add($data='',$options=array(),$replace=false){
        if(empty($data)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $data           =   $this->data;
                // 重置数据
                $this->data     = array();
            }else{
                $this->error    = L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // 启动事务
        $this->startTrans();
        // 写入主表数据
        $result     =   M($this->masterModel)->strict(false)->add($data);
        if($result){
            // 写入外键数据
            $data[$this->fk]    =   $result;
            $models     =   $this->modelList;
            array_shift($models);
            // 写入附表数据
            foreach($models as $model){
                $res =   M($model)->strict(false)->add($data);
                if(!$res){
                    $this->rollback();
                    return false;
                }
            }
            // 提交事务
            $this->commit();
        }else{
            $this->rollback();
            return false;
        }
        return $result;
    }

   /**
     * 对保存到数据库的数据进行处理
     * @access protected
     * @param mixed $data 要操作的数据
     * @return boolean
     */
     protected function _facade($data) {

        // 检查数据字段合法性
        if(!empty($this->fields)) {
            if(!empty($this->options['field'])) {
                $fields =   $this->options['field'];
                unset($this->options['field']);
                if(is_string($fields)) {
                    $fields =   explode(',',$fields);
                }    
            }else{
                $fields =   $this->fields;
            }        
            foreach ($data as $key=>$val){
                if(!in_array($key,$fields,true)){
                    unset($data[$key]);
                }elseif(array_key_exists($key,$this->mapFields)){
                    // 需要处理映射字段
                    $data[$this->mapFields[$key]] = $val;
                    unset($data[$key]);
                }
            }
        }
       
        // 安全过滤
        if(!empty($this->options['filter'])) {
            $data = array_map($this->options['filter'],$data);
            unset($this->options['filter']);
        }
        $this->_before_write($data);
        return $data;
     }

    /**
     * 保存聚合模型数据
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @return boolean
     */
    public function save($data='',$options=array()){
        // 根据主表的主键更新
        if(empty($data)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $data           =   $this->data;
                // 重置数据
                $this->data     =   array();
            }else{
                $this->error    =   L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        if(empty($data)){
            // 没有数据则不执行
            $this->error    =   L('_DATA_TYPE_INVALID_');
            return false;
        }            
        // 如果存在主键数据 则自动作为更新条件
        $pk         =   $this->pk;
        if(isset($data[$pk])) {
            $where[$pk]         =   $data[$pk];
            $options['where']   =   $where;
            unset($data[$pk]);
        }
        $options['join']    =   '';
        $options    =   $this->_parseOptions($options);
        // 更新操作不使用JOIN 
        $options['table']   =   $this->getTableName();

        if(is_array($options['where']) && isset($options['where'][$pk])){
            $pkValue    =   $options['where'][$pk];
        }
        if(false === $this->_before_update($data,$options)) {
            return false;
        }        
        $result     =   $this->db->update($data,$options);
        if(false !== $result) {
            if(isset($pkValue)) $data[$pk]   =  $pkValue;
            $this->_after_update($data,$options);
        }
        return $result;
    }

    /**
     * 删除聚合模型数据
     * @access public
     * @param mixed $options 表达式
     * @return mixed
     */
    public function delete($options=array()){
        $pk   =  $this->pk;
        if(empty($options) && empty($this->options['where'])) {
            // 如果删除条件为空 则删除当前数据对象所对应的记录
            if(!empty($this->data) && isset($this->data[$pk]))
                return $this->delete($this->data[$pk]);
            else
                return false;
        }
        
        if(is_numeric($options)  || is_string($options)) {
            // 根据主键删除记录
            if(strpos($options,',')) {
                $where[$pk]     =  array('IN', $options);
            }else{
                $where[$pk]     =  $options;
            }
            $options            =  array();
            $options['where']   =  $where;
        }
        // 分析表达式
        $options['join']    =   '';
        $options =  $this->_parseOptions($options);
        if(empty($options['where'])){
            // 如果条件为空 不进行删除操作 除非设置 1=1
            return false;
        }        
        if(is_array($options['where']) && isset($options['where'][$pk])){
            $pkValue            =  $options['where'][$pk];
        }
        
        $options['table']   =   implode(',',$this->modelList);
        $options['using']   =   $this->getTableName();
        if(false === $this->_before_delete($options)) {
            return false;
        }        
        $result  =    $this->db->delete($options);
        if(false !== $result) {
            $data = array();
            if(isset($pkValue)) $data[$pk]   =  $pkValue;
            $this->_after_delete($data,$options);
        }
        // 返回删除记录个数
        return $result;
    }

    /**
     * 表达式过滤方法
     * @access protected
     * @param string $options 表达式
     * @return void
     */
    protected function _options_filter(&$options) {
        if(!isset($options['join'])){
            $models     =   $this->modelList;
            array_shift($models);
            foreach($models as $model){
                $options['join'][]    =   $this->joinType.' JOIN '.M($model)->getTableName().' '.$model.' ON '.$this->masterModel.'.'.$this->pk.' = '.$model.'.'.$this->fk;
            }
        }
        $options['table']   =   M($this->masterModel)->getTableName().' '.$this->masterModel;
        $options['field']   =   $this->checkFields(isset($options['field'])?$options['field']:'');
        if(isset($options['group']))
            $options['group']  =  $this->checkGroup($options['group']);
        if(isset($options['where']))
            $options['where']  =  $this->checkCondition($options['where']);
        if(isset($options['order']))
            $options['order']  =  $this->checkOrder($options['order']);
    }

    /**
     * 检查条件中的聚合字段
     * @access protected
     * @param mixed $data 条件表达式
     * @return array
     */
    protected function checkCondition($where) {
        if(is_array($where)) {
            $view   =   array();
            foreach($where as $name=>$value){
                if(array_key_exists($name,$this->mapFields)){
                    // 需要处理映射字段
                    $view[$this->mapFields[$name]] = $value;
                    unset($where[$name]);
                }
            }
            $where    =   array_merge($where,$view);
         }
        return $where;
    }

    /**
     * 检查Order表达式中的聚合字段
     * @access protected
     * @param string $order 字段
     * @return string
     */
    protected function checkOrder($order='') {
         if(is_string($order) && !empty($order)) {
            $orders = explode(',',$order);
            $_order = array();
            foreach ($orders as $order){
                $array  =   explode(' ',trim($order));
                $field  =   $array[0];
                $sort   =   isset($array[1])?$array[1]:'ASC';
                if(array_key_exists($field,$this->mapFields)){
                    // 需要处理映射字段
                    $field  =   $this->mapFields[$field];
                }                
                $_order[] = $field.' '.$sort;
            }
            $order = implode(',',$_order);
         }
        return $order;
    }

    /**
     * 检查Group表达式中的聚合字段
     * @access protected
     * @param string $group 字段
     * @return string
     */
    protected function checkGroup($group='') {
         if(!empty($group)) {
            $groups = explode(',',$group);
            $_group = array();
            foreach ($groups as $field){
                // 解析成聚合字段
                if(array_key_exists($field,$this->mapFields)){
                    // 需要处理映射字段
                    $field  =   $this->mapFields[$field];
                }                 
                $_group[] = $field;
            }
            $group  =   implode(',',$_group);
         }
        return $group;
    }

    /**
     * 检查fields表达式中的聚合字段
     * @access protected
     * @param string $fields 字段
     * @return string
     */
    protected function checkFields($fields='') {
        if(empty($fields) || '*'==$fields ) {
            // 获取全部聚合字段
            $fields =   $this->fields;
        }
        if(!is_array($fields))
            $fields =   explode(',',$fields);

        // 解析成聚合字段
        $array =  array();
        foreach ($fields as $field){
            if(array_key_exists($field,$this->mapFields)){
                // 需要处理映射字段
                $array[]  =   $this->mapFields[$field].' AS '.$field;
            }else{
                $array[]  =     $field;
            }
        }
        $fields = implode(',',$array);
        return $fields;
    }

    /**
     * 获取数据表字段信息
     * @access public
     * @return array
     */
    public function getDbFields(){
        return $this->fields;
    }

}