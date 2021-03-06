<?php
/**
 * 投资模块API接口
 * @author jiangsongfang
 *
 */
class Invest_Api {

    /**
     * 主动投标后，财务回调投标模块进行确定投标
     * @author hejunhua
     */
    public static function doInvest($orderId, $userid, $loanId, $amount){
        $logic  = new Invest_Logic_Invest();
        $bolRet = $logic->doInvest($orderId, $userid, $loanId, $amount);
        if (!$bolRet) {
            Base_Log::error(array(
                'msg'     => '投资确认失败',
                'orderId' => $orderId,
                'userid'  => $userid,
                'loanId'  => $loanId,
                'amount'  => $amount,
            ));
        }
        return $bolRet;
    }
    
    /**
     * 投标时创建的分享收益信息
     * @param integer $orderId
     * @param integer $userId
     * @param integer $proId
     * @param float $transAmt
     * @param integer $toId
     * @param float $rate
     * @return boolean
     */
    public static function shareInvest($orderId, $userId, $proId, $transAmt, $toId,$rate){
        $obj_invest = new Invest_Object_Invest();
        $obj_invest->fetch(array('loan_id'=>$proId,'user_id'=>$userId,'order_id'=>$orderId));
        $investId = $obj_invest->id;
        
        $loan = Loan_Api::getLoanInfo($proId);
        $type = $loan['refund_type'];
        $date           = new DateTime('tomorrow');
        $start          = $date->getTimestamp() - 1;
        $capital_refund = 0;
        Base_Log::notice(array(
                        'msg'      => '测试多月',
                        'duration' => $loan['duration'],
                        'type'     => $type,
                        'proId'    => $proId,
                        'transAmt' => $transAmt,
                    ));
        if($type == Loan_Type_RefundType::MONTH_INTEREST || $loan['duration']<30){
            if($loan['duration']>= 30){
                $periods = ceil($loan['duration'] / 30);
                $date->modify('+'.$periods.' month');
            }else{
                $date->modify('+'.$loan['duration'].' day');
            }
            $promise  = $date->getTimestamp() - 1;
            $days     = ($promise - $start) / 3600 / 24;
            $income = self::getInterestByDay($transAmt, $rate, $days);
        }elseif($type == Loan_Type_RefundType::AVERAGE){
            $date = new DateTime('tomorrow');
            $periods = ceil($loan['duration'] / 30);
            $start = $date->getTimestamp() - 1;
            $b = $rate/100/12;
            $a = $transAmt;           
            $income   = ($a * $b * (pow(1 + $b, $periods)) / (pow(1 + $b, $periods) - 1)-$a/$periods)*$periods;                     
        }
        
        $obj_share = new Invest_Object_Share();
        $obj_share->loanId     = $proId;
        $obj_share->investId   = $investId;
        $obj_share->fromUserid = $userId;
        $obj_share->toUserid   = $toId;
        $obj_share->rate       = $rate;
        $obj_share->income     = $income;
        $bRet = $obj_share->save();
        return $bRet;
    }

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
     * @param number $uid
     * @param mix $mixStatus, 可以是 -1/array/int
     *  -1全部 1审核中 2投标中 3放款审核 4打款中 5回款中 6已完成 9失败
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
    public static function getUserInvests($uid, $mixStatus = -1, $page = 1, $pagesize = 10) {
        $logic = new Invest_Logic_Invest();
        $data = $logic->getUserInvests($uid, $mixStatus, $page, $pagesize);
        Base_Log::debug(array(
            'uid'      => $uid,
            'status'   => $mixStatus,
            'data' => $data,
        ));
        return $data;
    }
    
    /**
     * 获取用户一段时间的投资总额
     * @param number $uid
     * @param number $startTime
     * @param number $endTime
     * @return number
     */
    public static function getUserInvestTotal($uid, $startTime = 0, $endTime = 0) {
        $startTime = empty($startTime) ? strtotime('-3months') : $startTime;
        $endTime = empty($endTime) ? time() : $endTime;

        $logic = new Invest_Logic_Invest();
        $total = $logic->getUserInvestTotal($uid, $startTime, $endTime);
        return floatval($total);
    }
    
