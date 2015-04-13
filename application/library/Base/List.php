<?php
/**
 * 数据列表基类
 * 用于对数据表中的数据进行分页读取，可以通过设置filter来进行数据筛选
 * @author jiangsongfang
 *
 */
class Base_List {
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
     * 排序方式
     * @var string
     */
    public $order = null;
    
    /**
     * 当前页
     * @var integer
     */
    public $page = 1;

    /**
     * 每页条数
     * @var integer
     */
    public $pagesize = 10;
    
    /**
     * 翻页的offset
     * @var integer
     */
    public $offset = 0;

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array();
    
    /**
     * 当前的数据
     * @var array
     */
    protected $data = array();

    /**
     * 数据总数
     * @var integer
     */
    protected $total = 0;
    
    
    /**
     * 翻页总数
     * @var integer
     */
    protected $pageall = 0;
    
    /**
     * 是否已经从db获取过数据
     * @var integer
     */
    private $fetched = 0;
    
    /**
     * 数据过滤器
     * @var array
     */
    private $filters = array();
    
    /**
     * 数据过滤条件 用于sql中的where条件
     * @var string
     */
    private $filterStr = '1';
    
    /**
     * @var Base_TopazDb
     */
    protected $db;
    
    
    public function __construct($db = null) {
        if (!empty($db)) {
            $this->db = $db;
        }
    }
    
    /**
     * 初始化DB
     */
    protected function initDB() {
        if (!isset($this->db)) {
            $this->db = Base_Db::getInstance($this->dbname);
        }
    }
    
    /**
     * 获取where条件
     * @return string
     */
    private function getWhere() {
        return $this->filterStr;
    }
    
