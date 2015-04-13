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
     * 是否已经从DB中读取该对象
     * @var integer
     */
    protected $fetched = 0;
    
    /**
     * @var Base_TopazDb
     */
    protected $db;
    
    public function __construct($id = 0, $db = null) {
        if (!empty($id)) {
            if (is_array($id)) {
                $this->setData($id);
                $this->fetch();
            } else {
                $key = $this->prikey;
                $this->$key = $id;
                $this->fetchFromPrimary();
            }
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
     * 获取对象的属性值
     * @param string $prop
     */
    public function get($prop) {
        return $this->$prop;
    }
    
    /**
     * 对不存在的属性进行默认处理 防止报错
     */
    public function __set($key, $val) {
        
    }
    
    /**
     * 设置对象属性值
     * @param string $prop
     * @param mixed $val
     */
    public function set($prop, $val) {
        $this->$prop = $val;
    }
    
    /**
     * 批量设置对象数据
     * @param array $data
     */
    public function setData($data) {
        if (empty($data)) {
            return false;
        }
        foreach ($data as $field => $val) {
            if (!isset($this->properties[$field])) {
                continue;
            }
            if (isset($this->intProps[$field])) {
                $val = intval($val);
            }
            $this->set($this->properties[$field], $val);
        }
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
    protected function fetchFromPrimary($forcedb = false) {
        $key = $this->prikey;
        if (!isset($this->$key)) {
            return false;
        }
        $this->initDB();
        
        $val = $this->db->escape($this->$key);
        $cols = implode("`, `", $this->fields);
        $sql = "select `$cols` from `{$this->dbname}`.`{$this->table}` where `{$key}` = '$val' limit 1";
        
        
        $data = $this->db->fetchRow($sql);

        if (!empty($data)) {
            $this->setData($data);
            $this->fetched = 1;
        }
    }
    
    /**
     * 通过对象属性作为查询条件从DB中查询对象数据<br>
     * 默认会使用对象的属性作为条件，但是也可以指定$cond参数来设定查询条件
     * @param array $cond
     * @return boolean
     */
    public function fetch($cond = array()) {
        if (empty($cond)) {
            $cond = $this->getCond();
        }
        if (empty($cond)) {
            return false;
        }
        
        $this->initDB();
        $ary = array();
        foreach ($this->fields as $field) {
            if (isset($cond[$field])) {
                $val = $this->db->escape($cond[$field]);
                $ary[] = "`$field` = '$val'";
            }
        }
        if (empty($ary)) {
            return false;
        }
        
        $where = implode(' and ', $ary);
        $cols = implode("`, `", $this->fields);
        $sql = "select `$cols` from `{$this->dbname}`.`{$this->table}` where $where limit 1";
        $data = $this->db->fetchRow($sql);
        
        if (!empty($data)) {
            $this->setData($data);
            $this->fetched = 1;
            return true;
        }
        return false;
    }
    
    /**
     * 通过对象的属性获取查询条件
     * @return array
     */
    private function getCond() {
        $cond = array();
        foreach ($this->properties as $fileld => $prop) {
            if (isset($this->$prop)) {
                $cond[$fileld] = $this->get($prop);
            }
        }
        return $cond;
    }
    
    /**
     * 保存对象到数据库中，会更新状态到对象中，使对象中的数据跟DB是完全对应的
     * @return boolean
     */
    public function save() {
        $data = $this->prepareData();  
        $this->initDB();
        if ($this->get($this->prikey)) {  
        	return $this->update($data);
        } else {
            return $this->insert($data);
        }
        
        return false;
    }
    
    /**
     * 获取对象属性对应的字段值数组 用于进行db写入与更新<br>
     * 会默认设置create_time与update_time字段的值
     * @return array
     */
    private function prepareData() {
        $data = array();
        foreach ($this->properties as $field => $prop) {
            if (isset($this->$prop)) {
                $data[$field] = $this->get($prop);
            }
        }
    
        if ($this->properties['create_time'] && empty($data['create_time'])) {
            $data['create_time'] = time();
        }
        if (isset($this->properties['update_time'])) {
            $data['update_time'] = time();
        }
        return $data;
    }
    
    /**
     * 更新数据到db中，默认尝试写入新数据，如果存在primary key则会更新
     * @param array $data 要更新的数据
     * @return boolean 成功返回true 失败false
     */
    private function update($data) {
        $keys = $vals = $sets = array();
        foreach ($data as $key => $val) {
            $val = $this->db->escape($data[$key]);
            $keys[] = $key;
            $vals[] = $val;
            $sets[] = "`$key` = '$val'";
        }
        $keys = implode('`, `', $keys);
        $vals = implode("', '", $vals);
        $sets = implode(', ', $sets);
        
        $sql = "insert into `{$this->table}`(`$keys`) values('$vals') on duplicate key update $sets";
        try {                  	
            $res = $this->db->query($sql);           
        } catch (Exception $ex) {
            Base_Log::error($ex->getMessage());          
            return false;
        }
        if ($res == true) {     
            $this->fetchFromPrimary(true);
            return true;
        }        
        return false;
    }
    
    /**
     * 插入数据到db中
     * @param array $data 要写入的数据
     * @return boolean 成功返回true 失败false
     */
    private function insert($data) {
        try {
            $res = $this->db->insert($this->table, $data);
        } catch (Exception $ex) {
            Base_Log::error($ex->getMessage());
            return false;
        }
        
        if (!empty($res)) {
            $this->set($this->prikey, $this->db->getLastInsertId());
            $this->fetchFromPrimary(true);
            return true;
        }
        return false;
    }
    
    /**
     * 逻辑删除
     * @return Ambigous <multitype:, boolean>
     */
    public function remove() {
        $key = $this->prikey;
        $this->initDB();
        $val = $this->db->escape($this->$key);
        $sql = "delete from `{$this->dbname}`.`{$this->table}` where `{$key}` = '$val' limit 1";
        
        try {
            $this->db->query($sql);
        } catch (Exception $ex) {
            Base_Log::error($ex->getMessage());
            return false;
        }
        return true;
    }
    
    /**
     * 物理删除
     * @return Ambigous <multitype:, boolean>
     */
    public function erase() {
        $key = $this->prikey;
        $this->initDB();
        $val = $this->db->escape($this->$key);
        $sql = "delete from `{$this->dbname}`.`{$this->table}` where `{$key}` = '$val' limit 1";
        
        try {
            $this->db->query($sql);
        } catch (Exception $ex) {
            Base_Log::error($ex->getMessage());
            return false;
        }
        return true;
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
    
    /**
     * 对象是否已经从DB中装载
     * @return boolean
     */
    public function isLoaded() {
        return $this->fetched ? true : false;
    }
    
}
