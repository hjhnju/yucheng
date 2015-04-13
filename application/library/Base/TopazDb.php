<?php
/**
 * From Topaz_Db
 */
class Base_TopazDb {

    /**
     * DB配置节点
     * @var array
     */
    protected $_arrConfig = null;

    /**
     * 是否自动释放
     * @var bool
     */
    protected $_bolAutoFree = false;

    /**
     * 是否使用持续链接
     * @var bool
     */
    protected $_bolPconnect = false;

    /**
     * 事务次数
     * @var integer
     */
    protected $_intTransTimes = 0;

    /**
     * 查询语句
     * @var string
     */
    protected $_strQueryStr = '';

    /**
     * 最后自增ID
     * @var integer
     */
    protected $_intLastInsID = null;

    /**
     * 影响行数
     * @var integer
     */
    protected $_intNumRows = 0;

    /**
     * 当前的链接对象
     * @var mysqli
     */
    protected $_objLinkID = null;

    /**
     * 当前查询的对象
     * @var object
     */
    protected $_objQueryID = null;

    /**
     * 是否使用主库
     * @var bool
     */
    protected $_bolIsMaster = false;

    /**
     * 当前是否需要连接到主库
     * @var bool
     */
    protected $_bolMaster = false;

    /**
     * 标记是否成功
     * @var bool
     */
    protected $_bolConnected = false;

    /**
     * 构造函数
     * 获取配置信息传入，构造DB对象
     * $arrConfig = array(
     *   'master'=>array(
     *   'username'=>'',
     *   'password'=>'',
     *   'host'=>'',
     *   'dbname'=>'',
     *   'port'=>'',
     *   'charset'=>'',
     *   'profiler'=>false,
     * ),
     * 'slave'=>array(
     *   array(
     *      'username'=>'',
     *      'password'=>'',
     *      'host'=>'',
     *      'dbname'=>'',
     *      'port'=>'',
     *      'charset'=>'',
     *      'profiler'=>false,
     *   )
     *   array(
     *      'username'=>'',
     *      'password'=>'',
     *      'host'=>'',
     *      'dbname'=>'',
     *      'port'=>'',
     *      'charset'=>'',
     *      'profiler'=>false,
     *   )
     * ),
     * )
     */
    /*
    public function __construct ($arrConfig = '')
    {
        if (! extension_loaded('mysqli')) {
            throw new Base_Exception_Runtime('MYSQLI EXTENSTION NOT LOADED!');
        }
        //加载配置
        if (! empty($arrConfig)) {
            $this->_arrConfig = $arrConfig;
        } else {
            throw new Base_Exception_Runtime('DB CONFIG ITEMS IS EMPTY!');
        }
    }*/

    /**
     * 
     * @param string $arrConfig array('hosts'=>, 'port', 'dbname', 'username', 'password', 'charset')
     */
    public function __construct ($arrConfig = '')
    {
        if (! extension_loaded('mysqli')) {
            throw new Base_Exception_Runtime('MYSQLI EXTENSTION NOT LOADED!');
        }

        //加载配置
        if (! empty($arrConfig)) {
            $arrConfig['hosts'] = explode(',', $arrConfig['hosts']);
            $this->_arrConfig = $arrConfig;
        } else {
            throw new Base_Exception_Runtime('DB CONFIG ITEMS IS EMPTY!');
        }
    }

    private function _rand_db_config () {
        //DBPROXY NOT SUPPORT master yet!
        $key = array_rand($this->_arrConfig['hosts']);
        $this->_arrConfig['host'] = $this->_arrConfig['hosts'][$key];
    }

    /**
     * 连接数据库获取实例
     * 根据传入的linkNum来实现db的切换
     * 连接到DB SERVER的配置
     */
    public function connect () {
        if ($this->_objLinkID && $this->_bolIsMaster == false && $this->_bolMaster == true) {
            //已连接到slave但需要连接到master，则断开已有连接
            $this->close();
        }
        
        if (! isset($this->_objLinkID)) {
            //创建连接
            $connect_success = false;
            for ($i = 0; $i < 3; $i ++) {
                $this->_rand_db_config();
                $this->_objLinkID = new mysqli($this->_arrConfig['host'], $this->_arrConfig['username'], 
                    $this->_arrConfig['password'], $this->_arrConfig['dbname'], $this->_arrConfig['port']);
                if (mysqli_connect_errno()) {
                    continue;
                } else {
                    $connect_success = true;
                    break;
                }
            }
            if (! $connect_success) {
                throw new Base_Exception_Runtime(mysqli_connect_error());
            }
            if (! $this->_objLinkID->set_charset('utf8')) {
                throw new Base_Exception_Runtime("Failed to set character set utf-8!");
            }
            $this->_bolConnected = true;
            $this->_bolIsMaster = $this->_bolMaster;
        }
        return $this->_objLinkID;
    }

