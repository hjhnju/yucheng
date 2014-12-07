<?php
/**
 * 数据层Model基类
 */
class BaseModel {

    protected $db;

    public function __construct() {
        $this->db       = Base_Db::getInstance('xjd');
    }
}