    /**
     * 创建where条件
     * @return string
     */
    private function buildWhere($filters) {
        if (empty($filters)) {
            return '1';
        }
        $this->initDB();
        $ary = array();
        foreach ($filters as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $filter) {
                    $ary[] = $filter;
                }
            } else {
                $val = $this->db->escape($val);
                $ary[] = "`$key` = '$val'";
            }
        }
        return implode(' and ', $ary);
    }
    
    
    /**
     * 设置过滤条件
     * @param array $filters
     * @return boolean
     */
    public function setFilter($filters) {
        if (!is_array($filters)) {
            return false;
        }
        $this->filters = $filters;
        $where = $this->buildWhere($filters);
        $this->setFilterString($where);
        $this->fetched = 0;
    }
    
    /**
     * 附加更多的过滤条件
     * @param array $filters
     * @return boolean
     */
    public function appendFilter($filters) {
        if (!is_array($filters)) {
            return false;
        }
        $this->filters = array_merge($this->filters, $filters);
        $where = $this->buildWhere($this->filters);
        $this->appendFilterString($where);
        $this->fetched = 0;
    }
    
    /**
     * 设置过滤where条件
     * @param string $filter
     */
    public function setFilterString($filter) {
        $this->filterStr = $filter;
        $this->fetched = 0;
    }
    
    /**
     * 附加更多的where条件
     * @param string $filter
     */
    public function appendFilterString($filter) {
        $this->filterStr .= ' and ' . $filter;
        $this->fetched = 0;
    }
    
    /**
     * 设置要查询的字段，默认会查询数据表所有的字段<br>
     * 通过设置fields来查询指定的字段，减少数据量
     * 例如：array('id', 'name')
     * @param array $fields
     * @return boolean
     */
    public function setFields($fields) {
        $this->fields = $fields;
        return true;
    }
    
    /**
     * 从数据库中获取数据 未来会增加缓存，通过forcedb参数强制直接从db中读数据
     * @param string $forcedb
     */
    protected function fetch($forcedb = false) {
        if ($this->fetched) {
            return ;
        }
        $cols = implode("`, `", $this->fields);
        $where = $this->getWhere();
        $order = !empty($this->order) ? $this->order : $this->prikey . ' desc';
        $sql = "select `$cols` from `{$this->dbname}`.`{$this->table}` where $where order by $order";
        if ($this->pagesize > 0 && $this->pagesize != PHP_INT_MAX) {
            $offset = $this->offset;
            $pagesize = $this->pagesize;
            $sql .= " limit $offset, $pagesize";
        }
        $this->initDB();
        $this->data = $this->db->fetchAll($sql);
        $this->dealIntField();
        $this->countAll();
        
        $this->fetched = 1;
    }
    
    /**
     * 处理int类型的字段，使属性转换为int类型，便于进行数据交互
     */
    private function dealIntField() {
        $cnt = count($this->data);
        for ($i = 0; $i < $cnt; $i++) {
            foreach ($this->fields as $key) {
                if (isset($this->intProps[$key])) {
                    $this->data[$i][$key] = intval($this->data[$i][$key]);
                }
            }
        }
    }
    
    /**
     * 统计列表中的所有行数
     * @return integer
     */
    public function countAll() {
        $where = $this->getWhere();
        $sql = "select count(*) as total from `{$this->dbname}`.`{$this->table}` where $where";
        $this->total = $this->db->fetchOne($sql);
        $this->pageall = ceil($this->total / $this->pagesize);
        return $this->total;
    }
   
    /**
     * 统计列表中的所有不重复记录行数
     * @return integer
     */
    public function distinCount($col) {
    	$this->initDB();
    	$sql = "select count(distinct $col) as total from `{$this->dbname}`.`{$this->table}`";
    	$distinTotal = $this->db->fetchOne($sql);
    	return $distinTotal;
    }
    
    /**
     * 计算所有行某字段的总和
     * @return integer | array
     * 如果field是string，则直接返回sum后的结果<br>
     * 如果field是array，则返回的是该array为key的数组，例如：<pre>
     * array(
     *      $field1 => $sum1,
     *      $field2 => $sum2,
     * );
     */
    public function sumField($field) {
        $where = $this->getWhere();
        $this->initDB();
        if (is_array($field)) {
            $sumary = array();
            foreach ($field as $k) {
                $sumary[] = "sum(`$k`) as $k";
            }
            $field = implode(',', $sumary);
            
            $sql = "select $field from `{$this->dbname}`.`{$this->table}` where $where";
            $total = $this->db->fetchRow($sql);
        } else {
            $sql = "select sum(`$field`) as total from `{$this->dbname}`.`{$this->table}` where $where";
            $total = $this->db->fetchOne($sql);
        }
        return $total;
    }
    
    /**
     * 在数据列表中连接类型字段名,会在列表行中增加$type_field的字段名，值是对应的类型
     * @param Base_Type $type
     * @param string $field
     * @param string $type_field
     */
    public function joinType($type, $field = '', $type_field = '') {
        $this->fetch();
        if (empty($field)) {
            $field = $type->getDefaultKey();
        }
        if (empty($type_field)) {
            $type_field = $type->getDefaultField();
        }
        foreach ($this->data as $key => $val) {
            $this->data[$key][$type_field] = $type->getTypeName($val[$field]);
        }
    }
    
    /**
     * 将数据转换成数组
     * @return array
     */
    public function toArray() {
        $this->fetch();
        $pagesize = $this->pagesize;
        if ($this->pagesize == PHP_INT_MAX) {
            $pagesize = 0;
        }
       
        $data = $this->getData();
        $list = array(
            'page' => $this->page,
            'pagesize' => $pagesize,
            'pageall' => $this->pageall,
            'total' => $this->total,
            'list' => $data,
        );
        return $list;
    }
    
    /**
     * 设置当前页码
     * @param integer $page
     */
    public function setPage($page) {
        if ($page > 0) {
            $this->page = $page;
            $this->setOffset(($page - 1) * $this->pagesize);
            $this->fetched = 0;
        }
    }
    
    /**
     * 设置当前offset 优先级高于page与pagesize
     * @param integer $page
     */
    public function setOffset($offset) {
        $this->offset = $offset;
        $this->fetched = 0;
    }
    
    /**
     * 设置每页数据条数
     * @param integer $pagesize
     */
    public function setPagesize($pagesize) {
        $this->pagesize = $pagesize;
        $this->setOffset(($this->page - 1) * $this->pagesize);
        $this->fetched = 0;
    }
    
    /**
     * 设置排序方式
     * @param integer $order
     */
    public function setOrder($order) {
        $this->order = $order;
        $this->fetched = 0;
    }
    
    /**
     * 获取数据-数组格式
     * @return array
     */
    public function getData() {
        $this->fetch();
        return $this->data;
    }
    
    /**
     * 获取数据对象格式
     * @param string $object
     * @return array
     */
    public function getObjects($object = '') {
        $data = $this->getData();
        if (empty($object) || empty($data)) {
            return $data;
        }
        
        $objects = array();
        foreach ($data as $row) {
            $obj = new $object();
            $obj->setData($row);
            $objects[] = $obj;
        }
        return $objects;
    }
    
    /**
     * 获取数据总数
     * @return number
     */
    public function getTotal() {
        $this->fetch();
        return $this->total;
    }
    
    /**
     * 获取总页数
     * @return number
     */
    public function getPageTotal() {
        $this->fetch();
        return $this->pageall;
    }
    
}