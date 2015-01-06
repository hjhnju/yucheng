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
     * @param integer $invest_id
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
     * @param integer $uid
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
     * @param integer $uid
     * @param integer $loan_id
     * @param integer $amount
     * @param integer $interest
     */
    public static function invest($uid, $loan_id, $amount, $interest) {
        //@todo 自动投标使用
    }
    
    /**
     * 获取用户的帐户余额
     * @param integer $uid
     * @return number
     */
    public static function getAccountAmout($uid) {
        return 100;
    }
    
    /**
     * 获取用户在某借款上最大的可投标金额
     * @param integer $uid
     * @param integer $loan_id
     */
    public static function getUserCanInvest($uid, $loan_id) {
        $logic = new Invest_Logic_Invest();
        $amount = self::getAccountAmout($uid);
        return $logic->getUserCanInvest($uid, $loan_id, $amount);
    }
    
    /**
     * 获取投资的信息
     * @param integer $invest_id
     * @param array
     */
    public static function getInvest($invest_id) {
        $logic = new Invest_Logic_Invest();
        return $logic->getInvest($invest_id);
    }
    
    /**
     * 获取投资列表，通过filter过滤列表数据
     * @param integer $page
     * @param integer $pagesize
     * @param array $filter
     * @return array
     * array(
     *      'page' => 1,
     *      'pagesize' => 10,
     *      'pageall' => 100,
     *      'list' => array(),
     * )
     */
    public static function getInvestList($page = 1, $pagesize = 10, $filter = array()) {
        $list = Loan_Api::getLoans($page, $pagesize, $filter);
         
        $status = new Invest_Type_InvestStatus();
        foreach ($list['list'] as $key => $val) {
            $list['list'][$key]['status_name'] = $status->getTypeName($val['status']);
        }
        return $list;
    }
    
    /**
     * 创建收款计划
     * @param integer $invest_id
     * @param boolean
     */
    public static function buildRefunds($invest_id) {
        $invest = self::getInvest($invest_id);
        $loan = Loan_Api::getLoanInfo($invest['loan_id']);
        
        if ($loan['duration'] < 30) {
            $date = new DateTime('tomorrow');
            $date->modify('+' . $loan['duration'] . 'days');
            $time = $date->getTimestamp() - 1;
            
            $income = self::getInterestByDay($invest['amount'], $invest['interest'], $loan['duration']);
            return self::addRefund($invest, 1, $invest['amount'], $income, $time);
        }
        
        //超过30天 按月还款
        $periods = ceil($loan['duration'] / 30);
        if ($loan['refund_type'] == Invest_Type_RefundType::MONTH_INTEREST) {
            $date = new DateTime('tomorrow');
            $start = $date->getTimestamp() - 1;
            for ($period = 1; $period <= $periods; $period++) {
                $date->modify('+1month');
                $promise = $date->getTimestamp() - 1;
                $days = ($promise - $start) / 3600 / 24;
                $income = self::getInterestByDay($invest['amount'], $loan['interest'], $days);
                if ($period == $periods) {
                    $capital = $invest['amount'];
                } else {
                    $capital = 0;
                }
                $res = self::addRefund($invest, $period, $capital, $income, $promise);
                
                if (!$res) {
                    $msg = array(
                        'msg'    => 'add refund error',
                        'invest' => json_encode($invest),
                        'period' => $period,
                        'capital'=> 0,
                        'income' => $income,
                        'promise'=> $promise,
                    );
                    Base_Log::error($msg);
                    return false;
                }
                
                $start = $promise;
            }
        } elseif ($loan['refund_type'] == Invest_Type_RefundType::AVERAGE) {
            $date = new DateTime('tomorrow');
            $start = $date->getTimestamp() - 1;
            $b = $loan['interest'] / 12;
            $a = $invest['amount'];
            $n = $periods;
            for ($period = 1; $period <= $periods; $period++) {
                /*  收益计算公式
                    P = A * b% * (1 + b%)^n / ((1 + b%)^n - 1)
                    P: 每月还款额
                    A: 借款本金
                    b: 月利率
                    n: 还款总期数
                    
                    每月月供额=〔贷款本金×月利率×(1＋月利率)＾还款月数〕÷〔(1＋月利率)＾还款月数-1〕
                    每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
                    每月应还本金=贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
                    总利息=还款月数×每月月供额-贷款本金
                */
                $date->modify('+1month');
                $promise = $date->getTimestamp() - 1;
                $days = ($promise - $start) / 3600 / 24;
                $income = $a * $b * (pow(1 + $b, $periods) - pow(1 + $b, $period - 1)) / (pow(1 + $b, $periods) - 1);
                $capital = $a * $b * pow(1 + $b, $period - 1) / (pow(1 + $b, $periods) - 1);
                $res = self::addRefund($invest, $period, $capital, $income, $promise);
                
                if (!$res) {
                    $msg = array(
                        'msg'    => 'add refund error',
                        'invest' => json_encode($invest),
                        'period' => $period,
                        'capital'=> $capital,
                        'income' => $income,
                        'promise'=> $promise,
                    );
                    Base_Log::error($msg);
                    return false;
                }
                
                $start = $promise;
            }
        }
        
        return true;
    }
    
    /**
     * 新增一个收款计划
     * @param unknown $invest
     * @param unknown $period
     * @param unknown $capital
     * @param unknown $income
     * @param unknown $time
     * @return boolean
     */
    private static function addRefund($invest, $period, $capital, $income, $time) {
        $refund = new Invest_Object_Refund();
        $refund->amount = $capital + $income;
        $refund->interest = $income;
        $refund->capital = $capital;
        $refund->investId = $invest['id'];
        $refund->lateCharge = 0;
        $refund->loanId = $invest['loan_id'];
        $refund->period = $period;
        $refund->userId = $invest['user_id'];
        $refund->promiseTime = $time;
        return $refund->save();
    }
    
    /**
     * 按月付息 到期还本 按天计算的利息收入
     * @param number $amount
     * @param number $interest
     * @param number $days
     * @return number
     */
    private static function getInterestByDay($amount, $interest, $days) {
        $money = $amount * $interest * $days / 365 / 100;
        return $money;
    }
}