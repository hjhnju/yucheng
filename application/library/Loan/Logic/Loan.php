<?php
class Loan_Logic_Loan {
    
    protected $objModel;
    
    public function __construct() {
        $this->objModel = new LoanModel();
    }

    /**
     * 发布借款, 默认发布后状态为立即开启，
     * @param integer $loanId 借款ID
     * @param integer $isOpen 默认发布后状态为“立即开启”，＝1时开启投标
     * @return Base_Result
     */
    public function publish($loanId, $isOpen = 0, $days = 7) {

        $objRst = new Base_Result();

        $db = Base_Db::getInstance('xjd');
        $db->beginTransaction();

        $loan = new Loan_Object_Loan($loanId);
        //时间戳投标开始时间(选用创建时设定的起至时间)
        $loan->startTime = time();
        //时间戳投标截止时间
        $loan->deadline  = time() + $days * 24 * 3600;
        
        //项目所在地
        $area    = new Area_Object_Area($loan->area);
        $proArea = $area->huifuCityid;
        //应还款日期
        $duration = new Loan_Type_Duration();
        $retDate  = $duration->getTimestamp($loan->duration, $loan->deadline);
        //总还款金额
        $retAmt  = Loan_Api::getLoanRefundAmount($loanId);

        if($isOpen >= 1){
            $loan->status = Loan_Type_LoanStatus::LENDING;
            //调用财务API进行借款录入
            $arrRst  = Finance_Api::addBidInfo($loanId, $loan->userId, $loan->amount, 
            $loan->interest/100, $loan->refundType, $loan->startTime, 
            $loan->deadline, $retAmt, $retDate, $proArea);
            if ($arrRst['status'] !== Base_RetCode::SUCCESS) {
                $objRst->status     = $arrRst['status'];
                $objRst->statusInfo = $arrRst['statusInfo'];
                Base_Log::warn($arrRst);
                return $objRst;
            }
            //订单号
            $loan->orderId = intval($arrRst['data']['orderId']);
        }else if($isOpen <= -1) {
            $loan->status = Loan_Type_LoanStatus::AUDIT;
        }else if($isOpen === 0) {
            $loan->status = Loan_Type_LoanStatus::WAITING;
        }

        if (!$loan->save()) {
            $objRst->status     = Loan_RetCode::LOAN_SAVE_FAIL;
            $objRst->statusInfo = Loan_RetCode::getMsg(Loan_RetCode::LOAN_SAVE_FAIL);
            return $objRst;
        }

        $db->commit();

        $objRst->status = Base_RetCode::SUCCESS;
        return $objRst;
    }

    /**
     * 满标打款
     * @param $loanId 
     */
    public function makeLoans($loanId){
        $objRst = new Base_Result();
        if (empty($loanId)) {
            $objRst->status     = Basele_RetCode::getMsg($objRst->status);
            return $objRst->format();
        }

        $logic       = new Loan_Logic_Loan();
        $arrLoanInfo = $logic->getLoanInfo($loanId);
        $bolRet      = true;
        if ($arrLoanInfo['status'] !== Loan_Type_LoanStatus::FULL_PAYING) {
            $objRst->status     = Loan_RetCode::UNABLE_MAKE_LOAN;
            $objRst->statusInfo = Loan_RetCode::getMsg($objRst->status);
            Base_Log::notice(array(
                'msg'    => '该笔投资无需再打款',
                'info'   => $arrLoanInfo,
                'bolRet' => $bolRet,
            ));
            return $objRst;
        }

        //借款人（入款用户）
        $inUserId = intval($arrLoanInfo['user_id']);
        //获取该项目所有投资
        $arrRet   = Invest_Api::getLoanInvests($loanId);
        foreach($arrRet['list'] as $arrInfo){

            $bolRet1 = $this->singleMakeLoans($loanId, $inUserId, $arrInfo);
            $bolRet  = $bolRet && $bolRet1;
        }
        
        if (!$bolRet) {
            $objRst->status     = Loan_RetCode::MAKE_LOAN_FAIL;
            $objRst->statusInfo = Loan_RetCode::getMsg(Loan_RetCode::MAKE_LOAN_FAIL);
            $content            = "给客户打款失败";
            Base_Log::error(array(
                'msg'    => $content,
                'loanId' => $loanId,
            ));
            return $objRst;
        }

        Loan_Api::updateLoanStatus($loanId, Loan_Type_LoanStatus::REFUNDING);

        $objRst->status = Base_RetCode::SUCCESS;
        $content = "给客户打款成功";
        Base_Log::notice(array(
            'msg'    => $content,
            'loanId' => $loanId,
        ));
        $this->addLog($loanId, $content);
        return $objRst;
    }

