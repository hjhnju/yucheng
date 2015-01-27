<?php
/**
 * 后台借款逻辑
 * @author jiangsongfang
 *
 */
class Admin_Logic_Loan {
    /**
     * @var Base_TopazDb
     */
    private $db;
    
    public function __construct() {
        $this->db = Base_Db::getInstance('xjd');
    }
    
    /**
     * 发布借款
     * @param integer $loanId 借款ID
     * @return boolean
     */
    public function publish($loanId, $days = 7) {
        $this->db->beginTransaction();
        $loan = new Loan_Object_Loan($loanId);
        if (empty($loan->amount)) {
            Base_Log::warn('loan id empty');
            return false;
        }
        
        $loan->status = Loan_Type_LoanStatus::LENDING;
        $loan->startTime = time();
        $loan->deadline = time() + $days * 24 * 3600;
        $duration = new Loan_Type_Duration();
        if (!$loan->save()) {
            Base_Log::warn('loan save failed');
            return false;
        }
        
        // 调用财务API进行借款录入
        $area    = new Area_Object_Area($loan->area);
        $retDate = $duration->getTimestamp($loan->duration, $loan->deadline);
        $retAmt  = Loan_Api::getLoanRefundAmount($loanId);
        $res     = Finance_Api::addBidInfo($loanId, $loan->userId, $loan->amount, 
            $loan->interest/100, $loan->refundType, $loan->startTime, $loan->deadline, 
            $retAmt, $retDate, $area->huifuCityid);
        if ($res === true) {
            return $this->db->commit();
        } else {
            Base_Log::warn('add bid info failed ' . $res);
        }
        
        return false;
    }
    
    public function disable($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        if (empty($loan->title)) {
            Base_Log::warn('loan not exists');
            return false;
        }
    }
}