    /**
     * 获取我的投资收益
     * @param integer $uid
     * @return array <pre>(
     *       'all_invest' => $all,
     *       'all_income' => $incomes,
     *       'wait_capital' => $capital,
     *       'wait_interest' => $interest,
     *   );
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
     *       '2014-09' => 100,
     *       '2014-10' => 200,
     *   );
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
    public static function getRefunds($invest_id,$user_id=0) {
        $refunds = new Invest_List_Refund();
        $filters = array(
            'invest_id' => $invest_id,
        );
        if(!empty($user_id)){
            $filters['user_id'] = $user_id;
        }
        $refunds->setFilter($filters);
        $refunds->setOrder('id asc');
        $refunds->setPagesize(PHP_INT_MAX);
        $list = $refunds->toArray();
        
        $data = $list['list'];
        Base_Log::notice(array(
            'invest_id' => $invest_id,
            'data' => $data,
        ));
        return $data;
    }

   /**
     * 根据id获取收款计划
     * @param integer $refundId
     * @return array
     */
    public static function getRefundById($refundId) {
        $refund = new Invest_Object_Refund($refundId);
        Base_Log::notice(array('msg'=>'refund行记录','row'=>$refund->toArray()));
        return $refund->toArray();
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
     * 获取用户的帐户可用余额
     * @param integer $uid
     * @return number
     */
    public static function getAccountAvlBal($uid) {
        $logic  = new Invest_Logic_Invest();
        return $logic->getAccountAvlBal($uid);
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
     * @param array $filter 过滤参数 目前支持type cat period
     * array('type' => 1, 'cat' => 1, period => 1);
     * @return array
     * array(
     *      'page' => 1,
     *      'pagesize' => 10,
     *      'pageall' => 100,
     *      'list' => array(),
     * )
     */
    public static function getInvestList($page = 1, $pagesize = 10, $filter = array()) {
        $logic = new Invest_Logic_Invest();
        $list = $logic->getInvestList($page, $pagesize, $filter);
         
        $status = new Invest_Type_InvestStatus();
        $level  = new Invest_Type_LevelName();
        foreach ($list['list'] as $key => $val) {
            // 对于投标时间已经结束的 进行修正
            if ($val['status'] == Invest_Type_InvestStatus::LENDING && $val['deadline'] < time()) {
                $list['list'][$key]['status'] = Invest_Type_InvestStatus::FAILED;
            }
            $list['list'][$key]['status_name'] = $status->getTypeName($list['list'][$key]['status']);
            $list['list'][$key]['level_name'] = $level->getTypeName($val['level']);
        }
        return $list;
    }
    
    /**
     * 更新投标的状态
     * @param integer $investId
     * @param integer $status
     * @return boolean
     */
    public static function updateInvestStatus($investId, $status) {
        $invest = new Invest_Object_Invest($investId);
        $invest->status = $status;
        $res = $invest->save();
        if ($res) {
            $type = new Loan_Type_LoanStatus();
            $content = "更新投标[{$investId}]状态为" . $type->getTypeName($status);
            Loan_Api::addLog($invest->loanId, $content);
        }
        return $res;
    }

    /**
     * 更新投资回款状态
     * @param integer $refundId
     * @param integer $status
     * @return boolean
     */
    public static function updateInvestRefundStatus($refundId, $status) {
        $refund = new Invest_Object_Refund($refundId);
        $refund->status = $status;
        if(Invest_Type_RefundStatus::RETURNED === $status){
            //已还本金＝待还本金
            $refund->capitalRefund = $refund->capitalRest;
            $refund->refundTime    = time();
        }
        $res = $refund->save();
        if($res){
            self::updateInvestInfo($refund->investId);
        }
        return $res;
    }

    public static function updateInvestInfo($investId){
        $logic = new Invest_Logic_Invest();
        $bolRet = $logic->updateInvestInfo($investId);
        return $bolRet;
    }
    
    /**
     * 创建收款计划
     * @param integer $invest_id
     * @param boolean
     */
    public static function buildRefunds($invest_id) {
        $invest = self::getInvest($invest_id);
        $loan   = Loan_Api::getLoanInfo($invest['loan_id']);
        $invest_share = new Invest_Object_Share();
        $invest_share->fetch(array('invest_id'=>$invest_id));
        if(!empty($invest_share->id)){
            $loan['interest'] -= $invest_share->rate;
        }
        if ($loan['duration'] < 30) {
            $date = new DateTime('tomorrow');
            $date->modify('+' . $loan['duration'] . 'days');
            $promise = $date->getTimestamp() - 1;
            
            $income = self::getInterestByDay($invest['amount'], $invest['interest'], $loan['duration']);
            //$invest, $period, $capital, $income, $promiseTime\
            if(!empty($invest_share->id)){
                $shareincome = self::getInterestByDay($invest['amount'], $invest_share->rate, $loan['duration']);
                $shareInvest['id']      = $invest_id;
                $shareInvest['loan_id'] = $invest_share->loanId;
                $shareInvest['user_id'] = $invest_share->toUserid;
                self::addRefund($shareInvest, 1, 0, $shareincome, $promise);
            }
            return self::addRefund($invest, 1, $invest['amount'], $income, $promise);
        }
        
        //超过30天 按月还款
        $periods = ceil($loan['duration'] / 30);
        if ($loan['refund_type'] == Invest_Type_RefundType::MONTH_INTEREST) {
            $date  = new DateTime('tomorrow');
            $start = $date->getTimestamp() - 1;
            for ($period = 1; $period <= $periods; $period++) {
                $date->modify('+1month');
                $promise = $date->getTimestamp() - 1;
                $days    = ($promise - $start) / 3600 / 24;
                $income  = self::getInterestByDay($invest['amount'], $loan['interest'], $days);
                if ($period == $periods) {
                    $capital = $invest['amount'];
                } else {
                    $capital = 0;
                }
                if(!empty($invest_share->id)){
                    $shareincome = self::getInterestByDay($invest['amount'], $invest_share->rate, $days);
                    $shareInvest['id']      = $invest_id;
                    $shareInvest['loan_id'] = $invest_share->loanId;
                    $shareInvest['user_id'] = $invest_share->toUserid;
                    self::addRefund($shareInvest, $period, 0, $shareincome, $promise);
                }
                $res = self::addRefund($invest, $period, $capital, $income, $promise);
                
                if (!$res) {
                    $msg = array(
                        'msg'     => 'add refund error',
                        'invest'  => json_encode($invest),
                        'period'  => $period,
                        'capital' => 0,
                        'income'  => $income,
                        'promise' => $promise,
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
                $days    = ($promise - $start) / 3600 / 24;
                $income  = $a * $b * (pow(1 + $b, $periods) - pow(1 + $b, $period - 1)) / (pow(1 + $b, $periods) - 1);
                $capital = $a * $b * pow(1 + $b, $period - 1) / (pow(1 + $b, $periods) - 1);
                if(!empty($invest_share->id)){
                    $c = $invest_share->rate/12;
                    $income  = $a * $c * (pow(1 + $c, $periods) - pow(1 + $c, $period - 1)) / (pow(1 + $c, $periods) - 1);
                    $shareInvest['id']      = $invest_id;
                    $shareInvest['loan_id'] = $invest_share->loanId;
                    $shareInvest['user_id'] = $invest_share->toUserid;
                    self::addRefund($shareInvest, $period, 0, $shareincome, $promise);
                }
                $res     = self::addRefund($invest, $period, $capital, $income, $promise);
                
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
     * @param unknown $capital, 本金
     * @param unknown $income, 利息
     * @param unknown $promiseTime
     * @return boolean
     */
    private static function addRefund($invest, $period, $capital, $income, $promiseTime) {
        $refund                = new Invest_Object_Refund();
        $refund->amount        = $capital + $income; //应还本息
        $refund->interest      = $income;  //利息
        $refund->capital       = $capital; //本金
        $refund->investId      = $invest['id'];
        $refund->lateCharge    = 0;
        $refund->loanId        = $invest['loan_id'];
        $refund->period        = $period;
        $refund->userId        = $invest['user_id'];
        $refund->promiseTime   = $promiseTime; //应还日期
        $refund->capitalRefund = 0;        //'已回收本金',
        $refund->capitalRest   = $capital; //'剩余本金',
        $refund->transfer      = 0;        //已还款标记:0-尚未还款
        return $refund->save();
    }
    
    /**
     * 按月付息 到期还本 按天计算的利息收入
     * @param number $amount
     * @param number $interest
     * @param number $days
     * @return number
     */
    public static function getInterestByDay($amount, $interest, $days) {
        $money = $amount * $interest * $days / 365 / 100;
        return $money;
    }

    /**
     * 判断用户是否已投资
     * @param  array $arrUid 用户列表
     * @return array array('userid'=>0|1)
     */
    public static function checkIsInvested($arrUid){
        $list = new Invest_List_Invest();
        $list->setFields(array('user_id'));
        $list->setFilterString('user_id IN (' . implode(',', $arrUid) . ')');
        $list->setPagesize(PHP_INT_MAX);
        $list = $list->toArray();
        $arrRet = array_fill_keys($arrUid, 0);
        foreach ($list['list'] as $row) {
            $arrRet[$row['user_id']] = 1;
        }
        return $arrRet;

    }


}
