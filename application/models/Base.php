<?php
/**
 * 数据层Model基类
 */
class BaseModel {

    protected $db;
    protected $logger;

    public function __construct() {
        $this->db = Base_Db::getInstance('xjd');
       //$this->logger = Base_Log::getInstance();
    }
}
