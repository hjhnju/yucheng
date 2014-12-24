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
     * 获取我的投资列表 借款维度
     * @param unknown $uid
     * @param integer $status -1全部 1审核中 2投标中 3放款审核 4打款中 5回款中 6已完成 9失败
     * @param number $page
     * @param number $pagesize
     * @return array <pre>(
     *      'page' => $page,
     *      'pagesize' => $pagesize,
     *      'pageall' => $pageall,
     *      'total' => $total,
     *      'list' => $data,
     *  );</pre>
     */
    public static function getUserInvests($uid, $status = -1, $page = 1, $pagesize = 10) {
        $logic = new Invest_Logic_Invest();
        $data = $logic->getUserInvests($uid, $status, $page, $pagesize);
        return $data;
    }
    
    /**
     * 获取我的投资收益
     * @param integer $uid
     * @return array <pre>(
            'all_invest' => $all,
            'all_income' => $incomes,
            'wait_capital' => $capital,
            'wait_interest' => $interest,
        );
     */
    public static function getUserEarnings($uid) {
        $logic = new Invest_Logic_Invest();
        $data = $logic->getUserEarnings($uid);
        return $data;
    }
    
    /**
     * 获取用户一定时期的投资收益情况 按月区分
     * @param unknown $uid
     * @param number $start
     * @param number $end
     * @return array <pre>(
            '2014-09' => 100,
            '2014-10' => 200,
        );
    */
    public static function getEarningsMonthly($uid, $start = 0, $end = 0) {
        if ($start == 0) {
            $start = strtotime('-6 months');
        }
        if ($end == 0) {
            $end = time();
        }
        
        $logic = new Invest_Logic_Invest();
        $data = $logic->getEarningsMonthly($uid, $start, $end);
        return $data;
    }
    
    /**
     * 获取我的投资列表 投资维度
     * @param integer $uid
     * @param integer $loan_id
     * @return array
     */
    public static function getUserLoanInvest($uid, $loan_id) {
        $logic = new Invest_Logic_Invest();
        $data = $logic->getUserLoanInvest($uid, $loan_id);
        
        return $data;
    }
    
    /**
     * 获取单笔投资的收款计划
     * @param unknown $invest_id
     * @return array
     */
    public static function getRefunds($invest_id) {
        $refunds = new Invest_List_Refund();
        $filters = array(
            'invest_id' => $invest_id,
        );
        $refunds->setFilter($filters);
        $refunds->setPagesize(PHP_INT_MAX);
        $list = $refunds->toArray();
        
        $data = $list['list'];
        return $data;
    }
    
    /**
     * 获取用户的投资总额
     * @param unknown $uid
     * @return number
     */
    public static function getUserAmount($uid) {
        $logic = new Invest_Logic_Invest();
        $amount = $logic->getUserInvestAmount($uid);
        return $amount;
    }
    
    /**
     * 获取用户的正在回收的资产总额
     * @param number $uid
     * @return number
     */
    public static function getUserRefundsAmount($uid) {
        $logic = new Invest_Logic_Invest();
        $amount = $logic->getUserRefundsAmount($uid);
        return $amount;
    }
    
    /**
     * 进行投标
     * @param unknown $uid
     * @param unknown $loan_id
     * @param unknown $amount
     * @param unknown $interest
     */
    public static function invest($uid, $loan_id, $amount, $interest) {
        //@todo 自动投标使用
    }
    
    /**
     * 获取用户的帐户余额
     * @param unknown $uid
     * @return number
     */
    public static function getAccountAmout($uid) {
        return 100;
    }
    
    /**
     * 获取用户在某借款上最大的可投标金额
     * @param unknown $uid
     * @param unknown $loan_id
     */
    public static function getUserCanInvest($uid, $loan_id) {
        $logic = new Invest_Logic_Invest();
        $amount = self::getAccountAmout($uid);
        return $logic->getUserCanInvest($uid, $loan_id, $amount);
    }
}