<?php
/**
 * 按还款计划来还款
 */
class Loan_Logic_Refund {
    /**
     * 
     * 实施还款
     * @param $refundId
     * @param $loanId
     */
    public function doRefund($refundId, $loanId){
        
        $refundId = intval($refundId);
        $loanId   = intval($loanId);

        $objRst = new Base_Result();
        if ($loanId <=0 || $refundId <= 0) {
            $objRst->status = Base_RetCode::PARAM_ERROR;
            return $objRst;
        }

        $objLoanRefund = new Loan_Object_Refund(array('id'=>$refundId, 'loan_id'=>$loanId));
        if(!$objLoanRefund->isLoaded()){
            $objRst->status = Base_RetCode::PARAM_ERROR;
            return $objRst;
        }
        if($objLoanRefund->status === Loan_Type_Refund::REFUNDED){
            //已还款
            $objRst->status     = Loan_RetCode::UNABLE_REFUND;
            $objRst->statusInfo = Loan_RetCode::getMsg(Loan_RetCode::UNABLE_REFUND);
            return $objRst;
        }

        $promiseTime = $objLoanRefund->promiseTime;
        $outUserId   = $objLoanRefund->userId;

        $listInvestRefund = new Invest_List_Refund();
        $listInvestRefund->setFilter(array('loan_id'=>$loanId, 'promise_time'=>$promiseTime));
        $listInvestRefund->setPagesize(PHP_INT_MAX);
        $list   = $listInvestRefund->toArray();
        $bolRet = true;
        foreach($list['list'] as $arrInfo){
            $investRefundId = $arrInfo['id'];
            if(intval($arrInfo['capital'])==0){
                $transAmt = floatval($arrInfo['amount']) + floatval($arrInfo['late_charge']);
                $bolRet1 = Finance_Api::transfer($arrInfo['user_id'], $transAmt);
                Invest_Api::updateInvestRefundStatus($investRefundId, Invest_Type_RefundStatus::RETURNED);
            }else{
                $bolRet1 = $this->singleDoRefund($investRefundId, $outUserId, $arrInfo);
            }
            if(!$bolRet1){
                $bolRet  = false;
                break;
            }
        }
        
        //还款失败返回
        if (!$bolRet) {
            $objRst->status     = Loan_RetCode::REFUND_FAILED;
            $objRst->statusInfo = Loan_RetCode::getMsg(Loan_RetCode::REFUND_FAILED);
            Base_Log::error(array(
                'objRst' => $objRst,
                'loanId' => $loanId,
            ));
            return $objRst;
        }

        //设置还款成功状态
        $bolRet = Loan_Api::updateLoanRefundStatus($refundId, Loan_Type_Refund::REFUNDED);
        Base_Log::notice(array('msg'=>'设置还款成功状态','bolRet'=>$bolRet));

        if($bolRet){
            //通知还款人已还款
            $transAmt   = $objLoanRefund->amount+$objLoanRefund->lateCharge;
            $arrArgs    = array('JK_'.$loanId, $transAmt);
            $tplid      = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
            $objOutUser = User_Api::getUserObject($outUserId);
            Base_Sms::getInstance()->send($objOutUser->phone, $tplid[9], $arrArgs);

            //若最后一期还款计划结束则更新投资记录状态
            $list = new Loan_List_Refund();
            $list->setFilter(array('loan_id' => $loanId));
            $list->appendFilterString('status !=' . Loan_Type_Refund::REFUNDED);
            $total = $list->getTotal();
            $total = intval($total);
            Base_Log::notice(array('msg'=>'check是否最后一期还款计划', 'total'=>$total));
            if($total === 0){
                $bolRet2 = Loan_Api::updateLoanStatus($loanId, Loan_Type_LoanStatus::FINISHED);
                if(!$bolRet2){
                    Base_Log::error(array('msg'=>'更新借款状态失败',
                        'loanId'=>$loanId, 'bolRet2'=>$bolRet2));
                }
                $listInvest = new Invest_List_Invest();
                $listInvest->setFilter(array('loan_id' => $loanId));
                $list = $listInvest->toArray();
                foreach ($list['list'] as $arrInfo) {
                    $investId = $arrInfo['id'];
                    Invest_Api::updateInvestStatus($investId, Invest_Type_InvestStatus::FINISHED);
                }
            }
        }

        $objRst->status = Base_RetCode::SUCCESS;

        Base_Log::notice(array(
            'msg'    => '还款计划还款成功',
            'loanId' => $loanId,
        ));
        
        return $objRst;
    }