    /**
     * 单笔打款失败
     */
    private function singleMakeLoans($loanId, $inUserId, $arrInvestInfo){
        $bolRet = false;

        $investId      = $arrInvestInfo['id'];
        $subOrdId      = $arrInvestInfo['order_id'];
        $outUserId     = $arrInvestInfo['user_id'];
        $transAmt      = $arrInvestInfo['amount'];
        $singlePayStat = $arrInvestInfo['status'];
        //是否需要打款
        if($singlePayStat >= Invest_Type_InvestStatus::REFUNDING){
            $bolRet = true;
            Base_Log::debug(array(
                'msg'    => '该笔投资已成功或结束，不能再打款',
                'info'   => $arrInvestInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        //通知财务打款
        $arrRet = Finance_Api::loans($loanId, $subOrdId, $inUserId, $outUserId, $transAmt);
        if(Base_RetCode::SUCCESS !== $arrRet['status']){
            $bolRet = false;
            Base_Log::error(array(
                'msg'    => '财务满标打款单笔失败',
                'info'   => $arrInvestInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        //单笔打款成功，更新投资人已打款字段
        $bolRet = Invest_Api::updateInvestStatus($investId, Invest_Type_InvestStatus::REFUNDING);
        if(!$bolRet){
            Base_Log::error(array(
                'msg'    => '更新打款状态失败',
                'info'   => $arrInvestInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        Base_Log::notice(array(
            'msg'    => '满标打款单笔成功',
            'info'   => $arrInvestInfo,
            'bolRet' => $bolRet,
        ));
        //短信通知
        $arrArgs    = array('JK_'.$loanId, $transAmt);
        $tplid      = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $objOutUser = User_Api::getUserObject($outUserId);
        $bResult    = Base_Sms::getInstance()->send($objOutUser->phone, $tplid[4], $arrArgs);

        return $bolRet;
    }

    /**
     * 添加借款日志记录
     * @param integer $loanId
     * @param string $content
     * @return boolean
     */
    public function addLog($loanId, $content) {
        $log          = new Loan_Object_Log();
        $log->loanId  = $loanId;
        $log->ip      = Base_Util_Ip::getClientIp();
        $log->content = $content;
        //userId是谁
        $log->userId  = 0;
        return $log->save();
    }
    
    /**
     * 借款成功 创建还款与收款计划
     * @param integer $loanId
     * @return boolean
     */
    public function lendSuccess($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        if (empty($loan->id)) {
            Base_Log::error(array(
               'loanid' => $loanId,
               'loanid2' => $loan->id,
	    ));
            return false;
        }
        
        $res = $this->objModel->beginTransaction();
        if (!$res) {
            return false;
        }
        
        //创建还款计划
        $res = $this->buildRefunds($loanId);
        if (empty($res)) {
	    Base_Log::error(array(
               'res' => $res,
               'loanid' => $loanId,
	    ));
            $this->objModel->rollback();
            return false;
        }
        
        //循环创建收款计划
        $invests = Invest_Api::getLoanInvests($loanId);
        $invests = $invests['list'];
        foreach ($invests as $invest) {
            $res = Invest_Api::buildRefunds($invest['id']);
            
            if (empty($res)) {
                Base_Log::error(array(
		    'res' => $res,
		    'loanid' => $loanId,
		    'investid' => $invest['id'],
                ));
                $this->objModel->rollback();
                return false;
            }
        }
        
        //更新状态
        $loan->status = Loan_Type_LoanStatus::REFUNDING;
        
        return $this->objModel->commit();
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
        
        if ($loan->refundType === Loan_Type_RefundType::AVERAGE) {
            $months = $duration->getMonths($loan->duration);
            $interest = Loan_Type_RefundType::getInterest($loan->refundType, $loan->amount, $loan->interest, $months);
        } elseif ($loan->refundType === Loan_Type_RefundType::MONTH_INTEREST) {
            $days = $duration->getDays($loan->duration, $start);
            $interest = Loan_Type_RefundType::getInterest($loan->refundType, $loan->amount, $loan->interest, $days);
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
    private function buildRefunds($loanId) {
        $loan = $this->getLoanInfo($loanId);
        
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
        $loan = $this->getLoanInfo($loanId);
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
            $amount = number_format($loan['amount'] + $interest, 2);
            return array(
                array(
                    'period' => 1,
                    'amount' => $amount,
                    'capital'=> $loan['amount'],
                    'interest'=> $amount,
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
                $amount = number_format($capital + $interest, 2);
                if ($period == $periods) {
                    $interest = $amount;
                } else {
                    $interest = number_format($interest, 2);
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
