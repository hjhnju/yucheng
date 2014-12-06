<?php
/**
 * 数据模型
 */
class TestModel extends BaseModel {

    public function __construct() {   
        parent::__construct();
    }

    public function query() {
        $sql = "SELECT * FROM `mysql`.`help_category` ORDER BY help_category_id DESC  LIMIT 0,2;";
        $arrRet = $this->db->fetchAll($sql);

        Base_Log::notice($arrRet);
        return $arrRet;
    }
}
