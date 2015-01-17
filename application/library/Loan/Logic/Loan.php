<?php
class Loan_Logic_Loan {
    
    protected $objModel;
    
    public function __construct() {
        $this->objModel = new LoanModel();
    }
    
    /**
     * 借款成功 创建还款与收款计划
     * @param integer $loanId
     * @return boolean
     */
    public function lendSuccess($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        if (empty($loan->id)) {
            return false;
        }
        
        $res = $this->objModel->beginTransaction();
        if (!res) {
            return false;
        }
        
        //创建还款计划
        $res = $this->buildRefunds($loanId);
        if (empty($res)) {
            $this->objModel->rollback();
            return false;
        }
        
        //循环创建收款计划
        $invests = Invest_Api::getLoanInvests($loanId);
        $invests = $invests['list'];
        foreach ($invests as $invest) {
            $res = Invest_Api::buildRefunds($invest['id']);
            
            if (empty($res)) {
                $this->objModel->rollback();
                return false;
            }
        }
        
        //更新状态
        $loan->status = Loan_Type_LoanStatus::PAYING;
        
        return $this->objModel->commit();
    }
    
    /**
     * 进行打款
     * @param unknown $loanId
     * @return boolean
     */
    public function sendMoney($loanId) {
        $invests = Invest_Api::getLoanInvests($loanId);
        $invests = $invests['list'];
        //循环组合打款参数
        foreach ($invests as $invest) {
            //
        }
        //进行打款
        return true;
    }
    
    public function addRefund($loan, $period, $capital, $interest, $time, $capital_refund, $capital_rest) {
        $refund = new Loan_Object_Refund();
        $refund->amount = $capital + $interest;
        $refund->interest = $interest;
        $refund->capital = $capital;
        $refund->lateCharge = 0;
        $refund->loanId = $loan['id'];
        $refund->period = $period;
        $refund->userId = $loan['user_id'];
        $refund->promiseTime = $time;
        return $refund->save();
    }
    
    /**
     * 获取借款基本信息
     * @param integer $loanId
     * @return array
     */
    public function getLoanInfo($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        $data = $loan->toArray();
        
        return $data;
    }
    
    /**
     * 获取借款的还款总额
     * @param number $loanId
     * @return number
     */
    public function getLoanRefundAmount($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        if ($loan->payTime > 0) {
            $start = $loan->payTime;
        } else {
            $start = time();
        }
        
        $duration = new Loan_Type_Duration();
        
        if ($loan->refundType == Loan_Type_RefundType::AVERAGE) {
            $days = $duration->getDays($loan->duration, $start);
            $interest = Loan_Type_RefundType::getInterest($loan->refundType, $loan->amount, $loan->interest, $days);
        } elseif ($loan->refundType == Loan_Type_RefundType::MONTH_INTEREST) {
            $months = $duration->getMonths($loan->duration);
            $interest = Loan_Type_RefundType::getInterest($loan->refundType, $loan->amount, $loan->interest, $months);
        }
        return $loan->amount + $interest;
    }
    
    /**
     * 格式化借款数据
     * @param array $data
     * @return array
     */
    private function formatLoan($data) {
        $data['amount'] = number_format($data['amount'], 2);
        $data['invest_amount'] = number_format($data['invest_amount'], 2);
        
        $duration = new Loan_Type_Duration();
        $data['duration_name'] = $duration->getTypeName($data['duration']);
        return $data;
    }
    
