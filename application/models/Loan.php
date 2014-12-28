<?php
/**
 * 借款的数据层
 */
class LoanModel extends BaseModel {

    public function __construct() {   
        parent::__construct();
    }

    /**
     * 更新总投资额
     * @param integer $loan_id
     * @param number $amount
     * @return boolean
     */
    public function updateInvestAmount($loan_id, $amount) {
        $sql = "update `loan` set invest_amount = invest_amount + $amount, invest_cnt = invest_cnt + 1
                where id = '$loan_id' and invest_amount + $amount <= amount limit 1";
        try {
            $res = $this->db->execute($sql);
            if ($res < 1) {
                return false;
            }
        } catch (Exception $ex) {
            Base_Log::error($ex->getMessage());
            return false;
        }
        return true;
    }
    
    /**
     * 开始事务
     */
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    /**
     * 提交事务
     */
    public function commit() {
        return $this->db->commit();
    }
    
    /**
     * 回滚
     */
    public function rollback() {
        return $this->db->rollBack();
    }
}