    /**
     * 查询SQL语句返回结果
     * @param string $strSql sql语句
     * @return boolean 是否执行成功
     */
    public function query ($strSql) {
        //连接数据库
        $this->connect();
        $this->_strQueryStr = $strSql;
        //释放上次的查询
        $this->free();
        $this->escape($strSql);
        $this->_objQueryID = mysqli_query($this->_objLinkID, $this->_strQueryStr);
        if (! $this->_objQueryID) {
            //查询失败则抛出异常
            throw new Base_Exception_Runtime(
            "QUERY ERROR:" . $this->_objLinkID->error . " SQL:" . $strSql);
            return false;
        } else {
            // SELECT，SHOW，EXPLAIN 或 DESCRIBE 语句返回一个资源标识符，其他返回True|False
            if(!is_bool($this->_objQueryID)){
                $this->_intNumRows = mysqli_num_rows($this->_objQueryID);
            }
            return true;
        }
    }

    /**
     * 获取query后的查询结果
     *
     */
    protected function fetch () {
        return mysqli_fetch_assoc($this->_objQueryID);
    }

    /**
     * 查询返回单条记录
     *
     * $db->fetchRow($strSql, $arrBind);
     * @param string $strSql SQL语句
     * @param array $arrBind
     * @return array/empty array 返回当条数据或者是空数组
     */
    public function fetchRow ($strSql) {
        if ($this->query($strSql)) {
            return $this->fetch();
        }
        return array();
    }

    /**
     * 查询返回一列的全部记录，例如SELECT ucid FROM acct WHERE appid = 3;
     *
     * $db->fetchColumn($strSql, $arrBind);
     * @param string $strSql SQL语句
     * @param array $arrBind
     * @return array/empty array 返回当条数据或者是空数组
     */
    public function fetchColumn ($strSql) {
        $arrColumn = array();
        if ($this->query($strSql)) {
            while ($arrRow = $this->fetch()) {
                $arrRow = array_values($arrRow);
                $arrColumn[] = $arrRow[0];
            }
        }
        return $arrColumn;
    }

    /**
     *
     * 查询返回第一个记录的第一个字段的值，例如SELECT ucname FROM acct WHERE ucid = 12;
     *
     * $db->fetchOne($strSql, $arrBind);
     * @param string $strSql
     * @param array $arrBind
     */
    public function fetchOne ($strSql) {
        $arrRow = $this->fetchRow($strSql);
        if (! is_array($arrRow)) {
            return false;
        }
        $arrRow = array_values($arrRow);
        return $arrRow[0];
    }

    /**
     *
     * 查询全部行，例如SELECT * FROM acct WHERE appid = 3;
     *
     * $db->fetchAll($strSql, $arrBind);
     * @param string $strSql
     * @param array $arrBind
     */
    public function fetchAll ($strSql) {
        $arrAll = array();
        if ($this->query($strSql)) {
            //return mysqli_fetch_all($this->_objQueryID, MYSQLI_ASSOC); //only works if you build mysqli with mysqlnd support
            while($res = mysqli_fetch_array($this->_objQueryID, MYSQLI_ASSOC)){
                $arrAll[]=$res;
            }
        }
        return $arrAll;
    }

    /**
     * 执行SQL语句
     * @param string $str sql语句
     * @return int  返回execute影响的行数
     */
    public function execute ($strSql) {
        //连接到主库
        $this->_bolMaster = true;
        $this->connect();
        $this->_strQueryStr = $strSql;
        $this->free();
        $startInterval = microtime(true);
        $this->escape($strSql);
        $result = mysqli_query($this->_objLinkID, $this->_strQueryStr);
        if (false === $result) {
            throw new Base_Exception_Runtime("QUERY ERROR:" . $this->_objLinkID->error . " SQL:" . $strSql);
        } else {
            $this->_intNumRows = mysqli_affected_rows($this->_objLinkID);
            $this->_intLastInsID = mysqli_insert_id($this->_objLinkID);
            $endInterval = microtime(true);
            return $this->_intNumRows;
        }
    }

    public function insert ($strTable, $arrBind) {
        $fields = "(";
        $values = "(";
        foreach ($arrBind as $key => $value) {
            $fields .= "`" . $key . "`";
            $fields .= ",";
            $values .= ("'" . $this->escape($value) . "'");
            $values .= ",";
        }
        $fields = rtrim($fields, ",");
        $values = rtrim($values, ",");
        $fields .= ")";
        $values .= ")";
        $sql = "INSERT INTO {$strTable} {$fields} VALUES {$values}";
        return $this->execute($sql);
    }