    /**
     * 单笔还款，成功则修改invest_refund纪录状态为结束
     */
    private function singleDoRefund($refundId, $outUserId, $arrInfo){
        $bolRet   = false;
        $loanId   = $arrInfo['loan_id'];
        $investId = $arrInfo['invest_id'];
        $inUserId = $arrInfo['user_id'];
        $transAmt = floatval($arrInfo['amount']) + floatval($arrInfo['late_charge']);
        $intSt    = intval($arrInfo['status']);
        //是否需要还款
        if($intSt === Invest_Type_RefundStatus::RETURNED){
            $bolRet = true;
            Base_Log::debug(array(
                'msg'    => '该笔投资已还款状态，不需要再还款',
                'info'   => $arrInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        //获取投资订单号
        $objInvest = new Invest_Object_Invest($investId);
        $subOrdId  = $objInvest->orderId;
        $mangFee   = 0.00; //预留还款管理费（一般逾期情况）

        //通知财务还款
        $arrRet = Finance_Api::repayment($refundId,$outUserId,$inUserId,$subOrdId,$transAmt,$loanId,$mangFee);
        if(Base_RetCode::SUCCESS !== $arrRet['status']){
            $bolRet = false;
            Base_Log::error(array(
                'msg'    => '财务还款失败',
                'info'   => $arrInfo,
                'arrRet' => $arrRet,
            ));
            return $bolRet;
        }

        //单笔还款成功，更新回款计划字段
        /*$bolRet = Invest_Api::updateInvestRefundStatus($refundId, Invest_Type_RefundStatus::RETURNED);
        if(!$bolRet){
            Base_Log::error(array(
                'msg'    => '更新投资回款计划状态失败',
                'info'   => $arrInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        Base_Log::notice(array(
            'msg'       => '单笔还款成功',
            'loanId'    => $loanId,
            'outUserId' => $outUserId,
            'info'      => $arrInfo,
            'bolRet'    => $bolRet,
        ));

        //投资人回款短信通知
        $arrArgs    = array('JK_'.$loanId,$transAmt,$arrInfo['capital'],$arrInfo['interest']);
        $tplid      = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $objOutUser = User_Api::getUserObject($inUserId);
        Base_Sms::getInstance()->send($objOutUser->phone, $tplid[6], $arrArgs);
        */

        return true;
    }


    /**
     * 创建还款计划
     * @param integer $loanId
     * @return boolean
     */
    public function buildRefunds($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        $loan = $loan->toArray();        
        if ($loan['duration'] < 30) {
            $date        = new DateTime('tomorrow');
            $date->modify('+' . $loan['duration'] . 'days');
            $promiseTime = $date->getTimestamp() - 1;
            $interest    = $this->getInterestByDay($loan['amount'], $loan['interest'], $loan['duration']);
            //$loan, $period, $capital, $interest, $promiseTime, $capital_refund, $capital_rest           
            return self::addRefund($loan, 1, $loan['amount'], $interest, $promiseTime, 0, $loan['amount']);
        }
        
        //超过30天 按月还款
        $periods = ceil($loan['duration'] / 30);
        if ($loan['refund_type'] == Invest_Type_RefundType::MONTH_INTEREST) {
            $date           = new DateTime('tomorrow');
            $start          = $date->getTimestamp() - 1;
            $capital_refund = 0;
            for ($period = 1; $period <= $periods; $period++) {
                $date->modify('+1month');
                $promise  = $date->getTimestamp() - 1;
                $days     = ($promise - $start) / 3600 / 24;
                $interest = $this->getInterestByDay($loan['amount'], $loan['interest'], $days);
                if ($period == $periods) {
                    $capital = $loan['amount'];
                } else {
                    $capital = 0;
                }
                $capital_rest   = $loan['amount'] - $capital;
                $capital_refund += $capital;               
                $res            = self::addRefund($loan, $period, $capital, $interest, 
                    $promise, $capital_refund, $capital_rest);
        
                if (!$res) {
                    $msg = array(
                        'msg'      => 'add refund error',
                        'invest'   => json_encode($loan),
                        'period'   => $period,
                        'capital'  => 0,
                        'interest' => $interest,
                        'promise'  => $promise,
                    );
                    Base_Log::error($msg);
                    return false;
                }
                $start = $promise;
            }
        } elseif ($loan['refund_type'] == Invest_Type_RefundType::AVERAGE) {
            $date           = new DateTime('tomorrow');
            $start          = $date->getTimestamp() - 1;
            $b              = $loan['interest'] / 12;
            $a              = $loan['amount'];
            $n              = $periods;
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
                $promise        = $date->getTimestamp() - 1;
                $days           = ($promise - $start) / 3600 / 24;
                $interest       = $a * $b * (pow(1 + $b, $periods) - pow(1 + $b, $period - 1))
                    / (pow(1 + $b, $periods) - 1);
                $capital        = $a * $b * pow(1 + $b, $period - 1) / (pow(1 + $b, $periods) - 1);
                $capital_rest   = $loan['amount'] - $capital;
                $capital_refund += $capital;
                $res            = self::addRefund($loan, $period, $capital, $interest, $promise, 
                    $capital_refund, $capital_rest);
        
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
        $loan = new Loan_Object_Loan($loanId);
        $loan = $loan->toArray();

        $refunds = $this->getRefundsInDb($loanId);
        if (!empty($refunds)) {
            return $refunds;
        }
        
        if ($loan['duration'] < 30) {
            $date = new DateTime('tomorrow');
            $date->modify('+' . $loan['duration'] . 'days');
            $time = $date->getTimestamp() - 1;
        
            //TODO 金额需要核对正确
            $interest = $this->getInterestByDay($loan['amount'], $loan['interest'], $loan['duration']);
            $amount = $loan['amount'] + $interest;
            return array(
                array(
                    'period' => 1,
                    'amount' => $amount,
                    'capital'=> $loan['amount'],
                    'interest'=> $interest,
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
                    $capital = intval($loan['amount']);
                } else {
                    $capital = 0;
                }
                $amount = $capital + $interest;
                if ($period == $periods) {
                    $interest = $amount;
                }
                
                $refund = array(
                    'period' => $period,
                    'amount' => $amount,
                    'capital'=> $capital,
                    'interest'=> $interest,
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
                $amount = $capital + $interest;
                
                $refund = array(
                    'period' => $period,
                    'amount' => $amount,
                    'capital'=> $capital,
                    'interest'=> $interest,
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
     * 根据用户获取最近待回款
     */
    public function getActiveRefunds($userId){
        $list = new Loan_List_Refund();
        $filters = array(
            'user_id' => $userId,
        );
        $list->setFilter($filters);
        $list->appendFilterString('status !=' . Loan_Type_Refund::REFUNDED);
        $list->setPagesize(PHP_INT_MAX);
        $list->setOrder('id asc');
        $data = $list->toArray();
        
        $refunds = array();
        foreach ($data['list'] as $row) {
            $refunds[] = array(
                'period'       => $row['period'],
                'amount'       => $row['amount'],
                'capital'      => $row['capital'],
                'interest'     => $row['interest'],
                'promise_time' => $row['promise_time'],
                'status'       => $row['status'],
            );
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
        $list->setOrder('id asc');
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

    /**
     * 添加借款人还款计划
     * 
     */
    private static function addRefund($loan, $period, $capital, $interest, $promise_time, $capital_refund, $capital_rest) {
        $refund                = new Loan_Object_Refund();
        $refund->amount        = $capital + $interest;
        $refund->interest      = $interest;
        $refund->capital       = $capital;
        $refund->lateCharge    = 0;
        $refund->loanId        = $loan['id'];
        $refund->period        = $period;
        $refund->userId        = $loan['user_id'];
        $refund->promiseTime   = $promise_time;
        $refund->capitalRefund = $capital_refund; //'已回收本金',
        $refund->capitalRest   = $capital_rest;   //'剩余本金',
        return $refund->save();
    }

}
