<?php
/**
 * 对象积累，用于实现对象的数据操作
 * @author jiangsongfang
 *
 */
class Base_Object {
    /**
     * 数据库名
     * @var string
     */
    protected $dbname = 'xjd';
    
    /**
     * 数据表名
     * @var string
     */
    protected $table;

    /**
     * 主键
     * @var string
     */
    protected $prikey;
    
    /**
     * 字段列表
     * @var array
     */
    protected $fields = array();

    /**
     * 字段与属性的映射关系
     * @var array
     */
    public $properties = array();

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array();
    
    /**
     * @var Base_TopazDb
     */
    protected $db;
    
    public function __construct($id = 0, $db = null) {
        if (!empty($id)) {
            $key = $this->prikey;
            $this->$key = $id;
            $this->fetch();
        }
        if (!empty($db)) {
            $this->db = $db;
        }
    }
    
    /**
     * 将数组映射到对象中，返回一个对象
     * @param array $data
     * @return Base_Object
     */
    public static function initObject($cls, $data) {
        $obj = new $cls;
        
        foreach ($obj->properties as $field => $prop) {
            if (isset($data[$field])) {
                $obj->$prop = $data[$field];
            }
        }
        return $obj;
    }
    
    /**
     * 初始化数据库
     */
    protected function initDB() {
        if (!isset($this->db)) {
            $this->db = Base_Db::getInstance($this->dbname);
        }
    }
    
    /**
     * 从数据库中获取对象
     * @param string $forcedb 强制从DB中操作
     * @return boolean
     */
    protected function fetch($forcedb = false) {
        $key = $this->prikey;
        if (!isset($this->$key)) {
            return false;
        }
        $this->initDB();
        
        $val = $this->db->escape($this->$key);
        $cols = implode("`, `", $this->fields);
        $sql = "select `$cols` from `{$this->dbname}`.`{$this->table}` where `{$key}` = '$val' limit 1";

        $data = $this->db->fetchRow($sql);
        if (empty($data)) {
            return false;
        }
        foreach ($this->properties as $field => $prop) {
            if (isset($this->intProps[$field])) {
                $data[$field] = intval($data[$field]);
            }
            $this->$prop = $data[$field];
        }
    }
    
    /**
     * 保存对象到数据库中，会更新状态到对象中，使对象中的数据跟DB是完全对应的
     * @return boolean
     */
    public function save() {
        $data = array();
        foreach ($this->properties as $field => $prop) {
            if (isset($this->$prop)) {
                $data[$field] = $this->$prop;
            }
        }
        $this->initDB();
        if (!empty($this->{$this->prikey})) {
            $key = $this->prikey;
            $val = $this->db->escape($this->$key);
            $where = "`$key` = '$val'";
            $this->db->update($this->table, $data, $where);
        } else {
            $res = $this->db->insert($this->table, $data);
            if (!empty($res)) {
                $this->{$this->prikey} = $this->db->getLastInsertId();
            } else {
                return false;
            }
        }
        $this->fetch(true);
        return true;
    }
    
    /**
     * 逻辑删除
     * @return Ambigous <multitype:, boolean>
     */
    public function remove() {
        $key = $this->prikey;
        $val = $this->$key;
        $sql = "delete from `{$this->dbname}`.`{$this->table}` where `{$key}` = '$val' limit 1";
        $this->initDB();
        return $this->db->query($sql);
    }
    
    /**
     * 物理删除
     * @return Ambigous <multitype:, boolean>
     */
    public function erase() {
        $key = $this->prikey;
        $val = $this->$key;
        $sql = "delete from `{$this->dbname}`.`{$this->table}` where `{$key}` = '$val' limit 1";
        $this->initDB();
        return $this->db->query($sql);
    }
    
    /**
     * 将对象转换成数组格式
     * @return array
     */
    public function toArray() {
        $data = array();
        foreach ($this->properties as $key => $prop) {
            $data[$key] = $this->$prop;
        }
        return $data;
    }
    
    /**
     * 对象是否已经存储到DB中[预留]
     * @return boolean
     */
    public function isStored() {
        return true;
    }
    
}