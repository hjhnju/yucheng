<?php
/**
 * 数据层Model基类
 */
class BaseModel {

    /**
     * @var Base_TopazDb
     */
    protected $db;

    public function __construct() {
        $this->db       = Base_Db::getInstance('xjd');
    }
}
