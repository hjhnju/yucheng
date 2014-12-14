<?php
/**
 * 借款模块API接口
 * @author jiangsongfang
 *
 */
class Loan_Api {
    /**
     * 生成借款标的还款计划
     * @param integer $lid
     */
    public static function buildRefunds($loan_id) {
        
    }
    
    /**
     * 获取某借款的还款计划列表
     * @param integer $loan_id
     * @return array
     */
    public static function getLoanRefunds($loan_id) {
        $refunds = new Loan_List_Refund();
        $filters = array('loan_id' => $loan_id);
        $refunds->setFilter($filters);
        $refunds->setPagesize(PHP_INT_MAX);
        return $refunds->toArray();
    }
    
    /**
     * 添加借款日志记录
     * @param integer $loan_id
     * @param string $content
     * @return boolean
     */
    public static function AddLog($loan_id, $content) {
        $log = new Loan_Object_Log();
        $log->loanId = $loan_id;
        $log->ip = Base_Util_Ip::getClientIp();
        $log->content = $content;
        $log->userId = 0;
        return $log->save();
    }
    
    public static function getUserLoans($uid, $page = 1, $pagesize = 10) {
        $list = new Loan_List_Loan();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list->setFilter(array('user_id' => $uid));
        return $list->toArray();
    }
    
    /**
     * 获取借款列表
     * @param number $page
     * @param number $pagesize
     * @param array $filters
     * @return array
     */
    public static function getLoans($page = 1, $pagesize = 10, $filters = array()) {
        $list = new Loan_List_Loan();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list->setFilter($filters);
        return $list->toArray();
    }
}