    /**
     * 批量插入
     * @param  [type] $strTable [description]
     * @param  [type] $arrBinds array of $arrBind
     * @return $affRow || false
     */
    public function insertBatch($strTable, $arrBinds) {
        $arrValues = array();
        $strFields = null;
        foreach ($arrBinds as $arrBind) {
            $fields = "(";
            $values = "(";
            foreach ($arrBind as $key => $value) {
                $fields .= "`" . $key . "`";
                $fields .= ",";
                $values .= ("'" . $this->escape($value) . "'");
                $values .= ",";
            }
            $fields = rtrim($fields, ",");
            $values = rtrim($values, ",");
            $fields .= ")";
            $values .= ")";
            if (is_null($strFields)) {
                $strFields = $fields;
            }elseif ($strFields != $fields) {
                return false;
            }
            $arrValues[] = $values;
        }
        if(is_null($strFields)){
            return false;
        }
        $strValues = implode(',', $arrValues);
        $sql = "REPLACE INTO {$strTable} {$strFields} VALUES {$strValues}";
        return $this->execute($sql);
    }

    public function replace ($strTable, $arrBind) {
        $fields = "(";
        $values = "(";
        foreach ($arrBind as $key => $value) {
            $fields .= "`" . $key . "`";
            $fields .= ",";
            $values .= ("'" . $this->escape($value) . "'");
            $values .= ",";
        }
        $fields = rtrim($fields, ",");
        $values = rtrim($values, ",");
        $fields .= ")";
        $values .= ")";
        $sql = "REPLACE INTO {$strTable} {$fields} VALUES {$values}";
        return $this->execute($sql);
    }

    public function update ($strTable, $arrBind, $strWhere) {
        $sql = "UPDATE {$strTable} SET ";
        $setString = "";
        foreach ($arrBind as $key => $value) {
            $strSeg = "`" . $key . "`";
            $valSeg = ("'" . $this->escape($value) . "'");
            $strSeg .= (" = " . $valSeg);
            $strSeg .= ",";
            $setString .= $strSeg;
        }
        
        $setString = rtrim($setString, ',');
        $sql .= $setString;
        $sql .= (" WHERE " . $strWhere);
        return $this->execute($sql);
    }

    /**
     * 开始事务
     * @return null
     */
    public function beginTransaction() {
        $this->_bolMaster = true;
        $this->_objLinkID = $this->connect();
        if (! $this->_objLinkID) {
            return false;
        }
        $result = mysqli_query($this->_objLinkID, 'START TRANSACTION');
        return $result;
    }

    /**
     * 提交
     * @return bool true/false
     */
    public function commit() {
        $result = mysqli_query($this->_objLinkID, 'COMMIT');
        return $result;
    }

    /**
     * 回滚
     * @return bool 结果
     */
    public function rollBack() {
        $result = mysqli_query($this->_objLinkID, 'ROLLBACK');
        return $result;
    }

    /**
     * 释放最近一次查询结果
     */
    public function free() {

        if(isset($this->_objQueryID) && is_bool($this->_objQueryID)){
            return;
        }
        if (! empty($this->_objQueryID)) {
            mysqli_free_result($this->_objQueryID);
            $this->_objQueryID = null;
        }
    }

    /**
     * 回收资源
     */
    public function close() {
        if (! empty($this->_objQueryID)) {
            mysqli_free_result($this->_objQueryID);
        }
        if (! empty($this->_objLinkID)) {
            mysqli_close($this->_objLinkID);
        }
        unset($this->_objLinkID);
        unset($this->_objQueryID);
        $this->_bolConnected = false;
    }

    /**
     * destruct
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * 获取最后一条查询的SQL
     * @return string
     */
    public function getLastSql() {
        return $this->_strQueryStr;
    }

    public function getLastInsertId() {
        return $this->_intLastInsID;
    }

    public function escape($string) {
        $this->connect();
        return $this->_objLinkID->real_escape_string($string);
    }

    public function getNumRows() {
        return $this->_intNumRows;
    }

    public function getConnection($bolMaster) {
        if(!isset($bolMaster)||$bolMaster === null) {
            $bolMaster = $this->_bolMaster;
        }
        $this->_bolMaster = $bolMaster;
        $this->connect();
        return $this->_objLinkID;
    }

    public function getQuery() {
        return $this->_objQueryID;
    }
    
    public function setIsMaster($bolMaster) {
        $this->_bolMaster = $bolMaster;
    }

}