    /**
     * 创建还款计划
     * @param integer $loanId
     * @return boolean
     */
    public function buildRefunds($loanId) {
        $loan = $this->getLoanInfo($loanId);
        
        if ($loan['duration'] < 30) {
            $date = new DateTime('tomorrow');
            $date->modify('+' . $loan['duration'] . 'days');
            $time = $date->getTimestamp() - 1;
        
            $interest = $this->getInterestByDay($loan['amount'], $loan['interest'], $loan['duration']);
            return $this->addRefund($loan, 1, $loan['amount'], $interest, $time, 0, 0, $loan['amount']);
        }
        
        //超过30天 按月还款
        $periods = ceil($loan['duration'] / 30);
        if ($loan['refund_type'] == Invest_Type_RefundType::MONTH_INTEREST) {
            $date = new DateTime('tomorrow');
            $start = $date->getTimestamp() - 1;
            $capital_refund = 0;
            for ($period = 1; $period <= $periods; $period++) {
                $date->modify('+1month');
                $promise = $date->getTimestamp() - 1;
                $days = ($promise - $start) / 3600 / 24;
                $interest = $this->getInterestByDay($loan['amount'], $loan['interest'], $days);
                if ($period == $periods) {
                    $capital = $loan['amount'];
                } else {
                    $capital = 0;
                }
                $capital_rest = $loan['amount'] - $capital;
                $capital_refund += $capital;
                $res = self::addRefund($loan, $period, $capital, $interest, $promise, $capital_refund, $capital_rest);
        
                if (!$res) {
                    $msg = array(
                        'msg'    => 'add refund error',
                        'invest' => json_encode($loan),
                        'period' => $period,
                        'capital'=> 0,
                        'interest' => $interest,
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
            $a = $loan['amount'];
            $n = $periods;
            $capital_refund = 0;
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
                $interest = $a * $b * (pow(1 + $b, $periods) - pow(1 + $b, $period - 1)) / (pow(1 + $b, $periods) - 1);
                $capital = $a * $b * pow(1 + $b, $period - 1) / (pow(1 + $b, $periods) - 1);
                $capital_rest = $loan['amount'] - $capital;
                $capital_refund += $capital;
                $res = self::addRefund($loan, $period, $capital, $interest, $promise, $capital_refund, $capital_rest);
        
                if (!$res) {
                    $msg = array(
                        'msg'    => 'add refund error',
                        'invest' => json_encode($loan),
                        'period' => $period,
                        'capital'=> $capital,
                        'interest' => $interest,
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
     * 获取借款的还款计划
     * @param integer $loanId
     * @return array
     */
    public function getRefunds($loanId) {
        $loan = $this->getLoanInfo($loanId);
        $refunds = $this->getRefundsInDb($loanId);
        if (!empty($refunds)) {
            return $refunds;
        }
        
        if ($loan['duration'] < 30) {
            $date = new DateTime('tomorrow');
            $date->modify('+' . $loan['duration'] . 'days');
            $time = $date->getTimestamp() - 1;
        
            $interest = $this->getInterestByDay($loan['amount'], $loan['interest'], $loan['duration']);
            $amount = number_format($loan['amount'] + $interest, 2);
            return array(
                array(
                    'period' => 1,
                    'amount' => $amount,
                    'capital'=> $loan['amount'],
                    'interest'=> number_format($interest, 2),
                    'promise_time'  => $time,
                    'status'  => 1,
                ),
            );
        }
        
        //超过30天 按月还款
        $periods = ceil($loan['duration'] / 30);
        if ($loan['refund_type'] == Invest_Type_RefundType::MONTH_INTEREST) {
            $date = new DateTime('tomorrow');
            $start = $date->getTimestamp() - 1;
            $refunds = array();
            for ($period = 1; $period <= $periods; $period++) {
                $date->modify('+1month');
                $promise = $date->getTimestamp() - 1;
                $days = ($promise - $start) / 3600 / 24;
                $interest = $this->getInterestByDay($loan['amount'], $loan['interest'], $days);
                if ($period == $periods) {
                    $capital = $loan['amount'];
                } else {
                    $capital = 0;
                }
                $amount = number_format($capital + $interest, 2);
                
                $refund = array(
                    'period' => $period,
                    'amount' => $amount,
                    'capital'=> $capital,
                    'interest'=> number_format($interest, 2),
                    'promise_time'  => $promise,
                    'status'  => 1,
                );
                $refunds[] = $refund;
        
                $start = $promise;
            }
        } elseif ($loan['refund_type'] == Invest_Type_RefundType::AVERAGE) {
            $date = new DateTime('tomorrow');
            $start = $date->getTimestamp() - 1;
            $b = $loan['interest'] / 12;
            $a = $loan['amount'];
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
                $interest = $a * $b * (pow(1 + $b, $periods) - pow(1 + $b, $period - 1)) / (pow(1 + $b, $periods) - 1);
                $capital = $a * $b * pow(1 + $b, $period - 1) / (pow(1 + $b, $periods) - 1);
                $amount = number_format($capital + $interest, 2);
                
                $refund = array(
                    'period' => $period,
                    'amount' => $amount,
                    'capital'=> $capital,
                    'interest'=> number_format($interest, 2),
                    'promise_time'  => $promise,
                    'status'  => 1,
                );
                $refunds[] = $refund;
        
                $start = $promise;
            }
        }
        
        return $refunds;
    }
    
    /**
     * 从db中获取借款的还款计划
     * @param integer $loanId
     * @return array
     */
    private function getRefundsInDb($loanId) {
        $list = new Loan_List_Refund();
        $filters = array(
            'loan_id' => $loanId,
        );
        $list->setFilter($filters);
        $list->setPagesize(PHP_INT_MAX);
        $data = $list->toArray();
        
        $refunds = array();
        foreach ($data['list'] as $row) {
            $refunds[] = array(
                'period' => $row['period'],
                'amount' => $row['amount'],
                'capital'=> $row['capital'],
                'interest'=> $row['interest'],
                'promise_time'  => $row['promise_time'],
                'status'  => $row['status'],
            );
        }
        return $refunds;
    }
    
    /**
     * 按月付息 到期还本 按天计算的利息收入
     * @param number $amount
     * @param number $interest
     * @param number $days
     * @return number
     */
    private function getInterestByDay($amount, $interest, $days) {
        $money = $amount * $interest * $days / 365 / 100;
        return $money;
    }
}