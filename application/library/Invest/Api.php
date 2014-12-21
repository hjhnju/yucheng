<?php
/**
 * 投资模块API接口
 * @author jiangsongfang
 *
 */
class Invest_Api {
    /**
     * 获取借款的投资列表
     * @param integer $loan_id
     * @return array
     */
    public static function getLoanInvests($loan_id, $page = 1, $pagesize = PHP_INT_MAX) {
        $logic = new Invest_Logic_Invest();
        $data = $logic->getLoanInvests($loan_id, $page, $pagesize);
        
        return $data;
    }
    
    /**
     * 获取我的投资列表
     * @param integer $uid
     * @param integer $loan_id
     * @return array
     */
    public static function getUserLoanInvest($uid, $loan_id) {
        $logic = new Invest_Logic_Invest();
        $data = $logic->getUserLoanInvest($uid, $loan_id);
        
        return $data;
    }
}