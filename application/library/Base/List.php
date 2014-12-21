<?php
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
    private $filterStr = '';
    
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
            $val = $this->db->escape($val);
            $ary[] = "`$key` = '$val'";
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
        if ($this->pagesize > 0 && $this->pagesize != PHP_INT_MAX && $this->page > 1) {
            $offset = ($this->page - 1) * $this->pagesize;
            $pagesize = $this->pagesize;
            $sql .= " limit $offset, $pagesize";
        }

        $this->initDB();
        $this->data = $this->db->fetchAll($sql);
        $cnt = count($this->data);
        for ($i = 0; $i < $cnt; $i++) {
            foreach ($this->intProps as $k => $val) {
                $this->data[$i][$k] = intval($this->data[$i][$k]);
            }
        }
        
        $sql = "select count(*) as total from `{$this->dbname}`.`{$this->table}` where $where";
        $this->total = $this->db->fetchOne($sql);
        $this->pageall = ceil($this->total / $this->pagesize);
        
        $this->fetched = 1;
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
        $list = array(
            'page' => $this->page,
            'pagesize' => $pagesize,
            'pageall' => $this->pageall,
            'total' => $this->total,
            'list' => $this->data,
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
            $this->fetched = 0;
        }
    }
    
    /**
     * 设置每页数据条数
     * @param integer $pagesize
     */
    public function setPagesize($pagesize) {
        $this->pagesize = $pagesize;
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
     * @return array
     */
    public function getObjects() {
        return $this->getData();
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